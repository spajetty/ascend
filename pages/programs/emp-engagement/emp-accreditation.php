<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employers Accreditation';
$pageHeading = 'Employers Accreditation';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 pt-6">
        <a href="/pages/programs/employers-engagement.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employers Engagement Section
        </a>
    </div>

    <div class="px-6 md:px-8 py-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <!-- Total Employers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardTotalEmployers">0</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Employers</span>
            </div>

            <!-- New Accreditations -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardNew">0</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">New Accreditations</span>
            </div>

            <!-- Renewed Accreditations -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardRenewed">0</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Renewed Accreditations</span>
            </div>

            <!-- Active Employers (current year) -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardActive">0</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 active-year-label">Active Employers (2026)</span>
            </div>

        </div>

        <!-- Search + Filters -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select id="filterYear" onchange="applyFilters()" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
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
                    oninput="applyFilters()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300"
                />
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Establishment Type:</span>
                <select id="filterEstType" onchange="applyFilters()" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">All Establishment Types</option>
                    <option value="manpower">Manpower</option>
                    <option value="direct">Direct</option>
                    <option value="direct (overseas)">Direct (Overseas)</option>
                </select>
            </div>
        </div>

        <!-- Employers Accreditation Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Employers Accreditation</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="accreditationTable">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">MONTH</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">ACCREDITATION</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">COMPANY</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">ESTABLISHMENT TYPE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">INDUSTRY</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">CITY/MUNICIPALITY</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-medium">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="accreditationTbody">

                        <!-- January 2026 - Row 1 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50"
                            data-year="2026"
                            data-month="January 2026"
                            data-accreditation="new"
                            data-company="Prime Manpower Solutions Inc."
                            data-esttype="manpower"
                            data-industry="Agriculture"
                            data-city="Quezon City">
                            <td class="px-4 py-3 text-gray-700 font-medium">January 2026</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">Prime Manpower Solutions Inc.</td>
                            <td class="px-4 py-3 font-medium text-blue-500">Manpower</td>
                            <td class="px-4 py-3 text-gray-600">Agriculture</td>
                            <td class="px-4 py-3 text-gray-600">Quezon City</td>
                            <td class="px-4 py-3 text-center">
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
                        <tr class="border-b border-gray-50 hover:bg-gray-50"
                            data-year="2026"
                            data-month="January 2026"
                            data-accreditation="renew"
                            data-company="Pacific Direct Placement Corp."
                            data-esttype="direct (overseas)"
                            data-industry="Maritime"
                            data-city="Manila">
                            <td class="px-4 py-3 text-gray-700 font-medium">January 2026</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Renew</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">Pacific Direct Placement Corp.</td>
                            <td class="px-4 py-3 font-medium text-teal-500">Direct (Overseas)</td>
                            <td class="px-4 py-3 text-gray-600">Maritime</td>
                            <td class="px-4 py-3 text-gray-600">Manila</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="2">
                                    <button onclick="toggleEditMode(2)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 2)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 2)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 2)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- February 2026 - Row 3 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50"
                            data-year="2026"
                            data-month="February 2026"
                            data-accreditation="new"
                            data-company="Mindanao Build & Trade Co."
                            data-esttype="direct"
                            data-industry="Construction"
                            data-city="Davao City">
                            <td class="px-4 py-3 text-gray-700 font-medium">February 2026</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">Mindanao Build & Trade Co.</td>
                            <td class="px-4 py-3 font-medium text-green-500">Direct</td>
                            <td class="px-4 py-3 text-gray-600">Construction</td>
                            <td class="px-4 py-3 text-gray-600">Davao City</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="3">
                                    <button onclick="toggleEditMode(3)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 3)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 3)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 3)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- February 2026 - Row 4 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50"
                            data-year="2026"
                            data-month="February 2026"
                            data-accreditation="new"
                            data-company="SunCorp Recruitment Agency"
                            data-esttype="manpower"
                            data-industry="Business Process Outsourcing"
                            data-city="Cebu City">
                            <td class="px-4 py-3 text-gray-700 font-medium">February 2026</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">SunCorp Recruitment Agency</td>
                            <td class="px-4 py-3 font-medium text-blue-500">Manpower</td>
                            <td class="px-4 py-3 text-gray-600">Business Process Outsourcing</td>
                            <td class="px-4 py-3 text-gray-600">Cebu City</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="4">
                                    <button onclick="toggleEditMode(4)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 4)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 4)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 4)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- March 2026 - Row 5 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50"
                            data-year="2026"
                            data-month="March 2026"
                            data-accreditation="renew"
                            data-company="Horizon Logistics Services"
                            data-esttype="direct"
                            data-industry="Logistics & Transport"
                            data-city="Makati City">
                            <td class="px-4 py-3 text-gray-700 font-medium">March 2026</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Renew</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">Horizon Logistics Services</td>
                            <td class="px-4 py-3 font-medium text-green-500">Direct</td>
                            <td class="px-4 py-3 text-gray-600">Logistics & Transport</td>
                            <td class="px-4 py-3 text-gray-600">Makati City</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="5">
                                    <button onclick="toggleEditMode(5)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 5)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 5)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 5)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- March 2026 - Row 6 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50"
                            data-year="2026"
                            data-month="March 2026"
                            data-accreditation="new"
                            data-company="BlueSeas Overseas Staffing"
                            data-esttype="direct (overseas)"
                            data-industry="Seafaring"
                            data-city="Pasig City">
                            <td class="px-4 py-3 text-gray-700 font-medium">March 2026</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">BlueSeas Overseas Staffing</td>
                            <td class="px-4 py-3 font-medium text-teal-500">Direct (Overseas)</td>
                            <td class="px-4 py-3 text-gray-600">Seafaring</td>
                            <td class="px-4 py-3 text-gray-600">Pasig City</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="6">
                                    <button onclick="toggleEditMode(6)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 6)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 6)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 6)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
                        </tr>

                        <!-- 2025 Sample Rows (for year filter demo) -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50"
                            data-year="2025"
                            data-month="June 2025"
                            data-accreditation="new"
                            data-company="GreenField Agri-Corp"
                            data-esttype="direct"
                            data-industry="Agriculture"
                            data-city="Batangas City">
                            <td class="px-4 py-3 text-gray-700 font-medium">June 2025</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">GreenField Agri-Corp</td>
                            <td class="px-4 py-3 font-medium text-green-500">Direct</td>
                            <td class="px-4 py-3 text-gray-600">Agriculture</td>
                            <td class="px-4 py-3 text-gray-600">Batangas City</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="7">
                                    <button onclick="toggleEditMode(7)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <button onclick="deleteRow(event, 7)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button onclick="saveRow(event, 7)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="cancelEdit(event, 7)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </td>
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

// ── Card computation ──────────────────────────────────────────────────────────
function recomputeCards() {
    const selectedYear = document.getElementById('filterYear').value;
    const allRows = Array.from(document.querySelectorAll('#accreditationTable tbody tr'));

    // Total Employers = unique company names across ALL rows (all years)
    const allCompanies = new Set(allRows.map(r => r.dataset.company?.trim().toLowerCase()).filter(Boolean));
    document.getElementById('cardTotalEmployers').textContent = allCompanies.size;

    // New / Renewed / Active = rows matching selected year only
    const yearRows = allRows.filter(r => r.dataset.year === selectedYear);
    const newCount     = yearRows.filter(r => r.dataset.accreditation === 'new').length;
    const renewCount   = yearRows.filter(r => r.dataset.accreditation === 'renew').length;
    const activeCompanies = new Set(yearRows.map(r => r.dataset.company?.trim().toLowerCase()).filter(Boolean));

    document.getElementById('cardNew').textContent     = newCount;
    document.getElementById('cardRenewed').textContent = renewCount;
    document.getElementById('cardActive').textContent  = activeCompanies.size;

    // Update the label of the Active card to reflect selected year
    document.querySelectorAll('.active-year-label').forEach(el => {
        el.textContent = `Active Employers (${selectedYear})`;
    });
}

// ── Filtering & Pagination ────────────────────────────────────────────────────
function applyFilters() {
    const year    = document.getElementById('filterYear').value;
    const query   = document.getElementById('searchCompany').value.toLowerCase().trim();
    const estType = document.getElementById('filterEstType').value.toLowerCase().trim();

    const rows = document.querySelectorAll('#accreditationTable tbody tr');
    rows.forEach(row => {
        const rowYear    = row.dataset.year    || '';
        const rowCompany = row.dataset.company || '';
        const rowEst     = (row.dataset.esttype || '').toLowerCase();

        const yearMatch    = rowYear === year;
        const queryMatch   = query === '' || rowCompany.toLowerCase().includes(query);
        const estMatch     = estType === '' || rowEst === estType;

        row.dataset.filtered = (yearMatch && queryMatch && estMatch) ? 'false' : 'true';
    });

    currentPage = 1;
    recomputeCards();
    renderPage();
}

function getVisibleFilteredRows() {
    return Array.from(document.querySelectorAll('#accreditationTable tbody tr'))
        .filter(r => r.dataset.filtered !== 'true');
}

function getTotalPages() {
    return Math.max(1, Math.ceil(getVisibleFilteredRows().length / ROWS_PER_PAGE));
}

function renderPage() {
    const rows  = getVisibleFilteredRows();
    const total = rows.length;
    const totalPages = getTotalPages();
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end   = Math.min(start + ROWS_PER_PAGE, total);

    // Hide all rows first
    document.querySelectorAll('#accreditationTable tbody tr').forEach(r => {
        r.style.display = r.dataset.filtered === 'true' ? 'none' : 'none';
    });
    // Show only current page rows
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
    currentPage = Math.max(1, Math.min(currentPage + dir, getTotalPages()));
    renderPage();
}

// ── Edit / Delete / Save helpers ──────────────────────────────────────────────
function getRowByIndex(rowId) {
    return Array.from(document.querySelectorAll('#accreditationTable tbody tr'))
        .find(r => r.querySelector(`[data-row="${rowId}"]`));
}

function toggleEditMode(rowId) {
    const row = getRowByIndex(rowId);
    if (!row) return;
    if (row.classList.contains('editing')) { cancelEdit(null, rowId); return; }

    row.classList.add('editing', 'bg-yellow-50');
    // Make text cells editable (cols: month, accreditation badge, company, est-type, industry, city — skip actions)
    const cells = row.querySelectorAll('td:not(:last-child)');
    cells.forEach((cell, idx) => {
        // Skip badge cell (idx 1) — keep as-is; make others editable
        if (idx === 1) return;
        editingData[`cell_${rowId}_${idx}`] = cell.textContent.trim();
        cell.contentEditable = 'true';
        cell.classList.add('border', 'border-yellow-300', 'bg-white', 'outline-none');
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
    const cells = row.querySelectorAll('td:not(:last-child)');
    cells.forEach((cell, idx) => {
        if (idx === 1) return;
        cell.contentEditable = 'false';
        cell.textContent = editingData[`cell_${rowId}_${idx}`] || '';
        cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
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
        setTimeout(() => { row.remove(); closeDeleteModal(); recomputeCards(); renderPage(); }, 300);
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
        const cells = row.querySelectorAll('td:not(:last-child)');
        cells.forEach((cell, idx) => {
            if (idx === 1) return;
            cell.contentEditable = 'false';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
            // Sync back data attributes from visible text where applicable
            if (idx === 2) row.dataset.company = cell.textContent.trim();
            if (idx === 4) row.dataset.industry = cell.textContent.trim();
            if (idx === 5) row.dataset.city = cell.textContent.trim();
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
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; recomputeCards(); }, 1500);
    }
}

document.addEventListener('click', (e) => {
    if (e.target === document.getElementById('modalBackdrop')) {
        closeDeleteModal(); closeSaveModal();
    }
});

// ── Init ──────────────────────────────────────────────────────────────────────
document.querySelectorAll('#accreditationTable tbody tr').forEach(r => {
    r.dataset.filtered = 'false';
});
applyFilters(); // triggers recomputeCards + renderPage
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>