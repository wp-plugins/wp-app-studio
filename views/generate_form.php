<?php
function wpas_generate_page($app_reg, $submits,$alert,$success)
{
	$apps_options = "";
	$submit_results = "";
	$message = "";
	$apps = wpas_get_app_list();
	foreach($apps as $key => $myapp)
	{
		$apps_options .= "<option value='" . $key . "'";
		if($_GET['app'] == $key)
		{
			$apps_options .= " selected";
		}
		$apps_options .= ">" .  $myapp['app_name'] . "</option>";
	}

	if($submits == "")
	{
		$total = 0;
		$submit_results = "<tr><td colspan=8>No application has been submitted.</td></tr>";
	}
	else
	{
		$total = count($submits);
		$page =1;
        	if(isset($_REQUEST['generatepage']))
        	{
                	$page = $_REQUEST['generatepage'];
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
				if($mysubmit['status_link'])
				{
					if(strpos($mysubmit['status'],'Error') != false)
					{	
						$mysubmit['status_link'] = "<a href='" . $mysubmit['status_link'] . "' target='_blank'>Please open a support ticket</a>";
					}
					elseif(strpos($mysubmit['status'],'Success') != false || strpos($mysubmit['status'],'Change') != false)
					{	
						$mysubmit['status_link'] = "<a href='" . $mysubmit['status_link'] . "'>Download</a>";
					}
				}
				$submit_results .= "<tr class='" . $alt . "'><td>" . $mysubmit['app_submitted'] . "</td><td id='credit-used'>" . $mysubmit['credit_used'] . "</td><td id='credit-left'>" . $mysubmit['credit_left'] . "</td><td id='update-left'>" . $mysubmit['update_left'] . "</td><td id='status'>" . $mysubmit['status'] . "</td><td id='download-link'>" . $mysubmit['status_link'] . "</p></td><td>" . $mysubmit['date_submit'] . "</td></tr>";
			}
                        $count ++;
			
		}
	}


	if($alert != "")
	{
		$message = "<div class='alert alert-error'>" . $alert . "</div>";
	}
	elseif($success == 1)
	{
		$message = "<div class='alert alert-success'>Your request has been submitted successfully.</div>";
	}

	?>
		<div id="was-container" class="container-fluid">
		<ul class="breadcrumb">
		<li id="first">
		<a href="<?php echo admin_url('admin.php?page=wpas_app_list'); ?>">
		<i class="icon-home"></i>Home</a>
		<span class="divider">/</span>
		</li>
		<li id="second" class="inactive">Generate</li>
		</ul>
		<div class="row-fluid">
		<div id="generate" class="span12">
		<form id="generate_form" class="form-horizontal" method="post" >
		<fieldset>
		<div class="well">
		<?php echo $message; ?>
		<div class="control-group">
		<label class="control-label">Email</label>
		<div class="controls">
		<input id="email" name="email" class="input-large" type="text" value="<?php echo $app_reg['email']; ?>">
		</div>
		</div>
		<div class="control-group">
		<label class="control-label">Passcode</label>
		<div class="controls">
		<input id="passcode" name="passcode" class="input-large" type="password" value="<?php echo $app_reg['passcode']; ?>">
		</div>
		</div>
		<div class="control-group">
		<label class="control-label">Applications</label>
		<div class="controls">
		<select id="app" name="app">
		<?php echo $apps_options; ?>
		</select>
		</div>
		</div>
		<div class="control-group">
		<div class="controls">
		<input id="save_passcode" name="save_passcode" type="checkbox" value="1">
		Save Passcode
		</div>
		</div>
		<div id="frm-btn" class="control-group">
		<button id="generate" class="btn  btn-primary pull-right" type="submit">
		<i class="icon-play"></i>Generate</button>
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
		<div class="row-fluid">
		<div class="tablenav top">
		<div class="tablenav-pages one-page">
		<?php 
		if($total > 0)
		{
			$base = "admin.php?page=wpas_app_list&generate=1&app=" . $_GET['app'];
			echo $paging_text = paginate_links( array(
								'total' => ceil($total/10),
								'current' => $page,
								'base' => $base .'&%_%',
								'format' => 'generatepage=%#%',
					) );
		}
		?>
		</div>
		</div>
		<table class="wp-list-table widefat plugins" cellspacing="0">
		<thead><tr>
		<th>Application Name</th>
		<th>Credit Used</th>
		<th>Balance</th>
		<th>Update Balance</th>
		<th>Status</th>
		<th>Status Link</th>
		<th>Submit Date</th>
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
?>
