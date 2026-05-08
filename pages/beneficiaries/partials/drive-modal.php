<!-- ══════════════════════════════════════
     ADD DRIVE LINK MODAL
══════════════════════════════════════ -->
<div id="driveModalOverlay" onclick="handleOverlayClick(event)">
    <div id="driveModal">
        <div class="drive-modal-header">
            <h3>Add Google Drive File Link</h3>
        </div>
        <div class="drive-modal-body">
            <div class="drive-field">
                <label for="driveFileName">File Name</label>
                <input type="text" id="driveFileName" placeholder="e.g. Resume_2025.pdf">
            </div>
            <div class="drive-field">
                <label for="driveLinkUrl">Google Drive Link</label>
                <input type="url" id="driveLinkUrl" placeholder="https://drive.google.com/file/d/...">
            </div>
            <div class="drive-field" style="margin-bottom:0;">
                <label>Category</label>
                <div style="display:flex;flex-wrap:wrap;gap:7px;margin-top:2px;" id="categoryTags">
                    <button type="button" class="cat-tag active" data-cat="Resume"      onclick="selectCat(this)">Resume</button>
                    <button type="button" class="cat-tag"        data-cat="Academic"    onclick="selectCat(this)">Academic</button>
                    <button type="button" class="cat-tag"        data-cat="Clearance"   onclick="selectCat(this)">Clearance</button>
                    <button type="button" class="cat-tag"        data-cat="Civil"       onclick="selectCat(this)">Civil</button>
                    <button type="button" class="cat-tag"        data-cat="Certificate" onclick="selectCat(this)">Certificate</button>
                    <button type="button" class="cat-tag"        data-cat="Employment"  onclick="selectCat(this)">Employment</button>
                    <button type="button" class="cat-tag"        data-cat="License"     onclick="selectCat(this)">License</button>
                    <button type="button" class="cat-tag"        data-cat="Portfolio"   onclick="selectCat(this)">Portfolio</button>
                </div>
            </div>
            <div class="drive-modal-actions">
                <button class="btn-add" onclick="submitDriveLink()">Add Document</button>
                <button class="btn-cancel" onclick="closeDriveModal()">Cancel</button>
            </div>
        </div>
        <hr class="drive-modal-divider">
        <div class="drive-modal-preview" id="driveModalPreview"></div>
    </div>
</div>