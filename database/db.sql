-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: db_hotel
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_kamar`
--

DROP TABLE IF EXISTS `tb_kamar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_kamar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `harga` bigint NOT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_kamar`
--

LOCK TABLES `tb_kamar` WRITE;
/*!40000 ALTER TABLE `tb_kamar` DISABLE KEYS */;
INSERT INTO `tb_kamar` VALUES (22,'20240502072523_66333fe32cdf9.jpg','Standard',200000,'https://www.youtube.com/embed/rXx4piaJ2x8?si=poTedH3kJSM-mhY7'),(23,'20240502072226_66333f32cfd84.jpg','Deluxe',400000,'https://www.youtube.com/embed/7KRw0nq6TaE?si=8r-0UyHW7Yy0vhy0'),(24,'20240502072332_66333f7406f0d.jpg','Family',600000,'https://www.youtube.com/embed/1LCXcXR9UFM?si=kjeFGQDNVaGtslUJ');
/*!40000 ALTER TABLE `tb_kamar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_pemesanan`
--

DROP TABLE IF EXISTS `tb_pemesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_pemesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `identitas` bigint NOT NULL,
  `kelamin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kamar` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `tglpesan` date DEFAULT NULL,
  `jmlHari` int DEFAULT NULL,
  `breakfast` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_pemesanan`
--

LOCK TABLES `tb_pemesanan` WRITE;
/*!40000 ALTER TABLE `tb_pemesanan` DISABLE KEYS */;
INSERT INTO `tb_pemesanan` VALUES (4,'dhimas',3242342342344321,'perempuan',17,500000,'2024-05-03',4,0),(5,'dhimas',7867868687677679,'laki',17,500000,'2024-05-02',3,0),(6,'dhimas',3432424221312312,'perempuan',17,500000,'2024-05-03',5,0),(7,'dhimas',2342342343434354,'laki',17,500000,'2024-05-03',2,0),(9,'dhimas',3243243434345211,'laki',19,800000,'2024-05-03',6,0),(10,'dhimas',8768668768765876,'laki',22,200000,'2024-05-03',4,1),(11,'dhimas',3242343242342344,'laki',22,200000,'2024-05-03',3,1);
/*!40000 ALTER TABLE `tb_pemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_user`
--

DROP TABLE IF EXISTS `tb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_user`
--

LOCK TABLES `tb_user` WRITE;
/*!40000 ALTER TABLE `tb_user` DISABLE KEYS */;
INSERT INTO `tb_user` VALUES (1,'admin','$2y$10$N/o7VczwnU0w/n0a5QAwqOt5lNWJ648rHvU6RX69JBlX.swgcDEw.','admin'),(2,'customer','$2y$10$wP97A7IDeE7F6MOQ7tBIZubmarI2RHJedc9GpZRvwINeWsM.NT6/K','customer'),(3,'testing','$2y$10$bHm/42YOhcsNiNNMTd.y2OWqcUzILBos.i/MZSeEQAN6oaRW1NNMC','customer');
/*!40000 ALTER TABLE `tb_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'db_hotel'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-03  0:21:10
