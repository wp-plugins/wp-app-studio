<?php
    function wpas_add_relationship_form($app_id)
    {
        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
        $('#rel-connected-display').click(function() {
		if($(this).attr('checked'))
		{
			$('#reltabs-2-li').show();
			if($('#rel-type').val() == 'one-to-many')
			{
				$('#rel-connected-show-attributes-div').hide();
			}
			else
			{
				$('#rel-connected-show-attributes-div').show();
			}
			app_id = $('input#app').val();
			comp_id_from = $('#rel-from-name').val();
			comp_id_to = $('#rel-to-name').val();
			rel_id = $('input#rel').val();
			$.fn.getAddons('rel-con_from_layout',app_id,'rel-con-rel',comp_id_to,'connected',[],rel_id);
			$.fn.getAddons('rel-con_to_layout',app_id,'rel-con-rel',comp_id_from,'connected',[],rel_id);
		}
		else
		{
			$('#reltabs-2-li').hide();
		}
        });
        $('#rel-related-display').click(function() {
		if($(this).attr('checked'))
		{
			$('#reltabs-3-li').show();
			app_id = $('input#app').val();
			comp_id_from = $('#rel-from-name').val();
			comp_id_to = $('#rel-to-name').val();
			rel_id = $('input#rel').val();
			$.fn.getAddons('rel-rel_from_layout',app_id,'rel-con-rel',comp_id_from,'related',[],rel_id);
			$.fn.getAddons('rel-rel_to_layout',app_id,'rel-con-rel',comp_id_to,'related',[],rel_id);
		}
		else
		{
			$('#reltabs-3-li').hide();
		}
        });
        $('#rel-type').click(function() {
		if($(this).val() == 'many-to-many')
                {
			$('#rel-related-display-div').show();
			if($('#rel-connected-display').attr('checked'))
			{
				$('#rel-connected-show-attributes-div').show();
			}
                }
                else
                {
			$('#rel-related-display-div').hide();
			if($('#rel-connected-display').attr('checked'))
			{
				$('#rel-connected-show-attributes-div').hide();
			}
                }
	});
        $('#rel-from-name,#rel-to-name').click(function() {
		if($('#rel-from-name').val() == 'user' || $('#rel-to-name').val() == 'user')
		{
			$('#rel-limit_user_relationship_role_div').show();
		}
		else
		{
			$('#rel-limit_user_relationship_role_div').hide();
		}
	});

});
</script>
        <form action="" method="post" id="relationship-form" class="form-horizontal">
        <input type="hidden" value="" name="rel" id="rel">
        <fieldset>
        <div class="well">
        <div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Relationships are connections between entities. You can create one-to-many(1-M), many-to-many(M-M) relationships. Each relationship may have one to many attributes.","wpas");?>"> <?php _e("HELP","wpas");?></a></div></div>
        <div class="control-group row-fluid">
        <label class="control-label req span3"><?php _e("Name","wpas");?></label>
        <div class="controls span9">
        <input name="rel-name" id="rel-name" type="text" placeholder="<?php _e("orders_products","wpas");?>">
        <a href="#" title="<?php _e("Relationship name should be in slug form (must not contain capital letters or spaces) and not more than 32 characters long. Previously used relationship names are not allowed. Underscores are allowed.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label req span3"><?php _e("From Entity Name","wpas");?></label>
        <div class="controls span9">
        <select id="rel-from-name" name="rel-from-name">
        <option value=""><?php _e("Please select","wpas");?></option>
        <?php
            echo wpas_entity_types($app_id,'relationship');
            ?>
        </select>
        <a href="#" title="<?php _e("FROM entity is the related entity in a relationship. Many entity instances from the related entity can reference any one entity instance from the primary entity.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label req span3"><?php _e("To Entity Name","wpas");?></label>
        <div class="controls span9">
        <select id="rel-to-name" name="rel-to-name">
        <option value=""><?php _e("Please select","wpas");?></option>
        <?php
            echo wpas_entity_types($app_id,'relationship');
            ?>
        </select>
        <a href="#" title="<?php _e("TO entity is the primary entity in a relationship. Any one entity instance from the primary entity can be referenced by many entity instances from the related entity.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
	<div class="control-group row-fluid" id="rel-limit_user_relationship_role_div" style="display: none;">
	<label class="control-label span3"><?php _e("Limit By Role","wpas"); ?></label>
	<div class="controls span9">
	<select name="rel-limit_user_relationship_role" id="rel-limit_user_relationship_role">
	<option selected="selected" value="false"><?php _e("Do not limit","wpas"); ?></option>
	<option value="editor"><?php _e("Only Editors can be related","wpas"); ?></option>
	<option value="author"><?php _e("Only Author can be related","wpas"); ?></option>
	<option value="contributor"><?php _e("Only Contributor can be related","wpas"); ?></option>
	<option value="subscriber"><?php _e("Only Subscriber can be related","wpas"); ?></option>
	<option value="administrator"><?php _e("Only Administrator can be related","wpas"); ?></option>
	</select>
	<a href="#" title="<?php _e("Limits the relationship to the group of users who belong to the selected role. Builtin roles: Super Admin - Someone with access to the blog network administration features controlling the entire network (See Create a Network). Administrator - Somebody who has access to all the administration features. Editor - Somebody who can publish and manage posts and pages as well as manage other users's posts, etc. Author - Somebody who can publish and manage their own posts. Contributor - Somebody who can write and manage their posts but not publish them. Subscriber - Somebody who can only manage their profile. If you predefined custom roles, you can select them as well. You can create multiple user relationships with the same entity and limit them by different roles. User to User relationships are not allowed.","wpas"); ?>" style="cursor: help;">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("Description","wpas");?></label>
	<div class="controls span9">
	<textarea class="input-xlarge" id="rel-desc" name="rel-desc"></textarea>
	<a href="#" style="cursor: help;" title="<?php _e("A short descriptive summary of what the relationship is.","wpas");?>">
	<i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Required for Submit","wpas");?>
			<input name="rel-required" id="rel-required" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the relationship required when entering data using this relationship so it can not be blank.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
	<div class="control-group">
    <label class="control-label span3"></label>
	<div class="controls span9">
			<label class="checkbox"><?php _e("Required for Search","wpas");?>
			<input name="rel-srequired" id="rel-srequired" type="checkbox" value="1"/>
			<a href="#" style="cursor: help;" title="<?php _e("Makes the relationship required when searching data using this relationship so it can not be blank.","wpas");?>">
			<i class="icon-info-sign"></i></a>
			</label>
	</div>
	</div>
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("Type","wpas");?></label>
        <div class="controls span9">
        <select name="rel-type" id="rel-type">
        <option selected="selected" value="one-to-many"><?php _e("One-to-Many","wpas");?></option>
        <option value="many-to-many"><?php _e("Many-to-Many","wpas");?></option>

        </select><a href="#" title="<?php _e("Pick the type of relationship between TO and FROM entity. In a one-to-many relationship, each record in the related to entity can be related to many records in the relating entity. For example, in a customer to an invoice relationship each customer can have many invoices but each invoice can only be generated for a single customer. In a many-to-many relationship, one or more records in a entity can be related to 0, 1 or many records in another entity. For example, Each customer can order many products and each product can be ordered by many customers.","wpas");?>" style="cursor: help;"> <i class="icon-info-sign"></i></a>
        </div>
        </div>
       <div class="control-group row-fluid">
        <label class="control-label span3"></label>
        <div class="controls span9">
        <label class="checkbox"><?php _e("Display Connected in Frontend","wpas");?>
        <input name="rel-connected-display" id="rel-connected-display" type="checkbox" value="1"/>
        <a href="#" style="cursor: help;" title="<?php _e("When checked, it displays the connected relationship data on the frontend. For example; you have a relationship between products and orders. One product may be ordered multiple times. On a product page, connected option will show the order records that the same product is ordered.","wpas");?>"><i class="icon-info-sign"></i></a></label>
        </div>
        </div>
        <div class="control-group row-fluid" id="rel-related-display-div">
        <label class="control-label span3"></label>
        <div class="controls span9">
        <label class="checkbox"><?php _e("Display Related in Frontend","wpas");?>
        <input name="rel-related-display" id="rel-related-display" type="checkbox" value="1"/>
        <a href="#" style="cursor: help;" title="<?php _e("When checked, it displays the related relationship data on the frontend. For example; you have a many to many relationship between products and orders. One product may be ordered multiple times. One order may include multiple products. On a product page, related option will show the products that are ordered in the same connected order. This option can be used only in Many-to-Many relationships.","wpas");?>"><i class="icon-info-sign"></i></a>
        </label>
        </div>
        </div>
	</div> <!-- well end -->
        <ul id="relTab" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#reltabs-1"><?php _e("Display Options","wpas");?></a></li>
        <li id="reltabs-2-li"><a data-toggle="tab" href="#reltabs-2"><?php _e("Connected","wpas");?></a></li>
        <li id="reltabs-3-li"><a data-toggle="tab" href="#reltabs-3"><?php _e("Related","wpas");?></a></li>
        </ul>
<div id="relTabContent" class="tab-content">
<div id="reltabs-1" class="tab-pane fade in active">
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("From Entity Title","wpas");?></label>
        <div class="controls span9">
        <input name="rel-from-title" id="rel-from-title" type="text" placeholder="<?php _e("e.g. Orders (To Entity)","wpas");?>">
        <a href="#" title="<?php _e("Default is set to To Entity label.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("To Entity Title","wpas");?></label>
        <div class="controls span9">
        <input name="rel-to-title" id="rel-to-title" type="text" placeholder="<?php _e("e.g. Products (From Entity)","wpas");?>">
        <a href="#" title="<?php _e("Default is set to From Entity label.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("Box Display","wpas");?></label>
        <div class="controls span9">
        <select name="rel-box-type" id="rel-box-type">
        <option selected="selected" value="from"><?php _e("Display in FROM entity","wpas");?></option>
        <option value="to"><?php _e("Display in TO entity","wpas");?></option>
        <option value="any"><?php _e("Display in ANY entity","wpas");?></option>
        <option value="false"><?php _e("Do not display","wpas");?></option>
        </select>
        <a href="#" title="<?php _e("Pick the location of relationship metabox. The metabox will be displayed in the editor screen of the selected entity or both.","wpas");?>" style="cursor: help;">
        <i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3" ></label>
        <div class="controls span9">
            <label class="checkbox"><?php _e("Main Column Display","wpas");?>
            <input name="rel-display_in_main" id="rel-display_in_main" type="checkbox" value="1"/>
            <a href="#" style="cursor: help;" title="<?php _e("When set, Wp App Studio displays the relationship box in the main column of the entity editor instead of the side column(default). This will allocate more space when defining connections. The relationships with attributes are by default allocated to the main column.","wpas");?>"><i class="icon-info-sign"></i></a>
            </label>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("List Column Display","wpas");?></label>
        <div class="controls span9">
        <select name="rel-display-type" id="rel-display-type">
        <option selected="selected" value="from"><?php _e("Display in FROM entity list","wpas");?></option>
        <option value="to"><?php _e("Display in TO entity list","wpas");?></option>
        <option value="any"><?php _e("Display in ANY entity record list","wpas");?></option>
        <option value="false"><?php _e("Do not display","wpas");?></option>
        </select><a href="#" title="<?php _e("You can display related entity objects in the entity list of from-entity, to-entity or choose not to display at all. List Column Display sets where relationship column will be displayed when viewing lists of an entity records.","wpas");?>" style="cursor: help;">
        <i class="icon-info-sign"></i></a>
        </div>
        </div>
</div><!-- end of tab1 -->
<div id="reltabs-2" class="tab-pane fade in">
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("From Entity Title","wpas");?></label>
        <div class="controls span9">
        <input name="rel-connected-display-from-title" id="rel-connected-display-from-title" type="text" placeholder="<?php _e("e.g. Connected Orders (To Entity)","wpas");?>">
        <a href="#" title="<?php _e("Sets the connected relationship title on the 'from' entity frontend.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("To Entity Title","wpas");?></label>
        <div class="controls span9">
        <input name="rel-connected-display-to-title" id="rel-connected-display-to-title" type="text" placeholder="<?php _e("e.g. Connected Products (From Entity)","wpas");?>">
        <a href="#" title="<?php _e("Sets the connected relationship title on the 'to' entity frontend.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3" ><?php _e("Display Type","wpas");?></label>
        <div class="controls span9">
        <select name="rel-connected-display-type" id="rel-connected-display-type" class="input-medium">
        <option selected="selected" value="ul"><?php _e("Unordered List","wpas");?></option>
        <option value="ol"><?php _e("Ordered List","wpas");?></option>
        <option value="inline"><?php _e("Comma Seperated List","wpas");?></option>
        <option value="std"><?php _e("Standard","wpas");?></option></select>
        <a href="#" style="cursor: help;" title="<?php _e("Sets how the connected relationship data will be displayed on the frontend. Standard ,Ordered List, Comma Seperated List, and Unordered List are the options. Standard option creates a div which wraps all relationship data.","wpas");?>">
        <i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid" id="rel-connected-show-attributes-div">
        <label class="control-label span3"></label>
        <div class="controls span9">
        <label class="checkbox"><?php _e("Display Attributes","wpas");?>
        <input name="rel-connected-show-attributes" id="rel-connected-show-attributes" type="checkbox" value="1"/>
        <a href="#" style="cursor: help;" title="<?php _e("When checked, it displays the connected relationship attribute data on the frontend.","wpas");?>"><i class="icon-info-sign"></i></a></label>
        </div>
        </div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("From Entity Layout","wpas"); ?></label>
	<div class="controls span9">
<?php
	display_tinymce('rel-con_from_layout','',1,1);
?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the layout of a single -from entity- record of your view. You can also edit the source code, add entity attributes, taxonomies.","wpas"); ?>"><i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("To Entity Layout","wpas"); ?></label>
	<div class="controls span9">
<?php
	display_tinymce('rel-con_to_layout','',1,1);
?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the layout of a single -to entity- record of your view. You can also edit the source code, add entity attributes, taxonomies.","wpas"); ?>"><i class="icon-info-sign"></i></a>
	</div>
	</div>
</div><!-- end of tab2 -->
<div id="reltabs-3" class="tab-pane fade in">
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("From Entity Title","wpas");?></label>
        <div class="controls span9">
        <input name="rel-related-display-from-title" id="rel-related-display-from-title" type="text" placeholder="<?php _e("e.g. Related Products (From Entity)","wpas");?>">
        <a href="#" title="<?php _e("Sets the related relationship title on the 'from' entity frontend. This option can be used only in Many-to-Many relationships.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3"><?php _e("To Entity Title","wpas");?></label>
        <div class="controls span9">
        <input name="rel-related-display-to-title" id="rel-related-display-to-title" type="text" placeholder="<?php _e("e.g. Related Orders (To Entity)","wpas");?>">
        <a href="#" title="<?php _e("Sets the related relationship title on the 'to' entity frontend. This option can be used only in Many-to-Many relationships.","wpas");?>" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3" ><?php _e("Display Type","wpas");?></label>
        <div class="controls span9">
        <select name="rel-related-display-type" id="rel-related-display-type" class="input-medium">
        <option selected="selected" value="ul"><?php _e("Unordered List","wpas");?></option>
        <option value="ol"><?php _e("Ordered List","wpas");?></option>
        <option value="inline"><?php _e("Comma Seperated List","wpas");?></option>
        <option value="std"><?php _e("Standard","wpas");?></option></select>
        <a href="#" style="cursor: help;" title="<?php _e("Sets how the related relationship data will be displayed on the frontend. Standard ,Ordered List, Comma Seperated List, and Unordered List are the options. Standard option creates a div which wraps all relationship data. This option can be used only in Many-to-Many relationships.","wpas");?>">
        <i class="icon-info-sign"></i></a>
        </div>
        </div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("From Entity Layout","wpas"); ?></label>
	<div class="controls span9">
<?php
	display_tinymce('rel-rel_from_layout','',1,1);
?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the layout of a single record of your view. You can also edit the source code, add entity attributes, taxonomies.","wpas"); ?>"><i class="icon-info-sign"></i></a>
	</div>
	</div>
	<div class="control-group row-fluid">
	<label class="control-label span3"><?php _e("To Entity Layout","wpas"); ?></label>
	<div class="controls span9">
<?php
	display_tinymce('rel-rel_to_layout','',1,1);
?>
	<a href="#" style="cursor: help;" title="<?php _e("Sets the layout of a single record of your view. You can also edit the source code, add entity attributes, taxonomies.","wpas"); ?>"><i class="icon-info-sign"></i></a>
	</div>
	</div>
</div><!-- end of tab3 -->
</div> <!-- end of div content -->
        <div class="control-group row-fluid">
        <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i><?php _e("Cancel","wpas");?></button>
        <button class="btn  btn-primary pull-right layout-buttons" id="save-relationship" type="submit" value="Save"><i class="icon-save"></i><?php _e("Save","wpas");?></button>
        </div>
        </fieldset>
        </form>

<?php
    }
    function wpas_view_relationship($rel,$rel_id,$app)
    {
	if($rel['rel-from-name'] == 'user')
	{
		$from_ent_name = "User";
	}
	else
	{
		$from_ent_name = $app['entity'][$rel['rel-from-name']]['ent-label'];
	}
	if($rel['rel-to-name'] == 'user')
	{
		$to_ent_name = "User";
	}
	else
	{
		$to_ent_name = $app['entity'][$rel['rel-to-name']]['ent-label'];
	}
        return '<div class="well form-horizontal">
        <div class="row-fluid">
        <button class="btn  btn-danger pull-left" id="cancel" name="cancel" type="button">
        <i class="icon-off"></i>' . __("Close","wpas") . '</button>
        <div class="relationship">
        <button class="btn  btn-primary pull-right" id="edit-relationship" name="Edit" type="submit" href="#' . esc_attr($rel_id) . '">
        <i class="icon-edit"></i>' . __("Edit","wpas") . '</button>
        </div>
        </div>
        <fieldset>
        <div class="control-group row-fluid">
        <label class="control-label span3">' . __("From Entity Name ","wpas") . '</label>
        <div class="controls span9"><span id="rel-from-name" class="input-xlarge uneditable-input">' . esc_html($from_ent_name) . '</span>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">' . __("To Entity Name","wpas") . '</label>
        <div class="controls span9"><span id="rel-to-name" class="input-xlarge uneditable-input">' . esc_html($to_ent_name) . '</span>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">' . __("Relationship Type","wpas") . '</label>
        <div class="controls span9"><span id="rel-type" class="input-xlarge uneditable-input">' . esc_html($rel['rel-type']) . '</span>
        </div>
        </div>
        </fieldset>
        </div>';
    }
    
    ?>
