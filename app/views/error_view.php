<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Error') ?></title>

    <!-- Favicon (ajusta la ruta si aplica) -->
    <link rel="icon" href="<?= BASE_PATH ?>/img/logodigitalfuture.jpg" type="image/x-icon">

    <style>
        :root {
            --color-primary: #0165D9;
            --color-secondary: #E23372;
            --color-tertiary: #010C42;
            --color-accent-cyan: #00B5F4;
            --color-accent-purple: #7E30BB;
            --color-background: #FFFFFF;
            --color-background-compuesto: #010C42;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg,
                rgba(226, 51, 114, 0.10),
                rgba(0, 181, 244, 0.08));
            font-family: 'Inter', sans-serif;
        }

        .error-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-box {
            width: 100%;
            max-width: 620px;
            background: var(--color-background);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
            border-top: 8px solid var(--color-secondary);
        }

        .error-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .error-box h1 {
            color: var(--color-tertiary);
            font-size: 2em;
            margin-bottom: 10px;
        }

        .error-badge {
            display: inline-block;
            margin: 15px 0 25px;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 0.75em;
            font-weight: 700;
            letter-spacing: .5px;
            background: rgba(252, 102, 0, .15);
            color: var(--color-secondary);
        }

        .error-box p {
            font-size: 1.05em;
            color: #444;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .error-message {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            font-size: 0.95em;
            margin: 20px 0;
            word-break: break-word;
        }

        .error-box a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 26px;
            background: var(--color-primary);
            color: var(--color-background-compuesto);
            text-decoration: none;
            border-radius: 30px;
            font-weight: 700;
            transition: all .3s ease;
        }

        .error-box a:hover {
            background: var(--color-secondary);
            color: #fff;
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .error-box {
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>

    <div class="error-wrapper">
        <div class="error-box">

            <div class="error-icon">⚠️</div>

            <h1>Algo salió mal</h1>

            <span class="error-badge">ERROR DE PROCESO</span>

            <p>
                Ocurrió un inconveniente al procesar tu solicitud.
            </p>

            <div class="error-message">
                <strong>Detalle:</strong><br>
                <?= htmlspecialchars($mensaje ?? 'Ha ocurrido un error inesperado.') ?>
            </div>

            <p>
                Por favor, verifica los datos ingresados e inténtalo nuevamente.
            </p>

            <a href="<?= BASE_PATH ?>/">Volver al Formulario</a>

        </div>
    </div>

</body>

</html>
