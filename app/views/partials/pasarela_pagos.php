<?php
$status = $_GET['status'] ?? null;

if ($status === 'success') {
    exit();
} elseif ($status === 'failure') {
    exit();
}

if (!isset($GLOBALS['esPasarelaPayphone'])) {
    echo "Acceso directo a la pasarela no permitido. Inicia el pago desde el dashboard.";
    exit();
}
$clientTransactionId = $GLOBALS['clientTransactionId'];
$amount = $GLOBALS['amount'];
$amountWithoutTax = $GLOBALS['amountWithoutTax'];
$tax = $GLOBALS['tax'];
$referencia = $GLOBALS['referencia'];
$payphoneToken = $GLOBALS['payphoneToken'] ?? '';
$payphoneStoreId = $GLOBALS['payphoneStoreId'] ?? '';
$payphoneCurrency = $GLOBALS['payphoneCurrency'] ?? 'USD';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de pagos - Digital Future</title>
    <link rel="icon" type="image/png" href="<?= BASE_PATH ?>/img/logodigitalfuture.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script src="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.js"></script>
    <link href="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'digitalfuture-navy': '#010C42',
                        'digitalfuture-blue': '#0165D9',
                        'digitalfuture-cyan': '#00B5F4',
                        'digitalfuture-violet': '#5E36C9',
                        'digitalfuture-purple': '#7E30BB',
                        'digitalfuture-pink': '#E23372',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col justify-between">
    <header class="bg-digitalfuture-navy text-white text-center py-3 shadow-sm">
        <div class="container">
            <p class="lead mb-0">Plataforma de Pagos - Digital Future</p>
        </div>
    </header>

    <main class="container mx-auto p-4 flex-grow flex items-center justify-center">
        <div class="w-full max-w-lg">
            <h1 class="text-3xl font-bold text-center text-digitalfuture-blue mb-6">
                Procesando Pago
            </h1>
            <div id="pp-button" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <p class="text-center text-gray-500">El botón de Payphone debe aparecer aquí.</p>
            </div>
        </div>
    </main>

    <script>
    window.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const paymentContainer = document.getElementById('pp-button');

        function mostrarErrorPago(message) {
            paymentContainer.innerHTML = `
                <div class="rounded-xl border border-red-200 bg-red-50 p-5 text-center text-red-700">
                    <h2 class="mb-2 text-xl font-semibold">Algo salio mal</h2>
                    <p>${message}</p>
                </div>
            `;
        }

        if (!window.PPaymentButtonBox) {
            mostrarErrorPago('No se pudo cargar el modulo de PayPhone. Recarga la pagina e intenta nuevamente.');
            return;
        }
        
        const paymentBox = new PPaymentButtonBox({
            token: '<?= addslashes($payphoneToken) ?>',
            clientTransactionId: '<?= $clientTransactionId ?>',
            amount: <?= (int)$amount ?>,
            amountWithoutTax: <?= (int)$amountWithoutTax ?>,
            tax: <?= (int)$tax ?>,
            currency: "<?= addslashes($payphoneCurrency) ?>",
            storeId: "<?= addslashes($payphoneStoreId) ?>",
            reference: "<?= addslashes($referencia) ?>",
            responseUrl: "<?= BASE_PATH ?>/home/payphoneResponse",
            cancelUrl: "<?= BASE_PATH ?>/response/error?msg=Pago%20cancelado%20por%20el%20usuario",
            
            onCompleted: (model, actions) => {
                console.log("¡Pago aprobado por Payphone! Iniciando registro local...");
                
                if (model.status === 'Approved') {
                    const datosVenta = new FormData();
                    datosVenta.append('primer_nombre', urlParams.get('primer_nombre') || "");
                    datosVenta.append('segundo_nombre', urlParams.get('segundo_nombre') || "");
                    datosVenta.append('primer_apellido', urlParams.get('primer_apellido') || "");
                    datosVenta.append('segundo_apellido', urlParams.get('segundo_apellido') || "");
                    datosVenta.append('correo', urlParams.get('correo') || "");
                    datosVenta.append('cedula', urlParams.get('cedula') || "");
                    datosVenta.append('telefono', urlParams.get('telefono') || "");
                    datosVenta.append('institucion', urlParams.get('institucion') || "");
                    datosVenta.append('ciudad', urlParams.get('ciudad') || "");
                    datosVenta.append('pais', urlParams.get('pais') || "");
                    datosVenta.append('tipo_entrada', urlParams.get('tipo_entrada') || "");
                    datosVenta.append('monto', <?= $amount / 100 ?>);
                    datosVenta.append('transactionId', model.transactionId);
                    datosVenta.append('clientTransactionId', '<?= $clientTransactionId ?>');
    
                    // Forzamos el envío ANTES de cambiar de página
                    fetch('<?= BASE_URL ?>/home/registrarVentaPayphone', {
                        method: 'POST',
                        body: datosVenta
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log("Servidor respondió:", data);
                        if (data.success) {
                            // RECIÉN AQUÍ redirigimos
                            window.location.href = "<?= BASE_URL ?>/home/payphoneResponse?id=" + model.transactionId + "&clientTransactionId=<?= $clientTransactionId ?>";
                        } else {
                            alert("Error al guardar en BD: " + data.message);
                        }
                    })
                    .catch(err => {
                        console.error("Error en fetch:", err);
                        alert("Error de conexión al registrar. El pago se hizo pero no se guardó en BD.");
                    });
                }
            },
            onError: (error) => {
                console.error('PayPhone error:', error);
                const message = error && error.message
                    ? error.message
                    : 'No fue posible iniciar el cobro con PayPhone. Verifica la configuracion del comercio y vuelve a intentar.';
                mostrarErrorPago(message);
            }
        });

        try {
            paymentBox.render('pp-button');
        } catch (error) {
            console.error('Error al renderizar PayPhone:', error);
            mostrarErrorPago('No fue posible inicializar el boton de PayPhone.');
        }
    });
    </script>
    <!--<script src="/js/pasarela.js"></script>-->

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> Congreso Digital Future. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>