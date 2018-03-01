<?php

require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'print_api.php' );
require_api( 'tag_api.php' );

form_security_validate( 'tag_create' );

$f_tag_name = gpc_get_string( 'name' );
$f_tag_description = gpc_get_string( 'description' );

$t_tag_user = auth_get_current_user_id();

if( !is_null( $f_tag_name ) ) {
	$t_tags = tag_parse_string( $f_tag_name );
	foreach ( $t_tags as $t_tag_row ) {
		if( -1 == $t_tag_row['id'] ) {
			tag_create( $t_tag_row['name'], $t_tag_user, $f_tag_description );
		}
	}
}

form_security_purge( 'tag_create' );
print_successful_redirect( 'manage_tags_page.php' );

