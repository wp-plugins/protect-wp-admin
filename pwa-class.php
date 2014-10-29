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
add_action('init', 'pwa_flush_rewrite');
add_action( 'init', 'init_pwa_admin_rewrite_rules' );
add_action( 'init', 'pwa_admin_url_redirect_conditions' );
}
/** Flush rewrite rules after update the permalink */
function pwa_flush_rewrite() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}	
/** Create a new rewrite rule for change to wp-admin url */
function init_pwa_admin_rewrite_rules() {
	$getPwaOptions=get_pwa_setting_options();
    if(isset($getPwaOptions['pwa_active']) && ''!=$getPwaOptions['pwa_rewrite_text']){
	$newurl=strip_tags($getPwaOptions['pwa_rewrite_text']);
    add_rewrite_rule( $newurl.'/?$', 'wp-login.php', 'top' );
    }
}

function pwa_admin_url_redirect_conditions()
{
	$wordpresActualURL =home_url('/wp-login.php');
    $request_url = pwa_get_current_page_url($_SERVER);
    $newUrl = explode('?',$request_url);

	if(! is_user_logged_in() && $wordpresActualURL==$newUrl[0] ) 
	{
		//echo "{False}";
		wp_redirect(home_url('/'));
		}else
		{
			//echo "{Ture}";
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
?>
