<?php

function wpas_branding_header()
{
echo '
<div id="was-container-header" class="container-fluid">
	<div class="row-fluid">
		<div id="header-branding">
		<div class="span2">
		<a title="Wp App Studio ' . __("home page","wpas") . '" target="_blank" href="//' . WPAS_URL .'"><img src="' . plugins_url('../img/WpAppStudioLogo.png',__FILE__) . '"></a>
		</div>
	        <div class="span10">
		<table border="0" cellspacing="1px" cellpadding="4px" style="background-color:#222222 !important;"><tr>
		<td><a title="' . __("Sign up for FreeDev account","wpas") . '" target="_blank" href="//' . WPAS_URL . '/wpas-freedev-signup/" class="btn btn-primary">' . __("FreeDev Signup","wpas") . '</a></td>
		<td><a title="' . __("Buy Wp App Studio created plugins and designs","wpas") . '" target="_blank" href="//emdplugins.com" class="btn btn-success">' . __("Store","wpas") . '</a></td>
		<td><a title="' . __("Create your own plugin for free","wpas") . '" target="_blank" href="//' . WPAS_URL . '/wp-app-studio-playground/" class="btn btn-danger">' . __("Playground","wpas") . '</a></td>
                <td><a title="' . __("See sample plugins created by Wp App Studio","wpas") . '" target="_blank" href="//' . WPAS_URL . '/wpas-demos/" class="btn btn-info">' . __("Demo","wpas") . '</a></td>
		<td><a title="' . __("See Wp App Studio Documentation","wpas") . '" target="_blank" href="//' . WPAS_URL . '" class="btn btn-warning">' . __("Docs","wpas") . '</a></td>
		<td><a title="' . __("See sample plugins created by Wp App Studio","wpas") . '" target="_blank" href="//support.emarketdesign.com" class="btn btn-default">' . __("Support","wpas") . '</a></td>
		</tr></table>
		</div>
		</div>
		</div>

	</div>';
}
function wpas_branding_footer()
{
	echo '<div id="was-container-footer" class="container-fluid">
		<div id="footer">
		<span><a target="_blank" href="//' . WPAS_URL . '">&copy; 2014 eMarket Design</a></span>
		<span id="footer-version" class="pull-right"> V ' . WPAS_VERSION . '</span>
		</div>
		</div>';
}
?>
