<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Job Fair';
$pageHeading = 'Job Fair';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<style>
    body.modal-open { overflow: hidden; }

    /* Bulk fill modal input cells */
    .vac-input {
        width: 52px;
        text-align: center;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 3px 4px;
        font-size: 12px;
        outline: none;
        transition: border-color .15s;
    }
    .vac-input:focus { border-color: #14b8a6; box-shadow: 0 0 0 2px rgba(20,184,166,.15); }
    .company-match-row { transition: background .15s; }
    .match-badge-ok   { background:#dcfce7; color:#166534; border-radius:9999px; padding:1px 8px; font-size:11px; font-weight:600; }
    .match-badge-warn { background:#fef9c3; color:#854d0e; border-radius:9999px; padding:1px 8px; font-size:11px; font-weight:600; }
    .match-badge-err  { background:#fee2e2; color:#991b1b; border-radius:9999px; padding:1px 8px; font-size:11px; font-weight:600; }
</style>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen min-w-0 overflow-hidden">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-4 md:px-8 pt-6">
        <a href="/pages/programs/employment-facilitation.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employment Facilitation Section
        </a>
    </div>

    <div class="px-4 md:px-8 pt-6 pb-24 md:pb-6 space-y-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-vacancies">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Job Vacancies</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-employers">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Employers</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-interviewed">—</span>
                    <div class="bg-cyan-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Interviewed</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-qualified">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Qualified</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-placed">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Placed / HOTS</span>
            </div>
        </div>

        <!-- Filters + Fill Vacancies button -->
        <div class="flex flex-col gap-2 mb-4">
            <!-- Row 1: Year + Month + Type filters + Fill Vacancies button -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Year:</span>
                    <select id="yearFilter" onchange="loadData()"
                            class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Month:</span>
                    <select id="monthFilter" onchange="applyFilters()"
                            class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">All Months</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Type:</span>
                    <div class="flex rounded-lg border border-gray-200 overflow-hidden text-sm">
                        <button id="filterAll"      onclick="setTypeFilter('')"         class="px-3 py-1.5 bg-teal-500 text-white font-medium transition-colors">All</button>
                        <button id="filterLocal"    onclick="setTypeFilter('LOCAL')"    class="px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition-colors border-l border-gray-200">Local</button>
                        <button id="filterOverseas" onclick="setTypeFilter('OVERSEAS')" class="px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition-colors border-l border-gray-200">Overseas</button>
                    </div>
                </div>
                <!-- Fill Vacancies button -->
                <button id="fillVacBtn" onclick="openFillVacModal()"
                        class="ml-auto inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-teal-500 hover:bg-teal-600 text-white text-sm font-medium shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Fill Vacancies
                </button>
            </div>
            <!-- Row 2: Search -->
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchCompany" placeholder="Search company…" oninput="filterTable()"
                           class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300"/>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Job Fair</h2>
                <span id="activeFilterBadge" class="hidden text-xs font-medium px-2.5 py-1 rounded-full bg-teal-100 text-teal-700"></span>
            </div>
            <div style="overflow-x: auto; overflow-y: hidden; scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6; -webkit-overflow-scrolling: touch; width: 100%; max-width: 100%;" class="[&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full">
                <table class="w-full text-xs min-w-[1400px]" id="jobFairTable">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-24" rowspan="2">MONTH</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-20" rowspan="2">TYPE</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-44" rowspan="2">DATE</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">PARTICIPATING EMPLOYER</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-indigo-500 font-semibold tracking-wide border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                            <th class="px-2 py-2 text-center text-gray-400 font-semibold tracking-wide border-l border-gray-100" rowspan="2">ACTIONS</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-teal-600">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-indigo-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="jobFairTbody">
                        <tr><td colspan="29" class="text-center py-8 text-gray-400 text-sm">Loading…</td></tr>
                    </tbody>
                    <tfoot id="jobFairTfoot" class="hidden">
                        <tr class="bg-gray-50 border-t-2 border-gray-200 font-semibold text-xs">
                            <td colspan="4" class="px-4 py-2 text-gray-700 font-bold uppercase tracking-wide">TOTALS</td>
                            <td id="ft-vm"    class="px-3 py-2 text-center text-blue-600 border-l border-gray-100"></td>
                            <td id="ft-vf"    class="px-3 py-2 text-center text-blue-600"></td>
                            <td id="ft-vt"    class="px-3 py-2 text-center text-blue-700 font-bold bg-blue-50"></td>
                            <td id="ft-rm"    class="px-3 py-2 text-center text-teal-600 border-l border-gray-100"></td>
                            <td id="ft-rf"    class="px-3 py-2 text-center text-teal-600"></td>
                            <td id="ft-rt"    class="px-3 py-2 text-center text-teal-700 font-bold bg-teal-50"></td>
                            <td id="ft-refm"  class="px-3 py-2 text-center text-indigo-600 border-l border-gray-100"></td>
                            <td id="ft-reff"  class="px-3 py-2 text-center text-indigo-600"></td>
                            <td id="ft-reft"  class="px-3 py-2 text-center text-indigo-700 font-bold bg-indigo-50"></td>
                            <td id="ft-im"    class="px-3 py-2 text-center text-cyan-600 border-l border-gray-100"></td>
                            <td id="ft-if"    class="px-3 py-2 text-center text-cyan-600"></td>
                            <td id="ft-it"    class="px-3 py-2 text-center text-cyan-700 font-bold bg-cyan-50"></td>
                            <td id="ft-qm"    class="px-3 py-2 text-center text-green-600 border-l border-gray-100"></td>
                            <td id="ft-qf"    class="px-3 py-2 text-center text-green-600"></td>
                            <td id="ft-qt"    class="px-3 py-2 text-center text-green-700 font-bold bg-green-50"></td>
                            <td id="ft-nm"    class="px-3 py-2 text-center text-red-500 border-l border-gray-100"></td>
                            <td id="ft-nf"    class="px-3 py-2 text-center text-red-500"></td>
                            <td id="ft-nt"    class="px-3 py-2 text-center text-red-600 font-bold bg-red-50"></td>
                            <td id="ft-pm"    class="px-3 py-2 text-center text-orange-500 border-l border-gray-100"></td>
                            <td id="ft-pf"    class="px-3 py-2 text-center text-orange-500"></td>
                            <td id="ft-pt"    class="px-3 py-2 text-center text-orange-600 font-bold bg-orange-50"></td>
                            <td id="ft-fm"    class="px-3 py-2 text-center text-purple-500 border-l border-gray-100"></td>
                            <td id="ft-ff"    class="px-3 py-2 text-center text-purple-500"></td>
                            <td id="ft-ft"    class="px-3 py-2 text-center text-purple-600 font-bold bg-purple-50"></td>
                            <td class="border-l border-gray-100"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-wrap items-center justify-between gap-2 px-4 md:px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
                <span class="text-sm text-gray-500" id="paginationInfo">—</span>
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

<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<!--  SHARED BACKDROP                                                           -->
<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- ─── Delete Modal ─────────────────────────────────────────────────────── -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Delete Batch</h3>
        </div>
        <p class="text-gray-600 mb-6">This will delete the entire import batch and all job fair records linked to it. This cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeAllModals()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmDelete()" class="flex-1 px-4 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600">Delete</button>
        </div>
    </div>
</div>

<!-- ─── Save Modal ───────────────────────────────────────────────────────── -->
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
        <p class="text-gray-600 mb-6">Save the month and year changes for this batch?</p>
        <div class="flex gap-3">
            <button onclick="closeAllModals()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmSave()" class="flex-1 px-4 py-2 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600">Save</button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<!--  UNFILLED WARNING MODAL                                                    -->
<!-- ═══════════════════════════════════════════════════════════════════════════ -->
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
                    <h3 class="text-lg font-bold text-gray-900">Job Vacancy data is incomplete</h3>
                    <p class="text-sm text-gray-600 mt-1" id="unfilledModalDetail">Some events have companies with no vacancy data entered yet.</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <p class="text-sm text-gray-600 leading-relaxed" id="unfilledModalBody">You can fill the missing values now, or dismiss this reminder and continue browsing.</p>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-end gap-2">
            <button onclick="closeUnfilledModal()" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">Dismiss</button>
            <button onclick="openFillVacFromWarning()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Fill in Data
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<!--  FILL VACANCIES MODAL                                                      -->
<!-- ═══════════════════════════════════════════════════════════════════════════ -->
<div id="fillVacModal" class="fixed inset-0 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="bg-teal-100 p-2 rounded-xl">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Fill Vacancies</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Enter job vacancy counts per company for each event. You can also import from Excel.</p>
                </div>
            </div>
            <button onclick="closeFillVacModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Event Tabs -->
        <div class="px-6 pt-4 shrink-0">
            <div id="eventTabsContainer" class="flex flex-wrap gap-2 mb-4">
                <!-- tabs rendered by JS -->
            </div>

            <!-- Mode switcher -->
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

            <!-- ── MANUAL MODE ── -->
            <div id="manualMode">
                <p class="text-xs text-gray-400 mb-3">
                    Showing companies for <strong id="activeEventLabel" class="text-gray-700"></strong>.
                    Rows highlighted in yellow still have all zeros for vacancies.
                </p>
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-xs min-w-[600px]">
                        <thead class="bg-gray-50">
                            <tr class="border-b border-gray-100">
                                <th class="text-left px-4 py-2.5 text-gray-500 font-medium">COMPANY</th>
                                <th class="px-3 py-2.5 text-center text-blue-500 font-semibold" colspan="2">JOB VACANCIES</th>
                            </tr>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th></th>
                                <th class="px-3 py-1.5 text-center text-gray-400 font-medium text-[11px]">MALE</th>
                                <th class="px-3 py-1.5 text-center text-gray-400 font-medium text-[11px]">FEMALE</th>
                            </tr>
                        </thead>
                        <tbody id="manualTableBody">
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Select an event above to load companies.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── EXCEL IMPORT MODE ── -->
            <div id="excelMode" class="hidden">
                <!-- Step 1: Upload -->
                <div id="excelStep1">
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:border-teal-300 transition-colors cursor-pointer"
                         id="excelDropZone" onclick="document.getElementById('excelFileInput').click()">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-600">Drop your Excel file here</p>
                        <p class="text-xs text-gray-400 mt-1">or click to browse — .xlsx or .xls</p>
                        <p class="text-xs text-gray-300 mt-3">Expected columns:
                            <span class="font-mono bg-gray-100 px-1 rounded">COMPANY</span>
                            <span class="font-mono bg-gray-100 px-1 rounded">VACANCY MALE</span>
                            <span class="font-mono bg-gray-100 px-1 rounded">VACANCY FEMALE</span>
                        </p>
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
                                    <th class="text-left px-4 py-2.5 text-gray-500 font-medium">MATCHED TO</th>
                                    <th class="px-3 py-2.5 text-center text-gray-500 font-medium">VAC M</th>
                                    <th class="px-3 py-2.5 text-center text-gray-500 font-medium">VAC F</th>
                                    <th class="px-3 py-2.5 text-center text-gray-500 font-medium">STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="excelMatchBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3 shrink-0">
            <span id="fillVacStatus" class="text-xs text-gray-400"></span>
            <div class="flex items-center gap-2">
                <button onclick="closeFillVacModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="saveFillBtn" onclick="saveFillData()"
                        class="inline-flex items-center gap-1.5 px-5 py-2 rounded-lg bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ─── Toasts ────────────────────────────────────────────────────────────── -->
<div id="errorToast"   class="fixed bottom-6 right-6 bg-red-500   text-white px-5 py-3 rounded-xl shadow-lg text-sm hidden z-50"></div>
<div id="successToast" class="fixed bottom-6 right-6 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm hidden z-50"></div>

<!-- SheetJS for Excel import -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
// ─── Config ───────────────────────────────────────────────────────────────────
const API_URL       = '/backend/emp-facilitation/job-fair/show-job-fair.php';
const ENTRY_API_URL = '/backend/emp-facilitation/job-fair/entry-job-fair.php';
const ROWS_PER_PAGE = 20;

// ─── State ────────────────────────────────────────────────────────────────────
let allRows         = [];
let filteredRows    = [];
let currentPage     = 1;
let activeType      = '';
let deletingId      = null;
let savingId        = null;
let editSnapshot    = {};

// Fill Vacancies modal state
let unfilledEvents    = [];       // [{jobfairevent_id, job_fair_type, date_start, venue, …}]
let activeEventId     = null;     // currently selected event in the modal
let fillCompanies     = [];       // companies loaded for activeEventId
let currentMode       = 'manual'; // 'manual' | 'excel'
let excelImportRows   = [];       // parsed rows from Excel upload
let unfilledModalShown = false;

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadData();
});

// ─── Type filter ──────────────────────────────────────────────────────────────
function setTypeFilter(type) {
    activeType = type;
    ['filterAll','filterLocal','filterOverseas'].forEach(id => {
        document.getElementById(id).className =
            'px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition-colors border-l border-gray-200';
    });
    const activeId = type === 'LOCAL' ? 'filterLocal' : type === 'OVERSEAS' ? 'filterOverseas' : 'filterAll';
    document.getElementById(activeId).className =
        'px-3 py-1.5 bg-teal-500 text-white font-medium transition-colors' +
        (activeId !== 'filterAll' ? ' border-l border-gray-200' : '');
    const badge = document.getElementById('activeFilterBadge');
    if (type) {
        badge.textContent = type === 'LOCAL' ? 'Local Job Fairs' : 'Overseas Job Fairs';
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
    applyFilters();
}

// ─── Load ─────────────────────────────────────────────────────────────────────
async function loadData() {
    const year  = document.getElementById('yearFilter').value || new Date().getFullYear();
    const tbody = document.getElementById('jobFairTbody');
    tbody.innerHTML = `<tr><td colspan="29" class="text-center py-8 text-gray-400 text-sm">Loading…</td></tr>`;
    document.getElementById('jobFairTfoot').classList.add('hidden');
    try {
        const res  = await fetch(`${API_URL}?year=${year}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error || 'Unknown error');
        const { rows, totals, years } = json.data;
        populateYears(years, parseInt(year));
        document.getElementById('card-vacancies').textContent   = totals.job_vacancies;
        document.getElementById('card-employers').textContent   = totals.employers;
        document.getElementById('card-interviewed').textContent = totals.interviewed;
        document.getElementById('card-qualified').textContent   = totals.qualified;
        document.getElementById('card-placed').textContent      = totals.placed;
        allRows     = rows;
        currentPage = 1;
        applyFilters();
        checkUnfilledData(year);
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="29" class="text-center py-8 text-red-400 text-sm">Error: ${escHtml(err.message)}</td></tr>`;
    }
}

function populateYears(years, selectedYear) {
    const sel = document.getElementById('yearFilter');
    sel.innerHTML = '';
    years.forEach(y => {
        const opt = document.createElement('option');
        opt.value = y; opt.textContent = y;
        if (y === selectedYear) opt.selected = true;
        sel.appendChild(opt);
    });
}

// ─── Filter ───────────────────────────────────────────────────────────────────
function filterTable() { applyFilters(); }

function applyFilters() {
    const q           = (document.getElementById('searchCompany').value || '').toLowerCase().trim();
    const monthFilter = document.getElementById('monthFilter').value;
    filteredRows = allRows.filter(r => {
        const matchType    = !activeType || r.job_fair_type.toUpperCase().includes(activeType);
        const matchCompany = !q || (r.company_name || '').toLowerCase().includes(q);
        const matchMonth   = !monthFilter || String(r.month_num) === monthFilter;
        return matchType && matchCompany && matchMonth;
    });
    updateFooter(computeTotals(filteredRows));
    currentPage = 1;
    renderPage();
}

function computeTotals(rows) {
    const keys = ['vacancy_male','vacancy_female','vacancy_total',
                  'reg_m','reg_f','reg_total',
                  'ref_m','ref_f','ref_total',
                  'int_m','int_f','int_total',
                  'qual_m','qual_f','qual_total',
                  'nqual_m','nqual_f','nqual_total',
                  'placed_m','placed_f','placed_total',
                  'ffi_m','ffi_f','ffi_total'];
    const t = {};
    keys.forEach(k => { t[k] = rows.reduce((s, r) => s + (parseInt(r[k]) || 0), 0); });
    return t;
}

function updateFooter(gt) {
    const map = {
        'ft-vm':gt.vacancy_male,  'ft-vf':gt.vacancy_female, 'ft-vt':gt.vacancy_total,
        'ft-rm':gt.reg_m,         'ft-rf':gt.reg_f,          'ft-rt':gt.reg_total,
        'ft-refm':gt.ref_m,       'ft-reff':gt.ref_f,        'ft-reft':gt.ref_total,
        'ft-im':gt.int_m,         'ft-if':gt.int_f,          'ft-it':gt.int_total,
        'ft-qm':gt.qual_m,        'ft-qf':gt.qual_f,         'ft-qt':gt.qual_total,
        'ft-nm':gt.nqual_m,       'ft-nf':gt.nqual_f,        'ft-nt':gt.nqual_total,
        'ft-pm':gt.placed_m,      'ft-pf':gt.placed_f,       'ft-pt':gt.placed_total,
        'ft-fm':gt.ffi_m,         'ft-ff':gt.ffi_f,          'ft-ft':gt.ffi_total,
    };
    Object.entries(map).forEach(([id, val]) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val ?? 0;
    });
    document.getElementById('jobFairTfoot').classList.toggle('hidden', filteredRows.length === 0);
}

// ─── Group: Month → Event → Employers ────────────────────────────────────────
function groupRows(rows) {
    const monthMap = new Map();
    rows.forEach(r => {
        const monthKey = `${r.month} ${r.year}`;
        if (!monthMap.has(monthKey)) monthMap.set(monthKey, { monthNum: r.month_num, year: r.year, events: new Map() });
        const events   = monthMap.get(monthKey).events;
        const eventKey = String(r.jobfairevent_id);
        if (!events.has(eventKey)) events.set(eventKey, { meta: r, employers: [] });
        events.get(eventKey).employers.push(r);
    });
    return monthMap;
}

// ─── Render page ──────────────────────────────────────────────────────────────
function renderPage() {
    const tbody      = document.getElementById('jobFairTbody');
    const total      = filteredRows.length;
    const totalPages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    currentPage      = Math.min(currentPage, totalPages);
    const start      = (currentPage - 1) * ROWS_PER_PAGE;
    const pageRows   = filteredRows.slice(start, start + ROWS_PER_PAGE);

    tbody.innerHTML = '';

    if (pageRows.length === 0) {
        tbody.innerHTML = `<tr><td colspan="29" class="text-center py-8 text-gray-400 text-sm">No entries found.</td></tr>`;
    } else {
        const monthMap = groupRows(pageRows);
        monthMap.forEach((monthData, monthKey) => {
            let monthRowspan = 0;
            monthData.events.forEach(ev => { monthRowspan += ev.employers.length; });
            let firstMonthRow = true;
            monthData.events.forEach(eventData => {
                const eventRowspan = eventData.employers.length;
                let firstEventRow  = true;
                eventData.employers.forEach(r => {
                    tbody.appendChild(buildRow(r, firstMonthRow ? monthRowspan : 0, firstEventRow ? eventRowspan : 0, monthKey));
                    firstMonthRow = false;
                    firstEventRow = false;
                });
            });
        });
    }

    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries found'
                    : `Showing ${start + 1}–${Math.min(start + ROWS_PER_PAGE, total)} of ${total} entries`;
    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;

    const container = document.getElementById('pageNumbers');
    container.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn       = document.createElement('button');
        btn.textContent = p;
        btn.className   = `px-3 py-1.5 rounded-lg text-sm border font-medium transition-colors ` +
            (p === currentPage ? 'bg-teal-500 text-white border-teal-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

// ─── Build row ────────────────────────────────────────────────────────────────
function buildRow(r, monthRowspan, eventRowspan, monthKey) {
    const tr = document.createElement('tr');
    tr.className = 'border-b border-gray-50 hover:bg-gray-50';
    tr.dataset.employer = (r.company_name || '').toLowerCase();

    const rowKey = `${r.jobfairevent_id}_${r.company_id}`;
    tr.dataset.id = rowKey;
    if (r.batch_id) tr.dataset.batchId = r.batch_id;

    const n   = v => parseInt(v) || 0;
    const td  = (val, cls = '') =>
        `<td class="px-3 py-2 text-center text-gray-600 ${cls}">${n(val)}</td>`;
    const tdB = (val, color, bg, borderL = false) =>
        `<td class="px-3 py-2 text-center font-semibold ${color} ${bg} ${borderL ? 'border-l border-gray-100' : ''}">${n(val)}</td>`;

    const fmtDate = d => {
        if (!d) return '';
        const dt = new Date(d);
        return isNaN(dt) ? d : dt.toLocaleDateString('en-PH', { month:'long', day:'numeric', year:'numeric' });
    };
    const dateStr = r.date_start
        ? (r.date_end && r.date_end !== r.date_start
            ? `<span class="block text-gray-500"><span class="font-medium text-gray-600">Start:</span> ${fmtDate(r.date_start)}</span><span class="block text-gray-500"><span class="font-medium text-gray-600">End:</span> ${fmtDate(r.date_end)}</span>`
            : `<span class="block text-gray-500"><span class="font-medium text-gray-600">Start:</span> ${fmtDate(r.date_start)}</span>`)
        : '—';

    const isOverseas = (r.job_fair_type || '').toUpperCase().includes('OVERSEAS');
    const typeBadge  = isOverseas
        ? `<span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">OVERSEAS</span>`
        : `<span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">LOCAL</span>`;

    const monthCell = monthRowspan > 0
        ? `<td class="px-4 py-3 font-bold text-sm text-teal-700 align-top bg-teal-50/40 border-r border-gray-100 whitespace-nowrap" rowspan="${monthRowspan}">${escHtml(monthKey)}</td>`
        : '';

    const eventCells = eventRowspan > 0
        ? `<td class="px-3 py-2 align-top border-r border-gray-100" rowspan="${eventRowspan}">${typeBadge}</td>
           <td class="px-3 py-2 text-gray-500 align-top text-xs leading-relaxed" rowspan="${eventRowspan}">${dateStr}</td>`
        : '';

    const batchId = r.batch_id || '';

    // Edit button removed per UX request; keep variable for template compatibility
    const editBtn = '';

    const actions = `
        <div class="flex items-center justify-center gap-2">
            ${editBtn}
            <button onclick="promptDelete('${rowKey}', '${batchId}', '${r.jobfairevent_id}', '${r.company_id}')" class="delete-btn text-red-400 hover:text-red-600" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
            <button onclick="promptSave('${rowKey}')" class="save-btn hidden text-green-500 hover:text-green-600" title="Save">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
            <button onclick="cancelEdit('${rowKey}')" class="cancel-btn hidden text-gray-400 hover:text-gray-600" title="Cancel">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>`;

    tr.innerHTML = `
        ${monthCell}
        ${eventCells}
        <td class="px-4 py-2 text-gray-700">${escHtml(r.company_name)}</td>
        <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">${n(r.vacancy_male)}</td>
        <td class="px-3 py-2 text-center text-blue-500 font-semibold">${n(r.vacancy_female)}</td>
        <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">${n(r.vacancy_total)}</td>
        ${td(r.reg_m,    'border-l border-gray-100')}${td(r.reg_f)}${tdB(r.reg_total,   'text-teal-600',   'bg-teal-50')}
        ${td(r.ref_m,    'border-l border-gray-100')}${td(r.ref_f)}${tdB(r.ref_total,   'text-indigo-500', 'bg-indigo-50')}
        ${td(r.int_m,    'border-l border-gray-100')}${td(r.int_f)}${tdB(r.int_total,   'text-cyan-500',   'bg-cyan-50')}
        ${td(r.qual_m,   'border-l border-gray-100')}${td(r.qual_f)}${tdB(r.qual_total,  'text-green-500',  'bg-green-50')}
        ${td(r.nqual_m,  'border-l border-gray-100')}${td(r.nqual_f)}${tdB(r.nqual_total, 'text-red-400',    'bg-red-50')}
        ${td(r.placed_m, 'border-l border-gray-100')}${td(r.placed_f)}${tdB(r.placed_total,'text-orange-400', 'bg-orange-50')}
        ${td(r.ffi_m,    'border-l border-gray-100')}${td(r.ffi_f)}${tdB(r.ffi_total,   'text-purple-400', 'bg-purple-50')}
        <td class="px-3 py-2 text-center border-l border-gray-100">${actions}</td>
    `;
    return tr;
}

// ─── Delete ───────────────────────────────────────────────────────────────────
let deletingMeta = null;

function promptDelete(rowKey, batchId, eventId, companyId) {
    deletingId   = rowKey;
    deletingMeta = { rowKey, batchId, eventId, companyId };
    showModal('deleteModal');
}

async function confirmDelete() {
    const meta = deletingMeta;
    closeAllModals();
    if (!meta) return;
    try {
        const params = new URLSearchParams({
            event_id:   meta.eventId,
            company_id: meta.companyId,
        });
        if (meta.batchId) params.set('batch_id', meta.batchId);

        const res  = await fetch(`${API_URL}?${params}`, { method: 'DELETE' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        allRows = allRows.filter(r =>
            !(String(r.jobfairevent_id) === String(meta.eventId) &&
              String(r.company_id)      === String(meta.companyId))
        );
        applyFilters();
    } catch (e) {
        showError('Delete failed: ' + e.message);
    }
    deletingId   = null;
    deletingMeta = null;
}

// ─── Edit ─────────────────────────────────────────────────────────────────────
const EDIT_FIELDS = [
    'reg_m','reg_f', 'ref_m','ref_f', 'int_m','int_f',
    'qual_m','qual_f', 'nqual_m','nqual_f', 'placed_m','placed_f', 'ffi_m','ffi_f'
];

function getRowEl(id) {
    return document.querySelector(`tr[data-id="${id}"]`);
}

function startEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    row.classList.add('editing', 'bg-yellow-50');

    const cells = [...row.querySelectorAll('td:not([rowspan])')];
    const snap  = [];
    cells.forEach((cell, idx) => {
        snap.push(cell.textContent.trim());
        if (idx >= 4 && (idx - 4) % 3 !== 2) {
            cell.contentEditable = 'true';
            cell.classList.add('border', 'border-yellow-300', 'bg-white', 'outline-none');
            cell.addEventListener('input', () => recalcTotalsInRow(row));
        }
    });
    editSnapshot[id] = snap;

    const editBtnEl = row.querySelector('.edit-btn'); if (editBtnEl) editBtnEl.classList.add('hidden');
    const deleteBtnEl = row.querySelector('.delete-btn'); if (deleteBtnEl) deleteBtnEl.classList.add('hidden');
    const saveBtnEl = row.querySelector('.save-btn'); if (saveBtnEl) saveBtnEl.classList.remove('hidden');
    const cancelBtnEl = row.querySelector('.cancel-btn'); if (cancelBtnEl) cancelBtnEl.classList.remove('hidden');
}

function recalcTotalsInRow(row) {
    const cells = [...row.querySelectorAll('td:not([rowspan])')];
    for (let i = 4; i < cells.length - 1; i += 3) {
        const m = parseInt(cells[i]?.textContent) || 0;
        const f = parseInt(cells[i + 1]?.textContent) || 0;
        if (cells[i + 2]) cells[i + 2].textContent = m + f;
    }
}

function cancelEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    const snap  = editSnapshot[id] || [];
    const cells = [...row.querySelectorAll('td:not([rowspan])')];
    cells.forEach((cell, idx) => {
        cell.contentEditable = 'false';
        if (snap[idx] !== undefined) cell.textContent = snap[idx];
        cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
    });
    row.classList.remove('editing', 'bg-yellow-50');
    const editBtnEl2 = row.querySelector('.edit-btn'); if (editBtnEl2) editBtnEl2.classList.remove('hidden');
    const deleteBtnEl2 = row.querySelector('.delete-btn'); if (deleteBtnEl2) deleteBtnEl2.classList.remove('hidden');
    const saveBtnEl2 = row.querySelector('.save-btn'); if (saveBtnEl2) saveBtnEl2.classList.add('hidden');
    const cancelBtnEl2 = row.querySelector('.cancel-btn'); if (cancelBtnEl2) cancelBtnEl2.classList.add('hidden');
    delete editSnapshot[id];
}

function promptSave(rowKey) {
    savingId = rowKey;
    showModal('saveModal');
}

async function confirmSave() {
    const rowKey = savingId;
    const row    = getRowEl(rowKey);
    closeAllModals();
    if (!row || !rowKey) return;

    const batchId = row.dataset.batchId;
    if (!batchId) { showError('No batch data to save.'); savingId = null; return; }

    const cells   = [...row.querySelectorAll('td:not([rowspan])')];
    const payload = { batch_id: batchId };
    let fi = 0;
    cells.forEach((cell, idx) => {
        if (idx >= 4 && (idx - 4) % 3 !== 2) {
            payload[EDIT_FIELDS[fi++]] = parseInt(cell.textContent.trim()) || 0;
        }
    });

    try {
        const res  = await fetch(API_URL, {
            method:  'PUT',
            headers: {'Content-Type':'application/json'},
            body:    JSON.stringify(payload),
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        cells.forEach(cell => {
            cell.contentEditable = 'false';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
        });
        row.classList.remove('editing', 'bg-yellow-50');
        const editBtnEl3 = row.querySelector('.edit-btn'); if (editBtnEl3) editBtnEl3.classList.remove('hidden');
        const deleteBtnEl3 = row.querySelector('.delete-btn'); if (deleteBtnEl3) deleteBtnEl3.classList.remove('hidden');
        const saveBtnEl3 = row.querySelector('.save-btn'); if (saveBtnEl3) saveBtnEl3.classList.add('hidden');
        const cancelBtnEl3 = row.querySelector('.cancel-btn'); if (cancelBtnEl3) cancelBtnEl3.classList.add('hidden');
        delete editSnapshot[rowKey];

        row.style.transition = 'background-color 0.3s';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);

        const record = allRows.find(r => String(r.batch_id) === String(batchId));
        if (record) Object.assign(record, payload);

    } catch (e) {
        showError('Save failed: ' + e.message);
    }
    savingId = null;
}

// ─── Modal helpers ────────────────────────────────────────────────────────────
function showModal(id) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById(id).classList.remove('hidden');
}
function closeAllModals() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    ['deleteModal','saveModal','unfilledModal','fillVacModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
    deletingId = null; savingId = null;
    document.body.classList.remove('modal-open');
}

// ─── Misc ─────────────────────────────────────────────────────────────────────
function changePage(dir) {
    const totalPages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));
    currentPage      = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderPage();
}

function showError(msg) {
    const toast = document.getElementById('errorToast');
    toast.textContent = msg;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 4000);
}
function showSuccess(msg) {
    const toast = document.getElementById('successToast');
    toast.textContent = msg;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ═════════════════════════════════════════════════════════════════════════════
//  UNFILLED CHECK + FILL VACANCIES MODAL
// ═════════════════════════════════════════════════════════════════════════════

// ─── Check for events with missing vacancy data ───────────────────────────────
async function checkUnfilledData(year) {
    try {
        const res  = await fetch(`${ENTRY_API_URL}?action=unfilled&year=${year}`);
        const json = await res.json();
        if (!json.success) return;
        unfilledEvents = json.data;
        updateFillVacButtonState();

        if (!unfilledEvents.length) {
            // All data is filled — close the warning if it's open
            document.getElementById('unfilledModal').classList.add('hidden');
            if (document.getElementById('fillVacModal').classList.contains('hidden')) {
                document.getElementById('modalBackdrop').classList.add('hidden');
            }
            return;
        }

        const names = unfilledEvents.map(e => {
            const d = e.date_start ? new Date(e.date_start).toLocaleDateString('en-PH', { month:'short', day:'numeric' }) : '';
            return d ? `${d} (${e.venue?.split(',')[0] ?? 'Event'})` : (e.venue?.split(',')[0] ?? 'Event');
        });
        document.getElementById('unfilledModalDetail').textContent =
            `${unfilledEvents.length} event(s) have missing vacancy data: ${names.join(', ')}.`;
        document.getElementById('unfilledModalBody').textContent =
            'Fill the missing vacancy counts now, or dismiss this reminder and continue browsing.';

        if (!unfilledModalShown) {
            document.getElementById('modalBackdrop').classList.remove('hidden');
            document.getElementById('unfilledModal').classList.remove('hidden');
            unfilledModalShown = true;
        }
    } catch (e) { /* silent */ }
}

function updateFillVacButtonState() {
    const btn = document.getElementById('fillVacBtn');
    if (!btn) return;
    const hasMissing = unfilledEvents.length > 0;
    btn.disabled = !hasMissing;
    btn.classList.toggle('opacity-50',       !hasMissing);
    btn.classList.toggle('cursor-not-allowed', !hasMissing);
    btn.title = hasMissing
        ? 'Fill in missing vacancy data'
        : 'All vacancy data is already saved';
    btn.innerHTML = hasMissing
        ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Fill Vacancies'
        : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> All Data Saved';
}

function closeUnfilledModal() {
    document.getElementById('unfilledModal').classList.add('hidden');
    if (document.getElementById('fillVacModal').classList.contains('hidden')) {
        document.getElementById('modalBackdrop').classList.add('hidden');
    }
}
function openFillVacFromWarning() {
    closeUnfilledModal();
    openFillVacModal();
}

// ─── Open / Close Fill Vacancies Modal ───────────────────────────────────────
function openFillVacModal() {
    if (!unfilledEvents.length) {
        showSuccess('All vacancy data is already saved for this year.');
        return;
    }
    document.getElementById('fillVacModal').classList.remove('hidden');
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.body.classList.add('modal-open');
    renderEventTabs();
    // Auto-select first unfilled event
    if (unfilledEvents.length) selectEvent(unfilledEvents[0].jobfairevent_id);
}

function closeFillVacModal() {
    document.getElementById('fillVacModal').classList.add('hidden');
    if (document.getElementById('unfilledModal').classList.contains('hidden')) {
        document.getElementById('modalBackdrop').classList.add('hidden');
    }
    document.body.classList.remove('modal-open');
    resetExcelImport();
}

// ─── Event Tabs ───────────────────────────────────────────────────────────────
function renderEventTabs() {
    // Build tab list: all events that appear in allRows + any unfilled events
    const eventMap = new Map();
    allRows.forEach(r => {
        if (!eventMap.has(r.jobfairevent_id)) {
            const d = r.date_start
                ? new Date(r.date_start).toLocaleDateString('en-PH', { month:'short', day:'numeric' })
                : '';
            const label = d
                ? `${d} – ${(r.venue || '').split(',')[0]}`
                : ((r.venue || '').split(',')[0] || `Event ${r.jobfairevent_id}`);
            eventMap.set(r.jobfairevent_id, label);
        }
    });
    // Also include unfilled events not yet in allRows
    unfilledEvents.forEach(e => {
        if (!eventMap.has(e.jobfairevent_id)) {
            const d = e.date_start
                ? new Date(e.date_start).toLocaleDateString('en-PH', { month:'short', day:'numeric' })
                : '';
            const label = d
                ? `${d} – ${(e.venue || '').split(',')[0]}`
                : ((e.venue || '').split(',')[0] || `Event ${e.jobfairevent_id}`);
            eventMap.set(e.jobfairevent_id, label);
        }
    });

    const container = document.getElementById('eventTabsContainer');
    if (!eventMap.size) {
        container.innerHTML = '<span class="text-xs text-gray-400">No events available.</span>';
        return;
    }

    container.innerHTML = [...eventMap.entries()].map(([eid, label]) => {
        const isUnfilled = unfilledEvents.some(u => +u.jobfairevent_id === +eid);
        const isActive   = +activeEventId === +eid;
        return `<button
            onclick="selectEvent(${eid})"
            class="event-tab px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border
                ${isActive
                    ? 'bg-teal-500 text-white border-teal-500'
                    : isUnfilled
                        ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100'
                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'}"
            data-event="${eid}">
            ${escHtml(label)}${isUnfilled ? ' ⚠️' : ''}
        </button>`;
    }).join('');
}

async function selectEvent(eventId) {
    activeEventId = +eventId;
    renderEventTabs(); // re-render to update active tab

    // Build a readable label for the selected event
    const ev = unfilledEvents.find(e => +e.jobfairevent_id === +eventId)
            || allRows.find(r => +r.jobfairevent_id === +eventId);
    let label = `Event ${eventId}`;
    if (ev) {
        const d = (ev.date_start || ev.date_start)
            ? new Date(ev.date_start).toLocaleDateString('en-PH', { month:'long', day:'numeric', year:'numeric' })
            : '';
        const venue = (ev.venue || '').split(',')[0];
        label = [d, venue].filter(Boolean).join(' — ');
    }
    document.getElementById('activeEventLabel').textContent = label;
    document.getElementById('fillVacStatus').textContent = 'Loading companies…';

    try {
        const res  = await fetch(`${ENTRY_API_URL}?action=companies&event_id=${eventId}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);
        fillCompanies = json.data;
        renderManualTable();
        document.getElementById('fillVacStatus').textContent = `${fillCompanies.length} companies loaded.`;
    } catch (e) {
        showError('Failed to load companies: ' + e.message);
        document.getElementById('fillVacStatus').textContent = '';
    }
}

// ─── Manual table ─────────────────────────────────────────────────────────────
function renderManualTable() {
    const tbody = document.getElementById('manualTableBody');
    if (!fillCompanies.length) {
        tbody.innerHTML = `<tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">No companies found for this event.</td></tr>`;
        return;
    }
    tbody.innerHTML = fillCompanies.map(c => {
        const allZero = +c.vac_m === 0 && +c.vac_f === 0;
        const rowBg   = allZero ? 'bg-amber-50' : '';
        return `<tr class="company-match-row border-b border-gray-50 hover:bg-gray-50 ${rowBg}" data-company-id="${c.company_id}">
            <td class="px-4 py-2 text-gray-700 font-medium">${escHtml(c.company_name)}</td>
            <td class="px-3 py-2 text-center">
                <input type="number" min="0" class="vac-input" data-field="vac_m" value="${c.vac_m}" placeholder="0"/>
            </td>
            <td class="px-3 py-2 text-center">
                <input type="number" min="0" class="vac-input" data-field="vac_f" value="${c.vac_f}" placeholder="0"/>
            </td>
        </tr>`;
    }).join('');
}

// ─── Mode switch ──────────────────────────────────────────────────────────────
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

// ─── Excel Import ─────────────────────────────────────────────────────────────
function resetExcelImport() {
    const inp = document.getElementById('excelFileInput');
    if (inp) inp.value = '';
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
            const rawRows = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });
            if (!rawRows.length) throw new Error('The file appears to be empty.');

            const norm = v => v.toString().trim().toUpperCase().replace(/\s+/g, ' ');

            // Find header row
            const COMPANY_KEYWORDS = ['COMPANY', 'COMPANY NAME', 'EMPLOYER', 'ESTABLISHMENT'];
            let headerIdx = -1, colMap = {};
            for (let i = 0; i < Math.min(rawRows.length, 15); i++) {
                const cells = rawRows[i].map(norm);
                const cidx  = cells.findIndex(c => COMPANY_KEYWORDS.includes(c));
                if (cidx === -1) continue;
                const vmIdx = cells.findIndex(c => c.includes('MALE') || c === 'VAC M' || c === 'VACANCY M');
                const vfIdx = cells.findIndex(c => c.includes('FEMALE') || c === 'VAC F' || c === 'VACANCY F');
                if (vmIdx === -1 && vfIdx === -1) continue;
                headerIdx = i;
                colMap = { company: cidx, vac_m: vmIdx, vac_f: vfIdx };
                break;
            }

            if (headerIdx === -1) throw new Error('Could not find header row. Expected columns: COMPANY, VACANCY MALE, VACANCY FEMALE.');

            const dataRows = rawRows.slice(headerIdx + 1)
                .filter(r => r.some(c => c.toString().trim() !== ''))
                .map(r => ({
                    excel_name:   (r[colMap.company] ?? '').toString().trim(),
                    vac_m:        Math.max(0, parseInt(r[colMap.vac_m] ?? 0) || 0),
                    vac_f:        Math.max(0, parseInt(r[colMap.vac_f] ?? 0) || 0),
                    matched_id:   null,
                    match_type:   'none',
                    suggestions:  [],
                }))
                .filter(r => r.excel_name);

            if (!dataRows.length) throw new Error('No data rows found after the header.');

            // Match each company name against DB
            await Promise.all(dataRows.map(async row => {
                const res  = await fetch(`${ENTRY_API_URL}?action=search_companies&q=${encodeURIComponent(row.excel_name)}`);
                const json = await res.json();
                if (!json.success || !json.data.length) return;
                row.suggestions = json.data;

                const exactIdx = json.data.findIndex(
                    s => s.company_name.toUpperCase() === row.excel_name.toUpperCase()
                );
                if (exactIdx !== -1) {
                    row.matched_id  = json.data[exactIdx].company_id;
                    row.match_type  = 'exact';
                } else {
                    row.matched_id  = json.data[0].company_id;
                    row.match_type  = 'fuzzy';
                }
            }));

            excelImportRows = dataRows;
            document.getElementById('excelStep1').classList.add('hidden');
            document.getElementById('excelStep2').classList.remove('hidden');
            renderExcelMatchTable();

        } catch (err) {
            const errEl = document.getElementById('excelUploadError');
            errEl.textContent = err.message;
            errEl.classList.remove('hidden');
        }
    };
    reader.readAsArrayBuffer(file);
}

async function searchAndAssign(idx, query) {
    if (query.length < 2) return;
    const res  = await fetch(`${ENTRY_API_URL}?action=search_companies&q=${encodeURIComponent(query)}`);
    const json = await res.json();
    if (!json.success || !json.data.length) return;
    excelImportRows[idx].suggestions  = json.data;
    excelImportRows[idx].matched_id   = json.data[0].company_id;
    excelImportRows[idx].match_type   = 'fuzzy';
    renderExcelMatchTable();
}

function renderExcelMatchTable() {
    const tbody    = document.getElementById('excelMatchBody');
    const notFound = excelImportRows.filter(r => r.match_type === 'none').length;

    let banner = document.getElementById('excelSkipWarning');
    if (!banner) {
        banner = document.createElement('p');
        banner.id = 'excelSkipWarning';
        banner.className = 'text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-2';
        tbody.closest('table').before(banner);
    }
    banner.textContent = notFound
        ? `⚠️ ${notFound} row${notFound > 1 ? 's' : ''} could not be matched and will be skipped on save.`
        : '';
    banner.style.display = notFound ? '' : 'none';

    tbody.innerHTML = excelImportRows.map((row, idx) => {
        const badge = row.match_type === 'exact' ? `<span class="match-badge-ok">Matched</span>`
                    : row.match_type === 'fuzzy' ? `<span class="match-badge-warn">Review</span>`
                    : `<span class="match-badge-err">Not Found</span>`;

        const selectOpts = row.suggestions.map(s =>
            `<option value="${s.company_id}" ${s.company_id == row.matched_id ? 'selected' : ''}>${escHtml(s.company_name)}</option>`
        ).join('');

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
            <td class="px-4 py-2.5 text-gray-700 font-medium text-xs">${escHtml(row.excel_name)}</td>
            <td class="px-4 py-2.5">${matchCell}</td>
            <td class="px-3 py-2.5 text-center text-gray-700 font-medium">${row.vac_m}</td>
            <td class="px-3 py-2.5 text-center text-gray-700 font-medium">${row.vac_f}</td>
            <td class="px-3 py-2.5 text-center">${badge}</td>
        </tr>`;
    }).join('');
}

// ─── Save fill data ───────────────────────────────────────────────────────────
async function saveFillData() {
    if (!activeEventId) { showError('Please select an event first.'); return; }

    let entries = [];

    if (currentMode === 'manual') {
        document.querySelectorAll('#manualTableBody tr[data-company-id]').forEach(row => {
            const companyId = +row.dataset.companyId;
            const inputs    = row.querySelectorAll('.vac-input');
            entries.push({
                company_id: companyId,
                vac_m: parseInt(inputs[0]?.value) || 0,
                vac_f: parseInt(inputs[1]?.value) || 0,
            });
        });
    } else {
        entries = excelImportRows
            .filter(r => r.matched_id)
            .map(r => ({ company_id: r.matched_id, vac_m: r.vac_m, vac_f: r.vac_f }));
    }

    if (!entries.length) { showError('No entries to save.'); return; }

    const btn = document.getElementById('saveFillBtn');
    btn.disabled    = true;
    btn.textContent = 'Saving…';
    document.getElementById('fillVacStatus').textContent = 'Saving…';

    try {
        const res  = await fetch(ENTRY_API_URL, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ event_id: activeEventId, entries }),
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        showSuccess(`✓ Saved ${json.data.saved} companies for this event.`);
        closeFillVacModal();
        loadData(); // refresh table + re-check unfilled
    } catch (e) {
        showError('Save failed: ' + e.message);
        document.getElementById('fillVacStatus').textContent = 'Save failed.';
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Save All';
    }
}

// ─── Drag-and-drop for Excel zone ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const dropZone = document.getElementById('excelDropZone');
    if (dropZone) {
        dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('border-teal-400','bg-teal-50'); });
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

    // Close modals on backdrop click
    document.getElementById('modalBackdrop').addEventListener('click', () => {
        if (!document.getElementById('fillVacModal').classList.contains('hidden')) return; // don't close if fill modal is open
        closeAllModals();
    });
});
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>