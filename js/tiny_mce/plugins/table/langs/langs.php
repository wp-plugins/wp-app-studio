<?php
$lang_file = dirname(__FILE__) . '/' .  'en_dlg.js';

if ( is_file($lang_file) && is_readable($lang_file) )
{
	$strings = @file_get_contents($lang_file);
}
else {
	//$strings = tdav_get_file(dirname(__FILE__) . '/en_dlg.js');
	$strings = @file_get_contents($lang_file);
	//$strings = preg_replace( '/([\'"])en\./', '$1'.$mce_locale.'.', $strings, 1 );
}


