<?php
require_once __DIR__ . '/../../../../app/controllers/InscripcionesController.php';

$controller = new InscripcionesController();

// === 1️⃣ RECIBIR PARÁMETROS ===
$tipo   = $_GET['tipo']   ?? 'all';
$taller = trim($_GET['taller'] ?? '');
$mesa   = trim($_GET['mesa'] ?? '');
$search = trim($_GET['busqueda'] ?? '');

// === 2️⃣ OBTENER DATOS ===
$data = $controller->index();
$inscripciones = $data['inscripciones'] ?? [];

// === 3️⃣ APLICAR FILTROS ===
if ($tipo === 'taller' && $taller !== '') {
    $inscripciones = array_filter($inscripciones, fn($i) =>
        ($i['taller_seleccionado'] ?? $i['nombre_taller'] ?? '') === $taller
    );
}

if ($tipo === 'mesa' && $mesa !== '') {
    $inscripciones = array_filter($inscripciones, fn($i) =>
        ($i['mesa_trabajo'] ?? '') === $mesa
    );
}

if ($search !== '') {
    $inscripciones = array_filter($inscripciones, fn($i) =>
        stripos($i['nombre'] ?? '', $search) !== false ||
        stripos($i['apellido_paterno'] ?? '', $search) !== false ||
        stripos($i['apellido_materno'] ?? '', $search) !== false ||
        stripos($i['email'] ?? '', $search) !== false
    );
}

// === 4️⃣ NOMBRE DEL ARCHIVO ===
switch ($tipo) {
    case 'taller':
        $filename = ($taller ?: 'Taller') . '.xls';
        break;
    case 'mesa':
        $filename = ($mesa ?: 'Mesa') . '.xls';
        break;
    default:
        $filename = 'Inscripciones.xls';
        break;
}

// Limpiar caracteres peligrosos
$filename = str_replace(['/', '\\', '?', '%', '*', ':', '|', '"', '<', '>'], '_', $filename);

// === 5️⃣ CABECERAS PARA DESCARGA ===
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
header("Pragma: no-cache");
header("Expires: 0");

// === 6️⃣ GENERAR TABLA HTML (para Excel) ===
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
echo "<table border='1' cellspacing='0' cellpadding='5'>";
echo "<thead style='background:#f2f2f2; font-weight:bold;'>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Edad</th>
            <th>Municipio</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Taller Seleccionado</th>
            <th>Mesa de Trabajo</th>
            <th>Fecha Registro</th>
            <th>Activo</th>
        </tr>
      </thead>
      <tbody>";

if (empty($inscripciones)) {
    echo "<tr><td colspan='10' style='text-align:center;'>No se encontraron registros.</td></tr>";
} else {
    foreach ($inscripciones as $i) {
        $nombreCompleto = trim(($i['nombre'] ?? '') . ' ' . ($i['apellido_paterno'] ?? '') . ' ' . ($i['apellido_materno'] ?? ''));
        echo "<tr>
                <td>" . htmlspecialchars($i['id'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($i['edad'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($i['municipio'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($i['email'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($i['telefono'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($i['taller_seleccionado'] ?? $i['nombre_taller'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($i['mesa_trabajo'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($i['fecha_registro'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . ((isset($i['activo']) && $i['activo']) ? 'Sí' : 'No') . "</td>
              </tr>";
    }
}
echo "</tbody></table>";
exit;
