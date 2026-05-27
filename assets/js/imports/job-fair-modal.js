// Job Fair event + company participants in one modal (two sections, one save).

import { showToast } from '../toast.js';
import { runWithButtonLoading } from '../loading.js';
import { state } from './excel-state.js';

const esc = v => String(v ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');

let _modalMode = 'create';
let _eventId = null;
let _eventDate = null;
let _addedCompanies = [];
let _importSeedCompanies = [];
let _importUnmatchedCompanies = [];
let _searchDebounce = null;
let _onDoneCallback = null;
let _eventsBound = false;
let _companyNameMap = {};
let _activeSearchOriginal = null;

const SAVE_BTN_HTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Save Event';

function buildModalDom() {
    return ''
        + '<div id="jobFairModalBackdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>'
        + '<div id="jobFairModalWrapper" class="relative w-full max-w-5xl rounded-2xl bg-white shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">'
        + '<div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 flex-shrink-0">'
        + '<div class="flex items-center gap-3">'
        + '<div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">'
        + '<svg class="w-4.5 h-4.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">'
        + '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>'
        + '<line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line>'
        + '<line x1="3" y1="10" x2="21" y2="10"></line></svg></div>'
        + '<div><h3 id="jobFairModalTitle" class="text-base font-bold text-gray-900">Job Fair Event</h3>'
        + '<p id="jobFairModalSubtitle" class="text-xs text-gray-400 mt-0.5">Event details and company participants</p></div>'
        + '</div>'
        + '<button id="jobFairModalClose" type="button" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-400 hover:text-gray-600">'
        + '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
        + '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></div>'
        + '<div id="jfModalBody" class="flex-1 overflow-y-auto px-8 py-6 flex flex-col md:flex-row gap-8">'
        + '<section id="jfEventSection" class="flex-1 w-full space-y-4">'
        + '<div class="flex items-center gap-2"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">1</span>'
        + '<h4 class="text-sm font-bold text-gray-800">Event Details</h4></div>'
        + '<div><label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Job Fair Type <span class="text-red-400">*</span></label>'
        + '<select id="jfType" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">'
        + '<option value="LOCAL JOB FAIR">Local Job Fair</option><option value="OVERSEAS JOB FAIR">Overseas Job Fair</option></select></div>'
        + '<div><label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Venue (Event Name) <span class="text-red-400">*</span></label>'
        + '<input id="jfVenue" type="text" placeholder="e.g. Bulwagang Lapu-lapu, Camp BGen Rafael T. Crame" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></div>'
        + '<div><label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Event Date <span class="text-red-400">*</span></label>'
        + '<input id="jfEventDate" type="date" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></div>'
        + '</section>'
        + '<section id="jfParticipantsSection" class="flex-1 w-full space-y-4">'
        + '<div class="flex items-center gap-2"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">2</span>'
        + '<h4 class="text-sm font-bold text-gray-800">Company Participants</h4></div>'
        + '<p class="text-sm text-gray-500">Search and add companies from the employer registry. At least one is required.</p>'
        + '<div id="jfImportSeedPanel" class="hidden rounded-xl border border-blue-100 bg-blue-50/40 p-4 space-y-3 max-h-72 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-200">'
        + '<div class="flex items-center gap-2"><span class="text-xs font-bold uppercase tracking-wider text-blue-700">Imported Matches</span>'
        + '<span class="text-[11px] text-blue-600">Auto-detected from the uploaded Job Fair file</span></div>'
        + '<div id="jfImportSeedList" class="space-y-1"></div>'
        + '<div id="jfImportUnmatchedWrap" class="hidden pt-2 border-t border-blue-100">'
        + '<p class="text-xs font-semibold text-amber-700 uppercase tracking-wider mb-2">Not detected</p>'
        + '<div id="jfImportUnmatchedList" class="flex flex-wrap gap-2"></div></div></div>'
        + '<div class="relative"><svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
        + '<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>'
        + '<input id="jfCompanySearch" type="text" placeholder="Search companies…" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></div>'
        + '<div id="jfSearchResults" class="hidden max-h-36 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-md divide-y divide-gray-50"></div>'
        + '<div><p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Added Companies</p>'
        + '<div id="jfAddedList" class="space-y-1.5 min-h-[60px]"><p class="text-sm text-gray-400 italic py-3 text-center">No companies added yet.</p></div></div>'
        + '</section></div>'
        + '<div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex-shrink-0">'
        + '<button id="jfCancelBtn" type="button" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>'
        + '<button id="jfSaveBtn" type="button" disabled class="flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">'
        + SAVE_BTN_HTML + '</button></div></div>';
}

function ensureJobFairModal() {
    let modal = document.getElementById('jobFairCreateModal');
    if (modal) return modal;

    modal = document.createElement('div');
    modal.id = 'jobFairCreateModal';
    modal.className = 'fixed inset-0 z-[60] hidden items-center justify-center p-4';
    modal.innerHTML = buildModalDom();
    document.body.appendChild(modal);
    return modal;
}

export function openCreateEventModal(onDone, seedData = {}) {
    _onDoneCallback = onDone;
    _resetState('create');
    _companyNameMap = {};
    _activeSearchOriginal = null;
    _importSeedCompanies = Array.isArray(seedData.importedCompanies) && seedData.importedCompanies.length
        ? seedData.importedCompanies
        : Array.isArray(state.jobFairImportedCompanies)
            ? state.jobFairImportedCompanies
            : [];
    _importUnmatchedCompanies = Array.isArray(seedData.unmatchedCompanies) && seedData.unmatchedCompanies.length
        ? seedData.unmatchedCompanies
        : Array.isArray(state.jobFairUnmatchedCompanies)
            ? state.jobFairUnmatchedCompanies
            : [];
    const modal = ensureJobFairModal();
    _bindModalEvents(modal);
    _configureModal(modal);
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    if (_importSeedCompanies.length > 0 || _importUnmatchedCompanies.length > 0) {
        _seedImportedCompanies(modal, _importSeedCompanies, _importUnmatchedCompanies);
    } else {
        _renderImportedSeedPanel(modal, [], []);
    }
}

export function openAddCompaniesModal(eventId, eventLabel, onDone, seedData = {}) {
    _onDoneCallback = onDone;
    _resetState('participants', parseInt(eventId, 10));
    _companyNameMap = {};
    _activeSearchOriginal = null;
    _importSeedCompanies = Array.isArray(seedData.importedCompanies) && seedData.importedCompanies.length
        ? seedData.importedCompanies
        : Array.isArray(state.jobFairImportedCompanies)
            ? state.jobFairImportedCompanies
            : [];
    _importUnmatchedCompanies = Array.isArray(seedData.unmatchedCompanies) && seedData.unmatchedCompanies.length
        ? seedData.unmatchedCompanies
        : Array.isArray(state.jobFairUnmatchedCompanies)
            ? state.jobFairUnmatchedCompanies
            : [];
    const modal = ensureJobFairModal();
    _bindModalEvents(modal);
    _loadExistingParticipants(modal, eventId, () => {
        _configureModal(modal, eventLabel);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if (_importSeedCompanies.length > 0 || _importUnmatchedCompanies.length > 0) {
            _seedImportedCompanies(modal, _importSeedCompanies, _importUnmatchedCompanies);
        } else {
            _renderImportedSeedPanel(modal, [], []);
        }
    });
}

function _resetState(mode, eventId = null) {
    _modalMode = mode;
    _eventId = eventId;
    _eventDate = null;
    _addedCompanies = [];
    _importSeedCompanies = [];
    _importUnmatchedCompanies = [];
}

function _configureModal(modal, eventLabel = '') {
    const isParticipantsOnly = _modalMode === 'participants';
    const titleEl = modal.querySelector('#jobFairModalTitle');
    const subtitleEl = modal.querySelector('#jobFairModalSubtitle');
    const eventSection = modal.querySelector('#jfEventSection');
    const saveBtn = modal.querySelector('#jfSaveBtn');

    if (titleEl) {
        titleEl.textContent = isParticipantsOnly
            ? 'Add Companies — ' + (eventLabel || 'Job Fair Event')
            : 'Create New Job Fair Event';
    }
    if (subtitleEl) {
        subtitleEl.textContent = isParticipantsOnly
            ? 'Add participating companies for this event'
            : 'Fill in event details and add company participants';
    }
    if (eventSection) {
        eventSection.classList.toggle('hidden', isParticipantsOnly);
    }
    
    const wrapper = modal.querySelector('#jobFairModalWrapper');
    if (wrapper) {
        wrapper.classList.toggle('max-w-5xl', !isParticipantsOnly);
        wrapper.classList.toggle('max-w-lg', isParticipantsOnly);
    }
    
    const body = modal.querySelector('#jfModalBody');
    if (body) {
        body.classList.toggle('md:flex-row', !isParticipantsOnly);
        body.classList.toggle('flex-col', isParticipantsOnly);
    }
    
    const participantsSection = modal.querySelector('#jfParticipantsSection');
    if (participantsSection) {
        participantsSection.classList.toggle('md:border-l', !isParticipantsOnly);
        participantsSection.classList.toggle('md:pl-8', !isParticipantsOnly);
        participantsSection.classList.toggle('border-gray-100', !isParticipantsOnly);
    }
    if (saveBtn) {
        saveBtn.innerHTML = isParticipantsOnly
            ? '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Save Participants'
            : SAVE_BTN_HTML;
    }

    if (!isParticipantsOnly) {
        const typeEl = modal.querySelector('#jfType');
        const venueEl = modal.querySelector('#jfVenue');
        const dateEl = modal.querySelector('#jfEventDate');
        if (typeEl) typeEl.value = 'LOCAL JOB FAIR';
        if (venueEl) venueEl.value = '';
        if (dateEl) dateEl.value = '';
    }

    const searchEl = modal.querySelector('#jfCompanySearch');
    const resultsEl = modal.querySelector('#jfSearchResults');
    if (searchEl) searchEl.value = '';
    if (resultsEl) resultsEl.classList.add('hidden');
    _renderAddedList(modal);
    _renderImportedSeedPanel(modal, _importSeedCompanies, _importUnmatchedCompanies);
}

function normalizeCompanyName(name) {
    return String(name ?? '').trim().toLowerCase().replace(/\s+/g, ' ');
}

async function _seedImportedCompanies(modal, importedCompanies, unmatchedCompanies) {
    const uniqueNames = Array.from(new Set(
        [...importedCompanies, ...unmatchedCompanies].map(name => String(name ?? '').trim()).filter(Boolean)
    ));

    const resolved = [];
    let unresolved = [];

    if (uniqueNames.length > 0) {
        try {
            const res = await fetch('../../backend/import/fuzzy_match_companies.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ companies: uniqueNames })
            });
            const data = await res.json();

            if (data.success && Array.isArray(data.results)) {
                for (const item of data.results) {
                    if (item.exact_match) {
                        const { company_id, company_name } = item.exact_match;
                        if (!_addedCompanies.find(c => String(c.company_id) === String(company_id))) {
                            _addedCompanies.push({ company_id, company_name });
                        }
                        resolved.push({ company_id, company_name, source_name: item.original });
                    } else {
                        unresolved.push(item);
                    }
                }
            } else {
                unresolved = uniqueNames.map(name => ({ original: name, exact_match: null, fuzzy_match: null }));
            }
        } catch {
            unresolved = uniqueNames.map(name => ({ original: name, exact_match: null, fuzzy_match: null }));
        }
    }

    _importSeedCompanies = resolved.map(item => item.company_name);
    _importUnmatchedCompanies = unresolved;
    _renderAddedList(modal);
    _renderImportedSeedPanel(modal, _importSeedCompanies, _importUnmatchedCompanies);
}

function _renderImportedSeedPanel(modal, seedCompanies = [], unmatchedCompanies = []) {
    const panel = modal.querySelector('#jfImportSeedPanel');
    const seedList = modal.querySelector('#jfImportSeedList');
    const unmatchedWrap = modal.querySelector('#jfImportUnmatchedWrap');
    const unmatchedList = modal.querySelector('#jfImportUnmatchedList');

    if (!panel || !seedList || !unmatchedWrap || !unmatchedList) return;

    if (seedCompanies.length === 0 && unmatchedCompanies.length === 0) {
        panel.classList.add('hidden');
        return;
    }

    const hasSeeds = Array.isArray(seedCompanies) && seedCompanies.length > 0;
    const hasUnmatched = Array.isArray(unmatchedCompanies) && unmatchedCompanies.length > 0;

    panel.classList.toggle('hidden', !hasSeeds && !hasUnmatched);

    seedList.innerHTML = hasSeeds
        ? seedCompanies.map(name => `
            <div class="flex items-center justify-between gap-3 rounded-lg border border-blue-100 bg-white px-3 py-2">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-[11px] font-bold text-blue-700">✓</span>
                    <span class="text-sm font-medium text-gray-800 truncate">${esc(name)}</span>
                </div>
            </div>`).join('')
        : '<p class="text-sm text-blue-700/80 italic">No imported companies were auto-detected.</p>';

    unmatchedWrap.classList.toggle('hidden', !hasUnmatched);
    unmatchedList.innerHTML = hasUnmatched
        ? unmatchedCompanies.map(item => {
            const originalName = typeof item === 'string' ? item : item.original;
            const suggestion = (typeof item === 'object' && item.fuzzy_match) ? item.fuzzy_match : null;

            if (suggestion) {
                return `
                <div class="jf-import-unmatched-card flex items-center justify-between gap-3 w-full bg-amber-50/50 border border-amber-200 rounded-lg p-2.5">
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs text-amber-800/70 truncate line-through decoration-amber-500/40">${esc(originalName)}</span>
                        <span class="text-sm font-semibold text-amber-900 truncate">Did you mean <span class="text-blue-700">${esc(suggestion.company_name)}</span>?</span>
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button type="button" class="jf-accept-suggestion px-2.5 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-bold rounded-md transition-colors" data-id="${esc(suggestion.company_id)}" data-name="${esc(suggestion.company_name)}" data-original="${esc(originalName)}">
                            Accept
                        </button>
                        <button type="button" class="jf-search-unmatched p-1.5 hover:bg-amber-100 text-amber-600 rounded-md transition-colors" data-name="${esc(originalName)}" title="Search manually">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                        <button type="button" class="jf-dismiss-unmatched p-1.5 hover:bg-red-100 text-red-500 rounded-md transition-colors" data-original="${esc(originalName)}" title="Dismiss">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                </div>`;
            } else {
                return `
                <div class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 pl-3 pr-1 py-1">
                    <button type="button" class="jf-search-unmatched flex items-center gap-1.5 text-xs font-semibold text-amber-800 hover:text-amber-900 transition-colors" data-name="${esc(originalName)}" title="Search this company">
                        <span class="max-w-[120px] truncate">${esc(originalName)}</span>
                        <span class="text-amber-600">Search</span>
                    </button>
                    <div class="w-px h-3.5 bg-amber-200 mx-0.5"></div>
                    <button type="button" class="jf-dismiss-unmatched p-1 hover:bg-amber-200 text-amber-600 hover:text-amber-800 rounded-full transition-colors" data-original="${esc(originalName)}" title="Dismiss">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>`;
            }
        }).join('')
        : '';

    // Bind accept buttons
    unmatchedList.querySelectorAll('.jf-accept-suggestion').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const original = btn.getAttribute('data-original');

            if (!_addedCompanies.find(c => String(c.company_id) === String(id))) {
                _addedCompanies.push({ company_id: id, company_name: name });
            }

            _importUnmatchedCompanies = _importUnmatchedCompanies.filter(item => {
                const itemOriginal = typeof item === 'string' ? item : item.original;
                return itemOriginal !== original;
            });

            if (!_importSeedCompanies.includes(name)) {
                _importSeedCompanies.push(name);
            }
            
            _companyNameMap[original] = name;

            _renderAddedList(modal);
            _renderImportedSeedPanel(modal, _importSeedCompanies, _importUnmatchedCompanies);
        });
    });

    unmatchedList.querySelectorAll('.jf-search-unmatched').forEach(btn => {
        btn.addEventListener('click', () => {
            const search = modal.querySelector('#jfCompanySearch');
            if (!search) return;
            const name = btn.getAttribute('data-name') || '';
            _activeSearchOriginal = btn.getAttribute('data-original') || name;
            search.value = name;
            _searchCompanies(modal, name);
            search.focus();
        });
    });

    unmatchedList.querySelectorAll('.jf-dismiss-unmatched').forEach(btn => {
        btn.addEventListener('click', () => {
            const original = btn.getAttribute('data-original');
            _importUnmatchedCompanies = _importUnmatchedCompanies.filter(item => {
                const itemOriginal = typeof item === 'string' ? item : item.original;
                return itemOriginal !== original;
            });
            _renderImportedSeedPanel(modal, _importSeedCompanies, _importUnmatchedCompanies);
        });
    });
}

function _closeModal() {
    const modal = document.getElementById('jobFairCreateModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function _bindModalEvents(modal) {
    if (_eventsBound) return;
    _eventsBound = true;

    modal.querySelector('#jobFairModalBackdrop')?.addEventListener('click', _closeModal);
    modal.querySelector('#jobFairModalClose')?.addEventListener('click', _closeModal);
    modal.querySelector('#jfCancelBtn')?.addEventListener('click', _closeModal);
    modal.querySelector('#jfSaveBtn')?.addEventListener('click', () => _handleSave(modal));

    const searchInput = modal.querySelector('#jfCompanySearch');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(_searchDebounce);
            _searchDebounce = setTimeout(() => _searchCompanies(modal, searchInput.value), 280);
        });
        searchInput.addEventListener('blur', () => {
            setTimeout(() => modal.querySelector('#jfSearchResults')?.classList.add('hidden'), 200);
        });
    }
}

async function _loadExistingParticipants(modal, eventId, cb) {
    try {
        const res = await fetch('../../backend/import/get_event_participants.php?event_id=' + eventId);
        const data = await res.json();
        if (data.success) {
            _addedCompanies = data.participants.map(p => ({
                company_id: p.company_id,
                company_name: p.company_name,
            }));
        }
    } catch (_) {}
    cb?.();
}

async function _searchCompanies(modal, query) {
    const resultsEl = modal.querySelector('#jfSearchResults');
    if (!resultsEl) return;
    if (!query.trim()) {
        resultsEl.classList.add('hidden');
        return;
    }

    try {
        const res = await fetch('../../backend/import/search_companies.php?q=' + encodeURIComponent(query));
        const data = await res.json();

        if (!data.success || !data.companies.length) {
            resultsEl.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">No companies found.</div>';
        } else {
            const addedIds = new Set(_addedCompanies.map(c => String(c.company_id)));
            resultsEl.innerHTML = data.companies.map(c => {
                const alreadyAdded = addedIds.has(String(c.company_id));
                return '<button type="button" class="jf-company-result w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 transition-colors flex items-center justify-between gap-2'
                    + (alreadyAdded ? ' opacity-40 cursor-not-allowed' : '')
                    + '" data-id="' + esc(c.company_id) + '" data-name="' + esc(c.company_name) + '"'
                    + (alreadyAdded ? ' disabled' : '') + '>'
                    + '<span>' + esc(c.company_name) + '</span>'
                    + (alreadyAdded ? '<span class="text-xs text-gray-400">Added</span>' : '<span class="text-xs text-blue-500 font-medium">+ Add</span>')
                    + '</button>';
            }).join('');
        }

        resultsEl.classList.remove('hidden');
        resultsEl.querySelectorAll('.jf-company-result:not([disabled])').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                if (!_addedCompanies.find(c => String(c.company_id) === String(id))) {
                    _addedCompanies.push({ company_id: id, company_name: name });
                    _renderAddedList(modal);
                }
                
                if (_activeSearchOriginal) {
                    _companyNameMap[_activeSearchOriginal] = name;
                    _importUnmatchedCompanies = _importUnmatchedCompanies.filter(item => {
                        const itemOriginal = typeof item === 'string' ? item : item.original;
                        return itemOriginal !== _activeSearchOriginal;
                    });
                    _renderImportedSeedPanel(modal, _importSeedCompanies, _importUnmatchedCompanies);
                    _activeSearchOriginal = null;
                }

                resultsEl.classList.add('hidden');
                modal.querySelector('#jfCompanySearch').value = '';
            });
        });
    } catch (_) {
        resultsEl.innerHTML = '<div class="px-4 py-3 text-sm text-red-400">Error searching companies.</div>';
        resultsEl.classList.remove('hidden');
    }
}

function _renderAddedList(modal) {
    const listEl = modal.querySelector('#jfAddedList');
    const saveBtn = modal.querySelector('#jfSaveBtn');
    if (!listEl) return;

    if (_addedCompanies.length === 0) {
        listEl.innerHTML = '<p class="text-sm text-gray-400 italic py-3 text-center">No companies added yet.</p>';
        if (saveBtn) saveBtn.disabled = true;
        return;
    }

    if (saveBtn) saveBtn.disabled = false;
    listEl.innerHTML = _addedCompanies.map((c, idx) => ''
        + '<div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg px-3 py-2">'
        + '<div class="flex items-center gap-2">'
        + '<div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">'
        + '<svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
        + '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'
        + '</svg></div>'
        + '<span class="text-sm font-medium text-gray-800">' + esc(c.company_name) + '</span>'
        + '</div>'
        + '<button type="button" class="jf-remove-company p-1.5 hover:bg-red-50 rounded-lg transition-colors group" data-idx="' + idx + '">'
        + '<svg class="w-4 h-4 text-gray-400 group-hover:text-red-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
        + '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'
        + '</svg></button></div>'
    ).join('');

    listEl.querySelectorAll('.jf-remove-company').forEach(btn => {
        btn.addEventListener('click', () => {
            _addedCompanies.splice(parseInt(btn.dataset.idx, 10), 1);
            _renderAddedList(modal);
        });
    });
}

async function _handleSave(modal) {
    const isParticipantsOnly = _modalMode === 'participants';
    const type = modal.querySelector('#jfType')?.value.trim() ?? '';
    const venue = modal.querySelector('#jfVenue')?.value.trim() ?? '';
    const date = modal.querySelector('#jfEventDate')?.value.trim() ?? '';

    if (!isParticipantsOnly) {
        if (!type) { showToast('Please select a job fair type.', 'warning'); return; }
        if (!venue) { showToast('Please enter a venue.', 'warning'); return; }
        if (!date) { showToast('Please select an event date.', 'warning'); return; }
    }

    if (_addedCompanies.length === 0) {
        showToast('Add at least one company participant.', 'warning');
        return;
    }

    if (_importUnmatchedCompanies && _importUnmatchedCompanies.length > 0) {
        showToast('Please resolve all "Not detected" companies before saving. You can search for them manually or accept suggestions.', 'warning');
        return;
    }

    const saveBtn = modal.querySelector('#jfSaveBtn');

    const payload = {
        company_ids: _addedCompanies.map(c => c.company_id),
    };
    if (isParticipantsOnly && _eventId) {
        payload.event_id = _eventId;
    } else {
        payload.job_fair_type = type;
        payload.venue = venue;
        payload.date_start = date;
    }

    try {
        await runWithButtonLoading(saveBtn, async () => {
            const res = await fetch('../../backend/import/save_job_fair_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error ?? 'Unknown error');

            _eventId = data.jobfairevent_id;
            _eventDate = data.date_start ?? date;

            showToast(
                isParticipantsOnly
                    ? (data.participants?.length ?? _addedCompanies.length) + ' company participant(s) saved!'
                    : 'Job fair event and participants saved!',
                'success'
            );
            _closeModal();
            _onDoneCallback?.({
                eventId: _eventId,
                eventDate: _eventDate,
                participants: data.participants ?? _addedCompanies,
                companyMapping: _companyNameMap
            });
        }, { label: 'Saving…' });
    } catch (err) {
        showToast('Failed to save: ' + err.message, 'error');
        if (saveBtn && _addedCompanies.length === 0) saveBtn.disabled = true;
    }
}

export function setParticipantsWarning(eventId, eventLabel) {
    let warningEl = document.getElementById('jfNoParticipantsWarning');

    if (!eventId) {
        warningEl?.remove();
        return;
    }

    if (!warningEl) {
        warningEl = document.createElement('div');
        warningEl.id = 'jfNoParticipantsWarning';
        warningEl.className = 'hidden mt-3 flex gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3';
        const wrapper = document.getElementById('jobFairEventWrapper');
        if (wrapper) wrapper.insertAdjacentElement('afterend', warningEl);
    }

    warningEl.innerHTML = ''
        + '<svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="currentColor">'
        + '<path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>'
        + '<div class="flex-1">'
        + '<p class="text-sm font-semibold text-amber-800 mb-0.5">No companies registered for this event.</p>'
        + '<p class="text-xs text-amber-700">Import requires at least one company participant. Add companies to this event before proceeding.</p>'
        + '<button id="jfAddCompaniesLink" type="button" class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">'
        + '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>'
        + 'Add Companies to This Event</button></div>';

    warningEl.classList.remove('hidden');
    showToast('Import requires at least one company participant. Add companies to this event before proceeding.', 'warning');

    document.getElementById('dataPreview')?.classList.add('hidden');

    const link = warningEl.querySelector('#jfAddCompaniesLink');
    if (link) {
        link.onclick = () => {
            openAddCompaniesModal(eventId, eventLabel, ({ participants, companyMapping }) => {
                if (participants && participants.length > 0) {
                    if (companyMapping) state.jobFairCompanyMapping = companyMapping;
                    warningEl.classList.add('hidden');
                    document.dispatchEvent(new CustomEvent('jfParticipantsResolved', { detail: { eventId } }));
                }
            }, {
                importedCompanies: Array.isArray(state.jobFairImportedCompanies) ? state.jobFairImportedCompanies : [],
                unmatchedCompanies: Array.isArray(state.jobFairUnmatchedCompanies) ? state.jobFairUnmatchedCompanies : [],
            });
        };
    }
}

export function hideParticipantsWarning() {
    document.getElementById('jfNoParticipantsWarning')?.classList.add('hidden');
}
