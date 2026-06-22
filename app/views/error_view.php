<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Error') ?></title>

    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/admin.css">
    <link rel="icon" href="<?= BASE_PATH ?>/img/logodigitalfuture.jpg" type="image/x-icon">
</head>

<body class="feedback-body">

    <div class="feedback-wrapper">
        <div class="feedback-card error">

            <div class="feedback-icon">⚠️</div>

            <h1>Algo salió mal</h1>

            <span class="feedback-badge error">Error de Proceso</span>

            <p>
                Ocurrió un inconveniente al procesar tu solicitud.
            </p>

            <div class="feedback-message-box">
                <strong>Detalle:</strong><br>
                <?= htmlspecialchars($mensaje ?? 'Ha ocurrido un error inesperado.') ?>
            </div>

            <p>
                Por favor, verifica los datos ingresados e inténtalo nuevamente.
            </p>

            <a href="<?= BASE_PATH ?>/" class="feedback-action">Volver al Formulario</a>

        </div>
    </div>

</body>

</html>
