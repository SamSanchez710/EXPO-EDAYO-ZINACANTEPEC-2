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
    <form method="get" action="">
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

        <button type="submit">Filtrar</button>
        <a href="index.php"><button type="button">Limpiar</button></a>
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
function toggleExportMenu(){
    const menu = document.getElementById('exportMenu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

function showFilter(tipo){
    document.getElementById('filterTaller').style.display = tipo==='taller' ? 'block' : 'none';
    document.getElementById('filterMesa').style.display = tipo==='mesa' ? 'block' : 'none';
}

function exportExcel(tipo){
    let url = 'export_excel.php?tipo='+tipo;
    if(tipo==='taller'){
        const taller = document.getElementById('tallerSelect').value;
        if(taller) url += '&taller=' + encodeURIComponent(taller);
        else { alert('Selecciona un taller'); return; }
    }
    if(tipo==='mesa'){
        const mesa = document.getElementById('mesaSelect').value;
        if(mesa) url += '&mesa=' + encodeURIComponent(mesa);
        else { alert('Selecciona una mesa'); return; }
    }
    window.location.href = url;
}
</script>

</body>
</html>
