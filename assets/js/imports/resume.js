import { programs, statusesByProgram } from './config.js';
import { formatBytes, previewTableRows, previewTableHeaders } from './common.js';
import { showToast } from '../toast.js';

// ─── RESUME TAB ───────────────────────────────────────────────────────────────
const resumeInput = document.getElementById('resumeInput');
const resumeDropZone = document.getElementById('resumeDropZone');
const resumeFileList = document.getElementById('resumeFileList');
const resumeBrowseBtn = document.getElementById('resumeBrowseBtn');

// ─── Dropdown Cascade & UI Enablement ─────────────────────────────────────────
const resumeSection = document.getElementById('resumeSection');
const resumeProgram = document.getElementById('resumeProgram');

function getResumeProgramOptions(sectionKey) {
    const values = Array.isArray(programs?.[sectionKey]) ? programs[sectionKey] : [];
    if (sectionKey === 'employers_engagement') {
        return values.filter(value => value === 'Workers Hiring for Infrastructure Projects - Beneficiaries');
    }
    return values;
}

function getResumeProgramLabel(program) {
    if (program === 'Workers Hiring for Infrastructure Projects - Beneficiaries') {
        return "Worker's Hiring for Infrastructure Project";
    }
    return program;
}

if (resumeSection && resumeProgram) {
    resumeSection.addEventListener('change', function () {
        const val = this.value;
        const options = getResumeProgramOptions(val);
        if (val && options.length > 0) {
            resumeProgram.innerHTML =
                '<option value="">Select a program…</option>' +
                options.map(p => `<option value="${p}">${getResumeProgramLabel(p)}</option>`).join('');
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

function updateResumeSelectionLock() {
    const hasFile = resumeFiles.length > 0;
    const hasSection = Boolean(resumeSection?.value);
    const hasProgram = Boolean(resumeProgram?.value);

    if (resumeSection) {
        resumeSection.disabled = hasFile;
    }

    if (resumeProgram) {
        resumeProgram.disabled = hasFile || !hasSection;
    }

    if (resumeDropZone && resumeBrowseBtn) {
        const disableUpload = hasFile || !hasProgram;
        resumeDropZone.classList.toggle('opacity-40', disableUpload);
        resumeDropZone.classList.toggle('pointer-events-none', disableUpload);
        resumeBrowseBtn.disabled = disableUpload;
    }
}

// ─── File list renderer ────────────────────────────────────────────────────────
function renderResumeFileList() {
    if (resumeFiles.length === 0) {
        resumeFileList.classList.add('hidden');
        updateResumeSelectionLock();
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
    updateResumeSelectionLock();
}

window.removeResumeFile = function (index) {
    resumeFiles.splice(index, 1);
    renderResumeFileList();
    if (resumeInput) resumeInput.value = '';
    if (resumeFiles.length === 0) {
        document.getElementById('resumePreview').classList.add('hidden');
    }
};

// ─── File validation & handling ────────────────────────────────────────────────
function handleResumeFiles(files) {
    const allowed = ['.pdf', '.doc', '.docx'];
    const maxSize = 5 * 1024 * 1024;

    if (files.length > 1) {
        showToast('Please import only one resume at a time.', 'warning');
    }

    const f = files[0];
    if (!f) return;

    resumeFiles = []; // Clear existing files to restrict to single file
    updateResumeSelectionLock();

    const ext = '.' + f.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
        showToast(`${f.name}: unsupported format. Use PDF, DOC, or DOCX.`, 'error');
        return;
    }
    if (f.size > maxSize) {
        showToast(`${f.name}: exceeds 5MB limit.`, 'error');
        return;
    }
    resumeFiles.push(f);

    if (resumeFiles.length === 0) return;

    const sectionEl = document.getElementById('resumeSection');
    const section = sectionEl ? sectionEl.options[sectionEl.selectedIndex].text : '';
    const program = document.getElementById('resumeProgram')?.value ?? '';

    if (!program) {
        resumeFiles = [];
        updateResumeSelectionLock();
        showToast('Please select a program first.', 'warning');
        return;
    }

    renderResumeFileList();
    showResumePreview(section, program);
}

if (resumeInput) {
    resumeInput.addEventListener('change', () => handleResumeFiles(resumeInput.files));
}

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

// ─── PHP parse call ────────────────────────────────────────────────────────────
async function parseResumeFile(file) {
    const formData = new FormData();
    formData.append('resume', file);

    const res = await fetch('../../backend/resume/parse_resume.php', {
        method: 'POST',
        body: formData,
    });
    const json = await res.json();
    if (!json.success) throw new Error(json.error || 'Parse failed');
    return json.data;
}

// ─── Preview builder ───────────────────────────────────────────────────────────
export async function showResumePreview(section, program) {
    const preview = document.getElementById('resumePreview');
    const previewMeta = document.getElementById('resumePreviewMeta');
    const previewBody = document.getElementById('resumePreviewBody');
    const confirmBtn = document.getElementById('confirmResumeImport');

    // Show panel in loading state
    preview.classList.remove('hidden');
    previewMeta.textContent = 'Extracting data…';
    previewBody.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12 px-4 rounded-xl border border-gray-100 bg-gray-50/50">
            <svg class="w-8 h-8 text-red-500 animate-spin mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="2" x2="12" y2="6"></line>
                <line x1="12" y1="18" x2="12" y2="22"></line>
                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                <line x1="2" y1="12" x2="6" y2="12"></line>
                <line x1="18" y1="12" x2="22" y2="12"></line>
                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
            </svg>
            <p class="text-sm font-semibold text-gray-700">Analyzing resume content...</p>
            <p class="text-xs text-gray-400 mt-1">Please wait while we extract the information.</p>
        </div>`;
    confirmBtn.disabled = true;
    preview.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Parse each file sequentially
    const results = [];
    for (const file of resumeFiles) {
        try {
            const data = await parseResumeFile(file);
            results.push({ file: file.name, data, error: null });
        } catch (err) {
            results.push({ file: file.name, data: null, error: err.message });
        }
    }

    // Stash for confirm step
    window._resumeResults = results;
    window._resumeProgram = program;
    window._resumeSection = section;

    const successCount = results.filter(r => !r.error && !r.data._is_duplicate).length;
    const hasDuplicates = results.some(r => !r.error && r.data._is_duplicate);

    let message = `${successCount} of ${results.length} resume${results.length > 1 ? 's' : ''} parsed • Review before importing`;
    if (hasDuplicates) {
        message += ' (Duplicates cannot be imported)';
    }
    previewMeta.textContent = message;

    // ── Field helpers ──────────────────────────────────────────────────────────
    const fieldInput = (field, label, value, placeholder = '', type = 'text') => `
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">${label}</label>
            <input
                type="${type}"
                data-field="${field}"
                class="resume-field w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-300 focus:bg-white transition"
                value="${String(value ?? '').replace(/"/g, '&quot;')}"
                ${placeholder ? `placeholder="${placeholder}"` : ''}
            >
        </div>`;

    const fieldCheck = (field, label, value) => `
        <label class="flex items-center gap-2.5 cursor-pointer select-none py-1">
            <input
                type="checkbox"
                data-field="${field}"
                class="resume-field w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-300 accent-red-500"
                ${value ? 'checked' : ''}
            >
            <span class="text-sm font-medium text-gray-700">${label}</span>
        </label>`;

    const sectionLabel = (title) => `
        <div class="col-span-full text-xs font-bold text-gray-400 uppercase tracking-widest pt-2 pb-0.5 border-b border-gray-100">
            ${title}
        </div>`;

    // Determine if this program is WHIP (Workers Hiring for Infrastructure Projects)
    const isWhipProgram = ['Workers Hiring for Infrastructure Projects - Beneficiaries', 'Workers Hiring for Infrastructure Projects'].includes(program);

    // ── Render cards ───────────────────────────────────────────────────────────
    previewBody.innerHTML = results.map((r, i) => {
        if (r.error) {
            return `
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-700">${r.file}</p>
                    <p class="text-sm text-red-500 mt-0.5">${r.error}</p>
                </div>
            </div>`;
        }

        const d = r.data;
        const isDup = d._is_duplicate;
        
        return `
        <div class="rounded-xl border ${isDup ? 'border-yellow-200 bg-yellow-50/30' : 'border-gray-100 bg-gray-50/50'} overflow-hidden" data-index="${i}">

            <!-- Card header -->
            <div class="flex items-center gap-3 px-5 py-3 bg-white border-b ${isDup ? 'border-yellow-100' : 'border-gray-100'}">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">${r.file}</p>
                ${isDup 
                    ? `<span class="ml-auto inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 cursor-help" title="${d._dup_message ?? 'Already Exists'}">
                           <span>⚠</span><span>Duplicate</span>
                       </span>`
                    : `<span class="ml-auto inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                           <span>✓</span><span>New</span>
                       </span>`
                }
            </div>

            <!-- Form body -->
            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-4 ${isDup ? 'opacity-70 pointer-events-none' : ''}">

                ${sectionLabel('Name')}
                ${fieldInput('last_name', 'Last Name', d.last_name)}
                ${fieldInput('first_name', 'First Name', d.first_name)}
                ${fieldInput('middle_name', 'Middle Name', d.middle_name)}
                ${fieldInput('suffix', 'Suffix', d.suffix, 'Jr., Sr., II…')}

                ${sectionLabel('Demographics')}
                ${fieldInput('dob', 'Date of Birth', d.dob, 'YYYY-MM-DD', 'date')}
                ${fieldInput('sex', 'Sex', d.sex, 'Male / Female')}
                ${fieldInput('civil_status', 'Civil Status', d.civil_status, 'Single, Married…')}

                ${sectionLabel('Contact')}
                ${fieldInput('contact', 'Contact No.', d.contact, '09XXXXXXXXX')}
                ${fieldInput('email', 'Email', d.email, 'example@email.com', 'email')}

                ${sectionLabel('Address')}
                ${fieldInput('house_no', 'House No./Street', d.house_no)}
                ${fieldInput('barangay', 'Barangay', d.barangay)}
                ${fieldInput('district', 'District', d.district)}
                ${fieldInput('city', 'City / Municipality', d.city)}

                ${sectionLabel('Classification & Flags')}

                ${isWhipProgram ? `
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Classification</label>
                    <input
                        type="text"
                        data-field="classification"
                        class="resume-field w-full text-sm bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2 text-emerald-800 font-semibold focus:outline-none"
                        value="Placed"
                        readonly
                    >
                    <p class="text-[11px] text-emerald-600">Automatically applied for WHIP beneficiaries.</p>
                </div>
                ` : `
                <!-- Classification dropdown based on selected program -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Classification</label>
                    <div class="relative">
                        <select
                            data-field="classification"
                            class="resume-field w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 pr-8 appearance-none focus:outline-none focus:ring-2 focus:ring-red-300 focus:bg-white transition"
                        >
                            <option value="">— select —</option>
                            ${(statusesByProgram[program] ?? []).map(s => `<option value="${s}">${s}</option>`).join('')}
                        </select>
                        <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                `}

                <!-- Checkboxes row -->
                <div class="col-span-full grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1">
                    ${fieldCheck('is_4ps', '4Ps Beneficiary', d.is_4ps)}
                    ${fieldCheck('is_pwd', 'Person with Disability (PWD)', d.is_pwd)}
                    ${fieldCheck('is_ofw_dependent', 'OFW Dependent', d.is_ofw_dependent)}
                </div>

                <!-- 4Ps ID — always visible so user can fill it in -->
                <div class="col-span-2">
                    ${fieldInput('ps4_id_no', '4Ps ID No.', d.ps4_id_no, 'Leave blank if not applicable')}
                </div>

            </div>
        </div>`;
    }).join('');

    if (successCount > 0) confirmBtn.disabled = false;
}

// ─── Cancel ───────────────────────────────────────────────────────────────────
const cancelResumeBtn = document.getElementById('cancelResumeImport');
if (cancelResumeBtn) {
    cancelResumeBtn.addEventListener('click', () => {
        document.getElementById('resumePreview').classList.add('hidden');
        resumeFiles = [];
        resumeInput.value = '';
        renderResumeFileList();
    });
}

// ─── Confirm / Import ─────────────────────────────────────────────────────────
const confirmResumeBtn = document.getElementById('confirmResumeImport');
if (confirmResumeBtn) {
    confirmResumeBtn.addEventListener('click', async () => {
        const results = window._resumeResults ?? [];
        const program = window._resumeProgram ?? '';

        // Collect the latest field values from the editable preview cards
        const rows = results
            .filter(r => !r.error && !r.data._is_duplicate)
            .map((r, i) => {
                const card = document.querySelector(`[data-index="${i}"]`);
                if (!card) return r.data;

                const updated = { ...r.data };
                card.querySelectorAll('.resume-field').forEach(el => {
                    const field = el.dataset.field;
                    if (!field) return;
                    updated[field] = el.type === 'checkbox' ? (el.checked ? 1 : 0) : el.value;
                });

                if (program.includes('Workers Hiring for Infrastructure Projects')) {
                    updated.classification = 'Placed';
                }
                return updated;
            });

        if (rows.length === 0) {
            showToast('Nothing to import.', 'warning');
            return;
        }

        // Skip classification requirement for WHIP programs
        const isWhipProgramPost = (program || '').toString().includes('Workers Hiring for Infrastructure Projects');
        if (!isWhipProgramPost) {
            const missingClassificationIndex = rows.findIndex(row => !String(row.classification ?? '').trim());
            if (missingClassificationIndex !== -1) {
                showToast('Classification is required before importing.', 'warning');
                const firstMissingCard = document.querySelector(`[data-index="${missingClassificationIndex}"]`);
                const classificationField = firstMissingCard?.querySelector('[data-field="classification"]');
                if (classificationField && typeof classificationField.focus === 'function') {
                    classificationField.focus();
                }
                return;
            }
        }

        confirmResumeBtn.disabled = true;
        confirmResumeBtn.textContent = 'Importing…';

        try {
            const res = await fetch('../../backend/resume/import_resume.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ program, data: rows }),
            });
            const json = await res.json();

            if (json.success) {
                showToast(json.message ?? 'Import successful!', 'success');
                // Reset state
                document.getElementById('resumePreview').classList.add('hidden');
                resumeFiles = [];
                if (resumeInput) resumeInput.value = '';
                renderResumeFileList();
            } else {
                showToast(json.error ?? 'Import failed.', 'error');
            }
        } catch (err) {
            showToast('Network error: ' + err.message, 'error');
        } finally {
            confirmResumeBtn.disabled = false;
            confirmResumeBtn.textContent = 'Import';
        }
    });
}

updateResumeSelectionLock();