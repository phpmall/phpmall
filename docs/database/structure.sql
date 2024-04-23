SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for account_log
-- ----------------------------
DROP TABLE IF EXISTS `account_log`;
CREATE TABLE `account_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_money` decimal(10,2) NOT NULL,
  `frozen_money` decimal(10,2) NOT NULL,
  `rank_points` int(11) NOT NULL,
  `pay_points` int(11) NOT NULL,
  `change_time` int(11) unsigned NOT NULL,
  `change_desc` varchar(255) NOT NULL,
  `change_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_account_log
-- ----------------------------

-- ----------------------------
-- Table structure for ad
-- ----------------------------
DROP TABLE IF EXISTS `ad`;
CREATE TABLE `ad` (
  `ad_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(11) unsigned NOT NULL DEFAULT '0',
  `media_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ad_name` varchar(60) NOT NULL DEFAULT '',
  `ad_link` varchar(255) NOT NULL DEFAULT '',
  `ad_code` text NOT NULL,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `link_man` varchar(60) NOT NULL DEFAULT '',
  `link_email` varchar(60) NOT NULL DEFAULT '',
  `link_phone` varchar(60) NOT NULL DEFAULT '',
  `click_count` int(11) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ad_id`),
  KEY `position_id` (`position_id`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_ad
-- ----------------------------

-- ----------------------------
-- Table structure for admin_action
-- ----------------------------
DROP TABLE IF EXISTS `admin_action`;
CREATE TABLE `admin_action` (
  `action_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `action_code` varchar(20) NOT NULL DEFAULT '',
  `relevance` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`action_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_admin_action
-- ----------------------------

-- ----------------------------
-- Table structure for admin_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE `admin_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for admin_message
-- ----------------------------
DROP TABLE IF EXISTS `admin_message`;
CREATE TABLE `admin_message` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `receiver_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sent_time` int(11) unsigned NOT NULL DEFAULT '0',
  `read_time` int(11) unsigned NOT NULL DEFAULT '0',
  `readed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `sender_id` (`sender_id`,`receiver_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_admin_message
-- ----------------------------

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `ec_salt` varchar(10) DEFAULT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `action_list` text NOT NULL,
  `nav_list` text NOT NULL,
  `lang_type` varchar(50) NOT NULL DEFAULT '',
  `agency_id` int(11) unsigned NOT NULL,
  `suppliers_id` int(11) unsigned DEFAULT '0',
  `todolist` text,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `agency_id` (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_admin_user
-- ----------------------------

-- ----------------------------
-- Table structure for adsense
-- ----------------------------
DROP TABLE IF EXISTS `adsense`;
CREATE TABLE `adsense` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_ad` int(11) NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `clicks` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_ad` (`from_ad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_adsense
-- ----------------------------

-- ----------------------------
-- Table structure for ad_custom
-- ----------------------------
DROP TABLE IF EXISTS `ad_custom`;
CREATE TABLE `ad_custom` (
  `ad_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ad_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ad_name` varchar(60) DEFAULT NULL,
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `url` varchar(255) DEFAULT NULL,
  `ad_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_ad_custom
-- ----------------------------

-- ----------------------------
-- Table structure for ad_position
-- ----------------------------
DROP TABLE IF EXISTS `ad_position`;
CREATE TABLE `ad_position` (
  `position_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `position_name` varchar(60) NOT NULL DEFAULT '',
  `ad_width` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_height` int(11) unsigned NOT NULL DEFAULT '0',
  `position_desc` varchar(255) NOT NULL DEFAULT '',
  `position_style` text NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_ad_position
-- ----------------------------

-- ----------------------------
-- Table structure for affiliate_log
-- ----------------------------
DROP TABLE IF EXISTS `affiliate_log`;
CREATE TABLE `affiliate_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(60) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int(11) NOT NULL DEFAULT '0',
  `separate_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_affiliate_log
-- ----------------------------

-- ----------------------------
-- Table structure for agency
-- ----------------------------
DROP TABLE IF EXISTS `agency`;
CREATE TABLE `agency` (
  `agency_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `agency_name` varchar(255) NOT NULL,
  `agency_desc` text NOT NULL,
  PRIMARY KEY (`agency_id`),
  KEY `agency_name` (`agency_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_agency
-- ----------------------------

-- ----------------------------
-- Table structure for area_region
-- ----------------------------
DROP TABLE IF EXISTS `area_region`;
CREATE TABLE `area_region` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_area_id` int(11) unsigned NOT NULL DEFAULT '0',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipping_area_id_region_id` (`shipping_area_id`,`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_area_region
-- ----------------------------

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `article_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `author` varchar(30) NOT NULL DEFAULT '',
  `author_email` varchar(60) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `article_type` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `file_url` varchar(255) NOT NULL DEFAULT '',
  `open_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_article
-- ----------------------------

-- ----------------------------
-- Table structure for article_cat
-- ----------------------------
DROP TABLE IF EXISTS `article_cat`;
CREATE TABLE `article_cat` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `cat_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `show_in_nav` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `cat_type` (`cat_type`),
  KEY `sort_order` (`sort_order`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_article_cat
-- ----------------------------

-- ----------------------------
-- Table structure for attribute
-- ----------------------------
DROP TABLE IF EXISTS `attribute`;
CREATE TABLE `attribute` (
  `attr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  `attr_name` varchar(60) NOT NULL DEFAULT '',
  `attr_input_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `attr_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `attr_values` text NOT NULL,
  `attr_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_linked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attr_group` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`attr_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_attribute
-- ----------------------------

-- ----------------------------
-- Table structure for auction_log
-- ----------------------------
DROP TABLE IF EXISTS `auction_log`;
CREATE TABLE `auction_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) unsigned NOT NULL,
  `bid_user` int(11) unsigned NOT NULL,
  `bid_price` decimal(10,2) unsigned NOT NULL,
  `bid_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_auction_log
-- ----------------------------

-- ----------------------------
-- Table structure for auto_manage
-- ----------------------------
DROP TABLE IF EXISTS `auto_manage`;
CREATE TABLE `auto_manage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_id_type` (`item_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_auto_manage
-- ----------------------------

-- ----------------------------
-- Table structure for back_goods
-- ----------------------------
DROP TABLE IF EXISTS `back_goods`;
CREATE TABLE `back_goods` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `back_id` int(11) unsigned DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `product_sn` varchar(60) DEFAULT NULL,
  `goods_name` varchar(120) DEFAULT NULL,
  `brand_name` varchar(60) DEFAULT NULL,
  `goods_sn` varchar(60) DEFAULT NULL,
  `is_real` tinyint(1) unsigned DEFAULT '0',
  `send_number` int(11) unsigned DEFAULT '0',
  `goods_attr` text,
  PRIMARY KEY (`rec_id`),
  KEY `back_id` (`back_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_back_goods
-- ----------------------------

-- ----------------------------
-- Table structure for back_order
-- ----------------------------
DROP TABLE IF EXISTS `back_order`;
CREATE TABLE `back_order` (
  `back_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_sn` varchar(20) NOT NULL,
  `order_sn` varchar(20) NOT NULL,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `invoice_no` varchar(50) DEFAULT NULL,
  `add_time` int(11) unsigned DEFAULT '0',
  `shipping_id` tinyint(3) unsigned DEFAULT '0',
  `shipping_name` varchar(120) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT '0',
  `action_user` varchar(30) DEFAULT NULL,
  `consignee` varchar(60) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `country` int(11) unsigned DEFAULT '0',
  `province` int(11) unsigned DEFAULT '0',
  `city` int(11) unsigned DEFAULT '0',
  `district` int(11) unsigned DEFAULT '0',
  `sign_building` varchar(120) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `zipcode` varchar(60) DEFAULT NULL,
  `tel` varchar(60) DEFAULT NULL,
  `mobile` varchar(60) DEFAULT NULL,
  `best_time` varchar(120) DEFAULT NULL,
  `postscript` varchar(255) DEFAULT NULL,
  `how_oos` varchar(120) DEFAULT NULL,
  `insure_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `shipping_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `update_time` int(11) unsigned DEFAULT '0',
  `suppliers_id` int(11) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `return_time` int(11) unsigned DEFAULT '0',
  `agency_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`back_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_back_order
-- ----------------------------

-- ----------------------------
-- Table structure for bonus_type
-- ----------------------------
DROP TABLE IF EXISTS `bonus_type`;
CREATE TABLE `bonus_type` (
  `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(60) NOT NULL DEFAULT '',
  `type_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `send_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `min_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `max_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `send_start_date` int(11) NOT NULL DEFAULT '0',
  `send_end_date` int(11) NOT NULL DEFAULT '0',
  `use_start_date` int(11) NOT NULL DEFAULT '0',
  `use_end_date` int(11) NOT NULL DEFAULT '0',
  `min_goods_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_bonus_type
-- ----------------------------

-- ----------------------------
-- Table structure for booking_goods
-- ----------------------------
DROP TABLE IF EXISTS `booking_goods`;
CREATE TABLE `booking_goods` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL DEFAULT '',
  `link_man` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_desc` varchar(255) NOT NULL DEFAULT '',
  `goods_number` int(11) unsigned NOT NULL DEFAULT '0',
  `booking_time` int(11) unsigned NOT NULL DEFAULT '0',
  `is_dispose` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dispose_user` varchar(30) NOT NULL DEFAULT '',
  `dispose_time` int(11) unsigned NOT NULL DEFAULT '0',
  `dispose_note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_booking_goods
-- ----------------------------

-- ----------------------------
-- Table structure for brand
-- ----------------------------
DROP TABLE IF EXISTS `brand`;
CREATE TABLE `brand` (
  `brand_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(60) NOT NULL DEFAULT '',
  `brand_logo` varchar(80) NOT NULL DEFAULT '',
  `brand_desc` text NOT NULL,
  `site_url` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`brand_id`),
  KEY `is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_brand
-- ----------------------------

-- ----------------------------
-- Table structure for card
-- ----------------------------
DROP TABLE IF EXISTS `card`;
CREATE TABLE `card` (
  `card_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `card_name` varchar(120) NOT NULL DEFAULT '',
  `card_img` varchar(255) NOT NULL DEFAULT '',
  `card_fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `free_money` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `card_desc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_card
-- ----------------------------

-- ----------------------------
-- Table structure for cart
-- ----------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `session_id` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text NOT NULL,
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `rec_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_gift` int(11) unsigned NOT NULL DEFAULT '0',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_handsel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`rec_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_cart
-- ----------------------------

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(90) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '50',
  `template_file` varchar(50) NOT NULL DEFAULT '',
  `measure_unit` varchar(15) NOT NULL DEFAULT '',
  `show_in_nav` tinyint(1) NOT NULL DEFAULT '0',
  `style` varchar(150) NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `grade` tinyint(4) NOT NULL DEFAULT '0',
  `filter_attr` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_category
-- ----------------------------

-- ----------------------------
-- Table structure for cat_recommend
-- ----------------------------
DROP TABLE IF EXISTS `cat_recommend`;
CREATE TABLE `cat_recommend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `recommend_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cat_id_recommend_type` (`cat_id`,`recommend_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_cat_recommend
-- ----------------------------

-- ----------------------------
-- Table structure for collect_goods
-- ----------------------------
DROP TABLE IF EXISTS `collect_goods`;
CREATE TABLE `collect_goods` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `is_attention` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_attention` (`is_attention`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_collect_goods
-- ----------------------------

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id_value` int(11) unsigned NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL DEFAULT '',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `comment_rank` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `parent_id` (`parent_id`),
  KEY `id_value` (`id_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_comment
-- ----------------------------

-- ----------------------------
-- Table structure for crons
-- ----------------------------
DROP TABLE IF EXISTS `crons`;
CREATE TABLE `crons` (
  `cron_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `cron_code` varchar(20) NOT NULL,
  `cron_name` varchar(120) NOT NULL,
  `cron_desc` text,
  `cron_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cron_config` text NOT NULL,
  `thistime` int(11) NOT NULL DEFAULT '0',
  `nextime` int(11) NOT NULL,
  `day` tinyint(2) NOT NULL,
  `week` varchar(1) NOT NULL,
  `hour` varchar(2) NOT NULL,
  `minute` varchar(255) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `run_once` tinyint(1) NOT NULL DEFAULT '0',
  `allow_ip` varchar(100) NOT NULL DEFAULT '',
  `alow_files` varchar(255) NOT NULL,
  PRIMARY KEY (`cron_id`),
  KEY `nextime` (`nextime`),
  KEY `enable` (`enable`),
  KEY `cron_code` (`cron_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_crons
-- ----------------------------

-- ----------------------------
-- Table structure for delivery_goods
-- ----------------------------
DROP TABLE IF EXISTS `delivery_goods`;
CREATE TABLE `delivery_goods` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `product_id` int(11) unsigned DEFAULT '0',
  `product_sn` varchar(60) DEFAULT NULL,
  `goods_name` varchar(120) DEFAULT NULL,
  `brand_name` varchar(60) DEFAULT NULL,
  `goods_sn` varchar(60) DEFAULT NULL,
  `is_real` tinyint(1) unsigned DEFAULT '0',
  `extension_code` varchar(30) DEFAULT NULL,
  `parent_id` int(11) unsigned DEFAULT '0',
  `send_number` int(11) unsigned DEFAULT '0',
  `goods_attr` text,
  PRIMARY KEY (`rec_id`),
  KEY `delivery_id` (`delivery_id`,`goods_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_delivery_goods
-- ----------------------------

-- ----------------------------
-- Table structure for delivery_order
-- ----------------------------
DROP TABLE IF EXISTS `delivery_order`;
CREATE TABLE `delivery_order` (
  `delivery_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_sn` varchar(20) NOT NULL,
  `order_sn` varchar(20) NOT NULL,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `invoice_no` varchar(50) DEFAULT NULL,
  `add_time` int(11) unsigned DEFAULT '0',
  `shipping_id` tinyint(3) unsigned DEFAULT '0',
  `shipping_name` varchar(120) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT '0',
  `action_user` varchar(30) DEFAULT NULL,
  `consignee` varchar(60) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `country` int(11) unsigned DEFAULT '0',
  `province` int(11) unsigned DEFAULT '0',
  `city` int(11) unsigned DEFAULT '0',
  `district` int(11) unsigned DEFAULT '0',
  `sign_building` varchar(120) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `zipcode` varchar(60) DEFAULT NULL,
  `tel` varchar(60) DEFAULT NULL,
  `mobile` varchar(60) DEFAULT NULL,
  `best_time` varchar(120) DEFAULT NULL,
  `postscript` varchar(255) DEFAULT NULL,
  `how_oos` varchar(120) DEFAULT NULL,
  `insure_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `shipping_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `update_time` int(11) unsigned DEFAULT '0',
  `suppliers_id` int(11) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `agency_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`delivery_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_delivery_order
-- ----------------------------

-- ----------------------------
-- Table structure for email_list
-- ----------------------------
DROP TABLE IF EXISTS `email_list`;
CREATE TABLE `email_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `stat` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_email_list
-- ----------------------------

-- ----------------------------
-- Table structure for email_sendlist
-- ----------------------------
DROP TABLE IF EXISTS `email_sendlist`;
CREATE TABLE `email_sendlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `template_id` int(11) NOT NULL,
  `email_content` text NOT NULL,
  `error` tinyint(1) NOT NULL DEFAULT '0',
  `pri` tinyint(11) NOT NULL,
  `last_send` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_email_sendlist
-- ----------------------------

-- ----------------------------
-- Table structure for error_log
-- ----------------------------
DROP TABLE IF EXISTS `error_log`;
CREATE TABLE `error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(255) NOT NULL,
  `file` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_error_log
-- ----------------------------

-- ----------------------------
-- Table structure for exchange_goods
-- ----------------------------
DROP TABLE IF EXISTS `exchange_goods`;
CREATE TABLE `exchange_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `exchange_integral` int(11) unsigned NOT NULL DEFAULT '0',
  `is_exchange` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_exchange_goods
-- ----------------------------

-- ----------------------------
-- Table structure for favourable_activity
-- ----------------------------
DROP TABLE IF EXISTS `favourable_activity`;
CREATE TABLE `favourable_activity` (
  `act_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL,
  `start_time` int(11) unsigned NOT NULL,
  `end_time` int(11) unsigned NOT NULL,
  `user_rank` varchar(255) NOT NULL,
  `act_range` tinyint(3) unsigned NOT NULL,
  `act_range_ext` varchar(255) NOT NULL,
  `min_amount` decimal(10,2) unsigned NOT NULL,
  `max_amount` decimal(10,2) unsigned NOT NULL,
  `act_type` tinyint(3) unsigned NOT NULL,
  `act_type_ext` decimal(10,2) unsigned NOT NULL,
  `gift` text NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  PRIMARY KEY (`act_id`),
  KEY `act_name` (`act_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_favourable_activity
-- ----------------------------

-- ----------------------------
-- Table structure for feedback
-- ----------------------------
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `msg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `user_email` varchar(60) NOT NULL DEFAULT '',
  `msg_title` varchar(200) NOT NULL DEFAULT '',
  `msg_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `msg_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `msg_content` text NOT NULL,
  `msg_time` int(11) unsigned NOT NULL DEFAULT '0',
  `message_img` varchar(255) NOT NULL DEFAULT '0',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_area` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for friend_link
-- ----------------------------
DROP TABLE IF EXISTS `friend_link`;
CREATE TABLE `friend_link` (
  `link_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_logo` varchar(255) NOT NULL DEFAULT '',
  `show_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  PRIMARY KEY (`link_id`),
  KEY `show_order` (`show_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_friend_link
-- ----------------------------

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `goods_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_name_style` varchar(60) NOT NULL DEFAULT '+',
  `click_count` int(11) unsigned NOT NULL DEFAULT '0',
  `brand_id` int(11) unsigned NOT NULL DEFAULT '0',
  `provider_name` varchar(100) NOT NULL DEFAULT '',
  `goods_number` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_start_date` int(11) unsigned NOT NULL DEFAULT '0',
  `promote_end_date` int(11) unsigned NOT NULL DEFAULT '0',
  `warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `goods_brief` varchar(255) NOT NULL DEFAULT '',
  `goods_desc` text NOT NULL,
  `goods_thumb` varchar(255) NOT NULL DEFAULT '',
  `goods_img` varchar(255) NOT NULL DEFAULT '',
  `original_img` varchar(255) NOT NULL DEFAULT '',
  `is_real` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_alone_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `integral` int(11) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `sort_order` int(11) unsigned NOT NULL DEFAULT '100',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_promote` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bonus_type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_update` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_type` int(11) unsigned NOT NULL DEFAULT '0',
  `seller_note` varchar(255) NOT NULL DEFAULT '',
  `give_integral` int(11) NOT NULL DEFAULT '-1',
  `rank_integral` int(11) NOT NULL DEFAULT '-1',
  `suppliers_id` int(11) unsigned DEFAULT NULL,
  `is_check` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`goods_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `cat_id` (`cat_id`),
  KEY `last_update` (`last_update`),
  KEY `brand_id` (`brand_id`),
  KEY `goods_weight` (`goods_weight`),
  KEY `promote_end_date` (`promote_end_date`),
  KEY `promote_start_date` (`promote_start_date`),
  KEY `goods_number` (`goods_number`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_goods
-- ----------------------------

-- ----------------------------
-- Table structure for goods_activity
-- ----------------------------
DROP TABLE IF EXISTS `goods_activity`;
CREATE TABLE `goods_activity` (
  `act_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL,
  `act_desc` text NOT NULL,
  `act_type` tinyint(3) unsigned NOT NULL,
  `goods_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL,
  `start_time` int(11) unsigned NOT NULL,
  `end_time` int(11) unsigned NOT NULL,
  `is_finished` tinyint(3) unsigned NOT NULL,
  `ext_info` text NOT NULL,
  PRIMARY KEY (`act_id`),
  KEY `act_name` (`act_name`,`act_type`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_goods_activity
-- ----------------------------

-- ----------------------------
-- Table structure for goods_article
-- ----------------------------
DROP TABLE IF EXISTS `goods_article`;
CREATE TABLE `goods_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `article_id` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_id_article_id_admin_id` (`goods_id`,`article_id`,`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_goods_article
-- ----------------------------

-- ----------------------------
-- Table structure for goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `goods_attr`;
CREATE TABLE `goods_attr` (
  `goods_attr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `attr_id` int(11) unsigned NOT NULL DEFAULT '0',
  `attr_value` text NOT NULL,
  `attr_price` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`goods_attr_id`),
  KEY `goods_id` (`goods_id`),
  KEY `attr_id` (`attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_goods_attr
-- ----------------------------

-- ----------------------------
-- Table structure for goods_cat
-- ----------------------------
DROP TABLE IF EXISTS `goods_cat`;
CREATE TABLE `goods_cat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_id_cat_id` (`goods_id`,`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_goods_cat
-- ----------------------------

-- ----------------------------
-- Table structure for goods_gallery
-- ----------------------------
DROP TABLE IF EXISTS `goods_gallery`;
CREATE TABLE `goods_gallery` (
  `img_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `img_url` varchar(255) NOT NULL DEFAULT '',
  `img_desc` varchar(255) NOT NULL DEFAULT '',
  `thumb_url` varchar(255) NOT NULL DEFAULT '',
  `img_original` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`img_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_goods_gallery
-- ----------------------------

-- ----------------------------
-- Table structure for goods_type
-- ----------------------------
DROP TABLE IF EXISTS `goods_type`;
CREATE TABLE `goods_type` (
  `cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(60) NOT NULL DEFAULT '',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `attr_group` varchar(255) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_goods_type
-- ----------------------------

-- ----------------------------
-- Table structure for group_goods
-- ----------------------------
DROP TABLE IF EXISTS `group_goods`;
CREATE TABLE `group_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id_goods_id_admin_id` (`parent_id`,`goods_id`,`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_group_goods
-- ----------------------------

-- ----------------------------
-- Table structure for keywords
-- ----------------------------
DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '2000-01-01',
  `searchengine` varchar(20) NOT NULL DEFAULT '',
  `keyword` varchar(90) NOT NULL DEFAULT '',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date_searchengine_keyword` (`date`,`searchengine`,`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_keywords
-- ----------------------------

-- ----------------------------
-- Table structure for link_goods
-- ----------------------------
DROP TABLE IF EXISTS `link_goods`;
CREATE TABLE `link_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `link_goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `is_double` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_id_link_goods_id_admin_id` (`goods_id`,`link_goods_id`,`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_link_goods
-- ----------------------------

-- ----------------------------
-- Table structure for mail_templates
-- ----------------------------
DROP TABLE IF EXISTS `mail_templates`;
CREATE TABLE `mail_templates` (
  `template_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `template_code` varchar(30) NOT NULL DEFAULT '',
  `is_html` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `template_subject` varchar(200) NOT NULL DEFAULT '',
  `template_content` text NOT NULL,
  `last_modify` int(11) unsigned NOT NULL DEFAULT '0',
  `last_send` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `template_code` (`template_code`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_mail_templates
-- ----------------------------

-- ----------------------------
-- Table structure for member_price
-- ----------------------------
DROP TABLE IF EXISTS `member_price`;
CREATE TABLE `member_price` (
  `price_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_rank` tinyint(3) NOT NULL DEFAULT '0',
  `user_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`price_id`),
  KEY `goods_id` (`goods_id`,`user_rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_member_price
-- ----------------------------

-- ----------------------------
-- Table structure for nav
-- ----------------------------
DROP TABLE IF EXISTS `nav`;
CREATE TABLE `nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctype` varchar(10) DEFAULT NULL,
  `cid` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ifshow` tinyint(1) NOT NULL,
  `vieworder` tinyint(1) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `ifshow` (`ifshow`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_nav
-- ----------------------------

-- ----------------------------
-- Table structure for order_action
-- ----------------------------
DROP TABLE IF EXISTS `order_action`;
CREATE TABLE `order_action` (
  `action_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `action_user` varchar(30) NOT NULL DEFAULT '',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_place` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_note` varchar(255) NOT NULL DEFAULT '',
  `log_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_order_action
-- ----------------------------

-- ----------------------------
-- Table structure for order_goods
-- ----------------------------
DROP TABLE IF EXISTS `order_goods`;
CREATE TABLE `order_goods` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_number` int(11) unsigned NOT NULL DEFAULT '1',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_attr` text NOT NULL,
  `send_number` int(11) unsigned NOT NULL DEFAULT '0',
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `is_gift` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`rec_id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for order_info
-- ----------------------------
DROP TABLE IF EXISTS `order_info`;
CREATE TABLE `order_info` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(20) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `country` int(11) unsigned NOT NULL DEFAULT '0',
  `province` int(11) unsigned NOT NULL DEFAULT '0',
  `city` int(11) unsigned NOT NULL DEFAULT '0',
  `district` int(11) unsigned NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `best_time` varchar(120) NOT NULL DEFAULT '',
  `sign_building` varchar(120) NOT NULL DEFAULT '',
  `postscript` varchar(255) NOT NULL DEFAULT '',
  `shipping_id` tinyint(3) NOT NULL DEFAULT '0',
  `shipping_name` varchar(120) NOT NULL DEFAULT '',
  `pay_id` tinyint(3) NOT NULL DEFAULT '0',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `how_oos` varchar(120) NOT NULL DEFAULT '',
  `how_surplus` varchar(120) NOT NULL DEFAULT '',
  `pack_name` varchar(120) NOT NULL DEFAULT '',
  `card_name` varchar(120) NOT NULL DEFAULT '',
  `card_message` varchar(255) NOT NULL DEFAULT '',
  `inv_payee` varchar(120) NOT NULL DEFAULT '',
  `inv_content` varchar(120) NOT NULL DEFAULT '',
  `goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `insure_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pack_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `card_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `money_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `surplus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `integral` int(11) unsigned NOT NULL DEFAULT '0',
  `integral_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `from_ad` int(11) NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `confirm_time` int(11) unsigned NOT NULL DEFAULT '0',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0',
  `shipping_time` int(11) unsigned NOT NULL DEFAULT '0',
  `pack_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `card_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bonus_id` int(11) unsigned NOT NULL DEFAULT '0',
  `invoice_no` varchar(255) NOT NULL DEFAULT '',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `extension_id` int(11) unsigned NOT NULL DEFAULT '0',
  `to_buyer` varchar(255) NOT NULL DEFAULT '',
  `pay_note` varchar(255) NOT NULL DEFAULT '',
  `agency_id` int(11) unsigned NOT NULL,
  `inv_type` varchar(60) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `is_separate` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `discount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_sn` (`order_sn`),
  KEY `user_id` (`user_id`),
  KEY `order_status` (`order_status`),
  KEY `shipping_status` (`shipping_status`),
  KEY `pay_status` (`pay_status`),
  KEY `shipping_id` (`shipping_id`),
  KEY `pay_id` (`pay_id`),
  KEY `extension_code` (`extension_code`,`extension_id`),
  KEY `agency_id` (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_order_info
-- ----------------------------

-- ----------------------------
-- Table structure for pack
-- ----------------------------
DROP TABLE IF EXISTS `pack`;
CREATE TABLE `pack` (
  `pack_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pack_name` varchar(120) NOT NULL DEFAULT '',
  `pack_img` varchar(255) NOT NULL DEFAULT '',
  `pack_fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `free_money` int(11) unsigned NOT NULL DEFAULT '0',
  `pack_desc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pack_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_pack
-- ----------------------------

-- ----------------------------
-- Table structure for package_goods
-- ----------------------------
DROP TABLE IF EXISTS `package_goods`;
CREATE TABLE `package_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_number` int(11) unsigned NOT NULL DEFAULT '1',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `package_id_goods_id_admin_id_product_id` (`package_id`,`goods_id`,`admin_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_package_goods
-- ----------------------------

-- ----------------------------
-- Table structure for payment
-- ----------------------------
DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `pay_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(20) NOT NULL DEFAULT '',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `pay_fee` varchar(10) NOT NULL DEFAULT '0',
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_payment
-- ----------------------------

-- ----------------------------
-- Table structure for pay_log
-- ----------------------------
DROP TABLE IF EXISTS `pay_log`;
CREATE TABLE `pay_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `order_amount` decimal(10,2) unsigned NOT NULL,
  `order_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_pay_log
-- ----------------------------

-- ----------------------------
-- Table structure for plugins
-- ----------------------------
DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL DEFAULT '',
  `version` varchar(10) NOT NULL DEFAULT '',
  `library` varchar(255) NOT NULL DEFAULT '',
  `assign` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `install_date` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_plugins
-- ----------------------------

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_attr` varchar(50) DEFAULT NULL,
  `product_sn` varchar(60) DEFAULT NULL,
  `product_number` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_products
-- ----------------------------

-- ----------------------------
-- Table structure for region
-- ----------------------------
DROP TABLE IF EXISTS `region`;
CREATE TABLE `region` (
  `region_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `region_name` varchar(120) NOT NULL DEFAULT '',
  `region_type` tinyint(1) NOT NULL DEFAULT '2',
  `agency_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`region_type`),
  KEY `agency_id` (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_region
-- ----------------------------

-- ----------------------------
-- Table structure for reg_extend_info
-- ----------------------------
DROP TABLE IF EXISTS `reg_extend_info`;
CREATE TABLE `reg_extend_info` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `reg_field_id` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_reg_extend_info
-- ----------------------------

-- ----------------------------
-- Table structure for reg_fields
-- ----------------------------
DROP TABLE IF EXISTS `reg_fields`;
CREATE TABLE `reg_fields` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `reg_field_name` varchar(60) NOT NULL,
  `dis_order` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_need` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_reg_fields
-- ----------------------------

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(60) NOT NULL DEFAULT '',
  `action_list` text NOT NULL,
  `role_describe` text,
  PRIMARY KEY (`role_id`),
  KEY `user_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_role
-- ----------------------------

-- ----------------------------
-- Table structure for searchengine
-- ----------------------------
DROP TABLE IF EXISTS `searchengine`;
CREATE TABLE `searchengine` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '2000-01-01',
  `searchengine` varchar(20) NOT NULL DEFAULT '',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date_searchengine` (`date`,`searchengine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_searchengine
-- ----------------------------

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `sesskey` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `expiry` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `user_name` varchar(60) NOT NULL,
  `user_rank` tinyint(3) NOT NULL,
  `discount` decimal(3,2) NOT NULL,
  `email` varchar(60) NOT NULL,
  `data` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for sessions_data
-- ----------------------------
DROP TABLE IF EXISTS `sessions_data`;
CREATE TABLE `sessions_data` (
  `sesskey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `expiry` int(11) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_sessions_data
-- ----------------------------

-- ----------------------------
-- Table structure for shipping
-- ----------------------------
DROP TABLE IF EXISTS `shipping`;
CREATE TABLE `shipping` (
  `shipping_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_code` varchar(20) NOT NULL DEFAULT '',
  `shipping_name` varchar(120) NOT NULL DEFAULT '',
  `shipping_desc` varchar(255) NOT NULL DEFAULT '',
  `insure` varchar(10) NOT NULL DEFAULT '0',
  `support_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_print` text NOT NULL,
  `print_bg` varchar(255) DEFAULT NULL,
  `config_lable` text,
  `print_model` tinyint(1) DEFAULT '0',
  `shipping_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipping_id`),
  KEY `shipping_code` (`shipping_code`,`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_shipping
-- ----------------------------

-- ----------------------------
-- Table structure for shipping_area
-- ----------------------------
DROP TABLE IF EXISTS `shipping_area`;
CREATE TABLE `shipping_area` (
  `shipping_area_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_area_name` varchar(150) NOT NULL DEFAULT '',
  `shipping_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `configure` text NOT NULL,
  PRIMARY KEY (`shipping_area_id`),
  KEY `shipping_id` (`shipping_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_shipping_area
-- ----------------------------

-- ----------------------------
-- Table structure for shop_config
-- ----------------------------
DROP TABLE IF EXISTS `shop_config`;
CREATE TABLE `shop_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `code` varchar(30) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `store_range` varchar(255) NOT NULL DEFAULT '',
  `store_dir` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_shop_config
-- ----------------------------

-- ----------------------------
-- Table structure for snatch_log
-- ----------------------------
DROP TABLE IF EXISTS `snatch_log`;
CREATE TABLE `snatch_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `snatch_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bid_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bid_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `snatch_id` (`snatch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_snatch_log
-- ----------------------------

-- ----------------------------
-- Table structure for stats
-- ----------------------------
DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `access_time` int(11) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `visit_times` int(11) unsigned NOT NULL DEFAULT '1',
  `browser` varchar(60) NOT NULL DEFAULT '',
  `system` varchar(20) NOT NULL DEFAULT '',
  `language` varchar(20) NOT NULL DEFAULT '',
  `area` varchar(30) NOT NULL DEFAULT '',
  `referer_domain` varchar(100) NOT NULL DEFAULT '',
  `referer_path` varchar(200) NOT NULL DEFAULT '',
  `access_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `access_time` (`access_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_stats
-- ----------------------------

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `suppliers_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `suppliers_name` varchar(255) DEFAULT NULL,
  `suppliers_desc` text,
  `is_check` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`suppliers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_suppliers
-- ----------------------------

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `tag_words` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`tag_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_tag
-- ----------------------------

-- ----------------------------
-- Table structure for template
-- ----------------------------
DROP TABLE IF EXISTS `template`;
CREATE TABLE `template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(30) NOT NULL DEFAULT '',
  `region` varchar(40) NOT NULL DEFAULT '',
  `library` varchar(40) NOT NULL DEFAULT '',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id_value` int(11) unsigned NOT NULL DEFAULT '0',
  `number` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `theme` varchar(60) NOT NULL DEFAULT '',
  `remarks` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`,`region`),
  KEY `theme` (`theme`),
  KEY `remarks` (`remarks`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_template
-- ----------------------------

-- ----------------------------
-- Table structure for topic
-- ----------------------------
DROP TABLE IF EXISTS `topic`;
CREATE TABLE `topic` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '''''',
  `intro` text NOT NULL,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `template` varchar(255) NOT NULL DEFAULT '''''',
  `css` text NOT NULL,
  `topic_img` varchar(255) DEFAULT NULL,
  `title_pic` varchar(255) DEFAULT NULL,
  `base_style` char(6) DEFAULT NULL,
  `htmls` text,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_topic
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL DEFAULT '',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` varchar(255) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '2000-01-01',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_points` int(11) unsigned NOT NULL DEFAULT '0',
  `rank_points` int(11) unsigned NOT NULL DEFAULT '0',
  `address_id` int(11) unsigned NOT NULL DEFAULT '0',
  `reg_time` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
  `last_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `visit_count` int(11) unsigned NOT NULL DEFAULT '0',
  `user_rank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_special` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ec_salt` varchar(10) DEFAULT NULL,
  `salt` varchar(10) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(60) NOT NULL,
  `msn` varchar(60) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `office_phone` varchar(20) NOT NULL,
  `home_phone` varchar(20) NOT NULL,
  `mobile_phone` varchar(20) NOT NULL,
  `is_validated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `credit_line` decimal(10,2) unsigned NOT NULL,
  `passwd_question` varchar(50) DEFAULT NULL,
  `passwd_answer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `email` (`email`),
  KEY `parent_id` (`parent_id`),
  KEY `flag` (`flag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_users
-- ----------------------------

-- ----------------------------
-- Table structure for user_account
-- ----------------------------
DROP TABLE IF EXISTS `user_account`;
CREATE TABLE `user_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_user` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `paid_time` int(11) NOT NULL DEFAULT '0',
  `admin_note` varchar(255) NOT NULL,
  `user_note` varchar(255) NOT NULL,
  `process_type` tinyint(1) NOT NULL DEFAULT '0',
  `payment` varchar(90) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_paid` (`is_paid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_user_account
-- ----------------------------

-- ----------------------------
-- Table structure for user_address
-- ----------------------------
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE `user_address` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `address_name` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `district` int(11) NOT NULL DEFAULT '0',
  `address` varchar(120) NOT NULL DEFAULT '',
  `zipcode` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `sign_building` varchar(120) NOT NULL DEFAULT '',
  `best_time` varchar(120) NOT NULL DEFAULT '',
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_user_address
-- ----------------------------

-- ----------------------------
-- Table structure for user_bonus
-- ----------------------------
DROP TABLE IF EXISTS `user_bonus`;
CREATE TABLE `user_bonus` (
  `bonus_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bonus_type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bonus_sn` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `used_time` int(11) unsigned NOT NULL DEFAULT '0',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `emailed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bonus_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_user_bonus
-- ----------------------------

-- ----------------------------
-- Table structure for user_feed
-- ----------------------------
DROP TABLE IF EXISTS `user_feed`;
CREATE TABLE `user_feed` (
  `feed_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `value_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `feed_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_feed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_user_feed
-- ----------------------------

-- ----------------------------
-- Table structure for user_rank
-- ----------------------------
DROP TABLE IF EXISTS `user_rank`;
CREATE TABLE `user_rank` (
  `rank_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rank_name` varchar(30) NOT NULL DEFAULT '',
  `min_points` int(11) unsigned NOT NULL DEFAULT '0',
  `max_points` int(11) unsigned NOT NULL DEFAULT '0',
  `discount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `show_price` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `special_rank` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_user_rank
-- ----------------------------

-- ----------------------------
-- Table structure for virtual_card
-- ----------------------------
DROP TABLE IF EXISTS `virtual_card`;
CREATE TABLE `virtual_card` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `card_sn` varchar(60) NOT NULL DEFAULT '',
  `card_password` varchar(60) NOT NULL DEFAULT '',
  `add_date` int(11) NOT NULL DEFAULT '0',
  `end_date` int(11) NOT NULL DEFAULT '0',
  `is_saled` tinyint(1) NOT NULL DEFAULT '0',
  `order_sn` varchar(20) NOT NULL DEFAULT '',
  `crc32` varchar(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`card_id`),
  KEY `goods_id` (`goods_id`),
  KEY `car_sn` (`card_sn`),
  KEY `is_saled` (`is_saled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_virtual_card
-- ----------------------------

-- ----------------------------
-- Table structure for volume_price
-- ----------------------------
DROP TABLE IF EXISTS `volume_price`;
CREATE TABLE `volume_price` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `price_type` tinyint(1) unsigned NOT NULL,
  `goods_id` int(11) unsigned NOT NULL,
  `volume_number` int(11) unsigned NOT NULL DEFAULT '0',
  `volume_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `price_type_goods_id_volume_number` (`price_type`,`goods_id`,`volume_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_volume_price
-- ----------------------------

-- ----------------------------
-- Table structure for vote
-- ----------------------------
DROP TABLE IF EXISTS `vote`;
CREATE TABLE `vote` (
  `vote_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vote_name` varchar(250) NOT NULL DEFAULT '',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0',
  `can_multi` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vote_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_vote
-- ----------------------------

-- ----------------------------
-- Table structure for vote_log
-- ----------------------------
DROP TABLE IF EXISTS `vote_log`;
CREATE TABLE `vote_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `vote_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `vote_id` (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_vote_log
-- ----------------------------

-- ----------------------------
-- Table structure for vote_option
-- ----------------------------
DROP TABLE IF EXISTS `vote_option`;
CREATE TABLE `vote_option` (
  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) unsigned NOT NULL DEFAULT '0',
  `option_name` varchar(250) NOT NULL DEFAULT '',
  `option_count` int(8) unsigned NOT NULL DEFAULT '0',
  `option_order` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`option_id`),
  KEY `vote_id` (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_vote_option
-- ----------------------------

-- ----------------------------
-- Table structure for wholesale
-- ----------------------------
DROP TABLE IF EXISTS `wholesale`;
CREATE TABLE `wholesale` (
  `act_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL,
  `goods_name` varchar(255) NOT NULL,
  `rank_ids` varchar(255) NOT NULL,
  `prices` text NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`act_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ecs_wholesale
-- ----------------------------
