/**
 * Handles beneficiary documents loaded from docs_benef, the Drive modal, and the inline Drive form.
 */

const DOCS_FIELD_META = {
  proof_of_residency: { label: 'Proof of Residency', icon: 'PDF' },
  latest_credential:   { label: 'Latest Credential', icon: 'PDF' },
  letter_of_intent:    { label: 'Letter of Intent', icon: 'PDF' },
  reco_letter:         { label: 'Recommendation Letter', icon: 'PDF' },
  resume:              { label: 'Resume', icon: 'PDF' },
  tor:                 { label: 'Transcript of Records', icon: 'PDF' },
  brgy_clearance:      { label: 'Barangay Clearance', icon: 'PDF' },
  nbi_clearance:       { label: 'NBI Clearance', icon: 'PDF' },
  birth_cert:          { label: 'Birth Certificate', icon: 'PDF' },
  tesda_cert:          { label: 'TESDA Certificate', icon: 'PDF' },
};

let currentDocuments = [];
let currentBeneficiaryName = '';
let currentDocumentIndex = -1;

// ─── Document Selection ───────────────────────────────────

/** Highlight a doc-item and populate the details panel. */
function selectDoc(el, name, type, size, date) {
  document.querySelectorAll('.doc-item').forEach(d => d.classList.remove('selected'));
  el.classList.add('selected');
  currentDocumentIndex = Number(el?.dataset?.docIndex ?? -1);

  document.getElementById('detailName').textContent = name;
  document.getElementById('detailType').textContent = type;
}

function _getDocActionsTarget() {
  if (currentDocumentIndex < 0 || currentDocumentIndex >= currentDocuments.length) {
    return null;
  }
  return currentDocuments[currentDocumentIndex];
}

function _openCurrentDoc(doc) {
  if (doc?.url) {
    window.open(doc.url, '_blank', 'noopener,noreferrer');
  }
}

function _findDocByElement(el) {
  const item = el?.closest('.doc-item');
  if (!item) return null;

  const index = Number(item.dataset.docIndex ?? -1);
  if (Number.isFinite(index) && index >= 0 && index < currentDocuments.length) {
    return currentDocuments[index];
  }

  const fallbackUrl = item.dataset.docUrl || '';
  const fallbackName = item.dataset.docName || '';
  return fallbackUrl ? { url: fallbackUrl, name: fallbackName } : null;
}

function _setEmptyDocumentsState(message) {
  const listEl = document.getElementById('docListItems');
  if (listEl) {
    listEl.innerHTML = `<div class="doc-empty-state" style="padding:16px;color:var(--text-muted);text-align:center;">${message}</div>`;
  }

  const detailName = document.getElementById('detailName');
  const detailType = document.getElementById('detailType');
  const driveUrlEl = document.querySelector('.drive-url span');
  const openBtn = document.querySelector('.open-drive-btn');

  if (detailName) detailName.textContent = 'No document selected';
  if (detailType) detailType.textContent = '—';
  if (driveUrlEl) driveUrlEl.textContent = '—';
  if (openBtn) openBtn.onclick = null;
}

const docListItemsEl = document.getElementById('docListItems');
if (docListItemsEl) {
  docListItemsEl.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-doc-action]');
    if (!button) return;

    const action = button.getAttribute('data-doc-action');
    const doc = _findDocByElement(button);
    if (!doc?.url) return;

    event.preventDefault();
    event.stopPropagation();

    const item = button.closest('.doc-item');
    if (item) {
      document.querySelectorAll('.doc-item').forEach(d => d.classList.remove('selected'));
      item.classList.add('selected');
      const index = Number(item.dataset.docIndex ?? -1);
      if (Number.isFinite(index) && index >= 0) {
        currentDocumentIndex = index;
      }
    }

    if (action === 'open') {
      _openCurrentDoc(doc);
      return;
    }
  });
}

function _renderDocuments(docs = []) {
  currentDocuments = Array.isArray(docs) ? docs : [];
  const listEl = document.getElementById('docListItems');
  if (!listEl) return;

  const beneficiaryLabel = currentBeneficiaryName || document.getElementById('profName')?.textContent?.trim() || 'Beneficiary';
  currentBeneficiaryName = beneficiaryLabel;

  if (currentDocuments.length === 0) {
    _setEmptyDocumentsState(`No documents found for ${beneficiaryLabel}.`);
    return;
  }

  listEl.innerHTML = currentDocuments.map((doc, index) => {
    const thumbCls = 'pdf';
    const selectedCls = index === 0 ? ' selected' : '';
    const metaLabel = doc.label || DOCS_FIELD_META[doc.field]?.label || doc.field;
    return `
      <div class="doc-item${selectedCls}" data-doc-index="${index}" data-doc-url="${doc.url || ''}" data-doc-name="${doc.name || ''}">
        <div class="doc-thumb ${thumbCls}"><span>FILE</span></div>
        <div style="flex:1;min-width:0;">
          <div class="doc-name">${doc.name || `${beneficiaryLabel} - ${metaLabel}`}</div>
          <div class="doc-meta">${metaLabel}</div>
        </div>
        <div class="doc-actions">
          <button class="doc-action-btn" type="button" data-doc-action="open" aria-label="Open link">↗</button>
        </div>
      </div>`;
  }).join('');

  listEl.querySelectorAll('.doc-item').forEach((item, index) => {
    if (index === 0 && currentDocumentIndex < 0) {
      currentDocumentIndex = 0;
    }
    item.addEventListener('click', () => {
      const doc = currentDocuments[index];
      if (!doc) return;

      document.querySelectorAll('.doc-item').forEach(d => d.classList.remove('selected'));
      item.classList.add('selected');
      currentDocumentIndex = index;

      const metaLabel = doc.label || DOCS_FIELD_META[doc.field]?.label || doc.field;
      const driveUrlEl = document.querySelector('.drive-url span');
      const openBtn = document.querySelector('.open-drive-btn');

      document.getElementById('detailName').textContent = doc.name || `${beneficiaryLabel} - ${metaLabel}`;
      document.getElementById('detailType').textContent = 'FILE';

      if (driveUrlEl) driveUrlEl.textContent = doc.url || '—';
      if (openBtn) openBtn.onclick = () => _openCurrentDoc(doc);
    });
  });

  const first = currentDocuments[0];
  if (first) {
    const metaLabel = first.label || DOCS_FIELD_META[first.field]?.label || first.field;
    document.getElementById('detailName').textContent = first.name || `${beneficiaryLabel} - ${metaLabel}`;
    document.getElementById('detailType').textContent = 'FILE';

    const driveUrlEl = document.querySelector('.drive-url span');
    const openBtn = document.querySelector('.open-drive-btn');
    currentDocumentIndex = 0;
    if (driveUrlEl) driveUrlEl.textContent = first.url || '—';
    if (openBtn) openBtn.onclick = () => _openCurrentDoc(first);
  }
}

async function loadBeneficiaryDocuments(benefId, beneficiaryName = '') {
  currentBeneficiaryName = beneficiaryName || document.getElementById('profName')?.textContent?.trim() || '';
  const listEl = document.getElementById('docListItems');
  if (!listEl) return;

  listEl.innerHTML = '<div class="doc-empty-state" style="padding:16px;color:var(--text-muted);text-align:center;">Loading documents…</div>';

  try {
    const res = await fetch(`../../backend/beneficiaries/get_documents.php?id=${encodeURIComponent(benefId)}`);
    const json = await res.json();

    if (!json.success) {
      _setEmptyDocumentsState(json.message || 'Unable to load documents.');
      return;
    }

    currentBeneficiaryName = json.beneficiary?.name || beneficiaryName || document.getElementById('profName')?.textContent?.trim() || '';
    _renderDocuments(Array.isArray(json.documents) ? json.documents : []);
  } catch (err) {
    console.error('[documents.js] Failed to load beneficiary documents:', err);
    _setEmptyDocumentsState('Unable to load documents.');
  }
}

// ─── Helpers ─────────────────────────────────────────────

/** Build a doc-item element and append it to #docListItems. */
function _appendDocItem(name, url) {
  const type = 'FILE';
  const thumbCls = 'pdf';
  const today = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

  const item = document.createElement('div');
  item.className = 'doc-item';
  item.dataset.docIndex = String(currentDocuments.length);
  item.dataset.docUrl = url;
  item.dataset.docName = name;
  item.setAttribute('onclick', `selectDoc(this,'${name}','${type}','— KB','${today}')`);
  item.innerHTML = `
    <div class="doc-thumb ${thumbCls}"><span>${type}</span></div>
    <div style="flex:1;min-width:0;">
      <div class="doc-name">${name}</div>
      <div class="doc-meta">— KB · ${today}</div>
    </div>
    <div class="doc-actions">
      <button class="doc-action-btn" type="button" data-doc-action="open" aria-label="Open link">↗</button>
    </div>`;

  // Wire up Drive link on the details panel when this item is clicked
  item.addEventListener('click', () => {
    currentDocumentIndex = Array.from(document.querySelectorAll('#docListItems .doc-item')).indexOf(item);
    document.getElementById('detailName').textContent = name;
    document.getElementById('detailType').textContent = type;

    const driveUrlEl = document.querySelector('.drive-url span');
    if (driveUrlEl) driveUrlEl.textContent = url;

    const openBtn = document.querySelector('.open-drive-btn');
    if (openBtn) openBtn.onclick = () => _openCurrentDoc({ url });
  });

  document.getElementById('docListItems').appendChild(item);
}

/** Validate that a required input is not empty; briefly highlight if blank. */
function _requireInput(id) {
  const el = document.getElementById(id);
  if (!el.value.trim()) {
    el.focus();
    el.style.borderColor = '#ef4444';
    setTimeout(() => { el.style.borderColor = ''; }, 1500);
    return false;
  }
  return true;
}

// ─── Drive Modal ──────────────────────────────────────────

/** Refresh the modal's doc-list preview to mirror #docListItems. */
function refreshModalPreview() {
  const items   = document.querySelectorAll('#docListItems .doc-item');
  const preview = document.getElementById('driveModalPreview');
  let html = '';

  items.forEach(item => {
    const nameEl  = item.querySelector('.doc-name');
    const metaEl  = item.querySelector('.doc-meta');
    const thumbEl = item.querySelector('.doc-thumb');
    if (!nameEl) return;

    const thumbCls  = thumbEl && thumbEl.classList.contains('img') ? 'img' : 'pdf';
    const thumbText = thumbEl ? thumbEl.innerText.trim() : 'FILE';

    html += `
      <div class="doc-item" style="pointer-events:none;">
        <div class="doc-thumb ${thumbCls}"><span>${thumbText}</span></div>
        <div style="flex:1;min-width:0;">
          <div class="doc-name">${nameEl.innerHTML}</div>
          <div class="doc-meta">${metaEl ? metaEl.textContent : ''}</div>
        </div>
        <div class="doc-actions">
          <button class="doc-action-btn">↗</button>
        </div>
      </div>`;
  });

  preview.innerHTML = html;
}

function openDriveModal() {
  refreshModalPreview();
  const overlay = document.getElementById('driveModalOverlay');
  const modal   = document.getElementById('driveModal');
  overlay.style.display = 'flex';

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      modal.style.transform = 'translateY(0)';
      modal.style.opacity   = '1';
    });
  });

  setTimeout(() => { document.getElementById('driveFileName').focus(); }, 250);
}

function closeDriveModal() {
  const overlay = document.getElementById('driveModalOverlay');
  const modal   = document.getElementById('driveModal');

  modal.style.transform = 'translateY(-14px)';
  modal.style.opacity   = '0';

  setTimeout(() => {
    overlay.style.display = 'none';
    document.getElementById('driveFileName').value = '';
    document.getElementById('driveLinkUrl').value  = '';
  }, 220);
}

function handleOverlayClick(e) {
  if (e.target === document.getElementById('driveModalOverlay')) {
    closeDriveModal();
  }
}

function submitDriveLink() {
  if (!_requireInput('driveFileName') || !_requireInput('driveLinkUrl')) return;

  const name = document.getElementById('driveFileName').value.trim();
  const url  = document.getElementById('driveLinkUrl').value.trim();

  _appendDocItem(name, url);
  closeDriveModal();
}

// ─── Inline Drive Form ────────────────────────────────────

function openInlineDriveForm() {
  const form = document.getElementById('inlineDriveForm');
  if (!form) return;
  form.style.display = 'block';
  form.setAttribute('aria-hidden', 'false');
  setTimeout(() => {
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    document.getElementById('inlineDriveFileName').focus();
  }, 120);
}

function closeInlineDriveForm() {
  const form = document.getElementById('inlineDriveForm');
  if (!form) return;
  form.style.display = 'none';
  form.setAttribute('aria-hidden', 'true');
  document.getElementById('inlineDriveFileName').value = '';
  document.getElementById('inlineDriveLinkUrl').value  = '';
}

function submitInlineDriveLink() {
  if (!_requireInput('inlineDriveFileName') || !_requireInput('inlineDriveLinkUrl')) return;

  const name = document.getElementById('inlineDriveFileName').value.trim();
  const url  = document.getElementById('inlineDriveLinkUrl').value.trim();

  _appendDocItem(name, url);
  closeInlineDriveForm();
}

window.selectDoc = selectDoc;
window.openInlineDriveForm = openInlineDriveForm;
window.closeInlineDriveForm = closeInlineDriveForm;
window.submitInlineDriveLink = submitInlineDriveLink;
window.loadBeneficiaryDocuments = loadBeneficiaryDocuments;

// ─── Edit Drive Link Modal ────────────────────────────────

function _showEditDriveToast(message, type) {
  if (typeof window.showToast === 'function') {
    window.showToast(message, type);
    return;
  }
  if (type === 'error') console.error(message);
  else console.log(message);
}

function openEditDriveModal() {
  const doc = _getDocActionsTarget();
  if (!doc) {
    _showEditDriveToast('No document selected.', 'warning');
    return;
  }

  document.getElementById('editDriveFileName').value = doc.name || '';
  document.getElementById('editDriveLink').value = doc.url || '';
  
  const modal = document.getElementById('modalEditDrive');
  if (modal) {
    modal.style.display = 'flex';
    setTimeout(() => { document.getElementById('editDriveFileName').focus(); }, 100);
  }
}

function closeEditDriveModal() {
  const modal = document.getElementById('modalEditDrive');
  if (modal) {
    modal.style.display = 'none';
  }
}

function submitEditDrive() {
  if (!_requireInput('editDriveLink')) return;

  const doc = _getDocActionsTarget();
  if (!doc) {
    _showEditDriveToast('No document selected.', 'error');
    closeEditDriveModal();
    return;
  }

  const url = document.getElementById('editDriveLink').value.trim();

  // Validate URL
  if (!url) {
    _showEditDriveToast('Google Drive link is required.', 'error');
    return;
  }

  // Post to backend to update database
  const benef_id = window.currentBeneficiaryId;
  if (!benef_id) {
    _showEditDriveToast('No beneficiary selected.', 'error');
    return;
  }

  fetch(`../../backend/beneficiaries/update_document.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      benef_id: benef_id,
      field: doc.field,
      doc_url: url
    })
  })
  .then(r => r.json())
  .then(j => {
    if (j && j.success) {
      // Update the document object
      doc.url = url;

      // Update the DOM
      const item = document.querySelector(`.doc-item[data-doc-index="${currentDocumentIndex}"]`);
      if (item) {
        item.dataset.docUrl = url;
      }

      // Update the details panel
      const driveUrlEl = document.querySelector('.drive-url span');
      const openBtn = document.querySelector('.open-drive-btn');

      if (driveUrlEl) driveUrlEl.textContent = url;
      if (openBtn) openBtn.onclick = () => _openCurrentDoc(doc);

      _showEditDriveToast('Google Drive link updated.', 'success');
      closeEditDriveModal();
    } else {
      _showEditDriveToast(j.message || 'Failed to update Google Drive link.', 'error');
    }
  })
  .catch(err => {
    console.error('[documents.js] submitEditDrive error', err);
    _showEditDriveToast('Failed to update Google Drive link.', 'error');
  });
}

window.openEditDriveModal = openEditDriveModal;
window.closeEditDriveModal = closeEditDriveModal;
window.submitEditDrive = submitEditDrive;