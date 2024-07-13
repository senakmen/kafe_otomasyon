-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 09 Haz 2024, 21:33:09
-- Sunucu sürümü: 8.2.0
-- PHP Sürümü: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `cafe_otomasyon`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `faturalar`
--

DROP TABLE IF EXISTS `faturalar`;
CREATE TABLE IF NOT EXISTS `faturalar` (
  `fatura_id` int NOT NULL AUTO_INCREMENT,
  `toplam_fiyat` decimal(10,2) DEFAULT NULL,
  `masa_id` int DEFAULT NULL,
  `garson_id` int DEFAULT NULL,
  PRIMARY KEY (`fatura_id`),
  KEY `masa_id` (`masa_id`),
  KEY `garson_id` (`garson_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE IF NOT EXISTS `kullanicilar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `giris_turu` enum('admin','garson','kasa') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `kullanici_adi`, `sifre`, `giris_turu`) VALUES
(1, 'cevat', '1234', 'admin'),
(2, 'nuri', '1234', 'garson'),
(3, 'k116', '116116', 'kasa'),
(8, 'emincan', '1234', 'garson'),
(7, 'sena', '1234', 'kasa');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `masalar`
--

DROP TABLE IF EXISTS `masalar`;
CREATE TABLE IF NOT EXISTS `masalar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `masalar`
--

INSERT INTO `masalar` (`id`, `status`) VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0),
(10, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `menu_items`
--

INSERT INTO `menu_items` (`id`, `category`, `name`, `price`, `image_path`, `type`) VALUES
(1, 'Fast Food', 'Pizza', 150.00, NULL, NULL),
(2, 'Fast Food', 'Burger', 160.00, NULL, NULL),
(3, 'Fast Food', 'Wrap', 85.00, NULL, NULL),
(4, 'Tostlar', 'Karışık Tost', 50.00, NULL, NULL),
(5, 'Kahveler', 'Americano', 55.00, NULL, NULL),
(7, 'Kahveler', 'Latte', 60.00, NULL, NULL),
(8, 'Kahveler', 'Mocha', 60.00, NULL, NULL),
(9, 'Soğuk İçecekler', 'Kola', 35.00, NULL, NULL),
(10, 'Soğuk İçecekler', 'İce Tea', 30.00, NULL, NULL),
(11, 'Soğuk İçecekler', 'Gazoz', 30.00, NULL, NULL),
(12, 'Soğuk İçecekler', 'Soda', 25.00, NULL, NULL),
(14, 'Kahvaltılar', 'Serpme Kahvaltı', 50.00, NULL, NULL),
(15, 'Makarnalar', 'Fettucini Alfredo', 85.00, NULL, NULL),
(17, 'Makarnalar', 'Penne Arabiata', 75.00, NULL, NULL),
(24, 'Kahveler', 'Filtre Kahve', 60.00, NULL, NULL),
(32, 'Salatalar', 'Sezar Salata', 75.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `table_id` int NOT NULL,
  `garson_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `table_id` (`table_id`)
) ENGINE=MyISAM AUTO_INCREMENT=525 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `item_id`, `quantity`, `total_price`, `table_id`, `garson_id`) VALUES
(451, 1, 1, 40.00, 0, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

DROP TABLE IF EXISTS `siparisler`;
CREATE TABLE IF NOT EXISTS `siparisler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `masa_id` int DEFAULT NULL,
  `siparis` text,
  PRIMARY KEY (`id`),
  KEY `masa_id` (`masa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `masa_id`, `siparis`) VALUES
(1, 5, NULL),
(2, 5, NULL),
(3, 5, NULL),
(4, 5, NULL),
(5, 5, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_kayit`
--

DROP TABLE IF EXISTS `siparis_kayit`;
CREATE TABLE IF NOT EXISTS `siparis_kayit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `table_id` int NOT NULL,
  `garson_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
