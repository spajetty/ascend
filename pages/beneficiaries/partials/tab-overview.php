<div id="tab-overview" class="tab-panel active">
    <div class="overview-grid">

        <!-- Personal Information -->
        <div class="info-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <h4 style="margin:0;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                    Personal Information
                </h4>
                <button class="edit-btn-icon" onclick="openEditPersonalModal()" style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/>
                    </svg>
                </button>
            </div>
            <div class="info-row">
                <div class="info-item"><label>Full Name</label><span id="pFullName">Rosa Cruz</span></div>
                <div class="info-item"><label>Gender</label><span id="pGender">Female</span></div>
                <div class="info-item"><label>Date of Birth</label><span id="pDob">January 14, 2002</span></div>
                <div class="info-item"><label>Civil Status</label><span id="pCivil">Single</span></div>
                <div class="info-item full-row"><label>Address</label><span id="pAddress">741 Ash Street, Fairview, Metro Manila</span></div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="side-col">
            <div class="info-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <h4 style="margin:0;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        Contact Information
                    </h4>
                    <button class="edit-btn-icon" onclick="openEditContactModal()" style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/>
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
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-5"/>
                            <polyline points="12 2 12 15"/>
                            <polyline points="8 11 12 15 16 11"/>
                        </svg>
                        Case Notes
                    </h4>
                    <button class="edit-btn-icon" onclick="openEditNotesModal()" style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/>
                        </svg>
                    </button>
                </div>
                <label style="font-size:11.5px;color:var(--text-muted);display:block;margin-bottom:5px;">Notes</label>
                <p id="pNotes" style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin:0;background:var(--bg);padding:10px;border-radius:7px;">
                    Still not hired after multiple visits.
                </p>
            </div>
        </div>
    </div>

    <!-- Education & Skills (or Job Fair events for Job Fair program) -->
    <div id="educationCard" class="info-card" style="margin-top:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                Education &amp; Skills
            </h4>
            <button class="edit-btn-icon" onclick="openEditEducationModal()" style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/>
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
                    <path d="M3 7h18v10H3z"/>
                    <path d="M3 12h18"/>
                </svg>
                Job Fair Events
            </h4>
        </div>
        <table class="emp-table">
            <thead>
                <tr>
                    <th>Event Type</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Position</th>
                </tr>
            </thead>
            <tbody id="pJobFairEvents">
                <tr><td colspan="4" style="color:var(--text-muted);text-align:center;padding:16px;">No job fair records.</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Employment Status -->
    <div class="info-card" style="margin-top:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <h4 style="margin:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                </svg>
                Employment Status
            </h4>
            <button class="edit-btn-icon" onclick="openEditEmploymentModal()" style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/>
                </svg>
            </button>
        </div>
        <table class="emp-table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody id="pEmployment"></tbody>
        </table>
    </div>
</div>