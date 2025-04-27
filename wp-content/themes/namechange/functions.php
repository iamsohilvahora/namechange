<?php
/**
 * namechange functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package namechange
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function namechange_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on namechange, use a find and replace
		* to change 'namechange' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'namechange', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'namechange' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'namechange_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'namechange_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function namechange_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'namechange_content_width', 640 );
}
add_action( 'after_setup_theme', 'namechange_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function namechange_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'namechange' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'namechange' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'namechange_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function namechange_scripts() {
	// Generate a unique version string using the current timestamp
	$version = time();

	wp_enqueue_style( 'namechange-style', get_stylesheet_uri(), array(), $version );
	wp_style_add_data( 'namechange-style', 'rtl', 'replace' );

	wp_enqueue_script( 'namechange-navigation', get_template_directory_uri() . '/js/navigation.js', array(), $version, true );
	wp_enqueue_script( 'custom-script', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), $version, true ); // Custom JS file.

	// load below bootstrap file only on order thank-you page.
	if(is_checkout()) {
		wp_enqueue_script( 'mask-script', get_template_directory_uri().'/assets/js/jquery.mask.min.js', array('jquery'), $version, true ); // load mask JS
	}
	
	if ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) {
		wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/assets/css/bootstrap.min.css', array(), $version ); // load bootstrap CSS
		wp_enqueue_script( 'bootstrap-script', get_template_directory_uri().'/assets/js/bootstrap.min.js', array('jquery'), $version, true ); // load bootstrap JS
	}

	wp_localize_script(
		'custom-script',
		'namechange_obj',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'site_url' => get_site_url(),
		)
	);

	// load css on homepage only
	if(is_front_page()) {
		wp_enqueue_style( 'namechange-home-style', get_template_directory_uri(). '/assets/css/home.css', array(), $version );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'namechange_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 *  Load woocommerce hooks
 */
require get_template_directory() . '/inc/woo-functions.php';

/**
 * Load Custom Post Type
 */
require get_template_directory() . '/inc/custom-post-types.php';
