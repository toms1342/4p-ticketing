<?php

/**
 * This include file prints out the bug history
 * $f_bug_id must already be defined
 *
 * @uses access_api.php
 * @uses collapse_api.php
 * @uses config_api.php
 * @uses helper_api.php
 * @uses history_api.php
 * @uses lang_api.php
 * @uses print_api.php
 * @uses string_api.php
 */

if( !defined( 'HISTORY_INC_ALLOW' ) ) {
	return;
}

require_api( 'access_api.php' );
require_api( 'collapse_api.php' );
require_api( 'config_api.php' );
require_api( 'helper_api.php' );
require_api( 'history_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'string_api.php' );

$t_access_level_needed = config_get( 'view_history_threshold' );
if( !access_has_bug_level( $t_access_level_needed, $f_bug_id ) ) {
	return;
}
?>

    <div class="col-md-12 col-xs-12">
        <a id="history"></a>
        <div class="space-10"></div>

<?php
	$t_collapse_block = is_collapsed( 'history' );
	$t_block_css = $t_collapse_block ? 'collapsed' : '';
	$t_block_icon = $t_collapse_block ? 'fa-chevron-down' : 'fa-chevron-up';
	$t_history = history_get_events_array( $f_bug_id );
?>
<div id="history" class="widget-box widget-color-blue2 <?php echo $t_block_css ?>">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-history"></i>
		<?php echo lang_get( 'bug_history' ) ?>
	</h4>
	<div class="widget-toolbar">
		<a data-action="collapse" href="#">
			<i class="1 ace-icon fa <?php echo $t_block_icon ?> bigger-125"></i>
		</a>
	</div>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive">
<table class="table table-bordered table-condensed table-hover table-striped">
	<thead>
		<tr>
			<th class="small-caption">
				<?php echo lang_get( 'date_modified' ) ?>
			</th>
			<th class="small-caption">
				<?php echo lang_get( 'username' ) ?>
			</th>
			<th class="small-caption">
				<?php echo lang_get( 'field' ) ?>
			</th>
			<th class="small-caption">
				<?php echo lang_get( 'change' ) ?>
			</th>
		</tr>
	</thead>

	<tbody>
<?php
	foreach( $t_history as $t_item ) {
?>
		<tr>
			<td class="small-caption">
				<?php echo $t_item['date'] ?>
			</td>
			<td class="small-caption">
				<?php print_user( $t_item['userid'] ) ?>
			</td>
			<td class="small-caption">
				<?php echo string_display( $t_item['note'] ) ?>
			</td>
			<td class="small-caption">
				<?php echo ( $t_item['raw'] ? string_display_line_links( $t_item['change'] ) : $t_item['change'] ) ?>
			</td>
		</tr>
<?php
	} # end for loop
?>
	</tbody>
</table>
</div>
</div>
</div>
</div>
</div>

<?php
