<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/helpers.php';

use App\Core\Application;
use App\Core\Auth;
use App\Core\Migrator;
use App\Core\Request;
use App\Models\BookingRequest;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\User;

echo "=================================================================\n";
echo " MOSAIC LUXURY CONCIERGE & RBAC · SUITE DI TEST AUTOMATIZZATA\n";
echo "=================================================================\n";

// Reset DB to clean seeded state
Migrator::migrateAndSeed(false);

$app = require __DIR__ . '/../bootstrap/app.php';
$passed = 0;
$failed = 0;

function assertTest(bool $cond, string $label, string $detail = ''): void
{
    global $passed, $failed;
    if ($cond) {
        $passed++;
        echo "  [PASS] {$label}\n";
    } else {
        $failed++;
        echo "  [FAIL] {$label}" . ($detail ? " -> {$detail}" : "") . "\n";
    }
}

function dispatchSim(Application $app, string $method, string $uri, array $post = [], array $headers = []): \App\Core\Response
{
    $query = [];
    $qStr = parse_url($uri, PHP_URL_QUERY);
    if ($qStr) {
        parse_str($qStr, $query);
    }
    $req = new Request($method, $uri, $query, $post, null, $headers);
    return $app->handle($req);
}

// 1. Catalog & Database Integrity
$allProducts = Product::all(true);
assertTest(count($allProducts) === 36, 'Catalogo completo 36 prodotti (10 Ville, 10 Auto, 10 Yacht, 6 Jet)', 'Trovati: ' . count($allProducts));

$jets = Product::filter(['category' => 'jet']);
assertTest(count($jets) === 6, 'Categoria Jet Privati & Elicotteri VIP attiva con 6 prodotti', 'Trovati: ' . count($jets));

$suppliers = Supplier::allWithCounts();
assertTest(count($suppliers) === 15, '15 Fornitori verificati accreditati a database', 'Trovati: ' . count($suppliers));

$roles = Role::all();
assertTest(count($roles) === 4, '4 Ruoli Spatie RBAC configurati (Super Admin, Concierge Manager, Fornitore/Partner, Cliente VIP)');

// 2. Public Pages & Product Detail (/prodotto/rolls-royce-cullinan)
$resHome = dispatchSim($app, 'GET', '/');
assertTest($resHome->status() === 200 && str_contains($resHome->content(), 'L\'arte di vivere l\'Italia'), 'GET / (Home Page Luxury Dark/Gold)');

$resCompose = dispatchSim($app, 'GET', '/compose?dest=costa-smeralda&cats=villa,auto,yacht,jet');
assertTest($resCompose->status() === 200 && str_contains($resCompose->content(), 'Cerca mentre muovi la mappa'), 'GET /compose (Compositore Multi-Tappa con Mappa Geografica Leaflet)');

$resComposeIso = dispatchSim($app, 'GET', '/compose?dest=costa-smeralda&from=2026-11-08&to=2026-11-15&adults=2&children=0&cats=auto');
assertTest(
    $resComposeIso->status() === 200
    && str_contains($resComposeIso->content(), '08 Nov 2026')
    && str_contains($resComposeIso->content(), '15 Nov 2026')
    && str_contains($resComposeIso->content(), '7 gg'),
    'GET /compose?dest=costa-smeralda&from=2026-11-08&to=2026-11-15&adults=2&children=0&cats=auto calcola 7 giorni (08 Nov 2026 - 15 Nov 2026)'
);

$resCullinan = dispatchSim($app, 'GET', '/prodotto/rolls-royce-cullinan');
assertTest(
    $resCullinan->status() === 200
    && str_contains($resCullinan->content(), 'Rolls-Royce Cullinan')
    && str_contains($resCullinan->content(), 'Scheda tecnica')
    && str_contains($resCullinan->content(), '571 CV')
    && str_contains($resCullinan->content(), '200 km/giorno')
    && str_contains($resCullinan->content(), '12.000')
    && str_contains($resCullinan->content(), '4,8')
    && str_contains($resCullinan->content(), '14 recensioni')
    && str_contains($resCullinan->content(), 'Età minima del conducente: 25 anni')
    && str_contains($resCullinan->content(), 'closedby="any"'),
    'GET /prodotto/rolls-royce-cullinan (Scheda Prodotto completa con 571 CV, 200 km/giorno, Cauzione €12.000, 4,8/14 recensioni e Regole di noleggio)'
);

$resJetDetail = dispatchSim($app, 'GET', '/prodotto/gulfstream-g650er-olbia');
assertTest(
    $resJetDetail->status() === 200 && str_contains($resJetDetail->content(), 'Gulfstream G650ER'),
    'GET /prodotto/gulfstream-g650er-olbia (Scheda Prodotto Categoria Jet)'
);

// Edge case: 404 on non-existent product slug
$res404 = dispatchSim($app, 'GET', '/prodotto/prodotto-inesistente-xyz');
assertTest($res404->status() === 404, 'GET /prodotto/prodotto-inesistente-xyz restituisce HTTP 404');

// 3. Map Bounding Box & Catalog API
$resApiBbox = dispatchSim($app, 'GET', '/api/catalog?category=villa&sw_lat=41.00&sw_lng=9.45&ne_lat=41.20&ne_lng=9.65', [], ['accept' => 'application/json']);
$bboxData = $resApiBbox->jsonData();
assertTest($resApiBbox->status() === 200 && !empty($bboxData['ok']) && $bboxData['count'] >= 3, 'GET /api/catalog con filtro Bounding Box geografico Costa Smeralda');

// 4. Hybrid AI Concierge Orchestrator (/api/ai/concierge) + Edge Case Empty Prompt
$resAi = dispatchSim(
    $app,
    'POST',
    '/api/ai/concierge',
    ['prompt' => '4 notti in Costiera Amalfitana tra Positano e Ravello per 4 ospiti con villa vista mare, Ferrari Spider, yacht per Capri ed elicottero VIP'],
    ['accept' => 'application/json']
);
$aiData = $resAi->jsonData();
assertTest(
    $resAi->status() === 200
    && ($aiData['intent']['destination_slug'] ?? '') === 'costiera-amalfitana'
    && (int)($aiData['intent']['days'] ?? 0) === 4
    && count($aiData['recommendations'] ?? []) === 4,
    'POST /api/ai/concierge orchestra Villa + Auto + Yacht + Elicottero in Costiera Amalfitana'
);

$resAiEmpty = dispatchSim($app, 'POST', '/api/ai/concierge', ['prompt' => '   '], ['accept' => 'application/json']);
$aiEmptyData = $resAiEmpty->jsonData();
assertTest($resAiEmpty->status() === 200 && !empty($aiEmptyData['ok']) && !empty($aiEmptyData['recommendations']), 'POST /api/ai/concierge gestisce prompt vuoto con fallback sicuro');

// 5. Booking Request Creation & Validation Edge Cases
$resInvalidBooking = dispatchSim($app, 'POST', '/richiesta', ['first_name' => '', 'email' => ''], ['accept' => 'application/json']);
assertTest($resInvalidBooking->status() === 422, 'POST /richiesta senza campi obbligatori restituisce HTTP 422');

$resEmptyItemsBooking = dispatchSim(
    $app,
    'POST',
    '/richiesta',
    [
        'first_name' => 'Test',
        'last_name' => 'Vuoto',
        'email' => 'test@vuoto.it',
        'legs_json' => json_encode([
            [
                'destination_slug' => 'costa-smeralda',
                'days' => 4,
                'items' => [['code' => 'codice-inesistente-999', 'days' => 4]],
            ],
        ]),
    ],
    ['accept' => 'application/json']
);
assertTest($resEmptyItemsBooking->status() === 422, 'POST /richiesta con soli prodotti inesistenti/non disponibili restituisce HTTP 422');

$resCreateBooking = dispatchSim(
    $app,
    'POST',
    '/richiesta',
    [
        'first_name' => 'Emanuele',
        'last_name' => 'Savoia',
        'email' => 'emanuele@savoia.it',
        'phone' => '+39 335 1020304',
        'notes' => 'Consegna Rolls-Royce Cullinan sottobordo al Gulfstream G650ER ad Olbia.',
        'legs_json' => json_encode([
            [
                'destination_slug' => 'costa-smeralda',
                'destination_name' => 'Costa Smeralda',
                'start_date' => '10 Ago 2026',
                'end_date' => '15 Ago 2026',
                'days' => 5,
                'adults' => 4,
                'children' => 0,
                'categories' => [null, null],
                'items' => [
                    ['code' => 'vl-01', 'days' => 5],
                    ['code' => 'au-08', 'days' => 5],
                    ['code' => 'yc-01', 'days' => 2],
                    ['code' => 'jt-01', 'days' => 1],
                ],
            ],
        ]),
    ],
    ['accept' => 'application/json']
);
$createdData = $resCreateBooking->jsonData();
$newBookingId = (int)($createdData['booking']['id'] ?? 0);
$createdLegCats = json_decode($createdData['booking']['legs'][0]['categories_json'] ?? '[]', true);
assertTest(
    $resCreateBooking->status() === 201
    && str_starts_with($createdData['reference_code'] ?? '', 'MSC-')
    && count($createdData['booking']['items'] ?? []) === 4
    && $createdLegCats === ['villa', 'auto', 'yacht', 'jet'],
    'POST /richiesta salva richiesta multi-categoria (Villa + Auto + Yacht + Jet) e ricostruisce categories_json valido'
);

// 6. Authentication Security & RBAC Enforcement Tests
Auth::logout();
$resEmptyPassLogin = dispatchSim(
    $app,
    'POST',
    '/login',
    ['email' => 'admin@mosaic.digital', 'password' => ''],
    ['accept' => 'application/json']
);
assertTest($resEmptyPassLogin->status() === 401, 'POST /login con password vuota senza demo_email rifiuta autenticazione (HTTP 401)');

$resGuestAdmin = dispatchSim($app, 'GET', '/admin');
assertTest($resGuestAdmin->status() === 302, 'Ospite non autenticato su GET /admin viene reindirizzato a /login (HTTP 302)');

$vipUser = User::findByEmail('vip@mosaic.digital');
Auth::login($vipUser);
$resVipAdmin = dispatchSim($app, 'GET', '/admin');
assertTest($resVipAdmin->status() === 403, 'Ruolo Cliente VIP bloccato su GET /admin con HTTP 403 Spatie RBAC');

$resVipPortal = dispatchSim($app, 'GET', '/area-clienti');
assertTest($resVipPortal->status() === 200 && str_contains($resVipPortal->content(), 'MSC-2026-8410'), 'Ruolo Cliente VIP accede a GET /area-clienti (HTTP 200)');

// Supplier Portal in Standby Mode for unauthenticated visitor vs authenticated Partner
Auth::logout();
Setting::set('supplier_portal_mode', 'standby');
$resStandbyPublic = dispatchSim($app, 'GET', '/portale-fornitore');
assertTest(
    $resStandbyPublic->status() === 200 && str_contains($resStandbyPublic->content(), 'In Standby (Accesso Beta Riservato)'),
    'GET /portale-fornitore mostra il Gate "In Standby" per i visitatori non autenticati'
);

$supplierUser = User::findByEmail('fornitore@mosaic.digital');
Auth::login($supplierUser);
$resPartnerPortal = dispatchSim($app, 'GET', '/portale-fornitore');
assertTest(
    $resPartnerPortal->status() === 200 && str_contains($resPartnerPortal->content(), 'Smeralda Estates'),
    'Ruolo Fornitore/Partner accede alla Dashboard Operativa del Portale Fornitore'
);

// Concierge Manager can create custom offers on bookings (including when custom_total_offer is pre-filled with total_estimated)
$conciergeUser = User::findByEmail('concierge@mosaic.digital');
Auth::login($conciergeUser);
$bookingBeforeOffer = BookingRequest::find($newBookingId);
$estimatedTotal = (int)($bookingBeforeOffer['total_estimated'] ?? 0);
$resConciergeOffer = dispatchSim(
    $app,
    'POST',
    "/admin/bookings/{$newBookingId}/offer",
    [
        'status' => 'offerta_inviata',
        'discount_percent' => 10,
        'custom_total_offer' => $estimatedTotal,
        'concierge_notes' => 'Sconto 10% riservato per pacchetto completo 4 categorie.',
        'offer_valid_until' => '30 Lug 2026',
    ],
    ['accept' => 'application/json']
);
$offerJson = $resConciergeOffer->jsonData();
assertTest(
    $resConciergeOffer->status() === 200
    && ($offerJson['booking']['status'] ?? '') === 'offerta_inviata'
    && (int)($offerJson['booking']['custom_total_offer'] ?? 0) === (int)round($estimatedTotal * 0.90),
    'Ruolo Concierge Manager invia Offerta Personalizzata (-10%) ricalcolando il totale anche con campo precompilato'
);

$resConciergeRbac = dispatchSim($app, 'POST', '/admin/roles/2/permissions', ['permission_ids' => [1, 2]], ['accept' => 'application/json']);
assertTest($resConciergeRbac->status() === 403, 'Ruolo Concierge Manager bloccato su modifica matrice RBAC (HTTP 403)');

// Cliente VIP accepts the custom offer; cancelled bookings cannot be accepted
Auth::login($vipUser);
$resAccept = dispatchSim($app, 'POST', "/area-clienti/bookings/{$newBookingId}/accept", [], ['accept' => 'application/json']);
$acceptJson = $resAccept->jsonData();
assertTest(
    $resAccept->status() === 200 && ($acceptJson['status'] ?? '') === 'confermata',
    'Ruolo Cliente VIP accetta l\'offerta del Concierge (stato -> confermata)'
);

// Super Admin cancels a booking -> Cliente VIP cannot re-accept a cancelled booking
$adminUser = User::findByEmail('admin@mosaic.digital');
Auth::login($adminUser);
dispatchSim($app, 'POST', "/admin/bookings/{$newBookingId}/offer", ['status' => 'annullata'], ['accept' => 'application/json']);
Auth::login($vipUser);
$resAcceptCancelled = dispatchSim($app, 'POST', "/area-clienti/bookings/{$newBookingId}/accept", [], ['accept' => 'application/json']);
assertTest(
    $resAcceptCancelled->status() === 422,
    'POST /area-clienti/bookings/{id}/accept blocca la conferma di una richiesta annullata (HTTP 422)'
);

// Super Admin accesses Admin Console, edits product preserving metadata, tests suspended product exclusion, & toggles Supplier Portal
Auth::login($adminUser);
$resAdminDash = dispatchSim($app, 'GET', '/admin');
assertTest($resAdminDash->status() === 200 && str_contains($resAdminDash->content(), 'Pannello di Controllo MOSAIC'), 'Ruolo Super Admin accede a GET /admin (HTTP 200)');

$cullinanBefore = Product::findBySlug('rolls-royce-cullinan');
$cullinanId = (int)$cullinanBefore['id'];
$resSaveProduct = dispatchSim(
    $app,
    'POST',
    '/admin/products/save',
    [
        'id' => $cullinanId,
        'title' => $cullinanBefore['title'],
        'category' => $cullinanBefore['category'],
        'destination_slug' => $cullinanBefore['destination_slug'],
        'supplier_id' => $cullinanBefore['supplier_id'],
        'location' => $cullinanBefore['location'],
        'price' => $cullinanBefore['price'],
        'capacity' => $cullinanBefore['capacity'],
        'subtitle' => $cullinanBefore['subtitle'],
        'description' => $cullinanBefore['description'],
    ],
    ['accept' => 'application/json']
);
$cullinanAfter = Product::find($cullinanId);
assertTest(
    $resSaveProduct->status() === 200
    && $cullinanAfter['badge'] === $cullinanBefore['badge']
    && $cullinanAfter['subtype'] === $cullinanBefore['subtype']
    && (int)$cullinanAfter['featured'] === (int)$cullinanBefore['featured'],
    'POST /admin/products/save su prodotto esistente preserva badge, subtype e featured'
);

// Suspend product and verify it cannot be booked
dispatchSim($app, 'POST', "/admin/products/{$cullinanId}/toggle", [], ['accept' => 'application/json']);
$resBookSuspended = dispatchSim(
    $app,
    'POST',
    '/richiesta',
    [
        'first_name' => 'Marco',
        'last_name' => 'Rossi',
        'email' => 'marco@rossi.it',
        'legs_json' => json_encode([
            [
                'destination_slug' => 'costa-smeralda',
                'days' => 3,
                'items' => [['code' => 'au-08', 'days' => 3]],
            ],
        ]),
    ],
    ['accept' => 'application/json']
);
assertTest($resBookSuspended->status() === 422, 'Prodotto sospeso da Admin (is_available=0) non è prenotabile tramite POST /richiesta (HTTP 422)');
// Re-enable product to leave clean seeded state
dispatchSim($app, 'POST', "/admin/products/{$cullinanId}/toggle", [], ['accept' => 'application/json']);

$resTogglePortal = dispatchSim($app, 'POST', '/admin/suppliers/toggle-portal', ['mode' => 'active'], ['accept' => 'application/json']);
assertTest(
    $resTogglePortal->status() === 200 && Setting::get('supplier_portal_mode') === 'active',
    'Super Admin commuta Portale Fornitore da Standby a Operativo (active)'
);
// Restore standby default and clean DB state
Setting::set('supplier_portal_mode', 'standby');
Migrator::migrateAndSeed(false);

echo "-----------------------------------------------------------------\n";
echo " RISULTATO FINALE: {$passed} test superati, {$failed} falliti.\n";
echo "=================================================================\n";

exit($failed > 0 ? 1 : 0);

