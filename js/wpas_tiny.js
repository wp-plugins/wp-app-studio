
tinymce.create('tinyMCE.plugins.TagListPlugin', {
    createControl: function(n, cm) {
	ed = tinyMCE.activeEditor;
        switch (n) {
            case 'mylistbox':
                var mlb = cm.createListBox('mylistbox', {
                     title : ed.getLang('taglist.insert_tag'),
                        width : "600",
                     onselect : function(v) {
                        tinymce.get(tinymce.activeEditor.id).focus();
                        tinymce.activeEditor.execCommand('mceInsertContent', false, v);
                     }
                });

                // Add some values to the list box
		jQuery.fn.fillList(mlb,"entity");
                // Return the new listbox instance
		return mlb;
        }

        return null;
    }
});

// Register plugin with a short name
tinymce.PluginManager.add('taglist', tinyMCE.plugins.TagListPlugin);


jQuery(document).ready(function($){
	ed = tinyMCE.activeEditor;
	$.fn.fillList = function (mlb,type){
		mlb.add(ed.getLang('taglist.wp_builtin'), '',{'style':'font-style:italic;font-weight:bold;'});
		mlb.add(ed.getLang('taglist.title'), '!#title#',{'style':'padding-left:2em;'});
		mlb.add(ed.getLang('taglist.permalink'), '!#permalink#',{'style':'padding-left:2em;'});
		if(type != 'relationship')
		{
			mlb.add(ed.getLang('taglist.excerpt'), '!#excerpt#',{'style':'padding-left:2em;'});
			mlb.add(ed.getLang('taglist.content'), '!#content#',{'style':'padding-left:2em;'});
			mlb.add(ed.getLang('taglist.author'), '!#author#',{'style':'padding-left:2em;'});
		}
		if(type != 'relationship' && type != 'email')
		{
			mlb.add(ed.getLang('taglist.modified_author'), '!#mod_author#',{'style':'padding-left:2em;'});
			mlb.add(ed.getLang('taglist.modified_date'), '!#modified_date#',{'style':'padding-left:2em;'});
			mlb.add(ed.getLang('taglist.modified_time'), '!#modified_time#',{'style':'padding-left:2em;'});
			mlb.add(ed.getLang('taglist.featured_img_large'), '!#featured_img_large#',{'style':'padding-left:2em;'});
			mlb.add(ed.getLang('taglist.featured_img_medium'), '!#featured_img_medium#',{'style':'padding-left:2em;'});
			mlb.add(ed.getLang('taglist.featured_img_thumb'), '!#featured_img_thumb#',{'style':'padding-left:2em;'});
		}
	}
	$.fn.getAddons = function (layout_id,app_id,type,comp_id,rel_conn_type,rels) {
		$.get(ajaxurl,{action:'wpas_get_comp_attrs', app_id:app_id,type:type,comp_id:comp_id,rel_conn_type:rel_conn_type,rels:rels}, function(response){
			layout = tinymce.get(layout_id);
			listbox = layout.controlManager.get('mylistbox');
			listbox.items = [];
			jQuery.fn.fillList(listbox,type);
			if(listbox.isMenuRendered) 
			{
				listbox.items.length =0;
				listbox.menu.destroy();
				delete listbox.isMenuRendered;
				delete listbox.menu;
				jQuery.fn.fillList(listbox,type);
			}
			$.each(response,function (key,value)
			{
				if(value == 'Taxonomies' || key.indexOf('relattr_') == 0 || key == 0)
				{
					listbox.add(value,'',{'style':'font-style:italic;font-weight:bold;'});
				}
				else
				{
					listbox.add(value,'!#'+key+'#',{'style':'padding-left:2em;'});
				}
			});
		}, 'json');
	}
	$(document).on('change','#shc-attach,#shc-attach_form,#widg-attach,#widg-attach-rel',function(){
		app_id = $('input#app').val();
		if($(this).attr('id') == 'shc-attach_form')
		{
			comp_id = $('#shc-attach_form :selected').val();
			layout_id= 'shc-sc_layout';
			type = "form";
		}
		else if($(this).attr('id') == 'shc-attach')
		{
			comp_id = $('#shc-attach :selected').val();
			layout_id= 'shc-sc_layout';
			type = "entity";
		}
		else if($(this).attr('id') == 'widg-attach')
		{
			comp_id = $('#widg-attach :selected').val();
			layout_id= 'widg-layout';
			type = "entity";
			tinymce.get('widg-layout').setContent('');
		}
		else if($(this).attr('id') == 'widg-attach-rel')
		{
			comp_id = $('#widg-attach-rel :selected').val();
			layout_id= 'widg-layout';
			type = "relationship";
			$.get(ajaxurl,{action:'wpas_get_rel_conn_types',app_id:app_id,rel_id:comp_id},function(response){
				$('#widg-rel-conn-type').html(response);
			});
			tinymce.get('widg-layout').setContent('');
		}
		jQuery.fn.getAddons(layout_id,app_id,type,comp_id,'',[]);
	});
	$(document).on('change','#widg-rel-conn-type',function(){
		comp_id = $('#widg-attach-rel :selected').val();
		conn_type = $('#widg-rel-conn-type :selected').val();
		app_id = $('input#app').val();
		jQuery.fn.getAddons('widg-layout',app_id,'relationship',comp_id,conn_type,[]);
	});
	$(document).on('change','#form-email_user_confirm,#form-email_admin_confirm',function(){
		if($(this).attr('id') == 'form-email_user_confirm')
		{
			show = 'form-confirm_msg';
		}
		else if($(this).attr('id') == 'form-email_admin_confirm')
		{
			show = 'form-confirm_admin_msg';
		}
		comp_id = $('#form-attached_entity :selected').val();
		app_id = $('input#app').val();
		var rels = [];
		$('#form-dependents :selected').each(function() {
			rels.push($(this).val()); 
		});
		jQuery.fn.getAddons(show,app_id,'email',comp_id,'',rels);	
	});
});
