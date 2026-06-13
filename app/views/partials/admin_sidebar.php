<aside class="admin-sidebar">
    <h2>Panel Admin</h2>
    <p><?= e($_SESSION['admin_user']['nombres'] ?? '') ?></p>
    <nav>
        <a href="<?= e(url('/admin/dashboard')) ?>">Dashboard</a>
        <a href="<?= e(url('/admin/participantes')) ?>">Participantes</a>
        <a href="<?= e(url('/admin/pagos')) ?>">Pagos</a>
        <a href="<?= e(url('/admin/asistencias')) ?>">Asistencias</a>
        <?php if (($_SESSION['admin_user']['rol'] ?? '') === 'super_admin'): ?>
            <a href="<?= e(url('/admin/usuarios')) ?>">Usuarios Admin</a>
        <?php endif; ?>
    </nav>
    <form method="post" action="<?= e(url('/admin/logout')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <button type="submit">Cerrar sesion</button>
    </form>
</aside>
