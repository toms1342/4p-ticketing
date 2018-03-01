<?php

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'config_api.php' );
require_api( 'form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'tag_api.php' );

form_security_validate( 'tag_delete' );

access_ensure_global_level( config_get( 'tag_edit_threshold' ) );

$f_tag_id = gpc_get_int( 'tag_id' );
tag_ensure_exists( $f_tag_id );
$t_tag_row = tag_get( $f_tag_id );

helper_ensure_confirmed( lang_get( 'tag_delete_message' ), lang_get( 'tag_delete_button' ) );

tag_delete( $f_tag_id );

form_security_purge( 'tag_delete' );

print_successful_redirect( 'manage_tags_page.php' );
