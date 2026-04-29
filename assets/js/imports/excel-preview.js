// Data preview: pagination, period detection/suggestion, showExcelPreview.

import { previewTableRows, previewTableHeaders } from './common.js';
import { state } from './excel-state.js';

// ─── Constants ────────────────────────────────────────────────────────────────
const PREVIEW_PAGE_SIZE = 25;
const MONTHS = [
    'January','February','March','April','May','June',
    'July','August','September','October','November','December',
];

// ─── Pagination state ─────────────────────────────────────────────────────────
let previewAllRows      = [];
let previewRequiredCols = [];
let previewCurrentPage  = 1;

// Month / year selectors (shared with period detection)
const monthSelect        = document.getElementById('importMonth');
const yearSelect         = document.getElementById('importYear');
const periodPanel        = document.getElementById('importPeriodPanel');
const periodSuggestionText = document.getElementById('periodSuggestionText');

// ─── Pagination helpers ───────────────────────────────────────────────────────
function ensurePaginationContainer() {
    const dataPreview = document.getElementById('dataPreview');
    if (!dataPreview) return null;

    let container = document.getElementById('previewPagination');
    if (container) return container;

    const tableWrap = dataPreview.querySelector('.overflow-x-auto');
    if (!tableWrap || !tableWrap.parentNode) return null;

    container = document.createElement('div');
    container.id        = 'previewPagination';
    container.className = 'hidden mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between';
    tableWrap.parentNode.insertBefore(container, tableWrap.nextSibling);
    return container;
}

export function resetPreviewPaginationState() {
    previewAllRows      = [];
    previewRequiredCols = [];
    previewCurrentPage  = 1;

    const pagination = document.getElementById('previewPagination');
    if (pagination) {
        pagination.innerHTML = '';
        pagination.classList.add('hidden');
    }
}

export function renderPreviewPage(page = 1) {
    const totalRows  = previewAllRows.length;
    const totalPages = Math.max(1, Math.ceil(totalRows / PREVIEW_PAGE_SIZE));
    previewCurrentPage = Math.min(Math.max(page, 1), totalPages);

    const startIdx = (previewCurrentPage - 1) * PREVIEW_PAGE_SIZE;
    const endIdx   = Math.min(startIdx + PREVIEW_PAGE_SIZE, totalRows);
    const pageRows = previewAllRows.slice(startIdx, endIdx);

    const thead = document.querySelector('#dataPreview thead tr');
    if (thead && previewAllRows.length > 0) {
        thead.innerHTML = previewTableHeaders(previewAllRows[0], previewRequiredCols);
    }
    document.getElementById('previewBody').innerHTML = previewTableRows(pageRows, previewRequiredCols);

    const pagination = ensurePaginationContainer();
    if (!pagination) return;

    if (totalRows <= PREVIEW_PAGE_SIZE) {
        pagination.innerHTML = '';
        pagination.classList.add('hidden');
        return;
    }

    pagination.classList.remove('hidden');
    pagination.innerHTML = `
        <div class="text-xs sm:text-sm text-gray-500">
            Showing <span class="font-semibold text-gray-700">${startIdx + 1}</span> to
            <span class="font-semibold text-gray-700">${endIdx}</span> of
            <span class="font-semibold text-gray-700">${totalRows}</span> rows
        </div>
        <div class="flex items-center gap-2">
            <button id="previewPrevPage" type="button"
                class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                ${previewCurrentPage <= 1 ? 'disabled' : ''}>Previous</button>
            <span class="text-xs sm:text-sm text-gray-600">Page ${previewCurrentPage} of ${totalPages}</span>
            <button id="previewNextPage" type="button"
                class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                ${previewCurrentPage >= totalPages ? 'disabled' : ''}>Next</button>
        </div>`;

    document.getElementById('previewPrevPage')?.addEventListener('click', () => renderPreviewPage(previewCurrentPage - 1));
    document.getElementById('previewNextPage')?.addEventListener('click', () => renderPreviewPage(previewCurrentPage + 1));
}

// ─── Period selectors ─────────────────────────────────────────────────────────
export function populatePeriodSelectors() {
    if (!monthSelect || !yearSelect) return;

    monthSelect.innerHTML = '<option value="">Select month...</option>' +
        MONTHS.map(m => `<option value="${m}">${m}</option>`).join('');

    const currentYear = new Date().getFullYear();
    let yearOptions = '<option value="">Select year...</option>';
    for (let y = currentYear; y >= currentYear - 8; y--) {
        yearOptions += `<option value="${y}">${y}</option>`;
    }
    yearSelect.innerHTML = yearOptions;
}

export function detectPeriodFromFilename(fileName) {
    const lowered = (fileName || '').toLowerCase();
    const monthMap = {
        january:'January', jan:'January',
        february:'February', feb:'February',
        march:'March', mar:'March',
        april:'April', apr:'April',
        may:'May',
        june:'June', jun:'June',
        july:'July', jul:'July',
        august:'August', aug:'August',
        september:'September', sep:'September', sept:'September',
        october:'October', oct:'October',
        november:'November', nov:'November',
        december:'December', dec:'December',
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
        year:  foundYear,
        confidence: (foundMonth && foundYear) ? 'high' : ((foundMonth || foundYear) ? 'medium' : 'low'),
        source: 'filename',
    };
}

export function detectPeriodFromRows(rows) {
    if (!Array.isArray(rows) || rows.length === 0) {
        return { month: '', year: '', confidence: 'low', source: 'content' };
    }

    const sample    = rows.slice(0, 20);
    const monthKeys = ['Month', 'month', 'MONTH'];
    const yearKeys  = ['Year', 'year', 'YEAR'];
    const dateKeys  = ['Date', 'date', 'DATE', 'Transaction Date', 'Report Date'];

    let month = '';
    let year  = '';

    for (const r of sample) {
        if (!month) {
            for (const k of monthKeys) {
                if (r[k]) {
                    const normalized = MONTHS.find(x => x.toLowerCase() === String(r[k]).trim().toLowerCase());
                    if (normalized) month = normalized;
                }
            }
        }
        if (!year) {
            for (const k of yearKeys) {
                if (r[k] && /^(19|20)\d{2}$/.test(String(r[k]).trim())) year = String(r[k]).trim();
            }
        }
        if ((!month || !year) && !dateKeys.every(k => !r[k])) {
            for (const k of dateKeys) {
                const raw = r[k];
                if (!raw) continue;
                const parsed = new Date(raw);
                if (!Number.isNaN(parsed.getTime())) {
                    if (!month) month = MONTHS[parsed.getMonth()];
                    if (!year)  year  = String(parsed.getFullYear());
                }
            }
        }
        if (month && year) break;
    }

    return {
        month,
        year,
        confidence: (month && year) ? 'medium' : 'low',
        source: 'content',
    };
}

export function applyDetectedPeriod(period, { hideMonth = false, hideYear = false } = {}) {
    if (!yearSelect || !periodPanel || !periodSuggestionText) return;

    const monthWrapper = document.getElementById('importMonthWrapper');
    const yearWrapper = document.getElementById('importYear')?.closest('div');

    periodPanel.classList.remove('hidden');

    if (hideMonth || hideYear) {
        if (monthWrapper) monthWrapper.classList.toggle('hidden', hideMonth);
        if (yearWrapper) yearWrapper.classList.toggle('hidden', hideYear);

        if (hideMonth && monthSelect) monthSelect.value = '';
        if (hideYear && yearSelect) yearSelect.value = '';

        if (hideMonth && hideYear) {
            periodPanel.classList.add('hidden');
            periodSuggestionText.textContent = '';
            return;
        }

        if (hideMonth) {
            // Program supplies month per-row in the file — hide the global month picker.
            // Pre-select current year (user can still change it).
            if (yearSelect && !yearSelect.value) {
                yearSelect.value = String(new Date().getFullYear());
            }
            periodSuggestionText.textContent = 'Month is taken from each row. Confirm the import year below.';
            return;
        }

        if (hideYear) {
            periodSuggestionText.textContent = 'Year is taken from the file. Confirm the import month below.';
            return;
        }
    } else {
        if (monthWrapper) monthWrapper.classList.remove('hidden');
        if (yearWrapper) yearWrapper.classList.remove('hidden');
        const wiirpCategoryWrapper = document.getElementById('wiirpCategoryWrapper');
        const selectedProgram = document.getElementById('excelProgram')?.value ?? '';
        if (wiirpCategoryWrapper) {
            wiirpCategoryWrapper.classList.toggle('hidden', selectedProgram !== 'Work Immersion and Internship Referral Program');
        }
        if (period.month && monthSelect) monthSelect.value = period.month;
        if (period.year)  yearSelect.value = period.year;

        periodSuggestionText.textContent = (period.month && period.year)
            ? `Detected from ${period.source === 'filename' ? 'filename' : 'file contents'}: ${period.month} ${period.year}`
            : 'Could not confidently detect period. Please select month and year before import.';
    }
}

// ─── Main preview renderer ────────────────────────────────────────────────────
export function showExcelPreview(rows, summary, requiredCols, extraCols) {
    const total    = summary?.total    ?? rows.length;
    const newCount = summary?.new      ?? rows.length;
    const errCount = summary?.invalid  ?? 0;
    const dupCount = summary?.duplicate ?? 0;

    let summaryHtml = `<div class="flex items-center gap-4 text-sm mb-2">
        <div class="px-3 py-1 bg-gray-100 rounded text-gray-700"><b>${total}</b> total rows</div>
        <div class="px-3 py-1 bg-emerald-100 rounded text-emerald-800"><b>${newCount}</b> valid rows</div>`;

    if (errCount > 0) summaryHtml += `<div class="px-3 py-1 bg-red-100 rounded text-red-800"><b>${errCount}</b> rows with errors</div>`;
    if (dupCount > 0) summaryHtml += `<div class="px-3 py-1 bg-yellow-100 rounded text-yellow-800"><b>${dupCount}</b> duplicate rows</div>`;
    if (extraCols?.length > 0) summaryHtml += `<div class="px-3 py-1 bg-orange-100 rounded text-orange-800"><b>${extraCols.length}</b> unmapped col(s)</div>`;
    summaryHtml += `</div>`;

    let metaHtml = summaryHtml;

    const selectedMonth = monthSelect?.value ?? '';
    const selectedYear  = yearSelect?.value ?? '';
    if (selectedMonth && selectedYear) {
        metaHtml += `<div class="mt-2 px-3 py-2 bg-blue-100 rounded text-blue-800 text-sm"><strong>Import Period:</strong> ${selectedMonth} ${selectedYear}</div>`;
    }
    if (extraCols?.length > 0) {
        metaHtml += `<div class="mt-2 p-3 bg-orange-50 border border-orange-200 text-orange-800 rounded text-sm mb-4">
            ⚠️ <strong>Some columns were not recognized and will not be imported:</strong> ${extraCols.join(', ')}
        </div>`;
    }

    document.getElementById('previewMeta').innerHTML = metaHtml;

    previewAllRows      = rows;
    previewRequiredCols = Array.isArray(requiredCols) ? requiredCols : [];
    renderPreviewPage(1);

    // Guard: disable Import button when nothing is importable
    const confirmBtn = document.getElementById('confirmImport');
    const hasImportableRows = newCount > 0;
    if (confirmBtn) {
        confirmBtn.disabled    = !hasImportableRows;
        confirmBtn.textContent = hasImportableRows ? 'Import' : 'Nothing to Import';
        confirmBtn.className   = hasImportableRows
            ? 'px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors'
            : 'px-5 py-2 bg-gray-200 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed';
    }

    if (!hasImportableRows) {
        document.getElementById('previewMeta').insertAdjacentHTML('beforeend', `
            <div class="mt-3 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg class="w-4 h-4 flex-shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>All rows are either <strong>duplicates</strong> or <strong>invalid</strong> — there is nothing to import.</span>
            </div>`);
    }

    const preview = document.getElementById('dataPreview');
    preview.classList.remove('hidden');
    preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
