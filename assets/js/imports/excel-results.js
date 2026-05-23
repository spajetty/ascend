// Results panel: stat cards, tab switching, duplicate/error tables,
// rollback confirm modal, CSV download, and panel show/hide.

import { showToast } from '../toast.js';
import { runWithButtonLoading } from '../loading.js';
import { state } from './excel-state.js';
import { formatColumnName } from './common.js';

// ─── Shared DOM refs ──────────────────────────────────────────────────────────
const importFormScreen   = document.getElementById('importFormScreen');
const importResultsView  = document.getElementById('importResultsView');
const importResultsSummary  = document.getElementById('importResultsSummary');
const importResultsWarnings = document.getElementById('importResultsWarnings');
const employerAccreditationView = document.getElementById('employerAccreditationView');
const employerAccreditationTableBody = document.getElementById('employerAccreditationTableBody');
const PENDING_ACCRREDITATION_KEY = 'ascend.pendingEmployerAccreditation';

const EMPLOYER_ACCRUAL_PROGRAMS = new Set([
    'Job Matching and Referral',
    'First Time Jobseeker',
    'SPES',
    'Workers Hiring for Infrastructure Projects - Projects',
    'Workers Hiring for Infrastructure Projects — Projects',
]);

const ACCREDITATION_MONTHS = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
];

// ─── Helpers ──────────────────────────────────────────────────────────────────
function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function toCsvCell(value) {
    const str = String(value ?? '');
    return '"' + str.replace(/"/g, '""') + '"';
}

function getRollbackEntityLabel(program) {
    switch (program) {
        case 'Job Matching and Referral':
        case 'First Time Jobseeker':
        case 'Job Fair':
            return 'employer records';
        default:
            return null;
    }
}

function shouldShowEmployerResultsTab(data) {
    if (['Work Immersion and Internship Referral Program', 
        'Government Internship Program',
        'Workers Hiring for Infrastructure Projects - Beneficiaries', 
        'Workers Hiring for Infrastructure Projects — Beneficiaries'].includes(data.program || '')) {
        return false;
    }
    return true;
}

function getProceedButtonLabel(program) {
    const p = String(program || '').trim();
    if (!p) return 'Proceed';
    const ACRONYMS = {
        'First Time Jobseeker': 'FTJS',
        'Work Immersion and Internship Referral Program': 'WIIRP',
        'Workers Hiring for Infrastructure Projects - Beneficiaries': 'WHIP',
        'Workers Hiring for Infrastructure Projects - Projects': 'Projects',
        'Government Internship Program': 'GIP',
    };
    const abbr = ACRONYMS[p] || null;
    return `Proceed to ${abbr || p}`;
}

function isEmployerAccreditationFollowupProgram(program) {
    return EMPLOYER_ACCRUAL_PROGRAMS.has(String(program || '').trim());
}

function buildDropdownOptions(values, selectedValue = '') {
    return values.map(value => {
        const isSelected = String(value) === String(selectedValue);
        return `<option value="${escapeHtml(value)}"${isSelected ? ' selected' : ''}>${escapeHtml(value)}</option>`;
    }).join('');
}

// ─── Tab switching ────────────────────────────────────────────────────────────
export function setResultsTab(tabKey) {
    document.querySelectorAll('.results-tab-btn').forEach(btn => {
        const active = btn.dataset.resultsTab === tabKey;
        btn.classList.toggle('bg-blue-600', active);
        btn.classList.toggle('text-white',  active);
        btn.classList.toggle('shadow-sm',   active);
        btn.classList.toggle('bg-gray-100', !active);
        btn.classList.toggle('text-gray-600', !active);
        const badge = btn.querySelector('span[id^="tabBadge"]');
        if (badge) {
            badge.classList.toggle('bg-white/30',    active);
            badge.classList.toggle('bg-gray-300/60', !active);
        }
    });

    const panels = {
        'new-employers': document.getElementById('resultsPanelNewEmployers'),
        'duplicates':    document.getElementById('resultsPanelDuplicates'),
        'errors':        document.getElementById('resultsPanelErrors'),
    };
    Object.entries(panels).forEach(([key, panel]) => {
        if (panel) panel.classList.toggle('hidden', key !== tabKey);
    });
}

// ─── Status pill ─────────────────────────────────────────────────────────────
function resultStatusPill(status) {
    const key = (status || '').toLowerCase();
    if (key === 'duplicate') {
        return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">🔁 Duplicate</span>';
    }
    if (key === 'invalid' || key === 'error') {
        return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">✕ Invalid</span>';
    }
    return `<span class="text-xs text-gray-400">${escapeHtml(status)}</span>`;
}

// ─── Rows table (duplicates / errors panel) ───────────────────────────────────
function buildRowsTable(rows, emptyLabel) {
    if (!rows.length) {
        return `
            <div class="flex flex-col items-center justify-center gap-3 py-10 text-center">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                </div>
                <p class="text-sm text-gray-500">${escapeHtml(emptyLabel)}</p>
            </div>`;
    }

    // Build dynamic headers from first row keys (filter out internal/system fields)
    const firstRow = rows[0] || {};
    const SKIP = new Set(['badge_status', 'status_message', '_sys_is_existing', '_sys_user_id', '_sys_benef_id', '_sys_skip', '_parsed_dob', 'is_new', 'duplicate']);
    const keys = Object.keys(firstRow).filter(k => !SKIP.has(k));

    const headerCols = keys.map(k => `
        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">${formatColumnName(k)}</th>`).join('');

    const body = rows.map((r, i) => {
        const status   = r.status_message || r.badge_status || '';
        const rowBg    = i % 2 === 0 ? 'bg-white' : 'bg-gray-50/60';
        const cells = keys.map(k => {
            let val = r[k];
            // Friendly fallbacks for common DOB/contact/name keys
            if ((k.toLowerCase().includes('dob') || k.toLowerCase().includes('birth')) && !val) val = r._parsed_dob || r.DOB || r.Birthday || '';
            if ((k.toLowerCase().includes('email') || k.toLowerCase().includes('contact')) && !val) val = r.Contact || r.contact || r.Email || r.email || '';
            if (!val && (k.toLowerCase() === 'first name' || k.toLowerCase() === 'fname')) val = r.fname || '';
            if (!val && (k.toLowerCase() === 'last name' || k.toLowerCase() === 'lname')) val = r.lname || '';
            const display = val !== undefined && val !== null ? escapeHtml(val) : '';
            const cellCls = String(val || '').length > 40 ? 'whitespace-normal max-w-[360px] break-words align-top' : 'whitespace-nowrap max-w-[180px] truncate';
            return `<td class="px-4 py-3 text-sm text-gray-600 ${cellCls}" title="${escapeHtml(val ?? '')}">${display}</td>`;
        }).join('');

        return `
            <tr class="${rowBg} hover:bg-blue-50/30 transition-colors">
                ${cells}
                <td class="px-4 py-3 whitespace-nowrap">${resultStatusPill(status)}</td>
            </tr>`;
    }).join('');

    return `
        <div class="overflow-x-auto overflow-y-hidden preview-scrollbar rounded-xl border border-gray-100">
            <table class="min-w-full text-left">
                <thead class="sticky top-0 bg-gray-50 border-b border-gray-100">
                    <tr>
                        ${headerCols}
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">${body}</tbody>
            </table>
        </div>`;
}

// ─── Stat card ────────────────────────────────────────────────────────────────
function statCard(value, label, c, iconPath) {
    return `
        <div class="flex items-center gap-3 rounded-xl border ${c.border} ${c.bg} px-4 py-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg ${c.icon} flex items-center justify-center">
                <svg class="w-4.5 h-4.5 ${c.svg}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">${iconPath}</svg>
            </div>
            <div>
                <p class="text-xl font-bold ${c.value}">${value}</p>
                <p class="text-xs font-medium ${c.label} leading-tight">${label}</p>
            </div>
        </div>`;
}

function buildEmployerAccreditationRows(createdEmployers, fallbackMonth = '', fallbackYear = '') {
    if (!employerAccreditationTableBody) return;

    const currentYear = new Date().getFullYear();
    const yearOptions = [];
    const startYear = 1990;
    const endYear = currentYear;
    for (let year = endYear; year >= startYear; year--) {
        yearOptions.push(String(year));
    }

    employerAccreditationTableBody.innerHTML = (createdEmployers || []).map((emp, index) => {
        const monthValue = fallbackMonth || new Date().toLocaleString('default', { month: 'long' });
        const yearValue = fallbackYear || String(new Date().getFullYear());
        return `
            <tr class="border-b border-gray-100 bg-white hover:bg-blue-50/30 transition-colors" data-employer-row="1" data-employer-id="${escapeHtml(emp.company_id ?? '')}">
                <td class="px-4 py-3">
                    <input type="text" class="accreditation-input w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" value="${escapeHtml(emp.company_name ?? '')}" data-field="company_name" />
                </td>
                <td class="px-4 py-3 w-40">
                    <select class="accreditation-input w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" data-field="month">
                        <option value="">Select month</option>
                        ${buildDropdownOptions(ACCREDITATION_MONTHS, monthValue)}
                    </select>
                </td>
                <td class="px-4 py-3 w-28">
                    <select class="accreditation-input w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" data-field="year">
                        <option value="">Select year</option>
                        ${buildDropdownOptions(yearOptions, yearValue)}
                    </select>
                </td>
                <td class="px-4 py-3 w-36">
                    <select class="accreditation-input w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" data-field="status">
                        <option value="new">New</option>
                        <option value="renew">Renew</option>
                    </select>
                </td>
                <td class="px-4 py-3">
                    <input type="text" class="accreditation-input w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" value="${escapeHtml(emp.est_type ?? '')}" data-field="est_type" placeholder="Est. type" />
                </td>
                <td class="px-4 py-3">
                    <input type="text" class="accreditation-input w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" value="${escapeHtml(emp.industry ?? '')}" data-field="industry" placeholder="Industry" />
                </td>
                <td class="px-4 py-3">
                    <input type="text" class="accreditation-input w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" value="${escapeHtml(emp.city ?? '')}" data-field="city" placeholder="City" />
                </td>
                <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">
                    #${index + 1}
                    <input type="hidden" data-field="company_id" value="${escapeHtml(emp.company_id ?? '')}" />
                </td>
            </tr>`;
    }).join('');

    // After rendering, wire up input listeners to clear errors and update submit state
    try {
        const inputs = employerAccreditationTableBody.querySelectorAll('.accreditation-input');
        inputs.forEach(inp => {
            inp.addEventListener('input', () => {
                try { inp.classList.remove('border-red-500', 'ring-2', 'ring-red-200'); } catch (e) {}
                updateSubmitButtonState();
            });
            inp.addEventListener('change', () => updateSubmitButtonState());
        });
    } catch (e) {}
    updateSubmitButtonState();
}

function collectEmployerAccreditationRows() {
    if (!employerAccreditationTableBody) return [];

    return [...employerAccreditationTableBody.querySelectorAll('tr[data-employer-row="1"]')].map(row => {
        const readField = (field) => row.querySelector(`[data-field="${field}"]`)?.value ?? '';
        return {
            _sys_employer_id: Number(readField('company_id') || 0),
            Company: readField('company_name'),
            Month: readField('month'),
            Year: readField('year'),
            Accreditation: readField('status'),
            'Est. Type': readField('est_type'),
            Industry: readField('industry'),
            'City/Municipality/Province': readField('city'),
        };
    });
}

function updateSubmitButtonState() {
    const btn = document.getElementById('submitEmployerAccreditationBtn');
    if (!btn) return;
    const rows = collectEmployerAccreditationRows();
    const ok = rows.length > 0 && validateEmployerAccreditationRows(rows).valid;
    btn.dataset.accreditationReady = ok ? '1' : '0';
    btn.classList.toggle('opacity-60', !ok);
    btn.classList.toggle('cursor-not-allowed', !ok);
    btn.classList.toggle('hover:bg-blue-700', ok);
}

function validateEmployerAccreditationRows(rows) {
    const requiredKeys = ['Company', 'Month', 'Year', 'Accreditation', 'Est. Type', 'Industry', 'City/Municipality/Province'];
    const errors = [];
    rows.forEach((r, idx) => {
        const missing = requiredKeys.filter(k => {
            const v = (r[k] ?? '').toString().trim();
            return v === '';
        });
        if (missing.length) errors.push({ row: idx, missing });
    });
    return { valid: errors.length === 0, errors };
}

function highlightAccreditationErrors(errors) {
    // errors: [{row, missing: [keys]}]
    const trs = [...employerAccreditationTableBody.querySelectorAll('tr[data-employer-row="1"]')];
    errors.forEach(err => {
        const tr = trs[err.row];
        if (!tr) return;
        err.missing.forEach(field => {
            // map display keys to data-field attributes
            let df = '';
            switch (field) {
                case 'Company': df = 'company_name'; break;
                case 'Month': df = 'month'; break;
                case 'Year': df = 'year'; break;
                case 'Accreditation': df = 'status'; break;
                case 'Est. Type': df = 'est_type'; break;
                case 'Industry': df = 'industry'; break;
                case 'City/Municipality/Province': df = 'city'; break;
                default: df = '';
            }
            if (!df) return;
            const el = tr.querySelector(`[data-field="${df}"]`);
            if (el && el.classList) {
                el.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            }
        });
    });
}

function ensureEmployerAccreditationViewState(show) {
    if (importFormScreen) importFormScreen.classList.toggle('hidden', show);
    if (importResultsView) importResultsView.classList.toggle('hidden', show);
    if (employerAccreditationView) employerAccreditationView.classList.toggle('hidden', !show);
}

export function showEmployerAccreditationView(data) {
    if (!employerAccreditationView || !employerAccreditationTableBody) return;

    state.pendingEmployerAccreditation = data;
    sessionStorage.setItem(PENDING_ACCRREDITATION_KEY, JSON.stringify(data));

    const metaLine = document.getElementById('employerAccreditationMetaLine');
    if (metaLine) {
        metaLine.textContent = `${escapeHtml(data.program)} • ${escapeHtml(data.period)} • Complete employer accreditation before leaving this step.`;
    }

    const summaryLine = document.getElementById('employerAccreditationSummaryLine');
    if (summaryLine) {
        summaryLine.textContent = `${(data.createdEmployers || []).length} employer${(data.createdEmployers || []).length === 1 ? '' : 's'} need accreditation.`;
    }

    buildEmployerAccreditationRows(data.createdEmployers || [], data.importMonth || '', data.importYear || '');
    ensureEmployerAccreditationViewState(true);
}

async function submitEmployerAccreditationFollowup() {
    const payloadRows = collectEmployerAccreditationRows();
    if (!payloadRows.length) {
        showToast('No employer rows found to submit.', 'warning');
        return;
    }

    // Client-side validation: all fields required
    const validation = validateEmployerAccreditationRows(payloadRows);
    if (!validation.valid) {
        showToast('Please complete all required fields before submitting.', 'warning');
        highlightAccreditationErrors(validation.errors);
        return;
    }

    const programData = state.pendingEmployerAccreditation || {};
    const btn = document.getElementById('submitEmployerAccreditationBtn');

    await runWithButtonLoading(btn, async () => {
        const res = await fetch('../../backend/import/save_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                program: 'Employers Accreditation',
                batchId: programData.batchId || null,
                data: payloadRows,
                importMonth: programData.importMonth || '',
                importYear: programData.importYear || '',
                fileName: programData.fileName || 'Employer accreditation follow-up',
            }),
        });

        const raw = await res.text();
        let result;
        try {
            result = JSON.parse(raw);
        } catch {
            throw new Error(`Unexpected server response (HTTP ${res.status}).`);
        }
        if (!res.ok || !result.success) {
            throw new Error(result.error ?? `Request failed (HTTP ${res.status}).`);
        }

        sessionStorage.removeItem('ascend.pendingEmployerAccreditation');
        state.pendingEmployerAccreditation = null;
        showToast(result.message ?? 'Employer accreditation saved.', 'success');
        window.location.href = '../../pages/beneficiaries/beneficiary.php';
    }, { label: 'Saving accreditation…' }).catch(err => {
        showToast('Accreditation submission failed: ' + (err.message ?? 'Unknown error'), 'error');
    });
}

// ─── Render results view ──────────────────────────────────────────────────────
export function renderImportResultsView(data) {
    if (!importResultsView || !importResultsSummary || !importResultsWarnings) return;
    const isSchools = (data.program || '') === 'Schools';
    const showEmployerTab = shouldShowEmployerResultsTab(data);
    const primaryLabel = isSchools
        ? 'New Schools'
        : 'New Employers';
    const emptyPrimaryLabel = isSchools
        ? 'No new schools were created in this import.'
        : 'No new employers were created in this import.';
    const createdItems = isSchools
        ? (data.newSchools || [])
        : showEmployerTab
            ? (data.newEmployers || [])
            : [];

    // Meta line
    const metaLine = document.getElementById('importResultsMetaLine');
    if (metaLine) {
        metaLine.textContent = `${escapeHtml(data.program)}  •  ${escapeHtml(data.period)}  •  ${escapeHtml(data.fileName)}`;
    }

    // Stat cards
    importResultsSummary.innerHTML = [
        statCard(data.processed, 'Rows Processed',
            { border:'border-gray-200', bg:'bg-gray-50', icon:'bg-gray-100', svg:'text-gray-500', value:'text-gray-800', label:'text-gray-500' },
            '<rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>'),
        statCard(data.added, 'Successfully Added',
            { border:'border-emerald-200', bg:'bg-emerald-50', icon:'bg-emerald-100', svg:'text-emerald-600', value:'text-emerald-700', label:'text-emerald-600' },
            '<path d="M20 6L9 17l-5-5"/>'),
        statCard(data.duplicates, 'Duplicates Skipped',
            { border:'border-yellow-200', bg:'bg-yellow-50', icon:'bg-yellow-100', svg:'text-yellow-600', value:'text-yellow-700', label:'text-yellow-600' },
            '<rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>'),
        statCard(data.errors, 'Errors Found',
            { border:'border-red-200', bg:'bg-red-50', icon:'bg-red-100', svg:'text-red-500', value:'text-red-700', label:'text-red-500' },
            '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'),
    ].join('');

    // Warnings accordion
    if (data.warnings.length) {
        importResultsWarnings.classList.remove('hidden');
        const countEl = document.getElementById('warningsCount');
        const listEl  = document.getElementById('warningsList');
        if (countEl) countEl.textContent = `${data.warnings.length} Warning${data.warnings.length !== 1 ? 's' : ''}`;
        if (listEl)  listEl.innerHTML = data.warnings
            .map(w => `<div class="flex gap-2"><span class="text-amber-400 flex-shrink-0">•</span><span>${escapeHtml(w)}</span></div>`)
            .join('');
    } else {
        importResultsWarnings.classList.add('hidden');
    }

    // Tab badge counts
    const labelNE = document.getElementById('tabLabelNewEmployers');
    const badgeNE  = document.getElementById('tabBadgeNewEmployers');
    const badgeDup = document.getElementById('tabBadgeDuplicates');
    const badgeErr = document.getElementById('tabBadgeErrors');
    const proceedBtn = document.getElementById('proceedToJobFairBtn');
    const employerTabBtn = document.querySelector('[data-results-tab="new-employers"]');
    const employerTabPanel = document.getElementById('resultsPanelNewEmployers');
    const reviewEmployersBtn = document.getElementById('reviewEmployersBtn');
    const addAccreditationBtn = document.getElementById('addAccreditationBtn');

    if (employerTabBtn) employerTabBtn.classList.toggle('hidden', !showEmployerTab);
    if (employerTabPanel) employerTabPanel.classList.toggle('hidden', !showEmployerTab);
    if (reviewEmployersBtn) reviewEmployersBtn.classList.toggle('hidden', !showEmployerTab);
    if (addAccreditationBtn) addAccreditationBtn.classList.toggle('hidden', !showEmployerTab);

    if (proceedBtn) {
        proceedBtn.textContent = getProceedButtonLabel(data.program || '');
    }

    if (labelNE) labelNE.textContent = primaryLabel;
    if (badgeNE)  badgeNE.textContent  = createdItems.length;
    if (badgeDup) badgeDup.textContent = data.duplicateRows.length;
    if (badgeErr) badgeErr.textContent = data.errorRows.length;

    // Panel content — program-specific created items
    const newEmployersPanel = employerTabPanel;
    if (newEmployersPanel) {
        if (!showEmployerTab) {
            newEmployersPanel.innerHTML = '';
        } else if (!createdItems.length) {
            newEmployersPanel.innerHTML = `
                <div class="flex flex-col items-center justify-center gap-3 py-10 text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5" /></svg>
                    </div>
                    <p class="text-sm text-gray-500">${escapeHtml(emptyPrimaryLabel)}</p>
                </div>`;
        } else {
            const items = createdItems.map((name, i) => `
                <div class="flex items-center gap-3 px-4 py-3 ${i % 2 === 0 ? 'bg-white' : 'bg-gray-50/60'} hover:bg-blue-50/30 transition-colors rounded-lg">
                    <div class="flex-shrink-0 w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-800">${escapeHtml(name)}</span>
                    <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">New</span>
                </div>`).join('');
            newEmployersPanel.innerHTML = `
                <div class="mb-3"><p class="text-sm font-semibold text-gray-700">${createdItems.length} ${isSchools ? 'school' : 'employer'}${createdItems.length !== 1 ? 's' : ''} created</p></div>
                <div class="space-y-1 rounded-xl border border-gray-100 overflow-hidden">${items}</div>`;
        }
    }

    const duplicatesPanel = document.getElementById('resultsPanelDuplicates');
    if (duplicatesPanel) duplicatesPanel.innerHTML = buildRowsTable(data.duplicateRows, 'No duplicate rows — all records were unique.');

    const errorsPanel = document.getElementById('resultsPanelErrors');
    if (errorsPanel) errorsPanel.innerHTML = buildRowsTable(data.errorRows, 'No errors — all records passed validation.');

    setResultsTab(showEmployerTab ? 'new-employers' : 'duplicates');
}

// ─── Show / hide views ────────────────────────────────────────────────────────
export function showImportResultsView(data, undoToken = null) {
    state.latestImportResultsData = data;
    state.latestUndoToken = undoToken || null;
    renderImportResultsView(data);

    const rollbackBtn = document.getElementById('rollbackImportBtn');
    if (rollbackBtn) {
        rollbackBtn.classList.toggle('hidden', !state.latestUndoToken);
        rollbackBtn.disabled = false;
        rollbackBtn.innerHTML = `
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                <path d="M3 3v5h5"/>
            </svg>
            Rollback Import`;
    }

    if (importFormScreen) importFormScreen.classList.add('hidden');
    const preview = document.getElementById('dataPreview');
    if (preview) preview.classList.add('hidden');
    if (importResultsView) importResultsView.classList.remove('hidden');
}

export function showImportFormView() {
    state.latestUndoToken = null;
    const rollbackBtn = document.getElementById('rollbackImportBtn');
    if (rollbackBtn) rollbackBtn.classList.add('hidden');
    if (importResultsView) importResultsView.classList.add('hidden');
    if (importFormScreen) importFormScreen.classList.remove('hidden');
}

// ─── CSV export ───────────────────────────────────────────────────────────────
export function downloadErrorReportCsv() {
    if (!state.latestImportResultsData) return;

    const rows = [...state.latestImportResultsData.errorRows, ...state.latestImportResultsData.duplicateRows];
    if (!rows.length) {
        showToast('No errors or duplicate rows to export.', 'warning');
        return;
    }

    const headers = ['First Name', 'Last Name', 'DOB', 'Contact', 'Email', 'Status Message'];
    const lines   = [headers.map(toCsvCell).join(',')];

    rows.forEach(r => {
        lines.push([
            r.fname || r['First Name'] || '',
            r.lname || r['Last Name']  || '',
            r._parsed_dob || r.DOB || r.Birthday || '',
            r.Contact || r.contact || '',
            r.Email   || r.email   || '',
            r.status_message || r.badge_status || '',
        ].map(toCsvCell).join(','));
    });

    const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `import_error_report_${Date.now()}.csv`;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
}

// ─── Rollback confirm modal ───────────────────────────────────────────────────
function ensureRollbackConfirmModal() {
    let modal = document.getElementById('rollbackConfirmModal');
    if (modal) return modal;

    modal = document.createElement('div');
    modal.id        = 'rollbackConfirmModal';
    modal.className = 'fixed inset-0 z-50 hidden items-center justify-center p-4';
    modal.innerHTML = `
        <div id="rollbackConfirmBackdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-start gap-4 mb-5">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Rollback Import?</h3>
                    <p class="mt-1 text-sm text-gray-500">All records added in this batch will be <strong class="text-red-600">permanently removed</strong> from the database. This action cannot be undone.</p>
                    <p class="mt-2 text-sm text-gray-600">Program: <span id="rollbackProgramText" class="font-semibold text-gray-900">—</span></p>
                </div>
            </div>
            <div class="rounded-xl bg-red-50 border border-red-100 px-4 py-3 mb-5">
                <p id="rollbackImpactNote" class="text-xs text-red-700 font-medium">⚠️ Newly created records linked to this import will also be deleted.</p>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button id="rollbackConfirmCancel" type="button"
                    class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 transition-colors">
                    Cancel
                </button>
                <button id="rollbackConfirmProceed" type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 px-5 py-2 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                    Yes, Rollback
                </button>
            </div>
        </div>`;
    document.body.appendChild(modal);

    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
    modal.querySelector('#rollbackConfirmBackdrop')?.addEventListener('click', closeModal);
    modal.querySelector('#rollbackConfirmCancel')?.addEventListener('click', closeModal);
    return modal;
}

function openRollbackConfirmModal(onConfirm) {
    const modal = ensureRollbackConfirmModal();
    if (!modal) return;

    const rollbackProgramText = modal.querySelector('#rollbackProgramText');
    if (rollbackProgramText) {
        rollbackProgramText.textContent = state.latestImportResultsData?.program ?? 'Unknown program';
    }

    const rollbackImpactNote = modal.querySelector('#rollbackImpactNote');
    if (rollbackImpactNote) {
        const program = state.latestImportResultsData?.program ?? '';
        const entityLabel = getRollbackEntityLabel(program);
        rollbackImpactNote.textContent = entityLabel
            ? `⚠️ Newly created ${entityLabel} linked to this import will also be deleted.`
            : '⚠️ Newly created records linked to this import will also be deleted.';
    }

    const proceedBtn = modal.querySelector('#rollbackConfirmProceed');
    if (proceedBtn) {
        proceedBtn.onclick = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            onConfirm();
        };
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// ─── Wire up all results-panel event listeners ────────────────────────────────
export function initImportResultsUi() {
    document.getElementById('backToImportBtn')?.addEventListener('click', () => {
        state.latestImportResultsData = null;
        showImportFormView();
    });

    document.getElementById('downloadErrorReportBtn')?.addEventListener('click', downloadErrorReportCsv);

    document.getElementById('proceedToJobFairBtn')?.addEventListener('click',
        () => showToast('You can now continue with the next import.', 'success'));

    document.getElementById('addAccreditationBtn')?.addEventListener('click',
        () => showToast('Use Employers section to add accreditation details.', 'warning'));

    document.getElementById('reviewEmployersBtn')?.addEventListener('click',
        () => showToast('Review newly created employers in the Employers module.', 'success'));

    document.getElementById('submitEmployerAccreditationBtn')?.addEventListener('click', submitEmployerAccreditationFollowup);
    document.getElementById('backToResultsBtn')?.addEventListener('click', () => {
        if (state.pendingEmployerAccreditation) {
            showToast('Complete employer accreditation before leaving this step.', 'warning');
            return;
        }
    });

    document.querySelectorAll('.results-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.dataset.resultsTab) setResultsTab(btn.dataset.resultsTab);
        });
    });

    // Warnings accordion
    const warningsToggle  = document.getElementById('warningsToggleBtn');
    const warningsList    = document.getElementById('warningsList');
    const warningsChevron = document.getElementById('warningsChevron');
    if (warningsToggle && warningsList) {
        warningsToggle.addEventListener('click', () => {
            const isOpen = !warningsList.classList.contains('hidden');
            warningsList.classList.toggle('hidden', isOpen);
            if (warningsChevron) warningsChevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        });
    }

    // Rollback button
    const rollbackBtn = document.getElementById('rollbackImportBtn');
    if (rollbackBtn) {
        rollbackBtn.addEventListener('click', () => {
            if (!state.latestUndoToken) {
                showToast('No rollback available for this import.', 'warning');
                return;
            }
            openRollbackConfirmModal(() => {
                runWithButtonLoading(rollbackBtn, async () => {
                    const res = await fetch('../../backend/import/undo_import.php', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body:    JSON.stringify({ undoToken: state.latestUndoToken }),
                    });
                    const data = JSON.parse(await res.text());
                    if (!res.ok || !data.success) throw new Error(data.error ?? 'Rollback failed.');

                    state.latestUndoToken = null;
                    state.latestImportResultsData = null;
                    showImportFormView();
                    showToast(data.message ?? 'Import rolled back successfully.', 'success');
                }, { label: 'Rolling back…' }).catch(err => {
                    showToast('Rollback failed: ' + (err.message ?? 'Unknown error'), 'error');
                });
            });
        });
    }
}

export function restorePendingEmployerAccreditationView() {
    if (!employerAccreditationView || !employerAccreditationTableBody) return false;

    const raw = sessionStorage.getItem(PENDING_ACCRREDITATION_KEY);
    const embedded = window.__ASCEND_PENDING_EMPLOYER_ACCRREDITATION__;

    const hydrate = (data) => {
        if (!data || !Array.isArray(data.createdEmployers) || data.createdEmployers.length === 0) return false;
        showEmployerAccreditationView(data);
        return true;
    };

    if (raw) {
        try {
            const data = JSON.parse(raw);
            if (hydrate(data)) return true;
        } catch {
            // Fall through to the server-embedded payload.
        }
    }

    if (hydrate(embedded)) {
        return true;
    }

    return false;
}
