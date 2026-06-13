<?php

declare(strict_types=1);

class RegistrationController extends Controller
{
    public function index(): void
    {
        $ticketModel = new TicketType();
        $ticketTypes = $ticketModel->allActive();

        $this->render('registro', [
            'title' => 'Registro de Participantes',
            'ticketTypes' => $ticketTypes,
            'styles' => ['registro.css'],
            'scripts' => ['registro.js'],
        ]);
    }

    public function store(): void
    {
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

        withOld($data);

        if (
            $data['primer_nombre'] === '' ||
            $data['primer_apellido'] === '' ||
            $data['correo'] === '' ||
            !filter_var($data['correo'], FILTER_VALIDATE_EMAIL) ||
            $data['cedula'] === '' ||
            $data['telefono'] === '' ||
            $data['institucion'] === '' ||
            $data['ciudad'] === '' ||
            $data['pais'] === '' ||
            $data['tipo_entrada_id'] <= 0
        ) {
            Security::flash('error', 'Completa todos los campos obligatorios con datos validos.');
            redirect('/registro');
        }

        $ticketModel = new TicketType();
        $ticketType = $ticketModel->findById($data['tipo_entrada_id']);
        if ($ticketType === null || ($ticketType['estado'] ?? 'activo') !== 'activo') {
            Security::flash('error', 'Tipo de entrada invalido.');
            redirect('/registro');
        }

        $participantModel = new Participant();
        $participantId = $participantModel->create($data);

        clearOld();
        Security::flash('success', 'Registro exitoso. Ahora puedes realizar tu pago. ID participante: ' . $participantId);
        redirect('/pagos?participante_id=' . $participantId);
    }
}
