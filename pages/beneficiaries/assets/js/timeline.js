/**
 * Handles filtering timeline items by activity type.
 */

function _timelineTypeLabel(type) {
  return {
    visit: 'PESO Visit',
    referral: 'Referral',
    jobfair: 'Job Fair Participation',
  }[type] || 'Activity';
}

function _timelineFallbackType(item) {
  return item?.dataset?.type || 'all';
}

function _setTimelineEmpty(message) {
  const list = document.getElementById('timelineList');
  if (!list) return;
  list.innerHTML = `<div class="tl-empty" style="padding:18px 16px;color:var(--text-muted);background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;">${message}</div>`;
}

function _renderTimelineItems(items) {
  const list = document.getElementById('timelineList');
  if (!list) return;

  if (!items.length) {
    _setTimelineEmpty('No timeline records found for this beneficiary.');
    return;
  }

  list.innerHTML = items.map(item => `
    <div class="tl-item" data-type="${item.type}">
      <div class="tl-icon tl-icon-${item.color}">${item.icon}</div>
      <div class="tl-body" style="flex:1;">
        <span class="tl-type-tag tag-${item.type}">${item.title}</span>
        <p>${item.description}</p>
      </div>
      <div class="tl-date">${item.date}</div>
    </div>
  `).join('');

  const activeBtn = document.querySelector('#tab-timeline .tf-btn.active');
  const activeType = activeBtn ? activeBtn.getAttribute('data-filter-type') || 'all' : 'all';
  filterTimeline(activeType, activeBtn || document.querySelector('#tab-timeline .tf-btn'));
}

async function loadBeneficiaryTimeline(benefId) {
  const list = document.getElementById('timelineList');
  if (!list) return;

  list.innerHTML = '<div class="tl-empty" style="padding:18px 16px;color:var(--text-muted);background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);text-align:center;">Loading timeline…</div>';

  try {
    const res = await fetch(`../../backend/beneficiaries/get_timeline.php?id=${encodeURIComponent(benefId)}`);
    const json = await res.json();

    if (!json.success) {
      _setTimelineEmpty(json.message || 'Unable to load timeline.');
      return;
    }

    _renderTimelineItems(Array.isArray(json.timeline) ? json.timeline : []);
  } catch (err) {
    console.error('[timeline.js] Failed to load beneficiary timeline:', err);
    _setTimelineEmpty('Unable to load timeline.');
  }
}

/** Show only timeline items matching the given type (or all). */
function filterTimeline(type, btn) {
  document.querySelectorAll('.tf-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  if (btn) btn.setAttribute('data-filter-type', type);

  document.querySelectorAll('.tl-item').forEach(item => {
    item.style.display = (type === 'all' || item.dataset.type === type) ? 'flex' : 'none';
  });
}

function _toggleTimelineModal(modalId, display) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.style.display = display;
}

function _showTimelineToast(message, type) {
  if (typeof window.showToast === 'function') {
    window.showToast(message, type);
    return;
  }

  if (type === 'error') {
    console.error(message);
    return;
  }

  console.log(message);
}

let referralEmployersLoaded = false;
let referralEmployersLoading = false;

async function _loadReferralEmployers() {
  const companySelect = document.getElementById('referralCompany');
  if (!companySelect || referralEmployersLoading || referralEmployersLoaded) {
    return;
  }

  referralEmployersLoading = true;
  companySelect.innerHTML = '<option value="">Loading employers…</option>';

  try {
    const res = await fetch('../../backend/beneficiaries/get_employers.php');
    const json = await res.json();

    if (!json.success) {
      throw new Error(json.message || 'Unable to load employers');
    }

    const employers = Array.isArray(json.employers) ? json.employers : [];
    companySelect.innerHTML = '<option value="">— Select employer —</option>';

    employers.forEach(employer => {
      const option = document.createElement('option');
      option.value = String(employer.company_id);
      option.textContent = employer.company_name;
      companySelect.appendChild(option);
    });

    referralEmployersLoaded = true;
  } catch (err) {
    console.error('[timeline.js] Failed to load employers:', err);
    companySelect.innerHTML = '<option value="">Unable to load employers</option>';
    _showTimelineToast('Unable to load employers.', 'error');
  } finally {
    referralEmployersLoading = false;
  }
}

function openLogVisitModal() {
  // populate next visit number and default date, then show modal
  const id = window.currentBeneficiaryId || (window.currentBeneficiary && window.currentBeneficiary.id);
  const visitDisplay = document.getElementById('visitNumberDisplay');
  const visitDate = document.getElementById('visitDate');
  if (visitDisplay) visitDisplay.value = '';
  if (visitDate) visitDate.value = new Date().toISOString().slice(0,10);
  if (!id) {
    _showTimelineToast('Select a beneficiary first.', 'error');
    return;
  }
  fetch(`../../backend/beneficiaries/get_next_visit_number.php?id=${encodeURIComponent(id)}`)
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        if (visitDisplay) visitDisplay.value = String(j.next || 1);
      }
    }).catch(() => {});
  _toggleTimelineModal('modalLogVisit', 'flex');
}

function closeLogVisitModal() {
  _toggleTimelineModal('modalLogVisit', 'none');
}

function openAddReferralModal() {
  const referralDate = document.getElementById('referralDate');
  const referralPosition = document.getElementById('referralPosition');
  const referralStatus = document.getElementById('referralStatus');
  const referralNotes = document.getElementById('referralNotes');

  if (referralDate) referralDate.value = new Date().toISOString().slice(0, 10);
  if (referralPosition) referralPosition.value = '';
  if (referralStatus) referralStatus.value = 'PENDING';
  if (referralNotes) referralNotes.value = '';

  _loadReferralEmployers();
  _toggleTimelineModal('modalAddReferral', 'flex');
}

function closeAddReferralModal() {
  _toggleTimelineModal('modalAddReferral', 'none');
}

function submitLogVisit() {
  const id = window.currentBeneficiaryId;
  if (!id) { _showTimelineToast('No beneficiary selected.', 'error'); return; }
  const dateEl = document.getElementById('visitDate');
  const dateVal = dateEl ? dateEl.value : new Date().toISOString().slice(0,10);

  fetch('../../backend/beneficiaries/save_visit.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ benef_id: id, date_of_record: dateVal })
  })
  .then(r => r.json())
  .then(j => {
    if (j && j.success) {
      closeLogVisitModal();
      // reload timeline to show the new visit
      if (typeof window.loadBeneficiaryTimeline === 'function') window.loadBeneficiaryTimeline(id);
      _showTimelineToast(`Visit ${j.visit_number || ''} saved successfully.`, 'success');
    } else {
      _showTimelineToast(j.message || 'Failed to save visit.', 'error');
    }
  }).catch(err => {
    console.error('[timeline.js] submitLogVisit error', err);
    _showTimelineToast('Failed to save visit.', 'error');
  });
}

function submitAddReferral() {
  const id = window.currentBeneficiaryId;
  if (!id) {
    _showTimelineToast('No beneficiary selected.', 'error');
    return;
  }

  const companyEl = document.getElementById('referralCompany');
  const positionEl = document.getElementById('referralPosition');
  const statusEl = document.getElementById('referralStatus');
  const dateEl = document.getElementById('referralDate');

  const companyId = companyEl ? parseInt(companyEl.value, 10) : 0;
  const position = positionEl ? positionEl.value.trim() : '';
  const referralStatus = statusEl ? statusEl.value : 'PENDING';
  const dateVal = dateEl ? dateEl.value : new Date().toISOString().slice(0, 10);

  if (!companyId) {
    _showTimelineToast('Please select an employer.', 'error');
    return;
  }

  if (!position) {
    _showTimelineToast('Please enter a position.', 'error');
    return;
  }

  fetch('../../backend/beneficiaries/save_referral.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      benef_id: id,
      company_id: companyId,
      position,
      referral_status: referralStatus,
      date_of_record: dateVal,
    }),
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        closeAddReferralModal();
        if (typeof window.loadBeneficiaryTimeline === 'function') window.loadBeneficiaryTimeline(id);
        _showTimelineToast('Referral saved successfully.', 'success');
      } else {
        _showTimelineToast(j.message || 'Failed to save referral.', 'error');
      }
    })
    .catch(err => {
      console.error('[timeline.js] submitAddReferral error', err);
      _showTimelineToast('Failed to save referral.', 'error');
    });
}

window.filterTimeline = filterTimeline;
window.loadBeneficiaryTimeline = loadBeneficiaryTimeline;
window.openLogVisitModal = openLogVisitModal;
window.closeLogVisitModal = closeLogVisitModal;
window.openAddReferralModal = openAddReferralModal;
window.closeAddReferralModal = closeAddReferralModal;
window.submitLogVisit = submitLogVisit;
window.submitAddReferral = submitAddReferral;