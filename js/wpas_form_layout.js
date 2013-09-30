jQuery(document).ready(function($) {
	var field_count = 1;
	$(document).on('click','#save-form-layout.btn',function(event){
                event.preventDefault();
		form_data = $('#form-layout').serialize();
		form_id = $('#form-layout input#form').val();
		$.post(ajaxurl,{action:'wpas_form_layout_save',app_id:app_id,form_id:form_id,data:form_data,nonce:form_vars.nonce_save_form_layout}, function(response) {
			if(response == 1)
			{
				$(this).getBreadcrumb('app');
				$('.group1').hide();
				$('#list-form').show();
			}
			else if(response == 2)
			{
				$('#errorModalForm .modal-body').html('Please add all required fields to the form layout.');
				$('#errorModalForm').modal('show'); //req missing
			}
			else if(response == 3)
			{
				$('#errorModalForm .modal-body').html('Please check duplicate entries and try again.');
				$('#errorModalForm').modal('show'); //dupe error
			}
			else if(response == 4)
			{
				$('#errorModalForm .modal-body').html('Please select an option for all dropdowns.');
				$('#errorModalForm').modal('show'); //dupe error
			}
		});

	});
	 $(document).on('click','button#error-form-close,button#error-form-ok',function(){
                $('#errorModalForm').modal('hide');
        });
	$(document).on('click','div.form-hr a.delete,div.form-text a.delete,div.form-relent a.delete,div.form-attr a.delete,div.form-tax a.delete',function(){
                currentItem = $(this).parent().parent().parent().remove();
	});
	$(document).on('click','div.form-text a.edit,div.form-relent a.edit,div.form-attr a.edit,div.form-tax a.edit',function(){
		currentItem = $(this).parent().parent().parent().attr('id');
		$('#'+currentItem +' .form-inside').toggle();
	});
	$(document).on('click','.form-attr-select,.form-tax-select,.form-relent-select',function(){
		label_vars = $(this).attr('id').split('-');
		label_id = label_vars[5];
		select_label = "";
                if($(this).find('option:selected').val() != '')
                {
			if(label_vars[1] != 'relent')
			{
				select_label = $(this).find('option:selected').attr('entity') + " - ";
			}
			select_label += $(this).find('option:selected').text();
			$(this).parent().parent().parent().parent().find('#field-label'+label_id).text(select_label);
		}
	});
	$(document).on('click','.add-attr,.delete-attr,.add-tax,.delete-tax,.add-relent,.delete-relent',function(){
		selected_val = [];
		i = 1;
		app_id = $('input#app').val();
		form_id = $('#edit-form-layout-div input#form').val();
		id_vars = $(this).attr('id').split('-');
		count = id_vars[2];
		type = id_vars[1];
		rep_div = $(this).closest('.form-inside').attr('id');
		rep_div_vars = rep_div.split('-');
		field_count = rep_div_vars[3];
		$(this).closest('.form-'+ type).find('.form-' + type + '-select').each(function() {
			selected_val[i]  = $(this).val();
			i++;
		});
		if(id_vars[0] == 'delete')
		{
			labelcount = Number(count)+1;
			$(this).closest('.form-'+type).find('#field-label'+labelcount).html('');
		}

		$.get(ajaxurl,{action:'wpas_get_form_html',app_id:app_id,form_id:form_id,type:type,count:count,field_count:field_count,selected:selected_val},function(response){
				$('#'+rep_div).html(response);
		});
	});
		
	$(document).on('click','#edit_layout.form a',function(){
                $('.group1').hide();
		app_id = $('input#app').val();
		form_id = $(this).attr('href').replace('#form','');
		app = $('input#app_title').val();
		$('#edit-form-layout-div #app').val(app_id);
		$('#edit-form-layout-div #form').val(form_id);
		form = $(this).parent().parent().parent().find('#form-name').html();
		$(this).getBreadcrumb('edit_form_layout',app,form,form_id,app_id);

		$.get(ajaxurl,{action:'wpas_get_form_layout',app_id:app_id,form_id:form_id}, function(response){
			$('#form-layout-bin-ctr').html(response);
			$('#edit-form-layout-div').show();
			$('#form-hr,#form-text,#form-relent-1,#form-attr-1,#form-tax-1').draggable({
				helper: 'clone',
			});
			$('#form-layout-ctr').droppable({
				accept: '#form-hr,#form-text,#form-relent-1,#form-attr-1,#form-tax-1',
				drop: function(event, ui) {
					if($('.dragme'))
					{
						$('.dragme').remove();
					}
					var field_count = $('#form-field-count').val();
					var drag_id = $(ui.draggable).attr('id');
					var type_val = $(ui.draggable).text();
					var subdrag = drag_id.split('-');
					var class_id = subdrag[0] + '-' + subdrag[1];
					var type = subdrag[1];
					var count = subdrag[2];
					var div_inside = "";
					var resp_div = '#'+drag_id + "-" + field_count + '-inside';	
					switch(drag_id)
					{
					case 'form-hr':
						break;
					case 'form-text':
						$.get(ajaxurl,{action:'wpas_get_form_text_html',field_count:field_count},function(response){
							$(resp_div).html(response);
						});
						break;
					case 'form-attr-1':
					case 'form-tax-1':
					case 'form-relent-1':
						$.get(ajaxurl,{action:'wpas_get_form_html',app_id:app_id,form_id:form_id,type:type,count:count,field_count:field_count},function(response){
							$(resp_div).html(response);
						});
						break;
					}	
					var div_html = "<div id='"+ drag_id + "-" + field_count +"' class='"+ class_id + "'>";
					div_html += "<div class='row-fluid form-field-str'>";
					div_html += "<div class='span1 layout-edit-icons'>";
					if(drag_id != 'form-hr')
					{
						div_html += "<a class='edit'><i class='icon-edit pull-left'></i></a>";
					}
					div_html += "</div><div id='field-label1' class='span4'>" + type_val + "</div>";
					div_html += "<div id='field-label2' class='span3'></div>";
					div_html += "<div id='field-label3' class='span3'></div>";
					div_html += "<div class='span1 layout-edit-icons'>";
					div_html += "<a class='delete'><i class='icon-trash pull-right'></i></a></div></div>";
					if(drag_id == 'form-hr')
					{
						div_html += "<input type='hidden' id='form-hr-" + field_count + "' name='form-hr-" + field_count + "' value='1'>";
					}
					else
					{
						div_html += "<div id='" + drag_id + "-" + field_count + "-inside' class='form-inside' style='display:none;'></div></div>";
					}
					
					$(this).append(div_html);
					field_count++;
					$('#form-field-count').val(field_count);
				}
			}).sortable();
				
			
		}); 
	});
});
