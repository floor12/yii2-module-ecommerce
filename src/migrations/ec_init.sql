-- MySQL dump 10.13  Distrib 8.0.17, for osx10.14 (x86_64)
--
-- Host: 127.0.0.1    Database: database
-- ------------------------------------------------------
-- Server version	5.7.28

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
-- Table structure for table `ec_category`
--

DROP TABLE IF EXISTS `ec_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Category title',
  `parent_id` int(11) DEFAULT NULL COMMENT 'Parent category',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Category status',
  `external_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'External id',
  `fulltitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Title with full path',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT 'Sort postion',
  PRIMARY KEY (`id`),
  KEY `idx-ec_category-status` (`status`),
  KEY `idx-ec_category-external_id` (`external_id`),
  KEY `ec_category-sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_category`
--

LOCK TABLES `ec_category` WRITE;
/*!40000 ALTER TABLE `ec_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_city`
--

DROP TABLE IF EXISTS `ec_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'City name',
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'City name with region',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_city`
--

LOCK TABLES `ec_city` WRITE;
/*!40000 ALTER TABLE `ec_city` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_discount_group`
--

DROP TABLE IF EXISTS `ec_discount_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_discount_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` int(11) NOT NULL COMMENT 'Created at',
  `created_by` int(11) NOT NULL COMMENT 'Created by',
  `updated_at` int(11) NOT NULL COMMENT 'Updated at',
  `updated_by` int(11) NOT NULL COMMENT 'Updated by',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Group title',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Discount description',
  `status` int(11) NOT NULL COMMENT 'Status',
  `discount_price_id` int(11) DEFAULT NULL COMMENT 'Discount item price id',
  `discount_percent` int(11) DEFAULT NULL COMMENT 'Discount in percents',
  `item_quantity` int(11) DEFAULT NULL COMMENT 'Quantity of items of this group',
  PRIMARY KEY (`id`),
  KEY `idx-ec_discount_group-status` (`status`),
  KEY `idx-ec_discount_group-item_quantity` (`item_quantity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_discount_group`
--

LOCK TABLES `ec_discount_group` WRITE;
/*!40000 ALTER TABLE `ec_discount_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_discount_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_discount_group_product`
--

DROP TABLE IF EXISTS `ec_discount_group_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_discount_group_product` (
  `discount_group_id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL,
  KEY `fk-ec_discount_group_item` (`product_variant_id`),
  KEY `ec_discount_group_product-index` (`discount_group_id`,`product_variant_id`),
  CONSTRAINT `fk-ec_discount_group-group` FOREIGN KEY (`discount_group_id`) REFERENCES `ec_discount_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk-ec_discount_group-product` FOREIGN KEY (`product_variant_id`) REFERENCES `ec_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_discount_group_product`
--

LOCK TABLES `ec_discount_group_product` WRITE;
/*!40000 ALTER TABLE `ec_discount_group_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_discount_group_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order`
--

DROP TABLE IF EXISTS `ec_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'Buyer indificator',
  `created` int(11) NOT NULL COMMENT 'Created',
  `updated` int(11) NOT NULL COMMENT 'Updated',
  `delivered` int(11) DEFAULT NULL COMMENT 'Delivered',
  `total` float NOT NULL DEFAULT '0' COMMENT 'Total cost',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Order status',
  `delivery_status` int(11) NOT NULL DEFAULT '0' COMMENT 'Delivery status',
  `external_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Extermnl indificator',
  `delivery_type_id` int(11) NOT NULL COMMENT 'Delivery type',
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Fullname',
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Phone',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Email',
  `address` text COLLATE utf8_unicode_ci COMMENT 'Address',
  `comment` text COLLATE utf8_unicode_ci COMMENT 'Client comment',
  `comment_admin` text COLLATE utf8_unicode_ci COMMENT 'Admin comment',
  `city_id` int(11) DEFAULT NULL COMMENT 'City ID for delivery service',
  `delivery_cost` float NOT NULL DEFAULT '0' COMMENT 'Delivery cost',
  `products_cost` float NOT NULL DEFAULT '0' COMMENT 'All items cost',
  `products_weight` float NOT NULL DEFAULT '0' COMMENT 'All items weight',
  `payment_type_id` int(11) DEFAULT '0' COMMENT 'Payment type',
  PRIMARY KEY (`id`),
  KEY `idx-ec_order-status` (`status`),
  KEY `idx-ec_order-user_id` (`user_id`),
  KEY `idx-ec_order-delivery_status` (`delivery_status`),
  KEY `idx-ec_order-created` (`created`),
  KEY `idx-ec_order-updated` (`updated`),
  KEY `idx-ec_order-external_id` (`external_id`),
  KEY `idx-ec_order-type` (`payment_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order`
--

LOCK TABLES `ec_order` WRITE;
/*!40000 ALTER TABLE `ec_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_item`
--

DROP TABLE IF EXISTS `ec_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'Buyer indificator',
  `product_variation_id` int(11) NOT NULL COMMENT 'Item identificator',
  `created` int(11) NOT NULL COMMENT 'Created',
  `order_id` int(11) NOT NULL COMMENT 'Order identificator',
  `price` double NOT NULL COMMENT 'Item price',
  `order_status` int(11) NOT NULL DEFAULT '0' COMMENT 'Order status',
  `quantity` int(11) NOT NULL COMMENT 'Quantity of product',
  `sum` double DEFAULT '0' COMMENT 'Total sum',
  PRIMARY KEY (`id`),
  KEY `idx-ec_order_item-created` (`created`),
  KEY `idx-ec_order_item-user_id` (`user_id`),
  KEY `idx-ec_order_item-item_id` (`product_variation_id`),
  KEY `idx-ec_order_item-order_id` (`order_id`),
  KEY `idx-ec_order_item-order_status` (`order_status`),
  CONSTRAINT `ec_order_item_ec_product_variation_id_fk` FOREIGN KEY (`product_variation_id`) REFERENCES `ec_product_variation` (`id`),
  CONSTRAINT `fk-ec_order_item-order` FOREIGN KEY (`order_id`) REFERENCES `ec_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_item`
--

LOCK TABLES `ec_order_item` WRITE;
/*!40000 ALTER TABLE `ec_order_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_parameter`
--

DROP TABLE IF EXISTS `ec_parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Parameter title',
  `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Unit of measure',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Parameter type',
  `external_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Extermnl id',
  `hide` int(11) NOT NULL DEFAULT '0' COMMENT 'Hide on site',
  PRIMARY KEY (`id`),
  KEY `idx-ec_item_param-type_id` (`type_id`),
  KEY `idx-ec_item_param-external_id` (`external_id`),
  KEY `idx-ec_item_param-hide_id` (`hide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_parameter`
--

LOCK TABLES `ec_parameter` WRITE;
/*!40000 ALTER TABLE `ec_parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_parameter_category`
--

DROP TABLE IF EXISTS `ec_parameter_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_parameter_category` (
  `parameter_id` int(11) NOT NULL COMMENT 'Param link',
  `category_id` int(11) NOT NULL COMMENT 'Category link',
  KEY `idx-ec_param_category-status` (`parameter_id`,`category_id`),
  KEY `fk-ec_param_category-category` (`category_id`),
  CONSTRAINT `fk-ec_param_category-category` FOREIGN KEY (`category_id`) REFERENCES `ec_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk-ec_param_category-param` FOREIGN KEY (`parameter_id`) REFERENCES `ec_parameter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_parameter_category`
--

LOCK TABLES `ec_parameter_category` WRITE;
/*!40000 ALTER TABLE `ec_parameter_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_parameter_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_parameter_value`
--

DROP TABLE IF EXISTS `ec_parameter_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_parameter_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Parameter value',
  `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Parameter unit of measure',
  `parameter_id` int(11) NOT NULL COMMENT 'Parameter id',
  `sort` int(11) DEFAULT '0' COMMENT 'Sort position',
  PRIMARY KEY (`id`),
  KEY `ec_parameter_value_param_id_index` (`parameter_id`),
  CONSTRAINT `ec_parameter_value_ec_parameter_id_fk` FOREIGN KEY (`parameter_id`) REFERENCES `ec_parameter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_parameter_value`
--

LOCK TABLES `ec_parameter_value` WRITE;
/*!40000 ALTER TABLE `ec_parameter_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_parameter_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_parameter_value_product_variation`
--

DROP TABLE IF EXISTS `ec_parameter_value_product_variation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_parameter_value_product_variation` (
  `parameter_value_id` int(11) NOT NULL,
  `product_variation_id` int(11) NOT NULL,
  PRIMARY KEY (`parameter_value_id`,`product_variation_id`),
  KEY `ec_parameter_value_product_variation_ec_product_variation_id_fk` (`product_variation_id`),
  CONSTRAINT `ec_parameter_value_product_variation_ec_parameter_value_id_fk` FOREIGN KEY (`parameter_value_id`) REFERENCES `ec_parameter_value` (`id`),
  CONSTRAINT `ec_parameter_value_product_variation_ec_product_variation_id_fk` FOREIGN KEY (`product_variation_id`) REFERENCES `ec_product_variation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_parameter_value_product_variation`
--

LOCK TABLES `ec_parameter_value_product_variation` WRITE;
/*!40000 ALTER TABLE `ec_parameter_value_product_variation` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_parameter_value_product_variation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_payment`
--

DROP TABLE IF EXISTS `ec_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL COMMENT 'Creation timestamp',
  `updated` int(11) NOT NULL COMMENT 'Update timestamp',
  `payed` int(11) DEFAULT NULL COMMENT 'Payed timestamp',
  `order_id` int(11) NOT NULL COMMENT 'Order id',
  `status` int(11) NOT NULL COMMENT 'Payment status',
  `type` int(11) NOT NULL COMMENT 'Payment type',
  `external_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sum` float NOT NULL COMMENT 'Sum',
  `comment` text COLLATE utf8_unicode_ci COMMENT 'Payment comment',
  `form_url` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Payment form address',
  `external_status` int(1) DEFAULT NULL COMMENT 'Payment status in external service',
  PRIMARY KEY (`id`),
  KEY `idx-ec_payment-order_id` (`order_id`),
  KEY `idx-ec_payment-status` (`status`),
  KEY `idx-ec_payment-type` (`type`),
  CONSTRAINT `fk-ec_payment-order` FOREIGN KEY (`order_id`) REFERENCES `ec_order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_payment`
--

LOCK TABLES `ec_payment` WRITE;
/*!40000 ALTER TABLE `ec_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product`
--

DROP TABLE IF EXISTS `ec_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Item title',
  `subtitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Item subtitle',
  `description` text COLLATE utf8_unicode_ci COMMENT 'Item description',
  `seo_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Description META',
  `seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Page title',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Item status',
  `external_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Extermnl indificator',
  `article` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Item article',
  `weight_delivery` float NOT NULL DEFAULT '0' COMMENT 'Weight for delivery',
  PRIMARY KEY (`id`),
  KEY `idx-ec_item-status` (`status`),
  KEY `idx-ec_item-external_id` (`external_id`),
  KEY `idx-ec_item-article` (`article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product`
--

LOCK TABLES `ec_product` WRITE;
/*!40000 ALTER TABLE `ec_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_category`
--

DROP TABLE IF EXISTS `ec_product_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_category` (
  `product_id` int(11) NOT NULL COMMENT 'Item link',
  `category_id` int(11) NOT NULL COMMENT 'Category link',
  KEY `idx-ec_product_category-status` (`product_id`,`category_id`),
  KEY `fk-ec_product_category-category` (`category_id`),
  CONSTRAINT `ec_product_category_ec_category_id_fk` FOREIGN KEY (`category_id`) REFERENCES `ec_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ec_product_category_ec_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `ec_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_category`
--

LOCK TABLES `ec_product_category` WRITE;
/*!40000 ALTER TABLE `ec_product_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_variation`
--

DROP TABLE IF EXISTS `ec_product_variation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_variation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT 'Link to product',
  `external_id` varchar (255) NULL COMMENT 'External ID',
  `price_0` double DEFAULT NULL COMMENT 'First price',
  `price_1` double DEFAULT NULL COMMENT 'Second price',
  `price_2` double DEFAULT NULL COMMENT 'Third price',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_product_id` (`product_id`),
  CONSTRAINT `ec_product_variation_ec_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `ec_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_variation`
--

LOCK TABLES `ec_product_variation` WRITE;
/*!40000 ALTER TABLE `ec_product_variation` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_variation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_stock`
--

DROP TABLE IF EXISTS `ec_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'Stock title',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Stock status',
  `description` text COMMENT 'Stock description',
  `external_id` varchar(255) DEFAULT NULL COMMENT 'External ID',
  PRIMARY KEY (`id`),
  KEY `ec_storehouse_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_stock`
--

LOCK TABLES `ec_stock` WRITE;
/*!40000 ALTER TABLE `ec_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_stock_balance`
--

DROP TABLE IF EXISTS `ec_stock_balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_stock_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_variation_id` int(11) NOT NULL COMMENT 'Product variation',
  `stock_id` int(11) NOT NULL COMMENT 'Stock',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT 'Stock balance',
  PRIMARY KEY (`id`),
  KEY `ec_stock_balance_product_variation_id_index` (`product_variation_id`),
  KEY `ec_stock_balance_stock_id_index` (`stock_id`),
  CONSTRAINT `ec_stock_balance_ec_product_variation_id_fk` FOREIGN KEY (`product_variation_id`) REFERENCES `ec_product_variation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ec_stock_balance_ec_stock_id_fk` FOREIGN KEY (`stock_id`) REFERENCES `ec_stock` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_stock_balance`
--

LOCK TABLES `ec_stock_balance` WRITE;
/*!40000 ALTER TABLE `ec_stock_balance` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_stock_balance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1573840735);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-11-15 19:04:34
