<?php

/**
 * Get config string
 * @param string $p_username   Username.
 * @param string $p_password   Password.
 * @param string $p_config_var A configuration variable.
 * @return mixed
 */
function mc_config_get_string( $p_username, $p_password, $p_config_var ) {
	$t_user_id = mci_check_login( $p_username, $p_password );
	if( $t_user_id === false ) {
		return mci_fault_login_failed();
	}

	if( !mci_has_readonly_access( $t_user_id ) ) {
		return mci_fault_access_denied( $t_user_id );
	}

	if( config_is_private( $p_config_var ) ) {
		return ApiObjectFactory::faultForbidden( 'Access to \'' . $p_config_var . '\' is denied' );
	}

	if( !config_is_set( $p_config_var ) ) {
		return ApiObjectFactory::faultNotFound( 'Config \'' . $p_config_var . '\' is undefined' );
	}

	return config_get( $p_config_var );
}

