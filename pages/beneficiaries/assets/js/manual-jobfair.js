import { openCreateEventModal } from '../../../../assets/js/imports/job-fair-modal.js';

const $ = id => document.getElementById(id);

let allJobFairEvents = [];

async function fetchAllJobFairEvents(force = false) {
  if (!force && allJobFairEvents.length > 0) return;
  try {
    const res = await fetch('../../backend/import/get_all_job_fair_events.php');
    if (!res.ok) return;
    const data = await res.json();
    if (data.success && data.events) {
      allJobFairEvents = data.events;
    }
  } catch (err) {
    console.error('Failed to fetch job fair events:', err);
  }
}

async function fetchEventParticipants(eventId) {
  try {
    const res = await fetch(`../../backend/import/get_event_participants.php?event_id=${eventId}`);
    const data = await res.json();
    if (data.success && data.participants) {
      let comps = data.participants;
      comps.sort((a, b) => a.company_name.localeCompare(b.company_name));
      return comps;
    }
  } catch (err) {
    console.error('Failed to fetch event participants:', err);
  }
  return [];
}

export function bindJobFairAutocomplete() {
  const eventInput = $('mf-jfevent-input');
  const eventDropdown = $('mf-jfevent-dropdown');
  const eventChips = $('mf-jfevent-chips');
  const eventHiddens = $('mf-jfevent-hiddens');
  const eventValidator = $('mf-jfevent');
  const addEventBtn = $('mf-add-jfevent');
  
  const compWrapper = $('mf-jfcompanies-wrapper');
  const compLists = $('mf-jfcompany-lists');
  const compValidator = $('mf-jfcompany');

  let selectedEvents = [];
  let selectedCompaniesByEvent = {};
  let cachedCompaniesByEvent = {};

  const normalizeCompanies = (items) => {
    if (!Array.isArray(items)) return [];
    const seen = new Set();
    const out = [];
    items.forEach(item => {
      const companyId = Number(item?.company_id);
      const companyName = String(item?.company_name ?? '').trim();
      if (!companyId || !companyName || seen.has(companyId)) return;
      seen.add(companyId);
      out.push({ company_id: companyId, company_name: companyName });
    });
    return out;
  };

  async function addEventWithCompaniesById(eventId, seedCompanies = []) {
    if (!eventId) return;

    await fetchAllJobFairEvents(true);
    const selectedId = Number(eventId);
    const ev = allJobFairEvents.find(e => Number(e.jobfairevent_id) === selectedId);
    if (!ev) {
      if (typeof window.showToast === 'function') {
        window.showToast('Created event was not found in the event list. Please refresh and try again.', 'warning');
      }
      return;
    }

    const alreadySelected = selectedEvents.some(e => Number(e.jobfairevent_id) === selectedId);
    if (!alreadySelected) {
      selectedEvents.push(ev);
    }

    const normalized = normalizeCompanies(seedCompanies);
    if (!selectedCompaniesByEvent[selectedId]) {
      selectedCompaniesByEvent[selectedId] = [];
    }

    normalized.forEach(comp => {
      if (!selectedCompaniesByEvent[selectedId].some(existing => Number(existing.company_id) === Number(comp.company_id))) {
        selectedCompaniesByEvent[selectedId].push(comp);
      }
    });

    cachedCompaniesByEvent[selectedId] = await fetchEventParticipants(selectedId);

    renderEvents();
    await renderCompanySections();
  }

  function validateCompanies() {
    let hasAll = true;
    if (selectedEvents.length === 0) hasAll = false;
    selectedEvents.forEach(ev => {
      const comps = selectedCompaniesByEvent[ev.jobfairevent_id] || [];
      if (comps.length === 0) hasAll = false;
    });
    if (compValidator) compValidator.value = hasAll ? '1' : '';
  }

  function renderEvents() {
    if (eventChips) eventChips.innerHTML = '';
    if (eventHiddens) eventHiddens.innerHTML = '';
    
    selectedEvents.forEach(ev => {
      const chip = document.createElement('div');
      chip.className = 'inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium';
      const label = ev.event_name ? ev.event_name : ev.venue;
      chip.innerHTML = `<span>${label}</span>
        <button type="button" class="text-blue-500 hover:text-blue-900 focus:outline-none">&times;</button>`;
      chip.querySelector('button').addEventListener('click', () => {
        selectedEvents = selectedEvents.filter(e => e.jobfairevent_id !== ev.jobfairevent_id);
        delete selectedCompaniesByEvent[ev.jobfairevent_id];
        renderEvents();
        renderCompanySections();
      });
      if (eventChips) eventChips.appendChild(chip);

      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'jobfairevent_ids[]';
      hidden.value = ev.jobfairevent_id;
      if (eventHiddens) eventHiddens.appendChild(hidden);
    });
    
    if (eventValidator) eventValidator.value = selectedEvents.length > 0 ? '1' : '';
    
    if (selectedEvents.length > 0) {
      if (compWrapper) compWrapper.style.display = 'block';
      
      const firstEv = selectedEvents[0];
      if (firstEv.date_start) {
        const batchEl = $('mf-jf-batch');
        if (batchEl && !batchEl.value) {
           const parts = firstEv.date_start.split('-');
           if (parts.length >= 2) {
             batchEl.value = `${parts[0]}-${parts[1]}`;
           }
        }
      }
    } else {
      if (compWrapper) compWrapper.style.display = 'none';
      validateCompanies(); // Revalidate when empty
    }
  }

  async function renderCompanySections() {
    if (!compLists) return;
    compLists.innerHTML = '';
    
    for (const ev of selectedEvents) {
      const evId = ev.jobfairevent_id;
      const label = ev.event_name ? ev.event_name : ev.venue;
      
      if (!cachedCompaniesByEvent[evId]) {
        cachedCompaniesByEvent[evId] = await fetchEventParticipants(evId);
      }
      const available = cachedCompaniesByEvent[evId];
      if (!selectedCompaniesByEvent[evId]) selectedCompaniesByEvent[evId] = [];

      const block = document.createElement('div');
      block.className = 'border border-gray-200 rounded-lg p-3 bg-gray-50';
      
      block.innerHTML = `
        <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Companies for: ${label}</div>
        <div class="relative">
          <div class="flex flex-wrap gap-2 mb-2 empty:hidden chips-container"></div>
          <input type="text" placeholder="Search companies..." class="w-full bg-white px-3 py-1.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-600 comp-input">
          <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] hidden max-h-40 overflow-y-auto overflow-x-hidden comp-dropdown" style="z-index: 9999;"></div>
          <div class="hidden-inputs"></div>
        </div>
      `;
      
      compLists.appendChild(block);

      const chipsCont = block.querySelector('.chips-container');
      const hiddenCont = block.querySelector('.hidden-inputs');
      const inputEl = block.querySelector('.comp-input');
      const dropdownEl = block.querySelector('.comp-dropdown');

      function renderChips() {
        chipsCont.innerHTML = '';
        hiddenCont.innerHTML = '';
        selectedCompaniesByEvent[evId].forEach(comp => {
          const chip = document.createElement('div');
          chip.className = 'inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 px-2 py-1 rounded text-xs font-medium';
          chip.innerHTML = `<span>${comp.company_name}</span>
            <button type="button" class="text-emerald-500 hover:text-emerald-900 focus:outline-none">&times;</button>`;
          chip.querySelector('button').addEventListener('click', () => {
            selectedCompaniesByEvent[evId] = selectedCompaniesByEvent[evId].filter(c => c.company_id !== comp.company_id);
            renderChips();
            validateCompanies();
          });
          chipsCont.appendChild(chip);

          const hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = `jf_company_ids[${evId}][]`;
          hidden.value = comp.company_id;
          hiddenCont.appendChild(hidden);
        });
        validateCompanies();
      }
      
      renderChips();

      function showDropdown() {
        const query = inputEl.value.toLowerCase().trim();
        const filtered = available.filter(c => {
          return c.company_name.toLowerCase().includes(query) && !selectedCompaniesByEvent[evId].some(sc => sc.company_id === c.company_id);
        });

        dropdownEl.innerHTML = '';
        if (filtered.length === 0) {
          dropdownEl.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 italic">No options found</div>';
        } else {
          filtered.forEach(item => {
            const div = document.createElement('div');
            div.className = 'px-4 py-3 text-sm font-medium text-gray-700 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors';
            
            const regex = new RegExp(`(${query})`, 'gi');
            div.innerHTML = query.length > 0 
              ? item.company_name.replace(regex, '<span class="text-blue-600 font-semibold">$1</span>') 
              : item.company_name;

            div.addEventListener('click', () => {
              selectedCompaniesByEvent[evId].push(item);
              inputEl.value = '';
              renderChips();
              dropdownEl.classList.add('hidden');
            });
            dropdownEl.appendChild(div);
          });
        }
        dropdownEl.classList.remove('hidden');
      }

      inputEl.addEventListener('focus', showDropdown);
      inputEl.addEventListener('input', showDropdown);

      document.addEventListener('click', (e) => {
        if (!inputEl.parentElement.contains(e.target)) {
          dropdownEl.classList.add('hidden');
        }
      });
    }
    validateCompanies();
  }

  function renderEventDropdown() {
    if (!eventDropdown) return;
    const query = eventInput.value.toLowerCase().trim();
    
    const filtered = allJobFairEvents.filter(e => {
      const name = (e.event_name ? e.event_name : e.venue).toLowerCase();
      return name.includes(query) && !selectedEvents.some(se => se.jobfairevent_id === e.jobfairevent_id);
    });

    eventDropdown.innerHTML = '';
    if (filtered.length === 0) {
      eventDropdown.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 italic">No options found</div>';
    } else {
      filtered.forEach(item => {
        const div = document.createElement('div');
        div.className = 'px-4 py-3 text-sm font-medium text-gray-700 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors';
        
        let label = item.event_name ? `${item.event_name} (${item.venue})` : item.venue;
        if (item.date_start) label += ` - ${item.date_start}`;

        const regex = new RegExp(`(${query})`, 'gi');
        div.innerHTML = query.length > 0 
          ? label.replace(regex, '<span class="text-blue-600 font-semibold">$1</span>') 
          : label;

        div.addEventListener('click', () => {
          selectedEvents.push(item);
          eventInput.value = '';
          renderEvents();
          eventDropdown.classList.add('hidden');
          renderCompanySections();
        });
        eventDropdown.appendChild(div);
      });
    }
    eventDropdown.classList.remove('hidden');
  }

  if (eventInput) {
    const card = eventInput.closest('.mf-card');
    if (card) card.style.overflow = 'visible';

    eventInput.addEventListener('focus', async () => {
      await fetchAllJobFairEvents();
      renderEventDropdown();
    });

    eventInput.addEventListener('input', () => {
      renderEventDropdown();
    });

    document.addEventListener('click', (e) => {
      if (eventDropdown && !eventInput.parentElement.contains(e.target)) {
        eventDropdown.classList.add('hidden');
      }
    });
  }

  if (addEventBtn) {
    addEventBtn.addEventListener('click', () => {
      openCreateEventModal(async ({ eventId, participants }) => {
        await addEventWithCompaniesById(eventId, participants || []);
      }, {
        importedCompanies: [],
        unmatchedCompanies: [],
      });
    });
  }
}
