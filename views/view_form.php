<?php
function wpas_add_shortcode_form($app_id)
{
	?>
		<form action="" method="post" id="shortcode-form" class="form-horizontal">
		<input type="hidden" id="app" name="app" value="">
		<input type="hidden" value="" name="shc" id="shc">
		<fieldset>
		<div class="well">
		<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="Views help you display entity content where and how you wanted on the frontend. If you'd like to sort, filter,and reformat content, you need to use views. Just put a view's shortcode on a page and you are good to go. Views can also be attached ta a search form if you need to display a search form and the results on a page."> HELP</a></div></div>
		<div class="control-group row-fluid">
		<label class="control-label span3">Name</label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-label" id="shc-label" type="text" placeholder="e.g. sc_products">
		<a href="#" title="General name for the view. By enclosing the view name in square brackets in a page, post or a text widget, you can display the content returned by the view shortcode's query. The view name should be all lowercase and use all letters, but numbers and underscores (not dashes!) should work fine too. Max 30 characters allowed. If the shortcode is used in a text widget or a page and the content has multiple pages, paginated navigation links are displayed. You can also add the custom taxonomies you defined as attributes to further filter the content. For example; [products product_line=&quot;Ships,Planes&quot; product_vendor=&quot;Autoart Studio Design&quot;]. " style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3">Attach to Entity</label>
		<div class="controls span9">
		<select id="shc-attach" name="shc-attach">
		</select><a href="#" title="Views must be attached to already defined entity. The attached entity's content are returned by the view after query filters applied." style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
                <label class="control-label span3"></label>
                <label class="checkbox span9">Enable paged navigation.
                <input type="checkbox" value="1" id="shc-sc_pagenav" name="shc-sc_pagenav">
                <a title="Enables pagination links." style="cursor: help;" href="#">
                <i class="icon-info-sign"></i></a>
                </label>
                </div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Layout</label>
		<div class="controls span9">
		<?php
		$initial_data='<table class="content-table" border=0 cellpadding=1 cellspacing=1><tbody><tr><td class="content-cell featured-image">!#featured_img_thumb#</td><td class="content-cell content-title">!#title#</td></tr><tr><td class="content-cell content-excerpt" colspan=2>!#excerpt#</td></tr></tbody></table>';

	$buttons['theme_advanced_buttons1'] = 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,link,unlink';
	$buttons['theme_advanced_buttons2'] = 'tablecontrols,code,mylistbox';

	$settings = array(
			'text_area_name'=>'shc-sc_layout',//name you want for the textarea
			'quicktags' => false,
			'media_buttons' => false,
    			'remove_linebreaks' => false,
			'textarea_rows' => 10,
			'language' => 'en',
			'tinymce' => $buttons,
			);
	$id = 'shc-sc_layout';//has to be lower case
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
		<a href="#" style="cursor: help;" title="It defines how the content will be displayed. You can also edit the source code and make further changes."><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Css </label>
		<div class="controls span9">
		<textarea class="input-xlarge" id="shc-sc_css" name="shc-sc_css" class="tinymce" placeholder="<?php echo esc_attr($default_css); ?>"></textarea>
		<a href="#" style="cursor: help;" title="The custom css code to be used when displaying the content. It is handy when you added custom classes in the layout editor and want to add css class definitions. You can leave this field blank and use a common css file for all.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Entities Per Page </label>
		<div class="controls span9">
		<input id="shc-sc_post_per_page" name="shc-sc_post_per_page" class="input-mini" type="text" placeholder="e.g. 16" value="" />
		<a href="#" style="cursor: help;" title="Specify the number of content block to show per page. Use any integer value or -1 to show all.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >List Order </label>
		<div class="controls span9">
		<select name="shc-sc_order" id="shc-sc_order" class="input-small">
		<option value="DESC" selected="selected">Descending</option>
		<option value="ASC">Ascending</option>
		</select>
		<a href="#" style="cursor: help;" title="Allows the content to be sorted ascending or descending by a parameter selected. Defaults to descending. ">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3" >Sort Retrieved Posts By </label>
		<div class="controls span9">
		<select name="shc-sc_orderby" id="shc-sc_orderby" class="input-small">
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
		<div class="control-group row-fluid">
		<label class="control-label span3" >Show By Status </label>
		<div class="controls span9">
		<select name="shc-sc_post_status" id="shc-sc_post_status" class="input-small">
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
		<button class="btn  btn-primary pull-right layout-buttons" id="save-shortcode" type="submit" value="Save">
		<i class="icon-save"></i>Save</button>
		</div>

		</fieldset>
		</form>

		<?php
}
?>
