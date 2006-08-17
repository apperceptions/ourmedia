CREATE TABLE weblink (
  nid integer default '0',
  weblink varchar(128) default NULL,
  click integer default '0',
  monitor smallint NOT NULL DEFAULT '0' ,
  size text NOT NULL DEFAULT '' ,
  change_stamp integer NOT NULL DEFAULT '0' ,
  checked integer NOT NULL DEFAULT '0' ,
  feed varchar(255) NOT NULL DEFAULT '' ,
  refresh integer NOT NULL DEFAULT '0' ,
  threshold integer NOT NULL DEFAULT '0' ,
  spider_site smallint NOT NULL DEFAULT '0' ,
  spider_url varchar(255) NOT NULL DEFAULT ''
);
CREATE INDEX weblink_nid_idx ON weblink(nid);
CREATE INDEX weblink_weblink_idx ON weblink(weblink);
