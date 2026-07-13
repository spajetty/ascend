import { statusesByProgram } from '../../../../assets/js/imports/config.js';

const $ = id => document.getElementById(id);

export function validatePanel(idx, selectedProgram) {
  let isValid = true;
  let firstInvalid = null;

  if (idx === 1) {
    if (['accreditation', 'careerdev', 'lmi'].includes(selectedProgram)) {
      return true;
    }

    const req1 = ['mf-lname', 'mf-fname', 'mf-dob', 'mf-barangay', 'mf-city'];
    const programLabel = $('manualProgram')?.selectedOptions?.[0]?.textContent?.trim() || '';
    const needsClassification = Boolean(programLabel && (statusesByProgram[programLabel] || []).length);
    if (needsClassification) req1.push('mf-classification');
    const is4ps = document.getElementById('mf-flag-4ps')?.classList.contains('on');
    if (is4ps) req1.push('mf-4psid');

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
    
    if (['jobmatch', 'firstjobseek', 'jobfair', 'whip'].includes(selectedProgram)) {
      req2.push('mf-company', 'mf-position');
    }
    if (selectedProgram === 'jobfair') {
      req2.push('mf-jfevent', 'mf-jfcompany');
    }
    if (selectedProgram === 'whip') {
      req2.push('mf-project');
    }
    if (['careerdev', 'lmi'].includes(selectedProgram)) {
      req2.push('mf-school');
    }
    if (selectedProgram === 'accreditation') {
      req2.push('mf-accred-company', 'mf-accred-year');
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
    
    for (const id of req2) {
      const el = $(id);
      if (el && !el.value.trim()) {
        isValid = false;
        if (!firstInvalid) firstInvalid = el;
      }
    }

    if (!isValid) {
      window.showToast('Please fill in all required fields in Panel 2.', 'warning');
      if (firstInvalid) firstInvalid.focus();
      return false;
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
