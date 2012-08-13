-- --------------------------------------------------------
-- Host:                         127.0.0.13
-- Server version:               5.5.20-log - Source distribution
-- Server OS:                    FreeBSD9.0
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-08-13 12:26:01
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
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `referer` varchar(255) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `host` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_posts: 5 rows
DELETE FROM `forum_posts`;
/*!40000 ALTER TABLE `forum_posts` DISABLE KEYS */;
INSERT INTO `forum_posts` (`id`, `fk_forum_thread_id`, `fk_user_id`, `title`, `body`, `created`, `modified`) VALUES
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
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_threads: 9 rows
DELETE FROM `forum_threads`;
/*!40000 ALTER TABLE `forum_threads` DISABLE KEYS */;
INSERT INTO `forum_threads` (`id`, `fk_forum_forums_id`, `fk_user_id`, `title`, `body`, `view_count`, `is_sticky`, `is_locked`, `is_closed`, `seo_url`, `last_post_id`, `last_post_created`, `created`, `modified`) VALUES
	(47, 5, 39, 'some title', 'wre g sfdg', 2, 0, 0, 0, '.html', 0, '0000-00-00 00:00:00', '2012-08-13 12:11:32', '0000-00-00 00:00:00'),
	(40, 3, 32, 'Hueeelefe', '[b]I need help[/b]', 5, 0, 0, 0, 'Hueeelefe.html', 70, '2012-08-02 12:37:20', '2012-08-02 12:35:27', '0000-00-00 00:00:00'),
	(41, 4, 33, 'What is a bug?', 'What is a bug?', 3, 0, 0, 0, 'What-is-a-bug.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:36:17', '0000-00-00 00:00:00'),
	(42, 2, 33, 'talk', 'I thought about going to a talkshow, but then I found this :D', 1, 0, 0, 0, 'talk.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:36:52', '0000-00-00 00:00:00'),
	(43, 2, 31, 'Talk...', 'Do whatever you want here', 7, 1, 0, 0, 'Talk....html', 0, '0000-00-00 00:00:00', '2012-08-02 12:37:37', '0000-00-00 00:00:00'),
	(44, 2, 31, 'locked thread', 'This is an example of a locked thread', 1, 0, 1, 0, 'locked-thread.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:38:17', '0000-00-00 00:00:00'),
	(46, 1, 31, 'further news', '[u]here are more news[/u]', 2, 0, 0, 0, 'further-news.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:41:39', '0000-00-00 00:00:00'),
	(45, 2, 31, 'closed thread', 'This is an example of a closed thread', 2, 0, 0, 1, 'closed-thread.html', 0, '0000-00-00 00:00:00', '2012-08-02 12:38:36', '0000-00-00 00:00:00'),
	(39, 1, 31, 'News by admin', 'Only admin can create news here :D\n\n[code]\n * @copyright		Copyright 2011-2012, Patu\n * @link		none yet\n * @package		sweany.sys\n * @author		Patu\n * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)\n * @version		0.7 2012-07-29 13:25\n[/code]\n\nnachtrag', 9, 0, 0, 0, 'News-by-admin.html', 71, '2012-08-13 10:27:59', '2012-08-02 12:33:21', '2012-08-02 13:08:17');
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
  `validation_key` varchar(255) NOT NULL DEFAULT '',
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
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.users: 6 rows
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `password_salt`, `email`, `has_accepted_terms`, `is_admin`, `is_enabled`, `is_deleted`, `is_locked`, `validation_key`, `session_id`, `last_ip`, `last_host`, `last_login`, `last_failed_login_count`, `created`, `modified`) VALUES
	(31, 'demo5', 'cb61d5932f86b2a9a71b7f5940f09edf', '01452f605343b935407d266bdc232f85', 'demo5@demo5.com', 1, 1, 1, 0, 0, 'fcdf650cf67bc4803f7b0952cd7c73a5', '', '', '', '0000-00-00 00:00:00', 0, '2012-08-13 12:21:46', '0000-00-00 00:00:00'),
	(32, 'demo4', 'd8ef884c746d8f8451fc8da9df32991b', '4533485de277fb939a2d18309818dbbb', 'demo4@demo4.com', 1, 0, 1, 0, 0, '7a1ded23a3a3908ae19f14b0e3355a43', '', '', '', '0000-00-00 00:00:00', 0, '2012-08-13 12:21:31', '0000-00-00 00:00:00'),
	(33, 'demo3', '1270dafa98acbaaf6bb1c2907e1f1943', '5f9fc4a2b7e45fe241d957cb6b51ec86', 'demo3@demo3.com', 1, 0, 1, 0, 0, '29a5825f1b5a86ae7061ad6035e1a58b', '', '', '', '0000-00-00 00:00:00', 0, '2012-08-13 12:21:19', '0000-00-00 00:00:00'),
	(34, 'demo2', '52143f3b814ae2d73f2bd663ae0cac67', '36724a7000a0ab9912141f498a0de678', 'demo2@demo2.com', 1, 0, 1, 0, 0, 'b3d22aee73130e272f4027421fe0d899', '', '', '', '0000-00-00 00:00:00', 0, '2012-08-13 12:21:09', '0000-00-00 00:00:00'),
	(35, 'demo1', 'b52313792496156f0ccfa129c4250b23', '362c517e3c9133079157952ede1cf06f', 'demo1@demo1.com', 1, 0, 1, 0, 0, '92e08c885c6b57a0d246221272e876de', '', '', '', '0000-00-00 00:00:00', 0, '2012-08-13 12:20:58', '0000-00-00 00:00:00'),
	(39, 'admin', '143f3fb9200899b3a9cff41ab315ce87', 'd8971e0b9b34745ec1c0da4654953904', 'admin@admin.com', 1, 0, 1, 0, 0, '19b5db1c3db700d8098b77b2926ce38f', '', '', '', '0000-00-00 00:00:00', 0, '2012-08-13 12:20:44', '0000-00-00 00:00:00');
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.visitors: 0 rows
DELETE FROM `visitors`;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
