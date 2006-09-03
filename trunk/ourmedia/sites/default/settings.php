<?php

//$db_url = 'mysql://root@localhost/ourmedia';
$db_url = 'mysql://ourmedia_p:password@localhost/ourmedia_pdevelopment';
$db_prefix = '';

#$base_url = 'http://www.ourmedia.org';

/**
 * PHP settings:
 *
 * To see what PHP settings are possible, including whether they can
 * be set at runtime (ie., when ini_set() occurs), read the PHP
 * documentation at http://www.php.net/manual/en/ini.php#ini.list
 * and take a look at the .htaccess file to see which non-runtime
 * settings are used there. Settings defined here should not be
 * duplicated there so as to avoid conflict issues.
 */
ini_set('arg_separator.output',     '&amp;');
ini_set('magic_quotes_runtime',     0);
ini_set('magic_quotes_sybase',      0);
ini_set('session.cache_expire',     200000);
ini_set('session.cache_limiter',    'none');
ini_set('session.cookie_lifetime',  2000000);
ini_set('session.gc_maxlifetime',   200000);
ini_set('session.save_handler',     'user');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid',    0);

/**
 * Variable overrides:
 *
 * To override specific entries in the 'variable' table for this site,
 * set them here. You usually don't need to use this feature. This is
 * useful in a configuration file for a vhost or directory, rather than
 * the default settings.php. Any configuration setting from the 'variable'
 * table can be given a new value.
 */
//$conf = array(
//  'site_name' => 'My Drupal site',
//  'theme_default' => 'pushbutton',
//  'anonymous' => 'Visitor'
//);

$conf = array (
  'disable_watchdog' => 0,
);

$bryght_admin_email = "borismann@gmail.com";
$bryght_site_name = "Ourmedia";
?>
