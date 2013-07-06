<?php
    function wpas_add_relationship_form($app_id)
    {
        ?>
<script type="text/javascript">
jQuery(document).ready(function() {
        jQuery('#rel-connected-display').click(function() {
		if(jQuery(this).attr('checked'))
		{
			jQuery('#rel-connected-div').show();
			if(jQuery('#rel-type').val() == 'one-to-many')
			{
				jQuery('#rel-connected-show-attributes-div').hide();
			}
			else
			{
				jQuery('#rel-connected-show-attributes-div').show();
			}
		}
		else
		{
			jQuery('#rel-connected-div').hide();
		}
        });
        jQuery('#rel-related-display').click(function() {
		if(jQuery(this).attr('checked'))
		{
			jQuery('#rel-related-div').show();
		}
		else
		{
			jQuery('#rel-related-div').hide();
		}
        });
        jQuery('#rel-type').click(function() {
		if(jQuery(this).val() == 'many-to-many')
                {
                        jQuery('#rel-related-div-group').show();
			if(jQuery('#rel-connected-display').attr('checked'))
			{
				jQuery('#rel-connected-show-attributes-div').show();
			}
                }
                else
                {
                        jQuery('#rel-related-div-group').hide();
                        jQuery('#rel-related-div').hide();
			if(jQuery('#rel-connected-display').attr('checked'))
			{
				jQuery('#rel-connected-show-attributes-div').hide();
			}
                }
	});
});
</script>
        <form action="" method="post" id="relationship-form" class="form-horizontal">
        <input type="hidden" value="" name="rel" id="rel">
        <fieldset>
        <div class="well">
        <div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="Relationships are connections between entities. You can create one-to-many(1-M), many-to-many(M-M) relationships. Each relationship may have one to many attributes."> HELP</a></div></div>
        <div class="control-group row-fluid">
        <label class="control-label span3">From Entity Name</label>
        <div class="controls span9">
        <select id="rel-from-name" name="rel-from-name">
        <option value="">Please select</option>
        <?php
            echo wpas_entity_types($app_id,'relationship');
            ?>
        </select>
        <a href="#" title="FROM entity is the related entity in a relationship. Many entity instances from the related entity can reference any one entity instance from the primary entity." style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">From Entity Title</label>
        <div class="controls span9">
        <input name="rel-from-title" id="rel-from-title" type="text" placeholder="e.g. Manager (To Entity)">
        <a href="#" title="Default is set to To Entity label." style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">To Entity Name</label>
        <div class="controls span9">
        <select id="rel-to-name" name="rel-to-name">
        <option value="">Please select</option>
        <?php
            echo wpas_entity_types($app_id,'relationship');
            ?>
        </select>
        <a href="#" title="TO entity is the primary entity in a relationship. Any one entity instance from the primary entity can be referenced by many entity instances from the related entity. " style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">To Entity Title</label>
        <div class="controls span9">
        <input name="rel-to-title" id="rel-to-title" type="text" placeholder="e.g. Employee (From Entity)">
        <a href="#" title="Default is set to From Entity label." style="cursor: help;"><i class="icon-info-sign"></i></a></div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">Type</label>
        <div class="controls span9">
        <select name="rel-type" id="rel-type">
        <option selected="selected" value="one-to-many">One-to-Many</option>
        <option value="many-to-many">Many-to-Many</option>

        </select><a href="#" title="Pick the type of relationship between TO and FROM entity. In a one-to-many relationship, each record in the related to entity can be related to many records in the relating entity. For example, in a customer to an invoice relationship each customer can have many invoices but each invoice can only be generated for a single customer. In a many-to-many relationship, one or more records in a entity can be related to 0, 1 or many records in another entity. For example, Each customer can order many products and each product can be ordered by many customers.  " style="cursor: help;"> <i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">Box Display</label>
        <div class="controls span9">
        <select name="rel-box-type" id="rel-box-type">
        <option selected="selected" value="from">Display in FROM entity</option>
        <option value="to">Display in TO entity</option>
        <option value="any">Display in ANY entity</option>
        <option value="false">Do not display</option>
        </select>
        <a href="#" title="Pick the location of relationship metabox. The metabox will be displayed in the editor screen of the selected entity or both." style="cursor: help;">
        <i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3" ></label>
        <div class="controls span9">
            <label class="checkbox">Main Column Display 
            <input name="rel-display_in_main" id="rel-display_in_main" type="checkbox" value="False"/>
            <a href="#" style="cursor: help;" title="Display the relationship box in the main column of the entity editor instead of the default side column. This will allocate more space when defining connections. The relationships with attributes are by default allocated to the main column."><i class="icon-info-sign"></i></a></label>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">List Column Display</label>
        <div class="controls span9">
        <select name="rel-display-type" id="rel-display-type">
        <option selected="selected" value="from">Display in FROM entity list</option>
        <option value="to">Display in TO entity list</option>
        <option value="any">Display in ANY entity record list</option>
        <option value="false">Do not display</option>
        </select><a href="#" title="You can display related entity objects in the entity list of from-entity, to-entity or choose not to display at all. List Column Display sets where relationship column will be displayed when viewing lists of an entity records." style="cursor: help;">
        <i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3"></label>
        <div class="controls span9">
        <label class="checkbox">Connected Frontend Display
        <input name="rel-connected-display" id="rel-connected-display" type="checkbox" value="False"/>
        <a href="#" style="cursor: help;" title="When checked, it displays the connected relationship data on the frontend. For example; you have a relationship between products and orders. One product may be ordered multiple times. On a product page, connected option will show the order records that the same product is ordered."><i class="icon-info-sign"></i></a></label>
        </div>
        </div>
	<div id="rel-connected-div">
        <div class="control-group row-fluid">
        <label class="control-label span3">Connected Display From Title</label>
        <div class="controls span9">
        <input name="rel-connected-display-from-title" id="rel-connected-display-from-title" type="text" placeholder="e.g. Connected Orders (To Entity)">
        <a href="#" title="Sets the connected relationship title which will be displayed on the from entity frontend" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">Connected Display To Title</label>
        <div class="controls span9">
        <input name="rel-connected-display-to-title" id="rel-connected-display-to-title" type="text" placeholder="e.g. Connected Products (From Entity)">
        <a href="#" title="Sets the connected relationship title which will be displayed on the to entity frontend" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3" >Connected Display Type</label>
        <div class="controls span9">
        <select name="rel-connected-display-type" id="rel-connected-display-type" class="input-medium">
        <option selected="selected" value="ul">Unordered List</option>
        <option value="ol">Ordered List</option>
        <option value="inline">Comma Seperated List</option></select>
        <a href="#" style="cursor: help;" title="Sets how the connected relationship data that will be displayed on the frontend.">
        <i class="icon-info-sign"></i></a> (default: Unordered List)
        </div>
        </div>
        <div class="control-group row-fluid" id="rel-connected-show-attributes-div">
        <label class="control-label span3"></label>
        <div class="controls span9">
        <label class="checkbox">Display Connected Attributes
        <input name="rel-connected-show-attributes" id="rel-connected-show-attributes" type="checkbox" value="False"/>
        <a href="#" style="cursor: help;" title="When checked, it displays the connected relationship attribute data on the frontend."><i class="icon-info-sign"></i></a></label>
        </div>
        </div>
	
	</div>
	<div id="rel-related-div-group">
        <div class="control-group row-fluid">
        <label class="control-label span3"></label>
        <div class="controls span9">
        <label class="checkbox">Related Frontend Display
        <input name="rel-related-display" id="rel-related-display" type="checkbox" value="False"/>
        <a href="#" style="cursor: help;" title="When checked, it displays the related relationship data on the frontend. For example; you have a many to many relationship between products and orders. One product may be ordered multiple times. One order may include multiple products. On a product page, related option will show the products that are ordered in the same connected order."><i class="icon-info-sign"></i></a></label>
        </div>
        </div>
        </div>
	<div id="rel-related-div">
        <div class="control-group row-fluid">
        <label class="control-label span3">Related Display From Title</label>
        <div class="controls span9">
        <input name="rel-related-display-from-title" id="rel-related-display-from-title" type="text" placeholder="e.g. Related Products (From Entity)">
        <a href="#" title="Sets the related relationship title which will be displayed on the from entity frontend" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">Related Display To Title</label>
        <div class="controls span9">
        <input name="rel-related-display-to-title" id="rel-related-display-to-title" type="text" placeholder="e.g. Related Orders (To Entity)">
        <a href="#" title="Sets the related relationship title which will be displayed on the to entity frontend" style="cursor: help;"><i class="icon-info-sign"></i></a>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3" >Related Display Type</label>
        <div class="controls span9">
        <select name="rel-related-display-type" id="rel-related-display-type" class="input-medium">
        <option selected="selected" value="ul">Unordered List</option>
        <option value="ol">Ordered List</option>
        <option value="inline">Comma Seperated List</option></select>
        <a href="#" style="cursor: help;" title="Sets how the related relationship data that will be displayed on the frontend.">
        <i class="icon-info-sign"></i></a> (default: Unordered List)
        </div>
        </div>
	</div>
        <div class="control-group row-fluid">
        <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
        <button class="btn  btn-primary pull-right layout-buttons" id="save-relationship" type="submit" value="Save"><i class="icon-save"></i>Save</button>
        </div>
        </fieldset>
        </form>

<?php
    }
    function wpas_view_relationship($rel,$rel_id)
    {
        return '<div class="well form-horizontal">
        <div class="row-fluid">
        <button class="btn  btn-danger pull-left" id="cancel" name="cancel" type="button">
        <i class="icon-off"></i>Close</button>
        <div class="relationship">
        <button class="btn  btn-primary pull-right" id="edit-relationship" name="Edit" type="submit" href="#' . esc_attr($rel_id) . '">
        <i class="icon-edit"></i>Edit</button>
        </div>
        </div>
        <fieldset>
        <div class="control-group row-fluid">
        <label class="control-label span3">From Entity Name </label>
        <div class="controls span9"><span id="rel-from-name" class="input-xlarge uneditable-input">' . esc_html($rel['rel-from-name']) . '</span>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">To Entity Name</label>
        <div class="controls span9"><span id="rel-to-name" class="input-xlarge uneditable-input">' . esc_html($rel['rel-to-name']) . '</span>
        </div>
        </div>
        <div class="control-group row-fluid">
        <label class="control-label span3">Relationship Type</label>
        <div class="controls span9"><span id="rel-type" class="input-xlarge uneditable-input">' . esc_html($rel['rel-type']) . '</span>
        </div>
        </div>
        </fieldset>
        </div>';
    }
    
    ?>
