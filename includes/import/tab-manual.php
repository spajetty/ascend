<!-- ─── TAB CONTENT: MANUAL ─── -->
<div id="tab-manual" class="tab-content hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form id="manualEntryForm" novalidate>

            <!-- ── Section & Program (drives dynamic fields below) ── -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 pb-6 border-b border-gray-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Section <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <select id="manualSection"
                            class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
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
                        <select id="manualProgram"
                            class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <option value="">Select a section first…</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
            </div>

            <!-- ── Base Fields ── -->
            <div class="space-y-5">

                <!-- Last / First / Middle / Suffix -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Last Name <span class="text-red-400">*</span></label>
                        <input type="text" name="last_name" placeholder="e.g. Santos" required
                            class="manual-input w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">First Name <span class="text-red-400">*</span></label>
                        <input type="text" name="first_name" placeholder="e.g. Juan" required
                            class="manual-input w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Middle Name</label>
                        <input type="text" name="middle_name" placeholder="e.g. Dela Cruz"
                            class="manual-input w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Suffix <span class="text-gray-400 font-normal text-xs">(optional)</span></label>
                        <div class="relative">
                            <select name="suffix"
                                class="manual-input w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">None</option>
                                <option>Jr.</option>
                                <option>Sr.</option>
                                <option>II</option>
                                <option>III</option>
                                <option>IV</option>
                                <option>V</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Sex / DOB -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sex <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <select name="sex" required
                                class="manual-input w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">Select Sex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date of Birth <span class="text-red-400">*</span></label>
                        <input type="date" name="date_of_birth" required
                            class="manual-input w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Email / Contact -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email address"
                            class="manual-input w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Contact Number <span class="text-red-400">*</span></label>
                        <input type="tel" name="contact" placeholder="e.g. 09171234567"
                            class="manual-input w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Address -->
                <div class="space-y-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Address <span class="text-red-400">*</span></p>

                    <!-- House No. / Street -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">House No. / Street <span class="text-red-400">*</span></label>
                        <input type="text" name="house_street" placeholder="e.g. 123 Rizal St."
                            class="manual-input w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>

                    <!-- City → Barangay → District (populated by address-data.js) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <!-- City (select first — drives barangay + district) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">City / Municipality <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <select name="city" id="manualCity" required
                                    class="manual-input w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="">Select City…</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>

                        <!-- Barangay (populated by JS when city is selected) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Barangay <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <select name="barangay" id="manualBarangay" required disabled
                                    class="manual-input w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">Select a city first…</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>

                        <!-- District (auto-filled by JS based on city) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">District</label>
                            <div class="relative">
                                <input type="text" name="district" id="manualDistrict" readonly
                                    placeholder="Auto-filled from city"
                                    class="manual-input w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed focus:outline-none">
                            </div>
                        </div>

                    </div>
                </div>

            </div><!-- /base fields -->

            <!-- ── Dynamic Program-Specific Fields ── -->
            <div id="dynamicFields" class="hidden mt-5 pt-5 border-t border-dashed border-blue-100 space-y-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    Additional Fields —
                    <span id="dynamicFieldsLabel" class="text-blue-600 ml-1 normal-case font-semibold"></span>
                </p>
                <div id="dynamicFieldsInner" class="space-y-5"></div>
            </div>

            <!-- ── Actions ── -->
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                    Save Entry
                </button>
                <button type="button" id="clearManualForm"
                    class="px-6 py-3 border border-gray-200 text-sm font-semibold text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">
                    Clear Form
                </button>
            </div>

        </form>
    </div>
</div>