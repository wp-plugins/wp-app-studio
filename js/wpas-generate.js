jQuery(document).ready(function($) {
		$(document).on('click','#clear-log.btn',function(event){
			$('#confirmdeleteModal').modal('show');
			return false;
		});
		$(document).on('click','button#delete-cancel',function(event){
                	$('#confirmdeleteModal').modal('hide');
			event.preventDefault();
        	});
		$(document).on('click','button#delete-ok',function(event){
			event.preventDefault();
                	$('#confirmdeleteModal').modal('hide');
			$.post(ajaxurl,{action:'wpas_clear_log_generate',nonce:wpas_vars.nonce_clear_log_generate}, function(response){
				document.location.href = response;
			});
		});
		$(document).on('click','#check-status.btn',function(){
			queue_id = $(this).attr('href').replace('#','');	
			tr_object = $(this).parent().parent();
			$.post(ajaxurl,{action:'wpas_check_status_generate',queue_id:queue_id,nonce:wpas_vars.nonce_check_status_generate}, function(response){
				if(response[0] != undefined)
				{
					if(response[1].search("Success") != -1 || response[1].search("Change") != -1)
					{
						if(response[0].search("freedev.s3") != -1){
							down_msg = wpas_vars.free_download;
							buy_msg = wpas_vars.free_buy;
						}
						else if(response[0].search("prodev.s3") != -1){
							down_msg = wpas_vars.pro_download;
							buy_msg = wpas_vars.pro_buy;
						}
						tr_object.find('#download-link').html('<a class="btn btn-success btn-mini" href="' + response[0] + '">' + down_msg + '</a>');	
						tr_object.find('#buy-link').html('<a class="btn btn-primary btn-mini" target="_blank" href="' + response[4] + '">' + buy_msg + '</a>');	
					}
					else if(response[1].search("Error") != -1)
					{
						tr_object.find('#download-link').html('<a class="btn btn-danger btn-mini" target="_blank" href="' + response[0] + '">' + wpas_vars.support_ticket + '</a>');				   
					}	
				}
				tr_object.find('#status').html(response[1]);	
				$('#status_info').hide();
				$('#status_error').hide();
				if(response[3])
				{
					$('#status_info').html(response[3]);
					$('#status_info').show();
				}
				if(response[2])
				{
					$('#status_error').html(response[2]);
					$('#status_error').show();
				}
			}, 'json');
		});
});
