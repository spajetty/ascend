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

            <!-- Total Employers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="totalAccreditations" class="text-2xl font-bold text-gray-800">—</span>
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
                    <div class="flex items-end gap-2">
                        <span id="newAccreditations" class="text-2xl font-bold text-gray-800">—</span>
                        <span class="text-xs text-gray-400 mb-1">New</span>
                        <span class="text-lg font-bold text-gray-300 mb-0.5">/</span>
                        <span id="renewAccreditations" class="text-2xl font-bold text-gray-800">—</span>
                        <span class="text-xs text-gray-400 mb-1">Renew</span>
                    </div>
                    <div class="bg-red-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">New · Renewed Accreditations</span>
            </div>

            <!-- Total WHIP Participants -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span id="workersHired" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Workers Hired</span>
            </div>

            <!-- Total WHIP Projects -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="infrastructureProjects" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Infrastructure Projects</span>
            </div>

        </div>

        <!-- Employers Accreditation Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <h2 class="font-bold text-gray-800 text-base">Employers Accreditation</h2>
                <span class="text-xs text-gray-500 font-medium">
                    <span id="newAccredBadge" class="text-green-600 font-semibold">0 New</span>
                    <span class="mx-1">·</span>
                    <span id="renewAccredBadge" class="text-orange-500 font-semibold">0 Renew</span>
                </span>
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
                    <tbody id="accreditationTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-engagement/emp-accreditation.php" class="text-sm text-green-600 hover:text-green-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- Workers Hiring for Infrastructure Projects (WHIP) Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <h2 class="font-bold text-gray-800 text-base">Workers Hiring for Infrastructure Projects</h2>
                <span id="whipTotalChip" class="text-sm font-semibold text-orange-500">0 Total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">MONTH</th>
                            <th class="text-left px-4 py-3 text-orange-400 font-semibold tracking-wide">MALE</th>
                            <th class="text-left px-4 py-3 text-orange-400 font-semibold tracking-wide">FEMALE</th>
                            <th class="text-left px-4 py-3 text-orange-500 font-semibold tracking-wide">TOTAL</th>
                            <th class="text-left px-4 py-3 text-orange-400 font-semibold tracking-wide">PROJECTS</th>
                        </tr>
                    </thead>
                    <tbody id="whipTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
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
async function loadEmployersEngagementData() {
    try {
        const formatMonthYear = (month, year) => {
            const parts = [month, year].filter(Boolean);
            return parts.length ? parts.join(' ').toUpperCase() : '—';
        };

        const accredRes = await fetch('/api/get-employers-accreditation.php');
        const accredData = await accredRes.json();
        if (accredData.success) {
            const accredStats = accredData.stats || {};
            document.getElementById('totalAccreditations').textContent = accredStats.total ?? 0;
            document.getElementById('newAccreditations').textContent = accredStats.new ?? 0;
            document.getElementById('renewAccreditations').textContent = accredStats.renew ?? 0;
            document.getElementById('newAccredBadge').textContent = `${accredStats.new ?? 0} New`;
            document.getElementById('renewAccredBadge').textContent = `${accredStats.renew ?? 0} Renew`;

            const accredBody = document.getElementById('accreditationTableBody');
            if (Array.isArray(accredData.data) && accredData.data.length > 0) {
                accredBody.innerHTML = accredData.data.slice(0, 5).map(row => `
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-800 font-semibold text-xs">${formatMonthYear(row.month, row.year)}</td>
                        <td class="px-4 py-3">
                            <span class="${String(row.accreditation).toLowerCase() === 'new'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-orange-100 text-orange-600'} text-xs font-semibold px-3 py-1 rounded-full">
                                ${row.accreditation ? `${row.accreditation.charAt(0).toUpperCase()}${row.accreditation.slice(1)}` : '—'}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">${row.company_name || '—'}</td>
                        <td class="px-4 py-3 text-gray-600">${row.est_type || '—'}</td>
                        <td class="px-4 py-3 text-gray-600">${row.industry || '—'}</td>
                        <td class="px-4 py-3 text-gray-600">${row.city || '—'}</td>
                    </tr>
                `).join('');
            } else {
                accredBody.innerHTML = `<tr class="border-b border-gray-50 hover:bg-gray-50"><td colspan="6" class="px-6 py-8 text-center text-gray-500">No data found.</td></tr>`;
            }
        }

        const whipRes = await fetch('/api/get-whip-data.php');
        const whipData = await whipRes.json();
        if (whipData.success) {
            const whipStats = whipData.stats || {};
            document.getElementById('workersHired').textContent = whipStats.workersHired ?? 0;
            document.getElementById('infrastructureProjects').textContent = whipStats.infrastructureProjects ?? 0;
            document.getElementById('whipTotalChip').textContent = `${whipStats.workersHired ?? 0} Total`;

            const whipBody = document.getElementById('whipTableBody');
            if (Array.isArray(whipData.data) && whipData.data.length > 0) {
                const rows = whipData.data.slice(0, 5).map(row => `
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-800 font-semibold text-xs">${formatMonthYear(row.month, row.year)}</td>
                        <td class="px-4 py-3 text-gray-600">${row.male ?? 0}</td>
                        <td class="px-4 py-3 text-gray-600">${row.female ?? 0}</td>
                        <td class="px-4 py-3 text-gray-700 font-semibold">${row.total ?? 0}</td>
                        <td class="px-4 py-3 text-gray-600">${row.project_name || '—'}</td>
                    </tr>
                `).join('');

                const totalRow = `
                    <tr class="bg-gray-50 border-t-2 border-gray-200">
                        <td class="px-6 py-3 text-gray-800 font-bold text-xs">TOTAL</td>
                        <td class="px-4 py-3 text-gray-700 font-semibold">${whipStats.maleTotal ?? 0}</td>
                        <td class="px-4 py-3 text-gray-700 font-semibold">${whipStats.femaleTotal ?? 0}</td>
                        <td class="px-4 py-3"><span class="bg-orange-200 text-orange-700 font-bold text-xs px-3 py-1 rounded-full">${whipStats.workersHired ?? 0}</span></td>
                        <td class="px-4 py-3 text-gray-400">—</td>
                    </tr>
                `;

                whipBody.innerHTML = rows + totalRow;
            } else {
                whipBody.innerHTML = `<tr class="border-b border-gray-50 hover:bg-gray-50"><td colspan="5" class="px-6 py-8 text-center text-gray-500">No data found.</td></tr>`;
            }
        }
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

// Load data when page is ready
document.addEventListener('DOMContentLoaded', loadEmployersEngagementData);
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>
