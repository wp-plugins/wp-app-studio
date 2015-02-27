<?php
function wpas_add_notify_form()
{
?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$.fn.getEmails = function (app_id,level,attach_to,attach_tax,values) {
			attach_to = attach_to || '';
			attach_tax = attach_tax || '';
			values = values || '';
			if(attach_to == '')
			{
				if(level == 'tax')
				{
					attach_to = $('#notify-attach_ent').val();
					attach_tax = $('#notify-attached_to').val();
				}
				else
				{
					attach_to = $('#notify-attached_to').val();
					attach_tax = '';
				}
			}
              		$.get(ajaxurl,{action:'wpas_get_email_attrs',level:level,attach_to:attach_to,attach_tax:attach_tax,app_id:app_id,values:values}, function(response)
			{
				$('#add-notify-div #notify-confirm_sendto').html(response);
			});
		}
		$('#notify-attached_to').click(function() {
			app_id = $('input#app').val();
			level = $('#notify-level').val();
			if(level == 'tax')
			{
				tax_id = $(this).val();
				$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,tax_id:tax_id}, function(response)
                        	{
					$('#notify-attach_ent').html(response);
					$('#notify-attach_ent_div').show();
                        	});
			}
			else
			{
				$('#notify-attach_ent_div').hide();
				if($('#notify-email_user_confirm').attr('checked'))
				{
					$(this).getEmails(app_id,level);
					$(this).showNotifyTags(app_id,level,'notify-user-tags');
				}
				if($('#notify-email_admin_confirm').attr('checked'))
				{
					$(this).showNotifyTags(app_id,level,'notify-admin-tags');
				}
			}
		});
		$.fn.showNotifyTags = function (app_id,level,div_id,values){
			values = values || '';
			type = 'tag-rel';
			switch (level) {
				case 'attr': 
					if(values != ''){
						comp = values['notify-attached_to'].split("__");
					}
					else {
						comp = $('#notify-attached_to').val().split("__");
					}
					comp_id = comp[1];
					break;
				case 'tax':
					if(values != ''){
						comp_id = values['notify-attach_ent'];
					}
					else {
						comp_id = $('#notify-attach_ent').val();
					}
					break;
				case 'rel':
					type = 'notify-rel';
					if(values != ''){
						comp_id = values['notify-attached_to'];
					}
					else {
						comp_id = $('#notify-attached_to').val();
					}
					break;
				case 'entity':
				case 'com':
				default:
					if(values != ''){
						comp_id = values['notify-attached_to'];
					}
					else {
						comp_id = $('#notify-attached_to').val();
					}
					break;
			}
			$.get(ajaxurl,{action:'wpas_get_layout_tags',type:type,app_id:app_id,comp_id:comp_id}, function(response){
				$('#'+div_id).html(response);
			});
		}
		$('#notify-attach_ent').click(function() {
			app_id = $('input#app').val();
			if($('#notify-email_user_confirm').attr('checked'))
			{
				$(this).getEmails(app_id,$('#notify-level').val());
				$(this).showNotifyTags(app_id,'tax','notify-user-tags');
			}
			if($('#notify-email_admin_confirm').attr('checked'))
			{
				$(this).showNotifyTags(app_id,'tax','notify-admin-tags');
			}
		});


		$('#notify-email_user_div').hide();
		$('#notify-change_val_div').hide();
		$('#notify-change').click(function() {
			notify_level = $('#notify-level').val();
			if($(this).attr('checked') && (notify_level == 'attr' || notify_level == 'tax'))
			{
				$('#notify-change_val_div').show();
			}
			else
			{
				$('#notify-change_val_div').hide();
				$('#notify-change_val').val('');
			}
		});
		$.fn.setLevel = function (type, change_sel, sel_val) {
			app_id = $('input#app').val();
			$.get(ajaxurl,{action:'wpas_get_notify_attach',app_id:app_id,type:type, value:sel_val},function(response) {
				$('#notify-attached_to').html(response);
			});
			if(change_sel == 1  && (type == 'attr' || type== 'tax'))
			{
				$('#notify-change_val_div').show();
			}
			else
			{
				$('#notify-change_val_div').hide();
				$('#notify-change_val').val('');
			}
			switch (type) {
				case 'entity':
					$('#notify-front-add-div').show();
					$('#notify-back-add-div').show();
					$('#notify-add-div').hide();
					$('#notify-delete-div').show();
					$('#notify-trash-div').show();
					$('#notify-change-div').show();
					break;
				case 'com':
					$('#notify-front-add-div').hide();
					$('#notify-back-add-div').hide();
					$('#notify-add-div').show();
					$('#notify-delete-div').show();
					$('#notify-trash-div').show();
					$('#notify-change-div').show();
					break;
				case 'rel':
					$('#notify-back-add-div').show();
					$('#notify-delete-div').show();
					$('#notify-change-div').hide();
					$('#notify-add-div').hide();
					$('#notify-trash-div').hide();
					$('#notify-front-add-div').hide();
					break;	
				case 'attr':
				case 'tax':
					$('#notify-front-add-div').hide();
					$('#notify-back-add-div').hide();
					$('#notify-delete-div').hide();
					$('#notify-trash-div').hide();
					$('#notify-add-div').hide();
					$('#notify-change-div').show();
					break;
			}
			if(type != 'tax')
			{
				$('#notify-attach_ent_div').hide();
			}
		}
		$('#notify-level').click(function() {
			change_sel = 0;
			if($('#notify-change').attr('checked'))
			{
				change_sel = 1;
			}
			$(this).setLevel($(this).val(),change_sel);
		});
	$('#notify-email_user_confirm').click(function() {
		if($(this).attr('checked'))
		{
			app_id = $('input#app').val();
			$('#notify-email_user_div').show();
			level = $('#notify-level').val();
			$(this).getEmails(app_id,level);
			$(this).showNotifyTags(app_id,level,'notify-user-tags');
		}
		else
		{
			$('#notify-email_user_div').hide();
		}
	});
	$('#notify-email_admin_confirm').click(function() {
		if($(this).attr('checked'))
		{
			$('#notify-email_admin_div').show();
			level = $('#notify-level').val();
			$(this).showNotifyTags(app_id,level,'notify-admin-tags');
		}
		else
		{
			$('#notify-email_admin_div').hide();
		}
	});
	});
</script>
	<form action="" method="post" id="notify-form" class="form-horizontal">
	<input type="hidden" id="app" name="app" value="">
	<input type="hidden" value="" name="notify" id="notify">
	<fieldset>
	<div class="field-container">
	<div class="well">
	<div class="row-fluid">
	<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Forms allow a user to enter data directly to your entities,taxonomies, and relationships. Forms are the main data entry interface for your web and mobile apps.","wpas");?>">
	<?php _e("HELP","wpas");?></a></div></div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Name","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-name" id="notify-name" type="text" placeholder="<?php _e("e.g. customer_survey","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Unique identifier for the notification. Can not contain capital letters, dashes or spaces. Between 3 and 30 characters.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Label","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-label" id="notify-label" type="text" placeholder="<?php _e("e.g. customer_survey","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Label of your notification. Displayed at the app settings notifications tab after app generation.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Description","wpas"); ?></label>
	<div class="controls span9">
	<textarea class="wpas-std-textarea" name="notify-desc" id="notify-desc"></textarea>
	<a href="#" style="cursor: help;" title="<?php _e("Initial short description for the notification. Displayed at the app settings notifications tab after app generation.","wpas"); ?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid"> 
	<label class="control-label req span3"><?php _e("Level","wpas");?></label>
	<div class="controls span9">
	<select name="notify-level" id="notify-level" class="input-medium">
	<option value="entity" selected><?php _e("Entity","wpas");?></option>
	<option value="attr"><?php _e("Attribute","wpas");?></option>
	<option value="tax"><?php _e("Taxonomy","wpas");?></option>
	<option value="rel"><?php _e("Relationship","wpas");?></option>
	<option value="com"><?php _e("Comment","wpas");?></option>
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the level of notifications to be configured. Entity level allows sending notifications when there is a backend(admin) addition, frontend(forms) addition, deletion of a record, or any change in entity objects such as taxonomies, relationships etc. Attribute and taxonomy level notifications are sent when there is any value change or when the value changed to a specific value specified in the change value field. Relationship level notifications are sent when there is an addition from the backend or when a connection get deleted for a specific relationship specified at the Attached To field. Comment level notifications are sent when when the comment records, specified at the Attached to field, gets a new addition, changed, deleted or put in trash.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="notify-attached_to_div"> 
	<label class="control-label req span3"><?php _e("Attached to","wpas");?></label>
	<div class="controls span9">
	<select name="notify-attached_to" id="notify-attached_to" style="width:auto;">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the primary object for your notification. The selected object will be used to trigger notifications.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid" id="notify-attach_ent_div" style="display:none;"> 
	<label class="control-label req span3"><?php _e("Attach to Entity","wpas");?></label>
	<div class="controls span9">
	<select name="notify-attach_ent" id="notify-attach_ent" style="width:auto;">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the primary entity for your notification. The selected entity will be used to trigger notifications.","wpas");?> ">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3 req">Events</label>
	<div class="controls span2" id="notify-front-add-div"><label class="checkbox"><?php _e("Frontend Add","wpas");?>
	<input name="notify-events[]" id="notify-front-add" type="checkbox" value="front-add"/>
	</label>
	</div>
	<div class="controls span2" id="notify-back-add-div"><label class="checkbox"><?php _e("Backend Add","wpas");?>
	<input name="notify-events[]" id="notify-back-add" type="checkbox" value="back-add"/>
	</label>
	</div>
	<div class="controls span2" id="notify-add-div"><label class="checkbox"><?php _e("Add","wpas");?>
	<input name="notify-events[]" id="notify-add" type="checkbox" value="add"/>
	</label>
	</div>
	<div class="controls span2" id="notify-change-div"><label class="checkbox"><?php _e("Change","wpas");?>
	<input name="notify-events[]" id="notify-change" type="checkbox" value="change"/>
	</label>
	</div>
	<div class="controls span2" id="notify-delete-div"><label class="checkbox"><?php _e("Delete","wpas");?>
	<input name="notify-events[]" id="notify-delete" type="checkbox" value="delete"/>
	</label>
	</div>
	<div class="controls span2" id="notify-trash-div"><label class="checkbox"><?php _e("Trash","wpas");?>
	<input name="notify-events[]" id="notify-trash" type="checkbox" value="trash"/>
	</label>
	</div>
	</div>
	<div class="control-group row-fluid" id="notify-change_val_div" style="display:none;">
	<label class="control-label span3"><?php _e("Change Value","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-change_val" id="notify-change_val" type="text" placeholder="<?php _e("e.g. ","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the value which will be used to trigger notification..","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div> 
	</div><!--well-->
	<div id="notify-tabs">
	<ul id="notifyTab" class="nav nav-tabs">
	<li class="active" id="notifytabs-1-li"><a data-toggle="tab" href="#notifytabs-1"><?php _e("User Notification","wpas");?></a></li>
	<li id="notifytabs-2-li"><a data-toggle="tab" href="#notifytabs-2"><?php _e("Admin Notification","wpas");?></a></li>
	</ul>
	<div id="NotifyTabContent" class="tab-content">
	<div class="row-fluid">
	<div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Display Options tab configures user and admin notification messages to be sent when the specified event(s) occur.","wpas");?>"><?php _e("HELP","wpas");?></a></div>
	</div>
	<div id="notifytabs-1" class="tab-pane fade in active">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable User Notification","wpas");?></label>
	<input name="notify-email_user_confirm" id="notify-email_user_confirm" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("When checked, users will be get notifications. You can enable or disable user notifications from app settings page notifications tab after your app generated.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div id="notify-email_user_div">
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Email Send To","wpas");?></label>
	<div class="controls span9">
	<select name="notify-confirm_sendto[]" id="notify-confirm_sendto" style="width:auto;" multiple="multiple">
	</select>
	<a href="#" style="cursor: help;" title="<?php _e("Select the email attribute you want to send the receipt to. The user email address must be available in the attribute selected otherwise no emails are sent.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Reply To","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_replyto" id="notify-confirm_replyto" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address used for reply messages. Multiple email addresses must be separated by coma. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div> 
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email CC","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_user_cc" id="notify-confirm_user_cc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that user notification will be CCed. Multiple email addresses must be separated by coma. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div> 
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email BCC","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_user_bcc" id="notify-confirm_user_bcc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that user notification will be BCCed. Multiple email addresses must be separated by coma. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div> 
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Email Subject","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_subject" id="notify-confirm_subject" type="text" placeholder="<?php _e("e.g. Thanks for filling out my form","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the subject field of user emails. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3 req"><?php _e("Email Message","wpas");?></label>
	<div class="controls span9">
	<textarea id="notify-confirm_msg" name="notify-confirm_msg" class="wpas-std-textarea">
	</textarea>
	<a href="#" style="cursor: help;" title="<?php _e("Notification message to be sent. You can use HTML. You should use <br> or <p> tags for line breaks. Use Show Tags to customize your message with tags available to your entity.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	<div id="notify-user-tags-div" style="padding:10px 0;">
	<div style="padding:10px;">
	<button type="button" class="btn btn-mini btn-info" data-toggle="collapse" data-target="#notify-user-tags">Show Tags</button>
	</div>
	<div id="notify-user-tags" class="collapse"></div>
	</div><!-- end of notfiy-tags-div -->
	</div>
	</div>
	</div>
	</div> <!--notifytabs-1-->
	<div id="notifytabs-2" class="tab-pane fade in">
	<div class="control-group row-fluid">
	<label class="control-label span3"></label>
	<div class="controls span9"><label class="checkbox"><?php _e("Enable Admin Notification","wpas");?></label>
	<input name="notify-email_admin_confirm" id="notify-email_admin_confirm" type="checkbox" value="1"/>
	<a href="#" style="cursor: help;" title="<?php _e("When checked, admin user(s) will be get notifications. You can enable or disable user notifications from app settings page notifications tab after your app generated.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div id="notify-email_admin_div" style="display:none;">
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Send To","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_admin_sendto" id="notify-confirm_admin_sendto" type="text" placeholder="<?php _e("e.g. admin-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("The admin email address(es). Multiple email addresses must be separated by coma. When left blank no notifications get sent. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div> 
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Reply To","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_admin_replyto" id="notify-confirm_admin_replyto" type="text" placeholder="<?php _e("e.g. admin-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("The admin email address used for reply messages. Multiple email addresses must be separated by coma. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Cc","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_admin_cc" id="notify-confirm_admin_cc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that the admin notification will be CCed. Multiple email addresses must be separated by coma. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div> 
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Email Bcc","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_admin_bcc" id="notify-confirm_admin_bcc" type="text" placeholder="<?php _e("e.g. user-emails@example.com","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the email address that the admin notification will be BCCed. Multiple email addresses must be separated by coma. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div> 
	<div class="control-group row-fluid">
	<label class="control-label req span3"><?php _e("Email Subject","wpas");?></label>
	<div class="controls span9">
	<input class="input-xlarge" name="notify-confirm_admin_subject" id="notify-confirm_admin_subject" type="text" placeholder="<?php _e("The admin message subject.","wpas");?>" value="" >
	<a href="#" style="cursor: help;" title="<?php _e("Sets the subject of admin emails. Max:255 Char.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3 req"><?php _e("Email Message","wpas");?></label>
	<div class="controls span9">
	<textarea id="notify-confirm_admin_msg" name="notify-confirm_admin_msg" class="wpas-std-textarea">
	</textarea>
	<a href="#" style="cursor: help;" title="<?php _e("Notification message to be sent. You can use HTML. You should use <br> or <p> tags for line breaks. Use Show Tags to customize your message with tags available to your entity","wpas");?>">
	<i class="icon-info-sign"></i></a>
	<div id="notify-admin-tags-div" style="padding:10px 0;">
	<div style="padding:10px;">
	<button type="button" class="btn btn-mini btn-info" data-toggle="collapse" data-target="#notify-admin-tags">Show Tags</button>
	</div>
	<div id="notify-admin-tags" class="collapse"></div>
	</div><!-- end of notfiy-tags-div -->
	</div>
	</div>
	</div>
	</div> <!--notifytabs-2-->
	</div>  <!--tab-contnotify-->
	</div><!--field-container-->
	<div class="control-group">
	<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
	<button class="btn  btn-primary pull-right layout-buttons" id="save-notify" type="submit" value="Save"><i class="icon-save"></i><?php _e("Save","wpas");?></button>
	</div>
	</fieldset>
	</form>

<?php
}
?>
