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
INSERT INTO `appointments` VALUES (1,27,3,'2025-07-30 01:05:43',45,'Filling','cancelled','Quis magnam reprehenderit amet.',NULL,'Quod eum veniam delectus et perferendis.',NULL,'2025-08-03 19:07:41','2025-08-03 19:07:41'),(2,22,7,'2025-05-12 06:37:50',30,'Filling','completed','Tempore excepturi voluptas consequatur quia mollitia quia porro.','Quia totam excepturi dolor nobis omnis officiis laborum esse. Quod commodi eos numquam et et nostrum ut. Eum nulla perspiciatis magni tempora dolor consequatur. Et eos et libero sequi veritatis consequatur error.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(3,26,3,'2025-07-26 22:11:17',30,'Extraction','cancelled','Qui suscipit eaque quas qui.',NULL,'Enim autem porro qui enim aperiam et.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(4,43,3,'2025-06-25 05:00:17',15,'Extraction','completed','Assumenda dolorem dicta quo maiores.','Sunt beatae sint qui temporibus vero quo. Illum voluptatum quaerat sed nesciunt ex quia. Dolorum ad quia necessitatibus sunt enim esse.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(5,42,6,'2025-09-20 16:26:17',45,'Filling','scheduled','Recusandae vero enim ipsam.','Voluptatem est aperiam velit. Quis qui explicabo laborum mollitia. Explicabo alias fuga maxime rerum tenetur sed sint. Explicabo et inventore sint eos.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(6,21,7,'2025-05-29 14:01:05',30,'Cleaning','cancelled','Ipsum impedit illum earum.',NULL,'Ut illo cupiditate deleniti voluptatem.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(7,21,7,'2025-10-04 14:20:51',60,'Extraction','confirmed','Eius magni et beatae placeat et.','Quas voluptatem numquam quae voluptas. Deserunt numquam consectetur quam itaque quas praesentium. Nulla ipsam quod laborum commodi consequatur. Pariatur repellat dolores quam quis suscipit repudiandae.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(8,17,6,'2025-05-26 23:07:22',60,'Check-up','completed','Quia magni aut sint et iusto.','Suscipit nisi assumenda qui aut ullam. Natus sunt ab sequi molestiae perspiciatis repellendus.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(9,46,6,'2025-06-08 02:33:26',45,'Extraction','cancelled','Deleniti ut enim eos et consectetur.',NULL,'Quam rem autem aut sit sed.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(10,2,6,'2025-05-28 15:13:34',30,'Filling','completed','Nisi accusamus ab quo.','Aspernatur commodi aut vitae distinctio sapiente. Quos nihil error est officiis quod ex.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(11,39,7,'2025-06-04 00:41:05',30,'Filling','cancelled','Animi saepe voluptas molestiae animi provident repellendus.',NULL,'Fugiat dolore ab et aut consequatur porro.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(12,49,3,'2025-10-29 17:27:23',30,'Filling','scheduled','Minus voluptas impedit atque.','Fuga maxime dolor delectus est eveniet qui placeat sunt. Est placeat fuga qui voluptatem natus et. Minima aut modi quos doloremque tenetur ut eum qui. Natus cum quo incidunt eos.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(13,24,6,'2025-07-22 18:06:25',45,'Cleaning','no_show','Voluptatem et et voluptates aut et.','Repudiandae quas voluptatem voluptatem. Necessitatibus minima autem vel et. Eum perspiciatis aperiam laudantium nihil sed suscipit porro. Laboriosam ipsum fugit fugiat adipisci.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(14,35,6,'2025-07-02 19:41:51',15,'Consultation','completed','Quae similique consequatur eos nemo.','Dolores ut in ducimus voluptatem ut earum non. Accusamus non debitis enim. Voluptatem odio quaerat quaerat rerum laboriosam amet ullam. Ut eaque neque placeat maiores.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(15,47,3,'2025-09-22 17:13:01',60,'Consultation','cancelled','Et et expedita et non in ut est.',NULL,'Sed ut molestiae molestiae aliquid.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(16,6,6,'2025-07-06 14:09:21',30,'Filling','completed','Qui doloremque et sit suscipit magni autem.',NULL,NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(17,47,3,'2025-09-04 04:20:04',45,'Extraction','cancelled','Quia et dolorem consequatur est quaerat.',NULL,'Nostrum eum molestiae consequatur omnis voluptatem molestiae.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(18,41,7,'2025-05-13 15:38:50',45,'Check-up','no_show','Aliquid in modi quibusdam accusamus.',NULL,NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(19,26,6,'2025-09-28 07:59:26',60,'Cleaning','confirmed','Id quos modi voluptatem ut reiciendis maiores voluptates aspernatur.','Et eos fugiat fuga ullam sit earum provident temporibus. Quisquam ad dolores enim sint. Esse autem atque atque. Dolor corporis alias illum aut architecto dolor hic.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(20,7,3,'2025-06-10 01:46:36',15,'Extraction','no_show','Tenetur numquam ea provident.',NULL,NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(21,39,6,'2025-09-24 21:14:23',60,'Consultation','scheduled','Temporibus molestias cumque nam velit in ut blanditiis sed.','Ea quaerat dolorum et aut id at minima. Neque velit vel maiores nesciunt assumenda eveniet doloremque. Perferendis in quis temporibus commodi cupiditate itaque blanditiis.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(22,41,7,'2025-07-04 01:21:26',60,'Cleaning','cancelled','Aut est voluptas corporis suscipit.',NULL,'Tenetur sed voluptatem quo sint optio accusantium.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(23,38,6,'2025-09-19 11:09:15',60,'Check-up','cancelled','Vero tempora et voluptatem fugit.','Aut assumenda inventore officia sint ut ut. Quae voluptas et quibusdam. Et est in laborum modi. Repellat qui porro unde cumque qui facere.','Nemo eos id aut molestiae eius quaerat.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(24,3,7,'2025-06-25 09:43:58',45,'Consultation','completed','Ut nulla possimus adipisci facere ipsum architecto.',NULL,NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(25,26,6,'2025-06-27 14:07:39',15,'Filling','cancelled','Tempora reprehenderit quos ut ipsum voluptatibus.','Odit dolores eos velit nihil illo officia aspernatur sunt. Nihil magnam temporibus totam et minima voluptatem. Iusto voluptates nihil at veniam veniam consectetur.','Omnis harum et voluptatem delectus voluptas reiciendis.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(26,2,7,'2025-05-24 02:44:07',45,'Filling','cancelled','Ratione id et quis modi vero quasi.',NULL,'Nulla necessitatibus est similique consectetur eius corrupti voluptatem.',NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(27,29,3,'2025-10-12 12:47:30',60,'Consultation','scheduled','Sequi repudiandae provident et magni ut itaque similique.',NULL,NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(28,12,6,'2025-07-13 23:55:32',45,'Extraction','no_show','Occaecati ut maxime expedita incidunt esse.','Vero recusandae facere iusto aut. Ea laborum accusantium eligendi qui. Officia eveniet molestiae similique consequatur suscipit consequuntur repudiandae.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(29,7,7,'2025-05-21 15:23:24',30,'Consultation','no_show','Minima molestiae voluptatem deleniti in nobis rerum laborum maxime.','Quis sequi placeat neque. Earum fugiat unde aspernatur voluptatem explicabo. Fugit nulla deserunt in expedita qui fuga quas. Quis omnis voluptatem quas quia.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(30,19,3,'2025-07-04 06:19:19',15,'Check-up','no_show','Sed eligendi perferendis et et.',NULL,NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(31,5,3,'2025-05-26 03:48:29',45,'Consultation','completed','Minus ut quidem pariatur impedit dolore.','Id vel unde quia dignissimos. Aut dicta et explicabo hic aspernatur sed pariatur. Voluptates earum consequatur recusandae similique dolor ipsum. Veritatis quam eos quam quidem temporibus rerum et.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(32,5,6,'2025-10-05 01:19:50',45,'Consultation','scheduled','Alias tempore quae iste autem explicabo.','Officia aut cumque sunt qui dolores. Impedit qui iusto omnis quia. Quasi molestias ut enim quo qui voluptatum suscipit. Ut consectetur rem harum non eos occaecati ducimus.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(33,42,3,'2025-06-10 09:48:42',15,'Filling','no_show','Eligendi labore adipisci quaerat dolor.','Quia et similique quia blanditiis harum beatae sed. Odit voluptates aut esse. Sunt cumque dolores suscipit aut consequatur maiores. Magnam quo distinctio ad.',NULL,NULL,'2025-08-03 19:07:42','2025-08-03 19:07:42'),(34,27,6,'2025-09-12 20:35:27',15,'Extraction','cancelled','Nobis commodi vero maxime et est quas temporibus.',NULL,'Veritatis quae ea sit sit ipsa ut consectetur.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(35,33,7,'2025-07-07 17:34:32',45,'Consultation','completed','Laudantium accusamus sunt modi.','Sunt quae voluptas id qui. Et voluptatem illum illo qui in consequatur harum earum. Voluptatem rem in enim enim placeat laborum ipsum.',NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(36,49,7,'2025-05-15 21:38:16',30,'Cleaning','completed','Quam dolores veritatis laboriosam sit quae sed sed.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(37,18,3,'2025-05-15 01:11:53',45,'Check-up','cancelled','Odio id accusantium veniam iusto fugiat voluptatem nihil.',NULL,'Necessitatibus ducimus occaecati illum eius dolorem nulla delectus.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(38,32,3,'2025-09-18 01:59:49',60,'Consultation','scheduled','Sed quo natus aut non.','Qui tempore voluptatem aliquid. Placeat saepe ratione animi ut doloribus. Optio aliquid deserunt eos quisquam tempore eos nihil.',NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(39,48,3,'2025-06-26 16:00:53',30,'Check-up','cancelled','A exercitationem voluptatem pariatur perferendis sit sit.',NULL,'Sit consectetur mollitia ea ut quo.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(40,31,3,'2025-07-18 03:47:49',45,'Cleaning','completed','Asperiores rerum quia eius cumque id quae maxime.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(41,17,6,'2025-09-28 12:42:01',30,'Filling','cancelled','Sint ipsum autem qui inventore cupiditate rerum molestiae.','Numquam quo ab dolores repellendus nam culpa unde. Recusandae dignissimos aliquam et quia. Amet consequuntur occaecati ab.','Corporis quidem alias delectus quaerat ea nisi.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(42,43,3,'2025-08-23 21:37:18',15,'Cleaning','confirmed','Aliquam aperiam facilis inventore quas alias.','Molestiae nulla aliquam nemo numquam ut blanditiis tenetur nemo. Maiores soluta maiores officia quia architecto deleniti optio. Asperiores rerum est nemo reprehenderit neque minima aut.',NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(43,41,6,'2025-06-29 00:00:10',30,'Filling','completed','Laboriosam in et sunt aliquid fugit.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(44,15,7,'2025-05-14 19:01:42',45,'Extraction','no_show','Beatae optio qui fugiat vero.','Nostrum quo fugit est. Et aliquid quia dolorum omnis. Aperiam non dicta deleniti eveniet est sapiente et. Doloremque perspiciatis id esse molestiae nihil.',NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(45,35,6,'2025-10-06 14:02:35',45,'Cleaning','scheduled','Inventore at est omnis occaecati sit.','Animi veritatis quidem illum omnis vel libero. Quis quibusdam officiis ea aperiam. Enim laudantium est quibusdam quos porro dolor.',NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(46,13,7,'2025-05-16 20:49:00',30,'Extraction','completed','Facilis sequi quo rerum molestiae eius odit error.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(47,12,7,'2025-09-06 05:00:47',30,'Filling','confirmed','Quos consequatur dolorem quos est non.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(48,3,6,'2025-10-21 09:42:22',45,'Consultation','scheduled','Et repellat rem est tempora dicta recusandae.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(49,15,7,'2025-05-10 07:04:38',15,'Check-up','no_show','Eveniet officia ad aperiam aut corporis sint temporibus.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(50,37,3,'2025-06-30 20:04:40',30,'Check-up','completed','Sed vel accusamus soluta autem sunt dolor.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(51,21,3,'2025-08-30 02:00:31',15,'Check-up','cancelled','Possimus deserunt dolorum quia fuga animi eaque.',NULL,'Doloremque soluta delectus rerum minima dolorem.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(52,37,7,'2025-05-12 21:14:44',15,'Check-up','no_show','Omnis ex placeat nostrum atque voluptates.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(53,38,6,'2025-08-18 06:10:49',60,'Extraction','scheduled','Laudantium aut tempore qui.','Sed qui quas et sint magni quia voluptas dolore. Quas atque quaerat qui possimus sunt porro. Quia quidem aspernatur nisi sit recusandae non nisi. Nihil sed officiis qui fuga consequatur.',NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(54,6,6,'2025-06-16 20:18:18',30,'Filling','completed','Sit dolorum officiis et et.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(55,26,6,'2025-10-30 23:50:41',45,'Filling','scheduled','Autem neque excepturi occaecati aspernatur dolore provident.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(56,38,3,'2025-05-05 07:09:02',30,'Check-up','cancelled','Architecto rerum ducimus maxime.','Incidunt perferendis ratione est ut eos cumque. Iusto rerum doloribus nobis voluptatibus. Sint atque voluptates et facilis error nobis suscipit.','Odio voluptas ducimus quis.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(57,8,6,'2025-05-29 02:45:04',60,'Filling','cancelled','Consequuntur excepturi facilis facilis voluptatem deleniti ullam.','Similique molestiae voluptas at nulla sint. Cum odio corrupti et temporibus velit laboriosam. Ut sed eum consequatur tempora. Qui hic quas libero voluptatum.','Aut voluptates qui quos omnis consectetur dolorem ab dolorum.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(58,23,7,'2025-07-24 17:35:19',60,'Extraction','completed','Commodi quis amet iure eius qui accusamus eveniet.',NULL,NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(59,21,3,'2025-08-08 11:34:06',60,'Extraction','cancelled','Ad aut non consequuntur accusamus mollitia excepturi reiciendis nesciunt.',NULL,'Labore sit at deserunt modi doloremque et nobis.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(60,35,7,'2025-06-09 03:19:15',60,'Consultation','cancelled','Consequuntur omnis omnis voluptate exercitationem vero porro cum.','Dolor in aperiam eos hic nulla. Labore vitae deleniti ea rerum esse. Laborum quas dolores nostrum et.','Sint et alias est dolorem error pariatur.',NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(61,9,3,'2025-09-25 04:49:00',30,'Cleaning','confirmed','Voluptatem explicabo et aut sint sed.','Eos repellendus nemo asperiores nobis ut. Quis aut reprehenderit deleniti doloremque. Sunt sapiente impedit ea accusamus at suscipit eum. Cupiditate fuga est corporis in non tempora provident. Sint quam ut vero sunt iure.',NULL,NULL,'2025-08-03 19:07:43','2025-08-03 19:07:43'),(62,35,7,'2025-10-14 13:37:54',45,'Extraction','confirmed','Dolores itaque laboriosam autem debitis ut a.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(63,27,3,'2025-05-14 16:08:09',60,'Filling','no_show','Soluta maxime quisquam temporibus qui.','Et dolorem suscipit dolore. Minima distinctio aut rerum quia dolore cumque laudantium pariatur.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(64,6,3,'2025-07-24 19:11:09',60,'Consultation','cancelled','Quia voluptas exercitationem ab harum expedita labore.',NULL,'Laudantium sit doloremque aut.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(65,40,7,'2025-05-17 09:04:44',60,'Check-up','cancelled','Molestias iusto possimus laborum eveniet voluptatum neque aliquid sint.','Deleniti provident ducimus hic ut modi et aut. Nihil vel blanditiis est eligendi neque cupiditate. Harum qui molestias quo officia quia natus vel.','Vitae voluptates qui et dolorum.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(66,50,7,'2025-10-17 03:49:47',45,'Consultation','cancelled','Inventore qui nisi id qui.','Odit dolore et aut perferendis rem eos vitae. Ipsa reiciendis odio itaque velit delectus amet quisquam. Beatae et nisi impedit velit eum consequatur.','Soluta consectetur enim quisquam pariatur sapiente.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(67,9,3,'2025-05-27 14:28:48',15,'Extraction','cancelled','Nulla enim incidunt incidunt.',NULL,'Molestiae optio consectetur id.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(68,48,7,'2025-05-24 20:35:04',60,'Consultation','no_show','Enim perspiciatis deleniti aut accusantium tempora.','Perspiciatis occaecati recusandae omnis tempore non inventore. Dolorum rerum odio totam cupiditate. Exercitationem numquam officiis molestias voluptas placeat ipsam.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(69,19,3,'2025-06-05 14:49:27',30,'Cleaning','completed','Quis et sunt est illum rerum unde necessitatibus.','Velit voluptatibus consequatur et ab error vitae. Dolorem fugiat reprehenderit voluptas similique voluptas quia perferendis atque.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(70,45,7,'2025-08-06 14:00:00',45,'Cleaning','scheduled','Voluptas sed quibusdam ut provident.',NULL,'Mollitia ex culpa eos beatae et ratione.','[{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-05T04:55:33.825250Z\",\"user_id\":5}]','2025-08-03 19:07:44','2025-08-04 20:55:33'),(71,18,6,'2025-10-01 10:31:17',60,'Filling','cancelled','Autem ipsa vel omnis laborum ipsa dolor quidem.','Nisi culpa qui provident harum perspiciatis dolor enim. Voluptatem sapiente earum est laudantium. Et tempora cum soluta omnis dolor ea. Enim cupiditate voluptas deserunt tempore fugit.','Aperiam voluptate qui dignissimos.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(72,30,3,'2025-05-29 18:17:06',30,'Extraction','cancelled','Nam in rem quis est pariatur at eum.','Voluptatem earum earum cupiditate nobis reprehenderit ab hic. Omnis iusto voluptates maxime vitae consequatur. Voluptatum provident non hic non dolor. Porro animi veritatis ea id incidunt. Suscipit non aut nostrum nisi sint explicabo impedit ut.','Aut molestiae repellat dolore et dolor quidem.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(73,50,3,'2025-10-14 21:27:00',45,'Consultation','scheduled','Maxime ut quia voluptatem voluptatibus.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(74,39,6,'2025-05-08 18:48:19',60,'Cleaning','no_show','Perferendis perferendis accusantium qui quam dignissimos similique adipisci.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(75,6,6,'2025-05-07 20:46:07',15,'Extraction','completed','Totam dolores et voluptatem magni totam in.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(76,44,7,'2025-11-03 15:52:36',60,'Extraction','scheduled','Perferendis aut magni dolorum unde nam enim.','Aliquid eos autem occaecati ad mollitia qui sequi. Aut veritatis quasi et sed iste est. Non inventore accusamus odit sed. Doloremque laudantium praesentium exercitationem.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(77,33,3,'2025-10-04 02:25:07',15,'Consultation','confirmed','Suscipit dicta blanditiis aliquid repellendus sint quasi.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(78,49,3,'2025-06-19 06:30:33',60,'Cleaning','completed','Asperiores in est architecto fugit.','Accusamus odit pariatur tempora nobis blanditiis. Quaerat dolorem incidunt facilis ex minima laudantium.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(79,47,6,'2025-07-27 20:32:45',45,'Consultation','cancelled','Sunt explicabo unde delectus sed.',NULL,'Quo temporibus soluta dolorem eum quod.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(80,12,7,'2025-08-19 13:45:44',60,'Filling','confirmed','Ullam repudiandae quo modi quis veniam blanditiis eaque porro.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(81,12,6,'2025-09-30 04:40:42',15,'Consultation','cancelled','Voluptas odio dolores dicta molestiae enim dolores ut non.',NULL,'Veritatis adipisci impedit quo alias.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(82,41,7,'2025-05-13 17:20:18',60,'Cleaning','no_show','Illo consequuntur dicta modi reprehenderit amet.','Adipisci qui expedita cum corporis assumenda ut tempore. Officiis illum quam vitae molestiae ut accusamus autem adipisci. In est sed autem et. Doloribus incidunt qui quia qui accusamus.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(83,45,7,'2025-07-02 14:09:10',60,'Extraction','no_show','Quasi odio eos incidunt minima illo distinctio eius.','Accusantium dolorum velit neque dignissimos veniam dolores iure exercitationem. Optio eum libero reiciendis laudantium ut debitis praesentium. Quo eaque eos et tempore maxime repudiandae. Omnis odit quia eos aut non.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(84,50,6,'2025-07-09 13:29:36',60,'Cleaning','completed','Ea dolore magnam culpa sit repellendus ipsa at hic.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(85,40,6,'2025-09-24 16:00:08',30,'Extraction','scheduled','Voluptatibus dolor expedita placeat.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(86,43,3,'2025-10-15 01:01:33',15,'Filling','cancelled','Quibusdam autem possimus vero et et similique sunt.','Quasi sit libero soluta voluptas modi. Explicabo iste est neque assumenda. Eos sit et provident blanditiis reiciendis earum aut. Nemo officiis debitis eaque iste.','Quisquam consequatur sunt ullam reprehenderit tenetur inventore.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(87,38,6,'2025-07-22 08:06:37',30,'Filling','completed','Ut vel deleniti nesciunt quasi qui quia possimus saepe.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(88,34,7,'2025-06-08 19:53:25',45,'Check-up','cancelled','Voluptas est laudantium temporibus dolor.','Hic rerum provident ut occaecati. Doloremque autem dolorem commodi iure facere. Consequuntur quasi voluptatum aspernatur fugit veniam laborum autem.','Quaerat rem voluptas dignissimos harum.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(89,24,6,'2025-10-29 13:01:18',30,'Cleaning','cancelled','Adipisci molestiae itaque nobis consequuntur minus rerum ipsa error.',NULL,'Assumenda omnis dolore qui perspiciatis cupiditate dolor nostrum.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(90,41,6,'2025-11-01 08:06:06',30,'Check-up','cancelled','Nemo dolor rem tempore.',NULL,'In eum qui ea dolore incidunt.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(91,26,6,'2025-07-06 16:26:13',30,'Consultation','no_show','Laborum aut sed voluptas sunt quas numquam.','Aspernatur inventore autem natus minus quam sequi. Quis vel alias rerum sed amet doloribus.',NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(92,42,7,'2025-08-18 04:45:05',15,'Cleaning','confirmed','Deleniti consectetur quam et quia minus similique.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(93,11,7,'2025-05-31 17:24:21',15,'Consultation','cancelled','Animi sunt placeat et optio modi sint quasi.','Doloremque deserunt et aut. Quos incidunt et nesciunt quaerat iste.','Quia sint odio ducimus laudantium optio necessitatibus pariatur excepturi.',NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(94,23,3,'2025-05-21 07:02:48',15,'Check-up','completed','Recusandae omnis ipsa minima autem.',NULL,NULL,NULL,'2025-08-03 19:07:44','2025-08-03 19:07:44'),(95,21,7,'2025-07-14 09:43:43',60,'Check-up','cancelled','Non repudiandae vitae perferendis reiciendis beatae repellat.',NULL,'Voluptatem placeat quis eaque corporis.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(96,44,3,'2025-07-11 02:30:22',60,'Cleaning','cancelled','Asperiores nihil debitis facere aut reiciendis iure ipsum.','Neque quasi nam praesentium cumque aut est incidunt non. Consequatur libero cumque earum amet praesentium consequatur. Reiciendis nisi facilis illum architecto sed est. Ullam sint tempora nostrum temporibus incidunt.','Laboriosam sunt quod odit autem sint.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(97,1,7,'2025-07-07 17:38:13',45,'Filling','completed','Ipsa nihil laboriosam sunt ut vitae.','Corrupti a rerum delectus sed. Nihil occaecati velit tempore voluptas officia molestias libero.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(98,27,7,'2025-10-19 04:32:15',60,'Filling','confirmed','Eum qui impedit est temporibus provident consequatur numquam.','Consequatur nihil enim dicta dolore perspiciatis architecto. Rerum sint earum aut qui. Sunt similique et repellat quod.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(99,33,7,'2025-08-02 13:43:37',15,'Consultation','completed','Soluta voluptas quos sint id doloribus et velit.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(100,16,7,'2025-08-13 17:00:00',45,'Extraction','scheduled','Officiis et eum soluta at cumque asperiores.',NULL,NULL,'[{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-05T04:53:43.128314Z\",\"user_id\":5},{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-05T04:54:22.721146Z\",\"user_id\":5}]','2025-08-03 19:07:45','2025-08-04 20:54:22'),(101,43,7,'2025-10-13 07:57:20',45,'Consultation','scheduled','Eaque voluptatem in molestias fugiat rerum enim.','Suscipit illo minus voluptatum quibusdam. Ipsa eum dicta et quis et aut.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(102,50,3,'2025-07-26 17:23:05',45,'Check-up','cancelled','Sit odit incidunt soluta rerum cupiditate vel.','Placeat dolores veritatis omnis hic velit adipisci repudiandae nemo. Commodi aut aliquam ipsa inventore dolor facilis esse. Eum quibusdam sunt impedit minima.','Qui et consequatur vel sed necessitatibus mollitia vel.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(103,22,7,'2025-05-23 11:33:57',15,'Filling','cancelled','Perferendis illo velit fuga neque quo.',NULL,'Totam earum eum voluptatem sit corrupti.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(104,21,3,'2025-08-20 03:44:04',60,'Consultation','scheduled','Unde quia magni facere aut.','Sit molestiae nobis et consequuntur. Eligendi quas et explicabo et voluptatem vel. Omnis pariatur qui dolorem eaque inventore dicta fuga eum.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(105,35,3,'2025-09-04 04:09:51',60,'Consultation','cancelled','In quo aperiam dolorum et.',NULL,'Consequatur incidunt ea minus soluta excepturi.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(106,35,7,'2025-05-26 07:23:43',15,'Filling','no_show','Harum odit fugiat temporibus eos doloribus totam.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(107,49,3,'2025-05-12 04:36:04',30,'Check-up','completed','Voluptatem quo ut corporis occaecati blanditiis eos vero.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(108,16,7,'2025-09-17 10:27:16',15,'Cleaning','scheduled','Itaque dolores provident unde sint rerum.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(109,38,3,'2025-07-17 09:52:45',30,'Extraction','no_show','Excepturi aspernatur consequatur dignissimos et in quo libero.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(110,34,3,'2025-10-01 10:43:19',45,'Consultation','cancelled','Doloribus est laboriosam debitis vero sed.',NULL,'Aspernatur et et ratione molestiae delectus.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(111,41,7,'2025-08-25 09:49:09',15,'Consultation','cancelled','Sit mollitia tempora ut.',NULL,'Veniam quasi iste facere voluptatem sit quidem minus.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(112,38,6,'2025-07-10 21:23:54',60,'Filling','no_show','Natus veniam qui soluta quo pariatur qui et.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(113,19,7,'2025-06-11 13:37:00',30,'Cleaning','cancelled','Excepturi dolores ea nesciunt et nihil.',NULL,'Eligendi asperiores corporis voluptatum id.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(114,6,7,'2025-05-27 22:49:35',30,'Extraction','cancelled','Dolorum nam odio vitae ab sint hic dolore.',NULL,'Voluptas excepturi neque sint impedit vel.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(115,19,6,'2025-08-03 21:08:40',45,'Extraction','no_show','Ut id id voluptatem beatae quia eveniet impedit.','Quibusdam in rerum sed autem eum tempore exercitationem. Voluptatem ipsa tempora qui quam est sint omnis. Aliquid sed doloremque aliquam incidunt natus et nisi.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(116,11,6,'2025-05-17 22:24:07',60,'Cleaning','completed','Laboriosam laborum doloremque adipisci suscipit.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(117,7,7,'2025-10-23 06:39:44',60,'Consultation','scheduled','Sunt quaerat exercitationem expedita ut sunt sit.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(118,13,7,'2025-05-14 01:08:32',15,'Check-up','completed','Dolorem est enim rem.','Aliquam eligendi velit voluptatem velit amet odit. Molestias facere pariatur consequatur molestiae perspiciatis. Mollitia sed totam non qui autem.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(119,27,6,'2025-08-12 18:57:54',60,'Check-up','confirmed','Totam nobis dolores id eveniet harum.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(120,9,3,'2025-09-26 04:30:07',45,'Cleaning','confirmed','Nostrum harum rem aut tempore incidunt aut dolor.','Magni ut et laborum porro. Dolores quidem recusandae odio voluptatibus. Sit in dolore illo animi. Quia nihil doloremque reprehenderit ut culpa velit voluptatem.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(121,34,3,'2025-07-31 17:30:25',15,'Extraction','completed','Temporibus odit quia aut eligendi et fugit omnis.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(122,3,7,'2025-06-15 19:25:22',15,'Check-up','cancelled','Consequuntur cumque necessitatibus quis rerum a.',NULL,'Consequuntur architecto eos repellendus ab.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(123,40,7,'2025-06-07 20:50:19',60,'Check-up','cancelled','Sed accusamus velit sint sint consequatur nobis cupiditate.',NULL,'Quibusdam vitae aut eum tempora eos ipsam.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(124,2,6,'2025-09-20 11:00:10',30,'Filling','scheduled','Iure dolorum perspiciatis qui possimus iure.','Numquam reiciendis alias beatae quae. Sit quisquam deserunt et facilis animi iusto ut. Veniam omnis delectus fuga dolores. Error rem fugiat dolor fuga fuga excepturi.',NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(125,44,3,'2025-07-26 12:01:00',15,'Filling','cancelled','Blanditiis sunt aut non reprehenderit iste.',NULL,'Dicta libero tempore et provident earum omnis amet.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(126,45,7,'2025-10-13 04:00:38',45,'Extraction','scheduled','Sunt expedita illum nihil ea.',NULL,NULL,NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(127,9,3,'2025-09-22 09:04:00',30,'Consultation','cancelled','Odio minima expedita et facere neque aperiam asperiores.',NULL,'Dignissimos veritatis debitis non qui.',NULL,'2025-08-03 19:07:45','2025-08-03 19:07:45'),(128,40,7,'2025-08-20 16:00:00',45,'Consultation','confirmed','Soluta non quod mollitia voluptatibus blanditiis.',NULL,NULL,'[{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-05T04:57:15.269752Z\",\"user_id\":5}]','2025-08-03 19:07:46','2025-08-04 20:57:15'),(129,46,7,'2025-08-21 10:01:09',15,'Check-up','scheduled','Et perferendis quasi aut aut voluptas.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(130,27,3,'2025-07-08 17:33:20',15,'Consultation','cancelled','Consectetur accusantium et provident molestias dolore.',NULL,'Corporis praesentium fugiat vel pariatur ut doloribus maiores.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(131,15,7,'2025-06-27 11:02:24',15,'Check-up','no_show','Minima est praesentium quia maiores nisi.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(132,42,3,'2025-07-08 19:30:17',45,'Check-up','completed','Impedit fuga minus ullam delectus vitae.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(133,28,7,'2025-05-24 16:28:02',45,'Filling','cancelled','Libero quia impedit quo tempore.','Corrupti quis maiores nam enim omnis quia recusandae. Ipsum earum rerum quis. Quisquam quia maxime omnis ut magni velit doloribus accusamus. Libero voluptates officia voluptatem aut reprehenderit in.','Maxime sunt repellat laboriosam aut eaque distinctio.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(134,32,3,'2025-10-15 01:28:09',45,'Extraction','scheduled','Et ex tenetur excepturi ut.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(135,14,7,'2025-10-24 08:14:49',30,'Check-up','confirmed','Accusamus omnis consequatur quibusdam id aut.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(136,25,7,'2025-09-23 19:32:39',15,'Check-up','confirmed','Velit est minima voluptatem nulla quibusdam.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(137,33,3,'2025-09-05 22:58:14',45,'Cleaning','scheduled','Occaecati commodi non culpa impedit.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(138,25,7,'2025-09-03 04:48:58',60,'Extraction','scheduled','Alias aliquam est id in.','Iure iste asperiores incidunt modi ullam nihil perferendis repudiandae. Excepturi numquam corrupti in inventore saepe. Ut asperiores vel mollitia non dolorem ab reprehenderit. Sit a dolorum ut voluptatum. Assumenda qui quae iste dicta autem et.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(139,3,3,'2025-08-10 11:25:37',15,'Filling','scheduled','Unde velit dolor rerum nisi dolorum molestiae.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(140,31,3,'2025-06-10 21:16:25',45,'Check-up','cancelled','Dignissimos sunt est vel labore rerum.','Enim autem aperiam non culpa deserunt dolores. Inventore expedita maxime et tempore aut.','Qui qui modi et beatae quod.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(141,47,7,'2025-09-27 15:42:17',60,'Cleaning','scheduled','Non explicabo vitae consequuntur itaque modi dolore.','Aut tempora ut ut quaerat. Tempora nam qui et perspiciatis ut beatae hic. Dolor officiis et harum dolore quia ullam nesciunt et. Expedita tenetur dolorem et saepe.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(142,42,3,'2025-08-12 04:49:37',45,'Check-up','confirmed','Quidem ut et dolores dolores possimus ut facilis iste.','Quibusdam excepturi doloribus itaque est. Deserunt dolor consequatur aliquam dolores officiis enim facilis. Earum ex quaerat sint labore.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(143,49,6,'2025-05-26 01:09:13',30,'Check-up','cancelled','Consectetur facere sed dolores vero qui numquam explicabo.',NULL,'Dolorem consectetur fuga aut ratione vel autem quas.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(144,11,6,'2025-10-14 01:29:49',45,'Check-up','scheduled','Quis quia ipsum alias et.','Aut saepe consequatur nemo magni aut dignissimos. Voluptatem aut laboriosam eos inventore cum iusto aut. Laborum cupiditate error aut nemo veniam est. Eum eum quod eligendi possimus facilis dolor.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(145,38,6,'2025-09-02 14:28:12',45,'Extraction','confirmed','Alias harum fugiat molestiae quia.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(146,50,3,'2025-07-13 06:41:22',60,'Cleaning','completed','Aut consectetur sit laborum dicta doloribus.','Nobis nihil enim quis vel. Quo illo veritatis aut ut culpa et repellendus. Aut ut sunt officiis esse.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(147,2,3,'2025-08-16 06:49:59',15,'Consultation','cancelled','Eos sit praesentium deleniti nulla doloremque quidem.','Culpa vel saepe distinctio. Provident qui voluptates dolorem doloremque dolor laudantium. Nesciunt fuga voluptatem enim asperiores. Tempore assumenda quasi earum maxime voluptates nemo ad pariatur.','Voluptates aut dolore illo quos.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(148,23,6,'2025-05-14 08:34:18',30,'Check-up','no_show','Non atque quaerat voluptatibus dolor.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(149,13,3,'2025-06-20 20:52:40',60,'Filling','no_show','Consectetur numquam eum magnam et.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(150,30,7,'2025-10-14 03:26:54',15,'Filling','scheduled','Eum laudantium qui molestiae ut aut officiis dolor quibusdam.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(151,44,3,'2025-06-15 00:38:00',15,'Filling','completed','Fugit et soluta qui.','Rerum impedit praesentium est modi similique consectetur quaerat. Voluptatem ullam ipsam quia. Exercitationem voluptatem sequi dolor iusto.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(152,17,7,'2025-09-25 14:26:57',45,'Consultation','cancelled','Et omnis qui modi.','Saepe excepturi alias quo molestiae dolores suscipit incidunt. Perferendis quasi rerum totam quidem. Tempore fuga ut id aliquid aut autem. Nemo quaerat eaque nesciunt eveniet debitis eum.','Est temporibus reprehenderit praesentium nemo.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(153,9,6,'2025-08-13 15:00:00',45,'Consultation','scheduled','Numquam accusantium eum iure repudiandae.',NULL,NULL,'[{\"action\":\"Updated by receptionist\\/admin\",\"data\":[],\"timestamp\":\"2025-08-05T04:52:58.241281Z\",\"user_id\":5}]','2025-08-03 19:07:46','2025-08-04 20:52:58'),(154,10,6,'2025-10-09 12:00:23',45,'Extraction','scheduled','Eligendi iste labore voluptas assumenda placeat.','Molestiae atque placeat ratione consequatur laborum corporis nihil. Qui libero cumque quaerat aspernatur in deleniti voluptatem. Id sapiente et laboriosam et libero corporis deserunt rerum. Aut error sit veniam ipsa consequatur qui.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(155,7,6,'2025-07-30 13:51:25',15,'Cleaning','cancelled','Exercitationem reiciendis ut quo repudiandae suscipit necessitatibus.',NULL,'Autem tempora aliquam in ipsa delectus est sunt.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(156,28,7,'2025-05-10 11:25:11',15,'Check-up','no_show','Voluptate repellat nostrum non mollitia ducimus nobis.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(157,23,6,'2025-07-29 20:36:42',30,'Filling','cancelled','Id magnam ut nihil minus rerum adipisci qui.',NULL,'Reprehenderit cum molestias iusto ipsa veniam.',NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(158,29,7,'2025-09-14 15:05:34',15,'Consultation','confirmed','Ut est atque dolor non.',NULL,NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(159,47,7,'2025-09-19 14:16:36',15,'Extraction','scheduled','Repellat expedita sit perferendis.','Voluptate sapiente aliquam autem. Sint non illum distinctio fugit est culpa necessitatibus. Nam aut rerum deleniti officiis deleniti esse at. Enim necessitatibus velit neque sint dolores inventore quibusdam.',NULL,NULL,'2025-08-03 19:07:46','2025-08-03 19:07:46'),(160,2,6,'2025-10-30 00:50:03',30,'Check-up','cancelled','Esse dolore minus ullam impedit.','Culpa quia nam et assumenda cupiditate explicabo quasi. Quia eius id sit quia sed rerum adipisci. Nostrum ducimus non optio odit. Et et iure et.','Autem ratione nam veritatis ullam placeat.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(161,27,6,'2025-07-26 20:59:32',60,'Check-up','no_show','Alias voluptatibus tenetur distinctio culpa veniam at voluptatem non.',NULL,NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(162,31,7,'2025-10-17 16:00:48',45,'Extraction','confirmed','Minus alias itaque minima omnis aut illum.',NULL,NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(163,26,7,'2025-06-01 15:00:51',15,'Cleaning','completed','Animi et ab et quia explicabo maiores minus dolorem.',NULL,NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(164,34,6,'2025-07-12 21:33:39',30,'Cleaning','cancelled','Nostrum id assumenda veritatis repellat autem sint.',NULL,'Facere aspernatur inventore distinctio et minus iste.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(165,24,6,'2025-05-10 10:49:34',15,'Extraction','completed','Ad est ab ut dolores similique praesentium et.',NULL,NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(166,26,6,'2025-10-25 17:27:44',45,'Check-up','scheduled','Quasi non dolorum temporibus et.','Tenetur sit ut rerum et maxime facilis. Delectus autem ullam enim blanditiis voluptates consequatur. Temporibus dolorem eius qui et eveniet. Impedit animi quam qui rerum cumque.',NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(167,12,3,'2025-10-13 22:02:20',45,'Extraction','cancelled','Esse beatae dicta repudiandae inventore.','Ipsum voluptatum est suscipit ut consequatur. Et magnam molestiae est quae magni. Dolor et accusantium numquam est itaque. Reiciendis iure architecto autem ut sed. Architecto aut quisquam quisquam ut est.','Laudantium culpa est autem velit quo.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(168,12,3,'2025-06-10 11:36:29',60,'Cleaning','cancelled','Ipsum nostrum et porro.',NULL,'Eos velit aut quia et aut aut perspiciatis.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(169,33,6,'2025-07-09 12:48:35',30,'Extraction','cancelled','Neque repellat id deleniti.',NULL,'Dolor rerum numquam consequatur dolores.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(170,23,7,'2025-10-18 02:41:42',15,'Cleaning','cancelled','Molestiae qui velit animi minima quas alias non.',NULL,'Dolore itaque iure excepturi facere.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(171,28,3,'2025-07-06 00:19:48',60,'Cleaning','cancelled','Qui placeat doloribus maiores perspiciatis explicabo et magni.','Et quod eos quod quo. Excepturi hic sunt eum laborum temporibus iusto ducimus. Ducimus labore incidunt corrupti aut possimus nostrum harum labore. Ratione laudantium rem aut.','Consequuntur vitae animi qui nobis.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(172,11,3,'2025-10-19 05:01:28',30,'Check-up','confirmed','Illo tempora ut rerum eum tempora consequatur.',NULL,NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(173,27,3,'2025-10-13 03:46:35',15,'Check-up','confirmed','Quis eum harum unde alias.','Laborum omnis quia voluptates nisi qui atque necessitatibus. Consequatur accusamus inventore et dignissimos et. Laboriosam recusandae in doloribus sed mollitia soluta unde ut.',NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(174,19,3,'2025-06-17 06:15:10',45,'Cleaning','cancelled','Quidem sit quia veritatis eos.','Sunt excepturi dolor aliquam qui nobis necessitatibus. Harum cum eius est rerum corporis consequuntur laudantium. Magnam nostrum autem aliquam adipisci.','Rem ut earum quisquam laudantium quidem id.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(175,49,6,'2025-08-28 17:17:32',45,'Filling','cancelled','Reprehenderit laboriosam vel possimus quo deserunt et totam.','Quis sit culpa reprehenderit. Provident fugit et nemo voluptatibus consequatur et animi ea. Sed at sunt quidem ratione.','Molestias officiis et rerum unde.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(176,15,7,'2025-05-16 06:31:09',45,'Check-up','cancelled','Porro sint sint qui quis corporis aut cumque.','Odit eius omnis reprehenderit asperiores distinctio occaecati. Dolor labore accusantium id aut tempora. Perspiciatis saepe recusandae commodi praesentium voluptatibus repellendus placeat. Adipisci odio esse a qui.','Quo ipsam adipisci dolorem est quod.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(177,17,3,'2025-06-16 19:33:41',30,'Cleaning','no_show','Optio optio quas quia velit consequatur harum.',NULL,NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(178,20,6,'2025-09-11 03:50:18',15,'Cleaning','cancelled','Labore sapiente dolores reiciendis dignissimos voluptas.','Itaque velit suscipit omnis nam repellat dicta ad. Sit sit quo voluptate fugiat cum vel qui nobis. Voluptas excepturi dolorem quaerat ab eos ipsa.','Necessitatibus tenetur saepe ut et.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(179,17,3,'2025-06-23 05:02:58',45,'Filling','no_show','Quod sequi voluptatem est.','Numquam ut voluptatem excepturi similique eum odit. Enim id et temporibus porro aut assumenda ex. Quia vel iste et magni voluptatem sint nam.',NULL,NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(180,47,7,'2025-05-25 20:04:56',60,'Check-up','cancelled','Ea aut et quia vel explicabo.',NULL,'Ut omnis dignissimos dicta consectetur magni sed sequi et.',NULL,'2025-08-03 19:07:47','2025-08-03 19:07:47'),(181,27,3,'2025-09-25 19:24:48',30,'Extraction','cancelled','Impedit iste nemo id saepe occaecati est.','Modi ad dolor aliquid. Eaque ut magni in placeat culpa et quaerat aut. Qui autem exercitationem sint facilis voluptatem accusamus.','Rerum atque cum dolores quo.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(182,1,7,'2025-09-25 08:23:44',30,'Filling','scheduled','Et voluptatem debitis corporis sapiente autem ut.','Quis ipsum voluptas debitis expedita. Nobis cumque quia maxime omnis ad illo. Optio ipsum aut mollitia ad consequatur soluta. Adipisci sit sint inventore nulla illo officiis illo.',NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(183,47,6,'2025-06-23 16:00:23',15,'Filling','completed','Quo ipsum illum quos quaerat voluptate aut velit qui.',NULL,NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(184,29,7,'2025-10-05 14:35:01',15,'Cleaning','scheduled','Eos eligendi excepturi autem sint eaque ipsam nemo est.','Nesciunt tempora qui veritatis molestiae dolorem quia amet quis. Molestiae voluptatem ad deleniti facilis accusamus ut. Praesentium et non magni quasi eum perspiciatis ex.',NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(185,12,7,'2025-05-31 18:59:49',60,'Consultation','cancelled','At animi eos commodi accusamus blanditiis omnis rerum molestiae.','Voluptatem est est non quod eius temporibus dolor rem. Debitis ut illo enim dolor eveniet rem ullam. Est blanditiis nisi blanditiis quisquam optio et nam.','Id delectus minus sed.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(186,44,3,'2025-10-04 23:39:44',30,'Consultation','cancelled','Odio voluptas eaque ea consequatur suscipit unde unde ipsa.','Qui ut occaecati itaque ratione eaque accusamus qui. Vel ut nemo voluptate pariatur libero. Iure nihil beatae tenetur aut. Repellendus velit explicabo dolorum non minus quis.','Quo voluptatem accusamus debitis animi hic.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(187,42,3,'2025-06-16 18:58:55',30,'Check-up','cancelled','Sed sit omnis illo expedita omnis.','Esse minima iste repellendus temporibus quae. Dolorem qui sapiente doloribus ullam. Sed ut numquam illo in qui rerum. Ut voluptatem et sit accusantium praesentium perferendis.','Ipsa autem odit officiis labore omnis optio.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(188,18,6,'2025-08-15 08:11:07',15,'Extraction','cancelled','Rerum ex voluptas laudantium consequatur vitae omnis quia.','Odit aspernatur laboriosam dignissimos laboriosam quo. Quo repellat nobis est mollitia. Harum sint consequatur dolorem officiis consequatur recusandae voluptatem.','Omnis corporis similique repellat facere rerum adipisci autem.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(189,33,3,'2025-09-21 23:15:45',30,'Consultation','scheduled','Qui distinctio deserunt consectetur ut sed.',NULL,NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(190,3,6,'2025-07-29 06:33:46',60,'Cleaning','completed','Et saepe vitae illum repellendus laboriosam in eum repudiandae.',NULL,NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(191,22,3,'2025-06-16 02:04:38',60,'Check-up','no_show','Sit nulla porro facilis a labore similique voluptas.','Nemo sit aliquid sint omnis praesentium et quis excepturi. Deleniti quis quia placeat. Eum perspiciatis suscipit exercitationem laudantium at sed. Explicabo enim repudiandae vel unde.',NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(192,50,3,'2025-06-06 14:02:41',30,'Check-up','cancelled','Voluptatem at et vel commodi.','In assumenda alias autem et sunt. Facere qui et vel ipsam voluptas quae. Consequatur voluptatem a facilis nobis.','Qui sint quia earum possimus enim.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(193,25,3,'2025-07-15 07:07:40',60,'Extraction','completed','Est delectus necessitatibus doloremque.','Vel cum repudiandae et quo ut ea non. In illo perspiciatis dolore eius voluptatum porro qui. Earum consequatur doloremque quidem omnis magnam. Itaque autem quis omnis aliquid magni nihil error.',NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(194,18,7,'2025-09-02 01:46:35',60,'Filling','scheduled','A et commodi qui delectus ab.',NULL,NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(195,18,6,'2025-10-23 02:47:22',30,'Check-up','scheduled','Praesentium culpa soluta occaecati earum.','Velit cupiditate ea beatae officia quia. Laudantium consequatur dolores architecto aut temporibus cum omnis. Rerum est sunt voluptas eius dolorem.',NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(196,34,3,'2025-05-24 10:18:34',30,'Filling','no_show','Amet omnis rem voluptas nisi laudantium.',NULL,NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(197,30,7,'2025-06-23 05:00:37',45,'Consultation','no_show','Sit vel quisquam quia ut voluptatem quia in aut.',NULL,NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(198,30,3,'2025-09-07 07:36:35',15,'Filling','confirmed','Aliquid eos dolor enim sit in molestias qui.','Debitis velit error aut placeat quidem non veritatis voluptatem. Officiis voluptates ut ipsa voluptate voluptates. Incidunt explicabo sapiente aut consequuntur eos. Quod ipsam iusto accusantium numquam.',NULL,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(199,23,3,'2025-10-22 19:56:24',30,'Consultation','cancelled','Nisi delectus soluta quis ab reprehenderit cumque fugiat.',NULL,'Sit modi nostrum corrupti sit ut dolores sit.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(200,44,6,'2025-06-03 04:50:44',15,'Filling','cancelled','Sint dolores minus enim dignissimos expedita fugiat.',NULL,'Minus quos ipsam sunt quia ea cumque.',NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48');
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
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `unit_of_measure` varchar(255) NOT NULL,
  `quantity_in_stock` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `last_restock_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_items_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `inventory_items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_items`
--

LOCK TABLES `inventory_items` WRITE;
/*!40000 ALTER TABLE `inventory_items` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_07_30_000000_create_procedures_table',1),(2,'2025_07_31_000000_create_all_tables',1),(3,'2025_08_01_084545_create_personal_access_tokens_table',1),(4,'2025_08_03_043021_add_appointment_id_to_treatment_records_table',1),(5,'2025_08_03_043133_add_modification_history_to_appointments_table',1),(6,'2025_08_03_115823_create_permission_tables',1);
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
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',8),(2,'App\\Models\\User',3),(2,'App\\Models\\User',6),(2,'App\\Models\\User',7),(3,'App\\Models\\User',1),(3,'App\\Models\\User',2),(3,'App\\Models\\User',5),(4,'App\\Models\\User',4);
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
INSERT INTO `patients` VALUES (1,'PAT20250001','Moses','Hand','2008-05-24','female','1457 Erling Terrace\nTiffanyborough, VA 94628-2430','726-294-4356','goodwin.ludie@example.net','Gilda Effertz','907.879.9693','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(2,'PAT20250002','Rubie','Kozey','1998-11-07','other','6748 Gibson Viaduct Apt. 204\nNorth Anahi, FL 82856','678-777-5545','jo37@example.com','Dr. Marco Larkin Sr.','520-845-6682','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(3,'PAT20250003','Rosemary','Bauch','2022-10-07','other','674 Casper Station\nMcClureton, OK 54246','269.966.7227','ihayes@example.net','Prof. Wilfredo Sawayn','667-937-9349','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(4,'PAT20250004','Eliezer','Goodwin','2024-08-31','female','6158 Morissette Inlet Apt. 269\nPort Jacynthe, UT 00822','+1-954-555-7965','elisha.beahan@example.com','Mr. Carter Reichel','+1-228-492-4548','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(5,'PAT20250005','Larry','Cassin','1994-02-02','other','688 Skiles Fields\nKerlukechester, SC 35761','+1.731.392.4636','zoie.heidenreich@example.net','Linda Watsica PhD','(952) 530-7397','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(6,'PAT20250006','Maiya','Marquardt','2024-11-02','male','10855 Anderson Well\nNew Mckenzieton, AL 68842','1-239-732-7122','doyle.nigel@example.net','Willy Mayert PhD','(859) 654-3681','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(7,'PAT20250007','Alysa','Pouros','1990-10-03','other','74143 Modesta Lakes\nLake Jeradfort, AK 45804','1-484-799-1993','cristian.parker@example.net','Shemar Williamson','+1-615-416-8026','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(8,'PAT20250008','Sheila','Stehr','1991-10-24','other','680 Anya Keys Suite 478\nElissaside, DE 26890','1-564-485-4630','reina.jaskolski@example.org','Mr. Sid Blanda','941.462.3781','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(9,'PAT20250009','Gabriella','Abbott','2002-01-10','other','678 Larson Islands Apt. 776\nWest Mikeville, SD 52931','(563) 478-4400','schmeler.colin@example.org','Dr. Jamey Abshire I','323-272-1529','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(10,'PAT20250010','Pinkie','Koepp','1994-05-18','male','19511 Lauretta Squares\nEttieland, OH 58409','919.992.4709','kuhlman.dora@example.net','Johann Pfannerstill','+1-917-273-8810','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(11,'PAT20250011','Kaleb','Ziemann','2000-10-24','male','881 Hills Ville\nTwilaton, MN 86322','1-743-962-9611','bjohnson@example.org','Dr. Buddy Pacocha PhD','+15644314072','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(12,'PAT20250012','Jarrod','Schumm','2022-01-03','male','909 Bartell Flat\nLake Colbyshire, KS 05397-9820','442-946-8508','margot.cummings@example.org','Luther Schoen','+1-618-869-8232','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(13,'PAT20250013','Lavada','Rice','1998-07-26','male','71637 Freeda Island Apt. 654\nPort Denabury, CA 51240-6531','+1-980-705-2647','stevie14@example.org','Prof. Oceane Rempel DDS','+1-580-357-6340','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(14,'PAT20250014','Felipa','Mohr','2022-10-18','female','3168 Stehr Prairie Suite 590\nEast Albin, SD 27740-6249','+1.561.301.9905','alysson15@example.org','Tremayne Ondricka','(260) 665-1678','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(15,'PAT20250015','Dana','Gusikowski','2014-12-07','male','44696 Alexanne Ford\nSouth Ruthside, GA 15783','1-559-985-0279','pgorczany@example.org','Layne Dach','734-537-9384','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(16,'PAT20250016','Keenan','Mitchell','1983-04-01','female','3934 Brendon Mall\nPort Lillyton, NH 06143-2292','949-460-9871','rosalinda.glover@example.org','Mrs. Joyce Block III','+1 (813) 996-9579','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(17,'PAT20250017','Tomasa','Abernathy','2011-02-24','female','6531 Jaleel Springs Suite 378\nGustavechester, NH 91220-3553','1-805-832-1413','upton.deja@example.org','Valentina Wiza PhD','+1.435.276.1630','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(18,'PAT20250018','Rosalia','Smith','2018-12-26','female','368 Lemke Street Apt. 378\nEast Berneicetown, NE 75961-7434','845.454.3359','kariane.schumm@example.net','Cristopher Skiles','1-510-757-0284','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(19,'PAT20250019','Angelita','Roberts','2004-12-28','male','297 Rutherford Lodge\nPort Ayanachester, MS 96977','772-643-3737','feeney.devan@example.com','Clay Donnelly','+19254841045','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(20,'PAT20250020','Francesco','Osinski','1980-09-21','other','6901 Ferry Ford Suite 560\nNew Elvishaven, VA 24983-4879','+1-425-287-9309','kprohaska@example.org','Kailyn Runte','1-816-987-9814','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(21,'PAT20250021','Lorenzo','Shields','2024-06-19','other','7524 Dorian Valleys\nO\'Konport, VT 80337','(667) 232-3174','fswaniawski@example.org','Trystan Hill IV','1-872-624-4869','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(22,'PAT20250022','Frederic','Kovacek','1974-06-19','male','598 Rodrick Ridge\nLake Pinkview, ID 91588-5652','209-407-0477','noelia99@example.net','Prof. Dave Satterfield MD','+1-838-328-4017','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(23,'PAT20250023','Brielle','Flatley','1982-10-25','other','414 Santina Corners Apt. 334\nHalvorsonshire, SD 21217','1-575-745-7556','krajcik.chelsea@example.net','Prof. Willard Turner','1-980-677-2721','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(24,'PAT20250024','Hertha','Altenwerth','1977-05-09','female','394 Reilly Manors Apt. 374\nLake Prestonview, CO 03126-2730','+15402100962','jimmie33@example.org','Mr. Junius Pacocha DDS','(504) 372-7155','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(25,'PAT20250025','Eryn','Reilly','1971-03-24','other','29330 Khalid Wall\nStephanieport, NH 72820-5795','484.880.7888','elise67@example.net','Caitlyn Dibbert','+1 (859) 402-1132','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(26,'PAT20250026','Rhett','Botsford','1998-07-04','other','6211 Sawayn Curve Suite 140\nPagacberg, KS 31071-1722','+1-239-443-3922','kennith.lang@example.org','Kaycee Smith','(623) 559-9537','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:39','2025-08-03 19:07:39'),(27,'PAT20250027','Jazmin','Mohr','2015-09-06','other','70487 Madonna Circles\nHerzogville, CT 77642','1-831-668-0560','bogisich.stanley@example.org','Leo Lind','417.895.3964','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(28,'PAT20250028','Kari','Leannon','2015-07-10','male','52114 Streich Islands Apt. 965\nDestineestad, NY 05960-3176','1-272-266-7270','wilkinson.melvin@example.org','Immanuel Wilkinson','(281) 889-3403','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(29,'PAT20250029','Lilly','Waelchi','1993-09-20','male','81120 Osbaldo Canyon Suite 266\nNew Norbert, AZ 57503','+1 (626) 415-8881','tillman.merlin@example.org','Prof. Nico Ruecker','+1.325.829.1312','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(30,'PAT20250030','Fermin','Hettinger','1995-11-22','other','39519 Kutch Canyon\nPort Madyson, MA 32739-4602','+1 (515) 549-9597','samanta40@example.com','Arturo Ullrich DDS','+1-706-767-6257','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(31,'PAT20250031','Tremayne','Klein','1981-12-11','female','5075 Kris Viaduct\nEast Flossie, KY 06199','586-297-3612','sschaefer@example.net','Stephanie Huel','(838) 537-7527','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(32,'PAT20250032','Mckayla','Jacobi','2000-07-22','female','25020 Kihn Turnpike\nSheilaland, TX 08880','+1-281-644-9519','tgulgowski@example.com','Felipa Wyman','+1 (218) 739-5903','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(33,'PAT20250033','Mafalda','Muller','1995-01-24','other','26184 Lexie Meadows\nLavernstad, FL 17235','(681) 263-0738','maverick.dicki@example.com','Joany Doyle Jr.','+1-820-991-5319','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(34,'PAT20250034','Vicky','Quigley','1973-03-15','female','87961 Alejandra Ridge Suite 673\nNorth Linnietown, AL 83175-1703','+1-469-262-1068','adriana06@example.com','Reagan Ebert','+18033819176','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(35,'PAT20250035','Leora','Wunsch','2000-02-16','other','6242 Nicolas Way\nKriston, AL 76519','626.493.3133','ulises.connelly@example.org','Madeline Blanda','+1 (240) 999-0800','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(36,'PAT20250036','Destini','Douglas','2020-09-16','male','20397 Gorczany Mills Apt. 060\nWillmsview, CO 34483','+1 (678) 666-4596','jessie.windler@example.com','Prof. Davin Runolfsdottir DDS','1-319-464-1541','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(37,'PAT20250037','Anna','Ebert','2001-01-07','male','9557 Kayleigh Ports Suite 869\nNew Abdul, AK 43723','(332) 315-6804','dickinson.jed@example.org','Dr. Floyd Shanahan','(660) 369-7964','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(38,'PAT20250038','Kathlyn','Cruickshank','1974-12-18','other','915 Koss Route\nArloland, IN 72154','(505) 994-6820','marcel20@example.net','Aileen Ebert','678-952-2079','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(39,'PAT20250039','Lily','Bartell','1982-09-24','female','611 Corwin Prairie Suite 857\nEast Heber, MA 97667-1387','346-812-1352','qschuster@example.org','Mr. Rodger Hagenes','+1-423-895-5915','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(40,'PAT20250040','Gunner','Sauer','1973-08-09','male','871 Hellen Walks\nKshlerinshire, FL 16959','+1-407-285-3218','maximo53@example.org','Prof. Wilfred Hilpert','+1-314-591-2054','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(41,'PAT20250041','Olga','Durgan','1991-08-23','male','403 Jones Cliffs\nMariammouth, OR 28521','(607) 831-9579','kristofer.torphy@example.org','Mrs. Kelsi Ebert','650.419.7650','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(42,'PAT20250042','Rozella','Block','1990-04-30','male','2144 Elbert Locks\nFunkburgh, OH 23582','908.779.7216','lexus93@example.net','Eileen Towne IV','240-673-3670','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(43,'PAT20250043','Roy','Thompson','1980-07-22','female','54799 Effertz Cape\nNorth Leannton, NM 07959','678.628.1820','effertz.clint@example.net','Garrison Koepp','716.428.6039','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(44,'PAT20250044','Rosa','Schowalter','1988-11-29','female','147 Hickle Shoals\nQuentinfurt, MT 10486-3677','+1-507-417-3561','trisha.pollich@example.org','Prof. Ceasar Feest MD','479-354-5932','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(45,'PAT20250045','Issac','White','2004-03-11','female','430 Schaefer Junction\nMarvinland, NM 66710-1728','1-479-852-2986','tcorkery@example.org','Garnet Mills','1-279-702-3617','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(46,'PAT20250046','Tom','Beahan','2010-12-02','other','132 Dominic Grove\nNew Lesleyborough, WI 00186','361-563-1133','dianna74@example.net','Gino McKenzie','+1 (478) 772-1019','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(47,'PAT20250047','Iva','Kassulke','2019-02-02','other','4281 Alysa Stream\nWest Giovanniside, CO 36487-3309','1-629-298-2576','robb78@example.org','Prof. Letha Prosacco PhD','979.418.0443','family',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(48,'PAT20250048','Heather','Maggio','2015-08-03','female','740 Daphney Fort\nWolffton, SD 49622','(806) 729-7432','denesik.maurine@example.org','Matteo Fahey','1-651-656-7918','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(49,'PAT20250049','Louisa','DuBuque','1974-11-01','other','85414 Douglas Mountain\nLake Gwenfort, CT 01244','+1-920-806-2209','fannie14@example.net','Benjamin Konopelski','1-470-291-8776','friend',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(50,'PAT20250050','Cleta','Wisoky','2011-03-10','male','5598 Miracle Unions Apt. 392\nNew Erlingborough, CO 29011-2441','775-761-2215','felicity01@example.org','Candido Sporer','1-570-873-2796','colleague',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-08-03 19:07:40','2025-08-03 19:07:40');
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
INSERT INTO `permissions` VALUES (1,'user-list','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(2,'user-create','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(3,'user-edit','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(4,'user-delete','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(5,'role-list','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(6,'role-create','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(7,'role-edit','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(8,'role-delete','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(9,'permission-list','web','2025-08-03 19:07:34','2025-08-03 19:07:34'),(10,'permission-create','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(11,'permission-edit','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(12,'permission-delete','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(13,'patient-list','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(14,'patient-create','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(15,'patient-edit','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(16,'patient-delete','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(17,'appointment-list','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(18,'appointment-create','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(19,'appointment-edit','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(20,'appointment-delete','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(21,'treatment-plan-list','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(22,'treatment-plan-create','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(23,'treatment-plan-edit','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(24,'treatment-plan-delete','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(25,'invoice-list','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(26,'invoice-create','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(27,'invoice-edit','web','2025-08-03 19:07:35','2025-08-03 19:07:35'),(28,'invoice-delete','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(29,'procedure-list','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(30,'procedure-create','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(31,'procedure-edit','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(32,'procedure-delete','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(33,'inventory-list','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(34,'inventory-create','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(35,'inventory-edit','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(36,'inventory-delete','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(37,'report-list','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(38,'appointment-calendar-view','web','2025-08-04 02:00:10','2025-08-04 02:00:10');
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
INSERT INTO `procedures` VALUES (1,'perferendis Extraction','Voluptates qui quae quae reprehenderit at saepe magnam.',980.54,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(2,'dolorem Filling','Expedita temporibus asperiores consectetur sequi quia aut.',646.47,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(3,'voluptatem Cleaning','Tempore consequuntur amet ad quis illum sit.',942.17,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(4,'dolor Cleaning','Enim non officiis voluptates magnam non veniam nihil.',182.34,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(5,'quaerat Crown','Voluptatum id quia voluptas commodi autem.',627.95,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(6,'est Extraction','Tenetur perferendis sit explicabo nemo quisquam molestiae.',180.75,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(7,'ducimus Root Canal','Maxime illum similique ut aliquid est consequatur assumenda.',515.32,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(8,'in Crown','Vero dolor magni porro maxime vitae reiciendis in.',773.23,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(9,'ut Crown','Id ratione cumque ea.',418.31,'2025-08-03 19:07:40','2025-08-03 19:07:40'),(10,'voluptate Root Canal','Quia et maiores perferendis et adipisci debitis.',628.67,'2025-08-03 19:07:40','2025-08-03 19:07:40');
/*!40000 ALTER TABLE `procedures` ENABLE KEYS */;
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
INSERT INTO `role_has_permissions` VALUES (1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(10,3),(11,3),(12,3),(13,1),(13,2),(13,3),(13,4),(14,1),(14,2),(14,3),(15,1),(15,2),(15,3),(16,2),(16,3),(17,1),(17,2),(17,3),(17,4),(18,1),(18,2),(18,3),(19,1),(19,2),(19,3),(20,2),(20,3),(21,2),(21,3),(22,2),(22,3),(23,2),(23,3),(24,2),(24,3),(25,1),(25,2),(25,3),(26,1),(26,2),(26,3),(27,1),(27,2),(27,3),(28,3),(29,2),(29,3),(30,2),(30,3),(31,2),(31,3),(32,2),(32,3),(33,2),(33,3),(34,2),(34,3),(35,2),(35,3),(36,2),(36,3),(37,2),(37,3),(38,1),(38,2),(38,3);
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
INSERT INTO `roles` VALUES (1,'receptionist','web','2025-08-03 19:07:36','2025-08-03 19:07:36'),(2,'dentist','web','2025-08-03 19:07:37','2025-08-03 19:07:37'),(3,'administrator','web','2025-08-03 19:07:37','2025-08-03 19:07:37'),(4,'viewer','web','2025-08-03 19:07:37','2025-08-03 19:07:37');
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
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
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
INSERT INTO `treatment_plan_procedure` VALUES (1,10),(2,2),(2,7),(2,8),(3,2),(3,6),(4,2),(5,10),(6,2),(6,5),(6,9),(7,2),(7,5),(7,10),(8,5),(9,9),(9,10),(10,9),(10,10),(11,5),(11,6),(11,8),(12,2),(12,10),(13,1),(13,4),(14,4),(15,1),(15,9),(15,10),(16,3),(16,6),(17,3),(17,6),(17,9),(18,7),(19,3),(19,4),(19,9),(20,4),(20,6),(21,1),(21,2),(21,10),(22,4),(22,6),(23,9),(23,10),(24,2),(24,7),(24,9),(25,1),(25,2),(25,5),(26,3),(26,8),(26,9),(27,2),(27,7),(27,9),(28,3),(28,8),(28,9),(29,5),(30,5),(30,7),(31,2),(31,3);
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_plans`
--

LOCK TABLES `treatment_plans` WRITE;
/*!40000 ALTER TABLE `treatment_plans` DISABLE KEYS */;
INSERT INTO `treatment_plans` VALUES (1,30,3,'Molestiae dolorum est aut sequi.','Possimus ipsa vitae delectus. Necessitatibus non vitae fuga sapiente voluptatum. Ab quas corporis vel aspernatur modi laborum rerum.',3115.71,10,'medium','proposed',NULL,NULL,NULL,'Deserunt veritatis explicabo corporis repellat commodi ut. Et dolorem at qui ut quia officia. Aut sed molestiae sint esse sit labore delectus.','Qui sed reiciendis sunt voluptatem quibusdam aliquid. Magnam enim qui quo quibusdam voluptatum. Aut quo repellendus mollitia in reprehenderit et et.','Cum voluptas laudantium similique possimus ex. Atque voluptas numquam in in minus. Enim molestiae aut sit sed magnam exercitationem. Dolor illum labore alias aut consequuntur.',NULL,0,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(2,17,7,'Necessitatibus ea nemo quis.','Praesentium sed corrupti inventore officiis et sunt. Quod error a possimus.',1106.10,8,'medium','patient_approved',NULL,NULL,NULL,'Nihil maiores assumenda distinctio et deleniti qui aliquid. Sunt voluptatem nisi sint iure ipsa. Id nesciunt optio voluptas deleniti sed itaque consectetur.','Accusamus voluptas odio dolorem ut id voluptatem. Mollitia inventore est asperiores. Consequuntur nihil aliquam et quam. Iste ipsum sit magnam est eos dolor.','Ut officia repellendus eos consequatur. Nam molestiae dicta fugit sint voluptatibus repellendus. Ad temporibus quos praesentium vel autem. Laboriosam reprehenderit voluptatem quo. Ducimus rerum molestiae aut vero sequi qui eos.',NULL,0,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(3,40,7,'Vero sunt autem.','Quidem laboriosam aspernatur repellat sunt quis laudantium impedit. Rerum recusandae sint aliquid dolores maxime saepe quibusdam.',2413.46,7,'urgent','completed',NULL,NULL,NULL,'Vero ut rem tempora error. Natus ex qui quia modi vel inventore aperiam.','Qui maiores ea aut ex voluptatem. Corrupti et libero id aspernatur iusto. Tenetur libero cum placeat fugiat.','Itaque autem velit beatae et maiores. Temporibus possimus iusto atque.',NULL,0,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(4,41,7,'Nostrum quos aut.','Autem ipsa numquam amet. Voluptatibus delectus reprehenderit repellendus.',3755.66,7,'low','in_progress',NULL,NULL,NULL,'Fuga vel sit non quia ut. Dolorum et deserunt aperiam voluptatem. Sed temporibus corrupti quia quaerat quod. Voluptas nisi unde voluptatum quo perferendis.','Et qui necessitatibus quod quam vel modi. Qui quis omnis sint iste laudantium aut autem.','Ab error asperiores quia inventore et ipsa. Et ea molestias ullam harum impedit quo vel mollitia. Exercitationem ut quia esse consequatur ut ut cumque.',NULL,0,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(5,26,7,'Laudantium molestiae nemo velit in.','Quod sit voluptatem quas non et. Harum et sapiente ab quasi qui.',1467.24,4,'low','patient_approved',NULL,NULL,NULL,'Nostrum voluptate facere voluptates distinctio quae vero. Harum officia et aut provident cupiditate. Nulla iusto numquam ut rem.','Ratione officia dolores sunt soluta distinctio cupiditate. Molestiae officia enim deserunt est quisquam sunt. Quae totam maxime aut in ullam dolor. Vero similique ut vero veniam.','Velit perspiciatis et aut pariatur alias accusantium. Dicta optio blanditiis neque architecto ut adipisci. Animi quia asperiores praesentium earum ad.',NULL,0,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(6,19,7,'Sed quis commodi corrupti.','Vero est quam repudiandae aut. Earum et vel corrupti nulla sint aperiam et.',4101.13,5,'high','cancelled',NULL,NULL,NULL,'Molestias nihil ut dignissimos saepe vel. Sunt qui repellendus a. Omnis rerum eius laboriosam quidem perspiciatis. Est ratione perferendis eveniet similique labore iusto.','Minus alias odit pariatur architecto. Fugit qui sit aliquid eaque ut ipsum sed. Officiis distinctio vel aut ut ratione sunt odio. Amet eum qui est et voluptatem et unde. Cumque aut provident dolorum illum nobis.','Consectetur officiis neque voluptatem quo aliquam velit. Omnis reiciendis vel corporis laborum. Velit itaque et qui assumenda.',NULL,0,NULL,'2025-08-03 19:07:48','2025-08-03 19:07:48'),(7,23,6,'Voluptatem sit.','Alias aut non iste dolorum saepe. Est rerum atque sed expedita aut iusto. Illum occaecati esse voluptatibus officiis.',4703.00,6,'medium','patient_approved',NULL,NULL,NULL,'Non velit nostrum rem similique eligendi nostrum. Consectetur qui sunt quod dolorem. Occaecati qui officia architecto qui voluptatem similique deserunt. Qui distinctio harum qui veniam quos laudantium tempore.','Corporis enim in sed ea dolore provident alias. Autem enim dicta quibusdam.','Qui sunt ut dolores sunt et earum. Perferendis velit sed quasi rerum quos. Ut eligendi saepe voluptates libero. Vitae consequatur rerum ut.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(8,10,7,'Maxime aperiam ducimus voluptatem.','Voluptatem amet sed ducimus sed reiciendis doloribus tempora. Repellat aut ratione ipsam quasi aut sint. Neque aspernatur ducimus fuga eius molestias exercitationem.',3552.49,7,'low','in_progress',NULL,NULL,NULL,'Distinctio nulla doloribus quod fuga perferendis. Optio minima et consequuntur ipsam at expedita repellat. Architecto in sed voluptas sit. Facere aperiam iste tempore velit.','Est aut voluptates consequatur dolorem. Consequuntur libero amet consequuntur perferendis. Saepe quas ut provident id non eaque occaecati quia. Rerum laborum accusantium sed minus harum quos.','Optio et dignissimos quia qui. Voluptatem doloribus consequatur similique animi. Eius itaque ea quo at non consequatur.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(9,10,7,'Adipisci non et.','Ut corporis quis voluptatem voluptas ea qui. Rem aspernatur nesciunt qui et vero unde porro. Ut dolor et quia reprehenderit cupiditate ut sequi nesciunt.',4803.61,4,'low','proposed',NULL,NULL,NULL,'Quod reiciendis nihil culpa nemo quibusdam. Reiciendis consequatur blanditiis dignissimos porro. Velit et autem et a labore suscipit aspernatur deserunt.','Aut optio eius fuga rerum voluptas. Aut cumque ipsa quod animi. Dolor quo quisquam ex eius eligendi pariatur.','In incidunt non qui dolores ea accusantium ducimus. Qui consequatur et dolore distinctio aspernatur minima. Consequatur assumenda error ea ut.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(10,2,6,'Fuga voluptates minima.','Eos voluptatem quis quas nisi. Facilis nemo ab aspernatur quisquam est omnis. Aut quia totam dolor soluta placeat ut.',2441.19,1,'medium','patient_approved',NULL,NULL,NULL,'Officiis velit et aperiam ipsa officiis necessitatibus et commodi. Quo officia dolor rerum qui. Aut sunt ab facere dolores. Sunt laboriosam voluptatem quae illum et in.','Odio sint animi provident. Autem voluptatem aliquid distinctio adipisci. Aut officia quisquam est a pariatur dolor et ducimus.','Sed libero harum quod ipsam. Et quo consectetur est distinctio velit.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(11,24,7,'Porro omnis qui aut.','Autem laborum quia aspernatur dolor maiores consequatur. Impedit dicta veritatis rem perspiciatis et perspiciatis. Ut sint laboriosam repudiandae sapiente corrupti.',1347.53,4,'medium','in_progress',NULL,NULL,NULL,'Autem sunt labore maxime non ut. Asperiores non consequatur laborum omnis quis odio. Cumque incidunt qui delectus.','Sequi molestiae corrupti eos temporibus mollitia ratione maxime est. Quas laborum et dolor animi recusandae. Et sed magnam quidem et ea id.','Temporibus voluptatem eaque explicabo. Consequuntur ipsum vero velit nihil provident. Veniam quia consequatur voluptas. Rerum recusandae eum in maiores in mollitia.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(12,47,6,'Exercitationem sint maiores.','Accusamus ex est voluptas similique rem eos. Dolores voluptas consequatur at. At ex aut quaerat quae ratione culpa.',481.24,10,'urgent','completed',NULL,NULL,NULL,'Iste a omnis voluptas ut. Dicta ea illo quo enim. Ut sint veritatis quas.','Necessitatibus veniam et eos tempora assumenda. Neque excepturi qui illum ut. Aliquid aspernatur iure sapiente quia ratione deleniti incidunt.','Ea similique atque deleniti enim. Atque facilis voluptate et repellat. Tempore molestias consequuntur sed fugit vitae quam molestiae.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(13,22,6,'Magnam voluptatem modi ex.','Ipsam consectetur dolor velit qui quas voluptatem tempore. Officiis natus fuga reprehenderit nihil et et.',365.08,9,'low','proposed',NULL,NULL,NULL,'Ratione quod quo iure sunt aliquam. Ipsa dolor reprehenderit nobis dignissimos. Officia cupiditate delectus molestiae in. Accusantium iure officiis labore iste dolorum quisquam et. Quas voluptatem distinctio quae ullam possimus similique recusandae.','Quam quas qui est deserunt voluptatem. Numquam minima nobis quis dolorem aperiam. Id laborum ea suscipit placeat. Et eaque vitae sed tempore error quam iusto.','Id aliquam et quam tempore sequi. Ad facere ut ipsam ut cumque ullam reprehenderit soluta. Est esse explicabo qui eos est consectetur. Eum beatae placeat totam nihil veniam magni.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(14,2,3,'Dolor officiis.','Tempora omnis repellat saepe nihil. Autem nemo velit blanditiis natus temporibus id qui. Autem velit aut cupiditate dolor sit quia laborum.',822.58,1,'urgent','completed',NULL,NULL,NULL,'Rem fugit iure similique est eius voluptatem. Aut aut dolorem ab tempore id qui. Molestiae magnam magni non debitis. Voluptas non assumenda dolor voluptatem earum.','Eos est aspernatur placeat quaerat nobis eum iure. Autem impedit ea laborum soluta fugiat blanditiis sequi quos. Laudantium voluptate nostrum fugiat in amet tempore. Repellendus quidem quia dolore molestiae officia.','Optio impedit ut ipsum voluptatem. Ut assumenda et hic dolores commodi. Doloribus est deleniti magnam ut aut assumenda voluptas cupiditate.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(15,37,3,'Dolor labore nemo rerum.','Et veritatis laborum ipsum inventore labore. Et adipisci qui velit.',981.74,2,'medium','in_progress',NULL,NULL,NULL,'Dolor doloremque voluptas nulla dolorum tenetur ut rerum odit. Et sunt optio voluptates aperiam eveniet provident vero. Molestiae aut ea libero dolor cumque illum qui quis. Fugit fugiat cupiditate nesciunt quod.','Voluptate a quia quia corrupti itaque sed ducimus provident. Voluptas adipisci aut sit voluptas nostrum quos. Iure illum quos placeat reiciendis ipsam facilis magnam.','Non eos explicabo aliquid impedit commodi. Earum sed cum pariatur. Explicabo aut hic quia amet velit est id.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(16,42,3,'Ut ipsum eos quasi.','Quasi vitae officiis tempore et. Voluptas optio debitis similique magni ea amet magnam officia.',1697.14,3,'urgent','cancelled',NULL,NULL,NULL,'Unde praesentium deleniti non iure. Odio corporis ut sequi a. Ducimus assumenda dolores adipisci quia ex ut. Qui est atque veritatis molestiae quae molestiae.','Facere dolorem debitis in aut qui et. Deleniti molestiae id earum natus. Commodi sit facere et minus et quisquam illum quo. Alias laborum pariatur aperiam provident ut.','Distinctio repellendus ut exercitationem iusto cupiditate voluptatem modi. Et enim excepturi impedit voluptatibus.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(17,42,6,'Modi assumenda minima vero.','Atque consequuntur sed dicta harum. Qui voluptas consequatur qui consequatur repellendus. Doloribus non quas ea quia officiis.',574.02,10,'low','in_progress',NULL,NULL,NULL,'Quia debitis ut omnis placeat et doloremque tenetur. Illum officia quis quia nihil minima ducimus recusandae. Ut repudiandae perferendis ut aut reiciendis officiis. Ut voluptas eum eum atque labore quas.','Assumenda consequatur et numquam facilis nihil. Voluptatum voluptatibus inventore et qui aut sit consequatur consequuntur. Consectetur asperiores veritatis reprehenderit porro aperiam labore. Impedit sequi est consectetur cumque consectetur qui numquam.','Enim est eaque excepturi iusto. Fugit excepturi aut nobis iusto quo.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(18,37,6,'Eum aliquid velit quia.','Recusandae dolorum occaecati praesentium ut sunt. Excepturi eos est culpa officiis animi id. Maxime hic amet est molestiae esse aperiam dolorem.',2973.69,9,'high','proposed',NULL,NULL,NULL,'Molestias et a aut cum eaque. Facere esse possimus aliquam dolor repudiandae a autem. Natus vitae repellat ut debitis. Recusandae ut magni voluptatem sit reiciendis iure consequatur tempora. Quo exercitationem natus placeat consequatur.','Ut velit quo ut in nesciunt ex sit. Ipsam et tempora facere ut unde similique. Rerum eligendi est dolor ipsam. Excepturi libero molestias blanditiis quo.','Nesciunt recusandae rerum voluptatem tempora qui quidem tempore qui. Quasi omnis quisquam repellat. Ut fugit et pariatur sint.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(19,16,6,'Recusandae eius.','Sapiente molestias cumque quia. Sunt reiciendis amet quaerat voluptatem praesentium in.',4656.80,3,'high','in_progress',NULL,NULL,NULL,'Inventore occaecati minima aut fugiat ut reiciendis. Est praesentium aut aut quis iure sed natus. Sequi nihil sit quidem optio. Iste minima ab vel.','Animi aliquam culpa fugiat ducimus aut. Molestiae mollitia quia voluptates deserunt nihil. Qui ea veniam dignissimos libero et. Esse quos aut sed dolores quas dolor.','Facere quisquam eum illum dignissimos nam voluptatem. Assumenda autem voluptatibus qui minima. Rerum quis neque praesentium rem ut aut.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(20,12,6,'Exercitationem voluptas laudantium ex.','Qui assumenda molestias nihil qui esse recusandae. Voluptates sapiente dolores officiis facere fuga dignissimos eveniet. Et nisi quod eos minus expedita magnam.',1804.84,2,'urgent','in_progress',NULL,NULL,NULL,'Velit est veniam commodi. Necessitatibus voluptatum nulla corporis non enim eveniet. Necessitatibus aut iure voluptatem quis consequuntur consequuntur.','Atque velit deserunt facilis laboriosam nam. Iusto voluptatem sed nihil voluptatum ullam in deserunt. Iure repellat et voluptas nulla aut dignissimos reprehenderit. Ea voluptas ut aut et fugiat vel.','Architecto repellat in quae et voluptatem. Placeat expedita ipsa eos laboriosam est necessitatibus. Et sapiente aut debitis totam adipisci.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(21,9,6,'Alias beatae veritatis.','Sint rem veniam accusantium magni vitae. Sit occaecati iste ut explicabo repellat sit.',4375.06,4,'medium','in_progress',NULL,NULL,NULL,'Est qui consequatur quidem dolorem eos. Voluptates tempore sapiente blanditiis. Quos voluptates et neque molestiae. Itaque architecto consequatur quisquam molestiae temporibus non.','Qui ut quidem deleniti iure quo. Et blanditiis quo autem modi enim quas delectus vel. Molestiae sed corporis dolorem quo voluptatem quia. Officia ad consequatur optio officia quas.','Quos eum quod nihil. Fugit eos alias et odit ipsa saepe ipsa.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(22,37,7,'Dolore maiores dignissimos.','Rerum optio id est laudantium rerum cumque. Ut non dicta non laudantium quas. Incidunt dolore doloremque et aliquam reprehenderit iste.',1109.54,5,'low','proposed',NULL,NULL,NULL,'Soluta sapiente quasi aperiam sunt qui. Voluptatem numquam iure ex dolores. Explicabo neque nesciunt tenetur sed qui.','Et ut non aliquam enim. Voluptas vel aut quasi. Nihil autem est labore ullam. Et est aut numquam aliquam et sit rem.','Iste magnam provident praesentium hic expedita. Aut natus qui non odit dicta. Consequatur eum ut enim eos. Suscipit aut suscipit officia excepturi unde dolorem ullam. Dolor dolor ad accusamus ut.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(23,48,7,'Aspernatur sed ducimus.','Dicta voluptatum sit eaque sapiente. Autem harum quae voluptatem quod asperiores magnam rerum. Earum veritatis eaque dolorum beatae nihil quae.',4331.06,8,'medium','cancelled',NULL,NULL,NULL,'Incidunt aut labore quasi repudiandae doloremque labore. Expedita qui cumque modi excepturi. Perferendis beatae tempore voluptas inventore sed. Non voluptatibus ut facere enim animi rerum.','Labore non eius dolores eveniet molestiae rerum assumenda. Earum repellat aspernatur cupiditate exercitationem quo consectetur inventore. Et eos facilis dolores mollitia.','Nulla architecto pariatur placeat. Voluptas neque et doloribus adipisci laudantium consequatur veritatis quia. Repellat dolorem enim fugiat consequatur illo.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(24,24,6,'Dolorem voluptatem corrupti ab est.','Quasi inventore doloremque magnam consequatur officiis expedita tempora. Molestiae laudantium ut et quis et.',871.90,2,'low','cancelled',NULL,NULL,NULL,'Vitae accusamus eos et dolores. Id blanditiis expedita rerum corrupti deserunt ab non. Provident praesentium dolorum iusto provident repudiandae beatae. Dolores et quia aperiam cupiditate.','Laborum quia deserunt magni. Pariatur sint expedita id tempora commodi aut. Voluptatem laudantium error voluptatem voluptatibus aliquam magni.','Sed laboriosam fugit ipsa adipisci ea. Dolore non et consequuntur accusamus vero quas delectus. Minima assumenda fugit molestiae libero eos aut dolores. Veritatis nihil fugit iusto minima.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(25,29,6,'Qui delectus tempore rerum.','Quas ducimus numquam ea sunt. Explicabo quod ipsam atque et optio aspernatur.',412.30,5,'urgent','completed',NULL,NULL,NULL,'Voluptatibus mollitia delectus aspernatur. Nisi eius dolores delectus cupiditate a eius quos qui. Architecto cupiditate dolore molestiae non praesentium necessitatibus eligendi. Ex cum id sed dolorem.','Voluptate temporibus cum quis sequi quam. Quae repellendus at velit voluptas. Eum ab molestiae facere dolore commodi sed.','Eaque sit est natus. Omnis non aut optio impedit.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(26,35,6,'Voluptatum ut voluptate in.','Dolores impedit optio beatae. Occaecati iste neque et quod est voluptas fugiat.',2504.41,10,'low','cancelled',NULL,NULL,NULL,'Ut aut praesentium aut modi. Officiis rem provident deleniti aut voluptatum. Omnis fugit ut harum sit. Rem inventore qui et in in.','Alias cumque sint temporibus non quia et et. Consequatur perspiciatis occaecati incidunt non. Et aperiam accusamus dolor quam.','Rerum dolores et numquam ut iusto dicta. Porro debitis fuga totam. Iure et fugit id dolor est. Aut quo repellat vitae mollitia.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(27,22,3,'Architecto culpa hic.','Est cupiditate dolore doloribus est et fuga. Deserunt sed inventore enim totam accusantium dolores. Aut quod beatae maiores maiores rerum deleniti et.',2312.00,4,'high','in_progress',NULL,NULL,NULL,'Error est repellendus odit enim. Est magnam dicta nihil earum voluptas quia. Rerum voluptatem ipsum sapiente iste illum.','Ab sunt autem quis ab iste dolor aut. Eum et nesciunt possimus perspiciatis accusamus ipsa aut. Ut voluptatum ea pariatur blanditiis fuga non voluptatem. Sapiente molestiae asperiores corporis expedita aliquid.','Ipsam quae autem est facere nesciunt. Inventore ullam voluptatem aspernatur a quia impedit sunt. Quo eveniet sed distinctio rerum itaque quam.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(28,46,7,'Repudiandae eum excepturi.','Sed iure quisquam fugit. Et fugiat laborum et eligendi voluptas perspiciatis illo.',3299.23,10,'medium','completed',NULL,NULL,NULL,'Optio deserunt omnis eligendi delectus. Explicabo voluptas distinctio sed vel. Incidunt et nesciunt provident cupiditate. Ab provident voluptate porro doloribus doloremque distinctio.','Voluptatem nostrum voluptates et itaque qui voluptatem et soluta. Ea corrupti eum minima beatae eius hic numquam. Vel culpa aut et reprehenderit quia ut.','Qui voluptatem dolorem non eveniet quis rerum. Molestiae dignissimos quaerat nulla perspiciatis aliquam explicabo. In amet voluptas accusamus fuga sint aspernatur ullam.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(29,20,7,'Accusamus est harum fugiat.','Alias qui quis totam quidem reiciendis blanditiis libero. Dolorem dolor sed molestiae in dicta.',2961.41,3,'high','proposed',NULL,NULL,NULL,'Est autem consequatur soluta distinctio quas ut illum et. Repellendus fugit qui aspernatur occaecati aperiam minus sunt. In maxime beatae mollitia dolorem sit. Itaque voluptatem aspernatur voluptatem voluptatem.','Sint enim sint numquam corporis qui quia. Reprehenderit nemo molestiae aliquam quis debitis et. Voluptas quod reiciendis at laudantium dicta qui voluptas. Dolore dicta maxime totam non consectetur praesentium modi.','Placeat consectetur consequatur voluptatem sit voluptatibus quaerat. Et cumque nihil saepe architecto. Distinctio ipsa delectus dolor et.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(30,34,3,'Non vero maxime autem.','Hic aut unde unde ut accusantium sit. Explicabo iste maxime necessitatibus nemo ullam et rem.',3953.43,5,'high','completed',NULL,NULL,NULL,'Dolor rerum nostrum nesciunt at amet. Eos perferendis ratione sit eaque quo ut ullam. Atque voluptates sunt soluta eos quas.','Et molestias aut asperiores dolore omnis. Corporis voluptatem nihil voluptate quae repellendus. Odio aut pariatur autem et.','Eaque sit aut sapiente distinctio perspiciatis. Asperiores blanditiis magnam odit illum rerum. Veritatis non molestiae accusantium voluptatem consequuntur. Aliquid velit dolor nihil pariatur ut.',NULL,0,NULL,'2025-08-03 19:07:49','2025-08-03 19:07:49'),(31,3,6,'tuli','may tulo',1588.64,1,'low','proposed',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2025-08-03 19:14:20','2025-08-03 19:14:20');
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
INSERT INTO `treatment_record_procedure` VALUES (1,4),(1,6),(1,7),(2,2),(3,5),(4,10),(5,1),(5,6),(5,10),(6,2),(6,6),(6,7),(7,2),(7,4),(8,2),(8,9),(9,3),(9,7),(9,9),(10,8),(11,8),(11,9),(12,10),(13,3),(14,9),(15,3),(15,10),(16,7),(17,1),(18,1),(18,6),(19,7),(19,10),(20,5),(20,10),(21,2),(21,4),(21,5),(22,3),(22,5),(23,6),(24,6),(25,1),(26,2),(26,8),(27,6),(28,3),(28,7),(28,10),(29,3),(29,5),(29,10),(30,3),(30,10),(31,3),(31,5),(32,4),(32,5),(32,10),(33,6),(33,7),(33,8),(34,7),(35,7),(36,2),(36,5),(37,7),(37,8),(38,2),(38,4),(39,7),(40,1),(40,2),(40,6),(41,5),(41,10),(42,2),(42,5),(43,8),(43,9),(43,10),(44,2),(44,5),(45,1),(46,4),(46,7),(46,8),(47,2),(47,9),(48,4),(49,3),(50,10);
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
INSERT INTO `treatment_records` VALUES (1,40,6,20,'2025-04-06','Itaque natus sed eius nostrum. Voluptatem consequatur quia numquam quis. Voluptatem nam rerum voluptatem ipsum animi. Occaecati sint ea quo ex ut.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1550.17,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(2,10,6,23,'2025-06-09','Vero necessitatibus laudantium nobis. Occaecati delectus aliquid consequatur. Aut maxime earum velit ratione exercitationem. Aut est possimus id voluptas.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1400.27,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(3,2,3,19,'2024-08-29','Aperiam voluptatem iste commodi veritatis. Qui unde doloribus ducimus non et. Mollitia aut porro ullam accusamus ex consectetur et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,448.20,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(4,39,7,29,'2025-06-04','Quia animi numquam nam dolorum. Qui facilis ducimus error sit in maxime commodi in. Rerum cum id commodi in quibusdam. Voluptatem et natus dolorem cumque provident beatae. Quia porro minus ut voluptatem.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1910.81,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(5,4,6,22,'2024-12-28','Quidem porro dolorem quisquam et dolor enim harum vel. Sed amet sed sed.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,117.71,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(6,42,7,21,'2025-07-09','Nam molestiae atque dolores molestias possimus architecto delectus dolorem. Qui minima est perferendis incidunt. Animi molestiae nisi fuga odit. Dolor officia vel perferendis.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,837.94,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(7,31,6,23,'2024-08-20','Reiciendis doloremque ipsa expedita omnis odit quo. Et neque quidem aut. Corrupti sit et eius placeat sapiente ut quis.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,418.11,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(8,50,7,19,'2025-02-23','Non magnam quisquam tempore est. Ipsam omnis est laudantium illum reiciendis. Quae optio et modi nostrum alias autem. Deserunt inventore dolorum labore totam nostrum at.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,491.30,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(9,30,6,18,'2024-08-20','Et occaecati perferendis exercitationem ut occaecati. Impedit vitae libero vel accusantium consequatur saepe. Beatae cum ipsa sed quia incidunt esse aut autem. Ut facilis ad consectetur impedit.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1736.25,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(10,18,7,12,'2025-05-30','Animi voluptas praesentium et. Nihil non neque distinctio exercitationem assumenda ut. Amet architecto velit natus hic numquam sunt. Autem qui aperiam pariatur occaecati sint perspiciatis ut asperiores.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,935.43,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(11,34,6,9,'2025-03-30','Voluptatibus sed natus omnis reiciendis. Fugit culpa dolorum iure numquam harum et. Suscipit sunt quidem autem facere laudantium enim. Sit molestiae neque saepe.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,462.36,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(12,42,6,19,'2025-02-03','Minus maiores beatae et. Quia qui explicabo architecto magnam fugit alias impedit. Dolorum iusto maiores dolorem quidem quia.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1936.91,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(13,25,6,28,'2024-08-05','Quo perferendis ad ut officia aliquam non. Accusamus voluptatum et blanditiis in commodi. Occaecati sit mollitia in cumque aut.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,967.16,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(14,20,7,27,'2025-03-18','Fuga nostrum laborum et dolore nulla. Aliquam aut voluptates consequatur quidem voluptatem. Aut eveniet veniam adipisci eligendi libero nulla ipsa. Optio pariatur ea qui qui delectus recusandae laborum necessitatibus.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,188.19,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(15,43,6,21,'2025-04-01','Sit sed dolore non qui laudantium sint. Repellendus ipsam porro cupiditate blanditiis ducimus exercitationem voluptatum quia. Ut iste aspernatur sunt.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,295.75,0,'2025-08-03 19:07:50','2025-08-03 19:07:50',NULL),(16,9,6,6,'2025-04-15','Voluptatem est omnis nemo consequatur animi aspernatur sit. Est possimus ut sit sunt ut quis nostrum odit. Cumque nam in praesentium qui dolor est tempore.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,621.62,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(17,18,7,23,'2025-04-05','Vero cupiditate consequuntur autem quidem praesentium voluptatibus nesciunt. Et qui cumque non quaerat commodi ab. Molestias suscipit accusantium qui exercitationem in. Eos ut qui recusandae molestiae ducimus.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1452.97,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(18,21,3,10,'2025-06-21','Delectus dignissimos inventore eligendi animi quasi ab alias. Nam id voluptatem corporis rerum repellendus eveniet. Excepturi et aut rerum eum.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,367.58,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(19,6,6,14,'2025-06-13','Nulla libero velit animi odit voluptates necessitatibus sed. Quia atque sequi eum quos iusto ratione. Nobis velit officiis sint tenetur. Blanditiis nostrum enim ab quia.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,569.89,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(20,7,7,28,'2025-03-08','Sint eligendi quas porro cupiditate. Atque corporis facilis perspiciatis quibusdam neque nesciunt. Voluptates aperiam quia magni aut. Amet debitis facilis aliquam tenetur labore.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1394.54,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(21,6,6,15,'2024-12-12','Dolorum fugit praesentium necessitatibus placeat. Neque quas nihil eveniet eaque. Molestiae nulla odit esse. Voluptatum alias et at id quia.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1107.82,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(22,36,3,30,'2025-01-31','Sit dicta dolores ut excepturi vero nesciunt. Sed et itaque aut consequatur et rerum eum. Rerum aut iure repudiandae deserunt odit. Dolores labore eum ut aspernatur quia architecto necessitatibus. Eaque nisi aut culpa et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,411.03,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(23,8,6,14,'2024-11-03','Sunt blanditiis nesciunt debitis vel. Non occaecati molestiae ut perspiciatis. Qui accusamus voluptatem ex aut unde quia repellendus similique. Tempora laborum nostrum officia quis dicta. Eligendi cum impedit minima et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,779.23,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(24,24,6,17,'2025-03-26','Quam blanditiis ipsum deleniti minima. Voluptas esse est quod incidunt et quo ut. Beatae ipsum culpa cum est quis quia quia. Non consequatur delectus quia dolore.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1102.35,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(25,36,3,20,'2025-05-20','Perferendis sint et voluptatem blanditiis. Sit cum deserunt quas inventore qui sunt rerum. Reprehenderit omnis cupiditate ut nam. Ut recusandae omnis est quia occaecati ex explicabo.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1703.36,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(26,20,3,12,'2025-05-23','Exercitationem quaerat consequatur ad placeat a vitae. Accusantium aut placeat maiores pariatur. Commodi impedit recusandae praesentium consequuntur. Nesciunt voluptatem laudantium et nihil.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1989.40,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(27,36,7,25,'2024-10-02','Eveniet unde delectus maxime. Magni nihil ullam aliquam nesciunt quae et et dolore. Veniam dolor quo modi facere.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1467.58,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(28,12,3,22,'2025-03-16','Id ipsam molestiae facilis quaerat dolor id. Et mollitia sapiente eligendi sit omnis nesciunt. Et fugiat nobis recusandae placeat est enim illo eum. Inventore sint asperiores pariatur.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,884.56,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(29,41,3,2,'2025-02-14','Qui similique sunt non voluptas. Sed nam omnis laudantium qui perferendis impedit. Animi eos reprehenderit aut et quam sint non.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1341.19,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(30,34,7,2,'2024-12-04','Qui mollitia et natus ea dolorem non necessitatibus rerum. Nulla quia vero consectetur culpa dolore ut consequatur.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1013.00,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(31,32,6,25,'2024-10-03','Deleniti et sed dicta soluta fugit ut recusandae aut. Dolorem ipsam ullam voluptas voluptas. Veritatis quia sit ut iusto vel. Optio numquam minima sint totam est omnis hic.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,375.40,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(32,12,3,12,'2025-01-01','Quis ratione harum perferendis sed ipsa mollitia ratione. Nihil deleniti autem perspiciatis. Omnis libero itaque voluptatem sit consequatur beatae eos sed.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,648.54,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(33,6,3,27,'2025-05-03','Molestiae sed eos velit autem vel reiciendis. Asperiores enim quos itaque nobis ea. Dolor cupiditate ratione molestias vero consequatur sint ut amet. Quis vel doloribus quia ipsa voluptatibus. Ut excepturi autem repudiandae dicta.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1165.82,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(34,6,7,13,'2025-01-16','Exercitationem consequuntur sed ipsa voluptatem sed laudantium cupiditate. Velit delectus minima maxime consequuntur animi porro. Culpa id vel ipsam et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1380.41,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(35,29,7,16,'2025-02-13','Nostrum et alias consequuntur adipisci qui et doloremque. Ad illum ut debitis consequatur illo praesentium. Eligendi eius error sint et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1394.13,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(36,35,3,7,'2024-12-31','Delectus quaerat molestiae non voluptates iusto. Omnis dolor laboriosam fuga omnis ipsum sed vero. Laboriosam quae voluptas omnis libero temporibus quis aut quis.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1321.44,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(37,29,6,14,'2024-11-22','Libero voluptatibus sit aut ullam vero est ducimus ab. Modi laboriosam recusandae vitae quaerat omnis dolor dignissimos quisquam.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1107.05,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(38,1,7,9,'2024-08-14','Est aspernatur vitae tempora optio quas maxime repellendus. Pariatur officia laboriosam adipisci in possimus id. Sequi et iste enim eos molestias quidem.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1196.71,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(39,22,6,6,'2024-12-18','Ullam sequi aut eum qui beatae asperiores. Et atque rem natus aperiam culpa distinctio sunt. Non voluptatibus magni asperiores ratione voluptas omnis aut. Reprehenderit voluptatum et nostrum doloribus odio.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1183.74,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(40,21,7,23,'2024-12-24','Fugiat veritatis est consectetur nihil optio dolorem repudiandae. Distinctio eveniet incidunt tempora voluptatem sapiente qui modi. Quisquam illum non quos velit aut nam quaerat. Voluptate molestias et enim eligendi.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,927.43,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(41,21,7,19,'2024-11-12','Eum dolorem saepe nobis incidunt mollitia temporibus. Quia repellendus eligendi nam voluptatum quibusdam. Molestiae atque fugit explicabo consequatur non. Et sit placeat minus voluptatibus doloremque omnis et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1280.38,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(42,40,7,27,'2024-10-11','Doloribus recusandae optio harum non sit. Dolores perspiciatis et dolorem deserunt. Voluptate ut harum eos necessitatibus. Maxime harum reprehenderit architecto ut et.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,1566.13,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(43,3,6,4,'2024-08-05','Nulla sapiente id qui omnis repellat. Minima corrupti temporibus non hic et ullam. Ut voluptate et quis sed eaque. Velit molestias voluptates fuga minus accusantium voluptates pariatur.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1467.72,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(44,23,7,17,'2025-04-22','Numquam distinctio rerum veritatis rerum recusandae amet alias repellat. Itaque impedit molestiae nihil quos. Soluta odio voluptas ut et ex. Accusamus ea molestiae et autem. Delectus et voluptas placeat tenetur laborum repudiandae dolorem placeat.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,645.22,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(45,39,7,6,'2025-07-03','Amet aut perferendis velit incidunt sunt. Sint ratione odit dolorum sunt cumque eius vel amet. Optio aut ut impedit voluptatem ipsum ut.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,279.75,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(46,10,6,22,'2025-04-09','Dolore aut libero quis temporibus. Consequatur similique culpa perferendis non cum tempore.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'unsuccessful',NULL,1273.26,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(47,39,6,8,'2024-10-14','Et velit mollitia fuga sed voluptatum. Voluptas rerum dolor exercitationem repellendus rerum eum omnis necessitatibus. Sit quo quaerat excepturi nam iure saepe libero. Aperiam molestiae incidunt velit reprehenderit molestiae vel architecto.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1758.18,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(48,32,7,5,'2024-08-17','Sed optio excepturi alias eaque molestiae eos eum dignissimos. Est sunt molestias ipsa voluptatibus. Veniam nemo qui culpa soluta voluptatem vero. Officia labore assumenda ab veniam inventore doloribus.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'successful',NULL,382.71,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(49,24,6,25,'2025-03-05','Fugiat non a sit sunt dicta aspernatur. Vel earum iste commodi facilis. Nisi modi totam suscipit non delectus.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,1974.82,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL),(50,7,7,23,'2024-12-26','Aut sunt odio libero tempore odit. Velit commodi sit pariatur molestiae et aspernatur minima. Aut inventore ut ullam ut voluptas dolor voluptatum. Doloribus ut excepturi quas eligendi.',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'partially successful',NULL,368.45,0,'2025-08-03 19:07:51','2025-08-03 19:07:51',NULL);
/*!40000 ALTER TABLE `treatment_records` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','superadmin@example.com','2025-08-03 19:07:37','$2y$12$NL2b8rTsQF8uJ9.GVKtgeuAgd5idBkKCdqbuELhmWkFfREDDEDnDW','receptionist',NULL,NULL,1,NULL,'X8dbmA8WZ3','2025-08-03 19:07:37','2025-08-03 19:07:37'),(2,'Admin User','admin@example.com','2025-08-03 19:07:37','$2y$12$NL2b8rTsQF8uJ9.GVKtgeuAgd5idBkKCdqbuELhmWkFfREDDEDnDW','receptionist',NULL,NULL,1,NULL,'pDy8xZmwYO','2025-08-03 19:07:37','2025-08-03 19:07:37'),(3,'Editor User','editor@example.com','2025-08-03 19:07:37','$2y$12$NL2b8rTsQF8uJ9.GVKtgeuAgd5idBkKCdqbuELhmWkFfREDDEDnDW','receptionist',NULL,NULL,1,NULL,'TUINaMAnZl','2025-08-03 19:07:37','2025-08-03 19:07:37'),(4,'Viewer User','viewer@example.com','2025-08-03 19:07:37','$2y$12$NL2b8rTsQF8uJ9.GVKtgeuAgd5idBkKCdqbuELhmWkFfREDDEDnDW','receptionist',NULL,NULL,1,NULL,'mBwPZzkql6','2025-08-03 19:07:37','2025-08-03 19:07:37'),(5,'System Administrator','admin@dentalclinic.com','2025-08-03 19:07:38','$2y$12$u8XfYqI50vdzezvWkDXPweTTmJSVyzt0pWFYBoXU4mucpm78RN.F.','receptionist','(555) 123-4567','123 Admin Street, City, State 12345',1,NULL,NULL,'2025-08-03 19:07:38','2025-08-03 19:07:38'),(6,'Dr. John Smith','dr.smith@dentalclinic.com','2025-08-03 19:07:38','$2y$12$N.alRoYz3Tzk4B4g6qkACOJQYpEJ1Anh4/eiSyMChYs8vD8RFcQeC','receptionist','(555) 234-5678','456 Dentist Avenue, City, State 12345',1,NULL,NULL,'2025-08-03 19:07:38','2025-08-03 19:07:38'),(7,'Dr. Sarah Johnson','dr.johnson@dentalclinic.com','2025-08-03 19:07:38','$2y$12$ZNhTB63AIysFsCIYYmS7iOKEaIanlcBkzN6mUr8rx8K.Ri89EFm3W','receptionist','(555) 345-6789','789 Oral Health Blvd, City, State 12345',1,NULL,NULL,'2025-08-03 19:07:38','2025-08-03 19:07:38'),(8,'Mary Wilson','receptionist@dentalclinic.com','2025-08-03 19:07:38','$2y$12$xmdFZmF0svYbv3kCB1iDRuMaQyzWW5eng5AcPMtxNkzdAU6qsLyXq','receptionist','(555) 456-7890','321 Reception Lane, City, State 12345',1,NULL,NULL,'2025-08-03 19:07:38','2025-08-03 19:07:38');
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

-- Dump completed on 2025-08-05 13:07:50
