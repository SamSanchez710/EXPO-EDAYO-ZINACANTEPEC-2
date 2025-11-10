<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../libs/Email.php';

class RegisterController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre'] ?? '');
            $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
            $apellido_materno = trim($_POST['apellido_materno'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validar contraseñas
            if ($password !== $confirm_password) {
                $_SESSION['alert'] = "Las contraseñas no coinciden";
                header("Location: ../../public/Vistas/index.php");
                exit;
            }

            // Validar si el correo ya existe
            if ($this->userModel->getUserByEmail($email)) {
                $_SESSION['alert'] = "El correo ya está registrado";
                header("Location: ../../public/Vistas/index.php");
                exit;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(32)); // Token seguro de 64 caracteres

            $registered = $this->userModel->registerUserWithToken(
                $nombre, $apellido_paterno, $apellido_materno, $email, $hashedPassword, $token
            );

            if(!$registered){
                $_SESSION['alert'] = "Ocurrió un error al registrar. Intenta de nuevo";
                header("Location: ../../public/Vistas/index.php");
                exit;
            }

            $emailSent = Email::sendVerificationEmail($email, $token);
            if($emailSent){
                $_SESSION['alert'] = "Registro exitoso. Revisa tu correo y haz clic en 'Verificar correo' para activar tu cuenta.";
                header("Location: ../../public/Vistas/index.php");
                exit;
            } else {
                $_SESSION['alert'] = "No se pudo enviar el correo de verificación. Intenta más tarde.";
                header("Location: ../../public/Vistas/index.php");
                exit;
            }

        } else {
            header("Location: ../../public/Vistas/index.php");
            exit;
        }
    }
}

$controller = new RegisterController();
$controller->register();
?>
