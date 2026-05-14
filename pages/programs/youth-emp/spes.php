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

        <!-- Row 1 Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span id="card-registered" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg"><svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Total Registered</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="card-referred" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg"><svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Total Referred</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="card-placed" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-green-100 p-2 rounded-lg"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Total Placed</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="card-vacancies" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Job Vacancies</span>
            </div>
        </div>

        <!-- Row 2 Cards -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <span id="card-spes-baby" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-pink-100 p-2 rounded-lg"><svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">SPES Baby Beneficiaries</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span id="card-fourps" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg"><svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">4Ps Beneficiaries</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span id="card-pwd" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-cyan-100 p-2 rounded-lg"><svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">PWD Beneficiaries</span>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex items-center gap-3 mb-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select id="yearFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"></select>
            </div>
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchEmployer" placeholder="Search employer..."
                    oninput="handleSearch()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
            </div>
            <span id="loadingIndicator" class="text-xs text-gray-400 hidden">Loading…</span>
        </div>

        <!-- Main SPES Table -->
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
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                <span class="text-sm text-gray-500" id="paginationInfo"></span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed" disabled>&#8249;</button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">&#8250;</button>
                </div>
            </div>
        </div>

        <!-- Monthly LGU/Private Summary Table -->
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

<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

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

<script>
const API_URL       = '/api/spes-api.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];
let currentPage  = 1;
let selectedYear = new Date().getFullYear();
let searchQuery  = '';
let deletingId   = null;
let savingId     = null;
let editSnapshot = {};
let searchTimer  = null;

// ─── API ───────────────────────────────────────────────────────────────────
async function fetchData(year, search = '') {
    showLoading(true);
    try {
        const params = new URLSearchParams({ year, search });
        const res    = await fetch(`${API_URL}?${params}`);
        const json   = await res.json();
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
        <td class="px-3 py-2 text-gray-700 editable-employer">${r.employer}</td>
        <td class="px-3 py-2 text-gray-600 editable-start">${fmt(r.start_of_contract)}</td>
        <td class="px-3 py-2 text-gray-600 editable-end">${fmt(r.end_of_contract)}</td>
        <td class="px-3 py-2 text-center text-gray-700 font-medium editable-days">${r.days}</td>
        ${mft(r.reg_m,      r.reg_f,      'text-teal-600',   'bg-teal-50')}
        ${mft(r.ref_m,      r.ref_f,      'text-blue-500',   'bg-blue-50')}
        ${mft(r.placed_m,   r.placed_f,   'text-green-500',  'bg-green-50')}
        ${mft(r.vac_m,      r.vac_f,      'text-orange-400', 'bg-orange-50')}
        ${mft(r.spes_baby_m,r.spes_baby_f,'text-pink-500',   'bg-pink-50')}
        ${mft(r.fourps_m,   r.fourps_f,   'text-purple-500', 'bg-purple-50')}
        ${mft(r.pwd_m,      r.pwd_f,      'text-cyan-500',   'bg-cyan-50')}
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

function buildTotalRow(rows) {
    const t = { reg_m:0,reg_f:0,ref_m:0,ref_f:0,placed_m:0,placed_f:0,
                vac_m:0,vac_f:0,baby_m:0,baby_f:0,fps_m:0,fps_f:0,pwd_m:0,pwd_f:0 };
    rows.forEach(r => {
        t.reg_m    += +r.reg_m;    t.reg_f    += +r.reg_f;
        t.ref_m    += +r.ref_m;    t.ref_f    += +r.ref_f;
        t.placed_m += +r.placed_m; t.placed_f += +r.placed_f;
        t.vac_m    += +r.vac_m;    t.vac_f    += +r.vac_f;
        t.baby_m   += +r.spes_baby_m; t.baby_f += +r.spes_baby_f;
        t.fps_m    += +r.fourps_m; t.fps_f    += +r.fourps_f;
        t.pwd_m    += +r.pwd_m;    t.pwd_f    += +r.pwd_f;
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

// ─── Load ──────────────────────────────────────────────────────────────────
async function load(year, search = '') {
    const data = await fetchData(year, search);
    if (!data) return;
    allRows = data.rows;
    currentPage = 1;
    updateCards(data.totals);
    populateYearFilter(data.years);
    renderTable();
    document.getElementById('summaryBody').innerHTML = buildSummaryBody(data.summary);
}

// ─── Search (debounced — sends to API) ─────────────────────────────────────
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
    // Editable: month, employer, start, end, days (first 5 cells)
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

// ─── Modals ────────────────────────────────────────────────────────────────
function showModal(id) { document.getElementById('modalBackdrop').classList.remove('hidden'); document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById('modalBackdrop').classList.add('hidden'); document.getElementById(id).classList.add('hidden'); }
function closeDeleteModal() { closeModal('deleteModal'); deletingId = null; }
function closeSaveModal()   { closeModal('saveModal');   savingId   = null; }
document.addEventListener('click', e => { if (e.target.id === 'modalBackdrop') { closeDeleteModal(); closeSaveModal(); } });

// ─── UI helpers ────────────────────────────────────────────────────────────
function showLoading(state) { document.getElementById('loadingIndicator').classList.toggle('hidden', !state); }
function showError(msg) {
    const t = document.getElementById('errorToast');
    t.textContent = msg; t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 4000);
}

// ─── Year filter ───────────────────────────────────────────────────────────
document.getElementById('yearFilter').addEventListener('change', function () {
    selectedYear = +this.value;
    load(selectedYear, searchQuery);
});

// ─── Init ──────────────────────────────────────────────────────────────────
load(selectedYear);
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>