-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: clevora_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `about_page`
--

DROP TABLE IF EXISTS `about_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `about_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT 'About Clevora',
  `intro` text DEFAULT NULL,
  `company_story` longtext DEFAULT NULL,
  `problem_section` text DEFAULT NULL,
  `solution_section` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_page`
--

LOCK TABLES `about_page` WRITE;
/*!40000 ALTER TABLE `about_page` DISABLE KEYS */;
INSERT INTO `about_page` VALUES (1,'About Clevora','Clevora is a privately held organization and was incorporated in 2011. Since then Clevora has gained deep business insights and expertise in the outsourcing industry. We are proud of our client-centric models and dynamic execution strategies that yield concrete results. Our state-of-the-art infrastructure enables seamless remote team integrations and guarantees continuous business performance.','Clevora was established in 2011 in Delhi, India, with a vision to provide world-class business process outsourcing solutions. Over the last decade, we have expanded from a small team of 10 agents to a global enterprise service provider. Our operations span across call center support, technical helpdesks, data entry services, content moderation, and custom software engineering. We maintain high standards of quality assurance and data security, keeping in line with modern compliance frameworks.',NULL,NULL,'To empower organizations globally by providing high-quality, secure, and cost-effective outsourcing services that maximize operating efficiency.','To be the preferred global outsourcing partner recognized for operational excellence, integrity, and innovative business process solutions.',1,'2026-06-25 12:34:29','2026-06-25 12:34:29');
/*!40000 ALTER TABLE `about_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `about_values`
--

DROP TABLE IF EXISTS `about_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `about_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT '⭐',
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_values`
--

LOCK TABLES `about_values` WRITE;
/*!40000 ALTER TABLE `about_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `about_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$w31bCcbdjkd/j9Iq8UacDOgnUh/NOPZX0nPZUjKmvxgnnENEFBuPO','020b2f89ed821810b9963c33c758295b5d277b92753d4e6e789d79cd29121c98','2026-06-26 12:05:54','2026-06-25 12:34:29','2026-06-26 06:35:54');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `career_settings`
--

DROP TABLE IF EXISTS `career_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `career_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(100) NOT NULL,
  `section_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `career_settings`
--

LOCK TABLES `career_settings` WRITE;
/*!40000 ALTER TABLE `career_settings` DISABLE KEYS */;
INSERT INTO `career_settings` VALUES (1,'hero_label','Clevora Global Operations','2026-06-25 16:04:20','2026-06-25 16:04:20'),(2,'hero_title','Careers','2026-06-25 16:04:20','2026-06-25 16:04:20'),(3,'main_heading','Build Your Career With Clevora','2026-06-25 16:04:20','2026-06-25 16:04:20'),(4,'intro_text','Join a growing outsourcing company where talented people work with global businesses and develop professional skills.','2026-06-25 16:04:20','2026-06-25 16:04:20'),(5,'apply_heading','Ready to join us?','2026-06-25 16:04:20','2026-06-25 16:04:20'),(6,'apply_text','Send your resume and a brief cover letter to <a href=\"mailto:info@clevora.in\" style=\"color:#2563eb; font-weight:600; text-decoration:none; border-bottom:1px solid #bfdbfe;\">info@clevora.in</a>. Include the role you\'re applying for in the subject line. We\'ll get back to you within 3 business days.','2026-06-25 16:04:20','2026-06-25 16:04:20'),(7,'benefit_cards','[{\"title\":\"Work with Global Clients\",\"description\":\"Collaborate with international businesses across e-commerce, finance, healthcare and technology.\"},{\"title\":\"Training & Growth\",\"description\":\"Receive structured onboarding and continuous skill development opportunities.\"},{\"title\":\"Collaborative Culture\",\"description\":\"Work in a supportive environment built around teamwork and innovation.\"},{\"title\":\"Competitive Compensation\",\"description\":\"Get rewarding career opportunities with fair compensation.\"}]','2026-06-25 16:04:20','2026-06-25 16:04:20');
/*!40000 ALTER TABLE `career_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `careers`
--

DROP TABLE IF EXISTS `careers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `careers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `location` varchar(150) DEFAULT 'India',
  `job_type` varchar(100) DEFAULT 'Full-Time',
  `department` varchar(150) DEFAULT '',
  `short_description` text DEFAULT NULL,
  `responsibilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`responsibilities`)),
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `experience` varchar(100) DEFAULT '',
  `is_active` tinyint(4) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `careers`
--

LOCK TABLES `careers` WRITE;
/*!40000 ALTER TABLE `careers` DISABLE KEYS */;
INSERT INTO `careers` VALUES (1,'Business Development Associate / Sr Associate','business-development-associate','India','Full-Time','Sales & Business Development','Responsible for identifying opportunities, managing client communication and supporting business growth.','[\"Identify new business opportunities\", \"Manage client communication\", \"Build customer relationships\"]','[\"Good communication skills\", \"Sales understanding\", \"Professional attitude\"]','1-3 Years',1,1,'2026-06-25 16:04:20','2026-06-25 16:04:20'),(2,'Ecommerce Specialist / Assistant Manager','ecommerce-specialist','India','Full-Time','Ecommerce Operations','Manage marketplace operations, listings, orders and online business workflows.','[\"Manage marketplace operations\", \"Optimize product listings\", \"Process orders and track shipments\"]','[\"Experience with e-commerce platforms\", \"Detail-oriented mindset\", \"Good analytical skills\"]','2-4 Years',1,2,'2026-06-25 16:04:20','2026-06-25 16:04:20'),(3,'Human Resource Manager / Assistant Manager','human-resource-manager','India','Full-Time','Human Resources','Handle recruitment, employee processes and HR operations.','[\"Manage recruitment pipelines\", \"Coordinate employee onboarding\", \"Oversee HR operations and compliance\"]','[\"Strong interpersonal skills\", \"Knowledge of HR practices\", \"Relevant degree or certification\"]','3-5 Years',1,3,'2026-06-25 16:04:20','2026-06-25 16:04:20'),(4,'Virtual Assistant','virtual-assistant','India','Full-Time','Administrative Support','Provide remote administrative and operational support.','[\"Provide remote administrative support\", \"Schedule meetings and manage calendars\", \"Handle email correspondence\"]','[\"Proficiency in office software\", \"Excellent time management\", \"Strong verbal and written English\"]','1-2 Years',1,4,'2026-06-25 16:04:20','2026-06-25 16:04:20');
/*!40000 ALTER TABLE `careers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo_url` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'/assets/images/client-1.png','Client One',NULL,0,1,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(2,'/assets/images/client-2.png','Client Two',NULL,0,1,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(3,'/assets/images/client-3.png','Client Three',NULL,0,1,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(4,'/assets/images/client-4.png','Client Four',NULL,0,1,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(5,'/assets/images/client-5.png','Client Five',NULL,0,1,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(6,'/assets/images/client-6.png','Client Six',NULL,0,1,'2026-06-25 12:34:29','2026-06-25 12:34:29');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_settings`
--

DROP TABLE IF EXISTS `contact_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_settings`
--

LOCK TABLES `contact_settings` WRITE;
/*!40000 ALTER TABLE `contact_settings` DISABLE KEYS */;
INSERT INTO `contact_settings` VALUES (1,'phone','+91 9953310085','2026-06-25 12:34:29','2026-06-25 12:34:29'),(2,'email','info@clevora.in','2026-06-25 12:34:29','2026-06-25 12:34:29'),(3,'address','Delhi, India','2026-06-25 12:34:29','2026-06-25 12:34:29'),(4,'whatsapp','','2026-06-25 12:34:29','2026-06-25 12:34:29'),(5,'google_map','','2026-06-25 12:34:29','2026-06-25 12:34:29'),(6,'business_hours','Monday - Friday: 9:00 AM - 6:00 PM IST','2026-06-25 12:34:29','2026-06-25 12:34:29'),(7,'facebook','','2026-06-25 12:34:29','2026-06-25 12:34:29'),(8,'linkedin','','2026-06-25 12:34:29','2026-06-25 12:34:29'),(9,'xing','','2026-06-25 12:34:29','2026-06-25 12:34:29'),(10,'linktree','','2026-06-25 12:34:29','2026-06-25 12:34:29');
/*!40000 ALTER TABLE `contact_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `founder`
--

DROP TABLE IF EXISTS `founder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `founder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT 'Mayank Chandhok',
  `role` varchar(255) DEFAULT 'Founder & Managing Director',
  `image` varchar(255) DEFAULT '/assets/images/founder.jpg',
  `bio` longtext DEFAULT NULL,
  `message` text DEFAULT NULL,
  `experience_text` text DEFAULT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `founder`
--

LOCK TABLES `founder` WRITE;
/*!40000 ALTER TABLE `founder` DISABLE KEYS */;
INSERT INTO `founder` VALUES (1,'Mayank Chandhok','Founder & Managing Director','/assets/images/founder.jpg','Mayank Chandhok founded Clevora in 2011 with the goal of bridging the gap between global enterprises and skilled talent in India. Under his leadership, Clevora has grown into a trusted outsourcing partner for hundreds of organizations globally.',NULL,NULL,NULL,1,'2026-06-25 12:34:29','2026-06-25 12:34:29');
/*!40000 ALTER TABLE `founder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) NOT NULL,
  `caption` varchar(150) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'General',
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (1,'/assets/images/gallery-1.jpg','Our Modern Workspace',1,NULL,'General',NULL,1,'2026-06-25 12:34:29'),(2,'/assets/images/gallery-2.jpg','Server Room & Infrastructure',2,NULL,'General',NULL,1,'2026-06-25 12:34:29'),(3,'/assets/images/gallery-3.jpg','Team Collaboration Session',3,NULL,'General',NULL,1,'2026-06-25 12:34:29'),(4,'/assets/images/gallery-4.jpg','Training Workshop',4,NULL,'General',NULL,1,'2026-06-25 12:34:29'),(5,'/assets/images/gallery-5.jpg','Recreation & Play Area',5,NULL,'General',NULL,1,'2026-06-25 12:34:29'),(6,'/assets/images/gallery-6.jpg','Client Visit and Discussion',6,NULL,'General',NULL,1,'2026-06-25 12:34:29');
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_albums`
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` VALUES (1,'Our Modern Workspace','our-modern-workspace','Legacy gallery item category: General','/assets/images/gallery-1.jpg',1,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(2,'Server Room & Infrastructure','server-room-infrastructure','Legacy gallery item category: General','/assets/images/gallery-2.jpg',2,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(3,'Team Collaboration Session','team-collaboration-session','Legacy gallery item category: General','/assets/images/gallery-3.jpg',3,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(4,'Training Workshop','training-workshop','Legacy gallery item category: General','/assets/images/gallery-4.jpg',4,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(5,'Recreation & Play Area','recreation-play-area','Legacy gallery item category: General','/assets/images/gallery-5.jpg',5,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(6,'Client Visit and Discussion','client-visit-and-discussion','Legacy gallery item category: General','/assets/images/gallery-6.jpg',6,1,'2026-06-26 04:15:39','2026-06-26 04:15:39');
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `album_id` (`album_id`),
  CONSTRAINT `gallery_images_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` VALUES (1,1,'/assets/images/gallery-1.jpg','Our Modern Workspace',1,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(2,2,'/assets/images/gallery-2.jpg','Server Room & Infrastructure',1,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(3,3,'/assets/images/gallery-3.jpg','Team Collaboration Session',1,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(4,4,'/assets/images/gallery-4.jpg','Training Workshop',1,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(5,5,'/assets/images/gallery-5.jpg','Recreation & Play Area',1,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(6,6,'/assets/images/gallery-6.jpg','Client Visit and Discussion',1,1,'2026-06-26 04:15:39','2026-06-26 04:15:39'),(7,1,'/assets/images/uploads/gallery_photo_1782447710_982.jpg','',2,1,'2026-06-26 04:21:50','2026-06-26 04:21:50');
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hero_sliders`
--

DROP TABLE IF EXISTS `hero_sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hero_sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `small_heading` varchar(255) DEFAULT '',
  `main_heading` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `bullets` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bullets`)),
  `cta_text` varchar(100) DEFAULT 'Contact Us',
  `cta_link` varchar(255) DEFAULT '/contact.php',
  `image` varchar(255) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `media_type` enum('image','video') DEFAULT 'image',
  `media_file` varchar(500) DEFAULT NULL,
  `video_poster` varchar(500) DEFAULT NULL,
  `secondary_cta_text` varchar(150) DEFAULT 'Explore Solutions',
  `secondary_cta_link` varchar(255) DEFAULT '/services.php',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_sliders`
--

LOCK TABLES `hero_sliders` WRITE;
/*!40000 ALTER TABLE `hero_sliders` DISABLE KEYS */;
INSERT INTO `hero_sliders` VALUES (1,'SCALABLE OUTSOURCING SOLUTIONS','Test Update','Test Description 123','[\"Inbound and outbound customer care support\",\"Fluent multilingual support agents\",\"SLA-backed email and live chat support\",\"Technical helpdesk and troubleshooting support\"]','Contact Us','/contact.php','',1,0,'2026-06-25 12:34:29','2026-06-26 07:03:15','video','uploads/hero/hero_vid_1782449506_931.mp4','','Explore Solutions','/services.php'),(2,'TRUST & SAFETY SOLUTIONS','Content Moderation & Operations','Protecting your brand and users round the clock.','[\"Live streaming content moderation\",\"Video and image review services\",\"Social media review and comments moderation\",\"Compliance and policy enforcement\"]','Contact Us','/contact.php','/assets/images/content-mod.jpg',2,1,'2026-06-25 12:34:29','2026-06-25 17:15:46','image','/assets/images/content-mod.jpg',NULL,'Explore Solutions','/services.php'),(3,'SECURE PROCESS MANAGEMENT','Data Operations & Back Office','Highly accurate and secure database processing.','[\"Data entry and transcription services\",\"Database standardisation and cleaning\",\"Bookkeeping and accounts support\",\"Administrative process management\"]','Contact Us','/contact.php','/assets/images/service-banner.jpg',3,1,'2026-06-25 12:34:29','2026-06-25 17:15:46','image','/assets/images/service-banner.jpg',NULL,'Explore Solutions','/services.php'),(4,'GLOBAL SUPPORT OPERATIONS','E-Commerce Support Operations','Powering your storefront operations seamlessly.','[\"Order processing and shipment tracking support\",\"Product listing and catalog uploads\",\"Returns, refunds and exchange management\",\"Multi-marketplace store support\"]','Contact Us','/contact.php','/assets/images/hero-office.jpg',4,1,'2026-06-25 12:34:29','2026-06-25 17:15:46','image','/assets/images/hero-office.jpg',NULL,'Explore Solutions','/services.php'),(5,'SCALABLE OUTSOURCING SOLUTIONS','Customer Experience','Proactive 24/7 customer engagement across channels.','[\"Inbound and outbound customer care support\",\"Fluent multilingual support agents\",\"SLA-backed email and live chat support\",\"Technical helpdesk and troubleshooting support\"]','Contact Us','/contact.php','',1,1,'2026-06-26 04:33:08','2026-06-26 06:36:28','video','uploads/hero/hero_vid_1782455788_511.mp4','','Explore Solutions','/services.php');
/*!40000 ALTER TABLE `hero_sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_partners`
--

DROP TABLE IF EXISTS `home_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_partners`
--

LOCK TABLES `home_partners` WRITE;
/*!40000 ALTER TABLE `home_partners` DISABLE KEYS */;
INSERT INTO `home_partners` VALUES (1,'Client One Updated','/assets/images/client-1.png',1,1,'2026-06-25 17:34:09','2026-06-25 17:42:31'),(2,'Client Two','/assets/images/client-2.png',2,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(3,'Client Three','/assets/images/client-3.png',3,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(4,'Client Four','/assets/images/client-4.png',4,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(5,'Client Five','/assets/images/client-5.png',5,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(6,'Client Six','/assets/images/client-6.png',6,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(7,'Client One','/assets/images/client-1.png',1,1,'2026-06-26 05:01:04','2026-06-26 05:01:04');
/*!40000 ALTER TABLE `home_partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_process_steps`
--

DROP TABLE IF EXISTS `home_process_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_process_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `step_number` int(11) DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_process_steps`
--

LOCK TABLES `home_process_steps` WRITE;
/*!40000 ALTER TABLE `home_process_steps` DISABLE KEYS */;
INSERT INTO `home_process_steps` VALUES (1,1,'Tell Us What You Need Updated','Share your business challenges, goals and outsourcing requirements.',1,1,'2026-06-25 17:34:09','2026-06-25 17:46:03'),(2,2,'We Deploy Your Team','Our experts create a customized support process for your company.',2,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(3,3,'Scale & Grow','Focus on growth while Clevora manages your operations.',3,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(4,1,'Tell Us What You Need','Share your business challenges, goals and outsourcing requirements.',1,1,'2026-06-26 05:01:04','2026-06-26 05:01:04');
/*!40000 ALTER TABLE `home_process_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_solutions`
--

DROP TABLE IF EXISTS `home_solutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_solutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT '?',
  `button_text` varchar(100) DEFAULT 'Explore Solutions',
  `button_link` varchar(255) DEFAULT '/services.php',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_solutions`
--

LOCK TABLES `home_solutions` WRITE;
/*!40000 ALTER TABLE `home_solutions` DISABLE KEYS */;
INSERT INTO `home_solutions` VALUES (1,'Customer Support Services Updated','Multilingual inbound/outbound support, email and live chat operations with SLAs tailored to keep customer satisfaction high.','💬','Explore Solutions','/services.php?category=customer-support-services',1,1,'2026-06-25 17:34:09','2026-06-25 17:44:44'),(2,'Content & Moderation Services','24/7 video, audio, image and social media review moderation. Keep your application community and brand reputation safe.','🛡️','Explore Solutions','/services.php?category=content-moderation-services',2,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(3,'E-Commerce Support','Optimize store operations: catalog product uploads, order tracking coordination, returns and marketplace support.','🛒','Explore Solutions','/services.php?category=e-commerce-support',3,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(4,'Back Office & Data Management','High speed data entry, processing, and standardisation solutions to keep corporate records accurate and accessible.','📂','Explore Solutions','/services.php?category=back-office-data-management',4,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(5,'Finance & Accounting','Automate billing support, accounts receivable/payable, expense reconciliations and payroll auditing.','💳','Explore Solutions','/services.php?category=finance-accounting',5,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(6,'Recruitment & HR Services','Recruitment process outsourcing (RPO), resume screening, talent sourcing and HR operations administration.','👥','Explore Solutions','/services.php?category=hr-solutions',6,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(7,'Customer Support Services','Multilingual inbound/outbound support, email and live chat operations with SLAs tailored to keep customer satisfaction high.','💬','Explore Solutions','/services.php?category=customer-support-services',1,1,'2026-06-26 05:01:04','2026-06-26 05:01:04');
/*!40000 ALTER TABLE `home_solutions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `homepage_sections`
--

DROP TABLE IF EXISTS `homepage_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homepage_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT '',
  `extra_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_data`)),
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_section_type` (`section_type`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homepage_sections`
--

LOCK TABLES `homepage_sections` WRITE;
/*!40000 ALTER TABLE `homepage_sections` DISABLE KEYS */;
INSERT INTO `homepage_sections` VALUES (1,'why_choose','Qualified Experts','Experienced teams trained for complex business operations.','fa-graduation-cap',NULL,1,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(2,'why_choose','Operational Quality','Structured workflows focused on accuracy and performance.','fa-certificate',NULL,2,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(3,'why_choose','Flexible & Scalable','Increase or reduce support capacity according to demand.','fa-clock',NULL,3,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(4,'why_choose','Affordable Packages','Cost-effective outsourcing solutions.','fa-coins',NULL,4,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(5,'why_choose','Data Security','Confidential processes and secure information handling.','fa-shield-halved',NULL,5,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(6,'why_choose','24/7 Operations','Continuous support across multiple time zones.','fa-users',NULL,6,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(7,'industry','Gaming & Entertainment','Gaming support and content operations for immersive platforms.','fa-gamepad',NULL,1,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(8,'industry','Education','Student support and digital learning platform administration.','fa-book-open',NULL,2,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(9,'industry','Retail & E-Commerce','Order workflows, listings, catalog management and helpdesk operations.','fa-cart-shopping',NULL,3,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(10,'industry','Hospitality','Booking, reservations, routing and continuous guest inquiries.','fa-hotel',NULL,4,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(11,'industry','Staffing & HR','Sourcing, screening, data auditing and onboarding processes.','fa-user-tie',NULL,5,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(12,'industry','Healthcare','Secure validation, scheduling and patient coordination services.','fa-heart-pulse',NULL,6,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(13,'industry','Real Estate','Listings updates, database operations and lead processing support.','fa-building',NULL,7,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(14,'industry','Financial Services','Accounts processing, invoice routing, data cleanup and reports.','fa-money-bill-trend-up',NULL,8,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(15,'industry','SaaS & Technology','Technical helpdesk, user onboarding and platform monitoring.','fa-laptop-code',NULL,9,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(16,'industry','Telecommunications','Customer workflows, calling support and routing configurations.','fa-phone-volume',NULL,10,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(17,'faq','What outsourcing services does Clevora offer?','Clevora provides customer support, content operations, data management, finance, HR, e-commerce, KPO and call center solutions.','❓',NULL,1,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(18,'faq','How quickly can you deploy a team?','Deployment depends on process requirements and team size. We create flexible solutions based on business needs.','❓',NULL,2,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(19,'faq','How do you ensure data security?','We use controlled workflows, access management and secure operational practices.','❓',NULL,3,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(20,'faq','Can I scale services?','Yes. Our teams can expand or adjust according to business demand.','❓',NULL,4,1,'2026-06-25 17:34:09','2026-06-25 17:34:09'),(21,'faq','Where are your teams located?','Clevora operates from Delhi, India and supports global clients.','❓',NULL,5,1,'2026-06-25 17:34:09','2026-06-25 17:34:09');
/*!40000 ALTER TABLE `homepage_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `interest` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('new','read','contacted','closed') DEFAULT 'new',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` VALUES (1,'Test User','test@example.com','1234567890','Testing','This is a test message from automation.','2026-06-14 17:26:10','new','2026-06-25 12:34:29'),(2,'Test User','testuser@example.com','+919876543210','Customer Support Services','Hello, this is a test message to verify the contact form works correctly.','2026-06-25 07:10:53','new','2026-06-25 12:34:29'),(3,'Test User','manish.work118@gmail.com','1234567890','Customer Support Services','Testing PHPMailer Integration.','2026-06-25 10:59:18','new','2026-06-25 12:34:29'),(4,'Test User','manish.work118@gmail.com','1234567890','Customer Support Services','Testing PHPMailer Integration.','2026-06-25 11:00:42','new','2026-06-25 12:34:29'),(5,'Manish Sharma','1182003@gmail.com','9990830656','HR Services','hi','2026-06-25 11:04:26','new','2026-06-25 12:34:29'),(6,'Modal Test','manish.work118@gmail.com','9988776655','Customer Support Services','testing message','2026-06-25 11:08:46','new','2026-06-25 12:34:29'),(7,'Modal Test','manish.work118@gmail.com','9988776655','Customer Support Services','testing message','2026-06-25 11:11:13','new','2026-06-25 12:34:29'),(8,'Test PowerShell Lead','pslead@example.com','1234567890','Customer Support Services','Test message from automated syntax verification','2026-06-25 15:58:27','new','2026-06-25 15:58:27');
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_library`
--

DROP TABLE IF EXISTS `media_library`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT '',
  `size` int(11) DEFAULT 0,
  `url` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_library`
--

LOCK TABLES `media_library` WRITE;
/*!40000 ALTER TABLE `media_library` DISABLE KEYS */;
INSERT INTO `media_library` VALUES (1,'gallery_photo_1782447710_982.jpg','bmw-m3-black-headlights-dark-desktop-wallpaper-4k.jpg','image/jpeg',850441,'/assets/images/uploads/gallery_photo_1782447710_982.jpg','','2026-06-26 04:21:50','2026-06-26 04:21:50'),(2,'hero_vid_1782449506_931.mp4','sample.mp4','video/mp4',3211752,'/uploads/hero/hero_vid_1782449506_931.mp4','','2026-06-26 04:51:46','2026-06-26 04:51:46'),(3,'hero_vid_1782455788_511.mp4','hero_vid_1782449506_931.mp4','video/mp4',3211752,'/uploads/hero/hero_vid_1782455788_511.mp4','','2026-06-26 06:36:28','2026-06-26 06:36:28');
/*!40000 ALTER TABLE `media_library` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_settings`
--

DROP TABLE IF EXISTS `seo_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_slug` varchar(100) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` varchar(500) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_slug` (`page_slug`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_settings`
--

LOCK TABLES `seo_settings` WRITE;
/*!40000 ALTER TABLE `seo_settings` DISABLE KEYS */;
INSERT INTO `seo_settings` VALUES (1,'home','Clevora | Global BPO, Customer Experience & Digital Operations Partner','Clevora provides secure outsourcing solutions including customer support, content operations, data management, HR, finance, e-commerce support and BPO services worldwide.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(2,'about','About Clevora | Global Outsourcing Company Since 2011','Learn about Clevora\'s journey from a small Delhi startup to a global outsourcing partner.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(3,'services','Our Services | Clevora BPO Solutions','Explore Clevora\'s comprehensive range of BPO and outsourcing services.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(4,'technology','Technology & Infrastructure | Clevora','Discover Clevora\'s state-of-the-art technology infrastructure and security systems.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(5,'gallery','Our Gallery | Clevora Workspace & Team','View Clevora\'s modern workspace, team activities, and infrastructure.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(6,'clients','Our Clients | Clevora Partners','Organizations that trust Clevora for their outsourcing needs.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(7,'careers','Careers at Clevora | Build Your Future','Explore career opportunities at Clevora.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(8,'contact','Contact Clevora | Outsourcing Consultation','Get in touch with Clevora for your outsourcing needs.',NULL,NULL,'2026-06-25 12:34:29','2026-06-25 12:34:29');
/*!40000 ALTER TABLE `seo_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_benefits`
--

DROP TABLE IF EXISTS `service_benefits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_benefits`
--

LOCK TABLES `service_benefits` WRITE;
/*!40000 ALTER TABLE `service_benefits` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_benefits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banner_image` varchar(255) DEFAULT NULL,
  `full_description` longtext DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_categories`
--

LOCK TABLES `service_categories` WRITE;
/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES (1,'Customer Support Services','customer-support-services','Professional inbound, outbound, email, chat, and helpdesk solutions to elevate user engagement.','💬',1,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(2,'Content & Moderation Services','content-moderation-services','Safeguard your platform and engage audiences with high-quality content review and review moderation.','🛡️',2,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(3,'E-Commerce Support','e-commerce-support','Scale your online store with order processing, catalog listing, returns, and marketplace operations.','🛒',3,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(4,'Back Office & Data Management','back-office-data-management','Increase efficiency with professional data entry, bookkeeping, processing, and admin support.','📂',4,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(5,'Finance & Accounting','finance-accounting','Comprehensive billing support, general accounting operations, and financial processing.','💳',5,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(6,'Recruitment & HR Services','recruitment-hr-services','Recruitment process outsourcing, candidate screening, talent acquisition, and HR administration.','👥',6,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(7,'Knowledge Process Outsourcing (KPO)','knowledge-process-outsourcing-kpo','Advanced research support, business process design, and project management excellence.','🧠',7,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(8,'Online Reputation Management','online-reputation-management','Verify online reviews, manage ratings, and protect your global brand sentiment.','🌟',8,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL),(9,'Call Center & BPO Services','call-center-bpo-services','Inbound/outbound calling, domestic and international voice processes, and 24/7 engagement.','📞',9,1,'2026-06-25 11:53:28','2026-06-25 11:53:28',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_features`
--

DROP TABLE IF EXISTS `service_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_features`
--

LOCK TABLES `service_features` WRITE;
/*!40000 ALTER TABLE `service_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_industries`
--

DROP TABLE IF EXISTS `service_industries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_industries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_industries`
--

LOCK TABLES `service_industries` WRITE;
/*!40000 ALTER TABLE `service_industries` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_industries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_process`
--

DROP TABLE IF EXISTS `service_process`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_process`
--

LOCK TABLES `service_process` WRITE;
/*!40000 ALTER TABLE `service_process` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `slug` varchar(80) NOT NULL,
  `name` varchar(150) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `intro` text DEFAULT NULL,
  `detailed_description` longtext DEFAULT NULL,
  `challenge_solved` text DEFAULT NULL,
  `full_content` longtext DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `benefits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`benefits`)),
  `is_active` tinyint(4) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` varchar(500) DEFAULT NULL,
  `cta_heading` varchar(255) DEFAULT NULL,
  `cta_text` text DEFAULT NULL,
  `cta_button` varchar(150) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_services_category` (`category_id`),
  CONSTRAINT `fk_services_category` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (2,1,'multilingual-outbound-customer-support','Multilingual Outbound Customer Support','/assets/images/service-callcenter.svg','Proactive outbound customer support, surveys, and follow-ups in international languages.','We offer proactive customer reach-out programs, customer feedback loops, and customer satisfaction surveys in multiple languages. Our outbound professionals handle service reminders, renewals, post-purchase surveys, and data verification to keep your database accurate and build long-term loyalty.',NULL,NULL,'[\"Automated & Manual Dialing\",\"Feedback Tracking\",\"Detailed Analytics\",\"Multilingual Outreach\"]','[\"Proactive engagement\",\"Actionable feedback data\",\"Increased retention\"]',1,2,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(3,1,'email-support','Email Support Services','/assets/images/service-backoffice.svg','Accurate, timely, and branded email responses to resolve customer queries.','Email remains a core channel for detailed customer inquiries. We manage your customer service inbox with pre-defined response SLA standards. Our team handles ticketing queues, sorts complex requests, and drafts professional, personalized responses that represent your company policies accurately.',NULL,NULL,'[\"Ticket Queue Management\",\"SLA Adherence\",\"Template customization\",\"Issue Tracking\"]','[\"Reduced response latency\",\"Consistent brand voice\",\"Cost-effective support\"]',1,3,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(4,1,'chat-support','Live Chat Support','/assets/images/service-validation.svg','Provide real-time help to visitors on your web, mobile app, or social store.','Instantly engage customers when they visit your site. Our live chat support team answers product questions, helps users navigate the purchase process, and resolves technical issues in real-time. We help you minimize shopping cart abandonment and turn website visitors into loyal customers.',NULL,NULL,'[\"Multi-platform Integration\",\"Real-time Chat Logs\",\"Co-browsing Support\",\"Pre-sales Assistance\"]','[\"Higher web conversion\",\"Immediate problem solving\",\"Lower bounce rates\"]',1,4,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(5,1,'technical-helpdesk-services','Technical & Non-Technical Helpdesk Services','/assets/images/service-software.svg','L1 and L2 technical troubleshooting, software guidance, and helpdesk support.','Support your users through hardware, software, or account access challenges. Our helpdesk team is trained in Tier 1 and Tier 2 support levels. We resolve common issues, troubleshoot system bugs, reset credentials, and escalate complex issues systematically to your internal developers.',NULL,NULL,'[\"Tier 1 & Tier 2 Support\",\"SLA Management\",\"Knowledge Base Creation\",\"Bug Logging\"]','[\"Reduced dev team workload\",\"Fast tech resolution\",\"Enhanced user trust\"]',1,5,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(6,2,'content-writing-services','Content Writing Services','/assets/images/service-publishing.svg','SEO-friendly copy, blogs, articles, and product copy drafted by expert writers.','Produce high-quality content that ranks and converts. Our writing team works on blogs, product descriptions, website landing pages, and newsletters. We ensure every piece of content matches your brand style guidelines and incorporates search optimization parameters.',NULL,NULL,'[\"SEO Optimization\",\"Copyediting\",\"Topic Research\",\"Formatting & Typesetting\"]','[\"Increased organic traffic\",\"Better brand authority\",\"Ready-to-publish copy\"]',1,6,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(7,2,'video-content-moderation','Video Content Moderation','/assets/images/service-moderation.svg','24/7 video reviews to protect your app or web community from harmful uploads.','Protect your users from offensive, copyrighted, or malicious video uploads. Our 24/7 content operations team reviews user-generated videos, checks compliance with community guidelines, and filters out inappropriate submissions before they damage your brand reputation.',NULL,NULL,'[\"Frame-by-frame Review\",\"Real-time Censorship\",\"Compliance Tagging\",\"24\\/7 Coverage\"]','[\"Brand safety\",\"Safe user community\",\"Compliance assurance\"]',1,7,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(8,2,'live-streaming-content-moderation','Live Streaming Content Moderation','/assets/images/service-moderation.svg','Real-time moderation of live audio and video streams to guarantee compliance.','Managing live content requires instant decisions. Our dedicated moderators monitor live streams to detect violation of terms, illegal activities, or explicit behaviors, immediately terminating or flagging accounts as necessary.',NULL,NULL,'[\"Low Latency Action\",\"Audio Stream Monitoring\",\"Chat Log Filtering\",\"Automated Alerts Integration\"]','[\"Immediate brand protection\",\"Reduced legal liability\",\"Safe online spaces\"]',1,8,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(9,2,'social-media-content-review','Social Media Content Review','/assets/images/service-marketing.svg','Monitor user reviews, comments, and tags on major social networks.','Maintain clean and positive social channels. We monitor your brand mentions, comments, and tags across platforms. We hide spam, reply to customer inquiries, and flag high-priority issues to your public relations team.',NULL,NULL,'[\"Spam Filtering\",\"Multi-channel Coverage\",\"Sentiment Analysis\",\"Escalation Frameworks\"]','[\"Clean social feeds\",\"Faster response times\",\"Brand preservation\"]',1,9,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(10,3,'order-processing-management','Order Processing & Management','/assets/images/service-backoffice.svg','Process and log online orders, verify payments, and coordinate shipping.','Keep your e-commerce operations moving smoothly. We process sales orders, check payment authorization, verify customer address details, and update inventory systems to coordinate fulfillment.',NULL,NULL,'[\"Order Entry Validation\",\"Payment Status Checks\",\"Inventory Updates\",\"SLA Tracking\"]','[\"Faster order dispatch\",\"Fewer processing mistakes\",\"Efficient inventory data\"]',1,10,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(11,3,'order-tracking-support','Order Tracking Support','/assets/images/service-db.svg','Help customers track shipments and handle carrier delay inquiries.','Respond to the common \\\"Where is my order?\\\" inquiry quickly. We track packages, interface with logistics providers, and inform customers about shipment delays or custom delivery requirements.',NULL,NULL,'[\"Tracking Status Checks\",\"Logistics coordination\",\"Automated Updates\",\"Carrier Communication\"]','[\"Reduced support tickets\",\"Better post-purchase trust\",\"Accurate delivery data\"]',1,11,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(12,3,'product-listing-management','Product Listing Management','/assets/images/service-publishing.svg','Create and maintain SKU lists, descriptions, and images in store databases.','Ensure your digital storefront is clean, structured, and search-optimized. We upload new product details, write features lists, optimize images, configure variants (size, color), and manage prices.',NULL,NULL,'[\"SKU Database Updates\",\"Copywriting Optimization\",\"Variant Setup\",\"SEO Meta Tags\"]','[\"High catalog accuracy\",\"Reduced list time-to-market\",\"Better search visibility\"]',1,12,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(13,3,'returns-exchange-management','Returns & Exchange Management','/assets/images/service-validation.svg','Log customer returns, authorize replacements, and issue credit refunds.','Make return processes simple and secure. We verify return conditions, coordinate return shipping tags, process store credits, and manage refunds following your store guidelines.',NULL,NULL,'[\"Return Status Tracking\",\"Refund Verification\",\"Replacement Authorization\",\"Logistics Coordination\"]','[\"Higher client loyalty\",\"Better fraud prevention\",\"Organized return flow\"]',1,13,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(14,3,'marketplace-support-services','Marketplace Support Services','/assets/images/service-bpo.svg','Support for Amazon, eBay, Shopify, Walmart, and custom online storefronts.','Manage listings, pricing alerts, and customer disputes across different online marketplaces. We ensure your business complies with marketplace standards to avoid listing penalties or account suspensions.',NULL,NULL,'[\"Dispute Resolution\",\"Feed Quality Checks\",\"Price Synchronization\",\"Seller Dashboard Management\"]','[\"Higher seller ratings\",\"Multi-channel growth\",\"Less admin burden\"]',1,14,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(15,4,'data-entry-services','Data Entry Services','/assets/images/service-db.svg','Accurate, high-speed text, numeric, and database entry solutions.','Digitize your paperwork, documents, logs, and files with speed and accuracy. We enter customer records, capture scan data, transcribe forms, and build structured directories for your company.',NULL,NULL,'[\"99.9% Accuracy Guarantee\",\"High-Speed Typing\",\"Batch Formatting\",\"Double-Key Validation\"]','[\"Clean data assets\",\"Lower operational overhead\",\"Accelerated access to files\"]',1,15,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(16,4,'data-processing','Data Processing','/assets/images/service-validation.svg','Consolidate, parse, format, and organize raw text and numerical files.','Turn unstructured logs, spreadsheets, and files into organized reports. We clean duplicate data points, map missing parameters, standardize inputs, and output databases ready for system importing.',NULL,NULL,'[\"CSV\\/JSON Mapping\",\"Data Scrubbing\",\"Regex Normalization\",\"Structured Reporting\"]','[\"Actionable business data\",\"Reduced import errors\",\"Enhanced database health\"]',1,16,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(17,4,'data-management','Data Management','/assets/images/service-db.svg','Long-term maintenance, formatting, backups, and security checks for data storage.','Secure and maintain your data assets. We manage databases, perform routine integrity checks, implement security profiles, coordinate backups, and structure access controls for team members.',NULL,NULL,'[\"Database Auditing\",\"Secure Backups\",\"Access Management\",\"Compliance Compliance\"]','[\"Reduced database errors\",\"Better disaster readiness\",\"Enhanced data protection\"]',1,17,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(18,4,'bookkeeping-services','Bookkeeping Services','/assets/images/service-backoffice.svg','Log daily financial entries, payments, invoices, and bank statements.','Keep your cash flows and accounts organized. We log supplier invoices, record client payments, map expenses, and reconcile bank statements following standard guidelines.',NULL,NULL,'[\"QuickBooks & Xero Mapping\",\"Daily Expense Audits\",\"Statement Reconciling\",\"Tax Prep Support\"]','[\"Accurate financial ledger\",\"Faster tax filing\",\"Lower accounting overhead\"]',1,18,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(19,4,'administrative-support','Administrative Support','/assets/images/service-bpo.svg','Support for business planning, calendar booking, and email management.','Get support for your daily administrative tasks. We manage executive calendars, draft standard memos, coordinate company travel details, and filter client messages.',NULL,NULL,'[\"Calendar Coordination\",\"Email Filtering\",\"Document Archiving\",\"Meeting Scheduling\"]','[\"Reduced desk overhead\",\"Organized operations\",\"More time for core growth\"]',1,19,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(20,5,'billing-support','Billing Support','/assets/images/service-backoffice.svg','Create and dispatch client invoices, track dues, and manage bill processing.','Accelerate your invoice cycles. We generate accurate customer bills, dispatch invoice notices, verify payment terms, and track overdue balances proactively.',NULL,NULL,'[\"Invoice Creation\",\"Ageing Balances Tracking\",\"Dispute Resolution\",\"Payment Link Integration\"]','[\"Better cash flows\",\"Fewer billing disputes\",\"Faster client payments\"]',1,20,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(21,5,'accounts-management','Accounts Management','/assets/images/service-validation.svg','Support for accounts receivable (AR) and accounts payable (AP) processes.','Manage incoming and outgoing payment pipelines. We process supplier bills, check purchase orders, reconcile ledger accounts, and ensure invoices are paid on time.',NULL,NULL,'[\"AP Invoice Indexing\",\"AR Ledger Reconciliations\",\"Vendor Communication\",\"Payment Scheduling\"]','[\"Lower ledger overhead\",\"Improved vendor relationships\",\"Fewer payment delays\"]',1,21,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(22,5,'financial-processing-services','Financial Processing Services','/assets/images/service-mortgage.svg','Process expense claims, payroll, tax documentation, and merchant payments.','Outsource complex payment transaction processing. We audit employee business expense sheets, calculate payroll details, process merchant credit card transactions, and file standard tax documentation.',NULL,NULL,'[\"Expense Validation\",\"Payroll Calculations\",\"Compliance Auditing\",\"Refund Processing\"]','[\"Fewer payroll errors\",\"Improved tax compliance\",\"Quick merchant reconciliation\"]',1,22,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(23,5,'accounting-support','Accounting Support','/assets/images/service-db.svg','Draft income statements, prepare balance sheets, and audit system entries.','Support your finance directors with organized ledgers. We prepare draft balance sheets, run reconciliation audits, format general ledgers, and compile files for tax audits.',NULL,NULL,'[\"Ledger Close Support\",\"Reconcile Assets\",\"Audit File prep\",\"Financial Formatting\"]','[\"Audit-ready accounts\",\"Lower accountant fees\",\"Clear business statistics\"]',1,23,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(24,6,'recruitment-process-outsourcing','Recruitment Process Outsourcing (RPO)','/assets/images/service-bpo.svg','Outsource candidate sourcing, interview booking, and recruiter support.','Scale your hiring capability quickly. We manage job listings, source active resumes from platforms, coordinate candidate communications, and book interviews for your team.',NULL,NULL,'[\"Resume Sourcing\",\"ATS Logging\",\"Interview Bookings\",\"Feedback Coordination\"]','[\"Faster hires\",\"Lower cost-per-hire\",\"Qualified talent pipelines\"]',1,24,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(25,6,'candidate-screening','Candidate Screening','/assets/images/service-validation.svg','Run resume filter audits, verify experiences, and check applicant references.','Ensure you hire the right fit. We check candidate employment histories, call reference lists, review certificates, and filter out unqualified applicants.',NULL,NULL,'[\"Reference Check Calls\",\"Degree Verification\",\"Skills Assessments\",\"Profile Summarization\"]','[\"Better hire quality\",\"Lower hiring risks\",\"Clean background data\"]',1,25,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(26,6,'talent-acquisition-support','Talent Acquisition Support','/assets/images/service-language.svg','Identify specialized profiles, build talent pools, and manage outreach.','Target and source talent for niche roles. We research industry professionals, run cold outreach campaigns, and present pre-screened talent cards to your hiring team.',NULL,NULL,'[\"Niche Role Sourcing\",\"Outreach Writing\",\"Talent Database Logs\",\"Market Rate Audits\"]','[\"Direct access to talent\",\"Better industry insight\",\"Reduced hire delays\"]',1,26,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(27,6,'hr-operations-support','HR Operations Support','/assets/images/service-backoffice.svg','Track worker attendance, manage employee files, and log HR requests.','Outsource your employee administration overhead. We manage employee files, log leave requests, monitor onboarding progress, and handle standard payroll documentation.',NULL,NULL,'[\"Leave System Checks\",\"Onboarding Logs\",\"Worker Record Audits\",\"Benefits Administration\"]','[\"Organized employee logs\",\"Reduced compliance risk\",\"Lower admin costs\"]',1,27,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(28,7,'research-support','Research Support','/assets/images/service-db.svg','Market research, competitor analysis, database compilation, and trend research.','Make business decisions backed by clean, structured research. We compile market reports, map competitor pricing, organize target industry databases, and track online search trends.',NULL,NULL,'[\"Market Sizing Reports\",\"Competitor Auditing\",\"Database Compilations\",\"Trend Tracking\"]','[\"Data-driven strategies\",\"Identified growth areas\",\"Better competitive edge\"]',1,28,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(29,7,'business-process-management','Business Process Management','/assets/images/service-bpo.svg','Design, map, optimize, and document your operational workflows.','Improve standard operating procedures. We audit current department workflows, identify bottlenecks, map standard flows, and draft clear training documents.',NULL,NULL,'[\"SOP Writing\",\"Workflow Mapping\",\"Efficiency Audits\",\"Team Training Guides\"]','[\"Increased operating speed\",\"Consistent quality outputs\",\"Faster staff onboarding\"]',1,29,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(30,7,'process-excellence-services','Process Excellence Services','/assets/images/service-validation.svg','Six Sigma workflow design, quality assurance checks, and optimization audits.','Achieve operational excellence. We implement quality monitoring systems, review agent calls and files, log errors, and coordinate process improvements to lower mistakes.',NULL,NULL,'[\"Quality Audits\",\"Error Logs Tracking\",\"Six Sigma Methods\",\"KPI Reports\"]','[\"Lower mistake rates\",\"Highly efficient workflows\",\"Enhanced customer satisfaction\"]',1,30,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(31,7,'project-management-support','Project Management Support','/assets/images/service-software.svg','Project tracking, milestone scheduling, task updates, and report delivery.','Keep complex projects on track. We manage task updates in software (Jira, Trello, Asana), track milestones, coordinate client updates, and compile progress reports.',NULL,NULL,'[\"Milestone Tracking\",\"PM Software Logs\",\"Status Reporting\",\"Team coordination\"]','[\"On-time delivery\",\"Transparent timelines\",\"Reduced coordination costs\"]',1,31,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(32,8,'online-reviews-verification-services','Online Reviews Verification Services','/assets/images/service-validation.svg','Audit review sites for spam, identify fake comments, and report violations.','Ensure your online ratings remain genuine. We audit platforms (Google Business, Trustpilot, App Store) for spam or fake negative reviews, file disputes, and track resolution progress.',NULL,NULL,'[\"Review Auditing\",\"Platform Dispute Filings\",\"Spam detection\",\"Status Logs\"]','[\"Higher rating trust\",\"Spam protection\",\"Improved search CTR\"]',1,32,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(33,8,'customer-feedback-management','Customer Feedback Management','/assets/images/service-moderation.svg','Monitor ratings, draft replies, and compile customer issues reports.','Respond to customer reviews professionally. We draft custom replies to negative and positive reviews, help resolve client complaints, and log common issues for your product team.',NULL,NULL,'[\"Review Replies\",\"Issue Escalations\",\"Sentiment Analysis\",\"Feedback Reports\"]','[\"Better brand trust\",\"Fewer churned customers\",\"Identified product issues\"]',1,33,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(34,9,'domestic-voice-processes','Domestic Voice Processes','/assets/images/service-callcenter.svg','High-quality customer support and sales operations for local audiences.','Reach and support your national customer base. We provide call center operations optimized for domestic customers, offering support, lead verification, telesales, and account help.',NULL,NULL,'[\"Local Support Agents\",\"High Call Capacity\",\"CRM Integrations\",\"Call Recording\"]','[\"Lower call queue times\",\"Optimized localized support\",\"Efficient sales dialing\"]',1,34,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(35,9,'international-voice-processes','International Voice Processes','/assets/images/service-language.svg','Global outbound and inbound voice solutions with accent-trained agents.','Provide accent-trained, professional voice support to international callers. We handle queries, troubleshoot tech problems, confirm order details, and follow up on global leads 24/7.',NULL,NULL,'[\"Global dialer support\",\"Accent Training\",\"24\\/7 Timezone coverage\",\"International VoIP routes\"]','[\"Improved global retention\",\"Round-the-clock availability\",\"Professional brand voice\"]',1,35,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(36,9,'non-voice-processes','Non-Voice Processes','/assets/images/service-backoffice.svg','Support for chat, email, ticket entry, and backend tasks.','Handle high-volume tickets without voice overhead. We manage customer support tickets, process application forms, log customer requests, and audit accounts via text channels.',NULL,NULL,'[\"Email & Chat queues\",\"Form Transcriptions\",\"High SLA compliance\",\"Database Logging\"]','[\"Lower cost per ticket\",\"Efficient bulk handling\",\"Flexible agent scaling\"]',1,36,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(37,9,'shared-service-center-operations','Shared Service Center Operations','/assets/images/service-bpo.svg','Consolidate multiple company departments into one shared operations center.','Improve operational efficiency by combining backend tasks. We set up shared services for data management, invoice logging, employee benefits, and basic customer help across your subsidiaries.',NULL,NULL,'[\"Cross-trained Agents\",\"Standardized SOPs\",\"Unified Reporting\",\"Resource optimization\"]','[\"Higher cost savings\",\"Standardized quality\",\"Simplified management\"]',1,37,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29'),(38,9,'24x7-customer-engagement-solutions','24x7 Customer Engagement Solutions','/assets/images/service-callcenter.svg','Ensure continuous coverage for customer inquiries across all global timezones.','Keep your support channels active 24/7. We schedule day, night, and weekend shifts to monitor incoming chats, answer urgent calls, reply to emails, and ensure your customers always get assistance.',NULL,NULL,'[\"24\\/7 Shift Rotations\",\"Cross-channel Monitoring\",\"Emergency Escalations\",\"Consistent SLAs\"]','[\"Zero missed opportunities\",\"Stronger global retention\",\"Outstanding service reviews\"]',1,38,'2026-06-25 11:53:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-25 12:34:29');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` longtext DEFAULT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES ('about_full_history','Clevora was established in 2011 in Delhi, India, with a vision to provide world-class business process outsourcing solutions. Over the last decade, we have expanded from a small team of 10 agents to a global enterprise service provider. Our operations span across call center support, technical helpdesks, data entry services, content moderation, and custom software engineering. We maintain high standards of quality assurance and data security, keeping in line with modern compliance frameworks.'),('about_home_text','Clevora is a privately held organization and was incorporated in 2011. Since then Clevora has gained deep business insights and expertise in the outsourcing industry. We are proud of our client-centric models and dynamic execution strategies that yield concrete results. Our state-of-the-art infrastructure enables seamless remote team integrations and guarantees continuous business performance.'),('about_mission','To empower organizations globally by providing high-quality, secure, and cost-effective outsourcing services that maximize operating efficiency.'),('about_vision','To be the preferred global outsourcing partner recognized for operational excellence, integrity, and innovative business process solutions.'),('contact_address','Delhi, India'),('contact_email','info@clevora.in'),('contact_hours',''),('contact_map_embed',''),('contact_phone','+91 9953310085'),('contact_whatsapp',''),('cta_button_text','Get a Free Quote'),('cta_button_url','contact.php'),('cta_heading','Ready to become our next success story? Updated'),('cta_text','Tell us about your operational challenge. We will help build a scalable outsourcing solution.'),('footer_subscribe_text','Clevora takes its technological capabilities very seriously and has acquired all the technologies that are required for running a successful call center in this competitive market segment.'),('hero_bullets','Preventing Oversized Non-Standard Data Formats\nMultiple Sourcing and Non-Standard Data systems\nAddress Verification, Postal Code Correction, NCOA and Standardization\nCleaning Databases, Address Checking, Output to Printer, E-Mailer, or Printed Mailing Catalog'),('hero_cta_text','Contact Us'),('hero_headline','Database Management'),('management_founder_bio','Mayank Chandhok founded Clevora in 2011 with the goal of bridging the gap between global enterprises and skilled talent in India. Under his leadership, Clevora has grown into a trusted outsourcing partner for hundreds of organizations globally.'),('management_founder_name','Mayank Chandhok'),('management_founder_role','Founder & Managing Director'),('social_facebook',''),('social_linkedin',''),('social_linktree',''),('social_xing',''),('stats_clients','5000'),('stats_industries','50'),('stats_projects','5500'),('stats_resumes','1670'),('tech_infrastructure','Our production servers are housed in a Tier-3 secure data center in Delhi. We maintain redundant fiber-optic connectivity from multiple internet service providers, automated daily backup protocols, and UPS backup battery arrays alongside on-site diesel generators to guarantee 99.9% network availability. Physical access to our production rooms is restricted with biometric authorization.');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
INSERT INTO `subscribers` VALUES (1,'manish.work118@gmail.com','active','2026-06-25 11:29:47');
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technology_sections`
--

DROP TABLE IF EXISTS `technology_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technology_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(100) DEFAULT '',
  `section_title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT '',
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT '?',
  `badge_text` varchar(150) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technology_sections`
--

LOCK TABLES `technology_sections` WRITE;
/*!40000 ALTER TABLE `technology_sections` DISABLE KEYS */;
INSERT INTO `technology_sections` VALUES (1,'infrastructure','Infrastructure','','Enterprise-ready infrastructure designed to support secure, stable, and scalable global outsourcing operations.','🏗️','',1,1,'2026-06-26 05:01:04','2026-06-26 05:01:04'),(2,'security','Security & Compliance','','Structured security practices, controlled access, confidentiality processes, and responsible data handling standards.','🔒','',2,1,'2026-06-26 05:01:04','2026-06-26 05:01:04'),(3,'workflow','Workflow Systems','','Organized workflow management systems designed for productivity tracking, process visibility, and consistent delivery.','⚙️','',3,1,'2026-06-26 05:01:04','2026-06-26 05:01:04'),(4,'quality','Quality Monitoring','','Performance reviews, quality checks, reporting, and improvement processes ensure reliable service delivery.','📊','',4,1,'2026-06-26 05:01:04','2026-06-26 05:01:04'),(5,'backup','Backup & Business Continuity','','Reliable backup practices and continuity planning help maintain smooth business operations.','💾','',5,1,'2026-06-26 05:01:04','2026-06-26 05:01:04'),(6,'operations','Operations Technology','','Modern communication platforms and operational tools enable efficient customer support and business management.','🌐','',6,1,'2026-06-26 05:01:04','2026-06-26 05:01:04');
/*!40000 ALTER TABLE `technology_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technology_settings`
--

DROP TABLE IF EXISTS `technology_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technology_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technology_settings`
--

LOCK TABLES `technology_settings` WRITE;
/*!40000 ALTER TABLE `technology_settings` DISABLE KEYS */;
INSERT INTO `technology_settings` VALUES (1,'hero_small_text','Clevora Global Operations','2026-06-25 16:20:28','2026-06-25 16:20:28'),(2,'hero_title','Technology','2026-06-25 16:20:28','2026-06-25 16:20:28'),(3,'breadcrumb_title','Technology','2026-06-25 16:20:28','2026-06-25 16:20:28'),(4,'main_label','Our Capabilities','2026-06-25 16:20:28','2026-06-25 16:20:28'),(5,'main_heading','Secure Operations & Technology Infrastructure','2026-06-25 16:20:28','2026-06-25 16:20:28'),(6,'main_description','Clevora combines skilled teams, modern infrastructure, secure processes, and advanced operational systems to deliver reliable outsourcing services worldwide.','2026-06-25 16:20:28','2026-06-25 16:20:28'),(7,'security_title','Data Protection & Security','2026-06-25 16:20:28','2026-06-25 16:20:28'),(8,'security_description','We implement controlled access, secure workflows, confidentiality practices, and operational safeguards to protect client information.','2026-06-25 16:20:28','2026-06-25 16:20:28'),(9,'security_badge','Security & Compliance Assured','2026-06-25 16:20:28','2026-06-25 16:20:28');
/*!40000 ALTER TABLE `technology_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `position` varchar(150) DEFAULT NULL,
  `company` varchar(150) DEFAULT NULL,
  `industry` varchar(150) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Rahul Kumar Gupta','Karnal, India','/assets/images/testimonial-1.jpg','This is very satisfied that Clevora provides us with great satisfaction and we are extremely happy with their operations and quality standards.',1,NULL,NULL,NULL,5,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(2,'Patrick John','California, USA','/assets/images/testimonial-2.jpg','I am most satisfied customer after working with clevora. Their round-the-clock availability has changed how we do our business operations.',1,NULL,NULL,NULL,5,'2026-06-25 12:34:29','2026-06-25 12:34:29'),(3,'John Andrea','Berlin, Germany','/assets/images/testimonial-3.jpg','We have been running outsourcing through Clevora for some time now. Their professional multilingual team handles our tickets efficiently.',1,NULL,NULL,NULL,5,'2026-06-25 12:34:29','2026-06-25 12:34:29');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-26 12:39:48
