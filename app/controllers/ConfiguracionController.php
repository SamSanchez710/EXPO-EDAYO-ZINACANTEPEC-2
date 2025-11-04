<?php
session_start();
require_once __DIR__ . '/../models/ConfiguracionModel.php';

class ConfiguracionController {
    private $model;

    public function __construct() {
        $this->model = new ConfiguracionModel();
    }

    // Obtener datos para la vista
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            return ['error' => 'No tienes sesión iniciada. Acceso denegado.'];
        }

        $user_id = $_SESSION['user_id'];
        return [
            'usuario' => $this->model->getUsuario($user_id),
            'preferencias' => $this->model->getPreferencias($user_id)
        ];
    }

    // Actualizar perfil
    public function actualizarPerfil($user_id, $nombre, $email) {
        return $this->model->actualizarPerfil($user_id, $nombre, $email);
    }

    // Cambiar contraseña
    public function cambiarPassword($user_id, $password) {
        return $this->model->cambiarPassword($user_id, $password);
    }

    // Guardar preferencias
    public function guardarPreferencias($user_id, $tema, $notificaciones, $formato_fecha, $idioma) {
        return $this->model->guardarPreferencias($user_id, $tema, $notificaciones, $formato_fecha, $idioma);
    }
}

// AJAX handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $controller = new ConfiguracionController();

    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success'=>false, 'message'=>'No hay sesión iniciada']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    switch($_GET['action']) {
        case 'actualizarPerfil':
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            echo json_encode($controller->actualizarPerfil($user_id, $nombre, $email));
            break;

        case 'cambiarPassword':
            $password = $_POST['password'] ?? '';
            echo json_encode($controller->cambiarPassword($user_id, $password));
            break;

        case 'guardarPreferencias':
            $tema = $_POST['tema'] ?? 'claro';
            $notificaciones = $_POST['notificaciones'] ?? 1;
            $formato_fecha = $_POST['formato_fecha'] ?? 'd/m/Y';
            $idioma = $_POST['idioma'] ?? 'es';
            echo json_encode($controller->guardarPreferencias($user_id, $tema, $notificaciones, $formato_fecha, $idioma));
            break;

        default:
            echo json_encode(['success'=>false, 'message'=>'Acción no válida']);
    }
    exit;
}
