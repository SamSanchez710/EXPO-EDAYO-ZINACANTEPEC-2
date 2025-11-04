<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/MesaController.php';

$controller = new MesaController();

// Eliminar mesa
if(isset($_GET['delete_id'])){
    $controller->delete(intval($_GET['delete_id']));
    header("Location: mesas_index.php");
    exit();
}

// Obtener lista de mesas
$mesas = $controller->list();
?>

<h2>Mesas de Trabajo</h2>
<button onclick="openModal('mesas/mesas_form.php')">Agregar Mesa</button>

<table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%;">
<thead>
<tr>
<th>ID</th>
<th>Nombre Mesa</th>
<th>Persona a cargo</th>
<th>Hora</th>
<th>Lugar/Área</th>
<th>Taller</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach($mesas as $mesa): ?>
<tr>
<td><?= $mesa['id'] ?></td>
<td><?= htmlspecialchars($mesa['nombre_mesa']) ?></td>
<td><?= htmlspecialchars($mesa['persona_cargo']) ?></td>
<td><?= $mesa['hora_especifica'] ?></td>
<td><?= htmlspecialchars($mesa['lugar_area']) ?></td>
<td><?= htmlspecialchars($mesa['taller_nombre']) ?></td>
<td>
<button onclick="openModal('mesas/mesas_ver.php?id=<?= $mesa['id'] ?>')">Ver</button>
<button onclick="openModal('mesas/mesas_form.php?id=<?= $mesa['id'] ?>')">Editar</button>
<button onclick="if(confirm('Eliminar mesa?')) loadSection('mesas/mesas_index.php?delete_id=<?= $mesa['id'] ?>')">Eliminar</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
