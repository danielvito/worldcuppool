# Host: localhost  (Version: 5.5.16)
# Date: 2017-04-15 11:30:26
# Generator: MySQL-Front 5.2  (Build 4.88)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

DROP DATABASE IF EXISTS `worldcuppool`;
CREATE DATABASE `worldcuppool` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;
USE `worldcuppool`;

#
# Source for table "config"
#

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `value` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Data for table "config"
#

INSERT INTO `config` VALUES (1,'2014-05-24 18:58:35','2014-07-06 11:04:28',1,'palpites','off'),(2,'2014-05-24 22:03:04','2014-06-19 22:11:37',1,'palpites_adicionais','off');

#
# Source for table "game"
#

DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `place` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `id_team_a` int(11) NOT NULL DEFAULT '0',
  `id_team_b` int(11) NOT NULL DEFAULT '0',
  `game_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `score_a` int(11) NOT NULL DEFAULT '0',
  `score_b` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Data for table "game"
#

INSERT INTO `game` VALUES (1,'2014-05-16 21:57:01','2014-06-12 18:55:24',1,'GRUPO A','São Paulo',7,14,'2014-06-12 17:00:00',3,1),(2,'2014-05-16 21:57:01','2014-06-13 14:56:12',1,'GRUPO A','Natal',27,8,'2014-06-13 13:00:00',1,0),(3,'2014-05-16 21:57:01','2014-06-17 17:51:07',1,'GRUPO A','Fortaleza',7,27,'2014-06-17 16:00:00',0,0),(4,'2014-05-16 21:57:01','2014-06-18 20:49:30',1,'GRUPO A','Manaus',8,14,'2014-06-18 19:00:00',0,4),(5,'2014-05-16 21:57:01','2014-06-23 18:53:19',1,'GRUPO A','Brasília',8,7,'2014-06-23 17:00:00',1,4),(6,'2014-05-16 21:57:01','2014-06-23 18:53:19',1,'GRUPO A','Recife',14,27,'2014-06-23 17:00:00',1,3),(7,'2014-05-16 21:57:01','2014-06-13 18:09:21',1,'GRUPO B','Salvador',16,21,'2014-06-13 16:00:00',1,5),(8,'2014-05-16 21:57:01','2014-06-13 20:52:01',1,'GRUPO B','Cuiabá',9,4,'2014-06-13 19:00:00',3,1),(9,'2014-05-16 21:57:01','2014-06-18 14:52:03',1,'GRUPO B','Porto Alegre',4,21,'2014-06-18 13:00:00',2,3),(10,'2014-05-16 21:57:01','2014-06-18 17:55:41',1,'GRUPO B','Rio de Janeiro',16,9,'2014-06-18 16:00:00',0,2),(11,'2014-05-16 21:57:01','2014-06-23 14:51:20',1,'GRUPO B','Curitiba',4,16,'2014-06-23 13:00:00',0,3),(12,'2014-05-16 21:57:01','2014-06-23 14:51:20',1,'GRUPO B','São Paulo',21,9,'2014-06-23 13:00:00',2,0),(13,'2014-05-16 21:57:01','2014-06-14 14:53:21',1,'GRUPO C','Belo Horizonte',10,20,'2014-06-14 13:00:00',3,0),(14,'2014-05-16 21:57:01','2014-06-14 23:52:56',1,'GRUPO C','Recife',12,26,'2014-06-14 22:00:00',2,1),(15,'2014-05-16 21:57:01','2014-06-19 14:54:54',1,'GRUPO C','Brasília',10,12,'2014-06-19 13:00:00',2,1),(16,'2014-05-16 21:57:01','2014-06-19 20:52:34',1,'GRUPO C','Natal',26,20,'2014-06-19 19:00:00',0,0),(17,'2014-05-16 21:57:01','2014-06-24 18:57:30',1,'GRUPO C','Cuiabá',26,10,'2014-06-24 17:00:00',1,4),(18,'2014-05-16 21:57:01','2014-06-24 18:57:30',1,'GRUPO C','Fortaleza',20,12,'2014-06-24 17:00:00',2,1),(19,'2014-05-16 21:57:01','2014-06-14 18:00:06',1,'GRUPO D','Fortaleza',32,13,'2014-06-14 16:00:00',1,3),(20,'2014-05-16 21:57:01','2014-06-14 20:53:57',1,'GRUPO D','Manaus',23,25,'2014-06-14 19:00:00',1,2),(21,'2014-05-16 21:57:01','2014-06-19 17:51:29',1,'GRUPO D','São Paulo',32,23,'2014-06-19 16:00:00',2,1),(22,'2014-05-16 21:57:01','2014-06-20 15:36:10',1,'GRUPO D','Recife',25,13,'2014-06-20 13:00:00',0,1),(23,'2014-05-16 21:57:01','2014-06-24 14:57:22',1,'GRUPO D','Natal',25,32,'2014-06-24 13:00:00',0,1),(24,'2014-05-16 21:57:01','2014-06-24 14:57:22',1,'GRUPO D','Belo Horizonte',13,23,'2014-06-24 13:00:00',0,0),(25,'2014-05-16 21:57:01','2014-06-15 14:50:37',1,'GRUPO E','Brasília',31,15,'2014-06-15 13:00:00',2,1),(26,'2014-05-16 21:57:01','2014-06-15 17:52:12',1,'GRUPO E','Porto Alegre',18,22,'2014-06-15 16:00:00',3,0),(27,'2014-05-16 21:57:01','2014-06-20 17:55:35',1,'GRUPO E','Salvador',31,18,'2014-06-20 16:00:00',2,5),(28,'2014-05-16 21:57:01','2014-06-20 20:53:08',1,'GRUPO E','Curitiba',22,15,'2014-06-20 19:00:00',1,2),(29,'2014-05-16 21:57:01','2014-06-25 18:53:24',1,'GRUPO E','Manaus',22,31,'2014-06-25 17:00:00',0,3),(30,'2014-05-16 21:57:01','2014-06-25 18:53:24',1,'GRUPO E','Rio de Janeiro',15,18,'2014-06-25 17:00:00',0,0),(31,'2014-05-16 21:57:01','2014-06-15 20:51:21',1,'GRUPO F','Rio de Janeiro',3,6,'2014-06-15 19:00:00',2,1),(32,'2014-05-16 21:57:01','2014-06-16 17:54:09',1,'GRUPO F','Curitiba',24,28,'2014-06-16 16:00:00',0,0),(33,'2014-05-16 21:57:01','2014-06-21 14:53:11',1,'GRUPO F','Belo Horizonte',3,24,'2014-06-21 13:00:00',1,0),(34,'2014-05-16 21:57:01','2014-06-21 20:49:23',1,'GRUPO F','Cuiabá',28,6,'2014-06-21 19:00:00',1,0),(35,'2014-05-16 21:57:01','2014-06-25 14:55:11',1,'GRUPO F','Porto Alegre',28,3,'2014-06-25 13:00:00',2,3),(36,'2014-05-16 21:57:01','2014-06-25 14:55:11',1,'GRUPO F','Salvador',6,24,'2014-06-25 13:00:00',3,1),(37,'2014-05-16 21:57:01','2014-06-16 14:54:42',1,'GRUPO G','Salvador',1,29,'2014-06-16 13:00:00',4,0),(38,'2014-05-16 21:57:01','2014-06-16 20:56:22',1,'GRUPO G','Natal',19,17,'2014-06-16 19:00:00',1,2),(39,'2014-05-16 21:57:01','2014-06-21 17:52:49',1,'GRUPO G','Fortaleza',1,19,'2014-06-21 16:00:00',2,2),(40,'2014-05-16 21:57:01','2014-06-22 20:53:44',1,'GRUPO G','Manaus',17,29,'2014-06-22 19:00:00',2,2),(41,'2014-05-16 21:57:01','2014-06-26 14:58:22',1,'GRUPO G','Recife',17,1,'2014-06-26 13:00:00',0,1),(42,'2014-05-16 21:57:01','2014-06-26 14:58:22',1,'GRUPO G','Brasília',29,19,'2014-06-26 13:00:00',2,1),(43,'2014-05-16 21:57:01','2014-06-17 14:49:45',1,'GRUPO H','Belo Horizonte',5,2,'2014-06-17 13:00:00',2,1),(44,'2014-05-16 21:57:01','2014-06-17 20:50:18',1,'GRUPO H','Cuiabá',30,11,'2014-06-17 19:00:00',1,1),(45,'2014-05-16 21:57:01','2014-06-22 14:50:51',1,'GRUPO H','Rio de Janeiro',5,30,'2014-06-22 13:00:00',1,0),(46,'2014-05-16 21:57:01','2014-06-22 17:51:43',1,'GRUPO H','Porto Alegre',11,2,'2014-06-22 16:00:00',2,4),(47,'2014-05-16 21:57:01','2014-06-26 18:53:51',1,'GRUPO H','São Paulo',11,5,'2014-06-26 17:00:00',0,1),(48,'2014-05-16 21:57:01','2014-06-26 18:53:51',1,'GRUPO H','Curitiba',2,30,'2014-06-26 17:00:00',1,1),(49,'2014-06-26 22:16:01','2014-06-28 14:54:50',1,'8as','Belo Horizonte',7,9,'2014-06-28 13:00:00',1,1),(50,'2014-06-26 22:16:01','2014-06-28 18:51:21',1,'8as','Rio de Janeiro',10,32,'2014-06-28 17:00:00',2,0),(51,'2014-06-26 22:16:01','2014-06-30 15:10:22',1,'8as','Brasília',18,28,'2014-06-30 13:00:00',2,0),(52,'2014-06-26 22:16:01','2014-06-30 18:53:24',1,'8as','Porto Alegre',1,2,'2014-06-30 17:00:00',0,0),(53,'2014-06-26 22:16:01','2014-06-29 14:56:52',1,'8as','Ceará',21,27,'2014-06-29 13:00:00',2,1),(54,'2014-06-26 22:16:01','2014-06-29 19:02:22',1,'8as','Recife',13,20,'2014-06-29 17:00:00',1,1),(55,'2014-06-26 22:16:01','2014-07-01 18:51:28',1,'8as','São Paulo',3,31,'2014-07-01 13:00:00',0,0),(56,'2014-06-26 22:16:01','2014-07-01 18:51:28',1,'8as','Salvador',5,17,'2014-07-01 17:00:00',0,0),(57,'2014-07-01 21:41:00','2014-07-04 18:55:29',1,'4as','Ceará',7,10,'2014-07-04 17:00:00',2,1),(58,'2014-07-01 21:43:06','2014-07-04 14:54:48',1,'4as','Rio de Janeiro',18,1,'2014-07-04 13:00:00',0,1),(59,'2014-07-01 21:44:57','2014-07-05 18:53:48',1,'4as','Bahia',21,13,'2014-07-05 17:00:00',0,0),(60,'2014-07-01 21:45:43','2014-07-05 14:54:04',1,'4as','Brasília',3,5,'2014-07-05 13:00:00',1,0),(61,'2014-07-06 10:55:00','2014-07-06 10:57:59',0,'Semi','Belo Horizonte',7,1,'2014-07-08 17:00:00',0,0),(62,'2014-07-06 10:56:00','2014-07-06 10:57:59',0,'Semi','São Paulo',21,3,'2014-07-09 17:00:00',0,0);

#
# Source for table "game_import"
#

DROP TABLE IF EXISTS `game_import`;
CREATE TABLE `game_import` (
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `game_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `team_a` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `team_b` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `place` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `id_team_a` int(11) NOT NULL DEFAULT '0',
  `id_team_b` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Data for table "game_import"
#

INSERT INTO `game_import` VALUES ('GRUPO A','2014-06-12 17:00:00','Brasil','Croácia','São Paulo',7,14),('GRUPO A','2014-06-13 13:00:00','México','Camarões','Natal',27,8),('GRUPO A','2014-06-17 16:00:00','Brasil','México','Fortaleza',7,27),('GRUPO A','2014-06-18 19:00:00','Camarões','Croácia','Manaus',8,14),('GRUPO A','2014-06-23 17:00:00','Camarões','Brasil','Brasília',8,7),('GRUPO A','2014-06-23 17:00:00','Croácia','México','Recife',14,27),('GRUPO B','2014-06-13 16:00:00','Espanha','Holanda','Salvador',16,21),('GRUPO B','2014-06-13 19:00:00','Chile','Austrália','Cuiabá',9,4),('GRUPO B','2014-06-18 13:00:00','Austrália','Holanda','Porto Alegre',4,21),('GRUPO B','2014-06-18 16:00:00','Espanha','Chile','Rio de Janeiro',16,9),('GRUPO B','2014-06-23 13:00:00','Austrália','Espanha','Curitiba',4,16),('GRUPO B','2014-06-23 13:00:00','Holanda','Chile','São Paulo',21,9),('GRUPO C','2014-06-14 13:00:00','Colômbia','Grécia','Belo Horizonte',10,20),('GRUPO C','2014-06-14 22:00:00','Costa do Marfim','Japão','Recife',12,26),('GRUPO C','2014-06-19 13:00:00','Colômbia','Costa do Marfim','Brasília',10,12),('GRUPO C','2014-06-19 19:00:00','Japão','Grécia','Natal',26,20),('GRUPO C','2014-06-24 17:00:00','Japão','Colômbia','Cuiabá',26,10),('GRUPO C','2014-06-24 17:00:00','Grécia','Costa do Marfim','Fortaleza',20,12),('GRUPO D','2014-06-14 16:00:00','Uruguai','Costa Rica','Fortaleza',32,13),('GRUPO D','2014-06-14 19:00:00','Inglaterra','Itália','Manaus',23,25),('GRUPO D','2014-06-19 16:00:00','Uruguai','Inglaterra','São Paulo',32,23),('GRUPO D','2014-06-20 13:00:00','Itália','Costa Rica','Recife',25,13),('GRUPO D','2014-06-24 13:00:00','Itália','Uruguai','Natal',25,32),('GRUPO D','2014-06-24 13:00:00','Costa Rica','Inglaterra','Belo Horizonte',13,23),('GRUPO E','2014-06-15 13:00:00','Suíça','Equador','Brasília',31,15),('GRUPO E','2014-06-15 16:00:00','França','Honduras','Porto Alegre',18,22),('GRUPO E','2014-06-20 16:00:00','Suíça','França','Salvador',31,18),('GRUPO E','2014-06-20 19:00:00','Honduras','Equador','Curitiba',22,15),('GRUPO E','2014-06-25 17:00:00','Honduras','Suíça','Manaus',22,31),('GRUPO E','2014-06-25 17:00:00','Equador','França','Rio de Janeiro',15,18),('GRUPO F','2014-06-15 19:00:00','Argentina','Bósnia','Rio de Janeiro',3,6),('GRUPO F','2014-06-16 16:00:00','Irã','Nigéria','Curitiba',24,28),('GRUPO F','2014-06-21 13:00:00','Argentina','Irã','Belo Horizonte',3,24),('GRUPO F','2014-06-21 19:00:00','Nigéria','Bósnia','Cuiabá',28,6),('GRUPO F','2014-06-25 13:00:00','Nigéria','Argentina','Porto Alegre',28,3),('GRUPO F','2014-06-25 13:00:00','Bósnia','Irã','Salvador',6,24),('GRUPO G','2014-06-16 13:00:00','Alemanha','Portugal','Salvador',1,29),('GRUPO G','2014-06-16 19:00:00','Gana','Estados Unidos','Natal',19,17),('GRUPO G','2014-06-21 16:00:00','Alemanha','Gana','Fortaleza',1,19),('GRUPO G','2014-06-22 19:00:00','Estados Unidos','Portugal','Manaus',17,29),('GRUPO G','2014-06-26 13:00:00','Estados Unidos','Alemanha','Recife',17,1),('GRUPO G','2014-06-26 13:00:00','Portugal','Gana','Brasília',29,19),('GRUPO H','2014-06-17 13:00:00','Bélgica','Argélia','Belo Horizonte',5,2),('GRUPO H','2014-06-17 19:00:00','Rússia','Coréia do Sul','Cuiabá',30,11),('GRUPO H','2014-06-22 13:00:00','Bélgica','Rússia','Rio de Janeiro',5,30),('GRUPO H','2014-06-22 16:00:00','Coréia do Sul','Argélia','Porto Alegre',11,2),('GRUPO H','2014-06-26 17:00:00','Coréia do Sul','Bélgica','São Paulo',11,5),('GRUPO H','2014-06-26 17:00:00','Argélia','Rússia','Curitiba',2,30);

#
# Source for table "team"
#

DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `icon` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Data for table "team"
#

INSERT INTO `team` VALUES (1,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Alemanha','alemanha_30x30.png'),(2,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Argélia','argelia_30x30.png'),(3,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Argentina','argentina_30x30_.png'),(4,'2014-05-16 21:23:02','2014-05-16 23:52:30',1,'Austrália','australia_30x30.png'),(5,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Bélgica','belgica_30x30.png'),(6,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Bósnia','bosnia_30x30.png'),(7,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Brasil','brasil_30x30.png'),(8,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Camarões','camaroes_30x30.png'),(9,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Chile','chile_30x30.png'),(10,'2014-05-16 21:23:02','2014-05-16 23:52:43',1,'Colômbia','colombia_30x30.png'),(11,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Coréia do Sul','coreia_do_sul_30x30.png'),(12,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Costa do Marfim','costa_do_marfim_30x30.png'),(13,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Costa Rica','costa_rica_30x30.png'),(14,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Croácia','croacia_30x30.png'),(15,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Equador','equador_30x30.png'),(16,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Espanha','espanha_30x30.png'),(17,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Estados Unidos','estados_unidos_30x30.png'),(18,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'França','franca_30x30.png'),(19,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Gana','gana_30x30.png'),(20,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Grécia','grecia_30x30_.png'),(21,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Holanda','holanda_30x30.png'),(22,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Honduras','honduras_30x30.png'),(23,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Inglaterra','inglaterra_30x30.png'),(24,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Irã','ira_30x30.png'),(25,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Itália','italia_30x30.png'),(26,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Japão','japao_30x30.png'),(27,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'México','mexico_30x30.png'),(28,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Nigéria','nigeria_30x30.png'),(29,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Portugal','portugal_30x30.png'),(30,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Rússia','russia_30x30.png'),(31,'2014-05-16 21:23:02','2014-05-16 23:53:39',1,'Suíça','suica_30x30.png'),(32,'2014-05-16 21:23:02','2014-05-16 23:23:02',1,'Uruguai','uruguai_30x30.png');

#
# Source for table "user_bet"
#

DROP TABLE IF EXISTS `user_bet`;
CREATE TABLE `user_bet` (
  `id` int(11) NOT NULL DEFAULT '0',
  `create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_team1` int(11) NOT NULL DEFAULT '0',
  `id_team2` int(11) NOT NULL DEFAULT '0',
  `id_team3` int(11) NOT NULL DEFAULT '0',
  `striker` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `team1_points` int(11) NOT NULL DEFAULT '0',
  `team2_points` int(11) NOT NULL DEFAULT '0',
  `team3_points` int(11) NOT NULL DEFAULT '0',
  `striker_points` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Source for table "game_user"
#

DROP TABLE IF EXISTS `game_user`;
CREATE TABLE `game_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `id_user` int(255) NOT NULL DEFAULT '0',
  `id_game` int(11) NOT NULL DEFAULT '0',
  `score_a` int(11) NOT NULL DEFAULT '0',
  `score_b` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user_2` (`id_user`,`id_game`),
  KEY `id_user` (`id_user`),
  KEY `id_game` (`id_game`)
) ENGINE=InnoDB AUTO_INCREMENT=1372 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Source for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `nickname` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `email` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `master` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `k_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Source for table "user_bet"
#

DROP TABLE IF EXISTS `user_bet`;
CREATE TABLE `user_bet` (
  `id` int(11) NOT NULL DEFAULT '0',
  `create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_team1` int(11) NOT NULL DEFAULT '0',
  `id_team2` int(11) NOT NULL DEFAULT '0',
  `id_team3` int(11) NOT NULL DEFAULT '0',
  `striker` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `team1_points` int(11) NOT NULL DEFAULT '0',
  `team2_points` int(11) NOT NULL DEFAULT '0',
  `team3_points` int(11) NOT NULL DEFAULT '0',
  `striker_points` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

#
# Source for table "user_log"
#

DROP TABLE IF EXISTS `user_log`;
CREATE TABLE `user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `k_id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;