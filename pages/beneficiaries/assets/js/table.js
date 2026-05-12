let currentPage = 1;
let pageSize    = 10;  // rows per page sent to the server

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
        <td colspan="7">
          <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <span>No beneficiaries found</span>
          </div>
        </td>
      </tr>`;
  } else {
    tbody.innerHTML = beneficiaries.map(b => `
      <tr onclick="openProfile(${b.id})">
        <td class="td-name">${b.name}</td>
        <td>${b.gender}</td>
        <td>${b.section}</td>
        <td>${b.program}</td>
        <td><span class="badge ${badgeClass(b.status)}">${b.status}</span></td>
        <td class="td-secondary">${b.email}</td>
        <td class="td-secondary">${b.contact}</td>
      </tr>`).join('');
  }

  document.getElementById('footerLabel').textContent = totalBeneficiaries
    ? `Showing ${start}\u2013${end} of ${totalBeneficiaries} Beneficiaries`
    : 'Showing 0 of 0 Beneficiaries';

  renderPagination(totalBeneficiaries ? totalPages : 0);
}

/** Filter the beneficiaries array and re-render (server-side). */
async function filterTable() {
  const q  = document.getElementById('searchInput')?.value  || '';
  const sc = document.getElementById('sectionFilter')?.value || '';
  const pg = document.getElementById('programFilter')?.value || '';
  const st = document.getElementById('statusFilter')?.value  || '';

  currentPage = 1;

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

/** Jump to a specific page and reload from server. */
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

  const btns = [];
  btns.push(`<button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="goToPage(${currentPage - 1})">&#8249;</button>`);

  for (let p = 1; p <= totalPages; p++) {
    btns.push(`<button class="page-btn ${p === currentPage ? 'active' : ''}" onclick="goToPage(${p})">${p}</button>`);
  }

  btns.push(`<button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="goToPage(${currentPage + 1})">&#8250;</button>`);

  controls.innerHTML = btns.join('');
}
