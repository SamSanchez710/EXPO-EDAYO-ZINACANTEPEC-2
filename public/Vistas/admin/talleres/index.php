<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/TallerController.php';
$controller = new TallerController();

// Eliminar taller
if (isset($_GET['delete_id'])) {
    $controller->delete(intval($_GET['delete_id']));
    header("Location: index.php");
    exit();
}

$talleres = $controller->list();
?>
<h2>Talleres Registrados</h2>

<button onclick="openModal('talleres/form.php')">Agregar Taller</button>

<table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%;">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Horario</th>
            <th>Lugar</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($talleres as $t): ?>
        <tr>
            <td>
                <?php if ($t['imagen']): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($t['imagen']) ?>" width="50" height="50">
                <?php else: ?>
                    <span>No hay imagen</span>
                <?php endif; ?>
            </td>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['nombre']) ?></td>
            <td><?= htmlspecialchars($t['descripcion']) ?></td>
            <td><?= $t['hora_inicio'] . ' - ' . $t['hora_fin'] ?></td>
            <td><?= htmlspecialchars($t['lugar']) ?></td>
            <td><?= $t['activo'] ? 'Sí' : 'No' ?></td>
            <td>
                <button onclick="openModal('talleres/view.php?id=<?= $t['id'] ?>')">Ver</button>
                <button onclick="openModal('talleres/form.php?id=<?= $t['id'] ?>')">Editar</button>
                <button onclick="if(confirm('¿Eliminar taller?')) loadSection('talleres/index.php?delete_id=<?= $t['id'] ?>')">Eliminar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
