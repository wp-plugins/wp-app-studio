<?php
function wpas_form_layout_form()
{
?>
<div class="modal hide" id="errorModalForm">
  <div class="modal-header">
        <button id="error-form-close" type="button" class="close" data-dismiss="errorModalForm" aria-hidden="true">x</button>
    <h3><i class="icon-flag icon-red"></i>Error</h3>
  </div>
  <div class="modal-body" style="clear:both">Please add all required fields to the form layout.
  </div>
  <div class="modal-footer">
<button id="error-form-ok" data-dismiss="errorModalForm" aria-hidden="true" class="btn btn-primary">OK</button>
  </div>
</div>
<form action="" method="post" id="form-layout">
<input type="hidden" id="app" name="app" value="">
<input type="hidden" value="" name="form" id="form">
<div class="row-fluid" id= "form-layout-bin-ctr">
</div>
<div class="row-fluid">
<div id="form-layout-frm-btn" class="control-group span12">
<button id="cancel" class="btn  btn-danger layout-buttons" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
<button id="save-form-layout" class="btn  btn-primary pull-right layout-buttons" type="submit" name="Save"><i class="icon-save"></i>
Save
</button>
</div>
</div>
</form>
<?php
}
function wpas_get_ent_tax_rel_count($app,$form_id)
{
	$tax_count = 0;
	$rel_count = 0;
	$ent_field_count = 0;
	$ent_id = $app['form'][$form_id]['form-attached_entity_id'];
	$ent_label = $app['form'][$form_id]['form-attached_entity'];
	if(!empty($app['entity'][$ent_id]['field']))
	{
		$ent_field_count = count($app['entity'][$ent_id]['field']);
	}
	if(!empty($app['form'][$form_id]['form-dependents']))
	{
		$rel_count = count($app['form'][$form_id]['form-dependents']);
	}
	if(!empty($app['taxonomy']))
	{
		foreach($app['taxonomy'] as $mytaxonomy)
		{
			if(in_array($ent_label,$mytaxonomy['txn-attach']))
			{
				$tax_count ++;
			}
		}
	}
	return Array($ent_field_count,$rel_count,$tax_count);
}
function wpas_form_container($layout,$app,$form_id)
{
	$count = 1;
	list($ent_field_count,$rel_count,$tax_count) = wpas_get_ent_tax_rel_count($app,$form_id);
	$layout_html = "<div class=\"layout-bin span3 pull-left\" data-spy=\"affix\" data-offset-top=50>
			<ul class=\"ui-draggable\">
			<li class=\"ui-draggable\"><div class=\"form-hr\" id=\"form-hr\"><div>HR</div></div></li>
			<li class=\"ui-draggable\"><div class=\"form-text\" id=\"form-text\"><div>Text</div></div></li>";
	if($ent_field_count != 0)
	{
		$layout_html .="<li class=\"ui-draggable\"><div class=\"form-attr\" id=\"form-attr-1\"><div>Attribute</div></div></li>";
	}
	if($tax_count != 0)
	{
		$layout_html .=	"<li class=\"ui-draggable\"><div class=\"form-tax\" id=\"form-tax-1\"><div>Taxonomy</div></div></li>";
	}
	if($rel_count != 0)
	{
		$layout_html .= "<li class=\"ui-draggable\"><div class=\"form-relent\" id=\"form-relent-1\"><div>Related Entity</div></div></li>";
	}
	$layout_html .= "</ul></div><div id=\"form-layout-ctr\" class=\"ui-droppable ui-sortable  span9 pull-right\">";
	if(!is_array($layout))
	{
		$layout_html .="<div class=\"dragme\">DRAG AND DROP</div>";
	}
	else
	{
		foreach($layout as $key => $mylayout)
		{
			$class = $mylayout['obtype'];
			$text = ucfirst($mylayout['obtype']);
			$text2 = $text3 = "";
			$attr_type = explode("-",$class);
			if(!in_array($mylayout['obtype'], Array('hr','text')))
			{
				$class = $attr_type[0];
				if(isset($mylayout['box_label1']))
				{
					$text =  $mylayout['box_label1'];
				}
				if(isset($mylayout['box_label2']))
				{
					$text2 = $mylayout['box_label2'];
				}
				if(isset($mylayout['box_label3']))
				{
					$text3 = $mylayout['box_label3'];
				}

			}
			$id = "form-" . $mylayout['obtype'] . "-" . $key;
			if($mylayout['obposition'] == 1)
			{
				$selected_vals = Array();
				$layout_html .= "<div id='" . esc_attr($id) . "' class='form-" . esc_attr($class) . "'>";
				$layout_html .= "<div class='row-fluid form-field-str'>";
				$layout_html .= "<div class='span1 layout-edit-icons'>";
				if($mylayout['obtype'] != 'hr')
				{
					$layout_html .= "<a class='edit'><i class='icon-edit pull-left'></i></a>";
				}
				$layout_html .= "</div>";
				$layout_html .= "<div id='field-label1' class='span4'>" . esc_html($text) . "</div>";
				$layout_html .= "<div id='field-label2' class='span3'>" . esc_html($text2) . "</div>";
				$layout_html .= "<div id='field-label3' class='span3'>" . esc_html($text3) . "</div>";
				$layout_html .= "<div class='span1 layout-edit-icons'>";
				$layout_html .= "<a class='delete'><i class='icon-trash pull-right'></i></a></div></div>";
				$layout_html .= "<div id='" . esc_attr($id) . "-inside' class='form-inside'>";
			}
			if($mylayout['obtype'] == 'text')
			{
				$layout_html .= "<div class='control-group row-fluid'>";
				$layout_html .= "<label class='control-label span3'>Description</label>";
				$layout_html .= "<div class='controls span9'>";
				$layout_html .= "<textarea id='form-text-desc-" . esc_attr($key) . "' class='input-xlarge' name='form-text-desc-" . esc_attr($key) . "'>";
				$layout_html .= $mylayout['desc'] . "</textarea></div></div>"; 
			}
			elseif($mylayout['obtype'] == 'hr')
			{
				$layout_html .= "<input type='hidden' id='form-hr-" . esc_attr($key) . "' name='form-hr-" . esc_attr($key) . "' value=1>";
			}
			else
			{
				$selected_vals[$mylayout['obposition']] = $mylayout['entity'] . "__" . $mylayout[$class];
				if($mylayout['obtype'] == $class . '-' . $mylayout['obposition'])
				{
				$layout_html .= wpas_get_form_layout_select_all($app,$form_id,$class,$attr_type[1],$mylayout['sequence'],$selected_vals);
				}
			}
			if(!isset($attr_type[1]))
			{
				$attr_type[1] = 1;
			}
			if($attr_type[1] == $mylayout['obposition'])
			{
				$layout_html .= "</div></div>";
			}
			$count ++;
		}
	}
	$layout_html .= "<input type='hidden' id='form-field-count' name='form-field-count' value='" . esc_attr($count) . "'></div>";
	return $layout_html;
}
function display_tinymce($id,$initial='')
{
	$buttons['theme_advanced_buttons1'] = 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,link,unlink';
	$buttons['theme_advanced_buttons2'] = 'tablecontrols';

	$settings = array(
			'text_area_name'=> $id,//name you want for the textarea
			'quicktags' => false,
			'media_buttons' => false,
			'textarea_rows' => 15,
			'tinymce' => $buttons,
			);
	wp_editor($initial,$id,$settings);
}
function wpas_add_forms_form($app_id)
{
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#form-schedule_start').datetimepicker({dateFormat:'yy-mm-dd',timeFormat:'hh:mm:ss'});
	$('#form-schedule_end').datetimepicker({dateFormat:'yy-mm-dd',timeFormat:'hh:mm:ss'});
	$('#form-advanced-option').click(function() {
		if($(this).attr('checked'))
		{
			$('#form-tabs').show();
		}
		else
		{
			$('#form-tabs').hide();
		}
	});
	$('#form-temp_type').click(function () {
		if($(this).find('option:selected').val() == 'Bootstrap')
		{
			$('#form-font_awesome').attr('checked',true);
			$('#form-submit_button_fa_div').show();
			$('#form-font_awesome').attr('disabled',true);
		}
		else
		{
			$('#form-font_awesome').attr('disabled',false);
		}
	});
	$('#form-font_awesome').click(function () {
		if($(this).attr('checked'))
		{
			$('#form-submit_button_fa_div').show();
		}
		else
		{
			$('#form-submit_button_fa_div').hide();
		}
	});
	$('#form-confirm_method').click(function () {
		if($(this).find('option:selected').text() == 'Show text')
		{
			$('#form-confirm_txt_div').show();
			$('#form-confirm_url_div').hide();
		}
		else if($(this).find('option:selected').text() == 'Redirect')
		{
			$('#form-confirm_txt_div').hide();
			$('#form-confirm_url_div').show();
		}
	});
	$('#form-email_user_confirm').click(function() {
		if($(this).attr('checked'))
		{
			$('#form-email_user_div').show();
			primary_ent = $('#form-attached_entity :selected').val();
			dependents = $('#form-dependents').val();
			app_id = $('input#app').val();
              		$.get(ajaxurl,{action:'wpas_get_email_attrs',primary_entity:primary_ent,dependents:dependents,app_id:app_id}, function(response)
			{
				$('#add-form-div #form-confirm_sendto').html(response);
			});
		}
		else
		{
			$('#form-email_user_div').hide();
		}
	});
	$('#form-email_admin_confirm').click(function() {
		if($(this).attr('checked'))
		{
			$('#form-email_admin_div').show();
		}
		else
		{
			$('#form-email_admin_div').hide();
		}
	});
	$('#form-enable_form_schedule').click(function() {
		if($(this).attr('checked'))
		{
			$('#form-schedule_div').show();
		}
		else
		{
			$('#form-schedule_div').hide();
		}
	});
});
</script>
<form action="" method="post" id="form-form" class="form-horizontal">
<input type="hidden" id="app" name="app" value="">
<input type="hidden" value="" name="form" id="form">
<fieldset>
	<div class="field-container">
	<div class="well">
	<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="Forms allow a user to enter data directly to your entities,taxonomies, and relationships. Forms are the main data entry interface for your web and mobile apps."> HELP</a></div></div>
	<div class="control-group row-fluid">
	<label class="control-label span3">Name</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-name" id="form-name" type="text" placeholder="e.g. customer_survey" value="" >
	<a href="#" style="cursor: help;" title="Unique identifier for the form. Can not contain capital letters,dashes or spaces. Between 3 and 30 characters.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-form-attached_entity_div"> 
	<label class="control-label span3" >Attached to Entity</label>
	<div class="controls span9">
	<select name="form-attached_entity" id="form-attached_entity" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="Sets the primary entity for your form. The selected entity will be main entry point and will be used for the dependent selection. ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-dependents_div"> 
	<label class="control-label span3">Dependents</label>
	<div class="controls span9">
	<select name="form-dependents" id="form-dependents" multiple="multiple" class="input-xlarge">
	</select>
	<a href="#" style="cursor: help;" title="Sets the dependents of the primary entity for your form. The attributes of the dependents will be included in the form layout. ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3">Title</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-form_title" id="form-form_title" type="text" placeholder="e.g. Customer Survey" value="" >
	<a href="#" style="cursor: help;" title="Optional. Sets the first piece of text displayed to users when they see your form. Optional. Max:40 char. Use it to standardize the form's title.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3">Description</label>
	<div class="controls span9">
<?php display_tinymce('form-form_desc'); ?>
	<a href="#" style="cursor: help;" title="Optional. Set a short description or instructions, notes, or guidelines that users should read when filling out the form. This will appear directly below the form title and above the fields.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox">Show Advanced Options
	<input name="form-advanced-option" id="form-advanced-option" type="checkbox" value="1"/>
	</label>
	</div>
	</div>
	</div><!--well-->
	<div id="form-tabs" style="display:none;">
	<ul id="formTab" class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#formtabs-1">Display Options</a></li>
	<li><a data-toggle="tab" href="#formtabs-2">Submissions</a></li>
	<li><a data-toggle="tab" href="#formtabs-3">Confirmations</a></li>
	<li><a data-toggle="tab" href="#formtabs-4">Scheduling</a></li>
	</ul>
	<div id="FormTabContent" class="tab-content">
	<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="Display Options tab configures how the form will be displayed on the frontend. Confirmations tab defines the notifications after data entry occured. Submissions tab sets the options for the submit button,spam protection, and how the data enttry will be saved. Use scheduling tab to create a form submission schedule."> HELP</a></div></div>
	
	<div id="formtabs-1" class="tab-pane fade in active">
	<div class="control-group row-fluid" id="form-temp_type_div"> 
	<label class="control-label span3" >Frontend Template</label>
	<div class="controls span9">
	<select name="form-temp_type" id="form-temp_type" class="input-medium">
	<option value="Pure">JQuery UI</option>
	<option value="Bootstrap" selected="selected">Twitter's Bootstrap</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets the frontend framework which will be used to configure the overall look and feel of the form. If you pick JQuery UI, you can choose your theme from App's Settings under Theme tab. Default is Twitter Bootstrap.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>				
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox">Enable Font Awesome
	<input name="form-font_awesome" id="form-font_awesome" type="checkbox" value="1" checked/>
	<a href="#" style="cursor: help;" title="Enables Font Awesome webfont for radios, checkboxes and other icons. Can not be disabled for the Bootstrap framework.">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-label_position_div"> 
	<label class="control-label span3" >Label Placement</label>
	<div class="controls span9">
	<select name="form-label_position" id="form-label_position" class="input-medium">
	<option value="top" selected="selected">Top</option>
	<option value="left">Left</option>
	<option value="inside">Inside</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets the field label position relative to the field input location. Options are Top,Left or Inside. The inside option is relavant only for textboxes.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	<div class="control-group row-fluid">
	<label class="control-label span3" >No Access Message</label>
	<div class="controls span9">
<?php display_tinymce('form-not_loggedin_msg','You are not allowed to access to this area. Please contact the site administrator.'); ?>
	<a href="#" style="cursor: help;" title="Sets the text which will be displayed to users that do not have access to this form.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div>	<!--formtabs-1-->			

	<div id="formtabs-2" class="tab-pane fade in">
	<div class="control-group row-fluid" id="form-submit_status_div"> 
	<label class="control-label span3" >Submit Status</label>
	<div class="controls span9">
	<select name="form-submit_status" id="form-submit_status" class="input-medium">
	<option value="publish">Publish</option>
	<option value="pending">Pending</option>
	<option value="draft">Draft</option>
	<option value="future">Future</option>
	<option value="private">Private</option>
	<option value="trash">Trash</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets the status of all form entries. 'publish' - Entry is available immediately.'pending' - Entry is pending review. 'draft' - Entry is in draft status. 'future' - Entry is will be published in the future. 'private' - Entry is not visible to users who are not logged in. 'trash' - Entry is in trashbin. Default is publish.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-submit_button_type_div"> 
	<label class="control-label span3" >Submit Button Type</label>
	<div class="controls span9">
	<select name="form-submit_button_type" id="form-submit_button_type" class="input-medium">
	<option value="btn-standard" selected="selected">Standard (White - #FFFFF)</option>
	<option value="btn-primary">Primary (Blue - #006DCC)</option>
	<option value="btn-info">Info (Light Blue - #49AFCD)</option>
	<option value="btn-success">Success (Green - #5BB75B)</option>
	<option value="btn-warning">Warning (Orange - #FAA732)</option>
	<option value="btn-danger">Danger (Red - #DA4F49)</option>
	<option value="btn-inverse">Inverse (Black - #363636)</option>
	<option value="btn-link">Link (Blue -  #0088CC)</option>
	</select>
	<a href="#" style="cursor: help;" title="Standard button is a white background button with a darkgray border. Primary button provides extra visual weight and identifies the primary action in a set of buttons. Info button is used as an alternative to the standard style. Success button indicates a successful or positive action. Warning button indicates caution should be taken with this action. Danger button indicates a dangerous or potentially negative action. Inverse button is alternate dark gray button, not tied to a semantic action or use. Link button deemphasizes a button by making it look like a link while maintaining button behavior.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3">Submit Button Label</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-submit_button_label" id="form-submit_button_label" type="text" placeholder="e.g. Submit" value="" >
	<a href="#" style="cursor: help;" title="Sets the submit button label of your form. Max:30 Char.">
	<i class="icon-info-sign"></i></a> (Default: Submit)
	</div>
	</div>	
	<div class="control-group row-fluid" id="form-submit_button_size_div"> 
	<label class="control-label span3" >Submit Button Size</label>
	<div class="controls span9">
	<select name="form-submit_button_size" id="form-submit_button_size" class="input-medium">
	<option value="btn-std" selected="selected">Standard</option>
	<option value="btn-xlarge">XLarge</option>
	<option value="btn-large">Large</option>
	<option value="btn-small">Small</option>
	<option value="btn-mini">Mini</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets the submit button size of your form.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div id="form-submit_button_fa_div" name="form-submit_button_fa_div">
	<div class="control-group row-fluid">
		<label class="control-label span3">Submit Button Icon Class</label>
		<div class="controls span9">
		<input class="input-medium" name="form-submit_button_fa" id="form-submit_button_fa" type="text" placeholder="" value="" >
		<a href="#" style="cursor: help;" title="Sets the font awesome icon which will be displayed next to the button text.">
		<i class="icon-info-sign"></i></a><a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_blank">Cheatsheet</a>
		</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label span3" >Submit Button Icon Size</label>
	<div class="controls span9">
	<select name="form-submit_button_fa_size" id="form-submit_button_fa_size" class="input-medium">
	<option value="" selected="selected">Please select</option>
	<option value="icon-large">Icon Large</option>
	<option value="icon-2x">Icon 2x</option>
	<option value="icon-3x">Icon 3x</option>
	<option value="icon-4x">Icon 4x</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets the size of font awesome icon which will be displayed next to the button text.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-submit_button_position_div"> 
	<label class="control-label span3" >Submit Button Icon Position</label>
	<div class="controls span9">
	<select name="form-submit_button_fa_pos" id="form-submit_button_fa_pos" class="input-medium">
	<option value="left" selected="selected">Left</option>
	<option value="right">Right</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets the position of icon within the submit button.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div> <!-- form-submit_button_fa_div -->
	<div class="control-group row-fluid" id="form-show_captcha_div"> 
	<label class="control-label span3" >Show Captcha</label>
	<div class="controls span9">
	<select name="form-show_captcha" id="form-show_captcha" class="input-medium">
	<option value="show-always">Always Show</option>
	<option value="show-to-visitors">Visitors Only</option>
	<option value="never-show">Never Show</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets Captcha display option. WPAS forms use the 'honeypot' technique by default however CAPTCHAs can also be used for even stronger protection. Always Show displays captcha for everybody. Visitors Only option shows it for only visitors. Never Show option disables it.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	<div class="control-group row-fluid">
	<label class="control-label span3">Disable After</label>
	<div class="controls span9">
	<input class="input-mini" name="form-disable_after" id="form-disable_after" type="text" placeholder="e.g. 5" value="" >
	<a href="#" style="cursor: help;" title="Disables form submissions after the set number reached. Leave blank for no limit.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div>	<!--formtabs-2-->

	<div id="formtabs-3" class="tab-pane fade in">
	<div class="control-group row-fluid" id="form-confirm_method_div"> 
	<label class="control-label span3" >Confirmation Method</label>
	<div class="controls span9">
	<select name="form-confirm_method" id="form-confirm_method" class="input-medium">
	<option value="text" selected>Show text</option>
	<option value="redirect">Redirect</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets the event that will occur after a successful entry. Show text option display a text message. Redirect option redirects users toanother URL.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-confirm_txt_div">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox">Enable Ajax
	<input name="form-ajax" id="form-ajax" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="When set ajax form submissions are enabled.">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-show_after_submit_div"> 
	<label class="control-label span3" >After Submit</label>
	<div class="controls span9">
	<select name="form-show_after_submit" id="form-show_after_submit" class="input-medium">
	<option value="show" selected>Show Form</option>
	<option value="clear">Clear Form</option>
	<option value="hide">Hide Form</option>
	</select>
	<a href="#" style="cursor: help;" title="Sets what users see after a successful submission. Show Form option shows the completed form. Clear Form option resets all fields to their defaults. Hide Form option hides the form from the user who completed the entry.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3">Success Text</label>
	<div class="controls span9">
<?php display_tinymce('form-confirm_success_txt','Thanks for your submission.'); ?>
	<a href="#" style="cursor: help;" title="Sets the text which will be displayed to users after a successful entry.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	<div class="control-group row-fluid">
	<label class="control-label span3">Error Text</label>
	<div class="controls span9">
<?php display_tinymce('form-confirm_error_txt','There has been an error when submitting your entry. Please contact the site administrator.'); ?>
	<a href="#" style="cursor: help;" title="Sets the text which will be displayed to users after an unsuccessful entry.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	</div>
	<div class="control-group row-fluid" id="form-confirm_url_div" style="display:none;">
	<label class="control-label span3">Confirmation URL</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_url" id="form-confirm_url" type="text" placeholder="e.g. http://example.com/myform-confirm.php" value="" >
	<a href="#" style="cursor: help;" title="When set, after a successful entry, users get redirected to another url. Max:255 Char.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox">Email User Confirmation
	<input name="form-email_user_confirm" id="form-email_user_confirm" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="When checked user confirmation emails are enabled.">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div id="form-email_user_div" style="display:none;"> 
	<div class="control-group row-fluid"> 
	<label class="control-label span3" >User Email Send To</label>
	<div class="controls span9">
	<select name="form-confirm_sendto" id="form-confirm_sendto" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="Select the email attribute you want to send the receipt to. The user email address must be available in the attribute selected otherwise no emails are sent.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3">User Email Reply To</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_replyto" id="form-confirm_replyto" type="text" placeholder="e.g. user-emails@example.com" value="" >
	<a href="#" style="cursor: help;" title="Sets the email address users can reply to the receipt. Leave it blank if you don't want them to email you. Max:255 Char.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3">User Email Subject</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_subject" id="form-confirm_subject" type="text" placeholder="e.g. Thanks for filling out my form" value="" >
	<a href="#" style="cursor: help;" title="Sets the subject of user emails. Max:255 Char.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3">User Email Message</label>
	<div class="controls span9">
<?php display_tinymce('form-confirm_msg'); ?>
	<a href="#" style="cursor: help;" title="A short message confirming a successful submission.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox">Email Admin Confirmation
	<input name="form-email_admin_confirm" id="form-email_admin_confirm" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="When checked, enables admin confirmation emails.">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div id="form-email_admin_div" style="display:none;"> 
	<div class="control-group row-fluid">
	<label class="control-label span3">Admin Email Send To</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_sendto" id="form-confirm_admin_sendto" type="text" placeholder="e.g. admin-emails@example.com" value="" >
	<a href="#" style="cursor: help;" title="The email address admins to get messages when a successful entry occurred. Leave it blank if you don't want them to get emails. Max:255 Char.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3">Admin Email Reply To</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_replyto" id="form-confirm_admin_replyto" type="text" placeholder="e.g. admin-emails@example.com" value="" >
	<a href="#" style="cursor: help;" title="The email address admins to reply to the receipt. Leave it blank if you don't want them to email you. Max:255 Char.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3">Admin Email Subject</label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_subject" id="form-confirm_admin_subject" type="text" placeholder="e.g. Someone filled out my form" value="" >
	<a href="#" style="cursor: help;" title="Sets the subject of admin emails. Max:255 Char.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3">Admin Email Message</label>
	<div class="controls span9">
<?php display_tinymce('form-confirm_admin_msg'); ?>
	<a href="#" style="cursor: help;" title="Sets a message confirming a successful submission.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	</div>
	</div>	<!--formtabs-3-->
	
	<div id="formtabs-4" class="tab-pane fade in">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox">Enable Form Scheduling
	<input name="form-enable_form_schedule" id="form-enable_form_schedule" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="Set to make the form automatically become active or inactive at a certain date.">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div id="form-schedule_div" style="display:none;"> 
	<div class="control-group row-fluid">
	<label class="control-label span3">Start Datetime</label>
	<div id="form-datetime_start" class="controls span9">
	<input class="input-medium" name="form-schedule_start" id="form-schedule_start" type="text">
	<a href="#" style="cursor: help;" title="The start datetime after which form will be active.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3">End Datetime</label>
	<div class="controls span9">
	<input class="input-medium" name="form-schedule_end" id="form-schedule_end" type="text">
	<a href="#" style="cursor: help;" title="The last form submission datetime after which form will be inactive.">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	</div>
	</div>	<!--formtabs-4-->	
	
	</div>	<!--tab-contform-->	
	</div><!--field-container-->
	<div class="control-group">
	<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
	<button class="btn  btn-primary pull-right layout-buttons" id="save-form" type="submit" value="Save"><i class="icon-save"></i>Save</button>
	</div>
</fieldset>
</form>
<?php
}
?>
