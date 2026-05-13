<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – LMI Orientation';
$pageHeading = 'LMI Orientation';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 pt-6">
        <a href="/pages/programs/career-development.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Career Development Section
        </a>
    </div>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span id="cardSessions" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">LMI Orientations</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="cardTotal" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">LMI Participants (Total)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-cyan-400">
                <div class="flex items-center justify-between">
                    <span id="cardMale" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-cyan-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">LMI Participants (Male)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <span id="cardFemale" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-pink-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">LMI Participants (Female)</span>
            </div>

        </div>

        <!-- Filter -->
        <div class="flex items-center gap-3 mb-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select id="yearSelect"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                </select>
            </div>
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchSchool" placeholder="Search school / event..."
                    oninput="applyFilters()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"/>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 text-base">LMI Orientation</h2>
                <span id="tableTotal" class="text-sm font-semibold text-blue-600 bg-blue-100 px-3 py-1 rounded-full">— Total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium w-40">DATE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold border-l border-gray-100">SCHOOL / INSTITUTION / EVENT</th>
                            <th class="px-4 py-3 text-center text-cyan-500 font-semibold border-l border-gray-100">MALE</th>
                            <th class="px-4 py-3 text-center text-pink-500 font-semibold border-l border-gray-100">FEMALE</th>
                            <th class="px-4 py-3 text-center text-blue-600 font-semibold border-l border-gray-100">TOTAL</th>
                            <th class="px-4 py-3 text-center text-gray-400 font-semibold border-l border-gray-100">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="loadingRow">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">Loading...</td>
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
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>&rsaquo;</button>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40 modal-backdrop"></div>

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
const API_URL = '/api/lmi-api.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];
let filteredRows = [];
let currentPage  = 1;
let deletingId   = null;
let savingId     = null;
let editingData  = {};

// ─── Helpers ──────────────────────────────────────────────────────────────────
function fmt(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

function num(n) {
    return Number(n).toLocaleString();
}

// ─── Load data from API ───────────────────────────────────────────────────────
async function loadData(year) {
    document.getElementById('loadingRow').style.display = '';
    Array.from(document.getElementById('tableBody').querySelectorAll('tr:not(#loadingRow)')).forEach(r => r.remove());

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
        !query || (r.school || '').toLowerCase().includes(query)
    );
    currentPage = 1;
    renderPage();
}

function renderPage() {
    const tbody = document.getElementById('tableBody');
    const total = filteredRows.length;
    const pages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end   = Math.min(start + ROWS_PER_PAGE, total);
    const slice = filteredRows.slice(start, end);

    // Clear existing rows except loading row
    Array.from(tbody.querySelectorAll('tr:not(#loadingRow)')).forEach(r => r.remove());
    document.getElementById('loadingRow').style.display = 'none';

    if (slice.length === 0) {
        tbody.insertAdjacentHTML('beforeend',
            `<tr><td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">No entries found.</td></tr>`);
    } else {
        slice.forEach(row => tbody.insertAdjacentHTML('beforeend', buildRow(row)));
    }

    // Total row
    const totM = filteredRows.reduce((s, r) => s + Number(r.lmi_m), 0);
    const totF = filteredRows.reduce((s, r) => s + Number(r.lmi_f), 0);
    tbody.insertAdjacentHTML('beforeend', `
        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
            <td class="px-4 py-3 text-gray-800 font-bold">TOTAL</td>
            <td class="px-4 py-3 border-l border-gray-100"></td>
            <td class="px-4 py-3 text-center font-bold text-cyan-500 bg-cyan-100 border-l border-gray-100">${num(totM)}</td>
            <td class="px-4 py-3 text-center font-bold text-pink-500 bg-pink-100 border-l border-gray-100">${num(totF)}</td>
            <td class="px-4 py-3 text-center font-bold text-blue-600 bg-blue-100 border-l border-gray-100">${num(totM + totF)}</td>
            <td class="border-l border-gray-100"></td>
        </tr>
    `);

    // Pagination
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
    const id    = r.lmi_id;
    const total = Number(r.lmi_m) + Number(r.lmi_f);
    return `
    <tr class="border-b border-gray-50 hover:bg-gray-50" data-id="${id}" data-school="${(r.school || '').toLowerCase()}">
        <td class="px-4 py-3 text-gray-700 font-medium date-cell">${fmt(r.date)}</td>
        <td class="px-4 py-3 text-gray-600 border-l border-gray-100 school-cell">${r.school || '—'}</td>
        <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 male-cell">${num(r.lmi_m)}</td>
        <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 female-cell">${num(r.lmi_f)}</td>
        <td class="px-4 py-3 text-center font-semibold text-blue-600 bg-blue-50 border-l border-gray-100 total-cell">${num(total)}</td>
        <td class="px-4 py-3 text-center border-l border-gray-100">
            <div class="flex items-center justify-center gap-2 action-buttons">
                <button onclick="toggleEditMode('${id}')" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="deleteRow('${id}')" class="text-red-400 hover:text-red-600 delete-btn" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <button onclick="saveRow('${id}')" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button onclick="cancelEdit('${id}')" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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

    const rec = allRows.find(r => String(r.lmi_id) === String(id));
    editingData[id] = { date: rec.date, school: rec.school, lmi_m: rec.lmi_m, lmi_f: rec.lmi_f };

    // Date cell → input[date]
    row.querySelector('.date-cell').innerHTML =
        `<input type="date" value="${rec.date}" id="edit-date-${id}"
            class="border border-yellow-300 rounded px-2 py-1 text-xs w-36 bg-white focus:outline-none focus:ring-1 focus:ring-teal-400">`;

    // School, male, female → contentEditable
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
        const total = Number(backup.lmi_m) + Number(backup.lmi_f);
        row.querySelector('.date-cell').innerHTML    = fmt(backup.date);
        row.querySelector('.school-cell').textContent = backup.school || '—';
        row.querySelector('.male-cell').textContent   = num(backup.lmi_m);
        row.querySelector('.female-cell').textContent = num(backup.lmi_f);
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
            body: JSON.stringify({ lmi_id: id, date: newDate, school: newSchool, lmi_m: newMale, lmi_f: newFemale })
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Update local data
        const rec = allRows.find(r => String(r.lmi_id) === String(id));
        if (rec) {
            rec.date  = newDate;
            rec.school = newSchool;
            rec.lmi_m  = newMale;
            rec.lmi_f  = newFemale;
            rec.total  = newMale + newFemale;
        }

        // Re-render cells
        const total = newMale + newFemale;
        row.querySelector('.date-cell').innerHTML    = fmt(newDate);
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

        allRows = allRows.filter(r => String(r.lmi_id) !== String(id));
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