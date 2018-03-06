<?php

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'config_api.php' );
require_api( 'database_api.php' );
require_api( 'file_api.php' );
require_api( 'form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'string_api.php' );

form_security_validate( 'proj_doc_delete' );

# Check if project documentation feature is enabled.
if( OFF == config_get( 'enable_project_documentation' ) ) {
	access_denied();
}

$f_file_id = gpc_get_int( 'file_id' );

$t_project_id = file_get_field( $f_file_id, 'project_id', 'project' );

access_ensure_project_level( config_get( 'upload_project_file_threshold' ), $t_project_id );

$t_query = 'SELECT title FROM {project_file} WHERE id=' . db_param();
$t_result = db_query( $t_query, array( $f_file_id ) );
$t_title = db_result( $t_result );

# Confirm with the user
helper_ensure_confirmed( lang_get( 'confirm_file_delete_msg' ) .
	'<br />' . lang_get( 'filename_label' ) . lang_get( 'word_separator' ) . string_display( $t_title ),
	lang_get( 'file_delete_button' ) );

file_delete( $f_file_id, 'project' );

form_security_purge( 'proj_doc_delete' );

$t_redirect_url = 'proj_doc_page.php';

layout_page_header( null, $t_redirect_url );

layout_page_begin( 'proj_doc_page.php' );

html_operation_successful( $t_redirect_url );

layout_page_end();
