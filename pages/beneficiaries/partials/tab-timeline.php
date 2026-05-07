<div id="tab-timeline" class="tab-panel">
    <div style="margin-top:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
            <h4 style="margin:0;font-size:14px;font-weight:700;display:flex;align-items:center;gap:7px;">🕐 Activity Timeline</h4>
            <div class="timeline-filters">
                <button class="tf-btn active" onclick="filterTimeline('all',this)">All</button>
                <button class="tf-btn" onclick="filterTimeline('visit',this)">Visits</button>
                <button class="tf-btn" onclick="filterTimeline('jobfair',this)">Job Fairs</button>
                <button class="tf-btn" onclick="filterTimeline('referral',this)">Referrals</button>
                <button class="tf-btn" onclick="filterTimeline('outcome',this)">Outcomes</button>
            </div>
        </div>
        <div class="timeline-list" id="timelineList">
            <div class="tl-item" data-type="visit">
                <div class="tl-icon tl-icon-blue">🚶</div>
                <div class="tl-body" style="flex:1;">
                    <span class="tl-type-tag tag-visit">3rd PESO Visit</span>
                    <p>Follow-up consultation. Applicant still seeking placement.</p>
                </div>
                <div class="tl-date">May 28, 2025</div>
            </div>
            <div class="tl-item" data-type="outcome">
                <div class="tl-icon tl-icon-orange">🏆</div>
                <div class="tl-body" style="flex:1;">
                    <span class="tl-type-tag tag-outcome">Job Fair Outcome</span>
                    <p>Career counselor advised to expand job search to adjacent fields.</p>
                </div>
                <div class="tl-date">May 28, 2025</div>
            </div>
            <div class="tl-item" data-type="jobfair">
                <div class="tl-icon tl-icon-green">📋</div>
                <div class="tl-body" style="flex:1;">
                    <span class="tl-type-tag tag-jobfair">Job Fair Participation</span>
                    <p>Attended job fair. Applicant submitted applications to 3 employers.</p>
                </div>
                <div class="tl-date">May 28, 2025</div>
            </div>
            <div class="tl-item" data-type="referral">
                <div class="tl-icon tl-icon-purple">↗</div>
                <div class="tl-body" style="flex:1;">
                    <span class="tl-type-tag tag-referral">Referral</span>
                    <p>Referred to SM Supermalls for store associate position. Application is being processed.</p>
                </div>
                <div class="tl-date">May 26, 2025</div>
            </div>
            <div class="tl-item" data-type="visit">
                <div class="tl-icon tl-icon-blue">🚶</div>
                <div class="tl-body" style="flex:1;">
                    <span class="tl-type-tag tag-visit">2nd PESO Visit</span>
                    <p>Follow-up consultation. Career counselor advised to expand job search to adjacent fields.</p>
                </div>
                <div class="tl-date">May 28, 2025</div>
            </div>
            <div class="tl-item" data-type="referral">
                <div class="tl-icon tl-icon-purple">↗</div>
                <div class="tl-body" style="flex:1;">
                    <span class="tl-type-tag tag-referral">Referral</span>
                    <p>Initial referral to Jollibee Foods Corporation. No feedback received from employer.</p>
                </div>
                <div class="tl-date">May 20, 2025</div>
            </div>
        </div>
    </div>
</div>