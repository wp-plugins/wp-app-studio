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
	var widget_id = "";
	var widget = "";
	var role = "";
	var role_id = "";
	var form= "";
	var form_id = "";

	$.validator.setDefaults({
    		ignore: ''
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
					$('button#save-role').html('<i class="icon-save"></i>Update');
					$('button#save-role').val('Update');
					$('button#save-role').attr('id','update-role');
				}
				else if(myaction == 'add')
				{
					$(this).getBreadcrumb('add-role');
					$('button#update-role').html('<i class="icon-save"></i>Save');
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
		var app_url = '<li id=\"second\"><a href=\"#list-' + app_link + '\"> Application - ' + app + '</a><span class=\"divider\">/</span></li>';
		var ent_url = '<li id=\"third\"><a href=\"#list-ent-fields\">Entity - '+ent+'</a><span class=\"divider\">/</span></li>';  
		var rel_url = '<li id=\"third\"><a href=\"#list-rel-fields\">Relationship - '+rel+'</a><span class=\"divider\">/</span></li>'; 
		var help_url = '<li id=\"third\"><a href=\"#list-help-fields\">Help - '+help+'</a><span class=\"divider\">/</span></li>';  
		var form_url = '<li id=\"third\"><a href=\"#list-form\">Form - '+form+'</a><span class=\"divider\">/</span></li>';  
		if(type == 'app')
		{
			$('ul.breadcrumb').append('<li id=\"second\" class=\"active\">Application - ' + app + '</li>');
		}
		else if(type == 'entity')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">Entity - '+ent+'</li>');
		}
		else if(type == 'taxonomy')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">Taxonomy - '+txn+'</li>');
		}
		else if(type == 'relationship')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">Relationship - '+rel+'</li>');
		}
		else if(type == 'relationship-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(rel_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Attribute - '+field+'</li>');
		}
		else if(type == 'help-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(help_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Tab - '+field+'</li>');
		}
		else if(type == 'entity-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(ent_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Attribute - '+field+'</li>');
		}
		else if(type == 'help')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">Help - '+help+'</li>');
		}
		else if(type == 'shortcode')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">View - '+shc+'</li>');
		}
		else if(type == 'widget')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">Widget - '+widget+'</li>');
		}
		else if(type == 'form')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">Form - '+form+'</li>');
		}
		else if(type == 'add-entity')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Entity</li>');
		}
		else if(type == 'add-entity-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(ent_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Add New Attribute</li>');
		}
		else if(type == 'edit_layout')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(ent_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Edit Admin Layout</li>');
		}
		else if(type == 'edit_form_layout')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(form_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Edit Form Layout</li>');
		}
		else if(type == 'add-relationship-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(rel_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Add New Attribute</li>');
		}
		else if(type == 'add-taxonomy')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Taxonomy</li>');
		}
		else if(type == 'add-relationship')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Relationship</li>');
		}
		else if(type == 'add-option')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Application Settings</li>');
		}
		else if(type == 'add-help')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Help</li>');
		}
		else if(type == 'add-shortcode')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New View</li>');
		}
		else if(type == 'add-widget')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Widget</li>');
		}
		else if(type == 'add-form')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Form</li>');
		}
		else if(type == 'add-help-field')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append(help_url);
			$('ul.breadcrumb').append('<li id=\"fourth\" class=\"active\">Add New Tab</li>');
		}
		else if(type == 'update-option')
		{       
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Update Application Settings</li>');
		}
		else if(type == 'add-role')
		{      
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\" class=\"active\">Add New Role</li>');
		}
		else if(type == 'role')
		{
			$('ul.breadcrumb').append(app_url);
			$('ul.breadcrumb').append('<li id=\"third\"  class=\"active\">Role - '+role+'</li>');
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
			$.each(response[0],function (key,value) {
				if(value != undefined)
				{
					if(tinymce.get(key) != undefined)
                                        {
                                               tinymce.get(key).setContent(value);
                                        }
					else if(value == 1 && key != 'fld_max_file_uploads')
					{
						if($(current_div+' #'+key).attr('type') == 'radio')
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
						if($(current_div+' #'+key).attr('type') == 'radio')
						{
							$('input:radio[name="'+key+'"][value="0"]').attr('checked', true);
						}
					}
					else{
                                                if(key == 'fld_values')
                                                {
                                                     $('#fld_values_div').show();
                                                }
						else if(key == 'fld_type')
						{
							$('#fld_file_size_div').hide();
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
							$('#fld_file_ext_div').hide();
							$('#fld_multiple_div').hide();
							$('#fld_is_advanced_div').hide();
							$('#fld_image_thickbox_div').hide();
							$('#fld_fa_chkd_div').hide();
							$('#fld_fa_unchkd_div').hide();
							$('#fld_uniq_id_div').show();
							$(this).changeValidateMsg(value);
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
				

		$('button#save-'+type+'-field').html('<i class="icon-save"></i>Update');
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

	$(document).on('click','p#add-option a, p#add-relationship a, p#add-taxonomy a, p#add-help a,p#add-entity a,p#add-shortcode a,p#add-widget a,p#add-form a,#add-new.entity a,#add-new.relationship a,#add-new.taxonomy a,#add-new.help a,#add-new.shortcode a,#add-new.widg a,#add-new.form a,#add_field.entity a,#add_field.relationship a,#add_field.help a, #add_field_entity a,#add_field_rel a,#add_field_help a',function(){
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
			} 
			else if($(this).attr('href').match(/#help/g)) 
			{
				button = "help";
				if(div_id == 'add_field')
				{
					help = $(this).parent().parent().parent().find('#help-object_name').html();
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
				$('#ent-limit_user_relationship_role_div').hide();
                		$.get(ajaxurl,{action:'wpas_get_roles',type:button,app_id:app_id}, function(response)
				{
					$('#add-entity-div #ent-limit_user_relationship_role').html(response);
				}); 
			}
			else if(button == 'taxonomy')
			{
				$('#txntabs').hide();
                		$.get(ajaxurl,{action:'wpas_get_entities',type:button,app_id:app_id}, function(response)
				{
					$('#add-taxonomy-div #txn-attach').html(response);
				});
			}
			else if(button == 'shortcode')
			{
				$('#add-shortcode-div input#app').val(app_id);
                		$.get(ajaxurl,{action:'wpas_get_entities',type:button,app_id:app_id}, function(response)
				{
					$('#add-shortcode-div #shc-attach').html('<option value="">Please select</option>'+response);
				});
			}
			else if(button == 'widget')
			{
				$('#add-widget-div input#app').val(app_id);
                		$.get(ajaxurl,{action:'wpas_get_entities',type:button,app_id:app_id}, function(response)
				{
					$('#add-widget-div #widg-attach').html('<option value="">Please select</option>'+response);
				}); 
                		$.get(ajaxurl,{action:'wpas_get_rels',app_id:app_id}, function(response)
				{
					$('#add-widget-div #widg-attach-rel').html('<option value="">Please select</option>'+response);
				}); 
				$('#widg-dash_subtype_div').hide();
				$('#widg-side_subtype_div').hide();
				$('#widg-label_div').hide();
                                $('#widg-wdesc_div').hide();
				$('#widg-html_div').hide();
				$('#widg-attach_div').hide();
				$('#widg-attach-rel_div').hide();
				$('#widg-rel-conn-type_div').hide();
				$('#widg-rel-to-title_div').hide();
				$('#widg-layout_div').hide();
				$('#widg-css_div').hide();
				$('#widg-post_per_page_div').hide();
				$('#widg-order_div').hide();
				$('#widg-orderby_div').hide();
				$('#widg-post_status_div').hide(); 
			}
			else if(button == 'relationship')
                        {
				$('#add-rel-field-div input#app').val(app_id);
				$('#add-rel-field-div input#rel').val(rel_id);
				$('#rel-connected-div').hide();
				$('#rel-related-div-group').hide();
				$('#rel-related-div').hide();
                                $.get(ajaxurl,{action:'wpas_get_entities',type:button+'_from',app_id:app_id}, function(response)
                                {
                                        $('#add-relationship-div #rel-from-name').html('<option value="">Please select</option>'+response);
                                });
                                $.get(ajaxurl,{action:'wpas_get_entities',type:button+'_to',app_id:app_id}, function(response)
                                {
                                        $('#add-relationship-div #rel-to-name').html('<option value="">Please select</option>'+response);
                                });
                        }
			else if(button == 'help')
			{
				$('#add-help-field-div input#app').val(app_id);
				$('#add-help-field-div input#help').val(help_id);
                		$.get(ajaxurl,{action:'wpas_get_entities',type:button,app_id:app_id}, function(response)
				{
					response = '<option value="">Please select</option>' + response;
					$('#add-help-div #help-object_name').html(response);
				});
			}
			else if(button == 'option')
			{
                               $('#support-cust-nav-div').hide();
                               $('#ao_force_col_div').hide();
			       $('#ao_theme_type_div').hide();
			}
			else if(button == 'form')
			{
				$('#add-form-div input#app').val(app_id);
				$('#add-form-div input#form').val(form_id);
                                $.get(ajaxurl,{action:'wpas_get_entities',type:'form',app_id:app_id}, function(response)
                                {
                                        $('#add-form-div #form-attached_entity').html('<option value="">Please select</option>'+response);
                                });
				$('#form-dependents').html('');
                                $('#form-font_awesome').attr('checked', true);
				$('#form-font_awesome').attr('disabled',true);
				$('#form-confirm_url_div').hide();
				$('#form-email_user_div').hide();
				$('#form-email_admin_div').hide();
				$('#form-schedule_div').hide();
				$('#form-tabs').hide();
				$('#formtabs-3-li').show();
				$('#form-submit_status_div').show();
				$('#form-noresult_msg_div').hide();
				$('#form-result_msg_div').hide();
				$('#form-ajax_search_div').hide();
				$('#form-enable_operators_div').hide();
				$('#form-attached_view_div').hide();
			}
			$('label.error').each(function() {
                                $(this).remove();
                        });
                	$('label.valid').each(function() {
                                $(this).remove();
                        });
			$('button#save-'+button+'-field').html('<i class="icon-save"></i>Save');
			$('button#save-'+button+'-field').val('Save');

			$('button#update-'+button).html('<i class="icon-save"></i>Save');
			$('button#update-'+button).val('Save');
			$('button#update-'+button).attr('id','save-'+button);
				
		}
	});
	$(document).on('click','p#entity a, p#taxonomy a, p#relationship a, p#help a,p#shortcode a,p#widget a,p#role a,p#form a',function(){
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

	$(document).on('click','#save-relationship.btn, #save-taxonomy.btn, #save-entity.btn, #save-help.btn, #save-shortcode.btn, #save-widget.btn,#save-role.btn, #save-form.btn',function(event){
                event.preventDefault();
		app_id = $('input#app').val();
		var btn_id = $(this).attr('id');
                app = document.getElementById('app-name').value;
		type = btn_id.replace("save-","");
		type = type.replace("update-","");
		tinyMCE.triggerSave();
		$('#'+type+'-form').validate();
		var valid = $('#'+type+'-form').valid();
		if(!valid)
		{
			return false;
		}
		form_data = $('#'+type+'-form :input').serialize();
		var checked_count = 0;
		$('#'+type+'-form :input[type=checkbox]').each(function() {     
    			if (this.checked) {
				checked_count++;
				if(form_data.search(this.name+'=1') == -1)
				{
					form_data += '&'+this.name+'=1';
				}
			}
			else
			{
				form_data += '&'+this.name+'=0';
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
		tinyMCE.triggerSave();
		var valid = $('#'+type+'-form').valid();
		if(!valid)
		{
			return false;
		}
		form_data = $('#'+type+'-form :input').serialize();
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
			$('#add-option a').html('<i class="icon-picture"></i>Update Settings');
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
	$(document).on('click','#update-taxonomy.btn, #update-entity.btn, #update-relationship.btn, #update-help.btn, #update-shortcode.btn, #update-widget.btn,#update-role.btn, #update-form.btn',function(event){
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
		
		tinyMCE.triggerSave();
		if($("#"+form_name).validate().form() == true)
		{
			var form_data = $('#'+type+'-form :input').serialize();
			$('#'+ form_name + ' :input[type=checkbox]').each(function() {     
				if (this.checked) {
					form_data += '&'+this.name+'=1';
				}
				else
				{
					form_data += '&'+this.name+'=0';
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
			tinyMCE.triggerSave();
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
	$(document).on('click','#view.entity a, #view.relationship a,#view.help a, td#edit_td a#ent-name, td#edit_td a#rel-name, td#edit_td a#help-object_name, tbody#the-list a#ent-name,#the-list a#help-object_name,#the-list a#rel-name',function(){
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
		else if($(this).attr('id') == 'help-object_name' || $(this).attr('class') == 'help' || $(this).parent().attr('class') == 'help')
		{
			type = 'help';
			help_id = comp_id;
			help = $(this).parent().parent().parent().find('#help-object_name').html();
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



	$(document).on('click','span#edit.entity a,span#edit.relationship a,span#edit.taxonomy a,span#edit.shortcode a,span#edit.widget a,span#edit.help a,span#edit.form a,#edit-entity.btn,#edit-relationship.btn,#edit-help.btn,td#edit_td a#txn-name,td#edit_td a#shc-label,td#edit_td a#widg-title,td#edit_td a#form-name',function(event){
		var subtype = "";
                app_id = $('#app_form input#app').val();
		if($(this).parent().attr('class') == 'app')
		{
			return;
		}
                event.preventDefault();
		if($(this).attr('id') == 'txn-name')
		{
			var myclass = 'taxonomy';
                        txn = $(this).parent().parent().parent().find('#txn-name').html();
		}
		else if($(this).attr('id') == 'shc-label')
		{
			var myclass = 'shortcode';
                        shc = $(this).parent().parent().parent().find('#shc-label').html();
		}
		else if($(this).attr('id') == 'widg-title')
		{
			var myclass = 'widget';
                        widget = $(this).parent().parent().parent().find('#widg-title').html();
		}
		else if($(this).attr('id') == 'form-name')
		{
			var myclass = 'form';
                        form = $(this).parent().parent().parent().find('#form-name').html();
		}
		else
		{
			var myclass = $(this).parent().attr('class');
                        txn = $(this).parent().parent().parent().find('#txn-name').html();
			shc = $('a#shc-label').html();
			widget = $('a#widg-title').html();
			form = $('a#form-name').html();
		}

                var comp_id = $(this).attr('href').replace('#','');
		if(myclass == 'entity')
		{
			ent_id = comp_id;
                        ent = $(this).parent().parent().parent().find('#ent-name').html();
		}
		else if(myclass == 'taxonomy')
		{
			txn_id = comp_id;
		}
		else if(myclass == 'relationship')
		{
			rel_id = comp_id;
			rel = $('a#rel-name').html();
		}
		else if(myclass == 'help')
		{
			help_id = comp_id;
			help = $('a#help-object_name').html();
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
				menu_selected = "";
				show_ui = "";
				rel_type = "";
				widg_rel_name = "";
				primary_entity = "";
				dependents = "";
				$.each(response[0],function (key,value) {
					if(value != undefined)
					{
						if(tinymce.get(key) != undefined)
                                                {
                                                        tinymce.get(key).setContent(value);
                                                }
						else if(key == 'txn-attach' || key == 'shc-attach' || key == 'help-object_name' || key == 'widg-attach')
						{
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass,values:value}, function(response)
                                			{
								if(key == 'shc-attach' || key == 'help-object_name' || key == 'widg-attach')
								{
									response = '<option value="">Please select</option>' + response;
								}
                                        			$('#add-'+myclass+'-div #'+ key).html(response);
								if(key == 'help-object_name')
								{
									if(value.indexOf('txn-') == 0)
									{
										$('#help-screen_type-div').hide();
									}
									else
									{
										$('#help-screen_type-div').show();
									}
								}
                                			});
							if(key == 'shc-attach' || key == 'widg-attach')
							{
								//for insert entity attributes 
								app_id = $('input#app').val();	
								$.fn.getAddons(layout_id,app_id,'entity',value,'');
							}
						}
						else if(key == 'ent-limit_user_relationship_role')
						{
							$.get(ajaxurl,{action:'wpas_get_roles',type:'entity',app_id:app_id,value:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
						}
						else if(key == 'widg-attach-rel')
						{
							$.get(ajaxurl,{action:'wpas_get_rels',app_id:app_id,value:value}, function(response)
							{
								response = '<option value="">Please select</option>'+response;
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
							widg_rel_name = value; 


						}
						else if(key == 'widg-rel-conn-type')
						{
							$.get(ajaxurl,{action:'wpas_get_rel_conn_types',app_id:app_id,rel_name:widg_rel_name,value:value},function(response){
                                				$('#widg-rel-conn-type').html(response);
                        				});
							$.fn.getAddons(layout_id,app_id,'relationship',widg_rel_name,value);
						}
						else if(key == 'rel-from-name')
						{
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass+'_from',values:value}, function(response)
                                                        {
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
						}	
						else if(key == 'rel-to-name')
						{
							$.get(ajaxurl,{action:'wpas_get_entities',app_id:app_id,type:myclass+'_to',values:value}, function(response)
                                                        {
								$('#add-'+myclass+'-div #'+ key).html(response);
							});
						}
						else if(key == 'form-attached_entity')
						{
							$.get(ajaxurl,{action:'wpas_get_entities',type:'form',app_id:app_id,values:value}, function(response)
							{
								$('#add-'+myclass+'-div #'+ key).html('<option value="">Please select</option>'+response);
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
                                				$('#form-attached_view_div').show();
								$('#formtabs-3-li').hide();
								$('#form-submit_status_div').hide();
								$('#form-noresult_msg_div').show();
								$('#form-result_msg_div').show();
								$('#form-ajax_search_div').show();
								$('#form-enable_operators_div').show();
							}
							else
							{
								$('#form-attached_view_div').hide();
								$('#formtabs-3-li').show();
								$('#form-submit_status_div').show();
								$('#form-noresult_msg_div').hide();
								$('#form-result_msg_div').hide();
								$('#form-ajax_search_div').hide();
								$('#form-enable_operators_div').hide();
							}
							$('#add-'+myclass+'-div #'+key).val(value);
						}	
						else if(key == 'form-attached_view')
						{
							$.get(ajaxurl,{action:'wpas_get_views',app_id:app_id,val:value}, function(response)
                        				{
                                				$('#add-form-div #form-attached_view').html('<option value="">Please select</option>'+response);
                                				$('#form-attached_view_div').show();
                        				});
						}	
						else if(key == 'form-confirm_sendto')
						{
							$.get(ajaxurl,{action:'wpas_get_email_attrs',primary_entity:primary_entity,dependents:dependents,app_id:app_id,values:value}, function(response)
						{
							$('#add-'+myclass+'-div #'+ key).html(response);
						});
						}
						else if(value == 1)
						{
							$('#add-'+myclass+'-div #'+key).attr('checked', true);
							if(key == 'ent-advanced-option')
							{
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
								$('#ent-menu_icon_div').show();
								$('#ent-menu_icon_32_div').show();
								$('#ent-menu_position_div').show();
								$('#ent-top_level_page_div').show();
								show_ui = 1;
							}
							else if(key == 'ent-show_in_menu')
							{
								menu_selected = 1;
							}
							else if(key == 'ent-hierarchical')
							{
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#ent-page-attributes-div').show();
								$('#ent-parent_item_colon_div').show();
							}
							else if(key == 'ent-has_user_relationship')
							{
								$('#ent-limit_user_relationship_role_div').show();
							}
							else if(key == 'txn-advanced-option')
							{
								$('#txntabs').show();
							}
							else if(key == 'txn-hierarchical')
							{
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
							}
							else if(key == 'txn-rewrite')
							{
								$('#add-'+myclass+'-div #'+key).val(value);
								$('#txn-custom_rewrite_slug').removeAttr('disabled');
							}
							else if(key == 'rel-connected-display')
							{
								$('#rel-connected-div').show();
								if(rel_type == 'one-to-many')
								{
									$('#rel-connected-show-attributes-div').hide();
								}
								else if(rel_type == 'many-to-many')
								{
									$('#rel-connected-show-attributes-div').show();
								}

							}
							else if(key == 'rel-related-display')
							{
								$('#rel-related-div').show();
								$('#rel-related-div-group').show();
							}
							else if(key == 'form-advanced-option')
							{
								$('#form-tabs').show();
							}
							else if(key == 'form-email_user_confirm')
							{
								$('#form-email_user_div').show();
								show = 'form-confirm_msg';
								app_id = $('input#app').val();
								$.fn.getAddons(show,app_id,'email',primary_entity,'',dependents);
							}
							else if(key == 'form-email_admin_confirm')
							{
								$('#form-email_admin_div').show();
								show = 'form-confirm_admin_msg';
								app_id = $('input#app').val();
								$.fn.getAddons(show,app_id,'email',primary_entity,'',dependents);
							}
							else if(key == 'form-enable_form_schedule')
							{
								$('#form-schedule_div').show();
							}
							else
							{
								$('#add-'+myclass+'-div #'+key).val(value);
							}
						}
						else if(value == 0)
						{
							$('#add-'+myclass+'-div #'+key).val(value);
							if(key == 'ent-show_ui')
							{
								show_ui = 0;
								$('#ent-show_in_menu_div').hide();
								$('#ent-menu_icon_div').hide();
								$('#ent-menu_icon_32_div').hide();
								$('#ent-menu_position_div').hide();
								$('#ent-top_level_page_div').hide();
							}
							else if(key == 'ent-show_in_menu')
							{
								menu_selected = 0;
							}
							else if(key == 'ent-hierarchical')
							{
								$('#ent-page-attributes-div').hide();
								$('#ent-parent_item_colon_div').hide();
							}
							else if(key == 'txn-rewrite')
							{
								$('#ent-rewrite_slug').attr('disabled',true);
							}
							else if(key == 'txn-hierarchical')
							{
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
							}
							else if(key == 'rel-connected-display')
							{
								$('#rel-connected-div').hide();
							}
							else if(key == 'rel-related-display')
							{
								$('#rel-related-div').hide();
								$('#rel-related-div-group').hide();
							}
							else if(key == 'form-advanced-option')
							{
								$('#form-tabs').hide();
							}
							else if(key == 'form-email_user_confirm')
							{
								$('#form-email_user_div').hide();
							}
							else if(key == 'form-email_admin_confirm')
							{
								$('#form-email_admin_div').hide();
							}
							else if(key == 'form-enable_form_schedule')
							{
								$('#form-schedule_div').hide();
							}
						}
						else{
							if(key == 'rel-type')
							{
								if(value == 'many-to-many')
								{
									$('#rel-related-div-group').show();
									$('#rel-related-div').show();
									rel_type = "many-to-many";
								}
								else if(value == 'one-to-many')
								{
									$('#rel-related-div-group').hide();
									$('#rel-related-div').hide();
									rel_type = "one-to-many";
								}
							}
							if(key == 'widg-type')
							{
								if(value == 'sidebar')
								{
									$('#widg-attach_div').show();
									$('#widg-label_div').show();
									$('#widg-wdesc_div').show();
									$('#widg-dash_subtype_div').hide();
									$('#widg-html_div').hide();
									$('#widg-side_subtype_div').show();
									$('#widg-layout_div').show();
									$('#widg-css_div').show();
									$('#widg-post_per_page_div').show();
									$('#widg-order_div').show();
									$('#widg-orderby_div').show();
									$('#widg-post_status_div').show();
								}
								else if(value == 'dashboard')
								{
									$('#widg-label_div').hide();
									$('#widg-wdesc_div').hide();
									$('#widg-dash_subtype_div').show();
									$('#widg-side_subtype_div').hide();
									$('#widg-attach_div').hide();
									$('#widg-html_div').hide();
									$('#widg-layout_div').hide();
									$('#widg-css_div').hide();
									$('#widg-post_per_page_div').hide();
									$('#widg-order_div').hide();
									$('#widg-orderby_div').hide();
									$('#widg-post_status_div').hide();
								}
							}
							if(key == 'widg-dash_subtype')
							{
								subtype = value;
							}
							if(key == 'widg-side_subtype')
							{
								if(subtype != 'admin')
								{
									subtype = value;
								}
								if(subtype == 'relationship')
								{
									$('#widg-attach_div').hide();
									$('#widg-attach-rel_div').show();
									$('#widg-rel-conn-type_div').show();
									$('#widg-rel-to-title_div').show();
									$('#widg-order_div').hide();
									$('#widg-orderby_div').hide();
									$('#widg-post_status_div').hide();
								}
								else if(subtype == 'entity')
								{
									$('#widg-attach_div').show();
									$('#widg-attach-rel_div').hide();
									$('#widg-rel-conn-type_div').hide();
									$('#widg-rel-to-title_div').hide();
								}
							}
							else if(key == 'ent-show_in_menu')
							{
								menu_selected = value;
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
							}	
							$('#add-'+myclass+'-div #'+key).val(value);
						}
					}
				});
				if(menu_selected == 0 && show_ui == 1)
				{
					$('#ent-menu_icon_div').hide();
					$('#ent-menu_icon_32_div').hide();
					$('#ent-menu_position_div').hide();
					$('#ent-top_level_page_div').hide();
				}
				else if(menu_selected == 2 && show_ui == 1)
				{
					$('#ent-menu_icon_div').show();
					$('#ent-menu_icon_32_div').show();
					$('#ent-menu_position_div').hide();
					$('#ent-top_level_page_div').show();
				}
				else if(menu_selected == 1 && show_ui == 1)
				{
					$('#ent-menu_icon_div').show();
					$('#ent-menu_icon_32_div').show();
					$('#ent-menu_position_div').show();
					$('#ent-top_level_page_div').hide();
				}
				if(myclass == 'widget' && subtype == 'admin')
				{
					$('#widg-html_div').show();
					$('#widg-attach_div').hide();
					$('#widg-layout_div').hide();
					$('#widg-css_div').hide();
					$('#widg-post_per_page_div').hide();
					$('#widg-order_div').hide();
					$('#widg-orderby_div').hide();
					$('#widg-post_status_div').hide();
				}
                                else if(myclass == 'widget' && subtype == 'entity')
                                {
					$('#widg-html_div').hide();
					$('#widg-attach_div').show();
					$('#widg-layout_div').show();
					$('#widg-css_div').show();
					$('#widg-post_per_page_div').show();
					$('#widg-order_div').show();
					$('#widg-orderby_div').show();
					$('#widg-post_status_div').show();
                                }
				
				} //if response end
				
				}, 'json');

		
                		$(this).getBreadcrumb(myclass);
				
				$('button#save-'+myclass).html('<i class="icon-save"></i>Update');
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
                if($('ul li#fourth').html() == 'Edit Admin Layout')
		{
			app_id = $('input#app').val();	
			app = $('input#app_title').val();
			list_type= 'entity';
			ent = $('ul li#third a').html().replace('Entity - ','');
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
		$('#edit-btn-div').hide();
		app_id = $('#app-save input#app').val();
		app = $('input#app_title').val();
		$.get(ajaxurl,{action:'wpas_get_app_options',app_id:app_id}, function(response){
				$.each(response,function (key,value) {
                                        if(value != undefined)
                                        {
						if(tinymce.get(key) != undefined)
                                        	{
                                               		tinymce.get(key).setContent(value);
                                        	}
                                                else if(value == 1)
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
		$('button#save-option').html('<i class="icon-save"></i>Update');
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
	$(document).on('click','#tab-tax input.checkall,#tab-ent input.checkall,#tab-def input.checkall,#tab-widg input.checkall',function(){
		divId = $(this).attr('id').replace('-all','');
		if(this.checked == true)
		{
			$('#' + divId + ' :input[type=checkbox]').attr('checked',true);
		}
		else
		{
			$('#' + divId + ' :input[type=checkbox]').attr('checked',false);
		}
	});

	$(document).on('click','#check-status.btn',function(){
		queue_id = $(this).attr('href').replace('#','');	
		tr_object = $(this).parent().parent();
		$.post(ajaxurl,{action:'wpas_check_status_generate',queue_id:queue_id,nonce:wpas_vars.nonce_check_status_generate}, function(response){
			if(response[0] != undefined)
			{
				if(response[1].search("Success") != -1 || response[1].search("Change") != -1)
				{
					tr_object.find('#download-link').html('<a href="' + response[0] + '">Download</a>');	
				}
				else if(response[1].search("Error") != -1)
				{
					tr_object.find('#download-link').html('<a href="' + response[0] + '">Please open a support ticket</a>');				     }	
			}
			tr_object.find('#credit-used').html(response[4]);	
			tr_object.find('#credit-left').html(response[5]);	
			tr_object.find('#update-left').html(response[6]);	
			tr_object.find('#status').html(response[1]);	
			$('#status_info').hide();
			$('#status_error').hide();
			if(response[3])
			{
				$('#status_info').html(response[3]);
				$('#status_info').show();
			}
			if(response[2])
			{
				$('#status_error').html(response[2]);
				$('#status_error').show();
			}
		}, 'json');
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
		dependents = $('#form-dependents').val();
                $.get(ajaxurl,{action:'wpas_get_email_attrs',primary_entity:primary_entity,dependents:dependents,app_id:app_id}, function(response)
		{
			$('#add-form-div #form-confirm_sendto').html(response);
		});
	});
	$(document).on('change','#form-dependents',function(){
		primary_entity = $('#form-attached_entity :selected').val();
                app_id = $('input#app').val();
		dependents = $('#form-dependents').val();
                $.get(ajaxurl,{action:'wpas_get_email_attrs',primary_entity:primary_entity,dependents:dependents,app_id:app_id}, function(response)
		{
			$('#add-form-div #form-confirm_sendto').html(response);
		});
	});


});
