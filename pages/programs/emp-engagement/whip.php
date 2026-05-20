<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Workers Hiring for Infrastructure Projects';
$pageHeading = 'Workers Hiring for Infrastructure Projects';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<style>
    body.modal-open { overflow: hidden; }
</style>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen w-0 md:w-auto">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-4 md:px-8 pt-6">
        <a href="/pages/programs/employers-engagement.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employers Engagement Section
        </a>
    </div>

    <div class="px-4 md:px-8 py-6 space-y-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="cardTotal" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Workers Hired (Total)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span id="cardMale" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Workers Hired (Male)</span>
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
                <span class="text-xs text-gray-500 leading-tight">Workers Hired (Female)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="cardProjects" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Infrastructure Projects</span>
            </div>

        </div>

        <!-- Filter -->
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-sm text-gray-500 whitespace-nowrap">Filter by year:</span>
            <select id="yearSelect"
                class="text-sm border border-gray-200 rounded-lg px-3 py-1.5">
            </select>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Workers Hiring for Infrastructure Projects</h2>
                <span id="tableTotal" class="text-sm font-semibold text-orange-500 bg-orange-100 px-3 py-1 rounded-full shrink-0">— Total</span>
            </div>

            <!-- Scrollable table wrapper -->
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs min-w-[500px]">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold whitespace-nowrap">MONTH</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">PROJECT NAME</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">MALE</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">FEMALE</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="loadingRow">
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">Loading...</td>
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

<!-- Project Info Modal -->
<div id="projectInfoModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-lg w-full mx-4 animate-modal max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-2.5 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900" id="projectInfoTitle">Project Details</h3>
            </div>
            <button onclick="closeProjectInfoModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="projectInfoContent" class="space-y-3 text-sm">
            <!-- Filled dynamically -->
        </div>
    </div>
</div>

<script>
const API_URL = '/backend/emp-engagement/whip/show-whip.php';
const ROWS_PER_PAGE = 10;

let allRows      = [];   // raw worker rows from API
let groupedRows  = [];   // aggregated month+project rows
let currentPage  = 1;
let deletingKey  = null; // { month_key, project_id }

// ─── Load data from API ───────────────────────────────────────────────────────
async function loadData(year) {
    document.getElementById('loadingRow').style.display = '';
    document.getElementById('tableBody').querySelectorAll('tr:not(#loadingRow)').forEach(r => r.remove());

    try {
        const url = year === 'all' ? API_URL : `${API_URL}?year=${year}`;
        const res  = await fetch(url);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        const { rows, totals } = json.data;

        // Update summary cards
        document.getElementById('cardTotal').textContent    = totals.total;
        document.getElementById('cardMale').textContent     = totals.male;
        document.getElementById('cardFemale').textContent   = totals.female;
        document.getElementById('cardProjects').textContent = totals.projects;
        document.getElementById('tableTotal').textContent   = totals.total + ' Total';

        allRows = rows;
        buildGrouped();
        currentPage = 1;
        renderPage();

    } catch (err) {
        document.getElementById('loadingRow').innerHTML =
            `<td colspan="5" class="px-4 py-8 text-center text-red-500 text-sm">Error: ${err.message}</td>`;
    }
}

// ─── Group raw rows into month+project buckets ────────────────────────────────
function buildGrouped() {
    const map = new Map();

    allRows.forEach(r => {
        if (!r.date_hired) return;
        const d   = new Date(r.date_hired);
        const key = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}_${r.project_id || 'none'}`;

        if (!map.has(key)) {
            map.set(key, {
                key,
                year:        d.getFullYear(),
                month:       d.getMonth() + 1,
                month_label: d.toLocaleDateString('en-PH', { year: 'numeric', month: 'long' }),
                project_id:    r.project_id   || null,
                project_title: r.project_title || null,
                // project detail fields (from first encountered row)
                nature_of_project:      r.nature_of_project,
                duration:               r.duration,
                budget:                 r.budget,
                fund_source:            r.fund_source,
                persons_from_locality:  r.persons_from_locality,
                skills_required:        r.skills_required,
                skills_deficiencies:    r.skills_deficiencies,
                contractor:             r.contractor,
                is_legitimate_contractor: r.is_legitimate_contractor,
                filled:   r.filled,
                unfilled: r.unfilled,
                male:   0,
                female: 0,
            });
        }

        const g   = map.get(key);
        const sex = (r.sex || '').toLowerCase();
        if (sex === 'male')   g.male++;
        if (sex === 'female') g.female++;
    });

    // Sort: newest month first, then by project title
    groupedRows = Array.from(map.values()).sort((a, b) => {
        if (b.year !== a.year) return b.year - a.year;
        if (b.month !== a.month) return b.month - a.month;
        return (a.project_title || '').localeCompare(b.project_title || '');
    });
}

// ─── Render current page ──────────────────────────────────────────────────────
function renderPage() {
    const tbody = document.getElementById('tableBody');
    const total = groupedRows.length;
    const pages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end   = Math.min(start + ROWS_PER_PAGE, total);
    const slice = groupedRows.slice(start, end);

    Array.from(tbody.querySelectorAll('tr:not(#loadingRow)')).forEach(r => r.remove());
    document.getElementById('loadingRow').style.display = 'none';

    if (slice.length === 0) {
        tbody.insertAdjacentHTML('beforeend',
            `<tr><td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">No entries found.</td></tr>`);
    } else {
        slice.forEach(g => tbody.insertAdjacentHTML('beforeend', buildRow(g)));
    }

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
            (p === currentPage
                ? 'bg-teal-500 text-white border-teal-500'
                : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

function escHtml(str) {
    return String(str || '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function buildRow(g) {
    const projectData = encodeURIComponent(JSON.stringify({
        project_id:               g.project_id,
        project_title:            g.project_title,
        nature_of_project:        g.nature_of_project,
        duration:                 g.duration,
        budget:                   g.budget,
        fund_source:              g.fund_source,
        persons_from_locality:    g.persons_from_locality,
        skills_required:          g.skills_required,
        skills_deficiencies:      g.skills_deficiencies,
        contractor:               g.contractor,
        is_legitimate_contractor: g.is_legitimate_contractor,
        filled:                   g.filled,
        unfilled:                 g.unfilled,
    }));

    const deleteKey = encodeURIComponent(JSON.stringify({
        month_key:  g.key,
        project_id: g.project_id,
        year:       g.year,
        month:      g.month,
    }));

    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50">

        <!-- MONTH -->
        <td class="px-4 py-3 text-gray-700 font-medium whitespace-nowrap">
            ${escHtml(g.month_label)}
        </td>

        <!-- PROJECT NAME -->
        <td class="px-4 py-3 border-l border-gray-100">
            <div class="flex items-center gap-1.5">
                ${g.project_title
                    ? `<span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-lg font-medium max-w-[200px] truncate"
                            title="${escHtml(g.project_title)}">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                            </svg>
                            ${escHtml(g.project_title)}
                       </span>`
                    : '<span class="text-gray-400 text-xs">—</span>'}
                ${g.project_id
                    ? `<button onclick='openProjectInfo(JSON.parse(decodeURIComponent("${projectData}")))'
                            class="shrink-0 text-blue-400 hover:text-blue-600 transition-colors"
                            title="View project details">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                       </button>`
                    : ''}
            </div>
        </td>

        <!-- MALE -->
        <td class="px-4 py-3 text-center border-l border-gray-100">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                ${g.male}
            </span>
        </td>

        <!-- FEMALE -->
        <td class="px-4 py-3 text-center border-l border-gray-100">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-pink-100 text-pink-600">
                ${g.female}
            </span>
        </td>

        <!-- ACTIONS -->
        <td class="px-4 py-3 text-center border-l border-gray-100">
            <button onclick="deleteGroup('${deleteKey}')"
                class="text-red-400 hover:text-red-600 transition-colors"
                title="Delete all entries for this month/project">
                🗑️
            </button>
        </td>

    </tr>`;
}

// ─── Project Info Modal ───────────────────────────────────────────────────────
function openProjectInfo(data) {
    document.getElementById('projectInfoTitle').textContent = data.project_title || 'Project Details';

    const fmt       = (val) => (val !== null && val !== undefined && val !== '') ? val : '—';
    const fmtBudget = (val) => val ? '₱' + parseFloat(val).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : '—';
    const fmtBool   = (val) => val == 1
        ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Yes</span>`
        : `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">No</span>`;

    document.getElementById('projectInfoContent').innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            <div class="sm:col-span-2 bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Project Title</p>
                <p class="text-gray-800 font-medium">${escHtml(fmt(data.project_title))}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Nature of Project</p>
                <p class="text-gray-700">${escHtml(fmt(data.nature_of_project))}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Duration</p>
                <p class="text-gray-700">${escHtml(fmt(data.duration))}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Budget</p>
                <p class="text-gray-700 font-medium">${fmtBudget(data.budget)}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Fund Source</p>
                <p class="text-gray-700">${escHtml(fmt(data.fund_source))}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Contractor</p>
                <p class="text-gray-700">${escHtml(fmt(data.contractor))}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-3 flex items-center justify-between">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Legitimate Contractor</p>
                ${fmtBool(data.is_legitimate_contractor)}
            </div>

            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Persons from Locality</p>
                <p class="text-gray-700">${fmt(data.persons_from_locality)}</p>
            </div>

            <div class="bg-teal-50 rounded-xl p-3 flex items-center justify-between">
                <p class="text-xs text-teal-600 font-semibold uppercase tracking-wide">Slots Filled</p>
                <span class="text-teal-700 font-bold">${fmt(data.filled)}</span>
            </div>

            <div class="bg-orange-50 rounded-xl p-3 flex items-center justify-between">
                <p class="text-xs text-orange-500 font-semibold uppercase tracking-wide">Slots Unfilled</p>
                <span class="text-orange-600 font-bold">${fmt(data.unfilled)}</span>
            </div>

            ${data.skills_required ? `
            <div class="sm:col-span-2 bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Skills Required</p>
                <p class="text-gray-700">${escHtml(data.skills_required)}</p>
            </div>` : ''}

            ${data.skills_deficiencies ? `
            <div class="sm:col-span-2 bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Skills Deficiencies</p>
                <p class="text-gray-700">${escHtml(data.skills_deficiencies)}</p>
            </div>` : ''}

        </div>
    `;

    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('projectInfoModal').classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function closeProjectInfoModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('projectInfoModal').classList.add('hidden');
    document.body.classList.remove('modal-open');
}

// ─── Delete (group) ───────────────────────────────────────────────────────────
function deleteGroup(encodedKey) {
    deletingKey = JSON.parse(decodeURIComponent(encodedKey));
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('deleteModal').classList.add('hidden');
    deletingKey = null;
}

async function confirmDelete() {
    // Placeholder — wire up logic later
    closeDeleteModal();
}

// ─── Pagination ───────────────────────────────────────────────────────────────
function changePage(dir) {
    const pages = Math.max(1, Math.ceil(groupedRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, pages));
    renderPage();
}

// ─── Backdrop click ───────────────────────────────────────────────────────────
document.addEventListener('click', (e) => {
    if (e.target === document.getElementById('modalBackdrop')) {
        closeDeleteModal();
        closeProjectInfoModal();
    }
});

// ─── Year change ──────────────────────────────────────────────────────────────
document.getElementById('yearSelect').addEventListener('change', function () {
    loadData(this.value);
});

// ─── Init ─────────────────────────────────────────────────────────────────────
async function initializePage() {
    try {
        const res  = await fetch(API_URL);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        const years = json.data.years || [];
        const sel   = document.getElementById('yearSelect');

        const allOpt = document.createElement('option');
        allOpt.value = 'all';
        allOpt.textContent = 'All Years';
        allOpt.selected = true;
        sel.appendChild(allOpt);

        years.forEach(y => {
            const opt = document.createElement('option');
            opt.value = y;
            opt.textContent = y;
            sel.appendChild(opt);
        });

        await loadData('all');

    } catch (err) {
        console.error(err);
    }
}

initializePage();
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>