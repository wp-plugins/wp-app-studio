<?php
function wpas_add_connection_form()
{
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
        $.fn.initConnection = function(app_id) {
                $.get(ajaxurl,{action:'wpas_get_entities',type:'form',app_id:app_id}, function(response){
                        $('#add-connection-div #connection-entity').html(response);
			$('#connection-fields').hide();
			$('#connection-inline-entity').hide();
                });
        }
	$('#connection-type').click(function(){
		type = $(this).val();
		$(this).showCon(type);
	});
	$.fn.showCon = function (type){
		if(type == 'inc_email'){
			$('#connection-fields').show();
			$('#connection-inline-entity').hide();
		}
		else if(type == 'inline_entity'){
			$('#connection-fields').hide();
			$('#connection-inline-entity').show();
		}
		else {
			$('#connection-inline-entity').hide();
			$('#connection-fields').hide();
		}
	}
	$('#connection-entity').click(function(){
		ent_id = $(this).val();
		app_id = $('input#app').val();
		con_type = $('#connection-type').val();
		if(con_type == 'inc_email'){
			$(this).incEmail(app_id,ent_id,'','');
		}
		else if(con_type == 'inline_entity'){
			$(this).inlineEnt(app_id,ent_id,'','');
		}
	});
	$.fn.inlineEnt = function (app_id,ent_id,val_ent,val_loc){
		$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:'inline_tax',chart_ent:ent_id,values:val_ent}, function(response){
			$('#add-connection-div #connection-inl_entity').html(response);
			$('#add-connection-div #connection-inl_entity-div').show();
		});
		$.get(ajaxurl,{action:'wpas_get_inline_ent_attr',app_id:app_id,ent_id:ent_id,values:val_loc}, function(response){
			$('#add-connection-div #connection-inl_loc').html(response);
		});
	}
	$.fn.incEmail = function (app_id,ent_id,ftype,val){
		if(ent_id != '' && app_id != ''){
			if(ftype == '' || ftype == 'tax'){
				$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:'tax',chart_ent:ent_id,values:val}, function(response){
					$('#add-connection-div #connection-inc_tax').html(response);
				});
			}
			if(val != '' && ftype == 'subject')
			{
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'name',app_id:app_id,ent_id:ent_id,value:val}, function(response){
					$('#add-connection-div #connection-inc_subject').html(response['pre']+response['list']);
				}, 'json');
			}
			if(ftype == 'from')
			{
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'email',app_id:app_id,ent_id:ent_id,value:val}, function(response){
					$('#connection-inc_email_div').show();
					$('#add-connection-div #connection-inc_email').html(response['pre']+response['list']);
				}, 'json');
			}	
			if(ftype == 'from_name')
			{
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'name',app_id:app_id,ent_id:ent_id,value:val}, function(response){
					$('#connection-inc_name_div').show();
					$('#add-connection-div #connection-inc_name').html(response['list']);
				}, 'json');
			}		
			if(ftype == '' && $('#connection-inc_vis_submit').attr('checked')){
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'email',app_id:app_id,ent_id:ent_id}, function(response){
					$('#add-connection-div #connection-inc_email').html(response['pre']+response['list']);
				}, 'json');
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'name',app_id:app_id,ent_id:ent_id}, function(response){
					$('#add-connection-div #connection-inc_name').html(response['list']);
					$('#add-connection-div #connection-inc_subject').html(response['pre']+response['list']); 
				}, 'json');
			}
			else if(ftype == '' && val == '') {
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'name',app_id:app_id,ent_id:ent_id}, function(response){
					$('#add-connection-div #connection-inc_subject').html(response['pre']+response['list']); 
				}, 'json');
				$('#connection-inc_email_div').hide();
				$('#connection-inc_name_div').hide();
			}
				
			if(ftype == '' || ftype == 'date')
			{
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'pdate',app_id:app_id,ent_id:ent_id,value:val}, function(response){
					$('#add-connection-div #connection-inc_date').html(response['list']);
				}, 'json');
			}	
			if(ftype == '' || ftype == 'body')
			{
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'body',app_id:app_id,ent_id:ent_id,value:val}, function(response){
					$('#add-connection-div #connection-inc_body').html(response['pre']+response['list']);
				}, 'json');
			}
			if(ftype == '' || ftype == 'att')
			{
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'attach',app_id:app_id,ent_id:ent_id,value:val}, function(response){
					$('#add-connection-div #connection-inc_att').html(response['pre']+response['list']);
				}, 'json');
			}
		}
	}
	$('#connection-inc_vis_submit').click(function() {
		if($(this).attr('checked')){
			app_id = $('input#app').val();
			ent_id = $('input#ent').val();
			if(ent_id == ''){
				ent_id = $('#connection-entity').val();
			}
			$('#connection-inc_vis_status_div').show();
			$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'email',app_id:app_id,ent_id:ent_id}, function(response){
				$('#add-connection-div #connection-inc_email').html(response['pre']+response['list']);
			}, 'json');
			$.get(ajaxurl,{action:'wpas_get_ent_fields',type:'name',app_id:app_id,ent_id:ent_id}, function(response){
				$('#add-connection-div #connection-inc_name').html(response['list']);
			}, 'json');
			$('#connection-inc_email_div').show();
			$('#connection-inc_name_div').show();
			
		}
		else {
			$('#connection-inc_vis_status_div').hide();
			$('#connection-inc_email_div').hide();
			$('#connection-inc_name_div').hide();
		}
	});
});
</script>
	<form action="" method="post" id="connection-form" class="form-horizontal">
	<input type="hidden" id="app" name="app" value="">
	<input type="hidden" value="" name="connection" id="connection">
	<fieldset>
	<div class="field-container">
	<div class="well">
	<div class="row-fluid">
	<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Connections allow entities to get and send data from/to external apps or services such as incoming/outgoing email, twitter, facebook etc.","wpas");?>">
	<?php _e("HELP","wpas");?></a></div></div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Name","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="connection-name" id="connection-name" type="text" placeholder="<?php _e("e.g. customer_survey","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Unique identifier for connection. Can not contain capital letters, dashes or spaces. Between 3 and 30 characters.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Type","wpas");?></label>
	<div class="controls span9">
	<select name="connection-type" id="connection-type" class="input-medium">
	<option value="" selected="selected"><?php _e("Please select","wpas");?></option>
	<option value="inc_email"><?php _e("Incoming Email","wpas");?></option>
	<option value="inline_entity"><?php _e("Inline Entity","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the type of connection to be created. Incoming email is a connection type you can use to import email messages to your entities. Inline Entity is for using inline entity content in your primary entities.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Attached to Entity","wpas");?></label>
	<div class="controls span9">
	<select name="connection-entity" id="connection-entity" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the primary entity for connection. The selected entity will be used to get/send data from/to external apps or services. It will be used for the dependent selection as well.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div><!--well-->
	<div id="connection-fields">
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Submit Status","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_status" id="connection-inc_status" class="input-medium">
	<option value="publish"><?php _e("Publish","wpas");?></option>
	<option value="draft"><?php _e("Draft","wpas");?></option>
	<option value="private"><?php _e("Private","wpas");?></option>
	<option value="trash"><?php _e("Trash","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the status of all entries for the users who have -edit_published- capability. Available values are Publish, Draft, Private, and Trash.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
    	<label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Allow Visitors to Submit","wpas"); ?>
			<input name="connection-inc_vis_submit" id="connection-inc_vis_submit" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("It allows users who have NOT -edit_published- capability to send content through connections.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="connection-inc_vis_status_div" style="display:none;"> 
	<label class="control-label req span3"><?php _e("Visitor Submit Status","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_vis_status" id="connection-inc_vis_status" class="input-medium">
	<option value="publish"><?php _e("Publish","wpas");?></option>
	<option value="draft"><?php _e("Draft","wpas");?></option>
	<option value="private"><?php _e("Private","wpas");?></option>
	<option value="trash"><?php _e("Trash","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the status of all entries for the users who have NOT -edit_published- capability. Available values are Publish, Draft, Private, and Trash.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label span3"><?php _e("Default Taxonomy","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_tax" id="connection-inc_tax" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the default taxonomy of the imported entity records.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="connection-inc_email_div"> 
	<label class="control-label req span3"><?php _e("From Address","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_email" id="connection-inc_email" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Maps the from address of an incoming message to an entity attribute. Displayed when -Allow Visitors to Submit- checked otherwise WordPress user email used.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="connection-inc_name_div"> 
	<label class="control-label req span3"><?php _e("From Name","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_name[]" id="connection-inc_name" class="input-medium" multiple>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Maps the from name of an incoming message to an entity attribute. Displayed when -Allow Visitors to Submit- checked otherwise WordPress user name used.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Subject","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_subject" id="connection-inc_subject" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Maps the subject text of an incoming message to an entity attribute.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Date","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_date[]" id="connection-inc_date" class="input-medium" multiple>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Maps the date of an incoming message to an entity attribute.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Body","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_body" id="connection-inc_body" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Maps the body of an incoming message to an entity attribute.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label span3"><?php _e("Attachment","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inc_att" id="connection-inc_att" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Maps the attachment of an incoming message to an entity attribute.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	</div><!-- end of connection-fields -->
	<div id="connection-inline-entity">
	<div class="control-group row-fluid" id="connection-inl_entity-div" style="display:none;"> 
	<label class="control-label req span3"><?php _e("Inline Entity","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inl_entity" id="connection-inl_entity" class="input-medium">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the inline entity for connection. Inline entities can not shared by multiple entities.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Button Location","wpas");?></label>
	<div class="controls span9">
	<select name="connection-inl_loc[]" id="connection-inl_loc" class="input-medium" multiple>
	<option value="" selected="selected"><?php _e("Please select","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Set the location of the inline entity button; content, excerpt, or comment editors can be used. If you select content, quick tag editor button is added by default.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Button Label","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="connection-inl_blabel" id="connection-inl_blabel" type="text" placeholder="<?php _e("e.g. customer_survey","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the inline entity button label.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Button Icon","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="connection-inl_bicon" id="connection-inl_bicon" type="text" placeholder="<?php _e("e.g. fa-heart","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the inline entity button icon. Use cheat-sheet for possible icons.","wpas");?>">
	<i class="icon-info-sign"></i></a><a href="https://wpas.emdplugins.com/articles/supported-icons/" target="_blank"><?php _e("Cheatsheet","wpas");?></a>
	</div>
	</div>

	</div><!-- end of inline entity -->	
	<div class="control-group">
	<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
	<button class="btn  btn-primary pull-right layout-buttons" id="save-connection" type="submit" value="Save"><i class="icon-save"></i><?php _e("Save","wpas");?></button>
	</div>
</fieldset>
</form>
<?php
}
