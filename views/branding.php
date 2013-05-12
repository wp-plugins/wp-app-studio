<?php

function wpas_branding_header()
{
echo '
<div id="was-container-header" class="container-fluid">
	<div class="row-fluid">
		<div id="header-branding">
		<div class="span2">
		<a target="_blank" href="//' . WPAS_URL . '/fully-integrated-wordpress-based-solutions/wp-app-studio/"><img src="//wpappstudio.s3.amazonaws.com/WpAppStudioLogo.png"></a>
		</div>
	        <div class="span4">
		<table border="0" cellspacing="1px" cellpadding="4px" style="background-color:#222222 !important;"><tr>
		<td><a target="_blank" href="//' . WPAS_URL . '/wp-app-studio/wp-app-studio-pricing/" class="btn btn-primary btn-large">Buy Credits</a></td>
		<td><a target="_blank" href="//' . WPAS_URL . '/marketplace/" class="btn btn-success btn-large">Buy Designs</a></td>
		</tr></table>
		</div>
		<div class="span6">
       			<ul class="nav nav-pills pull-right">
    			<li><a target="_blank" href="//' . WPAS_URL . '/fully-integrated-wordpress-based-solutions/wp-app-studio/"><i class="icon-home"></i></a></li>
			<li class="dropdown">
       		   	<a class="dropdown-toggle" data-toggle="dropdown" href="#">Support <b class="caret"></b></a>
			<ul class="dropdown-menu">
                  	  <li><a target="_blank" href="//' . WPAS_URL . '/forums/forum/wp-app-studio-forums/">Forums</a></li>
			  <li><a target="_blank" href="//' . WPAS_URL . '/documentation/wp-app-studio-documentation/">Documentation</a></li>
			  <li><a target="_blank" href="//' . WPAS_URL . '/support">Paid Support</a></li>	
			</ul>
                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">About<b class="caret"></b></a>
                <ul class="dropdown-menu">
                <li><a target="_blank" href="//' . WPAS_URL . '/about">Products</a></li>
                <li><a target="_blank" href="//' . WPAS_URL . '/terms-and-conditions">Sales Policy</a></li>
                <li><a target="_blank" href="//' . WPAS_URL . '/paid-support-terms-and-conditions/">Paid Support Policy</a></li>
                <li><a target="_blank" href="//' . WPAS_URL . '/privacy-policy/">Privacy Policy</a></li>
                </ul>
                </li>
			</li>
			<li><a target="_blank" href="//demo.' . WPAS_URL . '/">Demo</a></li>
                        <li><a target="_blank" href="//' . WPAS_URL . '/wp-app-studio-screenshots/">Screenshots</a></li>
                        <li><a target="_blank" href="//' . WPAS_URL . '/fully-integrated-wordpress-based-solutions/wp-app-studio/wp-app-studio-features/">Features</a></li>
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
