<?php
require_once __DIR__ . '/../../../../app/models/ReportesModel.php';

$model = new ReportesModel();

// Recibir fecha
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;

$inscripciones = $model->obtenerInscripcionesPorFecha($fecha);

// Nombre archivo según fecha o "inscripciones"
$nombre_archivo = $fecha ? "inscripciones_" . $fecha : "inscripciones";

// Encabezados para Excel con UTF-8
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".urlencode($nombre_archivo).".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
echo "<table border='1'>";
echo "<tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>
        <th>Edad</th>
        <th>Municipio</th>
        <th>Email</th>
        <th>Teléfono</th>
        <th>Taller</th>
        <th>Mesa de Trabajo</th>
        <th>Fecha Registro</th>
      </tr>";

foreach($inscripciones as $i){
    echo "<tr>
            <td>{$i['id']}</td>
            <td>{$i['nombre']}</td>
            <td>{$i['apellido_paterno']}</td>
            <td>{$i['apellido_materno']}</td>
            <td>{$i['edad']}</td>
            <td>{$i['municipio']}</td>
            <td>{$i['email']}</td>
            <td>{$i['telefono']}</td>
            <td>{$i['taller_seleccionado']}</td>
            <td>{$i['mesa_trabajo']}</td>
            <td>{$i['fecha_registro']}</td>
          </tr>";
}
echo "</table>";
?>
