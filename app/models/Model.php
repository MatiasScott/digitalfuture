<?php

// Importar la clase de conexión a la base de datos
require_once __DIR__ . '/../config/Database.php';

class Model
{

    protected $db; // Objeto PDO de la conexión
    protected $table; // Nombre de la tabla principal del modelo

    public function __construct($table = null)
    {
        // Obtener la instancia única de la conexión a la BD
        $this->db = Database::getInstance()->getConnection();

        // El nombre de la tabla puede ser definido por modelos hijos
        $this->table = $table;
    }

    /**
     * Obtiene todos los registros de la tabla definida.
     * @return array
     */
    public function findAll()
    {
        if (!$this->table) {
            return [];
        }
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un registro por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        if (!$this->table) {
            return null;
        }
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aquí se añadirían métodos como save(), update(), delete(), etc., si fueran genéricos.
}
