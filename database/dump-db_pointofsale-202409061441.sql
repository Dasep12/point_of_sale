-- MariaDB dump 10.19  Distrib 10.6.7-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_pointofsale
-- ------------------------------------------------------
-- Server version	10.6.7-MariaDB

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
-- Table structure for table `tbl_mst_categories`
--

DROP TABLE IF EXISTS `tbl_mst_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mst_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_categories` varchar(100) DEFAULT NULL,
  `code_categories` varchar(10) DEFAULT NULL,
  `status_categories` int(1) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mst_categories`
--

LOCK TABLES `tbl_mst_categories` WRITE;
/*!40000 ALTER TABLE `tbl_mst_categories` DISABLE KEYS */;
INSERT INTO `tbl_mst_categories` VALUES (1,'MAKANAN','MKN',1,'-','2024-08-27 13:00:00','1','2024-09-04 11:14:22','13'),(2,'MINUMAN','MNM',1,'-','2024-08-27 13:00:00','1','2024-09-04 11:14:17','13');
/*!40000 ALTER TABLE `tbl_mst_harga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_mst_level_member`
--

DROP TABLE IF EXISTS `tbl_mst_level_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mst_level_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_level` varchar(255) DEFAULT NULL,
  `status_level` int(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mst_level_member`
--

LOCK TABLES `tbl_mst_level_member` WRITE;
/*!40000 ALTER TABLE `tbl_mst_level_member` DISABLE KEYS */;
INSERT INTO `tbl_mst_level_member` VALUES (1,'MEMBER-1',1,'2024-08-30 13:00:00','1',NULL,NULL),(2,'MEMBER-2',1,'2024-08-30 13:00:00','1',NULL,NULL),(3,'GROSIR',1,'2024-08-30 13:00:00','1','2024-08-30 09:26:38','13'),(5,'UMUM',1,'2024-08-30 09:26:44','13','2024-08-30 09:26:44','13');
/*!40000 ALTER TABLE `tbl_mst_level_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_mst_material`
--

DROP TABLE IF EXISTS `tbl_mst_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mst_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_item` varchar(100) DEFAULT NULL,
  `barcode` varchar(100) NOT NULL,
  `name_item` varchar(100) DEFAULT NULL,
  `categori_id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `merek` varchar(255) DEFAULT NULL,
  `satuan_dasar` varchar(100) DEFAULT NULL,
  `konversi_satuan` double DEFAULT NULL,
  `harga_pokok` double DEFAULT NULL,
  `harga_jual_member_1` double DEFAULT NULL,
  `harga_jual_member_2` double DEFAULT NULL,
  `harga_umum` double DEFAULT NULL,
  `stock` double DEFAULT NULL,
  `stock_minimum` double DEFAULT NULL,
  `tipe_item` varchar(100) DEFAULT NULL,
  `serial` varchar(100) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `status_item` int(1) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_mst_material_unique_1` (`barcode`),
  UNIQUE KEY `tbl_mst_material_unique` (`kode_item`),
  KEY `tbl_mst_material_tbl_mst_categories_FK` (`categori_id`),
  KEY `tbl_mst_material_tbl_mst_units_FK` (`unit_id`),
  KEY `tbl_mst_material_tbl_mst_rak_FK` (`location_id`),
  KEY `tbl_mst_material_tbl_mst_warehouse_FK` (`warehouse_id`),
  CONSTRAINT `tbl_mst_material_tbl_mst_categories_FK` FOREIGN KEY (`categori_id`) REFERENCES `tbl_mst_categories` (`id`),
  CONSTRAINT `tbl_mst_material_tbl_mst_rak_FK` FOREIGN KEY (`location_id`) REFERENCES `tbl_mst_rak` (`id`),
  CONSTRAINT `tbl_mst_material_tbl_mst_units_FK` FOREIGN KEY (`unit_id`) REFERENCES `tbl_mst_units` (`id`),
  CONSTRAINT `tbl_mst_material_tbl_mst_warehouse_FK` FOREIGN KEY (`warehouse_id`) REFERENCES `tbl_mst_warehouse` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mst_material`
--

LOCK TABLES `tbl_mst_material` WRITE;
/*!40000 ALTER TABLE `tbl_mst_material` DISABLE KEYS */;
INSERT INTO `tbl_mst_material` VALUES (1,'AL091','BB','Mie Rasa Pedas',1,1,'Indofood1','D',1,10000,1500,NULL,NULL,10,5,'1','N',6,25,1,'TESTER','2024-08-27 13:00:00','1','13','2024-08-31 17:06:44'),(12,'AL092','AA','Ale-Ale',2,1,'Ale Ale','1',1,1000,NULL,NULL,NULL,NULL,10,'1','N',6,25,1,'tester','2024-08-31 16:22:21','13','13','2024-08-31 17:06:40');
/*!40000 ALTER TABLE `tbl_mst_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_mst_pajak`
--

DROP TABLE IF EXISTS `tbl_mst_pajak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mst_pajak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code_pajak` varchar(100) DEFAULT NULL,
  `persentase` double DEFAULT NULL,
  `status_pajak` int(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mst_pajak`
--

LOCK TABLES `tbl_mst_pajak` WRITE;
/*!40000 ALTER TABLE `tbl_mst_pajak` DISABLE KEYS */;
INSERT INTO `tbl_mst_pajak` VALUES (1,'PPN 21','PPN 21',11,1,'2024-08-19 13:00:00','1','2024-09-03 12:51:52','13');
/*!40000 ALTER TABLE `tbl_mst_pajak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_mst_rak`
--

DROP TABLE IF EXISTS `tbl_mst_rak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mst_rak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(100) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `status_location` int(1) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_mst_locationwarehouse_tbl_mst_warehouse_FK` (`warehouse_id`),
  CONSTRAINT `tbl_mst_locationwarehouse_tbl_mst_warehouse_FK` FOREIGN KEY (`warehouse_id`) REFERENCES `tbl_mst_warehouse` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mst_rak`
--

LOCK TABLES `tbl_mst_rak` WRITE;
/*!40000 ALTER TABLE `tbl_mst_rak` DISABLE KEYS */;
INSERT INTO `tbl_mst_rak` VALUES (5,'Y01',25,1,NULL,'2024-08-27 13:00:00','1','2024-08-27 13:00:00',NULL),(6,'Y09',25,1,NULL,'2024-08-27 13:00:00','1','2024-08-27 13:00:00',NULL),(7,'C1',25,1,NULL,'2024-08-27 13:00:00','1','2024-08-27 13:00:00',NULL);
/*!40000 ALTER TABLE `tbl_mst_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_mst_units`
--

DROP TABLE IF EXISTS `tbl_mst_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mst_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(100) DEFAULT NULL,
  `unit_code` varchar(100) DEFAULT NULL,
  `status_unit` int(1) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mst_units`
--

LOCK TABLES `tbl_mst_units` WRITE;
/*!40000 ALTER TABLE `tbl_mst_units` DISABLE KEYS */;
INSERT INTO `tbl_mst_units` VALUES (1,'PIECES','PCS',1,'tester','2024-08-29 12:00:00',NULL,'2024-08-29 14:07:27','13'),(2,'DUS','DUS',1,'tes','2024-08-29 12:00:00',NULL,'2024-08-29 14:07:23','13'),(3,'BOX','BOX',1,'TES','2024-08-29 12:00:00',NULL,'2024-08-29 14:07:20','13');
/*!40000 ALTER TABLE `tbl_mst_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_mst_users`
--

DROP TABLE IF EXISTS `tbl_mst_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_mst_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `lock_user` int(1) DEFAULT 0,
  `status_user` int(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_mst_users_tbl_mst_role_FK` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_mst_users`
--

LOCK TABLES `tbl_mst_users` WRITE;
/*!40000 ALTER TABLE `tbl_mst_users` DISABLE KEYS */;
INSERT INTO `tbl_mst_users` VALUES (13,'dev','dev','$2y$10$UL5rkiGSP/OYybe8zOOkxeLXH7r1NvPEJsVHL3NJHWiN1oBfVEPju',14,'d@mail.com','083821619460',0,1,'2024-07-12 13:00:30',NULL,NULL,NULL),(14,'admin','administrator','123',19,'admin@mail.com','123',NULL,1,'2024-09-03 19:40:31','13','2024-09-03 19:40:59','13'),(15,'kasir','KASIR','$2y$10$ugO10tTHqHjxhK5arP4M5OCOLkui3KOaGOrNHh/ll5479TmX3a6Wy',20,'kasir@mail.com','6256665',NULL,1,'2024-09-04 13:43:48','13','2024-09-04 13:48:05','13');
/*!40000 ALTER TABLE `tbl_mst_warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_sys_accesmenu`
--

DROP TABLE IF EXISTS `tbl_sys_accesmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sys_accesmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accessmenu_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `add` int(1) DEFAULT NULL,
  `edit` int(1) DEFAULT NULL,
  `delete` int(1) DEFAULT NULL,
  `showAll` int(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_sys_accesmenu_unique` (`accessmenu_id`,`user_id`),
  KEY `tbl_sys_accesmenu_tbl_mst_users_FK` (`user_id`),
  CONSTRAINT `tbl_sys_accesmenu_tbl_mst_users_FK` FOREIGN KEY (`user_id`) REFERENCES `tbl_mst_users` (`id`),
  CONSTRAINT `tbl_sys_accesmenu_tbl_sys_roleaccessmenu_FK` FOREIGN KEY (`accessmenu_id`) REFERENCES `tbl_sys_roleaccessmenu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=884 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_sys_accesmenu`
--

LOCK TABLES `tbl_sys_accesmenu` WRITE;
/*!40000 ALTER TABLE `tbl_sys_accesmenu` DISABLE KEYS */;
INSERT INTO `tbl_sys_accesmenu` VALUES (700,185,13,1,1,1,1,NULL,'1'),(701,186,13,1,1,1,1,NULL,'1'),(702,187,13,1,1,1,1,NULL,'1'),(703,188,13,1,1,1,1,NULL,'1'),(704,189,13,1,1,1,1,NULL,'1'),(705,190,13,1,1,1,1,NULL,'1'),(706,191,13,1,1,1,1,NULL,'1'),(707,192,13,1,1,1,1,NULL,'1'),(708,193,13,1,1,1,1,NULL,'1'),(711,195,13,1,1,1,1,NULL,'1'),(712,196,13,1,1,1,1,NULL,'1'),(713,197,13,1,1,1,1,NULL,'1'),(714,198,13,1,1,1,1,NULL,'1'),(716,199,13,1,1,1,1,NULL,'1'),(717,201,13,1,1,1,1,NULL,'1'),(718,202,13,1,1,1,1,NULL,'1'),(719,203,13,1,1,1,1,NULL,'1'),(720,204,13,1,1,1,1,NULL,'1'),(721,205,13,1,1,1,1,NULL,'1'),(722,206,13,1,1,1,1,NULL,'1'),(783,207,14,0,1,1,NULL,'2024-09-03 19:40:59','1'),(784,208,14,1,0,1,NULL,'2024-09-03 19:40:59','1'),(785,209,14,0,1,1,NULL,'2024-09-03 19:40:59','1'),(786,210,14,1,0,1,NULL,'2024-09-03 19:40:59','1'),(787,211,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(788,212,14,0,1,1,NULL,'2024-09-03 19:40:59','1'),(789,213,14,1,0,1,NULL,'2024-09-03 19:40:59','1'),(790,214,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(791,215,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(792,216,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(793,217,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(794,218,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(795,219,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(796,220,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(797,221,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(798,222,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(799,223,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(800,224,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(801,225,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(802,226,14,1,1,1,NULL,'2024-09-03 19:40:59','1'),(863,227,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(864,228,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(865,229,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(866,230,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(867,231,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(868,232,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(869,233,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(870,234,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(871,235,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(872,236,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(873,237,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(874,238,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(875,239,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(876,240,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(877,241,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(878,242,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(879,243,15,1,1,1,NULL,'2024-09-04 13:48:05','1'),(880,244,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(881,245,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(882,246,15,0,0,0,NULL,'2024-09-04 13:48:05','1'),(883,247,13,0,0,0,NULL,'2024-09-04 13:48:05','1');
/*!40000 ALTER TABLE `tbl_sys_accesmenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_sys_menu`
--

DROP TABLE IF EXISTS `tbl_sys_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sys_menu` (
  `Menu_id` varchar(100) NOT NULL,
  `MenuLevel` varchar(100) DEFAULT NULL,
  `MenuUrut` varchar(100) DEFAULT NULL,
  `LevelNumber` varchar(100) DEFAULT NULL,
  `ParentMenu` varchar(100) DEFAULT NULL,
  `MenuName` varchar(100) DEFAULT NULL,
  `MenuIcon` varchar(100) DEFAULT NULL,
  `MenuUrl` varchar(100) DEFAULT NULL,
  `StatusMenu` int(1) DEFAULT 0,
  PRIMARY KEY (`Menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_sys_menu`
--

LOCK TABLES `tbl_sys_menu` WRITE;
/*!40000 ALTER TABLE `tbl_sys_menu` DISABLE KEYS */;
INSERT INTO `tbl_sys_menu` VALUES ('MN-0001','0','MN-1','0','*','Dashboard','fa fa-dashboard','administrator/dashboard',1),('MN-0002','1','MN-2','1','*','Master','fa fa-cube','#',1),('MN-0002A','2','MN-3','2','MN-0002','Gudang','#','administrator/warehouse',1),('MN-0002B','2','MN-4','2','MN-0002','Rak/Location','#','administrator/location',1),('MN-0002C','2','MN-5','2','MN-0002','Kategori','#','administrator/category',1),('MN-0002D','2','MN-6','2','MN-0002','Unit','#','administrator/units',1),('MN-0002E','2','MN-7','2','MN-0002','Item','#','administrator/material',1),('MN-0002F','2','MN-8','2','MN-0002','Level Member','#','administrator/levelmember',1),('MN-0002G','2','MN-9','2','MN-0002','Member','#','administrator/member',0),('MN-0002H','2','MN-10','2','MN-0002','Pajak','#','administrator/pajak',1),('MN-0003','1','MN-11','1','*','Transaksi','fa fa-users','#',1),('MN-0003A','2','MN-12','2','MN-0003','Input Penjualan','#','administrator/penjualan',1),('MN-0003B','2','MN-13','2','MN-0003','Input Pembelian','#','administrator/pembelian',1),('MN-0003C','2','MN-14','2','MN-0003','Adjust','#','administrator/adjust',1),('MN-0004','1','MN-15','1','*','Laporan','fa fa-file-pdf-o','#',1),('MN-0004A','2','MN-16','2','MN-0004','Penjualan','#','administrator/reportOut',1),('MN-0004B','2','MN-7','2','MN-0004','Pembelian','#','administrator/reportIn',1),('MN-0004C','2','MN-18','2','MN-0004','Stock Item','#','administrator/stock',1),('MN-0008','1','MN-24','1','*','Tools','fa fa-cog','#',1),('MN-0008A','2','MN-25','2','MN-0008','Role Setting','#','administrator/roles',1),('MN-0008B','2','MN-26','2','MN-0008','User Setting','#','administrator/users',1);
/*!40000 ALTER TABLE `tbl_sys_roleaccessmenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_trn_detail_beli`
--

DROP TABLE IF EXISTS `tbl_trn_detail_beli`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_trn_detail_beli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `header_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `unit_name` varchar(100) DEFAULT NULL,
  `kode_item` varchar(100) DEFAULT NULL,
  `in_stock` double NOT NULL,
  `hpp` double DEFAULT NULL,
  `merek` varchar(255) DEFAULT NULL,
  `total_harga` double NOT NULL DEFAULT 0,
  `supplier_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_trn_instock_tbl_mst_material_FK` (`item_id`),
  KEY `tbl_trn_detail_instock_tbl_mst_header_trans_FK` (`header_id`),
  CONSTRAINT `tbl_trn_detail_instock_tbl_mst_header_trans_FK` FOREIGN KEY (`header_id`) REFERENCES `tbl_trn_header_trans` (`id`),
  CONSTRAINT `tbl_trn_instock_tbl_mst_material_FK` FOREIGN KEY (`item_id`) REFERENCES `tbl_mst_material` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_trn_detail_beli`
--

LOCK TABLES `tbl_trn_detail_beli` WRITE;
/*!40000 ALTER TABLE `tbl_trn_detail_beli` DISABLE KEYS */;
INSERT INTO `tbl_trn_detail_beli` VALUES (11,'2024-09-06 13:42:44',40,12,'Ale-Ale',1,'PCS','AL092',5,2500,'Ale Ale',12500,NULL,'2024-09-06 13:42:44','13',NULL,NULL);
/*!40000 ALTER TABLE `tbl_trn_detail_beli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_trn_detail_sales`
--

DROP TABLE IF EXISTS `tbl_trn_detail_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_trn_detail_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `header_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(255) DEFAULT NULL,
  `kode_item` varchar(100) DEFAULT NULL,
  `merek` varchar(255) DEFAULT NULL,
  `harga_jual` double NOT NULL DEFAULT 0,
  `out_stock` double NOT NULL DEFAULT 0,
  `discount` double DEFAULT 0,
  `created_at` datetime NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_trn_outstock_tbl_mst_material_FK` (`item_id`),
  KEY `tbl_trn_outstock_tbl_mst_units_FK` (`unit_id`),
  KEY `tbl_trn_detail_outstock_tbl_mst_header_trans_FK` (`header_id`),
  CONSTRAINT `tbl_trn_detail_outstock_tbl_mst_header_trans_FK` FOREIGN KEY (`header_id`) REFERENCES `tbl_trn_header_trans` (`id`),
  CONSTRAINT `tbl_trn_outstock_tbl_mst_material_FK` FOREIGN KEY (`item_id`) REFERENCES `tbl_mst_material` (`id`),
  CONSTRAINT `tbl_trn_outstock_tbl_mst_units_FK` FOREIGN KEY (`unit_id`) REFERENCES `tbl_mst_units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_trn_detail_sales`
--

LOCK TABLES `tbl_trn_detail_sales` WRITE;
/*!40000 ALTER TABLE `tbl_trn_detail_sales` DISABLE KEYS */;
INSERT INTO `tbl_trn_detail_sales` VALUES (69,'2024-09-06 00:00:00',41,1,'Mie Rasa Pedas',1,'PCS','AL091','Indofood1',2000,5,500,'2024-09-06 13:55:20','13',NULL,NULL);
/*!40000 ALTER TABLE `tbl_trn_detail_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_trn_header_trans`
--

DROP TABLE IF EXISTS `tbl_trn_header_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_trn_header_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_trans` datetime NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `type` enum('in','out') DEFAULT NULL,
  `types` enum('sales','beli','adjust') DEFAULT NULL,
  `no_transaksi` varchar(255) DEFAULT NULL,
  `status_bayar` enum('pending','lunas','cancel') DEFAULT NULL,
  `uang_bayar` double DEFAULT 0,
  `total_bayar` double DEFAULT 0,
  `sub_total` double DEFAULT 0,
  `total_potongan` double DEFAULT 0,
  `kembalian` double DEFAULT 0,
  `created_at` datetime NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_mst_header_trans_unique` (`no_transaksi`),
  KEY `tbl_mst_header_trans_tbl_mst_level_member_FK` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_trn_header_trans`
--

LOCK TABLES `tbl_trn_header_trans` WRITE;
/*!40000 ALTER TABLE `tbl_trn_header_trans` DISABLE KEYS */;
INSERT INTO `tbl_trn_header_trans` VALUES (40,'2024-09-06 01:42:44',NULL,'in','beli','TRNB.092406.1','lunas',0,12500,0,0,0,'2024-09-06 13:42:44','13',NULL,NULL),(41,'2024-09-06 01:55:20',1,'out','sales','TRNS.092406.1','lunas',10000,9500,10000,500,500,'2024-09-06 13:55:20','13',NULL,NULL);
 DROP VIEW IF EXISTS `vw_stock_item`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_stock_item` AS select `a`.`id` AS `id`,`a`.`name_item` AS `name_item`,`a`.`barcode` AS `barcode`,`a`.`kode_item` AS `kode_item`,`a`.`merek` AS `merek`,`b`.`unit_code` AS `unit_code`,`a`.`stock_minimum` AS `stock_minimum`,coalesce(`x`.`inStock`,0) AS `inStock`,coalesce(`y`.`outStock`,0) AS `outStock`,coalesce(`x`.`inStock`,0) - coalesce(`y`.`outStock`,0) AS `Stock`,coalesce(`x`.`date_in`,`y`.`date_out`) AS `updated_at` from (((`db_pointofsale`.`tbl_mst_material` `a` left join `db_pointofsale`.`tbl_mst_units` `b` on(`b`.`id` = `a`.`unit_id`)) left join (select coalesce(sum(`a`.`in_stock`),0) AS `inStock`,`a`.`item_id` AS `item_id`,max(`a`.`date`) AS `date_in` from `db_pointofsale`.`tbl_trn_detail_beli` `a` group by `a`.`item_id`) `x` on(`a`.`id` = `x`.`item_id`)) left join (select coalesce(sum(`a`.`out_stock`),0) AS `outStock`,`a`.`item_id` AS `item_id`,max(`a`.`date`) AS `date_out` from `db_pointofsale`.`tbl_trn_detail_sales` `a` group by `a`.`item_id`) `y` on(`a`.`id` = `y`.`item_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_sys_menu`
--

/*!50001 DROP TABLE IF EXISTS `vw_sys_menu`*/;
/*!50001 DROP VIEW IF EXISTS `vw_sys_menu`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_sys_menu` AS select `a`.`user_id` AS `user_id`,`b`.`enable_menu` AS `enable_menu`,`b`.`menu_id` AS `menu_id`,`b`.`role_id` AS `role_id`,`c`.`MenuName` AS `MenuName`,`c`.`MenuLevel` AS `MenuLevel`,`c`.`MenuIcon` AS `MenuIcon`,`c`.`LevelNumber` AS `LevelNumber`,`c`.`ParentMenu` AS `ParentMenu`,`c`.`MenuUrl` AS `MenuUrl`,`a`.`add` AS `add`,`a`.`edit` AS `edit`,`a`.`delete` AS `delete` from ((`tbl_sys_accesmenu` `a` join `tbl_sys_roleaccessmenu` `b` on(`b`.`id` = `a`.`accessmenu_id`)) join `tbl_sys_menu` `c` on(`c`.`Menu_id` = `b`.`menu_id`)) where `c`.`StatusMenu` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_topsales`
--

/*!50001 DROP TABLE IF EXISTS `vw_topsales`*/;
/*!50001 DROP VIEW IF EXISTS `vw_topsales`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_topsales` AS select `a`.`item_name` AS `item_name`,sum(`a`.`out_stock`) AS `qty`,sum(`a`.`harga_jual` * `a`.`out_stock` - `a`.`discount`) AS `total_out` from (`tbl_trn_detail_sales` `a` left join `tbl_trn_header_trans` `b` on(`a`.`header_id` = `b`.`id`)) group by `a`.`item_id`,`a`.`item_name` */;
/*!50001 SET characte/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-06 14:41:35
