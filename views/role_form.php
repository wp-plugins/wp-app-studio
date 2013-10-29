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
$html .= '<label class="checkbox inline span12"><b>Check All</b>
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
	$count = 1;
	$navcount = 0;

	foreach($ent_caps as $myent_cap)
	{
		foreach($myent_cap as $key => $mycap)
		{
			if($key == 'heading')
			{
				$heading_label = $mycap;
			}
			else
			{
			$nav_in = "";
			if($key == 'edit')
			{
				if($navcount == 0)
				{
					$nav_in = "in";
					$navcount ++;
				}

				$ent_name = str_replace('edit_emd_','',$mycap);
				$ent_name = str_replace('edit_','',$mycap);
				$div_name = $ent_name;
				$ent_name = str_replace('_',' ',$ent_name);
				$ent_name = ucwords($ent_name);
		

				$html.= '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" href="#collapse_' . esc_attr($mycap) . '" data-toggle="collapse" data-parent="#tab-ent">Entity : ' . esc_html($heading_label) . '</a></div><div id="collapse_'. esc_attr($mycap) . '" class="accordion-body ' . $nav_in . ' collapse"><div class="accordion-inner"><label class="checkbox inline span12"><b>Check All</b><input name="' . esc_attr($div_name) . '-all" id="' . esc_attr($div_name) . '-all" class="checkall" type="checkbox" value="1" /></label><div id="' . esc_attr($div_name) . '">';
			}
			if($count == 1)
			{
				$html .= '<div class="control-group">';
			}

			$html .= '<label class="checkbox inline span3">' . $key;
			$html .= '<input name="role-' . esc_attr($mycap) . '" id="role-' . esc_attr($mycap) . '" type="checkbox" value="1"';
			if(isset($myrole['role-'.$mycap]))
			{
				$html .= ' checked';
			}	
			$html .= '/> </label>';
			$count ++;
			if($count  == 5)
			{
				$html .= "</div>";
				$count = 1;
			}
			}
		}
		if($count <= 5)
		{
			$html .= "</div></div></div></div>";
			$count = 1;
		}
		$html .= "</div>"; //div for check all
	}

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

	$count = 1;
	$navcount = 0;

	if(empty($tax_caps))
	{
		$html = 'No taxonomies defined yet.';
	}
	else
	{
		foreach($tax_caps as $mytax_cap)
		{
			foreach($mytax_cap as $key => $mycap)
			{
				$nav_in = "";
				if($key == 'manage')
				{
					if($navcount == 0)
					{
						$nav_in = "in";
						$navcount ++;
					}
					$tax_name = str_replace('manage_','',$mycap);
					$div_name = $tax_name;
					$tax_name = str_replace('_',' ',$tax_name);
					$tax_name = ucwords($tax_name);
					$html.= '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" href="#collapse_' . esc_attr($mycap) . '" data-toggle="collapse" data-parent="#tab-tax">Taxonomy : ' . esc_html($tax_name) . '</a></div><div id="collapse_'. esc_attr($mycap) . '" class="accordion-body ' . $nav_in . ' collapse"><div class="accordion-inner"><label class="checkbox inline span12"><b>Check All</b><input name="' . esc_attr($div_name) . '-all" id="' . esc_attr($div_name) . '-all" class="checkall" type="checkbox" value="1" /></label>';
				}
				if($count == 1)
				{
					$html .= '<div class="control-group" id="' . esc_attr($div_name) . '">';
				}
				$html .= '<label class="checkbox inline span3">' . $key;
				$html .= '<input name="role-' . esc_attr($mycap) . '" id="role-' . esc_attr($mycap) . '" type="checkbox" value="1"';
				if(isset($myrole['role-'.$mycap]))
				{
					$html .= ' checked';
				}	
				$html .= '/> </label>';
				$count ++;
				if($count  == 5)
				{
					$html .= "</div>";
					$count = 1;
				}
			}
			if($count <= 5)
			{
				$html .= "</div></div></div>";
				$count = 1;
			}
		}
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

	$count = 1;
	$navcount = 0;

	if(empty($widg_caps))
	{
		$html = 'Only dashboard widgets can have permissions set.';
	}
	else
	{
		foreach($widg_caps as $mywidg_cap)
		{
			foreach($mywidg_cap as $key => $mycap)
			{
				$nav_in = "";
				if($key == 'view')
				{
					if($navcount == 0)
					{
						$nav_in = "in";
						$navcount ++;
					}
					$widg_name = str_replace('view_','',$mycap);
					$div_name = $widg_name;
					$widg_name = preg_replace('/^entity_/',' Entity : ',$widg_name);
					$widg_name = preg_replace('/^admin_/',' Admin : ',$widg_name);
					$widg_name = str_replace('_',' ',$widg_name);
					$widg_name = ucwords($widg_name);
					$html.= '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" href="#collapse_' . esc_attr($mycap) . '" data-toggle="collapse" data-parent="#tab-widg">Widget : ' . esc_html($widg_name) . '</a></div><div id="collapse_'. esc_attr($mycap) . '" class="accordion-body ' . $nav_in . ' collapse"><div class="accordion-inner"><label class="checkbox inline span12"><b>Check All</b><input name="' . esc_attr($div_name) . '-all" id="' . esc_attr($div_name) . '-all" class="checkall" type="checkbox" value="1" /></label>';
				}
				if($count == 1)
				{
					$html .= '<div class="control-group" id="' . esc_attr($div_name) . '">';
				}
				$html .= '<label class="checkbox inline span3">' . $key;
				$html .= '<input name="role-' . $mycap . '" id="role-' . esc_attr($mycap) . '" type="checkbox" value="1"';
				if(isset($myrole['role-'.$mycap]))
				{
					$html .= ' checked';
				}	
				$html .= '/> </label>';
				$count ++;
				if($count  == 3)
				{
					$html .= "</div>";
					$count = 1;
				}
			}
			if($count <= 3)
			{
				$html .= "</div></div></div>";
				$count = 1;
			}
		}
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

	$count = 1;
	$navcount = 0;

	if(empty($form_caps))
	{
		$html = 'No forms defined yet.';
	}
	else
	{
		foreach($form_caps as $myform_cap)
		{
			foreach($myform_cap as $key => $mycap)
			{
				$nav_in = "";
				if($key == 'view')
				{
					if($navcount == 0)
					{
						$nav_in = "in";
						$navcount ++;
					}
					$form_name = str_replace('view_','',$mycap);
					$div_name = $form_name;
					$form_name = str_replace('_',' ',$form_name);
					$form_name = ucwords($form_name);
					$html.= '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" href="#collapse_' . esc_attr($mycap) . '" data-toggle="collapse" data-parent="#tab-form">Form : ' . esc_html($form_name) . '</a></div><div id="collapse_'. esc_attr($mycap) . '" class="accordion-body ' . $nav_in . ' collapse"><div class="accordion-inner">';
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
				if($count  == 5)
				{
					$html .= "</div>";
					$count = 1;
				}
			}
			if($count <= 5)
			{
				$html .= "</div></div></div>";
				$count = 1;
			}
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
    <h3><i class="icon-flag icon-red"></i>Error</h3>
  </div>
  <div class="modal-body" style="clear:both">There must be at least one capability enabled.
  </div>
  <div class="modal-footer">
<button id="error-ok" data-dismiss="errorRoleModal" aria-hidden="true" class="btn btn-primary">OK</button>
  </div>
</div>
		<form action="" method="post" id="role-form" name="role-form" class="form-horizontal">
		<input type="hidden" id="app" name="app" value="<?php echo esc_attr($app_id); ?>" />
		<input type="hidden" value="<?php echo esc_attr($role_id); ?>" name="role" id="role" />  
		<fieldset>
		<div class="well">
		<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="A role is a collection of capabilities which enable or disable access to your application's data."> HELP</a></div></div>

		<div class="control-group row-fluid">
		<label class="control-label span3">Name</label>
		<div class="controls span9">
		<input class="input-xlarge" name="role-name" id="role-name" type="text" placeholder="e.g. product_owner"
		<?php 
		 if(isset($myrole['role-name']))
		 {
			 echo ' value="' . esc_attr($myrole['role-name']) . '" ' . $disable;
		 }
		?>
		></input><a href="#" title="Sets a unique name containing only alphanumeric characters and underscores." style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3">Label</label>
		<div class="controls span9">
		<input class="input-xlarge" name="role-label" id="role-label" type="text" placeholder="e.g. Product Owner"
		<?php 
		 if(isset($myrole['role-label']))
		 {
			 echo ' value="' . esc_attr($myrole['role-label']) . '" ' . $disable ;
		 }
		?>
		></input><a href="#" title="Sets a role label to represent your role." style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="tabbable">
		<ul class="nav nav-tabs">
		<li>
		<a data-toggle="tab" href="#tab-def">Default Capabilities</a>
		</li>
		<li class="active">
		<a data-toggle="tab" href="#tab-ent">Entities</a>
		</li>
		<li>
		<a data-toggle="tab" href="#tab-tax">Taxonomies</a>
		</li>
		<li>
		<a data-toggle="tab" href="#tab-widg">Widgets</a>
		</li>
		<li>
		<a data-toggle="tab" href="#tab-form">Forms</a>
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
		</div>
		</div> 
		</div> 
		<div class="control-group">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
		<i class="icon-ban-circle"></i>Cancel</button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-role" type="submit" value="Save">
		<i class="icon-save"></i>Save</button>
		</div>
		</fieldset>
		</form>

<?php
}
?>
