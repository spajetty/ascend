// Import confirmation modal (pre-import summary + confirm/cancel).

export function ensureImportConfirmModal() {
    let modal = document.getElementById('importConfirmModal');
    if (modal) return modal;

    modal = document.createElement('div');
    modal.id        = 'importConfirmModal';
    modal.className = 'fixed inset-0 z-50 hidden items-center justify-center p-4';
    modal.innerHTML = `
        <div id="importConfirmBackdrop" class="absolute inset-0 bg-black/40"></div>
        <div class="relative w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">✔️</div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Confirm Import</h3>
                    <p class="mt-1 text-sm text-gray-500">Review key details — import will begin after confirmation.</p>
                </div>
            </div>
            <div id="importConfirmSummary" class="space-y-2 text-sm text-gray-700"></div>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-xs text-gray-500">Tip: You can cancel to review the preview first.</div>
                <div class="flex items-center justify-end gap-3">
                    <button id="importConfirmCancel"  type="button" class="whitespace-nowrap rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-800">Cancel</button>
                    <button id="importConfirmProceed" type="button" class="whitespace-nowrap rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Import Now</button>
                </div>
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
            ? `<div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2"><div><strong>${summary.categoryLabel || 'Category'}:</strong> ${summary.category}</div></div>`
            : '';
        const contractPeriodRow = summary.contractPeriod
            ? `<div class="rounded-lg border-l-4 border-blue-600 bg-blue-50 px-3 py-2"><div class="text-xs text-blue-700 font-semibold">Contract Period</div><div class="mt-1 text-sm text-blue-900">${summary.contractPeriod}</div></div>`
            : '';

        // Build a compact definition-list style summary.
        summaryEl.innerHTML = `
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50/70">
                <div class="grid grid-cols-[8rem_1fr] border-b border-gray-200 px-4 py-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Program</div>
                    <div class="font-medium text-gray-900">${summary.program}</div>
                </div>
                <div class="grid grid-cols-[8rem_1fr] border-b border-gray-200 px-4 py-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Period</div>
                    <div class="font-medium text-gray-900">${summary.period}</div>
                </div>
                ${categoryRow ? `<div class="grid grid-cols-[8rem_1fr] border-b border-gray-200 px-4 py-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">${summary.categoryLabel || 'Category'}</div>
                    <div class="font-medium text-gray-900">${summary.category}</div>
                </div>` : ''}
                <div class="grid grid-cols-[8rem_1fr] border-b border-gray-200 px-4 py-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">File</div>
                    <div class="font-medium text-gray-900 break-all">${summary.fileName}</div>
                </div>
                <div class="grid grid-cols-[8rem_1fr] border-b border-gray-200 px-4 py-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Rows</div>
                    <div class="font-medium text-gray-900">${summary.rowsToImport}</div>
                </div>
                <div class="grid grid-cols-[8rem_1fr] px-4 py-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Skipped</div>
                    <div class="font-medium text-gray-900">${summary.skipped} (${summary.duplicates} dup, ${summary.invalid} inv)</div>
                </div>
            </div>
            <div class="mt-3">${contractPeriodRow}</div>`;
    }

    const proceedBtn = modal.querySelector('#importConfirmProceed');
    if (proceedBtn) {
        proceedBtn.onclick = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // small delay so modal closes cleanly before heavy work
            setTimeout(() => onConfirm(), 120);
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
        <div id="programMismatchBackdrop" class="absolute inset-0 bg-black/40"></div>
        <div class="relative w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700">⚠️</div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Program Mismatch Detected</h3>
                    <p class="mt-1 text-sm text-gray-600">Some rows in the file use a different program value than the one selected. Choose how to proceed.</p>
                </div>
            </div>
            <div id="programMismatchDetails" class="rounded-lg bg-gray-50 border border-gray-100 p-4 mb-4 text-sm text-gray-700">
                <div class="flex items-center justify-between">
                    <div><strong class="text-xs text-gray-500">You selected</strong><div id="selectedProgramText" class="text-sm font-semibold text-gray-900">—</div></div>
                    <div><strong class="text-xs text-gray-500">Mismatches</strong><div id="mismatchCountText" class="text-sm font-semibold text-red-600">0</div></div>
                </div>
                <div class="mt-3 text-xs text-gray-600">File contains these program values (counts):</div>
                <div id="fileProgramsList" class="mt-2 flex flex-wrap gap-2"></div>
            </div>
            <div id="programMismatchPreview" class="mb-4 text-sm text-gray-700"></div>
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
    // compute program counts
    const counts = {};
    mismatches.forEach(m => { const p = m.excelProgram || '(empty)'; counts[p] = (counts[p]||0) + 1; });

    modal.querySelector('#selectedProgramText').textContent = selectedProgram;
    modal.querySelector('#mismatchCountText').textContent = mismatches.length;
    modal.querySelector('#option1ProgramText').textContent = selectedProgram;

    const fileProgramsList = modal.querySelector('#fileProgramsList');
    fileProgramsList.innerHTML = Object.keys(counts).map(p => `
        <div class="px-2 py-1 rounded-full bg-white border border-gray-200 text-xs text-gray-700 flex items-center gap-2">
            <span class="font-medium">${p}</span>
            <span class="ml-2 inline-block bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">${counts[p]}</span>
        </div>
    `).join('');

    // small preview of first 4 mismatched rows (if data available)
    const previewEl = modal.querySelector('#programMismatchPreview');
    if (mismatches.length) {
        const previewRows = mismatches.slice(0,4).map(r => {
            const rowNum = r.rowIndex ?? r.row ?? '?';
            return `<div class="flex justify-between py-1 border-b last:border-b-0"><div class="text-xs text-gray-700">Row ${rowNum}:</div><div class="text-xs font-medium text-gray-900">${r.excelProgram}</div></div>`;
        }).join('');
        previewEl.innerHTML = `<div class="rounded-md border border-gray-100 p-2 bg-white">${previewRows}</div>`;
    } else previewEl.innerHTML = '';

    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };

    // Use onclick to avoid duplicate listeners on repeated opens
    const btn1 = modal.querySelector('#programMismatchOption1');
    const btn2 = modal.querySelector('#programMismatchOption2');
    const btn3 = modal.querySelector('#programMismatchOption3');

    if (btn1) btn1.onclick = () => { closeModal(); setTimeout(() => onOption1(), 80); };
    if (btn2) btn2.onclick = () => { closeModal(); setTimeout(() => onOption2(), 80); };
    if (btn3) btn3.onclick = () => { closeModal(); setTimeout(() => onOption3(), 80); };

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// ─── Unknown employers modal ──────────────────────────────────────────────────
export function ensureUnknownEmployersModal() {
    let modal = document.getElementById('unknownEmployersModal');
    if (modal) return modal;

    modal = document.createElement('div');
    modal.id        = 'unknownEmployersModal';
    modal.className = 'fixed inset-0 z-50 hidden items-center justify-center p-4';
    modal.innerHTML = `
        <div id="unknownEmployersBackdrop" class="absolute inset-0 bg-black/40"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700">⚠️</div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Unregistered Employers Found</h3>
                    <p class="mt-1 text-sm text-gray-600">The following employers are not yet registered in the system:</p>
                </div>
            </div>
            <div id="unknownEmployersList" class="mb-4 rounded-lg bg-yellow-50 border border-yellow-100 p-4 max-h-48 overflow-y-auto"></div>
            <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 mb-4">
                <p class="text-sm text-blue-700">You can continue and automatically add them, or cancel and register them first for better data consistency.</p>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button id="unknownEmployersCancel" type="button" class="whitespace-nowrap rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-800">Cancel Import</button>
                <button id="unknownEmployersProceed" type="button" class="whitespace-nowrap rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Continue & Auto-Create</button>
            </div>
        </div>`;
    document.body.appendChild(modal);

    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
    modal.querySelector('#unknownEmployersBackdrop')?.addEventListener('click', closeModal);
    modal.querySelector('#unknownEmployersCancel')?.addEventListener('click', closeModal);
    return modal;
}

export function openUnknownEmployersModal(employers, onProceed, onCancel) {
    const modal = ensureUnknownEmployersModal();
    if (!modal) return;

    const listEl = modal.querySelector('#unknownEmployersList');
    if (listEl) {
        const escapeHtml = value => String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        listEl.innerHTML = employers.map(name => `
            <div class="flex items-start gap-2 py-2 border-b last:border-b-0">
                <span class="text-yellow-600 flex-shrink-0">•</span>
                <span class="text-sm text-gray-900">${escapeHtml(name)}</span>
            </div>
        `).join('');
    }

    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };

    const proceedBtn = modal.querySelector('#unknownEmployersProceed');
    const cancelBtn = modal.querySelector('#unknownEmployersCancel');

    if (proceedBtn) {
        proceedBtn.onclick = () => {
            closeModal();
            setTimeout(() => onProceed?.(), 80);
        };
    }

    if (cancelBtn) {
        cancelBtn.onclick = () => {
            closeModal();
            setTimeout(() => onCancel?.(), 80);
        };
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
