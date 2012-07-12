CREATE TABLE IF NOT EXISTS `#__cmc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `value` text NOT NULL,
  `values` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `catdisp` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__cmc_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mc_id` varchar(10) NOT NULL,
  `web_id` int(11) NOT NULL,
  `list_name` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `email_type_option` tinyint(1) NOT NULL DEFAULT '0',
  `use_awesomebar` tinyint(1) NOT NULL DEFAULT '1',
  `default_from_name` varchar(255) NOT NULL,
  `default_from_email` varchar(255) NOT NULL,
  `default_subject` varchar(255) DEFAULT NULL,
  `default_language` varchar(10) NOT NULL DEFAULT 'en',
  `list_rating` float(5,4) NOT NULL DEFAULT '0.0000',
  `subscribe_url_short` varchar(255) NOT NULL,
  `subscribe_url_long` varchar(255) NOT NULL,
  `beamer_address` varchar(255) NOT NULL,
  `visibility` varchar(255) NOT NULL DEFAULT 'pub',
  `created_user_id` int(11) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(11) NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) NOT NULL DEFAULT '0',
  `query_data` text,
  PRIMARY KEY (`id`)
);

