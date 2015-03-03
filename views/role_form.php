<?php
function wpas_is_def_role($myrole)
{
	$def_roles = Array('administrator','subscriber','editor','contributor','author');
	if(in_array($myrole['role-name'],$def_roles))
	{
		return true;
	}
	return false;
}
function wpas_admin_entity($label,$entity)
{
	$admin_role = Array('role-edit_' . $label => 1,
			'role-delete_' . $label => 1,
			'role-edit_others_' . $label => 1,
			'role-publish_' . $label => 1,
			'role-read_private_' . $label => 1,
			'role-delete_private_' . $label => 1,
			'role-delete_published_' . $label => 1,
			'role-delete_others_' . $label => 1,
			'role-edit_private_' . $label => 1,
			'role-edit_published_' . $label => 1,
			'role-manage_operations_' .$label => 1);
	if(!empty($entity['ent-com_name']) && $entity['ent-com_type'] == 'custom')
	{
		$admin_role['role-manage_' . $entity['ent-com_name'] . "_" . $label] = 1;
	}
	return $admin_role;				
}
function wpas_admin_taxonomy($label)
{
	$admin_role = Array('role-manage_' . $label => 1,
			'role-edit_' . $label => 1,
			'role-delete_' . $label => 1,
			'role-assign_' .$label => 1);
	return $admin_role;				
}
function wpas_admin_widget($label)
{
	$admin_role = Array('role-view_' . $label => 1,
			'role-configure_' .$label => 1);
	return $admin_role;				
}
function wpas_default_capabilities($myrole)
{
$html = "";
if(empty($myrole))
{
	$myrole['role-read'] = 1;
}
$def_caps = array(
                'activate_plugins',
                'add_users',
                'create_users',
                'delete_plugins',
                'delete_users',
                'edit_files',
                'edit_plugins',
                'edit_theme_options',
                'edit_themes',
                'edit_users',
                'import',
                'install_plugins',
                'install_themes',
                'list_users',
		'manage_categories',
                'manage_links',
                'manage_options',
                'moderate_comments',
                'promote_users',
                'read',
                'remove_users',
                'switch_themes',
                'unfiltered_html',
                'unfiltered_upload',
                'update_core',
                'update_plugins',
                'update_themes',
                'upload_files',
		'edit_dashboard',
		'view_app_dashboard'
		);


$count = 1;
$html .= '<label class="checkbox inline"><b>' . __("Check All","wpas") . '</b>
	<input id="def-all" class="checkall" type="checkbox" value="1" name="def-all" /></label><div id="def" class="well-white">';

foreach($def_caps as $mycap)
{
	if($count == 1)
	{
		$html .= '<div class="control-group">';
	}
	$html .= '<label class="checkbox inline span3">' . $mycap;
	$html .= '<input name="role-' . $mycap . '" id="role-' . $mycap . '" type="checkbox" value="1"';
	if(isset($myrole['role-'.$mycap]) && $myrole['role-'.$mycap] == 1)
	{
		$html .= ' checked';
	}	
	$html .= '/></label>';
	
	$count ++;
	if($count  == 5)
	{
		$html .= "</div>";
		$count = 1;
	}
}
$html .= "</div></div>";
return $html;
}
function wpas_entity_capabilities($app_id,$entities,$myrole)
{
	$html ="";
	$entcount = 0;
	$ent_caps = Array();
	$user_rels= Array();
	$app = wpas_get_app($app_id);
	if(!empty($app['relationship']))
	{
		foreach($app['relationship'] as $keyrel => $myrel)
		{
			if($myrel['rel-from-name'] == 'user')
			{
				$user_rels[$myrel['rel-to-name']][$keyrel] = $myrel['rel-name'];
			}
			elseif($myrel['rel-to-name'] == 'user')
			{
				$user_rels[$myrel['rel-from-name']][$keyrel] = $myrel['rel-name'];
			}
			else
			{
				foreach($entities[$myrel['rel-from-name']]['field'] as $mfield)
				{
					if($mfield['fld_type'] == 'user')
					{
						$user_rels[$myrel['rel-to-name']][$keyrel] = $myrel['rel-name'];
						break;
					}
				}
				foreach($entities[$myrel['rel-to-name']]['field'] as $mfield)
				{
					if($mfield['fld_type'] == 'user')
					{
						$user_rels[$myrel['rel-from-name']][$keyrel] = $myrel['rel-name'];
						break;
					}
				}
			}
		}
	}
	foreach($entities as $keyent => $myentity)
	{
		$show_cap = 0;
		$label = 'ent_' . $keyent;
		if(isset($myentity['ent-capability_type']) && $myentity['ent-capability_type'] != 'post')
		{
			if(!in_array($myentity['ent-name'],Array('posts','pages')))
			{	
				$label = 'ent_' . $keyent;
				$show_cap = 1;
			}
		}
		if($myentity['ent-name'] == 'page' || $myentity['ent-name'] == 'post')
		{
			$label = $myentity['ent-name'] ."s";
			$show_cap = 1;
		}
		if($show_cap == 1)
		{
			$ent_caps[$entcount]['edit'] = "edit_" . $label;
			$ent_caps[$entcount]['delete'] = "delete_" . $label;
			$ent_caps[$entcount]['edit_others'] = "edit_others_" . $label;
			$ent_caps[$entcount]['publish'] = "publish_" . $label;
			$ent_caps[$entcount]['read_private'] = "read_private_" . $label;
			$ent_caps[$entcount]['delete_private'] = "delete_private_" . $label;
			$ent_caps[$entcount]['delete_published'] = "delete_published_" . $label;
			$ent_caps[$entcount]['delete_others'] = "delete_others_" . $label;
			$ent_caps[$entcount]['edit_private'] = "edit_private_" . $label;
			$ent_caps[$entcount]['edit_published'] = "edit_published_" . $label;
			$ent_caps[$entcount]['manage_operations'] = "manage_operations_" . $label;
			if(!empty($myentity['ent-com_name']) && $myentity['ent-com_type'] == 'custom')
			{
				$ent_caps[$entcount]['manage_' . $myentity['ent-com_name']] = "manage_" . $myentity['ent-com_name'] . "_" . $label;
			}
			$ent_caps[$entcount]['name'] = $myentity['ent-name'];
			$ent_caps[$entcount]['limitby_author'] = "limitby_author_" . $label;
			if(!empty($user_rels[$keyent]))
			{
				foreach($user_rels[$keyent] as $keyrel => $myuser_rel)
				{
					$ent_caps[$entcount]['limitby_' . $myuser_rel] = "limitby_rel_" . $keyrel;
				}
			}
			$entcount++;
		}
	}
	$html = wpas_display_caps($ent_caps,'edit','entity',5, $myrole);
	return $html;
}
function wpas_tax_capabilities($app_id,$taxonomies,$myrole)
{
	$html ="";
	$taxcount = 0;
	foreach($taxonomies as $keytax => $mytax)
	{
		if(empty($mytax['txn-inline'])){
			$label = "tax_" . $keytax;
			$tax_caps[$taxcount]['manage'] = "manage_" . $label;
			$tax_caps[$taxcount]['edit'] = "edit_" . $label;
			$tax_caps[$taxcount]['delete'] = "delete_" . $label;
			$tax_caps[$taxcount]['assign'] = "assign_" . $label;
			$tax_caps[$taxcount]['name'] = $mytax['txn-name'];
			$taxcount++;
		}
	}
	if(empty($tax_caps))
	{
		$html = __("No taxonomies defined yet.","wpas");
	}
	else
	{
		$html = wpas_display_caps($tax_caps,'manage','taxonomy',5, $myrole);
	}
	return $html;
}
function wpas_widg_capabilities($app_id,$widgets,$myrole)
{
	$html ="";
	$widgcount = 0;
	foreach($widgets as $keywidg => $mywidg)
	{
		$label = 'widg_' . $keywidg;
		$widg_caps[$widgcount]['view'] = "view_" . $label;
		if($mywidg['widg-type'] == 'dashboard' && $mywidg['widg-wp_dash'] == 1)
		{
			$widg_caps[$widgcount]['configure'] = "configure_" . $label;
		}
		$widg_caps[$widgcount]['name'] = $mywidg['widg-name'];
		$widgcount++;
	}

	if(empty($widg_caps))
	{
		$html = __("No widgets defined yet.","wpas");
	}
	else
	{
		$html = wpas_display_caps_noacc($widg_caps,'widget',$myrole);
	}
	return $html;
}
function wpas_form_capabilities($app_id,$forms,$myrole)
{
	$html ="";
	$formcount = 0;
	foreach($forms as $keyform => $myform)
	{
		$form_caps[$formcount]['view'] = "view_form_" . $keyform;
		$form_caps[$formcount]['name'] = $myform['form-name'];
		$formcount++;
	}
	if(empty($form_caps))
	{
		$html = __("No forms defined yet.","wpas");
	}
	else
	{
		$html = wpas_display_caps_noacc($form_caps,'form',$myrole);
	}
	return $html;
}
function wpas_view_capabilities($app_id,$views,$myrole)
{
	$html ="";
	$viewcount = 0;
	foreach($views as $keyview => $myview)
	{
		$view_caps[$viewcount]['view'] = "view_shc_" . $keyview;
		$view_caps[$viewcount]['name'] = $myview['shc-label'];
		$viewcount++;
	}
	if(empty($view_caps))
	{
		$html = __("No views defined yet.","wpas");
	}
	else
	{
		$html = wpas_display_caps_noacc($view_caps,'view',$myrole);
	}
	return $html;
}
function wpas_display_caps_noacc($type_caps,$type_key,$myrole)
{
	$html = "";
	$count = 1;
	$html .= '<label class="checkbox inline"><b>' . __("Check All","wpas") . '</b><input name="' . esc_attr($type_key) . '-all" id="' . esc_attr($type_key) . '-all" class="checkall" type="checkbox" value="1" /></label><div id="' . $type_key . '" class="well-white">';
	foreach($type_caps as $mytype_cap)
	{
		foreach($mytype_cap as $key => $mycap)
		{
			if($key != 'name' && $count == 1)
			{
				$html .= '<div class="control-group">';
			}
			if($key != 'name')
			{
				$html .= '<label class="checkbox inline span4">' . $key . " " . $mytype_cap['name'];
				$html .= '<input name="role-' . $mycap . '" id="role-' . esc_attr($mycap) . '" type="checkbox" value="1"';
				if(isset($myrole['role-'.$mycap]) && $myrole['role-'.$mycap] != 0)
				{
					$html .= ' checked';
				}	
				$html .= '/> </label>';
				$count ++;
			}
			if($key != 'name' && $count == 4)
			{
				$html .= "</div>";
				$count = 1;
			}
				
		}
	}
	if($count < 4 && $count != 1)
	{
		$html .= "</div>";
	}
		
	$html .= "</div>";
	return $html;
}
function wpas_display_caps($type_caps,$type_key,$type,$pnum,$myrole)
{
	$navcount = 0;
	$html = "";
	$count = 1;
	foreach($type_caps as $mytype_cap)
	{
		foreach($mytype_cap as $key => $mycap)
		{
			$nav_in = "";
			if($key == $type_key)
			{
				if($navcount == 0)
				{
					$nav_in = "in";
					$navcount ++;
				}
				
				$html.= '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" href="#collapse_' . esc_attr($mycap) . '" data-toggle="collapse" data-parent="#tab-' . $type . '">' . ucfirst($type) . ': ' . esc_html($mytype_cap['name']) . '</a></div><div id="collapse_'. esc_attr($mycap) . '" class="accordion-body ' . $nav_in . ' collapse"><div class="accordion-inner">';
				if(in_array($type, Array('widget','taxonomy','entity')))
				{
					$html .= '<label class="checkbox inline"><b>' . __("Check All","wpas") . '</b><input name="' . esc_attr($mytype_cap['name']) . '-all" id="' . esc_attr($mytype_cap['name']) . '-all" class="checkall" type="checkbox" value="1" /></label>';
				}
			}
			if($count == 1 && $pnum > 2)
			{
				$html .= '<div class="row-fluid" id="' . esc_attr($mytype_cap['name']) . '">';
			}
			if($key != 'name')
			{
				$html .= '<label class="checkbox inline span3">' . $key;
				$html .= '<input name="role-' . $mycap . '" id="role-' . esc_attr($mycap) . '" type="checkbox" value="1"';
				if(isset($myrole['role-'.$mycap]) && $myrole['role-'.$mycap] != 0)
				{
					$html .= ' checked';
				}	
				$html .= '/> </label>';
			}
			$count ++;
			if($count  == $pnum && $pnum >2)
			{
				$html .= "</div>";
				$count = 1;
			}
		}
		if($count < $pnum && $count != 1)
		{
			$html .= "</div>";
		}
		$html .= "</div></div></div>";
		$count = 1;
	}
	return $html;
}
function wpas_add_role_form($app_id,$role_id)
{
	$app = wpas_get_app($app_id);
        $entities = $app['entity'];
	$taxonomies = Array();
	$widgets = Array();
	$forms = Array();
	$views = Array();
	
	if(isset($app['taxonomy']))
	{
		$taxonomies = $app['taxonomy'];
	}
	if(isset($app['widget']))
	{
		$widgets = $app['widget'];
	}
	if(isset($app['form']))
	{
		$forms = $app['form'];
	}
	if(isset($app['shortcode']))
	{
		$views = $app['shortcode'];
	}
	$myrole = Array();

	$def_roles = Array('administrator','subscriber','editor','contributor','author');
	$disable = "";

	if($role_id != '')
	{
		$myrole = $app['role'][$role_id];
		$role_name = $myrole['role-name'];
		$role_label = $myrole['role-label'];
		if(in_array($role_name,$def_roles))
		{
			$disable = "disabled";
		}
	}
	

	?>
		<div class="modal hide" id="errorRoleModal">
  <div class="modal-header">
        <button id="error-close" type="button" class="close" data-dismiss="errorRoleModal" aria-hidden="true">x</button>
    <h3><i class="icon-flag icon-red"></i><?php _e("Error","wpas"); ?></h3>
  </div>
  <div class="modal-body" style="clear:both"><?php _e("There must be at least one capability enabled.","wpas");?>
  </div>
  <div class="modal-footer">
<button id="error-ok" data-dismiss="errorRoleModal" aria-hidden="true" class="btn btn-primary"><?php _e("OK","wpas"); ?></button>
  </div>
</div>
		<form action="" method="post" id="role-form" name="role-form" class="form-horizontal">
		<input type="hidden" id="app" name="app" value="<?php echo esc_attr($app_id); ?>" />
		<input type="hidden" value="<?php echo esc_attr($role_id); ?>" name="role" id="role" />  
		<fieldset>
		<div class="well">
	<div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("A role is a collection of capabilities which enable or disable access to your application's data.","wpas");?>"><?php _e("HELP","wpas"); ?></a></div></div>

		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Name","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="role-name" id="role-name" type="text" placeholder="<?php _e("e.g. product_owner","wpas");?>"
		<?php 
		 if(isset($myrole['role-name']))
		 {
			 echo ' value="' . esc_attr($myrole['role-name']) . '" ' . $disable;
		 }
		?>
	 /><a href="#" title="<?php _e("Sets a unique name containing only alphanumeric characters and underscores.","wpas");?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Label","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="role-label" id="role-label" type="text" placeholder="<?php _e("e.g. Product Owner","wpas"); ?>"
		<?php 
		 if(isset($myrole['role-label']))
		 {
			 echo ' value="' . esc_attr($myrole['role-label']) . '" ' . $disable ;
		 }
		?>
		/><a href="#" title="<?php _e("Sets a role label to represent your role.","wpas");?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="tabbable">
		<ul class="nav nav-tabs">
		<li>
		<a data-toggle="tab" href="#tab-def"><?php _e("Default Capabilities","wpas");?></a>
		</li>
		<li class="active">
		<a data-toggle="tab" href="#tab-ent"><?php _e("Entities","wpas");?></a>
		</li>
		<li>
		<a data-toggle="tab" href="#tab-tax"><?php _e("Taxonomies","wpas");?></a>
		</li>
		<li>
		<a data-toggle="tab" href="#tab-widg"><?php _e("Widgets","wpas");?></a>
		</li>
		<li>
		<a data-toggle="tab" href="#tab-form"><?php _e("Forms","wpas");?></a>
		</li>
		<li>
		<a data-toggle="tab" href="#tab-view"><?php _e("Views","wpas");?></a>
		</li>
		</ul>	
		<div id="role-tabs" class="tab-content">
		<div id="tab-def" class="tab-pane">
		<?php 
		echo wpas_default_capabilities($myrole); ?>	
		</div>
		<div id="tab-ent" class="tab-pane active">
		<?php 
		//<div class="control-group span12" id="role-cap-list">
		//</div>
		echo wpas_entity_capabilities($app_id,$entities,$myrole); ?>	
		</div>
		<div id="tab-tax" class="tab-pane">
		<?php echo wpas_tax_capabilities($app_id,$taxonomies,$myrole); ?>
		</div>
		<div id="tab-widg" class="tab-pane">
		<?php echo wpas_widg_capabilities($app_id,$widgets,$myrole); ?>
		</div>
		<div id="tab-form" class="tab-pane">
		<?php echo wpas_form_capabilities($app_id,$forms,$myrole); ?>
		</div>
		<div id="tab-view" class="tab-pane">
		<?php echo wpas_view_capabilities($app_id,$views,$myrole); ?>
		</div>
		</div> <!-- end of role-tabs -->
		</div> <!-- end of tabbable -->
		</div> <!-- end of well -->
		<div class="control-group">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
		<i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-role" type="submit" value="Save">
		<i class="icon-save"></i><?php _e("Save","wpas");?></button>
		</div>
		</fieldset>
		</form>

<?php
}
?>
