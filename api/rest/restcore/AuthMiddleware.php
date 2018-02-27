<?php

require_api( 'authentication_api.php' );
require_api( 'user_api.php' );

/**
 * A middleware class that handles authentication and authorization to access APIs.
 */
class AuthMiddleware {
	public function __invoke( \Slim\Http\Request $request, \Slim\Http\Response $response, callable $next ) {
		$t_authorization_header = $request->getHeaderLine( HEADER_AUTHORIZATION );

		if( empty( $t_authorization_header ) ) {
			# Since authorization header is empty, check if user is authenticated by checking the cookie
			# This mode is used when Web UI javascript calls into the API.
			if( auth_is_user_authenticated() ) {
				$t_username = user_get_username( auth_get_current_user_id() );
				$t_password = auth_get_current_user_cookie( /* auto-login-anonymous */ false );
				$t_login_method = LOGIN_METHOD_COOKIE;
			} else {
				$t_username = auth_anonymous_account();

				if( !auth_anonymous_enabled() || empty( $t_username ) ) {
					return $response->withStatus( HTTP_STATUS_UNAUTHORIZED, 'API token required' );
				}

				$t_login_method = LOGIN_METHOD_ANONYMOUS;
				$t_password = '';
			}
		} else {
			# TODO: add an index on the token hash for the method below
			$t_user_id = api_token_get_user( $t_authorization_header );
			if( $t_user_id === false ) {
				return $response->withStatus( HTTP_STATUS_FORBIDDEN, 'API token not found' );
			}

			# use api token
			$t_login_method = LOGIN_METHOD_API_TOKEN;
			$t_password = $t_authorization_header;
			$t_username = user_get_username( $t_user_id );
		}

		if( mci_check_login( $t_username, $t_password ) === false ) {
			return $response->withStatus( HTTP_STATUS_FORBIDDEN, 'Access denied' );
		}

		# Now that user is logged in, check if they have the right access level to access the REST API.
		# Don't treat web UI calls with cookies as API calls that need to be disabled for certain access levels.
		if( $t_login_method != LOGIN_METHOD_COOKIE && !mci_has_readonly_access() ) {
			return $response->withStatus( HTTP_STATUS_FORBIDDEN, 'Higher access level required for API access' );
		}

		$t_force_enable = $t_login_method == LOGIN_METHOD_COOKIE;
		return $next( $request->withAttribute( ATTRIBUTE_FORCE_API_ENABLED, $t_force_enable ), $response )->
			withHeader( HEADER_USERNAME, $t_username )->
			withHeader( HEADER_LOGIN_METHOD, $t_login_method );
	}
}
