<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/MesasController.php';

$controller = new MesasController();
$talleres = $controller->getTalleres();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$mesa = $id ? $controller->get($id) : null;

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['ajax']) && $_POST['ajax']==1){
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

<h2><?= $mesa ? 'Editar Mesa' : 'Agregar Nueva Mesa' ?></h2>

<form id="mesasForm">
    <input type="hidden" name="id" value="<?= $mesa['id'] ?? '' ?>">
    <input type="hidden" name="ajax" value="1">

    <label>Nombre de Mesa:</label><br>
    <input type="text" name="nombre_mesa" value="<?= $mesa['nombre_mesa'] ?? '' ?>" required><br>

    <label>Taller:</label><br>
    <select name="taller_id" required>
        <option value="">Seleccione un taller</option>
        <?php foreach($talleres as $taller): ?>
            <option value="<?= $taller['id'] ?>" <?= ($mesa['taller_id'] ?? '')==$taller['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($taller['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Persona a Cargo:</label><br>
    <input type="text" name="persona_cargo" value="<?= $mesa['persona_cargo'] ?? '' ?>" required><br>

    <label>Hora:</label><br>
    <input type="time" name="hora_especifica" value="<?= $mesa['hora_especifica'] ?? '' ?>" required><br>

    <label>Lugar/Área:</label><br>
    <input type="text" name="lugar_area" value="<?= $mesa['lugar_area'] ?? '' ?>"><br><br>

    <button type="submit"><?= $mesa ? 'Actualizar' : 'Guardar' ?></button>
    <button type="button" onclick="closeModal()">Cancelar</button>
</form>

<script>
document.getElementById('mesasForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch('mesas_form.php<?= $mesa ? "?id=".$mesa['id'] : "" ?>',{
        method:'POST',
        body: formData
    }).then(res=>res.json())
      .then(data=>{
        if(data.status==='success'){
            alert('Mesa guardada correctamente');
            closeModal();
            fetch('mesas_index.php')
                .then(res=>res.text())
                .then(html=>{
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html,'text/html');
                    const newTable = doc.querySelector('table');
                    document.querySelector('table').replaceWith(newTable);
                });
        } else {
            alert('Error al guardar la mesa');
        }
      });
});
</script>
