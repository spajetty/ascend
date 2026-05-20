<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employers Accreditation';
$pageHeading = 'Employers Accreditation';

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
                    <span id="cardTotalEmployers" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Total Employers</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="cardNew" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">New Accreditations</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="cardRenewed" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight">Renewed Accreditations</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span id="cardActive" class="text-xl md:text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500 leading-tight active-year-label">Active Employers</span>
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

            <!-- Row 2: Search + Establishment Type on same line -->
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text"
                        id="searchCompany"
                        placeholder="Search company..."
                        oninput="applyFilters()"
                        class="w-full pl-4 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg"/>
                </div>

                <select id="filterEstType" onchange="applyFilters()"
                    class="shrink-0 text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white">
                    <option value="">All Establishment Types</option>
                    <option value="corporation">Corporation</option>
                    <option value="manpower">Manpower</option>
                    <option value="direct">Direct</option>
                    <option value="direct (overseas)">Direct (Overseas)</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Employers Accreditation</h2>
                <span id="tableTotal" class="text-sm font-semibold text-teal-600 bg-teal-100 px-3 py-1 rounded-full shrink-0">— Total</span>
            </div>

            <!-- Scrollable table wrapper -->
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs min-w-[700px]">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold whitespace-nowrap">MONTH</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">ACCREDITATION</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">COMPANY</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap hidden md:table-cell">ESTABLISHMENT TYPE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap hidden lg:table-cell">INDUSTRY</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap hidden sm:table-cell">CITY/MUNICIPALITY</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-semibold border-l border-gray-100 whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="loadingRow">
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">Loading...</td>
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

<script>
const API_URL = '/backend/emp-engagement/emp-accred/show-employers.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];
let filteredRows = [];
let currentPage  = 1;
let deletingId   = null;
let savingId     = null;
let editingData  = {};

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
        document.getElementById('cardTotalEmployers').textContent = totals.total;
        document.getElementById('cardNew').textContent            = totals.new;
        document.getElementById('cardRenewed').textContent        = totals.renewed;
        document.getElementById('cardActive').textContent         = totals.active;
        document.getElementById('tableTotal').textContent         = totals.total + ' Total';
        document.querySelectorAll('.active-year-label').forEach(el => {
            el.textContent = `Active Employers (${year})`;
        });

        allRows = rows;
        document.getElementById('searchCompany').value = '';
        document.getElementById('filterEstType').value = '';
        applyFilters();

    } catch (err) {
        document.getElementById('loadingRow').innerHTML =
            `<td colspan="7" class="px-4 py-8 text-center text-red-500 text-sm">Error: ${err.message}</td>`;
    }
}

// ─── Filter + Render ──────────────────────────────────────────────────────────
function applyFilters() {
    const query   = document.getElementById('searchCompany').value.toLowerCase().trim();
    const estType = document.getElementById('filterEstType').value.toLowerCase().trim();

    filteredRows = allRows.filter(r => {
        const companyMatch = !query   || (r.company_name || '').toLowerCase().includes(query);
        const estTypeMatch = !estType || (r.est_type || '').toLowerCase() === estType;
        return companyMatch && estTypeMatch;
    });
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

    Array.from(tbody.querySelectorAll('tr:not(#loadingRow)')).forEach(r => r.remove());
    document.getElementById('loadingRow').style.display = 'none';

    if (slice.length === 0) {
        tbody.insertAdjacentHTML('beforeend',
            `<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">No entries found.</td></tr>`);
    } else {
        slice.forEach(row => tbody.insertAdjacentHTML('beforeend', buildRow(row)));
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
            (p === currentPage ? 'bg-teal-500 text-white border-teal-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50');
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

function accreditationBadge(val) {
    if (val === 'new')
        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">New</span>`;
    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Renew</span>`;
}

function estTypeBadge(type) {
    const t = (type || '').toLowerCase().trim();

    if (!t || t === 'null') {
        return `<span class="text-gray-400">—</span>`;
    }

    if (t.includes('corporation')) {
        return `<span class="font-medium text-purple-500">Corporation</span>`;
    }

    if (t.includes('manpower')) {
        return `<span class="font-medium text-blue-500">Manpower</span>`;
    }

    if (t.includes('overseas')) {
        return `<span class="font-medium text-teal-500">Direct (Overseas)</span>`;
    }

    if (t.includes('direct')) {
        return `<span class="font-medium text-green-500">Direct</span>`;
    }

    return `<span class="font-medium text-gray-500">${type}</span>`;
}

const MONTH_NAMES = ['January','February','March','April','May','June',
                     'July','August','September','October','November','December'];

function buildRow(r) {
    const id        = r.accreditation_id;
    const monthName =
        typeof r.month === 'number'
            ? MONTH_NAMES[r.month - 1]
            : r.month || '—';

    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50"
        data-id="${id}">

        <td class="px-4 py-3 text-gray-700 font-medium month-cell">
            ${monthName} ${r.year}
        </td>

        <td class="px-4 py-3 border-l border-gray-100 accreditation-cell">
            ${accreditationBadge(r.accreditation)}
        </td>

        <td class="px-4 py-3 text-gray-600 border-l border-gray-100 company-cell">
            ${r.company_name || '—'}
        </td>

        <td class="px-4 py-3 border-l border-gray-100 hidden md:table-cell esttype-cell">
            ${estTypeBadge(r.est_type)}
        </td>

        <td class="px-4 py-3 text-gray-600 border-l border-gray-100 hidden lg:table-cell industry-cell">
            ${r.industry || '—'}
        </td>

        <td class="px-4 py-3 text-gray-600 border-l border-gray-100 hidden sm:table-cell city-cell">
            ${r.city || '—'}
        </td>

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

    const rec = allRows.find(r => String(r.accreditation_id) === String(id));
    editingData[id] = {
        month:        rec.month,
        year:         rec.year,
        status:       rec.accreditation,
        company_name: rec.company_name,
        est_type:     rec.est_type,
        industry:     rec.industry,
        city:         rec.city,
    };

    // Month/year — inline selects
    const currentMonth =
    typeof rec.month === 'number'
        ? rec.month
        : MONTH_NAMES.indexOf(rec.month) + 1;
    const monthCell = row.querySelector('.month-cell');
    const monthOpts = MONTH_NAMES.map((m, i) =>
        `<option value="${i + 1}" ${currentMonth == i + 1 ? 'selected' : ''}>`
    ).join('');
    monthCell.innerHTML = `
        <select id="edit-month-${id}" class="border border-yellow-300 rounded px-1 py-1 text-xs bg-white focus:outline-none focus:ring-1 focus:ring-teal-400">
            ${monthOpts}
        </select>
        <input type="number" id="edit-year-${id}" value="${rec.year}" min="2000" max="2099"
            class="border border-yellow-300 rounded px-2 py-1 text-xs w-16 bg-white focus:outline-none focus:ring-1 focus:ring-teal-400 ml-1">`;

    // Accreditation select
    const accCell = row.querySelector('.accreditation-cell');
    accCell.innerHTML = `
        <select id="edit-status-${id}" class="border border-yellow-300 rounded px-2 py-1 text-xs bg-white focus:outline-none focus:ring-1 focus:ring-teal-400">
            <option value="new"   ${rec.accreditation === 'new'   ? 'selected' : ''}>New</option>
            <option value="renew" ${rec.accreditation === 'renew' ? 'selected' : ''}>Renew</option>
        </select>`;

    // Est type select
    const estCell = row.querySelector('.esttype-cell');
    if (estCell) {
        const types = ['Corporation','Manpower','Direct','Direct (Overseas)'];
        const typeOpts = types.map(t =>
            `<option value="${t}" ${rec.est_type === t ? 'selected' : ''}>${t}</option>`
        ).join('');
        estCell.innerHTML = `
            <select id="edit-esttype-${id}" class="border border-yellow-300 rounded px-2 py-1 text-xs bg-white focus:outline-none focus:ring-1 focus:ring-teal-400">
                ${typeOpts}
            </select>`;
    }

    // Company, industry, city — contentEditable
    ['company-cell', 'industry-cell', 'city-cell'].forEach(cls => {
        const cell = row.querySelector('.' + cls);
        if (!cell) return;
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
        const monthName = MONTH_NAMES[(backup.month - 1)] ?? '—';
        row.querySelector('.month-cell').innerHTML        = `${monthName} ${backup.year}`;
        row.querySelector('.accreditation-cell').innerHTML = accreditationBadge(backup.status);
        row.querySelector('.company-cell').textContent    = backup.company_name || '—';
        const estCell = row.querySelector('.esttype-cell');
        if (estCell) estCell.innerHTML = estTypeBadge(backup.est_type);
        const indCell = row.querySelector('.industry-cell');
        if (indCell) indCell.textContent = backup.industry || '—';
        const cityCell = row.querySelector('.city-cell');
        if (cityCell) cityCell.textContent = backup.city || '—';
    }
    ['company-cell', 'industry-cell', 'city-cell'].forEach(cls => {
        const cell = row.querySelector('.' + cls);
        if (!cell) return;
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

    const newMonth   = parseInt(document.getElementById(`edit-month-${id}`)?.value)  || editingData[id]?.month;
    const newYear    = parseInt(document.getElementById(`edit-year-${id}`)?.value)   || editingData[id]?.year;
    const newStatus  = document.getElementById(`edit-status-${id}`)?.value           || editingData[id]?.accreditation;
    const newEstType = document.getElementById(`edit-esttype-${id}`)?.value          || editingData[id]?.est_type;
    const newCompany = row.querySelector('.company-cell').textContent.trim();
    const newIndustry = row.querySelector('.industry-cell')?.textContent.trim() || '';
    const newCity    = row.querySelector('.city-cell')?.textContent.trim() || '';

    try {
        const res  = await fetch(API_URL, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                accreditation_id: id,
                month:            newMonth,
                year:             newYear,
                status:           newStatus,
                est_type:         newEstType,
                company_name:     newCompany,
                industry:         newIndustry,
                city:             newCity,
            })
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Update local data
        const rec = allRows.find(r => String(r.accreditation_id) === String(id));
        if (rec) {
            rec.month = newMonth; rec.year = newYear; rec.accreditation = newStatus;
            rec.est_type = newEstType; rec.company_name = newCompany;
            rec.industry = newIndustry; rec.city = newCity;
        }

        // Re-render cells
        const monthName = MONTH_NAMES[(newMonth - 1)] ?? '—';
        row.querySelector('.month-cell').innerHTML         = `${monthName} ${newYear}`;
        row.querySelector('.accreditation-cell').innerHTML = accreditationBadge(newStatus);
        row.querySelector('.company-cell').textContent     = newCompany;
        const estCell = row.querySelector('.esttype-cell');
        if (estCell) estCell.innerHTML = estTypeBadge(newEstType);
        const indCell = row.querySelector('.industry-cell');
        if (indCell) indCell.textContent = newIndustry || '—';
        const cityCell = row.querySelector('.city-cell');
        if (cityCell) cityCell.textContent = newCity || '—';

        ['company-cell', 'industry-cell', 'city-cell'].forEach(cls => {
            const cell = row.querySelector('.' + cls);
            if (!cell) return;
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

        allRows = allRows.filter(r => String(r.accreditation_id) !== String(id));
        applyFilters();

    } catch (err) {
        alert('Delete failed: ' + err.message);
    }
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
        closeDeleteModal(); closeSaveModal();
    }
});

// ─── Year change ──────────────────────────────────────────────────────────────
document.getElementById('yearSelect').addEventListener('change', function () {
    loadData(parseInt(this.value));
});

// ─── Init ─────────────────────────────────────────────────────────────────────
loadData(new Date().getFullYear());
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>