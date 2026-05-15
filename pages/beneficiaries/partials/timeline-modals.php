<!-- ── Log Visit Modal ── -->
<div id="modalLogVisit" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Log a PESO Visit</h3>
            <button class="modal-close" onclick="closeLogVisitModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Visit Number</label>
                <input type="text" id="visitNumberDisplay" disabled style="background:var(--bg);color:var(--text-muted);">
            </div>
            <div class="modal-field">
                <label>Date</label>
                <input type="date" id="visitDate">
            </div>
            <div class="modal-field">
                <label>Notes <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
                <textarea id="visitNotes" rows="3" placeholder="e.g. Follow-up consultation. Applicant still seeking placement."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeLogVisitModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitLogVisit()">Save Visit</button>
        </div>
    </div>
</div>

<!-- ── Add Referral Modal ── -->
<div id="modalAddReferral" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add Referral</h3>
            <button class="modal-close" onclick="closeAddReferralModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Company</label>
                <select id="referralCompany">
                    <option value="">— Select employer —</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Position</label>
                <input type="text" id="referralPosition" placeholder="e.g. Service Crew">
            </div>
            <div class="modal-field">
                <label>Status</label>
                <select id="referralStatus">
                    <option value="PENDING">Pending</option>
                    <option value="PROCESSING">Processing</option>
                    <option value="HIRED">Hired</option>
                    <option value="REJECTED">Rejected</option>
                    <option value="NO_FEEDBACK">No Feedback</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Date</label>
                <input type="date" id="referralDate">
            </div>
            <div class="modal-field">
                <label>Notes <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
                <textarea id="referralNotes" rows="3" placeholder="e.g. Referred to SM Supermalls for store associate position."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeAddReferralModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitAddReferral()">Save Referral</button>
        </div>
    </div>
</div>

<!-- ── Delete Timeline Item Modal ── -->
<div id="modalDeleteTimelineItem" class="timeline-modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <h3>Delete Timeline Record</h3>
            <button class="modal-close" onclick="closeDeleteTimelineModal()">✕</button>
        </div>
        <div class="modal-body">
            <p style="margin:0;color:var(--text-secondary);">Are you sure you want to delete this timeline record?</p>
            <p style="margin:8px 0 0 0;font-size:13px;color:var(--text-muted);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeDeleteTimelineModal()">Cancel</button>
            <button class="btn-confirm" onclick="confirmDeleteTimelineItem()" style="background:#ef4444;color:white;">Delete</button>
        </div>
    </div>
</div>
