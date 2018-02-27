<?php

if( !defined( 'CHECK_EMAIL_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );
require_api( 'config_api.php' );
require_api( 'utility_api.php' );

check_print_section_header_row( 'Email' );

$t_email_options = array(
	'webmaster_email',
	'from_email',
	'return_path_email'
);

foreach( $t_email_options as $t_email_option ) {
	$t_email = config_get_global( $t_email_option );
	check_print_test_row(
		$t_email_option . ' configuration option has a valid email address specified',
		!preg_match( '/@example\.com$/', $t_email ),
		array( false => 'You need to specify a valid email address for the ' . $t_email_option . ' configuration option.' )
	);
}

check_print_test_warn_row(
	'Email addresses are validated',
	config_get_global( 'validate_email' ),
	array( false => 'You have disabled email validation checks. For security reasons it is suggested that you enable these validation checks.' )
);

check_print_test_row(
	'send_reset_password = ON requires allow_blank_email = OFF',
	!config_get_global( 'send_reset_password' ) || !config_get_global( 'allow_blank_email' )
);

check_print_test_row(
	'send_reset_password = ON requires enable_email_notification = ON',
	!config_get_global( 'send_reset_password' ) || config_get_global( 'enable_email_notification' )
);

check_print_test_row(
	'allow_signup = ON requires enable_email_notification = ON',
	!config_get_global( 'allow_signup' ) || config_get_global( 'enable_email_notification' )
);

check_print_test_row(
	'allow_signup = ON requires send_reset_password = ON',
	!config_get_global( 'allow_signup' ) || config_get_global( 'send_reset_password' )
);
