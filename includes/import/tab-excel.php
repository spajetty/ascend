<!-- ─── TAB CONTENT: EXCEL ─── -->
<div id="tab-excel" class="tab-content space-y-5">

    <div id="importFormScreen" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Import Data from Excel</h2>
        <p class="text-sm text-gray-400 mb-5">Select a section and program before uploading your file.</p>

        <!-- ── Inline Section / Program selectors ── -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pb-6 mb-6 border-b border-gray-100">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Section <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select id="excelSection"
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Select a section…</option>
                        <option value="employment_facilitation">Employment Facilitation</option>
                        <option value="employers_engagement">Employers Engagement</option>
                        <option value="youth_employability">Youth Employability</option>
                        <!-- <option value="career_development">Career Development</option> -->
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Program <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select id="excelProgram"
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <option value="">Select a section first…</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Drop Zone (disabled until program selected) -->
        <div id="dropZone"
            class="border-2 border-dashed border-gray-200 rounded-2xl p-12 flex flex-col items-center justify-center gap-4 transition-all duration-200 opacity-40 pointer-events-none select-none"
            onclick="document.getElementById('fileInput').click()">

            <svg class="w-14 h-14 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5">
                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                <path d="m9.5 16.5 3-3 3 3" />
                <path d="M12 13.5V21" />
            </svg>

            <div class="text-center">
                <p class="text-base font-semibold text-gray-700">Upload Excel File</p>
                <p class="text-sm text-gray-400 mt-1">Drag and drop your Excel file here, or click to browse</p>
            </div>

            <button type="button" id="excelBrowseBtn"
                onclick="event.stopPropagation(); document.getElementById('fileInput').click();"
                class="mt-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors"
                disabled>
                Browse File
            </button>

            <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" class="hidden">
        </div>

        <!-- Selected file info (hidden until file chosen) -->
        <div id="fileInfo"
            class="hidden mt-4 flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </div>
                <div>
                    <p id="fileName" class="text-sm font-semibold text-gray-800"></p>
                    <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                        <span id="fileSize"></span>
                        <span class="text-gray-300">•</span>
                        <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z" />
                        </svg>
                        <span class="text-emerald-500 font-medium">Completed</span>
                    </p>
                </div>
            </div>
            <button id="removeFile" class="p-2 hover:bg-red-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6" />
                    <path d="M14 11v6" />
                </svg>
            </button>
        </div>

        <!-- Import period detection + editable confirmation -->
        <div id="importPeriodPanel" class="hidden mt-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-4">
            <p id="periodSuggestionText" class="text-sm text-blue-800 font-medium mb-3"></p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div id="importMonthWrapper">
                    <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1.5">Month</label>
                    <select id="importMonth"
                        class="w-full appearance-none bg-white border border-blue-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Select month...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1.5">Year</label>
                    <select id="importYear"
                        class="w-full appearance-none bg-white border border-blue-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Select year...</option>
                    </select>
                </div>
            </div>
            <!-- SPES Category Dropdown (hidden by default, shown only for SPES) -->
            <div id="spesCategoryWrapper" class="hidden mt-4">
                <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1.5">SPES Category</label>
                <select id="spesCategory"
                    class="w-full appearance-none bg-white border border-blue-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Select category...</option>
                    <option value="lgu">SPES LGU</option>
                    <option value="private">SPES Private</option>
                </select>
            </div>
            <p class="text-xs text-blue-700 mt-2">Suggestion is pre-filled but editable before final import.</p>
        </div>

        <!-- Guidelines -->
        <div class="mt-5 bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-4">
            <p class="text-sm font-semibold text-yellow-700 flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                Excel Format Guidelines
            </p>
            <ul class="space-y-1 text-sm text-yellow-700">
                <li class="flex items-start gap-2"><span
                        class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>First row should
                    contain column headers</li>
                <li class="flex items-start gap-2"><span
                        class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Supported formats:
                    .xlsx, .xls, .csv</li>
                <li class="flex items-start gap-2"><span
                        class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Maximum file size:
                    10MB</li>
                <li class="flex items-start gap-2"><span
                        class="mt-1.5 w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Ensure data matches
                    the required fields for each section</li>
            </ul>
        </div>
    </div>

    <!-- Data Preview (hidden until file selected) -->
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

        <div class="overflow-x-auto preview-scrollbar rounded-xl border border-gray-100">
            <table class="min-w-max w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sex
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Section
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Program
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact
                        </th>
                    </tr>
                </thead>
                <tbody id="previewBody" class="divide-y divide-gray-50"></tbody>
            </table>
        </div>
    </div>

    <!-- Import Results View (shown after successful import) -->
    <div id="importResultsView" class="hidden space-y-5">

        <!-- ── Header bar ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M20 6L9 17l-5-5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Import Complete</h2>
                        <p id="importResultsMetaLine" class="text-xs text-gray-400 mt-0.5"></p>
                    </div>
                </div>
                <button id="backToImportBtn" type="button"
                    class="flex-shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 5l-7 7 7 7" />
                    </svg>
                    New Import
                </button>
            </div>

            <!-- Summary stat cards -->
            <div id="importResultsSummary" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5"></div>

            <!-- Warnings accordion (hidden by default) -->
            <div id="importResultsWarnings" class="hidden mt-4">
                <button type="button" id="warningsToggleBtn"
                    class="w-full flex items-center justify-between gap-2 px-4 py-3 rounded-xl border border-amber-200 bg-amber-50 text-sm font-semibold text-amber-800 hover:bg-amber-100 transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                        </svg>
                        <span id="warningsCount">Warnings</span>
                    </span>
                    <svg id="warningsChevron" class="w-4 h-4 text-amber-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div id="warningsList" class="hidden mt-2 rounded-xl border border-amber-100 bg-amber-50/60 px-4 py-3 space-y-1 text-sm text-amber-800"></div>
            </div>
        </div>

        <!-- ── Detail tabs ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <!-- Tab buttons -->
            <div class="flex flex-wrap gap-2 pb-4 border-b border-gray-100">
                <button type="button" data-results-tab="new-employers"
                    class="results-tab-btn inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition-all bg-blue-600 text-white shadow-sm">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    <span id="tabLabelNewEmployers">New Employers</span>
                    <span id="tabBadgeNewEmployers" class="ml-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 rounded-full bg-white/30 px-1.5 text-xs font-bold">0</span>
                </button>
                <button type="button" data-results-tab="duplicates"
                    class="results-tab-btn inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="9" y="9" width="13" height="13" rx="2" />
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                    </svg>
                    Duplicates
                    <span id="tabBadgeDuplicates" class="ml-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 rounded-full bg-gray-300/60 px-1.5 text-xs font-bold">0</span>
                </button>
                <button type="button" data-results-tab="errors"
                    class="results-tab-btn inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    Errors
                    <span id="tabBadgeErrors" class="ml-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 rounded-full bg-gray-300/60 px-1.5 text-xs font-bold">0</span>
                </button>
            </div>

            <!-- Tab panels -->
            <div class="mt-4 max-h-[440px] overflow-auto rounded-xl">
                <div id="resultsPanelNewEmployers" class="results-tab-panel"></div>
                <div id="resultsPanelDuplicates" class="results-tab-panel hidden"></div>
                <div id="resultsPanelErrors" class="results-tab-panel hidden"></div>
            </div>

            <!-- Action buttons -->
            <div class="mt-5 flex flex-wrap items-center gap-3 pt-4 border-t border-gray-100">
                <button id="downloadErrorReportBtn" type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Download Error Report
                </button>
                <button id="proceedToJobFairBtn" type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                    Proceed to Job Fair
                </button>
                <button id="addAccreditationBtn" type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="6" />
                        <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" />
                    </svg>
                    Add Accreditation
                </button>
                <button id="reviewEmployersBtn" type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Review Employers
                </button>

                <!-- Rollback button — only visible when an undo token exists -->
                <button id="rollbackImportBtn" type="button"
                    class="hidden ml-auto inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    Rollback Import
                </button>
            </div>
        </div>
    </div>

</div>