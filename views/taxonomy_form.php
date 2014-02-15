<?php
function wpas_add_taxonomy_form()
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#txn-hierarchical').click(function() {
		if(jQuery(this).find('option:selected').val() == 1)
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
		if(jQuery(this).find('option:selected').val() == 1)
		{
                        jQuery('#txn-show-in-nav-menus-div').show();
                }
                else
                {
                        jQuery('#txn-show-in-nav-menus-div').hide();
                }
        });
	jQuery('#txn-rewrite').click(function() {
		if(jQuery(this).find('option:selected').val() == 0)
                {
                        jQuery('#txn-custom_rewrite_slug').attr('disabled',true);
                }
                if(jQuery(this).find('option:selected').val() == 1)
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
		<div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("A taxonomy is a way to group things together. It might contain only one attribute of interest to users.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
		
                <div class="control-group row-fluid">
						<label class="control-label req span3"><?php _e("Name","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-name" id="txn-name" type="text" placeholder="<?php _e("e.g. product_tag","wpas"); ?>">
						<a href="#" title="<?php _e(" General name for the taxonomy, usually singular. Name should be in slug form (must not contain capital letters or spaces or reserved words) and not more than 32 characters long. Previously used entity or taxonomy names are not allowed. Dashes and underscores are allowed.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                </div>

                <div class="control-group row-fluid">
						<label class="control-label req span3"><?php _e("Plural","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-label" id="txn-label" type="text" placeholder="<?php _e("e.g. Product Tags","wpas"); ?>"> <a href="#" title="<?php _e("Taxonomy label.  Used in the admin menu for displaying custom taxonomy.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                </div>

                <div class="control-group row-fluid">
                    <label class="control-label req span3"><?php _e("Singular","wpas"); ?></label>
                    <div class="controls span9">
                    <input class="input-xlarge" name="txn-singular-label" id="txn-singular-label" type="text" placeholder="<?php _e("e.g. Product Tag","wpas"); ?>">
                    <a href="#" title="<?php _e("Taxonomy Singular label. Used when a singular label is needed.","wpas"); ?>" style="cursor: help;">
                    <i class="icon-info-sign"></i></a>
                    </div>
                </div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Description","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="input-xlarge" id="txn-tax_desc" name="txn-tax_desc"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("A short, optional descriptive summary of what the taxonomy is. It will be displayed in the front-end forms if the taxonomy is used in a form layout. Leave it blank if you do not need help text for your taxonomy. Max 500 chars.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>

				<div class="control-group row-fluid">
                    <label class="control-label req span3"><?php _e("Attach to Entity","wpas"); ?></label>
                    <div class="controls span9">
<select id="txn-attach" name="txn-attach" multiple="multiple">
</select><a href="#" title="<?php _e("Select one or more entities your taxonomy will be attached to.","wpas"); ?>" style="cursor: help;"> <i class="icon-info-sign"></i></a>
					</div>
                </div>
	<div class="control-group row-fluid">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Required for Submit","wpas"); ?>
			<input name="txn-required" id="txn-required" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the taxonomy required. When you set a taxonomy required, users must assign at least one taxonomy value when they create an entity record.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Required for Search","wpas"); ?>
			<input name="txn-srequired" id="txn-srequired" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the taxonomy required. When you set a taxonomy required, users must assign at least one taxonomy value when they search entity records using the taxonomy.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
            
				<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Hierarchical","wpas"); ?></label>
			<div class="controls span9">
				<select name="txn-hierarchical" id="txn-hierarchical" class="input-mini">
					<option value="0" selected="selected"><?php _e("False","wpas"); ?></option>
					<option value="1"><?php _e("True","wpas"); ?></option>
				</select> <a href="#" title="<?php _e("Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.","wpas"); ?>" style="cursor: help;">
				<i class="icon-info-sign"></i></a> (<?php _e("default: False","wpas"); ?>)
			</div>
		</div>
				<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Display Type","wpas"); ?></label>
			<div class="controls span9">
				<select name="txn-display_type" id="txn-display_type" class="input-medium">
					<option value="multi" selected="selected"><?php _e("Multiple Select","wpas"); ?></option>
					<option value="single"><?php _e("Single Select","wpas"); ?></option>
				</select> <a href="#" title="<?php _e("Set to allow single or multiple taxonomy value entry.","wpas"); ?>" style="cursor: help;">
				<i class="icon-info-sign"></i></a> (<?php _e("default: Multiple Select","wpas"); ?>)
			</div>
		</div>
        <div class="control-group row-fluid" id="txn-values_div" >
        <label class="control-label span3"><?php _e("Values","wpas");?></label>
        <div class="controls span9">
        <textarea id="txn-values" name="txn-values" class="input-xlarge" rows="3" placeholder="e.g. blue{color blue}[color];red{color red}[color];white{color white}[color]" ></textarea>
        <a href="#" style="cursor: help;" title="<?php _e("Enter semicolon separated option values for the taxonomy. There must be only one semicolon between the values. Optiopnally Term descriptions and term parent can be entered using term{term-description}[term-parent] format. For example; Monkey{Monkey is a funny animal}[Animal]","wpas");?>">
        <i class="icon-info-sign"></i></a>
        </div>
</div>
        <div class="control-group row-fluid" id="txn-dflt_value_div" name="txn-dflt_value_div">
                        <label class="control-label span3"><?php _e("Default Value","wpas");?></label>
                        <div class="controls span9">
                        <input class="input-xlarge" name="txn-dflt_value" id="txn-dflt_value" type="text" placeholder="" value="" >
                        <a href="#" style="cursor: help;" title="<?php _e("Sets the default value or values separated by a semicolon for the taxonomy.","wpas");?>">
                        <i class="icon-info-sign"></i></a>
                        </div>
        </div>
		
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9"><label class="checkbox"><?php _e("Show Advanced Options","wpas"); ?>
		<input name="txn-advanced-option" id="txn-advanced-option" type="checkbox" value="1"/>
		</label>
		</div>
		</div>    
</div>
    <div id="txntabs" style="display:none;">
                <ul id="mytxnTab" class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#txntabs-1"><?php _e("Label Options","wpas"); ?></a></li>
                        <li><a data-toggle="tab" href="#txntabs-2" ><?php _e("Options","wpas"); ?></a></li>
                </ul>
        <div id="mytxnTabContent" class="tab-content">
           <div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("If you are unfamiliar with these labels and leave them empty they will be automatically created based on your taxonomy name and the default configuration.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
            <div id="txntabs-1" class="tab-pane fade in active">
		<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Menu Name","wpas"); ?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="txn-menu_name" id="txn-menu_name" type="text" placeholder="<?php _e("e.g. Product Tags","wpas"); ?>" value="" />
	<a href="#" style="cursor: help;" title="<?php _e("It defines the menu name text. This string is the name to give menu items. Defaults to value of taxonomy name.","wpas"); ?> ">
	<i class="icon-info-sign"></i></a>
	</div>
 </div>
                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Search Items","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-search_items"  id="txn-search_items" type="text" placeholder="<?php _e("e.g. Search Product Tags","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Search Items. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
					</div>
					<div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Popular Items","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-popular_items" id="txn-popular_items" type="text" placeholder="<?php _e("e.g. Popular Product Tags","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Popular Items. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
                    	</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("All Items","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-all_items" id="txn-all_items" type="text" placeholder="<?php _e("e.g. All Product Tags","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for All Items. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid" id="txn-parent_item_div" style="display:none;">
						<label class="control-label span3"><?php _e("Parent Item","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-parent_item" id="txn-parent_item" type="text" placeholder="<?php _e("e.g. Parent Product Tag","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Parent Item. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid" id="txn-parent_item_colon_div" style="display:none;">
						<label class="control-label span3"><?php _e("Parent Item:","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-parent_item_colon" id="txn-parent_item_colon" type="text" placeholder="<?php _e("e.g. Parent Product Tag:","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Parent Item:. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>
					<div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Edit Item","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-edit_item" id="txn-edit_item" type="text" placeholder="<?php _e("e.g. Edit Product Tag","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Edit Item. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Update Item","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-update_item" id="txn-update_item" type="text" placeholder="<?php _e("e.g. Update Product Tag","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Update Item. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Add New Item","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-add_new_item" id="txn-add_new_item" type="text" placeholder="<?php _e("e.g. Add New Product Tag","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Add New Item. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("New Item Name","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-new_item_name" id="txn-new_item_name" type="text" placeholder="<?php _e("e.g. New Product Tag Name","wpas"); ?>">
						 <a href="#" title="<?php _e("Custom taxonomy label for New Item Name. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>
					<div class="control-group row-fluid" id="txn-separate_items_with_comas_div">
						<label class="control-label span3"><?php _e("Separate Items with Commas","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-separate_items_with_comas" id="txn-separate_items_with_comas" type="text" placeholder="<?php _e("e.g. Separate product tags with commas","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Separate Items with Commas. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

                    <div class="control-group row-fluid" id="txn-add_or_remove_items_div">
						<label class="control-label span3"><?php _e("Add or Remove Items","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-add_or_remove_items" id="txn-add_or_remove_items" type="text" placeholder="<?php _e("e.g. Add or remove product tags","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Add or Remove Items. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>

            <div class="control-group row-fluid" id="txn-choose_from_most_used_div">
						<label class="control-label span3"><?php _e("Choose From Most Used","wpas"); ?></label>
						<div class="controls span9">
						<input class="input-xlarge" name="txn-choose_from_most_used" id="txn-choose_from_most_used" type="text" placeholder="<?php _e("e.g. Choose from the most used product tags","wpas"); ?>">
						<a href="#" title="<?php _e("Custom taxonomy label for Choose From Most Used. Used in the admin menu for displaying taxonomies.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>
						</div>
                    </div>
                </div>
		<div id="txntabs-2" class="tab-pane fade">
<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Available for Public","wpas"); ?></label>
	<div class="controls span9">
	<select name="txn-public" id="txn-public" class="input-mini" >
	<option selected="selected" value="1"><?php _e("True","wpas"); ?></option>
	<option value="0"><?php _e("False","wpas"); ?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Whether this taxonomy is intended to be used publicly either via the admin interface or by front-end users. -false- Taxonomy is not intended to be used publicly and should generally be unavailable in in the admin interface and on the front end unless explicitly planned for elsewhere. -true - Taxonomy is intended for public use. This includes on the front end and in the admin interface.","wpas"); ?>">
	<i class="icon-info-sign"></i></a> (<?php _e("default:True","wpas"); ?>)
	</div>
                                        </div>
                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Show UI","wpas"); ?></label>
						<div class="controls span9">
							<select name="txn-show_ui" id="txn-show_ui" class="input-mini">
								<option value="1" selected="selected"><?php _e("True","wpas"); ?></option>
								<option value="0"><?php _e("False","wpas"); ?></option>
							</select> <a href="#" title="<?php _e("Whether to generate a default UI for managing this taxonomy.","wpas"); ?>" style="cursor: help;">
							<i class="icon-info-sign"></i></a> (<?php _e("default:True","wpas"); ?>)
						</div>
                    </div>
                    <div class="control-group row-fluid" id="txn-show-in-nav-menus-div">
						<label class="control-label span3"><?php _e("Show In Menus","wpas"); ?></label>
						<div class="controls span9">
							<select name="txn-show_in_nav_menus" id="txn-show_in_nav_menus" class="input-mini">
								<option value="1" selected="selected"><?php _e("True","wpas"); ?></option>
								<option value="0"><?php _e("False","wpas"); ?></option>
							</select> <a href="#" title="<?php _e("True makes this taxonomy available for selection in navigation menus.","wpas"); ?>" style="cursor: help;">
							<i class="icon-info-sign"></i></a> (<?php _e("default:True","wpas"); ?>)
						</div>
                    </div>
                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Show In Tag Clouds","wpas"); ?></label>
						<div class="controls span9">
							<select name="txn-show_tagcloud" id="txn-show_tagcloud" class="input-mini">
								<option value="1" selected="selected"><?php _e("True","wpas"); ?></option>
								<option value="0"><?php _e("False","wpas"); ?></option>
							</select> <a href="#" title="<?php _e("Whether to allow the Tag Cloud widget to use this taxonomy.","wpas"); ?>" style="cursor: help;">
							<i class="icon-info-sign"></i></a> (<?php _e("default:True","wpas"); ?>)
						</div>
                    </div>                    
                    <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Query Var","wpas"); ?></label>
						<div class="controls span9">
							<select name="txn-query_var" id="txn-query_var" class="input-mini">
								<option value="1" selected="selected"><?php _e("True","wpas"); ?></option>
								<option value="0"><?php _e("False","wpas"); ?></option>
							</select>
							<a href="#" title="<?php _e("False to disable the query_var, set as string to use custom query_var instead of default which is the taxonomy's name.","wpas"); ?>" style="cursor: help;">
							<i class="icon-info-sign"></i></a> (<?php _e("default:True","wpas"); ?>)
						</div>
                    </div>
 					<div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Rewrite","wpas"); ?></label>
						<div class="controls span9">
							<select name="txn-rewrite" id="txn-rewrite" class="input-mini">
								<option value="1" selected="selected"><?php _e("True","wpas"); ?></option>
								<option value="0"><?php _e("False","wpas"); ?></option>
							</select>
							<a href="#" title="<?php _e("Set to false to prevent automatic URL rewriting a.k.a. pretty permalinks.","wpas"); ?>" style="cursor: help;"><i class="icon-info-sign"></i></a> (<?php _e("default:True","wpas"); ?>)
						</div>
                    </div>

             <div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Custom Rewrite Slug","wpas"); ?></label>
						<div class="controls span9">
						<input name="txn-custom_rewrite_slug" id="txn-custom_rewrite_slug" type="text" placeholder="<?php _e("e.g. product_tags","wpas"); ?>">
						<a href="#" title="<?php _e("Used as pretty permalink text (i.e. /tag/) - defaults to taxonomy's name slug.","wpas"); ?>" style="cursor: help;">
						<i class="icon-info-sign"></i></a>(<?php _e("default: taxonomy name","wpas"); ?>)
						</div>
                    </div>
            </div>
     	</div>
	</div>

					<div class="control-group row-fluid">
					   <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
					   <i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
					   <button class="btn  btn-primary pull-right layout-buttons" id="save-taxonomy" type="submit" value="Save">
					   <i class="icon-save"></i><?php _e("Save","wpas"); ?></button>
                	</div>

		</fieldset>
	</form>

<?php
}
?>
