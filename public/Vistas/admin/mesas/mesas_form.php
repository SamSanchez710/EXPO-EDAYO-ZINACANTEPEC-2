<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/MesaController.php';

$controller = new MesaController();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$mesa = $id ? $controller->get($id) : null;
$talleres = $controller->getTalleres();

// Guardar datos vía AJAX
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax']==1){
    $data = [
        'taller_id' => $_POST['taller_id'],
        'nombre_mesa' => $_POST['nombre_mesa'],
        'persona_cargo' => $_POST['persona_cargo'],
        'hora_especifica' => $_POST['hora_especifica'],
        'lugar_area' => $_POST['lugar_area']
    ];

    if(!empty($_POST['id'])){
        $controller->update(intval($_POST['id']), $data);
    } else {
        $controller->create($data);
    }

    echo json_encode(['status'=>'success']);
    exit();
}
?>

<h2><?= $mesa ? 'Editar Mesa' : 'Agregar Mesa' ?></h2>

<form id="mesaForm">
    <input type="hidden" name="id" value="<?= $mesa['id'] ?? '' ?>">
    <input type="hidden" name="ajax" value="1">

    <label>Taller:</label><br>
    <select name="taller_id" required>
        <option value="">Selecciona un taller</option>
        <?php foreach($talleres as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($mesa['taller_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Nombre Mesa:</label><br>
    <input type="text" name="nombre_mesa" value="<?= $mesa['nombre_mesa'] ?? '' ?>" required><br>

    <label>Persona a cargo:</label><br>
    <input type="text" name="persona_cargo" value="<?= $mesa['persona_cargo'] ?? '' ?>" required><br>

    <label>Hora específica:</label><br>
    <input type="time" name="hora_especifica" value="<?= $mesa['hora_especifica'] ?? '' ?>"><br>

    <label>Lugar/Área:</label><br>
    <input type="text" name="lugar_area" value="<?= $mesa['lugar_area'] ?? '' ?>"><br><br>

    <button type="submit"><?= $mesa ? 'Actualizar' : 'Guardar' ?></button>
    <button type="button" onclick="closeModal()">Cancelar</button>
</form>

<script>
document.getElementById('mesaForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('mesas_form.php<?= $mesa ? "?id=".$mesa['id'] : "" ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            alert('Mesa guardada correctamente');
            closeModal();
            loadSection('mesas/mesas_index.php'); // recargar tabla dinámica
        } else {
            alert('Error al guardar la mesa');
        }
    });
});
</script>
