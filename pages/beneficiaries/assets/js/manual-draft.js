// ── DRAFT MANAGER ─────────────────────────────────────────────────

export function initDraftManager() {
    const form = document.getElementById('manualEntryForm');
    if (!form) return;

    const checkAndShowModal = () => {
        // Check if the manual tab is hidden
        const manualTab = document.getElementById('tab-manual');
        if (manualTab && manualTab.classList.contains('hidden')) {
            return;
        }
        
        const savedDraft = localStorage.getItem('ascend_manual_draft');
        if (savedDraft && !window._draftModalShown) {
            try {
                const parsed = JSON.parse(savedDraft);
                if (parsed.data && Object.keys(parsed.data).length > 0) {
                    window._draftModalShown = true;
                    showDraftModal(parsed.data, parsed.timestamp);
                }
            } catch (e) {
                console.error('Failed to parse draft', e);
            }
        }
    };

    // 1. Check for draft immediately (if tab is already visible)
    checkAndShowModal();
    
    // Also check when a tab button is clicked
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.dataset.tab === 'manual') {
                setTimeout(checkAndShowModal, 10);
            }
        });
    });

    // 2. Bind auto-save
    let saveTimeout;
    const triggerSave = () => {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveDraft, 1000);
    };
    
    form.addEventListener('input', triggerSave);
    form.addEventListener('change', triggerSave);
}

function saveDraft() {
    const form = document.getElementById('manualEntryForm');
    if (!form) return;
    const formData = new FormData(form);
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (value instanceof File) continue; // Skip files
        if (data[key]) {
            if (!Array.isArray(data[key])) data[key] = [data[key]];
            data[key].push(value);
        } else {
            data[key] = value;
        }
    }
    
    // Explicitly add section and program since they lack name attributes
    const secEl = document.getElementById('manualSection');
    const progEl = document.getElementById('manualProgram');
    if (secEl && secEl.value) data['__manualSection'] = secEl.value;
    if (progEl && progEl.value) data['__manualProgram'] = progEl.value;
    
    // Only save if there's actual data beyond hidden fields
    const hasData = Object.keys(data).some(k => !k.startsWith('__') && data[k] !== '' && k !== 'sex' && k !== 'is_4ps' && k !== 'is_pwd' && k !== 'is_ofw_dependent' && k !== 'spes_status' && k !== 'city');
    if (hasData || data['__manualSection'] || data['__manualProgram']) {
        localStorage.setItem('ascend_manual_draft', JSON.stringify({ data, timestamp: Date.now() }));
    } else {
        localStorage.removeItem('ascend_manual_draft');
    }
}

export function clearDraft() {
    localStorage.removeItem('ascend_manual_draft');
}

function showDraftModal(draftData, timestamp) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 999999; display: flex; align-items: center; justify-content: center; padding: 1rem; opacity: 0; transition: opacity 0.3s;';
    
    const dateStr = new Date(timestamp).toLocaleString();
    
    overlay.innerHTML = `
        <div style="background: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-width: 28rem; width: 100%; overflow: hidden; transform: scale(0.95); transition: transform 0.3s; font-family: inherit;">
            <div style="padding: 1.5rem;">
                <div style="width: 3rem; height: 3rem; border-radius: 50%; background: #DBEAFE; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <i class="fa-solid fa-file-pen" style="color: #2563EB; font-size: 1.25rem;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin: 0 0 0.5rem 0;">Unfinished Draft Found</h3>
                <p style="color: #6B7280; font-size: 0.875rem; margin: 0 0 1.5rem 0; line-height: 1.5;">
                    We found an auto-saved manual entry from <strong style="color: #111827;">${dateStr}</strong>. Would you like to restore your progress?
                </p>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                    <button type="button" id="btn-draft-discard" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #D1D5DB; border-radius: 0.5rem; cursor: pointer;">
                        Discard
                    </button>
                    <button type="button" id="btn-draft-restore" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563EB; border: none; border-radius: 0.5rem; cursor: pointer; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                        Restore Draft
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.style.opacity = '1';
        overlay.querySelector('div').style.transform = 'scale(1)';
    }, 10);
    
    const closeModal = () => {
        overlay.style.opacity = '0';
        overlay.querySelector('div').style.transform = 'scale(0.95)';
        setTimeout(() => overlay.remove(), 300);
    };
    
    document.getElementById('btn-draft-discard').addEventListener('click', (e) => {
        e.preventDefault();
        clearDraft();
        closeModal();
    });
    
    document.getElementById('btn-draft-restore').addEventListener('click', (e) => {
        e.preventDefault();
        restoreDraft(draftData);
        closeModal();
    });
}

function restoreDraft(data) {
    const form = document.getElementById('manualEntryForm');
    if (!form) return;
    
    // Pass 1: Standard inputs
    Object.entries(data).forEach(([key, value]) => {
        const input = form.elements[key];
        if (!input) return;
        
        if (input instanceof RadioNodeList || (input.length && !input.tagName)) {
            input.forEach(radio => {
                if (radio.type === 'radio' || radio.type === 'checkbox') {
                    if (Array.isArray(value)) radio.checked = value.includes(radio.value);
                    else radio.checked = (radio.value === value);
                }
            });
        } else {
            input.value = value;
        }
    });
    
    // Pass 2: Triggers and UI components
    const secEl = document.getElementById('manualSection');
    if (secEl && data['__manualSection']) {
        secEl.value = data['__manualSection'];
        secEl.dispatchEvent(new Event('change'));
    }
    
    const progEl = document.getElementById('manualProgram');
    if (progEl && data['__manualProgram']) {
        progEl.value = data['__manualProgram'];
        progEl.dispatchEvent(new Event('change'));
        const classEl = document.getElementById('mf-classification');
        if (classEl && data.classification) {
            classEl.value = data.classification;
        }
    }
    
    document.querySelectorAll('.mf-chip').forEach(chip => {
        const group = chip.dataset.group;
        const hidden = document.getElementById('mf-h-' + group);
        if (hidden && hidden.value === chip.dataset.val) chip.classList.add('on');
        else chip.classList.remove('on');
    });
    
    document.querySelectorAll('.mf-flag').forEach(flag => {
        let isChecked = false;
        if (flag.dataset.flag) {
            const hidden = document.getElementById('mf-h-' + flag.dataset.flag);
            if (hidden && hidden.value === '1') isChecked = true;
        } else if (flag.dataset.flagInline) {
            const hidden = document.getElementById('mf-h-' + flag.dataset.flagInline.replace(/_/g, '-'));
            if (hidden && hidden.value === '1') isChecked = true;
        }
        
        if (isChecked) {
            flag.classList.add('on');
            if (flag.dataset.flag === '4ps') {
                const cond = document.getElementById('mf-cond-4ps');
                if (cond) cond.classList.add('show');
            }
        } else {
            flag.classList.remove('on');
        }
    });
    
    const dist = document.getElementById('mf-district');
    if (dist && dist.value) {
        dist.dispatchEvent(new Event('change'));
        const brgy = document.getElementById('mf-barangay');
        if (brgy && data.barangay) brgy.value = data.barangay;
    }
    
    const dob = document.getElementById('mf-dob');
    if (dob && dob.value) dob.dispatchEvent(new Event('change'));
    
    if (typeof window.showToast === 'function') window.showToast('Draft restored.', 'info');
}
