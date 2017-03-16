<?php

if ( !defined( 'SORT_NATURAL' ) ) {
	define( 'SORT_NATURAL', 5 );
}

/**
 * Returns the font weight of a given font
 * @param string $font_id 
 * @return array or font weights else default array of normal and bold.
 * @since 1.0
 */

function lander_font_weights( $font_id ) {
	if ( $font_id ) {
		$font_specs = lander_get_font( $font_id );
		return $font_specs['font_weights'];
	} else {
		return array(
			'normal',
			'bold'
		);
	}
}


/***************************************************************************
 ************************ New super font functions **************************
 ***************************************************************************/

/**
 * Returns the font weight from a given font variant eg 300italic will return 300
 * @param string $variant 
 * @return string $weight
 * @since 1.0
 */
function lander_get_font_weight( $variant ) {

	// If this is inherit then do nothing
	if ( stristr( $variant, 'inherit' ) ) {
		{
			//return 'inherit';
		}
	}

	// Get the font weight from the variant
	$weight = intval( $variant, 10 );

	// If there is no integer and font variant doesn't contain the string 'regular' then use the default font weight
	if ( !$weight || stristr( $variant, 'regular' ) ) {
		$weight = '400';
	}
	return $weight;
}


/**
 * Returns the font style from a font variant string
 * @param string $variant 
 * @return string $style (bold italic etc.)
 * @since 1.0
 */
function lander_get_font_style( $variant ) {
	$style = "inherit";	// if no font-style is declared, then use inherit
	if ( $variant == 'italic' || stristr( $variant, 'italic' ) ) {
		$style = 'italic';
	}
	return $style;
}


/**
 * Returns a specific font from the entire list of fonts
 * @param string $font 
 * @return array? $font
 * @since 1.0
 */
function lander_get_font( $font ) {
	$all_fonts = lander_get_super_fonts();
	$font      = $all_fonts[$font];
	return $font;
}


/**
 * Returns the fallback font-stack for a font-family of a given fontID
 * @param string $font_id 
 * @return string font-family
 * @since 1.0
 */
function lander_get_font_family( $font_id ) {
	$font        = lander_get_font( $font_id );
	$font_family = $font['name'];

	if ( $font_family == 'inherit' )
		return 'inherit';

	$font_family = '"' . $font_family . '"';
	
	if ( $font['category'] == 'handwriting' || $font['category'] == 'display' ) {
		$font_family .= ', cursive';
	} else {
		$font_family .= ', ' . $font['category'];
	}

	return $font_family;
}


/**
 * Builds and returns the complete list of available fonts for use in Lander
 * @return string fonts list
 * @since 1.0
 */
function lander_get_super_fonts() {

	// Uncomment this to force a refresh
	//delete_transient( 'lander_fonts_list' );

	if ( !get_transient( 'lander_fonts_list' ) ) { // if the fonts are not already saved in a transient then build the array and save it into a transient with 24hr expiry.
		$json = array();

		$gfonts_key = genesis_get_option( 'gfonts-api-key', CHILD_SETTINGS_FIELD_EXTRAS, false );

		if ( $gfonts_key ) {
			$gfont_parm = '&key=' . $gfonts_key;
			$json       = wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity' . $gfont_parm, array(
					'sslverify' => false
				) );

			if ( !is_wp_error( $json ) ) { //if the wp_remote_get call was successful
				$web_fonts = json_decode( $json['body'], true );
				// Validate response from gfont url
				if ( !isset( $web_fonts['error'] ) ) { // if the server response doesn't contain the error
					$json = $web_fonts;
				} else {
					unset( $json );
					$json = array();
				}
			} else {
				unset( $json );
				$json = array();
			}
		}

		if ( ( !$gfonts_key || !$json ) && file_exists( CHILD_DIR . '/fonts/g-web-fonts.json' ) ) {
			$json = file_get_contents( CHILD_DIR . '/fonts/g-web-fonts.json' );
			$json = json_decode( $json, 1 );
		}

		$web_fonts = array();
		foreach ( $json['items'] as $item ) {

			$urls = array();

			// Get font properties from json array.
			foreach ( $item['variants'] as $variant ) {

				$name           = str_replace( ' ', '+', $item['family'] );
				$urls[$variant] = "https://fonts.googleapis.com/css?family={$name}:{$variant}";

			}

			$atts = array(
				'name' => $item['family'],
				'category' => $item['category'],
				'font_type' => 'google',
				'font_weights' => $item['variants'],
				'subsets' => $item['subsets'],
				'files' => $item['files'],
				'urls' => $urls
			);

			// Add this font to the fonts array
			$id             = strtolower( str_replace( ' ', '_', $item['family'] ) );
			$web_fonts[$id] = $atts;
		}


		// Declare default font list
		$websafe_list = array(
			'inherit' => array(
				'weights' => array(
					'inherit',
					'100',
					'100italic',
					'200',
					'200italic',
					'300',
					'300italic',
					'400',
					'400italic',
					'500',
					'500italic',
					'600',
					'600italic',
					'700',
					'700italic',
					'800',
					'800italic',
					'900',
					'900italic'
				),
				'category' => 'inherit'
			),
			'Arial' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			),
			'Century Gothic' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			),
			'Courier New' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'monospace'
			),
			'Georgia' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'serif'
			),
			'Helvetica' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			),
			'Impact' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			),
			'Lucida Console' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'monospace'
			),
			'Lucida Sans Unicode' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			),
			'Palatino Linotype' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'serif'
			),
			'Tahoma' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			),
			'Times New Roman' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'serif'
			),
			'Trebuchet MS' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			),
			'Verdana' => array(
				'weights' => array(
					'400',
					'400italic',
					'700',
					'700italic'
				),
				'category' => 'sans-serif'
			)
		);

		// Build font list to return
		$websafe_fonts = array();
		foreach ( $websafe_list as $font => $attributes ) {
			$urls = array();
			// Get font properties from json array.
			foreach ( $attributes['weights'] as $variant ) {
				//$urls[ $variant ] = "";
			}
			// Create a font array containing it's properties and add it to the $websafe_fonts array
			$atts = array(
				'name' => $font,
				'font_type' => 'default',
				'category' => $attributes['category'],
				'font_weights' => $attributes['weights'],
				'subsets' => array(),
				'files' => array()
				//'urls'         => $urls,
			);

			// Add this font to all of the fonts
			$id                 = strtolower( str_replace( ' ', '_', $font ) );
			$websafe_fonts[$id] = $atts;
		}

		$fontslist = array_merge( $websafe_fonts, $web_fonts );
		set_transient( 'lander_fonts_list', $fontslist, 60 * 60 * 24 ); //Expires in 24 hours
		return $fontslist;
	} else {
		return get_transient( 'lander_fonts_list' );
	}

}
