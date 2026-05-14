/**
 * Handles opening/closing the profile detail view and tab switching.
 */

/** Populate and animate the profile view for a given beneficiary id. */
async function openProfile(benefId) {
  const b = beneficiaries.find(x => String(x.benef_id ?? x.id) === String(benefId));
  if (!b) return;

  const safeText = (value) => (value && String(value).trim() ? String(value) : '—');

  window.currentBeneficiaryId = b.benef_id;
  window.currentBeneficiaryName = b.name;

  // ── Header ──
  document.getElementById('profAvatar').textContent    = b.avatar;
  document.getElementById('profName').textContent      = b.name;
  document.getElementById('profAge').textContent       = b.age;
  document.getElementById('profProgram').textContent   = `${b.program} • ${b.section}`;
  document.getElementById('profLastVisit').textContent = safeText(b.lastVisit);
  document.getElementById('profVisit').textContent     = safeText(b.visit);

  // Status badge — normalise to title case for the lookup
  const statusNorm = (b.status || '').trim();
  const statusKey  = statusNorm.charAt(0).toUpperCase() + statusNorm.slice(1).toLowerCase();
  const statusMap = {
    Hired:      ['status-hired',      'Hired'],
    Referred:   ['status-referred',   'Referred'],
    Registered: ['status-registered', 'Registered'],
  };
  const [cls, lbl] = statusMap[statusKey] || ['status-registered', statusNorm || 'Unknown'];
  const sb = document.getElementById('profStatusBadge');
  sb.className   = `status-badge-pill ${cls}`;
  sb.textContent = lbl;

  // ── Overview tab ──
  document.getElementById('pFullName').textContent  = b.name;
  document.getElementById('pGender').textContent    = b.gender;
  document.getElementById('pDob').textContent       = b.dob;
  document.getElementById('pCivil').textContent     = b.civil;
  document.getElementById('pAddress').textContent   = b.address;
  document.getElementById('pEmail').textContent     = b.emailAddr;
  document.getElementById('pEmail').href            = `mailto:${b.emailAddr}`;
  document.getElementById('pPhone').textContent     = b.phone;
  // Notes column removed from database; leave overview notes empty
  document.getElementById('pNotes').textContent     = '';
  document.getElementById('pEducation').textContent = b.education;
  document.getElementById('pSkills').innerHTML      = b.skills.map(s => `<span class="skill-tag">${s}</span>`).join('');

  // ── Employment: show spinner then lazy-load from API ──
  const empEl = document.getElementById('pEmployment');
  empEl.innerHTML = `<tr><td colspan="4" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>`;

  fetchEmploymentHistory(b.benef_id).then(history => {
    // Cache on the object so repeat opens skip the network call
    b.employment = history;
    empEl.innerHTML = history.length
      ? history.map(e => `
          <tr>
            <td style="font-weight:500;">${e.co}</td>
            <td><span class="badge ${badgeClass(e.st)}">${e.st}</span></td>
            <td>${e.dt}</td>
            <td style="color:var(--text-secondary);font-size:12.5px;">${e.note}</td>
          </tr>`).join('')
      : `<tr><td colspan="4" style="color:var(--text-muted);text-align:center;padding:16px;">No employment records yet.</td></tr>`;
  });

  if (typeof window.loadBeneficiaryDocuments === 'function') {
    window.loadBeneficiaryDocuments(b.benef_id, b.name);
  }

  if (typeof window.loadBeneficiaryTimeline === 'function') {
    window.loadBeneficiaryTimeline(b.benef_id);
  }

  // Reset to Overview tab
  switchTab('overview', document.querySelector('#profileView .tab-btn'));

  // ── View transition ──
  const listEl    = document.getElementById('listView');
  const profileEl = document.getElementById('profileView');

  listEl.classList.add('view-exit');
  profileEl.classList.add('view-enter');
  profileEl.style.display = 'block';

  const topbarBreadcrumb = document.getElementById('topbarBreadcrumb');
  const topbarTitle      = document.getElementById('topbarTitle');
  if (topbarBreadcrumb) topbarBreadcrumb.style.display = 'flex';
  if (topbarTitle)      topbarTitle.classList.add('hidden');

  window.scrollTo({ top: 0, behavior: 'smooth' });

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      profileEl.classList.remove('view-enter');
      profileEl.classList.add('view-active');
    });
  });

  setTimeout(() => { listEl.style.display = 'none'; }, 200);
}

/** Animate back to the list view. */
function closeProfile() {
  const listEl    = document.getElementById('listView');
  const profileEl = document.getElementById('profileView');

  profileEl.classList.remove('view-active');
  profileEl.style.opacity   = '0';
  profileEl.style.transform = 'translateX(18px)';

  const topbarBreadcrumb = document.getElementById('topbarBreadcrumb');
  const topbarTitle      = document.getElementById('topbarTitle');
  if (topbarBreadcrumb) topbarBreadcrumb.style.display = 'none';
  if (topbarTitle)      topbarTitle.classList.remove('hidden');

  listEl.style.display = 'block';
  listEl.style.opacity = '0';

  window.scrollTo({ top: 0, behavior: 'smooth' });

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      listEl.style.opacity = '1';
      listEl.classList.remove('view-exit');
    });
  });

  setTimeout(() => {
    profileEl.style.display   = 'none';
    profileEl.style.opacity   = '';
    profileEl.style.transform = '';
  }, 200);
}

/** Activate a named tab panel and highlight its button. */
function switchTab(name, btn) {
  document.querySelectorAll('#profileView .tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('#profileView .tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById(`tab-${name}`).classList.add('active');
  if (btn) btn.classList.add('active');

  if (name === 'timeline' && typeof window.loadBeneficiaryTimeline === 'function' && window.currentBeneficiaryId) {
    window.loadBeneficiaryTimeline(window.currentBeneficiaryId);
  }

  if (name === 'documents' && typeof window.loadBeneficiaryDocuments === 'function' && window.currentBeneficiaryId) {
    window.loadBeneficiaryDocuments(window.currentBeneficiaryId, window.currentBeneficiaryName || '');
  }
}