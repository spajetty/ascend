<?php
require_once __DIR__ . '/../../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Workers Hiring for Infrastructure Projects';
$pageHeading = 'Workers Hiring for Infrastructure Projects';

require_once __DIR__ . '/../../../includes/layout/head.php';
require_once __DIR__ . '/../../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 pt-6">
        <a href="/pages/programs/employers-engagement.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employers Engagement Section
        </a>
    </div>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardTotal">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Workers Hired (Total)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardMale">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Workers Hired (Male)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardFemale">—</span>
                    <div class="bg-pink-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Workers Hired (Female)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="cardProjects">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Infrastructure Projects</span>
            </div>

        </div>

        <!-- Filter + Add Button -->
        <div class="flex items-center justify-between gap-3 mb-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select id="yearFilter" onchange="loadData()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                </select>
            </div>
        </div>

        <!-- Workers Hiring Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 text-base">Workers Hiring for Infrastructure Projects</h2>
                <span id="tableTotal" class="text-sm font-semibold text-orange-500 bg-orange-100 px-3 py-1 rounded-full">— Total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium w-36">MONTH</th>
                            <th class="px-4 py-3 text-center text-gray-500 font-semibold border-l border-gray-100">PROJECT NAME</th>
                            <th class="px-4 py-3 text-center text-teal-600 font-semibold border-l border-gray-100">MALE</th>
                            <th class="px-4 py-3 text-center text-pink-500 font-semibold border-l border-gray-100">FEMALE</th>
                            <th class="px-4 py-3 text-center text-orange-500 font-semibold border-l border-gray-100">TOTAL</th>
                            <th class="px-4 py-3 text-center text-gray-400 font-semibold border-l border-gray-100">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="loadingRow">
                            <td colspan="6" class="text-center py-16 text-gray-400">
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                    </svg>
                                    <span class="text-sm">Loading data…</span>
                                </div>
                            </td>
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
<div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

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
const API = '/backend/emp-engagement/whip/show-whip.php';
const ROWS_PER_PAGE = 9;

let allRows      = [];   // full dataset from API
let currentPage  = 1;
let deletingId   = null;
let savingId     = null;
let editingData  = {};   // { whip_id: { male, female, project_name } }

// ─── Bootstrap ───────────────────────────────────────────────────────────────
async function loadData() {
    const year = document.getElementById('yearFilter').value;
    setLoading(true);

    try {
        const res  = await fetch(`${API}?year=${year}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Rebuild year dropdown (keep selection)
        const sel = document.getElementById('yearFilter');
        const cur = sel.value || year;
        sel.innerHTML = json.data.years
            .map(y => `<option value="${y}" ${y == cur ? 'selected' : ''}>${y}</option>`)
            .join('');

        allRows = json.data.rows;
        updateCards(json.data.totals);
        currentPage = 1;
        renderTable();
    } catch (err) {
        console.error(err);
    } finally {
        setLoading(false);
    }
}

function setLoading(state) {
    if (state) {
        document.getElementById('tableBody').innerHTML = `
            <tr><td colspan="6" class="text-center py-16 text-gray-400">
                <div class="flex items-center justify-center gap-3">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <span class="text-sm">Loading data…</span>
                </div>
            </td></tr>`;
    }
}

// ─── Cards ────────────────────────────────────────────────────────────────────
function updateCards(t) {
    document.getElementById('cardTotal').textContent    = t.total;
    document.getElementById('cardMale').textContent     = t.male;
    document.getElementById('cardFemale').textContent   = t.female;
    document.getElementById('cardProjects').textContent = t.projects;
    document.getElementById('tableTotal').textContent   = `${t.total} Total`;
}

// ─── Table Render ─────────────────────────────────────────────────────────────
function renderTable() {
    const tbody = document.getElementById('tableBody');

    if (!allRows.length) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-16 text-gray-400 text-sm">No entries for this year.</td></tr>`;
        updatePagination(0);
        return;
    }

    const total  = allRows.length;
    const start  = (currentPage - 1) * ROWS_PER_PAGE;
    const end    = Math.min(start + ROWS_PER_PAGE, total);
    const page   = allRows.slice(start, end);

    // Data rows
    const rowsHtml = page.map(r => `
        <tr class="border-b border-gray-50 hover:bg-gray-50 data-row" data-id="${r.whip_id}">
            <td class="px-4 py-3 text-gray-700 font-medium">${r.month} ${r.year}</td>
            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 cell-male">${r.male}</td>
            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 cell-female">${r.female}</td>
            <td class="px-4 py-3 text-center font-semibold text-orange-500 bg-orange-50 border-l border-gray-100 cell-total">${r.total}</td>
            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 cell-project">
                ${r.project_name
                    ? `<span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-lg font-medium">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                        </svg>
                        ${escHtml(r.project_name)}
                       </span>`
                    : '<span class="text-gray-300 text-xs">—</span>'}
            </td>
            <td class="px-4 py-3 text-center border-l border-gray-100">
                <div class="flex items-center justify-center gap-2 action-buttons">
                    <button onclick="toggleEditMode(${r.whip_id})" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="deleteRow(${r.whip_id})" class="text-red-400 hover:text-red-600 delete-btn" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    <button onclick="saveRow(${r.whip_id})" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                    <button onclick="cancelEdit(${r.whip_id})" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    // Totals row (always sum full allRows, not just page)
    const totM = allRows.reduce((s, r) => s + +r.male,   0);
    const totF = allRows.reduce((s, r) => s + +r.female, 0);
    const totT = totM + totF;

    tbody.innerHTML = rowsHtml + `
        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
            <td class="px-4 py-3 text-gray-800 font-bold">TOTAL</td>
            <td class="px-4 py-3 text-center font-bold text-teal-600 bg-teal-100 border-l border-gray-100">${totM}</td>
            <td class="px-4 py-3 text-center font-bold text-pink-500 bg-pink-100 border-l border-gray-100">${totF}</td>
            <td class="px-4 py-3 text-center font-bold text-orange-500 bg-orange-100 border-l border-gray-100">${totT}</td>
            <td class="px-4 py-3 border-l border-gray-100"></td>
            <td class="border-l border-gray-100"></td>
        </tr>
    `;

    updatePagination(total);
}

// ─── Pagination ───────────────────────────────────────────────────────────────
function updatePagination(total) {
    const totalPages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
    const start = total === 0 ? 0 : (currentPage - 1) * ROWS_PER_PAGE + 1;
    const end   = Math.min(currentPage * ROWS_PER_PAGE, total);

    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries' : `Showing ${start}–${end} of ${total} entries`;

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
        btn.onclick = () => { currentPage = p; renderTable(); };
        container.appendChild(btn);
    }
}

function changePage(dir) {
    const totalPages = Math.max(1, Math.ceil(allRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderTable();
}

// ─── Edit Mode ────────────────────────────────────────────────────────────────
function getRowEl(id) {
    return document.querySelector(`tr[data-id="${id}"]`);
}

function toggleEditMode(id) {
    const row = getRowEl(id);
    if (!row) return;
    if (row.classList.contains('editing')) { cancelEdit(id); return; }

    // Save original values
    editingData[id] = {
        male:         row.querySelector('.cell-male').textContent.trim(),
        female:       row.querySelector('.cell-female').textContent.trim(),
        project_name: row.querySelector('.cell-project').textContent.trim()
    };

    row.classList.add('editing', 'bg-yellow-50');

    // Make male, female, project editable
    const cellMale    = row.querySelector('.cell-male');
    const cellFemale  = row.querySelector('.cell-female');
    const cellProject = row.querySelector('.cell-project');

    [cellMale, cellFemale].forEach(c => {
        c.contentEditable = 'true';
        c.classList.add('border', 'border-yellow-300', 'bg-white');
    });

    // Replace project badge with an input
    const projVal = allRows.find(r => r.whip_id == id)?.project_name ?? '';
    cellProject.innerHTML = `<input type="text" value="${escAttr(projVal)}"
        class="edit-project-input w-full text-xs border border-yellow-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-yellow-400">`;

    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.add('hidden');
    ab.querySelector('.delete-btn').classList.add('hidden');
    ab.querySelector('.save-btn').classList.remove('hidden');
    ab.querySelector('.cancel-btn').classList.remove('hidden');
}

function cancelEdit(id) {
    const row = getRowEl(id);
    if (!row) return;
    const orig = editingData[id] || {};

    row.querySelector('.cell-male').contentEditable   = 'false';
    row.querySelector('.cell-female').contentEditable = 'false';
    row.querySelector('.cell-male').textContent       = orig.male   ?? '';
    row.querySelector('.cell-female').textContent     = orig.female ?? '';

    // Restore project cell
    const r = allRows.find(r => r.whip_id == id);
    row.querySelector('.cell-project').innerHTML = projectBadge(r?.project_name ?? '');

    [row.querySelector('.cell-male'), row.querySelector('.cell-female')].forEach(c => {
        c.classList.remove('border', 'border-yellow-300', 'bg-white');
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
    showModal('saveModal');
}

async function confirmSave() {
    const id  = savingId;
    const row = getRowEl(id);
    if (!row) return;

    const male         = parseInt(row.querySelector('.cell-male').textContent.trim())   || 0;
    const female       = parseInt(row.querySelector('.cell-female').textContent.trim()) || 0;
    const project_name = row.querySelector('.edit-project-input')?.value?.trim() ?? '';

    closeSaveModal();

    try {
        const res  = await fetch(API, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ whip_id: id, male, female, project_name })
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Update local data
        const idx = allRows.findIndex(r => r.whip_id == id);
        if (idx !== -1) {
            allRows[idx].male         = male;
            allRows[idx].female       = female;
            allRows[idx].total        = male + female;
            allRows[idx].project_name = project_name;
        }

        // Recalculate cards
        const totals = calcTotals();
        updateCards(totals);
        renderTable();

        // Flash green
        setTimeout(() => {
            const r = getRowEl(id);
            if (r) {
                r.style.transition = 'background-color 0.3s ease-out';
                r.style.backgroundColor = '#dcfce7';
                setTimeout(() => { r.style.backgroundColor = ''; r.style.transition = ''; }, 1500);
            }
        }, 50);

    } catch (err) {
        alert('Failed to save: ' + err.message);
    }
}

// ─── Delete ───────────────────────────────────────────────────────────────────
function deleteRow(id) {
    deletingId = id;
    showModal('deleteModal');
}

async function confirmDelete() {
    const id = deletingId;
    closeDeleteModal();

    try {
        const res  = await fetch(`${API}?id=${id}`, { method: 'DELETE' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        allRows = allRows.filter(r => r.whip_id != id);
        updateCards(calcTotals());

        // Go back a page if current page is now empty
        const totalPages = Math.max(1, Math.ceil(allRows.length / ROWS_PER_PAGE));
        if (currentPage > totalPages) currentPage = totalPages;
        renderTable();

    } catch (err) {
        alert('Failed to delete: ' + err.message);
    }
}

// ─── Add Modal ────────────────────────────────────────────────────────────────
function openAddModal() {
    document.getElementById('addMonth').value       = '';
    document.getElementById('addYear').value        = new Date().getFullYear();
    document.getElementById('addMale').value        = '0';
    document.getElementById('addFemale').value      = '0';
    document.getElementById('addProjectName').value = '';
    document.getElementById('addError').classList.add('hidden');
    showModal('addModal');
}

function closeAddModal() {
    hideModal('addModal');
}

async function submitAdd() {
    const month        = document.getElementById('addMonth').value;
    const year         = parseInt(document.getElementById('addYear').value);
    const male         = parseInt(document.getElementById('addMale').value)   || 0;
    const female       = parseInt(document.getElementById('addFemale').value) || 0;
    const project_name = document.getElementById('addProjectName').value.trim();

    const errEl = document.getElementById('addError');
    if (!month || !year) {
        errEl.textContent = 'Month and year are required.';
        errEl.classList.remove('hidden');
        return;
    }
    errEl.classList.add('hidden');

    const btn = document.getElementById('addSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Saving…';

    try {
        const res  = await fetch(API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ month, year, male, female, project_name })
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        closeAddModal();
        await loadData();   // reload everything fresh

    } catch (err) {
        errEl.textContent = 'Error: ' + err.message;
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Add Entry';
    }
}

// ─── Modal Helpers ────────────────────────────────────────────────────────────
function showModal(id) {
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById(id).classList.remove('hidden');
}
function closeDeleteModal() {
    hideModal('deleteModal');
    deletingId = null;
}
function closeSaveModal() {
    hideModal('saveModal');
    savingId = null;
}
function hideModal(id) {
    document.getElementById(id).classList.add('hidden');
    // hide backdrop only if no other modal is open
    const open = ['addModal','deleteModal','saveModal']
        .filter(m => !document.getElementById(m).classList.contains('hidden'));
    if (!open.length) document.getElementById('modalBackdrop').classList.add('hidden');
}

document.addEventListener('click', e => {
    if (e.target === document.getElementById('modalBackdrop')) {
        closeDeleteModal(); closeSaveModal(); closeAddModal();
    }
});

// ─── Utilities ────────────────────────────────────────────────────────────────
function calcTotals() {
    const male     = allRows.reduce((s, r) => s + +r.male,   0);
    const female   = allRows.reduce((s, r) => s + +r.female, 0);
    return { total: male + female, male, female, projects: allRows.length };
}

function projectBadge(name) {
    if (!name) return '<span class="text-gray-300 text-xs">—</span>';
    return `<span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-lg font-medium">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
        </svg>
        ${escHtml(name)}
    </span>`;
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escAttr(str) {
    return String(str).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

// ─── Init ─────────────────────────────────────────────────────────────────────
// Seed year dropdown then load
(function init() {
    const sel  = document.getElementById('yearFilter');
    const year = new Date().getFullYear();
    sel.innerHTML = `<option value="${year}">${year}</option>`;
    loadData();
})();
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>