<?php

/**
 * Class that generates the Lander Admin Settings UI. See Genesis_Admin_Boxes for more information.
 *
 * @since 1.0
 *
 */
class Lander_Extras extends Genesis_Admin_Boxes {
	
	function __construct() {
		$page_id          = CHILD_SETTINGS_FIELD_EXTRAS;
		$menu_ops         = array(
			'submenu' => array(
				'parent_slug' => __('genesis', CHILD_DOMAIN),
				'page_title' => sprintf( __('%s Settings', CHILD_DOMAIN), CHILD_THEME_NAME ),
				'menu_title' => sprintf( __('%s Settings', CHILD_DOMAIN), CHILD_THEME_NAME ),
			)
		);
		$page_ops         = array(
			'screen_icon' => 'themes'
		);
		$settings_field   = CHILD_SETTINGS_FIELD_EXTRAS;
		$default_settings = lander_admin_defaults();
		$this->create($page_id, $menu_ops, $page_ops, $settings_field, $default_settings);
		add_action('admin_print_styles', array(
			$this,
			'styles'
		));
		add_action('genesis_settings_sanitizer_init', array(
			$this,
			'sanitization_filters'
		));
	}
	
	function styles() {
		wp_enqueue_style('lander-admin-style', CHILD_URL . '/lib/css/lander-admin.css');
	}
	
	function help() {
	}
	
	function metaboxes() {
		
		add_action('genesis_admin_before_metaboxes', array(
			$this,
			'hidden_fields'
		));
		add_meta_box('lander_google_fonts', __('Google Fonts Fetcher', CHILD_DOMAIN), array(
			$this,
			'lander_gfonts_api_box'
		), $this->pagehook, 'main');
		
		add_meta_box('lander_widget_settings_box', __('Widget Areas', CHILD_DOMAIN), array(
			$this,
			'widget_settings_box'
		), $this->pagehook, 'main');
		
		add_meta_box('lander_mobile_landing_box', __('Mobile Template Settings', CHILD_DOMAIN), array(
			$this,
			'lander_mobile_settings_box'
		), $this->pagehook, 'main');
		
		add_meta_box('lander_debug_settings_box', __('Debug Tools', CHILD_DOMAIN), array(
			$this,
			'debug_settings_box'
		), $this->pagehook, 'main');

		// Add Lander Help metabox
		add_action($this->pagehook . '_settings_page_boxes', 'lander_admin_sidebar');
		
	}
	
	function hidden_fields($pagehook) {
		if ($pagehook !== $this->pagehook) {
			return;
		}
		?>
			<input class="new-design" type="hidden" id="<?php echo $this->settings_field; ?>[settings-version]" name="<?php echo $this->settings_field; ?>[settings-version]" value="<?php echo CHILD_THEME_VERSION; ?>" />
		<?php
	}
	
	function save($newsettings, $oldsettings) {
				
		$newsettings['gfonts-api-key'] = empty($newsettings['gfonts-api-key']) ? '' : trim( $newsettings['gfonts-api-key'] );
		
		if ($newsettings['gfonts-api-key'] != $oldsettings['gfonts-api-key']) {
			//just reset the lander_fonts_list transient so that it can be rebuilt if a new key has been set
			delete_transient('lander_fonts_list');
		}
		
		if ( function_exists( 'w3tc_flush_all' ) ) { //check if W3Total cache is installed and active
			w3tc_flush_all(); //flush the entire cache since we've updated theme settings, widget output and more
		}

		return $newsettings;
		
	}
	
	function notices() {
		
		if ( ! genesis_is_menu_page( $this->page_id ) ) {
			return;
		}
	
		if ( isset( $_REQUEST['settings-updated'] ) && 'true' === $_REQUEST['settings-updated'] )
			echo '<div id="message" class="updated"><p><strong>' . $this->page_ops['saved_notice_text'] . '</strong></p></div>';
		elseif ( isset( $_REQUEST['reset'] ) && 'true' === $_REQUEST['reset'] )
			echo '<div id="message" class="updated"><p><strong>' . $this->page_ops['reset_notice_text'] . '</strong></p></div>';
		elseif ( isset( $_REQUEST['error'] ) && 'true' === $_REQUEST['error'] )
			echo '<div id="message" class="updated"><p><strong>' . $this->page_ops['error_notice_text'] . '</strong></p></div>';
			
	}
	
	function sanitization_filters() {
		
		genesis_add_option_filter('no_html', $this->settings_field, array(
			'gfonts-api-key',
		));
		
		genesis_add_option_filter('absint', $this->settings_field, array(
			'footer-widgets-count'
		));
		
		genesis_add_option_filter('one_zero', $this->settings_field, array(
			'footer-widgets',
			
			'widgets_before_header_front',
			'widgets_before_header_posts_page',
			
			'widgets_before_header_home',
			'widgets_before_header_post',
			'widgets_before_header_page',
			'widgets_before_header_archives',
			'widgets_before_header_404',
			
			'widgets_after_header_front',
			'widgets_after_header_posts_page',
			
			'widgets_after_header_home',
			'widgets_after_header_post',
			'widgets_after_header_page',
			'widgets_after_header_archives',
			'widgets_after_header_404',
			
			'widgets_above_footer_front',
			'widgets_above_footer_posts_page',
			'widgets_above_footer_home',
			'widgets_above_footer_post',
			'widgets_above_footer_page',
			'widgets_above_footer_archives',
			'widgets_above_footer_404',
			
			'mlp_hide_breadcrumbs',
			'mlp_hide_widgets_above_header',
			'mlp_hide_widgets_below_header',
			'mlp_hide_widgets_above_footer',
			'mlp_hide_sidebars',
			
			'widget-default-content-enabled',
			'custom-functions-enabled',
			'custom-style-enabled',
		));
		
	}
	
	function scripts() {
		
		parent::scripts();
		genesis_load_admin_js();
		wp_enqueue_script('lander-admin-js', CHILD_URL . '/lib/js/lander-admin.js');
		wp_localize_script('lander-admin-js', 'lander', array(
			'pageHook' => $this->pagehook,
			'firstTime' => !is_array(get_user_option('closedpostboxes_' . $this->pagehook)),
			'confirmUpgrade' => sprintf( __( "Updating %s will overwrite the core designs inside Lander.\nIf you haven't already done so, move them to lander-user&raquo;designs folder.\nAre you sure you ready to update Lander?. \"Cancel\" to stop, \"OK\" to update.", CHILD_DOMAIN ), CHILD_THEME_NAME )
		));
		
	}
	
	/* Callback for Credentials metabox */
	function lander_gfonts_api_box() {
		
		//clear the updated and installed flags when settings are saved upon those pages.
		$url = esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$url = preg_replace( '/(?<!settings-)updated=true/', 'updated=false', $url );
		$url = str_ireplace( 'installed=true', 'installed=false', $url );
	
		?>
		<input type="hidden" name="_wp_http_referer" value="<?php echo $url; ?>" />
		
		<h4><?php _e( 'Google Fonts API Key', CHILD_DOMAIN ); ?></h4>
		<p>
			<label for="<?php echo $this->settings_field; ?>[gfonts-api-key]"><?php printf( __( 'A Google Fonts API Key allows you to get the latest fonts from Google &#40;else you can only use the ones that ship with %s&#41;. <a href="https://developers.google.com/fonts/docs/developer_api#Auth" target="_blank">Get Google Fonts API Key here.</a>', CHILD_DOMAIN ), CHILD_THEME_NAME ); ?></label>
		</p>
		<p>
			<input class="gfonts-api-key" style="width:50%" placeholder="Google Fonts API Key" type="text" id="<?php echo $this->settings_field; ?>[gfonts-api-key]" name="<?php echo $this->settings_field; ?>[gfonts-api-key]" value="<?php echo genesis_get_option('gfonts-api-key', $this->settings_field, false); ?>" />
		</p>
		<?php
	
	}
	
	
	/* Callback for Widget Areas metabox  */
	function widget_settings_box() {
		
?>
		<div class="gl-section">
		<div class="gl-desc">
			<table class="gl-cta-table">
				<tr>
				<td class="gl-label">
					<p>
					<label for="<?php
		echo $this->settings_field . '[footer-widgets]';
?>"><?php
		_e('Footer Widgets Support', CHILD_DOMAIN);
?></label>
					</p>
				</td>
				<td class="lander-input gl-fw-select" colspan="2">
				<?php
		
		echo '<select id="' . $this->settings_field . '[footer-widgets]" name="' . $this->settings_field . '[footer-widgets]">';
		
		$fwnum = array(
			'Enabled' => 1,
			'Disabled' => 0
		);
		
		$fwn = genesis_get_option('footer-widgets', $this->settings_field, false);
		foreach ($fwnum as $wnum => $val) {
			
			echo "<option " . selected($val, $fwn, false) . " value=\"$val\">" . $wnum . "</option>\n";
			
		}
		echo '</select>';
		
?>
				</td></tr>
				<tr id="fwidget-count">
				<!--<div id="fwidget-count">-->
				<td class="gl-label">
				<p>
				<label for="<?php
		echo $this->settings_field . '[footer-widgets-count]';
?>"><?php
		_e('No. of Footer Widgets', CHILD_DOMAIN);
?></label>
					</p>
				</td>
				<td class="lander-input gl-fw-select"> 
				<?php
		$fwidgets_count = array(
			'One',
			'Two',
			'Three',
			'Four'
		);
?>
					<?php
		echo '<select id="' . $this->settings_field . '[footer-widgets-count]" name="' . $this->settings_field . '[footer-widgets-count]">';
		$wcount = genesis_get_option('footer-widgets-count', $this->settings_field, false);
		foreach ($fwidgets_count as $count => $fwidget_count) {
			$count++;
			echo "<option " . selected($count, $wcount, false) . " value=\"$count\">" . ucwords($fwidget_count) . "</option>\n";
		}
		echo '</select>';
?>
				</td>
				<!--</div>-->
				</tr>
				<tr>
					<td class="gl-label">
						<p><?php
		_e('Widgets Above Header', CHILD_DOMAIN);
?></p>
					</td>
					<td class="lander-input" colspan="2">
						<p>						
							<?php
		if ('page' === get_option('show_on_front')) {
			$custom_front = 'checkbox';
			$home_blog    = 'hidden';
		} else {
			$custom_front = 'hidden';
			$home_blog    = 'checkbox';
		}
?>
								<label class="lander-<?php
		echo $custom_front;
?>" for="<?php
		echo $this->get_field_id('widgets_before_header_front');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_before_header_front'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_before_header_front');
?>" id="<?php
		echo $this->get_field_id('widgets_before_header_front');
?>" /><?php
		_e('Front Page', CHILD_DOMAIN);
?></label>
								<label class="lander-<?php
		echo $custom_front;
?>" for="<?php
		echo $this->get_field_id('widgets_before_header_posts_page');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_before_header_posts_page'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_before_header_posts_page');
?>" id="<?php
		echo $this->get_field_id('widgets_before_header_posts_page');
?>" /><?php
		_e('Posts Page', CHILD_DOMAIN);
?></label>
							
							
								<label class="lander-<?php
		echo $home_blog;
?>" for="<?php
		echo $this->get_field_id('widgets_before_header_home');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_before_header_home'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_before_header_home');
?>" id="<?php
		echo $this->get_field_id('widgets_before_header_home');
?>" /><?php
		_e('Home Page', CHILD_DOMAIN);
?></label>
							
							
						<label for="<?php
		echo $this->get_field_id('widgets_before_header_post');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_before_header_post'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_before_header_post');
?>" id="<?php
		echo $this->get_field_id('widgets_before_header_post');
?>" /><?php
		_e('Post', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_before_header_page');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_before_header_page'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_before_header_page');
?>" id="<?php
		echo $this->get_field_id('widgets_before_header_page');
?>" /><?php
		_e('Page', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_before_header_archives');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_before_header_archives'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_before_header_archives');
?>" id="<?php
		echo $this->get_field_id('widgets_before_header_archives');
?>" /><?php
		_e('Archives', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_before_header_404');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_before_header_404'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_before_header_404');
?>" id="<?php
		echo $this->get_field_id('widgets_before_header_404');
?>" /><?php
		_e('404', CHILD_DOMAIN);
?></label>
						</p>
					</td>
				</tr>
				<tr>
					<td class="gl-label">
						<p><?php
		_e('Widgets Below Header', CHILD_DOMAIN);
?></p>
					</td>
					<td class="lander-input" colspan="2">
						<p>
							
								<label class="lander-<?php
		echo $custom_front;
?>" for="<?php
		echo $this->get_field_id('widgets_after_header_front');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_after_header_front'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_after_header_front');
?>" id="<?php
		echo $this->get_field_id('widgets_after_header_front');
?>" /><?php
		_e('Front Page', CHILD_DOMAIN);
?></label>				
								<label class="lander-<?php
		echo $custom_front;
?>" for="<?php
		echo $this->get_field_id('widgets_after_header_posts_page');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_after_header_posts_page'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_after_header_posts_page');
?>" id="<?php
		echo $this->get_field_id('widgets_after_header_posts_page');
?>" /><?php
		_e('Posts Page', CHILD_DOMAIN);
?></label>										
							
								<label class="lander-<?php
		echo $home_blog;
?>" for="<?php
		echo $this->get_field_id('widgets_after_header_home');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_after_header_home'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_after_header_home');
?>" id="<?php
		echo $this->get_field_id('widgets_after_header_home');
?>" /><?php
		_e('Home Page', CHILD_DOMAIN);
?></label>
							
						<label for="<?php
		echo $this->get_field_id('widgets_after_header_post');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_after_header_post'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_after_header_post');
?>" id="<?php
		echo $this->get_field_id('widgets_after_header_post');
?>" /><?php
		_e('Post', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_after_header_page');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_after_header_page'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_after_header_page');
?>" id="<?php
		echo $this->get_field_id('widgets_after_header_page');
?>" /><?php
		_e('Page', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_after_header_archives');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_after_header_archives'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_after_header_archives');
?>" id="<?php
		echo $this->get_field_id('widgets_after_header_archives');
?>" /><?php
		_e('Archives', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_after_header_404');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_after_header_404'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_after_header_404');
?>" id="<?php
		echo $this->get_field_id('widgets_after_header_404');
?>" /><?php
		_e('404', CHILD_DOMAIN);
?></label>
						</p>
					</td>
				</tr>
				
				<tr>
					<td class="gl-label">
						<p><?php
		_e('Widgets Above Footer', CHILD_DOMAIN);
?></p>
					</td>
					<td class="lander-input" colspan="2">
						<p>
							
								<label class="lander-<?php
		echo $custom_front;
?>" for="<?php
		echo $this->get_field_id('widgets_above_footer_front');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_above_footer_front'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_above_footer_front');
?>" id="<?php
		echo $this->get_field_id('widgets_above_footer_front');
?>" /><?php
		_e('Front Page', CHILD_DOMAIN);
?></label>
								<label class="lander-<?php
		echo $custom_front;
?>" for="<?php
		echo $this->get_field_id('widgets_above_footer_posts_page');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_above_footer_posts_page'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_above_footer_posts_page');
?>" id="<?php
		echo $this->get_field_id('widgets_above_footer_posts_page');
?>" /><?php
		_e('Posts Page', CHILD_DOMAIN);
?></label>
							
								<label class="lander-<?php
		echo $home_blog;
?>" for="<?php
		echo $this->get_field_id('widgets_above_footer_home');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_above_footer_home'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_above_footer_home');
?>" id="<?php
		echo $this->get_field_id('widgets_above_footer_home');
?>" /><?php
		_e('Home Page', CHILD_DOMAIN);
?></label>
							
						<label for="<?php
		echo $this->get_field_id('widgets_above_footer_post');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_above_footer_post'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_above_footer_post');
?>" id="<?php
		echo $this->get_field_id('widgets_above_footer_post');
?>" /><?php
		_e('Post', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_above_footer_page');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_above_footer_page'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_above_footer_page');
?>" id="<?php
		echo $this->get_field_id('widgets_above_footer_page');
?>" /><?php
		_e('Page', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_above_footer_archives');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_above_footer_archives'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_above_footer_archives');
?>" id="<?php
		echo $this->get_field_id('widgets_above_footer_archives');
?>" /><?php
		_e('Archives', CHILD_DOMAIN);
?></label>
						<label for="<?php
		echo $this->get_field_id('widgets_above_footer_404');
?>"><input value="1"<?php
		checked($this->get_field_value('widgets_above_footer_404'), '1');
?> type="checkbox" name="<?php
		echo $this->get_field_name('widgets_above_footer_404');
?>" id="<?php
		echo $this->get_field_id('widgets_above_footer_404');
?>" /><?php
		_e('404', CHILD_DOMAIN);
?></label>
						</p>
					</td>
				</tr>
				 			 
			</table>
		  </div>
		</div>
		<?php
	}
	
	
	/* Callback for Mobile Template Settings metabox */
	function lander_mobile_settings_box() {
		
?>
		<p><?php _e( 'Use these settings to globally enable disable the following elements on the site. These settings will only take effect when user is viewing the site on a mobile viewport.', CHILD_DOMAIN ); ?></p>
		<p><em><?php printf( __( 'Note: You can also change these settings on a per page / post basis using the <strong>%s Mobile Experience</strong> metabox on the page / post edit screen.', CHILD_DOMAIN ), CHILD_THEME_NAME ); ?></em></p>
		<div class="gl-section">
		<div class="gl-desc">
		<table>
			<?php
				if ( genesis_get_option( 'breadcrumb_home' ) == 1 || genesis_get_option( 'breadcrumb_single' ) == 1 || genesis_get_option( 'breadcrumb_page' ) == 1 || genesis_get_option( 'breadcrumb_archive' ) == 1 || genesis_get_option( 'breadcrumb_404' ) == 1 || genesis_get_option( 'breadcrumb_attachment' ) == 1 ) {
			?>
			<tr>
				<td class="gl-label">
				<p><label for="<?php
		$this->field_id('mlp_hide_breadcrumbs');
?>"><?php
		_e('Hide Breadcrumbs', CHILD_DOMAIN);
?></label></p>
				</td>
				<td>
				<p><input type="checkbox" id="<?php
		$this->field_id('mlp_hide_breadcrumbs');
?>" name="<?php
		$this->field_name('mlp_hide_breadcrumbs');
?>" value="1" <?php
		checked($this->get_field_value('mlp_hide_breadcrumbs'), '1');
?> /></p>
				</td>
			</tr>
			<?php } ?>
			
			<tr>
				<td class="gl-label">
				<p><label for="<?php
		$this->field_id('mlp_hide_widgets_above_header');
?>"><?php
		_e('Hide Widgets Above Header', CHILD_DOMAIN);
?></label></p>
				</td>
				<td>
				<p><input type="checkbox" id="<?php
		$this->field_id('mlp_hide_widgets_above_header');
?>" name="<?php
		$this->field_name('mlp_hide_widgets_above_header');
?>" value="1" <?php
		checked($this->get_field_value('mlp_hide_widgets_above_header'), '1');
?> /></p>
				</td>
			</tr>
			
			<tr>
				<td class="gl-label">
				<p><label for="<?php
		$this->field_id('mlp_hide_widgets_below_header');
?>"><?php
		_e('Hide Widgets Below Header', CHILD_DOMAIN);
?></label></p>
				</td>
				<td>
				<p><input type="checkbox" id="<?php
		$this->field_id('mlp_hide_widgets_below_header');
?>" name="<?php
		$this->field_name('mlp_hide_widgets_below_header');
?>" value="1" <?php
		checked($this->get_field_value('mlp_hide_widgets_below_header'), '1');
?> /></p>
				</td>
			</tr>
			
			<tr>
				<td class="gl-label">
				<p><label for="<?php
		$this->field_id('mlp_hide_widgets_above_footer');
?>"><?php
		_e('Hide Widgets Above Footer', CHILD_DOMAIN);
?></label></p>
				</td>
				<td>
				<p><input type="checkbox" id="<?php
		$this->field_id('mlp_hide_widgets_above_footer');
?>" name="<?php
		$this->field_name('mlp_hide_widgets_above_footer');
?>" value="1" <?php
		checked($this->get_field_value('mlp_hide_widgets_above_footer'), '1');
?> /></p>
				</td>
			</tr>
			
			<tr>
				<td class="gl-label">
				<p><label for="<?php
		$this->field_id('mlp_hide_sidebars');
?>"><?php
		_e('Hide Sidebar(s)', CHILD_DOMAIN);
?></label></p>
				</td>
				<td>
				<p><input type="checkbox" id="<?php
		$this->field_id('mlp_hide_sidebars');
?>" name="<?php
		$this->field_name('mlp_hide_sidebars');
?>" value="1" <?php
		checked($this->get_field_value('mlp_hide_sidebars'), '1');
?> /></p>
				</td>
			</tr>
			
			
			<?php
		$global_fwidgets = genesis_get_option('footer-widgets', CHILD_SETTINGS_FIELD_EXTRAS, false);
		
		if ($global_fwidgets) {
?>
				<tr>
					<td class="gl-label">
					<p><label for="<?php
			$this->field_id('mlp_hide_fwidgets');
?>"><?php
			_e('Hide Footer Widgets', CHILD_DOMAIN);
?></label></p>
					</td>
					<td>
					<p><input type="checkbox" id="<?php
			$this->field_id('mlp_hide_fwidgets');
?>" name="<?php
			$this->field_name('mlp_hide_fwidgets');
?>" value="1" <?php
			checked($this->get_field_value('mlp_hide_fwidgets'), '1');
?> /></p>
					</td>
				</tr>
				<?php
		}
?>
	
		</table>
		</div>
		</div>
		<?php
		
	}
	
	
	/* Callback for Debug Tools metabox */
	function debug_settings_box() {
		$master_functions_path = lander_get_res(false, 'userphp');
		
		$master_functions_exists = '';
		if (!file_exists($master_functions_path)) {
			$master_functions_exists = 'hidden';
		}
		
		
		$master_scss_path = lander_get_res(false, 'usersass');
		
		$master_scss_exists = '';
		if (!file_exists($master_scss_path)) {
			$master_scss_exists = 'hidden';
		}
		
?>
			<div class="gl-section">
			  <div class="gl-desc">
				<table class="gl-col-table">
					<tr>
					<td colspan="2">
							<p class="l-desc"><?php
		_e('For troubleshooting and debugging purposes, you can enable or disable certain functionality from right here.', CHILD_DOMAIN);
?></p>
					</td>
					</tr>
					
					<tr>
					<td class="gl-label">
						<p><label for="<?php
		echo $this->settings_field . '[widget-default-content-enabled]';
?>"><?php
		_e('Show default content in empty widget areas.', CHILD_DOMAIN);
?></label></p>
					</td>
					<td class="gl-checkbox">
						<p><input id="<?php
		echo $this->settings_field . '[widget-default-content-enabled]';
?>" name="<?php
		echo $this->settings_field . '[widget-default-content-enabled]';
?>" type="checkbox" value="1" <?php
		checked(genesis_get_option('widget-default-content-enabled', $this->settings_field, false), '1');
?> title="<?php
		_e('Uncheck to hide default content inside empty widget areas.', CHILD_DOMAIN);
?>" /></p>
					</td>
					</tr>
					
					<tr class="gl-rule"><td class="gl-rule" colspan="3"></td></tr>
					
					<tr class="<?php
		echo ($master_functions_exists == 'hidden' && $master_scss_exists == 'hidden') ? 'hidden' : '';
?>">
					<td colspan="2"><strong>Global Customizations</strong></td>
					</tr>
					
					<tr class="<?php
		echo $master_functions_exists;
?>">
					<td class="gl-label">
					<p>
					<label for="<?php
		echo $this->settings_field . '[custom-functions-enabled]';
?>" title="<?php
		if (!empty($master_functions_path)) {
			printf(__('Uncheck to disable user functions stored in %s.', CHILD_DOMAIN), $master_functions_path);
		}
?>"><?php
		_e('Enable master &ldquo;functions.php&rdquo;.', CHILD_DOMAIN);
?></label>
					</p>
					</td>
					<td class="gl-checkbox">
						<p>
						<input id="<?php
		echo $this->settings_field . '[custom-functions-enabled]';
?>" name="<?php
		echo $this->settings_field . '[custom-functions-enabled]';
?>"  type="checkbox" value="1" <?php
		checked(genesis_get_option('custom-functions-enabled', $this->settings_field, false), '1');
?> title="<?php
		if (!empty($master_functions_path)) {
			printf(__('Uncheck to disable user functions stored in %s.', CHILD_DOMAIN), $master_functions_path);
		}
?>" />
						</p>
					</td>
					</tr>
					
					<tr class="<?php
		echo $master_scss_exists;
?>">
					<td class="gl-label">
						<p>
						<label for="<?php
		echo $this->settings_field . '[custom-style-enabled]';
?>" title="<?php
		if (!empty($master_scss_path)) {
			printf(__('Uncheck to disable user styles stored in %s.', CHILD_DOMAIN), $master_scss_path);
		}
?>"><?php
		_e('Enable master &ldquo;style.scss&rdquo;.', CHILD_DOMAIN);
?></label>
						</p>
					</td>
					<td class="gl-checkbox">
						<p>
						<input id="<?php
		echo $this->settings_field . '[custom-style-enabled]';
?>" name="<?php
		echo $this->settings_field . '[custom-style-enabled]';
?>" type="checkbox" value="1" <?php
		checked(genesis_get_option('custom-style-enabled', $this->settings_field, false), '1');
?> title="<?php
		if (!empty($master_scss_path)) {
			printf(__('Uncheck to disable user styles stored in %s.', CHILD_DOMAIN), $master_scss_path);
		}
?>" />
						</p>
					</td>
					</tr>
					
				</table>
			  </div>
			</div>
		<?php
	}
	
	
}