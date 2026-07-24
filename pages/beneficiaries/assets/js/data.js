/**
 * Loads beneficiaries from the database via the backend API.
 */

let beneficiaries    = [];   // current page of records from the server
let totalBeneficiaries = 0;  // total matching rows (for pagination)
let beneficiaryStats = { total: 0, hired: 0, referred: 0, registered: 0 };

/** Ordinal helper: 1 → "1st", 2 → "2nd", etc. */
function ordinal(n) {
  const s = ['th', 'st', 'nd', 'rd'];
  const v = n % 100;
  return n + (s[(v - 20) % 10] || s[v] || s[0]);
}

/** Build two-letter avatar initials from available data. */
function makeAvatar(record) {
  const first = (record.first_name || '').trim();
  const last  = (record.last_name  || '').trim();
  if (first && last)  return (first[0] + last[0]).toUpperCase();
  if (first)          return first.slice(0, 2).toUpperCase();
  // Fallback to last 2 digits of benef_id when no name is present
  const id = String(record.benef_id || '').padStart(2, '0');
  return '#' + id.slice(-2);
}

/**
 * Map a raw API row → the shape expected by table.js / profile.js.
 * Adjust the field mappings here when you add name columns to the schema.
 */
function mapRow(b) {
  // Build full name from DB columns; fall back gracefully if not yet populated.
  const parts = [b.first_name, b.middle_name, b.last_name, b.suffix]
    .map(p => (p || '').trim())
    .filter(Boolean);
  const displayName = parts.length
    ? parts.join(' ')
    : (b.contact ? 'Contact: ' + b.contact : 'Beneficiary #' + b.benef_id);

  return {
    // ── Identifiers ──────────────────────────────────────────────────────
    id:          b.benef_id,
    benef_id:    b.benef_id,

    // ── Table columns ────────────────────────────────────────────────────
    name:        displayName,
    gender:      b.sex        || '—',
    section:     b.section_name || '—',
    program:     b.program_name || '—',
    spes_status: b.spes_status || '',
    status:      b.classification || '—',
    enrollments: b.enrollments || [],
    email:       b.email     || '—',
    contact:     b.contact   || '—',

    // ── Profile header ───────────────────────────────────────────────────
    avatar:      makeAvatar(b),
    age:         b.age != null ? b.age + ' years old' : '—',
    lastVisit:   b.last_visit  || '—',
    visit:       b.visit_label || '—',

    // ── Overview tab ─────────────────────────────────────────────────────
    dob:         b.dob_formatted || '—',
    civil:       b.civil_status  || '—',
    address:     b.address       || '—',
    emailAddr:   b.email         || '—',
    phone:       b.contact       || '—',
    notes:       '',             // emphistory.notes loaded lazily on profile open
    education:   '—',           // not stored in DB — add column when ready
    skills:      [],            // not stored in DB — add column when ready

    // ── Employment (populated lazily when profile opens) ─────────────────
    employment:  [],

    // ── Flags ────────────────────────────────────────────────────────────
    is4ps:       !!b.is_4ps,
    isPwd:       !!b.is_pwd,
    isOfwDep:    !!b.is_ofw_dependent,
  };
}

/**
 * Fetch a page of beneficiaries from the server with optional filters.
 * Populates `beneficiaries` (current page) and `totalBeneficiaries` (total count).
 *
 * @param {object} params - Keys: page, limit, search, section, program, status
 */
async function fetchBeneficiaries(params = {}) {
  const tableBody = document.getElementById('tableBody');
  if (window.AscendLoading) {
    window.AscendLoading.setContainerLoading(tableBody, true, 'Loading beneficiaries…');
  }

  try {
    // Build query string from non-empty params only
    const qs = new URLSearchParams(
      Object.fromEntries(
        Object.entries(params).filter(([, v]) => v !== '' && v != null)
      )
    ).toString();

    const url = '../../backend/beneficiaries/get_beneficiaries.php' + (qs ? '?' + qs : '');
    const res  = await fetch(url);
    const json = await res.json();

    if (!json.success) {
      console.error('[data.js] API error:', json.message);
      return;
    }

    beneficiaries      = (json.data  || []).map(mapRow);
    totalBeneficiaries = json.total  ?? 0;
    beneficiaryStats   = json.stats  || beneficiaryStats;

    updateStatCards(beneficiaryStats);

    if (tableBody && window.AscendLoading) {
      window.AscendLoading.releaseContainerLoading(tableBody);
    }

  } catch (err) {
    console.error('[data.js] Failed to fetch beneficiaries:', err);
    if (tableBody) {
      tableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:24px;color:var(--text-muted);">Failed to load beneficiaries.</td></tr>';
      if (window.AscendLoading) window.AscendLoading.releaseContainerLoading(tableBody);
    }
  }
}

/**
 * Fetch employment history for one beneficiary (lazy — called when profile opens).
 * Returns an array of { co, st, dt, note } matching the profile.js shape.
 */
async function fetchEmploymentHistory(benefId) {
  try {
    const res  = await fetch(`../../backend/beneficiaries/get_empHistory.php?id=${benefId}`);
    const json = await res.json();

    if (!json.success) return [];

    return (json.employment || []).map(e => ({
      id:             e.history_id      || 0,
      company_id:     e.company_id      || 0,
      co:             e.company_name    || '—',
      status:         e.status          || '—',
      st:             e.status          || '—',
      date_of_record:  e.date_of_record  || '',
      dt:             e.date_formatted  || '—',
      notes:          e.notes           || '',
      note:           e.notes           || '',
    }));
  } catch {
    return [];
  }
}

/** Write real counts into the stat card elements. */
function updateStatCards(stats) {
  const el = (id) => document.getElementById(id);
  if (el('statTotal'))      el('statTotal').textContent      = stats.total.toLocaleString();
  if (el('statHired'))      el('statHired').textContent      = stats.hired.toLocaleString();
  if (el('statReferred'))   el('statReferred').textContent   = stats.referred.toLocaleString();
  if (el('statRegistered')) el('statRegistered').textContent = stats.registered.toLocaleString();
}