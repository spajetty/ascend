<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'beneficiaries';
$pageTitle   = 'ASCEND PED System – Beneficiaries';
$pageHeading = 'Beneficiaries';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<link rel="stylesheet" href="assets/css/beneficiaries.css">

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6" style="background:var(--bg,#f4f6f9); min-height:100vh;">

        <?php require_once __DIR__ . '/partials/list-view.php'; ?>

        <?php require_once __DIR__ . '/partials/profile-view.php'; ?>

    </div>
</main>

<?php require_once __DIR__ . '/partials/drive-modal.php'; ?>

<script src="assets/js/data.js"></script>
<script src="assets/js/table.js"></script>
<script src="assets/js/profile.js"></script>
<script src="assets/js/documents.js"></script>
<script src="assets/js/timeline.js"></script>
<script src="assets/js/init.js"></script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>