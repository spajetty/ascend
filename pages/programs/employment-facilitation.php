<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'programs';
$pageTitle   = 'ASCEND PED System – Employment Facilitation Section';
$pageHeading = 'Employment Facilitation Section';

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
            Back
        </a>
        <h2 class="text-2xl font-extrabold text-gray-900">Employment Facilitation Section</h2>
    </div>

    <div class="px-6 md:px-8 py-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            <!-- Total Users -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">351</span>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Users</span>
            </div>

            <!-- Total Employers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">158</span>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Employers</span>
            </div>

            <!-- Total Job Fair Vacancies -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-yellow-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">720</span>
                    <div class="bg-yellow-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total Job Fair Vacancies</span>
            </div>

            <!-- Total First Time Job Seekers -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col gap-2 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">184</span>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-500">Total First Time Job Seekers</span>
            </div>

        </div>

        <!-- Job Matching & Referral Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Job Matching & Referral</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-2 py-2 text-center text-teal-600 font-semibold tracking-wide border-l border-gray-100">REGISTERED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">REFERRED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <!-- Registered -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-teal-600">T</th>
                            <!-- Referred -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <!-- Interviewed -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <!-- Qualified -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <!-- Not Qualified -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <!-- Placed/HOTS -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <!-- For Further Interview -->
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">January 2026</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">45</td><td class="px-3 py-2 text-center text-gray-600">62</td><td class="px-3 py-2 text-center font-semibold text-teal-600 bg-teal-50">107</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">30</td><td class="px-3 py-2 text-center text-gray-600">41</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">71</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">25</td><td class="px-3 py-2 text-center text-gray-600">35</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">60</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">20</td><td class="px-3 py-2 text-center text-gray-600">28</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">48</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-3 py-2 text-center text-gray-600">7</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">12</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">18</td><td class="px-3 py-2 text-center text-gray-600">25</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">43</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">5</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">February 2026</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">52</td><td class="px-3 py-2 text-center text-gray-600">58</td><td class="px-3 py-2 text-center font-semibold text-teal-600 bg-teal-50">110</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">35</td><td class="px-3 py-2 text-center text-gray-600">38</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">73</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">30</td><td class="px-3 py-2 text-center text-gray-600">32</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">62</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">24</td><td class="px-3 py-2 text-center text-gray-600">26</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">50</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-3 py-2 text-center text-gray-600">6</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">12</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">22</td><td class="px-3 py-2 text-center text-gray-600">24</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">46</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-3 py-2 text-center text-gray-600">2</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">4</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">March 2026</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">60</td><td class="px-3 py-2 text-center text-gray-600">74</td><td class="px-3 py-2 text-center font-semibold text-teal-600 bg-teal-50">134</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">42</td><td class="px-3 py-2 text-center text-gray-600">50</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">92</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">36</td><td class="px-3 py-2 text-center text-gray-600">44</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">80</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">30</td><td class="px-3 py-2 text-center text-gray-600">36</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">66</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-3 py-2 text-center text-gray-600">8</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">14</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">28</td><td class="px-3 py-2 text-center text-gray-600">33</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">61</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">5</td>
                        </tr>
                        <!-- TOTAL Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <td class="px-4 py-2 text-gray-800 font-bold">TOTAL</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">157</td><td class="px-3 py-2 text-center text-gray-700">194</td><td class="px-3 py-2 text-center font-bold text-teal-600 bg-teal-100">351</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">107</td><td class="px-3 py-2 text-center text-gray-700">129</td><td class="px-3 py-2 text-center font-bold text-blue-500 bg-blue-100">236</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">91</td><td class="px-3 py-2 text-center text-gray-700">111</td><td class="px-3 py-2 text-center font-bold text-cyan-500 bg-cyan-100">202</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">74</td><td class="px-3 py-2 text-center text-gray-700">90</td><td class="px-3 py-2 text-center font-bold text-green-500 bg-green-100">164</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">17</td><td class="px-3 py-2 text-center text-gray-700">21</td><td class="px-3 py-2 text-center font-bold text-red-400 bg-red-100">38</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">68</td><td class="px-3 py-2 text-center text-gray-700">82</td><td class="px-3 py-2 text-center font-bold text-orange-400 bg-orange-100">150</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">6</td><td class="px-3 py-2 text-center text-gray-700">8</td><td class="px-3 py-2 text-center font-bold text-purple-400 bg-purple-100">14</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/job-match.php" class="text-sm text-teal-600 hover:text-teal-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- First-Time Jobseekers Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">First-Time Jobseekers</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th colspan="3" class="px-2 py-2 text-center text-pink-500 font-semibold tracking-wide border-l border-gray-100">FIRST-TIME JOBSEEKER</th>
                            <th colspan="2" class="px-2 py-2 text-center text-teal-500 font-semibold tracking-wide border-l border-gray-100">ISSUED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-pink-500">T</th>
                            <th class="px-3 py-1 text-center text-teal-500 font-medium border-l border-gray-100">Occ. Permit</th>
                            <th class="px-3 py-1 text-center text-teal-500 font-medium">Health Card</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">January 2026</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">24</td><td class="px-3 py-2 text-center text-gray-600">38</td><td class="px-3 py-2 text-center font-semibold text-pink-500 bg-pink-50">62</td>
                            <td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50 border-l border-gray-100">87</td><td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50">112</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">20</td><td class="px-3 py-2 text-center text-gray-600">32</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">52</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">16</td><td class="px-3 py-2 text-center text-gray-600">26</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">42</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">4</td><td class="px-3 py-2 text-center text-gray-600">6</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">10</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">13</td><td class="px-3 py-2 text-center text-gray-600">22</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">35</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-3 py-2 text-center text-gray-600">4</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">7</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">February 2026</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">18</td><td class="px-3 py-2 text-center text-gray-600">29</td><td class="px-3 py-2 text-center font-semibold text-pink-500 bg-pink-50">47</td>
                            <td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50 border-l border-gray-100">95</td><td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50">134</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">15</td><td class="px-3 py-2 text-center text-gray-600">24</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">39</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">12</td><td class="px-3 py-2 text-center text-gray-600">19</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">31</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-3 py-2 text-center text-gray-600">5</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">8</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-3 py-2 text-center text-gray-600">16</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">26</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">5</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">March 2026</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">31</td><td class="px-3 py-2 text-center text-gray-600">44</td><td class="px-3 py-2 text-center font-semibold text-pink-500 bg-pink-50">75</td>
                            <td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50 border-l border-gray-100">103</td><td class="px-3 py-2 text-center font-semibold text-teal-500 bg-teal-50">148</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">26</td><td class="px-3 py-2 text-center text-gray-600">38</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">64</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">21</td><td class="px-3 py-2 text-center text-gray-600">31</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">52</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-3 py-2 text-center text-gray-600">7</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">12</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">18</td><td class="px-3 py-2 text-center text-gray-600">27</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">45</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-3 py-2 text-center text-gray-600">4</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">7</td>
                        </tr>
                        <!-- TOTAL Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <td class="px-4 py-2 text-gray-800 font-bold">TOTAL</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">73</td><td class="px-3 py-2 text-center text-gray-700">111</td><td class="px-3 py-2 text-center font-bold text-pink-500 bg-pink-100">184</td>
                            <td class="px-3 py-2 text-center font-bold text-teal-500 bg-teal-100 border-l border-gray-100">285</td><td class="px-3 py-2 text-center font-bold text-teal-500 bg-teal-100">394</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">61</td><td class="px-3 py-2 text-center text-gray-700">94</td><td class="px-3 py-2 text-center font-bold text-cyan-500 bg-cyan-100">155</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">49</td><td class="px-3 py-2 text-center text-gray-700">76</td><td class="px-3 py-2 text-center font-bold text-green-500 bg-green-100">125</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">12</td><td class="px-3 py-2 text-center text-gray-700">18</td><td class="px-3 py-2 text-center font-bold text-red-400 bg-red-100">30</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">41</td><td class="px-3 py-2 text-center text-gray-700">65</td><td class="px-3 py-2 text-center font-bold text-orange-400 bg-orange-100">106</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">8</td><td class="px-3 py-2 text-center text-gray-700">11</td><td class="px-3 py-2 text-center font-bold text-purple-400 bg-purple-100">19</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/first-time.php" class="text-sm text-purple-600 hover:text-purple-800 font-medium">See More →</a>
            </div>
        </div>

        <!-- Job Fair Table -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-base">Job Fair</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-28" rowspan="2">MONTH</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-medium w-40 border-l border-gray-100" rowspan="2">PARTICIPATING EMPLOYER</th>
                            <th colspan="3" class="px-2 py-2 text-center text-blue-500 font-semibold tracking-wide border-l border-gray-100">JOB VACANCIES</th>
                            <th colspan="3" class="px-2 py-2 text-center text-cyan-500 font-semibold tracking-wide border-l border-gray-100">INTERVIEWED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-green-500 font-semibold tracking-wide border-l border-gray-100">QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-red-400 font-semibold tracking-wide border-l border-gray-100">NOT QUALIFIED</th>
                            <th colspan="3" class="px-2 py-2 text-center text-orange-400 font-semibold tracking-wide border-l border-gray-100">PLACED / HOTS</th>
                            <th colspan="3" class="px-2 py-2 text-center text-purple-400 font-semibold tracking-wide border-l border-gray-100">FOR FURTHER INTERVIEW</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-blue-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-cyan-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-green-500">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-red-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-orange-400">T</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium border-l border-gray-100">M</th>
                            <th class="px-3 py-1 text-center text-gray-500 font-medium">F</th>
                            <th class="px-3 py-1 text-center font-semibold text-purple-400">T</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">January 2026</td>
                            <td class="px-4 py-2 text-gray-600 border-l border-gray-100">Agro Prime Manpower...</td>
                            <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">30</td><td class="px-3 py-2 text-center text-blue-500">25</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">55</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">22</td><td class="px-3 py-2 text-center text-gray-600">18</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">40</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">18</td><td class="px-3 py-2 text-center text-gray-600">15</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">33</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">4</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">7</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">14</td><td class="px-3 py-2 text-center text-gray-600">12</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">26</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">4</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">7</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">January 2026</td>
                            <td class="px-4 py-2 text-gray-600 border-l border-gray-100">Pacific Direct Placement Corp.</td>
                            <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">20</td><td class="px-3 py-2 text-center text-blue-500">30</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">50</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">15</td><td class="px-3 py-2 text-center text-gray-600">22</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">37</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">12</td><td class="px-3 py-2 text-center text-gray-600">18</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">30</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-3 py-2 text-center text-gray-600">4</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">7</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-3 py-2 text-center text-gray-600">15</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">25</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">5</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">February 2026</td>
                            <td class="px-4 py-2 text-gray-600 border-l border-gray-100">Mindanao Build & Trade Co.</td>
                            <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">45</td><td class="px-3 py-2 text-center text-blue-500">15</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">60</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">38</td><td class="px-3 py-2 text-center text-gray-600">12</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">50</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">30</td><td class="px-3 py-2 text-center text-gray-600">10</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">40</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-3 py-2 text-center text-gray-600">2</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">10</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">25</td><td class="px-3 py-2 text-center text-gray-600">8</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">33</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">5</td><td class="px-3 py-2 text-center text-gray-600">2</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">7</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">February 2026</td>
                            <td class="px-4 py-2 text-gray-600 border-l border-gray-100">SunCorp Recruitment Agency</td>
                            <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">18</td><td class="px-3 py-2 text-center text-blue-500">32</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">50</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">14</td><td class="px-3 py-2 text-center text-gray-600">25</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">39</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">11</td><td class="px-3 py-2 text-center text-gray-600">20</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">31</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">3</td><td class="px-3 py-2 text-center text-gray-600">5</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">8</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">9</td><td class="px-3 py-2 text-center text-gray-600">17</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">26</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">5</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">March 2026</td>
                            <td class="px-4 py-2 text-gray-600 border-l border-gray-100">Horizon Logistics Services</td>
                            <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">35</td><td class="px-3 py-2 text-center text-blue-500">20</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">55</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">28</td><td class="px-3 py-2 text-center text-gray-600">16</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">44</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">22</td><td class="px-3 py-2 text-center text-gray-600">13</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">35</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">6</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">9</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">18</td><td class="px-3 py-2 text-center text-gray-600">11</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">29</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">4</td><td class="px-3 py-2 text-center text-gray-600">2</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">6</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700 font-medium">March 2026</td>
                            <td class="px-4 py-2 text-gray-600 border-l border-gray-100">BlueSeas Overseas Staffing</td>
                            <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">12</td><td class="px-3 py-2 text-center text-blue-500">28</td><td class="px-3 py-2 text-center font-semibold text-blue-500 bg-blue-50">40</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">10</td><td class="px-3 py-2 text-center text-gray-600">22</td><td class="px-3 py-2 text-center font-semibold text-cyan-500 bg-cyan-50">32</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">8</td><td class="px-3 py-2 text-center text-gray-600">16</td><td class="px-3 py-2 text-center font-semibold text-green-500 bg-green-50">26</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">2</td><td class="px-3 py-2 text-center text-gray-600">4</td><td class="px-3 py-2 text-center font-semibold text-red-400 bg-red-50">6</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">7</td><td class="px-3 py-2 text-center text-gray-600">15</td><td class="px-3 py-2 text-center font-semibold text-orange-400 bg-orange-50">22</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-l border-gray-100">1</td><td class="px-3 py-2 text-center text-gray-600">3</td><td class="px-3 py-2 text-center font-semibold text-purple-400 bg-purple-50">4</td>
                        </tr>
                        <!-- TOTALS Row -->
                        <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <td class="px-4 py-2 text-gray-800 font-bold" colspan="2">TOTALS</td>
                            <td class="px-3 py-2 text-center text-blue-500 border-l border-gray-100">160</td><td class="px-3 py-2 text-center text-blue-500">150</td><td class="px-3 py-2 text-center font-bold text-blue-500 bg-blue-100">310</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">127</td><td class="px-3 py-2 text-center text-gray-700">115</td><td class="px-3 py-2 text-center font-bold text-cyan-500 bg-cyan-100">242</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">101</td><td class="px-3 py-2 text-center text-gray-700">94</td><td class="px-3 py-2 text-center font-bold text-green-500 bg-green-100">195</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">26</td><td class="px-3 py-2 text-center text-gray-700">21</td><td class="px-3 py-2 text-center font-bold text-red-400 bg-red-100">47</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">83</td><td class="px-3 py-2 text-center text-gray-700">78</td><td class="px-3 py-2 text-center font-bold text-orange-400 bg-orange-100">161</td>
                            <td class="px-3 py-2 text-center text-gray-700 border-l border-gray-100">18</td><td class="px-3 py-2 text-center text-gray-700">16</td><td class="px-3 py-2 text-center font-bold text-purple-400 bg-purple-100">34</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end px-6 py-3 border-t border-gray-100">
                <a href="/pages/programs/emp-facilitation/job-fair.php" class="text-sm text-blue-600 hover:text-blue-800 font-medium">See More →</a>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>