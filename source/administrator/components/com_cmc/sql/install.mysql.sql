CREATE TABLE IF NOT EXISTS `#__cmc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `value` text NOT NULL,
  `values` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `catdisp` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);