<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Government Internship Program (GIP)';
$pageHeading = 'Government Internship Program (GIP)';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<style>
    body.modal-open { overflow: hidden; }
</style>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen overflow-x-hidden">
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
                    <div>
                        <span id="card-participants-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="card-participants-m" class="text-xs text-blue-500 font-medium">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span id="card-participants-f" class="text-xs text-pink-500 font-medium">—F</span>
                        </div>
                    </div>
                    <div class="bg-teal-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Total Participants</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-inquired-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="card-inquired-m" class="text-xs text-blue-500 font-medium">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span id="card-inquired-f" class="text-xs text-pink-500 font-medium">—F</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Total Inquired</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-violet-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-referred-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="card-referred-m" class="text-xs text-blue-500 font-medium">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span id="card-referred-f" class="text-xs text-pink-500 font-medium">—F</span>
                        </div>
                    </div>
                    <div class="bg-violet-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Total Referred</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-amber-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-interviewed-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="card-interviewed-m" class="text-xs text-blue-500 font-medium">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span id="card-interviewed-f" class="text-xs text-pink-500 font-medium">—F</span>
                        </div>
                    </div>
                    <div class="bg-amber-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Total Interviewed</span>
            </div>
        </div>

        <!-- Row 2 Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-8">

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-peso-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="card-peso-m" class="text-xs text-blue-500 font-medium">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span id="card-peso-f" class="text-xs text-pink-500 font-medium">—F</span>
                        </div>
                    </div>
                    <div class="bg-orange-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">PESO-Accepted</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-private-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="card-private-m" class="text-xs text-blue-500 font-medium">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span id="card-private-f" class="text-xs text-pink-500 font-medium">—F</span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Privately-Accepted</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-notp-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="card-notp-m" class="text-xs text-blue-500 font-medium">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span id="card-notp-f" class="text-xs text-pink-500 font-medium">—F</span>
                        </div>
                    </div>
                    <div class="bg-red-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                </div>
                <span class="text-xs text-gray-500">Not Proceeded</span>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col gap-2 mb-4">
            <!-- Row 1: Year + Type filters -->
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Filter by year:</span>
                    <select id="yearFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"></select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Type:</span>
                    <select id="filterType" onchange="applyFilters()"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                        <option value="">All</option>
                        <option value="college">College</option>
                        <option value="shs">SHS</option>
                    </select>
                </div>
            </div>
            <!-- Row 2: Search always on its own row -->
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchSchool" placeholder="Search school..."
                        oninput="handleSearch()"
                        class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
                </div>
                <span id="loadingIndicator" class="text-xs text-gray-400 hidden shrink-0">Loading…</span>
            </div>
        </div>

        <!-- Main GIP Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Government Internship Program (GIP)</h2>
            </div>
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs" id="gipTable">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-36" rowspan="2">CONTRACT PERIOD</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium" rowspan="2">SCHOOL</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-28" rowspan="2">COLLEGE / SHS</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-24" rowspan="2">COURSE</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium" rowspan="2">OFFICE ASSIGNMENT</th>
                            <th class="px-3 py-3 text-center text-gray-500 font-medium w-20" rowspan="2">REQ. HRS.</th>
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
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-2 text-center text-gray-500 font-medium">F</th><th class="px-2 py-2 text-center font-semibold text-teal-600">T</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-2 text-center text-gray-500 font-medium">F</th><th class="px-2 py-2 text-center font-semibold text-blue-500">T</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-2 text-center text-gray-500 font-medium">F</th><th class="px-2 py-2 text-center font-semibold text-violet-500">T</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-2 text-center text-gray-500 font-medium">F</th><th class="px-2 py-2 text-center font-semibold text-amber-500">T</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-2 text-center text-gray-500 font-medium">F</th><th class="px-2 py-2 text-center font-semibold text-orange-500">T</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-2 text-center text-gray-500 font-medium">F</th><th class="px-2 py-2 text-center font-semibold text-green-500">T</th>
                            <th class="px-2 py-2 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-2 py-2 text-center text-gray-500 font-medium">F</th><th class="px-2 py-2 text-center font-semibold text-red-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="28" class="px-4 py-8 text-center text-gray-400 text-sm">Loading data…</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 px-4 md:px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
                <span class="text-sm text-gray-500" id="paginationInfo"></span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed" disabled>&#8249;</button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">&#8250;</button>
                </div>
            </div>
        </div>

    </div>
</main>

<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4 animate-modal">
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
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4 animate-modal">
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
const API_URL = '/backend/youth-employ/show-gip.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];
let currentPage  = 1;
let selectedYear = new Date().getFullYear();
let selectedType = '';
let searchQuery  = '';
let searchTimer  = null;
let deletingId   = null;
let savingId     = null;
let editSnapshot = {};

// ─── API ───────────────────────────────────────────────────────────────────
async function fetchData(year, type = '', search = '') {
    showLoading(true);
    try {
        const params = new URLSearchParams({ year, type, search });
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
        body:    JSON.stringify({ gip_id: id, ...payload }),
    });
    const json = await res.json();
    if (!json.success) throw new Error(json.error);
}

// ─── Row builder ───────────────────────────────────────────────────────────
function typeBadge(type) {
    const t = (type || '').toLowerCase();
    const cls = t === 'shs'
        ? 'bg-purple-100 text-purple-700'
        : 'bg-teal-100 text-teal-700';
    const label = t === 'shs' ? 'SHS' : 'College';
    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${cls}">${label}</span>`;
}

function mft(m, f, tc, bg) {
    return `<td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">${+m}</td>
            <td class="px-2 py-3 text-center text-gray-600">${+f}</td>
            <td class="px-2 py-3 text-center font-semibold ${tc} ${bg}">${+m + +f}</td>`;
}

function buildRow(r) {
    const id = r.gip_id;
    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50" data-id="${id}">
        <td class="px-3 py-3 text-gray-700 font-medium e-period">${r.contract_period}</td>
        <td class="px-3 py-3 text-gray-700 e-school">${r.school}</td>
        <td class="px-3 py-3 e-type">${typeBadge(r.college_or_shs)}</td>
        <td class="px-3 py-3 text-gray-600 e-course">${r.course}</td>
        <td class="px-3 py-3 text-gray-600 e-office">${r.office_assignment}</td>
        <td class="px-3 py-3 text-center text-gray-700 font-medium e-hours">${r.required_hours}</td>
        ${mft(r.part_m, r.part_f, 'text-teal-600',   'bg-teal-50')}
        ${mft(r.inq_m,  r.inq_f,  'text-blue-500',   'bg-blue-50')}
        ${mft(r.ref_m,  r.ref_f,  'text-violet-500', 'bg-violet-50')}
        ${mft(r.int_m,  r.int_f,  'text-amber-500',  'bg-amber-50')}
        ${mft(r.peso_m, r.peso_f, 'text-orange-500', 'bg-orange-50')}
        ${mft(r.priv_m, r.priv_f, 'text-green-500',  'bg-green-50')}
        ${mft(r.notp_m, r.notp_f, 'text-red-400',    'bg-red-50')}
        <td class="px-3 py-3 text-center border-l border-gray-100">
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
    const t = {pm:0,pf:0,im:0,if_:0,rm:0,rf:0,itm:0,itf:0,esm:0,esf:0,prm:0,prf:0,nm:0,nf:0};
    rows.forEach(r => {
        t.pm  += +r.part_m; t.pf  += +r.part_f;
        t.im  += +r.inq_m;  t.if_ += +r.inq_f;
        t.rm  += +r.ref_m;  t.rf  += +r.ref_f;
        t.itm += +r.int_m;  t.itf += +r.int_f;
        t.esm += +r.peso_m; t.esf += +r.peso_f;
        t.prm += +r.priv_m; t.prf += +r.priv_f;
        t.nm  += +r.notp_m; t.nf  += +r.notp_f;
    });
    function t3(m, f, tc, bc) {
        return `<td class="px-2 py-3 text-center text-gray-700 border-l border-gray-100">${m}</td>
                <td class="px-2 py-3 text-center text-gray-700">${f}</td>
                <td class="px-2 py-3 text-center font-bold ${tc} ${bc}">${m+f}</td>`;
    }
    return `
    <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-3 py-3 text-gray-800 font-bold" colspan="5">TOTAL</td>
        <td class="px-3 py-3 text-center font-bold text-gray-700">—</td>
        ${t3(t.pm,  t.pf,  'text-teal-600',   'bg-teal-100')}
        ${t3(t.im,  t.if_, 'text-blue-500',   'bg-blue-100')}
        ${t3(t.rm,  t.rf,  'text-violet-500', 'bg-violet-100')}
        ${t3(t.itm, t.itf, 'text-amber-500',  'bg-amber-100')}
        ${t3(t.esm, t.esf, 'text-orange-500', 'bg-orange-100')}
        ${t3(t.prm, t.prf, 'text-green-500',  'bg-green-100')}
        ${t3(t.nm,  t.nf,  'text-red-400',    'bg-red-100')}
        <td class="border-l border-gray-100"></td>
    </tr>`;
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
        tbody.innerHTML = `<tr><td colspan="28" class="px-4 py-8 text-center text-gray-400 text-sm">No data found.</td></tr>`;
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
    function set(key, t) {
        document.getElementById(`card-${key}-total`).textContent = t.m + t.f;
        document.getElementById(`card-${key}-m`).textContent     = t.m + 'M';
        document.getElementById(`card-${key}-f`).textContent     = t.f + 'F';
    }
    set('participants', totals.participants);
    set('inquired',     totals.inquired);
    set('referred',     totals.referred);
    set('interviewed',  totals.interviewed);
    set('peso',         totals.peso);
    set('private',      totals.private);
    set('notp',         totals.not_proceeded);
}

function populateYearFilter(years) {
    const sel = document.getElementById('yearFilter');
    sel.innerHTML = years.map(y =>
        `<option value="${y}" ${y == selectedYear ? 'selected' : ''}>${y}</option>`
    ).join('');
}

// ─── Load ──────────────────────────────────────────────────────────────────
async function load(year, type = '', search = '') {
    const data = await fetchData(year, type, search);
    if (!data) return;
    allRows = data.rows;
    currentPage = 1;
    updateCards(data.totals);
    populateYearFilter(data.years);
    renderTable();
}

// ─── Filters ───────────────────────────────────────────────────────────────
function applyFilters() {
    selectedType = document.getElementById('filterType').value;
    searchQuery  = document.getElementById('searchSchool').value.trim();
    load(selectedYear, selectedType, searchQuery);
}

function handleSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(applyFilters, 300);
}

// ─── Edit ──────────────────────────────────────────────────────────────────
function getRowEl(id) { return document.querySelector(`tr[data-id="${id}"]`); }

const EDIT_CLASSES = ['e-period','e-school','e-course','e-office','e-hours'];

function startEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    row.classList.add('bg-yellow-50');
    const snap = {};
    EDIT_CLASSES.forEach(cls => {
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
    EDIT_CLASSES.forEach(cls => {
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
        contract_period:   row.querySelector('.e-period').textContent.trim(),
        school:            row.querySelector('.e-school').textContent.trim(),
        course:            row.querySelector('.e-course').textContent.trim(),
        office_assignment: row.querySelector('.e-office').textContent.trim(),
        required_hours:    parseInt(row.querySelector('.e-hours').textContent.trim()) || 0,
    };

    try {
        await updateRecord(id, payload);
        EDIT_CLASSES.forEach(cls => {
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

        const record = allRows.find(r => r.gip_id == id);
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
        allRows = allRows.filter(r => r.gip_id != id);
        renderTable();
        // Rebuild totals from remaining rows and update cards
        const t = buildRawTotals();
        updateCards(t);
    } catch (e) {
        showError('Delete failed: ' + e.message);
    }
    deletingId = null;
}

function buildRawTotals() {
    const t = {
        participants: {m:0,f:0}, inquired: {m:0,f:0}, referred: {m:0,f:0},
        interviewed:  {m:0,f:0}, peso:     {m:0,f:0}, private:  {m:0,f:0},
        not_proceeded:{m:0,f:0}
    };
    allRows.forEach(r => {
        t.participants.m += +r.part_m; t.participants.f += +r.part_f;
        t.inquired.m     += +r.inq_m;  t.inquired.f     += +r.inq_f;
        t.referred.m     += +r.ref_m;  t.referred.f     += +r.ref_f;
        t.interviewed.m  += +r.int_m;  t.interviewed.f  += +r.int_f;
        t.peso.m         += +r.peso_m; t.peso.f         += +r.peso_f;
        t.private.m      += +r.priv_m; t.private.f      += +r.priv_f;
        t.not_proceeded.m+= +r.notp_m; t.not_proceeded.f+= +r.notp_f;
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

// ─── UI ────────────────────────────────────────────────────────────────────
function showLoading(state) { document.getElementById('loadingIndicator').classList.toggle('hidden', !state); }
function showError(msg) {
    const t = document.getElementById('errorToast');
    t.textContent = msg; t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 4000);
}

// ─── Year filter ───────────────────────────────────────────────────────────
document.getElementById('yearFilter').addEventListener('change', function () {
    selectedYear = +this.value;
    load(selectedYear, selectedType, searchQuery);
});

// ─── Init ──────────────────────────────────────────────────────────────────
load(selectedYear);
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>