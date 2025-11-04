<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/InscripcionesController.php';

$controller = new InscripcionesController();
$data = $controller->index();

$inscripciones = $data['inscripciones'];
$talleres = $data['talleres'];
$mesas = $data['mesas'];
$filtros = $data['filtros'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripciones</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .filtro { margin-bottom: 20px; }
        .export-menu { display:none; position:absolute; background:#fff; border:1px solid #ccc; padding:10px; }
    </style>
</head>
<body>
<h1>Inscripciones</h1>

<!-- Filtros -->
<div class="filtro">
    <form id="filtroForm" method="get" action="">
        <label>Taller:</label>
        <select name="taller_id">
            <option value="">Todos</option>
            <?php foreach($talleres as $t): ?>
                <option value="<?= $t['id'] ?>" <?= ($filtros['taller_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['nombre']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Mesa de trabajo:</label>
        <select name="mesa_trabajo">
            <option value="">Todas</option>
            <?php foreach($mesas as $m): ?>
                <option value="<?= htmlspecialchars($m['nombre_mesa']) ?>" <?= ($filtros['mesa_trabajo'] ?? '') == $m['nombre_mesa'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nombre_mesa']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Buscar:</label>
        <input type="text" name="search" value="<?= htmlspecialchars($filtros['search'] ?? '') ?>" placeholder="Nombre, Apellido o Email">

        <button type="button" id="filtrarBtn">Filtrar</button>
        <button type="button" id="limpiarBtn">Limpiar</button>
    </form>
</div>

<!-- Botón Exportar -->
<div style="margin-bottom:20px; position:relative;">
    <button onclick="toggleExportMenu()">Exportar a Excel ▼</button>
    <div id="exportMenu" class="export-menu">
        <button onclick="exportExcel('all')">Todo</button><br>
        <button onclick="showFilter('taller')">Por Taller</button><br>
        <button onclick="showFilter('mesa')">Por Mesa de Trabajo</button>
    </div>

    <!-- Select ocultos para elegir taller o mesa -->
    <div id="filterTaller" style="display:none; margin-top:5px;">
        <select id="tallerSelect">
            <option value="">Selecciona un Taller</option>
            <?php foreach($talleres as $t): ?>
                <option value="<?= htmlspecialchars($t['nombre']) ?>"><?= htmlspecialchars($t['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <button onclick="exportExcel('taller')">Exportar</button>
    </div>

    <div id="filterMesa" style="display:none; margin-top:5px;">
        <select id="mesaSelect">
            <option value="">Selecciona una Mesa</option>
            <?php foreach($mesas as $m): ?>
                <option value="<?= htmlspecialchars($m['nombre_mesa']) ?>"><?= htmlspecialchars($m['nombre_mesa']) ?></option>
            <?php endforeach; ?>
        </select>
        <button onclick="exportExcel('mesa')">Exportar</button>
    </div>
</div>

<!-- Tabla de inscripciones -->
<table id="tablaInscripciones">
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
                    <td><?= htmlspecialchars($i['nombre'] . ' ' . $i['apellido_paterno'] . ' ' . $i['apellido_materno']) ?></td>
                    <td><?= $i['edad'] ?></td>
                    <td><?= htmlspecialchars($i['municipio']) ?></td>
                    <td><?= htmlspecialchars($i['email']) ?></td>
                    <td><?= htmlspecialchars($i['telefono']) ?></td>
                    <td><?= htmlspecialchars($i['taller_seleccionado']) ?><?= $i['nombre_taller'] ? ' (' . htmlspecialchars($i['nombre_taller']) . ')' : '' ?></td>
                    <td><?= htmlspecialchars($i['mesa_trabajo']) ?></td>
                    <td><?= $i['fecha_registro'] ?></td>
                    <td><?= $i['activo'] ? 'Sí' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
(() => {
  // Detectar si estamos dentro del admin o se abrió directo
  const root = document.getElementById('dynamicSection') || document;

  /* =====================
     EXPORTAR A EXCEL
  ====================== */
  window.toggleExportMenu = function () {
    const menu = root.querySelector('#exportMenu');
    if (menu) {
      menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
  };

  window.showFilter = function (tipo) {
    const fT = root.querySelector('#filterTaller');
    const fM = root.querySelector('#filterMesa');
    if (fT) fT.style.display = tipo === 'taller' ? 'block' : 'none';
    if (fM) fM.style.display = tipo === 'mesa' ? 'block' : 'none';
  };

  window.exportExcel = function (tipo) {
    let url = 'inscripciones/export_excel.php?tipo=' + encodeURIComponent(tipo);

    if (tipo === 'taller') {
      const taller = root.querySelector('#tallerSelect')?.value || '';
      if (!taller) {
        alert('Selecciona un taller antes de exportar.');
        return;
      }
      url += '&taller=' + encodeURIComponent(taller);
    }

    if (tipo === 'mesa') {
      const mesa = root.querySelector('#mesaSelect')?.value || '';
      if (!mesa) {
        alert('Selecciona una mesa antes de exportar.');
        return;
      }
      url += '&mesa=' + encodeURIComponent(mesa);
    }

    // Abrir descarga
    window.location.href = url;
  };

  /* =====================
     FILTROS (AJAX)
  ====================== */
  function aplicarFiltro() {
    const form = root.querySelector('#filtroForm');
    if (!form) return;

    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();

    // Mostrar animación mientras carga
    const tabla = root.querySelector('#tablaInscripciones');
    if (tabla) tabla.innerHTML = '<p style="text-align:center;">Cargando...</p>';

    fetch('inscripciones/index.php?' + params, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(res => {
        if (!res.ok) throw new Error('Error al filtrar');
        return res.text();
      })
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const nuevaTabla = doc.querySelector('#tablaInscripciones');
        if (tabla && nuevaTabla) tabla.innerHTML = nuevaTabla.innerHTML;
      })
      .catch(err => {
        if (tabla) tabla.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
      });
  }

  /* =====================
     LIMPIAR FILTROS
  ====================== */
  function limpiarFiltros() {
    const form = root.querySelector('#filtroForm');
    if (form) form.reset();
    aplicarFiltro();
  }

  /* =====================
     EVENTOS
  ====================== */
  const btnFiltrar = root.querySelector('#filtrarBtn');
  const btnLimpiar = root.querySelector('#limpiarBtn');

  if (btnFiltrar) btnFiltrar.addEventListener('click', aplicarFiltro);
  if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFiltros);

})();
</script>


</body>
</html>
