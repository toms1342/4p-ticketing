<?php

require_once( 'core.php' );
require_api( 'bug_api.php' );
require_api( 'gpc_api.php' );
require_api( 'print_api.php' );
require_api( 'project_api.php' );
require_api( 'wiki_api.php' );

$f_id = gpc_get_int( 'id' );
$f_type = gpc_get_string( 'type', 'issue' );

if( $f_type == 'project' ) {
	if( $f_id !== 0 ) {
		project_ensure_exists( $f_id );
	}

	$t_url = wiki_link_project( $f_id );
} else {
	bug_ensure_exists( $f_id );
	$t_url = wiki_link_bug( $f_id );
}

print_header_redirect( $t_url, true, false, true );
