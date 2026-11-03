<?php

namespace Spatie\Permission\Traits;

use App\Core\Database;
use App\Models\Role;

trait HasRoles
{
    private ?array $loadedRoles = null;
    private ?array $loadedPermissions = null;

    public function getMorphClass(): string
    {
        return static::class;
    }

    public function roles(): array
    {
        if ($this->loadedRoles !== null) {
            return $this->loadedRoles;
        }
        $rows = Database::select(
            'SELECT r.* FROM `roles` r
             INNER JOIN `model_has_roles` mhr ON mhr.role_id = r.id
             WHERE mhr.model_id = :mid AND mhr.model_type = :mtype',
            [':mid' => $this->id, ':mtype' => $this->getMorphClass()]
        );
        $this->loadedRoles = $rows;
        return $this->loadedRoles;
    }

    public function getRoleNames(): array
    {
        return array_map(fn($r) => is_array($r) ? (string)$r['name'] : (string)$r->name, $this->roles());
    }

    public function primaryRole(): string
    {
        $names = $this->getRoleNames();
        return $names[0] ?? 'Cliente VIP';
    }

    public function getAllPermissions(): array
    {
        if ($this->loadedPermissions !== null) {
            return $this->loadedPermissions;
        }

        $rows = Database::select(
            'SELECT DISTINCT p.* FROM `permissions` p
             LEFT JOIN `role_has_permissions` rhp ON rhp.permission_id = p.id
             LEFT JOIN `model_has_roles` mhr ON mhr.role_id = rhp.role_id AND mhr.model_id = :mid1 AND mhr.model_type = :mtype1
             LEFT JOIN `model_has_permissions` mhp ON mhp.permission_id = p.id AND mhp.model_id = :mid2 AND mhp.model_type = :mtype2
             WHERE mhr.role_id IS NOT NULL OR mhp.permission_id IS NOT NULL',
            [
                ':mid1' => $this->id,
                ':mtype1' => $this->getMorphClass(),
                ':mid2' => $this->id,
                ':mtype2' => $this->getMorphClass(),
            ]
        );

        $this->loadedPermissions = $rows;
        return $this->loadedPermissions;
    }

    public function getPermissionNames(): array
    {
        return array_map(fn($p) => is_array($p) ? (string)$p['name'] : (string)$p->name, $this->getAllPermissions());
    }

    public function hasRole(string|array $roles): bool
    {
        $userRoles = array_map('strtolower', $this->getRoleNames());
        if (is_string($roles)) {
            $roles = array_map('trim', explode('|', $roles));
        }
        foreach ((array)$roles as $role) {
            if (in_array(strtolower((string)$role), $userRoles, true)) {
                return true;
            }
        }
        return false;
    }

    public function hasAnyRole(array|string ...$roles): bool
    {
        $flat = [];
        foreach ($roles as $r) {
            if (is_array($r)) {
                $flat = array_merge($flat, $r);
            } else {
                $flat = array_merge($flat, explode('|', (string)$r));
            }
        }
        return $this->hasRole($flat);
    }

    public function hasAllRoles(array $roles): bool
    {
        $userRoles = array_map('strtolower', $this->getRoleNames());
        foreach ($roles as $role) {
            if (!in_array(strtolower((string)$role), $userRoles, true)) {
                return false;
            }
        }
        return true;
    }

    public function hasPermissionTo(string $permission): bool
    {
        if ($this->hasRole('Super Admin')) {
            return true;
        }
        $perms = array_map('strtolower', $this->getPermissionNames());
        return in_array(strtolower(trim($permission)), $perms, true);
    }

    public function hasAnyPermission(array|string ...$permissions): bool
    {
        if ($this->hasRole('Super Admin')) {
            return true;
        }
        $flat = [];
        foreach ($permissions as $p) {
            if (is_array($p)) {
                $flat = array_merge($flat, $p);
            } else {
                $flat = array_merge($flat, explode('|', (string)$p));
            }
        }
        foreach ($flat as $perm) {
            if ($this->hasPermissionTo(trim((string)$perm))) {
                return true;
            }
        }
        return false;
    }

    public function can(string $permission): bool
    {
        return $this->hasPermissionTo($permission);
    }

    public function assignRole(string|array ...$roles): self
    {
        foreach ($roles as $role) {
            $roleRow = is_array($role) ? $role : Role::findByName((string)$role);
            if (!$roleRow) {
                continue;
            }
            $roleId = (int)$roleRow['id'];
            $exists = Database::selectOne(
                'SELECT 1 FROM `model_has_roles` WHERE role_id = :rid AND model_id = :mid AND model_type = :mtype',
                [':rid' => $roleId, ':mid' => $this->id, ':mtype' => $this->getMorphClass()]
            );
            if (!$exists) {
                Database::insert('model_has_roles', [
                    'role_id' => $roleId,
                    'model_type' => $this->getMorphClass(),
                    'model_id' => $this->id,
                ]);
            }
        }
        $this->loadedRoles = null;
        $this->loadedPermissions = null;
        return $this;
    }

    public function syncRoles(array $roles): self
    {
        Database::execute(
            'DELETE FROM `model_has_roles` WHERE model_id = :mid AND model_type = :mtype',
            [':mid' => $this->id, ':mtype' => $this->getMorphClass()]
        );
        $this->loadedRoles = null;
        $this->loadedPermissions = null;
        return $this->assignRole(...$roles);
    }
}
