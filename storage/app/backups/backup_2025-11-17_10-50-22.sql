-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: dental_care_saas
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `appointment_conflicts`
--

DROP TABLE IF EXISTS `appointment_conflicts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointment_conflicts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint(20) unsigned NOT NULL,
  `conflicting_appointment_id` bigint(20) unsigned NOT NULL,
  `conflict_type` varchar(255) NOT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_conflicts_appointment_id_foreign` (`appointment_id`),
  KEY `appointment_conflicts_conflicting_appointment_id_foreign` (`conflicting_appointment_id`),
  CONSTRAINT `appointment_conflicts_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointment_conflicts_conflicting_appointment_id_foreign` FOREIGN KEY (`conflicting_appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_conflicts`
--

LOCK TABLES `appointment_conflicts` WRITE;
/*!40000 ALTER TABLE `appointment_conflicts` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointment_conflicts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 30,
  `type` varchar(255) NOT NULL DEFAULT 'consultation',
  `status` enum('scheduled','confirmed','in_progress','completed','cancelled','no_show') NOT NULL DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `treatment_cost` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','partial','paid','refunded') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_clinic_id_appointment_date_index` (`clinic_id`,`appointment_date`),
  KEY `appointments_clinic_id_status_index` (`clinic_id`,`status`),
  KEY `appointments_patient_id_status_index` (`patient_id`,`status`),
  KEY `appointments_dentist_id_appointment_date_index` (`dentist_id`,`appointment_date`),
  KEY `appointments_created_at_index` (`created_at`),
  KEY `appointments_clinic_date_status_idx` (`clinic_id`,`appointment_date`,`status`),
  CONSTRAINT `appointments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,1,1,3,'2025-11-18','10:00:00',30,'consultation','scheduled','Regular checkup and cleaning',NULL,'pending','2025-11-17 02:23:39','2025-11-17 02:23:39'),(2,1,2,3,'2025-11-19','14:00:00',30,'consultation','scheduled','Cavity treatment required',NULL,'pending','2025-11-17 02:23:39','2025-11-17 02:23:39'),(3,2,3,7,'2025-11-18','11:00:00',30,'consultation','scheduled','Root canal treatment',NULL,'pending','2025-11-17 02:23:39','2025-11-17 02:23:39'),(4,2,4,7,'2025-11-20','15:30:00',30,'consultation','scheduled','Initial consultation',NULL,'pending','2025-11-17 02:23:39','2025-11-17 02:23:39');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `clinic_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `severity` varchar(255) NOT NULL DEFAULT 'info',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_clinic_id_created_at_index` (`clinic_id`,`created_at`),
  KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  CONSTRAINT `audit_logs_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('email','sms','social_media','advertisement') NOT NULL DEFAULT 'email',
  `status` enum('draft','active','paused','completed') NOT NULL DEFAULT 'draft',
  `target_audience` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_audience`)),
  `budget` decimal(10,2) DEFAULT NULL,
  `total_sent` int(11) NOT NULL DEFAULT 0,
  `total_opened` int(11) NOT NULL DEFAULT 0,
  `total_clicked` int(11) NOT NULL DEFAULT 0,
  `total_converted` int(11) NOT NULL DEFAULT 0,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinic_chains`
--

DROP TABLE IF EXISTS `clinic_chains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinic_chains` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clinic_chains_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinic_chains`
--

LOCK TABLES `clinic_chains` WRITE;
/*!40000 ALTER TABLE `clinic_chains` DISABLE KEYS */;
/*!40000 ALTER TABLE `clinic_chains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinics`
--

DROP TABLE IF EXISTS `clinics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Nepal',
  `theme_color` varchar(255) NOT NULL DEFAULT '#2563eb',
  `postal_code` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services`)),
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `primary_color` varchar(255) NOT NULL DEFAULT '#3B82F6',
  `accent_color` varchar(255) NOT NULL DEFAULT '#3b82f6',
  `secondary_color` varchar(255) NOT NULL DEFAULT '#1E40AF',
  `subscription_status` enum('active','inactive','suspended','trial') NOT NULL DEFAULT 'trial',
  `subscription_plan` enum('basic','standard','premium') NOT NULL DEFAULT 'basic',
  `subscription_expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `chain_id` bigint(20) unsigned DEFAULT NULL,
  `is_main_location` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clinics_slug_unique` (`slug`),
  UNIQUE KEY `clinics_email_unique` (`email`),
  KEY `clinics_chain_id_foreign` (`chain_id`),
  KEY `clinics_is_active_subscription_status_index` (`is_active`,`subscription_status`),
  KEY `clinics_slug_index` (`slug`),
  KEY `clinics_created_at_index` (`created_at`),
  CONSTRAINT `clinics_chain_id_foreign` FOREIGN KEY (`chain_id`) REFERENCES `clinic_chains` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinics`
--

LOCK TABLES `clinics` WRITE;
/*!40000 ALTER TABLE `clinics` DISABLE KEYS */;
INSERT INTO `clinics` VALUES (1,'Smile Dental Clinic','smile-dental-clinic','info@smiledentalclinic.com','+977-1-4567890','Kathmandu, Nepal',NULL,NULL,'Nepal','#2563eb',NULL,'https://smiledentalclinic.com',NULL,NULL,'Premier dental care services in Kathmandu',NULL,NULL,NULL,NULL,NULL,NULL,'#3B82F6','#3b82f6','#1E40AF','active','basic',NULL,1,'2025-11-17 02:22:34','2025-11-17 02:22:34',NULL,1),(2,'Perfect Teeth Clinic','perfect-teeth-clinic','info@perfectteeth.com','+977-1-9876543','Pokhara, Nepal',NULL,NULL,'Nepal','#2563eb',NULL,'https://perfectteeth.com',NULL,NULL,'Advanced dental treatments in Pokhara',NULL,NULL,NULL,NULL,NULL,NULL,'#3B82F6','#3b82f6','#1E40AF','active','basic',NULL,1,'2025-11-17 02:22:47','2025-11-17 02:22:47',NULL,1);
/*!40000 ALTER TABLE `clinics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_logs`
--

DROP TABLE IF EXISTS `email_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `to_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `status` enum('sent','failed','bounced','opened','clicked') NOT NULL DEFAULT 'sent',
  `campaign_id` bigint(20) unsigned DEFAULT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `opened_at` timestamp NULL DEFAULT NULL,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_logs_campaign_id_foreign` (`campaign_id`),
  KEY `email_logs_lead_id_foreign` (`lead_id`),
  CONSTRAINT `email_logs_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  CONSTRAINT `email_logs_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_logs`
--

LOCK TABLES `email_logs` WRITE;
/*!40000 ALTER TABLE `email_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emails`
--

DROP TABLE IF EXISTS `emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emails` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint(20) unsigned NOT NULL,
  `recipients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`recipients`)),
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `status` enum('draft','sent','failed') NOT NULL DEFAULT 'draft',
  `sent_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emails_sender_id_foreign` (`sender_id`),
  KEY `emails_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `emails_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`),
  CONSTRAINT `emails_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emails`
--

LOCK TABLES `emails` WRITE;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `min_quantity` int(11) NOT NULL DEFAULT 10,
  `unit_cost` decimal(10,2) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_items_sku_unique` (`sku`),
  KEY `inventory_items_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `inventory_items_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `due_date` date NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `issued_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_appointment_id_foreign` (`appointment_id`),
  KEY `invoices_clinic_id_status_index` (`clinic_id`,`status`),
  KEY `invoices_patient_id_status_index` (`patient_id`,`status`),
  KEY `invoices_due_date_status_index` (`due_date`,`status`),
  KEY `invoices_created_at_index` (`created_at`),
  CONSTRAINT `invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `landing_page_contents`
--

DROP TABLE IF EXISTS `landing_page_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `landing_page_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned DEFAULT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_subtitle` text DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `hero_cta_primary` varchar(255) DEFAULT NULL,
  `hero_cta_secondary` varchar(255) DEFAULT NULL,
  `trust_clinics` varchar(255) DEFAULT NULL,
  `trust_patients` varchar(255) DEFAULT NULL,
  `trust_appointments` varchar(255) DEFAULT NULL,
  `trust_uptime` varchar(255) DEFAULT NULL,
  `trust_revenue` varchar(255) DEFAULT NULL,
  `about_title` varchar(255) DEFAULT NULL,
  `about_description` text DEFAULT NULL,
  `about_image` varchar(255) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `company_name` varchar(255) DEFAULT NULL,
  `company_tagline` varchar(255) DEFAULT NULL,
  `company_rating` varchar(255) DEFAULT NULL,
  `company_description` text DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `contact_title` varchar(255) DEFAULT NULL,
  `contact_subtitle` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `footer_description` text DEFAULT NULL,
  `footer_copyright` varchar(255) DEFAULT NULL,
  `theme_primary_color` varchar(255) DEFAULT NULL,
  `theme_secondary_color` varchar(255) DEFAULT NULL,
  `theme_accent_color` varchar(255) DEFAULT NULL,
  `theme_template` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `landing_page_contents_clinic_id_is_active_index` (`clinic_id`,`is_active`),
  CONSTRAINT `landing_page_contents_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `landing_page_contents`
--

LOCK TABLES `landing_page_contents` WRITE;
/*!40000 ALTER TABLE `landing_page_contents` DISABLE KEYS */;
INSERT INTO `landing_page_contents` VALUES (1,1,'Welcome to Smile Dental Clinic','Your trusted dental care partner in Kathmandu',NULL,'Book Appointment','Learn More',NULL,NULL,NULL,NULL,NULL,'Expert Dental Care','We provide comprehensive dental services with state-of-the-art technology and experienced professionals.',NULL,NULL,'Smile Dental Clinic','Your Smile, Our Priority','4.8','Leading dental clinic in Kathmandu with over 10 years of experience.',NULL,'Get in Touch','Schedule your appointment today','+977-1-4567890','info@smiledentalclinic.com','Kathmandu, Nepal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2025-11-17 02:23:52','2025-11-17 02:23:52'),(2,2,'Perfect Teeth Clinic','Advanced dental treatments in beautiful Pokhara',NULL,'Book Now','View Services',NULL,NULL,NULL,NULL,NULL,'Modern Dental Solutions','Experience the latest in dental technology and personalized care in the heart of Pokhara.',NULL,NULL,'Perfect Teeth Clinic','Perfect Smile, Perfect Life','4.9','Pokhara\'s premier dental clinic offering world-class treatments.',NULL,'Visit Us Today','Your perfect smile awaits','+977-1-9876543','info@perfectteeth.com','Pokhara, Nepal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2025-11-17 02:23:52','2025-11-17 02:23:52');
/*!40000 ALTER TABLE `landing_page_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `status` enum('new','contacted','qualified','converted','lost') NOT NULL DEFAULT 'new',
  `source` enum('website','referral','social_media','advertisement','other') NOT NULL DEFAULT 'website',
  `notes` text DEFAULT NULL,
  `potential_value` decimal(10,2) DEFAULT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2024_01_01_000001_create_clinics_table',1),(2,'2024_01_01_000002_create_users_table',1),(3,'2024_01_01_000003_create_patients_table',1),(4,'2024_01_01_000004_create_appointments_table',1),(5,'2024_01_01_000005_create_invoices_table',1),(6,'2025_01_01_000001_create_phase2_tables',1),(7,'2025_01_01_000002_create_clinic_chains_table',1),(8,'2025_11_11_053252_create_cache_table',1),(9,'2025_11_11_104023_fix_database_schema',1),(10,'2025_11_11_105649_create_subscriptions_table',1),(11,'2025_11_11_110623_create_staff_management_tables',1),(12,'2025_11_12_111030_create_leads_table',1),(13,'2025_11_12_111038_create_campaigns_table',1),(14,'2025_11_12_111046_create_email_logs_table',1),(15,'2025_11_13_091324_create_payments_table',1),(16,'2025_11_13_091809_add_missing_columns_to_invoices_table',1),(17,'2025_11_13_091941_fix_invoices_table_structure',1),(18,'2025_11_13_132724_create_landing_page_contents_table',1),(19,'2025_11_13_135048_add_gallery_to_landing_page_contents_table',1),(20,'2025_11_13_140841_create_permission_tables',1),(21,'2025_11_15_163305_add_category_to_permissions_table',1),(22,'2025_11_15_171930_add_landing_page_fields_to_clinics_table',1),(23,'2025_11_16_044230_fix_treatment_plans_table',1),(24,'2025_11_16_052710_create_emails_table',1),(25,'2025_11_16_052710_create_notifications_table',1),(26,'2025_11_16_062303_optimize_database_indexes',1),(27,'2025_11_17_075332_create_jobs_table',1),(28,'2025_11_17_075556_create_failed_jobs_table',1),(29,'2025_11_17_075638_create_job_batches_table',1),(30,'2025_11_17_093249_modify_patients_table_for_encryption',2),(31,'2025_11_17_104541_create_audit_logs_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(2,'App\\Models\\User',6),(3,'App\\Models\\User',3),(3,'App\\Models\\User',7),(4,'App\\Models\\User',4),(4,'App\\Models\\User',8),(5,'App\\Models\\User',5),(5,'App\\Models\\User',9);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('email','system','appointment','payment') NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  KEY `notifications_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,2,'Welcome to Email & Notifications System','Your communication system is now fully functional. You can send emails and receive notifications.','system',NULL,NULL,'2025-11-17 04:28:12','2025-11-17 04:28:12');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `patient_invoices`
--

DROP TABLE IF EXISTS `patient_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','sent','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_invoices_invoice_number_unique` (`invoice_number`),
  KEY `patient_invoices_clinic_id_foreign` (`clinic_id`),
  KEY `patient_invoices_patient_id_foreign` (`patient_id`),
  CONSTRAINT `patient_invoices_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_invoices`
--

LOCK TABLES `patient_invoices` WRITE;
/*!40000 ALTER TABLE `patient_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `emergency_contact_name` text DEFAULT NULL,
  `emergency_contact_phone` text DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `insurance_provider` text DEFAULT NULL,
  `insurance_number` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_patient_id_unique` (`patient_id`),
  KEY `patients_clinic_id_is_active_index` (`clinic_id`,`is_active`),
  KEY `patients_clinic_id_email_index` (`clinic_id`,`email`),
  KEY `patients_clinic_id_patient_id_index` (`clinic_id`,`patient_id`),
  KEY `patients_created_at_index` (`created_at`),
  KEY `patients_clinic_created_active_idx` (`clinic_id`,`created_at`,`is_active`),
  CONSTRAINT `patients_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,1,NULL,'Hari Bahadur','Magar','hari@example.com','+977-9841111111','1985-05-15','male','Thamel, Kathmandu',NULL,NULL,NULL,'Rita Magar','+977-9841111112','No known allergies',NULL,NULL,NULL,1,'2025-11-17 02:23:23','2025-11-17 02:23:23'),(2,1,NULL,'Kamala Devi','Sharma','kamala@example.com','+977-9841111113','1990-08-22','female','Patan, Lalitpur',NULL,NULL,NULL,'Ram Sharma','+977-9841111114','Diabetes',NULL,NULL,NULL,1,'2025-11-17 02:23:23','2025-11-17 02:23:23'),(3,2,NULL,'Binod Kumar','Thapa','binod@example.com','+977-9851111111','1988-12-10','male','Lakeside, Pokhara',NULL,NULL,NULL,'Mina Thapa','+977-9851111112','Hypertension',NULL,NULL,NULL,1,'2025-11-17 02:23:23','2025-11-17 02:23:23'),(4,2,NULL,'Sushma','Gurung','sushma@example.com','+977-9851111113','1992-03-18','female','Mahendrapul, Pokhara',NULL,NULL,NULL,'Dhan Gurung','+977-9851111114','No known conditions',NULL,NULL,NULL,1,'2025-11-17 02:23:23','2025-11-17 02:23:23');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_installments`
--

DROP TABLE IF EXISTS `payment_installments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_installments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_plan_id` bigint(20) unsigned NOT NULL,
  `installment_number` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `status` enum('pending','paid','overdue') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_installments_payment_plan_id_foreign` (`payment_plan_id`),
  CONSTRAINT `payment_installments_payment_plan_id_foreign` FOREIGN KEY (`payment_plan_id`) REFERENCES `payment_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_installments`
--

LOCK TABLES `payment_installments` WRITE;
/*!40000 ALTER TABLE `payment_installments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_installments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_plans`
--

DROP TABLE IF EXISTS `payment_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `down_payment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `installments` int(11) NOT NULL,
  `installment_amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `status` enum('active','completed','defaulted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_plans_clinic_id_foreign` (`clinic_id`),
  KEY `payment_plans_patient_id_foreign` (`patient_id`),
  KEY `payment_plans_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `payment_plans_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_plans_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_plans_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_plans`
--

LOCK TABLES `payment_plans` WRITE;
/*!40000 ALTER TABLE `payment_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','esewa','khalti','bank_transfer') NOT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  KEY `payments_patient_id_foreign` (`patient_id`),
  KEY `payments_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `payments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage-users',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(2,'manage-clinics',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(3,'manage-subscriptions',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(4,'view-analytics',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(5,'manage-patients',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(6,'manage-appointments',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(7,'manage-invoices',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(8,'manage-staff',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(9,'manage-settings',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(10,'view-reports',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(11,'manage-treatments',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(12,'manage-landing-page',NULL,NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recurring_appointments`
--

DROP TABLE IF EXISTS `recurring_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recurring_appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `frequency` varchar(255) NOT NULL,
  `interval_count` int(11) NOT NULL DEFAULT 1,
  `days_of_week` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`days_of_week`)),
  `appointment_time` time NOT NULL,
  `type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recurring_appointments_clinic_id_foreign` (`clinic_id`),
  KEY `recurring_appointments_patient_id_foreign` (`patient_id`),
  KEY `recurring_appointments_dentist_id_foreign` (`dentist_id`),
  CONSTRAINT `recurring_appointments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recurring_appointments_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recurring_appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recurring_appointments`
--

LOCK TABLES `recurring_appointments` WRITE;
/*!40000 ALTER TABLE `recurring_appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `recurring_appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(5,2),(5,3),(5,4),(6,1),(6,2),(6,3),(6,4),(7,1),(7,2),(7,5),(8,1),(8,2),(9,1),(9,2),(10,1),(10,2),(10,3),(10,5),(11,1),(11,2),(11,3),(12,1),(12,2);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'superadmin',NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(2,'clinic_admin',NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(3,'dentist',NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(4,'receptionist',NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48'),(5,'accountant',NULL,NULL,'web','2025-11-17 02:21:48','2025-11-17 02:21:48');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `sessions` VALUES ('6LPrEsYmyMMg46wFbaKjJagPr6QWyrGAGkPTmcSG',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ2U1dEJhRDI4Y0FvSXlWZEdlRjJSNUoxUE5BNFhmOWtleWFuSzhvayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763373017),('CDpEhnrwawJLlRPKJiDhRtsqzcgAmicSUpswyJIQ',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3lUYkdYMUJhWE9wVUZMc21qM1R0OW4xdGFhZENZa2lrTWZwc1VDRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljL3JlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763372634),('Ga3RYPhUMF8SRd1U0Qa4otSROP6CLjZORimsdMxJ',4,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:145.0) Gecko/20100101 Firefox/145.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMU8wV002RUtGWlFESEZ6RlZOekk3T2pIWFd5VGlvc1FrTlBxQXNyWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=',1763375468),('goLxKoaZzAOX01vIFsZJWEeXYXzC1sWKBtjbV0RT',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZlZVZVJ2OEJra29SMVk5OEZYUFJkd3pOeHlubXVJUWtseGQzdklsdyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1763375538),('iSnvr9kFoOSO1AEeygTkWgTlywy7IoXO7g8CddSX',NULL,'::1','curl/8.5.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoid010OHBGSEN1cEp6bTFNTkgxUXVGVXlXR1JuVHhLZXA5M0tHOENhbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovL2xvY2FsaG9zdC9kZW50YWwtY2FyZS9wdWJsaWMvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljL2Rhc2hib2FyZCI7fX0=',1763372675),('kkgiZLWbLjIcGa7WbMSXXJXI0RkATeWcycdsGghU',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3FUOW50NWxnV2IyejlXaTZldUpQOGt4YUtFS2N0T0hBT3ZRdUM3OCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763372634),('LaTJuUuVyTD3P3emtnGMOvHxkuTCeLWpUlItbKbS',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY3dJc0JMcjhEZlJLUGNLcTRZVlNLT2RJbXVFWU41d1NuRzZoa0hKOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763372752),('QgT43Fiqu0dTEHxvDMEXy5dG6oCCzC5N4ROGx5Au',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:145.0) Gecko/20100101 Firefox/145.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRUIzMm8xeTl1YjZ4VmtJQmsxdE9LaU9RajBHaWhuREZRc1pQUUs4cSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdXBlcmFkbWluL2NybS9lbWFpbC1sb2dzIjt9czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3JvbGVzLzUvZWRpdCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1763375467),('UCuYRZ2iVJylPjca8ZZ6anyJmGy2nFM8tBo6mAwJ',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoic1hXVDR3YmJzWkZmYVZtYXNrY3o0MHNWQUlTeDQwWWlzUnBDb05abiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763372634),('UmQXjLbqpAX7jDdoYtKlZWCpsi9bTC81NUCtpL11',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoid09vTk1McmlXZFBucktYT1N5dEZHVENreEhEYWJ0SUVuNUphQkt1UCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdXBlcmFkbWluL2NybS9lbWFpbC1sb2dzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1763375467),('vKhPgH8Coo3JbIA0WVxFZHotPsX5vjTa2D9kFFKX',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiamNPck5TemFleTNZd01IQXNtM0pSSmE4bnRaOHhDQnBLZVU3TXFNNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763372675),('WR8AduiPFR7eijUZ8qzieAJCAoBelvcb0NhfiZEx',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZVRIYUkxN1Ewa1FBdWg3RHVlSUtkdUpRTWtPNWM0T1dwdkFUVEMzYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3QvZGVudGFsLWNhcmUvcHVibGljL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763372654);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_locations`
--

DROP TABLE IF EXISTS `staff_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_locations_user_id_foreign` (`user_id`),
  KEY `staff_locations_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `staff_locations_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_locations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_locations`
--

LOCK TABLES `staff_locations` WRITE;
/*!40000 ALTER TABLE `staff_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_plans`
--

DROP TABLE IF EXISTS `subscription_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `billing_cycle` enum('monthly','yearly') NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `max_users` int(11) NOT NULL DEFAULT 5,
  `max_patients` int(11) NOT NULL DEFAULT 100,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_plans_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_plans`
--

LOCK TABLES `subscription_plans` WRITE;
/*!40000 ALTER TABLE `subscription_plans` DISABLE KEYS */;
INSERT INTO `subscription_plans` VALUES (1,'Basic','basic','Perfect for small clinics',2500.00,'monthly','[\"Patient Management System\",\"Appointment Scheduling\",\"Billing & Invoicing\",\"Treatment Plans\",\"Staff Management\",\"Reports & Analytics\"]',5,100,1,'2025-11-17 02:22:15','2025-11-17 02:22:15'),(2,'Professional','professional','Best for growing practices',5000.00,'monthly','[\"Patient Management System\",\"Appointment Scheduling\",\"Billing & Invoicing\",\"Treatment Plans\",\"Staff Management\",\"Reports & Analytics\"]',15,500,1,'2025-11-17 02:22:15','2025-11-17 02:22:15'),(3,'Enterprise','enterprise','For large dental chains',10000.00,'monthly','[\"Patient Management System\",\"Appointment Scheduling\",\"Billing & Invoicing\",\"Treatment Plans\",\"Staff Management\",\"Reports & Analytics\"]',-1,-1,1,'2025-11-17 02:22:15','2025-11-17 02:22:15');
/*!40000 ALTER TABLE `subscription_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `subscription_plan_id` bigint(20) unsigned NOT NULL,
  `status` enum('active','cancelled','expired','trial') NOT NULL DEFAULT 'trial',
  `starts_at` date NOT NULL,
  `ends_at` date NOT NULL,
  `trial_ends_at` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_clinic_id_foreign` (`clinic_id`),
  KEY `subscriptions_subscription_plan_id_foreign` (`subscription_plan_id`),
  CONSTRAINT `subscriptions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1,1,2,'active','2025-11-17','2025-12-17',NULL,5000.00,NULL,'2025-11-17 02:22:57','2025-11-17 02:22:57'),(2,2,1,'active','2025-11-17','2025-12-17',NULL,2500.00,NULL,'2025-11-17 02:22:58','2025-11-17 02:22:58');
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_plans`
--

DROP TABLE IF EXISTS `treatment_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treatment_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `estimated_duration` varchar(255) DEFAULT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `treatments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`treatments`)),
  `total_cost` decimal(10,2) DEFAULT NULL,
  `status` enum('draft','proposed','approved','in_progress','completed') NOT NULL DEFAULT 'draft',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_plans_clinic_id_foreign` (`clinic_id`),
  KEY `treatment_plans_patient_id_foreign` (`patient_id`),
  KEY `treatment_plans_dentist_id_foreign` (`dentist_id`),
  CONSTRAINT `treatment_plans_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatment_plans_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatment_plans_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_plans`
--

LOCK TABLES `treatment_plans` WRITE;
/*!40000 ALTER TABLE `treatment_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `treatment_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `clinic_id` bigint(20) unsigned DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_clinic_id_is_active_index` (`clinic_id`,`is_active`),
  KEY `users_email_is_active_index` (`email`,`is_active`),
  KEY `users_created_at_index` (`created_at`),
  CONSTRAINT `users_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@dentalcare.com','2025-11-17 04:06:29','$2y$12$ksfCBmnGLq/tzdQtkEIeDOfUkBSQJW1IwFotl0vkFc4wIRxo6uAMW',NULL,NULL,1,NULL,'2025-11-17 02:22:22','2025-11-17 04:06:29'),(2,'Dr. Rajesh Sharma','admin@smiledentalclinic.com','2025-11-17 04:06:29','$2y$12$8sCBf2vrTGtr3fAwb2aFrOqd3OkuuvhR97Xgo7hbtdOB8.Jl4xYcW',1,'+977-9841234567',1,NULL,'2025-11-17 02:22:35','2025-11-17 04:06:29'),(3,'Dr. Sita Poudel','dentist@smiledentalclinic.com','2025-11-17 04:06:29','$2y$12$TGylNQ0BTCYOCASiOcKM/usRAG4LauWMfFSxu3ywOcAC0BLTCXEqi',1,'+977-9841234568',1,NULL,'2025-11-17 02:22:35','2025-11-17 04:06:29'),(4,'Maya Gurung','reception@smiledentalclinic.com','2025-11-17 04:06:29','$2y$12$y/bidAJEB7rEWOCIaS55Y.v/2BtPIxq8Tixi4GNv5NSoZKhKJJJZG',1,'+977-9841234569',1,NULL,'2025-11-17 02:22:35','2025-11-17 04:06:29'),(5,'Ram Bahadur Thapa','accounts@smiledentalclinic.com','2025-11-17 04:06:29','$2y$12$L98B1sHUFT83fkTEyUl1euMd/IiUXHf90fWZ66LdOkgI/C1muq/li',1,'+977-9841234570',1,NULL,'2025-11-17 02:22:35','2025-11-17 04:06:29'),(6,'Dr. Anita Shrestha','admin@perfectteeth.com','2025-11-17 04:06:29','$2y$12$l/Nf5VTG.imuDLZLfABYEObsqUGsPIhRaaz72HDgtEuNifeFjSx5O',2,'+977-9851234567',1,NULL,'2025-11-17 02:22:47','2025-11-17 04:06:29'),(7,'Dr. Bikash Adhikari','dentist@perfectteeth.com','2025-11-17 04:06:29','$2y$12$smrB2pxOkWREo0aJjW35FeKZTXQZT/l6hhW8EHOwnWacwiQwjR.Cm',2,'+977-9851234568',1,NULL,'2025-11-17 02:22:47','2025-11-17 04:06:29'),(8,'Sunita Rai','reception@perfectteeth.com','2025-11-17 04:06:29','$2y$12$qRORDF/UHlY9Z0eiPh1u5u6vm5G662ECdTamhV52mClRL6G2LK9FO',2,'+977-9851234569',1,NULL,'2025-11-17 02:22:48','2025-11-17 04:06:29'),(9,'Krishna Tamang','accounts@perfectteeth.com','2025-11-17 04:06:29','$2y$12$x4Pqjh7AMmMyFIX3bRF8f.AwPV7q2VNV6RqVE03VoCh44G1XWY.RC',2,'+977-9851234570',1,NULL,'2025-11-17 02:22:48','2025-11-17 04:06:29');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `waitlists`
--

DROP TABLE IF EXISTS `waitlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `waitlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time` time DEFAULT NULL,
  `appointment_type` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('waiting','contacted','scheduled','cancelled') NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `waitlists_clinic_id_foreign` (`clinic_id`),
  KEY `waitlists_patient_id_foreign` (`patient_id`),
  KEY `waitlists_dentist_id_foreign` (`dentist_id`),
  CONSTRAINT `waitlists_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `waitlists_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `waitlists_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `waitlists`
--

LOCK TABLES `waitlists` WRITE;
/*!40000 ALTER TABLE `waitlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `waitlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-17 16:35:34
