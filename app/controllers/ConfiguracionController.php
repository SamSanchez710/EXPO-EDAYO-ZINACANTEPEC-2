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

    // ======================================
    // FOTO DE PERFIL
    // ======================================

    public function actualizarFotoPerfil($user_id, $foto_data) {
        $response = ['success'=>false, 'message'=>''];

        if($this->model->actualizarFotoPerfil($user_id, $foto_data)) {
            $response['success'] = true;
            $response['message'] = 'Foto de perfil actualizada correctamente.';
        } else {
            $response['message'] = 'Error al actualizar la foto de perfil.';
        }

        return $response;
    }

    public function obtenerFotoPerfil($user_id) {
        $foto_data = $this->model->obtenerFotoPerfil($user_id);

        if($foto_data) {
            header('Content-Type: image/jpeg');
            echo $foto_data;
        } else {
            $foto_por_defecto = file_get_contents('../../images/avatar_default.png');
            header('Content-Type: image/png');
            echo $foto_por_defecto;
        }
        exit;
    }
}

// ======================================
// AJAX handler
// ======================================
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

        case 'actualizarFotoPerfil':
            if(isset($_FILES['avatar'])) {
                $foto_data = file_get_contents($_FILES['avatar']['tmp_name']);
                echo json_encode($controller->actualizarFotoPerfil($user_id, $foto_data));
            } else {
                echo json_encode(['success'=>false, 'message'=>'No se recibió archivo']);
            }
            break;

        default:
            echo json_encode(['success'=>false, 'message'=>'Acción no válida']);
    }
    exit;
}

// ======================================
// Obtener foto (GET)
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'foto') {
    $controller = new ConfiguracionController();
    if(isset($_SESSION['user_id'])) {
        $controller->obtenerFotoPerfil($_SESSION['user_id']);
    }
}
