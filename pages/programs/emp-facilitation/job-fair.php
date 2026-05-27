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

        <!-- Filters + Import button -->
        <div class="flex flex-col gap-2 mb-4">
            <!-- Row 1: Year + Month + Type filters -->
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

<!-- ─── Backdrop ──────────────────────────────────────────────────────────── -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"
     onclick="closeAllModals()"></div>

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

<!-- ─── Error Toast ──────────────────────────────────────────────────────── -->
<div id="errorToast" class="fixed bottom-6 right-6 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm hidden z-50"></div>

<script>
// ─── Config ───────────────────────────────────────────────────────────────────
const API_URL        = '/backend/emp-facilitation/job-fair/show-job-fair.php';
const ROWS_PER_PAGE  = 20;

// ─── State ────────────────────────────────────────────────────────────────────
let allRows      = [];
let filteredRows = [];
let currentPage  = 1;
let activeType   = '';
let deletingId   = null;  // batch_id
let savingId     = null;  // batch_id
let editSnapshot = {};    // { batch_id: { month, year } }

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

    // Use a composite key (always available) so getRowEl works even without a batch
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

    // Edit: always visible; greyed out with tooltip when no batch data yet
    const editBtn = batchId
        ? `<button onclick="startEdit('${rowKey}')" class="edit-btn text-yellow-500 hover:text-yellow-600" title="Edit">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
           </button>`
        : `<button class="edit-btn text-gray-300 cursor-not-allowed" title="No imported data to edit" disabled>
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
           </button>`;

    // Delete is always available (removes participant record)
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
let deletingMeta = null; // { rowKey, batchId, eventId, companyId }

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

        // Remove all rows for this event+company from local data
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
// Editable M/F columns (skip vacancy cols 0-2 which are from jobvacancies, and total cols)
// Row cells after month+type+date+company: vacancy_m, vacancy_f, vacancy_t,
//   reg_m, reg_f, reg_t, ref_m, ref_f, ref_t, int_m, int_f, int_t,
//   qual_m, qual_f, qual_t, nqual_m, nqual_f, nqual_t, placed_m, placed_f, placed_t, ffi_m, ffi_f, ffi_t
// Cell index 0 = company, 1-3 = vacancy, 4+ = stats. Edit only stats M/F (idx 4,5, 7,8, 10,11 …)
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

    // Cells: company(0), vac_m(1), vac_f(2), vac_t(3), then groups of 3 (m,f,t) × 7
    // Skip company (idx 0) and vacancies (idx 1-3); edit stat M/F cells only (every idx%3 != 2, starting at idx 4)
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

    row.querySelector('.edit-btn').classList.add('hidden');
    row.querySelector('.delete-btn').classList.add('hidden');
    row.querySelector('.save-btn').classList.remove('hidden');
    row.querySelector('.cancel-btn').classList.remove('hidden');
}

function recalcTotalsInRow(row) {
    const cells = [...row.querySelectorAll('td:not([rowspan])')];
    // Stat groups start at cell index 4, groups of 3 (m, f, total)
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
    row.querySelector('.edit-btn').classList.remove('hidden');
    row.querySelector('.delete-btn').classList.remove('hidden');
    row.querySelector('.save-btn').classList.add('hidden');
    row.querySelector('.cancel-btn').classList.add('hidden');
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

    // Retrieve the actual batch_id from the row's data attribute
    const batchId = row.dataset.batchId;
    if (!batchId) { showError('No batch data to save.'); savingId = null; return; }

    // Build payload: stat M/F cells starting at index 4, skipping totals
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

        // Commit edit styles
        cells.forEach(cell => {
            cell.contentEditable = 'false';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
        });
        row.classList.remove('editing', 'bg-yellow-50');
        row.querySelector('.edit-btn').classList.remove('hidden');
        row.querySelector('.delete-btn').classList.remove('hidden');
        row.querySelector('.save-btn').classList.add('hidden');
        row.querySelector('.cancel-btn').classList.add('hidden');
        delete editSnapshot[rowKey];

        // Flash green
        row.style.transition = 'background-color 0.3s';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);

        // Update local data so card totals stay correct
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
    ['deleteModal','saveModal'].forEach(id =>
        document.getElementById(id).classList.add('hidden')
    );
    deletingId = null; savingId = null;
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

function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>