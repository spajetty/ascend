<?php
require_once __DIR__ . '/../../includes/auth-check.php';
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../backend/import/helpers/followup_utils.php';

$currentPage = 'imports';
$pageTitle = 'ASCEND PED System – Import Data';
$pageHeading = 'Import Data';

$pendingEmployerAccreditationData = null;
if (isset($_SESSION['user_id'])) {
    $pendingEmployerAccreditationData = getLatestPendingImportFollowupForUser($conn, (int)$_SESSION['user_id']);
    if ($pendingEmployerAccreditationData) {
        $_SESSION['pending_employer_accreditation'] = [
            'program' => $pendingEmployerAccreditationData['program'],
            'batch_id' => $pendingEmployerAccreditationData['batch_id'],
            'created_at' => time(),
        ];
    }
}

$pendingEmployerAccreditationPayload = null;
if ($pendingEmployerAccreditationData) {
    $pendingEmployerAccreditationPayload = $pendingEmployerAccreditationData['payload'];
    $pendingEmployerAccreditationPayload['batchId'] = $pendingEmployerAccreditationData['batch_id'];
    $pendingEmployerAccreditationPayload['followupId'] = $pendingEmployerAccreditationData['followup_id'];
}

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<?php if ($pendingEmployerAccreditationPayload): ?>
<script>
    window.__ASCEND_PENDING_EMPLOYER_ACCRREDITATION__ = <?= json_encode($pendingEmployerAccreditationPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<?php endif; ?>

<main id="mainContent" class="flex-1 min-w-0 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>  
    <div class="px-6 md:px-8 pt-6 pb-24 md:py-6 space-y-6">

        <?php require_once __DIR__ . '/../../includes/import/tab-excel.php'; ?>
        <?php require_once __DIR__ . '/../../includes/import/tab-resume.php'; ?>
        <?php require_once __DIR__ . '/../../includes/import/tab-manual.php'; ?>

    </div>
</main>

<script src="../../assets/js/xlsx.full.min.js"></script>
<script src="../../assets/js/address-data.js"></script>
<script type="module" src="../../assets/js/loading.js?v=<?= time() ?>"></script>
<script type="module" src="../../assets/js/imports/excel.js?v=<?= time() ?>"></script>
<script type="module" src="../../assets/js/imports/resume.js?v=<?= time() ?>"></script>
<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>