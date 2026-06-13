<?php

declare(strict_types=1);

class AdminAttendanceController extends Controller
{
    public function index(): void
    {
        Security::requireAdmin();

        $participantModel = new Participant();
        $attendanceModel = new Attendance();

        $this->render('admin/attendances', [
            'title' => 'Gestion de Asistencias',
            'participants' => $participantModel->all(),
            'attendances' => $attendanceModel->listWithParticipants(),
            'styles' => ['asistencias.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }

    public function store(): void
    {
        Security::requireAdmin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $participantId = (int) ($_POST['participante_id'] ?? 0);
        $status = Security::clean($_POST['estado'] ?? 'ausente');

        if ($participantId <= 0 || !in_array($status, ['presente', 'ausente'], true)) {
            Security::flash('error', 'Datos de asistencia invalidos.');
            redirect('/admin/asistencias');
        }

        $attendanceModel = new Attendance();
        $attendanceModel->create([
            'participante_id' => $participantId,
            'estado' => $status,
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
        ]);

        Security::flash('success', 'Asistencia registrada correctamente.');
        redirect('/admin/asistencias');
    }
}
