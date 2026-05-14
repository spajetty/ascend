import { importStatusStyles, classificationColors } from './config.js';

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}


export function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

// Helper to make database keys readable (e.g. 'first_name' -> 'First Name')
export function formatColumnName(str) {
    return str.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

// ─── Classification badge (business status from Excel) ───────────────────────
export function classificationBadge(value) {
    if (!value) return '<span class="text-gray-300 text-xs">—</span>';
    const key = value.trim().toLowerCase();
    const cls = classificationColors[key] ?? 'bg-gray-100 text-gray-600';
    return `<span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold ${cls}">${value}</span>`;
}

// Internal keys that should never appear as data columns
const SKIP_FIELDS = new Set([
    'badge_status', 'status_message',
    '_sys_is_existing', '_sys_user_id', '_sys_benef_id',
    '_sys_skip', '_parsed_dob', '_parsed_start_date',
    '_program_override', '_program_mismatch', '_excel_program',
    '_sys_row_index', '_sys_original_company',
    'is_new', 'duplicate', 'type',
    'suggested_company_name', 'suggested_company_id', 'suggested_company_similarity',
]);

// Columns always shown last in a fixed order
const PINNED_LAST = ['Classification'];

// ─── Import status pill (system concept: new / duplicate / invalid) ──────────
export function importStatusPill(badgeStatus, message = null) {
    const key = (badgeStatus ?? '').toLowerCase();
    const style = importStatusStyles[key] ?? importStatusStyles['invalid'];
    const titleAttr = message ? ` title="${message}"` : '';
    const cursorCls = message ? ' cursor-help' : '';
    return `
        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold ${style.pill}${cursorCls}"${titleAttr}>
            <span>${style.icon}</span>
            <span>${style.label}</span>
        </span>`;
}

// Generates dynamic table headers based on the first row's keys
export function previewTableHeaders(firstRow, allowedCols = null) {
    if (!firstRow) return '';

    let dataKeys = Object.keys(firstRow)
        .filter(k => !SKIP_FIELDS.has(k) && !PINNED_LAST.includes(k));

    if (allowedCols) {
        const allowedLower = allowedCols.map(c => c.toLowerCase());
        dataKeys = dataKeys.filter(k => allowedLower.includes(k.toLowerCase()));
    }

    const pinnedKeys = PINNED_LAST.filter(k => k in firstRow);

    const dataCols = dataKeys.map(k => `
        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">
            ${formatColumnName(k)}
        </th>`).join('');

    const pinnedCols = pinnedKeys.map(k => `
        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">
            ${formatColumnName(k)}
        </th>`).join('');

    // Fixed last column for import status
    const statusCol = `
        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">
            Import
        </th>`;

    return dataCols + pinnedCols + statusCol;
}

export function previewTableRows(rows, allowedCols = null) {
    if (!rows || rows.length === 0) return '';

    const firstRow = rows[0];
    let dataKeys = Object.keys(firstRow).filter(k => !SKIP_FIELDS.has(k) && !PINNED_LAST.includes(k));

    if (allowedCols) {
        const allowedLower = allowedCols.map(c => c.toLowerCase());
        dataKeys = dataKeys.filter(k => allowedLower.includes(k.toLowerCase()));
    }

    const pinnedKeys = PINNED_LAST.filter(k => k in firstRow);

    return rows.map(r => {
        const badgeStatus = (r.badge_status ?? '').toLowerCase();
        const hasMismatch = r._program_mismatch === true;
        
        // Use mismatch styling if program mismatch, otherwise use badge status styling
        let style;
        if (hasMismatch) {
            style = {
                row: 'bg-orange-50/60 border-l-4 border-orange-400',
                pill: 'bg-orange-100 text-orange-700',
                icon: '❌',
                label: `Mismatch: ${r._excel_program ?? '?'}`,
            };
        } else {
            style = importStatusStyles[badgeStatus] ?? importStatusStyles['invalid'];
        }

        // Row classes: left border color + background tint + dim if skipped
        const skipped = badgeStatus === 'duplicate' || badgeStatus === 'invalid';
        const rowCls = `${style.row} ${skipped ? 'opacity-60' : 'hover:brightness-95'} transition-all`;

        // Data cells
        const dataCells = dataKeys.map(k => {
            const val = r[k] !== undefined && r[k] !== '' ? r[k] : null;
            const isName = k.toLowerCase().includes('name') || ['fname', 'lname'].includes(k.toLowerCase());
            const isSkillsColumn = ['skills required for the job', 'skills deficiencies'].includes(k.toLowerCase());
            const textCls = isName ? 'font-semibold text-gray-800' : 'text-gray-600';
            const display = val !== null ? val : ''; // Missing data → show empty cells
            const cellCls = isSkillsColumn
                ? 'whitespace-normal max-w-[360px] break-words align-top'
                : 'whitespace-nowrap max-w-[180px] truncate';
            return `<td class="px-4 py-3 ${textCls} ${cellCls}" title="${val ?? ''}">${display}</td>`;
        }).join('');

        // Pinned columns (Classification as badge)
        const pinnedCells = pinnedKeys.map(k => {
            const val = r[k] ?? '';
            return `<td class="px-4 py-3 whitespace-nowrap">${classificationBadge(val)}</td>`;
        }).join('');

        // Import status pill (always last) - show mismatch or regular status
        const statusPill = hasMismatch 
            ? `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold ${style.pill} cursor-help" title="Row program (${r._excel_program}) does not match selected program">
                <span>${style.icon}</span>
                <span>${style.label}</span>
              </span>`
            : importStatusPill(r.badge_status, r.status_message);
        const suggestionButton = (badgeStatus === 'invalid' && r.suggested_company_name)
            ? `<button type="button" class="mt-2 inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors" data-accept-company-suggestion="1" data-preview-row-index="${escapeHtml(r._sys_row_index ?? '')}" title="Use the suggested company and revalidate this preview">
                <span>Accept suggestion</span>
                <span class="font-medium text-emerald-900">${escapeHtml(r.suggested_company_name)}</span>
            </button>`
            : '';
        const statusCell = `<td class="px-4 py-3 whitespace-nowrap">${statusPill}${suggestionButton ? `<div class="mt-2">${suggestionButton}</div>` : ''}</td>`;

        return `<tr class="${rowCls}">${dataCells}${pinnedCells}${statusCell}</tr>`;
    }).join('');
}

// ─── Tab switching ────────────────────────────────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('text-blue-600', 'border-blue-600');
            b.classList.add('text-gray-400', 'border-transparent');
        });
        btn.classList.add('text-blue-600', 'border-blue-600');
        btn.classList.remove('text-gray-400', 'border-transparent');

        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
    });
});

