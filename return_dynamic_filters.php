<?php

# Prevent output of HTML in the content if errors occur
define( 'DISABLE_INLINE_ERROR_REPORTING', true );

require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'compress_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'current_user_api.php' );
require_api( 'custom_field_api.php' );
require_api( 'error_api.php' );
require_api( 'filter_api.php' );
require_api( 'filter_constants_inc.php' );
require_api( 'filter_form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );

if( !auth_is_user_authenticated() ) {
	trigger_error( ERROR_ACCESS_DENIED, ERROR );
}

compress_enable();

$f_filter_id = gpc_get( 'filter_id', null );
if( null === $f_filter_id ) {
	$t_filter = current_user_get_bug_filter();
} else {
	$c_filter_id = (int)$f_filter_id;
	$t_filter_string = filter_db_get_filter( $c_filter_id );
	if( !$t_filter_string ) {
		trigger_error( ERROR_ACCESS_DENIED, ERROR );
	} else {
		$t_filter = filter_deserialize( $t_filter_string );
		$t_filter['_source_query_id'] = $f_filter_id;
		filter_cache_row( $c_filter_id );
	}
}

$f_view_type = gpc_get_string( 'view_type', $t_filter['_view_type'] );
$t_filter['_view_type'] = $f_view_type;
$t_filter = filter_ensure_valid_filter( $t_filter );

/**
 * Prepend headers to the dynamic filter forms that are sent as the response from this page.
 * @return void
 */
function return_dynamic_filters_prepend_headers() {
	if( !headers_sent() ) {
		header( 'Content-Type: text/html; charset=utf-8' );
	}
}

$f_filter_target = gpc_get_string( 'filter_target' );
$filter_target = utf8_substr( $f_filter_target, 0, -7 ); # -7 for '_filter'
$t_found = false;
$t_content = @call_user_func_array( 'filter_form_get_input', array( $t_filter, $filter_target, true ) );
if( false !== $t_content ) {
	return_dynamic_filters_prepend_headers();
	$t_found = true;
	echo $t_content;
} else if( 'custom_field' == utf8_substr( $f_filter_target, 0, 12 ) ) {
	# custom function
	$t_custom_id = utf8_substr( $f_filter_target, 13, -7 );
	$t_cfdef = @custom_field_get_definition( $t_custom_id );
	# Check existence of custom field id, and if the user have access to read and filter by
	if( $t_cfdef && access_has_any_project_level( $t_cfdef['access_level_r'] ) && $t_cfdef['filter_by'] ) {
		$t_found = true;
		return_dynamic_filters_prepend_headers();
		print_filter_custom_field( $t_custom_id, $t_filter );
	} else {
		trigger_error( ERROR_ACCESS_DENIED, ERROR );
	}
} else {
	$t_plugin_filters = filter_get_plugin_filters();
	foreach ( $t_plugin_filters as $t_field_name => $t_filter_object ) {
		if( $t_field_name . '_filter' == $f_filter_target ) {
			return_dynamic_filters_prepend_headers();
			print_filter_plugin_field( $t_field_name, $t_filter_object, $t_filter );
			$t_found = true;
			break;
		}
	}
}

if( !$t_found ) {
	# error - no function to populate the target (e.g., print_filter_foo)
	error_parameters( $f_filter_target );
	trigger_error( ERROR_FILTER_NOT_FOUND, ERROR );
}

