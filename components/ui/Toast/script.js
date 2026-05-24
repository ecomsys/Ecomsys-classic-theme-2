// cli_components/templates/ui/Toast/script.js
import { cn } from '../../lib/cn.js';

/**
 * Функция вызова Toast
 */
export function toast(message, options = {}) {
    const { 
        type = 'success', 
        description = '', 
        duration = 4000,
        action = null, // { label: 'Отменить', onClick: () => {} }
        className = ''
    } = options;

    const container = document.getElementById('toaster');
    if (!container) {
        console.warn('Toaster container #toaster not found. Add render_component("ui.Toast") to your theme.');
        return;
    }

    const variants = {
        success: 'bg-white border-l-4 border-l-green-500 text-slate-900',
        error: 'bg-white border-l-4 border-l-red-500 text-slate-900',
        warning: 'bg-white border-l-4 border-l-yellow-500 text-slate-900',
        info: 'bg-white border-l-4 border-l-blue-500 text-slate-900',
    };

    const icons = {
        success: `<svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
        error: `<svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
        warning: `<svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>`,
        info: `<svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
    };

    const toastEl = document.createElement('div');
    
    // ИСПОЛЬЗУЕМ ИМПОРТИРОВАННУЮ ФУНКЦИЮ cn()
    toastEl.className = cn(
        'pointer-events-auto w-full flex items-start gap-3 p-4 rounded-lg shadow-lg border border-slate-200 transform transition-all duration-300 translate-y-2 opacity-0',
        variants[type] || variants.success,
        className
    );

    let actionHtml = '';
    if (action) {
        actionHtml = `<button class="text-sm font-medium text-slate-900 underline hover:no-underline ml-auto" data-action>${action.label}</button>`;
    }

    toastEl.innerHTML = `
        <div class="flex-shrink-0">${icons[type] || icons.success}</div>
        <div class="flex-1">
            <p class="text-sm font-semibold">${message}</p>
            ${description ? `<p class="mt-1 text-sm text-slate-500">${description}</p>` : ''}
        </div>
        ${actionHtml}
        <button class="text-slate-400 hover:text-slate-600" data-close>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    `;

    const closeBtn = toastEl.querySelector('[data-close]');
    closeBtn.addEventListener('click', () => removeToast(toastEl));

    if (action && action.onClick) {
        const actionBtn = toastEl.querySelector('[data-action]');
        actionBtn.addEventListener('click', () => {
            action.onClick();
            removeToast(toastEl);
        });
    }

    container.appendChild(toastEl);

    requestAnimationFrame(() => {
        toastEl.classList.remove('translate-y-2', 'opacity-0');
        toastEl.classList.add('translate-y-0', 'opacity-100');
    });

    if (duration > 0) {
        setTimeout(() => removeToast(toastEl), duration);
    }
}

function removeToast(toastEl) {
    toastEl.classList.remove('translate-y-0', 'opacity-100');
    toastEl.classList.add('translate-y-2', 'opacity-0');
    setTimeout(() => toastEl.remove(), 300);
}

// Обработка PHP Flash-сообщений
document.addEventListener('DOMContentLoaded', () => {
    const serverToasts = document.querySelectorAll('[data-toast]');
    serverToasts.forEach(el => {
        const data = el.dataset.toast ? JSON.parse(el.dataset.toast) : null;
        if (data) {
            toast(data.message, data.options || {});
        }
        el.remove();
    });
});

// Вешаем на window для удобного вызова
window.toast = toast;