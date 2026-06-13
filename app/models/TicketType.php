<?php

declare(strict_types=1);

class TicketType extends Model
{
    public function allActive(): array
    {
        $stmt = $this->db->prepare('SELECT id, nombre, precio, descripcion FROM tipos_entrada WHERE estado = :estado ORDER BY nombre ASC');
        $stmt->execute(['estado' => 'activo']);

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tipos_entrada WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
