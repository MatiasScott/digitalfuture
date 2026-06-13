<section class="login-card">
    <h1>Acceso Administrativo</h1>
    <form method="post" action="<?= e(url('/admin/login')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">

        <label>Usuario o correo
            <input type="text" name="login" required>
        </label>
        <label>Contrasena
            <input type="password" name="password" required>
        </label>

        <button type="submit" class="btn-primary">Ingresar</button>
    </form>
</section>
