-- MySQL dump 10.13  Distrib 5.6.42, for Linux (x86_64)
--
-- Host: localhost    Database: nbook
-- ------------------------------------------------------
-- Server version	5.6.42-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `favorite`
--

DROP TABLE IF EXISTS `favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorite` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_favorite_user` (`user_id`),
  KEY `FK_favorite_user_to` (`user_id_to`),
  CONSTRAINT `FK_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_favorite_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite`
--

LOCK TABLES `favorite` WRITE;
/*!40000 ALTER TABLE `favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow`
--

DROP TABLE IF EXISTS `follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `follow` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_follow_user` (`user_id`),
  KEY `FK_follow_user_to` (`user_id_to`),
  CONSTRAINT `FK_follow_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_follow_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow`
--

LOCK TABLES `follow` WRITE;
/*!40000 ALTER TABLE `follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friend_relation`
--

DROP TABLE IF EXISTS `friend_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friend_relation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_friend_relation_user` (`user_id`),
  KEY `FK_friend_relation_user_to` (`user_id_to`),
  CONSTRAINT `FK_friend_relation_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_friend_relation_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friend_relation`
--

LOCK TABLES `friend_relation` WRITE;
/*!40000 ALTER TABLE `friend_relation` DISABLE KEYS */;
INSERT INTO `friend_relation` VALUES (6,1,6,'2015-10-26 15:43:49'),(7,1,3,'2015-10-26 15:43:58'),(9,1,2,'2015-10-26 15:44:26'),(10,1,4,'2015-10-26 15:44:37');
/*!40000 ALTER TABLE `friend_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friend_request`
--

DROP TABLE IF EXISTS `friend_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friend_request` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_friend_request_user` (`user_id`),
  KEY `FK_friend_request_user_to` (`user_id_to`),
  CONSTRAINT `FK_friend_request_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_friend_request_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friend_request`
--

LOCK TABLES `friend_request` WRITE;
/*!40000 ALTER TABLE `friend_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `friend_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) NOT NULL DEFAULT '3',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `regist_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,1,'Admin','2015-10-15 10:23:31'),(2,3,'User','2015-10-26 15:15:03');
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `like`
--

DROP TABLE IF EXISTS `like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `like` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `pictures_id` int(10) NOT NULL,
  PRIMARY KEY (`id`,`user_id`,`pictures_id`),
  KEY `FK_like_user` (`user_id`),
  KEY `FK_like_pictures` (`pictures_id`),
  CONSTRAINT `FK_like_pictures` FOREIGN KEY (`pictures_id`) REFERENCES `picture` (`id`),
  CONSTRAINT `FK_like_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `like`
--

LOCK TABLES `like` WRITE;
/*!40000 ALTER TABLE `like` DISABLE KEYS */;
/*!40000 ALTER TABLE `like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_log`
--

DROP TABLE IF EXISTS `message_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_id_to` int(10) NOT NULL,
  `regist_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_message_log_user` (`user_id`),
  KEY `FK_message_log_user_to` (`user_id_to`),
  CONSTRAINT `FK_message_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_message_log_user_to` FOREIGN KEY (`user_id_to`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_log`
--

LOCK TABLES `message_log` WRITE;
/*!40000 ALTER TABLE `message_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture`
--

DROP TABLE IF EXISTS `picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `picture` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `url` text COLLATE utf8_unicode_ci,
  `view` int(10) NOT NULL DEFAULT '0',
  `like_number` int(10) NOT NULL DEFAULT '0',
  `regist_datetime` datetime DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pictures_user` (`user_id`),
  CONSTRAINT `FK_pictures_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture`
--

LOCK TABLES `picture` WRITE;
/*!40000 ALTER TABLE `picture` DISABLE KEYS */;
INSERT INTO `picture` VALUES (89,'20150610150935_v60K13tmTj5629b5388b34d.jpg',0,0,'2015-10-23 11:19:04',1),(92,'20150609154933_ZaSEynrddh5629b984b51d0.jpg',0,0,'2015-10-23 11:37:24',1),(96,'20150926131009_wPpZsOz1Hd5629ba408ca07.jpg',0,0,'2015-10-23 11:40:32',1),(99,'20150926122918_nv3r3lhqWn5629dc187624f.jpg',0,0,'2015-10-23 02:04:56',1),(100,'20150926131009_wPpZsOz1Hd5629dc18775df.jpg',0,0,'2015-10-23 02:04:56',1),(101,'20150928085512_BVCnzRU2iy5629dc1878965.jpg',0,0,'2015-10-23 02:04:56',1),(102,'20150928150023_oQX6EEW7sC5629dc187a0e8.jpg',0,0,'2015-10-23 02:04:56',1),(103,'20150928172425_im4tqkRmlj5629dc187b469.jpg',0,0,'2015-10-23 02:04:56',1),(104,'20150928181611_Cu4ZXzxSGF5629dc187c800.jpg',0,0,'2015-10-23 02:04:56',1),(105,'2Q==(1)562de5e700f07.jpg',0,0,'2015-10-26 03:35:51',6),(106,'2Q==(2)562de5e702295.jpg',0,0,'2015-10-26 03:35:51',6),(107,'2Q==(3)562de5e703239.jpg',0,0,'2015-10-26 03:35:51',6),(108,'2Q==562de5e7045c8.jpg',0,0,'2015-10-26 03:35:51',6),(109,'9k=(1)562de5e705951.jpg',0,0,'2015-10-26 03:35:51',6),(110,'9k=(2)562de5e7068f5.jpg',0,0,'2015-10-26 03:35:51',6),(111,'9k=(3)562de5e707c88.jpg',0,0,'2015-10-26 03:35:51',6),(112,'9k=(4)562de5e709040.jpg',0,0,'2015-10-26 03:35:51',6),(113,'9k=(5)562de5f138d80.jpg',0,0,'2015-10-26 03:36:01',6),(114,'9k=(6)562de5f13d003.jpg',0,0,'2015-10-26 03:36:01',6),(115,'9k=(7)562de5f13e3a7.jpg',0,0,'2015-10-26 03:36:01',6),(116,'9k=(8)562de5f13fb16.jpg',0,0,'2015-10-26 03:36:01',6),(117,'9k=562de5f1410e3.jpg',0,0,'2015-10-26 03:36:01',6),(118,'Z(1)562de5f14282b.jpg',0,0,'2015-10-26 03:36:01',6),(119,'Z(2)562de5f143d85.jpg',0,0,'2015-10-26 03:36:01',6),(120,'Z(3)562de5f1454fa.jpg',0,0,'2015-10-26 03:36:01',6),(121,'Z(4)562de5fb7a876.jpg',0,0,'2015-10-26 03:36:11',6),(122,'Z(5)562de5fb7b81a.jpg',0,0,'2015-10-26 03:36:11',6),(123,'Z(6)562de5fb7cba0.jpg',0,0,'2015-10-26 03:36:11',6),(124,'Z562de5fb87b9e.jpg',0,0,'2015-10-26 03:36:11',6),(125,'son tung (1)562de6b646065.jpg',0,0,'2015-10-26 03:39:18',3),(126,'son tung (2)562de6b648399.jpg',0,0,'2015-10-26 03:39:18',3),(127,'son tung (3)562de6b649efa.jpg',0,0,'2015-10-26 03:39:18',3),(128,'son tung (4)562de6b657065.jpg',0,0,'2015-10-26 03:39:18',3),(130,'son tung (6)562de6b65a8e8.jpg',0,0,'2015-10-26 03:39:18',3),(131,'son tung (7)562de6b65cc77.jpg',0,0,'2015-10-26 03:39:18',3),(132,'son tung (8)562de6b65f75e.jpg',0,0,'2015-10-26 03:39:18',3),(133,'son tung (9)562de6d0b22ea.jpg',0,0,'2015-10-26 03:39:44',3),(134,'son tung (11)562de6d0b427d.jpg',0,0,'2015-10-26 03:39:44',3);
/*!40000 ALTER TABLE `picture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server_status`
--

DROP TABLE IF EXISTS `server_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_busy` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server_status`
--

LOCK TABLES `server_status` WRITE;
/*!40000 ALTER TABLE `server_status` DISABLE KEYS */;
INSERT INTO `server_status` VALUES (1,0);
/*!40000 ALTER TABLE `server_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '1',
  `birthday` date DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `introduction` text COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `FK_user_group` (`group_id`),
  CONSTRAINT `FK_user_group` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'comboyin','e10adc3949ba59abbe56e057f20f883e','Trần Minh Nhựt',1,'2014-01-01','61 nguyen trai','asd asjnsdf dsajkfb dskafbsdjabf jksdabf kjsdbfk jbsdfkj bdsakjf bsdjkfabsjdka bfasdkj bfksjadfb askjdfb k','administrator562df010ec4cd.png','trannhut031192@gmail.com',1),(2,'comboyinA','e10adc3949ba59abbe56e057f20f883e','Lê văn tám mươi chín',1,'2015-10-23','asda sdas dasd',' asd asd as fsdagdg afdg fdagfd gdfgg','14321203052562a063dc2c6c.jpg','asdasdasdas@gmail.com',2),(3,'sontung','e10adc3949ba59abbe56e057f20f883e','Sơn Tùng MTP',1,'1992-01-01','Thái Bình','jh sdfbjhasdv fjhdsvaf jhsadvfjhasv','2015-10-26_154102562de70cc8fdd.jpg','sontung@gmail.com',2),(4,'camly','e10adc3949ba59abbe56e057f20f883e','Cẩm Ly',0,'1980-01-01','Đồng tháp','asdasdasd asdad asd a','cam-ly-2562de896830c8.jpg','camly@gmail.com',2),(5,'khoimy','e10adc3949ba59abbe56e057f20f883e','Khởi my',0,'1992-10-26','Ở đâu','ádasdasdasdasda','dasdasdasdasda.jpg','đâsdasđ@gmail.com',2),(6,'miule','e10adc3949ba59abbe56e057f20f883e','miu lê',0,'1981-10-26','An giang','ádasdaskbdaksjbdakjs','2015-10-26_152632562de3a15ebe5.jpg','miule@gmail.com',2);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-02 16:12:44
