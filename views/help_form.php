<?php
function wpas_add_help_form($app_id)
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery(document).on('change','#help-object_name',function(){
	if(jQuery(this).attr('value').indexOf('txn-') == 0)
	{
		jQuery('#help-screen_type-div').hide();
	}
	else
	{
		jQuery('#help-screen_type-div').show();
	}
	
        });
});
</script>
	<div class="well">
		<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="A contextual help section provides additional information to the user on how to navigate the various settings in the admin panel. Each help section are displayed as tabs and attached to an entity or taxonomy. Optionally, you can also include a sidebar help section. The sidebar appears to the right-side of the main help content. Generally, a sidebar includes related info links on the entity or the taxonomy it is attached to."> HELP</a></div></div>
		<form action="" method="post" id="help-form" class="form-horizontal">
		<input type="hidden" value="" name="help" id="help">
        <fieldset>
                   <div class="control-group">
							<label class="control-label">Attach To</label>
							<div class="controls">
						<select id="help-object_name" name="help-object_name">
<option value="">Please select</option>
<?php
echo wpas_entity_types($app_id,'help');
?>
</select>
<a href="#" title="Select the entity or taxonomy you want to display helpscreen at." style="cursor: help;"><i class="icon-info-sign"></i></a></div>
							</div>
                   <div class="control-group" id="help-screen_type-div">
							<label class="control-label">Screen Type</label>
							<div class="controls">
						<select id="help-screen_type" name="help-screen_type">
<option value="">Please select</option>
<option value="edit">Edit Page</option>
<option id="list" value="list">List Page</option>
</select>
<a href="#" title="Select the location of the helpscreen. You can select list or edit page." style="cursor: help;"><i class="icon-info-sign"></i></a></div>
							</div>
                   <div class="control-group">
				<label class="control-label">Help SideBar</label>
				<div class="controls">
<?php
$buttons['theme_advanced_buttons1'] = 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent';
$buttons['theme_advanced_buttons2'] = 'tablecontrols';


$settings = array(
'text_area_name'=>'help-screen_sidebar',//name you want for the textarea
'quicktags' => false,
'media_buttons' => false,
'textarea_rows' => 15,
'tinymce' => $buttons,
);
$id = 'help-screen_sidebar';//has to be lower case
wp_editor('',$id,$settings);

?>
<a href="#" title="The content of the helpsceen sidebar." style="cursor: help;"><i class="icon-info-sign"></i></a></div>
							</div>
				</div>                                                                                      
	<div class="control-group">
                   <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
                   <button class="btn  btn-primary pull-right layout-buttons" id="save-help" type="submit" value="Save"><i class="icon-save"></i>Save</button>
                </div>
        </fieldset>
</form>
</div>
<?php
}
function wpas_view_help($help,$help_id)
{
return '<div class="well form-horizontal">
        <div class="row-fluid">
                <button class="btn  btn-danger pull-left" id="cancel" name="cancel" type="button">
                <i class="icon-off"></i>Close</button>
        <div class="help">
        <button class="btn  btn-primary pull-right" id="edit-help" name="Edit" type="submit" href="#' . esc_attr($help_id) . '">
        <i class="icon-edit"></i>Edit Help</button>
        </div>
        </div>
        <fieldset>
                <div class="control-group">
                                <label class="control-label">Attach To </label>
                                <div class="controls"><span id="help-object_name" class="input-xlarge uneditable-input">' . esc_html($help['help-object_name']) . '</span>
                                </div>
                        </div>
                <div class="control-group">
                                <label class="control-label">Screen Type </label>
                                <div class="controls"><span id="help-screen_type" class="input-xlarge uneditable-input">' . esc_html($help['help-screen_type']) . '</span>
                                </div>
                        </div>
     </fieldset>
</div>';
}

?>
