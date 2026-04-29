<aside id="sidebar" class="hidden md:flex flex-col w-56 min-h-screen bg-white shadow-xl fixed top-0 left-0 z-30 py-6 px-4 transition-all duration-300">

    <!-- Logo -->
    <div class="flex items-center justify-center md:justify-start gap-3 mb-10 px-1">
        <div class="logo-bg w-10 h-10 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
            <img src="/assets/images/logo.png" alt="ASCEND logo">
        </div>
        <div class="logo-text">
            <p class="text-lg font-extrabold leading-tight text-gray-900">ASCEND</p>
            <p class="text-xs text-gray-400 font-medium">PED System</p>
        </div>
    </div>

    <!-- Desktop nav links -->
    <nav class="flex flex-col gap-1 flex-1">
        <?php foreach ($navItems as $key => $item): ?>
            <?php $isActive = $currentPage === $key; ?>
            <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
               class="nav-item <?= $isActive ? 'nav-active font-semibold' : 'text-gray-500 font-medium' ?> flex items-center gap-3 px-4 py-3 rounded-xl text-sm">
                <?= renderNavIcon($key, 'nav-icon w-5 h-5') ?>
                <span class="sidebar-text"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Logout -->
    <a href="/backend/auth/logout.php" class="logout-btn flex items-center gap-3 px-4 py-3 text-gray-400 font-medium text-sm mt-4">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span class="sidebar-text">Logout</span>
    </a>
</aside>

<!-- Mobile bottom nav -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-30 py-2 flex items-center">

    <button id="navLeft" class="px-2 text-gray-400 active:scale-95">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </button>

    <div id="mobileNav" class="flex items-center gap-6 overflow-hidden flex-1 px-2 scroll-smooth">
        <?php foreach ($navItems as $key => $item): ?>
            <?php $isActive = $currentPage === $key; ?>
            <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
               class="flex flex-col items-center gap-1 px-3 py-1 <?= $isActive ? 'text-orange-500' : 'text-gray-400' ?>">
                <?= renderNavIcon($key, 'w-5 h-5') ?>
                <span class="text-xs font-medium"><?= htmlspecialchars($item['mobileLabel'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        <?php endforeach; ?>

        <a href="/backend/auth/logout.php" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span class="text-xs font-medium">Logout</span>
        </a>
    </div>

    <button id="navRight" class="px-2 text-gray-400 active:scale-95">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    </button>
</nav>
