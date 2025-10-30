<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/MesaController.php';

$controller = new MesaController();

// Eliminar
if(isset($_GET['delete_id'])) {
    $controller->delete(intval($_GET['delete_id']));
    header("Location: mesas_index.php");
    exit();
}

// Obtener lista de mesas
$mesas = $controller->list();
?>

<h1>Mesas de Trabajo</h1>
<button onclick="openModal('mesas_form.php')">Agregar Mesa</button>

<table border="1" cellpadding="5" cellspacing="0">
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
                <button onclick="openModal('mesas_ver.php?id=<?= $mesa['id'] ?>')">Ver</button>
                <button onclick="openModal('mesas_form.php?id=<?= $mesa['id'] ?>')">Editar</button>
                <button onclick="if(confirm('Eliminar mesa?')) window.location.href='mesas_index.php?delete_id=<?= $mesa['id'] ?>'">Eliminar</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal -->
<div id="modalOverlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);justify-content:center;align-items:center;">
    <div id="modalContent" style="background:#fff;padding:20px;max-width:600px;width:90%;"></div>
</div>

<script>
function openModal(url){
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');

    fetch(url)
    .then(response => response.text())
    .then(html => {
        content.innerHTML = html;
        overlay.style.display = 'flex';
    });
}

function closeModal(){
    const overlay = document.getElementById('modalOverlay');
    overlay.style.display = 'none';
    document.getElementById('modalContent').innerHTML = '';
}
</script>
