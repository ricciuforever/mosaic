<?php

namespace App\Models;

use App\Core\Database;

class Destination
{
    public static function allActive(): array
    {
        $rows = Database::select("SELECT * FROM destinations WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        foreach ($rows as &$r) {
            $r['locations'] = json_decode($r['locations_json'] ?? '[]', true) ?: [];
        }
        return $rows;
    }

    public static function findBySlug(string $slug): ?array
    {
        $r = Database::selectOne("SELECT * FROM destinations WHERE slug = ? LIMIT 1", [$slug]);
        if ($r) {
            $r['locations'] = json_decode($r['locations_json'] ?? '[]', true) ?: [];
        }
        return $r;
    }
}
