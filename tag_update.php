<?php

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'compress_api.php' );
require_api( 'config_api.php' );
require_api( 'form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'print_api.php' );
require_api( 'tag_api.php' );
require_api( 'user_api.php' );

form_security_validate( 'tag_update' );

compress_enable();

$f_tag_id = gpc_get_int( 'tag_id' );
tag_ensure_exists( $f_tag_id );
$t_tag_row = tag_get( $f_tag_id );

if( !( access_has_global_level( config_get( 'tag_edit_threshold' ) )
	|| ( auth_get_current_user_id() == $t_tag_row['user_id'] )
		&& access_has_global_level( config_get( 'tag_edit_own_threshold' ) ) ) ) {
	access_denied();
}

if( access_has_global_level( config_get( 'tag_edit_threshold' ) ) ) {
	$f_new_user_id = gpc_get_int( 'user_id', $t_tag_row['user_id'] );
} else {
	$f_new_user_id = $t_tag_row['user_id'];
}

$f_new_name = gpc_get_string( 'name', $t_tag_row['name'] );
$f_new_description = gpc_get_string( 'description', $t_tag_row['description'] );

tag_update( $f_tag_id, $f_new_name, $f_new_user_id, $f_new_description );

form_security_purge( 'tag_update' );

$t_url = 'tag_view_page.php?tag_id='.$f_tag_id;
print_successful_redirect( $t_url );
