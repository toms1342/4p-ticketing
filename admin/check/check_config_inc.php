<?php

if( !defined( 'CHECK_CONFIG_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );

check_print_section_header_row( 'Configuration' );

check_print_test_row( 'config_inc.php configuration file exists',
	file_exists( $g_config_path . 'config_inc.php' ),
	array( false => 'Please use <a href="install.php">install.php</a> to perform the initial installation of MantisBT.' )
);

check_print_test_row( 'config_inc.php must not be in MantisBT root folder',
	!file_exists( $g_absolute_path . 'config_inc.php' ),
	array( false => 'Move from MantisBT root folder to config folder.' )
);

check_print_test_row( 'custom_strings_inc.php must not be in MantisBT root folder',
	!file_exists( $g_absolute_path . 'custom_strings_inc.php' ),
	array( false => 'Move from MantisBT root folder to config folder.' )
);

check_print_test_row( 'custom_functions_inc.php must not be in MantisBT root folder',
	!file_exists( $g_absolute_path . 'custom_functions_inc.php' ),
	array( false => 'Move from MantisBT root folder to config folder.' )
);

check_print_test_row( 'custom_constants_inc.php must not be in MantisBT root folder',
	!file_exists( $g_absolute_path . 'custom_constants_inc.php' ),
	array( false => 'Move from MantisBT root folder to config folder.' )
);

check_print_test_row( 'custom_relationships_inc.php must not be in MantisBT root folder',
	!file_exists( $g_absolute_path . 'custom_relationships_inc.php' ),
	array( false => 'Move from MantisBT root folder to config folder.' )
);

check_print_test_row( 'api/soap/mc_config_inc.php is no longer supported',
	!file_exists( $g_absolute_path . 'api/soap/mc_config_inc.php' ),
	array( false => 'Move contents of api/soap/mc_config_inc.php into config/config_inc.php.' )
);

# Debugging / Developer Settings
check_print_test_warn_row( 'Check whether diagnostic logging is enabled',
	$g_log_level == LOG_NONE,
	array( false => 'Global Log Level should usually be set to LOG_NONE for production use' )
);

check_print_test_warn_row( 'Check whether log output is sent to end user',
	!($g_log_destination == 'firebug' || $g_log_destination == 'page'),
	array( false => 'Diagnostic output destination is currently sent to end users browser' )
);

check_print_test_warn_row( 'Detailed errors should be OFF',
	$g_show_detailed_errors == OFF,
	array( false => 'Setting show_detailed_errors = ON is a potential security hazard as it can expose sensitive information.' )
);

check_print_test_warn_row( 'Email debugging should be OFF',
	empty( $g_debug_email ),
	array( false => 'All notification e-mails will be sent to: ' . $g_debug_email )
);

check_print_test_row( 'Default move category must exists ("default_category_for_moves")',
	category_exists( config_get( 'default_category_for_moves' ) ),
	array( false => 'Issues moved may end up with invalid category id.' )
);

# Obsolete Settings
require_api( 'obsolete.php' );
