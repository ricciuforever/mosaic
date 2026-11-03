<?php

use App\Core\Migrator;

return new class {
    public function up(\PDO $pdo, string $driver): void
    {
        if ($driver === 'mysql') {
            $schemas = Migrator::getMysqlCreateStatements();
            $pdo->exec('DROP TABLE IF EXISTS `users`');
            $pdo->exec($schemas['users']);
            $pdo->exec('DROP TABLE IF EXISTS `settings`');
            $pdo->exec($schemas['settings']);
            return;
        }

        $pdo->exec('DROP TABLE IF EXISTS `users`');
        $pdo->exec(<<<SQL
CREATE TABLE `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `phone` VARCHAR(64) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `company_name` VARCHAR(191) DEFAULT NULL,
  `vip_tier` VARCHAR(64) DEFAULT 'Platinum',
  `avatar_initials` VARCHAR(16) DEFAULT NULL,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
)
SQL);

        $pdo->exec('DROP TABLE IF EXISTS `settings`');
        $pdo->exec(<<<SQL
CREATE TABLE `settings` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `key` VARCHAR(125) NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL,
  `group_name` VARCHAR(64) DEFAULT 'system',
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
)
SQL);
    }

    public function down(\PDO $pdo, string $driver): void
    {
        $pdo->exec('DROP TABLE IF EXISTS `settings`');
        $pdo->exec('DROP TABLE IF EXISTS `users`');
    }
};
