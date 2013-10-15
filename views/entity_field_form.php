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
        <div class="span1">Req</div>
        <div class="span1">Unique</div>
	<div class="span1"><div id="edit-field"></div><div id="delete-field"></div></div>
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
		if(isset($myfield['fld_uniq_id']) && $myfield['fld_uniq_id'] == 1)
		{
			$uniq_id = 'Y';
		}
		else
		{
			$uniq_id = 'N';
		}
                $ret .= '<li id="' . esc_attr($key) . '"><div id="field-row"><div class="row-fluid">
                                <div class="span1"><i class="icon-sort"></i></div>
                                <div class="span3" id="field-name">' . esc_html($myfield['fld_name']) . '</div>
                                <div class="span3" id="field-label">' . esc_html($myfield['fld_label']) . '</div>
                                <div class="span2">' . esc_html($myfield['fld_type']) . '</div>
                                <div class="span1">' . $required . '</div>
                                <div class="span1">' . $uniq_id . '</div>
                                <div class="span1"><div id="edit-field"><a href="#' . esc_attr($key) . '">Edit</a></div>
                                <div id="delete-field"><a href="#' . esc_attr($key) . '">Delete</a></div></div></div></div></li>';
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
jQuery(document).ready(function($) {
        var options_arr = ['checkbox_list','radio','select'];
	var filterable_arr = ['textarea','wysiwyg','file','image'];
	var min_max_value_arr = ['decimal','digits_only','integer'];
	var min_max_length_arr = ['text','letters_with_punc','alphanumeric','letters_only','no_whitespace','textarea','password'];
	var min_max_words_arr = ['textarea'];
	var required_arr = ['file','image','hidden_constant','hidden_function'];
	var not_uniq_arr = ['file','image','hidden_constant','hidden_function','checkbox','checkbox_list','radio','select','textarea','wysiwyg','password'];

	$.fn.changeValidateMsg = function(myItem){
		switch(myItem) {
		case 'email':
			$('#validation-message').text('Validation Message: Please enter a valid email address.');		
			break;
		case 'url':
			$('#validation-message').text('Validation Message: Please enter a valid URL.');		
			break;
		case 'decimal':
			$('#validation-message').text('Validation Message: Please enter a valid number.');		
			break;
		case 'digits_only':
			$('#validation-message').text('Validation Message: Please enter only digits.');		
			break;
		case 'credit_card':
			$('#validation-message').text('Validation Message: Please enter a valid credit card number.');
			break;
		case 'phone_us':
			$('#validation-message').text('Validation Message: Please enter a valid phone number.');
			break;
		case 'phone_uk':
			$('#validation-message').text('Validation Message: Please enter a valid phone number.');
			break;
		case 'mobile_uk':
			$('#validation-message').text('Validation Message: Please enter a valid mobile number.');
			break;
		case 'letters_with_punc':
			$('#validation-message').text('Validation Message: Letters or punctuation only please.');
			break;
		case 'alphanumeric':
			$('#validation-message').text('Validation Message: Letters, numbers, and underscores only please.');
			break;
		case 'letters_only':
			$('#validation-message').text('Validation Message: Letters only please.');
			break;
		case 'no_whitespace':
			$('#validation-message').text('Validation Message: No white space please.');
			break;
		case 'zipcode_us':
			$('#validation-message').text('Validation Message: The specified US ZIP Code is invalid.');
			break;
		case 'zipcode_uk':
			$('#validation-message').text('Validation Message: Please specify a valid postcode.');
			break;
		case 'integer':
			$('#validation-message').text('Validation Message: A positive or negative non-decimal number please.');
			break;
		case 'vin_number_us':
			$('#validation-message').text('Validation Message: The specified vehicle identification number (VIN) is invalid.');
			break;
		case 'ip4':
			$('#validation-message').text('Validation Message: Please enter a valid IP v4 address.');
			break;
		case 'ip6':
			$('#validation-message').text('Validation Message: Please enter a valid IP v6 address.');
			break;
		default:	
			$('#validation-message').text('');		
			break;
		}
		if(myItem == 'image')
		{
			$('#fld_dflt_value_div').hide();
			$('#fld_file_size_div').show();
			$('#fld_file_ext_div').show();
			$('#fld_image_thickbox_div').show();
			$('#validation-options').show();		
			$('#max-file-uploads').show();		
			$('#fld_file_ext').val('jpg,jpeg,png,gif');	
		}
		if(myItem == 'file')
		{
			$('#fld_dflt_value_div').hide();
			$('#fld_file_size_div').show();
			$('#fld_file_ext_div').show();
			$('#fld_image_thickbox_div').hide();
			$('#validation-options').show();		
			$('#max-file-uploads').show();		
			$('#fld_file_ext').val('');	
		}
		if(myItem == 'date' || myItem == 'datetime')
		{
			$('#date-format').show();
		}
		if(myItem == 'time' || myItem == 'datetime')
		{
			$('#time-format').show();
		}
		if($.inArray(myItem,min_max_value_arr) != -1)
                {
			$('#validation-options').show();		
			$('#min-max-value').show();		
                }
		if($.inArray(myItem,min_max_length_arr) != -1)
                {
			$('#validation-options').show();		
			$('#min-max-length').show();		
                }
		if($.inArray(myItem,min_max_words_arr) != -1)
                {
			$('#validation-options').show();		
			$('#min-max-words').show();		
                }
                if($.inArray(myItem,options_arr) != -1)
                {
                        $('#fld_values_div').show();
                }
                if($.inArray(myItem,['checkbox','checkbox_list','radio']) != -1)
                {
                        $('#fld_fa_chkd_div').show();
                        $('#fld_fa_unchkd_div').show();
			if(myItem == 'radio')
			{
				$('#fld_fa_chkd_val').attr('placeholder','icon-circle');
				$('#fld_fa_unchkd_val').attr('placeholder','icon-circle-blank');
			}
			else
			{
				$('#fld_fa_chkd_val').attr('placeholder','icon-checked');
				$('#fld_fa_unchkd_val').attr('placeholder','icon-checked-empty');
			}
                }
		if(myItem == 'select')
		{
			$('#fld_is_advanced_div').show();
			$('#fld_multiple_div').show();
		}
		if($.inArray(myItem,filterable_arr) != -1)
		{
                        $('#fld_is_filterable_div').hide();
			$('#fld_is_filterable').attr('checked',false);
		}
		if($.inArray(myItem,required_arr) != -1)
		{
                        $('#fld_required_div').hide();
                        $('#fld_required').attr('checked',false);
		}
		if(myItem == 'hidden_function')
		{
			$('#fld_dflt_value_div').hide();
			$('#fld_hidden_func_div').show();
		}
		if($.inArray(myItem,not_uniq_arr) != -1)
		{
                        $('#fld_uniq_id_div').hide();
                        $('#fld_uniq_id').attr('checked',false);
		}
		if(myItem == '')
		{
			$('#fld_uniq_id_div').hide();
                        $('#fld_uniq_id').attr('checked',false);
                        $('#fld_required_div').hide();
                        $('#fld_required').attr('checked',false);
                        $('#fld_is_filterable_div').hide();
			$('#fld_is_filterable').attr('checked',false);
			$('#fld_dflt_value_div').hide();
			$('#fld_dflt_value').val('');
		}
		if(myItem == 'checkbox')
		{
			$('#fld_dflt_checked_div').show();
			$('#fld_dflt_value_div').hide();
			$('#fld_dflt_value').val('');
		}
		
		
		if($('#validation-message').text() == '')
                {
                        $('#validation-message').hide();           
                }
                else
                {
                        $('#validation-message').show();           
                }
	}

	$(document).on('change','#fld_type',function(){
		$('#fld_dflt_checked_div').hide();
		$('#validation-options').hide();		
		$('#max-file-uploads').hide();		
		$('#date-format').hide();	
		$('#time-format').hide();	
		$('#min-max-value').hide();		
		$('#min-max-length').hide();		
		$('#min-max-words').hide();		
		$('#fld_values_div').hide();
		$('#fld_hidden_func_div').hide();
		$('#fld_is_filterable_div').show();
		$('#fld_required_div').show();
		$('#fld_dflt_value_div').show();
		$('#fld_file_size_div').hide();
		$('#fld_file_ext_div').hide();
		$('#fld_multiple_div').hide();
		$('#fld_is_advanced_div').hide();
		$('#fld_image_thickbox_div').hide();
		$('#fld_fa_chkd_div').hide();
		$('#fld_fa_unchkd_div').hide();
		$('#fld_uniq_id_div').show();
		$('#fld_required').attr('checked',false);
		$('#fld_uniq_id').attr('checked',false);
		$('#fld_required').attr('disabled',false);
		$(this).changeValidateMsg($(this).val());
        });

	$('#fld_uniq_id').click(function () {
		if($(this).attr('checked')){
			$('#fld_required').attr('checked',true);
			$('#fld_required').attr('disabled',true);
		}
		else
		{
			$('#fld_required').attr('checked',false);
			$('#fld_required').attr('disabled',false);
		}			
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
			<label class="control-label span3">Type</label>
			<div class="controls span9">
					<select name="fld_type" id="fld_type">
						<option selected="selected" value="">Please select</option>
						<option value="" style='font-style:italic;font-weight:bold;'>Text</option>
						<option value="alphanumeric" style='padding-left:2em;'>AlphaNumeric</option>
						<option value="color" style='padding-left:2em;'>Color Picker</option>
						<option value="credit_card" style='padding-left:2em;'>Credit Card</option>
						<option value="decimal" style='padding-left:2em;'>Decimal</option>
						<option value="digits_only" style='padding-left:2em;'>Digits Only</option>
						<option value="email" style='padding-left:2em;'>Email</option>
						<option value="integer" style='padding-left:2em;'>Integer</option>
						<option value="ip4" style='padding-left:2em;'>IP Address V4</option>
						<option value="ip6" style='padding-left:2em;'>IP Address V6</option>
						<option value="letters_only" style='padding-left:2em;'>Letters Only</option>
						<option value="letters_with_punc" style='padding-left:2em;'>Letters with Punctuation</option>
						<option value="mobile_uk" style='padding-left:2em;'>Mobile UK</option>
						<option value="no_whitespace" style='padding-left:2em;'>No WhiteSpace</option>
						<option value="password" style='padding-left:2em;'>Password</option>
						<option value="phone_uk" style='padding-left:2em;'>Phone UK</option>
						<option value="phone_us" style='padding-left:2em;'>Phone US</option>
						<option value="zipcode_uk" style='padding-left:2em;'>Postal Code UK</option>
						<option value="text" style='padding-left:2em;'>Text</option>
						<option value="url" style='padding-left:2em;'>URL</option>
						<option value="vin_number_us" style='padding-left:2em;'>VIN Number US</option>
						<option value="zipcode_us" style='padding-left:2em;'>Zipcode US</option>
						<option value="" style='font-style:italic;font-weight:bold;'>Date/Time</option>
						<option value="date" style='padding-left:2em;'>Date</option>
						<option value="datetime" style='padding-left:2em;'>DateTime</option>
						<option value="time" style='padding-left:2em;'>Time</option>
						<option value="" style='font-style:italic;font-weight:bold;'>Textarea</option>
						<option value="textarea" style='padding-left:2em;'>Text Area</option>
						<option value="wysiwyg" style='padding-left:2em;'>Wysiwyg Editor</option>
						<option value="" style='font-style:italic;font-weight:bold;'>Uploaders</option>
						<option value="file" style='padding-left:2em;'>File Uploader</option>
						<option value="image" style='padding-left:2em;'>Image Uploader</option>
						<option value="" style='font-style:italic;font-weight:bold;'>Hidden</option>
						<option value="hidden_constant" style='padding-left:2em;'>Hidden Constant</option>
						<option value="hidden_function" style='padding-left:2em;'>Hidden Function</option>
						<option value="" style='font-style:italic;font-weight:bold;'>Selectors</option>
						<option value="checkbox" style='padding-left:2em;'>Checkbox</option>
						<option value="checkbox_list" style='padding-left:2em;'>Checkbox List</option>
						<option value="radio" style='padding-left:2em;'>Radios</option>
						<option value="select" style='padding-left:2em;'>Select</option>
					</select>
			<a href="#" style="cursor: help;" title="Defines the attribute display and validation type. ">
			<i class="icon-info-sign"></i></a>      
			<span id="validation-message" class="label label-info" style="display:none;"> </span>
			</div>
	  </div>
	<div class="control-group" id="fld_uniq_id_div" name="fld_uniq_id_div">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Unique
			<input name="fld_uniq_id" id="fld_uniq_id" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="An identifier which is guaranteed to be unique among all identifiers used for those objects and for a specific purpose. Exp; VIN of a car uniquely identifies a car among other cars. A unique identifier is used in forms as a searchable dropdown to link related entities.">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_required_div" name="fld_required_div">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Required
			<input name="fld_required" id="fld_required" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Makes the attribute required so it can not be blank. ">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_is_filterable_div" name="fld_is_filterable_div">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Filterable
            <input name="fld_is_filterable" id="fld_is_filterable" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Makes the attribute filterable in admin list page of the entity.">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_is_advanced_div" name="fld_is_advanced_div" style="display:none;">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Advanced
            <input name="fld_is_advanced" id="fld_is_advanced" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Enables support for searching, remote data sets, and infinite scrolling of results.">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid" name="fld_file_size_div" id="fld_file_size_div" style="display:none;">
			<label class="control-label span3">Maximum File Size</label>
			<div class="controls span9">
			<input class="input-large" name="fld_file_size" id="fld_file_size" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Set maximum file size in kilobytes. exp. 100K. Leave it blank for no limit. Validation Message: Please upload no greater than X kbytes.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid" name="fld_file_ext_div" id="fld_file_ext_div" style="display:none;">
			<label class="control-label span3">Allowed Extensions</label>
			<div class="controls span9">
			<input class="input-large" name="fld_file_ext" id="fld_file_ext" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Sets the file extensions allowed to upload. exp. for files : pdf,txt for images: jpg,png. Leave it blank for all types. Validation Message: Please upload a valid file type.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group" id="fld_multiple_div" name="fld_multiple_div" style="display:none;">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Multiple
            <input name="fld_multiple" id="fld_multiple" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Allows users to select multiple values when set.">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_image_thickbox_div" name="fld_image_thickbox_div" style="display:none;">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Thickbox
            <input name="fld_image_thickbox" id="fld_image_thickbox" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Sets thickbox option.">
			<i class="icon-info-sign"></i></a>
			</label>
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
	<div id="date-format" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Date Format</label>
			<div class="controls span9">
			<select name="fld_date_format" id="fld_date_format">
			<option value="" selected="selected">Please select</option>
			<option value="mm-dd-yy">MM-DD-YYYY</option>
			<option value="yy-mm-dd">YYYY-MM-DD</option>
			<option value="dd-mm-yy">DD-MM-YYYY</option>
			<option value="mm/dd/yy">MM/DD/YYYY</option>
			<option value="yy/mm/dd">YYYY/MM/DD</option>
			<option value="dd/mm/yy">DD/MM/YYYY</option>
			</select>
			<a href="#" style="cursor: help;" title="Select a date format.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="time-format" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Time Format</label>
			<div class="controls span9">
			<select name="fld_time_format" id="fld_time_format">
			<option value="" selected="selected">Please select</option>
			<option value="hh:mm:ss">HH:mm:ss</option>
			<option value="hh:mm">HH:mm</option>
			</select>
			<a href="#" style="cursor: help;" title="Select a time format.">
			<i class="icon-info-sign"></i></a>
			</div>
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
	<div id="max-file-uploads" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3">Max File Uploads</label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_file_uploads" id="fld_max_file_uploads" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Sets the number of maximum allowable file uploads.">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	</div><!--validation-optiond-->
	</div>
	<div class="control-group row-fluid" id="fld_values_div" style="display:none;">
        <label class="control-label span3">Values</label>
        <div class="controls span9">
        <textarea id="fld_values" name="fld_values" class="input-xlarge" rows="3" placeholder="e.g. blue;red;white " ></textarea>
        <a href="#" style="cursor: help;" title="Enter semicolon separated option values for the field. There must be only one semicolon between the values. You can not put a semicolon at the end of the values as well.">
        <i class="icon-info-sign"></i></a>
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
	<div class="control-group row-fluid" id="fld_dflt_checked_div" name="fld_dflt_checked_div" style="display:none;">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox">Default Value
			<input name="fld_dflt_checked" id="fld_dflt_checked" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="Default is unchecked. ">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="fld_fa_chkd_div" name="fld_fa_chkd_div" style="display:none;">
			<label class="control-label span3">Font Awesome Checked Icon Class</label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_fa_chkd_val" id="fld_fa_chkd_val" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Sets font awesome web font icon class for selected values.">
			<i class="icon-info-sign"></i></a><a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_blank">Cheatsheet</a>
			</div>
	</div>
	<div class="control-group row-fluid" id="fld_fa_unchkd_div" name="fld_fa_unchkd_div" style="display:none;">
			<label class="control-label span3">Font Awesome Unchecked Icon Class</label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_fa_unchkd_val" id="fld_fa_unchkd_val" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="Sets font awesome web font icon class for unselected values.">
			<i class="icon-info-sign"></i></a><a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_blank">Cheatsheet</a>
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
