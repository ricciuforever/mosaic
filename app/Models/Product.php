<?php

namespace App\Models;

use App\Core\Database;

class Product
{
    public static function hydrate(array $r): array
    {
        $r['id'] = (int)$r['id'];
        $r['price'] = (int)$r['price'];
        $r['capacity'] = (int)$r['capacity'];
        $r['lng'] = (float)$r['lng'];
        $r['lat'] = (float)$r['lat'];
        $r['map_x'] = (float)$r['map_x'];
        $r['map_y'] = (float)$r['map_y'];
        $r['featured'] = (bool)$r['featured'];
        $r['is_available'] = (bool)$r['is_available'];
        $r['images'] = json_decode($r['images_json'] ?? '[]', true) ?: [];
        $r['amenities'] = json_decode($r['amenities_json'] ?? '[]', true) ?: [];
        $r['servicesIncluded'] = json_decode($r['services_included_json'] ?? '[]', true) ?: [];
        $r['servicesExcluded'] = json_decode($r['services_excluded_json'] ?? '[]', true) ?: [];
        $r['rules'] = json_decode($r['rules_json'] ?? '[]', true) ?: [];
        $r['extra'] = json_decode($r['specs_extra_json'] ?? '{}', true) ?: [];
        $r['lnglat'] = [$r['lng'], $r['lat']];
        $r['map'] = ['x' => $r['map_x'], 'y' => $r['map_y']];
        return $r;
    }

    public static function all(bool $onlyAvailable = true): array
    {
        $sql = "SELECT p.*, s.company_name AS supplier_company, s.verified AS supplier_verified, s.rating AS supplier_rating,
                       d.name AS destination_name, d.region AS destination_region
                FROM products p
                LEFT JOIN suppliers s ON s.id = p.supplier_id
                LEFT JOIN destinations d ON d.id = p.destination_id";
        if ($onlyAvailable) {
            $sql .= " WHERE p.is_available = 1";
        }
        $sql .= " ORDER BY p.featured DESC, p.price DESC, p.id ASC";
        $rows = Database::select($sql);
        return array_map([self::class, 'hydrate'], $rows);
    }

    public static function findBySlug(string $slug): ?array
    {
        $row = Database::selectOne(
            "SELECT p.*, s.company_name AS supplier_company, s.verified AS supplier_verified, s.rating AS supplier_rating,
                    s.location AS supplier_location, s.contact_name AS supplier_contact, s.badge_label AS supplier_badge,
                    d.name AS destination_name, d.region AS destination_region
             FROM products p
             LEFT JOIN suppliers s ON s.id = p.supplier_id
             LEFT JOIN destinations d ON d.id = p.destination_id
             WHERE p.slug = ? LIMIT 1",
            [$slug]
        );
        return $row ? self::hydrate($row) : null;
    }

    public static function find(int $id): ?array
    {
        $row = Database::selectOne(
            "SELECT p.*, s.company_name AS supplier_company, s.verified AS supplier_verified
             FROM products p
             LEFT JOIN suppliers s ON s.id = p.supplier_id
             WHERE p.id = ? LIMIT 1",
            [$id]
        );
        return $row ? self::hydrate($row) : null;
    }

    public static function findByCode(string $code): ?array
    {
        $row = Database::selectOne(
            "SELECT p.*, s.company_name AS supplier_company
             FROM products p
             LEFT JOIN suppliers s ON s.id = p.supplier_id
             WHERE p.code = ? LIMIT 1",
            [$code]
        );
        return $row ? self::hydrate($row) : null;
    }

    public static function similar(array $product, int $limit = 3): array
    {
        $rows = Database::select(
            "SELECT p.*, s.company_name AS supplier_company, s.verified AS supplier_verified
             FROM products p
             LEFT JOIN suppliers s ON s.id = p.supplier_id
             WHERE p.category = ? AND p.id != ? AND p.is_available = 1
             ORDER BY (p.destination_slug = ?) DESC, ABS(p.price - ?) ASC
             LIMIT " . (int)$limit,
            [$product['category'], $product['id'], $product['destination_slug'], $product['price']]
        );
        return array_map([self::class, 'hydrate'], $rows);
    }

    public static function filter(array $filters = []): array
    {
        $where = ['p.is_available = 1'];
        $params = [];

        if (!empty($filters['category']) && $filters['category'] !== 'all') {
            $where[] = 'p.category = ?';
            $params[] = $filters['category'];
        }

        if (!empty($filters['destination']) && $filters['destination'] !== 'all') {
            $where[] = 'p.destination_slug = ?';
            $params[] = $filters['destination'];
        }

        if (!empty($filters['subtype']) && $filters['subtype'] !== 'all') {
            $where[] = 'LOWER(p.subtype) = LOWER(?)';
            $params[] = $filters['subtype'];
        }

        if (!empty($filters['min_price'])) {
            $where[] = 'p.price >= ?';
            $params[] = (int)$filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where[] = 'p.price <= ?';
            $params[] = (int)$filters['max_price'];
        }

        if (!empty($filters['min_capacity'])) {
            $where[] = 'p.capacity >= ?';
            $params[] = (int)$filters['min_capacity'];
        }

        if (!empty($filters['q'])) {
            $where[] = '(p.title LIKE ? OR p.subtitle LIKE ? OR p.location LIKE ? OR p.subtype LIKE ?)';
            $q = '%' . trim($filters['q']) . '%';
            array_push($params, $q, $q, $q, $q);
        }

        if (isset($filters['sw_lat'], $filters['sw_lng'], $filters['ne_lat'], $filters['ne_lng'])) {
            $where[] = 'p.lat BETWEEN ? AND ? AND p.lng BETWEEN ? AND ?';
            array_push(
                $params,
                (float)$filters['sw_lat'],
                (float)$filters['ne_lat'],
                (float)$filters['sw_lng'],
                (float)$filters['ne_lng']
            );
        }

        $orderBy = 'p.featured DESC, p.price DESC';
        if (!empty($filters['sort'])) {
            if ($filters['sort'] === 'price_asc') {
                $orderBy = 'p.price ASC';
            } elseif ($filters['sort'] === 'price_desc') {
                $orderBy = 'p.price DESC';
            } elseif ($filters['sort'] === 'capacity_desc') {
                $orderBy = 'p.capacity DESC, p.price DESC';
            }
        }

        $sql = "SELECT p.*, s.company_name AS supplier_company, s.verified AS supplier_verified,
                       d.name AS destination_name
                FROM products p
                LEFT JOIN suppliers s ON s.id = p.supplier_id
                LEFT JOIN destinations d ON d.id = p.destination_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY {$orderBy}";

        $rows = Database::select($sql, $params);
        return array_map([self::class, 'hydrate'], $rows);
    }

    public static function forSupplier(int $supplierId): array
    {
        $rows = Database::select(
            "SELECT p.*, s.company_name AS supplier_company, d.name AS destination_name
             FROM products p
             LEFT JOIN suppliers s ON s.id = p.supplier_id
             LEFT JOIN destinations d ON d.id = p.destination_id
             WHERE p.supplier_id = ?
             ORDER BY p.category ASC, p.price DESC",
            [$supplierId]
        );
        return array_map([self::class, 'hydrate'], $rows);
    }
}
