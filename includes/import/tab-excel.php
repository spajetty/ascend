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
                <path d="m9.5 16.5 3-3 3 3"/>
                <path d="M12 13.5V21"/>
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
                        <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                        </svg>
                        <span class="text-emerald-500 font-medium">Completed</span>
                    </p>
                </div>
            </div>
            <button id="removeFile" class="p-2 hover:bg-red-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/>
                    <path d="M14 11v6"/>
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

    <!-- Data Preview (hidden until modal confirmed) -->
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