<?php

add_post_type_support( bbp_get_forum_post_type(), 'genesis-layouts' );
add_post_type_support( bbp_get_forum_post_type(), 'genesis-seo' );
add_action( 'genesis_title', 'lander_bbpress_init' );
add_filter( 'genesis_pre_get_option_site_layout', 'lander_bbpress_layout' );

/**
 * Profile View Fixes on bbPress/frontend profile view
 * 
 */
if ( !is_admin() ) {
	remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );
}

/**
 * Removes default content hooked by Genesis on bbPress pages
 * @return none
 */
function lander_bbpress_init() {
	if ( is_bbpress() ) {
		remove_action( 'genesis_before_post_content', 'genesis_post_info' );
		remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_post_content', 'genesis_do_post_image' );
		remove_action( 'genesis_post_content', 'genesis_do_post_content' );
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
		remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
		remove_action( 'genesis_after_post', 'genesis_do_author_box_single' );
		remove_action( 'genesis_entry_footer', 'genesis_do_author_box_single' );
		remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
		add_action( 'genesis_post_content', 'the_content' );
		add_action( 'genesis_entry_content', 'the_content' );
	}
}


/**
 * Allow overriding of bbPress layout
 * @param string $layout 
 * @return string $layout 
 */
//allow overriding of bbpress layout
function lander_bbpress_layout( $layout ) {

	if ( !is_bbpress() ) {
		return $layout;
	}

	//get current layout saved in genesis setting
	$gsettings = get_option( GENESIS_SETTINGS_FIELD, null );
	$layout    = isset( $gsettings['site_layout'] ) ? $gsettings['site_layout'] : null;


	$set = genesis_get_option( 'bbpress-layout', CHILD_SETTINGS_FIELD, false );
	$set = ( $set == 'default' ) ? $layout : $set; // if no setting in lander defined then use Genesis setting

	$forum_id    = bbp_get_forum_id();
	$forumlayout = false;
	if ( !empty( $forum_id ) ) {
		$forumlayout = esc_attr( get_post_meta( $forum_id, '_genesis_layout', true ) );
		if ( !empty( $forumlayout ) ) {
			return $forumlayout;
		}
	}

	if ( empty( $forumlayout ) ) {
		return $set;
	}
	if ( is_user_logged_in() ) {
	}

	return $set;
}
