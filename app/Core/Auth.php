<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    private static ?self $instance = null;
    private ?User $cachedUser = null;
    private bool $resolved = false;

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function reset(): void
    {
        self::$instance = new self();
    }

    private function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        if (!isset($_SESSION) || !is_array($_SESSION)) {
            $_SESSION = [];
        }
    }

    public static function user(): ?User
    {
        $inst = self::instance();
        if ($inst->resolved) {
            return $inst->cachedUser;
        }
        $inst->ensureSession();
        $userId = $_SESSION['mosaic_user_id'] ?? null;
        if ($userId) {
            $inst->cachedUser = User::find((int)$userId);
        }
        $inst->resolved = true;
        return $inst->cachedUser;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function id(): ?int
    {
        $u = self::user();
        return $u ? $u->id : null;
    }

    public static function login(User $user): void
    {
        $inst = self::instance();
        $inst->ensureSession();
        $_SESSION['mosaic_user_id'] = $user->id;
        $inst->cachedUser = $user;
        $inst->resolved = true;
    }

    public static function logout(): void
    {
        $inst = self::instance();
        $inst->ensureSession();
        unset($_SESSION['mosaic_user_id']);
        $inst->cachedUser = null;
        $inst->resolved = true;
    }
}
