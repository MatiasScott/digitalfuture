<?php

declare(strict_types=1);

class AdminUserController extends Controller
{
    public function index(): void
    {
        Security::requireRole(['super_admin']);

        $model = new AdminUser();

        $this->render('admin/users', [
            'title' => 'Usuarios Administrativos',
            'users' => $model->all(),
            'styles' => ['dashboard.css'],
            'scripts' => ['dashboard.js'],
        ], 'admin');
    }

    public function store(): void
    {
        Security::requireRole(['super_admin']);

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $nombres = Security::clean($_POST['nombres'] ?? '');
        $correo = Security::clean($_POST['correo'] ?? '');
        $usuario = Security::clean($_POST['usuario'] ?? '');
        $rol = Security::clean($_POST['rol'] ?? 'admin');
        $password = (string) ($_POST['password'] ?? '');

        if ($nombres === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $usuario === '' || $password === '') {
            Security::flash('error', 'Datos invalidos para crear usuario.');
            redirect('/admin/usuarios');
        }

        if (!in_array($rol, ['super_admin', 'admin'], true)) {
            Security::flash('error', 'Rol invalido.');
            redirect('/admin/usuarios');
        }

        $model = new AdminUser();
        $model->create([
            'nombres' => $nombres,
            'correo' => $correo,
            'usuario' => $usuario,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol,
            'estado' => 'activo',
        ]);

        Security::flash('success', 'Usuario administrativo creado.');
        redirect('/admin/usuarios');
    }

    public function toggle(string $id): void
    {
        Security::requireRole(['super_admin']);

        if (!Security::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Token CSRF invalido';
            return;
        }

        $model = new AdminUser();
        $model->toggleStatus((int) $id);

        Security::flash('success', 'Estado de usuario actualizado.');
        redirect('/admin/usuarios');
    }
}
