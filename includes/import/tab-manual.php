<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../../pages/imports/assets/css/manual-form.css?v=<?= time() ?>">
<script type="module" src="../../assets/js/toast.js?v=<?= time() ?>"></script>
<script type="module" src="../../pages/beneficiaries/assets/js/manual.js?v=<?= time() ?>"></script>

<!-- ─── TAB CONTENT: MANUAL ─── -->
<div id="tab-manual" class="tab-content hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form id="manualEntryForm" novalidate>

            <!-- ── Section & Program (drives dynamic fields below) ── -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 ">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Section <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <select id="manualSection"
                            class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Select a section…</option>
                            <option value="1">Employment Facilitation</option>
                            <option value="2">Employers Engagement</option>
                            <option value="3">Youth Employability</option>
                            <option value="4">Career Development</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Program <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <select id="manualProgram"
                            class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <option value="">Select a section first…</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- ── Progress Bar ──────────────────────────────────────── -->
            <div class="mf-progress-bar">
                <div class="mf-progress-fill" id="mf-prog-fill" style="width:0%"></div>
            </div>

            <!-- ── Step Tabs ─────────────────────────────────────────── -->
            <div class="mf-stepper">
                <div class="mf-step-tab" id="mf-stab-1" data-step="1">
                    <div class="mf-step-num" id="mf-snum-1">1</div>
                    <div class="mf-step-label">Beneficiary</div>
                </div>
                <div class="mf-step-tab" id="mf-stab-2" data-step="2">
                    <div class="mf-step-num" id="mf-snum-2">2</div>
                    <div class="mf-step-label">Program Details</div>
                </div>
                <div class="mf-step-tab" id="mf-stab-3" data-step="3">
                    <div class="mf-step-num" id="mf-snum-3">3</div>
                    <div class="mf-step-label">Documents</div>
                </div>
                <div class="mf-step-tab" id="mf-stab-4" data-step="4">
                    <div class="mf-step-num" id="mf-snum-4">4</div>
                    <div class="mf-step-label">Review</div>
                </div>
            </div>


            <?php require_once 'manual-panels/panel-0-empty.php'; ?>
            <?php require_once 'manual-panels/panel-1-beneficiary.php'; ?>
            <?php require_once 'manual-panels/panel-2-program.php'; ?>
            <?php require_once 'manual-panels/panel-3-documents.php'; ?>
            <?php require_once 'manual-panels/panel-4-review.php'; ?>
            <?php require_once 'manual-panels/panel-5-success.php'; ?>

        </form>
    </div>
</div>