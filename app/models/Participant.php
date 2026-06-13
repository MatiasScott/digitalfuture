<?php

declare(strict_types=1);

class Participant extends Model
{
    public function create(array $data): int
    {
        $sql = 'INSERT INTO participantes (
                    primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                    correo, cedula, telefono, institucion, ciudad, pais,
                    tipo_entrada_id, estado, fecha_creacion, fecha_actualizacion
                ) VALUES (
                    :primer_nombre, :segundo_nombre, :primer_apellido, :segundo_apellido,
                    :correo, :cedula, :telefono, :institucion, :ciudad, :pais,
                    :tipo_entrada_id, :estado, NOW(), NOW()
                )';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'primer_nombre' => $data['primer_nombre'],
            'segundo_nombre' => $data['segundo_nombre'],
            'primer_apellido' => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'correo' => $data['correo'],
            'cedula' => $data['cedula'],
            'telefono' => $data['telefono'],
            'institucion' => $data['institucion'],
            'ciudad' => $data['ciudad'],
            'pais' => $data['pais'],
            'tipo_entrada_id' => $data['tipo_entrada_id'],
            'estado' => 'registrado',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function all(): array
    {
        $sql = 'SELECT p.*, te.nombre AS tipo_entrada, te.precio
                FROM participantes p
                INNER JOIN tipos_entrada te ON te.id = p.tipo_entrada_id
                ORDER BY p.fecha_creacion DESC';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT p.*, te.nombre AS tipo_entrada, te.precio
                FROM participantes p
                INNER JOIN tipos_entrada te ON te.id = p.tipo_entrada_id
                WHERE p.id = :id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE participantes
                SET primer_nombre = :primer_nombre,
                    segundo_nombre = :segundo_nombre,
                    primer_apellido = :primer_apellido,
                    segundo_apellido = :segundo_apellido,
                    correo = :correo,
                    cedula = :cedula,
                    telefono = :telefono,
                    institucion = :institucion,
                    ciudad = :ciudad,
                    pais = :pais,
                    tipo_entrada_id = :tipo_entrada_id,
                    fecha_actualizacion = NOW()
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'primer_nombre' => $data['primer_nombre'],
            'segundo_nombre' => $data['segundo_nombre'],
            'primer_apellido' => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'correo' => $data['correo'],
            'cedula' => $data['cedula'],
            'telefono' => $data['telefono'],
            'institucion' => $data['institucion'],
            'ciudad' => $data['ciudad'],
            'pais' => $data['pais'],
            'tipo_entrada_id' => $data['tipo_entrada_id'],
        ]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE participantes SET estado = :estado, fecha_actualizacion = NOW() WHERE id = :id');
        return $stmt->execute(['id' => $id, 'estado' => $status]);
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) AS total FROM participantes');
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function countConfirmed(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM participantes WHERE estado = :estado');
        $stmt->execute(['estado' => 'asistencia_confirmada']);
        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }
}
