<?php $payment = isset($payment) && is_array($payment) ? $payment : []; ?>
<?php $history = isset($history) && is_array($history) ? $history : []; ?>

<header class="admin-header">
    <h1>Detalle Pago #<?= e((string) $payment['id']) ?></h1>
</header>

<section class="card">
    <p><strong>Participante:</strong> <?= e($payment['primer_nombre'] . ' ' . $payment['primer_apellido']) ?></p>
    <p><strong>Correo:</strong> <?= e($payment['correo']) ?></p>
    <p><strong>Monto:</strong> $<?= e(number_format((float) $payment['monto'], 2)) ?></p>
    <p><strong>Metodo:</strong> <?= e($payment['metodo_pago']) ?></p>
    <p><strong>Estado:</strong> <?= e($payment['estado']) ?></p>
    <p><strong>Transaction ID:</strong> <?= e($payment['transaction_id']) ?></p>
    <p><strong>Referencia:</strong> <?= e($payment['referencia']) ?></p>

    <?php if (!empty($payment['ruta'])): ?>
        <p><a target="_blank" href="<?= e(url('/' . ltrim($payment['ruta'], '/'))) ?>">Ver comprobante</a></p>
    <?php endif; ?>
</section>

<section class="inline-actions">
    <form method="post" action="<?= e(url('/admin/pagos/' . $payment['id'] . '/aprobar')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <button type="submit" class="btn-success">Aprobar</button>
    </form>
    <form method="post" action="<?= e(url('/admin/pagos/' . $payment['id'] . '/rechazar')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <button type="submit" class="btn-danger">Rechazar</button>
    </form>
</section>

<section class="table-wrap">
    <h2>Historial</h2>
    <table>
        <thead>
            <tr>
                <th>Estado</th>
                <th>Observacion</th>
                <th>Admin</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($history as $item): ?>
                <tr>
                    <td><?= e($item['estado']) ?></td>
                    <td><?= e($item['observacion']) ?></td>
                    <td><?= e($item['nombres']) ?></td>
                    <td><?= e($item['fecha_creacion']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
