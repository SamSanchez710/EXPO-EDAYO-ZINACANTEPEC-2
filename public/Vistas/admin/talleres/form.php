<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/TallerController.php';

$controller = new TallerController();
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$taller = $id ? $controller->get($id) : null;

// Guardar vía AJAX
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['ajax']) && $_POST['ajax']==1){
    $mesas = [];
    if(isset($_POST['mesas'])){
        foreach($_POST['mesas']['nombre_mesa'] as $k=>$nombre){
            $mesas[]=[
                'nombre_mesa'=>$nombre,
                'persona_cargo'=>$_POST['mesas']['persona_cargo'][$k] ?? '',
                'hora_especifica'=>$_POST['mesas']['hora_especifica'][$k] ?? '',
                'lugar_area'=>$_POST['mesas']['lugar_area'][$k] ?? ''
            ];
        }
    }

    $imagen = $taller['imagen'] ?? null;
    if(isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name']){
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    $data = [
        'nombre'=>$_POST['nombre'],
        'descripcion'=>$_POST['descripcion'],
        'imagen'=>$imagen,
        'hora_inicio'=>$_POST['hora_inicio'],
        'hora_fin'=>$_POST['hora_fin'],
        'lugar'=>$_POST['lugar'],
        'activo'=>isset($_POST['activo'])?1:0
    ];

    if($taller){
        $controller->update($id, $data, $mesas);
    } else {
        $controller->create($data, $mesas);
    }

    echo json_encode(['status'=>'success']);
    exit();
}
?>

<h2><?= $taller ? 'Editar Taller' : 'Agregar Taller' ?></h2>

<form id="tallerForm" enctype="multipart/form-data">
<input type="hidden" name="ajax" value="1">

<label>Nombre:</label><br>
<input type="text" name="nombre" value="<?= $taller['nombre'] ?? '' ?>" required><br>

<label>Descripción:</label><br>
<textarea name="descripcion" required><?= $taller['descripcion'] ?? '' ?></textarea><br>

<label>Imagen:</label><br>
<?php if($taller && $taller['imagen']): ?>
<img src="data:image/jpeg;base64,<?= base64_encode($taller['imagen']) ?>" width="100"><br>
<?php endif; ?>
<input type="file" name="imagen" accept="image/*"><br>

<label>Hora Inicio:</label><br>
<input type="time" name="hora_inicio" value="<?= $taller['hora_inicio'] ?? '10:00' ?>"><br>

<label>Hora Fin:</label><br>
<input type="time" name="hora_fin" value="<?= $taller['hora_fin'] ?? '12:00' ?>"><br>

<label>Lugar:</label><br>
<input type="text" name="lugar" value="<?= $taller['lugar'] ?? '' ?>"><br>

<label>Activo:</label>
<input type="checkbox" name="activo" <?= isset($taller['activo']) && $taller['activo'] ? 'checked' : '' ?>><br><br>

<h3>Mesas de Trabajo</h3>
<table id="mesasTable" border="1" cellpadding="5">
<thead>
<tr>
<th>Nombre Mesa</th>
<th>Persona a Cargo</th>
<th>Hora Específica</th>
<th>Lugar/Área</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<?php if($taller && !empty($taller['mesas'])): ?>
<?php foreach($taller['mesas'] as $m): ?>
<tr>
<td><input type="text" name="mesas[nombre_mesa][]" value="<?= $m['nombre_mesa'] ?>"></td>
<td><input type="text" name="mesas[persona_cargo][]" value="<?= $m['persona_cargo'] ?>"></td>
<td><input type="time" name="mesas[hora_especifica][]" value="<?= $m['hora_especifica'] ?>"></td>
<td><input type="text" name="mesas[lugar_area][]" value="<?= $m['lugar_area'] ?>"></td>
<td><button type="button" onclick="removeRow(this)">Eliminar</button></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
<button type="button" onclick="addRow()">Agregar Mesa</button><br><br>

<button type="submit"><?= $taller ? 'Actualizar' : 'Guardar' ?></button>
<button type="button" onclick="closeModal()">Cancelar</button>
</form>

<script>
function addRow(){
    const table = document.getElementById('mesasTable').getElementsByTagName('tbody')[0];
    const row = table.insertRow();
    row.innerHTML = `
    <td><input type="text" name="mesas[nombre_mesa][]"></td>
    <td><input type="text" name="mesas[persona_cargo][]"></td>
    <td><input type="time" name="mesas[hora_especifica][]"></td>
    <td><input type="text" name="mesas[lugar_area][]"></td>
    <td><button type="button" onclick="removeRow(this)">Eliminar</button></td>
    `;
}

function removeRow(btn){
    btn.closest('tr').remove();
}

document.getElementById('tallerForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('form.php<?= $taller ? "?id=".$taller['id'] : "" ?>',{
        method:'POST',
        body:formData
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==='success'){
            alert('Taller guardado correctamente');
            closeModal();
            loadSection('talleres/index.php');
        } else {
            alert('Error al guardar el taller');
        }
    });
});
</script>
