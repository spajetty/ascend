<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Youth Employability Section';
$pageHeading = 'Youth Employability Section';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen overflow-x-hidden">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-4 md:px-8 pt-6">
        <a href="/pages/programs/program.php"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Employment Programs
        </a>
    </div>

    <div class="px-4 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 mb-8">

            <!-- Total Youth Served -->
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-total-youth">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Youth Served</span>
            </div>

            <!-- SPES Participants -->
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-spes-participants">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">SPES Participants</span>
            </div>

            <!-- GIP Interns -->
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-gip-interns">—</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">GIP Interns</span>
            </div>

            <!-- Work Immersion Participants -->
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-wimm-participants">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Work Immersion Participants</span>
            </div>

            <!-- Total Placed -->
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-xl md:text-2xl font-bold text-gray-800" id="card-total-placed">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Hired / Placed</span>
            </div>

        </div>

        <!-- SPES Preview Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Special Program for Employment of Students (SPES)</h2>
            </div>
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">MONTH REPORTED</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">EMPLOYER</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">START OF CONTRACT</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">END OF CONTRACT</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">DAYS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-600 font-semibold border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-500 font-semibold border-l border-gray-100">PLACED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold border-l border-gray-100">SPES BABY</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-400 font-semibold border-l border-gray-100">4PS BENEFICIARIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold border-l border-gray-100">PWD</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-green-600">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-teal-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-purple-400">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-pink-400">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-red-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="spes-tbody">
                        <tr><td colspan="26" class="text-center py-6 text-gray-400 text-sm">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-4 md:px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/youth-emp/spes.php" class="text-sm text-green-600 hover:text-green-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- GIP Preview Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Government Internship Program (GIP)</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Left: DOLE -->
                <div class="overflow-x-auto bg-white rounded-xl shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-100 bg-white">
                        <h3 class="font-semibold text-sm text-gray-700">DOLE</h3>
                    </div>
                    <div class="overflow-x-auto" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="text-left px-4 py-2 text-gray-500 font-medium">MONTH</th>
                                    <th class="text-left px-4 py-2 text-gray-500 font-medium">OFFICE</th>
                                    <th class="text-left px-4 py-2 text-gray-500 font-medium">SCHOOL</th>
                                    <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold border-l border-gray-100">PARTICIPANTS</th>
                                </tr>
                                <tr class="border-b border-gray-100 bg-gray-50">
                                    <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-teal-600">T</th>
                                </tr>
                            </thead>
                            <tbody id="gipLeftBody">
                                <tr><td colspan="6" class="text-center py-6 text-gray-400 text-sm">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: LGU -->
                <div class="overflow-x-auto bg-white rounded-xl shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-100 bg-white">
                        <h3 class="font-semibold text-sm text-gray-700">LGU</h3>
                    </div>
                    <div class="overflow-x-auto" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="text-left px-4 py-2 text-gray-500 font-medium">MONTH</th>
                                    <th class="text-left px-4 py-2 text-gray-500 font-medium">OFFICE</th>
                                    <th class="text-left px-4 py-2 text-gray-500 font-medium">SCHOOL</th>
                                    <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold border-l border-gray-100">PARTICIPANTS</th>
                                </tr>
                                <tr class="border-b border-gray-100 bg-gray-50">
                                    <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-teal-600">T</th>
                                </tr>
                            </thead>
                            <tbody id="gipRightBody">
                                <tr><td colspan="6" class="text-center py-6 text-gray-400 text-sm">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex justify-end px-4 md:px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/youth-emp/gip.php" class="text-sm text-teal-600 hover:text-teal-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- Work Immersion & Internship Referral Preview Table -->
        <!-- WIMM rows are grouped by month/batch, so only PERIOD column + stat columns -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-4 md:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800 text-sm md:text-base leading-tight">Work Immersion &amp; Internship Referral Program</h2>
            </div>
            <div class="overflow-x-auto [&::-webkit-scrollbar]:h-[4px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <!-- Single info column: period (month + year) -->
                            <th class="text-left px-4 py-2 text-gray-500 font-medium" rowspan="2">PERIOD</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-500 font-semibold border-l border-gray-100">PARTICIPANTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">INQUIRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-500 font-semibold border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-500 font-semibold border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold border-l border-gray-100">PESO-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold border-l border-gray-100">PRIVATE-ACCEPTED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold border-l border-gray-100">NOT PROCEEDED</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-orange-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-teal-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-purple-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-pink-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-2 py-1 text-center text-gray-400 font-medium border-l border-gray-100">M</th><th class="px-2 py-1 text-center text-gray-400 font-medium">F</th><th class="px-2 py-1 text-center font-semibold text-red-400">T</th>
                        </tr>
                    </thead>
                    <tbody id="wimm-tbody">
                        <!-- 1 info col + 7 groups × 3 = 22 columns total -->
                        <tr><td colspan="22" class="text-center py-6 text-gray-400 text-sm">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-4 md:px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/youth-emp/work-imm.php" class="text-sm text-orange-600 hover:text-orange-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<script>
// ─── API paths ────────────────────────────────────────────────────────────────
// FIX: point to the actual show-*.php files under /backend/youth-employ/
const YEAR     = new Date().getFullYear();
const SPES_API = `/backend/youth-employ/spes/show-spes.php?year=${YEAR}`;
const GIP_API  = `/backend/youth-employ/gip/show-gip.php?year=${YEAR}`;
const WIMM_API = `/backend/youth-employ/work-imm/show-work-imm.php?year=${YEAR}`;

// Preview shows only the last N rows
const PREVIEW_ROWS = 3;
// Month labels used by GIP preview
const MONTH_NAMES = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

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
        fetchJson(SPES_API),
        fetchJson(GIP_API),
        fetchJson(WIMM_API),
    ]).then(([spesResult, gipResult, wimmResult]) => {

        const spesData = (spesResult.status === 'fulfilled' && spesResult.value.success)
            ? spesResult.value.data : null;
        const gipData  = (gipResult.status  === 'fulfilled' && gipResult.value.success)
            ? gipResult.value.data  : null;
        const wimmData = (wimmResult.status === 'fulfilled' && wimmResult.value.success)
            ? wimmResult.value.data : null;

        // ── Summary Cards ──────────────────────────────────────────────────────
        const spesPart   = spesData ? spesData.totals.registered    : 0;
        const spesPlaced = spesData ? spesData.totals.placed        : 0;
        // GIP totals: tolerant to new API shape.
        const sumMF = obj => { if (!obj) return 0; return (+obj.m || 0) + (+obj.f || 0); };
        const gipTotalsFromData = d => {
            const totals = d && d.totals ? d.totals : {};
            const result = { participants: 0, placed: 0 };

            if (totals.participants) {
                result.participants = sumMF(totals.participants);
            } else if (totals.lgu || totals.dole) {
                result.participants = sumMF(totals.lgu && totals.lgu.participants) + sumMF(totals.dole && totals.dole.participants);
            } else if (Array.isArray(d && d.rows)) {
                result.participants = (d.rows || []).reduce((s, r) => s + (+r.part_m || 0) + (+r.part_f || 0), 0);
            }

            if (totals.peso || totals.private) {
                result.placed = sumMF(totals.peso) + sumMF(totals.private);
            } else if (totals.lgu || totals.dole) {
                result.placed = sumMF(totals.lgu && totals.lgu.peso) + sumMF(totals.lgu && totals.lgu.private)
                              + sumMF(totals.dole && totals.dole.peso) + sumMF(totals.dole && totals.dole.private);
            } else if (Array.isArray(d && d.rows)) {
                result.placed = (d.rows || []).reduce((s, r) => s + (+r.peso_m || 0) + (+r.peso_f || 0) + (+r.priv_m || 0) + (+r.priv_f || 0), 0);
            }

            return result;
        };

        const gipSummary = gipData ? gipTotalsFromData(gipData) : { participants: 0, placed: 0 };
        const gipPart    = gipSummary.participants;
        const gipPlaced  = gipSummary.placed;
        const wimmPart   = wimmData ? wimmData.totals.part_total    : 0;
        const wimmPlaced = wimmData ? (wimmData.totals.peso_total + wimmData.totals.priv_total) : 0;

        document.getElementById('card-total-youth').textContent      = spesPart + gipPart + wimmPart;
        document.getElementById('card-spes-participants').textContent = spesData ? spesPart : '—';
        document.getElementById('card-gip-interns').textContent      = gipData  ? gipPart  : '—';
        document.getElementById('card-wimm-participants').textContent = wimmData ? wimmPart : '—';
        document.getElementById('card-total-placed').textContent      = spesPlaced + gipPlaced + wimmPlaced;

        // ── Preview Tables ─────────────────────────────────────────────────────
        renderSpes(spesData);
        renderGip(gipData);
        renderWimm(wimmData);
    });
});

// ─── Shared helpers ───────────────────────────────────────────────────────────
function escHtml(s) {
    return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}
function t(v)  { return `<td class="px-2 py-2 text-center text-gray-600">${v ?? 0}</td>`; }
function tL(v) { return `<td class="px-2 py-2 text-center text-gray-600 border-l border-gray-100">${v ?? 0}</td>`; }
function tTotal(v, color, bg) {
    return `<td class="px-2 py-2 text-center font-semibold ${color} ${bg}">${v ?? 0}</td>`;
}
function badge(type) {
    if (!type) return '—';
    const lc = String(type).toLowerCase();
    if (lc === 'college') return `<span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span>`;
    if (lc === 'shs')     return `<span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">SHS</span>`;
    return escHtml(type);
}

// ─── SPES ─────────────────────────────────────────────────────────────────────
function renderSpes(data) {
    const tbody = document.getElementById('spes-tbody');
    if (!data || !data.rows.length) { clearLoading('spes-tbody', 26); return; }

    const rows   = data.rows.slice(-PREVIEW_ROWS);
    const totals = data.totals;
    let html = '';

    rows.forEach(r => {
        const regT    = +r.reg_m       + +r.reg_f;
        const refT    = +r.ref_m       + +r.ref_f;
        const plcT    = +r.placed_m    + +r.placed_f;
        const vacT    = +r.vac_total;
        const babyT   = +r.spes_baby_m + +r.spes_baby_f;
        const fourpsT = +r.fourps_m    + +r.fourps_f;
        const pwdT    = +r.pwd_m       + +r.pwd_f;

        html += `<tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-4 py-2 text-gray-700 font-semibold">${escHtml(r.month_reported)}</td>
            <td class="px-4 py-2 text-gray-600">${escHtml(r.employer)}</td>
            <td class="px-4 py-2 text-gray-600">${escHtml(r.start_of_contract)}</td>
            <td class="px-4 py-2 text-gray-600">${escHtml(r.end_of_contract)}</td>
            <td class="px-4 py-2 font-semibold text-gray-700">${r.days ?? '—'}</td>
            ${tL(r.reg_m)}${t(r.reg_f)}${tTotal(regT,    'text-green-600', 'bg-green-50')}
            ${tL(r.ref_m)}${t(r.ref_f)}${tTotal(refT,    'text-blue-500',  'bg-blue-50')}
            ${tL(r.placed_m)}${t(r.placed_f)}${tTotal(plcT, 'text-teal-500', 'bg-teal-50')}
            ${tL(r.vac_m)}${t(r.vac_f)}${tTotal(vacT,    'text-orange-400','bg-orange-50')}
            ${tL(r.spes_baby_m)}${t(r.spes_baby_f)}${tTotal(babyT,  'text-purple-400','bg-purple-50')}
            ${tL(r.fourps_m)}${t(r.fourps_f)}${tTotal(fourpsT,'text-pink-400',  'bg-pink-50')}
            ${tL(r.pwd_m)}${t(r.pwd_f)}${tTotal(pwdT,    'text-red-400',   'bg-red-50')}
        </tr>`;
    });

    html += `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-2 text-gray-800 font-bold" colspan="5">TOTAL</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-green-600 bg-green-100 border-l border-gray-100">${totals.registered}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-blue-500 bg-blue-100 border-l border-gray-100">${totals.referred}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-teal-500 bg-teal-100 border-l border-gray-100">${totals.placed}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-orange-400 bg-orange-100 border-l border-gray-100">${totals.vacancies}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-purple-400 bg-purple-100 border-l border-gray-100">${totals.spes_baby}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-pink-400 bg-pink-100 border-l border-gray-100">${totals.fourps}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-red-400 bg-red-100 border-l border-gray-100">${totals.pwd}</td>
    </tr>`;

    tbody.innerHTML = html;
}

// ─── GIP ──────────────────────────────────────────────────────────────────────
function renderGip(data) {
    const tbody = document.getElementById('gip-tbody');
    if (!data || !data.rows || !data.rows.length) { clearLoading('gip-tbody', 8); return; }
    const rows = data.rows.slice(-PREVIEW_ROWS);
    let html = '';

    // group rows by type across all returned rows
    const allRows = data.rows || [];
    const lguRows = allRows.filter(r => String((r.gip_type || r.type || '')).toLowerCase() !== 'dole');
    const doleRows = allRows.filter(r => String((r.gip_type || r.type || '')).toLowerCase() === 'dole');

    const buildRowsHtml = list => {
        if (!list.length) return `<tr><td colspan="6" class="text-center py-6 text-gray-400 text-sm">No data</td></tr>`;
        return list.map(r => {
            const partT = (+r.part_m || 0) + (+r.part_f || 0);
            const monthLabel = MONTH_NAMES[+r.month_num || +r.month || 0] || '—';
            return `<tr class="border-b border-gray-50 hover:bg-gray-50">
                <td class="px-4 py-2 text-gray-700 font-semibold">${escHtml(monthLabel)}</td>
                <td class="px-4 py-2 text-gray-600">${escHtml(r.office_assignment || '—')}</td>
                <td class="px-4 py-2 text-gray-600">${escHtml(r.school || '—')}</td>
                ${tL(r.part_m)}${t(r.part_f)}${tTotal(partT, 'text-teal-600', 'bg-teal-50')}
            </tr>`;
        }).join('');
    };

    // show the last PREVIEW_ROWS per section
    const dolePreview = doleRows.slice(-PREVIEW_ROWS);
    const lguPreview = lguRows.slice(-PREVIEW_ROWS);

    document.getElementById('gipLeftBody').innerHTML = buildRowsHtml(dolePreview);
    document.getElementById('gipRightBody').innerHTML = buildRowsHtml(lguPreview);

    // per-section totals (use backend totals when available)
    const lt = data.totals && data.totals.lgu ? data.totals.lgu : lguRows.reduce((s,r)=>({ m: s.m + (+r.part_m||0), f: s.f + (+r.part_f||0) }), { m:0,f:0 });
    const dt = data.totals && data.totals.dole ? data.totals.dole : doleRows.reduce((s,r)=>({ m: s.m + (+r.part_m||0), f: s.f + (+r.part_f||0) }), { m:0,f:0 });

    // append totals row to each tbody
    const leftTotalRow = `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-2 text-gray-800 font-bold" colspan="3">TOTAL</td>
        <td class="px-1.5 py-3 text-center text-gray-700 border-l border-gray-100">${dt.m}</td>
        <td class="px-1.5 py-3 text-center text-gray-700">${dt.f}</td>
        <td class="px-1.5 py-3 text-center font-bold text-teal-600">${dt.m + dt.f}</td>
    </tr>`;
    const rightTotalRow = `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-2 text-gray-800 font-bold" colspan="3">TOTAL</td>
        <td class="px-1.5 py-3 text-center text-gray-700 border-l border-gray-100">${lt.m}</td>
        <td class="px-1.5 py-3 text-center text-gray-700">${lt.f}</td>
        <td class="px-1.5 py-3 text-center font-bold text-teal-600">${lt.m + lt.f}</td>
    </tr>`;

    document.getElementById('gipLeftBody').innerHTML += leftTotalRow;
    document.getElementById('gipRightBody').innerHTML += rightTotalRow;
}

// ─── Work Immersion ───────────────────────────────────────────────────────────
// show-work-imm.php returns rows grouped by batch/month.
// Each row has: period, part_m/f/total, inq_m/f/total, ref_m/f/total,
//               int_m/f/total, peso_m/f/total, priv_m/f/total, notpr_m/f/total
// FIX: was using GIP field names (contract_period, school, etc.) which don't exist here.
function renderWimm(data) {
    const tbody = document.getElementById('wimm-tbody');
    // 1 info col + 7 groups × 3 = 22 columns
    if (!data || !data.rows.length) { clearLoading('wimm-tbody', 22); return; }

    const rows   = data.rows.slice(-PREVIEW_ROWS);
    const totals = data.totals;
    let html = '';

    rows.forEach(r => {
        html += `<tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-4 py-2 text-gray-700 font-semibold">${escHtml(r.period)}</td>
            ${tL(r.part_m)}${t(r.part_f)}${tTotal(r.part_total,   'text-orange-500','bg-orange-50')}
            ${tL(r.inq_m)}${t(r.inq_f)}${tTotal(r.inq_total,     'text-blue-500',  'bg-blue-50')}
            ${tL(r.ref_m)}${t(r.ref_f)}${tTotal(r.ref_total,     'text-teal-500',  'bg-teal-50')}
            ${tL(r.int_m)}${t(r.int_f)}${tTotal(r.int_total,     'text-purple-500','bg-purple-50')}
            ${tL(r.peso_m)}${t(r.peso_f)}${tTotal(r.peso_total,  'text-pink-500',  'bg-pink-50')}
            ${tL(r.priv_m)}${t(r.priv_f)}${tTotal(r.priv_total,  'text-green-500', 'bg-green-50')}
            ${tL(r.notpr_m)}${t(r.notpr_f)}${tTotal(r.notpr_total,'text-red-400',  'bg-red-50')}
        </tr>`;
    });

    html += `<tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
        <td class="px-4 py-2 text-gray-800 font-bold">TOTAL</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-orange-500 bg-orange-100 border-l border-gray-100">${totals.part_total}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-blue-500 bg-blue-100 border-l border-gray-100">${totals.inq_total}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-teal-500 bg-teal-100 border-l border-gray-100">${totals.ref_total}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-purple-500 bg-purple-100 border-l border-gray-100">${totals.int_total}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-pink-500 bg-pink-100 border-l border-gray-100">${totals.peso_total}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-green-500 bg-green-100 border-l border-gray-100">${totals.priv_total}</td>
        <td colspan="3" class="px-2 py-2 text-center font-bold text-red-400 bg-red-100 border-l border-gray-100">${totals.notpr_total}</td>
    </tr>`;

    tbody.innerHTML = html;
}
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>