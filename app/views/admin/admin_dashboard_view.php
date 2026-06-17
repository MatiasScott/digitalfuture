<?php
$participantes = $data['participantes'] ?? [];
$mensaje = $data['mensaje'] ?? null;
$counts = $data['counts'] ?? [];
$countsEntrada = $data['countsEntrada'] ?? [];
$estadoActual = $data['estadoActual'] ?? null;
$entradaActual = $data['entradaActual'] ?? null;

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<?php require_once __DIR__ . '/../partials/admin_header.php'; ?>

<main class="admin-main">
    <div class="container-admin">

        <h1>Panel de Gestión de Inscripciones</h1>

        <?php if ($mensaje): ?>
            <div class="alert-success"><?= e($mensaje) ?></div>
        <?php endif; ?>

        <h3 class="section-title">Filtrar por Estado de Pago</h3>
        <div class="stats-grid">
            <a href="<?= BASE_URL ?>/admin/dashboard" class="stat total <?= !$estadoActual && !$entradaActual ? 'active' : '' ?>">
                Total Registros<br><strong><?= $counts['total'] ?></strong>
            </a>

            <a href="<?= BASE_URL ?>/admin/dashboard/aprobado" class="stat approved <?= $estadoActual === 'aprobado' ? 'active' : '' ?>">
                Aprobados<br><strong><?= $counts['aprobados'] ?></strong>
            </a>

            <a href="<?= BASE_URL ?>/admin/dashboard/pendiente" class="stat pending <?= $estadoActual === 'pendiente' ? 'active' : '' ?>">
                Pendientes<br><strong><?= $counts['pendientes'] ?></strong>
            </a>

            <a href="<?= BASE_URL ?>/admin/dashboard/rechazado" class="stat rejected <?= $estadoActual === 'rechazado' ? 'active' : '' ?>">
                Rechazados<br><strong><?= $counts['rechazados'] ?></strong>
            </a>
        </div>

        <h3 class="section-title">Filtrar por Tipo de Entrada</h3>
        <div class="stats-grid grid-secondary">
            <a href="<?= BASE_URL ?>/admin/dashboardEntrada/Estudiante" class="stat info <?= $entradaActual === 'Estudiante' ? 'active' : '' ?>">
                Estudiante<br><strong><?= $countsEntrada['Estudiante'] ?? 0 ?></strong>
            </a>

            <a href="<?= BASE_URL ?>/admin/dashboardEntrada/Profesional" class="stat info <?= $entradaActual === 'Profesional' ? 'active' : '' ?>">
                Profesional<br><strong><?= $countsEntrada['Profesional'] ?? 0 ?></strong>
            </a>

            <a href="<?= BASE_URL ?>/admin/dashboardEntrada/VIP" class="stat info <?= $entradaActual === 'VIP' ? 'active' : '' ?>">
                VIP<br><strong><?= $countsEntrada['VIP'] ?? 0 ?></strong>
            </a>
        </div>

        <div class="dashboard-actions">
            <a href="<?= BASE_URL ?>/admin/exportarAprobados" class="btn btn-secondary">
                📥 Descargar Aprobados (Excel)
            </a>

            <input type="text" id="searchInput" class="search-input" placeholder="Buscar por nombre, email o ID...">
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Participante</th>
                        <th>Email</th>
                        <th>Entrada</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Comprobante</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($participantes)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No se encontraron registros con este filtro.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($participantes as $p): ?>
                            <tr>
                                <td><strong>#<?= e($p['participante_id']) ?></strong></td>
                                <td><?= e($p['participante_nombre']) ?></td>
                                <td><?= e($p['correo']) ?></td>
                                <td><span class="badge-entrada"><?= e($p['tipo_entrada']) ?></span></td>
                                <td>$<?= e($p['monto']) ?></td>

                                <td>
                                    <span class="status-pill status-<?= strtolower($p['estado']) ?>">
                                        <?= e(ucfirst($p['estado'])) ?>
                                    </span>
                                </td>

                                <td><?= date('d/m/Y', strtotime($p['fecha_registro'])) ?></td>

                                <td>
                                    <?php if (!empty($p['comprobante_ruta'])): ?>
                                        <?php if ($p['metodo_pago'] === 'payphone'): ?>
                                            <span class="badge bg-info text-dark" title="<?= e($p['transaction_id'] ?? '') ?>">Pago Digital</span>
                                        <?php else: ?>
                                            <a href="<?= BASE_PATH . e($p['comprobante_ruta']) ?>" target="_blank" class="action-btn view-btn">Ver archivo</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin archivo</span>
                                    <?php endif; ?>
                                </td>

                                <td class="actions-cell">
                                    <a href="<?= BASE_URL ?>/admin/editarEstado/<?= e($p['participante_id']) ?>" class="action-btn edit-btn" title="Cambiar Estado">
                                        ✏️
                                    </a>

                                    <a href="<?= BASE_URL ?>/admin/eliminarTransaccion/<?= e($p['pago_id']) ?>"
                                        class="action-btn delete-btn"
                                        title="Eliminar"
                                        onclick="return confirm('Estas seguro? Se borrara el pago y su comprobante asociado.');">
                                        🗑️
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<script>
    // Buscador en tiempo real
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        document.querySelectorAll("tbody tr").forEach(row => {
            // No ocultar la fila de "No hay registros"
            if (row.cells.length > 1) {
                row.style.display = row.textContent.toLowerCase().includes(value) ? "" : "none";
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>