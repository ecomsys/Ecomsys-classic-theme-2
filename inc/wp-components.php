<?php
// inc/wp-components.php

// Подключаем автолоадер если есть
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use TailwindMerge\TailwindMerge;

/**
 * Умное слияние Tailwind классов
 * Использует официальный gehrisandro/tailwind-merge-php
 *
 * @param string ...$classes
 * @return string
 */
function cn(...$classes)
{
    static $tw = null;

    if ($tw === null) {
        try {
            $tw = TailwindMerge::instance();
        } catch (Throwable $e) {
            // Fallback если библиотека не загружена
            $filtered = array_filter($classes, fn($c) => ! empty($c) && $c !== '');
            return implode(' ', $filtered);
        }
    }

    // Фильтруем пустые значения
    $filtered = [];
    foreach ($classes as $class) {
        if (is_array($class)) {
            $filtered[] = cn(...$class);
        } elseif (is_string($class) && trim($class) !== '') {
            $filtered[] = $class;
        }
    }

    if (empty($filtered)) {
        return '';
    }

    // Библиотека сама разрешит ВСЕ конфликты Tailwind
    return $tw->merge(implode(' ', $filtered));
}

// --------------------------------------------------------------------------------------------------------------------------------
// Рендер компонента с авто-выводом и с поддержкой вложенности
// ---------------------------------------------------------------------------------------------------------------------------------

/**
 * Рендер компонента с поддержкой children
 *
 * @param string $name Имя компонента (ui.Button)
 * @param array $data Данные для компонента
 * @param string|null $children Вложенный контент (строка HTML)
 * @return void
 */
function render_component(string $name, array $data = [], ?string $children = null): void
{
    if (!$name) return;

    $parts = explode('.', $name);
    if (count($parts) < 2) return;

    $category = strtolower($parts[0]);
    $component = $parts[1];

    $theme_root = get_template_directory();

    $paths = [
        $theme_root . "/components/{$category}/{$component}/render/index.php",
        $theme_root . "/components/{$category}/{$component}/index.php",
    ];

    $found = false;
    foreach ($paths as $path) {
        if (file_exists($path)) {
            $found = $path;
            break;
        }
    }

    if (!$found) return;

    // Передаем children как строку в данные, если он не пустой
    if ($children !== null && $children !== '') {
        $data['children'] = $children;
    }

    $props = $data;

    if (!empty($data)) {
        extract($data, EXTR_SKIP);
    }

    ob_start();
    try {
        require $found;
    } catch (Throwable $e) {
        ob_end_clean();
        return;
    }

    echo ob_get_clean();
}

/**
 * Возвращает отрендеренный компонент как строку (для вложенности)
 * 
 * @param string $name Имя компонента (ui.Button)
 * @param array $data Данные для компонента
 * @param string|null $children Вложенный контент (строка HTML)
 * @return string
 */
function component(string $name, array $data = [], ?string $children = null): string
{
    ob_start();
    render_component($name, $data, $children);
    return ob_get_clean();
}

// --------------------------------------------------------------------------------------------------------------------------------
// Шорткаты (Магические функции)
// ---------------------------------------------------------------------------------------------------------------------------------

// Регистрируем динамические функции для популярных компонентов
 $components = ['container', 'fragment', 'button', 'alert', 'badge', 'card'];
foreach ($components as $comp) {
    if (!function_exists($comp)) {
        eval("function $comp(\$props = [], ?string \$children = null) { 
            return component('ui." . ucfirst($comp) . "', \$props, \$children); 
        }");
    }
}

// Базовые HTML-теги как компоненты
 $tags = ['div', 'section', 'article', 'header', 'footer', 'main', 'aside', 'nav', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a'];
foreach ($tags as $tag) {
    if (!function_exists($tag)) {
        eval("function $tag(\$props = [], ?string \$children = null) { 
            \$props['tag'] = '$tag';
            return component('ui.Container', \$props, \$children); 
        }");
    }
}

// --------------------------------------------------------------------------------------------------------------------------------
// Подключаем стили компонентов (JS теперь собирает Vite!)
// ---------------------------------------------------------------------------------------------------------------------------------
add_action('wp_enqueue_scripts', 'wp_components_enqueue_assets', 5);

function wp_components_enqueue_assets()
{
    $theme_root     = get_template_directory();
    $components_dir = $theme_root . '/components';

    if (! is_dir($components_dir)) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($components_dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $filename = $file->getFilename();

            $relativePath = str_replace($components_dir . DIRECTORY_SEPARATOR, '', $file->getPath());
            $pathParts    = explode(DIRECTORY_SEPARATOR, $relativePath);

            if (count($pathParts) >= 2) {
                $category  = $pathParts[0];
                $component = $pathParts[1];

                // Подключаем ТОЛЬКО CSS! (JS собирает Vite из main.js)
                if ($filename === 'style.css') {
                    wp_enqueue_style(
                        "wp-{$category}-{$component}",
                        get_template_directory_uri() . "/components/{$category}/{$component}/style.css",
                        [],
                        filemtime($file->getPathname())
                    );
                }
            }
        }
    }
}


// --------------------------------------------------------------------------------------------------------------------------------
// Flash Toasts (Серверные уведомления)
// ---------------------------------------------------------------------------------------------------------------------------------

/**
 * Установить Flash Toast (сохраняется в сессию на 1 запрос)
 * API идентичен JS: первый аргумент - сообщение, второй - массив опций
 * 
 * @param string $message
 * @param array $options [type, description, duration, className]
 */
function set_flash_toast(string $message, array $options = []) {
    if (!session_id()) {
        session_start();
    }
    $_SESSION['wp_flash_toasts'][] = [
        'message' => $message,
        'options' => $options
    ];
}

/**
 * Вывести скрытые div'ы для инициализации JS тостов после перезагрузки страницы
 */
add_action('wp_footer', function() {
    if (!session_id()) return;
    if (empty($_SESSION['wp_flash_toasts'])) return;

    foreach ($_SESSION['wp_flash_toasts'] as $toast) {
        echo '<div data-toast="' . esc_attr(json_encode($toast)) . '" style="display:none;"></div>';
    }
    
    // Очищаем сессию, чтобы тосты не показывались повторно при рефреше
    unset($_SESSION['wp_flash_toasts']);
});