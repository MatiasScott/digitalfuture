<?php $payments = isset($payments) && is_array($payments) ? $payments : []; ?>

<header class="admin-header">
    <h1>Pagos</h1>
</header>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Participante</th>
                <th>Correo</th>
                <th>Monto</th>
                <th>Metodo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= e((string) $payment['id']) ?></td>
                    <td><?= e($payment['primer_nombre'] . ' ' . $payment['primer_apellido']) ?></td>
                    <td><?= e($payment['correo']) ?></td>
                    <td>$<?= e(number_format((float) $payment['monto'], 2)) ?></td>
                    <td><?= e($payment['metodo_pago']) ?></td>
                    <td><?= e($payment['estado']) ?></td>
                    <td><?= e($payment['fecha_pago']) ?></td>
                    <td><a href="<?= e(url('/admin/pagos/' . $payment['id'])) ?>">Ver</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
