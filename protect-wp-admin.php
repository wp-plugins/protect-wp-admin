<?php
/**
Plugin Name: Protect WP-Admin
Plugin URI: http://www.mrwebsolution.in/
Description: "protect-wp-admin" is a very help full plugin to make wordpress admin more secure. Protect WP-Admin plugin is provide the options for change the wp-admin url and make the login page private(directly user can't access the login page).
Author: Raghunath
Author URI: http://www.mrwebsolution.in/
Version: 1.0
*/

/***  Copyright 2014  Raghunath  (email : raghunath.0087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
***/


/**
 * Initialize "Protect WP-Admin" plugin admin menu 
 * @create new menu
 * @create plugin settings page
 */
add_action('admin_menu','init_pwa_admin_menu');

function init_pwa_admin_menu(){

	add_options_page('Protect WP-Admin','Protect WP-Admin','manage_options','pwa-settings','init_pwa_admin_option_page');

}

/** Define Action to register "Protect WP-Admin" Options */
add_action('admin_init','init_pwa_options_fields');
/** Register "Protect WP-Admin" options */
function init_pwa_options_fields(){
	register_setting('pwa_setting_options','pwa_active');
	register_setting('pwa_setting_options','pwa_rewrite_text');
} 


/** Add settings link to plugin list page in admin */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pwa_action_links' );
function pwa_action_links( $links ) {
   $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=pwa-settings') .'">Settings</a>';
   return $links;
}

/** Options Form HTML for "Protect WP-Admin" plugin */
function init_pwa_admin_option_page(){ ?>
	<div style="width: 80%; padding: 10px; margin: 10px;"> 
	<h1>Protect WP-Admin Settings</h1>
  <!-- Start Options Form -->
	<form action="options.php" method="post" id="pwa-settings-form-admin">
		
	<div id="pwa-tab-menu"><a id="pwa-general" class="pwa-tab-links active" >General</a> <a  id="pwa-support" class="pwa-tab-links">Support</a> </div>

	<div class="pwa-setting">
	<!-- General Setting -->	
	<div class="first pwa-tab" id="div-pwa-general">
	<h2>General Settings</h2>
	<p><strong>Note!:</strong> After update the new admin url,you have need to update the site permalink!</p>
	<p><label>Enable:</label><input type="checkbox" id="pwa_active" name="pwa_active" value='1' <?php if(get_option('pwa_active')!=''){ echo ' checked="checked"'; }?>/></p>
	<p><label>New Admin URL:</label><input type="text" id="pwa_rewrite_text" name="pwa_rewrite_text" value="<?php echo esc_attr(get_option('pwa_rewrite_text')); ?>"  placeholder="wp-admin"></p>
	</div>

	<!-- Support -->
	<div class="last author pwa-tab" id="div-pwa-support">
	<h2>Plugin Support</h2>
	
	<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" title="Donate for this plugin"></a></p>
	
	<p><strong>Plugin Author:</strong><br><img src="<?php echo  plugins_url( 'images/raghu.jpg' , __FILE__ );?>" width="75" height="75"><br><a href="http://raghunathgurjar.wordpress.com" target="_blank">Raghunath Gurjar</a></p>
	<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
	<p><strong>My Other Plugins:</strong><br>
	<ul>
		<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Simple Testimonial Rutator</a></li>
		<li><a href="https://wordpress.org/plugins/simple-testimonial-rutator/" target="_blank">Simple Testimonial Rutator</a></li>
		<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
		<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
		<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
		</ul></p>
	</div>

	</div>
	<span class="submit-btn"><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></span>
		
    <?php settings_fields('pwa_setting_options'); ?>
	
	</form>

<!-- End Options Form -->
	</div>

<?php
}

/** add js into admin footer */
add_action('admin_footer','init_pwa_admin_scripts');
function init_pwa_admin_scripts()
{
wp_register_style( 'pwa_admin_style', plugins_url( 'css/pwa-admin-min.css',__FILE__ ) );
wp_enqueue_style( 'pwa_admin_style' );

echo $script='<script type="text/javascript">
	/* Protect WP-Admin js for admin */
	jQuery(document).ready(function(){
		jQuery(".pwa-tab").hide();
		jQuery("#div-pwa-general").show();
	    jQuery(".pwa-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".pwa-tab-links").removeClass("active");
		jQuery(".pwa-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		})
		})
	</script>';

	}


/** register_install_hook */
if( function_exists('register_install_hook') ){
register_uninstall_hook(__FILE__,'init_install_pwa_plugins'); 
}
//flush the rewrite
function init_install_pwa_plugins(){
	  flush_rewrite_rules();
} 
/** register_uninstall_hook */
/** Delete exits options during disable the plugins */
if( function_exists('register_uninstall_hook') ){
   register_uninstall_hook(__FILE__,'init_uninstall_pwa_plugins');   
}

//Delete all options after uninstall the plugin
function init_uninstall_pwa_plugins(){
	delete_option('pwa_active');
	delete_option('pwa_rewrite_text');
}
require dirname(__FILE__).'/pwa-class.php';

?>
