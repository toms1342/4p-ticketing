<?php

if( !defined( 'CHECK_ANONYMOUS_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );
require_api( 'config_api.php' );
require_api( 'user_api.php' );

check_print_section_header_row( 'Anonymous access' );

$t_anonymous_access_enabled = auth_anonymous_enabled();
check_print_info_row(
	'Anonymous access is enabled',
	$t_anonymous_access_enabled ? 'Yes' : 'No'
);

if( !$t_anonymous_access_enabled ) {
	return;
}

$t_anonymous_account = auth_anonymous_account();
check_print_test_row(
	'anonymous_account configuration option is specified',
	$t_anonymous_account !== '',
	array(
		true => 'The account currently being used for anonymous access is: ' . htmlentities( $t_anonymous_account ),
		false => 'The anonymous_account configuration option must specify the username of an account to use for anonymous logins.'
	)
);

if( $t_anonymous_account === '' ) {
	return;
}

$t_anonymous_user_id = user_get_id_by_name( $t_anonymous_account );
check_print_test_row(
	'anonymous_account is a valid user account',
	$t_anonymous_user_id !== false,
	array( false => 'You need to specify a valid user account to use with the anonymous_account configuration options.' )
);

check_print_test_row(
	'anonymous_account user has the enabled flag set',
	user_is_enabled( $t_anonymous_user_id ),
	array( false => 'The anonymous user account must be enabled before it can be used.' )
);

check_print_test_row(
	'anonymous_account user has the protected flag set',
	user_get_field( $t_anonymous_user_id, 'protected' ),
	array( false => 'The anonymous user account needs to have the protected flag set to prevent anonymous users modifying the account.' )
);

check_print_test_row(
	'anonymous_account user does not have administrator permissions',
	!user_is_administrator( $t_anonymous_user_id ),
	array(
		true => 'The anonymous user account currently has an access level of: ' . htmlentities( get_enum_element( 'access_levels', user_get_access_level( $t_anonymous_user_id ) ) ),
		false => 'The anonymous user account should not have administrator level permissions.'
	)
);
