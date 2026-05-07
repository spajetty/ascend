<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Career Development Section';
$pageHeading = 'Career Development Section';

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

            <!-- Total Participants -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span id="totalParticipants" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Participants</span>
            </div>

            <!-- Career Dev Sessions -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <span id="careerDevSessions" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-red-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Career Dev. Sessions</span>
            </div>

            <!-- LMI Sessions -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span id="lmiSessions" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">LMI Orientations</span>
            </div>

            <!-- Total Schools Reached -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span id="lmiParticipants" class="text-2xl font-bold text-gray-800">—</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">LMI Participants</span>
            </div>

        </div>

        <!-- Career Development Support Program Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Career Development Support Program</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">DATE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">SCHOOL / INSTITUTION</th>
                            <th class="text-left px-4 py-3 text-green-500 font-semibold tracking-wide">MALE</th>
                            <th class="text-left px-4 py-3 text-green-500 font-semibold tracking-wide">FEMALE</th>
                            <th class="text-left px-4 py-3 text-green-600 font-semibold tracking-wide">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="cdspTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/career-dev/cdsp.php" class="text-sm text-green-600 hover:text-green-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- LMI Orientation Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">LMI Orientation</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">DATE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">SCHOOL / INSTITUTION / EVENT</th>
                            <th class="text-left px-4 py-3 text-orange-400 font-semibold tracking-wide">MALE</th>
                            <th class="text-left px-4 py-3 text-orange-400 font-semibold tracking-wide">FEMALE</th>
                            <th class="text-left px-4 py-3 text-orange-500 font-semibold tracking-wide">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="lmiTableBody">
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/career-dev/lmi-orientation.php" class="text-sm text-orange-600 hover:text-orange-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<script>
async function loadCareerDevelopmentData() {
    try {
        const response = await fetch('/api/get-career-development-data.php');
        const payload = await response.json();
        if (!payload.success) return;

        const stats = payload.stats || {};
        document.getElementById('totalParticipants').textContent = Number(stats.totalParticipants || 0).toLocaleString();
        document.getElementById('careerDevSessions').textContent = Number(stats.cdspSessions || 0).toLocaleString();
        document.getElementById('lmiSessions').textContent = Number(stats.lmiSessions || 0).toLocaleString();
        document.getElementById('lmiParticipants').textContent = Number(stats.lmiParticipants || 0).toLocaleString();

        const formatDate = (value) => {
            if (!value) return '—';
            const d = new Date(`${value}T00:00:00`);
            return Number.isNaN(d.getTime()) ? value : d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }).toUpperCase();
        };

        const renderRows = (rows, bodyId, totalColorClasses) => {
            const tbody = document.getElementById(bodyId);
            if (!Array.isArray(rows) || rows.length === 0) {
                tbody.innerHTML = `<tr class="border-b border-gray-50 hover:bg-gray-50"><td colspan="5" class="px-6 py-8 text-center text-gray-500">No data found.</td></tr>`;
                return;
            }

            const topRows = rows.slice(0, 5);
            const maleTotal = topRows.reduce((sum, row) => sum + Number(row.male || 0), 0);
            const femaleTotal = topRows.reduce((sum, row) => sum + Number(row.female || 0), 0);
            const grandTotal = maleTotal + femaleTotal;

            const dataRows = topRows.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-800 font-semibold">${formatDate(row.date)}</td>
                    <td class="px-4 py-3 text-gray-600">${row.school || '—'}</td>
                    <td class="px-4 py-3 text-gray-600">${row.male ?? 0}</td>
                    <td class="px-4 py-3 text-gray-600">${row.female ?? 0}</td>
                    <td class="px-4 py-3 text-gray-700 font-semibold">${row.total ?? 0}</td>
                </tr>
            `).join('');

            const totalRow = `
                <tr class="bg-gray-50 border-t-2 border-gray-200">
                    <td class="px-6 py-3 text-gray-800 font-bold text-xs">TOTAL</td>
                    <td class="px-4 py-3 text-gray-400">—</td>
                    <td class="px-4 py-3 text-gray-700 font-semibold">${maleTotal}</td>
                    <td class="px-4 py-3 text-gray-700 font-semibold">${femaleTotal}</td>
                    <td class="px-4 py-3"><span class="${totalColorClasses} font-bold text-xs px-3 py-1 rounded-full">${grandTotal}</span></td>
                </tr>
            `;

            tbody.innerHTML = dataRows + totalRow;
        };

        const cdspRows = (payload.data?.cdsp || []).map((row) => ({
            date: row.date,
            school: row.school,
            male: Number(row.cdsp_m || 0),
            female: Number(row.cdsp_f || 0),
            total: Number(row.total || 0)
        }));
        renderRows(cdspRows, 'cdspTableBody', 'bg-green-200 text-green-800');

        const lmiRows = (payload.data?.lmi || []).map((row) => ({
            date: row.date,
            school: row.school,
            male: Number(row.lmi_m || 0),
            female: Number(row.lmi_f || 0),
            total: Number(row.total || 0)
        }));
        renderRows(lmiRows, 'lmiTableBody', 'bg-orange-200 text-orange-700');
    } catch (error) {
        console.error('Error loading career development data:', error);
    }
}

document.addEventListener('DOMContentLoaded', loadCareerDevelopmentData);
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>
