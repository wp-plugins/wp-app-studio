<?php

add_action( 'admin_init', 'wpas_export');
add_action( 'admin_init', 'wpas_duplicate');

function wpas_is_allowed()
{
	//for now wpas only is allowed to administrator , will add a role and cap next version
	if (!current_user_can('design_wpas'))
	{
		wp_die(__("You do not have permission to access.","wpas"));
	}
}
function wpas_remote_request($method,$fields)
{
	$args = array(
		'body' => $fields
	);

	if($method == 'check_status')
	{
		$url = WPAS_SSL_URL . "/check_status.php";
	}
	elseif($method == 'check' || $method == 'generate')
	{
		$url = WPAS_SSL_URL . "/check_reg.php";
	}
	$resp = wp_remote_post($url,$args);
	if($resp && !is_wp_error($resp) && $resp['response']['code'] == 200) 
	{
		if(isset($resp['body']) && strlen($resp['body']) > 0)
		{
			libxml_use_internal_errors(true);
			$xml_resp = simplexml_load_string($resp['body']);
			if(count(libxml_get_errors()) == 0 && $xml_resp !== false)
			{
				return $xml_resp;
			} 
		}
	}
	return false;
}
function wpas_duplicate()
{
        if(isset($_GET['duplicate']) && $_GET['duplicate'] == 1 && !empty($_GET['app']))
        {
		wpas_is_allowed();
		check_admin_referer('wpas_duplicate');
		$app = wpas_get_app($_GET['app']);
		if($app !== false && !empty($app))
		{
			$app['app_name'] = $app['app_name'] . " Copy";
			$app_key = uniqid('',true);
			$app['app_id'] = $app_key;
			wpas_update_app($app,$app_key,'new');
		}
        }
}
function wpas_export()
{
        if(isset($_GET['export']) && $_GET['export'] == 1 && !empty($_GET['app']))
        {
		wpas_is_allowed();
		check_admin_referer('wpas_export');
		$app = wpas_get_app($_GET['app']);
		if($app !== false && !empty($app))
		{
			$now = date("Y-m-d-H-i-s");
			$fileName = sanitize_file_name($app['app_name']. "-" . $now .'.wpas');
			$output = gzdeflate(base64_encode(serialize($app)),9);
			header("Expires: Mon, 21 Nov 1997 05:00:00 GMT");    // Date in the past
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
			header("Pragma: no-cache");                          // HTTP/1.0
			header('Content-type: application/wpas');
			header('Content-Disposition: attachment; filename='.$fileName);
			header('Content-Description: File Transfer' );
			print($output);
			exit;
		}
        }
}
function wpas_import_app($app_new)
{
	$status = "";
        echo '<div class="wpas">';
        wpas_branding_header();
        if(isset($_FILES['wpasimport']) && !empty($_FILES['wpasimport']['size']))
	{
		check_admin_referer('wpas_import_file','wpas_import_nonce');
                $overrides = array('action'=>'wpas_import', 'mimes'=> array('wpas' => 'application/wpas'),'test_form' => false);
                $upload = wp_handle_upload($_FILES['wpasimport'], $overrides);
		if(!empty($upload['error']))
                {
                        echo wpas_import_page($upload['error']);
                }
                elseif(isset($upload['file']) && !empty($upload['file']))
                {
                        $data = file_get_contents($upload['file']);
			$inflated_data = @gzinflate($data);
			if($inflated_data !== false)
			{
                        	$data = unserialize(base64_decode($inflated_data));
				if(!isset($data['ver']) || $data['ver'] != WPAS_DATA_VERSION)
				{
					$data = wpas_update_data_arr($data['app_id'],$data);
				}

			}
			else
			{
				$status = "error_data";
			}
			if($status != "error_data" && !empty($data) && !empty($data['app_name']) && !empty($data['app_id']) && strtotime($data['modified_date'] ))
			{
				$status = "new";
				if(!empty($app_new))
				{
					foreach($app_new as $app_key => $app_value)
					{
						if($data['app_id'] == $app_key)
						{
							if($data['modified_date'] == $app_value['modified_date'])
							{
								$status = "dupe";
								break;
							}
							else
							{
						
								$status = "dupe_diff_date";
								break;
							}
						}
						elseif($data['app_name'] == $app_value['app_name'])
						{
							$status = "new_with_date";
							break;
						}
					}
				}
			}
			else
			{
				$status = "error_data";
			}
			switch ($status) {
				case 'dupe':
					echo wpas_import_page('dupe');
					break;
				case 'dupe_diff_date':
					//overwrite
					wpas_update_app($data,$data['app_id'],'dupe');
					echo wpas_import_page('dupe_diff_date');
					break;
				case 'new_with_date':
					//overwrite
					wpas_update_app($data,$data['app_id'],'new_with_date');
					echo wpas_import_page('dupe_name');
					break;
				case 'new':
					wpas_update_app($data,$data['app_id'],'new');
					echo wpas_import_page('success');
					break;
				case 'error_data':
					echo wpas_import_page('error_data');
					break;
				default:
                			echo wpas_import_page();
					break;
			}
                }
        }
        else
        {
                echo wpas_import_page();
        }
        wpas_branding_footer();
        echo '</div>';
}
function wpas_generate_app()
{
        $alert = "";
	$success = 0;
	$entity_count = 0;
	$submits = Array();
	$app_reg = Array();
	if(get_option('wpas_passcode_email') !== false)
	{
        	$app_reg = unserialize(get_option('wpas_passcode_email'));
	}
	if(get_option('wpas_apps_submit') !== false)
	{
        	$submits = unserialize(get_option('wpas_apps_submit'));
	}
	
	if(!empty($_POST) && (empty($_POST['email']) || !is_email($_POST['email'])))
	{
		$alert = __("Error: Please enter valid email.","wpas");
		$success = 0;
	}
	elseif(!empty($_POST) && empty($_POST['passcode']))
	{
		$alert = __("Error: Please enter passcode.","wpas");
		$success = 0;
	}
	elseif(!empty($_POST) && (empty($_POST['app']) || wpas_get_app(sanitize_text_field($_POST['app'])) === false))
	{
		$alert = __("Error: Please select an application to generate.","wpas");
		$success = 0;
	}
        elseif(!empty($_POST))
        {
		check_admin_referer('wpas_generate_app','wpas_generate_nonce');
		$passcode = sanitize_text_field($_POST['passcode']);
		$email = sanitize_email($_POST['email']); 
		$app_key = sanitize_text_field($_POST['app']);
		if(isset($_POST['save_passcode']) && $_POST['save_passcode'] == 1)
		{
                        $app_reg = Array('passcode' => $passcode,
					'email' => $email);
                        update_option('wpas_passcode_email',serialize($app_reg));
		}

		$myapp = wpas_get_app($app_key);
	
		if($myapp !== false && !empty($myapp))
		{
			//if app is ready to generate , alert will be empty
			$alert = wpas_check_valid_generate($myapp);
			if(empty($alert))
			{
				$entity_count = wpas_get_entity_count($myapp);
				$postfields = array(
						'passcode' => $passcode,
						'email' => $email,
						'method' => 'check',
						'entity_count' =>  $entity_count,
						'app_id' => $app_key,
						);
			
				$xml_check = wpas_remote_request('check',$postfields); 
	

				if($xml_check === false)
				{
					$alert = __("Error: System error, please try again later.","wpas");
					$success = 0;
				}
				elseif(isset($xml_check->error))
				{
					$alert = __("Error:","wpas") . " " . (string) $xml_check->errormsg;
					$success = 0;
				}
				elseif(isset($xml_check->success))
				{
					$token = (string) $xml_check->token;
					$myfields = array(
						'token' => $token,
						'method' => 'generate',
						'type' => (string) $xml_check->success,
						'app' => gzdeflate(base64_encode(serialize($myapp))),
						);
					$xml_generate = wpas_remote_request('generate',$myfields); 
		
					if($xml_generate === false)
					{
						$alert = __("Error: System error, please try again later.","wpas");
						$success = 0;
					}
					elseif(isset($xml_generate->error))
					{
						$alert = __("Error:","wpas") . " " . (string) $xml_generate->errormsg;
						$success = 0;
					}
					elseif(isset($xml_generate->success))
					{
						$success = 1;
						if(isset($xml_generate->queue_id))
						{
							$queue_id = sanitize_text_field($xml_generate->queue_id);
							$new_submit['credit_used']= intval ($xml_generate->credit_used);
							$new_submit['credit_left']= intval ($xml_generate->credit_left);
							$new_submit['update_left']= intval ($xml_generate->update_left);
							$new_submit['app_submitted']= $myapp['app_name'];
							$new_submit['date_submit']= date("Y-m-d H:i:s");
							$new_submit['queue_id']= $queue_id;
							$new_submit['status'] = '<a id="check-status" class="btn btn-info btn-mini" href="#'. esc_attr($queue_id) . '">' . __("Refresh","wpas") . '</a>';
							$new_submit['status_link'] = '';
							$new_submit['refresh_time'] = time();
							$submits[] = $new_submit;
							update_option('wpas_apps_submit',serialize($submits));
						}
					}
				}
			}
        	}
	}
	
        echo '<div class="wpas">';
        wpas_branding_header();
        echo wpas_generate_page($app_reg,$submits,$alert,$success);
        wpas_branding_footer();
        echo '</div>';
}
function wpas_check_valid_generate($myapp)
{
	$generate_error = 0;
	$resp_error = Array();
	$error_loc_name = "";
	//generate_errors , error_loc_name
	// 1: no_setting
	// 2: no_entity
	// 3: no attribute in an entity
	// 4: emtpy panel in layout
	// 5: not all attributes assigned to layout
	// 6: help with no tab  
	// 7: no unique key in entity
	// 8: search form layout empty
	// 9: search form not linked to any view
	// 10: forms not attached to an entity


	if(!isset($myapp['option']) || empty($myapp['option']))
	{
		$generate_error = 1;
	}
	elseif(!empty($myapp['entity']))
	{
		//check if entities have at least one field
		$resp_error = wpas_check_entity_field($myapp['entity']);
		$generate_error = $resp_error['generate_error'];
		$error_loc_name = $resp_error['error_loc_name'];
	}
	if($generate_error == 0 && !empty($myapp['form']))
	{	
		$resp_error = wpas_check_search_form($myapp);
		$generate_error = $resp_error['generate_error'];
		$error_loc_name = $resp_error['error_loc_name'];
		if($generate_error == 0)
		{
			$resp_error = wpas_check_form_rel_uniq($myapp);
			$generate_error = $resp_error['generate_error'];
			$error_loc_name = $resp_error['error_loc_name'];
		}
	}
	if($generate_error == 0 && !empty($myapp['help']))
	{
		foreach($myapp['help'] as $myhelp)
		{
			if(!isset($myhelp['field']) || empty($myhelp['field']))
			{
				$generate_error = 6;
				if(isset($myhelp['help-entity']))
				{
					$error_loc_name = $myapp['entity'][$myhelp['help-entity']]['ent-label'];
				}
				elseif(isset($myhelp['help-tax']))
				{
					$error_loc_name = $myapp['taxonomy'][$myhelp['help-tax']]['txn-label'];
				}
				break;
			}
		}
	}
	switch ($generate_error)
	{
		case 1:
			$alert = __("Error: You must fill out the domain name field in the application's settings app info tab.","wpas");
			break;
		case 2:
			$alert = __("Error: You must have at least one entity and each entity must have at least one attribute.","wpas");
			break;
		case 3:
			$alert = __("Error: You must have at least one entity and each entity must have at least one attribute. Please add at least one attribute to:","wpas") . " " . $error_loc_name;
			break;
		case 4:
			$alert = sprintf(__('Error: You must have at least one attribute in each panel in %s entity layout.','wpas'),$error_loc_name);
			break;
		case 5:
			$alert = sprintf(__('Error: You must assign all attributes to a panel in %s entity layout.','wpas'),$error_loc_name);
			break;
		case 6:
			$alert = __("Error: You must assign tabs to the help attached to:","wpas") . " " . $error_loc_name;
			break;
		case 7:
			$alert = sprintf(__('Error: You must have at least one unique attribute in each entity. Please set at least one attribute unique in %s entity.','wpas'),$error_loc_name);
			break;
		case 8:
			$alert = sprintf(__('Error: You must have a form layout for %s search form.','wpas'),$error_loc_name);
			break;
		case 9:
			$alert = sprintf(__('Error: You must have a view attached for %s search form.','wpas'),$error_loc_name);
			break;
		case 10:
			$alert = sprintf(__('Error: You must have an entity attached for %s form.','wpas'),$error_loc_name);
			break;
		default:
			$alert = "";
			break;
	}
	return $alert;	
}
function wpas_check_form_rel_uniq($myapp)
{
	$generate_error = 0;
	$error_loc_name = "";
	foreach($myapp['form'] as $myform)
	{
		$myent_ids = Array();
		if(!empty($myform['form-dependents']))
		{
			if(!empty($myform['form-layout']))
			{
				foreach($myform['form-layout'] as $myform_layout)
				{
					foreach($myform_layout as $myitem)
					{
						if(isset($myitem['relent']) && $myitem['obtype'] == 'relent')
						{
							if($myitem['entity'] == $myapp['relationship'][$myitem['relent']]['rel-from-name'] && $myapp['relationship'][$myitem['relent']]['rel-to-name'] != 'user')
							{	
								$myent_ids[] = $myapp['relationship'][$myitem['relent']]['rel-to-name'];
							}
							elseif($myitem['entity'] == $myapp['relationship'][$myitem['relent']]['rel-to-name'] && $myapp['relationship'][$myitem['relent']]['rel-from-name'] != 'user')
							{
								$myent_ids[] = $myapp['relationship'][$myitem['relent']]['rel-from-name'];
							}
						}
					}
				}
			}
			else
			{
				foreach($myform['form-dependents'] as $rel_dep)
				{
					$myrel = $myapp['relationship'][$rel_dep];
					if($myform['form-attached_entity'] == $myrel['rel-from-name'] && $myrel['rel-to-name'] != 'user')
					{
						$myent_ids[] = $myrel['rel-to-name'];
					}
					else if($myrel['rel-from-name'] != 'user')
					{
						$myent_ids[] = $myrel['rel-from-name'];
					}
					break;
				}
					
			}
			$unique_key = 0;
			foreach($myent_ids as $ent_id)
			{
				foreach($myapp['entity'][$ent_id]['field'] as $myfields)
				{
					if(isset($myfields['fld_uniq_id']) && $myfields['fld_uniq_id'] == 1)
					{
						$unique_key = 1;
						break;
					}
				}
				if($unique_key == 0)
				{
					$generate_error = 7;
					$error_loc_name = $myapp['entity'][$ent_id]['ent-label'];
					return Array('generate_error' => $generate_error, 'error_loc_name' => $error_loc_name);
				}
			}
		}
	}
	return Array('generate_error' => $generate_error, 'error_loc_name' => $error_loc_name);
}
function wpas_check_entity_field($myapp_entity)
{
	$no_post = 0;
	$no_page = 0;
	$check_field = 0;
	$has_entity = 0;
	$empty_panel = 0;
	$attrs_left = 0;
	$generate_error = 0;
	$error_loc_name = "";
	
	//check if entities have at least one field
	foreach($myapp_entity as $myentity)
	{
		$ent_attr_count = 0;
		$layout_attr_count =0;
		if($myentity['ent-name'] == 'post' && !empty($myentity['field']))
		{
			$no_post = 1;
		}
		elseif($myentity['ent-name'] == 'page' && !empty($myentity['field']))
		{
			$no_page = 1;
		}
		elseif(!in_array($myentity['ent-name'],Array('page','post')) && empty($myentity['field']))
		{
			$error_loc_name = $myentity['ent-label'];
			$check_field = 1;
			break;
		}
		elseif(!in_array($myentity['ent-name'],Array('page','post')))
		{
			$has_entity = 1;
		}

		//check if layout has empty tabs or empty acc
		if(!empty($myentity['layout']))
		{
			foreach($myentity['layout'] as $mylayout)
			{
				if(!empty($mylayout['tabs']))
				{
					$resp_layout = wpas_check_layout_attr($mylayout['tabs']);
					if($resp_layout === false)
					{
						$error_loc_name = $myentity['ent-label'];
						$empty_panel = 1;
						break;
					}
					else
					{
						$layout_attr_count += $resp_layout;	
					}
				}
				if(!empty($mylayout['accs']))
				{
					$resp_layout = wpas_check_layout_attr($mylayout['accs']);
					if($resp_layout === false)
					{
						$error_loc_name = $myentity['ent-label'];
						$empty_panel = 1;
						break;
					}
					else
					{
						$layout_attr_count += $resp_layout;	
					}
				}
			}
		}
		if(isset($myentity['field']))
		{
			foreach($myentity['field'] as $myfield)
			{
				if(!isset($myfield['fld_builtin']) || $myfield['fld_builtin'] == 0)
				{
					$ent_attr_count++;
				}
			}
		}
		if(!in_array($myentity['ent-name'],Array('page','post')))
		{
			$error_loc_name = $myentity['ent-label'];
		}
		if(!empty($myentity['layout']) && $layout_attr_count < $ent_attr_count)
		{
			$attrs_left = 1;
			$error_loc_name = $myentity['ent-label'];
			break;
		}
	}
	if($check_field == 0 && $no_post == 0 && $no_page == 0 && $has_entity == 0)
	{
		$generate_error = 2;	
	}
	elseif($check_field == 1)
	{
		$generate_error = 3;	
	}
	elseif($empty_panel == 1)
	{
		$generate_error = 4;
	}
	elseif($attrs_left == 1)
	{
		$generate_error = 5;
	}
	return Array('generate_error' => $generate_error, 'error_loc_name' => $error_loc_name);
}
function wpas_get_entity_count($myapp)
{
	$entity_count = 0;
	foreach($myapp['entity'] as $myentity)
	{
		if(!empty($myentity['field']))
		{
			$entity_count ++;
		}
	}
	return $entity_count;
}
function wpas_check_layout_attr($mylayout_panel)
{
	$layout_attr_count = 0;
	foreach($mylayout_panel as $mypanel)
	{
		if(empty($mypanel['attr']))
		{
			return false;
		}
		else
		{
			$layout_attr_count = $layout_attr_count + count($mypanel['attr']);
		}
	}
	return $layout_attr_count;
}
function wpas_check_search_form($myapp)
{
	$generate_error = 0;
	$error_loc_name = "";
	foreach($myapp['form'] as $keyform => $myform)
	{
		if($myform['form-attached_entity'] == '' || is_null($myform['form-attached_entity']))
		{
			$generate_error = 10;
			$error_loc_name = $myform['form-name'];
			break;
		}
		$form_linked = 0;
		if($myform['form-form_type'] == 'search' && empty($myform['form-layout']))
		{
			$generate_error = 8;
			$error_loc_name = $myform['form-name'];
			break;
		}
		elseif($myform['form-form_type'] == 'search')
		{
			if(!empty($myapp['shortcode']))
			{
				foreach($myapp['shortcode'] as $myview)
				{
					if($myview['shc-view_type'] == 'search' && $myview['shc-attach_form'] == $keyform)
					{
						$form_linked = 1;
					}
				}
			}
			if($form_linked == 0)
			{
				$generate_error = 9;
				$error_loc_name = $myform['form-name'];
				break;
			}
		}
	}
	return Array('generate_error' => $generate_error, 'error_loc_name' => $error_loc_name);
}
?>
