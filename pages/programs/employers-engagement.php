<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employers Engagement Section';
$pageHeading = 'Employers Engagement Section';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 pt-6">
        <a href="/pages/programs/program.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employment Programs
        </a>
    </div>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <!-- Total Accreditations -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-total-accred">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Accreditations</span>
            </div>

            <!-- New vs Renewed -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div class="flex flex-wrap items-end gap-x-2 gap-y-0.5">
                        <span class="text-2xl font-bold text-gray-800" id="card-new">—</span>
                        <span class="text-xs text-gray-400 mb-1">New</span>
                        <span class="text-lg font-bold text-gray-300 mb-0.5">/</span>
                        <span class="text-2xl font-bold text-gray-800" id="card-renew">—</span>
                        <span class="text-xs text-gray-400 mb-1">Renew</span>
                    </div>
                    <div class="bg-red-100 p-2 rounded-lg shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">New · Renewed Accreditations</span>
            </div>

            <!-- Workers Hired (WHIP) -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-workers-hired">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Workers Hired</span>
            </div>

            <!-- Infrastructure Projects (WHIP) -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-projects">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16M3 21h18M9 7h1m-1 4h1m4-4h1m-1 4h1M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Infrastructure Projects</span>
            </div>

        </div>

        <!-- Employers Accreditation Preview Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Employers Accreditation</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">MONTH</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">ACCREDITATION</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">COMPANY</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">ESTABLISHMENT TYPE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">INDUSTRY</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">CITY/MUNICIPALITY</th>
                        </tr>
                    </thead>
                    <tbody id="accred-tbody">
                        <tr><td colspan="6" class="text-center py-6 text-gray-400 text-sm">Loading…</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-engagement/emp-accreditation.php" class="text-sm text-green-600 hover:text-green-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- WHIP Preview Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Workers Hiring for Infrastructure Projects</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">MONTH</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">PROJECT NAME</th>
                            <th class="text-left px-4 py-3 text-teal-600 font-semibold tracking-wide">MALE</th>
                            <th class="text-left px-4 py-3 text-pink-500 font-semibold tracking-wide">FEMALE</th>
                            <th class="text-left px-4 py-3 text-orange-500 font-semibold tracking-wide">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="whip-tbody">
                        <tr><td colspan="5" class="text-center py-6 text-gray-400 text-sm">Loading…</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-engagement/whip.php" class="text-sm text-orange-600 hover:text-orange-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<script>
// ─── API paths ────────────────────────────────────────────────────────────────
const YEAR       = new Date().getFullYear();
const ACCRED_API = `/backend/emp-engagement/emp-accred/show-employers.php?year=${YEAR}`;
const WHIP_API   = `/backend/emp-engagement/whip/show-whip.php?year=all`;

// Preview shows only the last N rows
const PREVIEW_ROWS = 3;

// ─── Helper: clear a stuck "Loading…" tbody ───────────────────────────────────
function clearLoading(tbodyId, colspan, msg = 'No data available.') {
    document.getElementById(tbodyId).innerHTML =
        `<tr><td colspan="${colspan}" class="text-center py-6 text-gray-400 text-sm">${msg}</td></tr>`;
}

// ─── Boot ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

    const fetchJson = url => fetch(url).then(r => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
    });

    Promise.allSettled([
        fetchJson(ACCRED_API),
        fetchJson(WHIP_API),
    ]).then(([acResult, whResult]) => {

        const acData = (acResult.status === 'fulfilled' && acResult.value.success)
            ? acResult.value.data : null;
        const whData = (whResult.status === 'fulfilled' && whResult.value.success)
            ? whResult.value.data : null;

        // ── Summary Cards ──────────────────────────────────────────────────────
        document.getElementById('card-total-accred').textContent  = acData ? acData.totals.total   : '—';
        document.getElementById('card-new').textContent           = acData ? acData.totals.new      : '—';
        document.getElementById('card-renew').textContent         = acData ? acData.totals.renewed  : '—';
        document.getElementById('card-workers-hired').textContent = whData ? whData.totals.total    : '—';
        document.getElementById('card-projects').textContent      = whData ? whData.totals.projects : '—';

        // ── Preview Tables ─────────────────────────────────────────────────────
        renderAccreditation(acData);
        renderWhip(whData);
    });
});

// ─── Shared helpers ───────────────────────────────────────────────────────────
function escHtml(s) {
    return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function fmtMonth(dateStr) {
    if (!dateStr) return '—';
    const dt = new Date(dateStr);
    if (isNaN(dt)) return '—';
    return dt.toLocaleString('en-US', { month: 'short' }).toUpperCase()
        + ' ' + dt.getFullYear();
}

function computeRowGender(rows) {
    return rows.map(r => {
        const sex  = (r.sex || '').toLowerCase();
        const date = r.date_hired ? new Date(r.date_hired) : null;
        return {
            ...r,
            month:  date ? date.toLocaleString('en-US', { month: 'short' }) : '—',
            year:   date ? date.getFullYear() : '—',
            male:   sex === 'male'   ? 1 : 0,
            female: sex === 'female' ? 1 : 0,
            total:  1,
        };
    });
}

// ─── Employers Accreditation ──────────────────────────────────────────────────
function renderAccreditation(data) {
    const tbody = document.getElementById('accred-tbody');
    if (!data || !data.rows.length) { clearLoading('accred-tbody', 6); return; }

    const rows   = data.rows.slice(-PREVIEW_ROWS);
    const totals = data.totals;

    const estTypeColor = {
        'Manpower':          'text-green-600',
        'Direct (Overseas)': 'text-orange-500',
        'Direct':            'text-blue-500',
    };

    const accredBadge = type => type === 'new'
        ? `<span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">New</span>`
        : `<span class="bg-orange-100 text-orange-600 text-xs font-semibold px-3 py-1 rounded-full">Renew</span>`;

    let html = '';
    rows.forEach(r => {
        const estColor = estTypeColor[r.est_type] ?? 'text-gray-600';
        const mo = fmtMonth(r.date_hired);
        html += `<tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-6 py-3 text-gray-800 font-semibold">${mo}</td>
            <td class="px-4 py-3">${accredBadge(r.accreditation)}</td>
            <td class="px-4 py-3 text-gray-600">${escHtml(r.company_name)}</td>
            <td class="px-4 py-3 ${estColor} font-medium">${escHtml(r.est_type)}</td>
            <td class="px-4 py-3 text-gray-600">${escHtml(r.industry)}</td>
            <td class="px-4 py-3 text-gray-600">${escHtml(r.city)}</td>
        </tr>`;
    });

    html += `<tr class="bg-gray-50 border-t-2 border-gray-200">
        <td class="px-6 py-3 text-gray-800 font-bold text-xs">TOTAL</td>
        <td class="px-4 py-3 text-gray-400">—</td>
        <td class="px-4 py-3 text-gray-400">—</td>
        <td class="px-4 py-3 text-gray-400">—</td>
        <td class="px-4 py-3 text-gray-400">—</td>
        <td class="px-4 py-3">
            <span class="bg-green-200 text-green-800 font-bold text-xs px-3 py-1 rounded-full">${totals.total}</span>
        </td>
    </tr>`;

    tbody.innerHTML = html;
}

// ─── WHIP ─────────────────────────────────────────────────────────────────────
function renderWhip(data) {
    const tbody = document.getElementById('whip-tbody');
    if (!data || !data.rows.length) { clearLoading('whip-tbody', 5); return; }

    const allRows = computeRowGender(data.rows);
    const rows    = allRows.slice(-PREVIEW_ROWS);
    const totals  = data.totals;

    const totM = allRows.reduce((s, r) => s + +r.male,   0);
    const totF = allRows.reduce((s, r) => s + +r.female, 0);
    const totT = totM + totF;

    let html = '';
    rows.forEach(r => {
        html += `<tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-6 py-3 text-gray-800 font-semibold">${(r.month ?? '—').toUpperCase()} ${r.year}</td>
            <td class="px-4 py-3 text-gray-600">${escHtml(r.project_title || '—')}</td>
            <td class="px-4 py-3 text-gray-600">${r.male}</td>
            <td class="px-4 py-3 text-gray-600">${r.female}</td>
            <td class="px-4 py-3 text-gray-700 font-semibold">${r.total}</td>
        </tr>`;
    });

    html += `<tr class="bg-gray-50 border-t-2 border-gray-200">
        <td class="px-6 py-3 text-gray-800 font-bold text-xs">TOTAL</td>
        <td class="px-4 py-3 text-gray-400">—</td>
        <td class="px-4 py-3 text-gray-700 font-semibold">${totM}</td>
        <td class="px-4 py-3 text-gray-700 font-semibold">${totF}</td>
        <td class="px-4 py-3">
            <span class="bg-orange-200 text-orange-700 font-bold text-xs px-3 py-1 rounded-full">${totT}</span>
        </td>
    </tr>`;

    tbody.innerHTML = html;
}
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>