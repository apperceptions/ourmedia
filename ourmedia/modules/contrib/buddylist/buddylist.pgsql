CREATE TABLE buddylist (
  uid integer NOT NULL default 0,
  buddy integer NOT NULL default 0,
  timestamp integer NOT NULL default 0
);
ALTER TABLE buddylist ADD COLUMN received smallint NOT NULL;
