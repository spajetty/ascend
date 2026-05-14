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

    <div class="px-6 md:px-8 pt-6">
        <a href="/pages/programs/employment-facilitation.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employment Facilitation Section
        </a>
    </div>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-vacancies">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Job Vacancies</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-employers">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Employers</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-interviewed">—</span>
                    <div class="bg-cyan-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Interviewed Applicants</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-qualified">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Qualified Applicants</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-placed">—</span>
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
                <select id="yearFilter"
                        onchange="loadData()"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
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
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="jobFairTbody">
                        <tr>
                            <td colspan="20" class="text-center py-8 text-gray-400 text-sm">Loading…</td>
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

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40 modal-backdrop"></div>

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
// ─── State ───────────────────────────────────────────────────────────────────
const API_URL    = '/api/job-fair-api.php';   // adjust path to match your project layout
const ROWS_PER_PAGE = 9;

let allRows      = [];   // raw rows from API
let filteredRows = [];   // after search filter
let currentPage  = 1;
let deletingId   = null;
let savingId     = null;
const editingData = {};  // snapshot before edit

// ─── Bootstrap ───────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => loadData());

async function loadData() {
    const year = document.getElementById('yearFilter').value || new Date().getFullYear();
    const tbody = document.getElementById('jobFairTbody');
    tbody.innerHTML = '<tr><td colspan="20" class="text-center py-8 text-gray-400 text-sm">Loading…</td></tr>';

    try {
        const res  = await fetch(`${API_URL}?year=${year}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        const { rows, totals, years } = json.data;

        // Populate year dropdown (keep current selection)
        populateYears(years, parseInt(year));

        // Update summary cards
        document.getElementById('card-vacancies').textContent  = totals.job_vacancies;
        document.getElementById('card-employers').textContent   = totals.employers;
        document.getElementById('card-interviewed').textContent = totals.interviewed;
        document.getElementById('card-qualified').textContent   = totals.qualified;
        document.getElementById('card-placed').textContent      = totals.placed;

        allRows      = rows;
        filteredRows = [...allRows];
        currentPage  = 1;
        renderPage();

    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="20" class="text-center py-8 text-red-400 text-sm">Error: ${err.message}</td></tr>`;
    }
}

function populateYears(years, selectedYear) {
    const sel = document.getElementById('yearFilter');
    const current = sel.value;
    sel.innerHTML = '';
    years.forEach(y => {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        if (y === selectedYear || (!current && y === new Date().getFullYear())) {
            opt.selected = true;
        }
        sel.appendChild(opt);
    });
}

// ─── Rendering ───────────────────────────────────────────────────────────────
function renderPage() {
    const tbody = document.getElementById('jobFairTbody');
    const total = filteredRows.length;
    const totalPages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    currentPage = Math.min(currentPage, totalPages);
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const pageRows = filteredRows.slice(start, start + ROWS_PER_PAGE);

    tbody.innerHTML = '';

    if (pageRows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="20" class="text-center py-8 text-gray-400 text-sm">No entries found.</td></tr>';
    } else {
        pageRows.forEach(r => tbody.appendChild(buildRow(r)));
    }

    // Pagination info
    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries found' : `Showing ${start + 1}–${Math.min(start + ROWS_PER_PAGE, total)} of ${total} entries`;

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

function buildRow(r) {
    const id = r.jobfair_id;
    const tr = document.createElement('tr');
    tr.className = 'border-b border-gray-50 hover:bg-gray-50';
    tr.dataset.id       = id;
    tr.dataset.employer = (r.company_name || '').toLowerCase();

    // Helper: styled td
    const td = (content, cls = '') => `<td class="px-3 py-2 text-center text-gray-600 ${cls}">${content}</td>`;
    const tdBold = (content, color, bg, borderL = false) =>
        `<td class="px-3 py-2 text-center font-semibold ${color} ${bg} ${borderL ? 'border-l border-gray-100' : ''}">${content}</td>`;

    tr.innerHTML = `
        <td class="px-4 py-2 text-gray-700 font-medium" data-field="month">${r.month} ${r.year}</td>
        <td class="px-4 py-2 text-gray-700" data-field="company_name">${escHtml(r.company_name)}</td>

        <!-- Job Vacancies -->
        <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100" data-field="vacancy_male">${r.vacancy_male}</td>
        <td class="px-3 py-2 text-center text-blue-500 font-semibold" data-field="vacancy_female">${r.vacancy_female}</td>
        <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">${r.vacancy_total}</td>

        <!-- Interviewed -->
        ${td(r.int_m,    'border-l border-gray-100')}
        ${td(r.int_f)}
        ${tdBold(r.int_total,    'text-cyan-500',   'bg-cyan-50')}

        <!-- Qualified -->
        ${td(r.qual_m,   'border-l border-gray-100')}
        ${td(r.qual_f)}
        ${tdBold(r.qual_total,   'text-green-500',  'bg-green-50')}

        <!-- Not Qualified -->
        ${td(r.nqual_m,  'border-l border-gray-100')}
        ${td(r.nqual_f)}
        ${tdBold(r.nqual_total,  'text-red-400',    'bg-red-50')}

        <!-- Placed / HOTS -->
        ${td(r.placed_m, 'border-l border-gray-100')}
        ${td(r.placed_f)}
        ${tdBold(r.placed_total, 'text-orange-400', 'bg-orange-50')}

        <!-- For Further Interview -->
        ${td(r.ffi_m,    'border-l border-gray-100')}
        ${td(r.ffi_f)}
        ${tdBold(r.ffi_total,    'text-purple-400', 'bg-purple-50')}

        <!-- Actions -->
        <td class="px-3 py-2 text-center border-l border-gray-100">
            <div class="flex items-center justify-center gap-2 action-buttons">
                <button onclick="toggleEditMode(${id})" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="deleteRow(${id})" class="text-red-400 hover:text-red-600 delete-btn" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <button onclick="saveRow(${id})" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button onclick="cancelEdit(${id})" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </td>
    `;
    return tr;
}

function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── Filter & Pagination ─────────────────────────────────────────────────────
function filterTable() {
    const q = document.getElementById('searchCompany').value.toLowerCase().trim();
    filteredRows = q ? allRows.filter(r => (r.company_name || '').toLowerCase().includes(q)) : [...allRows];
    currentPage = 1;
    renderPage();
}

function changePage(dir) {
    const totalPages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderPage();
}

// ─── Edit ────────────────────────────────────────────────────────────────────
// Only month (text), company_name, vacancy_male, vacancy_female are editable.
// Beneficiary-derived counts (interviewed, qualified, etc.) are read-only.
function getRow(id) {
    return document.querySelector(`#jobFairTbody tr[data-id="${id}"]`);
}

function toggleEditMode(id) {
    const row = getRow(id);
    if (!row) return;
    if (row.classList.contains('editing')) { cancelEdit(id); return; }

    row.classList.add('editing', 'bg-yellow-50');

    // Make month, company_name, vacancy_male, vacancy_female editable
    const editableFields = ['month', 'company_name', 'vacancy_male', 'vacancy_female'];
    editableFields.forEach(field => {
        const cell = row.querySelector(`[data-field="${field}"]`);
        if (!cell) return;
        editingData[`${id}_${field}`] = cell.textContent.trim();
        cell.contentEditable = 'true';
        cell.classList.add('border', 'border-yellow-300', 'bg-white', 'rounded');
    });

    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.add('hidden');
    ab.querySelector('.delete-btn').classList.add('hidden');
    ab.querySelector('.save-btn').classList.remove('hidden');
    ab.querySelector('.cancel-btn').classList.remove('hidden');
}

function cancelEdit(id) {
    const row = getRow(id);
    if (!row) return;
    const editableFields = ['month', 'company_name', 'vacancy_male', 'vacancy_female'];
    editableFields.forEach(field => {
        const cell = row.querySelector(`[data-field="${field}"]`);
        if (!cell) return;
        cell.textContent = editingData[`${id}_${field}`] || '';
        cell.contentEditable = 'false';
        cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'rounded');
    });
    row.classList.remove('editing', 'bg-yellow-50');
    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.remove('hidden');
    ab.querySelector('.delete-btn').classList.remove('hidden');
    ab.querySelector('.save-btn').classList.add('hidden');
    ab.querySelector('.cancel-btn').classList.add('hidden');
}

function saveRow(id) {
    savingId = id;
    openModal('saveModal');
}

async function confirmSave() {
    const id  = savingId;
    const row = getRow(id);
    if (!row) { closeSaveModal(); return; }

    // Parse month/year from the month cell (e.g. "January 2026")
    const monthCell   = row.querySelector('[data-field="month"]');
    const monthText   = monthCell ? monthCell.textContent.trim() : '';
    const parts       = monthText.split(' ');
    const monthVal    = parts[0] || '';
    const yearVal     = parseInt(parts[1]) || new Date().getFullYear();

    const companyCell = row.querySelector('[data-field="company_name"]');
    const vacMCell    = row.querySelector('[data-field="vacancy_male"]');
    const vacFCell    = row.querySelector('[data-field="vacancy_female"]');

    const payload = {
        jobfair_id:     id,
        month:          monthVal,
        year:           yearVal,
        company_name:   companyCell ? companyCell.textContent.trim() : '',
        vacancy_male:   parseInt(vacMCell  ? vacMCell.textContent.trim()  : 0) || 0,
        vacancy_female: parseInt(vacFCell  ? vacFCell.textContent.trim()  : 0) || 0,
    };

    try {
        const res  = await fetch(API_URL, {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Exit edit mode
        cancelEdit(id);
        closeSaveModal();

        // Flash green
        row.style.transition = 'background-color 0.3s';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);

        // Sync allRows cache
        const idx = allRows.findIndex(r => r.jobfair_id == id);
        if (idx !== -1) {
            allRows[idx].month          = monthVal;
            allRows[idx].year           = yearVal;
            allRows[idx].company_name   = payload.company_name;
            allRows[idx].vacancy_male   = payload.vacancy_male;
            allRows[idx].vacancy_female = payload.vacancy_female;
            allRows[idx].vacancy_total  = payload.vacancy_male + payload.vacancy_female;
        }

    } catch (err) {
        alert('Save failed: ' + err.message);
        closeSaveModal();
    }
}

// ─── Delete ──────────────────────────────────────────────────────────────────
function deleteRow(id) {
    deletingId = id;
    openModal('deleteModal');
}

async function confirmDelete() {
    try {
        const res  = await fetch(`${API_URL}?id=${deletingId}`, { method: 'DELETE' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Remove from allRows + re-render
        allRows      = allRows.filter(r => r.jobfair_id != deletingId);
        filteredRows = filteredRows.filter(r => r.jobfair_id != deletingId);

        const row = getRow(deletingId);
        if (row) {
            row.style.transition = 'opacity 0.3s';
            row.style.opacity = '0';
            setTimeout(() => { renderPage(); }, 300);
        } else {
            renderPage();
        }

        closeDeleteModal();

        // Refresh cards
        loadData();

    } catch (err) {
        alert('Delete failed: ' + err.message);
        closeDeleteModal();
    }
}

// ─── Modals ──────────────────────────────────────────────────────────────────
function openModal(id) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById(id).classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('deleteModal').classList.add('hidden');
    deletingId = null;
}
function closeSaveModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('saveModal').classList.add('hidden');
    savingId = null;
}

document.addEventListener('click', e => {
    if (e.target === document.getElementById('modalBackdrop')) {
        closeDeleteModal(); closeSaveModal();
    }
});
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>