<?php
// $Id: index.php,v 1.82 2004/08/21 06:42:34 dries Exp $

/**
 * @file
 * The PHP page that serves all page requests on a Drupal installation.
 *
 * The routines here dispatch control to the appropriate handler, which then
 * prints the appropriate page.
 */

include_once 'includes/bootstrap.inc';
//include_once 'index-acl.inc';
drupal_page_header();
include_once 'includes/common.inc';

fix_gpc_magic();

$status = menu_execute_active_handler();
switch ($status) {
  case MENU_NOT_FOUND:
    drupal_not_found();
    break;
  case MENU_ACCESS_DENIED:
    drupal_access_denied();
    break;
}

drupal_page_footer();

//_anders_footer();

?>
