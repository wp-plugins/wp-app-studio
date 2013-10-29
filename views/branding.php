<?php

function wpas_branding_header()
{
echo '
<div id="was-container-header" class="container-fluid">
	<div class="row-fluid">
		<div id="header-branding">
		<div class="span2">
		<a title="Wp App Studio home page" target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/"><img src="//wpappstudio.s3.amazonaws.com/WpAppStudioLogo.png"></a>
		</div>
	        <div class="span5">
		<table border="0" cellspacing="1px" cellpadding="4px" style="background-color:#222222 !important;"><tr>
		<td><a title="Start here first and buy API credits" target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/wp-app-studio-pricing/" class="btn btn-primary">Buy Credits</a></td>
		<td><a title="Buy Wp App Studio created plugins and designs" target="_blank" href="//' . WPAS_URL . '/marketplace/" class="btn btn-success">Marketplace</a></td>
		<td><a title="Create your own plugin for free" target="_blank" href="//' . WPAS_URL . '/wp-app-studio-playground/" class="btn btn-danger">Free Trial</a></td>
                <td><a title="See sample plugins created by Wp App Studio" target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/wpas-demos/" class="btn btn-info">Demo</a></li></td>
		</tr></table>
		</div>
		<div class="span5">
       			<ul class="nav nav-pills pull-right">
			<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Support <b class="caret"></b></a>
			<ul class="dropdown-menu">
			  <li><a target="_blank" href="//' . WPAS_URL . '/videos/">Videos</a></li>
			  <li><a target="_blank" href="//' . WPAS_URL . '/documentation/wp-app-studio-documentation/">Documentation</a></li>
			  <li><a target="_blank" href="//' . WPAS_URL . '/automate-wordpress-development/wp-app-studio/wp-app-studio-support-pricing">Paid Support</a></li>	
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
		<div id="footer-version" class="span1 pull-right">Version '. WPAS_VERSION . '</div>
		</div>
		</div>';
}
?>
