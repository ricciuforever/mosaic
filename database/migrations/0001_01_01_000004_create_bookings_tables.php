<?php

use App\Core\Migrator;

return new class {
    public function up(\PDO $pdo, string $driver): void
    {
        if ($driver === 'mysql') {
            $schemas = Migrator::getMysqlCreateStatements();
            foreach (['booking_items', 'booking_legs', 'booking_requests'] as $t) {
                $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
            }
            foreach (['booking_requests', 'booking_legs', 'booking_items'] as $t) {
                $pdo->exec($schemas[$t]);
            }
            return;
        }

        foreach (['booking_items', 'booking_legs', 'booking_requests'] as $t) {
            $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
        }

        $pdo->exec(<<<SQL
CREATE TABLE `booking_requests` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `reference_code` VARCHAR(64) NOT NULL UNIQUE,
  `user_id` INTEGER DEFAULT NULL,
  `client_first_name` VARCHAR(125) NOT NULL,
  `client_last_name` VARCHAR(125) NOT NULL,
  `client_email` VARCHAR(191) NOT NULL,
  `client_phone` VARCHAR(64) DEFAULT NULL,
  `client_notes` TEXT DEFAULT NULL,
  `ai_prompt_used` TEXT DEFAULT NULL,
  `status` VARCHAR(48) NOT NULL DEFAULT 'nuova',
  `total_estimated` INTEGER NOT NULL DEFAULT 0,
  `custom_total_offer` INTEGER DEFAULT NULL,
  `discount_percent` INTEGER NOT NULL DEFAULT 0,
  `concierge_notes` TEXT DEFAULT NULL,
  `offer_valid_until` VARCHAR(64) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
)
SQL);

        $pdo->exec(<<<SQL
CREATE TABLE `booking_legs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `booking_request_id` INTEGER NOT NULL,
  `leg_index` INTEGER NOT NULL DEFAULT 1,
  `destination_slug` VARCHAR(64) NOT NULL,
  `destination_name` VARCHAR(191) NOT NULL,
  `start_date` VARCHAR(64) NOT NULL,
  `end_date` VARCHAR(64) NOT NULL,
  `days` INTEGER NOT NULL DEFAULT 3,
  `adults` INTEGER NOT NULL DEFAULT 2,
  `children` INTEGER NOT NULL DEFAULT 0,
  `categories_json` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE
)
SQL);

        $pdo->exec(<<<SQL
CREATE TABLE `booking_items` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `booking_request_id` INTEGER NOT NULL,
  `booking_leg_id` INTEGER NOT NULL,
  `product_id` INTEGER DEFAULT NULL,
  `supplier_id` INTEGER DEFAULT NULL,
  `category` VARCHAR(32) NOT NULL,
  `product_title` VARCHAR(191) NOT NULL,
  `product_subtitle` VARCHAR(255) DEFAULT NULL,
  `product_location` VARCHAR(191) DEFAULT NULL,
  `product_image` VARCHAR(255) DEFAULT NULL,
  `unit_price` INTEGER NOT NULL,
  `unit_label` VARCHAR(32) NOT NULL DEFAULT 'giorno',
  `quantity_days` INTEGER NOT NULL DEFAULT 1,
  `subtotal` INTEGER NOT NULL,
  `supplier_status` VARCHAR(48) NOT NULL DEFAULT 'in_attesa',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`booking_leg_id`) REFERENCES `booking_legs` (`id`) ON DELETE CASCADE
)
SQL);
    }

    public function down(\PDO $pdo, string $driver): void
    {
        foreach (['booking_items', 'booking_legs', 'booking_requests'] as $t) {
            $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
        }
    }
};
