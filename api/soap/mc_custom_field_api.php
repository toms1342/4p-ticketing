<?php

/**
 * Get the custom field id given an object ref.  The id is set based on the following algorithm:
 * - id from objectref (if not zero).
 * - id corresponding to name in object ref.
 * - 0, if object ref doesn't contain an id or a name.
 *
 * @param stdClass $p_object_ref An associate array with "id" and "name" keys.
 * @return integer
 */
function mci_get_custom_field_id_from_objectref( stdClass $p_object_ref ) {
	$p_object_ref = ApiObjectFactory::objectToArray( $p_object_ref );

	if( isset( $p_object_ref['id'] ) && (int) $p_object_ref['id'] != 0 ) {
		$t_id = (int)$p_object_ref['id'];
	} else {
		if( !is_blank( $p_object_ref['name'] ) ) {
			$t_id = custom_field_get_id_from_name( $p_object_ref['name'] );
		} else {
			$t_id = 0;
		}
	}

	return $t_id;
}
