<?php

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'config_api.php' );
require_api( 'file_api.php' );
require_api( 'form_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'utility_api.php' );

# Check if project documentation feature is enabled.
if( OFF == config_get( 'enable_project_documentation' ) ||
	!file_is_uploading_enabled() ||
	!file_allow_project_upload() ) {
	access_denied();
}

access_ensure_project_level( config_get( 'upload_project_file_threshold' ) );

$t_max_file_size = (int)min( ini_get_number( 'upload_max_filesize' ), ini_get_number( 'post_max_size' ), config_get( 'max_file_size' ) );

layout_page_header();

layout_page_begin( 'proj_doc_page.php' );

print_doc_menu('proj_doc_add_page.php');
?>

<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container">
<form method="post" enctype="multipart/form-data" action="proj_doc_add.php">
	<?php echo form_security_field( 'proj_doc_add' ) ?>
	<div class="widget-box widget-color-blue2">
		<div class="widget-header widget-header-small">
			<h4 class="widget-title lighter">
				<i class="ace-icon fa fa-upload"></i>
				<?php echo lang_get( 'upload_file_title' ) ?>
			</h4>
		</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive">
<table class="table table-bordered table-condensed table-striped">
<tr>
	<th class="category" width="25%">
		<span class="required">*</span> <?php echo lang_get( 'title' ) ?>
	</th>
	<td width="75%">
		<input type="text" name="title" class="input-sm" size="70" maxlength="250" required />
	</td>
</tr>
<tr>
	<th class="category">
		<?php echo lang_get( 'description' ) ?>
	</th>
	<td>
		<textarea class="form-control" name="description" cols="60" rows="7"></textarea>
	</td>
</tr>
<tr>
	<td class="category">
		<span class="required">*</span> <?php echo lang_get( 'select_file' ); ?>
		<br />
		<?php print_max_filesize( $t_max_file_size ); ?>
	</td>
	<td>
		<input type="hidden" name="max_file_size" value="<?php echo $t_max_file_size ?>" required />
		<input name="file" type="file" size="70" />
	</td>
</tr>
</table>
		</div>
		</div>
		<div class="widget-toolbox padding-8 clearfix">
			<span class="required pull-right"> * <?php echo lang_get('required') ?></span>
			<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo lang_get('upload_file_button') ?>"/>
		</div>
		</div>
	</div>
</form>
</div>
</div>

<?php
layout_page_end();
