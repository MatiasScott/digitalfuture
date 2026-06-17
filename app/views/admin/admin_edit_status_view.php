<?php
$t = $transaccion ?? null;
$estados = ['pendiente', 'aprobado', 'rechazado'];

if (!$t) {
    header('Location: ' . BASE_URL . '/admin/dashboard?mensaje=' . urlencode('Error: Transacción no encontrada.'));
    exit;
}

function e($v)
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>

<?php require_once __DIR__ . '/../partials/admin_header.php'; ?>

<main class="admin-main">
    <div class="container-admin">

        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary btn-small mb-3">
            ← Volver al Dashboard
        </a>

        <div class="edit-card">

            <div class="edit-card-header">
                <h1>Editar Estado</h1>
                <span class="status-badge status-<?= strtolower($t['estado']) ?>">
                    <?= e(ucfirst($t['estado'])) ?>
                </span>
            </div>

            <div class="edit-card-body">

                <div class="info-grid">
                    <div><strong>Participante</strong><span><?= e($t['participante_nombre']) ?></span></div>
                    <div><strong>Email</strong><span><?= e($t['correo']) ?></span></div>
                    <div><strong>Entrada</strong><span><?= e($t['tipo_entrada']) ?></span></div>
                    <div><strong>Monto</strong><span>$<?= e($t['monto']) ?> USD</span></div>
                </div>

                <form action="<?= BASE_URL ?>/admin/editarEstado/<?= e($t['participante_id']) ?>" method="POST" class="edit-form">

                    <input type="hidden" name="pago_id" value="<?= e($t['pago_id']) ?>">

                    <div class="form-group">
                        <label for="estado">Nuevo Estado</label>
                        <select id="estado" name="estado" class="form-control" required>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?= $estado ?>" <?= $t['estado'] === $estado ? 'selected' : '' ?>>
                                    <?= ucfirst($estado) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        Guardar Cambios
                    </button>
                </form>

            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>