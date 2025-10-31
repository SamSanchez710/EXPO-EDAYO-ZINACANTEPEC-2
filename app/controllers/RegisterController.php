<?php
session_start();
require_once __DIR__ . '/../models/User.php';

class RegisterController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido_paterno = $_POST['apellido_paterno'] ?? '';
            $apellido_materno = $_POST['apellido_materno'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validar contraseñas
            if($password !== $confirm_password) {
                $error = "Las contraseñas no coinciden";
                include __DIR__ . '/../Vistas/register.php';
                return;
            }

            // Validar si el correo ya existe
            if($this->userModel->getUserByEmail($email)) {
                $error = "El correo ya está registrado";
                include __DIR__ . '/../Vistas/register.php';
                return;
            }

            // Hashear contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Guardar usuario como tipo 'usuario'
            if($this->userModel->registerUser($nombre, $apellido_paterno, $apellido_materno, $email, $hashedPassword)) {
                
                // Obtener usuario recién creado
                $user = $this->userModel->getUserByEmail($email);

                // Iniciar sesión automáticamente
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

                // Redirigir a panel de usuario
                header("Location: ../../public/Vistas/usuario/usuario.php");
                exit;
            } else {
                $error = "Ocurrió un error, intenta de nuevo";
                include __DIR__ . '/../Vistas/register.php';
            }
        } else {
            include __DIR__ . '/../Vistas/register.php';
        }
    }
}

// Ejecutar controlador
$controller = new RegisterController();
$controller->register();
?>
