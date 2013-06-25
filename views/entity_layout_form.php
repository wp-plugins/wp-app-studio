<?php
function wpas_entity_layout_form()
{
?>
<div class="modal hide" id="myModal">
  <div class="modal-header">
	<button id="edit-close" type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3>Enter a new title</h3>
  </div>
  <div class="modal-body" style="clear:both">
<form id="layout-form"><fieldset><table><tr><td><label for="grp-title">Title:</label></td><td><input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all" /></td></tr></table></fieldset> </form>
  </div>
  <div class="modal-footer">
<button id="edit-cancel" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button> <button id="save-layout-title" class="btn btn-primary">Save</button>
  </div>
</div>
<div class="modal hide" id="errorModal">
  <div class="modal-header">
	<button id="error-close" type="button" class="close" data-dismiss="errorModal" aria-hidden="true">x</button>
    <h3><i class="icon-flag icon-red"></i>Error</h3>
  </div>
  <div class="modal-body" style="clear:both">All attributes must be assigned to at least one panel.
  </div>
  <div class="modal-footer">
<button id="save-layout-modal" class="btn btn-primary">Save</button>
  </div>
</div>
<div class="modal hide" id="errorModal1">
  <div class="modal-header">
	<button id="error-close" type="button" class="close" data-dismiss="errorModal1" aria-hidden="true">x</button>
    <h3><i class="icon-flag icon-red"></i>Error</h3>
  </div>
  <div class="modal-body" style="clear:both"> All panels must have at least one attribute.
  </div>
  <div class="modal-footer">
<button id="error-ok" data-dismiss="errorModal1" aria-hidden="true" class="btn btn-primary">OK</button>
  </div>
</div>
<div class="modal hide" id="errorModal2">
  <div class="modal-header">
	<button id="error-close" type="button" class="close" data-dismiss="errorModal2" aria-hidden="true">x</button>
    <h3><i class="icon-flag icon-red"></i>Error</h3>
  </div>
  <div class="modal-body" style="clear:both">This action will reset custom layout for this entity.
  </div>
  <div class="modal-footer">
<button id="error-ok" data-dismiss="errorModal2" aria-hidden="true" class="btn btn-primary">OK</button>
  </div>
</div>
<div class="row-fluid"><div id="layout-alert" class="span12"></div></div>
<div class="row-fluid">
<div class="layout-bin span3 pull-left" data-spy="affix" data-offset-top="50">
</div>
<div id="layout-ctr" class="ui-droppable ui-sortable  span9 pull-right">
<div class="dragme">DRAG AND DROP</div>
</div>
</div>
<div class="row-fluid">
<div id="layout-frm-btn" class="control-group span12">
<button id="cancel" class="btn  btn-danger layout-buttons" name="cancel" type="button"><i class="icon-ban-circle"></i>Cancel</button>
<button id="save-layout" class="btn  btn-primary pull-right layout-buttons" type="submit" name="Save"><i class="icon-save"></i>
Save
</button>
</div>
</div>
<?php
}
function wpas_entity_container($layout)
{
$grp_count = 0;
$tab_count = 0;
$acc_count = 0;
$layout_html = "";
if(!is_array($layout))
{
$layout_html ="<div class=\"dragme\">DRAG AND DROP</div>";
}
else
{
foreach($layout as $mylayout)
{
$grp_count++;
if(isset($mylayout['tabs']) && is_array($mylayout['tabs']))
{
$layout_html .= "<div id=\"tabgrp".$grp_count . "\" class=\"tabgrp-ctr ui-sortable\">
	<div id=\"tabgrp" . $grp_count . "-row\" class=\"row-fluid\">
	<div id=\"tabgrp" . $grp_count . "-title\" class=\"tabgrp-title layout-title span11 pull-left\">" . esc_html($mylayout['gr_title']) . "</div>
	<div class=\"pull-right layout-edit-icons\"><a class=\"edit\"><i class=\"icon-edit\"></i></a>
	<a class=\"delete\"><i class=\"icon-trash\"></i></a></div>
	</div><div class=\"multitab-ctr ui-droppable ui-sortable\">";
}
elseif(is_array($mylayout['accs']))
{
$layout_html .= "<div id=\"accgrp".$grp_count . "\" class=\"accgrp-ctr ui-sortable\">
        <div id=\"accgrp" . $grp_count . "-row\" class=\"row-fluid\">
        <div id=\"accgrp" . $grp_count . "-title\" class=\"accgrp-title layout-title span11 pull-left\">" . esc_html($mylayout['gr_title']) . "</div>
        <div class=\"pull-right layout-edit-icons\"><a class=\"edit\"><i class=\"icon-edit\"></i></a>
        <a class=\"delete\"><i class=\"icon-trash\"></i></a></div>
        </div><div class=\"multiacc-ctr ui-droppable ui-sortable\">";
}

if(isset($mylayout['tabs']))
{
	foreach($mylayout['tabs'] as $mytab)
	{
		$tab_count++;
		$layout_html .= "<div id=\"tab-ctr" . $tab_count . "\" class=\"tab-ctr\">
				<div id=\"tab-ctr". $tab_count . "-row\" class=\"row-fluid\">
				<div id=\"tab-ctr" . $tab_count . "-title\" class=\"tabctr-title layout-title span10 pull-left\">" . $mytab['tab_title'] . "</div>
				<div class=\"pull-right layout-edit-icons\"><a class=\"edit\"><i class=\"icon-edit\"></i></a>
				<a class=\"delete\"><i class=\"icon-trash\"></i></a></div></div>
				<div id=\"multiattr-ctr\" class=\"multiattr-ctr ui-droppable\">";
		if($mytab['attr'])
		{
			$attrs = explode(",",$mytab['attr']);
			foreach($attrs as $myattr)
			{
				$layout_html .= "<div class=\"tabattr ui-draggable\" style=\"position: relative; left: 0px; top: 0px;\">" . esc_html($myattr) . 
					"<div class=\"pull-right layout-edit-icons\"><a class=\"delete\"><i class=\"icon-trash\"></i></a></div></div>";
			}
		}
		$layout_html .="</div></div>";
	}
}
if(isset($mylayout['accs']))
{
	foreach($mylayout['accs'] as $myacc)
	{
	$acc_count++;
	$layout_html .= "<div id=\"acc-ctr" . $acc_count . "\" class=\"acc-ctr\">
			<div id=\"acc-ctr". $acc_count . "-row\" class=\"row-fluid\">
			<div id=\"acc-ctr" . $acc_count . "-title\" class=\"accctr-title layout-title span10 pull-left\">" . esc_html($myacc['acc_title']) . "</div>
			<div class=\"pull-right layout-edit-icons\"><a class=\"edit\"><i class=\"icon-edit\"></i></a>
			<a class=\"delete\"><i class=\"icon-trash\"></i></a></div></div>
			<div id=\"multiattr-ctr\" class=\"multiattr-ctr ui-droppable\">";
	if($myacc['attr'])
	{
	$attrs = explode(",",$myacc['attr']);
	foreach($attrs as $myattr)
	{
	$layout_html .= "<div class=\"tabattr ui-draggable\" style=\"position: relative; left: 0px; top: 0px;\">" . esc_html($myattr) . 
			"<div class=\"pull-right layout-edit-icons\"><a class=\"delete\"><i class=\"icon-trash\"></i></a></div></div>";
	}
	}
	$layout_html .="</div></div>";
	}
}

$layout_html .="</div></div>";
}
}

return $layout_html;



}
?>
