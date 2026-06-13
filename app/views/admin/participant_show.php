<?php $participant = isset($participant) && is_array($participant) ? $participant : []; ?>
<?php $payments = isset($payments) && is_array($payments) ? $payments : []; ?>

<header class="admin-header">
    <h1>Detalle de Participante #<?= e((string) $participant['id']) ?></h1>
</header>

<section class="card">
    <p><strong>Nombre:</strong> <?= e($participant['primer_nombre'] . ' ' . $participant['segundo_nombre'] . ' ' . $participant['primer_apellido'] . ' ' . $participant['segundo_apellido']) ?></p>
    <p><strong>Correo:</strong> <?= e($participant['correo']) ?></p>
    <p><strong>Cedula:</strong> <?= e($participant['cedula']) ?></p>
    <p><strong>Telefono:</strong> <?= e($participant['telefono']) ?></p>
    <p><strong>Institucion:</strong> <?= e($participant['institucion']) ?></p>
    <p><strong>Ciudad/Pais:</strong> <?= e($participant['ciudad'] . ' / ' . $participant['pais']) ?></p>
    <p><strong>Entrada:</strong> <?= e($participant['tipo_entrada']) ?> ($<?= e(number_format((float) $participant['precio'], 2)) ?>)</p>
    <p><strong>Estado:</strong> <?= e($participant['estado']) ?></p>
</section>

<section class="inline-actions">
    <form method="post" action="<?= e(url('/admin/participantes/' . $participant['id'] . '/aprobar-pago')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <button type="submit" class="btn-success">Aprobar pago</button>
    </form>
    <form method="post" action="<?= e(url('/admin/participantes/' . $participant['id'] . '/rechazar-pago')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <button type="submit" class="btn-danger">Rechazar pago</button>
    </form>
    <form method="post" action="<?= e(url('/admin/participantes/' . $participant['id'] . '/confirmar-asistencia')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <button type="submit" class="btn-primary">Confirmar asistencia</button>
    </form>
</section>

<section class="table-wrap">
    <h2>Historial de pagos</h2>
    <table>
        <thead>
            <tr>
                <th>ID Pago</th>
                <th>Monto</th>
                <th>Metodo</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= e((string) $payment['id']) ?></td>
                    <td>$<?= e(number_format((float) $payment['monto'], 2)) ?></td>
                    <td><?= e($payment['metodo_pago']) ?></td>
                    <td><?= e($payment['estado']) ?></td>
                    <td><?= e($payment['fecha_pago']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
