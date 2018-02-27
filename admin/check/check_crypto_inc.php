<?php

if( !defined( 'CHECK_CRYPTO_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );

check_print_section_header_row( 'Cryptography' );

check_print_test_row(
	'Master salt value has been specified',
	strlen( config_get_global( 'crypto_master_salt' ) ) >= 16,
	array( false => 'The crypto_master_salt option needs to be specified in config_inc.php with a minimum string length of 16 characters.' )
);

check_print_test_row(
	'login_method is not equal to CRYPT_FULL_SALT',
	config_get_global( 'login_method' ) != CRYPT_FULL_SALT,
	array( false => 'Login method CRYPT_FULL_SALT has been deprecated and should not be used.' )
);

if( config_get_global( 'login_method' ) != LDAP ) {
	check_print_test_warn_row(
		'login_method is set to MD5',
		config_get_global( 'login_method' ) == MD5,
		'MD5 password encryption is currently the strongest password storage method supported by MantisBT.'
	);
}
