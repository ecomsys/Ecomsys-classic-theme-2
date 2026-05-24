<?php
// templates/ui/Button/render/index.php

 $label = $label ?? '';
 $variant = $variant ?? 'default';
 $size = $size ?? 'default';
 $href = $href ?? null;
 $disabled = $disabled ?? false;
 $class = $class ?? '';
 $type = $type ?? 'button';
 $onclick = $onclick ?? '';
 $children = $children ?? null;

 $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

 $variants = [
    'default' => 'bg-slate-900 text-white hover:bg-slate-800',
    'destructive' => 'bg-red-500 text-white hover:bg-red-600',
    'outline' => 'border border-slate-200 bg-white hover:bg-slate-100 hover:text-slate-900',
    'secondary' => 'bg-slate-100 text-slate-900 hover:bg-slate-200',
    'ghost' => 'hover:bg-slate-100 hover:text-slate-900',
    'link' => 'text-slate-900 underline-offset-4 hover:underline',
];

 $sizes = [
    'default' => 'h-10 px-4 py-2',
    'sm' => 'h-9 rounded-md px-3',
    'lg' => 'h-11 rounded-md px-8',
    'icon' => 'h-10 w-10',
];

 $allClasses = cn(
    $baseClasses,
    $variants[$variant],
    $sizes[$size],
    $disabled ? 'opacity-50 cursor-not-allowed' : '',
    $class
);

// Формируем строку атрибутов
 $attrsString = '';

// 1. Стандартные атрибуты из пропсов
if ($disabled) $attrsString .= ' disabled';
if ($onclick) $attrsString .= ' onclick="' . esc_attr($onclick) . '"';
if ($type && !$href) $attrsString .= ' type="' . esc_attr($type) . '"';

// 2. МАГИЯ: Любые кастомные атрибуты (data-*, id, target, rel и т.д.)
// Список зарезервированных ключей, которые НЕ должны стать HTML-атрибутами
 $reservedKeys = ['label', 'variant', 'size', 'href', 'disabled', 'class', 'type', 'onclick', 'children'];

if (!empty($props) && is_array($props)) {
    foreach ($props as $key => $value) {
        if (in_array($key, $reservedKeys)) continue; // Пропусляем зарезервированные
        
        if (is_bool($value)) {
            if ($value) $attrsString .= ' ' . esc_attr($key);
        } else {
            $attrsString .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }
    }
}

// Формируем внутренний контент
if (!empty($children)) {
    $content = $children;
} elseif (!empty($label)) {
    $content = '<span>' . esc_html($label) . '</span>';
} else {
    $content = 'Button';
}

if ($href && !$disabled) : ?>
    <a href="<?php echo esc_url($href); ?>" class="<?php echo esc_attr($allClasses); ?>" <?php echo $attrsString; ?>>
        <?php echo $content; ?>
    </a>
<?php else : ?>
    <button class="<?php echo esc_attr($allClasses); ?>" <?php echo $attrsString; ?>>
        <?php echo $content; ?>
    </button>
<?php endif; ?>