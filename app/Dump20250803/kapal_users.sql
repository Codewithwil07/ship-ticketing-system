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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@gmail.com','admin',NULL,'$2y$12$mXNIDl9ef0/r/44eVU00ve1niB7odpdZ6CdyPV2t3N22yAOIqSbzC',NULL,'2025-07-07 07:16:00','2025-07-07 07:16:00'),(8,'Wang','wang@gmail.com','user',NULL,'$2y$12$5J19A5RAvxDmxVbqLNceLOrnjb.XxiY8W3KSX6EpkesGK277R6IZ.',NULL,'2025-07-07 08:15:21','2025-07-07 08:15:21'),(9,'Wandy','Wandy@gmail.com','user',NULL,'$2y$12$XRMfRi1akZnKPLcPXkmUPewToPUqFj9lGFN04km58ga3OJpAb564G',NULL,'2025-07-07 09:41:22','2025-07-07 09:41:22'),(10,'zizan','zizan@gmail.com','user',NULL,'$2y$12$irWeUMI8RB/2M2.RxXG0SuosQrt0luZRYo9nGjD7agxnbWl0kYhd2',NULL,'2025-07-15 06:33:12','2025-07-15 06:33:12'),(11,'joni','joni@gmail.com','user',NULL,'$2y$12$4c1GzcWupX2yXgO/L2aonu4dMNYYPp2T2DXbC0G2f7rureAkEJTMi',NULL,'2025-07-15 06:34:17','2025-07-15 06:34:17'),(12,'jana','jana@gmail.com','user',NULL,'$2y$12$eRbHIkjpcu9mhOCr8CgX0O/5nn2F6tAekG18bU799IisR70J7ryB.',NULL,'2025-07-15 06:34:38','2025-07-15 06:34:38'),(16,'gofi','gofi@gmail.com','user',NULL,'$2y$12$8GKFoxHFZCftI.UKRmYeQuq0wkj/MtEUTvci5xSSyUy7Jz0C.4u6S',NULL,'2025-07-15 06:47:00','2025-07-15 06:47:00'),(17,'yaya','yaya@gmail.com','user',NULL,'$2y$12$gqkOUGaJ.DcDubSxhzMBD.o4ybaBdKKtav1xUqTonyOL5FlVZB1Ne',NULL,'2025-07-15 06:49:50','2025-07-15 06:49:50'),(18,'haji','haji@gmail.com','user',NULL,'$2y$12$J57BiePtPAdDCD9vP1.ceOgxhbvBszGGa7RBimIS4j51Ee1xtPwb6',NULL,'2025-07-27 09:26:12','2025-07-27 09:26:12'),(19,'ritsuki','ritsuki@gmail.com','user',NULL,'$2y$12$hcIz/fqnoGw8E.//6xvigueZpOBlU5qLuCeE4VRI1cZt7llgMZcbW',NULL,'2025-08-02 21:30:07','2025-08-02 21:30:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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
