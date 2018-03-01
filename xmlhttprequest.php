<?php

require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'gpc_api.php' );
require_api( 'logging_api.php' );

auth_ensure_user_authenticated();

$f_entrypoint = gpc_get_string( 'entrypoint' );

$t_function = 'xmlhttprequest_' . $f_entrypoint;
if( function_exists( $t_function ) ) {
	log_event( LOG_AJAX, 'DEPRECATED: Calling {' . $t_function . '}. Use REST API instead.' );
	call_user_func( $t_function );
} else {
	$t_msg = 'Unknown function for entry point: ' . $t_function;
	log_event( LOG_AJAX, $t_msg );
	header(
		'HTTP/1.1 ' . HTTP_STATUS_BAD_REQUEST . ' ' . $t_msg,
		false,
		HTTP_STATUS_BAD_REQUEST
	);
	echo string_attribute( $t_msg );
}
