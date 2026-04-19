// ─── Programs per section ───────────────────────────────────────────────────
const programs = {
    employment_facilitation: ['Job Matching and Referral', 'First Time Jobseeker', 'Job Fair'],
    employers_engagement:    ['Employers Accreditation', 'Workers Hiring for Infrastructure Projects'],
    youth_employability:     ['SPES Baby', '4Ps Beneficiaries', 'PWD', 'Government Internship Program', 'Work Immersion and Internship Referral Program'],
    career_development:      ['Career Development Support Program', 'LMI Orientation'],
};

// ─── Status badge colors ─────────────────────────────────────────────────────
const statusColors = {
    employed:   'bg-teal-100 text-teal-700',
    registered: 'bg-gray-100 text-gray-600',
    referred:   'bg-orange-100 text-orange-600',
};

function statusBadge(status) {
    const key = status.toLowerCase();
    const cls = statusColors[key] ?? 'bg-blue-100 text-blue-700';
    return `<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold ${cls}">${status}</span>`;
}

function formatBytes(bytes) {
    if (bytes < 1024)        return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function previewTableRows(rows) {
    return rows.map(r => `
        <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-4 py-3 font-semibold text-gray-800">${r.name}</td>
            <td class="px-4 py-3 text-gray-500">${r.gender}</td>
            <td class="px-4 py-3 text-gray-500">${r.section}</td>
            <td class="px-4 py-3 text-gray-500">${r.program}</td>
            <td class="px-4 py-3">${statusBadge(r.status)}</td>
            <td class="px-4 py-3 text-gray-500">${r.contact}</td>
        </tr>
    `).join('');
}

// ─── Tab switching ────────────────────────────────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('text-blue-600', 'border-blue-600');
            b.classList.add('text-gray-400', 'border-transparent');
        });
        btn.classList.add('text-blue-600', 'border-blue-600');
        btn.classList.remove('text-gray-400', 'border-transparent');

        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
    });
});

// ─── Modal helpers ────────────────────────────────────────────────────────────
// showModal / hideModal are provided globally by modal-helper.php

let resumeModalMode = false;

function openSectionModal(fileLabel, isResume = false) {
    resumeModalMode = isResume;
    document.getElementById('modalFileNameText').textContent = fileLabel;
    document.getElementById('sectionSelect').value = '';
    document.getElementById('programSelect').innerHTML = '<option value="">Select a section first…</option>';
    document.getElementById('programSelect').disabled = true;
    document.getElementById('modalConfirm').disabled = true;
    showModal('sectionModal');
}

// Section → populate programs
document.getElementById('sectionSelect').addEventListener('change', function () {
    const prog    = document.getElementById('programSelect');
    const confirm = document.getElementById('modalConfirm');
    const val     = this.value;

    if (val && programs[val]) {
        prog.innerHTML = '<option value="">Select a program…</option>' +
            programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
        prog.disabled = false;
    } else {
        prog.innerHTML = '<option value="">Select a section first…</option>';
        prog.disabled  = true;
    }
    confirm.disabled = true;
});

document.getElementById('programSelect').addEventListener('change', function () {
    document.getElementById('modalConfirm').disabled = !this.value;
});

// Modal confirm — routes to Excel or Resume preview based on mode
document.getElementById('modalConfirm').addEventListener('click', () => {
    const sectionEl = document.getElementById('sectionSelect');
    const section   = sectionEl.options[sectionEl.selectedIndex].text;
    const program   = document.getElementById('programSelect').value;

    hideModal('sectionModal');

    if (resumeModalMode) {
        showResumePreview(section, program);
    } else {
        showExcelPreview(section, program);
    }
});

// ─── EXCEL TAB ────────────────────────────────────────────────────────────────
const fileInput  = document.getElementById('fileInput');
const dropZone   = document.getElementById('dropZone');
const fileInfo   = document.getElementById('fileInfo');
const fileName   = document.getElementById('fileName');
const fileSize   = document.getElementById('fileSize');
const removeFile = document.getElementById('removeFile');

let selectedFile = null;

function handleFile(file) {
    if (!file) return;

    const allowed = ['.xlsx', '.xls', '.csv'];
    const ext = '.' + file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
        alert('Please upload a .xlsx, .xls, or .csv file.');
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        alert('File exceeds 10MB limit.');
        return;
    }

    selectedFile = file;
    fileName.textContent = file.name;
    fileSize.textContent = formatBytes(file.size) + ' of ' + formatBytes(file.size);
    fileInfo.classList.remove('hidden');
    openSectionModal(file.name, false);
}

fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));

dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('border-blue-400', 'bg-blue-50/30');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-blue-400', 'bg-blue-50/30');
});
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50/30');
    handleFile(e.dataTransfer.files[0]);
});

removeFile.addEventListener('click', e => {
    e.stopPropagation();
    selectedFile = null;
    fileInput.value = '';
    fileInfo.classList.add('hidden');
    document.getElementById('dataPreview').classList.add('hidden');
});

function showExcelPreview(section, program) {
    // TODO: Replace with real CSV/XLSX parse from backend
    const mockRows = [
        { name: 'Christine Brooks', gender: 'Female', section, program, status: 'Employed',   contact: '09261234567' },
        { name: 'Rosie Pearson',    gender: 'Female', section, program, status: 'Registered', contact: '09251234567' },
        { name: 'Darrell Caldwell', gender: 'Male',   section, program, status: 'Referred',   contact: '09241234567' },
        { name: 'Mark Santos',      gender: 'Male',   section, program, status: 'Employed',   contact: '09171234567' },
        { name: 'Ana Reyes',        gender: 'Female', section, program, status: 'Registered', contact: '09181234567' },
    ];

    document.getElementById('previewMeta').textContent =
        `Found ${mockRows.length} rows • ${Object.keys(mockRows[0]).length} columns`;
    document.getElementById('previewBody').innerHTML = previewTableRows(mockRows);

    const preview = document.getElementById('dataPreview');
    preview.classList.remove('hidden');
    preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.getElementById('cancelImport').addEventListener('click', () => {
    document.getElementById('dataPreview').classList.add('hidden');
    selectedFile = null;
    fileInput.value = '';
    fileInfo.classList.add('hidden');
});

document.getElementById('confirmImport').addEventListener('click', () => {
    // TODO: Submit to your PHP import endpoint via fetch/form
    alert('Import submitted! Connect this to your backend endpoint.');
});

// ─── RESUME TAB ───────────────────────────────────────────────────────────────
const resumeInput    = document.getElementById('resumeInput');
const resumeDropZone = document.getElementById('resumeDropZone');
const resumeFileList = document.getElementById('resumeFileList');

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
            alert(`${f.name}: unsupported format. Use PDF, DOC, or DOCX.`);
            return;
        }
        if (f.size > maxSize) {
            alert(`${f.name}: exceeds 5MB limit.`);
            return;
        }
        if (!resumeFiles.find(r => r.name === f.name && r.size === f.size)) {
            resumeFiles.push(f);
        }
    });

    if (resumeFiles.length === 0) return;

    renderResumeFileList();

    const label = resumeFiles.length === 1
        ? resumeFiles[0].name
        : `${resumeFiles.length} resume files selected`;
    openSectionModal(label, true);
}

resumeInput.addEventListener('change', () => handleResumeFiles(resumeInput.files));

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

function showResumePreview(section, program) {
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
        `${mockRows.length} resume${mockRows.length > 1 ? 's' : ''} processed • 6 columns`;
    document.getElementById('resumePreviewBody').innerHTML = previewTableRows(mockRows);

    const preview = document.getElementById('resumePreview');
    preview.classList.remove('hidden');
    preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.getElementById('cancelResumeImport').addEventListener('click', () => {
    document.getElementById('resumePreview').classList.add('hidden');
    resumeFiles = [];
    resumeInput.value = '';
    renderResumeFileList();
});

document.getElementById('confirmResumeImport').addEventListener('click', () => {
    // TODO: Submit to your PHP resume import endpoint via fetch/form
    alert('Resume import submitted! Connect this to your backend endpoint.');
});