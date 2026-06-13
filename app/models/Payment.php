<?php

declare(strict_types=1);

class Payment extends Model
{
    public function create(array $data): int
    {
        $sql = 'INSERT INTO pagos (
                    participante_id, monto, metodo_pago, transaction_id, referencia, estado,
                    fecha_pago, fecha_creacion, fecha_actualizacion
                ) VALUES (
                    :participante_id, :monto, :metodo_pago, :transaction_id, :referencia, :estado,
                    :fecha_pago, NOW(), NOW()
                )';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'participante_id' => $data['participante_id'],
            'monto' => $data['monto'],
            'metodo_pago' => $data['metodo_pago'],
            'transaction_id' => $data['transaction_id'],
            'referencia' => $data['referencia'],
            'estado' => $data['estado'],
            'fecha_pago' => $data['fecha_pago'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function listWithParticipants(): array
    {
        $sql = 'SELECT pa.id, pa.monto, pa.metodo_pago, pa.transaction_id, pa.referencia, pa.estado, pa.fecha_pago,
                       p.primer_nombre, p.primer_apellido, p.correo
                FROM pagos pa
                INNER JOIN participantes p ON p.id = pa.participante_id
                ORDER BY pa.fecha_creacion DESC';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT pa.*, p.primer_nombre, p.primer_apellido, p.correo, cp.archivo, cp.ruta, cp.tipo_archivo
                FROM pagos pa
                INNER JOIN participantes p ON p.id = pa.participante_id
                LEFT JOIN comprobantes_pago cp ON cp.pago_id = pa.id
                WHERE pa.id = :id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findLatestByParticipant(int $participantId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM pagos WHERE participante_id = :participante_id ORDER BY fecha_creacion DESC LIMIT 1');
        $stmt->execute(['participante_id' => $participantId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findByParticipant(int $participantId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM pagos WHERE participante_id = :participante_id ORDER BY fecha_creacion DESC');
        $stmt->execute(['participante_id' => $participantId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $paymentId, string $status, int $adminUserId, ?string $note = null): bool
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('UPDATE pagos SET estado = :estado, fecha_actualizacion = NOW() WHERE id = :id');
            $stmt->execute([
                'id' => $paymentId,
                'estado' => $status,
            ]);

            $history = $this->db->prepare('INSERT INTO historial_estados_pago (
                    pago_id, estado, observacion, admin_usuario_id, fecha_creacion
                ) VALUES (
                    :pago_id, :estado, :observacion, :admin_usuario_id, NOW()
                )');

            $history->execute([
                'pago_id' => $paymentId,
                'estado' => $status,
                'observacion' => $note,
                'admin_usuario_id' => $adminUserId,
            ]);

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM pagos WHERE estado = :estado');
        $stmt->execute(['estado' => $status]);
        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function totalIncomeApproved(): float
    {
        $stmt = $this->db->prepare('SELECT COALESCE(SUM(monto), 0) AS total FROM pagos WHERE estado = :estado');
        $stmt->execute(['estado' => 'aprobado']);
        $row = $stmt->fetch();

        return (float) ($row['total'] ?? 0);
    }

    public function historyByPayment(int $paymentId): array
    {
        $sql = 'SELECT h.estado, h.observacion, h.fecha_creacion, u.nombres
                FROM historial_estados_pago h
                LEFT JOIN usuarios_admin u ON u.id = h.admin_usuario_id
                WHERE h.pago_id = :pago_id
                ORDER BY h.fecha_creacion DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pago_id' => $paymentId]);

        return $stmt->fetchAll();
    }
}
