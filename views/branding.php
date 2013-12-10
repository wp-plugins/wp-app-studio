<?php

function wpas_branding_header()
{
echo '
<div id="was-container-header" class="container-fluid">
	<div class="row-fluid">
		<div id="header-branding">
		<div class="span2">
		<a title="Wp App Studio ' . __("home page","wpas") . '" target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/"><img src="//wpappstudio.s3.amazonaws.com/WpAppStudioLogo.png"></a>
		</div>
	        <div class="span5">
		<table border="0" cellspacing="1px" cellpadding="4px" style="background-color:#222222 !important;"><tr>
		<td><a title="' . __("Start here first and buy API credits","wpas") . '" target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/wp-app-studio-pricing/" class="btn btn-primary">' . __("Buy Credits","wpas") . '</a></td>
		<td><a title="' . __("Buy Wp App Studio created plugins and designs","wpas") . '" target="_blank" href="//' . WPAS_URL . '/marketplace/" class="btn btn-success">' . __("Marketplace","wpas") . '</a></td>
		<td><a title="' . __("Create your own plugin for free","wpas") . '" target="_blank" href="//' . WPAS_URL . '/wp-app-studio-playground/" class="btn btn-danger">' . __("Free Trial","wpas") . '</a></td>
                <td><a title="' . __("See sample plugins created by Wp App Studio","wpas") . '" target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/wpas-demos/" class="btn btn-info">' . __("Demo","wpas") . '</a></li></td>
		</tr></table>
		</div>
		<div class="span5">
       			<ul class="nav nav-pills pull-right">
			<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">' . __("Support","wpas") . ' <b class="caret"></b></a>
			<ul class="dropdown-menu">
			  <li><a target="_blank" href="//' . WPAS_URL . '/videos/">' . __("Videos","wpas") . '</a></li>
			  <li><a target="_blank" href="//' . WPAS_URL . '/documentation/wp-app-studio-documentation/">' . __("Documentation","wpas") . '</a></li>
			  <li><a target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/wp-app-studio-support-pricing">' . __("Paid Support","wpas") . '</a></li>	
			</ul>
			</li>
			</ul>
		</div>
		</div>
		</div>

	</div>';
}
function wpas_branding_footer()
{
	echo '<div id="was-container-footer" class="container-fluid">
		<div class="row-fluid">
		<div id="footer" class="span11">
		<ul class="nav nav-pills">
		<li><a target="_blank" href="//' . WPAS_URL . '">&copy; 2013 eMarket Design</a></li>
		</ul>
		</div>
		<div id="footer-version" class="span1 pull-right"> V ' . WPAS_VERSION . '</div>
		</div>
		</div>';
}
?>
