<?php
/*
* $Id: start_drupal.inc,v 1.2 2004/09/22 11:52:43 gordon Exp $
*/
chdir('../../../../../'); /* We need to go to the root drupal directory so that 
                             we can include the drupal includes and have full 
                             access to the api and database. */ 
for ($i=0; $i<5; $i++) {
  $_SERVER['PHP_SELF'] = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
}
if (!file_exists('includes/bootstrap.inc')) {
  chdir('..');
  $_SERVER['PHP_SELF'] = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
}
$_SERVER['PHP_SELF'] = dirname($_SERVER['PHP_SELF']) .(dirname($_SERVER['PHP_SELF']) == "/" ? "" : "/") ."index.php";

require_once('includes/bootstrap.inc'); 
require_once('includes/common.inc');
?>
