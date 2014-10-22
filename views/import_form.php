<?php

function wpas_import_page($msg='')
{
?>
<div id="was-container" class="container-fluid">
<ul class="breadcrumb">
<li id="first">
<a href="<?php echo admin_url('admin.php?page=wpas_app_list'); ?>">
<i class="icon-home"></i>
<?php _e("Home","wpas"); ?>
</a>
<span class="divider">/</span>
</li>
<li id="second" class="inactive"><?php _e("Import","wpas"); ?></li>
</ul>
<?php 
if($msg == 'success')
{
?>
<div class='alert alert-success'><?php _e("Import completed successfully.","wpas"); ?></div><p>
<?php
}
elseif($msg == 'dupe_diff_date')
{
?>
<div class='alert alert-error'><?php _e("Import completed successfully with a warning: An application with the same id already exists.","wpas"); ?>
</div><p>
<?php
}
elseif($msg == 'dupe_name')
{
?>
<div class='alert alert-error'><?php _e("Import completed successfully with a warning: An application with the same name already exists.","wpas"); ?>
</div><p>
<?php
}
elseif($msg == 'dupe')
{
?>
<div class='alert alert-error'><?php _e("Import has not been completed. This application already exists.","wpas"); ?>
</div><p>
<?php
}
elseif($msg == 'error_data')
{
?>
<div class='alert alert-error'><?php _e("Import has not been completed. Data is not in correct format.","wpas"); ?>
</div><p>
<?php
}
elseif(!empty($msg))
{
?>
<div class='alert alert-error'><?php echo esc_html($msg); ?></div><p>
<?php } ?>
<p class="install-help"><?php _e("Import a WP App Studio Application in .wpas format by uploading it here.","wpas"); ?></p>

<form class="form-inline" name="importWpas" enctype="multipart/form-data" method="POST" action="">
<?php wp_nonce_field('wpas_import_file','wpas_import_nonce'); ?>
<input type="file" style="padding-bottom:30px;" name="wpasimport" class="input-xlarge" id="wpasimport">
<button type="submit" class="button-primary"><?php _e("Import Now","wpas"); ?></button>
</form>
</div>
<?php
}
?>
