<!-- ══════════════════════════════════════
     LIST VIEW
══════════════════════════════════════ -->
<div id="listView">

    <!-- Stat Cards -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff; color:#2563eb;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Total Beneficiaries</div>
                <div class="stat-value" id="statTotal">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5; color:#059669;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                    <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7"/>
                    <path d="M7 6V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Hired</div>
                <div class="stat-value" id="statHired">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed; color:#ea580c;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 17L17 7"/>
                    <path d="M7 7h10v10"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Referred</div>
                <div class="stat-value" id="statReferred">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3f4f6; color:#4b5563;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="3" width="14" height="18" rx="2"/>
                    <path d="M8 7h6"/><path d="M8 11h6"/><path d="M8 15h4"/>
                    <path d="M18 13l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Registered</div>
                <div class="stat-value" id="statRegistered">0</div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="search-wrap">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" id="searchInput"
                   placeholder="Search by name, section, program, email, or contact…"
                   oninput="filterTable()">
        </div>
        <select id="sectionFilter" onchange="onSectionChange()">
            <option value="">All Sections</option>
        </select>
        <select id="programFilter" onchange="filterTable()">
            <option value="">All Programs</option>
        </select>
        <select id="statusFilter" onchange="filterTable()">
            <option value="">All Statuses</option>
        </select>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-wrap">
            <table id="beneficiariesTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Section</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div class="table-footer">
            <div style="display:flex;align-items:center;gap:16px;">
                <span id="footerLabel">Showing 0 of 0</span>
                <div style="display:flex;align-items:center;gap:8px;">
                    <label for="pageSizeSelect" style="font-size:12.5px;color:var(--text-secondary);">Rows per page:</label>
                    <select id="pageSizeSelect" onchange="changePageSize(this.value)"
                            style="padding:5px 12px;border:1px solid var(--border);border-radius:6px;background:var(--bg);color:var(--text-primary);font-size:13px;cursor:pointer;">
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>
            <div class="pagination" id="paginationControls"></div>
        </div>
    </div>

</div><!-- end listView -->