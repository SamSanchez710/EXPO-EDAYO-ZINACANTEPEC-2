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

            if ($user) {
                // Verificar si la contraseña es correcta
                if (!password_verify($password, $user['password'])) {
                    echo "<script>
                        alert('Correo o contraseña incorrectos');
                        window.history.back();
                    </script>";
                    exit;
                }

                // Verificar si el usuario ha confirmado su correo
                if ($user['verificado'] != 1) {
                    echo "<script>
                        alert('Debes verificar tu correo antes de iniciar sesión');
                        window.history.back();
                    </script>";
                    exit;
                }

                // Iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

                // Redirigir según tipo de usuario
                if ($user['tipo_usuario'] === 'admin') {
                    header("Location: ../../public/Vistas/admin/admin.php");
                    exit;
                } else {
                    header("Location: ../../public/Vistas/usuario/usuario.php");
                    exit;
                }

            } else {
                // Usuario no encontrado
                echo "<script>
                    alert('Correo o contraseña incorrectos');
                    window.history.back();
                </script>";
                exit;
            }
        }
        // No hacer include aquí para que el usuario permanezca en la página actual
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
