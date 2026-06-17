<?php

require_once __DIR__ . '/../models/AdminModel.php';

class AdminController
{
    private $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* =====================================================
       AUTENTICACIÓN
    ===================================================== */

    private function checkAuth()
    {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: ' . BASE_PATH . '/admin/login');
            exit;
        }
    }

    public function login()
    {
        if (!empty($_SESSION['admin_logged_in'])) {
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }
        $this->renderView('admin/admin_login_view');
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/admin/login');
            exit;
        }

        $usuario  = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($this->adminModel->verifyUser($usuario, $password)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $usuario;

            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }

        $this->renderView('admin/admin_login_view', [
            'error' => 'Usuario o contrase�0�9a incorrectos'
        ]);
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_PATH . '/admin/login');
        exit;
    }

    /* =====================================================
       DASHBOARD
    ===================================================== */

    /**
     * Ruta:
     *  /admin/dashboard
     *  /admin/dashboard/aprobado
     *  /admin/dashboard/pendiente
     *  /admin/dashboard/rechazado
     */
    public function dashboard($estado = null)
    {
        $this->checkAuth();

        $estadosValidos = ['aprobado', 'pendiente', 'rechazado'];

        if ($estado && !in_array($estado, $estadosValidos)) {
            $estado = null;
        }

        // Datos
        $counts = $this->adminModel->getDashboardCounts();
        $countsEntrada = $this->adminModel->getDashboardEntradaCounts();

        if ($estado) {
            $participantes = $this->adminModel->getParticipantsByStatus($estado);
        } else {
            $participantes = $this->adminModel->getAllParticipantsWithTransactions();
        }

        $data = [
            'participantes' => $participantes,
            'counts' => $counts,
            'countsEntrada' => $countsEntrada,
            'estadoActual' => $estado,
            'entradaActual' => null,
            'mensaje' => $_GET['mensaje'] ?? null
        ];

        $this->renderView('admin/admin_dashboard_view', $data);
    }

    /* =====================================================
       DASHBOARD ENTRADA
    ===================================================== */

    /**
     * Ruta:
     *  /admin/dashboardEntrada
     *  /admin/dashboardEntrada/Estudiante
     *  /admin/dashboardEntrada/Profesional
     *  /admin/dashboardEntrada/VIP
     */
    public function dashboardEntrada($entrada = null)
    {
        $this->checkAuth();

        $entradasValidas = ['Estudiante', 'Profesional', 'VIP'];

        if ($entrada && !in_array($entrada, $entradasValidas)) {
            $entrada = null;
        }

        // Datos
        $counts = $this->adminModel->getDashboardCounts();
        $countsEntrada = $this->adminModel->getDashboardEntradaCounts();

        if ($entrada) {
            $participantes = $this->adminModel->getParticipantsByEntrada($entrada);
        } else {
            $participantes = $this->adminModel->getAllParticipantsWithTransactions();
        }

        $data = [
            'participantes' => $participantes,
            'counts' => $counts,
            'countsEntrada' => $countsEntrada,
            'estadoActual' => null, // Separado
            'entradaActual' => $entrada,
            'mensaje' => $_GET['mensaje'] ?? null
        ];

        $this->renderView('admin/admin_dashboard_view', $data);
    }

    /* =====================================================
       EDITAR ESTADO
    ===================================================== */

    public function editarEstado($participanteId)
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $transaccion = $this->adminModel->getTransactionByParticipantId($participanteId);
            $this->renderView('admin/admin_edit_status_view', compact('transaccion'));
            return;
        }

        $pagoId = (int)($_POST['pago_id'] ?? 0);
        $nuevoEstado   = $_POST['estado'] ?? 'pendiente';

        if ($this->adminModel->updatePaymentState($pagoId, $nuevoEstado)) {
            $mensaje = "Estado actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar estado.";
        }

        header('Location: ' . BASE_PATH . '/admin/dashboard?mensaje=' . urlencode($mensaje));
        exit;
    }

    /* =====================================================
       ELIMINAR
    ===================================================== */

    public function eliminarTransaccion($transaccionId)
    {
        $this->checkAuth();

        if ($this->adminModel->deletePayment((int) $transaccionId)) {
            $mensaje = "Transacción eliminada correctamente.";
        } else {
            $mensaje = "Error al eliminar la transacción.";
        }

        header('Location: ' . BASE_PATH . '/admin/dashboard?mensaje=' . urlencode($mensaje));
        exit;
    }

    /* =====================================================
       EXPORTAR APROBADOS
    ===================================================== */

    public function exportarAprobados()
    {
        $this->checkAuth();

        $aprobados = $this->adminModel->getApprovedParticipantsData();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=aprobados_' . date('Ymd') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nombre', 'Apellido', 'Email', 'Entrada', 'Monto'], ';');

        foreach ($aprobados as $p) {
            $nombreCompleto = trim(
                $p['primer_nombre'] . ' ' .
                ($p['segundo_nombre'] ?? '') . ' ' .
                $p['primer_apellido'] . ' ' .
                ($p['segundo_apellido'] ?? '')
            );

            fputcsv($output, [
                $nombreCompleto,
                '',
                $p['correo'],
                $p['tipo_entrada'],
                $p['monto']
            ], ';');
        }

        fclose($output);
        exit;
    }

    /* =====================================================
       RENDER
    ===================================================== */

    private function renderView($viewName, $data = [])
    {
        extract($data);
        $path = APP_PATH . '/views/' . $viewName . '.php';

        if (!file_exists($path)) {
            http_response_code(500);
            die("Vista no encontrada: {$path}");
        }

        require $path;
    }
}
