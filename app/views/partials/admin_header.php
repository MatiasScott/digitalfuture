<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Gestión AgroVet</title>

    <!-- CSS ADMIN -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/admin.css">

    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_PATH ?>/img/05 Icono/CertfEASMesa de trabajo 1 copia 12.png" type="image/x-icon">
</head>

<body>
    <header class="admin-header">
        <div class="admin-header-inner">

            <!-- LOGO -->
            <a href="<?= BASE_PATH ?>/admin/dashboard" class="admin-logo">
                <img src="<?= BASE_PATH ?>/img/03 Versión Texto/CertfEASMesa de trabajo 1 copia 20.png"
                    alt="Logo AgroVet"
                    width="15%">
            </a>

            <!-- NAV -->
            <nav class="admin-nav">
                <span class="admin-user">Administrador</span>

                <a href="<?= BASE_PATH ?>/admin/logout"
                    class="btn btn-secondary btn-small"
                    onclick="return confirm('¿Desea cerrar sesión?');">
                    Cerrar Sesión
                </a>
            </nav>

        </div>
    </header>