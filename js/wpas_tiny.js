
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
                mlb.add('Title', '!#title#');
                mlb.add('Excerpt', '!#excerpt#');
                mlb.add('Content', '!#content#');
                mlb.add('Permalink', '!#permalink#');
                mlb.add('Modified Date', '!#modified_date#');
                mlb.add('Modified Time', '!#modified_time#');
                mlb.add('Featured Img Large', '!#featured_img_large#');
                mlb.add('Featured Img Medium', '!#featured_img_medium#');
                mlb.add('Featured Img Thumb', '!#featured_img_thumb#');
                mlb.add('Author', '!#author#');
                mlb.add('Modified Author', '!#mod_author#');

                // Return the new listbox instance
                return mlb;
        }

        return null;
    }
});

// Register plugin with a short name
tinymce.PluginManager.add('taglist', tinyMCE.plugins.TagListPlugin);

