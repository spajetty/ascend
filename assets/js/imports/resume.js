import { programs } from './config.js';
import { formatBytes, previewTableRows, previewTableHeaders } from './common.js';
import { showToast } from '../toast.js';

// ─── RESUME TAB ───────────────────────────────────────────────────────────────
const resumeInput    = document.getElementById('resumeInput');
const resumeDropZone = document.getElementById('resumeDropZone');
const resumeFileList = document.getElementById('resumeFileList');
const resumeBrowseBtn = document.getElementById('resumeBrowseBtn');

// ─── Dropdown Cascade & UI Enablement ──────────────────────────────────────────
const resumeSection = document.getElementById('resumeSection');
const resumeProgram = document.getElementById('resumeProgram');

if (resumeSection && resumeProgram) {
    resumeSection.addEventListener('change', function () {
        const val = this.value;
        if (val && typeof programs !== 'undefined' && programs[val]) {
            resumeProgram.innerHTML = '<option value="">Select a program…</option>' +
                programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
            resumeProgram.disabled = false;
        } else {
            resumeProgram.innerHTML = '<option value="">Select a section first…</option>';
            resumeProgram.disabled = true;
        }

        // Reset dropzone state
        if (resumeDropZone && resumeBrowseBtn) {
            resumeDropZone.classList.add('opacity-40', 'pointer-events-none');
            resumeBrowseBtn.disabled = true;
        }
    });

    resumeProgram.addEventListener('change', function () {
        if (resumeDropZone && resumeBrowseBtn) {
            if (this.value) {
                resumeDropZone.classList.remove('opacity-40', 'pointer-events-none');
                resumeBrowseBtn.disabled = false;
            } else {
                resumeDropZone.classList.add('opacity-40', 'pointer-events-none');
                resumeBrowseBtn.disabled = true;
            }
        }
    });
}

let resumeFiles = [];

function renderResumeFileList() {
    if (resumeFiles.length === 0) {
        resumeFileList.classList.add('hidden');
        return;
    }
    resumeFileList.classList.remove('hidden');
    resumeFileList.innerHTML = resumeFiles.map((f, i) => `
        <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">${f.name}</p>
                    <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                        ${formatBytes(f.size)}
                        <span class="text-gray-300">•</span>
                        <span class="text-emerald-500 font-medium">Ready</span>
                    </p>
                </div>
            </div>
            <button onclick="removeResumeFile(${i})" class="p-2 hover:bg-red-50 rounded-lg transition-colors group">
                <svg class="w-4 h-4 text-gray-400 group-hover:text-red-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                </svg>
            </button>
        </div>
    `).join('');
}

window.removeResumeFile = function (index) {
    resumeFiles.splice(index, 1);
    renderResumeFileList();
    if (resumeFiles.length === 0) {
        document.getElementById('resumePreview').classList.add('hidden');
    }
};

function handleResumeFiles(files) {
    const allowed = ['.pdf', '.doc', '.docx'];
    const maxSize = 5 * 1024 * 1024;

    Array.from(files).forEach(f => {
        const ext = '.' + f.name.split('.').pop().toLowerCase();
        if (!allowed.includes(ext)) {
            showToast(`${f.name}: unsupported format. Use PDF, DOC, or DOCX.`, 'error');
            return;
        }
        if (f.size > maxSize) {
            showToast(`${f.name}: exceeds 5MB limit.`, 'error');
            return;
        }
        if (!resumeFiles.find(r => r.name === f.name && r.size === f.size)) {
            resumeFiles.push(f);
        }
    });

    if (resumeFiles.length === 0) return;

    const sectionEl = document.getElementById('resumeSection');
    const section   = sectionEl ? sectionEl.options[sectionEl.selectedIndex].text : '';
    const program   = document.getElementById('resumeProgram') ? document.getElementById('resumeProgram').value : '';

    if (!program) { 
        showToast('Please select a program first.', 'warning'); 
        return; 
    }

    renderResumeFileList();

    // Directly evaluate and show preview based on selected inline dropdowns
    showResumePreview(section, program);
}

if (resumeInput) resumeInput.addEventListener('change', () => handleResumeFiles(resumeInput.files));

if (resumeDropZone) {
    resumeDropZone.addEventListener('dragover', e => {
        e.preventDefault();
        resumeDropZone.classList.add('border-red-400', 'bg-red-50/20');
    });
    resumeDropZone.addEventListener('dragleave', () => {
        resumeDropZone.classList.remove('border-red-400', 'bg-red-50/20');
    });
    resumeDropZone.addEventListener('drop', e => {
        e.preventDefault();
        resumeDropZone.classList.remove('border-red-400', 'bg-red-50/20');
        handleResumeFiles(e.dataTransfer.files);
    });
}

export function showResumePreview(section, program) {
    // TODO: Replace with real backend extraction response
    const mockRows = resumeFiles.map((f, i) => ({
        name:    ['Maria Santos', 'Jose Dela Cruz', 'Anna Reyes', 'Miguel Torres', 'Liza Gomez'][i % 5],
        gender:  i % 2 === 0 ? 'Female' : 'Male',
        section,
        program,
        status:  ['Registered', 'Referred', 'Employed'][i % 3],
        contact: `0917${String(1234567 + i).padStart(7, '0')}`,
    }));

    document.getElementById('resumePreviewMeta').textContent =
        `${mockRows.length} resume${mockRows.length > 1 ? 's' : ''} processed • ${Object.keys(mockRows[0]).length} columns`;
    
    const thead = document.querySelector('#resumePreview thead tr');
    if (thead) {
        thead.innerHTML = previewTableHeaders(mockRows[0]);
    }
    document.getElementById('resumePreviewBody').innerHTML = previewTableRows(mockRows);

    const preview = document.getElementById('resumePreview');
    preview.classList.remove('hidden');
    preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

const cancelResumeBtn = document.getElementById('cancelResumeImport');
if (cancelResumeBtn) {
    cancelResumeBtn.addEventListener('click', () => {
        document.getElementById('resumePreview').classList.add('hidden');
        resumeFiles = [];
        resumeInput.value = '';
        renderResumeFileList();
    });
}

const confirmResumeBtn = document.getElementById('confirmResumeImport');
if (confirmResumeBtn) {
    confirmResumeBtn.addEventListener('click', () => {
        // TODO: Submit to your PHP resume import endpoint via fetch/form
        showToast('Resume import submitted! Connect this to your backend endpoint.', 'success');
    });
}
