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
$spesCategoryRaw = trim((string)($input['spesCategory'] ?? ''));
$wiirpCategoryRaw = trim((string)($input['wiirpCategory'] ?? ''));

// Shared helpers
require_once __DIR__ . '/helpers/db_utils.php';
require_once __DIR__ . '/helpers/formatting_utils.php';
require_once __DIR__ . '/helpers/program_utils.php';

// Row savers
require_once __DIR__ . '/savers/save_common_person.php';
require_once __DIR__ . '/savers/save_employers_accreditation.php';
require_once __DIR__ . '/savers/save_whip_projects.php';
require_once __DIR__ . '/savers/save_whip_beneficiaries.php';
require_once __DIR__ . '/savers/save_wiirp.php';
require_once __DIR__ . '/savers/save_job_matching.php';
require_once __DIR__ . '/savers/save_spes.php';
require_once __DIR__ . '/savers/save_schools.php';

$conn->begin_transaction();

$saved = 0;
$skipped = 0;
$batchId = null;
$undoToken = null;

$state = [
    'insertedBenefIds' => [],
    'insertedDocIds' => [],
    'insertedJobMatchIds' => [],
    'insertedJobFairIds' => [],
    'insertedFirstJobSeekIds' => [],
    'insertedWhipIds' => [],
    'insertedWhipTable' => null,
    'insertedWiirpIds' => [],
    'insertedWiirpTable' => null,
    'insertedWiirpPrivateIds' => [],
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
        'Workers Hiring for Infrastructure Projects - Beneficiaries',
        'Workers Hiring for Infrastructure Projects — Beneficiaries',
    ];
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
        'importYearRaw' => $importYearRaw,
        'spesCategory' => $spesCategoryRaw,
        'wiirpCategory' => $wiirpCategoryRaw,
    ];

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

        $benefId = ensurePersonBeneficiaryAndDocs($conn, $row, $ctx, $state);
        if (!$benefId) {
            $skipped++;
            continue;
        }

        if (isWhipBeneficiariesProgram($program)) {
            $result = saveWhipBeneficiariesRow($conn, $row, $benefId, $ctx, $state);
        } elseif (in_array($program, ['Job Matching and Referral', 'Job Fair', 'First Time Jobseeker'], true)) {
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

    $hasUndoPayload =
        !empty($state['insertedBenefIds']) ||
        !empty($state['insertedDocIds']) ||
        !empty($state['insertedJobMatchIds']) ||
        !empty($state['insertedJobFairIds']) ||
        !empty($state['insertedFirstJobSeekIds']) ||
        !empty($state['insertedWhipIds']) ||
        !empty($state['insertedWiirpIds']) ||
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
            'first_job_seek_ids' => array_values(array_unique(array_map('intval', $state['insertedFirstJobSeekIds']))),
            'whip_ids' => array_values(array_unique(array_map('intval', $state['insertedWhipIds']))),
            'whip_table' => $state['insertedWhipTable'],
            'wiirp_ids' => array_values(array_unique(array_map('intval', $state['insertedWiirpIds']))),
            'wiirp_table' => $state['insertedWiirpTable'],
            'wiirp_private_ids' => array_values(array_unique(array_map('intval', $state['insertedWiirpPrivateIds']))),
            'project_ids' => array_values(array_unique(array_map('intval', $state['insertedProjectIds']))),
            'project_table' => $state['insertedProjectTable'],
            'spes_ids' => array_values(array_unique(array_map('intval', $state['insertedSPESIds']))),
            'spes_employment_ids' => array_values(array_unique(array_map('intval', $state['insertedSPESEmploymentIds']))),
            'school_ids' => array_values(array_unique(array_map('intval', $state['insertedSchoolIds']))),
            'employer_ids' => array_values(array_unique(array_map('intval', $state['createdEmployerIds']))),
            'accreditation_ids' => array_values(array_unique(array_map('intval', $state['insertedAccreditationIds']))),
        ];
    }

    echo json_encode([
        'success' => true,
        'saved' => $saved,
        'skipped' => $skipped,
        'batch_id' => $batchId,
        'undo_token' => $undoToken,
        'warnings' => array_values(array_unique($state['warnings'])),
        'message' => "{$saved} record(s) imported, {$skipped} skipped.",
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
