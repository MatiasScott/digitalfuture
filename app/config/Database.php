<?php
require_once 'db_credentials.php';

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        // La conexión se realiza en el constructor privado
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            // En un entorno de producción, esto debería ir a un log, no mostrarse al usuario
            die("Error de conexión a la BD: " . $e->getMessage());
        }
    }

    // Método estático para obtener la única instancia de la clase (Singleton)
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Método para obtener el objeto PDO de conexión
    public function getConnection()
    {
        return $this->connection;
    }

    // Evitar clonación y deserialización
    private function __clone() {}
    public function __wakeup() {}
}
