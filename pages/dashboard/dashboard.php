<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'dashboard';
$pageTitle   = 'ASCEND PED System – Dashboard';
$pageHeading = 'Dashboard Overview';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6 space-y-8">

        <!-- ─── STAT CARDS ─── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

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

                <!-- Employment Facilitation -->
                <div class="section-card card-yellow rounded-2xl p-6 text-white shadow-md">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM8 7V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-base leading-tight">Employment Facilitation</p>
                            <p class="text-xs text-white/70">Total: 19,282</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <?php foreach ([['Job Matching', 8456], ['First Time Jobseeker', 7367], ['Job Fair', 3459]] as $item): ?>
                        <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                            <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                            <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Employers Engagement -->
                <div class="section-card card-red rounded-2xl p-6 text-white shadow-md">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-base leading-tight">Employers Engagement</p>
                            <p class="text-xs text-white/70">Total: 16,589</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <?php foreach ([['Employers Accreditation', 10009], ['Workers Hiring for Infrastructure Projects', 6580]] as $item): ?>
                        <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                            <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                            <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Youth Employability -->
                <div class="section-card card-blue rounded-2xl p-6 text-white shadow-md">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-base leading-tight">Youth Employability</p>
                            <p class="text-xs text-white/70">Total: 3,893</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <?php foreach ([['SPES Baby', 1104], ['4Ps Beneficiaries', 1500], ['PWD', 928], ['Government Internship Program', 210], ['Work Immersion & Internship Referral', 151]] as $item): ?>
                        <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                            <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                            <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Career Development -->
                <div class="section-card card-orange rounded-2xl p-6 text-white shadow-md">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-base leading-tight">Career Development</p>
                            <p class="text-xs text-white/70">Total: 5,100</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <?php foreach ([['Career Support', 4002], ['LMI Orientation', 1098]] as $item): ?>
                        <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                            <span class="text-sm font-medium"><?= htmlspecialchars($item[0]) ?></span>
                            <span class="text-sm font-bold"><?= number_format($item[1]) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>
