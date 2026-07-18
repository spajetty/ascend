import { openCreateEventModal } from '../../../../assets/js/imports/job-fair-modal.js';
import { statusesByProgram } from '../../../../assets/js/imports/config.js';
import { bindJobFairAutocomplete } from './manual-jobfair.js';
import { validatePanel } from './manual-validation.js';
import { buildReview } from './manual-review.js';
import { initDraftManager, clearDraft } from './manual-draft.js';

// Program config is handled by bindProgramBar below.

/**
 * assets/js/imports/manual.js
 * ─────────────────────────────────────────────────────────────
 * Manual entry form logic for the Import Data page.
 * Loaded as: <script type="module" src="...manual.js">
 *
 * All IDs and class names are prefixed with "mf-" to avoid
 * conflicts with other tabs on the same page.
 */

// ── DATA ──────────────────────────────────────────────────────────

const PROGRAMS = {
  '1': [
    { val: 'jobmatch',      label: 'Job Matching and Referral' },
    { val: 'firstjobseek', label: 'First Time Jobseeker' },
    { val: 'jobfair',      label: 'Job Fair' },
  ],
  '2': [
    { val: 'accreditation', label: 'Employers Accreditation' },
    { val: 'whip', label: 'Workers Hiring for Infrastructure Projects' },
  ],
  '3': [
    { val: 'spes',  label: 'SPES' },
    { val: 'gip',   label: 'Government Internship Program' },
    { val: 'wiirp', label: 'Work Immersion and Internship Referral Program' },
  ],
  '4': [
    { val: 'careerdev', label: 'Career Development Support Program' },
    { val: 'lmi',       label: 'LMI Orientation' },
  ],
};

const SECTION_LABELS = {
  '1': 'Employment Facilitation',
  '2': 'Employers Engagement',
  '3': 'Youth Employability',
  '4': 'Career Development',
};

// Which detail-section card IDs to show per program
const PROG_SECTIONS = {
  jobmatch:      ['mf-sec-employer'],
  firstjobseek:  ['mf-sec-employer', 'mf-sec-ftj'],
  jobfair:       ['mf-sec-employer', 'mf-sec-jobfair'],
  accreditation: ['mf-sec-accred'],
  // mf-sec-whip-projects (the "new project" sub-form) is toggled separately
  // by the project-picker logic, not shown unconditionally here.
  whip:          ['mf-sec-whip-picker', 'mf-sec-whip'],
  spes:          ['mf-sec-spes'],
  gip:           ['mf-sec-gip'],
  wiirp:         ['mf-sec-internship'],
  careerdev:     ['mf-sec-school'],
  lmi:           ['mf-sec-school'],
};

const ALL_DETAIL_SECTIONS = [
  'mf-sec-employer', 'mf-sec-ftj', 'mf-sec-jobfair',
  'mf-sec-accred',   'mf-sec-whip-picker', 'mf-sec-whip', 'mf-sec-whip-projects', 'mf-sec-spes',
  'mf-sec-internship', 'mf-sec-gip', 'mf-sec-school',
];

// ── STATE ──────────────────────────────────────────────────────────

let currentPanel    = 0;
let selectedSection = '';
let selectedProgram = '';
let lastSubmissionState = null;
let lastWhipProject = null; // { id, mode, title } — set on WHIP submit, used for "Add Another Worker"

// ── HELPERS ───────────────────────────────────────────────────────

// Element resolver with fallbacks for the two markup variants used
const $ = id => {
  const el = document.getElementById(id);
  if (el) return el;
  // Fallbacks for legacy IDs used in the Tailwind variant
  if (id === 'mf-sel-section') return document.getElementById('manualSection');
  if (id === 'mf-sel-program')  return document.getElementById('manualProgram');
  return null;
};

// ── INIT ──────────────────────────────────────────────────────────

function initManualForm() {
  bindProgramBar();
  bindAddressDropdowns();
  bindStepperTabs();
  bindNavButtons();
  bindChips();
  bindFlags();
  bindDob();
  bindFileSlots();
  bindCompanyAutocomplete();
  bindWhipProjectPicker();
  bindJobFairAutocomplete();
  bindDateConstraints();
  
  // Initialize Draft Auto-Save
  initDraftManager();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initManualForm);
} else {
  initManualForm();
}

// ── PROGRAM BAR ───────────────────────────────────────────────────

function bindProgramBar() {
  $('mf-sel-section').addEventListener('change', onSectionChange);
  $('mf-sel-program').addEventListener('change', onProgramChange);
  
  // Initialize state on load
  onSectionChange();
}

function onSectionChange() {
  selectedSection = $('mf-sel-section').value;
  selectedProgram = '';

  const selProg = $('mf-sel-program');
  selProg.innerHTML = '';
  
  const programs = PROGRAMS[selectedSection] || [];
  if (programs.length) {
    selProg.disabled = false;
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Select a program…';
    selProg.appendChild(placeholder);
    programs.forEach(p => {
      const opt       = document.createElement('option');
      opt.value       = p.val;
      opt.textContent = p.label;
      selProg.appendChild(opt);
    });
  } else {
    selProg.disabled = true;
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Select a section first…';
    selProg.appendChild(placeholder);
  }

  updateBadge();
  syncClassificationOptions();
}

function onProgramChange() {
  selectedProgram = $('mf-sel-program').value;
  updateBadge();
  syncClassificationOptions();

  // SPES status panel only shown for SPES
  const spesPanel = $('mf-cond-spes-status');
  if (spesPanel) spesPanel.classList.toggle('show', selectedProgram === 'spes');

  if (selectedProgram === 'whip') {
    resetWhipProjectPicker();
  }

  // Refresh detail sections if already on step 2
  if (currentPanel === 2) applyProgramSections();

  // Accreditation is company-level only, so jump straight to program details.
  if (selectedProgram === 'accreditation') {
    goPanel(2);
    return;
  }

  // Show the first form immediately once a valid program exists.
  if (selectedProgram) {
    goPanel(1);
  } else {
    goPanel(0);
  }
}

function syncClassificationOptions() {
  const classificationEl = $('mf-classification');
  const classificationWrap = $('mf-classification-wrap');
  const reviewItem = $('mf-rv-class-item');
  const programEl = $('mf-sel-program');
  if (!classificationEl) return;

  const programLabel = programEl?.selectedOptions?.[0]?.textContent?.trim() || '';
  const statuses = programLabel ? (statusesByProgram[programLabel] || []) : [];
  const currentValue = classificationEl.value;
  const shouldShow = statuses.length > 0;

  classificationEl.innerHTML = '<option value="">— select —</option>' +
    statuses.map(status => `<option value="${status}">${status}</option>`).join('');
  classificationEl.disabled = !shouldShow;
  classificationEl.required = shouldShow;
  if (classificationWrap) classificationWrap.style.display = shouldShow ? '' : 'none';
  if (reviewItem) reviewItem.style.display = shouldShow ? '' : 'none';

  if (shouldShow && currentValue && statuses.includes(currentValue)) {
    classificationEl.value = currentValue;
  } else {
    classificationEl.value = '';
  }
}

function bindAddressDropdowns() {
  const districtEl = $('mf-district');
  const barangayEl = $('mf-barangay');
  const addressData = globalThis.ADDRESS_DATA?.['Quezon City'];

  if (!districtEl || !barangayEl || !addressData?.districts) return;

  const districtEntries = Object.entries(addressData.districts)
    .sort((a, b) => Number(a[0]) - Number(b[0]));

  const barangaysByDistrict = new Map(
    districtEntries.map(([district, info]) => [district, info.barangays || []])
  );

  districtEl.innerHTML = '<option value="">Select district…</option>' +
    districtEntries.map(([district]) => `<option value="${district}">District ${district}</option>`).join('');

  function renderBarangays(district, selectedBarangay = '') {
    const barangays = barangaysByDistrict.get(district) || [];
    barangayEl.innerHTML = '<option value="">Select barangay…</option>' +
      barangays.map(barangay => `<option value="${barangay}">${barangay}</option>`).join('');
    barangayEl.disabled = !barangays.length;
    barangayEl.value = selectedBarangay && barangays.includes(selectedBarangay) ? selectedBarangay : '';
  }

  districtEl.addEventListener('change', () => {
    renderBarangays(districtEl.value);
  });

  barangayEl.addEventListener('change', () => {
    if (!barangayEl.value) return;
    const matchedDistrict = districtEntries.find(([, info]) =>
      (info.barangays || []).includes(barangayEl.value)
    );
    if (matchedDistrict) {
      districtEl.value = matchedDistrict[0];
    }
  });

  renderBarangays(districtEl.value);
}

function updateBadge() {
  const badge = $('mf-prog-badge');
  if (!badge) return;
  if (selectedProgram) {
    const p = (PROGRAMS[selectedSection] || []).find(x => x.val === selectedProgram);
    badge.textContent = p ? p.label : '—';
    badge.classList.remove('mf-hidden');
  } else {
    badge.classList.add('mf-hidden');
  }
}

// ── COMPANY AUTOCOMPLETE ──────────────────────────────────────────

function bindCompanyAutocomplete() {
  const inputs = [
    { el: $('mf-company'), hidden: $('mf-h-company-id') },
    { el: $('mf-accred-company'), hidden: null },
    { el: $('mf-project-contractor'), hidden: null }
  ];

  let debounceTimer;

  inputs.forEach(({ el, hidden }) => {
    if (!el) return;
    
    // Remove native datalist reference
    el.removeAttribute('list');

    // Setup wrapper for absolute positioning
    const parent = el.parentElement;
    parent.style.position = 'relative';

    // Fix the card container cutting off the dropdown
    const card = el.closest('.mf-card');
    if (card) {
      card.style.overflow = 'visible';
    }

    // Create custom styled dropdown
    const dropdown = document.createElement('div');
    dropdown.className = 'absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] hidden max-h-60 overflow-y-auto overflow-x-hidden';
    dropdown.style.zIndex = '9999';
    parent.appendChild(dropdown);

    const isAccredCompany = el.id === 'mf-accred-company';

    const renderAddCompanyAction = (typedName) => {
      if (!isAccredCompany || !typedName) return;

      const footer = document.createElement('div');
      footer.className = 'sticky bottom-0 border-t border-gray-100 bg-gray-50 p-2';

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'w-full rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-left text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors';
      btn.textContent = `+ Add "${typedName}" as a new company`;
      btn.addEventListener('click', () => {
        const modeHidden = $('mf-h-accred-company-mode');
        if (modeHidden) modeHidden.value = 'new';
        if (hidden) hidden.value = '';
        dropdown.classList.add('hidden');
        if (typeof window.showToast === 'function') {
          window.showToast('New company mode enabled. Continue filling in the company details.', 'info');
        }
      });

      footer.appendChild(btn);
      dropdown.appendChild(footer);
    };

    const closeDropdown = () => {
      dropdown.classList.add('hidden');
    };

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!parent.contains(e.target)) {
        closeDropdown();
      }
    });

    el.addEventListener('input', (e) => {
      const val = e.target.value;
      
      // Clear hidden input since the text is changing
      if (hidden) hidden.value = '';
      const modeHidden = el.id === 'mf-accred-company' ? $('mf-h-accred-company-mode') : null;
      if (modeHidden) modeHidden.value = 'search';
      
      const trimVal = val.trim();
      if (trimVal.length < 1) {
        closeDropdown();
        return;
      }

      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(async () => {
        try {
          const res = await fetch(`../../backend/import/search_companies.php?q=${encodeURIComponent(trimVal)}`);
          if (!res.ok) return;
          const data = await res.json();
          if (data.success && data.companies) {
            dropdown.innerHTML = '';
            const typedName = trimVal;
            
            if (data.companies.length === 0) {
              const noRes = document.createElement('div');
              noRes.className = 'px-4 py-3 text-sm text-gray-500';
              noRes.textContent = 'No companies found';
              dropdown.appendChild(noRes);
              renderAddCompanyAction(typedName);
            } else {
              data.companies.forEach(c => {
                const item = document.createElement('div');
                item.className = 'px-4 py-3 text-sm font-medium text-gray-700 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors';
                
                // Highlight matching part of text
                const regex = new RegExp(`(${trimVal})`, 'gi');
                const highlightedName = c.company_name.replace(regex, '<span class="text-blue-600 font-semibold">$1</span>');
                item.innerHTML = highlightedName;

                item.addEventListener('click', () => {
                  el.value = c.company_name;
                  if (hidden) hidden.value = c.company_id;
                  closeDropdown();
                });
                dropdown.appendChild(item);
              });

              if (isAccredCompany) {
                renderAddCompanyAction(typedName);
              }
            }
            
            dropdown.classList.remove('hidden');
          }
        } catch (err) {
          console.error('Failed to search companies:', err);
        }
      }, 300);
    });

    el.addEventListener('focus', () => {
      if (dropdown.innerHTML !== '' && el.value.trim().length > 0) {
        dropdown.classList.remove('hidden');
      }
    });
  });
}

// ── WHIP PROJECT PICKER (search existing project or add a new one) ────

let whipProjectsCache = null; // [{ project_id, project_title, contractor }]

function fetchWhipProjects() {
  if (whipProjectsCache) return Promise.resolve(whipProjectsCache);
  return fetch('../../backend/beneficiaries/get_projects_options.php')
    .then(res => res.json())
    .then(data => {
      whipProjectsCache = (data && data.success && Array.isArray(data.projects)) ? data.projects : [];
      return whipProjectsCache;
    })
    .catch(() => {
      whipProjectsCache = [];
      return whipProjectsCache;
    });
}

function showNewWhipProjectFields(typedTitle) {
  const card = $('mf-sec-whip-projects');
  if (card) card.style.display = 'block';
  const titleInput = $('mf-project-title');
  if (titleInput && typedTitle) titleInput.value = typedTitle;
  const modeHidden = $('mf-h-whip-project-mode');
  if (modeHidden) modeHidden.value = 'new';
  const idHidden = $('mf-h-whip-project-id');
  if (idHidden) idHidden.value = '';
  const summary = $('mf-whip-project-summary');
  if (summary) summary.style.display = 'none';
}

function hideNewWhipProjectFields() {
  const card = $('mf-sec-whip-projects');
  if (card) card.style.display = 'none';
}

function selectExistingWhipProject(project) {
  const search = $('mf-whip-project-search');
  const idHidden = $('mf-h-whip-project-id');
  const modeHidden = $('mf-h-whip-project-mode');
  if (search) search.value = project.project_title;
  if (idHidden) idHidden.value = project.project_id;
  if (modeHidden) modeHidden.value = 'search';
  hideNewWhipProjectFields();

  const summary = $('mf-whip-project-summary');
  const summaryTitle = $('mf-whip-project-summary-title');
  const summaryMeta = $('mf-whip-project-summary-meta');
  if (summary && summaryTitle && summaryMeta) {
    summaryTitle.textContent = project.project_title;
    summaryMeta.textContent = project.contractor ? `Contractor: ${project.contractor}` : '';
    summary.style.display = 'block';
  }
}

function resetWhipProjectPicker() {
  const search = $('mf-whip-project-search');
  const idHidden = $('mf-h-whip-project-id');
  const modeHidden = $('mf-h-whip-project-mode');
  if (search) search.value = '';
  if (idHidden) idHidden.value = '';
  if (modeHidden) modeHidden.value = 'search';
  hideNewWhipProjectFields();
  const summary = $('mf-whip-project-summary');
  if (summary) summary.style.display = 'none';
}

function bindWhipProjectPicker() {
  const search = $('mf-whip-project-search');
  if (!search) return;

  const parent = search.parentElement;
  parent.style.position = 'relative';
  const card = search.closest('.mf-card');
  if (card) card.style.overflow = 'visible';

  const dropdown = document.createElement('div');
  dropdown.className = 'absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] hidden max-h-60 overflow-y-auto overflow-x-hidden';
  dropdown.style.zIndex = '9999';
  parent.appendChild(dropdown);

  const closeDropdown = () => dropdown.classList.add('hidden');
  document.addEventListener('click', (e) => {
    if (!parent.contains(e.target)) closeDropdown();
  });

  let debounceTimer;
  search.addEventListener('input', (e) => {
    const typedName = e.target.value;
    const trimVal = typedName.trim();

    // Any manual edit invalidates a previous selection until they pick again.
    const idHidden = $('mf-h-whip-project-id');
    if (idHidden) idHidden.value = '';
    const summary = $('mf-whip-project-summary');
    if (summary) summary.style.display = 'none';

    if (trimVal.length < 1) {
      closeDropdown();
      hideNewWhipProjectFields();
      return;
    }

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
      const projects = await fetchWhipProjects();
      const q = trimVal.toLowerCase();
      const matches = projects.filter(p =>
        (p.project_title || '').toLowerCase().includes(q) ||
        (p.contractor || '').toLowerCase().includes(q)
      ).slice(0, 8);

      dropdown.innerHTML = '';

      matches.forEach(project => {
        const item = document.createElement('div');
        item.className = 'px-4 py-3 text-sm font-medium text-gray-700 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors';
        const label = project.contractor ? `${project.project_title} <span class="text-gray-400 font-normal">— ${project.contractor}</span>` : project.project_title;
        item.innerHTML = label;
        item.addEventListener('click', () => {
          selectExistingWhipProject(project);
          closeDropdown();
        });
        dropdown.appendChild(item);
      });

      if (matches.length === 0) {
        const noRes = document.createElement('div');
        noRes.className = 'px-4 py-3 text-sm text-gray-500';
        noRes.textContent = 'No matching projects found';
        dropdown.appendChild(noRes);
      }

      const footer = document.createElement('div');
      footer.className = 'sticky bottom-0 border-t border-gray-100 bg-gray-50 p-2';
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'w-full rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-left text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors';
      btn.textContent = `+ Add "${typedName}" as a new project`;
      btn.addEventListener('click', () => {
        showNewWhipProjectFields(typedName);
        closeDropdown();
        if (window.showToast) window.showToast('New project mode enabled. Fill in the project details below.', 'info');
      });
      footer.appendChild(btn);
      dropdown.appendChild(footer);

      dropdown.classList.remove('hidden');
    }, 250);
  });

  search.addEventListener('focus', () => {
    if (dropdown.innerHTML !== '' && search.value.trim().length > 0) {
      dropdown.classList.remove('hidden');
    }
  });
}

// ── JOB FAIR MULTI-SELECT ─────────────────────────────────────────
// Logic moved to manual-jobfair.js

// ── STEPPER TABS ──────────────────────────────────────────────────

function bindStepperTabs() {
  document.querySelectorAll('.mf-step-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const n = parseInt(tab.dataset.step);
      tryGoStep(n);
    });
  });
}

// Validation logic moved to manual-validation.js

function tryGoStep(n) {
  if (n >= 1 && !selectedProgram) {
    window.showToast('Please select a Section and Program first.', 'warning');
    return;
  }

  if (selectedProgram === 'accreditation') {
    // Accreditation only has two real steps: Program Details -> Review.
    // Route "Continue" (n=3, originally "Documents") and the Review tab (n=4)
    // to the Review panel so the user can check their input before submitting.
    if (n === 3 || n === 4) {
      if (!validatePanel(2, selectedProgram)) return;
      goPanel(4);
      return;
    }
  }
  
  if (n > currentPanel) {
    for (let i = currentPanel; i < n; i++) {
      if (!validatePanel(i, selectedProgram)) {
        return;
      }
    }
  }

  goPanel(n);
}

function goPanel(n) {
  if (selectedProgram === 'accreditation') {
    if (n === 1) n = 2;
    if (n === 3) n = 2; // "Back" from Review (panel 4) lands on Program Details
  }

  // Hide all panels, show target
  document.querySelectorAll('.mf-panel').forEach(p => p.classList.remove('active'));
  $('mf-panel-' + n).classList.add('active');

  // Disable Program/Section dropdowns if past Step 1
  const secSel = $('mf-sel-section');
  const progSel = $('mf-sel-program');
  if (secSel && progSel) {
    if (n >= 2) {
      secSel.disabled = true;
      progSel.disabled = true;
    } else {
      secSel.disabled = false;
      progSel.disabled = !secSel.value;
    }
  }

  // Update stepper visual state
  for (let i = 1; i <= 4; i++) {
    const tab = $('mf-stab-' + i);
    const num = $('mf-snum-' + i);
    if (!tab) continue;
    tab.classList.remove('active', 'done');
    if (i < n)        { tab.classList.add('done');   num.textContent = '✓'; }
    else if (i === n) { tab.classList.add('active'); num.textContent = i;   }
    else                num.textContent = i;
  }

  // Progress bar
  const pct = { 0: 0, 1: 25, 2: 50, 3: 75, 4: 95, 5: 100 }[n] ?? 0;
  $('mf-prog-fill').style.width = pct + '%';

  // Panel-specific hooks
  if (n === 2) applyProgramSections();
  if (n === 4) {
    buildReview(selectedProgram, selectedSection, PROGRAMS, SECTION_LABELS);
  }
  currentPanel = n;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── NAV BUTTONS ───────────────────────────────────────────────────

function bindNavButtons() {
  // Step 1 → 2
  const next1 = $('mf-next-1');
  if (next1) next1.addEventListener('click', () => tryGoStep(2));

  // Step 2
  const back2 = $('mf-back-2');
  const next2 = $('mf-next-2');
  if (back2) back2.addEventListener('click', () => goPanel(1));
  if (next2) next2.addEventListener('click', () => tryGoStep(3));

  // Step 3
  const back3 = $('mf-back-3');
  const next3 = $('mf-next-3');
  if (back3) back3.addEventListener('click', () => goPanel(2));
  if (next3) next3.addEventListener('click', () => tryGoStep(4));

  // Step 4
  const back4    = $('mf-back-4');
  const submit   = $('mf-submit');
  if (back4)  back4.addEventListener('click', () => goPanel(3));
  if (submit) submit.addEventListener('click', submitForm);

  // Success → add another
  const addAnother = $('mf-add-another');
  if (addAnother) addAnother.addEventListener('click', resetForm);

  // Success → add another worker to the same WHIP project
  const addWhipWorker = $('mf-add-whip-worker');
  if (addWhipWorker) addWhipWorker.addEventListener('click', addAnotherWhipWorker);

  // Undo submission
  const undoSubmit = $('mf-undo-submit');
  if (undoSubmit) undoSubmit.addEventListener('click', undoSubmission);
}

// ── PROGRAM SECTIONS (panel 2) ────────────────────────────────────

function applyProgramSections() {
  const show = PROG_SECTIONS[selectedProgram] || [];
  const isAccreditation = selectedProgram === 'accreditation';

  ALL_DETAIL_SECTIONS.forEach(id => {
    const el = $(id);
    if (el) el.style.display = show.includes(id) ? 'block' : 'none';
  });

  // mf-sec-whip-projects isn't in PROG_SECTIONS (it's conditional), so restore
  // its visibility based on the current project mode rather than always hiding it.
  if (selectedProgram === 'whip') {
    const modeHidden = $('mf-h-whip-project-mode');
    const projCard = $('mf-sec-whip-projects');
    if (projCard) projCard.style.display = (modeHidden && modeHidden.value === 'new') ? 'block' : 'none';
  }

  const beneficiaryPanel = $('mf-panel-1');
  const documentsPanel = $('mf-panel-3');
  const step1 = $('mf-stab-1');
  const step3 = $('mf-stab-3');
  if (beneficiaryPanel) beneficiaryPanel.style.display = isAccreditation ? 'none' : '';
  if (documentsPanel) documentsPanel.style.display = isAccreditation ? 'none' : '';
  if (step1) step1.style.display = isAccreditation ? 'none' : '';
  if (step3) step3.style.display = isAccreditation ? 'none' : '';
  // Step 4 (Review) and panel 2/4 visibility are controlled entirely by the
  // 'active' class via goPanel() — no inline style overrides here, otherwise
  // the panel stays visibly stuck on screen after navigating away from it.

  // WIIRP-only fields
  document.querySelectorAll('.mf-wiirp-only').forEach(el => {
    el.style.display = selectedProgram === 'wiirp' ? 'flex' : 'none';
  });
  
  syncWiirpFields();

  // Dynamic titles
  const internTitle = $('mf-internship-title');
  if (internTitle) {
    internTitle.textContent =
      selectedProgram === 'wiirp' ? 'Work Immersion & Internship Referral Details' :
                                    'Internship / Immersion Details';
  }

  const accredCompany = $('mf-accred-company');
  if (accredCompany) {
    accredCompany.placeholder = 'Search existing company or type a new company name';
  }

  const next2 = $('mf-next-2');
  if (next2) {
    next2.textContent = isAccreditation ? 'Continue → Review' : 'Continue → Documents';
  }

  const schoolTitle = $('mf-school-card-title');
  if (schoolTitle) {
    schoolTitle.textContent =
      selectedProgram === 'lmi' ? 'LMI Orientation Record' :
                                  'Career Development Activity Record';
  }

  // Update info note
  const p = (PROGRAMS[selectedSection] || []).find(x => x.val === selectedProgram);
  const noteText = $('mf-panel2-note-text');
  if (p && noteText) {
    noteText.innerHTML = `Showing fields for <strong>${p.label}</strong>. Fill in what applies.`;
  }
}

function syncWiirpFields() {
  const type = $('mf-h-inttype')?.value || 'inquiry';
  const assignmentSec = $('mf-int-assignment-sec');
  const endorse1 = $('mf-endorse1')?.closest('.mf-field');
  const endorse2 = $('mf-endorse2')?.closest('.mf-field');

  if (selectedProgram === 'wiirp') {
    if (type === 'inquiry') {
      if (assignmentSec) assignmentSec.style.display = 'none';
    } else if (type === 'peso-assigned') {
      if (assignmentSec) assignmentSec.style.display = 'block';
      if (endorse1) endorse1.style.display = 'none';
      if (endorse2) endorse2.style.display = 'none';
    } else if (type === 'private') {
      if (assignmentSec) assignmentSec.style.display = 'block';
      if (endorse1) endorse1.style.display = 'flex';
      if (endorse2) endorse2.style.display = 'flex';
    }
  } else {
    if (assignmentSec) assignmentSec.style.display = 'none';
  }
}

// ── CHIPS (radio-style toggles) ───────────────────────────────────

function bindChips() {
  // Delegate from the whole tab so dynamically shown chips also work
  document.getElementById('tab-manual')?.addEventListener('click', e => {
    const chip = e.target.closest('.mf-chip');
    if (!chip) return;

    const group = chip.dataset.group;
    if (!group) return;

    // Deselect siblings
    document.querySelectorAll(`[data-group="${group}"]`)
      .forEach(c => c.classList.remove('on'));

    chip.classList.add('on');

    // Sync hidden input
    const hiddenInput = document.getElementById(`mf-h-${group}`);
    if (hiddenInput) {
      hiddenInput.value = chip.dataset.val;
      if (group === 'inttype') {
        syncWiirpFields();
      }
    }
  });
}

// ── FLAG CHECKBOXES ───────────────────────────────────────────────

function bindFlags() {
  document.getElementById('tab-manual')?.addEventListener('click', e => {

    // Named flags (4ps / pwd / ofw)
    const namedFlag = e.target.closest('.mf-flag[data-flag]');
    if (namedFlag) {
      const type = namedFlag.dataset.flag;
      namedFlag.classList.toggle('on');
      const hidden = document.getElementById(`mf-h-${type}`);
      if (hidden) hidden.value = namedFlag.classList.contains('on') ? '1' : '0';

      // 4Ps reveals an extra field
      if (type === '4ps') {
        const cond = $('mf-cond-4ps');
        if (cond) cond.classList.toggle('show', namedFlag.classList.contains('on'));
      }
      return;
    }

    // Inline flags (occ_permit, health_card — no conditional panel)
    const inlineFlag = e.target.closest('.mf-flag[data-flag-inline]');
    if (inlineFlag) {
      const key = inlineFlag.dataset.flagInline;
      inlineFlag.classList.toggle('on');
      // Convert kebab-case data attribute to valid id segment
      const hidden = document.getElementById(`mf-h-${key.replace(/_/g, '-')}`);
      if (hidden) hidden.value = inlineFlag.classList.contains('on') ? '1' : '0';
    }
  });
}

// ── DOB → AGE HINT ───────────────────────────────────────────────

function bindDob() {
  const dob = $('mf-dob');
  if (!dob) return;
  const ageField = $('mf-age');

  // Block future dates in the native date picker
  const todayStr = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD
  dob.max = todayStr;

  const updateAge = () => {
    if (!ageField) return;
    if (!dob.value) {
      ageField.value = '';
      return;
    }

    const birthDate = new Date(dob.value);
    if (Number.isNaN(birthDate.getTime())) {
      ageField.value = '';
      return;
    }

    // Guard against a future date typed in manually (bypasses the max attribute)
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    if (birthDate > now) {
      dob.value = '';
      ageField.value = '';
      if (typeof window.showToast === 'function') {
        window.showToast('Date of birth cannot be in the future.', 'warning');
      } else {
        alert('Date of birth cannot be in the future.');
      }
      return;
    }

    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    const dayDiff = today.getDate() - birthDate.getDate();

    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
      age--;
    }

    ageField.value = `${age} years old`;
  };

  dob.addEventListener('change', updateAge);
  dob.addEventListener('input', updateAge);
  updateAge();
}

// ── DATE CONSTRAINTS ──────────────────────────────────────────────

function bindDateConstraints() {
  const specs = [
    { start: $('mf-contract-start'), end: $('mf-contract-end') },       // SPES
    { start: $('mf-gip-contract-start'), end: $('mf-gip-contract-end') } // GIP
  ];

  specs.forEach(pair => {
    if (!pair.start || !pair.end) return;

    const update = () => {
      if (pair.start.value) pair.end.min = pair.start.value;
      else pair.end.removeAttribute('min');
      
      if (pair.end.value) pair.start.max = pair.end.value;
      else pair.start.removeAttribute('max');
    };

    pair.start.addEventListener('change', update);
    pair.start.addEventListener('input', update);
    pair.end.addEventListener('change', update);
    pair.end.addEventListener('input', update);
    
    update();
  });
}

// ── FILE SLOTS ────────────────────────────────────────────────────

function bindFileSlots() {
  document.querySelectorAll('.mf-url-input').forEach(input => {
    const slot  = input.closest('.mf-fslot');
    if (!slot) return;
    
    // Store original text/icon for reset
    const iconEl = slot.querySelector('.mf-fslot-icon');
    const origIcon = iconEl.innerHTML;

    input.addEventListener('input', () => {
      const val = input.value.trim();
      if (!val) {
        slot.classList.remove('uploaded');
        iconEl.innerHTML = origIcon;
        return;
      }

      slot.classList.add('uploaded');
      iconEl.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
    });

    const removeBtn = slot.querySelector('.mf-remove-file');
    if (removeBtn) {
      removeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        input.value = ''; // clear url
        slot.classList.remove('uploaded');
        iconEl.innerHTML = origIcon;
      });
    }
  });
}

// ── REVIEW PANEL ──────────────────────────────────────────────────
// Logic moved to manual-review.js

// ── SUBMIT ────────────────────────────────────────────────────────
// Replace this with a real fetch() POST to your API endpoint.

function submitForm() {
  const p = (PROGRAMS[selectedSection] || []).find(x => x.val === selectedProgram);
  $('mf-success-prog').textContent = p ? p.label : selectedProgram;

  const isWhip = selectedProgram === 'whip';
  const whipProjectIdAtSubmit = isWhip ? ($('mf-h-whip-project-id')?.value || '') : '';
  const whipProjectTitleAtSubmit = isWhip ? ($('mf-whip-project-search')?.value || '') : '';

  const formEl = document.getElementById('manualEntryForm');
  const formData = new FormData(formEl);
  const programLabel = p ? p.label : selectedProgram;
  formData.append('program', programLabel);

  // Use the global window function or simulate loading if not defined
  if (typeof showLoading === 'function') showLoading();

  fetch('../../backend/import/save_manual.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const successId = data.beneficiary_id ? `Benef #${data.beneficiary_id}` : 'Record saved';
      $('mf-success-id').textContent = successId;
      lastSubmissionState = data.state;

      const addWorkerBtn = $('mf-add-whip-worker');
      const addWorkerLabel = $('mf-add-whip-worker-label');
      if (isWhip) {
        let projId = whipProjectIdAtSubmit ? parseInt(whipProjectIdAtSubmit, 10) : null;
        if (!projId && data.state && Array.isArray(data.state.insertedProjectIds) && data.state.insertedProjectIds.length) {
          projId = data.state.insertedProjectIds[data.state.insertedProjectIds.length - 1];
        }
        lastWhipProject = projId ? { id: projId, title: whipProjectTitleAtSubmit } : null;
        if (addWorkerBtn) addWorkerBtn.style.display = lastWhipProject ? 'inline-flex' : 'none';
        if (addWorkerLabel && lastWhipProject) addWorkerLabel.textContent = lastWhipProject.title;
        whipProjectsCache = null; // force a refresh so a newly-created project is searchable next time
      } else if (addWorkerBtn) {
        addWorkerBtn.style.display = 'none';
      }

      if (typeof window.showToast === 'function') {
        window.showToast('Manual entry saved successfully.', 'success');
      }
      clearDraft(); // Clear draft on successful save
      goPanel(5);
    } else {
      if (typeof window.showToast === 'function') {
        window.showToast(data.error || 'Error saving manual entry.', 'error');
      }
    }
  })
  .catch(err => {
    console.error(err);
    if (typeof window.showToast === 'function') {
      window.showToast('Network error. Please try again.', 'error');
    }
  })
  .finally(() => {
    if (typeof hideLoading === 'function') hideLoading();
  });
}

// ── WHIP: ADD ANOTHER WORKER TO THE SAME PROJECT ───────────────────

function addAnotherWhipWorker() {
  if (!lastWhipProject || selectedProgram !== 'whip') {
    resetForm();
    return;
  }

  // Clear only the person + worker fields; keep section/program/project intact.
  ['mf-lname', 'mf-fname', 'mf-mname', 'mf-dob', 'mf-age', 'mf-contact', 'mf-email',
   'mf-house', 'mf-city', 'mf-notes', 'mf-whip-pos', 'mf-date-hired'].forEach(id => {
    const el = $(id);
    if (el) el.value = '';
  });

  const districtEl = $('mf-district');
  const barangayEl = $('mf-barangay');
  if (districtEl) districtEl.value = '';
  if (barangayEl) {
    barangayEl.value = '';
    barangayEl.innerHTML = '<option value="">Select district first…</option>';
    barangayEl.disabled = true;
  }

  document.querySelectorAll('[data-group="sex"]').forEach(c => c.classList.remove('on'));
  document.querySelector('[data-group="sex"][data-val="Male"]')?.classList.add('on');
  const hSex = $('mf-h-sex');
  if (hSex) hSex.value = 'Male';

  ['mf-flag-4ps', 'mf-flag-pwd', 'mf-flag-ofw'].forEach(id => $(id)?.classList.remove('on'));
  const h4ps = $('mf-h-4ps'); if (h4ps) h4ps.value = '0';
  const hPwd = $('mf-h-pwd'); if (hPwd) hPwd.value = '0';
  const hOfw = $('mf-h-ofw'); if (hOfw) hOfw.value = '0';

  document.querySelectorAll('.mf-url-input').forEach(input => {
    input.value = '';
    input.dispatchEvent(new Event('input'));
  });

  // Re-apply the same project selection.
  const cached = (whipProjectsCache || []).find(p => String(p.project_id) === String(lastWhipProject.id));
  selectExistingWhipProject(cached || { project_id: lastWhipProject.id, project_title: lastWhipProject.title, contractor: '' });

  goPanel(1);
}

// ── UNDO ──────────────────────────────────────────────────────────

function undoSubmission() {
  if (!lastSubmissionState) {
    if (typeof window.showToast === 'function') window.showToast('No entry to undo.', 'error');
    return;
  }
  
  if (typeof showLoading === 'function') showLoading();
  
  fetch('../../backend/import/undo_manual.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ state: lastSubmissionState })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      if (typeof window.showToast === 'function') window.showToast('Entry successfully undone.', 'info');
      lastSubmissionState = null;
      resetForm();
    } else {
      if (typeof window.showToast === 'function') window.showToast(data.error || 'Failed to undo entry.', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    if (typeof window.showToast === 'function') window.showToast('Network error during undo.', 'error');
  })
  .finally(() => {
    if (typeof hideLoading === 'function') hideLoading();
  });
}

// ── RESET ─────────────────────────────────────────────────────────

function resetForm() {
  selectedSection = '';
  selectedProgram = '';
  lastSubmissionState = null;
  lastWhipProject = null;
  const addWhipWorker = $('mf-add-whip-worker');
  if (addWhipWorker) addWhipWorker.style.display = 'none';

  const secEl = $('manualSection');
  if(secEl) secEl.value = '';
  const progEl = $('manualProgram');
  if(progEl) {
    progEl.innerHTML = '<option value="">Select a section first…</option>';
    progEl.disabled = true;
  }

  const badge = $('mf-prog-badge');
  if(badge) badge.classList.add('mf-hidden');

  const districtEl = $('mf-district');
  const barangayEl = $('mf-barangay');
  if (districtEl) districtEl.value = '';
  if (barangayEl) {
    barangayEl.value = '';
    barangayEl.innerHTML = '<option value="">Select district first…</option>';
    barangayEl.disabled = true;
  }

  const form = document.getElementById('manualEntryForm');
  if (form) form.reset();
  
  document.querySelectorAll('.mf-url-input').forEach(input => {
    input.value = '';
    input.dispatchEvent(new Event('input'));
  });

  goPanel(0);
}