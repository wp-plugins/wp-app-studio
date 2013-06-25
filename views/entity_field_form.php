<?php
function wpas_view_ent_fields($ent_name)
{
return '<div id="title-bar"><div class="row-fluid"><div class="span3"><i class="icon-columns icon-large pull-left"></i><h4>Attributes</h3></div>
                <div class="span9 field" id="add_field_entity">
<a class="btn btn-info  pull-right" href="#ent'. esc_attr($ent_name) . '" class="add-new" ><i class="icon-plus-sign"></i>Add New</a>
</div></div></div>';
}
function wpas_view_ent_fields_list($ent_field)
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
        foreach($ent_field as $key => $myfield)
        {
		if(isset($myfield['fld_required']) && $myfield['fld_required'] == 1)
		{
			$required = 'Y';
		}
		else
		{
			$required = 'N';
		}
                $ret .= '<li id="' . esc_attr($key) . '"><div id="field-row"><div class="row-fluid">
                                <div class="span1"><i class="icon-sort"></i></div>
                                <div class="span3" id="field-name">' . esc_html($myfield['fld_name']) . '</div>
                                <div class="span3" id="field-label">' . esc_html($myfield['fld_label']) . '</div>
                                <div class="span2">' . esc_html($myfield['fld_type']) . '</div>
                                <div class="span1">' . $required . '</div>
                                <div class="span1" id="edit-field"><a href="#' . esc_attr($key) . '">Edit</a></div>
                                <div class="span1" id="delete-field"><a href="#' . esc_attr($key) . '">Delete</a></div></div></div></li>';
        }
        $ret .= '</ul>';
	return $ret;
}

function wpas_list_ent_fields()
{
$app_id = $_GET['app_id'];
$ent_id = $_GET['ent_id'];
$app = wpas_get_app($app_id);
$attrs = Array();
$layout_attrs = "";

if(isset($app['entity'][$ent_id]['layout']) && is_array($app['entity'][$ent_id]['layout']))
{
	foreach($app['entity'][$ent_id]['layout'] as $mylayout)
	{
		if(isset($mylayout['tabs']))
		{
			foreach($mylayout['tabs'] as $mytab)
			{
				$layout_attrs .= $mytab['attr'] . ",";
			}
		}
		if(isset($mylayout['accs']))
		{	
			foreach($mylayout['accs'] as $myacc)
			{
				$layout_attrs .= $myacc['attr'] . ",";
			}
		}
	}
	$attrs = explode(",",rtrim($layout_attrs,","));
}

$response = "<ul class=\"ui-draggable\">
<li class=\"ui-draggable\"><div class=\"tabgrp\" id=\"tabgrp\"><div><i class=\"icon-check-empty\"></i>Tab Panel</div></div></li>
<li class=\"ui-draggable\"><div class=\"tab\"><div><i class=\"icon-folder-close\"></i>Tab</div></div></li>
<li class=\"ui-draggable\"><div class=\"accgrp\" id=\"accgrp\"><div><i class=\"icon-reorder\"></i>Accordion Panel</div></div></li>
<li class=\"ui-draggable\"><div class=\"acc\"><div><i class=\"icon-minus\"></i>Accordion</div></div></li>
</ul>
<div class=\"attr-bin\"><ul class=\"ui-draggable\">";

if(isset($app['entity'][$ent_id]['field']) && is_array($app['entity'][$ent_id]['field']))
{
	foreach($app['entity'][$ent_id]['field'] as $myfield)
	{
		if(!in_array($myfield['fld_label'],$attrs))
		{
		$response .= "<li class=\"ui-draggable\"><div class=\"tabattr\">" . esc_html($myfield['fld_label']) . "</div></li>";
		}

	}
}
$response .= "</ul></div>";

echo $response;
die();
}

function wpas_add_ent_field_form($app_id,$ent_id)
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
        var options_arr = ['checkbox_list','radio','select','multi_select','select_advanced'];
	var filterable_arr = ['textarea','wysiwyg','file','image'];
	var min_max_value_arr = ['decimal','digits_only','integer'];
	var min_max_length_arr = ['text','letters_with_punc','alphanumeric','letters_only','no_whitespace','textarea','password'];
	var min_max_words_arr = ['textarea'];
	var required_arr = ['date','datetime','time','wysiwyg','file','image','plupload_image','thickbox_image','color','hidden_constant','hidden_function','checkbox'];
	var clone_arr = ['image','plupload_image','thickbox_image','hidden_constant','hidden_function','checkbox','select_advanced'];

	jQuery.fn.changeValidateMsg = function(myItem){
		switch(myItem) {
		case 'email':
			jQuery('#validation-message').text('Validation Message: Please enter a valid email address.');		
			break;
		case 'url':
			jQuery('#validation-message').text('Validation Message: Please enter a valid URL.');		
			break;
		case 'decimal':
			jQuery('#validation-message').text('Validation Message: Please enter a valid number.');		
			break;
		case 'digits_only':
			jQuery('#validation-message').text('Validation Message: Please enter only digits.');		
			break;
		case 'credit_card':
			jQuery('#validation-message').text('Validation Message: Please enter a valid credit card number.');
			break;
		case 'phone_us':
			jQuery('#validation-message').text('Validation Message: Please enter a valid phone number.');
			break;
		case 'phone_uk':
			jQuery('#validation-message').text('Validation Message: Please enter a valid phone number.');
			break;
		case 'mobile_uk':
			jQuery('#validation-message').text('Validation Message: Please enter a valid mobile number.');
			break;
		case 'letters_with_punc':
			jQuery('#validation-message').text('Validation Message: Letters or punctuation only please.');
			break;
		case 'alphanumeric':
			jQuery('#validation-message').text('Validation Message: Letters, numbers, and underscores only please.');
			break;
		case 'letters_only':
			jQuery('#validation-message').text('Validation Message: Letters only please.');
			break;
		case 'no_whitespace':
			jQuery('#validation-message').text('Validation Message: No white space please.');
			break;
		case 'zipcode_us':
			jQuery('#validation-message').text('Validation Message: The specified US ZIP Code is invalid.');
			break;
		case 'zipcode_uk':
			jQuery('#validation-message').text('Validation Message: Please specify a valid postcode.');
			break;
		case 'integer':
			jQuery('#validation-message').text('Validation Message: A positive or negative non-decimal number please.');
			break;
		case 'vin_number_us':
			jQuery('#validation-message').text('Validation Message: The specified vehicle identification number (VIN) is invalid.');
			break;
		case 'ip4':
			jQuery('#validation-message').text('Validation Message: Please enter a valid IP v4 address.');
			break;
		case 'ip6':
			jQuery('#validation-message').text('Validation Message: Please enter a valid IP v6 address.');
			break;
		default:	
			jQuery('#validation-message').text('');		
			break;
		}
	
		if(myItem == 'plupload_image')
		{
			jQuery('#validation-options').show();		
			jQuery('#max-file-uploads').show();		
		}
		if(myItem == 'date')
		{
			jQuery('#validation-options').show();		
			jQuery('#date-format').show();
		}
		if(jQuery.inArray(myItem,min_max_value_arr) != -1)
                {
			jQuery('#validation-options').show();		
			jQuery('#min-max-value').show();		
                }
		if(jQuery.inArray(myItem,min_max_length_arr) != -1)
                {
			jQuery('#validation-options').show();		
			jQuery('#min-max-length').show();		
                }
		if(jQuery.inArray(myItem,min_max_words_arr) != -1)
                {
			jQuery('#validation-options').show();		
			jQuery('#min-max-words').show();		
                }
                if(jQuery.inArray(myItem,options_arr) != -1)
                {
                        jQuery('#fld_values_div').show();
                }
		if(jQuery.inArray(myItem,filterable_arr) != -1)
		{
                        jQuery('#fld_is_filterable_div').hide();
		}
		if(jQuery.inArray(myItem,required_arr) != -1)
		{
                        jQuery('#fld_required_div').hide();
		}
		if(jQuery.inArray(myItem,clone_arr) != -1)
		{
                        jQuery('#fld_clone_div').hide();
		}
		if(myItem == 'hidden_function')
		{
			jQuery('#fld_dflt_value_div').hide();
			jQuery('#fld_hidden_func_div').show();
		}

		if(jQuery('#validation-message').text() == '')
                {
                        jQuery('#validation-message').hide();           
                }
                else
                {
                        jQuery('#validation-message').show();           
                }
	}

        jQuery('#fld_type').click(function() {
		jQuery('#validation-options').hide();		
		jQuery('#max-file-uploads').hide();		
		jQuery('#date-format').hide();	
		jQuery('#min-max-value').hide();		
		jQuery('#min-max-length').hide();		
		jQuery('#min-max-words').hide();		
		jQuery('#fld_values_div').hide();
		jQuery('#fld_hidden_func_div').hide();
		jQuery('#fld_is_filterable_div').show();
		jQuery('#fld_required_div').show();
		jQuery('#fld_dflt_value_div').show();
		jQuery('#fld_clone_div').show();

		jQuery(this).changeValidateMsg(jQuery(this).val());
                
        });
});
</script>
<input type="hidden" id="app" name="app" value="<?php echo $app_id; ?>">
<input type="hidden" id="ent" name="ent" value="<?php echo $ent_id;  ?>">
<input type="hidden" id="ent_field" name="ent_field" value="">
<div class="well">
	<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="An attribute is a property or descriptor of an entity, for example, Customer Name is an attribute of the entity Customer."> HELP</a></div></div>
  <attributeset>
  	<div class="control-group row-fluid">
			<label class="control-label span3">Name</label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_name" id="fld_name" type="text" placeholder="e.g. product_name" value="">
			<a href="#" style="cursor: help;" title="General name for the attribute, single word, no spaces, all lower case. Underscores and dashes allowed ">
			<i class="icon-info-sign"></i></a>			
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3">Label</label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_label" id="fld_label" type="text" placeholder="e.g. Product Name" value="">
			<a href="#" style="cursor: help;" title="User friendly name for your attribute. It will appear on the EDIT page of the entity.">
			<i class="icon-info-sign"></i></a>                          
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3">Description</label>
			<div class="controls span9">
					<textarea id="fld_desc" name="fld_desc" class="input-xlarge" rows="3" placeholder="Write a brief description on how the attribute will be used." ></textarea>
					<a href="#" style="cursor: help;" title="Instructions or help-text related to your attribute.">
					<i class="icon-info-sign"></i></a>          		
			</div>
    </div>
	<div class="control-group row-fluid">
    <label class="control-label span3"></label>
	<div class="controls span9">
	<div id="fld_required_div" name="fld_required_div">
			<label class="checkbox">Required?
			<input name="fld_required" id="fld_required" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Makes the attribute required so it can not be blank. ">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	<div id="fld_is_filterable_div" name="fld_is_filterable_div">
			<label class="checkbox">Filterable?
            <input name="fld_is_filterable" id="fld_is_filterable" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Makes the attribute filterable in admin list page of the entity.">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	<div  id="fld_clone_div" name="fld_clone_div">
			<label class="checkbox">Cloneable?
           <input name="fld_clone" id="fld_clone" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Makes the attribute Cloneable so it can have multiple values. ">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	</div>
	<div class="control-group row-fluid" id="fld_dflt_value_div" name="fld_dflt_value_div">
			<label class="control-label span3">Default Value</label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_dflt_value" id="fld_dflt_value" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Sets a default value for the attribute.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3">Type</label>
			<div class="controls span9">
					<select name="fld_type" id="fld_type">
							<option selected="selected" value="">Please select</option>
							<option value="email">Email</option>
							<option value="url">URL</option>
							<option value="decimal">Decimal</option>
							<option value="digits_only">Digits Only</option>
							<option value="credit_card">Credit Card</option>
							<option value="phone_us">Phone US</option>
							<option value="phone_uk">Phone UK</option>
							<option value="mobile_uk">Mobile UK</option>
							<option value="letters_with_punc">Letters with Punctuation</option>
							<option value="alphanumeric">AlphaNumeric</option>
							<option value="letters_only">Letters Only</option>
							<option value="no_whitespace">No WhiteSpace</option>
							<option value="zipcode_us">Zipcode US</option>
							<option value="zipcode_uk">Postal Code UK</option>
							<option value="integer">Integer</option>
							<option value="vin_number_us">VIN Number US</option>
							<option value="ip4">IP Address V4</option>
							<option value="ip6">IP Address V6</option>
							<option value="date">Date</option>
							<option value="datetime">DateTime</option>
							<option value="time">Time</option>
							<option value="text">Text</option>
							<option value="textarea">Text Area</option>
							<option value="wysiwyg">Wysiwyg Editor</option>
							<option value="file">File</option>
							<option value="image">Image</option>
							<option value="plupload_image">Plupload Image</option>
							<option value="thickbox_image">Thickbox Image</option>
							<option value="color">Color Picker</option>
							<option value="hidden_constant">Hidden Constant</option>
							<option value="hidden_function">Hidden Function</option>
							<option value="password">Password</option>
							<option value="checkbox">Checkbox</option>
							<option value="radio">Radio Button</option>
							<option value="select">Select</option>
							<option value="select_advanced">Select Advanced</option>
							<option value="multi_select">Multi Select</option>
							<option value="checkbox_list">Checkbox List</option>
					</select>
			<a href="#" style="cursor: help;" title="Defines the attribute display and validation type. ">
			<i class="icon-info-sign"></i></a>      
			<span id="validation-message" class="label label-info" style="display:none;"> </span>
			</div>
	  </div>
	<div class="control-group row-fluid" name="fld_hidden_func_div" id="fld_hidden_func_div" style="display:none;">
			<label class="control-label span3">Hidden Function</label>
			<div class="controls span9">
			<select name="fld_hidden_func" id="fld_hidden_func">
			<option selected="selected" value="">Please select</option>
			<option value="user_login">Username</option>
			<option value="user_email">User Email</option>
			<option value="user_firstname">User Firstname</option>
			<option value="user_lastname">User Lastname</option>
			<option value="user_displayname">User Display Name</option>
			<option value="user_id">User ID</option>
			<option value="date_mm_dd_yyyy">Current Date (MM-DD-YYYY)</option>
			<option value="date_dd_mm_yyyy">Current Date (DD-MM-YYYY)</option>
			<option value="current_year">Current Year (YYYY)</option>
			<option value="current_month">Current Month Name (January)</option>
			<option value="current_month_num">Current Month (01)</option>
			<option value="current_day">Current Day (01)</option>
			<option value="now">Now (YYYY-MM-DD HH:mm:ss)</option>
			<option value="current_time">Current Time (HH:mm:ss)</option>
			</select>
			<a href="#" style="cursor: help;" title="Sets a default value for the attribute.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div id="validation-options" class="control-group row-fluid" style="display:none;">
	<label class="control-label span3">Validation Options</label>
	<div class="controls span9">  
	<div id="min-max-length" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Minimum Length</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_min_length" id="fld_min_length" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Create a minumum length for the attribute. Validation Message: Please enter at least X characters.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3">Maximum Length</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_length" id="fld_max_length" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Create a maximum length for the attribute. Validation Message: Please enter no more than X characters.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="min-max-value" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Minimum Value</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_min_value" id="fld_min_value" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Create a minumum value for the attribute. Validation Message: Please enter a value greater than or equal to X.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3">Maximum Value</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_value" id="fld_max_value" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Create a maximum value for the attribute. Validation Message: Please enter a value less than or equal to X.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="min-max-words" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Minimum Words</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_min_words" id="fld_min_words" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Create a minumum number of words for the attribute. Validation Message: Please enter at least X words.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3">Maximum Words</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_words" id="fld_max_words" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Create a maximum number of words for the attribute. Validation Message: Please enter X words or less.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="date-format" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Date Format</label>
			<div class="controls span9">
			<select name="fld_date_format" id="fld_date_format">
			<option value="" selected>Please select</option>
			<option value="yyyy_mm_dd">YYYY-MM-DD</option>
			<option value="dd_mm_yyyy">DD-MM-YYYY</option>
			</select>
			<a href="#" style="cursor: help;" title="Create a minumum value for the attribute.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="max-file-uploads" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Max File Uploads</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_file_uploads" id="fld_max_file_uploads" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Create a minumum value for the attribute.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	</div><!--validation-optiond-->
	</div>
	<div class="control-group row-fluid" id="fld_values_div" style="display:none;">
        <label class="control-label span3">Values</label>
        <div class="controls span9">
        <textarea id="fld_values" name="fld_values" class="input-xlarge" rows="3" placeholder="e.g. blue,red,white " ></textarea>
        <a href="#" style="cursor: help;" title="Enter comma separated option values for the field. There must be only one comma between the values. You can not put a comma at the end of the values as well.">
        <i class="icon-info-sign"></i></a>
        </div>
</div>
  </attributeset>
</div>

	<div class="control-group row-fluid">
		  <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
	   <button class="btn  btn-primary pull-right layout-buttons" id="save-entity-field" name="Save" type="submit">Save
	   </button>
	</div>
<?php
}
?>
