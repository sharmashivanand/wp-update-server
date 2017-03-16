<?php

add_shortcode('button', 'lander_cta_button');

/**
 * Shortcode for simple cta button
 * Outputs markup for simple cta button
 * @since 1.0
 */
function lander_cta_button($atts, $content = null) {
   extract(shortcode_atts(array(
		'link' => '#',
		'color' => 'bt-blue',
		'type' => 'transparent',
		'round' => "no",
		'icon' => '',
		"newwindow" => "no",
		"id" => '',
		"class" => '',
	), $atts));
	$border_radius = ($round == 'yes') ? ' round' : null;
	$user_class = ($class) ? ' '.$class : null;
	$display_icon = ($icon == '') ? '' : '<i class="fa '.$icon.'"></i> ';
	$id = ($id != '') ? " id='" . esc_attr( $id ) . "'" : '';
	$target = ($newwindow == 'yes') ? '_blank' : '_self';
	
	$cta_button =  '<a'.$id.' class="cta-btn '.esc_attr($type).' '. esc_attr($color).''.$border_radius.''.esc_attr($user_class).'" href="'.$link.'" target="'.$target.'">'.$display_icon.' '.do_shortcode($content). '</a>';
	return $cta_button;
}


add_shortcode('big-button', 'lander_big_cta_button');

/**
 * Shortcode for big cta button
 * Outputs markup for big cta button
 * @since 1.0
 */
function lander_big_cta_button($atts, $content = null) {
   extract(shortcode_atts(array(
		'link' => '#',
		'color' => 'bt-blue',
		'type' => 'transparent',
		'icon' => '',
		"newwindow" => "no",
		"id" => '',
		"class" => '',
	), $atts));
	$user_class = ($class) ? ' '.$class : null;
	$display_icon = ($icon == '') ? '' : '<i class="fa '.$icon.'"></i> ';
	$id = ($id != '') ? " id='" . esc_attr( $id ) . "'" : '';
	$target = ($newwindow == 'yes') ? '_blank' : '_self';
	
	$bordered_button =  '<a'.$id.' class="big-btn '.esc_attr($type).' '. esc_attr($color).''.esc_attr($user_class).'" href="'.$link.'" target="'.$target.'">'.$display_icon.do_shortcode($content). '</a>';
	return $bordered_button;
}


add_shortcode('white-button', 'lander_white_button');

/**
 * Shortcode for white cta button
 * Outputs markup for white cta button
 * @since 1.0
 */
function lander_white_button($atts, $content = null) {
   extract(shortcode_atts(array(
		'link' => '#',
		'hovercolor' => 'bt-blue',
		'type' => 'transparent',
		'round' => "no",
		'icon' => '',
		"newwindow" => "no",
		"id" => '',
		"class" => '',
	), $atts));
	$border_radius = ($round == 'yes') ? ' round' : null;
	$user_class = ($class) ? ' '.$class : null;
	$display_icon = ($icon == '') ? '' : '<i class="fa '.$icon.'"></i> ';
	$id = ($id != '') ? " id='" . esc_attr( $id ) . "'" : '';
	$target = ($newwindow == 'yes') ? '_blank' : '_self';
	
	$white_button = '<a'.$id.' class="white-btn '.esc_attr($type).' '. esc_attr($hovercolor).''.$border_radius.''.esc_attr($user_class).'" href="'.$link.'" target="'.$target.'">'.$display_icon.' '.do_shortcode($content). '</a>';
	return $white_button;
}


add_shortcode('custom-list', 'lander_list_styles');

/**
 * Shortcode for custom lists
 * Outputs markup for custom list which allows use of custom icons in place of regular list styles
 * @since 1.0
 */
function lander_list_styles($atts, $content = null) {
   extract(shortcode_atts(array(
		'color' => 'bt-blue',
		'style' => '',
		"id" => '',
		"class" => '',
	), $atts));
	
	$id = ($id != '') ? " id='" . esc_attr( $id ) . "'" : '';
	$user_class = ($class) ? ' '.$class : null;
	
	$lander_list =  '<div'.$id.' class="custom-list '.esc_attr($style).' '. esc_attr($color).''.esc_attr($user_class).'"> '.do_shortcode($content). '</div>';
	return $lander_list;
}


add_shortcode('collapsible-content', 'lander_collapsible_content');

/**
 * Shortcode for collapsible content
 * Outputs markup for collapsible content
 * @since 1.0
 */
function lander_collapsible_content($atts, $content = null) {
	
	extract(shortcode_atts(array (
		'caption' => __( 'Click here to read more', CHILD_DOMAIN ),
		'state' => 'close',
		'id' => '',
		'class' => ''
	), $atts ));
	
	$id = ($id != '') ? " id='" . esc_attr( $id ) . "'" : '';
	$class = ($class != '') ? esc_attr( ' ' . $class ) : '';
	
	$divClass = ($state == 'close') ? 'collapsible-content' : 'collapsible-content open';
	$hClass = ($state == 'close') ? 'collapsible-heading' : 'collapsible-heading open';
	
	$output = '<h3 class="'.$hClass.'"><a>'. esc_html( $caption ) . '</a></h3><div'. $id .' class="'.$divClass.''.$class.'" style="display: none;"><div class="collapsed-content">'.do_shortcode($content).'</div></div>';

	return $output;
	
}