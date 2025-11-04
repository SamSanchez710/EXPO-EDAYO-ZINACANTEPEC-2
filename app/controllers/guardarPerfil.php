<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/ConfiguracionController.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false, 'message'=>'No hay sesión iniciada']);
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';

if(empty($nombre) || empty($email)){
    echo json_encode(['success'=>false, 'message'=>'Todos los campos son obligatorios']);
    exit;
}

$controller = new ConfiguracionController();
$result = $controller->actualizarPerfil($_SESSION['user_id'], $nombre, $email);

echo json_encode($result);
