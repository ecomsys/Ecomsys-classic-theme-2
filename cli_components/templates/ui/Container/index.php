<?php
// templates/ui/Container/render/index.php
/**
 * Универсальный компонент-обёртка (Container)
 * Может рендерить любой HTML-тег с поддержкой Tailwind классов
 */

// Атрибуты
 $tag = $tag ?? 'div';           // HTML тег
 $class = $class ?? '';          // Дополнительные CSS классы
 $children = $children ?? null;  // Вложенный контент
 $id = $id ?? null;              // ID элемента

// Массив всех переданных пропсов (из wp-components.php)
 $props = $props ?? []; 

// Базовые классы (можно оставить пустыми или добавить полезные по умолчанию)
 $baseClasses = '';

// Собираем все классы
 $allClasses = cn($baseClasses, $class);

// Формируем строку атрибутов
 $attributes = '';

// 1. ID (отдельно, чтобы он был в начале для читаемости)
if ($id) {
    $attributes .= ' id="' . esc_attr($id) . '"';
}

// 2. Классы
 $attributes .= ' class="' . esc_attr($allClasses) . '"';

// 3. МАГИЯ: Любые кастомные атрибуты из $props (data-*, style, target и т.д.)
 $reservedKeys = ['tag', 'class', 'children', 'id', 'props'];

if (!empty($props) && is_array($props)) {
    foreach ($props as $key => $value) {
        // Пропускаем зарезервированные ключи
        if (in_array($key, $reservedKeys)) continue; 
        
        // Пропускаем null
        if ($value === null) continue;

        // Безопасная обработка типов
        if (is_bool($value)) {
            if ($value) $attributes .= ' ' . esc_attr($key);
        } elseif (is_array($value)) {
            // Массивы кодируем в JSON (полезно для Alpine.js: x-data="{}")
            $attributes .= ' ' . esc_attr($key) . "='" . esc_attr(json_encode($value)) . "'";
        } elseif (is_scalar($value)) {
            $attributes .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }
    }
}

// Рендер
echo "<{$tag}{$attributes}>";
if ($children !== null) {
    echo $children;
}
echo "</{$tag}>";
?>