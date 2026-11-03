<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\HttpException;
use App\Core\Request;
use App\Core\Response;
use App\Models\BookingRequest;
use App\Models\Destination;
use App\Models\Product;
use App\Models\Setting;
use App\Services\AiConciergeService;

class CatalogController
{
    public function home(Request $request): Response
    {
        $destinations = Destination::allActive();
        $products = Product::all(true);
        $featured = array_values(array_filter($products, fn($p) => !empty($p['featured'])));

        return view('catalog.home', [
            'pageTitle' => 'MOSAIC · Esperienze di lusso su misura in Italia',
            'destinations' => $destinations,
            'products' => $products,
            'featured' => array_slice($featured, 0, 12),
            'supplierPortalMode' => Setting::get('supplier_portal_mode', 'standby'),
        ]);
    }

    public function compose(Request $request): Response
    {
        $destinations = Destination::allActive();
        $products = Product::all(true);

        $rawCats = $request->input('cats', $request->input('cat', 'villa,auto,yacht'));
        $cats = is_array($rawCats)
            ? array_values(array_filter($rawCats))
            : array_values(array_filter(explode(',', (string)$rawCats)));
        if (empty($cats)) {
            $cats = ['villa', 'auto', 'yacht'];
        }

        $fromIso = trim((string)$request->input('from', ''));
        $toIso = trim((string)$request->input('to', ''));
        $computedDays = null;
        $computedStart = null;
        $computedEnd = null;

        $itMonths = [
            1 => 'Gen', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mag', 6 => 'Giu',
            7 => 'Lug', 8 => 'Ago', 9 => 'Set', 10 => 'Ott', 11 => 'Nov', 12 => 'Dic',
        ];

        if ($fromIso !== '' && ($tsFrom = strtotime($fromIso)) !== false) {
            $computedStart = sprintf('%02d %s %s', (int)date('d', $tsFrom), $itMonths[(int)date('n', $tsFrom)], date('Y', $tsFrom));
            if ($toIso !== '' && ($tsTo = strtotime($toIso)) !== false && $tsTo > $tsFrom) {
                $computedDays = max(1, (int)round(($tsTo - $tsFrom) / 86400));
                $computedEnd = sprintf('%02d %s %s', (int)date('d', $tsTo), $itMonths[(int)date('n', $tsTo)], date('Y', $tsTo));
            }
        }

        $days = max(1, (int)$request->input('days', $computedDays ?? 4));
        $startLabel = (string)$request->input('start', $computedStart ?? '15 Lug 2026');
        $endLabel = (string)$request->input('end', $computedEnd ?? '19 Lug 2026');

        $hasExplicitQuery = $request->query('dest') !== null
            || $request->query('cats') !== null
            || $request->query('cat') !== null
            || $request->query('from') !== null
            || $request->query('to') !== null
            || $request->query('days') !== null
            || $request->query('adults') !== null
            || $request->query('preselect') !== null;

        $initialState = [
            'dest' => $request->input('dest', 'costa-smeralda'),
            'cats' => $cats,
            'days' => $days,
            'adults' => max(1, (int)$request->input('adults', 2)),
            'children' => max(0, (int)$request->input('children', 0)),
            'start' => $startLabel,
            'end' => $endLabel,
            'from' => $fromIso,
            'to' => $toIso,
            'preselect' => array_values(array_filter(explode(',', (string)$request->input('preselect', '')))),
            'ai' => (bool)$request->input('ai', false),
            'hasExplicitQuery' => $hasExplicitQuery,
        ];

        return view('catalog.compose', [
            'pageTitle' => 'Componi la tua esperienza · MOSAIC Concierge',
            'destinations' => $destinations,
            'products' => $products,
            'initialState' => $initialState,
            'supplierPortalMode' => Setting::get('supplier_portal_mode', 'standby'),
        ]);
    }

    public function productDetail(Request $request, string $slug): Response
    {
        $product = Product::findBySlug($slug);
        if (!$product) {
            throw new HttpException(404, "Il prodotto richiesto ({$slug}) non è presente a catalogo.");
        }

        $similar = Product::similar($product, 3);
        $destinations = Destination::allActive();

        return view('catalog.product', [
            'pageTitle' => $product['title'] . ' · ' . $product['location'] . ' · MOSAIC',
            'product' => $product,
            'similar' => $similar,
            'destinations' => $destinations,
            'supplierPortalMode' => Setting::get('supplier_portal_mode', 'standby'),
        ]);
    }

    public function requestPage(Request $request): Response
    {
        $destinations = Destination::allActive();
        $products = Product::all(true);
        $ref = $request->input('ref');
        $savedBooking = $ref ? BookingRequest::findByReference((string)$ref) : null;

        return view('catalog.request', [
            'pageTitle' => 'Riepilogo e Richiesta Concierge · MOSAIC',
            'destinations' => $destinations,
            'products' => $products,
            'savedBooking' => $savedBooking,
            'user' => Auth::user(),
            'supplierPortalMode' => Setting::get('supplier_portal_mode', 'standby'),
        ]);
    }

    public function submitRequest(Request $request): Response
    {
        $payload = $request->all();
        if (isset($payload['legs_json']) && is_string($payload['legs_json'])) {
            $decoded = json_decode($payload['legs_json'], true);
            if (is_array($decoded)) {
                $payload['legs'] = $decoded;
            }
        }

        $firstName = trim((string)($payload['first_name'] ?? ''));
        $email = trim((string)($payload['email'] ?? ''));

        if ($firstName === '' || $email === '') {
            if ($request->wantsJson()) {
                return Response::json([
                    'ok' => false,
                    'error' => 'Inserisci almeno nome e indirizzo email per inviare la richiesta al Private Concierge.',
                ], 422);
            }
            return Response::redirect('/richiesta?error=validation');
        }

        $booking = BookingRequest::createFromPayload($payload, Auth::id());
        if (!$booking || empty($booking['items'])) {
            if ($request->wantsJson()) {
                return Response::json([
                    'ok' => false,
                    'error' => 'Seleziona almeno un\'esperienza disponibile a catalogo prima di inviare la richiesta.',
                ], 422);
            }
            return Response::redirect('/richiesta?error=empty_items');
        }

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'reference_code' => $booking['reference_code'],
                'booking' => $booking,
                'redirect_url' => '/richiesta?ref=' . urlencode($booking['reference_code']),
            ], 201);
        }

        return Response::redirect('/richiesta?ref=' . urlencode($booking['reference_code']));
    }

    public function apiCatalog(Request $request): Response
    {
        $filters = [
            'category' => $request->input('category'),
            'destination' => $request->input('destination'),
            'subtype' => $request->input('subtype'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'min_capacity' => $request->input('min_capacity'),
            'q' => $request->input('q'),
            'sort' => $request->input('sort'),
            'sw_lat' => $request->input('sw_lat'),
            'sw_lng' => $request->input('sw_lng'),
            'ne_lat' => $request->input('ne_lat'),
            'ne_lng' => $request->input('ne_lng'),
        ];

        $items = Product::filter($filters);

        return Response::json([
            'ok' => true,
            'count' => count($items),
            'products' => $items,
        ]);
    }

    public function apiAiConcierge(Request $request): Response
    {
        $prompt = (string)$request->input('prompt', '');
        $context = (array)$request->input('context', []);
        $result = AiConciergeService::orchestrate($prompt, $context);
        return Response::json($result);
    }
}
