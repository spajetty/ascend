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
                    <span class="text-2xl font-bold text-gray-800">351</span>
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
                    <span class="text-2xl font-bold text-gray-800">158</span>
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
                    <span class="text-2xl font-bold text-gray-800">720</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">GIP Interns</span>
            </div>

            <!-- GIP Interns -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">184</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Work Immersion Participants</span>
            </div>

            <!-- Work Immersion -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">255</span>
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
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">February</td>
                            <td class="px-4 py-2 text-gray-600">HANSBURY INC.</td>
                            <td class="px-4 py-2 text-gray-600">February 11, 2026</td>
                            <td class="px-4 py-2 text-gray-600">May 18, 2026</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">78</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-2 py-2 text-center text-gray-600">5</td><td class="px-2 py-2 text-center font-semibold text-green-600 bg-green-50">10</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-2 py-2 text-center text-gray-600">5</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">10</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-2 py-2 text-center text-gray-600">2</td><td class="px-2 py-2 text-center font-semibold text-teal-500 bg-teal-50">5</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-orange-400 bg-orange-50">2</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-purple-400 bg-purple-50">1</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-pink-400 bg-pink-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-red-400 bg-red-50">0</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">February</td>
                            <td class="px-4 py-2 text-gray-600">KNC GROUP OF COMP.</td>
                            <td class="px-4 py-2 text-gray-600">February 18, 2026</td>
                            <td class="px-4 py-2 text-gray-600">May 5, 2026</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">60</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">163</td><td class="px-2 py-2 text-center text-gray-600">335</td><td class="px-2 py-2 text-center font-semibold text-green-600 bg-green-50">498</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">20</td><td class="px-2 py-2 text-center text-gray-600">30</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">50</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">7</td><td class="px-2 py-2 text-center text-gray-600">16</td><td class="px-2 py-2 text-center font-semibold text-teal-500 bg-teal-50">23</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">9</td><td class="px-2 py-2 text-center text-gray-600">16</td><td class="px-2 py-2 text-center font-semibold text-orange-400 bg-orange-50">25</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">3</td><td class="px-2 py-2 text-center font-semibold text-purple-400 bg-purple-50">4</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">2</td><td class="px-2 py-2 text-center font-semibold text-pink-400 bg-pink-50">2</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-red-400 bg-red-50">0</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">March</td>
                            <td class="px-4 py-2 text-gray-600">ALFAMART TRADING</td>
                            <td class="px-4 py-2 text-gray-600">March 9, 2026</td>
                            <td class="px-4 py-2 text-gray-600">June 16, 2026</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">78</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">226</td><td class="px-2 py-2 text-center text-gray-600">504</td><td class="px-2 py-2 text-center font-semibold text-green-600 bg-green-50">730</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-teal-500 bg-teal-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-orange-400 bg-orange-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-purple-400 bg-purple-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-pink-400 bg-pink-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-red-400 bg-red-50">0</td>
                        </tr>
                        <!-- TOTAL -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <td class="px-4 py-2 text-gray-800 font-bold" colspan="5">TOTAL</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">578</td><td class="px-2 py-2 text-center text-gray-700">1256</td><td class="px-2 py-2 text-center font-bold text-green-600 bg-green-100">1834</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">33</td><td class="px-2 py-2 text-center text-gray-700">88</td><td class="px-2 py-2 text-center font-bold text-blue-500 bg-blue-100">121</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">16</td><td class="px-2 py-2 text-center text-gray-700">61</td><td class="px-2 py-2 text-center font-bold text-teal-500 bg-teal-100">77</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">24</td><td class="px-2 py-2 text-center text-gray-700">61</td><td class="px-2 py-2 text-center font-bold text-orange-400 bg-orange-100">85</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">4</td><td class="px-2 py-2 text-center text-gray-700">10</td><td class="px-2 py-2 text-center font-bold text-purple-400 bg-purple-100">14</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-700">4</td><td class="px-2 py-2 text-center font-bold text-pink-400 bg-pink-100">5</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-700">1</td><td class="px-2 py-2 text-center font-bold text-red-400 bg-red-100">1</td>
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
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold border-l border-gray-100">PESO-ACCEPTED</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-teal-600">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-pink-500">T</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">January – March 2026</td>
                            <td class="px-4 py-2 text-gray-600">Davao del Sur State College</td>
                            <td class="px-4 py-2"><span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span></td>
                            <td class="px-4 py-2 text-gray-600">BSBA</td>
                            <td class="px-4 py-2 text-gray-600">PESO Digos City Office</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">300</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-600">12</td><td class="px-2 py-2 text-center font-semibold text-teal-600 bg-teal-50">20</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-2 py-2 text-center text-gray-600">8</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">13</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">February – April 2026</td>
                            <td class="px-4 py-2 text-gray-600">Holy Cross of Davao College</td>
                            <td class="px-4 py-2"><span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span></td>
                            <td class="px-4 py-2 text-gray-600">BSIT</td>
                            <td class="px-4 py-2 text-gray-600">DOLE Regional Office XI</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">300</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-2 py-2 text-center text-gray-600">15</td><td class="px-2 py-2 text-center font-semibold text-teal-600 bg-teal-50">25</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-2 py-2 text-center text-gray-600">10</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">16</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">March – May 2026</td>
                            <td class="px-4 py-2 text-gray-600">University of Southeastern Philippines</td>
                            <td class="px-4 py-2"><span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span></td>
                            <td class="px-4 py-2 text-gray-600">BSED</td>
                            <td class="px-4 py-2 text-gray-600">PESO Provincial Office</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">300</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">12</td><td class="px-2 py-2 text-center text-gray-600">18</td><td class="px-2 py-2 text-center font-semibold text-teal-600 bg-teal-50">30</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-600">12</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">20</td>
                        </tr>
                        <!-- TOTAL -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <td class="px-4 py-2 text-gray-800 font-bold" colspan="5">TOTAL</td>
                            <td class="px-4 py-2 font-bold text-gray-700">—</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">30</td><td class="px-2 py-2 text-center text-gray-700">45</td><td class="px-2 py-2 text-center font-bold text-teal-600 bg-teal-100">75</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">19</td><td class="px-2 py-2 text-center text-gray-700">30</td><td class="px-2 py-2 text-center font-bold text-pink-500 bg-pink-100">49</td>
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
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">COLLEGE / SHS</th>
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
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">January</td>
                            <td class="px-4 py-2 text-gray-600">Digos City National High School</td>
                            <td class="px-4 py-2"><span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">SHS</span></td>
                            <td class="px-4 py-2 text-gray-600">STEM</td>
                            <td class="px-4 py-2 text-gray-600">PESO Digos City Office</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">240</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-2 py-2 text-center text-gray-600">15</td><td class="px-2 py-2 text-center font-semibold text-orange-500 bg-orange-50">25</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">12</td><td class="px-2 py-2 text-center text-gray-600">18</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">30</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-2 py-2 text-center text-gray-600">15</td><td class="px-2 py-2 text-center font-semibold text-teal-500 bg-teal-50">25</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-600">12</td><td class="px-2 py-2 text-center font-semibold text-purple-500 bg-purple-50">20</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-2 py-2 text-center text-gray-600">8</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">13</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-2 text-center text-gray-600">3</td><td class="px-2 py-2 text-center font-semibold text-green-500 bg-green-50">5</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">1</td><td class="px-2 py-2 text-center font-semibold text-red-400 bg-red-50">2</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">January</td>
                            <td class="px-4 py-2 text-gray-600">Holy Cross of Davao College</td>
                            <td class="px-4 py-2"><span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span></td>
                            <td class="px-4 py-2 text-gray-600">BSBA</td>
                            <td class="px-4 py-2 text-gray-600">DOLE Regional Office XI</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">320</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-600">12</td><td class="px-2 py-2 text-center font-semibold text-orange-500 bg-orange-50">20</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-2 py-2 text-center text-gray-600">15</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">25</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-600">12</td><td class="px-2 py-2 text-center font-semibold text-teal-500 bg-teal-50">20</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-2 py-2 text-center text-gray-600">10</td><td class="px-2 py-2 text-center font-semibold text-purple-500 bg-purple-50">16</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">4</td><td class="px-2 py-2 text-center text-gray-600">6</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">10</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">3</td><td class="px-2 py-2 text-center font-semibold text-green-500 bg-green-50">4</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">1</td><td class="px-2 py-2 text-center font-semibold text-red-400 bg-red-50">2</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-semibold">March</td>
                            <td class="px-4 py-2 text-gray-600">University of Southeastern Philippines</td>
                            <td class="px-4 py-2"><span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span></td>
                            <td class="px-4 py-2 text-gray-600">BSIT</td>
                            <td class="px-4 py-2 text-gray-600">PESO Davao City</td>
                            <td class="px-4 py-2 font-semibold text-gray-700">320</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">18</td><td class="px-2 py-2 text-center text-gray-600">12</td><td class="px-2 py-2 text-center font-semibold text-orange-500 bg-orange-50">30</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">22</td><td class="px-2 py-2 text-center text-gray-600">15</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">37</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">18</td><td class="px-2 py-2 text-center text-gray-600">12</td><td class="px-2 py-2 text-center font-semibold text-teal-500 bg-teal-50">30</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">15</td><td class="px-2 py-2 text-center text-gray-600">10</td><td class="px-2 py-2 text-center font-semibold text-purple-500 bg-purple-50">25</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-600">5</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">13</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-2 py-2 text-center text-gray-600">8</td><td class="px-2 py-2 text-center font-semibold text-green-500 bg-green-50">11</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-2 text-center text-gray-600">2</td><td class="px-2 py-2 text-center font-semibold text-red-400 bg-red-50">4</td>
                        </tr>
                        <!-- TOTAL -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <td class="px-4 py-2 text-gray-800 font-bold" colspan="5">TOTAL</td>
                            <td class="px-4 py-2 font-bold text-gray-700">—</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">68</td><td class="px-2 py-2 text-center text-gray-700">83</td><td class="px-2 py-2 text-center font-bold text-orange-500 bg-orange-100">151</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">85</td><td class="px-2 py-2 text-center text-gray-700">102</td><td class="px-2 py-2 text-center font-bold text-blue-500 bg-blue-100">187</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">68</td><td class="px-2 py-2 text-center text-gray-700">83</td><td class="px-2 py-2 text-center font-bold text-teal-500 bg-teal-100">151</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">56</td><td class="px-2 py-2 text-center text-gray-700">70</td><td class="px-2 py-2 text-center font-bold text-purple-500 bg-purple-100">126</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">33</td><td class="px-2 py-2 text-center text-gray-700">43</td><td class="px-2 py-2 text-center font-bold text-pink-500 bg-pink-100">76</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">15</td><td class="px-2 py-2 text-center text-gray-700">20</td><td class="px-2 py-2 text-center font-bold text-green-500 bg-green-100">35</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-700">7</td><td class="px-2 py-2 text-center font-bold text-red-400 bg-red-100">15</td>
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

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>