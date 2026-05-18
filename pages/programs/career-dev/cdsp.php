<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Career Development Support Program';
$pageHeading = 'Career Development Support Program';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<style>
    body.modal-open { overflow: hidden; }
</style>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen w-0 md:w-auto">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-4 md:px-8 pt-6">
        <a href="/pages/programs/career-development.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Career Development Section
        </a>
    </div>

    <div class="px-4 md:px-8 py-6 space-y-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span id="cardSessions" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Career Dev. Sessions</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="cardTotal" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Career Dev. Participants (Total)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span id="cardMale" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-cyan-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Career Dev. Participants (Male)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <span id="cardFemale" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-pink-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Career Dev. Participants (Female)</span>
            </div>

        </div>

        <!-- Filter -->
        <div class="flex flex-col gap-2 mb-4">
            <!-- Row 1: Year filter -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 whitespace-nowrap">Filter by year:</span>
                <select id="yearSelect"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5">
                </select>
            </div>

            <!-- Row 2: Search + Add Entry always on same line -->
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text"
                        id="searchSchool"
                        placeholder="Search school..."
                        oninput="applyFilters()"
                        class="w-full pl-4 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg"/>
                </div>

                <button onclick="openAddModal()"
                    class="shrink-0 inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition-all whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Entry
                </button>
            </div>

        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Career Development Support Program</h2>
                <span id="tableTotal" class="text-sm font-semibold text-teal-600 bg-teal-100 px-3 py-1 rounded-full shrink-0">— Total</span>
            </div>

            <!-- Scrollable table wrapper — scroll is scoped here, not the whole page -->
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs min-w-[700px]">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold whitespace-nowrap">DATE CONDUCTED</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">SCHOOL</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap hidden md:table-cell">DISTRICT</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">GRADE LEVEL</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap hidden lg:table-cell">GRADES OFFERED</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap hidden sm:table-cell">APPROVAL</th>
                            <th class="text-center px-4 py-3 text-cyan-500 font-semibold border-l border-gray-100 whitespace-nowrap">MALE</th>
                            <th class="text-center px-4 py-3 text-pink-500 font-semibold border-l border-gray-100 whitespace-nowrap">FEMALE</th>
                            <th class="text-center px-4 py-3 text-teal-600 font-semibold border-l border-gray-100 whitespace-nowrap">TOTAL</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="loadingRow">
                            <td colspan="10" class="px-4 py-8 text-center text-gray-400 text-sm">Loading...</td>
                        </tr>
                    </tbody>
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
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>&rsaquo;</button>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Modal Backdrop -->
<div id="modalBackdrop"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-40">
</div>

<!-- Delete Confirmation Modal -->
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

<!-- Save Confirmation Modal -->
<div id="saveModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4 animate-modal">
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

<!-- ADD ENTRY MODAL -->
<div id="addModal"
    class="fixed inset-0 hidden z-50 items-center justify-center px-4">

    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden animate-modal">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-teal-50 to-cyan-50">

            <div>
                <h2 class="text-base font-bold text-gray-800">
                    Add Career Development Entry
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    Create a new CDSP record
                </p>
            </div>

            <button onclick="closeAddModal()"
                class="w-8 h-8 rounded-xl hover:bg-white/70 flex items-center justify-center text-gray-500 transition">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>

            </button>
        </div>

        <!-- BODY -->
        <form id="addForm"
            class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">

            <!-- DATE + GRADE -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Date Conducted
                    </label>

                    <input type="date"
                        name="date_of_conduct"
                        required
                        class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Grade Level
                    </label>

                    <input type="text"
                        name="grade_level"
                        placeholder="Example: Grade 12"
                        class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-300">
                </div>

            </div>

            <!-- SCHOOL -->
            <div class="relative">

                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    School / Institution
                </label>

                <input type="text"
                    id="schoolSearch"
                    autocomplete="off"
                    placeholder="Search school..."
                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-300">

                <input type="hidden"
                    id="selectedSchoolId"
                    name="school_id">

                <!-- RESULTS -->
                <div id="schoolResults"
                    class="absolute z-50 hidden mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto">
                </div>

            </div>

            <!-- PARTICIPANTS -->
            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-semibold text-cyan-600 mb-1.5">
                        Male Participants
                    </label>

                    <input type="number"
                        name="participants_male"
                        min="0"
                        value="0"
                        class="w-full text-sm border border-cyan-100 bg-cyan-50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-pink-600 mb-1.5">
                        Female Participants
                    </label>

                    <input type="number"
                        name="participants_female"
                        min="0"
                        value="0"
                        class="w-full text-sm border border-pink-100 bg-pink-50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-pink-300">
                </div>

            </div>

            <!-- APPROVAL -->
            <label class="flex items-center gap-3 bg-gray-50 rounded-2xl px-4 py-3 cursor-pointer">

                <input type="checkbox"
                    name="approval_letter"
                    value="1"
                    class="w-4 h-4 rounded border-gray-300 text-teal-500 focus:ring-teal-400">

                <div>
                    <p class="text-sm font-medium text-gray-700">
                        Approval Letter Submitted
                    </p>

                    <p class="text-xs text-gray-500">
                        Mark if documentation is complete
                    </p>
                </div>

            </label>

            <!-- FOOTER -->
            <div class="flex items-center justify-end gap-3 pt-2">

                <button type="button"
                    onclick="closeAddModal()"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition">

                    Cancel
                </button>

                <button type="submit"
                    id="submitCdspBtn"
                    class="px-5 py-2.5 text-sm font-medium bg-teal-500 hover:bg-teal-600 text-white rounded-xl transition">

                    Submit Entry
                </button>

            </div>

        </form>

    </div>
</div>

<!-- SCHOOL MODAL -->
<div id="schoolModal"
    class="fixed inset-0 hidden z-[60] items-center justify-center px-4">

    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden animate-modal">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-cyan-50 to-teal-50">

            <div>
                <h2 class="text-base font-bold text-gray-800">
                    Add School
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    Create a new school record
                </p>
            </div>

            <button onclick="closeSchoolModal()"
                class="w-8 h-8 rounded-xl hover:bg-white/70 flex items-center justify-center text-gray-500 transition">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>

            </button>
        </div>

        <!-- BODY -->
        <form id="schoolForm"
            class="p-6 space-y-5">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    School Name
                </label>

                <input type="text"
                    name="school_name"
                    required
                    placeholder="Enter school name"
                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-300">
            </div>

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Congressional District
                    </label>

                    <input type="number"
                        name="congressional_district"
                        placeholder="Example: 2"
                        class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Grades Offered
                    </label>

                    <input type="text"
                        name="grades_offered"
                        placeholder="K-12"
                        class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-300">
                </div>

            </div>

            <!-- FOOTER -->
            <div class="flex items-center justify-end gap-3 pt-2">

                <button type="button"
                    onclick="closeSchoolModal()"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition">

                    Cancel
                </button>

                <button type="submit"
                    id="submitSchoolBtn"
                    class="px-5 py-2.5 text-sm font-medium bg-teal-500 hover:bg-teal-600 text-white rounded-xl transition">

                    Save School
                </button>

            </div>

        </form>

    </div>
</div>

<script>
const API_URL = '/backend/career-dev/show-cdsp.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];   // raw data from API
let filteredRows = [];   // after search filter
let currentPage  = 1;
let deletingId   = null;
let savingId     = null;
let editingData  = {};   // backup before edit

// ─── Helpers ─────────────────────────────────────────────────────────────────
function fmt(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

function num(n) {
    return Number(n || 0).toLocaleString();
}

// ─── Load data from API ───────────────────────────────────────────────────────
async function loadData(year) {
    document.getElementById('loadingRow').style.display = '';
    document.getElementById('tableBody').querySelectorAll('tr:not(#loadingRow)').forEach(r => r.remove());

    try {
        const res  = await fetch(`${API_URL}?year=${year}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        const { rows, totals, years } = json.data;

        // Populate year dropdown (only on first load)
        const sel = document.getElementById('yearSelect');
        if (sel.options.length === 0) {
            years.forEach(y => {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                if (y === year) opt.selected = true;
                sel.appendChild(opt);
            });
        }

        // Update cards
        document.getElementById('cardSessions').textContent = num(totals.sessions);
        document.getElementById('cardTotal').textContent    = num(totals.total);
        document.getElementById('cardMale').textContent     = num(totals.total_m);
        document.getElementById('cardFemale').textContent   = num(totals.total_f);
        document.getElementById('tableTotal').textContent   = num(totals.total) + ' Total';

        allRows = rows;
        document.getElementById('searchSchool').value = '';
        applyFilters();

    } catch (err) {
        document.getElementById('loadingRow').innerHTML =
            `<td colspan="6" class="px-4 py-8 text-center text-red-500 text-sm">Error: ${err.message}</td>`;
    }
}

// ─── Filter + Render ──────────────────────────────────────────────────────────
function applyFilters() {
    const query = document.getElementById('searchSchool').value.toLowerCase().trim();
    filteredRows = allRows.filter(r =>
        !query || (r.school_name || '').toLowerCase().includes(query)
    );
    currentPage = 1;
    renderPage();
}

function renderPage() {
    const tbody  = document.getElementById('tableBody');
    const total  = filteredRows.length;
    const pages  = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    const start  = (currentPage - 1) * ROWS_PER_PAGE;
    const end    = Math.min(start + ROWS_PER_PAGE, total);
    const slice  = filteredRows.slice(start, end);

    // Clear all but loading row
    Array.from(tbody.querySelectorAll('tr:not(#loadingRow)')).forEach(r => r.remove());
    document.getElementById('loadingRow').style.display = 'none';

    if (slice.length === 0) {
        tbody.insertAdjacentHTML('beforeend',
            `<tr><td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">No entries found.</td></tr>`);
    } else {
        slice.forEach(row => tbody.insertAdjacentHTML('beforeend', buildRow(row)));
    }

    // Total row
    const totM = filteredRows.reduce(
        (s, r) => s + (Number(r.participants_male) || 0),
        0
    );

    const totF = filteredRows.reduce(
        (s, r) => s + (Number(r.participants_female) || 0),
        0
    );
    tbody.insertAdjacentHTML('beforeend', `
    <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">

        <td class="px-4 py-3 text-gray-800 font-bold">
            TOTAL
        </td>

        <td class="px-4 py-3 border-l border-gray-100"></td>

        <!-- DISTRICT (hidden md) -->
        <td class="px-4 py-3 border-l border-gray-100 hidden md:table-cell"></td>

        <!-- GRADE LEVEL -->
        <td class="px-4 py-3 border-l border-gray-100"></td>

        <!-- GRADES OFFERED (hidden lg) -->
        <td class="px-4 py-3 border-l border-gray-100 hidden lg:table-cell"></td>

        <!-- APPROVAL (hidden sm) -->
        <td class="px-4 py-3 border-l border-gray-100 hidden sm:table-cell"></td>

        <!-- MALE -->
        <td class="px-4 py-3 text-center font-bold text-cyan-500 bg-cyan-100 border-l border-gray-100">
            ${num(totM)}
        </td>

        <!-- FEMALE -->
        <td class="px-4 py-3 text-center font-bold text-pink-500 bg-pink-100 border-l border-gray-100">
            ${num(totF)}
        </td>

        <!-- TOTAL -->
        <td class="px-4 py-3 text-center font-bold text-teal-600 bg-teal-100 border-l border-gray-100">
            ${num(totM + totF)}
        </td>

        <!-- ACTIONS -->
        <td class="border-l border-gray-100"></td>

    </tr>
    `);

    // Pagination info
    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries found' : `Showing ${start + 1}–${end} of ${total} entries`;
    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= pages;

    const container = document.getElementById('pageNumbers');
    container.innerHTML = '';
    for (let p = 1; p <= pages; p++) {
        const btn = document.createElement('button');
        btn.textContent = p;
        btn.className = `px-3 py-1.5 rounded-lg text-sm border font-medium transition-colors ` +
            (p === currentPage ? 'bg-teal-500 text-white border-teal-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

function buildRow(r) {
    const id = r.cdsp_id;

    const total =
        Number(r.participants_male ?? 0) +
        Number(r.participants_female ?? 0);

    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50"
        data-id="${id}"
        data-school="${(r.school_name || '').toLowerCase()}">

        <td class="px-4 py-3 text-gray-700 font-medium date-cell">
            ${fmt(r.date_of_conduct)}
        </td>

        <td class="px-4 py-3 text-gray-600 border-l border-gray-100 school-cell">
            ${r.school_name || '—'}
        </td>

        <!-- DISTRICT -->
        <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 hidden md:table-cell">
            ${r.congressional_district ?? '—'}
        </td>

        <!-- GRADE LEVEL (from careerdev table) -->
        <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">
            ${r.grade_level ?? '—'}
        </td>

        <!-- GRADES OFFERED (from schools table) -->
        <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 hidden lg:table-cell">
            ${r.grades_offered ?? '—'}
        </td>

        <!-- APPROVAL -->
        <td class="px-4 py-3 text-center border-l border-gray-100 hidden sm:table-cell">
            ${r.approval_letter == 1
                ? `<span class="text-green-600 font-semibold">✔ Yes</span>`
                : `<span class="text-gray-400">No</span>`}
        </td>

        <!-- MALE -->
        <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 male-cell">
            ${num(r.participants_male)}
        </td>

        <!-- FEMALE -->
        <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 female-cell">
            ${num(r.participants_female)}
        </td>

        <!-- TOTAL -->
        <td class="px-4 py-3 text-center font-semibold text-teal-600 bg-teal-50 border-l border-gray-100 total-cell">
            ${num(total)}
        </td>

        <!-- ACTIONS -->
        <td class="px-4 py-3 text-center border-l border-gray-100">
            <div class="flex items-center justify-center gap-2 action-buttons">

                <button onclick="toggleEditMode('${id}')"
                    class="text-yellow-500 hover:text-yellow-600 edit-btn">
                    ✏️
                </button>

                <button onclick="deleteRow('${id}')"
                    class="text-red-400 hover:text-red-600 delete-btn">
                    🗑️
                </button>

                <button onclick="saveRow('${id}')"
                    class="text-green-500 hover:text-green-600 save-btn hidden">
                    ✔
                </button>

                <button onclick="cancelEdit('${id}')"
                    class="text-gray-400 hover:text-gray-600 cancel-btn hidden">
                    ✖
                </button>

            </div>
        </td>

    </tr>`;
}

function getRowEl(id) {
    return document.querySelector(`tr[data-id="${id}"]`);
}

// ─── Edit ─────────────────────────────────────────────────────────────────────
function toggleEditMode(id) {
    const row = getRowEl(id);
    if (!row) return;
    if (row.classList.contains('editing')) { cancelEdit(id); return; }

    row.classList.add('editing', 'bg-yellow-50');

    // Store originals
    const rec = allRows.find(r => String(r.cdsp_id) === String(id));
    editingData[id] = { date: rec.date_of_conduct, school: rec.school_name, cdsp_m: rec.participants_male, cdsp_f: rec.participants_female };

    const dateCell = row.querySelector('.date-cell');
    dateCell.innerHTML = `<input type="date" value="${rec.date_of_conduct}" class="border border-yellow-300 rounded px-2 py-1 text-xs w-36 bg-white focus:outline-none focus:ring-1 focus:ring-teal-400" id="edit-date-${id}">`;

    // school, male, female editable
    ['school-cell', 'male-cell', 'female-cell'].forEach(cls => {
        const cell = row.querySelector('.' + cls);
        cell.contentEditable = 'true';
        cell.classList.add('border', 'border-yellow-300', 'bg-white');
    });

    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.add('hidden');
    ab.querySelector('.delete-btn').classList.add('hidden');
    ab.querySelector('.save-btn').classList.remove('hidden');
    ab.querySelector('.cancel-btn').classList.remove('hidden');
}

function cancelEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    const backup = editingData[id];
    if (backup) {
        const total = Number(backup.cdsp_m) + Number(backup.cdsp_f);
        row.querySelector('.date-cell').innerHTML   = fmt(backup.date);
        row.querySelector('.school-cell').textContent = backup.school || '—';
        row.querySelector('.male-cell').textContent   = num(backup.cdsp_m);
        row.querySelector('.female-cell').textContent = num(backup.cdsp_f);
        row.querySelector('.total-cell').textContent  = num(total);
    }
    ['school-cell', 'male-cell', 'female-cell'].forEach(cls => {
        const cell = row.querySelector('.' + cls);
        cell.contentEditable = 'false';
        cell.classList.remove('border', 'border-yellow-300', 'bg-white');
    });
    row.classList.remove('editing', 'bg-yellow-50');
    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.remove('hidden');
    ab.querySelector('.delete-btn').classList.remove('hidden');
    ab.querySelector('.save-btn').classList.add('hidden');
    ab.querySelector('.cancel-btn').classList.add('hidden');
    delete editingData[id];
}

function saveRow(id) {
    savingId = id;
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('saveModal').classList.remove('hidden');
}

function closeSaveModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('saveModal').classList.add('hidden');
    savingId = null;
}

async function confirmSave() {
    const id  = savingId;
    const row = getRowEl(id);
    closeSaveModal();
    if (!row) return;

    const newDate   = document.getElementById(`edit-date-${id}`)?.value || editingData[id]?.date;
    const newSchool = row.querySelector('.school-cell').textContent.trim();
    const newMale   = parseInt(row.querySelector('.male-cell').textContent.replace(/,/g, ''), 10) || 0;
    const newFemale = parseInt(row.querySelector('.female-cell').textContent.replace(/,/g, ''), 10) || 0;

    try {
        const res  = await fetch(API_URL, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cdsp_id: id, date_of_conduct: newDate, school_name: newSchool, participants_male: newMale, participants_female: newFemale })        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Update local data
        const rec = allRows.find(r => String(r.cdsp_id) === String(id));
        if (rec) { rec.date_of_conduct = newDate; rec.school_name = newSchool; rec.participants_male = newMale; rec.participants_female = newFemale; rec.total = newMale + newFemale; }

        // Re-render cells
        const total = newMale + newFemale;
        row.querySelector('.date-cell').innerHTML   = fmt(newDate);
        row.querySelector('.school-cell').textContent = newSchool;
        row.querySelector('.male-cell').textContent   = num(newMale);
        row.querySelector('.female-cell').textContent = num(newFemale);
        row.querySelector('.total-cell').textContent  = num(total);

        ['school-cell', 'male-cell', 'female-cell'].forEach(cls => {
            const cell = row.querySelector('.' + cls);
            cell.contentEditable = 'false';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white');
        });
        row.classList.remove('editing', 'bg-yellow-50');
        const ab = row.querySelector('.action-buttons');
        ab.querySelector('.edit-btn').classList.remove('hidden');
        ab.querySelector('.delete-btn').classList.remove('hidden');
        ab.querySelector('.save-btn').classList.add('hidden');
        ab.querySelector('.cancel-btn').classList.add('hidden');
        delete editingData[id];

        row.style.transition = 'background-color 0.3s ease-out';
        row.style.backgroundColor = '#dcfce7';
        setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);

    } catch (err) {
        alert('Save failed: ' + err.message);
    }
}

// ─── Delete ───────────────────────────────────────────────────────────────────
function deleteRow(id) {
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
    const id  = deletingId;
    const row = getRowEl(id);
    closeDeleteModal();
    if (!row) return;

    try {
        const res  = await fetch(`${API_URL}?id=${id}`, { method: 'DELETE' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Remove from local data and re-render
        allRows = allRows.filter(r => String(r.cdsp_id) !== String(id));
        applyFilters();

    } catch (err) {
        alert('Delete failed: ' + err.message);
    }
}

function openAddModal() {
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.body.style.paddingRight = scrollbarWidth + 'px';
    document.body.classList.add('modal-open');
    document.getElementById('modalBackdrop').classList.remove('hidden');
    const modal = document.getElementById('addModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAddModal() {
    document.body.style.paddingRight = '';
    document.body.classList.remove('modal-open');
    document.getElementById('modalBackdrop').classList.add('hidden');
    const modal = document.getElementById('addModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openSchoolModal() {
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.body.style.paddingRight = scrollbarWidth + 'px';
    document.body.classList.add('modal-open');
    const modal = document.getElementById('schoolModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeSchoolModal() {
    document.body.style.paddingRight = '';
    document.body.classList.remove('modal-open');
    const modal = document.getElementById('schoolModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// ─── Pagination ───────────────────────────────────────────────────────────────
function changePage(dir) {
    const pages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, pages));
    renderPage();
}

// ─── Backdrop click ───────────────────────────────────────────────────────────
document.addEventListener('click', (e) => {
    if (e.target === document.getElementById('modalBackdrop')) {
        closeDeleteModal(); closeSaveModal(); closeAddModal(); closeSchoolModal();
    }
});

// ─── Year change ──────────────────────────────────────────────────────────────
document.getElementById('yearSelect').addEventListener('change', function () {
    loadData(parseInt(this.value));
});

// ─── Init ─────────────────────────────────────────────────────────────────────
loadData(new Date().getFullYear());

async function searchSchools(query) {

    const resultsBox = document.getElementById('schoolResults');

    if (!query.trim()) {
        resultsBox.classList.add('hidden');
        return;
    }

    const res = await fetch(`/backend/career-dev/show-schools.php?q=${encodeURIComponent(query)}`);
    const json = await res.json();

    resultsBox.innerHTML = '';

    if (json.data.length > 0) {

        json.data.forEach(school => {

            resultsBox.innerHTML += `
                <button type="button"
                    onclick="selectSchool('${school.school_id}', '${school.school_name}')"
                    class="w-full text-left px-4 py-3 hover:bg-gray-50 border-b border-gray-100">

                    ${school.school_name}
                </button>
            `;
        });

    } else {

        resultsBox.innerHTML = `
            <div class="p-4 text-sm text-gray-500">
                No schools found.
            </div>

            <button type="button"
                onclick="openSchoolModal()"
                class="w-full text-left px-4 py-3 text-teal-600 hover:bg-teal-50 font-medium">

                + Add this school
            </button>
        `;
    }

    resultsBox.classList.remove('hidden');
}

document.getElementById('schoolSearch')
.addEventListener('input', function () {
    searchSchools(this.value);
});

function selectSchool(id, name) {

    document.getElementById('schoolSearch').value = name;
    document.getElementById('selectedSchoolId').value = id;

    document.getElementById('schoolResults')
        .classList.add('hidden');
}

document.getElementById('addForm')
.addEventListener('submit', async function(e) {

    e.preventDefault();

    const btn = document.getElementById('submitCdspBtn');

    btn.disabled = true;

    btn.innerHTML = `
        <span class="inline-flex items-center gap-2">
            <svg class="animate-spin w-4 h-4"
                fill="none"
                viewBox="0 0 24 24">

                <circle class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v8H4z">
                </path>
            </svg>

            Submitting...
        </span>
    `;

    const formData = Object.fromEntries(
        new FormData(this).entries()
    );

    formData.approval_letter =
        formData.approval_letter ? 1 : 0;

    const start = Date.now();

    const res = await fetch('/backend/career-dev/submit-cdsp.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    });

    const elapsed = Date.now() - start;

    if (elapsed < 2000) {
        await new Promise(r =>
            setTimeout(r, 2000 - elapsed)
        );
    }

    const json = await res.json();

    btn.disabled = false;
    btn.innerHTML = 'Submit Entry';

    if (json.success) {

        closeAddModal();

        loadData(
            parseInt(document.getElementById('yearSelect').value)
        );
    }
});

document.getElementById('schoolForm').addEventListener('submit', async function(e) {

    e.preventDefault();

    const btn = document.getElementById('submitSchoolBtn');
    btn.disabled = true;

    btn.innerHTML = `
        <span class="inline-flex items-center gap-2">
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Saving...
        </span>
    `;

    const formData = Object.fromEntries(
        new FormData(this).entries()
    );

    const start = Date.now();

    const res = await fetch('/backend/career-dev/submit-school.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    });

    const elapsed = Date.now() - start;

    if (elapsed < 2000) {
        await new Promise(r => setTimeout(r, 2000 - elapsed));
    }

    const json = await res.json();

    btn.disabled = false;
    btn.innerHTML = 'Save School';

    if (json.success) {

        closeSchoolModal();

        // optional: refresh school search if input is open
        const input = document.getElementById('schoolSearch');
        if (input.value.trim()) {
            searchSchools(input.value);
        }

    } else {
        alert(json.error || 'Failed to save school');
    }
});

</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>