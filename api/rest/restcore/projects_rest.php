<?php

$g_app->group('/projects', function() use ( $g_app ) {
	$g_app->get( '', 'rest_projects_get' );
	$g_app->get( '/', 'rest_projects_get' );
});

/**
 * A method to get list of projects accessible to user with all their related information.
 *
 * @param \Slim\Http\Request $p_request   The request.
 * @param \Slim\Http\Response $p_response The response.
 * @param array $p_args Arguments
 * @return \Slim\Http\Response The augmented response.
 */
function rest_projects_get( \Slim\Http\Request $p_request, \Slim\Http\Response $p_response, array $p_args ) {
	$t_user_id = auth_get_current_user_id();
	$t_lang = mci_get_user_lang( $t_user_id );

	$t_project_ids = user_get_all_accessible_projects( $t_user_id, /* disabled */ false );
	$t_projects = array();

	foreach( $t_project_ids as $t_project_id ) {
		$t_project = mci_project_get( $t_project_id, $t_lang, /* detail */ true );
		$t_subproject_ids = user_get_accessible_subprojects( $t_user_id, $t_project_id );
		if( !empty( $t_subproject_ids ) ) {
			$t_subprojects = array();
			foreach( $t_subproject_ids as $t_subproject_id ) {
				$t_subprojects[] = mci_project_as_array_by_id( $t_subproject_id );
			}

			$t_project['subProjects'] = $t_subprojects;
		}

		$t_projects[] = $t_project;
	}

	$t_result = array( 'projects' => $t_projects );

	return $p_response->withStatus( HTTP_STATUS_SUCCESS )->withJson( $t_result );
}


