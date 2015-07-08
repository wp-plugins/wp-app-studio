<?php
function wpas_add_help_tab_form($app_id)
{
	?>
		<form action="" method="post" id="help-field-form" class="form-horizontal">
		<input type="hidden" id="app" name="app" value="<?php echo esc_attr($app_id); ?>">
		<input type="hidden" id="help" name="help" value="0">
		<input type="hidden" id="help_field" name="help_field" value="">
		<div class="well">
		<fieldset>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Tab Title","wpas"); ?></label>
		<div class="controls span9">
		<input name="help_fld_name" class="input-xlarge" id="help_fld_name" type="text" placeholder="<?php _e("This is the name which will appear on the EDIT page","wpas"); ?>" value="" >                            
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Tab Content","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="wpas-std-textarea" id="help_fld_content" name="help_fld_content"></textarea>
		</div>
		</div>
		</fieldset>
		</div>

		<div class="control-group">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-help-field" name="Save" type="submit"><?php _e("Save","wpas"); ?></button>
		</div>
		</form>
		<?php
}
?>
