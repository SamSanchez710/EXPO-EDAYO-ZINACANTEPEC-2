<?php
session_start();
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol']!=='admin'){
    echo json_encode(['success'=>false,'mensaje'=>'No autorizado']);
    exit;
}

require_once __DIR__ . '/../../../../app/controllers/ConfiguracionController.php';
$controller = new ConfiguracionController();

$accion = $_GET['accion'] ?? '';
$usuario_id = $_SESSION['usuario_id'];
$res = ['success'=>false,'mensaje'=>'Acción desconocida'];

switch($accion){
    case 'perfil':
        $res['success'] = $controller->guardarPerfil($usuario_id, $_POST);
        $res['mensaje'] = $res['success'] ? 'Perfil actualizado' : 'Error al actualizar perfil';
    break;
    case 'preferencias':
        $res['success'] = $controller->guardarPreferencias($usuario_id, $_POST);
        $res['mensaje'] = $res['success'] ? 'Preferencias guardadas' : 'Error al guardar preferencias';
    break;
}

header('Content-Type: application/json');
echo json_encode($res);
