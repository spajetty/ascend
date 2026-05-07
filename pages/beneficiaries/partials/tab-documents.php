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
                <div>
                    <label style="font-size:12px;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:6px;">Category</label>
                    <div id="inlineCategoryTags">
                        <button type="button" class="cat-tag active" data-cat="Resume"      onclick="selectInlineCat(this)">Resume</button>
                        <button type="button" class="cat-tag"        data-cat="Academic"    onclick="selectInlineCat(this)">Academic</button>
                        <button type="button" class="cat-tag"        data-cat="Clearance"   onclick="selectInlineCat(this)">Clearance</button>
                        <button type="button" class="cat-tag"        data-cat="Civil"       onclick="selectInlineCat(this)">Civil</button>
                        <button type="button" class="cat-tag"        data-cat="Certificate" onclick="selectInlineCat(this)">Certificate</button>
                        <button type="button" class="cat-tag"        data-cat="Employment"  onclick="selectInlineCat(this)">Employment</button>
                        <button type="button" class="cat-tag"        data-cat="License"     onclick="selectInlineCat(this)">License</button>
                        <button type="button" class="cat-tag"        data-cat="Portfolio"   onclick="selectInlineCat(this)">Portfolio</button>
                    </div>
                </div>
                <div class="drive-modal-actions">
                    <button class="btn-add" onclick="submitInlineDriveLink()">Add Document</button>
                    <button class="btn-cancel" onclick="closeInlineDriveForm()">Cancel</button>
                </div>
            </div>

            <!-- Document Items -->
            <div id="docListItems">
                <div class="doc-item selected" onclick="selectDoc(this,'Resume_RosaCruz_2025.pdf','Resume','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Resume_RosaCruz_2025.pdf <span class="doc-tag resume">Resume</span></div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'Transcript_of_Records.pdf','Academic','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Transcript_of_Records.pdf <span class="doc-tag academic">Academic</span></div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'Barangay_Clearance.jpg','Clearance','JPG','245 KB','April 18, 2025')">
                    <div class="doc-thumb img"><span>JPG</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Barangay_Clearance.jpg <span class="doc-tag clearance">Clearance</span></div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'NBI_Clearance_2025.png','Clearance','PNG','245 KB','April 18, 2025')">
                    <div class="doc-thumb img"><span>PNG</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">NBI_Clearance_2025.png <span class="doc-tag clearance">Clearance</span></div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'Birth_Certificate_PSA.pdf','Civil','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">Birth_Certificate_PSA.pdf <span class="doc-tag civil">Civil</span></div>
                        <div class="doc-meta">245 KB · April 18, 2025</div>
                    </div>
                    <div class="doc-actions"><button class="doc-action-btn">⎘</button><button class="doc-action-btn">↗</button></div>
                </div>
                <div class="doc-item" onclick="selectDoc(this,'TESDA_Certificate.pdf','Certificate','PDF','245 KB','April 18, 2025')">
                    <div class="doc-thumb pdf"><span>PDF</span></div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">TESDA_Certificate.pdf <span class="doc-tag cert">Certificate</span></div>
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
            <div class="detail-row"><span>Category</span><span id="detailCat">Resume</span></div>
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