<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Confirmación') ?></title>

    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/admin.css">
    <link rel="icon" href="<?= BASE_PATH ?>/img/logodigitalfuture.jpg" type="image/x-icon">
</head>

<body class="feedback-body">

    <div class="feedback-wrapper">
        <div class="feedback-card">

            <div class="feedback-icon">🎉</div>

            <h1>¡Inscripción Recibida!</h1>

            <span class="feedback-badge pending">Pendiente de Verificación</span>

            <p>
                <?= htmlspecialchars($mensaje ?? 'Tu solicitud ha sido recibida correctamente.') ?>
            </p>

            <p>
                Nuestro equipo validará el comprobante bancario enviado.
                Este proceso puede tardar hasta
                <span class="feedback-highlight">24 horas hábiles</span>.
            </p>

            <p>
                Se puede comunicar con el área de gestión de pagos al tlf:
                <span class="feedback-highlight">098 387 3798 Dirección de Docencia</span>
            </p>

            <a href="<?= BASE_PATH ?>/" class="feedback-action">Volver al Inicio</a>

        </div>
    </div>

</body>

</html>
