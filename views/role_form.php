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
function wpas_admin_entity($label)
{
	$suff = '';
	if($label != 'posts' || $label != 'pages')
	{
		$suff = 'emd_';
	}
	$label = $suff . $label;
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
                'upload_files'
		);


$count = 1;
$html .= '<label class="checkbox inline span12"><b>' . __("Check All","wpas") . '</b>
	<input id="def-all" class="checkall" type="checkbox" value="1" name="def-all" /></label><div id="def">';

foreach($def_caps as $mycap)
{
	if($count == 1)
	{
		$html .= '<div class="control-group">';
	}
	$html .= '<label class="checkbox inline span3">' . $mycap;
	$html .= '<input name="role-' . $mycap . '" id="role-' . $mycap . '" type="checkbox" value="1"';
	if(isset($myrole['role-'.$mycap]))
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
if($count <= 5)
{
	$count = 1;
}

$html .= "</div>";

return $html;
}
function wpas_entity_capabilities($app_id,$entities,$myrole)
{
	$html ="";
	$entcount = 0;
	$ent_caps = Array();
	foreach($entities as $myentity)
	{
		$heading_label = $myentity['ent-label'];
		if(isset($myentity['ent-capability_type']) && $myentity['ent-capability_type'] != 'post')
		{
			$label = $myentity['ent-name'];
			$label = strtolower($label);
			$label = $label . "s";
		
			if(!in_array($label,Array('posts','pages')))
			{	
				$label = 'emd_' . $label;
			}
			$ent_caps[$entcount]['heading'] = $heading_label;
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
			$entcount++;
		}
		elseif(in_array($myentity['ent-name'],Array('post','page')))
		{
			$label = $myentity['ent-name'];
                        $label = strtolower($label);
                        $label = $label . "s";
			$ent_caps[$entcount]['heading'] = $heading_label;
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
	foreach($taxonomies as $mytax)
	{
		$label = $mytax['txn-label'];
		$label = str_replace(" ","_",$label);
		$label = strtolower($label);
		$tax_caps[$taxcount]['manage'] = "manage_" . $label;
		$tax_caps[$taxcount]['edit'] = "edit_" . $label;
		$tax_caps[$taxcount]['delete'] = "delete_" . $label;
		$tax_caps[$taxcount]['assign'] = "assign_" . $label;
		$taxcount++;
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
	foreach($widgets as $mywidg)
	{
		if($mywidg['widg-type'] == 'dashboard')
		{
			$label = $mywidg['widg-title'];
			$label = str_replace(" ","_",$label);
			$label = strtolower($label);
			$label = $mywidg['widg-dash_subtype'] . "_" . $label;
			$widg_caps[$widgcount]['view'] = "view_" . $label;
			$widg_caps[$widgcount]['configure'] = "configure_" . $label;
			$widgcount++;
		}
	}

	if(empty($widg_caps))
	{
		$html = __("Only dashboard widgets can have permissions set.","wpas");
	}
	else
	{
		$html = wpas_display_caps($widg_caps,'view','widget',3,$myrole);
	}
	return $html;
}
function wpas_form_capabilities($app_id,$forms,$myrole)
{
	$html ="";
	$formcount = 0;
	foreach($forms as $myform)
	{
		$label = $myform['form-name'];
		$label = str_replace(" ","_",$label);
		$label = strtolower($label);
		$form_caps[$formcount]['view'] = "view_" . $label;
		$formcount++;
	}
	if(empty($form_caps))
	{
		$html = __("No forms defined yet.","wpas");
	}
	else
	{
		$html = wpas_display_caps($form_caps,'view','form',5,$myrole);
	}
	return $html;
}
function wpas_view_capabilities($app_id,$views,$myrole)
{
	$html ="";
	$viewcount = 0;
	foreach($views as $myview)
	{
		$label = $myview['shc-label'];
		$label = str_replace(" ","_",$label);
		$label = strtolower($label);
		$view_caps[$viewcount]['view'] = "view_" . $label;
		$viewcount++;
	}
	if(empty($view_caps))
	{
		$html = __("No views defined yet.","wpas");
	}
	else
	{
		$html = wpas_display_caps($view_caps,'view','view',5,$myrole);
	}
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
			if($type == 'entity' && $key == 'heading')
			{
				$heading_label = $mycap;
			}
			else
			{
				$nav_in = "";
				if($key == $type_key)
				{
					if($navcount == 0)
					{
						$nav_in = "in";
						$navcount ++;
					}
					if($type == 'entity')
					{
						$type_name = str_replace($type_key . '_emd_','',$mycap);
					}
					$type_name = str_replace($type_key .'_','',$mycap);
					$div_name = $type_name;
					if($type == 'widget')
					{
						$type_name = preg_replace('/^entity_/',' ' . __("Entity","wpas") . ' : ',$type_name);
						$type_name = preg_replace('/^admin_/',' ' . __("Admin","wpas") . ' : ',$type_name);
					}	
					$type_name = str_replace('_',' ',$type_name);
					$type_name = ucwords($type_name);
					if($type != 'entity')
					{
						$heading_label = $type_name;
					}
					
					$html.= '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" href="#collapse_' . esc_attr($mycap) . '" data-toggle="collapse" data-parent="#tab-' . $type . '">' . ucfirst($type) . ': ' . esc_html($heading_label) . '</a></div><div id="collapse_'. esc_attr($mycap) . '" class="accordion-body ' . $nav_in . ' collapse"><div class="accordion-inner">';
					if(in_array($type, Array('widget','taxonomy','entity')))
					{
						$html .= '<label class="checkbox inline span12"><b>' . __("Check All","wpas") . '</b><input name="' . esc_attr($div_name) . '-all" id="' . esc_attr($div_name) . '-all" class="checkall" type="checkbox" value="1" /></label>';
					}
				}
				if($count == 1)
				{
					$html .= '<div class="control-group" id="' . esc_attr($div_name) . '">';
				}
				$html .= '<label class="checkbox inline span3">' . $key;
				$html .= '<input name="role-' . $mycap . '" id="role-' . esc_attr($mycap) . '" type="checkbox" value="1"';
				if(isset($myrole['role-'.$mycap]) && $myrole['role-'.$mycap] != 0)
				{
					$html .= ' checked';
				}	
				$html .= '/> </label>';
				$count ++;
				if($count  == $pnum)
				{
					$html .= "</div>";
					$count = 1;
				}
			}
		}
		if($count <= $pnum)
		{
			$html .= "</div></div></div>";
			if(!in_array($type, Array('widget','taxonomy')))
			{
				$html .= "</div>";
			}
			$count = 1;
		}
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
		<label class="control-label span3"><?php _e("Name","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="role-name" id="role-name" type="text" placeholder="<?php _e("e.g. product_owner","wpas");?>"
		<?php 
		 if(isset($myrole['role-name']))
		 {
			 echo ' value="' . esc_attr($myrole['role-name']) . '" ' . $disable;
		 }
		?>
		></input><a href="#" title="<?php _e("Sets a unique name containing only alphanumeric characters and underscores.","wpas");?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Labeli","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="role-label" id="role-label" type="text" placeholder="<?php _e("e.g. Product Owneri","wpas"); ?>"
		<?php 
		 if(isset($myrole['role-label']))
		 {
			 echo ' value="' . esc_attr($myrole['role-label']) . '" ' . $disable ;
		 }
		?>
		></input><a href="#" title="<?php _e("Sets a role label to represent your role.i","wpas");?>" style="cursor: help;">
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
		</div>
		</div> 
		</div> 
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
