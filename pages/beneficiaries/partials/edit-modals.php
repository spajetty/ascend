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

<!-- ── Edit Employment Status Modal ── -->
<div id="modalEditEmployment" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add Employment Record</h3>
            <button class="modal-close" onclick="closeEditEmploymentModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Company</label>
                <input type="text" id="editEmploymentCompany" placeholder="Company name">
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
            <button class="btn-confirm" onclick="submitEditEmployment()">Add Record</button>
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
