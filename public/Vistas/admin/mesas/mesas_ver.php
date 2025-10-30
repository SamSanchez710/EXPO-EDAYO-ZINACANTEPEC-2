<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/MesasController.php';

$controller = new MesasController();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$mesa = $id ? $controller->get($id) : null;

if(!$mesa){
    echo "Mesa no encontrada";
    exit();
}
?>

<h1>Detalles de la Mesa</h1>
<p><strong>ID:</strong> <?= $mesa['id'] ?></p>
<p><strong>Nombre de Mesa:</strong> <?= htmlspecialchars($mesa['nombre_mesa']) ?></p>
<p><strong>Taller:</strong> <?= htmlspecialchars($mesa['nombre_taller'] ?? 'No asignado') ?></p>
<p><strong>Persona a Cargo:</strong> <?= htmlspecialchars($mesa['persona_cargo']) ?></p>
<p><strong>Hora:</strong> <?= $mesa['hora_especifica'] ?></p>
<p><strong>Lugar/Área:</strong> <?= htmlspecialchars($mesa['lugar_area']) ?></p>

<button onclick="closeModal()">Volver</button>
