<?php

namespace App\Http\Middleware;

use App\Core\Auth;
use App\Core\HttpException;
use App\Core\Request;
use App\Core\Response;

class RoleMiddleware
{
    public function handle(Request $request, string $rolesParam): ?Response
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return Response::json(['error' => 'Autenticazione richiesta.'], 401);
            }
            return Response::redirect('/login?redirect=' . urlencode($request->path()));
        }

        $roles = array_map('trim', explode('|', $rolesParam));
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole($roles)) {
            throw new HttpException(403, 'Accesso riservato ai ruoli: ' . implode(', ', $roles));
        }

        return null;
    }
}
