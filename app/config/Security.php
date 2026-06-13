<?php

declare(strict_types=1);

class Security
{
    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    public static function verifyCsrf(?string $token): bool
    {
        return is_string($token)
            && isset($_SESSION['_csrf_token'])
            && hash_equals($_SESSION['_csrf_token'], $token);
    }

    public static function clean(string $value): string
    {
        return trim(filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));
    }

    public static function requireAdmin(): void
    {
        if (empty($_SESSION['admin_user'])) {
            header('Location: ' . url('/admin/login'));
            exit;
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireAdmin();
        $currentRole = $_SESSION['admin_user']['rol'] ?? '';

        if (!in_array($currentRole, $roles, true)) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }
    }

    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    public static function pullFlash(): array
    {
        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);

        return is_array($messages) ? $messages : [];
    }
}
