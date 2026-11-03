<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\HttpException;
use App\Core\Request;
use App\Core\Response;
use App\Models\BookingRequest;
use App\Models\Setting;

class ClientPortalController
{
    public function portal(Request $request): Response
    {
        $user = Auth::user();
        if (!$user) {
            return Response::redirect('/login?redirect=/area-clienti');
        }

        if ($user->hasAnyRole(['Super Admin', 'Concierge Manager'])) {
            $bookings = BookingRequest::allWithDetails();
        } else {
            $bookings = BookingRequest::forUser($user->id, $user->email);
        }

        return view('client.portal', [
            'pageTitle' => 'Area Clienti VIP · MOSAIC Private Concierge',
            'user' => $user,
            'bookings' => $bookings,
            'supplierPortalMode' => Setting::get('supplier_portal_mode', 'standby'),
        ]);
    }

    public function acceptOffer(Request $request, string $id): Response
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyPermission(['bookings.accept_offer', 'bookings.manage_status'])) {
            throw new HttpException(403, 'Permesso bookings.accept_offer richiesto per confermare la proposta.');
        }

        $bookingId = (int)$id;
        $booking = BookingRequest::find($bookingId);
        if (!$booking) {
            throw new HttpException(404, 'Richiesta non trovata.');
        }

        if (
            !$user->can('bookings.manage_status')
            && !empty($booking['user_id'])
            && (int)$booking['user_id'] !== $user->id
            && strtolower((string)$booking['client_email']) !== strtolower($user->email)
        ) {
            throw new HttpException(403, 'Non sei autorizzato a modificare questo dossier.');
        }

        if ($booking['status'] === 'annullata') {
            if ($request->wantsJson()) {
                return Response::json([
                    'ok' => false,
                    'error' => 'Impossibile confermare una richiesta annullata.',
                ], 422);
            }
            return Response::redirect('/area-clienti?error=cancelled');
        }

        Database::execute(
            "UPDATE booking_requests SET status = 'confermata', updated_at = ? WHERE id = ?",
            [date('Y-m-d H:i:s'), $bookingId]
        );

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'booking_id' => $bookingId,
                'status' => 'confermata',
            ]);
        }

        return Response::redirect('/area-clienti?accepted=' . $bookingId);
    }
}
