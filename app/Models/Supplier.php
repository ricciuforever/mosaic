<?php

namespace App\Models;

use App\Core\Database;

class Supplier
{
    public static function allWithCounts(): array
    {
        $rows = Database::select(
            "SELECT s.*,
                    (SELECT COUNT(*) FROM products p WHERE p.supplier_id = s.id) AS products_count,
                    (SELECT COUNT(*) FROM booking_items bi WHERE bi.supplier_id = s.id) AS bookings_count
             FROM suppliers s
             ORDER BY s.company_name ASC"
        );
        foreach ($rows as &$r) {
            $r['categories'] = json_decode($r['categories_json'] ?? '[]', true) ?: [];
        }
        return $rows;
    }

    public static function find(int $id): ?array
    {
        $r = Database::selectOne("SELECT * FROM suppliers WHERE id = ? LIMIT 1", [$id]);
        if ($r) {
            $r['categories'] = json_decode($r['categories_json'] ?? '[]', true) ?: [];
        }
        return $r;
    }

    public static function findBySlug(string $slug): ?array
    {
        $r = Database::selectOne("SELECT * FROM suppliers WHERE slug = ? LIMIT 1", [$slug]);
        if ($r) {
            $r['categories'] = json_decode($r['categories_json'] ?? '[]', true) ?: [];
        }
        return $r;
    }
}
