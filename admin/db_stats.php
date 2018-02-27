<?php

require_once( dirname( dirname( __FILE__ ) ) . '/core.php' );

access_ensure_global_level( config_get_global( 'admin_site_threshold' ) );

layout_page_header();

layout_admin_page_begin();

/**
 * Output HTML Table Row
 *
 * @param string $p_description Row Description.
 * @param string $p_value       Row Value.
 * @return void
 */
function print_info_row( $p_description, $p_value ) {
	echo '<tr>';
	echo '<td class="category">' . $p_description . '</td>';
	echo '<td>' . $p_value . '</td>';
	echo '</tr>';
}

/**
 * Function to get row count for a given table
 *
 * @param string $p_table Table name.
 * @return integer row count
 */
function helper_table_row_count( $p_table ) {
	$t_table = $p_table;
	$t_query = 'SELECT COUNT(*) FROM ' . $t_table;
	$t_result = db_query( $t_query );
	$t_count = db_result( $t_result );

	return $t_count;
}
?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
<h4 class="widget-title lighter">
	<i class="ace-icon fa fa-database"></i>
	<?php echo lang_get( 'mantisbt_database_statistics' ) ?>
</h4>
</div>

<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive">
<table class="table table-bordered table-striped table-condensed table-hover">
	<thead>
	<tr class="row-category">
		<th>Table Name</th>
		<th>Record Count</th>
	</tr>
	<thead>
	<tbody>
<?php
foreach( db_get_table_list() as $t_table ) {
	if( db_table_exists( $t_table ) ) {
			print_info_row( $t_table, helper_table_row_count( $t_table ) );
	}
}
?>
	</tbody>
</table>
</div>
</div>
</div>
</div>
</div>

<?php
layout_admin_page_end();