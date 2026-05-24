<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ecomsys Classic Theme
 */

get_header();
?>
 <main class="flex-1 flex flex-col">

    <?php render_component('sections.Page');?>   

  </main>   

<?php get_footer();



