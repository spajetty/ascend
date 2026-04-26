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

// Resolve lookup tables by name so the import can reuse existing records.
function resolveProgramId(mysqli $conn, string $programName): ?int {
    if ($programName === '') return null;
    $stmt = $conn->prepare('SELECT program_id FROM programs WHERE name = ? LIMIT 1');
    $stmt->bind_param('s', $programName);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ? (int)$row['program_id'] : null;
}

function resolveEmployer(mysqli $conn, string $name): array {
    $name = trim($name);
    if ($name === '') return ['id' => null, 'created' => false];

    $stmt = $conn->prepare('SELECT company_id FROM employers WHERE company_name = ? LIMIT 1');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) return ['id' => (int)$row['company_id'], 'created' => false];

    $ins = $conn->prepare('INSERT INTO employers (company_name) VALUES (?)');
    $ins->bind_param('s', $name);
    $ins->execute();
    return ['id' => (int)$ins->insert_id, 'created' => true];
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

$insertedBenefIds = [];
$insertedDocIds = [];
$insertedJobMatchIds = [];
$insertedFirstJobSeekIds = [];
$createdEmployerIds = [];

try {
    $programId = resolveProgramId($conn, $program);

    // Programs that require batch tracking create an import_batches record.
    $batchTrackedPrograms = ['Job Matching and Referral', 'First Time Jobseeker'];
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

        // Employers Accreditation is handled by a different import path.
        $isEmployerOnly = ($program === 'Employers Accreditation');
        if ($isEmployerOnly) {
            $skipped++;
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
            $employerResult = resolveEmployer($conn, $companyName);
            $companyId = (int)($employerResult['id'] ?? 0);
            if (!empty($employerResult['created']) && $companyId > 0) {
                $createdEmployerIds[] = $companyId;
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

        if ($program === 'First Time Jobseeker' && tableExists($conn, 'firstJobSeek')) {
            $occPermit = toBoolInt(rowValue($row, ['Occupational Permit', 'occ_permit', 'Occ Permit'], 0));
            $healthCard = toBoolInt(rowValue($row, ['Health Card', 'health_card'], 0));

            $insFtj = $conn->prepare('
                INSERT INTO firstJobSeek (benef_id, occ_permit, health_card)
                VALUES (?, ?, ?)
            ');
            $insFtj->bind_param('iii', $benefId, $occPermit, $healthCard);
            $insFtj->execute();
            $insertedFirstJobSeekIds[] = (int)$insFtj->insert_id;
        }

        $saved++;
    }

    // If every row finishes cleanly, persist the entire import.
    $conn->commit();

    $hasUndoPayload = !empty($insertedBenefIds) || !empty($insertedDocIds) || !empty($insertedJobMatchIds) || !empty($insertedFirstJobSeekIds) || $batchId !== null;
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
            'created_at' => $now,
            'expires_at' => $now + 120,
            'program' => $program,
            'batch_id' => $batchId,
            'beneficiary_ids' => array_values(array_unique(array_map('intval', $insertedBenefIds))),
            'docs_ids' => array_values(array_unique(array_map('intval', $insertedDocIds))),
            'jobmatch_ids' => array_values(array_unique(array_map('intval', $insertedJobMatchIds))),
            'first_job_seek_ids' => array_values(array_unique(array_map('intval', $insertedFirstJobSeekIds))),
            'employer_ids' => array_values(array_unique(array_map('intval', $createdEmployerIds))),
        ];
    }

    echo json_encode([
        'success' => true,
        'saved' => $saved,
        'skipped' => $skipped,
        'batch_id' => $batchId,
        'undo_token' => $undoToken,
        'undo_window_sec' => $undoToken ? $undoWindowSec : 0,
        'message' => "{$saved} record(s) imported, {$skipped} skipped.",
    ]);
} catch (Throwable $e) {
    // Any failure cancels the whole import so we do not leave partial data behind.
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
