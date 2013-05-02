<?php
//ajax functions
add_action('wp_ajax_wpas_get_app_options', 'wpas_get_app_options');
add_action('wp_ajax_wpas_save_form', 'wpas_save_form');
add_action('wp_ajax_wpas_save_option_form', 'wpas_save_option_form');
add_action('wp_ajax_wpas_save_field', 'wpas_save_field');
add_action('wp_ajax_wpas_update_form', 'wpas_update_form');
add_action('wp_ajax_wpas_list_all', 'wpas_list_all');
add_action('wp_ajax_wpas_delete', 'wpas_delete');
add_action('wp_ajax_wpas_edit', 'wpas_edit');
add_action('wp_ajax_wpas_edit_field', 'wpas_edit_field');
add_action('wp_ajax_wpas_delete_field', 'wpas_delete_field');
add_action('wp_ajax_wpas_list_fields', 'wpas_list_fields');
add_action('wp_ajax_wpas_update_field_order', 'wpas_update_field_order');
add_action('wp_ajax_wpas_list_ent_fields', 'wpas_list_ent_fields');
add_action('wp_ajax_wpas_save_layout', 'wpas_save_layout');
add_action('wp_ajax_wpas_get_layout', 'wpas_get_layout');
add_action('wp_ajax_wpas_get_entities', 'wpas_get_entities');
add_action('wp_ajax_wpas_edit_role', 'wpas_edit_role');

add_action('wp_ajax_wpas_check_unique', 'wpas_check_unique');
add_action('wp_ajax_wpas_check_rel', 'wpas_check_rel');
add_action('wp_ajax_wpas_check_help', 'wpas_check_help');
add_action('wp_ajax_wpas_check_widg', 'wpas_check_widg');
add_action('wp_ajax_wpas_check_status_generate', 'wpas_check_status_generate');


function wpas_check_status_generate()
{
	check_ajax_referer('check_status_generate_nonce','nonce');
	$queue_id = $_POST['queue_id'];
	$resp = Array();
	$fields_string = "method=check_status&queue_id=" . $queue_id;

	$submits = unserialize(get_option('wpas_apps_submit'));
	$no_check = 0;
	//check last submit time and send curl request to check status
	if(isset($submits))
	{
		foreach($submits as $mysubmit)
		{
			if($mysubmit['queue_id'] == $queue_id)
			{
				if(time() < $mysubmit['refresh_time'] + 300)
				{
					$resp[3] = "Your application is in queue and will be generated soon. Thank you for your patience.";
					$no_check =1;
				}
				elseif($mysubmit['status'])
				{
					if(strpos($mysubmit['status'],'Success') != false || strpos($mysubmit['status'],'Error') != false || strpos($mysubmit['status'],'Change') != false )
					{
						$resp[0] = $mysubmit['status_link'];
						$resp[1] = $mysubmit['status'];
						$resp[4] = $mysubmit['credit_used'];
						$resp[5] = $mysubmit['credit_left'];
						$resp[6] = $mysubmit['update_left'];
						$no_check =1;
					}
				}
			}
		}
	}
	if($no_check == 0)
	{		
		$xml = wpas_curl_request('check_status',$fields_string);

		if(!$xml)
		{
			$resp[2] = "System error, please try again later.";
		}
		elseif($xml->error)
		{
			if((string) $xml->error == 'error')
			{
				$resp[0] = (string) $xml->url;
				$resp[1] = '<span style="color:red;">Error</span>';
				$resp[2] = (string) $xml->errormsg;
				$resp[4] = (string) $xml->credit_used;
				$resp[5] = (string) $xml->credit_left;
				$resp[6] = (string) $xml->update_left;
				$new_submit['status']= '<span style="color:red;">Error</span>';
				$new_submit['credit_used']= (string) $xml->credit_used;
				$new_submit['credit_left']= (string) $xml->credit_left;
				$new_submit['update_left']= (string) $xml->update_left;
				$new_submit['status_link']= (string) $xml->url;
			}
			else
			{
				//if return is not completed 
				$resp[3] = (string) $xml->errormsg;
			}
		}
		elseif($xml->success)
		{
			//if return is success 
			if($xml->success == 'duplicate')
			{
				$resp[1] = '<span style="color:green;">No Change Detected</span>';
				$new_submit['status']= '<span style="color:green;">No Change Detected</span>';
			}
			else
			{
				$resp[1] = '<span style="color:green;">Success</span>';
				$new_submit['status']= '<span style="color:green;">Success</span>';
			}
				
			$resp[0] = (string) $xml->url;
			$resp[4] = (string) $xml->credit_used;
			$resp[5] = (string) $xml->credit_left;
			$resp[6] = (string) $xml->update_left;
			$new_submit['credit_used']= (string) $xml->credit_used;
			$new_submit['credit_left']= (string) $xml->credit_left;
			$new_submit['update_left']= (string) $xml->update_left;
			$new_submit['status_link']= (string) $xml->url;
		}

		$newsubmits = Array();
		foreach($submits as $mysubmit)
		{
			$newsub = Array();
			if($mysubmit['queue_id'] == $queue_id)
			{
				foreach($mysubmit as $key => $value)
				{
					if(isset($new_submit[$key]))
					{
						$newsub[$key] = $new_submit[$key];
					}
					else
					{
						$newsub[$key] = $value;
					}
				}
				$newsubmits[] = $newsub;	
			}
			else
			{
				$newsubmits[] = $mysubmit;	
			}
		}
		update_option('wpas_apps_submit',serialize($newsubmits));
	}
	//send return back
	echo json_encode($resp);
	die();
}


function wpas_edit_role()
{
	$app_id = $_GET['app_id'];
	$role_id = $_GET['role_id'];
	if(isset($app_id) && isset($role_id))
	{
		wpas_add_role_form($app_id,$role_id);
	}
	die();
}


function wpas_check_help()
{
	$app_id = $_GET['app_id'];
	$help_id = $_GET['help_id'];
	$object_name = $_GET['object_name'];
	$screen_type = $_GET['screen_type'];
	$app = wpas_get_app($app_id);
	$resp = true;
	foreach($app['help'] as $key => $myhelp)
	{
		if($myhelp['help-object_name'] == $object_name && $myhelp['help-screen_type'] == $screen_type)
		{
			if($help_id == null || $help_id != $key)
			{
				$resp = false;
				break;
			}
		}
	}
	echo json_encode($resp);
	die();
}
function wpas_check_rel()
{
	$app_id = $_GET['app_id'];
	$rel_id = $_GET['rel_id'];
	$from_name = $_GET['from_name'];
	$to_name = $_GET['to_name'];
	$from_title = $_GET['from_title'];
	$to_title = $_GET['to_title'];
	$app = wpas_get_app($app_id);
	$resp = true;
	foreach($app['relationship'] as $key => $myrel)
	{
		if($myrel['rel-from-title'] == $from_title && $myrel['rel-to-title'] == $to_title && $myrel['rel-from-name'] == $from_name && $myrel['rel-to-name'] == $to_name)
		{
			if($rel_id == null || $rel_id != $key)
			{
				$resp = false;
				break;
			}
		}
		elseif($myrel['rel-from-name'] == $to_name && $myrel['rel-to-name'] == $from_name && $myrel['rel-from-title'] == $to_title && $myrel['rel-to-title'] == $from_title)
		{
			if($rel_id == null || $rel_id != $key)
			{
				$resp = false;
				break;
			}
		}
	}
	echo json_encode($resp);
	die();
}

function wpas_check_widg()
{
	$app_id = $_GET['app_id'];
        $widg_id = $_GET['widg_id'];
        $widg_title= $_GET['widg_title'];
        $widg_type= $_GET['widg_type'];
        $widg_subtype= $_GET['widg_subtype'];
        $app = wpas_get_app($app_id);
        $resp = true;
	if($widg_type == 'dashboard')
	{
		$check_subtype = 'widg-dash_subtype';
	}
	elseif($widg_type == 'sidebar')
	{
		$check_subtype = 'widg-side_subtype';
	}
        foreach($app['widget'] as $key => $mywidg)
        {
                if($mywidg['widg-title'] == $widg_title && $mywidg['widg-type'] == $widg_type && $mywidg[$check_subtype] == $widg_subtype)
                {
                        if($widg_id == null || $widg_id != $key)
                        {
                                $resp = false;
                                break;
                        }
                }
        }
        echo json_encode($resp);
        die();
}

function wpas_check_unique()
{
$response = true;
$name = $_GET['value'];
$type = $_GET['type'];

$app_id = $_GET['app_id'];

if(isset($_GET['ent_id']))
{
	$ent_id = $_GET['ent_id'];
}
if(isset($_GET['fld_id']))
{
	$fld_id = $_GET['fld_id'];
}
if(isset($_GET['txn_id']))
{
	$txn_id = $_GET['txn_id'];
}
if(isset($_GET['rel_fld_id']))
{
	$rel_fld_id = $_GET['rel_fld_id'];
}
if(isset($_GET['rel_id']))
{
	$rel_id = $_GET['rel_id'];
}
if(isset($_GET['help_id']))
{
	$help_id = $_GET['help_id'];
}
if(isset($_GET['help_fld_id']))
{
	$help_fld_id = $_GET['help_fld_id'];
}
if(isset($_GET['shc_id']))
{
	$shc_id = $_GET['shc_id'];
}
if(isset($_GET['role_id']))
{
	$role_id = $_GET['role_id'];
}
	if($type == 'app')
	{
		$apps_unserialized = wpas_get_app_list();
		foreach($apps_unserialized as $key => $myapp)
		{
			if(stripslashes($name) == $myapp['app_name'] && $key != $app_id)
			{
				$response = false;
				break;
			}
		}
	}
	elseif($type == 'ent')
	{
		$app = wpas_get_app($app_id);
		foreach($app['entity'] as $key => $myent)
		{
			if(strtolower($name) == $myent['ent-name'] && ($ent_id == null || $key != $ent_id))
			{
				$response = false;
				break;
			}	
		}
	}
	elseif($type == 'ent_field')
	{
		$app = wpas_get_app($app_id);
		foreach($app['entity'][$ent_id]['field'] as $key => $myfield)
		{
			if(strtolower($name) == $myfield['fld_name'] && ($fld_id == null || $key != $fld_id))
			{
				$response = false;
				break;
			}
		}
	}
	elseif($type == 'rel_field')
	{
		$app = wpas_get_app($app_id);
		foreach($app['relationship'][$rel_id]['field'] as $key => $myfield)
		{
			if(strtolower($name) == $myfield['rel_fld_name'] && ($rel_fld_id == null || $key != $rel_fld_id))
			{
				$response = false;
				break;
			}
		}
	}
	elseif($type == 'txn')
	{
		$app = wpas_get_app($app_id);
		foreach($app['taxonomy'] as $key => $mytxn)
		{
			if(strtolower($name) == $mytxn['txn-name'] && ($txn_id == null || $key != $txn_id))
			{
				$response = false;
				break;
			}
		}
		foreach($app['entity'] as $key => $myent)
		{
			if(strtolower($name) == $myent['ent-name'])
			{
				$response = false;
				break;
			}
		}
	}
	elseif($type == 'help_fld')
	{
		$app = wpas_get_app($app_id);
		foreach($app['help'][$help_id]['field'] as $key => $myfield)
		{
			if(strtolower($name) == strtolower($myfield['help_fld_name']) && ($help_fld_id == null || $key != $help_fld_id))
			{
				$response = false;
				break;
			}
		}
	}
	elseif($type == 'shc')
	{
		$app = wpas_get_app($app_id);
		foreach($app['shortcode'] as $key => $myshc)
		{
			if(strtolower($name) == $myshc['shc-label'] && ($shc_id == null || $key != $shc_id))
			{
				$response = false;
				break;
			}
		}
	}
	elseif($type == 'role')
	{
		$app = wpas_get_app($app_id);
		foreach($app['role'] as $key => $myrole)
		{
			if(strtolower($name) == $myrole['role-name'] && ($role_id == null || $key != $role_id))
			{
				$response = false;
				break;
			}
		}
	}
	echo json_encode($response);
	die();
}

function wpas_save_layout()
{
	check_ajax_referer('save_layout_nonce','nonce');
	$app_id = $_POST['app_id'];
	$ent_id = $_POST['ent_id'];
	$layout = $_POST['layout'];
	$app = wpas_get_app($app_id);
	$app['entity'][$ent_id]['layout'] = $layout;
	$app['entity'][$ent_id]['modified_date'] = date("Y-m-d H:i:s");
	wpas_update_app($app,$app_id);
	die();
}
function wpas_get_layout()
{
	$layout = "";
	$app_id = $_GET['app_id'];
	$ent_id = $_GET['ent_id'];
	$app = wpas_get_app($app_id);
	if(isset($app['entity'][$ent_id]['layout']))
	{
		$layout = $app['entity'][$ent_id]['layout'];
	}
	echo  wpas_entity_container($layout);
	die();
}
function wpas_get_app_options()
{
	$app_id = $_GET['app_id'];
	$app = wpas_get_app($app_id);
	$response = $app['option'];
	echo json_encode($response);
	die();
}
function wpas_entity_types($app_id,$type,$values="")
{
	if(!is_array($values))
	{
		$values= Array("$values");
	}
	$app = wpas_get_app($app_id);
	if($type == 'help')
	{
		$return .= "<option value='' style='font-style:italic;font-weight:bold;'> Entities </option>";
	}
	foreach($app['entity'] as $myent)
	{
		$return .= "<option value='" . $myent['ent-label'] . "'"; 
		if($type == 'help')
		{
			$return .= " style='padding-left:2em;'";
		}
		if(in_array($myent['ent-label'],$values))
		{
			$return .= " selected";
		}
		$return .= ">" . $myent['ent-label'] . "</option>";
	}
	if($type == 'help')
	{
		$return .= "<option value='' style='font-style:italic;font-weight:bold;'> Taxonomies </option>";

		foreach($app['taxonomy'] as $mytxn)
		{
			$return .= "<option value='txn-" . $mytxn['txn-label'] . "' style='padding-left:2em;'"; 
			if(in_array($mytxn['txn-label'],$values))
			{
				$return .= " selected";
			}
			$return .= ">" . $mytxn['txn-label'] . "</option>";
		}
	}
	return $return;
}
function wpas_get_entities()
{
	$app_id = $_GET['app_id'];
	$values = $_GET['values'];
	$type = $_GET['type'];
	echo wpas_entity_types($app_id,$type,$values);
	die();
}

function wpas_update_field_order()
{
	check_ajax_referer('update_field_order_nonce','nonce');
	$order = $_POST['order'];
	$app_id = $_POST['app'];
	$comp_id = $_POST['comp'];
	$type = $_POST['type'];
	$count = 0;
	$app = wpas_get_app($app_id);
	$newfield = Array();

	foreach($order as $ord)
	{
		$newfield[$ord] = $app[$type][$comp_id]['field'][$ord];
	}
	$app[$type][$comp_id]['field'] = $newfield;
	wpas_update_app($app,$app_id);
	if($type == 'entity')
	{
		echo wpas_view_ent_fields_list($app[$type][$comp_id]['field']);     
	}
	elseif($type == 'relationship')
	{
		echo wpas_view_rel_fields_list($app[$type][$comp_id]['field']);
	}
	elseif($type == 'help')
	{
		echo wpas_view_help_tabs_list($app[$type][$comp_id]['field']);
	}
	die();
}       
function wpas_edit_field()
{
	$app_id = $_GET['app'];
	$comp_id = $_GET['comp'];
	$type = $_GET['type'];
	$field_id = $_GET['field'];
	$app = wpas_get_app($app_id);

	$response[0] = $app[$type][$comp_id]['field'][$field_id];
	echo json_encode($response);
	die();
}
function wpas_delete_field()
{
	check_ajax_referer('delete_field_nonce','nonce');
	$app_id = $_POST['app'];
	$comp_id = $_POST['comp'];
	$type = $_POST['type'];
	$field_id = $_POST['field'];
	$app = wpas_get_app($app_id);

	if($type == 'entity')
	{       
		$flabel = $app[$type][$comp_id]['field'][$field_id]['fld_label'];
		//also delete this attr from entity layout
		if(isset($app[$type][$comp_id]['layout']))
		{
			foreach($app[$type][$comp_id]['layout'] as $lkey => $mylayout)
			{
				if(isset($mylayout['tabs']))
				{
				foreach($mylayout['tabs'] as $tkey => $mytab)
				{
					$my_attrs = explode(",",$mytab['attr']);
					$my_attr_key = array_search($flabel,$my_attrs);
					if($my_attr_key !== false)
					{
						unset($my_attrs[$my_attr_key]);
					}
					$mylayout['tabs'][$tkey]['attr'] = implode(',',$my_attrs);
				}
				}
				if(isset($mylayout['accs']))
				{
				foreach($mylayout['accs'] as $akey => $myacc)
				{
					$my_attrs = explode(",",$myacc['attr']);
					$my_attr_key = array_search($flabel,$my_attrs);
					if($my_attr_key !== false)
					{
						unset($my_attrs[$my_attr_key]);
					}
					$mylayout['accs'][$akey]['attr'] = implode(',',$my_attrs);
				}
				}
				$app[$type][$comp_id]['layout'][$lkey] = $mylayout;
			}
		}
		unset($app[$type][$comp_id]['field'][$field_id]);
		echo '<div id="ent-fld-list-div">';
		echo wpas_view_ent_fields_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}       
	if($type == 'relationship')
	{       
		unset($app[$type][$comp_id]['field'][$field_id]);
		echo '<div id="rel-fld-list-div">';
		echo wpas_view_rel_fields_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}       
	if($type == 'help')
	{       
		unset($app[$type][$comp_id]['field'][$field_id]);
		echo '<div id="help-fld-list-div">';
		echo wpas_view_help_tabs_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}
	wpas_update_app($app,$app_id);       
	die();
}
function wpas_save_field()
{
	check_ajax_referer('save_field_nonce','nonce');
	$get_array = explode("&", stripslashes($_POST['form']));
	$type = $_POST['type'];
	$field_id = $_POST['field_id'];
	if($type == 'entity')
	{
		$search_str = "fld_";
	}
	elseif($type == 'relationship')
	{
		$search_str = "rel_fld_";
	}
	else
	{
		$search_str = "help_fld_";
	}
	$field = Array();

	foreach($get_array as $myget)
	{
		$field_form = explode("=",$myget);
		$pos = strpos($field_form[0],$search_str);
		$field_form_value = urldecode(str_replace($field_form[0].'=','',$myget));

		if($field_form[0] == 'app')
		{
			$app_id = $field_form_value;
		}
		if($field_form[0] == 'ent')
		{
			$comp_id = $field_form_value;
		}
		if($field_form[0] == 'rel')
		{
			$comp_id = $field_form_value;
		}
		if($field_form[0] == 'help')
		{
			$comp_id = $field_form_value;
		}

		if($pos !== false && $field_form_value != "")
		{
			if($field_form[0] == 'rel_fld_name' || $field_form[0] == 'fld_name')
			{
				$field[$field_form[0]] = strtolower($field_form_value);
			}
			else
			{
				$field[$field_form[0]] = $field_form_value;
			}
		}
	}
	$app = wpas_get_app($app_id);
	if($field_id == "")
	{
		if(!empty($app[$type][$comp_id]['field']))
		{
			$field_id = max(array_keys($app[$type][$comp_id]['field'])) +1;
		}
		else
		{
			$field_id = 0;
		}
		$field['date'] = date("Y-m-d H:i:s");
	}
	else
	{
		$field['modified_date'] = date("Y-m-d H:i:s");
	}

	$app[$type][$comp_id]['modified_date'] = date("Y-m-d H:i:s");
	$app[$type][$comp_id]['field'][$field_id] = $field;
	if($type == 'entity')
	{
		//update layout also
		$flabel = $field['fld_label'];
		$flabel_old = $app[$type][$comp_id]['field'][$field_id]['fld_label'];
		if(isset($app[$type][$comp_id]['layout']))
		{
			foreach($app[$type][$comp_id]['layout'] as $lkey => $mylayout)
			{
				if(isset($mylayout['tabs']))
				{
					foreach($mylayout['tabs'] as $tkey => $mytab)
					{
						$my_attrs = explode(",",$mytab['attr']);
						$my_attr_key = array_search($flabel_old,$my_attrs);
						if($my_attr_key !== false)
						{
							$my_attrs[$my_attr_key] = $flabel;
						}
						$mylayout['tabs'][$tkey]['attr'] = implode(',',$my_attrs);
					}
				}
				if(isset($mylayout['accs']))
				{
					foreach($mylayout['accs'] as $akey => $myacc)
					{
						$my_attrs = explode(",",$myacc['attr']);
						$my_attr_key = array_search($flabel_old,$my_attrs);
						if($my_attr_key !== false)
						{
							$my_attrs[$my_attr_key] = $flabel;
						}
						$mylayout['accs'][$akey]['attr'] = implode(',',$my_attrs);
					}
				}
				$app[$type][$comp_id]['layout'][$lkey] = $mylayout;
			}
		}
		
		echo wpas_view_entity($app[$type][$comp_id],$comp_id);
		echo wpas_view_ent_fields($app[$type][$comp_id]['ent-name']);       
		echo '<div id="ent-fld-list-div">';
		echo wpas_view_ent_fields_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}
	elseif($type == 'relationship')
	{
		echo wpas_view_relationship($app[$type][$comp_id],$comp_id);
		echo wpas_view_rel_fields($app[$type][$comp_id]['rel-from-name']. "-" . $app[$type][$comp_id]['rel-to-name']);
		echo '<div id="rel-fld-list-div">';
		echo wpas_view_rel_fields_list($app[$type][$comp_id]['field']);
		echo '</div>';
	}
	elseif($type == 'help')
	{
		echo wpas_view_help($app[$type][$comp_id],$comp_id);
		echo wpas_view_help_tabs($app[$type][$comp_id]['help-object_name']);
		echo '<div id="help-fld-list-div">';
		echo wpas_view_help_tabs_list($app[$type][$comp_id]['field']);
		echo '</div>';
	}
	wpas_update_app($app,$app_id);

	die();
}

function wpas_list_fields()
{
	$type = $_GET['type'];
	$app_id = $_GET['app_id'];
	$comp_id = $_GET['comp_id'];
	if(isset($type) && isset($app_id) && isset($comp_id))
	{
		$app = wpas_get_app($app_id);
		if($type == 'entity')
		{
			echo wpas_view_entity($app['entity'][$comp_id],$comp_id);
			echo wpas_view_ent_fields($app['entity'][$comp_id]['ent-name']);    
			echo '<div id="ent-fld-list-div">';
			if(isset($app[$type][$comp_id]['field']))
			{
				echo wpas_view_ent_fields_list($app[$type][$comp_id]['field']);     
			}
			echo '</div>';
		}
		elseif($type == 'relationship')
		{
			echo wpas_view_relationship($app['relationship'][$comp_id],$comp_id);
			echo wpas_view_rel_fields($app[$type][$comp_id]['rel-from-name']. "-" . $app[$type][$comp_id]['rel-to-name']);
			echo '<div id="rel-fld-list-div">';
			if(isset($app[$type][$comp_id]['field']))
			{
				echo wpas_view_rel_fields_list($app[$type][$comp_id]['field']);
			}
			echo '</div>';
		}
		elseif($type == 'help')
		{
			echo wpas_view_help($app['help'][$comp_id],$comp_id);
			echo wpas_view_help_tabs($app[$type][$comp_id]['help-object_name']);
			echo '<div id="help-fld-list-div">';
			if(isset($app[$type][$comp_id]['field']))
			{
				echo wpas_view_help_tabs_list($app[$type][$comp_id]['field']);
			}
			echo '</div>';
		}
	}

	die();
}
function wpas_edit()
{
	$app_id = $_GET['app_id'];
	$comp_id = $_GET['comp_id'];
	$type = $_GET['type'];
	$app = wpas_get_app($app_id);

	if(isset($app[$type][$comp_id]['shc-sc_layout']))
	{
		$app[$type][$comp_id]['shc-sc_layout'] =stripslashes($app[$type][$comp_id]['shc-sc_layout']);
	}
	$response[0] = $app[$type][$comp_id];
	$response[1] = $comp_id;
	echo json_encode($response);
	die();  
}
function wpas_delete()
{
	check_ajax_referer('delete_nonce','nonce');

	$type = $_POST['type'];
	if($type == 'app')
	{
		$app_del_keys = $_POST['fields'];
		foreach($app_del_keys as $del_key)
		{
			$app_key_list = wpas_delete_app($del_key);
		}
		$app_list = wpas_get_app_list($app_key_list);
		echo wpas_list('app',$app_list,0,"",1);
	}
	else
	{
		$app_id = $_POST['app_id'];
		$comp_arr = $_POST['fields'];
		$app = wpas_get_app($app_id);
		foreach ($comp_arr as $del_key)
		{
			if($type == 'entity')
			{
				$ent_name = $app[$type][$del_key]['ent-name'];
				$ent_label = $app[$type][$del_key]['ent-label'];
				$txn_labels_arr = Array();
				if(!in_array($ent_name,Array('post','page')))
				{
					unset($app[$type][$del_key]);
				}
				//after deleting entity delete all attached objects
				//1- delete the taxonomy
				if(isset($app['taxonomy']))
				{
					foreach($app['taxonomy'] as $tkey => $mytaxonomy)
					{
						if(count($mytaxonomy['txn-attach']) == 1)
						{
							if($mytaxonomy['txn-attach'][0] == $ent_label)
							{
								$txn_labels_arr[] = $app['taxonomy'][$tkey]['txn-label'];
								unset($app['taxonomy'][$tkey]);
							}
						}
						else	
						{
							foreach($mytaxonomy['txn-attach'] as $tattachkey => $txn_attach)
							{	
								if($txn_attach == $ent_label)
								{
									unset($app['taxonomy'][$tkey]['txn-attach'][$tattachkey]);
								}
							}
						}
					}
				}
				//2-delete the relationships
				if(isset($app['relationship']))
				{
					foreach($app['relationship'] as $rkey => $myrelationship)
					{
						if($myrelationship['rel-from-name'] == $ent_label)
						{
							unset($app['relationship'][$rkey]);
						}
						if($myrelationship['rel-to-name'] == $ent_label)
						{
							unset($app['relationship'][$rkey]);
						}
					}
				}
				//3 -delete the shortcodes
				if(isset($app['shortcode']))
				{
					foreach($app['shortcode'] as $skey => $myshortcode)
					{
						if($myshortcode['shc-attach'] == $ent_label)
						{
							unset($app['shortcode'][$skey]);
						}
					}
				}
				//4 -delete the widgets
				if(isset($app['widget']))
				{
					foreach($app['widget'] as $skey => $mywidget)
					{
						if($mywidget['widg-attach'] == $ent_label)
						{
							unset($app['widget'][$skey]);
						}
					}
				}
				//5 - delete the helps
				if(isset($app['help']))
				{
					foreach($app['help'] as $hkey => $myhelp)
					{
						if($myhelp['help-object_name'] == $ent_label)
						{
							unset($app['help'][$hkey]);
						}
						if(!empty($txn_labels_arr))
						{
							foreach($txn_labels_arr as $mytxn_label)
							{
								if($myhelp['help-object_name'] == $mytxn_label)
								{
									unset($app['help'][$hkey]);
								}
							}
						}
					}
				}
				//6- delete the entity capabilities for each role
				if(isset($app['role']))
				{
					foreach($app['role'] as $rkey => $myrole)
					{
						//check removed taxonomies
						foreach($txn_labels_arr as $mytxn_label)
						{
							$label = $mytxn_label;
							$label = str_replace(" ","_",$label);
							$label = strtolower($label);
							$pattern = '/' . $label . '/';
							foreach($myrole as $role_name => $role_value)
							{
								if(preg_match($pattern,$role_name))
								{
									unset($app['role'][$rkey][$role_name]);
								}
							}
						}
						//delete capabilities for that entity
						foreach($myrole as $role_name => $role_value)
						{
							$label = $ent_name;
							$label = strtolower($label);
							$label = $label . "s";
							$label = 'emd_' . $label;
							$pattern = '/' . $label . '/';
							if(preg_match($pattern,$role_name))
							{
								unset($app['role'][$rkey][$role_name]);
							}
						}
					}
				}
			}
			elseif($type == 'taxonomy')
			{
				$txn_label = $app[$type][$del_key]['txn-label'];
				//1 check if help has this taxonomy, then delete those help
				if(isset($app['help']))
				{
					foreach($app['help'] as $hkey => $myhelp)
					{
						if($myhelp['help-object_name'] == $txn_label)
						{
							unset($app['help'][$hkey]);
						}
					}
				}
				//2- delete the taxonomy capabilities for each role
				if(isset($app['role']))
				{
					foreach($app['role'] as $rkey => $myrole)
					{
						$label = $txn_label;
						$label = str_replace(" ","_",$label);
						$label = strtolower($label);
						$pattern = '/' . $label . '/';
						foreach($myrole as $role_name => $role_value)
						{
							if(preg_match($pattern,$role_name))
							{
								unset($app['role'][$rkey][$role_name]);
							}
						}
					}
				}
				unset($app[$type][$del_key]);
			}	
			elseif($type == 'widget')
			{
				$widg_label = $app[$type][$del_key]['widg-title'];
				if($app[$type][$del_key]['widg-type'] == 'dashboard')
				{ 
					//1- delete the widget capabilities for each role
					foreach($app['role'] as $rkey => $myrole)
					{
						$label = $widg_label;
						$label = str_replace(" ","_",$label);
						$label = strtolower($label);
						$label = $app[$type][$del_key]['widg-dash_subtype'] . "_" . $label;
						$pattern = '/' . $label . '$/';
						foreach($myrole as $role_name => $role_value)
						{
							if(preg_match($pattern,$role_name))
							{
								unset($app['role'][$rkey][$role_name]);
							}
						}
					}
				}
				unset($app[$type][$del_key]);
			}	
			elseif($type == 'role')
			{
				if(!wpas_is_def_role($app[$type][$del_key]))
				{
					unset($app[$type][$del_key]);
				}
			}
			else
			{
				unset($app[$type][$del_key]);
			}
		}
		echo wpas_list($type,$app[$type],$app_id,$app['app_name'],1);
		wpas_update_app($app,$app_id);       
	}
	die();
}
function wpas_list_all()
{
	$page  =1;
	$type = $_GET['type'];
	$app_id = $_GET['app_id'];
	$list = Array();
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	}
	if(isset($type) && isset($app_id)) 
	{
		$app = wpas_get_app($app_id);
		if(isset($app[$type]))
		{
			$list = $app[$type];
		}
		echo wpas_list($type,$list,$app_id,$app['app_name'],$page);
	}
	die();

}
function wpas_save_option_form()
{
	check_ajax_referer('save_option_form_nonce','nonce');
	$post_array = explode("&", stripslashes($_POST['form']));
	$app_id = $_POST['app_id'];
	$app = wpas_get_app($app_id);
	$search_str = "ao_";
	$comp = Array();
	foreach($post_array as $mypost)
	{
		$comp_form = explode("=",$mypost);
		$pos = strpos($comp_form[0],$search_str);
		$comp_form_value = urldecode(str_replace($comp_form[0]."=","",$mypost));
		if($pos !== false && $comp_form_value != "")
		{
			$comp[$comp_form[0]] = $comp_form_value;
		}
	}
	
	if(!isset($comp['ao_blog_name']))
	{
		$comp['ao_blog_name'] = "My " . $comp['ao_domain'] . " Site";
	}
	if(!isset($comp['ao_app_version']))
	{
		$comp['ao_app_version'] = "1.0.0";
	}
	if(!isset($comp['ao_author']))
	{
		$comp['ao_author'] = $comp['ao_domain'] . " Owner";
	}
	if(!isset($comp['ao_author_url']))
	{
		$comp['ao_author_url'] = "http://" . $comp['ao_domain'] ;
	}
	if(!isset($comp['ao_license_text']))
	{
		$comp['ao_license_text'] = "This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA";
	}
	$comp['date'] = date("Y-m-d H:i:s");
	$comp['modified_date'] = date("Y-m-d H:i:s");
	
	$app['option'] = $comp;
	wpas_update_app($app,$app_id);
	echo json_encode($comp);
	die();
}
	function wpas_save_form()
	{
		check_ajax_referer('save_form_nonce','nonce');
		$help_txnlist = 0;
		$post_array = explode("&", stripslashes($_POST['form']));
		$type = $_POST['type'];
		$app_id = $_POST['app_id'];
		$app = wpas_get_app($app_id);
		if($type == 'entity')
		{
			$search_str = "ent-";
		}
		elseif($type == 'taxonomy')
		{
			$search_str = "txn-";
		}
		elseif($type == 'option')
		{
			$search_str = "ao_";
		}
		elseif($type == 'relationship')
		{
			$search_str = "rel-";
		}
		elseif($type == 'help')
		{
			$search_str = "help-";
		}
		elseif($type == 'shortcode')
		{
			$search_str = "shc-";
		}
		elseif($type == 'widget')
		{
			$search_str = "widg-";
		}
		elseif($type == 'role')
		{
			$search_str = "role-";
		}
		$comp_type = $type;
		$comp = Array();
		foreach($post_array as $mypost)
		{
			$comp_form = explode("=",$mypost);
			$pos = strpos($comp_form[0],$search_str);
			$comp_form_value = urldecode(str_replace($comp_form[0]."=","",$mypost));
			if($pos !== false && $comp_form_value != "")
			{
				if($comp_form[0] == 'ent-name' || $comp_form[0] == 'txn-name' || $comp_form[0] == 'role-name' || $comp_form[0] == 'shc-label')
				{
					$comp[$comp_form[0]] = strtolower($comp_form_value);
				}
				elseif($comp_form[0] == 'ent-label' || $comp_form[0] == 'ent-singular-label' || $comp_form[0] == 'txn-label' || $comp_form[0] == 'txn-singular-label')
				{
					$comp[$comp_form[0]] = ucwords(strtolower($comp_form_value));
				}
				elseif($comp_form[0] == 'txn-attach')
				{
					$comp[$comp_form[0]][] = $comp_form_value;
				}
				else
				{
					if($type == 'help' && preg_match("/^txn-/",$comp_form_value))
					{
						$comp_form_value = str_replace("txn-","",$comp_form_value);
						$help_txnlist = 1;
					}

					if(isset($comp[$comp_form[0]]))
					{
						$comp[$comp_form[0]] = Array($comp[$comp_form[0]],$comp_form_value);
					}
					else
					{
						$comp[$comp_form[0]] = $comp_form_value;
					}
				}
			}
		}
		if($help_txnlist == 1)
		{
			$comp['help-screen_type'] = 'edit';
		}
		$comp['date'] = date("Y-m-d H:i:s");
		$comp['modified_date'] = date("Y-m-d H:i:s");
		if(!empty($app[$comp_type]))
		{
			$comp_id = max(array_keys($app[$comp_type])) +1;
		}
		else
		{
			$comp_id = 0;
		}
		if($comp_type == 'entity' && $comp['ent-capability_type'] != 'post')
		{
			$label = $comp['ent-name'];
			$label = strtolower($label);
			$label = $label . "s";

			$admin_new_entity_arr = wpas_admin_entity($label);
			$app['role'][0] = array_merge($admin_new_entity_arr,$app['role'][0]);
		}
		if($comp_type == 'taxonomy')
		{
			$label = str_replace(" ","_",$comp['txn-label']);
			$label = strtolower($label);
			$admin_new_tax_arr = wpas_admin_taxonomy($label);
			$app['role'][0] = array_merge($admin_new_tax_arr,$app['role'][0]);
		}
		if($comp_type == 'widget')
		{
			if($comp['widg-type'] == 'dashboard')
			{	
				$label = str_replace(" ","_",$comp['widg-title']);
				$label = strtolower($label);
				$label = $comp['widg-dash_subtype'] . "_" . $label;
				$admin_new_widg_arr = wpas_admin_widget($label);
				$app['role'][0] = array_merge($admin_new_widg_arr,$app['role'][0]);
			}
		}
		$app[$comp_type][$comp_id] = $comp;
		echo wpas_list($type,$app[$comp_type],$app_id,$app['app_name'],1);
		wpas_update_app($app,$app_id);
		die();
	}
	function wpas_update_form()
	{
		check_ajax_referer('update_form_nonce','nonce');
		$post_array = explode("&", stripslashes($_POST['form']));
		$type = $_POST['type'];
		$app_id = $_POST['app_id'];
		$app = wpas_get_app($app_id);
		if($type == 'entity')
		{
			$search_str = "ent-";
		}
		elseif($type == 'taxonomy')
		{
			$search_str = "txn-";
		}
		elseif($type == 'relationship')
		{
			$search_str = "rel-";
		}
		elseif($type == 'help')
		{
			$search_str = "help-";
		}
		elseif($type == 'shortcode')
		{
			$search_str = "shc-";
		}
		elseif($type == 'widget')
		{
			$search_str = "widg-";
		}
		elseif($type == 'role')
		{
			$search_str = "role-";
		}
		$comp_type = $type;
		$comp = Array();
		$types = Array('ent','txn','rel','help','shc','widget','role');
		foreach($post_array as $mypost)
		{
			$comp_form = explode("=",$mypost);

			if(in_array($comp_form[0],$types))
			{
				$comp_id = $comp_form[1];
			}
			$pos = strpos($comp_form[0],$search_str);
			$comp_form_value = urldecode(str_replace($comp_form[0]."=","",$mypost));
			if($pos !== false && $comp_form_value != "")
			{
				if($comp_form[0] == 'ent-name' || $comp_form[0] == 'txn-name' || $comp_form[0] == 'role-name' || $comp_form[0] == 'shc-label')
				{
					$comp[$comp_form[0]] = strtolower($comp_form_value);
				}
				elseif($comp_form[0] == 'ent-label' || $comp_form[0] == 'ent-singular-label' || $comp_form[0] == 'txn-label' || $comp_form[0] == 'txn-singular-label')
				{
					$comp[$comp_form[0]] = ucwords(strtolower($comp_form_value));
				}
				elseif($comp_form[0] == 'txn-attach')
				{
					$comp[$comp_form[0]][] = $comp_form_value;
				}
				elseif($comp_form[0] == 'help-object_name')
				{
					$comp_form_value = str_replace("txn-","",$comp_form_value);
					$comp[$comp_form[0]] = $comp_form_value;
				}
				else
				{
					$comp[$comp_form[0]] = $comp_form_value;
				}
			}
		}
		$comp['date'] = $app[$comp_type][$comp_id]['date'];
		$comp['modified_date'] = date("Y-m-d H:i:s");
		if(isset($app[$comp_type][$comp_id]['field']))
		{
			$fields = $app[$comp_type][$comp_id]['field'];
			$comp['field'] = $fields;
		}
		if($comp_type == 'entity')
		{
			if(isset($app[$comp_type][$comp_id]['layout']))
			{	
				$layout = $app[$comp_type][$comp_id]['layout'];
				if(is_array($layout))
				{
					$comp['layout'] = $layout;
				}
			}
			$ent_name = $comp['ent-name'];
			$ent_label = $comp['ent-label'];
			$ent_old_label = $app['entity'][$comp_id]['ent-label'];
			$ent_old_name = $app['entity'][$comp_id]['ent-name'];

			if($ent_label != $ent_old_label)
			{
				//after updating entity update all attached objects
				//1- update the taxonomy
				if(isset($app['taxonomy']))
				{
					foreach($app['taxonomy'] as $tkey => $mytaxonomy)
					{
						foreach($mytaxonomy['txn-attach'] as $tattachkey => $txn_attach)
						{	
							if($txn_attach == $ent_old_label)
							{
								$app['taxonomy'][$tkey]['txn-attach'][$tattachkey] = $ent_label;
							}
						}
					}
				}
				//2-update the relationships
				if(isset($app['relationship']))
				{
					foreach($app['relationship'] as $rkey => $myrelationship)
					{
						if($myrelationship['rel-from-name'] == $ent_old_label)
						{
							$app['relationship'][$rkey]['rel-from-name'] = $ent_label;
						}
						if($myrelationship['rel-to-name'] == $ent_old_label)
						{
							$app['relationship'][$rkey]['rel-to-name'] = $ent_label;
						}
					}
				}
				//3 -update the shortcodes
				if(isset($app['shortcode']))
				{
					foreach($app['shortcode'] as $skey => $myshortcode)
					{
						if($myshortcode['shc-attach'] == $ent_old_label)
						{
							$app['shortcode'][$skey]['shc-attach'] = $ent_label;
						}
					}
				}
				//4 -update the widget
				if(isset($app['widget']))
				{
					foreach($app['widget'] as $skey => $mywidget)
					{
						if(isset($mywidget['widg-attach']) &&  $mywidget['widg-attach'] == $ent_old_label)
						{
							$app['widget'][$skey]['widg-attach'] = $ent_label;
						}
					}
				}
				//5 - update the helps
				if(isset($app['help']))
				{
					foreach($app['help'] as $hkey => $myhelp)
					{
						if($myhelp['help-object_name'] == $ent_old_label)
						{
							$app['help'][$hkey]['help-object_name']= $ent_label;
						}
					}
				}
			}
			if($ent_name != $ent_old_name)
			{
				//5- update the entity capabilities for each role
				if(isset($app['role']))
				{
					foreach($app['role'] as $rkey => $myrole)
					{
						$label = strtolower($ent_old_name);
						$label = $label . "s";
						$label = 'emd_' . $label;
						$pattern = '/' . $label . '/';
						//update cappabilities for that entity
						foreach($myrole as $role_name => $role_value)
						{
							if(preg_match($pattern,$role_name))
							{
								$new_label = "emd_" . strtolower($ent_name) . "s";
								$new_role_name = str_replace($label,$new_label,$role_name);
								unset($app['role'][$rkey][$role_name]);
								$app['role'][$rkey][$new_role_name] = $role_value;
							}
						}
					}
				}
			}
		}
		elseif($comp_type == 'taxonomy')
		{
			$txn_label = $comp['txn-label'];
			$txn_old_label = $app['taxonomy'][$comp_id]['txn-label'];
			if($txn_old_label != $txn_label)
			{
				//1 check if help has this taxonomy, then update those help
				if(isset($app['help']))
				{
					foreach($app['help'] as $hkey => $myhelp)
					{
						if($myhelp['help-object_name'] == $txn_old_label)
						{
							$app['help'][$hkey]['help-object_name'] =  $txn_label;
						}
					}
				}
				//2- update the taxonomy capabilities for each role
				foreach($app['role'] as $rkey => $myrole)
				{
					$label = $txn_old_label;
					$label = str_replace(" ","_",$label);
					$label = strtolower($label);
					$pattern = '/' . $label . '/';
					$new_label = str_replace(" ","_",$txn_label);
					$new_label = strtolower($new_label);
					
					foreach($myrole as $role_name => $role_value)
					{
						if(preg_match($pattern,$role_name))
						{
							$new_role_name = str_replace($label,$new_label,$role_name);
							unset($app['role'][$rkey][$role_name]);
							$app['role'][$rkey][$new_role_name] = $role_value;
						}
					}
				}
			}
		}
		elseif($comp_type == 'widget')
		{
			if($comp['widg-type'] == 'dashboard')
			{
				$widg_title = $comp['widg-title'];
				$widg_old_title = $app['widget'][$comp_id]['widg-title'];
				if($widg_old_title != $widg_title)
				{
					//1- update the widget capabilities for each role
					foreach($app['role'] as $rkey => $myrole)
					{
						$label = $widg_old_title;
						$label = str_replace(" ","_",$label);
						$label = strtolower($label);
						$label = $app['widget'][$comp_id]['widg-dash_subtype'] . "_" . $label;
						$pattern = '/' . $label . '/';
						$new_label = str_replace(" ","_",$widg_title);
						$new_label = strtolower($new_label);
						$new_label = $comp['widg-dash_subtype'] . "_" . $new_label;
						
						foreach($myrole as $role_name => $role_value)
						{
							if(preg_match($pattern,$role_name))
							{
								$new_role_name = str_replace($label,$new_label,$role_name);
								unset($app['role'][$rkey][$role_name]);
								$app['role'][$rkey][$new_role_name] = $role_value;
							}
						}
					}
				}
			}
		}
		elseif($comp_type == 'role')
		{
			if(!isset($comp['role-name']))
			{
				$comp['role-name'] = $app[$comp_type][$comp_id]['role-name'];
			}
			if(!isset($comp['role-label']))
			{
				$comp['role-label'] = $app[$comp_type][$comp_id]['role-label'];
			}
		}
		$app[$comp_type][$comp_id] = $comp;
		wpas_update_app($app,$app_id);
		echo wpas_list($type,$app[$comp_type],$app_id,$app['app_name'],1);
		die();
	}
?>
