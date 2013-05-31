<?php
function wpas_add_widget_form($app_id)
{
	?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
				jQuery('#widg-type').click(function() {
					if(jQuery(this).find('option:selected').val() == 'sidebar')
					{
						jQuery('#widg-attach_div').show();
						jQuery('#widg-label_div').show();
						jQuery('#widg-wdesc_div').show();
						jQuery('#widg-dash_subtype_div').hide();
						jQuery('#widg-html_div').hide();
						jQuery('#widg-side_subtype_div').show();
						jQuery('#widg-layout_div').show();
						jQuery('#widg-css_div').show();
						jQuery('#widg-post_per_page_div').show();
						jQuery('#widg-order_div').show();
						jQuery('#widg-orderby_div').show();
						jQuery('#widg-post_status_div').show();
					}
					else if(jQuery(this).find('option:selected').val() == 'dashboard')
					{
						jQuery('#widg-label_div').hide();
						jQuery('#widg-wdesc_div').hide();
						jQuery('#widg-dash_subtype_div').show();
						jQuery('#widg-side_subtype_div').hide();
						jQuery('#widg-attach_div').hide();
						jQuery('#widg-html_div').hide();
						jQuery('#widg-layout_div').hide();
						jQuery('#widg-css_div').hide();
						jQuery('#widg-post_per_page_div').hide();
						jQuery('#widg-order_div').hide();
						jQuery('#widg-orderby_div').hide();
						jQuery('#widg-post_status_div').hide();
					}
					else
					{
						jQuery('#widg-dash_subtype_div').hide();
						jQuery('#widg-html_div').hide();
						jQuery('#widg-label_div').hide();
						jQuery('#widg-wdesc_div').hide();
						jQuery('#widg-side_subtype_div').hide();
						jQuery('#widg-attach_div').hide();
						jQuery('#widg-layout_div').hide();
						jQuery('#widg-css_div').hide();
						jQuery('#widg-post_per_page_div').hide();
						jQuery('#widg-order_div').hide();
						jQuery('#widg-orderby_div').hide();
						jQuery('#widg-post_status_div').hide();
					}
				});
				jQuery('#widg-dash_subtype,#widg-side_subtype').click(function() {
					if(jQuery(this).find('option:selected').val() == 'admin')
					{
						jQuery('#widg-html_div').show();
						jQuery('#widg-attach_div').hide();
						jQuery('#widg-layout_div').hide();
						jQuery('#widg-css_div').hide();
						jQuery('#widg-post_per_page_div').hide();
						jQuery('#widg-order_div').hide();
						jQuery('#widg-orderby_div').hide();
						jQuery('#widg-post_status_div').hide();
						
					}
					else if(jQuery(this).find('option:selected').val() == 'entity')
					{
						jQuery('#widg-html_div').hide();
						jQuery('#widg-attach_div').show();
						jQuery('#widg-layout_div').show();
						jQuery('#widg-css_div').show();
						jQuery('#widg-post_per_page_div').show();
						jQuery('#widg-order_div').show();
						jQuery('#widg-orderby_div').show();
						jQuery('#widg-post_status_div').show();
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
		<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="Widgets add content and features to your Sidebars or Dashboard. You can display entity data in a sidebar or dashboard widget."> HELP</a></div></div>
		<div class="control-group row-fluid">
                <label class="control-label span3">Widget Title</label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-title" id="widg-title" type="text" placeholder="e.g. Recent Orders">
                <a href="#" title="Sets Title of the dashboard widget which will be displayed on the backend dashboard page." style="cursor: help;">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid">
                <label class="control-label span3" >Type</label>
                <div class="controls span9">
                <select name="widg-type" id="widg-type" class="input-medium">
                <option value="" selected="selected">Please select</option>
                <option value="sidebar">Sidebar</option>
                <option value="dashboard">Dashboard</option>
                </select>
                <a href="#" style="cursor: help;" title="Retrieves content by status, default value is publish ">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-dash_subtype_div" style="display:none;">
                <label class="control-label span3" >Subtype</label>
                <div class="controls span9">
                <select name="widg-dash_subtype" id="widg-dash_subtype" class="input-medium">
                <option value="" selected="selected">Please select</option>
                <option value="entity">Entity</option>
                <option value="admin">Admin</option>
                </select>
                <a href="#" style="cursor: help;" title="Retrieves content by status, default value is publish ">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-side_subtype_div" style="display:none;">
                <label class="control-label span3" >Subtype</label>
                <div class="controls span9">
                <select name="widg-side_subtype" id="widg-side_subtype" class="input-medium">
                <option value="entity">Entity</option>
                </select>
                <a href="#" style="cursor: help;" title="Retrieves content by status, default value is publish ">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-attach_div" style="display:none;">
		<label class="control-label span3">Attach to Entity</label>
		<div class="controls span9">
		<select id="widg-attach" name="widg-attach">
		</select><a href="#" title="All widgets must be attached to already defined entity. Widgets display The attached entity's content." style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
                <div class="control-group row-fluid" id="widg-label_div" style="display:none;">
                <label class="control-label span3">Widget label</label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-label" id="widg-label" type="text" placeholder="e.g. Recent Products">
                <a href="#" title="Title of the widget which will displayed on the backend." style="cursor: help;">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid" id="widg-wdesc_div" style="display:none;">
                <label class="control-label span3">Widget description</label>
                <div class="controls span9">
                <input class="input-xlarge" name="widg-wdesc" id="widg-wdesc" type="text" placeholder="e.g. The most recent products">
                <a href="#" title="A short description explaining what the widget does." style="cursor: help;">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
                <div class="control-group row-fluid" id="widg-html_div" style="display:none;">
                <label class="control-label span3">Widget message</label>
                <div class="controls span9">
		<textarea class="input-xlarge" name="widg-html" id="widg-html" rows="7" placeholder="Optionally you can set an initial message"></textarea>
		<a href="#" style="cursor: help;" title="Initial message of the dashboard widget for application-wide messages.">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid" id="widg-layout_div" style="display:none;">
		<label class="control-label span3" >Layout</label>
		<div class="controls span9">
		<?php
		$initial_data='<table class="content-table" border=0 cellpadding=1 cellspacing=1><tbody><tr><td class="content-cell featured-image">!#featured_img_thumb#</td><td class="content-cell content-title">!#title#</td></tr><tr><td class="content-cell content-excerpt" colspan=2>!#excerpt#</td></tr></tbody></table>';

	$buttons['theme_advanced_buttons1'] = 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,mylistbox';
	$buttons['theme_advanced_buttons2'] = 'tablecontrols,code';

	$settings = array(
			'text_area_name'=>'widg-layout',//name you want for the textarea
			'quicktags' => false,
			'media_buttons' => false,
			'textarea_rows' => 10,
			'language' => 'en',
			'tinymce' => $buttons,
			);
	$id = 'widg-layout';//has to be lower case
	wp_editor($initial_data,$id,$settings);
	$default_css = ".content-table{
border:0;
}
.featured-image{
border:0;
}
.content-title{
font-weight:bold;
}
.content-excerpt{
font-style:italic;
}";
	?>
		<a href="#" style="cursor: help;" title="The widget layout defines how the attached entity content will be displayed within the widget."><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-css_div" style="display:none;">
		<label class="control-label span3" >Css </label>
		<div class="controls span9">
		<textarea class="input-xlarge" id="widg-css" name="widg-css" class="tinymce" placeholder="<?php echo esc_attr($default_css); ?>"></textarea>
		<a href="#" style="cursor: help;" title="The custom css code to be used when displaying the content. You can leave this field blank and use a common css file for all.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-post_per_page_div" style="display:none;">
		<label class="control-label span3" >Entities Per Page </label>
		<div class="controls span9">
		<input id="widg-post_per_page" name="widg-post_per_page" class="input-mini" type="text" placeholder="e.g. 16" value="" />
		<a href="#" style="cursor: help;" title="Number of entity content to show per page. Use any integer value or -1 to show all.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-order_div" style="display:none;">
		<label class="control-label span3" >List Order </label>
		<div class="controls span9">
		<select name="widg-order" id="widg-order" class="input-small">
		<option value="DESC" selected="selected">Descending</option>
		<option value="ASC">Ascending</option>
		</select>
		<a href="#" style="cursor: help;" title="Allows the content to be sorted ascending or descending by a parameter selected. Defaults to descending. ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-orderby_div" style="display:none;">
		<label class="control-label span3" >Sort Retrieved Posts By </label>
		<div class="controls span9">
		<select name="widg-orderby" id="widg-orderby" class="input-small">
		<option value="date" selected="selected">Date</option>
		<option value="ID">ID</option>
		<option value="author">Author</option>
		<option value="title">Title</option>
		<option value="parent">Post parent id</option>
		<option value="modified">Last modified date</option>
		<option value="rand">Random</option>
		<option value="comment_count">Number of comments</option>
		<option value="none">None</option>
		</select>
		<a href="#" style="cursor: help;" title="Allows sorting of retrieved content by a parameter selected. Defaults to date. ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="widg-post_status_div" style="display:none;">
		<label class="control-label span3" >Show By Status </label>
		<div class="controls span9">
		<select name="widg-post_status" id="widg-post_status" class="input-small">
		<option value="publish" selected="selected">Publish</option>
		<option value="pending">Pending</option>
		<option value="title">Draft</option>
		<option value="auto-draft">With no content</option>
		<option value="future" >Future</option>
		<option value="private">Private</option>
		<option value="trash">Trash</option>
		<option value="any">Any but excluded from search</option>
		</select>
		<a href="#" style="cursor: help;" title="Retrieves content by status, default value is publish ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div>

		<div class="control-group row-fluid">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
		<i class="icon-ban-circle"></i>Cancel</button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-widget" type="submit" value="Save">
		<i class="icon-save"></i>Save</button>
		</div>

		</fieldset>
		</form>

		<?php
}
?>
