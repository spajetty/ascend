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

// ─── File input handling ──────────────────────────────────────────────────────
const fileInput  = document.getElementById('fileInput');
const dropZone   = document.getElementById('dropZone');
const fileInfo   = document.getElementById('fileInfo');
const fileName   = document.getElementById('fileName');
const fileSize   = document.getElementById('fileSize');
const removeFile = document.getElementById('removeFile');

let selectedFile = null;

function formatBytes(bytes) {
    if (bytes < 1024)        return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function openSectionModal(file) {
    document.getElementById('modalFileNameText').textContent = file.name;
    document.getElementById('sectionSelect').value = '';
    document.getElementById('programSelect').innerHTML = '<option value="">Select a section first…</option>';
    document.getElementById('programSelect').disabled = true;
    document.getElementById('modalConfirm').disabled = true;
    showModal('sectionModal');
}

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
    openSectionModal(file);
}

fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));

// Drag & drop
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

// ─── Modal logic ──────────────────────────────────────────────────────────────
// showModal / hideModal are provided globally by modal-helper.php

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

// Confirm → show data preview
document.getElementById('modalConfirm').addEventListener('click', () => {
    const sectionEl = document.getElementById('sectionSelect');
    const section   = sectionEl.options[sectionEl.selectedIndex].text;
    const program   = document.getElementById('programSelect').value;

    hideModal('sectionModal');

    // TODO: Replace with real CSV/XLSX parse
    const mockRows = [
        { name: 'Christine Brooks', gender: 'Female', section, program, status: 'Employed',   contact: '09261234567' },
        { name: 'Rosie Pearson',    gender: 'Female', section, program, status: 'Registered', contact: '09251234567' },
        { name: 'Darrell Caldwell', gender: 'Male',   section, program, status: 'Referred',   contact: '09241234567' },
        { name: 'Mark Santos',      gender: 'Male',   section, program, status: 'Employed',   contact: '09171234567' },
        { name: 'Ana Reyes',        gender: 'Female', section, program, status: 'Registered', contact: '09181234567' },
    ];

    document.getElementById('previewMeta').textContent =
        `Found ${mockRows.length} rows • ${Object.keys(mockRows[0]).length} columns`;

    document.getElementById('previewBody').innerHTML = mockRows.map(r => `
        <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-4 py-3 font-semibold text-gray-800">${r.name}</td>
            <td class="px-4 py-3 text-gray-500">${r.gender}</td>
            <td class="px-4 py-3 text-gray-500">${r.section}</td>
            <td class="px-4 py-3 text-gray-500">${r.program}</td>
            <td class="px-4 py-3">${statusBadge(r.status)}</td>
            <td class="px-4 py-3 text-gray-500">${r.contact}</td>
        </tr>
    `).join('');

    const preview = document.getElementById('dataPreview');
    preview.classList.remove('hidden');
    preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

// Cancel preview
document.getElementById('cancelImport').addEventListener('click', () => {
    document.getElementById('dataPreview').classList.add('hidden');
    selectedFile = null;
    fileInput.value = '';
    fileInfo.classList.add('hidden');
});

// Confirm import
document.getElementById('confirmImport').addEventListener('click', () => {
    // TODO: Submit to your PHP import endpoint via fetch/form
    alert('Import submitted! Connect this to your backend endpoint.');
});
