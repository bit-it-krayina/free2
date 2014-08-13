CREATE TABLE `ng_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) unsigned DEFAULT NULL,
  `priority_name` varchar(50) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `message` text,
  `type` varchar(50) DEFAULT NULL,
  `error_code` int(11) DEFAULT NULL,
  `exception_class` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `line` int(11) unsigned DEFAULT NULL,
  `called_function` varchar(255) DEFAULT NULL,
  `trace` text,
  `ip_address` varchar(40) DEFAULT NULL,
  `request_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `extra` text,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;