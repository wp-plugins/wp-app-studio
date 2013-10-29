<?php
function wpas_add_taxonomy_form()
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#txn-hierarchical').click(function() {
		if(jQuery(this).find('option:selected').text() == 'True')
		{
			jQuery('#txn-parent_item_div').show();
			jQuery('#txn-parent_item_colon_div').show();
			jQuery('#txn-separate_items_with_comas_div').hide();
			jQuery('#txn-add_or_remove_items_div').hide();
			jQuery('#txn-choose_from_most_used_div').hide();
                }
                else
                {
                        jQuery('#txn-parent_item_div').hide();
                        jQuery('#txn-parent_item_colon_div').hide();
			jQuery('#txn-separate_items_with_comas_div').show();
			jQuery('#txn-add_or_remove_items_div').show();
			jQuery('#txn-choose_from_most_used_div').show();
                }
	});
	jQuery('#txn-advanced-option').click(function() {
                if(jQuery(this).attr('checked'))
                {
			jQuery('#txntabs').show();
                }
                else
                {
                        jQuery('#txntabs').hide();
                }
        });
	jQuery('#txn-show_ui').click(function() {
		if(jQuery(this).find('option:selected').text() == 'True')
		{
                        jQuery('#txn-show-in-nav-menus-div').show();
                }
                else
                {
                        jQuery('#txn-show-in-nav-menus-div').hide();
                }
        });
	jQuery('#txn-rewrite').click(function() {
		if(jQuery(this).find('option:selected').text() == 'False')
                {
                        jQuery('#txn-custom_rewrite_slug').attr('disabled',true);
                }
                if(jQuery(this).find('option:selected').text() == 'True')
                {
                        jQuery('#txn-custom_rewrite_slug').removeAttr('disabled');
                }
        });
		
});
</script>
<form action="" method="post" id="taxonomy-form" class="form-horizontal">
			<input type="hidden" value="" name="txn" id="txn">
        	<fieldset>
<div class="well">
		<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="A taxonomy is a way to group things together. It might contain only one attribute of interest to users."> HELP</a></div></div>
		
                <div class="control-group row-fluid">
						<label class="control-label span3">Name</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-name" id="txn-name" type="text" placeholder="e.g. product_tag">
						<a href="#" title=" General name for the taxonomy, usually singular. Name should be in slug form (must not contain capital letters or spaces or reserved words) and not more than 32 characters long. Previously used entity or taxonomy names are not allowed. Dashes and underscores are allowed. " style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                </div>

                <div class="control-group row-fluid">
						<label class="control-label span3">Plural Label</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-label" id="txn-label" type="text" placeholder="e.g. Product Tags"> <a href="#" title="Taxonomy label.  Used in the admin menu for displaying custom taxonomy." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                </div>

                <div class="control-group row-fluid">
                    <label class="control-label span3">Singular Label</label>
                    <div class="controls span9">
                    <input class="input-xlarge" name="txn-singular-label" id="txn-singular-label" type="text" placeholder="e.g. Product Tag"> <a href="#" title="Taxonomy Singular label.  Used when a singular label is needed." style="cursor: help;">
                    <i class="icon-info-sign"></i></a>
                    </div>
                </div>
		<div class="control-group row-fluid">
		<label class="control-label span3">Description</label>
		<div class="controls span9">
		<textarea class="input-xlarge" id="txn-tax_desc" name="txn-tax_desc"></textarea>
		<a href="#" style="cursor: help;" title="A short, optional descriptive summary of what the taxonomy is. It will be displayed in the front-end forms if the taxonomy is used in a form layout. Leave it blank if you do not need help text for your taxonomy. Max 500 chars.">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>

				<div class="control-group row-fluid">
                    <label class="control-label span3">Attach to Entity</label>
                    <div class="controls span9">
<select id="txn-attach" name="txn-attach" multiple="multiple">
</select><a href="#" title="Select one or more entities your taxonomy will be attached to." style="cursor: help;"> <i class="icon-info-sign"></i></a>
					</div>
                </div>
	<div class="control-group row-fluid">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Required for Submit
			<input name="txn-required" id="txn-required" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Makes the taxonomy required. When you set a taxonomy required, users must assign at least one taxonomy value when they create an entity record.">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Required for Search
			<input name="txn-srequired" id="txn-srequired" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Makes the taxonomy required. When you set a taxonomy required, users must assign at least one taxonomy value when they search entity records using the taxonomy.">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
            
				<div class="control-group row-fluid">
			<label class="control-label span3">Hierarchical</label>
			<div class="controls span9">
				<select name="txn-hierarchical" id="txn-hierarchical" class="input-mini">
					<option value="0" selected="selected">False</option>
					<option value="1">True</option>
				</select> <a href="#" title="Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags." style="cursor: help;">
				<i class="icon-info-sign"></i></a> (default: False)
			</div>
		</div>
				<div class="control-group row-fluid">
			<label class="control-label span3">Display Type</label>
			<div class="controls span9">
				<select name="txn-display_type" id="txn-display_type" class="input-medium">
					<option value="multi" selected="selected">Multiple Select</option>
					<option value="single">Single Select</option>
				</select> <a href="#" title="Set to allow single or multiple taxonomy value entry." style="cursor: help;">
				<i class="icon-info-sign"></i></a> (default: Multiple Select)
			</div>
		</div>
		
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9"><label class="checkbox">Show Advanced Options
		<input name="txn-advanced-option" id="txn-advanced-option" type="checkbox" value="1"/>
		</label>
		</div>
		</div>    
</div>
    <div id="txntabs" style="display:none;">
                <ul id="mytxnTab" class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#txntabs-1">Label Options</a></li>
                        <li><a data-toggle="tab" href="#txntabs-2" >Options</a></li>
                </ul>
        <div id="mytxnTabContent" class="tab-content">
           <div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="If you are unfamiliar with these labels and leave them empty they will be automatically created based on your taxonomy name and the default configuration."> HELP</a></div></div>
            <div id="txntabs-1" class="tab-pane fade in active">
		<div class="control-group row-fluid">
	<label class="control-label span3" >Menu Name</label>
	<div class="controls span9">
	<input class="input-xlarge" name="txn-menu_name" id="txn-menu_name" type="text" placeholder="e.g. Product Tags" value="" />
	<a href="#" style="cursor: help;" title="It defines the menu name text. This string is the name to give menu items. Defaults to value of taxonomy name ">
	<i class="icon-info-sign"></i></a>
	</div>
 </div>
                    <div class="control-group row-fluid">
						<label class="control-label span3">Search Items</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-search_items"  id="txn-search_items" type="text" placeholder="e.g. Search Product Tags"> <a href="#" title="Custom taxonomy label for Search Items. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
					</div>
					<div class="control-group row-fluid">
						<label class="control-label span3">Popular Items</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-popular_items" id="txn-popular_items" type="text" placeholder="e.g. Popular Product Tags"> <a href="#" title="Custom taxonomy label for Popular Items. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
                    	</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3">All Items</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-all_items" id="txn-all_items" type="text" placeholder="e.g. All Product Tags"> <a href="#" title="Custom taxonomy label for All Items. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid" id="txn-parent_item_div" style="display:none;">
						<label class="control-label span3">Parent Item</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-parent_item" id="txn-parent_item" type="text" placeholder="e.g. Parent Product Tag"> <a href="#" title="Custom taxonomy label for Parent Item. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid" id="txn-parent_item_colon_div" style="display:none;">
						<label class="control-label span3">Parent Item:</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-parent_item_colon" id="txn-parent_item_colon" type="text" placeholder="e.g. Parent Product Tag:"> <a href="#" title="Custom taxonomy label for Parent Item:. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>
					<div class="control-group row-fluid">
						<label class="control-label span3">Edit Item</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-edit_item" id="txn-edit_item" type="text" placeholder="e.g. Edit Product Tag"> <a href="#" title="Custom taxonomy label for Edit Item. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3">Update Item</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-update_item" id="txn-update_item" type="text" placeholder="e.g. Update Product Tag"> <a href="#" title="Custom taxonomy label for Update Item. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3">Add New Item</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-add_new_item" id="txn-add_new_item" type="text" placeholder="e.g. Add New Product Tag"> <a href="#" title="Custom taxonomy label for Add New Item. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3">New Item Name</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-new_item_name" id="txn-new_item_name" type="text" placeholder="e.g. New Product Tag Name"> <a href="#" title="Custom taxonomy label for New Item Name. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>
					<div class="control-group row-fluid" id="txn-separate_items_with_comas_div">
						<label class="control-label span3">Separate Items with Commas</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-separate_items_with_comas" id="txn-separate_items_with_comas" type="text" placeholder="e.g. Separate product tags with commas"> <a href="#" title="Custom taxonomy label for Separate Items with Commas. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid" id="txn-add_or_remove_items_div">
						<label class="control-label span3">Add or Remove Items</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-add_or_remove_items" id="txn-add_or_remove_items" type="text" placeholder="e.g. Add or remove product tags"> <a href="#" title="Custom taxonomy label for Add or Remove Items. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid" id="txn-choose_from_most_used_div">
						<label class="control-label span3">Choose From Most Used</label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-choose_from_most_used" id="txn-choose_from_most_used" type="text" placeholder="e.g. Choose from the most used product tags"> <a href="#" title="Custom taxonomy label for Choose From Most Used. Used in the admin menu for displaying taxonomies." style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>
                </div>
		<div id="txntabs-2" class="tab-pane fade">
<div class="control-group row-fluid">
	<label class="control-label span3" >Available for Public</label>
	<div class="controls span9">
	<select name="txn-public" id="txn-public" class="input-mini" >
	<option selected="selected" value="1">True</option>
	<option value="0">False</option>
	</select>
	<a href="#" style="cursor: help;" title="Whether this taxonomy is intended to be used publicly either via the admin interface or by front-end users. -false- Taxonomy is not intended to be used publicly and should generally be unavailable in in the admin interface and on the front end unless explicitly planned for elsewhere. -true - Taxonomy is intended for public use. This includes on the front end and in the admin interface.">
	<i class="icon-info-sign"></i></a> (default: True)
	</div>
                                        </div>
                    <div class="control-group row-fluid">
						<label class="control-label span3">Show UI</label>
						<div class="controls span9">
							<select name="txn-show_ui" id="txn-show_ui" class="input-mini">
								<option value="1" selected="selected">True</option>
								<option value="0">False</option>
							</select> <a href="#" title="Whether to generate a default UI for managing this taxonomy." style="cursor: help;">
							<i class="icon-info-sign"></i></a> (default: True)
						</div>
                    </div>
                    <div class="control-group row-fluid" id="txn-show-in-nav-menus-div">
						<label class="control-label span3">Show In Menus</label>
						<div class="controls span9">
							<select name="txn-show_in_nav_menus" id="txn-show_in_nav_menus" class="input-mini">
								<option value="1" selected="selected">True</option>
								<option value="0">False</option>
							</select> <a href="#" title="True makes this taxonomy available for selection in navigation menus." style="cursor: help;">
							<i class="icon-info-sign"></i></a> (default: True)
						</div>
                    </div>
                    <div class="control-group row-fluid">
						<label class="control-label span3">Show In Tag Clouds</label>
						<div class="controls span9">
							<select name="txn-show_tagcloud" id="txn-show_tagcloud" class="input-mini">
								<option value="1" selected="selected">True</option>
								<option value="0">False</option>
							</select> <a href="#" title="Whether to allow the Tag Cloud widget to use this taxonomy." style="cursor: help;">
							<i class="icon-info-sign"></i></a> (default: True)
						</div>
                    </div>                    
                    <div class="control-group row-fluid">
						<label class="control-label span3">Query Var</label>
						<div class="controls span9">
							<select name="txn-query_var" id="txn-query_var" class="input-mini">
								<option value="1" selected="selected">True</option>
								<option value="0">False</option>
							</select>
							<a href="#" title="False to disable the query_var, set as string to use custom query_var instead of default which is the taxonomy's name" style="cursor: help;">
							<i class="icon-info-sign"></i></a> (default: True)
						</div>
                    </div>
 					<div class="control-group row-fluid">
						<label class="control-label span3">Rewrite</label>
						<div class="controls span9">
							<select name="txn-rewrite" id="txn-rewrite" class="input-mini">
								<option value="1" selected="selected">True</option>
								<option value="0">False</option>
							</select>
							<a href="#" title="Set to false to prevent automatic URL rewriting a.k.a. pretty permalinks." style="cursor: help;"><i class="icon-info-sign"></i></a> (default: True)
						</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3">Custom Rewrite Slug</label>
						<div class="controls span9">
						<input name="txn-custom_rewrite_slug" id="txn-custom_rewrite_slug" type="text" placeholder="e.g. product_tags">
						<a href="#" title="Used as pretty permalink text (i.e. /tag/) - defaults to taxonomy's name slug." style="cursor: help;">
						<i class="icon-info-sign"></i></a>(default: taxonomy name)
						</div>
                    </div>
            </div>
     	</div>
	</div>

					<div class="control-group row-fluid">
					   <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
					   <i class="icon-ban-circle"></i>Cancel</button>
					   <button class="btn  btn-primary pull-right layout-buttons" id="save-taxonomy" type="submit" value="Save">
					   <i class="icon-save"></i>Save</button>
                	</div>

		</fieldset>
	</form>

<?php
}
?>
