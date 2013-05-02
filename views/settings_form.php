<?php
function wpas_add_app_option()
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery.fn.changeTheme = function (theme_name) {
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
                jQuery('#theme_url').attr("src",new_url);
                jQuery('#theme_url').load();
	}
        jQuery('#ao_modify_navigation_menus').click(function() {
                if(jQuery(this).attr('checked'))
                {
			jQuery('#support-cust-nav-div').show();
                }
                else
                {
			jQuery('#support-cust-nav-div').hide();
                }
        });
        jQuery('#ao_force_dashboard_to_column').click(function() {
                if(jQuery(this).attr('checked'))
                {
			jQuery('#ao_force_col_div').show();
                }
                else
                {
			jQuery('#ao_force_col_div').hide();
                }
        });

	jQuery('#ao_theme_type').click(function() {
                theme_name = jQuery(this).find('option:selected').val();
		jQuery(this).changeTheme(theme_name);
        }); 
});
</script>
	<div class="row-fluid" style="display:none;" id="edit-btn-div">
	<div id="app-edit-btn">
        <button class="btn  btn-primary pull-right" id="edit-option" name="Edit" type="submit" href="#">
        <i class="icon-edit"></i>Edit</button>
        </div>
	</div>
	<form action="" method="post" id="option-form" class="form-horizontal">
	<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">App Info</a></li>
		<li><a href="#tab2" data-toggle="tab">Navigation</a></li>
		<li><a href="#tab3" data-toggle="tab">Dashboards</a></li>
		<li><a href="#tab4" data-toggle="tab">Toolbar</a></li>
		<li><a href="#tab5" data-toggle="tab">Login Screen</a></li>
		<li><a href="#tab6" data-toggle="tab">Theme</a></li>
		<li><a href="#tab7" data-toggle="tab">Footer</a></li>
		<li><a href="#tab8" data-toggle="tab">Mail</a></li>
	</ul>
	<input type="hidden" value="" name="app" id="app">	
	<div class="tab-content">
	<div class="row-fluid"><div class="alert alert-info pull-right"><a class="icon-info-sign" data-placement="bottom" href="#" rel="tooltip" title="Settings page offers quick configuration of some of the features of your application."> HELP</a></div></div>

			<div class="tab-pane active" id="tab1">
					<div class="control-group row-fluid">
							<label class="control-label span3">Domain Name</label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_domain" id="ao_domain" type="text" placeholder="e.g. example.com" value="" >
								 <a href="#" style="cursor: help;" title="Enter your domain name. ">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3">Site Name</label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_blog_name" id="ao_blog_name" type="text" placeholder="e.g. My WordPress Blog" value="" >
								<a href="#" style="cursor: help;" title="Enter your blog's title. ">
								<i class="icon-info-sign"></i></a>
							</div>
					</div>	
					<div class="control-group row-fluid">
							<label class="control-label span3">Application Desc</label>
							 <div class="controls span8">
								 <textarea class="input-xlarge" rows="4" name="ao_app_desc" id="ao_app_desc" placeholder="e.g. Product List Application" value="" ></textarea>
								 <a href="#" style="cursor: help;" title="Enter a brief description of the application">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3">Application Version</label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_app_version" id="ao_app_version" type="text" placeholder="e.g 1.0.0" value="" >
								 <a href="#" style="cursor: help;" title="Enter the application's version number.">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3">Author</label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_author" id="ao_author" type="text" placeholder="Name Of The Plugin Author" value="" >
								 <a href="#" style="cursor: help;" title="Name of the application author.">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3">Author Site Url</label>
							 <div class="controls span8">
								 <input class="input-xlarge" name="ao_author_url" id="ao_author_url" type="text" placeholder="e.g. http://example.com" value="" >
								 <a href="#" style="cursor: help;" title="URI of the application author.">
								<i class="icon-info-sign"></i></a>
							 </div>                  
					</div>
					<div class="control-group row-fluid">
							<label class="control-label span3">License Text</label>
							 <div class="controls span8">
								 <textarea class="input-xlarge" rows="10" name="ao_license_text" id="ao_license_text" readonly placeholder="This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.&#013 &#013 This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.&#013 &#013You should have received a copy of the GNU General Public License  along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA"></textarea>
								 <a href="#" style="cursor: help;" title="information about licensing for the application">
								<i class="icon-info-sign"></i></a>
							 </div>
					</div>
			</div>
			<div class="tab-pane" id="tab2">
					<div class="row-fluid">
					<label class="control-label span1"></label>
					<div class="control-group">
							<label class="checkbox">Customize Navigation menus
									<input type="checkbox" name="ao_modify_navigation_menus" id="ao_modify_navigation_menus" value="1">
									<a href="#" style="cursor: help;" title="Allows to display or remove menus.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					
					<div id="sub-controls" class="row-fluid">
					<label class="control-label span1"></label>
					<div class="controls span7">
					<div id="support-cust-nav-div" style="display:none;">
					<div class="control-group" id="ao_dashboard_on_div">
							<label class="checkbox span8">Display Dashboard menu
									<input type="checkbox" name="ao_dashboard_on" id="ao_dashboard_on" value="1">
								  <a href="#" style="cursor: help;" title="Allows to display or remove the dashboard menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_posts_on_div">
							<label class="checkbox span8">Display Posts menu
									<input type="checkbox" name="ao_posts_on" id="ao_posts_on" value="1">
								  <a href="#" style="cursor: help;" title="Allows to display or remove the posts menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_media_on_div">
							<label class="checkbox span8">Display Media menu
									<input type="checkbox" name="ao_media_on" id="ao_media_on" value="1">
								  <a href="#" style="cursor: help;" title="Allows to display or remove the media menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_links_on_div">
							<label class="checkbox span8">Display Links menu
									<input type="checkbox" name="ao_links_on" id="ao_links_on" value="1">
								  <a href="#" style="cursor: help;" title="Allows to display or remove the links menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_pages_on_div">
							<label class="checkbox span8">Display Pages menu
									<input type="checkbox" name="ao_pages_on" id="ao_pages_on" value="1">
								  <a href="#" style="cursor: help;" title="Allows to display or remove the pages menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_appearance_on_div">
							<label class="checkbox span8">Display Appearance menu
										<input type="checkbox" name="ao_appearance_on" id="ao_appearance_on" value="1">
								  <a href="#" style="cursor: help;" title="Allows to display or remove the appearance menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_tools_on_div">
							<label class="checkbox span8">Display Tools menu
										<input type="checkbox" name="ao_tools_on" id="ao_tools_on" value="1">
								   <a href="#" style="cursor: help;" title="Allows to display or remove the tools menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_users_on_div">
							<label class="checkbox span8">Display Users menu
										<input type="checkbox" name="ao_users_on" id="ao_users_on" value="1">
								   <a href="#" style="cursor: help;" title="Allows to display or remove the users menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
					<div class="control-group row-fluid" id="ao_comments_on_div">
							<label class="checkbox span8">Display Comments menu
										  <input type="checkbox" name="ao_comments_on" id="ao_comments_on" value="1">
								   <a href="#" style="cursor: help;" title="Allows to display or remove the comments menu.">
								<i class="icon-info-sign"></i></a>
							</label>
					</div>
				</div>
				
				</div>
				</div>
				</div>
			</div>
			<div class="tab-pane" id="tab3">	
				<div class="row-fluid">
				<label class="control-label span1"></label>
						<div class="controls span8">
						<div class="control-group row-fluid">
					<label class="checkbox">Remove all default dashboard widgets
							<input type="checkbox" name="ao_remove_std_dashboard_widgets" id="ao_remove_std_dashboard_widgets" value="1">
					 	 <a href="#" style="cursor: help;" title="Allows to remove all the standard dashboard widgets.">
						<i class="icon-info-sign"></i></a>
					 </label>
				</div>
				<div class="control-group row-fluid">
						 <label class="checkbox">Force the Number of Columns in Dashboard
						<input type="checkbox" name="ao_force_dashboard_to_column" id="ao_force_dashboard_to_column" value="1">
					  	<a href="#" style="cursor: help;" title="Forces the number of columns in the admin dashboard widgets.">
						<i class="icon-info-sign"></i></a>
					  </label>
				</div>
				<div class="row-fluid  control-group" id="ao_force_col_div" style="display:none;">
					 
							<select id="ao_force_dash_column_num" name="ao_force_dash_column_num" class="input-medium">
							<option value="">Please select</option>
							<option value="1">1 column</option>
							<option value="2">2 columns</option>
							<option value="3">3 columns</option>
							<option value="4">4 columns</option>
							<option value="5">5 columns</option>
							</select>
					<a href="#" style="cursor: help;" title="Select the number of columns in the admin dashboard widgets.">	<i class="icon-info-sign"></i></a>
				</div>			
				</div> <!--controls -->
				</div><!--row -->	            
			</div><!--tab -->	
			<div class="tab-pane" id="tab4">
				<div class="row-fluid">
				<label class="control-label span1"></label>
					<div class="controls span8">
					<div class="control-group row-fluid">
						<label class="control-label span3">Modify Toolbar </label>
						<div class="span8">
							<select id="ao_modify_admin_bar" name="ao_modify_admin_bar" class="input-large">
							<option value="">Please select</option>
							<option value="1">Remove both toolbars</option>
							<option value="2">Remove backend toolbar</option>
							<option value="3">Remove frontend toolbar</option>
							<option value="4">Remove all standard admin toolbar menus</option>
							</select>
							<a href="#" style="cursor: help;" title="Remove both toolbars: Allows to remove admin toolbar from the backend and the front side of your website. Remove backend toolbar: Allows to remove admin toolbar from the backend. Remove frontend toolbar: Allows to remove admin toolbar from the front side of your website. Remove all standard admin toolbar menus: Allows to remove all default admin toolbar menus.">	<i class="icon-info-sign"></i></a>
						</div>	
					</div>
				</div><!--controls -->
			</div><!--row -->	 		
		</div><!--tab -->
		<div class="tab-pane" id="tab5">
				<div class="control-group row-fluid">
					<label class="control-label span3">Login logo image url</label>
					<div class="controls span8">
							<input class="input-xlarge" name="ao_login_logo_url" id="ao_login_logo_url" type="text" placeholder="http://path-to-my-application-logo" value="" >
							<a href="#" style="cursor: help;" title="Enter login logo image url for the application. It is displayed above the login box. For best results, use an image that is less than 326 pixels wide.">
							<i class="icon-info-sign"></i></a>
					</div>
				</div>
				<div class="control-group row-fluid">
					<label class="control-label span3">Toolbar image url</label>
					<div class="controls span8">
				<input class="input-xlarge" name="ao_admin_logo_url" id="ao_admin_logo_url" type="text" placeholder="http://path-to-my-application-toolbar-logo" value="" >
						<a href="#" style="cursor: help;" title="Enter toolbar image url for the application. It is displayed in the admin toolbar. For best results, use an image that is 20x20 pixels.">
						<i class="icon-info-sign"></i></a>
					</div>
				</div>
				
		</div>
		<div class="tab-pane" id="tab6">
		<div class="row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<table style="background-color:transparent !important;">
              <tr><td>Theme Type</td>
                      <td> <select name="ao_theme_type" id="ao_theme_type">
                        <option value="smoothness" selected="selected">Smoothness</option>
                        <option value="ui-lightness">UI lightness</option>
                        <option value="ui-darkness">UI darkness</option>
                        <option value="start">Start</option>
                        <option value="redmond">Redmond</option>
                        <option value="sunny">Sunny</option>
                        <option value="overcast">Overcast</option>
                        <option value="le-frog">Le Frog</option>
                        <option value="flick">Flick</option>
                        <option value="pepper-grinder">Pepper Grinder </option>
                        <option value="eggplant">Eggplant</option>
                        <option value="dark-hive">Dark Hive</option>
                        <option value="cupertino">Cupertino</option>
                        <option value="south-street">South Street</option>
                        <option value="blitzer">Blitzer</option>
                        <option value="humanity">Humanity</option>
                        <option value="hot-sneaks">Hot Sneaks</option>
                        <option value="excite-bike">Excite Bike</option>
                        <option value="vader">Vader</option>
                        <option value="dot-luv">Dot Luv</option>
                        <option value="mint-choc">Mint Choc</option>
                        <option value="black-tie">Black Tie</option>
                        <option value="trontastic">Trontastic</option>
                        <option value="swanky-purse">Swanky Purse</option>
                        </select>
                        <a href="#" style="cursor: help;" title="Whether this entity is intended to be used publicly either via the admin interface or by front-end users. -false- Entity is not intended to be used publicly and should generally be unavailable in the admin interface and on the front end unless explicitly planned for elsewhere. -true - Entity is intended for public use. This includes on the front end and in the admin interface.">
                        <i class="icon-info-sign"></i> </a></td>
                        <td><img id="theme_url" name="theme_url" src="http://jqueryui.com/resources/images/themeGallery/theme_90_smoothness.png"></td>
                        </tr>
            </table>
           </div>
           </div>
	</div>

		<div class="tab-pane" id="tab7">
			<div class="row-fluid">
				<div class="control-group row-fluid span6">
					<label class="">Left footer</label>
					 <div class="controls span8">
<?php
        $buttons['theme_advanced_buttons1'] = 'bold,italic,underline,link,unlink';
        $buttons['theme_advanced_buttons2'] = 'tablecontrols';

      $settings = array(
                                'text_area_name'=>'ao_left_footer_html',//name you want for the textarea
                                'quicktags' => false,
                                'media_buttons' => false,
                                'textarea_rows' => 15,
                        	'tinymce' => $buttons,
                );
        $id = 'ao_left_footer_html';//has to be lower case


	wp_editor('',$id,$settings);

?>
					 <a href="#" style="cursor: help;" title="Displays a message in the left hand side of the backend footer.">
						<i class="icon-info-sign"></i></a>
 	</div></div>

				<div class="control-group row-fluid span6">
					<label class="">Right footer</label>
					 <div class="controls span8">
<?php
        $buttons['theme_advanced_buttons1'] = 'bold,italic,underline,link,unlink';
        $buttons['theme_advanced_buttons2'] = 'tablecontrols';
      $settings = array(
                                'text_area_name'=>'ao_right_footer_html',//name you want for the textarea
                                'quicktags' => false,
                                'media_buttons' => false,
                                'textarea_rows' => 15,
                        	'tinymce' => $buttons,
                );
        $id = 'ao_right_footer_html';//has to be lower case


	wp_editor('',$id,$settings);

?>
					 <a href="#" style="cursor: help;" title="Displays a short message such as application version number in the right hand side of the backend footer.">
						<i class="icon-info-sign"></i></a>
					 </div>
				</div></div>
		</div>
		<div class="tab-pane" id="tab8">
				<div class="control-group row-fluid">
					<label class="control-label span3">Mail FROM email address</label>
					 <div class="controls span8">
						 <input class="input-xlarge" name="ao_mail_from_email" id="ao_mail_from_email" type="text" placeholder="e.g. info@example.com" value="" >
						 <a href="#" style="cursor: help;" title="Sets the FROM email address for the application wide emails">
						<i class="icon-info-sign"></i></a>
					 </div>
				</div>
				<div class="control-group row-fluid">
					<label class="control-label span3">Mail FROM name</label>
					 <div class="controls span8">
						 <input class="input-xlarge" name="ao_mail_from_name" id="ao_mail_from_name" type="text" placeholder="e.g. Webmaster" value="" >
						 <a href="#" style="cursor: help;" title="Sets the name of the sender for the application wide emails.">
						<i class="icon-info-sign"></i></a>
					 </div>
				</div>
		</div>
	</div>
</div>
	<div class="control-group row-fluid">
                   <button class="btn  btn-danger layout-buttons" id="cancel" name="cancel" type="button"><i class="icon-ban-circle">
</i>Cancel</button>
                   <button class="btn  btn-primary pull-right layout-buttons" id="update-option" type="submit" value="Update">
                   <i class="icon-save"></i>Update</button>
    </div>

</form>
<?php
}
?>
