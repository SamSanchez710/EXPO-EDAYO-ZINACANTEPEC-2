<?php
require_once __DIR__ . '/../../../../app/controllers/DashboardController.php';

$controller = new DashboardController();
$data = $controller->index();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .row { display: flex; flex-wrap: wrap; gap: 20px; }
        .card { background: #f5f5f5; padding: 20px; border-radius: 8px; flex: 1; min-width: 200px; text-align: center; }
        .chart-container { width: 100%; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div id="dashboardSection">
<h1>Dashboard</h1>

<div class="row">
    <div class="card">
        <h3>Total de personas registradas</h3>
        <p><?= $data['totalUsuarios'] ?></p>
    </div>
    <div class="card">
        <h3>Talleres activos</h3>
        <p><?= $data['talleresActivos'] ?></p>
    </div>
    <div class="card">
        <h3>Inscripciones hoy</h3>
        <p><?= $data['inscripcionesHoy'] ?></p>
    </div>
    <div class="card">
        <h3>Tasa de completamiento</h3>
        <p><?= $data['tasaCompletamiento'] ?>%</p>
    </div>
</div>

<div class="chart-container">
    <h3>Inscripciones por Taller</h3>
    <canvas id="chartTaller"></canvas>
</div>

<div class="chart-container">
    <h3>Actividad Reciente (últimos 7 días)</h3>
    <canvas id="chartActividad"></canvas>
</div>

<div class="chart-container">
    <h3>Top 5 Talleres con más inscripciones</h3>
    <canvas id="chartTopTalleres"></canvas>
</div>

<div class="chart-container">
    <h3>Mesas más populares</h3>
    <canvas id="chartMesasPopulares"></canvas>
</div>

<div class="chart-container">
    <h3>Últimas 5 inscripciones</h3>
    <table>
        <thead>
            <tr>
                <th>Nombre completo</th>
                <th>Taller</th>
                <th>Mesa</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['ultimasInscripciones'] as $i): ?>
                <tr>
                    <td><?= htmlspecialchars($i['nombre'].' '.$i['apellido_paterno'].' '.$i['apellido_materno']) ?></td>
                    <td><?= htmlspecialchars($i['taller_seleccionado']) ?></td>
                    <td><?= htmlspecialchars($i['mesa_trabajo']) ?></td>
                    <td><?= $i['fecha_registro'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div> <!-- Fin dashboardSection -->

<script>
(() => {
  const root = document.getElementById('dashboardSection') || document;

  // Gráfico de inscripciones por taller
  const ctxTaller = root.querySelector('#chartTaller').getContext('2d');
  new Chart(ctxTaller, {
      type: 'bar',
      data: {
          labels: <?= json_encode(array_column($data['inscripcionesPorTaller'], 'taller')) ?>,
          datasets: [{
              label: 'Inscripciones',
              data: <?= json_encode(array_column($data['inscripcionesPorTaller'], 'total')) ?>,
              backgroundColor: 'rgba(54, 162, 235, 0.6)'
          }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
  });

  // Gráfico de actividad reciente
  const ctxActividad = root.querySelector('#chartActividad').getContext('2d');
  new Chart(ctxActividad, {
      type: 'line',
      data: {
          labels: <?= json_encode(array_column($data['actividadReciente'], 'fecha')) ?>,
          datasets: [{
              label: 'Inscripciones',
              data: <?= json_encode(array_column($data['actividadReciente'], 'total')) ?>,
              borderColor: 'rgba(255, 99, 132, 1)',
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              fill: true,
              tension: 0.3
          }]
      },
      options: { responsive: true }
  });

  // Gráfico Top Talleres
  const ctxTopTalleres = root.querySelector('#chartTopTalleres').getContext('2d');
  new Chart(ctxTopTalleres, {
      type: 'bar',
      data: {
          labels: <?= json_encode(array_column($data['topTalleres'], 'taller')) ?>,
          datasets: [{
              label: 'Inscripciones',
              data: <?= json_encode(array_column($data['topTalleres'], 'total')) ?>,
              backgroundColor: 'rgba(255, 159, 64, 0.6)'
          }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
  });

  // Gráfico Mesas Populares
  const ctxMesas = root.querySelector('#chartMesasPopulares').getContext('2d');
  new Chart(ctxMesas, {
      type: 'bar',
      data: {
          labels: <?= json_encode(array_column($data['mesasPopulares'], 'mesa')) ?>,
          datasets: [{
              label: 'Inscripciones',
              data: <?= json_encode(array_column($data['mesasPopulares'], 'total')) ?>,
              backgroundColor: 'rgba(75, 192, 192, 0.6)'
          }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
  });

})();
</script>

</body>
</html>
