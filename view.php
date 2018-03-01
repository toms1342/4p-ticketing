<?php

require_once( 'core.php' );

$t_file = __FILE__;
$t_mantis_dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
$t_show_page_header = true;
$t_force_readonly = false;
$t_fields_config_option = 'bug_view_page_fields';

define( 'BUG_VIEW_INC_ALLOW', true );
include( dirname( __FILE__ ) . '/bug_view_inc.php' );
