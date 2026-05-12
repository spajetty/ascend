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
                    <span class="text-2xl font-bold text-gray-800">6</span>
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
                        <span class="text-2xl font-bold text-gray-800">4</span>
                        <span class="text-xs text-gray-400 mb-1">New</span>
                        <span class="text-lg font-bold text-gray-300 mb-0.5">/</span>
                        <span class="text-2xl font-bold text-gray-800">2</span>
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
                    <span class="text-2xl font-bold text-gray-800">235</span>
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
                    <span class="text-2xl font-bold text-gray-800">3</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16M3 21h18M9 7h1m-1 4h1m4-4h1m-1 4h1M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4"/>
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
                    <span class="text-green-600 font-semibold">4 New</span>
                    <span class="mx-1">·</span>
                    <span class="text-orange-500 font-semibold">2 Renew</span>
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
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold text-xs">JANUARY 2026</td>
                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">COMPANY NAME</td>
                            <td class="px-4 py-3 text-green-600 font-medium">Manpower</td>
                            <td class="px-4 py-3 text-gray-600">Agriculture</td>
                            <td class="px-4 py-3 text-gray-600">CITY</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold text-xs">JANUARY 2026</td>
                            <td class="px-4 py-3">
                                <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-3 py-1 rounded-full">Renew</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">COMPANY NAME</td>
                            <td class="px-4 py-3 text-orange-500 font-medium">Direct (Overseas)</td>
                            <td class="px-4 py-3 text-gray-600">Maritime</td>
                            <td class="px-4 py-3 text-gray-600">CITY</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold text-xs">FEBRUARY 2026</td>
                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">COMPANY NAME</td>
                            <td class="px-4 py-3 text-blue-500 font-medium">Direct</td>
                            <td class="px-4 py-3 text-gray-600">Construction</td>
                            <td class="px-4 py-3 text-gray-600">CITY</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold text-xs">FEBRUARY 2026</td>
                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">New</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">COMPANY NAME</td>
                            <td class="px-4 py-3 text-green-600 font-medium">Manpower</td>
                            <td class="px-4 py-3 text-gray-600">Business Process Outsourcing</td>
                            <td class="px-4 py-3 text-gray-600">CITY</td>
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
                <span class="text-sm font-semibold text-orange-500">235 Total</span>
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
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold text-xs">JANUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">48</td>
                            <td class="px-4 py-3 text-gray-600">22</td>
                            <td class="px-4 py-3 text-gray-700 font-semibold">70</td>
                            <td class="px-4 py-3 text-gray-600">PROJECT NAME</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold text-xs">FEBRUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">62</td>
                            <td class="px-4 py-3 text-gray-600">18</td>
                            <td class="px-4 py-3 text-gray-700 font-semibold">80</td>
                            <td class="px-4 py-3 text-gray-600">PROJECT NAME</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold text-xs">MARCH 2026</td>
                            <td class="px-4 py-3 text-gray-600">55</td>
                            <td class="px-4 py-3 text-gray-600">30</td>
                            <td class="px-4 py-3 text-gray-700 font-semibold">85</td>
                            <td class="px-4 py-3 text-gray-600">PROJECT NAME</td>
                        </tr>
                        <!-- TOTAL Row -->
                        <tr class="bg-gray-50 border-t-2 border-gray-200">
                            <td class="px-6 py-3 text-gray-800 font-bold text-xs">TOTAL</td>
                            <td class="px-4 py-3 text-gray-700 font-semibold">165</td>
                            <td class="px-4 py-3 text-gray-700 font-semibold">70</td>
                            <td class="px-4 py-3">
                                <span class="bg-orange-200 text-orange-700 font-bold text-xs px-3 py-1 rounded-full">235</span>
                            </td>
                            <td class="px-4 py-3 text-gray-400">—</td>
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

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>