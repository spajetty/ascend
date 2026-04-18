<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employment Programs';
$pageHeading = 'Employment Programs';

require_once __DIR__ . '/../../includes/layout-head.php';
require_once __DIR__ . '/../../includes/layout-sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout-topbar.php'; ?>

    <div class="px-6 md:px-8 py-6">
        <!-- Employment Programs content goes here -->
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/layout-footer.php'; ?>
