<?php
require_once __DIR__ . '/../../../../app/controllers/InscripcionesController.php';

$controller = new InscripcionesController();

// Recibir parámetros
$tipo = $_GET['tipo'] ?? 'all';
$taller = $_GET['taller'] ?? '';
$mesa = $_GET['mesa'] ?? '';
$search = $_GET['busqueda'] ?? '';

// Obtener inscripciones filtradas
$inscripciones = $controller->index()['inscripciones'];
if($tipo === 'taller' && $taller){
    $inscripciones = array_filter($inscripciones, fn($i) => $i['taller_seleccionado'] === $taller);
}
if($tipo === 'mesa' && $mesa){
    $inscripciones = array_filter($inscripciones, fn($i) => $i['mesa_trabajo'] === $mesa);
}
if($search){
    $inscripciones = array_filter($inscripciones, fn($i) => 
        stripos($i['nombre'], $search)!==false ||
        stripos($i['apellido_paterno'], $search)!==false ||
        stripos($i['apellido_materno'], $search)!==false ||
        stripos($i['email'], $search)!==false
    );
}

// Determinar nombre del archivo
$filename = 'inscripciones.xls';
if($tipo === 'taller' && $taller){
    $filename = $taller . '.xls';
}
if($tipo === 'mesa' && $mesa){
    $filename = $mesa . '.xls';
}

// Reemplazar caracteres problemáticos de manera segura
$filename_sanitizado = str_replace(['/', '\\', '?', '%', '*', ':', '|', '"', '<', '>'], '_', $filename);

// Cabeceras con soporte para UTF-8
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename_sanitizado));
header("Pragma: no-cache");
header("Expires: 0");

// Crear tabla
// Forzar UTF-8 dentro del Excel
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
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
            <td>".htmlspecialchars($i['id'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['nombre'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['apellido_paterno'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['apellido_materno'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['edad'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['municipio'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['email'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['telefono'], ENT_QUOTES, 'UTF-8')."</td>
            <td>{$i['taller_seleccionado']}</td>
            <td>".htmlspecialchars($i['mesa_trabajo'], ENT_QUOTES, 'UTF-8')."</td>
            <td>".htmlspecialchars($i['fecha_registro'], ENT_QUOTES, 'UTF-8')."</td>
          </tr>";
}
echo "</table>";
exit;
