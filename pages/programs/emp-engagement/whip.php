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

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <!-- Workers Hired (Male) -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">165</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Workers Hired (Male)</span>
            </div>

            <!-- Workers Hired (Female) -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-pink-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">70</span>
                    <div class="bg-pink-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Workers Hired (Female)</span>
            </div>

            <!-- Infrastructure Projects -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">3</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Infrastructure Projects</span>
            </div>

        </div>

        <!-- Filter -->
        <div class="flex items-center gap-3 mb-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter by year:</span>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300">
                    <option>2026</option>
                    <option>2025</option>
                    <option>2024</option>
                </select>
            </div>
        </div>

        <!-- Workers Hiring Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 text-base">Workers Hiring for Infrastructure Projects</h2>
                <span class="text-sm font-semibold text-orange-500 bg-orange-100 px-3 py-1 rounded-full">235 Total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium w-36">MONTH</th>
                            <th class="px-4 py-3 text-center text-teal-600 font-semibold border-l border-gray-100">MALE</th>
                            <th class="px-4 py-3 text-center text-pink-500 font-semibold border-l border-gray-100">FEMALE</th>
                            <th class="px-4 py-3 text-center text-orange-500 font-semibold border-l border-gray-100">TOTAL</th>
                            <th class="px-4 py-3 text-center text-gray-500 font-semibold border-l border-gray-100">PROJECTS</th>
                            <th class="px-4 py-3 text-center text-gray-400 font-semibold border-l border-gray-100">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- January 2026 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700 font-medium">January 2026</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">48</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">22</td>
                            <td class="px-4 py-3 text-center font-semibold text-orange-500 bg-orange-50 border-l border-gray-100">70</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-lg font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                    </svg>
                                    Project Name
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="1">
                                    <button onclick="toggleEditMode(1)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteRow(event, 1)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button onclick="saveRow(event, 1)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button onclick="cancelEdit(event, 1)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- February 2026 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700 font-medium">February 2026</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">62</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">18</td>
                            <td class="px-4 py-3 text-center font-semibold text-orange-500 bg-orange-50 border-l border-gray-100">80</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-lg font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                    </svg>
                                    Project Name
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="2">
                                    <button onclick="toggleEditMode(2)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteRow(event, 2)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button onclick="saveRow(event, 2)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button onclick="cancelEdit(event, 2)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- March 2026 -->
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700 font-medium">March 2026</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">55</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">30</td>
                            <td class="px-4 py-3 text-center font-semibold text-orange-500 bg-orange-50 border-l border-gray-100">85</td>
                            <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-lg font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                    </svg>
                                    Project Name
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2 action-buttons" data-row="3">
                                    <button onclick="toggleEditMode(3)" class="text-yellow-500 hover:text-yellow-600 edit-btn" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteRow(event, 3)" class="text-red-400 hover:text-red-600 delete-btn" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button onclick="saveRow(event, 3)" class="text-green-500 hover:text-green-600 save-btn hidden" title="Save">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button onclick="cancelEdit(event, 3)" class="text-gray-400 hover:text-gray-600 cancel-btn hidden" title="Cancel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- TOTAL Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200 total-row">
                            <td class="px-4 py-3 text-gray-800 font-bold">TOTAL</td>
                            <td class="px-4 py-3 text-center font-bold text-teal-600 bg-teal-100 border-l border-gray-100">165</td>
                            <td class="px-4 py-3 text-center font-bold text-pink-500 bg-pink-100 border-l border-gray-100">70</td>
                            <td class="px-4 py-3 text-center font-bold text-orange-500 bg-orange-100 border-l border-gray-100">235</td>
                            <td class="px-4 py-3 text-center border-l border-gray-100"></td>
                            <td class="border-l border-gray-100"></td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl">
                <span class="text-sm text-gray-500" id="paginationInfo">Showing 1–3 of 3 entries</span>
                <div class="flex items-center gap-1">
                    <button onclick="changePage(-1)" id="prevBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>
                        &lsaquo;
                    </button>
                    <div id="pageNumbers" class="flex items-center gap-1"></div>
                    <button onclick="changePage(1)" id="nextBtn"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">
                        &rsaquo;
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

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
const ROWS_PER_PAGE = 9;
let currentPage = 1;
let deletingRowId = null;
let savingRowId = null;
const editingData = {};

function getAllDataRows() {
    return Array.from(document.querySelectorAll('tbody tr:not(.total-row)'));
}

function getTotalPages() {
    return Math.max(1, Math.ceil(getAllDataRows().length / ROWS_PER_PAGE));
}

function renderPage() {
    const rows = getAllDataRows();
    const total = rows.length;
    const totalPages = getTotalPages();
    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end = Math.min(start + ROWS_PER_PAGE, total);

    rows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    document.getElementById('paginationInfo').textContent =
        total === 0 ? 'No entries' : `Showing ${start + 1}–${end} of ${total} entries`;

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
        btn.onclick = () => { currentPage = p; renderPage(); };
        container.appendChild(btn);
    }
}

function changePage(dir) {
    const totalPages = getTotalPages();
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderPage();
}

function getRow(rowId) {
    return getAllDataRows()[rowId - 1];
}

function toggleEditMode(rowId) {
    const row = getRow(rowId);
    const isEditing = row.classList.contains('editing');
    if (isEditing) { cancelEdit(null, rowId); return; }

    row.classList.add('editing', 'bg-yellow-50');
    // editable cells: male, female, total (indices 0,1,2), skip projects (3)
    const cells = row.querySelectorAll('td:not(:first-child):not(:last-child)');
    cells.forEach((cell, idx) => {
        if (idx < 3) {
            editingData[`cell_${rowId}_${idx}`] = cell.textContent.trim();
            cell.contentEditable = 'true';
            cell.classList.add('border', 'border-yellow-300', 'bg-white');
        }
    });
    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.add('hidden');
    ab.querySelector('.delete-btn').classList.add('hidden');
    ab.querySelector('.save-btn').classList.remove('hidden');
    ab.querySelector('.cancel-btn').classList.remove('hidden');
}

function cancelEdit(event, rowId) {
    const row = getRow(rowId);
    const cells = row.querySelectorAll('td:not(:first-child):not(:last-child)');
    cells.forEach((cell, idx) => {
        if (idx < 3) {
            cell.contentEditable = 'false';
            cell.textContent = editingData[`cell_${rowId}_${idx}`] || '';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white');
        }
    });
    row.classList.remove('editing', 'bg-yellow-50');
    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.remove('hidden');
    ab.querySelector('.delete-btn').classList.remove('hidden');
    ab.querySelector('.save-btn').classList.add('hidden');
    ab.querySelector('.cancel-btn').classList.add('hidden');
}

function deleteRow(event, rowId) {
    event.preventDefault();
    deletingRowId = rowId;
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('deleteModal').classList.add('hidden');
    deletingRowId = null;
}

function confirmDelete() {
    const row = getRow(deletingRowId);
    row.style.transition = 'opacity 0.3s ease-out';
    row.style.opacity = '0';
    setTimeout(() => { row.remove(); closeDeleteModal(); renderPage(); }, 300);
}

function saveRow(event, rowId) {
    event.preventDefault();
    savingRowId = rowId;
    document.getElementById('modalBackdrop').classList.remove('hidden');
    document.getElementById('saveModal').classList.remove('hidden');
}

function closeSaveModal() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('saveModal').classList.add('hidden');
    savingRowId = null;
}

function confirmSave() {
    const row = getRow(savingRowId);
    const cells = row.querySelectorAll('td:not(:first-child):not(:last-child)');
    cells.forEach((cell, idx) => {
        if (idx < 3) {
            cell.contentEditable = 'false';
            cell.classList.remove('border', 'border-yellow-300', 'bg-white');
        }
    });
    row.classList.remove('editing', 'bg-yellow-50');
    const ab = row.querySelector('.action-buttons');
    ab.querySelector('.edit-btn').classList.remove('hidden');
    ab.querySelector('.delete-btn').classList.remove('hidden');
    ab.querySelector('.save-btn').classList.add('hidden');
    ab.querySelector('.cancel-btn').classList.add('hidden');
    closeSaveModal();
    row.style.transition = 'background-color 0.3s ease-out';
    row.style.backgroundColor = '#dcfce7';
    setTimeout(() => { row.style.backgroundColor = ''; row.style.transition = ''; }, 1500);
}

document.addEventListener('click', (e) => {
    if (e.target === document.getElementById('modalBackdrop')) {
        closeDeleteModal(); closeSaveModal();
    }
});

renderPage();
</script>

<?php require_once __DIR__ . '/../../../includes/layout/footer.php'; ?>