<!-- ── Edit Personal Information Modal ── -->
<div id="modalEditPersonal" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Personal Information</h3>
            <button class="modal-close" onclick="closeEditPersonalModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Full Name</label>
                <input type="text" id="editPersonalName" placeholder="First, Middle, Last name">
            </div>
            <div class="modal-field">
                <label>Date of Birth</label>
                <input type="date" id="editPersonalDob">
            </div>
            <div class="modal-field">
                <label>Gender</label>
                <select id="editPersonalGender">
                    <option value="">— Select —</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Civil Status</label>
                <select id="editPersonalCivil">
                    <option value="">— Select —</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                </select>
            </div>
            <div class="modal-field">
                <label>House No. / Street</label>
                <input type="text" id="editPersonalHouse" placeholder="House #, Street">
            </div>
            <div class="modal-field">
                <label>Barangay</label>
                <input type="text" id="editPersonalBarangay" placeholder="Barangay">
            </div>
            <div class="modal-field">
                <label>District</label>
                <input type="text" id="editPersonalDistrict" placeholder="District">
            </div>
            <div class="modal-field">
                <label>City / Municipality</label>
                <input type="text" id="editPersonalCity" placeholder="City / Municipality">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditPersonalModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditPersonal()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Edit Contact Information Modal ── -->
<div id="modalEditContact" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Contact Information</h3>
            <button class="modal-close" onclick="closeEditContactModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Email</label>
                <input type="email" id="editContactEmail" placeholder="email@example.com">
            </div>
            <div class="modal-field">
                <label>Phone</label>
                <input type="tel" id="editContactPhone" placeholder="09XXXXXXXXX">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditContactModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditContact()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Edit Case Notes Modal ── -->
<div id="modalEditNotes" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Case Notes</h3>
            <button class="modal-close" onclick="closeEditNotesModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Notes</label>
                <textarea id="editNotesText" rows="6" placeholder="Add or update case notes..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditNotesModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditNotes()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Edit Education & Skills Modal ── -->
<div id="modalEditEducation" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Education & Skills</h3>
            <button class="modal-close" onclick="closeEditEducationModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Highest Educational Attainment</label>
                <select id="editEducationLevel">
                    <option value="">— Select —</option>
                    <option value="Elementary">Elementary</option>
                    <option value="High School">High School</option>
                    <option value="High School Graduate">High School Graduate</option>
                    <option value="College">College</option>
                    <option value="College Graduate">College Graduate</option>
                    <option value="Vocational">Vocational</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Skills <span style="color:var(--text-muted);font-weight:400;">(comma-separated)</span></label>
                <input type="text" id="editEducationSkills" placeholder="e.g. Communication, Teamwork, Problem Solving">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditEducationModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditEducation()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Edit SPES Student Information Modal ── -->
<div id="modalEditSpes" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:720px;">
        <div class="modal-header">
            <h3>Edit SPES Student Information</h3>
            <button class="modal-close" onclick="closeEditSpesModal()">✕</button>
        </div>
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
            <input type="hidden" id="editSpesId" value="">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px;">
                <div class="modal-field">
                    <label>Student Type</label>
                    <select id="editSpesStudentType">
                        <option value="student">Student</option>
                        <option value="osy">OSY</option>
                    </select>
                </div>
                <div class="modal-field">
                    <label>Highest Educational Attainment</label>
                    <input type="text" id="editSpesHighestEduc" placeholder="Highest educational attainment">
                </div>
                <div class="modal-field">
                    <label>Course</label>
                    <input type="text" id="editSpesCourse" placeholder="Course">
                </div>
                <div class="modal-field">
                    <label>School</label>
                    <input type="text" id="editSpesSchool" placeholder="School">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditSpesModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditSpes()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Edit SPES OJT Employment Modal ── -->
<div id="modalEditSpesEmployment" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:680px;">
        <div class="modal-header">
            <h3>Edit SPES OJT Employment</h3>
            <button class="modal-close" onclick="closeEditSpesEmploymentModal()">✕</button>
        </div>
        <div class="modal-body" style="display:flex;flex-direction:column;gap:12px;">
            <input type="hidden" id="editSpesEmploymentId" value="">

            <div class="modal-field">
                <label>Company</label>
                <select id="editSpesEmploymentCompany" style="width:100%;">
                    <option value="">Loading companies…</option>
                </select>
            </div>

            <div class="modal-field">
                <label>Store Assignment</label>
                <input type="text" id="editSpesEmploymentStore" placeholder="e.g. Sales Floor, Backroom">
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;">
                <div class="modal-field">
                    <label>Start of Contract</label>
                    <input type="date" id="editSpesEmploymentStart">
                </div>
                <div class="modal-field">
                    <label>End of Contract</label>
                    <input type="date" id="editSpesEmploymentEnd">
                </div>
                <div class="modal-field">
                    <label>Days</label>
                    <input type="number" id="editSpesEmploymentDays" min="0">
                </div>
            </div>

            <div class="modal-field">
                <label>Category</label>
                <select id="editSpesEmploymentCategory">
                    <option value="">— Select —</option>
                    <option value="lgu">LGU</option>
                    <option value="private">Private</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditSpesEmploymentModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditSpesEmployment()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Edit Government Internship Program Modal ── -->
<div id="modalEditGip" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:860px;">
        <div class="modal-header">
            <h3>Edit Government Internship Program</h3>
            <button class="modal-close" onclick="closeEditGipModal()">✕</button>
        </div>
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
            <input type="hidden" id="editGipId" value="">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px;">
                <div class="info-card" style="margin:0;padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                        <strong style="font-size:13px;">Academic Details</strong>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="modal-field">
                            <label>Contract Period</label>
                            <input type="text" id="editGipContractPeriod" placeholder="e.g. June 2026 to November 2026">
                        </div>
                        <div class="modal-field">
                            <label>School</label>
                            <input type="text" id="editGipSchool" placeholder="School name">
                        </div>
                        <div class="modal-field">
                            <label>Course</label>
                            <input type="text" id="editGipCourse" placeholder="Course">
                        </div>
                        <div class="modal-field">
                            <label>Required Hours</label>
                            <input type="number" id="editGipRequiredHours" min="0" step="1" placeholder="Required hours">
                        </div>
                        <div class="modal-field">
                            <label>Education Level</label>
                            <select id="editGipCollegeOrShs">
                                <option value="">— Select —</option>
                                <option value="college">College</option>
                                <option value="shs">Senior High School</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="info-card" style="margin:0;padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                        <strong style="font-size:13px;">Preferences</strong>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="modal-field">
                            <label>Preferred Organization</label>
                            <input type="text" id="editGipPreferredOrgType" placeholder="Preferred organization type">
                        </div>
                        <div class="modal-field">
                            <label>Preferred Industry</label>
                            <input type="text" id="editGipPreferredIndustry" placeholder="Preferred industry">
                        </div>
                        <div class="modal-field">
                            <label>Willing Outside Area</label>
                            <select id="editGipWillingOutside">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="info-card" style="margin:0;padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                        <strong style="font-size:13px;">Placement</strong>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="modal-field">
                            <label>Office Assignment</label>
                            <input type="text" id="editGipOfficeAssignment" placeholder="Office assignment">
                        </div>
                        <div class="modal-field">
                            <label>Placement Type</label>
                            <select id="editGipType">
                                <option value="">— Select —</option>
                                <option value="DOLE">DOLE</option>
                                <option value="LGU">LGU</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditGipModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditGip()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Edit WIIRP Modal ── -->
<div id="modalEditWiirp" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:980px;">
        <div class="modal-header">
            <h3>Edit WIIRP Information</h3>
            <button class="modal-close" onclick="closeEditWiirpModal()">✕</button>
        </div>
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
            <input type="hidden" id="editWiirpWorkImmersionId" value="">
            <input type="hidden" id="editWiirpAssignmentId" value="">

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;">
                <div class="info-card" style="margin:0;padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                        <strong style="font-size:13px;">Academic Details</strong>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="modal-field">
                            <label>Contract Period</label>
                            <input type="text" id="editWiirpContractPeriod" placeholder="Contract period">
                        </div>
                        <div class="modal-field">
                            <label>School</label>
                            <input type="text" id="editWiirpSchool" placeholder="School">
                        </div>
                        <div class="modal-field">
                            <label>Course</label>
                            <input type="text" id="editWiirpCourse" placeholder="Course">
                        </div>
                        <div class="modal-field">
                            <label>Required Hours</label>
                            <input type="number" id="editWiirpRequiredHours" min="0" step="1" placeholder="Required hours">
                        </div>
                        <div class="modal-field">
                            <label>Year Level</label>
                            <input type="text" id="editWiirpYearLevel" placeholder="Year level">
                        </div>
                    </div>
                </div>

                <div class="info-card" style="margin:0;padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                        <strong style="font-size:13px;">Preferences</strong>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="modal-field">
                            <label>Inquiry Type</label>
                            <input type="text" id="editWiirpInquiryType" placeholder="Inquiry type">
                        </div>
                        <div class="modal-field">
                            <label>Preferred Organization</label>
                            <input type="text" id="editWiirpPreferredOrgType" placeholder="Preferred organization">
                        </div>
                        <div class="modal-field">
                            <label>Preferred Industry</label>
                            <input type="text" id="editWiirpPreferredIndustry" placeholder="Preferred industry">
                        </div>
                        <div class="modal-field">
                            <label>Willing Outside Area</label>
                            <select id="editWiirpWillingOutside">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="modal-field">
                            <label>Internship Schedule</label>
                            <input type="text" id="editWiirpInternshipSched" placeholder="Internship schedule">
                        </div>
                    </div>
                </div>

                <div class="info-card" style="margin:0;padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:999px;background:var(--accent);"></span>
                        <strong style="font-size:13px;">Placement Summary</strong>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="modal-field">
                            <label>Start Date</label>
                            <input type="date" id="editWiirpStartDate">
                        </div>
                        <div class="modal-field">
                            <label>Placement Type</label>
                            <p id="displayWiirpType" class="readonly-field">—</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditWiirpModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditWiirp()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Add/Edit Issuance Status Modal (First Time Jobseeker) ── -->
<div id="modalEditIssuance" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-header">
            <h3>Edit Issuance Status</h3>
            <button class="modal-close" onclick="closeEditIssuanceModal()">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editIssuanceJobseekId" value="">
            <div class="modal-field">
                <label>Occupational Permit</label>
                <select id="editIssuanceOccPermit">
                    <option value="0">Not issued</option>
                    <option value="1">Issued</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Health Card</label>
                <select id="editIssuanceHealthCard">
                    <option value="0">Not issued</option>
                    <option value="1">Issued</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditIssuanceModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitUpdateIssuance()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Add Employment Status Modal ── -->
<div id="modalAddEmployment" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add Employment Record</h3>
            <button class="modal-close" onclick="closeAddEmploymentModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Company</label>
                <select id="addEmploymentCompany" style="width:100%;">
                    <option value="">Loading companies…</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Status</label>
                <select id="addEmploymentStatus">
                    <option value="">— Select —</option>
                    <option value="Currently Employed">Currently Employed</option>
                    <option value="Previously Employed">Previously Employed</option>
                    <option value="Unemployed">Unemployed</option>
                    <option value="Self-Employed">Self-Employed</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Date</label>
                <input type="date" id="addEmploymentDate">
            </div>
            <div class="modal-field">
                <label>Notes <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
                <textarea id="addEmploymentNotes" rows="3" placeholder="Employment details..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeAddEmploymentModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitAddEmployment()">Add Record</button>
        </div>
    </div>
</div>

<!-- ── Edit Employment Status Modal ── -->
<div id="modalEditEmployment" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Employment Record</h3>
            <button class="modal-close" onclick="closeEditEmploymentModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Company</label>
                <select id="editEmploymentCompany" style="width:100%;">
                    <option value="">Loading companies…</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Status</label>
                <select id="editEmploymentStatus">
                    <option value="">— Select —</option>
                    <option value="Currently Employed">Currently Employed</option>
                    <option value="Previously Employed">Previously Employed</option>
                    <option value="Unemployed">Unemployed</option>
                    <option value="Self-Employed">Self-Employed</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Date</label>
                <input type="date" id="editEmploymentDate">
            </div>
            <div class="modal-field">
                <label>Notes <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
                <textarea id="editEmploymentNotes" rows="3" placeholder="Employment details..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditEmploymentModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitUpdateEmployment()">Update Record</button>
        </div>
    </div>
</div>

<!-- ── Edit Google Drive Link Modal ── -->
<div id="modalEditDrive" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Google Drive Link</h3>
            <button class="modal-close" onclick="closeEditDriveModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>File Name</label>
                <input type="text" id="editDriveFileName" placeholder="e.g. Resume_2025.pdf" readonly>
            </div>
            <div class="modal-field">
                <label>Google Drive Link</label>
                <input type="url" id="editDriveLink" placeholder="https://drive.google.com/file/d/...">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditDriveModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditDrive()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Delete Employment Record Modal ── -->
<div id="modalDeleteEmployment" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <h3>Delete Employment Record</h3>
            <button class="modal-close" onclick="closeDeleteEmploymentModal()">✕</button>
        </div>
        <div class="modal-body">
            <p style="margin:0;color:var(--text-secondary);">Are you sure you want to delete this employment record?</p>
            <p style="margin:8px 0 0 0;font-size:13px;color:var(--text-muted);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeDeleteEmploymentModal()">Cancel</button>
            <button class="btn-confirm" onclick="confirmDeleteEmploymentRecord()" style="background:#ef4444;color:white;">Delete</button>
        </div>
    </div>
</div>

<!-- ── Bulk Delete Beneficiaries Modal ── -->
<div id="modalBulkDelete" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:440px;">
        <div class="modal-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="width:34px;height:34px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                </span>
                <h3 style="color:#b91c1c;">Delete Beneficiaries</h3>
            </div>
            <button class="modal-close" onclick="closeBulkDeleteModal()">✕</button>
        </div>
        <div class="modal-body">
            <p style="margin:0;color:var(--text-secondary);line-height:1.6;">
                You are about to permanently delete
                <strong id="bulkDeleteCount" style="color:var(--text-primary);">0</strong>
                beneficiar<span id="bulkDeleteWord">ies</span>.
            </p>
            <p style="margin:10px 0 0;font-size:13px;color:var(--text-muted);">This action cannot be undone. All associated records will be removed.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeBulkDeleteModal()">Cancel</button>
            <button class="btn-confirm" onclick="confirmBulkDelete()" style="background:#ef4444;color:#fff;">
                Delete Selected
            </button>
        </div>
    </div>
</div>

<!-- ── Bulk Update Classification Modal ── -->
<div id="modalBulkClassify" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="width:34px;height:34px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                </span>
                <h3>Update Classification</h3>
            </div>
            <button class="modal-close" onclick="closeBulkClassifyModal()">✕</button>
        </div>
        <div class="modal-body" style="gap:12px;">

            <!-- Summary rows -->
            <div class="bulk-classify-info">
                <div class="bulk-info-row">
                    <span class="bulk-info-label">Applying to</span>
                    <span class="bulk-info-value"><strong id="bulkClassifyCount">0</strong> beneficiaries</span>
                </div>
                <div class="bulk-info-row" id="bulkClassifyProgramRow">
                    <span class="bulk-info-label">Program</span>
                    <span class="bulk-info-value" id="bulkClassifyProgramName" style="font-weight:600;color:var(--text-primary);">—</span>
                </div>
            </div>

            <!-- Mixed-program warning (shown only for Case 2) -->
            <div id="bulkClassifyWarning" class="bulk-classify-warning" style="display:none;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span id="bulkClassifyWarningText"></span>
            </div>

            <!-- Status field (hidden when mixed programs) -->
            <div class="modal-field" id="bulkClassifyStatusField">
                <label>New Status</label>
                <select id="bulkClassifyStatus">
                    <option value="">— Select status —</option>
                </select>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeBulkClassifyModal()">Cancel</button>
            <button class="btn-confirm" id="bulkClassifySubmitBtn" onclick="confirmBulkClassify()">Apply to All</button>
        </div>
    </div>
</div>

<!-- ── Edit Job Fair Modal ── -->
<div id="modalEditJobFair" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Job Fair Record</h3>
            <button class="modal-close" onclick="closeEditJobFairModal()">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editJobFairId" value="">
            <div class="modal-field">
                <label>Job Fair Event / Venue</label>
                <select id="editJobFairEvent" onchange="loadJobFairCompanies(this.value)" style="max-width:100%; text-overflow:ellipsis;">
                    <option value="">Loading events…</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Company</label>
                <select id="editJobFairCompany" style="max-width:100%; text-overflow:ellipsis;">
                    <option value="">— Select event first —</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Position</label>
                <input type="text" id="editJobFairPosition" placeholder="Position applied for">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditJobFairModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditJobFair()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Add Job Fair Modal ── -->
<div id="modalAddJobFair" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add Job Fair Record</h3>
            <button class="modal-close" onclick="closeAddJobFairModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Job Fair Event / Venue</label>
                <select id="addJobFairEvent" onchange="loadJobFairCompanies(this.value, '', 'addJobFairCompany')" style="max-width:100%; text-overflow:ellipsis;">
                    <option value="">Loading events…</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Company</label>
                <select id="addJobFairCompany" style="max-width:100%; text-overflow:ellipsis;">
                    <option value="">— Select event first —</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Position</label>
                <input type="text" id="addJobFairPosition" placeholder="Position applied for">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeAddJobFairModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitAddJobFair()">Add Record</button>
        </div>
    </div>
</div>

<!-- ── Delete Job Fair Record Modal ── -->
<div id="modalDeleteJobFair" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <h3>Delete Job Fair Record</h3>
            <button class="modal-close" onclick="closeDeleteJobFairModal()">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="deleteJobFairId" value="">
            <p style="margin:0;color:var(--text-secondary);">Are you sure you want to delete this job fair record?</p>
            <p style="margin:8px 0 0 0;font-size:13px;color:var(--text-muted);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeDeleteJobFairModal()">Cancel</button>
            <button class="btn-confirm" onclick="confirmDeleteJobFairRecord()" style="background:#ef4444;color:white;">Delete</button>
        </div>
    </div>
</div>

<!-- ── Add WHIP Assignment Modal ── -->
<div id="modalAddWhip" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add Project Assignment</h3>
            <button class="modal-close" onclick="closeAddWhipModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Project</label>
                <select id="addWhipProject" style="max-width:100%; text-overflow:ellipsis;">
                    <option value="">Loading projects…</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Position</label>
                <input type="text" id="addWhipPosition" placeholder="Position / Role">
            </div>
            <div class="modal-field">
                <label>Date Hired</label>
                <input type="date" id="addWhipDateHired">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeAddWhipModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitAddWhip()">Add Assignment</button>
        </div>
    </div>
</div>

<!-- ── Edit WHIP Assignment Modal ── -->
<div id="modalEditWhip" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Project Assignment</h3>
            <button class="modal-close" onclick="closeEditWhipModal()">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editWhipId" value="">
            <div class="modal-field">
                <label>Project</label>
                <select id="editWhipProject" style="max-width:100%; text-overflow:ellipsis;">
                    <option value="">Loading projects…</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Position</label>
                <input type="text" id="editWhipPosition" placeholder="Position / Role">
            </div>
            <div class="modal-field">
                <label>Date Hired</label>
                <input type="date" id="editWhipDateHired">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeEditWhipModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitEditWhip()">Save Changes</button>
        </div>
    </div>
</div>

<!-- ── Delete WHIP Assignment Modal ── -->
<div id="modalDeleteWhip" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <h3>Delete Project Assignment</h3>
            <button class="modal-close" onclick="closeDeleteWhipModal()">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="deleteWhipId" value="">
            <p style="margin:0;color:var(--text-secondary);">Are you sure you want to delete this project assignment?</p>
            <p style="margin:8px 0 0 0;font-size:13px;color:var(--text-muted);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeDeleteWhipModal()">Cancel</button>
            <button class="btn-confirm" onclick="confirmDeleteWhipRecord()" style="background:#ef4444;color:white;">Delete</button>
        </div>
    </div>
</div>

