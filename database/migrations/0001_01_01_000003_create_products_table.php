<?php

use App\Core\Migrator;

return new class {
    public function up(\PDO $pdo, string $driver): void
    {
        if ($driver === 'mysql') {
            $schemas = Migrator::getMysqlCreateStatements();
            $pdo->exec('DROP TABLE IF EXISTS `products`');
            $pdo->exec($schemas['products']);
            return;
        }

        $pdo->exec('DROP TABLE IF EXISTS `products`');
        $pdo->exec(<<<SQL
CREATE TABLE `products` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `code` VARCHAR(32) NOT NULL UNIQUE,
  `slug` VARCHAR(191) NOT NULL UNIQUE,
  `category` VARCHAR(32) NOT NULL,
  `destination_id` INTEGER DEFAULT NULL,
  `destination_slug` VARCHAR(64) NOT NULL,
  `supplier_id` INTEGER DEFAULT NULL,
  `title` VARCHAR(191) NOT NULL,
  `subtitle` VARCHAR(255) NOT NULL,
  `location` VARCHAR(191) NOT NULL,
  `price` INTEGER NOT NULL,
  `unit` VARCHAR(32) NOT NULL DEFAULT 'giorno',
  `capacity` INTEGER NOT NULL DEFAULT 2,
  `capacity_label` VARCHAR(64) NOT NULL,
  `featured` INTEGER NOT NULL DEFAULT 0,
  `is_available` INTEGER NOT NULL DEFAULT 1,
  `badge` VARCHAR(100) DEFAULT NULL,
  `subtype` VARCHAR(100) DEFAULT NULL,
  `lng` DECIMAL(10,6) NOT NULL,
  `lat` DECIMAL(10,6) NOT NULL,
  `map_x` DECIMAL(6,2) NOT NULL DEFAULT 50,
  `map_y` DECIMAL(6,2) NOT NULL DEFAULT 50,
  `description` TEXT NOT NULL,
  `cancellation_policy` TEXT DEFAULT NULL,
  `images_json` TEXT NOT NULL,
  `amenities_json` TEXT NOT NULL,
  `services_included_json` TEXT NOT NULL,
  `services_excluded_json` TEXT NOT NULL,
  `rules_json` TEXT NOT NULL,
  `specs_extra_json` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
)
SQL);
    }

    public function down(\PDO $pdo, string $driver): void
    {
        $pdo->exec('DROP TABLE IF EXISTS `products`');
    }
};
