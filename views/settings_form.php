<?php
function wpas_add_app_option($app_name)
{
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$.fn.changeTheme = function (theme_name) {
                new_url = 'http://jqueryui.com/resources/images/themeGallery/theme_90_';
                if(theme_name == 'mint-choc')
                {
                        theme_name = 'mint_choco';
                }
                else if(theme_name == 'vader')
                {
                        theme_name = 'black_matte';
                }
                else if(theme_name == 'redmond')
                {
                        theme_name = 'windoze';
                }
                else if(theme_name == 'start')
                {
                        theme_name = 'start_menu';
                }
                else if(theme_name == 'ui-darkness')
                {
                        theme_name = 'ui_dark';
                }
                else if(theme_name == 'ui-lightness')
                {
                        theme_name = 'ui_light';
                }
                else
                {
                        theme_name = theme_name.replace('-','_');
                }
                new_url += theme_name;
                new_url += '.png';
                $('#theme_url').attr("src",new_url);
                $('#theme_url').load();
	}
        $('#ao_modify_navigation_menus').click(function() {
                if($(this).attr('checked'))
                {
			$('#support-cust-nav-div').show();
                }
                else
                {
			$('#support-cust-nav-div').hide();
                }
        });
        $('#ao_force_dashboard_to_column').click(function() {
                if($(this).attr('checked'))
                {
			$('#ao_force_col_div').show();
                }
                else
                {
			$('#ao_force_col_div').hide();
                }
        });
        $('#ao_set_uitheme').click(function() {
                if($(this).attr('checked'))
                {
			$('#ao_theme_type_div').show();
                }
                else
                {
			$('#ao_theme_type_div').hide();
			$('#ao_theme_type').val('');
                }
        });
	$('#ao_theme_type').click(function() {
                theme_name = $(this).find('option:selected').val();
		$(this).changeTheme(theme_name);
        }); 
});
</script>
	<div class="row-fluid" style="display:none;" id="edit-btn-div">
	<div id="app-edit-btn">
        <button class="btn  btn-primary pull-right" id="edit-option" name="Edit" type="submit" href="#">
        <i class="icon-edit"></i><?php _e("Edit","wpas"); ?></button>
        </div>
	</div>
	<form action="" method="post" id="option-form" class="form-horizontal">
	<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab"><?php _e("App Info","wpas"); ?></a></li>
		<li><a href="#tab3" data-toggle="tab"><?php _e("Navigation","wpas"); ?></a></li>
		<li><a href="#tab4" data-toggle="tab"><?php _e("Dashboards","wpas"); ?></a></li>
		<li><a href="#tab5" data-toggle="tab"><?php _e("Toolbar","wpas"); ?></a></li>
		<li><a href="#tab6" data-toggle="tab"><?php _e("Login Screen","wpas"); ?></a></li>
		<li><a href="#tab7" data-toggle="tab"><?php _e("Footer","wpas"); ?></a></li>
		<li><a href="#tab8" data-toggle="tab"><?php _e("Mail","wpas"); ?></a></li>
		<li><a href="#tab9" data-toggle="tab"><?php _e("Misc","wpas"); ?></a></li>
	</ul>
	<input type="hidden" value="" name="app" id="app">	
	<div class="tab-content">
	<div class="row-fluid"><div class="alert alert-info pull-right"><i class="icon-info-sign"></i><a data-placement="bottom" href="#" rel="tooltip" title="<?php _e("Settings page offers quick configuration of some of the features of your application.","wpas"); ?>"> <?php _e("HELP","wpas"); ?></a></div></div>

			<div class="tab-pane active" id="tab1">
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Textdomain","wpas"); ?></label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_plugin_name" id="ao_plugin_name" type="text" placeholder="<?php _e("e.g. sim-ent","wpas"); ?>" value="" >
								 <a href="#" style="cursor: help;" title="<?php _e("Set a unique textdomain for your app. Textdomains are used in translations and plugin implementation. It can contain only letters and dashes, not more than 10 chars.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Site URL","wpas"); ?></label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_domain" id="ao_domain" type="text" placeholder="<?php _e("e.g. http://example.com","wpas"); ?>" value="" >
								 <a href="#" style="cursor: help;" title="<?php _e("Enter your the URL of site starting with http://..","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3"><?php _e("Site Name","wpas"); ?></label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_blog_name" id="ao_blog_name" type="text" placeholder="<?php _e("e.g. My WordPress Blog","wpas");?>" value="" >
								<a href="#" style="cursor: help;" title="<?php _e("Enter your blog's title.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</div>
					</div>	
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Application Short Desc","wpas"); ?></label>
							 <div class="controls span8">
								 <textarea class="wpas-std-textarea" name="ao_app_sdesc" id="ao_app_sdesc" placeholder="<?php _e("e.g. Product List Application","wpas"); ?>" value="" ></textarea>
								 <a href="#" style="cursor: help;" title="<?php _e("Enter a short description of the application","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Application Desc","wpas"); ?></label>
							 <div class="controls span8">
								 <textarea class="wpas-std-textarea" name="ao_app_desc" id="ao_app_desc" value="" ></textarea>
								 <a href="#" style="cursor: help;" title="<?php _e("Enter a description of the application","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Application Version","wpas"); ?></label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_app_version" id="ao_app_version" type="text" placeholder="<?php _e("e.g 1.0.0","wpas"); ?>" value="" >
								 <a href="#" style="cursor: help;" title="<?php _e("Enter the application's version number.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Author","wpas"); ?></label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_author" id="ao_author" type="text" placeholder="<?php _e("Name Of The Plugin Author","wpas"); ?>" value="" >
								 <a href="#" style="cursor: help;" title="<?php _e("Name of the application author.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Author Site Url","wpas"); ?></label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_author_url" id="ao_author_url" type="text" placeholder="<?php _e("e.g. http://example.com","wpas"); ?>" value="" >
								 <a href="#" style="cursor: help;" title="<?php _e("URI of the application author.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>                  
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3 req"><?php _e("Change Log","wpas"); ?></label>
							 <div class="controls span8">
								 <textarea class="wpas-std-textarea" name="ao_change_log" id="ao_change_log" placeholder="= 1.0.0 =" value="" ></textarea>
								 <a href="#" style="cursor: help;" title="<?php _e("Enter a brief description for changes in your app version.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
			</div>
			<div class="tab-pane" id="tab3">
					<div class="row-fluid">
					<label class="control-label span1"></label>
					<div class="control-group">
							<label class="checkbox"><?php _e("Customize Navigation menus","wpas"); ?>
									<input type="checkbox" name="ao_modify_navigation_menus" id="ao_modify_navigation_menus" value="1">
									<a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove menus.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					
					<div id="sub-controls" class="row-fluid">
					<label class="control-label span1"></label>
					<div class="controls span7">
					<div id="support-cust-nav-div" style="display:none;">
					<div class="control-group" id="ao_dashboard_on_div">
							<label class="checkbox span8"><?php _e("Display Dashboard menu","wpas"); ?>
									<input type="checkbox" name="ao_dashboard_on" id="ao_dashboard_on" value="1">
								  <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the dashboard menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_posts_on_div">
							<label class="checkbox span8"><?php _e("Display Posts menu","wpas"); ?>
									<input type="checkbox" name="ao_posts_on" id="ao_posts_on" value="1">
								  <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the posts menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_media_on_div">
							<label class="checkbox span8"><?php _e("Display Media menu","wpas"); ?>
									<input type="checkbox" name="ao_media_on" id="ao_media_on" value="1">
								  <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the media menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_links_on_div">
							<label class="checkbox span8"><?php _e("Display Links menu","wpas"); ?>
									<input type="checkbox" name="ao_links_on" id="ao_links_on" value="1">
								  <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the links menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_pages_on_div">
							<label class="checkbox span8"><?php _e("Display Pages menu","wpas"); ?>
									<input type="checkbox" name="ao_pages_on" id="ao_pages_on" value="1">
								  <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the pages menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_appearance_on_div">
							<label class="checkbox span8"><?php _e("Display Appearance menu","wpas"); ?>
										<input type="checkbox" name="ao_appearance_on" id="ao_appearance_on" value="1">
								  <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the appearance menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_tools_on_div">
							<label class="checkbox span8"><?php _e("Display Tools menu","wpas"); ?>
										<input type="checkbox" name="ao_tools_on" id="ao_tools_on" value="1">
								   <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the tools menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_users_on_div">
							<label class="checkbox span8"><?php _e("Display Users menu","wpas"); ?>
										<input type="checkbox" name="ao_users_on" id="ao_users_on" value="1">
								   <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the users menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_comments_on_div">
							<label class="checkbox span8"><?php _e("Display Comments menu","wpas"); ?>
										  <input type="checkbox" name="ao_comments_on" id="ao_comments_on" value="1">
								   <a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove the comments menu.","wpas"); ?>">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
				</div>
				
				</div>
				</div>
				</div>
			</div>
			<div class="tab-pane" id="tab4">	
				<div class="row-fluid">
				<label class="control-label span1"></label>
						<div class="controls span8">
						<div class="control-group row-fluid">
					<label class="checkbox"><?php _e("Remove all default dashboard widgets","wpas"); ?>
							<input type="checkbox" name="ao_remove_std_dashboard_widgets" id="ao_remove_std_dashboard_widgets" value="1">
					 	 <a href="#" style="cursor: help;" title="<?php _e("Allows to remove all the standard dashboard widgets.","wpas"); ?>">
						<i class="icon-info-sign"></i></a>
					 </label>
				</div>
				<div class="control-group row-fluid">
						 <label class="checkbox"><?php _e("Force the Number of Columns in Dashboard","wpas"); ?>
						<input type="checkbox" name="ao_force_dashboard_to_column" id="ao_force_dashboard_to_column" value="1">
					  	<a href="#" style="cursor: help;" title="<?php _e("Forces the number of columns in the admin dashboard widgets.","wpas"); ?>">
						<i class="icon-info-sign"></i></a>
					  </label>
				</div>
				<div class="row-fluid  control-group" id="ao_force_col_div" style="display:none;">
					 
							<select id="ao_force_dash_column_num" name="ao_force_dash_column_num" class="input-medium">
							<option value=""><?php _e("Please select","wpas"); ?></option>
							<option value="1"><?php _e("1 column","wpas"); ?></option>
							<option value="2"><?php _e("2 columns","wpas"); ?></option>
							<option value="3"><?php _e("3 columns","wpas"); ?></option>
							<option value="4"><?php _e("4 columns","wpas"); ?></option>
							<option value="5"><?php _e("5 columns","wpas"); ?></option>
							</select>
					<a href="#" style="cursor: help;" title="<?php _e("Select the number of columns in the admin dashboard widgets.","wpas"); ?>"><i class="icon-info-sign"></i></a>
				</div>			
				</div> <!--controls -->
				</div><!--row -->	            
			</div><!--tab -->	
			<div class="tab-pane" id="tab5">
				<div class="row-fluid">
				<label class="control-label span1"></label>
					<div class="controls span8">
					<div class="control-group row-fluid">
						<label class="control-label span3"><?php _e("Modify Toolbar ","wpas"); ?></label>
						<div class="span8">
							<select id="ao_modify_admin_bar" name="ao_modify_admin_bar" class="input-large">
							<option value=""><?php _e("Please select","wpas"); ?></option>
							<option value="1"><?php _e("Remove both toolbars","wpas"); ?></option>
							<option value="2"><?php _e("Remove backend toolbar","wpas"); ?></option>
							<option value="3"><?php _e("Remove frontend toolbar","wpas"); ?></option>
							<option value="4"><?php _e("Remove all standard admin toolbar menus","wpas"); ?></option>
							</select>
							<a href="#" style="cursor: help;" title="<?php _e("Remove both toolbars: Allows to remove admin toolbar from the backend and the front side of your website. Remove backend toolbar: Allows to remove admin toolbar from the backend. Remove frontend toolbar: Allows to remove admin toolbar from the front side of your website. Remove all standard admin toolbar menus: Allows to remove all default admin toolbar menus.","wpas"); ?>"><i class="icon-info-sign"></i></a>
						</div>	
					</div>
				</div><!--controls -->
			</div><!--row -->	 		
		</div><!--tab -->
		<div class="tab-pane" id="tab6">
				<div class="control-group row-fluid">
					<label class="control-label span3"><?php _e("Login logo image url","wpas"); ?></label>
					<div class="controls span8">
							<input class="input-xlarge" name="ao_login_logo_url" id="ao_login_logo_url" type="text" placeholder="<?php _e("http://path-to-my-application-logo","wpas"); ?>" value="" >
							<a href="#" style="cursor: help;" title="<?php _e("Enter login logo image url for the application. It is displayed above the login box. For best results, use an image that is less than 326 pixels wide.","wpas"); ?>">
							<i class="icon-info-sign"></i></a>
					</div>
				</div>
				<div class="control-group row-fluid">
					<label class="control-label span3"><?php _e("Toolbar image url","wpas"); ?></label>
					<div class="controls span8">
				<input class="input-xlarge" name="ao_admin_logo_url" id="ao_admin_logo_url" type="text" placeholder="<?php _e("http://path-to-my-application-toolbar-logo","wpas"); ?>" value="" >
						<a href="#" style="cursor: help;" title="<?php _e("Enter toolbar image url for the application. It is displayed in the admin toolbar. For best results, use an image that is 20x20 pixels.","wpas"); ?>">
						<i class="icon-info-sign"></i></a>
					</div>
				</div>
				
		</div>
		<div class="tab-pane" id="tab7">
			<div class="row-fluid">
				<div class="control-group row-fluid span6">
					<label class=""><?php _e("Left footer","wpas"); ?></label>
					 <div class="controls span8">
					<textarea class="wpas-std-textarea" id="ao_left_footer_html" name="ao_left_footer_html"></textarea>
					 <a href="#" style="cursor: help;" title="<?php _e("Displays a message in the left hand side of the backend footer.","wpas"); ?>">
						<i class="icon-info-sign"></i></a>
 	</div></div>

				<div class="control-group row-fluid span6">
					<label class=""><?php _e("Right footer","wpas"); ?></label>
					 <div class="controls span8">
					<textarea class="wpas-std-textarea" id="ao_right_footer_html" name="ao_right_footer_html"></textarea>
					 <a href="#" style="cursor: help;" title="<?php _e("Displays a short message such as application version number in the right hand side of the backend footer.","wpas"); ?>">
						<i class="icon-info-sign"></i></a>
					 </div>
				</div></div>
		</div>
		<div class="tab-pane" id="tab8">
				<div class="control-group row-fluid">
					<label class="control-label span3"><?php _e("Mail FROM email address","wpas"); ?></label>
					 <div class="controls span8">
						 <input class="input-xlarge" name="ao_mail_from_email" id="ao_mail_from_email" type="text" placeholder="<?php _e("e.g. info@example.com","wpas"); ?>" value="" >
						 <a href="#" style="cursor: help;" title="<?php _e("Sets the FROM email address for the application wide emails","wpas"); ?>">
						<i class="icon-info-sign"></i></a>
					 </div>
				</div>
				<div class="control-group row-fluid">
					<label class="control-label span3"><?php _e("Mail FROM name","wpas"); ?></label>
					 <div class="controls span8">
						 <input class="input-xlarge" name="ao_mail_from_name" id="ao_mail_from_name" type="text" placeholder="<?php _e("e.g. Webmaster","wpas"); ?>" value="" >
						 <a href="#" style="cursor: help;" title="<?php _e("Sets the name of the sender for the application wide emails.","wpas"); ?>">
						<i class="icon-info-sign"></i></a>
					 </div>
				</div>
		</div>
		<div class="tab-pane" id="tab9">
		<div class="row-fluid">
		<label class="control-label span1"></label>
		<div class="control-group">
			<label class="checkbox"><?php _e("Remove Filters","wpas"); ?>
			<input type="checkbox" name="ao_remove_colfilter" id="ao_remove_colfilter" value="1">
			<a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove filters and columns component.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
			</label>
		</div>
		</div>
		<div class="row-fluid">
		<label class="control-label span1"></label>
		<div class="control-group">
			<label class="checkbox"><?php _e("Remove Operations","wpas"); ?>
			<input type="checkbox" name="ao_remove_operations" id="ao_remove_operations" value="1">
			<a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove operations page and its button.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
			</label>
		</div>
		</div>
		<div class="row-fluid">
		<label class="control-label span1"></label>
		<div class="control-group">
			<label class="checkbox"><?php _e("Remove Anayltics","wpas"); ?>
			<input type="checkbox" name="ao_remove_analytics" id="ao_remove_analytics" value="1">
			<a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove analytics component.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
			</label>
		</div>
		</div>
		<div class="row-fluid">
		<label class="control-label span1"></label>
		<div class="control-group">
			<label class="checkbox"><?php _e("Set jQuery UI Theme","wpas"); ?>
			<input type="checkbox" name="ao_set_uitheme" id="ao_set_uitheme" value="1">
			<a href="#" style="cursor: help;" title="<?php _e("Allows to display or remove menus.","wpas"); ?>">
			<i class="icon-info-sign"></i></a>
			</label>
		</div>
		</div>
		<div class="row-fluid" id="ao_theme_type_div" style="display:none;">
		<div class="span1"></div>
		<div class="span10">
			<table style="background-color:transparent !important;">
              <tr><td><?php _e("Theme Type","wpas"); ?></td>
                      <td> <select name="ao_theme_type" id="ao_theme_type">
                        <option value="" selected="selected"><?php _e("Please select","wpas"); ?></option>
                        <option value="smoothness"><?php _e("Smoothness","wpas"); ?></option>
                        <option value="ui-lightness"><?php _e("UI lightness","wpas"); ?></option>
                        <option value="ui-darkness"><?php _e("UI darkness","wpas"); ?></option>
                        <option value="start"><?php _e("Start","wpas"); ?></option>
                        <option value="redmond"><?php _e("Redmond","wpas"); ?></option>
                        <option value="sunny"><?php _e("Sunny","wpas"); ?></option>
                        <option value="overcast"><?php _e("Overcast","wpas"); ?></option>
                        <option value="le-frog"><?php _e("Le Frog","wpas"); ?></option>
                        <option value="flick"><?php _e("Flick","wpas"); ?></option>
                        <option value="pepper-grinder"><?php _e("Pepper Grinder ","wpas"); ?></option>
                        <option value="eggplant"><?php _e("Eggplant","wpas"); ?></option>
                        <option value="dark-hive"><?php _e("Dark Hive","wpas"); ?></option>
                        <option value="cupertino"><?php _e("Cupertino","wpas"); ?></option>
                        <option value="south-street"><?php _e("South Street","wpas"); ?></option>
                        <option value="blitzer"><?php _e("Blitzer","wpas"); ?></option>
                        <option value="humanity"><?php _e("Humanity","wpas"); ?></option>
                        <option value="hot-sneaks"><?php _e("Hot Sneaks","wpas"); ?></option>
                        <option value="excite-bike"><?php _e("Excite Bike","wpas"); ?></option>
                        <option value="vader"><?php _e("Vader","wpas"); ?></option>
                        <option value="dot-luv"><?php _e("Dot Luv","wpas"); ?></option>
                        <option value="mint-choc"><?php _e("Mint Choc","wpas"); ?></option>
                        <option value="black-tie"><?php _e("Black Tie","wpas"); ?></option>
                        <option value="trontastic"><?php _e("Trontastic","wpas"); ?></option>
                        <option value="swanky-purse"><?php _e("Swanky Purse","wpas"); ?></option>
                        </select>
                        <a href="#" style="cursor: help;" title="<?php _e("Allows to set a jQuery UI theme for the frontend and backend.","wpas"); ?>">
                        <i class="icon-info-sign"></i> </a></td>
                        <?php //<td><img id="theme_url" name="theme_url" src="http://jqueryui.com/resources/images/themeGallery/theme_90_smoothness.png"></td> ?>
                        </tr>
            </table>
           </div>
           </div>
	</div>
	</div>
</div>
	<div class="control-group row-fluid">
                   <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle">
</i><?php _e("Cancel","wpas"); ?></button>
                   <button class="btn  btn-primary pull-right layout-buttons" id="update-option" type="submit" value="Update">
                   <i class="icon-save"></i><?php _e("Update","wpas"); ?></button>
    </div>

</form>
<?php
}
?>
