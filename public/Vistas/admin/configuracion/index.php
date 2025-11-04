<?php
require_once __DIR__ . '/../../../../app/controllers/ConfiguracionController.php';
$controller = new ConfiguracionController();
$data = $controller->index();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Configuración</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.card { background: #f5f5f5; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
input, select { padding:5px; margin:5px 0; width: 100%; }
button { padding:8px 15px; margin-top:10px; cursor:pointer; }
</style>
</head>
<body>

<h1>Configuración</h1>

<?php if(isset($data['error'])): ?>
    <p style="color:red;"><?= $data['error'] ?></p>
<?php else: ?>

<div class="card">
    <h3>Perfil del usuario</h3>
    <form id="perfilForm">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($data['usuario']['nombre']) ?>">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['usuario']['email']) ?>">
        <button type="button" onclick="guardarPerfil()">Guardar perfil</button>
    </form>

    <h4>Cambiar contraseña</h4>
    <form id="passwordForm">
        <label>Nueva contraseña</label>
        <input type="password" name="password">
        <button type="button" onclick="cambiarPassword()">Cambiar contraseña</button>
    </form>
</div>

<div class="card">
    <h3>Preferencias del sistema</h3>
    <form id="preferenciasForm">
        <label>Tema</label>
        <select name="tema">
            <option value="claro" <?= $data['preferencias']['tema']=='claro'?'selected':'' ?>>Claro</option>
            <option value="oscuro" <?= $data['preferencias']['tema']=='oscuro'?'selected':'' ?>>Oscuro</option>
        </select>
        <label>Notificaciones por correo</label>
        <select name="notificaciones">
            <option value="1" <?= $data['preferencias']['notificaciones']==1?'selected':'' ?>>Sí</option>
            <option value="0" <?= $data['preferencias']['notificaciones']==0?'selected':'' ?>>No</option>
        </select>
        <label>Formato de fecha</label>
        <input type="text" name="formato_fecha" value="<?= $data['preferencias']['formato_fecha'] ?>">
        <label>Idioma</label>
        <select name="idioma">
            <option value="es" <?= $data['preferencias']['idioma']=='es'?'selected':'' ?>>Español</option>
            <option value="en" <?= $data['preferencias']['idioma']=='en'?'selected':'' ?>>Inglés</option>
        </select>
        <button type="button" onclick="guardarPreferencias()">Guardar preferencias</button>
    </form>
</div>

<script>
const baseURL = '/EXPO-EDAYO-ZINACANTEPEC-2/app/controllers/ConfiguracionController.php';

function guardarPerfil(){
    const form = document.getElementById('perfilForm');
    fetch(baseURL + '?action=actualizarPerfil', { method:'POST', body:new FormData(form) })
    .then(res=>res.json())
    .then(r=>alert(r.success ? 'Perfil actualizado' : 'Error: '+(r.message||'No se pudo actualizar')))
    .catch(()=>alert('Error en la comunicación con el servidor'));
}

function cambiarPassword(){
    const form = document.getElementById('passwordForm');
    fetch(baseURL + '?action=cambiarPassword', { method:'POST', body:new FormData(form) })
    .then(res=>res.json())
    .then(r=>alert(r.success ? 'Contraseña cambiada' : 'Error: '+(r.message||'No se pudo cambiar')))
    .catch(()=>alert('Error en la comunicación con el servidor'));
}

function guardarPreferencias(){
    const form = document.getElementById('preferenciasForm');
    fetch(baseURL + '?action=guardarPreferencias', { method:'POST', body:new FormData(form) })
    .then(res=>res.json())
    .then(r=>{
        if(r.success){
            alert('Preferencias guardadas');
        } else {
            alert('Error: '+(r.message||'No se pudo guardar'));
        }
    })
    .catch(()=>alert('Error en la comunicación con el servidor'));
}
</script>

<?php endif; ?>
</body>
</html>
