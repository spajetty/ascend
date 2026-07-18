<!-- ════════════════════════════════════════════════════════
                PANEL 1 — Beneficiary Info
            ════════════════════════════════════════════════════════ -->
            <div class="mf-panel" id="mf-panel-1">

                <!-- Personal Information -->
                <div class="mf-card">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-user"></i></div>
                        <div class="mf-card-title">Personal Information</div>
                    </div>
                    <div class="mf-card-body">

                        <!-- Name row: Last / First / Middle / Suffix -->
                        <div class="mf-grid mf-grid-4" style="margin-bottom:13px;">
                            <div class="mf-field">
                                <label for="mf-lname">Last Name <span class="mf-req">*</span></label>
                                <input type="text" id="mf-lname" name="last_name" placeholder="e.g. Dela Cruz">
                            </div>
                            <div class="mf-field">
                                <label for="mf-fname">First Name <span class="mf-req">*</span></label>
                                <input type="text" id="mf-fname" name="first_name" placeholder="e.g. Juan">
                            </div>
                            <div class="mf-field">
                                <label for="mf-mname">Middle Name</label>
                                <input type="text" id="mf-mname" name="middle_name" placeholder="Optional">
                            </div>
                            <div class="mf-field">
                                <label for="mf-suffix">Suffix</label>
                                <select id="mf-suffix" name="suffix">
                                    <option value="">—</option>
                                    <option>Jr.</option>
                                    <option>Sr.</option>
                                    <option>II</option>
                                    <option>III</option>
                                </select>
                            </div>
                        </div>

                        <!-- DOB / Sex / Civil / Classification / Contact / Email -->
                        <div class="mf-grid mf-grid-4">
                            <div class="mf-field">
                                <label for="mf-dob">Date of Birth <span class="mf-req">*</span></label>
                                <input type="date" id="mf-dob" name="dob">
                            </div>
                            <div class="mf-field">
                                <label for="mf-age">Age</label>
                                <input type="text" id="mf-age" name="age" placeholder="Auto-calculated" readonly>
                            </div>
                            <div class="mf-field">
                                <label for="mf-civil">Civil Status</label>
                                <select id="mf-civil" name="civil_status">
                                    <option value="">— Select —</option>
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Widowed</option>
                                    <option>Divorced</option>
                                    <option>Separated</option>
                                    <option>Annulled</option>
                                    <option>Common-Law Partner</option>
                                </select>
                            </div>
                            <div class="mf-field">
                                <label>Sex <span class="mf-req">*</span></label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="sex" data-val="Male">Male</div>
                                    <div class="mf-chip" data-group="sex" data-val="Female">Female</div>
                                </div>
                                <input type="hidden" name="sex" id="mf-h-sex" value="Male">
                            </div>
                            
                            <div class="mf-field mf-col2" id="mf-classification-wrap" style="display:none;">
                                <label for="mf-classification">Classification <span class="mf-req">*</span></label>
                                <select id="mf-classification" name="classification" disabled>
                                    <option value="">— select a program first —</option>
                                </select>
                            </div>
                            <div class="mf-field">
                                <label for="mf-contact">Contact No.</label>
                                <input type="tel" id="mf-contact" name="contact" placeholder="09XX XXX XXXX">
                            </div>
                            <div class="mf-field">
                                <label for="mf-email">Email</label>
                                <input type="email" id="mf-email" name="email" placeholder="optional">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Address -->
                <div class="mf-card">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="mf-card-title">Address</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-4">
                            <div class="mf-field">
                                <label for="mf-house">House / Unit</label>
                                <input type="text" id="mf-house" name="house_no" placeholder="123">
                            </div>
                            <div class="mf-field">
                                <label for="mf-district">District</label>
                                <select id="mf-district" name="district"
                                    class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="">Select district…</option>
                                </select>
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-barangay">Barangay <span class="mf-req">*</span></label>
                                <select id="mf-barangay" name="barangay"
                                    class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                    <option value="">Select district first…</option>
                                </select>
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-city">City <span class="mf-req">*</span></label>
                                <input type="text" id="mf-city" name="city" value="Quezon City">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special Classifications -->
                <div class="mf-card">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-tags"></i></div>
                        <div>
                            <div class="mf-card-title">Special Classifications</div>
                            <div class="mf-card-sub">Tick all that apply</div>
                        </div>
                    </div>
                    <div class="mf-card-body">

                        <div class="mf-flag-row">
                            <div class="mf-flag" id="mf-flag-4ps" data-flag="4ps">
                                <div class="mf-flag-box"></div> 4Ps Beneficiary
                            </div>
                            <input type="hidden" name="is_4ps" id="mf-h-4ps" value="0">

                            <div class="mf-flag" id="mf-flag-pwd" data-flag="pwd">
                                <div class="mf-flag-box"></div> PWD
                            </div>
                            <input type="hidden" name="is_pwd" id="mf-h-pwd" value="0">

                            <div class="mf-flag" id="mf-flag-ofw" data-flag="ofw">
                                <div class="mf-flag-box"></div> OFW Dependent
                            </div>
                            <input type="hidden" name="is_ofw_dependent" id="mf-h-ofw" value="0">
                        </div>

                        <!-- 4Ps ID — revealed when 4Ps is checked -->
                        <div class="mf-cond" id="mf-cond-4ps">
                            <div class="mf-cond-title">4Ps Details</div>
                            <div class="mf-field" style="max-width:280px;">
                                <label for="mf-4psid">4Ps ID Number <span class="mf-req">*</span></label>
                                <input type="text" id="mf-4psid" name="ps4_id_no" placeholder="Enter 4Ps ID">
                            </div>
                        </div>

                        <!-- SPES status — revealed only when SPES program is selected -->
                        <div class="mf-cond" id="mf-cond-spes-status"
                            style="border-left-color:var(--mf-accent2); margin-top:10px;">
                            <div class="mf-cond-title" style="color:var(--mf-accent2);">SPES Status</div>
                            <div class="mf-chip-group">
                                <div class="mf-chip on" data-group="spes-status" data-val="Pending">New</div>
                                <div class="mf-chip" data-group="spes-status" data-val="Active">SPES Baby</div>
                            </div>
                            <input type="hidden" name="spes_status" id="mf-h-spes-status" value="Pending">
                        </div>

                        <div class="mf-field" style="margin-top:14px;">
                            <label for="mf-notes">Notes <span class="mf-hint">Optional</span></label>
                            <textarea id="mf-notes" name="notes"
                                placeholder="Any additional notes about this beneficiary…"></textarea>
                        </div>

                    </div>
                </div>

                <!-- Worker Assignment (WHIP only) -->
                <div class="mf-card" id="mf-sec-ben-whip" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-hammer"></i></div>
                        <div class="mf-card-title">Worker Assignment</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field">
                                <label for="mf-whip-pos">Position</label>
                                <input type="text" id="mf-whip-pos" name="whip_position"
                                    placeholder="e.g. Construction Worker">
                            </div>
                            <div class="mf-field">
                                <label for="mf-date-hired">Date Hired</label>
                                <input type="date" id="mf-date-hired" name="date_hired">
                            </div>
                            <div class="mf-field">
                                <label for="mf-whip-batch">Batch <span class="mf-req">*</span></label>
                                <input type="month" id="mf-whip-batch" name="whip_batch">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mf-btn-row">
                    <button type="button" class="mf-btn mf-btn-primary" id="mf-next-1">
                        Continue → Program Details
                    </button>
                </div>

            </div><!-- /mf-panel-1 -->