<?php

function wpas_branding_header()
{
$product_name = 'WP App Studio';
$product_uri = 'goo.gl/3Q9Xzy';
$product_desc = 'WP App Studio is the Visual WordPress Design and Development Platform for building great apps in the form of plugins.';
$product_image = plugins_url('../img/wp_app_studio_autobahn.jpg',__FILE__);
$email_body="WP App Studio is a Visual WordPress Design and Development Platform for building powerful apps in the form of WordPress plugins. No coding experience is required.";
?>
<div id="was-container-header" class="container-fluid">
	<div class="row-fluid">
		<div id="header-branding">
		<div class="span2">
		<a title="Wp App Studio <?php _e("home page","wpas") ?>" target="_blank" href="//<?php echo WPAS_URL; ?>">
		<img src="<?php echo plugins_url('../img/WpAppStudioLogo.png',__FILE__); ?>"></a>
		</div>
	        <div class="span5">
		<table border="0" cellspacing="1px" cellpadding="4px" style="background-color:#222222 !important;"><tr>
		<td><a title="<?php _e("Buy Prodev Now!","wpas"); ?>" target="_blank" href="//<?php echo WPAS_URL . '/downloads/pro-dev-app/'; ?>" class="btn btn-danger btn-mini"><?php _e("Buy ProDev Now!","wpas"); ?></a></td>
		<td><a title="<?php _e("Sign up for FreeDev account","wpas"); ?>" target="_blank" href="//<?php echo WPAS_URL . '/wpas-freedev-signup/'; ?>" class="btn btn-primary btn-mini"><?php _e("FreeDev Signup","wpas"); ?></a></td>
		<td><a title="<?php _e("Buy Wp App Studio created plugins and designs","wpas"); ?>" target="_blank" href="//emdplugins.com" class="btn btn-success btn-mini"><?php _e("Store","wpas"); ?></a></td>
		</tr>
		</table>
		</div>
		<div class="span5">
		<div style="color:red;font-weight:700;">Share this delight with others! <i class="icon-arrow-right"></i> </div>
		<ul class="list-inline socialize pull-right">
		<li><a href="javascript:twitterShare('http://<?php echo $product_uri; ?>','<?php echo $product_desc; ?>', 602,496);" data-lang="en"><img src="<?php echo plugins_url('../img/twitter_icon.jpg',__FILE__); ?>" alt="Share on Twitter" /></a></li>
		<li><a href="" onclick="javascript:fbShare('<?php echo $product_uri; ?>','<?php echo $product_name; ?>','<?php echo $product_desc; ?>', 600, 400);return false;" target="_blank"><img src="<?php echo  plugins_url('../img/fb_icon.jpg',__FILE__); ?>" alt="Share on Facebook" /></a></li>
		<li><a href="javascript:gplusShare('<?php echo $product_uri; ?>', 483, 540)"><img src="<?php echo plugins_url('../img/gplus_icon.jpg',__FILE__); ?>" alt="Share on Google+"/></a></li>
		<li><a href="http://www.tumblr.com/share/link?url=<?php echo $product_uri;?>&amp;name=<?php echo $product_name;?>&amp;description=<?php echo $product_desc; ?>" title="Share on Tumblr" target="_blank"><img src="<?php echo plugins_url('../img/tumblr_icon.jpg',__FILE__); ?>" alt="Share on Tumblr"/></a></li>
		<li><a href="javascript:pinterestShare('<?php echo $product_uri; ?>', '<?php echo $product_image; ?>', '<?php echo $product_desc; ?>', 774, 452)" data-pin-do="buttonPin" ><img src="<?php echo plugins_url('../img/pinterest_icon.jpg',__FILE__); ?>" alt="Share on Pinterest"/></a></li>
		<li><a href="javascript:stumbleuponShare('<?php echo $product_uri; ?>', 802, 592)"><img src="<?php echo plugins_url('../img/stumbleupon_icon.jpg',__FILE__); ?>" alt="Share on Stumbleupon"/></a></li>
		<li><a href="javascript:linkedinShare('<?php echo $product_uri; ?>', '<?php echo $product_name; ?>', '<?php echo $product_desc; ?>', 850, 450)"><img src="<?php echo plugins_url('../img/linkedin_icon.jpg',__FILE__);?>" alt="Share on LinkedIn"/></a></li>
		<li><a href="javascript:redditShare('<?php echo $product_uri;?>', 800, 400)"><img src="<?php echo plugins_url('../img/reddit_icon.jpg',__FILE__); ?>" alt="Share on Reddit"/></a></li>
		<li><a href="mailto:?subject=<?php echo $product_name; ?>&amp;body=<?php echo $email_body . "%0D%0A%0D%0A http://" . $product_uri;?>"><img src="<?php echo plugins_url('../img/email_icon.jpg',__FILE__); ?>" alt="Email to a friend"/></a></li>
		</ul>
		</div>
		</div>
		</div>
	</div>
<?php
}
function wpas_branding_footer()
{
	echo '<div id="was-container-footer" class="container-fluid">
		<div id="footer">
		<a target="_blank" href="//' . WPAS_URL . '">&copy; 2014 eMarket Design</a>
		<span class="wpas-links">
		<a class="btn btn-inverse btn-mini" title="' . __("Create your own plugin for free","wpas") . '" target="_blank" href="//' . WPAS_URL . '/wp-app-studio-playground/">' . __("Playground","wpas") . '</a>
		<a class="btn btn-danger btn-mini" title="' . __("See sample plugins created by Wp App Studio","wpas") . '" target="_blank" href="//' . WPAS_URL . '/wpas-demos/" >' . __("Demo","wpas") . '</a>
		<a class="btn btn-warning btn-mini" title="' . __("See Wp App Studio Documentation","wpas") . '" target="_blank" href="//' . WPAS_URL . '/articles">' . __("Docs","wpas") . '</a>
		<a class="btn btn-default btn-mini" title="' . __("Open a free support ticket","wpas") . '" target="_blank" href="//support.emarketdesign.com">' . __("Support","wpas") . '</a>
		</span>
		<span id="footer-version" class="pull-right"> V ' . WPAS_VERSION . '</span>
		</div>
		</div>';
}
?>
