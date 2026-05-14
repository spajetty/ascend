import { programs, programFields } from './config.js';
import { showToast } from '../toast.js';

// ─── MANUAL ENTRY TAB ─────────────────────────────────────────────────────────

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
    // programFields is defined in config.js
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
const manualSection = document.getElementById('manualSection');
if (manualSection) {
    manualSection.addEventListener('change', function () {
        const prog = document.getElementById('manualProgram');
        const val  = this.value;

        if (val && typeof programs !== 'undefined' && programs[val]) {
            prog.innerHTML = '<option value="">Select a program…</option>' +
                programs[val].map(p => `<option value="${p}">${p}</option>`).join('');
            prog.disabled = false;
        } else {
            prog.innerHTML = '<option value="">Select a section first…</option>';
            prog.disabled  = true;
        }

        // clear dynamic fields when section changes
        const dynamicFields = document.getElementById('dynamicFields');
        const dynamicFieldsInner = document.getElementById('dynamicFieldsInner');
        if (dynamicFields && dynamicFieldsInner) {
            dynamicFields.classList.add('hidden');
            dynamicFieldsInner.innerHTML = '';
        }
    });
}

// Program → render dynamic fields
const manualProgram = document.getElementById('manualProgram');
if (manualProgram) {
    manualProgram.addEventListener('change', function () {
        renderDynamicFields(this.value);
    });
}

// Clear form
const clearManualBtn = document.getElementById('clearManualForm');
if (clearManualBtn) {
    clearManualBtn.addEventListener('click', () => {
        document.getElementById('manualEntryForm').reset();
        if (manualProgram) {
            manualProgram.innerHTML = '<option value="">Select a section first…</option>';
            manualProgram.disabled = true;
        }
        const df = document.getElementById('dynamicFields');
        const dfi = document.getElementById('dynamicFieldsInner');
        if (df) df.classList.add('hidden');
        if (dfi) dfi.innerHTML = '';
    });
}

// Submit
const manualForm = document.getElementById('manualEntryForm');
if (manualForm) {
    manualForm.addEventListener('submit', e => {
        e.preventDefault();

        const section = document.getElementById('manualSection');
        const program = document.getElementById('manualProgram');

        if (!section || !section.value) { showToast('Please select a Section.', 'warning'); if(section) section.focus(); return; }
        if (!program || !program.value) { showToast('Please select a Program.', 'warning'); if(program) program.focus(); return; }

        // TODO: Gather form data and submit to your PHP endpoint
        const data = Object.fromEntries(new FormData(e.target));
        data.section = section.options[section.selectedIndex].text;
        data.program = program.value;

        console.log('Manual entry data:', data);
        showToast('Entry saved! Connect this to your backend endpoint.', 'success');
    });
}
