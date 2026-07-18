const $ = id => document.getElementById(id);

export function buildReview(selectedProgram, selectedSection, PROGRAMS, SECTION_LABELS) {
  const p = (PROGRAMS[selectedSection] || []).find(x => x.val === selectedProgram);
  const isAccreditation = selectedProgram === 'accreditation';

  $('mf-rv-section').textContent = SECTION_LABELS[selectedSection] || '—';
  $('mf-rv-program').textContent = p ? p.label : '—';

  if (isAccreditation) {
    $('mf-rv-name').textContent = 'Not applicable';
    $('mf-rv-dob').textContent = 'Not applicable';
    $('mf-rv-sex').textContent = 'Not applicable';
    $('mf-rv-class').textContent = 'Not applicable';
    $('mf-rv-district').textContent = 'Not applicable';
    $('mf-rv-barangay').textContent = 'Not applicable';
    $('mf-rv-flags').textContent = 'None';
  } else {
    const fname = $('mf-fname')?.value || '';
    const lname = $('mf-lname')?.value || '';
    $('mf-rv-name').textContent =
      (lname && fname) ? `${lname}, ${fname}` : fname || lname || '—';

    const dob = $('mf-dob')?.value;
    $('mf-rv-dob').textContent = dob
      ? new Date(dob).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })
      : '—';

    const sexChip = document.querySelector('[data-group="sex"].on');
    $('mf-rv-sex').textContent = sexChip?.dataset.val || '—';

    const classificationWrap = $('mf-classification-wrap');
    $('mf-rv-class').textContent    = classificationWrap?.style.display === 'none' ? '—' : ($('mf-classification')?.value || '—');
    $('mf-rv-district').textContent = $('mf-district')?.value ? `District ${$('mf-district')?.value}` : '—';
    $('mf-rv-barangay').textContent = $('mf-barangay')?.value || '—';

    const flags = [];
    if ($('mf-flag-4ps')?.classList.contains('on')) flags.push('4Ps');
    if ($('mf-flag-pwd')?.classList.contains('on')) flags.push('PWD');
    if ($('mf-flag-ofw')?.classList.contains('on')) flags.push('OFW Dependent');
    $('mf-rv-flags').textContent = flags.length ? flags.join(', ') : 'None';
  }

  // --- Program Details ---
  const progGrid = $('mf-rv-prog-grid');
  const progSec = $('mf-rv-prog-details-sec');
  const progTitle = $('mf-rv-prog-title');

  if (progGrid && progSec && progTitle) {
    progGrid.innerHTML = '';
    progSec.style.display = 'none';

    let details = [];

    // Shared for Job Matching / FTJ / Job Fair
    if (['jobmatch', 'firstjobseek', 'jobfair'].includes(selectedProgram)) {
      details.push(['Company', $('mf-company')?.value || '—']);
      details.push(['Position Applied', $('mf-position')?.value || '—']);
      details.push(['Batch / Period', $('mf-batch')?.value || '—']);
    }

    if (selectedProgram === 'jobfair') {
      const type = document.querySelector('[data-group="jftype"].on')?.dataset.val || '—';
      details.push(['Job Fair Type', type]);
    }

    if (selectedProgram === 'spes') {
      const stutype = document.querySelector('[data-group="stutype"].on')?.dataset.val || '—';
      details.push(['Student Type', stutype]);
      details.push(['School', $('mf-spes-school')?.value || '—']);
      details.push(['Course', $('mf-spes-course')?.value || '—']);
      details.push(['Highest Educ.', $('mf-highest-educ')?.value || '—']);
      details.push(['Company / Assignment', $('mf-store')?.value || '—']);
      const spescat = document.querySelector('[data-group="spescat"].on')?.dataset.val || '—';
      details.push(['Category', spescat]);
      details.push(['Contract Start', $('mf-contract-start')?.value || '—']);
      details.push(['Contract End', $('mf-contract-end')?.value || '—']);
      details.push(['Days', $('mf-days')?.value || '—']);
      details.push(['Batch', $('mf-spes-batch')?.value || '—']);
    }

    if (selectedProgram === 'wiirp') {
      const inttype = document.querySelector('[data-group="inttype"].on')?.dataset.val || '—';
      details.push(['Internship Type', inttype]);
      details.push(['School', $('mf-int-school')?.value || '—']);
      details.push(['Course / Strand', $('mf-int-course')?.value || '—']);
      details.push(['Year Level', $('mf-year-level')?.value || '—']);
      details.push(['Required Hours', $('mf-req-hours')?.value || '—']);
      details.push(['Contract Period', $('mf-contract-period')?.value || '—']);
    }

    if (selectedProgram === 'gip') {
      const gipstutype = document.querySelector('[data-group="gipstutype"].on')?.dataset.val || '—';
      details.push(['Student Type', gipstutype]);
      details.push(['School', $('mf-gip-school')?.value || '—']);
      details.push(['Course', $('mf-gip-course')?.value || '—']);
      details.push(['Highest Educ.', $('mf-gip-highest-educ')?.value || '—']);
      details.push(['Office Assignment', $('mf-gip-office')?.value || '—']);
      details.push(['Contract Start', $('mf-gip-contract-start')?.value || '—']);
      details.push(['Contract End', $('mf-gip-contract-end')?.value || '—']);
      details.push(['Days', $('mf-gip-days')?.value || '—']);
      details.push(['Batch', $('mf-gip-batch')?.value || '—']);
    }

    if (selectedProgram === 'whip') {
      const mode = $('mf-h-whip-project-mode')?.value || 'search';
      if (mode === 'new' || mode === 'edit') {
        const suffix = mode === 'new' ? ' (new)' : ' (edited — updates the master project)';
        details.push(['Project', `${$('mf-project-title')?.value || '—'}${suffix}`]);
        details.push(['Project Contractor', $('mf-project-contractor')?.value || '—']);
        details.push(['Nature of Project', $('mf-project-nature')?.value || '—']);
        details.push(['Duration', $('mf-project-duration')?.value || '—']);
        details.push(['Budget', $('mf-project-budget')?.value || '—']);
        details.push(['Fund Source', $('mf-project-fund')?.value || '—']);
      } else {
        details.push(['Project', $('mf-whip-project-search')?.value || '—']);
      }
      details.push(['Position', $('mf-whip-pos')?.value || '—']);
      details.push(['Date Hired', $('mf-date-hired')?.value || '—']);
      details.push(['Batch', $('mf-whip-batch')?.value || '—']);
    }

    if (['careerdev', 'lmi'].includes(selectedProgram)) {
      details.push(['School', $('mf-school')?.value || '—']);
      details.push(['Grade Level', $('mf-grade-level')?.value || '—']);
      details.push(['Date of Conduct', $('mf-conduct')?.value || '—']);
      details.push(['Male Participants', $('mf-male')?.value || '0']);
      details.push(['Female Participants', $('mf-female')?.value || '0']);
      details.push(['Batch', $('mf-school-batch')?.value || '—']);
    }

    if (selectedProgram === 'accreditation') {
      const beneficiarySection = $('mf-rv-beneficiary-section');
      if (beneficiarySection) beneficiarySection.style.display = 'none';
      details.push(['Company', $('mf-accred-company')?.value || '—']);
      const accstatus = document.querySelector('[data-group="accstatus"].on')?.dataset.val || '—';
      details.push(['Accreditation', accstatus === 'renew' ? 'Renew' : 'New']);
      const period = $('mf-accred-period')?.value || '';
      details.push(['Accreditation Period', period ? new Date(period + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) : '—']);
      details.push(['Est. Type', $('mf-accred-est-type')?.value || '—']);
      details.push(['Industry', $('mf-accred-industry')?.value || '—']);
      details.push(['City/Municipality', $('mf-accred-city')?.value || '—']);
    }

    if (details.length > 0) {
      progTitle.textContent = `${p ? p.label : 'Program'} Details`;
      details.forEach(([label, value]) => {
        const dl = document.createElement('dl');
        dl.className = 'mf-rv-item';
        dl.innerHTML = `<dt>${label}</dt><dd>${value}</dd>`;
        progGrid.appendChild(dl);
      });
      progSec.style.display = 'block';
    }

    if (selectedProgram !== 'accreditation') {
      const beneficiarySection = $('mf-rv-beneficiary-section');
      if (beneficiarySection) beneficiarySection.style.display = '';
    }
  }
}