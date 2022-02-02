-- Error Report 2 Module
-- Update from ER2 2.0.1 to 2.0.2
-- Update from database structure version 2 to 3

ALTER TABLE ErrorReport2 ADD COLUMN `er2_protocol_version` smallint unsigned AFTER `er2_server_version`;
Update ErrorReport2 SET `er2_protocol_version` = 1;
ALTER TABLE ErrorReport2 CHANGE COLUMN `er2_protocol_version` `er2_protocol_version` smallint unsigned NOT NULL AFTER `er2_server_version`;
ALTER TABLE ErrorReport2 ADD COLUMN `environment_name` varchar(200) AFTER `php_mem_usage`;
ALTER TABLE ErrorReport2 COMMENT = 'er2-db-vers:3';
