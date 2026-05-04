<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Job Fair';
$pageHeading = 'Job Fair';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">

            <!-- Job Vacancies -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">310</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Job Vacancies</span>
            </div>

            <!-- Employers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">6</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Employers</span>
            </div>

            <!-- Interviewed Applicants -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">242</span>
                    <div class="bg-cyan-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Interviewed Applicants</span>
            </div>

            <!-- Qualified Applicants -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">195</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Qualified Applicants</span>
            </div>

            <!-- Successful Placements -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">161</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Successful Placements</span>
            </div>

        </div>

        <!-- Search + Filter -->
        <div class="flex items-center gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option>2026</option>
                    <option>2025</option>
                    <option>2024</option>
                </select>
            </div>
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    id="searchCompany"
                    placeholder="Search company..."
                    oninput="filterTable()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300"
                />
            </div>
        </div>

        <!-- Job Fair Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Job Fair</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="jobFairTable">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">PARTICIPATING EMPLOYER</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                            <th class="px-2 py-2 text-center text-gray-400 font-semibold tracking-wide border-l border-gray-100" rowspan="2">ACTIONS</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <!-- Job Vacancies -->
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
                            <!-- Placed / HOTS -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <!-- For Further Interview -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- January 2026 - Row 1 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="agro prime manpower">
                            <td class="px-4 py-2 text-gray-700 font-medium">January 2026</td>
                            <td class="px-4 py-2 text-gray-700">Agro Prime Manpower...</td>
                            <!-- Job Vacancies -->
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">30</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold">25</td>
                            <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">55</td>
                            <!-- Interviewed -->
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">22</td>
                            <td class="px-3 py-2 text-center text-gray-600">18</td>
                            <td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">40</td>
                            <!-- Qualified -->
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">18</td>
                            <td class="px-3 py-2 text-center text-gray-600">15</td>
                            <td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">33</td>
                            <!-- Not Qualified -->
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">4</td>
                            <td class="px-3 py-2 text-center text-gray-600">3</td>
                            <td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">7</td>
                            <!-- Placed / HOTS -->
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">14</td>
                            <td class="px-3 py-2 text-center text-gray-600">12</td>
                            <td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">26</td>
                            <!-- For Further Interview -->
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">4</td>
                            <td class="px-3 py-2 text-center text-gray-600">3</td>
                            <td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">7</td>
                            <!-- Actions -->
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="1">
                                    <button onclick="toggleEditMode(1)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button onclick="deleteRow(event, 1)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <button onclick="saveRow(event, 1)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button onclick="cancelEdit(event, 1)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- January 2026 - Row 2 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="pacific direct placement corp">
                            <td class="px-4 py-2 text-gray-700 font-medium">January 2026</td>
                            <td class="px-4 py-2 text-gray-700">Pacific Direct Placement Corp.</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">20</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold">30</td>
                            <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">50</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">15</td>
                            <td class="px-3 py-2 text-center text-gray-600">22</td>
                            <td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">37</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">12</td>
                            <td class="px-3 py-2 text-center text-gray-600">18</td>
                            <td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">30</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">3</td>
                            <td class="px-3 py-2 text-center text-gray-600">4</td>
                            <td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">7</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">10</td>
                            <td class="px-3 py-2 text-center text-gray-600">15</td>
                            <td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">25</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td>
                            <td class="px-3 py-2 text-center text-gray-600">3</td>
                            <td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">5</td>
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
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="mindanao build trade co">
                            <td class="px-4 py-2 text-gray-700 font-medium">February 2026</td>
                            <td class="px-4 py-2 text-gray-700">Mindanao Build & Trade Co.</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">45</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold">15</td>
                            <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">60</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">38</td>
                            <td class="px-3 py-2 text-center text-gray-600">12</td>
                            <td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">50</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">30</td>
                            <td class="px-3 py-2 text-center text-gray-600">10</td>
                            <td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">40</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">8</td>
                            <td class="px-3 py-2 text-center text-gray-600">2</td>
                            <td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">10</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">25</td>
                            <td class="px-3 py-2 text-center text-gray-600">8</td>
                            <td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">33</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">5</td>
                            <td class="px-3 py-2 text-center text-gray-600">2</td>
                            <td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">7</td>
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="3">
                                    <button onclick="toggleEditMode(3)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 3)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 3)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 3)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- February 2026 - Row 4 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="suncorp recruitment agency">
                            <td class="px-4 py-2 text-gray-700 font-medium">February 2026</td>
                            <td class="px-4 py-2 text-gray-700">SunCorp Recruitment Agency</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">18</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold">32</td>
                            <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">50</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">14</td>
                            <td class="px-3 py-2 text-center text-gray-600">25</td>
                            <td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">39</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">11</td>
                            <td class="px-3 py-2 text-center text-gray-600">20</td>
                            <td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">31</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">3</td>
                            <td class="px-3 py-2 text-center text-gray-600">5</td>
                            <td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">8</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">9</td>
                            <td class="px-3 py-2 text-center text-gray-600">17</td>
                            <td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">26</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td>
                            <td class="px-3 py-2 text-center text-gray-600">3</td>
                            <td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">5</td>
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="4">
                                    <button onclick="toggleEditMode(4)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 4)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 4)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 4)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- March 2026 - Row 5 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="horizon logistics services">
                            <td class="px-4 py-2 text-gray-700 font-medium">March 2026</td>
                            <td class="px-4 py-2 text-gray-700">Horizon Logistics Services</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">35</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold">20</td>
                            <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">55</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">28</td>
                            <td class="px-3 py-2 text-center text-gray-600">16</td>
                            <td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">44</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">22</td>
                            <td class="px-3 py-2 text-center text-gray-600">13</td>
                            <td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">35</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">6</td>
                            <td class="px-3 py-2 text-center text-gray-600">3</td>
                            <td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">9</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">18</td>
                            <td class="px-3 py-2 text-center text-gray-600">11</td>
                            <td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">29</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">4</td>
                            <td class="px-3 py-2 text-center text-gray-600">2</td>
                            <td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">6</td>
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="5">
                                    <button onclick="toggleEditMode(5)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 5)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 5)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 5)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- March 2026 - Row 6 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="blueseas overseas staffing">
                            <td class="px-4 py-2 text-gray-700 font-medium">March 2026</td>
                            <td class="px-4 py-2 text-gray-700">BlueSeas Overseas Staffing</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">12</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-semibold">28</td>
                            <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">40</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">10</td>
                            <td class="px-3 py-2 text-center text-gray-600">22</td>
                            <td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">32</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">8</td>
                            <td class="px-3 py-2 text-center text-gray-600">16</td>
                            <td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">26</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td>
                            <td class="px-3 py-2 text-center text-gray-600">4</td>
                            <td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">6</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">7</td>
                            <td class="px-3 py-2 text-center text-gray-600">15</td>
                            <td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">22</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">1</td>
                            <td class="px-3 py-2 text-center text-gray-600">3</td>
                            <td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">4</td>
                            <td class="px-3 py-2 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="6">
                                    <button onclick="toggleEditMode(6)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 6)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 6)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 6)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- TOTALS Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
                            <td class="px-4 py-2 text-gray-800 font-bold">TOTALS</td>
                            <td class="px-4 py-2 text-gray-500 text-xs italic"></td>
                            <td class="px-3 py-2 text-center text-blue-500 font-bold border-l border-gray-100">160</td>
                            <td class="px-3 py-2 text-center text-blue-500 font-bold">150</td>
                            <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-100">310</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">127</td>
                            <td class="px-3 py-2 text-center text-gray-700">115</td>
                            <td class="px-3 py-2 text-center font-bold text-cyan-500 bg-cyan-100">242</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">101</td>
                            <td class="px-3 py-2 text-center text-gray-700">94</td>
                            <td class="px-3 py-2 text-center font-bold text-green-500 bg-green-100">195</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">26</td>
                            <td class="px-3 py-2 text-center text-gray-700">21</td>
                            <td class="px-3 py-2 text-center font-bold text-red-400 bg-red-100">47</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">83</td>
                            <td class="px-3 py-2 text-center text-gray-700">78</td>
                            <td class="px-3 py-2 text-center font-bold text-orange-400 bg-orange-100">161</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">18</td>
                            <td class="px-3 py-2 text-center text-gray-700">16</td>
                            <td class="px-3 py-2 text-center font-bold text-purple-400 bg-purple-100">34</td>
                            <td class="border-l border-gray-100"></td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
                <span class="text-sm text-gray-500" id="paginationInfo">Showing 1–6 of 6 entries</span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>&lsaquo;</button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">&rsaquo;</button>
                </div>
            </div>
        </div>

    </div>
</main>

<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40 modal-backdrop"></div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4 animate-modal">
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
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4 animate-modal">
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
    return Array.from(document.querySelectorAll('#jobFairTable tbody tr:not(.total-row)'))
        .filter(r => r.style.display !== 'none' || r.dataset.filtered !== 'true');
}

function getVisibleFilteredRows() {
    return Array.from(document.querySelectorAll('#jobFairTable tbody tr:not(.total-row)'))
        .filter(r => r.dataset.filtered !== 'true');
}

function filterTable() {
    const query = document.getElementById('searchCompany').value.toLowerCase().trim();
    const rows = document.querySelectorAll('#jobFairTable tbody tr:not(.total-row)');
    rows.forEach(row => {
        const employer = row.dataset.employer || '';
        const employerCell = row.querySelectorAll('td')[1];
        const text = (employerCell ? employerCell.textContent : employer).toLowerCase();
        if (query === '' || text.includes(query)) {
            row.dataset.filtered = 'false';
        } else {
            row.dataset.filtered = 'true';
            row.style.display = 'none';
        }
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
    const totalPages = getTotalPages();
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end = Math.min(start + ROWS_PER_PAGE, total);

    // Hide all non-filtered rows first
    document.querySelectorAll('#jobFairTable tbody tr:not(.total-row)').forEach(r => {
        if (r.dataset.filtered === 'true') {
            r.style.display = 'none';
        }
    });

    rows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries found' : `Showing ${start + 1}–${end} of ${total} entries`;

    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;

    const container = document.getElementById('pageNumbers');
    container.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.textContent = p;
        btn.className = `px-3 py-1.5 rounded-lg text-sm border font-medium transition-colors ` +
            (p === currentPage
                ? 'bg-teal-500 text-white border-teal-500'
                : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

function changePage(dir) {
    const totalPages = getTotalPages();
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderPage();
}

function getRowByIndex(rowId) {
    const allRows = Array.from(document.querySelectorAll('#jobFairTable tbody tr:not(.total-row)'));
    return allRows.find(r => r.querySelector(`[data-row="${rowId}"]`));
}

function toggleEditMode(rowId) {
    const row = getRowByIndex(rowId);
    if (!row) return;
    const isEditing = row.classList.contains('editing');
    if (isEditing) { cancelEdit(null, rowId); return; }

    row.classList.add('editing', 'bg-yellow-50');
    const cells = row.querySelectorAll('td:not(:first-child):not(:nth-child(2)):not(:last-child)');
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
    const cells = row.querySelectorAll('td:not(:first-child):not(:nth-child(2)):not(:last-child)');
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
        setTimeout(() => { row.remove(); closeDeleteModal(); renderPage(); }, 300);
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
        const cells = row.querySelectorAll('td:not(:first-child):not(:nth-child(2)):not(:last-child)');
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

// Initialize all rows as not filtered
document.querySelectorAll('#jobFairTable tbody tr:not(.total-row)').forEach(r => {
    r.dataset.filtered = 'false';
});

renderPage();
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>