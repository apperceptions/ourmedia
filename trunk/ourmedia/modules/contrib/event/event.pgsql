-- Event.module SQL Definitions
-- $Id: event.pgsql,v 1.2 2004/03/14 21:39:57 killes Exp $

DROP TABLE event;

CREATE TABLE event (
  nid int NOT NULL default '0',
  start int NOT NULL default '0',
  location text,
  data TEXT,
  PRIMARY KEY  (nid)
);
