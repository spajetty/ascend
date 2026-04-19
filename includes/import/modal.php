<?php
// ─── Section / Program Modal ──────────────────────────────────────────────────

ob_start(); ?>
    <!-- Selected file recap -->
    <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 mb-5">
        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
        </div>
        <p id="modalFileNameText" class="text-sm font-semibold text-gray-700 truncate"></p>
    </div>

    <!-- Section dropdown -->
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Section</label>
        <div class="relative">
            <select id="sectionSelect"
                class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <option value="">Select a section…</option>
                <option value="employment_facilitation">Employment Facilitation</option>
                <option value="employers_engagement">Employers Engagement</option>
                <option value="youth_employability">Youth Employability</option>
                <option value="career_development">Career Development</option>
            </select>
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </div>
    </div>

    <!-- Program dropdown -->
    <div class="mb-2">
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Program</label>
        <div class="relative">
            <select id="programSelect"
                class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <option value="">Select a section first…</option>
            </select>
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </div>
    </div>
<?php
$modalContent = ob_get_clean();

ob_start(); ?>
    <div class="flex gap-3">
        <button id="modalCancel" onclick="hideModal('sectionModal')"
            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-500 hover:bg-gray-50 transition-colors">
            Cancel
        </button>
        <button id="modalConfirm"
            class="flex-1 px-4 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            Upload & Preview
        </button>
    </div>
<?php
$modalFooter = ob_get_clean();

renderModal(
    'sectionModal',
    'Assign Section & Program',
    'Select where this data belongs before uploading',
    $modalContent,
    $modalFooter
);
?>

<script src="/assets/js/import.js" defer></script>