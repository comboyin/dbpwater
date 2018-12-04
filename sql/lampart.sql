-- --------------------------------------------------------
-- Host:                         172.16.100.3
-- Server version:               5.0.95-log - Source distribution
-- Server OS:                    redhat-linux-gnu
-- HeidiSQL Version:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table minh_nhut_lession_3.group
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(10) NOT NULL auto_increment,
  `level` tinyint(1) NOT NULL default '3',
  `name` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `regist_datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.group: ~1 rows (approximately)
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` (`id`, `level`, `name`, `regist_datetime`) VALUES
	(1, 1, 'Admin', '2015-10-15 10:23:31'),
	(2, 3, 'User', '2015-10-26 15:15:03');
/*!40000 ALTER TABLE `group` ENABLE KEYS */;

-- Dumping structure for table minh_nhut_lession_3.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(40) collate utf8_unicode_ci NOT NULL,
  `password` varchar(32) collate utf8_unicode_ci NOT NULL,
  `fullname` varchar(32) collate utf8_unicode_ci NOT NULL,
  `sex` tinyint(1) NOT NULL default '1',
  `birthday` date default NULL,
  `address` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `introduction` text collate utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) collate utf8_unicode_ci NOT NULL,
  `email` varchar(40) collate utf8_unicode_ci NOT NULL default '',
  `group_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `FK_user_group` (`group_id`),
  CONSTRAINT `FK_user_group` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.user: ~2 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `password`, `fullname`, `sex`, `birthday`, `address`, `introduction`, `avatar`, `email`, `group_id`) VALUES
	(1, 'comboyin', 'e10adc3949ba59abbe56e057f20f883e', 'Trần Minh Nhựt', 1, '2014-01-01', '61 nguyen trai', 'asd asjnsdf dsajkfb dskafbsdjabf jksdabf kjsdbfk jbsdfkj bdsakjf bsdjkfabsjdka bfasdkj bfksjadfb askjdfb k', 'administrator562df010ec4cd.png', 'trannhut031192@gmail.com', 1),
	(2, 'comboyinA', 'e10adc3949ba59abbe56e057f20f883e', 'Lê văn tám mươi chín', 1, '2015-10-23', 'asda sdas dasd', ' asd asd as fsdagdg afdg fdagfd gdfgg', '14321203052562a063dc2c6c.jpg', 'asdasdasdas@gmail.com', 2),
	(3, 'sontung', 'e10adc3949ba59abbe56e057f20f883e', 'Sơn Tùng MTP', 1, '1992-01-01', 'Thái Bình', 'jh sdfbjhasdv fjhdsvaf jhsadvfjhasv', '2015-10-26_154102562de70cc8fdd.jpg', 'sontung@gmail.com', 2),
	(4, 'camly', 'e10adc3949ba59abbe56e057f20f883e', 'Cẩm Ly', 0, '1980-01-01', 'Đồng tháp', 'asdasdasd asdad asd a', 'cam-ly-2562de896830c8.jpg', 'camly@gmail.com', 2),
	(5, 'khoimy', 'e10adc3949ba59abbe56e057f20f883e', 'Khởi my', 0, '1992-10-26', 'Ở đâu', 'ádasdasdasdasda', 'dasdasdasdasda.jpg', 'đâsdasđ@gmail.com', 2),
	(6, 'miule', 'e10adc3949ba59abbe56e057f20f883e', 'miu lê', 0, '1981-10-26', 'An giang', 'ádasdaskbdaksjbdakjs', '2015-10-26_152632562de3a15ebe5.jpg', 'miule@gmail.com', 2);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-- Dumping structure for table minh_nhut_lession_3.favorite
CREATE TABLE IF NOT EXISTS `favorite` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_favorite_user` (`user_id`),
  KEY `FK_favorite_user_to` (`user_id_to`),
  CONSTRAINT `FK_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_favorite_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.favorite: ~0 rows (approximately)
/*!40000 ALTER TABLE `favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite` ENABLE KEYS */;


-- Dumping structure for table minh_nhut_lession_3.follow
CREATE TABLE IF NOT EXISTS `follow` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_follow_user` (`user_id`),
  KEY `FK_follow_user_to` (`user_id_to`),
  CONSTRAINT `FK_follow_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_follow_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.follow: ~0 rows (approximately)
/*!40000 ALTER TABLE `follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow` ENABLE KEYS */;


-- Dumping structure for table minh_nhut_lession_3.friend_relation
CREATE TABLE IF NOT EXISTS `friend_relation` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_friend_relation_user` (`user_id`),
  KEY `FK_friend_relation_user_to` (`user_id_to`),
  CONSTRAINT `FK_friend_relation_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_friend_relation_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.friend_relation: ~1 rows (approximately)
/*!40000 ALTER TABLE `friend_relation` DISABLE KEYS */;
INSERT INTO `friend_relation` (`id`, `user_id`, `user_id_to`, `regist_datetime`) VALUES
	(6, 1, 6, '2015-10-26 15:43:49'),
	(7, 1, 3, '2015-10-26 15:43:58'),
	(9, 1, 2, '2015-10-26 15:44:26'),
	(10, 1, 4, '2015-10-26 15:44:37');
/*!40000 ALTER TABLE `friend_relation` ENABLE KEYS */;


-- Dumping structure for table minh_nhut_lession_3.friend_request
CREATE TABLE IF NOT EXISTS `friend_request` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_friend_request_user` (`user_id`),
  KEY `FK_friend_request_user_to` (`user_id_to`),
  CONSTRAINT `FK_friend_request_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_friend_request_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.friend_request: ~0 rows (approximately)
/*!40000 ALTER TABLE `friend_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `friend_request` ENABLE KEYS */;

-- Dumping structure for table minh_nhut_lession_3.message_log
CREATE TABLE IF NOT EXISTS `message_log` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_message_log_user` (`user_id`),
  KEY `FK_message_log_user_to` (`user_id_to`),
  CONSTRAINT `FK_message_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_message_log_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.message_log: ~0 rows (approximately)
/*!40000 ALTER TABLE `message_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_log` ENABLE KEYS */;


-- Dumping structure for table minh_nhut_lession_3.picture
CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(10) NOT NULL auto_increment,
  `url` text collate utf8_unicode_ci,
  `view` int(10) NOT NULL default '0',
  `like_number` int(10) NOT NULL default '0',
  `regist_datetime` datetime default NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_pictures_user` (`user_id`),
  CONSTRAINT `FK_pictures_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.picture: ~9 rows (approximately)
/*!40000 ALTER TABLE `picture` DISABLE KEYS */;
INSERT INTO `picture` (`id`, `url`, `view`, `like_number`, `regist_datetime`, `user_id`) VALUES
	(89, '20150610150935_v60K13tmTj5629b5388b34d.jpg', 0, 0, '2015-10-23 11:19:04', 1),
	(92, '20150609154933_ZaSEynrddh5629b984b51d0.jpg', 0, 0, '2015-10-23 11:37:24', 1),
	(96, '20150926131009_wPpZsOz1Hd5629ba408ca07.jpg', 0, 0, '2015-10-23 11:40:32', 1),
	(99, '20150926122918_nv3r3lhqWn5629dc187624f.jpg', 0, 0, '2015-10-23 02:04:56', 1),
	(100, '20150926131009_wPpZsOz1Hd5629dc18775df.jpg', 0, 0, '2015-10-23 02:04:56', 1),
	(101, '20150928085512_BVCnzRU2iy5629dc1878965.jpg', 0, 0, '2015-10-23 02:04:56', 1),
	(102, '20150928150023_oQX6EEW7sC5629dc187a0e8.jpg', 0, 0, '2015-10-23 02:04:56', 1),
	(103, '20150928172425_im4tqkRmlj5629dc187b469.jpg', 0, 0, '2015-10-23 02:04:56', 1),
	(104, '20150928181611_Cu4ZXzxSGF5629dc187c800.jpg', 0, 0, '2015-10-23 02:04:56', 1),
	(105, '2Q==(1)562de5e700f07.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(106, '2Q==(2)562de5e702295.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(107, '2Q==(3)562de5e703239.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(108, '2Q==562de5e7045c8.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(109, '9k=(1)562de5e705951.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(110, '9k=(2)562de5e7068f5.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(111, '9k=(3)562de5e707c88.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(112, '9k=(4)562de5e709040.jpg', 0, 0, '2015-10-26 03:35:51', 6),
	(113, '9k=(5)562de5f138d80.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(114, '9k=(6)562de5f13d003.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(115, '9k=(7)562de5f13e3a7.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(116, '9k=(8)562de5f13fb16.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(117, '9k=562de5f1410e3.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(118, 'Z(1)562de5f14282b.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(119, 'Z(2)562de5f143d85.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(120, 'Z(3)562de5f1454fa.jpg', 0, 0, '2015-10-26 03:36:01', 6),
	(121, 'Z(4)562de5fb7a876.jpg', 0, 0, '2015-10-26 03:36:11', 6),
	(122, 'Z(5)562de5fb7b81a.jpg', 0, 0, '2015-10-26 03:36:11', 6),
	(123, 'Z(6)562de5fb7cba0.jpg', 0, 0, '2015-10-26 03:36:11', 6),
	(124, 'Z562de5fb87b9e.jpg', 0, 0, '2015-10-26 03:36:11', 6),
	(125, 'son tung (1)562de6b646065.jpg', 0, 0, '2015-10-26 03:39:18', 3),
	(126, 'son tung (2)562de6b648399.jpg', 0, 0, '2015-10-26 03:39:18', 3),
	(127, 'son tung (3)562de6b649efa.jpg', 0, 0, '2015-10-26 03:39:18', 3),
	(128, 'son tung (4)562de6b657065.jpg', 0, 0, '2015-10-26 03:39:18', 3),
	(130, 'son tung (6)562de6b65a8e8.jpg', 0, 0, '2015-10-26 03:39:18', 3),
	(131, 'son tung (7)562de6b65cc77.jpg', 0, 0, '2015-10-26 03:39:18', 3),
	(132, 'son tung (8)562de6b65f75e.jpg', 0, 0, '2015-10-26 03:39:18', 3),
	(133, 'son tung (9)562de6d0b22ea.jpg', 0, 0, '2015-10-26 03:39:44', 3),
	(134, 'son tung (11)562de6d0b427d.jpg', 0, 0, '2015-10-26 03:39:44', 3);
/*!40000 ALTER TABLE `picture` ENABLE KEYS */;


-- Dumping structure for table minh_nhut_lession_3.like
CREATE TABLE IF NOT EXISTS `like` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `pictures_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`,`user_id`,`pictures_id`),
  KEY `FK_like_user` (`user_id`),
  KEY `FK_like_pictures` (`pictures_id`),
  CONSTRAINT `FK_like_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_like_pictures` FOREIGN KEY (`pictures_id`) REFERENCES `picture` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table minh_nhut_lession_3.like: ~0 rows (approximately)
/*!40000 ALTER TABLE `like` DISABLE KEYS */;
/*!40000 ALTER TABLE `like` ENABLE KEYS */;