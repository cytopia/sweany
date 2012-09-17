-- --------------------------------------------------------
-- Host:                         127.0.0.13
-- Server version:               5.5.27-log - Source distribution
-- Server OS:                    FreeBSD9.0
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-09-17 13:48:32
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for sweany
DROP DATABASE IF EXISTS `sweany`;
CREATE DATABASE IF NOT EXISTS `sweany` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sweany`;


-- Dumping structure for table sweany.contact
DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.emails: ~0 rows (approximately)
DELETE FROM `emails`;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;


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
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_forum_category_id` (`fk_forum_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_forums: 5 rows
DELETE FROM `forum_forums`;
/*!40000 ALTER TABLE `forum_forums` DISABLE KEYS */;
INSERT INTO `forum_forums` (`id`, `fk_forum_category_id`, `sort`, `display`, `can_create`, `can_reply`, `name`, `description`, `icon`, `seo_url`, `created`, `modified`) VALUES
	(1, 3, 0, 1, 0, 1, 'News', 'These are the news', 'forum_news.png', 'Neuigkeiten.html', '0000-00-00 00:00:00', '2012-01-12 21:33:54'),
	(2, 1, 0, 1, 1, 1, 'Talk', 'You can talk in here about whatever you want', 'forum_discussion.png', 'Geplauder.html', '0000-00-00 00:00:00', '2012-01-04 16:54:07'),
	(3, 2, 1, 1, 1, 1, 'Help', 'Do you need any help? This is the palce to start', 'forum_bug.png', 'Hilfe.html', '0000-00-00 00:00:00', '2012-01-04 16:54:09'),
	(4, 2, 2, 1, 1, 1, 'Features and Bugs', 'You want a new feature? ', 'forum_feedback.png', 'Features-und-Fehler.html', '0000-00-00 00:00:00', '2012-01-04 16:54:10'),
	(5, 1, 0, 1, 1, 1, 'Voting shit', 'Vote', 'forum_device.png', 'Voting-Aufrufe.html', '0000-00-00 00:00:00', '2012-01-07 20:22:12');
/*!40000 ALTER TABLE `forum_forums` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_posts
DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_forum_thread_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_forum_thread_id` (`fk_forum_thread_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_posts: 14 rows
DELETE FROM `forum_posts`;
/*!40000 ALTER TABLE `forum_posts` DISABLE KEYS */;
INSERT INTO `forum_posts` (`id`, `fk_forum_thread_id`, `fk_user_id`, `title`, `body`, `created`, `modified`) VALUES
	(80, 48, 33, '', ':D', '2012-09-16 15:18:47', '0000-00-00 00:00:00'),
	(79, 48, 34, '', ':D', '2012-09-16 15:18:33', '0000-00-00 00:00:00'),
	(78, 48, 35, '', ':D', '2012-09-16 15:17:17', '0000-00-00 00:00:00'),
	(77, 49, 39, '', '[url=null]null[/url][url=http://test]http://test[/url]', '2012-08-14 13:33:08', '0000-00-00 00:00:00'),
	(76, 42, 39, '', ':red::cry::[u]Da ds[/u]', '2012-08-14 13:22:59', '0000-00-00 00:00:00'),
	(75, 42, 39, 'sdfg', 'dsfgdfgdf', '2012-08-14 13:22:48', '0000-00-00 00:00:00'),
	(74, 42, 39, '', ':roll::p:red:', '2012-08-14 13:14:13', '0000-00-00 00:00:00'),
	(73, 42, 39, '', ':confuse::red::p:roll::) asd ', '2012-08-14 13:08:38', '2012-08-14 13:14:49'),
	(72, 42, 39, '', 'tester', '2012-08-14 13:05:27', '2012-08-14 13:22:40'),
	(71, 39, 39, '', ':confuse::red:', '2012-08-13 10:27:59', '0000-00-00 00:00:00'),
	(70, 40, 31, '', 'Be patient', '2012-08-02 12:37:20', '0000-00-00 00:00:00'),
	(68, 39, 32, '', ':):D:roll::(:p:red:', '2012-08-02 12:35:08', '0000-00-00 00:00:00'),
	(69, 40, 33, '', 'indeed you do!', '2012-08-02 12:35:59', '0000-00-00 00:00:00'),
	(67, 39, 32, '', ':):D:roll::(:p:red:', '2012-08-02 12:33:56', '0000-00-00 00:00:00');
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
  `last_post_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'stores the time, when the last post was made',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_forum_forums_id` (`fk_forum_forums_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_threads: 13 rows
DELETE FROM `forum_threads`;
/*!40000 ALTER TABLE `forum_threads` DISABLE KEYS */;
INSERT INTO `forum_threads` (`id`, `fk_forum_forums_id`, `fk_user_id`, `title`, `body`, `view_count`, `is_sticky`, `is_locked`, `is_closed`, `seo_url`, `last_post_id`, `last_post_created`, `created`, `modified`) VALUES
	(50, 2, 39, 'gsfdgsdg', 'fsdaf sd fsd fsdsadf sd', 1, 0, 0, 0, '.html', 0, '0000-00-00 00:00:00', '2012-08-14 14:15:27', '0000-00-00 00:00:00'),
	(51, 2, 39, 'asdasd', 'asd', 2, 0, 0, 0, 'asdasd.html', 0, '0000-00-00 00:00:00', '2012-08-14 19:13:19', '0000-00-00 00:00:00'),
	(49, 5, 39, 'sadfsad', ':roll::D:)[url=http://]http://[/url]', 11, 0, 0, 0, '.html', 77, '2012-08-14 13:33:08', '2012-08-14 13:32:53', '0000-00-00 00:00:00'),
	(48, 2, 39, ':(:red::confuse::)', ':(:red::confuse::)da sd ', 13, 0, 0, 0, 'redconfuse.html', 80, '2012-09-16 15:18:47', '2012-08-14 13:15:05', '2012-08-14 13:16:06'),
	(47, 5, 39, 'some title', 'wre g sfdg', 6, 0, 0, 0, '.html', 0, '0000-00-00 00:00:00', '2012-08-13 12:11:32', '0000-00-00 00:00:00'),
	(40, 3, 32, 'Hueeelefe', '[b]I need help[/b]', 6, 0, 0, 0, 'Hueeelefe.html', 70, '2012-08-02 12:37:20', '2012-08-02 12:35:27', '0000-00-00 00:00:00'),
	(41, 4, 33, 'What is a bug?', 'What is a bug?', 3, 0, 0, 0, 'What-is-a-bug.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:36:17', '0000-00-00 00:00:00'),
	(42, 2, 33, 'talk', 'I thought about going to a talkshow, but then I found this :D', 40, 0, 0, 0, 'talk.html', 76, '2012-08-14 13:22:59', '2012-08-02 12:36:52', '0000-00-00 00:00:00'),
	(43, 2, 31, 'Talk...', 'Do whatever you want here', 15, 1, 0, 0, 'Talk....html', 0, '0000-00-00 00:00:00', '2012-08-02 12:37:37', '0000-00-00 00:00:00'),
	(44, 2, 31, 'locked thread', 'This is an example of a locked thread', 1, 0, 1, 0, 'locked-thread.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:38:17', '0000-00-00 00:00:00'),
	(46, 1, 31, 'further news', '[u]here are more news[/u]', 4, 0, 0, 0, 'further-news.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:41:39', '0000-00-00 00:00:00'),
	(45, 2, 31, 'closed thread', 'This is an example of a closed thread', 4, 0, 0, 1, 'closed-thread.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:38:36', '0000-00-00 00:00:00'),
	(39, 1, 31, 'News by admin', 'Only admin can create news here :D\n\n[code]\n * @copyright		Copyright 2011-2012, Patu\n * @link		none yet\n * @package		sweany.sys\n * @author		Patu\n * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)\n * @version		0.7 2012-07-29 13:25\n[/code]\n\nnachtrag', 18, 0, 0, 0, 'News-by-admin.html', 71, '2012-08-13 10:27:59', '2012-08-02 12:33:21', '2012-08-02 13:08:17');
/*!40000 ALTER TABLE `forum_threads` ENABLE KEYS */;


-- Dumping structure for table sweany.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_salt` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `has_accepted_terms` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'The user has accepted this site''s policy',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_fake` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `validation_key` varchar(255) NOT NULL DEFAULT '',
  `reset_password_key` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(255) NOT NULL DEFAULT '',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `last_host` varchar(255) NOT NULL DEFAULT '',
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_failed_login_count` int(10) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.users: 6 rows
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `password_salt`, `email`, `has_accepted_terms`, `is_admin`, `is_enabled`, `is_deleted`, `is_locked`, `is_fake`, `validation_key`, `reset_password_key`, `session_id`, `last_ip`, `last_host`, `last_login`, `last_failed_login_count`, `created`, `modified`) VALUES
	(39, 'demo3', '250a8c97e53920fe3b7a814af7eddbf143ddfda41110d0e52cfd154421a38aa7055eade9e43cbe54cb271b6abcedb938df51505d30773139fb3aa678be00dbb4', '5e9d64f9551addc20649079beaa9fe1bac18e9ff7eff4e09d0b6370409f5fad78dabe58e62b7f172f750e9024fd68a446c679d8fd7cd7797eb3451a6b6826b97', 'demo3@domain.tld', 1, 0, 1, 0, 0, 0, '', '', '', '', '', '0000-00-00 00:00:00', 0, '2012-09-16 15:02:01', '0000-00-00 00:00:00'),
	(35, 'demo2', '6099d9454583ee029b37b672c07dcec0c4c198a7c43ddd0016baab09845009cc95d5f4f665bcd1872b84791cb4a808bc45183191fad5838ed786a1641cf7973c', 'fdff43bab85b26d9f997805bfe958e2094a40d98e7f1346a3eb3c8da7942e110c7d0a76e86d1451de08efdebf06db53b48a8176f9ca5e0bd58654f2a476f3715', 'demo2@domain.tld', 1, 0, 1, 0, 0, 0, '', '', '', '', '', '0000-00-00 00:00:00', 0, '2012-09-16 14:58:35', '0000-00-00 00:00:00'),
	(34, 'demo1', 'a4a173c8551c05e852433e9b45a16c681a8e47f12cac9ae9d203fe3821df78d2bbc517c733048f86efa7bf8b9c63b516422732d1ac961afdaba7ca06713028ec', 'eed8d4791e9e4bdd1521bae9dce4ab56252b4f4e1cf9552f9904bdc7ef3aa68971af8648197a01633777d7d2dc02e8802816fb9a14cfb7358e9c665c614282d4', 'demo1@domain.tld', 1, 0, 1, 0, 0, 0, '', '', '', '', '', '0000-00-00 00:00:00', 0, '2012-09-16 14:56:24', '0000-00-00 00:00:00'),
	(33, 'demo4', 'c291746e4eeaefda430b6b167a6f0cec3cb0632cc9b0240c0df60054c106f441bd1a3332749357dde8c2aa8d3d1230d04f6d3826f82f8cc13f2a2b36d56345f8', 'e88a5791b320d8df3d60dd1d16fb37bf6be1cd3371e56f935cfc1e955db35ddd782ba89579aad730020d0a821637229c74abae11ceda8fc7601e0f77135ac64b', 'demo4@domain.tld', 1, 0, 1, 0, 0, 0, '', '', '', '', '', '0000-00-00 00:00:00', 0, '2012-09-16 15:02:49', '0000-00-00 00:00:00'),
	(32, 'demo5', 'd5134f97ca815ba20a6927be84126eb063fc012c07d4b3b5c4deefa857df46e06c7263fe4228d20ba1eeeb83711e6f3d6649b0aaa161a01d31bd7371752a6668', '627e6698876d73e28e85214a29e01fc32caaad48a75dc62ba9d6c6252e1366fb624d6ee92261ad01940ad5586efeeaacee409f250374c798095f55aa82fe3281', 'demo5@domain.tld', 1, 0, 1, 0, 0, 0, '', '', '', '', '', '0000-00-00 00:00:00', 0, '2012-09-16 15:03:37', '0000-00-00 00:00:00'),
	(31, 'admin', '75db2364df0e77b69e2c8744f3f02b5d2312c3ea9aa17f1deb7a067c2111e6c61e85265ec7414b6c7f463be564360dc0a53a3ecb65846e140723edff433cca5a', '2a54ac26d8ac337666b2fedeea160e039dcc957a9f7bb3b7544639ca8c00f336ca4908343f88cd8e65172ee1d583421a6ba00789cc879d951134b2e47a5a883b', 'admin@domain.tld', 1, 1, 1, 0, 0, 0, '', '', '', '', '', '2012-09-17 13:44:33', 0, '2012-09-16 15:09:31', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table sweany.user_failed_logins
DROP TABLE IF EXISTS `user_failed_logins`;
CREATE TABLE IF NOT EXISTS `user_failed_logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `hostname` varchar(255) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.user_failed_logins: 0 rows
DELETE FROM `user_failed_logins`;
/*!40000 ALTER TABLE `user_failed_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_failed_logins` ENABLE KEYS */;


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
  `referer` varchar(255) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `host` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=785 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.visitors: 0 rows
DELETE FROM `visitors`;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
