// Data preview: pagination, period detection/suggestion, showExcelPreview.

import { previewTableRows, previewTableHeaders } from './common.js';
import { state } from './excel-state.js';
import { showToast } from '../toast.js';

// ─── Constants ────────────────────────────────────────────────────────────────
const PREVIEW_PAGE_SIZE = 25;
const MONTHS = [
    'January','February','March','April','May','June',
    'July','August','September','October','November','December',
];

// ─── Pagination state ─────────────────────────────────────────────────────────
let previewAllRows      = [];
let previewRequiredCols = [];
let previewExtraCols     = [];
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

function buildPageWindow(totalPages, currentPage) {
    if (totalPages <= 7) {
        return Array.from({ length: totalPages }, (_, index) => index + 1);
    }

    if (currentPage <= 4) {
        return [1, 2, 3, 4, 5, 'ellipsis', totalPages];
    }

    if (currentPage >= totalPages - 3) {
        return [1, 'ellipsis', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
    }

    return [1, 'ellipsis', currentPage - 1, currentPage, currentPage + 1, 'ellipsis', totalPages];
}

function buildPageButtonMarkup(page, currentPage) {
    const isActive = page === currentPage;
    const activeClasses = 'bg-blue-600 text-white border-blue-600 shadow-sm';
    const idleClasses = 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50';

    return `
        <button type="button" data-preview-page="${page}"
            class="min-w-9 px-3 py-1.5 text-xs sm:text-sm rounded-lg border transition-colors ${isActive ? activeClasses : idleClasses}"
            ${isActive ? 'disabled' : ''}>
            ${page}
        </button>`;
}

export function resetPreviewPaginationState() {
    previewAllRows      = [];
    previewRequiredCols = [];
    previewExtraCols    = [];
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
    const pageWindow = buildPageWindow(totalPages, previewCurrentPage);
    pagination.innerHTML = `
        <div class="text-xs sm:text-sm text-gray-500">
            Showing <span class="font-semibold text-gray-700">${startIdx + 1}</span> to
            <span class="font-semibold text-gray-700">${endIdx}</span> of
            <span class="font-semibold text-gray-700">${totalRows}</span> rows
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button id="previewPrevPage" type="button"
                class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                ${previewCurrentPage <= 1 ? 'disabled' : ''}>Previous</button>
            <div class="flex items-center gap-1">
                ${pageWindow.map(page => page === 'ellipsis'
                    ? '<span class="px-2 text-gray-400 text-xs sm:text-sm">...</span>'
                    : buildPageButtonMarkup(page, previewCurrentPage)).join('')}
            </div>
            <button id="previewNextPage" type="button"
                class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                ${previewCurrentPage >= totalPages ? 'disabled' : ''}>Next</button>
        </div>`;

    document.getElementById('previewPrevPage')?.addEventListener('click', () => renderPreviewPage(previewCurrentPage - 1));
    document.getElementById('previewNextPage')?.addEventListener('click', () => renderPreviewPage(previewCurrentPage + 1));
    pagination.querySelectorAll('[data-preview-page]').forEach(btn => {
        btn.addEventListener('click', () => {
            const page = Number(btn.getAttribute('data-preview-page'));
            if (Number.isFinite(page)) renderPreviewPage(page);
        });
    });
}

export async function revalidateCurrentPreview() {
    const program = document.getElementById('excelProgram')?.value ?? '';
    if (!program || !state.parsedExcelData.length) return;

    const wiirpCategory = document.getElementById('wiirpCategory')?.value ?? '';
    const gipCategory = document.getElementById('gipCategory')?.value ?? '';
    const jobFairEvent = (document.getElementById('jobFairEvent')?.value || state.selectedJobFairEvent || '').trim();

    const res = await fetch('../../backend/import/validate_preview.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            program,
            data: state.parsedExcelData,
            wiirpCategory,
            gipCategory,
            jobFairEvent,
        }),
    });

    const raw = await res.text();
    let result;
    try {
        result = JSON.parse(raw);
    } catch {
        const snippet = (raw || '').replace(/\s+/g, ' ').trim().slice(0, 180);
        throw new Error(`Unexpected validation response (HTTP ${res.status}). ${snippet ? `Details: ${snippet}` : ''}`.trim());
    }
    if (!res.ok) throw new Error(result.error ?? `Validation failed (HTTP ${res.status}).`);

    state.parsedExcelData = result.data;
    state.unknownEmployers = Array.isArray(result.unknownEmployers) ? result.unknownEmployers : [];
    showExcelPreview(result.data, result.summary, previewRequiredCols, previewExtraCols);
}

export function isUnresolvedJobFairCompanyRow(row) {
    if ((row?.badge_status ?? '').toLowerCase() !== 'invalid') return false;

    const hasSuggestion = String(row?.suggested_company_name ?? '').trim() !== '';
    if (hasSuggestion) return true;

    const msg = String(row?.status_message ?? '').toLowerCase();
    return msg.includes('did you mean')
        || msg.includes('not a participant')
        || msg.includes('missing company')
        || (msg.includes('company') && msg.includes('not found'));
}

async function acceptCompanySuggestion(rowIndex) {
    const index = Number(rowIndex);
    if (!Number.isFinite(index) || index < 0 || index >= state.parsedExcelData.length) return;

    const row = state.parsedExcelData[index];
    const suggestion = String(row?.suggested_company_name ?? '').trim();
    if (!suggestion) return;

    const companyKeys = ['Company', 'CompanyName', 'Employer'];
    const originalCompany = row._sys_original_company ?? companyKeys.map(key => row[key]).find(value => String(value ?? '').trim()) ?? '';
    row._sys_original_company = originalCompany;

    let updated = false;
    for (const key of companyKeys) {
        if (Object.prototype.hasOwnProperty.call(row, key) || key === 'Company') {
            row[key] = suggestion;
            updated = true;
        }
    }
    if (!updated) {
        row.Company = suggestion;
    }

    delete row.suggested_company_name;
    delete row.suggested_company_id;
    delete row.suggested_company_similarity;

    row.badge_status = 'new';
    row.status_message = 'Accepted company suggestion. Revalidating...';
    row._sys_skip = false;

    try {
        showToast(`Accepted suggestion: ${suggestion}. Revalidating rows...`, 'info');
        await revalidateCurrentPreview();

        const refreshedRow = state.parsedExcelData[index];
        if (refreshedRow && isUnresolvedJobFairCompanyRow(refreshedRow)) {
            showToast(
                `Company was updated to "${suggestion}" but this row is still invalid. Check the status message or try another company name.`,
                'warning'
            );
            return;
        }

        const remaining = state.parsedExcelData.filter(isUnresolvedJobFairCompanyRow).length;
        if (remaining > 0) {
            showToast(
                `Suggestion applied. ${remaining} row(s) still have unresolved company names — accept suggestions or fix them before importing.`,
                'warning'
            );
            return;
        }

        showToast('Suggestion applied and rows revalidated.', 'success');
    } catch (err) {
        if (originalCompany) {
            for (const key of companyKeys) {
                if (Object.prototype.hasOwnProperty.call(row, key) || key === 'Company') {
                    row[key] = originalCompany;
                }
            }
        }
        row.badge_status = 'invalid';
        row.status_message = row.status_message || 'Company suggestion could not be applied.';
        showToast('Could not revalidate after applying the suggestion: ' + (err?.message ?? 'Unknown error'), 'error');
    }
}

const previewContainer = document.getElementById('dataPreview');
if (previewContainer) {
    previewContainer.addEventListener('click', event => {
        const button = event.target.closest('[data-accept-company-suggestion]');
        if (!button) return;
        event.preventDefault();
        const rowIndex = button.getAttribute('data-preview-row-index');
        acceptCompanySuggestion(rowIndex);
    });
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
        if (period.month && monthSelect) monthSelect.value = period.month;
        if (period.year)  yearSelect.value = period.year;

        if (state.program !== 'Job Fair') {
            periodSuggestionText.textContent = (period.month && period.year)
                ? `Detected from ${period.source === 'filename' ? 'filename' : 'file contents'}: ${period.month} ${period.year}`
                : 'Could not confidently detect period. Please select month and year before import.';
        } else {
            periodSuggestionText.textContent = '';
        }
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
    previewExtraCols    = Array.isArray(extraCols) ? extraCols : [];
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

    const scrollTarget = periodPanel || preview;
    if (scrollTarget) {
        const topOffset = 100;
        const targetTop = window.scrollY + scrollTarget.getBoundingClientRect().top - topOffset;
        window.scrollTo({ top: Math.max(0, targetTop), behavior: 'smooth' });
    }
}
