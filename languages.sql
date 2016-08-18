# ************************************************************
# Sequel Pro SQL dump
# Version 4468
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.1.73)
# Database: partnershealthplan_db
# Generation Time: 2016-06-05 20:55:06 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table languages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;

INSERT INTO `languages` (`id`, `name`)
VALUES
  (1,'Spanish'),
  (2,'Hungarian'),
  (3,'Hebrew'),
  (4,'Italian'),
  (5,'Chinese'),
  (6,'Greek'),
  (7,'Polish'),
  (8,'Tamil'),
  (9,'Hindi'),
  (10,'Japanese'),
  (11,'Romanian'),
  (12,'Afrikaans'),
  (13,'Turkish'),
  (14,'Malayalam'),
  (15,'Arabic'),
  (16,'Russian'),
  (17,'Kannada'),
  (18,'Burmese'),
  (19,'Haitian-Creole'),
  (20,'French'),
  (21,'Latin'),
  (22,'Sign-Language'),
  (23,'Dutch'),
  (24,'Punjabi'),
  (25,'Telugu'),
  (26,'German'),
  (27,'Korean'),
  (28,'Kashmiri'),
  (29,'Tagalog/Filipino'),
  (30,'Yoruba'),
  (31,'Yiddish'),
  (32,'Urdu'),
  (33,'Croatian'),
  (34,'Armenian'),
  (35,'Marathia'),
  (36,'Bulgarian'),
  (37,'Gujarati'),
  (38,'Persian'),
  (39,'Malay'),
  (40,'Vietnamese'),
  (41,'Chinese-Mandarin'),
  (42,'Albanian'),
  (43,'Ukrainian'),
  (44,'Slovak'),
  (45,'Portuguese'),
  (46,'Thai'),
  (47,'Amharic'),
  (48,'Swedish'),
  (49,'Czech'),
  (50,'Nepali'),
  (51,'English');

/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
