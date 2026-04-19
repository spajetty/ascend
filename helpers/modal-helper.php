<?php
/**
 * Renders a consistent modal structure.
 *
 * @param string $id      Unique ID for the modal.
 * @param string $title   The heading of the modal.
 * @param string $subtitle Optional subtitle or description.
 * @param string $content  The inner HTML content.
 * @param string $footer   The footer HTML (usually buttons).
 * @param string $maxWidth Tailwind max-width class (e.g., 'max-w-md').
 */
function renderModal($id, $title, $subtitle = '', $content = '', $footer = '', $maxWidth = 'max-w-md') {
    ?>
    <div id="<?= htmlspecialchars($id) ?>" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 modal-backdrop" onclick="if(window.hideModal) hideModal('<?= htmlspecialchars($id) ?>')"></div>

        <!-- Panel -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full <?= htmlspecialchars($maxWidth) ?> p-6 animate-modal">

            <!-- Header -->
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900"><?= htmlspecialchars($title) ?></h3>
                    <?php if ($subtitle): ?>
                        <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($subtitle) ?></p>
                    <?php endif; ?>
                </div>
                <button onclick="if(window.hideModal) hideModal('<?= htmlspecialchars($id) ?>')" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="modal-content">
                <?= $content ?>
            </div>

            <!-- Footer -->
            <?php if ($footer): ?>
                <div class="mt-6">
                    <?= $footer ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Script to ensure window.hideModal exists -->
    <script>
        if (typeof window.hideModal !== 'function') {
            window.hideModal = function(id) {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            };
        }
        if (typeof window.showModal !== 'function') {
            window.showModal = function(id) {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            };
        }
    </script>
    <?php
}
