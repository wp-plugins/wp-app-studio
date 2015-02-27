<?php
function wpas_add_shortcode_form($app_id)
{
	?>
<script type="text/javascript">
jQuery(document).ready(function($) {
        $('#shc-view_type').click(function() {
		app_id = jQuery('input#app').val();
		var selected_type = $(this).find('option:selected').val();
		$(this).showShcTabs(selected_type,app_id,1);
	});
        $('#shc-attach').click(function() {
		shc_type = $('#shc-view_type').find('option:selected').val();
		app_id = $('input#app').val();
		ent_id = $(this).find('option:selected').val();
		if(shc_type == 'datagrid')
		{
			$.get(ajaxurl,{action:'wpas_get_table_cols',app_id:app_id,chart_ent:ent_id}, function(response){
				$('#shc-table_ids').html(response[0]);
			},'json');
			$('#shc-table_div').show();
		}
		else if($.inArray(shc_type,['single','archive','tax']) != -1 && ent_id != ''){
			$(this).showShcTags(app_id,ent_id,'tag-nocount');
		}
		else if(shc_type != 'chart' && ent_id != ''){
			$(this).showShcTags(app_id,ent_id,'tag-rel');
		}
	});
	$('#shc-sc_pagenav').click(function(){
		if($(this).attr('checked'))
		{
			$('#shc-pgn_class_div').show();
		}
		else {
			$('#shc-pgn_class_div').hide();
		}
		
	});
        $('#shc-attach_form').click(function() {
		app_id = $('input#app').val();
		comp_id = $(this).find('option:selected').val();
		$(this).showShcTags(app_id,comp_id,'tag-form');
	});
	$.fn.showShcTags = function (app_id,comp_id,type){
		$.get(ajaxurl,{action:'wpas_get_layout_tags',type:type,app_id:app_id,comp_id:comp_id}, function(response){
			$('#shc-layout-tags').html(response);
			$('#shc-layout-tags').show();
		});
	}
	$.fn.showShcTabs = function(selected_type,app_id,get_vals){
		$('#shcTab').show();
		$('#ShcTabContent').show();
		$('#shc-pgn_class_div').hide();
		switch (selected_type) {
			case 'std':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').show();
				$('#shctabs-2-li').show();
				if(get_vals == 1)
				{
					$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id}, function(response)
					{
						$('#add-shortcode-div #shc-attach').html(response);
						$('#shc-attach_div').show();
					});
				}
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
                        	$('#shc-setup_page_div').show();
				$('#shc-rmv_wpasbtn_div').show();
                        	$('#shc-app_dash_div').hide();
                        	$('#shc-layout_header_div').show();
                        	$('#shc-layout_footer_div').show();
                        	$('#shc-layout_div').show();
				$('#show-tags-div').show();
                        	$('#shc-sc_css_div').show();
				$('#shc-chart_div').hide();
				$('#shc-table_div').hide();
				break;
			case 'search':
				$('#shc-theme_type_div').hide();
				$('#shc-sc_pagenav_div').show();
				$('#shctabs-2-li').show();
				if(get_vals == 1)
				{
					$.get(ajaxurl,{action:'wpas_get_search_forms',app_id:app_id}, function(response)
					{
						$('#shc-attach_form').html(response);
						$('#shc-attach_form_div').show();
					});
				}
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_div').hide();
                        	$('#shc-setup_page_div').hide();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').hide();
                        	$('#shc-layout_header_div').show();
                        	$('#shc-layout_footer_div').show();
                        	$('#shc-layout_div').show();
				$('#show-tags-div').show();
                        	$('#shc-sc_css_div').show();
				$('#shc-chart_div').hide();
				$('#shc-table_div').hide();
				break;
			case 'single':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').hide();
				if(get_vals == 1)
				{
					$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,subtype:selected_type}, function(response)
					{
						$('#add-shortcode-div #shc-attach').html(response);
						$('#shc-attach_div').show();
					});
				}
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
				$('#shctabs-2-li').hide();
                        	$('#shc-setup_page_div').hide();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').hide();
                        	$('#shc-layout_header_div').hide();
                        	$('#shc-layout_footer_div').hide();
                        	$('#shc-layout_div').show();
				$('#show-tags-div').show();
                        	$('#shc-sc_css_div').show();
				$('#shc-chart_div').hide();
				$('#shc-table_div').hide();
				break;	
			case 'archive':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').hide();
				$('#shctabs-2-li').hide();
				if(get_vals == 1)
				{
					$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,subtype:selected_type}, function(response)
					{
						$('#add-shortcode-div #shc-attach').html(response);
						$('#shc-attach_div').show();
					});
				}
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
                        	$('#shc-setup_page_div').hide();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').hide();
                        	$('#shc-layout_header_div').hide();
                        	$('#shc-layout_footer_div').hide();
                        	$('#shc-layout_div').show();
				$('#show-tags-div').show();
                        	$('#shc-sc_css_div').show();
				$('#shc-chart_div').hide();
				$('#shc-table_div').hide();
				break;
			case 'tax':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').hide();
				$('#shctabs-2-li').hide();
				if(get_vals == 1)
				{
					$.get(ajaxurl,{action:'wpas_get_entities',type:'tax',app_id:app_id,subtype:selected_type}, function(response)
					{
						$('#add-shortcode-div #shc-attach_tax').html(response);
						$('#shc-attach_tax_div').show();
					});
				}
				$('#shc-attach_div').hide();
				$('#shc-attach_form_div').hide();
                        	$('#shc-setup_page_div').hide();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').hide();
                        	$('#shc-layout_header_div').hide();
                        	$('#shc-layout_footer_div').hide();
                        	$('#shc-layout_div').show();
				$('#show-tags-div').show();
                        	$('#shc-sc_css_div').show();
				$('#shc-chart_div').hide();
				$('#shc-table_div').hide();
				break;
			case 'chart':
				$('#shctabs-2-li').hide();
				if(get_vals == 1)
				{
					$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id}, function(response)
					{
						$('#add-shortcode-div #shc-attach').html(response);
						$('#shc-attach_div').show();
					});
				}
                        	$('#shc-setup_page_div').show();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').show();
				$('#shc-theme_type_div').hide();
				$('#shc-sc_pagenav_div').hide();
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
                        	$('#shc-layout_header_div').hide();
                        	$('#shc-layout_footer_div').hide();
                        	$('#shc-layout_div').hide();
				$('#shc-chart_div').show();
                        	$('#shc-sc_css_div').hide();
				$('#shc-table_div').hide();
				$(this).chartType('');
				break;
			case 'datagrid':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').show();
				$('#shctabs-2-li').show();
				if(get_vals == 1)
				{
					$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id}, function(response)
					{
						$('#add-shortcode-div #shc-attach').html(response);
						$('#shc-attach_div').show();
					});
				}
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
                        	$('#shc-setup_page_div').show();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').show();
                        	$('#shc-layout_header_div').hide();
                        	$('#shc-layout_footer_div').hide();
                        	$('#shc-layout_div').hide();
                        	$('#shc-sc_css_div').hide();
				$('#shc-chart_div').hide();
				$('#shc-font_awesome_div').hide();
				$('#shc-table_div').show();
				break;
			case 'integration':
				$('#shc-theme_type_div').show();
				$('#shc-sc_pagenav_div').hide();
				$('#shctabs-2-li').hide();
				$('#shc-attach_div').hide();
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_form_div').hide();
                        	$('#shc-setup_page_div').show();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').show();
                        	$('#shc-layout_header_div').hide();
                        	$('#shc-layout_footer_div').hide();
				$('#show-tags-div').hide();
                        	$('#shc-layout_div').show();
                        	$('#shc-sc_css_div').show();
				$('#shc-chart_div').hide();
				$('#shc-table_div').hide();
				break;
			default:
				$('#shcTab').hide();
                                $('#ShcTabContent').hide();
                                $('#shc-view_type').val('');
                                $('#add-shortcode-div input#app').val(app_id);
                                $('#shc-setup_page_title_div').hide();
                                $('#shc-app_dash_title_div').hide();
                                $('#shc-app_dash_loc_div').hide();
                                $('#shc-table_col').html(''); 

				$('#shc-theme_type_div').hide();
				$('#shc-sc_pagenav_div').hide();
				$('#shc-attach_form_div').hide();
				$('#shc-attach_tax_div').hide();
				$('#shc-attach_div').hide();
				$('#shctabs-2-li').show();
                        	$('#shc-setup_page_div').hide();
				$('#shc-rmv_wpasbtn_div').hide();
                        	$('#shc-app_dash_div').hide();
                        	$('#shc-layout_header_div').hide();
                        	$('#shc-layout_footer_div').hide();
                        	$('#shc-layout_div').hide();
				$('#show-tags-div').show();
				$('#shc-chart_div').hide();
				$('#shc-table_div').hide();
				break;
		}
	}
	$.fn.vhAxis = function(sel,app_id,chart_ent,chart_type,func,selected,fid){
		var type = sel;
		var show_id = '';
		if(fid == 'haxis')
		{
			show_id = '#shc-haxis';
		}
		else if(fid == 'vaxis')
		{
			show_id = '#shc-vaxis';
			if(sel == 'attribute' && func == 'count')
			{
				type = 'attribute';
			}
			else if(sel == 'attribute')
			{
				type = 'num_attribute';
			}	
		}
		if(sel == 'taxonomy')
		{
			type  = 'tax';
		}
		else if(sel == 'relationship')
		{
			type  = 'form_dependents';
		}
		switch (sel){
			case 'taxonomy':	
				$.get(ajaxurl,{action:'wpas_get_entities',type:type,app_id:app_id,chart_ent:chart_ent,values:selected}, function(response){
					$(show_id+ '_id').html(response);
				});
				break;
			case 'attribute':
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:type,app_id:app_id,ent_id:chart_ent,value:selected}, function(response){
					$(show_id+ '_id').html(response['pre']+response['list']);
				}, 'json');
				break;
			case 'date':
				$.get(ajaxurl,{action:'wpas_get_ent_fields',type:type,app_id:app_id,ent_id:chart_ent,value:selected}, function(response){
					$(show_id+ '_id').html(response['pre']+response['list']);
				}, 'json');
				break;
			case 'relationship':
				$.get(ajaxurl,{action:'wpas_get_entities',type:type,app_id:app_id,primary_entity:chart_ent,add_sel:1,values:selected}, function(response){
					$(show_id+ '_id').html(response);
				});
				break;
		}
		$(show_id + "_div").show();
		if(chart_type == 'pie')
		{
			$('#shc-vaxis_title_div').hide();
			if(sel == 'unique')
			{
				$('#shc-haxis_div').hide();
			}
			else
			{
				$('#shc-haxis_title_div').hide();
			}
		}
		else
		{
			if(sel == 'unique')
			{
				$('#shc-haxis_div').hide();
			}
			else if(sel == 'entity')
			{
				$('#shc-vaxis_div').hide();
			}
			else
			{
				$('#shc-vaxis_title_div').show();
				$('#shc-haxis_title_div').show();
			}
		}
		if(sel == 'date' && chart_type != 'table')
		{
			$(show_id +'_date_type_div').show();
		}
		else
		{
			$(show_id +'_date_type_div').hide();
			$(show_id +'_date_range_div').hide();
		}
	};
	$.fn.vhAxisType = function(chart,func,id){
		if(id=='#shc-haxis_type')
		{
			if(func == 'none')
			{
				func = 'uniq';
			}
			else
			{
				func = 'default';
			}
		}
		switch(func) {
			case 'uniq':
				var axis_type = '<option value="">Please select</option><option value="unique">Unique Attributes</option>';
				break;
			case 'none':
			case 'sum':
			case 'avg':
			case 'max':
			case 'min':
				var axis_type = '<option value="">Please select</option><option value="attribute">Attribute Field</option>';
				break;
			case 'count':
			case 'default':		
				var axis_type = '<option value="">Please select</option><option value="date">Date Field</option><option value="attribute">Attribute Field</option><option value="taxonomy">Taxonomy</option><option value="relationship">Relationship</option>';
				break;
		}
		if(id=='#shc-vaxis_type' && chart == 'pie' && func == 'count')
		{
			$('#shc-vaxis_type_div').hide();
			$('#shc-vaxis_div').hide();
		}
		else
		{
			$(id).html(axis_type);
			$(id).show();
		}
	}
	$.fn.chartFunc = function(chart_type){
		switch (chart_type) {
			case 'pie':
				var funcs = '<option value="">Please select</option><option value="none">None</option><option value="sum">Sum</option><option value="count">Count</option>';
				break;
			default:
				var funcs = '<option value="">Please select</option><option value="none">None</option><option value="count">Count</option><option value="sum">Sum</option><option value="avg">Average</option><option value="max">Max</option><option value="min">Min</option>';
				break;
		}
		$('#shc-chart_func').html(funcs);
	}
	$('#shc-chart_func').click(function() {
		var func = $(this).find('option:selected').val();
		var chart = $('#shc-chart_type').find('option:selected').val();
		$(this).vhAxisType(chart,func,'#shc-vaxis_type');
		$(this).vhAxisType(chart,func,'#shc-haxis_type');
	});
	$('#shc-chart_type').click(function() {
		var chart = $(this).find('option:selected').val();
		$(this).chartType(chart);
		$(this).chartFunc(chart);
	});
	$.fn.chartType = function(chart){
		switch (chart){
			case 'bar':
				$('#shc-vaxis_type_div').show();
				$('#shc-haxis_type_div').show();
				$('#shc-chart_func_div').show();
				$('#shc-pie_type_div').hide();
				$('#shc-chart_stacked_div').show();
				$('#shc-chart_title_div').show();
				$('.haxis').each(function() {
					axis_label = $(this).html().replace("(H)","(V)");
					$(this).html(axis_label);
				});
				$('.vaxis').each(function() {
					axis_label = $(this).html().replace("(V)","(H)");
					$(this).html(axis_label);
				});
				break;
			case 'pie':
				//hide haxis
				$('#shc-vaxis_type_div').show();
				$('#shc-haxis_type_div').show();
				$('#shc-haxis_div').show();
				$('#shc-pie_type_div').show();
				$('#shc-chart_func_div').show();
				$('#shc-chart_stacked_div').hide();
				$('#shc-chart_title_div').show();
				$('.haxis').each(function() {
					axis_label = $(this).html().replace("(V)","(H)");
					$(this).html(axis_label);
				});
				$('.vaxis').each(function() {
					axis_label = $(this).html().replace("(H)","(V)");
					$(this).html(axis_label);
				});
				break;
			case 'column':
			case 'line':
			case 'area':
				if(chart == 'column')
				{
					$('#shc-chart_stacked_div').show();
				}
				else
				{
					$('#shc-chart_stacked_div').hide();
				}
				$('#shc-vaxis_type_div').show();
				$('#shc-haxis_type_div').show();
				$('#shc-chart_func_div').show();
				$('#shc-pie_type_div').hide();
				$('#shc-chart_title_div').show();
				$('.haxis').each(function() {
					axis_label = $(this).html().replace("(V)","(H)");
					$(this).html(axis_label);
				});
				$('.vaxis').each(function() {
					axis_label = $(this).html().replace("(H)","(V)");
					$(this).html(axis_label);
				});
				break;
			default:
				$('#shc-vaxis_type_div').hide();
				$('#shc-haxis_type_div').hide();
				$('#shc-haxis_div').hide();
				$('#shc-vaxis_div').hide();
				$('#shc-vaxis_type_div').hide();
				$('#shc-chart_func_div').hide();
				$('#shc-pie_type_div').hide();
				$('#shc-chart_stacked_div').hide();
				$('#shc-table_div').hide();
				$('#shc-chart_title_div').show();
				break;
		}
	}	
	$('#shc-vaxis_type,#shc-haxis_type').click(function() {
		if($(this).attr('id') == 'shc-vaxis_type')
		{
			fid = 'vaxis';
		}
		else
		{
			fid = 'haxis';
		}
		app_id = $('input#app').val();
		chart_ent = $('#shc-attach').find('option:selected').val();
		chart_type = $('#shc-chart_type').find('option:selected').val();
		func = $('#shc-chart_func').find('option:selected').val();
		var sel = $(this).find('option:selected').val();
		$(this).vhAxis(sel,app_id,chart_ent,chart_type,func,'',fid);
	});
	$('#shc-table_ids').click(function() {
		$('#shc-table_col').append($("<option></option>").attr("value",$("option:selected",this).val()).text($("option:selected",this).html()));
		$("option:selected",this).attr('disabled','disabled');
		$("option:selected",this).removeAttr('selected');	
			
			//var newval = '';
			/*if($('#shc-table_col').val() != '')
			{
				newval = $('#shc-table_col').val()+';'+$("option:selected",this).attr('col');
			}
			else
			{
				newval = $("option:selected",this).attr('col');
			}
			$("option:selected",this).attr('disabled','disabled');
			$("option:selected",this).removeAttr('selected');	
			$('#shc-table_col').val(newval);	*/
	});
	$('#shc-table_col').click(function() {
		var tval = $("option:selected",this).val();
		$("option:selected",this).remove();
		$('#shc-table_ids option[value=' + tval + ']').removeAttr('disabled');
	});
	$('#shc-haxis_date_type,#shc-vaxis_date_type').click(function() {
		var myid = $(this).attr('id');
		if(myid == 'shc-haxis_date_type')
		{
			divtype = 'haxis';
		}
		else
		{
			divtype = 'vaxis';
		}	
		var type = $(this).find('option:selected').val();
		$(this).vhDateType(divtype,type,'');
	});
	$.fn.saveGridCols = function(){
		$('#shc-table_col option').each(function()
		{
			$(this).attr("selected",true);
		});
	}
	$.fn.updateGridCols = function(cols){
		$.each(cols,function(i,val) {
			$('#shc-table_col option[value=' + val + ']').removeAttr('selected');
			$('#shc-table_ids option[value=' + val + ']').attr('disabled','disabled');
		});
	}
	$.fn.vhDateType = function(divtype,type,value){
		if(type != 'year')
		{
			$.get(ajaxurl,{action:'wpas_get_date_ranges',type:type,value:value}, function(response)
			{
				$('#shc-'+divtype+'_date_range').html(response);
				$('#shc-'+divtype+'_date_range_div').show();
			});
		}
		else
		{
			$('#shc-'+divtype+'_date_range_div').hide();
		}
	}
        $('#shc-attach_tax').click(function() {
		var app_id = $('input#app').val();
		var tax_id = $('#shc-attach_tax').val();
		if(tax_id != '')
		{
			$.get(ajaxurl,{action:'wpas_get_tax_values',app_id:app_id,tax_id:tax_id}, function(response)
			{
				$('#add-shortcode-div #shc-attach_taxterm').html(response);
			});
			$.get(ajaxurl,{action:'wpas_get_entities',type:'shortcode',app_id:app_id,tax_id:tax_id}, function(response)
                        {
                                $('#add-shortcode-div #shc-attach').html(response);
                                $('#shc-attach_div').show();
                        });
		}
	});
	$('#shc-setup_page').click(function() {
                if($(this).attr('checked'))
                {
                        $('#shc-setup_page_title_div').show();
                }
                else
                {
                        $('#shc-setup_page_title_div').hide();
                }
        });
	$('#shc-app_dash').click(function() {
                if($(this).attr('checked'))
                {
                        $('#shc-app_dash_title_div').show();
                        $('#shc-app_dash_loc_div').show();
                }
                else
                {
                        $('#shc-app_dash_title_div').hide();
                        $('#shc-app_dash_loc_div').hide();
                }
        });
});			
</script>
		<form action="" method="post" id="shortcode-form" class="form-horizontal">
		<input type="hidden" id="app" name="app" value="">
		<input type="hidden" value="" name="shc" id="shc">
		<fieldset>
		<div class="well">
		<div class="row-fluid">
		<div class="alert alert-info pull-right">
		<i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Views help you display content where and how you wanted on the frontend.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Name","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-label" id="shc-label" type="text" placeholder="<?php _e("e.g. sc_products","wpas");?>">
		<a href="#" title="<?php _e("General name for the view. By enclosing the view name in square brackets in a page, post or a text widget, you can display the content returned by the view shortcode's query. The view name should be all lowercase and use all letters, but numbers and underscores (not dashes!) should work fine too. Max 30 characters allowed. If the shortcode is used in a text widget or a page and the content has multiple pages, paginated navigation links are displayed. You can filter the content by generating a shortcode filter using the WPAS toolbar button on a page or post.","wpas"); ?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
                <label class="control-label span3"><?php _e("Description","wpas"); ?></label>
                <div class="controls span9">
                <textarea name="shc-desc" id="shc-desc" class="wpas-std-textarea"></textarea>
                <a href="#" style="cursor: help;" title="<?php _e("Sets the initial short description for the view.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Type","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-view_type" id="shc-view_type" class="input-medium">
		<option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
		<option value="std"><?php _e("Standard","wpas"); ?></option>
		<option value="search"><?php _e("Search","wpas"); ?></option>
		<option value="single"><?php _e("Single","wpas"); ?></option>
		<option value="archive"><?php _e("Archive","wpas"); ?></option>
		<option value="tax"><?php _e("Taxonomy","wpas"); ?></option>
		<option value="chart"><?php _e("Chart","wpas"); ?></option>
		<option value="datagrid"><?php _e("Data grid","wpas"); ?></option>
		<option value="integration"><?php _e("Integration","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the type of view to be created.You can create search, standard, single, archive, and taxonomy views. All views except single view produce a list of content. The single views display individual entity content. Each entity can only have one single view. Search views are for displaying search results and must be attached to at least one search form. Taxonomy views display the content of a taxonomy. Each taxonomy can have only one taxonomy view. Archive views display a list of entity content. Each entity can have only one archive view. In addition, you can sort, filter the archived content of views using the filter tab. Single views diplay only one record so can not be filtered or sorted. If you like to display multiple versions of the archived content of an entity, you can create a standard view. There is no limitation of the number of standard views that can be attached to an entity. Standard views can be put on a page or post using the wpas toolbar button.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-attach_form_div" name="shc-attach_form_div" style="display:none;">
		<label class="control-label req span3"><?php _e("Attach to Form","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach_form" name="shc-attach_form">
		</select><a href="#" style="cursor: help;" title="<?php _e("Search forms must be attached to an already created view. A search view defines the format of how search results will be displayed.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div id="shc-attach_tax_div" name="shc-attach_tax_div" style="display:none;">
		<div class="control-group row-fluid">
		<label class="control-label req span3"><?php _e("Attach to Taxonomy","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach_tax" name="shc-attach_tax">
		</select><a href="#" style="cursor: help;" title="<?php _e("Taxonomy views must be attached to a predefined taxonomy.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
                <div class="control-group row-fluid">
                <label class="control-label span3"><?php _e("Attach to Term","wpas"); ?></label>
                <div class="controls span9">
                <select id="shc-attach_taxterm" name="shc-attach_taxterm">
		<option value=''><?php _e("Apply to all","wpas"); ?></option>
                </select><a href="#" style="cursor: help;" title="<?php _e("Taxonomy views can be attached to a predefined taxonomy term.","wpas"); ?>">
                <i class="icon-info-sign"></i></a>
                </div>
                </div>
		</div>
		<div class="control-group row-fluid" id="shc-attach_div" name="shc-attach_div" style="display:none;">
		<label class="control-label req span3"><?php _e("Attach to Entity","wpas"); ?></label>
		<div class="controls span9">
		<select id="shc-attach" name="shc-attach">
		</select><a href="#" title="<?php _e("Views must be attached to a predefined entity. The attached entity's content is returned by the view after query filters applied.","wpas"); ?>" style="cursor: help;">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
                </div>
		<div id="view-tabs">
        <ul id="shcTab" class="nav nav-tabs">
        <li id="shctabs-1-li" class="active"><a data-toggle="tab" href="#shctabs-1"><?php _e("Display Options","wpas"); ?></a></li>
        <li id="shctabs-2-li"><a data-toggle="tab" href="#shctabs-2"><?php _e("Filters","wpas"); ?></a></li>
        <li id="shctabs-3-li"><a data-toggle="tab" href="#shctabs-3"><?php _e("Messages","wpas"); ?></a></li>
        </ul>
        <div id="ShcTabContent" class="tab-content">
        <div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Display Options tab configures how the view will be displayed on the frontend. Filters tab defines how the content will be returned by setting sort order, number of records etc. Messages tab helps you define the messages to be displayed to users when the view's content is requested.","wpas"); ?>"><?php _e("HELP","wpas"); ?></a></div></div>
	<div id="shctabs-1" class="tab-pane fade in active">
		<div class="control-group row-fluid" id="shc-setup_page_div" style="display:none;">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Create Setup Page","wpas");?>
		<input name="shc-setup_page" id="shc-setup_page" type="checkbox" value="1">
		<a href="#" style="cursor: help;" title="<?php _e("When set, the view will be created as a page upon activation.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-rmv_wpasbtn_div" style="display:none;">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Remove from WPAS button list","wpas");?>
		<input name="shc-rmv_wpasbtn" id="shc-rmv_wpasbtn" type="checkbox" value="1">
		<a href="#" style="cursor: help;" title="<?php _e("When set, this view is removed from the WPAS button in page toolbar.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-setup_page_title_div" style="display:none;">
		<label class="control-label span3 req"><?php _e("Setup Page Title","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-setup_page_title" id="shc-setup_page_title" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("The title of the setup page. Max:255 char.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid" id="shc-app_dash_div" style="display:none;">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Display in App Dashboard","wpas");?>
		<input name="shc-app_dash" id="shc-app_dash" type="checkbox" value="1">
		<a href="#" style="cursor: help;" title="<?php _e("When set, displays shortcode in app dashboard. App Dashboard can only contain one instance of chart or datagrid shortcode. You can not select to display an integration view on your app dashboard if it includes a chart or datagrid which is already selected to be displayed on your app dashboard.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-app_dash_title_div" style="display:none;">
		<label class="control-label span3 req"><?php _e("Dashboard Title","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-app_dash_title" id="shc-app_dash_title" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("The title of the dashboard. Max:255 char.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid" id="shc-app_dash_loc_div" style="display:none;">
		<label class="control-label span3 req"><?php _e("Dashboard Location","wpas");?></label>
		<div class="controls span9">
		<select name="shc-app_dash_loc" id="shc-app_dash_loc" class="input-medium">
		<option value="">Please select</option>
		<option value="wholecol">One Column</option>
		<option value="normal">Two Column Left</option>
		<option value="side">Two Column Right</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("The title of the dashboard. Max:255 char.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div id="shc-theme_type_div" name="shc-theme_type_div" style="display:none;">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Template","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-theme_type" id="shc-theme_type" class="input-medium">
		<option value="Na">None</option>
		<option value="Bootstrap">Twitter's Bootstrap</option>
		<option value="Pure">jQuery UI</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the frontend framework which will be used to configure the overall look and feel of the view. If you pick jQuery UI, you can choose your theme from App's Settings under the theme tab. Default is Twitter Bootstrap.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-font_awesome_div" name="shc-font_awesome_div">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Enable Font Awesome","wpas");?>
		<input name="shc-font_awesome" id="shc-font_awesome" type="checkbox" value="1" checked/>
		<a href="#" style="cursor: help;" title="<?php _e("Enables Font Awesome webfont for radios, checkboxes and other icons. Can not be disabled for the Bootstrap framework.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-sc_pagenav_div">
                <label class="control-label span3"></label>
                <label class="checkbox span9"><?php _e("Enable paged navigation.","wpas"); ?>
                <input type="checkbox" value="1" id="shc-sc_pagenav" name="shc-sc_pagenav">
                <a title="<?php _e("Enables pagination links.","wpas"); ?>" style="cursor: help;" href="#">
                <i class="icon-info-sign"></i></a>
               </label>
                </div>
		<div class="control-group row-fluid" id="shc-pgn_class_div">
                <label class="control-label span3"><?php _e("Paging Class","wpas"); ?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-pgn_class" id="shc-pgn_class" type="text" placeholder="<?php _e("e.g. visible-lg","wpas");?>" value="" >
                <a title="<?php _e("Add css class to page navigation.","wpas"); ?>" style="cursor: help;" href="#">
                <i class="icon-info-sign"></i></a>
               </label>
                </div>
                </div>
		<div id="shc-table_div" style="display:none;">
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Show Index","wpas");?>
		<input name="shc-show_index" id="shc-show_index" type="checkbox" value="1">
		<a href="#" style="cursor: help;" title="<?php _e("When set, it displays the row index as the grid's first column.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Show Hide Columns","wpas");?>
		<input name="shc-show_hide_col" id="shc-show_hide_col" type="checkbox" value="1">
		<a href="#" style="cursor: help;" title="<?php _e("When set, it gives the user an option to choose which columns to show/hide in the form of a button.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("List","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-table_ids" id="shc-table_ids" class="input-xlarge" size=10 multiple>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select the attribute, taxonomy, or relationship identifier you would like to include in your grid by default. Select by clicking on the item.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid">
		<label class="control-label span3 req"><?php _e("Columns","wpas");?></label>
		<div class="controls span9">
		<select name="shc-table_col[]" id="shc-table_col" class="input-xlarge" size=10 multiple="multiple">
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Displays the attributes, taxonomies, or relationship identifiers selected from the list. Deselect by clicking on the item.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		</div><!-- end of shc-table_div -->
		<div class="control-group row-fluid" id="shc-layout_header_div" style="display:none;">
		<label class="control-label span3"><?php _e("Header","wpas"); ?></label>
		<div class="controls span9">
		<textarea id="shc-layout_header" name="shc-layout_header" class="wpas-std-textarea"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("It defines the header content of the view. The header content is static and displayed on the top section of your view's content.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-layout_div">
		<label class="control-label req span3"><?php _e("Layout","wpas"); ?></label>
		<div class="controls span9">
		<textarea id="shc-sc_layout" name="shc-sc_layout" class="wpas-std-textarea"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the layout of a single record of your view. You can also edit the source code, add entity attributes, taxonomies.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		<div id="show-tags-div" style="padding:10px 0;">
		<div style="padding:10px;">
		<button type="button" class="btn btn-mini btn-info" data-toggle="collapse" data-target="#shc-layout-tags">Show Tags</button>
		</div>
		<div id="shc-layout-tags" class="collapse in"><?php _e("Please select an entity to view tags.","wpas"); ?></div>
		</div>
		</div>
		</div>
		<div class="control-group row-fluid" id="shc-layout_footer_div" style="display:none;">
		<label class="control-label span3"><?php _e("Footer","wpas"); ?></label>
		<div class="controls span9">
		<textarea id="shc-layout_footer" name="shc-layout_footer" class="wpas-std-textarea"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("It defines the footer content of the view. The footer content is static and displayed on the bottom section of your view's content.","wpas"); ?>"><i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div id="shc-chart_div" style="display:none;">
		<div class="control-group row-fluid" id="shc-chart_title_div">
		<label class="control-label span3"><?php _e("Chart Title","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-chart_title" id="shc-chart_title" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Text to display above the chart. Max:255 char.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid">
		<label class="control-label span3 req"><?php _e("Subtype","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-chart_type" id="shc-chart_type" class="input-medium">
		<option value="">Please select</option>
		<option value="column">Column</option>
		<option value="bar">Bar</option>
		<option value="line">Line</option>
		<option value="area">Area</option>
		<option value="pie">Pie</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Set the type of chart.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-chart_func_div" style="display:none;">
		<label class="control-label span3 req"><?php _e("Function","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-chart_func" id="shc-chart_func" class="input-medium">
		<option value="">Please select</option>
		<option value="none">None</option>
		<option value="count">Count</option>
		<option value="sum">Sum</option>
		<option value="avg">Average</option>
		<option value="max">Max</option>
		<option value="min">Min</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Sets aggregation function which will perform an action across all values in each group. Groups can be specified as attributes, taxonomies, dates, or relationships. Aggregation functions only apply on the numeric data for a group. None option does not apply any function. Count returns the count of elements. Null cells are not counted. Average returns the average value of all values. Max returns the maximum value. Min returns the minimum value. Sum returns the sum of all values.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-pie_type_div" style="display:none;">
		<label class="control-label span3 req"><?php _e("Pie Type","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-pie_type" id="shc-pie_type" class="input-medium">
		<option value="">Please select</option>
		<option value="pie">2D</option>
		<option value="3d">3D</option>
		<option value="donut">Donut</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select the type of pie chart.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-haxis_type_div" style="display:none;">
		<label class="control-label span3 req haxis"><?php _e("Axis Type (H)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-haxis_type" id="shc-haxis_type" class="input-medium">
		<option value="">Please select</option>
		<option value="date">Date</option>
		<option value="attribute">Attribute</option>
		<option value="taxonomy">Taxonomy</option>
		<option value="relationship">Relationship</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("The horizontal axis with an exception of a bar chart displays the categories in your content. You can use dates, attributes, taxonomies, or relationships as your categories. Date option offers data level and data range features for better categorization.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div id="shc-haxis_div" style="display:none;">
		<div class="control-group row-fluid" id="shc-haxis_title_div">
		<label class="control-label span3 haxis"><?php _e("Axis Title (H)","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-haxis_title" id="shc-haxis_title" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("The title of the horizontal axis. Max:255 char.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid">
		<label class="control-label span3 req haxis"><?php _e("Axis Value (H)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-haxis_id" id="shc-haxis_id" class="input-medium">
		<option value="">Please select</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select the specific date, attribute, taxonomy, or relationship for your horizontal axis categories.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-haxis_date_type_div" style="display:none;">
		<label class="control-label span3 req haxis"><?php _e("Date Level (H)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-haxis_date_type" id="shc-haxis_date_type" class="input-medium">
		<option value="">Please select</option>
		<option value="year">Year</option>
		<option value="month">Month</option>
		<option value="day">Day</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select how you want to display your date category. You can use year or month for summarization.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-haxis_date_range_div" style="display:none;">
		<label class="control-label span3 req haxis"><?php _e("Date Range (H)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-haxis_date_range" id="shc-haxis_date_range" class="input-medium">
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select the date range.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		</div> <!-- end of haxis_div -->
		<div class="control-group row-fluid" id="shc-vaxis_type_div" style="display:none;">
		<label class="control-label span3 req vaxis"><?php _e("Axis Type (V)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-vaxis_type" id="shc-vaxis_type" class="input-medium">
		<option value="">Please select</option>
		<option value="date">Date Field</option>
		<option value="attribute">Attribute Field</option>
		<option value="taxonomy">Taxonomy</option>
		<option value="relationship">Relationship</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("The vertical axis with the exception of bar charts displays the scale of a measure categorized by the horizontal axis. A measure is a property of which calculations (e.g., sum, count, average, minimum, maximum) can be made. You can use dates, attributes, taxonomies, and relationships as your vertical axis measures. Date is a special attribute type and offers data range level and data range features for better categorization.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div id="shc-vaxis_div" style="display:none;">
		<div class="control-group row-fluid" id="shc-vaxis_title_div">
		<label class="control-label span3 vaxis"><?php _e("Axis Title (V)","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-vaxis_title" id="shc-vaxis_title" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("The title of the vertical axis. Max:255 char.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid">
		<label class="control-label span3 req vaxis"><?php _e("Axis Value (V)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-vaxis_id" id="shc-vaxis_id" class="input-medium">
		<option value="">Please select</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select the measure you would to use in your vertical axis.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-vaxis_date_type_div" style="display:none;">
		<label class="control-label span3 req vaxis"><?php _e("Date Level (V)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-vaxis_date_type" id="shc-vaxis_date_type" class="input-medium">
		<option value="">Please select</option>
		<option value="year">Year</option>
		<option value="month">Month</option>
		<option value="day">Day</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select how you want to display your date measure. You can use year or month for summarization.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-vaxis_date_range_div" style="display:none;">
		<label class="control-label span3 req vaxis"><?php _e("Date Range (V)","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-vaxis_date_range" id="shc-vaxis_date_range" class="input-medium">
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Select the range of dates to be used in your measure.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		</div> <!-- end of vaxis_div -->
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Height","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-chart_height" id="shc-chart_height" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Sets the height of the chart area. Set a simple number in pixels.Example: 100px.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Width","wpas");?></label>
		<div class="controls span9">
		<input class="input-xlarge" name="shc-chart_width" id="shc-chart_width" type="text" placeholder="<?php _e("e.g. Customer Survey","wpas");?>" value="" >
		<a href="#" style="cursor: help;" title="<?php _e("Sets the width of the chart area. Set a simple number in pixels. Example: 100px. Leave it blank for responsive sizing i.e. chart will resize itself based on the screen size.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>	
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Legend Position","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-legend_pos" id="shc-legend_pos" class="input-medium">
		<option value="top">Top</option>
		<option value="bottom">Bottom</option>
		<option value="left">Left</option>
		<option value="right">Right</option>
		<option value="in">In</option>
		<option value="none">None</option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Position of the legend. Can be one of the following: Bottom - Below the chart. Left - To the left of the chart. In - Inside the chart, by the top left corner. Right - To the right of the chart. Incompatible with the vAxes option. Top - Above the chart. None - No legend is displayed.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div> 
		<div class="control-group row-fluid" id="shc-chart_stacked_div">
		<label class="control-label span3"></label>
		<div class="controls span9">
		<label class="checkbox"><?php _e("Is Stacked","wpas");?>
		<input name="shc-chart_stacked" id="shc-chart_stacked" type="checkbox" value="1">
		<a href="#" style="cursor: help;" title="<?php _e("When set, the chart series are displayed on top of one another.","wpas");?>">
		<i class="icon-info-sign"></i></a>
		</label>
		</div>
		</div>
		</div><!-- end of chart div -->
		<div class="control-group row-fluid" id="shc-sc_css_div">
		<label class="control-label span3"><?php _e("Css","wpas"); ?></label>
		<div class="controls span9">
		<textarea class="wpas-std-textarea" id="shc-sc_css" name="shc-sc_css"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("The custom css code to be used when displaying the content. It is handy when you added custom classes in the layout editor and want to add css class definitions. You can leave this field blank and use a common css file for all.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div> <!-- end of tab1 -->
		<div id="shctabs-2" class="tab-pane fade in">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Entities Per Page","wpas"); ?></label>
		<div class="controls span9">
		<input id="shc-sc_post_per_page" name="shc-sc_post_per_page" class="input-mini" type="text" placeholder="<?php _e("e.g. 16","wpas"); ?>" value="" />
		<a href="#" style="cursor: help;" title="<?php _e("Specify the number of content block to show per page. Use any integer value or -1 to show all.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("List Order","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-sc_order" id="shc-sc_order" class="input-small">
		<option value="DESC" selected="selected"><?php _e("Descending","wpas"); ?></option>
		<option value="ASC"><?php _e("Ascending","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Allows the content to be sorted ascending or descending by a parameter selected. Defaults to descending.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Sort Retrieved Posts By","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-sc_orderby" id="shc-sc_orderby" class="input-small">
		<option value="date" selected="selected"><?php _e("Date","wpas"); ?></option>
		<option value="ID"><?php _e("ID","wpas"); ?></option>
		<option value="author"><?php _e("Author","wpas"); ?></option>
		<option value="title"><?php _e("Title","wpas"); ?></option>
		<option value="parent"><?php _e("Post parent id","wpas"); ?></option>
		<option value="modified"><?php _e("Last modified date","wpas"); ?></option>
		<option value="rand"><?php _e("Random","wpas"); ?></option>
		<option value="comment_count"><?php _e("Number of comments","wpas"); ?></option>
		<option value="none"><?php _e("None","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Allows sorting of retrieved content by a parameter selected. Defaults to date.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("Show By Status","wpas"); ?></label>
		<div class="controls span9">
		<select name="shc-sc_post_status" id="shc-sc_post_status" class="input-small">
		<option value="publish" selected="selected"><?php _e("Publish","wpas"); ?></option>
		<option value="pending"><?php _e("Pending","wpas"); ?></option>
		<option value="title"><?php _e("Draft","wpas"); ?></option>
		<option value="auto-draft"><?php _e("With no content","wpas"); ?></option>
		<option value="future"><?php _e("Future","wpas"); ?></option>
		<option value="private"><?php _e("Private","wpas"); ?></option>
		<option value="trash"><?php _e("Trash","wpas"); ?></option>
		<option value="any"><?php _e("Any but excluded from search","wpas"); ?></option>
		</select>
		<a href="#" style="cursor: help;" title="<?php _e("Retrieves content by status, default value is publish.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div> <!-- end of tab2 -->
		<div id="shctabs-3" class="tab-pane fade in">
		<div class="control-group row-fluid">
		<label class="control-label span3"><?php _e("No Access Message","wpas"); ?></label>
		<div class="controls span9">
		<textarea id="shc-no_access_msg" name="shc-no_access_msg" class="wpas-std-textarea"></textarea>
		<a href="#" style="cursor: help;" title="<?php _e("Sets the text which will be displayed to users that do not have access to this view.","wpas"); ?>">
		<i class="icon-info-sign"></i></a>
		</div>
		</div>
		</div> <!-- end of tab3 -->

		<div class="control-group row-fluid">
		<button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button">
		<i class="icon-ban-circle"></i><?php _e("Cancel","wpas"); ?></button>
		<button class="btn  btn-primary pull-right layout-buttons" id="save-shortcode" type="submit" value="Save">
		<i class="icon-save"></i><?php _e("Save","wpas"); ?></button>
		</div>
		</fieldset>
		</form>

		<?php
}
?>
