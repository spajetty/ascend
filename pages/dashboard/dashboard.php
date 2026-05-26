<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'dashboard';
$pageTitle   = 'ASCEND PED System – Dashboard';
$pageHeading = 'Dashboard Overview';

/* Caching removed: do not read fetch-details.json; use fresh defaults. */
$cacheData = [];

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
$totalEmployers  = cacheVal($cacheData, 'employers',            'total_employers');
$totalVacancies  = cacheVal($cacheData, 'employers',            'total_vacancies');

/* ── Previous-month delta helpers (for "Up from last month" badges) ──
 *  We look at the last two months in comparison_by_month.           */
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
    return $sign . number_format($change, 2) . '% ' . ($change >= 0 ? 'Up' : 'Down') . ' from last month';
}

$registeredBadge = pctChange($totalRegistered, $prevRegistered);
$hiredBadge      = pctChange($totalHired,      $prevHired);

/* ═══════════════════════════════════════════════════════════════════
 * Build section & program lookups from cache
 * ═══════════════════════════════════════════════════════════════════ */

/* section_id → [ name, total ] */
$sectionMap = [];
foreach ($cacheData['beneficiaries_by_section'] ?? [] as $row) {
    $sid = (int) $row['section_id'];
    $sectionMap[$sid] = [
        'name'  => $row['section_name'] ?? "Section $sid",
        'total' => (int) $row['total'],
    ];
}

/* section_id → [ [ program_name, total ], … ] */
$programMap = [];
foreach ($cacheData['beneficiaries_by_program'] ?? [] as $row) {
    $sid = (int) $row['section_id'];
    $programMap[$sid][] = [
        'name'  => $row['program_name'] ?? "Program {$row['program_id']}",
        'total' => (int) $row['total'],
    ];
}

/* ── Section card definitions (id → style) ── */
$sectionStyles = [
    1 => [
        'css'  => 'card-yellow',
        'icon' => '<path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM8 7V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H8z"/>',
    ],
    2 => [
        'css'  => 'card-red',
        'icon' => '<path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>',
    ],
    3 => [
        'css'  => 'card-blue',
        'icon' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z" stroke="currentColor" stroke-width="2" fill="none"/><path d="M6 12v5c3 3 9 3 12 0v-5" stroke="currentColor" stroke-width="2" fill="none"/>',
    ],
    4 => [
        'css'  => 'card-orange',
        'icon' => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17" stroke="currentColor" stroke-width="2" fill="none"/><polyline points="16 7 22 7 22 13" stroke="currentColor" stroke-width="2" fill="none"/>',
    ],
];

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6 space-y-8">

        <!-- ─── STAT CARDS ─── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Total Registered -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Total<br>Registered</p>
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 11c1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.3 3 3 3zm-8 0c1.7 0 3-1.3 3-3S9.7 5 8 5 5 6.3 5 8s1.3 3 3 3zm0 2c-2.3 0-7 1.2-7 3.5V19h14v-2.5c0-2.3-4.7-3.5-7-3.5zm8 0c-.3 0-.6 0-.9.1 1.1.8 1.9 1.8 1.9 3.4V19h6v-2.5c0-2.3-4.7-3.5-7-3.5z"/>
                        </svg>
                    </div>
                </div>
                <p id="stat-registered" class="text-2xl md:text-3xl font-extrabold text-gray-900">
                    <?= number_format($totalRegistered) ?>
                </p>
                <p id="badge-registered" class="text-xs text-emerald-500 font-semibold mt-1 flex items-center gap-1 <?= $registeredBadge ? '' : 'hidden' ?>">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="18 15 12 9 6 15"/>
                    </svg>
                    <span id="badge-registered-text"><?= htmlspecialchars($registeredBadge) ?></span>
                </p>
            </div>

            <!-- Total Hired -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Total<br>Hired</p>
                    <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-9 9H9v-2h2v2zm0-4H9V10h2v2zm4 4h-2v-2h2v2zm0-4h-2V10h2v2zm4 4h-2v-2h2v2zm0-4h-2V10h2v2zM8 7V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H8z"/>
                        </svg>
                    </div>
                </div>
                <p id="stat-hired" class="text-2xl md:text-3xl font-extrabold text-gray-900">
                    <?= number_format($totalHired) ?>
                </p>
                <p id="badge-hired" class="text-xs text-emerald-500 font-semibold mt-1 flex items-center gap-1 <?= $hiredBadge ? '' : 'hidden' ?>">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="18 15 12 9 6 15"/>
                    </svg>
                    <span id="badge-hired-text"><?= htmlspecialchars($hiredBadge) ?></span>
                </p>
            </div>

            <!-- Accredited Employers -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Accredited<br>Employers</p>
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                        </svg>
                    </div>
                </div>
                <p id="stat-employers" class="text-3xl md:text-4xl font-extrabold text-gray-900">
                    <?= number_format($totalEmployers) ?>
                </p>
            </div>

            <!-- Active Job Vacancies -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-tight">Active Job<br>Vacancies</p>
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 4V1L8 5l4 4V6c3.3 0 6 2.7 6 6s-2.7 6-6 6-6-2.7-6-6H4c0 4.4 3.6 8 8 8s8-3.6 8-8-3.6-8-8-8z"/>
                        </svg>
                    </div>
                </div>
                <p id="stat-vacancies" class="text-3xl md:text-4xl font-extrabold text-gray-900">
                    <?= number_format($totalVacancies) ?>
                </p>
            </div>

        </div>

        <!-- ─── SECTION OVERVIEW ─── -->
        <div>
            <h2 class="text-base font-bold text-gray-800 mb-4">Section Overview</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <?php foreach ($sectionStyles as $sid => $style):
                    $section  = $sectionMap[$sid]  ?? ['name' => "Section $sid", 'total' => 0];
                    $programs = $programMap[$sid]   ?? [];
                ?>
                <div class="section-card <?= $style['css'] ?> rounded-2xl p-6 text-white shadow-md"
                     data-section-id="<?= $sid ?>">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <?= $style['icon'] ?>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-base leading-tight">
                                <?= htmlspecialchars($section['name']) ?>
                            </p>
                            <p class="text-xs text-white/70" data-section-total>
                                Total: <?= number_format($section['total']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="space-y-2" data-program-list>
                        <?php if (empty($programs)): ?>
                            <div class="flex items-center justify-center bg-white/10 rounded-xl px-4 py-3">
                                <span class="text-sm text-white/60">No program data available</span>
                            </div>
                        <?php else: ?>
                            <?php foreach ($programs as $prog): ?>
                            <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                                <span class="text-sm font-medium"><?= htmlspecialchars($prog['name']) ?></span>
                                <span class="text-sm font-bold"><?= number_format($prog['total']) ?></span>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>

    </div>
</main>

<script>
const DASHBOARD_DETAILS_ENDPOINT = '/backend/dashboard/fetch-details.php';

const dashboardNumberFormat = new Intl.NumberFormat('en-US');

function dashboardText(id, value) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = value;
    }
}

function dashboardBadge(elementId, textId, value) {
    const badge = document.getElementById(elementId);
    const text = document.getElementById(textId);
    if (!badge || !text) return;

    if (value) {
        badge.classList.remove('invisible');
        text.textContent = value;
    } else {
        badge.classList.add('invisible');
        text.textContent = '';
    }
}

function dashboardPctChange(current, previous) {
    if (!previous) return '';
    const change = ((current - previous) / previous) * 100;
    const sign = change >= 0 ? '+' : '';
    return `${sign}${change.toFixed(2)}% ${change >= 0 ? 'Up' : 'Down'} from last month`;
}

function dashboardGroupPrograms(programRows) {
    const bySection = new Map();

    for (const row of programRows) {
        const sectionId = Number(row.section_id ?? 0);
        if (!bySection.has(sectionId)) {
            bySection.set(sectionId, []);
        }
        bySection.get(sectionId).push(row);
    }

    return bySection;
}

function dashboardRenderPrograms(sectionCard, programs, sectionTotal) {
    const list = sectionCard.querySelector('[data-program-list]');
    const totalNode = sectionCard.querySelector('[data-section-total]');
    if (!list || !totalNode) return;

    totalNode.textContent = `Total: ${dashboardNumberFormat.format(sectionTotal)}`;

    if (!programs.length) {
        list.innerHTML = '<div class="flex items-center justify-center bg-white/10 rounded-xl px-4 py-3"><span class="text-sm text-white/60">No program data available</span></div>';
        return;
    }

    list.innerHTML = programs.map((program) => `
        <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
            <span class="text-sm font-medium">${String(program.program_name ?? 'Program')}</span>
            <span class="text-sm font-bold">${dashboardNumberFormat.format(Number(program.total ?? 0))}</span>
        </div>
    `).join('');
}

async function loadDashboardDetails() {
    try {
        const response = await fetch(DASHBOARD_DETAILS_ENDPOINT, { credentials: 'same-origin' });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            throw new Error(payload.error || `Request failed (HTTP ${response.status}).`);
        }

        const data = payload.data ?? {};
        const totals = data.beneficiaries_totals ?? {};
        const programs = Array.isArray(data.beneficiaries_by_program) ? data.beneficiaries_by_program : [];
        const comparisons = Array.isArray(data.comparison_by_month) ? data.comparison_by_month : [];

        dashboardText('stat-registered', dashboardNumberFormat.format(Number(totals.total_registered ?? 0)));
        dashboardText('stat-hired', dashboardNumberFormat.format(Number(totals.total_hired ?? 0)));
        dashboardText('stat-employers', dashboardNumberFormat.format(Number(data.employers?.total_employers ?? 0)));
        dashboardText('stat-vacancies', dashboardNumberFormat.format(Number(data.employers?.total_vacancies ?? 0)));

        const previous = comparisons.length >= 2 ? comparisons[comparisons.length - 2] : null;
        dashboardBadge('badge-registered', 'badge-registered-text', dashboardPctChange(Number(totals.total_registered ?? 0), Number(previous?.total_registered ?? 0)));
        dashboardBadge('badge-hired', 'badge-hired-text', dashboardPctChange(Number(totals.total_hired ?? 0), Number(previous?.total_hired ?? 0)));

        const programGroups = dashboardGroupPrograms(programs);
        document.querySelectorAll('.section-card').forEach((card) => {
            const sectionId = Number(card.dataset.sectionId ?? 0);
            const sectionPrograms = programGroups.get(sectionId) ?? [];
            const sectionTotal = sectionPrograms.reduce((sum, row) => sum + Number(row.total ?? 0), 0);
            dashboardRenderPrograms(card, sectionPrograms, sectionTotal);
        });
    } catch (error) {
        console.error('[Dashboard] Failed to load live details:', error);
    }
}

document.addEventListener('DOMContentLoaded', loadDashboardDetails);
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>