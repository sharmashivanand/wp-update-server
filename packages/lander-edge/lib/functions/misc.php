<?php

/**
 * Returns the new design settings merged with the old design settings so ensure no setting is left blank
 * @param array $settings 
 * @return none or array $settings
 * @since 1.0
 */
function lander_back_compat( $settings = array() ) {
	if ( !is_array( $settings ) || empty( $settings ) ) {
		return;
	}
	$lander_design_defaults = lander_design_defaults();
	$updated                = wp_parse_args( $settings, $lander_design_defaults );
	return $updated;
}


/**
 * Enqueues settings.css, debug.css (if current user can manage options, google web fonts)
 * @return none
 * @since 1.0
 */
function lander_settings_css() {
	
	if ( @lander_get_res( 'file', 'settingscss' ) ) {
		wp_register_style( 'lander-settings-style', lander_get_res( 'fileurl', 'settingscss' ), array(), is_user_logged_in() ? microtime() : null, 'all' );
	}

	if ( is_admin() || current_user_can( 'manage_options' ) ) {
		wp_enqueue_style( 'lander-debug-style', lander_get_res( 'dirurl' ) . 'debug.css', array(), microtime(), 'all' );
	} else {
		wp_enqueue_style( 'lander-settings-style' );
	}

	$lander_fonts_url = genesis_get_option( 'lander-web-fonts', CHILD_SETTINGS_FIELD, false );
	if ( !empty( $lander_fonts_url ) ) {
		$protocol = is_ssl() ? 'https' : 'http';
		wp_enqueue_style( 'lander-fonts', $protocol . '://fonts.googleapis.com/css?family=' . $lander_fonts_url, array(), null, 'all' );
	}

}


/**
 * Enqueue Semantic UI styles and scripts in Lander to support Semantic UI integration
 * @return null
 * @since 1.0
 */
function lander_semantic_ui(){
	wp_enqueue_style( 'lander-semantic-ui', get_stylesheet_directory_uri() . '/lib/semantic-ui/semantic.min.css', array(), false, 'all' );
	wp_enqueue_script( 'lander-semantic-ui', get_stylesheet_directory_uri() . '/lib/semantic-ui/semantic.min.js', array(), false, true );
}


add_filter( 'genesis_export_options', 'lander_export_options' );

/**
 * Inserts Lander options for all designs plus admin settings in the Genesis import/export settings page
 * @param type $options 
 * @return type
 * @since 1.0
 */
function lander_export_options( $options ) {
	$options['design-settings'] = array(
		'label' => sprintf( __( '%s Design', CHILD_DOMAIN ), CHILD_THEME_NAME ),
		'settings-field' => CHILD_SETTINGS_FIELD
	);
	$options['admin-settings'] = array(
		'label' => sprintf( __( '%s Settings', CHILD_DOMAIN ), CHILD_THEME_NAME ),
		'settings-field' => CHILD_SETTINGS_FIELD_EXTRAS
	);
	return $options;
}


/**
 * Default admin settings for Lander
 * @return array
 * @since 1.0
 */
function lander_admin_defaults() {
	$defaults                                     = array();
	$defaults['settings-version']                 = CHILD_THEME_VERSION;
	$defaults['gfonts-api-key']                   = '';
	$defaults['footer-widgets-count']             = 3;
	$defaults['footer-widgets']                   = 1;
	$defaults['widgets_before_header_front']      = 1; //above header widget area
	$defaults['widgets_before_header_posts_page'] = 1;
	$defaults['widgets_before_header_home']       = 1;
	$defaults['widgets_before_header_post']       = 1;
	$defaults['widgets_before_header_page']       = 1;
	$defaults['widgets_before_header_archives']   = 1;
	$defaults['widgets_before_header_404']        = 1;
	$defaults['widgets_after_header_front']       = 1; //after header widget area
	$defaults['widgets_after_header_posts_page']  = 1;
	$defaults['widgets_after_header_home']        = 1;
	$defaults['widgets_after_header_post']        = 1;
	$defaults['widgets_after_header_page']        = 1;
	$defaults['widgets_after_header_archives']    = 1;
	$defaults['widgets_after_header_404']         = 1;
	$defaults['widgets_above_footer_front']       = 1; //above footer widget area
	$defaults['widgets_above_footer_posts_page']  = 1;
	$defaults['widgets_above_footer_home']        = 1;
	$defaults['widgets_above_footer_post']        = 1;
	$defaults['widgets_above_footer_page']        = 1;
	$defaults['widgets_above_footer_archives']    = 1;
	$defaults['widgets_above_footer_404']         = 1;
	$defaults['mlp_hide_breadcrumbs']             = 1; //Mobile template defaults
	$defaults['mlp_hide_widgets_above_header']    = 1;
	$defaults['mlp_hide_widgets_below_header']    = 1;
	$defaults['mlp_hide_widgets_above_footer']    = 1;
	$defaults['mlp_hide_sidebars']                = 0;
	$defaults['mlp_hide_fwidgets']                = 0;
	$defaults['widget-default-content-enabled']   = 1; //Debug options
	$defaults['custom-functions-enabled']         = 1; //Debug options
	$defaults['custom-style-enabled']             = 1;

	return apply_filters( 'lander_admin_defaults', $defaults );
}


/**
 * Default design settings for all Designs
 * @return array
 * @since 1.0
 * ***NOTE: All font-family settings must have the string "font-family" in the setting name. Lander uses it to detect & validate font-family related settings, detect web fonts and enqueue them
 */

function lander_design_defaults() {
	$defaults = array();
	$defaults['design-version']                 			= CHILD_THEME_VERSION;
	$defaults['layout']										= 'fullwidth';
	if ( function_exists( 'bbpress' ) ) {
		$defaults['bbpress-layout'] = 'default';
	}

	$defaults['col-spacing']                                = '40';
	$defaults['column-content-1col']                        = '960';
	$defaults['column-content-2col']                        = '670';
	$defaults['sidebar-one-2col']                           = '250';
	$defaults['column-content-3col']                        = '480';
	$defaults['sidebar-one-3col']                           = '200';
	$defaults['sidebar-two-3col']                           = '200';
	
	$defaults['site-background-color']                      = '#eee';
	$defaults['page-background-color']                      = '#ffffff';
	
	$defaults['body-font-family']                           = 'times_new_roman';
	$defaults['body-font-weight']                           = '300';
	$defaults['body-font-size']                             = '16';
	$defaults['primary-text-color']                         = '#000';
	$defaults['primary-link-color']                         = '#c00';
	$defaults['primary-link-hover-color']                   = '#a00';
	$defaults['form-font-family']                           = $defaults['body-font-family'];
	$defaults['form-text-color']                            = '#000';
	$defaults['form-font-size']                             = 'inherit';
	
	$defaults['site-title-font-color']                      = '#000';
	$defaults['site-title-font-size']                       = '72';
	$defaults['site-title-font-family']                     = 'inherit';
	$defaults['site-title-font-weight']                     = 'inherit';
	$defaults['site-description-font-color']                = '#000';
	$defaults['site-description-font-size']                 = 'inherit';
	$defaults['site-description-font-family']               = 'inherit';
	$defaults['site-description-font-weight']               = 'inherit';
	
	$defaults['nav-menu-font-family']                       = 'inherit';
	$defaults['nav-menu-font-weight']                       = 'inherit';
	$defaults['nav-menu-font-size']                         = 'inherit';
	$defaults['nav-menu-link-text-color']                   = '#000000';
	$defaults['nav-menu-link-text-hover-color']             = '#cc0000';
	$defaults['nav-menu-current-link-text-color']           = '#cc0000';
	$defaults['nav-menu-current-parent-link-text-color']    = '#cc0000';
	$defaults['nav-menu-link-bg-color']                     = '#ffffff';
	$defaults['nav-menu-hover-bg-color']                    = '#ffffff';
	$defaults['nav-menu-current-bg-color']                  = '#ffffff';
	$defaults['nav-menu-current-parent-bg-color']           = '#ffffff';
	$defaults['nav-menu-submenu-width']                     = '200';
	
	$defaults['subnav-menu-font-family']                    = 'inherit';
	$defaults['subnav-menu-font-weight']                    = 'inherit';
	$defaults['subnav-menu-font-size']                      = 'inherit';
	$defaults['subnav-menu-link-text-color']                = '#000000';
	$defaults['subnav-menu-link-text-hover-color']          = '#cc0000';
	$defaults['subnav-menu-current-link-text-color']        = '#cc0000';
	$defaults['subnav-menu-current-parent-link-text-color'] = '#cc0000';
	$defaults['subnav-menu-link-bg-color']                  = '#ffffff';
	$defaults['subnav-menu-hover-bg-color']                 = '#ffffff';
	$defaults['subnav-menu-current-bg-color']               = '#ffffff';
	$defaults['subnav-menu-current-parent-bg-color']        = '#ffffff';
	$defaults['subnav-menu-submenu-width']                  = '200';
	
	$defaults['headline-font-family']                       = 'inherit';
	$defaults['headline-font-weight']                       = '700';
	$defaults['headline-font-color']                        = '#000';
	$defaults['headline-font-size']                         = '28';
	$defaults['headline-subhead-font-color']                = '#000';
	$defaults['headline-subhead-font-family']               = 'inherit';
	$defaults['headline-subhead-font-weight']               = '700';
	
	$defaults['byline-font-family']                         = 'inherit';
	$defaults['byline-font-weight']                         = '300';
	$defaults['byline-font-color']                          = '#000';
	$defaults['byline-font-size']                           = 'inherit';
	
	$defaults['sidebar-heading-font-family']                = 'inherit';
	$defaults['sidebar-heading-font-weight']                = 'regular';
	$defaults['sidebar-heading-font-size']                  = '18';
	$defaults['sidebar-heading-font-color']                 = '#000';
	$defaults['sidebar-font-family']                        = 'inherit';
	$defaults['sidebar-font-weight']                        = '300';
	$defaults['sidebar-font-color']                         = '#000';
	$defaults['sidebar-font-size']                          = '14';
	
	$defaults['footer-widgets-heading-font-family']         = 'inherit';
	$defaults['footer-widgets-heading-font-weight']         = '700';
	$defaults['footer-widgets-heading-font-size']           = '18';
	$defaults['footer-widgets-heading-font-color']          = '#000';
	$defaults['footer-widgets-font-family']                 = 'inherit';
	$defaults['footer-widgets-font-weight']                 = '300';
	$defaults['footer-widgets-font-color']                  = '#000';
	$defaults['footer-widgets-font-size']                   = '14';
	
	$defaults['footer-font-family']                         = 'inherit';
	$defaults['footer-font-weight']                         = 'inherit';
	$defaults['footer-font-color']                          = '#888888';
	$defaults['footer-font-size']                           = 'inherit';
	$defaults['lander-web-fonts']							= 'Roboto:300|Italianno:regular|Roboto|Italianno:regular|Roboto:300|Roboto+Condensed:regular|Roboto+Condensed:regular|Roboto:300|Roboto:300|Roboto:300|Roboto:300|Roboto+Condensed:regular|Roboto:300|Roboto+Condensed:regular|Roboto:regular';

	$defaults = apply_filters( 'lander_design_defaults', $defaults );

	return $defaults;
}


/**
 * Outputs the Lander footer menu.
 * @return none
 * @since 1.0
 */
function lander_do_nav_footer() {
	if ( !genesis_nav_menu_supported( 'footer_menu' ) ) {
		return;
	}
	if ( has_nav_menu( 'footer_menu' ) ) {
		$class = 'menu lander-menu-footer';
		if ( genesis_superfish_enabled() ) {
			$class .= ' js-superfish';
		}
		$args = array(
			'theme_location' => 'footer_menu',
			'container' => '',
			'menu_class' => $class,
			'depth' => 1, // Allows only parent items to be included in the menu
			'echo' => 0
		);
		$nav  = wp_nav_menu( $args );
		if ( !$nav ) {
			return;
		}
		$nav_markup_open  = genesis_markup( array(
				'html5' => '<nav %s>',
				'xhtml' => '<div id="lander-nav-footer menu">',
				'context' => 'lander-nav-footer',
				'echo' => false
			) );
		$nav_markup_close = genesis_html5() ? '</nav>' : '</div>';
		$nav_output       = $nav_markup_open . $nav . $nav_markup_close;
		echo apply_filters( 'lander_do_nav_footer', $nav_output, $nav, $args );
	}
}

/**
 * Wraps the footer output in a div with the classname creds for styling?
 * @param string $output 
 * @param string $backtotop 
 * @param string $creds 
 * @return string
 * @since 1.0
 */
function lander_footer_output( $output, $backtotop, $creds ) {
	return '<div class="creds">' . $output . '</div>';
}

/**
 * Modifies the Genesis attribute anchor text slightly for better readability.
 * @param string $output 
 * @param array? $atts 
 * @return string
 * @since 1.0
 */
function lander_genesis_link_output( $output, $atts ) {
	$output = str_replace( "Genesis Framework</a>", "Genesis</a>", $output );
	return $output;
}

/**
 * Inserts a copyright notice and site ownership in the footer.
 * @param string $creds_text 
 * @return string
 * @since 1.0
 */
function lander_footer_creds( $creds_text ) {
	$lander_footer_creds = __( 'Copyright', CHILD_DOMAIN ) . ' &copy; ' . date( 'Y' ) . ' &mdash; <a title="' . get_bloginfo( 'name' ) . '" href="' . get_site_url() . '">' . get_bloginfo( 'name' ) . '</a>&nbsp;&bull;&nbsp;' . __( 'All Rights Reserved', CHILD_DOMAIN ) . '&nbsp;&bull;&nbsp;' . __( 'Powered by', CHILD_DOMAIN ) . '&nbsp;' . do_shortcode( '[footer_childtheme_link before=""]' ) . '&nbsp;on&nbsp;' . do_shortcode( '[footer_genesis_link]' ) . '.';
	
	return apply_filters( 'lander_footer_creds', $lander_footer_creds );
}


/**
 * Inserts certain body classes for styling purposes
 * @param array $classes 
 * @return array
 * @since 1.0
 */
function lander_body_classes( $classes ) {
	$classes[]      = genesis_get_option( 'layout', CHILD_SETTINGS_FIELD, false );
	$classes[]      = CHILD_THEME_NAME_WS;
	$footer_widgets = genesis_get_option( 'footer-widgets-count', CHILD_SETTINGS_FIELD_EXTRAS, false );
	$classes[]      = 'footer-' . $footer_widgets;

	if ( is_front_page() && ! is_home() ) {
		$classes[] = 'front-page'; // Thanks Justin Tadlock / Hybrid Core / GPL
	}

	if(is_home() || is_archive() || is_search()){
		$classes[] = 'plural'; // Thanks Justin Tadlock / Hybrid Core / GPL
	}
	if ( lander_is_mobile() ) {
		$classes[] = CHILD_THEME_NAME_WS . '-mobile-viewport';
	}

	return $classes;
}


/**
 * Modify the active body classes for layout styles depending on per page layout set by the user
 */
function lander_set_page_custom_layout( $classes ) {
	global $post;
	$selected_page_layout = get_post_meta( $post->ID, '_lander_page_layout', true );
	$active_site_layout = genesis_get_option( 'layout', CHILD_SETTINGS_FIELD, false );
	
	if( empty( $selected_page_layout ) )
		return $classes;
	
	// Unset the current active layout class
	if( !empty( $active_site_layout ) ) {
		foreach( $classes as $index => $value ) {
			if( $value == $active_site_layout )
				unset( $classes[$index] );
		}
	}

	// Set the user selected layout class
	$classes[] = $selected_page_layout;
	
	return $classes;
}


/**
 * Inserts certain body classes to Lander Admin pages for styling purposes
 * @param array $classes 
 * @return array
 * @since 1.0
 */
function lander_admin_body_classes( $classes ) {

	$screen    = get_current_screen();
	$screen_id = $screen->base;

	$lander_admin_page  = 'genesis_page_' . CHILD_SETTINGS_FIELD_EXTRAS;
	$lander_design_page = 'genesis_page_' . CHILD_SETTINGS_FIELD;

	if ( $screen_id == $lander_admin_page || $screen_id == $lander_design_page ) {
		$classes .= 'lander-framework';
	}

	return $classes;

}

/**
 * Enqueues jQuery
 * @return type
 * @since 1.0
 */
function lander_enqueue_jquery() {
	wp_enqueue_script( 'jquery' );
}


/**
 * Enqueues the lander scripts js
 * @return none
 * @since 1.0
 */
function lander_scripts() {
	wp_enqueue_script( 'lander-scripts', get_stylesheet_directory_uri() . '/lib/js/lander-scripts.js', array(
			'jquery'
		), '1.0.0', true );
}


/**
 * Enqueues the responsive menu js
 * @return none
 * @since 1.0
 */
function lander_res_menu_output() {
	wp_enqueue_script( 'lander-res-menu-output', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array(
			'jquery'
		), '1.0.0', true );
}


/**
 * Enqueues the retina js
 * @return none
 * @since 1.0
 */
function lander_retina_script() {
	wp_enqueue_script( 'lander-retina-script', get_stylesheet_directory_uri() . '/lib/js/retina.min.js', array(
			'jquery'
		), '1.0.0', true );
}

/**
 * Inserts the schema to the frontend
 * @return none
 * @since 1.0
 */
function lander_set_schema() {
	if ( !is_single() && !is_page() )
		return;
	global $post;
	$schema = get_post_meta( $post->ID, '_lander_schema', 1 );
	if ( $schema == 'none' )
		return;
	if ( !empty( $schema ) ) {
		$schema  = lander_get_schema( $schema );
		$context = $schema['context'];
		add_filter( 'genesis_attr_' . $context, 'lander_attributes_node' );
	}
}

/**
 * Inserts the schema to the frontend
 * @return none
 * @since 1.0
 */
function lander_attributes_node( $attributes ) {
	global $post;
	$saved_schema            = get_post_meta( $post->ID, '_lander_schema', 1 );
	$schema                  = lander_get_schema( $saved_schema );
	$attributes['itemtype']  = $schema['itemtype'];
	$attributes['itemscope'] = 'itemscope';
	return $attributes;
}

/**
 * Inserts a read more link if excerpts are enabled on archives
 * @return none
 * @since 1.0
 */
function show_read_more_on_excerpt() {
	if ( is_singular() || ( function_exists( 'is_bbPress' ) && is_bbPress() ) ) {
		return;
	}
	if ( 'excerpts' == genesis_get_option( 'content_archive' ) || has_excerpt() ) {
		echo '<p class="more-link-excerpt"><a href="' . get_permalink() . '" class="more-link">' . __( 'Read more', CHILD_DOMAIN ) . '&hellip;</a></p>';
	}
}

/**
 * Helper function to modify the pagination text to our taste.
 * @param string $text 
 * @return string
 * @since 1.0
 */
function lander_previous_page_link( $text ) {
	return '&laquo; ' . __( 'Newer', CHILD_DOMAIN );
}

/**
 * Helper function to modify the pagination text to our taste.
 * @param string $text 
 * @return string
 * @since 1.0
 */
function lander_next_page_link( $text ) {
	return __( 'Older', CHILD_DOMAIN ) . ' &raquo; ';
}

/**
 * Linkifies the postdate to the permalink
 * @param string $date 
 * @return string
 * @since 1.0
 */
function lander_post_date_shortcode( $date ) {
	return '<a href="' . get_permalink() . '" title="Permalink">' . $date . '</a>';
}

/**
 * Inserts the .sticky post class for the sticky posts.
 * @param array $classes 
 * @return array
 * @since 1.0
 */
function lander_post_classes( $classes ) {
	global $post;
	if ( is_sticky( $post->ID ) && !is_singular() ) {
		$classes[] = "sticky";
	}
	return $classes;
}

function lander_add_inpost_layout_box() {
	if ( !current_theme_supports( 'genesis-inpost-layouts' ) )
		return;
	foreach ( (array) get_post_types( array(
				'public' => true
			) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-layouts' ) )
			add_meta_box( 'genesis_inpost_layout_box', __( 'Layout Settings', CHILD_DOMAIN ), 'genesis_inpost_layout_box', $type, 'normal', 'default' );
	}
}

/**
 * Disables theme update check for Lander from the WP repo
 * @param array $r 
 * @param string $url 
 * @return array
 * @since 1.0
 */
function lander_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r;
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[get_option( 'template' )] );
	unset( $themes[get_option( 'stylesheet' )] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

/**
 * Enqueues the master style.css
 * @return none
 * @since 1.0
 */
function lander_user_css() {
	if ( !genesis_get_option( 'custom-style-enabled', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
		return;
	}
	$usercss = lander_get_res( 'file', 'usercss' );
	if ( @file_exists( $usercss ) ) {
		wp_register_style( 'lander-user-style', lander_get_res( 'fileurl', 'usercss' ), array(), is_user_logged_in() ? microtime() : null, 'all' );
	}
	if ( !is_admin() ) {
		wp_enqueue_style( 'lander-user-style' );
	}
}


/**
 * Enqueues the design specific css
 * @return none
 * @since 1.0
 */
function lander_customizations_css() {
	wp_enqueue_style( 'lander-customization-style', LANDER_CORE_DESIGNS_URL . '/autogenerated.css'  );
}

/**
 * Beautifully formated var_dump for developers for troubleshooting purposes. Makes life easy by showing the variable/object/array's gory details.
 * @param array/object/variable/etc $text 
 * @param bool $echo 
 * @return none || string
 * @since 1.0
 */
function llog( $text, $echo = true ) {
	if ( $echo ) {
		echo '<pre>';
		var_dump( $text );
		echo '</pre>';
	} else {
		ob_start();
		var_dump( $text );
		$result = ob_get_clean();
		return '<pre>' . $result . '</pre>';
	}
}


/**
 * Beautifully formatted var_dump for developers for troubleshooting purposes. Makes life easy by WRITING the variable/object/array's gory details into ABSPATH . "lander-log.txt"
 * @param array/object/variable/etc $text 
 * @return none
 * @since 1.0
 */
function llogfile( $text = 'hello' ) {
	$out = llog( $text, false );
	file_put_contents( ABSPATH . "lander-log.txt", $out . PHP_EOL, FILE_APPEND );
}


/**
 * Disable the activation redirection when Lander is in deployment mode.
 * @return none
 * @since 1.0
 */
function lander_redir_settings() {
	if ( current_theme_supports( 'lander-deploy' ) || current_theme_supports( 'lander-admin-deploy' ) )
		return;

	if ( isset( $_GET['activated'] ) ) {
		genesis_admin_redirect( CHILD_SETTINGS_FIELD, array( 'installed' => 'true' ) );
		exit;
	}
}

/**
 * Helper function that forces full-width layout
 * @param string $layout 
 * @return string
 * @since 1.0
 */
function lander_force_full_width_layout( $layout ) {
	$layout = 'full-width-content';
	return $layout;
}


/**
 * Helper function that hides the breadcrumbs when hidden in the options panel
 * @return bool
 * @since 1.0
 */
function lander_hide_breadcrumbs() {
	return false;
}


/**
 * Adds 'lander-no-bcrumb' class to <body> so that the breadcrumbs can be visually hidden
 * Breadcrumbs being important for SEO, should stay in the page markup, hence we'd disable them visually on the site if user chooses to do so
 * @param array $classes
 * @return array $classes
 * @since 1.1.1
 */
function lander_seo_hide_breadcrumbs( $classes ) {
	$no_bcrumb_class = 'lander-no-bcrumb';
	$classes[] =  esc_attr( sanitize_html_class( $no_bcrumb_class ) );
	
	return $classes;
}


/**
 * Inserts the hide-title class into the post that enables us to hide post title via CSS
 * @param array $classes 
 * @return array
 * @since 1.0
 */
function lander_hide_title_class( $classes ) {
	if ( in_the_loop() ) {
		$new_class = 'hide-title';
		$classes[] = esc_attr( sanitize_html_class( $new_class ) );
	}
	return $classes;
}


/**
 * Replaces bozo brackets with attitude brackets in the post edit link on the front-end
 * @param string $link 
 * @return string
 * @since 1.0
 */
function lander_post_edit_link( $link ) {
	$link = str_replace( '(', '[', $link );
	$link = str_replace( ')', ']', $link );
	return $link;
}

/**
 * Helper function that converts any string into a websafe string
 * @param string $str 
 * @return string
 */
function lander_ws( $str ) {
	return preg_replace( "/[\/_| -]+/u", '-', strtolower( trim( preg_replace( "/[^a-zA-Z0-9\/_| -]/u", '', $str ), '-' ) ) );
}

/**
 * Changes Site Name to H2 (Genesis does an H1 by default). Bozo?
 * @param type $title 
 * @param type $inside 
 * @param type $wrap 
 * @return type
 * @since 1.0
 */
function lander_title( $title, $inside, $wrap ) {
	$tag   = is_home() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
	$tag   = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h2' : $tag;
	$title = preg_replace( "/<$wrap\s(.+?)>(.+?)<\/$wrap>/is", "<$tag $1>$2</$tag>", $title );
	return $title;
}

/**
 * Changes Site Description to H1 (Genesis does an H2 by default). Bozo?
 * @param type $title 
 * @param type $inside 
 * @param type $wrap 
 * @return type
 * @since 1.0
 */
function lander_desc( $description, $inside, $wrap ) {
	$tag         = is_home() && 'description' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
	$tag         = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $tag;
	$description = preg_replace( "/<$wrap\s(.+?)>(.+?)<\/$wrap>/is", "<$tag $1>$2</$tag>", $description );
	return $description;
}


/**
 * Outputs Lander widget area before header
 * @return none
 * @since 1.0
 */
function lander_sidebar_before_header() {

	$show = ( ( ( 'posts' === get_option( 'show_on_front' ) && is_home() ) && genesis_get_option( 'widgets_before_header_home', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( 'page' === get_option( 'show_on_front' ) && is_front_page() ) && genesis_get_option( 'widgets_before_header_front', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( 'page' === get_option( 'show_on_front' ) && is_home() ) && genesis_get_option( 'widgets_before_header_posts_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( is_single() && genesis_get_option( 'widgets_before_header_post', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( is_page() && !is_front_page() ) && genesis_get_option( 'widgets_before_header_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( is_archive() || is_search() ) && genesis_get_option( 'widgets_before_header_archives', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( is_404() && genesis_get_option( 'widgets_before_header_404', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) );
	$show = apply_filters( 'show_widgets_before_header', $show );
	if ( $show ) {
		if ( is_active_sidebar( 'before-header' ) ) {
			echo '<div class="lander-sb-before-header"><div class="wrap lander-sb-wrap lander-sb-before-header-wrap">';
			genesis_widget_area( 'before-header' );
			echo '</div></div>';
		} else {
			if ( current_user_can( 'edit_theme_options' ) ) {
				$lander_show_default_widget_content = apply_filters( 'widget_default_content_enabled', genesis_get_option( 'widget-default-content-enabled', CHILD_SETTINGS_FIELD_EXTRAS, false ) );
				if ( $lander_show_default_widget_content ) {
					echo '<div class="lander-sb-before-header"><div class="wrap lander-sb-wrap lander-sb-before-header-wrap">';
					genesis_default_widget_area_content( __( 'Before Header Widget Area', CHILD_DOMAIN ) );
					echo '</div></div>';
				}
			}
		}
	}
}

/**
 * Outputs Lander widget area after header
 * @return none
 * @since 1.0
 */
function lander_sidebar_after_header() {
	$show = ( ( ( 'posts' === get_option( 'show_on_front' ) && is_home() ) && genesis_get_option( 'widgets_after_header_home', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( 'page' === get_option( 'show_on_front' ) && is_front_page() ) && genesis_get_option( 'widgets_after_header_front', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( 'page' === get_option( 'show_on_front' ) && is_home() ) && genesis_get_option( 'widgets_after_header_posts_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( is_single() && genesis_get_option( 'widgets_after_header_post', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( is_page() && !is_front_page() ) && genesis_get_option( 'widgets_after_header_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( is_archive() || is_search() ) && genesis_get_option( 'widgets_after_header_archives', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( is_404() && genesis_get_option( 'widgets_after_header_404', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) );

	$show = apply_filters( 'show_widgets_after_header', $show );

	if ( $show ) {
		if ( is_active_sidebar( 'after-header' ) ) {
			echo '<div class="lander-sb-after-header"><div class="wrap lander-sb-wrap lander-sb-after-header-wrap">';
			genesis_widget_area( 'after-header' );
			echo '</div></div>';
		} else {
			if ( current_user_can( 'edit_theme_options' ) ) {
				$lander_show_default_widget_content = apply_filters( 'widget-default-content-enabled', genesis_get_option( 'widget-default-content-enabled', CHILD_SETTINGS_FIELD_EXTRAS, false ) );
				if ( $lander_show_default_widget_content ) {
					echo '<div class="lander-sb-after-header"><div class="wrap lander-sb-wrap lander-sb-after-header-wrap">';
					genesis_default_widget_area_content( __( 'After Header Widget Area', CHILD_DOMAIN ) );
					echo '</div></div>';
				}
			}
		}
	}
}


/**
 * Outputs Lander widget area above footer
 * @return none
 * @since 1.0
 */
function lander_sidebar_above_footer() {
	$show = ( ( ( 'posts' === get_option( 'show_on_front' ) && is_home() ) && genesis_get_option( 'widgets_above_footer_home', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( 'page' === get_option( 'show_on_front' ) && is_front_page() ) && genesis_get_option( 'widgets_above_footer_front', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( 'page' === get_option( 'show_on_front' ) && is_home() ) && genesis_get_option( 'widgets_above_footer_posts_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( is_single() && genesis_get_option( 'widgets_above_footer_post', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( is_page() && !is_front_page() ) && genesis_get_option( 'widgets_above_footer_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( ( is_archive() || is_search() ) && genesis_get_option( 'widgets_above_footer_archives', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) || ( is_404() && genesis_get_option( 'widgets_above_footer_404', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) );
	$show = apply_filters( 'show_widgets_above_footer', $show );
	if ( $show ) {
		if ( is_active_sidebar( 'above-footer' ) ) {
			echo '<div class="lander-sb-above-footer"><div class="wrap lander-sb-wrap lander-sb-above-footer-wrap">';
			genesis_widget_area( 'above-footer' );
			echo '</div></div>';
		} else {
			if ( current_user_can( 'edit_theme_options' ) ) {
				$lander_show_default_widget_content = apply_filters( 'widget-default-content-enabled', genesis_get_option( 'widget-default-content-enabled', CHILD_SETTINGS_FIELD_EXTRAS, false ) );
				if ( $lander_show_default_widget_content ) {
					echo '<div class="lander-sb-above-footer"><div class="wrap lander-sb-wrap lander-sb-above-footer-wrap">';
					genesis_default_widget_area_content( __( 'Above Footer Widget Area', CHILD_DOMAIN ) );
					echo '</div></div>';
				}
			}
		}
	}
}

/**
 * Registers all the custom widget areas
 * @return none
 * @since 1.0
 */
function lander_register_sidebars() { 

	genesis_register_widget_area( array('id' => 'before-header', 'name' => 'Before Header', 'description' => __( 'These widgets will show up above the header.', CHILD_DOMAIN ) ) );
	genesis_register_widget_area( array('id' => 'after-header', 'name' => 'After Header', 'description'      => __( 'These widgets will show up below the header.', CHILD_DOMAIN ) ) );
	genesis_register_widget_area( array('id' => 'above-footer', 'name' => 'Before Footer', 'description'      => __( 'These widgets will show up above the footer or the footer widgets if they are enabled.', CHILD_DOMAIN ) ) );

	//add_action('after_setup_theme' , 'lander_register_default_widget_areas');
}

function lander_register_default_widget_areas() {

	global $wp_registered_sidebars;

	if ( isset( $wp_registered_sidebars['before-header'] ) ) {
		genesis_register_widget_area(
			array(
				'id'               => 'before-header',
				'name'             =>__( 'Before Header', CHILD_DOMAIN ),
				'description'      => __( 'These widgets will show up above the header.', CHILD_DOMAIN )
			)
		);
	}

	if ( isset( $wp_registered_sidebars['after-header'] ) ) {
		genesis_register_widget_area(
			array(
				'id'               => 'after-header',
				'name'             =>__( 'After Header', CHILD_DOMAIN ),
				'description'      => __( 'These widgets will show up below the header.', CHILD_DOMAIN )
			)
		);
	}

	if ( isset( $wp_registered_sidebars['above-footer'] ) ) {
		genesis_register_widget_area(
			array(
				'id'               => 'above-footer',
				'name'             =>__( 'Above Footer', CHILD_DOMAIN ),
				'description'      => __( 'These widgets will show up above the footer or the footer widgets if they are enabled.', CHILD_DOMAIN )
			)
		);
	}

	

}
/**
 * Spaces in the nav HTML markup don't allow us to style the nav correctly using inline-block style. Removes such spaces.
 * @param type $markup 
 * @param type $args 
 * @return type
 * @since 1.0
 */
function lander_nav_trim( $markup, $args ) {
	return preg_replace( '~>\s+<~', '><', $markup );
}


add_filter( 'genesis_post_meta', 'lander_post_meta_filter' );

/**
 * Helper function to remove the extra markup before post categories and post tags
 * @param string $post_meta 
 * @return string
 * @since 1.0
 */
function lander_post_meta_filter( $post_meta ) {
	$post_meta = '[post_categories before=""] [post_tags before=""]';
	return $post_meta;
}


add_filter( 'genesis_toggles', 'lander_admin_toggle' );

/**
 * Add toggle ability to footer widgets option on Lander Admin screen. The footer widgets count option displays only if footer widgets are enabled
 * @param array $toggles 
 * @return array
 * @since 1.0
 */
function lander_admin_toggle( $toggles ) {
	$toggles['footer-widgets'] = array(
		'#lander-framework-admin\\[footer-widgets\\]',
		'#fwidget-count',
		'1'
	);
	return $toggles;
}


/**
 * Helper function that can be called to check if we are on a mobile viewport
 * @return bool
 * @since 1.0
 */
function lander_is_mobile() {
	return apply_filters( 'lander_is_mobile', wp_is_mobile() );
}


/** Add theme support for Genesis Footer Widgets dynamically. **/
$footer_widgets      = genesis_get_option( 'footer-widgets', CHILD_SETTINGS_FIELD_EXTRAS, false );
$footer_widget_count = genesis_get_option( 'footer-widgets-count', CHILD_SETTINGS_FIELD_EXTRAS );
if ( $footer_widgets ) {
	add_theme_support( 'genesis-footer-widgets', $footer_widget_count );
}



/**
 * Callback for Lander Help area on Lander Admin and Lander Design screen
 * @return none
 * @since 1.0
 */
function lander_admin_sidebar() {
	
	$disable = apply_filters( 'lander_hide_admin_sb', false );
	if ( $disable !== false ) {
		return $disable;
	}

	?>
		<!-- Lander Help Area -->
		
		<div class="lander-sidebar">
			<div class="lander-help-area section">
			<h3><?php _e( 'Lander Help &amp; Support', CHILD_DOMAIN ); ?></h3>
			<div class="lander-postbox-wrapper">
			<ul>
			<!-- Documentation to get started with Lander -->
			<li><span class="dashicons dashicons-lightbulb"></span><a class="lander-admin-help" href="https://www.binaryturf.com/?p=17953"><?php _e( 'Getting Started with Lander', CHILD_DOMAIN ); ?></a></li>
			
			<!-- Documentation to help with usage -->
			<li><span class="dashicons dashicons-editor-help"></span><a class="lander-admin-help" href="https://www.binaryturf.com/?p=17950"><?php _e( 'Lander Docs &amp; References', CHILD_DOMAIN ); ?></a></li>

			<!-- Report a bug -->
			<li><span class="dashicons dashicons-flag"></span><a class="lander-admin-help" href="https://www.binaryturf.com/?p=17940"><?php _e( 'Report a Bug', CHILD_DOMAIN ); ?></a></li>
			
			<!-- Showcase your site built on Lander -->
			<li><span class="dashicons dashicons-twitter"></span><a class="lander-admin-help" href="https://twitter.com/intent/tweet?text=Here%27s+my+new+design+built+on+Lander+&micro;Framework:&nbsp;&amp;url=<?php echo get_site_url(); ?>&amp;hashtags=landerwp&amp;via=shivanandwp" target="_blank"><?php _e( 'Tweet us your new design', CHILD_DOMAIN ); ?></a></li>

			<!-- Link To Affiliate Sign-Up Form/Page
			<li><span class="dashicons dashicons-awards"></span><a class="lander-admin-help" href="https://www.binaryturf.com/"><?php # _e( 'Become Lander Affiliate', CHILD_DOMAIN ); ?></a></li>  -->
			</ul>
			</div>
			</div>
			
			<!-- Lander Testimonials Metabox -->
			
			<div class="lander-testimonial-area section">
			<h3><?php _e( 'Share Your Success Story', CHILD_DOMAIN ); ?></h3>
			<div class="lander-postbox-wrapper">
			<p><?php printf( __( 'Share you success story with Lander. And we may even feature you in our %sfeatured developers%s list!', CHILD_DOMAIN ), '<a class="lander-featured-devs" href="https://www.binaryturf.com/?p=18272">', '</a>' ); ?></p>
			<p class="lander-testimonial-cta"><a class="lander-submit-testimonial" title="Share Your Experience" href="https://www.binaryturf.com/?p=18130"><?php _e( 'Share your success story!', CHILD_DOMAIN ); ?></a></p>
			<p class="lander-sb-sm"><?php printf( __( '%s%s%s', CHILD_DOMAIN ), '<a title="Twitter" class="lander-follow-sm" href="https://twitter.com/shivanandwp"><span class="dashicons dashicons-twitter"></span></a>', '<a title="Facebook" class="lander-follow-sm" href="https://www.facebook.com/binaryturf"><span class="dashicons dashicons-facebook-alt"></span></a>', '<a title="Google+" class="lander-follow-sm" href="https://plus.google.com/+binaryturf"><span class="dashicons dashicons-googleplus"></span></a>' ); ?></p>
			</div>
			</div>
		</div>
	<?php

}


add_action( 'admin_head', 'lander_help_area_styles' );

/**
 * WordPress scheme specific styles for Lander help area
 * @return none
 * @since 1.0
 */
function lander_help_area_styles() {

	$current_color_scheme = lander_get_admin_color_scheme();

	if ( !$current_color_scheme )
		return;

	$active_colors      = $current_color_scheme->colors;
	$active_theme_color = $active_colors[1];
	$active_cta_color   = $active_colors[3];

	if ( $active_theme_color ) {
		echo '
			<style type="text/css">
				.lander-framework .lander-sidebar h3 {
					bbackground-color: ' . $active_theme_color . ';
					color: #ffffff;
				}
			</style>
		';
	}

}

/**
 * Helper function to get the admin area color scheme set by the current user.
 * @return ?
 * @since 1.0
 */
function lander_get_admin_color_scheme() {

	$current_color = get_user_option( 'admin_color' );
	global $_wp_admin_css_colors;
	$current_scheme = array();
	$colors         = $_wp_admin_css_colors;

	if ( array_key_exists( $current_color, $colors ) ) {
		$current_scheme = $colors[$current_color];
	}

	return $current_scheme;

}


/**
 * Helper function that validates a CSS usable color
 * @param string $clr 
 * @param string $def 
 * @return string
 */
function lander_validate_color( $clr, $def ) {
	$clrnames = array(
		'transparent',
		'inherit',
		'aliceblue',
		'antiquewhite',
		'aqua',
		'aquamarine',
		'azure',
		'beige',
		'bisque',
		'black',
		'blanchedalmond',
		'blue',
		'blueviolet',
		'brown',
		'burlywood',
		'cadetblue',
		'chartreuse',
		'chocolate',
		'coral',
		'cornflowerblue',
		'cornsilk',
		'crimson',
		'cyan',
		'darkblue',
		'darkcyan',
		'darkgoldenrod',
		'darkgray',
		'darkgreen',
		'darkkhaki',
		'darkmagenta',
		'darkolivegreen',
		'darkorange',
		'darkorchid',
		'darkred',
		'darksalmon',
		'darkseagreen',
		'darkslateblue',
		'darkslategray',
		'darkturquoise',
		'darkviolet',
		'deeppink',
		'deepskyblue',
		'dimgray',
		'dodgerblue',
		'firebrick',
		'floralwhite',
		'forestgreen',
		'fuchsia',
		'gainsboro',
		'ghostwhite',
		'gold',
		'goldenrod',
		'gray',
		'green',
		'greenyellow',
		'honeydew',
		'hotpink',
		'indianred',
		'indigo',
		'ivory',
		'khaki',
		'lavender',
		'lavenderblush',
		'lawngreen',
		'lemonchiffon',
		'lightblue',
		'lightcoral',
		'lightcyan',
		'lightgoldenrodyellow',
		'lightgray',
		'lightgreen',
		'lightpink',
		'lightsalmon',
		'lightseagreen',
		'lightskyblue',
		'lightslategray',
		'lightsteelblue',
		'lightyellow',
		'lime',
		'limegreen',
		'linen',
		'magenta',
		'maroon',
		'mediumaquamarine',
		'mediumblue',
		'mediumorchid',
		'mediumpurple',
		'mediumseagreen',
		'mediumslateblue',
		'mediumspringgreen',
		'mediumturquoise',
		'mediumvioletred',
		'midnightblue',
		'mintcream',
		'mistyrose',
		'moccasin',
		'navajowhite',
		'navy',
		'oldlace',
		'olive',
		'olivedrab',
		'orange',
		'orangered',
		'orchid',
		'palegoldenrod',
		'palegreen',
		'paleturquoise',
		'palevioletred',
		'papayawhip',
		'peachpuff',
		'peru',
		'pink',
		'plum',
		'powderblue',
		'purple',
		'red',
		'rosybrown',
		'royalblue',
		'saddlebrown',
		'salmon',
		'sandybrown',
		'seagreen',
		'seashell',
		'sienna',
		'silver',
		'skyblue',
		'slateblue',
		'slategray',
		'snow',
		'springgreen',
		'steelblue',
		'tan',
		'teal',
		'thistle',
		'tomato',
		'turquoise',
		'violet',
		'wheat',
		'white',
		'whitesmoke',
		'yellow',
		'yellowgreen'
	);

	$clr = strtolower( $clr );
	if ( preg_match( '/rgba?\((\s+)?\d+(\s+)?,(\s+)?\d+(\s+)?,(\s+)?\d+(\s+)?,\d*(?:\.\d+)?\)/i', $clr ) ) {
		return $clr;
	}

	// Try validating hex
	//can also replace /(#.*?)(([0-9a-f]{3}){1,2})/ with $2???
	preg_match( '/(([0-9a-f]{3}){1,2})/i', $clr, $matches );

	if ( $matches ) {
		return '#' . $matches[0];
	}

	if ( in_array( $clr, $clrnames ) ) {
		return $clr;
	}

	return $def;
}

add_action( 'genesis_after_content', 'lander_clear_csb_wrap', 10 );

/**
 * Fix the uncleared content and sidebar columns in Genesis
 * @return none
 * @since 1.0
 */
function lander_clear_csb_wrap() {
	echo '<div class="clear"></div>';
}

/**
 * Wrapper for genesis breadcrumbs functions so that the theme options have control over breacrumbs display
 * @return none
 * @since 1.0
 */
function lander_do_breadcrumbs() {

	$display_breadcrumbs = apply_filters( 'lander_do_breadcrumbs', true );

	if ( $display_breadcrumbs )
		genesis_do_breadcrumbs();

}


/**
 * Adds lander-alignnone class to content archives featured image if alignment is set as none. Helps tweak the typography/spacing with CSS
 * @param array $attributes 
 * @return array
 */
function lander_attributes_entry_image( $attributes ) {
	$alignment = genesis_get_option( 'image_alignment' );
	if ( empty( $alignment ) ) {
		$attributes['class'] = $attributes['class'] . ' lander-alignnone';
	}
	return $attributes;
}

/**
 * Returns the allowed file extensions for favicon.
 * @return array
 * @since 1.0
 */
function lander_favicon_types() {
	return apply_filters( 'lander_favicon_types', array(
			'png',
			'ico',
			'jpg',
			'gif'
		) );
}

/**
 * Returns the url of the favicon which Genesis will use. If the favicon named file doesn't exist with a supported extension then returns false and allows Genesis to use parent's favicon.
 * @return url to the favicon else false
 * @since 1.0
 */
add_filter( 'genesis_pre_load_favicon', 'lander_favicon' );

function lander_favicon( $url = '' ) {
	$icon_types = lander_favicon_types();
	$fav_dest   = lander_get_res( 'dir' ) . 'images/favicon.';

	foreach ( $icon_types as $ext ) {
		if ( file_exists( $fav_dest . $ext ) ) {
			return lander_get_res( 'dirurl' ) . 'images/favicon.' . $ext;
		}
	}

	if ( file_exists( CHILD_DIR . '/images/favicon.png' ) ) {
		return CHILD_URL . '/images/favicon.png';
	} else {
		return false;
	}
}


/**
 * Hide the admin area sidebar for the theme
 * @uses lander_hide_admin_sb filter to disable the sidebar
 */
 
add_filter( 'lander_hide_admin_sb', '__return_true' );

/**
 * Wrapper function for woocommerce 'is_shop' function
 * @return none
 * @since 1.0
 */

function lander_is_woo_shop() {
	if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
		if( is_shop() ) {
			return true;
		}
	}
}