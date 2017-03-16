<?php

/**
 * Builds a list of schemas
 * @return array
 * @since 1.0
 */
function lander_get_schemas() {
	$lander_schemas = array(
		'none' => array(
			'name' => __( 'Default (Creative Work)', CHILD_DOMAIN ),
			'itemtype' => '',
			'context' => ''
		),
		'about-page' => array(
			'name' => __( 'About Page', CHILD_DOMAIN ),
			'itemtype' => 'http://schema.org/AboutPage',
			'context' => 'body'
		),
		'contact-page' => array(
			'name' => __( 'Contact Page', CHILD_DOMAIN ),
			'itemtype' => 'http://schema.org/ContactPage',
			'context' => 'body'
		),
		'profile-page' => array(
			'name' => __( 'Profile Page', CHILD_DOMAIN ),
			'itemtype' => 'http://schema.org/ProfilePage',
			'context' => 'body'
		),
		'qa-page' => array(
			'name' => __( 'QA Page', CHILD_DOMAIN ),
			'itemtype' => 'http://schema.org/QAPage',
			'context' => 'body'
		),
		'article' => array(
			'name' => __( 'Article', CHILD_DOMAIN ),
			'itemtype' => 'http://schema.org/Article',
			'context' => 'entry'
		),
		'review' => array(
			'name' => __( 'Review', CHILD_DOMAIN ),
			'itemtype' => 'http://schema.org/Review',
			'context' => 'entry'
		),
		'recipe' => array(
			'name' => __( 'Recipe', CHILD_DOMAIN ),
			'itemtype' => 'http://schema.org/Recipe',
			'context' => 'entry'
		)
	);

	return apply_filters( 'lander_schemas', $lander_schemas );
}

/**
 * Return a particular schema from the list of all schemas
 * @param string $schema 
 * @return string
 * @since 1.0
 */
function lander_get_schema( $schema ) {
	if ( !$schema )
		return;

	$schemas = lander_get_schemas();
	if ( array_key_exists( $schema, $schemas ) ) {
		return $schemas[$schema];
	}
}

add_action( 'add_meta_boxes', 'lander_add_schema_settings_box' );

/**
 * Adds the schema metabox at the post editor screen
 * @return type
 * @since 1.0
 */
function lander_add_schema_settings_box() {
	foreach ( (array) get_post_types( array(
				'public' => true
			) ) as $type ) {
		if ( post_type_supports( $type, 'lander-schema' ) ) {
			add_meta_box( 'landerschema', sprintf( __( '%s Schema', CHILD_DOMAIN ), CHILD_THEME_NAME ), 'lander_schema_meta_box', $type, 'side', 'default' );
		}
	}
}


/**
 * Outputs the markup of the schema metabox
 * @param type $post 
 * @return none
 * @since 1.0
 */
function lander_schema_meta_box( $post ) {
	global $post;
	if ( get_option( 'show_on_front' ) == 'page' ) {
		$posts_page_id = get_option( 'page_for_posts' );
		if ( $posts_page_id == $post->ID ) {
			echo '<p><em>' . __( 'Lander Schema is not available on on the posts page.', CHILD_DOMAIN ) . '</em></p>';
			return;
		}
	}
	wp_nonce_field( 'lander_schema_box', 'lander_schema_box_nonce' );
	$lander_schema  = get_post_meta( $post->ID, '_lander_schema', 1 );
	$current_schema = isset( $lander_schema ) ? $lander_schema : false;
	echo '<p>' . __( 'Depending on the semantics of this content you may select a specific schema to override the default.', CHILD_DOMAIN ) . '</p>';
	echo '<select id="lander_schema" name="lander_schema">';
	$lander_schemas = lander_get_schemas();

	foreach ( $lander_schemas as $key => $value ) {
		$selected = selected( $key, $current_schema, 0 );
		echo '<option ' . $selected . ' value="' . $key . '">' . $value['name'] . '</option>' . "\n";
	}
	echo '</select>';
}


add_action( 'save_post', 'lander_save_schema_settings' );

/**
 * Saves the schema settings when the post is saved
 * @param type $post_id 
 * @return none
 * @since 1.0
 */
function lander_save_schema_settings( $post_id ) {
	if ( !isset( $_POST['lander_schema_box_nonce'] ) ) {
		return;
	}
	if ( !wp_verify_nonce( $_POST['lander_schema_box_nonce'], 'lander_schema_box' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	
	/* It's safe for us to save the data now */
	// Make sure it is set
	if ( !isset( $_POST['lander_schema'] ) ) {
		return;
	}
	$lander_schema = isset( $_POST['lander_schema'] ) ? $_POST['lander_schema'] : false;
	
	update_post_meta( $post_id, '_lander_schema', $lander_schema );
}
