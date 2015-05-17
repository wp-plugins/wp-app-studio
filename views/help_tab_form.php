<?php
function wpas_view_help_tabs($help_name)
{
	return '<div id="title-bar"><div class="row-fluid"><h4 class="span3"><i class="icon-columns"></i>' . __("Tabs","wpas") . '</h3>
		<div class="span9 field" id="add_field_help">
		<a class="btn btn-info  pull-right" href="#help'. esc_attr($help_name) . '" class="add-new" ><i class="icon-plus-sign"></i>' . __("Add New","wpas") . '</a>
		</div></div></div>';
}
function wpas_view_help_tabs_list($help_tab)
{
	$ret = '<div id="field-title"><div class="row-fluid"><div class="span1"></div>
        <div id="field-label" class="span6">' . __("Tab Title","wpas") . '</div>
        <div id="edit-field" class="span2"></div>
        <div id="delete-field" class="span2"></div>
        </div></div>';
	$ret .= '<ul id="fields-sort" class="sortable ui-sortable">';
	foreach($help_tab as $key => $myfield)
	{
		$ret .= '<li id="' . esc_attr($key) . '"><div class="field-row"><div class="row-fluid">
			<div class="span1"><i class="icon-sort"></i></div>
			<div class="span6" class="field-label">' . esc_html($myfield['help_fld_name']) . '</div>
			<div class="span2" id="edit-help-field"><a href="#' . esc_attr($key) . '">' . __("Edit","wpas") . '</a></div>
			<div class="span2" id="delete-help-field"><a href="#' . esc_attr($key) . '">' . __("Delete","wpas") . '</a></div></div></div></li>';
	}
	$ret .= '</ul>';
	return $ret;
}
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
