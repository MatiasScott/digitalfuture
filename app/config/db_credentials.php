<?php

if (!function_exists('loadEnvFile')) {
	/**
	 * Carga variables simples KEY=VALUE desde un archivo .env.
	 */
	function loadEnvFile($envPath)
	{
		if (!is_readable($envPath)) {
			return;
		}

		$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if ($lines === false) {
			return;
		}

		foreach ($lines as $line) {
			$line = trim($line);
			if ($line === '' || strpos($line, '#') === 0) {
				continue;
			}

			$parts = explode('=', $line, 2);
			if (count($parts) !== 2) {
				continue;
			}

			$key = trim($parts[0]);
			$value = trim($parts[1]);
			$value = trim($value, "\"'");

			if ($key === '') {
				continue;
			}

			$_ENV[$key] = $value;
			$_SERVER[$key] = $value;
			putenv($key . '=' . $value);
		}
	}
}

if (!defined('PROJECT_ROOT')) {
	define('PROJECT_ROOT', dirname(__DIR__, 2));
}

loadEnvFile(PROJECT_ROOT . '/.env');

if (!function_exists('envVar')) {
	function envVar($key, $default = null)
	{
		$value = getenv($key);
		return $value !== false ? $value : $default;
	}
}

define('DB_HOST', envVar('DB_HOST', 'localhost'));
define('DB_NAME', envVar('DB_NAME', 'congreso_db'));
define('DB_USER', envVar('DB_USER', 'root'));
define('DB_PASS', envVar('DB_PASS', ''));
define('DB_CHARSET', envVar('DB_CHARSET', 'utf8mb4'));

define('PAYPHONE_TOKEN', envVar('PAYPHONE_TOKEN', ''));
define('PAYPHONE_STORE_ID', envVar('PAYPHONE_STORE_ID', ''));
define('PAYPHONE_CURRENCY', envVar('PAYPHONE_CURRENCY', 'USD'));