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
add_action('wp_ajax_wpas_get_comp_attrs','wpas_get_comp_attrs');
add_action('wp_ajax_wpas_get_rels', 'wpas_get_rels');
add_action('wp_ajax_wpas_get_rel_conn_types','wpas_get_rel_conn_types');
add_action('wp_ajax_wpas_get_roles','wpas_get_roles');
add_action('wp_ajax_wpas_get_email_attrs','wpas_get_email_attrs');
add_action('wp_ajax_wpas_get_form_layout', 'wpas_get_form_layout');
add_action('wp_ajax_wpas_get_form_text_html', 'wpas_get_form_text_html');
add_action('wp_ajax_wpas_get_form_html', 'wpas_get_form_html');
add_action('wp_ajax_wpas_form_layout_save', 'wpas_form_layout_save');

function wpas_form_layout_save()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_save_form_layout_nonce','nonce');
	$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$form_id = isset($_POST['form_id']) ? $_POST['form_id'] : '';
	$data = isset($_POST['data']) ? $_POST['data'] : '';
	if(empty($app_id) || $form_id == '' || empty($data))
        {
                die(-1);
        }
	$app = wpas_get_app($app_id);	
	if($app !== false && !empty($app['form'][$form_id]))
	{
		$resp = false;
		$req_fields = Array();
		$req_taxs = Array();
		$req_rels = Array();
		$attr_fields = Array();
		$taxs = Array();
		$rels = Array();
		$post_array = explode("&", stripslashes($data));
		$counter = 0;
		$next_seq = 1;
		$layout_fields = Array();
		$dependent_arr = wpas_get_dependent_arr($app,$app['form'][$form_id]['form-attached_entity'],'primary');
		foreach($app['entity'] as $ent)
		{
			if($ent['ent-label'] == $app['form'][$form_id]['form-attached_entity'])
			{
				foreach($ent['field'] as $fld)
				{
					if(isset($fld['fld_required']) && $fld['fld_required'] == 1 && !in_array($fld['fld_name'],$req_fields))
					{
						$req_fields[] =  $fld['fld_name'];
					}
				}
			}
		}
		foreach($app['taxonomy'] as $tax)
		{
			if(in_array($app['form'][$form_id]['form-attached_entity'],$tax['txn-attach']))
			{
				if(isset($tax['txn-required']) && $tax['txn-required'] == 1 
							&& !in_array($tax['txn-name'],$req_taxs))
				{
					$req_taxs[] = $tax['txn-name'];
				}
			}
		}
		if(!empty($app['form'][$form_id]['form-dependents']))
		{
			foreach($app['relationship'] as $rel)
			{
				if($app['form'][$form_id]['form-attached_entity'] == $rel['rel-from-name'] ||
					$app['form'][$form_id]['form-attached_entity'] == $rel['rel-to-name'])
				{
					$rel_name_id = $app['form'][$form_id]['form-attached_entity']. "__" . $rel['rel-name-id'];
					if(isset($rel['rel-required']) && $rel['rel-required'] == 1 
								&& !in_array($rel['rel-name-id'],$req_rels)
								&& (!empty($dependent_arr) && in_array($rel_name_id,array_keys($dependent_arr))) && in_array($rel_name_id,$app['form'][$form_id]['form-dependents']))
							{
								$req_rels[] = $rel['rel-name-id'];
							}
				}
			}
		}
		foreach($post_array as $mypost)
		{
			$form_field = explode("=",$mypost);
			$form_field_value = urldecode(str_replace($form_field[0]."=","",$mypost));
			if($form_field_value == "")
			{
				$resp = 4; //empty field
				echo $resp;
				die();
			}
			if(!in_array($form_field[0],Array('form','app','form-field-count'))) 
			{
				$key_arr = explode("-",$form_field[0]);
				if(!empty($form_field_value))
				{
				$val_arr = explode("__",$form_field_value);
				if(!empty($key_arr) && $key_arr[1] == 'text' && $key_arr[2] == 'desc')
				{
					$layout_fields[$counter]['obtype'] = 'text';
					$layout_fields[$counter]['desc'] = sanitize_text_field($form_field_value);
					$layout_fields[$counter]['sequence'] = $next_seq;
					$layout_fields[$counter]['obposition'] = 1;
					$next_seq += 1;
				}
				elseif(!empty($key_arr) && $key_arr[1] == 'hr')
				{
					$layout_fields[$counter]['obtype'] = 'hr';
					$layout_fields[$counter]['sequence'] = $next_seq;
					$layout_fields[$counter]['obposition'] = 1;
					$next_seq += 1;
				}
				else
				{
					$fld_label = "";
					$ent_label = "";
					if(in_array($val_arr[1],$attr_fields) || in_array($val_arr[1],$taxs) 
						|| in_array($val_arr[1],$rels))
					{
						$resp = 3; //dupe field send error
						echo $resp;
						die();
					}
					foreach($app['entity'] as $ent)
					{
						if($ent['ent-name'] == $val_arr[0])
						{
							$ent_label = $ent['ent-label'];
							if($key_arr[1] == 'attr')
							{
								foreach($ent['field'] as $fld)
								{
									if($fld['fld_name'] == $val_arr[1])
									{
										if(in_array($val_arr[1],$req_fields))
										{
											$fld_label = "* ";
										}
										$fld_label .= $ent_label . " - " . $fld['fld_label'] . " (" . ucfirst($fld['fld_type']) . ")";
										$attr_fields[] = sanitize_text_field($val_arr[1]);
									}
								}
							}
							elseif($key_arr[1] == 'tax')
							{
								foreach($app['taxonomy'] as $tax)
								{
									if($tax['txn-name'] == $val_arr[1])
									{
										if(in_array($val_arr[1],$req_taxs))
										{
											$fld_label = "* ";
										}
										$fld_label .=  $ent_label . " - " . $tax['txn-singular-label'];
										$taxs[] = sanitize_text_field($val_arr[1]);
									}
								}
							}
							$entity =  sanitize_text_field($val_arr[0]);
						}
						elseif($ent['ent-label'] == $val_arr[0])
						{
							if($key_arr[1] == 'relent')
							{
								foreach($app['relationship'] as $rel)
								{
									if($val_arr[1] == $rel['rel-name-id'])
									{
										if(in_array($val_arr[1],$req_rels))
										{
											$fld_label = "* ";
										}
										$fld_label .= $dependent_arr[$form_field_value];
										$rels[] = sanitize_text_field($val_arr[1]);
									}
								}
								$entity =  $ent['ent-label'];
					
							}
							break;
						}
					}
					$box_label_counter = $counter - ($key_arr[5] - 1);
					$layout_fields[$counter]['obtype'] = $key_arr[1] . "-" . $key_arr[3];
					$layout_fields[$counter]['entity'] = $entity;
					$layout_fields[$box_label_counter]['box_label' . $key_arr[5]] = $fld_label;
					$layout_fields[$counter][$key_arr[1]] = sanitize_text_field($val_arr[1]);
					$layout_fields[$counter]['obposition'] = $key_arr[5];
					if($key_arr[5] == 2)
					{
						$layout_fields[$counter]['sequence'] = $next_seq-1;
					}
					elseif($key_arr[5] == 3)
					{
						$layout_fields[$counter]['sequence'] = $next_seq-1;
					}
					elseif($key_arr[5] == 1)
					{
						$layout_fields[$counter]['sequence'] = $next_seq;
						$next_seq += 1;
					}
						
				}
				$counter++;
				}

			}
		}
		if(!empty($layout_fields))
		{
			foreach($req_fields as $myreq_field)
			{
				if(!in_array($myreq_field,$attr_fields))
				{
					$resp = 2; // required ent fields missing
					echo $resp;
					die();
				}
			}
			foreach($req_taxs as $myreq_tax)
			{
				if(!in_array($myreq_tax,$taxs))
				{
					$resp = 2;
					echo $resp;
					die();
				}
			}
			foreach($req_rels as $myreq_rel)
			{
				if(!in_array($myreq_rel,$rels))
				{
					$resp = 2;
					echo $resp;
					die();
				}
			}
		}

		$app['form'][$form_id]['form-layout'] = $layout_fields;
		$app['form'][$form_id]['modified_date'] = date("Y-m-d H:i:s");
		wpas_update_app($app,$app_id);
		$resp = 1;  //complete , layout saved
	}
	echo $resp;
	die();
}
function wpas_get_form_layout_select($app,$form_id,$type,$count,$value)
{
	$ret_option = "";
	$entity_filter = $app['form'][$form_id]['form-attached_entity'];
	$entity_filter_id = $app['form'][$form_id]['form-attached_entity_id'];
	$entity_name = $app['entity'][$entity_filter_id]['ent-name'];
	$myentity = $app['entity'][$entity_filter_id];
	if($type == 'attr' && !empty($app['entity']))
	{
		if(!empty($app['entity'][$entity_filter_id]['field']))
		{
			$ret_option .="<option value='' style='font-style:italic;font-weight:bold;'>" . esc_html($myentity['ent-label']) . "</option>";
			foreach($app['entity'][$entity_filter_id]['field'] as $myfield)
			{
				if($count > 1 && in_array($myfield['fld_type'],Array('hidden_constant','hidden_function')))
				{
					continue;
				}
				$ret_option .= "<option value='" . esc_attr($myentity['ent-name']) . "__" . esc_attr($myfield['fld_name']) . "' style='padding-left:2em;' entity='" . esc_attr($myentity['ent-label']) . "'";
				if($value == $myentity['ent-name'] . "__" . $myfield['fld_name'])
				{
					$ret_option .= " selected";
				}
				$ret_option .= ">";
				if(isset($myfield['fld_required']) && $myfield['fld_required'] == 1)
				{
					$ret_option .= "* ";
				}
				$ret_option .= esc_html($myfield['fld_label']) . " (" . esc_html(ucfirst($myfield['fld_type'])) . ")</option>";
			}
		}
	}
	elseif($type == 'tax' && !empty($app['taxonomy']))
	{
		$tax_selects = Array();
		$txn_key = "";
		foreach($app['taxonomy'] as $mytax)
		{
			$val_req = "";
			foreach($mytax['txn-attach'] as $mytxn_attach)
			{
				if($mytxn_attach == $entity_filter)
				{
					$ent_name = "";
					foreach($app['entity'] as $myentity)
					{
						if($mytxn_attach == $myentity['ent-label'])
						{
							$ent_name = $myentity['ent-name'];
						}
					}
					if(isset($mytax['txn-required']) && $mytax['txn-required'] == 1)
					{
						$val_req = "* ";
					}
						
					$tax_selects[$mytxn_attach][$ent_name . "__" . $mytax['txn-name']] = $val_req . $mytax['txn-singular-label'];
				}
			}
		}
		foreach($tax_selects as $key => $value_arr)
		{
			if($txn_key != $key)
			{
				$txn_key = $key;
				$ret_option .="<option value='' style='font-style:italic;font-weight:bold;'>" . esc_html($key) . "</option>";
			}
			foreach($value_arr as $key1 => $tax_value)
			{
				$ret_option .="<option value='" . esc_attr($key1) . "' style='padding-left:2em;' entity='" . esc_attr($key) . "'"; 
				if($value == $key1)
				{
					$ret_option .= " selected";
				}
				$ret_option .= ">" . esc_html($tax_value) . "</option>";
			}
		}
	}
	elseif($type == 'relent')
	{
		$dependent_arr = wpas_get_dependent_arr($app,$app['form'][$form_id]['form-attached_entity']);
		if(!empty($app['form'][$form_id]['form-dependents']))
		{
			if(!is_array($app['form'][$form_id]['form-dependents']))
			{
				$form_dependents = Array($app['form'][$form_id]['form-dependents']);
			}
			else
			{
				$form_dependents = $app['form'][$form_id]['form-dependents'];
			}
			foreach($form_dependents as $mydep)
			{
				$dep_val = explode("__",$mydep);
				$rel_req = "";
				$ret_option .= "<option value='" . esc_attr($app['form'][$form_id]['form-attached_entity']) . "__" . esc_attr($dep_val[1]) . "'"; 
				if($value == $app['form'][$form_id]['form-attached_entity'] . "__" . $dep_val[1])
				{
					$ret_option .= " selected";
				}
				foreach($app['relationship'] as $rel)
				{
					if($rel['rel-name-id'] == $dep_val[1] && isset($rel['rel-required']) && $rel['rel-required'] == 1)
					{
						$rel_req = "* ";
						break;
					}
				}
				$ret_option .= ">" . esc_html($rel_req) . esc_html($dependent_arr[$mydep]) . "</option>";
			}
		}
	}
	return $ret_option;

}
function wpas_get_form_html()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$form_id = isset($_GET['form_id']) ? $_GET['form_id'] : '';
	$count = isset($_GET['count']) ? $_GET['count'] : '1';
	$field_count = isset($_GET['field_count']) ? $_GET['field_count'] : '1';
	$type = isset($_GET['type']) ? $_GET['type'] : 'attr';
	$selected = isset($_GET['selected']) ? $_GET['selected'] : Array();
	if(empty($app_id) && empty($form_id))
        {
                die();
        }
	$app = wpas_get_app($app_id);	
	if($app !== false && !empty($app['form'][$form_id]))
	{
		echo wpas_get_form_layout_select_all($app,$form_id,$type,$count,$field_count,$selected);
	}
	die();
}
function  wpas_get_form_layout_select_all($app,$form_id,$type,$count,$field_count,$values=Array())
{
	$res = "";
	$value = "";
	$label[1][1] = "Attach To";
	$label[2][1] = "Attach To Left";
	$label[2][2] = "Attach To Right";
	$label[3][1] = "Attach To Left";
	$label[3][2] = "Attach To Center";
	$label[3][3] = "Attach To Right";
	for($i=1;$i<=$count; $i++)
	{
		$res .= '<div class="control-group row-fluid">';
		$res .= '<div class="span1 layout-edit-icons"><a class="delete-' . esc_attr($type) . '"';
		if($count != $i || $count == 1)
		{
			$res .= ' style="display:none;"';
		}
		$res .= ' id="delete-' . esc_attr($type) . '-' . ($i-1) . '"><i class="icon-minus-sign pull-right"></i></a></div>';
		$res .= '<div class="span1 layout-edit-icons"><a class="add-' . esc_attr($type) . '"';
	
		//check entitiy field count , tax count and related entity counts
		list($ent_field_count,$rel_count,$tax_count) = wpas_get_ent_tax_rel_count($app,$form_id);
		
		if($count != $i || $count == 3 || ($type == 'attr' && $ent_field_count <= $i) || ($type == 'tax' && $tax_count <= $i) || ($type == 'relent' && $rel_count <= $i))
		{
			$res .= ' style="display:none;"';
		}
		$res .= ' id="add-' . esc_attr($type) . '-' . ($i+1) . '"><i class="icon-plus-sign pull-right"></i></a></div>';
		$res .= '<label class="control-label span3">' . $label[$count][$i] . '</label>
			<div class="controls span7">';
		$res .= '<select name="form-' . esc_attr($type) . '-select-' . $count . "-" . $field_count . "-" . $i .'" id="form-' . esc_attr($type) . '-select-' . $count . "-" . $field_count . "-" . $i .'" class="input-large form-' . esc_attr($type) . '-select">';
		$res .= '<option value=""> Please select </option>';		
		if(!empty($values[$i]))
		{
			$value = $values[$i];
		}
		else
		{
			$value = "";
		}
		$res .= wpas_get_form_layout_select($app,$form_id,$type,$count,$value);
		$res .= '</select></div>
		</div>';
	}
	return $res;
}

function wpas_get_form_text_html()
{
	wpas_is_allowed();
	$field_count = isset($_GET['field_count']) ? $_GET['field_count'] : '1';
	echo '<div class="control-group row-fluid">
		<label class="control-label span3">Description</label>
		<div class="controls span9">';
	echo '<textarea id="form-text-desc-' . esc_attr($field_count) . '" class="input-xlarge" name="form-text-desc-' . esc_attr($field_count) . '"></textarea>';
	echo '</div></div>';
	die();
}

function wpas_get_form_layout()
{
	wpas_is_allowed();
	$layout = "";
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$form_id = isset($_GET['form_id']) ? $_GET['form_id'] : '';
	if(empty($app_id) && $form_id == null)
        {
                die();
        }
	$app = wpas_get_app($app_id);
	if($app !== false && isset($app['form'][$form_id]['form-layout']))
	{
		$layout = $app['form'][$form_id]['form-layout'];
	}
	echo  wpas_form_container($layout,$app,$form_id);
	die();
}
function wpas_get_email_attrs()
{
	$return = ""; 
	$rel_name = "";
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$primary_entity = isset($_GET['primary_entity']) ? $_GET['primary_entity'] : '';
	$dependents = isset($_GET['dependents']) ? $_GET['dependents'] : '';
	$values = isset($_GET['values']) ? $_GET['values'] : '';
	$app = wpas_get_app($app_id);
	if($app !== false && !empty($app['entity']))
	{
		$check_ents[] = $primary_entity;
		if(!empty($dependents))
		{
			foreach($dependents as $mydep)
			{
				$mydep_ent =  explode("__",$mydep);
				$check_ents[$mydep_ent[1]] = $mydep_ent[0];
			}
		}
		foreach($check_ents as $rel => $ent)
		{
			foreach($app['entity'] as $myentity)
			{
				if($myentity['ent-label'] == $ent && !empty($myentity['field']))
				{
					foreach($myentity['field'] as $myentfld)
					{
						if($myentfld['fld_type'] == 'email')
						{
							$return .= "<option value='' style='font-style:italic;font-weight:bold;'>"; 
							$opt_value = $myentfld['fld_name'];
							foreach($app['relationship'] as $myrel)
							{
								if($rel !== 0 && $myrel['rel-name-id'] == $rel)
								{
									$rel_name = wpas_get_rel_full_name($primary_entity,$myrel);
									$return .=  esc_html($rel_name);
									$opt_value = $myentfld['fld_name'] . "_rel_" . $rel;
									break;
								}
							}
							if($rel_name == "" && $rel == 0)
							{
								$return .= esc_html($ent);
							}
							$return .= "</option>";
							$return .= "<option value='" . esc_attr($opt_value) . "' style='padding-left:2em;'";
							if($values == $opt_value)
							{
								$return .= " selected";
							}
							$return .= ">" . esc_html($myentfld['fld_label']) . "</option>";
						}
					}
				}
			}
		}
	}
	if(empty($return))
	{
		$return = "<option value=''>First define an email attribute type.</option>";
	}
	else
	{
		$return = "<option value=''>Please select</option>" . $return;
	}
	echo $return;
	die();
}
function wpas_get_roles()
{
//ent-limit_user_relationship_role
	wpas_is_allowed();
	$return = "";
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$value = isset($_GET['value']) ? stripslashes_deep($_GET['value']) : Array();
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	if(!is_array($value))
	{
		$value= Array("$value");
	}
	if($type == 'entity')
	{
		$return = "<option value='false'";
		if($value == '')
		{
			$return .= " selected";
		}
		$return .= ">Do not limit</option>";
	}
	$app = wpas_get_app($app_id);
	if($app !== false && !empty($app['role']))
	{
		foreach($app['role'] as $myrole)
		{
			$return .= "<option value='" . esc_attr($myrole['role-name']) . "'"; 
			if(in_array($myrole['role-name'],$value))
			{
				$return .= " selected";
			}
			if($type == 'entity')
			{
				$return .= ">Only " . esc_html($myrole['role-label']) . " can be related</option>";
			}
			else
			{
				$return .= ">" . esc_html($myrole['role-label']) . "</option>";
			}			
		}
	}
	echo $return;
	die();
}

function wpas_get_rel_conn_types()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$rel_name = isset($_GET['rel_name']) ? sanitize_text_field($_GET['rel_name']) : '';
	$value = isset($_GET['value']) ? sanitize_text_field($_GET['value']) : '';
	$app = wpas_get_app($app_id);
	$return = "<option value=''>Please select</option>";
	if($app !== false && !empty($app['relationship']))
	{
		foreach($app['relationship'] as $myrel)
		{
			if($rel_name == $myrel['rel-from-name'] . "_" . $myrel['rel-to-name']) 
			{
				if($myrel['rel-type'] == 'many-to-many')
				{
					$return .= "<option value='related'"; 
					if($value == 'related')
					{
						$return .= " selected";
					}
					$return .= ">Related</option>";
				}
				$return .= "<option value='connected'";
				if($value == 'connected')
				{
					$return .= " selected";
				}
				$return .= ">Connected</option>";
				break;
			}
		}
	}
	echo $return;
	die();
}

function wpas_get_rels()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$value = isset($_GET['value']) ? sanitize_text_field($_GET['value']) : '';
	$return = "";
	$app = wpas_get_app($app_id);
	if($app !== false && !empty($app['relationship']))
	{
		foreach($app['relationship'] as $myrel)
		{
			if($myrel['rel-type'] == 'one-to-many')
			{
				$connector = " -> ";
			}
			elseif($myrel['rel-type'] == 'many-to-many')
			{
				$connector = " <-> ";
			}
			$rel_name = $myrel['rel-from-name'] . "_" . $myrel['rel-to-name'];
			$rel_name_display = $myrel['rel-from-name'] . $connector . $myrel['rel-to-name'];
			$return .= "<option value='" . esc_attr($rel_name) . "'"; 
			if($rel_name == $value)
			{
				$return .= " selected";
			}
			$return .= ">" . esc_html($rel_name_display) . "</option>";
		}
	}
	echo $return;
	die();
}

function wpas_get_comp_attrs()
{
	wpas_is_allowed();
	$comp_attrs = Array();
	$tax_attrs = Array();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$comp_name = isset($_GET['comp_name']) ? sanitize_text_field($_GET['comp_name']) : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$rel_conn_type = isset($_GET['rel_conn_type']) ? $_GET['rel_conn_type'] : '';
	if(empty($app_id) || empty($comp_name) || empty($type))
	{
		die(-1);
	}
	$app = wpas_get_app($app_id);
	if($type == 'entity')
	{
		if(!empty($app['entity']))
		{
			foreach($app['entity'] as $key => $myent)
			{
				if($comp_name == $myent['ent-label'] && !empty($myent['field']))
				{
					$comp_attrs[0] = $comp_name;
					foreach($myent['field'] as $myfield)
					{
						$comp_attrs['ent_'.$myfield['fld_name']] =  $myfield['fld_label'];
					}
					break;
				}
			}
		}
		if(!empty($app['taxonomy']))
		{
			foreach($app['taxonomy'] as $tkey => $mytax)
			{
				foreach($mytax['txn-attach'] as $mytxn_attach)
				{
					if($mytxn_attach == $comp_name)
					{
						$tax_attrs['tax_'.$mytax['txn-name']] =  $mytax['txn-label'];
					}
				}
			}
		}
		if(!empty($tax_attrs))
		{
			$tax_attrs = array_merge(Array('tax' => 'Taxonomies'),$tax_attrs);
			$comp_attrs= array_merge($comp_attrs,$tax_attrs);
		}
	}
	elseif($type == 'relationship')
	{
		if(!empty($app['relationship']))
		{
			foreach($app['relationship'] as $key => $myrel)
			{
				$rel_name = $myrel['rel-from-name'] . "_" . $myrel['rel-to-name'];
				if($comp_name == $rel_name && !empty($myrel['field']) && $rel_conn_type != 'related')
				{
					$comp_attrs[0] = $comp_name;
					foreach($myrel['field'] as $myfield)
					{
						$comp_attrs['rel_'.$myfield['rel_fld_name']] =  $myfield['rel_fld_label'];
					}
					break;
				}
			}
		}
	}
	echo json_encode($comp_attrs);
	die();
}
function wpas_check_status_generate()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_check_status_generate_nonce','nonce');
	$queue_id = isset($_POST['queue_id']) ? $_POST['queue_id'] : '';
	$resp = Array();

	$submits = unserialize(get_option('wpas_apps_submit'));
	$no_check = 0;
	//check last submit time and send request to check status
	if(isset($submits) && !empty($submits))
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
				elseif(!empty($mysubmit['status']))
				{
					if(strpos($mysubmit['status'],'Success') !== false || strpos($mysubmit['status'],'Error') !== false || strpos($mysubmit['status'],'Change') !== false )
					{
						$resp[0] = esc_url($mysubmit['status_link']);
						$resp[1] = esc_html($mysubmit['status']);
						$resp[4] = intval ($mysubmit['credit_used']);
						$resp[5] = intval ($mysubmit['credit_left']);
						$resp[6] = intval ($mysubmit['update_left']);
						$no_check =1;
					}
				}
			}
		}
	}
	if($no_check == 0 && !empty($queue_id))
	{		
		$postfields = array(
				'method' => 'check_status',
				'queue_id' => $queue_id,
		);

		$xml_status = wpas_remote_request('check_status',$postfields);

		if(!$xml_status)
		{
			$resp[2] = "System error, please try again later.";
		}
		elseif($xml_status->error)
		{
			if((string) $xml_status->error == 'error')
			{
				$resp[0] = esc_url($xml_status->url);
				$resp[1] = '<span style="color:red;">Error</span>';
				$resp[2] = esc_html($xml_status->errormsg);
				$resp[4] = intval ($xml_status->credit_used);
				$resp[5] = intval ($xml_status->credit_left);
				$resp[6] = intval ($xml_status->update_left);
				$new_submit['status']= $resp[1];
				$new_submit['credit_used']= $resp[4];
				$new_submit['credit_left']= $resp[5];
				$new_submit['update_left']= $resp[6];
				$new_submit['status_link']= $resp[0];
			}
			else
			{
				//if return is not completed 
				$resp[3] = esc_html($xml_status->errormsg);
			}
		}
		elseif($xml_status->success)
		{
			//if return is success 
			if($xml_status->success == 'duplicate')
			{
				$resp[1] = '<span style="color:green;">No Change Detected</span>';
			}
			else
			{
				$resp[1] = '<span style="color:green;">Success</span>';
			}
			
			$resp[0] = esc_url($xml_status->url);
			$resp[4] = intval ($xml_status->credit_used);
			$resp[5] = intval ($xml_status->credit_left);
			$resp[6] = intval ($xml_status->update_left);
			$new_submit['status']= $resp[1];
			$new_submit['credit_used']= $resp[4];
			$new_submit['credit_left']= $resp[5];
			$new_submit['update_left']= $resp[6];
			$new_submit['status_link']= $resp[0];
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
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$role_id = isset($_GET['role_id']) ? $_GET['role_id'] : '';
	if(empty($app_id))
	{
		die(-1);
	}
	wpas_add_role_form($app_id,$role_id);
	die();
}


function wpas_check_help()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$help_id = isset($_GET['help_id']) ? $_GET['help_id'] : '';
	$object_name = isset($_GET['object_name']) ? stripslashes($_GET['object_name']) : '';
	$screen_type = isset($_GET['screen_type']) ? $_GET['screen_type'] : '';
	$resp = true;
	if(empty($app_id))
	{
		$resp = false;
	}
	else
	{	
		$app = wpas_get_app($app_id);
		if(!empty($app['help']))
		{
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
		}
	}
	echo json_encode($resp);
	die();
}
function wpas_check_rel()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$rel_id = isset($_GET['rel_id']) ? $_GET['rel_id'] : '';
	$from_name = isset($_GET['from_name']) ? stripslashes($_GET['from_name']) : '';
	$to_name = isset($_GET['to_name']) ? stripslashes($_GET['to_name']) : '';
	$from_title = isset($_GET['from_title']) ? sanitize_text_field($_GET['from_title']) : '';
	$to_title = isset($_GET['to_title']) ? sanitize_text_field($_GET['to_title']) : '';
	$resp = true;
	$check = 0;
	if(empty($app_id))
	{
		$resp = false;
	}
	else
	{	
		$app = wpas_get_app($app_id);
		if(!empty($app['relationship']))
		{
			foreach($app['relationship'] as $key => $myrel)
			{
				if(($myrel['rel-from-name'] == $from_name && $myrel['rel-to-name'] == $to_name) || ($myrel['rel-from-name'] == $to_name && $myrel['rel-to-name'] == $from_name))
				{
					if(isset($myrel['rel-from-title']) && $myrel['rel-from-title'] == $from_title && $rel_id != $key)
					{ 
						$check = 1;
					}
					elseif(isset($myrel['rel-to-title']) && $myrel['rel-to-title'] == $to_title && $rel_id != $key)
					{
						$check = 1;
					}
					elseif($from_title == '' && $to_title == '' && $rel_id != $key)
					{
						$check = 1;
					}
				}
				if($check == 1 && $rel_id == null)
				{
					$resp = false;
					break;
				}
			}
		}
	}
	echo json_encode($resp);
	die();
}

function wpas_check_widg()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$widg_id = isset($_GET['widg_id']) ? $_GET['widg_id'] : '';
	$widg_title = isset($_GET['widg_title']) ? sanitize_text_field($_GET['widg_title']) : '';
	$widg_type = isset($_GET['widg_type']) ? $_GET['widg_type'] : 'dashboard';
	$widg_subtype = isset($_GET['widg_subtype']) ? $_GET['widg_subtype'] : 'entity';
        $resp = true;
	if(empty($app_id))
	{
		$resp = false;
	}
	else
	{	
		$app = wpas_get_app($app_id);
		if($widg_type == 'dashboard')
		{
			$check_subtype = 'widg-dash_subtype';
		}
		elseif($widg_type == 'sidebar')
		{
			$check_subtype = 'widg-side_subtype';
		}
		if(!empty($app['widget']))
		{
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
		}
	}
	echo json_encode($resp);
	die();
}

function wpas_check_unique()
{
	wpas_is_allowed();
	$response = true;
	$name = isset($_GET['value']) ? $_GET['value'] : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';

	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$ent_id = isset($_GET['ent_id']) ? $_GET['ent_id'] : '';
	$fld_id = isset($_GET['fld_id']) ? $_GET['fld_id'] : '';
	$txn_id = isset($_GET['txn_id']) ? $_GET['txn_id'] : '';
	$rel_fld_id = isset($_GET['rel_fld_id']) ? $_GET['rel_fld_id'] : '';
	$rel_id = isset($_GET['rel_id']) ? $_GET['rel_id'] : '';
	$help_id = isset($_GET['help_id']) ? $_GET['help_id'] : '';
	$help_fld_id = isset($_GET['help_fld_id']) ? $_GET['help_fld_id'] : '';
	$shc_id = isset($_GET['shc_id']) ? $_GET['shc_id'] : '';
	$role_id = isset($_GET['role_id']) ? $_GET['role_id'] : '';

	if($type == 'app')
	{
		$apps_unserialized = wpas_get_app_list();
		if(!empty($apps_unserialized) && $app_id != null)
		{
			foreach($apps_unserialized as $key => $myapp)
			{
				if(stripslashes($name) == $myapp['app_name'] && $key != $app_id)
				{
					$response = false;
					break;
				}
			}
		}
	}
	elseif($type == 'ent')
	{
		$app = wpas_get_app($app_id);
		if(!empty($app['entity']))
		{
			foreach($app['entity'] as $key => $myent)
			{
				if(strtolower($name) == $myent['ent-name'] && ($ent_id == null || $key != $ent_id))
				{
					$response = false;
					break;
				}	
			}
		}
	}
	elseif($type == 'ent_field')
	{
		$app = wpas_get_app($app_id);
		if($ent_id != null && !empty($app['entity'][$ent_id]['field']))
		{
			foreach($app['entity'][$ent_id]['field'] as $key => $myfield)
			{
				if(strtolower($name) == $myfield['fld_name'] && ($fld_id == null || $key != $fld_id))
				{
					$response = false;
					break;
				}
			}
		}
	}
	elseif($type == 'rel_field')
	{
		$app = wpas_get_app($app_id);
		if($rel_id != null && !empty($app['relationship'][$rel_id]['field']))
		{
			foreach($app['relationship'][$rel_id]['field'] as $key => $myfield)
			{
				if(strtolower($name) == $myfield['rel_fld_name'] && ($rel_fld_id == null || $key != $rel_fld_id))
				{
					$response = false;
					break;
				}
			}
		}
	}
	elseif($type == 'txn')
	{
		$app = wpas_get_app($app_id);
		if(!empty($app['taxonomy']))
		{
			foreach($app['taxonomy'] as $key => $mytxn)
			{
				if(strtolower($name) == $mytxn['txn-name'] && ($txn_id == null || $key != $txn_id))
				{
					$response = false;
					break;
				}
			}
		}
		if(!empty($app['taxonomy']))
		{
			foreach($app['entity'] as $key => $myent)
			{
				if(strtolower($name) == $myent['ent-name'])
				{
					$response = false;
					break;
				}
			}
		}
	}
	elseif($type == 'help_fld')
	{
		$app = wpas_get_app($app_id);
		if($help_id != null && !empty($app['help'][$help_id]['field']))
		{
			foreach($app['help'][$help_id]['field'] as $key => $myfield)
			{
				if(strtolower($name) == strtolower($myfield['help_fld_name']) && ($help_fld_id == null || $key != $help_fld_id))
				{
					$response = false;
					break;
				}
			}
		}
	}
	elseif($type == 'shc')
	{
		$app = wpas_get_app($app_id);
		if(!empty($app['shortcode']))
		{
			foreach($app['shortcode'] as $key => $myshc)
			{
				if(strtolower($name) == $myshc['shc-label'] && ($shc_id == null || $key != $shc_id))
				{
					$response = false;
					break;
				}
			}
		}
	}
	elseif($type == 'role')
	{
		$app = wpas_get_app($app_id);
		if(!empty($app['role']))
		{
			foreach($app['role'] as $key => $myrole)
			{
				if(strtolower($name) == $myrole['role-name'] && ($role_id == null || $key != $role_id))
				{
					$response = false;
					break;
				}
			}
		}
	}
	echo json_encode($response);
	die();
}

function wpas_save_layout()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_save_layout_nonce','nonce');
	$layout = "";
	$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$ent_id = isset($_POST['ent_id']) ? $_POST['ent_id'] : '';
	if(empty($app_id) && $ent_id == null)
        {
                die();
        }
	if(isset($_POST['layout']))
	{	
		$layout = $_POST['layout'];
	}
	$app = wpas_get_app($app_id);
	if($app !== false && is_array($app['entity'][$ent_id]))
	{
		$app['entity'][$ent_id]['layout'] = $layout;
		$app['entity'][$ent_id]['modified_date'] = date("Y-m-d H:i:s");
		wpas_update_app($app,$app_id);
	}
	die();
}
function wpas_get_layout()
{
	wpas_is_allowed();
	$layout = "";
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$ent_id = isset($_GET['ent_id']) ? $_GET['ent_id'] : '';
	if(empty($app_id) && $ent_id == null)
        {
                die();
        }
	$app = wpas_get_app($app_id);
	if($app !== false && isset($app['entity'][$ent_id]['layout']))
	{
		$layout = $app['entity'][$ent_id]['layout'];
	}
	echo  wpas_entity_container($layout);
	die();
}
function wpas_get_app_options()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$app = wpas_get_app($app_id);
	if($app !== false && !empty($app['option']))
	{
		$response = $app['option'];
		echo json_encode($response);
		die();
	}
}
function wpas_entity_types($app_id,$type,$values="")
{
	$return = "";
	if(!is_array($values))
	{
		$values= Array("$values");
	}
	$app = wpas_get_app($app_id);
	if($app !== false && !empty($app['entity']))
	{
		if($type == 'help')
		{
			$return .= "<option value='' style='font-style:italic;font-weight:bold;'> Entities </option>";
		}
		foreach($app['entity'] as $myent)
		{
			$show_ent = 0;
			if($type == 'relationship_to')
			{
				$show_ent = 1;
			}
			elseif($type == 'form' && empty($myent['field']))
			{
				$show_ent = 0;
			}
			elseif(in_array($myent['ent-label'],Array('Posts','Pages')))
			{
				if(isset($myent['field']) && !empty($myent['field']))
				{
					$show_ent = 1;
				}
				else
				{
					$show_ent = 0;
				}
			}
			else
			{
				$show_ent = 1;
			}
				

			if($show_ent == 1)
			{
				$return .= "<option value='" . esc_attr($myent['ent-label']) . "'"; 
				if($type == 'help')
				{
					$return .= " style='padding-left:2em;'";
				}
				if(in_array($myent['ent-label'],$values))
				{
					$return .= " selected";
				}
				$return .= ">" . esc_html($myent['ent-label']) . "</option>";
			}
		}
	}
	if($type == 'help')
	{
		if(!empty($app['taxonomy']))
		{
			$return .= "<option value='' style='font-style:italic;font-weight:bold;'> Taxonomies </option>";
			foreach($app['taxonomy'] as $mytxn)
			{
				$return .= "<option value='txn-" . esc_attr($mytxn['txn-label']) . "' style='padding-left:2em;'"; 
				if(in_array($mytxn['txn-label'],$values))
				{
					$return .= " selected";
				}
				$return .= ">" . esc_html($mytxn['txn-label']) . "</option>";
			}
		}
	}
	return $return;
}

function wpas_get_rel_full_name($primary_entity,$myrel)
{
	$rel_ent = "";
	if($myrel['rel-from-name'] == $primary_entity)
	{
		$rel_ent = $primary_entity;
		if(isset($myrel['rel-from-title']) && $myrel['rel-from-title'] != "")
		{
			$rel_ent .= " (" . $myrel['rel-from-title'] . ")";
		}
		if($myrel['rel-type'] == 'many-to-many')
		{
			$rel_ent .= " <--> ";
		}
		else
		{
			$rel_ent .= " --> ";
		}
		$rel_ent .= $myrel['rel-to-name'];
	}
	elseif($myrel['rel-to-name'] == $primary_entity)
	{
		$rel_ent = $myrel['rel-from-name'];
		if($myrel['rel-type'] == 'many-to-many')
		{
			$rel_ent .= " <--> ";
		}
		else
		{
			$rel_ent .= " --> ";
		}
		$rel_ent .= $primary_entity;
		if(isset($myrel['rel-to-title']) && $myrel['rel-to-title'] != "")
		{
			$rel_ent .= " (" . $myrel['rel-to-title'] . ")";
		}
	}
	return $rel_ent;
}
			
	


function wpas_get_dependent_arr($app,$primary_entity,$key_primary='')
{
	$key = "";
	$dependent_arr = Array();
	if($app !== false && !empty($app['relationship']))
	{
		foreach($app['relationship'] as $myrel)
		{
			if($primary_entity == $myrel['rel-from-name'] || $primary_entity == $myrel['rel-to-name'])
			{
				if($key_primary == '')
				{
					$key = $myrel['rel-from-name']. "__" ;
				}
				else
				{
					$key = $primary_entity . "__" ;
				}
				$key .= $myrel['rel-name-id'];
				$dependent_arr[$key] = wpas_get_rel_full_name($primary_entity,$myrel);
			}
		}
	}
	return $dependent_arr;
}

function wpas_dependent_entities($app_id,$primary_entity,$values)
{
	$return = "";
	$dependent_arr = Array();
	if(!is_array($values))
	{
		$values= Array("$values");
	}
	$app = wpas_get_app($app_id);
	$dependent_arr = wpas_get_dependent_arr($app,$primary_entity);
	if(!empty($dependent_arr))
	{
		foreach($dependent_arr as $key => $dependent)
		{
			$return .= "<option value='" . esc_attr($key) . "'"; 
			if(in_array($key,$values))
			{
				$return .= " selected";
			}
			$return .= ">" . esc_html($dependent) . "</option>";
		}
	}
	return $return;
}
function wpas_get_entities()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$values = isset($_GET['values']) ? stripslashes_deep($_GET['values']) : Array();
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	if($app_id == null)
	{
		die(-1);
	}
	if($type == 'form_dependents')
	{
		$primary_entity = isset($_GET['primary_entity']) ? $_GET['primary_entity'] : '';
		echo wpas_dependent_entities($app_id,$primary_entity,$values);
	}
	else
	{
		echo wpas_entity_types($app_id,$type,$values);
	}
	die();
}

function wpas_update_field_order()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_update_field_order_nonce','nonce');
	$app_id = isset($_POST['app']) ? $_POST['app'] : '';
	$order = isset($_POST['order']) ? $_POST['order'] : Array();
	$comp_id = isset($_POST['comp']) ? $_POST['comp'] : '';
	$type = isset($_POST['type']) ? $_POST['type'] : '';
	if(empty($order) || $app_id == null || $comp_id == null || $type == null)
	{
		die(-1);
	}	
	$app = wpas_get_app($app_id);
	$newfield = Array();
	$count = 0;

	if(empty($app[$type][$comp_id]['field']))
	{
		die(-1);
	}

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
	wpas_is_allowed();
	$app_id = isset($_GET['app']) ? $_GET['app'] : '';
	$comp_id = isset($_GET['comp']) ? $_GET['comp'] : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$field_id = isset($_GET['field']) ? $_GET['field'] : '';
	if($app_id == null || $comp_id == null || $type == null || $field_id == null)
	{
		die(-1);
	}	
	$app = wpas_get_app($app_id);

	if(!empty($app[$type][$comp_id]['field'][$field_id]))
	{
		$response[0] = $app[$type][$comp_id]['field'][$field_id];
		echo json_encode($response);
		die();
	}
	die(-1);
}
function wpas_delete_field()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_delete_field_nonce','nonce');
	$app_id = isset($_POST['app']) ? $_POST['app'] : '';
	$comp_id = isset($_POST['comp']) ? $_POST['comp'] : '';
	$type = isset($_POST['type']) ? $_POST['type'] : '';
	$field_id = isset($_POST['field']) ? $_POST['field'] : '';
	if($app_id == null || $comp_id == null || $type == null || $field_id == null)
	{
		die(-1);
	}	
	$app = wpas_get_app($app_id);

	if(empty($app[$type][$comp_id]['field'][$field_id]))
	{
		die(-1);
	}

	if($type == 'entity')
	{       
		$flabel = $app[$type][$comp_id]['field'][$field_id]['fld_label'];
		$fname = $app[$type][$comp_id]['field'][$field_id]['fld_name'];
		//also delete this attr from entity layout
		if(!empty($app[$type][$comp_id]['layout']))
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
		//delete this entity from form layout
		if(!empty($app['form']))
		{
			foreach($app['form'] as $keyform => $myform)
			{
				if(!empty($myform['form-layout']))
				{
					foreach($myform['form-layout'] as $keylayout => $myform_layout)
					{
						if(isset($myform_layout['entity']) && isset($myform_layout['attr']) && $myform_layout['attr'] == $fname)
						{
							if($myform_layout['obposition'] == 1)
							{
								unset($app['form'][$keyform]['form-layout'][$keylayout]);
							}
							else
							{
								$app['form'][$keyform]['form-layout'][$keylayout]['attr'] = "";
							}
						}
						
					}
				}
			}	
		}
		unset($app[$type][$comp_id]['field'][$field_id]);
		echo '<div id="ent-fld-list-div">';
		echo wpas_view_ent_fields_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}       
	elseif($type == 'relationship')
	{       
		unset($app[$type][$comp_id]['field'][$field_id]);
		echo '<div id="rel-fld-list-div">';
		echo wpas_view_rel_fields_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}       
	elseif($type == 'help')
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
	wpas_is_allowed();
	check_ajax_referer('wpas_save_field_nonce','nonce');
	$type = isset($_POST['type']) ? $_POST['type'] : '';
	$field_id = isset($_POST['field_id']) ? $_POST['field_id'] : '';
	$search_str = wpas_get_search_string($type . '_fld');
	$field = Array();

	$get_array = explode("&", stripslashes($_POST['form']));
	foreach($get_array as $myget)
	{
		$field_form = explode("=",$myget);
		$pos = strpos($field_form[0],$search_str);
		$field_form_value = urldecode(str_replace($field_form[0].'=','',$myget));
		if($field_form[0] == 'help_fld_content')
		{
			//tinymce field
			$field_form_value_sanitized = wpautop($field_form_value);
		}
		else
		{
			$field_form_value_sanitized = sanitize_text_field($field_form_value);
			$req_fields = Array('fld_name','fld_label','fld_values','rel_fld_name','rel_fld_label','rel_fld_values','help_fld_name');
			if(empty($field_form_value_sanitized) && !empty($field_form_value) && in_array($field_form[0],$req_fields))
			{
				die(-1);
			}
		}

		if($field_form[0] == 'app')
		{
			$app_id = $field_form_value_sanitized;
		}
		if(in_array($field_form[0], Array('ent','rel','help')))
		{
			$comp_id = $field_form_value_sanitized;
		}
		if($pos !== false && $field_form_value_sanitized != "")
		{
			if($field_form[0] == 'rel_fld_name' || $field_form[0] == 'fld_name')
			{
				$field[$field_form[0]] = strtolower($field_form_value_sanitized);
			}
			else
			{
				$field[$field_form[0]] = $field_form_value_sanitized;
			}
		}
	}
	if(empty($app_id) || !isset($comp_id))
        {       
                die(-1);
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

	if($type =='entity' && !empty($app[$type][$comp_id]['field'][$field_id]['fld_label']))
	{
		$flabel_old = $app[$type][$comp_id]['field'][$field_id]['fld_label'];
		$fname_old = $app[$type][$comp_id]['field'][$field_id]['fld_name'];
		if(isset($field['fld_label']))
		{ 
			$flabel = $field['fld_label'];
		}
		if(!empty($app[$type][$comp_id]['layout']) && $flabel_old != $flabel)
		{
			//update layout also
			$app[$type][$comp_id]['layout'] = wpas_update_layout_fields($app[$type][$comp_id]['layout'],$flabel_old,$flabel);
		}
		$fname = $field['fld_name'];
		if($fname != $fname_old && !empty($app['form']))
		{
			foreach($app['form'] as $keyform => $myform)
			{
				if(!empty($myform['form-layout']))
				{
					foreach($myform['form-layout'] as $keylayout => $myform_layout)
					{
						if(isset($myform_layout['entity']) && isset($myform_layout['attr']) && $myform_layout['attr'] == $fname_old)
						{
							$app['form'][$keyform]['form-layout'][$keylayout]['attr'] = $fname;
						}
					}
				}
			}	
		}
	}

	$app[$type][$comp_id]['modified_date'] = date("Y-m-d H:i:s");
	$app[$type][$comp_id]['field'][$field_id] = $field;
	if($type == 'entity')
	{
		echo wpas_view_entity($app[$type][$comp_id],$comp_id);
		echo wpas_view_ent_fields($app[$type][$comp_id]['ent-name']);       
		echo '<div id="ent-fld-list-div">';
		echo wpas_view_ent_fields_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}
	elseif($type == 'relationship')
	{
		echo wpas_view_relationship($app[$type][$comp_id],$comp_id);
		if($app['relationship'][$comp_id]['rel-type'] == 'many-to-many')
		{
			echo wpas_view_rel_fields($app[$type][$comp_id]['rel-from-name']. "-" . $app[$type][$comp_id]['rel-to-name']);
		}
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
	wpas_is_allowed();
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$comp_id = isset($_GET['comp_id']) ? $_GET['comp_id'] : '';
	if($app_id == null || $comp_id == null || $type == null)
	{
		die(-1);
	}	
	$app = wpas_get_app($app_id);
	if($type == 'entity' && !empty($app['entity'][$comp_id]))
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
	elseif($type == 'relationship' && !empty($app['relationship'][$comp_id]))
	{
		echo wpas_view_relationship($app['relationship'][$comp_id],$comp_id);
		if($app['relationship'][$comp_id]['rel-type'] == 'many-to-many')
		{
			echo wpas_view_rel_fields($app[$type][$comp_id]['rel-from-name']. "-" . $app[$type][$comp_id]['rel-to-name']);
		}
		echo '<div id="rel-fld-list-div">';
		if(isset($app[$type][$comp_id]['field']))
		{
			echo wpas_view_rel_fields_list($app[$type][$comp_id]['field']);
		}
		echo '</div>';
	}
	elseif($type == 'help' && !empty($app['help'][$comp_id]))
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
	die();
}
function wpas_edit()
{
	wpas_is_allowed();
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$comp_id = isset($_GET['comp_id']) ? $_GET['comp_id'] : '';
	if($app_id == null || $comp_id == null || $type == null)
	{
		die(-1);
	}	
	$app = wpas_get_app($app_id);

	if(isset($app[$type][$comp_id]['shc-sc_layout']))
	{
		$app[$type][$comp_id]['shc-sc_layout'] =stripslashes($app[$type][$comp_id]['shc-sc_layout']);
	}
	if(empty($app[$type][$comp_id]))
	{
		die(-1);
	}
	$response[0] = $app[$type][$comp_id];
	$response[1] = $comp_id;
	echo json_encode($response);
	die();  
}
function wpas_delete()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_delete_nonce','nonce');
	$type = isset($_POST['type']) ? $_POST['type'] : '';
	if($type == null)
	{
		die(-1);
	}
	elseif($type == 'app')
	{
		$app_del_keys = isset($_POST['fields']) ? $_POST['fields'] : Array();
		if(!empty($app_del_keys))
		{
			foreach($app_del_keys as $del_key)
			{
				$app_key_list = wpas_delete_app($del_key);
			}
		}
		$app_list = wpas_get_app_list($app_key_list);
		echo wpas_list('app',$app_list,0,"",1);
	}
	else
	{
		$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
		$comp_arr = isset($_POST['fields']) ? $_POST['fields'] : Array();
		$app = wpas_get_app($app_id);
		if(!empty($comp_arr) && !empty($app))
		{
		foreach ($comp_arr as $del_key)
		{
			if($type == 'entity' && !empty($app[$type][$del_key]))
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
				if(!empty($app['taxonomy']))
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
				if(!empty($app['relationship']))
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
				if(!empty($app['shortcode']))
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
				if(!empty($app['widget']))
				{
					foreach($app['widget'] as $skey => $mywidget)
					{
						if(isset($mywidget['widg-attach']) && $mywidget['widg-attach'] == $ent_label)
						{
							unset($app['widget'][$skey]);
						}
					}
				}
				//5 - delete the helps
				if(!empty($app['help']))
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
				if(!empty($app['role']))
				{
					foreach($app['role'] as $rkey => $myrole)
					{
						//check removed taxonomies
						if(!empty($txn_labels_arr))
						{
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
	}
	die();
}
function wpas_list_all()
{
	wpas_is_allowed();
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	if($type == null || $app_id == null)
	{
		die(-1);
	}
	$list = Array();
	$app = wpas_get_app($app_id);
	if(isset($app[$type]))
	{
		$list = $app[$type];
	}
	echo wpas_list($type,$list,$app_id,$app['app_name'],$page);
	die();
}
function wpas_save_option_form()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_save_option_form_nonce','nonce');
	$post_array = explode("&", stripslashes($_POST['form']));
	$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	if(empty($app_id))
	{
		die(-1);
	}	
	$app = wpas_get_app($app_id);
	$search_str = "ao_";
	$comp = Array();
	if(empty($app))
	{
		die(-1);
	}
	foreach($post_array as $mypost)
	{
		$comp_form = explode("=",$mypost);
		$pos = strpos($comp_form[0],$search_str);
		$comp_form_value = urldecode(str_replace($comp_form[0]."=","",$mypost));
		if(!in_array($comp_form[0], Array('ao_left_footer_html','ao_right_footer_html')))
		{
			$comp_form_value = sanitize_text_field($comp_form_value);
		}
		else
		{
			$comp_form_value = wpautop($comp_form_value);
		}

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
	wpas_is_allowed();
	check_ajax_referer('wpas_save_form_nonce','nonce');
	$type = isset($_POST['type']) ? $_POST['type'] : '';
	$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	if(empty($app_id) || $type == null)
	{
		die(-1);
	}
	$help_txnlist = 0;
	$post_array = explode("&", stripslashes($_POST['form']));
	$app = wpas_get_app($app_id);
	if(empty($app))
	{
		die(-1);
	}
	$search_str = wpas_get_search_string($type);
	$comp_type = $type;
	$comp = Array();
	foreach($post_array as $mypost)
	{
		$comp_form = explode("=",$mypost);
		$pos = strpos($comp_form[0],$search_str);
		$comp_form_value = urldecode(str_replace($comp_form[0]."=","",$mypost));
		if(in_array($comp_form[0], Array('shc-sc_layout','widg-layout','help-screen_sidebar','help_fld_content','form-form_desc','form-not_loggedin_msg','form-confirm_success_txt','form-confirm_error_txt','form-confirm_msg','form-confirm_admin_msg')))
		{
			//tinymce field
			$comp_form_value_sanitized = wpautop($comp_form_value);
		}
		else
		{
			$comp_form_value_sanitized = sanitize_text_field($comp_form_value);
			$req_fields = Array('ent-name','ent-label','ent-singular-label','txn-name','txn-label','txn-singular-label','shc-label','widg-title','role-name','role-label','form-name');
			if(empty($comp_form_value_sanitized) && !empty($comp_form_value) && in_array($comp_form[0],$req_fields))
			{
				die(-1);
			}
		}
		if($pos !== false && $comp_form_value_sanitized != '')
		{
			if(in_array($comp_form[0], Array('ent-name','txn-name','role-name','shc-label','form-name')))
			{
				$comp[$comp_form[0]] = strtolower($comp_form_value_sanitized);
			}
			elseif($comp_form[0] == 'ent-label' || $comp_form[0] == 'ent-singular-label' || $comp_form[0] == 'txn-label' || $comp_form[0] == 'txn-singular-label')
			{
				$comp[$comp_form[0]] = ucwords(strtolower($comp_form_value_sanitized));
			}
			elseif($comp_form[0] == 'txn-attach' || $comp_form[0] == 'form-dependents')
			{
				$comp[$comp_form[0]][] = $comp_form_value_sanitized;
			}
			elseif($comp_form[0] == 'form-attached_entity')
			{
				$comp[$comp_form[0]] = $comp_form_value_sanitized;
				foreach($app['entity'] as $key_ent => $myentity)
				{
					if($myentity['ent-label'] == $comp_form_value_sanitized)
					{
						$comp['form-attached_entity_id'] = $key_ent;
						break;
					}
				}
			}
			else
			{
				if($type == 'help' && preg_match("/^txn-/",$comp_form_value_sanitized))
				{
					$comp_form_value_sanitized = str_replace("txn-","",$comp_form_value_sanitized);
					$help_txnlist = 1;
				}

				if(isset($comp[$comp_form[0]]))
				{
					$comp[$comp_form[0]] = Array($comp[$comp_form[0]],$comp_form_value_sanitized);
				}
				else
				{
					$comp[$comp_form[0]] = $comp_form_value_sanitized;
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
	if($comp_type == 'relationship')
	{
		$rel_name_id = $comp['rel-from-name'];
		if(isset($comp['rel-from-title']))
		{ 
			$rel_name_id .=  "_" . $comp['rel-from-title'];
		}
		$rel_name_id .=  "_" . $comp['rel-to-name'];
		if(isset($comp['rel-to-title']))
		{
			$rel_name_id .= "_" . $comp['rel-to-title'];
		}
		$comp['rel-name-id'] = md5($rel_name_id);
	}
	$app[$comp_type][$comp_id] = $comp;
	echo wpas_list($type,$app[$comp_type],$app_id,$app['app_name'],1);
	wpas_update_app($app,$app_id);
	die();
}
function wpas_update_form()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_update_form_nonce','nonce');
	$type = isset($_POST['type']) ? $_POST['type'] : '';
	$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	if(empty($app_id) || $type == null)
	{
		die(-1);
	}
	$post_array = explode("&", stripslashes($_POST['form']));
	$app = wpas_get_app($app_id);
	if(empty($app))
	{
		die(-1);
	}
	$search_str = wpas_get_search_string($type);
	$comp_type = $type;
	$comp = Array();
	$types = Array('ent','txn','rel','help','shc','widget','role','form');
	foreach($post_array as $mypost)
	{
		$comp_form = explode("=",$mypost);

		if(in_array($comp_form[0],$types))
		{
			$comp_id = $comp_form[1];
		}
		$pos = strpos($comp_form[0],$search_str);
		$comp_form_value = urldecode(str_replace($comp_form[0]."=","",$mypost));
		if(in_array($comp_form[0], Array('shc-sc_layout','widg-layout','help-screen_sidebar','help_fld_content','form-form_desc','form-not_loggedin_msg','form-confirm_success_txt','form-confirm_error_txt','form-confirm_msg','form-confirm_admin_msg')))
		{
			//tinymce field
			$comp_form_value_sanitized = wpautop($comp_form_value);
		}
		else
		{
			$comp_form_value_sanitized = sanitize_text_field($comp_form_value);
			$req_fields = Array('ent-name','ent-label','ent-singular-label','txn-name','txn-label','txn-singular-label','shc-label','widg-title','role-name','role-label','form-name');
			if(empty($comp_form_value_sanitized) && !empty($comp_form_value) && in_array($comp_form[0],$req_fields))
			{
				die(-1);
			}
		}
		if($pos !== false && $comp_form_value_sanitized != "")
		{
			if(in_array($comp_form[0], Array('ent-name','txn-name','role-name','shc-label','form-name')))
			{
				$comp[$comp_form[0]] = strtolower($comp_form_value_sanitized);
			}
			elseif($comp_form[0] == 'ent-label' || $comp_form[0] == 'ent-singular-label' || $comp_form[0] == 'txn-label' || $comp_form[0] == 'txn-singular-label')
			{
				$comp[$comp_form[0]] = ucwords(strtolower($comp_form_value_sanitized));
			}
			elseif($comp_form[0] == 'txn-attach' || $comp_form[0] == 'form-dependents')
			{
				$comp[$comp_form[0]][] = $comp_form_value_sanitized;
			}
			elseif($comp_form[0] == 'help-object_name')
			{
				$comp[$comp_form[0]] = str_replace("txn-","",$comp_form_value_sanitized);
			}
			elseif($comp_form[0] == 'form-attached_entity')
			{
				$comp[$comp_form[0]] = $comp_form_value_sanitized;
				foreach($app['entity'] as $key_ent => $myentity)
				{
					if($myentity['ent-label'] == $comp_form_value_sanitized)
					{
						$comp['form-attached_entity_id'] = $key_ent;
						break;
					}
				}
			}
			else
			{
				$comp[$comp_form[0]] = $comp_form_value_sanitized;
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
	elseif($comp_type == 'form' && !empty($app[$comp_type][$comp_id]['form-layout']))
	{
		$comp['form-layout'] = $app[$comp_type][$comp_id]['form-layout'];
	}

	if($comp_type == 'entity' && $comp['ent-capability_type'] != 'post')
	{
		$label = $comp['ent-name'];
		$label = strtolower($label);
		$label = $label . "s";

		$admin_new_entity_arr = wpas_admin_entity($label);
		$app['role'][0] = array_merge($admin_new_entity_arr,$app['role'][0]);
	}
	if($comp_type == 'relationship')
	{
		$rel_name_id = $comp['rel-from-name'];
		if(isset($comp['rel-from-title']))
		{ 
			$rel_name_id .=  "_" . $comp['rel-from-title'];
		}
		$rel_name_id .=  "_" . $comp['rel-to-name'];
		if(isset($comp['rel-to-title']))
		{
			$rel_name_id .= "_" . $comp['rel-to-title'];
		}
		$comp['rel-name-id'] = md5($rel_name_id);
	}

	$app[$comp_type][$comp_id] = $comp;
	wpas_update_app($app,$app_id);
	echo wpas_list($type,$app[$comp_type],$app_id,$app['app_name'],1);
	die();
}
function wpas_get_search_string($type)
{
	switch($type) {
		case 'entity':	
			$search_str = "ent-";
			break;
		case 'taxonomy':
			$search_str = "txn-";
			break;
		case 'option':
			$search_str = "ao_";
			break;
		case 'relationship':
			$search_str = "rel-";
			break;
		case 'help':
			$search_str = "help-";
			break;
		case 'shortcode':
			$search_str = "shc-";
			break;
		case 'widget':
			$search_str = "widg-";
			break;
		case 'role':
			$search_str = "role-";
			break;
		case 'form':
			$search_str = "form-";
			break;
		case 'entity_fld':
			$search_str = "fld_";
			break;
		case 'relationship_fld':
			$search_str = "rel_fld_";
			break;
		case 'help_fld':
			$search_str = "help_fld_";
			break;
		default:
			$search_str = "ent-";
			break;
	}
	return $search_str;
}
function wpas_update_layout_fields($layout,$flabel_old,$flabel)
{
	foreach($layout as $lkey => $mylayout)
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
		$layout[$lkey] = $mylayout;
	}
	return $layout;
}
?>
