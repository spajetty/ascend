<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Youth Employability Section';
$pageHeading = 'Youth Employability Section';

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
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">

            <!-- Total Youth Served -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="totalYouthServed" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Youth Served</span>
            </div>

            <!-- Total Registered -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="spesParticipants" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">SPES Participants</span>
            </div>

            <!-- Total Referred -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span id="gipInterns" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">GIP Interns</span>
            </div>

            <!-- Work Immersion -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span id="workImmersionParticipants" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Work Immersion Participants</span>
            </div>

            <!-- Total Hired / Placed -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="totalHired" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Hired / Placed</span>
            </div>

        </div>

        <!-- SPES Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Special Program for Employment of Students (SPES)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">MONTH REPORTED</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">EMPLOYER</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">START OF CONTRACT</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">END OF CONTRACT</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">DAYS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-600 font-semibold border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-500 font-semibold border-l border-gray-100">PLACED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold border-l border-gray-100">SPES BABY</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-400 font-semibold border-l border-gray-100">4PS BENEFICIARIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold border-l border-gray-100">PWD</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <!-- Registered -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-green-600">T</th>
                            <!-- Referred -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-blue-500">T</th>
                            <!-- Placed -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-teal-500">T</th>
                            <!-- Job Vacancies -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-orange-400">T</th>
                            <!-- SPES Baby -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-purple-400">T</th>
                            <!-- 4Ps Beneficiaries -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-pink-400">T</th>
                            <!-- PWD -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-red-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="spesTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="28" class="px-4 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/youth-emp/spes.php" class="text-sm text-green-600 hover:text-green-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- GIP Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Government Internship Program (GIP)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">CONTRACT PERIOD</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">SCHOOL</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">COLLEGE / SHS</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">COURSE</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">OFFICE ASSIGNMENT</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">REQ. HRS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold border-l border-gray-100">PARTICIPANTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">INQUIRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-500 font-semibold border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-500 font-semibold border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold border-l border-gray-100">PESO-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold border-l border-gray-100">PRIVATE-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold border-l border-gray-100">NOT PROCEEDED</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <!-- Participants -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-teal-600">T</th>
                            <!-- Inquired -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-blue-500">T</th>
                            <!-- Referred -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-teal-500">T</th>
                            <!-- Interviewed -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-purple-500">T</th>
                            <!-- PESO-Accepted -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-pink-500">T</th>
                            <!-- Private-Accepted -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-green-500">T</th>
                            <!-- Not Proceeded -->
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-red-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="gipTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="27" class="px-4 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/youth-emp/gip.php" class="text-sm text-teal-600 hover:text-teal-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- WIIRP Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Work Immersion &amp; Internship Referral Program</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">CONTRACT PERIOD</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">SCHOOL</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">YEAR LEVEL</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">COURSE</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">OFFICE ASSIGNMENT</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">REQ. HRS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-500 font-semibold border-l border-gray-100">PARTICIPANTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">INQUIRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-500 font-semibold border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-500 font-semibold border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold border-l border-gray-100">PESO-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold border-l border-gray-100">PRIVATE-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold border-l border-gray-100">NOT PROCEEDED</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-orange-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-teal-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-purple-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-pink-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-red-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="workImmersionTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="27" class="px-4 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/youth-emp/work-imm.php" class="text-sm text-orange-600 hover:text-orange-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<script>
async function loadYouthEmployabilityData() {
    try {
        // Load summary stats
        const statsRes = await fetch('/api/get-youth-employability-stats.php');
        const statsData = await statsRes.json();
        if (statsData.success) {
            document.getElementById('totalYouthServed').textContent = statsData.data.totalYouthServed;
            document.getElementById('spesParticipants').textContent = statsData.data.spesParticipants;
            document.getElementById('gipInterns').textContent = statsData.data.gipInterns;
            document.getElementById('workImmersionParticipants').textContent = statsData.data.workImmersionParticipants;
            document.getElementById('totalHired').textContent = statsData.data.totalHired;
        }

        // Load SPES data
        const spesRes = await fetch('/api/get-spes-data.php');
        const spesData = await spesRes.json();
        if (spesData.success && spesData.data.length > 0) {
            const tbody = document.getElementById('spesTableBody');
            tbody.innerHTML = spesData.data.map(row => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-semibold">${row.month_reported}</td>
                    <td class="px-4 py-2 text-gray-600">${row.employer}</td>
                    <td class="px-4 py-2 text-gray-600">${row.start_of_contract}</td>
                    <td class="px-4 py-2 text-gray-600">${row.end_of_contract}</td>
                    <td class="px-4 py-2 font-semibold text-gray-700">${row.days}</td>
                    <td colspan="23" class="px-4 py-2 text-center text-gray-500 text-xs">Additional data columns - customize as needed</td>
                </tr>
            `).join('');
        }

        // Load GIP data
        const gipRes = await fetch('/api/get-gip-data.php');
        const gipData = await gipRes.json();
        if (gipData.success && gipData.data.length > 0) {
            const tbody = document.getElementById('gipTableBody');
            tbody.innerHTML = gipData.data.map(row => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-semibold">${row.contract_period}</td>
                    <td class="px-4 py-2 text-gray-600">${row.school}</td>
                    <td class="px-4 py-2"><span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">${row.college_or_shs}</span></td>
                    <td class="px-4 py-2 text-gray-600">${row.course}</td>
                    <td class="px-4 py-2 text-gray-600">${row.office_assignment}</td>
                    <td class="px-4 py-2 font-semibold text-gray-700">${row.required_hours}</td>
                    <td colspan="21" class="px-4 py-2 text-center text-gray-500 text-xs">Additional data columns - customize as needed</td>
                </tr>
            `).join('');
        }

        // Load Work Immersion data
        const wiRes = await fetch('/api/get-work-immersion-data.php');
        const wiData = await wiRes.json();
        if (wiData.success && wiData.data.length > 0) {
            const tbody = document.getElementById('workImmersionTableBody');
            tbody.innerHTML = wiData.data.map(row => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-semibold">${row.contract_period}</td>
                    <td class="px-4 py-2 text-gray-600">${row.school}</td>
                    <td class="px-4 py-2"><span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">${row.education_level}</span></td>
                    <td class="px-4 py-2 text-gray-600">${row.course}</td>
                    <td class="px-4 py-2 text-gray-600">${row.office_assignment}</td>
                    <td class="px-4 py-2 font-semibold text-gray-700">${row.required_hours}</td>
                    <td colspan="21" class="px-4 py-2 text-center text-gray-500 text-xs">Additional data columns - customize as needed</td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

// Load data when page is ready
document.addEventListener('DOMContentLoaded', loadYouthEmployabilityData);
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>