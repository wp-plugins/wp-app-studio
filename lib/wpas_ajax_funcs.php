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
add_action('wp_ajax_wpas_save_layout', 'wpas_save_layout');
add_action('wp_ajax_wpas_get_layout', 'wpas_get_layout');
add_action('wp_ajax_wpas_get_entities', 'wpas_get_entities');
add_action('wp_ajax_wpas_edit_role', 'wpas_edit_role');

add_action('wp_ajax_wpas_check_unique', 'wpas_check_unique');
add_action('wp_ajax_wpas_check_help', 'wpas_check_help');
add_action('wp_ajax_wpas_check_status_generate', 'wpas_check_status_generate');
add_action('wp_ajax_wpas_get_roles','wpas_get_roles');
add_action('wp_ajax_wpas_get_email_attrs','wpas_get_email_attrs');
add_action('wp_ajax_wpas_get_form_layout', 'wpas_get_form_layout');
add_action('wp_ajax_wpas_get_form_text_html', 'wpas_get_form_text_html');
add_action('wp_ajax_wpas_get_form_html', 'wpas_get_form_html');
add_action('wp_ajax_wpas_form_layout_save', 'wpas_form_layout_save');

add_action('wp_ajax_wpas_get_search_forms','wpas_get_search_forms');
add_action('wp_ajax_wpas_get_ent_layout_attrs','wpas_get_ent_layout_attrs');
add_action('wp_ajax_wpas_check_email','wpas_check_email');
add_action('wp_ajax_wpas_get_tax_values','wpas_get_tax_values');
add_action('wp_ajax_wpas_get_ent_fields', 'wpas_get_ent_fields');
add_action('wp_ajax_wpas_get_date_ranges', 'wpas_get_date_ranges');
add_action('wp_ajax_wpas_get_table_cols', 'wpas_get_table_cols');
add_action('wp_ajax_wpas_check_app_dash', 'wpas_check_app_dash');

add_action('wp_ajax_wpas_get_notify_attach','wpas_get_notify_attach');

add_filter('wp_kses_allowed_html','wpas_allowed_html',10,2);


add_action('wp_ajax_wpas_get_layout_tags','wpas_get_layout_tags');

function wpas_get_layout_tags(){
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$comp_id = isset($_GET['comp_id']) ? $_GET['comp_id'] : '';
	$rel_id = isset($_GET['rel_id']) ? $_GET['rel_id'] : '';
	$ret = '';
	if(empty($app_id) || $comp_id == '' || empty($type))
	{
		die(-1);
	}
	$app = wpas_get_app($app_id);
	$comp_id_arr = Array();
	$builtins = Array(
                'title' => __('Title','wpas'),
                'permalink' => __('Permalink','wpas'),
                'edit_link' => __('Edit Link','wpas'),
                'delete_link' => __('Delete Link','wpas'),
                'excerpt' => __('Excerpt','wpas'),
                'content' => __('Content','wpas'),
                'author' => __('Author','wpas'),
        );
	$builtins_user = Array(
                'user_nicename' => __('User Nicename','wpas'),
                'user_email' => __('User Email','wpas'),
                'user_url' => __('User URL','wpas'),
                'user_registered' => __('User Registered','wpas'),
                'user_display_name' => __('User Display Name','wpas'),
                'user_login' => __('User Login','wpas')
        );
	if(in_array($type,Array('tag','tag-rel','tag-form','rel','notify-rel')) && isset($comp_id)){
		if($type == 'tag-form' && !empty($app['form']))
		{
			$comp_id_arr[] = $app['form'][$comp_id]['form-attached_entity'];
		}
		elseif($type == 'rel')
		{
			if($comp_id == 'user')
			{
				$builtins = array_merge($builtins,$builtins_user);
			}
			else
			{
				$comp_id_arr[] = $comp_id;
			}
		}
		elseif($type == 'notify-rel' && !empty($app['relationship'][$comp_id]))
		{
			if($app['relationship'][$comp_id]['rel-from-name'] == 'user')
			{
				$builtins = array_merge($builtins,$builtins_user);
			}
			else
			{
				$comp_id_arr[] = $app['relationship'][$comp_id]['rel-from-name'];
			}
			if($app['relationship'][$comp_id]['rel-to-name'] == 'user')
			{
				$builtins = array_merge($builtins,$builtins_user);
			}
			else
			{
				$comp_id_arr[] = $app['relationship'][$comp_id]['rel-to-name'];
			}
		}	
		else
		{
			$comp_id_arr[] = $comp_id;
		}
		foreach($comp_id_arr as $cid)
		{
			if(!empty($app['entity'][$cid]))
			{
				if(!empty($app['entity'][$cid]['ent-taxonomy_category']) && $app['entity'][$cid]['ent-taxonomy_category'] == 1)
				{
					$blt_tax['blt_tax_cat'] = __('Categories','wpas');
					$blt_tax['blt_tax_cat_NL'] = __('Categories NL','wpas');
				}
				if(!empty($app['entity'][$cid]['ent-taxonomy_post_tag']) && $app['entity'][$cid]['ent-taxonomy_post_tag'] == 1)
				{
					$blt_tax['blt_tax_tag'] = __('Tags','wpas');
					$blt_tax['blt_tax_tag_NL'] = __('Tags NL','wpas');
				}
				if(!empty($app['entity'][$cid]['field']))
				{
					foreach($app['entity'][$cid]['field'] as $myfield)
					{
						if(empty($myfield['fld_builtin']))
						{
							$comp_attrs['ent_'.$myfield['fld_name']] =  $myfield['fld_label'];
						}
					}
				}
			}
			if(!empty($app['taxonomy']))
			{
				foreach($app['taxonomy'] as $tkey => $mytax)
				{
					foreach($mytax['txn-attach'] as $mytxn_attach)
					{
						if($mytxn_attach == $cid)
						{
							$tax_attrs['tax_'.$mytax['txn-name']] =  $mytax['txn-label'];
							$tax_attrs['tax_'.$mytax['txn-name'] . '_NL'] =  $mytax['txn-label'] . " NL";
						}
					}
				}
			}
			if($type == 'tag-rel' && !empty($app['relationship']))
			{
				foreach($app['relationship'] as $myrelation)
				{
					if(isset($myrelation['rel-connected-display']) && $myrelation['rel-connected-display'] == 1)
					{
						if($myrelation['rel-from-name'] == $cid && !empty($myrelation['rel-con_from_layout']))
						{
							$rel_attrs['entrelcon_' . $myrelation['rel-name']] = $myrelation['rel-connected-display-from-title'];
						}
						elseif($myrelation['rel-to-name'] == $cid && !empty($myrelation['rel-con_to_layout']))
						{
							$rel_attrs['entrelcon_' . $myrelation['rel-name']] = $myrelation['rel-connected-display-to-title'];
						}
					}
					if($myrelation['rel-type'] == 'many-to-many' && isset($myrelation['rel-related-display']) && $myrelation['rel-related-display'] == 1)
					{
						if($myrelation['rel-from-name'] == $cid && !empty($myrelation['rel-rel_from_layout']))
						{
							$rel_attrs['entrelrltd_' . $myrelation['rel-name']] = $myrelation['rel-related-display-to-title'];
						}
						elseif($myrelation['rel-to-name'] == $cid && !empty($myrelation['rel-rel_to_layout']))
						{
							$rel_attrs['entrelrltd_' . $myrelation['rel-name']] = $myrelation['rel-related-display-from-title'];
						}
					}
				}
			}
			if($type == 'rel' && $rel_id != '' && !empty($app['relationship'][$rel_id]['field']))
			{
				foreach($app['relationship'][$rel_id]['field'] as $myfield)
				{
					$rel_fld_attrs['rel_'.$myfield['rel_fld_name']] =  $myfield['rel_fld_label'];
				}
			}
		}
		$ret = "<table class='table table-striped'><tr><th colspan=2>";
		$ret .= __('Use template tags below to customize your layout. Taxonomy tags produce link(s) to the related record(s). For no link tag, add _nl to taxonomy tag. For example, for !#mytag_nl# tag produces a no link version of the tag.','wpas');
		$ret .= '</th></tr>
                        <tr><th>' . __('Builtin', 'wpas') . '</th>
                        <td>';
		foreach($builtins as $key => $value){
			$ret .= $value . ": <b>!#" . $key . "#</b>, ";
		}
                $ret .= '</td></tr>';
		if(!empty($blt_tax)){
                        $ret .= '<tr><th>' . __('WP Builtin Taxonomies', 'wpas') . '</th><td>';
			foreach($blt_tax as $key => $value){
				$ret .= $value . ": <b>!#" . $key . "#</b>, ";
			}
			$ret .= '</td></tr>';
		}
		if(!empty($comp_attrs)){
                        $ret .= '<tr><th>' . __('Attributes', 'wpas') . '</th><td>';
			foreach($comp_attrs as $key => $value){
				$ret .= $value . ": <b>!#" . $key . "#</b>, ";
			}
			$ret .= '</td></tr>';
		}
		if(!empty($tax_attrs)){
                        $ret .= '<tr><th>' . __('Taxonomies', 'wpas') . '</th><td>';
			foreach($tax_attrs as $key => $value){
				$ret .= $value . ": <b>!#" . $key . "#</b>, ";
			}
			$ret .= '</td></tr>';
		}
		if(!empty($rel_attrs)){
                        $ret .= '<tr><th>' . __('Relationships', 'wpas') . '</th><td>';
			foreach($rel_attrs as $key => $value){
				$ret .= $value . ": <b>!#" . $key . "#</b>, ";
			}
			$ret .= '</td></tr>';
		}
		if(!empty($rel_fld_attrs)){
                        $ret .= '<tr><th>' . __('Relationship Attributes', 'wpas') . '</th><td>';
			foreach($rel_fld_attrs as $key => $value){
				$ret .= $value . ": <b>!#" . $key . "#</b>, ";
			}
			$ret .= '</td></tr>';
		}
		$ret .= '</table>';
	}
	echo $ret;
        die();
}
function wpas_allowed_html($allowed,$content){
	if($content === 'post'){
		$allowed['button']['data-toggle'] = true;
		$allowed['button']['data-target'] = true;
		$allowed['button']['data-dismiss'] = true;
		$allowed['img']['data-src'] = true;
	}
	return $allowed;
}

function wpas_get_notify_attach()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$value = isset($_GET['value']) ? $_GET['value'] : '';
	$app = wpas_get_app($app_id);
	if(!empty($app) && !empty($type))
	{
		echo "<option value=''>Please select</option>";
		switch ($type) {
			case 'entity':
			case 'tax':
				echo wpas_entity_types($app_id,$type,$value);
				break;
			case 'attr':
				foreach($app['entity'] as $kent => $myentity)
				{
					if(!empty($myentity['field']))
					{
						echo "<option style='font-style:italic;font-weight:bold;' value=''>" . $myentity['ent-label'] . "</option>";
						echo wpas_entity_fields('notify',$app_id,$kent,$value);
					}
				}
				break;
			case 'rel':
				foreach($app['relationship'] as $krel => $myrel)
				{
					echo "<option value='" . $krel . "'";
					if($krel == $value)
					{
						echo " selected";
					}
					echo ">" . wpas_get_rel_full_name($myrel,$app) . "</option>";
				}
				break;
			case 'com':
				foreach($app['entity'] as $kent => $myentity)
                                {
                                        if(!empty($myentity['ent-com_name']) && $myentity['ent-com_type'] == 'custom')
                                        {
                                                echo "<option value='" . $kent . "'";
						if($kent == $value)
						{
							echo " selected";
						}
						echo ">" . $myentity['ent-com_single_label'] . "</option>";
					}
				}
				break;
		}
	}
	die();
}
function wpas_check_app_dash()
{
	wpas_is_allowed();
	$resp = true;
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$layout = isset($_GET['layout']) ? $_GET['layout'] : '';
	$app = wpas_get_app($app_id);
	$pattern = '/!#shortcode\[.*?\]#/';
	if(!empty($app) && !empty($app['shortcode']))
	{
		if(preg_match_all($pattern,$layout,$matches))
		{
			foreach($matches[0] as $mymatch)
			{
				$newmatch = preg_replace("/!#shortcode\[/","",$mymatch);
				$newmatch = preg_replace("/\]#/","",$newmatch);
				foreach($app['shortcode'] as $myshc)
				{
					if($myshc['shc-label'] == $newmatch && $myshc['shc-app_dash'] == 1)
					{
						$resp = false;
						break;
					}
				}

			}
		}
	}
	echo $resp;
	die();
}
function wpas_get_table_cols()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$chart_ent = isset($_GET['chart_ent']) ? $_GET['chart_ent'] : '';
	$table_cols = isset($_GET['table_cols']) ? $_GET['table_cols'] : '';
	$return[0] = "";
	$return[1] = "";
	$app = wpas_get_app($app_id);
	if($table_cols != "" && $chart_ent != "" && !empty($app))
	{
		foreach($table_cols as $mycol)
		{
			if(preg_match('/__fld$/',$mycol))
			{
				$mycol_id = str_replace("__fld","",$mycol);
				$return[1] .= "<option value='" . $mycol . "' selected>";
				$return[1] .= esc_html($app['entity'][$chart_ent]['field'][$mycol_id]['fld_label']) . "</option>";
			}
			elseif(preg_match('/__tax__nl$/',$mycol))
			{
				$mycol_id = str_replace("__tax__nl","",$mycol);
				$return[1] .= "<option value='" . $mycol . "' selected>";
				$return[1] .= esc_html($app['taxonomy'][$mycol_id]['txn-singular-label']) . " No Link</option>";
			}
			elseif(preg_match('/__tax$/',$mycol))
			{
				$mycol_id = str_replace("__tax","",$mycol);
				$return[1] .= "<option value='" . $mycol . "' selected>";
				$return[1] .= esc_html($app['taxonomy'][$mycol_id]['txn-singular-label']) . "</option>";
			}
			elseif(preg_match('/__rel__nl$/',$mycol))
			{
				$mycol_id = str_replace("__rel__nl","",$mycol);
				$return[1] .= "<option value='" . $mycol . "' selected>";
				$return[1] .= esc_html(wpas_get_rel_full_name($app['relationship'][$mycol_id],$app)) . " No Link</option>";
			}
			elseif(preg_match('/__rel$/',$mycol))
			{
				$mycol_id = str_replace("__rel","",$mycol);
				$return[1] .= "<option value='" . $mycol . "' selected>";
				$return[1] .= esc_html(wpas_get_rel_full_name($app['relationship'][$mycol_id],$app)) . "</option>";
			}
		}
	}
	$ret_attr = wpas_entity_fields('all',$app_id,$chart_ent,'');
	if(!empty($ret_attr))
	{
		$return[0] .= "<option style='font-style:italic;font-weight:bold;' value='' selected>Attributes</option>" . $ret_attr;
	}
	$ret_tax = wpas_entity_types($app_id,'tax',Array(),"","",$chart_ent,0);
	if(!empty($ret_tax))
	{
		$return[0] .= "<option style='font-style:italic;font-weight:bold;' value=''>Taxonomies</option>" . $ret_tax;
	}
	$ret_rel = wpas_dependent_entities($app_id,$chart_ent,"",0);
	if(!empty($ret_rel))
	{
		$return[0] .= "<option style='font-style:italic;font-weight:bold;' value=''>Relationships</option>" . $ret_rel;
	}
	echo json_encode($return);
	die();
}
function wpas_get_date_ranges()
{
	wpas_is_allowed();
	$return = "<option value=''>" . __("Please select","wpas") . "</option>";
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$value = isset($_GET['value']) ? $_GET['value'] : '';
	switch ($type){
		case 'month':
			$options = Array("cur" => "All Months in Current Year",
					 "prev" => "All Months in Previous Year");
			for($i=2;$i <= 5;$i++)
			{
				$options["last".$i] = "All Months in Last " . $i . " Years";
			}
			break;
		case 'day':
			$options = Array("cur" => "All Days in Current Week",
					 "prev" => "All Days in Previous Week");
			for($i=2;$i <= 10;$i++)
			{
				$options["last".$i] = "All Days in Last " . $i . " Weeks";
			}
			break;
	}
	foreach($options as $kopt => $vopt)
	{
		$return .= "<option value='" . $kopt . "'";
		if($value == $kopt)
		{
			$return .= " selected";
		}
		$return .= ">" . $vopt . "</option>";
	}
	echo $return;
	die();
}
function wpas_get_ent_fields()
{
	wpas_is_allowed();
	$return = "<option value=''>" . __("Please select","wpas") . "</option>";
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$ent_id = isset($_GET['ent_id']) ? $_GET['ent_id'] : '';
	$value = isset($_GET['value']) ? $_GET['value'] : '';
	if($app_id == null)
	{
		die(-1);
	}
	$return .= wpas_entity_fields($type,$app_id,$ent_id,$value);
	echo $return;
	die();
}
function wpas_entity_fields($type,$app_id,$ent_id,$value)
{
	$return = "";
	$date_types = Array('date','datetime','time');
	$hidden_date_types = Array('date_mm_dd_yyyy','date_dd_mm_yyyy','current_year','current_month','current_month_num','current_day','now','current_time');
	$num_types = Array('decimal','integer');
	$not_allowed_types = Array('password','textarea','wysiwyg','file','image','hidden_function');
	$not_allowed_all = Array('password');
	if($type == 'date')
	{
		$post_dates = Array("post_date"=>"Post Date","post_date_gmt"=>"Post Date GMT","post_modified"=>"Post Modified","post_modified_gmt"=>"Post Modified GMT");
		foreach($post_dates as $kpdate=> $vpdate)
		{
			$return .= "<option value='" . $kpdate . "'";
			if($kpdate == $value)
			{
				$return .= " selected";
			}
			$return .= ">" . $vpdate . "</option>";
		}
	}
	$app = wpas_get_app($app_id);
	if($app !== false && $ent_id != '' && !empty($app['entity'][$ent_id]['field']))
	{
		foreach($app['entity'][$ent_id]['field'] as $keyfield => $myfield)
		{	
			$show_field = 0;
			if(($type == 'all' || $type == 'notify') && !in_array($myfield['fld_type'],$not_allowed_all))
			{
				$show_field = 1;
			}
			else if($type == 'date' && (in_array($myfield['fld_type'],$date_types) || ($myfield['fld_type'] == 'hidden_function' && in_array($myfield['fld_hidden_func'],$hidden_date_types))))
			{
				$show_field = 1;
			}
			else if($type == 'attribute' && !in_array($myfield['fld_type'],$not_allowed_types) && !in_array($myfield['fld_type'],$date_types))
			{
				$show_field = 1;
			}
			else if($type == 'attribute' && $myfield['fld_type'] == 'hidden_function' && !in_array($myfield['fld_hidden_func'],$hidden_date_types))
			{
				$show_field = 1;
			}
			else if($type == 'num_attribute' && in_array($myfield['fld_type'],$num_types))
			{
				$show_field = 1;
			}
			if($show_field == 1)
			{
				if($type == 'all')
				{
					$keyfield = $keyfield . "__fld";
				}
				elseif($type == 'notify')
				{
					$keyfield = $keyfield . "__" . $ent_id;
				}
				$return .= "<option value='" . $keyfield . "'";
                                if($keyfield == $value)
                                {
                                        $return .= " selected";
                                }
                                $return .= ">" . esc_html($myfield['fld_label']) . "</option>";
			}
		}
	}
	return $return;
}	
	

function wpas_get_tax_values()
{

	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$tax_id = isset($_GET['tax_id']) ? $_GET['tax_id'] : '';
	$value = isset($_GET['value']) ? $_GET['value'] : '';
	if($app_id == null)
	{
		die(-1);
	}
	$return = "<select><option value=''>" . __("Apply to all","wpas") . "</option>";
	$app = wpas_get_app($app_id);
        if($app !== false && !empty($app['taxonomy'][$tax_id]['txn-values']))
        {
		$tax_values = explode(";",$app['taxonomy'][$tax_id]['txn-values']);
		foreach($tax_values as $tax_val)
		{
			preg_match('/(.*)\{(.*)\}/',$tax_val,$matches);
			if(empty($matches))
			{
				$tval = str_replace("$","",$tax_val);
                        }
			elseif(!empty($matches[1]))
			{
				$tval = str_replace("$","",$matches[1]);
			}
			$return .= "<option value='". $tval . "'";
			if($value == $tval)
			{
				$return .= " selected";
			}
			$return .= ">" . $tval . "</option>";
		}
	}
	$return .= "</select>";
	echo $return;
	die();
}
function wpas_check_email()
{
	wpas_is_allowed();
	$email_list = isset($_GET['email_list']) ? $_GET['email_list'] : '';
	$emails = explode(",",$email_list);
	if(!empty($emails))
	{
		foreach($emails as $myemail)
		{
			if(!is_email($myemail))
			{
				echo false;
				die();
			}
		}
	}
	echo true;
	die();
}

	
function wpas_get_ent_layout_attrs()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$ent_id = isset($_GET['ent_id']) ? $_GET['ent_id'] : '';
	$return = "<select><option value=''>" . __("Please select","wpas") . "</option></select>";
	if($app_id != null)
	{
		$app = wpas_get_app($app_id);
		if($app !== false && !empty($app['entity'][$ent_id]['field']))
		{
			$return =  wpas_get_select_attrs($app['entity'][$ent_id]['field']);
		}
	}
	echo $return;
	die();
}

function wpas_get_select_attrs($field,$val="")
{
	$options = "<select class='attr-sel span10'><option value=''>" . __("Please select","wpas") . "</option>";
	foreach($field as $keyfield=>$myfield)
	{
		if(!isset($myfield['fld_builtin']) || $myfield['fld_builtin'] == 0)
		{ 
			$options .= "<option value='" . $keyfield . "'";
			if($val != '' && $keyfield == $val)
			{
				$options .= " selected";
			}
			$options .= ">" . $myfield['fld_label'] . "</option>";
		}
	}
	$options .= "</select>";
	$return = "<div class=\"span2 layout-edit-icons\">";
	$return .= "<a class=\"delete-attr\">
		<i class=\"icon-minus-sign pull-right\"></i></a>";
	$return .= "</div>" . $options;			
	return $return;
}	

function wpas_get_search_forms()
{
	wpas_is_allowed();
	$return = "<option value=''>" . __("Please select","wpas") . "</option>";
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$val = isset($_GET['val']) ? $_GET['val'] : '';
	if($app_id == null)
	{
		die(-1);
	}
	$app = wpas_get_app($app_id);
	if($app !== false && !empty($app['form']))
	{
		foreach($app['form'] as $keyform => $myform)
		{
			if($myform['form-form_type'] == 'search')
			{
				$return .= '<option value="' . $keyform . '"'; 
				if($val == $keyform)
				{
					$return .= " selected";
				}
				$return .= '>' . $myform['form-name'] . '</option>';
			}
		}
	}
	echo $return;
	die();
}
function wpas_form_layout_save()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_save_form_layout_nonce','nonce');
	$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$form_id = isset($_POST['form_id']) ? $_POST['form_id'] : '';
	$data = isset($_POST['data']) ? $_POST['data'] : '';
	if(empty($app_id) || $form_id == null || empty($data))
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
		$prev_seq = 0;
		$total_size = 0;
		$next_seq = 1;
		$layout_fields = Array();
		$entkey = $app['form'][$form_id]['form-attached_entity'];
		$dependent_arr = wpas_get_dependent_arr($app,$entkey);
		
		foreach($app['entity'][$entkey]['field'] as $keyfld => $fld)
		{
			if(!in_array($keyfld,$req_fields))
			{
				if($app['form'][$form_id]['form-form_type'] == 'submit' && isset($fld['fld_required']) && $fld['fld_required'] == 1)
				{
					$req_fields[] =  $keyfld;
				}
				elseif($app['form'][$form_id]['form-form_type'] == 'search' && isset($fld['fld_srequired']) && $fld['fld_srequired'] == 1)
				{
					$req_fields[] =  $keyfld;
				}
			}
		}
		foreach($app['taxonomy'] as $keytax => $tax)
		{
			if(in_array($entkey,$tax['txn-attach']))
			{
				if(!in_array($keytax,$req_taxs))
				{
					if($app['form'][$form_id]['form-form_type'] == 'submit' && isset($tax['txn-required']) && $tax['txn-required'] == 1)
					{
						$req_taxs[] = $keytax;
					}
					elseif($app['form'][$form_id]['form-form_type'] == 'search' && isset($tax['txn-srequired']) && $tax['txn-srequired'] == 1 )
					{
						$req_taxs[] = $keytax;
					}
				}
			}
		}
		if(!empty($app['form'][$form_id]['form-dependents']) && !empty($dependent_arr))
		{
			foreach($dependent_arr as $keyrel => $rel)
			{
				$myrel = $app['relationship'][$keyrel];
				if(!in_array($keyrel,$req_rels) && in_array($keyrel,$app['form'][$form_id]['form-dependents']))
				{
					if($app['form'][$form_id]['form-form_type'] == 'submit' && isset($myrel['rel-required']) && $myrel['rel-required'] == 1)
					{
						$req_rels[] = $keyrel;
					}
					elseif($app['form'][$form_id]['form-form_type'] == 'search' && isset($myrel['rel-srequired']) && $myrel['rel-srequired'] == 1)
					{
						$req_rels[] = $keyrel;
					}
				}
			}
		}
		$multiple_submits = 0;
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
				if(isset($key_arr[3]) && $prev_seq != $key_arr[3])
				{
					if($total_size > 12)
					{
						$resp = 5;
						echo $resp;
						die();
					}
					$total_size = 0;
					$counter++;
				}
				elseif(!isset($key_arr[3]) && $prev_seq != $key_arr[2])
				{
					if($total_size > 12)
					{
						$resp = 5;
						echo $resp;
						die();
					}
					$total_size = 0;
					$counter++;
				}
				if($key_arr[2] == 'size')
				{
					if($total_size > 12)
                                        {
                                                $resp = 5;
                                                echo $resp;
                                                die();
                                        }
					$layout_fields[$counter][$key_arr[4]]['size'] = $form_field_value;
					$prev_seq = $key_arr[3];
					$total_size += $form_field_value;
				}
				elseif($key_arr[1] == 'text' && $key_arr[2] == 'desc')
				{
					$layout_fields[$counter]['obtype'] = 'text';
					$layout_fields[$counter]['desc'] = sanitize_text_field($form_field_value);
					$prev_seq = $key_arr[2];
				}
				elseif($key_arr[1] == 'hr')
				{
					$layout_fields[$counter]['obtype'] = 'hr';
					$prev_seq = $key_arr[2];
				}		
				elseif($key_arr[2] == 'select')
				{
					if($form_field_value == 'submit')
					{
						$layout_fields[$counter][$key_arr[4]]['entity'] = 0;
						$layout_fields[$counter][$key_arr[4]]['attr'] = 0;
						$layout_fields[$counter][$key_arr[4]]['obtype'] = 'btn-std';
						$multiple_submits ++;
						$prev_seq = $key_arr[3];
					}
					else
					{
						$val_arr = explode("__",$form_field_value);
						if(in_array($val_arr[1],$attr_fields) || in_array($val_arr[1],$taxs) 
							|| in_array($val_arr[1],$rels))
						{
							$resp = 3; //dupe field send error
							echo $resp;
							die();
						}
						$layout_fields[$counter][$key_arr[4]]['entity'] = $val_arr[0];
						$ent = $app['entity'][$val_arr[0]];
						if(preg_match('/fld/',$val_arr[1]))
						{
							$fld_id = str_replace('fld','',$val_arr[1]);
							$fld = $ent['field'][$fld_id];
							$attr_fields[] = $val_arr[1];
							$layout_fields[$counter][$key_arr[4]]['attr'] = $fld_id;
							$layout_fields[$counter][$key_arr[4]]['obtype'] = 'attr';
						}
						elseif(preg_match('/blttax/',$val_arr[1]))
						{
							$tax_id = str_replace('blttax_','',$val_arr[1]);
							$taxs[] = $val_arr[1];
							$layout_fields[$counter][$key_arr[4]]['tax'] = $tax_id;
							$layout_fields[$counter][$key_arr[4]]['obtype'] = 'tax';
						}
						elseif(preg_match('/tax/',$val_arr[1]))
						{
							$tax_id = str_replace('tax','',$val_arr[1]);
							$tax = $app['taxonomy'][$tax_id];
							$taxs[] = $val_arr[1];
							$layout_fields[$counter][$key_arr[4]]['tax'] = $tax_id;
							$layout_fields[$counter][$key_arr[4]]['obtype'] = 'tax';
						}
						elseif(preg_match('/rel/',$val_arr[1]))
						{
							$rel_id = str_replace('rel','',$val_arr[1]);
							$rel = $app['relationship'][$rel_id];
							$rels[] = $val_arr[1];
							$layout_fields[$counter][$key_arr[4]]['relent'] = $rel_id;
							$layout_fields[$counter][$key_arr[4]]['obtype'] = 'relent';
						}
						$prev_seq = $key_arr[3];
					}
				}
			}	
		}
		if(!empty($layout_fields))
		{
			if($multiple_submits > 1)
			{
				$resp = 6;
				echo $resp;
				die();
			}
			foreach($req_fields as $myreq_field)
			{
				if(!in_array('fld'.$myreq_field,$attr_fields))
				{
					$resp = 2; // required ent fields missing
					echo $resp;
					die();
				}
			}
			foreach($req_taxs as $myreq_tax)
			{
				if(!in_array('tax'.$myreq_tax,$taxs))
				{
					$resp = 2;
					echo $resp;
					die();
				}
			}
			foreach($req_rels as $myreq_rel)
			{
				if(!in_array('rel'.$myreq_rel,$rels))
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
function wpas_get_form_layout_select($app,$form_id,$count,$value)
{
	$ret_option = "";
	$entity_filter_id = $app['form'][$form_id]['form-attached_entity'];
	$myentity = $app['entity'][$entity_filter_id];
	$tax_selects = "";
	if(!empty($myentity))
	{
		if(!empty($myentity['ent-taxonomy_category']) && $myentity['ent-taxonomy_category'] == 1)
		{
			$key1 = $entity_filter_id . "__blttax_cat";
			$tax_selects .= "<option value='" . esc_attr($key1) . "' style='padding-left:2em;'";
			if($value == $key1)
			{
				$tax_selects .= " selected";
			}
			$tax_selects .= ">Categories</option>";
		}
		if(!empty($myentity['ent-taxonomy_post_tag']) && $myentity['ent-taxonomy_post_tag'] == 1)
		{
			$key1 = $entity_filter_id . "__blttax_tag";
			$tax_selects .= "<option value='" . esc_attr($key1) . "' style='padding-left:2em;'";
			if($value == $key1)
			{
				$tax_selects .= " selected";
			}
			$tax_selects .= ">Tags</option>";
		}
		if(!empty($myentity['field']))
		{
			$ret_option .="<option value='' style='font-style:italic;font-weight:bold;'>" . esc_html($myentity['ent-label']) . " Attributes</option>";
			foreach($myentity['field'] as $keyfield => $myfield)
			{
				if($count > 1 && $app['form'][$form_id]['form-form_type'] == 'submit' && in_array($myfield['fld_type'],Array('hidden_constant','hidden_function')))
				{
					continue;
				}
				$fval = $entity_filter_id . "__fld" . $keyfield;
				$ret_option .= "<option value='" . esc_attr($fval) . "' style='padding-left:2em;'";
				if($value == $fval)
				{
					$ret_option .= " selected";
				}
				if(in_array($myfield['fld_type'],Array('hidden_constant','hidden_function')))
				{
					$ret_option .= " ftype='hidden'";
				}
				$ret_option .= ">";
				if($app['form'][$form_id]['form-form_type'] == 'submit' && isset($myfield['fld_required']) && $myfield['fld_required'] == 1)
				{
					$ret_option .= "* ";
				}
				elseif($app['form'][$form_id]['form-form_type'] == 'search' && isset($myfield['fld_srequired']) && $myfield['fld_srequired'] == 1)
				{
					$ret_option .= "* ";
				}
				$ret_option .= esc_html($myfield['fld_label']). "</option>";
			}
		}
	}
	if(!empty($app['taxonomy']))
	{
		foreach($app['taxonomy'] as $keytax => $mytax)
		{
			$val_req = "";
			foreach($mytax['txn-attach'] as $mytxn_attach)
			{
				if($mytxn_attach == $entity_filter_id)
				{
					if($app['form'][$form_id]['form-form_type'] == 'submit' && isset($mytax['txn-required']) && $mytax['txn-required'] == 1)
					{
						$val_req = "* ";
					}
					elseif($app['form'][$form_id]['form-form_type'] == 'search' && isset($mytax['txn-srequired']) && $mytax['txn-srequired'] == 1)
					{
						$val_req = "* ";
					}
					$key1 = $entity_filter_id . "__tax" . $keytax;
					$tax_selects .= "<option value='" . esc_attr($key1) . "' style='padding-left:2em;'";
					if($value == $key1)
					{
						$tax_selects .= " selected";
					}
					$tax_selects .= ">" . $val_req . esc_html($mytax['txn-singular-label']) . "</option>";
				}
			}
		}
	}
	if(!empty($tax_selects))
	{
		$ret_option .="<option value='' style='font-style:italic;font-weight:bold;'>" . esc_html($myentity['ent-label']) . " Taxonomies</option>" . $tax_selects;
	}
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
		$ret_option .="<option value='' style='font-style:italic;font-weight:bold;'>Relationships</option>";
		foreach($form_dependents as $mydep)
		{
			$rel_req = "";
			$valr = $entity_filter_id . "__rel" . $mydep;
			$ret_option .= "<option style='padding-left:2em;' value='" . esc_attr($valr) . "'"; 
			if($value == $valr)
			{
				$ret_option .= " selected";
			}
			$rel = $app['relationship'][$mydep];
			if($app['form'][$form_id]['form-form_type'] == 'submit' && isset($rel['rel-required']) && $rel['rel-required'] == 1)
			{
				$rel_req = "* ";
			}
			elseif($app['form'][$form_id]['form-form_type'] == 'search' && isset($rel['rel-srequired']) && $rel['rel-srequired'] == 1)
			{
				$rel_req = "* ";
			}
			$ret_option .= ">" . $rel_req . esc_html(wpas_get_rel_full_name($rel,$app)) . "</option>";
		}
	}
	return $ret_option;
}
function wpas_get_form_html()
{
	wpas_is_allowed();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$form_id = isset($_GET['form_id']) ? $_GET['form_id'] : '';
	$field_count = isset($_GET['field_count']) ? $_GET['field_count'] : '1';
	$count = isset($_GET['count']) ? $_GET['count'] : '1';
	$selected = isset($_GET['selected']) ? $_GET['selected'] : Array();
	$selected_sizes = isset($_GET['selected_size']) ? $_GET['selected_size'] : Array();
	if(empty($app_id) && $form_id == null)
        {
                die();
        }
	$app = wpas_get_app($app_id);	
	if($app !== false && !empty($app['form'][$form_id]))
	{
		echo wpas_get_form_layout_select_all($app,$form_id,$count,$field_count,0,$selected,$selected_sizes);
	}
	die();
}
function  wpas_get_form_layout_select_all($app,$form_id,$count,$field_count,$is_hidden=0,$values=Array(),$sizes=Array())
{
	$res = "";
	$value = "";
	$label = "Attach To";
	for($i=1;$i<=$count; $i++)
	{
		$res .= '<div class="row-fluid"><div>';
		$res .= '<div class="span2 layout-edit-icons"><div class="pull-right"><a class="delete-element"';
		if($count != $i || $count == 1)
		{
			$res .= ' style="display:none;"';
		}
		$res .= ' id="delete-element' . '-' . $field_count . "-" . ($i-1) . '"><i class="icon-minus-sign"></i></a>';
		$res .= '<a class="add-element"';
	
		//check entitiy field count , tax count and related entity counts
		list($ent_field_count,$rel_count,$tax_count) = wpas_get_ent_tax_rel_count($app,$form_id);
		$total_count = $ent_field_count + $rel_count + $tax_count + 1;


                if($count != $i || $count == 12 || $total_count <= $i || $is_hidden == 1)
		{
			$res .= ' style="display:none;"';
		}
		$res .= ' id="add-element' . '-' . $field_count . "-" . ($i+1) . '"><i class="icon-plus-sign"></i></a></div></div>';
		$res .= '<label class="control-label span2">' . $label . '</label>
			<div class="controls span6">';
		$res .= '<select name="form-element-select-' . $field_count . "-" . $i .'" id="form-element-select-' . $field_count . "-" . $i .'" class="form-element-select" style="width:190px;">';
		$res .= '<option value=""> ' . __("Please select","wpas") . ' </option>';		
		if(!empty($values[$i]))
		{
			$value = $values[$i];
		}
		else
		{
			$value = "";
		}
		$res .= wpas_get_form_layout_select($app,$form_id,$count,$value);
		$res .= '<option value="" style="font-style:italic;font-weight:bold;"> ' . __("Buttons","wpas") . ' </option>';
		$res .= '<option value="submit" style="padding-left:2em;"';
		if($value == 'submit')
		{
			$res .= " selected";
		}
		$res .= '> ' . __("Submit Button","wpas") . ' </option>';
		$res .= '</select></div></div>';
		$res .= '<label class="control-label span1">Size</label>';
		$res .= '<div class="controls span1"><select style="width:43px;" class="form-element-size" id="form-element-size-' . $field_count . "-" . $i . '" name="form-element-size-' . $field_count . "-" . $i . '">';
		if(empty($sizes))
		{
			$sizes[$i] = 12;
		}
		for($size=1;$size<=12;$size++)
		{
			$res .= '<option value="' . $size . '"'; 
			if(isset($sizes[$i]) && $sizes[$i] == $size)
			{
				$res .= " selected";
			}
			$res .= '>' . $size . '</option>';
		}
		$res .= '</select></div>';
		$res .= '</div>';
	}
	return $res;
}

function wpas_get_form_text_html()
{
	wpas_is_allowed();
	$field_count = isset($_GET['field_count']) ? $_GET['field_count'] : '1';
	echo '<div class="control-group row-fluid">
		<label class="control-label span3">' . __("Description","wpas") . '</label>
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

function wpas_check_entity_has_email($entities,$ent_id,$myrel='')
{
	$ent_fld_list = Array();
	if(!empty($entities[$ent_id]['field']))
	{
		foreach($entities[$ent_id]['field'] as $keyfld => $myentfld)
		{
			if($myentfld['fld_type'] == 'email')
			{
				$ent_fld['id'] = $ent_id;
				$ent_fld['fld_id'] = $keyfld;
				$ent_fld['fld'] = $myentfld['fld_label'];
				if(!empty($myrel))
				{
					$ent_fld['rel'] = $myrel;
				}
				$ent_fld_list[] = $ent_fld;
			}
		}
	}
	return $ent_fld_list;
}	
function wpas_get_email_rel_list($app,$attach_list)
{
	$check_ents = Array();
	foreach($attach_list as $attach_to)
	{
		$rels = wpas_get_dependent_arr($app,$attach_to);
		foreach($rels as $krel => $myrel)
		{
			if($app['relationship'][$krel]['rel-from-name'] == $attach_to)
			{
				$ent_email_fld =  wpas_check_entity_has_email($app['entity'],$app['relationship'][$krel]['rel-to-name'],$myrel);
				if(!empty($ent_email_fld))
				{
					$check_ents[$krel] = $ent_email_fld;
				}
			}
			elseif($app['relationship'][$krel]['rel-to-name'] == $attach_to)
			{
				$ent_email_fld =  wpas_check_entity_has_email($app['entity'],$app['relationship'][$krel]['rel-from-name'],$myrel);
				if(!empty($ent_email_fld))
				{
					$check_ents[$krel] = $ent_email_fld;
				}
			}
		}
	}
	return $check_ents;
}
function wpas_get_email_attrs()
{
	$return = ""; 
	$rel_name = "";
	$check_ents = Array();
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$level = isset($_GET['level']) ? $_GET['level'] : '';
	$attach_to = isset($_GET['attach_to']) ? $_GET['attach_to'] : '';
	$values = isset($_GET['values']) ? stripslashes_deep($_GET['values']) : Array();
	$app = wpas_get_app($app_id);
	if(!is_array($values))
        {
                $values= Array("$values");
        }
	if($app !== false && !empty($app['entity']) && $level != '')
	{
		if($level == 'attr')
		{
			$vals = explode("__",$attach_to);
			$attach_to = $vals[1];
		}
		if($level == 'com')
		{
			$return .= "<option value='com_author_email'";
			if(in_array('com_author_email',$values)){
				$return .= " selected";
			}
			$return .= ">Comment Author Email</option>";
		}
		elseif($level != 'rel')
		{
			$ent_email = wpas_check_entity_has_email($app['entity'],$attach_to);
			if(!empty($ent_email))
			{
				foreach($ent_email as $efld)
				{
					$fval = $efld['id'] . "__" . $efld['fld_id'];
					$return .= "<option value='" . $fval . "'";
					if(in_array($fval,$values))
					{
						$return .= " selected";
					}
					$return .= ">" . $efld['fld'] . " --- " . $app['entity'][$attach_to]['ent-label'] . "</option>";
				}
			}
		}
		if($level == 'rel')
		{
			$attach_list = Array($app['relationship'][$attach_to]['rel-to-name'],$app['relationship'][$attach_to]['rel-from-name']);
			$check_ents = wpas_get_email_rel_list($app,$attach_list);
		}
		else
		{
			$check_ents = wpas_get_email_rel_list($app,Array($attach_to));
		}
		if(!empty($check_ents))
		{
			foreach($check_ents as $krel => $ent)
			{
				foreach($ent as $efld)
				{
					$fval = $efld['id'] . "__" . $efld['fld_id'] . "__" . $krel;
					$return .= "<option value='" . $fval . "'"; 
					if(in_array($fval,$values))
					{
						$return .= " selected";
					}
					$return .= ">" . $efld['fld'] . " --- " . $efld['rel'] . "</option>"; 	
				}
			}
		}
	}
	if(empty($return))
	{
		$return = "<option value=''>" . __("First define an email attribute type.","wpas") . "</option>";
	}
	echo $return;
	die();
}
function wpas_get_roles()
{
//rel-limit_user_relationship_role
	wpas_is_allowed();
	$return = "";
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
	$value = isset($_GET['value']) ? stripslashes_deep($_GET['value']) : Array();
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	if($type == 'entity')
	{
		$return = "<option value='false'";
		if($value == '' || $value == 'false')
		{
			$return .= " selected";
		}
		$return .= ">" . __("Do not limit") . "</option>";
	}
	if(!is_array($value) && $value != 'false')
	{
		$value= Array("$value");
	}
	$app = wpas_get_app($app_id);
	if($app !== false && !empty($app['role']))
	{
		foreach($app['role'] as $keyrole => $myrole)
		{
			$return .= "<option value='" . $keyrole . "'"; 
			if($value != 'false' && in_array($keyrole,$value))
			{
				$return .= " selected";
			}
			if($type == 'entity')
			{
				$return .= ">" . sprintf(__('Only %s can be related','wpas'),esc_html($myrole['role-label'])) . "</option>";
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
				if(time() < $mysubmit['refresh_time'] + 120)
				{
					$resp[3] = __("Your app is in queue and will be generated soon. Thank you for your patience.","wpas");
					$no_check =1;
				}
				elseif(!empty($mysubmit['status']))
				{
					if(strpos($mysubmit['status'],'Success') !== false || strpos($mysubmit['status'],'Error') !== false || strpos($mysubmit['status'],'Change') !== false )
					{
						$resp[0] = esc_url($mysubmit['status_link']);
						$resp[1] = esc_html($mysubmit['status']);
						$resp[4] = esc_url($mysubmit['buy_link']);
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
			$resp[2] = __("System error, please try again later.","wpas");
		}
		elseif($xml_status->error)
		{
			if((string) $xml_status->error == 'error')
			{
				$resp[0] = esc_url($xml_status->url);
				$resp[1] = '<span style="color:red;">' . __("Error","wpas") . '</span>';
				$resp[2] = esc_html($xml_status->errormsg);
				$new_submit['status']= $resp[1];
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
				$resp[1] = '<span style="color:green;">' . __("No Change Detected","wpas") . '</span>';
			}
			else
			{
				$resp[1] = '<span style="color:green;">' . __("Success","wpas") . '</span>';
			}
			
			$resp[0] = esc_url($xml_status->url);
			$resp[4] = esc_url($xml_status->buy_url);
			$new_submit['status']= $resp[1];
			$new_submit['status_link']= $resp[0];
			$new_submit['buy_link'] = $resp[4];
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
	$help_type = isset($_GET['help_type']) ? $_GET['help_type'] : '';
	$attached_id = isset($_GET['attached_id']) ? $_GET['attached_id'] : '';
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
				if($myhelp['help-type'] == $help_type && (($help_type == 'ent' && $myhelp['help-entity'] == $attached_id && $myhelp['help-screen_type'] == $screen_type) || ($help_type == 'tax' && $myhelp['help-tax'] == $attached_id)))
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
	echo $resp;
	die();
}

function wpas_check_unique()
{
	wpas_is_allowed();
	$response = true;
	$name = isset($_GET['value']) ? $_GET['value'] : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$comp_id = isset($_GET['comp_id']) ? $_GET['comp_id'] : '';
	$par_id = isset($_GET['par_id']) ? $_GET['par_id'] : '';
	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';

	if($type != 'app' && !empty($app_id))
	{
		$app = wpas_get_app($app_id);
	}
		
	switch ($type) {
		case 'app':
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
			break;
		case 'ent':
			if(!empty($app['entity']))
			{
				foreach($app['entity'] as $key => $myent)
				{
					if($name == $myent['ent-name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}	
				}
			}
			break;
		case 'ent_field':
			if($par_id != null && !empty($app['entity'][$par_id]['field']))
			{
				foreach($app['entity'][$par_id]['field'] as $key => $myfield)
				{
					if($name == $myfield['fld_name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'rel':
			if(!empty($app['relationship']))
			{
				foreach($app['relationship'] as $key => $myrel)
				{
					if($name == $myrel['rel-name']  && ($comp_id == null || $comp_id != $key))
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'rel_field':
			if($par_id != null && !empty($app['relationship'][$par_id]['field']))
			{
				foreach($app['relationship'][$par_id]['field'] as $key => $myfield)
				{
					if($name == $myfield['rel_fld_name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'txn':
			if(!empty($app['taxonomy']))
			{
				foreach($app['taxonomy'] as $key => $mytxn)
				{
					if($name == $mytxn['txn-name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			if(!empty($app['taxonomy']))
			{
				foreach($app['entity'] as $myent)
				{
					if($name == $myent['ent-name'])
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'help_fld':
			if($par_id != null && !empty($app['help'][$par_id]['field']))
			{
				foreach($app['help'][$par_id]['field'] as $key => $myfield)
				{
					if($name == $myfield['help_fld_name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'widg':
			if(!empty($app['widget']))
			{
				foreach($app['widget'] as $key => $mywidg)
				{
					if($name == $mywidg['widg-name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'form':
			if(!empty($app['form']))
			{
				foreach($app['form'] as $key => $myform)
				{
					if($name == $myform['form-name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'shc':
			if(!empty($app['shortcode']))
			{
				foreach($app['shortcode'] as $key => $myshc)
				{
					if($name == $myshc['shc-label'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			break;
		case 'role':
			if(!empty($app['role']))
			{
				foreach($app['role'] as $key => $myrole)
				{
					if($name == $myrole['role-name'] && ($comp_id == null || $key != $comp_id))
					{
						$response = false;
						break;
					}
				}
			}
			break;
	}
	echo $response;
	die();
}

function wpas_save_layout()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_save_layout_nonce','nonce');
	$layout = "";
	$all_attrs = Array();
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
	if(!empty($_POST['all_attrs']) && is_array($_POST['all_attrs']))
	{	
		$all_attrs = $_POST['all_attrs'];
	}
	$app = wpas_get_app($app_id);
	if($app !== false && is_array($app['entity'][$ent_id]))
	{
		$all_fields = 0;
		if(!empty($app['entity'][$ent_id]['field']))
		{
			foreach($app['entity'][$ent_id]['field'] as $myfield)
			{
				if(!isset($myfield['fld_builtin']) || $myfield['fld_builtin'] == 0)
				{
					$all_fields++;
				}
			}
		}
		if(count($all_attrs) != count(array_unique($all_attrs)))
		{
			echo 2; //dupe
			die();
		}
		elseif(!empty($all_attrs) && $all_fields != count($all_attrs))
		{
			echo 3; //not all fields added
			die();
		}
		else
		{
			$app['entity'][$ent_id]['layout'] = $layout;
			$app['entity'][$ent_id]['modified_date'] = date("Y-m-d H:i:s");
			wpas_update_app($app,$app_id);
			echo 1;
			die();
		}
	}
	die();
}
function wpas_get_layout()
{
	wpas_is_allowed();
	$layout = "";
	$fields = Array();
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
	if(!empty($app['entity'][$ent_id]['field']))
	{
		$fields = $app['entity'][$ent_id]['field'];
	}
	echo  wpas_entity_container($layout,$fields);
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
function wpas_entity_types($app_id,$type,$values="",$subtype="",$tax_id="",$chart_ent="",$add_select=0)
{
	$return = "";
	$selected = 0;
	if($values != "" && !is_array($values))
	{
		$values= Array("$values");
	}
	$app = wpas_get_app($app_id);
	if($add_select == 1 && ($type != 'taxonomy' || !empty($chart_ent)))
	{
		$return = "<option value=''>" . __("Please select","wpas") . "</option>";
	}
	if($app !== false && !empty($app['entity']) && $type != 'tax')
	{
		if($type == 'relationship_from' || $type == 'relationship_to')
		{
			$return .= "<option value='user'";
			if(in_array('user',$values))
			{
				$return .= " selected";
				$selected = 1;
			}
			$return .= ">" . __("Users","wpas") . "</option>";
		}		
		foreach($app['entity'] as $keyent => $myent)
		{
			$show_ent = 0;
			if($tax_id != "" && $type == 'shortcode')
			{
				if(in_array($keyent,$app['taxonomy'][$tax_id]['txn-attach']))
				{
					$show_ent =1;
				}
			}
			else
			{
				if(!empty($myent['field']))
				{
					$show_ent = 1;
				}
				if($type == 'widget-comment' && $myent['ent-com_type'] == 'wp')
				{
					$show_ent = 0;
				}
				if($type == 'shortcode' && !empty($app['shortcode']))
				{
					foreach($app['shortcode'] as $myshc)
					{
						if($myshc['shc-view_type'] == $subtype && $myshc['shc-attach'] == $keyent && !in_array($keyent,$values))
						{
							$show_ent = 0;
						}
					}
				}
			}		
			if($show_ent == 1)
			{
				$return .= "<option value='" . $keyent . "'"; 
				if($selected == 0 && is_array($values) && in_array($keyent,$values))
				{
					$return .= " selected";
				}
				$return .= ">" . esc_html($myent['ent-label']) . "</option>";
			}
		}
	}
	elseif($app !== false && !empty($app['taxonomy']) && ($type == 'tax' || $type == 'chart'))
	{
		foreach($app['taxonomy'] as $keytxn => $mytxn)
		{
			$show_tax = 1;
			if(!empty($subtype))
			{
				foreach($app['shortcode'] as $myshc)
				{
					//if($myshc['shc-view_type'] == $subtype && $myshc['shc-attach_tax'] == $keytxn && !in_array($keytxn,$values) && (!isset($myshc['shc-attach_taxterm']) && $myshc['shc-attach_taxterm'] == '') && empty($mytxn['txn-values']))
					//{
				//		$show_tax = 0;
					//}
				}
			}
			if(!empty($chart_ent))
			{
				if(!in_array($chart_ent,$mytxn['txn-attach']))
				{
					$show_tax = 0;
				}
			}
			if($show_tax == 1)
			{
				if(!empty($chart_ent) && $add_select == 0)
				{
					$keytxn = $keytxn . "__tax";
				}
				$return .= "<option value='" . $keytxn . "'"; 
				if(in_array($keytxn,$values))
				{
					$return .= " selected";
				}
				$return .= ">" . esc_html($mytxn['txn-label']) . "</option>";
				if(!empty($chart_ent) && $add_select == 0)
				{
					$return .= "<option value='" . $keytxn . "__nl'"; 
					$return .= ">" . esc_html($mytxn['txn-label']) . " No Link</option>";
				}
			}
		}
	}
	return $return;
}

function wpas_get_rel_full_name($myrel,$app)
{
	if($myrel['rel-from-name'] == 'user')
	{
		$rel_ent = "Users";
	}
	else
	{
		$rel_ent = $app['entity'][$myrel['rel-from-name']]['ent-label'];
	}
	if(!empty($myrel['rel-from-title']))
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
	if($myrel['rel-to-name'] == 'user')
	{
		$rel_ent .= "Users";
	}
	else
	{
		$rel_ent .= $app['entity'][$myrel['rel-to-name']]['ent-label'];
	}
	if(!empty($myrel['rel-to-title']))
	{
		$rel_ent .= " (" . $myrel['rel-to-title'] . ")";
	}
	if(empty($myrel['rel-from-title']) && empty($myrel['rel-to-title']))
	{
		$rel_ent .= " (" . $myrel['rel-name'] . ")";
	}
	return $rel_ent;
}
			
	


function wpas_get_dependent_arr($app,$primary_entity)
{
	$dependent_arr = Array();
	if($app !== false && !empty($app['relationship']))
	{
		foreach($app['relationship'] as $keyrel => $myrel)
		{
			if($primary_entity == $myrel['rel-from-name'] || $primary_entity == $myrel['rel-to-name'])
			{
				$dependent_arr[$keyrel] = wpas_get_rel_full_name($myrel,$app);
			}
		}
	}
	return $dependent_arr;
}

function wpas_dependent_entities($app_id,$primary_entity,$values,$dgrid=0)
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
			if($dgrid == 1)
			{
				$key = $key . "__rel";
			}
			$return .= "<option value='" . esc_attr($key) . "'"; 
			if(in_array($key,$values))
			{
				$return .= " selected";
			}
			$return .= ">" . esc_html($dependent) . "</option>";
			if($dgrid == 1)
			{
				$return .= "<option value='" . esc_attr($key. "__nl") . "'"; 
				$return .= ">" . esc_html($dependent) . " No Link</option>";
			}
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
	$subtype = isset($_GET['subtype']) ? $_GET['subtype'] : '';
	$tax_id = isset($_GET['tax_id']) ? $_GET['tax_id'] : '';
	$chart_ent = isset($_GET['chart_ent']) ? $_GET['chart_ent'] : '';
	$add_sel = isset($_GET['add_sel']) ? $_GET['add_sel'] : 0;
	$return = "";
	if($add_sel == 1)
	{
		$return = "<option value=''>" . __("Please select","wpas") . "</option>";
	}
	if($app_id == null)
	{
		die(-1);
	}
	if($type == 'form_dependents')
	{
		$primary_entity = isset($_GET['primary_entity']) ? $_GET['primary_entity'] : '';
		echo $return . wpas_dependent_entities($app_id,$primary_entity,$values,0);
	}
	else
	{
		echo wpas_entity_types($app_id,$type,$values,$subtype,$tax_id,$chart_ent,1);
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
		//also delete this attr from entity layout
		if(!empty($app[$type][$comp_id]['layout']))
		{
			foreach($app[$type][$comp_id]['layout'] as $lkey => $mylayout)
			{
				if(isset($mylayout['tabs']))
				{
					foreach($mylayout['tabs'] as $tkey => $mytab)
					{
						if(in_array($field_id,$mytab['attr']))
						{
							$my_attr_key = array_search($field_id,$mytab['attr']);
							unset($mylayout['tabs'][$tkey]['attr'][$my_attr_key]);
							if(count($mylayout['tabs'][$tkey]['attr']) == 0)
							{
								unset($mylayout['tabs'][$tkey]);
								if(count($mylayout['tabs']) == 0)
								{
									unset($mylayout['tabs']);
									break;
								}
							}
						}
					}
				}
				if(isset($mylayout['accs']))
				{
					foreach($mylayout['accs'] as $akey => $myacc)
					{
						if(in_array($field_id,$myacc['attr']))
						{
							$my_attr_key = array_search($field_id,$myacc['attr']);
							unset($mylayout['accs'][$akey]['attr'][$my_attr_key]);
							if(count($mylayout['accs'][$akey]['attr']) == 0)
							{
								unset($mylayout['accs'][$akey]);
								if(count($mylayout['accs']) == 0)
								{
									unset($mylayout['accs']);
									break;
								}
							}
						}
					}
				}
				$app[$type][$comp_id]['layout'][$lkey] = $mylayout;
			}
		}
		//delete this field from form layout
		if(!empty($app['form']))
		{
			foreach($app['form'] as $keyform => $myform)
			{
				if($myform['form-attached_entity'] == $comp_id && !empty($myform['form-layout']))
				{
					foreach($myform['form-layout'] as $keylayout => $myform_layout)
					{
						$change = 0;
						foreach($myform_layout as $keyel => $myform_layout_el)
						{
							if(isset($myform_layout_el['entity']) && $myform_layout_el['entity'] == $comp_id && isset($myform_layout_el['attr']) && $myform_layout_el['attr'] == $field_id)
							{
								unset($myform_layout[$keyel]);
								$change =1;
							}
						}
						if($change == 1)
						{
							if(count($myform_layout) >= 1)
							{		
								$app['form'][$keyform]['form-layout'][$keylayout] = array_combine(range(1,count($myform_layout)),array_values($myform_layout));
							}
							elseif(count($myform_layout) == 0)
							{
								unset($app['form'][$keyform]['form-layout'][$keylayout]);
							}
						}
					}
					if(count($app['form'][$keyform]['form-layout']) >= 1)
					{
						$app['form'][$keyform]['form-layout']= array_combine(range(1,count($app['form'][$keyform]['form-layout'])),array_values($app['form'][$keyform]['form-layout']));
					}
					elseif(count($app['form'][$keyform]['form-layout']) == 0)
					{
						$app['form'][$keyform]['form-layout'] = Array();
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
			elseif($field_form[0] == 'fld_limit_user_role')
			{
				$field[$field_form[0]][] = $field_form_value_sanitized;
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

	if(isset($field['fld_builtin']) && $field['fld_builtin'] == 1)
	{
		if($field['fld_name'] == 'blt_title')
		{
			$field['fld_type'] = 'text';
		}
		elseif($field['fld_name'] == 'blt_content')
		{
			$field['fld_type'] = 'wysiwyg';
		}
		elseif($field['fld_name'] == 'blt_excerpt')
		{
			$field['fld_type'] = 'textarea';
		}
	}


	$app[$type][$comp_id]['modified_date'] = date("Y-m-d H:i:s");
	if(isset($app[$type][$comp_id]['field'][$field_id]))
	{
		$old_field = $app[$type][$comp_id]['field'][$field_id];
	}
	$app[$type][$comp_id]['field'][$field_id] = $field;
	if($type == 'entity')
	{
		if(!empty($old_field) && $field['fld_name'] != $old_field['fld_name'])
		{
			$app = wpas_update_all_layout('attr',$old_field['fld_name'],$field['fld_name'],$app,$app_id);
		}
		echo wpas_view_entity($app[$type][$comp_id],$comp_id);
		echo wpas_view_ent_fields($app[$type][$comp_id]['ent-name']);       
		echo '<div id="ent-fld-list-div">';
		echo wpas_view_ent_fields_list($app[$type][$comp_id]['field']);     
		echo '</div>';
	}
	elseif($type == 'relationship')
	{
		if(!empty($old_field) && $field['rel_fld_name'] != $old_field['rel_fld_name'])
		{
			$app = wpas_update_all_layout('rel',$old_field['rel_fld_name'],$field['rel_fld_name'],$app,$app_id);
		}
		echo wpas_view_relationship($app[$type][$comp_id],$comp_id,$app);
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
		echo wpas_view_help($app[$type][$comp_id],$comp_id,$app);
		if(isset($app[$type][$comp_id]['help-entity'] ))
		{
			$help = $app[$type][$comp_id]['help-entity'];
		}
		elseif(isset($app[$type][$comp_id]['help-tax']))
		{
			$help = $app[$type][$comp_id]['help-tax'];
		}
		echo wpas_view_help_tabs($help);
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
		echo wpas_view_relationship($app['relationship'][$comp_id],$comp_id,$app);
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
		echo wpas_view_help($app['help'][$comp_id],$comp_id,$app);
		if(!empty($app[$type][$comp_id]['help-entity']))
		{
			echo wpas_view_help_tabs($app['entity'][$app[$type][$comp_id]['help-entity']]['ent-label']);
		}
		else
		{
			echo wpas_view_help_tabs($app['taxonomy'][$app[$type][$comp_id]['help-tax']]['txn-label']);
		}
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
		echo wpas_list('app',$app_list,0,1);
	}
	else
	{
		$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
		$comp_arr = isset($_POST['fields']) ? $_POST['fields'] : Array();
		$app = wpas_get_app($app_id);
		$roles_to_remove = Array();
		if(!empty($comp_arr) && !empty($app))
		{
		foreach ($comp_arr as $del_key)
		{
			$ent_arr = Array();
			$txn_arr = Array();
			$rel_arr = Array();
			$form_arr = Array();
			if($type == 'entity' && !empty($app[$type][$del_key]))
			{
				if(!in_array($app[$type][$del_key]['ent-name'],Array('post','page')))
				{
					unset($app[$type][$del_key]);
					$ent_arr[] = $del_key;
					$roles_to_remove[] = 'ent_' . $del_key;
				}
				//after deleting entity delete all attached objects
				//1- delete the taxonomy
				if(!empty($app['taxonomy']))
				{
					foreach($app['taxonomy'] as $tkey => $mytaxonomy)
					{
						if(count($mytaxonomy['txn-attach']) == 1 && in_array($del_key,$mytaxonomy['txn-attach']))
						{
							$txn_arr[] = $tkey;
							unset($app['taxonomy'][$tkey]);
							$roles_to_remove[] = 'tax_' . $tkey;
						}
						elseif(count($mytaxonomy['txn-attach']) > 1 && in_array($del_key,$mytaxonomy['txn-attach']))
						{
							$txn_attach_key = array_search($del_key,$mytaxonomy['txn-attach']);
							unset($app['taxonomy'][$tkey]['txn-attach'][$txn_attach_key]);
						}
					}
				}
				//2-delete the relationships
				if(!empty($app['relationship']))
				{
					foreach($app['relationship'] as $rkey => $myrelationship)
					{
						if($myrelationship['rel-from-name'] == $del_key)
						{
							unset($app['relationship'][$rkey]);
							$rel_arr[] = $rkey;
							$roles_to_remove[] = 'limitby_rel_' . $rkey;
						}
						if($myrelationship['rel-to-name'] == $del_key)
						{
							unset($app['relationship'][$rkey]);
							$rel_arr[] = $rkey;
							$roles_to_remove[] = 'limitby_rel_' . $rkey;
						}
					}
				}
			}
			elseif($type == 'taxonomy')
			{
				unset($app[$type][$del_key]);
				$txn_arr[] = $del_key;
				$roles_to_remove[] = 'tax_' . $del_key;
			}	
			elseif($type == 'relationship')
			{
				unset($app[$type][$del_key]);
				$rel_arr[] = $del_key;
				$roles_to_remove[] = 'limitby_rel_' . $del_key;
			}
			elseif($type == 'widget')
			{
				if($app[$type][$del_key]['widg-type'] == 'dashboard')
				{ 
					$roles_to_remove[] = 'widg_' . $del_key;
				}
				unset($app[$type][$del_key]);
			}
			elseif($type == 'form')
			{
				if($app[$type][$del_key]['form-form_type'] == 'search')
				{
					$form_arr[] = $del_key;
				}
				unset($app[$type][$del_key]);
				$roles_to_remove[] = 'form_' . $del_key;
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
				//view , help
				unset($app[$type][$del_key]);
				if($type == 'shortcode')
				{
					$roles_to_remove[] = 'shc_' . $del_key;
				}
			}
			//delete the helps attached to ent and tax
			if(!empty($app['help']) && (!empty($ent_arr) || !empty($txn_arr)))
			{
				foreach($app['help'] as $hkey => $myhelp)
				{
					if(!empty($myhelp['help-entity']) && in_array($myhelp['help-entity'],$ent_arr) && $myhelp['help-type'] == 'ent')
					{
						unset($app['help'][$hkey]);
					}
					if(!empty($myhelp['help-tax']) && in_array($myhelp['help-tax'],$txn_arr))
					{
						unset($app['help'][$hkey]);
					}
				}
			}
			//delete form dependents and forms
			if(!empty($app['form']) && (!empty($rel_arr) || !empty($ent_arr) || !empty($txn_arr)))
			{
				foreach($app['form'] as $keyform => $myform)
				{
					foreach($rel_arr as $del)
					{
						if(!empty($myform['form-dependents']) && in_array($del,$myform['form-dependents']))
						{
							$dep_key = array_search($del,$myform['form-dependents']);
							unset($app['form'][$keyform]['form-dependents'][$dep_key]);
						}
					}
					if(in_array($myform['form-attached_entity'],$ent_arr))
					{
						unset($app['form'][$keyform]);
						$roles_to_remove[] = 'form_' . $keyform;
						$form_arr[] = $keyform;
					}
					elseif(!empty($myform['form-layout']))
					{
						foreach($myform['form-layout'] as $keylayout => $myform_layout)
						{
							$new_layout = $myform_layout;
							$change = 0;
							foreach($myform_layout as $keyel => $myform_layout_el)
							{
								if(isset($myform_layout_el['entity']) && in_array($myform_layout_el['entity'],$ent_arr) || (isset($myform_layout_el['obtype']) && $myform_layout_el['obtype'] == 'relent' && in_array($myform_layout_el['relent'],$rel_arr)) || (isset($myform_layout_el['obtype']) && $myform_layout_el['obtype'] == 'tax' && in_array($myform_layout_el['tax'],$txn_arr)))
								{
									unset($new_layout[$keyel]);
									$change =1;
								}
							}
							if($change == 1)
							{
								if(count($new_layout) >= 1)
								{
									$app['form'][$keyform]['form-layout'][$keylayout] = array_combine(range(1,count($new_layout)),array_values($new_layout));
								}
								elseif(count($new_layout) == 0)
								{
									unset($app['form'][$keyform]['form-layout'][$keylayout]);
								}
							}
						}
						if(count($app['form'][$keyform]['form-layout']) >= 1)
						{
							$app['form'][$keyform]['form-layout']= array_combine(range(1,count($app['form'][$keyform]['form-layout'])),array_values($app['form'][$keyform]['form-layout']));
						}		
						elseif(count($app['form'][$keyform]['form-layout']) == 0)
						{
							$app['form'][$keyform]['form-layout'] = Array();
						}
					}
				}
			}
			//delete widgets
			if(!empty($app['widget']) && !empty($ent_arr))
			{
				foreach($app['widget'] as $keywidg => $mywidg)
				{
					if((isset($mywidg['widg-attach']) && in_array($mywidg['widg-attach'],$ent_arr)))
					{
						unset($app['widget'][$keywidg]);
						$roles_to_remove[] = 'widg_' . $keywidg;
					}
				}
			}
			//delete views
			if(!empty($app['shortcode']) && (!empty($form_arr) || !empty($txn_arr) || !empty($ent_arr)))
			{
				foreach($app['shortcode'] as $skey => $myshortcode)
				{
					if((!empty($myshortcode['shc-attach']) && in_array($myshortcode['shc-attach'],$ent_arr)) ||
					(!empty($myshortcode['shc-attach_form']) && in_array($myshortcode['shc-attach_form'],$form_arr)) ||
					(!empty($myshortcode['shc-attach_tax']) && in_array($myshortcode['shc-attach_tax'],$txn_arr)))
					{
						unset($app['shortcode'][$skey]);
						$roles_to_remove[] = 'shc_' . $skey;
					}
				}
			}
			//delete the capabilities for each role
			if(!empty($app['role']) && !empty($roles_to_remove))
			{
				foreach($app['role'] as $rkey => $myrole)
				{
					foreach($roles_to_remove as $mycap_remove)
					{
						$pattern = '/' . $mycap_remove . '/';
						foreach($myrole as $role_name => $role_value)
						{
							if(preg_match($pattern,$role_name))
							{
								unset($app['role'][$rkey][$role_name]);
							}
						}
					}
				}
			}
		}
		echo wpas_list($type,$app,$app_id,1);
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
	echo wpas_list($type,$app,$app_id,$page);
	die();
}
function wpas_save_option_form()
{
	wpas_is_allowed();
	check_ajax_referer('wpas_save_option_form_nonce','nonce');
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
	parse_str(stripslashes($_POST['form']),$post_array);

	foreach($post_array as $pkey => $comp_form_value){
		$pos = strpos($pkey,$search_str);
		if(in_array($pkey,Array('ao_change_log','ao_app_desc'))){
			$comp_form_value = implode("\n",array_map('sanitize_text_field',explode("\n",$comp_form_value)));
		}
		elseif(!in_array($pkey, Array('ao_left_footer_html','ao_right_footer_html')))
		{
			$comp_form_value = sanitize_text_field($comp_form_value);
		}
		else
		{
			$comp_form_value = wpautop($comp_form_value);
		}

		if($pos !== false && $comp_form_value != "")
		{
			$comp[$pkey] = $comp_form_value;
		}
	}
	
	if(!isset($comp['ao_blog_name']))
	{
		$comp['ao_blog_name'] = sprintf(__('My %s Site','wpas'),$comp['ao_domain']);
	}
	if(!isset($comp['ao_app_version']))
	{
		$comp['ao_app_version'] = "1.0.0";
	}
	if(!isset($comp['ao_author']))
	{
		$comp['ao_author'] = $comp['ao_domain'] . " " . __("Owner","wpas");
	}
	if(!isset($comp['ao_author_url']))
	{
		$comp['ao_author_url'] = "http://" . $comp['ao_domain'] ;
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
	$comp_type = isset($_POST['type']) ? $_POST['type'] : '';
	$app_id = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	if(empty($app_id) || $comp_type == null)
	{
		die(-1);
	}
	parse_str(stripslashes($_POST['form']),$post_array);
	$app = wpas_get_app($app_id);
	if(empty($app))
	{
		die(-1);
	}
	$search_str = wpas_get_search_string($comp_type);
	$comp = Array();
	foreach($post_array as $pkey => $comp_form_value)
	{
		$pos = strpos($pkey,$search_str);
		if(in_array($pkey, Array('rel-con_from_header','rel-con_from_footer','rel-con_from_layout','rel-con_to_header','rel-con_to_footer','rel-con_to_layout','rel-rel_from_header','rel-rel_from_footer','rel-rel_from_layout','rel-rel_to_header','rel-rel_to_footer','rel-rel_to_layout','shc-sc_layout','shc-layout_header','shc-layout_footer','widg-layout','help-screen_sidebar','help_fld_content','form-form_desc','form-not_loggedin_msg','notify-confirm_msg','notify-confirm_admin_msg','form-result_msg','form-result_footer_msg','form-confirm_success_txt','form-confirm_error_txt')))
		{
			$comp_form_value_sanitized = wp_kses_post(stripslashes($comp_form_value));
		}
		elseif(!is_array($comp_form_value))
		{
			$comp_form_value_sanitized = sanitize_text_field($comp_form_value);
		}
		else
		{
			$comp_form_value_sanitized = $comp_form_value;
		}
		$req_fields = Array('ent-name','ent-label','ent-singular-label','txn-name','txn-label','txn-singular-label','rel-name','shc-label','widg-name','widg-title','role-name','role-label','form-name','notify-name');
		if(empty($comp_form_value_sanitized) && !empty($comp_form_value) && in_array($pkey,$req_fields))
		{
			die(-1);
		}
		if($pos !== false && $comp_form_value_sanitized != '')
		{
			if(in_array($pkey, Array('ent-name','txn-name','rel-name','widg-name','role-name','shc-label','form-name','notify-name')))
			{
				$comp[$pkey] = strtolower($comp_form_value_sanitized);
			}
			elseif(in_array($pkey,Array('ent-label','ent-singular-label')))
			{
				$comp[$pkey] = ucwords(strtolower($comp_form_value_sanitized));
			}
			else
			{
				$comp[$pkey] = $comp_form_value_sanitized;
			}
		}
	}
	if($comp_type == 'help' && empty($comp['help-screen_type']))
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
	if($comp_type == 'entity')
	{
		if($comp['ent-capability_type'] != 'post')
		{
			$admin_new_entity_arr = wpas_admin_entity('ent_' .$comp_id, $comp);
			$app['role'][0] = array_merge($admin_new_entity_arr,$app['role'][0]);
		}
		if(isset($comp['ent-supports_title']) && $comp['ent-supports_title'] == 1)
		{
			$comp['field'][] = Array('fld_name' => 'blt_title','fld_label'=>'Title','fld_type'=>'text','fld_builtin' => 1,'fld_required' => 1);
		}
		if(isset($comp['ent-supports_editor']) && $comp['ent-supports_editor'] == 1)
		{
			$comp['field'][] = Array('fld_name' => 'blt_content','fld_label'=>'Content','fld_type'=>'wysiwyg','fld_builtin' => 1);
		}
		if(isset($comp['ent-supports_excerpt']) && $comp['ent-supports_excerpt'] == 1)
		{
			$comp['field'][] = Array('fld_name' => 'blt_excerpt','fld_label'=>'Excerpt','fld_type'=>'textarea','fld_builtin' => 1);
		}
	}
	if($comp_type == 'taxonomy')
	{
		$admin_new_tax_arr = wpas_admin_taxonomy('tax_' . $comp_id);
		$app['role'][0] = array_merge($admin_new_tax_arr,$app['role'][0]);
	}
	if($comp_type == 'widget' && $comp['widg-type'] == 'dashboard')
	{
		$admin_new_widg_arr = wpas_admin_widget('widg_' . $comp_id);
		$app['role'][0] = array_merge($admin_new_widg_arr,$app['role'][0]);
	}
	$app[$comp_type][$comp_id] = $comp;
	echo wpas_list($comp_type,$app,$app_id,1);
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
	parse_str(stripslashes($_POST['form']),$post_array);
	$app = wpas_get_app($app_id);
	if(empty($app))
	{
		die(-1);
	}
	$search_str = wpas_get_search_string($type);
	$comp_type = $type;
	$comp = Array();
	$types = Array('ent','txn','rel','help','shc','widget','role','form','notify');
	foreach($post_array as $pkey => $comp_form_value)
	{
		if(in_array($pkey,$types))
		{
			$comp_id = $comp_form_value;
		}
		$pos = strpos($pkey,$search_str);

		if(in_array($pkey, Array('rel-con_from_header','rel-con_from_footer','rel-con_from_layout','rel-con_to_header','rel-con_to_footer','rel-con_to_layout','rel-rel_from_header','rel-rel_from_footer','rel-rel_from_layout','rel-rel_to_header','rel-rel_to_footer','rel-rel_to_layout','shc-sc_layout','shc-layout_header','shc-layout_footer','widg-layout','help-screen_sidebar','help_fld_content','form-form_desc','form-not_loggedin_msg','notify-confirm_msg','notify-confirm_admin_msg','form-result_msg','form-result_footer_msg','form-confirm_success_txt','form-confirm_error_txt')))
		{
			$comp_form_value_sanitized = wp_kses_post(stripslashes($comp_form_value));
		}
		elseif(!is_array($comp_form_value))
		{
			$comp_form_value_sanitized = sanitize_text_field($comp_form_value);
		}
		else
		{
			$comp_form_value_sanitized = $comp_form_value;
		}
		$req_fields = Array('ent-name','ent-label','ent-singular-label','txn-name','txn-label','txn-singular-label','shc-label','widg-name','widg-title','role-name','role-label','form-name');
		if(empty($comp_form_value_sanitized) && !empty($comp_form_value) && in_array($pkey,$req_fields))
		{
			die(-1);
		}
		if($pos !== false && $comp_form_value_sanitized != "")
		{
			if(in_array($pkey, Array('ent-name','txn-name','role-name','shc-label','form-name','widg-name')))
			{
				$comp[$pkey] = strtolower($comp_form_value_sanitized);
			}
			elseif(in_array($pkey, Array('ent-label','ent-singular-label')))
			{
				$comp[$pkey] = ucwords(strtolower($comp_form_value_sanitized));
			}
			else
			{
				if(($comp_type == 'role' && !empty($comp_form_value_sanitized)) || $comp_type != 'role')
				{
					$comp[$pkey] = $comp_form_value_sanitized;
				}
			}
		}
	}
	$comp['date'] = $app[$comp_type][$comp_id]['date'];
	if(empty($comp['date']))
	{
		$comp['date'] = date("Y-m-d H:i:s");
	}
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
		$set_builtin = Array();
		$unset_builtin = Array();
		$dont_set = Array();
		if(isset($comp['ent-supports_title']) && $comp['ent-supports_title'] == 1)
                {
			$set_builtin['blt_title'] = Array('fld_name' => 'blt_title','fld_label'=>'Title','fld_type'=>'text', 'fld_builtin'=>1, 'fld_required' => 1);
                }
		else
		{
			$unset_builtin[] = 'blt_title';
		}
		if(isset($comp['ent-supports_editor']) && $comp['ent-supports_editor'] == 1)
		{
			$set_builtin['blt_content'] = Array('fld_name' => 'blt_content','fld_label'=>'Content','fld_type'=>'wysiwyg','fld_builtin' => 1);
		}
		else
		{
			$unset_builtin[] = 'blt_content';
		}
		if(isset($comp['ent-supports_excerpt']) && $comp['ent-supports_excerpt'] == 1)
		{
			$set_builtin['blt_excerpt'] = Array('fld_name' => 'blt_excerpt','fld_label'=>'Excerpt','fld_type'=>'textarea','fld_builtin' => 1);
		}
		else
		{
			$unset_builtin[] = 'blt_excerpt';
		}
		if(!empty($comp['field']))
		{
			foreach($comp['field'] as $keyfield => $myfield)
			{
				if(in_array($myfield['fld_name'],array_keys($set_builtin)))
				{
					$dont_set[] = $myfield['fld_name'];
				}
				elseif(in_array($myfield['fld_name'],$unset_builtin))
				{
					unset($comp['field'][$keyfield]);
				}
			}
		}
		foreach($set_builtin as $setkey => $setval)
		{
			if(empty($dont_set) || (!empty($dont_set) && !in_array($setkey,$dont_set)))
			{
				$comp['field'][] = $set_builtin[$setkey];
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

	if($comp_type == 'entity')
	{
		if($comp['ent-capability_type'] != 'post')
		{
			$admin_new_entity_arr = wpas_admin_entity('ent_' . $comp_id,$comp);
			$app['role'][0] = array_merge($admin_new_entity_arr,$app['role'][0]);
		}
		elseif($comp['ent-capability_type'] == 'post' && $app[$comp_type][$comp_id]['ent-capability_type'] != 'post')
		{
			$new_entity_arr = wpas_admin_entity('ent_' . $comp_id,$comp);
			foreach($app['role'] as $keyrole => $myrole)
			{
				$app['role'][$keyrole] = array_diff_key($app['role'][$keyrole],$new_entity_arr);
			}
		}
	}
	elseif($comp_type == 'taxonomy')
	{
		$old_tax = $app[$comp_type][$comp_id];
		if(!empty($old_tax) && $comp['txn-name'] != $old_tax['txn-name'])
                {
                        $app = wpas_update_all_layout('tax',$old_tax['txn-name'],$comp['txn-name'],$app,$app_id);
                        $app = wpas_update_all_layout('taxnl',$old_tax['txn-name'],$comp['txn-name'],$app,$app_id);
                }
	}
	$app[$comp_type][$comp_id] = $comp;
	wpas_update_app($app,$app_id);
	echo wpas_list($type,$app,$app_id,1);
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
		case 'notify':
			$search_str = 'notify-';
			break;
		default:
			$search_str = "ent-";
			break;
	}
	return $search_str;
}
function wpas_update_all_layout($ftype,$fold,$fnew,$app,$app_id)
{
	if($ftype == 'attr')
	{
		$check = "!#ent_" . $fold . "#";
		$new = "!#ent_" . $fnew . "#";
	}
	elseif($ftype == 'rel')
	{
		$check = "!#rel_" . $fold . "#";
		$new = "!#rel_" . $fnew . "#";
	}
	elseif($ftype == 'tax')
	{
		$check = "!#tax_" . $fold . "#";
		$new = "!#tax_" . $fnew . "#";
	}
	elseif($ftype == 'taxnl')
	{
		$check = "!#tax_" . $fold . "_NL#";
		$new = "!#tax_" . $fnew . "_NL#";
	}
	if(in_array($ftype,Array('attr','tax','taxnl')))
	{
		if(!empty($app['shortcode']))
		{
			foreach($app['shortcode'] as $kshc => $myshc)
			{
				if(!empty($myshc['shc-sc_layout']) && preg_match('/'.$check.'/',$myshc['shc-sc_layout']))
				{
					$app['shortcode'][$kshc]['shc-sc_layout'] = str_replace($check,$new,$myshc['shc-sc_layout']);
				}
			}
		}
		if(!empty($app['form']))
		{	
			foreach($app['form'] as $kform => $myform)
			{
				if($myform['form-form_type'] == 'submit' && !empty($myform['form-confirm_msg']) && preg_match('/'.$check.'/',$myform['form-confirm_msg']))
				{
					$app['form'][$kform]['form-confirm_msg'] = str_replace($check,$new,$myform['form-confirm_msg']);
				}
				if($myform['form-form_type'] == 'submit' && !empty($myform['form-confirm_admin_msg']) && preg_match('/'.$check.'/',$myform['form-confirm_admin_msg']))
				{
					$app['form'][$kform]['form-confirm_admin_msg'] = str_replace($check,$new,$myform['form-confirm_admin_msg']);
				}
				if($myform['form-form_type'] == 'submit' && !empty($myform['form-confirm_admin_subject']) && preg_match('/'.$check.'/',$myform['form-confirm_admin_subject']))
				{
					$app['form'][$kform]['form-confirm_admin_subject'] = str_replace($check,$new,$myform['form-confirm_admin_subject']);
				}
				if($myform['form-form_type'] == 'submit' && !empty($myform['form-confirm_subject']) && preg_match('/'.$check.'/',$myform['form-confirm_subject']))
				{
					$app['form'][$kform]['form-confirm_subject'] = str_replace($check,$new,$myform['form-confirm_subject']);
				}
			}
		}
	}
	if(in_array($ftype,Array('attr','rel','tax','taxnl')))
	{
		if(!empty($app['widget']))
		{
			foreach($app['widget'] as $kwidg => $mywidg)
			{
				if(!empty($mywidg['widg-layout']) && preg_match('/'.$check.'/',$mywidg['widg-layout']))
				{
					$app['widget'][$kwidg]['widg-layout'] = str_replace($check,$new,$mywidg['widg-layout']);
				}
			}
		}
	}
	return $app;
}		
