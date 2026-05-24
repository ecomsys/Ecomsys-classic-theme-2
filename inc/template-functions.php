<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Ecomsys Classic Theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

/*---------------------------------------------------
|Disable WP Emojis
---------------------------------------------------*/
function ecomsys_classic_disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'ecomsys_classic_disable_emojis');

/*---------------------------------------------------
|Remove WP version
---------------------------------------------------*/
function ecomsys_classic_remove_wp_version()
{
    remove_action('wp_head', 'wp_generator');
}
add_action('init', 'ecomsys_classic_remove_wp_version');

/*---------------------------------------------------
|Lazy-load YouTube iframe
---------------------------------------------------*/
add_filter('embed_oembed_html', function ($html) {
    return str_replace('<iframe', '<iframe loading="lazy"', $html);
});

/*---------------------------------------------------
| Enable SVG Upload
---------------------------------------------------*/
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

/*---------------------------------------------------
|Disable WP Embeds
---------------------------------------------------*/
remove_action('wp_head', 'wp_oembed_add_host_js');


/*---------------------------------------------------
Add a pingback url auto-discovery header for single posts, pages, or attachments.
---------------------------------------------------*/
function ecomsys_classic_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'ecomsys_classic_pingback_header');
