<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – SPES';
$pageHeading = 'Special Program for Employment of Students (SPES)';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<style>
    body.modal-open { overflow: hidden; }
    #mainContent { box-sizing: border-box; width: 100%; }
    #mainContent * { box-sizing: border-box; }

    /* Bulk fill modal input cells */
    .vac-ref-input {
        width: 52px;
        text-align: center;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 3px 4px;
        font-size: 12px;
        outline: none;
        transition: border-color .15s;
    }
    .vac-ref-input:focus { border-color: #14b8a6; box-shadow: 0 0 0 2px rgba(20,184,166,.15); }
    .company-match-row { transition: background .15s; }
    .match-badge-ok   { background:#dcfce7; color:#166534; border-radius:9999px; padding:1px 8px; font-size:11px; font-weight:600; }
    .match-badge-warn { background:#fef9c3; color:#854d0e; border-radius:9999px; padding:1px 8px; font-size:11px; font-weight:600; }
    .match-badge-err  { background:#fee2e2; color:#991b1b; border-radius:9999px; padding:1px 8px; font-size:11px; font-weight:600; }
</style>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen" style="max-width:100%;">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-4 md:px-8 pt-6">
        <a href="/pages/programs/youth-employability.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Youth Employability Programs
        </a>
    </div>

    <div class="px-4 md:px-8 py-2 pb-8">

        <!-- Row 1 Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-4">
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span id="card-registered" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg"><svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Registered</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="card-referred" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg"><svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Referred</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="card-placed" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-green-100 p-2 rounded-lg"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Placed</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="card-vacancies" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Job Vacancies</span>
            </div>
        </div>

        <!-- Row 2 Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <span id="card-spes-baby" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-pink-100 p-2 rounded-lg"><svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">SPES Baby Beneficiaries</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span id="card-fourps" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg"><svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">4Ps Beneficiaries</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span id="card-pwd" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-cyan-100 p-2 rounded-lg"><svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">PWD Beneficiaries</span>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col gap-2 mb-4">
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Filter by year:</span>
                    <select id="yearFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"></select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Filter by month:</span>
                    <select id="monthFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"></select>
                </div>
                <!-- Fill Vacancies button in filter bar too -->
                <button id="bulkFillBtn" onclick="openBulkFillModal()" class="ml-auto inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-teal-500 hover:bg-teal-600 text-white text-sm font-medium shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Fill Vacancies
                </button>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchEmployer" placeholder="Search employer..."
                        oninput="handleSearch()"
                        class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
                </div>
                <span id="loadingIndicator" class="text-xs text-gray-400 hidden shrink-0">Loading…</span>
            </div>
        </div>

        <!-- Main SPES Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-4 md:px-6 py-4 border-b border-gray-100 rounded-t-2xl">
                <h2 class="font-bold text-gray-800 text-sm md:text-base">Special Program for Employment of Students (SPES)</h2>
            </div>
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs min-w-[900px]" id="spesTable">
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
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-500 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-teal-600">T</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-500 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-500 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-500 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-500 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-pink-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-500 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-purple-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-500 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-cyan-500">T</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="27" class="px-4 py-8 text-center text-gray-400 text-sm">Loading data…</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 px-4 md:px-6 py-4 border-t border-gray-100">
                <span class="text-sm text-gray-500" id="paginationInfo"></span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed" disabled>&#8249;</button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">&#8250;</button>
                </div>
            </div>
        </div>

        <!-- Monthly LGU/Private Summary Table -->
        <div class="bg-white rounded-2xl shadow-sm">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-4 md:px-6 py-4 border-b border-gray-100 rounded-t-2xl">
                <h2 class="font-bold text-gray-800 text-sm md:text-base">Monthly SPES-LGU / SPES-Private Summary <span class="text-gray-400 font-normal text-sm">(Placed)</span></h2>
            </div>
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs min-w-[600px]">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-gray-500 font-medium w-36" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-4 py-3 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100 bg-teal-50">
                                <div class="flex items-center justify-center gap-1.5"><div class="w-2 h-2 rounded-full bg-teal-400"></div>SPES-LGU</div>
                            </th>
                            <th colspan="3" class="px-4 py-3 text-center text-blue-600 font-semibold tracking-wide border-l border-gray-100 bg-blue-50">
                                <div class="flex items-center justify-center gap-1.5"><div class="w-2 h-2 rounded-full bg-blue-400"></div>SPES-PRIVATE</div>
                            </th>
                            <th colspan="3" class="px-4 py-3 text-center text-green-600 font-semibold tracking-wide border-l border-gray-100 bg-green-50">
                                <div class="flex items-center justify-center gap-1.5"><div class="w-2 h-2 rounded-full bg-green-400"></div>COMBINED TOTAL</div>
                            </th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-4 py-2 text-center text-gray-500 font-medium">F</th><th class="px-4 py-2 text-center font-semibold text-teal-600">T</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-4 py-2 text-center text-gray-500 font-medium">F</th><th class="px-4 py-2 text-center font-semibold text-blue-600">T</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-4 py-2 text-center text-gray-500 font-medium">F</th><th class="px-4 py-2 text-center font-semibold text-green-600">T</th>
                        </tr>
                    </thead>
                    <tbody id="summaryBody">
                        <tr><td colspan="10" class="px-4 py-6 text-center text-gray-400 text-sm">Loading…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<!-- ══════════════════════════════════════════════════════════════════════════ -->
<!--  BULK FILL MODAL                                                          -->
<!-- ══════════════════════════════════════════════════════════════════════════ -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- Unfilled Data Modal -->
<div id="unfilledModal" class="fixed inset-0 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-yellow-50">
            <div class="flex items-start gap-3">
                <div class="bg-amber-100 p-3 rounded-xl shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-lg font-bold text-gray-900">Job Vacancies &amp; Referred data is incomplete</h3>
                    <p class="text-sm text-gray-600 mt-1" id="unfilledModalDetail">Some months have companies with no vacancy or referral data entered yet.</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <p class="text-sm text-gray-600 leading-relaxed" id="unfilledModalBody">You can fill the missing values now, or dismiss this reminder and continue browsing.</p>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-end gap-2">
            <button onclick="closeUnfilledModal()" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">Dismiss</button>
            <button onclick="openBulkFillFromWarning()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Fill in Data
            </button>
        </div>
    </div>
</div>

<!-- Bulk Fill Modal -->
<div id="bulkFillModal" class="fixed inset-0 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col">

        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="bg-teal-100 p-2 rounded-xl">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Fill Vacancies</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Enter counts per company. You can also import from Excel.</p>
                </div>
            </div>
            <button onclick="closeBulkFillModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Month Tabs -->
        <div class="px-6 pt-4 shrink-0">
            <div id="monthTabsContainer" class="flex flex-wrap gap-2 mb-4">
                <!-- tabs rendered by JS -->
            </div>

            <!-- Tab: Mode switcher (Manual / Excel Import) -->
            <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1 w-fit mb-4">
                <button id="tabManual" onclick="switchMode('manual')"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-colors bg-white text-gray-800 shadow-sm">
                    ✏️ Manual Entry
                </button>
                <button id="tabExcel" onclick="switchMode('excel')"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-colors text-gray-500 hover:text-gray-700">
                    📊 Import from Excel
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="flex-1 overflow-y-auto px-6 pb-2">

            <!-- ── MANUAL MODE ─────────────────────────────────────────────── -->
            <div id="manualMode">
                <p class="text-xs text-gray-400 mb-3">
                    Showing companies for <strong id="activeMonthLabel" class="text-gray-700"></strong>.
                    Rows highlighted in yellow still have all zeros for vacancies.
                </p>
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-xs min-w-[600px]">
                        <thead class="bg-gray-50">
                            <tr class="border-b border-gray-100">
                                <th class="text-left px-4 py-2.5 text-gray-500 font-medium">COMPANY</th>
                                <th class="px-3 py-2.5 text-center text-orange-500 font-semibold" colspan="2">JOB VACANCIES</th>
                            </tr>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th></th>
                                <th class="px-3 py-1.5 text-center text-gray-400 font-medium text-[11px]">MALE</th>
                                <th class="px-3 py-1.5 text-center text-gray-400 font-medium text-[11px]">FEMALE</th>
                            </tr>
                        </thead>
                        <tbody id="manualTableBody">
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Select a month above to load companies.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── EXCEL IMPORT MODE ───────────────────────────────────────── -->
            <div id="excelMode" class="hidden">
                <!-- Step 1: Upload -->
                <div id="excelStep1">
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:border-teal-300 transition-colors cursor-pointer" id="excelDropZone" onclick="document.getElementById('excelFileInput').click()">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm font-semibold text-gray-600">Drop your Excel file here</p>
                        <p class="text-xs text-gray-400 mt-1">or click to browse — .xlsx or .xls</p>
                        <p class="text-xs text-gray-300 mt-3">Expected columns: <span class="font-mono bg-gray-100 px-1 rounded">COMPANY</span> <span class="font-mono bg-gray-100 px-1 rounded">VACANCY MALE</span> <span class="font-mono bg-gray-100 px-1 rounded">VACANCY FEMALE</span> </p>
                    </div>
                    <input type="file" id="excelFileInput" accept=".xlsx,.xls" class="hidden" onchange="handleExcelUpload(this)"/>
                    <p id="excelUploadError" class="text-xs text-red-500 mt-2 hidden"></p>
                </div>

                <!-- Step 2: Review & Match -->
                <div id="excelStep2" class="hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Review &amp; Match Companies</p>
                            <p class="text-xs text-gray-500 mt-0.5">Companies from your Excel are matched to the system. Fix any mismatches below.</p>
                        </div>
                        <button onclick="resetExcelImport()" class="text-xs text-gray-400 hover:text-gray-600 underline">Upload different file</button>
                    </div>

                    <!-- Legend -->
                    <div class="flex flex-wrap gap-3 mb-3 text-xs">
                        <span class="flex items-center gap-1.5"><span class="match-badge-ok">Matched</span> Exact match found</span>
                        <span class="flex items-center gap-1.5"><span class="match-badge-warn">Review</span> Close match — please confirm</span>
                        <span class="flex items-center gap-1.5"><span class="match-badge-err">Not Found</span> Select manually</span>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="w-full text-xs min-w-[700px]">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-4 py-2.5 text-gray-500 font-medium">COMPANY IN EXCEL</th>
                                    <th class="text-left px-4 py-2.5 text-gray-500 font-medium">MATCHED TO (SYSTEM)</th>
                                    <th class="px-3 py-2.5 text-center text-orange-500 font-semibold">VAC M</th>
                                    <th class="px-3 py-2.5 text-center text-orange-400 font-semibold">VAC F</th>
                                    <th class="px-3 py-2.5 text-center text-gray-400 font-medium">STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="excelMatchBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 shrink-0">
            <span class="text-xs text-gray-400" id="bulkFillStatus"></span>
            <div class="flex gap-2">
                <button onclick="closeBulkFillModal()" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</button>
                <button onclick="saveBulkData()" id="saveBulkBtn" class="px-5 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold shadow-sm transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    Save All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-lg"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <h3 class="text-lg font-bold text-gray-900">Delete Entry</h3>
        </div>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this entry? This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmDelete()" class="flex-1 px-4 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600">Delete</button>
        </div>
    </div>
</div>

<!-- Save Modal -->
<div id="saveModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-green-100 p-3 rounded-lg"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <h3 class="text-lg font-bold text-gray-900">Save Changes</h3>
        </div>
        <p class="text-gray-600 mb-6">Do you want to save the changes to this entry?</p>
        <div class="flex gap-3">
            <button onclick="closeSaveModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmSave()" class="flex-1 px-4 py-2 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600">Save</button>
        </div>
    </div>
</div>

<!-- Error Toast -->
<div id="errorToast" class="fixed bottom-6 right-6 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm hidden z-50"></div>
<!-- Success Toast -->
<div id="successToast" class="fixed bottom-6 right-6 bg-teal-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm hidden z-50"></div>

<!-- SheetJS for Excel parsing client-side -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
const API_URL        = '/backend/youth-employ/spes/show-spes.php';
const ENTRY_API_URL  = '/backend/youth-employ/spes/entry-spes.php';
const ROWS_PER_PAGE  = 9;

let allRows      = [];
let currentPage  = 1;
let selectedYear = new Date().getFullYear();
let selectedMonth = '';
let searchQuery  = '';
let deletingId   = null;
let savingId     = null;
let editSnapshot = {};
let searchTimer  = null;
let unfilledModalShown = false;

// Bulk fill state
let unfilledMonths   = [];   // [{ month, month_name, total_companies, unfilled_vac, unfilled_ref }]
let activeMonth      = null; // currently selected month number in modal
let bulkCompanies    = [];   // companies loaded for activeMonth
let currentMode      = 'manual';
let excelImportRows  = [];   // parsed + matched rows from Excel

const MONTH_OPTIONS = [
    { value: '', label: 'All months' },
    { value: '1',  label: 'January'   }, { value: '2',  label: 'February'  },
    { value: '3',  label: 'March'     }, { value: '4',  label: 'April'     },
    { value: '5',  label: 'May'       }, { value: '6',  label: 'June'      },
    { value: '7',  label: 'July'      }, { value: '8',  label: 'August'    },
    { value: '9',  label: 'September' }, { value: '10', label: 'October'   },
    { value: '11', label: 'November'  }, { value: '12', label: 'December'  },
];

// ─── API ───────────────────────────────────────────────────────────────────
async function fetchData(year, search = '') {
    showLoading(true);
    try {
        const params = new URLSearchParams({ year, search });
        if (selectedMonth !== '') params.set('month', selectedMonth);
        const res  = await fetch(`${API_URL}?${params}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);
        return json.data;
    } catch (e) {
        showError('Failed to load data: ' + e.message);
        return null;
    } finally {
        showLoading(false);
    }
}

async function deleteRecord(id) {
    const res  = await fetch(`${API_URL}?id=${id}`, { method: 'DELETE' });
    const json = await res.json();
    if (!json.success) throw new Error(json.error);
}

async function updateRecord(id, payload) {
    const res  = await fetch(API_URL, {
        method:  'PUT',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ spes_id: id, ...payload }),
    });
    const json = await res.json();
    if (!json.success) throw new Error(json.error);
}

// ─── Row builders ──────────────────────────────────────────────────────────
function fmt(date) {
    if (!date) return '—';
    const d = new Date(date);
    return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
}

function mft(m, f, tc, bg, border = true) {
    const bl = border ? 'border-l border-gray-100' : '';
    return `<td class="px-2 py-2 text-center text-gray-600 ${bl}">${+m}</td>
            <td class="px-2 py-2 text-center text-gray-600">${+f}</td>
            <td class="px-2 py-2 text-center font-semibold ${tc} ${bg}">${+m + +f}</td>`;
}

function buildRow(r) {
    const id = r.spes_id;
    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50" data-id="${id}">
        <td class="px-3 py-2 text-gray-700 font-medium editable-month">${r.month_reported}</td>
        <td class="px-3 py-2 text-gray-700 editable-employer">${r.employer ?? '—'}</td>
        <td class="px-3 py-2 text-gray-600 editable-start">${fmt(r.start_of_contract)}</td>
        <td class="px-3 py-2 text-gray-600 editable-end">${fmt(r.end_of_contract)}</td>
        <td class="px-3 py-2 text-center text-gray-700 font-medium editable-days">${r.days ?? '—'}</td>
        ${mft(r.reg_m,       r.reg_f,       'text-teal-600',   'bg-teal-50')}
        ${mft(r.ref_m,       r.ref_f,       'text-blue-500',   'bg-blue-50')}
        ${mft(r.placed_m,    r.placed_f,    'text-green-500',  'bg-green-50')}
        ${mft(r.vac_m,       r.vac_f,       'text-orange-400', 'bg-orange-50')}
        ${mft(r.spes_baby_m, r.spes_baby_f, 'text-pink-500',   'bg-pink-50')}
        ${mft(r.fourps_m,    r.fourps_f,    'text-purple-500', 'bg-purple-50')}
        ${mft(r.pwd_m,       r.pwd_f,       'text-cyan-500',   'bg-cyan-50')}
        <td class="px-3 py-2 text-center border-l border-gray-100">
            <div class="flex items-center justify-center gap-2">
                <button onclick="startEdit('${id}')" class="edit-btn text-yellow-500 hover:text-yellow-600" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="promptDelete('${id}')" class="delete-btn text-red-400 hover:text-red-600" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <button onclick="promptSave('${id}')" class="save-btn hidden text-green-500 hover:text-green-600" title="Save">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button onclick="cancelEdit('${id}')" class="cancel-btn hidden text-gray-400 hover:text-gray-600" title="Cancel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </td>
    </tr>`;
}

function buildTotalRow(rows) {
    const t = { reg_m:0,reg_f:0,ref_m:0,ref_f:0,placed_m:0,placed_f:0,
                vac_m:0,vac_f:0,baby_m:0,baby_f:0,fps_m:0,fps_f:0,pwd_m:0,pwd_f:0 };
    rows.forEach(r => {
        t.reg_m    += +r.reg_m;       t.reg_f    += +r.reg_f;
        t.ref_m    += +r.ref_m;       t.ref_f    += +r.ref_f;
        t.placed_m += +r.placed_m;    t.placed_f += +r.placed_f;
        t.vac_m    += +r.vac_m;       t.vac_f    += +r.vac_f;
        t.baby_m   += +r.spes_baby_m; t.baby_f   += +r.spes_baby_f;
        t.fps_m    += +r.fourps_m;    t.fps_f    += +r.fourps_f;
        t.pwd_m    += +r.pwd_m;       t.pwd_f    += +r.pwd_f;
    });
    function t3(m, f, tc, bc) {
        return `<td class="px-2 py-2 text-center text-gray-700 border-l border-gray-100">${m}</td>
                <td class="px-2 py-2 text-center text-gray-700">${f}</td>
                <td class="px-2 py-2 text-center font-bold ${tc} ${bc}">${m+f}</td>`;
    }
    return `
    <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-3 py-2 text-gray-800 font-bold">TOTAL</td>
        <td class="px-3 py-2 text-gray-500 text-xs italic"></td>
        <td colspan="3" class="px-3 py-2 border-l border-gray-100"></td>
        ${t3(t.reg_m,    t.reg_f,    'text-teal-600',   'bg-teal-100')}
        ${t3(t.ref_m,    t.ref_f,    'text-blue-500',   'bg-blue-100')}
        ${t3(t.placed_m, t.placed_f, 'text-green-500',  'bg-green-100')}
        ${t3(t.vac_m,    t.vac_f,    'text-orange-400', 'bg-orange-100')}
        ${t3(t.baby_m,   t.baby_f,   'text-pink-500',   'bg-pink-100')}
        ${t3(t.fps_m,    t.fps_f,    'text-purple-500', 'bg-purple-100')}
        ${t3(t.pwd_m,    t.pwd_f,    'text-cyan-500',   'bg-cyan-100')}
        <td class="border-l border-gray-100"></td>
    </tr>`;
}

function buildSummaryBody(summary) {
    if (!summary.length) return `<tr><td colspan="10" class="px-4 py-6 text-center text-gray-400 text-sm">No data.</td></tr>`;
    const totLguM = summary.reduce((a,r) => a + +r.lgu_m,  0);
    const totLguF = summary.reduce((a,r) => a + +r.lgu_f,  0);
    const totPrvM = summary.reduce((a,r) => a + +r.priv_m, 0);
    const totPrvF = summary.reduce((a,r) => a + +r.priv_f, 0);
    const rows = summary.map(r => `
        <tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-6 py-3 text-gray-700 font-medium">${r.month_reported}</td>
            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${+r.lgu_m}</td>
            <td class="px-4 py-3 text-center text-gray-600">${+r.lgu_f}</td>
            <td class="px-4 py-3 text-center font-semibold text-teal-600 bg-teal-50">${+r.lgu_m + +r.lgu_f}</td>
            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${+r.priv_m}</td>
            <td class="px-4 py-3 text-center text-gray-600">${+r.priv_f}</td>
            <td class="px-4 py-3 text-center font-semibold text-blue-600 bg-blue-50">${+r.priv_m + +r.priv_f}</td>
            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${+r.lgu_m + +r.priv_m}</td>
            <td class="px-4 py-3 text-center text-gray-600">${+r.lgu_f + +r.priv_f}</td>
            <td class="px-4 py-3 text-center font-bold text-green-600 bg-green-50">${+r.lgu_m + +r.lgu_f + +r.priv_m + +r.priv_f}</td>
        </tr>`).join('');
    const total = `
        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
            <td class="px-6 py-3 text-gray-800 font-bold">TOTAL</td>
            <td class="px-4 py-3 text-center text-gray-700 border-l border-gray-100">${totLguM}</td>
            <td class="px-4 py-3 text-center text-gray-700">${totLguF}</td>
            <td class="px-4 py-3 text-center font-bold text-teal-600 bg-teal-100">${totLguM+totLguF}</td>
            <td class="px-4 py-3 text-center text-gray-700 border-l border-gray-100">${totPrvM}</td>
            <td class="px-4 py-3 text-center text-gray-700">${totPrvF}</td>
            <td class="px-4 py-3 text-center font-bold text-blue-600 bg-blue-100">${totPrvM+totPrvF}</td>
            <td class="px-4 py-3 text-center text-gray-700 border-l border-gray-100">${totLguM+totPrvM}</td>
            <td class="px-4 py-3 text-center text-gray-700">${totLguF+totPrvF}</td>
            <td class="px-4 py-3 text-center font-bold text-green-600 bg-green-100">${totLguM+totLguF+totPrvM+totPrvF}</td>
        </tr>`;
    return rows + total;
}

// ─── Render ────────────────────────────────────────────────────────────────
function renderTable() {
    const tbody   = document.getElementById('tableBody');
    const total   = allRows.length;
    const totalPg = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    currentPage   = Math.min(currentPage, totalPg);
    const start   = (currentPage - 1) * ROWS_PER_PAGE;
    const end     = Math.min(start + ROWS_PER_PAGE, total);

    if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="27" class="px-4 py-8 text-center text-gray-400 text-sm">No data found.</td></tr>`;
    } else {
        tbody.innerHTML = allRows.slice(start, end).map(buildRow).join('') + buildTotalRow(allRows);
    }

    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries found' : `Showing ${start + 1}–${end} of ${total} entries`;
    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPg;

    const container = document.getElementById('pageNumbers');
    container.innerHTML = '';
    for (let p = 1; p <= totalPg; p++) {
        const btn = document.createElement('button');
        btn.textContent = p;
        btn.className = 'px-3 py-1.5 rounded-lg text-sm border font-medium transition-colors ' +
            (p === currentPage ? 'bg-teal-500 text-white border-teal-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderTable(); };
        container.appendChild(btn);
    }
}

function updateCards(totals) {
    document.getElementById('card-registered').textContent = totals.registered;
    document.getElementById('card-referred').textContent   = totals.referred;
    document.getElementById('card-placed').textContent     = totals.placed;
    document.getElementById('card-vacancies').textContent  = totals.vacancies;
    document.getElementById('card-spes-baby').textContent  = totals.spes_baby;
    document.getElementById('card-fourps').textContent     = totals.fourps;
    document.getElementById('card-pwd').textContent        = totals.pwd;
}

function populateYearFilter(years) {
    const sel = document.getElementById('yearFilter');
    sel.innerHTML = years.map(y =>
        `<option value="${y}" ${y == selectedYear ? 'selected' : ''}>${y}</option>`
    ).join('');
}

function populateMonthFilter() {
    const sel = document.getElementById('monthFilter');
    sel.innerHTML = MONTH_OPTIONS.map(option =>
        `<option value="${option.value}" ${option.value === selectedMonth ? 'selected' : ''}>${option.label}</option>`
    ).join('');
}

// ─── Load ──────────────────────────────────────────────────────────────────
async function load(year, search = '') {
    const data = await fetchData(year, search);
    if (!data) return;
    allRows = data.rows;
    currentPage = 1;
    updateCards(data.totals);
    populateYearFilter(data.years);
    populateMonthFilter();
    renderTable();
    document.getElementById('summaryBody').innerHTML = buildSummaryBody(data.summary);
    checkUnfilledData(year);
}

// ─── Search (debounced) ────────────────────────────────────────────────────
function handleSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        searchQuery = document.getElementById('searchEmployer').value.trim();
        load(selectedYear, searchQuery);
    }, 300);
}

// ─── Edit ──────────────────────────────────────────────────────────────────
function getRowEl(id) { return document.querySelector(`tr[data-id="${id}"]`); }

function startEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    row.classList.add('bg-yellow-50');
    const editCells = ['editable-month','editable-employer','editable-start','editable-end','editable-days'];
    const snap = {};
    editCells.forEach(cls => {
        const cell = row.querySelector('.' + cls);
        snap[cls] = cell.textContent.trim();
        cell.contentEditable = 'true';
        cell.classList.add('border', 'border-yellow-300', 'bg-white', 'outline-none');
    });
    editSnapshot[id] = snap;
    row.querySelector('.edit-btn').classList.add('hidden');
    row.querySelector('.delete-btn').classList.add('hidden');
    row.querySelector('.save-btn').classList.remove('hidden');
    row.querySelector('.cancel-btn').classList.remove('hidden');
}

function cancelEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    const snap = editSnapshot[id] || {};
    ['editable-month','editable-employer','editable-start','editable-end','editable-days'].forEach(cls => {
        const cell = row.querySelector('.' + cls);
        if (cell) {
            cell.contentEditable = 'false';
            cell.textContent = snap[cls] ?? cell.textContent;
            cell.classList.remove('border','border-yellow-300','bg-white','outline-none');
        }
    });
    row.classList.remove('bg-yellow-50');
    row.querySelector('.edit-btn').classList.remove('hidden');
    row.querySelector('.delete-btn').classList.remove('hidden');
    row.querySelector('.save-btn').classList.add('hidden');
    row.querySelector('.cancel-btn').classList.add('hidden');
    delete editSnapshot[id];
}

function promptSave(id) { savingId = id; showModal('saveModal'); }

async function confirmSave() {
    const id  = savingId;
    const row = getRowEl(id);
    closeModal('saveModal');
    if (!row || !id) return;

    const payload = {
        month_reported:    row.querySelector('.editable-month').textContent.trim(),
        employer:          row.querySelector('.editable-employer').textContent.trim(),
        start_of_contract: row.querySelector('.editable-start').textContent.trim(),
        end_of_contract:   row.querySelector('.editable-end').textContent.trim(),
        days:              parseInt(row.querySelector('.editable-days').textContent.trim()) || 0,
    };

    try {
        await updateRecord(id, payload);
        ['editable-month','editable-employer','editable-start','editable-end','editable-days'].forEach(cls => {
            const cell = row.querySelector('.' + cls);
            if (cell) { cell.contentEditable = 'false'; cell.classList.remove('border','border-yellow-300','bg-white','outline-none'); }
        });
        row.classList.remove('bg-yellow-50');
        row.querySelector('.edit-btn').classList.remove('hidden');
        row.querySelector('.delete-btn').classList.remove('hidden');
        row.querySelector('.save-btn').classList.add('hidden');
        row.querySelector('.cancel-btn').classList.add('hidden');
        delete editSnapshot[id];

        row.style.transition = 'background-color 0.3s';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);

        const record = allRows.find(r => r.spes_id == id);
        if (record) Object.assign(record, payload);
    } catch (e) {
        showError('Save failed: ' + e.message);
    }
    savingId = null;
}

// ─── Delete ────────────────────────────────────────────────────────────────
function promptDelete(id) { deletingId = id; showModal('deleteModal'); }

async function confirmDelete() {
    const id = deletingId;
    closeModal('deleteModal');
    if (!id) return;
    try {
        await deleteRecord(id);
        allRows = allRows.filter(r => r.spes_id != id);
        renderTable();
        updateCards(buildSummaryTotals());
    } catch (e) {
        showError('Delete failed: ' + e.message);
    }
    deletingId = null;
}

function buildSummaryTotals() {
    const t = { registered:0, referred:0, placed:0, vacancies:0, spes_baby:0, fourps:0, pwd:0 };
    allRows.forEach(r => {
        t.registered += +r.reg_m    + +r.reg_f;
        t.referred   += +r.ref_m   + +r.ref_f;
        t.placed     += +r.placed_m + +r.placed_f;
        t.vacancies  += +r.vac_total;
        t.spes_baby  += +r.spes_baby_m + +r.spes_baby_f;
        t.fourps     += +r.fourps_m + +r.fourps_f;
        t.pwd        += +r.pwd_m   + +r.pwd_f;
    });
    return t;
}

// ─── Pagination ─────────────────────────────────────────────────────────────
function changePage(dir) {
    const totalPg = Math.max(1, Math.ceil(allRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPg));
    renderTable();
}

// ─── Modals (generic) ──────────────────────────────────────────────────────
function showModal(id)  { document.getElementById('modalBackdrop').classList.remove('hidden'); document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById('modalBackdrop').classList.add('hidden');    document.getElementById(id).classList.add('hidden'); }
function closeDeleteModal() { closeModal('deleteModal'); deletingId = null; }
function closeSaveModal()   { closeModal('saveModal');   savingId   = null; }
function showUnfilledModal() {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('unfilledModal').classList.remove('hidden');
    unfilledModalShown = true;
}
function closeUnfilledModal() {
    document.getElementById('unfilledModal').classList.add('hidden');
    if (document.getElementById('bulkFillModal').classList.contains('hidden')) {
        document.getElementById('modalBackdrop').classList.add('hidden');
    }
}
function openBulkFillFromWarning() {
    closeUnfilledModal();
    openBulkFillModal();
}
document.addEventListener('click', e => {
    if (e.target.id === 'modalBackdrop') { closeDeleteModal(); closeSaveModal(); closeBulkFillModal(); closeUnfilledModal(); }
});

// ─── UI helpers ────────────────────────────────────────────────────────────
function showLoading(state) { document.getElementById('loadingIndicator').classList.toggle('hidden', !state); }
function showError(msg) {
    const t = document.getElementById('errorToast');
    t.textContent = msg; t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 4000);
}
function showSuccess(msg) {
    const t = document.getElementById('successToast');
    t.textContent = msg; t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 3000);
}

function updateBulkFillButtonState() {
    const btn = document.getElementById('bulkFillBtn');
    if (!btn) return;

    const hasMissingData = unfilledMonths.length > 0;
    btn.disabled = !hasMissingData;
    btn.classList.toggle('opacity-50', !hasMissingData);
    btn.classList.toggle('cursor-not-allowed', !hasMissingData);
    btn.classList.toggle('hover:bg-teal-600', hasMissingData);
    btn.classList.toggle('hover:bg-teal-500', !hasMissingData);
    btn.title = hasMissingData ? 'Fill in missing vacancy and referral data' : 'All vacancy and referral data are already saved';
    btn.innerHTML = hasMissingData
        ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Fill Vacancies'
        : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> All Data Saved';
}

// ─── Year / Month filter ───────────────────────────────────────────────────
document.getElementById('yearFilter').addEventListener('change', function () {
    selectedYear = +this.value;
    load(selectedYear, searchQuery);
});
document.getElementById('monthFilter').addEventListener('change', function () {
    selectedMonth = this.value;
    load(selectedYear, searchQuery);
});

// ════════════════════════════════════════════════════════════════════════════
//  UNFILLED DATA PROMPT
// ════════════════════════════════════════════════════════════════════════════
async function checkUnfilledData(year) {
    try {
        const res  = await fetch(`${ENTRY_API_URL}?action=unfilled&year=${year}`);
        const json = await res.json();
        if (!json.success) return;
        unfilledMonths = json.data;
        updateBulkFillButtonState();

        if (!unfilledMonths.length) {
            closeUnfilledModal();
            return;
        }

        document.getElementById('unfilledModalDetail').textContent =
            `${unfilledMonths.length} month(s) have missing data: ${unfilledMonths.map(m => m.month_name).join(', ')}.`;
        document.getElementById('unfilledModalBody').textContent =
            'Fill the missing vacancy and referral counts now, or dismiss this reminder and continue browsing.';

        if (!unfilledModalShown) showUnfilledModal();
    } catch (e) { /* silent */ }
}

// ════════════════════════════════════════════════════════════════════════════
//  BULK FILL MODAL
// ════════════════════════════════════════════════════════════════════════════
function openBulkFillModal() {
    if (!unfilledMonths.length) {
        showSuccess('All vacancy and referred data is already saved for this year.');
        return;
    }

    document.getElementById('bulkFillModal').classList.remove('hidden');
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.body.classList.add('modal-open');
    renderMonthTabs();
    // Auto-select first unfilled month if any
    if (unfilledMonths.length) {
        selectMonth(unfilledMonths[0].month);
    } else {
        // no unfilled — load current month from all months in data
        const months = [...new Set(allRows.map(r => r.month))].sort((a,b)=>a-b);
        if (months.length) selectMonth(months[0]);
    }
}

function closeBulkFillModal() {
    document.getElementById('bulkFillModal').classList.add('hidden');
    if (document.getElementById('unfilledModal').classList.contains('hidden')) {
        document.getElementById('modalBackdrop').classList.add('hidden');
    }
    document.body.classList.remove('modal-open');
    resetExcelImport();
}

function renderMonthTabs() {
    // Build tab list from all months present in allRows + unfilled months
    const monthSet = new Map();
    allRows.forEach(r => {
        if (!monthSet.has(+r.month)) {
            monthSet.set(+r.month, r.month_reported);
        }
    });
    // Also include unfilled months even if no rows yet
    unfilledMonths.forEach(m => {
        if (!monthSet.has(+m.month)) monthSet.set(+m.month, m.month_name);
    });

    const sorted = [...monthSet.entries()].sort((a,b) => a[0]-b[0]);

    const container = document.getElementById('monthTabsContainer');
    if (!sorted.length) {
        container.innerHTML = '<span class="text-xs text-gray-400">No months available for the selected year.</span>';
        return;
    }

    container.innerHTML = sorted.map(([monthNum, monthName]) => {
        const isUnfilled = unfilledMonths.some(u => +u.month === +monthNum);
        const isActive   = activeMonth === +monthNum;
        return `<button
            onclick="selectMonth(${monthNum})"
            class="month-tab px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border
                ${isActive
                    ? 'bg-teal-500 text-white border-teal-500'
                    : isUnfilled
                        ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100'
                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'}"
            data-month="${monthNum}">
            ${monthName}${isUnfilled ? ' ⚠️' : ''}
        </button>`;
    }).join('');
}

async function selectMonth(monthNum) {
    activeMonth = +monthNum;
    renderMonthTabs(); // re-render to update active tab

    const monthLabel = MONTH_OPTIONS.find(m => +m.value === +monthNum)?.label ?? monthNum;
    document.getElementById('activeMonthLabel').textContent = `${monthLabel} ${selectedYear}`;
    document.getElementById('bulkFillStatus').textContent = 'Loading companies…';

    try {
        const res  = await fetch(`${ENTRY_API_URL}?action=companies&month=${monthNum}&year=${selectedYear}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);
        bulkCompanies = json.data;
        renderManualTable();
        document.getElementById('bulkFillStatus').textContent = `${bulkCompanies.length} companies loaded.`;
    } catch (e) {
        showError('Failed to load companies: ' + e.message);
        document.getElementById('bulkFillStatus').textContent = '';
    }
}

function renderManualTable() {
    const tbody = document.getElementById('manualTableBody');
    if (!bulkCompanies.length) {
        tbody.innerHTML = `<tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">No companies found for this month.</td></tr>`;
        return;
    }

    tbody.innerHTML = bulkCompanies.map(c => {
        const allZero = +c.vac_m === 0 && +c.vac_f === 0;
        const rowBg   = allZero ? 'bg-amber-50' : '';
        return `<tr class="company-match-row border-b border-gray-50 hover:bg-gray-50 ${rowBg}" data-company-id="${c.company_id}">
            <td class="px-4 py-2 text-gray-700 font-medium">${c.company_name}</td>
            <td class="px-3 py-2 text-center">
                <input type="number" min="0" class="vac-ref-input" data-field="vac_m" value="${c.vac_m}" placeholder="0"/>
            </td>
            <td class="px-3 py-2 text-center">
                <input type="number" min="0" class="vac-ref-input" data-field="vac_f" value="${c.vac_f}" placeholder="0"/>
            </td>
        </tr>`;
    }).join('');
}

// ─── Mode switch (Manual / Excel) ──────────────────────────────────────────
function switchMode(mode) {
    currentMode = mode;
    document.getElementById('manualMode').classList.toggle('hidden', mode !== 'manual');
    document.getElementById('excelMode').classList.toggle('hidden',  mode !== 'excel');
    document.getElementById('tabManual').className = mode === 'manual'
        ? 'px-4 py-1.5 rounded-lg text-sm font-medium transition-colors bg-white text-gray-800 shadow-sm'
        : 'px-4 py-1.5 rounded-lg text-sm font-medium transition-colors text-gray-500 hover:text-gray-700';
    document.getElementById('tabExcel').className = mode === 'excel'
        ? 'px-4 py-1.5 rounded-lg text-sm font-medium transition-colors bg-white text-gray-800 shadow-sm'
        : 'px-4 py-1.5 rounded-lg text-sm font-medium transition-colors text-gray-500 hover:text-gray-700';
}

// ─── Excel Import ───────────────────────────────────────────────────────────
function resetExcelImport() {
    document.getElementById('excelFileInput').value = '';
    document.getElementById('excelStep1').classList.remove('hidden');
    document.getElementById('excelStep2').classList.add('hidden');
    document.getElementById('excelUploadError').classList.add('hidden');
    excelImportRows = [];
}

function handleExcelUpload(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = async (e) => {
        try {
            const wb      = XLSX.read(e.target.result, { type: 'array' });
            const ws      = wb.Sheets[wb.SheetNames[0]];
            // header:1 = raw arrays, works regardless of merged cells or title rows
            const allRows = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });
            if (!allRows.length) throw new Error('The file appears to be empty.');

            const norm = v => v.toString().trim().toUpperCase().replace(/\s+/g, ' ');

            // ── 1. Find header row: first row whose cells include "COMPANY" / "EMPLOYER" ──
            // Also skip rows where most cells are numbers (sub-header rows like MALE/FEMALE labels)
            const COMPANY_KEYWORDS = ['COMPANY', 'COMPANY NAME', 'EMPLOYER', 'ESTABLISHMENT'];
            let headerIdx = -1, colMap = {};

            for (let i = 0; i < Math.min(allRows.length, 15); i++) {
                const row = allRows[i].map(norm);
                // Skip rows that are mostly numeric (data rows or sub-header rows with M/F labels)
                const nonEmpty = row.filter(v => v !== '');
                const numericCount = nonEmpty.filter(v => !isNaN(v) && v !== '').length;
                if (nonEmpty.length > 0 && numericCount / nonEmpty.length > 0.5) continue;

                const compCol = row.findIndex(v => COMPANY_KEYWORDS.includes(v));
                if (compCol === -1) continue;

                headerIdx      = i;
                colMap.company = compCol;

                // Map numeric columns by keyword matching
                for (let c = 0; c < row.length; c++) {
                    if (c === compCol) continue;
                    const cell     = row[c];
                    const isVac    = cell.includes('VAC');
                    const isRef    = cell.includes('REF') || cell.includes('REFER');
                    const isMale   = cell.includes('MALE') && !cell.includes('FEMALE');
                    const isFemale = cell.includes('FEMALE');
                    if (isVac && isMale   && colMap.vac_m == null) colMap.vac_m = c;
                    if (isVac && isFemale && colMap.vac_f == null) colMap.vac_f = c;
                    if (isRef && isMale   && colMap.ref_m == null) colMap.ref_m = c;
                    if (isRef && isFemale && colMap.ref_f == null) colMap.ref_f = c;
                }

                // Positional fallback if names didn't resolve
                if (colMap.vac_m == null) colMap.vac_m = compCol + 1;
                if (colMap.vac_f == null) colMap.vac_f = compCol + 2;
                if (colMap.ref_m == null) colMap.ref_m = compCol + 3;
                if (colMap.ref_f == null) colMap.ref_f = compCol + 4;
                break;
            }

            if (headerIdx === -1) throw new Error(
                'Could not find a header row with a "COMPANY" or "EMPLOYER" column in the first 15 rows.'
            );

            // ── 2. Parse data rows, skip blanks, totals, and pure-label rows ──
            const isLikelyLabel = name => {
                const u = name.toUpperCase();
                return ['MALE', 'FEMALE', 'M', 'F', 'TOTAL', 'GRAND TOTAL', 'SUB-TOTAL', 'SUBTOTAL'].includes(u);
            };

            const parsed = allRows.slice(headerIdx + 1).map(row => ({
                excel_name: row[colMap.company]?.toString().trim() ?? '',
                vac_m: Math.max(0, parseInt(row[colMap.vac_m]) || 0),
                vac_f: Math.max(0, parseInt(row[colMap.vac_f]) || 0),

            })).filter(r => r.excel_name && !isLikelyLabel(r.excel_name));

            if (!parsed.length) throw new Error(
                'No company rows found after the header. Check that company names are in the "COMPANY" column.'
            );

            // ── 3. Match each row to system companies ────────────────────────
            // searchCompanies already does LIKE %query% so capitalization/spacing is fine
            excelImportRows = await Promise.all(parsed.map(async (row, idx) => {
                const suggestions = await searchCompanies(row.excel_name);
                // Exact match: normalize both sides (trim + upper) before comparing
                const normName = n => n.trim().toUpperCase().replace(/\s+/g, ' ');
                const exact    = suggestions.find(s => normName(s.company_name) === normName(row.excel_name));
                return {
                    ...row,
                    _idx:         idx,
                    matched_id:   exact ? exact.company_id   : (suggestions[0]?.company_id   ?? null),
                    matched_name: exact ? exact.company_name : (suggestions[0]?.company_name ?? null),
                    match_type:   exact ? 'exact' : (suggestions.length ? 'fuzzy' : 'none'),
                    suggestions,
                };
            }));

            renderExcelMatchTable();
            document.getElementById('excelStep1').classList.add('hidden');
            document.getElementById('excelStep2').classList.remove('hidden');

            const notFound = excelImportRows.filter(r => r.match_type === 'none').length;
            const status   = notFound
                ? `${excelImportRows.length} rows parsed — ⚠️ ${notFound} not matched (search below to fix)`
                : `${excelImportRows.length} rows parsed and matched.`;
            document.getElementById('bulkFillStatus').textContent = status;

        } catch (err) {
            const errEl = document.getElementById('excelUploadError');
            errEl.textContent = 'Error reading file: ' + err.message;
            errEl.classList.remove('hidden');
        }
    };
    reader.readAsArrayBuffer(file);
}

async function searchCompanies(query) {
    if (!query || query.trim().length < 2) return [];
    try {
        const res  = await fetch(`${ENTRY_API_URL}?action=search_companies&q=${encodeURIComponent(query.trim())}`);
        const json = await res.json();
        return json.success ? json.data : [];
    } catch { return []; }
}

// Called when user types in the search box on a "Not Found" row
async function searchAndAssign(idx, query) {
    if (!query || query.trim().length < 2) return;
    const results = await searchCompanies(query);
    if (!results.length) return;
    excelImportRows[idx].suggestions  = results;
    excelImportRows[idx].matched_id   = results[0].company_id;
    excelImportRows[idx].matched_name = results[0].company_name;
    excelImportRows[idx].match_type   = 'fuzzy';
    renderExcelMatchTable();
}

function renderExcelMatchTable() {
    const tbody    = document.getElementById('excelMatchBody');
    const notFound = excelImportRows.filter(r => r.match_type === 'none').length;

    // Show/hide the "X rows will be skipped" warning banner
    let banner = document.getElementById('excelSkipWarning');
    if (!banner) {
        banner = document.createElement('p');
        banner.id = 'excelSkipWarning';
        banner.className = 'text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-2';
        tbody.closest('table').before(banner);
    }
    banner.textContent = notFound
        ? `⚠️ ${notFound} row${notFound > 1 ? 's' : ''} could not be matched and will be skipped on save. Use the search box in those rows to fix them.`
        : '';
    banner.style.display = notFound ? '' : 'none';

    tbody.innerHTML = excelImportRows.map((row, idx) => {
        const badge = row.match_type === 'exact' ? `<span class="match-badge-ok">Matched</span>`
                    : row.match_type === 'fuzzy' ? `<span class="match-badge-warn">Review</span>`
                    : `<span class="match-badge-err">Not Found</span>`;

        const selectOpts = row.suggestions.map(s =>
            `<option value="${s.company_id}" ${s.company_id == row.matched_id ? 'selected' : ''}>${s.company_name}</option>`
        ).join('');

        // Not Found: show a live search input so user can correct it inline
        const matchCell = row.match_type === 'none'
            ? `<input type="text" placeholder="Type to search…"
                class="text-xs border border-amber-300 bg-amber-50 rounded-lg px-2 py-1 w-48 focus:outline-none focus:ring-2 focus:ring-teal-300"
                oninput="searchAndAssign(${idx}, this.value)"/>`
            : `<select class="text-xs border border-gray-200 rounded-lg px-2 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 max-w-[220px]"
                onchange="excelImportRows[${idx}].matched_id = +this.value">
                ${selectOpts}
               </select>`;

        const rowClass = row.match_type === 'none'
            ? 'border-b border-amber-100 bg-amber-50'
            : 'border-b border-gray-50 hover:bg-gray-50';

        return `<tr class="${rowClass}">
            <td class="px-4 py-2.5 text-gray-700 font-medium text-xs">${row.excel_name}</td>
            <td class="px-4 py-2.5">${matchCell}</td>
            <td class="px-3 py-2.5 text-center text-gray-700 font-medium">${row.vac_m}</td>
            <td class="px-3 py-2.5 text-center text-gray-700 font-medium">${row.vac_f}</td>
            <td class="px-3 py-2.5 text-center">${badge}</td>
        </tr>`;
    }).join('');
}

// ─── Save bulk data ─────────────────────────────────────────────────────────
async function saveBulkData() {
    if (!activeMonth) { showError('Please select a month first.'); return; }

    let entries = [];

    if (currentMode === 'manual') {
        // Read from manual table inputs
        document.querySelectorAll('#manualTableBody tr[data-company-id]').forEach(row => {
            const companyId = +row.dataset.companyId;
            const inputs    = row.querySelectorAll('.vac-ref-input');
            entries.push({
                company_id: companyId,
                vac_m: parseInt(inputs[0]?.value) || 0,
                vac_f: parseInt(inputs[1]?.value) || 0,
            });
        });
    } else {
        // Read from Excel import matched rows
        entries = excelImportRows
            .filter(r => r.matched_id)
            .map(r => ({ company_id: r.matched_id, vac_m: r.vac_m, vac_f: r.vac_f }));
    }

    if (!entries.length) { showError('No entries to save.'); return; }

    const btn = document.getElementById('saveBulkBtn');
    btn.disabled = true;
    btn.textContent = 'Saving…';
    document.getElementById('bulkFillStatus').textContent = 'Saving…';

    try {
        const res  = await fetch(ENTRY_API_URL, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ month: activeMonth, year: selectedYear, entries }),
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        showSuccess(`✓ Saved ${json.data.saved} companies for ${MONTH_OPTIONS.find(m=>+m.value===activeMonth)?.label}.`);
        closeBulkFillModal();
        load(selectedYear, searchQuery); // refresh table + re-check unfilled
    } catch (e) {
        showError('Save failed: ' + e.message);
        document.getElementById('bulkFillStatus').textContent = 'Save failed.';
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Save All';
    }
}

// Drag-and-drop for Excel drop zone
const dropZone = document.getElementById('excelDropZone');
if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-teal-400','bg-teal-50'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-teal-400','bg-teal-50'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-teal-400','bg-teal-50');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            const input = document.getElementById('excelFileInput');
            input.files = dt.files;
            handleExcelUpload(input);
        }
    });
}

// ─── Init ──────────────────────────────────────────────────────────────────
populateMonthFilter();
load(selectedYear);
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>