<?php

declare(strict_types=1);

class PaymentReceipt extends Model
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO comprobantes_pago (
                pago_id, archivo, ruta, tipo_archivo, fecha_creacion
            ) VALUES (
                :pago_id, :archivo, :ruta, :tipo_archivo, NOW()
            )');

        $stmt->execute([
            'pago_id' => $data['pago_id'],
            'archivo' => $data['archivo'],
            'ruta' => $data['ruta'],
            'tipo_archivo' => $data['tipo_archivo'],
        ]);

        return (int) $this->db->lastInsertId();
    }
}
