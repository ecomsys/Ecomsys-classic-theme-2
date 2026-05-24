<?php
/**
 * Ecomsys Classic Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Ecomsys Classic Theme
 */


if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/*---------------------------------------------------
|ADMIN BAR: Отключаем админ-бар на фронте для всех пользователей
---------------------------------------------------*/
// add_filter('show_admin_bar', '__return_false');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ecomsys_classic_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on wp-start, use a find and replace
	 * to change 'septic-classic' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('septic-classic', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	// add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	// register_nav_menus([		
	// 	'footer' => __('Footer', 'septic-classic'),
	// ]);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(		
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	
}
add_action('after_setup_theme', 'ecomsys_classic_setup');

/**
 * Functions which enhance the theme by hooking into WordPress. Scripts and styles are connecting here !
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Connecting dynamic or static scripts and styles
 */
require get_template_directory() . '/inc/enqueue-scripts.php';


/**
 * Connect helpers
 */
require get_template_directory() . '/inc/helpers.php';


/**
 * Connect wp-components library
 */
require_once __DIR__ . '/inc/wp-components.php';

