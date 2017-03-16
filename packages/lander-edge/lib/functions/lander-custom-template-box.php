<?php

add_action( 'add_meta_boxes', 'lander_template_box' );

/**
 * Adds a meta box that allows us to choose a custom page template
 * @return none
 * @since 1.0
 */
function lander_template_box() {
	$screens = array(
		'page'
	);

	foreach ( $screens as $screen ) {
		add_meta_box( 'landerpagetemplate', __( 'Template', CHILD_DOMAIN ), 'lander_custom_template_meta_box', $screen, 'side', 'core' );
	}

}


/**
 * Outputs the html markup of the meta box that allows us to choose a custom page template
 * @return none
 * @since 1.0
 */
function lander_custom_template_meta_box( $post ) {
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'lander_custom_template_meta_box', 'lander_custom_template_nonce' );
	$value            = get_post_meta( $post->ID, '_lander_page_template', true );
	$lander_templates = lander_get_templates( $post );

	echo '<p>';
	_e( 'Select the page template to be used for this page.', CHILD_DOMAIN );
	echo '</p>';
	echo '<select type="text" id="lander-page-template" name="lander-page-template" value="' . esc_attr( $value ) . '">';
	echo PHP_EOL . '<option value="default">Default Template</option>';
	$current = get_post_meta( $post->ID, '_wp_page_template', true );

	foreach ( $lander_templates as $key => $value ) {
		$selected = selected( $current, $key, false );
		echo PHP_EOL . '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . $value . '</option>';
	}
	echo '</select>';

	echo '<style type="text/css">
	#parent_id +p,#page_template {display:none;}
	</style>';
}

/**
 * Gets all the custom templates stored inside the current design directory
 * @param type $post 
 * @return ? templates
 * @since 1.0
 */
function lander_get_templates( $post ) {
	$page_templates = ''; // wp_cache_get( 'page_templates' );
	$page_templates = array();
	$files = glob( LANDER_CORE_DESIGNS . '/*.php' );

	foreach ( $files as $file => $full_path ) {
		if ( !preg_match( '|Template Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
			continue;
		}
		$page_templates[basename( $full_path )] = _cleanup_header_comment( $header[1] );
	}

	if ( wp_get_theme()->parent() ) {
		$page_templates = wp_get_theme()->parent()->get_page_templates( $post ) + $page_templates;
	}
	
	return apply_filters( 'lander_page_templates', $page_templates, $post );
}


add_action( 'save_post', 'lander_save_template_box_data' );
/**
 * Save the setting of our custom page template chooser
 * @param type $post_id 
 * @return none
 * @since 1.0
 */
function lander_save_template_box_data( $post_id ) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( !isset( $_POST['lander_custom_template_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( !wp_verify_nonce( $_POST['lander_custom_template_nonce'], 'lander_custom_template_meta_box' ) ) {
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
	// Make sure that it is set.
	if ( !isset( $_POST['lander-page-template'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['lander-page-template'] );
	// Update the meta field in the database.

	update_post_meta( $post_id, '_wp_page_template', $my_data );
}



add_filter( 'page_template', 'lander_template_locate' );
/**
 * Locates the custom template to use when displaying a page on the front-end
 * @param type $template 
 * @return string path to the custom template
 * @since 1.0
 */
function lander_template_locate( $template ) {
	$current = get_page_template_slug();
	if ( empty( $current ) ) {
		return $template;
	}
	if ( basename( $template ) != $current ) { // If Wordpress hasn't been able to locate our custom template
		if ( file_exists( LANDER_CORE_DESIGNS . "/$current" ) ) { // And if such a template exists
			return LANDER_CORE_DESIGNS . "/$current";
		} else {
			// echo 'such file doesn\'t exist';
		}
	}
	return $template;
}