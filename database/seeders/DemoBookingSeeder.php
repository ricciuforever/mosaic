<?php

namespace Database\Seeders;

use App\Core\Database;
use App\Models\Product;

class DemoBookingSeeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $vipUser = Database::selectOne("SELECT id FROM users WHERE email = 'vip@mosaic.digital' LIMIT 1");
        $vipUserId = $vipUser ? (int)$vipUser['id'] : null;

        $demoBookings = [
            [
                'reference_code' => 'MSC-2026-8410',
                'user_id' => $vipUserId,
                'client_first_name' => 'Ginevra',
                'client_last_name' => 'Borromeo',
                'client_email' => 'vip@mosaic.digital',
                'client_phone' => '+41 91 922 8810',
                'client_notes' => 'Gradito chef privato specializzato in crudi di mare e trasferimento elicottero diretto da Olbia Eccelsa in villa.',
                'ai_prompt_used' => '5 notti in Costa Smeralda per 6 ospiti con villa vista mare, yacht Riva e Rolls-Royce + elicottero VIP',
                'status' => 'offerta_inviata',
                'discount_percent' => 8,
                'concierge_notes' => 'Offerta Bespoke Black Member: incluso trasferimento in elicottero AW139 di benvenuto e 1 bottiglia Dom Pérignon P2 a bordo del Riva 88 Folgore.',
                'offer_valid_until' => '30 Giu 2026',
                'created_at' => '2026-05-10 14:20:00',
                'legs' => [
                    [
                        'destination_slug' => 'costa-smeralda',
                        'destination_name' => 'Costa Smeralda',
                        'start_date' => '18 Lug 2026',
                        'end_date' => '23 Lug 2026',
                        'days' => 5,
                        'adults' => 6,
                        'children' => 0,
                        'categories' => ['villa', 'auto', 'yacht', 'jet'],
                        'items' => [
                            ['code' => 'vl-01', 'days' => 5, 'supplier_status' => 'confermato'],
                            ['code' => 'au-01', 'days' => 5, 'supplier_status' => 'confermato'],
                            ['code' => 'yc-01', 'days' => 2, 'supplier_status' => 'confermato'],
                            ['code' => 'jt-03', 'days' => 1, 'supplier_status' => 'confermato'],
                        ],
                    ],
                ],
            ],
            [
                'reference_code' => 'MSC-2026-7902',
                'user_id' => $vipUserId,
                'client_first_name' => 'Ginevra',
                'client_last_name' => 'Borromeo',
                'client_email' => 'vip@mosaic.digital',
                'client_phone' => '+41 91 922 8810',
                'client_notes' => 'Anniversario sul Lago di Como con motoscafo Riva Aquarama sotto darsena.',
                'ai_prompt_used' => 'Weekend lungo sul Lago di Como per 4 persone con villa storica e Riva Aquarama',
                'status' => 'confermata',
                'discount_percent' => 5,
                'concierge_notes' => 'Prenotazione confermata con darsena privata riservata a Villa Serbelloni Depandance.',
                'offer_valid_until' => '15 Mag 2026',
                'created_at' => '2026-04-28 09:45:00',
                'legs' => [
                    [
                        'destination_slug' => 'lago-di-como',
                        'destination_name' => 'Lago di Como',
                        'start_date' => '05 Giu 2026',
                        'end_date' => '08 Giu 2026',
                        'days' => 3,
                        'adults' => 4,
                        'children' => 0,
                        'categories' => ['villa', 'auto', 'yacht'],
                        'items' => [
                            ['code' => 'vl-08', 'days' => 3, 'supplier_status' => 'confermato'],
                            ['code' => 'au-08', 'days' => 3, 'supplier_status' => 'confermato'],
                            ['code' => 'yc-08', 'days' => 2, 'supplier_status' => 'confermato'],
                        ],
                    ],
                ],
            ],
            [
                'reference_code' => 'MSC-2026-9104',
                'user_id' => null,
                'client_first_name' => 'Lord Julian',
                'client_last_name' => 'Kensington',
                'client_email' => 'j.kensington@mayfaircapital.co.uk',
                'client_phone' => '+44 20 7946 0921',
                'client_notes' => 'Itinerario doppia tappa Sardegna + Costiera Amalfitana con volo privato di collegamento.',
                'ai_prompt_used' => 'Tour 8 giorni tra Costa Smeralda e Costiera Amalfitana con jet privato, ville e yacht',
                'status' => 'nuova',
                'discount_percent' => 0,
                'concierge_notes' => 'In lavorazione con Smeralda Estates, Riviera Dimore e Mosaic Sky Aviation.',
                'offer_valid_until' => null,
                'created_at' => $now,
                'legs' => [
                    [
                        'destination_slug' => 'costa-smeralda',
                        'destination_name' => 'Costa Smeralda',
                        'start_date' => '01 Ago 2026',
                        'end_date' => '05 Ago 2026',
                        'days' => 4,
                        'adults' => 8,
                        'children' => 2,
                        'categories' => ['villa', 'yacht', 'jet'],
                        'items' => [
                            ['code' => 'vl-02', 'days' => 4, 'supplier_status' => 'in_attesa'],
                            ['code' => 'yc-02', 'days' => 2, 'supplier_status' => 'in_attesa'],
                            ['code' => 'jt-01', 'days' => 1, 'supplier_status' => 'confermato'],
                        ],
                    ],
                    [
                        'destination_slug' => 'costiera-amalfitana',
                        'destination_name' => 'Costiera Amalfitana',
                        'start_date' => '05 Ago 2026',
                        'end_date' => '09 Ago 2026',
                        'days' => 4,
                        'adults' => 8,
                        'children' => 2,
                        'categories' => ['villa', 'auto', 'yacht'],
                        'items' => [
                            ['code' => 'vl-05', 'days' => 4, 'supplier_status' => 'in_attesa'],
                            ['code' => 'au-05', 'days' => 4, 'supplier_status' => 'confermato'],
                            ['code' => 'yc-05', 'days' => 2, 'supplier_status' => 'in_attesa'],
                        ],
                    ],
                ],
            ],
            [
                'reference_code' => 'MSC-2026-6520',
                'user_id' => null,
                'client_first_name' => 'Maximilian',
                'client_last_name' => 'Von Berg',
                'client_email' => 'mvb@bergholding.ch',
                'client_phone' => '+41 44 580 1290',
                'client_notes' => 'Richiesta consegna Ferrari 296 GTS direttamente all\'eliporto di Villa Petra Bianca.',
                'ai_prompt_used' => null,
                'status' => 'pagata',
                'discount_percent' => 5,
                'concierge_notes' => 'Saldo ricevuto via bonifico SWIFT. Briefing comandante ed executive chef completato.',
                'offer_valid_until' => '01 Mag 2026',
                'created_at' => '2026-04-12 17:10:00',
                'legs' => [
                    [
                        'destination_slug' => 'costa-smeralda',
                        'destination_name' => 'Costa Smeralda',
                        'start_date' => '20 Giu 2026',
                        'end_date' => '25 Giu 2026',
                        'days' => 5,
                        'adults' => 4,
                        'children' => 0,
                        'categories' => ['villa', 'auto', 'yacht'],
                        'items' => [
                            ['code' => 'vl-03', 'days' => 5, 'supplier_status' => 'confermato'],
                            ['code' => 'au-02', 'days' => 5, 'supplier_status' => 'confermato'],
                            ['code' => 'yc-03', 'days' => 2, 'supplier_status' => 'confermato'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($demoBookings as $b) {
            $legs = $b['legs'];
            unset($b['legs']);

            $totalEstimated = 0;
            $resolvedLegs = [];

            foreach ($legs as $lIdx => $leg) {
                $resolvedItems = [];
                foreach ($leg['items'] as $it) {
                    $prod = Product::findByCode($it['code']);
                    if ($prod) {
                        $sub = $prod['price'] * $it['days'];
                        $totalEstimated += $sub;
                        $resolvedItems[] = [
                            'product' => $prod,
                            'days' => $it['days'],
                            'subtotal' => $sub,
                            'supplier_status' => $it['supplier_status'],
                        ];
                    }
                }
                $leg['resolved_items'] = $resolvedItems;
                $resolvedLegs[$lIdx] = $leg;
            }

            $discount = (int)$b['discount_percent'];
            $customOffer = $discount > 0 ? (int)round($totalEstimated * (100 - $discount) / 100) : null;

            $bookingId = Database::insert('booking_requests', [
                'reference_code' => $b['reference_code'],
                'user_id' => $b['user_id'],
                'client_first_name' => $b['client_first_name'],
                'client_last_name' => $b['client_last_name'],
                'client_email' => $b['client_email'],
                'client_phone' => $b['client_phone'],
                'client_notes' => $b['client_notes'],
                'ai_prompt_used' => $b['ai_prompt_used'],
                'status' => $b['status'],
                'total_estimated' => $totalEstimated,
                'custom_total_offer' => $customOffer,
                'discount_percent' => $discount,
                'concierge_notes' => $b['concierge_notes'],
                'offer_valid_until' => $b['offer_valid_until'],
                'created_at' => $b['created_at'],
                'updated_at' => $now,
            ]);

            foreach ($resolvedLegs as $idx => $leg) {
                $legId = Database::insert('booking_legs', [
                    'booking_request_id' => $bookingId,
                    'leg_index' => $idx + 1,
                    'destination_slug' => $leg['destination_slug'],
                    'destination_name' => $leg['destination_name'],
                    'start_date' => $leg['start_date'],
                    'end_date' => $leg['end_date'],
                    'days' => $leg['days'],
                    'adults' => $leg['adults'],
                    'children' => $leg['children'],
                    'categories_json' => json_encode($leg['categories']),
                    'created_at' => $b['created_at'],
                    'updated_at' => $now,
                ]);

                foreach ($leg['resolved_items'] as $rit) {
                    $p = $rit['product'];
                    Database::insert('booking_items', [
                        'booking_request_id' => $bookingId,
                        'booking_leg_id' => $legId,
                        'product_id' => $p['id'],
                        'supplier_id' => $p['supplier_id'] ?: null,
                        'category' => $p['category'],
                        'product_title' => $p['title'],
                        'product_subtitle' => $p['subtitle'],
                        'product_location' => $p['location'],
                        'product_image' => $p['images'][0] ?? null,
                        'unit_price' => $p['price'],
                        'unit_label' => $p['unit'],
                        'quantity_days' => $rit['days'],
                        'subtotal' => $rit['subtotal'],
                        'supplier_status' => $rit['supplier_status'],
                        'created_at' => $b['created_at'],
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
