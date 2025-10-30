<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/TallerController.php';

$controller = new TallerController();

if(isset($_GET['delete_id'])){
    $controller->delete(intval($_GET['delete_id']));
    header("Location: index.php");
    exit();
}

$talleres = $controller->list();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Talleres</title>
<style>
#modalOverlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);justify-content:center;align-items:center;}
#modalContent{background:#fff;padding:20px;max-width:800px;width:90%;}
img.taller-img{width:50px;height:50px;}
</style>
</head>
<body>
<h1>Talleres Registrados</h1>
<button onclick="openModal('form.php')">Agregar Taller</button>

<table border="1" cellpadding="5" cellspacing="0">
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
<?php foreach($talleres as $t): ?>
<tr>
<td>
<?php if($t['imagen']): ?>
<img class="taller-img" src="data:image/jpeg;base64,<?= base64_encode($t['imagen']) ?>" alt="Imagen">
<?php else: ?>
<span>No hay imagen</span>
<?php endif; ?>
</td>
<td><?= $t['id'] ?></td>
<td><?= htmlspecialchars($t['nombre']) ?></td>
<td><?= htmlspecialchars($t['descripcion']) ?></td>
<td><?= $t['hora_inicio'].' - '.$t['hora_fin'] ?></td>
<td><?= htmlspecialchars($t['lugar']) ?></td>
<td><?= $t['activo'] ? 'Sí' : 'No' ?></td>
<td>
<button onclick="openModal('form.php?id=<?= $t['id'] ?>')">Editar</button>
<button onclick="if(confirm('Eliminar taller?')) window.location.href='index.php?delete_id=<?= $t['id'] ?>'">Eliminar</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div id="modalOverlay">
<div id="modalContent"></div>
</div>

<script>
function openModal(url){
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');
    fetch(url).then(res=>res.text()).then(html=>{
        content.innerHTML = html;
        overlay.style.display = 'flex';
    });
}
function closeModal(){
    document.getElementById('modalOverlay').style.display='none';
    document.getElementById('modalContent').innerHTML='';
}
</script>

<script>
window.addRow = function(){
    const table = document.querySelector('#modalContent tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="mesas[nombre_mesa][]"></td>
        <td><input type="text" name="mesas[persona_cargo][]"></td>
        <td><input type="time" name="mesas[hora_especifica][]"></td>
        <td><input type="text" name="mesas[lugar_area][]"></td>
        <td><button type="button" onclick="this.closest('tr').remove()">Eliminar</button></td>
    `;
    table.appendChild(row);
};
</script>


</body>
</html>
