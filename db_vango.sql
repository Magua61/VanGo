CREATE DATABASE  IF NOT EXISTS `db_vango` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_vango`;
-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: db_vango
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `Customer_ID` int NOT NULL AUTO_INCREMENT,
  `C_FName` varchar(30) DEFAULT NULL,
  `C_MName` varchar(20) DEFAULT NULL,
  `C_LName` varchar(20) DEFAULT NULL,
  `C_Gender` varchar(20) DEFAULT NULL,
  `C_Address` varchar(150) DEFAULT NULL,
  `C_Birthdate` date DEFAULT NULL,
  `C_Email` varchar(50) DEFAULT NULL,
  `C_PhoneNo` char(11) DEFAULT NULL,
  PRIMARY KEY (`Customer_ID`),
  UNIQUE KEY `C_Email_UNIQUE` (`C_Email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Juan','Villaceran','Dela Cruz',NULL,'Palapala, San Ildefonso, Bulacan',NULL,'jdc@gmail.com','09795482641'),(2,'John','William','Smith',NULL,'123 Main Street',NULL,'johnsmith@example.com','09794522641'),(3,'Peter','Santos','Dela Cruz',NULL,'456 Sampaguita St., Makati City, Metro Manila, Philippines',NULL,'petdc@example.ph','09123456789'),(4,'James','Santos','Dela Cruz',NULL,'456 Sampaguita St., Makati City, Metro Manila, Philippines',NULL,'jamesdc@example.ph','09123456789'),(5,'Maria','Santos','Cruz',NULL,'789 Rizal Ave., Quezon City, Metro Manila, Philippines',NULL,'maria.cruz@example.ph','09123458733'),(6,'Carlos','Cruz','Gonzales',NULL,'789 Oak Street, Cebu City           ','1998-01-17','carlos.gonzales@example.com','09795488973'),(7,'Frank','Cruz','Dela Cruz','Male','422 Sampaguita St., Makati City, Metro Manila, Philippines','2015-01-17','frank.dc@example.ph','09789456444');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `customer_AFTER_UPDATE` AFTER UPDATE ON `customer` FOR EACH ROW BEGIN
	IF NEW.C_Email <> OLD.C_Email THEN
        UPDATE user
        SET User_Email = NEW.C_Email
        WHERE User_Email = OLD.C_Email;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `customer_AFTER_DELETE` AFTER DELETE ON `customer` FOR EACH ROW BEGIN
	DELETE FROM user WHERE User_Email = OLD.C_Email;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `customer_profile`
--

DROP TABLE IF EXISTS `customer_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_profile` (
  `Customer_ID` int DEFAULT NULL,
  `C_ProfilePic` varchar(2048) DEFAULT NULL,
  KEY `FK_C_ProfilePic_idx` (`Customer_ID`),
  CONSTRAINT `FK_C_ProfilePic` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_profile`
--

LOCK TABLES `customer_profile` WRITE;
/*!40000 ALTER TABLE `customer_profile` DISABLE KEYS */;
INSERT INTO `customer_profile` VALUES (6,'../registration/uploads/profiles/64b67a6c00271_1610243189305.jpg');
/*!40000 ALTER TABLE `customer_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owner`
--

DROP TABLE IF EXISTS `owner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owner` (
  `Owner_ID` int NOT NULL AUTO_INCREMENT,
  `O_FName` varchar(30) NOT NULL,
  `O_MName` varchar(20) NOT NULL,
  `O_LName` varchar(20) NOT NULL,
  `O_Gender` varchar(20) NOT NULL,
  `O_Address` varchar(150) NOT NULL,
  `O_Birthdate` date NOT NULL,
  `O_Email` varchar(50) NOT NULL,
  `O_PhoneNo` char(11) NOT NULL,
  PRIMARY KEY (`Owner_ID`),
  UNIQUE KEY `O_Email_UNIQUE` (`O_Email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner`
--

LOCK TABLES `owner` WRITE;
/*!40000 ALTER TABLE `owner` DISABLE KEYS */;
INSERT INTO `owner` VALUES (1,'Antonio','Santos','Fernandez','Male','567 Cedar Street, Baguio City','1983-06-13','antonio.fernandez@example.com','09123456888'),(3,'Lucas','Gomez','Garcia','Male','543 Maple Street, Quezon City            ','1980-03-16','lucas.garcia@example.com','09998765432'),(6,'John','Cruz','Santos','Male','447 Cedar Street, Baguio City','1987-06-17','john.santos@example.ph','09789456333');
/*!40000 ALTER TABLE `owner` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `owner_AFTER_UPDATE` AFTER UPDATE ON `owner` FOR EACH ROW BEGIN
    IF NEW.O_Email <> OLD.O_Email THEN
        UPDATE user
        SET User_Email = NEW.O_Email
        WHERE User_Email = OLD.O_Email;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `owner_AFTER_DELETE` AFTER DELETE ON `owner` FOR EACH ROW BEGIN
	DELETE FROM van WHERE Owner_ID = OLD.Owner_ID;
    
    DELETE FROM user WHERE User_Email = OLD.O_Email;
    
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `owner_profile`
--

DROP TABLE IF EXISTS `owner_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owner_profile` (
  `Owner_ID` int DEFAULT NULL,
  `O_ProfilePic` varchar(2048) DEFAULT NULL,
  KEY `FK_O_ProfilePic_idx` (`Owner_ID`),
  CONSTRAINT `FK_O_ProfilePic` FOREIGN KEY (`Owner_ID`) REFERENCES `owner` (`Owner_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner_profile`
--

LOCK TABLES `owner_profile` WRITE;
/*!40000 ALTER TABLE `owner_profile` DISABLE KEYS */;
INSERT INTO `owner_profile` VALUES (1,'uploads/profiles/64a2a607b618a_1_5059972890644971712.webp'),(3,'../registration/uploads/profiles/64b8ea872d597_1601172675683.jpg');
/*!40000 ALTER TABLE `owner_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owner_valid_id`
--

DROP TABLE IF EXISTS `owner_valid_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owner_valid_id` (
  `Owner_ID` int DEFAULT NULL,
  `O_ValidID` varchar(2048) DEFAULT NULL,
  KEY `FK_O_ValidID_idx` (`Owner_ID`),
  CONSTRAINT `FK_O_ValidID` FOREIGN KEY (`Owner_ID`) REFERENCES `owner` (`Owner_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner_valid_id`
--

LOCK TABLES `owner_valid_id` WRITE;
/*!40000 ALTER TABLE `owner_valid_id` DISABLE KEYS */;
INSERT INTO `owner_valid_id` VALUES (1,'uploads/validids/64a2a607b6be6_Screenshot 2023-07-03 183148.png'),(3,'uploads/validids/64a75508052f0_Valid ID.png'),(6,'uploads/validids/64b46e3160c7e_Valid ID.png');
/*!40000 ALTER TABLE `owner_valid_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password`
--

DROP TABLE IF EXISTS `password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password` (
  `User_Email` varchar(50) NOT NULL,
  `Hash_Password` varchar(1000) DEFAULT NULL,
  `Salt_Password` varchar(1000) DEFAULT NULL,
  UNIQUE KEY `C_Email_UNIQUE` (`User_Email`),
  CONSTRAINT `FK_Password` FOREIGN KEY (`User_Email`) REFERENCES `user` (`User_Email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password`
--

LOCK TABLES `password` WRITE;
/*!40000 ALTER TABLE `password` DISABLE KEYS */;
INSERT INTO `password` VALUES ('antonio.fernandez@example.com','747838fd95b684fac806c20eea228bc81fd58e7e24c3ad70bfe4e42b766cadee','db993a7d7d67f73eadd30cee99c8ccc2'),('carlos.gonzales@example.com','1bb215a7de20813fc498986b97033faab09f8eaa14ec763b7d54dc6d39803d11','b8fc15a7ed3e7b85ec865988b9bdc5b7'),('frank.dc@example.ph','c99c7b4528917061a438b07d92a96d0cec953849779411cecfdcdb1e3c11698d','6c5c338a144f0d44b4f12f253c93a642'),('jamesdc@example.ph','dec213d9a2710457a4de6cf2507495b35c2ee022209aa3fce5bcf35ddbd49c5f','6388451f6ee781d40e458dd2d76e94b0'),('jdc@gmail.com','e7f9d80665c9a826a0b05da0f2070d32c7f1254b1745ef9ae19e68ff1d87f84e','a1b2c3d4e5f6g7h8'),('john.santos@example.ph','3f18bbe9c2bb7f1f2e792eb5dcb8e42eaa322e1b04eb2227af70afd062a35766','2c778e9868f84b43cb69036c67402b90'),('johnsmith@example.com','0a3c08075fadf8b36185ef55120422c26615a9ed1ec868a4c18557e601c1e7d6','0aa47b6117f286bb71dc1a0ec191ed53'),('lucas.garcia@example.com','dfe2d5c3ce5d02ade317dcbff20ed7c7724b27d1fbbc41b3b9d6168cfc56ab9a','6283eb1441e7133f364102e6919161f6'),('maria.cruz@example.ph','72ae9fc6eea47ff62027c89ede6a5f02c6b888397294e036e4b6c1c811704d80','68fa69badef4caba58fc798d586b3aaa'),('petdc@example.ph','8d6943814a0b0b49c1b25b9ae566bac4453d1eaf709d510f3d14e9b5aa0a8e47','a8c73c5459eb595c4ccea568d508ca41');
/*!40000 ALTER TABLE `password` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `Payment_ID` int NOT NULL AUTO_INCREMENT,
  `Rental_ID` int DEFAULT NULL,
  `Payment_Amount` decimal(10,2) NOT NULL,
  `Payment_Date_Time` datetime NOT NULL,
  PRIMARY KEY (`Payment_ID`),
  KEY `FK_Rental_ID_idx` (`Rental_ID`),
  CONSTRAINT `FK_Rental_ID` FOREIGN KEY (`Rental_ID`) REFERENCES `rental` (`Rental_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (1,2,18000.00,'2023-07-10 10:59:17'),(4,5,18000.00,'2023-07-12 06:09:17'),(5,6,13500.00,'2023-07-17 06:55:31'),(6,7,12000.00,'2023-07-17 07:06:15'),(7,8,9000.00,'2023-07-19 14:47:51'),(8,9,25000.00,'2023-07-21 03:44:39'),(9,10,4500.00,'2023-07-21 04:18:31'),(10,11,4500.00,'2023-07-21 04:19:22'),(11,12,4500.00,'2023-07-21 04:19:59'),(12,13,5500.00,'2023-07-21 04:21:47'),(13,14,90000.00,'2023-07-21 04:30:13'),(14,15,90000.00,'2023-07-21 04:31:07'),(15,16,27500.00,'2023-07-21 04:38:34'),(16,17,16500.00,'2023-07-21 04:40:45');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `payment_AFTER_INSERT` AFTER INSERT ON `payment` FOR EACH ROW BEGIN
    INSERT INTO payment_history (Payment_ID, Rental_ID, Payment_Amount, Payment_Date_Time, Action, Action_Datetime)
    VALUES (NEW.Payment_ID, NEW.Rental_ID, NEW.Payment_Amount, NEW.Payment_Date_Time, 'Insert', NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `payment_AFTER_UPDATE` AFTER UPDATE ON `payment` FOR EACH ROW BEGIN
	INSERT INTO payment_history (Payment_ID, Rental_ID, Payment_Amount, Payment_Date_Time, Action, Action_Datetime)
    VALUES (OLD.Payment_ID, OLD.Rental_ID, OLD.Payment_Amount, OLD.Payment_Date_Time, 'Update', NOW());
	
	INSERT INTO payment_history (Payment_ID, Rental_ID, Payment_Amount, Payment_Date_Time, Action, Action_Datetime)
    VALUES (NEW.Payment_ID, NEW.Rental_ID, NEW.Payment_Amount, NEW.Payment_Date_Time, 'Update', NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `payment_AFTER_DELETE` AFTER DELETE ON `payment` FOR EACH ROW BEGIN
	INSERT INTO payment_history (Payment_ID, Rental_ID, Payment_Amount, Payment_Date_Time, Action, Action_Datetime)
    VALUES (OLD.Payment_ID, OLD.Rental_ID, OLD.Payment_Amount, OLD.Payment_Date_Time, 'Delete', NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `payment_history`
--

DROP TABLE IF EXISTS `payment_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_history` (
  `History_ID` int NOT NULL AUTO_INCREMENT,
  `Payment_ID` int NOT NULL,
  `Rental_ID` int DEFAULT NULL,
  `Payment_Amount` decimal(10,2) NOT NULL,
  `Payment_Date_Time` datetime NOT NULL,
  `Action` varchar(45) DEFAULT NULL,
  `Action_Datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`History_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_history`
--

LOCK TABLES `payment_history` WRITE;
/*!40000 ALTER TABLE `payment_history` DISABLE KEYS */;
INSERT INTO `payment_history` VALUES (1,7,8,9000.00,'2023-07-19 14:47:51','Insert','2023-07-19 14:47:51'),(2,8,9,25000.00,'2023-07-21 03:44:39','Insert','2023-07-21 03:44:39'),(3,9,10,4500.00,'2023-07-21 04:18:31','Insert','2023-07-21 04:18:31'),(4,10,11,4500.00,'2023-07-21 04:19:22','Insert','2023-07-21 04:19:22'),(5,11,12,4500.00,'2023-07-21 04:19:59','Insert','2023-07-21 04:19:59'),(6,12,13,5500.00,'2023-07-21 04:21:47','Insert','2023-07-21 04:21:47'),(7,13,14,90000.00,'2023-07-21 04:30:13','Insert','2023-07-21 04:30:13'),(8,14,15,90000.00,'2023-07-21 04:31:07','Insert','2023-07-21 04:31:07'),(9,15,16,27500.00,'2023-07-21 04:38:34','Insert','2023-07-21 04:38:34'),(10,16,17,16500.00,'2023-07-21 04:40:45','Insert','2023-07-21 04:40:45');
/*!40000 ALTER TABLE `payment_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rental`
--

DROP TABLE IF EXISTS `rental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rental` (
  `Rental_ID` int NOT NULL AUTO_INCREMENT,
  `Van_ID` int DEFAULT NULL,
  `Customer_ID` int DEFAULT NULL,
  `Destination` varchar(200) DEFAULT NULL,
  `Pickup_Address` varchar(200) DEFAULT NULL,
  `Pickup_Date` date DEFAULT NULL,
  `Pickup_Time` time DEFAULT NULL,
  `Return_Date` date DEFAULT NULL,
  `Return_Time` time DEFAULT NULL,
  `Rental_Status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Rental_ID`),
  KEY `FK_Van_ID_idx` (`Van_ID`),
  KEY `FKK_Customer_ID_idx` (`Customer_ID`),
  CONSTRAINT `FK_Rental_Customer_ID` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_Rental_Van_ID` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental`
--

LOCK TABLES `rental` WRITE;
/*!40000 ALTER TABLE `rental` DISABLE KEYS */;
INSERT INTO `rental` VALUES (2,2,6,'Ilocos Norte','SM Manila','2023-07-12','10:00:00','2023-07-15','10:00:00','Cancelled'),(5,2,6,'Bagaiso','aaeqweqew','2023-07-17','10:00:00','2023-07-20','10:00:00','Completed'),(6,6,7,'Morong, Bataan','PUP Manila','2023-07-25','05:00:00','2023-07-28','05:00:00','Pending'),(7,2,7,'Batangas','PUP Manila','2023-07-25','08:00:00','2023-07-27','08:00:00','Pending'),(8,6,6,'Baler','PUP Sta. Mesa','2023-07-06','04:00:00','2023-07-08','04:00:00','Completed'),(9,2,6,'Lucban, Quezon','PUP Manila','2023-07-23','05:00:00','2023-07-28','05:00:00','Pending'),(10,1,6,'qweqe','eqweqw','2023-07-22','07:18:00','2023-07-23','07:18:00','Pending'),(11,1,6,'qweqe','eqweqw','2023-07-22','07:18:00','2023-07-23','07:18:00','Pending'),(12,1,6,'qweqe','eqweqw','2023-07-22','07:18:00','2023-07-23','07:18:00','Pending'),(13,1,6,'Tagaytay','PUP Sta. Mesa','2023-07-27','07:00:00','2023-07-28','07:00:00','Pending'),(14,2,6,'aweq','eweqw','2023-08-05','04:32:00','2023-08-23','04:32:00','Pending'),(15,2,6,'aweq','eweqw','2023-08-05','04:32:00','2023-08-23','04:32:00','Pending'),(16,1,6,'WEeqe','ewqweqeq','2023-10-17','06:38:00','2023-10-22','06:38:00','Pending'),(17,1,6,'Tagaytay','PUP Manila','2023-09-12','08:00:00','2023-09-15','08:00:00','Pending');
/*!40000 ALTER TABLE `rental` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `rental_AFTER_INSERT` AFTER INSERT ON `rental` FOR EACH ROW BEGIN
    
    INSERT INTO van_unavailable_date (Van_ID, Start_Date, End_Date, Status)
	SELECT Van_ID, Pickup_Date, Return_Date, 'Booked'
	FROM rental
	ORDER BY Rental_ID DESC
	LIMIT 1;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `rental_without_driver`
--

DROP TABLE IF EXISTS `rental_without_driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rental_without_driver` (
  `Rental_ID` int DEFAULT NULL,
  `Return_Address` varchar(200) DEFAULT NULL,
  KEY `FK_WODriver_Rental_ID_idx` (`Rental_ID`),
  CONSTRAINT `FK_WODriver_Rental_ID` FOREIGN KEY (`Rental_ID`) REFERENCES `rental` (`Rental_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental_without_driver`
--

LOCK TABLES `rental_without_driver` WRITE;
/*!40000 ALTER TABLE `rental_without_driver` DISABLE KEYS */;
INSERT INTO `rental_without_driver` VALUES (6,'PUP Manila'),(8,'PUP Sta. Mesa'),(9,'PUP Manila'),(10,'qweqw'),(11,'qweqw'),(12,'qweqw'),(14,'qweqw'),(15,'qweqw');
/*!40000 ALTER TABLE `rental_without_driver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `Review_ID` int NOT NULL AUTO_INCREMENT,
  `Review_Rating` int NOT NULL,
  `Review_Comment` varchar(1000) NOT NULL,
  `Review_Datetime` datetime NOT NULL,
  `Rental_ID` int DEFAULT NULL,
  PRIMARY KEY (`Review_ID`),
  KEY `FK_RentalID_idx` (`Rental_ID`),
  CONSTRAINT `FK_RentalID` FOREIGN KEY (`Rental_ID`) REFERENCES `rental` (`Rental_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (5,5,'Excellent service!!!','2023-07-12 09:17:49',5),(6,5,'Excellent van, excellent service!!','2023-07-19 06:37:12',5),(32,5,'Excellent van!!','2023-07-19 14:50:28',8);
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_photo`
--

DROP TABLE IF EXISTS `review_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review_photo` (
  `Review_ID` int DEFAULT NULL,
  `Review_Photo` varchar(2048) DEFAULT NULL,
  KEY `FK_Review_Photo_idx` (`Review_ID`),
  CONSTRAINT `FK_Review_Photo` FOREIGN KEY (`Review_ID`) REFERENCES `review` (`Review_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_photo`
--

LOCK TABLES `review_photo` WRITE;
/*!40000 ALTER TABLE `review_photo` DISABLE KEYS */;
INSERT INTO `review_photo` VALUES (6,'64b714186452a_4_694361646142849078.webp'),(32,'64b787b44ee01_3c1a26e92d0b419b0025f77ea6b1a352.jpg');
/*!40000 ALTER TABLE `review_photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `User_Email` varchar(50) NOT NULL,
  `User_Type` varchar(8) DEFAULT NULL,
  `User_RegiDatetime` datetime DEFAULT NULL,
  UNIQUE KEY `User_Email_UNIQUE` (`User_Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('antonio.fernandez@example.com','owner','2023-06-18 01:32:02'),('carlos.gonzales@example.com','customer','2023-07-01 01:32:02'),('frank.dc@example.ph','customer',NULL),('jamesdc@example.ph','customer',NULL),('jdc@gmail.com','customer',NULL),('john.santos@example.ph','owner','2023-06-18 01:32:02'),('johnsmith@example.com','customer',NULL),('lucas.garcia@example.com','owner','2023-06-18 01:32:02'),('maria.cruz@example.ph','customer',NULL),('petdc@example.ph','customer',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van`
--

DROP TABLE IF EXISTS `van`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `van` (
  `Van_ID` int NOT NULL AUTO_INCREMENT,
  `V_PlateNo` varchar(7) NOT NULL,
  `V_Make` varchar(40) NOT NULL,
  `V_Model` varchar(50) NOT NULL,
  `V_Year` year NOT NULL,
  `V_Capacity` int NOT NULL,
  `Owner_ID` int DEFAULT NULL,
  PRIMARY KEY (`Van_ID`),
  KEY `FK_Van_idx` (`Owner_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van`
--

LOCK TABLES `van` WRITE;
/*!40000 ALTER TABLE `van` DISABLE KEYS */;
INSERT INTO `van` VALUES (1,'EG 7777','Toyota','Hiace',2019,16,1),(2,'GG 8231','Toyota','Hiace Super Grandia',2023,16,3),(6,'VB 4231','Nissan','NV350',2023,15,6);
/*!40000 ALTER TABLE `van` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_document`
--

DROP TABLE IF EXISTS `van_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `van_document` (
  `Van_ID` int DEFAULT NULL,
  `V_OR` varchar(2048) DEFAULT NULL,
  `V_CR` varchar(2048) DEFAULT NULL,
  KEY `FK_Document_idx` (`Van_ID`),
  CONSTRAINT `FK_Document` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_document`
--

LOCK TABLES `van_document` WRITE;
/*!40000 ALTER TABLE `van_document` DISABLE KEYS */;
INSERT INTO `van_document` VALUES (1,NULL,'uploads/certificates/64a2a607b7126_Screenshot 2023-07-03 183552.png'),(2,'uploads/receipts/OR.jpg','uploads/certificates/CR.jpg'),(6,'uploads/receipts/64b46e316167d_or.png','uploads/certificates/64b46e3161177_CR.png');
/*!40000 ALTER TABLE `van_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_photo`
--

DROP TABLE IF EXISTS `van_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `van_photo` (
  `Van_ID` int DEFAULT NULL,
  `V_Photo` varchar(2048) DEFAULT NULL,
  KEY `FK_V_Photo_idx` (`Van_ID`),
  CONSTRAINT `FK_V_Photo` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_photo`
--

LOCK TABLES `van_photo` WRITE;
/*!40000 ALTER TABLE `van_photo` DISABLE KEYS */;
INSERT INTO `van_photo` VALUES (1,'uploads/van_photos/64a2a607b7f89_HIACE.jpg'),(2,'uploads/van_photos/64a755080687e_Toyota_Hiace_Super_Grandia.jpg'),(6,'uploads/van_photos/64b46e3162d0b_nissan.jpg');
/*!40000 ALTER TABLE `van_photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_rate`
--

DROP TABLE IF EXISTS `van_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `van_rate` (
  `Van_ID` int NOT NULL,
  `V_Rate` decimal(20,2) DEFAULT NULL,
  KEY `FK_VanRate_Van_ID_idx` (`Van_ID`),
  CONSTRAINT `FK_VanRate_Van_ID` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_rate`
--

LOCK TABLES `van_rate` WRITE;
/*!40000 ALTER TABLE `van_rate` DISABLE KEYS */;
INSERT INTO `van_rate` VALUES (1,4500.00),(2,5000.00),(6,4500.00);
/*!40000 ALTER TABLE `van_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_unavailable_date`
--

DROP TABLE IF EXISTS `van_unavailable_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `van_unavailable_date` (
  `XDate_ID` int NOT NULL AUTO_INCREMENT,
  `Van_ID` int DEFAULT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date DEFAULT NULL,
  `Status` varchar(45) DEFAULT 'Unavailable',
  PRIMARY KEY (`XDate_ID`),
  KEY `FK_Van_ID_idx` (`Van_ID`),
  CONSTRAINT `FK_Van_ID` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_unavailable_date`
--

LOCK TABLES `van_unavailable_date` WRITE;
/*!40000 ALTER TABLE `van_unavailable_date` DISABLE KEYS */;
INSERT INTO `van_unavailable_date` VALUES (1,1,'2023-07-10','2023-07-14','Unavailable'),(2,1,'2023-07-18','2023-07-21','Unavailable'),(4,1,'2023-07-24','2023-07-26','Unavailable'),(11,2,'2023-07-17','2023-07-20','Booked'),(12,2,'2023-07-31','2023-08-02','Unavailable'),(13,6,'2023-07-25','2023-07-28','Booked'),(14,2,'2023-07-25','2023-07-27','Booked'),(15,6,'2023-07-06','2023-07-08','Booked'),(17,2,'2023-07-23','2023-07-28','Booked'),(18,1,'2023-07-22','2023-07-23','Booked'),(19,1,'2023-07-22','2023-07-23','Booked'),(20,1,'2023-07-22','2023-07-23','Booked'),(21,1,'2023-07-27','2023-07-28','Booked'),(22,2,'2023-08-05','2023-08-23','Booked'),(23,2,'2023-08-05','2023-08-23','Booked'),(24,1,'2023-10-17','2023-10-22','Booked'),(25,1,'2023-09-12','2023-09-15','Booked');
/*!40000 ALTER TABLE `van_unavailable_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'db_vango'
--

--
-- Dumping routines for database 'db_vango'
--
/*!50003 DROP PROCEDURE IF EXISTS `cancelRental` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `cancelRental`(IN inRental_ID INT)
BEGIN
	UPDATE rental SET Rental_Status = 'Cancelled' WHERE Rental_ID=inRental_ID;
    
    DELETE FROM van_unavailable_date 
	WHERE 
		Van_ID = (SELECT Van_ID FROM rental WHERE Rental_ID = inRental_ID) 
		AND Start_Date = (SELECT Pickup_Date FROM rental WHERE Rental_ID = inRental_ID)
		AND End_Date = (SELECT Return_Date FROM rental WHERE Rental_ID = inRental_ID)
        AND Status = 'Booked';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `deleteDate` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteDate`(in inXDate_ID int)
BEGIN
	UPDATE rental 
    SET Rental_Status = 'Cancelled' 
    WHERE Van_ID = (SELECT Van_ID FROM van_unavailable_date where XDate_ID = inXDate_ID)
		and Pickup_Date = (SELECT Start_Date FROM van_unavailable_date where XDate_ID = inXDate_ID)
        and Return_Date = (SELECT End_Date FROM van_unavailable_date where XDate_ID = inXDate_ID)
		and Status = 'Booked';
        
	DELETE FROM van_unavailable_date WHERE XDate_ID = inXDate_ID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-21  5:00:07
