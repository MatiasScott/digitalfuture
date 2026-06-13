<?php

declare(strict_types=1);

class AdminParticipantController extends Controller
{
    public function index(): void
    {
        Security::requireAdmin();

        $participantModel = new Participant();
        $participants = $participantModel->all();

        $this->render('admin/participants', [
            'title' => 'Gestion de Participantes',
            'participants' => $participants,
            'styles' => ['dashboard.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }

    public function show(string $id): void
    {
        Security::requireAdmin();

        $participantModel = new Participant();
        $paymentModel = new Payment();

        $participant = $participantModel->findById((int) $id);
        if ($participant === null) {
            http_response_code(404);
            echo 'Participante no encontrado';
            return;
        }

        $payments = $paymentModel->findByParticipant((int) $id);

        $this->render('admin/participant_show', [
            'title' => 'Detalle de Participante',
            'participant' => $participant,
            'payments' => $payments,
            'styles' => ['dashboard.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }

    public function edit(string $id): void
    {
        Security::requireAdmin();

        $participantModel = new Participant();
        $ticketModel = new TicketType();

        $participant = $participantModel->findById((int) $id);
        if ($participant === null) {
            http_response_code(404);
            echo 'Participante no encontrado';
            return;
        }

        $this->render('admin/participant_edit', [
            'title' => 'Editar Participante',
            'participant' => $participant,
            'ticketTypes' => $ticketModel->allActive(),
            'styles' => ['dashboard.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }

    public function update(string $id): void
    {
        Security::requireAdmin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $data = [
            'primer_nombre' => Security::clean($_POST['primer_nombre'] ?? ''),
            'segundo_nombre' => Security::clean($_POST['segundo_nombre'] ?? ''),
            'primer_apellido' => Security::clean($_POST['primer_apellido'] ?? ''),
            'segundo_apellido' => Security::clean($_POST['segundo_apellido'] ?? ''),
            'correo' => Security::clean($_POST['correo'] ?? ''),
            'cedula' => Security::clean($_POST['cedula'] ?? ''),
            'telefono' => Security::clean($_POST['telefono'] ?? ''),
            'institucion' => Security::clean($_POST['institucion'] ?? ''),
            'ciudad' => Security::clean($_POST['ciudad'] ?? ''),
            'pais' => Security::clean($_POST['pais'] ?? ''),
            'tipo_entrada_id' => (int) ($_POST['tipo_entrada_id'] ?? 0),
        ];

        $participantModel = new Participant();
        $participantModel->update((int) $id, $data);

        Security::flash('success', 'Participante actualizado correctamente.');
        redirect('/admin/participantes/' . (int) $id);
    }

    public function approvePayment(string $id): void
    {
        Security::requireAdmin();
        $this->changeLatestPaymentStatus((int) $id, 'aprobado', 'Pago aprobado manualmente');
    }

    public function rejectPayment(string $id): void
    {
        Security::requireAdmin();
        $this->changeLatestPaymentStatus((int) $id, 'rechazado', 'Pago rechazado manualmente');
    }

    public function confirmAttendance(string $id): void
    {
        Security::requireAdmin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $participantId = (int) $id;
        $paymentModel = new Payment();
        $latestPayment = $paymentModel->findLatestByParticipant($participantId);

        if ($latestPayment === null || ($latestPayment['estado'] ?? '') !== 'aprobado') {
            Security::flash('error', 'Solo se puede confirmar asistencia con pago aprobado.');
            redirect('/admin/participantes/' . $participantId);
        }

        $attendanceModel = new Attendance();
        $attendanceModel->create([
            'participante_id' => $participantId,
            'estado' => 'presente',
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
        ]);

        $participantModel = new Participant();
        $participantModel->updateStatus($participantId, 'asistencia_confirmada');

        Security::flash('success', 'Asistencia confirmada.');
        redirect('/admin/participantes/' . $participantId);
    }

    private function changeLatestPaymentStatus(int $participantId, string $status, string $note): void
    {
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $paymentModel = new Payment();
        $participantModel = new Participant();

        $latestPayment = $paymentModel->findLatestByParticipant($participantId);
        if ($latestPayment === null) {
            Security::flash('error', 'El participante no tiene pagos registrados.');
            redirect('/admin/participantes/' . $participantId);
        }

        $paymentModel->updateStatus((int) $latestPayment['id'], $status, (int) $_SESSION['admin_user']['id'], $note);

        if ($status === 'aprobado') {
            $participantModel->updateStatus($participantId, 'pago_aprobado');
        }

        if ($status === 'rechazado') {
            $participantModel->updateStatus($participantId, 'pago_rechazado');
        }

        Security::flash('success', 'Estado de pago actualizado a ' . $status . '.');
        redirect('/admin/participantes/' . $participantId);
    }
}
