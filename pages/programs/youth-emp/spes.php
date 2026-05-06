<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – SPES';
$pageHeading = 'Special Program for Employment of Students (SPES)';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 pt-6">
        <a href="/pages/programs/youth-employability.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Youth Employability Programs
        </a>
    </div>

    <div class="px-6 md:px-8 py-2 pb-8">

        <!-- ===== SUMMARY CARDS ===== -->
        <!-- Row 1: Core pipeline metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">

            <!-- Total Registered -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">110</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Registered</span>
            </div>

            <!-- Total Referred -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">90</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Referred</span>
            </div>

            <!-- Total Placed -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">75</span>
                    </div>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Placed</span>
            </div>

            <!-- Job Vacancies -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">132</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Job Vacancies</span>
            </div>
        </div>

        <!-- Row 2: Beneficiary breakdown -->
        <div class="grid grid-cols-3 gap-4 mb-8">

            <!-- SPES Baby -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">47</span>
                    <div class="bg-pink-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">SPES Baby Beneficiaries</span>
            </div>

            <!-- 4Ps Beneficiaries -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">37</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">4Ps Beneficiaries</span>
            </div>

            <!-- PWD -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">47</span>
                    <div class="bg-cyan-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">PWD Beneficiaries</span>
            </div>
        </div>

        <!-- ===== FILTER BAR ===== -->
        <div class="flex items-center gap-3 mb-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                    <option>2026</option>
                    <option>2025</option>
                    <option>2024</option>
                </select>
            </div>
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchEmployer" placeholder="Search employer..."
                    oninput="filterTable()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
            </div>
        </div>

        <!-- ===== MAIN SPES TABLE ===== -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Special Program for Employment of Students (SPES)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="spesTable">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-3 py-2 text-gray-500 font-medium w-24" rowspan="2">MONTH<br>REPORTED</th>
                            <th class="text-left px-3 py-2 text-gray-500 font-medium" rowspan="2">EMPLOYER</th>
                            <th class="text-left px-3 py-2 text-gray-500 font-medium w-28" rowspan="2">START OF<br>CONTRACT</th>
                            <th class="text-left px-3 py-2 text-gray-500 font-medium w-28" rowspan="2">END OF<br>CONTRACT</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium w-12" rowspan="2">DAYS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">PLACED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold tracking-wide border-l border-gray-100">SPES BABY</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-500 font-semibold tracking-wide border-l border-gray-100">4PS BENEFICIARIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">PWD</th>
                            <th class="px-2 py-2 text-center text-gray-400 font-semibold tracking-wide border-l border-gray-100" rowspan="2">ACTIONS</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <!-- Registered -->
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-teal-600">T</th>
                            <!-- Referred -->
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-blue-500">T</th>
                            <!-- Placed -->
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-green-500">T</th>
                            <!-- Job Vacancies -->
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-orange-400">T</th>
                            <!-- SPES Baby -->
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-pink-500">T</th>
                            <!-- 4Ps -->
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-purple-500">T</th>
                            <!-- PWD -->
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-1 text-center font-semibold text-cyan-500">T</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- February 2026 - Row 1 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="hansbury inc">
                            <td class="px-3 py-2 text-gray-700 font-medium">February</td>
                            <td class="px-3 py-2 text-gray-700">HANSBURY INC. (TOK...)</td>
                            <td class="px-3 py-2 text-gray-600">February 11, 2026</td>
                            <td class="px-3 py-2 text-gray-600">May 16, 2026</td>
                            <td class="px-3 py-2 text-center text-gray-700 font-medium">78</td>
                            <!-- Registered -->
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-2 py-2 text-center text-gray-600">5</td><td class="px-2 py-2 text-center font-semibold text-teal-600 bg-teal-50">10</td>
                            <!-- Referred -->
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-2 py-2 text-center text-gray-600">5</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">10</td>
                            <!-- Placed -->
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-2 py-2 text-center text-gray-600">2</td><td class="px-2 py-2 text-center font-semibold text-green-500 bg-green-50">5</td>
                            <!-- Job Vacancies -->
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-2 py-2 text-center text-gray-600">2</td><td class="px-2 py-2 text-center font-semibold text-orange-400 bg-orange-50">5</td>
                            <!-- SPES Baby -->
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">1</td>
                            <!-- 4Ps -->
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-purple-500 bg-purple-50">0</td>
                            <!-- PWD -->
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">0</td>
                            <!-- Actions -->
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="1">
                                    <button onclick="toggleEditMode(1)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 1)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 1)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 1)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- February 2026 - Row 2 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="knc group of comp">
                            <td class="px-3 py-2 text-gray-700 font-medium">February</td>
                            <td class="px-3 py-2 text-gray-700">KNC GROUP OF COMP...</td>
                            <td class="px-3 py-2 text-gray-600">February 18, 2026</td>
                            <td class="px-3 py-2 text-gray-600">May 9, 2026</td>
                            <td class="px-3 py-2 text-center text-gray-700 font-medium">60</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">163</td><td class="px-2 py-2 text-center text-gray-600">335</td><td class="px-2 py-2 text-center font-semibold text-teal-600 bg-teal-50">498</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">20</td><td class="px-2 py-2 text-center text-gray-600">30</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">50</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">7</td><td class="px-2 py-2 text-center text-gray-600">15</td><td class="px-2 py-2 text-center font-semibold text-green-500 bg-green-50">22</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">9</td><td class="px-2 py-2 text-center text-gray-600">16</td><td class="px-2 py-2 text-center font-semibold text-orange-400 bg-orange-50">25</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">3</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">4</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">2</td><td class="px-2 py-2 text-center font-semibold text-purple-500 bg-purple-50">2</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">0</td>
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="2">
                                    <button onclick="toggleEditMode(2)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 2)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 2)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 2)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- February 2026 - Row 3 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="hiranand group">
                            <td class="px-3 py-2 text-gray-700 font-medium">February</td>
                            <td class="px-3 py-2 text-gray-700">HIRANAND GROUP OF...</td>
                            <td class="px-3 py-2 text-gray-600">February 18, 2026</td>
                            <td class="px-3 py-2 text-gray-600">May 26, 2026</td>
                            <td class="px-3 py-2 text-center text-gray-700 font-medium">78</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">184</td><td class="px-2 py-2 text-center text-gray-600">412</td><td class="px-2 py-2 text-center font-semibold text-teal-600 bg-teal-50">596</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-2 text-center text-gray-600">53</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">61</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-2 py-2 text-center text-gray-600">43</td><td class="px-2 py-2 text-center font-semibold text-green-500 bg-green-50">49</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">12</td><td class="px-2 py-2 text-center text-gray-600">43</td><td class="px-2 py-2 text-center font-semibold text-orange-400 bg-orange-50">55</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-2 text-center text-gray-600">7</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">9</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-600">2</td><td class="px-2 py-2 text-center font-semibold text-purple-500 bg-purple-50">3</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">1</td><td class="px-2 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">1</td>
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="3">
                                    <button onclick="toggleEditMode(3)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 3)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 3)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 3)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- March 2026 - Row 4 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="alfamart trading">
                            <td class="px-3 py-2 text-gray-700 font-medium">March</td>
                            <td class="px-3 py-2 text-gray-700">ALFAMART TRADING...</td>
                            <td class="px-3 py-2 text-gray-600">March 9, 2026</td>
                            <td class="px-3 py-2 text-gray-600">June 16, 2026</td>
                            <td class="px-3 py-2 text-center text-gray-700 font-medium">78</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">225</td><td class="px-2 py-2 text-center text-gray-600">504</td><td class="px-2 py-2 text-center font-semibold text-teal-600 bg-teal-50">730</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-blue-500 bg-blue-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-green-500 bg-green-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-orange-400 bg-orange-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-pink-500 bg-pink-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-purple-500 bg-purple-50">0</td>
                            <td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-600">0</td><td class="px-2 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">0</td>
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="4">
                                    <button onclick="toggleEditMode(4)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 4)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 4)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 4)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- TOTAL Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
                            <td class="px-3 py-2 text-gray-800 font-bold">TOTAL</td>
                            <td class="px-3 py-2 text-gray-500 text-xs italic"></td>
                            <td colspan="3" class="px-3 py-2 border-l border-gray-100"></td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">578</td><td class="px-2 py-2 text-center text-gray-700">1256</td><td class="px-2 py-2 text-center font-bold text-teal-600 bg-teal-100">1834</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">33</td><td class="px-2 py-2 text-center text-gray-700">88</td><td class="px-2 py-2 text-center font-bold text-blue-500 bg-blue-100">121</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">16</td><td class="px-2 py-2 text-center text-gray-700">61</td><td class="px-2 py-2 text-center font-bold text-green-500 bg-green-100">77</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">24</td><td class="px-2 py-2 text-center text-gray-700">61</td><td class="px-2 py-2 text-center font-bold text-orange-400 bg-orange-100">85</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">4</td><td class="px-2 py-2 text-center text-gray-700">10</td><td class="px-2 py-2 text-center font-bold text-pink-500 bg-pink-100">14</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">1</td><td class="px-2 py-2 text-center text-gray-700">4</td><td class="px-2 py-2 text-center font-bold text-purple-500 bg-purple-100">5</td>
                            <td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">0</td><td class="px-2 py-2 text-center text-gray-700">1</td><td class="px-2 py-2 text-center font-bold text-cyan-500 bg-cyan-100">1</td>
                            <td class="border-l border-gray-100"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                <span class="text-sm text-gray-500" id="paginationInfo">Showing 1–4 of 4 entries</span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                        disabled>&#8249;</button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">&#8250;</button>
                </div>
            </div>
        </div>

        <!-- ===== MONTHLY SPES-LGU / SPES-PRIVATE SUMMARY ===== -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Monthly SPES-LGU / SPES-Private Summary <span class="text-gray-400 font-normal text-sm">(Placed)</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-gray-500 font-medium w-36" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-4 py-3 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100 bg-teal-50">
                                <div class="flex items-center justify-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-teal-400"></div>
                                    SPES-LGU
                                </div>
                            </th>
                            <th colspan="3" class="px-4 py-3 text-center text-blue-600 font-semibold tracking-wide border-l border-gray-100 bg-blue-50">
                                <div class="flex items-center justify-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                    SPES-PRIVATE
                                </div>
                            </th>
                            <th colspan="3" class="px-4 py-3 text-center text-green-600 font-semibold tracking-wide border-l border-gray-100 bg-green-50">
                                <div class="flex items-center justify-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-green-400"></div>
                                    COMBINED TOTAL
                                </div>
                            </th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-4 py-2 text-center font-semibold text-teal-600">T</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-4 py-2 text-center font-semibold text-blue-600">T</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-4 py-2 text-center font-semibold text-green-600">T</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 font-medium">February</td>
                            <!-- SPES-LGU -->
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">0</td>
                            <td class="px-4 py-3 text-center text-gray-600">0</td>
                            <td class="px-4 py-3 text-center font-semibold text-teal-600 bg-teal-50">0</td>
                            <!-- SPES-Private -->
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">15</td>
                            <td class="px-4 py-3 text-center text-gray-600">61</td>
                            <td class="px-4 py-3 text-center font-semibold text-blue-600 bg-blue-50">76</td>
                            <!-- Combined -->
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">15</td>
                            <td class="px-4 py-3 text-center text-gray-600">61</td>
                            <td class="px-4 py-3 text-center font-bold text-green-600 bg-green-50">76</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 font-medium">March</td>
                            <!-- SPES-LGU -->
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">0</td>
                            <td class="px-4 py-3 text-center text-gray-600">0</td>
                            <td class="px-4 py-3 text-center font-semibold text-teal-600 bg-teal-50">0</td>
                            <!-- SPES-Private -->
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">0</td>
                            <td class="px-4 py-3 text-center text-gray-600">0</td>
                            <td class="px-4 py-3 text-center font-semibold text-blue-600 bg-blue-50">0</td>
                            <!-- Combined -->
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">0</td>
                            <td class="px-4 py-3 text-center text-gray-600">0</td>
                            <td class="px-4 py-3 text-center font-bold text-green-600 bg-green-50">0</td>
                        </tr>
                        <!-- TOTAL Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <td class="px-6 py-3 text-gray-800 font-bold">TOTAL</td>
                            <!-- SPES-LGU totals -->
                            <td class="px-4 py-3 text-center text-gray-700 border-l border-gray-100">0</td>
                            <td class="px-4 py-3 text-center text-gray-700">0</td>
                            <td class="px-4 py-3 text-center font-bold text-teal-600 bg-teal-100">0</td>
                            <!-- SPES-Private totals -->
                            <td class="px-4 py-3 text-center text-gray-700 border-l border-gray-100">16</td>
                            <td class="px-4 py-3 text-center text-gray-700">61</td>
                            <td class="px-4 py-3 text-center font-bold text-blue-600 bg-blue-100">77</td>
                            <!-- Combined totals -->
                            <td class="px-4 py-3 text-center text-gray-700 border-l border-gray-100">16</td>
                            <td class="px-4 py-3 text-center text-gray-700">61</td>
                            <td class="px-4 py-3 text-center font-bold text-green-600 bg-green-100">77</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Delete Entry</h3>
        </div>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this entry? This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmDelete()" class="flex-1 px-4 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600">Delete</button>
        </div>
    </div>
</div>

<!-- Save Confirmation Modal -->
<div id="saveModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Save Changes</h3>
        </div>
        <p class="text-gray-600 mb-6">Do you want to save the changes to this entry?</p>
        <div class="flex gap-3">
            <button onclick="closeSaveModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmSave()" class="flex-1 px-4 py-2 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600">Save</button>
        </div>
    </div>
</div>

<script>
const ROWS_PER_PAGE = 9;
let currentPage = 1;
let deletingRowId = null;
let savingRowId = null;
const editingData = {};

function getAllDataRows() {
    return Array.from(document.querySelectorAll('#spesTable tbody tr:not(.total-row)'));
}

function getVisibleFilteredRows() {
    return getAllDataRows().filter(r => r.dataset.filtered !== 'true');
}

function filterTable() {
    const query = document.getElementById('searchEmployer').value.toLowerCase().trim();
    getAllDataRows().forEach(row => {
        const employerCell = row.querySelectorAll('td')[1];
        const text = (employerCell ? employerCell.textContent : '').toLowerCase();
        row.dataset.filtered = (query !== '' && !text.includes(query)) ? 'true' : 'false';
        if (row.dataset.filtered === 'true') row.style.display = 'none';
    });
    currentPage = 1;
    renderPage();
}

function getTotalPages() {
    return Math.max(1, Math.ceil(getVisibleFilteredRows().length / ROWS_PER_PAGE));
}

function renderPage() {
    const rows = getVisibleFilteredRows();
    const total = rows.length;
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end = Math.min(start + ROWS_PER_PAGE, total);

    // Hide filtered rows
    getAllDataRows().forEach(r => {
        if (r.dataset.filtered === 'true') r.style.display = 'none';
    });

    rows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries found' : `Showing ${start + 1}–${end} of ${total} entries`;

    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= getTotalPages();

    const container = document.getElementById('pageNumbers');
    container.innerHTML = '';
    for (let p = 1; p <= getTotalPages(); p++) {
        const btn = document.createElement('button');
        btn.textContent = p;
        btn.className = 'px-3 py-1.5 rounded-lg text-sm border font-medium transition-colors ' +
            (p === currentPage
                ? 'bg-teal-500 text-white border-teal-500'
                : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

function changePage(dir) {
    currentPage = Math.max(1, Math.min(currentPage + dir, getTotalPages()));
    renderPage();
}

function getRowByIndex(rowId) {
    return Array.from(document.querySelectorAll('#spesTable tbody tr:not(.total-row)'))
        .find(r => r.querySelector(`[data-row="${rowId}"]`));
}

function toggleEditMode(rowId) {
    const row = getRowByIndex(rowId);
    if (!row) return;
    if (row.classList.contains('editing')) { cancelEdit(null, rowId); return; }

    row.classList.add('editing', 'bg-yellow-50');
    // Skip first 5 cols (month, employer, start, end, days) and last (actions)
    const cells = Array.from(row.querySelectorAll('td')).slice(5, -1);
    cells.forEach((cell, idx) => {
        editingData[`cell_${rowId}_${idx}`] = cell.textContent.trim();
        cell.contentEditable = 'true';
        cell.classList.add('border', 'border-yellow-300', 'bg-white');
    });
    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.add('hidden');
    ab.querySelector('.delete-btn').classList.add('hidden');
    ab.querySelector('.save-btn').classList.remove('hidden');
    ab.querySelector('.cancel-btn').classList.remove('hidden');
}

function cancelEdit(event, rowId) {
    const row = getRowByIndex(rowId);
    if (!row) return;
    const cells = Array.from(row.querySelectorAll('td')).slice(5, -1);
    cells.forEach((cell, idx) => {
        cell.contentEditable = 'false';
        cell.textContent = editingData[`cell_${rowId}_${idx}`] || '';
        cell.classList.remove('border', 'border-yellow-300', 'bg-white');
    });
    row.classList.remove('editing', 'bg-yellow-50');
    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.remove('hidden');
    ab.querySelector('.delete-btn').classList.remove('hidden');
    ab.querySelector('.save-btn').classList.add('hidden');
    ab.querySelector('.cancel-btn').classList.add('hidden');
}

function deleteRow(event, rowId) {
    event.preventDefault();
    deletingRowId = rowId;
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('deleteModal').classList.add('hidden');
    deletingRowId = null;
}

function confirmDelete() {
    const row = getRowByIndex(deletingRowId);
    if (row) {
        row.style.transition = 'opacity 0.3s ease-out';
        row.style.opacity = '0';
        setTimeout(() => {
            row.remove();
            closeDeleteModal();
            if (currentPage > getTotalPages()) currentPage = getTotalPages();
            renderPage();
        }, 300);
    }
}

function saveRow(event, rowId) {
    event.preventDefault();
    savingRowId = rowId;
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('saveModal').classList.remove('hidden');
}

function closeSaveModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('saveModal').classList.add('hidden');
    savingRowId = null;
}

function confirmSave() {
    const row = getRowByIndex(savingRowId);
    if (row) {
        const cells = Array.from(row.querySelectorAll('td')).slice(5, -1);
        cells.forEach(cell => {
            cell.contentEditable = 'false';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white');
        });
        row.classList.remove('editing', 'bg-yellow-50');
        const ab = row.querySelector('.action-buttons');
        ab.querySelector('.edit-btn').classList.remove('hidden');
        ab.querySelector('.delete-btn').classList.remove('hidden');
        ab.querySelector('.save-btn').classList.add('hidden');
        ab.querySelector('.cancel-btn').classList.add('hidden');
        closeSaveModal();
        row.style.transition = 'background-color 0.3s ease-out';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);
    }
}

document.addEventListener('click', (e) => {
    if (e.target === document.getElementById('modalBackdrop')) {
        closeDeleteModal(); closeSaveModal();
    }
});

// Init
getAllDataRows().forEach(r => r.dataset.filtered = 'false');
renderPage();
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>