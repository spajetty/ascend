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

            <div class="drive-modal-actions">
                <button class="btn-add" onclick="submitDriveLink()">Add Document</button>
                <button class="btn-cancel" onclick="closeDriveModal()">Cancel</button>
            </div>
        </div>
        <hr class="drive-modal-divider">
        <div class="drive-modal-preview" id="driveModalPreview"></div>
    </div>
</div>