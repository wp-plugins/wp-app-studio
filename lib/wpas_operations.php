<?php

add_action( 'admin_init', 'wpas_export');

function wpas_curl_request($method,$fields)
{
	if($method == 'check_status')
	{
		$url = WPAS_SSL_URL . "/check_status.php";
	}
	else
	{
		$url = WPAS_SSL_URL . "/check_reg.php";
	}
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	if($method == 'generate')
	{
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type:multipart/form-data'));
	}
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_POST, 1);
	curl_setopt($c, CURLOPT_POSTFIELDS, $fields);
	$result = curl_exec($c);
	curl_close($c);
	if($result)
	{
		$xml = simplexml_load_string($result);
		return $xml;
	}
	return false;
}


function wpas_export(){
        if(isset($_GET['export']) && $_GET['export'] == 1 && isset($_GET['app']))
        {
                $app_key = $_GET['app'];
		$app = wpas_get_app($app_key);
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

function wpas_import_app($app_new)
{
	$status = "";
        echo '<div class="wpas">';
        wpas_branding_header();
        if(!empty($_FILES['wpasimport']['size'])){
                $overrides = array('action'=>'wpas_import', 'mimes'=> array('wpas' => 'application/wpas'),'test_form' => false);
                $_POST['action'] = 'wpas_import';
                $upload = wp_handle_upload($_FILES['wpasimport'], $overrides);
                if(isset($upload['error']))
                {
                        echo wpas_import_page($upload['error']);
                }
                elseif(!empty($upload['file']))
                {
                        $data = file_get_contents($upload['file']);
                        $data = unserialize(base64_decode(gzinflate($data)));
                        foreach($app_new as $app_value)
                        {
                                if($data['app_name'] == $app_value['app_name'])
                                {
					if($data['modified_date'] == $app_value['modified_date'])
					{
						$status = "dupe";
						break;
					}
					else
					{
						$status = "dupe_diff_date";
					}
                                }
                        }
			if($status == "dupe")
			{
				echo wpas_import_page('dupe');
				exit;
			}
			elseif($status == "dupe_diff_date")
			{
                        	echo wpas_import_page('dupe_diff_date');
			}
			else
			{
                        	echo wpas_import_page('success');
			}
			wpas_update_app($data,$data['app_id'],'new');
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
        $app_reg = unserialize(get_option('wpas_passcode_email'));
        $submits = unserialize(get_option("wpas_apps_submit"));

        if(isset($_POST['passcode']))
        {
		if(isset($_POST['save_passcode']) && $_POST['save_passcode'] == 1)
		{
                        $app_reg = Array('passcode'=>$_POST['passcode'],'email'=>$_POST['email']);
                        update_option('wpas_passcode_email',serialize($app_reg));
		}
		$myapp_id = $_POST['app'];
                $myapp = wpas_get_app($myapp_id);
		$check_field = 0;
		$no_setting = 0;
		$has_entity = 0;
		$empty_panel = 0;
		$empty_help = 0;
		$attrs_left = 0;
		$fields_string = "";


		if(!isset($myapp['option']) || empty($myapp['option']))
		{
			$no_setting = 1;
		}
		else
		{
			//check if entities have at least one field
			foreach($myapp['entity'] as $myentity)
			{
				if($myentity['ent-name'] == 'post')
				{
					if(!empty($myentity['field']))
					{
						$entity_count++;
						$no_post = 1;
					}
					else
					{
						$no_post = 0;
					}
				}
				elseif($myentity['ent-name'] == 'page')
				{
					if(!empty($myentity['field']))
					{
						$entity_count++;
						$no_page = 1;
					}
					else
					{
						$no_page = 0;
					}
				}
				else
				{
					if(empty($myentity['field']))
					{
						$ent_name = $myentity['ent-label'];
						$check_field = 1;
						break;
					}
					$entity_count++;
					$has_entity = 1;
				}
				
				$layout_attr_count =0;
				//check if layout has empty tabs or empty acc
				if(isset($myentity['layout']))
				{
					foreach($myentity['layout'] as $mylayout)
					{
						if(isset($mylayout['tabs']))
						{
							foreach($mylayout['tabs'] as $mytab)
							{
								if($mytab['attr'] == "")
								{
									$empty_panel_ent_name = $myentity['ent-label'];
									$empty_panel = 1;
									break;
								}
								else
								{
									$my_attrs = explode(",",$mytab['attr']);
									$layout_attr_count = $layout_attr_count + count($my_attrs);
								}
							}
						}
						if(isset($mylayout['accs']))
						{
							foreach($mylayout['accs'] as $myacc)
							{
								if($myacc['attr'] == "")
								{
									$empty_panel_ent_name = $myentity['ent-label'];
									$empty_panel = 1;
									break;
								}
								else
								{
									$my_attrs = explode(",",$myacc['attr']);
									$layout_attr_count = $layout_attr_count + count($my_attrs);
								}
							}
						}	
					}
				}
				if(isset($myentity['field']))
				{
					$ent_attr_count = count($myentity['field']);
				}
				if(!empty($myentity['layout']) && $layout_attr_count < $ent_attr_count)
				{
					$attrs_left = 1;
					$empty_panel_ent_name = $myentity['ent-label'];
					break;
				}
			}
			if(isset($myapp['help']))
			{
				foreach($myapp['help'] as $myhelp)
				{
					if(!is_array($myhelp['field']) && empty($myhelp['field']))
					{
						$empty_help = 1;
						$empty_help_name = $myhelp['help-object_name'];
						break;
					}
				}
			}
		}

		if($no_setting == 1)
		{
                        $alert = "Error: You must fill out the domain name field in the application's settings app info tab.";
			$success = 0;
		}
		elseif($check_field == 0 && $no_post == 0 && $no_page == 0 && $has_entity == 0)
		{
                        $alert = "Error: You must have at least one entity and each entity must have at least one attribute.";
			$success = 0;
		}
		elseif($check_field == 1)
		{
                        $alert = "Error: You must have at least one entity and each entity must have at least one attribute. Please add at least one attribute to: " . $ent_name;
			$success = 0;
		}
		elseif($empty_panel == 1)
		{
                        $alert = "Error: You must have at least one attribute in each panel in " . $empty_panel_ent_name . " entity layout.";
			$success = 0;
		}
		elseif($attrs_left == 1)
		{
                        $alert = "Error: You must assign all attributes to a panel in " . $empty_panel_ent_name . " entity layout.";
			$success = 0;
		}
		elseif($empty_help == 1)
		{
                        $alert = "Error: You must assign tabs to the help attached to " . $empty_help_name . ".";
			$success = 0;
		}
                else
                {
                        $postfields = array(
                                        'passcode' => $_POST['passcode'],
                                        'email' => $_POST['email'],
					'method' => 'check',
					'entity_count' =>  $entity_count,
					'app_id' => $myapp_id,
                                        );
			
			foreach ( $postfields as $key => $value )
			{
                                $fields_string .= $key.'='.$value.'&';
			}
                        rtrim($fields_string,'&');
                       
			$xml = wpas_curl_request('check',$fields_string); 

			if(!$xml)
			{
				$alert = "Error: System error, please try again later.";
				$success = 0;
			}
			elseif($xml->error)
			{
				$alert = "Error: " . (string) $xml->errormsg;
				$success = 0;
			}
			elseif($xml->success)
			{
				$token = (string) $xml->token;
				$myfields = array(
					'token' => $token,
					'method' => 'generate',
					'type' => (string) $xml->success,
					'app' => gzdeflate(base64_encode(serialize($myapp))),
					);
				$xml1 = wpas_curl_request('generate',$myfields); 
	
				if(!$xml1)
				{
					$alert = "Error: System error, please try again later.";
					$success = 0;
				}
				elseif($xml1->error)
				{
					$alert = "Error: " . (string) $xml1->errormsg;
					$success = 0;
				}
				elseif($xml1->success)
				{
					$success = 1;
					$new_submit['credit_used']= (string) $xml1->credit_used;
					$new_submit['credit_left']= (string) $xml1->credit_left;
					$new_submit['update_left']= (string) $xml1->update_left;
					$new_submit['app_submitted']= $myapp['app_name'];
					$new_submit['date_submit']= date("Y-m-d H:i:s");
					$new_submit['queue_id']= (string) $xml1->queue_id;
					$new_submit['status'] = '<a id="check-status" class="btn btn-info btn-mini" href="#'. $new_submit['queue_id'] . '">Refresh</a>';
					$new_submit['status_link'] = '';
					$new_submit['refresh_time'] = time();
					$submits[] = $new_submit;
					update_option('wpas_apps_submit',serialize($submits));
				}
			}
                }
        }
	elseif(isset($_POST['email']))
	{
                        $alert = "Error: Please enter passcode.";
			$success = 0;
	}
		
        echo '<div class="wpas">';
        wpas_branding_header();
        echo wpas_generate_page($app_reg,$submits,$alert,$success);
        wpas_branding_footer();
        echo '</div>';


}


?>
