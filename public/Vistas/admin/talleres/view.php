<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/TallerController.php';

$controller = new TallerController();
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$taller = $id ? $controller->get($id) : null;

if(!$taller){
    echo "Taller no encontrado";
    exit();
}
?>

<h2>Detalles del Taller</h2>
<p><strong>Imagen:</strong><br>
<?php if($taller['imagen']): ?>
    <img src="data:image/jpeg;base64,<?= base64_encode($taller['imagen']) ?>" width="150">
<?php else: ?>
    <span>No hay imagen</span>
<?php endif; ?>
</p>
<p><strong>ID:</strong> <?= $taller['id'] ?></p>
<p><strong>Nombre:</strong> <?= htmlspecialchars($taller['nombre']) ?></p>
<p><strong>Descripción:</strong> <?= htmlspecialchars($taller['descripcion']) ?></p>
<p><strong>Horario:</strong> <?= $taller['hora_inicio'].' - '.$taller['hora_fin'] ?></p>
<p><strong>Lugar:</strong> <?= htmlspecialchars($taller['lugar']) ?></p>
<p><strong>Activo:</strong> <?= $taller['activo'] ? 'Sí' : 'No' ?></p>

<button onclick="closeModal()">Volver</button>
