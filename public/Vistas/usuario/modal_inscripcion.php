<?php
session_start();
require_once __DIR__ . '/../../../app/controllers/TalleresUsuarioController.php';
require_once __DIR__ . '/../../../config/database.php';

// --- Validar sesión ---
if (!isset($_SESSION['user_id'])) {
    echo "<p>Error: No se ha iniciado sesión.</p>";
    exit;
}
$usuario_id = $_SESSION['user_id'];

// --- Validar taller ---
$taller_id = isset($_GET['taller_id']) ? intval($_GET['taller_id']) : 0;
if ($taller_id <= 0) {
    echo "<p>Error: No se especificó el taller.</p>";
    exit;
}

// --- Obtener nombre del taller y mesas ---
$db = new Database();
$conn = $db->getConnection();

$stmtT = $conn->prepare("SELECT nombre FROM talleres WHERE id = :id LIMIT 1");
$stmtT->bindParam(':id', $taller_id, PDO::PARAM_INT);
$stmtT->execute();
$tallerRow = $stmtT->fetch(PDO::FETCH_ASSOC);
$taller_nombre = $tallerRow['nombre'] ?? 'Taller';

$controller = new TalleresUsuarioController();
$mesas = $controller->getMesas($taller_id);
?>

<!-- ✅ Modal de inscripción (HTML puro) -->
<div class="modal-container" role="dialog" aria-modal="true">
  <div class="modal-header">
      <h2>Inscríbete al taller de <?= htmlspecialchars($taller_nombre) ?></h2>
      <button onclick="closeModal()" class="close-btn" aria-label="Cerrar">✖</button>
  </div>

  <!-- Formulario: note los name="" en los inputs para que PHP reciba $_POST -->
  <form id="formInscripcion">
      <input type="hidden" id="usuario_id" name="usuario_id" value="<?= htmlspecialchars($usuario_id) ?>">
      <input type="hidden" id="taller_id" name="taller_id" value="<?= htmlspecialchars($taller_id) ?>">
      <input type="hidden" id="taller_seleccionado" name="taller_seleccionado" value="<?= htmlspecialchars($taller_nombre) ?>">

      <div class="form-group">
          <label>Nombre(s):</label>
          <input type="text" id="nombre" name="nombre" required>
      </div>

      <div class="form-group">
          <label>Apellido Paterno:</label>
          <input type="text" id="apellido_paterno" name="apellido_paterno" required>
      </div>

      <div class="form-group">
          <label>Apellido Materno:</label>
          <input type="text" id="apellido_materno" name="apellido_materno" required>
      </div>

      <div class="form-group">
          <label>Edad:</label>
          <input type="number" id="edad" name="edad" min="10" max="100" required>
      </div>

      <div class="form-group">
          <label>Municipio:</label>
          <input type="text" id="municipio" name="municipio" required>
      </div>

      <div class="form-group">
          <label>Correo Electrónico:</label>
          <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
          <label>Teléfono:</label>
          <input type="tel" id="telefono" name="telefono" required pattern="[0-9]{10}" placeholder="Ej. 7221234567">
      </div>

      <div class="form-group">
          <label>Mesa de Trabajo:</label>
          <select id="mesa_trabajo" name="mesa_trabajo" required>
              <option value="">Seleccione una mesa</option>
              <?php foreach ($mesas as $mesa): ?>
                  <option value="<?= htmlspecialchars($mesa['nombre_mesa']) ?>">
                      <?= htmlspecialchars($mesa['nombre_mesa']) ?>
                  </option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="form-group checkbox-group">
          <label>
              <input type="checkbox" id="aviso_privacidad" name="aviso_privacidad" value="1" required>
              He leído y acepto el <a href="https://icati.edomex.gob.mx/aviso-de-privacidad" target="_blank">aviso de privacidad</a>.
          </label>
      </div>

      <div class="form-group checkbox-group">
          <label>
              <input type="checkbox" id="confirmacion_asistencia" name="confirmacion_asistencia" value="1" required>
              Confirmo mi asistencia al taller y mesa seleccionada.
          </label>
      </div>

      <div class="form-actions">
          <button type="submit" class="btn btn-primary">Enviar Inscripción</button>
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
      </div>
  </form>
</div>

<!-- Estilos mínimos (mantén/ajusta según tu CSS) -->
<style>
.modal-container { background:#fff; padding:12px; border-radius:8px; max-width:520px; }
.modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
.close-btn{ background:none;border:0;font-size:16px;cursor:pointer;}
.form-group{ margin-bottom:8px; }
.form-group input, .form-group select { width:100%; padding:6px; border:1px solid #ccc; border-radius:4px; height:34px; }
.form-actions{ display:flex; gap:8px; justify-content:flex-end; margin-top:10px; }
.btn{ padding:6px 12px; border-radius:6px; cursor:pointer; }
.btn-primary{ background:#007bff;color:#fff;border:0;}
.btn-secondary{ background:#ccc;border:0;}
</style>
