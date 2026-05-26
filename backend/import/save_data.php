<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../career-dev/cache-refresh.php';

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
    // Optional: duration in months for contract period (GIP). Defaults to 3 if not provided.
    $importDurationMonths = isset($input['importDurationMonths']) ? (int)$input['importDurationMonths'] : 3;
$sourceFileName = trim((string)($input['fileName'] ?? ''));
$spesCategoryRaw = trim((string)($input['spesCategory'] ?? ''));
$wiirpCategoryRaw = trim((string)($input['wiirpCategory'] ?? ''));
$gipCategoryRaw = trim((string)($input['gipCategory'] ?? ''));
$jobFairEventRaw = trim((string)($input['jobFairEvent'] ?? ''));

// Shared helpers
require_once __DIR__ . '/helpers/db_utils.php';
require_once __DIR__ . '/helpers/formatting_utils.php';
require_once __DIR__ . '/helpers/program_utils.php';
require_once __DIR__ . '/helpers/followup_utils.php';
$inputBatchId = isset($input['batchId']) ? (int)$input['batchId'] : 0;

// Row savers
require_once __DIR__ . '/savers/save_common_person.php';
require_once __DIR__ . '/savers/save_employers_accreditation.php';
require_once __DIR__ . '/savers/save_whip_projects.php';
require_once __DIR__ . '/savers/save_whip_beneficiaries.php';
require_once __DIR__ . '/savers/save_wiirp.php';
require_once __DIR__ . '/savers/save_gip.php';
require_once __DIR__ . '/savers/save_job_matching.php';
require_once __DIR__ . '/savers/save_job_fair.php';
require_once __DIR__ . '/savers/save_spes.php';
require_once __DIR__ . '/savers/save_schools.php';

$conn->begin_transaction();

$saved = 0;
$skipped = 0;
$batchId = null;
$undoToken = null;

$state = [
    'insertedBenefIds' => [],
    'jobFairBeneficiaryMap' => [],
    'insertedDocIds' => [],
    'insertedJobMatchIds' => [],
    'insertedJobFairIds' => [],
    'insertedActivityHistoryIds' => [],
    'insertedFirstJobSeekIds' => [],
    'insertedWhipIds' => [],
    'insertedWhipTable' => null,
    'insertedWiirpIds' => [],
    'insertedWiirpTable' => null,
    'insertedWiirpAssignmentIds' => [],
    'insertedGipIds' => [],
    'insertedProjectIds' => [],
    'insertedProjectTable' => null,
    'insertedSPESIds' => [],
    'insertedSPESEmploymentIds' => [],
    'insertedSchoolIds' => [],
    'createdEmployerIds' => [],
    'insertedAccreditationIds' => [],
    'warnings' => [],
];

try {
    $programId = resolveProgramId($conn, $program);

    $batchTrackedPrograms = [
        'Job Matching and Referral',
        'First Time Jobseeker',
        'Job Fair',
        'Work Immersion and Internship Referral Program',
        'Government Internship Program',
        'Workers Hiring for Infrastructure Projects - Beneficiaries',
        'Workers Hiring for Infrastructure Projects — Beneficiaries',
        'Workers Hiring for Infrastructure Projects - Projects',
        'Workers Hiring for Infrastructure Projects — Projects',
        'SPES',
    ];
    $needsBatch = in_array($program, $batchTrackedPrograms, true) && tableExists($conn, 'import_batches');

    if ($needsBatch) {
        if ($program === 'Job Fair' && ($importMonthRaw === '' || $importYearRaw === '')) {
            $eventId = (int)$jobFairEventRaw;
            if ($eventId <= 0) {
                throw new RuntimeException('Please select a Job Fair event before importing.');
            }
            $eventStmt = $conn->prepare('SELECT date_start FROM job_fair_events WHERE jobfairevent_id = ? LIMIT 1');
            $eventStmt->bind_param('i', $eventId);
            $eventStmt->execute();
            $eventRow = $eventStmt->get_result()->fetch_assoc();
            $eventStmt->close();
            if (!$eventRow || trim((string)($eventRow['date_start'] ?? '')) === '') {
                throw new RuntimeException('Selected Job Fair event has no event date.');
            }
            $eventTs = strtotime((string)$eventRow['date_start']);
            if ($eventTs === false) {
                throw new RuntimeException('Invalid date on selected Job Fair event.');
            }
            if ($importMonthRaw === '') {
                $importMonthRaw = date('F', $eventTs);
            }
            if ($importYearRaw === '') {
                $importYearRaw = date('Y', $eventTs);
            }
        }

        $monthInt = monthToInt($importMonthRaw);
        if ($monthInt === null) {
            throw new RuntimeException('Invalid import month. Please select a valid month.');
        }

        $yearInt = (int)$importYearRaw;
        if ($yearInt < 1900 || $yearInt > 3000) {
            throw new RuntimeException('Invalid import year. Please select a valid year.');
        }

        $uploadedBy = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $insBatch = $conn->prepare('INSERT INTO import_batches (file_name, month, year, uploaded_by) VALUES (?, ?, ?, ?)');
        $insBatch->bind_param('siii', $sourceFileName, $monthInt, $yearInt, $uploadedBy);
        $insBatch->execute();
        $batchId = (int)$insBatch->insert_id;
    }

    $ctx = [
        'program' => $program,
        'programId' => $programId,
        'batchId' => $batchId,
        'importMonthRaw' => $importMonthRaw,
        'importDurationMonths' => $importDurationMonths,
        'importYearRaw' => $importYearRaw,
        'spesCategory' => $spesCategoryRaw,
        'wiirpCategory' => $wiirpCategoryRaw,
        'gipCategory' => $gipCategoryRaw,
        'jobFairEvent' => $jobFairEventRaw,
    ];

    // Server-side validation: Employers Accreditation follow-up must have all required fields
    if ($program === 'Employers Accreditation') {
        $importYear = trim((string)$importYearRaw);
        if ($importYear === '') {
            throw new RuntimeException('Please select a Year before importing Employers Accreditation data.');
        }

        foreach ($rows as $i => $r) {
            $missing = [];
            $company = s(rowValue($r, ['COMPANY', 'Company', 'CompanyName', 'company_name'], ''));
            $month = s(rowValue($r, ['MONTH', 'Month', 'month'], ''));
            $status = s(rowValue($r, ['ACCREDITATION', 'Accreditation', 'accreditation', 'status'], ''));
            $est = s(rowValue($r, ['EST. TYPE', 'Est. Type', 'Establishment Type', 'est_type'], ''));
            $industry = s(rowValue($r, ['INDUSTRY', 'Industry', 'industry'], ''));
            $city = s(rowValue($r, ['CITY/MUNICIPALITY/PROVINCE', 'City/Municipality/Province', 'City/Municipality', 'city'], ''));
            if ($company === '') $missing[] = 'Company';
            if ($month === '') $missing[] = 'Month';
            if ($status === '') $missing[] = 'Accreditation';
            if ($est === '') $missing[] = 'Est. Type';
            if ($industry === '') $missing[] = 'Industry';
            if ($city === '') $missing[] = 'City/Municipality/Province';
            if (!empty($missing)) {
                throw new RuntimeException('Accreditation row '.($i+1).' is missing required fields: '.implode(', ', $missing));
            }
        }
    }

    foreach ($rows as $row) {
        if (!empty($row['_sys_skip'])) {
            $skipped++;
            continue;
        }

        if ($program === 'Employers Accreditation') {
            $result = saveEmployersAccreditationRow($conn, $row, $ctx, $state);
            if ($result === 'saved') {
                $saved++;
            } else {
                $skipped++;
            }
            continue;
        }

        if ($program === 'Schools') {
            $result = saveSchoolsRow($conn, $row, $ctx, $state);
            if ($result === 'saved') {
                $saved++;
            } else {
                $skipped++;
            }
            continue;
        }

        if (isWhipProjectsProgram($program)) {
            $result = saveWhipProjectsRow($conn, $row, $ctx, $state);
            if ($result === 'saved') {
                $saved++;
            } else {
                $skipped++;
            }
            continue;
        }

        if (isWiirpProgram($program)) {
            $result = saveWiirpRow($conn, $row, $ctx, $state);
            if ($result === 'saved') {
                $saved++;
            } else {
                $skipped++;
            }
            continue;
        }

        if (isGipProgram($program)) {
            $result = saveGipRow($conn, $row, $ctx, $state);
            if ($result === 'saved') {
                $saved++;
            } else {
                $skipped++;
            }
            continue;
        }

        $benefId = ensurePersonBeneficiaryAndDocs($conn, $row, $ctx, $state);
        if (!$benefId) {
            $skipped++;
            continue;
        }

        if (isWhipBeneficiariesProgram($program)) {
            $result = saveWhipBeneficiariesRow($conn, $row, $benefId, $ctx, $state);
        } elseif ($program === 'Job Fair') {
            $result = saveJobFairRow($conn, $row, $benefId, $ctx, $state);
        } elseif (in_array($program, ['Job Matching and Referral', 'First Time Jobseeker'], true)) {
            $result = saveJobMatchingFamilyRow($conn, $row, $benefId, $ctx, $state);
        } elseif ($program === 'SPES') {
            // Pass category through row for SPES
            $row['_spes_category'] = $spesCategoryRaw;
            $result = saveSPESRow($conn, $row, $benefId, $ctx, $state);
        } else {
            $result = saveYouthEmployabilityRow($conn, $row, $benefId, $ctx, $state);
        }

        if ($result === 'saved') {
            $saved++;
        } else {
            $skipped++;
        }
    }

    $conn->commit();

    // Refresh employers cache if this import touched employer/accreditation data
    $employerTouchingPrograms = [
        'Employers Accreditation',
        'Job Matching and Referral',
        'First Time Jobseeker',
        'SPES',
        'Workers Hiring for Infrastructure Projects - Projects',
        'Workers Hiring for Infrastructure Projects — Projects',
    ];

    if (in_array($program, $employerTouchingPrograms, true)) {
        try {
            $cacheYear = ($importYearRaw !== '') ? (int)$importYearRaw : (int)date('Y');
            $cacheConn = new mysqli(
                $_ENV['DB_HOST'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                $_ENV['DB_NAME']
            );
            if (!$cacheConn->connect_error) {
                refreshEmployersCache($cacheConn, $cacheYear);
                $cacheConn->close();
            }
        } catch (Throwable $cacheErr) {
            // Non-fatal — log but don't fail the import response
            error_log('[save_data] Failed to refresh employers cache: ' . $cacheErr->getMessage());
        }
    }

    // Refresh WHIP cache if this import touched WHIP data
    $whipTouchingPrograms = [
        'Workers Hiring for Infrastructure Projects - Beneficiaries',
        'Workers Hiring for Infrastructure Projects — Beneficiaries',
        'Workers Hiring for Infrastructure Projects - Projects',
        'Workers Hiring for Infrastructure Projects — Projects',
    ];

    if (in_array($program, $whipTouchingPrograms, true)) {
        try {
            $whipCacheYear = ($importYearRaw !== '') ? (int)$importYearRaw : (int)date('Y');
            $whipCacheConn = new mysqli(
                $_ENV['DB_HOST'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                $_ENV['DB_NAME']
            );
            if (!$whipCacheConn->connect_error) {
                refreshWhipCache($whipCacheConn, $whipCacheYear);
                $whipCacheConn->close();
            }
        } catch (Throwable $whipCacheErr) {
            error_log('[save_data] Failed to refresh WHIP cache: ' . $whipCacheErr->getMessage());
        }
    }

    $createdEmployers = [];
    $createdEmployerIds = array_values(array_unique(array_filter(array_map('intval', $state['createdEmployerIds']))));
    if (!empty($createdEmployerIds)) {
        $placeholders = implode(', ', array_fill(0, count($createdEmployerIds), '?'));
        $stmt = $conn->prepare(
            'SELECT company_id, company_name, est_type, industry, city, batch_id
             FROM employers
             WHERE company_id IN (' . $placeholders . ')
             ORDER BY company_name ASC'
        );
        $types = str_repeat('i', count($createdEmployerIds));
        $stmt->bind_param($types, ...$createdEmployerIds);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $createdEmployers[] = [
                'company_id' => (int)($row['company_id'] ?? 0),
                'company_name' => (string)($row['company_name'] ?? ''),
                'est_type' => (string)($row['est_type'] ?? ''),
                'industry' => (string)($row['industry'] ?? ''),
                'city' => (string)($row['city'] ?? ''),
                'batch_id' => (int)($row['batch_id'] ?? 0),
            ];
        }
        $stmt->close();
    }

    $hasUndoPayload =
        !empty($state['insertedBenefIds']) ||
        !empty($state['insertedDocIds']) ||
        !empty($state['insertedJobMatchIds']) ||
        !empty($state['insertedJobFairIds']) ||
        !empty($state['insertedActivityHistoryIds']) ||
        !empty($state['insertedFirstJobSeekIds']) ||
        !empty($state['insertedWhipIds']) ||
        !empty($state['insertedWiirpIds']) ||
        !empty($state['insertedGipIds']) ||
        !empty($state['insertedProjectIds']) ||
        !empty($state['insertedSPESIds']) ||
        !empty($state['insertedSPESEmploymentIds']) ||
        !empty($state['insertedSchoolIds']) ||
        !empty($state['insertedAccreditationIds']) ||
        $batchId !== null;

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
            'expires_at' => $now + 600,
            'program' => $program,
            'batch_id' => $batchId,
            'beneficiary_ids' => array_values(array_unique(array_map('intval', $state['insertedBenefIds']))),
            'docs_ids' => array_values(array_unique(array_map('intval', $state['insertedDocIds']))),
            'jobmatch_ids' => array_values(array_unique(array_map('intval', $state['insertedJobMatchIds']))),
            'jobfair_ids' => array_values(array_unique(array_map('intval', $state['insertedJobFairIds']))),
            'activity_history_ids' => array_values(array_unique(array_map('intval', $state['insertedActivityHistoryIds']))),
            'first_job_seek_ids' => array_values(array_unique(array_map('intval', $state['insertedFirstJobSeekIds']))),
            'whip_ids' => array_values(array_unique(array_map('intval', $state['insertedWhipIds']))),
            'whip_table' => $state['insertedWhipTable'],
            'wiirp_ids' => array_values(array_unique(array_map('intval', $state['insertedWiirpIds']))),
            'wiirp_table' => $state['insertedWiirpTable'],
            'wiirp_assignment_ids' => array_values(array_unique(array_map('intval', $state['insertedWiirpAssignmentIds']))),
            'gip_ids' => array_values(array_unique(array_map('intval', $state['insertedGipIds']))),
            'project_ids' => array_values(array_unique(array_map('intval', $state['insertedProjectIds']))),
            'project_table' => $state['insertedProjectTable'],
            'spes_ids' => array_values(array_unique(array_map('intval', $state['insertedSPESIds']))),
            'spes_employment_ids' => array_values(array_unique(array_map('intval', $state['insertedSPESEmploymentIds']))),
            'school_ids' => array_values(array_unique(array_map('intval', $state['insertedSchoolIds']))),
            'employer_ids' => array_values(array_unique(array_map('intval', $state['createdEmployerIds']))),
            'accreditation_ids' => array_values(array_unique(array_map('intval', $state['insertedAccreditationIds']))),
        ];
    }

    $followupRecorded = false;
    if ($program === 'Employers Accreditation') {
        $followupRecorded = completeImportFollowup($conn, $inputBatchId);
    } elseif (!empty($createdEmployers) && $batchId) {
        $followupRecorded = recordImportFollowup($conn, $batchId, $program, [
            'program' => $program,
            'period' => trim(($importMonthRaw !== '' ? $importMonthRaw : '') . ' ' . ($importYearRaw !== '' ? $importYearRaw : '')),
            'fileName' => $sourceFileName,
            'importMonth' => $importMonthRaw,
            'importYear' => $importYearRaw,
            'batchId' => $batchId,
            'createdEmployers' => $createdEmployers,
        ]);
    }

    if (!empty($createdEmployers) && !$followupRecorded && $program !== 'Employers Accreditation') {
        $state['warnings'][] = 'Employer follow-up could not be persisted. Please complete accreditation before logging out.';
    }

    echo json_encode([
        'success' => true,
        'saved' => $saved,
        'skipped' => $skipped,
        'batch_id' => $batchId,
        'created_employers' => $createdEmployers,
        'undo_token' => $undoToken,
        'warnings' => array_values(array_unique($state['warnings'])),
        'message' => "{$saved} record(s) imported, {$skipped} skipped.",
    ]);

    if ($program === 'Employers Accreditation') {
        unset($_SESSION['pending_employer_accreditation']);
    } elseif (!empty($createdEmployers)) {
        $_SESSION['pending_employer_accreditation'] = [
            'program' => $program,
            'batch_id' => $batchId,
            'created_at' => time(),
        ];
    }
} catch (Throwable $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
