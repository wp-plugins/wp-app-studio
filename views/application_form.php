<?php
//functions to get and update applist and a specific app
function wpas_get_app_list($app_key_list = "")
{
	$app_list = Array();
	if(empty($app_key_list))
	{	
		$app_key_list = get_option('wpas_app_key_list');
	}
	if($app_key_list !== false && !empty($app_key_list))
	{
		foreach($app_key_list as $app_key)
		{
			if(get_option('wpas_app_' . $app_key) !== false)
			{
				$app_list[$app_key] = unserialize(base64_decode(get_option('wpas_app_' . $app_key)));
			}
		}
	}
	return $app_list;
}
function wpas_get_app($app_key)
{
	if(get_option('wpas_app_' . $app_key) !== false)
	{
		return unserialize(base64_decode(get_option('wpas_app_' . $app_key)));
	}
	return false;
}
	
function wpas_update_app($app,$app_key,$type='')
{
	if(empty($type))
	{
		$app['modified_date']= date('Y-m-d H:i:s');
	}
	if($type == 'new_with_date' && !empty($app['app_name']))
	{
		$app_date = str_replace(":","-",$app['modified_date']);
		$app_date = str_replace(" ","-",$app_date);
		$app['app_name'] = sanitize_text_field($app['app_name'] . "-" . $app_date);
		
	}
	if(is_array($app) && !empty($app))
	{
		$app_serialized = base64_encode(serialize($app));
		update_option('wpas_app_' . $app_key ,$app_serialized);
		if($type == 'new' || $type == 'new_with_date')
		{
			wpas_update_app_key_list($app_key,'add');
		}
	}
}
function wpas_update_app_key_list($app_key,$type)
{
	$app_key = sanitize_text_field($app_key);
	$app_key_list = get_option('wpas_app_key_list');
	if($app_key_list === false)
	{
		$app_key_list = Array();
	}
	if($type == 'add')
	{
		if(!in_array($app_key,$app_key_list))
		{
			$app_key_list[] = $app_key;
		}
	}
	elseif($type == 'delete')
	{
		foreach($app_key_list as $mykey => $myapp_key)
		{
			if($myapp_key == $app_key)
			{
				unset($app_key_list[$mykey]);
				break;
			}
		}
	}
	update_option('wpas_app_key_list',$app_key_list);
	return $app_key_list;
}
function wpas_delete_app($app_key)
{
	$app_key = sanitize_text_field($app_key);
	delete_option('wpas_app_' . $app_key);
	return wpas_update_app_key_list($app_key,'delete');
}

function wpas_add_app_form($button,$app_id,$app_title,$style)
{
        ?>
                <div class="row-fluid">
                <div class="span12" id="app-save" style="display: <?php echo $style; ?>">
                <form action="" method="post" id="app_form" class="well form-inline">
                <fieldset>
                <input id="app_title" type="text" class="input-xlarge" placeholder="<?php _e("Enter application name","wpas");?>" value="<?php  echo esc_attr($app_title); ?>" name="app_title">
                <input type="hidden" name="app" value="<?php echo esc_attr($app_id); ?>" id="app">
                <input type="hidden" name="type" value="app" id="type">
		<?php wp_nonce_field("wpas_" . strtolower($button) . "_app_nonce"); ?>
		<input class="btn btn-mid btn-primary" id="save-app" type="submit" name="Save" value="<?php echo $button; ?>">
                </fieldset>
                </form>
                </div></div>
                <?php
}
function wpas_nav($app_name,$option="")
{
	if(is_array($option))
	{
                $option_link = '<p id="update-option"><a href="#' .esc_attr($app_name) .'"><i class="icon-picture"></i>' . __("Update","wpas") . '</a></p>';
	}
	else
	{
                $option_link = '<p id="add-option"><a href="#' .esc_attr($app_name) .'"><i class="icon-picture"></i>' . __("Add New","wpas") . '</a></p>';
	}
        ?>
                <div class="row-fluid"><div id="was-nav" class="span2">
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse1" data-parent="#was-nav" data-toggle="collapse"><i class="icon-table icon-large"></i><?php _e("Entities","wpas");?></a>
                </div>
                <div id="collapse1" class="accordion-body in collapse">
                <div class="accordion-inner">
                <p id="add-entity"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-table"></i><?php _e("Add New","wpas");?></a></p>
                <p id="entity"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse2" data-parent="#was-nav" data-toggle="collapse"><i class="icon-tag icon-large"></i><?php _e("Taxonomies","wpas");?></a>
                </div>
                <div id="collapse2" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-taxonomy"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-tag"></i><?php _e("Add New","wpas");?></a></p>
                <p id="taxonomy"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse3" data-parent="#was-nav" data-toggle="collapse"><i class="icon-link icon-large"></i><?php _e("Relationships","wpas");?></a>
  </div>
                <div id="collapse3" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-relationship"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-link"></i><?php _e("Add New","wpas");?></a></p>
                <p id="relationship"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse4" data-parent="#was-nav" data-toggle="collapse"><i class="icon-cog icon-large"></i><?php _e("Widgets","wpas");?></a>
  </div>
                <div id="collapse4" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-widget"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-cog"></i><?php _e("Add New","wpas");?></a></p>
                <p id="widget"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse5" data-parent="#was-nav" data-toggle="collapse"><i class="icon-info-sign icon-large"></i><?php _e("Help Screens","wpas");?></a>
  </div>
                <div id="collapse5" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-help"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-info-sign"></i><?php _e("Add New","wpas");?></a></p>
                <p id="help"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse6" data-parent="#was-nav" data-toggle="collapse"><i class="icon-list-alt icon-large"></i><?php _e("Forms","wpas");?></a>
  </div>
                <div id="collapse6" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-form"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-list-alt"></i><?php _e("Add New","wpas");?></a></p>
                <p id="form"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse7" data-parent="#was-nav" data-toggle="collapse"><i class="icon-eye-open icon-large"></i><?php _e("Views","wpas");?></a>
  </div>
                <div id="collapse7" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-shortcode"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-eye-open"></i><?php _e("Add New","wpas");?></a></p>
                <p id="shortcode"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse8" data-parent="#was-nav" data-toggle="collapse"><i class="icon-volume-up icon-large"></i><?php _e("Notifications","wpas");?></a>
  </div>
                <div id="collapse8" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-notify"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-volume-up"></i><?php _e("Add New","wpas");?></a></p>
                <p id="notify"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse9" data-parent="#was-nav" data-toggle="collapse"><i class="icon-external-link icon-large"></i><?php _e("Connections","wpas");?></a>
  </div>
                <div id="collapse9" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-connection"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-external-link"></i><?php _e("Add New","wpas");?></a></p>
                <p id="connection"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
		<div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse10" data-parent="#was-nav" data-toggle="collapse"><i class="icon-key icon-large"></i><?php _e("Permissions","wpas");?></a>
  </div>
                <div id="collapse10" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-role"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-key"></i><?php _e("Add New","wpas");?></a></p>
                <p id="role"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i><?php _e("List All","wpas");?></a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapse11" data-parent="#was-nav" data-toggle="collapse"><i class="icon-picture icon-large"></i><?php _e("Settings","wpas");?></a>
                </div>
                <div id="collapse11" class="accordion-body collapse">
                <div class="accordion-inner">
		<?php echo $option_link; ?>
                </div>
                </div>
                </div>                
                </div>
                <?php
}
function wpas_list_html($list_values)
{
        $ret =  '<form action="" method="post"><div id="title-bar">
		<div class="row-fluid">
		<h4 class="span3"><i class="' . $list_values['icon'] . '"></i>' .  $list_values['title'] . '</h4>';
	if($list_values['type'] == 'app')
	{
		$ret .= '<div class="pull-right ' . $list_values['type'] . '" id="add-new">
			<a class="btn btn-warning  pull-left" href="' .  wp_nonce_url($list_values['import'],'wpas_import') . '" class="import">
			<i class="icon-signin"></i>' . __("Import","wpas") . '</a>
       			<a class="btn btn-info  pull-right add-new" href="' . esc_url($list_values['add_new_url'])  . '">
			<i class="icon-plus-sign"></i>' . __("Add New","wpas") . '</a>';
	}
	else
	{
		$ret .= '<div class="span9 ' . $list_values['type'] . '" id="add-new">
			<a class="btn btn-info  pull-right add-new" href="' . esc_url($list_values['add_new_url']) . '">
			<i class="icon-plus-sign"></i>' . __("Add New","wpas") . '</a>';
	}
	$ret .= '</div>
		</div>
		</div>';
	if(isset($list_values['app_name']))
	{
                $ret .= '<input type="hidden" name="app-name" id="app-name" value="' . esc_attr($list_values['app_name']). '">';
	}
        $ret .= '<ul class="subsubsub">
                <li class="all">All<span class="count">(' . intval ($list_values['count']) . ')</span></li>
                </ul>
                <div class="tablenav top">
                <div class="alignleft actions ' . $list_values['type'] . '">
                        <select name="action" class="' . $list_values['type'] . '">
                        <option selected="selected" value="-1">' . __("Bulk Actions","wpas") . '</option>
                        <option value="delete">' . __("Delete","wpas") . '</option>
                        </select>
                        <input id="doaction" class="btn  btn-primary" type="submit" value="' . __("Apply","wpas") . '">
                </div>
                <div class="pagination pagination-right"> ';
	return $ret;
}
function wpas_list_table($labels)
{

	$others = "";

	foreach($labels as $mylabel)
	{
	$others .= '<th class="manage-column column-name"><span>' . $mylabel . '</span></th>';
	}

	$ret =  '<table class="table table-striped table-condensed table-bordered" cellspacing="0">
	<thead><tr class="theader">
	<th id="cb" class="manage-column column-cb check-column" scope="col">
	<input type="checkbox"></th>' . $others;

	$ret .='</thead>
	<tbody id="the-list">';
	return $ret;
}
function wpas_list_row($url,$key_list,$mylist,$field_name,$alt,$type,$other_fields)
{
	$view = "";
	$others = "";
	$view_url = $url['view'];
	$url_title = "View";

	switch ($type) {
		case 'entity':
			$view = '<span id="view" class="' . $type . '"><a href="' . $url['view'] . '" title="' . __("View","wpas") . '">' . __("View","wpas") . '</a> | </span>
			<span id="add_field" class="' . $type . '"><a href="' . $url['add_field'] . '" title="' . __("Add Attribute","wpas") . '">' . __("Add Attribute","wpas") . '</a> | </span>
			<span id="edit_layout" class="' . $type . '"><a href="' . $url['edit_layout'] . '" title="' . __("Edit Admin Layout","wpas") . '">' . __("Edit Admin Layout","wpas") . '</a>';
			break;
		case 'form':
			$view = ' <span id="edit_layout" class="' . $type . '"><a href="' . $url['edit_layout'] . '" title="' . __("Edit Layout","wpas") . '">' . __("Edit Layout","wpas") . '</a>';
			break;
		case 'relationship':
			$view = '<span id="view" class="' . $type . '"><a href="' . $url['view'] . '" title="' . __("View","wpas") . '">' . __("View","wpas") . '</a> </span>
			<span id="add_field" class="' . $type . '">'; 
			if($mylist['rel-type'] == 'many-to-many')
			{
				$view .= '| <a href="' . $url['add_field'] . '" title="' . __("Add Attribute","wpas") . '">' . __("Add Attribute","wpas") . '</a>';
			}
			break;
		case 'help':
			$view = '<span id="view" class="' . $type . '"><a href="' . $url['view'] . '" title="' . __("View","wpas") . '">' . __("View","wpas") . '</a> | </span>
			<span id="add_field" class="' . $type . '"><a href="' . $url['add_field'] . '" title="' . __("Add Tab","wpas") . '">' . __("Add Tab","wpas") . '</a>';
			break;
		case 'app':
			$view = '<span id="duplicate" class="' . $type . '"><a href="' . wp_nonce_url($url['duplicate'],'wpas_duplicate') . '" title="' . __("Duplicate","wpas") . '">' . __("Duplicate","wpas") . '</a>
				| <span id="export" class="' . $type . '"><a href="' . wp_nonce_url($url['export'],'wpas_export') . '" title="' . __("Export","wpas") . '">' . __("Export","wpas") . '</a>';
			$view_url = $url['edit_url'];
			$url_title = "Edit";
			break;
	}

	foreach($other_fields as $myfield)
	{
		if(isset($mylist[$myfield]))
		{
			if($mylist[$myfield] == '0')
			{
				$field_val = __("False","wpas");
			}
			elseif($mylist[$myfield] == '1')
			{
				$field_val = __("True","wpas");
			}
			else if($mylist[$myfield] == '')
			{
				$field_val = __("None defined.","wpas");
			}
			else
			{
				$field_val = $mylist[$myfield];
			}
		}
		else
		{
			$field_val = __("None defined.","wpas");
		}
		if(isset($field_val))
		{	
			$others .= '<td>' . $field_val . '</td>';
		}
	}

	if($field_name == 'help-entity' && !isset($mylist[$field_name]) && isset($mylist['help-tax']))
	{
		$mylist[$field_name] = $mylist['help-tax'];
	}
	$ret = '<tr valign="top" class="'. $alt . '">
	<th class="check-column" scope="row">
	<input type="checkbox" value="' .$key_list .'" name="checkbox[]">
	</th><td class="post-title page-title column-title" id="edit_td"><strong>
	<a class="row-title" id="' . $field_name . '" title="' . $url_title . '" href="' .  $view_url . '">' . esc_html($mylist[$field_name]) . '</a></strong>
	<div class="row-actions">';
	if($type == 'entity' && in_array($mylist['ent-name'],Array("post","page")))
	{
	}
	elseif($type == 'role' && in_array($mylist['role-name'],Array("administrator","contributor","editor","author","subscriber")))
	{
	$ret .='<span id="edit" class="' . $type . '"><a title="' . __("Edit","wpas") . '" href="' . $url['edit_url'] . '">' . __("Edit","wpas") . '</a></span>';
	}
	else
	{
	$ret .='<span id="edit" class="' . $type . '"><a title="' . __("Edit","wpas") . '" href="' . $url['edit_url'] . '">' . __("Edit","wpas") . '</a> | </span>';
	$ret .= '<span id="delete" class="' . $type . '"><a href="' . $url['delete_url'] . '" title="' . __("Delete","wpas") . '">' . __("Delete","wpas") . '</a>  | </span>';
	}
	$ret .= $view . '
	</span></div></td>' . $others; 
	$ret .='</tr>';
	return $ret;
}
function wpas_list($list_type,$app,$app_id=0,$page=1)
{
	$list_array = Array();
	if($list_type != 'app' && !empty($app))
	{
		if(!empty($app[$list_type]))
		{
			$list_array = $app[$list_type];
		}
		$app_name = $app['app_name'];
	}
	else
	{
		$list_array = $app;
		$app_name = "";
	}
        $return_list = "";
        $paging = Array();
        $paging_html  = "";
	$div_table = "";
	$edit_url = "#";
        $list_values = Array();
        $list_values['count'] = 0;
	if(isset($app_name))
	{
        	$list_values['app_name'] = $app_name;
	}

	if(!isset($page))
	{
		$page =1;
	}

        if(!empty($list_array))
        {
                $list_values['count'] = count($list_array);
        }
        if(isset($_REQUEST[$list_type . 'page']))
        {
                $page = intval ($_REQUEST[$list_type . 'page']);
        }
        $list_values['type'] = $list_type;
        $list_values['add_new_url'] = "#" . esc_attr($app_name);
        
	if($list_type == 'app')
        {
                $base = admin_url('admin.php?page=wpas_app_list');
                $list_values['title'] = __("Applications","wpas");
                $edit_url = wp_nonce_url(admin_url('admin.php?page=wpas_add_new_app&edit'),'wpas_edit_app_nonce') . '&app=';
                $duplicate_url = admin_url('admin.php?page=wpas_app_list&duplicate=1&app=');
                $export_url = admin_url('admin.php?page=wpas_app_list&export=1&app=');
                $list_values['import'] = admin_url('admin.php?page=wpas_app_list&import=1');
                $format = "apppage";
                $field_name = "app_name";
                $other_fields = Array('generate','entities','taxonomies','date','modified_date');
                $other_labels = Array(__("Name","wpas"),__("Generate","wpas"),__("Entities","wpas"),__("Taxonomies","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['add_new_url'] = admin_url('admin.php?page=wpas_add_new_app');
                $list_values['icon'] = "icon-cogs";
        }
	elseif($list_type == 'entity')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=entity&app='. $app_id);
                $list_values['title'] = __("Entities","wpas");
                $format = "entitypage";
                $field_name = "ent-name";
                $other_fields = Array("ent-label","ent-singular-label","ent-hierarchical","ent_fields","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Plural","wpas"),__("Singular","wpas"),__("Hierarchical","wpas"),__("Attributes","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-table";
                $add_field_tag = "#ent";
        }
	elseif($list_type == 'taxonomy')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=taxonomy&app=' . $app_id);
                $list_values['title'] = __("Taxonomies","wpas");
                $format = "taxonomypage";
                $field_name = "txn-name";
                $other_fields = Array("txn-label","txn-singular-label","txn-hierarchical","txn-attaches","txn-display_type","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Plural","wpas"),__("Singular","wpas"),__("Hierarchical","wpas"),__("Attached To","wpas"),__("Display","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-tag";
        }
        elseif($list_type == 'relationship')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=relationship&app=' . $app_id);
                $list_values['title'] = __("Relationships","wpas");
                $format = "relationshippage";
                $field_name = "rel-name";
                $other_fields = Array("rel-from-name","rel-to-name","rel-type","rel_fields","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("From","wpas"),__("To","wpas"),__("Type","wpas"),__("Attributes","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-link";
                $add_field_tag = "#rel";
        }
	elseif($list_type == 'help')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=help&app=' . $app_id);
                $list_values['title'] = __("Help Screens","wpas");
                $format = "helppage";
                $field_name = "help-entity";
                $other_fields = Array("help-screen_type","sidebar_on_off","help_tabs","date","modified_date");
                $other_labels = Array(__("Attached To","wpas"),__("Screen Type","wpas"),__("SideBar","wpas"),__("Tabs","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-info-sign";
                $add_field_tag = "#help";
        }
        elseif($list_type == 'role')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=role&app=' . $app_id);
                $list_values['title'] = __("Permissions","wpas");
                $format = "rolepage";
                $field_name = "role-name";
                $other_fields = Array("role-label","role_permissions","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Label","wpas"),__("Capabilities","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-key";
                $add_field_tag = "#role";
        }
	elseif($list_type == 'shortcode')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=shortcode&app=' . $app_id);
                $list_values['title'] = __("Views","wpas");
                $format = "shortcodepage";
                $field_name = "shc-label";
                $other_fields = Array("shc-attach","shc-view_type","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Attached To","wpas"),__("Type","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-eye-open";
                $add_field_tag = "#shortcode";
        }
	elseif($list_type == 'notify')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=notify&app=' . $app_id);
                $list_values['title'] = __("Notifications","wpas");
                $format = "notifypage";
                $field_name = "notify-name";
                $other_fields = Array("notify-level","notify-attached_to","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Level","wpas"),__("Attached To","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-volume-up";
                $add_field_tag = "#notify";
        }
	elseif($list_type == 'connection')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=connection&app=' . $app_id);
                $list_values['title'] = __("Connections","wpas");
                $format = "connectionpage";
                $field_name = "connection-name";
                $other_fields = Array("connection-type","connection-entity","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Type","wpas"),__("Attached To","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['icon'] = "icon-external-link";
                $add_field_tag = "#connection";
        }
	elseif($list_type == 'widget')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=widg&app=' . $app_id);
                $list_values['title'] = __("Widgets","wpas");
                $format = "widgpage";
                $field_name = "widg-name";
                $other_fields = Array("widg-type","widg-subtype","widg-attach","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Type","wpas"),__("Subtype","wpas"),__("Attached To","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['type'] = 'widg';
                $list_values['icon'] = "icon-cog";
                $add_field_tag = "#widg";
        }
	elseif($list_type == 'form')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=form&app=' . $app_id);
                $list_values['title'] = __("Forms","wpas");
                $format = "formpage";
                $field_name = "form-name";
                $other_fields = Array("form-form_type","form-attached_entity","form-temp_type","date","modified_date");
                $other_labels = Array(__("Name","wpas"),__("Type","wpas"),__("Attached To","wpas"),__("Template","wpas"),__("Created","wpas"),__("Modified","wpas"));
                $list_values['type'] = 'form';
                $list_values['icon'] = "icon-list-alt";
                $add_field_tag = "#form";
        }
        $return_list = wpas_list_html($list_values);
        if($list_values['count'] == 0)
        {
		$col_count = count($other_labels) + 1;
                $div_table = '<tr class="no-items"><td colspan="' . $col_count . '">';
		$div_table .= sprintf(__('No %s found.','wpas'),strtolower($list_values['title'])) . '</td></tr>';
        }
        else
        {
                $count = 0;
                foreach($list_array as $key_list => $mylist)
                {
                        if($list_type == 'app')
                        {
				$mylist['entities'] = "";
				$mylist['taxonomies'] = "";
				$mylist['generate'] = "<div id=\"generate\"><a class=\"btn btn-mini btn-success\" href=\"". admin_url('admin.php?page=wpas_generate_page&app=') . $mylist['app_id'] . "\">Generate</a></div>";
				if(isset($mylist['entity']))
				{
					foreach($mylist['entity'] as $myentity)
					{
						if(!empty($myentity['field']))
						{
							$mylist['entities'] .= $myentity['ent-label'] . ", ";
						}
					}
					$mylist['entities'] = rtrim($mylist['entities'],', ');
				}
				if(isset($mylist['taxonomy']))
				{
					foreach($mylist['taxonomy'] as $mytax)
					{
						$mylist['taxonomies'] .= $mytax['txn-label'] . ", ";
					}
					$mylist['taxonomies'] = rtrim($mylist['taxonomies'],', ');
				}
                        }
			elseif($list_type == 'entity')
                        {
                                $count_ent_fields = 0;
				if(isset($mylist['field']))
				{
					$mylist['ent_fields'] = "";
					foreach($mylist['field'] as $myfield)
					{
						if($count_ent_fields == 3)
						{
							$more_link = "#" . $key_list;
							$mylist['ent_fields'] .= "<a id=\"ent-name\" href=\"" . $more_link . "\"> " . __("More","wpas") . " >></a>";
							break;
						}
						$mylist['ent_fields'] .= $myfield['fld_label'] . ", ";
						$count_ent_fields ++;
					}
					$mylist['ent_fields'] = rtrim($mylist['ent_fields'],', ');
				}
                        }
                        elseif($list_type == 'relationship')
                        {
                                $count_rel_fields = 0;
				if(isset($mylist['field']))
				{
					$mylist['rel_fields'] ="";
					foreach($mylist['field'] as $myfield)
					{
						if($count_rel_fields == 3)
						{
							$more_link = "#" . $key_list;
							$mylist['rel_fields'] .= "<a id=\"rel-name\" href=\"" . $more_link . "\"> " . __("More","wpas") . " >></a>";
							break;
						}
						$mylist['rel_fields'] .= $myfield['rel_fld_label'] . ", ";
						$count_rel_fields++;
					}
					$mylist['rel_fields'] = rtrim($mylist['rel_fields'],', ');
				}
				if($mylist['rel-from-name'] != 'user')
				{
					$mylist['rel-from-name'] = $app['entity'][$mylist['rel-from-name']]['ent-label'];
				}
				else
				{
					$mylist['rel-from-name'] = ucfirst($mylist['rel-from-name']);
				}
				if($mylist['rel-to-name'] != 'user')
				{
					$mylist['rel-to-name'] = $app['entity'][$mylist['rel-to-name']]['ent-label'];
				}
				else
				{
					$mylist['rel-to-name'] = ucfirst($mylist['rel-to-name']);
				}
                        }
                        elseif($list_type == 'taxonomy')
                        {
				$mylist['txn-attaches'] = "";
				foreach($mylist['txn-attach'] as $txn_att)
				{
					$mylist['txn-attaches'] .= $app['entity'][$txn_att]['ent-label'] . ",";
				}
				$mylist['txn-attaches'] = rtrim($mylist['txn-attaches'],",");
				$mylist['txn-display_type'] = ucfirst($mylist['txn-display_type']);
                        }
			elseif($list_type == 'help')
                        {
                                if(isset($mylist['help-screen_sidebar']))
                                {
                                        $mylist['sidebar_on_off'] = __("Yes","wpas");
                                }
                                else
                                {
                                        $mylist['sidebar_on_off'] = __("No","wpas");
                                }
                                $count_help_tabs = 0;
				$mylist['help_tabs'] = "";
				if(isset($mylist['field']))
				{
					foreach($mylist['field'] as $myfield)
					{
						if($count_help_tabs == 3)
						{
							$more_link = "#" . $key_list;
							$mylist['help_tabs'] .= "<a id=\"help-entity\" href=\"" . $more_link . "\"> " . __("More","wpas") . " >></a>";
							break;
						}
						$mylist['help_tabs'] .= $myfield['help_fld_name'] . ", ";
						$count_help_tabs ++;
					}
                                	$mylist['help_tabs'] = rtrim($mylist['help_tabs'],', ');
				}
				if(isset($mylist['help-entity']))
				{
					$mylist['help-entity'] = $app['entity'][$mylist['help-entity']]['ent-label'];
				}
				elseif(isset($mylist['help-tax']))
				{
					$mylist['help-entity'] = $app['taxonomy'][$mylist['help-tax']]['txn-singular-label'];
				}
                        }
			elseif($list_type == 'role')
			{	
				$permission_count = count($mylist) - 4;  //count only caps
				$mylist['role_permissions'] = sprintf(__('%d capabilities set','wpas'),$permission_count);
			}
			elseif($list_type == 'widget')
			{	
				if($mylist['widg-type'] == 'sidebar')
				{
					$subtype = "widg-side_subtype";
				}
				elseif($mylist['widg-type'] == 'dashboard')
				{
					$subtype = "widg-dash_subtype";
				}
				if(isset($mylist['widg-attach']) && ((isset($mylist['widg-side_subtype']) && in_array($mylist['widg-side_subtype'], Array('entity','comment'))) || (isset($mylist['widg-dash_subtype']) && in_array($mylist['widg-dash_subtype'],Array('entity','comment')))))
				{
					$mylist['widg-attach'] = $app['entity'][$mylist['widg-attach']]['ent-label'];
				}
				elseif(isset($mylist['widg-dash_subtype']) && $mylist['widg-dash_subtype'] == 'admin')
				{
					$mylist['widg-attach']  = "";
				}
				$other_fields[1] = $subtype;
			}
			elseif($list_type == 'form')
			{
				if($mylist['form-temp_type'] == 'Pure')
				{
					$mylist['form-temp_type'] = 'jQuery UI';
				}
				if(isset($mylist['form-attached_entity']) && $mylist['form-attached_entity'] != "")
				{
					$mylist['form-attached_entity'] = $app['entity'][$mylist['form-attached_entity']]['ent-label'];
				}
			}
			elseif($list_type == 'connection')
			{
				if($mylist['connection-type'] == 'inc_email')
				{
					$mylist['connection-type'] = __('Incoming Email','wpas');
				}
				if(isset($mylist['connection-entity']) && $mylist['connection-entity'] != "")
				{
					$mylist['connection-entity']= $app['entity'][$mylist['connection-entity']]['ent-label'];
				}
			}
			elseif($list_type == 'shortcode')
			{
				switch ($mylist['shc-view_type']) {
					case 'std':	
						$mylist['shc-view_type'] = __("Standard","wpas");
						$mylist['shc-attach'] = $app['entity'][$mylist['shc-attach']]['ent-label'];
						break;
					case 'search':
						$mylist['shc-view_type'] = __("Search","wpas");
						$mylist['shc-attach'] = $app['form'][$mylist['shc-attach_form']]['form-name'];
						break;
					case 'single':
						$mylist['shc-view_type'] = __("Single","wpas");
						$mylist['shc-attach'] = $app['entity'][$mylist['shc-attach']]['ent-label'];
						break;
					case 'archive':
						$mylist['shc-view_type'] = __("Archive","wpas");
						$mylist['shc-attach'] = $app['entity'][$mylist['shc-attach']]['ent-label'];
						break;
					case 'tax':
						$mylist['shc-view_type'] = __("Taxonomy","wpas");
						$ent = $app['entity'][$mylist['shc-attach']]['ent-label'];
						$mylist['shc-attach'] = $app['taxonomy'][$mylist['shc-attach_tax']]['txn-label'];
						$mylist['shc-attach'] .= " (";
						if(!empty($mylist['shc-attach_taxterm']))
						{
						 	$mylist['shc-attach'] .= $mylist['shc-attach_taxterm'] . "-";
						}
						$mylist['shc-attach'] .= $ent . ")";
						break;
					case 'chart':
						$mylist['shc-view_type'] = __("Chart","wpas");	
						$mylist['shc-attach'] = $app['entity'][$mylist['shc-attach']]['ent-label'];
						break;
					case 'datagrid':
						$mylist['shc-view_type'] = __("Data Grid","wpas");	
						$mylist['shc-attach'] = $app['entity'][$mylist['shc-attach']]['ent-label'];
						break;
				}
			}
			elseif($list_type == 'notify')
			{
				switch($mylist['notify-level']) {
					case 'entity':
						$mylist['notify-level'] = "Entity";
						$mylist['notify-attached_to'] = $app['entity'][$mylist['notify-attached_to']]['ent-label'];
						break;
					case 'tax':
						$mylist['notify-level'] = "Taxonomy";
						$mylist['notify-attached_to'] = $app['taxonomy'][$mylist['notify-attached_to']]['txn-label'];
						break;
					case 'attr':
						$fids = explode("__",$mylist['notify-attached_to']);
						$mylist['notify-level'] = "Attribute";
						$mylist['notify-attached_to'] = $app['entity'][$fids[1]]['field'][$fids[0]]['fld_label'];
						break;
					case 'rel':
						$mylist['notify-level'] = "Relationship";
						$mylist['notify-attached_to'] = $app['relationship'][$mylist['notify-attached_to']]['rel-name'];
						break;
					case 'com':
						$mylist['notify-level'] = "Comment";
						$mylist['notify-attached_to'] = $app['entity'][$mylist['notify-attached_to']]['ent-label'];
						break;
				}
			}
                        $url['edit_url'] = $edit_url . $key_list;
                        $url['delete_url'] = "#" . $key_list;
                        $url['view'] = "#" . $key_list;
			if(isset($add_field_tag))
			{
                        	$url['add_field'] = $add_field_tag . $key_list;
                        	$url['edit_layout'] =  $add_field_tag .  $key_list;
			}
			if(isset($duplicate_url))
			{
                        	$url['duplicate'] = $duplicate_url . $key_list;
			}
			if(isset($export_url))
			{
                        	$url['export'] = $export_url . $key_list;
			}
                        if($count < ($page * 10) && $count >= ($page-1)*10)
                        {
                                $alt = "";
                                if($count %2)
                                {
                                        $alt = "alternate";
                                }
                                $div_table .= wpas_list_row($url,$key_list,$mylist,$field_name,$alt,$list_type,$other_fields);
                        }
                        $count ++;
                }
	
		$paging = paginate_links( array(
                                        'total' => ceil($list_values['count']/10),
                                        'current' => $page,
                                        'base' => $base .'&%_%',
                                        'format' => $format . '=%#%',
					'type' => 'array',
                                        ) );
		if(!empty($paging))
		{
			$paging_html = "<ul>";
			foreach($paging as $key_paging => $my_paging)
			{
				$paging_html .= "<li";
				if(($page == 1 && $key_paging == 0) || ($page > 1 && $page == $key_paging))
				{
					$paging_html .= " class='active'";
				}
				$paging_html .= ">" . $my_paging . "</li>";
			}
			$paging_html .= "</ul>";
		}

                //$div_table .= "</tbody></table>";
        }
        $return_list .= $paging_html . "</div><br class=\"clear\"></div>";
        $return_list .= wpas_list_table($other_labels);
        $return_list .= $div_table . "</tbody></table></form>";
        return $return_list;
}
function wpas_breadcrumb($page,$app_id)
{
	$home = admin_url('admin.php?page=wpas_app_list');
	echo '<div class="wpas">';
	wpas_branding_header();
	echo '<div id="was-container" class="container-fluid">';
	echo '<div class="row-fluid">';
	echo '<ul class="breadcrumb span9">
                <li id="first">
                <a href="'. $home . '"><i class="icon-home"></i> ' . __("Home","wpas") . '</a> <span class="divider">/</span>
                </li>';
        if($page == "add_new_app")
        {
                echo '<li id="second" class="active">' . __("Add New Application","wpas") . '</li>
                        </li>
                        </ul>';
        }
        elseif($page == "edit_app")
        {
                echo '<li id="second" class="active">' . __("Edit Application","wpas") . '</li>
                        </ul>';
        }
	echo '<div class="span3"><a style="padding:7px 14px;" class="btn btn-success pull-right" href="'. admin_url('admin.php?page=wpas_generate_page&app=') .$app_id . '"><i class="icon-play"></i>' . __("Generate","wpas") .'</a></div>';
	echo '</div>';
}


?>
