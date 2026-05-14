<div id="tab-timeline" class="tab-panel timeline-panel">
    <div style="margin-top:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
            <h4 style="margin:0;font-size:14px;font-weight:700;display:flex;align-items:center;gap:7px;">🕐 Activity Timeline</h4>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <button class="btn-log-visit" onclick="openLogVisitModal()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                    Log Visit
                </button>
                <button class="btn-add-referral" onclick="openAddReferralModal()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add Referral
                </button>
                <div class="timeline-filters">
                    <button class="tf-btn active" onclick="filterTimeline('all',this)">All</button>
                    <button class="tf-btn" onclick="filterTimeline('visit',this)">Visits</button>
                    <button class="tf-btn" onclick="filterTimeline('referral',this)">Referrals</button>
                    <button class="tf-btn" onclick="filterTimeline('jobfair',this)">Job Fair Participation</button>
                </div>
            </div>
        </div>
        <div class="timeline-list" id="timelineList">
            <div class="tl-empty" id="timelineEmpty" style="padding:18px 16px;color:var(--text-muted);background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;">
                Select a beneficiary to load their timeline.
            </div>
        </div>
    </div>
</div>

