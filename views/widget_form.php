<?php
function wpas_add_widget_form($app_id)
{
	?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
				$('#widg-type').click(function() {
					$(this).showWidgByType($(this).find('option:selected').val());
				});
				$.fn.showWidgByType = function(type,ptype) {	
					ptype = ptype || '';
					if(type == 'sidebar')
					{
						$('#widg-dash_subtype_div').hide();
						$('#widg-side_subtype_div').show();
						$('#widg-label_div').show();
					}
					else if(type == 'dashboard')
					{
						$('#widg-dash_subtype_div').show();
						$('#widg-side_subtype_div').hide();
						$('#widg-label_div').hide();
					}
					else
					{ 
						$('#widg-dash_subtype_div').hide();
						$('#widg-side_subtype_div').hide();
					}
					if(ptype != 'edit')
					{
						$('#widg-html_div').hide();
						$('#widg-wdesc_div').hide();
						$('#widg-layout_div').hide();
						$('#widg-css_div').hide();
						$('#widg-js_div').hide();
						$('#widg-cdnjs_div').hide();
						$('#widg-cdncss_div').hide();
						$('#widg-post_per_page_div').hide();
						$('#widg-order_div').hide();
						$('#widg-orderby_div').hide();
						$('#widg-orderby_comment_div').hide();
						$('#widg-post_status_div').hide();
						$('#widg-comment_status_div').hide();
						$('#widg-wp_dash_div').hide();
						$('#widg-app_dash_div').hide();
						$('#widg-app_dash_loc_div').hide();
					}
					$('#widg-attach_div').hide();
				}
				$('#widg-dash_subtype,#widg-side_subtype').click(function() {
					var widg_type = $('#widg-type').find('option:selected').val();
					$(this).showWidgFields($(this).find('option:selected').val(),widg_type);
				});
				$(document).on('change','#widg-attach',function(){
					app_id = $('input#app').val();
					ent_id = $('#widg-attach').find('option:selected').val();
					subtype = $('#widg-side_subtype').find('option:selected').val();
					dsubtype = $('#widg-dash_subtype').find('option:selected').val();
					$.get(ajaxurl,{action:'wpas_get_orderby_fields',app_id:app_id,ent_id:ent_id}, function(response)
					{
						$('#widg-orderby').html(response);
					});
					$(this).showWidgTags(app_id,ent_id,subtype,dsubtype);
				});
				$.fn.showWidgTags = function (app_id,ent_id,subtype,dsubtype){
					if(ent_id != 0 && (subtype == 'entity' || dsubtype == 'entity')){
						$.get(ajaxurl,{action:'wpas_get_layout_tags',type:'tag',app_id:app_id,comp_id:ent_id}, function(response){
							$('#widg-layout-tags').html(response);
						});
					}
				}
				$.fn.showWidgFields = function(subtype,widg_type,type){
					type = type || '';
					switch (subtype) {
						case 'admin':
							$('#widg-html_div').show();
							$('#widg-wdesc_div').show();
							$('#widg-attach_div').hide();
							$('#widg-layout_div').hide();
							$('#widg-css_div').hide();
							$('#widg-js_div').hide();
							$('#widg-cdnjs_div').hide();
							$('#widg-cdncss_div').hide();
							$('#widg-post_per_page_div').hide();
							$('#widg-order_div').hide();
							$('#widg-orderby_div').hide();
							$('#widg-orderby_comment_div').hide();
							$('#widg-post_status_div').hide();
							$('#widg-comment_status_div').hide();
							$('#widg-wp_dash_div').show();
							$('#widg-app_dash_div').show();
							if(type != 'edit')
							{
								$('#widg-app_dash').attr('checked',true);
								$('#widg-app_dash').val(1);
							}
							break;
						case 'entity':
							if(widg_type == 'dashboard')
							{
								$('#widg-wp_dash_div').show();
								$('#widg-app_dash_div').show();
								if(type != 'edit')
								{
									$('#widg-app_dash').attr('checked',true);
									$('#widg-app_dash').val(1);
								}
							}
							else
							{
								$('#widg-wp_dash_div').hide();
								$('#widg-app_dash_div').hide();
								$('#widg-app_dash_loc_div').hide();
							}
							$('#widg-wdesc_div').show();
							$('#widg-html_div').hide();
							$('#widg-attach_div').show();
							$('#widg-layout_div').show();
							$('#widg-css_div').show();
							$('#widg-js_div').show();
							$('#widg-cdnjs_div').show();
							$('#widg-cdncss_div').show();
							$('#widg-post_per_page_div').show();
							$('#widg-order_div').show();
							$('#widg-orderby_div').show();
							$('#widg-orderby_comment_div').hide();
							$('#widg-post_status_div').show();
							$('#widg-comment_status_div').hide();
							if(type != 'edit')
							{
								app_id = $('input#app').val();
								$.get(ajaxurl,{action:'wpas_get_entities',type:'widget',app_id:app_id}, function(response)
								{
									$('#add-widget-div #widg-attach').html(response);
								});
							}
							break; 
						case 'comment':
							if(widg_type == 'dashboard')
							{
								$('#widg-wp_dash_div').show();
								$('#widg-app_dash_div').show();
								if(type != 'edit')
								{
									$('#widg-app_dash').attr('checked',true);
									$('#widg-app_dash').val(1);
								}
							}
							else
							{
								$('#widg-wp_dash_div').hide();
								$('#widg-app_dash_div').hide();
								$('#widg-app_dash_loc_div').hide();
							}
							$('#widg-wdesc_div').show();
							$('#widg-html_div').hide();
							$('#widg-attach_div').show();
							$('#widg-layout_div').hide();
							$('#widg-css_div').hide();
							$('#widg-js_div').hide();
							$('#widg-cdnjs_div').hide();
							$('#widg-cdncss_div').hide();
							$('#widg-post_per_page_div').show();
							$('#widg-order_div').show();
							$('#widg-orderby_div').hide();
							$('#widg-orderby_comment_div').show();
							$('#widg-post_status_div').hide();
							$('#widg-comment_status_div').show();
							if(type != 'edit')
							{
								app_id = $('input#app').val();
								$.get(ajaxurl,{action:'wpas_get_entities',type:'widget-comment',app_id:app_id}, function(response)
								{
									$('#add-widget-div #widg-attach').html(response);
								});
							}
							break; 
						default:
							$('#widg-html_div').hide();
							$('#widg-attach_div').hide();
							$('#widg-layout_div').hide();
							$('#widg-css_div').hide();
							$('#widg-js_div').hide();
							$('#widg-cdnjs_div').hide();
							$('#widg-cdncss_div').hide();
							$('#widg-post_per_page_div').hide();
							$('#widg-order_div').hide();
							$('#widg-orderby_div').hide();
							$('#widg-orderby_comment_div').hide();
							$('#widg-post_status_div').hide();
							$('#widg-comment_status_div').hide();
							$('#widg-wp_dash_div').hide();
							$('#widg-app_dash_div').hide();
							$('#widg-app_dash_loc_div').hide();
							break;
					}
				}
				$('#widg-app_dash').click(function() {
					if($(this).attr('checked'))
					{
						$('#widg-app_dash_loc_div').show();
					}
					else
					{
						$('#widg-app_dash_loc_div').hide();
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
		<label class="control-label req span3"><?php _e("Name","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="widg-name" id="widg-name" type="text" placeholder="<?php _e("e.g. customer_survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Unique identifier for the widget. Can not contain capital letters,dashes or spaces. Between 3 and 30 characters.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
                <div class="control-group row-fluid" id="widg-label_div" style="display:none;">
                <label class="control-label span3 req"><?php _e("Label","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-label" id="widg-label" type="text" placeholder="<?php _e("e.g. Recent Products","wpas"); ?>">
                <a href="#" style="cursor: help;" title="<?php _e("Sets the initial title of the widget which will displayed on the backend.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid">
                <label class="control-label req span3"><?php _e("Title","wpas"); ?></label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-title" id="widg-title" type="text" placeholder="<?php _e("e.g. Recent Orders","wpas"); ?>">
                <a href="#" style="cursor: help;" title="<?php _e("Sets the title of the widget on sidebar or dashboard.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid">
                <label class="control-label req span3"><?php _e("Type","wpas"); ?></label>
                <div class="controls span9">
                <select name="widg-type" id="widg-type" class="input-medium">
                <option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
                <option value="sidebar"><?php _e("Sidebar","wpas"); ?></option>
                <option value="dashboard"<?php _e(">Dashboard","wpas"); ?></option>
                </select>
                <a href="#" style="cursor: help;" title="<?php _e("Sets type of the your widget. You can create a Dashboard or Sidebar widget.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-dash_subtype_div" style="display:none;">
                <label class="control-label req span3"><?php _e("Subtype","wpas"); ?></label>
                <div class="controls span9">
                <select name="widg-dash_subtype" id="widg-dash_subtype" class="input-medium">
                <option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
                <option value="entity"><?php _e("Entity","wpas"); ?></option>
                <option value="comment"><?php _e("Comment","wpas"); ?></option>
                <option value="admin"><?php _e("Admin","wpas"); ?></option>
                </select>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the type of dashboard widget.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-side_subtype_div" style="display:none;">
                <label class="control-label req span3"><?php _e("Subtype","wpas"); ?></label>
                <div class="controls span9">
                <select name="widg-side_subtype" id="widg-side_subtype" class="input-medium">
                <option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
                <option value="entity"><?php _e("Entity","wpas"); ?></option>
                <option value="comment"><?php _e("Comment","wpas"); ?></option>
                </select>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the type of sidebar widget. ","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-attach_div" style="display:none;">
		<label class="control-label req span3"><?php _e("Attach to Entity","wpas"); ?></label>
		<div class="controls span9">
		<select id="widg-attach" name="widg-attach">
		</select><a href="#" style="cursor: help;" title="<?php _e("All widgets must be attached to a predefined entity. Entity widgets display the attached entity's content.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
                <div class="control-group row-fluid" id="widg-wdesc_div" style="display:none;">
                <label class="control-label span3"><?php _e("Description","wpas"); ?></label>
                <div class="controls span9">
                <textarea name="widg-wdesc" id="widg-wdesc" class="wpas-std-textarea"></textarea>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the initial short description explaining what the widget does.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid" id="widg-html_div" style="display:none;">
                <label class="control-label span3"><?php _e("Widget message","wpas"); ?></label>
                <div class="controls span9">
		<textarea class="wpas-std-textarea" name="widg-html" id="widg-html" placeholder="<?php _e("Optionally you can set an initial message","wpas"); ?>"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the initial message of the dashboard widget for application-wide messages.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div id="widg-layout_div" style="display:none;">
                <div class="control-group row-fluid">
                <label class="control-label span3"><?php _e("Header","wpas"); ?></label>
                <div class="controls span9">
                <textarea id="widg-layout_header" name="widg-layout_header" class="wpas-std-textarea"></textarea>
                <a href="#" style="cursor: help;" title="<?php _e("It defines the header content of the widget. The header content is static and displayed on the top section of your widget's content.","wpas"); ?>"><i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid">
                <label class="control-label req span3"><?php _e("Layout","wpas"); ?></label>
                <div class="controls span9">
                <textarea id="widg-layout" name="widg-layout" class="wpas-std-textarea"></textarea>
                <a href="#" style="cursor: help;" title="<?php _e("The widget layout defines how the content will be displayed within the widget. Click Show tags button to customize the content to be returned.","wpas"); ?>"><i class="icon-info-sign"></i></a>
                <div style="padding:10px 0;">
                <div style="padding:10px;">
                <button type="button" class="btn btn-mini btn-info" data-toggle="collapse" data-target="#widg-layout-tags">Show Tags</button>
                </div>
                <div id="widg-layout-tags" class="collapse in"><?php _e("Please select an entity to view tags.","wpas"); ?></div>
                </div>
                </div>
                </div>
                <div class="control-group row-fluid">
                <label class="control-label span3"><?php _e("Footer","wpas"); ?></label>
                <div class="controls span9">
                <textarea id="widg-layout_footer" name="widg-layout_footer" class="wpas-std-textarea"></textarea>
                <a href="#" style="cursor: help;" title="<?php _e("It defines the footer content of the widget. The footer content is static and displayed on the bottom section of your widget's content.","wpas"); ?>"><i class="icon-info-sign"></i></a>
                </div>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-css_div" style="display:none;">
		<label class="control-label span3"><?php _e("Css ","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="wpas-std-textarea" id="widg-css" name="widg-css"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("The custom css code to be used when displaying the content. You can leave this field blank and use WPAS app css file.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-js_div" style="display:none;">
		<label class="control-label span3"><?php _e("Js","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="wpas-std-textarea" id="widg-js" name="widg-js"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("The custom JavaScript code which will be enqueued to the view. Do not include script tags.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-cdnjs_div" style="display:none;">
		<label class="control-label span3"><?php _e("CDN JS","wpas"); ?></label>
		<div class="controls span9">
		<textarea id="widg-cdnjs" name="widg-cdnjs" class="wpas-std-textarea" placeholder=" YOU MUST USE HTTPS e.g. https://cdnjs.cloudflare.com/ajax/libs/Readmore.js/2.0.5/readmore.min.js;https://cdn.jsdelivr.net/ace/1.1.9/min/ace.js" ></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("Enter semicolon separated JavaScript file urls starting with https. We only accept files from the following sources; cdnjs.cloudflare.com and cdn.jsdelivr.net or raw.githubusercontent.com. All files will be downloaded and merged before getting locally enqueued to the view they are linked to.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-cdncss_div" style="display:none;">
		<label class="control-label span3"><?php _e("CDN CSS","wpas"); ?></label>
		<div class="controls span9">
		<textarea id="widg-cdncss" name="widg-cdncss" class="wpas-std-textarea" placeholder=" YOU MUST USE HTTPS e.g. https://cdnjs.cloudflare.com/ajax/libs/example/2.0.5/example.min.css;https://cdn.jsdelivr.net/example/1.1.9/min/example.min.css" ></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("Enter semicolon separated CSS file urls starting with https. We only accept files from the following sources; cdnjs.cloudflare.com and cdn.jsdelivr.net or raw.githubusercontent.com. All files will be downloaded and merged before getting locally enqueued to the view they are linked to.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-wp_dash_div" style="display:none;">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Display in Wordpress Dashboard","wpas");?>
		<input name="widg-wp_dash" id="widg-wp_dash" type="checkbox" value="1">
		<a href="#" style="cursor: help;" title="<?php _e("When set, displays widget in wordpress dashboard.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-app_dash_div" style="display:none;">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Display in App Dashboard","wpas");?>
		<input name="widg-app_dash" id="widg-app_dash" type="checkbox" value="1" checked>
		<a href="#" style="cursor: help;" title="<?php _e("When set, displays widget in app dashboard.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-app_dash_loc_div" style="display:none;">
		<label class="control-label span3 req"><?php _e("Dashboard Location","wpas");?></label>
		<div class="controls span9">
		<select name="widg-app_dash_loc" id="widg-app_dash_loc" class="input-medium">
		<option value="">Please select</option>
		<option value="wholecol">One Column</option>
		<option value="normal">Two Column Left</option>
		<option value="side">Two Column Right</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("The title of the dashboard. Max:255 char.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid" id="widg-post_per_page_div" style="display:none;">
		<label class="control-label span3"><?php _e("Per Page ","wpas"); ?></label>
		<div class="controls span9">
		<input id="widg-post_per_page" name="widg-post_per_page" class="input-mini" type="text" placeholder="<?php _e("e.g. 16'","wpas"); ?>" value="">
		<a href="#" style="cursor: help;" title="<?php _e("Number of entity/comment content to show per page. Use any integer value or -1 to show all.","wpas"); ?>">
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
		<label class="control-label span3"><?php _e("Sort Retrieved By ","wpas"); ?></label>
		<div class="controls span9">
		<select name="widg-orderby" id="widg-orderby" class="input-small">
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Allows sorting of retrieved content by a parameter selected. Defaults to date.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-orderby_comment_div" style="display:none;">
		<label class="control-label span3"><?php _e("Sort Retrieved By ","wpas"); ?></label>
		<div class="controls span9">
		<select name="widg-orderby_comment" id="widg-orderby_comment" class="input-small">
		<option value="date" selected="selected"><?php _e("Date","wpas"); ?></option>
		<option value="ID"><?php _e("ID","wpas"); ?></option>
		<option value="author"><?php _e("Author","wpas"); ?></option>
		<option value="author_email"><?php _e("Author Email","wpas"); ?></option>
		<option value="author_IP"><?php _e("Author IP","wpas"); ?></option>
		<option value="author_url"><?php _e("Author Url","wpas"); ?></option>
		<option value="content"><?php _e("Content","wpas"); ?></option>
		<option value="parent"><?php _e("Parent","wpas"); ?></option>
		<option value="post_ID"><?php _e("Post id","wpas"); ?></option>
		<option value="approved"><?php _e("Approved","wpas"); ?></option>
		<option value="agent"><?php _e("Agent","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Allows sorting of retrieved content by a parameter selected. Defaults to date.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div id="widg-post_status_div">
		<div class="control-group row-fluid" style="display:none;">
		<label class="control-label span3"><?php _e("Show By Status ","wpas"); ?></label>
		<div class="controls span9">
		<select name="widg-post_status" id="widg-post_status" class="input-small">
		<option value="publish" selected="selected"><?php _e("Publish","wpas"); ?></option>
		<option value="pending"><?php _e("Pending","wpas"); ?></option>
		<option value="draft"><?php _e("Draft","wpas"); ?></option>
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
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Filter","wpas");?></label>
		<div class="controls span9">
		<textarea class="wpas-std-textarea" name="widg-filter" id="widg-filter" placeholder="<?php _e("e.g. attr::emd_product_featured::is::1;tax::product_cat::is::electronics","wpas");?>" value="" ></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("Set the default filter for the content to be displayed in the widget. You can use widget filters to return for example; featured products, on-sale products etc. You can combine multiple filters with semicolon which triggers AND operator. For example;-attr::emd_product_featured::is::1;tax::product_cat::is::electronics- filter shows the featured products in electronics category. The easiest way to create filters is to use the WPAS button on a page toolbar of the generated app to design a filter and then copy paste the required part here. Please note that we emd_ prefix vs. WPAS ent_ for attributes.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-comment_status_div" style="display:none;">
		<label class="control-label span3"><?php _e("Show By Status ","wpas"); ?></label>
		<div class="controls span9">
		<select name="widg-comment_status" id="widg-comment_status" class="input-small">
		<option value="approve" selected="selected"><?php _e("Approved","wpas"); ?></option>
		<option value="hold"><?php _e("Hold","wpas"); ?></option>
		<option value="spam"><?php _e("Spam","wpas"); ?></option>
		<option value="trash"><?php _e("Trash","wpas"); ?></option>
		<option value="post-trashed" ><?php _e("Post Trashed","wpas"); ?></option>
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
