<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['data']) || !isset($input['program'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data format']);
    exit;
}

$program = trim((string)$input['program']);
$rows = $input['data'];
$importMonthRaw = trim((string)($input['importMonth'] ?? ''));
$importYearRaw = trim((string)($input['importYear'] ?? ''));
$sourceFileName = trim((string)($input['fileName'] ?? ''));

// Small helpers keep the row-to-database mapping readable.
function s($val): string {
    return trim((string)($val ?? ''));
}

function rowValue(array $row, array $keys, $default = '') {
    foreach ($keys as $k) {
        if (array_key_exists($k, $row) && $row[$k] !== null && $row[$k] !== '') {
            return $row[$k];
        }
    }
    return $default;
}

function tableExists(mysqli $conn, string $table): bool {
    $stmt = $conn->prepare('
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = DATABASE() AND table_name = ?
        LIMIT 1
    ');
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

function monthToInt($val): ?int {
    $raw = trim((string)($val ?? ''));
    if ($raw === '') return null;

    if (ctype_digit($raw)) {
        $n = (int)$raw;
        return ($n >= 1 && $n <= 12) ? $n : null;
    }

    $ts = strtotime('1 ' . $raw);
    return $ts ? (int)date('n', $ts) : null;
}

function toBoolInt($val): int {
    if (is_bool($val)) return $val ? 1 : 0;
    $raw = strtolower(trim((string)($val ?? '')));
    if ($raw === '') return 0;
    return in_array($raw, ['1', 'true', 'yes', 'y', 'checked', 'x'], true) ? 1 : 0;
}

function normalizeEmployerName(string $name): string {
    $name = strtolower(trim($name));
    $name = preg_replace('/[\.,\-\(\)\[\]\{\}]+/', ' ', $name);
    $name = trim(preg_replace('/\s+/', ' ', $name)); // trim here prevents a trailing space (from e.g. "Inc.") blocking suffix detection

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
        $name = preg_replace('/\s+/', ' ', trim($name));
    }

    return trim($name);
}

function isWhipProjectsProgram(string $program): bool {
    return in_array($program, [
        'Workers Hiring for Infrastructure Projects - Projects',
        'Workers Hiring for Infrastructure Projects — Projects',
    ], true);
}

function firstExistingColumn(mysqli $conn, string $table, array $candidates): ?string {
    foreach ($candidates as $column) {
        if (tableHasColumn($conn, $table, $column)) {
            return $column;
        }
    }
    return null;
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

function parseMoneyNullable($value): ?float {
    $raw = trim((string)($value ?? ''));
    if ($raw === '') return null;
    $clean = preg_replace('/[^0-9.\-]/', '', $raw);
    return ($clean !== '' && is_numeric($clean)) ? (float)$clean : null;
}

function parseIntNullable($value): ?int {
    $raw = trim((string)($value ?? ''));
    if ($raw === '') return null;
    return is_numeric($raw) ? (int)$raw : null;
}

function whipProjectPayload(array $row): array {
    return [
        'title' => s(rowValue($row, ['Project Title / Name of Implementing Partner', 'Project Title', 'Project Name', 'project_title'], '')),
        'nature' => s(rowValue($row, ['Nature of Project', 'nature_of_project'], '')),
        'duration' => s(rowValue($row, ['Duration', 'duration'], '')),
        'budget_raw' => s(rowValue($row, ['Budget', 'budget'], '')),
        'fund_source' => s(rowValue($row, ['Fund Source', 'fund_source'], '')),
        'jobs_generated' => s(rowValue($row, ['Jobs Generated', 'jobs_generated'], '')),
        'persons_locality' => s(rowValue($row, ['No. of Persons Employed from the Locality', 'No. of Persons', 'persons_employed_locality'], '')),
        'skills_required' => s(rowValue($row, ['Skills Required for the Job', 'skills_required'], '')),
        'skills_deficiencies' => s(rowValue($row, ['Skills Deficiencies', 'skills_deficiencies'], '')),
        'contractor' => s(rowValue($row, ['Project Contractor', 'Company', 'contractor'], '')),
        'legitimate_contractors' => strtoupper(s(rowValue($row, ['Legitimate Contractors (YES or NO)', 'Legitimate Contractors', 'legitimate_contractors'], ''))),
        'filled' => s(rowValue($row, ['Filled', 'filled'], '')),
        'unfilled' => s(rowValue($row, ['Unfilled', 'unfilled'], '')),
    ];
}

function resolveWhipProjectsSchema(mysqli $conn): array {
    static $schema = null;
    if ($schema !== null) return $schema;

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
        $schema = ['table' => null];
        return $schema;
    }

    $schema = [
        'table' => $table,
        'project_id_col' => firstExistingColumn($conn, $table, ['project_id', 'whip_project_id', 'id']),
        'title_col' => firstExistingColumn($conn, $table, ['project_title', 'project_name', 'name_of_project', 'title']),
        'nature_col' => firstExistingColumn($conn, $table, ['nature_of_project', 'project_nature', 'nature']),
        'duration_col' => firstExistingColumn($conn, $table, ['duration', 'project_duration']),
        'budget_col' => firstExistingColumn($conn, $table, ['budget', 'project_budget']),
        'fund_col' => firstExistingColumn($conn, $table, ['fund_source', 'fundsource', 'source_of_funds']),
        'jobs_generated_col' => firstExistingColumn($conn, $table, ['jobs_generated']),
        'persons_locality_col' => firstExistingColumn($conn, $table, ['persons_employed_locality', 'no_of_persons', 'persons_locality']),
        'skills_required_col' => firstExistingColumn($conn, $table, ['skills_required', 'skills_required_for_the_job']),
        'skills_def_col' => firstExistingColumn($conn, $table, ['skills_deficiencies', 'skills_deficiency']),
        'contractor_col' => firstExistingColumn($conn, $table, ['project_contractor', 'contractor', 'company_name']),
        'legit_col' => firstExistingColumn($conn, $table, ['legitimate_contractors', 'legitimate_contractor']),
        'filled_col' => firstExistingColumn($conn, $table, ['filled']),
        'unfilled_col' => firstExistingColumn($conn, $table, ['unfilled']),
        'company_id_col' => firstExistingColumn($conn, $table, ['company_id']),
        'program_id_col' => firstExistingColumn($conn, $table, ['program_id']),
        'batch_id_col' => firstExistingColumn($conn, $table, ['batch_id']),
    ];

    return $schema;
}

function whipProjectAlreadyExists(mysqli $conn, array $schema, array $payload, ?int $companyId): bool {
    $table = $schema['table'] ?? null;
    $titleCol = $schema['title_col'] ?? null;
    if (!$table || !$titleCol || $payload['title'] === '') return false;

    $sql = sprintf('SELECT * FROM `%s` WHERE LOWER(TRIM(`%s`)) = ? LIMIT 200', $table, $titleCol);
    $stmt = $conn->prepare($sql);
    $titleSeed = strtolower(trim($payload['title']));
    $stmt->bind_param('s', $titleSeed);
    $stmt->execute();
    $result = $stmt->get_result();

    $contractorNorm = normalizeKeyText($payload['contractor']);
    $durationNorm = normalizeKeyText($payload['duration']);
    $budgetNorm = normalizeMoneyValue($payload['budget_raw']);
    $fundNorm = normalizeKeyText($payload['fund_source']);

    while ($db = $result->fetch_assoc()) {
        $matches = true;

        if ($contractorNorm !== '') {
            $companyIdCol = $schema['company_id_col'] ?? null;
            $contractorCol = $schema['contractor_col'] ?? null;
            if ($companyIdCol && $companyId !== null) {
                $dbCompanyId = isset($db[$companyIdCol]) ? (int)$db[$companyIdCol] : 0;
                if ($dbCompanyId !== $companyId) {
                    $matches = false;
                }
            } elseif ($contractorCol) {
                $dbContractor = normalizeKeyText($db[$contractorCol] ?? '');
                if ($dbContractor !== $contractorNorm) {
                    $matches = false;
                }
            }
        }

        $durationCol = $schema['duration_col'] ?? null;
        if ($matches && $durationNorm !== '' && $durationCol) {
            $dbDuration = normalizeKeyText($db[$durationCol] ?? '');
            if ($dbDuration !== $durationNorm) {
                $matches = false;
            }
        }

        $budgetCol = $schema['budget_col'] ?? null;
        if ($matches && $budgetNorm !== '' && $budgetCol) {
            $dbBudget = normalizeMoneyValue($db[$budgetCol] ?? '');
            if ($dbBudget !== $budgetNorm) {
                $matches = false;
            }
        }

        $fundCol = $schema['fund_col'] ?? null;
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

// Resolve lookup tables by name so the import can reuse existing records.
function resolveProgramId(mysqli $conn, string $programName): ?int {
    if ($programName === '') return null;
    $stmt = $conn->prepare('SELECT program_id FROM programs WHERE name = ? LIMIT 1');
    $stmt->bind_param('s', $programName);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ? (int)$row['program_id'] : null;
}

function resolveEmployer(mysqli $conn, string $name, ?int $batchId = null, array $meta = []): array {
    $name = trim($name);
    if ($name === '') return ['id' => null, 'created' => false];

    $normalized = normalizeEmployerName($name);

    $stmt = $conn->prepare('SELECT company_id, company_name FROM employers');
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $existingName = (string)($row['company_name'] ?? '');
        if ($existingName === '') continue;

        $existingNormalized = normalizeEmployerName($existingName);
        if ($existingNormalized !== '' && $existingNormalized === $normalized) {
            return ['id' => (int)$row['company_id'], 'created' => false, 'matched_name' => $existingName];
        }
    }

    $columns = ['company_name'];
    $placeholders = ['?'];
    $bindTypes = 's';
    $bindValues = [$name];

    if (tableHasColumn($conn, 'employers', 'est_type')) {
        $columns[] = 'est_type';
        $placeholders[] = '?';
        $bindTypes .= 's';
        $bindValues[] = s($meta['est_type'] ?? '') ?: null;
    }

    if (tableHasColumn($conn, 'employers', 'industry')) {
        $columns[] = 'industry';
        $placeholders[] = '?';
        $bindTypes .= 's';
        $bindValues[] = s($meta['industry'] ?? '') ?: null;
    }

    if (tableHasColumn($conn, 'employers', 'city')) {
        $columns[] = 'city';
        $placeholders[] = '?';
        $bindTypes .= 's';
        $bindValues[] = s($meta['city'] ?? '') ?: null;
    }

    if ($batchId !== null && $batchId > 0 && tableHasColumn($conn, 'employers', 'batch_id')) {
        $columns[] = 'batch_id';
        $placeholders[] = '?';
        $bindTypes .= 'i';
        $bindValues[] = $batchId;
    }

    $sql = 'INSERT INTO employers (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
    $ins = $conn->prepare($sql);
    $ins->bind_param($bindTypes, ...$bindValues);
    $ins->execute();
    return ['id' => (int)$ins->insert_id, 'created' => true, 'matched_name' => $name];
}

function ensureDocsBenef(mysqli $conn, int $benefId, array $row): ?int {
    // Only create docs_benef once per beneficiary.
    $check = $conn->prepare('SELECT document_id FROM docs_benef WHERE benef_id = ? LIMIT 1');
    $check->bind_param('i', $benefId);
    $check->execute();
    $existing = $check->get_result()->fetch_assoc();
    if ($existing) return null;

    $proof = s(rowValue($row, ['Proof of Residency', 'proof_of_residency'], '')) ?: null;
    $latest = s(rowValue($row, ['Latest Credentials', 'latest_credential'], '')) ?: null;
    $intent = s(rowValue($row, ['Letter of Intent', 'letter_of_intent'], '')) ?: null;
    $reco = s(rowValue($row, ['Reco Letter', 'reco_letter'], '')) ?: null;
    $resume = s(rowValue($row, ['Resume', 'resume'], '')) ?: null;
    $tor = s(rowValue($row, ['TOR', 'tor'], '')) ?: null;
    $brgy = s(rowValue($row, ['Brgy Clearance', 'Barangay Clearance', 'brgy_clearance'], '')) ?: null;
    $nbi = s(rowValue($row, ['NBI Clearance', 'nbi_clearance'], '')) ?: null;
    $birth = s(rowValue($row, ['Birth Cert', 'B-Cert', 'birth_cert'], '')) ?: null;
    $tesda = s(rowValue($row, ['TESDA Cert', 'tesda_cert'], '')) ?: null;

    $ins = $conn->prepare('
        INSERT INTO docs_benef
            (benef_id, proof_of_residency, latest_credential, letter_of_intent, reco_letter, resume, tor, brgy_clearance, nbi_clearance, birth_cert, tesda_cert)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $ins->bind_param('issssssssss', $benefId, $proof, $latest, $intent, $reco, $resume, $tor, $brgy, $nbi, $birth, $tesda);
    $ins->execute();
    return (int)$ins->insert_id;
}

function createUndoToken(): string {
    return bin2hex(random_bytes(16));
}

// Wrap the whole import so we can commit or roll back everything as one unit.
$conn->begin_transaction();

$saved = 0;
$skipped = 0;
$batchId = null;
$undoToken = null;
$undoWindowSec = 15;

$insertedBenefIds         = [];
$insertedDocIds           = [];
$insertedJobMatchIds      = [];
$insertedJobFairIds       = [];
$insertedFirstJobSeekIds  = [];
$insertedProjectIds       = [];
$insertedProjectTable     = null;
$createdEmployerIds       = [];
$insertedAccreditationIds = [];
$warnings = [];

try {
    $programId = resolveProgramId($conn, $program);

    // Programs that require batch tracking create an import_batches record.
    $batchTrackedPrograms = ['Job Matching and Referral', 'First Time Jobseeker', 'Job Fair'];
    $needsBatch = in_array($program, $batchTrackedPrograms, true) && tableExists($conn, 'import_batches');
    if ($needsBatch) {
        $monthInt = monthToInt($importMonthRaw);
        if ($monthInt === null) {
            throw new RuntimeException('Invalid import month. Please select a valid month.');
        }

        $yearInt = (int)$importYearRaw;
        if ($yearInt < 1900 || $yearInt > 3000) {
            throw new RuntimeException('Invalid import year. Please select a valid year.');
        }

        $uploadedBy = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $insBatch = $conn->prepare('
            INSERT INTO import_batches (file_name, month, year, uploaded_by)
            VALUES (?, ?, ?, ?)
        ');
        $insBatch->bind_param('siii', $sourceFileName, $monthInt, $yearInt, $uploadedBy);
        $insBatch->execute();
        $batchId = (int)$insBatch->insert_id;
    }

    foreach ($rows as $row) {
        // Preview validation marks rows that should be skipped.
        if (!empty($row['_sys_skip'])) {
            $skipped++;
            continue;
        }

        // ── Employers Accreditation ──────────────────────────────────────────────
        if ($program === 'Employers Accreditation') {
            $companyName   = s(rowValue($row, ['COMPANY', 'Company', 'CompanyName'], ''));
            $estType       = s(rowValue($row, ['EST. TYPE', 'Establishment Type', 'EstType', 'est_type'], '')) ?: null;
            $industry      = s(rowValue($row, ['INDUSTRY', 'Industry', 'industry'], '')) ?: null;
            $city          = s(rowValue($row, ['CITY/MUNICIPALITY/PROVINCE', 'City/Municipality/Province', 'City/Municipality', 'city'], '')) ?: null;
            $accStatus     = strtolower(s(rowValue($row, ['ACCREDITATION', 'Accreditation', 'accreditation'], 'new')));
            $monthRaw      = rowValue($row, ['MONTH', 'Month', 'month'], '');

            if ($companyName === '') { $skipped++; continue; }

            // Normalise accreditation status to the enum values ('new' | 'renew').
            $accStatus = in_array($accStatus, ['new', 'renew'], true) ? $accStatus : 'new';

            // Resolve month: prefer the row's MONTH column, fall back to import period.
            $monthInt = monthToInt($monthRaw) ?? monthToInt($importMonthRaw);
            if ($monthInt === null) { $skipped++; continue; }

            $yearInt = (int)$importYearRaw;
            if ($yearInt < 1900 || $yearInt > 3000) { $skipped++; continue; }

            // ── Resolve employer (create if needed) ──────────────────────────────
            $existingEmpId = isset($row['_sys_employer_id']) && (int)$row['_sys_employer_id'] > 0
                ? (int)$row['_sys_employer_id']
                : null;

            if ($existingEmpId) {
                $employerId = $existingEmpId;
                // Update metadata fields that may have improved in this file.
                $setClauses = []; $bindTypes = ''; $bindValues = [];
                if ($estType !== null)  { $setClauses[] = 'est_type = ?'; $bindTypes .= 's'; $bindValues[] = $estType; }
                if ($industry !== null) { $setClauses[] = 'industry = ?'; $bindTypes .= 's'; $bindValues[] = $industry; }
                if ($city !== null)     { $setClauses[] = 'city = ?';     $bindTypes .= 's'; $bindValues[] = $city; }
                if (!empty($setClauses)) {
                    $bindTypes  .= 'i'; $bindValues[] = $employerId;
                    $upd = $conn->prepare('UPDATE employers SET ' . implode(', ', $setClauses) . ' WHERE company_id = ?');
                    $upd->bind_param($bindTypes, ...$bindValues);
                    $upd->execute();
                }
            } else {
                $employerResult = resolveEmployer($conn, $companyName, null, [
                    'est_type' => $estType,
                    'industry' => $industry,
                    'city'     => $city,
                ]);
                $employerId = (int)($employerResult['id'] ?? 0);
                if ($employerId > 0 && !empty($employerResult['created'])) {
                    $createdEmployerIds[] = $employerId;
                    $warnings[] = 'New company created: ' . ($employerResult['matched_name'] ?? $companyName);
                }
            }

            if (!$employerId) { $skipped++; continue; }

            // ── Check accreditation-level duplicate (same company + month + year) ─
            $chk = $conn->prepare('
                SELECT accreditation_id FROM employers_accreditations
                WHERE company_id = ? AND month = ? AND year = ? LIMIT 1
            ');
            $chk->bind_param('iii', $employerId, $monthInt, $yearInt);
            $chk->execute();
            if ($chk->get_result()->fetch_assoc()) {
                $skipped++; // Accreditation for this period already on record.
                continue;
            }

            // ── Insert accreditation record ───────────────────────────────────────
            $insAcc = $conn->prepare('
                INSERT INTO employers_accreditations (company_id, status, month, year)
                VALUES (?, ?, ?, ?)
            ');
            $insAcc->bind_param('isii', $employerId, $accStatus, $monthInt, $yearInt);
            $insAcc->execute();
            $insertedAccreditationIds[] = (int)$conn->insert_id;
            $saved++;
            continue;
        }

        if (isWhipProjectsProgram($program)) {
            $payload = whipProjectPayload($row);

            if ($payload['title'] === '' || $payload['contractor'] === '') {
                $skipped++;
                continue;
            }

            $schema = resolveWhipProjectsSchema($conn);
            $table = $schema['table'] ?? null;
            $titleCol = $schema['title_col'] ?? null;
            if (!$table || !$titleCol) {
                throw new RuntimeException('WHIP Projects table is not configured in the database.');
            }

            $employerResult = resolveEmployer($conn, $payload['contractor'], $batchId, [
                'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
                'industry' => rowValue($row, ['Industry', 'industry'], ''),
                'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
            ]);
            $companyId = (int)($employerResult['id'] ?? 0);
            if (!empty($employerResult['created']) && $companyId > 0) {
                $createdEmployerIds[] = $companyId;
                $warnings[] = 'New company created: ' . ($employerResult['matched_name'] ?? $payload['contractor']);
            }

            if (whipProjectAlreadyExists($conn, $schema, $payload, $companyId > 0 ? $companyId : null)) {
                $skipped++;
                continue;
            }

            $columns = [];
            $placeholders = [];
            $types = '';
            $values = [];

            $addValue = static function (string $column, string $type, $value) use (&$columns, &$placeholders, &$types, &$values) {
                $columns[] = $column;
                $placeholders[] = '?';
                $types .= $type;
                $values[] = $value;
            };

            $addValue($titleCol, 's', $payload['title']);

            if (($col = $schema['nature_col'] ?? null) && $payload['nature'] !== '') {
                $addValue($col, 's', $payload['nature']);
            }
            if (($col = $schema['duration_col'] ?? null) && $payload['duration'] !== '') {
                $addValue($col, 's', $payload['duration']);
            }
            if (($col = $schema['budget_col'] ?? null)) {
                $budgetValue = parseMoneyNullable($payload['budget_raw']);
                if ($budgetValue !== null) {
                    $addValue($col, 'd', $budgetValue);
                }
            }
            if (($col = $schema['fund_col'] ?? null) && $payload['fund_source'] !== '') {
                $addValue($col, 's', $payload['fund_source']);
            }
            if (($col = $schema['jobs_generated_col'] ?? null)) {
                $v = parseIntNullable($payload['jobs_generated']);
                if ($v !== null) $addValue($col, 'i', $v);
            }
            if (($col = $schema['persons_locality_col'] ?? null)) {
                $v = parseIntNullable($payload['persons_locality']);
                if ($v !== null) $addValue($col, 'i', $v);
            }
            if (($col = $schema['skills_required_col'] ?? null) && $payload['skills_required'] !== '') {
                $addValue($col, 's', $payload['skills_required']);
            }
            if (($col = $schema['skills_def_col'] ?? null) && $payload['skills_deficiencies'] !== '') {
                $addValue($col, 's', $payload['skills_deficiencies']);
            }

            if (($col = $schema['company_id_col'] ?? null) && $companyId > 0) {
                $addValue($col, 'i', $companyId);
            } elseif (($col = $schema['contractor_col'] ?? null) && $payload['contractor'] !== '') {
                $addValue($col, 's', $payload['contractor']);
            }

            if (($col = $schema['legit_col'] ?? null) && $payload['legitimate_contractors'] !== '') {
                $legit = in_array($payload['legitimate_contractors'], ['YES', 'NO'], true)
                    ? $payload['legitimate_contractors']
                    : null;
                if ($legit !== null) {
                    $addValue($col, 's', $legit);
                }
            }
            if (($col = $schema['filled_col'] ?? null)) {
                $v = parseIntNullable($payload['filled']);
                if ($v !== null) $addValue($col, 'i', $v);
            }
            if (($col = $schema['unfilled_col'] ?? null)) {
                $v = parseIntNullable($payload['unfilled']);
                if ($v !== null) $addValue($col, 'i', $v);
            }
            if (($col = $schema['program_id_col'] ?? null) && $programId !== null) {
                $addValue($col, 'i', $programId);
            }
            if (($col = $schema['batch_id_col'] ?? null) && $batchId !== null) {
                $addValue($col, 'i', $batchId);
            }

            $quotedColumns = array_map(static fn($c) => '`' . $c . '`', $columns);
            $sql = sprintf(
                'INSERT INTO `%s` (%s) VALUES (%s)',
                $table,
                implode(', ', $quotedColumns),
                implode(', ', $placeholders)
            );
            $ins = $conn->prepare($sql);
            $ins->bind_param($types, ...$values);
            $ins->execute();
            $insertedProjectIds[] = (int)$ins->insert_id;
            if ($insertedProjectTable === null) {
                $insertedProjectTable = $table;
            }

            $saved++;
            continue;
        }

        // Reuse an existing beneficiary when validation already identified one.
        $existingBenefId = $row['_sys_benef_id'] ?? null;

        $benefId = $existingBenefId ? (int)$existingBenefId : null;
        if (!$benefId) {
            // Map spreadsheet columns into the beneficiaries table.
            $sex = s(rowValue($row, ['Sex', 'sex'], ''));
            $civil = s(rowValue($row, ['Civil Status', 'CivilStatus', 'civil_status'], ''));
            $dob = rowValue($row, ['_parsed_dob', 'DOB', 'Birthday'], null);
            $contact = s(rowValue($row, ['Contact', 'contact'], ''));
            $email = s(rowValue($row, ['Email', 'email'], '')) ?: null;
            $classification = s(rowValue($row, ['Classification', 'classification'], '')) ?: null;
            $houseNo = s(rowValue($row, ['House No.', 'house_no'], '')) ?: null;
            $barangay = s(rowValue($row, ['Barangay', 'barangay'], '')) ?: null;
            $district = s(rowValue($row, ['District', 'district'], '')) ?: null;
            $city = s(rowValue($row, ['City', 'city'], '')) ?: null;

            $insBenef = $conn->prepare('
                INSERT INTO beneficiaries
                    (sex, civil_status, dob, contact, email, program_id, classification, house_no, barangay, district, city)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $insBenef->bind_param('ssssssissss', $sex, $civil, $dob, $contact, $email, $programId, $classification, $houseNo, $barangay, $district, $city);
            $insBenef->execute();
            $benefId = (int)$insBenef->insert_id;
            $insertedBenefIds[] = $benefId;
        }

        if (!$benefId) {
            $skipped++;
            continue;
        }

        // Documents are optional, but we still create the row if the table exists.
        if (tableExists($conn, 'docs_benef')) {
            $insertedDocId = ensureDocsBenef($conn, $benefId, $row);
            if ($insertedDocId) {
                $insertedDocIds[] = $insertedDocId;
            }
        }

        // Job Matching rows link the beneficiary to the employer and batch.
        if ($program === 'Job Matching and Referral') {
            $companyName = s(rowValue($row, ['Company', 'CompanyName', 'Employer'], ''));
            $employerResult = resolveEmployer($conn, $companyName, $batchId, [
                'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
                'industry' => rowValue($row, ['Industry', 'industry'], ''),
                'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
            ]);
            $companyId = (int)($employerResult['id'] ?? 0);
            if (!empty($employerResult['created']) && $companyId > 0) {
                $createdEmployerIds[] = $companyId;
                $warnings[] = 'New company created: ' . ($employerResult['matched_name'] ?? $companyName);
            }
            if (!$companyId) {
                $skipped++;
                continue;
            }

            $position = s(rowValue($row, ['Position', 'Desired Position', 'desired_position'], '')) ?: 'N/A';

            $hasBatchField = false;
            if (tableExists($conn, 'jobMatch')) {
                $fieldCheck = $conn->prepare('
                    SELECT 1
                    FROM information_schema.columns
                    WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?
                    LIMIT 1
                ');
                $tbl = 'jobMatch';
                $col = 'batch_id';
                $fieldCheck->bind_param('ss', $tbl, $col);
                $fieldCheck->execute();
                $hasBatchField = (bool)$fieldCheck->get_result()->fetch_assoc();
            }

            if ($hasBatchField) {
                $insJm = $conn->prepare('
                    INSERT INTO jobMatch (benef_id, company_id, batch_id, position)
                    VALUES (?, ?, ?, ?)
                ');
                $insJm->bind_param('iiis', $benefId, $companyId, $batchId, $position);
                $insJm->execute();
                $insertedJobMatchIds[] = (int)$insJm->insert_id;
            } else {
                $insJm = $conn->prepare('
                    INSERT INTO jobMatch (benef_id, company_id, position)
                    VALUES (?, ?, ?)
                ');
                $insJm->bind_param('iis', $benefId, $companyId, $position);
                $insJm->execute();
                $insertedJobMatchIds[] = (int)$insJm->insert_id;
            }
        }

        if ($program === 'Job Fair' && tableExists($conn, 'jobFair')) {
            $companyName = s(rowValue($row, ['Company', 'CompanyName', 'Employer'], ''));
            $employerResult = resolveEmployer($conn, $companyName, $batchId, [
                'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
                'industry' => rowValue($row, ['Industry', 'industry'], ''),
                'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
            ]);
            $companyId = (int)($employerResult['id'] ?? 0);
            if (!empty($employerResult['created']) && $companyId > 0) {
                $createdEmployerIds[] = $companyId;
                $warnings[] = 'New company created: ' . ($employerResult['matched_name'] ?? $companyName);
            }
            if (!$companyId) {
                $skipped++;
                continue;
            }

            $position = s(rowValue($row, ['Position', 'Desired Position', 'desired_position'], '')) ?: 'N/A';

            $insJf = $conn->prepare('
                INSERT INTO jobFair (benef_id, company_id, position)
                VALUES (?, ?, ?)
            ');
            $insJf->bind_param('iis', $benefId, $companyId, $position);
            $insJf->execute();
            $insertedJobFairIds[] = (int)$insJf->insert_id;
        }

        if ($program === 'First Time Jobseeker' && tableExists($conn, 'firstJobSeek')) {
            $companyName = s(rowValue($row, ['Company', 'CompanyName', 'Employer'], ''));
            $employerResult = resolveEmployer($conn, $companyName, $batchId, [
                'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
                'industry' => rowValue($row, ['Industry', 'industry'], ''),
                'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
            ]);
            $companyId = (int)($employerResult['id'] ?? 0);
            if (!empty($employerResult['created']) && $companyId > 0) {
                $createdEmployerIds[] = $companyId;
                $warnings[] = 'New company created: ' . ($employerResult['matched_name'] ?? $companyName);
            }
            if (!$companyId) {
                $skipped++;
                continue;
            }

            $occPermit = toBoolInt(rowValue($row, ['Occupational Permit', 'occ_permit', 'Occ Permit'], 0));
            $healthCard = toBoolInt(rowValue($row, ['Health Card', 'health_card'], 0));

            if (tableHasColumn($conn, 'firstJobSeek', 'company_id')) {
                $insFtj = $conn->prepare('
                    INSERT INTO firstJobSeek (benef_id, company_id, occ_permit, health_card)
                    VALUES (?, ?, ?, ?)
                ');
                $insFtj->bind_param('iiii', $benefId, $companyId, $occPermit, $healthCard);
            } else {
                $insFtj = $conn->prepare('
                    INSERT INTO firstJobSeek (benef_id, occ_permit, health_card)
                    VALUES (?, ?, ?)
                ');
                $insFtj->bind_param('iii', $benefId, $occPermit, $healthCard);
            }
            $insFtj->execute();
            $insertedFirstJobSeekIds[] = (int)$insFtj->insert_id;
        }

        $saved++;
    }

    // If every row finishes cleanly, persist the entire import.
    $conn->commit();

    $hasUndoPayload = !empty($insertedBenefIds) || !empty($insertedDocIds) || !empty($insertedJobMatchIds) || !empty($insertedJobFairIds) || !empty($insertedFirstJobSeekIds) || !empty($insertedProjectIds) || !empty($insertedAccreditationIds) || $batchId !== null;
    if ($hasUndoPayload) {
        if (!isset($_SESSION['import_undo']) || !is_array($_SESSION['import_undo'])) {
            $_SESSION['import_undo'] = [];
        }

        $now = time();
        foreach ($_SESSION['import_undo'] as $token => $payload) {
            if (!is_array($payload) || (($payload['expires_at'] ?? 0) < $now)) {
                unset($_SESSION['import_undo'][$token]);
            }
        }

        $undoToken = createUndoToken();
        $_SESSION['import_undo'][$undoToken] = [
            'created_at'          => $now,
            'expires_at'          => $now + 600,
            'program'             => $program,
            'batch_id'            => $batchId,
            'beneficiary_ids'     => array_values(array_unique(array_map('intval', $insertedBenefIds))),
            'docs_ids'            => array_values(array_unique(array_map('intval', $insertedDocIds))),
            'jobmatch_ids'        => array_values(array_unique(array_map('intval', $insertedJobMatchIds))),
            'jobfair_ids'         => array_values(array_unique(array_map('intval', $insertedJobFairIds))),
            'first_job_seek_ids'  => array_values(array_unique(array_map('intval', $insertedFirstJobSeekIds))),
            'project_ids'         => array_values(array_unique(array_map('intval', $insertedProjectIds))),
            'project_table'       => $insertedProjectTable,
            'employer_ids'        => array_values(array_unique(array_map('intval', $createdEmployerIds))),
            'accreditation_ids'   => array_values(array_unique(array_map('intval', $insertedAccreditationIds))),
        ];
    }

    echo json_encode([
        'success' => true,
        'saved' => $saved,
        'skipped' => $skipped,
        'batch_id' => $batchId,
        'undo_token' => $undoToken,
        'warnings' => array_values(array_unique($warnings)),
        'message' => "{$saved} record(s) imported, {$skipped} skipped.",
    ]);
} catch (Throwable $e) {
    // Any failure cancels the whole import so we do not leave partial data behind.
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
