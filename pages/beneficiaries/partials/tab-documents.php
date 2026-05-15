<div id="tab-documents" class="tab-panel">
    <div class="docs-layout" style="margin-top:14px;">

        <!-- Document List -->
        <div class="doc-list">
            <div class="doc-list-header">
                <h4>📁 Documents</h4>
                <button class="add-drive-btn" onclick="openInlineDriveForm()">+ Add Drive Link</button>
            </div>

            <!-- Inline Add Drive Form -->
            <div id="inlineDriveForm" class="drive-inline-form" aria-hidden="true">
                <div class="drive-modal-header">
                    <h3>Add Google Drive File Link</h3>
                </div>
                <div class="drive-field">
                    <label for="inlineDriveFileName" style="font-size:12px;font-weight:600;color:var(--text-secondary);">File Name</label>
                    <input type="text" id="inlineDriveFileName" placeholder="e.g. Resume_2025.pdf">
                </div>
                <div class="drive-field">
                    <label for="inlineDriveLinkUrl" style="font-size:12px;font-weight:600;color:var(--text-secondary);">Google Drive Link</label>
                    <input type="url" id="inlineDriveLinkUrl" placeholder="https://drive.google.com/file/d/...">
                </div>

                <div class="drive-modal-actions">
                    <button class="btn-add" onclick="submitInlineDriveLink()">Add Document</button>
                    <button class="btn-cancel" onclick="closeInlineDriveForm()">Cancel</button>
                </div>
            </div>

            <!-- Document Items -->
            <div id="docListItems">
                <div class="doc-empty-state" style="padding:16px;color:var(--text-muted);text-align:center;">
                    Select a beneficiary to load their documents.
                </div>
            </div>
        </div>

        <!-- Document Details Panel -->
        <div class="doc-details">
            <h4>👁 Document Details</h4>
            <div class="doc-detail-file">
                <div class="doc-thumb pdf" style="width:34px;height:34px;font-size:8px;"><span>FILE</span></div>
                <div>
                    <div class="doc-detail-name" id="detailName">No document selected</div>
                </div>
            </div>
            <div class="detail-row"><span>File Type</span><span id="detailType">—</span></div>
            <div class="drive-link-section">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <label style="margin:0;">Google Drive Link</label>
                    <button class="edit-btn-icon" onclick="openEditDriveModal()" style="padding:4px 8px;display:flex;align-items:center;gap:5px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3"/>
                        </svg>
                    </button>
                </div>
                <div class="drive-url">🔗 <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">—</span></div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <button class="open-drive-btn" type="button">Open in Drive</button>
                </div>
            </div>
        </div>

    </div>
</div>