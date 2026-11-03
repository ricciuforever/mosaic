<?php

namespace App\Http\Middleware;

use App\Core\Auth;
use App\Core\HttpException;
use App\Core\Request;
use App\Core\Response;

class PermissionMiddleware
{
    public function handle(Request $request, string $permissionsParam): ?Response
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return Response::json(['error' => 'Autenticazione richiesta.'], 401);
            }
            return Response::redirect('/login?redirect=' . urlencode($request->path()));
        }

        $permissions = array_map('trim', explode('|', $permissionsParam));
        $user = Auth::user();

        if (!$user || !$user->hasAnyPermission($permissions)) {
            throw new HttpException(403, 'Permesso insufficiente: ' . implode(', ', $permissions));
        }

        return null;
    }
}
