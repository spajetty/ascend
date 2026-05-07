    <!-- Spacer so content isn't hidden behind mobile nav -->
    <div class="md:hidden h-20"></div>

<script>
// ── Sidebar collapse toggle ───────────────────────────────────────────────
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar   = document.getElementById('sidebar');
const main      = document.getElementById('mainContent');
const icon      = document.getElementById('toggleIcon');

if (toggleBtn && sidebar && main && icon) {
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-collapsed');

        if (sidebar.classList.contains('sidebar-collapsed')) {
            main.classList.remove('md:ml-56');
            main.classList.add('md:ml-20');
            icon.style.transform = 'rotate(180deg)';
        } else {
            main.classList.remove('md:ml-20');
            main.classList.add('md:ml-56');
            icon.style.transform = 'rotate(0deg)';
        }
    });
}

// ── Mobile nav scroll arrows ──────────────────────────────────────────────
const mobileNav   = document.getElementById('mobileNav');
const navLeft     = document.getElementById('navLeft');
const navRight    = document.getElementById('navRight');
const scrollAmount = 120;

if (mobileNav && navLeft && navRight) {
    navLeft.addEventListener('click',  () => mobileNav.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    navRight.addEventListener('click', () => mobileNav.scrollBy({ left:  scrollAmount, behavior: 'smooth' }));
}
</script>
<script>
async function fetchJson(url) {
    try {
        const res = await fetch(url);
        if (!res.ok) return null;
        return await res.json();
    } catch (_) {
        return null;
    }
}

function setText(selectorOrEl, value) {
    const el = typeof selectorOrEl === 'string' ? document.querySelector(selectorOrEl) : selectorOrEl;
    if (el) el.textContent = value;
}

function monthYear(month, year) {
    const parts = [month, year].filter(Boolean);
    return parts.length ? parts.join(' ') : '—';
}

function noDataRow(colspan) {
    return `<tr class="border-b border-gray-50 hover:bg-gray-50"><td colspan="${colspan}" class="px-4 py-8 text-center text-gray-500">No data found.</td></tr>`;
}

function setCardValues(values) {
    const cards = document.querySelectorAll('.grid .text-2xl.font-bold.text-gray-800');
    values.forEach((value, idx) => {
        if (cards[idx]) {
            cards[idx].textContent = Number(value || 0).toLocaleString();
        }
    });
}

function reRunPageTableHandlers() {
    if (typeof window.applyFilters === 'function') {
        window.applyFilters();
        return;
    }
    if (typeof window.renderPage === 'function') {
        window.renderPage();
    }
}

async function bindYouthEmployability() {
    const [statsData, spesData, gipData, wiData] = await Promise.all([
        fetchJson('/api/get-youth-employability-stats.php'),
        fetchJson('/api/get-spes-data.php'),
        fetchJson('/api/get-gip-data.php'),
        fetchJson('/api/get-work-immersion-data.php')
    ]);

    const spesCount = (spesData?.success && Array.isArray(spesData.data)) ? spesData.data.length : 0;
    const gipCount = (gipData?.success && Array.isArray(gipData.data)) ? gipData.data.length : 0;
    const wiCount = (wiData?.success && Array.isArray(wiData.data)) ? wiData.data.length : 0;
    const totalYouthServed = spesCount + gipCount + wiCount;
    const totalHired = statsData?.success ? Number(statsData.data.totalHired || 0) : 0;

    setText('#totalYouthServed', Number(totalYouthServed).toLocaleString());
    setText('#spesParticipants', Number(spesCount).toLocaleString());
    setText('#gipInterns', Number(gipCount).toLocaleString());
    setText('#workImmersionParticipants', Number(wiCount).toLocaleString());
    setText('#totalHired', Number(totalHired).toLocaleString());

    const spesBody = document.getElementById('spesTableBody');
    if (spesBody) {
        if (spesData?.success && Array.isArray(spesData.data) && spesData.data.length) {
            spesBody.innerHTML = spesData.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-semibold">${row.month_reported || '—'}</td>
                    <td class="px-4 py-2 text-gray-600">${row.employer || '—'}</td>
                    <td class="px-4 py-2 text-gray-600">${row.start_of_contract || '—'}</td>
                    <td class="px-4 py-2 text-gray-600">${row.end_of_contract || '—'}</td>
                    <td class="px-4 py-2 font-semibold text-gray-700">${row.days ?? 0}</td>
                    ${'<td class="px-4 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(23)}
                </tr>
            `).join('');
        } else {
            spesBody.innerHTML = noDataRow(28);
        }
    }

    const gipBody = document.getElementById('gipTableBody');
    if (gipBody) {
        if (gipData?.success && Array.isArray(gipData.data) && gipData.data.length) {
            gipBody.innerHTML = gipData.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-semibold">${row.contract_period || '—'}</td>
                    <td class="px-4 py-2 text-gray-600">${row.school || '—'}</td>
                    <td class="px-4 py-2"><span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-0.5 rounded-full">${row.college_or_shs || '—'}</span></td>
                    <td class="px-4 py-2 text-gray-600">${row.course || '—'}</td>
                    <td class="px-4 py-2 text-gray-600">${row.office_assignment || '—'}</td>
                    <td class="px-4 py-2 font-semibold text-gray-700">${row.required_hours ?? 0}</td>
                    ${'<td class="px-4 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(21)}
                </tr>
            `).join('');
        } else {
            gipBody.innerHTML = noDataRow(27);
        }
    }

    const wiBody = document.getElementById('workImmersionTableBody');
    if (wiBody) {
        if (wiData?.success && Array.isArray(wiData.data) && wiData.data.length) {
            wiBody.innerHTML = wiData.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-semibold">${row.contract_period || '—'}</td>
                    <td class="px-4 py-2 text-gray-600">${row.school || '—'}</td>
                    <td class="px-4 py-2"><span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">${row.education_level || '—'}</span></td>
                    <td class="px-4 py-2 text-gray-600">${row.course || '—'}</td>
                    <td class="px-4 py-2 text-gray-600">${row.office_assignment || '—'}</td>
                    <td class="px-4 py-2 font-semibold text-gray-700">${row.required_hours ?? 0}</td>
                    ${'<td class="px-4 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(21)}
                </tr>
            `).join('');
        } else {
            wiBody.innerHTML = noDataRow(27);
        }
    }
}

async function bindDetailPages() {
    const path = window.location.pathname;

    if (path.endsWith('/pages/programs/emp-engagement/emp-accreditation.php')) {
        const data = await fetchJson('/api/get-employers-accreditation.php');
        const tbody = document.getElementById('accreditationTbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50"
                    data-year="${row.year || ''}"
                    data-month="${monthYear(row.month, row.year)}"
                    data-accreditation="${String(row.accreditation || '').toLowerCase()}"
                    data-company="${(row.company_name || '').toLowerCase()}"
                    data-esttype="${(row.est_type || '').toLowerCase()}"
                    data-industry="${(row.industry || '').toLowerCase()}"
                    data-city="${(row.city || '').toLowerCase()}">
                    <td class="px-4 py-3 text-gray-700 font-medium">${monthYear(row.month, row.year)}</td>
                    <td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ${String(row.accreditation).toLowerCase() === 'new' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'}">${row.accreditation ? row.accreditation.charAt(0).toUpperCase() + row.accreditation.slice(1) : '—'}</span></td>
                    <td class="px-4 py-3 text-gray-700">${row.company_name || '—'}</td>
                    <td class="px-4 py-3 font-medium text-blue-500">${row.est_type || '—'}</td>
                    <td class="px-4 py-3 text-gray-600">${row.industry || '—'}</td>
                    <td class="px-4 py-3 text-gray-600">${row.city || '—'}</td>
                    <td class="px-4 py-3 text-center text-gray-400">—</td>
                </tr>
            `).join('');
            setText('#cardTotalEmployers', Number(data.stats?.total ?? data.data.length).toLocaleString());
            setText('#cardNew', Number(data.stats?.new ?? 0).toLocaleString());
            setText('#cardRenewed', Number(data.stats?.renew ?? 0).toLocaleString());
            const year = Number(document.getElementById('filterYear')?.value || new Date().getFullYear());
            setText('#cardActive', Number(data.data.filter((r) => Number(r.year) === year).length).toLocaleString());
        } else {
            tbody.innerHTML = noDataRow(7);
            setText('#cardTotalEmployers', 0);
            setText('#cardNew', 0);
            setText('#cardRenewed', 0);
            setText('#cardActive', 0);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/emp-engagement/whip.php')) {
        const data = await fetchJson('/api/get-whip-data.php');
        const tbody = document.querySelector('main table tbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-700 font-medium">${monthYear(row.month, row.year)}</td>
                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${row.male ?? 0}</td>
                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${row.female ?? 0}</td>
                    <td class="px-4 py-3 text-center font-semibold text-orange-500 bg-orange-50 border-l border-gray-100">${row.total ?? 0}</td>
                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${row.project_name || '—'}</td>
                    <td class="px-4 py-3 text-center text-gray-400 border-l border-gray-100">—</td>
                </tr>
            `).join('');
            setCardValues([
                data.stats?.workersHired ?? 0,
                data.stats?.maleTotal ?? 0,
                data.stats?.femaleTotal ?? 0,
                data.stats?.infrastructureProjects ?? 0
            ]);
        } else {
            tbody.innerHTML = noDataRow(6);
            setCardValues([0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/emp-facilitation/job-match.php')) {
        const data = await fetchJson('/api/get-job-matching-data.php');
        const tbody = document.querySelector('main table tbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-medium">${monthYear(row.month, row.year)}</td>
                    ${'<td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(21)}
                </tr>
            `).join('');
            setCardValues([data.data.length, 0, 0, 0]);
        } else {
            tbody.innerHTML = noDataRow(22);
            setCardValues([0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/emp-facilitation/job-fair.php')) {
        const data = await fetchJson('/api/get-job-fair-data.php');
        const tbody = document.querySelector('#jobFairTable tbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="${(row.company_name || '').toLowerCase()}">
                    <td class="px-4 py-2 text-gray-700 font-medium">${monthYear(row.month, row.year)}</td>
                    <td class="px-4 py-2 text-gray-700">${row.company_name || '—'}</td>
                    <td class="px-3 py-2 text-center text-blue-500 font-semibold border-l border-gray-100">${row.vacancy_male ?? 0}</td>
                    <td class="px-3 py-2 text-center text-blue-500 font-semibold">${row.vacancy_female ?? 0}</td>
                    <td class="px-3 py-2 text-center font-bold text-blue-600 bg-blue-50">${row.total_vacancies ?? 0}</td>
                    ${'<td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(15)}
                    <td class="px-3 py-2 text-center border-l border-gray-100 text-gray-400">—</td>
                </tr>
            `).join('');
            const totalVacancies = data.data.reduce((sum, row) => sum + Number(row.total_vacancies || 0), 0);
            const employerCount = new Set(data.data.map((row) => row.company_name || '')).size;
            setCardValues([totalVacancies, employerCount, 0, 0, 0]);
        } else {
            tbody.innerHTML = noDataRow(22);
            setCardValues([0, 0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/emp-facilitation/first-time.php')) {
        const data = await fetchJson('/api/get-first-time-jobseek-data.php');
        const tbody = document.querySelector('main table tbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700 font-medium">${monthYear(row.month, row.year)}</td>
                    <td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>
                    <td class="px-3 py-2 text-center text-gray-400">—</td>
                    <td class="px-3 py-2 text-center font-semibold text-teal-600 bg-teal-50">${row.jobseek ?? 0}</td>
                    <td class="px-3 py-2 text-center font-semibold text-pink-500 border-l border-gray-100">${row.occ_permit ?? 0}</td>
                    <td class="px-3 py-2 text-center font-semibold text-green-500">${row.health_card ?? 0}</td>
                    ${'<td class="px-3 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(15)}
                    <td class="px-3 py-2 text-center border-l border-gray-100 text-gray-400">—</td>
                </tr>
            `).join('');
            const totalJobseek = data.data.reduce((sum, row) => sum + Number(row.jobseek || 0), 0);
            const totalOcc = data.data.reduce((sum, row) => sum + Number(row.occ_permit || 0), 0);
            const totalHealth = data.data.reduce((sum, row) => sum + Number(row.health_card || 0), 0);
            setCardValues([totalJobseek, totalOcc, totalHealth, 0]);
        } else {
            tbody.innerHTML = noDataRow(23);
            setCardValues([0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/career-dev/cdsp.php') || path.endsWith('/pages/programs/career-dev/lmi-orientation.php')) {
        const data = await fetchJson('/api/get-career-development-data.php');
        const isCdsp = path.endsWith('/pages/programs/career-dev/cdsp.php');
        const rows = isCdsp ? (data?.data?.cdsp || []) : (data?.data?.lmi || []);
        const tbody = document.querySelector('main table tbody');
        if (!tbody) return;
        if (data?.success && rows.length) {
            const normalized = rows.map((r) => ({
                date: r.date || '—',
                school: r.school || '—',
                male: Number(isCdsp ? (r.cdsp_m || 0) : (r.lmi_m || 0)),
                female: Number(isCdsp ? (r.cdsp_f || 0) : (r.lmi_f || 0)),
                total: Number(r.total || 0)
            }));
            tbody.innerHTML = normalized.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50" data-school="${row.school.toLowerCase()}">
                    <td class="px-4 py-3 text-gray-700 font-medium">${row.date}</td>
                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${row.school}</td>
                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${row.male}</td>
                    <td class="px-4 py-3 text-center text-gray-600 border-l border-gray-100">${row.female}</td>
                    <td class="px-4 py-3 text-center font-semibold text-teal-600 bg-teal-50 border-l border-gray-100">${row.total}</td>
                    <td class="px-4 py-3 text-center border-l border-gray-100 text-gray-400">—</td>
                </tr>
            `).join('');
            const sessions = normalized.length;
            const maleTotal = normalized.reduce((sum, r) => sum + r.male, 0);
            const femaleTotal = normalized.reduce((sum, r) => sum + r.female, 0);
            const participants = maleTotal + femaleTotal;
            setCardValues([sessions, participants, maleTotal, femaleTotal]);
        } else {
            tbody.innerHTML = noDataRow(6);
            setCardValues([0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/youth-emp/spes.php')) {
        const data = await fetchJson('/api/get-spes-data.php');
        const tbody = document.querySelector('#spesTable tbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50" data-employer="${(row.employer || '').toLowerCase()}">
                    <td class="px-3 py-2 text-gray-700 font-medium">${row.month_reported || '—'}</td>
                    <td class="px-3 py-2 text-gray-700">${row.employer || '—'}</td>
                    <td class="px-3 py-2 text-gray-600">${row.start_of_contract || '—'}</td>
                    <td class="px-3 py-2 text-gray-600">${row.end_of_contract || '—'}</td>
                    <td class="px-3 py-2 text-center text-gray-700 font-medium">${row.days ?? 0}</td>
                    ${'<td class="px-2 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(21)}
                    <td class="px-3 py-2 text-center border-l border-gray-100 text-gray-400">—</td>
                </tr>
            `).join('');
            const totalRows = data.data.length;
            setCardValues([totalRows, totalRows, 0, 0, 0, 0, 0]);
        } else {
            tbody.innerHTML = noDataRow(27);
            setCardValues([0, 0, 0, 0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/youth-emp/gip.php')) {
        const data = await fetchJson('/api/get-gip-data.php');
        const tbody = document.querySelector('#gipTable tbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50" data-school="${(row.school || '').toLowerCase()}" data-type="${(row.college_or_shs || '').toLowerCase()}">
                    <td class="px-3 py-3 text-gray-700 font-medium">${row.contract_period || '—'}</td>
                    <td class="px-3 py-3 text-gray-700">${row.school || '—'}</td>
                    <td class="px-3 py-3 text-gray-600">${row.college_or_shs || '—'}</td>
                    <td class="px-3 py-3 text-gray-600">${row.course || '—'}</td>
                    <td class="px-3 py-3 text-gray-600">${row.office_assignment || '—'}</td>
                    <td class="px-3 py-3 text-center text-gray-700">${row.required_hours ?? 0}</td>
                    ${'<td class="px-2 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(21)}
                    <td class="px-3 py-3 text-center border-l border-gray-100 text-gray-400">—</td>
                </tr>
            `).join('');
            const totalRows = data.data.length;
            setCardValues([totalRows, totalRows, totalRows, totalRows, 0, 0, 0]);
        } else {
            tbody.innerHTML = noDataRow(28);
            setCardValues([0, 0, 0, 0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
        return;
    }

    if (path.endsWith('/pages/programs/youth-emp/work-imm.php')) {
        const data = await fetchJson('/api/get-work-immersion-data.php');
        const tbody = document.querySelector('#wiTable tbody');
        if (!tbody) return;
        if (data?.success && Array.isArray(data.data) && data.data.length) {
            tbody.innerHTML = data.data.map((row) => `
                <tr class="border-b border-gray-50 hover:bg-gray-50" data-school="${(row.school || '').toLowerCase()}" data-type="${(row.education_level || '').toLowerCase()}">
                    <td class="px-3 py-3 text-gray-700 font-medium">${row.contract_period || '—'}</td>
                    <td class="px-3 py-3 text-gray-700">${row.school || '—'}</td>
                    <td class="px-3 py-3 text-gray-600">${row.education_level || '—'}</td>
                    <td class="px-3 py-3 text-gray-600">${row.course || '—'}</td>
                    <td class="px-3 py-3 text-gray-600">${row.office_assignment || '—'}</td>
                    <td class="px-3 py-3 text-center text-gray-700">${row.required_hours ?? 0}</td>
                    ${'<td class="px-2 py-2 text-center text-gray-400 border-l border-gray-100">—</td>'.repeat(21)}
                    <td class="px-3 py-3 text-center border-l border-gray-100 text-gray-400">—</td>
                </tr>
            `).join('');
            const totalRows = data.data.length;
            setCardValues([totalRows, totalRows, totalRows, totalRows, 0, 0, 0]);
        } else {
            tbody.innerHTML = noDataRow(28);
            setCardValues([0, 0, 0, 0, 0, 0, 0]);
        }
        reRunPageTableHandlers();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;
    if (path.endsWith('/pages/programs/youth-employability.php')) {
        bindYouthEmployability();
    }
    bindDetailPages();
});
</script>
</body>
</html>
