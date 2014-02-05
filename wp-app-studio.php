<?php
defined( 'ABSPATH' ) OR exit;
/*
   Plugin Name: Wp App Studio
   Plugin URI: http://emarketdesign.com
   Description: Wp App Studio is a simple to use WordPress plugin which enables web designers, business users as well as bloggers to create wordpress based fully featured web and mobile apps without any coding.
   Version: 2.9.8
   Author: eMarket Design LLC
   Author URI: http://emarketdesign.com
   License: GPLv2 or later
*/
register_activation_hook( __FILE__, 'wpas_activate' );
register_deactivation_hook( __FILE__, 'wpas_deactivate' );

define('WPAS_URL', "emarketdesign.com");
define('WPAS_SSL_URL', "https://www.emarketdesign.com");
define('WPAS_VERSION', "2.9.8");
define('WPAS_DATA_VERSION', "3");
if(get_option('wpas_version') != WPAS_VERSION)
{
	update_option('wpas_version',WPAS_VERSION);
	wpas_add_design_cap();
}

function wpas_add_design_cap()
{
	$admin = get_role('administrator');
	if(!empty($admin) && !$admin->has_cap('design_wpas'))
	{
		$admin->add_cap('design_wpas');
	}
}

function wpas_activate () 
{
      if(!current_user_can('activate_plugins'))
      {
		return;
      }

      wpas_add_design_cap();
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
      $admin = get_role('administrator');
      $admin->remove_cap('design_wpas');
      //deletes of options are done in uninstall, delete of plugin
}

require_once("wpas_translate.php");
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
require_once("views/view_form.php");
require_once("views/widget_form.php");
require_once("views/role_form.php");
require_once("views/forms_form.php");

load_plugin_textdomain( 'wpas', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );


add_action('admin_menu', 'wpas_plugin_menu');
add_action( 'admin_init', 'wpas_update' );

if(get_option('wpas_data_version') != WPAS_DATA_VERSION)
{
	$apps = wpas_get_app_list();
	foreach($apps as $app_key => $myapp)
	{
		if(!isset($myapp['ver']) || $myapp['ver'] != WPAS_DATA_VERSION)
		{
			$myapp = wpas_update_data_arr($app_key,$myapp);
			wpas_update_app($myapp,$app_key);
		}
	}
	update_option('wpas_data_version',WPAS_DATA_VERSION);
}

function wpas_update_data_arr($app_key,$myapp)
{
	$entities = Array();
	$entnames = Array();
	$taxonomies = Array();
	$taxnames = Array();
	$fields = Array();
	$rfields = Array();
	$afields = Array();
	$rels = Array();
	$relfts = Array();
	$forms = Array();
	$relnameids = Array();
	$change_needed = Array();
	foreach($myapp['role'] as $keyrole => $myrole)
	{
		$roles[$keyrole] = $myrole['role-name'];
	}
	foreach($myapp['entity'] as $keyentity => $myentity)
	{
		if(!empty($myentity['field']))
		{
			$entities[$keyentity] = $myentity['ent-label'];
			$entnames[$keyentity] = $myentity['ent-name'];
			foreach($myentity['field'] as $keyfield => $myfield)
			{
				$fields[$keyentity . "__" . $keyfield] = $keyentity . "__" . $myfield['fld_name'];
				$rfields[$keyentity . "__" . $keyfield] = $myfield['fld_name'];
				$afields[$keyfield] = $myfield['fld_label'];
			}
		}
		if(isset($myentity['ent-has_user_relationship']) && isset($myentity['ent-limit_user_relationsip_role']) && in_array($myentity['ent-limit_user_relationsip_role'],$roles))
		{
			$myapp['entity'][$keyentity]['ent-limit_user_relationsip_role'] = array_search($myentity['ent-limit_user_relationsip_role'],$roles);
		}        
		if(!empty($myentity['layout']))
		{
			foreach($myentity['layout'] as $keyel => $layel)
			{
				if(!empty($layel['tabs']))
				{
					foreach($layel['tabs'] as $ktab => $tab)
					{
						$new_attr = Array();
						$attr_arr = explode(",",$tab['attr']);
						foreach($attr_arr as $myattr)
						{
							$new_attr[] = array_search($myattr,$afields);
						}
						$myapp['entity'][$keyentity]['layout'][$keyel]['tabs'][$ktab]['attr'] = $new_attr;
					}
				}
				if(!empty($layel['accs']))
				{
					foreach($layel['accs'] as $kacc => $acc)
					{
						$new_attr = Array();
						$attr_arr = explode(",",$acc['attr']);
						foreach($attr_arr as $myattr)
						{
							$new_attr[] = array_search($myattr,$afields);
						}
						$myapp['entity'][$keyentity]['layout'][$keyel]['accs'][$kacc]['attr'] = $new_attr;
					}
				}
			}
		}
	}
	if(!empty($myapp['form']))
	{
		foreach($myapp['form'] as $form_key => $myform)
		{
			$forms[$form_key] = $myform['form-name'];
		}
	}
	if(!empty($myapp['taxonomy']))
	{
		foreach($myapp['taxonomy'] as $keytax => $mytax)
		{
			$taxonomies[$keytax] = $mytax['txn-label'];
			$taxnames[$keytax] = $mytax['txn-name'];
			$newtax = $mytax;
			unset($newtax['txn-attach']);
			foreach($mytax['txn-attach'] as $key_attach => $txn_attach)
			{
				if(in_array($txn_attach,$entities))
				{
					$newtax['txn-attach'][$key_attach] = array_search($txn_attach,$entities);
				}
			}
			$newtax['modified_date'] = date("Y-m-d H:i:s");
			$myapp['taxonomy'][$keytax] = $newtax;
		}
	}
	if(!empty($myapp['relationship']))
	{
		foreach($myapp['relationship'] as $keyrel => $myrel)
		{
			$newrel = $myrel;
			if(in_array($myrel['rel-from-name'],$entities))
			{
				$newrel['rel-from-name'] = array_search($myrel['rel-from-name'],$entities);
			}
			if(in_array($myrel['rel-to-name'],$entities))
			{
				$newrel['rel-to-name'] = array_search($myrel['rel-to-name'],$entities);
			}
			if(!empty($myrel['rel-name-id']))
			{
				$rels[$myrel['rel-from-name'] . "__" . $myrel['rel-name-id']] = $keyrel;
				$relfts[$myrel['rel-from-name'] . "_" . $myrel['rel-to-name']] = $keyrel;
				$relnameids[$myrel['rel-name-id']] = $keyrel;
				unset($newrel['rel-name-id']);
			}
			$newrel['modified_date'] = date("Y-m-d H:i:s");
			$myapp['relationship'][$keyrel] = $newrel;
		}		
	}
	if(!empty($myapp['help']))
	{
		foreach($myapp['help'] as $keyhelp => $myhelp)
		{
			if(isset($myhelp['help-object_name']) && empty($myhelp['help-type']))
			{
				$newhelp = $myhelp;
				if(array_search($myhelp['help-object_name'],$taxonomies) !== false)
				{
					$newhelp['help-tax'] = array_search($myhelp['help-object_name'],$taxonomies);
					unset($newhelp['help-object_name']);
					$newhelp['help-type'] = 'tax';
				}
				elseif(array_search($myhelp['help-object_name'],$entities) !== false)
				{
					$newhelp['help-entity'] = array_search($myhelp['help-object_name'],$entities);
					$newhelp['help-type'] = 'ent';
					unset($newhelp['help-object_name']);
				}
				$newhelp['modified_date'] = date("Y-m-d H:i:s");
				$myapp['help'][$keyhelp] = $newhelp;
			}
		}
	}
	if(!empty($myapp['shortcode']))
	{
		foreach($myapp['shortcode'] as $keyshc => $myshc)
		{
			$newshc = $myshc;
			if(!isset($newshc['shc-view_type']))
			{
				$newshc['shc-view_type'] = 'std';
			}
			if(!empty($newshc['shc-attach_form']) && array_search($newshc['shc-attach_form'] ,$forms) !== false)
			{
				$newshc['shc-attach_form'] = array_search($newshc['shc-attach_form'] ,$forms);
				unset($newshc['shc-attach']);
			}
			elseif(!empty($newshc['shc-attach']) && array_search($newshc['shc-attach'] ,$entities) !== false)
			{
				$newshc['shc-attach'] = array_search($newshc['shc-attach'] ,$entities);
			}
			elseif(!empty($newshc['shc-attach_tax']))
			{
				$txn = str_replace("txn-","",$newshc['shc-attach_tax']);
				if(array_search($txn,$taxonomies) !== false)
				{
					$newshc['shc-attach_tax'] = array_search($txn,$taxonomies);
				}
			}
			$newshc['modified_date'] = date("Y-m-d H:i:s");
			$myapp['shortcode'][$keyshc] = $newshc;
				
		}
	}			
	if(!empty($myapp['widget']))
	{
		$new_widget = Array();
		foreach($myapp['widget'] as $widg_key => $mywidg)
		{
			$new_widget[$widg_key] = $mywidg;
			if(empty($mywidg['widg-name']))
			{
				$new_widget[$widg_key]['widg-name'] = str_replace(" ","_",strtolower($mywidg['widg-title']));
				$new_widget[$widg_key]['widg-name'] .= "_" . $mywidg['widg-type']; 
			}
			if(!empty($mywidg['widg-attach']) && array_search($mywidg['widg-attach'] ,$entities) !== false)
			{
				$new_widget[$widg_key]['widg-attach'] = array_search($mywidg['widg-attach'] ,$entities);
			}
			if(!empty($mywidg['widg-attach-rel']) && !empty($relfts[$mywidg['widg-attach-rel']]))
			{
				$new_widget[$widg_key]['widg-attach-rel'] = $relfts[$mywidg['widg-attach-rel']];
			}
			$new_widget[$widg_key]['modified_date'] = date("Y-m-d H:i:s"); 
		}
		$myapp['widget'] = $new_widget; 
	}
	if(!empty($myapp['role']))
	{
		if(!empty($myapp['entity']))
		{
			foreach($myapp['entity'] as $keyent => $myent)
			{
				if($myent['ent-name'] != 'post' && $myent['ent-name'] != 'page')
				{
					$change_needed['emd_' . $myent['ent-name'].'s'] = 'ent_' . $keyent;
				}
			}
		}
		if(!empty($myapp['taxonomy']))
		{
			foreach($myapp['taxonomy'] as $keytax => $mytax)
			{
				$label = str_replace(" ","_",$mytax['txn-label']);
				$change_needed[strtolower($label)] = 'tax_' . $keytax;
			}
		}
		if(!empty($myapp['widget']))
		{
			foreach($myapp['widget'] as $keywidg => $mywidg)
			{
				$label = str_replace(" ","_",$mywidg['widg-title']);
				if(isset($mywidg['widg-dash_subtype']))
				{
					$label = $mywidg['widg-dash_subtype'] . "_" . strtolower($label);
					$change_needed[$label] = 'widg_' . $keywidg;
				}
			}
		}
		if(!empty($myapp['form']))
		{
			foreach($myapp['form'] as $keyform => $myform)
			{
				$label = strtolower(str_replace(" ","_",$myform['form-name']));
				$change_needed[$label] = 'form_' . $keyform;
			}
		}
		if(!empty($myapp['shortcode']))
		{
			foreach($myapp['shortcode'] as $keyshc => $myshc)
			{
				$label = strtolower(str_replace(" ","_",$myshc['shc-label']));
				$change_needed[$label] = 'shc' . $keyshc;
			}
		}
		foreach($myapp['role'] as $rkey => $myrole)
		{
			$new_role = $myrole;
			foreach($myrole as $role_name => $role_value)
			{
				if(!in_array($role_name,Array('role-name','role-label','modified_date','date')))
				{
					if($role_value != 0)
					{
						$new_role[$role_name] = $role_value;
					}
					foreach($change_needed as $old => $new)
					{
						if(preg_match('/' . $old .'/',$role_name))
						{
							$new_role_name = str_replace($old,$new,$role_name);
							$new_role[$new_role_name] = $role_value;
							unset($new_role[$role_name]);
						}
					}
				}
				
			}
			$new_role['modified_date'] = date("Y-m-d H:i:s");
			$myapp['role'][$rkey] = $new_role;
		}
	}
	if(!empty($myapp['form']))
	{
		foreach($myapp['form'] as $form_key => $myform)
		{
			$newform = $myform;
			if(!empty($myform['form-attached_entity_id']))
			{
				$newform['form-attached_entity'] = $myform['form-attached_entity_id'];
				unset($newform['form-attached_entity_id']);
			}
			else
			{
				$newform['form-attached_entity'] = array_search($myform['form-attached_entity'],$entities);
			}
				
			if(!empty($newform['form-dependents']))
			{
				foreach($newform['form-dependents'] as $depkey => $mydep)
				{
					if(isset($rels[$mydep]))
					{
						$newform['form-dependents'][$depkey] = $rels[$mydep];
					}
				}
			}
			if(!empty($newform['form-confirm_sendto']))
			{
				if(preg_match('/_rel_/',$newform['form-confirm_sendto']))
				{
					$send_to = explode('_rel_',$newform['form-confirm_sendto']);
					if(in_array($send_to[0],$rfields))
					{
						$newform['form-confirm_sendto'] = array_search($send_to[0],$rfields);
						$newform['form-confirm_sendto'] .= "__" . $relnameids[$send_to[1]];
					}
				}
				elseif(in_array($newform['form-attached_entity'] . "__" . $newform['form-confirm_sendto'],$fields))
				{
					$newform['form-confirm_sendto'] = array_search($newform['form-attached_entity'] . "__" . $newform['form-confirm_sendto'],$fields);
				}
			}
			if(!empty($myform['form-layout']))
			{
				$seq = 0;
				$new_layout = Array();
				foreach($myform['form-layout'] as $layout_key => $layout_field)
				{
					if(get_option('wpas_data_version') == 2)
					{
						//new code
						if(isset($layout_field['obtype']) && !in_array($layout_field['obtype'],Array('hr','text')))
						{
							$new_layout[$layout_key] = $layout_field;
							foreach($layout_field as $fieldkey => $myfield)
							{
								$new_layout[$layout_key][$fieldkey]['obtype'] = $layout_field['obtype'];
							}
							unset($new_layout[$layout_key]['obtype']);
						}
						else
						{
							$new_layout[$layout_key] = $layout_field;
						}
						foreach($new_layout[$layout_key] as $fkey => $ffield)
						{
							if(!in_array($ffield,Array('hr','text')) && $fkey != 'desc')
							{
								$new_ffield = Array();
								$new_ffield['size'] = $ffield['size'];
								$new_ffield['obtype'] = $ffield['obtype'];
								if(isset($ffield['entity']))
								{
									$new_ffield['entity'] = array_search($ffield['entity'],$entnames);
								}
								if($ffield['obtype'] == 'tax')
								{
									$new_ffield['tax'] = array_search($ffield['tax'],$taxnames);
								}
								elseif($ffield['obtype'] == 'attr')
								{
									$attr = explode('__',array_search($new_ffield['entity'] . "__" . $ffield['attr'],$fields));
									if(isset($attr[1]))
									{
										$new_ffield['attr'] = $attr[1];	
									}
									$new_ffield['entity'] = $attr[0];
								}
								elseif($ffield['obtype'] == 'relent')
								{
									$new_ffield['relent'] = $relnameids[$ffield['relent']];
									$new_ffield['entity'] = array_search($ffield['entity'],$entities);
								}
								$new_layout[$layout_key][$fkey] = $new_ffield;
							}
						}
					}
					else
					{
						if(isset($layout_field['sequence']))
						{
							$seq = $layout_field['sequence'];
							if($layout_field['obtype'] == 'hr')
							{
								$new_layout[$seq]['obtype'] = 'hr';
							}
							elseif($layout_field['obtype'] == 'text')
							{
								$new_layout[$seq]['obtype'] = 'text';
								$new_layout[$seq]['desc'] = $layout_field['desc'];
							}
							else
							{
								$type = explode('-',$layout_field['obtype']);
								$new_layout[$seq]['obtype'] = $type[0];
								$span = (int) (12/ $type[1]);
								
								if(in_array($type[0], Array('attr','tax','relent')))
								{
									$new_layout[$seq][$layout_field['obposition']]['entity'] = $layout_field['entity'];
									$new_layout[$seq][$layout_field['obposition']][$type[0]] = $layout_field[$type[0]];
									$new_layout[$seq][$layout_field['obposition']]['size'] = $span;
									if($layout_field['obposition'] > 1)
									{
										$new_layout[$seq][$layout_field['obposition']]['label'] = $myform['form-layout'][$layout_key-$layout_field['obposition']+1]['box_label'.$layout_field['obposition']];
									}
									else
									{
										$new_layout[$seq][$layout_field['obposition']]['label'] = $layout_field['box_label'.$layout_field['obposition']];
									}
								}
							}
						}
					}
				}
				$newform['form-layout'] = $new_layout;
			}
			$newform['modified_date'] = date("Y-m-d H:i:s");
			$myapp['form'][$form_key] = $newform;
		}
	}
	$myapp['ver'] = '3';
	$myapp['modified_date'] = date("Y-m-d H:i:s");
	$myapp['option']['ao_domain'] = 'http://' . $myapp['option']['ao_domain'];
	return $myapp;
}


function wpas_plugin_menu() {
	global $hook_app_list, $hook_app_new;
	$icon_url = plugin_dir_url( __FILE__ ) . "img/wpas-icon.png";
	
	$hook_app_list = add_menu_page('WP App Studio', 'WP App Studio', 'design_wpas', 'wpas_app_list', 'wpas_app_list',$icon_url);
	$hook_app_new = add_submenu_page( 'wpas_app_list', __('Add New App','wpas'), __('Add New App','wpas'), 'design_wpas', 'wpas_add_new_app', 'wpas_add_new_app');
	add_action( 'admin_enqueue_scripts', 'wpas_enqueue_scripts' );

}
function wpas_enqueue_scripts($hook_suffix){
	global $hook_app_list, $hook_app_new, $local_vars,$form_vars, $layout_vars, $validate_vars;
	if($hook_suffix == $hook_app_list || $hook_suffix == $hook_app_new)
	{
		add_filter('user_can_richedit', '__return_true');
		$local_vars['nonce_update_field_order'] = wp_create_nonce( 'wpas_update_field_order_nonce' );
		$local_vars['nonce_delete_field'] = wp_create_nonce( 'wpas_delete_field_nonce' );
		$local_vars['nonce_delete'] = wp_create_nonce( 'wpas_delete_nonce' );
		$local_vars['nonce_save_form'] = wp_create_nonce( 'wpas_save_form_nonce' );
		$local_vars['nonce_save_option_form'] = wp_create_nonce( 'wpas_save_option_form_nonce' );
		$local_vars['nonce_update_form'] = wp_create_nonce( 'wpas_update_form_nonce' );
		$local_vars['nonce_save_field'] = wp_create_nonce( 'wpas_save_field_nonce' );
		$local_vars['nonce_check_status_generate'] = wp_create_nonce( 'wpas_check_status_generate_nonce' );
		$local_vars['nonce_save_layout'] = wp_create_nonce( 'wpas_save_layout_nonce' );

		$form_vars['nonce_save_form_layout'] = wp_create_nonce('wpas_save_form_layout_nonce');

		wp_enqueue_style('wpas-admin-css', plugin_dir_url( __FILE__ ) . 'css/wpas-admin.css');
		wp_enqueue_style('wpas-core',plugin_dir_url( __FILE__ ) . 'css/wpas-core.css');
		wp_enqueue_style('font-awesome',plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css');
		wp_enqueue_style("wp-jquery-ui-draggable");
		wp_enqueue_style("wp-jquery-ui-droppable");
		wp_enqueue_style("wp-jquery-ui-sortable");
		wp_enqueue_style("jq-css","http://code.jquery.com/ui/1.10.3/themes/pepper-grinder/jquery-ui.css");


		wp_enqueue_script('bootstrap-min-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js');
		wp_enqueue_script('jquery-validate-js', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js');
		wp_enqueue_script("jquery-ui-draggable");
		wp_enqueue_script("jquery-ui-droppable");
		wp_enqueue_script("jquery-ui-sortable");
		wp_enqueue_script('jquery-ui-accordion');
	
		wp_enqueue_script('wpas-js', plugin_dir_url( __FILE__ ) . 'js/wpas.js',array(),false,true);
		wp_enqueue_script('wpas-layout-js',plugin_dir_url( __FILE__ ) . 'js/wpas_layout.js',array(),false,true);
		wp_enqueue_script('wpas-paging-js',plugin_dir_url( __FILE__ ) . 'js/wpas_paging.js',array(),false,true);
		wp_enqueue_script('wpas-validate-js',plugin_dir_url( __FILE__ ) . 'js/wpas_validate.js',array(),false,true);
		wp_enqueue_script('jquery-time-js',plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js',array('jquery-ui-datepicker', 'jquery-ui-slider'),null, true);

		wp_enqueue_script( 'wpas-form-layout-js',plugin_dir_url( __FILE__ ) . 'js/wpas_form_layout.js',array(),false,true);

		wp_localize_script('wpas-js','wpas_vars',$local_vars);
		wp_localize_script('wpas-form-layout-js','form_vars',$form_vars);
		wp_localize_script('wpas-layout-js','layout_vars',$layout_vars);
		wp_localize_script('wpas-validate-js','validate_vars',$validate_vars);
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
		echo wpas_list("app",$apps_unserialized,0,1);
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
		echo "<div id=\"loading\" class=\"group1\" style=\"display: none;\">" . __("Please wait","wpas") . "...</div>";
		wpas_modal_confirm_delete();
		echo "<div id=\"list-entity\" class=\"group1\" style=\"display: block;\">";
		echo wpas_list('entity',$app,$app_key,1);
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
		echo "</div>";
		echo "<div id=\"add-form-div\" class=\"group1\" style=\"display: none;\">";
		wpas_add_forms_form($app_key);
		echo "</div>";
		echo "<div id=\"edit-form-layout-div\" class=\"group1 container-fluid\" style=\"display: none;\">";
		wpas_form_layout_form();
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
		echo wpas_list('entity',Array(),$app_key,1);
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
                <h3><i class=\"icon-trash icon-red\"></i>" . __("Delete","wpas") . "</h3>
                </div>
                <div class=\"modal-body\" style=\"clear:both\">" . __("Are you sure you wish to delete?","wpas") . 
                "</div>
                <div class=\"modal-footer\">
                <button id=\"delete-cancel\" class=\"btn btn-danger\" data-dismiss=\"confirmdeleteModal\" aria-hidden=\"true\">" . __("Cancel","wpas") . "</button> 
                <button id=\"delete-ok\" data-dismiss=\"confirmdeleteModal\" aria-hidden=\"true\" class=\"btn btn-primary\">" . __("OK","wpas") . "</button>
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
