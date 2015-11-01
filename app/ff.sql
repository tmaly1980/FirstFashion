-- MySQL dump 10.11
--
-- Host: localhost    Database: firstfashion
-- ------------------------------------------------------
-- Server version	5.0.45

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
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `ff_chats`;
CREATE TABLE `ff_chats` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `key` varchar(45) NOT NULL default '',
  `handle` varchar(20) NOT NULL default '',
  `text` text NOT NULL,
  `ip_address` varchar(12) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `KEY_IDX` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chats`
--

LOCK TABLES `ff_chats` WRITE;
/*!40000 ALTER TABLE `ff_chats` DISABLE KEYS */;
/*!40000 ALTER TABLE `ff_chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ff_member_model_profiles`
--

DROP TABLE IF EXISTS `ff_member_model_profiles`;
CREATE TABLE `ff_member_model_profiles` (
  `member_id` int(10) unsigned NOT NULL,
  `gender` enum('Male','Female') default NULL,
  `height` int(3) default NULL,
  `weight` int(3) default NULL,
  `eye_color` enum('Blue','Brown','Green','Grey','Hazel','Other') default NULL,
  `hair_color` enum('Black','Blonde','Dark Blonde','Light Brown','Brown','Dark Brown','Red','Grey/White','Other') default NULL,
  `measurements` varchar(8) default NULL,
  `ethnicity` enum('African-American','Asian','Caucasian','Hispanic','Native American','Mixed','Other') default NULL,
  `skintone` enum('Fair','Pale','Bronze','Freckled') default NULL,
  `website` varchar(64) default NULL,
  `about_me` text,
  `since_experience` date default NULL,
  `availability` varchar(64) default NULL,
  PRIMARY KEY  (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ff_member_model_profiles`
--

LOCK TABLES `ff_member_model_profiles` WRITE;
/*!40000 ALTER TABLE `ff_member_model_profiles` DISABLE KEYS */;
INSERT INTO `ff_member_model_profiles` VALUES (1,'Male',67,144,'Hazel','Blonde','32-24-26','Caucasian','','http://www.mawdellschmawdel.com','I love to look good!','2007-01-01','M-F 9-5'),(2,'Female',81,165,'Green','Brown','32-24-26','African-American','','http://www.mawdellschmawdel.com','I love to look good!','2007-01-01','M-F 9-5'),(3,'Female',79,114,'Green','Brown','32-24-26','African-American','','http://www.mawdellschmawdel.com','I love to look good!','2007-01-01','M-F 9-5'),(4,'Male',73,129,'Green','Brown','32-24-26','African-American','','http://www.mawdellschmawdel.com','I love to look good!','2007-01-01','M-F 9-5'),(5,'Male',78,121,'Green','Brown','32-24-26','African-American','','http://www.mawdellschmawdel.com','I love to look good!','2007-01-01','M-F 9-5'),(6,'Male',75,162,'Green','Dark Brown','34-32-34','Other','Freckled','http://www.tomaswebdev.com/','I am a father of three young amazing children!',NULL,'Weekends Only'),(7,'Female',70,112,'Grey','Dark Blonde','36-22-34','Native American','Freckled','www.google.com','Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet','1997-03-01','Weekends Only'),(8,'Male',69,122,'Brown','Black','36-22-34','Caucasian','Fair','www.google.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2001-04-01','M,W,F 2pm - 6pm'),(9,'Female',70,225,'Hazel','Grey/White','38-28-34','Mixed','Freckled','www.site.com','Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est','2001-04-01','Weekends Only'),(10,'Female',66,140,'Grey','Blonde','36-26-32','Mixed','Pale','www.site.com','Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet','1997-03-01','M,W,F 2pm - 6pm'),(11,'Female',62,160,'Brown','Dark Blonde','36-32-30','Other','Fair','www.site.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2005-06-01','M-F 9-5'),(12,'Female',72,122,'Green','Red','36-26-32','Mixed','Bronze','www.myfirstsite.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2005-06-01','M,W,F 2pm - 6pm'),(13,'Female',71,122,'Brown','Red','36-26-32','Asian','Bronze','www.google.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2007-02-01','M-F 9-5'),(14,'Female',62,160,'Hazel','Brown','38-28-34','Mixed','Pale','www.google.com','Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet','2006-11-01','M-F 9-5'),(15,'Female',71,225,'Blue','Other','36-32-30','Asian','Pale','www.myfirstsite.com','Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est','1997-03-01','3pm - 9pm'),(16,'Male',65,122,'Green','Dark Brown','36-22-34','African-American','Bronze','www.domain.com','Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est','2006-11-01','3pm - 9pm'),(17,'Female',66,225,'Hazel','Dark Brown','36-26-32','African-American','Bronze','www.site.com','Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est','2007-02-01','3pm - 9pm'),(18,'Male',70,112,'Hazel','Red','36-26-32','Other','Pale','www.domain.com','Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est','2007-02-01','12am - 9am'),(19,'Female',66,112,'Blue','Dark Brown','38-28-34','African-American','Freckled','www.google.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2006-11-01','M-F 9-5'),(20,'Female',66,112,'Blue','Grey/White','36-26-32','Hispanic','Bronze','www.myfirstsite.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2007-02-01','Weekends Only'),(21,'Female',69,225,'Hazel','Black','36-26-32','Asian','Pale','www.myfirstsite.com','Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet','2002-12-01','Weekends Only'),(22,'Male',65,135,'Hazel','Grey/White','36-26-32','Other','Pale','www.site.com','Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est','2006-11-01','12am - 9am'),(23,'Female',71,160,'Hazel','Blonde','36-26-32','African-American','Freckled','www.home.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2007-02-01','3pm - 9pm'),(24,'Male',72,225,'Green','Blonde','36-22-34','African-American','Fair','www.home.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2002-12-01','Weekends Only'),(25,'Male',66,160,'Green','Dark Brown','36-26-32','Mixed','Bronze','www.myfirstsite.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2001-04-01','M-F 9-5'),(26,'Male',69,225,'Blue','Blonde','36-32-30','Hispanic','Freckled','www.site.com','Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est','2006-11-01','M-F 9-5'),(27,'Female',62,160,'Green','Red','32-22-34','Other','Freckled','www.domain.com','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation','2007-02-01','Weekends Only');
/*!40000 ALTER TABLE `ff_member_model_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ff_member_photos`
--

DROP TABLE IF EXISTS `ff_member_photos`;
CREATE TABLE `ff_member_photos` (
  `photo_id` int(10) unsigned NOT NULL auto_increment,
  `member_id` int(10) unsigned default NULL,
  `ext` varchar(5) default NULL,
  `album_id` int(10) unsigned default '0',
  `album_order` int(10) unsigned default NULL,
  `is_primary` tinyint(1) default '0',
  `title` varchar(32) default NULL,
  `comment` text,
  PRIMARY KEY  (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ff_member_photos`
--

LOCK TABLES `ff_member_photos` WRITE;
/*!40000 ALTER TABLE `ff_member_photos` DISABLE KEYS */;
INSERT INTO `ff_member_photos` VALUES (1,6,NULL,0,NULL,0,'Me, me, me','My beautiful looks....'),(8,6,'jpg',0,NULL,0,'skyscraper','wow, dude.'),(7,6,'png',0,NULL,1,'ME','Tomas Antonin Maly'),(9,6,'jpg',0,NULL,0,'My brother','A cool guy...'),(10,12,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(11,13,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(12,14,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(13,15,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(14,16,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(15,17,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(16,18,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(17,19,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(18,20,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(19,21,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(20,22,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(21,23,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(22,24,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(23,25,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(24,26,'jpg',0,NULL,1,'Primary Photo','Taken last year'),(25,27,'jpg',0,NULL,1,'Primary Photo','Taken last year');
/*!40000 ALTER TABLE `ff_member_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ff_members`
--

DROP TABLE IF EXISTS `ff_members`;
CREATE TABLE `ff_members` (
  `member_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(60) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(128) default NULL,
  `firstname` varchar(32) default NULL,
  `lastname` varchar(32) default NULL,
  `city` varchar(32) default NULL,
  `state` varchar(2) default NULL,
  `birthdate` date default NULL,
  `registration_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `active` tinyint(1) default '1',
  `member_type` enum('model','stylist','photographer','agent') default NULL,
  PRIMARY KEY  (`member_id`),
  KEY `email` (`email`),
  KEY `username` (`username`),
  KEY `firstname` (`firstname`,`lastname`),
  FULLTEXT KEY `username_2` (`username`,`firstname`,`lastname`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ff_members`
--

LOCK TABLES `ff_members` WRITE;
/*!40000 ALTER TABLE `ff_members` DISABLE KEYS */;
INSERT INTO `ff_members` VALUES (1,'model2@localhost','model2','model2','Mawdel1','Schmawdel','Philadelphia','PA','1983-04-19','2008-06-01 04:00:00',1,'model'),(2,'model3@localhost','model3','model3','Mawdel2','Schmawdel','Philadelphia','PA','1983-04-19','2008-06-01 04:00:00',1,'model'),(3,'model4@localhost','model4','model4','Mawdel3','Schmawdel','Philadelphia','PA','1983-04-19','2008-06-01 04:00:00',1,'model'),(4,'model5@localhost','model5','model5','Mawdel4','Schmawdel','Philadelphia','PA','1983-04-19','2008-06-01 04:00:00',1,'model'),(5,'model1@localhost','model1','model1','Mawdel','Schmawdel','Philadelphia','PA','1983-04-19','2008-06-01 04:00:00',1,'model'),(6,'tomas@localhost','tomas_maly','6e714de65367266eee76a5db945c1e23c24fc024','Tomas','Maly','Elmer','NJ','1979-12-30','2008-08-08 18:02:30',1,'model'),(7,'RachelS1@firstfashion.malysoft.com','RachelS1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Rachel','Sample','New York','FL','1978-09-12','2008-09-22 04:00:00',1,'model'),(8,'MichaelP1@firstfashion.malysoft.com','MichaelP1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Michael','Person','Silver City','MD','1981-03-22','2004-09-21 04:00:00',1,'model'),(9,'JudithM1@firstfashion.malysoft.com','JudithM1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Judith','Member','Hollywood','FL','1981-11-21','2004-09-21 04:00:00',1,'model'),(10,'SallyE1@firstfashion.malysoft.com','SallyE1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Sally','Example','Hollywood','FL','1984-02-09','2006-03-12 05:00:00',1,'model'),(11,'AmandaM1@firstfashion.malysoft.com','AmandaM1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Amanda','Member','Philadelphia','MA','1982-08-19','2004-09-21 04:00:00',1,'model'),(12,'TravisE1@firstfashion.malysoft.com','TravisE1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Travis','Example','Little City','AZ','1982-08-19','2004-09-21 04:00:00',1,'model'),(13,'ChrisP1@firstfashion.malysoft.com','ChrisP1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Chris','Person','New York','MA','1978-09-12','2006-03-12 05:00:00',1,'model'),(14,'KevinM1@firstfashion.malysoft.com','KevinM1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Kevin','Member','Toontown','PA','1981-11-21','2005-11-09 05:00:00',1,'model'),(15,'ChrisS1@firstfashion.malysoft.com','ChrisS1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Chris','Schmoe','Toontown','PA','1980-09-03','2007-07-13 04:00:00',1,'model'),(16,'DerekS1@firstfashion.malysoft.com','DerekS1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Derek','Sample','Little City','PA','1981-11-21','2006-03-12 05:00:00',1,'model'),(17,'MichaelE1@firstfashion.malysoft.com','MichaelE1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Michael','Example','Silver City','CA','1980-10-22','2008-09-22 04:00:00',1,'model'),(18,'KarenM1@firstfashion.malysoft.com','KarenM1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Karen','Member','New York','NY','1982-08-19','2006-03-12 05:00:00',1,'model'),(19,'TravisS1@firstfashion.malysoft.com','TravisS1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Travis','Schmoe','Philadelphia','PA','1984-02-09','2006-03-12 05:00:00',1,'model'),(20,'JudithM2@firstfashion.malysoft.com','JudithM2','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Judith','Model','Somewhereville','NY','1987-12-18','2006-03-12 05:00:00',1,'model'),(21,'DerekM1@firstfashion.malysoft.com','DerekM1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Derek','Member','Little City','PA','1978-09-12','2005-11-09 05:00:00',1,'model'),(22,'ElizabethP1@firstfashion.malysoft.com','ElizabethP1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Elizabeth','Person','Toontown','NY','1982-08-19','2006-01-02 05:00:00',1,'model'),(23,'TravisP1@firstfashion.malysoft.com','TravisP1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Travis','Person','Little City','NY','1982-08-19','2007-07-13 04:00:00',1,'model'),(24,'KevinS1@firstfashion.malysoft.com','KevinS1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Kevin','Sample','Philadelphia','MA','1978-09-12','2006-01-02 05:00:00',1,'model'),(25,'TravisP1@firstfashion.malysoft.com','TravisP1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Travis','Person','Silver City','NY','1982-08-19','2007-07-13 04:00:00',1,'model'),(26,'MichaelM1@firstfashion.malysoft.com','MichaelM1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Michael','Model','Toontown','NY','1987-12-18','2008-10-02 04:00:00',1,'model'),(27,'DerekM1@firstfashion.malysoft.com','DerekM1','5f68c8e90196f3f6648cd1f243d2311c0d5a64fc','Derek','Member','Philadelphia','PA','1976-05-14','2007-07-13 04:00:00',1,'model');
/*!40000 ALTER TABLE `ff_members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-08-28 16:11:18
