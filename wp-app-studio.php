<?php
defined( 'ABSPATH' ) OR exit;
/*
   Plugin Name: Wp App Studio
   Plugin URI: http://emarketdesign.com
   Description: Wp App Studio is a simple to use WordPress plugin which enables web designers, business users as well as bloggers to create wordpress based fully featured web and mobile apps without any coding.
   Version: 1.1.0
   Author: eMarket Design LLC
   Author URI: http://emarketdesign.com
   License: GPLv2 or later
*/
register_activation_hook( __FILE__, 'wpas_activate' );
register_deactivation_hook( __FILE__, 'wpas_deactivate' );

define('WPAS_URL', "emarketdesign.com");
define('WPAS_SSL_URL', "https://www.emarketdesign.com");
define('WPAS_VERSION', "1.1.0");

function wpas_activate () 
{
      if(!current_user_can('activate_plugins'))
      {
		return;
      }
      $default_entities = get_option('wpas_default_entities');
      $default_roles = get_option('wpas_default_roles');
      if(empty($default_entities))
      {
		$post_page[0] = Array('ent-name'=> 'post',
				      'ent-label' => 'Posts',
				      'ent-singular-label' => 'Post',
					'ent-hierarchical' => 0);
		$post_page[1] = Array('ent-name' => 'page',
				      'ent-label' => 'Pages',
				      'ent-singular-label' => 'Page',
					'ent-hierarchical' => 1);
		$default_entities = $post_page;	
				
                update_option('wpas_default_entities',$default_entities);
      }
      if(empty($default_roles))
      {
		$roles[0] = Array('role-name'=> 'administrator',
				      'role-label' => 'Administrator',
					'role-edit_others_posts' => 1,
					'role-edit_private_posts' => 1,
					'role-read_private_posts' => 1,
					'role-delete_others_posts' => 1,
					'role-delete_private_posts' => 1,
					'role-edit_published_posts' => 1,
					'role-publish_posts' => 1,
					'role-delete_published_posts' => 1,
					'role-edit_posts' => 1,
					'role-delete_posts' => 1,
					'role-manage_operations_posts' => 1,
					'role-edit_pages' => 1,
					'role-edit_others_pages' => 1,
					'role-edit_published_pages' => 1,
					'role-publish_pages' => 1,
					'role-delete_pages' => 1,
					'role-delete_others_pages' => 1,
					'role-delete_published_pages' => 1,
					'role-delete_private_pages' => 1,
					'role-edit_private_pages' => 1,
					'role-read_private_pages' => 1,
					'role-manage_operations_pages' => 1,
					'role-activate_plugins' => 1,
					'role-add_users' => 1,
					'role-create_users' => 1,
					'role-delete_plugins' => 1,
					'role-delete_users' => 1,
					'role-edit_dashboard' => 1,
					'role-edit_files' => 1,
					'role-edit_plugins' => 1,
					'role-edit_theme_options' => 1,
					'role-edit_themes' => 1,
					'role-edit_users' => 1,
					'role-import' => 1,
					'role-install_plugins' => 1,
					'role-install_themes' => 1,
					'role-list_users' => 1,
					'role-manage_categories' => 1,
					'role-manage_links' => 1,
					'role-manage_options' => 1,
					'role-moderate_comments' => 1,
					'role-promote_users' => 1,
					'role-read' => 1,
					'role-remove_users' => 1,
					'role-switch_themes' => 1,
					'role-unfiltered_html' => 1,
					'role-unfiltered_upload' => 1,
					'role-update_core' => 1,
					'role-update_plugins' => 1,
					'role-update_themes' => 1,
					'role-upload_files' => 1,
				);
		$roles[1] = Array('role-name'=> 'editor',
				      'role-label' => 'Editor',
					'role-edit_others_posts' => 1,
					'role-edit_private_posts' => 1,
					'role-read_private_posts' => 1,
					'role-delete_others_posts' => 1,
					'role-delete_private_posts' => 1,
					'role-edit_published_posts' => 1,
					'role-publish_posts' => 1,
					'role-delete_published_posts' => 1,
					'role-edit_posts' => 1,
					'role-delete_posts' => 1,
					'role-edit_pages' => 1,
					'role-edit_others_pages' => 1,
					'role-edit_published_pages' => 1,
					'role-publish_pages' => 1,
					'role-delete_pages' => 1,
					'role-delete_others_pages' => 1,
					'role-delete_published_pages' => 1,
					'role-delete_private_pages' => 1,
					'role-edit_private_pages' => 1,
					'role-read_private_pages' => 1,
					'role-moderate_comments' => 1,
					'role-manage_categories' => 1,
					'role-manage_links' => 1,
					'role-upload_files' => 1,
					'role-read' => 1,
				);
		$roles[2] = Array('role-name'=> 'author',
				  'role-label' => 'Author',
				  'role-edit_published_posts' => 1,
				  'role-publish_posts' => 1,
				  'role-delete_published_posts' => 1,
				  'role-edit_posts' => 1,
				  'role-delete_posts' => 1,
				  'role-upload_files' => 1,
				  'role-read' => 1,
				);
		$roles[3] = Array('role-name'=> 'contributor',
				  'role-label' => 'Contributor',
				  'role-edit_posts' => 1,
				  'role-delete_posts' => 1,
				  'role-read' => 1,
				);
		$roles[4] = Array('role-name'=> 'subscriber',
				  'role-label' => 'Subscriber',
				  'role-read' => 1,
				);
		$default_roles = $roles;	
				
                update_option('wpas_default_roles',$default_roles);
      }

}
function wpas_deactivate ()
{
//do nothing for now , deletes of options are done in uninstall, delete of plugin
}

require_once("lib/wpas_wysiwyg_fields.php");
require_once("lib/wpas_operations.php");
require_once("lib/wpas_ajax_funcs.php");
require_once("views/application_form.php");
require_once("views/entity_form.php");
require_once("views/entity_layout_form.php");
require_once("views/entity_field_form.php");
require_once("views/taxonomy_form.php");
require_once("views/settings_form.php");
require_once("views/relationship_form.php");
require_once("views/help_form.php");
require_once("views/relationship_field_form.php");
require_once("views/help_tab_form.php");
require_once("views/branding.php");
require_once("views/generate_form.php");
require_once("views/import_form.php");
require_once("views/shortcode_form.php");
require_once("views/widget_form.php");
require_once("views/role_form.php");

load_plugin_textdomain( 'wpas', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

add_action('admin_menu', 'wpas_plugin_menu');
add_action( 'admin_init', 'wpas_update' );


function wpas_plugin_menu() {
	global $hook_app_list, $hook_app_new;
	$icon_url = plugin_dir_url( __FILE__ ) . "img/wpas-icon.png";
	
	$hook_app_list = add_menu_page('WP App Studio', __('WP App Studio','wpas'), 'administrator', 'wpas_app_list', 'wpas_app_list',$icon_url);
	$hook_app_new = add_submenu_page( 'wpas_app_list', __('Add New App','wpas'), __('Add New App','wpas'), 'administrator', 'wpas_add_new_app', 'wpas_add_new_app');
	add_action( 'admin_enqueue_scripts', 'wpas_enqueue_scripts' );

}
function wpas_enqueue_scripts($hook_suffix){
	global $hook_app_list, $hook_app_new;
	if($hook_suffix == $hook_app_list || $hook_suffix == $hook_app_new)
	{
		$local_vars = array();
		$local_vars['nonce_update_field_order'] = wp_create_nonce( 'wpas_update_field_order_nonce' );
		$local_vars['nonce_delete_field'] = wp_create_nonce( 'wpas_delete_field_nonce' );
		$local_vars['nonce_delete'] = wp_create_nonce( 'wpas_delete_nonce' );
		$local_vars['nonce_save_form'] = wp_create_nonce( 'wpas_save_form_nonce' );
		$local_vars['nonce_save_option_form'] = wp_create_nonce( 'wpas_save_option_form_nonce' );
		$local_vars['nonce_update_form'] = wp_create_nonce( 'wpas_update_form_nonce' );
		$local_vars['nonce_save_field'] = wp_create_nonce( 'wpas_save_field_nonce' );
		$local_vars['nonce_check_status_generate'] = wp_create_nonce( 'wpas_check_status_generate_nonce' );
		$local_vars['nonce_save_layout'] = wp_create_nonce( 'wpas_save_layout_nonce' );

		wp_register_style( 'wpas-admin-css', plugin_dir_url( __FILE__ ) . 'css/wpas-admin.css' );
		wp_enqueue_style( 'wpas-admin-css' );
		wp_register_style( 'wpas-core', plugin_dir_url( __FILE__ ) . 'css/wpas-core.css');
		wp_enqueue_style( 'wpas-core' );
		wp_register_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css');
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style("wp-jquery-ui-draggable");
		wp_enqueue_style("wp-jquery-ui-droppable");
		wp_enqueue_style("wp-jquery-ui-sortable");


		wp_register_script( 'bootstrap-min-js',plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js');
		wp_enqueue_script( 'bootstrap-min-js');

		wp_register_script( 'jquery-validate-js',plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js');
		wp_enqueue_script( 'jquery-validate-js');
		wp_enqueue_script("jquery-ui-draggable");
		wp_enqueue_script("jquery-ui-droppable");
		wp_enqueue_script("jquery-ui-sortable");
		wp_enqueue_script('jquery-ui-accordion');
		wp_register_script( 'wpas-js',plugin_dir_url( __FILE__ ) . 'js/wpas.js',array(),false,true);
		wp_enqueue_script( 'wpas-js');
		wp_register_script( 'wpas-layout-js',plugin_dir_url( __FILE__ ) . 'js/wpas_layout.js',array(),false,true);
		wp_enqueue_script( 'wpas-layout-js');
		wp_register_script( 'wpas-paging-js',plugin_dir_url( __FILE__ ) . 'js/wpas_paging.js',array(),false,true);
		wp_enqueue_script( 'wpas-paging-js');
		wp_register_script( 'wpas-validate-js',plugin_dir_url( __FILE__ ) . 'js/wpas_validate.js',array(),false,true);
		wp_enqueue_script( 'wpas-validate-js');

		wp_localize_script('wpas-js','wpas_vars',$local_vars);
	}
}
function wpas_app_list()
{
	wpas_is_allowed();
	if(isset($_GET['generate']) && $_GET['generate'] == 1)
	{
		check_admin_referer('wpas_generate');
		wpas_generate_app();
	}
	elseif(isset($_GET['import']) && $_GET['import'] == 1)
	{
		check_admin_referer('wpas_import');
		$apps_unserialized = wpas_get_app_list();
		wpas_import_app($apps_unserialized);
	}
	else
	{	
		$apps_unserialized = wpas_get_app_list();
		echo '<div class="wpas">';
		wpas_branding_header();
		echo '<div id="was-container" class="container-fluid">';
		wpas_modal_confirm_delete();
		echo "<div class=\"row-fluid\"><div id=\"was-applist\" class=\"span12\">
			<div id=\"list_apps\" style=\"display: block;\">";
		echo wpas_list("app",$apps_unserialized,0,"",1);
		echo "</div>";
		echo "</div></div></div>"; 
		wpas_branding_footer();
		echo "</div>";
	}
}

function wpas_update()
{
	if(isset($_POST['Save']) && $_POST['Save'] == 'Rename')
	{
		wpas_is_allowed();
		check_admin_referer('wpas_rename_app_nonce');

		$app_key = sanitize_text_field($_POST['app']);
		$app = wpas_get_app($app_key);
		$app_name = sanitize_text_field(stripslashes($_POST['app_title']));
		
		if(!empty($app) && !empty($app_name) && $app['app_name'] != $app_name)
		{
			$app['app_name'] = $app_name;
			wpas_update_app($app,$app_key);
		}
		$return_page = admin_url('admin.php?page=wpas_app_list');
		wp_redirect($return_page);
	}
}

function wpas_add_new_app()
{
	$app = Array();
	if(isset($_REQUEST['app']))
	{
		$app_key = sanitize_text_field($_REQUEST['app']);
		$app = wpas_get_app($app_key);	
	}
	else
	{	
		//generate key
		$app_key = uniqid('',true);
	}

	//saving an app	
	if(isset($_POST['type']) && $_POST['type'] == 'app' && $_POST['Save'] == "Save")
	{
		check_admin_referer('wpas_save_app_nonce');
		//save application
		$app_name = sanitize_text_field(stripslashes($_POST['app_title']));
		$app['app_name']= $app_name;
		$app['app_id']= $app_key;

		if(!empty($app_name) && !empty($app_key))
		{
			$app = wpas_set_default_ent_roles($app);	
			wpas_update_app($app,$app_key,'new');
			wpas_show_page($app,"edit_app");
		}
		else
		{
			wpas_show_page($app,"new");
		}
			
	}
	elseif(isset($_GET['edit']))
	{
		check_admin_referer('wpas_edit_app_nonce');
		wpas_show_page($app,"edit_app");
	}
	else
	{
		$app['app_id'] = $app_key;
		wpas_show_page($app,"new");
	}
}

function wpas_show_page($app,$page)
{
	$app_name = "";
	$app_key = $app['app_id'];
	if($page == "edit_app")
	{
		$app_name = $app['app_name'];
		wpas_breadcrumb("edit_app");
		wpas_add_app_form("Rename",$app_key,$app_name,"block;");
		if(isset($app['option']))
		{
			wpas_nav($app_name,$app['option']);
		}
		else
		{
			wpas_nav($app_name);
		}
		echo "<div id=\"was-editor\" class=\"span9\">";
		echo "<div id=\"loading\" class=\"group1\" style=\"display: none;\">Please wait ...</div>";
		wpas_modal_confirm_delete();
		echo "<div id=\"list-entity\" class=\"group1\" style=\"display: block;\">";
		echo wpas_list('entity',$app['entity'],$app_key,$app_name,1);
		echo "</div>";
		echo "<div id=\"list-ent-fields\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"add-entity-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_entity_form();
		echo "</div>";
		echo "<div id=\"edit-layout-div\" class=\"group1 container-fluid\" style=\"display: none;\">";
		wpas_entity_layout_form();
		echo "</div>";
		echo "<div id=\"add-ent-field-div\" class=\"group1\" style=\"display: none;\">";
		echo "<form action=\"\" method=\"post\" id=\"ent-field-form\" class=\"form-horizontal\">";
		wpas_add_ent_field_form($app_key,0);
		echo "</form>";
		echo "</div>";
		echo "<div id=\"add-taxonomy-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_taxonomy_form();
		echo "</div>";
		echo "<div id=\"list-taxonomy\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"list-relationship\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"list-rel-fields\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"add-relationship-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_relationship_form($app_key);
		echo "</div>";
		echo "<div id=\"add-rel-field-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_rel_field_form($app_key);
		echo "</div>";
		echo "<div id=\"add-shortcode-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_shortcode_form($app_key);
		echo "</div>";
		echo "<div id=\"list-shortcode\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"add-widget-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_widget_form($app_key);
		echo "</div>";
		echo "<div id=\"list-widget\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"list-form\" class=\"group1\" style=\"display: none;\">";
		wpas_form_desc();
		echo "</div>";
		echo "<div id=\"add-form-div\" class=\"group1\" style=\"display: none;\">";
		wpas_form_desc();
		echo "</div>";
		echo "<div id=\"list-help\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"list-help-fields\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"add-help-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_help_form($app_key);
		echo "<div id=\"add-help-field-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_help_tab_form($app_key);
		echo "</div>";
		echo "<div id=\"list-pointer\" class=\"group1\" style=\"display: none;\">";
		wpas_pointer_desc();
		echo "</div>";
		echo "<div id=\"add-pointer-div\" class=\"group1\" style=\"display: none;\">";
		wpas_pointer_desc();
		echo "</div>";
		echo "<div id=\"list-role\" class=\"group1\" style=\"display: none;\">";
		echo "</div>";
		echo "<div id=\"add-role-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_role_form($app_key,'');
		echo "</div>";
		echo "<div id=\"add-option-div\" class=\"group1\" style=\"display:none;\">";
		wpas_add_app_option();
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>"; //for wpas 
	}
	else
	{
		wp_register_script( 'wpas-fade',plugin_dir_url( __FILE__ ) . 'js/wpas_fade.js');
		wp_enqueue_script( 'wpas-fade');
		wpas_breadcrumb("add_new_app");
		wpas_add_app_form("Save",$app_key,"","block;");
		wpas_nav($app_name);
		echo "<div id=\"was-editor\" class=\"span9\">";
		echo "<div id=\"list-entity\" class=\"group1\" style=\"display: block;\">";
		echo wpas_list('entity',Array(),$app_key,$app_name,1);
		echo "</div>"; //for was-editor 
		echo "</div></div>"; 
		echo "</div>"; //for wpas 
	}
	wpas_branding_footer();
	echo "</div>"; 
}
function wpas_modal_confirm_delete()
{
	echo "<div class=\"modal hide\" id=\"confirmdeleteModal\">
                <div class=\"modal-header\">
                <button id=\"delete-close\" type=\"button\" class=\"close\" data-dismiss=\"confirmdeleteModal\" aria-hidden=\"true\">x</button>
                <h3><i class=\"icon-trash icon-red\"></i>Delete</h3>
                </div>
                <div class=\"modal-body\" style=\"clear:both\">Are you sure you wish to delete?
                </div>
                <div class=\"modal-footer\">
                <button id=\"delete-cancel\" class=\"btn btn-danger\" data-dismiss=\"confirmdeleteModal\" aria-hidden=\"true\">Cancel</button> 
                <button id=\"delete-ok\" data-dismiss=\"confirmdeleteModal\" aria-hidden=\"true\" class=\"btn btn-primary\">OK</button>
                </div>
                </div>";
}

function wpas_set_default_ent_roles($app)
{
	$now = date('Y-m-d H:i:s');
	$app['date']= $now;
	$app['modified_date']= $now;
	$default_entities = get_option('wpas_default_entities');
	if(!empty($default_entities))
	{
		foreach($default_entities as $myentity)
		{
			$no_insert = 0;
			if(!empty($app['entity']))
			{
				foreach($app['entity'] as $ents_created)
				{
					if($myentity['ent-name'] == $ents_created['ent-name'])
					{
						$no_insert = 1;
					}
				}
			}
			if($no_insert == 0)
			{
				$myentity['modified_date'] = $now;
				$myentity['date'] = $now;
				$app['entity'][] = $myentity;
			}
		}
	}
	$default_roles = get_option('wpas_default_roles');
	if(!empty($default_roles))
	{
		foreach($default_roles as $myrole)
		{
			$no_insert_role = 0;
			if(!empty($app['role']))
			{
				foreach($app['role'] as $role_created)
				{
					if($myrole['role-name'] == $role_created['role-name'])
					{
						$no_insert_role = 1;
					}
				}
			}
			if($no_insert_role == 0)
			{
				$myrole['modified_date'] = $now;
				$myrole['date'] = $now;
				$app['role'][] = $myrole;
			}
		}
	}
	return $app;
}

?>
