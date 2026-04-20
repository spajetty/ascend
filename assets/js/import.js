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
            <td class="px-4 py-3 text-gray-500">${r.sex}</td>
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

// ─── EXCEL TAB ────────────────────────────────────────────────────────────────

// helpers to enable/disable the drop zone
function setDropZoneEnabled(zone, browseBtn, enabled) {
    if (enabled) {
        zone.classList.remove('opacity-40', 'pointer-events-none', 'select-none');
        zone.classList.add('cursor-pointer', 'hover:border-blue-400', 'hover:bg-blue-50/30');
        browseBtn.disabled = false;
    } else {
        zone.classList.add('opacity-40', 'pointer-events-none', 'select-none');
        zone.classList.remove('cursor-pointer', 'hover:border-blue-400', 'hover:bg-blue-50/30');
        browseBtn.disabled = true;
    }
}

// ── DOM refs ───────────────────────────────────────────────────────────────────
const fileInput  = document.getElementById('fileInput');
const dropZone   = document.getElementById('dropZone');
const fileInfo   = document.getElementById('fileInfo');
const fileName   = document.getElementById('fileName');
const fileSize   = document.getElementById('fileSize');
const removeFile = document.getElementById('removeFile');

// ── Inline section → program cascade (Excel) ──────────────────────────────────
document.getElementById('excelSection').addEventListener('change', function () {
    const prog = document.getElementById('excelProgram');
    const val  = this.value;

    if (val && programs[val]) {
        prog.innerHTML = '<option value="">Select a program…</option>' +
            programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
        prog.disabled = false;
    } else {
        prog.innerHTML = '<option value="">Select a section first…</option>';
        prog.disabled  = true;
    }
    prog.value = '';
    setDropZoneEnabled(dropZone, document.getElementById('excelBrowseBtn'), false);
    document.getElementById('dataPreview').classList.add('hidden');
});

document.getElementById('excelProgram').addEventListener('change', function () {
    setDropZoneEnabled(dropZone, document.getElementById('excelBrowseBtn'), !!this.value);
    if (!this.value) document.getElementById('dataPreview').classList.add('hidden');
});

// ── File handling ──────────────────────────────────────────────────────────────
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
    fileSize.textContent = formatBytes(file.size);
    fileInfo.classList.remove('hidden');

    const sectionEl = document.getElementById('excelSection');
    const section   = sectionEl.options[sectionEl.selectedIndex].text;
    const program   = document.getElementById('excelProgram').value;
    showExcelPreview(section, program);
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
        { name: 'Christine Brooks', sex: 'Female', section, program, status: 'Employed',   contact: '09261234567' },
        { name: 'Rosie Pearson',    sex: 'Female', section, program, status: 'Registered', contact: '09251234567' },
        { name: 'Darrell Caldwell', sex: 'Male',   section, program, status: 'Referred',   contact: '09241234567' },
        { name: 'Mark Santos',      sex: 'Male',   section, program, status: 'Employed',   contact: '09171234567' },
        { name: 'Ana Reyes',        sex: 'Female', section, program, status: 'Registered', contact: '09181234567' },
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

// ── Inline section → program cascade (Resume) ─────────────────────────────────
document.getElementById('resumeSection').addEventListener('change', function () {
    const prog = document.getElementById('resumeProgram');
    const val  = this.value;

    if (val && programs[val]) {
        prog.innerHTML = '<option value="">Select a program…</option>' +
            programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
        prog.disabled = false;
    } else {
        prog.innerHTML = '<option value="">Select a section first…</option>';
        prog.disabled  = true;
    }
    prog.value = '';
    setDropZoneEnabled(resumeDropZone, document.getElementById('resumeBrowseBtn'), false);
    document.getElementById('resumePreview').classList.add('hidden');
});

document.getElementById('resumeProgram').addEventListener('change', function () {
    setDropZoneEnabled(resumeDropZone, document.getElementById('resumeBrowseBtn'), !!this.value);
    if (!this.value) document.getElementById('resumePreview').classList.add('hidden');
});

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

    const sectionEl = document.getElementById('resumeSection');
    const section   = sectionEl.options[sectionEl.selectedIndex].text;
    const program   = document.getElementById('resumeProgram').value;
    showResumePreview(section, program);
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
        sex:     i % 2 === 0 ? 'Female' : 'Male',
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

// ─── ADDRESS CASCADE (driven by address-data.js) ─────────────────────────────

(function () {
    const citySelect    = document.getElementById('manualCity');
    const brgySelect    = document.getElementById('manualBarangay');
    const districtInput = document.getElementById('manualDistrict');

    const ordinals = { 1: '1st', 2: '2nd', 3: '3rd', 4: '4th' };

    // Populate city options from ADDRESS_DATA keys
    Object.keys(ADDRESS_DATA).sort().forEach(city => {
        const opt = document.createElement('option');
        opt.value = city;
        opt.textContent = city;
        citySelect.appendChild(opt);
    });

    citySelect.addEventListener('change', function () {
        const data = ADDRESS_DATA[this.value];

        // Reset barangay
        brgySelect.innerHTML = '<option value="">Select Barangay…</option>';
        brgySelect.disabled = true;
        districtInput.value = '';

        if (!data) return;

        // Populate barangays
        data.barangays.forEach(brgy => {
            const opt = document.createElement('option');
            opt.value = brgy;
            opt.textContent = brgy;
            brgySelect.appendChild(opt);
        });
        brgySelect.disabled = false;

        // Auto-fill district
        const d = data.district;
        districtInput.value = d ? `${ordinals[d] ?? d} District` : '';
    });
})();

// ─── MANUAL ENTRY TAB ─────────────────────────────────────────────────────────

// Extra fields per program key.
// Each field: { name, label, type, placeholder?, options?, required? }
// type: 'text' | 'number' | 'date' | 'select' | 'textarea'
const programFields = {

    // ── Employment Facilitation ──────────────────────────────────────────────
    'Job Matching and Referral': [
        { name: 'skills',           label: 'Skills / Qualifications', type: 'textarea',  placeholder: 'e.g. Accounting, MS Office, Driving', required: true },
        { name: 'desired_position', label: 'Desired Position',        type: 'text',      placeholder: 'e.g. Accounting Clerk',               required: true },
        { name: 'work_experience',  label: 'Years of Work Experience', type: 'number',   placeholder: '0',                                   required: false },
        { name: 'employer_name',    label: 'Referred Employer',        type: 'text',      placeholder: 'Employer name (if already referred)', required: false },
    ],
    'First Time Jobseeker': [
        { name: 'school',           label: 'School / University',      type: 'text',      placeholder: 'Enter school name',   required: true },
        { name: 'course',           label: 'Course / Degree',          type: 'text',      placeholder: 'e.g. BS Accountancy', required: true },
        { name: 'graduation_year',  label: 'Year Graduated',           type: 'number',    placeholder: 'e.g. 2024',           required: true },
        { name: 'desired_position', label: 'Desired Position',         type: 'text',      placeholder: 'e.g. Office Staff',   required: false },
    ],
    'Job Fair': [
        { name: 'skills',           label: 'Skills / Qualifications',  type: 'textarea',  placeholder: 'List relevant skills',  required: true },
        { name: 'desired_position', label: 'Desired Position',         type: 'text',      placeholder: 'e.g. Cashier',          required: false },
        { name: 'job_fair_date',    label: 'Job Fair Date Attended',   type: 'date',                                             required: false },
        { name: 'venue',            label: 'Venue',                    type: 'text',      placeholder: 'Enter job fair venue', required: true },
        { name: 'company',          label: 'Company of Interest',      type: 'text',      placeholder: 'Enter company name',     required: true },
    ],

    // ── Employers Engagement ─────────────────────────────────────────────────
    'Employers Accreditation': [
        { name: 'company_name',     label: 'Company Name',             type: 'text',      placeholder: 'Enter company name',         required: true },
        { name: 'industry',         label: 'Industry',                 type: 'text',      placeholder: 'e.g. Manufacturing, BPO',    required: true },
        { name: 'job_vacancies',    label: 'Number of Job Vacancies',  type: 'number',    placeholder: '0',                          required: true },
        { name: 'tin',              label: 'TIN / Business Reg. No.',  type: 'text',      placeholder: 'Enter TIN or registration',  required: false },
    ],
    'Workers Hiring for Infrastructure Projects': [
        { name: 'company_name',     label: 'Company / Contractor',     type: 'text',      placeholder: 'Enter company or contractor name', required: true },
        { name: 'project_name',     label: 'Project Name',             type: 'text',      placeholder: 'Enter project name',               required: true },
        { name: 'position',         label: 'Position / Trade',         type: 'text',      placeholder: 'e.g. Carpenter, Mason',            required: true },
        { name: 'contract_duration',label: 'Contract Duration',        type: 'text',      placeholder: 'e.g. 6 months',                    required: false },
    ],

    // ── Youth Employability ──────────────────────────────────────────────────
    'SPES Baby': [
        { name: 'school',           label: 'School',                   type: 'text',      placeholder: 'Enter school name',        required: true },
        { name: 'year_level',       label: 'Year Level',               type: 'select',    options: ['Grade 11', 'Grade 12', '1st Year', '2nd Year', '3rd Year', '4th Year'], required: true },
        { name: 'guardian_name',    label: "Guardian's Name",          type: 'text',      placeholder: "Enter guardian's name",    required: true },
        { name: 'guardian_contact', label: "Guardian's Contact",       type: 'tel',       placeholder: 'e.g. 09171234567',         required: false },
    ],
    '4Ps Beneficiaries': [
        { name: 'household_id',     label: '4Ps Household ID',         type: 'text',      placeholder: 'Enter household ID',       required: true },
        { name: 'school',           label: 'School',                   type: 'text',      placeholder: 'Enter school name',        required: false },
        { name: 'year_level',       label: 'Year Level',               type: 'text',      placeholder: 'e.g. Grade 10',            required: false },
    ],
    'PWD': [
        { name: 'pwd_id',           label: 'PWD ID Number',            type: 'text',      placeholder: 'Enter PWD ID number',      required: true },
        { name: 'disability_type',  label: 'Type of Disability',       type: 'select',    options: ['Visual', 'Hearing', 'Speech', 'Physical / Orthopedic', 'Intellectual', 'Psychosocial', 'Others'], required: true },
        { name: 'skills',           label: 'Skills / Abilities',       type: 'textarea',  placeholder: 'Describe skills or abilities', required: false },
    ],
    'Government Internship Program': [
        { name: 'school',           label: 'School / University',      type: 'text',      placeholder: 'Enter school name',        required: true },
        { name: 'course',           label: 'Course / Degree',          type: 'text',      placeholder: 'e.g. BS Public Administration', required: true },
        { name: 'agency_assigned',  label: 'Agency Assigned',          type: 'text',      placeholder: 'Enter government agency',  required: false },
        { name: 'internship_start', label: 'Internship Start Date',    type: 'date',                                               required: false },
        { name: 'internship_end',   label: 'Internship End Date',      type: 'date',                                               required: false },
    ],
    'Work Immersion and Internship Referral Program': [
        { name: 'school',           label: 'School',                   type: 'text',      placeholder: 'Enter school name',        required: true },
        { name: 'year_level',       label: 'Year Level',               type: 'text',      placeholder: 'e.g. Grade 12',            required: true },
        { name: 'company_partner',  label: 'Partner Company',          type: 'text',      placeholder: 'Enter company name',       required: false },
        { name: 'immersion_hours',  label: 'Total Immersion Hours',    type: 'number',    placeholder: '80',                       required: false },
    ],

    // ── Career Development ───────────────────────────────────────────────────
    'Career Development Support Program': [
        { name: 'career_goal',      label: 'Career Goal',              type: 'text',      placeholder: 'e.g. Become a licensed nurse', required: false },
        { name: 'training_needed',  label: 'Training / Support Needed',type: 'textarea',  placeholder: 'Describe needed training or support', required: false },
        { name: 'current_status',   label: 'Current Employment Status',type: 'select',    options: ['Unemployed', 'Underemployed', 'Student', 'Self-employed'], required: true },
    ],
    'LMI Orientation': [
        { name: 'orientation_date', label: 'Orientation Date',         type: 'date',                                               required: false },
        { name: 'venue',            label: 'Venue',                    type: 'text',      placeholder: 'Enter venue',              required: false },
        { name: 'current_status',   label: 'Current Employment Status',type: 'select',    options: ['Unemployed', 'Underemployed', 'Student', 'Self-employed'], required: true },
    ],
};

// Input class reused across dynamic fields
const inputCls = 'w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition';

function buildField(field) {
    const req = field.required ? '<span class="text-red-400">*</span>' : '';
    let control = '';

    if (field.type === 'select') {
        const opts = field.options.map(o => `<option value="${o}">${o}</option>`).join('');
        control = `
            <div class="relative">
                <select name="${field.name}" ${field.required ? 'required' : ''}
                    class="appearance-none ${inputCls} pr-10">
                    <option value="">Select…</option>
                    ${opts}
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>`;
    } else if (field.type === 'textarea') {
        control = `<textarea name="${field.name}" rows="3" placeholder="${field.placeholder ?? ''}" ${field.required ? 'required' : ''}
            class="${inputCls} resize-none"></textarea>`;
    } else {
        control = `<input type="${field.type}" name="${field.name}" placeholder="${field.placeholder ?? ''}" ${field.required ? 'required' : ''}
            class="${inputCls}">`;
    }

    return `
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">${field.label} ${req}</label>
            ${control}
        </div>`;
}

function renderDynamicFields(program) {
    const wrapper = document.getElementById('dynamicFields');
    const inner   = document.getElementById('dynamicFieldsInner');
    const label   = document.getElementById('dynamicFieldsLabel');
    const fields  = programFields[program];

    if (!fields || fields.length === 0) {
        wrapper.classList.add('hidden');
        inner.innerHTML = '';
        return;
    }

    label.textContent = program;

    // Group fields into rows: pairs side-by-side, lone fields full-width
    let html = '';
    for (let i = 0; i < fields.length; i++) {
        const f = fields[i];
        const next = fields[i + 1];

        // textarea always goes full width
        if (f.type === 'textarea') {
            html += `<div>${buildField(f)}</div>`;
        } else if (next && next.type !== 'textarea') {
            // pair with next
            html += `<div class="grid grid-cols-1 md:grid-cols-2 gap-5">${buildField(f)}${buildField(next)}</div>`;
            i++; // skip next
        } else {
            html += `<div>${buildField(f)}</div>`;
        }
    }

    inner.innerHTML = html;
    wrapper.classList.remove('hidden');
}

// Section → populate programs (manual form)
document.getElementById('manualSection').addEventListener('change', function () {
    const prog = document.getElementById('manualProgram');
    const val  = this.value;

    if (val && programs[val]) {
        prog.innerHTML = '<option value="">Select a program…</option>' +
            programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
        prog.disabled = false;
    } else {
        prog.innerHTML = '<option value="">Select a section first…</option>';
        prog.disabled  = true;
    }

    // clear dynamic fields when section changes
    document.getElementById('dynamicFields').classList.add('hidden');
    document.getElementById('dynamicFieldsInner').innerHTML = '';
});

// Program → render dynamic fields
document.getElementById('manualProgram').addEventListener('change', function () {
    renderDynamicFields(this.value);
});

// Clear form
document.getElementById('clearManualForm').addEventListener('click', () => {
    document.getElementById('manualEntryForm').reset();
    document.getElementById('manualProgram').innerHTML = '<option value="">Select a section first…</option>';
    document.getElementById('manualProgram').disabled = true;
    document.getElementById('dynamicFields').classList.add('hidden');
    document.getElementById('dynamicFieldsInner').innerHTML = '';
});

// Submit
document.getElementById('manualEntryForm').addEventListener('submit', e => {
    e.preventDefault();

    const section = document.getElementById('manualSection');
    const program = document.getElementById('manualProgram');

    if (!section.value) { alert('Please select a Section.'); section.focus(); return; }
    if (!program.value) { alert('Please select a Program.'); program.focus(); return; }

    // TODO: Gather form data and submit to your PHP endpoint
    const data = Object.fromEntries(new FormData(e.target));
    data.section = section.options[section.selectedIndex].text;
    data.program = program.value;

    console.log('Manual entry data:', data);
    alert('Entry saved! Connect this to your backend endpoint.');
});