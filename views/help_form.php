<?php
function wpas_add_help_form($app_id)
{
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#help-type').change(function() {
			app_id = $('input#app').val();
			if($(this).find('option:selected').val() == 'ent')
			{
				$('#help-others').show();
				$('#help-screen_type-div').show();
				$.get(ajaxurl,{action:'wpas_get_entities',type:'help',app_id:app_id}, function(response)
				{
					$('select#help-entity').html(response);
					$('#help-entity-div').show();
					$('#help-entity').show();
					$('#help-tax-div').hide();
				});
			}
			else if($(this).find('option:selected').val() == 'tax')
			{
				$('#help-others').show();
				$('#help-screen_type-div').hide();
				$.get(ajaxurl,{action:'wpas_get_entities',type:'tax',app_id:app_id}, function(response)
				{
					$('select#help-tax').html(response);
					$('#help-tax-div').show();
					$('#help-tax').show();
					$('#help-entity-div').hide();
				});
			}
			else
			{
				$('#help-others').hide();
			}
		});
	});
</script>
<div class="well">
<div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("A contextual help section provides additional information to the user on how to navigate the various settings in the admin panel. Each help section are displayed as tabs and attached to an entity or taxonomy. Optionally, you can also include a sidebar help section. The sidebar appears to the right-side of the main help content. Generally, a sidebar includes related info links on the entity or the taxonomy it is attached to.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
<form action="" method="post" id="help-form" class="form-horizontal">
	<input type="hidden" value="" name="help" id="help">
	<fieldset>
	<div class="control-group" id="help-type-div" name="help-type-div">
	<label class="control-label"><?php _e("Type","wpas"); ?></label>
	<div class="controls">
	<select id="help-type" name="help-type">
	<option value=""><?php _e("Please select","wpas"); ?></option>
	<option value="ent"><?php _e("Entities","wpas"); ?></option>
	<option value="tax"><?php _e("Taxonomies","wpas"); ?></option>
	</select>
	<a href="#" title="<?php _e("Select the type of help screen.","wpas"); ?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
	</div>
	<div id="help-others" name="help-others" style="display:none;">
	<div class="control-group" id="help-entity-div" name="help-entity-div">
	<label class="control-label"><?php _e("Attach To Entity","wpas"); ?></label>
	<div class="controls">
	<select id="help-entity" name="help-entity">
	</select>
	<a href="#" title="<?php _e("Select the entity you want to display help screen at.","wpas"); ?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
	</div>
	<div class="control-group" id="help-tax-div" name="help-tax-div">
	<label class="control-label"><?php _e("Attach To Taxonomy","wpas"); ?></label>
	<div class="controls">
	<select id="help-tax" name="help-tax">
	</select>
	<a href="#" title="<?php _e("Select the taxonomy you want to display help screen at.","wpas"); ?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
	</div>
	<div class="control-group" id="help-screen_type-div" name="help-screen_type-div">
	<label class="control-label"><?php _e("Screen Type","wpas"); ?></label>
	<div class="controls">
	<select id="help-screen_type" name="help-screen_type">
	<option value=""><?php _e("Please select","wpas"); ?></option>
	<option value="edit"><?php _e("Edit Page","wpas"); ?></option>
	<option id="list" value="list"><?php _e("List Page","wpas"); ?></option>
	</select>
	<a href="#" title="<?php _e("Select the location of the help screen. You can select list or edit page.","wpas"); ?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
	</div>
	<div class="control-group">
	<label class="control-label"><?php _e("Help SideBar","wpas"); ?></label>
	<div class="controls">
	<?php display_tinymce('help-screen_sidebar',''); ?>
	<a href="#" title="<?php _e("The content of the help screen sidebar.","wpas"); ?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
	</div>
	</div><!-- end help-others div -->
	</div>                                                                                      
	<div class="control-group">
	<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
	<button class="btn  btn-primary pull-right layout-buttons" id="save-help" type="submit" value="Save"><i class="icon-save"></i><?php _e("Save","wpas"); ?></button>
	</div>
	</fieldset>
	</form>
	</div>
<?php
}
function wpas_view_help($help,$help_id,$app)
{
	if($help['help-type'] == 'ent')
	{
		$type = "Entity";
	}
	else
	{
		$type = "Taxonomy";
	}
	$ret = '<div class="well form-horizontal">
		<div class="row-fluid">
		<button class="btn  btn-danger pull-left" id="cancel" name="cancel" type="button">
		<i class="icon-off"></i>' . __("Close","wpas") . '</button>
		<div class="help">
		<button class="btn  btn-primary pull-right" id="edit-help" name="Edit" type="submit" href="#' . esc_attr($help_id) . '">
		<i class="icon-edit"></i>' . __("Edit Help","wpas") . '</button>
		</div>
		</div>
		<fieldset>
		<div class="control-group">
		<label class="control-label">' . __("Type","wpas") . ' </label>
		<div class="controls"><span id="help-type" class="input-xlarge uneditable-input">' . $type . '</span>
		</div>
		</div>
		<div class="control-group">
		<label class="control-label">' . __("Attach To","wpas") . ' </label>';
	if(isset($help['help-entity']))
	{  
		$ret .= '<div class="controls"><span id="help-entity" class="input-xlarge uneditable-input">';
		$ret .= esc_html($app['entity'][$help['help-entity']]['ent-label']);
	}
	elseif(isset($help['help-tax']))
	{
		$ret .= '<div class="controls"><span id="help-tax" class="input-xlarge uneditable-input">';
		$ret .= esc_html($app['taxonomy'][$help['help-tax']]['txn-label']);
	}
	$ret .= '</span>
		</div>
		</div>
		<div class="control-group">
		<label class="control-label">Screen Type </label>
		<div class="controls"><span id="help-screen_type" class="input-xlarge uneditable-input">' . esc_html($help['help-screen_type']) . '</span>
		</div>
		</div>
		</fieldset>
		</div>';
	return $ret;
}

?>
