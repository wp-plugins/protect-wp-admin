<?php
/*
 * Protect WP-Admin (C)
 * @register_install_hook()
 * @register_uninstall_hook()
 * */
?>
<?php 
/** Get all options value */
function get_pwa_setting_options() {
		global $wpdb;
		$pwaOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'pwa_%'");
								
		foreach ($pwaOptions as $option) {
			$pwaOptions[$option->option_name] =  $option->option_value;
		}
		return $pwaOptions;	
	}
$getPwaOptions=get_pwa_setting_options();
if(isset($getPwaOptions['pwa_active']) && '1'==$getPwaOptions['pwa_active'])
{
add_action('init', 'init_pwa_admin_rewrite_rules' );
add_action('init', 'pwa_admin_url_redirect_conditions' );
}
if(isset($getPwaOptions['pwa_logout']))
{
add_action('admin_init', 'pwa_logout_user_after_settings_save');
add_action('admin_init', 'pwa_logout_user_after_settings_save');
}
function pwa_logout_user_after_settings_save()
{
	$getPwaOptions=get_pwa_setting_options();
    if(isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page']=='pwa-settings')
    {
    flush_rewrite_rules();
	}
	
if(isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page']=='pwa-settings' && isset($getPwaOptions['pwa_logout']) && $getPwaOptions['pwa_logout']==1)
   {
     $URL=str_replace('&amp;','&',wp_logout_url());
      if(isset($getPwaOptions['pwa_rewrite_text']) && isset($getPwaOptions['pwa_logout']) && $getPwaOptions['pwa_logout']==1 && $getPwaOptions['pwa_rewrite_text']!=''){
      wp_redirect(home_url('/'.$getPwaOptions['pwa_rewrite_text']));
     }else
     {
		 //silent
		 }
     //wp_redirect($URL);
   }   
}
/** Create a new rewrite rule for change to wp-admin url */
function init_pwa_admin_rewrite_rules() {
	$getPwaOptions=get_pwa_setting_options();
    if(isset($getPwaOptions['pwa_active']) && ''!=$getPwaOptions['pwa_rewrite_text']){
	$newurl=strip_tags($getPwaOptions['pwa_rewrite_text']);
    add_rewrite_rule( $newurl.'/?$', 'wp-login.php', 'top' );
    add_rewrite_rule( $newurl.'/register/?$', 'wp-login.php?action=register', 'top' );
    add_rewrite_rule( $newurl.'/lostpassword/?$', 'wp-login.php?action=lostpassword', 'top' );
    }
}
/** 
 * Update Login, Register & Forgot password link as per new admin url
 * */
add_action('login_head','csbwfs_custom_script');
function csbwfs_custom_script()
{ 	
$getPwaOptions=get_pwa_setting_options();
if(isset($getPwaOptions['pwa_active']) && ''!=$getPwaOptions['pwa_rewrite_text']){
echo '<script>jQuery(window).load(function(){var formId= jQuery("#login form").attr("id");
if(formId=="loginform"){
	jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["pwa_rewrite_text"]).'");
	}else if("lostpasswordform"==formId){
			jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["pwa_rewrite_text"].'/lostpassword').'");
		}else if("registerform"==formId){
			jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["pwa_rewrite_text"].'/register').'");
			}else
			{
				//silent
				}
jQuery("#nav a").each(function(){
            var linkText=jQuery(this).text();
            if(linkText=="Log in"){jQuery(this).attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"]).'");}
			else if(linkText=="Register"){jQuery(this).attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"].'/register').'");}else if(linkText=="Lost your password?"){jQuery(this).attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"].'/lostpassword').'");}else { 
				//silent
				}	
        });});</script>';
}
}
function pwa_admin_url_redirect_conditions()
{
	$getPwaOptions=get_pwa_setting_options();
	$pwaActualURLAry =array
	                       (
                           str_replace('www.','',home_url('/wp-login.php')),
                           str_replace('www.','',home_url('/wp-login.php/')),
                           str_replace('www.','',home_url('/wp-login')),
                           str_replace('www.','',home_url('/wp-login/')),
                           str_replace('www.','',home_url('/wp-admin')),
                           str_replace('www.','',home_url('/wp-admin/')),
                           );
    $request_url = pwa_get_current_page_url($_SERVER);
    $newUrl = explode('?',$request_url);
    // print_r($pwaActualURLAry); echo str_replace('www.','',$newUrl[0]);exit;
	$pwa_requestUrl=str_replace('www.','',$newUrl[0]);
if(! is_user_logged_in() && in_array($pwa_requestUrl,$pwaActualURLAry) ) 
	{
wp_redirect(home_url('/'),301);
		//exit;
		}
		else if(isset($getPwaOptions['pwa_restrict']) && $getPwaOptions['pwa_restrict']==1 && is_user_logged_in())
		{
			global $current_user;
	        $user_roles = $current_user->roles;
	        $user_ID = $current_user->ID;
	        $user_role = array_shift($user_roles);	        
	        if(isset($getPwaOptions['pwa_allow_custom_users']) && $getPwaOptions['pwa_allow_custom_users']!='')
	        {
				$userids=explode(',' ,$getPwaOptions['pwa_allow_custom_users']);
				if(is_array($userids))
				{
					$userids=explode(',' ,$getPwaOptions['pwa_allow_custom_users']);
					}else
					{
						$userids[]=$getPwaOptions['pwa_allow_custom_users'];
						}
				}else
				{
					$userids=array();
					}
	        
			if($user_role=='administrator' || in_array($user_ID,$userids))
			{
				//silent is gold
				}else
				{
					wp_redirect(home_url('/'));
					}
			}else
			{
				//silent is gold
				}
}
/** Get the current url*/
function pwa_current_path_protocol($s, $use_forwarded_host=false)
{
    $pwahttp = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $pwasprotocal = strtolower($s['SERVER_PROTOCOL']);
    $pwa_protocol = substr($pwasprotocal, 0, strpos($pwasprotocal, '/')) . (($pwahttp) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$pwahttp && $port=='80') || ($pwahttp && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $pwa_protocol . '://' . $host;
}
function pwa_get_current_page_url($s, $use_forwarded_host=false)
{
    return pwa_current_path_protocol($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
//if(isset($getPwaOptions['pwa_logo_path'])):
/* Change Wordpress Default Logo */
function pwa_update_login_page_logo() {
$getPwaOptions=get_pwa_setting_options();
    echo '<style type="text/css"> /* Protect WP-Admin Style*/';
    if(isset($getPwaOptions['pwa_logo_path']) && $getPwaOptions['pwa_logo_path']!='')
      echo ' h1 a { background-image:url('.$getPwaOptions['pwa_logo_path'].') !important; }';
    if(isset($getPwaOptions['pwa_login_page_bg_color']) && $getPwaOptions['pwa_login_page_bg_color']!='')
    echo ' body.login-action-login,html{ background:'.$getPwaOptions['pwa_login_page_bg_color'].' !important; height: 100% !important;}';
    echo '</style>';
}
add_action('login_head', 'pwa_update_login_page_logo');
?>