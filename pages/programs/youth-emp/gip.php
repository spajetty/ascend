<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Government Internship Program (GIP)';
$pageHeading = 'Government Internship Program (GIP)';

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
        <!-- Row 1: Pipeline funnel metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">

            <!-- Total Interns -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">75</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium">30M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium">45F</span>
                        </div>
                    </div>
                    <div class="bg-teal-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Participants</span>
            </div>

            <!-- Total Inquired -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">92</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium">38M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium">54F</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Inquired</span>
            </div>

            <!-- Total Referred -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-violet-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">75</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium">30M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium">45F</span>
                        </div>
                    </div>
                    <div class="bg-violet-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Referred</span>
            </div>

            <!-- Total Interviewed -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-amber-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">61</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium">25M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium">36F</span>
                        </div>
                    </div>
                    <div class="bg-amber-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Interviewed</span>
            </div>
        </div>

        <!-- Row 2: Acceptance outcomes -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">

            <!-- PESO-Accepted -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">49</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium">19M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium">30F</span>
                        </div>
                    </div>
                    <div class="bg-orange-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">PESO-Accepted</span>
            </div>

            <!-- Privately-Accepted -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">14</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium">6M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium">8F</span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Privately-Accepted</span>
            </div>

            <!-- Not Proceeded -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800">8</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium">4M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium">4F</span>
                        </div>
                    </div>
                    <div class="bg-red-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Not Proceeded</span>
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

            <!-- College / SHS filter -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Type:</span>
                <select id="filterType" onchange="applyFilters()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                    <option value="">All</option>
                    <option value="college">College</option>
                    <option value="shs">SHS</option>
                </select>
            </div>

            <!-- Search school -->
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchSchool" placeholder="Search school..."
                    oninput="applyFilters()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
            </div>
        </div>

        <!-- ===== MAIN GIP TABLE ===== -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Government Internship Program (GIP)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="gipTable">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-36" rowspan="2">CONTRACT PERIOD</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium" rowspan="2">SCHOOL</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-28" rowspan="2">COLLEGE / SHS</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-24" rowspan="2">COURSE</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium" rowspan="2">OFFICE ASSIGNMENT</th>
                            <th class="px-3 py-3 text-center text-gray-500 font-medium w-20" rowspan="2">REQ. HRS.</th>
                            <!-- Group headers -->
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold border-l border-gray-100">PARTICIPANTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">INQUIRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-violet-500 font-semibold border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-amber-500 font-semibold border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-500 font-semibold border-l border-gray-100">PESO-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold border-l border-gray-100">PRIVATELY-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold border-l border-gray-100">NOT PROCEEDED</th>
                            <th class="px-2 py-2 text-center text-gray-400 font-semibold border-l border-gray-100" rowspan="2">ACTIONS</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <!-- Participants -->
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-2 text-center font-semibold text-teal-600">T</th>
                            <!-- Inquired -->
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-2 text-center font-semibold text-blue-500">T</th>
                            <!-- Referred -->
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-2 text-center font-semibold text-violet-500">T</th>
                            <!-- Interviewed -->
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-2 text-center font-semibold text-amber-500">T</th>
                            <!-- PESO-Accepted -->
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-2 text-center font-semibold text-orange-500">T</th>
                            <!-- Privately-Accepted -->
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-2 text-center font-semibold text-green-500">T</th>
                            <!-- Not Proceeded -->
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium">F</th>
                            <th class="px-2 py-2 text-center font-semibold text-red-400">T</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- Row 1 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-school="davao del sur state college" data-type="college">
                            <td class="px-3 py-3 text-gray-700 font-medium">January – March 2026</td>
                            <td class="px-3 py-3 text-gray-700">Davao del Sur State College</td>
                            <td class="px-3 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-700">College</span>
                            </td>
                            <td class="px-3 py-3 text-gray-600">BSBA</td>
                            <td class="px-3 py-3 text-gray-600">PESO Digos City Office</td>
                            <td class="px-3 py-3 text-center text-gray-700 font-medium">300</td>
                            <!-- Participants -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-3 text-center text-gray-600">12</td><td class="px-2 py-3 text-center font-semibold text-teal-600 bg-teal-50">20</td>
                            <!-- Inquired -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-2 py-3 text-center text-gray-600">15</td><td class="px-2 py-3 text-center font-semibold text-blue-500 bg-blue-50">25</td>
                            <!-- Referred -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-3 text-center text-gray-600">12</td><td class="px-2 py-3 text-center font-semibold text-violet-500 bg-violet-50">20</td>
                            <!-- Interviewed -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-2 py-3 text-center text-gray-600">10</td><td class="px-2 py-3 text-center font-semibold text-amber-500 bg-amber-50">16</td>
                            <!-- PESO-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-2 py-3 text-center text-gray-600">8</td><td class="px-2 py-3 text-center font-semibold text-orange-500 bg-orange-50">13</td>
                            <!-- Privately-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-3 text-center text-gray-600">3</td><td class="px-2 py-3 text-center font-semibold text-green-500 bg-green-50">5</td>
                            <!-- Not Proceeded -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-3 text-center text-gray-600">2</td><td class="px-2 py-3 text-center font-semibold text-red-400 bg-red-50">3</td>
                            <!-- Actions -->
                            <td class="px-3 py-3 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="1">
                                    <button onclick="toggleEditMode(1)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 1)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 1)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 1)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-school="holy cross of davao college" data-type="college">
                            <td class="px-3 py-3 text-gray-700 font-medium">February – April 2026</td>
                            <td class="px-3 py-3 text-gray-700">Holy Cross of Davao College</td>
                            <td class="px-3 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-700">College</span>
                            </td>
                            <td class="px-3 py-3 text-gray-600">BSIT</td>
                            <td class="px-3 py-3 text-gray-600">DOLE Regional Office XI</td>
                            <td class="px-3 py-3 text-center text-gray-700 font-medium">300</td>
                            <!-- Participants -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-2 py-3 text-center text-gray-600">15</td><td class="px-2 py-3 text-center font-semibold text-teal-600 bg-teal-50">25</td>
                            <!-- Inquired -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">14</td><td class="px-2 py-3 text-center text-gray-600">20</td><td class="px-2 py-3 text-center font-semibold text-blue-500 bg-blue-50">34</td>
                            <!-- Referred -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-2 py-3 text-center text-gray-600">15</td><td class="px-2 py-3 text-center font-semibold text-violet-500 bg-violet-50">25</td>
                            <!-- Interviewed -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-3 text-center text-gray-600">12</td><td class="px-2 py-3 text-center font-semibold text-amber-500 bg-amber-50">20</td>
                            <!-- PESO-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-2 py-3 text-center text-gray-600">10</td><td class="px-2 py-3 text-center font-semibold text-orange-500 bg-orange-50">16</td>
                            <!-- Privately-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-3 text-center text-gray-600">3</td><td class="px-2 py-3 text-center font-semibold text-green-500 bg-green-50">5</td>
                            <!-- Not Proceeded -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-3 text-center text-gray-600">1</td><td class="px-2 py-3 text-center font-semibold text-red-400 bg-red-50">3</td>
                            <!-- Actions -->
                            <td class="px-3 py-3 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="2">
                                    <button onclick="toggleEditMode(2)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 2)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 2)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 2)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50" data-school="university of southeastern philippines" data-type="college">
                            <td class="px-3 py-3 text-gray-700 font-medium">March – May 2026</td>
                            <td class="px-3 py-3 text-gray-700">University of Southeastern Philippines</td>
                            <td class="px-3 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-700">College</span>
                            </td>
                            <td class="px-3 py-3 text-gray-600">BSED</td>
                            <td class="px-3 py-3 text-gray-600">PESO Provincial Office</td>
                            <td class="px-3 py-3 text-center text-gray-700 font-medium">300</td>
                            <!-- Participants -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">12</td><td class="px-2 py-3 text-center text-gray-600">18</td><td class="px-2 py-3 text-center font-semibold text-teal-600 bg-teal-50">30</td>
                            <!-- Inquired -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">14</td><td class="px-2 py-3 text-center text-gray-600">19</td><td class="px-2 py-3 text-center font-semibold text-blue-500 bg-blue-50">33</td>
                            <!-- Referred -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">12</td><td class="px-2 py-3 text-center text-gray-600">18</td><td class="px-2 py-3 text-center font-semibold text-violet-500 bg-violet-50">30</td>
                            <!-- Interviewed -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">11</td><td class="px-2 py-3 text-center text-gray-600">14</td><td class="px-2 py-3 text-center font-semibold text-amber-500 bg-amber-50">25</td>
                            <!-- PESO-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-2 py-3 text-center text-gray-600">12</td><td class="px-2 py-3 text-center font-semibold text-orange-500 bg-orange-50">20</td>
                            <!-- Privately-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-2 py-3 text-center text-gray-600">2</td><td class="px-2 py-3 text-center font-semibold text-green-500 bg-green-50">4</td>
                            <!-- Not Proceeded -->
                            <td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-2 py-3 text-center text-gray-600">1</td><td class="px-2 py-3 text-center font-semibold text-red-400 bg-red-50">2</td>
                            <!-- Actions -->
                            <td class="px-3 py-3 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="3">
                                    <button onclick="toggleEditMode(3)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 3)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 3)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 3)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- TOTAL Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
                            <td class="px-3 py-3 text-gray-800 font-bold" colspan="5">TOTAL</td>
                            <td class="px-3 py-3 text-center font-bold text-gray-700">—</td>
                            <!-- Participants -->
                            <td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">30</td><td class="px-2 py-3 text-center text-gray-700">45</td><td class="px-2 py-3 text-center font-bold text-teal-600 bg-teal-100">75</td>
                            <!-- Inquired -->
                            <td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">38</td><td class="px-2 py-3 text-center text-gray-700">54</td><td class="px-2 py-3 text-center font-bold text-blue-500 bg-blue-100">92</td>
                            <!-- Referred -->
                            <td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">30</td><td class="px-2 py-3 text-center text-gray-700">45</td><td class="px-2 py-3 text-center font-bold text-violet-500 bg-violet-100">75</td>
                            <!-- Interviewed -->
                            <td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">25</td><td class="px-2 py-3 text-center text-gray-700">36</td><td class="px-2 py-3 text-center font-bold text-amber-500 bg-amber-100">61</td>
                            <!-- PESO-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">19</td><td class="px-2 py-3 text-center text-gray-700">30</td><td class="px-2 py-3 text-center font-bold text-orange-500 bg-orange-100">49</td>
                            <!-- Privately-Accepted -->
                            <td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">6</td><td class="px-2 py-3 text-center text-gray-700">8</td><td class="px-2 py-3 text-center font-bold text-green-500 bg-green-100">14</td>
                            <!-- Not Proceeded -->
                            <td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">4</td><td class="px-2 py-3 text-center text-gray-700">4</td><td class="px-2 py-3 text-center font-bold text-red-400 bg-red-100">8</td>
                            <td class="border-l border-gray-100"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                <span class="text-sm text-gray-500" id="paginationInfo">Showing 1–3 of 3 entries</span>
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

    </div>
</main>

<!-- Backdrop -->
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
    return Array.from(document.querySelectorAll('#gipTable tbody tr:not(.total-row)'));
}

function getVisibleFilteredRows() {
    return getAllDataRows().filter(r => r.dataset.filtered !== 'true');
}

function applyFilters() {
    const query = document.getElementById('searchSchool').value.toLowerCase().trim();
    const type  = document.getElementById('filterType').value.toLowerCase();

    getAllDataRows().forEach(row => {
        const school  = (row.dataset.school || '').toLowerCase();
        const rowType = (row.dataset.type  || '').toLowerCase();

        const matchSearch = !query || school.includes(query);
        const matchType   = !type  || rowType === type;

        row.dataset.filtered = (matchSearch && matchType) ? 'false' : 'true';
        if (row.dataset.filtered === 'true') row.style.display = 'none';
    });

    currentPage = 1;
    renderPage();
}

function getTotalPages() {
    return Math.max(1, Math.ceil(getVisibleFilteredRows().length / ROWS_PER_PAGE));
}

function renderPage() {
    const rows  = getVisibleFilteredRows();
    const total = rows.length;
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end   = Math.min(start + ROWS_PER_PAGE, total);

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
    return Array.from(document.querySelectorAll('#gipTable tbody tr:not(.total-row)'))
        .find(r => r.querySelector(`[data-row="${rowId}"]`));
}

function toggleEditMode(rowId) {
    const row = getRowByIndex(rowId);
    if (!row) return;
    if (row.classList.contains('editing')) { cancelEdit(null, rowId); return; }

    row.classList.add('editing', 'bg-yellow-50');
    // Editable: all numeric cells (indices 6 to end, excluding last actions cell)
    const allCells = Array.from(row.querySelectorAll('td'));
    const editableCells = allCells.slice(6, -1);
    editableCells.forEach((cell, idx) => {
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
    const allCells = Array.from(row.querySelectorAll('td'));
    const editableCells = allCells.slice(6, -1);
    editableCells.forEach((cell, idx) => {
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
        const allCells = Array.from(row.querySelectorAll('td'));
        const editableCells = allCells.slice(6, -1);
        editableCells.forEach(cell => {
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