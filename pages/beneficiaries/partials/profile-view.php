<!-- ══════════════════════════════════════
     PROFILE VIEW
══════════════════════════════════════ -->
<div id="profileView">

    <!-- Header Card -->
    <div class="profile-header-card">
        <div class="profile-hero">
            <div class="avatar-circle" id="profAvatar">RC</div>
            <div class="profile-meta">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <h2 id="profName">Rosa Cruz</h2>
                    <span class="status-badge-pill status-registered" id="profStatusBadge">Registered</span>
                </div>
                <div class="sub" id="profProgram">Government Internship Program • Employment Facilitation</div>
                <div class="age">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                    <span id="profAge">24 years old</span>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;">
                <div class="visit-badge">
                    <div class="visit-notif">1</div>
                    <div class="vnum" id="profVisit">3rd</div>
                    <div class="vlbl">Visit</div>
                </div>
                <button class="edit-btn">Edit Profile</button>
            </div>
        </div>
        <div class="profile-dates">
            <span>🕐 Last PESO Visit: <strong id="profLastVisit">—</strong></span>
        </div>

        <!-- Tabs -->
        <div class="profile-tabs">
            <button class="tab-btn active" onclick="switchTab('overview',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
                Overview
            </button>
            <button class="tab-btn" onclick="switchTab('documents',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Documents
            </button>
            <button class="tab-btn" onclick="switchTab('timeline',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Timeline
            </button>
        </div>
    </div><!-- end profile-header-card -->

    <!-- ── Tab: Overview ── -->
    <?php require_once __DIR__ . '/tab-overview.php'; ?>

    <!-- ── Tab: Documents ── -->
    <?php require_once __DIR__ . '/tab-documents.php'; ?>

    <!-- ── Tab: Timeline ── -->
    <?php require_once __DIR__ . '/tab-timeline.php'; ?>

</div><!-- end profileView -->