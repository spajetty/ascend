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
        // Show/hide SPES category dropdown based on selected program
        const spesCategoryWrapper = document.getElementById('spesCategoryWrapper');
        if (spesCategoryWrapper) {
            if (this.value === 'SPES') {
                spesCategoryWrapper.classList.remove('hidden');
            } else {
                spesCategoryWrapper.classList.add('hidden');
            }
        }

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

        // Show file info row
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

        fetch('../../backend/import/validate_preview.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ program, section, data: json }),
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
                    showExcelPreview(result.data, result.summary, required, extra);
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
