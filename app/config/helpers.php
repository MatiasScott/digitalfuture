<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function base_url(): string
{
    $base = Env::get('APP_URL', '');
    return rtrim((string) $base, '/');
}

function url(string $path = ''): string
{
    if ($path === '') {
        return base_url();
    }

    if ($path[0] !== '/') {
        $path = '/' . $path;
    }

    return base_url() . $path;
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function old(string $key, string $default = ''): string
{
    return $_SESSION['_old'][$key] ?? $default;
}

function withOld(array $values): void
{
    $_SESSION['_old'] = $values;
}

function clearOld(): void
{
    unset($_SESSION['_old']);
}
