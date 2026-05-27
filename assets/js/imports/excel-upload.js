// File handling: dropzone events, file validation, header check,
// period detection trigger, backend validate_preview call,
// section/program cascade, and upload-zone enablement.

import { programs, programHeaders } from './config.js';
import { formatBytes } from './common.js';
import { showToast } from '../toast.js';
import { state } from './excel-state.js';
import {
    populatePeriodSelectors,
    detectPeriodFromFilename,
    detectPeriodFromRows,
    applyDetectedPeriod,
    showExcelPreview,
    resetPreviewPaginationState,
} from './excel-preview.js';
import { openProgramMismatchModal } from './excel-modals.js';
import {
    openCreateEventModal,
    openAddCompaniesModal,
    setParticipantsWarning,
    hideParticipantsWarning,
} from './job-fair-modal.js';

const WIIRP_PRIVATE_PREVIEW_HEADERS = [
    '# of hours',
    'Starting Date',
    'Est. End',
    'Office Assignment',
    'Endorsement 1',
    'Endorsement 2',
];

const WIIRP_PRIVATE_HEADER_ALIASES = {
    '# of hours': ['# of hours', 'number of hours', 'hours'],
    'Starting Date': ['starting date', 'start date', 'starting_date'],
    'Est. End': ['est. end', 'estimated end', 'est end', 'end date'],
    'Office Assignment': ['office assignment', 'office assign', 'office assginment', 'office_assignment'],
    'Endorsement 1': ['endorsement 1', 'endorsement_1'],
    'Endorsement 2': ['endorsement 2', 'endorsement_2'],
};

function resolveWiirpPrivatePreviewCols(rowKeys = []) {
    const present = new Set(rowKeys.map(k => String(k).trim().toLowerCase()));
    const resolved = [];

    for (const canonical of WIIRP_PRIVATE_PREVIEW_HEADERS) {
        const aliases = WIIRP_PRIVATE_HEADER_ALIASES[canonical] ?? [canonical.toLowerCase()];
        const matchedAlias = aliases.find(a => present.has(a.toLowerCase()));
        if (matchedAlias) {
            const originalKey = rowKeys.find(k => String(k).trim().toLowerCase() === matchedAlias.toLowerCase());
            resolved.push(originalKey || canonical);
        }
    }

    return Array.from(new Set(resolved));
}

function isKnownWiirpPrivateHeader(header) {
    const key = String(header || '').trim().toLowerCase();
    for (const aliases of Object.values(WIIRP_PRIVATE_HEADER_ALIASES)) {
        if (aliases.some(a => a.toLowerCase() === key)) return true;
    }
    return false;
}

const WIIRP_PESO_PREVIEW_HEADERS = [
    '# of hours',
    'Starting Date',
    'Est. End',
    'Office Assignment',
    'Full Name',
];

const WIIRP_PESO_HEADER_ALIASES = {
    '# of hours': ['# of hours', 'number of hours', 'hours', 'required work immersion / internship hours', 'required hours'],
    'Starting Date': ['starting date', 'start date', 'starting_date'],
    'Est. End': ['est. end', 'estimated end', 'est end', 'end date'],
    'Office Assignment': ['office assignment', 'office assign', 'office assginment', 'office_assignment'],
    'Full Name': ['full name', 'fullname', 'name'],
};

function resolveWiirpPesoPreviewCols(rowKeys = []) {
    const present = new Set(rowKeys.map(k => String(k).trim().toLowerCase()));
    const resolved = [];

    for (const canonical of WIIRP_PESO_PREVIEW_HEADERS) {
        const aliases = WIIRP_PESO_HEADER_ALIASES[canonical] ?? [canonical.toLowerCase()];
        const matchedAlias = aliases.find(a => present.has(a.toLowerCase()));
        if (matchedAlias) {
            const originalKey = rowKeys.find(k => String(k).trim().toLowerCase() === matchedAlias.toLowerCase());
            resolved.push(originalKey || canonical);
        }
    }

    return Array.from(new Set(resolved));
}

function isKnownWiirpPesoHeader(header) {
    const key = String(header || '').trim().toLowerCase();
    for (const aliases of Object.values(WIIRP_PESO_HEADER_ALIASES)) {
        if (aliases.some(a => a.toLowerCase() === key)) return true;
    }
    return false;
}

// ─── DOM refs ─────────────────────────────────────────────────────────────────
const fileInput    = document.getElementById('fileInput');
const dropZone     = document.getElementById('dropZone');
const fileInfo     = document.getElementById('fileInfo');
const fileName     = document.getElementById('fileName');
const fileSize     = document.getElementById('fileSize');
const removeFile   = document.getElementById('removeFile');
const monthSelect  = document.getElementById('importMonth');
const yearSelect   = document.getElementById('importYear');
const periodPanel  = document.getElementById('importPeriodPanel');
const periodGrid   = document.getElementById('importPeriodGrid');
const excelSection = document.getElementById('excelSection');
const excelProgram = document.getElementById('excelProgram');
const excelBrowseBtn = document.getElementById('excelBrowseBtn');

export function syncProgramSpecificFields(program = excelProgram?.value ?? '') {
    const isGip = program === 'Government Internship Program';
    const isJobFair = program === 'Job Fair';
    const spesCategoryWrapper = document.getElementById('spesCategoryWrapper');
    const wiirpCategoryWrapper = document.getElementById('wiirpCategoryWrapper');
    const gipCategoryWrapper = document.getElementById('gipCategoryWrapper');
    const jobFairEventWrapper = document.getElementById('jobFairEventWrapper');
    const durationMonthsWrapper = document.getElementById('importDurationMonthsWrapper');

    if (spesCategoryWrapper) {
        spesCategoryWrapper.classList.toggle('hidden', program !== 'SPES');
    }

    if (wiirpCategoryWrapper) {
        wiirpCategoryWrapper.classList.toggle('hidden', program !== 'Work Immersion and Internship Referral Program');
    }

    if (gipCategoryWrapper) {
        gipCategoryWrapper.classList.toggle('hidden', !isGip);
    }
    
    if (jobFairEventWrapper) {
        jobFairEventWrapper.classList.toggle('hidden', !isJobFair);
        if (isJobFair) {
            fetchJobFairEvents();
        }
    }

    if (durationMonthsWrapper) {
        durationMonthsWrapper.classList.toggle('hidden', !isGip);
    }

    if (periodGrid) {
        periodGrid.classList.toggle('md:grid-cols-3', isGip);
        periodGrid.classList.toggle('md:grid-cols-2', !isGip);
    }
}

let _allJobFairEvents = [];

function renderCustomSelectOptions(filterText = '') {
    const container = document.getElementById('jfSelectOptions');
    if (!container) return;

    if (_allJobFairEvents.length === 0) {
        container.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500 text-center">No events found.</div>`;
        return;
    }

    const lowerFilter = filterText.toLowerCase();
    const filtered = _allJobFairEvents.filter(e => {
        const label = e.venue ? `${e.venue} (${e.date_start})` : `Event (${e.date_start})`;
        return label.toLowerCase().includes(lowerFilter);
    });

    if (filtered.length === 0) {
        container.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500 text-center">No matching events.</div>`;
        return;
    }

    container.innerHTML = filtered.map(e => {
        const label = e.venue ? `${e.venue} (${e.date_start})` : `Event (${e.date_start})`;
        const isSelected = state.selectedJobFairEvent === String(e.jobfairevent_id);
        // Basic HTML escaping for safety
        const safeLabel = label.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        return `
            <div class="jf-option cursor-pointer px-4 py-2 text-sm hover:bg-blue-50 rounded-lg ${isSelected ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700'}"
                 data-value="${e.jobfairevent_id}" data-label="${safeLabel}">
                ${safeLabel}
            </div>
        `;
    }).join('');

    container.querySelectorAll('.jf-option').forEach(opt => {
        opt.addEventListener('click', async () => {
            const val = opt.getAttribute('data-value');
            const lbl = opt.getAttribute('data-label');
            
            document.getElementById('jobFairEvent').value = val;
            document.getElementById('jfSelectSearch').value = lbl;
            document.getElementById('jfSelectDropdown').classList.add('hidden');
            
            state.selectedJobFairEvent = val;
            const hasParticipants = await _checkEventParticipants(val, lbl);
            
            const selectedProgram = document.getElementById('excelProgram')?.value ?? '';
            if (selectedProgram === 'Job Fair' && state.selectedFile && hasParticipants) {
                handleFile(state.selectedFile);
            }
        });
    });
}

// ─── Load Job Fair events (filtered by month/year, or all if empty) ────────────
export function fetchJobFairEvents() {
    const month = document.getElementById('importMonth')?.value || '';
    const year = document.getElementById('importYear')?.value || '';
    const jfSelectSearch = document.getElementById('jfSelectSearch');
    
    if (!jfSelectSearch) return;

    jfSelectSearch.value = 'Loading events…';
    jfSelectSearch.disabled = true;

    fetch(`../../backend/import/get_job_fair_events.php?month=${month}&year=${year}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error(data.error ?? 'Error loading events');
            jfSelectSearch.disabled = false;
            
            _allJobFairEvents = data.events;
            renderCustomSelectOptions();
            
            const prev = state.selectedJobFairEvent;
            if (prev) {
                const found = _allJobFairEvents.find(e => String(e.jobfairevent_id) === String(prev));
                if (found) {
                    const lbl = found.venue ? `${found.venue} (${found.date_start})` : `Event (${found.date_start})`;
                    document.getElementById('jobFairEvent').value = prev;
                    jfSelectSearch.value = lbl;
                    _checkEventParticipants(prev, lbl);
                } else {
                    state.selectedJobFairEvent = ''; 
                    jfSelectSearch.value = '';
                    document.getElementById('jobFairEvent').value = '';
                }
            } else {
                jfSelectSearch.value = '';
                document.getElementById('jobFairEvent').value = '';
            }
        })
        .catch(() => {
            jfSelectSearch.disabled = false;
            jfSelectSearch.value = 'Error loading events';
            _allJobFairEvents = [];
            renderCustomSelectOptions();
        });
}

if (monthSelect) monthSelect.addEventListener('change', () => {
    if (document.getElementById('excelProgram')?.value === 'Job Fair') fetchJobFairEvents();
});
if (yearSelect) yearSelect.addEventListener('change', () => {
    if (document.getElementById('excelProgram')?.value === 'Job Fair') fetchJobFairEvents();
});

// ─── Participants check ───────────────────────────────────────────────────────
async function _checkEventParticipants(eventId, eventLabel) {
    if (!eventId || eventId === '__create_new__') {
        hideParticipantsWarning();
        _setImportButtonGated(false);
        return true;
    }

    try {
        const res = await fetch(`../../backend/import/get_event_participants.php?event_id=${eventId}`);
        const data = await res.json();
        if (!data.success) return false;

        if (data.participants.length === 0) {
            setParticipantsWarning(eventId, eventLabel);
            _setImportButtonGated(true);
            return false;
        }

        hideParticipantsWarning();
        _setImportButtonGated(false);
        // Cache for confirmation panel display
        state.currentEventParticipants = data.participants;
        return true;
    } catch (_) {
        return false;
    }
}

function _setImportButtonGated(gated) {
    // Disable/re-enable the Confirm & Import button
    const confirmBtn = document.getElementById('confirmImport');
    if (!confirmBtn) return;
    if (gated) {
        confirmBtn.disabled = true;
        confirmBtn.title = 'Add at least one company participant to this event before importing.';
    } else {
        confirmBtn.disabled = false;
        confirmBtn.title = '';
    }
}

function applyJobFairMismatchMode(rows, programColumnKey, program, mode) {
    if (!mode) return;

    rows.forEach((row) => {
        const excelProgram = programColumnKey ? String(row[programColumnKey] || '').trim() : '';
        if (mode === 'override') {
            row._program_override = true;
        } else if (mode === 'preview' && excelProgram && excelProgram !== program) {
            row._program_mismatch = true;
            row._excel_program = excelProgram;
            row._program_override = true;
        }
    });
}

// ─── Custom Job Fair Dropdown Logic ──────────────────────────────────────────
const jfSelectToggle = document.getElementById('jfSelectToggle');
const jfSelectDropdown = document.getElementById('jfSelectDropdown');
const jfSelectFilter = document.getElementById('jfSelectFilter');
if (jfSelectToggle && jfSelectDropdown) {
    jfSelectToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        if (document.getElementById('jfSelectSearch').disabled) return;

        // Toggle visibility first so we can measure size when visible
        const willOpen = jfSelectDropdown.classList.contains('hidden');
        if (!willOpen) {
            jfSelectDropdown.classList.add('hidden');
            // clear any inline positioning styles
            jfSelectDropdown.style.top = '';
            jfSelectDropdown.style.bottom = '';
            jfSelectDropdown.style.marginTop = '';
            jfSelectDropdown.style.marginBottom = '';
            return;
        }

        // Open dropdown then compute positioning to decide up/down
        jfSelectDropdown.classList.remove('hidden');
        jfSelectFilter.value = '';
        renderCustomSelectOptions();

        // Allow layout to update then measure
        requestAnimationFrame(() => {
            const rect = jfSelectToggle.getBoundingClientRect();
            const ddHeight = jfSelectDropdown.offsetHeight || jfSelectDropdown.scrollHeight || 200;
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;

            // If not enough space below and more space above, open upwards
            if (spaceBelow < ddHeight + 8 && spaceAbove > spaceBelow) {
                jfSelectDropdown.style.top = 'auto';
                jfSelectDropdown.style.bottom = 'calc(100% + 0.25rem)';
                jfSelectDropdown.style.marginTop = '';
                jfSelectDropdown.style.marginBottom = '0.25rem';
            } else {
                jfSelectDropdown.style.top = '';
                jfSelectDropdown.style.bottom = '';
                jfSelectDropdown.style.marginTop = '0.25rem';
                jfSelectDropdown.style.marginBottom = '';
            }

            jfSelectFilter.focus();
        });
    });

    jfSelectFilter.addEventListener('input', (e) => {
        renderCustomSelectOptions(e.target.value);
    });

    document.addEventListener('click', (e) => {
        if (!jfSelectToggle.contains(e.target) && !jfSelectDropdown.contains(e.target)) {
            jfSelectDropdown.classList.add('hidden');
            // cleanup inline styles when closed
            jfSelectDropdown.style.top = '';
            jfSelectDropdown.style.bottom = '';
            jfSelectDropdown.style.marginTop = '';
            jfSelectDropdown.style.marginBottom = '';
        }
    });
}

// ─── Standalone "Create New Event" button ────────────────────────────────────
const jfCreateStandaloneBtn = document.getElementById('jfCreateStandaloneBtn');
if (jfCreateStandaloneBtn) {
    jfCreateStandaloneBtn.addEventListener('click', () => {
        const importedCompanies = getImportedJobFairCompanies();
        openCreateEventModal(({ eventId, eventDate, participants, companyMapping }) => {
            state.selectedJobFairEvent = String(eventId);
            hideParticipantsWarning();
            _setImportButtonGated(false);
            state.currentEventParticipants = participants ?? [];
            if (companyMapping) state.jobFairCompanyMapping = companyMapping;

            // Update month/year dropdowns to match the newly created event's date
            if (eventDate) {
                const d = new Date(eventDate);
                if (!isNaN(d.getTime())) {
                    const m = d.toLocaleString('en-US', { month: 'long' }); // e.g., 'January'
                    const y = String(d.getFullYear());
                    const monthSelect = document.getElementById('importMonth');
                    const yearSelect = document.getElementById('importYear');
                    if (monthSelect) monthSelect.value = m;
                    if (yearSelect) yearSelect.value = y;
                }
            }

            // Reload list so new event appears (will auto-select state.selectedJobFairEvent)
            fetchJobFairEvents();

            // If file already loaded, re-validate
            const selectedProgram = document.getElementById('excelProgram')?.value ?? '';
            if (selectedProgram === 'Job Fair' && state.selectedFile) {
                handleFile(state.selectedFile);
            }
        }, { importedCompanies });
    });
}

function getImportedJobFairCompanies() {
    const rows = (Array.isArray(state.parsedExcelData) && state.parsedExcelData.length > 0)
        ? state.parsedExcelData
        : (Array.isArray(state.excelFileData) ? state.excelFileData : []);
    const unique = [];
    const seen = new Set();

    const companyKeys = ['suggested_company_name', 'company', 'companyname', 'employer'];

    for (const row of rows) {
        if (!row) continue;
        
        // Find the first matching key (case-insensitive)
        let name = '';
        for (const [key, value] of Object.entries(row)) {
            if (companyKeys.includes(key.toLowerCase().replace(/\s+/g, ''))) {
                name = String(value).trim();
                if (name) break;
            }
        }

        if (!name) continue;

        const key = name.toLowerCase().replace(/\s+/g, ' ');
        if (seen.has(key)) continue;
        seen.add(key);
        unique.push(name);
    }

    return unique;
}

// Handle the custom event fired when participants are added via the B2 warning link
document.addEventListener('jfParticipantsResolved', async (e) => {
    const { eventId } = e.detail ?? {};
    if (eventId) {
        const hasParticipants = await _checkEventParticipants(String(eventId), '');

        // Re-run validation so preview appears once participants are added.
        const selectedProgram = document.getElementById('excelProgram')?.value ?? '';
        if (selectedProgram === 'Job Fair' && state.selectedFile && hasParticipants) {
            handleFile(state.selectedFile);
        }
    }
});

// Show notice when WIIRP category changes to Private after a preview has been rendered
const wiirpCategoryEl = document.getElementById('wiirpCategory');
if (wiirpCategoryEl) {
    wiirpCategoryEl.addEventListener('change', function () {
        const val = (this.value || '').trim().toLowerCase();
        const previewMeta = document.getElementById('previewMeta');
        if (!previewMeta) return;

        // Remove existing private notice if present
        const existing = document.getElementById('wiirpPrivateNotice');
        if (existing) existing.remove();

        if (val === 'private') {
            // Only show notice if there is an active preview
            const dataPreview = document.getElementById('dataPreview');
            if (dataPreview && !dataPreview.classList.contains('hidden')) {
                const notice = document.createElement('div');
                notice.id = 'wiirpPrivateNotice';
                notice.className = 'mt-3 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded text-sm';
                notice.innerHTML = '<strong>Note:</strong> You selected the <em>Private</em> WIIRP category. Required private columns are office assignment and endorsements. Hours and start/end dates are optional.';
                previewMeta.insertAdjacentElement('afterbegin', notice);
            }
        }

        // If a WIIRP file is already loaded, re-run validation so preview columns match current category
        const selectedProgram = document.getElementById('excelProgram')?.value ?? '';
        if (selectedProgram === 'Work Immersion and Internship Referral Program' && state.selectedFile) {
            handleFile(state.selectedFile);
        }
    });
}

// ─── Upload zone toggle ───────────────────────────────────────────────────────
export function setProgramSelectorsLocked(locked) {
    if (!excelSection || !excelProgram) return;
    if (locked) {
        excelSection.disabled = true;
        excelProgram.disabled = true;
    } else {
        excelSection.disabled = false;
        excelProgram.disabled = !excelSection.value;
    }
}

function setUploadEnabled(enabled) {
    if (!dropZone || !excelBrowseBtn) return;
    if (enabled) {
        dropZone.classList.remove('opacity-40', 'pointer-events-none');
        excelBrowseBtn.disabled = false;
    } else {
        dropZone.classList.add('opacity-40', 'pointer-events-none');
        excelBrowseBtn.disabled = true;
    }
}

export function setUploadStateFromProgramSelection() {
    setUploadEnabled(Boolean(excelProgram?.value));
}

// ─── Section → Program cascade ────────────────────────────────────────────────
if (excelSection && excelProgram) {
    excelSection.addEventListener('change', function () {
        const val = this.value;
        if (val && programs[val]) {
            excelProgram.innerHTML = '<option value="">Select a program…</option>' +
                programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
            excelProgram.disabled = false;
        } else {
            excelProgram.innerHTML = '<option value="">Select a section first…</option>';
            excelProgram.disabled = true;
        }
        setUploadEnabled(false);
    });

    excelProgram.addEventListener('change', function () {
        syncProgramSpecificFields(this.value);

        // Store program choice for later use in file handler
        // Period panel visibility will be controlled by applyDetectedPeriod after file selection
        state.selectedProgram = this.value;

        if (state.selectedFile) {
            setUploadEnabled(false);
            return;
        }

        setUploadStateFromProgramSelection();
    });
}

// ─── File handler ─────────────────────────────────────────────────────────────
export function handleFile(file) {
    if (!file) return;

    const isSameSelectedFile = state.selectedFile
        && state.selectedFile.name === file.name
        && state.selectedFile.size === file.size
        && state.selectedFile.lastModified === file.lastModified;

    if (!isSameSelectedFile) {
        state.jobFairMismatchMode = '';
    }

    const resetFileSelectionForRetry = () => {
        state.selectedFile = null;
        state.excelFileData = null;
        if (fileInput) fileInput.value = '';
    };

    const allowed = ['.xlsx', '.xls', '.csv'];
    const ext = '.' + file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
        showToast('Please upload a .xlsx, .xls, or .csv file.', 'error');
        resetFileSelectionForRetry();
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        showToast('File exceeds 10MB limit.', 'error');
        resetFileSelectionForRetry();
        return;
    }

    const sectionEl = document.getElementById('excelSection');
    const section   = sectionEl.options[sectionEl.selectedIndex].text;
    const program   = document.getElementById('excelProgram').value;
    if (!program) {
        showToast('Please select a program first.', 'warning');
        resetFileSelectionForRetry();
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        const data     = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array', cellDates: false });
        const sheet    = workbook.Sheets[workbook.SheetNames[0]];
        const json     = XLSX.utils.sheet_to_json(sheet, { defval: '' });

        if (state.jobFairCompanyMapping && Object.keys(state.jobFairCompanyMapping).length > 0) {
            const companyKeys = ['suggested_company_name', 'company', 'companyname', 'employer'];
            for (const row of json) {
                for (const [key, value] of Object.entries(row)) {
                    if (companyKeys.includes(key.toLowerCase().replace(/\s+/g, ''))) {
                        const originalName = String(value).trim();
                        if (state.jobFairCompanyMapping[originalName]) {
                            row[key] = state.jobFairCompanyMapping[originalName];
                        }
                    }
                }
            }
        }

        if (json.length === 0) {
            showToast('The uploaded file is empty.', 'warning');
            resetFileSelectionForRetry();
            return;
        }

        // Header validation — case-insensitive
        const headers      = Object.keys(json[0] || {});
        const headersLower = headers.map(h => h.toLowerCase());
        const required     = programHeaders[program] ?? programHeaders['DEFAULT'] ?? [];
        const requiredLower = required.map(h => h.toLowerCase());
        const missing = required.filter(h => !headersLower.includes(h.toLowerCase()));
        const extra   = headers.filter(h => !requiredLower.includes(h.toLowerCase()));

        if (missing.length > 0) {
            showToast(`Missing required columns:\n${missing.join(', ')}`, 'error', 6000);
            resetFileSelectionForRetry();
            return;
        }

        // ─── Program mismatch detection ───────────────────────────────────────────
        const programColumnCandidates = ['Program', 'program', 'PROGRAM'];
        const programColumnKey = Object.keys(json[0] || {}).find(k =>
            programColumnCandidates.some(cand => String(k).toLowerCase() === cand.toLowerCase())
        );

        let hasProgramMismatch = false;
        let programMismatches = [];
        
        if (programColumnKey) {
            json.forEach((row, idx) => {
                const excelProgram = (String(row[programColumnKey] || '').trim());
                if (excelProgram && excelProgram !== program) {
                    hasProgramMismatch = true;
                    programMismatches.push({ rowIndex: idx, excelProgram });
                }
            });
        }

        if (hasProgramMismatch) {
            if (program === 'Job Fair' && state.jobFairMismatchMode) {
                state.excelFileData = json;
                state.selectedFile = file;
                fileName.textContent = file.name;
                fileSize.textContent = formatBytes(file.size);
                fileInfo.classList.remove('hidden');
                setProgramSelectorsLocked(true);
                setUploadEnabled(false);

                applyJobFairMismatchMode(json, programColumnKey, program, state.jobFairMismatchMode);
                proceedWithValidation();
                return;
            }

            // Hard stop: show program mismatch modal
            state.excelFileData = json;
            state.selectedFile = file;
            fileName.textContent = file.name;
            fileSize.textContent = formatBytes(file.size);
            fileInfo.classList.remove('hidden');
            setProgramSelectorsLocked(true);
            setUploadEnabled(false);

            openProgramMismatchModal(program, programMismatches,
                // Option 1: Use selected program for all rows
                () => {
                    state.jobFairMismatchMode = 'override';
                    json.forEach(row => {
                        row._program_override = true;
                    });
                    proceedWithValidation();
                },
                // Option 2: Show preview with mismatches highlighted
                () => {
                    state.jobFairMismatchMode = 'preview';
                    json.forEach((row, idx) => {
                        const excelProgram = (String(row[programColumnKey] || '').trim());
                        if (excelProgram && excelProgram !== program) {
                            row._program_mismatch = true;
                            row._excel_program = excelProgram;
                        }
                    });
                    proceedWithValidation();
                },
                // Option 3: Cancel
                () => {
                    showToast('Import cancelled.', 'info');
                    state.selectedFile = null;
                    state.excelFileData = null;
                    state.jobFairMismatchMode = '';
                    fileInput.value = '';
                    fileInfo.classList.add('hidden');
                    setProgramSelectorsLocked(false);
                    setUploadStateFromProgramSelection();
                }
            );
            return;
        }

        // Helper function to proceed with validation after mismatch handling
        async function proceedWithValidation() {
            state.selectedFile = file;
            fileName.textContent = file.name;
            fileSize.textContent = formatBytes(file.size);
            fileInfo.classList.remove('hidden');
            setProgramSelectorsLocked(true);
            setUploadEnabled(false);

            // Period detection — only on first load of this file, not when re-validating
            // after the user changes month/year or selects a job fair event.
            if (!isSameSelectedFile) {
                const byName    = detectPeriodFromFilename(file.name);
                const byContent = detectPeriodFromRows(json);
                state.detectedPeriod = byName.confidence === 'high' ? byName : {
                    ...byContent,
                    month:      byName.month || byContent.month,
                    year:       byName.year  || byContent.year,
                    confidence: (byName.month || byContent.month) && (byName.year || byContent.year) ? 'medium' : 'low',
                    source:     byName.month || byName.year ? 'filename' : 'content',
                };
                const isAccreditation = program === 'Employers Accreditation';
                const isSchools = program === 'Schools';
                applyDetectedPeriod(state.detectedPeriod, {
                    hideMonth: isAccreditation || isSchools,
                    hideYear: isSchools,
                });

                if (program === 'Job Fair' && monthSelect?.value && yearSelect?.value) {
                    fetchJobFairEvents();
                }
            }

            // Capture category values so preview validation can apply program-specific rules.
            const wiirpCategory = document.getElementById('wiirpCategory')?.value ?? '';
            const gipCategory = document.getElementById('gipCategory')?.value ?? '';
            const jobFairEvent = (document.getElementById('jobFairEvent')?.value || state.selectedJobFairEvent || '').trim();

            // Require selecting a real event for Job Fair uploads.
            if (program === 'Job Fair' && (!jobFairEvent || jobFairEvent === '')) {
                showToast('Please select a Job Fair event from the dropdown before validating.', 'warning');
                return;
            }

            // Prevent first-load race: wait for participants check before showing preview.
            if (program === 'Job Fair') {
                const selectedEventLabel = document.getElementById('jfSelectSearch')?.value ?? '';
                const hasParticipants = await _checkEventParticipants(jobFairEvent, selectedEventLabel);
                if (!hasParticipants) {
                    document.getElementById('previewMeta').innerHTML = '';
                    document.getElementById('previewBody').innerHTML = '';
                    document.getElementById('dataPreview').classList.add('hidden');
                    resetPreviewPaginationState();
                    return;
                }
            }

            // Validation request
            // If Job Fair event has no registered companies, do not show preview
            if (program === 'Job Fair') {
                const noPartWarn = document.getElementById('jfNoParticipantsWarning');
                if (noPartWarn && !noPartWarn.classList.contains('hidden')) {
                    document.getElementById('previewMeta').innerHTML  = '';
                    document.getElementById('previewBody').innerHTML  = '';
                    document.getElementById('dataPreview').classList.add('hidden');
                    resetPreviewPaginationState();
                    return;
                }
            }

            document.getElementById('previewMeta').innerHTML  = '<span class="text-gray-400 animate-pulse">Validating rows…</span>';
            document.getElementById('previewBody').innerHTML  = '';
            document.getElementById('dataPreview').classList.remove('hidden');

            const browseBtn = document.getElementById('excelBrowseBtn');
            if (browseBtn) browseBtn.disabled = true;

            fetch('../../backend/import/validate_preview.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({
                    program,
                    section,
                    data: json,
                    wiirpCategory,
                    gipCategory,
                    jobFairEvent,
                    importYear: yearSelect?.value ?? '',
                }),
            })
                .then(async res => {
                    const raw = await res.text();
                    let result;
                    try {
                        result = JSON.parse(raw);
                    } catch {
                        const snippet = (raw || '').replace(/\s+/g, ' ').trim().slice(0, 180);
                        throw new Error(`Unexpected validation response (HTTP ${res.status}). ${snippet ? `Details: ${snippet}` : ''}`.trim());
                    }
                    if (!res.ok) throw new Error(result.error ?? `Validation failed (HTTP ${res.status}).`);
                    return result;
                })
                .then(result => {
                    if (result.success) {
                        state.parsedExcelData = result.data;
                        state.unknownEmployers = Array.isArray(result.unknownEmployers) ? result.unknownEmployers : [];
                        const firstPreviewRowKeys = Object.keys((result.data && result.data[0]) || {});
                        const resolvedPrivateCols = resolveWiirpPrivatePreviewCols(firstPreviewRowKeys);
                        const resolvedPesoCols = resolveWiirpPesoPreviewCols(firstPreviewRowKeys);

                        // For WIIRP uploads, include category-specific preview columns.
                        let previewCols = required;
                        if (program === 'Work Immersion and Internship Referral Program') {
                            const cat = (wiirpCategory || '').toLowerCase();
                            if (cat === 'private') {
                                previewCols = Array.from(new Set([...(required ?? []), ...resolvedPrivateCols]));
                            } else if (cat === 'peso-assigned') {
                                previewCols = Array.from(new Set([...(required ?? []), ...resolvedPesoCols]));
                            }
                        }

                        // Filter out category-specific headers from the "extra columns" list so they aren't shown as unmapped.
                        let extraColsForPreview = extra;
                        if (program === 'Work Immersion and Internship Referral Program') {
                            const cat = (wiirpCategory || '').toLowerCase();
                            if (cat === 'private') {
                                extraColsForPreview = (extra ?? []).filter(col => !isKnownWiirpPrivateHeader(col));
                            } else if (cat === 'peso-assigned') {
                                extraColsForPreview = (extra ?? []).filter(col => !isKnownWiirpPesoHeader(col));
                            }
                        }
                        showExcelPreview(result.data, result.summary, previewCols, extraColsForPreview);
                    } else {
                        showToast('Validation error: ' + (result.error ?? 'Unknown error'), 'error');
                        state.parsedExcelData = [];
                        state.unknownEmployers = [];
                        document.getElementById('dataPreview').classList.add('hidden');
                        resetPreviewPaginationState();
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Validation failed: ' + (err.message ?? 'Unknown error'), 'error');
                    state.parsedExcelData = [];
                    state.unknownEmployers = [];
                    document.getElementById('dataPreview').classList.add('hidden');
                    resetPreviewPaginationState();
                })
                .finally(() => {
                    if (browseBtn) browseBtn.disabled = false;
                });
        }

        // Normal path: no program mismatch, proceed directly
        proceedWithValidation();
    };
    reader.readAsArrayBuffer(file);
}

// ─── Dropzone events ──────────────────────────────────────────────────────────
if (fileInput) fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));

if (dropZone) {
    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-50/30');
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-400', 'bg-blue-50/30');
    });
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50/30');
        handleFile(e.dataTransfer.files[0]);
    });
}

// ─── Remove file ──────────────────────────────────────────────────────────────
if (removeFile) {
    removeFile.addEventListener('click', e => {
        e.stopPropagation();
        state.selectedFile = null;
        fileInput.value    = '';
        fileInfo.classList.add('hidden');
        if (periodPanel)  periodPanel.classList.add('hidden');
        if (monthSelect)  monthSelect.value = '';
        if (yearSelect)   yearSelect.value  = '';
        const durationSelect = document.getElementById('importDurationMonths');
        if (durationSelect)  durationSelect.value  = '3';
        // Restore month wrapper in case it was hidden for Employers Accreditation
        const monthWrapper = document.getElementById('importMonthWrapper');
        if (monthWrapper) monthWrapper.classList.remove('hidden');
        const yearWrapper = document.getElementById('importYear')?.closest('div');
        if (yearWrapper) yearWrapper.classList.remove('hidden');
        document.getElementById('dataPreview').classList.add('hidden');
        setProgramSelectorsLocked(false);
        resetPreviewPaginationState();
        setUploadStateFromProgramSelection();
    });
}

// ─── Initialise period selectors on load ─────────────────────────────────────
populatePeriodSelectors();
