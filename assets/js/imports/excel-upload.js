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
const excelSection = document.getElementById('excelSection');
const excelProgram = document.getElementById('excelProgram');
const excelBrowseBtn = document.getElementById('excelBrowseBtn');

export function syncProgramSpecificFields(program = excelProgram?.value ?? '') {
    const spesCategoryWrapper = document.getElementById('spesCategoryWrapper');
    const wiirpCategoryWrapper = document.getElementById('wiirpCategoryWrapper');
    const gipCategoryWrapper = document.getElementById('gipCategoryWrapper');

    if (spesCategoryWrapper) {
        spesCategoryWrapper.classList.toggle('hidden', program !== 'SPES');
    }

    if (wiirpCategoryWrapper) {
        wiirpCategoryWrapper.classList.toggle('hidden', program !== 'Work Immersion and Internship Referral Program');
    }

    if (gipCategoryWrapper) {
        gipCategoryWrapper.classList.toggle('hidden', program !== 'Government Internship Program');
    }
}

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

    const allowed = ['.xlsx', '.xls', '.csv'];
    const ext = '.' + file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
        showToast('Please upload a .xlsx, .xls, or .csv file.', 'error');
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        showToast('File exceeds 10MB limit.', 'error');
        return;
    }

    const sectionEl = document.getElementById('excelSection');
    const section   = sectionEl.options[sectionEl.selectedIndex].text;
    const program   = document.getElementById('excelProgram').value;
    if (!program) { showToast('Please select a program first.', 'warning'); return; }

    const reader = new FileReader();
    reader.onload = function (e) {
        const data     = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array', cellDates: false });
        const sheet    = workbook.Sheets[workbook.SheetNames[0]];
        const json     = XLSX.utils.sheet_to_json(sheet, { defval: '' });

        if (json.length === 0) { showToast('The uploaded file is empty.', 'warning'); return; }

        // Header validation — case-insensitive
        const headers      = Object.keys(json[0] || {});
        const headersLower = headers.map(h => h.toLowerCase());
        const required     = programHeaders[program] ?? programHeaders['DEFAULT'] ?? [];
        const requiredLower = required.map(h => h.toLowerCase());
        const missing = required.filter(h => !headersLower.includes(h.toLowerCase()));
        const extra   = headers.filter(h => !requiredLower.includes(h.toLowerCase()));

        if (missing.length > 0) {
            showToast(`Missing required columns:\n${missing.join(', ')}`, 'error', 6000);
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
                    json.forEach(row => {
                        row._program_override = true;
                    });
                    proceedWithValidation();
                },
                // Option 2: Show preview with mismatches highlighted
                () => {
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
                    fileInput.value = '';
                    fileInfo.classList.add('hidden');
                    setProgramSelectorsLocked(false);
                    setUploadStateFromProgramSelection();
                }
            );
            return;
        }

        // Helper function to proceed with validation after mismatch handling
        function proceedWithValidation() {
            state.selectedFile = file;
            fileName.textContent = file.name;
            fileSize.textContent = formatBytes(file.size);
            fileInfo.classList.remove('hidden');
            setProgramSelectorsLocked(true);
            setUploadEnabled(false);

            // Period detection
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

            // Validation request
            document.getElementById('previewMeta').innerHTML  = '<span class="text-gray-400 animate-pulse">Validating rows…</span>';
            document.getElementById('previewBody').innerHTML  = '';
            document.getElementById('dataPreview').classList.remove('hidden');

            // Capture category values so preview validation can apply program-specific rules.
            const wiirpCategory = document.getElementById('wiirpCategory')?.value ?? '';
            const gipCategory = document.getElementById('gipCategory')?.value ?? '';
            fetch('../../backend/import/validate_preview.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ program, section, data: json, wiirpCategory, gipCategory }),
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
                        document.getElementById('dataPreview').classList.add('hidden');
                        resetPreviewPaginationState();
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Validation failed: ' + (err.message ?? 'Unknown error'), 'error');
                    document.getElementById('dataPreview').classList.add('hidden');
                    resetPreviewPaginationState();
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
