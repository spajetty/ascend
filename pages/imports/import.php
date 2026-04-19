<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'imports';
$pageTitle   = 'ASCEND PED System – Import Data';
$pageHeading = 'Import Data';

require_once __DIR__ . '/../../includes/layout-head.php';
require_once __DIR__ . '/../../includes/layout-sidebar.php';
require_once __DIR__ . '/../../helpers/modal-helper.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout-topbar.php'; ?>

    <div class="px-6 md:px-8 py-6 space-y-6">

        <!-- ─── TABS ─── -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex border-b border-gray-100">
                <button data-tab="excel"
                    class="tab-btn active-tab flex items-center gap-2 px-6 py-4 text-sm font-semibold text-blue-600 border-b-2 border-blue-600 -mb-px transition-colors">
                    <!-- Excel icon -->
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="18" rx="2"/>
                        <path d="M8 3v18M2 9h20M2 15h20"/>
                    </svg>
                    Import from Excel
                </button>
                <button data-tab="resume"
                    class="tab-btn flex items-center gap-2 px-6 py-4 text-sm font-medium text-gray-400 border-b-2 border-transparent -mb-px hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    Import from Resume
                </button>
                <button data-tab="manual"
                    class="tab-btn flex items-center gap-2 px-6 py-4 text-sm font-medium text-gray-400 border-b-2 border-transparent -mb-px hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                    Manual Entry
                </button>
            </div>
        </div>

        <!-- ─── TAB CONTENT: EXCEL ─── -->
        <div id="tab-excel" class="tab-content space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-bold text-gray-800 mb-4">Import Data from Excel</h2>

                <!-- Drop Zone -->
                <div id="dropZone"
                    class="border-2 border-dashed border-gray-200 rounded-2xl p-12 flex flex-col items-center justify-center gap-4 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all duration-200"
                    onclick="document.getElementById('fileInput').click()">

                    <svg class="w-14 h-14 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                        <path d="m9.5 16.5 3-3 3 3"/><path d="M12 13.5V21"/>
                    </svg>

                    <div class="text-center">
                        <p class="text-base font-semibold text-gray-700">Upload Excel File</p>
                        <p class="text-sm text-gray-400 mt-1">Drag and drop your Excel file here, or click to browse</p>
                    </div>

                    <button type="button"
                        onclick="event.stopPropagation(); document.getElementById('fileInput').click();"
                        class="mt-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                        Browse File
                    </button>

                    <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" class="hidden">
                </div>

                <!-- Selected file info (hidden until file chosen) -->
                <div id="fileInfo" class="hidden mt-4 flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div>
                            <p id="fileName" class="text-sm font-semibold text-gray-800"></p>
                            <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                <span id="fileSize"></span>
                                <span class="text-gray-300">•</span>
                                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                                <span class="text-emerald-500 font-medium">Completed</span>
                            </p>
                        </div>
                    </div>
                    <button id="removeFile" class="p-2 hover:bg-red-50 rounded-lg transition-colors group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6"/><path d="M14 11v6"/>
                        </svg>
                    </button>
                </div>

                <!-- Guidelines -->
                <div class="mt-5 bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-4">
                    <p class="text-sm font-semibold text-yellow-700 flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                        Excel Format Guidelines
                    </p>
                    <ul class="space-y-1 text-sm text-yellow-700">
                        <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>First row should contain column headers</li>
                        <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Supported formats: .xlsx, .xls, .csv</li>
                        <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Maximum file size: 10MB</li>
                        <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Ensure data matches the required fields for each section</li>
                    </ul>
                </div>
            </div>

            <!-- ─── DATA PREVIEW (shown after import confirmed) ─── -->
            <div id="dataPreview" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Data Preview</h2>
                        <p id="previewMeta" class="text-xs text-gray-400 mt-0.5"></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="cancelImport"
                            class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button id="confirmImport"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                            Import
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Gender</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Section</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Program</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                            </tr>
                        </thead>
                        <tbody id="previewBody" class="divide-y divide-gray-50"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ─── TAB CONTENT: RESUME (placeholder) ─── -->
        <div id="tab-resume" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
                <svg class="w-14 h-14 text-gray-200 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                <p class="text-gray-400 font-medium">Resume import coming soon</p>
            </div>
        </div>

        <!-- ─── TAB CONTENT: MANUAL (placeholder) ─── -->
        <div id="tab-manual" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
                <svg class="w-14 h-14 text-gray-200 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
                <p class="text-gray-400 font-medium">Manual entry coming soon</p>
            </div>
        </div>

    </div><!-- /px-6 -->
</main>

<!-- ═══════════════════ SECTION / PROGRAM MODAL ═══════════════════ -->
<?php
$modalContent = '
    <!-- Selected file recap -->
    <div id="modalFileName" class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 mb-5">
        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
        </div>
        <p id="modalFileNameText" class="text-sm font-semibold text-gray-700 truncate"></p>
    </div>

    <!-- Section dropdown -->
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Section</label>
        <div class="relative">
            <select id="sectionSelect"
                class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <option value="">Select a section…</option>
                <option value="employment_facilitation">Employment Facilitation</option>
                <option value="employers_engagement">Employers Engagement</option>
                <option value="youth_employability">Youth Employability</option>
                <option value="career_development">Career Development</option>
            </select>
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </div>
    </div>

    <!-- Program dropdown -->
    <div class="mb-6">
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Program</label>
        <div class="relative">
            <select id="programSelect"
                class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <option value="">Select a section first…</option>
            </select>
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </div>
    </div>
';

$modalFooter = '
    <div class="flex gap-3">
        <button id="modalCancel" onclick="hideModal(\'sectionModal\')"
            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-500 hover:bg-gray-50 transition-colors">
            Cancel
        </button>
        <button id="modalConfirm"
            class="flex-1 px-4 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            Upload & Preview
        </button>
    </div>
';

renderModal(
    'sectionModal', 
    'Assign Section & Program', 
    'Select where this data belongs before uploading', 
    $modalContent, 
    $modalFooter
);
?>
<script src="/assets/js/import.js" defer></script>

<?php require_once __DIR__ . '/../../includes/layout-footer.php'; ?>