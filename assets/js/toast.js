function getToastContainer() {
    let container = document.getElementById('global-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'global-toast-container';
        // Fixed top-right, high z-index, stack toasts vertically with gap
        container.className = 'fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none items-end max-w-sm w-full';
        document.body.appendChild(container);
    }
    return container;
}

export function showToast(message, type = 'info', duration = 4000) {
    const container = getToastContainer();
    const toast = document.createElement('div');
    
    // Convert newlines to breaks for multi-line messages
    const formattedMessage = message.replace(/\n/g, '<br>');
    
    // Base styles (using modern Tailwind design) without colors
    let baseClasses = 'pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border transform transition-all duration-300 translate-x-full opacity-0 w-full';
    
    let iconHtml, typeClasses, textClasses;
    switch(type) {
        case 'success':
            iconHtml = `<svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            typeClasses = 'bg-emerald-50 border-emerald-200';
            textClasses = 'text-emerald-800';
            break;
        case 'error':
            iconHtml = `<svg class="w-5 h-5 mt-0.5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            typeClasses = 'bg-red-50 border-red-200';
            textClasses = 'text-red-800';
            break;
        case 'warning':
            iconHtml = `<svg class="w-5 h-5 mt-0.5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
            typeClasses = 'bg-yellow-50 border-yellow-200';
            textClasses = 'text-yellow-800';
            break;
        default: // info
            iconHtml = `<svg class="w-5 h-5 mt-0.5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            typeClasses = 'bg-blue-50 border-blue-200';
            textClasses = 'text-blue-800';
            break;
    }
    
    toast.className = `${baseClasses} ${typeClasses}`;

    toast.innerHTML = `
        ${iconHtml}
        <div class="flex-1">
            <p class="text-[13px] font-medium leading-relaxed ${textClasses}">${formattedMessage}</p>
        </div>
        <button class="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors p-1 -m-1" onclick="this.closest('.pointer-events-auto').remove()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Trigger animation in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });
    
    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-full');
            toast.addEventListener('transitionend', () => toast.remove());
        }, duration);
    }
};
