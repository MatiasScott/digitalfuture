<?php

declare(strict_types=1);

$router->get('/', [HomeController::class, 'index']);
$router->get('/registro', [RegistrationController::class, 'index']);
$router->post('/registro', [RegistrationController::class, 'store']);

$router->get('/pagos', [PaymentController::class, 'index']);
$router->post('/pagos', [PaymentController::class, 'store']);
$router->post('/pagos/payphone', [PaymentController::class, 'createPayPhoneCharge']);

$router->get('/admin/login', [AdminAuthController::class, 'index']);
$router->post('/admin/login', [AdminAuthController::class, 'login']);
$router->post('/admin/logout', [AdminAuthController::class, 'logout']);

$router->get('/admin/dashboard', [AdminDashboardController::class, 'index']);

$router->get('/admin/participantes', [AdminParticipantController::class, 'index']);
$router->get('/admin/participantes/{id}', [AdminParticipantController::class, 'show']);
$router->get('/admin/participantes/{id}/editar', [AdminParticipantController::class, 'edit']);
$router->post('/admin/participantes/{id}/editar', [AdminParticipantController::class, 'update']);
$router->post('/admin/participantes/{id}/aprobar-pago', [AdminParticipantController::class, 'approvePayment']);
$router->post('/admin/participantes/{id}/rechazar-pago', [AdminParticipantController::class, 'rejectPayment']);
$router->post('/admin/participantes/{id}/confirmar-asistencia', [AdminParticipantController::class, 'confirmAttendance']);

$router->get('/admin/pagos', [AdminPaymentController::class, 'index']);
$router->get('/admin/pagos/{id}', [AdminPaymentController::class, 'show']);
$router->post('/admin/pagos/{id}/aprobar', [AdminPaymentController::class, 'approve']);
$router->post('/admin/pagos/{id}/rechazar', [AdminPaymentController::class, 'reject']);

$router->get('/admin/asistencias', [AdminAttendanceController::class, 'index']);
$router->post('/admin/asistencias/marcar', [AdminAttendanceController::class, 'store']);

$router->get('/admin/usuarios', [AdminUserController::class, 'index']);
$router->post('/admin/usuarios', [AdminUserController::class, 'store']);
$router->post('/admin/usuarios/{id}/toggle', [AdminUserController::class, 'toggle']);
