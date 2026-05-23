<?php
require_once __DIR__ . '/../../includes/auth-check.php';

// Admin only
if (($_SESSION['user_role'] ?? '') !== 'Admin') {
    header("Location: /pages/dashboard/dashboard.php");
    exit;
}

require_once __DIR__ . '/../../api/db.php';

// ── Handle actions ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $action  = $_POST['action'];
    $user_id = intval($_POST['user_id']);

    $map = [
        'approve' => 'Allowed',
        'decline' => 'Declined',
        'revoke'  => 'Pending',
    ];

    if (isset($map[$action])) {
        $newStatus = $map[$action];
        $stmt = $conn->prepare("UPDATE users SET access = ? WHERE user_id = ? AND role = 'Staff'");
        $stmt->bind_param("si", $newStatus, $user_id);
        $stmt->execute();
    }

    header("Location: /pages/access/access.php");
    exit;
}

// ── Fetch rows ────────────────────────────────────────────────────────────
$pending = $conn->query("
    SELECT user_id, fname, lname, middle_initial, email, contact, created_at
    FROM users WHERE access = 'Pending' AND role = 'Staff'
    ORDER BY created_at DESC
")->fetch_all(MYSQLI_ASSOC);

$approved = $conn->query("
    SELECT user_id, fname, lname, middle_initial, email, contact, created_at
    FROM users WHERE access = 'Allowed' AND role = 'Staff'
    ORDER BY created_at DESC
")->fetch_all(MYSQLI_ASSOC);

$declined = $conn->query("
    SELECT user_id, fname, lname, middle_initial, email, contact, created_at
    FROM users WHERE access = 'Declined' AND role = 'Staff'
    ORDER BY created_at DESC
")->fetch_all(MYSQLI_ASSOC);

// ── Page config ───────────────────────────────────────────────────────────
$currentPage = 'access';
$pageTitle   = 'ASCEND PED System – Access Management';
$pageHeading = 'Access Management';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6">
        <p class="text-sm text-gray-500 mb-6">Manage staff signup requests and account access</p>

        <!-- Tab bar -->
        <div class="flex gap-1 border-b border-gray-200 mb-6">

            <button onclick="switchTab('pending')" id="tab-pending"
                class="tab-btn flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-t-lg transition-colors">
                Pending Signups
                <?php if (count($pending) > 0): ?>
                    <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-0.5 rounded-full">
                        <?= count($pending) ?>
                    </span>
                <?php endif; ?>
            </button>

            <button onclick="switchTab('approved')" id="tab-approved"
                class="tab-btn flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-t-lg transition-colors">
                Approved Staffs
                <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-0.5 rounded-full">
                    <?= count($approved) ?>
                </span>
            </button>

            <button onclick="switchTab('declined')" id="tab-declined"
                class="tab-btn flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-t-lg transition-colors">
                Declined Users
                <?php if (count($declined) > 0): ?>
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">
                        <?= count($declined) ?>
                    </span>
                <?php endif; ?>
            </button>

        </div>

        <!-- ── Pending Signups ──────────────────────────────────────────── -->
        <div id="panel-pending" class="tab-panel">
            <div class="bg-white rounded-2xl border-t-4 border-orange-400 shadow-sm p-6">

                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-orange-100 p-3 rounded-xl flex-shrink-0">
                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Pending Signups</h2>
                        <p class="text-sm text-gray-400"><?= count($pending) ?> user(s) awaiting approval</p>
                    </div>
                </div>

                <?php if (empty($pending)): ?>
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-14 h-14 mb-3 opacity-25" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium">No pending signups at the moment</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                                <tr>
                                    <th class="px-4 py-3 text-left rounded-tl-lg">Name</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Contact</th>
                                    <th class="px-4 py-3 text-left">Registered</th>
                                    <th class="px-4 py-3 text-left rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($pending as $u): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-gray-800">
                                            <?= htmlspecialchars(
                                                $u['fname'] . ' ' .
                                                ($u['middle_initial'] ? $u['middle_initial'] . '. ' : '') .
                                                $u['lname'],
                                                ENT_QUOTES, 'UTF-8'
                                            ) ?>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($u['contact'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                                            <?= date('M j, Y g:i A', strtotime($u['created_at'])) ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <form method="POST">
                                                    <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                                    <input type="hidden" name="action"  value="approve">
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST">
                                                    <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                                    <input type="hidden" name="action"  value="decline">
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-red-100 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-200 transition">
                                                        Decline
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Approved Staffs ──────────────────────────────────────────── -->
        <div id="panel-approved" class="tab-panel hidden">
            <div class="bg-white rounded-2xl border-t-4 border-blue-400 shadow-sm p-6">

                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-blue-100 p-3 rounded-xl flex-shrink-0">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Approved Staffs</h2>
                        <p class="text-sm text-gray-400"><?= count($approved) ?> active staff member(s)</p>
                    </div>
                </div>

                <?php if (empty($approved)): ?>
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-14 h-14 mb-3 opacity-25" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm font-medium">No approved staff yet</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                                <tr>
                                    <th class="px-4 py-3 text-left rounded-tl-lg">Name</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Contact</th>
                                    <th class="px-4 py-3 text-left">Registered</th>
                                    <th class="px-4 py-3 text-left rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($approved as $u): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-gray-800">
                                            <?= htmlspecialchars(
                                                $u['fname'] . ' ' .
                                                ($u['middle_initial'] ? $u['middle_initial'] . '. ' : '') .
                                                $u['lname'],
                                                ENT_QUOTES, 'UTF-8'
                                            ) ?>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($u['contact'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                                            <?= date('M j, Y', strtotime($u['created_at'])) ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <form method="POST">
                                                <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                                <input type="hidden" name="action"  value="revoke">
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg hover:bg-gray-200 transition">
                                                    Revoke Access
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Declined Users ───────────────────────────────────────────── -->
        <div id="panel-declined" class="tab-panel hidden">
            <div class="bg-white rounded-2xl border-t-4 border-red-400 shadow-sm p-6">

                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-red-100 p-3 rounded-xl flex-shrink-0">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Declined Users</h2>
                        <p class="text-sm text-gray-400"><?= count($declined) ?> declined — email permanently blocked</p>
                    </div>
                </div>

                <?php if (empty($declined)): ?>
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-14 h-14 mb-3 opacity-25" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <p class="text-sm font-medium">No declined users</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                                <tr>
                                    <th class="px-4 py-3 text-left rounded-tl-lg">Name</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Contact</th>
                                    <th class="px-4 py-3 text-left rounded-tr-lg">Registered</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($declined as $u): ?>
                                    <tr class="hover:bg-gray-50 transition-colors opacity-60">
                                        <td class="px-4 py-3 font-semibold text-gray-700">
                                            <?= htmlspecialchars(
                                                $u['fname'] . ' ' .
                                                ($u['middle_initial'] ? $u['middle_initial'] . '. ' : '') .
                                                $u['lname'],
                                                ENT_QUOTES, 'UTF-8'
                                            ) ?>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($u['contact'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                                            <?= date('M j, Y', strtotime($u['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /px-6 -->
</main>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
        b.classList.add('text-gray-500');
    });

    document.getElementById('panel-' + tab).classList.remove('hidden');
    const btn = document.getElementById('tab-' + tab);
    btn.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
    btn.classList.remove('text-gray-500');
}

switchTab('pending');
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>