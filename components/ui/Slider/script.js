// templates/ui/Slider/script.js

export function initSliders() {
    const sliders = document.querySelectorAll('[data-min][data-max]');

    sliders.forEach(slider => {
        const isDual = slider.dataset.dual === 'true';
        const min = parseFloat(slider.dataset.min);
        const max = parseFloat(slider.dataset.max);
        const step = parseFloat(slider.dataset.step);
        const prefix = slider.dataset.prefix || '';
        const suffix = slider.dataset.suffix || '';

        const range = slider.querySelector('.wp-slider-range');
        const track = slider.querySelector('.wp-slider-track'); // ВАЖНО: берем саму полосу!
        const thumbMax = slider.querySelector('.wp-slider-thumb-max');
        const thumbMin = isDual ? slider.querySelector('.wp-slider-thumb-min') : null;
        const inputMax = slider.querySelector('.wp-slider-input-max') || slider.querySelector('.wp-slider-input-value');
        const inputMin = isDual ? slider.querySelector('.wp-slider-input-min') : null;
        
        const pinMax = thumbMax.querySelector('.wp-slider-pin');
        const pinMin = thumbMin ? thumbMin.querySelector('.wp-slider-pin') : null;

        let currentMin = inputMin ? parseFloat(inputMin.value) : min;
        let currentMax = parseFloat(inputMax.value);

        const formatValue = (val) => {
            const formatted = step % 1 === 0 ? Math.round(val) : val.toFixed(2);
            return prefix + formatted + suffix;
        };

        const updateUI = () => {
            const minPercent = ((currentMin - min) / (max - min)) * 100;
            const maxPercent = ((currentMax - min) / (max - min)) * 100;

            range.style.left = `${minPercent}%`;
            range.style.right = `${100 - maxPercent}%`;

            thumbMax.style.left = `${maxPercent}%`;
            if (thumbMin) thumbMin.style.left = `${minPercent}%`;

            if (inputMax) inputMax.value = currentMax;
            if (inputMin) inputMin.value = currentMin;

            if (pinMax) pinMax.textContent = formatValue(currentMax);
            if (pinMin) pinMin.textContent = formatValue(currentMin);
        };

        // МАТЕМАТИКА: Считаем проценты ИМЕННО ПО ШИРИНЕ ПОЛОСЫ (track)
        const getValueFromPosition = (clientX) => {
            const rect = track.getBoundingClientRect(); // Используем track вместо slider!
            let percent = (clientX - rect.left) / rect.width;
            percent = Math.max(0, Math.min(1, percent));
            
            let rawValue = min + percent * (max - min);
            rawValue = Math.round(rawValue / step) * step;
            return Math.max(min, Math.min(max, rawValue));
        };

        const setupDrag = (thumb, type) => {
            const onMove = (e) => {
                e.preventDefault();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let value = getValueFromPosition(clientX);

                if (type === 'min') {
                    currentMin = Math.min(value, currentMax - step);
                } else {
                    if (isDual) {
                        currentMax = Math.max(value, currentMin + step);
                    } else {
                        currentMax = value;
                    }
                }
                updateUI();
            };

            const onEnd = () => {
                thumb.classList.remove('is-dragging');
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onEnd);
                document.removeEventListener('touchmove', onMove);
                document.removeEventListener('touchend', onEnd);
            };

            const onStart = (e) => {
                e.preventDefault();
                thumb.classList.add('is-dragging');
                document.addEventListener('mousemove', onMove);
                document.addEventListener('mouseup', onEnd);
                document.addEventListener('touchmove', onMove, { passive: false });
                document.addEventListener('touchend', onEnd);
            };

            thumb.addEventListener('mousedown', onStart);
            thumb.addEventListener('touchstart', onStart, { passive: false });
        };

        setupDrag(thumbMax, 'max');
        if (isDual && thumbMin) setupDrag(thumbMin, 'min');

        // Клик по треку
        slider.addEventListener('click', (e) => {
            if (e.target.classList.contains('wp-slider-thumb')) return;
            
            const value = getValueFromPosition(e.clientX);
            if (isDual) {
                if (Math.abs(value - currentMin) < Math.abs(value - currentMax)) {
                    currentMin = Math.min(value, currentMax - step);
                } else {
                    currentMax = Math.max(value, currentMin + step);
                }
            } else {
                currentMax = value;
            }
            updateUI();
        });
        
        updateUI(); 
    });
}

initSliders();