<?php

namespace App\Core;

use Database\Seeders\DatabaseSeeder;
use PDO;

class Migrator
{
    public static function migrate(bool $fresh = true, bool $verbose = true): void
    {
        $pdo = Database::pdo();
        $driver = Database::driver();

        $files = glob(database_path('migrations/*.php')) ?: [];
        sort($files);

        if ($fresh) {
            if ($driver === 'mysql') {
                $pdo->exec('SET FOREIGN_KEY_CHECKS = 0;');
            } else {
                $pdo->exec('PRAGMA foreign_keys = OFF;');
            }

            foreach (array_reverse($files) as $file) {
                $migration = require $file;
                if (is_object($migration) && method_exists($migration, 'down')) {
                    $migration->down($pdo, $driver);
                }
            }

            if ($driver === 'mysql') {
                $pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');
            } else {
                $pdo->exec('PRAGMA foreign_keys = ON;');
            }
        }

        foreach ($files as $file) {
            $name = basename($file);
            $migration = require $file;
            if (is_object($migration) && method_exists($migration, 'up')) {
                $migration->up($pdo, $driver);
                if ($verbose) {
                    echo "  [MIGRATED] {$name} ({$driver})\n";
                }
            }
        }
    }

    public static function seed(bool $verbose = true): void
    {
        $seeder = new DatabaseSeeder();
        $seeder->run();
    }

    public static function migrateAndSeed(bool $verbose = true): void
    {
        self::migrate(true, $verbose);
        self::seed($verbose);
    }

    /**
     * Genera il dump SQL MySQL completo compatibile con Cloud1 Plesk / phpMyAdmin / MariaDB.
     */
    public static function generateSqlDump(string $outputPath): int
    {
        $pdo = Database::pdo();
        $tables = [
            'users',
            'roles',
            'permissions',
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions',
            'settings',
            'destinations',
            'suppliers',
            'products',
            'booking_requests',
            'booking_legs',
            'booking_items',
        ];

        $out = [];
        $out[] = '-- =============================================================================';
        $out[] = '-- MOSAIC · LUXURY EXPERIENCE CONCIERGE & RBAC PLATFORM';
        $out[] = '-- Dump SQL MySQL / MariaDB pronto per importazione su Cloud1 Plesk (phpMyAdmin)';
        $out[] = '-- Charset: utf8mb4 / Collation: utf8mb4_unicode_ci';
        $out[] = '-- =============================================================================';
        $out[] = '';
        $out[] = 'SET NAMES utf8mb4;';
        $out[] = 'SET FOREIGN_KEY_CHECKS = 0;';
        $out[] = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
        $out[] = 'SET time_zone = "+00:00";';
        $out[] = '';

        $mysqlSchemas = self::getMysqlCreateStatements();

        foreach ($tables as $table) {
            $out[] = "-- --------------------------------------------------------";
            $out[] = "-- Struttura della tabella `{$table}`";
            $out[] = "-- --------------------------------------------------------";
            $out[] = "DROP TABLE IF EXISTS `{$table}`;";
            $out[] = $mysqlSchemas[$table] . ';';
            $out[] = '';

            $rows = Database::select("SELECT * FROM `{$table}`");
            if (!empty($rows)) {
                $out[] = "-- Dump dei dati per la tabella `{$table}` (" . count($rows) . " record)";
                foreach ($rows as $row) {
                    $cols = array_map(fn($c) => "`{$c}`", array_keys($row));
                    $vals = array_map(function ($v) use ($pdo) {
                        if ($v === null) {
                            return 'NULL';
                        }
                        if (is_int($v) || is_float($v)) {
                            return (string)$v;
                        }
                        return $pdo->quote((string)$v);
                    }, array_values($row));

                    $out[] = sprintf(
                        'INSERT INTO `%s` (%s) VALUES (%s);',
                        $table,
                        implode(', ', $cols),
                        implode(', ', $vals)
                    );
                }
                $out[] = '';
            }
        }

        $out[] = 'SET FOREIGN_KEY_CHECKS = 1;';
        $out[] = '';

        $content = implode("\n", $out);
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($outputPath, $content);
        return strlen($content);
    }

    public static function getMysqlCreateStatements(): array
    {
        return [
            'users' => <<<SQL
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(64) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `company_name` VARCHAR(191) DEFAULT NULL,
  `vip_tier` VARCHAR(64) DEFAULT 'Platinum',
  `avatar_initials` VARCHAR(16) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'roles' => <<<SQL
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `guard_name` VARCHAR(125) NOT NULL DEFAULT 'web',
  `badge_color` VARCHAR(64) DEFAULT '#c6994f',
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'permissions' => <<<SQL
CREATE TABLE `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `guard_name` VARCHAR(125) NOT NULL DEFAULT 'web',
  `group_name` VARCHAR(100) DEFAULT 'general',
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'model_has_permissions' => <<<SQL
CREATE TABLE `model_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(191) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `mhp_permission_id_fk` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'model_has_roles' => <<<SQL
CREATE TABLE `model_has_roles` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(191) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `mhr_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'role_has_permissions' => <<<SQL
CREATE TABLE `role_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  CONSTRAINT `rhp_permission_id_fk` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rhp_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'settings' => <<<SQL
CREATE TABLE `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(125) NOT NULL,
  `value` TEXT DEFAULT NULL,
  `group_name` VARCHAR(64) DEFAULT 'system',
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'destinations' => <<<SQL
CREATE TABLE `destinations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(64) NOT NULL,
  `name` VARCHAR(191) NOT NULL,
  `region` VARCHAR(191) NOT NULL,
  `subtitle` TEXT DEFAULT NULL,
  `hero_image` VARCHAR(255) DEFAULT NULL,
  `lat` DECIMAL(10,7) NOT NULL,
  `lng` DECIMAL(10,7) NOT NULL,
  `zoom_level` INT NOT NULL DEFAULT 12,
  `locations_json` LONGTEXT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `destinations_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'suppliers' => <<<SQL
CREATE TABLE `suppliers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `company_name` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(125) NOT NULL,
  `contact_name` VARCHAR(191) DEFAULT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `phone` VARCHAR(64) DEFAULT NULL,
  `location` VARCHAR(191) DEFAULT NULL,
  `categories_json` TEXT DEFAULT NULL,
  `verified` TINYINT(1) NOT NULL DEFAULT 1,
  `rating` DECIMAL(3,2) NOT NULL DEFAULT 4.95,
  `commission_rate` DECIMAL(5,2) NOT NULL DEFAULT 15.00,
  `status` VARCHAR(64) NOT NULL DEFAULT 'active',
  `badge_label` VARCHAR(125) DEFAULT 'Fornitore Verificato',
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'products' => <<<SQL
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(32) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `category` VARCHAR(32) NOT NULL,
  `destination_id` BIGINT UNSIGNED DEFAULT NULL,
  `destination_slug` VARCHAR(64) NOT NULL,
  `supplier_id` BIGINT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(191) NOT NULL,
  `subtitle` VARCHAR(255) NOT NULL,
  `location` VARCHAR(191) NOT NULL,
  `price` INT NOT NULL,
  `unit` VARCHAR(32) NOT NULL DEFAULT 'giorno',
  `capacity` INT NOT NULL DEFAULT 2,
  `capacity_label` VARCHAR(64) NOT NULL,
  `featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_available` TINYINT(1) NOT NULL DEFAULT 1,
  `badge` VARCHAR(100) DEFAULT NULL,
  `subtype` VARCHAR(100) DEFAULT NULL,
  `lng` DECIMAL(10,6) NOT NULL,
  `lat` DECIMAL(10,6) NOT NULL,
  `map_x` DECIMAL(6,2) NOT NULL DEFAULT 50,
  `map_y` DECIMAL(6,2) NOT NULL DEFAULT 50,
  `description` LONGTEXT NOT NULL,
  `cancellation_policy` TEXT DEFAULT NULL,
  `images_json` LONGTEXT NOT NULL,
  `amenities_json` LONGTEXT NOT NULL,
  `services_included_json` LONGTEXT NOT NULL,
  `services_excluded_json` LONGTEXT NOT NULL,
  `rules_json` LONGTEXT NOT NULL,
  `specs_extra_json` LONGTEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_code_unique` (`code`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_dest_idx` (`category`, `destination_slug`),
  KEY `products_supplier_idx` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'booking_requests' => <<<SQL
CREATE TABLE `booking_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_code` VARCHAR(64) NOT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `client_first_name` VARCHAR(125) NOT NULL,
  `client_last_name` VARCHAR(125) NOT NULL,
  `client_email` VARCHAR(191) NOT NULL,
  `client_phone` VARCHAR(64) DEFAULT NULL,
  `client_notes` TEXT DEFAULT NULL,
  `ai_prompt_used` TEXT DEFAULT NULL,
  `status` VARCHAR(48) NOT NULL DEFAULT 'nuova',
  `total_estimated` INT NOT NULL DEFAULT 0,
  `custom_total_offer` INT DEFAULT NULL,
  `discount_percent` INT NOT NULL DEFAULT 0,
  `concierge_notes` TEXT DEFAULT NULL,
  `offer_valid_until` VARCHAR(64) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_requests_reference_unique` (`reference_code`),
  KEY `booking_requests_status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'booking_legs' => <<<SQL
CREATE TABLE `booking_legs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_request_id` BIGINT UNSIGNED NOT NULL,
  `leg_index` INT NOT NULL DEFAULT 1,
  `destination_slug` VARCHAR(64) NOT NULL,
  `destination_name` VARCHAR(191) NOT NULL,
  `start_date` VARCHAR(64) NOT NULL,
  `end_date` VARCHAR(64) NOT NULL,
  `days` INT NOT NULL DEFAULT 3,
  `adults` INT NOT NULL DEFAULT 2,
  `children` INT NOT NULL DEFAULT 0,
  `categories_json` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_legs_req_idx` (`booking_request_id`),
  CONSTRAINT `booking_legs_request_fk` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
            'booking_items' => <<<SQL
CREATE TABLE `booking_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_request_id` BIGINT UNSIGNED NOT NULL,
  `booking_leg_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED DEFAULT NULL,
  `supplier_id` BIGINT UNSIGNED DEFAULT NULL,
  `category` VARCHAR(32) NOT NULL,
  `product_title` VARCHAR(191) NOT NULL,
  `product_subtitle` VARCHAR(255) DEFAULT NULL,
  `product_location` VARCHAR(191) DEFAULT NULL,
  `product_image` VARCHAR(255) DEFAULT NULL,
  `unit_price` INT NOT NULL,
  `unit_label` VARCHAR(32) NOT NULL DEFAULT 'giorno',
  `quantity_days` INT NOT NULL DEFAULT 1,
  `subtotal` INT NOT NULL,
  `supplier_status` VARCHAR(48) NOT NULL DEFAULT 'in_attesa',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_items_req_idx` (`booking_request_id`),
  KEY `booking_items_supplier_idx` (`supplier_id`),
  CONSTRAINT `booking_items_request_fk` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_items_leg_fk` FOREIGN KEY (`booking_leg_id`) REFERENCES `booking_legs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL,
        ];
    }
}
