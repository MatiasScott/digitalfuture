<?php
ob_start();
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
// ===========================================
// CONFIGURACIÓN DE ERRORES (DESARROLLO)
// ===========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===========================================
// DEFINICIÓN DE RUTAS BASE DEL PROYECTO
// ===========================================

// Raíz del proyecto (donde están app, public, vendor, etc.)
define('PROJECT_ROOT', dirname(__DIR__));

// Ruta a la carpeta app
define('APP_PATH', PROJECT_ROOT . '/app');

// Ruta a la carpeta public
define('PUBLIC_PATH', PROJECT_ROOT . '/public');

// ===========================================
// CONFIGURACIÓN DE URLS
// ===========================================

// Ruta base dinámica del proyecto dentro del servidor (ej: /digitalfuture/public)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$scriptDir = rtrim($scriptDir, '/');
if ($scriptDir === '') {
    // Si la app corre en la raíz del dominio, usar cadena vacía evita URLs "//ruta"
    // al concatenar BASE_URL con rutas absolutas (ej: BASE_URL . '/admin/authenticate').
    $scriptDir = '';
}
define('BASE_URL', $scriptDir);

// URL absoluta dinámica del proyecto actual.
$requestScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$requestHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_PATH', rtrim($requestScheme . '://' . $requestHost . BASE_URL, '/'));

// ===========================================
// AUTOLOADER BÁSICO (Controllers, Models, Config)
// ===========================================
spl_autoload_register(function ($class) {

    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/config/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ===========================================
// ROUTER SIMPLE
// ===========================================

// URI solicitada
$request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');

// Limpiar ruta base del proyecto usando BASE_URL real
$basePath = trim(BASE_URL, '/');
if ($basePath !== '' && strpos($request_uri, $basePath) === 0) {
    $request_uri = trim(substr($request_uri, strlen($basePath)), '/');
}

// Dividir en segmentos
$segments = explode('/', $request_uri);

// Controlador y método por defecto
$controller_name = 'Home';
$method_name = 'index';
$params = [];

// ===========================================
// LÓGICA DE RUTEO
// ===========================================

if (!empty($segments) && $segments[0] !== '') {

    // -------- ADMIN --------
    if ($segments[0] === 'admin') {

        array_shift($segments); // quitar 'admin'
        $controller_name = 'Admin';

        if (!empty($segments)) {
            $method_name = array_shift($segments);
        } else {
            $method_name = 'dashboard';
        }

        $params = $segments;
    }
    // -------- FRONT --------
    else {
        $controller_name = ucfirst(array_shift($segments));

        if (!empty($segments)) {
            $method_name = array_shift($segments);
        }

        $params = $segments;
    }
}

// ===========================================
// EJECUCIÓN DEL CONTROLADOR
// ===========================================

$controller_class = $controller_name . 'Controller';

if (class_exists($controller_class)) {

    $controller = new $controller_class;

    if (method_exists($controller, $method_name)) {
        call_user_func_array([$controller, $method_name], $params);
    } else {
        http_response_code(404);
        echo "Error 404: Método '{$method_name}' no encontrado en {$controller_class}.";
    }

} else {
    http_response_code(404);
    echo "Error 404: Controlador '{$controller_class}' no encontrado.";
}
