-- --------------------------------------------------------
-- Host:                         127.0.0.13
-- Server version:               5.5.27-log - Source distribution
-- Server OS:                    FreeBSD9.0
-- HeidiSQL version:             7.0.0.4206
-- Date/time:                    2012-10-12 17:57:13
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for sweany
DROP DATABASE IF EXISTS `sweany`;
CREATE DATABASE IF NOT EXISTS `sweany` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sweany`;


-- Dumping structure for table sweany.contact
DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `host` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.contact: 0 rows
DELETE FROM `contact`;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;


-- Dumping structure for table sweany.emails
DROP TABLE IF EXISTS `emails`;
CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) NOT NULL DEFAULT '',
  `headers` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.emails: ~0 rows (approximately)
DELETE FROM `emails`;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;


-- Dumping structure for table sweany.failed_logins
DROP TABLE IF EXISTS `failed_logins`;
CREATE TABLE IF NOT EXISTS `failed_logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `hostname` varchar(255) NOT NULL DEFAULT '',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.failed_logins: 0 rows
DELETE FROM `failed_logins`;
/*!40000 ALTER TABLE `failed_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_logins` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_categories
DROP TABLE IF EXISTS `forum_categories`;
CREATE TABLE IF NOT EXISTS `forum_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_categories: 3 rows
DELETE FROM `forum_categories`;
/*!40000 ALTER TABLE `forum_categories` DISABLE KEYS */;
INSERT INTO `forum_categories` (`id`, `name`, `sort`) VALUES
	(1, 'General', 2),
	(2, 'Support', 3),
	(3, 'News', 1);
/*!40000 ALTER TABLE `forum_categories` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_forums
DROP TABLE IF EXISTS `forum_forums`;
CREATE TABLE IF NOT EXISTS `forum_forums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_forum_category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_create` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_reply` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `seo_url` varchar(255) NOT NULL DEFAULT '',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_forum_category_id` (`fk_forum_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_forums: 6 rows
DELETE FROM `forum_forums`;
/*!40000 ALTER TABLE `forum_forums` DISABLE KEYS */;
INSERT INTO `forum_forums` (`id`, `fk_forum_category_id`, `sort`, `display`, `can_create`, `can_reply`, `name`, `description`, `icon`, `seo_url`, `created`, `modified`) VALUES
	(1, 3, 6, 1, 0, 1, 'News', 'These are the news', 'forum_news.png', 'Neuigkeiten.html', 0, 2012),
	(2, 1, 5, 1, 1, 1, 'Talk', 'You can talk in here about whatever you want', 'forum_discussion.png', 'Geplauder.html', 0, 2012),
	(3, 2, 1, 1, 1, 1, 'Help', 'Do you need any help? This is the palce to start', 'forum_bug.png', 'Hilfe.html', 0, 2012),
	(4, 2, 2, 1, 1, 1, 'Features and Bugs', 'You want a new feature? ', 'forum_feedback.png', 'Features-und-Fehler.html', 0, 2012),
	(7, 2, 3, 1, 1, 1, 'Test', 'desc', '', '', 0, 0),
	(5, 1, 4, 1, 1, 1, 'Voting', 'Vote', 'forum_device.png', 'Voting-Aufrufe.html', 0, 2012);
/*!40000 ALTER TABLE `forum_forums` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_posts
DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_forum_thread_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_forum_thread_id` (`fk_forum_thread_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_posts: 1 rows
DELETE FROM `forum_posts`;
/*!40000 ALTER TABLE `forum_posts` DISABLE KEYS */;
INSERT INTO `forum_posts` (`id`, `fk_forum_thread_id`, `fk_user_id`, `title`, `body`, `created`, `modified`) VALUES
	(1, 1, 2, '', 'But normal user are allowed to reply :D', 1350057167, 0);
/*!40000 ALTER TABLE `forum_posts` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_threads
DROP TABLE IF EXISTS `forum_threads`;
CREATE TABLE IF NOT EXISTS `forum_threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_forum_forums_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  `is_sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `seo_url` varchar(255) NOT NULL DEFAULT '',
  `last_post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'stores the id, when the last post was made',
  `last_post_created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'stores the time, when the last post was made',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_forum_forums_id` (`fk_forum_forums_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `last_post_id` (`last_post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_threads: 4 rows
DELETE FROM `forum_threads`;
/*!40000 ALTER TABLE `forum_threads` DISABLE KEYS */;
INSERT INTO `forum_threads` (`id`, `fk_forum_forums_id`, `fk_user_id`, `title`, `body`, `view_count`, `is_sticky`, `is_locked`, `is_closed`, `seo_url`, `last_post_id`, `last_post_created`, `created`, `modified`) VALUES
	(1, 1, 1, 'News entry 1', 'This is news entry number one by admin user.\r\nOnly admin users can create news entries in this forum\r\n\r\n:p', 3, 0, 0, 0, 'News-entry-1.html', 1, 1350057167, 1350057077, 0),
	(2, 1, 1, 'News entry 2', 'Here is another news entry by me\r\n\r\nThis [b]time[/b] with bb code [s]examples[/s]', 1, 0, 0, 0, 'News-entry-2.html', 0, 0, 1350057120, 0),
	(3, 2, 3, 'New thread by me', ':):D:roll:\n[code]\n	require(CORE_BOOTSTRAP.DS.\'Validator.php\');\n	require(CORE_VALIDATOR.DS.\'Validate01Basics.php\');\n	require(CORE_VALIDATOR.DS.\'Validate02Config.php\');\n	require(CORE_VALIDATOR.DS.\'Validate03Language.php\');\n	require(CORE_VALIDATOR.DS.\'Validate04Database.php\');\n	require(CORE_VALIDATOR.DS.\'Validate05Tables.php\');\n	require(CORE_VALIDATOR.DS.\'Validate06User.php\');\n	require(CORE_VALIDATOR.DS.\'Validate07UserOnlineCount.php\');\n	require(CORE_VALIDATOR.DS.\'Validate08LogVisitors.php\');\n	require(CORE_VALIDATOR.DS.\'Validate09Plugins.php\');\n[/code]', 1, 0, 0, 0, 'New-thread-by-me.html', 0, 0, 1350057238, 1350057260),
	(4, 2, 3, 'Please note', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 2, 1, 1, 0, 'Please-note.html', 0, 0, 1350057300, 0);
/*!40000 ALTER TABLE `forum_threads` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_thread_is_read
DROP TABLE IF EXISTS `forum_thread_is_read`;
CREATE TABLE IF NOT EXISTS `forum_thread_is_read` (
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_forum_thread_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_user_id`,`fk_forum_thread_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.forum_thread_is_read: ~0 rows (approximately)
DELETE FROM `forum_thread_is_read`;
/*!40000 ALTER TABLE `forum_thread_is_read` DISABLE KEYS */;
/*!40000 ALTER TABLE `forum_thread_is_read` ENABLE KEYS */;


-- Dumping structure for table sweany.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_salt` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL COMMENT 'The user''s chosen page theme (if different theming exists)',
  `timezone` varchar(255) NOT NULL COMMENT 'The user''s chosen timezone (useful for date/time outputs)',
  `language` varchar(255) NOT NULL COMMENT 'The user''s chosen language (will affect the language core module behavior)',
  `has_accepted_terms` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'The user has accepted this site''s policy',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'USE WITH CAUTION! If set to 1, the user will have access to all admin PageControllers ($admin_area = true)',
  `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_fake` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'If you create fake users for your portal, you can mark them internally',
  `validation_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'If the user has registered, this is the key that will be sent via mail to him and matched against, to validate his/her registration',
  `reset_password_key` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(255) NOT NULL DEFAULT '',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `last_host` varchar(255) NOT NULL DEFAULT '',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Unix timestamp of last login',
  `last_failed_login_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Count failed logins since last successful login',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Unix timestamp of account creation',
  `modified` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Unix timestamp of account modification',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.users: 6 rows
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `password_salt`, `email`, `signature`, `theme`, `timezone`, `language`, `has_accepted_terms`, `is_admin`, `is_enabled`, `is_deleted`, `is_locked`, `is_fake`, `validation_key`, `reset_password_key`, `session_id`, `last_ip`, `last_host`, `last_login`, `last_failed_login_count`, `created`, `modified`) VALUES
	(4, 'demo3', '250a8c97e53920fe3b7a814af7eddbf143ddfda41110d0e52cfd154421a38aa7055eade9e43cbe54cb271b6abcedb938df51505d30773139fb3aa678be00dbb4', '5e9d64f9551addc20649079beaa9fe1bac18e9ff7eff4e09d0b6370409f5fad78dabe58e62b7f172f750e9024fd68a446c679d8fd7cd7797eb3451a6b6826b97', 'demo3@domain.tld', '', '', '', '', 1, 0, 1, 0, 0, 0, '', '', '', '', '', 0, 0, 1350057029, 0),
	(3, 'demo2', '6099d9454583ee029b37b672c07dcec0c4c198a7c43ddd0016baab09845009cc95d5f4f665bcd1872b84791cb4a808bc45183191fad5838ed786a1641cf7973c', 'fdff43bab85b26d9f997805bfe958e2094a40d98e7f1346a3eb3c8da7942e110c7d0a76e86d1451de08efdebf06db53b48a8176f9ca5e0bd58654f2a476f3715', 'demo2@domain.tld', '', '', '', '', 1, 0, 1, 0, 0, 0, '', '', '', '', '', 1350057193, 0, 1350057029, 0),
	(2, 'demo1', 'a4a173c8551c05e852433e9b45a16c681a8e47f12cac9ae9d203fe3821df78d2bbc517c733048f86efa7bf8b9c63b516422732d1ac961afdaba7ca06713028ec', 'eed8d4791e9e4bdd1521bae9dce4ab56252b4f4e1cf9552f9904bdc7ef3aa68971af8648197a01633777d7d2dc02e8802816fb9a14cfb7358e9c665c614282d4', 'demo1@domain.tld', '', '', '', '', 1, 0, 1, 0, 0, 0, '', '', '', '', '', 1350057146, 0, 1350057029, 0),
	(5, 'demo4', 'c291746e4eeaefda430b6b167a6f0cec3cb0632cc9b0240c0df60054c106f441bd1a3332749357dde8c2aa8d3d1230d04f6d3826f82f8cc13f2a2b36d56345f8', 'e88a5791b320d8df3d60dd1d16fb37bf6be1cd3371e56f935cfc1e955db35ddd782ba89579aad730020d0a821637229c74abae11ceda8fc7601e0f77135ac64b', 'demo4@domain.tld', '', '', '', '', 1, 0, 1, 0, 0, 0, '', '', '', '', '', 0, 0, 1350057029, 0),
	(6, 'demo5', 'd5134f97ca815ba20a6927be84126eb063fc012c07d4b3b5c4deefa857df46e06c7263fe4228d20ba1eeeb83711e6f3d6649b0aaa161a01d31bd7371752a6668', '627e6698876d73e28e85214a29e01fc32caaad48a75dc62ba9d6c6252e1366fb624d6ee92261ad01940ad5586efeeaacee409f250374c798095f55aa82fe3281', 'demo5@domain.tld', '', '', '', '', 1, 0, 1, 0, 0, 0, '', '', '', '', '', 0, 0, 1350057029, 0),
	(1, 'admin', '75db2364df0e77b69e2c8744f3f02b5d2312c3ea9aa17f1deb7a067c2111e6c61e85265ec7414b6c7f463be564360dc0a53a3ecb65846e140723edff433cca5a', '2a54ac26d8ac337666b2fedeea160e039dcc957a9f7bb3b7544639ca8c00f336ca4908343f88cd8e65172ee1d583421a6ba00789cc879d951134b2e47a5a883b', 'admin@domain.tld', '', '', '', '', 1, 1, 1, 0, 0, 0, '', '', '', '', '', 1350057029, 0, 1350057029, 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table sweany.user_groups
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `permissions` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.user_groups: ~0 rows (approximately)
DELETE FROM `user_groups`;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;


-- Dumping structure for table sweany.user_group_link
DROP TABLE IF EXISTS `user_group_link`;
CREATE TABLE IF NOT EXISTS `user_group_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(10) unsigned DEFAULT NULL,
  `fk_user_group_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.user_group_link: ~0 rows (approximately)
DELETE FROM `user_group_link`;
/*!40000 ALTER TABLE `user_group_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_group_link` ENABLE KEYS */;


-- Dumping structure for table sweany.user_online
DROP TABLE IF EXISTS `user_online`;
CREATE TABLE IF NOT EXISTS `user_online` (
  `time` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL,
  `session_id` varchar(30) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `current_page` varchar(50) NOT NULL,
  KEY `fk_user_id` (`fk_user_id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.user_online: 0 rows
DELETE FROM `user_online`;
/*!40000 ALTER TABLE `user_online` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_online` ENABLE KEYS */;


-- Dumping structure for table sweany.visitors
DROP TABLE IF EXISTS `visitors`;
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '31',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `host` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.visitors: 0 rows
DELETE FROM `visitors`;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
