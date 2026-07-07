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
                                <input type="text" id="mf-company" name="company_name"
                                    placeholder="Search company name…" list="mf-company-list">
                                <datalist id="mf-company-list">
                                    <!-- TODO: populate from DB query on employers table -->
                                </datalist>
                                <input type="hidden" name="company_id" id="mf-h-company-id" value="">
                            </div>
                            <div class="mf-field">
                                <label for="mf-position">Position Applied <span class="mf-req">*</span></label>
                                <input type="text" id="mf-position" name="position"
                                    placeholder="e.g. Customer Service Rep">
                            </div>
                            <div class="mf-field">
                                <label for="mf-batch">Batch / Period</label>
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
                                <label for="mf-spes-school">School</label>
                                <input type="text" id="mf-spes-school" name="spes_school" placeholder="School name">
                            </div>
                            <div class="mf-field">
                                <label for="mf-spes-course">Course</label>
                                <input type="text" id="mf-spes-course" name="course" placeholder="e.g. BSIT">
                            </div>
                            <div class="mf-field mf-col2">
                                <label for="mf-highest-educ">Highest Education Attained</label>
                                <input type="text" id="mf-highest-educ" name="highest_educ"
                                    placeholder="e.g. 2nd Year College">
                            </div>
                        </div>

                        <div class="mf-sec-rule"><span>Employment Slot</span></div>

                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field">
                                <label for="mf-store">Company / Office Assignment</label>
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
                                <label for="mf-contract-start">Contract Start</label>
                                <input type="date" id="mf-contract-start" name="start_of_contract">
                            </div>
                            <div class="mf-field">
                                <label for="mf-contract-end">Contract End</label>
                                <input type="date" id="mf-contract-end" name="end_of_contract">
                            </div>
                            <div class="mf-field">
                                <label for="mf-days">No. of Days</label>
                                <input type="number" id="mf-days" name="days" min="1" placeholder="0">
                            </div>
                            <div class="mf-field">
                                <label for="mf-spes-batch">Batch</label>
                                <input type="month" id="mf-spes-batch" name="spes_batch">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GIP / WIIRP — Internship / Immersion -->
                <div class="mf-card" id="mf-sec-internship" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-building-columns"></i></div>
                        <div class="mf-card-title" id="mf-internship-title">Internship / Immersion Details</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label>Type</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="inttype" data-val="inquiry">Inquiry</div>
                                    <div class="mf-chip" data-group="inttype" data-val="peso-assigned">PESO-Assigned
                                    </div>
                                    <div class="mf-chip" data-group="inttype" data-val="private">Private</div>
                                </div>
                                <input type="hidden" name="inquiry_type" id="mf-h-inttype" value="inquiry">
                            </div>
                            <!-- GIP only -->
                            <div class="mf-field mf-gip-only" style="display:none;">
                                <label>Level</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="level" data-val="College">College</div>
                                    <div class="mf-chip" data-group="level" data-val="SHS">SHS</div>
                                </div>
                                <input type="hidden" name="college_or_shs" id="mf-h-level" value="College">
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
                                <input type="text" id="mf-year-level" name="year_level"
                                    placeholder="e.g. 3rd Year / Grade 12">
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
                                <input type="text" id="mf-pref-org" name="preferred_org_type"
                                    placeholder="e.g. Government">
                            </div>
                            <div class="mf-field">
                                <label for="mf-pref-ind">Preferred Industry</label>
                                <input type="text" id="mf-pref-ind" name="preferred_industry"
                                    placeholder="e.g. IT &amp; Communications">
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
                                <input type="text" id="mf-int-sched" name="internship_sched"
                                    placeholder="e.g. MWF 8am–5pm">
                            </div>
                            <div class="mf-field">
                                <label for="mf-int-start">Start Date</label>
                                <input type="date" id="mf-int-start" name="int_start">
                            </div>
                            <div class="mf-field">
                                <label for="mf-int-batch">Batch</label>
                                <input type="month" id="mf-int-batch" name="int_batch">
                            </div>
                        </div>

                        <div class="mf-sec-rule"><span>Office Assignment (if PESO-assigned)</span></div>

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

                <!-- WHIP — Infrastructure Project -->
                <div class="mf-card" id="mf-sec-whip" style="display:none;">
                    <div class="mf-card-head">
                        <div class="mf-card-icon"><i class="fa-solid fa-hammer"></i></div>
                        <div class="mf-card-title">Infrastructure Project</div>
                    </div>
                    <div class="mf-card-body">
                        <div class="mf-grid mf-grid-2">
                            <div class="mf-field mf-col2">
                                <label for="mf-project">Project <span class="mf-req">*</span></label>
                                <select id="mf-project" name="project_id">
                                    <option value="">— select project —</option>
                                    <!-- TODO: populate from projects table -->
                                </select>
                            </div>
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
                                <label for="mf-whip-batch">Batch</label>
                                <input type="month" id="mf-whip-batch" name="whip_batch">
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
                                <label for="mf-school-batch">Batch</label>
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
                                <label for="mf-accred-company">Company <span class="mf-req">*</span></label>
                                <input type="text" id="mf-accred-company" name="accred_company"
                                    placeholder="Search company name…" list="mf-company-list">
                            </div>
                            <div class="mf-field">
                                <label>Status</label>
                                <div class="mf-chip-group">
                                    <div class="mf-chip on" data-group="accstatus" data-val="new">New</div>
                                    <div class="mf-chip" data-group="accstatus" data-val="renew">Renewal</div>
                                </div>
                                <input type="hidden" name="accred_status" id="mf-h-accstatus" value="new">
                            </div>
                            <div class="mf-field">
                                <label for="mf-accred-month">Month</label>
                                <select id="mf-accred-month" name="accred_month">
                                    <option value="">— month —</option>
                                    <?php
                                    $months = [
                                        'January',
                                        'February',
                                        'March',
                                        'April',
                                        'May',
                                        'June',
                                        'July',
                                        'August',
                                        'September',
                                        'October',
                                        'November',
                                        'December'
                                    ];
                                    foreach ($months as $i => $m) {
                                        $val = $i + 1;
                                        echo "<option value='$val'>$m</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mf-field">
                                <label for="mf-accred-year">Year <span class="mf-req">*</span></label>
                                <input type="number" id="mf-accred-year" name="accred_year" min="2020" max="2035"
                                    placeholder="<?= date('Y') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mf-btn-row">
                    <button type="button" class="mf-btn mf-btn-ghost" id="mf-back-2">← Back</button>
                    <button type="button" class="mf-btn mf-btn-primary" id="mf-next-2">Continue → Documents</button>
                </div>

            </div><!-- /mf-panel-2 -->
