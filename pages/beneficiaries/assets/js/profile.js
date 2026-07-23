/**
 * Handles opening/closing the profile detail view and tab switching.
 */

function _withModalSaveLoading(label, run) {
  const btn = window.AscendLoading?.getOpenModalConfirmButton?.() ?? null;
  if (btn && window.AscendLoading) window.AscendLoading.setButtonLoading(btn, true, { label });
  return Promise.resolve(run()).finally(() => {
    if (btn && window.AscendLoading) window.AscendLoading.setButtonLoading(btn, false);
  });
}

/** Calculate age from date of birth. */
function calculateAge(dob) {
  if (!dob) return '—';
  const birthDate = new Date(dob);
  if (isNaN(birthDate.getTime())) return '—';
  const today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age >= 0 ? `${age} years old` : '—';
}

/** Format date to YYYY-MM-DD for HTML date inputs. */
function formatDateForInput(dateStr) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  if (isNaN(date.getTime())) return '';
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

function upperText(value) {
  const text = value == null ? '—' : String(value).trim();
  return text ? text.toUpperCase() : '—';
}

function renderSpesStudentInfo(record) {
  const formatType = (val) => {
    if (val === 'student') return 'Student';
    if (val === 'osy') return 'Out-of-School Youth';
    return upperText(val);
  };

  window.currentSpesRecord = record || null;

  const setText = (id, value) => {
    const el = document.getElementById(id);
    if (el) el.textContent = upperText(value);
  };

  if (!record) {
    setText('pSpesStudentType', '—');
    setText('pSpesHighestEduc', '—');
    setText('pSpesCourse', '—');
    setText('pSpesSchool', '—');
    return;
  }

  setText('pSpesStudentType', formatType(record.student_type));
  setText('pSpesHighestEduc', record.highest_educ || '—');
  setText('pSpesCourse', record.course || '—');
  setText('pSpesSchool', record.school || '—');
}

/** Populate and animate the profile view for a given beneficiary id. */
async function openProfile(benefId) {
  const b = beneficiaries.find(x => String(x.benef_id ?? x.id) === String(benefId));
  if (!b) return;

  const safeText = (value) => (value && String(value).trim() ? String(value) : '—');
  const upperSafeText = (value) => upperText(safeText(value));

  window.currentBeneficiaryId = b.benef_id;
  window.currentBeneficiaryName = b.name;
  window.currentBeneficiary = b;  // Store full object for edit modals

  // ── Header ──
  document.getElementById('profAvatar').textContent = b.avatar;
  document.getElementById('profName').textContent = upperText(b.name);
  document.getElementById('profAge').textContent = calculateAge(b.dob);
  // Render Program Pill Bar
  const pillBar = document.getElementById('profProgramPillBar');
  if (pillBar) {
    if (b.enrollments && b.enrollments.length > 0) {
      pillBar.innerHTML = b.enrollments.map((e, idx) =>
        `<button class="program-pill ${idx === 0 ? 'active' : ''}" onclick="selectProgramPill(${idx})">
                  <span class="pill-dot"></span>
                  ${upperText(e.program_name)}
              </button>`
      ).join('');
    } else {
      pillBar.innerHTML = `<span style="font-size: 12px; color: var(--text-muted);">No programs</span>`;
    }
  }

  document.getElementById('profLastVisit').textContent = upperText(safeText(b.lastVisit));
  document.getElementById('profVisit').textContent = upperText(safeText(b.visit));

  // Show visit badge and dates only if there's at least one PESO visit record
  const visitBadgeContainer = document.getElementById('visitBadgeContainer');
  const profileDatesContainer = document.getElementById('profileDatesContainer');

  // Fetch visit count from activity history
  fetch(`../../backend/beneficiaries/get_visit_count.php?benef_id=${encodeURIComponent(b.benef_id)}`)
    .then(r => r.json())
    .then(j => {
      const hasVisit = j && j.success && j.count > 0;
      if (visitBadgeContainer) visitBadgeContainer.style.display = hasVisit ? 'flex' : 'none';
      if (profileDatesContainer) profileDatesContainer.style.display = hasVisit ? '' : 'none';
    })
    .catch(() => {
      // If fetch fails, hide the elements
      if (visitBadgeContainer) visitBadgeContainer.style.display = 'none';
      if (profileDatesContainer) profileDatesContainer.style.display = 'none';
    });

  // ── Overview tab ──
  document.getElementById('pFullName').textContent = upperText(b.name);
  document.getElementById('pGender').textContent = upperText(b.gender);
  document.getElementById('pDob').textContent = upperText(b.dob);
  document.getElementById('pCivil').textContent = upperText(b.civil);
  document.getElementById('pAddress').textContent = upperText(b.address);
  document.getElementById('pEmail').textContent = b.emailAddr;
  document.getElementById('pEmail').href = `mailto:${b.emailAddr}`;
  document.getElementById('pPhone').textContent = upperText(b.phone);
  document.getElementById('pNotes').textContent = (b.notes && String(b.notes).trim()) ? b.notes : 'No case notes yet.';

  // Select first program pill by default to load status badge and program-specific cards
  if (b.enrollments && b.enrollments.length > 0) {
    window.selectProgramPill(0);
  } else {
    // Fallback if no programs
    _updateProgramCards(b, null);
    const sb = document.getElementById('profStatusBadge');
    if (sb) {
      sb.className = 'status-badge-pill status-registered';
      sb.textContent = 'Registered';
    }
  }

  // ── Employment: show spinner then lazy-load from API ──
  const empEl = document.getElementById('pEmployment');
  empEl.innerHTML = `<tr><td colspan="4" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>`;

  fetchEmploymentHistory(b.benef_id).then(history => {
    // Cache on the object so repeat opens skip the network call
    b.employment = history;
    window.currentEmploymentHistory = history;
    empEl.innerHTML = history.length
      ? history.map((e, idx) => `
          <tr>
            <td style="font-weight:500;">${upperText(e.co)}</td>
            <td><span class="badge ${badgeClass(e.st)}">${e.st}</span></td>
            <td>${upperText(e.dt)}</td>
            <td style="color:var(--text-secondary);font-size:12.5px;">${upperText(e.note)}</td>
            <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
              <button onclick="editEmploymentRecord(${e.id})" style="padding:4px 8px;background:var(--accent);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Edit</button>
              <button onclick="deleteEmploymentRecord(${e.id})" style="padding:4px 8px;background:var(--danger,#ef4444);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Delete</button>
            </td>
          </tr>`).join('')
      : `<tr><td colspan="5" style="color:var(--text-muted);text-align:center;padding:16px;">No employment records yet.</td></tr>`;
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
  const listEl = document.getElementById('listView');
  const profileEl = document.getElementById('profileView');

  listEl.classList.add('view-exit');
  profileEl.classList.add('view-enter');
  profileEl.style.display = 'block';

  const topbarBreadcrumb = document.getElementById('topbarBreadcrumb');
  const topbarTitle = document.getElementById('topbarTitle');
  if (topbarBreadcrumb) topbarBreadcrumb.style.display = 'flex';
  if (topbarTitle) topbarTitle.classList.add('hidden');

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
  const listEl = document.getElementById('listView');
  const profileEl = document.getElementById('profileView');

  profileEl.classList.remove('view-active');
  profileEl.style.opacity = '0';
  profileEl.style.transform = 'translateX(18px)';

  const topbarBreadcrumb = document.getElementById('topbarBreadcrumb');
  const topbarTitle = document.getElementById('topbarTitle');
  if (topbarBreadcrumb) topbarBreadcrumb.style.display = 'none';
  if (topbarTitle) topbarTitle.classList.remove('hidden');

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
    profileEl.style.display = 'none';
    profileEl.style.opacity = '';
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

// ── Edit Modal Handlers ──────────────────────────────────────────────────────

function _toggleEditModal(modalId, display) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.style.display = display;
}

function _showEditToast(message, type) {
  if (typeof window.showToast === 'function') {
    window.showToast(message, type);
    return;
  }
  if (type === 'error') console.error(message);
  else console.log(message);
}

// ── Personal Information ─────────────────────────────────────────────────────
function openEditPersonalModal() {
  const b = window.currentBeneficiary;
  if (!b) return;
  document.getElementById('editPersonalName').value = b.name || '';
  document.getElementById('editPersonalDob').value = formatDateForInput(b.dob);
  document.getElementById('editPersonalGender').value = b.gender || '';
  document.getElementById('editPersonalCivil').value = b.civil || '';
  // Prefill address fields. Prefer discrete DB columns; fall back to parsing `b.address` if needed.
  const houseEl = document.getElementById('editPersonalHouse');
  const brgyEl = document.getElementById('editPersonalBarangay');
  const distEl = document.getElementById('editPersonalDistrict');
  const cityEl = document.getElementById('editPersonalCity');

  const hasDiscrete = (b.house_no && String(b.house_no).trim()) || (b.barangay && String(b.barangay).trim()) || (b.city && String(b.city).trim()) || (b.district && String(b.district).trim());
  if (hasDiscrete) {
    if (houseEl) houseEl.value = b.house_no || '';
    if (brgyEl) brgyEl.value = b.barangay || '';
    if (distEl) distEl.value = b.district || '';
    if (cityEl) cityEl.value = b.city || '';
  } else if (b.address) {
    // Try to split a combined address into components by commas
    const parts = String(b.address).split(',').map(p => p.trim()).filter(Boolean);
    // Typical formats: "House#, Street, Barangay, City" or "House#, Street, Barangay, District, City"
    if (parts.length >= 4) {
      if (houseEl) houseEl.value = parts.slice(0, parts.length - 3).join(', ');
      if (brgyEl) brgyEl.value = parts[parts.length - 3] || '';
      if (distEl) distEl.value = parts[parts.length - 2] || '';
      if (cityEl) cityEl.value = parts[parts.length - 1] || '';
    } else if (parts.length === 3) {
      if (houseEl) houseEl.value = parts[0] || '';
      if (brgyEl) brgyEl.value = parts[1] || '';
      if (cityEl) cityEl.value = parts[2] || '';
      if (distEl) distEl.value = '';
    } else if (parts.length === 2) {
      if (houseEl) houseEl.value = parts[0] || '';
      if (cityEl) cityEl.value = parts[1] || '';
      if (brgyEl) brgyEl.value = '';
      if (distEl) distEl.value = '';
    } else {
      if (houseEl) houseEl.value = b.address || '';
      if (brgyEl) brgyEl.value = '';
      if (distEl) distEl.value = '';
      if (cityEl) cityEl.value = '';
    }
  } else {
    if (houseEl) houseEl.value = '';
    if (brgyEl) brgyEl.value = '';
    if (distEl) distEl.value = '';
    if (cityEl) cityEl.value = '';
  }
  _toggleEditModal('modalEditPersonal', 'flex');
}

function closeEditPersonalModal() {
  _toggleEditModal('modalEditPersonal', 'none');
}

function _loadEmploymentCompanies(selectId, selectedCompanyId = '') {
  const companySelect = document.getElementById(selectId);
  if (!companySelect) return Promise.resolve(false);

  companySelect.innerHTML = '<option value="">Loading companies…</option>';

  return fetch('../../backend/beneficiaries/get_employers.php')
    .then(r => r.json())
    .then(j => {
      if (j && j.success && Array.isArray(j.employers)) {
        companySelect.innerHTML = '<option value="">— Select company —</option>';
        j.employers.forEach(emp => {
          const option = document.createElement('option');
          option.value = emp.company_id;
          const details = [emp.est_type, emp.industry, emp.city].filter(Boolean).join(' · ');
          option.textContent = details ? `${emp.company_name} (${details})` : emp.company_name;
          if (String(emp.company_id) === String(selectedCompanyId)) {
            option.selected = true;
          }
          companySelect.appendChild(option);
        });
      } else {
        companySelect.innerHTML = '<option value="">No companies available</option>';
      }
      return true;
    })
    .catch(() => {
      companySelect.innerHTML = '<option value="">Error loading companies</option>';
      return false;
    });
}

function openAddEmploymentModal() {
  window.editingEmploymentId = null;
  document.getElementById('addEmploymentStatus').value = '';
  document.getElementById('addEmploymentDate').value = '';
  document.getElementById('addEmploymentNotes').value = '';
  _loadEmploymentCompanies('addEmploymentCompany');
  _toggleEditModal('modalAddEmployment', 'flex');
}

function closeAddEmploymentModal() {
  _toggleEditModal('modalAddEmployment', 'none');
}

function openEditEmploymentModal(record) {
  if (!record) return;

  window.editingEmploymentId = record.id || 0;
  document.getElementById('editEmploymentStatus').value = record.status || '';
  document.getElementById('editEmploymentDate').value = record.date_of_record || '';
  document.getElementById('editEmploymentNotes').value = record.notes || '';
  _loadEmploymentCompanies('editEmploymentCompany', record.company_id || '');
  _toggleEditModal('modalEditEmployment', 'flex');
}

function closeEditEmploymentModal() {
  _toggleEditModal('modalEditEmployment', 'none');
}

function editEmploymentRecord(historyId) {
  const history = Array.isArray(window.currentEmploymentHistory)
    ? window.currentEmploymentHistory.find(item => String(item.id) === String(historyId))
    : null;

  if (!history) {
    _showEditToast('Could not load the selected employment record.', 'error');
    return;
  }

  openEditEmploymentModal(history);
}

function submitEditPersonal() {
  const id = window.currentBeneficiaryId;
  if (!id) {
    _showEditToast('No beneficiary selected.', 'error');
    return;
  }

  const fullName = document.getElementById('editPersonalName').value.trim();
  const dob = document.getElementById('editPersonalDob').value;
  const sex = document.getElementById('editPersonalGender').value;
  const civil = document.getElementById('editPersonalCivil').value;
  const house_no = document.getElementById('editPersonalHouse').value.trim();
  const barangay = document.getElementById('editPersonalBarangay').value.trim();
  const district = document.getElementById('editPersonalDistrict').value.trim();
  const city = document.getElementById('editPersonalCity').value.trim();

  if (!barangay) {
    _showEditToast('Barangay is required.', 'error');
    return;
  }
  if (!district) {
    _showEditToast('District is required.', 'error');
    return;
  }
  if (!city) {
    _showEditToast('City/municipality is required.', 'error');
    return;
  }

  // Validate DOB format and is not in future
  const dobDate = new Date(dob);
  if (isNaN(dobDate.getTime())) {
    _showEditToast('Invalid date of birth.', 'error');
    return;
  }
  if (dobDate > new Date()) {
    _showEditToast('Date of birth cannot be in the future.', 'error');
    return;
  }

  // Simple name parsing: first, middle, last
  const parts = fullName.split(/\s+/).filter(Boolean);
  const first = parts.length ? parts[0] : '';
  const last = parts.length > 1 ? parts[parts.length - 1] : '';
  const middle = parts.length > 2 ? parts.slice(1, -1).join(' ') : '';

  _withModalSaveLoading('Saving…', () => fetch(`../../backend/beneficiaries/update_personal.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      benef_id: id,
      first_name: first,
      middle_name: middle,
      last_name: last,
      suffix: '',
      dob: dob,
      sex: sex,
      civil_status: civil,
      house_no: house_no,
      barangay: barangay,
      district: district,
      city: city
    })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        // update local object and UI
        const b = window.currentBeneficiary || {};
        b.name = fullName || b.name;
        b.dob = dob || b.dob;
        b.gender = sex || b.gender;
        b.civil = civil || b.civil;
        b.house_no = house_no || b.house_no;
        b.barangay = barangay || b.barangay;
        b.district = district || b.district;
        b.city = city || b.city;

        // Build a display address
        const addrParts = [];
        if (b.house_no) addrParts.push(b.house_no);
        if (b.barangay) addrParts.push(b.barangay);
        if (b.district) addrParts.push(b.district);
        if (b.city) addrParts.push(b.city);
        b.address = addrParts.join(', ');

        document.getElementById('pFullName').textContent = b.name;
        document.getElementById('profName').textContent = b.name;
        document.getElementById('profAge').textContent = calculateAge(b.dob);
        document.getElementById('pDob').textContent = dob ? new Date(dob).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : (b.dob || '—');
        document.getElementById('pGender').textContent = b.gender || '—';
        document.getElementById('pCivil').textContent = b.civil || '—';
        document.getElementById('pAddress').textContent = b.address || '—';

        _showEditToast('Personal information saved.', 'success');
        closeEditPersonalModal();
      } else {
        _showEditToast(j.message || 'Failed to save personal information.', 'error');
      }
    })).catch(err => {
      console.error('[profile.js] submitEditPersonal error', err);
      _showEditToast('Failed to save personal information.', 'error');
    });
}

// ── Contact Information ──────────────────────────────────────────────────────
// ── Validators ──────────────────────────────────────────────────────────────
function validateEmail(email) {
  if (!email) return { valid: false, message: 'Email is required.' };
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    return { valid: false, message: 'Invalid email format.' };
  }
  return { valid: true };
}

function validatePhone(phone) {
  if (!phone) return { valid: false, message: 'Phone number is required.' };
  const phoneRegex = /^(\+?63|0)?9\d{9}$/;
  const cleanPhone = phone.replace(/[-\s]/g, '');
  if (!phoneRegex.test(cleanPhone)) {
    return { valid: false, message: 'Invalid phone number. Use format 09XXXXXXXXX or +639XXXXXXXXX.' };
  }
  return { valid: true };
}

// ── Contact Edit ─────────────────────────────────────────────────────────────
function openEditContactModal() {
  const b = window.currentBeneficiary;
  if (!b) return;
  document.getElementById('editContactEmail').value = b.emailAddr || '';
  document.getElementById('editContactPhone').value = b.phone || '';
  _toggleEditModal('modalEditContact', 'flex');
}

function closeEditContactModal() {
  _toggleEditModal('modalEditContact', 'none');
}

function submitEditContact() {
  const id = window.currentBeneficiaryId;
  if (!id) { _showEditToast('No beneficiary selected.', 'error'); return; }

  const email = document.getElementById('editContactEmail').value.trim();
  const phone = document.getElementById('editContactPhone').value.trim();

  // Validate email
  const emailValidation = validateEmail(email);
  if (!emailValidation.valid) {
    _showEditToast(emailValidation.message, 'error');
    return;
  }

  // Validate phone
  const phoneValidation = validatePhone(phone);
  if (!phoneValidation.valid) {
    _showEditToast(phoneValidation.message, 'error');
    return;
  }

  _withModalSaveLoading('Saving…', () => fetch(`../../backend/beneficiaries/update_contact.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ benef_id: id, contact: phone, email })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        const b = window.currentBeneficiary || {};
        b.emailAddr = email || b.emailAddr;
        b.phone = phone || b.phone;

        const emailEl = document.getElementById('pEmail');
        if (emailEl) {
          emailEl.textContent = b.emailAddr || '—';
          emailEl.href = `mailto:${b.emailAddr || ''}`;
        }

        const phoneEl = document.getElementById('pPhone');
        if (phoneEl) phoneEl.textContent = b.phone || '—';

        _showEditToast('Contact information saved.', 'success');
        closeEditContactModal();
      } else {
        _showEditToast(j.message || 'Failed to save contact information.', 'error');
      }
    })).catch(err => {
      console.error('[profile.js] submitEditContact error', err);
      _showEditToast('Failed to save contact information.', 'error');
    });
}

// ── Case Notes ───────────────────────────────────────────────────────────────
function openEditNotesModal() {
  const notesEl = document.getElementById('pNotes');
  const notesText = notesEl ? notesEl.textContent.trim() : '';
  document.getElementById('editNotesText').value = notesText;
  _toggleEditModal('modalEditNotes', 'flex');
}

function closeEditNotesModal() {
  _toggleEditModal('modalEditNotes', 'none');
}

function submitEditNotes() {
  const notes = document.getElementById('editNotesText')?.value ?? '';
  const b = window.currentBeneficiary || {};
  const benefId = b.benef_id || window.currentBeneficiaryId || 0;
  if (!benefId) {
    _showEditToast('No beneficiary selected.', 'error');
    return;
  }

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_notes.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ benef_id: Number(benefId), notes }),
  }))
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        // Update UI
        const pNotes = document.getElementById('pNotes');
        if (pNotes) pNotes.textContent = notes || '';
        if (window.currentBeneficiary) window.currentBeneficiary.notes = notes;
        _showEditToast('Case notes saved.', 'success');
        closeEditNotesModal();
      } else {
        _showEditToast(j.message || 'Failed to save case notes.', 'error');
      }
    }).catch(err => {
      console.error('[profile.js] submitEditNotes error', err);
      _showEditToast('Failed to save case notes.', 'error');
    });
}

// ── Education & Skills ───────────────────────────────────────────────────────
function openEditEducationModal() {
  const b = window.currentBeneficiary;
  if (!b) return;
  document.getElementById('editEducationLevel').value = b.education || '';
  document.getElementById('editEducationSkills').value = (b.skills || []).join(', ');
  _toggleEditModal('modalEditEducation', 'flex');
}

function closeEditEducationModal() {
  _toggleEditModal('modalEditEducation', 'none');
}

function submitEditEducation() {
  _showEditToast('Education & skills saved.', 'success');
  closeEditEducationModal();
}

function deleteEmploymentRecord(historyId) {
  window.pendingEmploymentDeleteId = historyId;
  _toggleEditModal('modalDeleteEmployment', 'flex');
}

function closeDeleteEmploymentModal() {
  window.pendingEmploymentDeleteId = 0;
  _toggleEditModal('modalDeleteEmployment', 'none');
}

function confirmDeleteEmploymentRecord() {
  const historyId = window.pendingEmploymentDeleteId;
  if (!historyId) {
    closeDeleteEmploymentModal();
    return;
  }

  const formData = new FormData();
  formData.append('history_id', historyId);
  formData.append('benef_id', window.currentBeneficiaryId);

  _withModalSaveLoading('Deleting…', () => fetch('../../backend/beneficiaries/delete_employment.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('Employment record deleted successfully.', 'success');
        closeDeleteEmploymentModal();

        // Refresh employment table
        const empEl = document.getElementById('pEmployment');
        if (empEl) {
          empEl.innerHTML = '<tr><td colspan="5" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>';

          fetchEmploymentHistory(window.currentBeneficiaryId).then(history => {
            empEl.innerHTML = history.length
              ? history.map((e, idx) => `
                  <tr>
                    <td style="font-weight:500;">${e.co}</td>
                    <td><span class="badge ${badgeClass(e.st)}">${e.st}</span></td>
                    <td>${e.dt}</td>
                    <td style="color:var(--text-secondary);font-size:12.5px;">${e.note}</td>
                    <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
                      <button onclick="editEmploymentRecord(${e.id})" style="padding:4px 8px;background:var(--accent);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Edit</button>
                      <button onclick="deleteEmploymentRecord(${e.id})" style="padding:4px 8px;background:var(--danger,#ef4444);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Delete</button>
                    </td>
                  </tr>`).join('')
              : '<tr><td colspan="5" style="color:var(--text-muted);text-align:center;padding:16px;">No employment records yet.</td></tr>';
          });
        }
      } else {
        _showEditToast(j?.error || 'Error deleting employment record.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error deleting employment record.', 'error');
    });
}

function _submitEmploymentRecord(endpoint, successMessage, errorMessage, modalCloseFn, companyIdEl, statusEl, dateEl, notesEl, historyId = 0) {
  const company_id = document.getElementById(companyIdEl).value;
  const status = document.getElementById(statusEl).value;
  const date_of_record = document.getElementById(dateEl).value;
  const notes = document.getElementById(notesEl).value;

  if (!company_id) {
    _showEditToast('Please select a company.', 'error');
    return;
  }

  if (!status) {
    _showEditToast('Please select a status.', 'error');
    return;
  }

  if (!date_of_record) {
    _showEditToast('Please select a date.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('benef_id', window.currentBeneficiaryId);
  formData.append('company_id', company_id);
  formData.append('status', status);
  formData.append('date_of_record', date_of_record);
  formData.append('notes', notes);
  if (historyId) {
    formData.append('history_id', historyId);
  }

  _withModalSaveLoading('Saving…', () => fetch(endpoint, {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast(successMessage, 'success');
        modalCloseFn();

        // Refresh employment table
        const empEl = document.getElementById('pEmployment');
        if (empEl) {
          empEl.innerHTML = '<tr><td colspan="5" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>';

          fetchEmploymentHistory(window.currentBeneficiaryId).then(history => {
            empEl.innerHTML = history.length
              ? history.map((e, idx) => `
                  <tr>
                    <td style="font-weight:500;">${e.co}</td>
                    <td><span class="badge ${badgeClass(e.st)}">${e.st}</span></td>
                    <td>${e.dt}</td>
                    <td style="color:var(--text-secondary);font-size:12.5px;">${e.note}</td>
                    <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
                      <button onclick="editEmploymentRecord(${e.id})" style="padding:4px 8px;background:var(--accent);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Edit</button>
                      <button onclick="deleteEmploymentRecord(${e.id})" style="padding:4px 8px;background:var(--danger,#ef4444);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Delete</button>
                    </td>
                  </tr>`).join('')
              : '<tr><td colspan="5" style="color:var(--text-muted);text-align:center;padding:16px;">No employment records yet.</td></tr>';
          });
        }
      } else {
        _showEditToast(j?.error || errorMessage, 'error');
      }
    }))
    .catch(() => {
      _showEditToast(errorMessage, 'error');
    });
}

function submitAddEmployment() {
  _submitEmploymentRecord(
    '../../backend/beneficiaries/save_employment.php',
    'Employment record added successfully.',
    'Error adding employment record.',
    closeAddEmploymentModal,
    'addEmploymentCompany',
    'addEmploymentStatus',
    'addEmploymentDate',
    'addEmploymentNotes'
  );
}

function submitUpdateEmployment() {
  _submitEmploymentRecord(
    '../../backend/beneficiaries/update_employment.php',
    'Employment record updated successfully.',
    'Error updating employment record.',
    closeEditEmploymentModal,
    'editEmploymentCompany',
    'editEmploymentStatus',
    'editEmploymentDate',
    'editEmploymentNotes',
    window.editingEmploymentId
  );
}

function openEditSpesModal() {
  const record = window.currentSpesRecord;
  if (!record) {
    _showEditToast('No SPES student record to edit.', 'error');
    return;
  }

  document.getElementById('editSpesId').value = record.spes_id || '';
  document.getElementById('editSpesStudentType').value = String(record.student_type || 'student').toLowerCase() === 'osy' ? 'osy' : 'student';
  document.getElementById('editSpesHighestEduc').value = record.highest_educ || '';
  document.getElementById('editSpesCourse').value = record.course || '';
  document.getElementById('editSpesSchool').value = record.school || '';

  _toggleEditModal('modalEditSpes', 'flex');
}

function closeEditSpesModal() {
  _toggleEditModal('modalEditSpes', 'none');
}

function submitEditSpes() {
  const spesId = document.getElementById('editSpesId').value;

  if (!spesId) {
    _showEditToast('No SPES student record selected.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('spes_id', spesId);
  formData.append('benef_id', window.currentBeneficiaryId || '');
  formData.append('student_type', document.getElementById('editSpesStudentType').value);
  formData.append('highest_educ', document.getElementById('editSpesHighestEduc').value.trim());
  formData.append('course', document.getElementById('editSpesCourse').value.trim());
  formData.append('school', document.getElementById('editSpesSchool').value.trim());

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_spes.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        renderSpesStudentInfo({
          spes_id: Number(spesId),
          benef_id: Number(window.currentBeneficiaryId || 0),
          student_type: document.getElementById('editSpesStudentType').value,
          highest_educ: document.getElementById('editSpesHighestEduc').value.trim(),
          course: document.getElementById('editSpesCourse').value.trim(),
          school: document.getElementById('editSpesSchool').value.trim(),
        });
        _showEditToast('SPES student information updated successfully.', 'success');
        closeEditSpesModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j?.error || 'Error updating SPES student information.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error updating SPES student information.', 'error');
    });
}

function openEditWiirpModal(section = 'record') {
  const record = window.currentWiirpRecord;
  if (!record) {
    _showEditToast('No WIIRP record to edit.', 'error');
    return;
  }

  const assignment = window.currentWiirpAssignmentRecord;

  window.editingWiirpSection = section;
  document.getElementById('editWiirpWorkImmersionId').value = record.work_immersion_id || '';
  document.getElementById('editWiirpAssignmentId').value = assignment?.id || '';

  document.getElementById('editWiirpContractPeriod').value = record.contract_period || '';
  document.getElementById('editWiirpSchool').value = record.school || '';
  document.getElementById('editWiirpCourse').value = record.course || '';
  document.getElementById('editWiirpRequiredHours').value = record.required_hours ?? '';
  document.getElementById('editWiirpInquiryType').value = record.inquiry_type || '';
  document.getElementById('editWiirpPreferredOrgType').value = record.preferred_org_type || '';
  document.getElementById('editWiirpPreferredIndustry').value = record.preferred_industry || '';
  document.getElementById('editWiirpWillingOutside').value = Number(record.is_willing_outside) === 1 ? '1' : '0';
  document.getElementById('editWiirpInternshipSched').value = record.internship_sched || '';
  document.getElementById('editWiirpStartDate').value = formatDateForInput(record.start);
  document.getElementById('editWiirpYearLevel').value = record.year_level || '';
  const wiirpTypeLabel = (() => {
    const normalized = String(record.type || '').trim().toLowerCase();
    if (normalized === 'inquiry') return 'Inquiry';
    if (normalized === 'peso-assigned') return 'PESO Assigned';
    if (normalized === 'private') return 'Private';
    return record.type || '—';
  })();
  const wiirpTypeDisplay = document.getElementById('displayWiirpType');
  if (wiirpTypeDisplay) wiirpTypeDisplay.textContent = wiirpTypeLabel;
  // Populate assignment display-only fields
  const disp = (id, value) => {
    const el = document.getElementById(id);
    if (el) el.textContent = value ?? '—';
  };

  disp('displayWiirpAssignmentStartDate', assignment?.start_date ? formatDateForInput(assignment.start_date) : '—');
  disp('displayWiirpAssignmentEndDate', assignment?.end_date ? formatDateForInput(assignment.end_date) : '—');
  disp('displayWiirpAssignmentRequiredHours', assignment?.required_hours != null ? String(assignment.required_hours) : '—');
  disp('displayWiirpAssignmentOffice', assignment?.office_assignment || '—');
  disp('displayWiirpAssignmentEndorsement1', assignment?.endorsement_1 || '—');
  disp('displayWiirpAssignmentEndorsement2', assignment?.endorsement_2 || '—');

  _toggleEditModal('modalEditWiirp', 'flex');
}

function closeEditWiirpModal() {
  _toggleEditModal('modalEditWiirp', 'none');
}

function submitEditWiirp() {
  const workImmersionId = document.getElementById('editWiirpWorkImmersionId').value;

  if (!workImmersionId) {
    _showEditToast('No WIIRP record selected.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('work_immersion_id', workImmersionId);
  formData.append('benef_id', window.currentBeneficiaryId || '');
  formData.append('contract_period', document.getElementById('editWiirpContractPeriod').value.trim());
  formData.append('school', document.getElementById('editWiirpSchool').value.trim());
  formData.append('course', document.getElementById('editWiirpCourse').value.trim());
  formData.append('required_hours', document.getElementById('editWiirpRequiredHours').value.trim());
  formData.append('inquiry_type', document.getElementById('editWiirpInquiryType').value.trim());
  formData.append('preferred_org_type', document.getElementById('editWiirpPreferredOrgType').value.trim());
  formData.append('preferred_industry', document.getElementById('editWiirpPreferredIndustry').value.trim());
  formData.append('is_willing_outside', document.getElementById('editWiirpWillingOutside').value);
  formData.append('internship_sched', document.getElementById('editWiirpInternshipSched').value.trim());
  formData.append('start', document.getElementById('editWiirpStartDate').value);
  formData.append('year_level', document.getElementById('editWiirpYearLevel').value.trim());
  // Assignment details are display-only in this modal — do not send them for update here.

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_wiirp.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('WIIRP record updated successfully.', 'success');
        closeEditWiirpModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j?.error || 'Error updating WIIRP record.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error updating WIIRP record.', 'error');
    });
}

// ---------------- WIIRP Assignment Edit/Delete Handlers ----------------
function openEditWiirpAssignmentModal(assignmentId) {
  const recs = Array.isArray(window.currentWiirpAssignments) ? window.currentWiirpAssignments : [];
  const record = recs.find(r => String(r.id) === String(assignmentId));
  if (!record) {
    _showEditToast('Could not load the selected assignment.', 'error');
    return;
  }
  document.getElementById('editWiirpAssignmentId').value = record.id || '';
  document.getElementById('editWiirpAssignmentStart').value = formatDateForInput(record.start_date) || '';
  document.getElementById('editWiirpAssignmentEnd').value = formatDateForInput(record.end_date) || '';
  document.getElementById('editWiirpAssignmentRequiredHours').value = record.required_hours != null ? String(record.required_hours) : '';
  document.getElementById('editWiirpAssignmentOffice').value = record.office_assignment || '';
  document.getElementById('editWiirpAssignmentEndorsement1').value = record.endorsement_1 || '';
  document.getElementById('editWiirpAssignmentEndorsement2').value = record.endorsement_2 || '';
  // ensure modal shows Edit mode
  const modal = document.getElementById('modalEditWiirpAssignment');
  if (modal) {
    const title = modal.querySelector('.modal-header h3');
    if (title) title.textContent = 'Edit WIIRP Assignment';
    const confirm = modal.querySelector('.modal-footer .btn-confirm');
    if (confirm) {
      confirm.textContent = 'Save Changes';
      confirm.onclick = submitEditWiirpAssignment;
    }
  }
  _toggleEditModal('modalEditWiirpAssignment', 'flex');
}

function closeEditWiirpAssignmentModal() {
  _toggleEditModal('modalEditWiirpAssignment', 'none');
  // restore default edit button behavior
  const modal = document.getElementById('modalEditWiirpAssignment');
  if (modal) {
    const title = modal.querySelector('.modal-header h3');
    if (title) title.textContent = 'Edit WIIRP Assignment';
    const confirm = modal.querySelector('.modal-footer .btn-confirm');
    if (confirm) {
      confirm.textContent = 'Save Changes';
      confirm.onclick = submitEditWiirpAssignment;
    }
  }
}

function submitEditWiirpAssignment() {
  const assignmentId = document.getElementById('editWiirpAssignmentId').value;
  if (!assignmentId) {
    _showEditToast('No assignment selected.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('assignment_id', assignmentId);
  formData.append('benef_id', window.currentBeneficiaryId || '');
  formData.append('start_date', document.getElementById('editWiirpAssignmentStart').value || '');
  formData.append('end_date', document.getElementById('editWiirpAssignmentEnd').value || '');
  formData.append('required_hours', document.getElementById('editWiirpAssignmentRequiredHours').value || '');
  formData.append('office_assignment', document.getElementById('editWiirpAssignmentOffice').value.trim());
  formData.append('endorsement_1', document.getElementById('editWiirpAssignmentEndorsement1').value.trim());
  formData.append('endorsement_2', document.getElementById('editWiirpAssignmentEndorsement2').value.trim());

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_wiirp_assignment.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('Assignment updated successfully.', 'success');
        closeEditWiirpAssignmentModal();
        if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
      } else {
        _showEditToast(j?.error || 'Error updating assignment.', 'error');
      }
    }).catch(() => {
      _showEditToast('Error updating assignment.', 'error');
    })
  );
}

// ---------------- WIIRP Assignment Add Handlers ----------------
function openAddWiirpAssignmentModal() {
  // Clear fields
  document.getElementById('editWiirpAssignmentId').value = '';
  document.getElementById('editWiirpAssignmentStart').value = '';
  document.getElementById('editWiirpAssignmentEnd').value = '';
  document.getElementById('editWiirpAssignmentRequiredHours').value = '';
  document.getElementById('editWiirpAssignmentOffice').value = '';
  document.getElementById('editWiirpAssignmentEndorsement1').value = '';
  document.getElementById('editWiirpAssignmentEndorsement2').value = '';

  const modal = document.getElementById('modalEditWiirpAssignment');
  if (modal) {
    const title = modal.querySelector('.modal-header h3');
    if (title) title.textContent = 'Add WIIRP Assignment';
    const confirm = modal.querySelector('.modal-footer .btn-confirm');
    if (confirm) {
      confirm.textContent = 'Add Assignment';
      confirm.onclick = submitAddWiirpAssignment;
    }
  }

  _toggleEditModal('modalEditWiirpAssignment', 'flex');
}

function submitAddWiirpAssignment() {
  // require a loaded WIIRP record and current beneficiary
  const wiirp = window.currentWiirpRecord || null;
  if (!wiirp || !wiirp.work_immersion_id) {
    _showEditToast('No WIIRP record selected for assignment.', 'error');
    return;
  }
  if (!window.currentBeneficiaryId) {
    _showEditToast('No beneficiary selected.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('work_immersion_id', wiirp.work_immersion_id);
  formData.append('benef_id', window.currentBeneficiaryId);
  formData.append('start_date', document.getElementById('editWiirpAssignmentStart').value || '');
  formData.append('end_date', document.getElementById('editWiirpAssignmentEnd').value || '');
  formData.append('required_hours', document.getElementById('editWiirpAssignmentRequiredHours').value || '');
  formData.append('office_assignment', document.getElementById('editWiirpAssignmentOffice').value.trim());
  formData.append('endorsement_1', document.getElementById('editWiirpAssignmentEndorsement1').value.trim());
  formData.append('endorsement_2', document.getElementById('editWiirpAssignmentEndorsement2').value.trim());

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/add_wiirp_assignment.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('Assignment added successfully.', 'success');
        closeEditWiirpAssignmentModal();
        if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
      } else {
        _showEditToast(j?.error || 'Error adding assignment.', 'error');
      }
    }).catch(() => {
      _showEditToast('Error adding assignment.', 'error');
    })
  );
}

function deleteWiirpAssignment(assignmentId) {
  document.getElementById('deleteWiirpAssignmentId').value = assignmentId;
  _toggleEditModal('modalDeleteWiirpAssignment', 'flex');
}

function closeDeleteWiirpAssignmentModal() {
  _toggleEditModal('modalDeleteWiirpAssignment', 'none');
  document.getElementById('deleteWiirpAssignmentId').value = '';
}

function confirmDeleteWiirpAssignment() {
  const id = document.getElementById('deleteWiirpAssignmentId').value;
  if (!id) return;

  _withModalSaveLoading('Deleting…', () => fetch('../../backend/beneficiaries/delete_wiirp_assignment.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ assignment_id: id, benef_id: window.currentBeneficiaryId })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('Assignment deleted.', 'success');
        closeDeleteWiirpAssignmentModal();
        if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
      } else {
        _showEditToast(j?.error || 'Failed to delete assignment.', 'error');
      }
    }).catch(() => {
      _showEditToast('Error deleting assignment.', 'error');
    })
  );
}

// ---------------- GIP Edit/Add/Delete Handlers ----------------

function _gipDoleModeElements() {
  return {
    start: 'editGipStartContractDole',
    end: 'editGipEndContractDole',
    days: 'editGipDaysDole'
  };
}

function openAddGipModal() {
  window.currentGipMode = 'add';
  window.currentGipRecord = null;

  document.getElementById('editGipId').value = '';
  document.getElementById('editGipType').value = 'LGU';
  document.getElementById('editGipType').removeAttribute('disabled');
  document.getElementById('editGipStudentType').value = 'student';
  document.getElementById('editGipHighestEduc').value = '';
  document.getElementById('editGipCourse').value = '';
  document.getElementById('editGipSchool').value = '';
  document.getElementById('editGipStartContract').value = '';
  document.getElementById('editGipEndContract').value = '';
  document.getElementById('editGipDays').value = '';
  document.getElementById('editGipOfficeAssignment').value = '';
  document.getElementById('editGipProponent').value = '';
  document.getElementById('editGipStatus').value = '';
  document.getElementById('editGipGsisBeneficiary').value = '';
  document.getElementById('editGipRelationship').value = '';
  document.getElementById('editGipGsisContact').value = '';

  const dole = _gipDoleModeElements();
  const doleStart = document.getElementById(dole.start);
  const doleEnd = document.getElementById(dole.end);
  const doleDays = document.getElementById(dole.days);
  if (doleStart) doleStart.value = '';
  if (doleEnd) doleEnd.value = '';
  if (doleDays) doleDays.value = '';

  document.getElementById('gipLguFields').style.display = 'flex';
  document.getElementById('gipDoleFields').style.display = 'none';

  document.getElementById('editGipType').onchange = function () {
    if (this.value === 'DOLE') {
      document.getElementById('gipLguFields').style.display = 'none';
      document.getElementById('gipDoleFields').style.display = 'flex';
    } else {
      document.getElementById('gipLguFields').style.display = 'flex';
      document.getElementById('gipDoleFields').style.display = 'none';
    }
  };

  const titleEl = document.querySelector('#modalEditGip .modal-header h3');
  const confirmBtn = document.querySelector('#modalEditGip .btn-confirm');
  if (titleEl) titleEl.textContent = 'Add GIP Record';
  if (confirmBtn) confirmBtn.textContent = 'Add Record';

  _toggleEditModal('modalEditGip', 'flex');
}

function openEditGipModal(gipId) {
  const recs = Array.isArray(window.currentGipRecords) ? window.currentGipRecords : [];
  const record = recs.find(r => String(r.gip_id) === String(gipId));

  if (!record) {
    _showEditToast('Could not load the selected GIP record.', 'error');
    return;
  }

  window.currentGipMode = 'edit';
  window.currentGipRecord = record;

  document.getElementById('editGipId').value = record.gip_id || '';
  document.getElementById('editGipType').value = record.type || '';
  document.getElementById('editGipType').setAttribute('disabled', 'disabled');
  document.getElementById('editGipType').onchange = null;
  document.getElementById('editGipStudentType').value = record.student_type || 'student';
  document.getElementById('editGipHighestEduc').value = record.highest_educ || '';
  document.getElementById('editGipCourse').value = record.course || '';
  document.getElementById('editGipSchool').value = record.school || '';

  const formatDateForModalInput = (val) => val ? val.substring(0, 10) : '';
  const isDole = (record.type || '').toUpperCase() === 'DOLE';

  const lguStartInput = document.getElementById('editGipStartContract');
  const lguEndInput = document.getElementById('editGipEndContract');
  const dole = _gipDoleModeElements();
  const doleStartInput = document.getElementById(dole.start);
  const doleEndInput = document.getElementById(dole.end);

  if (isDole) {
    lguStartInput.value = '';
    lguEndInput.value = '';
    if (doleStartInput) doleStartInput.value = formatDateForModalInput(record.start_of_contract);
    if (doleEndInput) doleEndInput.value = formatDateForModalInput(record.end_of_contract);
  } else {
    lguStartInput.value = formatDateForModalInput(record.start_of_contract);
    lguEndInput.value = formatDateForModalInput(record.end_of_contract);
    if (doleStartInput) doleStartInput.value = '';
    if (doleEndInput) doleEndInput.value = '';
  }

  const wireDateBounds = (startInput, endInput) => {
    if (!startInput || !endInput) return;
    const updateDates = () => {
      if (startInput.value) endInput.min = startInput.value;
      else endInput.removeAttribute('min');

      if (endInput.value) startInput.max = endInput.value;
      else startInput.removeAttribute('max');
    };
    startInput.onchange = updateDates;
    startInput.oninput = updateDates;
    endInput.onchange = updateDates;
    endInput.oninput = updateDates;
    updateDates();
  };

  wireDateBounds(lguStartInput, lguEndInput);
  wireDateBounds(doleStartInput, doleEndInput);

  document.getElementById('editGipDays').value = isDole ? '' : (record.days ?? '');
  const doleDaysInput = document.getElementById(dole.days);
  if (doleDaysInput) doleDaysInput.value = isDole ? (record.days ?? '') : '';

  document.getElementById('editGipOfficeAssignment').value = record.office_assignment || '';
  document.getElementById('editGipProponent').value = record.proponent || '';
  document.getElementById('editGipStatus').value = record.status || '';
  document.getElementById('editGipGsisBeneficiary').value = record.gsis_beneficiary || '';
  document.getElementById('editGipRelationship').value = record.relationship || '';
  document.getElementById('editGipGsisContact').value = record.gsis_benef_contact_no || '';

  if (isDole) {
    document.getElementById('gipLguFields').style.display = 'none';
    document.getElementById('gipDoleFields').style.display = 'flex';
  } else {
    document.getElementById('gipLguFields').style.display = 'flex';
    document.getElementById('gipDoleFields').style.display = 'none';
  }

  const titleEl = document.querySelector('#modalEditGip .modal-header h3');
  const confirmBtn = document.querySelector('#modalEditGip .btn-confirm');
  if (titleEl) titleEl.textContent = 'Edit GIP Record';
  if (confirmBtn) confirmBtn.textContent = 'Save Changes';

  _toggleEditModal('modalEditGip', 'flex');
}

function closeEditGipModal() {
  _toggleEditModal('modalEditGip', 'none');
}

function _collectGipFormData() {
  const typeVal = document.getElementById('editGipType').value;
  const isDole = String(typeVal).toUpperCase() === 'DOLE';
  const dole = _gipDoleModeElements();

  const startVal = isDole
    ? (document.getElementById(dole.start)?.value || '')
    : document.getElementById('editGipStartContract').value;
  const endVal = isDole
    ? (document.getElementById(dole.end)?.value || '')
    : document.getElementById('editGipEndContract').value;
  const daysVal = isDole
    ? (document.getElementById(dole.days)?.value.trim() || '')
    : document.getElementById('editGipDays').value.trim();

  const formData = new FormData();
  formData.append('benef_id', window.currentBeneficiaryId || '');
  formData.append('student_type', document.getElementById('editGipStudentType').value);
  formData.append('highest_educ', document.getElementById('editGipHighestEduc').value.trim());
  formData.append('course', document.getElementById('editGipCourse').value.trim());
  formData.append('school', document.getElementById('editGipSchool').value.trim());
  formData.append('start_of_contract', startVal);
  formData.append('end_of_contract', endVal);
  formData.append('days', daysVal);
  formData.append('office_assignment', document.getElementById('editGipOfficeAssignment').value.trim());
  formData.append('proponent', document.getElementById('editGipProponent').value.trim());
  formData.append('status', document.getElementById('editGipStatus').value.trim());
  formData.append('type', typeVal);
  formData.append('gsis_beneficiary', document.getElementById('editGipGsisBeneficiary').value.trim());
  formData.append('relationship', document.getElementById('editGipRelationship').value.trim());
  formData.append('gsis_benef_contact_no', document.getElementById('editGipGsisContact').value.trim());
  return formData;
}

function submitEditGip() {
  if (window.currentGipMode === 'add') {
    return submitAddGip();
  }

  const gip_id = document.getElementById('editGipId').value;
  if (!gip_id) {
    _showEditToast('No GIP record selected.', 'error');
    return;
  }

  const formData = _collectGipFormData();
  formData.append('gip_id', gip_id);

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_gip.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('GIP record updated successfully.', 'success');
        closeEditGipModal();
        if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
      } else {
        _showEditToast(j?.error || 'Error updating GIP record.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error updating GIP record.', 'error');
    });
}

function submitAddGip() {
  const formData = _collectGipFormData();

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/add_gip.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('GIP record added successfully.', 'success');
        closeEditGipModal();
        if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
      } else {
        _showEditToast(j?.error || 'Error adding GIP record.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error adding GIP record.', 'error');
    });
}

function deleteGipRecord(gipId) {
  if (!gipId) return;
  window.pendingGipDeleteId = gipId;
  _toggleEditModal('modalDeleteGip', 'flex');
}

function closeDeleteGipModal() {
  window.pendingGipDeleteId = 0;
  _toggleEditModal('modalDeleteGip', 'none');
}

function confirmDeleteGip() {
  const gipId = window.pendingGipDeleteId;
  if (!gipId) {
    closeDeleteGipModal();
    return;
  }

  const formData = new FormData();
  formData.append('gip_id', gipId);
  formData.append('benef_id', window.currentBeneficiaryId || '');

  _withModalSaveLoading('Deleting…', () => fetch('../../backend/beneficiaries/delete_gip.php', {
    method: 'POST',
    body: formData
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('GIP record deleted.', 'success');
        closeDeleteGipModal();
        if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
      } else {
        _showEditToast(j?.error || 'Error deleting GIP record.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error deleting GIP record.', 'error');
    });
}

function openEditIssuanceModal() {
  const rec = window.currentFirstTimeIssuanceRecord;
  console.log('Opening issuance edit modal with record:', rec);
  const titleEl = document.querySelector('#modalEditIssuance .modal-header h3');
  const submitBtn = document.querySelector('#modalEditIssuance .btn-confirm');
  const jobseekIdEl = document.getElementById('editIssuanceJobseekId');
  const occEl = document.getElementById('editIssuanceOccPermit');
  const healthEl = document.getElementById('editIssuanceHealthCard');

  if (!rec) {
    jobseekIdEl.value = '';
    occEl.value = '0';
    healthEl.value = '0';
    if (titleEl) titleEl.textContent = 'Add Issuance Record';
    if (submitBtn) submitBtn.textContent = 'Add Record';
    _toggleEditModal('modalEditIssuance', 'flex');
    return;
  }

  const jobseekId = rec.jobseek_id || '';
  const occValue = Number(rec.occ_permit) === 1 ? '1' : '0';
  const healthValue = Number(rec.health_card) === 1 ? '1' : '0';

  console.log('Setting form values:', { jobseekId, occValue, healthValue });

  jobseekIdEl.value = jobseekId;
  occEl.value = occValue;
  healthEl.value = healthValue;
  if (titleEl) titleEl.textContent = 'Edit Issuance Status';
  if (submitBtn) submitBtn.textContent = 'Save Changes';

  _toggleEditModal('modalEditIssuance', 'flex');
}

function closeEditIssuanceModal() {
  _toggleEditModal('modalEditIssuance', 'none');
}

function submitUpdateIssuance() {
  const jobseek_id = document.getElementById('editIssuanceJobseekId').value.trim();
  const occVal = document.getElementById('editIssuanceOccPermit').value;
  const healthVal = document.getElementById('editIssuanceHealthCard').value;

  console.log('Form field values:', { jobseek_id, occVal, healthVal });
  console.log('Field element checks:', {
    jobseekIdEl: document.getElementById('editIssuanceJobseekId'),
    occEl: document.getElementById('editIssuanceOccPermit'),
    healthEl: document.getElementById('editIssuanceHealthCard')
  });

  if (!window.currentBeneficiaryId) {
    _showEditToast('No beneficiary selected.', 'error');
    return;
  }

  const occInt = parseInt(occVal, 10);
  const healthInt = parseInt(healthVal, 10);

  console.log('Parsed values for submission:', { jobseek_id, occInt, healthInt });

  const formData = new FormData();
  formData.append('benef_id', window.currentBeneficiaryId);
  if (jobseek_id) {
    formData.append('jobseek_id', jobseek_id);
  }
  formData.append('occ_permit', occInt);
  formData.append('health_card', healthInt);

  console.log('FormData entries:', Array.from(formData.entries()));

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_issuance.php', {
    method: 'POST',
    body: formData
  })
    .then(r => {
      console.log('Response status:', r.status);
      return r.json();
    })
    .then(j => {
      console.log('Update response:', j);
      if (j && j.success) {
        _showEditToast(j.message || 'Issuance record saved.', 'success');
        closeEditIssuanceModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j?.error || 'Error updating issuance status.', 'error');
      }
    }))
    .catch(err => {
      console.error('Fetch error:', err);
      _showEditToast('Error updating issuance status.', 'error');
    });
}

// Make functions global
window.openEditPersonalModal = openEditPersonalModal;
window.currentSpesRecord = null;
window.closeEditPersonalModal = closeEditPersonalModal;
window.submitEditPersonal = submitEditPersonal;
window.openEditContactModal = openEditContactModal;
window.closeEditContactModal = closeEditContactModal;
window.submitEditContact = submitEditContact;
window.openEditNotesModal = openEditNotesModal;
window.closeEditNotesModal = closeEditNotesModal;
window.submitEditNotes = submitEditNotes;
window.openEditEducationModal = openEditEducationModal;
window.closeEditEducationModal = closeEditEducationModal;
window.submitEditEducation = submitEditEducation;
window.openEditSpesModal = openEditSpesModal;
window.closeEditSpesModal = closeEditSpesModal;
window.submitEditSpes = submitEditSpes;
window.openAddEmploymentModal = openAddEmploymentModal;
window.closeAddEmploymentModal = closeAddEmploymentModal;
window.submitAddEmployment = submitAddEmployment;
window.submitUpdateEmployment = submitUpdateEmployment;
window.openEditEmploymentModal = openEditEmploymentModal;
window.closeEditEmploymentModal = closeEditEmploymentModal;
window.editEmploymentRecord = editEmploymentRecord;
window.deleteEmploymentRecord = deleteEmploymentRecord;
window.closeDeleteEmploymentModal = closeDeleteEmploymentModal;
window.confirmDeleteEmploymentRecord = confirmDeleteEmploymentRecord;
window.openEditWiirpModal = openEditWiirpModal;
window.closeEditWiirpModal = closeEditWiirpModal;
window.submitEditWiirp = submitEditWiirp;
window.openEditWiirpAssignmentModal = openEditWiirpAssignmentModal;
window.closeEditWiirpAssignmentModal = closeEditWiirpAssignmentModal;
window.submitEditWiirpAssignment = submitEditWiirpAssignment;
window.openAddWiirpAssignmentModal = openAddWiirpAssignmentModal;
window.submitAddWiirpAssignment = submitAddWiirpAssignment;
window.deleteWiirpAssignment = deleteWiirpAssignment;
window.closeDeleteWiirpAssignmentModal = closeDeleteWiirpAssignmentModal;
window.confirmDeleteWiirpAssignment = confirmDeleteWiirpAssignment;
window.openAddGipModal = openAddGipModal;
window.openEditGipModal = openEditGipModal;
window.closeEditGipModal = closeEditGipModal;
window.submitEditGip = submitEditGip;
window.submitAddGip = submitAddGip;
window.deleteGipRecord = deleteGipRecord;
window.closeDeleteGipModal = closeDeleteGipModal;
window.confirmDeleteGip = confirmDeleteGip;

// ── Edit Job Fair ────────────────────────────────────────────────────────────

function loadJobFairCompanies(eventId, selectedCompanyId = '', targetSelectId = 'editJobFairCompany') {
  const compSelect = document.getElementById(targetSelectId);
  if (!eventId) {
    compSelect.innerHTML = '<option value="">— Select event first —</option>';
    return;
  }

  compSelect.innerHTML = '<option value="">Loading companies…</option>';
  fetch(`../../backend/beneficiaries/get_jobfair_companies.php?event_id=${eventId}`)
    .then(r => r.json())
    .then(j => {
      if (j && j.success && j.companies.length) {
        compSelect.innerHTML = '<option value="">— Select company —</option>';
        j.companies.forEach(c => {
          const opt = document.createElement('option');
          opt.value = c.company_id;
          const fullText = c.company_name;
          opt.textContent = fullText.length > 70 ? fullText.substring(0, 70) + '...' : fullText;
          opt.title = fullText;
          if (String(c.company_id) === String(selectedCompanyId)) {
            opt.selected = true;
          }
          compSelect.appendChild(opt);
        });
      } else {
        compSelect.innerHTML = '<option value="">No companies available</option>';
      }
    })
    .catch(() => {
      compSelect.innerHTML = '<option value="">Error loading companies</option>';
    });
}

function openEditJobFairModal(index) {
  const r = window.currentJobFairRecords[index];
  if (!r) return;

  document.getElementById('editJobFairId').value = r.jobfair_id;
  document.getElementById('editJobFairPosition').value = r.position || '';

  const evtSelect = document.getElementById('editJobFairEvent');
  evtSelect.innerHTML = '<option value="">Loading events…</option>';

  fetch('../../backend/beneficiaries/get_jobfair_events_options.php')
    .then(res => res.json())
    .then(j => {
      if (j && j.success && j.events.length) {
        evtSelect.innerHTML = '<option value="">— Select Event / Venue —</option>';
        j.events.forEach(e => {
          const opt = document.createElement('option');
          opt.value = e.jobfairevent_id;
          const fullText = `${e.job_fair_type} - ${e.venue} (${e.date_start})`;
          opt.textContent = fullText.length > 70 ? fullText.substring(0, 70) + '...' : fullText;
          opt.title = fullText;
          if (String(e.jobfairevent_id) === String(r.jobfairevent_id)) {
            opt.selected = true;
          }
          evtSelect.appendChild(opt);
        });
        loadJobFairCompanies(r.jobfairevent_id, r.company_id, 'editJobFairCompany');
      } else {
        evtSelect.innerHTML = '<option value="">No events available</option>';
      }
    })
    .catch(() => {
      evtSelect.innerHTML = '<option value="">Error loading events</option>';
    });

  _toggleEditModal('modalEditJobFair', 'flex');
}

function closeEditJobFairModal() {
  _toggleEditModal('modalEditJobFair', 'none');
}

function submitEditJobFair() {
  const jobfair_id = document.getElementById('editJobFairId').value;
  const jobfairevent_id = document.getElementById('editJobFairEvent').value;
  const company_id = document.getElementById('editJobFairCompany').value;
  const position = document.getElementById('editJobFairPosition').value.trim();

  if (!jobfairevent_id || !company_id) {
    _showEditToast('Event and Company are required.', 'error');
    return;
  }

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_jobfair.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      jobfair_id: jobfair_id,
      jobfairevent_id: jobfairevent_id,
      company_id: company_id,
      position: position
    })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast(j.message || 'Job fair updated.', 'success');
        closeEditJobFairModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j.error || 'Failed to update job fair.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error updating job fair.', 'error');
    });
}

window.openEditJobFairModal = openEditJobFairModal;
window.closeEditJobFairModal = closeEditJobFairModal;
window.submitEditJobFair = submitEditJobFair;
window.loadJobFairCompanies = loadJobFairCompanies;

// ── Add Job Fair ─────────────────────────────────────────────────────────────

function openAddJobFairModal() {
  document.getElementById('addJobFairPosition').value = '';
  document.getElementById('addJobFairCompany').innerHTML = '<option value="">— Select event first —</option>';

  const evtSelect = document.getElementById('addJobFairEvent');
  evtSelect.innerHTML = '<option value="">Loading events…</option>';

  fetch('../../backend/beneficiaries/get_jobfair_events_options.php')
    .then(res => res.json())
    .then(j => {
      if (j && j.success && j.events.length) {
        evtSelect.innerHTML = '<option value="">— Select Event / Venue —</option>';
        j.events.forEach(e => {
          const opt = document.createElement('option');
          opt.value = e.jobfairevent_id;
          const fullText = `${e.job_fair_type} - ${e.venue} (${e.date_start})`;
          opt.textContent = fullText.length > 70 ? fullText.substring(0, 70) + '...' : fullText;
          opt.title = fullText;
          evtSelect.appendChild(opt);
        });
      } else {
        evtSelect.innerHTML = '<option value="">No events available</option>';
      }
    })
    .catch(() => {
      evtSelect.innerHTML = '<option value="">Error loading events</option>';
    });

  _toggleEditModal('modalAddJobFair', 'flex');
}

function closeAddJobFairModal() {
  _toggleEditModal('modalAddJobFair', 'none');
}

function submitAddJobFair() {
  const jobfairevent_id = document.getElementById('addJobFairEvent').value;
  const company_id = document.getElementById('addJobFairCompany').value;
  const position = document.getElementById('addJobFairPosition').value.trim();

  if (!jobfairevent_id || !company_id) {
    _showEditToast('Event and Company are required.', 'error');
    return;
  }

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/add_jobfair.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      benef_id: window.currentBeneficiaryId,
      jobfairevent_id: jobfairevent_id,
      company_id: company_id,
      position: position
    })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast(j.message || 'Job fair record added.', 'success');
        closeAddJobFairModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j.error || 'Failed to add job fair.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error adding job fair.', 'error');
    });
}

window.openAddJobFairModal = openAddJobFairModal;
window.closeAddJobFairModal = closeAddJobFairModal;
window.submitAddJobFair = submitAddJobFair;

// ── Delete Job Fair ──────────────────────────────────────────────────────────

function deleteJobFairRecord(jobfair_id) {
  document.getElementById('deleteJobFairId').value = jobfair_id;
  _toggleEditModal('modalDeleteJobFair', 'flex');
}

function closeDeleteJobFairModal() {
  _toggleEditModal('modalDeleteJobFair', 'none');
  document.getElementById('deleteJobFairId').value = '';
}

function confirmDeleteJobFairRecord() {
  const id = document.getElementById('deleteJobFairId').value;
  if (!id) return;

  _withModalSaveLoading('Deleting…', () => fetch('../../backend/beneficiaries/delete_jobfair.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ jobfair_id: id })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('Job fair record deleted.', 'success');
        closeDeleteJobFairModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j.error || 'Failed to delete record.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error deleting record.', 'error');
    });
}

window.deleteJobFairRecord = deleteJobFairRecord;
window.closeDeleteJobFairModal = closeDeleteJobFairModal;
window.confirmDeleteJobFairRecord = confirmDeleteJobFairRecord;

// ── Add WHIP Assignment ──────────────────────────────────────────────────────

function openAddWhipModal() {
  document.getElementById('addWhipPosition').value = '';
  document.getElementById('addWhipDateHired').value = '';

  const projSelect = document.getElementById('addWhipProject');
  projSelect.innerHTML = '<option value="">Loading projects…</option>';

  fetch('../../backend/beneficiaries/get_projects_options.php')
    .then(res => res.json())
    .then(j => {
      if (j && j.success && j.projects.length) {
        projSelect.innerHTML = '<option value="">— Select Project —</option>';
        j.projects.forEach(p => {
          const opt = document.createElement('option');
          opt.value = p.project_id;
          const fullText = `${p.project_title} (Contractor: ${p.contractor || 'N/A'})`;
          opt.textContent = fullText.length > 70 ? fullText.substring(0, 70) + '...' : fullText;
          opt.title = fullText;
          projSelect.appendChild(opt);
        });
      } else {
        projSelect.innerHTML = '<option value="">No projects available</option>';
      }
    })
    .catch(() => {
      projSelect.innerHTML = '<option value="">Error loading projects</option>';
    });

  _toggleEditModal('modalAddWhip', 'flex');
}

function closeAddWhipModal() {
  _toggleEditModal('modalAddWhip', 'none');
}

function submitAddWhip() {
  const project_id = document.getElementById('addWhipProject').value;
  const position = document.getElementById('addWhipPosition').value.trim();
  const date_hired = document.getElementById('addWhipDateHired').value;

  if (!project_id) {
    _showEditToast('Project is required.', 'error');
    return;
  }

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/add_whip.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      benef_id: window.currentBeneficiaryId,
      project_id: project_id,
      position: position,
      date_hired: date_hired
    })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast(j.message || 'Project assignment added.', 'success');
        closeAddWhipModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j.error || 'Failed to add project assignment.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error adding project assignment.', 'error');
    });
}

window.openAddWhipModal = openAddWhipModal;
window.closeAddWhipModal = closeAddWhipModal;
window.submitAddWhip = submitAddWhip;

function openEditWhipModal(index) {
  const r = window.currentWhipRecords[index];
  if (!r) return;

  document.getElementById('editWhipId').value = r.whip_id;
  document.getElementById('editWhipPosition').value = r.position || '';
  document.getElementById('editWhipDateHired').value = formatDateForInput(r.date_hired) || '';

  const projSelect = document.getElementById('editWhipProject');
  projSelect.innerHTML = '<option value="">Loading projects…</option>';

  fetch('../../backend/beneficiaries/get_projects_options.php')
    .then(res => res.json())
    .then(j => {
      if (j && j.success && j.projects.length) {
        projSelect.innerHTML = '<option value="">— Select Project —</option>';
        j.projects.forEach(p => {
          const opt = document.createElement('option');
          opt.value = p.project_id;
          const fullText = `${p.project_title} (Contractor: ${p.contractor || 'N/A'})`;
          opt.textContent = fullText.length > 70 ? fullText.substring(0, 70) + '...' : fullText;
          opt.title = fullText;
          if (String(p.project_id) === String(r.project_id)) {
            opt.selected = true;
          }
          projSelect.appendChild(opt);
        });
      } else {
        projSelect.innerHTML = '<option value="">No projects available</option>';
      }
    })
    .catch(() => {
      projSelect.innerHTML = '<option value="">Error loading projects</option>';
    });

  _toggleEditModal('modalEditWhip', 'flex');
}

function closeEditWhipModal() {
  _toggleEditModal('modalEditWhip', 'none');
}

function submitEditWhip() {
  const whip_id = document.getElementById('editWhipId').value;
  const project_id = document.getElementById('editWhipProject').value;
  const position = document.getElementById('editWhipPosition').value.trim();
  const date_hired = document.getElementById('editWhipDateHired').value;

  if (!project_id) {
    _showEditToast('Project is required.', 'error');
    return;
  }

  _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_whip.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      whip_id: whip_id,
      project_id: project_id,
      position: position,
      date_hired: date_hired
    })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast(j.message || 'Project assignment updated.', 'success');
        closeEditWhipModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j.error || 'Failed to update project assignment.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error updating project assignment.', 'error');
    });
}

function deleteWhipRecord(whip_id) {
  document.getElementById('deleteWhipId').value = whip_id;
  _toggleEditModal('modalDeleteWhip', 'flex');
}

function closeDeleteWhipModal() {
  _toggleEditModal('modalDeleteWhip', 'none');
  document.getElementById('deleteWhipId').value = '';
}

function confirmDeleteWhipRecord() {
  const id = document.getElementById('deleteWhipId').value;
  if (!id) return;

  _withModalSaveLoading('Deleting…', () => fetch('../../backend/beneficiaries/delete_whip.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ whip_id: id })
  })
    .then(r => r.json())
    .then(j => {
      if (j && j.success) {
        _showEditToast('Project assignment deleted.', 'success');
        closeDeleteWhipModal();
        if (window.currentBeneficiaryId) {
          openProfile(window.currentBeneficiaryId);
        }
      } else {
        _showEditToast(j.error || 'Failed to delete record.', 'error');
      }
    }))
    .catch(() => {
      _showEditToast('Error deleting record.', 'error');
    });
}

window.openEditWhipModal = openEditWhipModal;
window.closeEditWhipModal = closeEditWhipModal;
window.submitEditWhip = submitEditWhip;
window.deleteWhipRecord = deleteWhipRecord;
window.closeDeleteWhipModal = closeDeleteWhipModal;
window.confirmDeleteWhipRecord = confirmDeleteWhipRecord;

// Issuance handlers
window.openEditIssuanceModal = openEditIssuanceModal;
window.closeEditIssuanceModal = closeEditIssuanceModal;
window.submitUpdateIssuance = submitUpdateIssuance;

window.selectProgramPill = function (idx) {
  const b = window.currentBeneficiary;
  if (!b || !b.enrollments || !b.enrollments[idx]) return;

  // Update pills styling
  const pillBar = document.getElementById('profProgramPillBar');
  if (pillBar) {
    const buttons = pillBar.querySelectorAll('button');
    buttons.forEach((btn, i) => {
      if (i === idx) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  }

  const enrollment = b.enrollments[idx];
  const programName = (enrollment.program_name || '').trim();

  // Update status badge
  const statusNorm = (enrollment.status || '').trim();
  const statusKey = statusNorm.charAt(0).toUpperCase() + statusNorm.slice(1).toLowerCase();
  const statusMap = {
    Hired: ['status-hired', 'Hired'],
    Placed: ['status-hired', 'Placed'],
    Referred: ['status-referred', 'Referred'],
    Registered: ['status-registered', 'Registered'],
  };
  const [cls, lbl] = statusMap[statusKey] || ['status-registered', statusNorm || 'Unknown'];
  const sb = document.getElementById('profStatusBadge');
  if (sb) {
    sb.className = `status-badge-pill ${cls}`;
    sb.textContent = lbl;
  }

  // Update cards visibility and fetch data if needed
  _updateProgramCards(b, programName);
};

function _updateProgramCards(b, overrideProgramName) {
  // This function replaces the inline logic that was formerly inside openProfile()

  const educationCard = document.getElementById('educationCard');
  const jobFairCard = document.getElementById('jobFairCard');
  const firstTimeJobSeekerCard = document.getElementById('firstTimeJobSeekerCard');
  const whipCard = document.getElementById('whipCard');
  const wiirpCard = document.getElementById('wiirpCard');
  const wiirpAssignmentCard = document.getElementById('wiirpAssignmentCard');
  const spesStudentCard = document.getElementById('spesStudentCard');
  const spesEmploymentCard = document.getElementById('spesEmploymentCard');
  const gipCard = document.getElementById('gipCard');
  const programName = (overrideProgramName || b.program || '').trim();
  const hideEducationCard = [
    'Job Fair',
    'Job Matching and Referral',
    'First Time Jobseeker',
    'Workers Hiring for Infrastructure Projects',
    'SPES',
    'Work Immersion and Internship Referral Program',
    'Government Internship Program',
  ].includes(programName);

  if (programName === 'Job Fair') {
    if (educationCard) educationCard.style.display = 'none';
    if (jobFairCard) jobFairCard.style.display = 'block';
    if (firstTimeJobSeekerCard) firstTimeJobSeekerCard.style.display = 'none';
    if (whipCard) whipCard.style.display = 'none';
    if (wiirpCard) wiirpCard.style.display = 'none';

    const eventsEl = document.getElementById('pJobFairEvents');

    if (eventsEl) eventsEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>`;

    fetch(`../../backend/beneficiaries/get_jobfair.php?benef_id=${encodeURIComponent(b.benef_id)}`)
      .then(r => r.json())
      .then(j => {
        if (!eventsEl) return;
        if (j && j.success && Array.isArray(j.records) && j.records.length) {
          window.currentJobFairRecords = j.records;
          eventsEl.innerHTML = j.records.map((r, idx) => `
            <tr>
              <td>${upperText(r.job_fair_type)}</td>
              <td style="font-weight:500;">${upperText(r.venue)}</td>
              <td>${r.date_start ? (upperText(r.date_start) + (r.date_end ? ` — ${upperText(r.date_end)}` : '')) : '—'}</td>
              <td style="font-weight:500;">${upperText(r.company_name)}</td>
              <td style="color:var(--text-secondary);font-size:12.5px;">${upperText(r.position)}</td>
              <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
                  <button onclick="openEditJobFairModal(${idx})" style="padding:4px 8px;background:var(--accent);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Edit</button>
                  <button onclick="deleteJobFairRecord(${r.jobfair_id})" style="padding:4px 8px;background:var(--danger,#ef4444);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Delete</button>
              </td>
            </tr>
          `).join('');
        } else {
          eventsEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">No job fair records.</td></tr>`;
        }
      }).catch(err => {
        if (eventsEl) eventsEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">Error loading records.</td></tr>`;
      });
  } else {
    if (jobFairCard) jobFairCard.style.display = 'none';
  }

  if (programName === 'First Time Jobseeker') {
    if (educationCard) educationCard.style.display = 'none';
    if (jobFairCard) jobFairCard.style.display = 'none';
    if (firstTimeJobSeekerCard) firstTimeJobSeekerCard.style.display = 'block';
    if (whipCard) whipCard.style.display = 'none';

    const issuanceEl = document.getElementById('pFirstTimeJobSeekerIssuance');
    if (issuanceEl) issuanceEl.innerHTML = `<tr><td colspan="2" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>`;

    fetch(`../../backend/beneficiaries/get_first_time_jobseeker.php?benef_id=${encodeURIComponent(b.benef_id)}`)
      .then(r => r.json())
      .then(j => {
        if (!issuanceEl) return;

        const rows = Array.isArray(j?.records) ? j.records : [];
        if (j && j.success && rows.length) {
          const latest = rows[0];
          window.currentFirstTimeIssuanceRecord = latest;

          const renderStatus = (value) => Number(value) === 1
            ? '<span class="badge badge-hired">Issued</span>'
            : '<span class="badge badge-registered">Not issued</span>';

          issuanceEl.innerHTML = `
            <tr>
              <td style="font-weight:500;">${renderStatus(latest.occ_permit)}</td>
              <td style="font-weight:500;">${renderStatus(latest.health_card)}</td>
            </tr>
          `;
        } else {
          window.currentFirstTimeIssuanceRecord = null;
          issuanceEl.innerHTML = `<tr><td colspan="2" style="color:var(--text-muted);text-align:center;padding:16px;">No issuance records.</td></tr>`;
        }
      })
      .catch(() => {
        window.currentFirstTimeIssuanceRecord = null;
        if (issuanceEl) {
          issuanceEl.innerHTML = `<tr><td colspan="2" style="color:var(--text-muted);text-align:center;padding:16px;">Error loading records.</td></tr>`;
        }
      });
  } else if (firstTimeJobSeekerCard) {
    firstTimeJobSeekerCard.style.display = 'none';
  }

  if (programName === 'Work Immersion and Internship Referral Program') {
    if (educationCard) educationCard.style.display = 'none';
    if (jobFairCard) jobFairCard.style.display = 'none';
    if (firstTimeJobSeekerCard) firstTimeJobSeekerCard.style.display = 'none';
    if (whipCard) whipCard.style.display = 'none';
    if (wiirpCard) wiirpCard.style.display = 'block';
    if (wiirpAssignmentCard) wiirpAssignmentCard.style.display = 'none';
    if (spesStudentCard) spesStudentCard.style.display = 'none';
    if (spesEmploymentCard) spesEmploymentCard.style.display = 'none';

    const wiirpFields = {
      contract_period: 'pWiirpContractPeriod',
      school: 'pWiirpSchool',
      course: 'pWiirpCourse',
      required_hours: 'pWiirpRequiredHours',
      inquiry_type: 'pWiirpInquiryType',
      preferred_org_type: 'pWiirpPreferredOrgType',
      preferred_industry: 'pWiirpPreferredIndustry',
      is_willing_outside: 'pWiirpWillingOutside',
      internship_sched: 'pWiirpInternshipSched',
      start: 'pWiirpStartDate',
      year_level: 'pWiirpYearLevel',
      type: 'pWiirpType',
    };

    Object.values(wiirpFields).forEach(id => {
      const el = document.getElementById(id);
      if (el) el.textContent = 'Loading…';
    });

    fetch(`../../backend/beneficiaries/get_wiirp.php?benef_id=${encodeURIComponent(b.benef_id)}&t=${Date.now()}`)
      .then(r => r.json())
      .then(j => {
        const record = j && j.success ? j.record : null;
        const assignments = Array.isArray(j?.assignments) ? j.assignments : [];
        window.currentWiirpRecord = record;
        window.currentWiirpAssignmentRecord = assignments.length ? assignments[0] : null;
        const setText = (id, value) => {
          const el = document.getElementById(id);
          if (el) el.textContent = value ?? '—';
        };
        const formatBool = (value) => Number(value) === 1 ? 'Yes' : 'No';
        const formatWiirpType = (value) => {
          const normalized = String(value || '').trim().toLowerCase();
          if (normalized === 'inquiry') return 'Inquiry';
          if (normalized === 'peso-assigned') return 'PESO Assigned';
          if (normalized === 'private') return 'Private';
          return value || '—';
        };
        const formatDate = (value) => {
          if (!value) return '—';
          const date = new Date(value);
          return isNaN(date.getTime())
            ? String(value)
            : date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        };

        setText('pWiirpContractPeriod', upperText(record?.contract_period));
        setText('pWiirpSchool', upperText(record?.school));
        setText('pWiirpCourse', upperText(record?.course));
        setText('pWiirpRequiredHours', record?.required_hours != null ? String(record.required_hours) : '—');
        setText('pWiirpInquiryType', upperText(record?.inquiry_type));
        setText('pWiirpPreferredOrgType', upperText(record?.preferred_org_type));
        setText('pWiirpPreferredIndustry', upperText(record?.preferred_industry));
        setText('pWiirpWillingOutside', record ? formatBool(record.is_willing_outside) : '—');
        setText('pWiirpInternshipSched', upperText(record?.internship_sched));
        setText('pWiirpStartDate', record ? upperText(formatDate(record.start)) : '—');
        setText('pWiirpYearLevel', upperText(record?.year_level));
        setText('pWiirpType', upperText(formatWiirpType(record?.type)));

        const showAssignment = record && ['peso-assigned', 'private'].includes(String(record.type || '').trim().toLowerCase());
        const showEndorsements = record && String(record.type || '').trim().toLowerCase() === 'private';
        const assignmentCard = document.getElementById('wiirpAssignmentCard');
        const assignmentsEl = document.getElementById('pWiirpAssignments');
        const endorsement1Header = document.getElementById('wiirpEndorsement1Header');
        const endorsement2Header = document.getElementById('wiirpEndorsement2Header');

        if (assignmentCard) assignmentCard.style.display = showAssignment ? 'block' : 'none';

        if (assignmentsEl) {
          if (showAssignment) {
            // keep assignments array for editing/deleting
            window.currentWiirpAssignments = assignments;
            if (endorsement1Header) endorsement1Header.style.display = showEndorsements ? '' : 'none';
            if (endorsement2Header) endorsement2Header.style.display = showEndorsements ? '' : 'none';

            if (assignments.length) {
              const rows = assignments.map(a => `
                <tr>
                  <td>${upperText(formatDate(a.start_date))}</td>
                  <td>${upperText(formatDate(a.end_date))}</td>
                  <td>${a.required_hours != null ? String(a.required_hours) : '—'}</td>
                  <td style="font-weight:500;">${upperText(a.office_assignment)}</td>
                  ${showEndorsements ? `<td>${upperText(a.endorsement_1)}</td><td>${upperText(a.endorsement_2)}</td>` : ''}
                  <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
                    <button type="button" class="edit-btn-icon" onclick="openEditWiirpAssignmentModal(${a.id})" title="Edit Assignment">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button type="button" class="delete-btn-icon" onclick="deleteWiirpAssignment(${a.id})" title="Delete Assignment" style="background:transparent;border:none;color:var(--danger);cursor:pointer;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                    </button>
                  </td>
                </tr>
              `).join('');

              assignmentsEl.innerHTML = rows;
            } else {
              assignmentsEl.innerHTML = `<tr><td colspan="${showEndorsements ? 6 : 4}" style="color:var(--text-muted);text-align:center;padding:16px;">No assignment records.</td></tr>`;
            }
          } else {
            if (endorsement1Header) endorsement1Header.style.display = showEndorsements ? '' : 'none';
            if (endorsement2Header) endorsement2Header.style.display = showEndorsements ? '' : 'none';
            assignmentsEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">No assignment records.</td></tr>`;
          }
        }
      })
      .catch(() => {
        window.currentWiirpRecord = null;
        window.currentWiirpAssignmentRecord = null;
        Object.values(wiirpFields).forEach(id => {
          const el = document.getElementById(id);
          if (el) el.textContent = 'Error loading records.';
        });

        const assignmentsEl = document.getElementById('pWiirpAssignments');
        if (assignmentsEl) {
          assignmentsEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">Error loading records.</td></tr>`;
        }
      });
  } else if (wiirpCard) {
    wiirpCard.style.display = 'none';
    if (wiirpAssignmentCard) wiirpAssignmentCard.style.display = 'none';
  }

  if (programName === 'Workers Hiring for Infrastructure Projects') {
    if (educationCard) educationCard.style.display = 'none';
    if (jobFairCard) jobFairCard.style.display = 'none';
    if (firstTimeJobSeekerCard) firstTimeJobSeekerCard.style.display = 'none';
    if (whipCard) whipCard.style.display = 'block';
    if (wiirpCard) wiirpCard.style.display = 'none';

    const whipEl = document.getElementById('pWhipProjects');
    if (whipEl) whipEl.innerHTML = `<tr><td colspan="7" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>`;

    fetch(`../../backend/beneficiaries/get_whip.php?benef_id=${encodeURIComponent(b.benef_id)}`)
      .then(r => r.json())
      .then(j => {
        if (!whipEl) return;

        const rows = Array.isArray(j?.records) ? j.records : [];
        if (j && j.success && rows.length) {
          window.currentWhipRecords = rows;
          const formatDate = (value) => {
            if (!value) return '—';
            const date = new Date(value);
            return isNaN(date.getTime())
              ? String(value)
              : date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
          };
          const formatBudget = (value) => {
            const amount = Number(value);
            return Number.isFinite(amount)
              ? `₱${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
              : '—';
          };

          whipEl.innerHTML = rows.map((r, idx) => `
            <tr>
              <td style="font-weight:500;">${r.position || '—'}</td>
              <td>${formatDate(r.date_hired)}</td>
              <td style="font-weight:500;">${r.contractor || '—'}</td>
              <td>${r.project_title || '—'}</td>
              <td>${r.duration || '—'}</td>
              <td>${formatBudget(r.budget)}</td>
              <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
                  <button onclick="openEditWhipModal(${idx})" style="padding:4px 8px;background:var(--accent);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Edit</button>
                  <button onclick="deleteWhipRecord(${r.whip_id})" style="padding:4px 8px;background:var(--danger,#ef4444);color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;font-weight:500;">Delete</button>
              </td>
            </tr>
          `).join('');
        } else {
          whipEl.innerHTML = `<tr><td colspan="7" style="color:var(--text-muted);text-align:center;padding:16px;">No project records.</td></tr>`;
        }
      })
      .catch(() => {
        if (whipEl) {
          whipEl.innerHTML = `<tr><td colspan="7" style="color:var(--text-muted);text-align:center;padding:16px;">Error loading records.</td></tr>`;
        }
      });
  } else if (whipCard) {
    whipCard.style.display = 'none';
  }

  if (programName === 'SPES') {
    if (educationCard) educationCard.style.display = 'none';
    if (jobFairCard) jobFairCard.style.display = 'none';
    if (firstTimeJobSeekerCard) firstTimeJobSeekerCard.style.display = 'none';
    if (whipCard) whipCard.style.display = 'none';
    if (spesStudentCard) spesStudentCard.style.display = 'block';
    if (spesEmploymentCard) spesEmploymentCard.style.display = 'block';

    // Fetch SPES student info
    fetch(`../../backend/beneficiaries/get_spes.php?benef_id=${encodeURIComponent(b.benef_id)}`)
      .then(r => r.json())
      .then(j => {
        if (j && j.success && j.record) {
          renderSpesStudentInfo(j.record);
        } else {
          renderSpesStudentInfo(null);
        }
      })
      .catch(() => {
        renderSpesStudentInfo(null);
      });

    // Fetch SPES employment records
    const empEl = document.getElementById('pSpesEmployment');
    if (empEl) empEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>`;

    fetch(`../../backend/beneficiaries/get_spes_employment.php?benef_id=${encodeURIComponent(b.benef_id)}`)
      .then(r => r.json())
      .then(j => {
        if (!empEl) return;

        const rows = Array.isArray(j?.records) ? j.records : [];
        if (j && j.success && rows.length) {
          const formatDate = (value) => {
            if (!value) return '—';
            const date = new Date(value);
            return isNaN(date.getTime())
              ? String(value)
              : date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
          };

          const formatSpesCategory = (val) => {
            if (!val) return '—';
            const v = String(val).trim().toLowerCase();
            if (v === 'lgu') return 'LGU';
            if (v === 'dole') return 'DOLE';
            return upperText(val);
          };

          // keep a copy of the records for editing
          window.currentSpesEmploymentRecords = rows;

          empEl.innerHTML = rows.map(r => `
            <tr data-employment-id="${r.employment_id}">
              <td style="font-weight:500;">${r.company_name || '—'}</td>
              <td>${r.store_assignment || '—'}</td>
              <td>${formatDate(r.start_of_contract)}</td>
              <td>${formatDate(r.end_of_contract)}</td>
              <td>${r.days || '—'}</td>
              <td><span class="badge badge-registered">${formatSpesCategory(r.category)}</span></td>
              <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
                <button type="button" class="edit-btn-icon" onclick="editSpesEmployment(${r.employment_id})" title="Edit OJT">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                  </svg>
                </button>
                <button type="button" class="delete-btn-icon" onclick="deleteSpesEmployment(${r.employment_id})" title="Delete OJT" style="background:transparent;border:none;color:var(--danger);cursor:pointer;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                </button>
              </td>
            </tr>
          `).join('');
        } else {
          empEl.innerHTML = `<tr><td colspan="7" style="color:var(--text-muted);text-align:center;padding:16px;">No OJT records.</td></tr>`;
        }
      })
      .catch(() => {
        if (empEl) {
          empEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">Error loading records.</td></tr>`;
        }
      });
  } else {
    if (spesStudentCard) spesStudentCard.style.display = 'none';
    if (spesEmploymentCard) spesEmploymentCard.style.display = 'none';
  }

  // ---------------- SPES Employment Edit Handlers ----------------
  window.editSpesEmployment = function (employmentId) {
    const recs = Array.isArray(window.currentSpesEmploymentRecords) ? window.currentSpesEmploymentRecords : [];
    const record = recs.find(r => String(r.employment_id) === String(employmentId));
    if (!record) {
      _showEditToast('Could not load the selected OJT record.', 'error');
      return;
    }
    openEditSpesEmploymentModal(record);
  };

  window.openEditSpesEmploymentModal = function (record) {
    if (!record) return;
    window.currentSpesEmploymentMode = 'edit';
    document.getElementById('editSpesEmploymentId').value = record.employment_id || '';
    document.getElementById('editSpesEmploymentStore').value = record.store_assignment || '';
    document.getElementById('editSpesEmploymentStart').value = record.start_of_contract || '';
    document.getElementById('editSpesEmploymentEnd').value = record.end_of_contract || '';
    document.getElementById('editSpesEmploymentDays').value = record.days || '';
    document.getElementById('editSpesEmploymentCategory').value = record.category || '';
    _loadEmploymentCompanies('editSpesEmploymentCompany', record.company_id || '');
    _toggleEditModal('modalEditSpesEmployment', 'flex');
  };

  window.closeEditSpesEmploymentModal = function () {
    _toggleEditModal('modalEditSpesEmployment', 'none');
  };

  window.submitEditSpesEmployment = function () {
    const employmentId = document.getElementById('editSpesEmploymentId').value;
    if (!employmentId || window.currentSpesEmploymentMode === 'add') {
      return window.submitAddSpesEmployment();
    }

    const formData = new FormData();
    formData.append('employment_id', employmentId);
    formData.append('benef_id', window.currentBeneficiaryId || '');
    formData.append('company_id', document.getElementById('editSpesEmploymentCompany').value || '');
    formData.append('store_assignment', document.getElementById('editSpesEmploymentStore').value.trim());
    formData.append('start_of_contract', document.getElementById('editSpesEmploymentStart').value || '');
    formData.append('end_of_contract', document.getElementById('editSpesEmploymentEnd').value || '');
    formData.append('days', document.getElementById('editSpesEmploymentDays').value || '');
    formData.append('category', document.getElementById('editSpesEmploymentCategory').value || '');

    _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/update_spes_employment.php', {
      method: 'POST',
      body: formData
    })
      .then(r => r.json())
      .then(j => {
        if (j && j.success) {
          _showEditToast('OJT employment updated successfully.', 'success');
          closeEditSpesEmploymentModal();
          if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
        } else {
          _showEditToast(j?.error || 'Error updating OJT employment.', 'error');
        }
      }).catch(() => {
        _showEditToast('Error updating OJT employment.', 'error');
      })
    );
  };

  // ---------------- SPES Employment Add/Delete Handlers ----------------
  window.openAddSpesEmploymentModal = function () {
    window.currentSpesEmploymentMode = 'add';
    document.getElementById('editSpesEmploymentId').value = '';
    document.getElementById('editSpesEmploymentStore').value = '';
    document.getElementById('editSpesEmploymentStart').value = '';
    document.getElementById('editSpesEmploymentEnd').value = '';
    document.getElementById('editSpesEmploymentDays').value = '';
    document.getElementById('editSpesEmploymentCategory').value = '';
    _loadEmploymentCompanies('editSpesEmploymentCompany', '');
    const header = document.querySelector('#modalEditSpesEmployment .modal-header h3');
    if (header) header.textContent = 'Add SPES OJT Employment';
    const confirmBtn = document.querySelector('#modalEditSpesEmployment .btn-confirm');
    if (confirmBtn) {
      confirmBtn.textContent = 'Add';
      confirmBtn.onclick = submitAddSpesEmployment;
    }
    _toggleEditModal('modalEditSpesEmployment', 'flex');
  };

  window.submitAddSpesEmployment = function () {
    const formData = new FormData();
    formData.append('benef_id', window.currentBeneficiaryId || '');
    formData.append('company_id', document.getElementById('editSpesEmploymentCompany').value || '');
    formData.append('store_assignment', document.getElementById('editSpesEmploymentStore').value.trim());
    formData.append('start_of_contract', document.getElementById('editSpesEmploymentStart').value || '');
    formData.append('end_of_contract', document.getElementById('editSpesEmploymentEnd').value || '');
    formData.append('days', document.getElementById('editSpesEmploymentDays').value || '');
    formData.append('category', document.getElementById('editSpesEmploymentCategory').value || '');

    _withModalSaveLoading('Saving…', () => fetch('../../backend/beneficiaries/add_spes_employment.php', {
      method: 'POST',
      body: formData
    })
      .then(r => r.json())
      .then(j => {
        if (j && j.success) {
          _showEditToast('OJT employment added successfully.', 'success');
          closeEditSpesEmploymentModal();
          if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
        } else {
          _showEditToast(j?.error || 'Error adding OJT employment.', 'error');
        }
      }).catch(() => {
        _showEditToast('Error adding OJT employment.', 'error');
      })
    );
  };

  function deleteSpesEmployment(employmentId) {
    if (!employmentId) return;
    document.getElementById('deleteSpesEmploymentId').value = employmentId;
    _toggleEditModal('modalDeleteSpesEmployment', 'flex');
  }

  window.closeDeleteSpesEmploymentModal = function () {
    _toggleEditModal('modalDeleteSpesEmploymentModal' in window ? 'modalDeleteSpesEmployment' : 'modalDeleteSpesEmployment', 'none');
    document.getElementById('deleteSpesEmploymentId').value = '';
  };

  window.confirmDeleteSpesEmployment = function () {
    const id = document.getElementById('deleteSpesEmploymentId').value;
    if (!id) return;
    const formData = new FormData();
    formData.append('employment_id', id);
    formData.append('benef_id', window.currentBeneficiaryId || '');

    _withModalSaveLoading('Deleting…', () => fetch('../../backend/beneficiaries/delete_spes_employment.php', {
      method: 'POST',
      body: formData
    })
      .then(r => r.json())
      .then(j => {
        if (j && j.success) {
          _showEditToast('OJT employment deleted.', 'success');
          closeDeleteSpesEmploymentModal();
          if (window.currentBeneficiaryId) openProfile(window.currentBeneficiaryId);
        } else {
          _showEditToast(j?.error || 'Error deleting OJT employment.', 'error');
        }
      }).catch(() => {
        _showEditToast('Error deleting OJT employment.', 'error');
      })
    );
  };

  window.deleteSpesEmployment = deleteSpesEmployment;
  window.closeDeleteSpesEmploymentModal = window.closeDeleteSpesEmploymentModal;
  window.confirmDeleteSpesEmployment = window.confirmDeleteSpesEmployment;

  if (programName === 'Government Internship Program') {
    if (educationCard) educationCard.style.display = 'none';
    if (jobFairCard) jobFairCard.style.display = 'none';
    if (firstTimeJobSeekerCard) firstTimeJobSeekerCard.style.display = 'none';
    if (whipCard) whipCard.style.display = 'none';
    if (spesStudentCard) spesStudentCard.style.display = 'none';
    if (spesEmploymentCard) spesEmploymentCard.style.display = 'none';
    if (wiirpCard) wiirpCard.style.display = 'none';
    if (gipCard) gipCard.style.display = 'block';

    const gipEl = document.getElementById('pGipRecords');
    if (gipEl) gipEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">Loading…</td></tr>`;

    fetch(`../../backend/beneficiaries/get_gip.php?benef_id=${encodeURIComponent(b.benef_id)}`)
      .then(r => r.json())
      .then(j => {
        if (!gipEl) return;

        const formatDate = (value) => {
          if (!value) return '—';
          const date = new Date(value);
          return isNaN(date.getTime())
            ? String(value)
            : date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        };
        const getStatusBadge = (r) => {
          const st = String(r.status || '').trim().toLowerCase();
          if (st === 'active') return '<span class="badge badge-hired">Active</span>';
          if (st === 'completed') return '<span class="badge badge-registered">Completed</span>';
          if (st === 'backed out') return '<span class="badge" style="background:#fee2e2;color:#b91c1c;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">Backed Out</span>';

          // Fallback if status is empty:
          const isActive = !r.end_of_contract || new Date(r.end_of_contract) >= new Date(new Date().toDateString());
          return isActive ? '<span class="badge badge-hired">Active</span>' : '<span class="badge badge-registered">Completed</span>';
        };

        const rows = Array.isArray(j?.records) ? j.records : [];
        if (j && j.success && rows.length) {
          window.currentGipRecords = rows;

          gipEl.innerHTML = rows.map(r => `
            <tr>
              <td><span class="badge badge-registered">${upperText(r.type)}</span></td>
              <td>${formatDate(r.start_of_contract)}</td>
              <td>${formatDate(r.end_of_contract)}</td>
              <td style="font-weight:500;">${upperText(r.office_assignment)}</td>
              <td>${getStatusBadge(r)}</td>
              <td style="text-align:center;gap:6px;display:flex;align-items:center;justify-content:center;">
                <button type="button" class="edit-btn-icon" onclick="openEditGipModal(${r.gip_id})" title="Edit GIP">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button type="button" class="delete-btn-icon" onclick="deleteGipRecord(${r.gip_id})" title="Delete GIP" style="background:transparent;border:none;color:var(--danger);cursor:pointer;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                </button>
              </td>
            </tr>
          `).join('');
        } else {
          window.currentGipRecords = [];
          gipEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">No GIP records.</td></tr>`;
        }
      })
      .catch(() => {
        window.currentGipRecords = [];
        if (gipEl) gipEl.innerHTML = `<tr><td colspan="6" style="color:var(--text-muted);text-align:center;padding:16px;">Error loading records.</td></tr>`;
      });
  } else if (gipCard) {
    gipCard.style.display = 'none';
  }
  if (educationCard) {
    educationCard.style.display = hideEducationCard ? 'none' : '';
  }

  if (!hideEducationCard) {
    document.getElementById('pEducation').textContent = upperText(b.education);
    document.getElementById('pSkills').innerHTML = (b.skills || []).map(s => `<span class="skill-tag">${upperText(s)}</span>`).join('');
  }

}