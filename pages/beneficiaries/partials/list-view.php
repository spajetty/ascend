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

    <!-- Bulk Action Bar -->
    <div id="bulkActionBar" class="bulk-action-bar" style="display:none;">
        <div class="bulk-action-left">
            <span id="bulkCount" class="bulk-count">0 selected</span>
            <button class="bulk-clear-btn" onclick="clearSelection(); renderTable();" title="Clear selection">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Clear
            </button>
        </div>
        <div class="bulk-action-right">
            <button class="bulk-btn bulk-btn-classify" onclick="openBulkClassifyModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                Update Classification
            </button>
            <button class="bulk-btn bulk-btn-delete" onclick="openBulkDeleteModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                Delete
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-wrap">
            <table id="beneficiariesTable">
                <thead>
                    <tr>
                        <th class="th-check">
                            <label class="check-wrap">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                                <span class="checkmark"></span>
                            </label>
                        </th>
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