let currentPage = 1;
let pageSize    = 10;  // rows per page sent to the server
let selectedIds  = new Set();          // tracks selected beneficiary IDs
let selectedMeta = new Map();          // id → { program } — persists across page turns

// ── Config (loaded once from beneficiaries-config.js) ────────────────────────
let _programsBySection = {};   // { employment_facilitation: [...], ... }
let _statusesByProgram = {};   // { 'Job Matching and Referral': [...], ... }

// Maps config key → human-readable section label
const SECTION_LABELS = {
  employment_facilitation: 'Employment Facilitation',
  employers_engagement:    'Employers Engagement',
  youth_employability:     'Youth Employability',
  career_development:      'Career Development',
};

function uniqueSorted(values) {
  return [...new Set(values.filter(Boolean))].sort((a, b) => a.localeCompare(b));
}

async function loadBeneficiaryConfig() {
  if (Object.keys(_programsBySection).length) return;
  try {
    const mod = await import('./beneficiaries-config.js');
    _programsBySection = mod.programsBySection || {};
    _statusesByProgram = mod.statusesByProgram || {};
  } catch (e) {
    // Fallback: try window global set by the config file
    const g = window.__beneficiariesConfig || {};
    _programsBySection = g.programsBySection || {};
    _statusesByProgram = g.statusesByProgram || {};
  }
}

function setSelectOptions(selectEl, allLabel, values, selectedValue = '', capitalize = false) {
  if (!selectEl) return;
  selectEl.innerHTML = '';
  selectEl.add(new Option(allLabel, ''));
  values.forEach(v => {
    const text = capitalize ? v.charAt(0).toUpperCase() + v.slice(1) : v;
    selectEl.add(new Option(text, v));
  });
  selectEl.value = (selectedValue && values.includes(selectedValue)) ? selectedValue : '';
}

/** All section labels defined in config. */
function getSections() {
  return Object.keys(_programsBySection).map(k => SECTION_LABELS[k] || k);
}

/** Programs for a given section label (or all programs if no section). */
function getProgramsForSection(sectionLabel) {
  if (!sectionLabel) {
    return Object.values(_programsBySection).flat();
  }
  // Reverse-look up the config key from the label
  const key = Object.keys(SECTION_LABELS).find(k => SECTION_LABELS[k] === sectionLabel) || sectionLabel;
  return _programsBySection[key] || [];
}

/** Statuses that are valid for the programs visible in the selected section. */
function getStatusesForSection(sectionLabel) {
  const programs = getProgramsForSection(sectionLabel);
  const statuses = programs.flatMap(p => _statusesByProgram[p] || []);
  return uniqueSorted(statuses);
}

function updateSectionOptions() {
  const el = document.getElementById('sectionFilter');
  setSelectOptions(el, 'All Sections', getSections(), el?.value || '');
}

function updateProgramOptions() {
  const section = document.getElementById('sectionFilter')?.value || '';
  const el      = document.getElementById('programFilter');
  setSelectOptions(el, 'All Programs', getProgramsForSection(section), el?.value || '');
}

function updateStatusOptions() {
  const section = document.getElementById('sectionFilter')?.value || '';
  const el      = document.getElementById('statusFilter');
  setSelectOptions(el, 'All Statuses', getStatusesForSection(section), el?.value || '', true);
}

function onSectionChange() {
  updateProgramOptions();
  updateStatusOptions();
  filterTable();
}

async function initBeneficiaryFilters() {
  await loadBeneficiaryConfig();
  updateSectionOptions();
  updateProgramOptions();
  updateStatusOptions();
}

/** Map a status string to its badge CSS class. */
function badgeClass(status) {
  const map = {
    Hired: 'badge-hired',
    Registered: 'badge-registered',
    Referred: 'badge-referred',
  };
  return map[status] || 'badge-registered';
}

/** Render the current page of rows into #tableBody. */
function renderTable() {
  const tbody      = document.getElementById('tableBody');
  const totalPages = Math.max(1, Math.ceil(totalBeneficiaries / pageSize));

  // Clamp currentPage
  currentPage = Math.min(Math.max(1, currentPage), totalPages);

  const start = (currentPage - 1) * pageSize + 1;
  const end   = Math.min(currentPage * pageSize, totalBeneficiaries);

  if (!beneficiaries.length) {
    tbody.innerHTML = `
      <tr class="empty-row">
        <td colspan="8">
          <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <span>No beneficiaries found</span>
          </div>
        </td>
      </tr>`;
  } else {
    tbody.innerHTML = beneficiaries.map(b => {
      const checked = selectedIds.has(b.id) ? 'checked' : '';
      return `
      <tr onclick="handleRowClick(event, ${b.id})" class="${selectedIds.has(b.id) ? 'row-selected' : ''}">
        <td class="td-check" onclick="event.stopPropagation()">
          <label class="check-wrap">
            <input type="checkbox" data-id="${b.id}" ${checked} onchange="toggleRowCheck(this, ${b.id})">
            <span class="checkmark"></span>
          </label>
        </td>
        <td class="td-name">${b.name}</td>
        <td>${b.gender}</td>
        <td>${b.section}</td>
        <td>${b.program}</td>
        <td><span class="badge ${badgeClass(b.status)}">${b.status}</span></td>
        <td class="td-secondary">${b.email}</td>
        <td class="td-secondary">${b.contact}</td>
      </tr>`;
    }).join('');
  }

  document.getElementById('footerLabel').textContent = totalBeneficiaries
    ? `Showing ${start}\u2013${end} of ${totalBeneficiaries} Beneficiaries`
    : 'Showing 0 of 0 Beneficiaries';

  renderPagination(totalBeneficiaries ? totalPages : 0);

  // Sync header checkbox and bulk bar to reflect any cross-page selections
  syncSelectAll();
  updateBulkBar();
}

/** Filter the beneficiaries array and re-render (server-side). */
async function filterTable() {
  const q  = document.getElementById('searchInput')?.value  || '';
  const sc = document.getElementById('sectionFilter')?.value || '';
  const pg = document.getElementById('programFilter')?.value || '';
  const st = document.getElementById('statusFilter')?.value  || '';

  currentPage = 1;
  clearSelection();

  await fetchBeneficiaries({
    page:    currentPage,
    limit:   pageSize,
    search:  q,
    section: sc,
    program: pg,
    status:  st,
  });

  renderTable();
}

/** Change the number of rows displayed per page and reload. */
async function changePageSize(size) {
  pageSize    = parseInt(size, 10);
  currentPage = 1;
  await filterTable();
}

/** Jump to a specific page and reload from server.
 *  Selections are intentionally preserved across page turns.
 */
async function goToPage(page) {
  const q  = document.getElementById('searchInput')?.value  || '';
  const sc = document.getElementById('sectionFilter')?.value || '';
  const pg = document.getElementById('programFilter')?.value || '';
  const st = document.getElementById('statusFilter')?.value  || '';

  currentPage = page;

  await fetchBeneficiaries({
    page:    currentPage,
    limit:   pageSize,
    search:  q,
    section: sc,
    program: pg,
    status:  st,
  });

  renderTable();
}

/** Build the pagination button strip. */
function renderPagination(totalPages) {
  const controls = document.getElementById('paginationControls');

  if (totalPages <= 1) {
    controls.innerHTML = '';
    return;
  }

  function buildPageList(total, current) {
    if (total <= 7) {
      return Array.from({ length: total }, (_, index) => index + 1);
    }

    const pages = [1];

    if (current <= 4) {
      for (let page = 2; page <= Math.min(4, total - 1); page++) {
        pages.push(page);
      }
      if (total > 5) pages.push('...');
      pages.push(total - 1, total);
      return pages;
    }

    if (current >= total - 3) {
      pages.push('...');
      for (let page = Math.max(2, total - 3); page <= total; page++) {
        pages.push(page);
      }
      return pages;
    }

    pages.push('...', current - 1, current, current + 1, '...', total - 1, total);
    return pages;
  }

  const btns = [];
  btns.push(`<button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="goToPage(${currentPage - 1})">&#8249;</button>`);

  buildPageList(totalPages, currentPage).forEach(page => {
    if (page === '...') {
      btns.push('<span class="page-ellipsis" aria-hidden="true">...</span>');
      return;
    }

    btns.push(`<button class="page-btn ${page === currentPage ? 'active' : ''}" onclick="goToPage(${page})">${page}</button>`);
  });

  btns.push(`<button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="goToPage(${currentPage + 1})">&#8250;</button>`);

  controls.innerHTML = btns.join('');
}

// ── Checkbox / Bulk-Selection ─────────────────────────────────────────────────

/** Click on a row: open profile unless the click was inside the checkbox cell. */
function handleRowClick(e, id) {
  if (e.target.closest('.td-check')) return;
  openProfile(id);
}

/** Called when a row checkbox changes. */
function toggleRowCheck(checkbox, id) {
  if (checkbox.checked) {
    selectedIds.add(id);
    // Store the program for this ID from the current page data
    const row = beneficiaries.find(b => b.id === id);
    if (row) selectedMeta.set(id, { program: row.program || '—' });
  } else {
    selectedIds.delete(id);
    selectedMeta.delete(id);
  }
  updateBulkBar();
  syncSelectAll();
}

/** Called when the header "select all" checkbox changes. */
function toggleSelectAll(checkbox) {
  const rowCheckboxes = document.querySelectorAll('#tableBody input[type="checkbox"]');
  rowCheckboxes.forEach(cb => {
    const id = parseInt(cb.dataset.id, 10);
    cb.checked = checkbox.checked;
    if (checkbox.checked) {
      selectedIds.add(id);
      // Store program from current page data
      const row = beneficiaries.find(b => b.id === id);
      if (row) selectedMeta.set(id, { program: row.program || '—' });
    } else {
      selectedIds.delete(id);
      selectedMeta.delete(id);
    }
    cb.closest('tr').classList.toggle('row-selected', checkbox.checked);
  });
  updateBulkBar();
}

/** Sync the header checkbox state (checked / indeterminate) with row selections. */
function syncSelectAll() {
  const rowCheckboxes = [...document.querySelectorAll('#tableBody input[type="checkbox"]')];
  const allChecked    = rowCheckboxes.length > 0 && rowCheckboxes.every(cb => cb.checked);
  const someChecked   = rowCheckboxes.some(cb => cb.checked);
  const headerCb      = document.getElementById('selectAllCheckbox');
  if (!headerCb) return;
  headerCb.checked       = allChecked;
  headerCb.indeterminate = !allChecked && someChecked;
}

/** Show/hide the bulk action bar based on how many rows are selected. */
function updateBulkBar() {
  const bar   = document.getElementById('bulkActionBar');
  const label = document.getElementById('bulkCount');
  const count = selectedIds.size;
  if (count > 0) {
    label.textContent = `${count} item${count > 1 ? 's' : ''} selected`;
    bar.style.display = 'flex';
  } else {
    bar.style.display = 'none';
  }
}

/** Clear all selections. */
function clearSelection() {
  selectedIds.clear();
  selectedMeta.clear();
  updateBulkBar();
  const headerCb = document.getElementById('selectAllCheckbox');
  if (headerCb) { headerCb.checked = false; headerCb.indeterminate = false; }
}

/** Open the bulk delete confirmation modal. */
function openBulkDeleteModal() {
  const count = selectedIds.size;
  if (!count) return;
  document.getElementById('bulkDeleteCount').textContent = count;
  document.getElementById('modalBulkDelete').style.display = 'flex';
}

function closeBulkDeleteModal() {
  document.getElementById('modalBulkDelete').style.display = 'none';
}

/** Open the bulk classify modal — handles single-program vs mixed-program selections. */
function openBulkClassifyModal() {
  const count = selectedIds.size;
  if (!count) return;

  // ── Collect unique programs from selectedMeta (persists across pages) ────
  const selectedPrograms = [
    ...new Set(
      [...selectedMeta.values()]
        .map(m => m.program)
        .filter(p => p && p !== '—')
    )
  ];

  // ── DOM refs ──────────────────────────────────────────────────────────────
  const countEl      = document.getElementById('bulkClassifyCount');
  const programName  = document.getElementById('bulkClassifyProgramName');
  const programRow   = document.getElementById('bulkClassifyProgramRow');
  const warningEl    = document.getElementById('bulkClassifyWarning');
  const warningText  = document.getElementById('bulkClassifyWarningText');
  const statusField  = document.getElementById('bulkClassifyStatusField');
  const statusEl     = document.getElementById('bulkClassifyStatus');
  const submitBtn    = document.getElementById('bulkClassifySubmitBtn');

  if (countEl) countEl.textContent = count;

  // ── Case 2: Mixed programs ────────────────────────────────────────────────
  if (selectedPrograms.length > 1) {
    if (programRow)  programRow.style.display  = 'none';
    if (statusField) statusField.style.display = 'none';
    if (warningEl)   warningEl.style.display   = 'flex';
    if (warningText) warningText.textContent =
      'Selected beneficiaries belong to different programs. ' +
      'Please select beneficiaries from the same program only to use bulk status update.';
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.45';
      submitBtn.style.cursor  = 'not-allowed';
    }
    document.getElementById('modalBulkClassify').style.display = 'flex';
    return;
  }

  // ── Case 1: Single program (or none found) ────────────────────────────────
  const prog     = selectedPrograms[0] || null;
  const statuses = prog ? (_statusesByProgram[prog] || []) : [];

  // Program info row
  if (programRow)  programRow.style.display  = 'flex';
  if (programName) programName.textContent   = prog || '—';

  // Warning hidden
  if (warningEl) warningEl.style.display = 'none';

  // Populate status dropdown
  if (statusEl) {
    statusEl.innerHTML = '<option value="">— Select status —</option>';
    statuses.forEach(s => {
      const label = s.charAt(0).toUpperCase() + s.slice(1);
      statusEl.add(new Option(label, s));
    });
  }

  // Show status field
  if (statusField) statusField.style.display = 'block';

  // Enable submit
  if (submitBtn) {
    submitBtn.disabled = false;
    submitBtn.style.opacity = '';
    submitBtn.style.cursor  = '';
  }

  document.getElementById('modalBulkClassify').style.display = 'flex';
}

function closeBulkClassifyModal() {
  document.getElementById('modalBulkClassify').style.display = 'none';
}

/** Confirm bulk delete — sends selected IDs to the backend then refreshes. */
async function confirmBulkDelete() {
  const ids = [...selectedIds];
  if (!ids.length) return;

  // ── Loading state ────────────────────────────────────────────────────────
  const btn = document.querySelector('#modalBulkDelete .btn-confirm');
  const originalText = btn ? btn.textContent : '';
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = `
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
           style="animation:spin 0.8s linear infinite">
        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
      </svg>
      Deleting…`;
  }

  try {
    const res  = await fetch('../../backend/beneficiaries/bulk_delete.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ ids }),
    });
    const json = await res.json();

    closeBulkDeleteModal();
    clearSelection();

    if (json.success) {
      if (typeof window.showToast === 'function') {
        window.showToast(json.message || 'Beneficiaries deleted successfully.', 'success');
      }
    } else {
      if (typeof window.showToast === 'function') {
        window.showToast(json.message || 'Delete failed. Please try again.', 'error');
      }
    }

    await filterTable();

  } catch (err) {
    console.error('Bulk delete error:', err);
    closeBulkDeleteModal();
    if (typeof window.showToast === 'function') {
      window.showToast('Network error. Could not delete beneficiaries.', 'error');
    }
  } finally {
    if (btn) {
      btn.disabled = false;
      btn.textContent = originalText;
    }
  }
}

/** Confirm bulk status update — sends selected IDs + new status to the backend. */
async function confirmBulkClassify() {
  const ids    = [...selectedIds];
  const status = document.getElementById('bulkClassifyStatus').value;

  if (!ids.length) return;

  if (!status) {
    if (typeof window.showToast === 'function') {
      window.showToast('Please select a status to apply.', 'error');
    }
    return;
  }

  // ── Loading state ────────────────────────────────────────────────────────
  const btn = document.getElementById('bulkClassifySubmitBtn');
  const originalText = btn ? btn.textContent : '';
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = `
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
           style="animation:spin 0.8s linear infinite">
        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
      </svg>
      Applying…`;
  }

  try {
    const res  = await fetch('../../backend/beneficiaries/bulk_update_classification.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ ids, status }),
    });
    const json = await res.json();

    closeBulkClassifyModal();
    clearSelection();

    if (json.success) {
      if (typeof window.showToast === 'function') {
        window.showToast(json.message || 'Classification updated successfully.', 'success');
      }
    } else {
      if (typeof window.showToast === 'function') {
        window.showToast(json.message || 'Update failed. Please try again.', 'error');
      }
    }

    await filterTable();

  } catch (err) {
    console.error('Bulk classify error:', err);
    closeBulkClassifyModal();
    if (typeof window.showToast === 'function') {
      window.showToast('Network error. Could not update classification.', 'error');
    }
  } finally {
    if (btn) {
      btn.disabled = false;
      btn.textContent = originalText;
    }
  }
}
