<?php

use App\Core\Migrator;

return new class {
    public function up(\PDO $pdo, string $driver): void
    {
        if ($driver === 'mysql') {
            $schemas = Migrator::getMysqlCreateStatements();
            $pdo->exec('DROP TABLE IF EXISTS `suppliers`');
            $pdo->exec('DROP TABLE IF EXISTS `destinations`');
            $pdo->exec($schemas['destinations']);
            $pdo->exec($schemas['suppliers']);
            return;
        }

        $pdo->exec('DROP TABLE IF EXISTS `suppliers`');
        $pdo->exec('DROP TABLE IF EXISTS `destinations`');

        $pdo->exec(<<<SQL
CREATE TABLE `destinations` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `slug` VARCHAR(64) NOT NULL UNIQUE,
  `name` VARCHAR(191) NOT NULL,
  `region` VARCHAR(191) NOT NULL,
  `subtitle` TEXT DEFAULT NULL,
  `hero_image` VARCHAR(255) DEFAULT NULL,
  `lat` DECIMAL(10,7) NOT NULL,
  `lng` DECIMAL(10,7) NOT NULL,
  `zoom_level` INTEGER NOT NULL DEFAULT 12,
  `locations_json` TEXT DEFAULT NULL,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `sort_order` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
)
SQL);

        $pdo->exec(<<<SQL
CREATE TABLE `suppliers` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user_id` INTEGER DEFAULT NULL,
  `company_name` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(125) NOT NULL UNIQUE,
  `contact_name` VARCHAR(191) DEFAULT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `phone` VARCHAR(64) DEFAULT NULL,
  `location` VARCHAR(191) DEFAULT NULL,
  `categories_json` TEXT DEFAULT NULL,
  `verified` INTEGER NOT NULL DEFAULT 1,
  `rating` DECIMAL(3,2) NOT NULL DEFAULT 4.95,
  `commission_rate` DECIMAL(5,2) NOT NULL DEFAULT 15.00,
  `status` VARCHAR(64) NOT NULL DEFAULT 'active',
  `badge_label` VARCHAR(125) DEFAULT 'Fornitore Verificato',
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
)
SQL);
    }

    public function down(\PDO $pdo, string $driver): void
    {
        $pdo->exec('DROP TABLE IF EXISTS `suppliers`');
        $pdo->exec('DROP TABLE IF EXISTS `destinations`');
    }
};
