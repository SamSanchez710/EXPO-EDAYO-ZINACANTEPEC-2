<?php
require_once __DIR__ . '/../../../../app/controllers/DashboardController.php';
$controller = new DashboardController();
$data = $controller->index();
?>

<div id="dashboardSection" style="font-family: Arial, sans-serif; padding: 10px;">
  <h1 style="margin-bottom:12px;">📊 Dashboard de Administración</h1>

  <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
    <div style="flex:1; min-width:200px; background:#fff; border-radius:8px; padding:14px; box-shadow:0 2px 6px rgba(0,0,0,0.06);">
      <div style="font-size:13px; color:#666;">Total Usuarios</div>
      <div style="font-size:22px; font-weight:700; color:#0b66c3;"><?= $data['totalUsuarios'] ?></div>
    </div>

    <div style="flex:1; min-width:200px; background:#fff; border-radius:8px; padding:14px; box-shadow:0 2px 6px rgba(0,0,0,0.06);">
      <div style="font-size:13px; color:#666;">Talleres Activos</div>
      <div style="font-size:22px; font-weight:700; color:#28a745;"><?= $data['talleresActivos'] ?></div>
    </div>

    <div style="flex:1; min-width:200px; background:#fff; border-radius:8px; padding:14px; box-shadow:0 2px 6px rgba(0,0,0,0.06);">
      <div style="font-size:13px; color:#666;">Inscripciones Hoy</div>
      <div style="font-size:22px; font-weight:700; color:#ff8c00;"><?= $data['inscripcionesHoy'] ?></div>
    </div>

    <div style="flex:1; min-width:200px; background:#fff; border-radius:8px; padding:14px; box-shadow:0 2px 6px rgba(0,0,0,0.06);">
      <div style="font-size:13px; color:#666;">Tasa de Completamiento</div>
      <div style="font-size:22px; font-weight:700; color:#6f42c1;"><?= $data['tasaCompletamiento'] ?>%</div>
    </div>
  </div>

  <div style="margin-top:10px;">
    <div style="margin-bottom:18px;">
      <h3 style="margin:0 0 8px 0;">Inscripciones por Taller</h3>
      <canvas id="chartTaller" style="max-height:300px;"></canvas>
    </div>

    <div style="margin-bottom:18px;">
      <h3 style="margin:0 0 8px 0;">Actividad Reciente (7 días)</h3>
      <canvas id="chartActividad" style="max-height:260px;"></canvas>
    </div>

    <div style="display:flex; gap:16px; flex-wrap:wrap;">
      <div style="flex:1; min-width:300px;">
        <h3 style="margin:0 0 8px 0;">Top 5 Talleres</h3>
        <canvas id="chartTopTalleres" style="max-height:260px;"></canvas>
      </div>

      <div style="flex:1; min-width:300px;">
        <h3 style="margin:0 0 8px 0;">Mesas más Populares</h3>
        <canvas id="chartMesasPopulares" style="max-height:260px;"></canvas>
      </div>
    </div>

    <div style="margin-top:20px;">
      <h3 style="margin:0 0 8px 0;">Últimas 5 Inscripciones</h3>
      <div style="overflow:auto;">
        <table style="width:100%; border-collapse:collapse;">
          <thead>
            <tr style="background:#f2f2f2;">
              <th style="padding:8px; border:1px solid #e6e6e6;">Nombre Completo</th>
              <th style="padding:8px; border:1px solid #e6e6e6;">Taller</th>
              <th style="padding:8px; border:1px solid #e6e6e6;">Mesa</th>
              <th style="padding:8px; border:1px solid #e6e6e6;">Fecha</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($data['ultimasInscripciones'])): ?>
              <?php foreach($data['ultimasInscripciones'] as $i): ?>
                <tr>
                  <td style="padding:8px; border:1px solid #eee;"><?= htmlspecialchars($i['nombre'].' '.$i['apellido_paterno'].' '.$i['apellido_materno']) ?></td>
                  <td style="padding:8px; border:1px solid #eee;"><?= htmlspecialchars($i['taller_seleccionado']) ?></td>
                  <td style="padding:8px; border:1px solid #eee;"><?= htmlspecialchars($i['mesa_trabajo']) ?></td>
                  <td style="padding:8px; border:1px solid #eee;"><?= htmlspecialchars($i['fecha_registro']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4" style="text-align:center; padding:12px;">Sin registros recientes</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
/**
 * Este script se asegura de:
 * - Cargar Chart.js si no está presente
 * - Inicializar gráficos después de que la vista sea inyectada dinámicamente
 */
(function(){
  function cargarChart(callback){
    if(window.Chart) return callback();
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    s.onload = callback;
    document.head.appendChild(s);
  }

  function init(){
    const root = document.getElementById('dashboardSection');
    if(!root) return;

    // Extraer datos (protegidos: si no hay valores, pasar arrays vacíos)
    const labelsTaller = <?= json_encode(array_column($data['inscripcionesPorTaller'], 'taller')) ?> || [];
    const dataTaller = <?= json_encode(array_column($data['inscripcionesPorTaller'], 'total')) ?> || [];

    const labelsAct = <?= json_encode(array_column($data['actividadReciente'], 'fecha')) ?> || [];
    const dataAct = <?= json_encode(array_column($data['actividadReciente'], 'total')) ?> || [];

    const labelsTop = <?= json_encode(array_column($data['topTalleres'], 'taller')) ?> || [];
    const dataTop = <?= json_encode(array_column($data['topTalleres'], 'total')) ?> || [];

    const labelsMesas = <?= json_encode(array_column($data['mesasPopulares'], 'mesa')) ?> || [];
    const dataMesas = <?= json_encode(array_column($data['mesasPopulares'], 'total')) ?> || [];

    // Chart: Inscripciones por Taller
    try {
      new Chart(root.querySelector('#chartTaller').getContext('2d'), {
        type: 'bar',
        data: { labels: labelsTaller, datasets: [{ data: dataTaller, backgroundColor: 'rgba(54,162,235,.7)' }] },
        options: { responsive:true, plugins:{ legend:{display:false} } }
      });
    } catch(e){ console.warn('Error chartTaller', e); }

    // Chart: Actividad reciente
    try {
      new Chart(root.querySelector('#chartActividad').getContext('2d'), {
        type: 'line',
        data: { labels: labelsAct, datasets: [{ label:'Inscripciones', data: dataAct, borderColor:'#ff6384', backgroundColor:'rgba(255,99,132,0.2)', fill:true, tension:0.3 }] },
        options: { responsive:true }
      });
    } catch(e){ console.warn('Error chartActividad', e); }

    // Chart: Top Talleres
    try {
      new Chart(root.querySelector('#chartTopTalleres').getContext('2d'), {
        type: 'bar',
        data: { labels: labelsTop, datasets: [{ data: dataTop, backgroundColor:'rgba(255,159,64,0.7)' }] },
        options: { responsive:true, indexAxis:'y', plugins:{ legend:{display:false} } }
      });
    } catch(e){ console.warn('Error chartTopTalleres', e); }

    // Chart: Mesas Populares
    try {
      new Chart(root.querySelector('#chartMesasPopulares').getContext('2d'), {
        type: 'bar',
        data: { labels: labelsMesas, datasets: [{ data: dataMesas, backgroundColor:'rgba(75,192,192,0.7)' }] },
        options: { responsive:true, plugins:{ legend:{display:false} } }
      });
    } catch(e){ console.warn('Error chartMesasPopulares', e); }
  }

  cargarChart(init);
})();
</script>

<style>
/* Minimal styling adicional (puedes integrarlo en tu CSS global) */
#dashboardSection h1 { font-size:20px; }
#dashboardSection h3 { font-size:16px; margin-bottom:6px; color:#333; }
</style>
