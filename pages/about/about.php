<?php
require_once __DIR__ . '/../../includes/auth-check.php';

$currentPage = 'about';
$pageTitle   = 'ASCEND PED System – About';
$pageHeading = 'About ASCEND';

require_once __DIR__ . '/../../includes/layout/head.php';
require_once __DIR__ . '/../../includes/layout/sidebar.php';
?>

<main id="mainContent" class="flex-1 md:ml-56 min-h-screen flex flex-col bg-slate-50 overflow-x-hidden">
    <?php require_once __DIR__ . '/../../includes/layout/topbar.php'; ?>

    <div class="flex-1 overflow-y-auto preview-scrollbar relative scroll-smooth">
        
        <!-- Decorative Background Elements -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-orange-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 pt-8 pb-24 md:py-16 space-y-24 relative z-10">

            <!-- 1. Hero Section -->
            <section class="flex flex-col lg:flex-row items-center justify-between gap-12 bg-white/80 backdrop-blur-xl rounded-[3rem] p-10 md:p-16 shadow-2xl border border-white/50">
                <div class="flex-1 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-gradient-to-r from-orange-50 to-red-50 border border-orange-100 text-sm font-bold text-orange-600 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        PESO Public Employment Division
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 via-red-500 to-yellow-500">ASCEND</span>
                    </h1>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                        Automated System for Centralized Employment and Numerical Data
                    </h2>
                    <p class="text-gray-500 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto lg:mx-0 mt-4">
                        An internal Employment Data Management and Reporting System for the PESO Public Employment Division, designed to streamline beneficiary tracking, employer accreditation, and empower staff with actionable data insights.
                    </p>
                    <div class="flex items-center justify-center lg:justify-start gap-4 pt-4">
                        <a href="#features" class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-gray-800 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            Explore Features
                        </a>
                        <a href="#mission" class="px-8 py-4 bg-white text-gray-900 border border-gray-200 rounded-2xl font-bold hover:bg-gray-50 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            Our Mission
                        </a>
                    </div>
                </div>
                <div class="flex-1 flex justify-center lg:justify-end">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-tr from-orange-400 to-red-500 rounded-full blur-2xl opacity-40 group-hover:opacity-60 transition-opacity duration-500"></div>
                        <div class="relative w-64 h-64 md:w-80 md:h-80 rounded-[3rem] bg-white p-8 shadow-2xl border border-white/50 transform transition duration-500 hover:scale-105 hover:rotate-3 flex items-center justify-center">
                            <img src="/assets/images/logo.png" alt="ASCEND Logo" class="w-full h-full object-contain drop-shadow-xl transition-transform duration-500 group-hover:scale-110">
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Mission & Vision -->
            <section id="mission" class="scroll-mt-32">
                <div class="text-center mb-12 space-y-4">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Empowering the PESO Workforce</h2>
                    <p class="text-gray-500 text-lg max-w-2xl mx-auto">An internal platform dedicated to assisting PESO employees in managing and organizing critical employment data efficiently.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-10 rounded-3xl border border-blue-100/50 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                        <p class="text-gray-600 leading-relaxed">
                            To provide PESO employees with a centralized, highly efficient databank system that accurately tracks employment programs, manages records seamlessly, and delivers internal analytics to support data-driven governance.
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-10 rounded-3xl border border-emerald-100/50 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <div class="w-14 h-14 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                        <p class="text-gray-600 leading-relaxed">
                            A highly organized and equipped Public Employment Service Office, supported by a modern infrastructure that completely eliminates administrative bottlenecks and optimizes daily internal operations.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 3. Key Features -->
            <section id="features" class="scroll-mt-32">
                <div class="bg-white/70 backdrop-blur-md rounded-[3rem] p-10 md:p-16 shadow-xl border border-white/50">
                    <div class="text-center mb-16 space-y-4">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">System Capabilities</h2>
                        <p class="text-gray-500 text-lg max-w-2xl mx-auto">Discover the powerful tools that make ASCEND the perfect solution for employment databanking.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="p-8 rounded-3xl bg-white border border-gray-100 hover:border-orange-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300 shadow-sm">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Beneficiary Tracking</h3>
                            <p class="text-gray-500 leading-relaxed">Maintain comprehensive records of applicants. Track their history across various employment programs from registration to hiring.</p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="p-8 rounded-3xl bg-white border border-gray-100 hover:border-red-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 mb-6 group-hover:scale-110 group-hover:bg-red-500 group-hover:text-white transition-all duration-300 shadow-sm">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Employer Network</h3>
                            <p class="text-gray-500 leading-relaxed">A centralized hub for managing accredited employers. Keep track of active job vacancies and streamline the matching process.</p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="p-8 rounded-3xl bg-white border border-gray-100 hover:border-yellow-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                            <div class="w-16 h-16 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-600 mb-6 group-hover:scale-110 group-hover:bg-yellow-500 group-hover:text-white transition-all duration-300 shadow-sm">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Insights & Analytics</h3>
                            <p class="text-gray-500 leading-relaxed">Instantly generate demographic reports, placement statistics, and visual charts to monitor the performance of employment initiatives.</p>
                        </div>
                    </div>
                </div>
            </section>


            <!-- 5. Footer / Version Note -->
            <div class="pt-8 border-t border-gray-200/60 text-center flex flex-col items-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-semibold text-gray-600 mb-4 shadow-sm">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    Built with PHP & Tailwind CSS
                </div>
                <p class="text-sm text-gray-400 font-medium">Version 1.0.0 &copy; <?php echo date('Y'); ?> ASCEND System.</p>
            </div>

        </div>
    </div>
</main>

<style>
    /* Decorative blobs animation */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

<?php require_once __DIR__ . '/../../includes/layout/footer.php'; ?>
