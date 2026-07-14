(function() {
  // ---------------- STATE ----------------
  const screenshots = []; // { dataUrl }
  let editingIndex = null;
  let selectedType = null;

  // ---------------- UI ELEMENTS ----------------
  const launcher = document.getElementById('fb-launcher');
  const tooltip = document.getElementById('fb-tooltip');
  const panel = document.getElementById('fb-panel');
  const closePanelBtn = document.getElementById('fb-close-panel');
  const thumbsEl = document.getElementById('fb-thumbs');
  const captureBtn = document.getElementById('fb-capture');
  const captureLabel = document.getElementById('fb-capture-label');
  const submitBtn = document.getElementById('fb-submit');
  const toast = document.getElementById('fb-toast-success');
  const typeGrid = document.getElementById('fb-type-grid');
  const typeError = document.getElementById('fb-type-error');
  const subjectField = document.getElementById('fb-subject-field');
  const subjectInput = document.getElementById('fb-subject');
  const subjectError = document.getElementById('fb-subject-error');
  const detailsInput = document.getElementById('fb-details');
  const detailsCounter = document.getElementById('fb-details-counter');
  const relatedPageInput = document.getElementById('fb-related-page');
  const attachInput = document.getElementById('fb-attach-input');

  // Annotation Modal Elements
  const annotateOverlay = document.getElementById('modal-annotate-overlay');
  const btnCloseAnnotate = document.getElementById('btn-close-annotate');
  const btnAnnotateCancel = document.getElementById('btn-annotate-cancel');
  const btnAnnotateSave = document.getElementById('btn-annotate-save');
  const annotateCanvas = document.getElementById('annotate-canvas');
  const ctx = annotateCanvas ? annotateCanvas.getContext('2d') : null;

  if (!launcher || !panel) return;

  if (relatedPageInput) {
    relatedPageInput.value = window.location.pathname + window.location.search;
  }

  // ---------------- LAUNCHER TOOLTIP ----------------
  let hoverTimer;
  launcher.addEventListener('mouseenter', () => {
    if (panel.classList.contains('open')) return;
    hoverTimer = setTimeout(() => {
      const r = launcher.getBoundingClientRect();
      tooltip.style.left = Math.max(8, r.left - 8) + 'px';
      tooltip.style.top = (r.top - 30) + 'px';
      const onLeftEdge = r.left < window.innerWidth / 2;
      tooltip.style.left = onLeftEdge ? (r.right + 10) + 'px' : (r.left - tooltip.offsetWidth - 10) + 'px';
      tooltip.style.top = (r.top + r.height / 2 - 12) + 'px';
      tooltip.classList.add('show');
    }, 350);
  });
  launcher.addEventListener('mouseleave', () => { clearTimeout(hoverTimer); tooltip.classList.remove('show'); });

  // ---------------- LAUNCHER DRAG & SNAP ----------------
  function positionDefault() {
    launcher.style.right = '24px';
    launcher.style.bottom = '24px';
    launcher.style.left = 'auto';
    launcher.style.top = 'auto';
  }
  positionDefault();

  let dragging = false, moved = false, startX = 0, startY = 0, startLeft = 0, startTop = 0;

  launcher.addEventListener('pointerdown', (e) => {
    dragging = true; moved = false;
    launcher.classList.add('dragging');
    tooltip.classList.remove('show');
    const rect = launcher.getBoundingClientRect();
    startLeft = rect.left; startTop = rect.top;
    startX = e.clientX; startY = e.clientY;
    launcher.setPointerCapture(e.pointerId);
  });

  launcher.addEventListener('pointermove', (e) => {
    if (!dragging) return;
    const dx = e.clientX - startX, dy = e.clientY - startY;
    if (Math.abs(dx) > 4 || Math.abs(dy) > 4) moved = true;
    if (!moved) return;
    let newLeft = startLeft + dx, newTop = startTop + dy;
    newLeft = Math.max(4, Math.min(window.innerWidth - 60, newLeft));
    newTop = Math.max(4, Math.min(window.innerHeight - 60, newTop));
    launcher.style.left = newLeft + 'px';
    launcher.style.top = newTop + 'px';
    launcher.style.right = 'auto';
    launcher.style.bottom = 'auto';
  });

  launcher.addEventListener('pointerup', (e) => {
    dragging = false;
    launcher.classList.remove('dragging');
    if (!moved) { togglePanel(); return; }
    // snap to nearest horizontal edge
    const rect = launcher.getBoundingClientRect();
    const center = rect.left + rect.width / 2;
    const snapLeft = center < window.innerWidth / 2;
    launcher.style.transition = 'left .22s cubic-bezier(.2,.8,.3,1), right .22s cubic-bezier(.2,.8,.3,1)';
    if (snapLeft) { launcher.style.left = '16px'; launcher.style.right = 'auto'; }
    else { launcher.style.left = 'auto'; launcher.style.right = '16px'; }
    setTimeout(() => { launcher.style.transition = ''; }, 240);
  });

  // ---------------- PANEL TOGGLE & DRAG ----------------
  function positionPanelNearLauncher() {
    const r = launcher.getBoundingClientRect();
    const panelW = 380, panelH = Math.min(panel.scrollHeight || 520, window.innerHeight - 32);
    const onLeftHalf = r.left < window.innerWidth / 2;
    let left = onLeftHalf ? r.right + 12 : r.left - panelW - 12;
    left = Math.max(12, Math.min(left, window.innerWidth - panelW - 12));
    let top = r.top - panelH + r.height;
    top = Math.max(12, Math.min(top, window.innerHeight - panelH - 12));
    panel.style.left = left + 'px';
    panel.style.top = top + 'px';
    panel.style.transformOrigin = onLeftHalf ? 'left bottom' : 'right bottom';
  }

  function togglePanel() {
    if (panel.classList.contains('open')) { closePanel(); }
    else { positionPanelNearLauncher(); panel.classList.add('open'); launcher.classList.add('open'); setTimeout(() => subjectInput.focus(), 180); }
  }
  
  function closePanel() { panel.classList.remove('open'); launcher.classList.remove('open'); }
  closePanelBtn.addEventListener('click', closePanel);

  const panelHeader = panel.querySelector('.fb-header');
  let panelDragging = false, panelMoved = false, pStartX = 0, pStartY = 0, pStartLeft = 0, pStartTop = 0;

  panelHeader.addEventListener('pointerdown', (e) => {
    if (e.target.closest('.fb-close')) return;
    panelDragging = true; panelMoved = false;
    panelHeader.classList.add('dragging');
    const rect = panel.getBoundingClientRect();
    pStartLeft = rect.left; pStartTop = rect.top;
    pStartX = e.clientX; pStartY = e.clientY;
    panelHeader.setPointerCapture(e.pointerId);
  });

  panelHeader.addEventListener('pointermove', (e) => {
    if (!panelDragging) return;
    const dx = e.clientX - pStartX, dy = e.clientY - pStartY;
    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) panelMoved = true;
    if (!panelMoved) return;
    let newLeft = pStartLeft + dx, newTop = pStartTop + dy;
    const w = panel.offsetWidth, h = panel.offsetHeight;
    newLeft = Math.max(4, Math.min(window.innerWidth - w - 4, newLeft));
    newTop = Math.max(4, Math.min(window.innerHeight - h - 4, newTop));
    panel.style.left = newLeft + 'px';
    panel.style.top = newTop + 'px';
  });

  panelHeader.addEventListener('pointerup', () => {
    panelDragging = false;
    panelHeader.classList.remove('dragging');
  });

  // Global click to close
  document.addEventListener('pointerdown', (e) => {
    if (!panel.classList.contains('open')) return;
    if (panel.contains(e.target) || launcher.contains(e.target)) return;
    if (annotateOverlay && annotateOverlay.classList.contains('active')) return; // don't close if annotating
    closePanel();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (annotateOverlay && annotateOverlay.classList.contains('active')) closeAnnotate();
    else if (panel.classList.contains('open')) closePanel();
  });

  // ---------------- FORM INTERACTIONS ----------------
  typeGrid.querySelectorAll('.fb-type-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      selectedType = chip.dataset.type;
      typeGrid.querySelectorAll('.fb-type-chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      typeGrid.classList.remove('invalid');
      typeError.classList.remove('show');
    });
  });

  detailsInput.addEventListener('input', () => {
    detailsCounter.textContent = detailsInput.value.length + ' / 1000';
  });

  // ---------------- SCREENSHOT LOGIC ----------------
  function renderThumbs() {
    thumbsEl.innerHTML = '';
    screenshots.forEach((s, i) => {
      const div = document.createElement('div');
      div.className = 'fb-thumb';
      div.innerHTML = `<img src="${s.dataUrl}">
        <div class="fb-thumb-overlay">
          <button class="fb-thumb-edit" title="Annotate">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg>
          </button>
          <button class="fb-thumb-del" title="Remove">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>`;
      div.querySelector('.fb-thumb-edit').addEventListener('click', (e) => { e.stopPropagation(); openAnnotate(i); });
      div.querySelector('.fb-thumb-del').addEventListener('click', (e) => { e.stopPropagation(); screenshots.splice(i, 1); renderThumbs(); });
      div.addEventListener('click', () => openAnnotate(i));
      thumbsEl.appendChild(div);
    });
  }

  captureBtn.addEventListener('click', () => {
    captureBtn.disabled = true;
    captureLabel.textContent = 'Capturing…';
    panel.style.visibility = 'hidden';
    launcher.style.visibility = 'hidden';
    
    setTimeout(() => {
        html2canvas(document.body).then(canvas => {
            screenshots.push({ dataUrl: canvas.toDataURL('image/png') });
            renderThumbs();
            panel.style.visibility = 'visible';
            launcher.style.visibility = 'visible';
            captureBtn.disabled = false;
            captureLabel.textContent = 'Capture page';
            // Automatically open annotation modal for newly captured screenshot
            openAnnotate(screenshots.length - 1);
        }).catch(err => {
            console.error('Capture failed', err);
            panel.style.visibility = 'visible';
            launcher.style.visibility = 'visible';
            captureBtn.disabled = false;
            captureLabel.textContent = 'Capture page';
        });
    }, 100);
  });

  attachInput.addEventListener('change', (e) => {
    Array.from(e.target.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = () => { screenshots.push({ dataUrl: reader.result }); renderThumbs(); };
            reader.readAsDataURL(file);
        }
    });
    attachInput.value = '';
  });

  // ---------------- ANNOTATION ENGINE ----------------
  let currentTool = 'free';
  let currentColor = '#ef4444';
  let isDrawing = false;
  let startDrawX = 0;
  let startDrawY = 0;
  let undoStack = [];

  function openAnnotate(index) {
      editingIndex = index;
      const img = new Image();
      img.onload = () => {
          annotateCanvas.width = img.width;
          annotateCanvas.height = img.height;
          ctx.drawImage(img, 0, 0);
          undoStack = [ctx.getImageData(0, 0, annotateCanvas.width, annotateCanvas.height)];
          annotateOverlay.classList.add('active');
      };
      img.src = screenshots[index].dataUrl;
  }

  function closeAnnotate() {
      annotateOverlay.classList.remove('active');
      editingIndex = null;
  }

  if (btnCloseAnnotate && btnAnnotateCancel) {
      btnCloseAnnotate.addEventListener('click', closeAnnotate);
      btnAnnotateCancel.addEventListener('click', closeAnnotate);
  }

  // Tool Selection
  const tools = document.querySelectorAll('.annotate-tool');
  tools.forEach(t => {
      t.addEventListener('click', () => {
          tools.forEach(btn => btn.classList.remove('active'));
          t.classList.add('active');
          currentTool = t.getAttribute('data-tool');
          let hint = 'Draw on the image to highlight or box areas.';
          if (currentTool === 'text') hint = 'Click anywhere on the image to add text.';
          else if (currentTool === 'arrow') hint = 'Click and drag to draw an arrow.';
          document.getElementById('annotate-hint').innerText = hint;
      });
  });

  // Color Selection
  const colors = document.querySelectorAll('.color-swatch');
  colors.forEach(c => {
      c.addEventListener('click', () => {
          colors.forEach(swatch => swatch.classList.remove('active'));
          c.classList.add('active');
          currentColor = c.getAttribute('data-color');
      });
  });

  const getMousePos = (e) => {
      const rect = annotateCanvas.getBoundingClientRect();
      const scaleX = annotateCanvas.width / rect.width;
      const scaleY = annotateCanvas.height / rect.height;
      return {
          x: (e.clientX - rect.left) * scaleX,
          y: (e.clientY - rect.top) * scaleY
      };
  };

  const saveState = () => {
      undoStack.push(ctx.getImageData(0, 0, annotateCanvas.width, annotateCanvas.height));
      if(undoStack.length > 20) undoStack.shift();
  };

  const restoreState = () => {
      if(undoStack.length > 0) {
          ctx.putImageData(undoStack[undoStack.length - 1], 0, 0);
      }
  };

  if (annotateCanvas) {
      annotateCanvas.addEventListener('mousedown', (e) => {
          const pos = getMousePos(e);
          
          if (currentTool === 'text') {
              const text = prompt('Enter text:');
              if (text) {
                  ctx.font = 'bold 36px sans-serif';
                  ctx.fillStyle = currentColor;
                  ctx.fillText(text, pos.x, pos.y);
                  saveState();
              }
              return;
          }

          isDrawing = true;
          startDrawX = pos.x;
          startDrawY = pos.y;
          
          if (currentTool === 'free') {
              ctx.beginPath();
              ctx.moveTo(startDrawX, startDrawY);
          }
      });

      annotateCanvas.addEventListener('mousemove', (e) => {
          if (!isDrawing) return;
          const pos = getMousePos(e);

          if (currentTool === 'free') {
              ctx.lineTo(pos.x, pos.y);
              ctx.strokeStyle = currentColor;
              ctx.lineWidth = 4;
              ctx.stroke();
          } else if (currentTool === 'box') {
              restoreState();
              ctx.beginPath();
              ctx.rect(startDrawX, startDrawY, pos.x - startDrawX, pos.y - startDrawY);
              ctx.strokeStyle = currentColor;
              ctx.lineWidth = 4;
              ctx.stroke();
          } else if (currentTool === 'arrow') {
              restoreState();
              const headlen = 15;
              const dx = pos.x - startDrawX;
              const dy = pos.y - startDrawY;
              const angle = Math.atan2(dy, dx);
              
              ctx.beginPath();
              ctx.moveTo(startDrawX, startDrawY);
              ctx.lineTo(pos.x, pos.y);
              
              ctx.lineTo(pos.x - headlen * Math.cos(angle - Math.PI / 6), pos.y - headlen * Math.sin(angle - Math.PI / 6));
              ctx.moveTo(pos.x, pos.y);
              ctx.lineTo(pos.x - headlen * Math.cos(angle + Math.PI / 6), pos.y - headlen * Math.sin(angle + Math.PI / 6));
              
              ctx.strokeStyle = currentColor;
              ctx.lineWidth = 4;
              ctx.stroke();
          }
      });

      const stopDrawing = () => {
          if (isDrawing) {
              isDrawing = false;
              saveState();
          }
      };

      annotateCanvas.addEventListener('mouseup', stopDrawing);
      annotateCanvas.addEventListener('mouseout', stopDrawing);
  }

  document.getElementById('btn-annotate-undo')?.addEventListener('click', () => {
      if (undoStack.length > 1) {
          undoStack.pop();
          restoreState();
      }
  });

  document.getElementById('btn-annotate-clear')?.addEventListener('click', () => {
      if (undoStack.length > 0) {
          ctx.putImageData(undoStack[0], 0, 0);
          undoStack = [undoStack[0]];
      }
  });

  if (btnAnnotateSave) {
      btnAnnotateSave.addEventListener('click', () => {
          if (editingIndex !== null) {
              screenshots[editingIndex].dataUrl = annotateCanvas.toDataURL('image/png');
              renderThumbs();
          }
          closeAnnotate();
      });
  }

  // ---------------- FORM SUBMISSION ----------------
  function shakeInvalid(el) { 
      el.classList.add('shake'); 
      setTimeout(() => el.classList.remove('shake'), 320); 
  }

  submitBtn.addEventListener('click', async () => {
    let valid = true;
    if (!selectedType) {
      typeGrid.classList.add('invalid');
      typeError.classList.add('show');
      shakeInvalid(typeGrid);
      valid = false;
    }
    
    if (!subjectInput.value.trim()) {
      subjectField.classList.add('invalid');
      subjectError.classList.add('show');
      shakeInvalid(subjectField);
      valid = false;
    } else {
      subjectField.classList.remove('invalid');
      subjectError.classList.remove('show');
    }
    
    if (!valid) return;

    const details = detailsInput.value.trim();
    const relatedPage = relatedPageInput.value;

    submitBtn.disabled = true;
    const originalHtml = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="fb-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9" stroke-opacity=".3"/><path d="M21 12a9 9 0 0 0-9-9"/></svg><span>Sending…</span>';

    // Build FormData payload
    const formData = new FormData();
    formData.append('type', selectedType);
    formData.append('subject', subjectInput.value.trim());
    formData.append('details', details);
    formData.append('related_page', relatedPage);

    // Convert dataUrls to Blob Files
    const fetchPromises = screenshots.map((s, index) => {
        return fetch(s.dataUrl)
            .then(res => res.blob())
            .then(blob => {
                formData.append('images[]', new File([blob], `screenshot_${Date.now()}_${index}.png`, { type: 'image/png' }));
            });
    });

    await Promise.all(fetchPromises);

    const pathParts = window.location.pathname.split('/');
    const pagesIndex = pathParts.indexOf('pages');
    let rootPrefix = pagesIndex !== -1 ? '../'.repeat(pathParts.length - pagesIndex - 1) : './';

    fetch(rootPrefix + 'api/feedback_submit.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closePanel();
            if (toast) {
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 3000);
            }
            // Reset form
            selectedType = null;
            typeGrid.querySelectorAll('.fb-type-chip').forEach(c => c.classList.remove('active'));
            subjectInput.value = '';
            detailsInput.value = '';
            detailsCounter.textContent = '0 / 1000';
            screenshots.length = 0;
            renderThumbs();
        } else {
            alert('Error submitting feedback: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('A network error occurred.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHtml;
    });
  });

})();
