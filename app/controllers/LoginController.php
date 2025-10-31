<?php
session_start();
require_once __DIR__ . '/../models/User.php';

class LoginController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Mostrar y procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

                if ($user['tipo_usuario'] === 'admin') {
                    header("Location: ../../public/Vistas/admin/admin.php");
                    exit;
                } else {
                    header("Location: ../../public/Vistas/usuario/usuario.php"); // pendiente
                    exit;
                }
            } else {
                $error = "Correo o contraseña incorrectos";
                include __DIR__ . '/../Vistas/login.php';
            }
        } else {
            include __DIR__ . '/../Vistas/login.php';
        }
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header("Location: ../Vistas/login.php");
        exit;
    }
}

// Ejecutar controlador
$controller = new LoginController();
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller->logout();
} else {
    $controller->login();
}
?>
