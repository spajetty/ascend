<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employment Facilitation Section';
$pageHeading = 'Employment Facilitation Section';

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

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-total-users">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Registered (Job Match + FTJS)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-total-employers">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Employers (Job Fair)</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-yellow-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-total-vacancies">—</span>
                    <div class="bg-yellow-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Job Fair Vacancies</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800" id="card-total-ftjs">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total First Time Job Seekers</span>
            </div>

        </div>

        <!-- Job Matching & Referral Preview Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Job Matching & Referral</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-teal-600">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="jobmatch-tbody">
                        <tr><td colspan="22" class="text-center py-6 text-gray-400 text-sm">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/job-match.php" class="text-sm text-teal-600 hover:text-teal-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- First Time Job Seekers Preview Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">First Time Job Seekers</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold tracking-wide border-l border-gray-100">JOB SEEKERS</th>
                            <th class="px-2 py-2 text-center text-teal-500 font-semibold tracking-wide border-l border-gray-100" rowspan="2">OCC. PERMIT</th>
                            <th class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100" rowspan="2">HEALTH CARD</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-pink-500">T</th>
                            <th class="px-3 py-1 border-l border-gray-100"></th><!-- spacer for OCC PERMIT rowspan -->
                            <th class="px-3 py-1 border-l border-gray-100"></th><!-- spacer for HEALTH CARD rowspan -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="firsttime-tbody">
                        <tr><td colspan="24" class="text-center py-6 text-gray-400 text-sm">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/first-time.php" class="text-sm text-purple-600 hover:text-purple-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- Job Fair Preview Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Job Fair</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-24" rowspan="2">MONTH</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-20" rowspan="2">TYPE</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-44" rowspan="2">DATE</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">PARTICIPATING EMPLOYER</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-indigo-500 font-semibold tracking-wide border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-teal-600">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-indigo-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th><th class="px-3 py-1 text-center text-gray-500 font-medium">F</th><th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="jobfair-tbody">
                        <tr><td colspan="28" class="text-center py-6 text-gray-400 text-sm">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/job-fair.php" class="text-sm text-blue-600 hover:text-blue-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<script>
// ─── API paths ────────────────────────────────────────────────────────────────
const YEAR           = new Date().getFullYear();
const JOB_MATCH_API  = `/backend/emp-facilitation/show-job-match.php?year=${YEAR}`;
const FIRST_TIME_API = `/backend/emp-facilitation/show-first-time.php?year=${YEAR}`;
const JOB_FAIR_API   = `/backend/emp-facilitation/show-job-fair.php?year=${YEAR}`;

// Preview shows only the last N rows (most recent months)
const PREVIEW_ROWS = 3;

// ─── Helper: clear a stuck "Loading…" tbody with an error/empty message ───────
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
        fetchJson(JOB_MATCH_API),
        fetchJson(FIRST_TIME_API),
        fetchJson(JOB_FAIR_API),
    ]).then(([jmResult, ftResult, jfResult]) => {

        // Unwrap each result — null on failure or API success=false
        const jmData = (jmResult.status === 'fulfilled' && jmResult.value.success)
            ? jmResult.value.data : null;
        const ftData = (ftResult.status === 'fulfilled' && ftResult.value.success)
            ? ftResult.value.data : null;
        const jfData = (jfResult.status === 'fulfilled' && jfResult.value.success)
            ? jfResult.value.data : null;

        // ── Summary Cards ──────────────────────────────────────────────────────
        const jmRegistered = jmData ? jmData.totals.registered : 0;
        const ftJobseekers = ftData ? ftData.totals.jobseekers : 0;
        document.getElementById('card-total-users').textContent      = jmRegistered + ftJobseekers;
        document.getElementById('card-total-employers').textContent  = jfData ? jfData.totals.employers     : '—';
        document.getElementById('card-total-vacancies').textContent  = jfData ? jfData.totals.job_vacancies : '—';
        document.getElementById('card-total-ftjs').textContent       = ftData ? ftJobseekers                : '—';

        // ── Preview Tables ─────────────────────────────────────────────────────
        renderJobMatch(jmData);
        renderFirstTime(ftData);
        renderJobFair(jfData);
    });
});

// ─── Helpers ──────────────────────────────────────────────────────────────────
function escHtml(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function t(v)  { return `<td class="px-3 py-2 text-center text-gray-600">${v}</td>`; }
function tL(v) { return `<td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">${v}</td>`; }
function tTotal(v, color, bg) {
    return `<td class="px-3 py-2 text-center font-semibold ${color} ${bg}">${v}</td>`;
}

// ─── Job Matching & Referral ──────────────────────────────────────────────────
function renderJobMatch(data) {
    const tbody = document.getElementById('jobmatch-tbody');
    if (!data || !data.rows.length) {
        clearLoading('jobmatch-tbody', 22);
        return;
    }

    const rows   = data.rows.slice(-PREVIEW_ROWS);
    const totals = data.totals;
    let html = '';

    rows.forEach(r => {
        const regT  = +r.reg_m    + +r.reg_f;
        const refT  = +r.ref_m    + +r.ref_f;
        const intT  = +r.int_m    + +r.int_f;
        const qualT = +r.qual_m   + +r.qual_f;
        const nqT   = +r.nqual_m  + +r.nqual_f;
        const plcT  = +r.placed_m + +r.placed_f;
        const ffiT  = +r.ffi_m    + +r.ffi_f;

        html += `<tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-4 py-2 text-gray-700 font-medium">${escHtml(r.month)}</td>
            ${tL(r.reg_m)}${t(r.reg_f)}${tTotal(regT,'text-teal-600','bg-teal-50')}
            ${tL(r.ref_m)}${t(r.ref_f)}${tTotal(refT,'text-blue-500','bg-blue-50')}
            ${tL(r.int_m)}${t(r.int_f)}${tTotal(intT,'text-cyan-500','bg-cyan-50')}
            ${tL(r.qual_m)}${t(r.qual_f)}${tTotal(qualT,'text-green-500','bg-green-50')}
            ${tL(r.nqual_m)}${t(r.nqual_f)}${tTotal(nqT,'text-red-400','bg-red-50')}
            ${tL(r.placed_m)}${t(r.placed_f)}${tTotal(plcT,'text-orange-400','bg-orange-50')}
            ${tL(r.ffi_m)}${t(r.ffi_f)}${tTotal(ffiT,'text-purple-400','bg-purple-50')}
        </tr>`;
    });

    html += `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-2 text-gray-800 font-bold">TOTAL</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-teal-600 bg-teal-100 border-l border-gray-100">${totals.registered}</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-blue-500 bg-blue-100 border-l border-gray-100">${totals.referred}</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-cyan-500 bg-cyan-100 border-l border-gray-100">${totals.interviewed}</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-green-500 bg-green-100 border-l border-gray-100">—</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-red-400 bg-red-100 border-l border-gray-100">—</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-orange-400 bg-orange-100 border-l border-gray-100">${totals.placed}</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-purple-400 bg-purple-100 border-l border-gray-100">—</td>
    </tr>`;

    tbody.innerHTML = html;
}

// ─── First Time Job Seekers ───────────────────────────────────────────────────
function renderFirstTime(data) {
    const tbody = document.getElementById('firsttime-tbody');
    if (!data || !data.rows.length) {
        clearLoading('firsttime-tbody', 24);
        return;
    }

    const rows   = data.rows.slice(-PREVIEW_ROWS);
    const totals = data.totals;
    let html = '';

    rows.forEach(r => {
        const seekT = +r.reg_m    + +r.reg_f;
        const intT  = +r.int_m    + +r.int_f;
        const qualT = +r.qual_m   + +r.qual_f;
        const nqT   = +r.nqual_m  + +r.nqual_f;
        const plcT  = +r.placed_m + +r.placed_f;
        const ffiT  = +r.ffi_m    + +r.ffi_f;

        html += `<tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-4 py-2 text-gray-700 font-medium">${escHtml(r.month)} ${r.year}</td>
            ${tL(r.reg_m)}${t(r.reg_f)}${tTotal(seekT,'text-pink-500','bg-pink-50')}
            ${tL(r.occ_permit)}
            ${tL(r.health_card)}
            ${tL(r.int_m)}${t(r.int_f)}${tTotal(intT,'text-cyan-500','bg-cyan-50')}
            ${tL(r.qual_m)}${t(r.qual_f)}${tTotal(qualT,'text-green-500','bg-green-50')}
            ${tL(r.nqual_m)}${t(r.nqual_f)}${tTotal(nqT,'text-red-400','bg-red-50')}
            ${tL(r.placed_m)}${t(r.placed_f)}${tTotal(plcT,'text-orange-400','bg-orange-50')}
            ${tL(r.ffi_m)}${t(r.ffi_f)}${tTotal(ffiT,'text-purple-400','bg-purple-50')}
        </tr>`;
    });

    html += `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-2 text-gray-800 font-bold">TOTAL</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-pink-500 bg-pink-100 border-l border-gray-100">${totals.jobseekers}</td>
        <td class="px-3 py-2 text-center font-bold text-teal-500 bg-teal-100 border-l border-gray-100">${totals.occ_permit}</td>
        <td class="px-3 py-2 text-center font-bold text-blue-500 bg-blue-100 border-l border-gray-100">${totals.health_card}</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-cyan-500 bg-cyan-100 border-l border-gray-100">—</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-green-500 bg-green-100 border-l border-gray-100">—</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-red-400 bg-red-100 border-l border-gray-100">—</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-orange-400 bg-orange-100 border-l border-gray-100">${totals.placed}</td>
        <td colspan="3" class="px-3 py-2 text-center font-bold text-purple-400 bg-purple-100 border-l border-gray-100">—</td>
    </tr>`;

    tbody.innerHTML = html;
}

// ─── Job Fair ─────────────────────────────────────────────────────────────────
function renderJobFair(data) {
    const tbody = document.getElementById('jobfair-tbody');
    if (!data || !data.rows.length) {
        clearLoading('jobfair-tbody', 28);
        return;
    }

    // Show only latest N job fair rows on summary page
    const PREVIEW_JOB_FAIR_ROWS = 5;

    const allRows = data.rows;
    const totals  = data.totals;

    // Collect unique month keys in order, then take the last PREVIEW_ROWS months
    const seenMonths = [];
    allRows.forEach(r => {
        const key = `${r.month} ${r.year}`;
        if (!seenMonths.includes(key)) seenMonths.push(key);
    });

    const previewMonths = new Set(seenMonths.slice(-PREVIEW_ROWS));

    // LIMIT FINAL DISPLAYED ROWS
    const rows = allRows
        .filter(r => previewMonths.has(`${r.month} ${r.year}`))
        .slice(-PREVIEW_JOB_FAIR_ROWS);

    // Two-line date formatter
    const fmtDate = d => {
        if (!d) return '';
        const dt = new Date(d);
        return isNaN(dt)
            ? d
            : dt.toLocaleDateString('en-US', {
                  month: 'long',
                  day: 'numeric',
                  year: 'numeric'
              });
    };

    const dateHtml = (start, end) => {
        if (!start) return '—';

        const startLine =
            `<span class="block">
                <span class="font-medium text-gray-600">Start:</span>
                ${fmtDate(start)}
            </span>`;

        if (!end || end === start) return startLine;

        return startLine +
            `<span class="block">
                <span class="font-medium text-gray-600">End:</span>
                ${fmtDate(end)}
            </span>`;
    };

    // Type badge
    const typeBadge = type => {
        const isLocal = String(type).toUpperCase().includes('LOCAL');

        const cls = isLocal
            ? 'bg-teal-100 text-teal-700'
            : 'bg-purple-100 text-purple-700';

        const label = isLocal ? 'LOCAL' : 'OVERSEAS';

        return `
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${cls}">
                ${label}
            </span>
        `;
    };

    // Group: month → event → employers
    const monthMap = new Map();

    rows.forEach(r => {
        const monthKey = `${r.month} ${r.year}`;

        if (!monthMap.has(monthKey)) {
            monthMap.set(monthKey, { events: new Map() });
        }

        const events = monthMap.get(monthKey).events;
        const eventKey = String(r.jobfairevent_id);

        if (!events.has(eventKey)) {
            events.set(eventKey, {
                meta: r,
                employers: []
            });
        }

        events.get(eventKey).employers.push(r);
    });

    let html = '';

    monthMap.forEach((monthData, monthKey) => {

        // Total rowspan for this month
        let monthRowspan = 0;

        monthData.events.forEach(ev => {
            monthRowspan += ev.employers.length;
        });

        let firstMonthRow = true;

        monthData.events.forEach(eventData => {

            const eventRowspan = eventData.employers.length;
            let firstEventRow = true;

            eventData.employers.forEach(r => {

                const monthCell = firstMonthRow
                    ? `
                        <td class="px-4 py-3 font-bold text-sm text-teal-700 align-top bg-teal-50/40 border-r border-gray-100"
                            rowspan="${monthRowspan}">
                            ${escHtml(monthKey)}
                        </td>
                    `
                    : '';

                const eventCells = firstEventRow
                    ? `
                        <td class="px-3 py-2 align-top border-r border-gray-100"
                            rowspan="${eventRowspan}">
                            ${typeBadge(r.job_fair_type)}
                        </td>

                        <td class="px-3 py-2 text-gray-500 align-top text-xs leading-relaxed border-r border-gray-100"
                            rowspan="${eventRowspan}">
                            ${dateHtml(r.date_start, r.date_end)}
                        </td>
                    `
                    : '';

                html += `
                    <tr class="border-b border-gray-50 hover:bg-gray-50">

                        ${monthCell}
                        ${eventCells}

                        <td class="px-4 py-2 text-gray-600">
                            ${escHtml(r.company_name)}
                        </td>

                        ${tL(r.vacancy_male)}
                        ${t(r.vacancy_female)}
                        ${tTotal(r.vacancy_total,'text-blue-500','bg-blue-50')}

                        ${tL(r.reg_m)}
                        ${t(r.reg_f)}
                        ${tTotal(r.reg_total,'text-teal-600','bg-teal-50')}

                        ${tL(r.ref_m)}
                        ${t(r.ref_f)}
                        ${tTotal(r.ref_total,'text-indigo-500','bg-indigo-50')}

                        ${tL(r.int_m)}
                        ${t(r.int_f)}
                        ${tTotal(r.int_total,'text-cyan-500','bg-cyan-50')}

                        ${tL(r.qual_m)}
                        ${t(r.qual_f)}
                        ${tTotal(r.qual_total,'text-green-500','bg-green-50')}

                        ${tL(r.nqual_m)}
                        ${t(r.nqual_f)}
                        ${tTotal(r.nqual_total,'text-red-400','bg-red-50')}

                        ${tL(r.placed_m)}
                        ${t(r.placed_f)}
                        ${tTotal(r.placed_total,'text-orange-400','bg-orange-50')}

                        ${tL(r.ffi_m)}
                        ${t(r.ffi_f)}
                        ${tTotal(r.ffi_total,'text-purple-400','bg-purple-50')}

                    </tr>
                `;

                firstMonthRow = false;
                firstEventRow = false;
            });
        });
    });

    html += `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-2 text-gray-800 font-bold" colspan="4">TOTALS</td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-blue-500 bg-blue-100 border-l border-gray-100">
            ${data.grandTotals.vacancy_total}
        </td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-teal-600 bg-teal-100 border-l border-gray-100">
            ${data.grandTotals.reg_total}
        </td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-indigo-500 bg-indigo-100 border-l border-gray-100">
            ${data.grandTotals.ref_total}
        </td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-cyan-500 bg-cyan-100 border-l border-gray-100">
            ${data.grandTotals.int_total}
        </td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-green-500 bg-green-100 border-l border-gray-100">
            ${data.grandTotals.qual_total}
        </td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-red-400 bg-red-100 border-l border-gray-100">
            ${data.grandTotals.nqual_total}
        </td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-orange-400 bg-orange-100 border-l border-gray-100">
            ${data.grandTotals.placed_total}
        </td>

        <td colspan="3" class="px-3 py-2 text-center font-bold text-purple-400 bg-purple-100 border-l border-gray-100">
            ${data.grandTotals.ffi_total}
        </td>
    </tr>`;

    tbody.innerHTML = html;
}
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>