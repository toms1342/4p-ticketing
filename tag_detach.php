<?php

require_once( 'core.php' );
require_api( 'event_api.php' );
require_api( 'form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'print_api.php' );

form_security_validate( 'tag_detach' );

$f_tag_id = gpc_get_int( 'tag_id' );
$f_bug_id = gpc_get_int( 'bug_id' );

$t_data = array(
	'query' => array( 'issue_id' => $f_bug_id, 'tag_id' => $f_tag_id )
);

$t_command = new TagDetachCommand( $t_data );
$t_command->execute();

form_security_purge( 'tag_detach' );

print_successful_redirect_to_bug( $f_bug_id );
