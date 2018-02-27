<?php

$g_app->group('/lang', function() use ( $g_app ) {
	$g_app->get( '', 'rest_lang_get' );
	$g_app->get( '/', 'rest_lang_get' );
});

function rest_lang_get( \Slim\Http\Request $p_request, \Slim\Http\Response $p_response, array $p_args ) {
	$t_strings = $p_request->getParam( 'string' );
	if( !is_array( $t_strings ) ) {
		$t_strings = array( $t_strings );
	}

	$t_current_language = lang_get_current();
	$t_localized_strings = array();
	foreach( $t_strings as $t_string ) {
		if( !lang_exists( $t_string, $t_current_language) ) {
			continue;
		}

		$t_localized_strings[] = array( 'name' => $t_string, 'localized' => lang_get( $t_string ) );
	}

	$t_result = array( 'strings' => $t_localized_strings );
	$t_result['language'] = $t_current_language;

	return $p_response->withStatus( HTTP_STATUS_SUCCESS )->withJson( $t_result );
}
