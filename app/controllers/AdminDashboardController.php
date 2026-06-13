<?php

declare(strict_types=1);

class AdminDashboardController extends Controller
{
    public function index(): void
    {
        Security::requireAdmin();

        $participantModel = new Participant();
        $paymentModel = new Payment();

        $this->render('admin/dashboard', [
            'title' => 'Dashboard Administrativo',
            'stats' => [
                'inscritos' => $participantModel->countAll(),
                'pagos_pendientes' => $paymentModel->countByStatus('pendiente'),
                'pagos_aprobados' => $paymentModel->countByStatus('aprobado'),
                'asistentes_confirmados' => $participantModel->countConfirmed(),
                'ingresos_totales' => $paymentModel->totalIncomeApproved(),
            ],
            'styles' => ['dashboard.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }
}
