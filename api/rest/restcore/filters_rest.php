<?php

require_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/soap/mc_filter_api.php'  );

use \Slim\Http\Request as SlimRequest;
use \Slim\Http\Response as SlimResponse;

$g_app->group('/filters', function() use ( $g_app ) {
	$g_app->get( '', 'rest_filter_get' );
	$g_app->get( '/', 'rest_filter_get' );
	$g_app->get( '/{id}', 'rest_filter_get' );
	$g_app->get( '/{id}/', 'rest_filter_get' );
	$g_app->delete( '/{id}', 'rest_filter_delete' );
	$g_app->delete( '/{id}/', 'rest_filter_delete' );
});

/**
 * A method that does the work to handle getting filters via REST API.
 * If a project id is specified, the filters applicable to such project is returned,
 * otherwise all filters are returned.
 *
 * @param SlimRequest $p_request   The request.
 * @param SlimResponse $p_response The response.
 * @param array $p_args Arguments
 * @return SlimResponse The augmented response.
 */
function rest_filter_get( SlimRequest $p_request, SlimResponse $p_response, array $p_args ) {
	# Filter id will be null if not provided.
	$t_filter_id = isset( $p_args['id'] ) ? $p_args['id'] : $p_request->getParam( 'id' );

	$t_project_id = $p_request->getParam( 'project_id', null );
	if( $t_project_id !== null && (int)$t_project_id != ALL_PROJECTS && !project_exists( $t_project_id ) ) {
		$t_message = "Project '$t_project_id' doesn't exist";
		return $p_response->withStatus( HTTP_STATUS_NOT_FOUND, $t_message );
	}

	$t_filters = mc_filter_get( '', '', $t_project_id, $t_filter_id );
	$t_result = array( 'filters' => $t_filters );

	return $p_response->withStatus( HTTP_STATUS_SUCCESS )->withJson( $t_result );
}

/**
 * Delete a filter given its id.
 *
 * @param SlimRequest $p_request   The request.
 * @param SlimResponse $p_response The response.
 * @param array $p_args Arguments
 * @return SlimResponse The augmented response.
 */
function rest_filter_delete( SlimRequest $p_request, SlimResponse $p_response, array $p_args ) {
	$t_filter_id = isset( $p_args['id'] ) ? $p_args['id'] : $p_request->getParam( 'id' );

	$t_result = mci_filter_delete( $t_filter_id );
	if( ApiObjectFactory::isFault( $t_result ) ) {
		return $p_response->withStatus( $t_result->status_code, $t_result->fault_string );
	}

	return $p_response->withStatus( HTTP_STATUS_NO_CONTENT );
}
