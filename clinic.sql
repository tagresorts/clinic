/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.27-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: clinic
-- ------------------------------------------------------
-- Server version	10.5.27-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `appointment_datetime` datetime NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `appointment_type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `reason_for_visit` text DEFAULT NULL,
  `appointment_notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `modification_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`modification_history`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_patient_id_foreign` (`patient_id`),
  KEY `appointments_dentist_id_foreign` (`dentist_id`),
  CONSTRAINT `appointments_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,47,7,'2025-08-24 09:53:51',60,'Check-up','confirmed','Voluptates consequuntur ut qui nobis debitis eum.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(2,11,6,'2025-08-05 16:18:28',45,'Check-up','no_show','Earum aliquam est veritatis tempore animi officia.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(3,36,6,'2025-10-25 12:26:45',60,'Filling','cancelled','Ad porro atque sit ducimus ut.',NULL,'Id praesentium dicta culpa autem nihil recusandae.',NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(4,25,3,'2025-07-11 23:43:20',30,'Cleaning','completed','Aut natus eos iusto saepe ut quidem ut.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(5,17,6,'2025-08-10 22:56:05',60,'Extraction','cancelled','Quia omnis enim delectus eligendi impedit praesentium.',NULL,'Soluta voluptas necessitatibus deleniti maiores.',NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(6,22,3,'2025-07-18 06:02:22',15,'Extraction','no_show','Et eveniet enim tenetur optio natus.','Quos enim mollitia est ut at molestias fugit. Molestiae est id nostrum molestiae aperiam voluptates. Enim eligendi ratione repellat officiis perspiciatis. Voluptas voluptatum earum recusandae atque.',NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(7,9,7,'2025-08-06 19:11:00',45,'Cleaning','confirmed','Modi voluptatem voluptatibus officia est facilis voluptatibus minus.','Nemo et doloremque fugiat et id quod mollitia repellat. Sequi quae praesentium libero. Ut quia voluptatem qui quae. Aut delectus debitis ut in eaque. Reprehenderit veniam quam explicabo alias facere omnis dolorem modi.','Quis et iste animi explicabo accusantium.','[{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-06T10:24:49.547062Z\",\"user_id\":5}]','2025-08-06 01:59:06','2025-08-06 02:24:49'),(8,27,3,'2025-07-18 08:29:10',30,'Check-up','cancelled','Fugit modi odit necessitatibus.','Enim explicabo minus odio est velit. Deleniti voluptatem animi modi qui. Eos mollitia maiores velit voluptatem doloribus.','Rem delectus ea error esse maiores doloribus et.',NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(9,8,7,'2025-06-08 20:53:14',60,'Check-up','no_show','Reprehenderit aperiam quis iure molestias assumenda eos fugit.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(10,13,3,'2025-09-15 13:41:35',15,'Filling','confirmed','Officia doloribus nulla non odit.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(11,37,6,'2025-06-16 20:31:20',15,'Filling','no_show','Consectetur autem dignissimos quidem libero nihil distinctio quisquam.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(12,45,7,'2025-06-18 22:55:43',15,'Cleaning','completed','Corrupti enim suscipit nisi.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(13,49,3,'2025-09-24 14:17:48',45,'Check-up','scheduled','Et temporibus sint eligendi dignissimos nam architecto.',NULL,NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(14,16,6,'2025-06-02 21:09:53',45,'Consultation','cancelled','Eveniet dolor ducimus occaecati veritatis.','Officia culpa distinctio minus similique alias dolores qui. Est ullam architecto id nostrum maxime minus et. Ratione necessitatibus autem ipsa qui magni sunt repellendus.','Veritatis fugiat itaque nobis ipsa.',NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(15,41,3,'2025-09-23 05:31:37',30,'Consultation','cancelled','Accusamus fugit et ut reiciendis.','Quas ut facilis soluta ut at quaerat sed velit. Ipsa quisquam consectetur voluptatem assumenda. Distinctio quia nulla minima libero voluptatem.','Et temporibus est placeat voluptas expedita cumque deleniti.',NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(16,28,6,'2025-10-28 05:44:42',60,'Cleaning','cancelled','Voluptatem animi itaque et qui voluptas.',NULL,'Qui qui quaerat quisquam id ratione vel quam nemo.',NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(17,46,3,'2025-08-01 00:29:06',45,'Filling','no_show','Ut dolore accusantium quia et et omnis ab.','Ea rerum sit quam inventore odio. Eveniet quia occaecati recusandae hic. Blanditiis ea ullam accusantium. Sint omnis nulla est eaque voluptate tempore unde iusto.',NULL,NULL,'2025-08-06 01:59:06','2025-08-06 01:59:06'),(18,44,3,'2025-05-23 08:09:01',30,'Filling','cancelled','Vel mollitia ea sed voluptatibus.','Ut ut eaque culpa totam voluptate est dolorum. Est quo soluta non voluptatibus commodi. Quaerat placeat facere quod porro ratione.','Quo corrupti ut eos magni nihil qui.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(19,3,6,'2025-08-17 06:26:51',60,'Check-up','scheduled','Similique aut consectetur sit aut distinctio omnis voluptas.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(20,36,3,'2025-09-06 22:30:14',15,'Extraction','scheduled','Velit sed et ut culpa odio ut cum.','Eius eveniet soluta asperiores quae magnam. Ut est ex et mollitia sit accusamus adipisci. Occaecati qui odit voluptatibus quos nam dolorum consequatur. Et dolores sit hic et.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(21,10,3,'2025-10-02 04:05:52',60,'Filling','confirmed','Placeat aut modi ex sapiente.','Voluptatum officia voluptate nulla nesciunt et nihil. Perferendis ea pariatur adipisci. Velit similique inventore nemo laudantium sed est fugit et.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(22,22,3,'2025-09-21 14:00:15',45,'Filling','cancelled','Non illum minima sit minus.',NULL,'Ipsum eius et id facilis tempore.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(23,44,7,'2025-09-08 13:18:42',15,'Extraction','confirmed','Libero facilis officia nihil rem.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(24,13,3,'2025-07-15 07:48:32',45,'Consultation','cancelled','Accusantium et perspiciatis quisquam ducimus qui voluptatibus.',NULL,'Et qui quis pariatur possimus.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(25,50,7,'2025-07-11 10:26:47',60,'Cleaning','completed','Quaerat qui aut cum.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(26,47,6,'2025-08-29 09:32:37',30,'Filling','confirmed','Rerum molestias eum dolore quos dolorem consectetur.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(27,22,3,'2025-05-13 12:22:16',30,'Consultation','completed','Ut quidem voluptatem aut autem.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(28,27,6,'2025-07-06 19:59:39',30,'Consultation','no_show','Tempore explicabo quia voluptatem odit maiores.','Et et at et et voluptatem. Et quas in culpa ut veniam quas vero. Placeat et omnis facilis. Reiciendis debitis consequuntur eos.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(29,48,6,'2025-09-25 14:23:29',15,'Extraction','cancelled','Dicta vel ea dolores qui fugit ut id.','Tempora vel atque qui omnis. Enim et qui expedita voluptate sed totam qui. Nisi beatae perferendis consequatur quia labore aut eum.','Dicta eos voluptatem cupiditate est.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(30,10,6,'2025-08-19 13:13:24',60,'Consultation','cancelled','Ea quas voluptates est.',NULL,'Rerum ut est sapiente repudiandae quos sed.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(31,50,3,'2025-05-28 15:39:58',15,'Filling','no_show','Accusantium aspernatur et qui eveniet iste laudantium.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(32,30,6,'2025-09-28 17:26:55',30,'Filling','confirmed','Id vero similique cumque quod perspiciatis minus at.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(33,11,7,'2025-07-30 06:26:09',30,'Filling','no_show','Voluptate nisi nihil distinctio unde explicabo est sapiente voluptatibus.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(34,24,6,'2025-05-08 09:51:26',15,'Extraction','cancelled','Corrupti molestias occaecati voluptatem sit delectus id.','Recusandae qui id sed et aut. Unde non omnis amet accusantium molestias. Dolores ea eos eius.','Impedit libero voluptas at similique facere ea facere.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(35,33,3,'2025-09-23 07:22:22',60,'Filling','cancelled','Fugit sapiente ut excepturi mollitia.',NULL,'Ducimus voluptatem rerum deserunt.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(36,43,3,'2025-09-11 21:47:59',15,'Consultation','scheduled','Esse et non odio labore debitis at et omnis.','Quidem eaque ex reiciendis qui incidunt est. Veniam id vitae dolorem enim. Voluptatibus aut minus et non. Sequi accusamus qui temporibus nisi laborum.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(37,2,3,'2025-05-17 17:56:23',15,'Consultation','cancelled','Culpa voluptatum dolores doloremque laboriosam hic nam.',NULL,'Et sint iure et animi et molestiae dolorem eum.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(38,46,7,'2025-08-17 15:38:15',60,'Consultation','scheduled','Totam at tempore aspernatur et doloremque.','Delectus voluptatibus numquam odit. Aspernatur vero consequuntur quibusdam nostrum numquam. Nesciunt nulla qui alias eum.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(39,41,7,'2025-10-09 02:46:07',60,'Consultation','scheduled','Fuga iure ut ut.','Et quisquam quia voluptatem occaecati consequatur rerum. Rerum amet repellendus incidunt vel consequatur pariatur quis. Libero libero autem quo est et. Ut temporibus et commodi nisi error sapiente.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(40,19,7,'2025-06-25 04:40:08',15,'Check-up','completed','Nisi autem ipsum quia voluptas excepturi aut porro quia.','Officiis accusantium quae qui at reiciendis provident recusandae. Dignissimos omnis totam quidem consequatur autem ex accusantium. Animi vitae est quia error voluptas.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(41,49,3,'2025-08-03 04:39:06',15,'Check-up','completed','In occaecati mollitia est odit consequatur dolore.','Ut quidem possimus vel odit sint et suscipit. Doloribus dolores est sunt sed iure. Doloremque nostrum recusandae consequuntur aperiam eos totam.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(42,14,3,'2025-10-22 19:09:48',45,'Cleaning','cancelled','Nostrum recusandae maiores beatae id.','Sit quae at est mollitia asperiores. Soluta qui aspernatur blanditiis at voluptatem. Voluptas voluptatem quia soluta omnis voluptates. Quam quam qui quo accusamus alias.','Et blanditiis voluptas nihil enim maxime.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(43,38,3,'2025-10-04 14:51:14',15,'Consultation','cancelled','Ut id cupiditate velit cumque eligendi nisi placeat.','Autem aspernatur ut non omnis qui culpa ut. Nisi et assumenda ut ea aspernatur. Et ut amet ipsam sunt et aut sed. Ut velit voluptatibus vitae illum cum est est nihil. Eum perspiciatis consequatur rerum voluptatibus laudantium ea libero.','Fugit est enim sed.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(44,8,7,'2025-05-11 07:58:01',15,'Consultation','cancelled','Sunt laudantium hic sit eum autem vel.','Sequi qui totam inventore et error qui sit. Ipsa dolor ut aspernatur laborum. Eius distinctio sed corporis ad. Et quis doloremque alias et aut.','Beatae sit error dolorem ipsa.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(45,34,3,'2025-09-18 15:36:36',15,'Filling','cancelled','Enim optio asperiores eius nemo omnis modi non distinctio.',NULL,'Iste ab quos quae magni.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(46,49,3,'2025-09-15 00:29:50',15,'Consultation','cancelled','Dolorum deleniti quo enim voluptas quas dolor.','Consequatur delectus qui velit ex voluptatum dicta. Cumque rerum accusantium quo ut. Ullam tenetur modi optio sapiente.','Sapiente dolorum asperiores error ipsam non et natus.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(47,22,7,'2025-06-26 17:09:50',30,'Cleaning','cancelled','Et eaque dolor fugit molestiae dolorum incidunt.',NULL,'Et veritatis pariatur iste pariatur est perspiciatis nesciunt.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(48,50,7,'2025-10-13 06:10:15',15,'Consultation','scheduled','Expedita est quis ut asperiores voluptatum dolores eos.','Eum ut quia et fugit minima dolor. Tempore cupiditate aperiam quo. Sed inventore totam totam. Voluptatum rerum optio impedit perferendis explicabo modi.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(49,2,7,'2025-09-29 01:00:51',60,'Cleaning','scheduled','Excepturi quos veritatis eum ut sint.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(50,10,3,'2025-08-09 19:48:22',60,'Filling','cancelled','Ut dignissimos vitae nisi eligendi.',NULL,'Ut officiis quae sunt est.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(51,1,7,'2025-06-19 10:41:23',60,'Filling','no_show','Aut quidem id quis sunt suscipit aut aut cum.','Quisquam aut voluptates pariatur nobis magni. Sunt ut quas quo soluta incidunt. Sequi et et quia et et in hic commodi. Est eligendi quas reprehenderit quam eum eaque.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(52,23,7,'2025-08-14 17:54:50',30,'Consultation','cancelled','Qui qui tempore excepturi omnis eum ut.','Totam et consectetur est aut. Laboriosam veniam nostrum ut hic qui optio non. Quos enim dicta repellat quasi. Eos rerum inventore sit consequatur quia similique. Ratione voluptatem pariatur qui explicabo deleniti quis culpa.','Rerum eligendi distinctio soluta quisquam.',NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(53,44,7,'2025-07-22 15:06:14',15,'Cleaning','completed','Iure perferendis sunt labore ratione.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(54,35,6,'2025-10-12 16:10:35',15,'Filling','scheduled','Sit inventore maxime quas qui eum recusandae dolor.','Dolorum sapiente voluptatem explicabo iure ducimus recusandae harum. Distinctio nihil magni autem. Corrupti in quia quia eius. Natus quia animi fuga tempore excepturi autem nulla.',NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(55,43,3,'2025-10-08 20:21:11',30,'Consultation','scheduled','Sequi qui tempora quod aut veritatis.',NULL,NULL,NULL,'2025-08-06 01:59:07','2025-08-06 01:59:07'),(56,15,6,'2025-10-14 10:37:43',15,'Check-up','confirmed','Ipsum ut eveniet nobis nostrum omnis.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(57,40,3,'2025-09-03 10:06:55',15,'Extraction','scheduled','Ullam neque vel incidunt officiis aliquam accusamus perferendis.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(58,9,7,'2025-05-06 20:23:00',30,'Check-up','scheduled','Amet rerum nostrum voluptatem architecto.','Necessitatibus commodi odio est ea mollitia possimus ipsam placeat. Qui maxime quia ex consectetur. Magni optio dolor velit nihil iusto. Natus qui delectus deserunt eum ipsum.',NULL,'[{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-06T10:20:34.643769Z\",\"user_id\":5},{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-06T10:21:56.649964Z\",\"user_id\":5}]','2025-08-06 01:59:08','2025-08-06 02:21:56'),(59,5,3,'2025-06-30 03:39:04',15,'Extraction','no_show','Iste autem consequatur accusantium voluptatibus sint necessitatibus sed.','Ullam modi voluptas accusamus suscipit expedita. Tenetur necessitatibus dolores maxime laudantium vel ut sed iste. Nulla officia pariatur aut quisquam temporibus autem. Et explicabo aliquid et in est rem.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(60,37,6,'2025-06-16 04:07:42',30,'Check-up','cancelled','Voluptatem libero voluptatibus impedit sequi suscipit dolore.','Ipsam dignissimos temporibus possimus. Et laudantium ab cupiditate. Rerum adipisci enim doloremque et quam. Dignissimos impedit cum tempora mollitia.','Aspernatur id iure et doloribus omnis.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(61,14,7,'2025-06-04 07:20:47',60,'Check-up','cancelled','Qui quia et animi ut veniam expedita.',NULL,'Assumenda dolor ipsa distinctio minus sunt aspernatur sapiente.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(62,39,7,'2025-05-20 15:36:39',45,'Cleaning','cancelled','Aut in sed ut quo assumenda id.',NULL,'Est exercitationem omnis aut accusantium vero.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(63,5,7,'2025-07-14 15:29:22',30,'Filling','cancelled','Dignissimos vel vitae id nihil nam.',NULL,'Reiciendis ut ex et sapiente eum.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(64,39,7,'2025-10-13 12:28:15',15,'Consultation','confirmed','Voluptate veritatis sint quam aut.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(65,22,6,'2025-06-21 02:38:10',60,'Cleaning','completed','Laborum ipsam rem voluptas.','Odio eius dolorem dignissimos vel aspernatur facere repellendus. Omnis quam nisi non iure. Quo qui quasi aut expedita qui corrupti. Voluptatem aut consequatur veniam laboriosam praesentium.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(66,49,7,'2025-09-12 15:20:46',15,'Consultation','cancelled','Suscipit aut velit sapiente sit voluptatibus dolorum.','Similique quaerat cupiditate pariatur officiis. Molestiae quaerat necessitatibus earum adipisci ut et. Numquam quo autem soluta quia odit animi quod.','Et ducimus aliquid id autem maiores consequatur.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(67,3,6,'2025-08-16 00:05:07',30,'Consultation','cancelled','Sed et laborum autem.','Voluptatibus quos saepe eligendi. Facere quidem id quis deserunt quisquam qui cum. Rerum aut qui et eaque.','Fugit quaerat exercitationem cumque placeat omnis reprehenderit.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(68,2,6,'2025-09-26 19:10:10',45,'Consultation','scheduled','Nemo sed placeat quisquam ipsa veniam.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(69,6,3,'2025-09-05 03:57:38',15,'Cleaning','cancelled','Deleniti aperiam id voluptas ex sit.','Sit sed aut ut molestiae et. Et excepturi nesciunt sapiente eum omnis nobis neque ut. Harum qui itaque explicabo. Eos magni nobis excepturi molestiae.','Est ipsam dolorem tempore a.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(70,31,7,'2025-06-09 17:59:49',15,'Cleaning','no_show','Temporibus incidunt ab quo occaecati.','Aut rerum qui illo autem est et. Veniam nihil distinctio doloribus ex qui. Praesentium at voluptatem et et tempore. Sint nesciunt tempore repellat qui enim voluptas beatae.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(71,38,3,'2025-09-03 17:44:34',60,'Check-up','confirmed','Optio vel cumque et nisi.','A enim ab vitae quis vel eaque sapiente nisi. Et aut commodi cumque. Adipisci sint modi cum suscipit.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(72,25,6,'2025-08-10 07:26:55',30,'Cleaning','confirmed','Sint ad consequuntur quo minima cumque.','Voluptatem ullam deleniti qui consequuntur totam vitae maxime. Labore sit ut quas laudantium aut quia. Quo et quo voluptates illo eligendi reiciendis.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(73,15,7,'2025-07-14 00:53:02',60,'Cleaning','no_show','Quia accusamus et ipsa culpa.','Exercitationem nesciunt vitae quibusdam aperiam. Pariatur nam doloribus aut in. Est consequatur id voluptatem.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(74,50,7,'2025-05-14 00:46:11',45,'Cleaning','cancelled','Quia quidem cumque eveniet iste doloremque.','Iste nihil doloribus voluptates ea nisi. Explicabo temporibus delectus voluptate quo.','In voluptatem magnam vel aliquam iusto velit voluptate quo.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(75,1,7,'2025-10-11 09:38:38',45,'Cleaning','confirmed','Rerum velit voluptas qui.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(76,7,3,'2025-09-07 19:55:18',30,'Check-up','confirmed','Omnis pariatur asperiores quia facilis quas.','Explicabo laboriosam dolorem dolores qui totam. Qui quos id assumenda. Ea molestias quaerat sit esse.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(77,8,7,'2025-07-21 06:57:20',45,'Consultation','no_show','Quo minus voluptas qui id.','Ut voluptatem sit vel tenetur qui minus voluptates. Ut ex quo qui et. Suscipit libero sed dolorem.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(78,28,6,'2025-06-30 06:21:29',45,'Cleaning','completed','Consectetur cupiditate ut quae modi aliquid.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(79,5,7,'2025-05-11 07:42:04',45,'Extraction','no_show','Beatae nemo praesentium assumenda omnis est fugit in voluptatem.','Est sunt dolores qui vero. Qui doloribus pariatur facere dolor. Dolores reiciendis distinctio sit qui cupiditate. Qui qui quod atque laudantium sed porro voluptate fugiat.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(80,23,7,'2025-06-17 01:39:32',30,'Cleaning','completed','Nam fugiat ut reiciendis eligendi ipsum ea pariatur.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(81,47,3,'2025-09-08 05:17:04',15,'Extraction','confirmed','Accusantium tempore reprehenderit sint qui dolorem.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(82,2,3,'2025-10-04 02:18:19',30,'Extraction','confirmed','Vero qui maiores iste.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(83,44,7,'2025-05-26 20:15:14',30,'Extraction','completed','Aliquam reiciendis et sint sunt nulla natus voluptatibus.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(84,46,6,'2025-06-10 10:51:49',30,'Cleaning','completed','Suscipit explicabo est cupiditate in maxime.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(85,21,6,'2025-08-09 04:32:23',45,'Cleaning','scheduled','Molestias necessitatibus ea magni perferendis quis possimus.','Non natus et corporis nobis id est ipsam. Aperiam consequatur possimus nostrum consequatur. Accusamus sequi dolores deleniti pariatur nulla est commodi.',NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(86,48,7,'2025-07-15 22:50:25',15,'Cleaning','cancelled','Qui ad enim quae aut odio.','Corrupti blanditiis adipisci dolor nulla velit veritatis et. Exercitationem velit quo reiciendis assumenda et.','Aut ad incidunt necessitatibus ut.',NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(87,47,6,'2025-08-19 06:31:54',30,'Filling','confirmed','Voluptatibus deleniti autem impedit corrupti eligendi dicta modi culpa.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(88,38,6,'2025-06-22 11:10:45',30,'Extraction','no_show','Enim impedit recusandae asperiores repudiandae sit.',NULL,NULL,NULL,'2025-08-06 01:59:08','2025-08-06 01:59:08'),(89,45,3,'2025-05-07 04:56:08',60,'Cleaning','completed','Consectetur quam commodi in eveniet expedita.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(90,29,3,'2025-08-10 22:16:00',60,'Filling','scheduled','Rem molestiae aut ut at voluptas non.','Qui et quasi similique consequatur excepturi dolorem. Aut doloremque aliquid voluptates doloremque molestiae eligendi minima. Vitae consequuntur cumque aperiam laudantium error pariatur. Quidem quam possimus explicabo non voluptas et.',NULL,'[{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-06T10:28:59.680253Z\",\"user_id\":5}]','2025-08-06 01:59:09','2025-08-06 10:28:59'),(91,1,7,'2025-08-20 15:37:45',15,'Filling','scheduled','Et veritatis nesciunt voluptatem nostrum qui omnis libero.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(92,35,7,'2025-06-03 19:52:45',30,'Extraction','cancelled','Vel facere in eaque qui est.','Tenetur sint eius iure quam voluptates dolor. Voluptatem ipsa similique aut eaque placeat sint et. Neque necessitatibus aut repellat aperiam amet assumenda quisquam.','Reprehenderit aut doloribus ut nam molestiae reprehenderit omnis est.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(93,38,3,'2025-07-15 18:28:02',30,'Check-up','cancelled','Ullam labore dolor dolores porro velit corporis.','Voluptatibus sequi et itaque quidem. Dolor praesentium aut doloremque dolorem facere libero. Et dolore aperiam explicabo hic fuga non et provident.','Consequuntur vitae iure aut laboriosam.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(94,25,6,'2025-05-19 04:20:06',30,'Extraction','cancelled','Enim ipsa quo eos quod maiores voluptatem.',NULL,'Itaque blanditiis aut ut explicabo doloremque.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(95,14,7,'2025-09-23 10:12:57',15,'Extraction','scheduled','Molestiae quasi non voluptatum omnis qui.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(96,9,3,'2025-10-11 00:52:51',45,'Consultation','cancelled','Quia est consequatur dolores earum ut.',NULL,'Nisi aut voluptas voluptatum numquam tempora consequatur.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(97,3,3,'2025-07-17 20:09:37',45,'Consultation','completed','Reprehenderit autem ipsa dicta praesentium ex enim.','Recusandae unde ex odio commodi. In dolores mollitia recusandae iste et. Id nulla adipisci illo molestiae.',NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(98,32,3,'2025-05-18 14:48:03',60,'Filling','no_show','Quibusdam voluptas occaecati culpa illum sed quas nulla.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(99,42,6,'2025-10-24 23:45:41',45,'Cleaning','confirmed','Aut libero quia voluptas.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(100,24,6,'2025-10-29 11:36:18',30,'Consultation','cancelled','Et rem minima distinctio velit.',NULL,'Vero qui et tenetur pariatur nulla illo.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(101,24,6,'2025-07-12 04:07:07',15,'Extraction','cancelled','Dolor facilis voluptate voluptatum ducimus.','Voluptas quam asperiores autem odio facilis. Voluptas expedita voluptas illo suscipit. Esse doloremque sed dolores totam voluptates sint et. Porro repellat vel commodi et quisquam.','Ab voluptatem rerum molestiae repudiandae odio est.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(102,28,7,'2025-05-27 16:04:21',60,'Check-up','no_show','Autem delectus nesciunt est quasi aut autem.','Et ratione modi et eum dolor ipsa. Rerum minus mollitia quisquam vitae fugit. Deserunt provident id assumenda inventore.',NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(103,47,3,'2025-10-18 02:06:51',30,'Check-up','scheduled','Porro quae ut dolor eum molestiae omnis perspiciatis.','Omnis autem officiis distinctio ut officia. Fugit vitae atque beatae quaerat vel. Nisi qui expedita vitae autem.',NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(104,7,6,'2025-07-14 10:59:10',60,'Cleaning','cancelled','Ut tempore consequuntur quas.',NULL,'Dolorem vel magnam similique.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(105,15,3,'2025-05-11 10:28:53',15,'Consultation','cancelled','Molestiae at nam ut molestias non.','Ex et minima impedit et repudiandae. Facere neque sed eum rerum odio nihil deleniti. Similique et ipsam facere optio cumque. At pariatur non harum odit.','Sunt ullam sed laboriosam autem.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(106,23,7,'2025-06-19 07:21:50',15,'Extraction','no_show','Vel sed laborum qui quaerat voluptatem mollitia.','Et alias ut consequatur dolorem dolor labore. Et consectetur cum blanditiis fuga modi expedita. Et saepe qui atque. Eum voluptas voluptas quae culpa voluptates.',NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(107,29,3,'2025-09-15 10:28:24',45,'Cleaning','scheduled','Ratione non hic sapiente enim nobis ipsam sunt.','Molestiae velit rerum alias corporis occaecati. Consequatur atque nesciunt sed facilis facilis qui. Iure omnis non est ex.',NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(108,49,6,'2025-09-27 16:34:14',30,'Check-up','cancelled','A qui impedit ullam ut.','Quidem sunt et dignissimos praesentium totam asperiores. Autem et quis omnis ut. Illum officia iusto deleniti rerum dignissimos possimus.','Ut blanditiis nihil vel a veritatis odit numquam quos.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(109,8,3,'2025-09-15 08:46:35',45,'Consultation','confirmed','Sint esse dolorum sequi.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(110,50,3,'2025-06-03 13:55:11',45,'Extraction','cancelled','Optio sit perspiciatis dolores est ut.','Alias eligendi et corrupti optio enim. Accusamus qui quisquam suscipit.','Magnam sit quia natus officiis nobis magni occaecati.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(111,21,7,'2025-09-21 05:12:09',60,'Check-up','cancelled','Libero officiis odit numquam dolor eaque molestias.',NULL,'Delectus tenetur odit est sequi magni.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(112,50,6,'2025-08-18 19:25:15',15,'Consultation','scheduled','Doloremque corporis ea esse impedit ut dicta.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(113,22,6,'2025-09-14 04:51:10',30,'Check-up','cancelled','Qui placeat quo est recusandae.',NULL,'Harum modi quaerat delectus dolores mollitia dignissimos.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(114,33,3,'2025-06-15 06:35:20',30,'Check-up','cancelled','Exercitationem qui quibusdam aut quis non adipisci repudiandae.',NULL,'Doloremque deleniti eum doloremque.',NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(115,32,7,'2025-05-11 08:53:04',45,'Consultation','no_show','Voluptate doloremque sapiente sed autem expedita alias accusantium.','Occaecati voluptatum commodi facere accusamus. Voluptatem placeat praesentium consequuntur mollitia amet. Consequatur eveniet fugit blanditiis facilis alias exercitationem. Nihil dolorem sed eius.',NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(116,30,6,'2025-10-18 09:01:55',30,'Consultation','confirmed','Corporis repellendus autem sapiente voluptatum.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(117,11,3,'2025-05-13 20:54:22',45,'Cleaning','no_show','Minus provident aut quod tenetur possimus.','Eveniet sapiente excepturi at voluptas molestiae. Perspiciatis nemo ullam repellendus velit. Veritatis quia et velit quibusdam cupiditate consequatur.',NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(118,18,3,'2025-05-17 07:41:40',45,'Extraction','completed','Quidem tempora temporibus sit amet iusto odio.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(119,16,7,'2025-05-18 04:59:57',45,'Extraction','completed','Fugiat accusamus tenetur dolore voluptatem.',NULL,NULL,NULL,'2025-08-06 01:59:09','2025-08-06 01:59:09'),(120,36,3,'2025-09-13 05:54:51',45,'Extraction','scheduled','Est voluptatum ad vel dignissimos.','Mollitia blanditiis impedit laboriosam voluptates vel consequatur. Et voluptatem ipsam quos veritatis maiores officia. Sequi iusto repellendus dolorem voluptas ea et.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(121,2,3,'2025-08-13 06:35:03',45,'Cleaning','cancelled','Vel possimus facilis eaque iste repellat velit.',NULL,'Possimus dolorem quis nesciunt nisi quo explicabo.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(122,45,6,'2025-06-17 17:32:33',45,'Extraction','cancelled','Ex sint ut eveniet commodi reiciendis et et quaerat.',NULL,'Qui culpa eum qui qui quidem modi eos.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(123,17,6,'2025-08-01 07:18:42',15,'Check-up','cancelled','Voluptas et adipisci eos officia aliquid.',NULL,'Ea blanditiis ut est explicabo.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(124,34,3,'2025-10-07 05:32:00',15,'Cleaning','cancelled','Laboriosam doloribus modi impedit optio.','Ad assumenda iure maxime repellat consequatur. Quo placeat neque voluptatum amet. Velit et autem asperiores magni corporis sapiente. Similique quod adipisci cupiditate. Aut sunt aperiam aliquam nostrum optio.','Porro accusantium vel veniam et blanditiis et explicabo.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(125,7,6,'2025-07-07 04:38:58',45,'Consultation','cancelled','Sunt ad recusandae fugiat et unde aut.','Sint ut aliquam molestiae tempore ut aperiam eos. Aut voluptatibus necessitatibus et ut. Atque nihil fuga et eaque cum reprehenderit aliquid quia.','Eligendi aperiam repellendus dicta qui eos et.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(126,20,3,'2025-06-08 18:41:15',45,'Cleaning','no_show','Inventore et aliquid quia et cum sit sequi.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(127,1,6,'2025-05-15 06:48:50',15,'Cleaning','cancelled','Officia sed voluptas sunt non temporibus minus cumque.','Beatae officiis earum consectetur nesciunt. Voluptas et tenetur ea nobis. Commodi aperiam velit dolorem quia iusto voluptatem.','Voluptate quia provident nulla eum commodi et exercitationem.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(128,50,3,'2025-05-08 20:09:08',45,'Extraction','cancelled','Asperiores aut magnam non reiciendis.','Consequatur labore laudantium et vitae. Et qui odio nesciunt soluta eveniet ratione nobis.','Vel doloribus consequatur recusandae consequuntur excepturi.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(129,2,3,'2025-06-05 02:39:38',60,'Extraction','no_show','Velit quisquam perferendis qui.','Assumenda harum earum beatae nam quia harum. In blanditiis atque quisquam. Sit in et consequatur et asperiores minus. Ex sunt est non libero voluptatem. Ex voluptas eligendi quos et eos est voluptatem.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(130,47,3,'2025-10-05 19:31:50',15,'Cleaning','scheduled','Autem omnis quas et vel.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(131,25,6,'2025-10-23 22:17:35',30,'Consultation','scheduled','Dolorem consequatur voluptates exercitationem vitae dolores sed sit.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(132,12,6,'2025-06-24 15:34:12',30,'Filling','completed','Impedit non modi ad quam enim.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(133,41,3,'2025-05-10 18:43:36',45,'Extraction','completed','Dolor et ex neque tempore sit aliquam voluptate.','Porro autem consequatur beatae. Cumque sed ratione repellendus consequuntur id error magnam. Cum vel occaecati pariatur rerum.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(134,11,7,'2025-09-30 02:23:46',30,'Cleaning','cancelled','Omnis non cumque sit est distinctio.',NULL,'Similique atque enim voluptatem vel.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(135,42,7,'2025-11-06 07:36:22',45,'Check-up','cancelled','Quibusdam ut facilis et eum est.','Et qui cupiditate quo qui saepe repudiandae rerum. Sit eligendi tempore sapiente fugiat earum. Unde dolore tempora id sit voluptas. Ducimus aut dolore qui dolore ab omnis veritatis.','Nihil et fugiat assumenda rerum.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(136,34,7,'2025-06-05 13:47:11',15,'Extraction','completed','Voluptas consequatur vitae in nemo praesentium quos.','Placeat ab voluptatem a exercitationem eligendi eum. Assumenda aut dolore culpa est consequatur. Eos accusamus quia aut neque est. Dolorem quaerat velit debitis vel sapiente est dicta rem.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(137,43,3,'2025-10-24 11:02:30',15,'Extraction','scheduled','Nobis nihil dolore quo architecto qui.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(138,10,3,'2025-05-29 03:58:58',45,'Check-up','cancelled','Itaque eum sint dolore asperiores excepturi.','Iusto nesciunt illum numquam adipisci autem in id. Aliquam doloribus repellat ut impedit enim sapiente eos. Fuga consequatur placeat reiciendis quia totam asperiores voluptatum. Nihil repudiandae omnis exercitationem.','Est et qui asperiores omnis officia.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(139,34,6,'2025-05-08 15:14:09',15,'Cleaning','completed','Exercitationem saepe omnis quod dolores.','Et quia ut id sunt praesentium. Dolores ut quo minus ipsum modi corrupti. Voluptatem ab nobis autem commodi accusamus quod.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(140,12,6,'2025-10-09 07:33:22',45,'Filling','scheduled','Fugit nam labore qui optio autem vero rerum.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(141,5,6,'2025-10-22 21:10:12',15,'Cleaning','confirmed','In et nihil quis sint aperiam id aut.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(142,24,3,'2025-05-31 07:06:28',45,'Filling','no_show','Cumque et fugiat sit autem et voluptatem.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(143,25,3,'2025-10-13 13:25:54',30,'Cleaning','confirmed','Quibusdam ipsum qui suscipit animi.','Incidunt eos eum soluta fuga occaecati accusamus. Et impedit aliquam optio minima. Velit molestias tenetur quae aut.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(144,39,7,'2025-06-01 01:02:48',30,'Extraction','no_show','Totam eveniet voluptatem quasi.','Quis omnis laboriosam non quo. Aut blanditiis alias est ea et. Necessitatibus nostrum et vitae enim itaque ut. Dignissimos omnis animi quis ad. Magnam beatae sit excepturi et.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(145,12,6,'2025-06-12 23:45:35',45,'Consultation','no_show','Dolor sunt non enim aut consequuntur nesciunt.','Omnis at corrupti saepe dolorum voluptatibus nemo occaecati. Ut quam consequatur voluptate nemo velit earum. Debitis ut dolores facilis temporibus earum fugiat aliquam reprehenderit. Labore est tempora laborum nobis. Magnam molestias ullam sint est doloremque ipsum.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(146,11,3,'2025-10-11 14:51:39',30,'Check-up','confirmed','Aut quo ut voluptas excepturi.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(147,40,7,'2025-05-19 09:45:59',15,'Cleaning','no_show','Aut et quis facilis qui qui dolorem.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(148,25,7,'2025-06-19 18:09:08',15,'Extraction','cancelled','Harum dicta aut et quia molestias.',NULL,'Facilis pariatur numquam quasi voluptatem iure.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(149,17,6,'2025-09-26 07:06:37',60,'Extraction','scheduled','Molestiae qui ex qui occaecati harum odio et.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(150,31,3,'2025-06-19 06:39:11',30,'Filling','no_show','Optio et saepe natus.','Dolorum totam sunt culpa a itaque. Consequatur ut aut totam quod est sit eaque. Voluptate aut occaecati quia dolor nulla itaque ut.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(151,24,7,'2025-07-29 14:18:47',30,'Filling','no_show','Numquam animi dicta corporis quibusdam.','Fugiat laboriosam error doloremque. Excepturi aut eius dolorem accusamus.',NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(152,11,7,'2025-09-15 04:42:31',60,'Filling','cancelled','Velit quos consequuntur ut.','Incidunt temporibus vel quis ad. Fuga facilis illo nostrum quae modi. Animi numquam quia id. Voluptas laudantium qui officiis cum et deleniti culpa. Suscipit eum voluptate molestias ut nostrum nihil.','Alias vel numquam atque vitae iusto consequatur cum.',NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(153,5,3,'2025-09-14 21:36:26',60,'Check-up','scheduled','Aperiam ea voluptatum doloremque nemo.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(154,27,6,'2025-06-28 23:25:41',45,'Check-up','completed','Ab assumenda exercitationem quis aperiam qui et qui.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(155,13,7,'2025-10-29 11:03:06',45,'Extraction','confirmed','Nam impedit quo quia vel.',NULL,NULL,NULL,'2025-08-06 01:59:10','2025-08-06 01:59:10'),(156,26,6,'2025-09-26 05:28:09',60,'Filling','confirmed','Vero non et officia porro eum.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(157,26,7,'2025-10-26 10:57:27',60,'Check-up','confirmed','Consectetur aut repellendus explicabo explicabo sed beatae dolores.','Deserunt sunt et et voluptatem dolores beatae ratione. Eligendi voluptatum cum asperiores dolorem maiores sequi. Quisquam praesentium sed quasi fugit. Ipsum maxime modi suscipit asperiores ut molestiae.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(158,35,7,'2025-08-23 07:20:47',30,'Extraction','cancelled','Nesciunt ea molestias rerum maiores fuga.','Ipsam corporis ullam ab optio blanditiis. Voluptatem eius nesciunt pariatur et nihil cupiditate. Id commodi beatae aliquid hic illum.','Commodi omnis totam rerum cumque blanditiis.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(159,11,6,'2025-10-25 02:47:06',45,'Consultation','scheduled','Voluptatem delectus odit eos laboriosam et consequatur.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(160,46,3,'2025-07-20 21:17:00',60,'Check-up','no_show','Molestias natus vel quidem praesentium cumque.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(161,40,7,'2025-05-21 12:02:10',30,'Consultation','no_show','Quos et fugit et in.','Aspernatur nam aut omnis blanditiis. Soluta laborum aliquam quis ut inventore est placeat. Est laudantium saepe aliquid illum ipsa non.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(162,18,3,'2025-09-03 02:22:55',15,'Filling','confirmed','Ea veniam consequuntur qui.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(163,25,3,'2025-07-05 10:11:22',15,'Consultation','completed','Ut magnam sed inventore eum qui voluptatem minima.','Facilis est mollitia doloremque quis. Tempora rem aut voluptates delectus. Vel eaque sunt qui dolorem doloremque et nihil nam. Quo tempora repellendus quas qui rerum minima.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(164,40,3,'2025-09-16 03:54:20',45,'Filling','scheduled','Exercitationem alias soluta deleniti.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(165,32,7,'2025-08-16 06:51:56',15,'Consultation','scheduled','Quo vel necessitatibus qui quisquam tenetur possimus necessitatibus.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(166,5,7,'2025-08-15 00:20:02',30,'Check-up','confirmed','Consequatur magni illo cumque aliquid libero natus dolores.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(167,46,3,'2025-08-08 12:09:56',60,'Consultation','confirmed','Cumque est suscipit repellat et aspernatur a quidem quasi.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(168,29,6,'2025-07-07 18:10:24',60,'Cleaning','completed','Laudantium id ea sed.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(169,4,3,'2025-09-01 11:43:42',60,'Extraction','scheduled','Laborum qui ipsum sunt atque soluta dolorem.','Labore rerum et sapiente eum excepturi. Ullam ut reprehenderit voluptate quisquam voluptate tempore.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(170,49,3,'2025-10-26 15:05:11',45,'Consultation','cancelled','Sed sint nisi officiis neque expedita.',NULL,'Porro et voluptas voluptate vero aut dolore et.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(171,20,6,'2025-08-30 09:43:13',15,'Filling','confirmed','Sint doloribus ullam sed in fuga a.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(172,39,7,'2025-05-08 17:01:45',30,'Consultation','completed','A vel unde sapiente quo expedita.','Alias fuga enim ipsa dolorem rem. Eveniet vero totam illum voluptatem sequi sed. Qui quis et qui illo. Voluptatem quae voluptatem qui quo.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(173,39,6,'2025-11-04 14:20:36',15,'Check-up','cancelled','Autem omnis sapiente itaque qui.',NULL,'Itaque sed voluptatem eos aliquam.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(174,41,6,'2025-08-20 20:59:55',15,'Extraction','scheduled','Unde rerum omnis corporis fugiat animi fugiat suscipit temporibus.','Eum quam molestias optio aut dolor temporibus reprehenderit. Itaque qui eius doloremque et nemo provident totam. Non labore eius quaerat nostrum nihil non fugiat ut. Est sapiente illo possimus.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(175,1,3,'2025-07-03 18:56:38',45,'Check-up','cancelled','Fuga tenetur nihil corrupti rem autem rerum earum.','Asperiores odit nisi debitis et. Voluptatum natus veniam enim. Officiis suscipit quo perspiciatis nihil aut excepturi impedit. Ut dicta repellat tenetur dolores quod officia placeat.','Praesentium nostrum iusto dolorem dolores sint itaque minima.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(176,18,6,'2025-06-14 20:57:25',60,'Extraction','cancelled','Natus reprehenderit quos necessitatibus ut quisquam.',NULL,'Labore deleniti voluptatem autem et saepe.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(177,35,7,'2025-05-28 05:30:35',30,'Consultation','cancelled','Doloremque nam voluptatibus explicabo omnis.',NULL,'Totam ad quidem qui beatae voluptas consectetur.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(178,15,3,'2025-07-15 10:34:24',45,'Cleaning','no_show','Aut rem illo expedita aut corrupti.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(179,7,7,'2025-10-09 10:11:21',15,'Check-up','scheduled','Neque aut labore sed explicabo cupiditate vel qui.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(180,5,7,'2025-09-16 22:40:00',15,'Cleaning','cancelled','Architecto veritatis alias quam vitae rerum rem tempore quia.','Cupiditate nesciunt eveniet mollitia laudantium dolores non pariatur. Qui fugit enim aut est expedita. Quos id aut non laborum iste neque.','Aut quasi consequatur iure optio quod aliquid.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(181,28,6,'2025-09-19 21:09:11',15,'Check-up','scheduled','Aliquid quae incidunt recusandae quisquam.','Ratione repellat et et et veniam ut accusantium. Perspiciatis incidunt in iure repudiandae. Quis voluptas exercitationem blanditiis laborum odio debitis. Cum possimus laboriosam voluptatem fugit aliquam et esse.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(182,34,7,'2025-10-26 05:35:00',60,'Extraction','scheduled','Delectus dicta rerum nisi quidem officiis.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(183,41,7,'2025-09-07 21:26:33',45,'Check-up','confirmed','Ad in cupiditate illo nihil optio.','Sint ipsam recusandae impedit quam dolorum veniam hic. Pariatur at libero aliquid et odit ex. Iste sit rerum vel ipsa unde. Harum culpa repellendus eaque.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(184,28,7,'2025-05-31 11:30:37',60,'Check-up','completed','Expedita a iure dicta quae est.','Voluptatum dolore necessitatibus explicabo aliquid neque libero. Ducimus facere ad dignissimos neque provident.',NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(185,47,7,'2025-07-05 04:42:33',15,'Consultation','cancelled','Voluptate minus voluptatem dolores id perferendis corrupti ipsam.','Non impedit magni reiciendis ipsa cum et laudantium. Aliquam incidunt excepturi molestias amet occaecati suscipit. Quidem reprehenderit recusandae assumenda quia. Laborum est assumenda et et totam.','Sunt rerum sapiente similique voluptas neque a.',NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(186,35,7,'2025-10-10 22:10:42',45,'Check-up','confirmed','Fugit facere autem rerum omnis.',NULL,NULL,NULL,'2025-08-06 01:59:11','2025-08-06 01:59:11'),(187,2,7,'2025-07-23 00:26:58',45,'Check-up','completed','Quia iste eum quo quo itaque.','Temporibus harum quo et ut culpa cum quo ut. Nesciunt impedit est dolor sit ratione facere. Et cumque ullam at nobis. Nam cupiditate error in quisquam eum reiciendis sapiente.',NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(188,4,3,'2025-10-21 12:11:05',15,'Consultation','cancelled','Rerum dignissimos iusto qui explicabo nisi ut.',NULL,'Error omnis aut laborum repellendus earum quis velit.',NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(189,13,7,'2025-09-03 02:29:50',60,'Filling','cancelled','Nihil ut quaerat rerum earum atque ut quia.',NULL,'Qui voluptatem sed ipsam iusto doloribus tempora molestiae.',NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(190,16,3,'2025-06-23 00:23:33',45,'Filling','completed','Laborum facere voluptatibus vero vero consequatur.','Repellat voluptatem rerum voluptatem libero. Tenetur aliquam soluta aut sit. Facere numquam recusandae incidunt qui ratione doloremque. Omnis corrupti molestiae cupiditate praesentium tenetur.',NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(191,34,7,'2025-10-18 04:54:30',15,'Check-up','scheduled','Dolore rerum omnis voluptatem dolores amet incidunt.',NULL,NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(192,48,6,'2025-06-07 14:32:38',15,'Extraction','cancelled','Quos atque voluptatem aut non voluptatum atque.','Rem numquam perferendis vero laudantium culpa quia reprehenderit libero. Ut dolorem dolorum modi quis cumque explicabo. Eum officiis et magni rerum molestias consequatur. Quo distinctio quidem quibusdam iure iusto deserunt voluptate labore.','Asperiores qui id ut iste.',NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(193,34,6,'2025-08-03 13:02:09',60,'Cleaning','cancelled','Et dolorem quod architecto enim eveniet.',NULL,'Ad fugit omnis repudiandae non similique.',NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(194,16,7,'2025-09-15 15:40:05',15,'Extraction','confirmed','Neque provident voluptates eaque.',NULL,NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(195,18,6,'2025-10-15 12:03:02',15,'Consultation','scheduled','In et qui et sit nulla enim.','Omnis sunt nihil non pariatur cumque. Atque est aut ut ut ratione eveniet. Eveniet fugit itaque dolorum odio assumenda deleniti veniam. Et non sunt iure aut veritatis.',NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(196,34,6,'2025-08-09 17:56:41',60,'Filling','scheduled','Officiis mollitia consequuntur modi impedit animi quis omnis neque.',NULL,NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(197,21,6,'2025-10-19 10:32:55',60,'Check-up','scheduled','Et ducimus quo doloremque molestias culpa itaque.',NULL,NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(198,42,7,'2025-08-19 19:46:47',45,'Extraction','scheduled','Adipisci incidunt tempora voluptatem ducimus quibusdam ad accusantium.','Cupiditate occaecati ab atque est quaerat aliquid. Necessitatibus est nulla quod optio illo veritatis amet. Neque sapiente eaque distinctio omnis est sed consequuntur. Molestias suscipit qui quas omnis magni vero nostrum.',NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(199,1,7,'2025-06-18 20:08:09',15,'Consultation','cancelled','Magni ipsam veniam magni quidem qui.',NULL,'Ea et sapiente enim quia.',NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12'),(200,31,3,'2025-10-08 13:05:19',60,'Consultation','scheduled','Nisi quia animi perferendis qui.',NULL,NULL,NULL,'2025-08-06 01:59:12','2025-08-06 01:59:12');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dental_charts`
--

DROP TABLE IF EXISTS `dental_charts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dental_charts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `chart_date` date NOT NULL,
  `chart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`chart_data`)),
  `notes` text DEFAULT NULL,
  `updated_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dental_charts_patient_id_foreign` (`patient_id`),
  KEY `dental_charts_updated_by_foreign` (`updated_by`),
  CONSTRAINT `dental_charts_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `dental_charts_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dental_charts`
--

LOCK TABLES `dental_charts` WRITE;
/*!40000 ALTER TABLE `dental_charts` DISABLE KEYS */;
/*!40000 ALTER TABLE `dental_charts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `recipient_type` varchar(255) DEFAULT NULL,
  `recipient_emails` text DEFAULT NULL,
  `recipient_roles` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_templates_type_unique` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
INSERT INTO `email_templates` VALUES (1,'Password Reset','Reset Your Password','<p>Hello {{user_name}},</p><p>You are receiving this email because we received a password reset request for your account.</p><p><a href=\"{{reset_link}}\">Reset Password</a></p><p>If you did not request a password reset, no further action is required.</p>','password_reset',NULL,NULL,NULL,'2025-08-09 23:04:45','2025-08-09 23:04:45'),(2,'Inventory Stock Digest','Inventory Digest: {{low_count}} low, {{expiring_count}} expiring','<h2>Inventory Alerts</h2><div>{{low_stock_table}}</div><div style=\"margin-top:12px;\">{{expiring_stock_table}}</div><p style=\"margin-top:12px;\">Open Inventory: <a href=\"{{inventory_url}}\">Inventory</a></p>','stock_digest',NULL,NULL,NULL,'2025-08-09 23:04:45','2025-08-09 23:04:45'),(3,'Inventory Expiring Items','Expiring Items: {{expiring_count}} item(s) nearing expiry','<h2>Expiring Soon</h2><div>{{expiring_stock_table}}</div><p style=\"margin-top:12px;\">Open Inventory: <a href=\"{{inventory_url}}\">Inventory</a></p>','stock_expiring',NULL,NULL,NULL,'2025-08-09 23:07:38','2025-08-09 23:07:38');
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_items`
--

DROP TABLE IF EXISTS `inventory_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) DEFAULT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `unit_of_measure` varchar(255) DEFAULT NULL,
  `quantity_in_stock` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `has_expiry` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `low_stock_alert_sent` tinyint(1) NOT NULL DEFAULT 0,
  `low_stock_alert_sent_at` timestamp NULL DEFAULT NULL,
  `expiry_alert_sent` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_alert_sent_at` timestamp NULL DEFAULT NULL,
  `unit_price` decimal(8,2) DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `last_restock_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_items_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `inventory_items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_items`
--

LOCK TABLES `inventory_items` WRITE;
/*!40000 ALTER TABLE `inventory_items` DISABLE KEYS */;
INSERT INTO `inventory_items` VALUES (1,'Disposable Gloves (M)','GLV-M-100','SafeTouch',NULL,NULL,NULL,NULL,50,50,5.50,0,NULL,0,NULL,0,NULL,NULL,1,NULL,'2025-08-09 21:50:18','2025-08-09 22:26:39'),(2,'Surgical Mask','MSK-50','AirMed',NULL,NULL,NULL,NULL,500,200,0.20,0,NULL,0,NULL,0,NULL,NULL,1,NULL,'2025-08-09 21:50:18','2025-08-09 21:50:18'),(3,'Fluoride Varnish','FLV-20','SmilePro',NULL,NULL,NULL,NULL,20,20,12.00,1,'2025-08-10',0,NULL,0,NULL,NULL,1,NULL,'2025-08-09 21:50:18','2025-08-09 22:03:39');
/*!40000 ALTER TABLE `inventory_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  `treatment_plan_id` bigint(20) unsigned DEFAULT NULL,
  `total_amount` decimal(8,2) NOT NULL,
  `outstanding_balance` decimal(8,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_patient_id_foreign` (`patient_id`),
  KEY `invoices_appointment_id_foreign` (`appointment_id`),
  KEY `invoices_treatment_plan_id_foreign` (`treatment_plan_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `invoices_treatment_plan_id_foreign` FOREIGN KEY (`treatment_plan_id`) REFERENCES `treatment_plans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_07_30_000000_create_procedures_table',1),(2,'2025_07_31_000000_create_all_tables',1),(3,'2025_08_01_084545_create_personal_access_tokens_table',1),(4,'2025_08_03_043021_add_appointment_id_to_treatment_records_table',1),(5,'2025_08_03_043133_add_modification_history_to_appointments_table',1),(6,'2025_08_03_115823_create_permission_tables',1),(7,'2025_08_05_073718_create_user_dashboard_preferences_table',1),(8,'2025_08_10_120000_create_user_table_preferences_table',2),(9,'2025_08_10_130000_update_suppliers_inventory_and_stock_po_tables',3),(10,'2025_08_10_131000_mutate_supplier_inventory_legacy_columns_nullable',4),(11,'2025_08_10_132000_create_smtp_configs_table',5),(12,'2025_08_10_062829_create_settings_table',6),(13,'2025_08_10_063854_create_email_templates_table',7),(14,'2025_08_10_140000_add_recipients_to_email_templates',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',8),(1,'App\\Models\\User',9),(2,'App\\Models\\User',3),(2,'App\\Models\\User',6),(2,'App\\Models\\User',7),(3,'App\\Models\\User',1),(3,'App\\Models\\User',2),(3,'App\\Models\\User',5),(4,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('admin@dentalclinic.com','$2y$12$RUPB/a3AilfczbnqPEH1B.kc6Q7qvYCTUlOuLeKa1BMvLLR2NNTt6','2025-08-09 23:12:32');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `emergency_contact_relationship` varchar(255) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `current_medications` text DEFAULT NULL,
  `medical_notes` text DEFAULT NULL,
  `dental_history` text DEFAULT NULL,
  `previous_treatments` text DEFAULT NULL,
  `dental_notes` text DEFAULT NULL,
  `insurance_provider` varchar(255) DEFAULT NULL,
  `insurance_policy_number` varchar(255) DEFAULT NULL,
  `insurance_group_number` varchar(255) DEFAULT NULL,
  `insurance_expiry_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_patient_id_unique` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,'PAT20250001','Louie','Bahringer','1975-04-28','male','98398 Donnell Lane\nEast Ozellaport, IL 94453','+18107625567','johnathan.rutherford@example.net','Katelyn Rath','732-434-6666','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(2,'PAT20250002','Stephania','Upton','2020-03-14','female','17244 Littel Courts\nThielberg, VA 73673','872.659.7528','braun.johnson@example.org','Annamae Kuhn','+1 (910) 727-7960','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(3,'PAT20250003','Blake','Cassin','1978-09-30','other','71087 Vanessa Views Apt. 568\nNorth Mariah, NV 86856-1213','+1.850.642.8278','cordie69@example.org','Chelsea Mueller','303.387.6375','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(4,'PAT20250004','Hilda','Wintheiser','2014-03-04','other','434 Mariane Cliffs Apt. 382\nKoelpinfort, NV 23865-8764','828-362-9627','queen.okon@example.net','Zena Feest','810.690.2403','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(5,'PAT20250005','Beth','O\'Conner','1972-10-01','male','941 Zboncak Cape Suite 252\nRogahnmouth, NC 76049-8480','828.899.9667','mohr.george@example.org','Rahul Wintheiser','925-913-2065','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(6,'PAT20250006','King','Mohr','1975-11-04','other','325 Kuhn Shore\nNorth Thadburgh, MN 35785','1-559-441-5401','brain.schimmel@example.net','Ms. Willie Lemke Jr.','323-705-6733','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(7,'PAT20250007','Yasmeen','Crist','1991-05-06','other','62005 Erin Expressway\nSouth Abby, RI 55044','+1-539-515-2453','frami.linwood@example.com','Miss Bria Collins V','+18282515958','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(8,'PAT20250008','Rudolph','Haag','2020-04-05','other','36297 Corkery Village\nAdamshaven, ND 99813','1-574-975-9753','clair20@example.org','Ruth Denesik','(872) 484-8686','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:03','2025-08-06 01:59:03'),(9,'PAT20250009','Kelvin','Ward','1999-03-25','male','2966 Christa Trafficway Suite 073\nEast Lilyan, AK 16255-1127','(469) 827-4256','royce46@example.net','Dr. Manuela Lang','+1-605-751-1164','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(10,'PAT20250010','Aliya','Greenfelder','2010-07-23','female','543 Deangelo Roads Apt. 067\nLynchshire, HI 65643','928.322.2016','nikko26@example.org','Jameson Macejkovic','+16783836396','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(11,'PAT20250011','Kendra','Gaylord','1989-04-17','female','78570 Markus Turnpike Suite 548\nOscarbury, TN 92969','971.635.8143','smith.erika@example.com','Chanel Morar MD','+1.607.386.9350','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(12,'PAT20250012','Orlando','Haag','2016-12-01','female','5198 Alfreda Lakes Apt. 912\nPercyland, KS 19999','+14584803336','august19@example.net','Hazle O\'Hara','(283) 476-5843','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(13,'PAT20250013','Eliseo','O\'Keefe','2005-03-03','male','4726 Bridget Cliff\nJohnsonfurt, NJ 33620','(936) 515-6269','gibson.gladys@example.net','Alberto Haley IV','1-804-446-9929','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(14,'PAT20250014','Miguel','Labadie','2004-06-23','male','2607 Zita Ramp Apt. 955\nNorth Albin, AK 56103-9614','+1 (539) 651-9695','hbaumbach@example.org','Forrest Kassulke Jr.','1-520-339-3842','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(15,'PAT20250015','Jerome','Thompson','2001-12-04','other','2762 Riley Plains Suite 387\nSouth Carolyntown, IN 87357-5760','+15393234929','ortiz.nils@example.com','Dr. Zion Upton','+15864839728','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(16,'PAT20250016','Faye','Crooks','1982-02-23','male','91510 Trever Corners Apt. 673\nNorth Maximilian, ME 55987','+1.903.236.7669','gilda54@example.com','Russ Rodriguez','660.533.7500','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(17,'PAT20250017','Tracy','Abshire','2015-06-18','male','2126 Dickinson Gardens\nLake Daphnee, ME 87318-8838','(929) 535-2162','huel.laney@example.net','Miles Mann','+1 (803) 569-6884','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(18,'PAT20250018','Valentin','Herzog','2019-11-19','female','817 Ulices Pike\nMorarton, TN 22188-1542','(458) 594-0124','htreutel@example.org','Prof. Cullen Stokes Jr.','+1-252-627-3312','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(19,'PAT20250019','Raegan','Heathcote','2018-03-02','male','423 Jacklyn Forge Suite 485\nPort Eda, RI 72482','364.881.9497','edyth.hartmann@example.org','Kyla Macejkovic','+1 (509) 759-6505','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-09 20:24:58'),(20,'PAT20250020','Lauryn','Lueilwitz','2020-06-14','male','165 Dibbert Parkway\nHahnfurt, MN 83351-1892','(740) 329-7716','rossie08@example.net','Burnice Kub V','1-351-333-7195','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(21,'PAT20250021','Deron','Kertzmann','1978-07-31','female','98719 Luettgen Plaza Apt. 261\nNew Maryjanetown, WY 92223-0163','(870) 704-2780','camila.fay@example.net','Janelle Nitzsche','(872) 266-0984','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(22,'PAT20250022','Elenora','Lindgren','1998-01-12','male','959 Collier Estate Apt. 139\nNew Addie, MA 84814-6025','+1.715.386.9838','omurazik@example.com','Graciela Murazik','361-812-5503','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(23,'PAT20250023','Velma','Wyman','1982-11-29','other','34379 Jacobs Center Apt. 502\nNikolausmouth, WY 39472','(574) 998-5549','yjakubowski@example.net','Prof. Dorthy Kerluke','843-803-8145','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(24,'PAT20250024','Lemuel','Abernathy','1991-09-25','female','609 Kayleigh Lodge\nSouth Bryanahaven, HI 01211','1-445-300-7590','dejuan19@example.net','Zachary Fritsch','+1.772.961.2616','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(25,'PAT20250025','Forrest','Torp','2007-04-11','other','30046 Keeling Shoals Suite 550\nAntwonside, WA 41076','1-458-809-8438','wrussel@example.org','Sarah Rosenbaum','(386) 303-4525','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(26,'PAT20250026','Genevieve','Stroman','1998-03-22','female','34242 Baby Orchard Apt. 233\nZelmaside, WI 92801','772-647-7542','kieran.mraz@example.net','Dr. Brady Nitzsche V','352.524.9667','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(27,'PAT20250027','Gus','Heaney','2023-07-16','other','766 Brenden Mount\nNew Merle, MN 95612','+15673933361','era83@example.org','Armand Kuvalis','+1-586-232-6178','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(28,'PAT20250028','Saige','Von','1995-10-03','other','835 Collier Extensions\nPort Justine, KY 62955-1062','+1 (808) 478-4758','windler.kennedy@example.com','Mr. Jordy Weber','1-341-548-9095','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(29,'PAT20250029','Ebba','Yundt','1974-12-08','other','424 Kovacek Green Suite 544\nLangside, WV 90165-6568','480.818.9772','gottlieb.hillary@example.net','Jayson Miller','+1-985-362-3212','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(30,'PAT20250030','Kallie','Cole','1982-04-04','female','894 Eusebio Drives\nKenyafurt, TX 60634','218.708.9082','thettinger@example.com','Evans Heller DVM','413.552.7509','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(31,'PAT20250031','Joyce','Satterfield','1984-11-25','male','1669 Michaela Branch\nWest Aliyah, WA 04049-6284','530-381-2914','altenwerth.armani@example.com','Pamela Schowalter','+1-540-990-6605','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(32,'PAT20250032','Caesar','Collier','2001-01-10','female','21743 Hand Ranch\nNorth Itzelstad, RI 86258-0585','+16504828310','gerard.bogan@example.com','Otha O\'Keefe','(380) 640-2696','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(33,'PAT20250033','Jerad','Jacobi','1987-08-27','other','837 Rice Mission Suite 731\nEast Josieland, HI 72944','1-209-225-3544','koss.aurore@example.com','Ms. Letha Stoltenberg','(919) 489-8601','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(34,'PAT20250034','Kellen','Nikolaus','1993-01-30','female','842 McClure Roads Suite 927\nLake Keith, FL 09179','+1.571.680.8672','laurence.denesik@example.com','Miss Kaylin Cummerata IV','(818) 714-0353','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(35,'PAT20250035','Haylee','Hermiston','1982-05-22','other','3842 Osinski Groves\nPort Warrenville, CA 17257-1442','469-646-1674','beier.malachi@example.org','Destiney Orn III','+1-820-707-2356','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(36,'PAT20250036','Mona','Stanton','2018-07-02','male','149 Breana Forges\nDooleyland, MS 05794','260-640-2363','earl82@example.org','Prof. Hettie Daniel','+1-682-325-4101','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(37,'PAT20250037','Maryjane','Goldner','2021-06-30','male','832 Aiyana Meadow\nLakinview, KY 23098','1-657-349-9727','arvilla44@example.com','Estrella McKenzie','(347) 473-1810','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(38,'PAT20250038','Caitlyn','Cummerata','1997-03-29','male','258 Effertz Run\nHettingershire, NV 52925-9163','+15022748690','omacejkovic@example.net','Myrtice Halvorson','856.585.1675','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(39,'PAT20250039','Percival','Cruickshank','2019-02-10','male','688 Lila Islands\nNorth Efren, MN 87670','+1 (667) 478-1660','green.stanton@example.org','Francisco Walsh MD','1-934-254-0633','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:04','2025-08-06 01:59:04'),(40,'PAT20250040','Kevon','Ullrich','1992-07-05','other','534 Littel Plain Suite 378\nWest Myra, MO 55200-3770','+1-302-654-7012','bahringer.dennis@example.net','Macy Rice DVM','279.266.6819','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-09 20:24:26'),(41,'PAT20250041','Manley','Langosh','2005-12-16','male','65437 Tyra Greens\nFrankiehaven, HI 74859-3098','847-628-0909','clyde72@example.org','Bethany Miller','248-227-9510','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-09 20:24:48'),(42,'PAT20250042','Rowena','Kreiger','1977-09-27','female','58769 Walsh Tunnel Suite 411\nJaskolskibury, OH 44517-4957','1-419-381-6215','narciso.trantow@example.org','Prof. Roxanne Volkman II','(321) 526-3776','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(43,'PAT20250043','Fern','Wilkinson','1986-11-12','female','266 Crawford Plaza\nAlessandrobury, LA 28666-0373','681.202.6970','jdavis@example.net','Carson Medhurst','+1 (828) 658-2294','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(44,'PAT20250044','Melba','McKenzie','1988-12-21','other','7262 Gisselle Inlet\nHarberborough, OR 91068','+19069824640','arohan@example.net','Merlin Donnelly','+1-339-702-6107','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(45,'PAT20250045','Veda','Bashirian','1994-04-26','male','56479 Leffler Forest Suite 498\nLake Clement, UT 19802-9212','930.672.2681','juliana.veum@example.org','Emilia Paucek','(352) 692-0412','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(46,'PAT20250046','Jeromy','Hansen','2009-03-30','other','93175 Emma Bridge\nPort Joannie, SC 01493','+1-480-916-3770','ari.bogisich@example.com','Shawna Lehner','1-445-903-2191','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(47,'PAT20250047','Selina','Parisian','2019-04-22','female','224 Ward Fork\nSchinnerhaven, MT 71053-9156','774.252.8905','freddie.kassulke@example.com','Vivianne Pacocha PhD','419-250-5519','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(48,'PAT20250048','Jeremie','Schinner','1974-03-07','male','881 Smith Harbors\nThielfort, IL 01691-1411','+1-940-368-9354','reinger.marvin@example.org','Talia Padberg','+1-480-930-1014','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(49,'PAT20250049','Queen','Feeney','2016-02-27','other','2600 Raynor Mountains Apt. 992\nLubowitzfurt, IL 53989-9304','(820) 524-1104','alexie10@example.com','Mr. Dylan Corkery Jr.','1-352-909-3215','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(50,'PAT20250050','Weston','Rolfson','1993-09-06','female','861 Abel Turnpike Apt. 645\nNorth Leann, NV 76704-8499','1-541-284-5971','kuhn.serena@example.net','Talon Ebert','(930) 354-3225','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-06 01:59:05','2025-08-06 01:59:05');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `received_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'user-list','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(2,'user-create','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(3,'user-edit','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(4,'user-delete','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(5,'role-list','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(6,'role-create','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(7,'role-edit','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(8,'role-delete','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(9,'permission-list','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(10,'permission-create','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(11,'permission-edit','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(12,'permission-delete','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(13,'patient-list','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(14,'patient-create','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(15,'patient-edit','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(16,'patient-delete','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(17,'appointment-list','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(18,'appointment-create','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(19,'appointment-edit','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(20,'appointment-delete','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(21,'treatment-plan-list','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(22,'treatment-plan-create','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(23,'treatment-plan-edit','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(24,'treatment-plan-delete','web','2025-08-06 01:58:59','2025-08-06 01:58:59'),(25,'invoice-list','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(26,'invoice-create','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(27,'invoice-edit','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(28,'invoice-delete','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(29,'procedure-list','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(30,'procedure-create','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(31,'procedure-edit','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(32,'procedure-delete','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(33,'inventory-list','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(34,'inventory-create','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(35,'inventory-edit','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(36,'inventory-delete','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(37,'report-list','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(38,'view_dashboard','web','2025-08-06 01:59:00','2025-08-06 01:59:00');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procedures`
--

DROP TABLE IF EXISTS `procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `procedures_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedures`
--

LOCK TABLES `procedures` WRITE;
/*!40000 ALTER TABLE `procedures` DISABLE KEYS */;
INSERT INTO `procedures` VALUES (1,'nulla Extraction','Veniam harum dolor fugit alias rem id.',50.44,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(2,'quae Extraction','Ipsam placeat pariatur doloremque corporis.',919.11,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(3,'facere Cleaning','Magnam dolorem veritatis consectetur voluptas blanditiis dolore.',966.06,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(4,'cumque Filling','Consequuntur sunt dolorem culpa nihil.',794.66,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(5,'voluptatibus Root Canal','Culpa aut quia ipsa eius mollitia ullam occaecati.',491.44,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(6,'consequatur Cleaning','Nemo ducimus error doloribus.',278.67,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(7,'corporis Filling','Eos illum sapiente harum dolorem mollitia qui.',973.11,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(8,'ea Root Canal','Ad sit autem sunt sit.',348.52,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(9,'voluptas Extraction','Nihil maxime sunt commodi laborum quasi aliquid consequatur.',560.20,'2025-08-06 01:59:05','2025-08-06 01:59:05'),(10,'sed Root Canal','Qui non voluptatum laudantium a incidunt.',903.33,'2025-08-06 01:59:05','2025-08-06 01:59:05');
/*!40000 ALTER TABLE `procedures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint(20) unsigned NOT NULL,
  `inventory_item_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_inventory_item_id_foreign` (`inventory_item_id`),
  CONSTRAINT `purchase_order_items_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `expected_date` date DEFAULT NULL,
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_created_by_foreign` (`created_by`),
  CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(10,3),(11,3),(12,3),(13,1),(13,2),(13,3),(13,4),(14,1),(14,2),(14,3),(15,1),(15,2),(15,3),(16,2),(16,3),(17,1),(17,2),(17,3),(17,4),(18,1),(18,2),(18,3),(19,1),(19,2),(19,3),(20,2),(20,3),(21,2),(21,3),(22,2),(22,3),(23,2),(23,3),(24,2),(24,3),(25,1),(25,3),(26,1),(26,3),(27,1),(27,3),(28,3),(29,2),(29,3),(30,2),(30,3),(31,2),(31,3),(32,3),(33,2),(33,3),(34,2),(34,3),(35,2),(35,3),(36,2),(36,3),(37,3),(38,1),(38,2),(38,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'receptionist','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(2,'dentist','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(3,'administrator','web','2025-08-06 01:59:00','2025-08-06 01:59:00'),(4,'viewer','web','2025-08-06 01:59:00','2025-08-06 01:59:00');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smtp_configs`
--

DROP TABLE IF EXISTS `smtp_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smtp_configs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` int(10) unsigned NOT NULL DEFAULT 587,
  `encryption` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `from_email` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smtp_configs`
--

LOCK TABLES `smtp_configs` WRITE;
/*!40000 ALTER TABLE `smtp_configs` DISABLE KEYS */;
INSERT INTO `smtp_configs` VALUES (1,'Microsoft 365 SMTP','smtp.office365.com',587,'tls','support@tagresorts.com.ph','eyJpdiI6Im92RnBUaVlpRi9jMU1XcTBIUFhYSGc9PSIsInZhbHVlIjoiNGlSdWZpRmJldGdtaUd2NEdRUnhPeFN2ZzlVVmM5T1R4dnFsWGxLd2pUdz0iLCJtYWMiOiIxMGM2NDE5Y2QyZTBjMjFjYmY0MDUyZTY5YjVlNmFlNjAwNzMwMDc2YTUzZjlhZGE4OGNlOThkNzljNTNjMWQxIiwidGFnIjoiIn0=','support@tagresorts.com.ph','Clinic',1,0,'2025-08-09 22:09:25','2025-08-09 22:20:26'),(2,'Microsoft 365 SMTP','smtp.office365.com',587,'tls','ryan.lopez@backofficesolutions.ph','eyJpdiI6IjZnalN2U3BpeVN3U1FpQ3g0ZU5sYkE9PSIsInZhbHVlIjoieWJDVzQ1bVdINDVoU3cwS2liazhMa2hPNTV6TUJ2NlBHTFJ4SkVKQ2VkRT0iLCJtYWMiOiI2ODNjZWJjNTg1MGNiZDg0MmNkNjUyNzk0YTNiMDNmNWU1MzcyYTQ0ZTc2MGQzOGUwMDIwMTU4ODRiOGVhYWQ0IiwidGFnIjoiIn0=','ryan.lopez@backofficesolutions.ph','Dental Clinic',1,1,'2025-08-09 22:20:21','2025-08-09 22:20:26');
/*!40000 ALTER TABLE `smtp_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_item_id` bigint(20) unsigned NOT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_inventory_item_id_foreign` (`inventory_item_id`),
  KEY `stock_movements_created_by_foreign` (`created_by`),
  CONSTRAINT `stock_movements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_movements_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) DEFAULT NULL,
  `contact_person_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'Dental Supplies Co',NULL,NULL,NULL,'555-0100','sales@dentalsupplies.example','123 Clinic St',1,'2025-08-09 21:50:17','2025-08-09 21:50:17');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_plan_procedure`
--

DROP TABLE IF EXISTS `treatment_plan_procedure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatment_plan_procedure` (
  `treatment_plan_id` bigint(20) unsigned NOT NULL,
  `procedure_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`treatment_plan_id`,`procedure_id`),
  KEY `treatment_plan_procedure_procedure_id_foreign` (`procedure_id`),
  CONSTRAINT `treatment_plan_procedure_procedure_id_foreign` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatment_plan_procedure_treatment_plan_id_foreign` FOREIGN KEY (`treatment_plan_id`) REFERENCES `treatment_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_plan_procedure`
--

LOCK TABLES `treatment_plan_procedure` WRITE;
/*!40000 ALTER TABLE `treatment_plan_procedure` DISABLE KEYS */;
INSERT INTO `treatment_plan_procedure` VALUES (1,1),(1,6),(1,10),(2,5),(3,1),(3,7),(3,9),(4,1),(4,9),(4,10),(5,3),(5,6),(5,10),(6,4),(6,6),(6,8),(7,7),(8,5),(8,6),(9,6),(10,7),(11,7),(11,9),(12,4),(12,8),(13,1),(13,5),(13,10),(14,7),(15,7),(16,6),(16,9),(16,10),(17,5),(17,9),(18,1),(18,6),(18,7),(19,4),(20,5),(20,8),(20,10),(21,1),(21,2),(21,10),(22,10),(23,1),(23,4),(24,8),(25,4),(25,8),(25,9),(26,4),(26,5),(26,7),(27,3),(27,8),(28,1),(28,2),(28,9),(29,6),(30,1),(30,3),(30,10);
/*!40000 ALTER TABLE `treatment_plan_procedure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_plans`
--

DROP TABLE IF EXISTS `treatment_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatment_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `plan_title` varchar(255) NOT NULL,
  `diagnosis` text NOT NULL,
  `estimated_cost` decimal(10,2) NOT NULL,
  `estimated_duration_sessions` int(11) NOT NULL,
  `priority` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `treatment_notes` text DEFAULT NULL,
  `patient_concerns` text DEFAULT NULL,
  `dentist_notes` text DEFAULT NULL,
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `insurance_covered` tinyint(1) NOT NULL DEFAULT 0,
  `insurance_coverage_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_plans_patient_id_foreign` (`patient_id`),
  KEY `treatment_plans_dentist_id_foreign` (`dentist_id`),
  CONSTRAINT `treatment_plans_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`),
  CONSTRAINT `treatment_plans_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_plans`
--

LOCK TABLES `treatment_plans` WRITE;
/*!40000 ALTER TABLE `treatment_plans` DISABLE KEYS */;
INSERT INTO `treatment_plans` VALUES (1,50,6,'Quam vel consequatur.','Laboriosam nisi ut veniam accusamus occaecati ut nisi. Deserunt molestiae et maiores. Inventore laboriosam at enim consequatur.',1977.76,6,'urgent','in_progress',NULL,NULL,NULL,'A dolor quisquam eius. Iure rerum ipsum ad explicabo sunt alias. Ratione similique voluptatem quae beatae in. Reiciendis mollitia qui commodi pariatur.','Possimus unde aut dolores temporibus. Est nulla veniam cum vero cum excepturi. Maxime accusamus non totam et id animi exercitationem.','Voluptatem et est sed aperiam quam odit. Ab odit fugiat aut ut quas. Fuga eius eos officia in. Aut explicabo molestias provident doloremque odio nihil necessitatibus est.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(2,19,6,'Voluptatem impedit magni aspernatur.','Officia voluptatem dolorem temporibus est voluptates. Inventore libero ducimus voluptatem sed.',4099.91,9,'medium','in_progress',NULL,NULL,NULL,'Eum hic omnis dolore dicta assumenda. Natus iste recusandae voluptatem ea est debitis. Magni voluptatem qui est voluptatem natus pariatur maxime laudantium.','Est voluptates sed velit ipsum autem. Voluptatum rerum explicabo est culpa illo nulla. Explicabo adipisci repellat occaecati quam est aut.','Quam iusto rerum aliquam corrupti. Veritatis aut laborum in rerum.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(3,21,6,'Earum nesciunt et.','Eum alias debitis sit quia. Dolores perspiciatis sunt quam numquam tempora placeat ut.',2096.68,1,'medium','cancelled',NULL,NULL,NULL,'Ratione vitae commodi similique et et odit nisi. Nulla laborum iure totam error voluptas debitis deleniti nulla. Autem in aperiam harum ut temporibus tempore et. Aut dignissimos voluptatem velit fugit hic consequuntur dolores.','Quia corporis ut ut provident possimus voluptates commodi sed. Error consectetur numquam rerum id omnis necessitatibus. Est aliquid eaque similique repellat.','Molestias cumque nesciunt id omnis. Occaecati nesciunt magni quos ad aut velit. Recusandae numquam possimus quidem voluptatum tenetur natus.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(4,44,6,'Quis et laudantium.','Expedita facilis perspiciatis rerum sed nemo aliquid. Cupiditate aut molestiae adipisci vero magni. Saepe qui autem nobis aperiam voluptatum aliquid in.',618.45,8,'medium','completed',NULL,NULL,NULL,'Et beatae consectetur voluptatem quas. Suscipit dolores repellat veniam perferendis consequuntur. Repudiandae laboriosam inventore quam facere omnis dolor vel.','Nulla iste molestias fuga quas delectus quae ut adipisci. Vel minus in voluptas dolores magnam. Sed inventore vitae soluta quam quo.','Harum doloremque et tempora inventore at alias. Praesentium accusamus voluptas eligendi ex. Deserunt et nisi minima deleniti quasi quia.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(5,35,3,'Non excepturi unde culpa.','Reiciendis voluptatem nam ut aut aut magnam. A sunt vel est quia debitis voluptatem voluptatem. Possimus doloremque quo harum vero.',932.99,8,'urgent','proposed',NULL,NULL,NULL,'Enim consequatur tempora nisi autem. Similique consequatur enim est sit aut. Odit fugit qui laudantium sit et.','Nihil quo quisquam et reprehenderit similique modi occaecati. Repellendus dolor ea ut soluta. Dolorem est maiores labore quia. Consequatur illo similique beatae quidem et eligendi.','Voluptatem laboriosam esse aut sit. Occaecati eaque minus totam voluptatem. Excepturi accusamus in amet commodi.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(6,32,6,'Voluptatem harum quia.','Sint qui dolores placeat sunt optio quia quo. Animi impedit nesciunt totam repellendus ducimus. Quisquam dolores pariatur ea.',3416.57,10,'high','cancelled',NULL,NULL,NULL,'Id qui aut et unde est doloribus. Dolore deserunt quasi rerum. Quidem quae eos qui quaerat quo dolorem laboriosam.','Ipsa et nobis dolor et suscipit eum laudantium. Sunt quia doloremque quam dolor eum rem. Expedita quidem aut aut ut nesciunt. Quae sit velit vel dolorem.','Id animi quidem ut ea aut. Consequatur est cumque cum. Id voluptatem aut fuga atque voluptatum.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(7,6,7,'Deserunt nobis libero quia.','Eum molestiae mollitia deleniti eos eligendi quas ut. Possimus architecto aut inventore sint deserunt cum dignissimos eius. Laboriosam est cupiditate cumque.',821.07,3,'urgent','cancelled',NULL,NULL,NULL,'Rem odit et dicta maiores. Quisquam facilis et iste quisquam sed et odit non. Iste dolores qui et natus cupiditate repellat nihil. Et velit mollitia itaque inventore. Ea quae tenetur accusamus qui.','Eveniet iste neque nisi expedita. Rerum necessitatibus ut quia dicta. Ut necessitatibus praesentium vel ducimus ad. Quae et optio architecto iusto rerum nulla.','Ut id aut perspiciatis fugit alias pariatur alias. Ut occaecati natus amet quia eos est vero. Tempora non deleniti asperiores veritatis quas dolores. Necessitatibus est error voluptatem illo nesciunt et pariatur.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(8,35,6,'Ut distinctio fugit voluptatem.','Rem doloribus fugiat et est. Impedit qui et enim sed earum. Aut rerum quo magni unde sit qui autem.',1988.89,4,'low','in_progress',NULL,NULL,NULL,'Laborum similique cum vel voluptatem qui laudantium distinctio est. Voluptas dolores voluptatem quas. Ad ullam quam ut autem ut dicta.','Unde tempora consectetur sequi sit. Sit est corrupti sit molestiae. Et quibusdam tempora ut. Officia sit rerum impedit autem odit nam numquam.','Inventore repellendus fugiat illum minima. Tempore tempore odit labore itaque molestiae veritatis. Optio ullam expedita veritatis. Laudantium consequatur aut quod non et. Ab temporibus consequatur velit dolor minus.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(9,13,3,'Et mollitia.','Non illum officia incidunt ullam. Veritatis deserunt necessitatibus autem vel repudiandae. Sint quos quaerat maiores et ut sed tempore.',3041.57,6,'urgent','patient_approved',NULL,NULL,NULL,'Itaque minus qui repudiandae cupiditate hic consequatur sed dolorem. Et facilis alias eos et eos possimus dolor. Alias tenetur aut quod necessitatibus omnis et. At omnis nisi saepe corporis et.','Et itaque et sit aliquid quasi voluptatem mollitia. Dolorem sed dicta provident nostrum sunt inventore. Et tempore ullam fugit quia.','Sit pariatur voluptatem aut et culpa quae. Sed quia iure occaecati. Excepturi non esse dicta assumenda praesentium.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(10,25,3,'Quam qui in unde.','Deleniti quis dignissimos voluptas et aliquam dignissimos. Dolorem est doloremque consequatur non non voluptatibus et.',784.98,5,'urgent','in_progress',NULL,NULL,NULL,'Quisquam voluptates eveniet in porro repellat dolore. Et cum quia vero. Accusantium voluptatum velit mollitia.','Voluptates ut error recusandae fuga. Vel repellendus minima eaque reiciendis dolores iure illum vel. Est accusamus nam non. Magnam adipisci voluptatum eaque ea omnis omnis suscipit.','Qui esse est iste qui nihil. Voluptatem cumque et quae libero. Ex et hic quibusdam maxime adipisci.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(11,14,3,'Quos laudantium necessitatibus dolorem.','Eligendi inventore similique et iste sed. Consequuntur eos ratione similique voluptatem consequatur consequatur iure. Et odit enim debitis distinctio tempora vel hic.',422.34,6,'low','completed',NULL,NULL,NULL,'Ipsam et mollitia aut deserunt dolore quis. Exercitationem nihil voluptatibus autem minus et dignissimos asperiores. Molestiae veniam nobis vero aliquid cupiditate. Et corrupti et at voluptas quisquam.','Ratione corrupti amet numquam repellat. Molestiae laboriosam quia omnis rerum rerum porro non. Iure a voluptas dolor unde. Animi rem dolorem amet sunt.','Minima in sed totam voluptatibus quisquam tempore. Ea est officiis neque esse animi. Quo nulla exercitationem odit libero.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(12,22,6,'Dicta et amet.','Ea eum nihil non molestiae voluptates assumenda commodi. Unde fugiat facilis cupiditate voluptas. Omnis ut dolorum quia sint quam dolores consequuntur nisi.',1029.61,7,'low','proposed',NULL,NULL,NULL,'Magnam consequuntur necessitatibus sit accusantium quia voluptas. Voluptatem esse accusantium accusamus qui. Laborum rerum voluptas sunt aut necessitatibus. Optio aut quam ea dolor.','Est est reprehenderit aspernatur magni. Voluptas ut culpa quod cum. Quis voluptates autem omnis voluptas corporis numquam iure. Optio qui hic reprehenderit error quia est inventore est.','Quibusdam aliquam est et sequi. Consequatur maxime nam et sapiente consectetur sed. Itaque soluta ut sit ad quibusdam libero.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(13,11,7,'Pariatur sunt reprehenderit.','Quia praesentium fugit ducimus unde ea sunt qui id. Enim quibusdam ad sed praesentium. Quia iste nobis enim enim fugit itaque.',1395.57,8,'urgent','in_progress',NULL,NULL,NULL,'Quia et rerum modi non provident facere. Consectetur in quibusdam repudiandae laboriosam. Cumque voluptatem quis vitae qui inventore amet officiis. Ratione modi eos labore quidem et.','Eaque natus error dicta occaecati inventore. Necessitatibus iusto iure fugiat. Qui et provident tempore dolorem eius ipsum animi. Officia accusantium voluptate iste error architecto quod.','Nesciunt quasi pariatur magni eaque. Assumenda voluptatem quaerat libero neque consectetur delectus ipsa. Nobis est vero quisquam soluta et est aliquam. Consequatur laboriosam doloribus repellendus libero ipsam praesentium.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(14,11,7,'Velit architecto vero sit.','Exercitationem voluptatem aliquid totam consequuntur consequatur magnam. Dicta ut distinctio qui.',4753.52,7,'high','completed',NULL,NULL,NULL,'Tenetur odio assumenda cupiditate possimus minus maiores. A reprehenderit quia ea. Et ut ipsa perspiciatis. Voluptatem qui neque amet quia.','Sunt nihil occaecati explicabo perferendis repellendus soluta. Eos quasi odio officiis quis sit magni ipsam. Fugiat quos commodi non et doloribus velit.','Fugiat assumenda non et quam dolorem qui est. Quos ut porro porro aut. Tempora excepturi rem aspernatur. Cum ea atque sit voluptatum omnis porro.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(15,5,3,'Omnis fuga sequi unde.','Vel voluptatem tempore consequatur quia. Ipsam quidem optio eius sapiente vitae maiores. Occaecati optio illo cumque est sit quibusdam distinctio quo.',2554.24,7,'medium','proposed',NULL,NULL,NULL,'Ipsam dolores necessitatibus aut eum natus facilis corporis. Officiis sapiente tenetur voluptas iste numquam. Delectus quae est fuga accusamus. Labore eos odit voluptatem.','Voluptas magni voluptas sunt aut. Vel temporibus officiis architecto esse quo nulla adipisci. Est quae impedit voluptas qui temporibus.','Odit eos voluptas eius. Sint blanditiis in beatae ratione enim eos. Omnis enim hic laudantium.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(16,42,6,'Minus dolor praesentium non.','Perferendis repellat molestias officiis illum harum dolore non. Alias quo est sint itaque hic laudantium.',4568.24,2,'low','patient_approved',NULL,NULL,NULL,'Reiciendis est nulla tenetur illo labore est. Dolorum aut quaerat quia vitae. Quis iure rerum non veritatis error quas officiis architecto. Ratione voluptatem omnis ut reprehenderit.','Deserunt illo iusto atque atque saepe. Quia possimus perferendis totam eveniet in cumque. Magni id sint et consequuntur aut et.','Aut quod culpa quibusdam et mollitia expedita molestias. Doloremque repellendus eligendi excepturi distinctio molestiae. Similique nihil facere minus fugit omnis quod.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(17,11,6,'Quas ipsa commodi iusto.','Laborum quam aperiam error et. Non qui non ut dolores neque. Necessitatibus id ut fugiat iusto nesciunt unde ullam.',469.76,5,'urgent','proposed',NULL,NULL,NULL,'Repellat sunt repellat perferendis temporibus eum. Voluptatem ex libero tempora sint error quis nostrum distinctio.','Magni minus fugit beatae optio. Velit corporis veniam assumenda quia. In ipsa aut similique quia quas at. Voluptatem eos aut rerum aliquid repellat doloremque quisquam. Necessitatibus et enim deserunt perferendis consectetur eum.','Vel dicta voluptatem dignissimos minima. Velit aut distinctio quos sunt illum. Repellat saepe doloremque ea. Sit in corporis repellat voluptas quidem voluptatem.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(18,35,7,'Eius voluptates perspiciatis.','Ipsam sit modi rerum impedit. Totam ea voluptatibus qui.',2871.03,8,'medium','cancelled',NULL,NULL,NULL,'Ad animi ullam at quaerat possimus veniam aut. Nobis iste aut aut iure quasi voluptatum aut quia. Alias sed quas ipsa aspernatur.','Maxime et nisi et placeat. Quaerat autem earum sed modi assumenda ipsum facere. Ex aut illum non accusamus et. Facilis itaque voluptates ea.','Voluptas voluptas eum quia impedit et odio. Libero ut velit dolore odit et. Sit laudantium et ut eaque. Et ratione molestiae vitae voluptas facere harum.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(19,34,3,'Architecto perferendis explicabo laboriosam.','Nemo eligendi eos sed tempore aut explicabo. Odit iste dolorem asperiores et harum tenetur ea. Veritatis vitae inventore accusamus labore.',4075.60,6,'medium','proposed',NULL,NULL,NULL,'Ut dolorum tenetur ex praesentium. Et ut sint aut rerum. Ut sequi voluptatem cupiditate quis sed voluptates. Blanditiis consequatur quibusdam consectetur odio ut sed reprehenderit laborum.','Voluptate veritatis eveniet tempore aliquid assumenda est ipsum itaque. Cupiditate aspernatur adipisci voluptatem. Doloremque id aut commodi et totam sequi unde optio. Voluptate mollitia nesciunt sed perspiciatis officia qui delectus.','Suscipit nostrum qui et incidunt labore et. Officiis hic tempora quibusdam atque. Dolor sequi autem fugiat et maxime modi deleniti.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(20,23,3,'Quo molestiae esse ut.','Dolores quasi soluta occaecati quos porro qui. Aperiam nisi velit totam.',4742.60,1,'urgent','in_progress',NULL,NULL,NULL,'Ut animi ullam provident sunt. Accusantium quia numquam quia voluptates. Dignissimos quaerat quia non omnis repudiandae. Adipisci deserunt dolorem quia ut dolore qui.','Molestiae odit molestiae fuga et provident est quas. Molestias accusantium sit architecto. Vero sunt autem voluptas autem voluptatem recusandae. Et recusandae non est voluptate.','Commodi accusamus fugit recusandae quia qui amet provident. Ipsa similique necessitatibus repellendus voluptatibus provident aut. Illum cupiditate est sint nihil aut tempora facilis. Voluptatibus asperiores voluptatem quisquam quisquam harum ipsam.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(21,21,7,'Rerum et dolorem velit.','Quidem reprehenderit optio velit. Voluptates sunt explicabo aut neque a qui.',4967.21,7,'high','in_progress',NULL,NULL,NULL,'Deleniti eligendi adipisci ea molestiae necessitatibus. Magnam voluptate et molestias officia. Beatae optio nemo veniam.','Fugiat excepturi ut quod saepe et quos nemo sunt. Consequatur qui enim qui. Quasi ut nobis qui consequuntur exercitationem accusamus. Ea impedit delectus et non.','Ut quam dicta expedita eveniet. Rerum eius voluptatem est voluptate molestias. Dicta omnis facere ex qui dignissimos. Molestias et maiores natus et doloribus labore nihil deleniti.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(22,3,6,'Illo quia et.','Corrupti sint harum est ut. Et omnis omnis tempore quisquam temporibus.',3982.27,5,'urgent','completed',NULL,NULL,NULL,'Consequatur tenetur placeat nulla voluptate. Blanditiis qui debitis velit quae molestiae voluptas voluptas. Commodi voluptatem molestias commodi reiciendis labore earum dolores quo. Iusto reprehenderit quod et eos aliquid.','Nihil aut dignissimos dolores. Dolor dolorum dolores officiis sit numquam. Eos quo eum velit tenetur fugiat totam.','Ex quam id fugit deleniti perspiciatis ut. Aperiam eos dignissimos tempora debitis incidunt. Inventore eius ut adipisci adipisci sunt totam odit. Illum voluptas assumenda amet illo et beatae labore.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(23,22,7,'Et velit aliquid.','Ipsam aut laborum placeat in voluptas quibusdam aperiam. Qui sunt mollitia eos.',3555.93,2,'high','patient_approved',NULL,NULL,NULL,'Rem consectetur natus eligendi id corporis vero natus. Tempore officia praesentium quo est sint. Dolorem quo assumenda doloribus est. Officia perspiciatis non velit nesciunt.','Incidunt porro debitis et magnam eum. Voluptatibus eum vero itaque delectus nemo. Dolores tenetur sed consectetur. Ex aspernatur expedita necessitatibus pariatur et.','Iure inventore consequatur ullam reprehenderit doloribus accusamus itaque error. Dolorum inventore sint amet. Saepe assumenda et eos magnam molestiae molestiae neque. Dicta iure et quisquam. Non nostrum perferendis suscipit consequatur.',NULL,0,NULL,'2025-08-06 01:59:13','2025-08-06 01:59:13'),(24,14,6,'Cupiditate non quia.','Velit et illum molestias exercitationem odio. Ex neque minima dolore at perferendis amet omnis. Sequi sit dolores porro voluptate hic qui.',4615.38,1,'urgent','cancelled',NULL,NULL,NULL,'Ut vero id qui dolores eaque porro. Nobis provident ratione illo ut possimus ea sint. Molestias quisquam aut dignissimos omnis perspiciatis. Quo quia iusto vel vel similique aut temporibus.','In ipsam odit voluptatem itaque mollitia. Vitae aut est quam perspiciatis. Doloremque natus voluptas provident temporibus possimus maxime.','Vero atque minima inventore rem. Quia vitae et labore asperiores eligendi et.',NULL,0,NULL,'2025-08-06 01:59:14','2025-08-06 01:59:14'),(25,22,3,'Expedita dolores perferendis.','Voluptatem quis ea omnis doloribus. Dolorem reiciendis commodi deleniti repellat nulla libero ipsam. Cupiditate et est repudiandae architecto blanditiis.',997.29,10,'high','cancelled',NULL,NULL,NULL,'Doloribus vel nobis est illum aspernatur facere. Non ut voluptas tenetur earum. Et aut deleniti magnam quo maiores.','Blanditiis voluptatum sit cumque fugiat odit. Rerum minus rerum tempora sunt laborum eos rerum. A dolores minus earum consequuntur id. Rerum praesentium repellat commodi autem deserunt vel.','Hic perspiciatis explicabo ducimus voluptatum. Et nisi ipsa atque a nulla. Quis cupiditate libero est sint autem. Qui nobis voluptatum neque. Aliquid est soluta maxime cum et rerum.',NULL,0,NULL,'2025-08-06 01:59:14','2025-08-06 01:59:14'),(26,28,6,'Hic voluptate.','Tenetur neque possimus ea ab. Doloremque sed alias in voluptates. Nihil quidem quasi delectus pariatur alias consequuntur.',1456.06,10,'low','cancelled',NULL,NULL,NULL,'Voluptatem assumenda illum laboriosam aut doloremque rerum incidunt. Ea iusto rerum dolor sit alias omnis ut. Recusandae repellat veniam et.','Beatae est est dolorem id officiis architecto. Porro et eos quod error possimus consectetur. Facilis quis voluptatem minima in. Voluptatibus est id eos repellendus. Dignissimos aut officia ut adipisci quidem.','Ut omnis et nam voluptatem qui. Officiis quae nihil animi et. Eius et hic necessitatibus neque quia. Aliquid voluptas dolorem laudantium optio.',NULL,0,NULL,'2025-08-06 01:59:14','2025-08-06 01:59:14'),(27,23,3,'Eum est quas quaerat.','Ut quae laudantium perferendis repudiandae et laboriosam est. Vero nisi cumque illo ad ratione.',4563.98,2,'medium','proposed',NULL,NULL,NULL,'Ipsum esse iusto sequi laborum. Perferendis iste aperiam accusantium ex fuga rerum quas. Doloribus sapiente deleniti sit ex et.','Qui esse magni exercitationem soluta commodi porro atque. Reprehenderit rerum laboriosam eveniet ut dolorem. Ipsum molestiae similique animi dolores. Eos vel architecto et alias. Inventore et laboriosam recusandae ea quas.','Sit hic repudiandae voluptas ea animi velit omnis. Natus sunt debitis culpa dignissimos eos ipsa atque. Rerum autem error officia cumque sequi excepturi vel. Dignissimos eius distinctio molestias minus. Et fuga id animi corporis aut reprehenderit ut.',NULL,0,NULL,'2025-08-06 01:59:14','2025-08-06 01:59:14'),(28,22,3,'Deleniti quae nisi.','Eius earum ipsa vitae explicabo in provident doloremque. Fuga et sint qui omnis rerum occaecati voluptatem. Doloremque ut in aut voluptatibus autem.',3214.83,6,'high','in_progress',NULL,NULL,NULL,'Eum omnis quas libero minus sed nulla et velit. Impedit provident ut veritatis quam est sed. Suscipit illo quae voluptas et omnis id. Praesentium assumenda aliquam sed.','Dolor omnis aut nostrum aliquam. Et quibusdam sapiente atque sit dicta. Nesciunt in veritatis id aut. Dolor dolor omnis doloribus ducimus minus nihil aut possimus.','Adipisci et aut quo voluptate asperiores ipsam. Modi molestiae asperiores culpa voluptatem. Optio sunt quis expedita non quidem nostrum voluptas. Nobis maiores odio qui quia veritatis quia.',NULL,0,NULL,'2025-08-06 01:59:14','2025-08-06 01:59:14'),(29,1,6,'Qui laboriosam nulla.','Nam in ipsam soluta ut illum explicabo. Est laborum nobis et voluptatem nemo qui totam. Vero sunt cum nulla labore velit ut.',3783.66,9,'medium','cancelled',NULL,NULL,NULL,'Sit aut sequi vero tempora architecto repudiandae eaque laboriosam. Illum inventore rerum ut qui magnam et suscipit id. Tenetur eum minima non blanditiis amet earum accusamus. Eius quidem in dolores officiis ut harum dolor aut.','Quam sequi non fugit sit voluptatum et et aut. Nemo voluptatem nobis sed repudiandae consequatur. Beatae consequatur saepe a quo et. Omnis ipsam iure aut dolorem.','Aspernatur sunt ut voluptates quos fugit. Non repellat molestias sunt. Aspernatur aut quidem in repellat est eaque ea.',NULL,0,NULL,'2025-08-06 01:59:14','2025-08-06 01:59:14'),(30,27,6,'Cum nihil neque.','Voluptatum cupiditate sunt cumque rem ab illo. Voluptatem maiores iste quia qui molestiae architecto omnis.',2523.33,4,'high','in_progress',NULL,NULL,NULL,'Doloremque magnam autem ut inventore ea sed officia natus. Quam voluptatum autem veniam et perspiciatis ut voluptatem. Cumque excepturi neque aut. Veritatis ducimus error eos in.','Praesentium beatae quo quidem id. Aliquid et nam quaerat et. Est eius corrupti voluptatem illum vero. Et exercitationem voluptas enim vero et quaerat aliquam.','Est harum temporibus qui consequatur amet consequuntur. Culpa accusantium ut aliquid eos. Porro ut blanditiis velit ipsam eum.',NULL,0,NULL,'2025-08-06 01:59:14','2025-08-06 01:59:14');
/*!40000 ALTER TABLE `treatment_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_record_procedure`
--

DROP TABLE IF EXISTS `treatment_record_procedure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatment_record_procedure` (
  `treatment_record_id` bigint(20) unsigned NOT NULL,
  `procedure_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`treatment_record_id`,`procedure_id`),
  KEY `treatment_record_procedure_procedure_id_foreign` (`procedure_id`),
  CONSTRAINT `treatment_record_procedure_procedure_id_foreign` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatment_record_procedure_treatment_record_id_foreign` FOREIGN KEY (`treatment_record_id`) REFERENCES `treatment_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_record_procedure`
--

LOCK TABLES `treatment_record_procedure` WRITE;
/*!40000 ALTER TABLE `treatment_record_procedure` DISABLE KEYS */;
INSERT INTO `treatment_record_procedure` VALUES (1,5),(1,6),(1,7),(2,7),(3,4),(3,7),(4,1),(4,2),(4,4),(5,5),(6,5),(6,9),(7,3),(8,6),(9,6),(10,3),(10,4),(11,8),(11,9),(12,1),(13,6),(13,9),(14,2),(14,10),(15,1),(16,3),(16,8),(17,7),(18,2),(18,7),(18,10),(19,3),(20,2),(20,4),(20,7),(21,2),(21,4),(22,10),(23,1),(23,3),(23,4),(24,1),(24,2),(24,7),(25,4),(25,10),(26,1),(26,2),(26,10),(27,3),(27,9),(28,4),(28,8),(28,9),(29,7),(30,3),(30,4),(30,10),(31,5),(31,6),(32,6),(33,2),(33,6),(34,1),(34,3),(34,6),(35,1),(36,6),(37,5),(37,6),(37,8),(38,2),(38,6),(39,1),(39,9),(40,3),(40,8),(40,9),(41,3),(41,5),(41,6),(42,1),(42,2),(42,10),(43,10),(44,2),(45,7),(45,8),(45,9),(46,2),(47,4),(47,5),(47,8),(48,6),(48,8),(49,8),(50,1),(50,3),(50,7);
/*!40000 ALTER TABLE `treatment_record_procedure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_records`
--

DROP TABLE IF EXISTS `treatment_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatment_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `treatment_plan_id` bigint(20) unsigned DEFAULT NULL,
  `treatment_date` date NOT NULL,
  `treatment_notes` text DEFAULT NULL,
  `post_treatment_instructions` text DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `teeth_treated` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`teeth_treated`)),
  `dental_chart_updates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dental_chart_updates`)),
  `medications_prescribed` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`medications_prescribed`)),
  `follow_up_required` tinyint(1) NOT NULL DEFAULT 0,
  `next_visit_recommended` date DEFAULT NULL,
  `attached_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attached_images`)),
  `attached_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attached_documents`)),
  `treatment_outcome` varchar(255) DEFAULT NULL,
  `complications_notes` text DEFAULT NULL,
  `treatment_cost` decimal(10,2) DEFAULT NULL,
  `billed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_records_patient_id_foreign` (`patient_id`),
  KEY `treatment_records_dentist_id_foreign` (`dentist_id`),
  KEY `treatment_records_treatment_plan_id_foreign` (`treatment_plan_id`),
  KEY `treatment_records_appointment_id_foreign` (`appointment_id`),
  CONSTRAINT `treatment_records_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `treatment_records_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`),
  CONSTRAINT `treatment_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `treatment_records_treatment_plan_id_foreign` FOREIGN KEY (`treatment_plan_id`) REFERENCES `treatment_plans` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_records`
--

LOCK TABLES `treatment_records` WRITE;
/*!40000 ALTER TABLE `treatment_records` DISABLE KEYS */;
INSERT INTO `treatment_records` VALUES (1,14,6,13,'2025-05-23','Animi iusto atque voluptatem sunt ea consequatur fuga voluptatem. Soluta possimus doloribus corrupti ea neque. Aut vel error et aut enim harum. Ea quibusdam in animi.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1690.30,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(2,28,3,6,'2025-05-12','Amet asperiores vitae saepe. Aut autem mollitia voluptatem rem beatae. Hic harum voluptas iure perspiciatis veritatis. Ullam voluptate doloremque et nostrum.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1171.59,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(3,7,3,14,'2024-11-25','Aspernatur vel dolorem perferendis optio quo accusamus. Vero omnis qui et inventore et esse voluptatem quae.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1769.12,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(4,38,7,20,'2025-06-04','Unde est neque ut natus distinctio veniam. Corrupti perspiciatis eos ut nulla. Labore omnis aut voluptas esse mollitia voluptas amet ut.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1379.25,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(5,29,3,30,'2025-03-31','Animi ad ut ipsum ad eaque velit. Hic cumque similique modi placeat aut dolores doloremque. Sunt sunt ullam quod aliquid natus rerum. Possimus aut accusamus nihil voluptatem dolorum ea ad.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1777.07,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(6,19,3,2,'2025-02-19','Vitae architecto esse est illum consectetur. Sed eius non consequatur vel eum enim.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1790.39,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(7,16,7,9,'2025-05-15','Iusto voluptatibus nisi nam illum nobis voluptates. Quae est ut debitis alias et atque. Provident quidem eaque ratione consequatur odio amet.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,948.65,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(8,13,6,16,'2025-04-15','Modi fuga fugit fuga. Sapiente iusto porro quasi consequatur ut officia. Recusandae voluptas culpa cum veritatis sequi laboriosam quia.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,230.71,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(9,45,6,30,'2025-03-22','Magnam ipsam eligendi minima earum repudiandae occaecati. Totam consequatur enim voluptatem corrupti qui quam eos nesciunt. Ea deserunt iure architecto doloribus commodi. At laboriosam ea in voluptate iste est.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1118.38,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(10,23,6,30,'2024-11-26','Possimus impedit enim consequuntur sunt et ut ipsa. Delectus voluptatum voluptate deserunt quia quia. Consequatur repellendus aliquid asperiores sit quidem earum repellat. Hic voluptates rerum autem quo ab minus atque.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,112.81,0,'2025-08-06 01:59:15','2025-08-06 01:59:15',NULL),(11,10,7,12,'2024-11-05','Autem suscipit quaerat quis incidunt rerum. Voluptas molestiae nihil sit ab officia corrupti. Id odio sed sunt aut. Earum et possimus voluptas sint voluptatum rerum. Molestiae quae facere eum ipsum molestiae aut officia voluptates.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,258.04,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(12,4,3,2,'2024-12-21','Optio exercitationem soluta necessitatibus rerum et laborum. Repudiandae rem corrupti et qui voluptas minus architecto. Vitae quasi deserunt libero vitae expedita in.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1733.56,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(13,11,6,30,'2025-05-28','Odio numquam voluptatem eaque vel natus qui. Sint consequatur optio sit aut numquam. Accusantium et atque quis. Amet quaerat voluptatem sequi ipsa.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1325.15,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(14,48,3,17,'2025-06-15','Ipsum fugit minus in atque dolorem laborum. Voluptatem exercitationem voluptatum nesciunt. Aut ut esse nihil debitis ut voluptatibus laudantium. Optio quo vel rerum dolorem praesentium necessitatibus.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,133.85,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(15,9,6,8,'2024-09-06','Sint et sint pariatur non totam aut ut vero. Officiis ad voluptatem ipsum sit. Vero consectetur voluptas dolorem.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1569.89,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(16,46,3,16,'2025-03-24','Voluptas et amet cupiditate odit. Veritatis quibusdam adipisci doloremque. Dolor quaerat est soluta omnis. Quasi vitae inventore deleniti sunt nemo. Non reiciendis velit officia incidunt corporis temporibus optio.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1217.26,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(17,10,3,25,'2024-08-26','Corrupti quos eveniet rem. Enim incidunt sit ut voluptatibus facere nesciunt tempore.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1149.42,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(18,49,3,7,'2025-05-04','Sit esse repellat odit ut sed minima. Modi dolor rem autem incidunt. Suscipit consequatur ut itaque officiis fugit expedita harum adipisci.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1066.85,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(19,44,3,21,'2025-04-04','Voluptatem et aut eligendi voluptas omnis ipsum id non. Quis in aliquam reiciendis illo cum explicabo. Fugit nesciunt qui vel qui. Quibusdam dolore qui aut.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1833.77,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(20,6,7,2,'2025-04-04','Rerum laborum est consequatur ducimus voluptates incidunt doloribus. Autem consequatur dolor sint ipsa voluptatibus qui nihil. Omnis sint architecto molestiae dicta consequatur ab ut. Architecto molestiae est est tenetur sunt aliquam aperiam enim. Excepturi quidem et provident.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,359.51,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(21,26,6,14,'2024-10-08','Veniam quod repudiandae aut perspiciatis facilis amet nam. Quae perferendis sequi praesentium nemo nulla. Ipsa quo eaque itaque hic maxime voluptas.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,468.37,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(22,33,7,12,'2024-11-21','Eaque cumque doloribus dolorum ut laborum. Non est enim sit odio rerum. Rerum omnis et suscipit. In fugit nobis qui vel ipsa molestiae.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1627.76,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(23,36,3,5,'2025-06-07','Perferendis minus atque excepturi consequatur. Eum eveniet beatae sed labore non aut. Facilis ex ad autem aut inventore eius. Voluptates alias eligendi placeat in occaecati sit cum optio.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1290.37,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(24,45,3,8,'2025-03-04','Ullam quia ut atque rem dolor. Itaque adipisci mollitia minus et maxime iste dignissimos tenetur. Libero neque non quos. Quisquam veritatis consectetur et ratione explicabo sit totam.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1240.39,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(25,4,3,30,'2024-10-19','Corporis voluptas dolor non nisi veritatis et magni. Voluptatem autem nulla modi quae nemo. Facere dolore maiores dolores porro nulla quia rerum.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1761.70,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(26,10,6,22,'2025-03-26','Fugit quasi et aperiam omnis. Aut accusantium distinctio ab cumque. Laudantium laborum deleniti sed itaque consequatur ut quisquam.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1060.46,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(27,13,7,23,'2024-12-26','Possimus aut animi et molestiae sequi. Doloribus rerum sed veniam perspiciatis enim aliquam. Doloremque qui qui natus saepe est. Et tempore quos hic.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,139.28,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(28,21,3,18,'2025-07-26','Quaerat cupiditate consequatur eum quis. Dolor quas enim et corrupti rerum. Sint sequi et qui sed enim est nulla adipisci. Ut unde debitis hic at.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,128.51,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(29,31,6,14,'2025-05-24','Itaque vel at exercitationem omnis sint qui. Commodi omnis et ab. Neque necessitatibus voluptatibus autem rem fugit similique. Voluptate ut exercitationem vero quaerat quia exercitationem.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,424.16,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(30,46,3,25,'2025-07-23','Molestiae quia enim aliquid. Rem maxime nesciunt vitae dolor ea. Alias soluta voluptatem molestiae sed deleniti sed aut. Aut aliquam necessitatibus nam reiciendis voluptatem quia.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1888.35,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(31,35,3,19,'2024-08-12','Autem et qui odit inventore voluptatum commodi. Error dolores ea ad et nihil qui.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1273.49,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(32,20,3,9,'2024-11-27','Voluptas laboriosam dolores et explicabo quasi qui. Labore sequi voluptate harum voluptatem commodi eveniet. Debitis explicabo eaque cumque. Doloremque velit voluptas et et a.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,326.06,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(33,16,6,10,'2025-08-03','Aspernatur voluptatem laboriosam commodi dicta sed ipsa architecto. Debitis et voluptatum sint recusandae. Et laudantium iusto cupiditate laboriosam et quisquam in. Et assumenda necessitatibus delectus voluptatem. Enim maxime dolore esse nulla consequatur blanditiis autem expedita.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1730.23,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(34,19,3,17,'2025-02-15','Id voluptatem est enim saepe ad possimus vero. Et ut libero nobis rem minus officiis. Similique omnis distinctio ullam eos. Placeat dolorum earum cum.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,846.54,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(35,11,6,14,'2024-11-29','Nihil totam sit rerum pariatur. Molestiae dolores accusantium architecto impedit rerum. Natus nulla exercitationem aut quia officia et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1745.26,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(36,18,3,30,'2025-07-17','Excepturi omnis fugiat a laudantium. Ipsa deleniti ducimus consequatur facere perspiciatis omnis eligendi provident. Unde repellat aspernatur nam aut sed repellendus corporis.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1280.89,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(37,10,6,6,'2025-07-04','Voluptatem id ut non aut praesentium et at. Quam exercitationem officiis nesciunt dolor natus pariatur nisi. Eius quo nihil at totam dolor.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,989.37,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(38,19,6,9,'2025-02-14','Tempora in numquam et dolor. Nihil voluptatem enim doloremque. Doloremque placeat totam minima. Quisquam commodi assumenda dolor.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1034.61,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(39,36,7,29,'2025-06-06','Dolores est nihil quaerat amet fugiat vitae cupiditate. Voluptas molestias aliquid nihil voluptatibus explicabo qui facilis. Quia tempore commodi eaque in vitae. Alias vel ut accusamus nisi dolor ducimus.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1632.94,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(40,28,3,10,'2024-10-27','Quidem consectetur veniam culpa ea. Consequuntur qui sint atque laborum. Est laboriosam autem neque. Aliquid ea tenetur et et quis ad. Voluptatibus aut numquam ut doloribus accusantium quod neque.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1577.09,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(41,15,6,5,'2024-08-29','Est odio sit aliquam commodi quibusdam labore distinctio. Fugit molestiae officiis et tenetur sint nobis quia. Autem nesciunt reiciendis iusto nam et aut aperiam.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1636.19,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(42,19,6,3,'2025-07-19','Vel eveniet deserunt natus ea. Aperiam debitis explicabo aspernatur ab magni qui iste. Dolorem libero id quam qui odio veritatis qui. Odio nostrum reprehenderit dolore voluptatibus amet id placeat.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1966.43,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(43,48,7,15,'2025-06-07','Voluptates laborum quasi nam ducimus et aut. Illo dolores repellat aut rerum.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1587.14,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(44,47,7,22,'2024-12-16','Ab voluptatum tenetur aut recusandae necessitatibus quis impedit at. Et quia quos vel doloribus in. Hic aut modi itaque sed quod deleniti. Sed quidem nobis accusantium in recusandae assumenda.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,614.32,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(45,26,6,5,'2024-12-09','Nisi rerum omnis iure impedit. Ad et ratione porro exercitationem quidem voluptas optio. Et tempore impedit odit consectetur perferendis ducimus architecto. Quidem distinctio tenetur id qui voluptas.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,261.19,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(46,16,7,11,'2025-01-17','Ea minus nihil sed autem. Placeat ut amet culpa et optio molestiae velit velit. Consectetur distinctio eaque consequatur harum similique excepturi.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,853.69,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(47,40,7,25,'2025-06-07','Ab eveniet assumenda molestiae ex. Molestias culpa consequatur quia earum. Modi omnis maiores eum eos quisquam. Quidem molestiae quam unde consequuntur est praesentium omnis.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,896.16,0,'2025-08-06 01:59:16','2025-08-06 01:59:16',NULL),(48,50,3,9,'2024-12-05','Repellat repellendus cupiditate quod. Enim aperiam et ab non ipsa aliquid laborum. Aut corrupti atque sed et in repellat ullam.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,334.73,0,'2025-08-06 01:59:17','2025-08-06 01:59:17',NULL),(49,41,6,18,'2025-01-26','Temporibus optio alias explicabo sed molestiae. Et alias qui non odio.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1240.16,0,'2025-08-06 01:59:17','2025-08-06 01:59:17',NULL),(50,42,7,7,'2024-11-24','Amet perferendis at est eos velit. Rem ex quas occaecati. Similique deserunt illum in libero expedita eligendi. Enim eos similique omnis et ea.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,711.09,0,'2025-08-06 01:59:17','2025-08-06 01:59:17',NULL);
/*!40000 ALTER TABLE `treatment_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_dashboard_preferences`
--

DROP TABLE IF EXISTS `user_dashboard_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_dashboard_preferences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `widget_key` varchar(255) NOT NULL,
  `x_pos` int(11) NOT NULL,
  `y_pos` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_dashboard_preferences_user_id_widget_key_unique` (`user_id`,`widget_key`),
  CONSTRAINT `user_dashboard_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_dashboard_preferences`
--

LOCK TABLES `user_dashboard_preferences` WRITE;
/*!40000 ALTER TABLE `user_dashboard_preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_dashboard_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_table_preferences`
--

DROP TABLE IF EXISTS `user_table_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_table_preferences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `table_key` varchar(255) NOT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`preferences`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_table_preferences_user_id_table_key_unique` (`user_id`,`table_key`),
  CONSTRAINT `user_table_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_table_preferences`
--

LOCK TABLES `user_table_preferences` WRITE;
/*!40000 ALTER TABLE `user_table_preferences` DISABLE KEYS */;
INSERT INTO `user_table_preferences` VALUES (1,5,'patients.index.table','{\"order\":[\"name\",\"dob\",\"gender\",\"address\",\"phone\",\"email\",\"actions\"],\"hidden\":[]}','2025-08-09 21:28:40','2025-08-09 21:31:36');
/*!40000 ALTER TABLE `user_table_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'receptionist',
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','superadmin@example.com','2025-08-06 01:59:01','$2y$12$EDwVOG5nB3aLn2Zla658NeusQiDUCHLmdZz2eYtLRapehzQumGzPO','receptionist',NULL,NULL,1,NULL,'p9QBf1BRNs','2025-08-06 01:59:01','2025-08-06 01:59:01'),(2,'Admin User','admin@example.com','2025-08-06 01:59:01','$2y$12$EDwVOG5nB3aLn2Zla658NeusQiDUCHLmdZz2eYtLRapehzQumGzPO','receptionist',NULL,NULL,1,NULL,'BrjoiPiA6j','2025-08-06 01:59:01','2025-08-06 01:59:01'),(3,'Editor User','editor@example.com','2025-08-06 01:59:01','$2y$12$EDwVOG5nB3aLn2Zla658NeusQiDUCHLmdZz2eYtLRapehzQumGzPO','receptionist',NULL,NULL,1,NULL,'VxUbDVNUYa','2025-08-06 01:59:01','2025-08-06 01:59:01'),(4,'Viewer User','viewer@example.com','2025-08-06 01:59:01','$2y$12$EDwVOG5nB3aLn2Zla658NeusQiDUCHLmdZz2eYtLRapehzQumGzPO','receptionist',NULL,NULL,1,NULL,'wNjw3qH51F','2025-08-06 01:59:01','2025-08-06 01:59:01'),(5,'System Administrator','admin@dentalclinic.com','2025-08-06 01:59:01','$2y$12$uFBFk7M8qAbtTfppaLZRZOJj7ju6GNbs00RGI4cciTqDluc.36szq','receptionist','(555) 123-4567','123 Admin Street, City, State 12345',1,NULL,'bAlkW570YuZnARHTkxeA6goKTHdKTWMe8OaFrjgFHs3HC1jAsKHrqJhXVwnn','2025-08-06 01:59:01','2025-08-06 01:59:01'),(6,'Dr. John Smith','dr.smith@dentalclinic.com','2025-08-06 01:59:02','$2y$12$DJ3.wnUkDM5vj/eGIjdDuO1jJ2uGHhJgwlbfeAZsDdfvdg.AY8CmS','receptionist','(555) 234-5678','456 Dentist Avenue, City, State 12345',1,NULL,NULL,'2025-08-06 01:59:02','2025-08-06 01:59:02'),(7,'Dr. Sarah Johnson','dr.johnson@dentalclinic.com','2025-08-06 01:59:02','$2y$12$5utUnLur9.znYgxgnYlCzePo8F.744g4M.Y8YzN1FQSmbU5pIy5kO','receptionist','(555) 345-6789','789 Oral Health Blvd, City, State 12345',1,NULL,NULL,'2025-08-06 01:59:02','2025-08-06 01:59:02'),(8,'Mary Wilson','receptionist@dentalclinic.com','2025-08-06 01:59:02','$2y$12$GPDHFfd4LIZsx50Bh7YpyOxLgrNWR.ztb2yAx4xqMZWlbL.a2V4De','receptionist','(555) 456-7890','321 Reception Lane, City, State 12345',1,NULL,NULL,'2025-08-06 01:59:02','2025-08-06 01:59:02'),(9,'Lisa Brown','lisa@dentalclinic.com','2025-08-06 01:59:02','$2y$12$UXG6Ktr/IsHpOyGIH7g3EeswA7TioMOBM.tp5YzNdvWPGeH7rPz9u','receptionist','(555) 567-8901','654 Front Desk Road, City, State 12345',1,NULL,NULL,'2025-08-06 01:59:02','2025-08-06 01:59:02');
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

-- Dump completed on 2025-08-20 16:48:40
