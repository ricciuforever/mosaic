<?php

namespace App\Models;

use App\Core\Database;

class Role
{
    public static function all(): array
    {
        $roles = Database::select("SELECT * FROM roles ORDER BY id ASC");
        foreach ($roles as &$r) {
            $r['permissions'] = self::permissionsForRole((int)$r['id']);
        }
        return $roles;
    }

    public static function findByName(string $name): ?array
    {
        return Database::selectOne("SELECT * FROM roles WHERE name = ? LIMIT 1", [$name]);
    }

    public static function permissionsForRole(int $roleId): array
    {
        return Database::select(
            "SELECT p.* FROM permissions p
             INNER JOIN role_has_permissions rhp ON rhp.permission_id = p.id
             WHERE rhp.role_id = ?
             ORDER BY p.group_name ASC, p.name ASC",
            [$roleId]
        );
    }

    public static function syncPermissions(int $roleId, array $permissionIds): void
    {
        Database::execute("DELETE FROM role_has_permissions WHERE role_id = ?", [$roleId]);
        foreach (array_unique($permissionIds) as $pid) {
            Database::insert('role_has_permissions', [
                'permission_id' => (int)$pid,
                'role_id' => $roleId,
            ]);
        }
    }
}
