<?php

declare(strict_types=1);

class Attendance extends Model
{
    public function create(array $data): bool
    {
        $sql = 'INSERT INTO asistencias (
                    participante_id, estado, fecha, hora, fecha_creacion
                ) VALUES (
                    :participante_id, :estado, :fecha, :hora, NOW()
                )';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'participante_id' => $data['participante_id'],
            'estado' => $data['estado'],
            'fecha' => $data['fecha'],
            'hora' => $data['hora'],
        ]);
    }

    public function listWithParticipants(): array
    {
        $sql = 'SELECT a.id, a.estado, a.fecha, a.hora,
                       p.id AS participante_id, p.primer_nombre, p.primer_apellido, p.correo
                FROM asistencias a
                INNER JOIN participantes p ON p.id = a.participante_id
                ORDER BY a.fecha DESC, a.hora DESC';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
