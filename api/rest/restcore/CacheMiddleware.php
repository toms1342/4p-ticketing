<?php

/**
 * A middleware class that disables caching.
 */
class CacheMiddleware {
	public function __invoke( \Slim\Http\Request $request, \Slim\Http\Response $response, callable $next ) {
		return $next( $request, $response )->
			withoutHeader( 'Cache-Control' )->
			withHeader( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
	}
}
