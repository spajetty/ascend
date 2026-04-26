import { programs, programHeaders } from './config.js';
import { formatBytes, previewTableRows, previewTableHeaders } from './common.js';
import { showToast } from '../toast.js';

// ─── EXCEL TAB ────────────────────────────────────────────────────────────────
const fileInput = document.getElementById('fileInput');
const dropZone = document.getElementById('dropZone');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const removeFile = document.getElementById('removeFile');

let parsedExcelData = [];
let selectedFile = null;
let detectedPeriod = { month: '', year: '', confidence: 'low', source: 'none' };

const monthSelect = document.getElementById('importMonth');
const yearSelect = document.getElementById('importYear');
const periodPanel = document.getElementById('importPeriodPanel');
const periodSuggestionText = document.getElementById('periodSuggestionText');

const MONTHS = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

function populatePeriodSelectors() {
    if (!monthSelect || !yearSelect) return;

    monthSelect.innerHTML = '<option value="">Select month...</option>' +
        MONTHS.map(m => `<option value="${m}">${m}</option>`).join('');

    // Keep the list tied to the live system year so it stays current over time.
    const currentYear = new Date().getFullYear();
    let yearOptions = '<option value="">Select year...</option>';
    for (let y = currentYear; y >= currentYear - 8; y--) {
        yearOptions += `<option value="${y}">${y}</option>`;
    }
    yearSelect.innerHTML = yearOptions;
}

function detectPeriodFromFilename(fileName) {
    const lowered = (fileName || '').toLowerCase();
    const monthMap = {
        january: 'January', jan: 'January',
        february: 'February', feb: 'February',
        march: 'March', mar: 'March',
        april: 'April', apr: 'April',
        may: 'May',
        june: 'June', jun: 'June',
        july: 'July', jul: 'July',
        august: 'August', aug: 'August',
        september: 'September', sep: 'September', sept: 'September',
        october: 'October', oct: 'October',
        november: 'November', nov: 'November',
        december: 'December', dec: 'December',
    };

    let foundMonth = '';
    for (const [token, month] of Object.entries(monthMap)) {
        if (new RegExp(`(^|[^a-z])${token}([^a-z]|$)`, 'i').test(lowered)) {
            foundMonth = month;
            break;
        }
    }

    const yearMatch = lowered.match(/(?:19|20)\d{2}/);
    const foundYear = yearMatch ? yearMatch[0] : '';

    return {
        month: foundMonth,
        year: foundYear,
        confidence: (foundMonth && foundYear) ? 'high' : ((foundMonth || foundYear) ? 'medium' : 'low'),
        source: 'filename',
    };
}

function detectPeriodFromRows(rows) {
    if (!Array.isArray(rows) || rows.length === 0) {
        return { month: '', year: '', confidence: 'low', source: 'content' };
    }

    const sample = rows.slice(0, 20);
    const monthKeys = ['Month', 'month', 'MONTH'];
    const yearKeys = ['Year', 'year', 'YEAR'];
    const dateKeys = ['Date', 'date', 'DATE', 'Transaction Date', 'Report Date'];

    let month = '';
    let year = '';

    for (const r of sample) {
        if (!month) {
            for (const k of monthKeys) {
                if (r[k]) {
                    const m = String(r[k]).trim();
                    const normalized = MONTHS.find(x => x.toLowerCase() === m.toLowerCase());
                    if (normalized) month = normalized;
                }
            }
        }

        if (!year) {
            for (const k of yearKeys) {
                if (r[k] && /^(19|20)\d{2}$/.test(String(r[k]).trim())) {
                    year = String(r[k]).trim();
                }
            }
        }

        if ((!month || !year) && !dateKeys.every(k => !r[k])) {
            for (const k of dateKeys) {
                const raw = r[k];
                if (!raw) continue;
                const parsed = new Date(raw);
                if (!Number.isNaN(parsed.getTime())) {
                    if (!month) month = MONTHS[parsed.getMonth()];
                    if (!year) year = String(parsed.getFullYear());
                }
            }
        }

        if (month && year) break;
    }

    return {
        month,
        year,
        confidence: (month && year) ? 'medium' : ((month || year) ? 'low' : 'low'),
        source: 'content',
    };
}

function applyDetectedPeriod(period) {
    if (!monthSelect || !yearSelect || !periodPanel || !periodSuggestionText) return;

    periodPanel.classList.remove('hidden');

    if (period.month) monthSelect.value = period.month;
    if (period.year) yearSelect.value = period.year;

    if (period.month && period.year) {
        const sourceLabel = period.source === 'filename' ? 'filename' : 'file contents';
        periodSuggestionText.textContent = `Detected from ${sourceLabel}: ${period.month} ${period.year}`;
    } else {
        periodSuggestionText.textContent = 'Could not confidently detect period. Please select month and year before import.';
    }
}

populatePeriodSelectors();

// ─── Dropdown Cascade & UI Enablement ──────────────────────────────────────────
const excelSection = document.getElementById('excelSection');
const excelProgram = document.getElementById('excelProgram');
const excelBrowseBtn = document.getElementById('excelBrowseBtn');

// Section → populate programs
if (excelSection && excelProgram) {
    // trigger when section is changed
    excelSection.addEventListener('change', function () {
        // get the current selected value
        const val = this.value;
        if (val && typeof programs !== 'undefined' && programs[val]) {
            excelProgram.innerHTML = '<option value="">Select a program…</option>' +
                programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
            excelProgram.disabled = false;
        } else {
            excelProgram.innerHTML = '<option value="">Select a section first…</option>';
            excelProgram.disabled = true;
        }

        // Reset dropzone state
        if (dropZone && excelBrowseBtn) {
            dropZone.classList.add('opacity-40', 'pointer-events-none');
            excelBrowseBtn.disabled = true;
        }
    });

    excelProgram.addEventListener('change', function () {
        if (dropZone && excelBrowseBtn) {
            if (this.value) {
                dropZone.classList.remove('opacity-40', 'pointer-events-none');
                excelBrowseBtn.disabled = false;
            } else {
                dropZone.classList.add('opacity-40', 'pointer-events-none');
                excelBrowseBtn.disabled = true;
            }
        }
    });
}

// When file is selected
function handleFile(file) {
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
    const section = sectionEl.options[sectionEl.selectedIndex].text;
    const program = document.getElementById('excelProgram').value;

    if (!program) { showToast('Please select a program first.', 'warning'); return; }

    const reader = new FileReader();
    reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array', cellDates: false });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        const json = XLSX.utils.sheet_to_json(sheet, { defval: '' });

        if (json.length === 0) { showToast('The uploaded file is empty.', 'warning'); return; }

        // ── Header validation ────────────────────────────────────────────────
        const headers = Object.keys(json[0] || {});
        // Note: programHeaders is assumed to be defined elsewhere in the frontend
        const required = (typeof programHeaders !== 'undefined' && programHeaders[program]) ? programHeaders[program] : (typeof programHeaders !== 'undefined' ? programHeaders['DEFAULT'] : []);
        
        // Find missing and extra columns
        const missing = required.filter(h => !headers.includes(h));
        const extra = headers.filter(h => !required.includes(h));

        if (missing.length > 0) {
            showToast(`Missing required columns:\n${missing.join(', ')}`, 'error', 6000);
            return; // Hard error, block preview completely
        }

        // ── Show file info row ───────────────────────────────────────────────
        selectedFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        fileInfo.classList.remove('hidden');

        const byName = detectPeriodFromFilename(file.name);
        const byContent = detectPeriodFromRows(json);
        detectedPeriod = byName.confidence === 'high' ? byName : {
            ...byContent,
            month: byName.month || byContent.month,
            year: byName.year || byContent.year,
            confidence: (byName.month || byContent.month) && (byName.year || byContent.year) ? 'medium' : 'low',
            source: byName.month || byName.year ? 'filename' : 'content',
        };
        applyDetectedPeriod(detectedPeriod);

        // ── Send to backend for duplicate check ──────────────────────────────
        document.getElementById('previewMeta').innerHTML =
            '<span class="text-gray-400 animate-pulse">Validating rows…</span>';
        document.getElementById('previewBody').innerHTML = '';
        document.getElementById('dataPreview').classList.remove('hidden');

        fetch('../../backend/import/validate_preview.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ program, section, data: json }),
        })
            .then(async (res) => {
                const raw = await res.text();
                let result;

                try {
                    result = JSON.parse(raw);
                } catch {
                    const snippet = (raw || '').replace(/\s+/g, ' ').trim().slice(0, 180);
                    throw new Error(`Unexpected validation response (HTTP ${res.status}). ${snippet ? `Details: ${snippet}` : ''}`.trim());
                }

                if (!res.ok) {
                    throw new Error(result.error ?? `Validation failed (HTTP ${res.status}).`);
                }

                return result;
            })
            .then(result => {
                if (result.success) {
                    parsedExcelData = result.data;
                    showExcelPreview(result.data, result.summary, required, extra);
                } else {
                    showToast('Validation error: ' + (result.error ?? 'Unknown error'), 'error');
                    document.getElementById('dataPreview').classList.add('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Validation failed: ' + (err.message ?? 'Unknown error'), 'error');
                document.getElementById('dataPreview').classList.add('hidden');
            });
    };
    reader.readAsArrayBuffer(file);
}

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

if (removeFile) {
    removeFile.addEventListener('click', e => {
        e.stopPropagation();
        selectedFile = null;
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        if (periodPanel) periodPanel.classList.add('hidden');
        if (monthSelect) monthSelect.value = '';
        if (yearSelect) yearSelect.value = '';
        document.getElementById('dataPreview').classList.add('hidden');
    });
}

// Receives the validated rows + summary object from validate_preview.php
function showExcelPreview(rows, summary, requiredCols, extraCols) {
    const total = summary?.total ?? rows.length;
    const newCount = summary?.new ?? rows.length;
    const errCount = summary?.invalid ?? 0;
    const dupCount = summary?.duplicate ?? 0;

    let summaryHtml = `
        <div class="flex items-center gap-4 text-sm mb-2">
            <div class="px-3 py-1 bg-gray-100 rounded text-gray-700"><b>${total}</b> total rows</div>
            <div class="px-3 py-1 bg-emerald-100 rounded text-emerald-800"><b>${newCount}</b> valid rows</div>
    `;

    if (errCount > 0) {
        summaryHtml += `<div class="px-3 py-1 bg-red-100 rounded text-red-800"><b>${errCount}</b> rows with errors</div>`;
    }
    if (dupCount > 0) {
        summaryHtml += `<div class="px-3 py-1 bg-yellow-100 rounded text-yellow-800"><b>${dupCount}</b> duplicate rows</div>`;
    }
    if (extraCols && extraCols.length > 0) {
        summaryHtml += `<div class="px-3 py-1 bg-orange-100 rounded text-orange-800"><b>${extraCols.length}</b> unmapped col(s)</div>`;
    }
    
    summaryHtml += `</div>`;

    let metaHtml = summaryHtml;

    const selectedMonth = monthSelect?.value ?? '';
    const selectedYear = yearSelect?.value ?? '';
    if (selectedMonth && selectedYear) {
        metaHtml += `<div class="mt-2 px-3 py-2 bg-blue-100 rounded text-blue-800 text-sm"><strong>Import Period:</strong> ${selectedMonth} ${selectedYear}</div>`;
    }
    
    if (extraCols && extraCols.length > 0) {
        metaHtml += `<div class="mt-2 p-3 bg-orange-50 border border-orange-200 text-orange-800 rounded text-sm mb-4">
            ⚠️ <strong>Some columns were not recognized and will not be imported:</strong> ${extraCols.join(', ')}
        </div>`;
    }

    document.getElementById('previewMeta').innerHTML = metaHtml;

    // Ensure table headers map dynamically to the imported keys, but only the mapped ones!
    const thead = document.querySelector('#dataPreview thead tr');
    if (thead && rows.length > 0) {
        thead.innerHTML = previewTableHeaders(rows[0], requiredCols);
    }

    document.getElementById('previewBody').innerHTML = previewTableRows(rows, requiredCols);

    const preview = document.getElementById('dataPreview');
    preview.classList.remove('hidden');
    preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

const cancelImportBtn = document.getElementById('cancelImport');
if (cancelImportBtn) {
    cancelImportBtn.addEventListener('click', () => {
        document.getElementById('dataPreview').classList.add('hidden');
        selectedFile = null;
        parsedExcelData = [];
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        if (periodPanel) periodPanel.classList.add('hidden');
        if (monthSelect) monthSelect.value = '';
        if (yearSelect) yearSelect.value = '';
    });
}

const confirmImportBtn = document.getElementById('confirmImport');
if (confirmImportBtn) {
    confirmImportBtn.addEventListener('click', () => {
        if (!parsedExcelData.length) return;

        const program = document.getElementById('excelProgram').value;
        const importMonth = monthSelect?.value ?? '';
        const importYear = yearSelect?.value ?? '';
        const btn = document.getElementById('confirmImport');

        if (!importMonth || !importYear) {
            showToast('Please confirm Month and Year before importing.', 'warning');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Importing…';

        fetch('../../backend/import/save_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                program,
                data: parsedExcelData,
                importMonth,
                importYear,
                fileName: selectedFile?.name ?? '',
            }),
        })
            .then(async (res) => {
                const raw = await res.text();
                let result;

                try {
                    result = JSON.parse(raw);
                } catch {
                    const snippet = (raw || '').replace(/\s+/g, ' ').trim().slice(0, 140);
                    throw new Error(`Unexpected server response (HTTP ${res.status}). ${snippet ? `Details: ${snippet}` : ''}`.trim());
                }

                if (!res.ok) {
                    throw new Error(result.error ?? `Request failed (HTTP ${res.status}).`);
                }

                return result;
            })
            .then(result => {
                if (result.success) {
                    // Reset UI
                    document.getElementById('cancelImport').click();
                    // Show inline success message above the drop zone
                    const msg = document.createElement('div');
                    msg.className = 'bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-3 text-sm text-emerald-700 font-medium';
                    msg.textContent = result.message ?? 'Import completed successfully.';
                    document.getElementById('tab-excel').prepend(msg);
                    setTimeout(() => msg.remove(), 6000);
                } else {
                    showToast('Import failed: ' + (result.error ?? 'Unknown error'), 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Import failed: ' + (err.message ?? 'Server connection error.'), 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Import';
            });
    });
}
