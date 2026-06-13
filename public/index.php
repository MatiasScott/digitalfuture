<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/config/bootstrap.php';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

if ($baseDir !== '' && $baseDir !== '/' && str_starts_with($uri, $baseDir)) {
	$uri = substr($uri, strlen($baseDir));
}

if ($uri === '' || $uri === false) {
	$uri = '/';
}

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $uri);
