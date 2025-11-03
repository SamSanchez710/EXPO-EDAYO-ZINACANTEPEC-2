<?php
// Si quieres mostrar mensajes de error desde el controlador, puedes usar $error
if (!isset($error)) $error = '';
?>

<div class="modal" id="modalLogin">
    <div class="modal-content">
        <div class="login-info-modal">
            <h3>Iniciar Sesión</h3>
            <link rel="stylesheet" href="../css/modal_login.css">

            <?php if($error) echo "<p class='error'>$error</p>"; ?>

            <form method="POST" action="../../app/controllers/LoginController.php">
                <input type="email" name="email" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
            </form>

            <div style="text-align:center; margin-top:10px;">
                <a href="register.php">¿No tienes cuenta? Regístrate</a>
            </div>
        </div>
    </div>
</div>
