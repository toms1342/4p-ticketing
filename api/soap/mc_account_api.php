<?php

/**
 * Get username, realname and email from for a given user id
 * @param integer $p_user_id A valid user identifier.
 * @return array
 */
function mci_account_get_array_by_id( $p_user_id ) {
	$t_result = array();
	$t_result['id'] = (int)$p_user_id;

	if( user_exists( $p_user_id ) ) {

		$t_current_user_id = auth_get_current_user_id();
		$t_access_level = user_get_field ( $t_current_user_id, 'access_level' );
		$t_can_manage = access_has_global_level( config_get( 'manage_user_threshold' ) ) &&
			access_has_global_level( $t_access_level );

		# this deviates from the behaviour of view_user_page.php, but it is more intuitive
		$t_is_same_user = $t_current_user_id === $p_user_id;

		$t_can_see_realname = access_has_project_level( config_get( 'show_user_realname_threshold' ) );
		$t_can_see_email = access_has_project_level( config_get( 'show_user_email_threshold' ) );

		$t_result['name'] = user_get_username( $p_user_id );

		if ( $t_is_same_user || $t_can_manage || $t_can_see_realname ) {
			$t_realname = user_get_realname( $p_user_id );

			if( !empty( $t_realname ) ) {
				$t_result['real_name'] = $t_realname;
			}
		}

		if ( $t_is_same_user || $t_can_manage || $t_can_see_email ) {
			$t_email = user_get_email( $p_user_id );

			if( !empty( $t_email ) ) {
				$t_result['email'] = $t_email;
			}
		}
	}
	return $t_result;
}

/**
 * Get username, realname and email from for a set of given user ids
 * @param array $p_user_ids An array of user identifiers.
 * @return array
 */
function mci_account_get_array_by_ids ( array $p_user_ids ) {
	$t_result = array();

	foreach ( $p_user_ids as $t_user_id ) {
		$t_result[] = mci_account_get_array_by_id( $t_user_id );
	}

	return $t_result;
}
