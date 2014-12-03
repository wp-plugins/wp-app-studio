jQuery(document).ready(function($) {
		var unique;
		// reserved words for entity and taxonomy
		var arr_ent = ['post','page','attachment','revision','nav_menu_item'];
		var arr_txn = ['attachment','attachment_id','author','author_name','calendar','cat','category','category__and','category__in','category__not_in','category_name','comments_per_page','comments_popup','cpage','day','debug','error','exact','feed','hour','link_category','m','minute','monthnum','more','name','nav_menu','nopaging','offset','order','orderby','p','page','page_id','paged','pagename','pb','perm','post','post__in','post__not_in','post_format','post_mime_type','post_status','post_tag','post_type','posts','posts_per_archive_page','posts_per_page','preview','robots','s','search','second','sentence','showposts','static','subpost','subpost_id','tag','tag__and','tag__in','tag__not_in','tag_id','tag_slug__and','tag_slug__in','taxonomy','tb','term','type','w','withcomments','withoutcomments','year'];
		$.validator.addMethod('noCap', function(value, element) { 
			if(value.match(/[A-Z]+/) != null)
			{
				return false;
			}
			return true;
		}, validate_vars.nocap_err);
		$.validator.addMethod('noSpace', function(value, element) { 
			if(value.indexOf(' ') < 0 && value != '' || value == '')
			{
				return true;
			}
			return false;
		}, validate_vars.nospace_err);
		$.validator.addMethod('noDash', function(value, element) { 
			if(value.indexOf('-') < 0 && value != '')
			{
				return true;
			}
			return false;
		}, validate_vars.nodash_err);
		$.validator.addMethod('checkNum', function(value, element) { 
			return this.optional(element) || /^(-1|[1-9][0-9]*)$/i.test(value);
		}, validate_vars.check_num);
		$.validator.addMethod('checkInt', function(value, element) { 
			return this.optional(element) || /^[1-9][0-9]*$/i.test(value);
		}, validate_vars.check_int);
		$.validator.addMethod('checkAlphaNum', function(value, element) { 
			return this.optional(element) || /^[a-z0-9]+$/i.test(value);
		}, validate_vars.check_alpha_num);
		$.validator.addMethod('checkAppTitle', function(value, element) { 
			return this.optional(element) || /^[a-z][a-z0-9 ]+$/i.test(value);
		}, validate_vars.check_app_title);
		$.validator.addMethod('checkAlphaDash', function(value, element) { 
			return this.optional(element) || /^[a-z\-]+$/i.test(value);
		}, validate_vars.check_alpha_dash);
		$.validator.addMethod('checkAlphaNumDash', function(value, element) { 
			return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
		}, validate_vars.check_alpha_num_dash);
		$.validator.addMethod('checkAlphaDashFa', function(value, element) { 
			return this.optional(element) || /^fa\-[a-z\-]+$/i.test(value);
		}, validate_vars.check_alpha_dash_fa);
		$.validator.addMethod('checkAlphaNumUnder', function(value, element) { 
			return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
		}, validate_vars.check_alpha_num_und);
		$.validator.addMethod('checkAlphaNumUnderSemiCurly', function(value, element) { 
			return this.optional(element) || /^[a-z0-9 \;\.\_\{\}]+$/i.test(value);
		}, validate_vars.check_alpha_num_und_semi_cur);
		$.validator.addMethod('checkTaxChar', function(value, element) { 
			return this.optional(element) || /^[a-z0-9 \.\;\,\_\{\}\[\]]+$/i.test(value);
		}, validate_vars.check_tax_char);
		$.validator.addMethod('checkAlphaNumUnderDash', function(value, element) { 
			return this.optional(element) || /^[a-z0-9\_\-]+$/i.test(value);
		}, validate_vars.check_alpha_num_und_dash);
		$.validator.addMethod('checkAlphaNumComma', function(value, element) { 
			return this.optional(element) || /^[a-z0-9,]+$/i.test(value);
		}, validate_vars.check_alpha_num_comma);
		$.validator.addMethod('checkVersion', function(value, element) { 
			return this.optional(element) || /^[0-9\.]+$/i.test(value);
		}, validate_vars.check_version);
		$.validator.addMethod('checkNotify', function(value, element) {
			if(!$('#notify-email_admin_confirm').attr('checked') && !$('#notify-email_user_confirm').attr('checked'))
			{
				return false;
			}
			return true;
		}, validate_vars.check_notify);
		$.validator.addMethod('checkRelUser', function(value, element) { 
			if($('#rel-from-name').val() == 'user' && value == 'user')
			{
				return false;
			}
			return true;	
		}, validate_vars.check_reluser);
		$.validator.addMethod('checkDefault', function(value, element) { 
			if($(element).attr('id') == 'txn-dflt_value')
			{
				var is_multiple = $('#txn-display_type').val();
				var fld_type = 'tax';
			}
			else
			{
				var is_multiple = $('#fld_multiple').attr('checked');
				var fld_type = $('#fld_type').val();
			}
			if(value.indexOf(';') > 0 && value != '')
			{
				if(fld_type == 'tax' && is_multiple != 'multi')
				{
					return false;
				}
				else if(fld_type == 'select' && is_multiple == undefined)
				{
					return false;
				}
				else if($.inArray(fld_type,Array('checkbox_list','select','tax')) == -1)
				{
					return false;
				}
				return true;
			}
			else
			{
				return true;
			}
		}, validate_vars.check_default);
				
				
		$.validator.addMethod('checkValues', function(value, element) { 
			hier = $('#txn-hierarchical').val();
			if(value.indexOf(';') < 0 && value != '')
			{
				return false;
			}
			else if(value.indexOf(';;') >= 0 && value != '')
			{
				return false;
			}
			else
			{
				var input = value.split(';');
				var valid = true;
				$.each(input, function(key,inp) {
					inp = $.trim(inp);
					if(inp.length != 0)
					{
						if((inp.match(/{/g) != null && inp.match(/{/g).length > 1) ||  (inp.match(/}/g) != null && inp.match(/}/g).length > 1))
						{
							return valid = false;
						}
						else if(inp.indexOf('{') > 0 && inp.indexOf('}') < 0)
						{
							return valid = false;
						}
						else if(inp.indexOf('{') < 0 && inp.indexOf('}') > 0)
						{
							return valid = false;
						}
						else if(inp.indexOf('{') > 0 && inp.indexOf('}') > 0 && (inp.indexOf('{') + 1 >= inp.indexOf('}')))
						{
							return valid = false;
						}
						else if(inp.indexOf('{') == 0)
						{
							return valid = false;
						}
						if(hier == 1)
						{
							if((inp.match(/\[/g) != null && inp.match(/\[/g).length > 1) ||  (inp.match(/]/g) != null && inp.match(/]/g).length > 1))
							{
								return valid = false;
							}
							else if(inp.indexOf('[') > 0 && inp.indexOf(']') < 0)
							{
								return valid = false;
							}
							else if(inp.indexOf('[') < 0 && inp.indexOf(']') > 0)
							{
								return valid = false;
							}
							else if(inp.indexOf('[') > 0 && inp.indexOf(']') > 0 && (inp.indexOf('[') + 1 >= inp.indexOf(']')))
							{
								return valid = false;
							}
							else if(inp.indexOf('[') == 0)
							{
								return valid = false;
							}
						}
						else if(hier == 0)
						{
							if(inp.indexOf('[') >= 0 || inp.indexOf(']') >= 0)
							{
								return valid = false;
							}
						}
					}
				});
				return valid;
			}
		}, validate_vars.check_values);
						
		$.validator.addMethod('checkSemiCo', function(value, element) { 
			if(value.indexOf(';') < 0 && value != '')
			{
				return false;
			}
			else if(value.indexOf(';;') >= 0 && value != '')
			{
				return false;
			}
			else
			{
				var input = value.split(';');
				var valid = true;
				var curly = false;
				$.each(input, function(key,inp) {
					inp = $.trim(inp);
					if(inp.length != 0)
					{
						if(curly && inp.indexOf('{') < 0 && inp.indexOf('}') < 0)
						{
							return valid = false;
						}
						else if(!curly && key != 0 && inp.indexOf('{') == 0 && inp.indexOf('}') > 0)
						{
							return valid = false;
						}
						else if((inp.match(/{/g) != null && inp.match(/{/g).length > 1) ||  (inp.match(/}/g) != null && inp.match(/}/g).length > 1))
						{
							return valid = false;
						}
						else if(inp.indexOf('{') == 0 && inp.indexOf('}') < 0)
						{
							return valid = false;
						}
						else if(inp.indexOf('{') < 0 && inp.indexOf('}') >= 0)
						{
							return valid = false;
						}
						else if(inp.indexOf('{') == 0 && inp.indexOf('}') > 0 && (inp.indexOf('{') + 1 >= inp.indexOf('}')))
						{
							return valid = false;
						}
						else if(inp.indexOf('{') > 0)
						{
							return valid = false;
						}
						else if(inp.indexOf('}') + 1 == inp.length)
						{
							return valid = false;
						}
						else if(inp.indexOf('{') >= 0)
						{
							curly = true;
						}
					}
				});
				return valid;
			}
		}, validate_vars.check_semico);
		$.validator.addMethod('noReservedEnt', function(value, element) { 
			if($.inArray(value,arr_ent) == -1)
			{
				return true;
			}
			return false;
		}, validate_vars.no_reserved);
		$.validator.addMethod('noReservedTxn', function(value, element) { 
			if($.inArray(value,arr_txn) == -1 )
			{
				return true;
			}
			return false;
		}, validate_vars.no_reserved);
		$.validator.addMethod('checkHelp', function(value, element) { 
			var help_type = $('#help-type').val();
			var attached_id;
			if(help_type == 'ent')
			{
				attached_id = $('#help-entity').val();
			}
			else if(help_type == 'tax')
			{
				attached_id = $('#help-tax').val();
			}
			var screen_type = $('#help-screen_type').val();
			var app_id = $('input#app').val();
			var help_id = $('input#help').val();
			var check = true;
			$.ajax({
				type: 'GET',
				url: ajaxurl,
				cache: false,
				async: false, 
				data: {action:'wpas_check_help',app_id:app_id,help_id:help_id,help_type:help_type,attached_id:attached_id,screen_type:screen_type},
				success: function(response)
				{
					check = response;
				}
			});
			return check;
		}, validate_vars.check_help);

		$.validator.addMethod('checkEmail', function(value,element) {
			var check = true;
			if(value != '')
			{
				$.ajax({
					type: 'GET',
					url: ajaxurl,
					cache: false,
					async: false, 
					data: {action:'wpas_check_email',email_list:value},
					success: function(response)
					{
						check = response;
					}
				});
			}
			return check;
		}, validate_vars.check_email);

		$.validator.addMethod('uniqueName',function(val,element,params){
		var type = params[0];
		var app_id = $('#app_form input#app').val();
		var comp_id;	
		var par_id;	
		var form_id = $(element.form).attr('id');
		switch (type) {
			case 'ent':
			case 'com':
				comp_id = $('#' + form_id + ' input#ent').val();
				break;
			case 'ent_field':
				par_id = $('#' + form_id + ' input#ent').val();
				comp_id = $('#' + form_id + ' input#ent_field').val();
				break;
			case 'rel_field':
				par_id = $('#' + form_id + ' input#rel').val();
				comp_id = $('#' + form_id + ' input#rel_field').val();
				break;
			case 'rel':
				comp_id = $('#' + form_id + ' input#rel').val();
				break;	
			case 'txn':
				comp_id = $('#' + form_id + ' input#txn').val();
				break;
			case 'help_fld':
				par_id = $('#' + form_id + ' input#help').val();
				comp_id = $('#' + form_id + ' input#help_field').val();
				break;
			case 'widg':
				comp_id = $('#' + form_id + ' input#widget').val();
				break;	
			case 'form':
				comp_id = $('#' + form_id + ' input#form').val();
				break;	
			case 'shc':
				comp_id = $('#' + form_id + ' input#shc').val();
				break;
			case 'role':
				comp_id = $('#' + form_id + ' input#role').val();
				break;
		}
			
		var unique = true;
		
		$.ajax({
			type: 'GET',
			url: ajaxurl,
			cache: false,
			async: false, 
			data: {action:'wpas_check_unique',value:val,app_id:app_id,par_id:par_id,comp_id:comp_id,type:type},
			success: function(response)
			{
				unique = response;
			},
		});
		return unique; 
		}, validate_vars.check_unique);
	
		$.validator.addMethod('checkAppDash',function(value,element){
			var resp = true;
			if($('#shc-app_dash').attr('checked') && $('#shc-view_type').val() == 'integration')
			{
				var layout = $('#shc-sc_layout').val();
				if(layout.indexOf('!#shortcode[') > 0)
				{
					var app_id = $('#app_form input#app').val();
					$.ajax({
						type: 'GET',
						url: ajaxurl,
						cache: false,
						async: false,
						data: {action:'wpas_check_app_dash',layout:layout,app_id:app_id},
						success: function(response)
						{
							resp = response;
						},
					});
				}
			}
			return resp;
		},validate_vars.check_app_dash);
	
		$.validator.addMethod('checkDash',function(value,element){
			if(!$('#widg-app_dash').attr('checked') && !$('#widg-wp_dash').attr('checked'))
			{
				return false;
			}
			return true;
		}, validate_vars.check_dash);

		$('#app_form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			rules: {
			app_title: {
			minlength:3,
			maxlength:50,
			uniqueName:['app'],
			checkAppTitle:true,
			required:true,
			}
			},
			success: function(label) {
				label.addClass('valid');
			}
		});
		$('#entity-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'ent-name':{
			minlength:3,
			maxlength:16,
			noSpace:true,
			noCap:true,
			checkAlphaNumUnder: true,
			noReservedEnt:true,
			uniqueName:['ent'],
			required:true
			},
			'ent-label': {
			minlength:3,
			maxlength:50,
			required:true
			},
			'ent-singular-label': {
			minlength:3,
			maxlength:50,
			required:true
			},
			'ent-desc': {
			maxlength:300,
			},
			'ent-menu_name': {
			maxlength:50,
			},
			'ent-add_new': {
			maxlength:50,
			},
			'ent-all_items': {
			maxlength:50,
			},
			'ent-add_new_item': {
			maxlength:50,
			},
			'ent-edit_item': {
			maxlength:50,
			},
			'ent-new_item': {
			maxlength:50,
			},
			'ent-view_item': {
			maxlength:50,
			},
			'ent-search_items': {
			maxlength:50,
			},
			'ent-not_found': {
			maxlength:50,
			},
			'ent-not_found_in_trash': {
			maxlength:50,
			},
			'ent-menu_icon': {
			maxlength:255,
			url:true,
			required:true,
			},
			'ent-menu_icon_fa': {
			required:true,
			checkAlphaDashFa: true,
			},
			'ent-menu_icon_dash': {
			required:true,
			checkAlphaDash: true,
			},
			'ent-menu_icon_base64': {
			required:true,
			maxlength:10000,
			},
			'ent-top_level_page': {
			required:true,
			maxlength:50,
			},
			'ent-default_grp_title': {
			maxlength:50,
			},
			'ent-rewrite_slug':{
			maxlength:16,
			checkAlphaNum: true,
			noSpace:true,
			noCap:true,
			},
			'ent-display_idx':{
			maxlength:2,
			checkInt: true,
			},
			'ent-com_display_type':{
			required:true,
			},	
			'ent-com_name':{
			required:true,
			maxlength:20,
			noSpace:true,
			noCap:true,
			checkAlphaNumUnder: true,
			uniqueName:['com'],
			},
			'ent-com_single_label':{
			minlength:3,
			required:true,
			maxlength:50,
			},
			'ent-com_plural_label':{
			minlength:3,
			required:true,
			maxlength:50,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});

		$('#ent-field-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'fld_name':{
			minlength:3,
			maxlength:50,
			uniqueName:['ent_field'],
			noSpace:true,
			noCap:true,
			checkAlphaNumUnderDash: true,
			required:true,
			},
			'fld_label': {
			minlength:2,
			maxlength:50,
			required:true,
			},
			'fld_values': {
			maxlength: 21844,
			checkSemiCo:true,
			checkAlphaNumUnderSemiCurly: true,
			required:true,
			},
			'fld_type': {
			required:true,
			},
			'fld_file_ext': {
			checkAlphaNumComma: true,
			},
			'fld_file_size': {
			number:true,
			},
			'fld_fa_chkd_val': {
			checkAlphaDashFa: true,
			},
			'fld_fa_unchkd_val': {
			checkAlphaDashFa: true,
			},
			'fld_date_format':{
			required:true,
			},
			'fld_time_format':{
			required:true,
			},
			'fld_hidden_func':{
			required:true,
			},
			'fld_dflt_value': {
			maxlength:150,
			checkDefault: true,
			},
			'fld_desc': {
			maxlength:300,
			},
			'fld_min_value': {
			maxlength:15,
			number:true,
			},
			'fld_max_value': {
			maxlength:15,
			number:true,
			},
			'fld_min_length': {
			maxlength:5,
			checkInt: true,
			},
			'fld_max_length': {
			maxlength:5,
			checkInt: true,
			},
			'fld_min_words': {
			maxlength:5,
			checkInt: true,
			},
			'fld_max_words': {
			maxlength:5,
			checkInt: true,
			},
			'fld_max_file_uploads': {
			maxlength:5,
			checkInt: true,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#taxonomy-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'txn-name':{
			minlength:3,
			maxlength:32,
			uniqueName:['txn'],
			noReservedTxn:true,
			noSpace:true,
			noCap: true,
			checkAlphaNumUnderDash: true,
			required:true,
			},
			'txn-attach[]':{
			required:true,
			},
			'txn-label': {
			minlength:3,
			maxlength:50,
			required:true,
			},
			'txn-singular-label': {
			minlength:3,
			maxlength:50,
			required:true,
			},
			'txn-dflt_value': {
                        maxlength:150,
                        checkDefault: true,
                        },
			'txn-values': {
			maxlength:21844,
			//checkTaxChar: true,
			checkValues:true,
			},
			'txn-menu_name':{
			maxlength:50,
			},
			'txn-search_items':{
			maxlength:50,
			},
			'txn-popular_items':{
			maxlength:50,
			},
			'txn-all_items':{
			maxlength:50,
			},
			'txn-edit_item':{
			maxlength:50,
			},
			'txn-update_item':{
			maxlength:50,
			},
			'txn-add_new_item':{
			maxlength:50,
			},
			'txn-new_item_name':{
			maxlength:50,
			},
			'txn-separate_items_with_comas':{
			maxlength:50,
			},
			'txn-add_or_remove_items':{
			maxlength:50,
			},
			'txn-choose_from_most_used':{
			maxlength:50,
			},
			'txn-custom_rewrite_slug':{
			maxlength:32,
			checkAlphaNum:true,
			noCap: true,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#relationship-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'rel-name':{
			uniqueName:['rel'],
			required:true,
			minlength:3,
			maxlength:32,
			noSpace:true,
			noCap:true,
			checkAlphaNumUnder: true,
			},
			'rel-to-name':{
			required:true,
			checkRelUser:true,
			},
			'rel-from-name':{
			required:true,
			},              
			'rel-to-title':{
			maxlength:50,
			},
			'rel-from-title':{
			maxlength:50,
			},
			'rel-rel_from_layout':{
			maxlength:5000,
			},
			'rel-rel_to_layout':{
			maxlength:5000,
			},
			'rel-con_from_layout':{
			maxlength:5000,
			},
			'rel-con_to_layout':{
			maxlength:5000,
			},
			'rel-connected-display-from-title':{
			maxlength:50,
			},              
			'rel-related-display-from-title':{
			maxlength:50,
			},              
			'rel-connected-display-to-title':{
			maxlength:50,
			},              
			'rel-related-display-to-title':{
			maxlength:50,
			},
			},
			success: function(label) {
			label.addClass('valid');
			$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		}); 
		$('#rel-field-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			rel_fld_name:{
			minlength:3,
			maxlength:50,
			uniqueName:['rel_field'],
			noSpace:true,
			noCap:true,
			required:true,
			checkAlphaNumUnderDash: true,
			},
			'rel_fld_label': {
			minlength:3,
			maxlength:50,
			required:true
			},
			'rel_fld_values': {
			maxlength:21844,
			required:true,
			checkSemiCo:true,
			checkAlphaNumUnderSemiCurly: true,
			},
			'rel_fld_desc': {
			maxlength:300,
			},
			'rel_fld_dflt_value': {
			maxlength:50,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#option-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'ao_plugin_name': {
			required:true,
			maxlength:15,
			checkAlphaDash: true,
			},			
			'ao_domain':{
			required:true,
			url: true,
			maxlength:255,
			},
			'ao_blog_name':{
			maxlength:100,
			},
			'ao_app_sdesc':{
			maxlength:150,
			required:true,
			},
			'ao_app_desc':{
			maxlength:5000,
			required:true,
			},
			'ao_change_log':{
			maxlength:5000,
			required:true,
			},
			'ao_app_version':{
			checkVersion: true,
			required:true,
			maxlength:10,
			},
			'ao_author':{
			maxlength:50,
			required:true,
			},
			'ao_author_url':{
			maxlength:255,
			url: true,
			required:true,
			},
			'ao_login_logo_url':{
			maxlength:255,
			url: true,
			},
			'ao_admin_logo_url':{
			maxlength:255,
			url: true,
			},
			'ao_theme_type':{
			required:true,
			},
			'ao_left_footer_html':{
			maxlength:300,
			},
			'ao_right_footer_html':{
			maxlength:300,
			},
			'ao_mail_from_email':{
			maxlength:255,
			email:true,
			},
			'ao_mail_from_name':{
			maxlength:150,
			},
			'ao_adm_notice1_url':{
			required:true,
			url:true,
			maxlength:255,
			},
			'ao_adm_notice2_url':{
			required:true,
			url:true,
			maxlength:255,
			},
			'ao_adm_notice1_desc':{
			required:true,
			maxlength:350,
			},
			'ao_adm_notice2_desc':{
			required:true,
			maxlength:350,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#help-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'help-type':{
			required:true
			},
			'help-entity':{
			required:true,
			},
			'help-tax':{
			required:true,
			checkHelp:true,
			},
			'help-screen_type':{
			required:true,
			checkHelp:true,
			},              
			'help-screen_sidebar':{
			maxlength:1000,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#help-field-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			rules: {
			'help_fld_name':{
			minlength:3,
			maxlength:50,
			required:true,
			uniqueName:['help_fld'],
			},
			'help_fld_content':{
			required:true,
			maxlength:1000,
			}
			},
			errorPlacement: function(label, element) {
				// position error label after generated textarea
				if (element.is('textarea')) {
					label.insertAfter(element.next());
				} else {
					label.insertAfter(element)
				}
			}, 
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			 }
		});
		$('#shortcode-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'shc-label':{
			minlength:3,
			maxlength:30,
			uniqueName:['shc'],
			noSpace:true,
			noCap:true,
			checkAlphaNumUnder: true,
			required:true,
			},
			'shc-view_type':{
			required:true,
			},
			'shc-attach':{
			required:true,
			},
			'shc-attach_form':{
			required:true,
			},
			'shc-attach_tax':{
			required:true,
			},
			'shc-setup_page_title':{
			required:true,
			maxlength:255,
			},
			'shc-app_dash':{
			checkAppDash:true,
			},
			'shc-app_dash_title':{
			required:true,
			maxlength:255,
			},
			'shc-app_dash_loc':{
			required:true,
			},
			'shc-sc_layout':{
			maxlength:5000,
			required:true,
			},
			'shc-sc_css':{
			maxlength:1000,
			},
			'shc-sc_post_per_page':{
			maxlength:3,
			checkNum:true,
			},
			'shc-chart_title':{
			maxlength:255,
			},
			'shc-chart_type':{
			required:true,
			},
			'shc-haxis_type':{
			required:true,
			},
			'shc-haxis_title':{
			maxlength:255,
			},
			'shc-haxis_id':{
			required:true,
			},
			'shc-vaxis_type':{
			required:true,
			},
			'shc-vaxis_title':{
			maxlength:255,
			},
			'shc-vaxis_id':{
			required:true,
			},
			'shc-chart_height':{
			checkInt:true,
			maxlength:4,
			},
			'shc-chart_width':{
			checkInt:true,
			maxlength:4,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#notify-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'notify-name':{
			minlength:3,
			maxlength:30,
			required:true,
			noSpace:true,
			noCap:true,
			checkAlphaNumUnder: true,
			uniqueName:['notify'],
			},
			'notify-label':{
			minlength:3,
			maxlength:50,
			required:true,
			},	
			'notify-level':{
			required:true,
			},
			'notify-attached_to':{
			required:true,
			},
			'notify-events[]':{
			required:true,
			},
			'notify-email_user_confirm':{
			checkNotify:true,
			},
			'notify-email_admin_confirm':{
			checkNotify:true,
			},
			'notify-confirm_sendto[]':{
			required:true,
			},
			'notify-confirm_replyto':{
			checkEmail: true,
			},
			'notify-confirm_user_cc':{
			checkEmail: true,
			},
			'notify-confirm_user_bcc':{
			checkEmail: true,
			},
			'notify-confirm_subject':{
			required:true,
			maxlength:255,
			},
			'notify-confirm_msg':{
			required:true,
			maxlength:5000,
			},
			'notify-confirm_admin_sendto':{
			checkEmail: true,
			},
			'notify-confirm_admin_replyto':{
			checkEmail: true,
			},
			'notify-confirm_admin_cc':{
			checkEmail: true,
			},
			'notify-confirm_admin_bcc':{
			checkEmail: true,
			},
			'notify-confirm_admin_subject':{
			required:true,
			maxlength:255,
			},
			'notify-confirm_admin_msg':{
			required:true,
			maxlength:5000,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#widget-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'widg-name':{
			minlength:3,
			maxlength:30,
			required:true,
			noSpace:true,
			noCap:true,
			checkAlphaNumUnder: true,
			uniqueName:['widg'],
			},
			'widg-attach':{
			required:true,
			},
			'widg-type':{
			required:true,
			},
			'widg-dash_subtype':{
			required:true,
			},
			'widg-side_subtype':{
			required:true,
			},
			'widg-app_dash':{
			checkDash:true,
			},
			'widg-app_dash_loc':{
			required:true,
			},
			'widg-title':{
			minlength:3,
			maxlength:50,
			required:true,
			},
			'widg-label':{
			minlength:3,
			maxlength:50,
			required:true,
			},
			'widg-wdesc':{
			maxlength:150,
			},
			'widg-html':{
			maxlength:1000,
			},
			'widg-layout':{
			maxlength:5000,
			required:true,
			},
			'widg-css':{
			maxlength:1000,
			},
			'widg-post_per_page':{
			maxlength:3,
			checkNum:true,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		$('#form-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'form-name':{
			minlength:3,
			maxlength:30,
			uniqueName:['form'],
			noSpace:true,
			noCap:true,
			checkAlphaNumUnder: true,
			required:true,
			},
			'form-attached_entity':{
			required:true,
			},
			'form-form_type':{
			required:true,
			},
			'form-setup_page_title':{
			required:true,
			maxlength:255,
			},
			'form-not_loggedin_msg':{
			maxlength:5000,
			},
			'form-noresult_msg':{
			maxlength:5000,
			},
			'form-form_title':{
			maxlength:50,
			},
			'form-form_desc':{
			maxlength:5000,
			},
			'form-submit_button_label':{
			maxlength:30,
			},
			'form-disable_after':{
			maxlength:10,
			checkNum:true,
			},
			'form-confirm_txt':{
			maxlength:5000,
			},
			'form-confirm_url':{
			maxlength:255,
			url:true,
			required:true,
			},
			},
			success: function(label) {
				label.addClass('valid');
				$('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});

	
});
