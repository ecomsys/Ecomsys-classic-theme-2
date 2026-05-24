<?php
// templates/ui/Slider/render/index.php

 $min = $min ?? 0;
 $max = $max ?? 100;
 $step = $step ?? 1;
 $value = $value ?? $min;
 $class = $class ?? '';
 $name = $name ?? 'slider_value'; 
 $showPins = $showPins ?? true;
 $prefix = $prefix ?? '';
 $suffix = $suffix ?? '';
 
 // НОВЫЙ ПРОПС: Пины всегда видны
 $pinAlways = $pinAlways ?? false; 

 // Массив всех переданных пропсов (из wp-components.php)
 $props = $props ?? []; 

 $isDual = is_array($value);
 $minValue = $isDual ? ($value[0] ?? $min) : $min;
 $maxValue = $isDual ? ($value[1] ?? $max) : $value;

 $minPercent = (($minValue - $min) / ($max - $min)) * 100;
 $maxPercent = (($maxValue - $min) / ($max - $min)) * 100;

 // Добавляем класс wp-slider-pins-always, если pinAlways включен
 $allClasses = cn(
     'relative w-full pt-2 pb-2', 
     $pinAlways ? 'wp-slider-pins-always' : '', 
     $class
 );

 $dataAttrs = sprintf(
    'data-min="%s" data-max="%s" data-step="%s" data-dual="%s" data-prefix="%s" data-suffix="%s"',
    esc_attr($min),
    esc_attr($max),
    esc_attr($step),
    $isDual ? 'true' : 'false',
    esc_attr($prefix),
    esc_attr($suffix)
);

 // Формируем строку кастомных атрибутов (aria-*, id, data-*)
 $reservedKeys = ['min', 'max', 'step', 'value', 'name', 'showPins', 'prefix', 'suffix', 'class', 'children', 'props', 'pinAlways']; // Добавили pinAlways
 $customAttrsString = '';

 if (!empty($props) && is_array($props)) {
     foreach ($props as $key => $val) {
         if (in_array($key, $reservedKeys)) continue; 
         if ($val === null) continue;

         if (is_bool($val)) {
             if ($val) $customAttrsString .= ' ' . esc_attr($key);
         } elseif (is_array($val)) {
             $customAttrsString .= ' ' . esc_attr($key) . "='" . esc_attr(json_encode($val)) . "'";
         } elseif (is_scalar($val)) {
             $customAttrsString .= ' ' . esc_attr($key) . '="' . esc_attr($val) . '"';
         }
     }
 }
?>

<div class="<?php echo esc_attr($allClasses); ?>" <?php echo $dataAttrs . $customAttrsString; ?>>
    
    <?php if ($isDual) : ?>
        <input type="hidden" name="<?php echo esc_attr($name . '_min'); ?>" class="wp-slider-input-min" value="<?php echo esc_attr($minValue); ?>">
        <input type="hidden" name="<?php echo esc_attr($name . '_max'); ?>" class="wp-slider-input-max" value="<?php echo esc_attr($maxValue); ?>">
    <?php else : ?>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" class="wp-slider-input-value" value="<?php echo esc_attr($maxValue); ?>">
    <?php endif; ?>

    <!-- ОБОЛОЧКА ТРЕКА И БЕГУНКОВ -->
    <div class="relative h-5 w-[calc(100%-1rem)] mx-auto flex items-center">
        
        <!-- Полоса трека -->
        <div class="-mx-[0.5rem] absolute left-0 right-0 h-2 rounded-full bg-slate-200 wp-slider-track">
            <div class="absolute h-full bg-slate-900 wp-slider-range" style="left: <?php echo $minPercent; ?>%; right: <?php echo 100 - $maxPercent; ?>%;"></div>
        </div>

        <!-- Бегунок 1 (Минимум) -->
        <?php if ($isDual) : ?>
        <div class="absolute h-5 w-5 rounded-full border-2 border-slate-900 bg-white shadow-md wp-slider-thumb wp-slider-thumb-min" style="left: <?php echo $minPercent; ?>%" tabindex="0">
            <?php if ($showPins) : ?>
                <div class="wp-slider-pin"><?php echo esc_html($prefix . $minValue . $suffix); ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Бегунок 2 (Максимум / Одиночный) -->
        <div class="absolute h-5 w-5 rounded-full border-2 border-slate-900 bg-white shadow-md wp-slider-thumb wp-slider-thumb-max" style="left: <?php echo $maxPercent; ?>%" tabindex="0">
            <?php if ($showPins) : ?>
                <div class="wp-slider-pin"><?php echo esc_html($prefix . $maxValue . $suffix); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>