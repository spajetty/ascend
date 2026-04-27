// Import confirmation modal (pre-import summary + confirm/cancel).

export function ensureImportConfirmModal() {
    let modal = document.getElementById('importConfirmModal');
    if (modal) return modal;

    modal = document.createElement('div');
    modal.id        = 'importConfirmModal';
    modal.className = 'fixed inset-0 z-50 hidden items-center justify-center p-4';
    modal.innerHTML = `
        <div id="importConfirmBackdrop" class="absolute inset-0 bg-black/30"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4">
                <h3 class="text-base font-bold text-gray-900">Confirm Import</h3>
                <p class="mt-1 text-sm text-gray-500">Please verify these details before importing.</p>
            </div>
            <div id="importConfirmSummary" class="space-y-2 text-sm text-gray-700"></div>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button id="importConfirmCancel"  type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Back</button>
                <button id="importConfirmProceed" type="button" class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">Confirm Import</button>
            </div>
        </div>`;
    document.body.appendChild(modal);

    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
    modal.querySelector('#importConfirmBackdrop')?.addEventListener('click', closeModal);
    modal.querySelector('#importConfirmCancel')?.addEventListener('click', closeModal);
    return modal;
}

export function openImportConfirmModal(summary, onConfirm) {
    const modal = ensureImportConfirmModal();
    if (!modal) return;

    const summaryEl = modal.querySelector('#importConfirmSummary');
    if (summaryEl) {
        summaryEl.innerHTML = `
            <div class="rounded-lg bg-gray-50 px-3 py-2"><strong>Program:</strong> ${summary.program}</div>
            <div class="rounded-lg bg-gray-50 px-3 py-2"><strong>Period:</strong> ${summary.period}</div>
            <div class="rounded-lg bg-gray-50 px-3 py-2"><strong>File:</strong> ${summary.fileName}</div>
            <div class="rounded-lg bg-gray-50 px-3 py-2"><strong>Rows to import:</strong> ${summary.rowsToImport}</div>
            <div class="rounded-lg bg-gray-50 px-3 py-2"><strong>Skipped:</strong> ${summary.skipped} (${summary.duplicates} duplicate, ${summary.invalid} invalid)</div>`;
    }

    const proceedBtn = modal.querySelector('#importConfirmProceed');
    if (proceedBtn) {
        proceedBtn.onclick = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            onConfirm();
        };
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
