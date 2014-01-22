jQuery(document).ready(function() {
        var $ = jQuery;
	$(document).on('click','div.pagination a',function(){
		$(this).checkColumn();
		pageType = "";
		pageNum = 1;
		pagelink = $(this).attr('href');
		//don't execute in app_list page
		if(pagelink.search(/app_list/i) == -1)
		{
			pagelinkVars = pagelink.split("&");
			for(i=0;i<pagelinkVars.length;i++)
			{
				paramArr = pagelinkVars[i].split('=');
				if(paramArr[0] == 'view')
				{
					pageType = paramArr[1];
				}
				if(pageType != '' && paramArr[0] == pageType + 'page')
				{
					pageNum = paramArr[1];
				}
				if(paramArr[0] == 'app')
				{
					appId = paramArr[1];
				}
				
			}
			if(pageType == 'widg')
			{
				pageType = 'widget';
			}
			$('#list-'+pageType).empty();
			$.get(ajaxurl,{action:'wpas_list_all',type:pageType,app_id:appId,page:pageNum}, function(response)
			{
				$('#list-'+pageType).html(response);
			});
			$('#list-'+pageType).show(); 
			return false;
		}
	});
});

