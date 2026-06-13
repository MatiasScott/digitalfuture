<?php

declare(strict_types=1);

class AdminAuthController extends Controller
{
    public function index(): void
    {
        if (!empty($_SESSION['admin_user'])) {
            redirect('/admin/dashboard');
        }

        $this->render('admin/login', [
            'title' => 'Login Administrador',
            'styles' => ['login.css'],
            'scripts' => ['login.js'],
        ], 'auth');
    }

    public function login(): void
    {
        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $login = Security::clean($_POST['login'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if ($login === '' || $password === '') {
            Security::flash('error', 'Completa usuario/correo y contrasena.');
            redirect('/admin/login');
        }

        $adminModel = new AdminUser();
        $user = $adminModel->findByUsernameOrEmail($login);

        if ($user === null || ($user['estado'] ?? '') !== 'activo' || !password_verify($password, $user['password'])) {
            Security::flash('error', 'Credenciales invalidas.');
            redirect('/admin/login');
        }

        session_regenerate_id(true);
        $_SESSION['admin_user'] = [
            'id' => (int) $user['id'],
            'nombres' => $user['nombres'],
            'correo' => $user['correo'],
            'rol' => $user['rol'],
        ];

        redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        Security::requireAdmin();

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();

        redirect('/admin/login');
    }
}
