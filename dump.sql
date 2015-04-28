-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.25 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.2.0.4675
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for test
DROP DATABASE IF EXISTS `test`;
CREATE DATABASE IF NOT EXISTS `test` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `test`;


-- Dumping structure for table test.messages
DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `text` varchar(1024) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`,`to_user_id`),
  KEY `to_user_id` (`to_user_id`,`from_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Dumping data for table test.messages: ~18 rows (approximately)
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` (`id`, `from_user_id`, `to_user_id`, `text`, `time`) VALUES
	(1, 1, 1, 'test', '2015-02-09 16:36:13'),
	(2, 1, 1, 'test', '2015-02-09 16:37:03'),
	(3, 1, 1, 'test2', '2015-02-09 16:38:19'),
	(4, 1, 1, 'test', '2015-02-09 16:38:26'),
	(5, 1, 2, 'test', '2015-02-09 16:49:45'),
	(6, 1, 2, 'test2', '2015-02-09 16:50:01'),
	(7, 2, 1, 'test3', '2015-02-09 17:26:00'),
	(8, 2, 1, 'test', '2015-02-09 17:38:57'),
	(9, 1, 2, '1', '2015-02-09 17:41:09'),
	(10, 1, 2, '2', '2015-02-09 17:41:10'),
	(11, 1, 2, '3', '2015-02-09 17:41:11'),
	(12, 1, 2, '4', '2015-02-09 17:41:11'),
	(13, 1, 2, '5', '2015-02-09 17:41:12'),
	(14, 1, 2, '6', '2015-02-09 17:41:13'),
	(15, 2, 1, '7', '2015-02-09 17:44:38'),
	(16, 2, 1, '8', '2015-02-09 17:44:39'),
	(17, 1, 2, 'test', '2015-02-09 17:50:26'),
	(18, 2, 1, 'test2', '2015-02-09 17:50:31');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;


-- Dumping structure for table test.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table test.users: ~3 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `login`, `password`, `name`) VALUES
	(1, 'user', '07ccdafe95f190bdb6bf0fe7d416ce2e', 'User'),
	(2, 'user2', '36f64d4b5e3b3aba52d5c6bd30f1559d', 'User2'),
	(3, 'user3', '0ab66eb9cb7292235c1cf2f37ac3f3b2', 'User3');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
