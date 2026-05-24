// cli_components/templates/lib/cn.js
import { twMerge } from 'tailwind-merge';

/**
 * Умное слияние Tailwind классов (JS версия функции cn())
 * Полный аналог PHP версии: поддерживает массивы, условия и резолвит конфликты
 */
export function cn(...inputs) {
    const classes = [];
    
    inputs.flat(Infinity).forEach(input => {
        if (!input) return; // Пропускаем null, undefined, false
        
        if (typeof input === 'string') {
            classes.push(input);
        } else if (Array.isArray(input)) {
            // Рекурсивно обрабатываем вложенные массивы
            classes.push(cn(...input));
        }
    });

    // Скармливаем всё twMerge для разрешения конфликтов
    return twMerge(classes.join(' '));
}