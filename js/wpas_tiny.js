
tinymce.create('tinyMCE.plugins.TagListPlugin', {
    createControl: function(n, cm) {
        switch (n) {
            case 'mylistbox':
                var mlb = cm.createListBox('mylistbox', {
                     title : 'Insert tag',
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
	$.fn.fillList = function (mlb,type){
		mlb.add('WP Builtin', '',{'style':'font-style:italic;font-weight:bold;'});
		mlb.add('Title', '!#title#',{'style':'padding-left:2em;'});
		mlb.add('Permalink', '!#permalink#',{'style':'padding-left:2em;'});
		if(type != 'relationship')
		{
			mlb.add('Excerpt', '!#excerpt#',{'style':'padding-left:2em;'});
			mlb.add('Content', '!#content#',{'style':'padding-left:2em;'});
			mlb.add('Modified Date', '!#modified_date#',{'style':'padding-left:2em;'});
			mlb.add('Modified Time', '!#modified_time#',{'style':'padding-left:2em;'});
			mlb.add('Featured Img Large', '!#featured_img_large#',{'style':'padding-left:2em;'});
			mlb.add('Featured Img Medium', '!#featured_img_medium#',{'style':'padding-left:2em;'});
			mlb.add('Featured Img Thumb', '!#featured_img_thumb#',{'style':'padding-left:2em;'});
			mlb.add('Author', '!#author#',{'style':'padding-left:2em;'});
			mlb.add('Modified Author', '!#mod_author#',{'style':'padding-left:2em;'});
		}
	}
	$.fn.getAddons = function (layout_id,app_id,type,comp_name,rel_conn_type) {
		$.get(ajaxurl,{action:'wpas_get_comp_attrs', app_id:app_id,type:type,comp_name:comp_name,rel_conn_type:rel_conn_type}, function(response){
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
				if(value == comp_name || value == 'Taxonomies')
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
	$(document).on('change','#shc-attach,#widg-attach,#widg-attach-rel',function(){
		app_id = $('input#app').val();
		if($(this).attr('id') == 'shc-attach')
		{
			comp_name = $('#shc-attach :selected').val();
			layout_id= 'shc-sc_layout';
			type = "entity";
		}
		else if($(this).attr('id') == 'widg-attach')
		{
			comp_name = $('#widg-attach :selected').val();
			layout_id= 'widg-layout';
			type = "entity";
			short_code = '<table class="content-table" border=0 cellpadding=1 cellspacing=1><tbody><tr><td class="content-cell featured-image">';
			short_code += '!#featured_img_thumb#</td><td class="content-cell content-title">!#title#</td></tr><tr><td class="content-cell content-excerpt" colspan=2>!#excerpt#</td></tr></tbody></table>';
			tinymce.get('widg-layout').setContent(short_code);
		}
		else if($(this).attr('id') == 'widg-attach-rel')
		{
			comp_name = $('#widg-attach-rel :selected').val();
			layout_id= 'widg-layout';
			type = "relationship";
			$.get(ajaxurl,{action:'wpas_get_rel_conn_types',app_id:app_id,rel_name:comp_name},function(response){
				$('#widg-rel-conn-type').html(response);
			});
			tinymce.get('widg-layout').setContent('');
		}
		jQuery.fn.getAddons(layout_id,app_id,type,comp_name,'');
	});
	$(document).on('change','#widg-rel-conn-type',function(){
		comp_name = $('#widg-attach-rel :selected').val();
		conn_type = $('#widg-rel-conn-type :selected').val();
		app_id = $('input#app').val();
		jQuery.fn.getAddons('widg-layout',app_id,'relationship',comp_name,conn_type);
	});
});
