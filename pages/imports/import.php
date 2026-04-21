<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'imports';
$pageTitle   = 'ASCEND PED System – Import Data';
$pageHeading = 'Import Data';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6 space-y-6">

        <?php require_once __DIR__ . '/../../includes/import/tab-nav.php'; ?>
        <?php require_once __DIR__ . '/../../includes/import/tab-excel.php'; ?>
        <?php require_once __DIR__ . '/../../includes/import/tab-resume.php'; ?>
        <?php require_once __DIR__ . '/../../includes/import/tab-manual.php'; ?>

    </div>
</main>

<script src="../../assets/js/address-data.js"></script>
<script src="../../assets/js/import.js"></script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>