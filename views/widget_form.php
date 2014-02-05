<?php
function wpas_add_widget_form($app_id)
{
	?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
				jQuery('#widg-type').click(function() {
					if(jQuery(this).find('option:selected').val() == 'sidebar')
					{
						jQuery('#widg-dash_subtype_div').hide();
						jQuery('#widg-side_subtype_div').show();
					}
					else if(jQuery(this).find('option:selected').val() == 'dashboard')
					{
						jQuery('#widg-dash_subtype_div').show();
						jQuery('#widg-side_subtype_div').hide();
					}
					else
					{ 
						jQuery('#widg-dash_subtype_div').hide();
						jQuery('#widg-side_subtype_div').hide();
					}
					jQuery('#widg-html_div').hide();
					jQuery('#widg-label_div').hide();
					jQuery('#widg-wdesc_div').hide();
					jQuery('#widg-attach_div').hide();
					jQuery('#widg-attach-rel_div').hide();
					jQuery('#widg-rel-conn-type_div').hide();
					jQuery('#widg-rel-to-title_div').hide();
					jQuery('#widg-layout_div').hide();
					jQuery('#widg-css_div').hide();
					jQuery('#widg-post_per_page_div').hide();
					jQuery('#widg-order_div').hide();
					jQuery('#widg-orderby_div').hide();
					jQuery('#widg-post_status_div').hide();
				});
				jQuery('#widg-dash_subtype,#widg-side_subtype').click(function() {
					if(jQuery(this).find('option:selected').val() == 'admin')
					{
						jQuery('#widg-html_div').show();
						jQuery('#widg-wdesc_div').show();
						jQuery('#widg-attach_div').hide();
						jQuery('#widg-attach-rel_div').hide();
						jQuery('#widg-rel-conn-type_div').hide();
						jQuery('#widg-rel-to-title_div').hide();
						jQuery('#widg-layout_div').hide();
						jQuery('#widg-css_div').hide();
						jQuery('#widg-post_per_page_div').hide();
						jQuery('#widg-order_div').hide();
						jQuery('#widg-orderby_div').hide();
						jQuery('#widg-post_status_div').hide();
						
					}
					else if(jQuery(this).find('option:selected').val() == 'entity')
					{
						jQuery('#widg-label_div').show();
						jQuery('#widg-wdesc_div').show();
						jQuery('#widg-html_div').hide();
						jQuery('#widg-attach_div').show();
						jQuery('#widg-attach-rel_div').hide();
						jQuery('#widg-rel-conn-type_div').hide();
						jQuery('#widg-rel-to-title_div').hide();
						jQuery('#widg-layout_div').show();
						jQuery('#widg-css_div').show();
						jQuery('#widg-post_per_page_div').show();
						jQuery('#widg-order_div').show();
						jQuery('#widg-orderby_div').show();
						jQuery('#widg-post_status_div').show();
						app_id = jQuery('input#app').val();
						jQuery.get(ajaxurl,{action:'wpas_get_entities',type:'widget',app_id:app_id}, function(response)
						{
							jQuery('#add-widget-div #widg-attach').html(response);
						}); 
					}
					else if(jQuery(this).find('option:selected').val() == 'relationship')
					{
						jQuery('#widg-label_div').hide();
						jQuery('#widg-wdesc_div').show();
						jQuery('#widg-html_div').hide();
						jQuery('#widg-attach_div').hide();
						jQuery('#widg-attach-rel_div').show();
						jQuery('#widg-rel-conn-type_div').show();
						jQuery('#widg-rel-to-title_div').show();
						jQuery('#widg-layout_div').show();
						jQuery('#widg-css_div').show();
						jQuery('#widg-post_per_page_div').show();
						jQuery('#widg-order_div').hide();
						jQuery('#widg-orderby_div').hide();
						jQuery('#widg-post_status_div').hide();
					}
					else
					{
						jQuery('#widg-html_div').hide();
						jQuery('#widg-attach_div').hide();
                                                jQuery('#widg-layout_div').hide();
                                                jQuery('#widg-css_div').hide();
                                                jQuery('#widg-post_per_page_div').hide();
                                                jQuery('#widg-order_div').hide();
                                                jQuery('#widg-orderby_div').hide();
                                                jQuery('#widg-post_status_div').hide();
					}
				});
		});
	</script>
		<form action="" method="post" id="widget-form" class="form-horizontal">
		<input type="hidden" id="app" name="app" value="">
		<input type="hidden" value="" name="widget" id="widget">
		<fieldset>
		<div class="well">
		<div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Widgets add content and features to your Sidebars or Dashboard. You can display entity data in a sidebar or dashboard widget.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Widget Name","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="widg-name" id="widg-name" type="text" placeholder="<?php _e("e.g. customer_survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Unique identifier for the widget. Can not contain capital letters,dashes or spaces. Between 3 and 30 characters.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
                <label class="control-label span3"><?php _e("Widget Title","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-title" id="widg-title" type="text" placeholder="<?php _e("e.g. Recent Orders","wpas"); ?>">
                <a href="#" style="cursor: help;" title="<?php _e("Sets the title of the widget on sidebar or dashboard. For relationship widgets, it is used as - from entity - relationship title.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid">
                <label class="control-label span3"><?php _e("Type","wpas"); ?></label>
                <div class="controls span9">
                <select name="widg-type" id="widg-type" class="input-medium">
                <option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
                <option value="sidebar"><?php _e("Sidebar","wpas"); ?></option>
                <option value="dashboard"<?php _e(">Dashboard","wpas"); ?></option>
                </select>
                <a href="#" style="cursor: help;" title="<?php _e("Widgets could be created either on sidebar or dashboard.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-dash_subtype_div" style="display:none;">
                <label class="control-label span3"><?php _e("Subtype","wpas"); ?></label>
                <div class="controls span9">
                <select name="widg-dash_subtype" id="widg-dash_subtype" class="input-medium">
                <option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
                <option value="entity"><?php _e("Entity","wpas"); ?></option>
                <option value="admin"><?php _e("Admin","wpas"); ?></option>
                </select>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the type of dashboard widget.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-side_subtype_div" style="display:none;">
                <label class="control-label span3"><?php _e("Subtype","wpas"); ?></label>
                <div class="controls span9">
                <select name="widg-side_subtype" id="widg-side_subtype" class="input-medium">
                <option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
                <option value="entity"><?php _e("Entity","wpas"); ?></option>
                <option value="relationship"><?php _e("Relationship","wpas"); ?></option>
                </select>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the type of sidebar widget. ","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-attach_div" style="display:none;">
		<label class="control-label span3"><?php _e("Attach to Entity","wpas"); ?></label>
		<div class="controls span9">
		<select id="widg-attach" name="widg-attach">
		</select><a href="#" style="cursor: help;" title="<?php _e("All widgets must be attached to a predefined entity. Entity widgets display the attached entity's content.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-attach-rel_div" style="display:none;">
		<label class="control-label span3"><?php _e("Attach to Relationship","wpas"); ?></label>
		<div class="controls span9">
		<select id="widg-attach-rel" name="widg-attach-rel">
		</select><a href="#" style="cursor: help;" title="<?php _e("All widgets must be attached to a predefined relationship. Relationship widgets display the attached relationship's content.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-rel-conn-type_div" style="display:none;">
                <label class="control-label span3"><?php _e("Connection Type","wpas"); ?></label>
                <div class="controls span9">
                <select name="widg-rel-conn-type" id="widg-rel-conn-type" class="input-medium">
		<option value=""><?php _e("Please select","wpas"); ?></option>
                </select>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the connection type of a relationship. Connection type could be either connected or related. Connected type displays the - many - side objects of a relationship. Related type is only used in many-to-many relationships and displays similar related objects. For example, in a products to orders many-to-many relationship; connected shows the orders which include the product selected and related shows the products which are also included in the same order of the selected product i.e. Customer who purchased this also purchased type.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid" id="widg-rel-to-title_div" style="display:none;">
                <label class="control-label span3"><?php _e("Widget To Title","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-rel-to-title" id="widg-rel-to-title" type="text" placeholder="<?php _e("e.g. Recent Products","wpas"); ?>">
                <a href="#" style="cursor: help;" title="<?php _e("Sets the initial 'to entity' relationship title. You can change this from the widget's configuration dialog after the widget is created.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid" id="widg-label_div" style="display:none;">
                <label class="control-label span3"><?php _e("Widget Label","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-label" id="widg-label" type="text" placeholder="<?php _e("e.g. Recent Products","wpas"); ?>">
                <a href="#" style="cursor: help;" title="<?php _e("Sets the initial title of the widget which will displayed on the backend.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid" id="widg-wdesc_div" style="display:none;">
                <label class="control-label span3"><?php _e("Widget Description","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-wdesc" id="widg-wdesc" type="text" placeholder="<?php _e("e.g. The most recent products","wpas"); ?>">
                <a href="#" style="cursor: help;" title="<?php _e("Sets the initial short description explaining what the widget does.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid" id="widg-html_div" style="display:none;">
                <label class="control-label span3"><?php _e("Widget message","wpas"); ?></label>
                <div class="controls span9">
		<textarea class="input-xlarge" name="widg-html" id="widg-html" rows="7" placeholder="<?php _e("Optionally you can set an initial message","wpas"); ?>"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the initial message of the dashboard widget for application-wide messages.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-layout_div" style="display:none;">
		<label class="control-label span3"><?php _e("Layout","wpas"); ?></label>
		<div class="controls span9">
	<?php display_tinymce('widg-layout','',1,1); ?>
		<a href="#" style="cursor: help;" title="<?php _e("The widget layout defines how the content will be displayed within the widget.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-css_div" style="display:none;">
		<label class="control-label span3"><?php _e("Css ","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="input-xlarge" id="widg-css" name="widg-css" class="tinymce"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("The custom css code to be used when displaying the content. You can leave this field blank and use WPAS app css file.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-post_per_page_div" style="display:none;">
		<label class="control-label span3"><?php _e("Entities Per Page ","wpas"); ?></label>
		<div class="controls span9">
		<input id="widg-post_per_page" name="widg-post_per_page" class="input-mini" type="text" placeholder="<?php _e("e.g. 16'","wpas"); ?>" value="">
		<a href="#" style="cursor: help;" title="<?php _e("Number of entity content to show per page. Use any integer value or -1 to show all.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-order_div" style="display:none;">
		<label class="control-label span3"><?php _e("List Order ","wpas"); ?></label>
		<div class="controls span9">
		<select name="widg-order" id="widg-order" class="input-small">
		<option value="DESC" selected="selected"><?php _e("Descending","wpas"); ?></option>
		<option value="ASC"><?php _e("Ascending","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Allows the content to be sorted ascending or descending. Defaults to descending.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-orderby_div" style="display:none;">
		<label class="control-label span3"><?php _e("Sort Retrieved Posts By ","wpas"); ?></label>
		<div class="controls span9">
		<select name="widg-orderby" id="widg-orderby" class="input-small">
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
		<div class="control-group row-fluid" id="widg-post_status_div" style="display:none;">
		<label class="control-label span3"><?php _e("Show By Status ","wpas"); ?></label>
		<div class="controls span9">
		<select name="widg-post_status" id="widg-post_status" class="input-small">
		<option value="publish" selected="selected"><?php _e("Publish","wpas"); ?></option>
		<option value="pending"><?php _e("Pending","wpas"); ?></option>
		<option value="title"><?php _e("Draft","wpas"); ?></option>
		<option value="auto-draft"><?php _e("With no content","wpas"); ?></option>
		<option value="future" ><?php _e("Future","wpas"); ?></option>
		<option value="private"><?php _e("Private","wpas"); ?></option>
		<option value="trash"><?php _e("Trash","wpas"); ?></option>
		<option value="any"><?php _e("Any but excluded from search","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Retrieves content by status, default value is publish","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>

		<div class="control-group row-fluid">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
		<i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-widget" type="submit" value="Save">
		<i class="icon-save"></i><?php _e("Save","wpas"); ?></button>
		</div>

		</fieldset>
		</form>

		<?php
}
?>
