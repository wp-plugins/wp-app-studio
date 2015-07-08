<?php
function wpas_add_glob_form(){
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#glob-type').click(function(){
		$(this).showDef($(this).val());
	});
	$.fn.showDef = function (type){
		switch(type){
			case 'textarea':
			case 'wysiwyg':
				$('#glob-req-div').show();
				$('#glob-dflt-div').hide();
				$('#glob-dflt-ta-div').show();
				$('#glob-values-div').hide();
				$('#glob-dflt-checked-div').hide();
				break;
			case 'checkbox_list':
			case 'multi_select':
			case 'radio':
			case 'select':
				$('#glob-req-div').show();
				$('#glob-dflt-div').show();
				$('#glob-dflt-ta-div').hide();
				$('#glob-values-div').show();
				$('#glob-dflt-checked-div').hide();
				break;
			case 'checkbox':
				$('#glob-req-div').show();
				$('#glob-dflt-checked-div').show();
				$('#glob-dflt-div').hide();
				$('#glob-dflt-ta-div').hide();
				$('#glob-values-div').hide();
				break;
			case 'map':
				$('#glob-req-div').hide();
				$('#glob-dflt-div').hide();
				$('#glob-dflt-ta-div').hide();
				$('#glob-values-div').hide();
				break;
			default:
				$('#glob-req-div').show();
				$('#glob-dflt-div').show();
				$('#glob-dflt-ta-div').hide();
				$('#glob-values-div').hide();
				$('#glob-dflt-checked-div').hide();
				break;
		}
	}
});
</script>
<form action="" method="post" id="glob-form" class="form-horizontal">
<input type="hidden" id="app" name="app" value="">
<input type="hidden" value="" name="glob" id="glob">
<fieldset>
	<div class="field-container">
	<div class="well">
	<div class="row-fluid">
	<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Globals are app-wide tags which can be used in your view layouts. In contrast to entity attributes, users can set the global value in the plugin settings page, thus applying to all entity records.","wpas");?>">
	<?php _e("HELP","wpas");?></a></div></div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Name","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="glob-name" id="glob-name" type="text" placeholder="<?php _e("e.g. ","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Unique identifier for global attribute. Can not contain capital letters, dashes or spaces. Between 3 and 30 characters.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Label","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="glob-label" id="glob-label" type="text" placeholder="<?php _e("e.g. Product Name","wpas");?>" value="">
	<a href="#" style="cursor: help;" title="<?php _e("User friendly name for global attribute. It will appear on the SETTINGS page of your app.","wpas");?>">
	<i class="icon-info-sign"></i></a>                          
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Description","wpas");?></label>
	<div class="controls span9">
	<textarea id="glob-desc" name="glob-desc" class="wpas-std-textarea" placeholder="<?php _e("Write a brief description on how the global will be used.","wpas");?>" ></textarea>
	<a href="#" style="cursor: help;" title="<?php _e("Instructions or help-text related to your attribute.","wpas");?>">
	<i class="icon-info-sign"></i></a>          		
	</div>
    	</div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Type","wpas");?></label>
	<div class="controls span9">
	<select name="glob-type" id="glob-type">
		<option selected="selected" value=""><?php _e("Please select","wpas");?></option>
		<option value="text"><?php _e("Text","wpas");?></option>
		<option value="textarea"><?php _e("Text Area","wpas");?></option>
		<option value="wysiwyg"><?php _e("Wysiwyg Editor","wpas");?></option>
		<option value="checkbox"><?php _e("Checkbox","wpas");?></option>
		<option value="checkbox_list"><?php _e("Checkbox List","wpas");?></option>
		<option value="radio"><?php _e("Radios","wpas");?></option>
		<option value="select"><?php _e("Select","wpas");?></option>
		<option value="multi_select"><?php _e("MultiSelect","wpas");?></option>
		<option value="map"><?php _e("Map","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Defines how the global attribute will be displayed.","wpas");?>">
	<i class="icon-info-sign"></i></a>      
	</div>
	</div>
	<div class="control-group" id="glob-req-div" style="display:none;">
	<label class="control-label span3"></label>
	<div class="controls span9">
	<label class="checkbox"><?php _e("Required","wpas");?>
	<input name="glob-required" id="glob-required" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("Makes the global required so it can not be blank or unset.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="glob-values-div" style="display:none;">
        <label class="control-label span3 req"><?php _e("Values","wpas");?></label>
        <div class="controls span9">
        <textarea id="glob-values" name="glob-values" class="wpas-std-textarea" placeholder="e.g. blue;red;white " ></textarea>
        <a href="#" style="cursor: help;" title="<?php _e("Enter semicolon separated option labels for the global. There must be only one semicolon between the values. Optionally, you can define value-label combinations using {Value}Label format. If the predined value does not exist, it is automatically created based on the label.","wpas");?>">
        <i class="icon-info-sign"></i></a>
        </div>
</div>
	<div class="control-group row-fluid" id="glob-dflt-ta-div" style="display:none;">
	<label class="control-label span3"><?php _e("Default Value","wpas");?></label>
	<div class="controls span9">
	<textarea name="glob-dflt-ta" id="glob-dflt-ta"></textarea>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the default value(s) for the global, separated by a semicolon. Multiple default values can only be set for select with multiple option and checkbox list types. You must enter the value from Values field and not the label.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="glob-dflt-div" style="display:none;">
	<label class="control-label span3"><?php _e("Default Value","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="glob-dflt" id="glob-dflt" type="text" placeholder="" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the default value(s) for the attribute, separated by a semicolon. Multiple default values can only be set for select with multiple option and checkbox list types. You must enter the value from Values field and not the label.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="glob-dflt-checked-div" name="glob-dflt-checked-div" style="display:none;">
	<label class="control-label span3"></label>
	<div class="controls span9">
	<label class="checkbox"><?php _e("Default Value","wpas");?>
	<input name="glob-dflt-checked" id="glob-dflt-checked" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("Default is unchecked.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</label>
	</div>
	</div>
	<div class="control-group">
	<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
	<button class="btn  btn-primary pull-right layout-buttons" id="save-glob" type="submit" value="Save"><i class="icon-save"></i><?php _e("Save","wpas");?></button>
	</div>
</fieldset>
</form>
<?php
}
