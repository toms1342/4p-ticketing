<?php

require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'config_api.php' );
require_api( 'print_api.php' );

if( auth_is_user_authenticated() ) {
	print_header_redirect( config_get_global( 'default_home_page' ) );
} else {
	print_header_redirect( auth_login_page() );
}
