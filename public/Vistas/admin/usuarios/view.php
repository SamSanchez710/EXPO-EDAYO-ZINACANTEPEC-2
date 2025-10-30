<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/UserController.php';

$controller = new UserController();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$usuario = $id ? $controller->get($id) : null;

if(!$usuario){
    echo "Usuario no encontrado";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Usuario</title>
</head>
<body>
<h1>Detalles del Usuario</h1>

<p><strong>Foto:</strong><br>
<?php if($usuario['foto_perfil']): ?>
    <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_perfil']) ?>" width="150" alt="Foto">
<?php else: ?>
    <span>No hay foto</span>
<?php endif; ?>
</p>

<p><strong>ID:</strong> <?= $usuario['id'] ?></p>
<p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre'].' '.$usuario['apellido_paterno'].' '.$usuario['apellido_materno']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
<p><strong>Tipo:</strong> <?= $usuario['tipo_usuario'] ?></p>
<p><strong>Activo:</strong> <?= $usuario['activo'] ? 'Sí' : 'No' ?></p>

<button onclick="window.location.href='index.php?tipo=<?= $_GET['tipo'] ?? 'all' ?>'">Volver</button>


</body>
</html>
