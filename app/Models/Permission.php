<?php

namespace App\Models;

use App\Core\Database;

class Permission
{
    public static function all(): array
    {
        return Database::select("SELECT * FROM permissions ORDER BY group_name ASC, id ASC");
    }

    public static function grouped(): array
    {
        $grouped = [];
        foreach (self::all() as $perm) {
            $group = $perm['group_name'] ?: 'general';
            $grouped[$group][] = $perm;
        }
        return $grouped;
    }
}
