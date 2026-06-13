<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/Env.php';
Env::load(dirname(__DIR__, 2) . '/.env');

$timezone = Env::get('APP_TIMEZONE', 'UTC');
if (is_string($timezone) && $timezone !== '') {
    date_default_timezone_set($timezone);
}

spl_autoload_register(function (string $class): void {
    $paths = [
        __DIR__,
        __DIR__ . '/../core',
        __DIR__ . '/../controllers',
        __DIR__ . '/../models',
        __DIR__ . '/../services',
    ];

    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/helpers.php';

$router = new Router();
require __DIR__ . '/routes.php';
