-- --------------------------------------------------------
-- Host:                         127.0.0.13
-- Server version:               5.5.28-log - Source distribution
-- Server OS:                    FreeBSD9.1
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-11-30 20:52:22
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
  KEY `fk_user_id` (`fk_user_id`),
  KEY `created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.contact: 0 rows
DELETE FROM `contact`;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;


-- Dumping structure for table sweany.core_emails
DROP TABLE IF EXISTS `core_emails`;
CREATE TABLE IF NOT EXISTS `core_emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) NOT NULL DEFAULT '',
  `headers` varchar(512) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table sweany.core_emails: ~0 rows (approximately)
DELETE FROM `core_emails`;
/*!40000 ALTER TABLE `core_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_emails` ENABLE KEYS */;


-- Dumping structure for table sweany.core_failed_logins
DROP TABLE IF EXISTS `core_failed_logins`;
CREATE TABLE IF NOT EXISTS `core_failed_logins` (
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.core_failed_logins: 0 rows
DELETE FROM `core_failed_logins`;
/*!40000 ALTER TABLE `core_failed_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_failed_logins` ENABLE KEYS */;


-- Dumping structure for table sweany.core_lang
DROP TABLE IF EXISTS `core_lang`;
CREATE TABLE IF NOT EXISTS `core_lang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` int(10) unsigned NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'def',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_language` (`group`,`language`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table sweany.core_lang: ~19 rows (approximately)
DELETE FROM `core_lang`;
/*!40000 ALTER TABLE `core_lang` DISABLE KEYS */;
INSERT INTO `core_lang` (`id`, `group`, `text`, `language`) VALUES
	(152, 1, 'General', 'def'),
	(153, 2, 'What is this?', 'def'),
	(154, 3, 'Are your sure?', 'def'),
	(155, 4, 'Another Section', 'def'),
	(156, 5, 'Test Question', 'def'),
	(157, 6, 'Another Question?', 'def'),
	(158, 7, 'This is something you won\'t know', 'def'),
	(159, 8, 'Yes I am totally sure', 'def'),
	(160, 9, 'And here is your test answer', 'def'),
	(161, 10, 'Feel free to ask more :-)', 'def'),
	(162, 11, 'Home', 'def'),
	(163, 12, 'Forum', 'def'),
	(164, 13, 'FAQ', 'def'),
	(165, 14, 'Guestbook', 'def'),
	(166, 15, 'Contact', 'def'),
	(167, 16, 'Test', 'def'),
	(168, 17, 'Messages', 'def'),
	(169, 18, 'Edit Data', 'def'),
	(170, 19, 'Settings', 'def');
/*!40000 ALTER TABLE `core_lang` ENABLE KEYS */;


-- Dumping structure for table sweany.core_lang_sections
DROP TABLE IF EXISTS `core_lang_sections`;
CREATE TABLE IF NOT EXISTS `core_lang_sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` int(10) unsigned DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lang_id` (`group`),
  KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=568 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table sweany.core_lang_sections: ~61 rows (approximately)
DELETE FROM `core_lang_sections`;
/*!40000 ALTER TABLE `core_lang_sections` DISABLE KEYS */;
INSERT INTO `core_lang_sections` (`id`, `group`, `url`) VALUES
	(507, 1, 'Faq/index'),
	(508, 2, 'Faq/index'),
	(509, 3, 'Faq/index'),
	(510, 4, 'Faq/index'),
	(511, 5, 'Faq/index'),
	(512, 6, 'Faq/index'),
	(513, 7, 'Faq/index'),
	(514, 8, 'Faq/index'),
	(515, 9, 'Faq/index'),
	(516, 10, 'Faq/index'),
	(517, 11, 'Faq/index'),
	(518, 12, 'Faq/index'),
	(519, 13, 'Faq/index'),
	(520, 14, 'Faq/index'),
	(521, 15, 'Faq/index'),
	(522, 16, 'Faq/index'),
	(523, 11, 'admin/translations'),
	(524, 12, 'admin/translations'),
	(525, 13, 'admin/translations'),
	(526, 14, 'admin/translations'),
	(527, 15, 'admin/translations'),
	(528, 16, 'admin/translations'),
	(529, 11, 'admin/favicon.ico'),
	(530, 12, 'admin/favicon.ico'),
	(531, 13, 'admin/favicon.ico'),
	(532, 14, 'admin/favicon.ico'),
	(533, 15, 'admin/favicon.ico'),
	(534, 16, 'admin/favicon.ico'),
	(535, 17, 'Home/index'),
	(536, 18, 'Home/index'),
	(537, 19, 'Home/index'),
	(538, 11, 'Home/index'),
	(539, 12, 'Home/index'),
	(540, 13, 'Home/index'),
	(541, 14, 'Home/index'),
	(542, 15, 'Home/index'),
	(543, 16, 'Home/index'),
	(544, 11, 'Forums/index'),
	(545, 12, 'Forums/index'),
	(546, 13, 'Forums/index'),
	(547, 14, 'Forums/index'),
	(548, 15, 'Forums/index'),
	(549, 16, 'Forums/index'),
	(550, 11, 'Guestbook/index'),
	(551, 12, 'Guestbook/index'),
	(552, 13, 'Guestbook/index'),
	(553, 14, 'Guestbook/index'),
	(554, 15, 'Guestbook/index'),
	(555, 16, 'Guestbook/index'),
	(556, 11, 'Contact/index'),
	(557, 12, 'Contact/index'),
	(558, 13, 'Contact/index'),
	(559, 14, 'Contact/index'),
	(560, 15, 'Contact/index'),
	(561, 16, 'Contact/index'),
	(562, 11, 'Test/index'),
	(563, 12, 'Test/index'),
	(564, 13, 'Test/index'),
	(565, 14, 'Test/index'),
	(566, 15, 'Test/index'),
	(567, 16, 'Test/index');
/*!40000 ALTER TABLE `core_lang_sections` ENABLE KEYS */;


-- Dumping structure for table sweany.core_online_users
DROP TABLE IF EXISTS `core_online_users`;
CREATE TABLE IF NOT EXISTS `core_online_users` (
  `time` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL,
  `session_id` varchar(30) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `current_page` varchar(50) NOT NULL,
  KEY `fk_user_id` (`fk_user_id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.core_online_users: 0 rows
DELETE FROM `core_online_users`;
/*!40000 ALTER TABLE `core_online_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_online_users` ENABLE KEYS */;


-- Dumping structure for table sweany.core_users
DROP TABLE IF EXISTS `core_users`;
CREATE TABLE IF NOT EXISTS `core_users` (
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
  `deleted` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Unix timestamp of account deletion time (if using flag "is_deleted")',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.core_users: 6 rows
DELETE FROM `core_users`;
/*!40000 ALTER TABLE `core_users` DISABLE KEYS */;
INSERT INTO `core_users` (`id`, `username`, `password`, `password_salt`, `email`, `signature`, `theme`, `timezone`, `language`, `has_accepted_terms`, `is_admin`, `is_enabled`, `is_deleted`, `is_locked`, `is_fake`, `validation_key`, `reset_password_key`, `session_id`, `last_ip`, `last_host`, `last_login`, `last_failed_login_count`, `created`, `modified`, `deleted`) VALUES
	(4, 'demo3', '250a8c97e53920fe3b7a814af7eddbf143ddfda41110d0e52cfd154421a38aa7055eade9e43cbe54cb271b6abcedb938df51505d30773139fb3aa678be00dbb4', '5e9d64f9551addc20649079beaa9fe1bac18e9ff7eff4e09d0b6370409f5fad78dabe58e62b7f172f750e9024fd68a446c679d8fd7cd7797eb3451a6b6826b97', 'demo3@domain.tld', '', '', 'Europe/Berlin', 'de', 1, 0, 1, 0, 0, 0, '', '', 'hfsgk30fq6lvr5bhei0j94fpu6', '192.168.0.244', '192.168.0.244', 1353241927, 1, 1350057029, 0, 0),
	(3, 'demo2', '6099d9454583ee029b37b672c07dcec0c4c198a7c43ddd0016baab09845009cc95d5f4f665bcd1872b84791cb4a808bc45183191fad5838ed786a1641cf7973c', '5a82b15406fbab6f0409219d691237b7c6076a96f0bd732406aa44b93399a266937717780cae92f24bf2f76e2c1768b791ea4462f7d204340d75c9a1dbf86f4e', 'demo2@domain.tld', '', '', 'Europe/Berlin', 'de', 1, 0, 1, 0, 0, 0, '', '', '', '', '', 1350057193, 0, 1350057029, 0, 0),
	(2, 'demo1', '1c6356c790b9525a1ed6bfe883ceff6916588774c1a0f085d7d815487980139c4995a2778d65dac2ba5ded87aceff0fcc0dca10c61183ee97145b00699173f16', '0ee8df6f00770d4a09e78daff105ba7ef36a7c7bc8eee13b74b4b1fb5d94386a44dd39403fb178ba31b7fff634287c3c8a762c61fbd12b2e5b497a70a85f606d', 'demo1@domain.tld', '', '', 'Europe/Berlin', 'de', 1, 0, 1, 0, 0, 0, '', '', 'l5lba0dn083a18r7at1o4mqcg7', '192.168.0.244', '192.168.0.244', 1353865718, 0, 1350057029, 0, 0),
	(5, 'demo4', 'c291746e4eeaefda430b6b167a6f0cec3cb0632cc9b0240c0df60054c106f441bd1a3332749357dde8c2aa8d3d1230d04f6d3826f82f8cc13f2a2b36d56345f8', 'e88a5791b320d8df3d60dd1d16fb37bf6be1cd3371e56f935cfc1e955db35ddd782ba89579aad730020d0a821637229c74abae11ceda8fc7601e0f77135ac64b', 'demo4@domain.tld', '', '', 'Europe/Berlin', 'de', 1, 0, 1, 0, 0, 0, '', '', '4d1dkst2ur9p66rb766274ikp4', '192.168.0.244', '192.168.0.244', 1353241973, 0, 1350057029, 0, 0),
	(6, 'demo5', 'd5134f97ca815ba20a6927be84126eb063fc012c07d4b3b5c4deefa857df46e06c7263fe4228d20ba1eeeb83711e6f3d6649b0aaa161a01d31bd7371752a6668', '627e6698876d73e28e85214a29e01fc32caaad48a75dc62ba9d6c6252e1366fb624d6ee92261ad01940ad5586efeeaacee409f250374c798095f55aa82fe3281', 'demo5@domain.tld', '', '', 'Europe/Berlin', 'de', 1, 0, 1, 0, 0, 0, '', '', '', '', '', 0, 0, 1350057029, 0, 0),
	(1, 'admin', '75db2364df0e77b69e2c8744f3f02b5d2312c3ea9aa17f1deb7a067c2111e6c61e85265ec7414b6c7f463be564360dc0a53a3ecb65846e140723edff433cca5a', '2a54ac26d8ac337666b2fedeea160e039dcc957a9f7bb3b7544639ca8c00f336ca4908343f88cd8e65172ee1d583421a6ba00789cc879d951134b2e47a5a883b', 'test@tasg.de', '', '', 'Europe/Berlin', 'de', 1, 1, 1, 0, 0, 0, '', '', '07cdhk1a0glkpp1mp12jatho91', '192.168.0.244', '192.168.0.244', 1354300626, 0, 1350057029, 0, 0);
/*!40000 ALTER TABLE `core_users` ENABLE KEYS */;


-- Dumping structure for table sweany.core_visitors
DROP TABLE IF EXISTS `core_visitors`;
CREATE TABLE IF NOT EXISTS `core_visitors` (
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

-- Dumping data for table sweany.core_visitors: 0 rows
DELETE FROM `core_visitors`;
/*!40000 ALTER TABLE `core_visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_visitors` ENABLE KEYS */;


-- Dumping structure for table sweany.faq
DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) DEFAULT NULL,
  `answer` text,
  `anchor` varchar(50) DEFAULT NULL,
  `fk_section_id` int(50) DEFAULT '0',
  `sort` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `anchor` (`anchor`),
  KEY `section` (`fk_section_id`),
  KEY `question` (`question`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.faq: ~4 rows (approximately)
DELETE FROM `faq`;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` (`id`, `question`, `answer`, `anchor`, `fk_section_id`, `sort`) VALUES
	(1, 'What is this?', 'This is something you won\'t know', 'what-is-this', 1, 0),
	(2, 'Are your sure?', 'Yes I am totally sure', 'are-you-sure', 1, 0),
	(3, 'Test Question', 'And here is your test answer', 'test', 2, 0),
	(4, 'Another Question?', 'Feel free to ask more :-)', 'another-question', 2, 0);
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;


-- Dumping structure for table sweany.faq_sections
DROP TABLE IF EXISTS `faq_sections`;
CREATE TABLE IF NOT EXISTS `faq_sections` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `sort` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.faq_sections: ~2 rows (approximately)
DELETE FROM `faq_sections`;
/*!40000 ALTER TABLE `faq_sections` DISABLE KEYS */;
INSERT INTO `faq_sections` (`id`, `name`, `sort`) VALUES
	(1, 'General', 0),
	(2, 'Another Section', 0);
/*!40000 ALTER TABLE `faq_sections` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_categories
DROP TABLE IF EXISTS `forum_categories`;
CREATE TABLE IF NOT EXISTS `forum_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
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
  KEY `fk_forum_category_id` (`fk_forum_category_id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_forums: 5 rows
DELETE FROM `forum_forums`;
/*!40000 ALTER TABLE `forum_forums` DISABLE KEYS */;
INSERT INTO `forum_forums` (`id`, `fk_forum_category_id`, `sort`, `display`, `can_create`, `can_reply`, `name`, `description`, `icon`, `seo_url`, `created`, `modified`) VALUES
	(1, 3, 6, 1, 0, 1, 'News', 'These are the news', 'forum_news.png', 'Neuigkeiten.html', 0, 2012),
	(2, 1, 5, 1, 1, 1, 'Talk', 'You can talk in here about whatever you want', 'forum_discussion.png', 'Geplauder.html', 0, 2012),
	(3, 2, 1, 1, 1, 1, 'Help', 'Do you need any help? This is the palce to start', 'forum_bug.png', 'Hilfe.html', 0, 2012),
	(4, 2, 2, 1, 1, 1, 'Features and Bugs', 'You want a new feature? ', 'forum_feedback.png', 'Features-und-Fehler.html', 0, 2012),
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_posts: 9 rows
DELETE FROM `forum_posts`;
/*!40000 ALTER TABLE `forum_posts` DISABLE KEYS */;
INSERT INTO `forum_posts` (`id`, `fk_forum_thread_id`, `fk_user_id`, `title`, `body`, `created`, `modified`) VALUES
	(1, 1, 2, '', 'But normal user are allowed to reply :D', 1350057167, 0),
	(2, 1, 1, '', ':)', 1352752561, 0),
	(3, 6, 4, '', ':D:D:D:D:D[b]sdsdsdsdasd[/b]\n\n:):D:(\n\n:roll::(:cry::cry:', 1353227359, 1353227478),
	(4, 6, 4, '', '[quote=demo3]:):D:roll:sadfsdafsdfsdasdfsda[code]dsfsdsdfssdf[/code][url=http://sdfsdfsdf]http://sdfsdfsdf[/url][/quote]', 1353227502, 1353227627),
	(5, 3, 2, '', 'yrdyrdyr', 1353238439, 0),
	(6, 5, 4, '', 'dfasd fd asd fasdfsad fd', 1353254488, 1353254504),
	(7, 2, 4, '', 'dsg sdafgsfdhsr tgh sstrh sfdh sgh sfh jsfjshfdah j ssfh sfdgh sdhjsfdjhsdzfg sdh fgj sfgh fgjh sfj sfgj sfjh sfj sfhda', 1353257309, 0),
	(8, 3, 1, '', ':):):):)[code]:cry::confuse::p:(:roll:[/code][img]http://[/img]:confuse::confuse::confuse::confuse::confuse::confuse::confuse::confuse::D:D:D:):):)', 1353790180, 1353794643),
	(9, 3, 1, '', ':red::p:D:):cry::red::confuse::p', 1353794655, 0);
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.forum_threads: 7 rows
DELETE FROM `forum_threads`;
/*!40000 ALTER TABLE `forum_threads` DISABLE KEYS */;
INSERT INTO `forum_threads` (`id`, `fk_forum_forums_id`, `fk_user_id`, `title`, `body`, `view_count`, `is_sticky`, `is_locked`, `is_closed`, `seo_url`, `last_post_id`, `last_post_created`, `created`, `modified`) VALUES
	(1, 1, 1, 'News entry 1', 'This is news entry number one by admin user.\r\nOnly admin users can create news entries in this forum\r\n\r\n:p', 20, 0, 0, 0, 'News-entry-1.html', 2, 1352752561, 1350057077, 0),
	(2, 1, 1, 'News entry 2', 'Here is another news entry by me\r\n\r\nThis [b]time[/b] with bb code [s]examples[/s]', 7, 0, 0, 0, 'News-entry-2.html', 7, 1353257309, 1350057120, 1353257309),
	(3, 2, 3, 'New thread by me', ':):D:roll:\n[code]\n	require(CORE_BOOTSTRAP.DS.\'Validator.php\');\n	require(CORE_VALIDATOR.DS.\'Validate01Basics.php\');\n	require(CORE_VALIDATOR.DS.\'Validate02Config.php\');\n	require(CORE_VALIDATOR.DS.\'Validate03Language.php\');\n	require(CORE_VALIDATOR.DS.\'Validate04Database.php\');\n	require(CORE_VALIDATOR.DS.\'Validate05Tables.php\');\n	require(CORE_VALIDATOR.DS.\'Validate06User.php\');\n	require(CORE_VALIDATOR.DS.\'Validate07UserOnlineCount.php\');\n	require(CORE_VALIDATOR.DS.\'Validate08LogVisitors.php\');\n	require(CORE_VALIDATOR.DS.\'Validate09Plugins.php\');\n[/code]', 18, 0, 0, 0, 'New-thread-by-me.html', 9, 1353794655, 1350057238, 1353794655),
	(4, 2, 3, 'Please note', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 9, 1, 1, 0, 'Please-note.html', 0, 0, 1350057300, 0),
	(5, 5, 4, 'fsdafsdafsda', ':):D:roll:sadfsdafsdfsdasdfsda[code]dsfsdsdfssdf[/code][url=http://sdfsdfsdf]http://sdfsdfsdf[/url]', 5, 0, 0, 0, 'fsdafsdafsda.html', 6, 1353254488, 1353227259, 1353254488),
	(6, 5, 4, 'fsdafsdafsda', ':):D:roll:sadfsdafsdfsdasdfsda[code]dsfsdsdfssdf[/code][url=http://sdfsdfsdf]http://sdfsdfsdf[/url]\n\n:red::cry::(:roll::D', 13, 0, 0, 0, 'fsdafsdafsda.html', 4, 1353227502, 1353227319, 1353227502),
	(7, 3, 4, 'test', 'test', 31, 0, 0, 0, 'test.html', 0, 0, 1353234244, 0);
/*!40000 ALTER TABLE `forum_threads` ENABLE KEYS */;


-- Dumping structure for table sweany.forum_thread_is_read
DROP TABLE IF EXISTS `forum_thread_is_read`;
CREATE TABLE IF NOT EXISTS `forum_thread_is_read` (
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_forum_thread_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_user_id`,`fk_forum_thread_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table sweany.forum_thread_is_read: ~0 rows (approximately)
DELETE FROM `forum_thread_is_read`;
/*!40000 ALTER TABLE `forum_thread_is_read` DISABLE KEYS */;
/*!40000 ALTER TABLE `forum_thread_is_read` ENABLE KEYS */;


-- Dumping structure for table sweany.guestbook
DROP TABLE IF EXISTS `guestbook`;
CREATE TABLE IF NOT EXISTS `guestbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_approved` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL,
  `useragent` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.guestbook: ~0 rows (approximately)
DELETE FROM `guestbook`;
/*!40000 ALTER TABLE `guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `guestbook` ENABLE KEYS */;


-- Dumping structure for table sweany.user_alerts
DROP TABLE IF EXISTS `user_alerts`;
CREATE TABLE IF NOT EXISTS `user_alerts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_to_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `flag_prio_low` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `flag_prio_medium` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `flag_prio_high` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'received msg put into trash',
  `is_trashed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'received msg put into trash',
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Permanently deleted received message',
  `read_count` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_to_user_id` (`fk_to_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sweany.user_alerts: 0 rows
DELETE FROM `user_alerts`;
/*!40000 ALTER TABLE `user_alerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_alerts` ENABLE KEYS */;


-- Dumping structure for table sweany.user_messages
DROP TABLE IF EXISTS `user_messages`;
CREATE TABLE IF NOT EXISTS `user_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_reply_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'For thread-view to see where to replied',
  `fk_from_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_to_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `flag_prio_low` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `flag_prio_medium` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `flag_prio_high` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_answered` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_received_archived` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'received msg put into archive',
  `is_received_trashed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'received msg put into trash',
  `is_received_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Permanently deleted received message',
  `is_send_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Permanently deleted sent message',
  `can_reply` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `read_count` int(10) unsigned NOT NULL DEFAULT '0',
  `first_read` int(11) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_from_user_id` (`fk_from_user_id`),
  KEY `fk_to_user_id` (`fk_to_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=232 DEFAULT CHARSET=utf8;

-- Dumping data for table sweany.user_messages: 0 rows
DELETE FROM `user_messages`;
/*!40000 ALTER TABLE `user_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_messages` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
