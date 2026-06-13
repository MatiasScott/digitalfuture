<?php $users = isset($users) && is_array($users) ? $users : []; ?>

<header class="admin-header">
    <h1>Usuarios Administrativos</h1>
</header>

<section class="card">
    <h2>Crear usuario</h2>
    <form class="form-grid" method="post" action="<?= e(url('/admin/usuarios')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">

        <label>Nombres
            <input type="text" name="nombres" required>
        </label>
        <label>Correo
            <input type="email" name="correo" required>
        </label>
        <label>Usuario
            <input type="text" name="usuario" required>
        </label>
        <label>Contrasena
            <input type="password" name="password" required>
        </label>
        <label>Rol
            <select name="rol" required>
                <option value="admin">Administrador</option>
                <option value="super_admin">Super Administrador</option>
            </select>
        </label>

        <button type="submit" class="btn-primary">Crear usuario</button>
    </form>
</section>

<section class="table-wrap">
    <h2>Listado</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombres</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= e((string) $user['id']) ?></td>
                    <td><?= e($user['nombres']) ?></td>
                    <td><?= e($user['correo']) ?></td>
                    <td><?= e($user['usuario']) ?></td>
                    <td><?= e($user['rol']) ?></td>
                    <td><?= e($user['estado']) ?></td>
                    <td>
                        <form method="post" action="<?= e(url('/admin/usuarios/' . $user['id'] . '/toggle')) ?>">
                            <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
                            <button type="submit">Activar/Desactivar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
