<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Youth Employability';
$pageHeading = 'Youth Employability';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <!-- Total Program Participants -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">428</span>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Program Participants</span>
            </div>

            <!-- Total Schools Involved -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">14</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 13c0 3.866-4.03 7-9 7s-9-3.134-9-7a12.083 12.083 0 012.84-1.578L12 14z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Schools Involved</span>
            </div>

            <!-- Total Program Engagements -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-teal-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">5</span>
                    <div class="bg-teal-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Program Engagements</span>
            </div>

            <!-- Total Employers Involved -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">22</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Employers Involved</span>
            </div>

        </div>

        <!-- SPES Baby Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">SPES Baby</h2>
                <p class="text-xs text-gray-500 mt-0.5">Special Program for Employment of Students</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">MONTH REPORTED</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">EMPLOYER</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">START OF CONTRACT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">END OF CONTRACT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">DAYS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">JANUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Jan 6, 2026</td>
                            <td class="px-4 py-3 text-gray-600">Mar 6, 2026</td>
                            <td class="px-4 py-3 font-semibold text-blue-600">52</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">FEBRUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Feb 3, 2026</td>
                            <td class="px-4 py-3 text-gray-600">Apr 3, 2026</td>
                            <td class="px-4 py-3 font-semibold text-blue-600">48</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">MARCH 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Mar 2, 2026</td>
                            <td class="px-4 py-3 text-gray-600">May 2, 2026</td>
                            <td class="px-4 py-3 font-semibold text-blue-600">60</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/programs/youth-employability/spes" class="text-sm text-blue-600 hover:text-blue-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- 4Ps Beneficiaries Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">4Ps Beneficiaries</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pantawid Pamilyang Pilipino Program</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">MONTH REPORTED</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">EMPLOYER</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">START OF CONTRACT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">END OF CONTRACT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">DAYS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">JANUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Jan 6, 2026</td>
                            <td class="px-4 py-3 text-gray-600">Mar 6, 2026</td>
                            <td class="px-4 py-3 font-semibold text-green-600">52</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">FEBRUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Feb 3, 2026</td>
                            <td class="px-4 py-3 text-gray-600">Apr 3, 2026</td>
                            <td class="px-4 py-3 font-semibold text-green-600">48</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">MARCH 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Mar 2, 2026</td>
                            <td class="px-4 py-3 text-gray-600">May 2, 2026</td>
                            <td class="px-4 py-3 font-semibold text-green-600">60</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/programs/youth-employability/4ps" class="text-sm text-green-600 hover:text-green-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- PWD Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">PWD</h2>
                <p class="text-xs text-gray-500 mt-0.5">Persons with Disability</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">MONTH REPORTED</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">EMPLOYER</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">START OF CONTRACT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">END OF CONTRACT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">DAYS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">JANUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Jan 6, 2026</td>
                            <td class="px-4 py-3 text-gray-600">Mar 6, 2026</td>
                            <td class="px-4 py-3 font-semibold text-purple-600">52</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">FEBRUARY 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Feb 3, 2026</td>
                            <td class="px-4 py-3 text-gray-600">Apr 3, 2026</td>
                            <td class="px-4 py-3 font-semibold text-purple-600">48</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">MARCH 2026</td>
                            <td class="px-4 py-3 text-gray-600">EMPLOYER NAME</td>
                            <td class="px-4 py-3 text-gray-600">Mar 2, 2026</td>
                            <td class="px-4 py-3 text-gray-600">May 2, 2026</td>
                            <td class="px-4 py-3 font-semibold text-purple-600">60</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/programs/youth-employability/pwd" class="text-sm text-purple-600 hover:text-purple-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- Government Internship Program (GIP) Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Government Internship Program</h2>
                <p class="text-xs text-gray-500 mt-0.5">GIP</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">CONTRACT PERIOD</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">SCHOOL</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">COLLEGE / SHS</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">COURSE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">OFFICE ASSIGNMENT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">REQUIRED HOURS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">Jan – Mar 2026</td>
                            <td class="px-4 py-3 text-gray-600">SCHOOL NAME</td>
                            <td class="px-4 py-3">
                                <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">BS Information Technology</td>
                            <td class="px-4 py-3 text-gray-600">OFFICE NAME</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">486</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">Jan – Mar 2026</td>
                            <td class="px-4 py-3 text-gray-600">SCHOOL NAME</td>
                            <td class="px-4 py-3">
                                <span class="bg-cyan-100 text-cyan-700 text-xs font-semibold px-2 py-0.5 rounded-full">SHS</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">ABM</td>
                            <td class="px-4 py-3 text-gray-600">OFFICE NAME</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">320</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">Feb – Apr 2026</td>
                            <td class="px-4 py-3 text-gray-600">SCHOOL NAME</td>
                            <td class="px-4 py-3">
                                <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">BS Accountancy</td>
                            <td class="px-4 py-3 text-gray-600">OFFICE NAME</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">486</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/programs/youth-employability/gip" class="text-sm text-teal-600 hover:text-teal-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- Work Immersion / Internship Referral Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Work Immersion and Internship Referral Program</h2>
                <p class="text-xs text-gray-500 mt-0.5">WIIRP</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-gray-500 font-semibold tracking-wide">CONTRACT PERIOD</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">SCHOOL</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">EDUCATION LEVEL</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">COURSE</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">OFFICE ASSIGNMENT</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-semibold tracking-wide">REQUIRED HOURS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">Jan – Mar 2026</td>
                            <td class="px-4 py-3 text-gray-600">SCHOOL NAME</td>
                            <td class="px-4 py-3">
                                <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">BS Nursing</td>
                            <td class="px-4 py-3 text-gray-600">OFFICE NAME</td>
                            <td class="px-4 py-3 font-semibold text-orange-600">600</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">Jan – Mar 2026</td>
                            <td class="px-4 py-3 text-gray-600">SCHOOL NAME</td>
                            <td class="px-4 py-3">
                                <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">SHS</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">HUMSS</td>
                            <td class="px-4 py-3 text-gray-600">OFFICE NAME</td>
                            <td class="px-4 py-3 font-semibold text-orange-600">80</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800 font-semibold">Feb – Apr 2026</td>
                            <td class="px-4 py-3 text-gray-600">SCHOOL NAME</td>
                            <td class="px-4 py-3">
                                <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded-full">College</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">BS Social Work</td>
                            <td class="px-4 py-3 text-gray-600">OFFICE NAME</td>
                            <td class="px-4 py-3 font-semibold text-orange-600">600</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/programs/youth-employability/work-immersion" class="text-sm text-orange-600 hover:text-orange-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>
