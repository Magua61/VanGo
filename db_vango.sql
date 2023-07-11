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
  `C_Address` varchar(150) DEFAULT NULL,
  `C_Email` varchar(50) DEFAULT NULL,
  `C_PhoneNo` char(11) DEFAULT NULL,
  `C_ProfilePic` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`Customer_ID`),
  UNIQUE KEY `C_Email_UNIQUE` (`C_Email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Juan','Villaceran','Dela Cruz','Palapala, San Ildefonso, Bulacan','jdc@gmail.com','09795482641',NULL),(2,'John','William','Smith','123 Main Street','johnsmith@example.com','09794522641',NULL),(3,'Peter','Santos','Dela Cruz','456 Sampaguita St., Makati City, Metro Manila, Philippines','petdc@example.ph','09123456789',NULL),(4,'James','Santos','Dela Cruz','456 Sampaguita St., Makati City, Metro Manila, Philippines','jamesdc@example.ph','09123456789',NULL),(5,'Maria','Santos','Cruz','789 Rizal Ave., Quezon City, Metro Manila, Philippines','maria.cruz@example.ph','09123458733',NULL),(6,'Carlos','Cruz','Gonzales','789 Oak Street, Cebu City','carlos.gonzales@example.com','09795488973','uploads/profiles/64a25c542a765_1~2bjwCLaA8TfH40OXcyLNvA.png');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
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
  `O_Address` varchar(150) NOT NULL,
  `O_Email` varchar(50) NOT NULL,
  `O_PhoneNo` char(11) NOT NULL,
  `O_ProfilePic` varchar(2048) DEFAULT NULL,
  `O_ValidID` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`Owner_ID`),
  UNIQUE KEY `O_Email_UNIQUE` (`O_Email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner`
--

LOCK TABLES `owner` WRITE;
/*!40000 ALTER TABLE `owner` DISABLE KEYS */;
INSERT INTO `owner` VALUES (1,'Antonio','Santos','Fernandez','567 Cedar Street, Baguio City','antonio.fernandez@example.com','09123456888','uploads/profiles/64a2a607b618a_1_5059972890644971712.webp','uploads/validids/64a2a607b6be6_Screenshot 2023-07-03 183148.png'),(3,'Lucas','Gomez','Garcia','543 Maple Street, Quezon City','lucas.garcia@example.com','09998765432','uploads/profiles/64a7550804f63_4_5881881995893541585.webp','uploads/validids/64a75508052f0_Valid ID.png');
/*!40000 ALTER TABLE `owner` ENABLE KEYS */;
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
  UNIQUE KEY `C_Email_UNIQUE` (`User_Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password`
--

LOCK TABLES `password` WRITE;
/*!40000 ALTER TABLE `password` DISABLE KEYS */;
INSERT INTO `password` VALUES ('antonio.fernandez@example.com','747838fd95b684fac806c20eea228bc81fd58e7e24c3ad70bfe4e42b766cadee','db993a7d7d67f73eadd30cee99c8ccc2'),('carlos.gonzales@example.com','1bb215a7de20813fc498986b97033faab09f8eaa14ec763b7d54dc6d39803d11','b8fc15a7ed3e7b85ec865988b9bdc5b7'),('jamesdc@example.ph','dec213d9a2710457a4de6cf2507495b35c2ee022209aa3fce5bcf35ddbd49c5f','6388451f6ee781d40e458dd2d76e94b0'),('jdc@gmail.com','e7f9d80665c9a826a0b05da0f2070d32c7f1254b1745ef9ae19e68ff1d87f84e','a1b2c3d4e5f6g7h8'),('johnsmith@example.com','0a3c08075fadf8b36185ef55120422c26615a9ed1ec868a4c18557e601c1e7d6','0aa47b6117f286bb71dc1a0ec191ed53'),('lucas.garcia@example.com','dfe2d5c3ce5d02ade317dcbff20ed7c7724b27d1fbbc41b3b9d6168cfc56ab9a','6283eb1441e7133f364102e6919161f6'),('maria.cruz@example.ph','72ae9fc6eea47ff62027c89ede6a5f02c6b888397294e036e4b6c1c811704d80','68fa69badef4caba58fc798d586b3aaa'),('petdc@example.ph','8d6943814a0b0b49c1b25b9ae566bac4453d1eaf709d510f3d14e9b5aa0a8e47','a8c73c5459eb595c4ccea568d508ca41');
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
  PRIMARY KEY (`Payment_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (1,2,18000.00,'2023-07-10 10:59:17');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental`
--

LOCK TABLES `rental` WRITE;
/*!40000 ALTER TABLE `rental` DISABLE KEYS */;
INSERT INTO `rental` VALUES (2,2,6,'Ilocos Norte','SM Manila','2023-07-12','10:00:00','2023-07-15','10:00:00','Cancelled');
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
/*!40000 ALTER TABLE `rental_without_driver` ENABLE KEYS */;
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
  UNIQUE KEY `User_Email_UNIQUE` (`User_Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('antonio.fernandez@example.com','owner'),('carlos.gonzales@example.com','customer'),('jamesdc@example.ph','customer'),('jdc@gmail.com','customer'),('johnsmith@example.com','customer'),('lucas.garcia@example.com','owner'),('maria.cruz@example.ph','customer'),('petdc@example.ph','customer');
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
  `V_CR` varchar(2048) NOT NULL,
  `V_Photo` varchar(2048) NOT NULL,
  `Owner_ID` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`Van_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van`
--

LOCK TABLES `van` WRITE;
/*!40000 ALTER TABLE `van` DISABLE KEYS */;
INSERT INTO `van` VALUES (1,'EG 7777','Toyota','Hiace',2019,16,'uploads/certificates/64a2a607b7126_Screenshot 2023-07-03 183552.png','uploads/van_photos/64a2a607b7f89_HIACE.jpg','1'),(2,'GG 8231','Toyota','Hiace Super Grandia',2023,16,'uploads/certificates/64a75508057ac_CR.png','uploads/van_photos/64a755080687e_Toyota_Hiace_Super_Grandia.jpg','3');
/*!40000 ALTER TABLE `van` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_rate`
--

DROP TABLE IF EXISTS `van_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `van_rate` (
  `Van_ID` int NOT NULL,
  `V_Rate` decimal(9,2) DEFAULT NULL,
  KEY `FK_VanRate_Van_ID_idx` (`Van_ID`),
  CONSTRAINT `FK_VanRate_Van_ID` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_rate`
--

LOCK TABLES `van_rate` WRITE;
/*!40000 ALTER TABLE `van_rate` DISABLE KEYS */;
INSERT INTO `van_rate` VALUES (1,4500.00),(2,5000.00);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_unavailable_date`
--

LOCK TABLES `van_unavailable_date` WRITE;
/*!40000 ALTER TABLE `van_unavailable_date` DISABLE KEYS */;
INSERT INTO `van_unavailable_date` VALUES (1,1,'2023-07-10','2023-07-14','Unavailable'),(2,1,'2023-07-18','2023-07-21','Unavailable'),(4,1,'2023-07-24','2023-07-26','Unavailable'),(8,2,'2023-07-12','2023-07-15','Booked');
/*!40000 ALTER TABLE `van_unavailable_date` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `van_unavailable_date_BEFORE_DELETE` BEFORE DELETE ON `van_unavailable_date` FOR EACH ROW BEGIN
	UPDATE rental
    SET Rental_Status = 'Cancelled'
    WHERE Van_ID = OLD.Van_ID
      AND Pickup_Date = OLD.Start_Date
      AND Return_Date = OLD.End_Date;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping routines for database 'db_vango'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-11 20:47:32
