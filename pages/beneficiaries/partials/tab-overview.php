<div id="tab-overview" class="tab-panel active">
    <div class="overview-grid">

        <!-- Personal Information -->
        <div class="info-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <h4 style="margin:0;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                    </svg>
                    Personal Information
                </h4>
                <button class="edit-btn-icon" onclick="openEditPersonalModal()"
                    style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                    </svg>
                </button>
            </div>
            <div class="info-row">
                <div class="info-item"><label>Full Name</label><span id="pFullName">Rosa Cruz</span></div>
                <div class="info-item"><label>Gender</label><span id="pGender">Female</span></div>
                <div class="info-item"><label>Date of Birth</label><span id="pDob">January 14, 2002</span></div>
                <div class="info-item"><label>Civil Status</label><span id="pCivil">Single</span></div>
                <div class="info-item full-row"><label>Address</label><span id="pAddress">741 Ash Street, Fairview,
                        Metro Manila</span></div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="side-col">
            <div class="info-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <h4 style="margin:0;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        Contact Information
                    </h4>
                    <button class="edit-btn-icon" onclick="openEditContactModal()"
                        style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                        </svg>
                    </button>
                </div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <div class="contact-email">
                        <label style="font-size:11.5px;color:var(--text-muted);">Email</label>
                        <a href="mailto:rose.cruz@email.com" id="pEmail">rose.cruz@email.com</a>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Phone</label>
                        <div style="font-size:13.5px;font-weight:500;" id="pPhone">09261234567</div>
                    </div>
                </div>
            </div>
            <div class="info-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <h4 style="margin:0;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M9 11H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-5" />
                            <polyline points="12 2 12 15" />
                            <polyline points="8 11 12 15 16 11" />
                        </svg>
                        Case Notes
                    </h4>
                    <button class="edit-btn-icon" onclick="openEditNotesModal()"
                        style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                        </svg>
                    </button>
                </div>
                <label style="font-size:11.5px;color:var(--text-muted);display:block;margin-bottom:5px;">Notes</label>
                <p id="pNotes"
                    style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin:0;background:var(--bg);padding:10px;border-radius:7px;">
                    No case notes yet.
                </p>
            </div>
        </div>
    </div>

    <!-- Education & Skills (or Job Fair events for Job Fair program) -->
    <div id="educationCard" class="info-card" style="margin-top:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
                Education &amp; Skills
            </h4>
            <button class="edit-btn-icon" onclick="openEditEducationModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                </svg>
            </button>
        </div>
        <div style="margin-bottom:10px;">
            <label style="font-size:11.5px;color:var(--text-muted);">Highest Educational Attainment</label>
            <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pEducation">High School Graduate</div>
        </div>
        <div id="pSkills"></div>
    </div>

    <!-- Job Fair events (hidden by default; shown when program === 'Job Fair') -->
    <div id="jobFairCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7h18v10H3z" />
                    <path d="M3 12h18" />
                </svg>
                Job Fair Events
            </h4>
            <button class="edit-btn-icon" onclick="openAddJobFairModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;background:var(--accent);color:white;border:none;border-radius:6px;cursor:pointer;font-size:12px;font-weight:500;"
                title="Add Job Fair Event">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add
            </button>
        </div>
        <div class="table-wrap">
            <table class="emp-table" style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Event Type</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Company</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="pJobFairEvents">
                <tr>
                    <td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">No job fair records.
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>

    <!-- First Time Job Seeker issuance status (hidden by default) -->
    <div id="firstTimeJobSeekerCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 7h16v10H4z" />
                    <path d="M8 11h8" />
                </svg>
                Issuance Status
            </h4>
            <button class="edit-btn-icon" onclick="openEditIssuanceModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                </svg>
            </button>
        </div>
        <div class="table-wrap">
            <table class="emp-table" style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Occupational Permit</th>
                    <th>Health Card</th>
                </tr>
            </thead>
            <tbody id="pFirstTimeJobSeekerIssuance">
                <tr>
                    <td colspan="2" style="color:var(--text-muted);text-align:center;padding:16px;">No issuance records.
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>

    <!-- WHIP project assignment (hidden by default) -->
    <div id="whipCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7h18v10H3z" />
                    <path d="M7 7v10" />
                    <path d="M17 7v10" />
                </svg>
                Project Assignment
            </h4>
            <button class="edit-btn-icon" onclick="openAddWhipModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;background:var(--accent);color:white;border:none;border-radius:6px;cursor:pointer;font-size:12px;font-weight:500;"
                title="Add Project Assignment">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add
            </button>
        </div>
        <div class="table-wrap">
            <table class="emp-table" style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Date Hired</th>
                    <th>Company</th>
                    <th>Project</th>
                    <th>Duration</th>
                    <th>Budget</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="pWhipProjects">
                <tr>
                    <td colspan="7" style="color:var(--text-muted);text-align:center;padding:16px;">No project records.
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>

    <!-- WIIRP info (hidden by default) -->
    <div id="wiirpCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
                WIIRP Information
            </h4>
            <button class="edit-btn-icon" onclick="openEditWiirpModal('record')"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                </svg>
            </button>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:10px;">
            <div class="info-card" style="margin:0;padding:12px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                    <strong style="font-size:13px;">Academic Details</strong>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Contract Period</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpContractPeriod">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">School</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpSchool">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Course</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpCourse">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Year Level</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpYearLevel">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Required Hours</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpRequiredHours">—</div>
                    </div>
                </div>
            </div>
            <div class="info-card" style="margin:0;padding:12px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                    <strong style="font-size:13px;">Preferences</strong>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Inquiry Category</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpInquiryType">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Preferred Organization</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpPreferredOrgType">—
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Preferred Industry</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpPreferredIndustry">—
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Willing Outside Area</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpWillingOutside">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Internship Schedule</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpInternshipSched">—</div>
                    </div>
                </div>
            </div>
            <div class="info-card" style="margin:0;padding:12px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                    <strong style="font-size:13px;">Placement Summary</strong>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Start Date</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpStartDate">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Placement Type</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pWiirpType">—</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WIIRP assignment details (hidden by default) -->
    <div id="wiirpAssignmentCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                </svg>
                Assignment Details
            </h4>
            
            <button class="edit-btn-icon" onclick="openAddWiirpAssignmentModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;background:var(--accent);color:white;border:none;border-radius:6px;cursor:pointer;font-size:12px;font-weight:500;"
                title="Add Wiirp Assignment">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add
            </button>
        </div>
        <div class="table-wrap">
            <table class="emp-table" style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Required Hours</th>
                    <th>Office Assignment</th>
                    <th id="wiirpEndorsement1Header">Endorsement 1</th>
                    <th id="wiirpEndorsement2Header">Endorsement 2</th>
                </tr>
            </thead>
            <tbody id="pWiirpAssignments">
                <tr>
                    <td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">No assignment
                        records.</td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>

    <!-- SPES student info (hidden by default) -->
    <div id="spesStudentCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
                Student Information
            </h4>
            <button class="btn-cancel" style="padding:7px 12px;font-size:12px;" onclick="openEditSpesModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                </svg>
            </button>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div style="flex:1 1 calc(50% - 5px);min-width:180px;">
                <label style="font-size:11.5px;color:var(--text-muted);">Student Type</label>
                <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pSpesStudentType">—</div>
            </div>
            <div style="flex:1 1 calc(50% - 5px);min-width:180px;">
                <label style="font-size:11.5px;color:var(--text-muted);">Highest Educational Attainment</label>
                <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pSpesHighestEduc">—</div>
            </div>
            <div style="flex:1 1 calc(50% - 5px);min-width:180px;">
                <label style="font-size:11.5px;color:var(--text-muted);">Course</label>
                <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pSpesCourse">—</div>
            </div>
            <div style="flex:1 1 calc(50% - 5px);min-width:180px;">
                <label style="font-size:11.5px;color:var(--text-muted);">School</label>
                <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pSpesSchool">—</div>
            </div>
        </div>
    </div>

    <!-- SPES employment (hidden by default) -->
    <div id="spesEmploymentCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                </svg>
                OJT Employment
            </h4>
            <button class="edit-btn-icon" onclick="openAddSpesEmploymentModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;background:var(--accent);color:white;border:none;border-radius:6px;cursor:pointer;font-size:12px;font-weight:500;"
                title="Add OJT Employment">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add
            </button>
        </div>
        <div class="table-wrap">
            <table class="emp-table" style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Store Assignment</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Days</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="pSpesEmployment">
                <tr>
                    <td colspan="7" style="color:var(--text-muted);text-align:center;padding:16px;">No OJT records.</td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>

    <!-- GIP (Government Internship Program) (hidden by default) -->
    <div id="gipCard" class="info-card" style="margin-top:14px;display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
                Government Internship Program
            </h4>
            <button class="edit-btn-icon" onclick="openEditGipModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3" />
                </svg>
            </button>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <div class="info-card" style="margin:0;padding:12px;flex:1;min-width:260px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                    <strong style="font-size:13px;">Academic Details</strong>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div>
                            <label style="font-size:11.5px;color:var(--text-muted);">Student Type</label>
                            <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipStudentType">—</div>
                        </div>
                        <div>
                            <label style="font-size:11.5px;color:var(--text-muted);">Highest Educational Attainment</label>
                            <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipHighestEduc">—</div>
                        </div>
                    </div>
                    <div id="gipSchoolWrapper">
                        <label style="font-size:11.5px;color:var(--text-muted);">School</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipSchool">—</div>
                    </div>
                    <div id="gipCourseWrapper">
                        <label style="font-size:11.5px;color:var(--text-muted);">Course</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipCourse">—</div>
                    </div>
                </div>
            </div>

            <div id="gipContractCard" class="info-card" style="margin:0;padding:12px;flex:1;min-width:260px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                    <strong style="font-size:13px;">Contract Details</strong>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div>
                            <label style="font-size:11.5px;color:var(--text-muted);">Start of Contract</label>
                            <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipStartContract">—</div>
                        </div>
                        <div>
                            <label style="font-size:11.5px;color:var(--text-muted);">End of Contract</label>
                            <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipEndContract">—</div>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">No. of Days</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipDays">—</div>
                    </div>
                    <div id="gipLguExtrasWrapper">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div>
                                <label style="font-size:11.5px;color:var(--text-muted);">Proponent</label>
                                <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipProponent">—</div>
                            </div>
                            <div>
                                <label style="font-size:11.5px;color:var(--text-muted);">Status</label>
                                <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipStatus">—</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card" style="margin:0;padding:12px;flex:1;min-width:260px;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                    <strong style="font-size:13px;">Placement</strong>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div id="gipOfficeAssignmentWrapper">
                        <label style="font-size:11.5px;color:var(--text-muted);">Office Assignment</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipOfficeAssignment">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Placement Type</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipType">—</div>
                    </div>
                </div>
            </div>

            <div id="gipDoleCard" class="info-card" style="margin:0;padding:12px;flex:1;min-width:260px;display:none;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                    <strong style="font-size:13px;">GSIS Details</strong>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">GSIS Beneficiary</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipGsisBeneficiary">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">Relationship</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipRelationship">—</div>
                    </div>
                    <div>
                        <label style="font-size:11.5px;color:var(--text-muted);">GSIS Contact No.</label>
                        <div style="font-size:13.5px;font-weight:500;margin-top:2px;" id="pGipGsisContactNo">—</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employment Status -->
    <div class="info-card" style="margin-top:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                </svg>
                Employment Status
            </h4>
            <button class="edit-btn-icon" onclick="openAddEmploymentModal()"
                style="padding:4px 8px;display:flex;align-items:center;gap:5px;background:var(--accent);color:white;border:none;border-radius:6px;cursor:pointer;font-size:12px;font-weight:500;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add
            </button>
        </div>
        <div class="table-wrap">
            <table class="emp-table" style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="pEmployment"></tbody>
            </table>
        </div>
    </div>
</div>