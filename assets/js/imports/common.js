import { importStatusStyles, classificationColors } from './config.js';


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
    '_sys_skip', '_parsed_dob',
    'is_new', 'duplicate',
]);

// Columns always shown last in a fixed order
const PINNED_LAST = ['Classification'];

// ─── Import status pill (system concept: new / duplicate / invalid) ──────────
export function importStatusPill(badgeStatus) {
    const key = (badgeStatus ?? '').toLowerCase();
    const style = importStatusStyles[key] ?? importStatusStyles['invalid'];
    return `
        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold ${style.pill}">
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
        dataKeys = dataKeys.filter(k => allowedCols.includes(k));
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
        dataKeys = dataKeys.filter(k => allowedCols.includes(k));
    }

    const pinnedKeys = PINNED_LAST.filter(k => k in firstRow);

    return rows.map(r => {
        const badgeStatus = (r.badge_status ?? '').toLowerCase();
        const style = importStatusStyles[badgeStatus] ?? importStatusStyles['invalid'];

        // Row classes: left border color + background tint + dim if skipped
        const skipped = badgeStatus === 'duplicate' || badgeStatus === 'invalid';
        const rowCls = `${style.row} ${skipped ? 'opacity-60' : 'hover:brightness-95'} transition-all`;

        // Data cells
        const dataCells = dataKeys.map(k => {
            const val = r[k] !== undefined && r[k] !== '' ? r[k] : null;
            const isName = k.toLowerCase().includes('name') || ['fname', 'lname'].includes(k.toLowerCase());
            const textCls = isName ? 'font-semibold text-gray-800' : 'text-gray-600';
            const display = val !== null ? val : ''; // Missing data → show empty cells
            return `<td class="px-4 py-3 ${textCls} whitespace-nowrap max-w-[180px] truncate" title="${val ?? ''}">${display}</td>`;
        }).join('');

        // Pinned columns (Classification as badge)
        const pinnedCells = pinnedKeys.map(k => {
            const val = r[k] ?? '';
            return `<td class="px-4 py-3 whitespace-nowrap">${classificationBadge(val)}</td>`;
        }).join('');

        // Import status pill (always last)
        const statusCell = `<td class="px-4 py-3 whitespace-nowrap">${importStatusPill(r.badge_status)}</td>`;

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

