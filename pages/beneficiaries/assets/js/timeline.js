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
    <div class="tl-item" data-type="${item.type}" data-history-id="${item.id || ''}">
      <div class="tl-icon tl-icon-${item.color}">${item.icon}</div>
      <div class="tl-body" style="flex:1;">
        <span class="tl-type-tag tag-${item.type}">${item.title}</span>
        <p>${item.description}</p>
      </div>
      <div class="tl-date">${item.date}</div>
      <button class="tl-delete-btn" onclick="deleteTimelineItem('${item.id || ''}')" title="Delete this record" style="padding:6px 8px;display:flex;align-items:center;gap:4px;background:transparent;border:none;cursor:pointer;color:var(--text-muted);transition:color 0.2s;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
        </svg>
      </button>
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

    const timelineItems = Array.isArray(json.timeline) ? json.timeline : [];
    _renderTimelineItems(timelineItems);
    
    // Update visit badge based on the latest timeline data
    if (timelineItems.length > 0) {
      const pesoCounts = timelineItems
        .filter(item => item.classification === 'PESO_VISIT')
        .map(item => item.raw?.visit_number || 0);
      
      if (pesoCounts.length > 0) {
        const maxVisit = Math.max(...pesoCounts);
        const visitEl = document.getElementById('profVisit');
        if (visitEl && maxVisit > 0) {
          // Format ordinal suffix (1st, 2nd, 3rd, 4th, etc.)
          const suffixes = ['th', 'st', 'nd', 'rd'];
          const suffix = suffixes[maxVisit % 10 === 1 && maxVisit % 100 !== 11 ? 1 : maxVisit % 10 === 2 && maxVisit % 100 !== 12 ? 2 : maxVisit % 10 === 3 && maxVisit % 100 !== 13 ? 3 : 0];
          visitEl.textContent = maxVisit + suffix;
        }
      }
    }
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

// ── Delete Timeline Item ──────────────────────────────────

let pendingDeleteHistoryId = null;

function deleteTimelineItem(historyId) {
  if (!historyId) {
    _showTimelineToast('Unable to delete this record.', 'error');
    return;
  }

  pendingDeleteHistoryId = historyId;
  const modal = document.getElementById('modalDeleteTimelineItem');
  if (modal) {
    modal.style.display = 'flex';
  }
}

function closeDeleteTimelineModal() {
  const modal = document.getElementById('modalDeleteTimelineItem');
  if (modal) {
    modal.style.display = 'none';
  }
  pendingDeleteHistoryId = null;
}

function confirmDeleteTimelineItem() {
  const historyId = pendingDeleteHistoryId;
  if (!historyId) {
    _showTimelineToast('Unable to delete this record.', 'error');
    closeDeleteTimelineModal();
    return;
  }

  const benef_id = window.currentBeneficiaryId;
  if (!benef_id) {
    _showTimelineToast('No beneficiary selected.', 'error');
    closeDeleteTimelineModal();
    return;
  }

  const item = document.querySelector(`.tl-item[data-history-id="${historyId}"]`);
  if (item) {
    item.style.opacity = '0.5';
    item.style.pointerEvents = 'none';
  }

  // Call backend to delete
  fetch(`../../backend/beneficiaries/delete_activity.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      benef_id: benef_id,
      history_id: historyId
    })
  })
  .then(r => r.json())
  .then(j => {
    if (j && j.success) {
      // Remove from DOM
      if (item) {
        setTimeout(() => {
          item.remove();
          
          // Check if timeline is now empty
          const list = document.getElementById('timelineList');
          const items = list ? list.querySelectorAll('.tl-item') : [];
          if (items.length === 0) {
            _setTimelineEmpty('No timeline records found for this beneficiary.');
          }
        }, 300);
      }
      
      // Reload timeline to update visit count and other data
      if (typeof window.loadBeneficiaryTimeline === 'function') {
        window.loadBeneficiaryTimeline(benef_id);
      }
      
      _showTimelineToast('Timeline record deleted successfully.', 'success');
    } else {
      // Restore item if deletion failed
      if (item) {
        item.style.opacity = '1';
        item.style.pointerEvents = 'auto';
      }
      _showTimelineToast(j.message || 'Failed to delete timeline record.', 'error');
    }
    closeDeleteTimelineModal();
  })
  .catch(err => {
    console.error('[timeline.js] confirmDeleteTimelineItem error', err);
    // Restore item if deletion failed
    if (item) {
      item.style.opacity = '1';
      item.style.pointerEvents = 'auto';
    }
    _showTimelineToast('Failed to delete timeline record.', 'error');
    closeDeleteTimelineModal();
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
window.deleteTimelineItem = deleteTimelineItem;
window.closeDeleteTimelineModal = closeDeleteTimelineModal;
window.confirmDeleteTimelineItem = confirmDeleteTimelineItem;