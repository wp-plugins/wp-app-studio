<?php
function wpas_add_shortcode_form($app_id)
{
	?>
<script type="text/javascript">
jQuery(document).ready(function($) {
        $('#shc-view_type').click(function() {
		app_id = jQuery('input#app').val();
                if($(this).find('option:selected').val() == 'std')
                {
			$('#shc-theme_type_div').show();
			$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id}, function(response)
			{
				$('#add-shortcode-div #shc-attach').html(response);
				$('#shc-attach_div').show();
			});
			$('#shc-attach_form_div').hide();
		}
                else if($(this).find('option:selected').val() == 'search')
		{
			$('#shc-theme_type_div').hide();
			$.get(ajaxurl,{action:'wpas_get_search_forms',app_id:app_id}, function(response)
                        {
                                $('#shc-attach_form').html(response);
				$('#shc-attach_form_div').show();
                        });
			$('#shc-attach_div').hide();
		}
		else
		{
			$('#shc-theme_type_div').hide();
			$('#shc-attach_form_div').hide();
			$('#shc-attach_div').hide();
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
		<i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Views help you display entity content where and how you wanted on the frontend. If you'd like to sort, filter,and reformat content, you need to use views. Just put a view's shortcode on a page and you are good to go. Views can also be attached ta a search form if you need to display a search form and the results on a page.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Name","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-label" id="shc-label" type="text" placeholder="<?php _e("e.g. sc_products","wpas");?>">
		<a href="#" title="<?php _e("General name for the view. By enclosing the view name in square brackets in a page, post or a text widget, you can display the content returned by the view shortcode's query. The view name should be all lowercase and use all letters, but numbers and underscores (not dashes!) should work fine too. Max 30 characters allowed. If the shortcode is used in a text widget or a page and the content has multiple pages, paginated navigation links are displayed. You can filter the content by generating a shortcode filter using the WPAS toolbar button on a page or post.","wpas"); ?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Type","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-view_type" id="shc-view_type" class="input-medium">
		<option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
		<option value="std"><?php _e("Standard","wpas"); ?></option>
		<option value="search"><?php _e("Search","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the type of view to be created. Standard views are for displaying content on a page or post without any dependency to any other wpas component such as a search form. Search views are for displaying the results of search query and can be attached to a search form. To attach a search results view to a search form, create a search form then select the view to be attached in the form configuration screen. Standard views can not be attached to search forms. Both types are available for further filtering using the WPAS toolbar button on a page or post.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-attach_div" name="shc-attach_div" style="display:none;">
		<label class="control-label span3"><?php _e("Attach to Entity","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach" name="shc-attach">
		</select><a href="#" title="<?php _e("Views must be attached to a predefined entity. The attached entity's content is returned by the view after query filters applied.","wpas"); ?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-attach_form_div" name="shc-attach_form_div" style="display:none;">
		<label class="control-label span3"><?php _e("Attach to Form","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach_form" name="shc-attach_form">
		</select><a href="#" style="cursor: help;" title="<?php _e("Search forms must be attched to a already created view. A search view defines the format of how search results will be displayed.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		<div id="view-tabs">
        <ul id="shcTab" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#shctabs-1"><?php _e("Display Options","wpas"); ?></a></li>
        <li><a data-toggle="tab" href="#shctabs-2"><?php _e("Filters","wpas"); ?></a></li>
        <li id="shctabs-3-li"><a data-toggle="tab" href="#shctabs-3"><?php _e("Messages","wpas"); ?></a></li>
        </ul>
        <div id="ShcTabContent" class="tab-content">
        <div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Display Options tab configures how the view will be displayed on the frontend. Filters tab defines how the content will be returned by setting sort order, number of records etc. Messages tab helps you define the messages to be displayed to users when the view's content is requested.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
	<div id="shctabs-1" class="tab-pane fade in active">
		<div class="control-group row-fluid" id="shc-theme_type_div" style="display:none;">
		<label class="control-label span3"><?php _e("Template","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-theme_type" id="shc-theme_type" class="input-medium">
		<option value="Pure">jQuery UI</option>
		<option value="Bootstrap" selected="selected">Twitter's Bootstrap</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the frontend framework which will be used to configure the overall look and feel of the view. If you pick jQuery UI, you can choose your theme from App's Settings under the theme tab. Default is Twitter Bootstrap.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
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
		<label class="control-label span3"><?php _e("Layout","wpas"); ?></label>
		<div class="controls span9">
<?php
		display_tinymce('shc-sc_layout','',1,1); 
?>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the layout of a single record of your view. You can also edit the source code, add entity attributes, taxonomies.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Footer","wpas"); ?></label>
		<div class="controls span9">
		<?php display_tinymce('shc-layout_footer','',1);  ?>
		<a href="#" style="cursor: help;" title="<?php _e("It defines the footer content of the view. The footer content is static and displayed on the bottom section of your view's content.","wpas"); ?>"><i class="icon-info-sign"></i></a>
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
