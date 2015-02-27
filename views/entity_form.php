<?php
function wpas_add_entity_form()
{
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#ent-inline-ent').click(function (){
		if($(this).attr('checked')){
			$(this).setInlineTabs('inline');
		}
		else {
			$(this).setInlineTabs('notinline');
		}
	});
	$.fn.setInlineTabs = function (type){
		if(type == 'inline')
		{
			$('#myTab a:first').tab('show');
			$('#tabs-3-li').hide();
			$('#tabs-4-li').hide();
			$('#tabs-5-li').hide();
			$('#tabs-6-li').hide();
			$('#tabs-3').removeClass('active');
			$('#tabs-4').removeClass('active');
			$('#tabs-5').removeClass('active');
			$('#tabs-6').removeClass('active');
			$('#ent-hierarchical-div').hide();
			$('#ent-msg-cust-fields-div').hide();
		}	
		else {
			$('#myTab a:first').tab('show');
			$('#tabs-3-li').show();
			$('#tabs-4-li').show();
			$('#tabs-5-li').show();
			$('#tabs-6-li').show();
			$('#ent-hierarchical-div').show();
			$('#ent-msg-cust-fields-div').show();
		}	
		
	}
	$('#ent-supports_comments').click(function () {
		if($(this).attr('checked'))
		{
			$('#ent-com_type_div').show();
			$('#ent-com_type').val('wp');
		}
		else
		{
			$('#ent-com_type_div').hide();
			$('#ent-com_detail_div').hide();
		}
	});
	$('#ent-com_type').click(function () {
		if($(this).find('option:selected').val() == 'custom')
		{
			$('#ent-com_detail_div').show();
			$('#ent-com_enable_trash').attr('checked',true);
			$('#ent-com_enable_spam').attr('checked',true);
		}
		else
		{
			$('#ent-com_detail_div').hide();
		}
	});
	$('#ent-rewrite').click(function () {
		if($(this).find('option:selected').val() == 0)
		{
			$('#ent-rewrite_slug').attr('disabled',true);
		}
		if($(this).find('option:selected').val() == 1)
		{
			$('#ent-rewrite_slug').removeAttr('disabled');
		}
	});
	$('#ent-hierarchical').click(function() {
		if($(this).find('option:selected').val() == 1)
		{
			$('#ent-page-attributes-div').show();
			$('#ent-parent_item_colon_div').show();
		}
		else
		{
			$('#ent-page-attributes-div').hide();
			$('#ent-parent_item_colon_div').hide();
		}
	});
	$('#ent-advanced-option').click(function() {
		if($(this).attr('checked'))
		{
			$('#tabs').show();
		}
		else
		{
			$('#tabs').hide();
		}
	});
	$('#ent-show_ui').click(function() {
		if($(this).find('option:selected').val() == 0)
		{
			$('#ent-show_in_menu_div').hide();
			$('#ent-menu_icon_type_div').hide();
			$(this).showEntIcons('');
			$('#ent-menu_position_div').hide();
			$('#ent-top_level_page_div').hide();
		}
		else
		{
			$('#ent-show_in_menu_div').show();
			$('#ent-menu_icon_type_div').show();
			$('#ent-menu_icon_type').val('');
			$('#ent-menu_position_div').show();
			$('#ent-top_level_page_div').show();
		}
	});
	$('#ent-show_in_menu').click(function() {
		var menu_selected = $(this).find('option:selected').val();

		if(menu_selected == 0)
		{
			$('#ent-menu_icon_type_div').hide();
			$(this).showEntIcons('');
			$('#ent-menu_position_div').hide();
			$('#ent-top_level_page_div').hide();
		}
		else if(menu_selected == 2)
		{
			$('#ent-menu_icon_type_div').show();
			$('#ent-menu_icon_type').val('');
			$(this).showEntIcons('');
			$('#ent-menu_position_div').hide();
			$('#ent-top_level_page_div').show();
		}
		else
		{
			$('#ent-menu_icon_type_div').show();
			$('#ent-menu_icon_type').val('');
			$(this).showEntIcons('');
			$('#ent-menu_position_div').show();
			$('#ent-top_level_page_div').hide();
		}
	});
	$.fn.showEntIcons = function(icon_type){
		switch (icon_type) {
			case 'image':
				$('#ent-menu_icon_div').show();
				$('#ent-menu_icon_fa_div').hide();
				$('#ent-menu_icon_dash_div').hide();
				$('#ent-menu_icon_base64_div').hide();
				$('#ent-menu_icon_base64').val('');
				break;
			case 'fa':
				$('#ent-menu_icon_div').hide();
				$('#ent-menu_icon_fa_div').show();
				$('#ent-menu_icon_dash_div').hide();
				$('#ent-menu_icon_base64_div').hide();
				$('#ent-menu_icon_base64').val('');
				break;
			case 'dash':
				$('#ent-menu_icon_div').hide();
				$('#ent-menu_icon_fa_div').hide();
				$('#ent-menu_icon_dash_div').show();
				$('#ent-menu_icon_base64_div').hide();
				$('#ent-menu_icon_base64').val('');
				break;
			case 'base64':
				$('#ent-menu_icon_div').hide();
				$('#ent-menu_icon_fa_div').hide();
				$('#ent-menu_icon_dash_div').hide();
				$('#ent-menu_icon_base64_div').show();
				break;
			default:
				$('#ent-menu_icon_div').hide();
				$('#ent-menu_icon_fa_div').hide();
				$('#ent-menu_icon_dash_div').hide();
				$('#ent-menu_icon_base64_div').hide();
				$('#ent-menu_icon_base64').val('');
				break;
		}
	}
	$('#ent-menu_icon_type').click(function() {
		$(this).showEntIcons($(this).val());
	});

});
</script>
<form action="" method="post" id="entity-form" class="form-horizontal">
			<input type="hidden" value="" name="ent" id="ent">
	<fieldset>
		<div class="well">
		<div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("The entity is a person, object, place or event for which data is collected.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
		<div class="field-container">
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Name","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-name" id="ent-name" type="text" placeholder="<?php _e("e.g. product","wpas"); ?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("General name for the entity, usually singular max. 16 characters, can not contain capital letters,reserved words,dashes or spaces.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e(" Plural","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-label" id="ent-label" type="text" value="" placeholder="<?php _e("e.g. Products","wpas"); ?>"/>
		<a href="#" style="cursor: help;" title="<?php _e("A plural descriptive name for the entity marked for translation.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Singular","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-singular-label" id="ent-singular-label" type="text" value="" placeholder="<?php _e("e.g. Product","wpas"); ?>"/>
		<a href="#" style="cursor: help;" title="<?php _e("It is the name for one object of this entity.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Inline Entity","wpas"); ?>
		<input name="ent-inline-ent" id="ent-inline-ent" type="checkbox" value="1"/>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid" id='ent-hierarchical-div'>
		<label class="control-label span3"><?php _e("Hierarchical","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-hierarchical" id="ent-hierarchical" class="input-mini">
		<option selected="selected" value="0"><?php _e("False","wpas"); ?></option>
		<option value="1"><?php _e("True","wpas");?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("Whether the entity is hierarchical (e.g. page). Allows Parent to be specified.","wpas"); ?>">
		<i class="icon-info-sign"></i></a> (<?php _e("default: False","wpas"); ?>)
		</div>
		</div>

		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Description","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="wpas-std-textarea" id="ent-desc" name="ent-desc"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("A short descriptive summary of what the entity is.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Show Advanced Options","wpas"); ?>
		<input name="ent-advanced-option" id="ent-advanced-option" type="checkbox" value="1"/>
		</label>
		</div>
		</div>
		</div>
		</div><!--well-->
		<div id="tabs" style="display:none;">
		<ul id="myTab" class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tabs-1"><?php _e("Label Options","wpas"); ?></a></li>
		<li id='tabs-2-li'><a data-toggle="tab" href="#tabs-2"><?php _e("Messages","wpas"); ?></a></li>
		<li id='tabs-3-li'><a data-toggle="tab" href="#tabs-3"><?php _e("Options","wpas"); ?></a></li>
		<li id='tabs-4-li'><a data-toggle="tab" href="#tabs-4"><?php _e("Menu Options","wpas"); ?></a></li>
		<li id='tabs-5-li'><a data-toggle="tab" href="#tabs-5"><?php _e("Display Options","wpas"); ?></a></li>
		<li id='tabs-6-li'><a data-toggle="tab" href="#tabs-6"><?php _e("Comments","wpas"); ?></a></li>
		</ul>
		<div id="myTabContent" class="tab-content">
		<div class="row-fluid">
		<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("If you are unfamiliar with these labels and leave them empty they will be automatically created based on your entity name and the default configuration.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div>
		</div>
		<div id="tabs-1" class="tab-pane fade in active">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Menu Name","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-menu_name" id="ent-menu_name" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the menu name text. This string is the name to give menu items. Defaults to value of entity name","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Add New","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-add_new"  id="ent-add_new" type="text" placeholder="<?php _e("e.g. Add New","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e(" It defines the add new text. The default is Add New for both hierarchical and non-hierarchical entities.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" All Items","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-all_items"  id="ent-all_items" type="text" placeholder="<?php _e("e.g. All Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the all items text used in the menu. Default is the Name label.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" Add New Item","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-add_new_item"  id="ent-add_new_item" type="text" placeholder="<?php _e("e.g. Add New Product","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e(" It defines the add new item text. If the entity hierarchical the default is Add New Post otherwise it is Add New Page.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" Edit Item","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-edit_item"  id="ent-edit_item" type="text" placeholder="<?php _e("e.g. Edit Product","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the edit item text. If the entity hierarchical the default is Edit Post otherwise it is Edit Page.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" New Item","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-new_item"  id="ent-new_item" type="text" placeholder="<?php _e("e.g. New Product","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the new item text. If the entity hierarchical the default is New Post otherwise it is New Page.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" View Item","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-view_item"  id="ent-view_item" type="text" placeholder="<?php _e("e.g. View Product","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the view item text. If the entity hierarchical the default is View Post otherwise it is View Page.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" Search Items","wpas"); ?> </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-search_items"  id="ent-search_items" type="text" placeholder="<?php _e("e.g. Search Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the search items text. If the entity hierarchical the default is Search Post otherwise it is Search Page.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" Not Found","wpas"); ?> </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-not_found"  id="ent-not_found" type="text" placeholder="<?php _e("e.g. No Products Found","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the not found text. If the entity hierarchical the default is No Post found otherwise it is No Page found.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e(" Not Found in Trash","wpas"); ?> </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-not_found_in_trash"  id="ent-not_found_in_trash" type="text" placeholder="<?php _e("e.g. No Products found in Trash","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the not found in trash text. If the entity hierarchical the default is No posts found in Trash otherwise it is No pages found in Trash.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-parent_item_colon_div" style="display:none;">
		<label class="control-label span3"><?php _e(" Parent Item:","wpas"); ?> </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-parent_item_colon"  id="ent-parent_item_colon" type="text" placeholder="<?php _e("e.g. Parent Product:","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("It defines the parent text. This string is not used on non-hierarchical types. In hierarchical ones the default is Parent.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		</div>
		<div id="tabs-2" class="tab-pane fade">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Updated","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_upd" id="ent-msg_upd" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays when entity updated.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div id="ent-msg-cust-fields-div">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Custom Field Updated","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_cust_upd" id="ent-msg_cust_upd" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays when a custom field is updated. Entity must support custom fields for this message to be displayed.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Custom Field Deleted","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_cust_dlt" id="ent-msg_cust_dlt" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays when a custom field is deleted. Entity must support custom fields for this message to be displayed.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Default Updated","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_dflt_upd" id="ent-msg_dflt_upd" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays default entity updated message when no other entity message applicable.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Revision","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_revision" id="ent-msg_revision" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays entity restored to revision from message.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Published","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_published" id="ent-msg_published" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays when entity published.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Saved","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_saved" id="ent-msg_saved" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays when entity saved.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Pending Submitted","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_pending" id="ent-msg_pending" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Future Submitted","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_future" id="ent-msg_future" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displays when entity scheduled to be published in future.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Draft Updated","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-msg_draft" id="ent-msg_draft" type="text" placeholder="<?php _e("e.g. Products","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Displayes when entity draft updated.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		</div> 
		<div id="tabs-3" class="tab-pane fade">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Available for Public","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-publicly_viewable" id="ent-publicly_viewable" class="input-mini" >
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Whether this entity is intended to be used publicly either via the admin interface or by front-end users. -false- Entity is not intended to be used publicly and should generally be unavailable in the admin interface and on the front end unless explicitly planned for elsewhere. -true - Entity is intended for public use. This includes on the front end and in the admin interface.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>(<?php _e("default: True","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Has Archive","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-has_archive" id="ent-has_archive" class="input-mini">
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Enables entity archives. Will use entity name as archive slug by default.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a> (<?php _e("default: True","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Exclude From Search","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-exclude_from_search" id="ent-exclude_from_search" class="input-mini">
		<option selected="selected" value="0"><?php _e("False","wpas"); ?></option>
		<option value="1"><?php _e("True","wpas"); ?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("Whether to exclude objects of this entity from front end search results.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a> (<?php _e("default: False","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Publicly Queryable","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-publicly_queryable" id="ent-publicly_queryable" class="input-mini">
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("Whether queries can be performed on the front end in the URL address.","wpas"); ?>">
		<i class="icon-info-sign"></i></a> (<?php _e("default: True","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Capability Type","wpas"); ?> </label>
		<div class="controls span9">
		<select name="ent-capability_type" id="ent-capability_type" class="input-medium">
		<option selected="selected" value="post"><?php _e("Use Post / Page","wpas"); ?></option>
		<option value="entity_name"><?php _e("Use Entity Name","wpas"); ?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("The string to use to build the read, edit, and delete capabilities. The default is post. If you choose to use ENTITY NAME as type, then you MUST assign its capabilities to a role. Otherwise, you will not be able to access to the entity.","wpas"); ?>"> <i class="icon-info-sign"></i></a>(<?php _e("default: post or page","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Rewrite","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-rewrite" id="ent-rewrite" class="input-mini">
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Triggers the handling of rewrites for this entity. To prevent rewrites, set to false. Default: true and use entity name as slug","wpas"); ?>">
		<i class="icon-info-sign"></i></a> (<?php _e("default: True","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Custom Rewrite Slug","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-rewrite_slug" id="ent-rewrite_slug" type="text" placeholder="<?php _e("e.g. product","wpas"); ?>" value=""/>
		<a href="#" style="cursor: help;" title="<?php _e("Customize the permastruct slug. Max. 16 characters, can not contain capital letters or spaces. Defaults to the entity name.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>(<?php _e("default: Entity name","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Query Var","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-query-var" id="ent-query-var" class="input-mini">
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the query_var key for this entity. Default: true - sets to entity. false - Disables query_var key use. A post type cannot be loaded at /?{query_var}={single_post_slug} ","wpas"); ?>">
		<i class="icon-info-sign"></i></a> (<?php _e("default: True","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Can Export","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-can_export" id="ent-can_export" class="input-mini">
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Can this entity be exported.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a> (<?php _e("default: True","wpas"); ?>)
		</div>
		</div>

		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Supports","wpas"); ?></label>
		<div class="controls span9">
		<label class="checkbox"><input name="ent-supports_title" id="ent-supports_title" type="checkbox" value="1">&nbsp;<?php _e("Title","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the title entry meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_editor" id="ent-supports_editor" type="checkbox" value="1">&nbsp;<?php _e("Editor","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the input text area for editor meta box","wpas"); ?>" /><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_author" id="ent-supports_author" type="checkbox" value="1">&nbsp;<?php _e("Author","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the author meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_thumbnail" id="ent-supports_thumbnail" type="checkbox" value="1">&nbsp;<?php _e("Featured Image","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the featured image meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_excerpt" id="ent-supports_excerpt" type="checkbox" value="1">&nbsp;<?php _e("Excerpt","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds a customized excerpt meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_trackbacks" id="ent-supports_trackbacks" type="checkbox" value="1">&nbsp;<?php _e("Trackbacks","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the trackbacks meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_custom_fields" id="ent-supports_custom_fields" type="checkbox" value="1">&nbsp;<?php _e("Custom Fields","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the custom fields meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_revisions" id="ent-supports_revisions" type="checkbox" value="1">&nbsp;<?php _e("Revisions","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the revisions meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		<div id="ent-page-attributes-div" style="display:none;">
		<label class="checkbox"><input name="ent-supports_page_attributes" id="ent-supports_page_attributes" type="checkbox" value="1">&nbsp;<?php _e("Page attributes","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the page attribute meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		</div>
		<label class="checkbox"><input name="ent-supports_post_formats" id="ent-supports_post_formats" type="checkbox" value="1">&nbsp;<?php _e("Post Formats","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds the post format meta box","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Built-in Taxonomies","wpas"); ?></label>
		<div class="controls span9">
		<label class="checkbox"><input name="ent-taxonomy_category" id="ent-taxonomy_category" type="checkbox" value="1">&nbsp;<?php _e("Categories","wpas"); ?>&nbsp; <a href="#" style="cursor: help;" rel="tooltip" title="<?php _e("Enables Built-in Category taxonomy support for the entity. Categories link shows in the entity submenu when enabled.","wpas"); ?>"> <i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-taxonomy_post_tag" id="ent-taxonomy_post_tag" type="checkbox" value="1">&nbsp;<?php _e("Tags","wpas"); ?>&nbsp;
	<a href="#" style="cursor: help;" rel="tooltip" title="<?php _e("Enables Built-in Tags taxonomy support. Tags link shows in the entity submenu when enabled.","wpas"); ?>"> <i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		</div>
		</div> 
		<div id="tabs-4" class="tab-pane fade">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Show UI","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-show_ui" id="ent-show_ui" class="input-mini">
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Whether to generate a default UI for managing this entity in the admin area.","wpas"); ?>">
		<i class="icon-info-sign"></i></a> (<?php _e("default: True","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-show_in_menu_div">
		<label class="control-label span3"><?php _e("Show in Menu","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-show_in_menu" id="ent-show_in_menu" class="input-mini">
		<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
		<option value="0"><?php _e("False","wpas"); ?></option>
		<option value="2"><?php _e("Define Top Level Page","wpas"); ?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("Where to show the entity in the admin menu. Show UI must be true. False - do not display in the admin menu. True - display as a top level menu.","wpas"); ?>  "><i class="icon-info-sign"></i></a> (<?php _e("default: True","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-menu_icon_type_div">
		<label class="control-label span3"><?php _e("Icon Type","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-menu_icon_type" id="ent-menu_icon_type" class="input-medium">
		<option selected="selected" value=""><?php _e("Default","wpas"); ?></option>
		<option value="image"><?php _e("Image","wpas"); ?></option>
		<option value="fa"><?php _e("Font Awesome","wpas"); ?></option>
		<option value="dash"><?php _e("Dashicons","wpas"); ?></option>
		<option value="base64"><?php _e("Base64 Svg","wpas"); ?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the type of icon for your entity. You can assign an image, font-awesome, dashicon or base64-encoded SVG icons. If the default icon option is selected, then the pushpin icon is displayed. Dashicon icons can only be used for WordPress Version 3.8+.","wpas"); ?>  "><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-menu_icon_div" style="display:none;">
		<label class="control-label req span3"><?php _e("Menu-icon 16x16","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-menu_icon" id="ent-menu_icon" type="text" placeholder="<?php _e("enter the url of the image including http://","wpas");?>"/>
		<a href="#" style="cursor: help;" title="<?php _e("The icon which will be displayed on the menu bar of the entity","wpas"); ?>"><i class="icon-info-sign"></i></a>(<?php _e("default: Post icon","wpas"); ?>)
		</div>
		</div>
                <div class="control-group row-fluid" id="ent-menu_icon_fa_div" style="display:none;">
                <label class="control-label req span3"><?php _e("Icon Class","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-small" name="ent-menu_icon_fa" id="ent-menu_icon_fa" type="text" placeholder="<?php _e("exp; fa-rocket","wpas");?>"/>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the class of the icon which will be displayed on the menu bar of the entity","wpas"); ?>"><i class="icon-info-sign"></i></a>
		<a target="_blank" href="https://wpas.emdplugins.com/articles/supported-icons/">Cheatsheet</a>
                </div>
                </div>
                <div class="control-group row-fluid" id="ent-menu_icon_dash_div" style="display:none;">
                <label class="control-label req span3"><?php _e("Icon Class","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-small" name="ent-menu_icon_dash" id="ent-menu_icon_dash" type="text" placeholder="<?php _e("exp; camera","wpas");?>"/>
                <a href="#" style="cursor: help;" title="<?php _e("Enter the class of the icon  which will be displayed on the menu bar of the entity","wpas"); ?>"><i class="icon-info-sign"></i></a>
                <a target="_blank" href="https://wpas.emdplugins.com/articles/supported-icons/dashicons">Cheatsheet</a>
                </div>
                </div>															
                <div class="control-group row-fluid" id="ent-menu_icon_base64_div" style="display:none;">
                <label class="control-label req span3"><?php _e("Base64 SVG URI","wpas"); ?></label>
                <div class="controls span9">
		<textarea id="ent-menu_icon_base64" name="ent-menu_icon_base64" class="wpas-std-textarea"></textarea>
                <a href="#" style="cursor: help;" title="<?php _e("Enter a base64-encoded SVG using a data URI (Max 20X20 px allowed) which will be displayed on the menu bar of the entity","wpas"); ?>"><i class="icon-info-sign"></i></a>
                </div>
                </div>															
		<div class="control-group row-fluid" id="ent-menu_position_div"> 
		<label class="control-label span3"><?php _e(" Show Menu Below","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-menu_position" id="ent-menu_position" class="input-medium">
		<option value="5"><?php _e("Posts","wpas"); ?></option>
		<option value="10"><?php _e("Media","wpas"); ?></option>
		<option value="15"><?php _e("Links","wpas"); ?></option>
		<option value="20"><?php _e("Pages","wpas"); ?></option>
		<option value="25"><?php _e("Comments","wpas"); ?></option>
		<option value="60"><?php _e("First Seperator","wpas"); ?></option>
		<option value="65"><?php _e("Plugins","wpas"); ?></option>
		<option value="70"><?php _e("Users","wpas"); ?></option>
		<option value="75"><?php _e("Tools","wpas"); ?></option>
		<option value="80"><?php _e("Settings","wpas"); ?></option>
		<option value="100"><?php _e("Second Seperator","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("The position in the menu order the post type should appear.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-display_idx_div">
		<label class="control-label span3"><?php _e("Entity Display Index","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-mini" name="ent-display_idx" id="ent-display_idx" type="text" placeholder="0" />
		<a href="#" style="cursor: help;" title="<?php _e("Sets the position of the entity relative to the other entities in the app. Numbers only. Enter 0 for alphabetical order.","wpas"); ?>"><i class="icon-info-sign"></i></a>(<?php _e("default: 0","wpas"); ?>)
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-top_level_page_div" style="display:none;">
		<label class="control-label span3"><?php _e(" Top level page","wpas"); ?></label>
		<div class="controls span9">
		<input name="ent-top_level_page" id="ent-top_level_page" size="5" type="text" placeholder="<?php _e(" e.g. &#39;plugins.php&#39;","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("If an existing top level page such as 'tools.php' or 'edit.php?post_type=page', the entity will be placed as a sub menu of that.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div> 
		</div> 
		<div id="tabs-5" class="tab-pane fade">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Default Group Title","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-default_grp_title" id="ent-default_grp_title" type="text" placeholder="<?php _e("e.g. Product Info","wpas"); ?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Sets the default group title if there is no entity layout defined.","wpas"); ?>"> <i class="icon-info-sign"></i></a>
		</div>
		</div> 
		</div>
		</div> <!-- end if tab5-->
		
		<div id="tabs-6" class="tab-pane fade">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><input name="ent-supports_comments" id="ent-supports_comments" type="checkbox" value="1">&nbsp;<?php _e("Enable Entity Comments","wpas"); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Adds custom emd-comment module to your entity. In emd-comments, comments made displayed under entity menu and not mixed with built-in post or page comments.","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-com_type_div" name="ent-com_type_div" style="display:none;">
		<label class="control-label span3"><?php _e("Type","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-com_type" id="ent-com_type" class="input-medium">
		<option value="wp"><?php _e("Built-in","wpas"); ?></option>
		<option value="custom"><?php _e("Custom","wpas");?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("You can choose either built-in or custom commenting funtionality. Built-in commenting accumulates comments in Comments menu. Custom commenting creates its own menu item under the menu of your entity and accumulates the recieved comments there.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div id="ent-com_detail_div" name="ent-com_detail_div" style="display:none;">
		<div class="control-group row-fluid">
		<label class="control-label span3 req"><?php _e("Display Type","wpas"); ?></label>
		<div class="controls span9">
		<select name="ent-com_display_type" id="ent-com_display_type" class="input-medium">
		<option value=""><?php _e("Please Select","wpas"); ?></option>
		<option value="backend"><?php _e("Backend Only","wpas"); ?></option>
		<option value="shc"><?php _e("Use Shortcode","wpas");?></option>
		<option value="noshc"><?php _e("Use Theme Template","wpas");?></option></select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets how custom comments are displayed. Use Shortcode - you can use !#shortcode[emd_comments]# code in the single layout view of your entity to display custom comments. Custom comment html, css, and javascript files will be used to display comments. Backend Only - comments are displayed in the admin edit screen of your entity. Use Theme Template - custom comments are displayed using the template(comments.php) your theme provides.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3 req"><?php _e("Name","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-com_name" id="ent-com_name" type="text" placeholder="<?php _e("e.g. customer_response","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Create a unique name for your comment subentity. Only alphanumeric and underscore characters allowed.i Limited to 20 characters.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3 req"><?php _e("Single Label","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-com_single_label" id="ent-com_single_label" type="text" placeholder="<?php _e("e.g. Customer Response","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Define a singular name for your comment subentiy.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3 req"><?php _e("Plural Label","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-com_plural_label" id="ent-com_plural_label" type="text" placeholder="<?php _e("e.g. Customer Responses","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Define a plural name for your comment subentiy.","wpas"); ?> ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Enable Trash","wpas"); ?></label>
		<div class="controls span9">
		<label class="checkbox"><input name="ent-com_enable_trash" id="ent-com_enable_trash" type="checkbox" value="1" checked>
		<a href="#" style="cursor: help;" title="<?php _e("When enabled it allows to move your comment subentity records to trash.","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Enable Spam","wpas"); ?></label>
		<div class="controls span9">
		<label class="checkbox"><input name="ent-com_enable_spam" id="ent-com_enable_spam" type="checkbox" value="1" checked>
		<a href="#" style="cursor: help;" title="<?php _e("When enabled it allows to move your comment subentity records to spam.","wpas"); ?>"><i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		</div><!-- end of detail div-->
		</div>
		</div><!-- end of tab6-->

		</div>
		</div>
		<div class="control-group row-fluid">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-entity" type="submit" value="Save"><i class="icon-save"></i>
		<?php _e("Save","wpas"); ?></button>
		</div>

	</fieldset>
	</form>
<?php
}
function wpas_view_entity($ent,$ent_id)
{
	$style = "";
	if(in_array($ent['ent-name'],Array('post','page')))
	{ 
		$style = 'style="display:none;"';
	}
	$ret = '<div class="well form-horizontal">
		<div class="row-fluid">
		<button class="btn  btn-danger pull-left" id="cancel" name="cancel" type="button">
		<i class="icon-off"></i>' . __("Close","wpas") . '</button>
		<div class="entity">'; 
	$ret .= '<button class="btn  btn-primary pull-right" id="edit-entity" name="Edit" type="submit" href="#' . esc_attr($ent_id) . '" ' . $style . '>
		<i class="icon-edit"></i>' . __("Edit","wpas") . '</button>';
	$ret .= '</div>
		</div>
		<fieldset>
		<div class="control-group row-fluid">
		<label class="control-label span3">' . __("Name","wpas") . '</label>
		<div class="controls span9"><span id="ent-name" class="input-xlarge uneditable-input">' . esc_html($ent['ent-name']) . '</span>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3">' . __("Plural","wpas") . '</label>
		<div class="controls span9"><span id="ent-label" class="input-xlarge uneditable-input">' . esc_html($ent['ent-label']) . '</span>
		</div>
		</div>
		</fieldset>
		</div>';
	return $ret;
}
?>
