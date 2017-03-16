<?php

add_action( 'add_meta_boxes', 'lander_add_mobile_template_settings_box' );

/**
 * Inserts the Mobile Landing Page Settings meta box
 * @return none
 * @since 1.0
 */
function lander_add_mobile_template_settings_box() {
	foreach ( (array) get_post_types( array(
				'public' => true
			) ) as $type ) {
		if ( post_type_supports( $type, 'lander-mobile-experience' ) ) {
			add_meta_box( 'lpmobsettings', sprintf( __( '%s Mobile Experience', CHILD_DOMAIN ), CHILD_THEME_NAME ), 'lander_mobile_template_meta_box', $type, 'side', 'default' );
		}
	}
}


/**
 * Displays mobile elements toggle fields for mobile template meta box
 * @return none
 * @since 1.0
 */
function lander_mobile_template_meta_box( $post ) {

	global $post, $typenow;

	wp_enqueue_script( 'lander-admin-js', CHILD_URL . '/lib/js/lander-admin.js' );

	$lander_mobile_landing_options = get_post_meta( $post->ID, '_lander_mobile_landing_options', 1 );

	$use_global = isset( $lander_mobile_landing_options['mobile_use_global'] ) ? $lander_mobile_landing_options['mobile_use_global'] : true;

	$hide_header = isset( $lander_mobile_landing_options['mobile-hide-header'] ) ? $lander_mobile_landing_options['mobile-hide-header'] : false;

	$hide_breadcrumbs = isset( $lander_mobile_landing_options['mobile-hide-breadcrumbs'] ) ? $lander_mobile_landing_options['mobile-hide-breadcrumbs'] : false;

	$hide_page_title = isset( $lander_mobile_landing_options['mobile-hide-page-title'] ) ? $lander_mobile_landing_options['mobile-hide-page-title'] : false;

	$hide_widgets_above_header = isset( $lander_mobile_landing_options['mobile-hide-widgets-above-header'] ) ? $lander_mobile_landing_options['mobile-hide-widgets-above-header'] : false;

	$hide_widgets_below_header = isset( $lander_mobile_landing_options['mobile-hide-widgets-below-header'] ) ? $lander_mobile_landing_options['mobile-hide-widgets-below-header'] : false;

	$hide_widgets_above_footer = isset( $lander_mobile_landing_options['mobile-hide-widgets-above-footer'] ) ? $lander_mobile_landing_options['mobile-hide-widgets-above-footer'] : false;

	$hide_after_entry_widget = isset( $lander_mobile_landing_options['mobile-hide-after-entry-widget'] ) ? $lander_mobile_landing_options['mobile-hide-after-entry-widget'] : false;

	$hide_sidebars = isset( $lander_mobile_landing_options['mobile-hide-sidebars'] ) ? $lander_mobile_landing_options['mobile-hide-sidebars'] : false;

	$hide_footer_widgets = isset( $lander_mobile_landing_options['mobile-hide-footer-widgets'] ) ? $lander_mobile_landing_options['mobile-hide-footer-widgets'] : false;

	$hide_footer = isset( $lander_mobile_landing_options['mobile-hide-footer'] ) ? $lander_mobile_landing_options['mobile-hide-footer'] : false;

	wp_nonce_field( 'lander_mobile_landing_field', 'lander_mobile_landing_nonce' );

	?>
	<p><?php _e( 'The elements you hide here will be hidden from users visiting your site on mobile, but will still be visible to users on other viewports.', CHILD_DOMAIN ); ?></p>
	<p><em><?php printf( __( 'These options will override the global settings as set on <a href="%s">%s Settings</a> screen.', CHILD_DOMAIN ), menu_page_url( CHILD_SETTINGS_FIELD_EXTRAS, false ), CHILD_THEME_NAME ); ?></em></p>

	<p>
		<input type="checkbox" id="mobile_use_global" value="true" name="mobile_use_global" <?php checked( $use_global, true ); ?> />
		<label for="mobile_use_global"><?php _e( 'Use global settings', CHILD_DOMAIN ); ?>
	</p>

	<div id="mobile-global-lp">
	<p>
		<input type="checkbox" id="mobile-hide-header" value="true" name="mobile-hide-header" <?php checked( $hide_header, true ); ?> />
		<label for="mobile-hide-header"><?php _e( 'Hide Header', CHILD_DOMAIN ); ?></label>
	</p>
	<?php

	// building the conditional UI for hide breadcrumbs
	if ( $typenow == 'page' ) {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			$front_page = get_option( 'page_on_front' );
			$blog_page  = get_option( 'page_for_posts' );
			if ( $front_page == $post->ID ) {
				if ( genesis_get_option( 'breadcrumb_front_page' ) == 1 ) {
?>
					<p>
					<input type="checkbox" id="mobile-hide-breadcrumbs" name="mobile-hide-breadcrumbs" <?php
					checked( $hide_breadcrumbs, true );
					?> value="true" />
					<label for="mobile-hide-breadcrumbs"><?php
					_e( 'Hide Breadcrumbs', CHILD_DOMAIN );
					?></label>
					</p>
					<?php
				}
			} else {
				if ( $blog_page == $post->ID ) {
					if ( genesis_get_option( 'breadcrumb_posts_page' ) == 1 ) {
?>
						<p>
						<input type="checkbox" id="mobile-hide-breadcrumbs" name="mobile-hide-breadcrumbs" <?php
						checked( $hide_breadcrumbs, true );
						?> value="true" />
						<label for="mobile-hide-breadcrumbs"><?php
						_e( 'Hide Breadcrumbs', CHILD_DOMAIN );
						?></label>
						</p>
						<?php
					}
				} else {
					if ( genesis_get_option( 'breadcrumb_page' ) == 1 ) {
?>
						<p>
						<input type="checkbox" id="mobile-hide-breadcrumbs" name="mobile-hide-breadcrumbs" <?php
						checked( $hide_breadcrumbs, true );
						?> value="true" />
						<label for="mobile-hide-breadcrumbs"><?php
						_e( 'Hide Breadcrumbs', CHILD_DOMAIN );
						?></label>
						</p>
						<?php
					}
				}
			}
		} else {
			if ( genesis_get_option( 'breadcrumb_page' ) == 1 ) {
?>
				<p>
				<input type="checkbox" id="mobile-hide-breadcrumbs" name="mobile-hide-breadcrumbs" <?php
				checked( $hide_breadcrumbs, true );
				?> value="true" />
				<label for="mobile-hide-breadcrumbs"><?php
				_e( 'Hide Breadcrumbs', CHILD_DOMAIN );
				?></label>
				</p>
				<?php
			}
		}
	} else {
		if ( genesis_get_option( 'breadcrumb_single' ) ) {
		?>
			<p>
			<input type="checkbox" id="mobile-hide-breadcrumbs" name="mobile-hide-breadcrumbs" <?php
			checked( $hide_breadcrumbs, true );
			?> value="true" />
			<label for="mobile-hide-breadcrumbs"><?php
			_e( 'Hide Breadcrumbs', CHILD_DOMAIN );
			?></label>
			</p>
			<?php
		}
	}

	?>
	<p>
		<input type="checkbox" id="mobile-hide-page-title" name="mobile-hide-page-title" <?php checked( $hide_page_title, true ); ?> value="true" />
		<label for="mobile-hide-page-title"><?php _e( 'Hide Page Title', CHILD_DOMAIN ); ?></label>
	</p>
	<?php

	// building the conditional UI for hide Widgets Above Header
	do {
		// Bail if a WooCommerce page
		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) && ( $post->ID == wc_get_page_id( 'shop' ) || $post->ID == wc_get_page_id( 'cart' ) || $post->ID == wc_get_page_id( 'checkout' ) || $post->ID == wc_get_page_id( 'myaccount' ) ) ) {
			break;
		}

		if ( $typenow == 'page' ) {
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$front_page = get_option( 'page_on_front' );
				$blog_page  = get_option( 'page_for_posts' );
				if ( $front_page == $post->ID ) {
					if ( genesis_get_option( 'widgets_before_header_front', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
					?>
						<p>
						<input type="checkbox" id="mobile-hide-widgets-above-header" name="mobile-hide-widgets-above-header" <?php
						checked( $hide_widgets_above_header, true );
						?> value="true" />
						<label for="mobile-hide-widgets-above-header"><?php
						_e( 'Hide Widgets Above Header', CHILD_DOMAIN );
						?></label>
						</p>
						<?php
					}
				} else {
					if ( $blog_page == $post->ID ) {
						if ( genesis_get_option( 'widgets_before_header_posts_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
						?>
							<p>
							<input type="checkbox" id="mobile-hide-widgets-above-header" name="mobile-hide-widgets-above-header" <?php
							checked( $hide_widgets_above_header, true );
							?> value="true" />
							<label for="mobile-hide-widgets-above-header"><?php
							_e( 'Hide Widgets Above Header', CHILD_DOMAIN );
							?></label>
							</p>
							<?php
						}
					} else {
						if ( genesis_get_option( 'widgets_before_header_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
						?>
							<p>
							<input type="checkbox" id="mobile-hide-widgets-above-header" name="mobile-hide-widgets-above-header" <?php
							checked( $hide_widgets_above_header, true );
							?> value="true" />
							<label for="mobile-hide-widgets-above-header"><?php
							_e( 'Hide Widgets Above Header', CHILD_DOMAIN );
							?></label>
							</p>
							<?php
						}
					}
				}
			} else {
				if ( genesis_get_option( 'widgets_before_header_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
				?>
					<p>
					<input type="checkbox" id="mobile-hide-widgets-above-header" name="mobile-hide-widgets-above-header" <?php
					checked( $hide_widgets_above_header, true );
					?> value="true" />
					<label for="mobile-hide-widgets-above-header"><?php
					_e( 'Hide Widgets Above Header', CHILD_DOMAIN );
					?></label>
					</p>
					<?php
				}
			}
		} else {
			if ( genesis_get_option( 'widgets_before_header_post', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
			?>
				<p>
				<input type="checkbox" id="mobile-hide-widgets-above-header" name="mobile-hide-widgets-above-header" <?php
				checked( $hide_widgets_above_header, true );
				?> value="true" />
				<label for="mobile-hide-widgets-above-header"><?php
				_e( 'Hide Widgets Above Header', CHILD_DOMAIN );
				?></label>
				</p>
				<?php
			}
		}
	} while(false);

	// building the conditional UI for hide Widgets Below Header
	do {
		// Bail if a WooCommerce page
		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) && ( $post->ID == wc_get_page_id( 'shop' ) || $post->ID == wc_get_page_id( 'cart' ) || $post->ID == wc_get_page_id( 'checkout' ) || $post->ID == wc_get_page_id( 'myaccount' ) ) ) {
			break;
		}

		if ( $typenow == 'page' ) {
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$front_page = get_option( 'page_on_front' );
				$blog_page  = get_option( 'page_for_posts' );
				if ( $front_page == $post->ID ) {
					if ( genesis_get_option( 'widgets_after_header_front', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
					?>
						<p>
						<input type="checkbox" id="mobile-hide-widgets-below-header" name="mobile-hide-widgets-below-header" <?php
						checked( $hide_widgets_below_header, true );
						?> value="true" />
						<label for="mobile-hide-widgets-below-header"><?php
						_e( 'Hide Widgets Below Header', CHILD_DOMAIN );
						?></label>
						</p>
						<?php
					}
				} else {
					if ( $blog_page == $post->ID ) {
						if ( genesis_get_option( 'widgets_after_header_posts_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
						?>
							<p>
							<input type="checkbox" id="mobile-hide-widgets-below-header" name="mobile-hide-widgets-below-header" <?php
							checked( $hide_widgets_below_header, true );
							?> value="true" />
							<label for="mobile-hide-widgets-below-header"><?php
							_e( 'Hide Widgets Below Header', CHILD_DOMAIN );
							?></label>
							</p>
							<?php
						}
					} else {
						if ( genesis_get_option( 'widgets_after_header_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
						?>
							<p>
							<input type="checkbox" id="mobile-hide-widgets-below-header" name="mobile-hide-widgets-below-header" <?php
							checked( $hide_widgets_below_header, true );
							?> value="true" />
							<label for="mobile-hide-widgets-below-header"><?php
							_e( 'Hide Widgets Below Header', CHILD_DOMAIN );
							?></label>
							</p>
							<?php
						}
					}
				}
			} else {
				if ( genesis_get_option( 'widgets_after_header_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
				?>
					<p>
					<input type="checkbox" id="mobile-hide-widgets-below-header" name="mobile-hide-widgets-below-header" <?php
					checked( $hide_widgets_below_header, true );
					?> value="true" />
					<label for="mobile-hide-widgets-below-header"><?php
					_e( 'Hide Widgets Below Header', CHILD_DOMAIN );
					?></label>
					</p>
					<?php
				}
			}
		} else {
			if ( genesis_get_option( 'widgets_after_header_post', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
			?>
				<p>
				<input type="checkbox" id="mobile-hide-widgets-below-header" name="mobile-hide-widgets-below-header" <?php
				checked( $hide_widgets_below_header, true );
				?> value="true" />
				<label for="mobile-hide-widgets-below-header"><?php
				_e( 'Hide Widgets Below Header', CHILD_DOMAIN );
				?></label>
				</p>
				<?php
			}
		}
	} while(false);

	// building the conditional UI for hide Widgets Above Footer
	do {
		// Bail if a WooCommerce page
		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) && ( $post->ID == wc_get_page_id( 'shop' ) || $post->ID == wc_get_page_id( 'cart' ) || $post->ID == wc_get_page_id( 'checkout' ) || $post->ID == wc_get_page_id( 'myaccount' ) ) ) {
			break;
		}

		if ( $typenow == 'page' ) {
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$front_page = get_option( 'page_on_front' );
				$blog_page  = get_option( 'page_for_posts' );
				if ( $front_page == $post->ID ) {
					if ( genesis_get_option( 'widgets_above_footer_front', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
	?>
						<p>
						<input type="checkbox" id="mobile-hide-widgets-above-footer" name="mobile-hide-widgets-above-footer" <?php
						checked( $hide_widgets_above_footer, true );
						?> value="true" />
						<label for="mobile-hide-widgets-above-footer"><?php
						_e( 'Hide Widgets Above Footer', CHILD_DOMAIN );
						?></label>
						</p>
						<?php
					}
				} else {
					if ( $blog_page == $post->ID ) {
						if ( genesis_get_option( 'widgets_above_footer_posts_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
	?>
							<p>
							<input type="checkbox" id="mobile-hide-widgets-above-footer" name="mobile-hide-widgets-above-footer" <?php
							checked( $hide_widgets_above_footer, true );
							?> value="true" />
							<label for="mobile-hide-widgets-above-footer"><?php
							_e( 'Hide Widgets Above Footer', CHILD_DOMAIN );
							?></label>
							</p>
							<?php
						}
					} else {
						if ( genesis_get_option( 'widgets_above_footer_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
	?>
							<p>
							<input type="checkbox" id="mobile-hide-widgets-above-footer" name="mobile-hide-widgets-above-footer" <?php
							checked( $hide_widgets_above_footer, true );
							?> value="true" />
							<label for="mobile-hide-widgets-above-footer"><?php
							_e( 'Hide Widgets Above Footer', CHILD_DOMAIN );
							?></label>
							</p>
							<?php
						}
					}
				}
			} else {
				if ( genesis_get_option( 'widgets_above_footer_page', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
	?>
					<p>
					<input type="checkbox" id="mobile-hide-widgets-above-footer" name="mobile-hide-widgets-above-footer" <?php
					checked( $hide_widgets_above_footer, true );
					?> value="true" />
					<label for="mobile-hide-widgets-above-footer"><?php
					_e( 'Hide Widgets Above Footer', CHILD_DOMAIN );
					?></label>
					</p>
					<?php
				}
			}
		} else {
			if ( genesis_get_option( 'widgets_above_footer_post', CHILD_SETTINGS_FIELD_EXTRAS, false ) ) {
			?>
				<p>
				<input type="checkbox" id="mobile-hide-widgets-above-footer" name="mobile-hide-widgets-above-footer" <?php
				checked( $hide_widgets_above_footer, true );
				?> value="true" />
				<label for="mobile-hide-widgets-above-footer"><?php
				_e( 'Hide Widgets Above Footer', CHILD_DOMAIN );
				?></label>
				</p>
				<?php
			}
		}
	} while(false);

	// conditional UI for After Entry widget area
	if ( $typenow == 'post' ) {
		if ( current_theme_supports( 'genesis-after-entry-widget-area' ) ) {
		?>
			<p>
				<input type="checkbox" id="mobile-hide-after-entry-widget" name="mobile-hide-after-entry-widget" <?php checked( $hide_after_entry_widget, true ); ?> value="true" />
				<label for="mobile-hide-after-entry-widget"><?php _e( 'Hide After Entry Widgets', CHILD_DOMAIN ); ?></label>
			</p>
		<?php
		}
	}

	?>
	<p>
		<input type="checkbox" id="mobile-hide-sidebars" name="mobile-hide-sidebars" <?php checked( $hide_sidebars, true ); ?> value="true" />
		<label for="mobile-hide-sidebars"><?php _e( 'Hide Sidebar(s)', CHILD_DOMAIN ); ?></label>
	</p>
	<?php
	
	$global_fwidgets = genesis_get_option( 'footer-widgets', CHILD_SETTINGS_FIELD_EXTRAS, false );

	if ( $global_fwidgets ) {
	?>
		<p>
			<input type="checkbox" id="mobile-hide-footer-widgets" name="mobile-hide-footer-widgets" <?php checked( $hide_footer_widgets, true ); ?> value="true" />
			<label for="mobile-hide-footer-widgets"><?php _e( 'Hide Footer Widgets', CHILD_DOMAIN ); ?></label>
		</p>
	<?php
	}
	
	?>
	<p>
		<input type="checkbox" id="mobile-hide-footer" name="mobile-hide-footer" <?php checked( $hide_footer, true ); ?> value="true" />
		<label for="mobile-hide-footer"><?php _e( 'Hide Footer', CHILD_DOMAIN ); ?></label>
	</p>
	</div>
	<?php

}

add_action( 'save_post', 'lander_save_mobile_template_settings' );

/**
 * Save the mobile landing page settings when saving the page/post
 * @param type $post_id 
 * @return none
 * @since 1.0
 */
function lander_save_mobile_template_settings( $post_id ) {

	// Check if our nonce is set.
	if ( !isset( $_POST['lander_mobile_landing_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( !wp_verify_nonce( $_POST['lander_mobile_landing_nonce'], 'lander_mobile_landing_field' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* It's safe for us to save the data now. */

	$lander_mobile_landing_options = array();

	$lander_mobile_landing_options['mobile_use_global'] = isset( $_POST['mobile_use_global'] ) ? true : false;

	if ( $lander_mobile_landing_options['mobile_use_global'] ) {

		$global_hide_breadcrumbs = genesis_get_option( 'mlp_hide_breadcrumbs', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$global_hide_widgets_above_header = genesis_get_option( 'mlp_hide_widgets_above_header', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$global_hide_widgets_below_header = genesis_get_option( 'mlp_hide_widgets_below_header', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$global_hide_widgets_above_footer = genesis_get_option( 'mlp_hide_widgets_above_footer', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$global_hide_sidebars = genesis_get_option( 'mlp_hide_sidebars', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$global_hide_fwidgets = genesis_get_option( 'mlp_hide_fwidgets', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$lander_mobile_landing_options['mobile-hide-breadcrumbs'] = ( $global_hide_breadcrumbs == true ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-widgets-above-header'] = ( $global_hide_widgets_above_header == true ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-widgets-below-header'] = ( $global_hide_widgets_below_header == true ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-widgets-above-footer'] = ( $global_hide_widgets_above_footer == true ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-sidebars'] = ( $global_hide_sidebars == true ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-footer-widgets'] = ( $global_hide_fwidgets == true ) ? true : false;

		$lander_mobile_options = get_post_meta( $post_id, '_lander_mobile_landing_options', TRUE );

		$lander_mobile_options['mobile_use_global'] = isset( $_POST['mobile_use_global'] ) ? true : false;

		update_post_meta( $post_id, '_lander_mobile_landing_options', $lander_mobile_options );

	} else {

		$lander_mobile_landing_options['mobile_use_global'] = isset( $_POST['mobile_use_global'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-header'] = isset( $_POST['mobile-hide-header'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-breadcrumbs'] = isset( $_POST['mobile-hide-breadcrumbs'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-page-title'] = isset( $_POST['mobile-hide-page-title'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-widgets-above-header'] = isset( $_POST['mobile-hide-widgets-above-header'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-widgets-below-header'] = isset( $_POST['mobile-hide-widgets-below-header'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-widgets-above-footer'] = isset( $_POST['mobile-hide-widgets-above-footer'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-after-entry-widget'] = isset( $_POST['mobile-hide-after-entry-widget'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-sidebars'] = isset( $_POST['mobile-hide-sidebars'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-footer-widgets'] = isset( $_POST['mobile-hide-footer-widgets'] ) ? true : false;

		$lander_mobile_landing_options['mobile-hide-footer'] = isset( $_POST['mobile-hide-footer'] ) ? true : false;

		update_post_meta( $post_id, '_lander_mobile_landing_options', $lander_mobile_landing_options );

	}

}

add_action( 'wp_head', 'lander_display_mobile_view', 10 );

/**
 * Used to build/show the mobile template on the front-end
 * @return type
 * @since 1.0
 */
function lander_display_mobile_view() {

	// Bail if not visiting on mobile
	if ( !lander_is_mobile() )
		return;

	if ( is_home() ) {
		$page_id = get_option( 'page_for_posts' );
	} else {
		if( lander_is_woo_shop() ) {
			$page_id = wc_get_page_id( 'shop' );
		} else {
			$page_id = get_the_ID();
		}
	}


	$lander_mobile_landing_optionset = get_post_meta( $page_id, '_lander_mobile_landing_options', 1 );

	$use_global = ( is_array( $lander_mobile_landing_optionset ) && array_key_exists( 'mobile_use_global', $lander_mobile_landing_optionset ) ) ? $lander_mobile_landing_optionset['mobile_use_global'] : true;

	if ( $use_global ) {

		$hide_header = genesis_get_option( 'mlp_hide_header', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_breadcrumbs = genesis_get_option( 'mlp_hide_breadcrumbs', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_page_title = genesis_get_option( 'mlp_hide_page_title', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_widgets_above_header = genesis_get_option( 'mlp_hide_widgets_above_header', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_widgets_below_header = genesis_get_option( 'mlp_hide_widgets_below_header', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_widgets_above_footer = genesis_get_option( 'mlp_hide_widgets_above_footer', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_after_entry_widget = genesis_get_option( 'mlp_hide_after_entry_widget', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_sidebars = genesis_get_option( 'mlp_hide_sidebars', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_footer_widgets = genesis_get_option( 'mlp_hide_fwidgets', CHILD_SETTINGS_FIELD_EXTRAS, false );

		$hide_footer = genesis_get_option( 'mlp_hide_footer', CHILD_SETTINGS_FIELD_EXTRAS, false );

	} else {

		if( !is_singular() && !lander_is_woo_shop() )
			return;
		
		$hide_header = isset( $lander_mobile_landing_optionset['mobile-hide-header'] ) ? $lander_mobile_landing_optionset['mobile-hide-header'] : false;

		$hide_breadcrumbs = isset( $lander_mobile_landing_optionset['mobile-hide-breadcrumbs'] ) ? $lander_mobile_landing_optionset['mobile-hide-breadcrumbs'] : false;

		$hide_page_title = isset( $lander_mobile_landing_optionset['mobile-hide-page-title'] ) ? $lander_mobile_landing_optionset['mobile-hide-page-title'] : false;

		$hide_widgets_above_header = isset( $lander_mobile_landing_optionset['mobile-hide-widgets-above-header'] ) ? $lander_mobile_landing_optionset['mobile-hide-widgets-above-header'] : false;

		$hide_widgets_below_header = isset( $lander_mobile_landing_optionset['mobile-hide-widgets-below-header'] ) ? $lander_mobile_landing_optionset['mobile-hide-widgets-below-header'] : false;

		$hide_widgets_above_footer = isset( $lander_mobile_landing_optionset['mobile-hide-widgets-above-footer'] ) ? $lander_mobile_landing_optionset['mobile-hide-widgets-above-footer'] : false;

		$hide_after_entry_widget = isset( $lander_mobile_landing_optionset['mobile-hide-after-entry-widget'] ) ? $lander_mobile_landing_optionset['mobile-hide-after-entry-widget'] : false;

		$hide_sidebars = isset( $lander_mobile_landing_optionset['mobile-hide-sidebars'] ) ? $lander_mobile_landing_optionset['mobile-hide-sidebars'] : false;

		$hide_footer_widgets = isset( $lander_mobile_landing_optionset['mobile-hide-footer-widgets'] ) ? $lander_mobile_landing_optionset['mobile-hide-footer-widgets'] : false;

		$hide_footer = isset( $lander_mobile_landing_optionset['mobile-hide-footer'] ) ? $lander_mobile_landing_optionset['mobile-hide-footer'] : false;

	}

	/** Disable options if selected in the metabox. Hides the relevant page elements if disabled on a page **/
	if ( $hide_header ) {
		remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
		remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
		remove_action( 'genesis_header', 'genesis_do_header' );
	}

	if ( $hide_breadcrumbs ) {
		// Do not remove breadcrumbs as per SEO guidelines; hide it via CSS instead
		add_filter( 'body_class', 'lander_seo_hide_breadcrumbs' );
	}

	if ( $hide_page_title ) {
		if ( !is_home() ) {
			add_filter( 'post_class', 'lander_hide_title_class' );
		}
		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
			if( is_woocommerce() || is_cart() || is_checkout() ) {
				add_filter( 'woocommerce_show_page_title', '__return_false' );
			}
		}
	}

	if ( $hide_widgets_above_header ) {
		remove_action( 'genesis_before_header', 'lander_sidebar_before_header' );
	}

	if ( $hide_widgets_below_header ) {
		remove_action( 'genesis_after_header', 'lander_sidebar_after_header' );
	}

	if ( $hide_widgets_above_footer ) {
		remove_action( 'genesis_before_footer', 'lander_sidebar_above_footer', 5 );
	}

	if ( $hide_after_entry_widget ) {
		remove_theme_support( 'genesis-after-entry-widget-area' );
	}

	if ( $hide_sidebars ) {
		// Force full-width-content layout setting
		add_filter( 'genesis_site_layout', 'lander_force_full_width_layout' );

	}

	if ( $hide_footer_widgets ) {
		remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
	}

	if ( $hide_footer ) {
		remove_action( 'genesis_footer', 'lander_do_nav_footer', 5 );
		remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
		remove_action( 'genesis_footer', 'genesis_footer_markup_close', 5 );
		remove_action( 'genesis_footer', 'genesis_do_footer' );
	}

}