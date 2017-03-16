<?php

add_filter( 'genesis_available_sanitizer_filters', 'lander_bool_sanitizer' );

/**
 * Registers a new sanitizer
 * @param array $filters 
 * @return array $filters
 * @since 1.0
 */
function lander_bool_sanitizer( $filters ) {
	$filters['lander_bool_string'] = 'lander_bool_string';
	return $filters;
}

// returns a string true false always that helps us actually save the false bool settings in the db. Makes settings blindly upgradeable
/**
 * Force return of true/false so that a valid bool can be saved in the db
 * @param type $new bool setting value 
 * @param type $old bool setting value
 * @return bool
 * @since 1.0
 */
function lander_bool_string( $new, $old ) {
	if ( !$new ) {
		return "false";
	} else {
		return "true";
	}
}

add_filter( 'genesis_available_sanitizer_filters', 'lander_color_sanitizer' );

/**
 * Register sanitizer function for color validation
 * @param array $filters 
 * @return array $filters
 * @since 1.0
 */
function lander_color_sanitizer( $filters ) {
	$filters['lander_validate_color'] = 'lander_validate_color';
	return $filters;
}

add_filter( 'genesis_available_sanitizer_filters', 'lander_font_size_sanitizer' );

/**
 * Register font size sanitizer filter function
 * @param array $filters 
 * @return array $filters 
 * @since 1.0
 */
function lander_font_size_sanitizer( $filters ) {
	$filters['lander_font_size'] = 'lander_font_size';
	return $filters;
}

/**
 * Limits the min font size to 8px
 * @param string/int $new 
 * @param string/int $old 
 * @return string/int
 * @since 1.0
 */
function lander_font_size( $new, $old ) {
	return !strcasecmp($new , 'inherit')	?	'inherit' : max( 10, $new );
}

add_filter( 'genesis_available_sanitizer_filters', 'lander_min_width_sanitizer' );

/**
 * Register new sanitizer filter for layout column width
 * @param array $filters 
 * @return array $filters 
 * @since 1.0
 */
function lander_min_width_sanitizer( $filters ) {
	$filters['lander_min_width'] = 'lander_min_width';
	return $filters;
}

/**
 * Limits the min column size to 50px
 * @param string/int $new 
 * @param string/int $old 
 * @return string/int
 * @since 1.0
 */
function lander_min_width( $new, $old ) {
	return max( 50, $new );
}

/**
 * 
 * Class that registers and outputs a design options page based on the current design set in Lander Admin Settings.
 */
class Lander_Design_Settings extends Genesis_Admin_Boxes {
	private $lander_fonts;
	function __construct() {
		$page_id  = CHILD_SETTINGS_FIELD;
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => __( 'genesis', CHILD_DOMAIN ),
				'page_title' => sprintf( __( '%s Design Settings', CHILD_DOMAIN ), CHILD_THEME_NAME ),
				'menu_title' => sprintf( __( '%s Design', CHILD_DOMAIN ), CHILD_THEME_NAME )
			)
		);
		$page_ops = array(
			'screen_icon' => 'themes',
		);

		$settings_field     = CHILD_SETTINGS_FIELD;
		$this->lander_fonts = lander_get_super_fonts();
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, lander_design_defaults() );

		add_action( 'genesis_settings_sanitizer_init', array(
				$this,
				'sanitization_filters'
			) );


		add_action( 'admin_print_styles', array(
				$this,
				'styles'
			) );

	}

	function sanitization_filters() {

		genesis_add_option_filter( 'lander_min_width', $this->settings_field, array(
				'column-content-1col',
				'column-content-2col',
				'sidebar-one-2col',
				'column-content-3col',
				'sidebar-one-3col',
				'sidebar-two-3col'
			) );

		genesis_add_option_filter( 'absint', $this->settings_field, array(
				'col-spacing',
				'nav-menu-submenu-width',				
				'subnav-menu-submenu-width'
			) );

		genesis_add_option_filter( 'lander_font_size', $this->settings_field, array(
				'body-font-size',
				'form-font-size',
				'site-title-font-size',
				'site-description-font-size',
				'nav-menu-font-size',
				'subnav-menu-font-size',
				'headline-font-size',
				'byline-font-size',
				'sidebar-heading-font-size',
				'sidebar-font-size',
				'footer-widgets-heading-font-size',
				'footer-widgets-font-size',
				'footer-font-size'
			) );

		genesis_add_option_filter( 'lander_validate_color', $this->settings_field, array(
				'site-background-color',
				'page-background-color',
				'primary-text-color',
				'primary-link-color',
				'primary-link-hover-color',
				'form-text-color',
				'site-title-font-color',
				'site-description-font-color',
				'headline-font-color',
				'headline-subhead-font-color',

				'nav-menu-link-text-color',
				'nav-menu-link-text-hover-color',
				'nav-menu-current-link-text-color',
				'nav-menu-current-parent-link-text-color',
				'nav-menu-link-bg-color',
				'nav-menu-hover-bg-color',
				'nav-menu-current-bg-color',
				'nav-menu-current-parent-bg-color',
				

				'subnav-menu-link-text-color',
				'subnav-menu-link-text-hover-color',
				'subnav-menu-current-link-text-color',
				'subnav-menu-current-parent-link-text-color',
				'subnav-menu-link-bg-color',
				'subnav-menu-hover-bg-color',
				'subnav-menu-current-bg-color',
				'subnav-menu-current-parent-bg-color',
				

				'byline-font-color',
				'sidebar-heading-font-color',
				'sidebar-font-color',

				'footer-widgets-heading-font-color',
				'footer-widgets-font-color',

				'footer-font-color'
			) );

		genesis_add_option_filter( 'no_html', $this->settings_field, array(
				'bbpress-layout',

				'body-font-family',
				'form-font-family',
				'site-title-font-family',
				'site-description-font-family',
				'headline-font-family',
				'headline-subhead-font-family',
				'nav-menu-font-family',
				'subnav-menu-font-family',
				'byline-font-family',

				'sidebar-heading-font-family',
				'sidebar-font-family',

				'footer-widgets-heading-font-family',
				'footer-widgets-font-family',

				'footer-font-family',

				'body-font-weight',
				'site-title-font-weight',
				'site-description-font-weight',
				'headline-font-weight',
				'headline-subhead-font-weight',
				'nav-menu-font-weight',
				'subnav-menu-font-weight',
				'byline-font-weight',
				'sidebar-heading-font-weight',
				'sidebar-font-weight',
				'footer-widgets-heading-font-weight',
				'footer-widgets-font-weight',
				'footer-font-weight'

			) );
	}

	function metaboxes() {

		add_action( 'genesis_admin_before_metaboxes', array(
				$this,
				'hidden_fields'
			) );
		
		add_meta_box( 'lander_layout_settings_box', __( 'Layout Widths', CHILD_DOMAIN ), array(
			$this,
			'layout_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_layout_styles_box', __( 'Layout Styles', CHILD_DOMAIN ), array(
			$this,
			'layout_styles_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_b_fonts_settings_box', __( 'Body Font &amp; Globals', CHILD_DOMAIN ), array(
			$this,
			'b_font_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_site_heading_settings_box', __( 'Site Header', CHILD_DOMAIN ), array(
			$this,
			'site_header_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_primary_navmenu_settings_box', __( 'Primary Navigation Menu', CHILD_DOMAIN ), array(
			$this,
			'primary_navmenu_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_secondary_navmenu_settings_box', __( 'Secondary Navigation Menu', CHILD_DOMAIN ), array(
			$this,
			'secondary_navmenu_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_headline_settings_box', __( 'Content Headlines', CHILD_DOMAIN ), array(
			$this,
			'headline_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_byline_meta_settings_box', __( 'Byline and Post Meta Data', CHILD_DOMAIN ), array(
			$this,
			'byline_meta_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_sidebar_settings_box', __( 'Sidebars', CHILD_DOMAIN ), array(
			$this,
			'sidebar_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_footer_widgets_settings_box', __( 'Footer Widgets', CHILD_DOMAIN ), array(
			$this,
			'footer_widgets_settings_box'
		), $this->pagehook, 'main' );

		add_meta_box( 'lander_footer_settings_box', __( 'Footer', CHILD_DOMAIN ), array(
			$this,
			'footer_settings_box'
		), $this->pagehook, 'main' );

		// Add Lander Help metabox
		add_action( $this->pagehook . '_settings_page_boxes', 'lander_admin_sidebar' );

		do_action( 'lander_design_after_metaboxes', $this );

	}

	function save( $newsettings, $oldsettings ) {
		
		$newsettings['lander-web-fonts'] = $this->lander_get_fonts_url( $newsettings );
		lander_generate_css( $newsettings );
		
		if ( function_exists( 'w3tc_browsercache_flush' ) ) { //check if W3Total cache is installed and active
			w3tc_browsercache_flush(); //flush the w3tc browser cache to fetch the new settings.css
		}
		
		return $newsettings;
	
	}

	function lander_get_fonts_url( $newsettings ) {

		$url       = '';
		$body_font = lander_get_font( $newsettings['body-font-family'] );
		if ( $body_font && $body_font['font_type'] == 'google' ) {
			$url .= urlencode( $body_font['name'] );
			$url .= ':';
			$url .= $newsettings['body-font-weight'];
			$url .= '|';
		}

		$site_title_font = lander_get_font( $newsettings['site-title-font-family'] );
		if ( $site_title_font && $site_title_font['font_type'] == 'google' ) {
			$url .= urlencode( $site_title_font['name'] );
			$url .= ':';
			$url .= $newsettings['site-title-font-weight'];
			$url .= '|';
		}

		$form_font_family = lander_get_font( $newsettings['form-font-family'] );
		if ( $form_font_family && $form_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $form_font_family['name'] );
			$url .= '|';
		}

		$site_title_font_family = lander_get_font( $newsettings['site-title-font-family'] );
		if ( $site_title_font_family && $site_title_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $site_title_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['site-title-font-weight'];
			$url .= '|';
		}

		$site_description_font_family = lander_get_font( $newsettings['site-description-font-family'] );
		if ( $site_description_font_family && $site_description_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $site_description_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['site-description-font-weight'];
			$url .= '|';
		}

		$headline_font_family = lander_get_font( $newsettings['headline-font-family'] );
		if ( $headline_font_family && $headline_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $headline_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['headline-font-weight'];
			$url .= '|';
		}

		$headline_subhead_font_family = lander_get_font( $newsettings['headline-subhead-font-family'] );
		if ( $headline_subhead_font_family && $headline_subhead_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $headline_subhead_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['headline-subhead-font-weight'];
			$url .= '|';
		}

		$nav_menu_font_family = lander_get_font( $newsettings['nav-menu-font-family'] );
		if ( $nav_menu_font_family && $nav_menu_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $nav_menu_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['nav-menu-font-weight'];
			$url .= '|';
		}

		$subnav_menu_font_family = lander_get_font( $newsettings['subnav-menu-font-family'] );
		if ( $subnav_menu_font_family && $subnav_menu_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $subnav_menu_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['subnav-menu-font-weight'];
			$url .= '|';
		}

		$byline_font_family = lander_get_font( $newsettings['byline-font-family'] );
		if ( $byline_font_family && $byline_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $byline_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['byline-font-weight'];
			$url .= '|';
		}

		$sidebar_font_family = lander_get_font( $newsettings['sidebar-font-family'] );
		if ( $sidebar_font_family && $sidebar_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $sidebar_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['sidebar-font-weight'];
			$url .= '|';
		}

		$sidebar_heading_font_family = lander_get_font( $newsettings['sidebar-heading-font-family'] );
		if ( $sidebar_heading_font_family && $sidebar_heading_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $sidebar_heading_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['sidebar-heading-font-weight'];
			$url .= '|';
		}

		$footer_widgets_font_family = lander_get_font( $newsettings['footer-widgets-font-family'] );
		if ( $footer_widgets_font_family && $footer_widgets_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $footer_widgets_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['footer-widgets-font-weight'];
			$url .= '|';
		}

		$footer_widgets_heading_font_family = lander_get_font( $newsettings['footer-widgets-heading-font-family'] );
		if ( $footer_widgets_heading_font_family && $footer_widgets_heading_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $footer_widgets_heading_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['footer-widgets-heading-font-weight'];
			$url .= '|';
		}

		$footer_font_family = lander_get_font( $newsettings['footer-font-family'] );
		if ( $footer_font_family && $footer_font_family['font_type'] == 'google' ) {
			$url .= urlencode( $footer_font_family['name'] );
			$url .= ':';
			$url .= $newsettings['footer-font-weight'];
		}

		return $url;
	
	}

	function scripts() {
		
		parent::scripts();
		genesis_load_admin_js();
		wp_enqueue_script( 'farbtastic' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'lander-chosen-js', CHILD_URL . '/lib/js/chosen.jquery.min.js' );
		wp_enqueue_script( 'lander-admin-js', CHILD_URL . '/lib/js/lander-admin.js' );
		wp_localize_script( 'lander-admin-js', 'lander', array(
			'pageHook' => $this->pagehook,
			'firstTime' => !is_array( get_user_option( 'closedpostboxes_' . $this->pagehook ) ),
			'lander_fonts' => json_encode( $this->lander_fonts )
		) );

	}

	function styles() {
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'lander-chosen-style', CHILD_URL . '/lib/css/chosen.css' );
		wp_enqueue_style( 'lander-admin-style', CHILD_URL . '/lib/css/lander-admin.css' );
	}

	function hidden_fields( $pagehook ) {
		if ( $pagehook !== $this->pagehook ) {
			return;
		}
		$ff = genesis_get_option( 'lander-web-fonts', $this->settings_field, false );		
		?>
		<input type="hidden" id="<?php echo $this->settings_field; ?>[lander-web-fonts]" name="<?php echo $this->settings_field; ?>[lander-web-fonts]" value="<?php echo $ff; ?>" />
		<input type="hidden" id="<?php echo $this->settings_field; ?>[design-version]" name="<?php echo $this->settings_field; ?>[design-version]" value="<?php echo CHILD_THEME_VERSION; ?>" />
		<?php
	}

	function layout_styles_box() {
		$current = genesis_get_option( 'layout', $this->settings_field, false );
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-layout-table">
				<tr>
				<td class="gl-label"  id="label-layout">
					<p><label for="<?php
		echo $this->settings_field . '[layout]';
		?>"><?php
		_e( 'Select Layout Style', CHILD_DOMAIN );
		?></label></p>
				</td>
				<td id="layoutwidths" class="lander-input"><p>
				<?php
		echo '<input type="radio" id="' . $this->settings_field . '[layout-fullwidth]" name="' . $this->settings_field . '[layout]" value="fullwidth" ' . checked( $current, 'fullwidth', false ) . ' />';
		echo '<label id="label-layout-fullwidth" for="' . $this->settings_field . '[layout-fullwidth]">' . __( 'Full-Width', CHILD_DOMAIN ) . '</label>';
		echo '<br />';
		echo '<input type="radio" id="' . $this->settings_field . '[layout-pagewidth]" name="' . $this->settings_field . '[layout]" value="pagewidth" ' . checked( $current, 'pagewidth', false ) . ' />';
		echo '<label id="label-layout-pagewidth" for="' . $this->settings_field . '[layout-pagewidth]">' . __( 'Page-Width', CHILD_DOMAIN ) . '</label>';
?>
			</p></td>
			  </tr>
			<?php
		if ( function_exists( 'bbpress' ) ) {
?>
			<tr>
				<td colspan="2"><p class="l-desc"><strong>These settings only affect the bbPress forum pages.</strong></p></td>
				</tr>
				<tr>
				<td class="gl-label">
					<p><label for="<?php
			echo $this->settings_field . '[bbpress-layout]';
			?>"><?php
			_e( 'Layout For bbPress', CHILD_DOMAIN );
			?></label></p>
				</td>
				<td class="lander-input"><p>
					<?php
			$layouts = genesis_get_layouts();
			$current = genesis_get_option( 'bbpress-layout', $this->settings_field, false );
			echo '<select id="' . $this->settings_field . '[bbpress-layout]" name="' . $this->settings_field . '[bbpress-layout]">';
			echo '<option value="default" ' . selected( $current, 'genesis-default' ) . '>' . __( 'Genesis Default', CHILD_DOMAIN ) . '</option> ';
			foreach ( $layouts as $identifier => $keys ) {
				echo '<option value="' . esc_attr( $identifier ) . '" ' . selected( $current, esc_attr( $identifier ) ) . '>' . esc_attr( $keys['label'] ) . '</option>';
			}
			echo '</select>';
			?>		  </p></td>
			  </tr>
			  <?php
		}
?>

			  <tr>
				<td colspan="2">
					<h4 class="gl-head"><?php
		_e( 'Layout Colors', CHILD_DOMAIN );
		?></h4>
					<p class="l-desc"><?php
		_e( 'Add some personal flair to your design by using the controls below. You\'ll find even more controls in the other boxes in this section, so you\'ve got lots of ways to let your creativity flow!', CHILD_DOMAIN );
		?></p></td>
			  </tr>
			  <tr>
				<td class="gl-label">
				  <p>
					<label for="<?php
		echo $this->settings_field;
		?>[site-background-color]"><?php
		_e( 'Body Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[site-background-color]" name="<?php
		echo $this->settings_field;
		?>[site-background-color]" value="<?php
		echo genesis_get_option( 'site-background-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[page-background-color]"><?php
		_e( 'Wrap Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[page-background-color]" name="<?php
		echo $this->settings_field;
		?>[page-background-color]" value="<?php
		echo genesis_get_option( 'page-background-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			</table>
		</div>
		</div>
		  <?php
	}
	
	
	function layout_settings_box() {
		$current = genesis_get_option( 'layout', $this->settings_field, false );
		?><div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">
				<tr>
					<td colspan="3">
						<h4 class="gl-head"><?php
		_e( 'Genesis Layout Widths', CHILD_DOMAIN );
		?></h4>
						<p class="l-desc"><?php
		_e( 'You can use these settings to specify the column widths and padding between the columns across all six Genesis layouts.', CHILD_DOMAIN );
		?></p>
					</td>
				</tr>
				<tr>
					<td class="gl-label">
						<p><label for="<?php
		echo $this->settings_field;
		?>[col-spacing]"><?php
		_e( 'Padding Between Columns', CHILD_DOMAIN );
		?></label></p>
					</td>
					<td class="lander-input">
						<p><input size="3" min="10" class="col-spacing" type="number" id="<?php
		echo $this->settings_field;
		?>[col-spacing]" name="<?php
		echo $this->settings_field;
		?>[col-spacing]" value="<?php
		echo genesis_get_option( 'col-spacing', $this->settings_field, false );
		?>" />&nbsp;px</p>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<h4 class="gl-head"><?php
		_e( 'Single Column Layout', CHILD_DOMAIN );
		?></h4>
					</td>
				</tr>
				<tr>
					<td class="gl-label">
						<p><label for="<?php
		echo $this->settings_field;
		?>[column-content-1col]"><?php
		_e( 'Content Column Width', CHILD_DOMAIN );
		?></label></p>
					</td>
					<td class="lander-input"><p>
						<input size="3" min="50" class="column-content-1col" type="number" id="<?php
		echo $this->settings_field;
		?>[column-content-1col]" name="<?php
		echo $this->settings_field;
		?>[column-content-1col]" value="<?php

		echo genesis_get_option( 'column-content-1col', $this->settings_field, false );
		?>" />&nbsp;px</p>
					</td>
					<td class="tip" id="tip-one-col"><p><?php
		_e( '<strong>Tip: </strong>Make this a total of <span class="ans"></span> to keep the page-width consistent across different layouts.', CHILD_DOMAIN );
		?></p></td>
				</tr>
				<tr>
				<td colspan="3"><h4 class="gl-head"><?php
		_e( 'Two Column Layout', CHILD_DOMAIN );
		?></h4></td>
			  </tr>

			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[column-content-2col]"><?php
		_e( 'Content Column Width', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input size="3" min="50" class="column-content-2col" type="number" id="<?php
		echo $this->settings_field;
		?>[column-content-2col]" name="<?php
		echo $this->settings_field;
		?>[column-content-2col]" value="<?php
		echo genesis_get_option( 'column-content-2col', $this->settings_field, false );
		?>" />&nbsp;px</p></td>
					<td rowspan="2"  class="tip" id="tip-two-col"><p><?php
		_e( '<strong>Tip: </strong>Make this a total of <span class="ans"></span> to keep the page-width consistent across different layouts.', CHILD_DOMAIN );
		?></p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-one-2col]"><?php
		_e( 'Primary Sidebar Width', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input size="3" min="50" class="sidebar-one-2col" type="number" id="<?php
		echo $this->settings_field;
		?>[sidebar-one-2col]" name="<?php
		echo $this->settings_field;
		?>[sidebar-one-2col]" value="<?php
		echo genesis_get_option( 'sidebar-one-2col', $this->settings_field, false );
		?>" />&nbsp;px</p></td>
			  </tr>
			  <tr>
				<td colspan="3"><h4 class="gl-head"><?php
		_e( 'Three Column Layout', CHILD_DOMAIN );
		?></h4>
				</td>
			  </tr>

			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[column-content-3col]"><?php
		_e( 'Content Column Width', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input size="3" min="50" class="column-content-3col" type="number" id="<?php
		echo $this->settings_field;
		?>[column-content-3col]" name="<?php
		echo $this->settings_field;
		?>[column-content-3col]" value="<?php
		echo genesis_get_option( 'column-content-3col', $this->settings_field, false );
		?>" />&nbsp;px</p></td>
					<td rowspan="3" class="tip" id="tip-three-col" ><p><?php
		_e( '<strong>Tip: </strong>Make this a total of <span class="ans"></span> to keep the page-width consistent across different layouts.', CHILD_DOMAIN );
		?></p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-one-3col]"><?php
		_e( 'Primary Sidebar Width', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input size="3" min="50" class="sidebar-one-3col" type="number" id="<?php
		echo $this->settings_field;
		?>[sidebar-one-3col]" name="<?php
		echo $this->settings_field;
		?>[sidebar-one-3col]" value="<?php
		echo genesis_get_option( 'sidebar-one-3col', $this->settings_field, false );
		?>" />&nbsp;px</p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-two-3col]"><?php
		_e( 'Secondary Sidebar Width', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input size="3" min="50" class="sidebar-two-3col" type="number" id="<?php
		echo $this->settings_field;
		?>[sidebar-two-3col]" name="<?php
		echo $this->settings_field;
		?>[sidebar-two-3col]" value="<?php
		echo genesis_get_option( 'sidebar-two-3col', $this->settings_field, false );
		?>" />&nbsp;px</p></td>
			  </tr>
			</table>
		  </div>

		</div>
		<?php
	}

	function b_font_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">

				<tr>
					<td class="gl-label">
						<p><label for="<?php
		echo $this->settings_field . '[body-font-family]';
		?>"><?php
		_e( 'Primary Font Family', CHILD_DOMAIN );
		?></label></p>
					</td>
					<td class="lander-input">
					<p>
					<?php
		$current_font = genesis_get_option( 'body-font-family', $this->settings_field, false );
		echo '<select class="font-family body-font-family" id="' . $this->settings_field . '[body-font-family]" name="' . $this->settings_field . '[body-font-family]">';
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $current_font, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[body-font-size]"><?php
		_e( 'Primary Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[body-font-size]" name="<?php
		echo $this->settings_field;
		?>[body-font-size]" value="<?php
		echo genesis_get_option( 'body-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[body-font-weight]"><?php
		_e( 'Primary Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'body-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'body-font-weight', $this->settings_field, false );
?>
					<select class="font-weight body-font-weight" id="<?php
		echo $this->settings_field;
		?>[body-font-weight]" name="<?php
		echo $this->settings_field;
		?>[body-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[primary-text-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[primary-text-color]" name="<?php
		echo $this->settings_field;
		?>[primary-text-color]" value="<?php
		echo genesis_get_option( 'primary-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[primary-link-color]"><?php
		_e( 'Link Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[primary-link-color]" name="<?php
		echo $this->settings_field;
		?>[primary-link-color]" value="<?php
		echo genesis_get_option( 'primary-link-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>


			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[primary-link-hover-color]"><?php
		_e( 'Link Hover Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[primary-link-hover-color]" name="<?php
		echo $this->settings_field;
		?>[primary-link-hover-color]" value="<?php
		echo genesis_get_option( 'primary-link-hover-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>

			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[form-font-family]"><?php
		_e( 'Form Fields Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family form-font-family" id="' . $this->settings_field . '[form-font-family]" name="' . $this->settings_field . '[form-font-family]">';
		$fff = genesis_get_option( 'form-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $fff, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>

			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[form-font-size]"><?php
		_e( 'Form-Fields Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[form-font-size]" name="<?php
		echo $this->settings_field;
		?>[form-font-size]" value="<?php
		echo genesis_get_option( 'form-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>


			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[form-text-color]"><?php
		_e( 'Form Fields Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[form-text-color]" name="<?php
		echo $this->settings_field;
		?>[form-text-color]" value="<?php
		echo genesis_get_option( 'form-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>


			</table>
		  </div>
		</div>
	  <?php
	}

	function site_header_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">

			<tr>
				<td colspan="2"><h4 class="gl-head"><?php
		_e( 'Customize The Look Of Site-Title.', CHILD_DOMAIN );
		?></h4>
				</td>
			</tr>
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[site-title-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family site-title-font-family" id="' . $this->settings_field . '[site-title-font-family]" name="' . $this->settings_field . '[site-title-font-family]">';
		$haf = genesis_get_option( 'site-title-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $haf, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[site-title-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[site-title-font-size]" name="<?php
		echo $this->settings_field;
		?>[site-title-font-size]" value="<?php
		echo genesis_get_option( 'site-title-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			</tr>
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[site-title-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'site-title-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'site-title-font-weight', $this->settings_field, false );
?>
					<select class="font-weight site-title-font-weight" id="<?php
		echo $this->settings_field;
		?>[site-title-font-weight]" name="<?php
		echo $this->settings_field;
		?>[site-title-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[site-title-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[site-title-font-color]" name="<?php
		echo $this->settings_field;
		?>[site-title-font-color]" value="<?php
		echo genesis_get_option( 'site-title-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			</table>
		  </div>
		  		  <div class="gl-desc">
			<table class="gl-col-table">
			 <tr>
				<td colspan="2"><h4 class="gl-head"><?php
		_e( 'Customize The Look Of Site-Tagline.', CHILD_DOMAIN );
		?></h4>
				</td>
			</tr><tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[site-description-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family site-description-font-family" id="' . $this->settings_field . '[site-description-font-family]" name="' . $this->settings_field . '[site-description-font-family]">';
		$htf = genesis_get_option( 'site-description-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $htf, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[site-description-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				  <input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[site-description-font-size]" name="<?php
		echo $this->settings_field;
		?>[site-description-font-size]" value="<?php
		echo genesis_get_option( 'site-description-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[site-description-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'site-description-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'site-description-font-weight', $this->settings_field, false );
?>
					<select class="font-weight site-description-font-weight" id="<?php
		echo $this->settings_field;
		?>[site-description-font-weight]" name="<?php
		echo $this->settings_field;
		?>[site-description-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[site-description-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[site-description-font-color]" name="<?php
		echo $this->settings_field;
		?>[site-description-font-color]" value="<?php
		echo genesis_get_option( 'site-description-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			</table>
		  </div>
		</div>
		<?php
	}

	function headline_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">

				<tr><td colspan="2"><h4 class="gl-head"><?php
		_e( 'Post Titles', CHILD_DOMAIN );
		?></h4></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[headline-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family headline-font-family" id="' . $this->settings_field . '[headline-font-family]" name="' . $this->settings_field . '[headline-font-family]">';
		$hl = genesis_get_option( 'headline-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $hl, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[headline-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[headline-font-size]" name="<?php
		echo $this->settings_field;
		?>[headline-font-size]" value="<?php
		echo genesis_get_option( 'headline-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>
<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[headline-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'headline-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'headline-font-weight', $this->settings_field, false );
?>
					<select class="font-weight headline-font-weight" id="<?php
		echo $this->settings_field;
		?>[headline-font-weight]" name="<?php
		echo $this->settings_field;
		?>[headline-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[headline-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[headline-font-color]" name="<?php
		echo $this->settings_field;
		?>[headline-font-color]" value="<?php
		echo genesis_get_option( 'headline-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td colspan="2"><h4 class="gl-head"><?php
		_e( 'Headlines Inside The Post Body', CHILD_DOMAIN );
		?></h4></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[headline-subhead-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family headline-subhead-font-family" id="' . $this->settings_field . '[headline-subhead-font-family]" name="' . $this->settings_field . '[headline-subhead-font-family]">';
		$subhl = genesis_get_option( 'headline-subhead-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $subhl, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[headline-subhead-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'headline-subhead-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'headline-subhead-font-weight', $this->settings_field, false );
?>
					<select class="font-weight headline-subhead-font-weight" id="<?php
		echo $this->settings_field;
		?>[headline-subhead-font-weight]" name="<?php
		echo $this->settings_field;
		?>[headline-subhead-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[headline-subhead-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[headline-subhead-font-color]" name="<?php
		echo $this->settings_field;
		?>[headline-subhead-font-color]" value="<?php
		echo genesis_get_option( 'headline-subhead-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			</table>
		  </div>
		</div>
	  <?php
	}

	function primary_navmenu_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[nav-menu-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family nav-menu-font-family" id="' . $this->settings_field . '[nav-menu-font-family]" name="' . $this->settings_field . '[nav-menu-font-family]">';
		$navh = genesis_get_option( 'nav-menu-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $navh, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[nav-menu-font-size]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-font-size]" value="<?php
		echo genesis_get_option( 'nav-menu-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			</tr>
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'nav-menu-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'nav-menu-font-weight', $this->settings_field, false );
?>
					<select class="font-weight nav-menu-font-weight" id="<?php
		echo $this->settings_field;
		?>[nav-menu-font-weight]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-link-text-color]"><?php
		_e( 'Nav Menu Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-link-text-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-link-text-color]" value="<?php
		echo genesis_get_option( 'nav-menu-link-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-link-text-hover-color]"><?php
		_e( 'Nav Menu Hover Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-link-text-hover-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-link-text-hover-color]" value="<?php
		echo genesis_get_option( 'nav-menu-link-text-hover-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-current-link-text-color]"><?php
		_e( 'Current Link Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-current-link-text-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-current-link-text-color]" value="<?php
		echo genesis_get_option( 'nav-menu-current-link-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-current-parent-link-text-color]"><?php
		_e( 'Parent Nav Link Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-current-parent-link-text-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-current-parent-link-text-color]" value="<?php
		echo genesis_get_option( 'nav-menu-current-parent-link-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-link-bg-color]"><?php
		_e( 'Nav Menu Link Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-link-bg-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-link-bg-color]" value="<?php
		echo genesis_get_option( 'nav-menu-link-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-hover-bg-color]"><?php
		_e( 'Nav Menu Link Hover Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-hover-bg-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-hover-bg-color]" value="<?php
		echo genesis_get_option( 'nav-menu-hover-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-current-bg-color]"><?php
		_e( 'Current Link Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-current-bg-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-current-bg-color]" value="<?php
		echo genesis_get_option( 'nav-menu-current-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-current-parent-bg-color]"><?php
		_e( 'Parent Nav Link Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[nav-menu-current-parent-bg-color]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-current-parent-bg-color]" value="<?php
		echo genesis_get_option( 'nav-menu-current-parent-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  
			  
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[nav-menu-submenu-width]"><?php
		_e( 'Submenu Width', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input maxlength="3" type="number" id="<?php
		echo $this->settings_field;
		?>[nav-menu-submenu-width]" name="<?php
		echo $this->settings_field;
		?>[nav-menu-submenu-width]" value="<?php
		echo genesis_get_option( 'nav-menu-submenu-width', $this->settings_field, false );
		?>" />&nbsp;px
				  </p></td>
			  </tr>

			</table>
		  </div>
		</div>
		<?php
	}

	function secondary_navmenu_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[subnav-menu-font-family]';
		?>">Font Family</label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family subnav-menu-font-family" id="' . $this->settings_field . '[subnav-menu-font-family]" name="' . $this->settings_field . '[subnav-menu-font-family]">';
		$subnavh = genesis_get_option( 'subnav-menu-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $subnavh, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-font-size]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-font-size]" value="<?php
		echo genesis_get_option( 'subnav-menu-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			</tr>
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'subnav-menu-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'subnav-menu-font-weight', $this->settings_field, false );
?>
					<select class="font-weight subnav-menu-font-weight" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-font-weight]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-text-color]"><?php
		_e( 'Menu Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-text-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-text-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-link-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-text-hover-color]"><?php
		_e( 'Menu Hover Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-text-hover-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-text-hover-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-link-text-hover-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-link-text-color]"><?php
		_e( 'Current Link Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-link-text-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-link-text-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-current-link-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-parent-link-text-color]"><?php
		_e( 'Parent Nav Link Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-parent-link-text-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-parent-link-text-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-current-parent-link-text-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-bg-color]"><?php
		_e( 'Menu Link Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-bg-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-link-bg-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-link-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-hover-bg-color]"><?php
		_e( 'Menu Link Hover Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-hover-bg-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-hover-bg-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-hover-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-bg-color]"><?php
		_e( 'Current Link Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-bg-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-bg-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-current-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-parent-bg-color]"><?php
		_e( 'Parent Nav Link Background Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-parent-bg-color]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-current-parent-bg-color]" value="<?php
		echo genesis_get_option( 'subnav-menu-current-parent-bg-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  
			  
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[subnav-menu-submenu-width]"><?php
		_e( 'Submenu Width', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input maxlength="3" type="number" id="<?php
		echo $this->settings_field;
		?>[subnav-menu-submenu-width]" name="<?php
		echo $this->settings_field;
		?>[subnav-menu-submenu-width]" value="<?php
		echo genesis_get_option( 'subnav-menu-submenu-width', $this->settings_field, false );
		?>" />&nbsp;px
				  </p></td>
			  </tr>
			</table>
		  </div>
		</div>
		<?php
	}

	function byline_meta_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">
			  <tr>
				<td colspan="2"><p class="l-desc"><?php
		_e( 'These settings affect the post bylines (entry-meta) of the posts & pages.', CHILD_DOMAIN );
		?></p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[byline-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family byline-font-family" id="' . $this->settings_field . '[byline-font-family]" name="' . $this->settings_field . '[byline-font-family]">';
		$byh = genesis_get_option( 'byline-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $byh, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[byline-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[byline-font-size]" name="<?php
		echo $this->settings_field;
		?>[byline-font-size]" value="<?php
		echo genesis_get_option( 'byline-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>
			   <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[byline-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'byline-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'byline-font-weight', $this->settings_field, false );
?>
					<select class="font-weight byline-font-weight" id="<?php
		echo $this->settings_field;
		?>[byline-font-weight]" name="<?php
		echo $this->settings_field;
		?>[byline-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[byline-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[byline-font-color]" name="<?php
		echo $this->settings_field;
		?>[byline-font-color]" value="<?php
		echo genesis_get_option( 'byline-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			</table>
		  </div>
		</div>
		<?php
	}

	function sidebar_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">
			<tr>
				<td colspan="2"><h4 class="gl-head"><?php
		_e( 'Sidebar Widget Headings', CHILD_DOMAIN );
		?></h4></td>
			</tr>
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[sidebar-heading-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family sidebar-heading-font-family" id="' . $this->settings_field . '[sidebar-heading-font-family]" name="' . $this->settings_field . '[sidebar-heading-font-family]" >';
		$ssh = genesis_get_option( 'sidebar-heading-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $ssh, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-size]" name="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-size]" value="<?php
		echo genesis_get_option( 'sidebar-heading-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>
			<tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'sidebar-heading-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'sidebar-heading-font-weight', $this->settings_field, false );
?>
					<select class="font-weight sidebar-heading-font-weight" id="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-weight]" name="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-color]" name="<?php
		echo $this->settings_field;
		?>[sidebar-heading-font-color]" value="<?php
		echo genesis_get_option( 'sidebar-heading-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td colspan="2"><h4 class="gl-head"><?php
		_e( 'Sidebar Widget Body', CHILD_DOMAIN );
		?></h4></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[sidebar-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family sidebar-font-family" id="' . $this->settings_field . '[sidebar-font-family]" name="' . $this->settings_field . '[sidebar-font-family]">';
		$sf = genesis_get_option( 'sidebar-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $sf, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[sidebar-font-size]" name="<?php
		echo $this->settings_field;
		?>[sidebar-font-size]" value="<?php
		echo genesis_get_option( 'sidebar-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'sidebar-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'sidebar-font-weight', $this->settings_field, false );
?>
					<select class="font-weight sidebar-font-weight" id="<?php
		echo $this->settings_field;
		?>[sidebar-font-weight]" name="<?php
		echo $this->settings_field;
		?>[sidebar-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[sidebar-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[sidebar-font-color]" name="<?php
		echo $this->settings_field;
		?>[sidebar-font-color]" value="<?php
		echo genesis_get_option( 'sidebar-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			</table>
		  </div>
		</div>
		<?php
	}

	function footer_widgets_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">
			  <tr>
				<td colspan="2"><h4 class="gl-head"><?php
		_e( 'Footer Widget Headings', CHILD_DOMAIN );
		?></h4></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[footer-widgets-heading-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family footer-widgets-heading-font-family" id="' . $this->settings_field . '[footer-widgets-heading-font-family]" name="' . $this->settings_field . '[footer-widgets-heading-font-family]">';
		$fwh = genesis_get_option( 'footer-widgets-heading-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $fwh, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="number" id="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-size]" name="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-size]" value="<?php
		echo genesis_get_option( 'footer-widgets-heading-font-size', $this->settings_field, false );
		?>" />&nbsp;px
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-weight]"><?php
		_e( 'Primary Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'footer-widgets-heading-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'footer-widgets-heading-font-weight', $this->settings_field, false );
?>
					<select class="font-weight footer-widgets-heading-font-weight" id="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-weight]" name="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-color]" name="<?php
		echo $this->settings_field;
		?>[footer-widgets-heading-font-color]" value="<?php
		echo genesis_get_option( 'footer-widgets-heading-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			  <tr>
				<td colspan="2"><h4 class="gl-head"><?php
		_e( 'Footer Widget Body', CHILD_DOMAIN );
		?></h4></td>
			  </tr>

			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[footer-widgets-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family footer-widgets-font-family" id="' . $this->settings_field . '[footer-widgets-font-family]" name="' . $this->settings_field . '[footer-widgets-font-family]">';
		$fwf = genesis_get_option( 'footer-widgets-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $fwf, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="number" id="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-size]" name="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-size]" value="<?php
		echo genesis_get_option( 'footer-widgets-font-size', $this->settings_field, false );
		?>" />&nbsp;px
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'footer-widgets-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'footer-widgets-font-weight', $this->settings_field, false );
?>
					<select class="font-weight footer-widgets-font-weight" id="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-weight]" name="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[footer-widgets-font-color]" name="<?php

		echo $this->settings_field;
		?>[footer-widgets-font-color]" value="<?php
		echo genesis_get_option( 'footer-widgets-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			</table>
		  </div>
		</div>
		<?php
	}

	function footer_settings_box() {
?>
		<div class="gl-section">
		  <div class="gl-desc">
			<table class="gl-col-table">
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field . '[footer-font-family]';
		?>"><?php
		_e( 'Font Family', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<?php
		echo '<select class="font-family footer-font-family" id="' . $this->settings_field . '[footer-font-family]" name="' . $this->settings_field . '[footer-font-family]">';
		$ff = genesis_get_option( 'footer-font-family', $this->settings_field, false );
		foreach ( $this->lander_fonts as $font_key => $font ) {
			$selected = selected( $ff, $font_key, 0 );

			echo "<option $selected value=\"$font_key\">" . $font['name'] . "</option>\n";
		}
		echo '</select>';
?>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-font-size]"><?php
		_e( 'Font Size', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input pattern="\d{2,3}|inherit" type="text" id="<?php
		echo $this->settings_field;
		?>[footer-font-size]" name="<?php
		echo $this->settings_field;
		?>[footer-font-size]" value="<?php
		echo genesis_get_option( 'footer-font-size', $this->settings_field, false );
		?>" />&nbsp;px&nbsp;&#124;&nbsp;inherit
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-font-weight]"><?php
		_e( 'Font Variant', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
				<?php
		$weights = lander_font_weights( genesis_get_option( 'footer-font-family', $this->settings_field, false ) );
		$weight  = genesis_get_option( 'footer-font-weight', $this->settings_field, false );
?>
					<select class="font-weight footer-font-weight" id="<?php
		echo $this->settings_field;
		?>[footer-font-weight]" name="<?php
		echo $this->settings_field;
		?>[footer-font-weight]">
					<?php
		foreach ( $weights as $value ) {
			echo '<option value="' . $value . '" ' . selected( $value, $weight, false ) . '>' . $value . '</option>';
		}
?>
					</select>
				  </p></td>
			  </tr>
			  <tr>
				<td class="gl-label"><p>
					<label for="<?php
		echo $this->settings_field;
		?>[footer-font-color]"><?php
		_e( 'Text Color', CHILD_DOMAIN );
		?></label>
				  </p></td>
				<td class="lander-input"><p>
					<input type="text" class="color_validate lander-color-selector" id="<?php
		echo $this->settings_field;
		?>[footer-font-color]" name="<?php
		echo $this->settings_field;
		?>[footer-font-color]" value="<?php
		echo genesis_get_option( 'footer-font-color', $this->settings_field, false );
		?>" />
				  </p></td>
			  </tr>
			<tr>
			<td colspan=2>
				<p>For better footer control and customizations options, install <a href="https://wordpress.org/plugins/genesis-footer-builder">Genesis Footer Builder</a>.</p>
			</td>
			</tr>
			</table>
		  </div>
		</div>
		<?php
	}

}