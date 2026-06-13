<?php $participants = isset($participants) && is_array($participants) ? $participants : []; ?>
<?php $attendances = isset($attendances) && is_array($attendances) ? $attendances : []; ?>

<header class="admin-header">
    <h1>Asistencias</h1>
</header>

<section class="card">
    <h2>Registrar asistencia</h2>
    <form class="form-grid" method="post" action="<?= e(url('/admin/asistencias/marcar')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">

        <label>Participante
            <select name="participante_id" required>
                <option value="">Selecciona</option>
                <?php foreach ($participants as $participant): ?>
                    <option value="<?= e((string) $participant['id']) ?>">
                        <?= e($participant['primer_nombre'] . ' ' . $participant['primer_apellido']) ?> - <?= e($participant['correo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Estado
            <select name="estado" required>
                <option value="presente">Presente</option>
                <option value="ausente">Ausente</option>
            </select>
        </label>

        <button type="submit" class="btn-primary">Guardar asistencia</button>
    </form>
</section>

<section class="table-wrap">
    <h2>Historial de asistencia</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Participante</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendances as $attendance): ?>
                <tr>
                    <td><?= e((string) $attendance['id']) ?></td>
                    <td><?= e($attendance['primer_nombre'] . ' ' . $attendance['primer_apellido']) ?></td>
                    <td><?= e($attendance['estado']) ?></td>
                    <td><?= e($attendance['fecha']) ?></td>
                    <td><?= e($attendance['hora']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
