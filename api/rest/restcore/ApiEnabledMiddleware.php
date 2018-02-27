<?php

/**
 * A middleware class that handles checks for REST API being enabled.
 */
class ApiEnabledMiddleware {
	public function __invoke( \Slim\Http\Request $request, \Slim\Http\Response $response, callable $next ) {
		$t_force_enable = $request->getAttribute( ATTRIBUTE_FORCE_API_ENABLED );

		# If request is coming from UI, then force enable will be true, hence, request shouldn't be blocked
		# even if API is disabled.
		if( !$t_force_enable ) {
			if( config_get( 'webservice_rest_enabled' ) == OFF ) {
				return $response->withStatus( HTTP_STATUS_UNAVAILABLE, 'API disabled' );
			}
		}

		return $next( $request, $response );
	}
}
