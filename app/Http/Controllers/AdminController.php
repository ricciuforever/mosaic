<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\HttpException;
use App\Core\Request;
use App\Core\Response;
use App\Models\BookingRequest;
use App\Models\Destination;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\User;

class AdminController
{
    public function dashboard(Request $request): Response
    {
        $user = Auth::user();
        if (!$user || !$user->can('admin.access')) {
            throw new HttpException(403, 'Permesso admin.access richiesto per accedere alla Console di Amministrazione.');
        }

        $bookings = BookingRequest::allWithDetails();
        $products = Product::all(false);
        $suppliers = Supplier::allWithCounts();
        $destinations = Destination::allActive();
        $roles = Role::all();
        $permissionsGrouped = Permission::grouped();
        $users = User::allWithRoles();
        $settings = Setting::allKeyed();

        $productsByCat = [
            'villa' => [],
            'auto' => [],
            'yacht' => [],
            'jet' => [],
        ];
        foreach ($products as $p) {
            $productsByCat[$p['category']][] = $p;
        }

        $totalRevenuePipeline = 0;
        $confirmedRevenue = 0;
        foreach ($bookings as $b) {
            $val = (int)($b['custom_total_offer'] ?: $b['total_estimated']);
            if ($b['status'] !== 'annullata') {
                $totalRevenuePipeline += $val;
            }
            if (in_array($b['status'], ['confermata', 'pagata'], true)) {
                $confirmedRevenue += $val;
            }
        }

        $kpis = [
            'bookings_total' => count($bookings),
            'bookings_new' => count(array_filter($bookings, fn($b) => $b['status'] === 'nuova')),
            'bookings_offered' => count(array_filter($bookings, fn($b) => $b['status'] === 'offerta_inviata')),
            'bookings_confirmed' => count(array_filter($bookings, fn($b) => in_array($b['status'], ['confermata', 'pagata'], true))),
            'pipeline_eur' => $totalRevenuePipeline,
            'confirmed_eur' => $confirmedRevenue,
            'products_total' => count($products),
            'suppliers_total' => count($suppliers),
        ];

        return view('admin.dashboard', [
            'pageTitle' => 'Console Admin & RBAC · MOSAIC Concierge',
            'user' => $user,
            'kpis' => $kpis,
            'bookings' => $bookings,
            'products' => $products,
            'productsByCat' => $productsByCat,
            'suppliers' => $suppliers,
            'destinations' => $destinations,
            'roles' => $roles,
            'permissionsGrouped' => $permissionsGrouped,
            'users' => $users,
            'settings' => $settings,
            'supplierPortalMode' => $settings['supplier_portal_mode'] ?? 'standby',
            'activeTab' => $request->input('tab', 'bookings'),
        ]);
    }

    public function updateBookingOffer(Request $request, string $id): Response
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyPermission(['bookings.manage_status', 'bookings.create_custom_offer'])) {
            throw new HttpException(403, 'Permesso bookings.manage_status o bookings.create_custom_offer richiesto.');
        }

        $bookingId = (int)$id;
        $existing = BookingRequest::find($bookingId);
        if (!$existing) {
            throw new HttpException(404, 'Richiesta non trovata.');
        }

        $allowedStatuses = ['nuova', 'offerta_inviata', 'confermata', 'pagata', 'annullata'];
        $statusInput = (string)$request->input('status', $existing['status']);
        $status = in_array($statusInput, $allowedStatuses, true) ? $statusInput : $existing['status'];

        $discount = max(0, min(50, (int)$request->input('discount_percent', $existing['discount_percent'])));
        $customOfferInput = $request->input('custom_total_offer');
        $hasCustomInput = ($customOfferInput !== null && $customOfferInput !== '');

        if ($discount > 0 && (!$hasCustomInput || (int)$customOfferInput === (int)$existing['total_estimated'])) {
            $customOffer = (int)round($existing['total_estimated'] * (100 - $discount) / 100);
        } elseif ($hasCustomInput) {
            $customOffer = max(0, (int)$customOfferInput);
        } else {
            $customOffer = $existing['custom_total_offer'] ? (int)$existing['custom_total_offer'] : null;
        }

        $notes = trim((string)$request->input('concierge_notes', $existing['concierge_notes'] ?? ''));
        $validUntil = trim((string)$request->input('offer_valid_until', $existing['offer_valid_until'] ?? '30 Giu 2026'));

        Database::execute(
            "UPDATE booking_requests
             SET status = ?, custom_total_offer = ?, discount_percent = ?, concierge_notes = ?, offer_valid_until = ?, updated_at = ?
             WHERE id = ?",
            [$status, $customOffer, $discount, $notes, $validUntil, date('Y-m-d H:i:s'), $bookingId]
        );

        $updated = BookingRequest::find($bookingId);

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'booking' => $updated,
                'message' => 'Offerta e stato richiesta aggiornati con successo.',
            ]);
        }

        return Response::redirect('/admin?tab=bookings&updated=' . $bookingId);
    }

    public function storeOrUpdateProduct(Request $request): Response
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyPermission(['catalog.create', 'catalog.edit'])) {
            throw new HttpException(403, 'Permesso catalog.create o catalog.edit richiesto.');
        }

        $id = (int)$request->input('id', 0);
        $existing = $id > 0 ? Product::find($id) : null;
        if ($id > 0 && !$existing) {
            throw new HttpException(404, 'Prodotto non trovato.');
        }

        $title = trim((string)$request->input('title', $existing['title'] ?? ''));
        $category = (string)$request->input('category', $existing['category'] ?? 'villa');
        $destSlug = (string)$request->input('destination_slug', $existing['destination_slug'] ?? 'costa-smeralda');
        $price = max(100, (int)$request->input('price', $existing['price'] ?? 2500));
        $capacity = max(1, (int)$request->input('capacity', $existing['capacity'] ?? 4));

        $subtypeInput = $request->input('subtype');
        $subtype = ($subtypeInput !== null && trim((string)$subtypeInput) !== '')
            ? trim((string)$subtypeInput)
            : ($existing['subtype'] ?? 'Esclusivo');

        $location = trim((string)$request->input('location', $existing['location'] ?? 'Porto Cervo, Costa Smeralda'));
        $subtitleInput = trim((string)$request->input('subtitle', ''));
        $subtitle = $subtitleInput !== ''
            ? $subtitleInput
            : ($existing['subtitle'] ?? ($subtype . ' · ' . $capacity . ' ospiti'));

        $descInput = trim((string)$request->input('description', ''));
        $description = $descInput !== ''
            ? $descInput
            : ($existing['description'] ?? 'Esperienza selezionata dal Private Concierge Mosaic.');

        $supplierId = (int)$request->input('supplier_id', $existing['supplier_id'] ?? 1);

        $badgeInput = $request->input('badge');
        $badge = $badgeInput !== null
            ? (trim((string)$badgeInput) ?: null)
            : ($existing['badge'] ?? null);

        $featuredInput = $request->input('featured');
        $featured = $featuredInput !== null
            ? ($featuredInput ? 1 : 0)
            : (!empty($existing['featured']) ? 1 : 0);

        if ($title === '') {
            return Response::json(['ok' => false, 'error' => 'Il titolo del prodotto è obbligatorio.'], 422);
        }

        $now = date('Y-m-d H:i:s');
        $dest = Destination::findBySlug($destSlug);
        $destId = $dest ? (int)$dest['id'] : (int)($existing['destination_id'] ?? 1);
        $capLabel = $capacity . ($category === 'auto' ? ' posti' : ($category === 'jet' ? ' passeggeri' : ' ospiti'));

        if ($id > 0) {
            Database::execute(
                "UPDATE products
                 SET title = ?, subtitle = ?, category = ?, destination_id = ?, destination_slug = ?, supplier_id = ?,
                     location = ?, price = ?, capacity = ?, capacity_label = ?, subtype = ?, badge = ?, featured = ?,
                     description = ?, updated_at = ?
                 WHERE id = ?",
                [$title, $subtitle, $category, $destId, $destSlug, $supplierId, $location, $price, $capacity, $capLabel, $subtype, $badge, $featured, $description, $now, $id]
            );
            $product = Product::find($id);
        } else {
            $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-')) . '-' . rand(10, 99);
            $prefix = $category === 'villa' ? 'vl' : ($category === 'auto' ? 'au' : ($category === 'yacht' ? 'yc' : 'jt'));
            $code = $prefix . '-' . rand(20, 99);
            $unit = $category === 'villa' ? 'notte' : ($category === 'jet' ? 'tratta' : 'giorno');
            $defaultImg = $category === 'jet' ? '/images/products/gulfstream-g650er-olbia/1.webp' : '/images/products/villa-smeralda-porto-cervo/1.webp';

            $newId = Database::insert('products', [
                'code' => $code,
                'slug' => $slug,
                'category' => $category,
                'destination_id' => $destId,
                'destination_slug' => $destSlug,
                'supplier_id' => $supplierId ?: 1,
                'title' => $title,
                'subtitle' => $subtitle,
                'location' => $location,
                'price' => $price,
                'unit' => $unit,
                'capacity' => $capacity,
                'capacity_label' => $capLabel,
                'featured' => $featured,
                'is_available' => 1,
                'badge' => $badge,
                'subtype' => $subtype,
                'lng' => $dest ? (float)$dest['lng'] : 9.535,
                'lat' => $dest ? (float)$dest['lat'] : 41.125,
                'map_x' => 52,
                'map_y' => 45,
                'description' => $description,
                'cancellation_policy' => 'Cancellazione gratuita fino a 14 giorni prima dell\'arrivo.',
                'images_json' => json_encode([$defaultImg, $defaultImg, $defaultImg, $defaultImg]),
                'amenities_json' => json_encode(['Servizio Concierge dedicato 24/7', 'Accesso prioritario VIP']),
                'services_included_json' => json_encode(['Accoglienza privata', 'Assicurazione All-Risk']),
                'services_excluded_json' => json_encode(['Carburante e consumi extra']),
                'rules_json' => json_encode(['Documento di identità richiesto']),
                'specs_extra_json' => json_encode(['customCreated' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $product = Product::find($newId);
        }

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'product' => $product,
                'message' => 'Offerta salvata a catalogo.',
            ]);
        }

        return Response::redirect('/admin?tab=catalog');
    }

    public function toggleProductAvailability(Request $request, string $id): Response
    {
        $user = Auth::user();
        if (!$user || !$user->can('catalog.edit')) {
            throw new HttpException(403, 'Permesso catalog.edit richiesto.');
        }

        $product = Product::find((int)$id);
        if (!$product) {
            throw new HttpException(404, 'Prodotto non trovato.');
        }

        $newState = $product['is_available'] ? 0 : 1;
        Database::execute("UPDATE products SET is_available = ?, updated_at = ? WHERE id = ?", [$newState, date('Y-m-d H:i:s'), (int)$id]);

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'id' => (int)$id,
                'is_available' => (bool)$newState,
            ]);
        }

        return Response::redirect('/admin?tab=catalog');
    }

    public function toggleSupplierPortalMode(Request $request): Response
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyPermission(['suppliers.toggle_standby', 'settings.manage'])) {
            throw new HttpException(403, 'Permesso suppliers.toggle_standby richiesto.');
        }

        $current = Setting::get('supplier_portal_mode', 'standby');
        $requested = $request->input('mode');
        $next = ($requested && in_array($requested, ['standby', 'active'], true))
            ? $requested
            : ($current === 'standby' ? 'active' : 'standby');

        Setting::set('supplier_portal_mode', $next);

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'supplier_portal_mode' => $next,
                'label' => $next === 'standby' ? 'In Standby (Accesso Beta Riservato)' : 'Operativo (Live per Partner)',
            ]);
        }

        return Response::redirect('/admin?tab=suppliers');
    }

    public function updateRolePermissions(Request $request, string $id): Response
    {
        $user = Auth::user();
        if (!$user || !$user->can('roles.manage')) {
            throw new HttpException(403, 'Solo il Super Admin con permesso roles.manage può modificare la matrice RBAC.');
        }

        $roleId = (int)$id;
        $permIds = (array)$request->input('permission_ids', []);
        Role::syncPermissions($roleId, $permIds);

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'role_id' => $roleId,
                'permissions' => Role::permissionsForRole($roleId),
            ]);
        }

        return Response::redirect('/admin?tab=rbac');
    }
}
