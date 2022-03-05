-- Error Report 2 Module
-- Update from ER2 2.0.1 to 2.0.2

ALTER TABLE ErrorReport2 CHANGE COLUMN `debug_mode` `debug_mode` tinyint unsigned COMMENT 'BOOL';
ALTER TABLE ErrorReport2 COMMENT = 'er2-db-vers:2';
