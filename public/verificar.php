<?php
session_start(); // <--- esto faltaba
require_once __DIR__ . '/../app/models/User.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $userModel = new User();

    if($userModel->verifyUserByToken($token)){
        $_SESSION['alert'] = "Tu cuenta ha sido verificada correctamente. Ya puedes iniciar sesión.";
        header("Location: http://localhost/EXPO-EDAYO-ZINACANTEPEC-2/public/Vistas/index.php");
        exit;
    } else {
        $_SESSION['alert'] = "Token inválido o cuenta ya verificada.";
        header("Location: http://localhost/EXPO-EDAYO-ZINACANTEPEC-2/public/Vistas/index.php");
        exit;
    }
} else {
    $_SESSION['alert'] = "Token no proporcionado.";
    header("Location: http://localhost/EXPO-EDAYO-ZINACANTEPEC-2/public/Vistas/index.php");
    exit;
}
?>
