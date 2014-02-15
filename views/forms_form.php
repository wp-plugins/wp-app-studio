<?php
function wpas_form_layout_form()
{
?>
<div class="modal hide" id="errorModalForm">
  <div class="modal-header">
        <button id="error-form-close" type="button" class="close" data-dismiss="errorModalForm" aria-hidden="true">x</button>
    <h3><i class="icon-flag icon-red"></i><?php _e("Error","wpas");?></h3>
  </div>
  <div class="modal-body" style="clear:both"><?php _e("Please add all required fields to the form layout.","wpas");?>
  </div>
  <div class="modal-footer">
<button id="error-form-ok" data-dismiss="errorModalForm" aria-hidden="true" class="btn btn-primary"><?php _e("OK","wpas");?></button>
  </div>
</div>
<form action="" method="post" id="form-layout">
<input type="hidden" id="app" name="app" value="">
<input type="hidden" value="" name="form" id="form">
<div class="row-fluid" id= "form-layout-bin-ctr">
</div>
<div class="row-fluid">
<div id="form-layout-frm-btn" class="control-group span12">
<button id="cancel" class="btn  btn-danger layout-buttons" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
<button id="save-form-layout" class="btn  btn-primary pull-right layout-buttons" type="submit" name="Save"><i class="icon-save"></i>
<?php _e("Save","wpas");?>
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
	$ent_id = $app['form'][$form_id]['form-attached_entity'];
	if(!empty($app['entity'][$ent_id]['field']))
	{
		foreach($app['entity'][$ent_id]['field'] as $appfield)
		{
			if(!in_array($appfield['fld_type'],Array('hidden_constant','hidden_function')))
			{
				$ent_field_count ++;
			}
		}
	}
	if(!empty($app['form'][$form_id]['form-dependents']))
	{
		$rel_count = count($app['form'][$form_id]['form-dependents']);
	}
	if(!empty($app['taxonomy']))
	{
		foreach($app['taxonomy'] as $mytaxonomy)
		{
			if(in_array($ent_id,$mytaxonomy['txn-attach']))
			{
				$tax_count ++;
			}
		}
	}
	return Array($ent_field_count,$rel_count,$tax_count);
}
function wpas_form_container($layout,$app,$form_id)
{
	list($ent_field_count,$rel_count,$tax_count) = wpas_get_ent_tax_rel_count($app,$form_id);
	$layout_html = "<div class=\"layout-bin span3 pull-left\" data-spy=\"affix\" data-offset-top=50>
			<ul class=\"ui-draggable\">
			<li class=\"ui-draggable\"><div class=\"form-hr\" id=\"form-hr\"><div><i class=\"icon-resize-horizontal\"></i>HR</div></div></li>
			<li class=\"ui-draggable\"><div class=\"form-text\" id=\"form-text\"><div> <i class=\"icon-text-width\"></i>" . __("Text","wpas") . "</div></div></li>";
	if($ent_field_count != 0 || $tax_count != 0 || $rel_count != 0)
	{
		$layout_html .="<li class=\"ui-draggable\"><div class=\"form-attr\" id=\"form-element\"><div> <i class=\"icon-tasks\"></i>" . __("Element","wpas") . "</div></div></li>";
	}
	$layout_html .= "</ul></div><div id=\"form-layout-ctr\" class=\"ui-droppable ui-sortable  span9 pull-right\">";
	if(!is_array($layout))
	{
		$layout_html .="<div class=\"dragme\">" . __("DRAG AND DROP") . "</div>";
	}
	else
	{
		$text = "";
		$selected_vals = Array();
		$sizes = Array();
		for($i=1;$i<=count($layout);$i++)
		{
			if(isset($layout[$i]['obtype']) && in_array($layout[$i]['obtype'],Array('hr','text')))
			{
				$class = $layout[$i]['obtype'];
			}
			else
			{
				$class = 'element';
			}
			$id = "form-" . $class . "-" . $i;
			$layout_html .= "<div id='" . esc_attr($id) . "' class='form-" . esc_attr($class) . "'>";
			$layout_html .= "<div class='row-fluid form-field-str'>";
			$layout_html .= "<div class='span1 layout-edit-icons'>";
			if((isset($layout[$i]['obtype']) && $layout[$i]['obtype'] != 'hr') || !isset($layout[$i]['obtype']))
			{
				$layout_html .= "<a class='edit'><i class='icon-edit pull-left'></i></a>";
			}
			$layout_html .= "</div><div id='field-labels' class='row-fluid span10'>";
			if(!in_array($class,Array('hr','text')))
			{
				for($j=1;$j<= count($layout[$i]);$j++)
				{
					if(isset($layout[$i][$j]))
				   	{
						$layout_html .= "<div id='field-label" . $j . "' class='span" . $layout[$i][$j]['size'] . "'>";
						if(isset($layout[$i][$j]['obtype']))
						{
							$connector = "";
							switch($layout[$i][$j]['obtype']) {
								case 'attr':
						 			$layout_html .= esc_html($app['entity'][$layout[$i][$j]['entity']]['field'][$layout[$i][$j]['attr']]['fld_label']);
									$connector = 'fld';
									break;
								case 'tax':
						 			$layout_html .= esc_html($app['taxonomy'][$layout[$i][$j]['tax']]['txn-label']);
									$connector='tax';
									break;
								case 'relent':
									$myrel = $app['relationship'][$layout[$i][$j]['relent']];
						 			$layout_html .= esc_html(wpas_get_rel_full_name($myrel,$app));
									$connector='rel';
									break;
							}
							
							
							$selected_vals[$j] = $layout[$i][$j]['entity'] . "__" . $connector . $layout[$i][$j][$layout[$i][$j]['obtype']];
						}
						$layout_html .= "</div>";	
						$sizes[$j] = $layout[$i][$j]['size'];
					}
				}
			}
			else
			{
				$layout_html .= ucfirst($class);
			}
			$layout_html .= "</div><div class='span1 layout-edit-icons'>";
			$layout_html .= "<a class='delete'><i class='icon-trash pull-right'></i></a></div></div>";
			$layout_html .= "<div id='" . esc_attr($id) . "-inside' class='form-inside'>";
			if($class == 'text')
			{
				$layout_html .= "<div class='control-group row-fluid'>";
				$layout_html .= "<label class='control-label span3'>" . __("Description","wpas") . "</label>";
				$layout_html .= "<div class='controls span9'>";
				$layout_html .= "<textarea id='form-text-desc-" . $i . "' class='input-xlarge' name='form-text-desc-" . $i . "'>";
				if(isset($layout[$i]['desc']))
				{
					$layout_html .= $layout[$i]['desc']; 
				}
				$layout_html .= "</textarea></div></div>"; 
			}
			elseif($class == 'hr')
			{
				$layout_html .= "<input type='hidden' id='form-hr-" . $i . "' name='form-hr-" . $i . "' value=1>";
			}
			else
			{
				$count = $j - 1;
				$layout_html .= wpas_get_form_layout_select_all($app,$form_id,$count,$i,$selected_vals,$sizes);
			}
			$layout_html .= "</div></div>";
		}
	}
	$latest_count = count($layout) + 1;
	$layout_html .= "<input type='hidden' id='form-field-count' name='form-field-count' value='" . $latest_count . "'></div>";
	return $layout_html;
}
function display_tinymce($id,$initial='',$set_html=0,$set_attr=0)
{
	$buttons['theme_advanced_buttons1'] = 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,link,unlink';
	$buttons['theme_advanced_buttons2'] = 'tablecontrols';
	if($set_html == 1)
	{
		$buttons['theme_advanced_buttons2'] .= ',code';
	}
	if($set_attr == 1)
	{
		$buttons['theme_advanced_buttons2'] .= ',mylistbox';
	}

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
	$('#form-form_type').click(function() {
		if($(this).find('option:selected').val() == 'search')
		{
			app_id = $('input#app').val();
			$('#formtabs-3-li').hide();
			$('#formtabs-4-li').hide();
			$('#formtabs-5-li').hide();
			$('#form-submit_status_div').hide();
			$('#form-noresult_msg_div').show();
			$('#form-result_msg_div').show();
			$('#form-ajax_search_div').show();
			$('#form-enable_operators_div').show();
		}
		else
		{
			$('#formtabs-3-li').show();
			$('#formtabs-4-li').show();
			$('#formtabs-5-li').show();
			$('#form-submit_status_div').show();
			$('#form-noresult_msg_div').hide();
			$('#form-result_msg_div').hide();
			$('#form-ajax_search_div').hide();
			$('#form-enable_operators_div').hide();
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
		if($(this).find('option:selected').val() == 'text')
		{
			$('#form-confirm_txt_div').show();
			$('#form-confirm_url_div').hide();
		}
		else if($(this).find('option:selected').val() == 'redirect')
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
	<div class="row-fluid">
	<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Forms allow a user to enter data directly to your entities,taxonomies, and relationships. Forms are the main data entry interface for your web and mobile apps.","wpas");?>">
	<?php _e("HELP","wpas");?></a></div></div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Name","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-name" id="form-name" type="text" placeholder="<?php _e("e.g. customer_survey","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Unique identifier for the form. Can not contain capital letters,dashes or spaces. Between 3 and 30 characters.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Type","wpas");?></label>
	<div class="controls span9">
	<select name="form-form_type" id="form-form_type" class="input-medium">
	<option value="" selected="selected"><?php _e("Please select","wpas");?></option>
	<option value="submit"><?php _e("Submit","wpas");?></option>
        <option value="search"><?php _e("Search","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the type of form to be created. Submit forms are for sending and saving data. Search forms are for searching content and displaying results on a page.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-form-attached_entity_div"> 
	<label class="control-label req span3"><?php _e("Attached to Entity","wpas");?></label>
	<div class="controls span9">
	<select name="form-attached_entity" id="form-attached_entity" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the primary entity for your form. The selected entity will be main entry point and will be used for the dependent selection.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-dependents_div"> 
	<label class="control-label span3"><?php _e("Dependents","wpas");?></label>
	<div class="controls span9">
	<select name="form-dependents" id="form-dependents" multiple="multiple" class="input-xlarge">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the dependents of the primary entity for your form. The attributes of the dependents will be included in the form layout.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Title","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-form_title" id="form-form_title" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Optional. Sets the first piece of text displayed to users when they see your form. Optional. Max:40 char. Use it to standardize the form's title.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Description","wpas");?></label>
	<div class="controls span9">
<?php display_tinymce('form-form_desc','',1); ?>
	<a href="#" style="cursor: help;" title="<?php _e("Optional. Set a short description or instructions, notes, or guidelines that users should read when filling out the form. This will appear directly below the form title and above the fields.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Show Advanced Options","wpas");?>
	<input name="form-advanced-option" id="form-advanced-option" type="checkbox" value="1"/>
	</label>
	</div>
	</div>
	</div><!--well-->
	<div id="form-tabs" style="display:none;">
	<ul id="formTab" class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#formtabs-1"><?php _e("Display Options","wpas");?></a></li>
	<li><a data-toggle="tab" href="#formtabs-2"><?php _e("Submissions","wpas");?></a></li>
	<li id="formtabs-3-li"><a data-toggle="tab" href="#formtabs-3"><?php _e("Confirmations","wpas");?></a></li>
	<li id="formtabs-4-li"><a data-toggle="tab" href="#formtabs-4"><?php _e("User Notification","wpas");?></a></li>
	<li id="formtabs-5-li"><a data-toggle="tab" href="#formtabs-5"><?php _e("Admin Notification","wpas");?></a></li>
	<li><a data-toggle="tab" href="#formtabs-6"><?php _e("Scheduling","wpas");?></a></li>
	</ul>
	<div id="FormTabContent" class="tab-content">
	<div class="row-fluid">
	<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Display Options tab configures how the form will be displayed on the frontend. Confirmations tab defines the notifications after data entry occured. Submissions tab sets the options for the submit button,spam protection, and how the data entry will be saved. Use scheduling tab to create a form submission schedule.","wpas");?>"><?php _e("HELP","wpas");?></a></div>
	</div>
	
	<div id="formtabs-1" class="tab-pane fade in active">
	<div class="control-group row-fluid" id="form-temp_type_div"> 
	<label class="control-label span3"><?php _e("Template","wpas");?></label>
	<div class="controls span9">
	<select name="form-temp_type" id="form-temp_type" class="input-medium">
	<option value="Bootstrap" selected="selected">Twitter's Bootstrap</option>
	<option value="Pure">jQuery UI</option>
	<option value="Na">None</option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the frontend framework which will be used to configure the overall look and feel of the form. If you pick JQuery UI, you can choose your theme from App's Settings under the theme tab. Default is Twitter Bootstrap.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>				
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9">
	<label class="checkbox"><?php _e("Enable Font Awesome","wpas");?>
	<input name="form-font_awesome" id="form-font_awesome" type="checkbox" value="1" checked/>
	<a href="#" style="cursor: help;" title="<?php _e("Enables Font Awesome webfont for radios, checkboxes and other icons. Can not be disabled for the Bootstrap framework.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label span3"><?php _e("Targeted Device","wpas");?></label>
	<div class="controls span9">
	<select name="form-targeted_device" id="form-targeted_device" class="input-medium">
	<option value="desktops" selected="selected"><?php _e("Desktops","wpas");?></option>
	<option value="phones"><?php _e("Phones","wpas");?></option>
	<option value="tablets"><?php _e("Tablets","wpas");?></option>
	<option value="large_desktops"><?php _e("Large Desktops","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the targeted device that your form will primarily be displayed on.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	<div class="control-group row-fluid" id="form-label_position_div"> 
	<label class="control-label span3"><?php _e("Label Placement","wpas");?></label>
	<div class="controls span9">
	<select name="form-label_position" id="form-label_position" class="input-medium">
	<option value="top" selected="selected"><?php _e("Top","wpas");?></option>
	<option value="left"><?php _e("Left","wpas");?></option>
	<option value="inside"><?php _e("Inside","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the field label position relative to the field input location. Options are Top,Left or Inside. Pick your label placement based on the space you have available for the form. Min 680px required for inside/top label placement with 3 column layout. If you enabled operators in your search form, you will need more space for multi-layout designs. You can always adjust the width css element of your form container when needed. Enabling operators will give access to all of your data so limiting access by role may always be a good idea.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>

        <div class="control-group row-fluid" id="form-element-size_div">
        <label class="control-label span3"><?php _e("Element Size","wpas");?></label>
        <div class="controls span9">
        <select name="form-element_size" id="form-element_size" class="input-medium">
        <option value="medium" selected="selected"><?php _e("Medium","wpas");?></option>
        <option value="small"><?php _e("Small","wpas");?></option>
        <option value="large"><?php _e("Large","wpas");?></option>
        </select>
        <a href="#" style="cursor: help;" title="<?php _e("Sets the field height to create larger or smaller form controls that match button sizes.","wpas");?>">
         <i class="icon-info-sign"></i></a>
        </div>
        </div>	

	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Display Radios and Checkboxes Inline","wpas");?>
	<input name="form-display_inline" id="form-display_inline" type="checkbox" value="1" checked/>
	<a href="#" style="cursor: help;" title="<?php _e("Sets a series of checkboxes or radios appear on the same line.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-ajax_search_div" style="display:none;">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable Ajax","wpas");?>
	<input name="form-ajax_search" id="form-ajax_search" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("Enables ajax when displaying search results without reloading the page.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-enable_operators_div" style="display:none;">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable Search Operators","wpas");?>
	<input name="form-enable_operators" id="form-enable_operators" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("Enables operators in search forms such as '<, >, Is, Search' etc.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-result_msg_div" style="display:none;">
	<label class="control-label span3"><?php _e("Results Header","wpas");?></label>
	<div class="controls span9">
<?php display_tinymce('form-result_msg','Below are your search results.',1); ?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the text which will be displayed when there are results to show. You can put in a title and a description of the data returned or a header row containing a column titles. Remember the results come from the view you attached to the form. So layout of the form results must be done in the view. This header text will be displayed between the form and results so anything outside of form and results boundry can be placed in a page before the form shortcode.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("No Access Message","wpas");?></label>
	<div class="controls span9">
<?php display_tinymce('form-not_loggedin_msg','You are not allowed to access to this area. Please contact the site administrator.'); ?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the text which will be displayed to users that do not have access to this form.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-noresult_msg_div" style="display:none;">
	<label class="control-label span3"><?php _e("No Results Message","wpas");?></label>
	<div class="controls span9">
<?php display_tinymce('form-noresult_msg',__('Your search returned no results.','wpas')); ?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the text which will be displayed when there are no results to show.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div>	<!--formtabs-1-->			

	<div id="formtabs-2" class="tab-pane fade in">
	<div class="control-group row-fluid" id="form-submit_status_div"> 
	<label class="control-label span3"><?php _e("Submit Status","wpas");?></label>
	<div class="controls span9">
	<select name="form-submit_status" id="form-submit_status" class="input-medium">
	<option value="publish"><?php _e("Publish","wpas");?></option>
	<option value="pending"><?php _e("Pending","wpas");?></option>
	<option value="draft"><?php _e("Draft","wpas");?></option>
	<option value="future"><?php _e("Future","wpas");?></option>
	<option value="private"><?php _e("Private","wpas");?></option>
	<option value="trash"><?php _e("Trash","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the status of all form entries. Publish - Entry is available immediately. Pending - Entry is pending review. Draft - Entry is in draft status. Future - Entry is will be published in the future. Private - Entry is not visible to users who are not logged in. Trash - Entry is in trashbin. Default is publish.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-submit_button_type_div"> 
	<label class="control-label span3"><?php _e("Submit Button Type","wpas");?></label>
	<div class="controls span9">
	<select name="form-submit_button_type" id="form-submit_button_type" class="input-medium">
	<option value="btn-standard" selected="selected"><?php _e("Standard (White - #FFFFF)","wpas");?></option>
	<option value="btn-primary"><?php _e("Primary (Blue - #006DCC)","wpas");?></option>
	<option value="btn-info"><?php _e("Info (Light Blue - #49AFCD)","wpas");?></option>
	<option value="btn-success"><?php _e("Success (Green - #5BB75B)","wpas");?></option>
	<option value="btn-warning"><?php _e("Warning (Orange - #FAA732)","wpas");?></option>
	<option value="btn-danger"><?php _e("Danger (Red - #DA4F49)","wpas");?></option>
	<option value="btn-inverse"><?php _e("Inverse (Black - #363636)","wpas");?></option>
	<option value="btn-link"><?php _e("Link (Blue -  #0088CC)","wpas");?></option>
	<option value="btn-jui"><?php _e("jQuery UI (Themeable)","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Standard button is a white background button with a darkgray border. Primary button provides extra visual weight and identifies the primary action in a set of buttons. Info button is used as an alternative to the standard style. Success button indicates a successful or positive action. Warning button indicates caution should be taken with this action. Danger button indicates a dangerous or potentially negative action. Inverse button is alternate dark gray button, not tied to a semantic action or use. Link button deemphasizes a button by making it look like a link while maintaining button behavior.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Submit Button Label","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-submit_button_label" id="form-submit_button_label" type="text" placeholder="<?php _e("e.g. Submit","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the submit button label of your form. Max:30 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a> (<?php _e("Default: Submit","wpas");?>)
	</div>
	</div>	
	<div class="control-group row-fluid" id="form-submit_button_size_div"> 
	<label class="control-label span3"><?php _e("Submit Button Size","wpas");?></label>
	<div class="controls span9">
	<select name="form-submit_button_size" id="form-submit_button_size" class="input-medium">
	<option value="btn-std" selected="selected"><?php _e("Standard","wpas");?></option>
	<option value="btn-xlarge"><?php _e("XLarge","wpas");?></option>
	<option value="btn-large"><?php _e("Large","wpas");?></option>
	<option value="btn-small"><?php _e("Small","wpas");?></option>
	<option value="btn-mini"><?php _e("Mini","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the submit button size of your form.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div id="form-submit_button_fa_div" name="form-submit_button_fa_div">
	<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Submit Button Icon Class","wpas");?></label>
		<div class="controls span9">
		<input class="input-medium" name="form-submit_button_fa" id="form-submit_button_fa" type="text" placeholder="" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Sets the font awesome icon which will be displayed next to the button text.","wpas");?>">
		<i class="icon-info-sign"></i></a><a href="http://emarketdesign.com/documentation/wp-app-studio-documentation/cheatsheet/" target="_blank"><?php _e("Cheatsheet","wpas");?></a>
		</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label span3"><?php _e("Submit Button Icon Size","wpas");?></label>
	<div class="controls span9">
	<select name="form-submit_button_fa_size" id="form-submit_button_fa_size" class="input-medium">
	<option value="" selected="selected"><?php _e("Standard","wpas");?></option>
	<option value="fa-lg"><?php _e("Large","wpas");?></option>
	<option value="fa-2x"><?php _e("2x","wpas");?></option>
	<option value="fa-3x"><?php _e("3x","wpas");?></option>
	<option value="fa-4x"><?php _e("4x","wpas");?></option>
	<option value="fa-5x"><?php _e("5x","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the size of font awesome icon which will be displayed next to the button text.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-submit_button_position_div"> 
	<label class="control-label span3"><?php _e("Submit Button Icon Position","wpas");?></label>
	<div class="controls span9">
	<select name="form-submit_button_fa_pos" id="form-submit_button_fa_pos" class="input-medium">
	<option value="left" selected="selected"><?php _e("Left","wpas");?></option>
	<option value="right"><?php _e("Right","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the position of icon within the submit button.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div> <!-- form-submit_button_fa_div -->
	<div class="control-group row-fluid" id="form-show_captcha_div"> 
	<label class="control-label span3"><?php _e("Show Captcha","wpas");?></label>
	<div class="controls span9">
	<select name="form-show_captcha" id="form-show_captcha" class="input-medium">
	<option value="show-always"><?php _e("Always Show","wpas");?></option>
	<option value="show-to-visitors"><?php _e("Visitors Only","wpas");?></option>
	<option value="never-show"><?php _e("Never Show","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets Captcha display option. WPAS forms use the - honeypot - technique by default however CAPTCHAs can also be used for even stronger protection. Always Show displays captcha for everybody. Visitors Only option shows it for only visitors. Never Show option disables it.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Disable After","wpas");?></label>
	<div class="controls span9">
	<input class="input-mini" name="form-disable_after" id="form-disable_after" type="text" placeholder="<?php _e("e.g. 5","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Disables form submissions after the set number reached. Leave blank for no limit.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div>	<!--formtabs-2-->

	<div id="formtabs-3" class="tab-pane fade in">
	<div class="control-group row-fluid" id="form-confirm_method_div"> 
	<label class="control-label span3"><?php _e("Confirmation Method","wpas");?></label>
	<div class="controls span9">
	<select name="form-confirm_method" id="form-confirm_method" class="input-medium">
	<option value="text" selected><?php _e("Show text","wpas");?></option>
	<option value="redirect"><?php _e("Redirect","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the event that will occur after a successful entry. Show text option display a text message. Redirect option redirects users toanother URL.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-confirm_txt_div">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable Ajax","wpas");?>
	<input name="form-ajax" id="form-ajax" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("When set ajax form submissions are enabled.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="form-show_after_submit_div"> 
	<label class="control-label span3"><?php _e("After Submit","wpas");?></label>
	<div class="controls span9">
	<select name="form-show_after_submit" id="form-show_after_submit" class="input-medium">
	<option value="show" selected><?php _e("Show Form","wpas");?></option>
	<option value="clear"><?php _e("Clear Form","wpas");?></option>
	<option value="hide"><?php _e("Hide Form","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets what users see after a successful submission. Show Form option shows the completed form. Clear Form option resets all fields to their defaults. Hide Form option hides the form from the user who completed the entry.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Success Text","wpas");?></label>
	<div class="controls span9">
	<textarea id="form-confirm_success_txt" name="form-confirm_success_txt" class="input-xlarge" rows="3">
	<?php _e("Thanks for your submission.","wpas");?>
	</textarea>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the text which will be displayed to users after a successful entry.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Error Text","wpas");?></label>
	<div class="controls span9">
	<textarea id="form-confirm_error_txt" name="form-confirm_error_txt" class="input-xlarge" rows="3">
	<?php _e("There has been an error when submitting your entry. Please contact the site administrator.","wpas");?>
	</textarea>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the text which will be displayed to users after an unsuccessful entry.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>		
	</div>
	<div class="control-group row-fluid" id="form-confirm_url_div" style="display:none;">
	<label class="control-label span3"><?php _e("Confirmation URL","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_url" id="form-confirm_url" type="text" placeholder="<?php _e("e.g. http://example.com/myform-confirm.php","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("When set, after a successful entry, users get redirected to another url. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div>	<!--formtabs-3-->
	<div id="formtabs-4" class="tab-pane fade in">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable User Notification","wpas");?>
	<input name="form-email_user_confirm" id="form-email_user_confirm" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("When checked, the user will be notified via email.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div id="form-email_user_div" style="display:none;"> 
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Email Send To","wpas");?></label>
	<div class="controls span9">
	<select name="form-confirm_sendto" id="form-confirm_sendto" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Select the email attribute you want to send the receipt to. The user email address must be available in the attribute selected otherwise no emails are sent.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Reply To","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_replyto" id="form-confirm_replyto" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address users can reply to the receipt. Multiple email addresses must be separated by coma. Leave it blank if you don't want them to email you. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email CC","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_user_cc" id="form-confirm_user_cc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that user notification will be CCed. Multiple email addresses must be separated by coma. Leave it blank if you don't want them to email you. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email BCC","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_user_bcc" id="form-confirm_user_bcc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that user notification will be BCCed. Multiple email addresses must be separated by coma. Leave it blank if you don't want them to email you. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Email Subject","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_subject" id="form-confirm_subject" type="text" placeholder="<?php _e("e.g. Thanks for filling out my form","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the subject field of user emails. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Message","wpas");?></label>
	<div class="controls span9">
<?php display_tinymce('form-confirm_msg','',0,1); ?>
	<a href="#" style="cursor: help;" title="<?php _e("A short message confirming a successful submission.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	</div>
	</div> <!--formtabs-4-->	
	<div id="formtabs-5" class="tab-pane fade in">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable Admin Notification","wpas");?>
	<input name="form-email_admin_confirm" id="form-email_admin_confirm" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("When checked, enables admin notification for each submission via email.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div id="form-email_admin_div" style="display:none;"> 
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Email Send To","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_sendto" id="form-confirm_admin_sendto" type="text" placeholder="<?php _e("e.g. admin-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("The email address admins to get messages when a successful entry occurred. Multiple email addresses must be separated by coma.Leave it blank if you don't want them to get emails. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Reply To","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_replyto" id="form-confirm_admin_replyto" type="text" placeholder="<?php _e("e.g. admin-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("The email address admins to reply to the receipt. Multiple email addresses must be separated by coma. Leave it blank if you don't want them to email you. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Cc","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_cc" id="form-confirm_admin_cc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that the admin notification will be CCed. Multiple email addresses must be separated by coma. Leave it blank if you don't want them to email you. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Bcc","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_bcc" id="form-confirm_admin_bcc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that the admin notification will be BCCed. Multiple email addresses must be separated by coma. Leave it blank if you don't want them to email you. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Email Subject","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="form-confirm_admin_subject" id="form-confirm_admin_subject" type="text" placeholder="<?php _e("e.g. Someone filled out my form","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the subject of admin emails. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Message","wpas");?></label>
	<div class="controls span9">
<?php display_tinymce('form-confirm_admin_msg','',0,1); ?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets a message confirming a successful submission.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	</div>
	</div> <!--formtabs-5-->	
	<div id="formtabs-6" class="tab-pane fade in">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable Form Scheduling","wpas");?>
	<input name="form-enable_form_schedule" id="form-enable_form_schedule" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("Set to make the form automatically become active or inactive at a certain date.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div id="form-schedule_div" style="display:none;"> 
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Start Datetime","wpas");?></label>
	<div id="form-datetime_start" class="controls span9">
	<input class="input-medium" name="form-schedule_start" id="form-schedule_start" type="text">
	<a href="#" style="cursor: help;" title="<?php _e("The start datetime after which form will be active.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("End Datetime","wpas");?></label>
	<div class="controls span9">
	<input class="input-medium" name="form-schedule_end" id="form-schedule_end" type="text">
	<a href="#" style="cursor: help;" title="<?php _e("The last form submission datetime after which form will be inactive.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>	
	</div>
	</div>	<!--formtabs-6-->	
	
	</div>	<!--tab-contform-->	
	</div><!--field-container-->
	<div class="control-group">
	<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
	<button class="btn  btn-primary pull-right layout-buttons" id="save-form" type="submit" value="Save"><i class="icon-save"></i><?php _e("Save","wpas");?></button>
	</div>
</fieldset>
</form>
<?php
}
?>
