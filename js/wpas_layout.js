jQuery(document).ready(function() {
	var $ = jQuery;
	var currentItem = '';
	var submit_empty = false;
	var do_nothing = false;
	var ret1 = true;

	$.fn.getCount = function(type) {
		var count_arr = $(this).find('div.'+type);
		var count = count_arr.length + 1;
		return count;
	}
	$.fn.getAttrs = function(){
		var attrs = [];
		$(this).find('.attr-sel').each(function () {
			if($(this).val != '')
			{
				attrs.push($(this).val());
			}
		});
		return attrs;
	}
	$.fn.attrDroppable = function(){
		 $('.multiattr-ctr').droppable({
			accept: 'div.tabattr',
			drop: function(event, ui) {
				app_id = $('input#app').val();
				ent_id = $('input#ent-layout').val();
				var attr_ctr = $(this);
				$.get(ajaxurl,{action:'wpas_get_ent_layout_attrs',app_id:app_id,ent_id:ent_id}, function(response){
					attr_ctr.append('<div class="el-attr row-fluid">'+response+'</div>');
				});
			}
		});
	}
	$.fn.tabDroppable = function () {
		$('.multitab-ctr').droppable({
			accept: 'div.tab',
			drop: function(event, ui) {
				var tabcount = $(this).getCount('tab-ctr');
				tab = '<div class="tab-ctr ui-draggable" id="tab-ctr'+tabcount+'"><div id="tab-ctr'+tabcount+'-row" class="row-fluid"><div class="pull-left layout-edit-icons"><a class="edit"><i class="icon-edit"></i></a></div><div id="tab-ctr'+tabcount+'-title" class="tabctr-title layout-title span10 pull-left">' + layout_vars.edit_tab_title + '</div><div class="pull-right layout-edit-icons"><a class="delete"><i class="icon-trash"></i></a></div></div><div id="multiattr-ctr" class="multiattr-ctr"></div></div>';
				$(this).append(tab);
				$(".multitab-ctr").sortable();
				$(this).attrDroppable();
			}
		});
	}
	$.fn.accDroppable = function () {
		$('.multiacc-ctr').droppable({
			accept: 'div.acc',
			drop: function(event, ui) {
				var acccount = $(this).getCount('acc-ctr');
				accordion = '<div class="acc-ctr" id="acc-ctr'+acccount+'"><div id="acc-ctr'+acccount+'-row" class="row-fluid"><div class="pull-left layout-edit-icons"><a class="edit"><i class="icon-edit"></i></a></div><div id="acc-ctr'+acccount+'-title" class="accctr-title layout-title span10 pull-left">' + layout_vars.edit_acc_title + '</div><div class="pull-right layout-edit-icons"><a class="delete"><i class="icon-trash"></i></a></div></div><div id="multiattr-ctr" class="multiattr-ctr"></div></div>';
				$(this).append(accordion);
				$(".multiacc-ctr").sortable();
				$(this).attrDroppable();
			}
		});
	}
	$(document).on('click','.delete-attr',function(){
		$(this).closest('.el-attr').remove();
        });

	$(document).on('click','#edit_layout.entity a',function(){
                $('.group1').hide();
		app_id = $('input#app').val();
		ent_id = $(this).attr('href').replace('#ent','');
		$('input#ent-layout').val(ent_id);
		app = $('input#app_title').val();
		ent = $(this).parent().parent().parent().find('#ent-name').html();
		$(this).getBreadcrumb('edit_layout',app,ent,ent_id,app_id);
		$.get(ajaxurl,{action:'wpas_get_layout',app_id:app_id,ent_id:ent_id}, function(response){
			$('#layout-ctr').html(response);
			$('.tabgrp, .tab, .accgrp, .acc, .tabattr').draggable({
				helper: "clone",
			});
			$('#edit-layout-div').show();

			$('#layout-ctr').droppable({
				accept: 'div.tabgrp,div.accgrp',
				drop: function(event, ui) {
				$('.dragme').remove();
				if($(ui.draggable).attr("id") == 'tabgrp')
				{
					var divcount = $(this).getCount('tabgrp-ctr');
					var tabcount = $(this).getCount('tab-ctr');
					tabgrp = '<div class="tabgrp-ctr ui-sortable" id="tabgrp'+divcount+'"><div id="tabgrp'+divcount +'-row" class="row-fluid"><div class="pull-left layout-edit-icons"><a class="edit"><i class="icon-edit"></i></a></div><div id="tabgrp'+divcount+ '-title" class="tabgrp-title layout-title span10 pull-left">' + layout_vars.edit_tab_gr_title + '</div><div class="pull-right layout-edit-icons"><a class="delete"><i class="icon-trash"></i></a></div></div><div class="multitab-ctr"><div class="tab-ctr ui-draggable" id="tab-ctr'+tabcount+'"><div id="tab-ctr'+tabcount+'-row" class="row-fluid"><div class="pull-left layout-edit-icons"><a class="edit"><i class="icon-edit"></i></a></div><div id="tab-ctr'+tabcount+'-title" class="tabctr-title layout-title span10 pull-left">' + layout_vars.edit_tab_title + '</div><div class="pull-right layout-edit-icons"><a class="delete"><i class="icon-trash"></i></a></div></div><div id="multiattr-ctr" class="multiattr-ctr"></div></div></div></div>';
					$(this).append(tabgrp);
					$(this).tabDroppable();
				}
				else if($(ui.draggable).attr("id") == 'accgrp')
				{
					var accdivcount = $(this).getCount('accgrp-ctr');
					var acccount = $(this).getCount('acc-ctr');
					accgrp = '<div class="accgrp-ctr ui-sortable" id="accgrp'+accdivcount+'"><div id="accgrp'+accdivcount +'-row" class="row-fluid"><div class="pull-left layout-edit-icons"><a class="edit"><i class="icon-edit"></i></a></div><div id="accgrp'+accdivcount+ '-title" class="accgrp-title layout-title span10 pull-left">' + layout_vars.edit_acc_gr_title + '</div><div class="pull-right layout-edit-icons"><a class="delete"><i class="icon-trash"></i></a></div></div><div class="multiacc-ctr"><div class="acc-ctr" id="acc-ctr'+acccount+'"><div id="acc-ctr'+acccount+'-row" class="row-fluid"><div class="pull-left layout-edit-icons"><a class="edit"><i class="icon-edit"></i></a></div><div id="acc-ctr'+acccount+'-title" class="accctr-title layout-title span10 pull-left">' + layout_vars.edit_acc_title + '</div><div class="pull-right layout-edit-icons"><a class="delete"><i class="icon-trash"></i></a></div></div><div id="multiattr-ctr" class="multiattr-ctr"></div></div></div></div>';
					$(this).append(accgrp);
					$(this).accDroppable();
				}
				$("#layout-ctr").sortable();
				$(this).attrDroppable();
			}
		});
		if(response != '<div class="dragme">' + layout_vars.drag_drop + '</div>')
		{
			$(this).tabDroppable();
			$(this).accDroppable();
			$(this).attrDroppable();
		}
		});
	});
	$(document).on('click','.tabgrp-ctr a.edit,.accgrp-ctr a.edit',function(){
		if($(this).parent().parent().attr('id').search('row') == -1)
                {
                        currentItem = $(this).parent().parent().attr('id') + '-title';
                }
                else {
                        currentItem = $(this).parent().parent().attr('id').replace('row','title');
                }
		$('#myModal input#title').val('');
		$('#myModal').modal('show');
	});
	$(document).on('click','button#edit-close,button#edit-cancel',function(){
		$('#myModal').modal('hide');
	});
	$(document).on('click','button#save-layout-title',function(){
		var valid = $('#layout-form').valid();
                if(!valid)
                {
                        return false;
                }
		var new_title = '<div id="'+currentItem+'" class="layout-title span11 pull-left">'+$('#title').val()+'</div>';
		$( "#"+ currentItem).replaceWith(new_title);
		$('#myModal').modal('hide');
	});
	$('div.modal-body #title').keypress(function(event) {
		if(event.keyCode == 13)
		{
			if($(this).val() != '')
			{
				$('button#save-layout-title').click();
			}
			return false;
		}
	});

	$(document).on('click','div.tabgrp-ctr a.delete,div.accgrp-ctr a.delete',function(){
		currentItem = $(this).parent().parent().parent();
		currentItem.remove();
		tabs = $('#layout-ctr').find('div.tabgrp-ctr');
		accs = $('#layout-ctr').find('div.accgrp-ctr');

		if(tabs.length === 0 && accs.length === 0)
		{
			$('#layout-ctr').append('<div class="dragme">' + layout_vars.drag_drop + '</div>');
		}
	});
	$(document).on('click','#errorModal1 button#error-ok,button#error-close,#errorModal3 button#error-ok,#errorModal4 button#error-ok,#errorModal button#error-ok',function(){
		$(this).closest('.modal').modal('hide');
		submit_empty = false;
		ret1 = false;
	});
	$(document).on('click','#errorModal2 button#error-close',function(){
		$('#errorModal2').modal('hide');
		do_nothing = true;
	});
	
	$(document).on('click','#errorModal2 button#error-ok',function(){
		$('#errorModal2').modal('hide');
		submit_empty = true;
		ret1 = false;
		do_nothing = false;
		$('#save-layout.btn').click();
	});

	$(document).on('click','#save-layout.btn',function(event){
		var lcount = 0;
		var layout = {};
		var all_attrs = [];
		var ret = true;
		var submit = true;
		if(submit_empty === false)
		{
			if($('#layout-ctr').find('select.attr-sel').length === 0)
			{
				$('#errorModal2').modal('show');
				ret1 = false;
				return false;
			}
			else
			{
				ret1 = true;
				$('#layout-ctr').find('select.attr-sel').each(function (){
					if($(this).val() == '')
					{
						$('#errorModal3').modal('show');
						ret1 = false;
						return false;
					}
				});
				if(ret1 === false)
				{
					return false;
				}
			}
		}
		if(do_nothing == true)
		{
			return false;
		}
		if(ret1 === true)
		{
		$('div.tabgrp-ctr,div.accgrp-ctr').each(function (){
			var layout_item = {};
			var layout_tabs = {};
			var layout_accs = {};
			var tcount = 0;
			var acount = 0;
			tabgrp_id = $(this).attr('id');
			layout_item['gr_title'] = $('#'+tabgrp_id+'-title').text();
			$('#'+tabgrp_id+' div.tab-ctr').each(function (){
				var layout_tab = {};
				tabId = $(this).attr('id');
				layout_tab['tab_title'] = $('#'+tabId+'-title').text();
				var attr_ctr = $(this).find('#multiattr-ctr');
				var attr_save = attr_ctr.find('select.attr-sel');
				if(attr_save.length === 0)
				{
					ret = false;
					return false;
				}
				layout_tab['attr'] = $(this).getAttrs();
				all_attrs = $.merge(all_attrs,layout_tab['attr']);
				layout_tabs[tcount] = layout_tab;
				layout_item['tabs'] = layout_tabs;
				tcount++;
			});
			if(ret == false)
			{
				return false;
			}
			$('#'+tabgrp_id+' div.acc-ctr').each(function (){
				var layout_acc = {};
				acc_id = $(this).attr('id');
				layout_acc['acc_title'] = $('#'+acc_id+'-title').text();
				var attr_ctr = $(this).find('#multiattr-ctr');
				var attr_save = attr_ctr.find('select.attr-sel');
				if(attr_save.length === 0)
				{
					ret = false;
					return false;
				}
				layout_acc['attr'] = $(this).getAttrs();
				all_attrs = $.merge(all_attrs,layout_acc['attr']);
				layout_accs[acount] = layout_acc;
				layout_item['accs'] = layout_accs;
				acount++;
			});
			if(ret == false)
			{
				return false;
			}
			layout[lcount] = layout_item;	
			lcount++;
		});
		if(ret == false)
		{
			$('#errorModal1').modal('show');
			return false;
		}
		}
		$.post(ajaxurl,{action:'wpas_save_layout',app_id:app_id,ent_id:ent_id,layout:layout,all_attrs:all_attrs,nonce:wpas_vars.nonce_save_layout}, function(response){
			switch (response) {
				case '2':	
					$('#errorModal4').modal('show');
					return false;
				case '3':
					$('#errorModal').modal('show');
					return false;
				case '1':
					submit_empty = false;
					$('.group1').hide();
					$.get(ajaxurl,{action:'wpas_list_all',type:'entity',app_id:app_id}, function(response)
					{
						$('#list-entity').html(response);
					});
					$('#list-entity').show();
			}
		});
	});
	jQuery('#layout-form').validate(
                {
                        onfocusout: false,
                        onkeyup: false,
                        onclick: false,
                        rules: {
                                'title':{
                                maxlength:50,
                                required:true
                                },
                        },
                        success: function(label) {
                                label.addClass('valid');
                                jQuery('label.valid').html('<i class=\"icon-check\"></i>');
                        }
                });

});
