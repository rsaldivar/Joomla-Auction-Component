CREATE TABLE IF NOT EXISTS `#__jereverseauction_mailtemplates` (
  `id` int(11) NOT NULL auto_increment,
  `subject` longtext NOT NULL,
  `mailbody` longtext NOT NULL,
  `published` tinyint(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__jereverseauction_probidding` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `prod_id` varchar(50) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__jereverseauction_wins` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `amount` DECIMAL( 10, 2 ) NOT NULL,
  `ended` varchar(250) NOT NULL,
  `published` tinyint(5) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `prod_id` (`prod_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__jereverseauction_commission` (
  `id` int(10) NOT NULL auto_increment,
  `prod_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `trans_id` varchar(250) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `payment_type` varchar(250) NOT NULL,
  `paid_date` varchar(250) NOT NULL,
  `published` tinyint(5) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `prod_id` (`prod_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__jereverseauction_products` (
  `id` int(11) NOT NULL auto_increment,
  `catid` int(11) NOT NULL,
  `prod_name` varchar(100) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `prod_amount` decimal(10,2) NOT NULL,
  `prod_image` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coupon_validity` varchar(250) NOT NULL,
  `commission_paid` int(11) NOT NULL,
  `max_bid_amount` decimal(10,2) NOT NULL,
  `access` tinyint(3) NOT NULL,
  `coupon_code` varchar(20) NOT NULL,
  `min_bid_amount` decimal(10,2) NOT NULL,
  `params` varchar(500) NOT NULL,
  `prod_detail_image` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;