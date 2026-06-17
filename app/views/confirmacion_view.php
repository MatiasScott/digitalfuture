<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Confirmación') ?></title>

    <!-- Favicon (ajusta la ruta si es necesario) -->
    <link rel="icon" href="/landing_agrovet/public/img/05 Icono/CertfEASMesa de trabajo 1 copia 12.png" type="image/x-icon">

    <style>
        :root {
            --color-primary: #AACC05;
            --color-secondary: #FC6600;
            --color-tertiary: #566E3A;
            --color-background: #FFFFFF;
            --color-background-compuesto: #232323;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg,
                rgba(170, 204, 5, 0.12),
                rgba(252, 102, 0, 0.08));
            font-family: 'Inter', sans-serif;
        }

        .confirmation-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .confirmation-box {
            width: 100%;
            max-width: 620px;
            background: var(--color-background);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
            border-top: 8px solid var(--color-primary);
        }

        .confirmation-icon {
            font-size: 3.2em;
            margin-bottom: 15px;
        }

        .confirmation-box h1 {
            color: var(--color-tertiary);
            font-size: 2em;
            margin-bottom: 15px;
        }

        .confirmation-box p {
            font-size: 1.05em;
            color: #444;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .highlight {
            color: var(--color-secondary);
            font-weight: 700;
        }

        .status-badge {
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

        .confirmation-box a {
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

        .confirmation-box a:hover {
            background: var(--color-secondary);
            color: #fff;
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .confirmation-box {
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>

    <div class="confirmation-wrapper">
        <div class="confirmation-box">

            <div class="confirmation-icon">🎉</div>

            <h1>¡Inscripción Recibida!</h1>

            <span class="status-badge">PENDIENTE DE VERIFICACIÓN</span>

            <p>
                <?= htmlspecialchars($mensaje ?? 'Tu solicitud ha sido recibida correctamente.') ?>
            </p>

            <p>
                Nuestro equipo validará el comprobante bancario enviado.
                Este proceso puede tardar hasta
                <span class="highlight">24 horas hábiles</span>.
            </p>

            <p>
                Se puede comunicar con el área de gestión de pagos al tlf:
                <span class="highlight">0990472515 (Msc. Jenny Siza)</span>
            </p>

            <p>
                Recibirás un correo electrónico cuando tu estado cambie a
                <span class="highlight">APROBADO</span>.
            </p>

            <a href="<?= BASE_PATH ?>/">Volver al Inicio</a>

        </div>
    </div>

</body>

</html>
