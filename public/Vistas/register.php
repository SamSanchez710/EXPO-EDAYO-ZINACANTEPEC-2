<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Expo Edayo</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .register-container { width: 350px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        input { width: 100%; padding: 10px; margin: 5px 0; }
        button { padding: 10px; width: 100%; background: #28a745; color: #fff; border: none; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registro</h2>

        <?php 
        if(isset($error)) echo "<p class='error'>$error</p>";
        if(isset($success)) echo "<p class='success'>$success</p>";
        ?>

        <form method="POST" action="../../app/controllers/RegisterController.php">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido_paterno" placeholder="Apellido Paterno" required>
            <input type="text" name="apellido_materno" placeholder="Apellido Materno" required>
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>

        <div style="text-align:center; margin-top:10px;">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>
