<!-- Spacer so content isn't hidden behind mobile nav -->
<div class="md:hidden h-20"></div>

<script>
  // ── Sidebar collapse toggle ───────────────────────────────────────────────
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  const main = document.getElementById('mainContent');
  const icon = document.getElementById('toggleIcon');

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
  const mobileNav = document.getElementById('mobileNav');
  const navLeft = document.getElementById('navLeft');
  const navRight = document.getElementById('navRight');
  const scrollAmount = 120;

  if (mobileNav && navLeft && navRight) {
    navLeft.addEventListener('click', () => mobileNav.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    navRight.addEventListener('click', () => mobileNav.scrollBy({ left: scrollAmount, behavior: 'smooth' }));
  }
</script>
<!-- ── FEEDBACK WIDGET (NEW UI) ── -->
<button id="fb-launcher" title="">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
    stroke-linejoin="round">
    <path
      d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
  </svg>
</button>
<div id="fb-tooltip">Send feedback</div>

<div id="fb-panel">
  <div class="fb-header">
    <div class="fb-header-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path
          d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
      </svg>
    </div>
    <div class="fb-header-text">
      <strong>Send Feedback</strong>
      <span>Bugs, ideas, or questions — we read all of these</span>
    </div>
    <button class="fb-close " id="fb-close-panel">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
        stroke-linejoin="round" style="width:14px; height:14px;">
        <line x1="18" y1="6" x2="6" y2="18" />
        <line x1="6" y1="6" x2="18" y2="18" />
      </svg>
    </button>
  </div>
  <div class="fb-body">
    <div>
      <span class="fb-section-label">What's this about?</span>
      <div class="fb-type-grid" id="fb-type-grid">
        <div class="fb-type-chip" data-type="Bug Report">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M8 2l1.88 1.88M14.12 3.88L16 2M9 7.13v-1a3.003 3.003 0 1 1 6 0v1" />
            <path d="M12 20c-3.3 0-6-2.7-6-6v-3a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v3c0 3.3-2.7 6-6 6z" />
            <path
              d="M12 20v-9M6.53 9c-1 .1-2.5.5-3.53 2M20.97 11c-1.03-1.5-2.53-1.9-3.53-2M4 17c-1 .3-2 1-2.5 2M20.5 19c-.5-1-1.5-1.7-2.5-2" />
          </svg>
          <span>Bug</span>
        </div>
        <div class="fb-type-chip" data-type="Feature Request">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3.5 10.9c.6.4.9 1 .9 1.6H14.6c0-.7.4-1.3.9-1.6A6 6 0 0 0 12 3z" />
          </svg>
          <span>Idea</span>
        </div>
        <div class="fb-type-chip" data-type="Question">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 2-3 4" />
            <path d="M12 17h.01" />
          </svg>
          <span>Question</span>
        </div>
        <div class="fb-type-chip" data-type="General Feedback">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <circle cx="5" cy="12" r="1.5" />
            <circle cx="12" cy="12" r="1.5" />
            <circle cx="19" cy="12" r="1.5" />
          </svg>
          <span>Other</span>
        </div>
      </div>
      <div class="fb-inline-error" id="fb-type-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="12" cy="12" r="10" />
          <line x1="12" y1="8" x2="12" y2="12" />
          <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        Pick one so we route this correctly
      </div>
    </div>

    <div class="fb-field" id="fb-subject-field">
      <div class="fb-input-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="4 7 4 4 20 4 20 7" />
          <line x1="9" y1="20" x2="15" y2="20" />
          <line x1="12" y1="4" x2="12" y2="20" />
        </svg>
        <input type="text" id="fb-subject" placeholder="Subject" maxlength="120">
      </div>
      <div class="fb-inline-error" id="fb-subject-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="12" cy="12" r="10" />
          <line x1="12" y1="8" x2="12" y2="12" />
          <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        A short subject helps us triage faster
      </div>
    </div>

    <div class="fb-field" id="fb-details-field">
      <textarea id="fb-details" placeholder="What happened? Steps to reproduce, what you expected, etc."
        maxlength="1000"></textarea>
      <div class="fb-counter" id="fb-details-counter">0 / 1000</div>
      <div class="fb-inline-error" id="fb-details-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="12" cy="12" r="10" />
          <line x1="12" y1="8" x2="12" y2="12" />
          <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        Please provide some details
      </div>
    </div>

    <div>
      <div class="fb-label-row">
        <span>Screenshots</span>
        <button class="fb-btn-small" id="fb-capture">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
            <circle cx="12" cy="13" r="4" />
          </svg>
          <span id="fb-capture-label">Capture page</span>
        </button>
      </div>
      <div class="fb-thumbs" id="fb-thumbs"></div>
    </div>

    <label class="fb-attach">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
      </svg>
      <span>Or upload image(s) from your device</span>
      <input type="file" id="fb-attach-input" accept="image/*" multiple>
    </label>

    <div class="fb-field">
      <span class="fb-section-label" style="margin-bottom:4px; font-weight:600; color:#6b7280; font-size:11px;">Related
        Page (Optional)</span>
      <div class="fb-input-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round">
          <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
          <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
        </svg>
        <input type="text" id="fb-related-page" placeholder="Where did this happen?">
      </div>
    </div>

    <button class="fb-submit" id="fb-submit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z" />
      </svg>
      <span>Submit</span>
    </button>
  </div>
</div>

<div class="fb-toast" id="fb-toast-success">
  <div class="fb-toast-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"
      stroke-linejoin="round">
      <polyline points="20 6 9 17 4 12" />
    </svg></div>
  <span>Feedback submitted — thank you!</span>
</div>

<!-- ── ANNOTATION MODAL ── -->
<div id="modal-annotate-overlay">
  <div id="modal-annotate-box">
    <div id="modal-annotate-header">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
          <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
          <path d="M2 2l7.586 7.586"></path>
          <circle cx="11" cy="11" r="2"></circle>
        </svg>
        Annotate screenshot
      </h3>
      <button id="btn-close-annotate" title="Close">✕</button>
    </div>
    <div id="modal-annotate-toolbar">
      <div class="annotate-tools">
        <button class="annotate-tool active" data-tool="free">Free</button>
        <button class="annotate-tool" data-tool="box">Box</button>
        <button class="annotate-tool" data-tool="arrow">Arrow</button>
        <button class="annotate-tool" data-tool="text">Text</button>
      </div>
      <div class="annotate-colors">
        <div class="color-swatch active" style="background-color: #ef4444;" data-color="#ef4444"></div>
        <div class="color-swatch" style="background-color: #f97316;" data-color="#f97316"></div>
        <div class="color-swatch" style="background-color: #eab308;" data-color="#eab308"></div>
        <div class="color-swatch" style="background-color: #22c55e;" data-color="#22c55e"></div>
        <div class="color-swatch" style="background-color: #3b82f6;" data-color="#3b82f6"></div>
        <div class="color-swatch" style="background-color: #000000;" data-color="#000000"></div>
        <div class="color-swatch" style="background-color: #ffffff;" data-color="#ffffff"></div>
      </div>
      <div class="annotate-actions">
        <button id="btn-annotate-undo">Undo</button>
        <button id="btn-annotate-clear">Clear</button>
      </div>
    </div>
    <div id="modal-annotate-body">
      <div id="canvas-container">
        <canvas id="annotate-canvas"></canvas>
      </div>
      <div id="annotate-hint">Pick a tool and draw on the image. "Text" asks for the words to place where you click.
      </div>
    </div>
    <div id="modal-annotate-footer">
      <button id="btn-annotate-cancel">Cancel</button>
      <button id="btn-annotate-save">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
          <polyline points="17 21 17 13 7 13 7 21"></polyline>
          <polyline points="7 3 7 8 15 8"></polyline>
        </svg>
        Save & use
      </button>
    </div>
  </div>
</div>

<script src="/assets/js/feedback.js?v=<?= time() ?>"></script>
<!-- ── SESSION TIMEOUT MODAL ── -->
<div id="session-timeout-modal"
  class="fixed inset-0 z-[10000] hidden items-center justify-center bg-gray-900/50 backdrop-blur-sm">
  <div
    class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center transform transition-all scale-95 opacity-0 duration-300"
    id="session-timeout-content">
    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
      <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
        </path>
      </svg>
    </div>
    <h3 class="text-xl font-bold text-gray-900 mb-2">Session Expired</h3>
    <p class="text-gray-500 mb-6">Your session has expired due to inactivity. Please log in again to continue.</p>
    <button id="btn-timeout-login"
      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200">
      Log In Again
    </button>
  </div>
</div>

<script>
  (function () {
    const timeoutDuration = 10000;
    const storageKey = 'ascend_last_active';

    function updateActivity() {
      localStorage.setItem(storageKey, Date.now().toString());
    }

    // Initialize on load if not set, or if it's very old
    const lastActive = localStorage.getItem(storageKey);
    if (!lastActive || (Date.now() - parseInt(lastActive)) > timeoutDuration) {
      updateActivity();
    }

    function showTimeoutModal() {
      const modal = document.getElementById('session-timeout-modal');
      const content = document.getElementById('session-timeout-content');

      modal.classList.remove('hidden');
      modal.classList.add('flex');

      requestAnimationFrame(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
      });
    }

    document.getElementById('btn-timeout-login').addEventListener('click', function () {
      window.location.href = '/backend/auth/logout.php?error=timeout';
    });

    // Track user activity to reset the timer
    const activityEvents = ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart'];
    let throttleTimer;
    function handleActivity() {
      if (throttleTimer) return;
      throttleTimer = setTimeout(() => {
        updateActivity();
        throttleTimer = null;
      }, 1000); // throttle storage updates to at most once per second
    }

    activityEvents.forEach(evt => {
      document.addEventListener(evt, handleActivity, { passive: true });
    });

    // Check expiration every second (syncs across all tabs via localStorage)
    const checkInterval = setInterval(() => {
      const last = parseInt(localStorage.getItem(storageKey) || Date.now());
      if (Date.now() - last >= timeoutDuration) {
        clearInterval(checkInterval);
        showTimeoutModal();
      }
    }, 1000);
  })();
</script>

</body>

</html>