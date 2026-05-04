<header class="bg-white shadow-sm px-6 md:px-8 py-4 flex items-center justify-between sticky top-0 z-20">

    <div class="flex items-center">
        <!-- Sidebar toggle (desktop only) -->
        <button id="toggleSidebar" class="hidden md:flex mr-3 p-2 rounded-full hover:bg-gray-100 hover:shadow-sm transition items-center justify-center">
            <svg id="toggleIcon" class="w-5 h-5 text-gray-600 transition-transform duration-300"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </button>
        <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">
            <?= htmlspecialchars($pageHeading, ENT_QUOTES, 'UTF-8') ?>
        </h1>
    </div>

    <!-- User pill -->
    <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 cursor-pointer hover:bg-gray-100 transition-colors">
        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">
            <?= htmlspecialchars($_initials, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <div class="hidden sm:block text-right">
            <p class="text-sm font-semibold text-gray-800 leading-tight">
                <?= htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>
            </p>
            <p class="text-xs text-gray-400">Admin</p>
        </div>
    </div>

</header>