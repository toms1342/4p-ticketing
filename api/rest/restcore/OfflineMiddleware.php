<?php

class OfflineMiddleware {
	public function __invoke( \Slim\Http\Request $request, \Slim\Http\Response $response, callable $next ) {
		if( mci_is_mantis_offline() ) {
			return $response->withStatus( HTTP_STATUS_UNAVAILABLE, 'Mantis Offline' );
		}

		return $next( $request, $response );
	}
}
