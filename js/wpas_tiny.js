
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
		jQuery.fn.fillList(mlb);
                // Return the new listbox instance
		return mlb;
        }

        return null;
    }
});

// Register plugin with a short name
tinymce.PluginManager.add('taglist', tinyMCE.plugins.TagListPlugin);


jQuery(document).ready(function($){
	$.fn.fillList = function (mlb){
		mlb.add('WP Builtin', '',{'style':'font-style:italic;font-weight:bold;'});
		mlb.add('Title', '!#title#',{'style':'padding-left:2em;'});
		mlb.add('Excerpt', '!#excerpt#',{'style':'padding-left:2em;'});
		mlb.add('Content', '!#content#',{'style':'padding-left:2em;'});
		mlb.add('Permalink', '!#permalink#',{'style':'padding-left:2em;'});
		mlb.add('Modified Date', '!#modified_date#',{'style':'padding-left:2em;'});
		mlb.add('Modified Time', '!#modified_time#',{'style':'padding-left:2em;'});
		mlb.add('Featured Img Large', '!#featured_img_large#',{'style':'padding-left:2em;'});
		mlb.add('Featured Img Medium', '!#featured_img_medium#',{'style':'padding-left:2em;'});
		mlb.add('Featured Img Thumb', '!#featured_img_thumb#',{'style':'padding-left:2em;'});
		mlb.add('Author', '!#author#',{'style':'padding-left:2em;'});
		mlb.add('Modified Author', '!#mod_author#',{'style':'padding-left:2em;'});
	}
	$.fn.getAddons = function (layout_id,app_id,ent_name) {
		$.get(ajaxurl,{action:'wpas_get_ent_attrs', app_id:app_id,ent_name:ent_name}, function(response){
			layout = tinymce.get(layout_id);
			listbox = layout.controlManager.get('mylistbox');
			if(listbox.isMenuRendered) 
			{
				listbox.items.length =0;
				listbox.menu.destroy();
				delete listbox.isMenuRendered;
				delete listbox.menu;
				jQuery.fn.fillList(listbox);
			}
			$.each(response,function (key,value)
			{
				if(value == ent_name || value == 'Taxonomies')
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
	$(document).on('change','#shc-attach,#widg-attach',function(){
		app_id = $('input#app').val();
		if($(this).attr('id') == 'shc-attach')
		{
			ent_name = $('#shc-attach :selected').val();
			layout_id= 'shc-sc_layout';
		}
		else if($(this).attr('id') == 'widg-attach')
		{
			ent_name = $('#widg-attach :selected').val();
			layout_id= 'widg-layout';
		}
		jQuery.fn.getAddons(layout_id,app_id,ent_name);
	});
});
