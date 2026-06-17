<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo | AgroVet</title>

    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/admin.css">
    <link rel="icon" href="<?= BASE_PATH ?>/img/05 Icono/CertfEASMesa de trabajo 1 copia 12.png" type="image/x-icon">
</head>

<body class="admin-auth-body">

    <div class="admin-auth-wrapper">

        <div class="admin-auth-card">

            <!-- Logo -->
            <div class="admin-auth-logo">
                <img src="<?= BASE_PATH ?>/img/02 Versión Compuesta/CertfEASMesa de trabajo 1.png"
                    alt="Logo AgroVet">
            </div>

            <!-- Título -->
            <h1 class="admin-auth-title">Panel Administrativo</h1>
            <p class="admin-auth-subtitle">
                Gestión de inscripciones AgroVet
            </p>

            <!-- Formulario -->
            <form action="<?= BASE_PATH ?>/admin/authenticate"
                method="POST"
                class="admin-auth-form">

                <?php if (!empty($data['error'])): ?>
                    <div class="admin-auth-error">
                        <?= htmlspecialchars($data['error']) ?>
                    </div>
                <?php endif; ?>

                <div class="admin-auth-group">
                    <label for="usuario">Usuario</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        placeholder="Ingresa tu usuario"
                        required
                        autofocus>
                </div>

                <div class="admin-auth-group">
                    <label for="password">Contraseña</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="admin-auth-btn">
                    Acceder al Panel
                </button>

            </form>

            <!-- Footer -->
            <?php require_once __DIR__ . '/../partials/admin_footer.php'; ?>

        </div>

    </div>

</body>

</html>