<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/TallerController.php';
$controller = new TallerController();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$taller = $id ? $controller->get($id) : null;

// Guardar vía AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    $imagen = $taller['imagen'] ?? null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] != '') {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    $data = [
        'nombre' => $_POST['nombre'],
        'descripcion' => $_POST['descripcion'],
        'imagen' => $imagen,
        'hora_inicio' => $_POST['hora_inicio'],
        'hora_fin' => $_POST['hora_fin'],
        'lugar' => $_POST['lugar'],
        'activo' => isset($_POST['activo']) ? 1 : 0
    ];

    if ($taller) {
        $controller->update($id, $data);
    } else {
        $controller->create($data);
    }

    echo json_encode(['status' => 'success']);
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
    <img id="previewImagen"
         src="<?= $taller && $taller['imagen'] ? 'data:image/jpeg;base64,' . base64_encode($taller['imagen']) : '' ?>"
         width="100"
         style="<?= $taller && $taller['imagen'] ? '' : 'display:none;' ?>"
         alt="Imagen"><br>
    <input type="file" name="imagen" accept="image/*" id="imagenInput"><br><br>

    <label>Hora Inicio:</label><br>
    <input type="time" name="hora_inicio" value="<?= $taller['hora_inicio'] ?? '10:00' ?>"><br>

    <label>Hora Fin:</label><br>
    <input type="time" name="hora_fin" value="<?= $taller['hora_fin'] ?? '12:00' ?>"><br>

    <label>Lugar:</label><br>
    <input type="text" name="lugar" value="<?= $taller['lugar'] ?? '' ?>"><br>

    <label>Activo:</label>
    <input type="checkbox" name="activo" <?= isset($taller['activo']) && $taller['activo'] ? 'checked' : '' ?>><br><br>

    <button type="submit"><?= $taller ? 'Actualizar' : 'Guardar' ?></button>
    <button type="button" onclick="closeModal()">Cancelar</button>
</form>

<script>
document.getElementById('tallerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('talleres/form.php<?= $taller ? "?id=" . $taller['id'] : "" ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Taller guardado correctamente');
            closeModal();
            loadSection('talleres/index.php');
        } else {
            alert('Error al guardar el taller');
        }
    });
});
</script>
