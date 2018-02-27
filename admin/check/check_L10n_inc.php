<?php

if( !defined( 'CHECK_L10N_INC_ALLOW' ) ) {
	return;
}

# MantisBT Check API
require_once( 'check_api.php' );
require_api( 'config_api.php' );

check_print_section_header_row( 'Localization' );

$t_possible_languages = config_get_global( 'language_choices_arr' );
$t_default_language = config_get_global( 'default_language' );
check_print_test_row(
	'default_language configuration option is set to a valid language',
	in_array( $t_default_language, $t_possible_languages ),
	array(
		true => 'The default language is currently specified as: ' . htmlentities( $t_default_language ),
		false => 'Invalid default language detected: ' . htmlentities( $t_default_language )
	)
);

$t_fallback_language = config_get_global( 'fallback_language' );
check_print_test_row(
	'fallback_language configuration option is set to a valid language',
	$t_fallback_language != 'auto' && in_array( $t_fallback_language, $t_possible_languages ),
	array(
		true => 'The fallback language is currently specified as: ' . htmlentities( $t_fallback_language ),
		false => 'Fallback language can not be set to auto or a non-implemented language. Invalid fallback language detected: ' . htmlentities( $t_fallback_language )
	)
);
