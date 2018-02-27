<?php

require_once( dirname( dirname( __FILE__ ) ) . '/core.php' );

# Load schema version needed to render admin menu bar
require_once( 'schema.php' );

access_ensure_global_level( config_get_global( 'admin_site_threshold' ) );

layout_page_header( 'MantisBT Administration - System Utilities' );

layout_admin_page_begin();

print_admin_menu_bar( 'system_utils.php' );

?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-wrench"></i>
		System Utilities
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive">
<table class="table table-bordered table-striped table-condensed table-hover">
	<thead>
	<tr class="category">
		<th width="70%">Description</th>
		<th width="30%" class="center">Execute</th>
	</tr>
	</thead>
	<tbody>
		<tr>
			<td>Move attachments stored in database schema to disk files.</td>
			<td class="center">
				<?php html_button( 'move_attachments_page.php', 'Move Attachments to Disk', array( 'type' => 'bug' ) );?>
			</td>
		</tr>
		<tr>
			<td>Move project files stored in database schema to disk.</td>
			<td class="center">
				<?php html_button( 'move_attachments_page.php', 'Move Project Files to Disk', array( 'type' => 'project' ) );?>
			</td>
		</tr>
		<tr>
			<td>Show database statistics.</td>
			<td class="center">
				<?php html_button( 'db_stats.php', 'Display', array() );?>
			</td>
		</tr>
	</tbody>
</table>
</div>
</div>
</div>
</div>
</div>

<?php
layout_admin_page_end();
