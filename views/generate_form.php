<?php
function wpas_generate_page()
{
	wpas_generate_app();
}	
function wpas_display_generate_page($email, $submits,$alert,$success)
{
	$apps_options = "<option value=''>" . __("Please select","wpas") . "</option>";
	$submit_results = "";
	$message = "";
	$apps = wpas_get_app_list();
	if(!empty($apps))
	{
		foreach($apps as $key => $myapp)
		{
			$apps_options .= "<option value='" . esc_attr($key) . "'";
			if(!empty($_REQUEST['app']) && $_REQUEST['app'] == $key)
			{
				$apps_options .= " selected";
			}
			$apps_options .= ">" .  esc_html($myapp['app_name']) . "</option>";
		}
	}

	if(empty($submits))
	{
		$total = 0;
		$submit_results = "<tr><td colspan=8>" . __("No application has been submitted.","wpas") . "</td></tr>";
	}
	else
	{
		$total = count($submits);
		$page =1;
        	if(isset($_REQUEST['generatepage']))
        	{
                	$page = intval ($_REQUEST['generatepage']);
        	}
		$count =0;
		$submits = array_reverse($submits);
		foreach($submits as $mysubmit)
		{
		 	if($count < ($page * 10) && $count >= ($page-1)*10)
			{
                                $alt = "";
                                if($count %2)
                                {
                                        $alt = "alternate";
                                }
				if(isset($mysubmit['status_link']))
				{
					if(strpos($mysubmit['status'],'Error') !== false)
					{	
						$mysubmit['status_link'] = "<a class='btn btn-danger btn-mini' href='" . esc_url($mysubmit['status_link']) . "' target='_blank'>" . __("Open a support ticket","wpas") . "</a>";
					}
					elseif(strpos($mysubmit['status'],'Success') !== false || strpos($mysubmit['status'],'Change') !== false)
					{	
						if(preg_match("/freedev.s3/",$mysubmit['status_link'])){
							$down_msg = __("Download FreeDev version","wpas");
							$buy_msg = __("Purchase ProDev version","wpas");
						}
						elseif(preg_match('/prodev.s3/',$mysubmit['status_link'])){
							$down_msg = __("Download ProDev version","wpas");
							$buy_msg = __("Upgrade your license","wpas");
						}
						$mysubmit['status_link'] = "<a class='btn btn-success btn-mini' href='" . esc_url($mysubmit['status_link']) . "'>" . $down_msg . "</a>";
						if(!empty($mysubmit['buy_link'])){
							$mysubmit['buy_link'] = "<a class='btn btn-primary btn-mini' target='_blank' href='" . esc_url($mysubmit['buy_link']) . "'>" . $buy_msg . "</a>";
						}
					}
				}
				$submit_results .= "<tr class='" . $alt . "'><td>" . esc_html($mysubmit['app_submitted']) . "</td>
				<td id='status'>" . $mysubmit['status'] . "</td>
				<td id='download-link'>" . $mysubmit['status_link'] . "</p></td>
				<td id='buy-link'>";
				if(!empty($mysubmit['buy_link'])){ 
					$submit_results .= $mysubmit['buy_link'];
				}
				$submit_results .= "</p></td>
				<td>" . esc_html($mysubmit['date_submit']) . "</td></tr>";
			}
                        $count ++;
			
		}
	}


	if(!empty($alert))
	{
		$message = "<div class='alert alert-error'>" . $alert . "</div>";
	}
	elseif($success == 1)
	{
		$message = "<div class='alert alert-success'>" . __("We have received your app and started the generation process. It could take 5-10 mins depending on the load. Please come back and check the generation status by clicking on the Refresh button below.","wpas") . "</div>";
	}

	?>
		<div id="was-container" class="container-fluid">
		<ul class="breadcrumb">
		<li id="first">
		<a href="<?php echo admin_url('admin.php?page=wpas_app_list'); ?>">
		<i class="icon-home"></i><?php _e("Home","wpas"); ?></a>
		<span class="divider">/</span>
		</li>
		<li id="second" class="inactive"><?php _e("Generate","wpas"); ?></li>
		</ul>
		<div class="row-fluid">
		<div id="generate" class="span12">
		<form id="generate_form" class="form-horizontal" method="post" >
		<fieldset>
		<?php wp_nonce_field('wpas_generate_app','wpas_generate_nonce'); ?>
		<div class="well">
		<div class="alert"><h3>
		<?php printf(__('<a href="%s" target="_blank">Open a FreeDev account</a> to hop on WP App Studio 4 AUTOBAHN.','wpas'),'https://wpas.emdplugins.com/wpas-freedev-signup/?pk_campaign=wpas&pk_source=plugin&pk_medium=link&pk_content=open-freedev'); ?>
		</h3>
		<p><h3><small>
		<?php _e('If you have one just enter your email and click generate below.','wpas'); ?>
		</small></h3></p></div>
		<?php echo $message; ?>
		<div class="control-group">
		<label class="control-label"><?php _e("Email","wpas"); ?></label>
		<div class="controls">
		<input id="email" name="email" class="input-large" type="text" value="<?php if(isset($email)) { echo $email; }?>">
		</div>
		</div>
		<div class="control-group">
		<label class="control-label"><?php _e("Apps","wpas"); ?></label>
		<div class="controls">
		<select id="app" name="app">
		<?php echo $apps_options; ?>
		</select>
		</div>
		</div>
		<div id="frm-btn" class="control-group">
		<label class="control-label"></label>
		<div class="controls">
		<button id="generate" class="btn btn-primary btn-large" type="submit">
		<i class="icon-play"></i><?php _e("Generate","wpas"); ?></button>
		</div>
		</div>
		</div>
		</fieldset>
		</div>
		</div>
		<div class="row-fluid">
		<div id='status_info' class='alert alert-info span12' style='display:none;'></div>
		</div>
		<div class="row-fluid">
		<div id='status_error' class='alert alert-error span12' style='display:none;'></div>
		</div>
		<?php wpas_modal_confirm_delete(1); ?>
		<div class="row-fluid">
		<div class="tablenav top">
		<div class="alignleft actions">
		<a id="clear-log" class="btn btn-danger" href="" title="<?php _e("Clear Log","wpas"); ?>"><?php _e("Clear Log History","wpas"); ?></a>
		</div>
		<div class="pagination pagination-right">
		<?php 
		if($total > 0)
		{
			if(!empty($_GET['app'])){
				$base = admin_url('admin.php?page=wpas_generate_page&app=' . $_GET['app']);
			}
			else {
				$base = admin_url('admin.php?page=wpas_generate_page');
			}

			$paging = paginate_links( array(
						'total' => ceil($total/10),
						'current' => $page,
						'base' => $base .'&%_%',
						'format' => 'generatepage=%#%',
						'type' => 'array',
					) );
			$paging_html = "<ul>";
			if(!empty($paging))
			{
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
			
			echo $paging_html;			
		}
		?>
		</div>
		</div>
		<table class="table table-striped table-condensed table-bordered" cellspacing="0">
		<thead><tr class="theader">
		<th><?php _e("App Name","wpas"); ?></th>
		<th><?php _e("Status","wpas"); ?></th>
		<th><?php _e("Status Link","wpas"); ?></th>
		<th><?php _e("Purchase Link","wpas"); ?></th>
		<th><?php _e("Submit Date","wpas"); ?></th>
		</tr>
		</thead>
		<tbody id="the-list">
		<?php echo $submit_results; ?>
		</tbody>
		</table>
		</div>
		</div>
		</form>
		<?php
}
