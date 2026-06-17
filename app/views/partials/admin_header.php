<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Gestion Digital Future</title>

    <!-- CSS ADMIN -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/admin.css">

    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_PATH ?>/img/logodigitalfuture.jpg" type="image/x-icon">
</head>

<body>
    <header class="admin-header">
        <div class="admin-header-inner">

            <!-- LOGO -->
                <a href="<?= BASE_URL ?>/admin/dashboard" class="admin-logo">
                    <img src="<?= BASE_PATH ?>/img/logodigitalfuture.jpg"
                    alt="Logo Digital Future"
                        width="180">
            </a>

            <!-- NAV -->
            <nav class="admin-nav">
                <span class="admin-user">Administrador</span>

                <a href="<?= BASE_URL ?>/admin/logout"
                    class="btn btn-secondary btn-small"
                    onclick="return confirm('¿Desea cerrar sesión?');">
                    Cerrar Sesión
                </a>
            </nav>

        </div>
    </header>