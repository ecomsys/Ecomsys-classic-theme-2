<?php
// templates/ui/Toast/index.php

/**
 * Toaster — контейнер для уведомлений (аналог Sonner)
 */

 $position = $position ?? 'bottom-right';
 $class = $class ?? '';

// Маппинг позиций на Tailwind классы
 $positions = [
    'top-left'      => 'top-0 left-0',
    'top-center'    => 'top-0 left-1/2 -translate-x-1/2',
    'top-right'     => 'top-0 right-0',
    'bottom-left'   => 'bottom-0 left-0',
    'bottom-center' => 'bottom-0 left-1/2 -translate-x-1/2',
    'bottom-right'  => 'bottom-0 right-0',
];

 $posClasses = $positions[$position] ?? $positions['bottom-right'];

 $allClasses = cn(
    'fixed z-[9999] flex flex-col gap-2 p-4 max-w-md w-full pointer-events-none',
    $posClasses,
    $class
);
?>
<div id="toaster" class="<?php echo esc_attr($allClasses); ?>" aria-live="assertive"></div>