jQuery(document).ready(function($) {
	var currentDeleteItem = '';
	var app = "";
	var app_id = "";
	var ent = "";
	var ent_id = "";
	var field_id = "";
	var field = "";
	var txn_id = "";
	var txn = "";
	var rel_id = "";
	var rel = "";
	var help_id = "";
	var help = "";
	var shc_id = "";
	var shc = "";
	var notify = "";
	var notify_id = "";
	var widget_id = "";
	var widget = "";
	var role = "";
	var role_id = "";
	var form= "";
	var form_id = "";
	var connection = "";
	var connection_id = "";
	var glob = "";
	var glob_id = "";

	$('#relTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});


	$.validator.setDefaults({
    		ignore: '',
//    		ignore: [],
	});


	$.ajaxSetup({
		beforeSend: function(){
		$('#loading').show();
		},
		complete: function(){
		$('#loading').hide();
		},
		success: function() {
		}
	});

	$.fn.roleValidate = function (){
		$('#role-form').validate(
		{
			onfocusout: false,
                        onkeyup: false,
                        onclick: false,
			rules: {
				'role-name':{
				minlength:3,
				maxlength:50,
                		uniqueName:['role'],
				noSpace:true,
				noCap:true,
				checkAlphaNumUnder: true,
				required:true
				},
				'role-label':{
				minlength:3,
				maxlength:50,
				required:true,
				},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
	}

	$.fn.checkColumn = function (){
		$(document).on('click','th#cb.manage-column input',function(){
                if(this.checked == true)
                {
                        $(this).closest('table').find('th.check-column :input[type=checkbox]').attr('checked',true);
                }
                else
                {
                        $(this).closest('table').find('th.check-column :input[type=checkbox]').attr('checked',false);
                }
        });
	}
	
	$.fn.editRole = function (app_id,role_id,myaction){
		//get edit role form
		$.get(ajaxurl,{action:'wpas_edit_role',app_id:app_id,role_id:role_id}, function(response){
			if(response)
			{
				$('.group1').hide();
				$('#add-role-div').html(response);
				if(myaction == 'edit')
				{
					$(this).getBreadcrumb('role');
					$('button#save-role').html('<i class="icon-save"></i>' + wpas_vars.update);
					$('button#save-role').val('Update');
					$('button#save-role').attr('id','update-role');
				}
				else if(myaction == 'add')
				{
					$(this).getBreadcrumb('add-role');
					$('button#update-role').html('<i class="icon-save"></i>'+wpas_vars.save);
					$('button#update-role').val('Save');
					$('button#update-role').attr('id','save-role');
				}
				$('#add-role-div').show();
				$(this).roleValidate();
			}
		});
			
	}

	$.fn.sortFields = function (app_id,comp_id,type){
		$('ul.sortable').sortable({
        		tolerance: 'pointer',
        		cursor: 'pointer',
        		dropOnEmpty: true,
        		connectWith: 'ul.sortable',
        		update: function(event, ui) {
				if(type == 'entity')
				{
					divId = 'ent';
				}
				else if(type == 'relationship')
				{
					divId = 'rel';
				}
				else if(type == 'help')
				{
					divId = 'help';
				}
            			var neworder = $('#'+divId+'-fld-list-div #fields-sort').sortable('toArray');
				if(type == 'entity')
				{
					var builtin = $('#builtin-fields-sort').find('li').map(function() { return this.id; }).get();
					neworder = $.merge(neworder,builtin);
				}
             			$.ajax({
					type:'POST',
					url : ajaxurl,
					data: {action: 'wpas_update_field_order',order:neworder,app:app_id,comp:comp_id,type:type,nonce:wpas_vars.nonce_update_field_order},
					success : function(response){
						if(response)
						{
							$('#'+divId+'-fld-list-div').html(response);
							$(this).sortFields(app_id,comp_id,type);
						}
					},
                		});
        		}
    		});
	}
	$.fn.showLink = function(link,list_type){
		if(link == '#list-ent-fields')
		{
			$.get(ajaxurl,{action:'wpas_list_fields',type:'entity',app_id:app_id,comp_id:ent_id}, function(response){
				$(link).html(response);
				$(this).sortFields(app_id,ent_id,'entity');
			});
			$(this).getBreadcrumb('entity');
		}
		else if(link == '#list-rel-fields')
		{
			$.get(ajaxurl,{action:'wpas_list_fields',type:'relationship',app_id:app_id,comp_id:rel_id}, function(response){
				$(link).html(response);
				$(this).sortFields(app_id,rel_id,'relationship');
			});
			$(this).getBreadcrumb('relationship');
		}
		else if(link == '#list-help-fields')
                {
                        $.get(ajaxurl,{action:'wpas_list_fields',type:'help',app_id:app_id,comp_id:help_id}, function(response){
                                $(link).html(response);
				$(this).sortFields(app_id,help_id,'help');
                        });
			$(this).getBreadcrumb('help');
                }
		else
                {
			$(this).checkColumn();
			list_type = link;
			list_type = list_type.replace('#list-','');
			$.get(ajaxurl,{action:'wpas_list_all',type:list_type,app_id:app_id}, function(response)
                	{
                        	$(link).html(response);
                	});
			$(this).getBreadcrumb('app',app,ent);
                }
                $('.group1').hide();
		$(link).show();
		
	}
	$.fn.getBreadcrumb = function(type,app1,ent1,ent_id1,app_id1){
		if(app1 != undefined)
		{
			app = app1;
		}
		if(ent1 != undefined)
		{
			if(type == 'edit_form_layout')
			{
				form = ent1;
			}
			else
			{
				ent = ent1;
			}
		}
		if(ent_id1 != undefined)
		{
			if(type == 'edit_form_layout')
			{
				form_id = ent_id1;
			}
			else
			{
				ent_id = ent_id1;
			}
		}
		if(app_id1 != undefined)
		{
			app_id = app_id1;
		}
	
		$('ul li#second').remove();
		$('ul li#third').remove();
		$('ul li#fourth').remove();
		app_link = type.replace('add-','');
		app_link = app_link.replace('-field','');
		if($.inArray(app_link, ['option','update-option','edit_layout','edit_form_layout']) != -1)
		{
			app_link = 'entity';
		}
		var app_url = '<li id=\"second\"><a href=\"#list-' + app_link + '\">' + wpas_vars.application + ' - ' + app + '</a><span class=\"divider\">/</span></li>';
		var ent_url = '<li id=\"third\"><a href=\"#list-ent-fields\">'+ wpas_vars.entity + ' - '+ent+'</a><span class=\"divider\">/</span></li>';  
		var rel_url = '<li id=\"third\"><a href=\"#list-rel-fields\">' + wpas_vars.relationship + ' - '+rel+'</a><span class=\"divider\">/</span></li>'; 
		var help_url = '<li id=\"third\"><a href=\"#list-help-fields\">' + wpas_vars.help + ' - '+help+'</a><span class=\"divider\">/</span></li>';  
		var form_url = '<li id=\"third\"><a href=\"#list-form\">' + wpas_vars.form + ' - '+form+'</a><span class=\"divider\">/</span></li>';  
		if(type == 'app')
		{
			$('ul.breadcrumb').append('<li id=\"second\" class=\"active\">' + wpas_vars.application + ' - ' + app + '</li>');
		}
		else if(type == 'entity')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.entity  + ' - '+ent+'</li>');
		}
		else if(type == 'taxonomy')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.taxonomy + ' - '+txn+'</li>');
		}
		else if(type == 'relationship')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.relationship + ' - '+rel+'</li>');
		}
		else if(type == 'relationship-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(rel_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.attribute + ' - '+field+'</li>');
		}
		else if(type == 'help-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(help_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.tab + ' - '+field+'</li>');
		}
		else if(type == 'entity-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(ent_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.attribute + ' - '+field+'</li>');
		}
		else if(type == 'help')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.help + ' - '+help+'</li>');
		}
		else if(type == 'shortcode')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.view + ' - '+shc+'</li>');
		}
		else if(type == 'notify')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.notify + ' - '+notify+'</li>');
		}
		else if(type == 'connection')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.connection + ' - '+connection+'</li>');
		}
		else if(type == 'widget')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.widget + ' - '+widget+'</li>');
		}
		else if(type == 'form')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.form + ' - '+form+'</li>');
		}
		else if(type == 'add-entity')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_entity + '</li>');
		}
		else if(type == 'add-entity-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(ent_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.add_new_attribute + '</li>');
		}
		else if(type == 'edit_layout')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(ent_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.edit_admin_layout + '</li>');
		}
		else if(type == 'edit_form_layout')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(form_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.edit_form_layout + '</li>');
		}
		else if(type == 'add-relationship-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(rel_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.add_new_attribute + '</li>');
		}
		else if(type == 'add-taxonomy')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_taxonomy + '</li>');
		}
		else if(type == 'add-relationship')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_relationship + '</li>');
		}
		else if(type == 'add-option')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_application_settings + '</li>');
		}
		else if(type == 'add-help')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_help + '</li>');
		}
		else if(type == 'add-shortcode')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_view + '</li>');
		}
		else if(type == 'add-widget')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_widget + '</li>');
		}
		else if(type == 'add-notify')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_notify + '</li>');
		}
		else if(type == 'add-connection')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_connection + '</li>');
		}
		else if(type == 'add-form')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_form + '</li>');
		}
		else if(type == 'add-help-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(help_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">' + wpas_vars.add_new_tab + '</li>');
		}
		else if(type == 'update-option')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.update_application_settings + '</li>');
		}
		else if(type == 'add-role')
		{      
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_role + '</li>');
		}
		else if(type == 'role')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.role + ' - '+role+'</li>');
		}
		else if(type == 'glob')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">' + wpas_vars.glob + ' - '+glob+'</li>');
		}
		else if(type == 'add-glob')
		{      
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">' + wpas_vars.add_new_glob + '</li>');
		}
	}
	$(document).on('click','#edit-field a,#edit-rel-field a,#edit-help-field a',function(){
		field_id = $(this).attr('href').replace('#','');
		field = $(this).parent().parent().parent().find('#field-label').html();
		if($(this).parent().attr('id') == 'edit-field')
		{
			type = 'entity';
			comp_id = ent_id;
			input_name = 'ent';
			$('.cond-div').each(function() {
				$(this).remove();
			});
		}
		else if($(this).parent().attr('id') == 'edit-rel-field')
		{
			type = 'relationship';
			comp_id = rel_id;
			input_name = 'rel';
		}
		else if($(this).parent().attr('id') == 'edit-help-field')
		{
			type = 'help';
			comp_id = help_id;
			input_name = 'help';
		}

		current_div = '#add-' + input_name + '-field-div';
	
                $('.group1').hide();
		$(current_div + ' :input').val('');
		$(current_div + ' :input[type=checkbox]').attr('checked',false);
		$(current_div + ' :input[type=radio]').attr('checked',true);
		
                $.get(ajaxurl,{action:'wpas_edit_field',app:app_id,comp:comp_id,field:field_id,type:type}, function(response)
                {
			if(response)
			{
			$('#fld_name').attr('readonly',false);
			$('#fld_type').attr('disabled',false);
			$('#fld_required').attr('disabled',false);
			cond_att_id = [];
			$.each(response[0],function (key,value) {
				console.log('key:'+key+'  val:'+value);
				if(value != undefined)
				{
					if(key == 'fld_limit_user_role')
					{
						$.get(ajaxurl,{action:'wpas_get_roles',type:'user',app_id:app_id,value:value}, function(response)
						{
							$(current_div+' #'+ key).html(response);
							$('#' + key +'_div').show();
						});
					}
					else if(key == 'fld_autoinc_start')
					{
						$('#fld_autoinc_field_div').show();
						$('#'+key).val(value);
					}
					else if(key == 'fld_autoinc_incr')
					{	
						$('#fld_autoinc_field_div').show();
						$('#'+key).val(value);
					}
					else if(key.match(/fld_cond_attr/))
					{
						cond_div_id = key.replace('fld_cond_attr_','');
						cond_att_id[cond_div_id] = value;
					}
					else if(key.match(/fld_cond_sel_value/))
					{
						div_id = key.replace('fld_cond_sel_value_','');
						$.get(ajaxurl,{action:'wpas_get_cond_div',app_id:app_id,ent_id:ent_id,field_id:field_id,div_id:div_id,attr_val:cond_att_id[div_id],val:value,val_type:'select',from:'fld'}).done(function (response){
							$('#fld_cond-list').append(response);
						});
					}
					else if(key.match(/fld_cond_value/))
					{
						div_id = key.replace('fld_cond_value_','');
						$.get(ajaxurl,{action:'wpas_get_cond_div',app_id:app_id,ent_id:ent_id,field_id:field_id,div_id:div_id,attr_val:cond_att_id[div_id],val:value,val_type:'text',from:'fld'}).done(function (response){
							$('#fld_cond-list').append(response);
						});
					}
					else if(value == 1 && key != 'fld_max_file_uploads')
					{
						if(key == 'fld_is_conditional')
						{
							$('#conditional-options').show();
						}
						if(key == 'fld_uniq_id')
						{
							$(this).changeReqMult(1);
						}
						if(key == 'fld_builtin')
						{
							$('#fld_name').attr('readonly',true);
							$('#fld_type').attr('disabled',true);
							$('#fld_builtin').val(1);
						}
						if(key == 'fld_dflt_value')
						{
							$(current_div+' #'+key).val(value);
						}
						else if($(current_div+' #'+key).attr('type') == 'radio')
						{
							$('input:radio[name="'+key+'"][value="1"]').attr('checked', true);
						}
						else
						{
							$(current_div+' #'+key).attr('checked', true);
						}
					}
					else if(value == 0)
					{
						if(key == 'fld_is_conditional')
						{
							$('#conditional-options').hide();
						}
						if(key == 'fld_uniq_id')
						{
							$(this).changeReqMult(0);
						}
						if($(current_div+' #'+key).attr('type') == 'radio')
						{
							$('input:radio[name="'+key+'"][value="0"]').attr('checked', true);
						}
					}
					else{
						if(key == 'fld_name' && value == 'blt_title')
						{
							$('#fld_required').attr('checked', true);
							$('#fld_required').attr('disabled',true);
						}
                                                if(key == 'fld_values')
                                                {
                                                     $('#fld_values_div').show();
                                                }
						else if(key == 'fld_type')
						{
							$(this).initAttr();
							$(this).changeValidateMsg(value,'edit');
							$('#conditional-options').hide();
						}
						else if(key == 'fld_hidden_func')
						{
							$(this).showAutoInc('edit',value);
						}
                                                else if(key == 'rel_fld_values')
                                                {
                                                     $('#rel_fld_values_div').show();
                                                }
						else if(key == 'rel_fld_type' && value == 'checkbox')
                				{
                        				$('#rel_fld_dflt_value_div').hide();
						}
						else if(key == 'rel_fld_type' && value != 'checkbox')
						{
                        				$('#rel_fld_dflt_value_div').show();
						}
						$(current_div +' #'+key).val(value);
					}
				}
			});
			}
		}, 'json');
				
		$('button#save-'+type+'-field').html('<i class="icon-save"></i>' + wpas_vars.update);
		$('button#save-'+type+'-field').val('Update');
		$(current_div+ ' input#' + input_name + '_field').val(field_id);
		$(current_div+ ' input#' + input_name).val(comp_id);
		$(current_div+ ' input#app').val(app_id);
		$('label.error').each(function() {
				$(this).remove();
                        });
		$('label.valid').each(function() {
				$(this).remove();
                        });

		$(current_div).show();
		$(this).getBreadcrumb(type + '-field');
	});
	$(document).on('click','#delete-field a,#delete-rel-field a,#delete-help-field a',function(){
		currentDeleteItem = $(this);
		$('#confirmdeleteModal button#delete-ok').attr('id','delete-ok-field');
		$('#confirmdeleteModal button#delete-ok-other').attr('id','delete-ok-field');
		$('#confirmdeleteModal').modal('show');
                return false;
	});
	$(document).on('click','#ent-inline-ent',function(){
		if($(this).val() == 0){
			$('#confirmdeleteInlineModal').modal('show');
                	return false;
		}
	});	
	$(document).on('click','button#delete-ok-inline',function(){
		$('#confirmdeleteInlineModal').modal('hide');
		$('#ent-inline-ent').attr('checked',true);
                return false;
	});	
	$(document).on('click','button#delete-close-inline,button#delete-cancel-inline',function(){
                $('#confirmdeleteInlineModal').modal('hide');
                return false;
        });
		
	$(document).on('click','button#delete-ok-field',function(){
		$('#confirmdeleteModal').modal('hide');
		field_id = currentDeleteItem.attr('href').replace('#','');
		field = currentDeleteItem.parent().parent().find('#field-label').html();
		if(currentDeleteItem.parent().attr('id') == 'delete-field')
		{
			type = 'entity';
			comp_id = ent_id;
			input_name = 'ent';
		}
		else if(currentDeleteItem.parent().attr('id') == 'delete-rel-field')
		{
			type = 'relationship';
			comp_id = rel_id;
			input_name = 'rel';
		}
		else if(currentDeleteItem.parent().attr('id') == 'delete-help-field')
		{
			type = 'help';
			comp_id = help_id;
			input_name = 'help';
		}

		current_div = '#' + input_name + '-fld-list-div';

                $.post(ajaxurl,{action:'wpas_delete_field',app:app_id,comp:comp_id,field:field_id,type:type,nonce:wpas_vars.nonce_delete_field}, function(response)
                {
			if(response)
			{
				$(current_div).html(response);
				currentDeleteItem.sortFields(app_id,comp_id,type);
			}

		});
	});	

	$(document).on('click','p#add-option a, p#add-relationship a, p#add-taxonomy a, p#add-help a,p#add-entity a,p#add-shortcode a,p#add-notify a,p#add-connection a,p#add-widget a,p#add-form a,#add-new.entity a,#add-new.relationship a,#add-new.taxonomy a,#add-new.help a,#add-new.shortcode a,#add-new.notify a,#add-new.connection a,#add-new.widg a,#add-new.form a,#add_field.entity a,#add_field.relationship a,#add_field.help a, #add_field_entity a,#add_field_rel a,#add_field_help a,p#add-glob a,#add-new.glob a',function(){
		var div_id = $(this).parent().attr('id');
		var class_id = $(this).parent().attr('class');
		if(class_id != undefined)
		{
			class_id = class_id.replace('span9 ','');
		}
		if(class_id == 'widg')
		{
			class_id = 'widget';
		}

		if(class_id != 'app')
		{	
			$('.group1').hide();
			var show_id = '#' + div_id;
			if(div_id == 'add-new')
			{
				div_id = 'add-' + class_id;
				show_id = '#add-' + class_id;
			}
			
			app_id = $('input#app').val();
			app = $('input#app_title').val();
			
			if($(this).attr('href').match(/#ent/g)) 
			{
				button = "entity";
				if(div_id == 'add_field')
				{
					ent = $(this).parent().parent().parent().find('#ent-name').html();
					ent_id = $(this).attr('href').replace('#ent','');
				}
				else
				{
					ent_id = $('#list-ent-fields button#edit-entity').attr('href').replace('#','');
					ent = $(this).attr('href').replace('#ent','');
				}
				var show_id = '#add-ent-field';
				$(this).getBreadcrumb('add-entity-field');
				$('#add-ent-field-div input#ent').val(ent_id);
				form_name = "#ent-field-form";
				//should be empty always since its an add form
				field_id = "";
				field = "";
				$('#fld_name').attr('readonly',false);
				$('#fld_type').attr('disabled',false);
				$('#fld_required').attr('disabled',false);
				$('#fld_builtin').val(0);
				$(this).initAttr();
				$('#conditional-options').hide();
				hiddenvar = 'ent_field';	
			} 
			else if($(this).attr('href').match(/#rel/g)) 
			{
				button = "relationship";
				if(div_id == 'add_field')
				{
					rel = $(this).parent().parent().parent().find('#rel-name').html();
					rel_id = $(this).attr('href').replace('#rel','');
				}
				else
				{
					rel_id = $('#list-rel-fields button#edit-relationship').attr('href').replace('#','');
					rel = $(this).attr('href').replace('#rel','');
				}
				var show_id = '#add-rel-field';
				$(this).getBreadcrumb('add-relationship-field');
				$('#add-rel-field-div input#rel').val(rel_id);
				form_name = "#rel-field-form";
				//should be empty always since its an add form
				field_id = "";
				field = "";
				hiddenvar = 'rel_field';	
				$('#rel_fld_values_div').hide();
			} 
			else if($(this).attr('href').match(/#help/g)) 
			{
				button = "help";
				if(div_id == 'add_field')
				{
					help = $(this).parent().parent().parent().find('#help-entity').html();
					help_id = $(this).attr('href').replace('#help','');
				}
				else
				{
					help_id = $('#list-help-fields button#edit-help').attr('href').replace('#','');
					help = $(this).attr('href').replace('#help','');
				}
				var show_id = '#add-help-field';
				$(this).getBreadcrumb('add-help-field');
				$('#add-help-field-div input#help').val(help_id);
				form_name = "#help-field-form";
				//should be empty always since its an add form
				field_id = "";
				field = "";
				hiddenvar = 'help_field';	
			} 
			else 
			{
				if(div_id == 'add-taxonomy')
				{
					button = "taxonomy";
					hiddenvar = 'txn';	
				}
				else if(div_id == 'add-entity')
				{
					button = "entity";
					hiddenvar = 'ent';	
				}
				else if(div_id == 'add-relationship')
				{
					button = "relationship";
					hiddenvar = 'rel';	
				}
				else if(div_id == 'add-option')
				{
					button = "option";
					hiddenvar = '';	
				}
				else if(div_id == 'add-help')
				{
					button = "help";
					hiddenvar = 'help';	
				}
				else if(div_id == 'add-shortcode')
				{
					button = "shortcode";
					hiddenvar = 'shc';	
				}
				else if(div_id == 'add-notify')
				{
					button = "notify";
					hiddenvar = 'notify';	
				}
				else if(div_id == 'add-connection')
				{
					button = "connection";
					hiddenvar = 'connection';	
				}
				else if(div_id == 'add-widget')
				{
					button = "widget";
					hiddenvar = 'widget';	
				}
				else if(div_id == 'add-form')
				{
					button = "form";
					hiddenvar = 'form';	
				}
				else if(div_id == 'add-glob')
				{
					button = "glob";
					hiddenvar = 'glob';	
				}
				app = $(this).attr('href').replace('#','');
				form_name = "#"+button+"-form";
				$(this).getBreadcrumb(div_id);
			}
			$('#app-save').hide();
			$(show_id + '-div').show();
			
			$(form_name)[0].reset();
			
			$('#'+hiddenvar).val('');
			$(show_id + '-div :input[type=checkbox]').attr('checked',false);
			$(show_id + '-div :input[type=radio]').attr('checked',true);
			if(button == 'entity')
			{
				$('#add-ent-field-div input#ent').val(ent_id);
				$('#add-ent-field-div input#app').val(app_id);
				$('#tabs').hide();
				$('#ent-inline-ent_div').hide();
				$('#ent-hierarchical-div').hide();
				$('#ent-com_type_div').hide();
				$('#ent-com_detail_div').hide();
				$('.cond-div').each(function() {
					$(this).remove();
				});
			}
			else if(button == 'taxonomy')
			{
				$('#txntabs').hide();
                		$.get(ajaxurl,{action:'wpas_get_entities',type:button,app_id:app_id}, function(response)
				{
					$('#add-taxonomy-div #txn-attach').html(response);
				});
				$('#txn-conditional-options').hide();
				$('.cond-div').each(function() {
					$(this).remove();
				});
			}
			else if(button == 'shortcode')
			{
				$(this).showShcTabs('',app_id,0);
			}
			else if(button == 'notify')
			{
				$('#add-notify-div input#app').val(app_id);
				$(this).setLevel('entity');
				$('#notify-email_user_div').hide();
				$('#notify-email_admin_div').hide();
				$('#notify-level').val('entity');
			}
			else if(button == 'connection')
			{
				$(this).initConnection(app_id);
			}
			else if(button == 'widget')
			{
				$('#add-widget-div input#app').val(app_id);
				$(this).showWidgByType('');
			}
			else if(button == 'relationship')
                        {
				$('#add-rel-field-div input#app').val(app_id);
				$('#add-rel-field-div input#rel').val(rel_id);
				$('#relTab a:first').tab('show');
				$("#reltabs-1").removeClass('fade');
				$('#reltabs-3-li').hide();
				$('#reltabs-2-li').hide();
				$('#rel-related-display-div').hide();
				$('#add-relationship-div input#app').val(app_id);
                                $.get(ajaxurl,{action:'wpas_get_entities',type:button+'_from',app_id:app_id}, function(response)
                                {
                                        $('#add-relationship-div #rel-from-name').html(response);
                                });
                                $.get(ajaxurl,{action:'wpas_get_entities',type:button+'_to',app_id:app_id}, function(response)
                                {
                                        $('#add-relationship-div #rel-to-name').html(response);
                                });
				$('#rel-limit_user_relationship_role_div').hide();
                		$.get(ajaxurl,{action:'wpas_get_roles',type:'entity',app_id:app_id}, function(response)
				{
					$('#rel-limit_user_relationship_role').html(response);
				}); 
				$('#rel-conditional-options').hide();
				$('.cond-div').each(function() {
					$(this).remove();
				});
                        }
			else if(button == 'help')
			{
				$('#add-help-field-div input#app').val(app_id);
				$('#add-help-field-div input#help').val(help_id);
				$('#help-others').hide();
			}
			else if(button == 'option')
			{
			       $('#ao_adm_notice1').attr('checked', false);
			       $('#ao_adm_notice2').attr('checked', false);
			       $('#ao_remove_colfilter').attr('checked', true);
			       $('#ao_remove_operations').attr('checked', true);
			       $('#ao_remove_analytics').attr('checked', true);
                               $('#support-cust-nav-div').hide();
                               $('#ao_force_col_div').hide();
			       $('#ao_theme_type_div').hide();
			}
			else if(button == 'form')
			{
				$('#add-form-div input#app').val(app_id);
				$('#add-form-div input#form').val(form_id);
				$(this).initForm(app_id);
			}
			$('label.error').each(function() {
                                $(this).remove();
                        });
                	$('label.valid').each(function() {
                                $(this).remove();
                        });
			$('button#save-'+button+'-field').html('<i class="icon-save"></i>' + wpas_vars.save);
			$('button#save-'+button+'-field').val('Save');

			$('button#update-'+button).html('<i class="icon-save"></i>' + wpas_vars.save);
			$('button#update-'+button).val('Save');
			$('button#update-'+button).attr('id','save-'+button);
				
		}
	});
	$(document).on('click','p#entity a, p#taxonomy a, p#relationship a, p#help a,p#shortcode a,p#notify a,p#connection a,p#widget a,p#role a,p#form a,p#glob a',function(){
                app = $(this).attr('href').replace('#','');
                app_id = $('input#app').val();
                $(this).getBreadcrumb('app');
                $('#app-save').hide();
                $('.group1').hide();
                var list_id = $(this).parent().attr('id');
                $('#list-'+list_id).empty();
		$(this).checkColumn();
                $.get(ajaxurl,{action:'wpas_list_all',type:list_id,app_id:app_id}, function(response)
                {
                        $('#list-'+list_id).html(response);
                });
                $('#list-'+list_id).show();
	});

	$(document).on('click','p#pointer a',function(){
		app = $(this).attr('href').replace('#','');
		app_id = $('input#app').val();
		$(this).getBreadcrumb('app');
		$('#app-save').hide();
		$('.group1').hide();
		var list_id = $(this).parent().attr('id');
		$('#list-'+list_id).show();
	});
	$(document).on('click','p#add-pointer a',function(){
		app = $(this).attr('href').replace('#','');
		app_id = $('input#app').val();
		$(this).getBreadcrumb('app');
		$('#app-save').hide();
		$('.group1').hide();
		var list_id = $(this).parent().attr('id');
		$('#'+list_id+'-div').show();
	});

	$(document).on('click','input#doaction.btn, span#delete a',function(event){
		currentDeleteItem = $(this);
		$('#confirmdeleteModal button#delete-ok').attr('id','delete-ok-other');
		$('#confirmdeleteModal button#delete-ok-field').attr('id','delete-ok-other');
		$('#confirmdeleteModal').modal('show');
                return false;
	});

	$(document).on('click','button#delete-ok-other',function(){
		$('#confirmdeleteModal').modal('hide');
		app_id = $('input#app').val();
		var myclass = currentDeleteItem.parent().attr('class').replace('alignleft actions ','');
		var allVals = [];
		if($('select.'+myclass).val() == 'delete')
		{
			$('tbody#the-list :checked').each(function() {
				allVals.push($(this).val());
			});
		}
		else
		{
			allVals.push(currentDeleteItem.attr('href').replace('#',''));
		}
		if(myclass == 'widg')
		{
			myclass = 'widget';
		}
		
		var show_div = '#list-' + myclass;
		if(app_id == undefined)
		{
			myclass = 'app';
			app_id = "";
			show_div = '#list_apps';
		}
		currentDeleteItem.checkColumn();
		$.post(ajaxurl,{action:'wpas_delete',type:myclass,app_id:app_id,fields:allVals,nonce:wpas_vars.nonce_delete}, function(response)
		{
				$(show_div).html(response);
		});
	});

	$(document).on('click','#save-relationship.btn, #save-taxonomy.btn, #save-entity.btn, #save-help.btn, #save-shortcode.btn, #save-notify.btn, #save-widget.btn,#save-role.btn, #save-form.btn, #save-connection.btn, #save-glob.btn',function(event){
                event.preventDefault();
		app_id = $('input#app').val();
		var btn_id = $(this).attr('id');
                app = document.getElementById('app-name').value;
		type = btn_id.replace("save-","");
		type = type.replace("update-","");
		$('#'+type+'-form').validate();
		
		var valid = $('#'+type+'-form').valid();
		if(!valid){
			return false;
		}
		if(type == 'shortcode' && $('#shc-view_type').val() == 'datagrid')
		{
			$(this).saveGridCols();
		}
		
		form_data = $("#"+type+"-form :input").serialize();

		var checked_count = 0;
		$('#'+type+'-form :input[type=checkbox]').each(function() {     
			if(this.name != 'notify-events[]')
			{
				if (this.checked) {
					checked_count++;
					if(form_data.search(this.name+'=1') == -1)
					{
						form_data += '&'+this.name+'=1';
					}
				}
				else
				{
					if(type != 'role')
					{		
						form_data += '&'+this.name+'=0';
					}
				}
			}
    		});

		if(type == 'role' && checked_count == 0)
		{
			$('#errorRoleModal').modal('show');
			return false;
		}

		$(this).checkColumn();

                $.post(ajaxurl,{action:'wpas_save_form',form: form_data, type: type, app_id:app_id,nonce:wpas_vars.nonce_save_form}, function(response){
				if(response)
				{
					$(this).getBreadcrumb('app');
					$('.group1').hide();
					$('#list-'+type).html(response);
					$('#list-'+type).show();
				}
		});
	});


	$(document).on('click','button#error-close,button#error-ok',function(){
                $('#errorRoleModal').modal('hide');
        });


	$(document).on('click','#save-option.btn, #update-option.btn',function(event){
                event.preventDefault();
                app_id = $('input#app').val();
		var btn_id = $(this).attr('id');
		type = btn_id.replace("save-","");
		type = type.replace("update-","");
		var valid = $('#'+type+'-form').valid();
		if(!valid)
		{
			return false;
		}
		form_data = $("#"+type+"-form :input").serialize();
		$('#'+type+'-form :input[type=checkbox]').each(function() {     
    			if (this.checked) {
				form_data += '&'+this.name+'=1';
			}
    		});
		$.post(ajaxurl,{action:'wpas_save_option_form',form: form_data, app_id:app_id,nonce:wpas_vars.nonce_save_option_form}, function(response){
			$('#add-option-div').fadeTo('fast',0.8,function(){
			});
			$('#add-option-div input,#add-option-div textarea').prop('disabled',true);
			$('#add-option-div button#cancel,button#update-option,button#save-option').prop('disabled',true);
			$('#add-option a').html('<i class="icon-picture"></i>' + wpas_vars.update_settings);
			$('#add-option a').parent().attr('id','update-option');
			$('#edit-btn-div').show();
			$.each(response,function (key,value) {
                                if(value == 1)
                                {
                                      $('#add-option-div #'+key).attr('checked', true);
                                }
                                else{
                                      $('#add-option-div #'+key).val(value);
                                }
                       });
		}, 'json');
	});
	$(document).on('click','#update-taxonomy.btn, #update-entity.btn, #update-relationship.btn, #update-help.btn, #update-shortcode.btn, #update-notify.btn, #update-connection.btn, #update-widget.btn,#update-role.btn, #update-form.btn, #update-glob.btn',function(event){
                event.preventDefault();
		var btn_id = $(this).attr('id');
		
		if(btn_id == 'update-entity')
		{
		var comp_id = $('input#ent').val();
		var form_name = 'entity-form';
		var type = 'entity';
		var show_id = '#list-entity';
		}
		else if(btn_id == 'update-taxonomy')
		{
		var comp_id = $('input#txn').val();
		var form_name = 'taxonomy-form';
		var type = 'taxonomy';
		var show_id = '#list-taxonomy';
		}
		else if(btn_id == 'update-relationship')
		{
		var comp_id = $('input#rel').val();
		var form_name = 'relationship-form';
		var type = 'relationship';
		var show_id = '#list-relationship';
		}
		else if(btn_id == 'update-help')
		{
		var comp_id = $('input#help').val();
		var form_name = 'help-form';
		var type = 'help';
		var show_id = '#list-help';
		}
		else if(btn_id == 'update-shortcode')
		{
		$('#add-shortcode-div input#app').val(app_id);
		var comp_id = $('input#shc').val();
		var form_name = 'shortcode-form';
		var type = 'shortcode';
		var show_id = '#list-shortcode';
		if(type == 'shortcode' && $('#shc-view_type').val() == 'datagrid')
		{
			$(this).saveGridCols();
		}
		}
		else if(btn_id == 'update-notify')
		{
			$('#add-notify-div input#app').val(app_id);
			var comp_id = $('input#notify').val();
			var form_name = 'notify-form';
			var type = 'notify';
			var show_id = '#list-notify';
		}
		else if(btn_id == 'update-connection')
		{
			$('#add-connection-div input#app').val(app_id);
			var comp_id = $('input#connection').val();
			var form_name = 'connection-form';
			var type = 'connection';
			var show_id = '#list-connection';
		}
		else if(btn_id == 'update-glob')
		{
			$('#add-glob-div input#app').val(app_id);
			var comp_id = $('input#glob').val();
			var form_name = 'glob-form';
			var type = 'glob';
			var show_id = '#list-glob';
		}
		else if(btn_id == 'update-widget')
		{
		$('#add-widget-div input#app').val(app_id);
		var comp_id = $('input#widget').val();
		var form_name = 'widget-form';
		var type = 'widget';
		var show_id = '#list-widget';
		}
		else if(btn_id == 'update-role')
		{
		var comp_id = $('input#role').val();
		var form_name = 'role-form';
		var type = 'role';
		var show_id = '#list-role';
		}
		else if(btn_id == 'update-form')
		{
		var comp_id = $('input#form').val();
		var form_name = 'form-form';
		var type = 'form';
		var show_id = '#list-form';
		}
	
		if($("#"+form_name).validate().form() == true)
		{
			if(type == 'widget' && $('#widg-dash_subtype').val() == 'entity' && !$('#widg-layout').val())
			{
				$('label.error').each(function() {
						$(this).remove();
				});
				$('#widg-layout').after('<label class="error">this field is required.</label');
				return false;
			}
				
			var form_data = $('#'+type+'-form :input').serialize();
			$('#'+ form_name + ' :input[type=checkbox]').each(function() {     
				if(this.name != 'notify-events[]')
				{
					if (this.checked) {
						form_data += '&'+this.name+'=1';
					}
					else
					{
						form_data += '&'+this.name+'=0';
					}
				}
			});

			$.post(ajaxurl,{action:'wpas_update_form',form: form_data, type: type, app_id:app_id,nonce:wpas_vars.nonce_update_form}, function(response){
					$(this).getBreadcrumb('app');
					$('.group1').hide();
					$(show_id).html(response);
					$(show_id).show();
			});
		}
	});
	$(document).on('click','#save-entity-field.btn, #save-relationship-field.btn, #save-help-field.btn',function(event){
                event.preventDefault();
		var check_content = 1;

		if($(this).attr('id') == 'save-entity-field')
		{
			var form_name = 'ent-field-form';
                	var type = 'entity';
			var show_id = '#list-ent-fields';
			var input_name = 'ent';
		}
		else if($(this).attr('id') == 'save-relationship-field')
		{
                	var type = 'relationship';
			var form_name = 'rel-field-form';
			var show_id = '#list-rel-fields';
			var input_name = 'rel';
		}
		else
		{
                	var type = 'help';
			var form_name = 'help-field-form';
			var show_id = '#list-help-fields';
			var input_name = 'help';
		}
		get_id = '#' + form_name;
		app_id = $(get_id+' input#app').val();
		ent_id = $(get_id+' input#ent').val();
		rel_id = $(get_id+' input#rel').val();
		help_id = $(get_id+' input#help').val();
		var breadcrumb = type;
		
		if($(this).attr('id') == 'save-entity-field')
		{
			var sort_id = ent_id;
			$.validator.addClassRules({
				'cond-attr':{
					required: true
				},
				'cond-value':{
					required: true
				}
			});
		}
		else if($(this).attr('id') == 'save-relationship-field')
		{
			var sort_id = rel_id;
		}
		else
		{
			var sort_id = help_id;
		}

		if($(this).attr('id') == 'save-help-field')
		{
			var check_content = $(get_id+' #help_fld_content').valid(); 
		}
		if($("#"+form_name).validate().form() == true && check_content == 1)
		{
                var form_data = $('#'+form_name+' :input').serialize();
		$('#'+ form_name + ' :input[type=checkbox]').each(function() {     
    			if (this.checked) {
				form_data += '&'+this.name+'=1';
			}
    		});
		if($('input#'+ input_name + '_field').val() != '')
		{
			field_id = $('input#'+ input_name + '_field').val();
		}

                $.post(ajaxurl,{action:'wpas_save_field',form: form_data, type: type, field_id: field_id,nonce:wpas_vars.nonce_save_field}, function(response){
				if(response)
				{
					$('.group1').hide();
					$(show_id).html(response);
					$(this).getBreadcrumb(type);
					$(show_id).show();
					$(this).sortFields(app_id,sort_id,type);
				}
		});
		}
	});
	$(document).on('click','#view.entity a, #view.relationship a,#view.help a, td#edit_td a#ent-name, td#edit_td a#rel-name, td#edit_td a#help-entity, tbody#the-list a#ent-name,#the-list a#help-entity,#the-list a#rel-name',function(){
                app_id = $('input#app').val();
		comp_id = $(this).attr('href').replace('#','');
		if($(this).attr('id') == 'rel-name' || $(this).attr('class') == 'relationship' || $(this).parent().attr('class') == 'relationship')
		{
		 	type = 'relationship';
			rel_id = comp_id;
			rel = $(this).parent().parent().parent().find('#rel-name').html();
			show_id = 'list-rel-fields';
			$('input#rel').val(rel_id);
		}
		else if($(this).attr('id') == 'help-entity' || $(this).attr('class') == 'help' || $(this).parent().attr('class') == 'help')
		{
			type = 'help';
			help_id = comp_id;
			help = $(this).parent().parent().parent().find('#help-entity').html();
			show_id = 'list-help-fields';
			$('input#help').val(help_id);
		}
		else
		{
			type = 'entity';
			ent_id = comp_id;
                	ent = $(this).parent().parent().parent().find('#ent-name').html();
			show_id = 'list-ent-fields';
			$('input#ent').val(ent_id);
		}
		if(show_id != '')
		{
                $('#'+show_id).empty();
		}
                app = $('input#app-name').val();
                $('#app-save').hide();
                $('.group1').hide();
		
                $(this).getBreadcrumb(type);
	
		if(show_id != '')
		{	
                $.get(ajaxurl,{action:'wpas_list_fields',type:type,app_id:app_id,comp_id:comp_id}, function(response)
                {
                        $('#'+show_id).html(response);
                        $('#'+show_id).css('display', 'block');
			$(this).sortFields(app_id,comp_id,type);
                });
		}

       });



	$(document).on('click','span#edit.entity a,span#edit.relationship a,span#edit.taxonomy a,span#edit.shortcode a,span#edit.notify a,span#edit.connection a,span#edit.widget a,span#edit.help a,span#edit.form a,#edit-entity.btn,#edit-relationship.btn,#edit-help.btn,td#edit_td a#txn-name,td#edit_td a#shc-label,td#edit_td a#widg-name,td#edit_td a#form-name,td#edit_td a#notify-name,td#edit_td a#connection-name,span#edit.glob a',function(event){
		var widg_type = "";
                app_id = $('#app_form input#app').val();
		if($(this).parent().attr('class') == 'app')
		{
			return;
		}
                event.preventDefault();
		if($(this).attr('id') == 'txn-name')
		{
			var myclass = 'taxonomy';
                        txn = $(this).html();
		}
		else if($(this).attr('id') == 'shc-label')
		{
			var myclass = 'shortcode';
                        shc = $(this).html();
		}
		else if($(this).attr('id') == 'widg-name')
		{
			var myclass = 'widget';
                        widget = $(this).html();
		}
		else if($(this).attr('id') == 'form-name')
		{
			var myclass = 'form';
                        form = $(this).html();
		}
		else if($(this).attr('id') == 'notify-name')
		{
			var myclass = 'notify';
                        notify = $(this).html();
		}
		else if($(this).attr('id') == 'connection-name')
		{
			var myclass = 'connection';
                        connection = $(this).html();
		}
		else if($(this).attr('id') == 'glob-name')
		{
			var myclass = 'glob';
                        glob = $(this).html();
		}
		else
		{
			var myclass = $(this).parent().attr('class');
                        txn = $(this).closest('#edit_td').find('#txn-name').html();
			shc = $(this).closest('#edit_td').find('a#shc-label').html();
			widget = $(this).closest('#edit_td').find('a#widg-name').html();
			form = $(this).closest('#edit_td').find('a#form-name').html();
			notify = $(this).closest('#edit_td').find('a#notify-name').html();
			connection = $(this).closest('#edit_td').find('a#connection-name').html();
			glob = $(this).closest('#edit_td').find('a#glob-name').html();
		}

                var comp_id = $(this).attr('href').replace('#','');
		if(myclass == 'entity')
		{
			ent_id = comp_id;
                        ent = $(this).closest('#edit_td').find('#ent-name').html();
			$(this).showEntIcons('');
			$('#ent-com_type_div').hide();
			$('#ent-com_detail_div').hide();
		}
		else if(myclass == 'taxonomy')
		{
			txn_id = comp_id;
			$('.cond-div').each(function() {
				$(this).remove();
			});
		}
		else if(myclass == 'relationship')
		{
			rel_id = comp_id;
			rel = $('a#rel-name').html();
			$('#relTab a:first').tab('show');
			$("#reltabs-1").removeClass('fade');
			$('#reltabs-3-li').hide();
			$('#reltabs-2-li').hide();
			$('#rel-limit_user_relationship_role_div').hide();
			$('.cond-div').each(function() {
				$(this).remove();
			});
		}
		else if(myclass == 'help')
		{
			help_id = comp_id;
			help = $('a#help-entity').html();
		}
		else if(myclass == 'shortcode')
		{
			shc_id = comp_id;
		}
		else if(myclass == 'widget')
		{
			widget_id = comp_id;
		}
		else if(myclass == 'form')
		{
			form_id = comp_id;
		}
		else if(myclass == 'notify')
		{
			notify_id = comp_id;
		}
		else if(myclass == 'connection')
		{
			connection_id = comp_id;
			$('#connection-inc_vis_status_div').hide();
			$('#connection-inc_email_div').hide();
                        $('#connection-inc_name_div').hide();
		}
		else if(myclass == 'glob')
		{
			glob_id = comp_id;
		}
                app = $('input#app-name').val();
                $('#app-save').hide();
                $('.group1').hide();
		$('#add-'+myclass+'-div :input').val('');
                $('#add-'+myclass+'-div :input[type=checkbox]').attr('checked',false);
                $('#add-'+myclass+'-div :input[type=radio]').attr('checked',true);
		
                $.get(ajaxurl,{action:'wpas_edit',type:myclass,app_id:app_id,comp_id:comp_id}, function(response)
                        {
			if(response) {
				layout_id = "";
				if(myclass == 'entity')
				{
					$('input#ent').val(response[1]);
                        		$(this).setInlineTabs('notinline');
				}
				else if(myclass == 'taxonomy')
				{
					$('input#txn').val(response[1]);
				}
				else if(myclass == 'relationship')
				{
					$('input#rel').val(response[1]);
				}
				else if(myclass == 'help')
				{
					$('input#help').val(response[1]);
				}
				else if(myclass == 'shortcode')
				{
					$('input#shc').val(response[1]);
					layout_id= 'shc-sc_layout';
					$('#shc-sc_pagenav_div').hide();
					$('#shc-theme_type_div').hide();
				}
				else if(myclass == 'widget')
				{
					$('input#widget').val(response[1]);
					layout_id= 'widg-layout';
				}
				else if(myclass == 'form')
				{
					$('input#form').val(response[1]);
				}
				else if(myclass == 'notify')
				{
					$('input#notify').val(response[1]);
				}
				else if(myclass == 'connection')
				{
					$('input#connection').val(response[1]);
				}
				else if(myclass == 'glob')
				{
					$('input#glob').val(response[1]);
				}
				menu_selected = "";
				show_ui = "";
				rel_type = "";
				primary_entity = "";
				dependents = "";
				view_subtype = "";
				comp_id_from = "";
				comp_id_to = "";
				chart_func = "";
				chart_ent = "";
				shc_form_id = "";
				chart_type = "";
				haxis_type = "";
				vaxis_type = "";
				vaxis_date_type = "";
				haxis_date_type = "";
				notify_level = "";
				attach_to = "";
				attach_tax = "";
				widg_subtype = "";
				txn_inline =0;
				inl_entity = "";
				ent_advanced = 0;
				shc_label = "";
				txn_att_id = [];
				rel_att_id = [];
				$.each(response[0],function (key,value) {
					console.log('key'+key+'val:'+value);
					if(value != undefined)
					{
						if(key == 'notify-events')
						{
							$.each(value,function(k,v){
								$('#notify-'+v).attr('checked',true);
							});	
							$("input[name='notify-events[]']").each(function (){
								new_val = $(this).attr('id').replace('notify-','');
								$('#'+$(this).attr('id')).val(new_val);
							});
						}
						else if(key == 'notify-level')
						{
							notify_level = value;
							$('#'+key).val(value);
						}
						else if(key == 'notify-attached_to')
						{
							change_sel = 0;
							events = response[0]['notify-events'];
							if(events.indexOf('change') > -1)
							{
								change_sel = 1;
							}
							$(this).setLevel(notify_level, change_sel, value);
							if(notify_level == 'tax')
							{
								attach_tax = value;
							}
							else
							{
								attach_to = value;
								attach_tax = '';
							}
						}
						else if(key =='glob-type')
						{
							$(this).showDef(value);	
							$('#'+key).val(value);
						}
						else if(key == 'connection-type')
						{
							$(this).showCon(value);
							$('#'+key).val(value);
						}
						else if(key == 'connection-inl_entity')
						{
							inl_entity = value;
						}
						else if(key == 'connection-inl_loc')
						{
							$(this).inlineEnt(app_id,ent_id,inl_entity,value);
						}
						else if(key == 'connection-entity')
						{
							ent_id = value;
							$.get(ajaxurl,{action:'wpas_get_entities',type:'form',app_id:app_id,values:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
						}
						else if(key == 'connection-inc_email')
						{
							$(this).incEmail(app_id,ent_id,'from',value);
						}
						else if(key == 'connection-inc_name')
						{
							$(this).incEmail(app_id,ent_id,'from_name',value);
						}
						else if(key == 'connection-inc_tax')
						{
							$(this).incEmail(app_id,ent_id,'tax',value);
						}
						else if(key == 'connection-inc_subject')
						{
							$(this).incEmail(app_id,ent_id,'subject',value);
						}
						else if(key == 'connection-inc_date')
						{
							$(this).incEmail(app_id,ent_id,'date',value);
						}
						else if(key == 'connection-inc_body')
						{
							$(this).incEmail(app_id,ent_id,'body',value);
						}
						else if(key == 'connection-inc_att')
						{
							$(this).incEmail(app_id,ent_id,'att',value);
						}
						else if(key == 'notify-attach_ent')
						{
							if(notify_level == 'tax')
							{
								attach_to = value;
								$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,tax_id:attach_tax,values:value}, function(response) {
                                        				$('#notify-attach_ent').html(response);
									$('#notify-attach_ent_div').show();
								});
							}
						}
						else if(key == 'notify-confirm_sendto')
						{
							$(this).getEmails(app_id,notify_level,attach_to,attach_tax,value);
						}
						else if(key == 'help-entity')
						{
							$('#help-screen_type-div').show();
							$('#help-others').show();
							$('#help-tax-div').hide();
							$('#help-entity-div').show();
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass,values:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
						}
						else if(key == 'help-tax')
						{
							$('#help-screen_type-div').hide();
							$('#help-others').show();
							$('#help-tax-div').show();
							$('#help-entity-div').hide();
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:'tax',values:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
						}
						else if(key == 'txn-attach' || key == 'widg-attach')
						{
							divid = myclass;
							ent_id = value;
							if(txn_inline == 1){
								myclass = 'inline_tax';
							}
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass,values:value}, function(response)
                                			{
                                        			$('#add-'+divid+'-div #'+ key).html(response);
                                			});
							if(key == 'widg-attach')
							{
								chart_ent = value;
                                                                $('#add-'+myclass+'-div #'+ key + '_div').show();
                                        			$(this).showWidgTags(app_id,value,widg_subtype);

							}
						}
						else if(key == 'shc-attach' && view_subtype != 'search')
						{
							chart_ent = value;
							if(view_subtype == 'std')
							{
								view_subtype = '';
							}
							ent_tax_id = value;
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass,values:value,subtype:view_subtype}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
								app_id = $('input#app').val();
								if($.inArray(view_subtype,['single','archive','tax']) != -1){
									$(this).showShcTags(app_id,value,'tag-nocount');
								}
								else {
									$(this).showShcTags(app_id,value,'tag-rel');
								}
								$('#add-'+myclass+'-div #'+ key + '_div').show();
								$('#shc-attach_form_div').hide();
								if(view_subtype != 'tax')
								{
									$('#shc-attach_tax_div').hide();
								}
							});
							if(view_subtype != 'single' && view_subtype != 'archive'){
								$('#shc-setup_page_div').show();
								$('#shc-rmv_wpasbtn_div').show();
							}
							else {
								$('#shc-setup_page_div').hide();
								$('#shc-rmv_wpasbtn_div').hide();
							}
						}	
						else if(key == 'shc-attach_tax' && view_subtype == 'tax')
						{
							tax_id = value;
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:'tax',values:value,subtype:view_subtype}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
								$('#add-'+myclass+'-div #'+ key + '_div').show();
								$('#shc-attach_form_div').hide();
								$('#shc-setup_page_div').hide();
								$('#shc-rmv_wpasbtn_div').hide();
							});
						}
						else if(key == 'shc-attach_taxterm')
						{
							app_id = $('input#app').val();
							$.get(ajaxurl,{action:'wpas_get_tax_values',app_id:app_id,tax_id:tax_id,value:value}, function(response)
							{
								$('#add-shortcode-div #shc-attach_taxterm').html(response);
							});
						}
						else if(key == 'shc-attach_form' && view_subtype == 'search')
						{
							shc_form_id = value;
							$.get(ajaxurl,{action:'wpas_get_search_forms',app_id:app_id,val:value}, function(response)
							{
								$('#shc-attach_form').html(response);
								$('#shc-attach_form_div').show();
								$('#shc-attach_div').hide();
								$('#shc-attach_tax_div').hide();
								$(this).showShcTags(app_id,value,'tag-form');
							});
							$('#shc-setup_page_div').hide();
							$('#shc-rmv_wpasbtn_div').hide();
							$('#shc-app_dash_div').hide();
						}
						else if(key == 'shc-sc_orderby')
						{
							app_id = $('input#app').val();
							$.get(ajaxurl,{action:'wpas_get_orderby_fields',app_id:app_id,ent_id:chart_ent,form_id:shc_form_id,value:value}, function(response)
							{
								$('#shc-sc_orderby').html(response);
							});

						}
						else if(key == 'widg-orderby')
						{
							app_id = $('input#app').val();
							$.get(ajaxurl,{action:'wpas_get_orderby_fields',app_id:app_id,ent_id:chart_ent,value:value}, function(response)
							{
								$('#widg-orderby').html(response);
							});

						}
						else if(key.match(/txn-cond_attr/))
						{
							txn_div_id = key.replace('txn-cond_attr_','');
							txn_att_id[txn_div_id] = value;
						}
						else if(key.match(/txn-cond_sel_value/))
						{
							div_id = key.replace('txn-cond_sel_value_','');
							$.get(ajaxurl,{action:'wpas_get_cond_div',app_id:app_id,ent_id:ent_id,field_id:txn_id,div_id:div_id,attr_val:txn_att_id[div_id],val:value,val_type:'select',from:'txn'}).done(function (response){
								$('#txn-cond-list').append(response);
							});
						}
						else if(key.match(/txn-cond_value/))
						{
							div_id = key.replace('txn-cond_value_','');
							$.get(ajaxurl,{action:'wpas_get_cond_div',app_id:app_id,ent_id:ent_id,field_id:txn_id,div_id:div_id,attr_val:txn_att_id[div_id],val:value,val_type:'text',from:'txn'}).done(function (response){
								$('#txn-cond-list').append(response);
							});
						}
						else if(key.match(/rel-cond_attr/))
						{
							rel_div_id = key.replace('rel-cond_attr_','');
							rel_att_id[rel_div_id] = value;
						}
						else if(key.match(/rel-cond_sel_value/))
						{
							div_id = key.replace('rel-cond_sel_value_','');
							$.get(ajaxurl,{action:'wpas_get_cond_div',app_id:app_id,ent_id:ent_id,div_id:div_id,attr_val:rel_att_id[div_id],val:value,val_type:'select',from:'rel'}).done(function (response){
								$('#rel-cond-list').append(response);
							});
						}
						else if(key.match(/rel-cond_value/))
						{
							div_id = key.replace('rel-cond_value_','');
							$.get(ajaxurl,{action:'wpas_get_cond_div',app_id:app_id,ent_id:ent_id,div_id:div_id,attr_val:rel_att_id[div_id],val:value,val_type:'text',from:'rel'}).done(function (response){
								$('#rel-cond-list').append(response);
							});
						}
						else if(key == 'rel-limit_user_relationship_role')
						{
							$.get(ajaxurl,{action:'wpas_get_roles',type:'entity',app_id:app_id,value:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
						}
						else if(key == 'rel-from-name')
						{
							comp_id_from = value;
							if(value != 'user'){
								ent_id = value;
							}
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass+'_from',values:value}, function(response)
                                                        {
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
							if(value == 'user')
							{
								$('#rel-limit_user_relationship_role_div').show();
							}
						}	
						else if(key == 'rel-to-name')
						{
							comp_id_to = value;
							if(value != 'user'){
								ent_id = value;
							}
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass+'_to',values:value}, function(response)
                                                        {
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
							if(value == 'user')
							{
								$('#rel-limit_user_relationship_role_div').show();
							}
						}
						else if(key == 'form-attached_entity')
						{
							$.get(ajaxurl,{action:'wpas_get_entities',type:'form',app_id:app_id,values:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
							primary_entity = value;
						}
						else if(key == 'form-dependents')
						{
							$.get(ajaxurl,{action:'wpas_get_entities',type:'form_dependents',app_id:app_id,primary_entity:primary_entity,values:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
							dependents = value;
							
						}
						else if(key == 'form-form_type')
						{
							if(value == 'search')
							{
								$('#formtabs-3-li').hide();
								$('#form-submit_status_div').hide();
								$('#form-visitor_submit_status_div').hide();
								$('#form-noresult_msg_div').show();
								$('#form-ajax_search_div').show();
								$('#form-enable_operators_div').show();
								$('#form-setup_page_title_div').hide();
							}
							else
							{
								$('#formtabs-3-li').show();
								$('#form-submit_status_div').show();
								$('#form-visitor_submit_status_div').show();
								$('#form-noresult_msg_div').hide();
								$('#form-ajax_search_div').hide();
								$('#form-enable_operators_div').hide();
								$('#form-setup_page_title_div').hide();
							}
							$('#add-'+myclass+'-div #'+key).val(value);
						}	
						else if(key == 'ent-com_type')
                				{
							if(value == 'custom')
							{
                        					$('#ent-com_detail_div').show();
							}
							else
							{
                        					$('#ent-com_detail_div').hide();
							}
							$('#add-'+myclass+'-div #'+key).val(value);
                				}
						else if(key == 'shc-label')
						{
							shc_label = value;
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-view_type')
						{
							$(this).showShcTabs(value,app_id,0);
							view_subtype = value;
							$('#add-'+myclass+'-div #'+key).val(value);
							if(value == 'integration'){
								$(this).showShcTags(app_id,0,'integration',shc_label);
							}
						}
						else if(key == 'shc-chart_type')
						{
							$(this).chartType(value);
							$(this).chartFunc(value);
							chart_type = value;
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-chart_func')
						{
							chart_func = value;
							$(this).vhAxisType(chart_type,value,'#shc-vaxis_type');
							$(this).vhAxisType(chart_type,value,'#shc-haxis_type');
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-vaxis_type')
						{
							vaxis_type = value;
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-haxis_type')
						{
							haxis_type = value;
							if(value == 'unique')
							{
								$(this).vhAxis(haxis_type,app_id,chart_ent,chart_type,chart_func,'','haxis');
							}
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-vaxis_id')
						{
							app_id = $('input#app').val();
							$(this).vhAxis(vaxis_type,app_id,chart_ent,chart_type,chart_func,value,'vaxis');
						}
						else if(key == 'shc-haxis_id')
						{
							app_id = $('input#app').val();
							$(this).vhAxis(haxis_type,app_id,chart_ent,chart_type,chart_func,value,'haxis');
						}
						else if(key == 'shc-haxis_date_type')
						{
							if(value == 'year')
							{
								$(this).vhDateType('haxis',value);
							}
							haxis_date_type = value;
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-vaxis_date_type')
						{
							if(value == 'year')
							{
								$(this).vhDateType('vaxis',value);
							}
							vaxis_date_type = value;
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-haxis_date_range')
						{
							$(this).vhDateType('haxis',haxis_date_type,value);
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-vaxis_date_type')
						{
							$(this).vhDateType('vaxis',vaxis_date_type,value);
							$('#add-'+myclass+'-div #'+key).val(value);
						}
						else if(key == 'shc-table_col')
						{
							app_id = $('input#app').val();
							$.get(ajaxurl,{action:'wpas_get_table_cols',app_id:app_id,chart_ent:chart_ent,table_cols:value}, function(response){
                                				$('#shc-table_ids').html(response[0]);
                                				$('#shc-table_col').html(response[1]);
								 $(this).updateGridCols(value);
                        				},'json');
						}
						else if(key == 'ent-menu_icon_type')
						{
							$('#add-'+myclass+'-div #'+key).val(value);
							$(this).showEntIcons(value);
						}
						else if(key == 'txn-display_type')
						{
							$('select>option[value="' + value + '"]').prop('selected', true);
						}			
						else if(value == 1)
						{
							$('#add-'+myclass+'-div #'+key).attr('checked', true);
							if(key == 'txn-is_conditional')
							{
								$('#txn-conditional-options').show();
							}
							if(key == 'rel-is_conditional')
							{
								$('#rel-conditional-options').show();
							}
							if(key == 'ent-inline-ent')
							{
                        					$(this).setInlineTabs('inline');
								if(ent_advanced == 1)
								{
									$('#ent-hierarchical-div').hide();
								}
								else {
									$('#ent-hierarchical-div').show();
								}
                					}
							if(key == 'txn-inline')
							{
								txn_inline =1;
                					}
							if(key == 'ent-advanced-option')
							{
								ent_advanced =1;
								$('#ent-inline-ent_div').show();
								$('#ent-hierarchical-div').show();
								$('#tabs').show();
							}
							else if(key == 'ent-rewrite')
							{
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#ent-rewrite_slug').removeAttr('disabled');
							}
							else if(key == 'ent-show_ui')
							{
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#ent-show_in_menu_div').show();
								$('#ent-menu_icon_type_div').show();
								$('#ent-menu_position_div').show();
								$('#ent-top_level_page_div').show();
								show_ui = 1;
							}
							else if(key == 'ent-show_in_menu')
							{
								menu_selected = 1;
								$('#add-'+myclass+'-div #'+key).val(value);
							}
							else if(key == 'ent-hierarchical')
							{
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#ent-page-attributes-div').show();
								$('#ent-parent_item_colon_div').show();
							}
							else if(key == 'ent-supports_comments')
                					{
                        					$('#ent-com_type_div').show();
                					}
							else if(key == 'txn-advanced-option')
							{
								$('#'+key).attr('checked', true);
								if(txn_inline == 1){
									$(this).setInline('inline','');
								}
								else {
									$(this).setInline('notinline','');
								}
								$('#txntabs').show();
							}
							else if(key == 'txn-hierarchical')
							{
								$('select>option[value="' + value + '"]').prop('selected', true);
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#txn-parent_item_div').show();
								$('#txn-parent_item_colon_div').show();
								$('#txn-separate_items_with_comas_div').hide();
								$('#txn-add_or_remove_items_div').hide();
								$('#txn-choose_from_most_used_div').hide();
							}
							else if(key == 'txn-show_ui')
							{
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#txn-show-in-nav-menus-div').show();
								$('#txn-show-in-menu-div').show();
							}
							else if(key == 'txn-rewrite')
							{
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#txn-custom_rewrite_slug').removeAttr('disabled');
							}
							else if(key == 'rel-connected-display')
							{
								$('#reltabs-2-li').show();
								if(rel_type == 'one-to-many')
								{
									$('#rel-related-display-div').hide();
								}
								else if(rel_type == 'many-to-many')
								{
									$('#rel-related-display-div').show();
								}
								rel_id = $('input#rel').val();
								$(this).showRelTags(app_id,comp_id_to,'rel-con-from-tags',rel_id);
								$(this).showRelTags(app_id,comp_id_from,'rel-con-to-tags',rel_id);
							}
							else if(key == 'rel-related-display')
							{
								$('#reltabs-3-li').show();
								rel_id = $('input#rel').val();
								$(this).showRelTags(app_id,comp_id_from,'rel-rltd-from-tags',rel_id);
								$(this).showRelTags(app_id,comp_id_to,'rel-rltd-to-tags',rel_id);
							}
							else if(key == 'form-advanced-option')
							{
								$('#form-tabs').show();
							}
							else if(key == 'form-enable_form_schedule')
							{
								$('#form-schedule_div').show();
							}
							else if(key == 'form-setup_page')
							{
								$('#form-setup_page_title_div').show();
							}
							else if(key == 'shc-setup_page')
							{
								$('#shc-setup_page_title_div').show();
								$('#add-'+myclass+'-div #'+key).val(value);
							}
							else if(key == 'shc-sc_pagenav')
							{
								$('#shc-pgn_class_div').show();
							}
							else if(key == 'shc-app_dash')
							{
								$('#shc-app_dash_title_div').show();
								$('#shc-app_dash_loc_div').show();
								$('#add-'+myclass+'-div #'+key).val(value);
							}
							else if(key == 'notify-email_user_confirm')
							{
								$('#notify-email_user_div').show();
								$('#'+key).attr('checked',true);
								$(this).showNotifyTags(app_id,notify_level,'notify-user-tags',response[0]);
							}
							else if(key == 'notify-email_admin_confirm')
							{
								$('#notify-email_admin_div').show();
								$('#'+key).attr('checked',true);
								$(this).showNotifyTags(app_id,notify_level,'notify-admin-tags',response[0]);
							}
							else if(key == 'shc-hier')
							{
								$('#'+key).attr('checked',true);
								$('#shc-hier_vals_div').show();
							}
							else
							{
								$('#add-'+myclass+'-div #'+key).val(value);
							}
						}
						else if(value == 0)
						{
							$('#add-'+myclass+'-div #'+key).val(value);
							if(key == 'txn-is_conditional')
							{
								$('#txn-conditional-options').hide();
							}
							else if(key == 'rel-is_conditional')
							{
								$('#rel-conditional-options').hide();
							}
							else if(key == 'ent-show_ui')
							{
								show_ui = 0;
								$('#ent-show_in_menu_div').hide();
								$('#ent-menu_icon_type_div').hide();
								$(this).showEntIcons('');
								$('#ent-menu_position_div').hide();
								$('#ent-top_level_page_div').hide();
							}
							else if(key == 'ent-show_in_menu')
							{
								menu_selected = 0;
								$('#add-'+myclass+'-div #'+key).val(value);
							}
							else if(key == 'ent-hierarchical')
							{
								$('#ent-page-attributes-div').hide();
								$('#ent-parent_item_colon_div').hide();
							}
							else if(key == 'txn-advanced-option')
							{
								if(txn_inline == 1){
									$(this).setInline('inline','');
								}
								else {
									$(this).setInline('notinline','');
								}
								$('#txntabs').hide();
							}
							else if(key == 'txn-rewrite')
							{
								$('#ent-rewrite_slug').attr('disabled',true);
							}
							else if(key == 'txn-hierarchical')
							{
								$('select>option[value="' + value + '"]').prop('selected', true);
								$('#txn-parent_item_div').hide();
								$('#txn-parent_item_colon_div').hide();
								$('#txn-separate_items_with_comas_div').show();
								$('#txn-add_or_remove_items_div').show();
								$('#txn-choose_from_most_used_div').show();
							}
							else if(key == 'txn-rewrite')
							{
								$('#txn-custom_rewrite_slug').attr('disabled',true);
							}
							else if(key == 'txn-show_ui')
							{
								$('#txn-show-in-nav-menus-div').hide();
								$('#txn-show-in-menu-div').hide();
							}
							else if(key == 'rel-connected-display')
							{
								$('#reltabs-2-li').hide();
							}
							else if(key == 'rel-related-display')
							{
								$('#reltabs-3-li').hide();
							}
							else if(key == 'form-advanced-option')
							{
								$('#form-tabs').hide();
							}
							else if(key == 'notify-email_user_confirm')
							{
								$('#notify-email_user_div').hide();
							}
							else if(key == 'notify-email_admin_confirm')
							{
								$('#notify-email_admin_div').hide();
							}
							else if(key == 'form-enable_form_schedule')
							{
								$('#form-schedule_div').hide();
							}
							else if(key == 'form-setup_page')
							{
								$('#form-setup_page_title_div').hide();
							}
							else if(key == 'shc-setup_page')
							{
								$('#shc-setup_page_title_div').hide();
							}
							else if(key == 'shc-app_dash')
							{
								$('#shc-app_dash_title_div').hide();
								$('#shc-app_dash_loc_div').hide();
							}
							else if(key == 'shc-hier')
							{
								$('#'+key).attr('checked',false);
								$('#shc-hier_vals_div').hide();
							}
						}
						else{
							if(key == 'rel-type')
							{
								if(value == 'many-to-many')
								{
									$('#reltabs-3-li').show();
									rel_type = "many-to-many";
								}
								else if(value == 'one-to-many')
								{
									$('#reltabs-3-li').hide();
									rel_type = "one-to-many";
								}
								$('#'+key).val(value);
							}
							else if(key == 'widg-type')
							{
								$(this).showWidgByType(value,'edit');
								widg_type = value;
								$('#'+key).val(value);
							}
							else if(key == 'widg-dash_subtype')
							{
								$(this).showWidgFields(value,widg_type,'edit');
								widg_subtype = value;
								$('#'+key).val(value);
							}
							else if(key == 'widg-side_subtype')
							{
								$(this).showWidgFields(value,widg_type,'edit');
								widg_subtype = value;
								$('#'+key).val(value);
							}
							else if(key == 'ent-show_in_menu')
							{
								menu_selected = value;
								$('#'+key).val(value);
							}
							else if(key == 'form-confirm_method')
							{
								if(value == 'text')
								{
									$('#form-confirm_txt_div').show();
									$('#form-confirm_url_div').hide();
								}
								else
								{
									$('#form-confirm_txt_div').hide();
									$('#form-confirm_url_div').show();
								}
							}
							else if(key == 'form-temp_type')
							{
								if(value == 'Bootstrap')
								{
									$('#form-font_awesome').attr('checked',true);
									$('#form-font_awesome').attr('disabled',true);
									$('#form-submit_button_fa_div').show();
								}
								$('#'+key).val(value);
							}
							else if(key == 'ent-label' || key == 'ent-name' || key == 'rel-name' || key =='form-name' || key == 'glob-name'){
								$('#add-'+myclass+'-div #'+key).val(value);
							}
							else if(key == 'shc-pgn_class')
							{
								$('#'+key).val(value);
								$('#shc-pgn_class_div').show();
							}
							else {
								$('#'+key).val(value);
							}
						}
					}
				});
				if(menu_selected == 0 && show_ui == 1)
				{
					$('#ent-menu_icon_type_div').hide();
					$(this).showEntIcons('');
					$('#ent-menu_position_div').hide();
					$('#ent-top_level_page_div').hide();
				}
				else if(menu_selected == 2 && show_ui == 1)
				{
					$('#ent-menu_icon_type_div').show();
					$('#ent-menu_position_div').hide();
					$('#ent-top_level_page_div').show();
				}
				else if(menu_selected == 1 && show_ui == 1)
				{
					$('#ent-menu_icon_type_div').show();
					$('#ent-menu_position_div').show();
					$('#ent-top_level_page_div').hide();
				}
				} //if response end
	
				}, 'json');
				
		
                		$(this).getBreadcrumb(myclass);
				
				$('button#save-'+myclass).html('<i class="icon-save"></i>'+ wpas_vars.update);
				$('button#save-'+myclass).val('Update');
				$('button#save-'+myclass).attr('id','update-'+myclass);
				$('#add-'+myclass+'-div input#app').val(app_id);
				$('label.error').each(function() {
                                	$(this).remove();
                        	});
				$('label.valid').each(function() {
                                	$(this).remove();
                        	});

                        	$('#add-'+myclass+'-div').show();
        });
	$(document).on('click','td#edit_td a#role-name',function(){
		role = $(this).html();
		role_id = $(this).parent().parent().find('span#edit.role a').attr('href').replace('#','');
		myaction = 'edit';
		$(this).editRole(app_id,role_id,myaction);
	});
	$(document).on('click','span#edit.role a',function(){
		role = $(this).parent().parent().parent().find('a#role-name').html();
		role_id = $(this).attr('href').replace('#','');
		myaction = 'edit';
		$(this).editRole(app_id,role_id,myaction);
	});
	$(document).on('click','p#add-role a,#add-new.role a',function(){
		role_id = '';
		myaction = 'add';
		app_id = $('input#app').val();	
		app = $('input#app_title').val();
		$(this).editRole(app_id,role_id,myaction);
	});

	$(document).on('click','#cancel.btn',function(){
                if($('ul li#third a').attr('href') != undefined)
                {
			var link = $('ul li#third a').attr('href');
		}
                else 
                {
			var link = $('ul li#second a').attr('href');
                }
		$(this).showLink(link);
         });

	$(document).on('click','ul.breadcrumb li a',function(){
		var link = $(this).attr('href');
                if($('ul li#fourth').html() == wpas_vars.edit_admin_layout)
		{
			app_id = $('input#app').val();	
			app = $('input#app_title').val();
			list_type= 'entity';
			ent = $('ul li#third a').html().replace(wpas_vars.entity + ' - ','');
			$('div #list-entity').find('#ent-name').each(function () {
				if($(this).html() == ent)
				{
					ent_id = $(this).attr('href').replace('#','');
				}
			});
			$(this).showLink(link,list_type);
		}
		else if($(this).parent().attr('id') == 'first')
		{
			return;
		}
		else
		{
			$(this).showLink(link);
		}
	});
	
	$(document).on('click','#update-option a, #edit-option.btn',function(){
                $('.group1').hide();
		$('#add-option-div').show();
		$('#add-option-div').fadeTo('fast',1,function(){
                });
                $('#add-option-div :input').removeAttr('disabled');
                $('#add-option-div button').removeAttr('disabled'); 
		$('#ao_adm_notice1').attr('checked', false);
		$('#ao_adm_notice2').attr('checked', false);
		$('#edit-btn-div').hide();
		app_id = $('#app-save input#app').val();
		app = $('input#app_title').val();
		$.get(ajaxurl,{action:'wpas_get_app_options',app_id:app_id}, function(response){
				$.each(response,function (key,value) {
                                        if(value != undefined)
                                        {
                                                if(value == 1)
                                                {
                                                        $('#add-option-div #'+key).attr('checked', true);
							if(key == 'ao_modify_navigation_menus')
							{
								$('#support-cust-nav-div').show();
							}
							else if(key == 'ao_force_dashboard_to_column')
							{
								$('#ao_force_col_div').show();
							}
							else if(key == 'ao_adm_notice1')
							{
								$('#ao_adm_notice1_detail_div').show();
							}
							else if(key == 'ao_adm_notice2')
							{
								$('#ao_adm_notice2_detail_div').show();
							}
							else if(key == 'ao_set_uitheme')
							{
								$('#ao_theme_type_div').show();
							}
                                                }
                                                else{
							if(key == 'ao_theme_type')
							{
								$(this).changeTheme(value);
							}
							$('#add-option-div #'+key).val(value);
                                                }
                                        }
				});
		}, 'json');
		$('button#save-option').html('<i class="icon-save"></i>' + wpas_vars.update);
		$('button#save-option').val('Update');
		$('button#save-option').attr('id','update-option');
		$(this).getBreadcrumb('update-option');

	});
	$(document).on('click','#save-app.btn',function(){
		var valid = $('#app_form').valid();
                if(!valid)
                {
                        return false;
                }
	});
	$(document).on('click','#tab-tax input.checkall,#tab-ent input.checkall,#tab-def input.checkall,#tab-widg input.checkall,#tab-form input.checkall,#tab-view input.checkall',function(){
		if(this.checked == true)
		{
			$(this).closest('div').find(':input[type=checkbox]').each(function() {
				$(this).attr('checked',true);
			});	
		}
		else
		{
			$(this).closest('div').find(':input[type=checkbox]').each(function() {
				$(this).attr('checked',false);
			});	
		}
	});
	$(document).on('click','button#delete-close,button#delete-cancel',function(){
                $('#confirmdeleteModal').modal('hide');
        });
	$(document).on('change','#form-attached_entity',function(){
		primary_entity = $('#form-attached_entity :selected').val();
                app_id = $('input#app').val();
		$.get(ajaxurl,{action:'wpas_get_entities',type:'form_dependents',app_id:app_id,primary_entity:primary_entity}, function(response)
		{
			$('#add-form-div #form-dependents').html(response);
		});
	});
	$(document).on('click','.add-cond',function(){
		cond_count = 1;
		list_id = $(this).closest('.cond-div').parent().attr('id');
		from = list_id.replace('-cond-list','');
		from = from.replace('_cond-list','');
		$('.cond-div').each(function() {
			div_id = $(this).attr('id').replace('cond-','');
			if(div_id > cond_count){
				cond_count = div_id;
			}
		});
                app_id = $('input#app').val();
		if(from == 'fld'){
			field_id = field_id;
                	ent_id = $('input#ent').val();
		}
		else if(from == 'txn'){
			field_id = txn_id;
                	ent_id = $('#txn-attach').find('option:selected').val();
		}
		else if(from == 'rel'){
			field_id = '';
			if($('#rel-from-name').val() != 'user'){
                                ent_id = $('#rel-from-name').val();;
                        }
                        else {
                                ent_id = $('#rel-to-name').val();;
                        }
		}
                $.get(ajaxurl,{action:'wpas_get_cond_list',app_id:app_id,ent_id:ent_id,field_id:field_id,cond_count:cond_count,from:from},function(response){
                        $('#' + list_id).append(response);
                });
        });
	$(document).on('click','.delete-cond',function(){
		div_id = $(this).closest('.layout-edit-icons').attr('id');
		rdiv = div_id.replace('add-delete-','');
		$('#'+rdiv).remove();
        });
	$(document).on('click','.cond-attr',function(){
                app_id = $('input#app').val();
                ent_id = $('input#ent').val();
                var type = $('option:selected', this).attr('att-type');
		list_id = $(this).closest('.cond-div').parent().attr('id').replace('cond-list','');
		div_id = $(this).attr('id').replace(list_id+'cond_attr_','');
                $.fn.changeCondVal(list_id,div_id,type,app_id,ent_id,$(this).val(),'');
                $('#cond-add-delete-'+div_id).show();
        });
});
