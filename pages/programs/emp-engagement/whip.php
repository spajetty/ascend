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

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select id="yearFilter" onchange="loadData()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                </select>
            </div>
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search worker or project..."
                    oninput="applyFilters()"
                    class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300" />
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Sex:</span>
                <select id="filterSex" onchange="applyFilters()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
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
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">MONTH</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">WORKER NAME</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-medium border-l border-gray-100">SEX</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium border-l border-gray-100">POSITION</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium border-l border-gray-100">PROJECT</th>
                            <th class="text-center px-4 py-3 text-gray-500 font-medium border-l border-gray-100">DATE HIRED</th>
                            <th class="text-center px-4 py-3 text-gray-400 font-medium border-l border-gray-100">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="loadingRow">
                            <td colspan="7" class="text-center py-16 text-gray-400">
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
        <p class="text-gray-600 mb-6">Are you sure you want to remove this worker from the project? This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
            <button onclick="confirmDelete()" class="flex-1 px-4 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600">Delete</button>
        </div>
    </div>
</div>

<script>
const API = '/backend/emp engagement/show-whip.php';
const ROWS_PER_PAGE = 15;

let allRows      = [];
let filteredRows = [];
let currentPage  = 1;
let deletingId   = null;

// ─── Load Data ────────────────────────────────────────────────────────────────
async function loadData() {
    const year = document.getElementById('yearFilter').value || new Date().getFullYear();
    document.getElementById('tableBody').innerHTML =
        `<tr><td colspan="7" class="text-center py-16 text-gray-400 text-sm">Loading…</td></tr>`;

    try {
        const res  = await fetch(`${API}?year=${year}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        // Rebuild year dropdown
        const sel = document.getElementById('yearFilter');
        const cur = sel.value;
        sel.innerHTML = json.data.years
            .map(y => `<option value="${y}" ${y == year ? 'selected' : ''}>${y}</option>`)
            .join('');
        if (cur && json.data.years.includes(parseInt(cur))) sel.value = cur;

        allRows = json.data.rows;
        updateCards(json.data.totals);
        applyFilters();

    } catch (err) {
        document.getElementById('tableBody').innerHTML =
            `<tr><td colspan="7" class="text-center py-10 text-red-400 text-sm">Failed to load: ${err.message}</td></tr>`;
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

// ─── Filters ─────────────────────────────────────────────────────────────────
function applyFilters() {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const sex   = document.getElementById('filterSex').value.toLowerCase().trim();

    filteredRows = allRows.filter(r => {
        const nameMatch    = !query || (r.full_name    || '').toLowerCase().includes(query)
                                    || (r.project_title|| '').toLowerCase().includes(query)
                                    || (r.position     || '').toLowerCase().includes(query);
        const sexMatch     = !sex   || (r.sex          || '').toLowerCase() === sex;
        return nameMatch && sexMatch;
    });

    currentPage = 1;
    renderTable();
}

// ─── Table Render ─────────────────────────────────────────────────────────────
function renderTable() {
    const tbody = document.getElementById('tableBody');
    const total = filteredRows.length;

    if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-16 text-gray-400 text-sm">No entries found.</td></tr>`;
        updatePagination(0);
        return;
    }

    const start   = (currentPage - 1) * ROWS_PER_PAGE;
    const end     = Math.min(start + ROWS_PER_PAGE, total);
    const pageRows = filteredRows.slice(start, end);

    tbody.innerHTML = pageRows.map(r => {
        const sexBadge = r.sex
            ? (r.sex.toLowerCase() === 'male'
                ? `<span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">Male</span>`
                : `<span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-pink-100 text-pink-600">Female</span>`)
            : '<span class="text-gray-300">—</span>';

        const dateHired = r.date_hired
            ? new Date(r.date_hired).toLocaleDateString('en-PH', { year:'numeric', month:'short', day:'numeric' })
            : '—';

        return `
        <tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-4 py-3 text-gray-700 font-medium whitespace-nowrap">${r.month_name} ${r.year}</td>
            <td class="px-4 py-3 text-gray-800 font-medium">${escHtml(r.full_name)}</td>
            <td class="px-4 py-3 text-center border-l border-gray-100">${sexBadge}</td>
            <td class="px-4 py-3 text-gray-600 border-l border-gray-100">${escHtml(r.position || '—')}</td>
            <td class="px-4 py-3 border-l border-gray-100">
                ${r.project_title
                    ? `<span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-lg font-medium">
                           <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                           </svg>
                           ${escHtml(r.project_title)}
                       </span>`
                    : '<span class="text-gray-300">—</span>'}
            </td>
            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100 whitespace-nowrap">${dateHired}</td>
            <td class="px-4 py-3 text-center border-l border-gray-100">
                <button onclick="deleteRow(${r.whip_id})" class="text-red-400 hover:text-red-600" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        </tr>`;
    }).join('');

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
    const totalPages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderTable();
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
    if (!deletingId) return;
    try {
        const res  = await fetch(`${API}?id=${deletingId}`, { method: 'DELETE' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error);

        allRows      = allRows.filter(r => r.whip_id != deletingId);
        filteredRows = filteredRows.filter(r => r.whip_id != deletingId);

        const totals = {
            total:    allRows.length,
            male:     allRows.filter(r => (r.sex||'').toLowerCase() === 'male').length,
            female:   allRows.filter(r => (r.sex||'').toLowerCase() === 'female').length,
            projects: new Set(allRows.map(r => r.project_id)).size,
        };
        updateCards(totals);

        const totalPages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));
        if (currentPage > totalPages) currentPage = totalPages;
        renderTable();

    } catch (err) {
        alert('Failed to delete: ' + err.message);
    } finally {
        closeDeleteModal();
    }
}

document.getElementById('modalBackdrop').addEventListener('click', closeDeleteModal);

// ─── Helpers ─────────────────────────────────────────────────────────────────
function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── Init ─────────────────────────────────────────────────────────────────────
(function init() {
    const sel  = document.getElementById('yearFilter');
    const year = new Date().getFullYear();
    sel.innerHTML = `<option value="${year}">${year}</option>`;
    loadData();
})();
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>