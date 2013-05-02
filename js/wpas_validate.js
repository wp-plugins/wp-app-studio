jQuery(document).ready(function() {
		var unique;
		// reserved words for entity and taxonomy
		var arr_ent = ['post','page','attachment','revision','nav_menu_item'];
		var arr_txn = ['attachment','attachment_id','author','author_name','calendar','cat','category','category__and','category__in','category__not_in','category_name','comments_per_page','comments_popup','cpage','day','debug','error','exact','feed','hour','link_category','m','minute','monthnum','more','name','nav_menu','nopaging','offset','order','orderby','p','page','page_id','paged','pagename','pb','perm','post','post__in','post__not_in','post_format','post_mime_type','post_status','post_tag','post_type','posts','posts_per_archive_page','posts_per_page','preview','robots','s','search','second','sentence','showposts','static','subpost','subpost_id','tag','tag__and','tag__in','tag__not_in','tag_id','tag_slug__and','tag_slug__in','taxonomy','tb','term','type','w','withcomments','withoutcomments','year'];
		jQuery.validator.addMethod('noCap', function(value, element) { 
			if(value.match(/[A-Z]+/) != null)
			{
				return false;
			}
			return true;
		}, 'Please remove capital letters.');
		jQuery.validator.addMethod('noSpace', function(value, element) { 
			if(value.indexOf(' ') < 0 && value != '')
			{
				return true;
			}
			return false;
		}, 'Please remove spaces.');
		jQuery.validator.addMethod('noDash', function(value, element) { 
			if(value.indexOf('-') < 0 && value != '')
			{
				return true;
			}
			return false;
		}, 'Please remove dashes.');
		jQuery.validator.addMethod('checkNum', function(value, element) { 
			return this.optional(element) || /^(-1|[1-9][0-9]*)$/i.test(value);
		}, 'Must contain only integers or -1.');
		jQuery.validator.addMethod('checkInt', function(value, element) { 
			return this.optional(element) || /^[1-9][0-9]*$/i.test(value);
		}, 'Must contain only integers.');
		jQuery.validator.addMethod('checkAlphaNum', function(value, element) { 
			return this.optional(element) || /^[a-z0-9]+$/i.test(value);
		}, 'Must contain only letters or numbers.');
		jQuery.validator.addMethod('checkAlphaNumDash', function(value, element) { 
			return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
		}, 'Must contain only letters, numbers or dashes.');
		jQuery.validator.addMethod('checkAlphaNumUnder', function(value, element) { 
			return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
		}, 'Must contain only letters, numbers or underscores.');
		jQuery.validator.addMethod('checkAlphaNumUnderDash', function(value, element) { 
			return this.optional(element) || /^[a-z0-9\_\-]+$/i.test(value);
		}, 'Must contain only letters, numbers, underscores or dashes.');
		jQuery.validator.addMethod('checkDomainName', function(value, element) { 
			return this.optional(element) || /^[a-z0-9\-]+\.(com|net|org)$/i.test(value);
		}, 'Must be a valid domain name.');
		jQuery.validator.addMethod('checkVersion', function(value, element) { 
			return this.optional(element) || /^[0-9\.]+$/i.test(value);
		}, 'Must contain only numbers and dots.');
		jQuery.validator.addMethod('checkCsv', function(value, element) { 
			comma_loc = (value.length - 1);
			if(value.indexOf(',') < 0 && value != '')
			{
				return false;
			}
			else if(value.indexOf(',,') >= 0 && value != '')
			{
				return false;
			}
			else if(comma_loc == value.lastIndexOf(',') && value != '')
			{
				return false;
			}
			else
			{
				return true;
			}
		}, 'CSV format is required.');
		jQuery.validator.addMethod('noReservedEnt', function(value, element) { 
			if(jQuery.inArray(value,arr_ent) == -1)
			{
				return true;
			}
			return false;
		}, 'You cannot use reserved words.');
		jQuery.validator.addMethod('noReservedTxn', function(value, element) { 
			if(jQuery.inArray(value,arr_txn) == -1 )
			{
				return true;
			}
			return false;
		}, 'You cannot use reserved words.');
		jQuery.validator.addMethod('checkRel', function(value, element) { 
			var to_name = jQuery('select#rel-to-name').val();
			var to_title = jQuery('#rel-to-title').val();
			var from_name = jQuery('select#rel-from-name').val();
			var from_title = jQuery('#rel-from-title').val();
			var app_id = jQuery('input#app').val();
			var rel_id = jQuery('input#rel').val();
			var check = true;
			jQuery.ajax({
				type: 'GET',
				url: ajaxurl,
				cache: false,
				dataType:'JSON',
				async: false, 
				data: {action:'wpas_check_rel',app_id: app_id,rel_id:rel_id,from_name:from_name,to_name:to_name,to_title:to_title,from_title:from_title},
				success: function(response)
				{
					check = response;
				}
			});
			return check;
		}, 'Please select a different entity name.');
		jQuery.validator.addMethod('checkWidg', function(value, element) { 
			var widg_type = jQuery('#widg-type').val();
			var widg_title = jQuery('#widg-title').val();
			if(widg_type === 'sidebar')
			{
				var widg_subtype = jQuery('#widg-side_subtype').val();
			}
			else
			{
				var widg_subtype = jQuery('#widg-dash_subtype').val();
			}
				
			var app_id = jQuery('input#app').val();
			var widg_id = jQuery('input#widget').val();
			var check = true;

			jQuery.ajax({
				type: 'GET',
				url: ajaxurl,
				cache: false,
				dataType:'JSON',
				async: false, 
				data: {action:'wpas_check_widg',app_id:app_id,widg_id:widg_id,widg_type:widg_type,widg_subtype:widg_subtype,widg_title:widg_title},
				success: function(response)
				{	
					check = response;
				}
			}); 
			return check;
		}, 'Please enter a unique widget title.');
		jQuery.validator.addMethod('checkHelp', function(value, element) { 
			var object_name = jQuery('select#help-object_name').val();
			var screen_type = jQuery('select#help-screen_type').val();
			var app_id = jQuery('input#app').val();
			var help_id = jQuery('input#help').val();
			var check = true;
			jQuery.ajax({
				type: 'GET',
				url: ajaxurl,
				cache: false,
				dataType:'JSON',
				async: false, 
				data: {action:'wpas_check_help',app_id: app_id,help_id:help_id,object_name:object_name,screen_type:screen_type},
				success: function(response)
				{
					check = response;
				}
			});
			return check;
		}, 'Please select a different attach to or screen type.');

		jQuery.validator.addMethod('uniqueName',function(val,element,params){
		var type = params[0];
		var app_id = jQuery('#app_form input#app').val();
		var ent_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#ent').val();
		var fld_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#ent_field').val();
		var txn_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#txn').val();
		var rel_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#rel').val();
		var help_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#help').val();
		var help_fld_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#help_field').val();
		var rel_fld_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#rel_field').val();
		var shc_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#shc').val();
		var role_id = jQuery('#' + jQuery(element.form).attr('id') + ' input#role').val();
		var unique = true;
		
		jQuery.ajax({
			type: 'GET',
			url: ajaxurl,
			cache: false,
			dataType:'JSON',
			async: false, 
			data: {action:'wpas_check_unique',value:val, app_id: app_id, ent_id: ent_id, fld_id: fld_id, txn_id: txn_id, rel_fld_id: rel_fld_id,rel_id:rel_id, help_id: help_id, help_fld_id: help_fld_id, shc_id: shc_id, role_id: role_id, type:type},
			success: function(response)
			{
				unique = response;
			},
		});
		return unique; 
		}, 'Please enter a unique name.');

		jQuery('#app_form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			rules: {
			app_title: {
			minlength:3,
			maxlength:50,
			uniqueName:['app'],
			required:true,
			}
			},
			success: function(label) {
				label.addClass('valid');
			}
		});
		jQuery('#entity-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'ent-name':{
			minlength:3,
			maxlength:16,
			uniqueName:['ent'],
			noSpace:true,
			checkAlphaNumUnder: true,
			noReservedEnt:true,
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
			},
			'ent-menu_icon_32': {
			maxlength:255,
			url:true,
			},
			'ent-top_level_page': {
			maxlength:50,
			},
			'ent-default_grp_title': {
			maxlength:50,
			},
			'ent-rewrite_slug':{
			maxlength:16,
			checkAlphaNum: true,
			},
			},
			success: function(label) {
				 label.addClass('valid');
				 jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});

		jQuery('#ent-field-form').validate(
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
			checkAlphaNumUnderDash: true,
			required:true,
			},
			'fld_label': {
			minlength:3,
			maxlength:50,
			required:true,
			},
			'fld_values': {
			maxlength:500,
			checkCsv:true,
			required:true,
			},
			'fld_type': {
			required:true,
			},
			'fld_hidden_func':{
			required:true,
			},
			'fld_dflt_value': {
			maxlength:50,
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
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		jQuery('#taxonomy-form').validate(
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
			checkAlphaNumUnderDash: true,
			required:true,
			},
			'txn-attach':{
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
			},
			},
			success: function(label) {
				label.addClass('valid');
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		jQuery('#relationship-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			rules: {
			'rel-to-name':{
			checkRel:true,
			required:true,
			},
			'rel-from-name':{
			required:true
			},              
			'rel-to-title':{
			maxlength:50,
			},
			'rel-from-title':{
			maxlength:50,
			},              
			},
			success: function(label) {
			label.addClass('valid');
			jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		jQuery('#rel-field-form').validate(
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
			required:true,
			checkAlphaNumUnderDash: true,
			},
			'rel_fld_label': {
			minlength:3,
			maxlength:50,
			required:true
			},
			'rel_fld_values': {
			maxlength:500,
			required:true,
			checkCsv:true,
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
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		jQuery('#option-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'ao_domain':{
			required:true,
			checkDomainName: true,
			maxlength:255,
			},
			'ao_blog_name':{
			maxlength:100,
			},
			'ao_app_desc':{
			maxlength:300,
			},
			'ao_app_version':{
			checkVersion: true,
			maxlength:10,
			},
			'ao_author':{
			maxlength:50,
			},
			'ao_author_url':{
			maxlength:255,
			url: true,
			},
			'ao_login_logo_url':{
			maxlength:255,
			url: true,
			},
			'ao_admin_logo_url':{
			maxlength:255,
			url: true,
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
			},
			success: function(label) {
				label.addClass('valid');
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		jQuery('#help-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
			'help-object_name':{
			required:true
			},
			'help-screen_type':{
			checkHelp:true,
			required:true
			},              
			'help-screen_sidebar':{
			maxlength:1000,
			},
			},
			success: function(label) {
				label.addClass('valid');
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		jQuery('#help-field-form').validate(
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
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			 }
		});
		jQuery('#shortcode-form').validate(
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
			checkAlphaNumUnder: true,
			required:true,
			},
			'shc-attach':{
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
			},
			success: function(label) {
				label.addClass('valid');
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});
		jQuery('#widget-form').validate(
		{
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			ignore: ":hidden",
			rules: {
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
			'widg-title':{
			minlength:3,
			maxlength:50,
			required:true,
			checkWidg:true,
			},
			'widg-label':{
			minlength:3,
			maxlength:50,
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
				jQuery('label.valid').html('<i class=\"icon-check\"></i>');
			}
		});

	
});
