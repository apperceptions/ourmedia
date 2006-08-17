# $Id: htmlarea.sql,v 1.1 2003/04/23 22:10:06 gordon Exp $

#
# Table structure for table `htmlarea`
#

CREATE TABLE htmlarea (
  textarea varchar(255) NOT NULL default '',
  status tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (textarea)
) TYPE=MyISAM;

