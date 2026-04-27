<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['data']) || !isset($input['program'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data format']);
    exit;
}

$program = trim((string)$input['program']);
$rows = $input['data'];

function parseExcelDate($value): ?string {
    if (empty($value)) return null;

    if (is_numeric($value) && (int)$value > 1000) {
        $unix = ((int)$value - 25569) * 86400;
        return date('Y-m-d', $unix);
    }

    $ts = strtotime((string)$value);
    return $ts ? date('Y-m-d', $ts) : null;
}

function tableExists(mysqli $conn, string $table): bool {
    $stmt = $conn->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1');
    $stmt->bind_param('s', $table);
    $stmt->execute();
    return (bool)$stmt->get_result()->fetch_assoc();
}

function tableHasColumn(mysqli $conn, string $table, string $column): bool {
    static $cache = [];
    $key = strtolower($table . '|' . $column);
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $stmt = $conn->prepare('SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ? LIMIT 1');
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $exists = (bool)$stmt->get_result()->fetch_assoc();
    $cache[$key] = $exists;
    return $exists;
}

function firstExistingColumn(mysqli $conn, string $table, array $candidates): ?string {
    foreach ($candidates as $column) {
        if (tableHasColumn($conn, $table, $column)) {
            return $column;
        }
    }
    return null;
}

function isWhipProjectsProgram(string $program): bool {
    return in_array($program, [
        'Workers Hiring for Infrastructure Projects - Projects',
        'Workers Hiring for Infrastructure Projects — Projects',
    ], true);
}

function normalizeKeyText($value): string {
    $text = strtolower(trim((string)($value ?? '')));
    if ($text === '') return '';
    $text = preg_replace('/[\s\t\r\n]+/', ' ', $text);
    $text = preg_replace('/[^a-z0-9 ]+/', ' ', $text);
    return trim(preg_replace('/\s+/', ' ', $text));
}

function normalizeMoneyValue($value): string {
    $raw = trim((string)($value ?? ''));
    if ($raw === '') return '';
    $clean = preg_replace('/[^0-9.\-]/', '', $raw);
    if ($clean !== '' && is_numeric($clean)) {
        return number_format((float)$clean, 2, '.', '');
    }
    return normalizeKeyText($raw);
}

function whipProjectRowFields(array $row): array {
    return [
        'title'      => trim((string)($row['Project Title / Name of Implementing Partner'] ?? $row['Project Title'] ?? $row['Project Name'] ?? '')),
        'contractor' => trim((string)($row['Project Contractor'] ?? $row['Company'] ?? '')),
        'duration'   => trim((string)($row['Duration'] ?? '')),
        'budget'     => trim((string)($row['Budget'] ?? '')),
        'fund'       => trim((string)($row['Fund Source'] ?? '')),
        'nature'     => trim((string)($row['Nature of Project'] ?? '')),
    ];
}

function joinRowValue(string $existing, string $addition): string {
    $existing = trim($existing);
    $addition = trim($addition);
    if ($addition === '') return $existing;
    if ($existing === '') return $addition;

    $parts = array_map('trim', explode('|', $existing));
    foreach ($parts as $p) {
        if (strcasecmp($p, $addition) === 0) {
            return $existing;
        }
    }

    return $existing . ' | ' . $addition;
}

function collapseWhipProjectRows(array $rows): array {
    $collapsed = [];
    $currentIndex = -1;

    foreach ($rows as $row) {
        $title = trim((string)($row['Project Title / Name of Implementing Partner'] ?? ''));
        $contractor = trim((string)($row['Project Contractor'] ?? $row['Company'] ?? ''));
        $nature = trim((string)($row['Nature of Project'] ?? ''));
        $duration = trim((string)($row['Duration'] ?? ''));
        $budget = trim((string)($row['Budget'] ?? ''));
        $fund = trim((string)($row['Fund Source'] ?? ''));
        $jobs = trim((string)($row['Jobs Generated'] ?? ''));
        $persons = trim((string)($row['No. of Persons Employed from the Locality'] ?? ''));
        $legit = trim((string)($row['Legitimate Contractors (YES or NO)'] ?? ''));
        $filled = trim((string)($row['Filled'] ?? ''));
        $unfilled = trim((string)($row['Unfilled'] ?? ''));
        $skills = trim((string)($row['Skills Required for the Job'] ?? ''));
        $skillsDef = trim((string)($row['Skills Deficiencies'] ?? ''));

        $hasAnchor = ($title !== '' || $contractor !== '' || $nature !== '' || $duration !== '' || $budget !== '' || $fund !== '' || $jobs !== '' || $persons !== '' || $legit !== '' || $filled !== '' || $unfilled !== '');
        $isContinuation = !$hasAnchor && ($skills !== '' || $skillsDef !== '');

        if ($isContinuation && $currentIndex >= 0) {
            $existingSkills = (string)($collapsed[$currentIndex]['Skills Required for the Job'] ?? '');
            $existingDef = (string)($collapsed[$currentIndex]['Skills Deficiencies'] ?? '');

            $collapsed[$currentIndex]['Skills Required for the Job'] = joinRowValue($existingSkills, $skills);
            $collapsed[$currentIndex]['Skills Deficiencies'] = joinRowValue($existingDef, $skillsDef);
            continue;
        }

        $collapsed[] = $row;

        if ($hasAnchor) {
            $currentIndex = count($collapsed) - 1;
        }
    }

    return $collapsed;
}

function buildWhipProjectDuplicateKey(array $row): ?string {
    $f = whipProjectRowFields($row);
    if ($f['title'] === '' || $f['contractor'] === '') {
        return null;
    }

    return implode('|', [
        normalizeKeyText($f['title']),
        normalizeKeyText($f['contractor']),
        normalizeKeyText($f['duration']),
        normalizeMoneyValue($f['budget']),
        normalizeKeyText($f['fund']),
    ]);
}

function getWhipProjectsShape(mysqli $conn): array {
    static $shape = null;
    if ($shape !== null) return $shape;

    $tables = [
        'projects',
        'whip_projects',
        'whipProject',
        'whip_project',
        'workers_hiring_projects',
        'workers_infra_projects',
        'infrastructure_projects',
    ];

    $table = null;
    foreach ($tables as $candidate) {
        if (tableExists($conn, $candidate)) {
            $table = $candidate;
            break;
        }
    }

    if ($table === null) {
        $shape = ['table' => null];
        return $shape;
    }

    $shape = [
        'table'          => $table,
        'title_col'      => firstExistingColumn($conn, $table, ['project_title', 'project_name', 'name_of_project', 'title']),
        'contractor_col' => firstExistingColumn($conn, $table, ['project_contractor', 'contractor', 'company_name']),
        'company_id_col' => firstExistingColumn($conn, $table, ['company_id']),
        'duration_col'   => firstExistingColumn($conn, $table, ['duration', 'project_duration']),
        'budget_col'     => firstExistingColumn($conn, $table, ['budget', 'project_budget']),
        'fund_col'       => firstExistingColumn($conn, $table, ['fund_source', 'fundsource', 'source_of_funds']),
    ];

    return $shape;
}

function whipProjectExists(mysqli $conn, array $row): bool {
    $shape = getWhipProjectsShape($conn);
    $table = $shape['table'] ?? null;
    $titleCol = $shape['title_col'] ?? null;
    if (!$table || !$titleCol) return false;

    $f = whipProjectRowFields($row);
    if ($f['title'] === '') return false;

    $sql = sprintf('SELECT * FROM `%s` WHERE LOWER(TRIM(`%s`)) = ? LIMIT 200', $table, $titleCol);
    $stmt = $conn->prepare($sql);
    $titleSeed = strtolower(trim($f['title']));
    $stmt->bind_param('s', $titleSeed);
    $stmt->execute();
    $result = $stmt->get_result();

    $existingEmployers = loadNormalizedEmployers($conn);
    $contractorNorm = normalizeKeyText($f['contractor']);
    $durationNorm = normalizeKeyText($f['duration']);
    $budgetNorm = normalizeMoneyValue($f['budget']);
    $fundNorm = normalizeKeyText($f['fund']);

    while ($db = $result->fetch_assoc()) {
        $matches = true;

        $companyIdCol = $shape['company_id_col'] ?? null;
        $contractorCol = $shape['contractor_col'] ?? null;
        if ($contractorNorm !== '') {
            if ($companyIdCol) {
                $expectedCompanyId = $existingEmployers[normalizeEmployerName($f['contractor'])] ?? null;
                if ($expectedCompanyId !== null) {
                    $dbCompanyId = isset($db[$companyIdCol]) ? (int)$db[$companyIdCol] : 0;
                    if ($dbCompanyId !== (int)$expectedCompanyId) {
                        $matches = false;
                    }
                }
            } elseif ($contractorCol) {
                $dbContractor = normalizeKeyText($db[$contractorCol] ?? '');
                if ($dbContractor !== $contractorNorm) {
                    $matches = false;
                }
            }
        }

        $durationCol = $shape['duration_col'] ?? null;
        if ($matches && $durationNorm !== '' && $durationCol) {
            $dbDuration = normalizeKeyText($db[$durationCol] ?? '');
            if ($dbDuration !== $durationNorm) {
                $matches = false;
            }
        }

        $budgetCol = $shape['budget_col'] ?? null;
        if ($matches && $budgetNorm !== '' && $budgetCol) {
            $dbBudget = normalizeMoneyValue($db[$budgetCol] ?? '');
            if ($dbBudget !== $budgetNorm) {
                $matches = false;
            }
        }

        $fundCol = $shape['fund_col'] ?? null;
        if ($matches && $fundNorm !== '' && $fundCol) {
            $dbFund = normalizeKeyText($db[$fundCol] ?? '');
            if ($dbFund !== $fundNorm) {
                $matches = false;
            }
        }

        if ($matches) return true;
    }

    return false;
}

// Mirrors the normalisation in save_data.php — must stay in sync.
function normalizeEmployerName(string $name): string {
    $name = strtolower(trim($name));
    $name = preg_replace('/[\.,\-\(\)\[\]\{\}]+/', ' ', $name);
    $name = trim(preg_replace('/\s+/', ' ', $name));

    $suffixes = [
        ' corporation', ' corp', ' incorporated', ' inc',
        ' company', ' co', ' limited', ' ltd', ' llc', ' l.l.c',
    ];

    $changed = true;
    while ($changed) {
        $changed = false;
        foreach ($suffixes as $suffix) {
            if ($suffix !== '' && substr($name, -strlen($suffix)) === $suffix) {
                $name = trim(substr($name, 0, -strlen($suffix)));
                $changed = true;
            }
        }
        $name = trim(preg_replace('/\s+/', ' ', $name));
    }
    return $name;
}

// Load all employer names from the DB once, keyed by normalized name → company_id.
function loadNormalizedEmployers(mysqli $conn): array {
    static $cache = null;
    if ($cache !== null) return $cache;

    $cache = [];
    $result = $conn->query('SELECT company_id, company_name FROM employers');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $norm = normalizeEmployerName((string)($row['company_name'] ?? ''));
            if ($norm !== '') {
                $cache[$norm] = (int)$row['company_id'];
            }
        }
    }
    return $cache;
}

function buildExcelDuplicateKey(string $fname, string $lname, ?string $dob): ?string {
    $fn = strtolower(trim($fname));
    $ln = strtolower(trim($lname));
    $db = trim((string)($dob ?? ''));

    // Require all 3 fields for deterministic in-file duplicate matching.
    if ($fn === '' || $ln === '' || $db === '') {
        return null;
    }

    return $fn . '|' . $ln . '|' . $db;
}

function getBeneficiaryNameColumns(mysqli $conn): array {
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $firstCandidates = ['first_name', 'fname', 'firstName'];
    $lastCandidates = ['last_name', 'lname', 'lastName'];
    $foundFirst = null;
    $foundLast = null;

    $stmt = $conn->prepare('
        SELECT column_name
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = ?
    ');
    $table = 'beneficiaries';
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $columns = [];
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if (isset($row['column_name'])) {
            $columns[] = strtolower((string)$row['column_name']);
        }
    }

    foreach ($firstCandidates as $candidate) {
        if (in_array(strtolower($candidate), $columns, true)) {
            $foundFirst = $candidate;
            break;
        }
    }

    foreach ($lastCandidates as $candidate) {
        if (in_array(strtolower($candidate), $columns, true)) {
            $foundLast = $candidate;
            break;
        }
    }

    $cached = [$foundFirst, $foundLast];
    return $cached;
}

// Duplicate check now prioritizes first name + last name + DOB.
function checkDuplicate(mysqli $conn, string $fname, string $lname, ?string $dob, string $contact, string $email): array {
    $empty = ['found' => false, 'user_id' => null, 'benef_id' => null];

    $fnameVal = trim($fname);
    $lnameVal = trim($lname);
    $dobVal = trim((string)($dob ?? ''));
    $contact = trim($contact);
    $email = trim($email);

    [$firstCol, $lastCol] = getBeneficiaryNameColumns($conn);

    // Prefer exact name + DOB matching when the beneficiaries table exposes name columns.
    if ($firstCol && $lastCol && $fnameVal !== '' && $lnameVal !== '' && $dobVal !== '') {
        $sql = sprintf(
            '
            SELECT benef_id
            FROM beneficiaries
            WHERE LOWER(TRIM(%s)) = ?
              AND LOWER(TRIM(%s)) = ?
              AND dob = ?
            LIMIT 1
        ',
            $firstCol,
            $lastCol
        );

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $fnameVal, $lnameVal, $dobVal);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            return ['found' => true, 'user_id' => null, 'benef_id' => $row['benef_id']];
        }
    }

    // Fallback for older records that may only match by contact/email.
    if ($email === '' && $contact === '' && $dobVal === '') {
        return $empty;
    }

    $stmt = $conn->prepare('
        SELECT benef_id, email, dob, contact
        FROM beneficiaries
        WHERE (email IS NOT NULL AND email <> "" AND email = ?)
           OR (dob IS NOT NULL AND dob = ?)
           OR (contact IS NOT NULL AND contact = ?)
        LIMIT 200
    ');

    $stmt->bind_param('sss', $email, $dobVal, $contact);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $score = 0;
        if ($email !== '' && isset($row['email']) && trim((string)$row['email']) === $email) $score++;
        if ($dobVal !== '' && isset($row['dob']) && trim((string)$row['dob']) === $dobVal) $score++;
        if ($contact !== '' && isset($row['contact']) && trim((string)$row['contact']) === $contact) $score++;

        if ($score >= 2) {
            return ['found' => true, 'user_id' => null, 'benef_id' => $row['benef_id']];
        }
    }

    return $empty;
}

$validatedData = [];
$seenExcelRows = [];

if (isWhipProjectsProgram($program)) {
    $rows = collapseWhipProjectRows($rows);
}

foreach ($rows as $row) {
    $previewRow = $row;
    $previewRow['_sys_is_existing'] = false;
    $previewRow['_sys_user_id'] = null;
    $previewRow['_sys_benef_id'] = null;
    $previewRow['_sys_skip'] = false;

    if ($program === 'Employers Accreditation') {
        $companyName = trim((string)($row['COMPANY'] ?? $row['Company'] ?? $row['CompanyName'] ?? ''));

        if ($companyName === '') {
            $previewRow['fname']          = '(missing company name)';
            $previewRow['lname']          = '';
            $previewRow['sex']            = '';
            $previewRow['contact']        = '';
            $previewRow['status_message'] = 'Missing Company Name';
            $previewRow['badge_status']   = 'invalid';
            $previewRow['_sys_skip']      = true;
            $validatedData[] = $previewRow;
            continue;
        }

        // Use the same normalised-name matching as save_data.php.
        $normalized = normalizeEmployerName($companyName);
        $existingEmployers = loadNormalizedEmployers($conn);
        $existingId = $existingEmployers[$normalized] ?? null;

        $previewRow['fname']          = $companyName;
        $previewRow['lname']          = '';
        $previewRow['sex']            = $row['EST. TYPE'] ?? $row['Establishment Type'] ?? $row['EstType'] ?? '';
        $previewRow['contact']        = $row['CITY/MUNICIPALITY/PROVINCE'] ?? $row['City/Municipality/Province'] ?? $row['INDUSTRY'] ?? $row['Industry'] ?? '';
        $previewRow['Classification'] = '';

        if ($existingId !== null) {
            $previewRow['status_message']    = 'Already Exists — will update accreditation';
            $previewRow['badge_status']      = 'duplicate';
            $previewRow['_sys_employer_id']  = $existingId;
            $previewRow['_sys_is_existing']  = true;
            $previewRow['_sys_skip']         = false; // update is still useful
        } else {
            $previewRow['status_message']    = 'New Employer';
            $previewRow['badge_status']      = 'new';
            $previewRow['_sys_employer_id']  = null;
            $previewRow['_sys_is_existing']  = false;
            $previewRow['_sys_skip']         = false;
        }

        $validatedData[] = $previewRow;
        continue;
    }

    if (isWhipProjectsProgram($program)) {
        $fields = whipProjectRowFields($row);

        $previewRow['fname'] = $fields['title'] !== '' ? $fields['title'] : '(missing project title)';
        $previewRow['lname'] = $fields['contractor'];
        $previewRow['sex'] = $fields['nature'];
        $previewRow['contact'] = $fields['budget'];

        if ($fields['title'] === '' || $fields['contractor'] === '') {
            $previewRow['status_message'] = 'Missing Project Title or Project Contractor';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $excelDupKey = buildWhipProjectDuplicateKey($row);
        if ($excelDupKey !== null) {
            if (isset($seenExcelRows[$excelDupKey])) {
                $previewRow['status_message'] = 'Duplicate in uploaded file';
                $previewRow['badge_status'] = 'duplicate';
                $previewRow['_sys_skip'] = true;
                $validatedData[] = $previewRow;
                continue;
            }
            $seenExcelRows[$excelDupKey] = true;
        }

        if (whipProjectExists($conn, $row)) {
            $previewRow['status_message'] = 'Already Exists';
            $previewRow['badge_status'] = 'duplicate';
            $previewRow['_sys_is_existing'] = true;
            $previewRow['_sys_skip'] = true;
        } else {
            $previewRow['status_message'] = 'New Record';
            $previewRow['badge_status'] = 'new';
            $previewRow['_sys_skip'] = false;
        }

        $validatedData[] = $previewRow;
        continue;
    }

    $fname = trim($row['First Name'] ?? $row['FirstName'] ?? '');
    $lname = trim($row['Last Name'] ?? $row['LastName'] ?? '');
    $contact = trim($row['Contact'] ?? '');
    $email = trim($row['Email'] ?? '');
    $dob = parseExcelDate($row['DOB'] ?? $row['Birthday'] ?? '');
    $age = trim($row['Age'] ?? '');

    $previewRow['fname'] = $fname;
    $previewRow['lname'] = $lname;
    $previewRow['sex'] = trim($row['Sex'] ?? $row['S'] ?? '');
    $previewRow['contact'] = $contact;
    $previewRow['_parsed_dob'] = $dob;
    $previewRow['Classification'] = trim($row['Classification'] ?? '');

    if (empty($fname) || empty($lname)) {
        $previewRow['status_message'] = 'Missing Name';
        $previewRow['badge_status'] = 'invalid';
        $previewRow['_sys_skip'] = true;
        $validatedData[] = $previewRow;
        continue;
    }

    if (!empty($age) && !is_numeric($age)) {
        $previewRow['status_message'] = 'Invalid Age format';
        $previewRow['badge_status'] = 'invalid';
        $previewRow['_sys_skip'] = true;
        $validatedData[] = $previewRow;
        continue;
    }

    $excelDupKey = buildExcelDuplicateKey($fname, $lname, $dob);
    if ($excelDupKey !== null) {
        if (isset($seenExcelRows[$excelDupKey])) {
            $previewRow['status_message'] = 'Duplicate in uploaded file';
            $previewRow['badge_status'] = 'duplicate';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }
        $seenExcelRows[$excelDupKey] = true;
    }

    $dup = checkDuplicate($conn, $fname, $lname, $dob, $contact, $email);

    if ($dup['found']) {
        $previewRow['status_message'] = 'Already Exists';
        $previewRow['badge_status'] = 'duplicate';
        $previewRow['_sys_is_existing'] = true;
        $previewRow['_sys_user_id'] = $dup['user_id'];
        $previewRow['_sys_benef_id'] = $dup['benef_id'];
        $previewRow['_sys_skip'] = true;
    } else {
        $previewRow['status_message'] = 'New Record';
        $previewRow['badge_status'] = 'new';
        $previewRow['_sys_skip'] = false;
    }

    $validatedData[] = $previewRow;
}

$newCount = count(array_filter($validatedData, fn($r) => ($r['badge_status'] ?? '') === 'new'));
$invalidCount = count(array_filter($validatedData, fn($r) => ($r['badge_status'] ?? '') === 'invalid'));
$duplicateCount = count(array_filter($validatedData, fn($r) => ($r['badge_status'] ?? '') === 'duplicate'));

echo json_encode([
    'success' => true,
    'data' => $validatedData,
    'summary' => [
        'total' => count($validatedData),
        'new' => $newCount,
        'invalid' => $invalidCount,
        'duplicate' => $duplicateCount,
        'skipped' => $invalidCount + $duplicateCount,
    ],
]);
