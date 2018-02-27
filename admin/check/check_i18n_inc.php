<?php

if( !defined( 'CHECK_I18N_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );
require_api( 'config_api.php' );

check_print_section_header_row( 'Internationalization' );

$t_config_default_timezone = config_get_global( 'default_timezone' );
if( $t_config_default_timezone ) {
	check_print_test_row(
		'Default timezone has been specified in config_inc.php (default_timezone option)',
		in_array( $t_config_default_timezone, timezone_identifiers_list() ),
		array(
			true => "Default timezone is '" . htmlentities( $t_config_default_timezone ) . "'",
			false => "Invalid timezone '" . htmlentities( $t_config_default_timezone ) . "' specified. "
				. 'Refer to the <a href="http://php.net/timezones">List of Supported Timezones</a>.'
		)
	);
} else {
	$t_php_default_timezone = ini_get( 'date.timezone' );
	$t_msg = 'No timezone has been specified in config_inc.php (default_timezone option)';
	$t_tz_link = '<a href="http://ch1.php.net/datetime.configuration#ini.date.timezone">date.timezone</a>';

	if( $t_php_default_timezone ) {
		check_print_test_row(
			$t_msg,
			in_array( $t_php_default_timezone, timezone_identifiers_list() ),
			array(
				true => "Default timezone (specified by the $t_tz_link directive in php.ini) "
					. "is '" . htmlentities( $t_php_default_timezone ) . "'",
				false => "Invalid timezone '" . htmlentities( $t_config_default_timezone ) . "' specified. "
			)
		);
	} else {
		check_print_test_warn_row(
			$t_msg,
			!empty( $t_php_default_timezone ),
			array(
				false => "Timezone has been defaulted to 'UTC'."
			)
		);
	}
}
