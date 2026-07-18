import { statusesByProgram } from '../../../../assets/js/imports/config.js';

const $ = id => document.getElementById(id);

export function validatePanel(idx, selectedProgram) {
  let isValid = true;
  let firstInvalid = null;

  if (idx === 1) {
    if (['accreditation', 'whip_projects', 'careerdev', 'lmi'].includes(selectedProgram)) {
      return true;
    }

    const req1 = ['mf-lname', 'mf-fname', 'mf-dob', 'mf-barangay', 'mf-city'];
    const programLabel = $('manualProgram')?.selectedOptions?.[0]?.textContent?.trim() || '';
    const needsClassification = Boolean(programLabel && (statusesByProgram[programLabel] || []).length);
    if (needsClassification) req1.push('mf-classification');
    const is4ps = document.getElementById('mf-flag-4ps')?.classList.contains('on');
    if (is4ps) req1.push('mf-4psid');
    if (selectedProgram === 'whip') req1.push('mf-whip-batch');

    for (const id of req1) {
      const el = $(id);
      if (el && !el.value.trim()) {
        isValid = false;
        if (!firstInvalid) firstInvalid = el;
      }
    }
    
    if (!isValid) {
      window.showToast('Please fill in all required fields in Panel 1.', 'warning');
      if (firstInvalid) firstInvalid.focus();
      return false;
    }
  } else if (idx === 2) {
    const req2 = [];
    
    if (['jobmatch', 'firstjobseek', 'jobfair'].includes(selectedProgram)) {
      req2.push('mf-company', 'mf-position', 'mf-batch');
    }
    if (selectedProgram === 'jobfair') {
      req2.push('mf-jfevent', 'mf-jfcompany');
    }
    if (['careerdev', 'lmi'].includes(selectedProgram)) {
      req2.push('mf-school', 'mf-school-batch');
    }
    if (selectedProgram === 'accreditation') {
      req2.push('mf-accred-period', 'mf-accred-company');
    }
    if (selectedProgram === 'spes') {
      req2.push(
        'mf-spes-school', 
        'mf-spes-course', 
        'mf-highest-educ', 
        'mf-store', 
        'mf-contract-start', 
        'mf-contract-end', 
        'mf-days', 
        'mf-spes-batch'
      );
    }
    if (selectedProgram === 'gip') {
      req2.push(
        'mf-gip-school',
        'mf-gip-course',
        'mf-gip-highest-educ',
        'mf-gip-office',
        'mf-gip-contract-start',
        'mf-gip-contract-end',
        'mf-gip-days',
        'mf-gip-batch'
      );
    }
    if (selectedProgram === 'wiirp') {
      req2.push(
        'mf-int-school',
        'mf-int-course',
        'mf-year-level',
        'mf-req-hours',
        'mf-contract-period',
        'mf-pref-org',
        'mf-pref-ind',
        'mf-int-sched',
        'mf-int-batch'
      );
      
      if ($('mf-pref-ind')?.value === 'Other') {
        req2.push('mf-pref-ind-other');
      }
      if ($('mf-int-sched')?.value === 'Other') {
        req2.push('mf-int-sched-other');
      }
      
      const type = $('mf-h-inttype')?.value || 'inquiry';
      if (type === 'peso-assigned' || type === 'private') {
        req2.push('mf-office', 'mf-assign-start', 'mf-assign-end');
      }
      if (type === 'private') {
        req2.push('mf-endorse1', 'mf-endorse2');
      }
    }
    
    const missingIds = [];
    for (const id of req2) {
      const el = $(id);
      if (!el) {
        missingIds.push(`${id} (not found in DOM)`);
        continue;
      }
      if (!el.value.trim()) {
        isValid = false;
        missingIds.push(id);
        if (!firstInvalid) firstInvalid = el;
      } else if (selectedProgram !== 'accreditation' && el.dataset.hidden) {
        const hiddenEl = $(el.dataset.hidden);
        if (hiddenEl && !hiddenEl.value.trim()) {
          isValid = false;
          missingIds.push(`${id} -> ${el.dataset.hidden}`);
          if (!firstInvalid) firstInvalid = el;
        }
      }
    }

    if (!isValid) {
      console.warn('[manual-validation] Panel 2 blocked. selectedProgram =', selectedProgram, '| missing/invalid fields:', missingIds);
      window.showToast('Please fill in all required fields in Panel 2.', 'warning');
      if (firstInvalid) firstInvalid.focus();
      return false;
    }

    if (selectedProgram === 'whip') {
      const mode = $('mf-h-whip-project-mode')?.value || 'search';
      const projectId = $('mf-h-whip-project-id')?.value || '';

      if (mode === 'new' || mode === 'edit') {
        const title = $('mf-project-title');
        const contractor = $('mf-project-contractor');
        if (!title?.value.trim() || !contractor?.value.trim()) {
          window.showToast('Please fill in the project\'s title and contractor.', 'warning');
          (title?.value.trim() ? contractor : title)?.focus();
          return false;
        }
        if (mode === 'edit' && !projectId) {
          window.showToast('Something went wrong identifying the project being edited. Please re-select it.', 'warning');
          return false;
        }
      } else if (!projectId) {
        window.showToast('Please select a project, or add it as a new one, before continuing.', 'warning');
        $('mf-whip-project-search')?.focus();
        return false;
      }
    }
  } else if (idx === 3) {
    if (!['accreditation', 'careerdev', 'lmi'].includes(selectedProgram)) {
      const resumeInput = document.querySelector('input[name="resume"]');
      if (resumeInput && resumeInput.value.trim() === '') {
        window.showToast('Please provide the required Resume / CV link in Panel 3.', 'warning');
        return false;
      }
    }
  }

  return true;
}