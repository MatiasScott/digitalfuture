<?php

declare(strict_types=1);

class AdminUser extends Model
{
    public function findByUsernameOrEmail(string $login): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios_admin WHERE (usuario = :login OR correo = :login) LIMIT 1');
        $stmt->execute(['login' => $login]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT id, nombres, correo, usuario, rol, estado, fecha_creacion FROM usuarios_admin ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO usuarios_admin (
                nombres, correo, usuario, password, rol, estado, fecha_creacion, fecha_actualizacion
            ) VALUES (
                :nombres, :correo, :usuario, :password, :rol, :estado, NOW(), NOW()
            )');

        $stmt->execute([
            'nombres' => $data['nombres'],
            'correo' => $data['correo'],
            'usuario' => $data['usuario'],
            'password' => $data['password'],
            'rol' => $data['rol'],
            'estado' => $data['estado'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function toggleStatus(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE usuarios_admin
                SET estado = CASE WHEN estado = "activo" THEN "inactivo" ELSE "activo" END,
                    fecha_actualizacion = NOW()
                WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }
}
