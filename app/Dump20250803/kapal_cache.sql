-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: kapal
-- ------------------------------------------------------
-- Server version	8.0.39

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel_cache_4QBDrvzsOOMgcyEW','a:1:{s:11:\"valid_until\";i:1753633640;}',1754843300),('laravel_cache_9WxLKG2SWe3co9qp','a:1:{s:11:\"valid_until\";i:1752562834;}',1753772494),('laravel_cache_aeJKJthtIWz49nFU','a:1:{s:11:\"valid_until\";i:1752072104;}',1753281584),('laravel_cache_aRT1K9S9RZeGlIsK','a:1:{s:11:\"valid_until\";i:1753634149;}',1754843449),('laravel_cache_Dj0Hv6MCOs4Fm40K','a:1:{s:11:\"valid_until\";i:1751907210;}',1753116750),('laravel_cache_DRisA5V6AGuVNPcG','a:1:{s:11:\"valid_until\";i:1752340098;}',1753546398),('laravel_cache_dV8IrXW3BRfR5fdB','a:1:{s:11:\"valid_until\";i:1753636048;}',1754843848),('laravel_cache_E3idOaIH1m55He4Z','a:1:{s:11:\"valid_until\";i:1752587647;}',1753797307),('laravel_cache_gZ7ckQTpjjqM1VRI','a:1:{s:11:\"valid_until\";i:1752074183;}',1753282403),('laravel_cache_kjII5xmqoeDpglLu','a:1:{s:11:\"valid_until\";i:1754201935;}',1755411115),('laravel_cache_KuqKavND9zIrYYAy','a:1:{s:11:\"valid_until\";i:1752072592;}',1753282252),('laravel_cache_mpJpziV8X3glywpv','a:1:{s:11:\"valid_until\";i:1752578320;}',1753787920),('laravel_cache_nrOliFGEpyFdgPhP','a:1:{s:11:\"valid_until\";i:1752563321;}',1753772501),('laravel_cache_O6RHZk8bZbiu2WM5','a:1:{s:11:\"valid_until\";i:1752587576;}',1753797176),('laravel_cache_Qurg1ZXiay6fD8V9','a:1:{s:11:\"valid_until\";i:1752516821;}',1753723781),('laravel_cache_RoMoWnCeINpD5iCa','a:1:{s:11:\"valid_until\";i:1752586029;}',1753794909),('laravel_cache_SXehMPMAwlQDhdAM','a:1:{s:11:\"valid_until\";i:1752585246;}',1753794906),('laravel_cache_uYIwaG0oZShsQH6Y','a:1:{s:11:\"valid_until\";i:1754204292;}',1755411612),('laravel_cache_Vhd2lPMJJPYVNbZG','a:1:{s:11:\"valid_until\";i:1752105189;}',1753313529),('laravel_cache_vmXM3PTPhtAsPa27','a:1:{s:11:\"valid_until\";i:1752586335;}',1753795875),('laravel_cache_w8yjmBVBF4dN6Azn','a:1:{s:11:\"valid_until\";i:1753636138;}',1754845738),('laravel_cache_WBNqR3CDxjVW0L4i','a:1:{s:11:\"valid_until\";i:1752397056;}',1753605816),('laravel_cache_xB9OFwFbFYRoHGbc','a:1:{s:11:\"valid_until\";i:1752571445;}',1753781105),('laravel_cache_XOkdfFkkqLEqyt8M','a:1:{s:11:\"valid_until\";i:1752586173;}',1753795713),('laravel_cache_YcZxFLMj3nRakiIA','a:1:{s:11:\"valid_until\";i:1752516924;}',1753726584);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-03 13:59:59
