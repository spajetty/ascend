<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['data']) || !isset($input['program'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request: missing program or data']);
    exit;
}

$program = trim((string)$input['program']);
$rows    = $input['data'];

if (empty($rows) || !is_array($rows)) {
    echo json_encode(['success' => false, 'error' => 'No records to import']);
    exit;
}

// ── Shared helpers (same as save_data.php) ───────────────────────────────────
require_once __DIR__ . '/../import/helpers/db_utils.php';
require_once __DIR__ . '/../import/helpers/formatting_utils.php';
require_once __DIR__ . '/../import/helpers/program_utils.php';
require_once __DIR__ . '/../import/helpers/followup_utils.php';

// ── Row savers ────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../import/savers/save_common_person.php';
require_once __DIR__ . '/../import/savers/save_job_matching.php';
require_once __DIR__ . '/../import/savers/save_job_fair.php';
require_once __DIR__ . '/../import/savers/save_spes.php';
require_once __DIR__ . '/../import/savers/save_gip.php';
require_once __DIR__ . '/../import/savers/save_wiirp.php';

$conn->begin_transaction();

$saved   = 0;
$skipped = 0;

$state = [
    'insertedBenefIds'            => [],
    'jobFairBeneficiaryMap'       => [],
    'insertedDocIds'              => [],
    'insertedJobMatchIds'         => [],
    'insertedJobFairIds'          => [],
    'insertedActivityHistoryIds'  => [],
    'insertedFirstJobSeekIds'     => [],
    'insertedWhipIds'             => [],
    'insertedWhipTable'           => null,
    'insertedWiirpIds'            => [],
    'insertedWiirpTable'          => null,
    'insertedWiirpAssignmentIds'  => [],
    'insertedGipIds'              => [],
    'insertedProjectIds'          => [],
    'insertedProjectTable'        => null,
    'insertedSPESIds'             => [],
    'insertedSPESEmploymentIds'   => [],
    'createdEmployerIds'          => [],
    'warnings'                    => [],
];

try {
    $programId = resolveProgramId($conn, $program);

    $ctx = [
        'program'               => $program,
        'programId'             => $programId,
        'batchId'               => null,
        'importMonthRaw'        => '',
        'importYearRaw'         => '',
        'importDurationMonths'  => 3,
        'spesCategory'          => '',
        'wiirpCategory'         => '',
        'gipCategory'           => '',
        'jobFairEvent'          => '',
        'userId'                => isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0,
    ];

    // Only one resume is allowed per import request. Enforce server-side.
    if (count($rows) !== 1) {
        throw new RuntimeException('Only one resume may be imported at a time.');
    }

    // Validate required fields for the single resume
    $row = $rows[0];
    $classification = trim((string)($row['classification'] ?? $row['Classification'] ?? ''));
    // WHIP (Workers Hiring for Infrastructure Projects) does not require classification.
    if (stripos($program, 'Workers Hiring for Infrastructure Projects') === false && $classification === '') {
        throw new RuntimeException('Resume is missing Classification.');
    }

    foreach ($rows as $row) {
        // Normalise field names so the common-person saver finds them
        // (the resume parser uses snake_case keys; savers also accept these)
        $benefId = ensurePersonBeneficiaryAndDocs($conn, $row, $ctx, $state);

        if (!$benefId) {
            $skipped++;
            continue;
        }

        // Program-specific follow-up row
        if (in_array($program, ['Job Matching and Referral', 'First Time Jobseeker', 'Job Fair'], true)) {
            saveJobMatchingFamilyRow($conn, $row, $benefId, $ctx, $state);
        } elseif ($program === 'SPES') {
            saveSPESRow($conn, $row, $benefId, $ctx, $state);
        } elseif (isGipProgram($program)) {
            // GIP is person-only; the saveGipRow expects to call ensurePerson itself,
            // so we just record the benef_id here — no duplicate insert needed.
        } elseif (isWiirpProgram($program)) {
            saveWiirpRow($conn, $row, $ctx, $state);
        }

        $saved++;
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'saved'   => $saved,
        'skipped' => $skipped,
        'message' => "{$saved} record(s) imported successfully" . ($skipped ? ", {$skipped} skipped." : '.'),
    ]);

} catch (Throwable $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
