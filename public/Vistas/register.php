<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Expo Edayo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/registro.css">
    <link rel="stylesheet" href="../css/modal_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Registro</h2>
        
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST" action="../../app/controllers/RegisterController.php">
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" placeholder="Nombre completo" required>
                </div>
            </div>
            
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                </div>
            </div>
            
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>
            </div>
            
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                </div>
            </div>
            
            <div class="input-group">
                <button type="submit">
                    <span class="btn-text">Registrarse</span>
                    <div class="loading-spinner"></div>
                </button>
            </div>
        </form>
        
        <div style="text-align:center; margin-top:10px;">
    <a href="#" id="backToLogin">¿Ya tienes cuenta? Inicia sesión</a>
</div>
    </div>
</body>
</html>