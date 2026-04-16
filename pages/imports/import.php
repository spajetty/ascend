<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /pages/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASCEND PED System – Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Collapsed sidebar */
        .sidebar-collapsed {
            width: 5rem !important;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        /* Hide text */
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .logo-text {
            display: none;
        }

        /* Center everything */
        .sidebar-collapsed .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        /* Keep icons visible and slightly bigger */
        .sidebar-collapsed .nav-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Center logo */
        .sidebar-collapsed .logo-bg {
            margin: 0 auto;
        }

        .sidebar-collapsed nav {
            gap: 0.75rem;
        }

        /* Sidebar gradient active pill */
        .nav-active {
            background: linear-gradient(90deg, #3b82f6 0%, #ef4444 50%, #eab308 100%);
            color: #fff;
            box-shadow: 0 4px 18px 0 rgba(59,130,246,0.35);
        }

        /* Sidebar hover */
        .nav-item {
            transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.15s;
        }

        .nav-item:hover:not(.nav-active) {
            background: linear-gradient(90deg, rgba(59,130,246,0.12) 0%, rgba(239,68,68,0.12) 50%, rgba(234,179,8,0.12) 100%);
            color: #f97316;
            transform: translateX(3px);
        }

        .nav-item:hover .nav-icon {
            color: #f97316;
        }

        /* Logout hover */
        .logout-btn {
            transition: color 0.18s, background 0.18s;
        }
        .logout-btn:hover {
            color: #ef4444;
            background: rgba(239,68,68,0.08);
            border-radius: 0.5rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex">

    <!-- ═══════════════════════════ SIDEBAR ═══════════════════════════ -->
    <aside id="sidebar" class="hidden md:flex flex-col w-56 min-h-screen bg-white shadow-xl fixed top-0 left-0 z-30 py-6 px-4 transition-all duration-300">

        <!-- Logo -->
        <div class="flex items-center justify-center md:justify-start gap-3 mb-10 px-1">
            <div class="logo-bg w-10 h-10 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                <img src="/assets/images/logo.png" alt="">
            </div>
            <div class="logo-text">
                <p class="text-lg font-800 font-extrabold leading-tight text-gray-900">ASCEND</p>
                <p class="text-xs text-gray-400 font-medium">PED System</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-1 flex-1">

            <a href="/pages/dashboard/dashboard.php" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-semibold text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="/pages/programs/program.php" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-medium text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
                <span class="sidebar-text">Employment Programs</span>
            </a>

            <a href="/pages/beneficiaries/beneficiary.php" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-medium text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span class="sidebar-text">Beneficiaries</span>
            </a>

            <a href="/pages/imports/import.php" class="nav-item nav-active flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <span class="sidebar-text">Import Data</span>
            </a>

            <a href="/pages/reports/report.php" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-medium text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                <span class="sidebar-text">Reports</span>
            </a>
        </nav>

        <!-- Logout -->
        <a href="/auth/logout.php" class="logout-btn flex items-center gap-3 px-4 py-3 text-gray-400 font-medium text-sm mt-4">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span class="sidebar-text">Logout</span>
        </a>
    </aside>

    <!-- ════════════════════════ MAIN CONTENT ════════════════════════ -->
    <main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
        <!-- Top bar -->
        <header class="bg-white shadow-sm px-6 md:px-8 py-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center">
                <button id="toggleSidebar" class="hidden md:flex mr-3 p-2 rounded-full hover:bg-gray-100 hover:shadow-sm transition items-center justify-center">
                    <svg id="toggleIcon" class="w-5 h-5 text-gray-600 transition-transform duration-300"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>
                <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">Import Data</h1>
            </div>

            <!-- User pill -->
            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 cursor-pointer hover:bg-gray-100 transition-colors">
                <?php
                $name = $_SESSION['user_name'] ?? 'User';
                $initials = '';
                $parts = explode(' ', $name);
                foreach ($parts as $p) {
                    $initials .= strtoupper($p[0]);
                }
                ?>

                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">
                    <?= $initials ?>
                </div>
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold text-gray-800 leading-tight">
                        <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                    </p>
                    <p class="text-xs text-gray-400">Admin</p>
                </div>
                <!--<svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg> -->
            </div>
        </header>
    </main>

    <!-- ════════════════ MOBILE BOTTOM NAV ════════════════ -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-30 py-2 flex items-center">

        <button id="navLeft" class="px-2 text-gray-400 active:scale-95">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </button>

        <div id="mobileNav" class="flex items-center gap-6 overflow-hidden flex-1 px-2 scroll-smooth">

            <a href="/pages/dashboard/dashboard.php" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span class="text-xs font-semibold">Dashboard</span>
            </a>

            <a href="/pages/programs/program.php" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                </svg>
                <span class="text-xs font-medium">Programs</span>
            </a>

            <a href="/pages/beneficiaries/beneficiary.php" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span class="text-xs font-medium">Beneficiaries</span>
            </a>

            <a href="/pages/imports/import.php" class="flex flex-col items-center gap-1 px-3 py-1 text-orange-500">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <span class="text-xs font-medium">Import</span>
            </a>

            <a href="/pages/reports/report.php" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                <span class="text-xs font-medium">Reports</span>
            </a>

            <a href="/auth/logout.php" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
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

    <!-- bottom padding for mobile nav -->
    <div class="md:hidden h-20"></div>

    <script>
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const main = document.getElementById('mainContent');
const icon = document.getElementById('toggleIcon');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('sidebar-collapsed');

    if (sidebar.classList.contains('sidebar-collapsed')) {
        main.classList.remove('md:ml-56');
        main.classList.add('md:ml-20');
        icon.style.transform = 'rotate(180deg)';
    } else {
        main.classList.remove('md:ml-20');
        main.classList.add('md:ml-56');
        icon.style.transform = 'rotate(0deg)';
    }
});
const nav = document.getElementById('mobileNav');
const left = document.getElementById('navLeft');
const right = document.getElementById('navRight');

const scrollAmount = 120;

left.addEventListener('click', () => {
    nav.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
});

right.addEventListener('click', () => {
    nav.scrollBy({ left: scrollAmount, behavior: 'smooth' });
});
</script>

</body>
</html>