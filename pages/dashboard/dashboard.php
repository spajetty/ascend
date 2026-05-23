<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'dashboard';
$pageTitle   = 'ASCEND PED System – Dashboard';
$pageHeading = 'Dashboard Overview';

/* ═══════════════════════════════════════════════════════════════════
 * Load cached dashboard data from fetch-details.json
 * Falls back to safe defaults if the cache doesn't exist yet.
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

<!-- ═══════════════════════════════════════════════════════════════
     Dashboard bootstrap: call fetch-details on every page load,
     update the stat cards with the fresh data, and log the cache.
     ═══════════════════════════════════════════════════════════════ -->
<script>
(async function initDashboard() {
    /* ── Resolve the API path relative to the current page ── */
    const apiUrl = new URL(
        '<?= htmlspecialchars(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/../../backend/dashboard/fetch-details.php', ENT_QUOTES) ?>',
        window.location.origin
    ).href;

    console.group('[Dashboard] Fetching fresh data from fetch-details API');
    console.log('Endpoint →', apiUrl);

    try {
        const res  = await fetch(apiUrl, { credentials: 'same-origin' });

        if (!res.ok) {
            console.error('[Dashboard] HTTP error:', res.status, res.statusText);
            return;
        }

        const json = await res.json();

        /* ── Always log the full cache payload ── */
        console.log('[Dashboard] fetch-details.json cache content:', json);

        if (!json.success) {
            console.error('[Dashboard] API returned an error:', json.error ?? 'Unknown error');
            return;
        }

        const data = json.data;

        /* ── Helper: format integers the same way PHP number_format does ── */
        const fmt = (n) =>
            Number(n ?? 0).toLocaleString('en-US');

        /* ── Helper: compute pct-change badge text ── */
        const pctChange = (current, previous) => {
            if (!previous) return null;
            const change = ((current - previous) / previous) * 100;
            const sign   = change >= 0 ? '+' : '';
            const dir    = change >= 0 ? 'Up' : 'Down';
            return `${sign}${change.toFixed(2)}% ${dir} from last month`;
        };

        /* ── Pull the values we need ── */
        const totals    = data.beneficiaries_totals ?? {};
        const employers = data.employers            ?? {};
        const monthRows = data.comparison_by_month  ?? [];

        const totalRegistered = totals.total_registered ?? 0;
        const totalHired      = totals.total_hired      ?? 0;
        const totalEmployers  = employers.total_employers ?? 0;
        const totalVacancies  = employers.total_vacancies ?? 0;

        /* Previous-month deltas */
        let prevRegistered = 0, prevHired = 0;
        if (monthRows.length >= 2) {
            const prev     = monthRows[monthRows.length - 2];
            prevRegistered = prev.total_registered ?? 0;
            prevHired      = prev.total_hired      ?? 0;
        }

        /* ── Update stat card numbers ── */
        document.getElementById('stat-registered').textContent = fmt(totalRegistered);
        document.getElementById('stat-hired').textContent      = fmt(totalHired);
        document.getElementById('stat-employers').textContent  = fmt(totalEmployers);
        document.getElementById('stat-vacancies').textContent  = fmt(totalVacancies);

        /* ── Update / show badges ── */
        const updateBadge = (badgeId, textId, current, previous) => {
            const badge = document.getElementById(badgeId);
            const span  = document.getElementById(textId);
            if (!badge || !span) return;
            const text = pctChange(current, previous);
            if (text) {
                span.textContent = text;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        };

        updateBadge('badge-registered', 'badge-registered-text', totalRegistered, prevRegistered);
        updateBadge('badge-hired',      'badge-hired-text',      totalHired,      prevHired);

        /* ── Update section overview cards ── */
        const bySection = {};
        (data.beneficiaries_by_section ?? []).forEach(s => {
            bySection[s.section_id] = s;
        });

        const byProgram = {};
        (data.beneficiaries_by_program ?? []).forEach(p => {
            if (!byProgram[p.section_id]) byProgram[p.section_id] = [];
            byProgram[p.section_id].push(p);
        });

        document.querySelectorAll('[data-section-id]').forEach(card => {
            const sid     = parseInt(card.dataset.sectionId);
            const section = bySection[sid];
            const programs = byProgram[sid] ?? [];

            if (section) {
                const totalEl = card.querySelector('[data-section-total]');
                if (totalEl) totalEl.textContent = 'Total: ' + fmt(section.total);
            }

            const list = card.querySelector('[data-program-list]');
            if (!list) return;
            list.innerHTML = programs.length
                ? programs.map(p => `
                    <div class="prog-item flex items-center justify-between bg-white/20 rounded-xl px-4 py-3 cursor-default">
                        <span class="text-sm font-medium">${p.program_name}</span>
                        <span class="text-sm font-bold">${fmt(p.total)}</span>
                    </div>`).join('')
                : `<div class="flex items-center justify-center bg-white/10 rounded-xl px-4 py-3">
                       <span class="text-sm text-white/60">No program data available</span>
                   </div>`;
        });

        console.log('[Dashboard] Stat cards and section overview updated successfully.');

    } catch (err) {
        console.error('[Dashboard] Failed to fetch or parse fetch-details:', err);
    } finally {
        console.groupEnd();
    }
})();
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>