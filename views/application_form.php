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
                <input id="app_title" type="text" class="input-xlarge" placeholder="Enter application name" value="<?php  echo esc_attr($app_title); ?>" name="app_title">
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
                $option_link = '<p id="update-option"><a href="#' .esc_attr($app_name) .'"><i class="icon-picture"></i>Update</a></p>';
	}
	else
	{
                $option_link = '<p id="add-option"><a href="#' .esc_attr($app_name) .'"><i class="icon-picture"></i>Add New</a></p>';
	}
        ?>
                <div class="row-fluid"><div id="was-nav" class="span3">
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseOne" data-parent="#was-nav" data-toggle="collapse"><i class="icon-table icon-large"></i>Entities</a>
                </div>
                <div id="collapseOne" class="accordion-body in collapse">
                <div class="accordion-inner">
                <p id="add-entity"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-table"></i>Add New</a></p>
                <p id="entity"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseTwo" data-parent="#was-nav" data-toggle="collapse"><i class="icon-tag icon-large"></i>Taxonomies</a>
                </div>
                <div id="collapseTwo" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-taxonomy"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-tag"></i>Add New</a></p>
                <p id="taxonomy"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseThree" data-parent="#was-nav" data-toggle="collapse"><i class="icon-link icon-large"></i>Relationships</a>
  </div>
                <div id="collapseThree" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-relationship"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-link"></i>Add New</a></p>
                <p id="relationship"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseFour" data-parent="#was-nav" data-toggle="collapse"><i class="icon-bookmark icon-large"></i>Shortcodes</a>
  </div>
                <div id="collapseFour" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-shortcode"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-bookmark"></i>Add New</a></p>
                <p id="shortcode"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseFive" data-parent="#was-nav" data-toggle="collapse"><i class="icon-cog icon-large"></i>Widgets</a>
  </div>
                <div id="collapseFive" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-widget"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-cog"></i>Add New</a></p>
                <p id="widget"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseSix" data-parent="#was-nav" data-toggle="collapse"><i class="icon-list-alt icon-large"></i>Forms</a>
  </div>
                <div id="collapseSix" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-form"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-list-alt"></i>Add New</a></p>
                <p id="form"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseSeven" data-parent="#was-nav" data-toggle="collapse"><i class="icon-info-sign icon-large"></i>Help Screens</a>
  </div>
                <div id="collapseSeven" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-help"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-info-sign"></i>Add New</a></p>
                <p id="help"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseEight" data-parent="#was-nav" data-toggle="collapse"><i class="icon-map-marker icon-large"></i>Pointers</a>
  </div>
                <div id="collapseEight" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-pointer"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-map-marker"></i>Add New</a></p>
                <p id="pointer"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
		<div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseNine" data-parent="#was-nav" data-toggle="collapse"><i class="icon-key icon-large"></i>Permissions</a>
  </div>
                <div id="collapseNine" class="accordion-body collapse">
                <div class="accordion-inner">
                <p id="add-role"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-key"></i>Add New</a></p>
                <p id="role"><a href="#<?php echo esc_attr($app_name); ?>"><i class="icon-reorder"></i>List All</a></p>
                </div>
                </div>
                </div>
                <div class="accordion-group">
                <div class="accordion-heading">
                <a class="accordion-toggle" href="#collapseTen" data-parent="#was-nav" data-toggle="collapse"><i class="icon-picture icon-large"></i>Settings</a>
                </div>
                <div id="collapseTen" class="accordion-body collapse">
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
			<a class="btn btn-info  pull-left" href="' .  wp_nonce_url($list_values['import'],'wpas_import') . '" class="import">
			<i class="icon-signin"></i>Import</a>
       			<a class="btn btn-info  pull-right" href="' . esc_url($list_values['add_new_url'])  . '" class="add-new">
			<i class="icon-plus-sign"></i>Add New</a>';
	}
	else
	{
		$ret .= '<div class="span9 ' . $list_values['type'] . '" id="add-new">
			<a class="btn btn-info  pull-right" href="' . esc_url($list_values['add_new_url']) . '" class="add-new">
			<i class="icon-plus-sign"></i>Add New</a>';
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
                        <option selected="selected" value="-1">Bulk Actions</option>
                        <option value="delete">Delete</option>
                        </select>
                        <input id="doaction" class="btn  btn-primary" type="submit" value="Apply">
                </div>
                <div class="tablenav-pages one-page"> ';
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

	if($type == "entity")
	{
	$view = '<span id="view" class="' . $type . '"><a href="' . $url['view'] . '" title="View">View</a> | </span>
	<span id="add_field" class="' . $type . '"><a href="' . $url['add_field'] . '" title="Add Attribute">Add Attribute</a> | </span>
	<span id="edit_layout" class="' . $type . '"><a href="' . $url['edit_layout'] . '" title="Edit Layout">Edit Layout</a>';
	}
	else if($type == "relationship")
	{
	$view = '<span id="view" class="' . $type . '"><a href="' . $url['view'] . '" title="View">View</a> | </span>
	<span id="add_field" class="' . $type . '"><a href="' . $url['add_field'] . '" title="Add Attribute">Add Attribute</a>';
	}
	else if($type == "help")
	{
	$view = '<span id="view" class="' . $type . '"><a href="' . $url['view'] . '" title="View">View</a> | </span>
	<span id="add_field" class="' . $type . '"><a href="' . $url['add_field'] . '" title="Add Tab">Add Tab</a>';
	}
	else if($type == 'app')
	{
	$view = '<span id="generate" class="' . $type . '"><a href="' . wp_nonce_url($url['generate'],'wpas_generate') . '" title="Generate">Generate</a>
		| <span id="export" class="' . $type . '"><a href="' . wp_nonce_url($url['export'],'wpas_export') . '" title="Export">Export</a>';
	$view_url = $url['edit_url'];
	$url_title = "Edit";
	}

	foreach($other_fields as $myfield)
	{
		if(isset($mylist[$myfield]))
		{
			if($mylist[$myfield] == '0')
			{
				$field_val = "False";
			}
			elseif($mylist[$myfield] == '1')
			{
				$field_val = "True";
			}
			else if($mylist[$myfield] == '')
			{
				$field_val = "None defined.";
			}
			else
			{
				$field_val = $mylist[$myfield];
			}
		}
		else
		{
			$field_val = "None defined.";
		}
		if(isset($field_val))
		{	
			$others .= '<td>' . $field_val . '</td>';
		}
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
	$ret .='<span id="edit" class="' . $type . '"><a title="Edit" href="' . $url['edit_url'] . '">Edit</a>  </span>';
	}
	else
	{
	$ret .='<span id="edit" class="' . $type . '"><a title="Edit" href="' . $url['edit_url'] . '">Edit</a> | </span>';
	$ret .= '<span id="delete" class="' . $type . '"><a href="' . $url['delete_url'] . '" title="Delete">Delete</a>  | </span>';
	}
	$ret .= $view . '
	</span></div></td>' . $others; 
	$ret .='</tr>';
	return $ret;
}
function wpas_list($list_type,$list_array,$app_id=0,$app_name="",$page=1)
{
        $return_list = "";
        $paging_text = "";
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
                $list_values['title'] = "Applications";
                $edit_url = wp_nonce_url(admin_url('admin.php?page=wpas_add_new_app&edit'),'wpas_edit_app_nonce') . '&app=';
                $generate_url = admin_url('admin.php?page=wpas_app_list&generate=1&app=');
                $export_url = admin_url('admin.php?page=wpas_app_list&export=1&app=');
                $list_values['import'] = admin_url('admin.php?page=wpas_app_list&import=1');
                $format = "apppage";
                $field_name = "app_name";
                $other_fields = Array('entities','taxonomies','date','modified_date');
                $other_labels = Array("Name","Entities","Taxonomies","Created","Modified");
                $list_values['add_new_url'] = admin_url('admin.php?page=wpas_add_new_app');
                $list_values['icon'] = "icon-cogs";
        }
	elseif($list_type == 'entity')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=entity&app='. $app_id);
                $list_values['title'] = "Entities";
                $format = "entitypage";
                $field_name = "ent-name";
                $other_fields = Array("ent-label","ent-singular-label","ent-hierarchical","ent_fields","date","modified_date");
                $other_labels = Array("Name","Plural Label","Singular Label","Hierarchical","Attributes","Created","Modified");
                $list_values['icon'] = "icon-table";
                $add_field_tag = "#ent";
        }
	elseif($list_type == 'taxonomy')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=taxonomy&app=' . $app_id);
                $list_values['title'] = "Taxonomies";
                $format = "taxonomypage";
                $field_name = "txn-name";
                $other_fields = Array("txn-label","txn-singular-label","txn-hierarchical","txn-attaches","date","modified_date");
                $other_labels = Array("Name","Plural Label","Singular Label","Hierarchical","Attached Entities","Created","Modified");
                $list_values['icon'] = "icon-tag";
        }
        elseif($list_type == 'relationship')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=relationship&app=' . $app_id);
                $list_values['title'] = "Relationships";
                $format = "relationshippage";
                $field_name = "rel-name";
                $other_fields = Array("rel-from-title","rel-to-title","rel-type","rel_fields","date","modified_date");
                $other_labels = Array("Name","From Title","To Title","Type","Attributes","Created","Modified");
                $list_values['icon'] = "icon-link";
                $add_field_tag = "#rel";
        }
	elseif($list_type == 'help')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=help&app=' . $app_id);
                $list_values['title'] = "Help";
                $format = "helppage";
                $field_name = "help-object_name";
                $other_fields = Array("help-screen_type","sidebar_on_off","help_tabs","date","modified_date");
                $other_labels = Array("Attach To","Screen Type","SideBar","Tabs","Created","Modified");
                $list_values['icon'] = "icon-info-sign";
                $add_field_tag = "#help";
        }
        elseif($list_type == 'role')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=role&app=' . $app_id);
                $list_values['title'] = "Roles";
                $format = "rolepage";
                $field_name = "role-name";
                $other_fields = Array("role-label","role_permissions","date","modified_date");
                $other_labels = Array("Name","Label","Capabilities","Created","Modified");
                $list_values['icon'] = "icon-key";
                $add_field_tag = "#role";
        }
	elseif($list_type == 'shortcode')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=shortcode&app=' . $app_id);
                $list_values['title'] = "ShortCode";
                $format = "shortcodepage";
                $field_name = "shc-label";
                $other_fields = Array("shc-attach","date","modified_date");
                $other_labels = Array("Name","Attach To Entity","Created","Modified");
                $list_values['icon'] = "icon-bookmark";
                $add_field_tag = "#shortcode";
        }
	elseif($list_type == 'widget')
        {
                $base = admin_url('admin.php?page=wpas_add_new_app&view=widg&app=' . $app_id);
                $list_values['title'] = "Widget";
                $format = "widgpage";
                $field_name = "widg-title";
                $other_fields = Array("widg-type","widg-subtype","widg-attach","date","modified_date");
                $other_labels = Array("Title","Type","Subtype","Attach To Entity","Created","Modified");
                $list_values['type'] = 'widg';
                $list_values['icon'] = "icon-cog";
                $add_field_tag = "#widg";
        }
        $return_list = wpas_list_html($list_values);
        if($list_values['count'] == 0)
        {
		$col_count = count($other_labels) + 1;
                $div_table = '<tr class="no-items"><td colspan="' . $col_count . '">No ' . strtolower($list_values['title']) . ' found</td></tr>';
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
				if(isset($mylist['entity']))
				{
					foreach($mylist['entity'] as $myentity)
					{
						$mylist['entities'] .= $myentity['ent-label'] . ", ";
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
							$mylist['ent_fields'] .= "<a id=\"ent-name\" href=\"" . $more_link . "\"> More >></a>";
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
                                $mylist['rel-name'] = $mylist['rel-from-name'] . '-' . $mylist['rel-to-name'];
                                $count_rel_fields = 0;
				if(isset($mylist['field']))
				{
					$mylist['rel_fields'] ="";
					foreach($mylist['field'] as $myfield)
					{
						if($count_rel_fields == 3)
						{
							$more_link = "#" . $key_list;
							$mylist['rel_fields'] .= "<a id=\"rel-name\" href=\"" . $more_link . "\"> More >></a>";
							break;
						}
						$mylist['rel_fields'] .= $myfield['rel_fld_label'] . ", ";
						$count_rel_fields++;
					}
					$mylist['rel_fields'] = rtrim($mylist['rel_fields'],', ');
				}
                        }
                        elseif($list_type == 'taxonomy')
                        {
				$mylist['txn-attaches'] = implode(",",$mylist['txn-attach']);
                        }
			elseif($list_type == 'help')
                        {
                                if(isset($mylist['help-screen_sidebar']))
                                {
                                        $mylist['sidebar_on_off'] = "Yes";
                                }
                                else
                                {
                                        $mylist['sidebar_on_off'] = "No";
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
							$mylist['help_tabs'] .= "<a id=\"help-object_name\" href=\"" . $more_link . "\"> More >></a>";
							break;
						}
						$mylist['help_tabs'] .= $myfield['help_fld_name'] . ", ";
						$count_help_tabs ++;
					}
                                	$mylist['help_tabs'] = rtrim($mylist['help_tabs'],', ');
				}
                        }
			elseif($list_type == 'role')
			{
				$permission_count = count($mylist) - 4;  //count only caps
				$mylist['role_permissions'] = $permission_count . " capabilities set";
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
				$other_fields[1] = $subtype;
			}
                        $url['edit_url'] = $edit_url . $key_list;
                        $url['delete_url'] = "#" . $key_list;
                        $url['view'] = "#" . $key_list;
			if(isset($add_field_tag))
			{
                        	$url['add_field'] = $add_field_tag . $key_list;
                        	$url['edit_layout'] =  $add_field_tag .  $key_list;
			}
			if(isset($generate_url))
			{
                        	$url['generate'] = $generate_url . $key_list;
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
	
		$paging_text = paginate_links( array(
                                        'total' => ceil($list_values['count']/10),
                                        'current' => $page,
                                        'base' => $base .'&%_%',
                                        'format' => $format . '=%#%',
                                        ) );
                $div_table .= "</tbody></table>";
        }
        $return_list .= $paging_text . "</div><br class=\"clear\"></div>";
        $return_list .= wpas_list_table($other_labels);
        $return_list .= $div_table . "</tbody></table></form>";
        return $return_list;
}
function wpas_breadcrumb($page)
{
        $home = admin_url('admin.php?page=wpas_app_list');
        echo '<div class="wpas">';
        wpas_branding_header();
        echo '<div id="was-container" class="container-fluid">';
        echo '<ul class="breadcrumb">
                <li id="first">
                <a href="'. $home . '"><i class="icon-home"></i> Home</a> <span class="divider">/</span>
                </li>';
        if($page == "add_new_app")
        {
                echo '<li id="second" class="active">Add New Application</li>
                        </li>
                        </ul>';
        }
        elseif($page == "edit_app")
        {
                echo '<li id="second" class="active">Edit Application</li>
                        </ul>';
        }
}
function wpas_form_desc()
{
	echo '<div id="title-bar"><div class="row-fluid">
		<div class="span3"><i class="icon-list-alt icon-large pull-left"></i><h4>Forms</h3></div>
	</div></div>';
	 echo '<div class="well"><p>Forms are used to collect infomation from the frontend of your application. There are public and private forms. Public forms are accessible to everyone. Private forms are accessible to the logged in users only.</p>
		<span class="label label-important">This feature is currently under development.</span></div>';
}
function wpas_pointer_desc()
{
	echo '<div id="title-bar"><div class="row-fluid">
		<div class="span3"><i class="icon-map-marker icon-large pull-left"></i><h4>Pointers</h3></div>
	</div></div>';
	 echo '<div class="well"><p>Pointers are used to create app tours to educate your users. There are public and private tours. Public tours are accessible to everyone. Private tours are accessible to the logged in users only.</p>
		<span class="label label-important">This feature is currently under development.</span></div>';
}


?>
