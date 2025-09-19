-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 15 Eyl 2025, 22:19:05
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `binsiyon`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `education_status` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_email_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `is_banned_by_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `site_id`, `name`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `district`, `education_status`, `email_verified_at`, `password`, `remember_token`, `is_email_confirmed`, `confirmation_token`, `is_banned_by_admin`, `is_admin`, `created_at`, `updated_at`, `unit_id`, `package_id`, `banned_at`) VALUES
(1, NULL, 'ismail ÖZKUL', '', '', 'iozkul@hotmail.com', NULL, NULL, NULL, NULL, NULL, '2025-09-01 11:23:50', '$2y$12$..DkVClHtW.RWL2Ns.mH.ub0SLmyRYnIxuOnA6rpTp88.OEKKXzbK', NULL, 1, NULL, 0, 1, '2025-08-28 13:06:17', '2025-09-05 05:17:33', NULL, NULL, NULL),
(2, NULL, 'Mehmet Alp', '', '', 'io@hotmail.com.tr', NULL, NULL, NULL, NULL, NULL, '2025-09-01 11:23:50', '$2y$12$TC90827N5Hncu39j12wur.CHTSEXaKrEjWM.O3V0XFec4M2HE9gZq', NULL, 1, NULL, 0, 0, '2025-09-04 11:26:26', '2025-09-04 11:26:26', NULL, NULL, NULL),
(3, NULL, 'Harun Eymen ÖZKUL', '', '', 'iistanbul@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$2L.PWyf3mqZRiAy.E.AGC.gIfet1n9p.4fz9Om4GY1Bcdl4s6C8iW', NULL, 0, 'Vxx4fI7qLd0Hj8Ve3QxV7fi13e9Ktz3WsiU3uBiWpsX8U9LyS6uvHoGbUSm0', 0, 0, '2025-09-10 10:26:02', '2025-09-10 10:37:17', NULL, 1, NULL),
(4, NULL, 'Ahmet Karabulut', 'Ahmet', 'Karabulut', 'ahmet@karabulut.com', '0001115525', 'karasu', 'Sakarya', 'Karasu', 'Üniversite', NULL, '$2y$12$9tBYSxzadJ034lejln2NsehmyGW70g6liCcCIMNl0ml355XI9Z.W2', NULL, 0, 'eE5qZIGAFR4ZAqlwesd1uovH4RXZOt9DoBqzfAulHoIpJwZS3H0ozriir87N', 0, 0, '2025-09-13 14:40:09', '2025-09-13 14:40:11', NULL, NULL, NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_confirmation_token_unique` (`confirmation_token`),
  ADD KEY `users_unit_id_foreign` (`unit_id`),
  ADD KEY `users_site_id_foreign` (`site_id`),
  ADD KEY `users_package_id_foreign` (`package_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
