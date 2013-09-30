<?php
function wpas_add_entity_form()
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#ent-rewrite').click(function () {
		if(jQuery(this).find('option:selected').text() == 'False')
		{
			jQuery('#ent-rewrite_slug').attr('disabled',true);
		}
		if(jQuery(this).find('option:selected').text() == 'True')
		{
			jQuery('#ent-rewrite_slug').removeAttr('disabled');
		}
	});
	jQuery('#ent-hierarchical').click(function() {
		if(jQuery(this).find('option:selected').text() == 'True')
		{
			jQuery('#ent-page-attributes-div').show();
			jQuery('#ent-parent_item_colon_div').show();
		}
		else
		{
			jQuery('#ent-page-attributes-div').hide();
			jQuery('#ent-parent_item_colon_div').hide();
		}
	});
	jQuery('#ent-has_user_relationship').click(function() {
		if(jQuery(this).attr('checked'))
		{
			jQuery('#ent-limit_user_relationship_role_div').show();
		}
		else
		{
			jQuery('#ent-limit_user_relationship_role_div').hide();
		}
	});
	jQuery('#ent-advanced-option').click(function() {
		if(jQuery(this).attr('checked'))
		{
			jQuery('#tabs').show();
		}
		else
		{
			jQuery('#tabs').hide();
		}
	});
	jQuery('#ent-show_ui').click(function() {
		if(jQuery(this).find('option:selected').text() == 'False')
		{
			jQuery('#ent-show_in_menu_div').hide();
			jQuery('#ent-menu_icon_div').hide();
			jQuery('#ent-menu_icon_32_div').hide();
			jQuery('#ent-menu_position_div').hide();
			jQuery('#ent-top_level_page_div').hide();
		}
		else
		{
			jQuery('#ent-show_in_menu_div').show();
			jQuery('#ent-menu_icon_div').show();
			jQuery('#ent-menu_icon_32_div').show();
			jQuery('#ent-menu_position_div').show();
			jQuery('#ent-top_level_page_div').show();
		}
	});
	jQuery('#ent-show_in_menu').click(function() {
		var menu_selected = jQuery(this).find('option:selected').text();

		if(menu_selected == 'False')
		{
			jQuery('#ent-menu_icon_div').hide();
			jQuery('#ent-menu_icon_32_div').hide();
			jQuery('#ent-menu_position_div').hide();
			jQuery('#ent-top_level_page_div').hide();
		}
		else if(menu_selected == 'Define Top Level Page')
		{
			jQuery('#ent-menu_icon_div').show();
			jQuery('#ent-menu_icon_32_div').show();
			jQuery('#ent-menu_position_div').hide();
			jQuery('#ent-top_level_page_div').show();
		}
		else
		{
			jQuery('#ent-menu_icon_div').show();
			jQuery('#ent-menu_icon_32_div').show();
			jQuery('#ent-menu_position_div').show();
			jQuery('#ent-top_level_page_div').hide();
		}
	});

});
</script>
<form action="" method="post" id="entity-form" class="form-horizontal">
			<input type="hidden" value="" name="ent" id="ent">
	<fieldset>
		<div class="well">
		<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="The entity is a person, object, place or event for which data is collected."> HELP</a></div></div>
		<div class="field-container">
		<div class="control-group row-fluid">
		<label class="control-label span3">Name</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-name" id="ent-name" type="text" placeholder="e.g. product" value="" >
		<a href="#" style="cursor: help;" title="General name for the entity, usually singular max. 16 characters, can not contain capital letters,reserved words,dashes or spaces.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"> Plural Label</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-label" id="ent-label" type="text" value="" placeholder="e.g. Products"/>
		<a href="#" style="cursor: help;" title="A plural descriptive name for the entity marked for translation.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3">Singular Label </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-singular-label" id="ent-singular-label" type="text" value="" placeholder="e.g. Product"/>
		<a href="#" style="cursor: help;" title="It is the name for one object of this entity.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Hierarchical </label>
		<div class="controls span9">
		<select name="ent-hierarchical" id="ent-hierarchical" class="input-mini">
		<option selected="selected" value="0">False</option>
		<option value="1">True</option></select>
		<a href="#" style="cursor: help;" title="Whether the entity is hierarchical (e.g. page). Allows Parent to be specified.">
		<i class="icon-info-sign"></i></a> (default: False)
		</div>
		</div>

		<div class="control-group row-fluid">
		<label class="control-label span3">Description</label>
		<div class="controls span9">
		<textarea class="input-xlarge" id="ent-desc" name="ent-desc"></textarea>
		<a href="#" style="cursor: help;" title="A short descriptive summary of what the entity is.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9"><label class="checkbox">Show Advanced Options
		<input name="ent-advanced-option" id="ent-advanced-option" type="checkbox" value="1"/>
		</label>
		</div>
		</div>
		</div>
		</div><!--well-->
		<div id="tabs" style="display:none;">
		<ul id="myTab" class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tabs-1">Label Options</a></li>
		<li><a data-toggle="tab" href="#tabs-2">Options</a></li>
		<li><a data-toggle="tab" href="#tabs-3">Menu Options</a></li>
		<li><a data-toggle="tab" href="#tabs-4">Display Options</a></li>
		<li><a data-toggle="tab" href="#tabs-5">User Relationship</a></li>
		</ul>
		<div id="myTabContent" class="tab-content">
		<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="If you are unfamiliar with these labels and leave them empty they will be automatically created based on your entity name and the default configuration."> HELP</a></div></div>
		<div id="tabs-1" class="tab-pane fade in active">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3" >Menu Name</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-menu_name" id="ent-menu_name" type="text" placeholder="e.g. Products" value="" />
		<a href="#" style="cursor: help;" title="It defines the menu name text. This string is the name to give menu items. Defaults to value of entity name ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Add New</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-add_new"  id="ent-add_new" type="text" placeholder="e.g. Add New" value="" />
		<a href="#" style="cursor: help;" title=" It defines the add new text. The default is Add New for both hierarchical and non-hierarchical entities.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > All Items</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-all_items"  id="ent-all_items" type="text" placeholder="e.g. All Products" value="" />
		<a href="#" style="cursor: help;" title=" It defines the all items text used in the menu. Default is the Name label."><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > Add New Item</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-add_new_item"  id="ent-add_new_item" type="text" placeholder="e.g. Add New Product" value="" />
		<a href="#" style="cursor: help;" title=" It defines the add new item text. If the entity hierarchical the default is Add New Post otherwise it is Add New Page.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > Edit Item</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-edit_item"  id="ent-edit_item" type="text" placeholder="e.g. Edit Product" value="" />
		<a href="#" style="cursor: help;" title="It defines the edit item text. If the entity hierarchical the default is Edit Post otherwise it is Edit Page.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > New Item</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-new_item"  id="ent-new_item" type="text" placeholder="e.g. New Product" value="" />
		<a href="#" style="cursor: help;" title="It defines the new item text. If the entity hierarchical the default is New Post otherwise it is New Page.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > View Item </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-view_item"  id="ent-view_item" type="text" placeholder="e.g. View Product" value="" />
		<a href="#" style="cursor: help;" title="It defines the view item text. If the entity hierarchical the default is View Post otherwise it is View Page.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > Search Items </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-search_items"  id="ent-search_items" type="text" placeholder="e.g. Search Products" value="" />
		<a href="#" style="cursor: help;" title="It defines the search items text. If the entity hierarchical the default is Search Post otherwise it is Search Page.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > Not Found </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-not_found"  id="ent-not_found" type="text" placeholder="e.g. No Products Found" value="" />
		<a href="#" style="cursor: help;" title="It defines the not found text. If the entity hierarchical the default is No Post found otherwise it is No Page found.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" > Not Found in Trash </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-not_found_in_trash"  id="ent-not_found_in_trash" type="text" placeholder="e.g. No Products found in Trash" value="" />
		<a href="#" style="cursor: help;" title="It defines the not found in trash text. If the entity hierarchical the default is No posts found in Trash otherwise it is No pages found in Trash. ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-parent_item_colon_div" style="display:none;">
		<label class="control-label span3" > Parent Item: </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-parent_item_colon"  id="ent-parent_item_colon" type="text" placeholder="e.g. Parent Product:" value="" />
		<a href="#" style="cursor: help;" title="It defines the parent text. This string is not used on non-hierarchical types. In hierarchical ones the default is Parent.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		</div>
		<div id="tabs-2" class="tab-pane fade">
		<div class="field_groups">
		<div class="control-group row-fluid">
		<label class="control-label span3" >Available for Public</label>
		<div class="controls span9">
		<select name="ent-publicly_viewable" id="ent-publicly_viewable" class="input-mini" >
		<option selected="selected" value="1">True</option>
		<option value="0">False</option>
		</select>
		<a href="#" style="cursor: help;" title="Whether this entity is intended to be used publicly either via the admin interface or by front-end users. -false- Entity is not intended to be used publicly and should generally be unavailable in the admin interface and on the front end unless explicitly planned for elsewhere. -true - Entity is intended for public use. This includes on the front end and in the admin interface.">
		<i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Has Archive</label>
		<div class="controls span9">
		<select name="ent-has_archive" id="ent-has_archive" class="input-mini">
		<option selected="selected" value="1">True</option>
		<option value="0">False</option>
		</select>
		<a href="#" style="cursor: help;" title="Enables entity archives. Will use entity name as archive slug by default. ">
		<i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Exclude From Search</label>
		<div class="controls span9">
		<select name="ent-exclude_from_search" id="ent-exclude_from_search" class="input-mini">
		<option selected="selected" value="0">False</option>
		<option value="1">True</option></select>
		<a href="#" style="cursor: help;" title="Whether to exclude objects of this entity from front end search results. ">
		<i class="icon-info-sign"></i></a> (default: False)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Publicly Queryable</label>
		<div class="controls span9">
		<select name="ent-publicly_queryable" id="ent-publicly_queryable" class="input-mini">
		<option selected="selected" value="1">True</option>
		<option value="0">False</option></select>
		<a href="#" style="cursor: help;" title="Whether queries can be performed on the front end in the URL address. ">
		<i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Capability Type </label>
		<div class="controls span9">
		<select name="ent-capability_type" id="ent-capability_type" class="input-medium">
		<option selected="selected" value="post">Use Post / Page</option>
		<option value="entity_name">Use Entity Name</option></select>
		<a href="#" style="cursor: help;" title="The string to use to build the read, edit, and delete capabilities. The default is post. If you choose to use ENTITY NAME as type, then you MUST assign its capabilities to a role. Otherwise, you will not be able to access to the entity."> <i class="icon-info-sign"></i></a>(default: post or page)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Rewrite</label>
		<div class="controls span9">
		<select name="ent-rewrite" id="ent-rewrite" class="input-mini">
		<option selected="selected" value="1">True</option>
		<option value="0">False</option>
		</select>
		<a href="#" style="cursor: help;" title="Triggers the handling of rewrites for this entity. To prevent rewrites, set to false. Default: true and use entity name as slug ">
		<i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Custom Rewrite Slug</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-rewrite_slug" id="ent-rewrite_slug" type="text" placeholder="e.g. product" value=""/>
		<a href="#" style="cursor: help;" title="Customize the permastruct slug. Max. 16 characters, can not contain capital letters or spaces. Defaults to the entity name. ">
		<i class="icon-info-sign"></i></a>(default: Entity name)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Query Var</label>
		<div class="controls span9">
		<select name="ent-query-var" id="ent-query-var" class="input-mini">
		<option selected="selected" value="1">True</option>
		<option value="0">False</option>
		</select>
		<a href="#" style="cursor: help;" title="Sets the query_var key for this entity. Default: true - set to $post_type. false - Disables query_var key use. A post type cannot be loaded at /?{query_var}={single_post_slug} ">
		<i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Can Export</label>
		<div class="controls span9">
		<select name="ent-can_export" id="ent-can_export" class="input-mini">
		<option selected="selected" value="1">True</option>
		<option value="0">False</option>
		</select>
		<a href="#" style="cursor: help;" title="Can this entity be exported. ">
		<i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>

		<div class="control-group row-fluid">
		<label class="control-label span3" > Supports</label>
		<div class="controls span9">
		<label class="checkbox"><input  name="ent-supports_title" id="ent-supports_title" type="checkbox" value="1"  checked />&nbsp;Title
		<a href="#" style="cursor: help;" title="Adds the title entry meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_editor" id="ent-supports_editor" type="checkbox" value="1"  checked />&nbsp;Editor
		<a href="#" style="cursor: help;" title="Adds the input text area for editor meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_author" id="ent-supports_author" type="checkbox" value="1" />&nbsp;Author
		<a href="#" style="cursor: help;" title="Adds the author meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_thumbnail" id="ent-supports_thumbnail" type="checkbox" value="1" />&nbsp;Featured Image
		<a href="#" style="cursor: help;" title="Adds the featured image meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_excerpt" id="ent-supports_excerpt" type="checkbox" value="1" />&nbsp;Excerpt
		<a href="#" style="cursor: help;" title="Adds a customized excerpt meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_comments" id="ent-supports_comments" type="checkbox" value="1" />&nbsp;Comments
		<a href="#" style="cursor: help;" title="Adds the comments meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_trackbacks" id="ent-supports_trackbacks" type="checkbox" value="1" />&nbsp;Trackbacks
		<a href="#" style="cursor: help;" title="Adds the trackbacks meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_custom_fields" id="ent-supports_custom_fields" type="checkbox" value="1" />&nbsp;Custom Fields
		<a href="#" style="cursor: help;" title="Adds the custom fields meta box"><i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-supports_revisions" id="ent-supports_revisions" type="checkbox" value="1" />&nbsp;Revisions
		<a href="#" style="cursor: help;" title="Adds the revisions meta box"><i class="icon-info-sign"></i></a></label>
		<div id="ent-page-attributes-div" style="display:none;">
		<label class="checkbox"><input name="ent-supports_page_attributes" id="ent-supports_page_attributes" type="checkbox" value="1" />&nbsp;Page attributes
		<a href="#" style="cursor: help;" title="Adds the page attribute meta box"><i class="icon-info-sign"></i></a></label>
		</div>
		<label class="checkbox"><input name="ent-supports_post_formats" id="ent-supports_post_formats" type="checkbox" value="1" />&nbsp;Post Formats
		<a href="#" style="cursor: help;" title="Adds the post format meta box"><i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Built-in Taxonomies </label>
		<div class="controls span9">
		<label class="checkbox"><input name="ent-taxonomy_category" id="ent-taxonomy_category" type="checkbox" value="1" />&nbsp;Categories&nbsp; <a href="#" style="cursor: help;" rel="tooltip" title="Enables Built-in Category taxonomy support for the entity. Categories link shows in the entity submenu when enabled."> <i class="icon-info-sign"></i></a></label>
		<label class="checkbox"><input name="ent-taxonomy_post_tag" id="ent-taxonomy_post_tag" type="checkbox" value="1" />&nbsp;Tags&nbsp;
	<a href="#" style="cursor: help;" rel="tooltip" title="Enables Built-in Tags taxonomy support. Tags link shows in the entity submenu when enabled."> <i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		</div>
		</div>
		<div id="tabs-3" class="tab-pane fade">
		<div class="tab-grp-data">
		<div class="control-group row-fluid">
		<label class="control-label span3" >Show UI</label>
		<div class="controls span9">
		<select name="ent-show_ui" id="ent-show_ui" class="input-mini">
		<option selected="selected" value="1">True</option>
		<option value="0">False</option>
		</select>
		<a href="#" style="cursor: help;" title="Whether to generate a default UI for managing this entity in the admin area.">
		<i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-show_in_menu_div">
		<label class="control-label span3" >Show in Menu</label>
		<div class="controls span9">
		<select name="ent-show_in_menu" id="ent-show_in_menu" class="input-mini">
		<option selected="selected" value="1">True</option>
		<option value="0">False</option>
		<option value="2">Define Top Level Page</option></select>
		<a href="#" style="cursor: help;" title="Where to show the entity in the admin menu. Show UI must be true. \'false\' - do not display in the admin menu.\'true\' - display as a top level menu.  "><i class="icon-info-sign"></i></a> (default: True)
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-menu_icon_div">
		<label class="control-label span3" >Menu-icon 16x16 </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-menu_icon" id="ent-menu_icon" type="text" placeholder="enter the url of the image including http://" />
		<a href="#" style="cursor: help;" title="The icon which will be displayed on the menu bar of the entity"><i class="icon-info-sign"></i></a>(default: Post icon)
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-menu_icon_32_div">
		<label class="control-label span3" >Menu-icon 32x32 </label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-menu_icon_32" id="ent-menu_icon_32" type="text" placeholder="enter the url of the image including http://" />
		<a href="#" style="cursor: help;" title="The icon which will be displayed on the edit and list screens of the entity"><i class="icon-info-sign"></i></a>(default: Post icon)
		</div>
		</div>															
		<div class="control-group row-fluid" id="ent-menu_position_div"> 
		<label class="control-label span3" > Show Menu Below</label>
		<div class="controls span9">
		<select name="ent-menu_position" id="ent-menu_position" class="input-medium">
		<option value="5">Posts</option>
		<option value="10">Media</option>
		<option value="15">Links</option>
		<option value="20">Pages</option>
		<option value="25">Comments</option>
		<option value="60">First Seperator</option>
		<option value="65">Plugins</option>
		<option value="70">Users</option>
		<option value="75">Tools</option>
		<option value="80">Settings</option>
		<option value="100">Second Seperator</option>
		</select>
		<a href="#" style="cursor: help;" title="The position in the menu order the post type should appear.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-display_idx_div">
		<label class="control-label span3" >Entity Display Index</label>
		<div class="controls span9">
		<input class="input-mini" name="ent-display_idx" id="ent-display_idx" type="text" placeholder="0" />
		<a href="#" style="cursor: help;" title="Sets the position of the entity relative to the other entities in the app. Numbers only. Enter 0 for alphabetical order."><i class="icon-info-sign"></i></a>(default: 0)
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-top_level_page_div" style="display:none;">
		<label class="control-label span3" > Top level page</label>
		<div class="controls span9">
		<input name="ent-top_level_page" id="ent-top_level_page" size="5" type="text" placeholder=" e.g. &#39;plugins.php&#39;" value="" />
		<a href="#" style="cursor: help;" title="If an existing top level page such as 'tools.php' or 'edit.php?post_type=page', the entity will be placed as a sub menu of that. ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		</div>
		<div id="tabs-4" class="tab-pane fade">
		<div class="tab-grp-data">
		<div class="control-group row-fluid">
		<label class="control-label span3">Default Group Title</label>
		<div class="controls span9">
		<input class="input-xlarge" name="ent-default_grp_title" id="ent-default_grp_title" type="text" placeholder="e.g. Product Info" value="" >
		<a href="#" style="cursor: help;" title="Sets the default group title if there is no entity layout defined."> <i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		</div>
		<div id="tabs-5" class="tab-pane fade">
		<div class="tab-grp-data">
		<div class="control-group row-fluid">
		<div class="controls">
		<label class="checkbox"><input name="ent-has_user_relationship" id="ent-has_user_relationship" type="checkbox" value="1"/>&nbsp;Create relationship with Users
		<a href="#" style="cursor: help;" title="Creates a relationship with users of the application such as Assign to relationship. You can also limit the relationship using user roles. ">
		<i class="icon-info-sign"></i></a></label>
		</div>
		</div>
		<div class="control-group row-fluid" id="ent-limit_user_relationship_role_div" style="display: none;">
		<label class="control-label span3">User role relationship</label>
		<div class="controls span9">
		<select name="ent-limit_user_relationship_role" id="ent-limit_user_relationship_role">
		<option selected="selected" value="false">Do not limit</option>
		<option value="editor">Only Editors can be related</option>
		<option value="author">Only Author can be related</option>
		<option value="contributor">Only Contributor can be related</option>
		<option value="subscriber">Only Subscriber can be related</option>
		<option value="administrator">Only Administrator can be related</option>
		</select>
		<a href="#" title="Super Admin - Someone with access to the blog network administration features controlling the entire network (See Create a Network). Administrator - Somebody who has access to all the administration features. Editor - Somebody who can publish and manage posts and pages as well as manage other users\' posts, etc. Author - Somebody who can publish and manage their own posts. Contributor - Somebody who can write and manage their posts but not publish them. Subscriber - Somebody who can only manage their profile." style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		</div>

		</div>
		</div>
		<div class="control-group row-fluid">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-entity" type="submit" value="Save"><i class="icon-save"></i>
		Save</button>
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
		<i class="icon-off"></i>Close</button>
		<div class="entity">'; 
	$ret .= '<button class="btn  btn-primary pull-right" id="edit-entity" name="Edit" type="submit" href="#' . esc_attr($ent_id) . '" ' . $style . '>
		<i class="icon-edit"></i>Edit</button>';
	$ret .= '</div>
		</div>
		<fieldset>
		<div class="control-group row-fluid">
		<label class="control-label span3">Name </label>
		<div class="controls span9"><span id="ent-name" class="input-xlarge uneditable-input">' . esc_html($ent['ent-name']) . '</span>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3">Plural Label</label>
		<div class="controls span9"><span id="ent-label" class="input-xlarge uneditable-input">' . esc_html($ent['ent-label']) . '</span>
		</div>
		</div>
		</fieldset>
		</div>';
	return $ret;
}
?>
