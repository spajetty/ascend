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
        const categoryRow = summary.category
            ? `<div class="rounded-lg bg-gray-50 px-3 py-2"><strong>${summary.categoryLabel || 'Category'}:</strong> ${summary.category}</div>`
            : '';
        summaryEl.innerHTML = `
            <div class="rounded-lg bg-gray-50 px-3 py-2"><strong>Program:</strong> ${summary.program}</div>
            ${categoryRow}
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

// Program mismatch modal
export function ensureProgramMismatchModal() {
    let modal = document.getElementById('programMismatchModal');
    if (modal) return modal;

    modal = document.createElement('div');
    modal.id        = 'programMismatchModal';
    modal.className = 'fixed inset-0 z-50 hidden items-center justify-center p-4';
    modal.innerHTML = `
        <div id="programMismatchBackdrop" class="absolute inset-0 bg-black/30"></div>
        <div class="relative w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-2xl">⚠️</span> Program Mismatch Detected
                </h3>
                <p class="mt-2 text-sm text-gray-600">
                    Your Excel file contains program values in some rows that don't match your selected program.
                </p>
            </div>
            <div id="programMismatchDetails" class="rounded-lg bg-blue-50 border border-blue-200 p-4 mb-6 text-sm text-gray-700">
                <p><strong>You selected:</strong> <span id="selectedProgramText" class="text-blue-700 font-semibold">—</span></p>
                <p class="mt-2"><strong>File contains:</strong> <span id="fileProgramsText" class="text-blue-700 font-semibold">—</span></p>
                <p class="mt-2 text-red-600"><strong>Mismatches:</strong> <span id="mismatchCountText">0</span> rows</p>
            </div>
            <div id="programMismatchActions" class="space-y-3">
                <button id="programMismatchOption1" type="button" class="w-full px-4 py-3 text-left rounded-lg border-2 border-blue-600 bg-blue-50 hover:bg-blue-100 text-gray-900 text-sm font-medium transition-colors">
                    <div class="font-semibold text-blue-700">Use "<span id="option1ProgramText">—</span>" for all rows</div>
                    <div class="text-xs text-gray-600 mt-1">This will update all rows to match your selected program.</div>
                </button>
                <button id="programMismatchOption2" type="button" class="w-full px-4 py-3 text-left rounded-lg border-2 border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 text-gray-900 text-sm font-medium transition-colors">
                    <div class="font-semibold">Go back and fix issues</div>
                    <div class="text-xs text-gray-600 mt-1">Return to preview and upload a corrected file.</div>
                </button>
                <button id="programMismatchOption3" type="button" class="w-full px-4 py-3 text-left rounded-lg border-2 border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 text-gray-900 text-sm font-medium transition-colors">
                    <div class="font-semibold">Cancel import</div>
                    <div class="text-xs text-gray-600 mt-1">Cancel the import process.</div>
                </button>
            </div>
        </div>`;
    document.body.appendChild(modal);

    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
    modal.querySelector('#programMismatchBackdrop')?.addEventListener('click', closeModal);
    return modal;
}

export function openProgramMismatchModal(selectedProgram, mismatches, onOption1, onOption2, onOption3) {
    const modal = ensureProgramMismatchModal();
    if (!modal) return;

    const uniquePrograms = [...new Set(mismatches.map(m => m.excelProgram))];
    
    modal.querySelector('#selectedProgramText').textContent = selectedProgram;
    modal.querySelector('#fileProgramsText').textContent = uniquePrograms.join(', ');
    modal.querySelector('#mismatchCountText').textContent = mismatches.length;
    modal.querySelector('#option1ProgramText').textContent = selectedProgram;

    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
    
    modal.querySelector('#programMismatchOption1')?.addEventListener('click', () => {
        closeModal();
        onOption1();
    });
    
    modal.querySelector('#programMismatchOption2')?.addEventListener('click', () => {
        closeModal();
        onOption2();
    });
    
    modal.querySelector('#programMismatchOption3')?.addEventListener('click', () => {
        closeModal();
        onOption3();
    });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
