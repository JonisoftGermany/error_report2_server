-- Error Report 2 Module
-- Version 2.0.2


CREATE TABLE ErrorReport2 (
	`id` bigint unsigned NOT NULL auto_increment PRIMARY KEY,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`service_id` varchar(50),
	`er2_client_version` varchar(11) NOT NULL,
	`er2_server_version` varchar(11) NOT NULL,
	`session_id` varchar(128) NOT NULL,
	`client_timestamp` datetime NOT NULL,
	`host_name` varchar(100) NOT NULL,
	`host_os` varchar(100) NOT NULL,
	`host_os_release` varchar(100) NOT NULL,
	`host_os_version` varchar(100) NOT NULL,
	`php_version` varchar(20) NOT NULL,
	`php_mode` varchar(30) NOT NULL,
	`php_mem_usage` bigint unsigned NOT NULL,
	`debug_mode` tinyint unsigned COMMENT 'BOOL',
	`request_method` enum('CONNECT', 'DELETE', 'GET', 'HEAD', 'OPTIONS', 'PATCH', 'POST', 'PUT', 'TRACE') NOT NULL,
	`request_domain` varchar(255) NOT NULL,
	`request_subdomain` varchar(255) NOT NULL,
	`request_tcp_port` smallint unsigned NOT NULL,
	`request_path` varchar(255) NOT NULL,
	`request_cli` tinyint unsigned NOT NULL COMMENT 'BOOL',
	`request_secure_connection` tinyint unsigned NOT NULL COMMENT 'BOOL',
	`environment` text,
	`database` text,
	`cookies` text,
	`get` text,
	`post` text,
	`session` text,
	`errors` text,
	`throwable` text
) ENGINE = InnoDB, COMMENT = 'er2-db-vers:2';
