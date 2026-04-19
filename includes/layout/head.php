<?php
// ── Defaults (pages can override these before including) ──────────────────
$currentPage = $currentPage ?? 'dashboard';
$pageTitle   = $pageTitle   ?? 'ASCEND PED System';
$pageHeading = $pageHeading ?? 'Dashboard Overview';

// ── Shared nav item definitions ───────────────────────────────────────────
$navItems = [
    'dashboard' => [
        'href'        => '/pages/dashboard/dashboard.php',
        'label'       => 'Dashboard',
        'mobileLabel' => 'Dashboard',
    ],
    'programs' => [
        'href'        => '/pages/programs/program.php',
        'label'       => 'Employment Programs',
        'mobileLabel' => 'Programs',
    ],
    'beneficiaries' => [
        'href'        => '/pages/beneficiaries/beneficiary.php',
        'label'       => 'Beneficiaries',
        'mobileLabel' => 'Beneficiaries',
    ],
    'imports' => [
        'href'        => '/pages/imports/import.php',
        'label'       => 'Import Data',
        'mobileLabel' => 'Import',
    ],
    'reports' => [
        'href'        => '/pages/reports/report.php',
        'label'       => 'Reports',
        'mobileLabel' => 'Reports',
    ],
];

// ── Icon renderer (used by sidebar & mobile nav) ──────────────────────────
if (!function_exists('renderNavIcon')) {
    function renderNavIcon(string $pageKey, string $class = 'w-5 h-5'): string
    {
        $base = 'class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"';

        switch ($pageKey) {
            case 'dashboard':
                return '<svg ' . $base . '><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>';
            case 'programs':
                return '<svg ' . $base . '><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>';
            case 'beneficiaries':
                return '<svg ' . $base . '><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
            case 'imports':
                return '<svg ' . $base . '><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>';
            case 'reports':
                return '<svg ' . $base . '><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>';
            default:
                return '';
        }
    }
}

// ── User initials (used by the top bar) ───────────────────────────────────
$_userName = $_SESSION['user_name'] ?? 'User';
$_initials = '';
foreach (preg_split('/\s+/', trim($_userName)) as $_part) {
    if ($_part !== '') {
        $_initials .= strtoupper($_part[0]);
    }
}
if ($_initials === '') {
    $_initials = 'U';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Collapsed sidebar ─────────────────────────────── */
        .sidebar-collapsed {
            width: 5rem !important;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .logo-text { display: none; }
        .sidebar-collapsed .nav-item  { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed .nav-icon  { width: 1.5rem; height: 1.5rem; }
        .sidebar-collapsed .logo-bg   { margin: 0 auto; }
        .sidebar-collapsed nav        { gap: 0.75rem; }

        /* ── Nav states ────────────────────────────────────── */
        .nav-active {
            background: linear-gradient(90deg, #3b82f6 0%, #ef4444 50%, #eab308 100%);
            color: #fff;
            box-shadow: 0 4px 18px 0 rgba(59,130,246,0.35);
        }
        .nav-item { transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.15s; }
        .nav-item:hover:not(.nav-active) {
            background: linear-gradient(90deg, rgba(59,130,246,0.12) 0%, rgba(239,68,68,0.12) 50%, rgba(234,179,8,0.12) 100%);
            color: #f97316;
            transform: translateX(3px);
        }
        .nav-item:hover .nav-icon { color: #f97316; }

        /* ── Logout ────────────────────────────────────────── */
        .logout-btn { transition: color 0.18s, background 0.18s; }
        .logout-btn:hover {
            color: #ef4444;
            background: rgba(239,68,68,0.08);
            border-radius: 0.5rem;
        }

        /* ── Cards ─────────────────────────────────────────── */
        .stat-card    { transition: transform 0.18s, box-shadow 0.18s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,0.10); }

        .section-card { transition: transform 0.2s, box-shadow 0.2s; }
        .section-card:hover { transform: translateY(-4px) scale(1.01); box-shadow: 0 16px 48px rgba(0,0,0,0.18); }

        .prog-item { transition: background 0.18s; }
        .prog-item:hover { background: rgba(255,255,255,0.25) !important; }

        .card-yellow { background: linear-gradient(135deg, #a16207 0%, #facc15 50%, #a16207 100%); }
        .card-red    { background: linear-gradient(135deg, #7f1d1d 0%, #ef4444 50%, #7f1d1d 100%); }
        .card-blue   { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #1e3a8a 100%); }
        .card-orange { background: linear-gradient(135deg, #9a3412 0%, #fb923c 50%, #9a3412 100%); }

        /* ── Scrollbar ─────────────────────────────────────── */
        ::-webkit-scrollbar       { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* ── Modals ────────────────────────────────────────── */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
        }
        @keyframes modalIn {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)    scale(1);    }
        }
        .animate-modal { animation: modalIn 0.22s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex">
