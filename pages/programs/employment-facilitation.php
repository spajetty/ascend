<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employment Facilitation Section';
$pageHeading = 'Employment Facilitation Section';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 pt-6">
        <a href="/pages/programs/program.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employment Programs 
        </a>
    </div>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <!-- Total Users -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="totalUsers" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Users</span>
            </div>

            <!-- Total Employers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="totalEmployers" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Employers</span>
            </div>

            <!-- Total Job Fair Vacancies -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-yellow-400">
                <div class="flex items-center justify-between">
                    <span id="totalJobFairVacancies" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-yellow-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Job Fair Vacancies</span>
            </div>

            <!-- Total First Time Job Seekers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span id="firstTimeJobSeekers" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total First Time Job Seekers</span>
            </div>

        </div>

        <!-- Job Matching & Referral Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Job Matching & Referral</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <!-- Registered -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-teal-600">T</th>
                            <!-- Referred -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <!-- Interviewed -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <!-- Qualified -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <!-- Not Qualified -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <!-- Placed/HOTS -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <!-- For Further Interview -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="jobMatchingTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="21" class="px-4 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/job-match.php" class="text-sm text-teal-600 hover:text-teal-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- First-Time Jobseekers Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">First-Time Jobseekers</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold tracking-wide border-l border-gray-100">FIRST-TIME JOBSEEKER</th>
                            <th colspan="2" class="px-2 py-2 text-center text-teal-500 font-semibold tracking-wide border-l border-gray-100">ISSUED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-pink-500">T</th>
                            <th class="px-3 py-1 text-center text-teal-500 font-medium border-l border-gray-100">Occ. Permit</th>
                            <th class="px-3 py-1 text-center text-teal-500 font-medium">Health Card</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="firstTimeTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="21" class="px-4 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/first-time.php" class="text-sm text-purple-600 hover:text-purple-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- Job Fair Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Job Fair</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-40 border-l border-gray-100" rowspan="2">PARTICIPATING EMPLOYER</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="jobFairTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="20" class="px-4 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/job-fair.php" class="text-sm text-blue-600 hover:text-blue-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<script>
async function loadEmploymentFacilitationData() {
    try {
        const formatMonthYear = (month, year) => {
            const parts = [month, year].filter(Boolean);
            return parts.length ? parts.join(' ') : '—';
        };
        const dash = '<td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>';

        // Load summary stats
        const statsRes = await fetch('/api/get-employment-facilitation-stats.php');
        const statsData = await statsRes.json();
        if (statsData.success) {
            document.getElementById('totalUsers').textContent = statsData.data.totalUsers;
            document.getElementById('totalEmployers').textContent = statsData.data.totalEmployers;
            document.getElementById('totalJobFairVacancies').textContent = statsData.data.totalJobFairVacancies;
            document.getElementById('firstTimeJobSeekers').textContent = statsData.data.firstTimeJobSeekers;
        }

        // Load Job Matching data
        const jobMatchRes = await fetch('/api/get-job-matching-data.php');
        const jobMatchData = await jobMatchRes.json();
        if (jobMatchData.success && jobMatchData.data.length > 0) {
            const tbody = document.getElementById('jobMatchingTableBody');
            tbody.innerHTML = jobMatchData.data.map(row => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-medium">${row.month} ${row.year}</td>
                    ${Array.from({ length: 20 }).map(() => dash).join('')}
                </tr>
            `).join('');
        } else {
            document.getElementById('jobMatchingTableBody').innerHTML = `<tr class="border-b border-gray-50 hover:bg-gray-50"><td colspan="21" class="px-4 py-8 text-center text-gray-500">No data found.</td></tr>`;
        }

        const firstTimeRes = await fetch('/api/get-first-time-jobseek-data.php');
        const firstTimeData = await firstTimeRes.json();
        const firstTimeBody = document.getElementById('firstTimeTableBody');
        if (firstTimeData.success && firstTimeData.data.length > 0) {
            firstTimeBody.innerHTML = firstTimeData.data.map(row => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-medium">${formatMonthYear(row.month, row.year)}</td>
                    <td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>
                    <td class="px-3 py-2 text-center text-gray-400">—</td>
                    <td class="px-3 py-2 text-center font-semibold text-pink-500 bg-pink-50">${row.jobseek ?? 0}</td>
                    <td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50 border-l border-gray-100">${row.occ_permit ?? 0}</td>
                    <td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50">${row.health_card ?? 0}</td>
                    ${Array.from({ length: 15 }).map(() => '<td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>').join('')}
                </tr>
            `).join('');
        } else {
            firstTimeBody.innerHTML = `<tr class="border-b border-gray-50 hover:bg-gray-50"><td colspan="21" class="px-4 py-8 text-center text-gray-500">No data found.</td></tr>`;
        }

        const jobFairRes = await fetch('/api/get-job-fair-data.php');
        const jobFairData = await jobFairRes.json();
        const jobFairBody = document.getElementById('jobFairTableBody');
        if (jobFairData.success && jobFairData.data.length > 0) {
            jobFairBody.innerHTML = jobFairData.data.map(row => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-medium">${formatMonthYear(row.month, row.year)}</td>
                    <td class="px-4 py-2 text-gray-600 border-l border-gray-100">${row.company_name || '—'}</td>
                    <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">${row.vacancy_male ?? 0}</td>
                    <td class="px-3 py-2 text-center text-blue-500">${row.vacancy_female ?? 0}</td>
                    <td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">${row.total_vacancies ?? 0}</td>
                    ${Array.from({ length: 15 }).map(() => '<td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>').join('')}
                </tr>
            `).join('');
        } else {
            jobFairBody.innerHTML = `<tr class="border-b border-gray-50 hover:bg-gray-50"><td colspan="20" class="px-4 py-8 text-center text-gray-500">No data found.</td></tr>`;
        }
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

// Load data when page is ready
document.addEventListener('DOMContentLoaded', loadEmploymentFacilitationData);
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>
