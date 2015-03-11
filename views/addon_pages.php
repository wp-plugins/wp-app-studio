<?php
/**
 * Store/Design Page Functions
 *
 * @package     EMD
 * @copyright   Copyright (c) 2014,  Emarket Design
 * @since       WPAS 4.3
 */
if (!defined('ABSPATH')) exit;
/**
 * Show emdplugins plugins and extensions
 *
 * @since WPAS 4.3
 *
 * @return html page content
 */
function wpas_store_page() {
	global $title;
	ob_start(); ?>
		<div class="wrap">
		<h2><?php echo $title;?> &nbsp;&mdash;&nbsp;<a href="https://emdplugins.com/plugins?pk_source=wpas-store-page&pk_medium=plugin&pk_campaign=wpas-store&pk_content=browseall" class="button-primary" title="<?php _e( 'Browse All', 'wpas' ); ?>" target="_blank"><?php _e( 'Browse All', 'wpas' ); ?></a>
		</h2>
		<p><?php _e('The following plugins extend and expand the functionality of your app.','wpas'); ?></p>
		<?php echo wpas_add_ons('addons'); ?>
		</div>
		<?php
		echo ob_get_clean();
}
/**
 * Show wpas designs
 *
 * @since WPAS 4.3
 *
 * @return html page content
 */
function wpas_design_page() {
	global $title;
	ob_start(); ?>
		<div class="wrap">
		<h2><?php echo $title;?> &nbsp;&mdash;&nbsp;<a href="https://emdplugins.com/designs?pk_source=wpas-design-page&pk_medium=plugin&pk_campaign=wpas-design&pk_content=browseall" class="button-primary" title="<?php _e( 'Browse All', 'wpas' ); ?>" target="_blank"><?php _e( 'Browse All', 'wpas' ); ?></a>
		</h2>
		<p><?php printf(__('The following <a href="%s" title="WP App Studio Prodev" target="_blank">WP App Studio Prodev</a> plugin designs can be used as a template:','wpas'),'https://wpas.emdplugins.com?pk_source=wpas-design-page&pk_medium=plugin&pk_campaign=wpas-design&pk_content=prodevlink');?>
		<ul><li><span class="dashicons dashicons-yes"></span>
		<?php _e('To customize the functionality of their corresponding plugins','wpas'); ?>
		</li>
		<li><span class="dashicons dashicons-yes"></span>
		<?php _e('To create your own plugin','wpas');?>
		</li>
		</ul>
		</p>
		<?php echo wpas_add_ons('plugin-designs'); ?>
		</div>
		<?php
		echo ob_get_clean();
}
/**
 * Get plugin and extension list from emdplugins site and save it in a transient
 *
 * @since WPAS 4.3
 *
 * @return $cache html content
 */
function wpas_add_ons($type) {
	//if ( false === ( $cache = get_transient( 'emd_store_feed' ) ) ) {
	$feed = wp_remote_get( 'https://emd-plugin-site.s3.amazonaws.com/' . $type . '.html');
	if ( ! is_wp_error( $feed ) ) {
		if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
			$cache = wp_remote_retrieve_body( $feed );
			//set_transient( 'emd_store_feed', $cache, 3600 );
		}
	} else {
		$cache = '<div class="error"><p>' . __( 'There was an error retrieving the extensions list from the server. Please try again later.', 'wpas' ) . '</div>';
	}
	//}
	return $cache;
}
function wpas_support_page(){
	global $title;
	ob_start(); ?>
		<div class="wrap">
		<h2><?php echo $title;?></h2>
		<div id="support-header"><?php _e('Thanks for installing WP APP Studio.','wpas');?> &nbsp; <?php  printf(__('All support requests are accepted through <a href="%s" target="_blank">our support site.</a>','wpas'),'https://support.emarketdesign.com?pk_source=wpas-support-page&pk_medium=plugin&pk_campaign=wpas-support&pk_content=supportlink'); ?>
		<div id="plugin-review">
		<div class="plugin-review-text"><a href="https://wordpress.org/support/view/plugin-reviews/wp-app-studio" target="_blank"><?php _e('Like our plugin? Leave us a review','wpas'); ?></a>
		</div><div class="plugin-review-star"><span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		</div>
		</div>
		</div>
		<?php echo wpas_add_ons('plugin-support'); ?>
		</div>
		<?php
		echo ob_get_clean();
}
function wpas_debug_page(){
	global $title,$wpdb;
	if ( get_bloginfo( 'version' ) < '3.4' ) {
		$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
		$theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
	} else {
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;
	}

	$args = array(
		'sslverify'  => false,
		'timeout'   => 15,
	);

	$response = wp_remote_post(WPAS_SSL_URL,$args);
	if( !is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
		$wpas_remote = 'Works';
	} else {
		if(is_wp_error( $response )){
			$wpas_remote = "WP Error Msg -- " . $response->get_error_message();
		}
		else {
			$wpas_remote = "Returned -- " . $response['response']['message'];
		}
	}
	ob_start(); ?>
		<div class="wrap">
		<h2><?php echo $title;?></h2>
		<div id="wpas-debug" name="wpas-debug">
		<p>WPAS VERSION: <?php echo WPAS_VERSION;?></p>
		<p>WPAS DATA VERSION: <?php echo WPAS_DATA_VERSION;?></p>
		<p>SITE_URL: <?php echo site_url(); ?></p>
		<p>HOME_URL: <?php echo home_url(); ?></p>
		<p>Multisite: <?php echo is_multisite() ? 'Yes' : 'No' ?> </p>
		<p>WordPress Version: <?php echo get_bloginfo( 'version' ); ?></p>
		<p>Language: <?php echo ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ); ?></p>
		<p>Permalink Structure: <?php echo get_option( 'permalink_structure' );?> </p>
		<p>Active Theme: <?php echo $theme; ?></p>
		<p>WP_DEBUG: <?php echo ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ); ?></p>
		<p>Remote Post: <?php echo $wpas_remote; ?></p>
		<p>PHP Version:              <?php echo PHP_VERSION; ?>
		<p>MySQL Version:            <?php echo $wpdb->db_version(); ?></p>
		<p>Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
		<p>PHP Post Max Size:        <?php echo ini_get( 'post_max_size' ); ?></p>
		<p>PHP Upload Max Filesize:  <?php echo ini_get( 'upload_max_filesize' ); ?></p>
		<p>cURL:                     <?php echo ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ); ?></p>

		<?php // Must-use plugins
		$muplugins = get_mu_plugins();
	if( !empty($muplugins) ) {
		echo '<p>Must-Use Plugins:<br>';
		foreach( $muplugins as $plugin => $plugin_data ) {
			echo  $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "<br>";
		}
		echo '</p>';
	}
	// WordPress active plugins
	echo  '<p>WordPress Active Plugins:<br>';

	$plugins = get_plugins();
	$active_plugins = get_option( 'active_plugins', array() );

	foreach( $plugins as $plugin_path => $plugin ) {
		if( !in_array( $plugin_path, $active_plugins ) )
			continue;
		echo $plugin['Name'] . ': ' . $plugin['Version'] . "<br>";
	}
	echo '</p>';

	if( is_multisite() ) {
		// WordPress Multisite active plugins
		echo '<p>Network Active Plugins:<br>';
		$plugins = wp_get_active_network_plugins();
		$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

		foreach( $plugins as $plugin_path ) {
			$plugin_base = plugin_basename( $plugin_path );

			if( !array_key_exists( $plugin_base, $active_plugins ) )
				continue;

			$plugin  = get_plugin_data( $plugin_path );
			echo $plugin['Name'] . ': ' . $plugin['Version'] . "<br>";
		}
		echo '</p>';
	}
	?>
	</div>
	<?php
	echo ob_get_clean();
}
