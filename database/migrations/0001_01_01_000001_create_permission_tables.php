<?php

use App\Core\Migrator;

return new class {
    public function up(\PDO $pdo, string $driver): void
    {
        if ($driver === 'mysql') {
            $schemas = Migrator::getMysqlCreateStatements();
            foreach (['role_has_permissions', 'model_has_roles', 'model_has_permissions', 'permissions', 'roles'] as $t) {
                $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
            }
            foreach (['roles', 'permissions', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'] as $t) {
                $pdo->exec($schemas[$t]);
            }
            return;
        }

        foreach (['role_has_permissions', 'model_has_roles', 'model_has_permissions', 'permissions', 'roles'] as $t) {
            $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
        }

        $pdo->exec(<<<SQL
CREATE TABLE `roles` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `guard_name` VARCHAR(125) NOT NULL DEFAULT 'web',
  `badge_color` VARCHAR(64) DEFAULT '#c6994f',
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (`name`, `guard_name`)
)
SQL);

        $pdo->exec(<<<SQL
CREATE TABLE `permissions` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `guard_name` VARCHAR(125) NOT NULL DEFAULT 'web',
  `group_name` VARCHAR(100) DEFAULT 'general',
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (`name`, `guard_name`)
)
SQL);

        $pdo->exec(<<<SQL
CREATE TABLE `model_has_permissions` (
  `permission_id` INTEGER NOT NULL,
  `model_type` VARCHAR(191) NOT NULL,
  `model_id` INTEGER NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
)
SQL);

        $pdo->exec(<<<SQL
CREATE TABLE `model_has_roles` (
  `role_id` INTEGER NOT NULL,
  `model_type` VARCHAR(191) NOT NULL,
  `model_id` INTEGER NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
)
SQL);

        $pdo->exec(<<<SQL
CREATE TABLE `role_has_permissions` (
  `permission_id` INTEGER NOT NULL,
  `role_id` INTEGER NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
)
SQL);
    }

    public function down(\PDO $pdo, string $driver): void
    {
        foreach (['role_has_permissions', 'model_has_roles', 'model_has_permissions', 'permissions', 'roles'] as $t) {
            $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
        }
    }
};
