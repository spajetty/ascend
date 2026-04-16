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

        /* Stat card hover */
        .stat-card {
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.10);
        }

        /* Section card hover */
        .section-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .section-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 16px 48px rgba(0,0,0,0.18);
        }

        /* Progress bar inner items */
        .prog-item {
            transition: background 0.18s;
        }
        .prog-item:hover {
            background: rgba(255,255,255,0.25) !important;
        }

        /* Gradient cards */
        .card-yellow  { background: linear-gradient(135deg, #a16207 0%, #facc15 50%, #a16207 100%); }
        .card-red     { background: linear-gradient(135deg, #7f1d1d 0%, #ef4444 50%, #7f1d1d 100%); }
        .card-blue    { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #1e3a8a 100%); }
        .card-orange  { background: linear-gradient(135deg, #9a3412 0%, #fb923c 50%, #9a3412 100%); }

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

            <a href="#" class="nav-item nav-active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="#" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-medium text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
                <span class="sidebar-text">Employment Programs</span>
            </a>

            <a href="#" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-medium text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span class="sidebar-text">Beneficiaries</span>
            </a>

            <a href="#" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-medium text-sm">
                <svg class="nav-icon w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <span class="sidebar-text">Import Data</span>
            </a>

            <a href="#" class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 font-medium text-sm">
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
            <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard Overview</h1>
        </div>
            <!-- User pill -->
            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 cursor-pointer hover:bg-gray-100 transition-colors">
                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden">
                    <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                </div>
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold text-gray-800 leading-tight">John Doe</p>
                    <p class="text-xs text-gray-400">Admin</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>
        </header>

        <div class="px-6 md:px-8 py-6 space-y-8">

            <!-- ─── STAT CARDS ─── -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Total Registered -->
                <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Total<br>Registered</p>
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-500" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 11c1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.3 3 3 3zm-8 0c1.7 0 3-1.3 3-3S9.7 5 8 5 5 6.3 5 8s1.3 3 3 3zm0 2c-2.3 0-7 1.2-7 3.5V19h14v-2.5c0-2.3-4.7-3.5-7-3.5zm8 0c-.3 0-.6 0-.9.1 1.1.8 1.9 1.8 1.9 3.4V19h6v-2.5c0-2.3-4.7-3.5-7-3.5z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl md:text-3xl font-extrabold text-gray-900">40,689</p>
                    <p class="text-xs text-emerald-500 font-semibold mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
                        8.5% Up from last month
                    </p>
                </div>

                <!-- Total Hired -->
                <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Total<br>Hired</p>
                        <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-9 9H9v-2h2v2zm0-4H9V10h2v2zm4 4h-2v-2h2v2zm0-4h-2V10h2v2zm4 4h-2v-2h2v2zm0-4h-2V10h2v2zM8 7V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H8z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl md:text-3xl font-extrabold text-gray-900">30,052</p>
                    <p class="text-xs text-emerald-500 font-semibold mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
                        11.35% Up from last month
                    </p>
                </div>

                <!-- Accredited Employers -->
                <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Accredited<br>Employers</p>
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl md:text-3xl font-extrabold text-gray-900">2,040</p>
                    <p class="text-xs text-emerald-500 font-semibold mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
                        1.8% Up from last month
                    </p>
                </div>

                <!-- Active Job Vacancies -->
                <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Active Job<br>Vacancies</p>
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 4V1L8 5l4 4V6c3.3 0 6 2.7 6 6s-2.7 6-6 6-6-2.7-6-6H4c0 4.4 3.6 8 8 8s8-3.6 8-8-3.6-8-8-8z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl md:text-3xl font-extrabold text-gray-900">1,024</p>
                    <p class="text-xs text-red-400 font-semibold mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>
                        4.3% Down from last month
                    </p>
                </div>

            </div>

            <!-- ─── SECTION OVERVIEW ─── -->
            <div>
                <h2 class="text-base font-bold text-gray-800 mb-4">Section Overview</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Employment Facilitation (Yellow) -->
                    <div class="section-card card-yellow rounded-2xl p-6 text-white shadow-md">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM8 7V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H8z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base leading-tight">Employment Facilitation</p>
                                <p class="text-xs text-white/70">Total: 12,547</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <?php
                            $ef_items = [
                                ['Job Matching', 8456],
                                ['First Time Jobseeker', 7367],
                                ['Job Fair', 3459],
                            ];
                            foreach ($ef_items as $item): ?>
                            <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                                <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                                <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Employers Engagement (Red) -->
                    <div class="section-card card-red rounded-2xl p-6 text-white shadow-md">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base leading-tight">Employers Engagement</p>
                                <p class="text-xs text-white/70">Total: 12,547</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <?php
                            $ee_items = [
                                ['Employers Accreditation', 10009],
                                ['Workers Hiring for Infrastructure Projects', 6580],
                            ];
                            foreach ($ee_items as $item): ?>
                            <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                                <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                                <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Youth Employability (Blue) -->
                    <div class="section-card card-blue rounded-2xl p-6 text-white shadow-md">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base leading-tight">Youth Employability</p>
                                <p class="text-xs text-white/70">Total: 12,547</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <?php
                            $ye_items = [
                                ['SPES Baby', 1104],
                                ['4Ps Beneficiaries', 1500],
                                ['PWD', 928],
                                ['Government Internship Program', 210],
                                ['Work Immersion and Internship Referral Program', 151],
                            ];
                            foreach ($ye_items as $item): ?>
                            <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                                <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                                <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Career Development (Orange) -->
                    <div class="section-card card-orange rounded-2xl p-6 text-white shadow-md">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base leading-tight">Career Development</p>
                                <p class="text-xs text-white/70">Total: 12,547</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <?php
                            $cd_items = [
                                ['Career Support', 4002],
                                ['LMI Orientation', 1098],
                            ];
                            foreach ($cd_items as $item): ?>
                            <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                                <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                                <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- end inner padding -->
    </main>

    <!-- ════════════════ MOBILE BOTTOM NAV ════════════════ -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-30 flex items-center justify-around px-2 py-2">
        <a href="#" class="flex flex-col items-center gap-1 px-3 py-1 rounded-xl" style="color:#f97316">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <span class="text-xs font-semibold">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2"/>
                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
            </svg>
            <span class="text-xs font-medium">Programs</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span class="text-xs font-medium">Beneficiaries</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            <span class="text-xs font-medium">Reports</span>
        </a>
        <a href="/auth/logout.php" class="flex flex-col items-center gap-1 px-3 py-1 text-gray-400">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span class="text-xs font-medium">Logout</span>
        </a>
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
</script>
</body>
</html>