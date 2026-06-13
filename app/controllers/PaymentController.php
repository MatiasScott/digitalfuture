<?php

declare(strict_types=1);

class PaymentController extends Controller
{
    private array $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
    private array $allowedMimeTypes = [
        'application/pdf',
        'image/jpeg',
        'image/png',
    ];

    public function index(): void
    {
        $participantId = (int) ($_GET['participante_id'] ?? 0);
        $participant = null;

        if ($participantId > 0) {
            $participantModel = new Participant();
            $participant = $participantModel->findById($participantId);
        }

        $this->render('pagos', [
            'title' => 'Pagos del Congreso',
            'participant' => $participant,
            'styles' => ['pagos.css'],
            'scripts' => ['pagos.js'],
        ]);
    }

    public function store(): void
    {
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $participantId = (int) ($_POST['participante_id'] ?? 0);
        $method = Security::clean($_POST['metodo_pago'] ?? 'transferencia');
        $transactionId = Security::clean($_POST['transaction_id'] ?? '');
        $reference = Security::clean($_POST['referencia'] ?? '');

        $participantModel = new Participant();
        $participant = $participantModel->findById($participantId);

        if ($participant === null) {
            Security::flash('error', 'Participante no encontrado.');
            redirect('/pagos');
        }

        if (!isset($_FILES['comprobante']) || ($_FILES['comprobante']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            Security::flash('error', 'Debes cargar un comprobante valido.');
            redirect('/pagos?participante_id=' . $participantId);
        }

        $file = $_FILES['comprobante'];
        $originalName = (string) ($file['name'] ?? '');
        $tmpPath = (string) ($file['tmp_name'] ?? '');

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions, true)) {
            Security::flash('error', 'Formato no permitido. Usa PDF, JPG, JPEG o PNG.');
            redirect('/pagos?participante_id=' . $participantId);
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $finfo ? (string) finfo_file($finfo, $tmpPath) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        if (!in_array($mimeType, $this->allowedMimeTypes, true)) {
            Security::flash('error', 'El tipo MIME del archivo no es valido.');
            redirect('/pagos?participante_id=' . $participantId);
        }

        $safeName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $extension;
        $relativePath = 'uploads/comprobantes/' . $safeName;
        $absolutePath = dirname(__DIR__, 2) . '/public/' . $relativePath;

        if (!move_uploaded_file($tmpPath, $absolutePath)) {
            Security::flash('error', 'No se pudo guardar el comprobante.');
            redirect('/pagos?participante_id=' . $participantId);
        }

        $paymentModel = new Payment();
        $receiptModel = new PaymentReceipt();

        $paymentId = $paymentModel->create([
            'participante_id' => $participantId,
            'monto' => (float) $participant['precio'],
            'metodo_pago' => $method,
            'transaction_id' => $transactionId,
            'referencia' => $reference,
            'estado' => 'pendiente',
            'fecha_pago' => date('Y-m-d H:i:s'),
        ]);

        $receiptModel->create([
            'pago_id' => $paymentId,
            'archivo' => $safeName,
            'ruta' => $relativePath,
            'tipo_archivo' => $extension,
        ]);

        Security::flash('success', 'Pago registrado correctamente. Tu estado es pendiente de aprobacion.');
        redirect('/pagos?participante_id=' . $participantId);
    }

    public function createPayPhoneCharge(): void
    {
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            $this->json(['success' => false, 'message' => 'Token CSRF invalido'], 419);
            return;
        }

        $participantId = (int) ($_POST['participante_id'] ?? 0);
        $participantModel = new Participant();
        $participant = $participantModel->findById($participantId);

        if ($participant === null) {
            $this->json(['success' => false, 'message' => 'Participante no encontrado'], 404);
            return;
        }

        $service = new PayPhoneService();
        $reference = 'PART-' . $participantId . '-' . time();
        $txId = bin2hex(random_bytes(10));

        $result = $service->createCharge([
            'amount' => (float) $participant['precio'],
            'reference' => $reference,
            'clientTransactionId' => $txId,
        ]);

        if (($result['success'] ?? false) !== true) {
            $this->json([
                'success' => false,
                'message' => $result['message'] ?? 'No se pudo iniciar pago',
                'error' => $result['data'] ?? null,
            ], 400);
            return;
        }

        $paymentModel = new Payment();
        $paymentModel->create([
            'participante_id' => $participantId,
            'monto' => (float) $participant['precio'],
            'metodo_pago' => 'payphone',
            'transaction_id' => $txId,
            'referencia' => $reference,
            'estado' => 'pendiente',
            'fecha_pago' => date('Y-m-d H:i:s'),
        ]);

        $this->json([
            'success' => true,
            'message' => 'Pago PayPhone inicializado',
            'data' => $result['data'],
        ]);
    }
}
