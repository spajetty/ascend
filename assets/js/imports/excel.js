// ─── excel.js ─────────────────────────────────────────────────────────────────
// Entry-point coordinator for the Excel import tab.
// Imports and initialises all sub-modules; owns the confirmImport handler.

import { showToast } from '../toast.js';
import { state } from './excel-state.js';
import { initImportResultsUi, showImportResultsView } from './excel-results.js';
import { openImportConfirmModal } from './excel-modals.js';
import { resetPreviewPaginationState } from './excel-preview.js';
import { setProgramSelectorsLocked, setUploadStateFromProgramSelection } from './excel-upload.js';

// Side-effect imports — these modules wire their own DOM event listeners on load.
import './excel-upload.js';

// ─── Initialise results UI ────────────────────────────────────────────────────
initImportResultsUi();

// ─── Cancel preview ───────────────────────────────────────────────────────────
const cancelImportBtn = document.getElementById('cancelImport');
if (cancelImportBtn) {
    cancelImportBtn.addEventListener('click', () => {
        document.getElementById('dataPreview').classList.add('hidden');
        state.selectedFile    = null;
        state.parsedExcelData = [];

        const fileInput   = document.getElementById('fileInput');
        const fileInfo    = document.getElementById('fileInfo');
        const periodPanel = document.getElementById('importPeriodPanel');
        const monthSelect = document.getElementById('importMonth');
        const yearSelect  = document.getElementById('importYear');
        const spesCategory = document.getElementById('spesCategory');

        if (fileInput) fileInput.value = '';
        if (fileInfo)  fileInfo.classList.add('hidden');
        if (periodPanel) periodPanel.classList.add('hidden');
        if (monthSelect) monthSelect.value = '';
        if (yearSelect)  yearSelect.value  = '';
        if (spesCategory) spesCategory.value = '';
        
        const monthWrapper = document.getElementById('importMonthWrapper');
        if (monthWrapper) monthWrapper.classList.remove('hidden');

        setProgramSelectorsLocked(false);
        resetPreviewPaginationState();
        setUploadStateFromProgramSelection();
    });
}

// ─── Confirm & run import ─────────────────────────────────────────────────────
const confirmImportBtn = document.getElementById('confirmImport');
if (confirmImportBtn) {
    confirmImportBtn.addEventListener('click', () => {
        if (!state.parsedExcelData.length) return;

        const program     = document.getElementById('excelProgram').value;
        const importMonth = document.getElementById('importMonth')?.value ?? '';
        const importYear  = document.getElementById('importYear')?.value  ?? '';
        const btn         = document.getElementById('confirmImport');

        const needsGlobalMonth = program !== 'Employers Accreditation' && program !== 'Schools';
        const needsGlobalYear = program !== 'Schools';
        if ((needsGlobalMonth && !importMonth) || (needsGlobalYear && !importYear)) {
            showToast(needsGlobalMonth ? 'Please confirm Month and Year before importing.' : 'Please confirm Year before importing.', 'warning');
            return;
        }

        const periodLabel = program === 'Schools'
            ? 'Not required'
            : `${importMonth} ${importYear}`.trim();

        const duplicateRows  = state.parsedExcelData.filter(r => (r.badge_status ?? '').toLowerCase() === 'duplicate').length;
        const invalidRows    = state.parsedExcelData.filter(r => (r.badge_status ?? '').toLowerCase() === 'invalid').length;
        const skippedRows    = state.parsedExcelData.filter(r => !!r._sys_skip).length;
        const importableRows = state.parsedExcelData.length - skippedRows;

        openImportConfirmModal({
            program,
            period:       periodLabel,
            fileName:     state.selectedFile?.name ?? 'N/A',
            rowsToImport: importableRows,
            skipped:      skippedRows,
            duplicates:   duplicateRows,
            invalid:      invalidRows,
        }, () => {
            btn.disabled    = true;
            btn.textContent = 'Importing…';

            fetch('../../backend/import/save_data.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({
                    program,
                    data:        state.parsedExcelData,
                    importMonth,
                    importYear,
                    fileName:    state.selectedFile?.name ?? '',
                    spesCategory: program === 'SPES' ? (document.getElementById('spesCategory')?.value ?? '') : '',
                }),
            })
                .then(async res => {
                    const raw = await res.text();
                    let result;
                    try {
                        result = JSON.parse(raw);
                    } catch {
                        const snippet = (raw || '').replace(/\s+/g, ' ').trim().slice(0, 140);
                        throw new Error(`Unexpected server response (HTTP ${res.status}). ${snippet ? `Details: ${snippet}` : ''}`.trim());
                    }
                    if (!res.ok) throw new Error(result.error ?? `Request failed (HTTP ${res.status}).`);
                    return result;
                })
                .then(result => {
                    if (result.success) {
                        const rowsSnapshot     = state.parsedExcelData.map(r => ({ ...r }));
                        const importedFileName = state.selectedFile?.name ?? '';
                        const duplicateRows    = rowsSnapshot.filter(r => (r.badge_status ?? '').toLowerCase() === 'duplicate');
                        const errorRows        = rowsSnapshot.filter(r => (r.badge_status ?? '').toLowerCase() === 'invalid');
                        const warnings         = Array.isArray(result.warnings) ? result.warnings.filter(Boolean) : [];
                        const newEmployers     = warnings
                            .map(w => { const m = String(w).match(/^New company created:\s*(.+)$/i); return m ? m[1].trim() : ''; })
                            .filter(Boolean);
                        const newSchools       = program === 'Schools'
                            ? rowsSnapshot
                                .filter(r => (r.badge_status ?? '').toLowerCase() === 'new')
                                .map(r => (r.school_name || r['School Name'] || '').trim())
                                .filter(Boolean)
                            : [];

                        // Reset the form/preview before showing results
                        document.getElementById('cancelImport').click();

                        showImportResultsView({
                            processed:    rowsSnapshot.length,
                            added:        Number(result.saved ?? 0),
                            duplicates:   duplicateRows.length,
                            errors:       errorRows.length,
                            program,
                            period:       periodLabel,
                            fileName:     importedFileName,
                            warnings,
                            newEmployers,
                            newSchools,
                            duplicateRows,
                            errorRows,
                        }, result.undo_token ?? null);

                        showToast(result.message ?? 'Import completed successfully.', 'success');
                    } else {
                        showToast('Import failed: ' + (result.error ?? 'Unknown error'), 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Import failed: ' + (err.message ?? 'Server connection error.'), 'error');
                })
                .finally(() => {
                    btn.disabled    = false;
                    btn.textContent = 'Import';
                });
        });
    });
}
