<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/ReportesController.php';

$controller = new ReportesController();
$data = $controller->index();

$inscripciones = $data['inscripciones'];
$estadisticas = $data['estadisticas'];
$fechas = $data['fechas'];
$filtro_fecha = $data['filtro_fecha'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Inscripciones</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .filtro { margin-bottom: 20px; }
        .estadisticas { margin-top: 20px; background-color: #f9f9f9; padding: 10px; border: 1px solid #ccc; width: fit-content; }
    </style>
</head>
<body>
<h1>Reportes de Inscripciones</h1>

<!-- FILTROS -->
<div class="filtro">
    <form id="filtroForm" method="get" action="">
        <label>Seleccionar fecha:</label>
        <input type="date" name="fecha" value="<?= $filtro_fecha ?? '' ?>">

        <button type="button" id="filtrarBtn">Filtrar</button>
        <button type="button" id="limpiarBtn">Limpiar</button>

        <?php if ($filtro_fecha): ?>
            <button type="button" id="descargarBtn">Descargar Excel</button>
        <?php else: ?>
            <button type="button" id="descargarBtn">Descargar Todo</button>
        <?php endif; ?>
    </form>
</div>

<!-- ESTADÍSTICAS -->
<div id="contenedorEstadisticas">
<?php if($estadisticas): ?>
<div class="estadisticas">
    <strong>Total inscritos:</strong> <?= $estadisticas['total_inscritos'] ?><br>
    <strong>Talleres con inscritos:</strong> <?= $estadisticas['total_talleres'] ?><br>
    <strong>Mesas de trabajo activas:</strong> <?= $estadisticas['total_mesas'] ?><br>
</div>
<?php endif; ?>
</div>

<!-- TABLA -->
<div id="tablaReportes">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre completo</th>
            <th>Edad</th>
            <th>Municipio</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Taller seleccionado</th>
            <th>Mesa de trabajo</th>
            <th>Fecha registro</th>
            <th>Activo</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($inscripciones) == 0): ?>
            <tr><td colspan="10">No se encontraron registros.</td></tr>
        <?php else: ?>
            <?php foreach($inscripciones as $i): ?>
                <tr>
                    <td><?= $i['id'] ?></td>
                    <td><?= htmlspecialchars($i['nombre'].' '.$i['apellido_paterno'].' '.$i['apellido_materno']) ?></td>
                    <td><?= $i['edad'] ?></td>
                    <td><?= htmlspecialchars($i['municipio']) ?></td>
                    <td><?= htmlspecialchars($i['email']) ?></td>
                    <td><?= htmlspecialchars($i['telefono']) ?></td>
                    <td><?= htmlspecialchars($i['taller_seleccionado']) ?><?= $i['nombre_taller'] ? ' ('.$i['nombre_taller'].')' : '' ?></td>
                    <td><?= htmlspecialchars($i['mesa_trabajo']) ?></td>
                    <td><?= $i['fecha_registro'] ?></td>
                    <td><?= $i['activo'] ? 'Sí' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>

<script>
(() => {
  const root = document.getElementById('dynamicSection') || document;

  function aplicarFiltro() {
    const form = root.querySelector('#filtroForm');
    if (!form) return;

    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();

    const tabla = root.querySelector('#tablaReportes');
    const contEstad = root.querySelector('#contenedorEstadisticas');

    if (tabla) tabla.innerHTML = '<p style="text-align:center;">Cargando...</p>';

    fetch('reportes/index.php?' + params, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
      if (!res.ok) throw new Error('Error al cargar reportes');
      return res.text();
    })
    .then(html => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const nuevaTabla = doc.querySelector('#tablaReportes');
      const nuevasEst = doc.querySelector('#contenedorEstadisticas');

      if (tabla && nuevaTabla) tabla.innerHTML = nuevaTabla.innerHTML;
      if (contEstad && nuevasEst) contEstad.innerHTML = nuevasEst.innerHTML;
    })
    .catch(err => {
      if (tabla) tabla.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
    });
  }

  function limpiarFiltros() {
    const form = root.querySelector('#filtroForm');
    if (form) form.reset();
    aplicarFiltro();
  }

  function descargarExcel() {
    const fecha = root.querySelector('[name="fecha"]').value;
    let url = 'reportes/export_excel.php';
    if (fecha) url += '?fecha=' + encodeURIComponent(fecha);
    window.location.href = url;
  }

  const btnFiltrar = root.querySelector('#filtrarBtn');
  const btnLimpiar = root.querySelector('#limpiarBtn');
  const btnDescargar = root.querySelector('#descargarBtn');

  if (btnFiltrar) btnFiltrar.addEventListener('click', aplicarFiltro);
  if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFiltros);
  if (btnDescargar) btnDescargar.addEventListener('click', descargarExcel);
})();
</script>

</body>
</html>
