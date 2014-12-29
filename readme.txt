=== Protect WP-Admin ===
Contributors:india-web-developer
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4
Tags: Protect WP-Admin,wp-admin,Protect wordpress admin,Secure Admin,Admin,Scure Wordpress Admin,Rename Admin URL, Rename Wordpress Admin URL,Change wp-admin url,Change Admin URL,Change Admin Path,Restrict wp-admin
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 1.2

Protect Your Website Admin Against Hackers!. Change default Admin URL To Secure Admin URL (i.e http://yourdomain.com/myadmin)

== Description ==

If you run a WordPress website, you should absolutely use "protect-wp-admin" to secure it against hackers.

Protect WP-Admin fixes a glaring security hole in the WordPress community: the well-known problem of the admin panel URL.
Everyone knows where the admin panel, and this includes hackers as well.

Protect WP-Admin helps solve this problem by allowing webmasters to customize their admin panel URL and blocking the default links.

After you setup Protect WP-Admin, webmasters will be able to change the "sitename.com/wp-admin" link into something like "sitename.com/custom-string".
All queries for the classic "/wp-admin/" and "wp-login.php" files will be redirected to the homepage, while access to the WP backend will be allowed only for the custom URL.

The plugin also comes with some access filters, allowing webmasters to restrict guest and registered users access to wp-admin, just in case you want some of your editors to log in the classic way.

= Features =

 * Rename/Change wp-admin url to new url (i.e http://yourdomain.com/myadmin)
 * Restrict guest users for access to wp-admin
 * Restrict registered non-admin users from wp-admin


== Installation ==

 * Step 1. Upload "protect-wp-admin" folder to the `/wp-content/plugins/` directory
 * Step 2. Activate the plugin through the Plugins menu in WordPress
 * Step 3. Go to Settings "Protect WP-Admin" and configure the plugin settings.

== Frequently Asked Questions ==

* 1.Nothing happen after enable and add the new wordpress admin url? 

   Don't worry, Just update the site permalink ("Settings" >> "Permalinks") and re-check,Now this time it will be work fine

* 2.Was not able to login after installation

Basicaly issues can come only in case when you will use default permalink settings. 
If your permalink will be update to any other option except default then it will be work fine. Anyway Dont' worry,add code give below into your site .htaccess file.
	
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteRule ^newadmin/?$ /wp-login.php [QSA,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress

Don not forgot to update the "newadmin" slug with your new admin slug (that you were added during update the plugin settings) :-)

== Screenshots ==

1. screenshot-1.png

2. screenshot-2.png


== Changelog == 

= 1.2 = 
 * Added new option for allow admin access to non-admin users
 * Added condition for check permalink is updated or not
 * Fixed a minor issues (logout issues after add/update admin new url)
 
= 1.1 = 
 * Add new option for restrict registered users from wp-admin
 * Fixed permalink update issue after add/update admin new url. Now no need to update your permalink
 * Add option for redirect user to new admin url after update the new admin url

= 1.0 = 
 * First stable release
