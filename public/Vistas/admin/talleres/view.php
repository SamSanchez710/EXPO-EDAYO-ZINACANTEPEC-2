<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/TallerController.php';
$controller = new TallerController();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$taller = $id ? $controller->get($id) : null;

if (!$taller) {
    echo "Taller no encontrado";
    exit();
}
?>

<h2>Detalles del Taller</h2>
<p><strong>Imagen:</strong><br>
<?php if ($taller['imagen']): ?>
    <img src="data:image/jpeg;base64,<?= base64_encode($taller['imagen']) ?>" width="150">
<?php else: ?>
    Sin imagen
<?php endif; ?>
</p>

<p><strong>ID:</strong> <?= $taller['id'] ?></p>
<p><strong>Nombre:</strong> <?= htmlspecialchars($taller['nombre']) ?></p>
<p><strong>Descripción:</strong> <?= htmlspecialchars($taller['descripcion']) ?></p>
<p><strong>Hora Inicio:</strong> <?= $taller['hora_inicio'] ?></p>
<p><strong>Hora Fin:</strong> <?= $taller['hora_fin'] ?></p>
<p><strong>Lugar:</strong> <?= htmlspecialchars($taller['lugar']) ?></p>
<p><strong>Activo:</strong> <?= $taller['activo'] ? 'Sí' : 'No' ?></p>

<h3>Mesas de Trabajo Asociadas</h3>
<?php if (!empty($taller['mesas'])): ?>
    <ul>
        <?php foreach ($taller['mesas'] as $m): ?>
            <li><?= htmlspecialchars($m['nombre_mesa']) ?> - <?= htmlspecialchars($m['persona_cargo']) ?> (<?= $m['hora_especifica'] ?>, <?= htmlspecialchars($m['lugar_area']) ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay mesas asociadas a este taller.</p>
<?php endif; ?>

<button onclick="closeModal()">Cerrar</button>
