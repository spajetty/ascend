<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Job Matching and Referral';
$pageHeading = 'Job Matching and Referral';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen w-0 md:w-auto">
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

    <div class="px-4 md:px-8 py-6 space-y-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="card-registered" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Registered</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="card-referred" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Referred</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="card-interviewed" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Interviewed</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span id="card-placed" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Hired / Placed</span>
            </div>

        </div>

        <!-- Filter -->
        <div class="flex flex-col gap-2 mb-4">
            <!-- Row 1: Year filter -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 whitespace-nowrap">Filter by year:</span>
                <select id="yearFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                    <!-- populated by JS -->
                </select>
            </div>
            <!-- Loading indicator -->
            <span id="loadingIndicator" class="text-xs text-gray-400 hidden">Loading…</span>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Job Matching & Referral</h2>
            </div>
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs min-w-[900px]">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                            <th class="px-2 py-2 text-center text-gray-400 font-semibold tracking-wide border-l border-gray-100" rowspan="2">ACTIONS</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-teal-600">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="emptyRow">
                            <td colspan="22" class="px-4 py-8 text-center text-gray-400 text-sm">Loading data…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-wrap items-center justify-between gap-2 px-4 md:px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
                <span class="text-sm text-gray-500" id="paginationInfo"></span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>&lsaquo;</button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>&rsaquo;</button>
                </div>
            </div>
        </div>

    </div>
</main>

<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- Delete Modal -->
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

<!-- Save Modal -->
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

<!-- Error Toast -->
<div id="errorToast" class="fixed bottom-6 right-6 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm hidden z-50"></div>

<script>
// ─── Config ────────────────────────────────────────────────────────────────
const API_URL = '/backend/emp-facilitation/show-job-match.php';
const ROWS_PER_PAGE = 9;

// ─── State ─────────────────────────────────────────────────────────────────
let allRows      = [];   // raw data from API
let currentPage  = 1;
let selectedYear = new Date().getFullYear();
let deletingId   = null;
let savingId     = null;
let editSnapshot = {};   // { jobmatch_id: { originalCells } }

// ─── Field mapping ─────────────────────────────────────────────────────────
// Maps DB columns → cell indices (0-based, skipping month col and actions col)
const FIELDS = [
    'reg_m','reg_f',
    'ref_m','ref_f',
    'int_m','int_f',
    'qual_m','qual_f',
    'nqual_m','nqual_f',
    'placed_m','placed_f',
    'ffi_m','ffi_f',
];

// ─── API calls ─────────────────────────────────────────────────────────────
async function fetchData(year) {
    showLoading(true);
    try {
        const res  = await fetch(`${API_URL}?year=${year}`);
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
        body:    JSON.stringify({ jobmatch_id: id, ...payload }),
    });
    const json = await res.json();
    if (!json.success) throw new Error(json.error);
}

// ─── Render ────────────────────────────────────────────────────────────────
function buildTotalRow(rows) {
    const t = { reg_m:0,reg_f:0,ref_m:0,ref_f:0,int_m:0,int_f:0,
                qual_m:0,qual_f:0,nqual_m:0,nqual_f:0,
                placed_m:0,placed_f:0,ffi_m:0,ffi_f:0 };
    rows.forEach(r => {
        Object.keys(t).forEach(k => { t[k] += Number(r[k] || 0); });
    });
    return t;
}

function cell(val, cls = '') {
    return `<td class="px-3 py-2 text-center text-gray-600 ${cls}">${val}</td>`;
}

function total3(m, f, textCls, bgCls) {
    return cell(m, 'border-l border-gray-100') +
           cell(f) +
           `<td class="px-3 py-2 text-center font-semibold ${textCls} ${bgCls}">${m + f}</td>`;
}

function buildRow(r, idx) {
    const id = r.jobmatch_id;
    const nm = { reg_m: +r.reg_m, reg_f: +r.reg_f, ref_m: +r.ref_m, ref_f: +r.ref_f,
                 int_m: +r.int_m, int_f: +r.int_f, qual_m: +r.qual_m, qual_f: +r.qual_f,
                 nqual_m: +r.nqual_m, nqual_f: +r.nqual_f,
                 placed_m: +r.placed_m, placed_f: +r.placed_f,
                 ffi_m: +r.ffi_m, ffi_f: +r.ffi_f };

    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50 data-row" data-id="${id}" data-idx="${idx}">
        <td class="px-4 py-2 text-gray-700 font-medium">${r.month}</td>
        ${total3(nm.reg_m,   nm.reg_f,   'text-teal-600',   'bg-teal-50')}
        ${total3(nm.ref_m,   nm.ref_f,   'text-blue-500',   'bg-blue-50')}
        ${total3(nm.int_m,   nm.int_f,   'text-cyan-500',   'bg-cyan-50')}
        ${total3(nm.qual_m,  nm.qual_f,  'text-green-500',  'bg-green-50')}
        ${total3(nm.nqual_m, nm.nqual_f, 'text-red-400',    'bg-red-50')}
        ${total3(nm.placed_m,nm.placed_f,'text-orange-400', 'bg-orange-50')}
        ${total3(nm.ffi_m,   nm.ffi_f,   'text-purple-400', 'bg-purple-50')}
        <td class="px-3 py-2 text-center border-l border-gray-100">
            <div class="flex items-center justify-center gap-2">
                <button onclick="startEdit(${id})" class="edit-btn text-yellow-500 hover:text-yellow-600" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="promptDelete(${id})" class="delete-btn text-red-400 hover:text-red-600" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <button onclick="promptSave(${id})" class="save-btn hidden text-green-500 hover:text-green-600" title="Save">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button onclick="cancelEdit(${id})" class="cancel-btn hidden text-gray-400 hover:text-gray-600" title="Cancel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </td>
    </tr>`;
}

function buildTotalHTMLRow(t) {
    function t3(m, f, tc, bc) {
        return `<td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">${m}</td>
                <td class="px-3 py-2 text-center text-gray-700">${f}</td>
                <td class="px-3 py-2 text-center font-bold ${tc} ${bc}">${m + f}</td>`;
    }
    return `
    <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
        <td class="px-4 py-2 text-gray-800 font-bold">TOTAL</td>
        ${t3(t.reg_m,   t.reg_f,   'text-teal-600',   'bg-teal-100')}
        ${t3(t.ref_m,   t.ref_f,   'text-blue-500',   'bg-blue-100')}
        ${t3(t.int_m,   t.int_f,   'text-cyan-500',   'bg-cyan-100')}
        ${t3(t.qual_m,  t.qual_f,  'text-green-500',  'bg-green-100')}
        ${t3(t.nqual_m, t.nqual_f, 'text-red-400',    'bg-red-100')}
        ${t3(t.placed_m,t.placed_f,'text-orange-400', 'bg-orange-100')}
        ${t3(t.ffi_m,   t.ffi_f,   'text-purple-400', 'bg-purple-100')}
        <td class="border-l border-gray-100"></td>
    </tr>`;
}

function renderTable() {
    const tbody   = document.getElementById('tableBody');
    const total   = allRows.length;
    const totalPg = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    currentPage   = Math.min(currentPage, totalPg);
    const start   = (currentPage - 1) * ROWS_PER_PAGE;
    const end     = Math.min(start + ROWS_PER_PAGE, total);
    const pageRows = allRows.slice(start, end);

    if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="22" class="px-4 py-8 text-center text-gray-400 text-sm">No data found for this year.</td></tr>`;
    } else {
        tbody.innerHTML = pageRows.map((r, i) => buildRow(r, start + i)).join('') +
                          buildTotalHTMLRow(buildTotalRow(allRows));
    }

    // Pagination controls
    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries' : `Showing ${start + 1}–${end} of ${total} entries`;
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
    document.getElementById('card-registered').textContent  = totals.registered;
    document.getElementById('card-referred').textContent    = totals.referred;
    document.getElementById('card-interviewed').textContent = totals.interviewed;
    document.getElementById('card-placed').textContent      = totals.placed;
}

function populateYearFilter(years) {
    const sel = document.getElementById('yearFilter');
    sel.innerHTML = years.map(y =>
        `<option value="${y}" ${y == selectedYear ? 'selected' : ''}>${y}</option>`
    ).join('');
}

// ─── Load ──────────────────────────────────────────────────────────────────
async function load(year) {
    const data = await fetchData(year);
    if (!data) return;
    allRows = data.rows;
    currentPage = 1;
    updateCards(data.totals);
    populateYearFilter(data.years);
    renderTable();
}

// ─── Edit ──────────────────────────────────────────────────────────────────
function getRowEl(id) {
    return document.querySelector(`tr[data-id="${id}"]`);
}

function startEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    row.classList.add('editing', 'bg-yellow-50');

    // Snapshot + make M/F cells editable (skip Total cols at indices 2,5,8,11,14,17,20)
    const cells = [...row.querySelectorAll('td')].slice(1, -1); // skip month + actions
    const snap  = [];
    cells.forEach((cell, idx) => {
        snap.push(cell.textContent.trim());
        // Only M and F cells (not totals) — totals are at every 3rd starting idx 2
        if ((idx % 3) !== 2) {
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
    // Re-sum M+F into each Total cell while editing
    const cells = [...row.querySelectorAll('td')].slice(1, -1);
    for (let i = 0; i < cells.length; i += 3) {
        const m = parseInt(cells[i]?.textContent) || 0;
        const f = parseInt(cells[i + 1]?.textContent) || 0;
        if (cells[i + 2]) cells[i + 2].textContent = m + f;
    }
}

function cancelEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    const snap  = editSnapshot[id] || [];
    const cells = [...row.querySelectorAll('td')].slice(1, -1);
    cells.forEach((cell, idx) => {
        cell.contentEditable = 'false';
        cell.textContent     = snap[idx] ?? cell.textContent;
        cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
    });
    row.classList.remove('editing', 'bg-yellow-50');
    row.querySelector('.edit-btn').classList.remove('hidden');
    row.querySelector('.delete-btn').classList.remove('hidden');
    row.querySelector('.save-btn').classList.add('hidden');
    row.querySelector('.cancel-btn').classList.add('hidden');
    delete editSnapshot[id];
}

function promptSave(id) {
    savingId = id;
    showModal('saveModal');
}

async function confirmSave() {
    const id  = savingId;
    const row = getRowEl(id);
    closeModal('saveModal');
    if (!row || !id) return;

    const cells = [...row.querySelectorAll('td')].slice(1, -1);
    // Build payload from editable cells: FIELDS order matches M,F pairs
    const payload = {};
    let fi = 0;
    cells.forEach((cell, idx) => {
        if ((idx % 3) !== 2) { // skip total cols
            payload[FIELDS[fi++]] = parseInt(cell.textContent.trim()) || 0;
        }
    });

    try {
        await updateRecord(id, payload);
        // Commit: turn off editing styles
        cells.forEach(cell => {
            cell.contentEditable = 'false';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
        });
        row.classList.remove('editing', 'bg-yellow-50');
        row.querySelector('.edit-btn').classList.remove('hidden');
        row.querySelector('.delete-btn').classList.remove('hidden');
        row.querySelector('.save-btn').classList.add('hidden');
        row.querySelector('.cancel-btn').classList.add('hidden');
        delete editSnapshot[id];

        // Flash green
        row.style.transition = 'background-color 0.3s';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);

        // Update local data & re-render cards
        const record = allRows.find(r => r.jobmatch_id == id);
        if (record) Object.assign(record, payload);
        updateCards(buildSummaryTotals());

    } catch (e) {
        showError('Save failed: ' + e.message);
    }
    savingId = null;
}

function buildSummaryTotals() {
    let reg = 0, ref = 0, int_ = 0, placed = 0;
    allRows.forEach(r => {
        reg    += +r.reg_m    + +r.reg_f;
        ref    += +r.ref_m    + +r.ref_f;
        int_   += +r.int_m    + +r.int_f;
        placed += +r.placed_m + +r.placed_f;
    });
    return { registered: reg, referred: ref, interviewed: int_, placed };
}

// ─── Delete ────────────────────────────────────────────────────────────────
function promptDelete(id) {
    deletingId = id;
    showModal('deleteModal');
}

async function confirmDelete() {
    const id = deletingId;
    closeModal('deleteModal');
    if (!id) return;
    try {
        await deleteRecord(id);
        allRows = allRows.filter(r => r.jobmatch_id != id);
        renderTable();
        updateCards(buildSummaryTotals());
    } catch (e) {
        showError('Delete failed: ' + e.message);
    }
    deletingId = null;
}

// ─── Pagination ─────────────────────────────────────────────────────────────
function changePage(dir) {
    const totalPg = Math.max(1, Math.ceil(allRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPg));
    renderTable();
}

// ─── Modal helpers ─────────────────────────────────────────────────────────
function showModal(id) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById(id).classList.add('hidden');
}
function closeDeleteModal() { closeModal('deleteModal'); deletingId = null; }
function closeSaveModal()   { closeModal('saveModal');   savingId   = null; }

document.addEventListener('click', e => {
    if (e.target.id === 'modalBackdrop') { closeDeleteModal(); closeSaveModal(); }
});

// ─── UI helpers ────────────────────────────────────────────────────────────
function showLoading(state) {
    document.getElementById('loadingIndicator').classList.toggle('hidden', !state);
}
function showError(msg) {
    const toast = document.getElementById('errorToast');
    toast.textContent = msg;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 4000);
}

// ─── Year filter ───────────────────────────────────────────────────────────
document.getElementById('yearFilter').addEventListener('change', function () {
    selectedYear = +this.value;
    load(selectedYear);
});

// ─── Init ──────────────────────────────────────────────────────────────────
load(selectedYear);
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>