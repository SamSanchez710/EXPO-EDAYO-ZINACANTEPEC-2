<?php
session_start();

// Borrar todas las variables de sesión
$_SESSION = array();

// Borrar las cookies de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Cerrar la sesión
session_destroy();

// Desde app/controllers/logout.php
header("Location: ../../public/Vistas/index.php");
exit();

?>
