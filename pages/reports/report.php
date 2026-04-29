<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'reports';
$pageTitle   = 'ASCEND PED System – Reports';
$pageHeading = 'Reports';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen bg-gray-50">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="px-6 md:px-8 py-6">

        <!-- ── Stat Cards ─────────────────────────────────────────── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

            <!-- Total Registered -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-5 shadow-md flex items-start justify-between">
                <div>
                    <p class="text-xs text-blue-100 mb-1 font-medium uppercase tracking-wide">Total Registered</p>
                    <h3 class="text-3xl font-bold text-white">12,458</h3>
                    <p class="text-xs text-blue-100 mt-1">↑ +12.5% from last month</p>
                </div>
                <div class="bg-white/20 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>

            <!-- Total Hired -->
            <div class="bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl p-5 shadow-md flex items-start justify-between">
                <div>
                    <p class="text-xs text-amber-100 mb-1 font-medium uppercase tracking-wide">Total Hired</p>
                    <h3 class="text-3xl font-bold text-white">3,247</h3>
                    <p class="text-xs text-amber-100 mt-1">↑ +8.3% from last month</p>
                </div>
                <div class="bg-white/20 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m8 0a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2"/></svg>
                </div>
            </div>

            <!-- Accredited Employers -->
            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-5 shadow-md flex items-start justify-between">
                <div>
                    <p class="text-xs text-red-100 mb-1 font-medium uppercase tracking-wide">Accredited Employers</p>
                    <h3 class="text-3xl font-bold text-white">856</h3>
                    <p class="text-xs text-red-100 mt-1">↑ +5.2% from last month</p>
                </div>
                <div class="bg-white/20 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>

            <!-- Active Job Vacancies -->
            <div class="bg-white rounded-xl p-5 shadow-md flex items-start justify-between border border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 mb-1 font-medium uppercase tracking-wide">Active Job Vacancies</p>
                    <h3 class="text-3xl font-bold text-gray-800">1,234</h3>
                    <p class="text-xs text-gray-500 mt-1">↓ -2.1% from last month</p>
                </div>
                <div class="bg-blue-600 rounded-lg w-11 h-11 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>

        <!-- ── Toolbar ─────────────────────────────────────────────── -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>January 2024</option>
                <option>February 2024</option>
                <option>March 2024</option>
                <option>April 2024</option>
                <option>May 2024</option>
                <option>June 2024</option>
            </select>
            <div class="flex gap-2">
                <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Report
                </button>
                <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Data
                </button>
            </div>
        </div>

        <!-- ── Charts Row ──────────────────────────────────────────── -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            <!-- Monthly Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-5">Monthly Registration &amp; Hiring Trends</h3>
                <div class="relative h-64">
                    <canvas id="trendChart" role="img" aria-label="Monthly registration and hiring trends Jan–Jun 2024">Registrations ranged from 1,245 to 1,678; hirings from 456 to 623.</canvas>
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
                        <canvas id="genderChart" role="img" aria-label="Donut chart: 54.5% male, 45.5% female">Male 54.5%, Female 45.5%.</canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <p class="text-2xl font-bold text-gray-800">12,458</p>
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
                                <p class="text-sm font-bold text-gray-800">6,789</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-blue-600">54.5%</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a4 4 0 110 8 4 4 0 010-8zm0 10c4.42 0 8 1.79 8 4v1H4v-1c0-2.21 3.58-4 8-4z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Female</p>
                                <p class="text-sm font-bold text-gray-800">5,669</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-amber-500">45.5%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Program Tables ──────────────────────────────────────── -->

        <!-- Tab navigation -->
        <div class="flex flex-wrap gap-2 mb-4" id="programTabs">
            <button onclick="showSection('facilitation')" data-tab="facilitation"
                class="tab-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-blue-600 text-white border-blue-600">
                Employment Facilitation
            </button>
            <button onclick="showSection('employers')" data-tab="employers"
                class="tab-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors text-gray-600 bg-white border-gray-300 hover:bg-gray-50">
                Employers Engagement
            </button>
            <button onclick="showSection('youth')" data-tab="youth"
                class="tab-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors text-gray-600 bg-white border-gray-300 hover:bg-gray-50">
                Youth Employability
            </button>
            <button onclick="showSection('career')" data-tab="career"
                class="tab-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors text-gray-600 bg-white border-gray-300 hover:bg-gray-50">
                Career Development
            </button>
        </div>

        <!-- Employment Facilitation -->
        <div id="section-facilitation" class="program-section bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Employment Facilitation</h3>
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
                    <tbody class="divide-y divide-gray-50">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">Job Matching</td>
                            <td class="px-4 py-3 text-center text-gray-600">4,523</td>
                            <td class="px-4 py-3 text-center text-gray-600">3,933</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">8,456</td>
                            <td class="px-6 py-3 text-right text-gray-500">67.9%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">First Time Jobseeker</td>
                            <td class="px-4 py-3 text-center text-gray-600">1,089</td>
                            <td class="px-4 py-3 text-center text-gray-600">1,045</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">2,134</td>
                            <td class="px-6 py-3 text-right text-gray-500">17.1%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">TUPAD</td>
                            <td class="px-4 py-3 text-center text-gray-600">945</td>
                            <td class="px-4 py-3 text-center text-gray-600">922</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">1,867</td>
                            <td class="px-6 py-3 text-right text-gray-500">15.0%</td>
                        </tr>
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-6 py-3 text-gray-800">Section Total</td>
                            <td class="px-4 py-3 text-center text-gray-800">6,557</td>
                            <td class="px-4 py-3 text-center text-gray-800">5,900</td>
                            <td class="px-4 py-3 text-center text-gray-800">12,457</td>
                            <td class="px-6 py-3 text-right text-gray-800">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Employers Engagement -->
        <div id="section-employers" class="program-section hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Employers Engagement</h3>
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
                    <tbody class="divide-y divide-gray-50">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">Employers Accreditation</td>
                            <td class="px-4 py-3 text-center text-gray-600">512</td>
                            <td class="px-4 py-3 text-center text-gray-600">344</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">856</td>
                            <td class="px-6 py-3 text-right text-gray-500">66.9%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">Workers Hiring</td>
                            <td class="px-4 py-3 text-center text-gray-600">234</td>
                            <td class="px-4 py-3 text-center text-gray-600">189</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">423</td>
                            <td class="px-6 py-3 text-right text-gray-500">33.1%</td>
                        </tr>
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-6 py-3 text-gray-800">Section Total</td>
                            <td class="px-4 py-3 text-center text-gray-800">746</td>
                            <td class="px-4 py-3 text-center text-gray-800">533</td>
                            <td class="px-4 py-3 text-center text-gray-800">1,279</td>
                            <td class="px-6 py-3 text-right text-gray-800">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Youth Employability -->
        <div id="section-youth" class="program-section hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Youth Employability</h3>
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
                    <tbody class="divide-y divide-gray-50">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">SPES Baby</td>
                            <td class="px-4 py-3 text-center text-gray-600">1,789</td>
                            <td class="px-4 py-3 text-center text-gray-600">1,667</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">3,456</td>
                            <td class="px-6 py-3 text-right text-gray-500">49.0%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">4Ps Beneficiaries</td>
                            <td class="px-4 py-3 text-center text-gray-600">1,123</td>
                            <td class="px-4 py-3 text-center text-gray-600">1,111</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">2,234</td>
                            <td class="px-6 py-3 text-right text-gray-500">31.6%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">PWD</td>
                            <td class="px-4 py-3 text-center text-gray-600">298</td>
                            <td class="px-4 py-3 text-center text-gray-600">269</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">567</td>
                            <td class="px-6 py-3 text-right text-gray-500">8.0%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">Government Internship</td>
                            <td class="px-4 py-3 text-center text-gray-600">145</td>
                            <td class="px-4 py-3 text-center text-gray-600">167</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">312</td>
                            <td class="px-6 py-3 text-right text-gray-500">4.4%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">Work Immersion</td>
                            <td class="px-4 py-3 text-center text-gray-600">234</td>
                            <td class="px-4 py-3 text-center text-gray-600">256</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">490</td>
                            <td class="px-6 py-3 text-right text-gray-500">6.9%</td>
                        </tr>
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-6 py-3 text-gray-800">Section Total</td>
                            <td class="px-4 py-3 text-center text-gray-800">3,589</td>
                            <td class="px-4 py-3 text-center text-gray-800">3,470</td>
                            <td class="px-4 py-3 text-center text-gray-800">7,059</td>
                            <td class="px-6 py-3 text-right text-gray-800">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Career Development -->
        <div id="section-career" class="program-section hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Career Development</h3>
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
                    <tbody class="divide-y divide-gray-50">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">Career Development Support</td>
                            <td class="px-4 py-3 text-center text-gray-600">567</td>
                            <td class="px-4 py-3 text-center text-gray-600">623</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">1,190</td>
                            <td class="px-6 py-3 text-right text-gray-500">56.3%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">LMI Orientation</td>
                            <td class="px-4 py-3 text-center text-gray-600">445</td>
                            <td class="px-4 py-3 text-center text-gray-600">478</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800">923</td>
                            <td class="px-6 py-3 text-right text-gray-500">43.7%</td>
                        </tr>
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-6 py-3 text-gray-800">Section Total</td>
                            <td class="px-4 py-3 text-center text-gray-800">1,012</td>
                            <td class="px-4 py-3 text-center text-gray-800">1,101</td>
                            <td class="px-4 py-3 text-center text-gray-800">2,113</td>
                            <td class="px-6 py-3 text-right text-gray-800">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── Grand Total Banner ──────────────────────────────────── -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-md p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Grand Total</p>
                    <p class="text-white text-3xl font-bold">22,908</p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Total Male</p>
                    <p class="text-white text-3xl font-bold">11,904</p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Total Female</p>
                    <p class="text-white text-3xl font-bold">11,004</p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Total Programs</p>
                    <p class="text-white text-3xl font-bold">12</p>
                </div>
            </div>
        </div>

    </div><!-- /px-6 -->
</main>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
    /* ── Monthly Trend Chart ─────────────────────────── */
    new Chart(document.getElementById('trendChart'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Registered',
                    data: [1245, 1389, 1567, 1423, 1678, 1534],
                    backgroundColor: '#2563EB',
                    borderRadius: 4,
                    borderSkipped: false,
                },
                {
                    label: 'Hired',
                    data: [456, 512, 589, 534, 623, 578],
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
                x: {
                    ticks: { font: { size: 12 }, autoSkip: false },
                    grid: { display: false }
                },
                y: {
                    ticks: { font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });

    /* ── Gender Donut Chart ──────────────────────────── */
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [6789, 5669],
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

    /* ── Tab Switcher ────────────────────────────────── */
    function showSection(id) {
        document.querySelectorAll('.program-section').forEach(el => el.classList.add('hidden'));
        document.getElementById('section-' + id).classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(btn => {
            const active = btn.dataset.tab === id;
            btn.classList.toggle('bg-blue-600',  active);
            btn.classList.toggle('text-white',    active);
            btn.classList.toggle('border-blue-600', active);
            btn.classList.toggle('bg-white',      !active);
            btn.classList.toggle('text-gray-600', !active);
            btn.classList.toggle('border-gray-300', !active);
        });
    }
</script>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>