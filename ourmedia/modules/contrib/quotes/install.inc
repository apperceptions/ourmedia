<?php
// $Id: install.inc,v 1.1.2.2 2004/11/18 03:21:57 jhriggs Exp $

/* This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307  USA
 */

/**
 * Installs the quotes module by creating the tables, setting the
 * variables, and initiating an upgrade as appropriate.
 */
function _quotes_install() {
  $the_ver = quotes_version();
  $the_cur_ver = variable_get('quotes_version', NULL);

  if (!$the_cur_ver) {
    // fresh install
    _quotes_create_tables();
    variable_set('quotes_version', quotes_version());
  }
  elseif ($the_cur_ver['build'] >= $the_ver['build']) {
    drupal_set_message(t('A newer version of the quotes module is already installed.'), 'error');
  }
  elseif ($the_cur_ver['build'] < 2004111100) {
    // upgrade from pre-4.5 versions
    if (!variable_get('quotes_upgrade_tables_created', FALSE)) {
      db_query("DELETE FROM {blocks} WHERE module = 'quotes'");
      _quotes_create_tables();
      variable_set('quotes_upgrade_tables_created', TRUE);
    }

    drupal_goto('node/add/quotes/upgrade');
  }
} // function _quotes_install

/**
 * Creates the quotes module tables.
 */
function _quotes_create_tables() {
  db_query('
CREATE TABLE {quotes} (
  nid INTEGER NOT NULL PRIMARY KEY,
  author VARCHAR(255) NOT NULL,
  promote INTEGER NOT NULL
)');
  db_query('
CREATE TABLE {quotes_blocks} (
  bid INTEGER NOT NULL PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  nid_filter TEXT NOT NULL,
  rid_filter TEXT NOT NULL,
  uid_filter TEXT NOT NULL,
  tid_filter TEXT NOT NULL
)');
} // function _quotes_create_tables
?>
