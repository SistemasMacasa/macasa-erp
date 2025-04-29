/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.21-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: erp_ecommerce_db
-- ------------------------------------------------------
-- Server version	10.6.21-MariaDB-ubu2004

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
-- Table structure for table `actividades`
--

DROP TABLE IF EXISTS `actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_actividad` datetime DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `recordatorio` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_actividad`),
  KEY `fk_actividades_id_usuario` (`id_usuario`),
  KEY `fk_actividades_id_cliente` (`id_cliente`),
  CONSTRAINT `fk_actividades_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `fk_actividades_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividades`
--


--
-- Table structure for table `asistencias`
--

DROP TABLE IF EXISTS `asistencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_entrada` datetime DEFAULT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_asistencia`),
  KEY `fk_asistencias_id_usuario` (`id_usuario`),
  CONSTRAINT `fk_asistencias_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencias`
--

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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

--
-- Table structure for table `carrito_compras`
--

DROP TABLE IF EXISTS `carrito_compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrito_compras` (
  `id_carrito` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_producto_proveedor` varchar(100) DEFAULT NULL,
  `clave_api_proveedor` varchar(100) DEFAULT NULL,
  `num_parte_proveedor` varchar(100) DEFAULT NULL,
  `nombre_api` varchar(100) DEFAULT NULL,
  `marca_api` varchar(100) DEFAULT NULL,
  `categoria_api` varchar(100) DEFAULT NULL,
  `datos_extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_extra`)),
  PRIMARY KEY (`id_carrito`),
  KEY `fk_carrito_compras_id_usuario` (`id_usuario`),
  CONSTRAINT `fk_carrito_compras_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito_compras`
--


--
-- Table structure for table `ciudades`
--

DROP TABLE IF EXISTS `ciudades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciudades` (
  `id_ciudad` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_ciudad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciudades`
--

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `sector` varchar(100) DEFAULT NULL,
  `segmento` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  KEY `fk_clientes_id_vendedor` (`id_vendedor`),
  CONSTRAINT `fk_clientes_id_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (2,'cliente','de prueba','Activo','ECOMMERCE',1,'Empresa Privada', 'Macasa Cuentas Especiales'),(7,'cliente 3','de prueba','Activo','ERP',1,'Empresa Privada', 'Macasa Cuentas Especiales'),(8,'Diestra','de prueba','Activo','ERP',1,'Empresa Privada', 'Macasa Cuentas Especiales'),(9,'Saavi','Prueba','Activo','ECOMMERCE',1,'Empresa Privada', 'Macasa Cuentas Especiales'),(10,'Cliente 6','apellido','Activo','Ecommerce 1',1,'Empresa Privada', 'Macasa Cuentas Especiales');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

--
-- Table structure for table `compras`
--

DROP TABLE IF EXISTS `compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_comprador` int(11) DEFAULT NULL,
  `consecutivo_compra` varchar(100) DEFAULT NULL,
  `fecha_compra` datetime DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_compra`),
  KEY `fk_compras_id_proveedor` (`id_proveedor`),
  KEY `fk_compras_id_comprador` (`id_comprador`),
  CONSTRAINT `fk_compras_id_comprador` FOREIGN KEY (`id_comprador`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_compras_id_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras`
--


--
-- Table structure for table `compras_partidas`
--

DROP TABLE IF EXISTS `compras_partidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras_partidas` (
  `id_compras_partidas` int(11) NOT NULL AUTO_INCREMENT,
  `id_compra` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `cantidad_recibida` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_compras_partidas`),
  KEY `fk_compras_partidas_id_compra` (`id_compra`),
  CONSTRAINT `fk_compras_partidas_id_compra` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_partidas`
--


--
-- Table structure for table `contactos`
--

DROP TABLE IF EXISTS `contactos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contactos` (
  `id_contacto` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido_p` varchar(100) DEFAULT NULL,
  `apellido_m` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `puesto` varchar(100) DEFAULT NULL,
  `telefono1` varchar(100) DEFAULT NULL,
  `ext1` varchar(10) DEFAULT NULL,
  `telefono2` varchar(100) DEFAULT NULL,
  `ext2` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_contacto`),
  KEY `fk_contactos_id_cliente` (`id_cliente`),
  CONSTRAINT `fk_contactos_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactos`
--


--
-- Table structure for table `cotizaciones`
--

DROP TABLE IF EXISTS `cotizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizaciones` (
  `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `id_razon_social` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `vencimiento` datetime DEFAULT NULL,
  `id_direccion_entrega` int(11) DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL,
  `id_divisa` int(11) DEFAULT NULL,
  `num_consecutivo` varchar(100) DEFAULT NULL,
  `orden_de_venta` varchar(100) DEFAULT NULL,
  `score_final` decimal(10,2) DEFAULT NULL,
  `notas_entrega` text DEFAULT NULL,
  `notas_facturacion` text DEFAULT NULL,
  `id_termino_pago` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_cotizacion`),
  KEY `fk_cotizaciones_id_cliente` (`id_cliente`),
  KEY `fk_cotizaciones_id_razon_social` (`id_razon_social`),
  KEY `fk_cotizaciones_id_direccion_entrega` (`id_direccion_entrega`),
  KEY `fk_cotizaciones_id_vendedor` (`id_vendedor`),
  KEY `fk_cotizaciones_id_divisa` (`id_divisa`),
  CONSTRAINT `fk_cotizaciones_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `fk_cotizaciones_id_direccion_entrega` FOREIGN KEY (`id_direccion_entrega`) REFERENCES `direcciones` (`id_direccion`),
  CONSTRAINT `fk_cotizaciones_id_divisa` FOREIGN KEY (`id_divisa`) REFERENCES `divisas` (`id_divisa`),
  CONSTRAINT `fk_cotizaciones_id_razon_social` FOREIGN KEY (`id_razon_social`) REFERENCES `razones_sociales` (`id_razon_social`),
  CONSTRAINT `fk_cotizaciones_id_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizaciones`
--


--
-- Table structure for table `cotizaciones_partidas`
--

DROP TABLE IF EXISTS `cotizaciones_partidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizaciones_partidas` (
  `id_cotizacion_partida` int(11) NOT NULL AUTO_INCREMENT,
  `id_cotizacion` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `score` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_cotizacion_partida`),
  KEY `fk_cotizaciones_partidas_id_cotizacion` (`id_cotizacion`),
  KEY `fk_cotizaciones_partidas_id_proveedor` (`id_proveedor`),
  CONSTRAINT `fk_cotizaciones_partidas_id_cotizacion` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizaciones` (`id_cotizacion`),
  CONSTRAINT `fk_cotizaciones_partidas_id_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizaciones_partidas`
--
--
-- Table structure for table `direcciones`
--

DROP TABLE IF EXISTS `direcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `direcciones` (
  `id_direccion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `num_ext` varchar(20) DEFAULT NULL,
  `num_int` varchar(20) DEFAULT NULL,
  `colonia` varchar(100) DEFAULT NULL,
  `id_ciudad` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_direccion`),
  KEY `fk_direcciones_id_cliente` (`id_cliente`),
  KEY `fk_direcciones_id_ciudad` (`id_ciudad`),
  KEY `fk_direcciones_id_estado` (`id_estado`),
  KEY `fk_direcciones_id_pais` (`id_pais`),
  CONSTRAINT `fk_direcciones_id_ciudad` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`id_ciudad`),
  CONSTRAINT `fk_direcciones_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `fk_direcciones_id_estado` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`),
  CONSTRAINT `fk_direcciones_id_pais` FOREIGN KEY (`id_pais`) REFERENCES `paises` (`id_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direcciones`
--


--
-- Table structure for table `divisas`
--

DROP TABLE IF EXISTS `divisas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisas` (
  `id_divisa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `nomenclatura` varchar(10) DEFAULT NULL,
  `tipo_cambio` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_divisa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisas`
--


--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados`
--

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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

--
-- Table structure for table `forma_pagos`
--

DROP TABLE IF EXISTS `forma_pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `forma_pagos` (
  `id_forma_pago` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_forma_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forma_pagos`
--

/*!40000 ALTER TABLE `forma_pagos` DISABLE KEYS */;
INSERT INTO `forma_pagos` VALUES 
(1,'01','Efectivo'),
(2,'02','Cheque nominativo'),
(3,'03','Transferencia electrónica de fondos'),
(4,'04','Tarjeta de crédito'),
(5,'05','Monedero electrónico'),
(6,'06','Dinero electrónico'),
(7,'08','Vales de despensa'),
(8,'12','Dación en pago'),
(9,'13','Pago por subrogación'),
(10,'14','Pago por consignación'),
(11,'15','Condonación'),
(12,'17','Compensación'),
(13,'23','Novación'),
(14,'24','Confusión'),
(15,'25','Remisión de deuda'),
(16,'26','Prescripción o caducidad'),
(17,'27','A satisfacción del acreedor'),
(18,'28','Tarjeta de débito'),
(19,'29','Tarjeta de servicios'),
(20,'30','Aplicación de anticipos'),
(21,'99','Por definir');
/*!40000 ALTER TABLE `forma_pagos` ENABLE KEYS */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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


--
-- Table structure for table `metas_ventas`
--

DROP TABLE IF EXISTS `metas_ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `metas_ventas` (
  `id_meta_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `mes_aplicacion` datetime DEFAULT NULL,
  `cuota_facturacion` decimal(10,2) DEFAULT NULL,
  `cuota_marginal` decimal(10,2) DEFAULT NULL,
  `cuota_cotizaciones` decimal(10,2) DEFAULT NULL,
  `cuota_llamadas` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_meta_venta`),
  KEY `fk_metas_ventas_id_usuario` (`id_usuario`),
  CONSTRAINT `fk_metas_ventas_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metas_ventas`
--

--
-- Table structure for table `metodo_pagos`
--

DROP TABLE IF EXISTS `metodo_pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `metodo_pagos` (
  `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_metodo_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metodo_pagos`
--

/*!40000 ALTER TABLE `metodo_pagos` DISABLE KEYS */;
INSERT INTO `metodo_pagos` VALUES (1,'PUE','Pago en Una sola Exhibición'),(2,'PPD','Pago en Parcialidades o Diferido'),(3,'99','por definir');

/*!40000 ALTER TABLE `metodo_pagos` ENABLE KEYS */;

--
-- Table structure for table `uso_cfdis`
--

DROP TABLE IF EXISTS `uso_cfdis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uso_cfdis` (
  `id_uso_cfdi` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_uso_cfdi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uso_cfdis`
--

/*!40000 ALTER TABLE `uso_cfdis` DISABLE KEYS */;
INSERT INTO `uso_cfdis` VALUES 
(1, 'G01', 'Adquisición de mercancías'),
(2, 'G02', 'Devoluciones, descuentos o bonificaciones'),
(3, 'G03', 'Gastos en general'),
(4, 'I01', 'Construcciones'),
(5, 'I02', 'Mobiliario y equipo de oficina por inversiones'),
(6, 'I03', 'Equipo de transporte'),
(7, 'I04', 'Equipo de cómputo y accesorios'),
(8, 'I05', 'Dados, troqueles, moldes, matrices y herramental'),
(9, 'I06', 'Comunicaciones telefónicas'),
(10, 'I07', 'Comunicaciones satelitales'),
(11, 'I08', 'Otra maquinaria y equipo'),
(12, 'D01', 'Honorarios médicos, dentales y gastos hospitalarios'),
(13, 'D02', 'Gastos médicos por incapacidad o discapacidad'),
(14, 'D03', 'Gastos funerales'),
(15, 'D04', 'Donativos'),
(16, 'D05', 'Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)'),
(17, 'D06', 'Aportaciones voluntarias al SAR'),
(18, 'D07', 'Primas por seguros de gastos médicos'),
(19, 'D08', 'Gastos de transportación escolar obligatorio'),
(20, 'D09', 'Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones'),
(21, 'D10', 'Pagos por servicios educativos (colegiaturas)'),
(22, 'S01', 'Sin efectos fiscales'),
(23, 'CP01', 'Pagos'),
(24, 'CN01', 'Nómina');

/*!40000 ALTER TABLE `metodo_pagos` ENABLE KEYS */;


--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_origen` int(11) DEFAULT NULL,
  `id_usuario_destino` int(11) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT NULL,
  `fecha_leido` datetime DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `id_referencia` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `fk_notificaciones_id_usuario_origen` (`id_usuario_origen`),
  KEY `fk_notificaciones_id_usuario_destino` (`id_usuario_destino`),
  CONSTRAINT `fk_notificaciones_id_usuario_destino` FOREIGN KEY (`id_usuario_destino`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_notificaciones_id_usuario_origen` FOREIGN KEY (`id_usuario_origen`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--


--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `importe` decimal(10,2) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `id_metodo_pago` int(11) DEFAULT NULL,
  `id_forma_pago` int(11) DEFAULT NULL,
  `id_divisa` int(11) DEFAULT NULL,
  `tipo_cambio` decimal(10,2) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `es_anticipo` tinyint(1) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `fk_pagos_id_cliente` (`id_cliente`),
  KEY `fk_pagos_id_metodo_pago` (`id_metodo_pago`),
  KEY `fk_pagos_id_forma_pago` (`id_forma_pago`),
  KEY `fk_pagos_id_divisa` (`id_divisa`),
  CONSTRAINT `fk_pagos_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `fk_pagos_id_divisa` FOREIGN KEY (`id_divisa`) REFERENCES `divisas` (`id_divisa`),
  CONSTRAINT `fk_pagos_id_forma_pago` FOREIGN KEY (`id_forma_pago`) REFERENCES `forma_pagos` (`id_forma_pago`),
  CONSTRAINT `fk_pagos_id_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodo_pagos` (`id_metodo_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--


--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `paises` (
  `id_pais` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paises`
--


--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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


--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_cotizacion` int(11) DEFAULT NULL,
  `consecutivo_pedido` varchar(100) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `fecha_pdf` datetime DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_razon_social` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `id_direccion_entrega` int(11) DEFAULT NULL,
  `id_divisa` int(11) DEFAULT NULL,
  `orden_en_venta` varchar(100) DEFAULT NULL,
  `factura_pdf` varchar(100) DEFAULT NULL,
  `factura_xml` varchar(100) DEFAULT NULL,
  `score_final` decimal(10,2) DEFAULT NULL,
  `notas_entrega` text DEFAULT NULL,
  `notas_facturacion` text DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `fk_pedidos_id_cotizacion` (`id_cotizacion`),
  KEY `fk_pedidos_id_cliente` (`id_cliente`),
  KEY `fk_pedidos_id_razon_social` (`id_razon_social`),
  KEY `fk_pedidos_id_vendedor` (`id_vendedor`),
  KEY `fk_pedidos_id_direccion_entrega` (`id_direccion_entrega`),
  KEY `fk_pedidos_id_divisa` (`id_divisa`),
  CONSTRAINT `fk_pedidos_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `fk_pedidos_id_cotizacion` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizaciones` (`id_cotizacion`),
  CONSTRAINT `fk_pedidos_id_direccion_entrega` FOREIGN KEY (`id_direccion_entrega`) REFERENCES `direcciones` (`id_direccion`),
  CONSTRAINT `fk_pedidos_id_divisa` FOREIGN KEY (`id_divisa`) REFERENCES `divisas` (`id_divisa`),
  CONSTRAINT `fk_pedidos_id_razon_social` FOREIGN KEY (`id_razon_social`) REFERENCES `razones_sociales` (`id_razon_social`),
  CONSTRAINT `fk_pedidos_id_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

--
-- Table structure for table `pedidos_partidas`
--

DROP TABLE IF EXISTS `pedidos_partidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos_partidas` (
  `id_pedido_partida` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `estatus` varchar(100) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `score` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_pedido_partida`),
  KEY `fk_pedidos_partidas_id_pedido` (`id_pedido`),
  KEY `fk_pedidos_partidas_id_proveedor` (`id_proveedor`),
  CONSTRAINT `fk_pedidos_partidas_id_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  CONSTRAINT `fk_pedidos_partidas_id_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos_partidas`
--


--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `id_direccion` int(11) DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `ext` varchar(10) DEFAULT NULL,
  `celular` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`),
  KEY `fk_proveedores_id_direccion` (`id_direccion`),
  CONSTRAINT `fk_proveedores_id_direccion` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--


--
-- Table structure for table `razones_sociales`
--

DROP TABLE IF EXISTS `razones_sociales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `razones_sociales` (
  `id_razon_social` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  `id_metodo_pago` int(11) DEFAULT NULL,
  `id_forma_pago` int(11) DEFAULT NULL,
  `id_uso_cfdi` int(11) DEFAULT NULL,
  `id_regimen_fiscal` int(11) DEFAULT NULL,
  `limite_credito` decimal(10,2) DEFAULT NULL,
  `dias_credito` int(11) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `id_direccion_facturacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_razon_social`),
  KEY `fk_razones_sociales_id_cliente` (`id_cliente`),
  KEY `fk_razones_sociales_id_metodo_pago` (`id_metodo_pago`),
  KEY `fk_razones_sociales_id_forma_pago` (`id_forma_pago`),
  KEY `fk_razones_sociales_id_uso_cfdi` (`id_uso_cfdi`),
  KEY `fk_razones_sociales_id_regimen_fiscal` (`id_regimen_fiscal`),
  KEY `fk_razones_sociales_id_direccion_facturacion` (`id_direccion_facturacion`),
  CONSTRAINT `fk_razones_sociales_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `fk_razones_sociales_id_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodo_pagos` (`id_metodo_pago`),
  CONSTRAINT `fk_razones_sociales_id_forma_pago` FOREIGN KEY (`id_forma_pago`) REFERENCES `forma_pagos` (`id_forma_pago`),
  CONSTRAINT `fk_razones_sociales_id_uso_cfdi` FOREIGN KEY (`id_uso_cfdi`) REFERENCES `uso_cfdi` (`id_uso_cfdi`),
  CONSTRAINT `fk_razones_sociales_id_regimen_fiscal` FOREIGN KEY (`id_regimen_fiscal`) REFERENCES `regimen_fiscales` (`id_regimen_fiscal`)
  CONSTRAINT `fk_razones_sociales_id_direccion_facturacion` FOREIGN KEY (`id_direccion_facturacion`) REFERENCES `direcciones` (`id_direccion`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `razones_sociales`
--


--
-- Table structure for table `regimen_fiscales`
--

DROP TABLE IF EXISTS `regimen_fiscales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `regimen_fiscales` (
  `id_regimen_fiscal` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_regimen_fiscal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuaregimen_fiscalesrios`
--

/*!40000 ALTER TABLE `regimen_fiscales` DISABLE KEYS */;
INSERT INTO `regimen_fiscales` VALUES (1,'601','General de Ley Personas Morales','Moral'),(2,'603','Personas Morales con Fines no Lucrativos','Moral'),(3,'607','Régimen de Enajenación o Adquisición de Bienes','Moral'),(4,'610','Residentes en el Extranjero sin Establecimiento Permanente en México','Moral'),(5,'620','Sociedades Cooperativas de Producción que optan por diferir sus ingresos','Moral'),(6,'622','Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras','Moral'),(7,'623','Opcional para Grupos de Sociedades','Moral'),(8,'624','Coordinados','Moral'),(9,'626','Régimen Simplificado de Confianza','Moral'),(10,'628','Hidrocarburos','Moral'),
                                      (11,'605','Sueldos y Salarios e Ingresos Asimilados a Salarios','Fisica'),(12,'606','Arrendamiento','Fisica'),(13,'608','Demás ingresos','Fisica'),(14,'611','Ingresos por Dividendos (socios y accionistas)','Fisica'),(15,'612','Personas Físicas con Actividades Empresariales y Profesionales','Fisica'),(16,'614','Ingresos por intereses','Fisica'),(17,'615','Régimen de los ingresos por obtención de premios','Fisica'),(18,'616','Sin obligaciones fiscales','Fisica'),(19,'621','Incorporación Fiscal','Fisica'),(20,'625','Régimen de Actividades Empresariales con ingresos a través de Plataformas Tecnológicas','Fisica'),(21,'629','De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales','Fisica'),(22,'630','Enajenación de acciones en bolsa de valores');
/*!40000 ALTER TABLE `regimen_fiscales` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;



--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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

/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('J3PBdWAVHZ1U31AWOelny9y58KzvjO94Ik9SHzM4',1,'172.19.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU1hBVVBjNDRUSGVPUmkzTTJ3RUFsS1REZ0hZT21lRFByTUJpRTVCeSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjE2OiJodHRwOi8vbG9jYWxob3N0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1745555668);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `es_admin` tinyint(1) DEFAULT 0,
  `fecha_alta` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuarios_id_cliente` (`id_cliente`),
  CONSTRAINT `fk_usuarios_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'sistemas','sistemas@macasahs.com.mx','$2y$12$ikFedIUuMaYK6/7PNurHAeJr4ITkQpZ13yqx/JLgDHCGwK17gvNnO','Sistemas','ERP','Activo',NULL,1,NULL),(2,'mcarreon','mcarreon@macasahs.com.mx',NULL,'Direccion','ERP','Activo',NULL,1,NULL),(7,'prueba2','prueba2@algo.com',NULL,'Aux Compras','ERP','Inactivo',NULL,0,'2025-04-17 00:36:10');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-25  4:45:39
