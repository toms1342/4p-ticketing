<?php

if( !defined( 'CHECK_WEBSERVICE_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );

check_print_section_header_row( 'Webservice' );

check_print_test_warn_row(
	'SOAP Extension Enabled',
	extension_loaded( 'soap' ),
	array( false => 'Enable the PHP SOAP extension.' )
);

