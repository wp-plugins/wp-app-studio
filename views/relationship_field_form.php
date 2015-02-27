<?php
function wpas_view_rel_fields($rel_name)
{
return '<div id="title-bar"><div class="row-fluid"><h4 class="span3"><i class="icon-columns"></i>' . __("Attributes","wpas") . '</h3>
                <div class="span9 field" id="add_field_rel">
<a class="btn btn-info  pull-right" href="#rel'. esc_attr($rel_name) . '" class="add-new" >
<i class="icon-plus-sign"></i>' . __("Add New","wpas") . '</a>
</div></div></div>';
}
function wpas_view_rel_fields_list($rel_field)
{
	$ret = '<div id="field-title"><div class="row-fluid"><div class="span1"></div>
        <div id="field-name" class="span3">' . __("Name","wpas") . '</div>
        <div id="field-label" class="span3">' . __("Label","wpas") . '</div>
        <div class="span2">' . __("Type","wpas") . '</div>
        <div class="span1">' . __("Required","wpas") . '</div>
        <div id="edit-field" class="span1"></div>
        <div id="delete-field" class="span1"></div>
        </div></div>';
        $ret .= '<ul id="fields-sort" class="sortable ui-sortable">';
        foreach($rel_field as $key => $myfield)
        {
		if(isset($myfield['rel_fld_required']) && $myfield['rel_fld_required'] == 1)
                {
                        $required = __("Y","wpas");
                }
                else
                {
                        $required = __("N","wpas");
                }

                $ret .= '<li id="' . $key . '"><div class="field-row"><div class="row-fluid">
                        <div class="span1"><i class="icon-sort"></i></div>
			<div class="span3" class="field-name">' . esc_html($myfield['rel_fld_name']) . '</div>
                        <div class="span3" class="field-label">' . esc_html($myfield['rel_fld_label']) . '</div>
                        <div class="span2">' . esc_html($myfield['rel_fld_type']) . '</div>
			<div class="span1">' . $required . '</div>
                        <div class="span1" id="edit-rel-field"><a href="#' . esc_attr($key) . '">' . __("Edit","wpas") . '</a></div>
                        <div class="span1" id="delete-rel-field"><a href="#' . esc_attr($key) . '">' . __("Delete","wpas") . '</a></div></div></div></li>';
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
<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("An attribute is a property or descriptor of a relationship. For example; quantity ordered is an attribute of the relationship between products and orders entities.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div>
                <fieldset>
<div class="control-group row-fluid">
      <label class="control-label req span3"><?php _e("Name","wpas"); ?></label>
     <div class="controls span9">
     <input name="rel_fld_name" id="rel_fld_name" type="text" placeholder="<?php _e("e.g quantity_ordered","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Single word, no spaces, all lower case. Underscores and dashes allowed","wpas"); ?>">
			<i class="icon-info-sign"></i></a>	
                                                </div>
                                                </div>
                                                <div class="control-group row-fluid">
                                                                        <label class="control-label req span3"><?php _e("Label","wpas"); ?></label>
                                                                        <div class="controls span9">
                                                                                <input name="rel_fld_label" id="rel_fld_label" type="text" placeholder="<?php _e("e.g Quantity Ordered","wpas");?>" value="" > 
                                                                                			<a href="#" style="cursor: help;" title="<?php _e("This is the name which will appear on the related relationship box of the admin EDIT page of the entity.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>                                  
                                                                         </div>
                                                </div>
                           <div class="control-group row-fluid">
                               <label class="control-label span3"><?php _e("Type","wpas"); ?></label>
                               <div class="controls span9">
                                    <select name="rel_fld_type" id="rel_fld_type">
                                           <option selected="selected" value="text"><?php _e("Text","wpas"); ?></option>
                                           <option value="textarea"><?php _e("Text Area","wpas"); ?></option>
                                           <option value="checkbox"><?php _e("Checkbox","wpas"); ?></option>
                                           <option value="checkbox_list"><?php _e("Checkbox List","wpas"); ?></option>
					   					   <option value="select"><?php _e("Select","wpas"); ?></option>
					   					   <option value="radio"><?php _e("Radio","wpas"); ?></option>
                                     </select>
                           <a href="#" style="cursor: help;" title="<?php _e("Attribute types defines how the entity attribute will be displayed on the admin edit page of the entity. ","wpas"); ?>">
			<i class="icon-info-sign"></i></a>                                             
                                                                        </div>
                                          </div>
<div class="control-group row-fluid">
<label class="control-label span3"><?php _e("Description","wpas"); ?></label>
                                                                        <div class="controls span9">
                                                                                <textarea id="rel_fld_desc" name="rel_fld_desc" class="input-xlarge" rows="3" placeholder="<?php _e("e.g please enter the quantity ordered.","wpas");?>"></textarea>
                                                                                <a href="#" style="cursor: help;" title="<?php _e("instructions for authors. shown when submitting data.","wpas"); ?>">
					<i class="icon-info-sign"></i></a>

                                                                        </div>
                                        </div>
                                   <div class="control-group row-fluid">
                                                                        <label class="control-label span3"></label>
                                                        <div class="controls span9">
                                                        <label class="checkbox"> <?php _e("Required","wpas"); ?>
                                                <input id="rel_fld_required" type="checkbox" value="1" name="rel_fld_required">
                                                       
                                                 <a href="#" style="cursor: help;" title="<?php _e("Makes the attribute required so it can not be blank.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
                                                        </label>
                                                        </div>
                                        </div>
<div class="control-group row-fluid" id="rel_fld_values_div" style="display:none;">
                                                                        <label class="control-label span3 req"><?php _e("Values","wpas"); ?></label>
                                                                        <div class="controls span9">
                                                                                <textarea id="rel_fld_values" name="rel_fld_values" class="input-xlarge" rows="3" placeholder="<?php _e("e.g. blue;red;white","wpas"); ?>"></textarea>
                                                                                <a href="#" style="cursor: help;" title="<?php _e("Enter semicolon separated option labels for the field. There must be only one semicolon between the values. Optionally, you can define value-label combinations using {Value}Label format. If the predined value does not exist, it is automatically created based on the label.","wpas"); ?>">
					<i class="icon-info-sign"></i></a>

                                                                        </div>
                                        </div>
                                        <div class="control-group row-fluid">
                                                                        <label class="control-label span3"><?php _e("Default Value","wpas"); ?></label>
                                                                        <div class="controls span9">
                                                                        <input name="rel_fld_dflt_value" id="rel_fld_dflt_value" type="text" placeholder="<?php _e("Default value of the field","wpas"); ?>" value="" >
                                                                        <a href="#" style="cursor: help;" title="<?php _e("Create a default value for the attribute.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
                                                                        </div>
                                        </div>
                </fieldset>
</div>

        <div class="control-group row-fluid">
              <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
           <button class="btn  btn-primary pull-right layout-buttons" id="save-relationship-field" name="Save" type="submit"><?php _e("Save","wpas"); ?></button>
        </div>
</form>
<?php
}
?>
