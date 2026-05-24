<?php
/**
 * The header template
 *
 * @package Ecomsys Classic Theme
 */

?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- MOBILE VIEWPORT -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />

<?php wp_head(); ?>    
</head>

<body <?php body_class();?>>
    <?php wp_body_open(); ?>
    
    <div id="site-overlay"
        class="fixed inset-0 bg-black/20 z-40  opacity-0 pointer-events-none  transition-opacity sm:hidden"></div>

    <div class="flex flex-col min-h-[100dvh]">

        <a class="skip-link screen-reader-text"
            href="#primary"><?php esc_html_e('Skip to content', 'ecomsys-classic'); ?></a>

        <?php render_component('layouts.Header'); ?>