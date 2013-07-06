<?php

//for tinymce , load changes only in appstudio , check screen parent_base == wpas_app_list
add_filter( 'mce_external_languages', 'wpas_load_langs');
add_filter('mce_external_plugins','wpas_load_plugin');
add_filter('mce_buttons_2','wpas_load_buttons');
add_filter( 'tiny_mce_before_init', 'wpas_unhide_kitchensink' );


function wpas_unhide_kitchensink( $args ) {
        $screen = get_current_screen();
        if($screen->parent_base == 'wpas_app_list')
        {
                $args['wordpress_adv_hidden'] = false;
        }
        return $args;
}


function wpas_load_plugin($myplugins)
{
        $screen = get_current_screen();
        if($screen->parent_base == 'wpas_app_list')
        {
                $url = plugin_dir_url( __FILE__ ) . '../js/wpas_tiny.js';
                $table_url = plugin_dir_url( __FILE__ ) . '../js/tiny_mce/plugins/table/editor_plugin.js';

                $myplugins['taglist'] = $url ;
                $myplugins['table'] = $table_url ;
        }
        return $myplugins;
}
function wpas_load_buttons($buttons)
{
        $screen = get_current_screen();
        if($screen->parent_base == 'wpas_app_list')
        {
                $buttons[] = 'tablecontrols';
        }
        return $buttons;
}

function wpas_load_langs($langs)
{
        $screen = get_current_screen();
        if($screen->parent_base == 'wpas_app_list')
        {
                $langs['table'] =  plugin_dir_path(__FILE__) . '../js/tiny_mce/plugins/table/langs/langs.php';
        }
        return $langs;
}



?>
