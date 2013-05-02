<?php

function wpas_import_page($msg='')
{
?>
<div id="was-container" class="container-fluid">
<ul class="breadcrumb">
<li id="first">
<a href="<?php echo admin_url('admin.php?page=wpas_app_list'); ?>">
<i class="icon-home"></i>
Home
</a>
<span class="divider">/</span>
</li>
<li id="second" class="inactive">Import</li>
</ul>
<?php 
if($msg == 'success')
{
?>
<div class='alert alert-success'>Import completed successfully.</div><p>
<?php
}
elseif($msg == 'dupe_diff_date')
{
?>
<div class='alert alert-error'> Import completed successfully with a warning: An application with the same name exists.
</div><p>
<?php
}
elseif($msg == 'dupe')
{
?>
<div class='alert alert-error'> This application already exists.
</div><p>
<?php
}
elseif($msg)
{
?>
<div class='alert alert-error'><?php echo $msg; ?> </div><p>
<?php } ?>
<p class="install-help">Import a WP App Studio Application in .wpas format by uploading it here.</p>
<form class="form-inline" name="importWpas" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?> ">
<input type="file" name="wpasimport" class="input-xlarge" id="wpasimport">
<button type="submit" class="button-primary">Import Now</button>
</form>
</div>
<?php
}
?>
