<?php
require_once __DIR__ . '/../../includes/auth-check.php';
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../backend/import/helpers/followup_utils.php';

$currentPage = 'beneficiaries';
$pageTitle   = 'ASCEND PED System – Beneficiaries';
$pageHeading = 'Beneficiaries';

$pendingFollowup = isset($_SESSION['user_id'])
    ? getLatestPendingImportFollowupForUser($conn, (int)$_SESSION['user_id'])
    : null;
$pendingEmployerAccreditation = $pendingFollowup !== null;
if ($pendingEmployerAccreditation) {
    $_SESSION['pending_employer_accreditation'] = [
        'program' => $pendingFollowup['program'],
        'batch_id' => $pendingFollowup['batch_id'],
        'created_at' => time(),
    ];
} else {
    unset($_SESSION['pending_employer_accreditation']);
}

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<link rel="stylesheet" href="assets/css/beneficiaries.css">

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6" style="background:var(--bg,#f4f6f9); min-height:100vh;">

        <?php if ($pendingEmployerAccreditation): ?>
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-900 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-800">
                        Pending employer accreditation
                    </div>
                    <h2 class="mt-3 text-lg font-bold text-amber-950">Some beneficiaries from the latest import are hidden</h2>
                    <p class="mt-2 text-sm leading-6 text-amber-900/90">
                        You can still use the Beneficiaries page, but records from the latest batch are hidden until the employer accreditation step is completed.
                    </p>
                    <p class="mt-1 text-sm leading-6 text-amber-900/90">
                        Please finish the import follow-up in order to view those beneficiaries again.
                    </p>
                    <?php if ($pendingFollowup): ?>
                        <p class="mt-2 text-xs font-medium text-amber-800">
                            Batch #<?= (int)$pendingFollowup['batch_id'] ?> • <?= htmlspecialchars((string)$pendingFollowup['program']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <a href="../../pages/imports/import.php" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-amber-700">
                    Go to Import
                </a>
            </div>
        </div>
        <?php endif; ?>

        <?php require_once __DIR__ . '/partials/list-view.php'; ?>

        <?php require_once __DIR__ . '/partials/profile-view.php'; ?>

    </div>
</main>

<?php require_once __DIR__ . '/partials/drive-modal.php'; ?>
<?php require_once __DIR__ . '/partials/timeline-modals.php'; ?>
<?php require_once __DIR__ . '/partials/edit-modals.php'; ?>

<script type="module">
    import '../../assets/js/loading.js';
</script>
<script src="assets/js/data.js"></script>
<script src="assets/js/table.js"></script>
<script src="assets/js/profile.js"></script>
<script src="assets/js/documents.js"></script>
<script type="module" src="../../assets/js/toast.js"></script>
<script src="assets/js/timeline.js"></script>
<script src="assets/js/init.js"></script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>