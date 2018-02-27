<?php

if( !defined( 'CHECK_DISPLAY_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );
require_api( 'config_api.php' );

check_print_section_header_row( 'Display' );

# Test length of OpenSearch ShortNames
$t_prefix = config_get( 'search_title' );
$t_shortname_text = sprintf( lang_get( 'opensearch_text_short' ), $t_prefix );
$t_shortname_id = sprintf( lang_get( 'opensearch_id_short' ), $t_prefix );
$t_shortname_length = max( strlen( $t_shortname_text ), strlen( $t_shortname_id ) );

check_print_test_warn_row(
	"Browser Search engine names must be 16 chars or less",
	$t_shortname_length <= 16,
	array( false => 'Either shorten the "search_title" configuration option to '
		. 'a maximum  of ' . ( 16 - $t_shortname_length + strlen( $t_prefix ) )
		. ' characters, or alter the "opensearch_XXX_short" language strings '
		. 'as appropriate to meet the <a href="http://www.opensearch.org/">'
		. 'OpenSearch 1.1</a> specification for the ShortName element.'
	)
);

check_print_test_row(
	'bug_link_tag is not blank/null',
	config_get_global( 'bug_link_tag' ),
	array( false => 'The value of the bug_link_tag option cannot be blank/null.' )
);

check_print_test_row(
	'bugnote_link_tag is not blank/null',
	config_get_global( 'bugnote_link_tag' ),
	array( false => 'The value of the bugnote_link_tag option cannot be blank/null.' )
);

