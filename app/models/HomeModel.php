<?php

require_once 'Model.php';

class HomeModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    public function commit()
    {
        return $this->db->commit();
    }

    public function rollBack()
    {
        if ($this->db->inTransaction()) {
            return $this->db->rollBack();
        }
        return false;
    }

    public function inTransaction()
    {
        return $this->db->inTransaction();
    }

    public function getTicketTypeByName($nombre)
    {
        $sql = "SELECT id, nombre, precio, estado
                FROM tipos_entrada
                WHERE nombre = :nombre
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createParticipant(array $data)
    {
        $sql = "INSERT INTO participantes (
                    primer_nombre,
                    segundo_nombre,
                    primer_apellido,
                    segundo_apellido,
                    correo,
                    cedula,
                    telefono,
                    institucion,
                    ciudad,
                    pais,
                    tipo_entrada_id,
                    estado
                ) VALUES (
                    :primer_nombre,
                    :segundo_nombre,
                    :primer_apellido,
                    :segundo_apellido,
                    :correo,
                    :cedula,
                    :telefono,
                    :institucion,
                    :ciudad,
                    :pais,
                    :tipo_entrada_id,
                    :estado
                )";

        $stmt = $this->db->prepare($sql);

        $ok = $stmt->execute([
            ':primer_nombre' => $data['primer_nombre'],
            ':segundo_nombre' => $data['segundo_nombre'] ?? null,
            ':primer_apellido' => $data['primer_apellido'],
            ':segundo_apellido' => $data['segundo_apellido'] ?? null,
            ':correo' => $data['correo'],
            ':cedula' => $data['cedula'],
            ':telefono' => $data['telefono'],
            ':institucion' => $data['institucion'],
            ':ciudad' => $data['ciudad'],
            ':pais' => $data['pais'],
            ':tipo_entrada_id' => (int) $data['tipo_entrada_id'],
            ':estado' => $data['estado'] ?? 'registrado',
        ]);

        return $ok ? (int) $this->db->lastInsertId() : false;
    }

    public function createPayment(
        $participanteId,
        $monto,
        $metodoPago,
        $transactionId = null,
        $referencia = null,
        $estado = 'pendiente',
        $fechaPago = null
    ) {
        $sql = "INSERT INTO pagos (
                    participante_id,
                    monto,
                    metodo_pago,
                    transaction_id,
                    referencia,
                    estado,
                    fecha_pago
                ) VALUES (
                    :participante_id,
                    :monto,
                    :metodo_pago,
                    :transaction_id,
                    :referencia,
                    :estado,
                    :fecha_pago
                )";

        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            ':participante_id' => (int) $participanteId,
            ':monto' => $monto,
            ':metodo_pago' => $metodoPago,
            ':transaction_id' => $transactionId,
            ':referencia' => $referencia,
            ':estado' => $estado,
            ':fecha_pago' => $fechaPago ?: date('Y-m-d H:i:s'),
        ]);

        return $ok ? (int) $this->db->lastInsertId() : false;
    }

    public function createPaymentVoucher($pagoId, $archivo, $ruta, $tipoArchivo)
    {
        $sql = "INSERT INTO comprobantes_pago (pago_id, archivo, ruta, tipo_archivo)
                VALUES (:pago_id, :archivo, :ruta, :tipo_archivo)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pago_id' => (int) $pagoId,
            ':archivo' => $archivo,
            ':ruta' => $ruta,
            ':tipo_archivo' => $tipoArchivo,
        ]);
    }

    public function getParticipantByEmail($correo)
    {
        $sql = "SELECT id FROM participantes WHERE correo = :correo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        return $stmt->fetchColumn() ?: false;
    }

    public function updatePaymentStatusByReference($referencia, $estado, $payphoneId = null)
    {
        $sql = "UPDATE pagos
                SET estado = :estado,
                    transaction_id = COALESCE(:transaction_id, transaction_id)
                WHERE referencia = :referencia";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':estado' => $estado,
            ':transaction_id' => $payphoneId,
            ':referencia' => $referencia,
        ]);
    }

}
