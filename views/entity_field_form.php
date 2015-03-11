<?php
function wpas_view_ent_fields($ent_name,$ent)
{
	$html = '<div id="title-bar"><div class="row-fluid"><div class="span3"><i class="icon-columns icon-large pull-left"></i><h4>' . __("Attributes","wpas") . '</h3></div>';
	if(empty($ent['ent-inline-ent'])){
		$html .= '<div class="span9 field" id="add_field_entity">
<a class="btn btn-info  pull-right" href="#ent'. esc_attr($ent_name) . '" class="add-new" ><i class="icon-plus-sign"></i>' . __("Add New","wpas") . '</a></div></div></div>';
	}
	return $html;
}
function wpas_view_ent_fields_list($ent_field)
{
	$ret = '<div id="field-title"><div class="row-fluid"><div class="span1"></div>
        <div id="field-name" class="span3">' . __("Name","wpas") . '</div>
        <div id="field-label" class="span3">' . __("Label","wpas") . '</div>
        <div class="span2">' . __("Type","wpas") . '</div>
        <div class="span1">' . __("Req","wpas") . '</div>
        <div class="span1">' . __("Unique","wpas") . '</div>
	<div class="span1"><div id="edit-field"></div><div id="delete-field"></div></div>
	</div></div>';
	$ret_fields = '<ul id="fields-sort" class="sortable ui-sortable">';
	$ret_builtin = '<ul id="builtin-fields-sort">';
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
		if(isset($myfield['fld_uniq_id']) && $myfield['fld_uniq_id'] == 1 || ($myfield['fld_type'] == 'hidden_function' && in_array($myfield['fld_hidden_func'],Array('unique_id','autoinc'))))
		{
			$uniq_id = 'Y';
		}
		else
		{
			$uniq_id = 'N';
		}
		if(isset($myfield['fld_builtin']) && $myfield['fld_builtin'] == 1)
		{
			$ret_builtin .= '<li id="' . esc_attr($key) . '"><div class="field-blt-row"><div class="row-fluid">
			<div class="span1"></div>
			<div class="span3" id="field-name">' . esc_html($myfield['fld_name']) . '</div>
			<div class="span3" id="field-label">' . esc_html($myfield['fld_label']) . '</div>
			<div class="span2">' . esc_html($myfield['fld_type']) . '</div>
			<div class="span1">' . $required . '</div>
			<div class="span1">' . $uniq_id . '</div>
			<div class="span1"><div id="edit-field"><a href="#' . esc_attr($key) . '">' . __("Edit","wpas") . '</a></div>
			</div></div></div></li>';
		}
		else
		{
			$ret_fields .= '<li id="' . esc_attr($key) . '"><div class="field-row"><div class="row-fluid">
			<div class="span1">' . '<i class="icon-sort"></i></div>
			<div class="span3" id="field-name">' . esc_html($myfield['fld_name']) . '</div>
			<div class="span3" id="field-label">' . esc_html($myfield['fld_label']) . '</div>
			<div class="span2">' . esc_html($myfield['fld_type']) . '</div>
			<div class="span1">' . $required . '</div>
			<div class="span1">' . $uniq_id . '</div>
			<div class="span1"><div id="edit-field"><a href="#' . esc_attr($key) . '">' . __("Edit","wpas") . '</a></div>
			<div id="delete-field"><a href="#' . esc_attr($key) . '">' . __("Delete","wpas") . '</a></div></div></div></div></li>';
		}
	}
        $ret .= $ret_builtin . '</ul>';
        $ret .= $ret_fields . '</ul>';
	return $ret;
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
	var srequired_arr = ['file','image'];
	var not_uniq_arr = ['file','image','hidden_constant','hidden_function','checkbox','checkbox_list','radio','select','textarea','wysiwyg','password'];

	$.fn.changeValidateMsg = function(myItem,page_type){
		switch(myItem) {
		case 'email':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid email address.","wpas");?>');		
			break;
		case 'url':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid URL.","wpas");?>');		
			break;
		case 'decimal':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid number.","wpas");?>');		
			break;
		case 'digits_only':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter only digits.","wpas");?>');		
			break;
		case 'credit_card':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid credit card number.","wpas");?>');
			break;
		case 'phone_us':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid phone number.","wpas");?>');
			break;
		case 'phone_uk':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid phone number.","wpas");?>');
			break;
		case 'mobile_uk':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid mobile number.","wpas");?>');
			break;
		case 'letters_with_punc':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Letters or punctuation only please.","wpas");?>');
			break;
		case 'alphanumeric':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Letters, numbers, and underscores only please.","wpas");?>');
			break;
		case 'letters_only':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Letters only please.","wpas");?>');
			break;
		case 'no_whitespace':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("No white space please.","wpas");?>');
			break;
		case 'zipcode_us':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("The specified US ZIP Code is invalid.","wpas");?>');
			break;
		case 'zipcode_uk':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please specify a valid postcode.","wpas");?>');
			break;
		case 'integer':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("A positive or negative non-decimal number please.","wpas");?>');
			break;
		case 'vin_number_us':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("The specified vehicle identification number (VIN) is invalid.","wpas");?>');
			break;
		case 'ip4':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid IP v4 address.","wpas");?>');
			break;
		case 'ip6':
			$('#validation-message').text('<?php _e("Validation Message:","wpas") . ' ' . _e("Please enter a valid IP v6 address.","wpas");?>');
			break;
		default:	
			$('#validation-message').text('');		
			break;
		}
		$('#fld_is_filterable_div').show();
		$('#fld_required_div').show();
		$('#fld_srequired_div').show();
		$('#fld_dflt_value_div').show();
		$('#fld_uniq_id_div').show();
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
		if(myItem == 'date')
		{
			$('#date-format').show();
			$('#fld_dflt_value').datetimepicker("destroy");
			$('#fld_dflt_value').datepicker({dateFormat:'yy-mm-dd'});
		}
 		else if(myItem == 'datetime')
		{
			$('#date-format').show();
			$('#time-format').show();
			$('#fld_dflt_value').datetimepicker("destroy");
			$('#fld_dflt_value').datetimepicker({dateFormat:'yy-mm-dd',timeFormat:'hh:mm'});
		}
		else if(myItem == 'time')
		{
			$('#time-format').show();
			$('#fld_dflt_value').datetimepicker("destroy");
			$('#fld_dflt_value').datetimepicker({timeFormat:'hh:mm',dateFormat: '',timeOnly:true});
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
				$('#fld_fa_chkd_val').attr('placeholder','fa-circle');
				$('#fld_fa_unchkd_val').attr('placeholder','fa-circle-o');
			}
			else
			{
				$('#fld_fa_chkd_val').attr('placeholder','fa-check-square-o');
				$('#fld_fa_unchkd_val').attr('placeholder','fa-square-o');
			}
                }
		if(myItem == 'text' || myItem == 'textarea')
		{
			$('#fld_allow_html_div').show();
		}
		else
		{
			$('#fld_allow_html_div').hide();
		}
		if(myItem == 'select')
		{
			$('#fld_is_advanced_div').show();
			$('#fld_multiple_div').show();
		}
		if(myItem == 'user')
		{
			if(page_type != 'edit'){
				app_id = $('input#app').val();
				$.get(ajaxurl,{action:'wpas_get_roles',type:'user',app_id:app_id}, function(response){
					$('#fld_limit_user_role').html(response);
					$('#fld_limit_user_role_div').show();
				});
			}
			$('#fld_is_advanced_div').show();
			$('#fld_multiple_div').hide();
		}
		else
		{
			$('#fld_limit_user_role_div').hide();
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
		if($.inArray(myItem,srequired_arr) != -1)
		{
                        $('#fld_srequired_div').hide();
                        $('#fld_srequired').attr('checked',false);
		}
		if(myItem == 'hidden_function')
		{
			$('#fld_dflt_value_div').hide();
			$('#fld_hidden_func_div').show();
			$('#fld_searchable_div').show();
		}
		if(myItem == 'hidden_constant')
		{
			$('#fld_searchable_div').show();
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
                        $('#fld_srequired_div').hide();
                        $('#fld_required').attr('checked',false);
                        $('#fld_srequired').attr('checked',false);
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
		$(this).initAttr();

		$('#fld_dflt_value').val('');
		$('#fld_required').attr('checked',false);
		$('#fld_srequired').attr('checked',false);
		$('#fld_uniq_id').attr('checked',false);
		$('#fld_required').attr('disabled',false);
		$('#fld_srequired').attr('disabled',false);

		$(this).changeValidateMsg($(this).val(),'');
        });
	$.fn.initAttr = function(){
		/*
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
		$('#fld_searchable_div').hide();
		$('#fld_is_filterable_div').show();
		$('#fld_required_div').show();
		$('#fld_srequired_div').show();
		$('#fld_dflt_value_div').show();
		$('#fld_file_size_div').hide();
		$('#fld_file_ext_div').hide();
		$('#fld_multiple_div').hide();
		$('#fld_is_advanced_div').hide();
		$('#fld_image_thickbox_div').hide();
		$('#fld_fa_chkd_div').hide();
		$('#fld_fa_unchkd_div').hide();
		$('#fld_uniq_id_div').show();
		$('#fld_allow_html_div').hide();
		$('#fld_limit_user_role_div').hide(); */
		$('#fld_dflt_checked_div').hide();
		$('#fld_allow_html_div').hide();
		$('#validation-message').hide();
		$('#validation-options').hide();
		$('#fld_values_div').hide();
		$('#fld_hidden_func_div').hide();
		$('#fld_searchable_div').hide();
		$('#fld_dflt_value_div').hide();
		$('#max-file-uploads').hide();
		$('#date-format').hide();
		$('#time-format').hide();
		$('#min-max-value').hide();
		$('#min-max-length').hide();
		$('#min-max-words').hide();
		$('#fld_file_size_div').hide();
		$('#fld_required_div').hide();
		$('#fld_srequired_div').hide();
		$('#fld_uniq_id_div').hide();
		$('#fld_is_filterable_div').hide();
		$('#fld_multiple_div').hide();
		$('#fld_is_advanced_div').hide();
		$('#fld_file_ext_div').hide();
		$('#fld_image_thickbox_div').hide();
		$('#fld_limit_user_role_div').hide();
		$('#fld_autoinc_field_div').hide();
	}
	$.fn.changeReqMult = function(uniq){
		if(uniq == 1)
		{
			$('#fld_required').attr('checked',true);
			$('#fld_required').attr('disabled',true);
		}
		else
		{
			$('#fld_required').attr('checked',false);
			$('#fld_required').attr('disabled',false);
		}
	}			
	$('#fld_uniq_id').click(function () {
		if($(this).attr('checked')){
			$(this).changeReqMult(1);
		}
		else
		{
			$(this).changeReqMult(0);
		}
	});
	$.fn.showAutoInc = function(type,show){
		
		if(show == 'autoinc'){
			$('#fld_autoinc_field_div').show();
		}
		else {
			$('#fld_autoinc_field_div').hide();
		}
		if(type == 'add'){
			$('#fld_autoinc_start').val(1);
			$('#fld_autoinc_incr').val(1);
		}
	}
	$('#fld_hidden_func').change(function (){
		$(this).showAutoInc('add',$(this).val());
	});
	
});
</script>
<input type="hidden" id="app" name="app" value="<?php echo $app_id; ?>">
<input type="hidden" id="ent" name="ent" value="<?php echo $ent_id;  ?>">
<input type="hidden" id="ent_field" name="ent_field" value="">
<input type="hidden" id="fld_builtin" name="fld_builtin" value="0">
<div class="well">
	<div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("An attribute is a property or descriptor of an entity, for example, Customer Name is an attribute of the entity Customer.","wpas");?>"><?php _e("HELP","wpas");?></a></div></div>
  <fieldset>
  	<div class="control-group row-fluid">
			<label class="control-label req span3"><?php _e("Name","wpas");?></label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_name" id="fld_name" type="text" placeholder="<?php _e("e.g. product_name","wpas");?>" value="">
			<a href="#" style="cursor: help;" title="<?php _e("General name for the attribute, single word, no spaces, all lower case. Underscores and dashes allowed","wpas");?>">
			<i class="icon-info-sign"></i></a>			
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label req span3"><?php _e("Label","wpas");?></label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_label" id="fld_label" type="text" placeholder="<?php _e("e.g. Product Name","wpas");?>" value="">
			<a href="#" style="cursor: help;" title="<?php _e("User friendly name for your attribute. It will appear on the EDIT page of the entity.","wpas");?>">
			<i class="icon-info-sign"></i></a>                          
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Description","wpas");?></label>
			<div class="controls span9">
					<textarea id="fld_desc" name="fld_desc" class="wpas-std-textarea" placeholder="<?php _e("Write a brief description on how the attribute will be used.","wpas");?>" ></textarea>
					<a href="#" style="cursor: help;" title="<?php _e("Instructions or help-text related to your attribute.","wpas");?>">
					<i class="icon-info-sign"></i></a>          		
			</div>
    </div>
	<div class="control-group row-fluid">
			<label class="control-label req span3"><?php _e("Type","wpas");?></label>
			<div class="controls span9">
					<select name="fld_type" id="fld_type">
						<option selected="selected" value=""><?php _e("Please select","wpas");?></option>
						<option value="" style='font-style:italic;font-weight:bold;'><?php _e("Text","wpas");?></option>
						<option value="alphanumeric" style='padding-left:2em;'><?php _e("AlphaNumeric","wpas");?></option>
						<option value="color" style='padding-left:2em;'><?php _e("Color Picker","wpas");?></option>
						<option value="credit_card" style='padding-left:2em;'><?php _e("Credit Card","wpas");?></option>
						<option value="decimal" style='padding-left:2em;'><?php _e("Decimal","wpas");?></option>
						<option value="digits_only" style='padding-left:2em;'><?php _e("Digits Only","wpas");?></option>
						<option value="email" style='padding-left:2em;'><?php _e("Email","wpas");?></option>
						<option value="integer" style='padding-left:2em;'><?php _e("Integer","wpas");?></option>
						<option value="ip4" style='padding-left:2em;'><?php _e("IP Address V4","wpas");?></option>
						<option value="ip6" style='padding-left:2em;'><?php _e("IP Address V6","wpas");?></option>
						<option value="letters_only" style='padding-left:2em;'><?php _e("Letters Only","wpas");?></option>
						<option value="letters_with_punc" style='padding-left:2em;'><?php _e("Letters with Punctuation","wpas");?></option>
						<option value="mobile_uk" style='padding-left:2em;'><?php _e("Mobile UK","wpas");?></option>
						<option value="no_whitespace" style='padding-left:2em;'><?php _e("No WhiteSpace","wpas");?></option>
						<option value="password" style='padding-left:2em;'><?php _e("Password","wpas");?></option>
						<option value="phone_uk" style='padding-left:2em;'><?php _e("Phone UK","wpas");?></option>
						<option value="phone_us" style='padding-left:2em;'><?php _e("Phone US","wpas");?></option>
						<option value="zipcode_uk" style='padding-left:2em;'><?php _e("Postal Code UK","wpas");?></option>
						<option value="text" style='padding-left:2em;'><?php _e("Text","wpas");?></option>
						<option value="url" style='padding-left:2em;'><?php _e("URL","wpas");?></option>
						<option value="vin_number_us" style='padding-left:2em;'><?php _e("VIN Number US","wpas");?></option>
						<option value="zipcode_us" style='padding-left:2em;'><?php _e("Zipcode US","wpas");?></option>
						<option value="" style='font-style:italic;font-weight:bold;'><?php _e("Date/Time","wpas");?></option>
						<option value="date" style='padding-left:2em;'><?php _e("Date","wpas");?></option>
						<option value="datetime" style='padding-left:2em;'><?php _e("DateTime","wpas");?></option>
						<option value="time" style='padding-left:2em;'><?php _e("Time","wpas");?></option>
						<option value="" style='font-style:italic;font-weight:bold;'><?php _e("Textarea","wpas");?></option>
						<option value="textarea" style='padding-left:2em;'><?php _e("Text Area","wpas");?></option>
						<option value="wysiwyg" style='padding-left:2em;'><?php _e("Wysiwyg Editor","wpas");?></option>
						<option value="" style='font-style:italic;font-weight:bold;'><?php _e("Uploaders","wpas");?></option>
						<option value="file" style='padding-left:2em;'><?php _e("File Uploader","wpas");?></option>
						<option value="image" style='padding-left:2em;'><?php _e("Image Uploader","wpas");?></option>
						<option value="" style='font-style:italic;font-weight:bold;'><?php _e("Hidden","wpas");?></option>
						<option value="hidden_constant" style='padding-left:2em;'><?php _e("Hidden Constant","wpas");?></option>
						<option value="hidden_function" style='padding-left:2em;'><?php _e("Hidden Function","wpas");?></option>
						<option value="" style='font-style:italic;font-weight:bold;'><?php _e("Selectors","wpas");?></option>
						<option value="checkbox" style='padding-left:2em;'><?php _e("Checkbox","wpas");?></option>
						<option value="checkbox_list" style='padding-left:2em;'><?php _e("Checkbox List","wpas");?></option>
						<option value="radio" style='padding-left:2em;'><?php _e("Radios","wpas");?></option>
						<option value="select" style='padding-left:2em;'><?php _e("Select","wpas");?></option>
						<option value="user" style='padding-left:2em;'><?php _e("User List","wpas");?></option>
					</select>
			<a href="#" style="cursor: help;" title="<?php _e("Defines the attribute display and validation type.","wpas");?>">
			<i class="icon-info-sign"></i></a>      
			<span id="validation-message" class="label label-info" style="display:none;"> </span>
			</div>
	  </div>
	<div class="control-group" id="fld_uniq_id_div" name="fld_uniq_id_div">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Unique","wpas");?>
			<input name="fld_uniq_id" id="fld_uniq_id" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("An identifier which is guaranteed to be unique among all identifiers used for those objects and for a specific purpose. Exp; VIN of a car uniquely identifies a car among other cars. A unique identifier is used in forms as a searchable dropdown to link related entities.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_required_div" name="fld_required_div">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Required for Submit","wpas");?>
			<input name="fld_required" id="fld_required" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the attribute required so it can not be blank.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_srequired_div" name="fld_srequired_div">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Required for Search","wpas");?>
			<input name="fld_srequired" id="fld_srequired" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the attribute required for search form submissions so it can not be blank.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_is_filterable_div" name="fld_is_filterable_div">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Filterable","wpas");?>
            <input name="fld_is_filterable" id="fld_is_filterable" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the attribute filterable in admin list page of the entity.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_allow_html_div" name="fld_allow_html_div">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Allow Html","wpas");?>
            <input name="fld_allow_html" id="fld_allow_html" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Allows html tags to be entered. When unchecked html tags are escaped.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_is_advanced_div" name="fld_is_advanced_div" style="display:none;">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Advanced","wpas");?>
            <input name="fld_is_advanced" id="fld_is_advanced" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Enables support for searching, remote data sets, and infinite scrolling of results.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid" name="fld_file_size_div" id="fld_file_size_div" style="display:none;">
			<label class="control-label span3"><?php _e("Maximum File Size","wpas");?></label>
			<div class="controls span9">
			<input class="input-large" name="fld_file_size" id="fld_file_size" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Set maximum file size in kilobytes. Only numbers allowed. Leave it blank for no limit.","wpas");?> <?php _e("Validation Message:","wpas") . ' ' . _e("Please upload no greater than X kbytes.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid" name="fld_file_ext_div" id="fld_file_ext_div" style="display:none;">
			<label class="control-label span3"><?php _e("Allowed Extensions","wpas");?></label>
			<div class="controls span9">
			<input class="input-large" name="fld_file_ext" id="fld_file_ext" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Sets the file extensions allowed to upload. exp. for files : pdf,txt for images: jpg,png. Leave it blank for all types.","wpas") .' '. _e("Validation Message:","wpas") . ' ' . _e("Please upload a valid file type.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group" id="fld_multiple_div" name="fld_multiple_div" style="display:none;">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Multiple","wpas");?>
            <input name="fld_multiple" id="fld_multiple" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Allows users to select multiple values when set.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group" id="fld_image_thickbox_div" name="fld_image_thickbox_div" style="display:none;">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Thickbox","wpas");?>
            <input name="fld_image_thickbox" id="fld_image_thickbox" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Sets thickbox option.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid" name="fld_hidden_func_div" id="fld_hidden_func_div" style="display:none;">
			<label class="control-label span3"><?php _e("Hidden Function","wpas");?></label>
			<div class="controls span9">
			<select name="fld_hidden_func" id="fld_hidden_func">
			<option selected="selected" value=""><?php _e("Please select","wpas");?></option>
			<option value="user_login"><?php _e("Username","wpas");?></option>
			<option value="user_email"><?php _e("User Email","wpas");?></option>
			<option value="user_firstname"><?php _e("User Firstname","wpas");?></option>
			<option value="user_lastname"><?php _e("User Lastname","wpas");?></option>
			<option value="user_displayname"><?php _e("User Display Name","wpas");?></option>
			<option value="user_id"><?php _e("User ID","wpas");?></option>
			<option value="date_mm_dd_yyyy"><?php _e("Current Date (MM-DD-YYYY)","wpas");?></option>
			<option value="date_dd_mm_yyyy"><?php _e("Current Date (DD-MM-YYYY)","wpas");?></option>
			<option value="current_year"><?php _e("Current Year (YYYY)","wpas");?></option>
			<option value="current_month"><?php _e("Current Month Name (January)","wpas");?></option>
			<option value="current_month_num"><?php _e("Current Month (01)","wpas");?></option>
			<option value="current_day"><?php _e("Current Day (01)","wpas");?></option>
			<option value="now"><?php _e("Now (YYYY-MM-DD HH:mm:ss)","wpas");?></option>
			<option value="current_time"><?php _e("Current Time (HH:mm:ss)","wpas");?></option>
			<option value="unique_id"><?php _e("Unique Identifier","wpas");?></option>
			<option value="autoinc"><?php _e("Sequence","wpas");?></option>
			</select>
			<a href="#" style="cursor: help;" title="<?php _e("Sets a default value for the attribute.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group" id="fld_searchable_div" name="fld_searchable_div" style="display:none;">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Searchable","wpas");?>
            <input name="fld_searchable" id="fld_searchable" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the hidden function or hidden constant attribute searchable in the front end. Searchable hidden attributes can be used in the search forms.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div id="date-format" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label req span3"><?php _e("Date Format","wpas");?></label>
			<div class="controls span9">
			<select name="fld_date_format" id="fld_date_format">
			<option value="" selected="selected"><?php _e("Please select","wpas");?></option>
			<option value="mm-dd-yy"><?php _e("MM-DD-YYYY","wpas");?></option>
			<option value="yy-mm-dd"><?php _e("YYYY-MM-DD","wpas");?></option>
			<option value="dd-mm-yy"><?php _e("DD-MM-YYYY","wpas");?></option>
			<option value="mm/dd/yy"><?php _e("MM/DD/YYYY","wpas");?></option>
			<option value="yy/mm/dd"><?php _e("YYYY/MM/DD","wpas");?></option>
			<option value="dd/mm/yy"><?php _e("DD/MM/YYYY","wpas");?></option>
			</select>
			<a href="#" style="cursor: help;" title="<?php _e("Select a date format.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="time-format" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Time Format","wpas");?></label>
			<div class="controls span9">
			<select name="fld_time_format" id="fld_time_format">
			<option value="" selected="selected"><?php _e("Please select","wpas");?></option>
			<option value="hh:mm:ss"><?php _e("HH:mm:ss","wpas");?></option>
			<option value="hh:mm"><?php _e("HH:mm","wpas");?></option>
			</select>
			<a href="#" style="cursor: help;" title="<?php _e("Select a time format.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="validation-options" class="control-group row-fluid" style="display:none;">
	<label class="control-label span3"><?php _e("Validation Options","wpas");?></label>
	<div class="controls span9">  
	<div id="min-max-length" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Minimum Length","wpas");?></label>
			<div class="controls span9">
			<input class="input-mini" name="fld_min_length" id="fld_min_length" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Create a minumum length for the attribute.","wpas") . _e("Validation Message:","wpas") . ' ' . _e("Please enter at least X characters.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Maximum Length","wpas");?></label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_length" id="fld_max_length" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Create a maximum length for the attribute","wpas") .  _e("Validation Message:","wpas") . ' ' . _e("Please enter no more than X characters.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="min-max-value" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Minimum Value","wpas");?></label>
			<div class="controls span9">
			<input class="input-mini" name="fld_min_value" id="fld_min_value" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Create a minumum value for the attribute.","wpas") . _e("Validation Message:","wpas") . ' ' . _e("Please enter a value greater than or equal to X.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Maximum Value","wpas");?></label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_value" id="fld_max_value" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Create a maximum value for the attribute.","wpas") . _e("Validation Message:","wpas") . ' ' . _e("Please enter a value less than or equal to X.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="min-max-words" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Minimum Words","wpas");?></label>
			<div class="controls span9">
			<input class="input-mini" name="fld_min_words" id="fld_min_words" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Create a minumum number of words for the attribute.","wpas") . _e("Validation Message:","wpas") . ' ' . _e("Please enter at least X words.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Maximum Words","wpas");?></label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_words" id="fld_max_words" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Create a maximum number of words for the attribute.","wpas") . _e("Validation Message:","wpas") . ' ' . _e("Please enter X words or less.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	<div id="max-file-uploads" style="display:none;">
	<div class="control-group row-fluid">
			<label class="control-label span3"><?php _e("Max File Uploads","wpas");?></label>
			<div class="controls span9">
			<input class="input-mini" name="fld_max_file_uploads" id="fld_max_file_uploads" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Sets the number of maximum allowable file uploads.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	</div>
	</div><!--validation-optiond-->
	</div>
	<div class="control-group row-fluid" id="fld_values_div" style="display:none;">
        <label class="control-label span3 req"><?php _e("Values","wpas");?></label>
        <div class="controls span9">
        <textarea id="fld_values" name="fld_values" class="wpas-std-textarea" placeholder="e.g. blue;red;white " ></textarea>
        <a href="#" style="cursor: help;" title="<?php _e("Enter semicolon separated option labels for the field. There must be only one semicolon between the values. Optionally, you can define value-label combinations using {Value}Label format. If the predined value does not exist, it is automatically created based on the label.","wpas");?>">
        <i class="icon-info-sign"></i></a>
        </div>
</div>
	<div class="control-group row-fluid" id="fld_dflt_value_div" name="fld_dflt_value_div">
			<label class="control-label span3"><?php _e("Default Value","wpas");?></label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_dflt_value" id="fld_dflt_value" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Sets the default value(s) for the attribute, separated by a semicolon. Multiple default values can only be set for select with multiple option and checkbox list types. You must enter the value from Values field and not the label.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</div>
	</div>
	<div class="control-group row-fluid" id="fld_dflt_checked_div" name="fld_dflt_checked_div" style="display:none;">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Default Value","wpas");?>
			<input name="fld_dflt_checked" id="fld_dflt_checked" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Default is unchecked.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="fld_fa_chkd_div" name="fld_fa_chkd_div" style="display:none;">
			<label class="control-label span3"><?php _e("Checked Icon Class","wpas");?></label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_fa_chkd_val" id="fld_fa_chkd_val" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Sets font awesome web font icon class for selected values.","wpas");?>">
			<i class="icon-info-sign"></i></a><a href="https://wpas.emdplugins.com/articles/supported-icons/" target="_blank"><?php _e("Cheatsheet","wpas");?></a>
			</div>
	</div>
	<div class="control-group row-fluid" id="fld_fa_unchkd_div" name="fld_fa_unchkd_div" style="display:none;">
			<label class="control-label span3"><?php _e("Unchecked Icon Class","wpas");?></label>
			<div class="controls span9">
			<input class="input-xlarge" name="fld_fa_unchkd_val" id="fld_fa_unchkd_val" type="text" placeholder="" value="" >
			<a href="#" style="cursor: help;" title="<?php _e("Sets font awesome web font icon class for unselected values.","wpas");?>">
			<i class="icon-info-sign"></i></a><a href="https://wpas.emdplugins.com/articles/supported-icons/" target="_blank"><?php _e("Cheatsheet","wpas");?></a>
			</div>
	</div>
	<div class="control-group row-fluid" id="fld_limit_user_role_div" name="fld_limit_user_role_div" style="display:none;">
		<label class="control-label span3"><?php _e("Limit By Role","wpas");?></label>
		<div class="controls span9">
		<select id="fld_limit_user_role" name="fld_limit_user_role" multiple>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets font awesome web font icon class for unselected values.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
	</div>
	<div id="fld_autoinc_field_div" name="fld_autoinc_field_div" style="display:none;">
	<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Start At Value","wpas");?></label>
		<div class="controls span9">
		<input class="input-medium" name="fld_autoinc_start" id="fld_autoinc_start" type="text" placeholder="" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Sets sequence start at value, set to 1 if empty.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
	</div>
	<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Increment By","wpas");?></label>
		<div class="controls span9">
		<input class="input-medium" name="fld_autoinc_incr" id="fld_autoinc_incr" type="text" placeholder="" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Sets sequence increment by value, set to 1 if empty.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
	</div>
	</div>
	
  </fieldset>
</div>

	<div class="control-group row-fluid">
		  <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
	   <button class="btn  btn-primary pull-right layout-buttons" id="save-entity-field" name="Save" type="submit"><?php _e("Save","wpas");?>
	   </button>
	</div>
<?php
}
?>
