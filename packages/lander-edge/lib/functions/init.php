<?php

if ( function_exists( 'is_bbPress' ) && !class_exists( 'BBP_Genesis' ) ) {
	require_once CHILD_DIR . '/lib/functions/bbpress-ready.php';
}	
require_once( CHILD_DIR . '/lib/functions/fonts.php' );
require_once( CHILD_DIR . '/lib/functions/schema-mgt.php' );
require_once( CHILD_DIR . '/lib/functions/menu-mgt.php' );
require_once( CHILD_DIR . '/lib/functions/landing-sections-mgt.php' );
require_once( CHILD_DIR . '/lib/functions/lander-mobile-detection.php' );
require_once( CHILD_DIR . '/lib/functions/landing-page-settings.php' );
require_once( CHILD_DIR . '/lib/functions/lander-custom-template-box.php' );
require_once( CHILD_DIR . '/lib/functions/mobile-template-settings.php' );
require_once( CHILD_DIR . '/lib/functions/lander-custom-layout-style.php' );
require_once( CHILD_DIR . '/lib/functions/shortcodes.php' );
require_once( CHILD_DIR . '/lib/functions/I18n.php' );
if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
	require_once( CHILD_DIR . '/lib/functions/lander-woocommerce.php' );
}

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'  ) );
add_theme_support( 'genesis-after-entry-widget-area' );
add_theme_support( 'genesis-responsive-viewport' );
add_theme_support( 'genesis-connect-woocommerce' );
add_theme_support( 'screen-reader-text' );
add_theme_support( 'genesis-accessibility', array(
			'404-page',
			'drop-down-menu',
			'headings',
			'rems',
			'search-form',
			'skip-links',
		) );
add_theme_support( 'post-formats', array(
	'aside',
	'audio',
	'chat',
	'gallery',
	'image',
	'link',
	'quote',
	'status',
	'video'
) );
add_theme_support( 'genesis-menus', array(
	'primary' => 'Primary Navigation Menu',
	'secondary' => 'Secondary Navigation Menu',
	'footer_menu' => 'Footer Menu'
) );
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'menu-primary',
	'menu-secondary',
	'lander-nav-above',
	'lander-nav-below',
	'lander-nav-footer',
	'inner',
	'after-header-first',
	'after-header-second',
	'after-header-third',
	'before-footer-first',
	'before-footer-second',
	'before-footer-third',
	'footer-widgets',
	'footer'
) );

add_action( 'init', 'lander_init_post_types', 10 );
remove_action( 'wp_head', '_ak_framework_meta_tags' );
add_action( 'wp_head', 'lander_set_schema' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav', 2 );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content_sidebar_wrap', 'lander_do_breadcrumbs' );
add_action( 'genesis_entry_content', 'show_read_more_on_excerpt' );
add_action( 'genesis_footer', 'lander_do_nav_footer', 5 );
add_action( 'wp_enqueue_scripts', 'lander_enqueue_jquery' );
add_action( 'wp_enqueue_scripts', 'lander_res_menu_output' );
add_action( 'wp_enqueue_scripts', 'lander_retina_script' );
add_action( 'wp_enqueue_scripts', 'lander_scripts' );

add_filter( 'genesis_seo_title', 'lander_title', 10, 3 );
add_filter( 'genesis_seo_description', 'lander_desc', 10, 3 );
add_filter( 'edit_post_link', 'lander_post_edit_link' );
add_filter( 'genesis_attr_lander-nav-above', 'genesis_attributes_nav' );
add_filter( 'genesis_attr_lander-nav-below', 'genesis_attributes_nav' );
add_filter( 'genesis_attr_lander-nav-footer', 'genesis_attributes_nav' );
add_filter( 'post_class', 'lander_post_classes' );
add_filter( 'genesis_prev_link_text', 'lander_previous_page_link' );
add_filter( 'genesis_next_link_text', 'lander_next_page_link' );
add_filter( 'genesis_post_date_shortcode', 'lander_post_date_shortcode' );
add_filter( 'genesis_footer_genesis_link_shortcode', 'lander_genesis_link_output', 10, 2 );
add_filter( 'genesis_footer_creds_text', 'lander_footer_creds' );
add_filter( 'wp_nav_menu', 'lander_nav_trim', 10, 2 ); // Remove spaces between html markup to support inline-block style.
add_filter( 'widget_text', 'do_shortcode' ); // Enable shortcodes support in widgets
add_filter( 'comment_author_says_text', '__return_false' ); // Enable shortcodes support in widgets

add_editor_style( 'editor-style.css' );

//unregister_sidebar( 'header-right' );

add_action( 'widgets_init', 'lander_register_sidebars' );
//add_action( 'after_setup_theme', 'lander_register_default_widget_areas' );
add_action( 'wp_head', 'lander_widgetize', 5 );

/**
 * Add post types for the theme features
 * @return none
 */
function lander_init_post_types() {
	add_post_type_support( 'page', 'lander-schema' );
	add_post_type_support( 'page', 'lander-landing-page-experience' );
	add_post_type_support( 'page', 'lander-mobile-experience' );
	add_post_type_support( 'page', 'lander-custom-menu-locations' );
	add_post_type_support( 'page', 'lander-landing-sections' );
	add_post_type_support( 'page', 'lander-custom-layout-styles' );
	add_post_type_support( 'post', 'lander-schema' );
	add_post_type_support( 'post', 'lander-landing-page-experience' );
	add_post_type_support( 'post', 'lander-mobile-experience' );
	add_post_type_support( 'post', 'lander-custom-menu-locations' );
	add_post_type_support( 'post', 'lander-landing-sections' );
	add_post_type_support( 'post', 'lander-custom-layout-styles' );
}

/**
 * Output the custom widget areas on the front-end
 * @return none
 */
function lander_widgetize() {
	add_action( 'genesis_before_header', 'lander_sidebar_before_header' );
	add_action( 'genesis_after_header', 'lander_sidebar_after_header' );
	add_action( 'genesis_before_footer', 'lander_sidebar_above_footer', 5 );
}