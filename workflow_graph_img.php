<?php

require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'compress_api.php' );
require_api( 'config_api.php' );
require_api( 'graphviz_api.php' );
require_api( 'workflow_api.php' );

auth_ensure_user_authenticated();

if( !config_get( 'relationship_graph_enable' ) ) {
	access_denied();
}

compress_enable();

$t_status_arr  = MantisEnum::getAssocArrayIndexedByValues( config_get( 'status_enum_string' ) );

$t_graph_fontname = config_get( 'relationship_graph_fontname' );
$t_graph_fontsize = config_get( 'relationship_graph_fontsize' );
$t_graph_fontpath = get_font_path();
$t_dot_tool = config_get( 'dot_tool' );

$t_graph_attributes = array();

if( !empty( $t_graph_fontpath ) ) {
	$t_graph_attributes['fontpath'] = $t_graph_fontpath;
}

$t_graph = new Graph( 'workflow', $t_graph_attributes, $t_dot_tool );

$t_graph->set_default_node_attr( array ( 'fontname' => $t_graph_fontname,
										 'fontsize' => $t_graph_fontsize,
										 'shape'    => 'record',
										 'style'    => 'filled',
										 'height'   => '0.2',
										 'width'    => '0.4' ) );

$t_graph->set_default_edge_attr( array ( 'style' => 'solid',
										 'color' => '#0000C0',
										 'dir'   => 'forward' ) );

foreach ( $t_status_arr as $t_from_status => $t_from_label ) {
	$t_enum_status = MantisEnum::getAssocArrayIndexedByValues( config_get( 'status_enum_string' ) );
	foreach ( $t_enum_status as $t_to_status_id => $t_to_status_label ) {
		if( workflow_transition_edge_exists( $t_from_status, $t_to_status_id ) ) {
			$t_graph->add_edge( string_no_break( MantisEnum::getLabel( lang_get( 'status_enum_string' ), $t_from_status ) ),
			                    string_no_break( MantisEnum::getLabel( lang_get( 'status_enum_string' ), $t_to_status_id ) ),
			                    array() );
		}
	}
}

$t_graph->output( 'png', true );
