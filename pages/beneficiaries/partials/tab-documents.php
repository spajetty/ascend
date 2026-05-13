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
                <div class="doc-item selected" onclick="selectDoc(this,'Resume_RosaCruz_2025.pdf','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Resume_RosaCruz_2025.pdf</div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'Transcript_of_Records.pdf','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Transcript_of_Records.pdf</div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'Barangay_Clearance.jpg','JPG','245 KB','April 18, 2025')">
                    <div class="doc-thumb img"><span>JPG</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Barangay_Clearance.jpg</div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'NBI_Clearance_2025.png','PNG','245 KB','April 18, 2025')">
                    <div class="doc-thumb img"><span>PNG</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">NBI_Clearance_2025.png</div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'Birth_Certificate_PSA.pdf','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Birth_Certificate_PSA.pdf</div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'TESDA_Certificate.pdf','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">TESDA_Certificate.pdf</div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
            </div>
        </div>

        <!-- Document Details Panel -->
        <div class="doc-details">
            <h4>👁 Document Details</h4>
            <div class="doc-detail-file">
                <div class="doc-thumb pdf" style="width:34px;height:34px;font-size:8px;"><span>PDF</span></div>
                <div>
                    <div class="doc-detail-name" id="detailName">Resume_RosaCruz_2025.pdf</div>
                    <div class="doc-detail-size" id="detailSize">245 KB</div>
                </div>
            </div>
            <div class="detail-row"><span>Upload Date</span><span id="detailDate">April 18, 2025</span></div>
            <div class="detail-row"><span>File Type</span><span id="detailType">PDF</span></div>
            <div class="detail-row"><span>File Size</span><span id="detailSz">245 KB</span></div>
            <div class="drive-link-section">
                <label>Google Drive Link</label>
                <div class="drive-url">🔗 <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">https://drive.google.com/file/…</span></div>
                <button class="open-drive-btn">Open in Drive</button>
            </div>
        </div>

    </div>
</div>