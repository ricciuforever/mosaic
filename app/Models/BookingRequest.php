<?php

namespace App\Models;

use App\Core\Database;

class BookingRequest
{
    public static function allWithDetails(): array
    {
        $bookings = Database::select(
            "SELECT br.*, u.name AS user_name
             FROM booking_requests br
             LEFT JOIN users u ON u.id = br.user_id
             ORDER BY br.created_at DESC, br.id DESC"
        );
        foreach ($bookings as &$b) {
            $b = self::attachChildren($b);
        }
        return $bookings;
    }

    public static function forUser(int $userId, string $email): array
    {
        $bookings = Database::select(
            "SELECT * FROM booking_requests
             WHERE user_id = ? OR LOWER(client_email) = LOWER(?)
             ORDER BY created_at DESC, id DESC",
            [$userId, $email]
        );
        foreach ($bookings as &$b) {
            $b = self::attachChildren($b);
        }
        return $bookings;
    }

    public static function find(int $id): ?array
    {
        $b = Database::selectOne("SELECT * FROM booking_requests WHERE id = ? LIMIT 1", [$id]);
        return $b ? self::attachChildren($b) : null;
    }

    public static function findByReference(string $reference): ?array
    {
        $b = Database::selectOne("SELECT * FROM booking_requests WHERE reference_code = ? LIMIT 1", [$reference]);
        return $b ? self::attachChildren($b) : null;
    }

    public static function attachChildren(array $b): array
    {
        $b['legs'] = Database::select(
            "SELECT * FROM booking_legs WHERE booking_request_id = ? ORDER BY leg_index ASC",
            [(int)$b['id']]
        );
        $b['items'] = Database::select(
            "SELECT bi.*, p.slug AS product_slug, s.company_name AS supplier_name
             FROM booking_items bi
             LEFT JOIN products p ON p.id = bi.product_id
             LEFT JOIN suppliers s ON s.id = bi.supplier_id
             WHERE bi.booking_request_id = ?
             ORDER BY bi.id ASC",
            [(int)$b['id']]
        );
        return $b;
    }

    public static function itemsForSupplier(int $supplierId): array
    {
        return Database::select(
            "SELECT bi.*, br.reference_code, br.client_first_name, br.client_last_name,
                    br.status AS booking_status, br.created_at AS booking_date,
                    bl.destination_name, bl.start_date, bl.end_date, bl.days
             FROM booking_items bi
             INNER JOIN booking_requests br ON br.id = bi.booking_request_id
             LEFT JOIN booking_legs bl ON bl.id = bi.booking_leg_id
             WHERE bi.supplier_id = ?
             ORDER BY br.created_at DESC, bi.id DESC",
            [$supplierId]
        );
    }

    public static function createFromPayload(array $payload, ?int $userId = null): ?array
    {
        $now = date('Y-m-d H:i:s');
        $ref = 'MSC-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));

        $legsInput = $payload['legs'] ?? [];
        if (empty($legsInput) && !empty($payload['items'])) {
            $legsInput = [[
                'destination_slug' => $payload['destination_slug'] ?? 'costa-smeralda',
                'destination_name' => $payload['destination_name'] ?? 'Costa Smeralda',
                'start_date' => $payload['start_date'] ?? '15 Ago 2026',
                'end_date' => $payload['end_date'] ?? '22 Ago 2026',
                'days' => (int)($payload['days'] ?? 7),
                'adults' => (int)($payload['adults'] ?? 2),
                'children' => (int)($payload['children'] ?? 0),
                'categories' => $payload['categories'] ?? ['villa', 'auto', 'yacht'],
                'items' => $payload['items'],
            ]];
        }

        $totalEstimated = 0;
        $preparedLegs = [];
        $validItemsCount = 0;

        foreach ($legsInput as $idx => $leg) {
            $days = max(1, (int)($leg['days'] ?? 5));
            $legItems = [];
            foreach (($leg['items'] ?? []) as $itemInput) {
                $product = null;
                if (!empty($itemInput['product_id'])) {
                    $product = Product::find((int)$itemInput['product_id']);
                } elseif (!empty($itemInput['code'])) {
                    $product = Product::findByCode((string)$itemInput['code']);
                } elseif (!empty($itemInput['slug'])) {
                    $product = Product::findBySlug((string)$itemInput['slug']);
                }

                // Ignora prodotti inesistenti o sospesi/disabilitati da Admin
                if ($product && !empty($product['is_available'])) {
                    $unitPrice = (int)$product['price'];
                    $qtyDays = max(1, (int)($itemInput['days'] ?? ($product['category'] === 'jet' ? 1 : $days)));
                    $subtotal = $unitPrice * $qtyDays;
                    $totalEstimated += $subtotal;
                    $validItemsCount++;
                    $legItems[] = [
                        'product_id' => $product['id'],
                        'supplier_id' => $product['supplier_id'] ?: null,
                        'category' => $product['category'],
                        'product_title' => $product['title'],
                        'product_subtitle' => $product['subtitle'],
                        'product_location' => $product['location'],
                        'product_image' => $product['images'][0] ?? null,
                        'unit_price' => $unitPrice,
                        'unit_label' => $product['unit'],
                        'quantity_days' => $qtyDays,
                        'subtotal' => $subtotal,
                    ];
                }
            }

            if (empty($legItems)) {
                continue;
            }

            $rawCats = isset($leg['categories']) && is_array($leg['categories'])
                ? array_values(array_filter($leg['categories'], fn($c) => is_string($c) && trim($c) !== ''))
                : [];
            if (empty($rawCats)) {
                $rawCats = array_values(array_unique(array_column($legItems, 'category')));
            }

            $preparedLegs[] = [
                'leg_index' => count($preparedLegs) + 1,
                'destination_slug' => $leg['destination_slug'] ?? 'costa-smeralda',
                'destination_name' => $leg['destination_name'] ?? 'Costa Smeralda',
                'start_date' => $leg['start_date'] ?? '15 Ago 2026',
                'end_date' => $leg['end_date'] ?? '20 Ago 2026',
                'days' => $days,
                'adults' => max(1, (int)($leg['adults'] ?? 2)),
                'children' => max(0, (int)($leg['children'] ?? 0)),
                'categories_json' => json_encode($rawCats),
                'items' => $legItems,
            ];
        }

        if ($validItemsCount === 0 || empty($preparedLegs)) {
            return null;
        }

        $bookingId = Database::insert('booking_requests', [
            'reference_code' => $ref,
            'user_id' => $userId,
            'client_first_name' => trim($payload['first_name'] ?? 'Ospite'),
            'client_last_name' => trim($payload['last_name'] ?? 'VIP'),
            'client_email' => trim($payload['email'] ?? 'vip@mosaic.digital'),
            'client_phone' => trim($payload['phone'] ?? '+39 02 8900 4120'),
            'client_notes' => trim($payload['notes'] ?? ''),
            'ai_prompt_used' => $payload['ai_prompt'] ?? null,
            'status' => 'nuova',
            'total_estimated' => $totalEstimated,
            'custom_total_offer' => null,
            'discount_percent' => 0,
            'concierge_notes' => null,
            'offer_valid_until' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ($preparedLegs as $pLeg) {
            $items = $pLeg['items'];
            unset($pLeg['items']);
            $pLeg['booking_request_id'] = $bookingId;
            $pLeg['created_at'] = $now;
            $pLeg['updated_at'] = $now;
            $legId = Database::insert('booking_legs', $pLeg);

            foreach ($items as $item) {
                $item['booking_request_id'] = $bookingId;
                $item['booking_leg_id'] = $legId;
                $item['supplier_status'] = 'in_attesa';
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
                Database::insert('booking_items', $item);
            }
        }

        return self::find($bookingId);
    }
}
