<?php

/**
 * Generates and write or return CSS as per the design settings. Generates dynamic media queries.
 * @param array $settings (Design Settings)
 * @param bool $writecss 
 * @return string CSS or none
 * @since 1.0
 */
function lander_generate_css( $settings = array(), $writecss = true ) {
	
	$writecss = (bool) $writecss;
	
	if ( !is_array( $settings ) || $settings == false || empty( $settings ) ) {
		/* In case there is no settings passed which is the case when doing after_theme_switch, Lander should check for the design that is set in the admin settings field. Get it's settings and use them instead of dumbing back to vanilla
		*/
		$design = get_option( CHILD_SETTINGS_FIELD );	// try to get the settings of the current design
		if( !$design ) {	// If no settings exist/first-run then get the default settings.
			$design = lander_design_defaults();
		}
		$settings = lander_back_compat( $design );
	}

	// Global $lander_design_defaults;
	$layout                   = $settings['layout'];
	$padding                  = (int) $settings['col-spacing'];
	$content_w_1col           = (int) $settings['column-content-1col'];
	$content_w_2col           = (int) $settings['column-content-2col'];
	$sb1_w_2col               = (int) $settings['sidebar-one-2col'];
	$content_w_3col           = (int) $settings['column-content-3col'];
	$sb1_w_3col               = (int) $settings['sidebar-one-3col'];
	$sb2_w_3col               = (int) $settings['sidebar-two-3col'];
	$site_bg_color            = strcasecmp($settings['site-background-color'] , 'inherit') ? ($settings['site-background-color']) : ((bool) 0);
	$page_bg_color            = strcasecmp($settings['page-background-color'] , 'inherit') ? ($settings['page-background-color']) : ((bool) 0);
	$primary_text_color       = strcasecmp($settings['primary-text-color'] , 'inherit') ? ($settings['primary-text-color']) : ((bool) 0);
	$form_text_color          = strcasecmp($settings['form-text-color'] , 'inherit') ? ($settings['form-text-color']) : ((bool) 0);
	$primary_link_color       = strcasecmp($settings['primary-link-color'] , 'inherit') ? ($settings['primary-link-color']) : ((bool) 0);
	$primary_link_hover_color = strcasecmp($settings['primary-link-hover-color'] , 'inherit') ? ($settings['primary-link-hover-color']) : ((bool) 0);
	
	$form_font_family = lander_get_font_family($settings['form-font-family']);
	$form_font_family = strcasecmp($form_font_family , 'inherit') ? ($form_font_family) : ((bool) 0);
	
	$body_font_family = lander_get_font_family($settings['body-font-family']);
	$body_font_family = strcasecmp($body_font_family , 'inherit') ? ($body_font_family) : ((bool) 0);
	$body_font_weight = lander_get_font_weight( $settings['body-font-weight'] );
	$body_font_weight = strcasecmp($body_font_weight , 'inherit') ? ($body_font_weight) : ((bool) 0);
	$body_font_style  = lander_get_font_style( $settings['body-font-weight'] );
	$body_font_style  = strcasecmp($body_font_style , 'inherit') ? ($body_font_style) : ((bool) 0);
	
	
	$nav_menu_font_family = lander_get_font_family($settings['nav-menu-font-family']);
	$nav_menu_font_family = strcasecmp($nav_menu_font_family , 'inherit') ? ($nav_menu_font_family) : ((bool) 0);
	$nav_menu_font_weight = lander_get_font_weight( $settings['nav-menu-font-weight'] );
	$nav_menu_font_weight = strcasecmp($nav_menu_font_weight , 'inherit') ? ($nav_menu_font_weight) : ((bool) 0);
	$nav_menu_font_style  = lander_get_font_style( $settings['nav-menu-font-weight'] );
	$nav_menu_font_style  = strcasecmp($nav_menu_font_style , 'inherit') ? ($nav_menu_font_style) : ((bool) 0);
	
	$subnav_menu_font_family = lander_get_font_family($settings['subnav-menu-font-family']);
	$subnav_menu_font_family = strcasecmp($subnav_menu_font_family , 'inherit') ? ($subnav_menu_font_family) : ((bool) 0);
	$subnav_menu_font_weight = lander_get_font_weight( $settings['subnav-menu-font-weight'] );
	$subnav_menu_font_weight = strcasecmp($subnav_menu_font_weight , 'inherit') ? ($subnav_menu_font_weight) : ((bool) 0);
	$subnav_menu_font_style  = lander_get_font_style( $settings['subnav-menu-font-weight'] );
	$subnav_menu_font_style  = strcasecmp($subnav_menu_font_style , 'inherit') ? ($subnav_menu_font_style) : ((bool) 0);
	
	$site_title_font_family = lander_get_font_family($settings['site-title-font-family']);
	$site_title_font_family = strcasecmp($site_title_font_family , 'inherit') ? ($site_title_font_family) : ((bool) 0);
	$site_title_font_weight = lander_get_font_weight( $settings['site-title-font-weight'] );
	$site_title_font_weight = strcasecmp($site_title_font_weight , 'inherit') ? ($site_title_font_weight) : ((bool) 0);
	$site_title_font_style  = lander_get_font_style( $settings['site-title-font-weight'] );
	$site_title_font_style  = strcasecmp($site_title_font_style , 'inherit') ? ($site_title_font_style) : ((bool) 0);
	
	$site_description_font_family = lander_get_font_family($settings['site-description-font-family']);
	$site_description_font_family = strcasecmp($site_description_font_family , 'inherit') ? ($site_description_font_family) : ((bool) 0);
	$site_description_font_weight = lander_get_font_weight( $settings['site-description-font-weight'] );
	$site_description_font_weight = strcasecmp($site_description_font_weight , 'inherit') ? ($site_description_font_weight) : ((bool) 0);
	$site_description_font_style  = lander_get_font_style( $settings['site-description-font-weight'] );
	$site_description_font_style  = strcasecmp($site_description_font_style , 'inherit') ? ($site_description_font_style) : ((bool) 0);
	
	$headline_font_family = lander_get_font_family($settings['headline-font-family']);
	$headline_font_family = strcasecmp($headline_font_family , 'inherit') ? ($headline_font_family) : ((bool) 0);
	$headline_font_weight = lander_get_font_weight( $settings['headline-font-weight'] );
	$headline_font_weight = strcasecmp($headline_font_weight , 'inherit') ? ($headline_font_weight) : ((bool) 0);
	$headline_font_style  = lander_get_font_style( $settings['headline-font-weight'] );
	$headline_font_style  = strcasecmp($headline_font_style , 'inherit') ? ($site_descheadline_font_styleription_font_style) : ((bool) 0);
	
	$headline_subhead_font_family = lander_get_font_family($settings['headline-subhead-font-family']);
	$headline_subhead_font_family = strcasecmp($headline_subhead_font_family , 'inherit') ? ($headline_subhead_font_family) : ((bool) 0);
	$headline_subhead_font_weight = lander_get_font_weight( $settings['headline-subhead-font-weight'] );
	$headline_subhead_font_weight = strcasecmp($headline_subhead_font_weight , 'inherit') ? ($headline_subhead_font_weight) : ((bool) 0);
	$headline_subhead_font_style  = lander_get_font_style( $settings['headline-subhead-font-weight'] );
	$headline_subhead_font_style  = strcasecmp($headline_subhead_font_style , 'inherit') ? ($headline_subhead_font_style) : ((bool) 0);
	
	$byline_font_family = lander_get_font_family($settings['byline-font-family']);
	$byline_font_family = strcasecmp($byline_font_family , 'inherit') ? ($byline_font_family) : ((bool) 0);
	$byline_font_weight = lander_get_font_weight( $settings['byline-font-weight'] );
	$byline_font_weight = strcasecmp($byline_font_weight , 'inherit') ? ($byline_font_weight) : ((bool) 0);
	$byline_font_style  = lander_get_font_style( $settings['byline-font-weight'] );
	$byline_font_style  = strcasecmp($byline_font_style , 'inherit') ? ($byline_font_style) : ((bool) 0);
	
	$sidebar_heading_font_family = lander_get_font_family($settings['sidebar-heading-font-family']);
	$sidebar_heading_font_family = strcasecmp($sidebar_heading_font_family , 'inherit') ? ($sidebar_heading_font_family) : ((bool) 0);
	$sidebar_heading_font_weight = lander_get_font_weight( $settings['sidebar-heading-font-weight'] );
	$sidebar_heading_font_weight = strcasecmp($sidebar_heading_font_weight , 'inherit') ? ($sidebar_heading_font_weight) : ((bool) 0);
	$sidebar_heading_font_style  = lander_get_font_style( $settings['sidebar-heading-font-weight'] );
	$sidebar_heading_font_style  = strcasecmp($sidebar_heading_font_style , 'inherit') ? ($sidebar_heading_font_style) : ((bool) 0);
	
	$sidebar_font_family = lander_get_font_family($settings['sidebar-font-family']);
	$sidebar_font_family = strcasecmp($sidebar_font_family , 'inherit') ? ($sidebar_font_family) : ((bool) 0);
	$sidebar_font_weight = lander_get_font_weight( $settings['sidebar-font-weight'] );
	$sidebar_font_weight = strcasecmp($sidebar_font_weight , 'inherit') ? ($sidebar_font_weight) : ((bool) 0);
	$sidebar_font_style  = lander_get_font_style( $settings['sidebar-font-weight'] );
	$sidebar_font_style  = strcasecmp($sidebar_font_style , 'inherit') ? ($sidebar_font_style) : ((bool) 0);
	
	
	$footer_widgets_font_family = lander_get_font_family($settings['footer-widgets-font-family']);
	$footer_widgets_font_family = strcasecmp($footer_widgets_font_family , 'inherit') ? ($footer_widgets_font_family) : ((bool) 0);
	$footer_widgets_font_weight = lander_get_font_weight( $settings['footer-widgets-font-weight'] );
	$footer_widgets_font_weight = strcasecmp($footer_widgets_font_weight , 'inherit') ? ($footer_widgets_font_weight) : ((bool) 0);
	$footer_widgets_font_style  = lander_get_font_style( $settings['footer-widgets-font-weight'] );
	$footer_widgets_font_style  = strcasecmp($footer_widgets_font_style , 'inherit') ? ($footer_widgets_font_style) : ((bool) 0);
	
	$footer_widgets_heading_font_family = lander_get_font_family($settings['footer-widgets-heading-font-family']);
	$footer_widgets_heading_font_family = strcasecmp($footer_widgets_heading_font_family , 'inherit') ? ($footer_widgets_heading_font_family) : ((bool) 0);
	$footer_widgets_heading_font_weight = lander_get_font_weight( $settings['footer-widgets-heading-font-weight'] );
	$footer_widgets_heading_font_weight = strcasecmp($footer_widgets_heading_font_weight , 'inherit') ? ($footer_widgets_heading_font_weight) : ((bool) 0);
	$footer_widgets_heading_font_style  = lander_get_font_style( $settings['footer-widgets-heading-font-weight'] );
	$footer_widgets_heading_font_style  = strcasecmp($footer_widgets_heading_font_style , 'inherit') ? ($footer_widgets_heading_font_style) : ((bool) 0);
	
	$footer_font_family	= lander_get_font_family($settings['footer-font-family']);
	$footer_font_family	= strcasecmp($footer_font_family , 'inherit') ? ($footer_font_family) : ((bool) 0);
	$footer_font_weight	= lander_get_font_weight( $settings['footer-font-weight'] );
	$footer_font_weight	= strcasecmp($footer_font_weight , 'inherit') ? ($footer_font_weight) : ((bool) 0);
	$footer_font_style	= lander_get_font_style( $settings['footer-font-weight'] );
	$footer_font_style	= strcasecmp($footer_font_style , 'inherit') ? ($footer_font_style) : ((bool) 0);
	
	$body_font_size                          = strcasecmp($settings['body-font-size'] , 'inherit')	?	((int) $settings['body-font-size']) : ((bool) 0);
	$form_font_size                          = strcasecmp($settings['form-font-size'] , 'inherit')	?	((int) $settings['form-font-size']) : ((bool) 0);
	$nav_menu_font_size                      = strcasecmp($settings['nav-menu-font-size'] , 'inherit')	?	((int) $settings['nav-menu-font-size']) : ((bool) 0);
	$nav_menu_link_text_color                = strcasecmp($settings['nav-menu-link-text-color'] , 'inherit') ? ($settings['nav-menu-link-text-color']) : ($primary_link_color);
	$nav_menu_link_text_hover_color          = strcasecmp($settings['nav-menu-link-text-hover-color'] , 'inherit') ? ($settings['nav-menu-link-text-hover-color']) : ($primary_link_hover_color);
	$nav_menu_current_link_text_color        = strcasecmp($settings['nav-menu-current-link-text-color'] , 'inherit') ? ($settings['nav-menu-current-link-text-color']) : ((bool) 0);
	$nav_menu_current_parent_link_text_color = strcasecmp($settings['nav-menu-current-parent-link-text-color'] , 'inherit') ? ($settings['nav-menu-current-parent-link-text-color']) : ((bool) 0);
	$nav_menu_link_bg_color                  = strcasecmp($settings['nav-menu-link-bg-color'] , 'inherit') ? ($settings['nav-menu-link-bg-color']) : ((bool) 0);
	$nav_menu_hover_bg_color                 = strcasecmp($settings['nav-menu-hover-bg-color'] , 'inherit') ? ($settings['nav-menu-hover-bg-color']) : ((bool) 0);
	$nav_menu_current_bg_color               = strcasecmp($settings['nav-menu-current-bg-color'] , 'inherit') ? ($settings['nav-menu-current-bg-color']) : ((bool) 0);
	$nav_menu_current_parent_bg_color        = strcasecmp($settings['nav-menu-current-parent-bg-color'] , 'inherit') ? ($settings['nav-menu-current-parent-bg-color']) : ((bool) 0);
	
	
	$nav_menu_submenu_width                  = $settings['nav-menu-submenu-width'];
	
	$subnav_menu_font_size                      = strcasecmp($settings['subnav-menu-font-size'] , 'inherit')	?	((int) $settings['subnav-menu-font-size']) : ((bool) 0);
	$subnav_menu_link_text_color                = strcasecmp($settings['subnav-menu-link-text-color'] , 'inherit') ? ($settings['subnav-menu-link-text-color']) : (primary_link_color);
	$subnav_menu_link_text_hover_color          = strcasecmp($settings['subnav-menu-link-text-hover-color'] , 'inherit') ? ($settings['subnav-menu-link-text-hover-color']) : ($primary_link_hover_color);
	$subnav_menu_current_link_text_color        = strcasecmp($settings['subnav-menu-current-link-text-color'] , 'inherit') ? ($settings['subnav-menu-current-link-text-color']) : ((bool) 0);
	$subnav_menu_current_parent_link_text_color = strcasecmp($settings['subnav-menu-current-parent-link-text-color'] , 'inherit') ? ($settings['subnav-menu-current-parent-link-text-color']) : ((bool) 0);
	$subnav_menu_link_bg_color                  = strcasecmp($settings['subnav-menu-link-bg-color'] , 'inherit') ? ($settings['subnav-menu-link-bg-color']) : ((bool) 0);
	$subnav_menu_hover_bg_color                 = strcasecmp($settings['subnav-menu-hover-bg-color'] , 'inherit') ? ($settings['subnav-menu-hover-bg-color']) : ((bool) 0);
	$subnav_menu_current_bg_color               = strcasecmp($settings['subnav-menu-current-bg-color'] , 'inherit') ? ($settings['subnav-menu-current-bg-color']) : ((bool) 0);
	$subnav_menu_current_parent_bg_color        = strcasecmp($settings['subnav-menu-current-parent-bg-color'] , 'inherit') ? ($settings['subnav-menu-current-parent-bg-color']) : ((bool) 0);
	
	
	$subnav_menu_submenu_width                  = $settings['subnav-menu-submenu-width'];
	
	$site_title_font_size              = strcasecmp($settings['site-title-font-size'] , 'inherit')	?	((int) $settings['site-title-font-size']) : ((bool) 0);
	$site_title_font_color             = strcasecmp($settings['site-title-font-color'] , 'inherit') ? ($settings['site-title-font-color']) : ((bool) 0);
	$site_description_font_size        = strcasecmp($settings['site-description-font-size'] , 'inherit')	?	((int) $settings['site-description-font-size']) : ((bool) 0);
	$site_description_font_color       = strcasecmp($settings['site-description-font-color'] , 'inherit') ? ($settings['site-description-font-color']) : ((bool) 0);
	
	$headline_font_size                = strcasecmp($settings['headline-font-size'] , 'inherit')	?	((int) $settings['headline-font-size']) : ((bool) 0);
	$headline_font_color               = strcasecmp($settings['headline-font-color'] , 'inherit') ? ($settings['headline-font-color']) : ((bool) 0);
	
	$headline_subhead_font_color       = strcasecmp($settings['headline-subhead-font-color'] , 'inherit') ? ($settings['headline-subhead-font-color']) : ((bool) 0);
	
	$byline_font_size                  = strcasecmp($settings['byline-font-size'] , 'inherit')	?	((int) $settings['byline-font-size']) : ((bool) 0);
	$byline_font_color                 = strcasecmp($settings['byline-font-color'] , 'inherit') ? ($settings['byline-font-color']) : ((bool) 0);
	
	$sidebar_font_size                 = strcasecmp($settings['sidebar-font-size'] , 'inherit')	?	((int) $settings['sidebar-font-size']) : ((bool) 0);
	$sidebar_font_color                = strcasecmp($settings['sidebar-font-color'] , 'inherit') ? ($settings['sidebar-font-color']) : ((bool) 0);
	
	$sidebar_heading_font_size         = strcasecmp($settings['sidebar-heading-font-size'] , 'inherit')	?	((int) $settings['sidebar-heading-font-size']) : ((bool) 0);
	$sidebar_heading_font_color        = strcasecmp($settings['sidebar-heading-font-color'] , 'inherit') ? ($settings['sidebar-heading-font-color']) : ((bool) 0);
	
	$footer_font_size                  = strcasecmp($settings['footer-font-size'] , 'inherit')	?	((int) $settings['footer-font-size']) : ((bool) 0);
	$footer_font_color                 = strcasecmp($settings['footer-font-color'] , 'inherit') ? ($settings['footer-font-color']) : ((bool) 0);
	
	$footer_widgets_font_size          = strcasecmp($settings['footer-widgets-font-size'] , 'inherit')	?	((int) $settings['footer-widgets-font-size']) : ((bool) 0);
	$footer_widgets_font_color         = strcasecmp($settings['footer-widgets-font-color'] , 'inherit') ? ($settings['footer-widgets-font-color']) : ((bool) 0);
	
	$footer_widgets_heading_font_size  = strcasecmp($settings['footer-widgets-heading-font-size'] , 'inherit')	?	((int) $settings['footer-widgets-heading-font-size']) : ((bool) 0);
	$footer_widgets_heading_font_color = strcasecmp($settings['footer-widgets-heading-font-color'] , 'inherit') ? ($settings['footer-widgets-heading-font-color']) : ((bool) 0);

	/*
	if ( $nav_menu_font_family == '0' ) {
		$nav_menu_font_family = $body_font_family;
	}
	if ( $site_title_font_family == '0' ) {
		$site_title_font_family = $body_font_family;
	}
	if ( $site_description_font_family == '0' ) {
		$site_description_font_family = $site_title_font_family;
	}
	if ( $headline_font_family == '0' ) {
		$headline_font_family = $body_font_family;
	}
	if ( $headline_subhead_font_family == '0' ) {
		$headline_subhead_font_family = $headline_font_family;
	}
	if ( $sidebar_font_family == '0' ) {
		$sidebar_font_family = $body_font_family;
	}
	if ( $sidebar_heading_font_family == '0' ) {
		$sidebar_heading_font_family = $sidebar_font_family;
	}
	if ( $footer_font_family == '0' ) {
		$footer_font_family = $body_font_family;
	}
	*/

	$widths['three-two']  = ( $content_w_3col + $sb1_w_3col + $sb2_w_3col + ( $padding * 4 ) ) - 1;
	$widths['three-one']  = ( $content_w_3col + $sb1_w_3col + $padding * 3 ) - 1;
	$widths['three-zero'] = ( $content_w_3col + $padding * 2 ) - 1;
	$widths['two-one']    = ( $content_w_2col + $sb1_w_2col + $padding * 3 ) - 1;
	$widths['two-zero']   = ( $content_w_2col + $padding * 2 ) - 1;
	$widths['one-zero']   = ( $content_w_1col + $padding * 2 ) - 1;
	$widths['min-width']  = min( $widths['three-zero'], $widths['two-zero'], $widths['one-zero'] );

	$site_container_css       = '';
	$site_container_one_col   = ".pagewidth.full-width-content .site-container {
	width:" . ( $widths['one-zero'] + 1 ) . "px;
	} ";
	$site_container_two_col   = ".pagewidth.content-sidebar .site-container,
	.pagewidth.sidebar-content .site-container {
	width:" . ( $widths['two-one'] + 1 ) . "px;
	}";
	$site_container_three_col = ".pagewidth.content-sidebar-sidebar .site-container,
	.pagewidth.sidebar-sidebar-content .site-container,
	.pagewidth.sidebar-content-sidebar .site-container{
	width:" . ( $widths['three-two'] + 1 ) . "px;
	}";

	$site_container_three_two  = '
	.pagewidth.content-sidebar-sidebar .site-container,
	.pagewidth.sidebar-sidebar-content .site-container,
	.pagewidth.sidebar-content-sidebar .site-container {
	width:' . ( $widths['three-one'] + 1 ) . 'px;
	}';
	$site_container_three_one  = '.pagewidth.content-sidebar-sidebar .site-container,
	.pagewidth.sidebar-sidebar-content .site-container,
	.pagewidth.sidebar-content-sidebar .site-container {
	width:' . ( $widths['three-zero'] + 1 ) . 'px;
	}';
	$site_container_three_zero = '.pagewidth.content-sidebar-sidebar .site-container,
	.pagewidth.sidebar-sidebar-content .site-container,
	.pagewidth.sidebar-content-sidebar .site-container {
	width:auto;
	}';
	$site_container_two_one    = '.pagewidth.sidebar-content .site-container,
	.pagewidth.content-sidebar .site-container {
	width:' . ( $widths['two-zero'] + 1 ) . 'px;
	}';
	$site_container_two_zero   = '.pagewidth.sidebar-content .site-container,
	.pagewidth.content-sidebar .site-container {
	width:auto;
	}';
	$site_container_one_zero   = '.pagewidth.full-width-content .site-container {
	width:auto;
	}';

	$css = "
	input, select, textarea {
	" . ( $form_text_color ? "color: " . $form_text_color . ";":"") . "
	" . ( $form_font_family ? "font-family: " . $form_font_family . ";":"") . "
	" . ( $form_font_size ? "font-size: " . $form_font_size . "px;":"") . "
}

input:focus,textarea:focus {
	" . ( $primary_link_color ? "border-color: " . $primary_link_color . ";":"") . "
}

/*! Layout */
" . $site_container_css . "
/*! 1 COL WIDTHS */
" . ( $site_container_one_col ) . "

.full-width-content .wrap {
	padding-left:" . $padding . "px;
	padding-right:" . $padding . "px;
	width:" . ( $content_w_1col + $padding * 2 ) . "px;
}

.full-width-content .content-sidebar-wrap,
.full-width-content .content {
	float:none;
}

/*! 2 COL WIDTHS */

" . ( $site_container_two_col ) . "

.content-sidebar .wrap,
.sidebar-content .wrap {
	width:" . ( $content_w_2col + $sb1_w_2col + $padding * 3 ) . "px;
	padding-left:" . $padding . "px;
	padding-right:" . $padding . "px;
}

.content-sidebar .content,
.sidebar-content .content {
	width:" . ( $content_w_2col ) . "px;
}
.site-header .widget-area,
.site-header .widget-area {
	max-width:" . ( $content_w_2col ) . "px;
}

.content-sidebar .sidebar-primary,
.sidebar-content .sidebar-primary {
	width:" . ( $sb1_w_2col ) . "px;
}

/*
:not(.header-full-width) .title-area,
:not(.header-full-width) .title-area {
	max-width:" . ( $sb1_w_2col ) . "px;
}
*/

/*! 3 COL WIDTHS */

" . ( $site_container_three_col ) . "


.content-sidebar-sidebar .wrap,
.sidebar-sidebar-content .wrap,
.sidebar-content-sidebar .wrap {
	width:" . ( $padding * 4 + $content_w_3col + $sb1_w_3col + $sb2_w_3col ) . "px;
	padding-left:" . $padding . "px;
	padding-right:" . $padding . "px;
}

.content-sidebar-sidebar .content-sidebar-wrap,
.sidebar-sidebar-content .content-sidebar-wrap,
.sidebar-content-sidebar .content-sidebar-wrap {
	width:" . ( $content_w_3col + $sb1_w_3col + $padding ) . "px; /* padding for content + sidebar */
}

.content-sidebar-sidebar .content,
.sidebar-sidebar-content .content,
.sidebar-content-sidebar .content,
.content-sidebar-sidebar .site-header .widget-area,
.sidebar-sidebar-content .site-header .widget-area,
.sidebar-content-sidebar .site-header .widget-area {
	width:" . ( $content_w_3col ) . "px; /* padding for content + sidebar */
}

.content-sidebar-sidebar .sidebar-primary,
.sidebar-sidebar-content .sidebar-primary,
.sidebar-content-sidebar .sidebar-primary {
	width:" . ( $sb1_w_3col ) . "px; /* padding for content + sidebar */
}

.content-sidebar-sidebar .sidebar-secondary,
.sidebar-sidebar-content .sidebar-secondary,
.sidebar-content-sidebar .sidebar-secondary {
	width:" . ( $sb2_w_3col ) . "px; /* padding for content + sidebar */
}


.content-sidebar-sidebar:not(.header-full-width) .site-header .title-area,
.sidebar-sidebar-content:not(.header-full-width) .site-header .title-area,
.sidebar-content-sidebar:not(.header-full-width) .site-header .title-area { max-width:" . ( $sb1_w_3col + $sb2_w_3col + $padding ) . "px;}



/*! BODY FONT-SZ */
body {
	" . ( $site_bg_color ? "background-color: " . $site_bg_color . ";":"") . "
	" . ( $body_font_family ? "font-family: " . $body_font_family . ";":"") . "
	" . ( $primary_text_color ? "color: " . $primary_text_color . ";":"") . "
	" . ( $body_font_size ? "font-size: " . $body_font_size . "px;":"") . "
	" . ( $body_font_weight ? "font-weight: " . $body_font_weight . ";":"") . "
	" . ( $body_font_style ? "font-style: " . $body_font_style . ";":"") . "
}

/*! PAGE BG */
.wrap {
	" . ( $page_bg_color ? "background-color: " . $page_bg_color . ";":"") . "
}

/*! LINKS  */

a {
	" . ( $primary_link_color ? "color: " . $primary_link_color . ";":"") . "
}

/*! Pagination */

.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current {
	" . ( $primary_link_color ? "color: " . $primary_link_color . ";":"") . "
}

.widget-wrap .entry-title a {
	text-decoration: none;
}

/*
a:hover,
.widget-wrap > :not(.widget-title) a:hover {
	text-decoration: none;
}

*/

a:hover {
	" . ( $primary_link_hover_color ? "color: " . $primary_link_hover_color . ";":"") . "
}

a {
	border-bottom: none;
}

/*! SITE TITLE */


.pagination li a {
	" . ( $primary_link_color ? "background-color: " . $primary_link_color . ";":"") . "
	color: #fff;
}

.pagination li.active a,
.pagination li a:hover {
	" . ( $primary_link_color ? "color: " . $primary_link_color . ";":"") . "
	background-color: #fff;
}

.site-title {
	" . ( $site_title_font_family ? "font-family: " . $site_title_font_family . ";":"") . "
	" . ( $site_title_font_size ? "font-size: " . $site_title_font_size . "px;":"") . "
	" . ( $site_title_font_weight ? "font-weight: " . $site_title_font_weight . ";":"") . "
	" . ( $site_title_font_style ? "font-style: " . $site_title_font_style . ";":"") . "
}

.site-title a {
	" . ( $site_title_font_color ? "color: " . $site_title_font_color . ";":"") . "
}

.site-title a:hover {
	" . ( $primary_link_color ? "border-color: " . $primary_link_color . ";":"") . "
}

.site-description {
	" . ( $site_description_font_family ? "font-family: " . $site_description_font_family . ";":"") . "
	" . ( $site_description_font_size ? "font-size: " . $site_description_font_size . "px;":"") . "
	" . ( $site_description_font_weight ? "font-weight: " . $site_description_font_weight . ";":"") . "
	" . ( $site_description_font_style ? "font-style: " . $site_description_font_style . ";":"") . "
	" . ( $site_description_font_color ? "color: " . $site_description_font_color . ";":"") . "
}

.entry-title,
.archive-title,
.woocommerce .page-title,
.landing-section-title {
	" . ( $headline_font_family ? "font-family: " . $headline_font_family . ";":"") . "
	" . ( $headline_font_size ? "font-size: " . $headline_font_size . "px;":"") . "
	" . ( $headline_font_weight ? "font-weight: " . $headline_font_weight . ";":"") . "
	" . ( $headline_font_style ? "font-style: " . $headline_font_style . ";":"") . "
	" . ( $headline_font_color ? "color: " . $headline_font_color . ";":"") . "
 }


.entry-title a {
	" . ( $headline_font_color ? "color: " . $headline_font_color . ";":"") . "
}

.entry-title a:hover {
	" . ( $primary_link_color ? "color: " . $primary_link_color . ";":"") . "
}

h1, h2, h3, h4, h5, h6 {
	" . ( $headline_subhead_font_family ? "font-family: " . $headline_subhead_font_family . ";":"") . "
	" . ( $headline_subhead_font_color ? "color: " . $headline_subhead_font_color . ";":"") . "
	" . ( $headline_subhead_font_weight ? "font-weight: " . $headline_subhead_font_weight . ";":"") . "
	" . ( $headline_subhead_font_style ? "font-style: " . $headline_subhead_font_style . ";":"") . "
}

/*! NAV */



.menu-primary .sub-menu {
	
}

.menu-primary  li {
	
}

.menu-primary a,
.site-header .genesis-nav-menu a,
.menu-toggle {

	" . ( $nav_menu_font_family ? "font-family: " . $nav_menu_font_family . ";":"") . "
	" . ( $nav_menu_font_size ? "font-size: " . $nav_menu_font_size . "px;":"") . "
	" . ( $nav_menu_font_weight ? "font-weight: " . $nav_menu_font_weight . ";":"") . "
	" . ( $nav_menu_font_style ? "font-style: " . $nav_menu_font_style . ";":"") . "
	" . ( $nav_menu_link_text_color ? "color: " . $nav_menu_link_text_color . ";":"") . "
	" . ( $nav_menu_link_bg_color ? "background-color: " . $nav_menu_link_bg_color . ";":"") . "
	
}" . PHP_EOL  .
PHP_EOL .
((!strcasecmp('transparent', $nav_menu_link_bg_color ) || !strcasecmp('white', $nav_menu_link_bg_color ) || !strcasecmp('#fff', $nav_menu_link_bg_color ) || !strcasecmp('#ffffff', $nav_menu_link_bg_color )) ?  ".menu-primary .sub-menu a {background-color: #eee;}.menu-primary .sub-menu a:hover{background-color: #ddd;} .menu-toggle{background-color: #ddd;}" : " ") . "


.rtl .menu-primary a,
.rtl .menu-toggle {
	
	border-right:0;
}

/*! redundant */
.lander .genesis-nav-menu > .right {
	" . ( $nav_menu_link_text_color ? "color: " . $nav_menu_link_text_color . ";":"") . "
	" . ( $nav_menu_font_size ? "font-size: " . $nav_menu_font_size . "px;":"") . "
}


/*! parent or parents parent/grandparent etc */
.menu-primary .current-menu-ancestor > a {
	" . ( $nav_menu_current_parent_link_text_color ? "color: " . $nav_menu_current_parent_link_text_color . ";":"") . "
	" . ( $nav_menu_current_parent_bg_color ? "background-color: " . $nav_menu_current_parent_bg_color . ";":"") . "
}

.menu-primary .current-menu-item > a {
	" . ( $nav_menu_current_link_text_color ? "color: " . $nav_menu_current_link_text_color . ";":"") . "
	" . ( $nav_menu_current_bg_color ? "background-color: " . $nav_menu_current_bg_color . ";":"") . "
}

.menu-primary .sub-menu,
.site-header .genesis-nav-menu .sub-menu {
	width:" . $nav_menu_submenu_width . "px;
}

.menu-primary a:hover {
	" . ( $nav_menu_link_text_hover_color ? "color: " . $nav_menu_link_text_hover_color . ";":"") . "
	" . ( $nav_menu_hover_bg_color ? "background-color: " . $nav_menu_hover_bg_color . ";":"") . "
}

.menu-secondary {
	
}



.menu-secondary .sub-menu {
	
}

.rtl .menu-secondary .sub-menu {
}

.menu-secondary  li {
	
}

.menu-secondary a,
.menu-toggle {
	" . ( $subnav_menu_font_family ? "font-family: " . $subnav_menu_font_family . ";":"") . "
	" . ( $subnav_menu_font_size ? "font-size: " . $subnav_menu_font_size . "px;":"") . "
	" . ( $subnav_menu_font_weight ? "font-weight: " . $subnav_menu_font_weight . ";":"") . "
	" . ( $subnav_menu_font_style ? "font-style: " . $subnav_menu_font_style . ";":"") . "
	" . ( $subnav_menu_link_text_color ? "color: " . $subnav_menu_link_text_color . ";":"") . "
	" . ( $subnav_menu_link_bg_color ? "background-color: " . $subnav_menu_link_bg_color . ";":"") . "
	
} " . PHP_EOL . PHP_EOL .
((!strcasecmp('transparent', $subnav_menu_link_bg_color ) || !strcasecmp('white', $subnav_menu_link_bg_color ) || !strcasecmp('#fff', $subnav_menu_link_bg_color ) || !strcasecmp('#ffffff', $subnav_menu_link_bg_color )) ?  ".menu-secondary .sub-menu a {background-color: #eee;} .menu-secondary .sub-menu a:hover{background-color: #ddd;}.menu-toggle{background-color: #ddd;}" : " ") . "

.rtl .menu-secondary a,
.rtl .menu-toggle {
	
}

.menu-secondary .current-menu-ancestor > a {
	" . ( $subnav_menu_current_parent_link_text_color ? "color: " . $subnav_menu_current_parent_link_text_color . ";":"") . "
	" . ( $subnav_menu_current_parent_bg_color ? "background-color: " . $subnav_menu_current_parent_bg_color . ";":"") . "
}

.menu-secondary .current-menu-item > a {
	" . ( $subnav_menu_current_link_text_color ? "color: " . $subnav_menu_current_link_text_color . ";":"") . "
	" . ( $subnav_menu_current_bg_color ? "background-color: " . $subnav_menu_current_bg_color . ";":"") . "
}

.menu-secondary .sub-menu {
	width:" . $subnav_menu_submenu_width . "px;
}

.menu-secondary a:hover {
	" . ( $subnav_menu_link_text_hover_color ? "color: " . $subnav_menu_link_text_hover_color . ";":"") . "
	" . ( $subnav_menu_hover_bg_color ? "background-color: " . $subnav_menu_hover_bg_color . ";":"") . "
}


/* Base design menu styles */

.menu-primary .sub-menu .sub-menu {
	left: " . ($nav_menu_submenu_width + 10 ) . "px;
}

/*
.menu-primary li {
	margin-bottom: 0;
}

.menu-primary a,
.menu-toggle {
	border: 0 none;
}

.rtl.menu-primary a,
.rtl .menu-toggle {
	border: 0 none;
}

.menu-secondary .sub-menu {
}
*/

.menu-secondary .sub-menu .sub-menu {
	left: " . ($subnav_menu_submenu_width + 10 ) . "px;
}

/*
.menu-secondary a,
.menu-toggle {
	border: 0 none;
}
*/

" . ( (!strcasecmp( $page_bg_color, $nav_menu_link_bg_color )) ? ' ' : ' .nav-primary .genesis-nav-menu > li:first-child { margin-left: 0; } .nav-primary .genesis-nav-menu > li:last-child { margin-right: 0; } ' ) .  PHP_EOL . ( ((!strcasecmp('transparent', $subnav_menu_link_bg_color ) || !strcasecmp('white', $subnav_menu_link_bg_color ) || !strcasecmp('#fff', $subnav_menu_link_bg_color ) || !strcasecmp('#ffffff', $subnav_menu_link_bg_color )) ? ' ' : ' .nav-secondary .genesis-nav-menu > li:first-child { margin-left: 0; } .nav-secondary .genesis-nav-menu > li:last-child { margin-right: 0; } ' ) ) . "

/*! BYLINES */
.entry-meta,
.entry-comments .comment-meta {
	" . ( $byline_font_family ? "font-family: " . $byline_font_family . ";":"") . "
	" . ( $byline_font_size ? "font-size: " . $byline_font_size . "px;":"") . "
	" . ( $byline_font_weight ? "font-weight: " . $byline_font_weight . ";":"") . "
	" . ( $byline_font_style ? "font-style: " . $byline_font_style . ";":"") . "
	" . ( $byline_font_color ? "color: " . $byline_font_color . ";":"") . "
}


.entry-meta a {
	" . ( $byline_font_color ? "color: " . $byline_font_color . ";":"") . "
}


.entry-meta a:hover {
	color:inherit;
}

/*! SB Widget Body */

.widget-wrap {
	" . ( $sidebar_font_family ? "font-family: " . $sidebar_font_family . ";":"") . "
	" . ( $sidebar_font_size ? "font-size: " . $sidebar_font_size . "px;":"") . "
	" . ( $sidebar_font_weight ? "font-weight: " . $sidebar_font_weight . ";":"") . "
	" . ( $sidebar_font_style ? "font-style: " . $sidebar_font_style . ";":"") . "
	" . ( $sidebar_font_color ? "color: " . $sidebar_font_color . ";":"") . "
}

/*! SB Widget Title */
.widget-title, .widgettitle {
	" . ( $sidebar_heading_font_family ? "font-family: " . $sidebar_heading_font_family . ";":"") . "
	" . ( $sidebar_heading_font_size ? "font-size: " . $sidebar_heading_font_size . "px;":"") . "
	" . ( $sidebar_heading_font_weight ? "font-weight: " . $sidebar_heading_font_weight . ";":"") . "
	" . ( $sidebar_heading_font_style ? "font-style: " . $sidebar_heading_font_style . ";":"") . "
	" . ( $sidebar_heading_font_color ? "color: " . $sidebar_heading_font_color . ";":"") . "
}

.widget-title a, .widgettitle a {
	" . ( $sidebar_heading_font_color ? "color: " . $sidebar_heading_font_color . ";":"") . "
}

/*! Footer Widget Body */
.footer-widgets .widget-wrap {
	" . ( $footer_widgets_font_family ? "font-family: " . $footer_widgets_font_family . ";":"") . "
	" . ( $footer_widgets_font_size ? "font-size: " . $footer_widgets_font_size . "px;":"") . "
	" . ( $footer_widgets_font_weight ? "font-weight: " . $footer_widgets_font_weight . ";":"") . "
	" . ( $footer_widgets_font_style ? "font-style: " . $footer_widgets_font_style . ";":"") . "
	" . ( $footer_widgets_font_color ? "color: " . $footer_widgets_font_color . ";":"") . "
}

/*! Footer Widgets Titles */
.footer-widgets .widgettitle {
	" . ( $footer_widgets_heading_font_family ? "font-family: " . $footer_widgets_heading_font_family . ";":"") . "
	" . ( $footer_widgets_heading_font_size ? "font-size: " . $footer_widgets_heading_font_size . "px;":"") . "
	" . ( $footer_widgets_heading_font_weight ? "font-weight: " . $footer_widgets_heading_font_weight . ";":"") . "
	" . ( $footer_widgets_heading_font_style ? "font-style: " . $footer_widgets_heading_font_style . ";":"") . "
	" . ( $footer_widgets_heading_font_color ? "color: " . $footer_widgets_heading_font_color . ";":"") . "
}

/*! Footer */
.site-footer,
#footer {
	" . ( $footer_font_family ? "font-family: " . $footer_font_family . ";":"") . "
	" . ( $footer_font_size ? "font-size: " . $footer_font_size . "px;":"") . "
	" . ( $footer_font_weight ? "font-weight: " . $footer_font_weight . ";":"") . "
	" . ( $footer_font_style ? "font-style: " . $footer_font_style . ";":"") . "
	" . ( $footer_font_color ? "color: " . $footer_font_color . ";":"") . "
}

.site-footer a {
	" . ( $footer_font_color ? "color: " . $footer_font_color . ";":"") . "
	text-decoration:none;
}

.site-footer a:hover {
	" . ( $footer_font_color ? "border-bottom: 1px solid " . $footer_font_color . ";":"") . "
}

.site-footer .lander-nav-footer a:hover {
	" . ( $footer_font_color ? "border-bottom-color: " . $footer_font_color . ";":"") . "
}

.content .sticky {
	padding-left:" . $padding . "px;
	" . ( $primary_link_color ? "border-left: 1px solid " . $primary_link_color . ";":"") . "
}

";



	$resp_css['three-two'] = '
		' . $site_container_three_two . '

		.content-sidebar-sidebar .wrap,
		.sidebar-content-sidebar .wrap,
		.sidebar-sidebar-content .wrap {
			width:' . ( $content_w_3col + $sb1_w_3col + $padding * 3 ) . 'px;
		}
		.content-sidebar-sidebar .sidebar-secondary,
		.sidebar-content-sidebar .sidebar-secondary,
		.sidebar-sidebar-content .sidebar-secondary {
			float: none;
			clear: both;
			width: auto;
		}';

	$resp_css['three-one'] = '
		' . $site_container_three_one . '

		.content-sidebar-sidebar .wrap,
		.sidebar-content-sidebar .wrap,
		.sidebar-sidebar-content .wrap {
			width:' . ( $content_w_3col + $padding * 2 ) . 'px;
			width: auto;
		}
		.content-sidebar-sidebar .wrap .content-sidebar-wrap,
		.sidebar-content-sidebar .wrap .content-sidebar-wrap,
		.sidebar-sidebar-content .wrap .content-sidebar-wrap {
			width:' . ( $content_w_3col ) . 'px;
			width: 100%;
		}
		.content-sidebar-sidebar .content,
		.sidebar-sidebar-content .content,
		.sidebar-content-sidebar .content {
			width: 100%;

		}
		.content-sidebar-sidebar .sidebar-primary,
		.sidebar-content-sidebar .sidebar-primary,
		.sidebar-sidebar-content .sidebar-primary {
			float: none;
			clear: both;
			width: auto;
		}
		.content-sidebar-sidebar .footer-widgets .widget-area,
		.sidebar-content-sidebar .footer-widgets .widget-area,
		.sidebar-sidebar-content .footer-widgets .widget-area {
			float: none;
			clear: both;
			display: block;
			width: auto;
		}';

	$resp_css['three-zero'] = '
		' . $site_container_three_zero . '
		.content-sidebar-sidebar .wrap,
		.sidebar-content-sidebar .wrap,
		.sidebar-sidebar-content .wrap {
			width:auto;
		}
		.content-sidebar-sidebar .wrap .content-sidebar-wrap,
		.sidebar-content-sidebar .wrap .content-sidebar-wrap,
		.sidebar-sidebar-content .wrap .content-sidebar-wrap {
			width:100%;
		}
		.content-sidebar-sidebar .content,
		.sidebar-content-sidebar .content,
		.sidebar-sidebar-content .content {
			width:100%;
		}
		.content-sidebar-sidebar .site-container,
		.sidebar-content-sidebar .site-container,
		.sidebar-sidebar-content .site-container {
			' . ( $page_bg_color ? 'background-color: ' . $page_bg_color . ';':'') . '
		}
		.content-sidebar-sidebar .title-area,
		.sidebar-content-sidebar .title-area,
		.sidebar-sidebar-content .title-area {
			text-align: center;
		}
		.content-sidebar-sidebar .footer-widgets .widget-area,
		.sidebar-content-sidebar .footer-widgets .widget-area,
		.sidebar-sidebar-content .footer-widgets .widget-area {
			float: none;
			clear: both;
			display: block;
			width: auto;
		}';

	$resp_css['two-one'] = '
		' . $site_container_two_one . '

		.sidebar-content .wrap,
		.content-sidebar .wrap {
			width:' . ( $content_w_2col + $padding * 2 ) . 'px;
			width: auto;
		}
		.sidebar-content .wrap .content-sidebar-wrap,
		.content-sidebar .wrap .content-sidebar-wrap {
			width:' . ( $content_w_2col ) . 'px;
			width: auto;

		}
		.content-sidebar .content,
		.sidebar-content .content {
			width: 100%;
		}
		.sidebar-content .sidebar-primary,
		.content-sidebar .sidebar-primary {
			float: none;
			clear: both;
			width: auto;
		}
		.sidebar-content .footer-widgets .widget-area,
		.content-sidebar .footer-widgets .widget-area {
			float: none;
			clear: both;
			width: auto;
			display: block;
		}';

	$resp_css['two-zero'] = '
		' . $site_container_two_zero . '
		.sidebar-content .wrap,
		.content-sidebar .wrap {
			width:auto;
		}
		.sidebar-content .wrap .content-sidebar-wrap,
		.content-sidebar .wrap .content-sidebar-wrap {
			width:100%;
		}
		.sidebar-content .content,
		.content-sidebar .content {
			width:100%;
		}
		.sidebar-content .site-container,
		.content-sidebar .site-container {
			' . ( $page_bg_color ? 'background-color: ' . $page_bg_color . ';':'') . '
		}
		.sidebar-content .title-area,
		.content-sidebar .title-area {
			text-align: center;
		}
		.sidebar-content .footer-widgets .widget-area,
		.content-sidebar .footer-widgets .widget-area {
			float: none;
			clear: both;
			width: auto;
			display: block;
		}';

	$resp_css['one-zero']  = '
		' . $site_container_one_zero . '
		.full-width-content .wrap {
			width:auto;
		}
		.full-width-content .wrap .content-sidebar-wrap {
			width:100%;
		}
		.full-width-content .content {
			width:100%;
		}
		.full-width-content .site-container{
			' . ( $page_bg_color ? 'background-color: ' . $page_bg_color . ';':'') . '
		}
		.full-width-content .title-area {
			text-align:center;
		}
		.full-width-content .footer-widgets .widget-area {
			float: none;
			clear: both;
			width: auto;
			display: block;
		}';
	$resp_css['min-width'] = '
		.content-sidebar-sidebar .wrap,
		.sidebar-content-sidebar .wrap,
		.sidebar-sidebar-content .wrap,
		.content-sidebar-sidebar .wrap,
		.sidebar-content-sidebar .wrap,
		.sidebar-sidebar-content .wrap,
		.sidebar-content .wrap,
		.content-sidebar .wrap,
		.sidebar-content .wrap,
		.content-sidebar .wrap,
		.full-width-content .wrap,
		.full-width-content .wrap {
			padding-left: 35px;
			padding-right: 35px;
		}
		.content-sidebar-sidebar .nav-primary .wrap,
		.sidebar-content-sidebar .nav-primary .wrap,
		.sidebar-sidebar-content .nav-primary .wrap,
		.content-sidebar-sidebar .nav-secondary .wrap,
		.sidebar-content-sidebar .nav-secondary .wrap,
		.sidebar-sidebar-content .nav-secondary .wrap,
		.sidebar-content .nav-primary .wrap,
		.content-sidebar .nav-primary .wrap,
		.sidebar-content .nav-secondary .wrap,
		.content-sidebar .nav-secondary .wrap,
		.full-width-content .nav-primary .wrap,
		.full-width-content .nav-secondary .wrap {
			padding: 0 0 0 0;
		}
		.nav-primary,
		.nav-secondary {
			margin-left: 35px;
			margin-right: 35px;
			display: none;
		}
		.genesis-nav-menu li,
		.site-header ul.genesis-nav-menu {
			float: none;
		}
		.menu-toggle,
		.sub-menu-toggle {
			display: block;
			visibility: visible;
		}
		.genesis-nav-menu .sub-menu {
			width: auto;
			box-shadow: none;
		}
		
		.genesis-nav-menu .sub-menu a {
		}
		.genesis-nav-menu .sub-menu li:first-child a {
			
		}
		.menu-toggle {
			margin-left: 35px;
			margin-right: 35px;
		}
		.menu-toggle:before {
			content: "\2261\00a0Menu";
		}
		.menu-toggle.activated:before {
			content: "\2191\00a0Menu";
		}
		.sub-menu-toggle:before {
			content: "+";
		}
		.sub-menu-toggle.activated:before {
			content: "-";
		}
		.genesis-nav-menu .menu-item {
			position: relative;
			display: block;
		}
		.nav-primary .genesis-nav-menu .sub-menu,
		.nav-secondary .genesis-nav-menu .sub-menu {
			display: none;
			opacity: 1;
			position: static;
			border-left: none;
		}
		.genesis-nav-menu .sub-menu .sub-menu {
			margin: 0;
		}
		.nav-primary .menu .sub-menu,
		.nav-secondary .menu .sub-menu {
			padding-left: 1.618em;
		}';

	$responsive        = '';
	$media_queries_css = '';
	$resp_css          = apply_filters( 'lander_media_queries', $resp_css, $widths, $settings );
	arsort( $widths );
	$resp_css = sortArrayByArray( $resp_css, $widths );
	foreach ( $widths as $layout_css => $width ) {
		$media_queries_css .= '@media only screen and (max-width: ' . $width . 'px) {' . "\n";
		$media_queries_css .= $resp_css[$layout_css];
		$media_queries_css .= '}' . "\n";
	}
	$css = apply_filters( 'lander_settings_css', $css, $settings, $widths );
	$css = $css . $media_queries_css;
	if ( !$writecss ) {
		return $css;
	}
	$layoutfile = lander_get_res( 'file', 'settingscss' );
	touch( $layoutfile );
	if ( is_writable( $layoutfile ) ) {
		$contents = lander_clean_css( $css );
		$contents = apply_filters( 'lander_settings', $contents );
		$res      = @fopen( $layoutfile, 'w' );
		if ( is_resource( $res ) ) {
			fwrite( $res, $contents );
			fclose( $res );
			wp_cache_flush();
		}
	} else {
		printf( __( '<p>Lander Notice: The %s file is not writable.</p>', CHILD_DOMAIN ), $layoutfile );
	}
	$lander_debug = lander_get_res( 'dir' ) . 'debug.css';
	file_put_contents( $lander_debug, apply_filters( 'lander_settings', $css ) );
	//llog($css);
}

/**
 * Debug function to help return calling function name
 * @param bool $completeTrace 
 * @return array
 * @since 1.0
 */
function getCallingFunctionName( $completeTrace = false ) {
	$trace = debug_backtrace();
	if ( $completeTrace ) {
		$str = '';
		foreach ( $trace as $caller ) {
			$str .= " -- Called by {$caller['function']}";
			if ( isset( $caller['class'] ) )
				$str .= " From Class {$caller['class']}";
		}
	} else {
		$caller = $trace[2];
		$str    = "Called by {$caller['function']}";
		if ( isset( $caller['class'] ) )
			$str .= " From Class {$caller['class']}";
	}
	return $str;
}

/**
 * Sorts an array by a certain order. Used to sort dynamic media queries
 * @param type array $tosort 
 * @param type array $orderArray 
 * @return array
 * @since 1.0
 */
function sortArrayByArray( array $tosort, array $orderArray ) {
	$ordered = array();
	foreach ( $orderArray as $key => $value ) {
		if ( array_key_exists( $key, $tosort ) ) {
			$ordered[$key] = $tosort[$key];
		}
	}
	return $ordered;
}

add_filter( 'lander_settings', 'landing_settings_header' );

/**
 * Returns a string containing a user warning message to be used in the settings CSS file.
 * @param string $css 
 * @return string
 * @since 1.0
 */
function landing_settings_header( $css ) {
	$msg = '/*!' . __( 'Do not edit this file directly. It\'s always overwritten upon saving of settings.', CHILD_DOMAIN ) . '*/';
	return $msg . $css;
}

$lander_user_path;

/**
 * Initializes the lander user directory and files
 * @param string $path 
 * @return bool true upon success else false
 * @since 1.0
 */
function lander_init_path( $path ) {
	if ( !is_dir( $path ) ) {
		if ( !wp_mkdir_p( $path ) ) {
			return false;
		}
	}

	global $lander_user_path;

	if ( is_dir( $lander_user_path ) ) {
		$css = $lander_user_path . '/style.scss';
		$php = $lander_user_path . '/functions.php';
		if ( !file_exists( $css ) ) {
			$msg       = __( "/**************** Place all your css customizations in the style.scss file *****************/\n\n", CHILD_DOMAIN );
			$style_out = file_put_contents( $css, $msg );
		}
		if ( !file_exists( $php ) ) {
			$msg           = __( "<?php\n/******** Place all your wp/php tweaks here ****************/\n/******** This is your master functions.php file ******/\n", CHILD_DOMAIN );
			$functions_out = file_put_contents( $php, $msg );
		}
	}

	if ( !file_exists( $php ) || !file_exists( $css ) ) {
		return false;
	}

	return true;
}


/**
 * Returns the requested resource (url or path of the requested directory or file from lander-user)
 * @param string $info (type of information requested… url/path of a directory or a file)
 * @param type $res (the resource for which the information has been requested… settings.css etc.)
 * @return type
 * @since 1.0
 */
function lander_get_res( $info = false, $res = null ) {
	$loc     = '';
	$uploads = wp_upload_dir();
	if ( $info == 'fileurl' || $info == 'dirurl' ) {
		$loc = $uploads['baseurl'] . '/lander-user/';
	} else {
		$loc = $uploads['basedir'] . '/lander-user/';
	}
	global $lander_user_path;
	$lander_user_path = $uploads['basedir'] . '/lander-user';
	if ( lander_init_path( $lander_user_path ) ) {
		if ( $info == 'dir' || $info == 'dirurl' ) {
			return $loc;
		}
		switch ( $res ) {
		case 'userphp':
			$loc .= 'functions.php';
			break;
		case 'usercss':
			$loc .= 'autogenerated.css';
			break;
		case 'usersass':
			$loc .= 'style.scss';
			break;
		case 'settingscss':
			$loc .= 'settings.css';
			break;
		default:
			break;
		}
		return apply_filters('lander_get_res',$loc);
	}
	return false;
}

/**
 * Cleans/minifies a given CSS
 * @param string $css 
 * @return string CSS
 * @since 1.0
 */

function lander_clean_css( $css ) {
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
	$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
	$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
	return apply_filters( 'lander_clean_css', $css );
}