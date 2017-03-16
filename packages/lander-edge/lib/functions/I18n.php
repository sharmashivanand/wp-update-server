<?php
/**
 * This file deals with making the Lander child theme translatable.
 *
 * @package Lander
 */
// used for theme localization
load_child_theme_textdomain( CHILD_DOMAIN, CHILD_DIR . '/lib/languages' );
$locale      = get_locale();
$locale_file = CHILD_DIR . '/lib/languages' . "/$locale.php";
if ( is_readable( $locale_file ) ) {
	require_once $locale_file;
}
