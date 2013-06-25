<?php
function wpas_view_rel_fields($rel_name)
{
return '<div id="title-bar"><div class="row-fluid"><h4 class="span3"><i class="icon-columns"></i>Attributes</h3>
                <div class="span9 field" id="add_field_rel">
<a class="btn btn-info  pull-right" href="#rel'. esc_attr($rel_name) . '" class="add-new" ><i class="icon-plus-sign"></i>Add New</a>
</div></div></div>';
}
function wpas_view_rel_fields_list($rel_field)
{
	$ret = '<div id="field-title"><div class="row-fluid"><div class="span1"></div>
        <div id="field-name" class="span3">Name</div>
        <div id="field-label" class="span3">Label</div>
        <div class="span2">Type</div>
        <div class="span1">Required</div>
        <div id="edit-field" class="span1"></div>
        <div id="delete-field" class="span1"></div>
        </div></div>';
        $ret .= '<ul id="fields-sort" class="sortable ui-sortable">';
        foreach($rel_field as $key => $myfield)
        {
		if(isset($myfield['rel_fld_required']) && $myfield['rel_fld_required'] == 1)
                {
                        $required = 'Y';
                }
                else
                {
                        $required = 'N';
                }

                $ret .= '<li id="' . $key . '"><div id="field-row"><div class="row-fluid">
                        <div class="span1"><i class="icon-sort"></i></div>
			<div class="span3" id="field-name">' . esc_html($myfield['rel_fld_name']) . '</div>
                        <div class="span3" id="field-label">' . esc_html($myfield['rel_fld_label']) . '</div>
                        <div class="span2">' . esc_html($myfield['rel_fld_type']) . '</div>
			<div class="span1">' . $required . '</div>
                        <div class="span1" id="edit-rel-field"><a href="#' . esc_attr($key) . '">Edit</a></div>
                        <div class="span1" id="delete-rel-field"><a href="#' . esc_attr($key) . '">Delete</a></div></div></div></li>';
        }
        $ret .= '</ul>';
	return $ret;
}
function wpas_add_rel_field_form($app_id)
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	var options_arr = ['checkbox_list','radio','select'];
        jQuery('#rel_fld_type').click(function() {
		
                if(jQuery.inArray(jQuery(this).val(),options_arr) != -1)
                {
                        jQuery('#rel_fld_values_div').show();
                }
                else
                {
                        jQuery('#rel_fld_values_div').hide();
                }
		if(jQuery(this).val() == 'checkbox')
		{
			jQuery('#rel_fld_dflt_value_div').hide();
		}
		else
		{
			jQuery('#rel_fld_dflt_value_div').show();
		}
        });
});
</script>

<form action="" method="post" id="rel-field-form" class="form-horizontal">
<input type="hidden" id="app" name="app" value="<?php echo esc_attr($app_id); ?>">
<input type="hidden" id="rel" name="rel" value="0">
<input type="hidden" id="rel_field" name="rel_field" value="">

<div class="well">
<div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="An attribute is a property or descriptor of a relationship, for example, Quantity Ordered is an attribute of the relationship between the products and the orders entities."> HELP</a></div>
                <fieldset>
<div class="control-group row-fluid">
                                                                        <label class="control-label span3">Name</label>
                                                                <div class="controls span9">
                                                                        <input name="rel_fld_name" id="rel_fld_name" type="text" placeholder="e.g quantity_ordered" value="" >
                                                                        			<a href="#" style="cursor: help;" title="Single word, no spaces, all lower case. Underscores and dashes allowed ">
			<i class="icon-info-sign"></i></a>	
                                                </div>
                                                </div>
                                                <div class="control-group row-fluid">
                                                                        <label class="control-label span3">Label</label>
                                                                        <div class="controls span9">
                                                                                <input name="rel_fld_label" id="rel_fld_label" type="text" placeholder="e.g Quantity Ordered" value="" > 
                                                                                			<a href="#" style="cursor: help;" title="This is the name which will appear on the related relationship box of the admin EDIT page of the entity. ">
			<i class="icon-info-sign"></i></a>                                  
                                                                         </div>
                                                </div>
                           <div class="control-group row-fluid">
                               <label class="control-label span3">Type</label>
                               <div class="controls span9">
                                    <select name="rel_fld_type" id="rel_fld_type">
                                           <option selected="selected" value="text">Text</option>
                                           <option value="textarea">Text Area</option>
                                           <option value="checkbox">Checkbox</option>
                                           <option value="checkbox_list">Checkbox List</option>
					   					   <option value="select">Select</option>
					   					   <option value="radio">Radio Button</option>
                                     </select>
                           <a href="#" style="cursor: help;" title="Attribute types defines how the entity attribute will be displayed on the admin edit page of the entity. ">
			<i class="icon-info-sign"></i></a>                                             
                                                                        </div>
                                          </div>
<div class="control-group row-fluid" id="rel_fld_values_div" style="display:none;">
                                                                        <label class="control-label span3">Values</label>
                                                                        <div class="controls span9">
                                                                                <textarea id="rel_fld_values" name="rel_fld_values" class="input-xlarge" rows="3" placeholder="e.g. blue,red,white " ></textarea>
                                                                                <a href="#" style="cursor: help;" title="Enter comma separated option values for the field. There must be only one comma between the values. You can not put a comma at the end of the values as well.">
					<i class="icon-info-sign"></i></a>

                                                                        </div>
                                        </div>

<div class="control-group row-fluid">
                                                                        <label class="control-label span3">Description</label>
                                                                        <div class="controls span9">
                                                                                <textarea id="rel_fld_desc" name="rel_fld_desc" class="input-xlarge" rows="3" placeholder="e.g please enter the quantity ordered." ></textarea>
                                                                                <a href="#" style="cursor: help;" title="instructions for authors. shown when submitting data. ">
					<i class="icon-info-sign"></i></a>

                                                                        </div>
                                        </div>
                                   <div class="control-group row-fluid">
                                                                        <label class="control-label span3">Required?</label>
                                                        <div class="controls span9">
                                                        <label class="radio inline">
                                                <input id="rel_fld_required" type="radio" value="1" name="rel_fld_required">Yes
                                                        </label>
                                                       
                                                        <label class="radio inline">
                                                <input id="rel_fld_required" type="radio" value="0" name="rel_fld_required" checked>No
                                                </label>
                                                 <a href="#" style="cursor: help;" title="Makes the attribute required so it can not be blank. ">
			<i class="icon-info-sign"></i></a>
                                                        </div>
                                        </div>
                                        <div class="control-group row-fluid">
                                                                        <label class="control-label span3">Default Value</label>
                                                                        <div class="controls span9">
                                                                        <input name="rel_fld_dflt_value" id="rel_fld_dflt_value" type="text" placeholder="Default value of the field" value="" >
                                                                        <a href="#" style="cursor: help;" title="Create a default value for the attribute.">
			<i class="icon-info-sign"></i></a>
                                                                        </div>
                                        </div>
                </fieldset>
</div>

        <div class="control-group row-fluid">
              <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
           <button class="btn  btn-primary pull-right layout-buttons" id="save-relationship-field" name="Save" type="submit">Save</button>
        </div>
</form>
<?php
}
?>
