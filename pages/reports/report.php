<!-- report.php -->
<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'reports';
$pageTitle   = 'ASCEND PED System – Reports';
$pageHeading = 'Reports';

/* ═══════════════════════════════════════════════════════════════════
 * Load cached dashboard data from fetch-details.json
 * Same pattern as dashboard.php — falls back to safe defaults.
 * ═══════════════════════════════════════════════════════════════════ */
$cachePath = __DIR__ . '/../../cache/fetch-details.json';
$cacheData = [];

if (file_exists($cachePath)) {
    $decoded = json_decode(file_get_contents($cachePath), true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $cacheData = $decoded;
    }
}

/* ── Helper: safely dig into the nested array ── */
function cacheVal(array $data, ...$keys): int {
    $current = $data;
    foreach ($keys as $key) {
        if (!isset($current[$key])) return 0;
        $current = $current[$key];
    }
    return (int) $current;
}

/* ── Stat-card values ── */
$totalRegistered = cacheVal($cacheData, 'beneficiaries_totals', 'total_registered');
$totalHired      = cacheVal($cacheData, 'beneficiaries_totals', 'total_hired');
$totalMale       = cacheVal($cacheData, 'beneficiaries_totals', 'total_male');
$totalFemale     = cacheVal($cacheData, 'beneficiaries_totals', 'total_female');
$totalEmployers  = cacheVal($cacheData, 'employers',            'total_employers');
$totalVacancies  = cacheVal($cacheData, 'employers',            'total_vacancies');

/* ── Previous-month delta helpers ── */
$compRows       = $cacheData['comparison_by_month'] ?? [];
$prevRegistered = 0;
$prevHired      = 0;

if (count($compRows) >= 2) {
    $last = end($compRows);
    prev($compRows);
    $prev = current($compRows);

    $prevRegistered = (int) ($prev['total_registered'] ?? 0);
    $prevHired      = (int) ($prev['total_hired']      ?? 0);
}

function pctChange(int $current, int $previous): string {
    if ($previous === 0) return '';
    $change = (($current - $previous) / $previous) * 100;
    $sign   = $change >= 0 ? '+' : '';
    return $sign . number_format($change, 1) . '% ' . ($change >= 0 ? '↑' : '↓') . ' from last month';
}

$registeredBadge = pctChange($totalRegistered, $prevRegistered);
$hiredBadge      = pctChange($totalHired, $prevHired);

/* ── Gender percentages ── */
$grandTotal     = $totalMale + $totalFemale;
$malePct        = $grandTotal > 0 ? round(($totalMale   / $grandTotal) * 100, 1) : 0;
$femalePct      = $grandTotal > 0 ? round(($totalFemale / $grandTotal) * 100, 1) : 0;

/* ── Build program list grouped by section ── */
$programsBySection = [];
foreach ($cacheData['beneficiaries_by_program'] ?? [] as $prog) {
    $sid = (int) $prog['section_id'];
    $programsBySection[$sid][] = $prog;
}

/* ── Section totals for grand total banner ── */
$grandMale   = $totalMale;
$grandFemale = $totalFemale;

/* Compute grand total across ALL sections (benef + special sources) ── */
$allSectionTotal = 0;
foreach ($cacheData['beneficiaries_by_section'] ?? [] as $s) {
    $allSectionTotal += (int) $s['total'];
}

/* ── Monthly chart data ── */
$monthLabels   = [];
$monthReg      = [];
$monthHired    = [];
foreach ($compRows as $row) {
    $monthLabels[] = substr($row['month'], 0, 3); // e.g. "January" → "Jan"
    $monthReg[]    = (int) $row['total_registered'];
    $monthHired[]  = (int) $row['total_hired'];
}
// Limit to last 6 months for the chart
$monthLabels = array_slice($monthLabels, -6);
$monthReg    = array_slice($monthReg,    -6);
$monthHired  = array_slice($monthHired,  -6);

/* ── Section name → tab id map ── */
$sectionTabMap = [
    1 => ['tab' => 'facilitation', 'label' => 'Employment Facilitation'],
    2 => ['tab' => 'employers',    'label' => 'Employers Engagement'],
    3 => ['tab' => 'youth',        'label' => 'Youth Employability'],
    4 => ['tab' => 'career',       'label' => 'Career Development'],
];

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen bg-gray-50">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6">

        <!-- ── Stat Cards ──────────────────────────────────────────── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5 mb-8">

            <!-- Total Registered -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-5 shadow-md flex items-start justify-between">
                <div>
                    <p class="text-xs text-blue-100 mb-2 font-medium uppercase tracking-wide">Total Registered</p>
                    <h3 id="stat-registered" class="text-4xl font-bold text-white"><?= number_format($totalRegistered) ?></h3>
                    <p id="badge-registered" class="text-xs text-blue-100 mt-2 <?= $registeredBadge ? '' : 'invisible' ?>">
                        <?= htmlspecialchars($registeredBadge) ?>
                    </p>
                </div>
                <div class="bg-white/20 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Total Hired -->
            <div class="bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl p-5 shadow-md flex items-start justify-between">
                <div>
                    <p class="text-xs text-amber-100 mb-2 font-medium uppercase tracking-wide">Total Hired</p>
                    <h3 id="stat-hired" class="text-4xl font-bold text-white"><?= number_format($totalHired) ?></h3>
                    <p id="badge-hired" class="text-xs text-amber-100 mt-2 <?= $hiredBadge ? '' : 'invisible' ?>">
                        <?= htmlspecialchars($hiredBadge) ?>
                    </p>
                </div>
                <div class="bg-white/20 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m8 0a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2"/>
                    </svg>
                </div>
            </div>

            <!-- Accredited Employers -->
            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-5 shadow-md flex items-start justify-between">
                <div>
                    <p class="text-xs text-red-100 mb-3 font-medium uppercase tracking-wide">Accredited Employers</p>
                    <h3 id="stat-employers" class="text-4xl font-bold text-white"><?= number_format($totalEmployers) ?></h3>
                </div>
                <div class="bg-white/20 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            <!-- Active Job Vacancies -->
            <div class="bg-white rounded-xl p-5 shadow-md flex items-start justify-between border border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 mb-3 mr-3 font-medium uppercase tracking-wide">Active Job Vacancies</p>
                    <h3 id="stat-vacancies" class="text-4xl font-bold text-gray-800"><?= number_format($totalVacancies) ?></h3>
                </div>
                <div class="bg-blue-600 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Export Report Card -->
            <div class="bg-white rounded-xl p-5 shadow-md border border-gray-100">
                <p class="text-xs text-gray-500 mb-5 font-medium uppercase tracking-wide">Export Report</p>
                <div class="flex items-start gap-2">
                    <input
                        type="month"
                        id="reportMonth"
                        class="w-[75%] text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <button
                        onclick="window.print()"
                        class="flex items-center justify-center w-[20%] h-10 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        title="Print Report"
                    >
                        <svg class="w-5 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h6zm0-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        <!-- ── Charts Row ──────────────────────────────────────────── -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            <!-- Monthly Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-5">Monthly Registration &amp; Hiring Trends</h3>
                <div class="relative h-64">
                    <canvas id="trendChart"></canvas>
                </div>
                <div class="flex justify-center gap-6 mt-4 pt-4 border-t border-gray-100">
                    <span class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="inline-block w-3 h-3 rounded bg-blue-600"></span>Registered
                    </span>
                    <span class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="inline-block w-3 h-3 rounded bg-amber-400"></span>Hired
                    </span>
                </div>
            </div>

            <!-- Gender Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-5">Gender Distribution</h3>
                <div class="flex items-center justify-center mb-5">
                    <div class="relative w-52 h-52">
                        <canvas id="genderChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <p id="gender-total" class="text-2xl font-bold text-gray-800"><?= number_format($grandTotal) ?></p>
                            <p class="text-xs text-gray-500">Total</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a4 4 0 110 8 4 4 0 010-8zm0 10c4.42 0 8 1.79 8 4v1H4v-1c0-2.21 3.58-4 8-4z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Male</p>
                                <p id="gender-male-count" class="text-sm font-bold text-gray-800"><?= number_format($totalMale) ?></p>
                            </div>
                        </div>
                        <p id="gender-male-pct" class="text-lg font-bold text-blue-600"><?= $malePct ?>%</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a4 4 0 110 8 4 4 0 010-8zm0 10c4.42 0 8 1.79 8 4v1H4v-1c0-2.21 3.58-4 8-4z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Female</p>
                                <p id="gender-female-count" class="text-sm font-bold text-gray-800"><?= number_format($totalFemale) ?></p>
                            </div>
                        </div>
                        <p id="gender-female-pct" class="text-lg font-bold text-amber-500"><?= $femalePct ?>%</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ── Program Tables ──────────────────────────────────────── -->

        <!-- Tab navigation -->
        <div class="flex flex-wrap gap-2 mb-4" id="programTabs">
            <?php foreach ($sectionTabMap as $sid => $info):
                $isFirst = $sid === array_key_first($sectionTabMap);
            ?>
            <button onclick="showSection('<?= $info['tab'] ?>')" data-tab="<?= $info['tab'] ?>"
                class="tab-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors <?= $isFirst ? 'bg-blue-600 text-white border-blue-600' : 'text-gray-600 bg-white border-gray-300' ?>">
                <?= $info['label'] ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Program Section Tables (rendered from cache) -->
        <?php foreach ($sectionTabMap as $sid => $info):
            $programs   = $programsBySection[$sid] ?? [];
            $isFirst    = $sid === array_key_first($sectionTabMap);

            /* Compute section totals from cache rows */
            $secMale    = 0;
            $secFemale  = 0;
            $secTotal   = 0;
            foreach ($programs as $p) {
                $secMale   += (int) $p['total_male'];
                $secFemale += (int) $p['total_female'];
                $secTotal  += (int) $p['total'];
            }
        ?>
        <div id="section-<?= $info['tab'] ?>" class="program-section <?= $isFirst ? '' : 'hidden' ?> bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6"
             data-section-id="<?= $sid ?>">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800"><?= $info['label'] ?></h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Program</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Male</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Female</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" data-program-rows>
                        <?php if (empty($programs)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400 text-sm">No program data available</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($programs as $prog):
                                if ((int)$prog['program_id'] === 4) continue;
                                $pct = $secTotal > 0 ? round(($prog['total'] / $secTotal) * 100, 1) : 0;
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 font-medium text-gray-800"><?= htmlspecialchars($prog['program_name']) ?></td>
                                <td class="px-4 py-3 text-center text-gray-600"><?= number_format((int)$prog['total_male']) ?></td>
                                <td class="px-4 py-3 text-center text-gray-600"><?= number_format((int)$prog['total_female']) ?></td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-800"><?= number_format((int)$prog['total']) ?></td>
                                <td class="px-6 py-3 text-right text-gray-500"><?= $pct ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-6 py-3 text-gray-800">Section Total</td>
                            <td class="px-4 py-3 text-center text-gray-800" data-col-male><?= number_format($secMale) ?></td>
                            <td class="px-4 py-3 text-center text-gray-800" data-col-female><?= number_format($secFemale) ?></td>
                            <td class="px-4 py-3 text-center text-gray-800" data-col-total><?= number_format($secTotal) ?></td>
                            <td class="px-6 py-3 text-right text-gray-800">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- ── Grand Total Banner ──────────────────────────────────── -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-md p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Grand Total</p>
                    <p id="grand-total" class="text-white text-3xl font-bold"><?= number_format($allSectionTotal) ?></p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Total Male</p>
                    <p id="grand-male" class="text-white text-3xl font-bold"><?= number_format($grandMale) ?></p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Total Female</p>
                    <p id="grand-female" class="text-white text-3xl font-bold"><?= number_format($grandFemale) ?></p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Total Programs</p>
                    <p id="grand-programs" class="text-white text-3xl font-bold"><?= count($cacheData['beneficiaries_by_program'] ?? []) ?></p>
                </div>
            </div>
        </div>

    </div><!-- /px-6 -->
</main>

<script>
console.log('[Reports] cache reset count:', <?= (int) ($cacheData['cache_reset_count'] ?? 0) ?>);
console.log('[Reports] cache refreshed at:', <?= json_encode($cacheData['cache_refreshed_at'] ?? '') ?>);
</script>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
/* ─────────────────────────────────────────────────────────────
 * Initial chart data seeded from the cached PHP snapshot only.
 * ─────────────────────────────────────────────────────────────*/
const initialTrendLabels  = <?= json_encode($monthLabels) ?>;
const initialTrendReg     = <?= json_encode($monthReg) ?>;
const initialTrendHired   = <?= json_encode($monthHired) ?>;
const initialMale         = <?= (int) $totalMale ?>;
const initialFemale       = <?= (int) $totalFemale ?>;

/* ── Monthly Trend Chart ──────────────────────────── */
const trendChart = new Chart(document.getElementById('trendChart'), {
    type: 'bar',
    data: {
        labels: initialTrendLabels.length ? initialTrendLabels : ['No data'],
        datasets: [
            {
                label: 'Registered',
                data: initialTrendReg,
                backgroundColor: '#2563EB',
                borderRadius: 4,
                borderSkipped: false,
            },
            {
                label: 'Hired',
                data: initialTrendHired,
                backgroundColor: '#F59E0B',
                borderRadius: 4,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { font: { size: 12 } }, grid: { display: false } },
            y: { ticks: { font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.05)' } }
        }
    }
});

/* ── Gender Donut Chart ───────────────────────────── */
const genderChart = new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            data: [initialMale || 1, initialFemale || 1], // avoid empty chart
            backgroundColor: ['#2563EB', '#F59E0B'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: { legend: { display: false } }
    }
});

/* ── Tab Switcher ─────────────────────────────────── */
function showSection(id) {
    document.querySelectorAll('.program-section').forEach(el => el.classList.add('hidden'));
    document.getElementById('section-' + id).classList.remove('hidden');

    document.querySelectorAll('.tab-btn').forEach(btn => {
        const active = btn.dataset.tab === id;
        btn.classList.toggle('bg-blue-600',     active);
        btn.classList.toggle('text-white',       active);
        btn.classList.toggle('border-blue-600',  active);
        btn.classList.toggle('bg-white',        !active);
        btn.classList.toggle('text-gray-600',   !active);
        btn.classList.toggle('border-gray-300', !active);
    });
}

</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>