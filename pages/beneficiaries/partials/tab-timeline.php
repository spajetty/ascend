<div id="tab-timeline" class="tab-panel">
    <div style="margin-top:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
            <h4 style="margin:0;font-size:14px;font-weight:700;display:flex;align-items:center;gap:7px;">🕐 Activity Timeline</h4>
            <div class="timeline-filters">
                <button class="tf-btn active" onclick="filterTimeline('all',this)">All</button>
                <button class="tf-btn" onclick="filterTimeline('visit',this)">Visits</button>
                <button class="tf-btn" onclick="filterTimeline('referral',this)">Referrals</button>
                <button class="tf-btn" onclick="filterTimeline('jobfair',this)">Job Fair Participation</button>
            </div>
        </div>
        <div class="timeline-list" id="timelineList">
            <div class="tl-empty" id="timelineEmpty" style="padding:18px 16px;color:var(--text-muted);background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;">
                Select a beneficiary to load their timeline.
            </div>
        </div>
    </div>
</div>