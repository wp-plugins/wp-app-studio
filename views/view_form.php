<?php
function wpas_add_shortcode_form($app_id)
{
	?>
<script type="text/javascript">
jQuery(document).ready(function($) {
        $('#shc-view_type').click(function() {
		app_id = jQuery('input#app').val();
		var selected_type = $(this).find('option:selected').val();
		switch (selected_type) {
			case 'std':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').show();
				$('#shctabs-2-li').show();
				$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id}, function(response)
				{
					$('#add-shortcode-div #shc-attach').html(response);
					$('#shc-attach_div').show();
				});
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
				break;
			case 'search':
				$('#shc-theme_type_div').hide();
				$('#shc-sc_pagenav_div').show();
				$('#shctabs-2-li').show();
				$.get(ajaxurl,{action:'wpas_get_search_forms',app_id:app_id}, function(response)
				{
					$('#shc-attach_form').html(response);
					$('#shc-attach_form_div').show();
				});
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_div').hide();
				break;
			case 'single':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').hide();
				$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,subtype:selected_type}, function(response)
				{
					$('#add-shortcode-div #shc-attach').html(response);
					$('#shc-attach_div').show();
				});
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
				$('#shctabs-2-li').hide();
				break;	
			case 'archive':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').hide();
				$('#shctabs-2-li').hide();
				$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,subtype:selected_type}, function(response)
				{
					$('#add-shortcode-div #shc-attach').html(response);
					$('#shc-attach_div').show();
				});
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
				break;
			case 'tax':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').hide();
				$('#shctabs-2-li').hide();
				$.get(ajaxurl,{action:'wpas_get_entities',type:'tax',app_id:app_id,subtype:selected_type}, function(response)
				{
					$('#add-shortcode-div #shc-attach_tax').html(response);
					$('#shc-attach_tax_div').show();
				});
				$('#shc-attach_div').hide();
				$('#shc-attach_form_div').hide();
				break;
			//$('#shc-sc_pagenav_div').hide();
			default:
				$('#shc-theme_type_div').hide();
				$('#shc-sc_pagenav_div').hide();
				$('#shc-attach_form_div').hide();
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_div').hide();
				$('#shctabs-2-li').show();
				break;
		}
	});
        $('#shc-attach_tax').click(function() {
		var app_id = jQuery('input#app').val();
		var tax_id = $('#shc-attach_tax').val();
		if(tax_id != '')
		{
			$.get(ajaxurl,{action:'wpas_get_tax_values',app_id:app_id,tax_id:tax_id}, function(response)
			{
				$('#add-shortcode-div #shc-attach_taxterm').html(response);
			});
			$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,tax_id:tax_id}, function(response)
                        {
                                $('#add-shortcode-div #shc-attach').html(response);
                                $('#shc-attach_div').show();
                        });
		}
	});
});			
</script>
		<form action="" method="post" id="shortcode-form" class="form-horizontal">
		<input type="hidden" id="app" name="app" value="">
		<input type="hidden" value="" name="shc" id="shc">
		<fieldset>
		<div class="well">
		<div class="row-fluid">
		<div class="alert alert-info pull-right">
		<i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Views help you display content where and how you wanted on the frontend.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Name","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-label" id="shc-label" type="text" placeholder="<?php _e("e.g. sc_products","wpas");?>">
		<a href="#" title="<?php _e("General name for the view. By enclosing the view name in square brackets in a page, post or a text widget, you can display the content returned by the view shortcode's query. The view name should be all lowercase and use all letters, but numbers and underscores (not dashes!) should work fine too. Max 30 characters allowed. If the shortcode is used in a text widget or a page and the content has multiple pages, paginated navigation links are displayed. You can filter the content by generating a shortcode filter using the WPAS toolbar button on a page or post.","wpas"); ?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Type","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-view_type" id="shc-view_type" class="input-medium">
		<option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
		<option value="std"><?php _e("Standard","wpas"); ?></option>
		<option value="search"><?php _e("Search","wpas"); ?></option>
		<option value="single"><?php _e("Single","wpas"); ?></option>
		<option value="archive"><?php _e("Archive","wpas"); ?></option>
		<option value="tax"><?php _e("Taxonomy","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the type of view to be created.You can create search, standard, single, archive, and taxonomy views. All views except single view produce a list of content. The single views display individual entity content. Each entity can only have one single view. Search views are for displaying search results and must be attached to at least one search form. Taxonomy views display the content of a taxonomy. Each taxonomy can have only one taxonomy view. Archive views display a list of entity content. Each entity can have only one archive view. In addition, you can sort, filter the archived content of views using the filter tab. Single views diplay only one record so can not be filtered or sorted. If you like to display multiple versions of the archived content of an entity, you can create a standard view. There is no limitation of the number of standard views that can be attached to an entity. Standard views can be put on a page or post using the wpas toolbar button.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-attach_form_div" name="shc-attach_form_div" style="display:none;">
		<label class="control-label req span3"><?php _e("Attach to Form","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach_form" name="shc-attach_form">
		</select><a href="#" style="cursor: help;" title="<?php _e("Search forms must be attached to an already created view. A search view defines the format of how search results will be displayed.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div id="shc-attach_tax_div" name="shc-attach_tax_div" style="display:none;">
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Attach to Taxonomy","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach_tax" name="shc-attach_tax">
		</select><a href="#" style="cursor: help;" title="<?php _e("Taxonomy views must be attached to a predefined taxonomy.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
                <div class="control-group row-fluid">
                <label class="control-label span3"><?php _e("Attach to Term","wpas"); ?></label>
                <div class="controls span9">
                <select id="shc-attach_taxterm" name="shc-attach_taxterm">
		<option value=''><?php _e("Apply to all","wpas"); ?></option>
                </select><a href="#" style="cursor: help;" title="<?php _e("Taxonomy views can be attached to a predefined taxonomy term.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		</div>
		<div class="control-group row-fluid" id="shc-attach_div" name="shc-attach_div" style="display:none;">
		<label class="control-label req span3"><?php _e("Attach to Entity","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach" name="shc-attach">
		</select><a href="#" title="<?php _e("Views must be attached to a predefined entity. The attached entity's content is returned by the view after query filters applied.","wpas"); ?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
                </div>
		<div id="view-tabs">
        <ul id="shcTab" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#shctabs-1"><?php _e("Display Options","wpas"); ?></a></li>
        <li id="shctabs-2-li"><a data-toggle="tab" href="#shctabs-2"><?php _e("Filters","wpas"); ?></a></li>
        <li id="shctabs-3-li"><a data-toggle="tab" href="#shctabs-3"><?php _e("Messages","wpas"); ?></a></li>
        </ul>
        <div id="ShcTabContent" class="tab-content">
        <div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Display Options tab configures how the view will be displayed on the frontend. Filters tab defines how the content will be returned by setting sort order, number of records etc. Messages tab helps you define the messages to be displayed to users when the view's content is requested.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
	<div id="shctabs-1" class="tab-pane fade in active">
		<div id="shc-theme_type_div" name="shc-theme_type_div" style="display:none;">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Template","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-theme_type" id="shc-theme_type" class="input-medium">
		<option value="Na">None</option>
		<option value="Bootstrap">Twitter's Bootstrap</option>
		<option value="Pure">jQuery UI</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the frontend framework which will be used to configure the overall look and feel of the view. If you pick jQuery UI, you can choose your theme from App's Settings under the theme tab. Default is Twitter Bootstrap.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Enable Font Awesome","wpas");?>
		<input name="shc-font_awesome" id="shc-font_awesome" type="checkbox" value="1" checked/>
		<a href="#" style="cursor: help;" title="<?php _e("Enables Font Awesome webfont for radios, checkboxes and other icons. Can not be disabled for the Bootstrap framework.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-sc_pagenav_div">
                <label class="control-label span3"></label>
                <label class="checkbox span9"><?php _e("Enable paged navigation.","wpas"); ?>
                <input type="checkbox" value="1" id="shc-sc_pagenav" name="shc-sc_pagenav">
                <a title="<?php _e("Enables pagination links.","wpas"); ?>" style="cursor: help;" href="#">
                <i class="icon-info-sign"></i></a>
               </label>
                </div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Header","wpas"); ?></label>
		<div class="controls span9">
		<?php display_tinymce('shc-layout_header','',1); ?>
		<a href="#" style="cursor: help;" title="<?php _e("It defines the header content of the view. The header content is static and displayed on the top section of your view's content.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Layout","wpas"); ?></label>
		<div class="controls span9">
<?php
		display_tinymce('shc-sc_layout','',1,1); 
?>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the layout of a single record of your view. You can also edit the source code, add entity attributes, taxonomies.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Css","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="input-xlarge" id="shc-sc_css" name="shc-sc_css" class="tinymce"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("The custom css code to be used when displaying the content. It is handy when you added custom classes in the layout editor and want to add css class definitions. You can leave this field blank and use a common css file for all.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Footer","wpas"); ?></label>
		<div class="controls span9">
		<?php display_tinymce('shc-layout_footer','',1);  ?>
		<a href="#" style="cursor: help;" title="<?php _e("It defines the footer content of the view. The footer content is static and displayed on the bottom section of your view's content.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div> <!-- end of tab1 -->
		<div id="shctabs-2" class="tab-pane fade in">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Entities Per Page","wpas"); ?></label>
		<div class="controls span9">
		<input id="shc-sc_post_per_page" name="shc-sc_post_per_page" class="input-mini" type="text" placeholder="<?php _e("e.g. 16","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Specify the number of content block to show per page. Use any integer value or -1 to show all.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("List Order","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-sc_order" id="shc-sc_order" class="input-small">
		<option value="DESC" selected="selected"><?php _e("Descending","wpas"); ?></option>
		<option value="ASC"><?php _e("Ascending","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Allows the content to be sorted ascending or descending by a parameter selected. Defaults to descending.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Sort Retrieved Posts By","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-sc_orderby" id="shc-sc_orderby" class="input-small">
		<option value="date" selected="selected"><?php _e("Date","wpas"); ?></option>
		<option value="ID"><?php _e("ID","wpas"); ?></option>
		<option value="author"><?php _e("Author","wpas"); ?></option>
		<option value="title"><?php _e("Title","wpas"); ?></option>
		<option value="parent"><?php _e("Post parent id","wpas"); ?></option>
		<option value="modified"><?php _e("Last modified date","wpas"); ?></option>
		<option value="rand"><?php _e("Random","wpas"); ?></option>
		<option value="comment_count"><?php _e("Number of comments","wpas"); ?></option>
		<option value="none"><?php _e("None","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Allows sorting of retrieved content by a parameter selected. Defaults to date.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Show By Status","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-sc_post_status" id="shc-sc_post_status" class="input-small">
		<option value="publish" selected="selected"><?php _e("Publish","wpas"); ?></option>
		<option value="pending"><?php _e("Pending","wpas"); ?></option>
		<option value="title"><?php _e("Draft","wpas"); ?></option>
		<option value="auto-draft"><?php _e("With no content","wpas"); ?></option>
		<option value="future"><?php _e("Future","wpas"); ?></option>
		<option value="private"><?php _e("Private","wpas"); ?></option>
		<option value="trash"><?php _e("Trash","wpas"); ?></option>
		<option value="any"><?php _e("Any but excluded from search","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Retrieves content by status, default value is publish.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div> <!-- end of tab2 -->
		<div id="shctabs-3" class="tab-pane fade in">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("No Access Message","wpas"); ?></label>
		<div class="controls span9">
		<?php display_tinymce('shc-no_access_msg','You are not allowed to access to this area. Please contact the site administrator.'); ?>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the text which will be displayed to users that do not have access to this view.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div> <!-- end of tab3 -->

		<div class="control-group row-fluid">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
		<i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-shortcode" type="submit" value="Save">
		<i class="icon-save"></i><?php _e("Save","wpas"); ?></button>
		</div>

		</fieldset>
		</form>

		<?php
}
?>
