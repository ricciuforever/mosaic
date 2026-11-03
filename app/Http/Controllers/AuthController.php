<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;

class AuthController
{
    public function showLogin(Request $request): Response
    {
        $demoUsers = User::allWithRoles();
        $roles = Role::all();

        return view('auth.login', [
            'pageTitle' => 'Accesso Riservato & Demo RBAC · MOSAIC',
            'demoUsers' => $demoUsers,
            'roles' => $roles,
            'redirect' => $request->input('redirect', ''),
            'error' => $request->input('error', ''),
            'supplierPortalMode' => Setting::get('supplier_portal_mode', 'standby'),
        ]);
    }

    public function login(Request $request): Response
    {
        $demoEmail = trim((string)$request->input('demo_email', ''));
        $email = $demoEmail !== '' ? $demoEmail : trim((string)$request->input('email', ''));
        $password = (string)$request->input('password', '');
        $redirect = trim((string)$request->input('redirect', ''));

        $row = Database::selectOne("SELECT * FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1", [$email]);
        if (!$row) {
            if ($request->wantsJson()) {
                return Response::json(['ok' => false, 'error' => 'Credenziali non valide.'], 401);
            }
            return Response::redirect('/login?error=invalid');
        }

        // If not using 1-click demo switch, require and verify password hash
        if ($demoEmail === '' && ($password === '' || !password_verify($password, $row['password']))) {
            if ($request->wantsJson()) {
                return Response::json(['ok' => false, 'error' => 'Credenziali non valide.'], 401);
            }
            return Response::redirect('/login?error=invalid');
        }

        $user = new User($row);
        Auth::login($user);

        if ($redirect !== '' && str_starts_with($redirect, '/')) {
            $target = $redirect;
        } elseif ($user->hasAnyRole(['Super Admin', 'Concierge Manager'])) {
            $target = '/admin';
        } elseif ($user->hasRole('Fornitore/Partner')) {
            $target = '/portale-fornitore';
        } else {
            $target = '/area-clienti';
        }

        if ($request->wantsJson()) {
            return Response::json([
                'ok' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                ],
                'redirect_url' => $target,
            ]);
        }

        return Response::redirect($target);
    }

    public function logout(Request $request): Response
    {
        Auth::logout();
        if ($request->wantsJson()) {
            return Response::json(['ok' => true]);
        }
        return Response::redirect('/');
    }
}
