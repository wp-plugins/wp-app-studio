<?php

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
{
	exit();
}

if(!current_user_can('activate_plugins'))
{
	return;
}

//delete all saved app data
$app_key_list = get_option('wpas_app_key_list');
if(!empty($app_key_list))
{
	foreach($app_key_list as $app_key)
	{
		delete_option('wpas_app_' . $app_key);
	}
}
//delete all history and default data
$options_to_delete = Array('wpas_default_entities', 'wpas_default_roles', 'wpas_passcode_email', 'wpas_apps_submit','wpas_app_key_list');

foreach($options_to_delete as $option) 
{
	delete_option($option);
}

?>
