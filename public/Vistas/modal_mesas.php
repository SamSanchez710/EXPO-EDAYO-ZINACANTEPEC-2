<?php
require_once __DIR__ . '/../../app/controllers/TalleresInicioController.php';

if(!isset($_GET['taller_id'])) exit;

$taller_id = intval($_GET['taller_id']);
$controller = new TalleresInicioController();
$mesas = $controller->getMesas($taller_id);

if(count($mesas) == 0){
    echo "<p>No hay mesas registradas para este taller.</p>";
} else {
    echo "<table border='1' cellpadding='5' cellspacing='0' style='width:100%;'>";
    echo "<tr>
            <th>Nombre Mesa</th>
            <th>Persona a Cargo</th>
            <th>Hora Específica</th>
            <th>Lugar/Área</th>
          </tr>";
    foreach($mesas as $m){
        echo "<tr>
                <td>".htmlspecialchars($m['nombre_mesa'])."</td>
                <td>".htmlspecialchars($m['persona_cargo'])."</td>
                <td>".htmlspecialchars($m['hora_especifica'])."</td>
                <td>".htmlspecialchars($m['lugar_area'])."</td>
              </tr>";
    }
    echo "</table>";
}
?>
