<?php

/*--------------------------------------------------
Проверка dev режима - запущен ли vite
---------------------------------------------------*/
function ecomsys_classic_is_dev()
{
    $response = wp_remote_get('http://localhost:5173/@vite/client');
    return !is_wp_error($response);
}

/*---------------------------------------------------
Add scripts and styles (используем динамический скрипт и стили или билд)
---------------------------------------------------*/
function ecomsys_classic_enqueue_assets()
{
    $is_dev = ecomsys_classic_is_dev();

    // ==============================
    // DEV режим (Vite server)
    // ==============================
    if ($is_dev) {
        // DEV: Vite server — обязательно type="module"
        add_action('wp_head', function() {
            echo '<script type="module" src="http://localhost:5173/@vite/client"></script>' . "\n";
            echo '<script type="module" src="http://localhost:5173/assets/src/js/main.js"></script>' . "\n";
        }, 20); // Приоритет 20 - после компонентов
        return;
    }

    // ==============================
    // PROD режим (manifest)
    // ==============================
    $manifest_path = get_template_directory() . '/assets/dist/.vite/manifest.json';

    if (!file_exists($manifest_path))
        return;

    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (!isset($manifest['assets/src/js/main.js']))
        return;

    $entry = $manifest['assets/src/js/main.js'];

    // CSS
    if (!empty($entry['css'])) {
        foreach ($entry['css'] as $cssFile) {
            wp_enqueue_style(
                'main-css',
                get_template_directory_uri() . '/assets/dist/' . $cssFile,
                [],
                null
            );
        }
    }

    // JS
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/dist/' . $entry['file'],
        [],
        null,
        true
    );
}

add_action('wp_enqueue_scripts', 'ecomsys_classic_enqueue_assets', 10);

/*---------------------------------------------------
|Async / Defer JS Loader (Помещаем скрипт внизу сайта)
---------------------------------------------------*/
function ecomsys_script_loader_tag($tag, $handle, $src)
{
    $deferable = array('main-js');
    if (in_array($handle, $deferable)) {
        return '<script src="' . esc_url($src) . '" defer></script>' . "\n";
    }
    return $tag;
}
add_filter('script_loader_tag', 'ecomsys_script_loader_tag', 10, 3);