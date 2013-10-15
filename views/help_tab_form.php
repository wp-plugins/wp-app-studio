<?php
function wpas_view_help_tabs($help_name)
{
	return '<div id="title-bar"><div class="row-fluid"><h4 class="span3"><i class="icon-columns"></i>Tabs</h3>
		<div class="span9 field" id="add_field_help">
		<a class="btn btn-info  pull-right" href="#help'. esc_attr($help_name) . '" class="add-new" ><i class="icon-plus-sign"></i>Add New</a>
		</div></div></div>';
}
function wpas_view_help_tabs_list($help_tab)
{
	$ret = '<div id="field-title"><div class="row-fluid"><div class="span1"></div>
        <div id="field-label" class="span6">Tab Title</div>
        <div id="edit-field" class="span2"></div>
        <div id="delete-field" class="span2"></div>
        </div></div>';
	$ret .= '<ul id="fields-sort" class="sortable ui-sortable">';
	foreach($help_tab as $key => $myfield)
	{
		$ret .= '<li id="' . esc_attr($key) . '"><div id="field-row"><div class="row-fluid">
			<div class="span1"><i class="icon-sort"></i></div>
			<div class="span6" id="field-label">' . esc_html($myfield['help_fld_name']) . '</div>
			<div class="span2" id="edit-help-field"><a href="#' . esc_attr($key) . '">Edit</a></div>
			<div class="span2" id="delete-help-field"><a href="#' . esc_attr($key) . '">Delete</a></div></div></div></li>';
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
		<div class="control-group">
		<label class="control-label">Tab Title</label>
		<div class="controls">
		<input name="help_fld_name" class="input-xlarge" id="help_fld_name" type="text" placeholder="This is the name which will appear on the EDIT page" value="" >                            
		</div>
		</div>
		<div class="control-group">
		<label class="control-label">Tab Content</label>
		<div class="controls">
		<?php

	$buttons['theme_advanced_buttons1'] = 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,link,unlink';
	$buttons['theme_advanced_buttons2'] = 'tablecontrols';

		$settings = array(
				'text_area_name'=>'help_fld_content',//name you want for the textarea
				'quicktags' => false,
				'media_buttons' => false,
				'textarea_rows' => 15,
				'tinymce' => $buttons,
		);
	$id = 'help_fld_content';//has to be lower case
	wp_editor('',$id,$settings);

	?>

		</div>
		</div>
		</fieldset>
		</div>

		<div class="control-group">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-help-field" name="Save" type="submit">Save</button>
		</div>
		</form>
		<?php
}
?>
