<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\HttpException;
use App\Core\Request;
use App\Core\Response;
use App\Models\BookingRequest;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Supplier;

class SupplierPortalController
{
    public function portal(Request $request): Response
    {
        $portalMode = Setting::get('supplier_portal_mode', 'standby');
        $user = Auth::user();

        $allSuppliers = Supplier::allWithCounts();
        $selectedSupplierId = (int)$request->input('supplier_id', 0);

        $activeSupplier = null;
        if ($selectedSupplierId > 0) {
            $activeSupplier = Supplier::find($selectedSupplierId);
        } elseif ($user) {
            $activeSupplier = $user->supplier();
        }
        if (!$activeSupplier && !empty($allSuppliers)) {
            $activeSupplier = $allSuppliers[0];
        }

        $supplierProducts = $activeSupplier ? Product::forSupplier((int)$activeSupplier['id']) : [];
        $supplierBookingItems = $activeSupplier ? BookingRequest::itemsForSupplier((int)$activeSupplier['id']) : [];

        $previewBypass = ($request->input('preview') === '1')
            || ($user && $user->hasAnyRole(['Super Admin', 'Concierge Manager', 'Fornitore/Partner']));

        return view('supplier.portal', [
            'pageTitle' => 'Portale Fornitore & Partner Hub · MOSAIC',
            'portalMode' => $portalMode,
            'previewBypass' => $previewBypass,
            'user' => $user,
            'activeSupplier' => $activeSupplier,
            'allSuppliers' => $allSuppliers,
            'supplierProducts' => $supplierProducts,
            'supplierBookingItems' => $supplierBookingItems,
            'supplierPortalMode' => $portalMode,
        ]);
    }

    public function updateItemStatus(Request $request, string $id): Response
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyPermission(['portal.supplier.access', 'bookings.manage_status', 'suppliers.manage'])) {
            throw new HttpException(403, 'Autorizzazione Fornitore/Partner o Admin richiesta per aggiornare la disponibilità.');
        }

        $itemId = (int)$id;
        $existingItem = Database::selectOne("SELECT * FROM booking_items WHERE id = ? LIMIT 1", [$itemId]);
        if (!$existingItem) {
            throw new HttpException(404, 'Elemento prenotazione non trovato.');
        }

        $status = (string)$request->input('supplier_status', 'confermato');
        if (!in_array($status, ['confermato', 'in_attesa', 'non_disponibile'], true)) {
            $status = 'confermato';
        }

        Database::execute(
            "UPDATE booking_items SET supplier_status = ?, updated_at = ? WHERE id = ?",
            [$status, date('Y-m-d H:i:s'), $itemId]
        );

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'item_id' => $itemId,
                'supplier_status' => $status,
            ]);
        }

        $supId = (int)($existingItem['supplier_id'] ?? 0);
        $target = '/portale-fornitore?preview=1' . ($supId > 0 ? '&supplier_id=' . $supId : '');
        return Response::redirect($target);
    }
}
