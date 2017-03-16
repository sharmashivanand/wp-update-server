<?php

//CHILD_SETTINGS_FIELD
//CHILD_SETTINGS_FIELD_EXTRAS

/*

Retain old values? What happens to old values that don't validate with the new version? Eg if color value 'transparent' is banned in teh new version?

Insert New Values?

*/

add_action( 'admin_init', 'lander_upgrade', 20 );


function lander_upgrade(){
	$lander_design_old = get_option(CHILD_SETTINGS_FIELD);
	$style_admin_old = get_option(CHILD_SETTINGS_FIELD_EXTRAS);
}