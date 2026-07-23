<?php

require_once __DIR__ . '/../models/HomeModel.php';
require_once __DIR__ . '/../config/db_credentials.php';

class HomeController
{
    private $homeModel;

    public function __construct()
    {
        $this->homeModel = new HomeModel();
    }

    // Método principal para la página de inicio
    public function index()
    {
        // En este punto podemos pasar datos dinámicos si es necesario
        $data = [
            'titulo' => 'Congreso Digital Future de Marketing Digital'
        ];

        // Usamos la función renderView para cargar la vista principal
        $this->renderView('index_view', $data);
    }

    public function procesarPago()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/');
            return;
        }

        $participantData = $this->buildParticipantDataFromRequest($_POST);
        $tipoEntradaNombre = trim($_POST['tipo_entrada'] ?? '');

        if (!$this->hasRequiredParticipantData($participantData) || $tipoEntradaNombre === '') {
            $mensajeError = 'Faltan datos obligatorios del participante o tipo de entrada.';
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode($mensajeError));
            return;
        }

        $ticketConfig = $this->resolveTicketConfig($tipoEntradaNombre);
        if (!$ticketConfig) {
            $mensajeError = 'Tipo de entrada no valido o inactivo.';
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode($mensajeError));
            return;
        }

        $tipoEntrada = $ticketConfig['ticketType'];
        $monto = $ticketConfig['precio'];

        $mensajeError = '';
        $comprobantePath = null;
        $comprobanteNombre = null;
        $comprobanteMime = null;

        if (!isset($_FILES['comprobante'])) {
            $mensajeError = 'Debe adjuntar el comprobante de pago.';
        } else {
            $file = $_FILES['comprobante'];
            $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

            if ($uploadError !== UPLOAD_ERR_OK) {
                $mensajeError = $this->mapUploadErrorMessage($uploadError);
            } else {
                $targetDir = rtrim(PUBLIC_PATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

                $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                $maxSize = 5 * 1024 * 1024;
                $detectedMime = $this->detectUploadedMimeType($file);

                if (!in_array($detectedMime, $allowedTypes, true)) {
                    $mensajeError = 'Tipo de archivo no permitido. Solo JPG, PNG y PDF.';
                } elseif ((int) $file['size'] > $maxSize) {
                    $mensajeError = 'El archivo es demasiado grande (máx. 5MB).';
                } else {
                    $fileExtension = strtolower((string) pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

                    if ($fileExtension === '') {
                        $extByMime = [
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',
                            'application/pdf' => 'pdf',
                        ];
                        $fileExtension = $extByMime[$detectedMime] ?? 'bin';
                    }

                    $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
                    $targetFile = $targetDir . $fileName;

                    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true)) {
                        $mensajeError = 'No se pudo preparar la carpeta de subida. Revisa permisos del servidor.';
                    } elseif (!is_uploaded_file($file['tmp_name'])) {
                        $mensajeError = 'No se detecto un archivo valido para subir.';
                    } else {
                        // Intentar normalizar permisos de carpeta en hosting Linux/Apache.
                        if (!is_writable($targetDir)) {
                            @chmod($targetDir, 0775);
                            @chmod($targetDir, 0777);
                        }

                        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                            $comprobantePath = '/uploads/' . $fileName;
                            $comprobanteNombre = $fileName;
                            $comprobanteMime = $detectedMime;
                        } else {
                            $lastError = error_get_last();
                            $lastMessage = is_array($lastError) ? ($lastError['message'] ?? 'sin detalle') : 'sin detalle';
                            error_log(
                                'Error al mover comprobante. tmp=' . ($file['tmp_name'] ?? '') .
                                ' destino=' . $targetFile .
                                ' writable=' . (is_writable($targetDir) ? 'si' : 'no') .
                                ' php_error=' . $lastMessage
                            );

                            if (!is_writable($targetDir)) {
                                $mensajeError = 'La carpeta de subida no tiene permisos de escritura.';
                            } else {
                                $mensajeError = 'Error interno al guardar el archivo. Intenta nuevamente.';
                            }
                        }
                    }
                }
            }
        }

        if (!empty($mensajeError)) {
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode($mensajeError));
            return;
        }

        try {
            $this->homeModel->beginTransaction();

            $participantData['tipo_entrada_id'] = (int) $tipoEntrada['id'];
            $participanteId = $this->homeModel->createParticipant($participantData);
            if (!$participanteId) {
                throw new Exception('Error al registrar participante.');
            }

            $referencia = 'TRANSFER_' . strtoupper(bin2hex(random_bytes(4)));
            $pagoId = $this->homeModel->createPayment(
                $participanteId,
                $monto,
                'transferencia',
                null,
                $referencia,
                'pendiente'
            );

            if (!$pagoId) {
                throw new Exception('Error al registrar el pago.');
            }

            $okComprobante = $this->homeModel->createPaymentVoucher(
                $pagoId,
                $comprobanteNombre,
                $comprobantePath,
                $comprobanteMime
            );

            if (!$okComprobante) {
                throw new Exception('Error al registrar el comprobante.');
            }

            $this->homeModel->commit();

            $mensaje = 'Inscripcion exitosa. Tu comprobante sera validado por un administrador. ID de pago: ' . $pagoId;
            header('Location: ' . BASE_URL . '/response/confirmacion?msg=' . urlencode($mensaje));
            return;
        } catch (\PDOException $e) {
            if ($this->homeModel->inTransaction()) {
                $this->homeModel->rollBack();
            }

            if ($e->getCode() == 23000) {
                $mensajeError = 'El correo o cedula ya se encuentra registrado.';
            } else {
                $mensajeError = 'Error grave en la base de datos. Código: ' . $e->getCode();
            }
        } catch (\Exception $e) {
            if ($this->homeModel->inTransaction()) {
                $this->homeModel->rollBack();
            }
            $mensajeError = 'No fue posible completar el registro: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode($mensajeError));
        return;
    }

    // ===========================================
    // FUNCIÓN DE AYUDA (Renderizado de Vistas)
    // ===========================================

    /**
     * Función helper para cargar una vista.
     * @param string $viewName Nombre del archivo de vista (sin .php).
     * @param array $data Datos a pasar a la vista.
     */
    private function renderView($viewName, $data = [])
    {
        // La variable global ROOT_PATH debe estar definida en index.php
        global $BASE_URL; // Asegurarse de tener acceso a BASE_URL si es necesario

        extract($data);

        // Buscar y requerir la vista.
        $filePath = APP_PATH . '/views/' . $viewName . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            http_response_code(500);
            echo "Error: Vista no encontrada - " . htmlspecialchars($filePath);
        }
    }
    
    public function payphone()
    {
        if (!isset($_GET['tipo_entrada'], $_GET['referencia'])) {
            die('Acceso inválido');
        }

        $participantData = $this->buildParticipantDataFromRequest($_GET);
        if (!$this->hasRequiredParticipantData($participantData)) {
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode('Faltan datos del participante para procesar el pago con tarjeta.'));
            exit;
        }

        $tipoEntradaNombre = trim($_GET['tipo_entrada']);
        $ticketConfig = $this->resolveTicketConfig($tipoEntradaNombre);
        if (!$ticketConfig) {
            die('Tipo de entrada no valido.');
        }

        $monto = (float) $ticketConfig['precio'];
        $referencia = trim($_GET['referencia']);

        if ($referencia === '') {
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode('No se pudo generar una referencia de pago valida.'));
            exit;
        }

        $clientTransactionId = uniqid('DIGITALFUTURE_' . date('Ymd_His') . '_', true);

        try {
            $existingPayment = $this->homeModel->getPaymentByReference($clientTransactionId);

            if (!$existingPayment) {
                $this->homeModel->beginTransaction();

                $participanteId = $this->homeModel->getParticipantByEmail($participantData['correo']);
                if (!$participanteId) {
                    $participantData['tipo_entrada_id'] = (int) $ticketConfig['ticketType']['id'];
                    $participanteId = $this->homeModel->createParticipant($participantData);
                }

                if (!$participanteId) {
                    throw new Exception('No se pudo registrar al participante para el pago con tarjeta.');
                }

                $pagoId = $this->homeModel->createPayment(
                    $participanteId,
                    $monto,
                    'payphone',
                    null,
                    $clientTransactionId,
                    'pendiente'
                );

                if (!$pagoId) {
                    throw new Exception('No se pudo crear el registro de pago pendiente.');
                }

                $this->homeModel->commit();
            }
        } catch (\Exception $e) {
            if ($this->homeModel->inTransaction()) {
                $this->homeModel->rollBack();
            }

            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode('No fue posible iniciar el pago con tarjeta: ' . $e->getMessage()));
            exit;
        }

        $amount = (int) round($monto * 100);

        $tax = 0;
        $amountWithoutTax = $amount;

        $GLOBALS['esPasarelaPayphone'] = true;
        $GLOBALS['clientTransactionId'] = $clientTransactionId;
        $GLOBALS['amount'] = $amount;
        $GLOBALS['amountWithoutTax'] = $amountWithoutTax;
        $GLOBALS['tax'] = $tax;
        $GLOBALS['referencia'] = $referencia;
        $GLOBALS['payphoneToken'] = PAYPHONE_TOKEN;
        $GLOBALS['payphoneStoreId'] = PAYPHONE_STORE_ID;
        $GLOBALS['payphoneCurrency'] = PAYPHONE_CURRENCY;

        if (PAYPHONE_TOKEN === '' || PAYPHONE_STORE_ID === '') {
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode('Configura PAYPHONE_TOKEN y PAYPHONE_STORE_ID en .env'));
            exit;
        }

        require_once '../app/views/partials/pasarela_pagos.php';
    }
    
    public function registrarVentaPayphone()
    {
        // 1. Limpiamos cualquier salida previa para asegurar un JSON válido
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
    
        $participantData = $this->buildParticipantDataFromRequest($_POST);
        $tipo = trim($_POST['tipo_entrada'] ?? '');
        $clientTxId = trim($_POST['clientTransactionId'] ?? '');
        $payphoneId = trim($_POST['transactionId'] ?? '');

        $ticketConfig = $this->resolveTicketConfig($tipo);

        if (!$ticketConfig) {
            echo json_encode([
                'success' => false,
                'message' => 'Tipo de entrada no valido.'
            ]);
            exit;
        }

        $ticketType = $ticketConfig['ticketType'];
        $monto = $ticketConfig['precio'];

        if (!$this->hasRequiredParticipantData($participantData) || empty($clientTxId)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Faltan datos esenciales del participante o ClientTransactionId'
            ]);
            exit;
        }
    
        try {
            $this->homeModel->beginTransaction();

            $existingPayment = $this->homeModel->getPaymentByReference($clientTxId);

            if ($existingPayment) {
                $this->homeModel->updatePaymentStatusByReference($clientTxId, 'aprobado', $payphoneId ?: null);
                $this->homeModel->commit();
                echo json_encode(['success' => true, 'id' => (int) $existingPayment['id']]);
                exit;
            }

            $participanteId = $this->homeModel->getParticipantByEmail($participantData['correo']);

            if (!$participanteId) {
                $participantData['tipo_entrada_id'] = (int) $ticketType['id'];
                $participanteId = $this->homeModel->createParticipant($participantData);
            }

            if (!$participanteId) {
                throw new Exception("No se pudo crear ni encontrar al participante.");
            }

            $pagoCreado = $this->homeModel->createPayment(
                $participanteId,
                $monto,
                'payphone',
                $payphoneId ?: null,
                $clientTxId,
                'aprobado'
            );

            if ($pagoCreado) {
                $this->homeModel->commit();
                echo json_encode(['success' => true, 'id' => $pagoCreado]);
            } else {
                throw new Exception("Error al insertar el pago en la base de datos.");
            }
    
        } catch (Exception $e) {
            if ($this->homeModel->inTransaction()) {
                $this->homeModel->rollBack();
            }
            error_log("Error en registrarVentaPayphone: " . $e->getMessage());
            
            echo json_encode([
                'success' => false, 
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    public function payphoneWebhook()
    {
        // Payphone envía JSON
        $raw = file_get_contents('php://input');
        file_put_contents(
            __DIR__ . '/payphone_webhook_log.txt',
            date('Y-m-d H:i:s') . " | " . $raw . PHP_EOL,
            FILE_APPEND
        );
    
        $data = json_decode($raw, true);
    
        if (!$data || !isset($data['transactionId'])) {
            http_response_code(400);
            echo 'Invalid payload';
            return;
        }
    
        $transactionId = $data['transactionId'];
        $clientTransactionId = $data['clientTransactionId'] ?? null;
        $status = $data['status'] ?? '';
    
        if ($status === 'Approved') {
            // 🔐 buscar transacción pendiente por clientTransactionId
            // actualizar estado a APROBADO
        }
    
        http_response_code(200);
        echo 'OK';
    }

    public function payphoneResponse()
    {
        $id = (int) ($_GET['id'] ?? $_GET['transactionId'] ?? 0);
        $clientTxId = trim($_GET['clientTransactionId'] ?? $_GET['clientTxId'] ?? '');

        if ($id <= 0 || $clientTxId === '') {
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode('Respuesta de pago incompleta. Intenta nuevamente.'));
            exit;
        }
    
        if (PAYPHONE_TOKEN === '') {
            header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode('PAYPHONE_TOKEN no configurado en .env'));
            exit;
        }
    
        $payload = json_encode([
            'id' => (int)$id,
            'clientTxId' => $clientTxId
        ]);
    
        $ch = curl_init("https://pay.payphonetodoesposible.com/api/button/V2/Confirm");
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . PAYPHONE_TOKEN,
                "Content-Type: application/json"
            ],
            CURLOPT_RETURNTRANSFER => true
        ]);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        file_put_contents(
            __DIR__ . '/DEBUG_confirm.txt',
            date('Y-m-d H:i:s') . ' | ' . $response . PHP_EOL,
            FILE_APPEND
        );
    
        $result = json_decode($response, true);
    
        if (!$result || !isset($result['statusCode'])) {
            die('Respuesta Payphone inválida');
        }
    
        if ((int) $result['statusCode'] === 3) {
            $this->homeModel->updatePaymentStatusByReference(
                $clientTxId,
                'aprobado',
                $result['transactionId']
            );

            $payment = $this->homeModel->getPaymentByReference($clientTxId);
            $paymentId = (int) ($payment['id'] ?? 0);
            $mensaje = 'Inscripcion exitosa. Tu comprobante sera validado por un administrador.';

            if ($paymentId > 0) {
                $mensaje .= ' ID de pago: ' . $paymentId;
            }

            header('Location: ' . BASE_URL . '/response/confirmacion?msg=' . urlencode($mensaje));
            exit;
        }
    
        $this->homeModel->updatePaymentStatusByReference(
            $clientTxId,
            'rechazado',
            null
        );
    
        header('Location: ' . BASE_URL . '/response/error?msg=' . urlencode('Pago rechazado o cancelado.'));
        exit;
    }

    private function buildParticipantDataFromRequest($input)
    {
        return [
            'primer_nombre' => trim($input['primer_nombre'] ?? ''),
            'segundo_nombre' => trim($input['segundo_nombre'] ?? ''),
            'primer_apellido' => trim($input['primer_apellido'] ?? ''),
            'segundo_apellido' => trim($input['segundo_apellido'] ?? ''),
            'correo' => trim($input['correo'] ?? ''),
            'cedula' => trim($input['cedula'] ?? ''),
            'telefono' => trim($input['telefono'] ?? ''),
            'institucion' => trim($input['institucion'] ?? ''),
            'ciudad' => trim($input['ciudad'] ?? ''),
            'pais' => trim($input['pais'] ?? ''),
            'estado' => 'registrado',
        ];
    }

    private function hasRequiredParticipantData($participantData)
    {
        $required = [
            'primer_nombre',
            'primer_apellido',
            'correo',
            'cedula',
            'telefono',
            'institucion',
            'ciudad',
            'pais',
        ];

        foreach ($required as $field) {
            if (empty($participantData[$field])) {
                return false;
            }
        }

        return true;
    }

    private function resolveTicketConfig($tipoEntradaSeleccionado)
    {
        $tipo = strtolower(trim($tipoEntradaSeleccionado));

        if (in_array($tipo, ['estudiante', 'academico', 'académico'])) {
            $ticketType = $this->homeModel->getFirstActiveTicketTypeByNames(['Estudiante', 'Academico', 'Académico']);
            if (!$ticketType) {
                return null;
            }
            return [
                'ticketType' => $ticketType,
                'precio' => 25.00,
            ];
        }

        if (in_array($tipo, ['publico externo', 'público externo', 'externo', 'profesional', 'vip', 'general'])) {
            $ticketType = $this->homeModel->getFirstActiveTicketTypeByNames(['Profesional', 'General', 'VIP', 'Publico Externo', 'Público Externo']);
            if (!$ticketType) {
                return null;
            }
            return [
                'ticketType' => $ticketType,
                'precio' => 50.00,
            ];
        }

        return null;
    }

    private function mapUploadErrorMessage($uploadError)
    {
        switch ($uploadError) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'El archivo excede el tamano permitido por el servidor (max. 5MB).';
            case UPLOAD_ERR_PARTIAL:
                return 'La carga del archivo fue incompleta. Intenta nuevamente.';
            case UPLOAD_ERR_NO_FILE:
                return 'Debe adjuntar el comprobante de pago.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Falta la carpeta temporal del servidor para subir archivos.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'No se pudo escribir el archivo en disco. Revisa permisos del servidor.';
            case UPLOAD_ERR_EXTENSION:
                return 'Una extension de PHP bloqueo la subida del archivo.';
            default:
                return 'Ocurrio un error al subir el archivo.';
        }
    }

    private function detectUploadedMimeType($file)
    {
        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName !== '' && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mime = finfo_file($finfo, $tmpName);
                finfo_close($finfo);
                if (is_string($mime) && $mime !== '') {
                    return $mime;
                }
            }
        }

        return (string) ($file['type'] ?? 'application/octet-stream');
    }

}
