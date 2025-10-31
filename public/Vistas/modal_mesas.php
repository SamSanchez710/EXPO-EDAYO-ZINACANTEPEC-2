<?php
require_once __DIR__ . '/../../app/controllers/TalleresInicioController.php';

if (!isset($_GET['taller_id'])) exit;

$taller_id = intval($_GET['taller_id']);
$controller = new TalleresInicioController();
$mesas = $controller->getMesas($taller_id);
?>

<div class="modal" id="modalMesas">
  <div class="modal-content">
    <span class="close" id="closeModal" onclick="closeModal()">&times;</span>
    <div class="taller-info-modal">
      <h3>Mesas de Trabajo</h3>
      <link rel="stylesheet" href="../css/modal_mesas.css">
      <?php
      if (count($mesas) == 0) {
          echo "<div class='no-mesas'>No hay mesas registradas para este taller.</div>";
      } else {
          echo "<ul class='lista-mesas'>";
          foreach ($mesas as $m) {
              echo "<li class='mesa-item'>
                      <div class='mesa-header'>
                          <span class='mesa-nombre'>".htmlspecialchars($m['nombre_mesa'])."</span>
                          <span class='mesa-hora'>".htmlspecialchars($m['hora_especifica'])."</span>
                      </div>
                      <div class='mesa-persona'><strong>Persona a cargo:</strong> ".htmlspecialchars($m['persona_cargo'])."</div>
                      <div class='mesa-lugar'><strong>Lugar/Área:</strong> ".htmlspecialchars($m['lugar_area'])."</div>
                    </li>";
          }
          echo "</ul>";
      }
      ?>
    </div>
  </div>
</div>