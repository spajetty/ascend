<!-- ════════════════════════════════════════════════════════
                PANEL 2 — Program-Specific Fields
                All cards rendered; JS shows/hides per selected program
            ════════════════════════════════════════════════════════ -->
            <div class="mf-panel" id="mf-panel-2">

                <div class="mf-note mf-note-info" id="mf-panel2-note">
                    <span><i class="fa-solid fa-clipboard-list"></i></span>
                    <span id="mf-panel2-note-text">Fields specific to the selected program.</span>
                </div>

                <!-- EMPLOYER (jobmatch / firstjobseek / jobfair / whip) -->
                <div class="mf-card" id="mf-sec-employer" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-building"></i></div>
                        <div class="mf-card-title">Employer / Company</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label for="mf-company">Company <span class="mf-req">*</span> <span class="mf-hint">Type
                                        to search</span></label>
                                <input type="text" id="mf-company" class="mf-company-autocomplete" name="company_name"
                                    placeholder="Search company name…" data-hidden="mf-h-company-id">

                                <input type="hidden" name="company_id" id="mf-h-company-id" value="">
                            </div>
                            <div class="mf-field">
                                <label for="mf-position">Position Applied <span class="mf-req">*</span></label>
                                <input type="text" id="mf-position" name="position"
                                    placeholder="e.g. Customer Service Rep">
                            </div>
                            <div class="mf-field">
                                <label for="mf-batch">Batch / Period <span class="mf-req">*</span></label>
                                <input type="month" id="mf-batch" name="batch_period">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FIRST TIME JOBSEEKER extras -->
                <div class="mf-card" id="mf-sec-ftj" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-id-card"></i></div>
                        <div class="mf-card-title">Government Documents Issued</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-flag-row">
                            <div class="mf-flag mf-flag-inline" data-flag-inline="occ_permit">
                                <div class="mf-flag-box"></div> Occupational Permit
                            </div>
                            <input type="hidden" name="occ_permit" id="mf-h-occ-permit" value="0">
                            <div class="mf-flag mf-flag-inline" data-flag-inline="health_card">
                                <div class="mf-flag-box"></div> Health Card
                            </div>
                            <input type="hidden" name="health_card" id="mf-h-health-card" value="0">
                        </div>
                    </div>
                </div>

                <!-- JOB FAIR EVENT -->
                <div class="mf-card" id="mf-sec-jobfair" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-tent"></i></div>
                        <div class="mf-card-title">Job Fair Event</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label>Type</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="jftype" data-val="Local">Local</div>
                                    <div class="mf-chip" data-group="jftype" data-val="Overseas">Overseas</div>
                                </div>
                                <input type="hidden" name="job_fair_type" id="mf-h-jftype" value="Local">
                            </div>

                            <div class="mf-field mf-col2" style="position: relative;">
                                <div class="flex items-center justify-between gap-3 mb-1">
                                    <label for="mf-jfevent-input" class="mb-0">Event(s) <span class="mf-req">*</span></label>
                                    <button type="button" id="mf-add-jfevent" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                        <i class="fa-solid fa-plus"></i>
                                        Add New Event
                                    </button>
                                </div>
                                <div id="mf-jfevent-chips" class="flex flex-wrap gap-2 mb-2 empty:hidden"></div>
                                <input type="text" id="mf-jfevent-input" placeholder="Search and select events...">
                                <div id="mf-jfevent-dropdown"
                                    class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] hidden max-h-60 overflow-y-auto overflow-x-hidden"
                                    style="z-index: 9999;"></div>
                                <div id="mf-jfevent-hiddens"></div>
                                <input type="hidden" id="mf-jfevent" value="">
                            </div>

                            <div class="mf-field mf-col2" id="mf-jfcompanies-wrapper" style="display:none;">
                                <label>Participating Companies <span class="mf-req">*</span></label>
                                <div id="mf-jfcompany-lists" class="flex flex-col gap-3"></div>
                                <input type="hidden" id="mf-jfcompany" value="">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SPES -->
                <div class="mf-card" id="mf-sec-spes" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                        <div class="mf-card-title">SPES Details</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label>Student Type <span class="mf-req">*</span></label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="stutype" data-val="student">Student</div>
                                    <div class="mf-chip" data-group="stutype" data-val="osy">Out-of-School Youth</div>
                                </div>
                                <input type="hidden" name="student_type" id="mf-h-stutype" value="student">
                            </div>
                            <div class="mf-field">
                                <label for="mf-spes-school">School <span class="mf-req">*</span></label>
                                <input type="text" id="mf-spes-school" name="spes_school" placeholder="School name">
                            </div>
                            <div class="mf-field">
                                <label for="mf-spes-course">Course <span class="mf-req">*</span></label>
                                <input type="text" id="mf-spes-course" name="course" placeholder="e.g. BSIT">
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-highest-educ">Highest Education Attained <span class="mf-req">*</span></label>
                                <input type="text" id="mf-highest-educ" name="highest_educ"
                                    placeholder="e.g. 2nd Year College">
                            </div>
                        </div>

                        <div class="mf-sec-rule"><span>Employment Slot</span></div>

                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field">
                                <label for="mf-store">Company / Office Assignment <span class="mf-req">*</span></label>
                                <input type="text" id="mf-store" name="store_assignment">
                            </div>
                            <div class="mf-field">
                                <label>Category</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="spescat" data-val="lgu">LGU</div>
                                    <div class="mf-chip" data-group="spescat" data-val="private">Private</div>
                                </div>
                                <input type="hidden" name="spes_category" id="mf-h-spescat" value="lgu">
                            </div>
                            <div class="mf-field">
                                <label for="mf-contract-start">Contract Start <span class="mf-req">*</span></label>
                                <input type="date" id="mf-contract-start" name="start_of_contract">
                            </div>
                            <div class="mf-field">
                                <label for="mf-contract-end">Contract End <span class="mf-req">*</span></label>
                                <input type="date" id="mf-contract-end" name="end_of_contract">
                            </div>
                            <div class="mf-field">
                                <label for="mf-days">No. of Days <span class="mf-req">*</span></label>
                                <input type="number" id="mf-days" name="days" min="1" placeholder="0">
                            </div>
                            <div class="mf-field">
                                <label for="mf-spes-batch">Batch <span class="mf-req">*</span></label>
                                <input type="month" id="mf-spes-batch" name="spes_batch">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GIP Details -->
                <div class="mf-card" id="mf-sec-gip" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-briefcase"></i></div>
                        <div class="mf-card-title">Government Internship Program (GIP) Details</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label>Student Type <span class="mf-req">*</span></label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="gipstutype" data-val="student">Student</div>
                                    <div class="mf-chip" data-group="gipstutype" data-val="osy">Out-of-School Youth</div>
                                </div>
                                <input type="hidden" name="gip_student_type" id="mf-h-gipstutype" value="student">
                            </div>
                            <div class="mf-field">
                                <label for="mf-gip-school">School <span class="mf-req">*</span></label>
                                <input type="text" id="mf-gip-school" name="gip_school" placeholder="School name">
                            </div>
                            <div class="mf-field">
                                <label for="mf-gip-course">Course <span class="mf-req">*</span></label>
                                <input type="text" id="mf-gip-course" name="gip_course" placeholder="e.g. BSIT">
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-gip-highest-educ">Highest Education Attained <span class="mf-req">*</span></label>
                                <input type="text" id="mf-gip-highest-educ" name="gip_highest_educ" placeholder="e.g. College Graduate">
                            </div>
                        </div>

                        <div class="mf-sec-rule"><span>Contract Details</span></div>

                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label for="mf-gip-office">Office Assignment <span class="mf-req">*</span></label>
                                <input type="text" id="mf-gip-office" name="gip_office_assignment">
                            </div>
                            <div class="mf-field">
                                <label for="mf-gip-contract-start">Contract Start <span class="mf-req">*</span></label>
                                <input type="date" id="mf-gip-contract-start" name="gip_start_of_contract">
                            </div>
                            <div class="mf-field">
                                <label for="mf-gip-contract-end">Contract End <span class="mf-req">*</span></label>
                                <input type="date" id="mf-gip-contract-end" name="gip_end_of_contract">
                            </div>
                            <div class="mf-field">
                                <label for="mf-gip-days">No. of Days <span class="mf-req">*</span></label>
                                <input type="number" id="mf-gip-days" name="gip_days" min="1" placeholder="0">
                            </div>
                            <div class="mf-field">
                                <label for="mf-gip-batch">Batch <span class="mf-req">*</span></label>
                                <input type="month" id="mf-gip-batch" name="gip_batch">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WIIRP — Internship / Immersion -->
                <div class="mf-card" id="mf-sec-internship" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-building-columns"></i></div>
                        <div class="mf-card-title" id="mf-internship-title">Internship / Immersion Details</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2 mf-wiirp-only" style="display:none;">
                                <label>Type</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="inttype" data-val="inquiry">Inquiry</div>
                                    <div class="mf-chip" data-group="inttype" data-val="peso-assigned">PESO-Assigned
                                    </div>
                                    <div class="mf-chip" data-group="inttype" data-val="private">Private</div>
                                </div>
                                <input type="hidden" name="inquiry_type" id="mf-h-inttype" value="inquiry">
                            </div>
                            <div class="mf-field">
                                <label for="mf-int-school">School</label>
                                <input type="text" id="mf-int-school" name="int_school" placeholder="School name">
                            </div>
                            <div class="mf-field">
                                <label for="mf-int-course">Course / Strand</label>
                                <input type="text" id="mf-int-course" name="int_course" placeholder="e.g. BSIT / STEM">
                            </div>
                            <!-- WIIRP only -->
                            <div class="mf-field mf-wiirp-only" style="display:none;">
                                <label for="mf-year-level">Year Level</label>
                                <select id="mf-year-level" name="year_level">
                                    <option value="">— select —</option>
                                    <option value="Grade 11">Grade 11</option>
                                    <option value="Grade 12">Grade 12</option>
                                    <option value="1st Year College">1st Year College</option>
                                    <option value="2nd Year College">2nd Year College</option>
                                    <option value="3rd Year College">3rd Year College</option>
                                    <option value="4th Year College">4th Year College</option>
                                </select>
                            </div>
                            <div class="mf-field">
                                <label for="mf-req-hours">Required Hours</label>
                                <input type="number" id="mf-req-hours" name="required_hours" placeholder="e.g. 300">
                            </div>
                            <div class="mf-field">
                                <label for="mf-contract-period">Contract Period</label>
                                <input type="text" id="mf-contract-period" name="contract_period"
                                    placeholder="e.g. June – August 2025">
                            </div>
                            <div class="mf-field">
                                <label for="mf-pref-org">Preferred Org Type</label>
                                <select id="mf-pref-org" name="preferred_org_type">
                                    <option value="">— select —</option>
                                    <option value="Quezon City Government Office">Quezon City Government Office</option>
                                    <option value="Private Company / Establishment">Private Company / Establishment</option>
                                    <option value="No Preference">No Preference</option>
                                </select>
                            </div>
                            <div class="mf-field">
                                <label for="mf-pref-ind">Preferred Industry</label>
                                <select id="mf-pref-ind" name="preferred_industry" onchange="document.getElementById('mf-pref-ind-other').style.display = this.value === 'Other' ? 'block' : 'none';">
                                    <option value="">— select —</option>
                                    <option value="Office Administration">Office Administration</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Customer Service / Retail">Customer Service / Retail</option>
                                    <option value="Hospitality / Tourism">Hospitality / Tourism</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Accounting / Finance">Accounting / Finance</option>
                                    <option value="Human Resources">Human Resources</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Other">Other</option>
                                </select>
                                <input type="text" id="mf-pref-ind-other" name="preferred_industry_other" placeholder="Please specify" style="display:none; margin-top: 0.5rem;">
                            </div>
                            <div class="mf-field mf-col2">
                                <label>Willing to work outside QC?</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="willing" data-val="1">Yes</div>
                                    <div class="mf-chip" data-group="willing" data-val="0">No</div>
                                </div>
                                <input type="hidden" name="is_willing_outside" id="mf-h-willing" value="1">
                            </div>
                            <!-- WIIRP only -->
                            <div class="mf-field mf-wiirp-only" style="display:none;">
                                <label for="mf-int-sched">Internship Schedule</label>
                                <select id="mf-int-sched" name="internship_sched" onchange="document.getElementById('mf-int-sched-other').style.display = this.value === 'Other' ? 'block' : 'none';">
                                    <option value="">— select —</option>
                                    <option value="Weekdays (Mon–Fri)">Weekdays (Mon–Fri)</option>
                                    <option value="Weekends (Sat–Sun)">Weekends (Sat–Sun)</option>
                                    <option value="Flexible">Flexible</option>
                                    <option value="Other">Other</option>
                                </select>
                                <input type="text" id="mf-int-sched-other" name="internship_sched_other" placeholder="Please specify" style="display:none; margin-top: 0.5rem;">
                            </div>
                            <div class="mf-field">
                                <label for="mf-int-start">Start Date</label>
                                <input type="date" id="mf-int-start" name="int_start">
                            </div>
                            <div class="mf-field">
                                <label for="mf-int-batch">Batch <span class="mf-req">*</span></label>
                                <input type="month" id="mf-int-batch" name="int_batch">
                            </div>
                        </div>

                        <div id="mf-int-assignment-sec" style="display:none;">
                            

                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label for="mf-office">Office Assignment</label>
                                <input type="text" id="mf-office" name="office_assignment">
                            </div>
                            <div class="mf-field">
                                <label for="mf-assign-start">Assignment Start</label>
                                <input type="date" id="mf-assign-start" name="assign_start">
                            </div>
                            <div class="mf-field">
                                <label for="mf-assign-end">Assignment End</label>
                                <input type="date" id="mf-assign-end" name="assign_end">
                            </div>
                            <!-- WIIRP only -->
                            <div class="mf-field mf-wiirp-only" style="display:none;">
                                <label for="mf-endorse1">Endorsement Letter 1</label>
                                <input type="text" id="mf-endorse1" name="endorsement_1"
                                    placeholder="Filename / reference">
                            </div>
                            <div class="mf-field mf-wiirp-only" style="display:none;">
                                <label for="mf-endorse2">Endorsement Letter 2</label>
                                <input type="text" id="mf-endorse2" name="endorsement_2"
                                    placeholder="Filename / reference">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WHIP — Project Picker (search existing project or add a new one) -->
                <div class="mf-card" id="mf-sec-whip-picker" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <div>
                            <div class="mf-card-title">Infrastructure Project</div>
                            <div class="mf-card-sub">Search an existing project, or add it as new if it isn't found</div>
                        </div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-field" style="position:relative;">
                            <label for="mf-whip-project-search">Project <span class="mf-req">*</span> <span class="mf-hint">Type to search</span></label>
                            <input type="text" id="mf-whip-project-search" placeholder="Search project title or contractor…" autocomplete="off">
                            <input type="hidden" name="project_id" id="mf-h-whip-project-id" value="">
                            <input type="hidden" name="project_mode" id="mf-h-whip-project-mode" value="search">
                        </div>
                        <div class="mf-card" id="mf-whip-project-summary" style="display:none; margin-top:12px; border:1px solid var(--mf-border, #e5e7eb); border-radius:12px; overflow:hidden;">
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:var(--mf-surface2, #f8fafc); border-bottom:1px solid var(--mf-border, #e5e7eb);">
                                <strong id="mf-whip-project-summary-title" style="font-size:14px;">—</strong>
                                <div style="display:flex; gap:8px;">
                                    <button type="button" id="mf-whip-project-save-edit" style="display:none;"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-white bg-green-600 border border-green-600 rounded-md hover:bg-green-700 transition-colors">
                                        <i class="fa-solid fa-floppy-disk"></i> Save Project Changes
                                    </button>
                                    <button type="button" id="mf-whip-project-edit-toggle"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                        <i class="fa-solid fa-pen"></i> Edit Details
                                    </button>
                                </div>
                            </div>
                            <div class="mf-grid mf-grid-2" style="padding:14px 16px;">
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Nature of Project</label>
                                    <div id="mf-whip-summary-nature" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Duration</label>
                                    <div id="mf-whip-summary-duration" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Budget</label>
                                    <div id="mf-whip-summary-budget" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Fund Source</label>
                                    <div id="mf-whip-summary-fund" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Contractor</label>
                                    <div id="mf-whip-summary-contractor" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Legitimate Contractor</label>
                                    <div id="mf-whip-summary-legit" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Persons from Locality</label>
                                    <div id="mf-whip-summary-locality" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Slots</label>
                                    <div style="font-size:13px; font-weight:500;">
                                        <span id="mf-whip-summary-filled">0</span> filled ·
                                        <span id="mf-whip-summary-unfilled">0</span> unfilled
                                    </div>
                                </div>
                                <div class="mf-field mf-col2" style="margin-bottom:0;">
                                    <label style="margin-bottom:2px;">Skills Required</label>
                                    <div id="mf-whip-summary-skills" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                                <div class="mf-field mf-col2" style="margin-bottom:0;" id="mf-whip-summary-deficiencies-wrap">
                                    <label style="margin-bottom:2px;">Skills Deficiencies</label>
                                    <div id="mf-whip-summary-deficiencies" style="font-size:13px; font-weight:500;">—</div>
                                </div>
                            </div>
                        </div>

                        <div class="mf-note mf-note-warn" id="mf-whip-project-full-warning" style="display:none; margin-top:12px;">
                            <span><i class="fa-solid fa-triangle-exclamation"></i></span>
                            <span>This project has no open slots left. An admin needs to edit the project details (increase unfilled slots) before another worker can be added here.</span>
                        </div>
                    </div>
                </div>

                <!-- WHIP PROJECT DETAILS — shown when adding a new project (mode='new')
                     OR when editing the currently-selected existing project (mode='edit').
                     In 'edit' mode, saving this form updates the master project record. -->
                <div class="mf-card" id="mf-sec-whip-projects" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-helmet-safety"></i></div>
                        <div>
                            <div class="mf-card-title" id="mf-whip-projects-title">WHIP Project Details</div>
                            <div class="mf-card-sub" id="mf-whip-projects-sub">Records infrastructure project metadata</div>
                        </div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label for="mf-project-title">Project Title <span class="mf-req">*</span></label>
                                <input type="text" id="mf-project-title" name="project_title" placeholder="e.g. Pharmacy Operations Support Program for Mercury Drug Branches">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-nature">Nature of Project</label>
                                <input type="text" id="mf-project-nature" name="nature_of_project" placeholder="e.g. Pharmaceutical Retail">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-duration">Duration</label>
                                <input type="text" id="mf-project-duration" name="duration" placeholder="e.g. 150 CALENDAR DAYS">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-budget">Budget</label>
                                <input type="text" id="mf-project-budget" name="budget" placeholder="e.g. 29800000">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-fund">Fund Source</label>
                                <input type="text" id="mf-project-fund" name="fund_source" placeholder="e.g. COMPANY FUND">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-contractor">Contractor <span class="mf-req">*</span></label>
                                <input type="text" id="mf-project-contractor" class="mf-company-autocomplete" name="project_contractor" placeholder="Search contractor name…">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-legit">Legitimate Contractor</label>
                                <select id="mf-project-legit" name="legitimate_contractors">
                                    <option value="">— select —</option>
                                    <option value="YES">Yes</option>
                                    <option value="NO">No</option>
                                </select>
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-locality">No. of Persons Employed from the Locality</label>
                                <input type="number" id="mf-project-locality" name="persons_locality" min="0" placeholder="0">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-filled">Slots Filled</label>
                                <input type="number" id="mf-project-filled" name="filled" min="0" placeholder="0">
                            </div>
                            <div class="mf-field">
                                <label for="mf-project-unfilled">Slots Unfilled</label>
                                <input type="number" id="mf-project-unfilled" name="unfilled" min="0" placeholder="0">
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-project-skills">Skills Required for the Job <span class="mf-hint">e.g. 2 - Branch Supervisor, 5 - Pharmacy Assistant, 4 - Cashier</span></label>
                                <textarea id="mf-project-skills" name="skills_required" rows="2" placeholder="Count - Role, one per line or comma-separated"></textarea>
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-project-deficiencies">Skills Deficiencies <span class="mf-hint">Optional</span></label>
                                <textarea id="mf-project-deficiencies" name="skills_deficiencies" rows="2" placeholder="List any skills gaps"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SCHOOL-BASED — Career Dev / LMI -->
                <div class="mf-card" id="mf-sec-school" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-school"></i></div>
                        <div>
                            <div class="mf-card-title" id="mf-school-card-title">School-Based Activity Record</div>
                            <div class="mf-card-sub">Records aggregate participant counts per school</div>
                        </div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-note mf-note-warn" style="margin-bottom:14px;">
                            <span><i class="fa-solid fa-triangle-exclamation"></i></span>
                            <span>This program records <strong>school-level</strong> data. Individual beneficiary fields
                                in Step 1 are not required.</span>
                        </div>
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label for="mf-school">School <span class="mf-req">*</span></label>
                                <input type="text" id="mf-school" name="school_name" placeholder="Search school name…"
                                    list="mf-school-list">
                                <datalist id="mf-school-list">
                                    <!-- TODO: populate from schools table -->
                                </datalist>
                                <input type="hidden" name="school_id" id="mf-h-school-id" value="">
                            </div>
                            <div class="mf-field">
                                <label for="mf-grade-level">Grade Level</label>
                                <input type="text" id="mf-grade-level" name="grade_level"
                                    placeholder="e.g. Grade 11–12">
                            </div>
                            <div class="mf-field">
                                <label for="mf-de">Date Endorsed (DE)</label>
                                <input type="date" id="mf-de" name="de">
                            </div>
                            <div class="mf-field">
                                <label for="mf-conduct">Date of Conduct</label>
                                <input type="date" id="mf-conduct" name="date_of_conduct">
                            </div>
                            <div class="mf-field">
                                <label for="mf-male">Participants – Male</label>
                                <input type="number" id="mf-male" name="participants_male" min="0" placeholder="0">
                            </div>
                            <div class="mf-field">
                                <label for="mf-female">Participants – Female</label>
                                <input type="number" id="mf-female" name="participants_female" min="0" placeholder="0">
                            </div>
                            <div class="mf-field mf-col2">
                                <label>Approval Letter Received?</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="approval" data-val="1">Yes</div>
                                    <div class="mf-chip" data-group="approval" data-val="0">No</div>
                                </div>
                                <input type="hidden" name="approval_letter" id="mf-h-approval" value="1">
                            </div>
                            <div class="mf-field">
                                <label for="mf-school-batch">Batch <span class="mf-req">*</span></label>
                                <input type="month" id="mf-school-batch" name="school_batch">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACCREDITATION -->
                <div class="mf-card" id="mf-sec-accred" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                        <div>
                            <div class="mf-card-title">Employer Accreditation</div>
                            <div class="mf-card-sub">No individual beneficiary — records a company accreditation</div>
                        </div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <label for="mf-accred-company" class="mb-0">Company <span class="mf-req">*</span></label>
                                </div>
                                <input type="text" id="mf-accred-company" class="mf-company-autocomplete" name="accred_company"
                                    placeholder="Search existing company or type a new one" data-hidden="mf-h-accred-company-id">
                                <input type="hidden" name="accred_company_id" id="mf-h-accred-company-id" value="">
                                <input type="hidden" name="accred_company_mode" id="mf-h-accred-company-mode" value="search">
                            </div>
                            <div class="mf-field">
                                <label>Accreditation</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="accstatus" data-val="new">New</div>
                                    <div class="mf-chip" data-group="accstatus" data-val="renew">Renew</div>
                                </div>
                                <input type="hidden" name="accred_status" id="mf-h-accstatus" value="new">
                            </div>
                            <div class="mf-field">
                                <label for="mf-accred-period">Accreditation Period <span class="mf-req">*</span></label>
                                <input type="month" id="mf-accred-period" name="accred_period">
                            </div>
                            <div class="mf-field">
                                <label for="mf-accred-est-type">Establishment Type <span class="mf-hint">Optional</span></label>
                                <select id="mf-accred-est-type" name="est_type">
                                    <option value="">— select —</option>
                                    <option value="Corporation">Corporation</option>
                                    <option value="Manpower">Manpower</option>
                                    <option value="Direct">Direct</option>
                                    <option value="LGU">LGU</option>
                                </select>
                            </div>
                            <div class="mf-field">
                                <label for="mf-accred-industry">Industry <span class="mf-hint">Optional</span></label>
                                <input type="text" id="mf-accred-industry" name="industry" placeholder="e.g. Logistics">
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-accred-city">City / Municipality <span class="mf-hint">Optional</span></label>
                                <input type="text" id="mf-accred-city" name="city" placeholder="e.g. Quezon City">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mf-btn-row">
                    <button type="button" class="mf-btn mf-btn-ghost" id="mf-back-2">← Back</button>
                    <button type="button" class="mf-btn mf-btn-primary" id="mf-next-2">Continue → Documents</button>
                </div>

            </div><!-- /mf-panel-2 -->