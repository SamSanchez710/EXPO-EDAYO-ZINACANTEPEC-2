<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/MesaController.php';

$controller = new MesaController();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$mesa = $id ? $controller->get($id) : null;

if(!$mesa){
    echo "Mesa no encontrada";
    exit();
}

$talleres = $controller->getTalleres();
$taller_nombre = '';
foreach($talleres as $t){
    if($t['id'] == $mesa['taller_id']){
        $taller_nombre = $t['nombre'];
        break;
    }
}
?>

<h2>Detalles de la Mesa</h2>

<p><strong>ID:</strong> <?= $mesa['id'] ?></p>
<p><strong>Nombre Mesa:</strong> <?= htmlspecialchars($mesa['nombre_mesa']) ?></p>
<p><strong>Persona a cargo:</strong> <?= htmlspecialchars($mesa['persona_cargo']) ?></p>
<p><strong>Hora específica:</strong> <?= $mesa['hora_especifica'] ?></p>
<p><strong>Lugar/Área:</strong> <?= htmlspecialchars($mesa['lugar_area']) ?></p>
<p><strong>Taller:</strong> <?= htmlspecialchars($taller_nombre) ?></p>

<button onclick="closeModal()">Cerrar</button>
