<?php

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'database_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'summary_api.php' );
require_api( 'user_api.php' );

$f_project_id = gpc_get_int( 'project_id', helper_get_current_project() );

# Override the current page to make sure we get the appropriate project-specific configuration
$g_project_override = $f_project_id;

access_ensure_project_level( config_get( 'view_summary_threshold' ) );

$t_time_stats = summary_helper_get_time_stats( $f_project_id );

$t_summary_header_arr = explode( '/', lang_get( 'summary_header' ) );

$t_summary_header = '';
foreach ( $t_summary_header_arr as $t_summary_header_name ) {
	$t_summary_header .= '<th class="align-right">';
	$t_summary_header .= $t_summary_header_name;
	$t_summary_header .= '</th>';
}

layout_page_header( lang_get( 'summary_link' ) );

layout_page_begin( __FILE__ );

print_summary_menu( 'summary_page.php' );
print_summary_submenu();
?>

<div class="col-md-12 col-xs-12">
<div class="space-10"></div>

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-bar-chart-o"></i>
		<?php echo lang_get('summary_title') ?>
	</h4>
</div>

<div class="widget-body">
<div class="widget-main no-padding">


<!-- LEFT COLUMN -->
<div class="col-md-6 col-xs-12">

	<!-- BY PROJECT -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_project' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_project(); ?>
	</table>
	</div>

	<!-- BY STATUS -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_status' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_enum( 'status' ) ?>
	</table>
	</div>

	<!-- BY SEVERITY -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_severity' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_enum( 'severity' ) ?>
	</table>
	</div>

	<!-- BY CATEGORY -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_category' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_category() ?>
	</table>
	</div>

	<!-- TIME STATS -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th colspan="2"><?php echo lang_get( 'time_stats' ) ?></th>
			</tr>
		</thead>
		<tr>
			<td><?php echo lang_get( 'longest_open_bug' ) ?></td>
			<td class="align-right"><?php
				if( $t_time_stats['bug_id'] > 0 )  {
					print_bug_link( $t_time_stats['bug_id'] );
				}
			?></td>
		</tr>
		<tr>
			<td><?php echo lang_get( 'longest_open' ) ?></td>
			<td class="align-right"><?php echo $t_time_stats['largest_diff'] ?></td>
		</tr>
		<tr>
			<td><?php echo lang_get( 'average_time' ) ?></td>
			<td class="align-right"><?php echo $t_time_stats['average_time'] ?></td>
		</tr>
		<tr>
			<td><?php echo lang_get( 'total_time' ) ?></td>
			<td class="align-right"><?php echo $t_time_stats['total_time'] ?></td>
		</tr>
	</table>
	</div>

	<!-- DEVELOPER STATS -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th><?php echo lang_get( 'developer_stats' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_developer() ?>
	</table>
</div>
</div>

<!-- RIGHT COLUMN -->
<div class="col-md-6 col-xs-12">

	<!-- BY DATE -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_date' ) ?></th>
				<th class="align-right"><?php echo lang_get( 'opened' ); ?></th>
				<th class="align-right"><?php echo lang_get( 'resolved' ); ?></th>
				<th class="align-right"><?php echo lang_get( 'balance' ); ?></th>
			</tr>
		</thead>
		<?php summary_print_by_date( config_get( 'date_partitions' ) ) ?>
	</table>
	</div>

	<!-- MOST ACTIVE -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-85"><?php echo lang_get( 'most_active' ) ?></th>
				<th class="align-right"><?php echo lang_get( 'score' ); ?></th>
			</tr>
		</thead>
		<?php summary_print_by_activity() ?>
	</table>
	</div>

	<!-- LONGEST OPEN -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-85"><?php echo lang_get( 'longest_open' ) ?></th>
				<th class="align-right"><?php echo lang_get( 'days' ); ?></th>
			</tr>
		</thead>
		<?php summary_print_by_age() ?>
	</table>
	</div>

	<!-- BY RESOLUTION -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_resolution' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_enum( 'resolution' ) ?>
	</table>
	</div>

	<!-- BY PRIORITY -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_priority' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_enum( 'priority' ) ?>
	</table>
	</div>

	<!-- REPORTER STATS -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'reporter_stats' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_reporter() ?>
	</table>
	</div>

	<!-- REPORTER EFFECTIVENESS -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'reporter_effectiveness' ) ?></th>
				<th class="align-right"><?php echo lang_get( 'severity' ); ?></th>
				<th class="align-right"><?php echo lang_get( 'errors' ); ?></th>
				<th class="align-right"><?php echo lang_get( 'total' ); ?></th>
			</tr>
		</thead>
		<?php summary_print_reporter_effectiveness( config_get( 'severity_enum_string' ), config_get( 'resolution_enum_string' ) ) ?>
	</table>
	</div>

</div>

<!-- BOTTOM -->
<div class="col-md-12 col-xs-12">

	<!-- REPORTER BY RESOLUTION -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-15"><?php echo lang_get( 'reporter_by_resolution' ) ?></th>
				<?php
					$t_resolutions = MantisEnum::getValues( config_get( 'resolution_enum_string' ) );

					foreach ( $t_resolutions as $t_resolution ) {
						echo '<th class="align-right">', get_enum_element( 'resolution', $t_resolution ), "</th>\n";
					}

					echo '<th class="align-right">', lang_get( 'total' ), "</th>\n";
					echo '<th class="align-right">', lang_get( 'percentage_errors' ), "</th>\n";
				?>
			</tr>
		</thead>
		<?php summary_print_reporter_resolution( config_get( 'resolution_enum_string' ) ) ?>
	</table>
	</div>

	<!-- DEVELOPER BY RESOLUTION -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-15"><?php echo lang_get( 'developer_by_resolution' ) ?></th>
				<?php
					$t_resolutions = MantisEnum::getValues( config_get( 'resolution_enum_string' ) );

					foreach ( $t_resolutions as $t_resolution ) {
						echo '<th class="align-right">', get_enum_element( 'resolution', $t_resolution ), "</th>\n";
					}

					echo '<th class="align-right">', lang_get( 'total' ), "</th>\n";
					echo '<th class="align-right">', lang_get( 'percentage_fixed' ), "</th>\n";
				?>
			</tr>
		</thead>
		<?php summary_print_developer_resolution( config_get( 'resolution_enum_string' ) ) ?>
	</table>
	</div>

</div>

</div>
</div>
<div class="clearfix"></div>
<div class="space-10"></div>
</div>
</div>

<?php
layout_page_end();
