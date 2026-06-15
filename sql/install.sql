-- =====================================================================
--  Europachape — Script d'installation de la base de données
--  À importer dans phpMyAdmin (IONOS) sur la base déjà créée.
--  Compatible MySQL 5.7+ / MariaDB 10.3+
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- ---------------------------------------------------------------------
--  Table : admin_users  (comptes administrateurs)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `login`         VARCHAR(60)  NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table : company_info  (coordonnées de l'entreprise, en clé/valeur)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `company_info` (
  `cle`    VARCHAR(60)   NOT NULL,
  `valeur` TEXT          NULL,
  PRIMARY KEY (`cle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table : `references`  (projets / chantiers de référence)
--  NB : "references" est un mot réservé -> toujours entre backticks.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `references` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre`       VARCHAR(180) NOT NULL,
  `slug`        VARCHAR(200) NOT NULL,
  `description` MEDIUMTEXT   NULL,
  `lieu`        VARCHAR(180) NULL,
  `surface`     VARCHAR(60)  NULL,
  `date_projet` DATE         NULL,
  `is_published` TINYINT(1)  NOT NULL DEFAULT 1,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table : reference_images  (images rattachées à une référence)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reference_images` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_id`  INT UNSIGNED NOT NULL,
  `chemin_fichier` VARCHAR(255) NOT NULL,
  `alt`           VARCHAR(200) NULL,
  `ordre`         INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_reference` (`reference_id`),
  CONSTRAINT `fk_image_reference`
    FOREIGN KEY (`reference_id`) REFERENCES `references` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
