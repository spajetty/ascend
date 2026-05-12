<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Work Immersion & Internship Referral Program';
$pageHeading = 'Work Immersion & Internship Referral Program';

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

        <!-- ===== SUMMARY CARDS Row 1 ===== -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800" id="card-part-total">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium" id="card-part-m">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium" id="card-part-f">—F</span>
                        </div>
                    </div>
                    <div class="bg-teal-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Participants</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800" id="card-inq-total">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium" id="card-inq-m">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium" id="card-inq-f">—F</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Inquired</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-violet-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800" id="card-ref-total">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium" id="card-ref-m">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium" id="card-ref-f">—F</span>
                        </div>
                    </div>
                    <div class="bg-violet-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Referred</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-amber-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800" id="card-int-total">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium" id="card-int-m">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium" id="card-int-f">—F</span>
                        </div>
                    </div>
                    <div class="bg-amber-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Interviewed</span>
            </div>
        </div>

        <!-- ===== SUMMARY CARDS Row 2 ===== -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800" id="card-peso-total">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium" id="card-peso-m">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium" id="card-peso-f">—F</span>
                        </div>
                    </div>
                    <div class="bg-orange-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">PESO-Accepted</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800" id="card-priv-total">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium" id="card-priv-m">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium" id="card-priv-f">—F</span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Privately-Accepted</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-gray-800" id="card-notpr-total">—</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-blue-500 font-medium" id="card-notpr-m">—M</span>
                            <span class="text-gray-300 text-xs">/</span>
                            <span class="text-xs text-pink-500 font-medium" id="card-notpr-f">—F</span>
                        </div>
                    </div>
                    <div class="bg-red-100 p-2.5 rounded-xl">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Not Proceeded</span>
            </div>
        </div>

        <!-- ===== FILTER BAR ===== -->
        <div class="flex items-center gap-3 mb-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select id="yearFilter" onchange="loadData()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                </select>
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
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchSchool" placeholder="Search school..."
                    oninput="applyFilters()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
            </div>
        </div>

        <!-- ===== MAIN TABLE ===== -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Work Immersion &amp; Internship Referral Program</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" id="wiTable">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-24" rowspan="2">CONTRACT PERIOD</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium" rowspan="2">SCHOOL</th>
                            <th class="text-left px-3 py-3 text-gray-500 font-medium w-28" rowspan="2">YEAR LEVEL</th>
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
                    <tbody id="wiTbody">
                        <tr><td colspan="28" class="text-center py-8 text-gray-400 text-sm">Loading…</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                <span class="text-sm text-gray-500" id="paginationInfo">—</span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                        disabled>&#8249;</button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">&#8250;</button>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
const API_URL      = '/api/work-imm-api.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];
let filteredRows = [];
let currentPage  = 1;
let deletingId   = null;
let savingId     = null;
const editingData = {};

// ─── Boot ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => loadData());

async function loadData() {
    const year = document.getElementById('yearFilter').value || new Date().getFullYear();
    const tbody = document.getElementById('wiTbody');
    tbody.innerHTML = '<tr><td colspan="28" class="text-center py-8 text-gray-400 text-sm">Loading…</td></tr>';

    try {
        const res  = await fetch(`${API_URL}?year=${year}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        const { rows, totals, years } = json.data;

        populateYears(years, parseInt(year));
        updateCards(totals);

        allRows      = rows;
        filteredRows = [...allRows];
        currentPage  = 1;
        applyFilters();

    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="28" class="text-center py-8 text-red-400 text-sm">Error: ${err.message}</td></tr>`;
    }
}

function populateYears(years, selected) {
    const sel = document.getElementById('yearFilter');
    sel.innerHTML = '';
    years.forEach(y => {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        if (y === selected) opt.selected = true;
        sel.appendChild(opt);
    });
}

function updateCards(t) {
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    set('card-part-total',  t.part_total);  set('card-part-m',  t.part_m  + 'M'); set('card-part-f',  t.part_f  + 'F');
    set('card-inq-total',   t.inq_total);   set('card-inq-m',   t.inq_m   + 'M'); set('card-inq-f',   t.inq_f   + 'F');
    set('card-ref-total',   t.ref_total);   set('card-ref-m',   t.ref_m   + 'M'); set('card-ref-f',   t.ref_f   + 'F');
    set('card-int-total',   t.int_total);   set('card-int-m',   t.int_m   + 'M'); set('card-int-f',   t.int_f   + 'F');
    set('card-peso-total',  t.peso_total);  set('card-peso-m',  t.peso_m  + 'M'); set('card-peso-f',  t.peso_f  + 'F');
    set('card-priv-total',  t.priv_total);  set('card-priv-m',  t.priv_m  + 'M'); set('card-priv-f',  t.priv_f  + 'F');
    set('card-notpr-total', t.notpr_total); set('card-notpr-m', t.notpr_m + 'M'); set('card-notpr-f', t.notpr_f + 'F');
}

// ─── Render ───────────────────────────────────────────────────────────────────
function escHtml(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function buildRow(r) {
    const id = r.work_immersion_id;

    // Education level badge
    const isCollege = (r.education_level || '').toLowerCase().includes('college');
    const isSHS     = (r.education_level || '').toLowerCase().includes('shs') ||
                      (r.education_level || '').toLowerCase().includes('senior');
    const badge = isCollege
        ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-700">College</span>`
        : isSHS
        ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">SHS</span>`
        : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">${escHtml(r.education_level)}</span>`;

    const typeKey = isCollege ? 'college' : isSHS ? 'shs' : (r.education_level || '').toLowerCase();

    // td helpers
    const td  = v => `<td class="px-2 py-3 text-center text-gray-600">${v}</td>`;
    const tdL = v => `<td class="px-2 py-3 text-center text-gray-600 border-l border-gray-100">${v}</td>`;
    const tdT = (v, color, bg) => `<td class="px-2 py-3 text-center font-semibold ${color} ${bg}">${v}</td>`;

    const actionBtns = `
        <td class="px-3 py-3 text-center border-l border-gray-100">
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
        </td>`;

    const tr = document.createElement('tr');
    tr.className = 'border-b border-gray-50 hover:bg-gray-50';
    tr.dataset.id     = id;
    tr.dataset.school = (r.school || '').toLowerCase();
    tr.dataset.type   = typeKey;

    tr.innerHTML = `
        <td class="px-3 py-3 text-gray-700 font-medium" data-field="contract_period">${escHtml(r.contract_period)}</td>
        <td class="px-3 py-3 text-gray-700" data-field="school">${escHtml(r.school)}</td>
        <td class="px-3 py-3" data-field="education_level">${badge}</td>
        <td class="px-3 py-3 text-gray-600" data-field="course">${escHtml(r.course)}</td>
        <td class="px-3 py-3 text-gray-600" data-field="office_assignment">${escHtml(r.office_assignment)}</td>
        <td class="px-3 py-3 text-center text-gray-700 font-medium" data-field="required_hours">${r.required_hours}</td>
        ${tdL(r.part_m)}${td(r.part_f)}${tdT(r.part_total,  'text-teal-600',   'bg-teal-50')}
        ${tdL(r.inq_m)}${td(r.inq_f)}${tdT(r.inq_total,    'text-blue-500',   'bg-blue-50')}
        ${tdL(r.ref_m)}${td(r.ref_f)}${tdT(r.ref_total,    'text-violet-500', 'bg-violet-50')}
        ${tdL(r.int_m)}${td(r.int_f)}${tdT(r.int_total,    'text-amber-500',  'bg-amber-50')}
        ${tdL(r.peso_m)}${td(r.peso_f)}${tdT(r.peso_total, 'text-orange-500', 'bg-orange-50')}
        ${tdL(r.priv_m)}${td(r.priv_f)}${tdT(r.priv_total, 'text-green-500',  'bg-green-50')}
        ${tdL(r.notpr_m)}${td(r.notpr_f)}${tdT(r.notpr_total,'text-red-400',  'bg-red-50')}
        ${actionBtns}
    `;
    return tr;
}

function buildTotalsRow(rows) {
    const sum = key => rows.reduce((a, r) => a + (parseInt(r[key]) || 0), 0);
    const td  = (v, color, bg, borderL = false) =>
        `<td class="px-2 py-3 text-center font-bold ${color} ${bg} ${borderL ? 'border-l border-gray-100' : ''}">${v}</td>`;

    return `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
        <td class="px-3 py-3 text-gray-800 font-bold" colspan="5">TOTAL</td>
        <td class="px-3 py-3 text-center font-bold text-gray-700">—</td>
        ${td(sum('part_m'), 'text-gray-700','', true)}${td(sum('part_f'),'text-gray-700','')}${td(sum('part_total'),'text-teal-600','bg-teal-100')}
        ${td(sum('inq_m'),  'text-gray-700','', true)}${td(sum('inq_f'), 'text-gray-700','')}${td(sum('inq_total'), 'text-blue-500','bg-blue-100')}
        ${td(sum('ref_m'),  'text-gray-700','', true)}${td(sum('ref_f'), 'text-gray-700','')}${td(sum('ref_total'), 'text-violet-500','bg-violet-100')}
        ${td(sum('int_m'),  'text-gray-700','', true)}${td(sum('int_f'), 'text-gray-700','')}${td(sum('int_total'), 'text-amber-500','bg-amber-100')}
        ${td(sum('peso_m'), 'text-gray-700','', true)}${td(sum('peso_f'),'text-gray-700','')}${td(sum('peso_total'),'text-orange-500','bg-orange-100')}
        ${td(sum('priv_m'), 'text-gray-700','', true)}${td(sum('priv_f'),'text-gray-700','')}${td(sum('priv_total'),'text-green-500','bg-green-100')}
        ${td(sum('notpr_m'),'text-gray-700','', true)}${td(sum('notpr_f'),'text-gray-700','')}${td(sum('notpr_total'),'text-red-400','bg-red-100')}
        <td class="border-l border-gray-100"></td>
    </tr>`;
}

// ─── Filter & Pagination ──────────────────────────────────────────────────────
function applyFilters() {
    const query = (document.getElementById('searchSchool').value || '').toLowerCase().trim();
    const type  = (document.getElementById('filterType').value || '').toLowerCase();

    filteredRows = allRows.filter(r => {
        const school   = (r.school || '').toLowerCase();
        const eduLevel = (r.education_level || '').toLowerCase();
        const typeKey  = eduLevel.includes('college') ? 'college'
                       : (eduLevel.includes('shs') || eduLevel.includes('senior')) ? 'shs'
                       : eduLevel;
        const matchSearch = !query || school.includes(query);
        const matchType   = !type  || typeKey === type;
        return matchSearch && matchType;
    });

    currentPage = 1;
    renderPage();
}

function renderPage() {
    const tbody      = document.getElementById('wiTbody');
    const total      = filteredRows.length;
    const totalPages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    currentPage      = Math.min(currentPage, totalPages);
    const start      = (currentPage - 1) * ROWS_PER_PAGE;
    const pageRows   = filteredRows.slice(start, start + ROWS_PER_PAGE);

    tbody.innerHTML = '';

    if (total === 0) {
        tbody.innerHTML = '<tr><td colspan="28" class="text-center py-8 text-gray-400 text-sm">No entries found.</td></tr>';
    } else {
        pageRows.forEach(r => tbody.appendChild(buildRow(r)));
        tbody.insertAdjacentHTML('beforeend', buildTotalsRow(filteredRows));
    }

    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries found' : `Showing ${start + 1}–${Math.min(start + ROWS_PER_PAGE, total)} of ${total} entries`;

    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;

    const container = document.getElementById('pageNumbers');
    container.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.textContent = p;
        btn.className = 'px-3 py-1.5 rounded-lg text-sm border font-medium transition-colors ' +
            (p === currentPage ? 'bg-teal-500 text-white border-teal-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

function changePage(dir) {
    const totalPages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderPage();
}

// ─── Edit ─────────────────────────────────────────────────────────────────────
// Editable: contract_period, school, education_level (text), course,
//           office_assignment, required_hours
// Read-only: all M/F/T count columns (come from apply_benef)
function getRow(id) {
    return document.querySelector(`#wiTbody tr[data-id="${id}"]`);
}

function toggleEditMode(id) {
    const row = getRow(id);
    if (!row || row.classList.contains('editing')) { cancelEdit(id); return; }

    row.classList.add('editing', 'bg-yellow-50');

    const editableFields = ['contract_period', 'school', 'education_level', 'course', 'office_assignment', 'required_hours'];
    editableFields.forEach(field => {
        const cell = row.querySelector(`[data-field="${field}"]`);
        if (!cell) return;
        // For education_level the cell shows a badge — swap to plain text for editing
        if (field === 'education_level') {
            editingData[`${id}_${field}_html`] = cell.innerHTML;
            const txt = allRows.find(r => r.work_immersion_id == id)?.education_level || '';
            cell.innerHTML = txt;
        } else {
            editingData[`${id}_${field}`] = cell.textContent.trim();
        }
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

    const editableFields = ['contract_period', 'school', 'education_level', 'course', 'office_assignment', 'required_hours'];
    editableFields.forEach(field => {
        const cell = row.querySelector(`[data-field="${field}"]`);
        if (!cell) return;
        if (field === 'education_level' && editingData[`${id}_${field}_html`]) {
            cell.innerHTML = editingData[`${id}_${field}_html`];
        } else {
            cell.textContent = editingData[`${id}_${field}`] || '';
        }
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

    const get = field => {
        const cell = row.querySelector(`[data-field="${field}"]`);
        return cell ? cell.textContent.trim() : '';
    };

    const payload = {
        work_immersion_id: id,
        contract_period:   get('contract_period'),
        school:            get('school'),
        education_level:   get('education_level'),
        course:            get('course'),
        office_assignment: get('office_assignment'),
        required_hours:    parseInt(get('required_hours')) || 0,
    };

    try {
        const res  = await fetch(API_URL, {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Update cache
        const idx = allRows.findIndex(r => r.work_immersion_id == id);
        if (idx !== -1) Object.assign(allRows[idx], payload);

        cancelEdit(id);
        closeSaveModal();

        row.style.transition = 'background-color 0.3s';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);

    } catch (err) {
        alert('Save failed: ' + err.message);
        closeSaveModal();
    }
}

// ─── Delete ───────────────────────────────────────────────────────────────────
function deleteRow(id) {
    deletingId = id;
    openModal('deleteModal');
}

async function confirmDelete() {
    try {
        const res  = await fetch(`${API_URL}?id=${deletingId}`, { method: 'DELETE' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        allRows      = allRows.filter(r => r.work_immersion_id != deletingId);
        filteredRows = filteredRows.filter(r => r.work_immersion_id != deletingId);

        const row = getRow(deletingId);
        if (row) {
            row.style.transition = 'opacity 0.3s';
            row.style.opacity = '0';
            setTimeout(() => renderPage(), 300);
        } else {
            renderPage();
        }

        closeDeleteModal();
    } catch (err) {
        alert('Delete failed: ' + err.message);
        closeDeleteModal();
    }
}

// ─── Modals ───────────────────────────────────────────────────────────────────
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