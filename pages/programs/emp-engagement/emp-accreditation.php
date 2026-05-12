<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employers Accreditation';
$pageHeading = 'Employers Accreditation';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';?>

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

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <!-- Total Employers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardTotalEmployers">—</span>
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
                    <span class="text-2xl font-bold text-gray-800" id="cardNew">—</span>
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
                    <span class="text-2xl font-bold text-gray-800" id="cardRenewed">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Renewed Accreditations</span>
            </div>

            <!-- Active Employers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardActive">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 active-year-label">Active Employers</span>
            </div>

        </div>

        <!-- Filters + Action Buttons -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select id="filterYear" onchange="loadData()" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <!-- populated by JS -->
                </select>
            </div>
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchCompany" placeholder="Search company..."
                    oninput="applyFilters()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300" />
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

            <!-- Spacer -->
            <div class="flex-1"></div>

            <!-- Add Entry Button -->
            <button onclick="openAddModal()"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-medium transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Entry
            </button>
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
                        <tr id="loadingRow">
                            <td colspan="7" class="text-center py-10 text-gray-400 text-sm">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
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

<!-- ── Backdrop ────────────────────────────────────────────────────────────── -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- ── Add Entry Modal ────────────────────────────────────────────────────── -->
<div id="addModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4 animate-modal">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Add Employers Accreditation Entry</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Month</label>
                <select id="addMonth" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <option>January</option><option>February</option><option>March</option>
                    <option>April</option><option>May</option><option>June</option>
                    <option>July</option><option>August</option><option>September</option>
                    <option>October</option><option>November</option><option>December</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Year</label>
                <input id="addYear" type="number" value="<?= date('Y') ?>" min="2000" max="2099"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Accreditation</label>
                <select id="addAccreditation" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <option value="new">New</option>
                    <option value="renew">Renew</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Establishment Type</label>
                <select id="addEstType" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <option value="Manpower">Manpower</option>
                    <option value="Direct">Direct</option>
                    <option value="Direct (Overseas)">Direct (Overseas)</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">Company</label>
            <input id="addCompany" type="text" placeholder="e.g. Agro Prime Manpower Services"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300" />
        </div>

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">Industry</label>
            <input id="addIndustry" type="text" placeholder="e.g. Agriculture, Construction, Maritime"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300" />
        </div>

        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-600 mb-1">City/Municipality</label>
            <input id="addCity" type="text" placeholder="e.g. Digos City"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300" />
        </div>

        <div id="addError" class="text-red-500 text-xs mb-3 hidden"></div>

        <div class="flex gap-3">
            <button onclick="closeAddModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="submitAddEntry()" id="addSubmitBtn"
                class="flex-1 px-4 py-2 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600">
                Save Entry
            </button>
        </div>
    </div>
</div>

<!-- ── Edit Modal ─────────────────────────────────────────────────────────── -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4 animate-modal">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Edit Entry</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <input type="hidden" id="editCompanyId" />

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Month</label>
                <select id="editMonth" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                    <option>January</option><option>February</option><option>March</option>
                    <option>April</option><option>May</option><option>June</option>
                    <option>July</option><option>August</option><option>September</option>
                    <option>October</option><option>November</option><option>December</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Year</label>
                <input id="editYear" type="number" min="2000" max="2099"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-300" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Accreditation</label>
                <select id="editAccreditation" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                    <option value="new">New</option>
                    <option value="renew">Renew</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Establishment Type</label>
                <select id="editEstType" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                    <option value="Manpower">Manpower</option>
                    <option value="Direct">Direct</option>
                    <option value="Direct (Overseas)">Direct (Overseas)</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">Company</label>
            <input id="editCompany" type="text"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-300" />
        </div>

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">Industry</label>
            <input id="editIndustry" type="text"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-300" />
        </div>

        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-600 mb-1">City/Municipality</label>
            <input id="editCity" type="text"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-300" />
        </div>

        <div id="editError" class="text-red-500 text-xs mb-3 hidden"></div>

        <div class="flex gap-3">
            <button onclick="closeEditModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="submitEditEntry()" id="editSubmitBtn"
                class="flex-1 px-4 py-2 rounded-lg bg-yellow-500 text-white font-medium hover:bg-yellow-600">
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- ── Delete Confirmation Modal ──────────────────────────────────────────── -->
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

<script>
const API_URL = '/api/emp-accreditation-api.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];   // full dataset from API
let filteredRows = [];   // after client-side filter
let currentPage  = 1;
let deletingId   = null;

// ── Helpers ───────────────────────────────────────────────────────────────────
function estTypeBadge(type) {
    const t = (type || '').toLowerCase();
    if (t === 'manpower')         return `<span class="font-medium text-blue-500">Manpower</span>`;
    if (t === 'direct (overseas)') return `<span class="font-medium text-teal-500">Direct (Overseas)</span>`;
    return `<span class="font-medium text-green-500">Direct</span>`;
}

function accreditationBadge(val) {
    if (val === 'new')
        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">New</span>`;
    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Renew</span>`;
}

function actionBtns(id) {
    return `
        <div class="flex items-center justify-center gap-2">
            <button onclick="openEditModal(${id})" class="text-yellow-500 hover:text-yellow-600" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button onclick="openDeleteModal(${id})" class="text-red-400 hover:text-red-600" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>`;
}

// ── Load Data from API ────────────────────────────────────────────────────────
async function loadData() {
    const year = document.getElementById('filterYear').value || new Date().getFullYear();

    document.getElementById('accreditationTbody').innerHTML =
        `<tr><td colspan="7" class="text-center py-10 text-gray-400 text-sm">Loading...</td></tr>`;

    try {
        const res  = await fetch(`${API_URL}?year=${year}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        allRows = json.data.rows;

        // Update year dropdown if needed (preserve selection)
        const sel = document.getElementById('filterYear');
        const cur = sel.value;
        sel.innerHTML = json.data.years.map(y =>
            `<option value="${y}" ${y == year ? 'selected' : ''}>${y}</option>`
        ).join('');
        if (cur && json.data.years.includes(parseInt(cur))) sel.value = cur;

        // Update cards
        document.getElementById('cardTotalEmployers').textContent = json.data.totals.total;
        document.getElementById('cardNew').textContent            = json.data.totals.new;
        document.getElementById('cardRenewed').textContent        = json.data.totals.renewed;
        document.getElementById('cardActive').textContent         = json.data.totals.active;
        document.querySelectorAll('.active-year-label').forEach(el => {
            el.textContent = `Active Employers (${year})`;
        });

        applyFilters();
    } catch (err) {
        document.getElementById('accreditationTbody').innerHTML =
            `<tr><td colspan="7" class="text-center py-10 text-red-400 text-sm">Failed to load data: ${err.message}</td></tr>`;
    }
}

// ── Filter + Render ───────────────────────────────────────────────────────────
function applyFilters() {
    const query   = document.getElementById('searchCompany').value.toLowerCase().trim();
    const estType = document.getElementById('filterEstType').value.toLowerCase().trim();

    filteredRows = allRows.filter(r => {
        const companyMatch  = !query   || r.company_name.toLowerCase().includes(query);
        const estTypeMatch  = !estType || r.est_type.toLowerCase() === estType;
        return companyMatch && estTypeMatch;
    });

    currentPage = 1;
    renderPage();
}

function renderPage() {
    const total      = filteredRows.length;
    const totalPages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    const start      = (currentPage - 1) * ROWS_PER_PAGE;
    const end        = Math.min(start + ROWS_PER_PAGE, total);
    const pageRows   = filteredRows.slice(start, end);

    const tbody = document.getElementById('accreditationTbody');

    if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-10 text-gray-400 text-sm">No entries found.</td></tr>`;
    } else {
        tbody.innerHTML = pageRows.map(r => `
            <tr class="border-b border-gray-50 hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-700 font-medium">${r.month} ${r.year}</td>
                <td class="px-4 py-3">${accreditationBadge(r.accreditation)}</td>
                <td class="px-4 py-3 text-gray-700">${r.company_name}</td>
                <td class="px-4 py-3">${estTypeBadge(r.est_type)}</td>
                <td class="px-4 py-3 text-gray-600">${r.industry}</td>
                <td class="px-4 py-3 text-gray-600">${r.city}</td>
                <td class="px-4 py-3 text-center">${actionBtns(r.company_id)}</td>
            </tr>
        `).join('');
    }

    // Pagination info
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
    const totalPages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderPage();
}

// ── Add Entry ─────────────────────────────────────────────────────────────────
function openAddModal() {
    document.getElementById('addError').classList.add('hidden');
    document.getElementById('addCompany').value  = '';
    document.getElementById('addIndustry').value = '';
    document.getElementById('addCity').value     = '';
    // Pre-select current month
    const monthNames = ['January','February','March','April','May','June',
                        'July','August','September','October','November','December'];
    document.getElementById('addMonth').value = monthNames[new Date().getMonth()];
    document.getElementById('addYear').value  = new Date().getFullYear();

    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('addModal').classList.add('hidden');
}

async function submitAddEntry() {
    const errEl = document.getElementById('addError');
    errEl.classList.add('hidden');

    const payload = {
        month:        document.getElementById('addMonth').value,
        year:         parseInt(document.getElementById('addYear').value),
        accreditation: document.getElementById('addAccreditation').value,
        est_type:     document.getElementById('addEstType').value,
        company_name: document.getElementById('addCompany').value.trim(),
        industry:     document.getElementById('addIndustry').value.trim(),
        city:         document.getElementById('addCity').value.trim(),
    };

    if (!payload.company_name || !payload.industry || !payload.city) {
        errEl.textContent = 'Company, Industry, and City are required.';
        errEl.classList.remove('hidden');
        return;
    }

    const btn = document.getElementById('addSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
        const res  = await fetch(API_URL, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload) });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        closeAddModal();
        await loadData();
    } catch (err) {
        errEl.textContent = 'Error: ' + err.message;
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Entry';
    }
}

// ── Edit Entry ────────────────────────────────────────────────────────────────
function openEditModal(id) {
    const row = allRows.find(r => r.company_id == id);
    if (!row) return;

    document.getElementById('editError').classList.add('hidden');
    document.getElementById('editCompanyId').value    = row.company_id;
    document.getElementById('editMonth').value        = row.month;
    document.getElementById('editYear').value         = row.year;
    document.getElementById('editAccreditation').value = row.accreditation;
    document.getElementById('editEstType').value      = row.est_type;
    document.getElementById('editCompany').value      = row.company_name;
    document.getElementById('editIndustry').value     = row.industry;
    document.getElementById('editCity').value         = row.city;

    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('editModal').classList.add('hidden');
}

async function submitEditEntry() {
    const errEl = document.getElementById('editError');
    errEl.classList.add('hidden');

    const payload = {
        company_id:   parseInt(document.getElementById('editCompanyId').value),
        month:        document.getElementById('editMonth').value,
        year:         parseInt(document.getElementById('editYear').value),
        accreditation: document.getElementById('editAccreditation').value,
        est_type:     document.getElementById('editEstType').value,
        company_name: document.getElementById('editCompany').value.trim(),
        industry:     document.getElementById('editIndustry').value.trim(),
        city:         document.getElementById('editCity').value.trim(),
    };

    if (!payload.company_name || !payload.industry || !payload.city) {
        errEl.textContent = 'Company, Industry, and City are required.';
        errEl.classList.remove('hidden');
        return;
    }

    const btn = document.getElementById('editSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
        const res  = await fetch(API_URL, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload) });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        closeEditModal();
        await loadData();
    } catch (err) {
        errEl.textContent = 'Error: ' + err.message;
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Changes';
    }
}

// ── Delete Entry ──────────────────────────────────────────────────────────────
function openDeleteModal(id) {
    deletingId = id;
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('deleteModal').classList.add('hidden');
    deletingId = null;
}

async function confirmDelete() {
    if (!deletingId) return;
    try {
        const res  = await fetch(`${API_URL}?id=${deletingId}`, { method: 'DELETE' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);
        closeDeleteModal();
        await loadData();
    } catch (err) {
        alert('Delete failed: ' + err.message);
    }
}

// ── Backdrop click closes all modals ──────────────────────────────────────────
document.getElementById('modalBackdrop').addEventListener('click', () => {
    closeAddModal();
    closeEditModal();
    closeDeleteModal();
});

// ── Init ──────────────────────────────────────────────────────────────────────
// Populate year dropdown with current year first, then load
(function init() {
    const sel  = document.getElementById('filterYear');
    const year = new Date().getFullYear();
    sel.innerHTML = `<option value="${year}">${year}</option>`;
    loadData();
})();
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>