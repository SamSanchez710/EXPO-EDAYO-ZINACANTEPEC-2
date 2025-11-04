<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/ConfiguracionController.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false, 'message'=>'No hay sesión iniciada']);
    exit;
}

// Obtener datos enviados desde el formulario
$tema = $_POST['tema'] ?? 'claro';
$notificaciones = isset($_POST['notificaciones']) ? intval($_POST['notificaciones']) : 1;
$formato_fecha = $_POST['formato_fecha'] ?? 'd/m/Y';
$idioma = $_POST['idioma'] ?? 'es';

$controller = new ConfiguracionController();
$result = $controller->guardarPreferencias($_SESSION['user_id'], $tema, $notificaciones, $formato_fecha, $idioma);

echo json_encode($result);
