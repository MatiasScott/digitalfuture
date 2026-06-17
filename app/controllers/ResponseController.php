<?php

class ResponseController
{

    // Método para mostrar la vista de confirmación
    public function confirmacion()
    {
        // La información del mensaje viene por la URL (GET)
        $mensaje = $_GET['msg'] ?? 'Tu solicitud ha sido recibida.';

        $mensaje = urldecode($mensaje);
        if (!mb_check_encoding($mensaje, 'UTF-8')) {
        $mensaje = utf8_encode($mensaje);
        }
        $data = [
            'titulo' => 'Confirmación de Inscripción',
            'mensaje' => $mensaje
        ];

        $this->renderView('confirmacion_view', $data);
    }

    // Método para mostrar la vista de error
    public function error()
    {
        // La información del mensaje viene por la URL (GET)
        $mensaje = $_GET['msg'] ?? 'Ha ocurrido un error inesperado.';
        
        $mensaje = urldecode($mensaje);
        if (!mb_check_encoding($mensaje, 'UTF-8')) {
        $mensaje = utf8_encode($mensaje);
        }

        $data = [
            'titulo' => 'Error de Procesamiento',
            'mensaje' => $mensaje
        ];

        $this->renderView('error_view', $data);
    }

    // Función de renderizado (copiada del HomeController)
    private function renderView($viewName, $data = [])
    {
        if (ob_get_level()) {
            ob_clean();
        }
    
        global $BASE_URL;
    
        extract($data);
    
        $filePath = APP_PATH . '/views/' . $viewName . '.php';
    
        if (file_exists($filePath)) {
            require $filePath;
        } else {
            http_response_code(500);
            echo "Error: Vista no encontrada - " . htmlspecialchars($filePath);
        }
    }
}
