<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/ConfiguracionController.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false, 'message'=>'No hay sesión iniciada']);
    exit;
}

$password = $_POST['password'] ?? '';

if(empty($password)){
    echo json_encode(['success'=>false, 'message'=>'La contraseña no puede estar vacía']);
    exit;
}

$controller = new ConfiguracionController();
$result = $controller->cambiarPassword($_SESSION['user_id'], $password);

echo json_encode($result);
