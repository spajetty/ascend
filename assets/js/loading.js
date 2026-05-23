// Shared loading states for buttons, containers, and async actions.

const SPINNER_SVG = (size = 16, className = '') => `
    <svg class="ascend-spinner inline-block flex-shrink-0 ${className}" width="${size}" height="${size}"
        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
        stroke-linecap="round" stroke-linejoin="round" style="animation:ascend-spin 0.75s linear infinite" aria-hidden="true">
        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
    </svg>`;

function ensureSpinnerStyle() {
    if (document.getElementById('ascend-spinner-style')) return;
    const style = document.createElement('style');
    style.id = 'ascend-spinner-style';
    style.textContent = '@keyframes ascend-spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);
}

/**
 * @param {HTMLElement|null} button
 * @param {boolean} loading
 * @param {{ label?: string, loadingHtml?: string }} [options]
 */
export function setButtonLoading(button, loading, options = {}) {
    if (!button) return;
    ensureSpinnerStyle();

    const label = options.label ?? 'Loading…';
    const loadingHtml = options.loadingHtml ?? `${SPINNER_SVG(14)} <span>${label}</span>`;

    if (loading) {
        if (button.dataset.ascendLoadingOriginal === undefined) {
            button.dataset.ascendLoadingOriginal = button.innerHTML;
        }
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.classList.add('ascend-btn-loading');
        button.innerHTML = loadingHtml;
    } else {
        button.disabled = false;
        button.removeAttribute('aria-busy');
        button.classList.remove('ascend-btn-loading');
        if (button.dataset.ascendLoadingOriginal !== undefined) {
            button.innerHTML = button.dataset.ascendLoadingOriginal;
            delete button.dataset.ascendLoadingOriginal;
        }
    }
}

/**
 * @param {HTMLElement|null} button
 * @param {() => Promise<unknown>} fn
 * @param {{ label?: string }} [options]
 */
export async function runWithButtonLoading(button, fn, options = {}) {
    setButtonLoading(button, true, options);
    try {
        return await fn();
    } finally {
        setButtonLoading(button, false, options);
    }
}

/**
 * @param {HTMLElement|null} container
 * @param {boolean} loading
 * @param {string} [message]
 * @param {{ colspan?: number }} [options]
 */
export function setContainerLoading(container, loading, message = 'Loading…', options = {}) {
    if (!container) return;
    ensureSpinnerStyle();

    if (loading) {
        if (container.dataset.ascendLoadingOriginal === undefined) {
            container.dataset.ascendLoadingOriginal = container.innerHTML;
        }
        const isTableBody = container.tagName === 'TBODY';
        if (isTableBody) {
            const cols = options.colspan ?? container.closest('table')?.querySelectorAll('thead th')?.length ?? 8;
            container.innerHTML = `
                <tr class="ascend-loading-row">
                    <td colspan="${cols}" style="text-align:center;padding:24px 16px;color:var(--text-muted,#6b7280);">
                        <span style="display:inline-flex;align-items:center;gap:8px;justify-content:center;">
                            ${SPINNER_SVG(18)}
                            <span>${message}</span>
                        </span>
                    </td>
                </tr>`;
        } else {
            container.innerHTML = `
                <div class="ascend-loading-block" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:24px 16px;color:#6b7280;text-align:center;">
                    ${SPINNER_SVG(18)}
                    <span>${message}</span>
                </div>`;
        }
        container.setAttribute('aria-busy', 'true');
    } else if (container.dataset.ascendLoadingOriginal !== undefined) {
        container.innerHTML = container.dataset.ascendLoadingOriginal;
        delete container.dataset.ascendLoadingOriginal;
        container.removeAttribute('aria-busy');
    }
}

/** Find the visible modal's primary confirm button. */
export function getOpenModalConfirmButton() {
    const modals = document.querySelectorAll('[id^="modal"]');
    for (const modal of modals) {
        if (modal.style.display === 'flex') {
            return modal.querySelector('.btn-confirm');
        }
    }
    return null;
}

/**
 * @param {string} label
 * @param {() => Promise<unknown>} fn
 */
export async function runModalConfirmLoading(label, fn) {
    return runWithButtonLoading(getOpenModalConfirmButton(), fn, { label });
}

/** Clear loading state without restoring previous HTML (caller will replace content). */
export function releaseContainerLoading(container) {
    if (!container) return;
    delete container.dataset.ascendLoadingOriginal;
    container.removeAttribute('aria-busy');
}

export { SPINNER_SVG };

if (typeof window !== 'undefined') {
    window.AscendLoading = {
        SPINNER_SVG,
        setButtonLoading,
        runWithButtonLoading,
        setContainerLoading,
        releaseContainerLoading,
        getOpenModalConfirmButton,
        runModalConfirmLoading,
    };
}
