<?php

$g_app->group('/users', function() use ( $g_app ) {
	$g_app->get( '/me', 'rest_user_get_me' );

	$g_app->post( '/', 'rest_user_create' );
	$g_app->post( '', 'rest_user_create' );

	$g_app->delete( '/{id}', 'rest_user_delete' );
	$g_app->delete( '/{id}/', 'rest_user_delete' );
});

/**
 * A method that does the work to get information about current logged in user.
 *
 * @param \Slim\Http\Request $p_request   The request.
 * @param \Slim\Http\Response $p_response The response.
 * @param array $p_args Arguments
 * @return \Slim\Http\Response The augmented response.
 */
function rest_user_get_me( \Slim\Http\Request $p_request, \Slim\Http\Response $p_response, array $p_args ) {
	$t_result = mci_user_get( auth_get_current_user_id() );
	return $p_response->withStatus( HTTP_STATUS_SUCCESS )->withJson( $t_result );
}

/**
 * A method that creates a user.
 *
 * @param \Slim\Http\Request $p_request   The request.
 * @param \Slim\Http\Response $p_response The response.
 * @param array $p_args Arguments
 * @return \Slim\Http\Response The augmented response.
 */
function rest_user_create( \Slim\Http\Request $p_request, \Slim\Http\Response $p_response, array $p_args ) {
	$t_payload = $p_request->getParsedBody();
	if( $t_payload === null ) {
		return $p_response->withStatus( HTTP_STATUS_BAD_REQUEST, "Unable to parse body, specify content type" );
	}

	$t_data = array( 'payload' => $t_payload );
	$t_command = new UserCreateCommand( $t_data );
	$t_result = $t_command->execute();
	$t_user_id = $t_result['id'];

	return $p_response->withStatus( HTTP_STATUS_CREATED, "User created with id $t_user_id" )->
		withJson( array( 'user' => mci_user_get( $t_user_id ) ) );
}

/**
 * Delete an user given its id.
 *
 * @param \Slim\Http\Request $p_request   The request.
 * @param \Slim\Http\Response $p_response The response.
 * @param array $p_args Arguments
 * @return \Slim\Http\Response The augmented response.
 */
function rest_user_delete( \Slim\Http\Request $p_request, \Slim\Http\Response $p_response, array $p_args ) {
	$t_user_id = $p_args['id'];

	$t_data = array(
		'query' => array( 'id' => $t_user_id )
	);

	$t_command = new UserDeleteCommand( $t_data );
	$t_command->execute();

	return $p_response->withStatus( HTTP_STATUS_NO_CONTENT );	
}
