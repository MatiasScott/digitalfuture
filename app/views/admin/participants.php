<?php $participants = isset($participants) && is_array($participants) ? $participants : []; ?>

<header class="admin-header">
    <h1>Participantes</h1>
</header>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Cedula</th>
                <th>Entrada</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($participants as $participant): ?>
                <tr>
                    <td><?= e((string) $participant['id']) ?></td>
                    <td><?= e($participant['primer_nombre'] . ' ' . $participant['primer_apellido']) ?></td>
                    <td><?= e($participant['correo']) ?></td>
                    <td><?= e($participant['cedula']) ?></td>
                    <td><?= e($participant['tipo_entrada']) ?></td>
                    <td><?= e($participant['estado']) ?></td>
                    <td>
                        <a href="<?= e(url('/admin/participantes/' . $participant['id'])) ?>">Ver</a>
                        <a href="<?= e(url('/admin/participantes/' . $participant['id'] . '/editar')) ?>">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
