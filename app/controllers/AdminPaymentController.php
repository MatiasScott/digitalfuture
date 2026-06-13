<?php

declare(strict_types=1);

class AdminPaymentController extends Controller
{
    public function index(): void
    {
        Security::requireAdmin();

        $paymentModel = new Payment();
        $payments = $paymentModel->listWithParticipants();

        $this->render('admin/payments', [
            'title' => 'Gestion de Pagos',
            'payments' => $payments,
            'styles' => ['dashboard.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }

    public function show(string $id): void
    {
        Security::requireAdmin();

        $paymentModel = new Payment();
        $payment = $paymentModel->findById((int) $id);

        if ($payment === null) {
            http_response_code(404);
            echo 'Pago no encontrado';
            return;
        }

        $history = $paymentModel->historyByPayment((int) $id);

        $this->render('admin/payment_show', [
            'title' => 'Detalle de Pago',
            'payment' => $payment,
            'history' => $history,
            'styles' => ['dashboard.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }

    public function approve(string $id): void
    {
        $this->changeStatus((int) $id, 'aprobado', 'Pago aprobado desde modulo de pagos');
    }

    public function reject(string $id): void
    {
        $this->changeStatus((int) $id, 'rechazado', 'Pago rechazado desde modulo de pagos');
    }

    private function changeStatus(int $paymentId, string $status, string $note): void
    {
        Security::requireAdmin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $paymentModel = new Payment();
        $payment = $paymentModel->findById($paymentId);

        if ($payment === null) {
            Security::flash('error', 'Pago no encontrado.');
            redirect('/admin/pagos');
        }

        $paymentModel->updateStatus($paymentId, $status, (int) $_SESSION['admin_user']['id'], $note);

        $participantModel = new Participant();
        if ($status === 'aprobado') {
            $participantModel->updateStatus((int) $payment['participante_id'], 'pago_aprobado');
        }
        if ($status === 'rechazado') {
            $participantModel->updateStatus((int) $payment['participante_id'], 'pago_rechazado');
        }

        Security::flash('success', 'Pago actualizado a ' . $status . '.');
        redirect('/admin/pagos/' . $paymentId);
    }
}
