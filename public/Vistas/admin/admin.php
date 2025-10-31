<?php
// public/Vistas/admin/index.php

// Iniciamos sesión para manejar accesos si luego agregamos autenticación
session_start();

// Si más adelante deseas proteger el acceso al admin, aquí se valida el login
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: ../login.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
</head>
<body>
    <h1>Panel de Administración - Edayo Zinacantepec</h1>

    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="talleres/index.php">Talleres</a></li>
            <li><a href="mesas/mesas_index.php">Mesas</a></li>
            <li><a href="inscripcciones/inscripciones_index.php">Inscripciones</a></li>
            <li><a href="reports.php">Reportes</a></li>
            <li><a href="usuarios/index.php">Usuarios</a></li>
            <li><a href="settings.php">Configuración</a></li>
        </ul>
    </nav>

    <hr>

    <p>Bienvenido al panel administrativo. Selecciona una opción del menú para continuar.</p>
</body>
</html>
