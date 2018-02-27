<?php

require_api( 'helper_api.php' );

/**
 * WARNING: All APIs under the internal route are considered private and can break anytime.
 */
$g_app->group('/internal', function() use ( $g_app ) {
	$g_app->any( '/autocomplete', 'rest_internal_autocomplete' );
});

/**
 * A method that gets the auto-complete result for given field and prefix.
 *
 * @param \Slim\Http\Request $p_request   The request.
 * @param \Slim\Http\Response $p_response The response.
 * @param array $p_args Arguments
 * @return \Slim\Http\Response The augmented response.
 */
function rest_internal_autocomplete( \Slim\Http\Request $p_request, \Slim\Http\Response $p_response, array $p_args ) {
	$t_field = $p_request->getParam( 'field' );;
	$t_prefix = $p_request->getParam( 'prefix' );

	switch( $t_field ) {
		case 'platform':
			$t_unique_entries = profile_get_field_all_for_user( 'platform' );
			$t_matches = helper_filter_by_prefix( $t_unique_entries, $t_prefix );
			break;
		case 'os':
			$t_unique_entries = profile_get_field_all_for_user( 'os' );
			$t_matches = helper_filter_by_prefix( $t_unique_entries, $t_prefix );
			break;
		case 'os_build':
			$t_unique_entries = profile_get_field_all_for_user( 'os_build' );
			$t_matches = helper_filter_by_prefix( $t_unique_entries, $t_prefix );
			break;
		default:
			return $p_response->withStatus( HTTP_STATUS_NOT_FOUND, "Field '$t_field' doesn't have auto-complete." );
	}

	return $p_response->withStatus( HTTP_STATUS_SUCCESS )->withJson( $t_matches );
}
