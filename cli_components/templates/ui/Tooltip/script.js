import { getScaleFactor } from "@/js/utils/autorem";

export function initTooltips() {
    let activeTooltip = null;

    // Функция для расчёта актуальных отступов (вызывается каждый раз)
    const getMargins = () => {
        const scale = getScaleFactor();
        return {
            left: 16 * scale,   // ~1rem
            right: 8 * scale,   // ~0.5rem
            top: 8 * scale,
            bottom: 8 * scale,
            pad: 8 * scale,
            arrow: 4 * scale    // компенсация вылета стрелки
        };
    };

    const findTrigger = (e) => e.target instanceof Element ? e.target.closest('[data-tooltip]') : null;

    const createTooltip = (trigger, text, placement) => {
        const el = document.createElement('div');
        el.className = 'wp-tooltip';
        el.textContent = text;
        el.setAttribute('role', 'tooltip');
        el.dataset.placement = placement;
        
        el.style.cssText = `
            position: fixed;
            z-index: 999999;
            pointer-events: none;
            top: -9999px;
            left: -9999px;
            opacity: 0;
            visibility: hidden;
            will-change: transform, opacity;
        `;
        document.body.appendChild(el);
        el._trigger = trigger;
        return el;
    };

    const updatePosition = (tooltip) => {
        const trigger = tooltip._trigger;
        if (!trigger?.isConnected) { hideTooltip(trigger); return; }

        const tRect = trigger.getBoundingClientRect();
        const ttRect = tooltip.getBoundingClientRect();
        
        if (ttRect.width === 0 || ttRect.height === 0) {
            requestAnimationFrame(() => updatePosition(tooltip));
            return;
        }

        const vw = window.innerWidth;
        const vh = window.innerHeight;

        // Получаем актуальные отступы ПРЯМО СЕЙЧАС
        const { left: ML, right: MR, top: MT, bottom: MB, pad: PAD, arrow: ARROW } = getMargins();

        let placement = trigger.dataset.tooltipPosition || 'top';
        let x = tRect.left + tRect.width / 2;
        let y = 0;

        // Применяем отступы + компенсацию стрелки
        switch(placement) {
            case 'top':    
                y = tRect.top - ttRect.height - MT - ARROW; 
                break;
            case 'bottom': 
                y = tRect.bottom + MB + ARROW; 
                break;
            case 'left':   
                x = tRect.left - ttRect.width - ML - ARROW; 
                y = tRect.top + tRect.height / 2; 
                break;
            case 'right':  
                x = tRect.right + MR + ARROW; 
                y = tRect.top + tRect.height / 2; 
                break;
        }

        // Ограничение краями (с учётом текущего PAD)
        if (placement === 'top' || placement === 'bottom') {
            x = Math.max(PAD, Math.min(x, vw - ttRect.width - PAD));
            if (placement === 'top' && y < PAD) y = PAD;
            if (placement === 'bottom' && y + ttRect.height > vh - PAD) y = vh - ttRect.height - PAD;
        } else {
            y = Math.max(PAD, Math.min(y, vh - ttRect.height - PAD));
            if (placement === 'left' && x < PAD) x = PAD;
            if (placement === 'right' && x + ttRect.width > vw - PAD) x = vw - ttRect.width - PAD;
        }

        tooltip.style.left = `${Math.round(x)}px`;
        tooltip.style.top = `${Math.round(y)}px`;
        tooltip.dataset.placement = placement;
    };

    const showTooltip = (trigger) => {
        if (trigger._tooltipEl || !trigger.dataset.tooltip) return;

        const placement = trigger.dataset.tooltipPosition || 'top';
        const tooltip = createTooltip(trigger, trigger.dataset.tooltip, placement);
        trigger._tooltipEl = tooltip;
        activeTooltip = tooltip;

        void tooltip.offsetHeight;
        updatePosition(tooltip);
        
        requestAnimationFrame(() => {
            tooltip.style.opacity = '1';
            tooltip.style.visibility = 'visible';
            tooltip.style.transform = '';
        });
    };

    const hideTooltip = (trigger) => {
        if (!trigger?._tooltipEl) return;
        const tooltip = trigger._tooltipEl;
        tooltip.style.opacity = '0';
        tooltip.style.visibility = 'hidden';
        
        setTimeout(() => {
            tooltip.remove();
            delete trigger._tooltipEl;
            if (activeTooltip === tooltip) activeTooltip = null;
        }, 150);
    };

    const onViewportChange = () => {
        if (activeTooltip?.isConnected) updatePosition(activeTooltip);
    };

    document.addEventListener('mouseover', e => {
        const t = findTrigger(e);
        if (t) showTooltip(t);
    }, { passive: true });

    document.addEventListener('mouseout', e => {
        const t = findTrigger(e);
        if (t && (!e.relatedTarget || !t.contains(e.relatedTarget))) hideTooltip(t);
    }, { passive: true });

    document.addEventListener('focusin', e => {
        const t = findTrigger(e);
        if (t) showTooltip(t);
    }, { passive: true });

    document.addEventListener('focusout', e => {
        const t = findTrigger(e);
        if (t) hideTooltip(t);
    }, { passive: true });

    window.addEventListener('scroll', onViewportChange, { passive: true });
    window.addEventListener('resize', onViewportChange, { passive: true });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTooltips);
} else {
    initTooltips();
}