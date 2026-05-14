<!-- ─── TAB CONTENT: RESUME ─── -->
<div id="tab-resume" class="tab-content hidden space-y-5">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Import Resume Files</h2>
        <p class="text-sm text-gray-400 mb-5">Select a section and program before uploading your resumes.</p>

        <!-- ── Inline Section / Program selectors ── -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pb-6 mb-6 border-b border-gray-100">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Section <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select id="resumeSection"
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition">
                        <option value="">Select a section…</option>
                        <option value="employment_facilitation">Employment Facilitation</option>
                        <option value="employers_engagement">Employers Engagement</option>
                        <option value="youth_employability">Youth Employability</option>
                        <option value="career_development">Career Development</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Program <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select id="resumeProgram"
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <option value="">Select a section first…</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
        </div>

        <!-- Drop Zone (disabled until program selected) -->
        <div id="resumeDropZone"
            class="border-2 border-dashed border-gray-200 rounded-2xl p-12 flex flex-col items-center justify-center gap-4 transition-all duration-200 opacity-40 pointer-events-none select-none"
            onclick="document.getElementById('resumeInput').click()">

            <svg class="w-14 h-14 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                <path d="m9.5 16.5 3-3 3 3"/>
                <path d="M12 13.5V21"/>
            </svg>

            <div class="text-center">
                <p class="text-base font-semibold text-gray-700">Upload Resume File</p>
                <p class="text-sm text-gray-400 mt-1">Upload PDF or Word documents containing applicant resumes</p>
            </div>

            <button type="button" id="resumeBrowseBtn"
                onclick="event.stopPropagation(); document.getElementById('resumeInput').click();"
                class="mt-2 px-8 py-3 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors"
                disabled>
                Browse File
            </button>

            <input type="file" id="resumeInput" accept=".pdf,.doc,.docx" multiple class="hidden">
        </div>

        <!-- File list (hidden until files chosen) -->
        <div id="resumeFileList" class="hidden mt-4 space-y-2"></div>

        <!-- Guidelines -->
        <div class="mt-5 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
            <p class="text-sm font-semibold text-blue-700 flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                Resume Format Guidelines
            </p>
            <ul class="space-y-1 text-sm text-blue-700">
                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Supported formats: PDF, DOC, DOCX</li>
                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>You can upload multiple files at once</li>
                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Maximum file size per resume: 5MB</li>
                <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>System will automatically extract relevant information</li>
            </ul>
        </div>
    </div>

    <!-- Resume Data Preview (hidden until files selected) -->
    <div id="resumePreview" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-gray-800">Extracted Data Preview</h2>
                <p id="resumePreviewMeta" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
            <div class="flex items-center gap-3">
                <button id="cancelResumeImport"
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel
                </button>
                <button id="confirmResumeImport"
                    class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                    Import
                </button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sex</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Section</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Program</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                    </tr>
                </thead>
                <tbody id="resumePreviewBody" class="divide-y divide-gray-50"></tbody>
            </table>
        </div>
    </div>

</div>