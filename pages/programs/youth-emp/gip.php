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
    @keyframes modalIn { from { opacity: 0; transform: scale(.95) translateY(8px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .animate-modal { animation: modalIn .18s ease both; }
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
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-8">
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
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-lgu-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5"><span class="text-xs text-gray-500">LGU participants</span></div>
                    </div>
                    <div class="bg-blue-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-emerald-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-dole-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5"><span class="text-xs text-gray-500">DOLE participants</span></div>
                    </div>
                    <div class="bg-emerald-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="card-records-total" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                        <div class="flex items-center gap-2 mt-0.5"><span class="text-xs text-gray-500">Grouped entries</span></div>
                    </div>
                    <div class="bg-pink-100 p-2.5 rounded-xl"><svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c1.933 0 3.5 1.567 3.5 3.5S13.933 11.5 12 11.5 8.5 9.933 8.5 8 10.067 4.5 12 4.5zM5 19.5a7 7 0 0114 0"/></svg></div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2 mb-4">
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Year:</span>
                    <select id="yearFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"></select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Month:</span>
                    <select id="monthFilter" onchange="applyFilters()" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                        <option value="">All</option>
                        <option value="1">January</option><option value="2">February</option>
                        <option value="3">March</option><option value="4">April</option>
                        <option value="5">May</option><option value="6">June</option>
                        <option value="7">July</option><option value="8">August</option>
                        <option value="9">September</option><option value="10">October</option>
                        <option value="11">November</option><option value="12">December</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchSchool" placeholder="Search school..." oninput="handleSearch()" class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
                </div>
                <span id="loadingIndicator" class="text-xs text-gray-400 hidden shrink-0">Loading…</span>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-sky-50 to-blue-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-sky-100 text-sky-700 text-xs font-bold tracking-wide">LGU</span>
                    <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Government Internship Program - LGU</h2>
                </div>
                <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                    <table class="w-full text-xs min-w-[820px]" id="gipLguTable">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50 text-gray-500 font-medium">
                                <th class="text-left px-4 py-2.5 w-32" rowspan="2">MONTH REPORTED</th>
                                <th class="text-left px-4 py-2.5" rowspan="2">OFFICE ASSIGNMENT</th>
                                <th class="text-left px-4 py-2.5" rowspan="2">SCHOOL</th>
                                <th colspan="3" class="px-2 py-1.5 text-center text-teal-600 font-semibold border-l border-gray-200 bg-teal-50/50">PARTICIPANTS</th>
                                <th class="px-2 py-1.5 text-center text-gray-400 font-semibold border-l border-gray-200" rowspan="2">ACTIONS</th>
                            </tr>
                            <tr class="border-b border-gray-200 bg-gray-50 text-gray-500 font-medium">
                                <th class="px-1.5 py-1.5 text-center border-l border-gray-200 w-8">M</th>
                                <th class="px-1.5 py-1.5 text-center w-8">F</th>
                                <th class="px-1.5 py-1.5 text-center font-semibold text-teal-600 w-8">T</th>
                            </tr>
                        </thead>
                        <tbody id="gipLguBody"><tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">Loading data…</td></tr></tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-2 px-4 md:px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
                    <span class="text-sm text-gray-500" id="gipLguInfo"></span>
                    <div class="flex items-center gap-1">
                        <button onclick="changePage('lgu', -1)" id="prevLguBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed" disabled>&#8249;</button>
                        <div id="pageNumbersLgu" class="flex items-center gap-1"></div>
                        <button onclick="changePage('lgu', 1)" id="nextLguBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">&#8250;</button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 text-xs font-bold tracking-wide">DOLE</span>
                    <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Government Internship Program - DOLE</h2>
                </div>
                <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                    <table class="w-full text-xs min-w-[820px]" id="gipDoleTable">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50 text-gray-500 font-medium">
                                <th class="text-left px-4 py-2.5 w-32" rowspan="2">MONTH REPORTED</th>
                                <th class="text-left px-4 py-2.5" rowspan="2">OFFICE ASSIGNMENT</th>
                                <th class="text-left px-4 py-2.5" rowspan="2">SCHOOL</th>
                                <th colspan="3" class="px-2 py-1.5 text-center text-teal-600 font-semibold border-l border-gray-200 bg-teal-50/50">PARTICIPANTS</th>
                                <th class="px-2 py-1.5 text-center text-gray-400 font-semibold border-l border-gray-200" rowspan="2">ACTIONS</th>
                            </tr>
                            <tr class="border-b border-gray-200 bg-gray-50 text-gray-500 font-medium">
                                <th class="px-1.5 py-1.5 text-center border-l border-gray-200 w-8">M</th>
                                <th class="px-1.5 py-1.5 text-center w-8">F</th>
                                <th class="px-1.5 py-1.5 text-center font-semibold text-teal-600 w-8">T</th>
                            </tr>
                        </thead>
                        <tbody id="gipDoleBody"><tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">Loading data…</td></tr></tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-2 px-4 md:px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
                    <span class="text-sm text-gray-500" id="gipDoleInfo"></span>
                    <div class="flex items-center gap-1">
                        <button onclick="changePage('dole', -1)" id="prevDoleBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed" disabled>&#8249;</button>
                        <div id="pageNumbersDole" class="flex items-center gap-1"></div>
                        <button onclick="changePage('dole', 1)" id="nextDoleBtn" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">&#8250;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

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

<div id="saveModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4 animate-modal">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-green-100 p-3 rounded-lg"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <h3 class="text-lg font-bold text-gray-900">Save Participant Counts</h3>
        </div>
        <p class="text-gray-600 mb-6">Do you want to save the participant count changes for this grouped entry?</p>
        <div class="flex gap-3">
            <button onclick="closeSaveModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmSave()" class="flex-1 px-4 py-2 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600">Save</button>
        </div>
    </div>
</div>

<div id="errorToast" class="fixed bottom-6 right-6 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm hidden z-50"></div>

<script>
const API_URL = '/backend/youth-employ/gip/show-gip.php';
const ROWS_PER_PAGE = 10;
const MONTH_NAMES = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

let allRows = [];
let lguRows = [];
let doleRows = [];
let currentPages = { lgu: 1, dole: 1 };
let selectedYear = new Date().getFullYear();
let selectedMonth = '';
let searchQuery = '';
let searchTimer = null;
let deletingId = null;
let savingId = null;
let editSnapshot = {};
const PARTICIPANT_OVERRIDE_KEY = 'ascend_gip_participant_overrides';

let participantOverrides = {};

try {
    participantOverrides = JSON.parse(localStorage.getItem(PARTICIPANT_OVERRIDE_KEY) || '{}') || {};
} catch {
    participantOverrides = {};
}

async function fetchData(year, month = '', search = '') {
    showLoading(true);
    try {
        const params = new URLSearchParams({ year });
        if (month) params.set('month', month);
        if (search) params.set('search', search);
        const res = await fetch(`${API_URL}?${params}`);
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

function persistParticipantOverrides() {
    localStorage.setItem(PARTICIPANT_OVERRIDE_KEY, JSON.stringify(participantOverrides));
}

function getCountsForRow(row) {
    const override = participantOverrides[row.group_key];
    const partM = override ? Number(override.m) || 0 : Number(row.part_m) || 0;
    const partF = override ? Number(override.f) || 0 : Number(row.part_f) || 0;
    return { partM, partF, total: partM + partF };
}

function applyOverrides(rows) {
    return rows.map(row => {
        const counts = getCountsForRow(row);
        return {
            ...row,
            display_m: counts.partM,
            display_f: counts.partF,
            display_total: counts.total,
        };
    });
}

function splitRows(rows) {
    const lgu = [];
    const dole = [];
    rows.forEach(row => {
        const bucket = String(row.gip_type || '').trim().toLowerCase();
        if (bucket === 'dole') {
            dole.push(row);
        } else {
            lgu.push(row);
        }
    });
    return { lgu, dole };
}

async function deleteRecord(ids) {
    const res = await fetch(`${API_URL}?ids=${encodeURIComponent(ids)}`, { method: 'DELETE' });
    const json = await res.json();
    if (!json.success) throw new Error(json.error);
}

function buildRow(r) {
    const total = +r.display_total || 0;
    const monthLabel = MONTH_NAMES[+r.month_num || +r.month || 0] || '—';
    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50/70 transition-colors" data-key="${r.group_key}" data-ids="${escHtml(r.gip_ids || '')}">
        <td class="px-4 py-3 text-gray-700 font-medium whitespace-nowrap">${escHtml(monthLabel)}</td>
        <td class="px-4 py-3 text-gray-700">${escHtml(r.office_assignment || '—')}</td>
        <td class="px-4 py-3 text-gray-700">${escHtml(r.school || '—')}</td>
        <td class="px-1.5 py-3 text-center text-gray-600 border-l border-gray-100 p-m" contenteditable="false">${+r.display_m || 0}</td>
        <td class="px-1.5 py-3 text-center text-gray-600 p-f" contenteditable="false">${+r.display_f || 0}</td>
        <td class="px-1.5 py-3 text-center font-semibold text-teal-600 p-total">${total}</td>
        <td class="px-2 py-3 text-center border-l border-gray-100">
            <div class="flex items-center justify-center gap-2">
                <button onclick="startEdit(this.closest('tr').dataset.key)" class="edit-btn text-yellow-500 hover:text-yellow-600" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="promptDelete(this.closest('tr').dataset.key)" class="delete-btn text-red-400 hover:text-red-600" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <button onclick="promptSave(this.closest('tr').dataset.key)" class="save-btn hidden text-green-500 hover:text-green-600" title="Save">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button onclick="cancelEdit(this.closest('tr').dataset.key)" class="cancel-btn hidden text-gray-400 hover:text-gray-600" title="Cancel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </td>
    </tr>`;
}

function buildTotalRow(rows) {
    let m = 0, f = 0;
    rows.forEach(r => { m += +r.display_m || 0; f += +r.display_f || 0; });
    return `
    <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-3 text-gray-800 font-bold" colspan="3">TOTAL</td>
        <td class="px-1.5 py-3 text-center text-gray-700 border-l border-gray-100">${m}</td>
        <td class="px-1.5 py-3 text-center text-gray-700">${f}</td>
        <td class="px-1.5 py-3 text-center font-bold text-teal-600">${m + f}</td>
        <td class="border-l border-gray-100"></td>
    </tr>`;
}

function renderSection(rows, section) {
    const config = section === 'lgu'
        ? { bodyId: 'gipLguBody', infoId: 'gipLguInfo', prevId: 'prevLguBtn', nextId: 'nextLguBtn', pagesId: 'pageNumbersLgu' }
        : { bodyId: 'gipDoleBody', infoId: 'gipDoleInfo', prevId: 'prevDoleBtn', nextId: 'nextDoleBtn', pagesId: 'pageNumbersDole' };

    const tbody = document.getElementById(config.bodyId);
    const total = rows.length;
    const totalPg = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    currentPages[section] = Math.min(currentPages[section], totalPg);
    const page = currentPages[section];
    const start = (page - 1) * ROWS_PER_PAGE;
    const end = Math.min(start + ROWS_PER_PAGE, total);

    if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">No ${section.toUpperCase()} data found for this year.</td></tr>`;
    } else {
        tbody.innerHTML = rows.slice(start, end).map(buildRow).join('') + buildTotalRow(rows);
    }

    document.getElementById(config.infoId).textContent =
        total === 0 ? 'No entries' : `Showing ${start + 1}–${end} of ${total} entries`;
    document.getElementById(config.prevId).disabled = page <= 1;
    document.getElementById(config.nextId).disabled = page >= totalPg;

    const container = document.getElementById(config.pagesId);
    container.innerHTML = '';
    for (let p = 1; p <= totalPg; p++) {
        const btn = document.createElement('button');
        btn.textContent = p;
        btn.className = 'px-3 py-1.5 rounded-lg text-sm border font-medium transition-colors ' +
            (p === page ? 'bg-teal-500 text-white border-teal-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPages[section] = p; renderSection(rows, section); };
        container.appendChild(btn);
    }
}

function sumParticipants(rows) {
    return rows.reduce((acc, row) => {
        acc.m += Number(row.display_m) || 0;
        acc.f += Number(row.display_f) || 0;
        return acc;
    }, { m: 0, f: 0 });
}

function updateCards(rows = []) {
    const totalCounts = sumParticipants(rows);
    const lguCounts = sumParticipants(lguRows);
    const doleCounts = sumParticipants(doleRows);

    document.getElementById('card-participants-total').textContent = totalCounts.m + totalCounts.f;
    document.getElementById('card-participants-m').textContent = `${totalCounts.m}M`;
    document.getElementById('card-participants-f').textContent = `${totalCounts.f}F`;
    document.getElementById('card-lgu-total').textContent = lguCounts.m + lguCounts.f;
    document.getElementById('card-dole-total').textContent = doleCounts.m + doleCounts.f;
    document.getElementById('card-records-total').textContent = rows.length;
}

function populateYearFilter(years) {
    const sel = document.getElementById('yearFilter');
    sel.innerHTML = years.map(y => `<option value="${y}" ${y == selectedYear ? 'selected' : ''}>${y}</option>`).join('');
}

async function load(year, month = '', search = '') {
    const data = await fetchData(year, month, search);
    if (!data) return;
    allRows = applyOverrides(data.rows);
    const split = splitRows(allRows);
    lguRows = split.lgu;
    doleRows = split.dole;
    currentPages = { lgu: 1, dole: 1 };
    updateCards(allRows);
    populateYearFilter(data.years);
    renderSection(lguRows, 'lgu');
    renderSection(doleRows, 'dole');
}

function applyFilters() {
    selectedMonth = document.getElementById('monthFilter').value;
    searchQuery = document.getElementById('searchSchool').value.trim();
    load(selectedYear, selectedMonth, searchQuery);
}

function handleSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(applyFilters, 300);
}

function changePage(section, dir) {
    const rows = section === 'lgu' ? lguRows : doleRows;
    const totalPg = Math.max(1, Math.ceil(rows.length / ROWS_PER_PAGE));
    currentPages[section] = Math.max(1, Math.min(currentPages[section] + dir, totalPg));
    renderSection(rows, section);
}

function getRowEl(key) { return document.querySelector(`tr[data-key="${key}"]`); }

const EDIT_CLASSES = ['p-m', 'p-f'];

function refreshParticipantTotal(row) {
    const partM = Number.parseInt(row.querySelector('.p-m').textContent.trim(), 10) || 0;
    const partF = Number.parseInt(row.querySelector('.p-f').textContent.trim(), 10) || 0;
    row.querySelector('.p-total').textContent = partM + partF;
}

function startEdit(key) {
    const row = getRowEl(key);
    if (!row) return;
    row.classList.add('bg-yellow-50');
    const snap = {};
    EDIT_CLASSES.forEach(cls => {
        const cell = row.querySelector('.' + cls);
        if (!cell) return;
        snap[cls] = cell.textContent.trim();
        cell.contentEditable = 'true';
        cell.classList.add('border', 'border-yellow-300', 'bg-white', 'outline-none');
        cell.oninput = () => refreshParticipantTotal(row);
    });
    refreshParticipantTotal(row);
    editSnapshot[key] = snap;
    row.querySelector('.edit-btn').classList.add('hidden');
    row.querySelector('.delete-btn').classList.add('hidden');
    row.querySelector('.save-btn').classList.remove('hidden');
    row.querySelector('.cancel-btn').classList.remove('hidden');
}

function cancelEdit(key) {
    const row = getRowEl(key);
    if (!row) return;
    const snap = editSnapshot[key] || {};
    EDIT_CLASSES.forEach(cls => {
        const cell = row.querySelector('.' + cls);
        if (cell) {
            cell.contentEditable = 'false';
            cell.textContent = snap[cls] ?? cell.textContent;
            cell.classList.remove('border', 'border-yellow-300', 'bg-white', 'outline-none');
            cell.oninput = null;
        }
    });
    refreshParticipantTotal(row);
    row.classList.remove('bg-yellow-50');
    row.querySelector('.edit-btn').classList.remove('hidden');
    row.querySelector('.delete-btn').classList.remove('hidden');
    row.querySelector('.save-btn').classList.add('hidden');
    row.querySelector('.cancel-btn').classList.add('hidden');
    delete editSnapshot[key];
}

function promptSave(key) { savingId = key; showModal('saveModal'); }
function promptDelete(key) { deletingId = key; showModal('deleteModal'); }

async function confirmSave() {
    const key = savingId;
    const row = getRowEl(key);
    closeModal('saveModal');
    if (!row || !key) return;

    const partM = Number.parseInt(row.querySelector('.p-m').textContent.trim(), 10) || 0;
    const partF = Number.parseInt(row.querySelector('.p-f').textContent.trim(), 10) || 0;

    participantOverrides[key] = { m: partM, f: partF };
    persistParticipantOverrides();
    await load(selectedYear, selectedMonth, searchQuery);
    savingId = null;
}

async function confirmDelete() {
    const key = deletingId;
    closeModal('deleteModal');
    if (!key) return;
    try {
        const row = getRowEl(key);
        if (!row) throw new Error('Row not found');
        await deleteRecord(row.dataset.ids || '');
        await load(selectedYear, selectedMonth, searchQuery);
    } catch (e) {
        showError('Delete failed: ' + e.message);
    }
    deletingId = null;
}

function showModal(id) { document.getElementById('modalBackdrop').classList.remove('hidden'); document.getElementById(id).classList.remove('hidden'); document.body.classList.add('modal-open'); }
function closeModal(id) { document.getElementById('modalBackdrop').classList.add('hidden'); document.getElementById(id).classList.add('hidden'); document.body.classList.remove('modal-open'); }
function closeDeleteModal() { closeModal('deleteModal'); deletingId = null; }
function closeSaveModal() { closeModal('saveModal'); savingId = null; }
document.addEventListener('click', e => { if (e.target.id === 'modalBackdrop') { closeDeleteModal(); closeSaveModal(); } });

function showLoading(state) { document.getElementById('loadingIndicator').classList.toggle('hidden', !state); }
function showError(msg) {
    const t = document.getElementById('errorToast');
    t.textContent = msg;
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 4000);
}

function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

document.getElementById('yearFilter').addEventListener('change', function () {
    selectedYear = +this.value;
    load(selectedYear, selectedMonth, searchQuery);
});

load(selectedYear);
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>