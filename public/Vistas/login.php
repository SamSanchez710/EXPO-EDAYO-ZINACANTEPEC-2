<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Expo Edayo</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .login-container { width: 300px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        input { width: 100%; padding: 10px; margin: 5px 0; }
        button { padding: 10px; width: 100%; background: #007bff; color: #fff; border: none; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="../../app/controllers/LoginController.php">
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
    <div style="text-align:center; margin-top:10px;">
    <a href="register.php">¿No tienes cuenta? Regístrate</a>
</div>

</body>
</html>
