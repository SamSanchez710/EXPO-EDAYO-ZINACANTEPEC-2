<?php
session_start();
require_once __DIR__ . '/../../app/controllers/TalleresInicioController.php';

$controller = new TalleresInicioController();
$talleres = $controller->index();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Expo Aprende Edayo</title>
<style>
/* Estilos básicos */
body { font-family: Arial, sans-serif; margin:0; padding:0; }
header { background: #333; color: #fff; padding: 15px 20px; display:flex; justify-content: space-between; align-items: center; }
header nav a { color: #fff; margin: 0 10px; text-decoration: none; font-weight: bold; }
section { padding: 50px 20px; }
h1,h2,h3 { margin-bottom: 20px; }
.carousel { display:flex; overflow-x:auto; scroll-behavior: smooth; gap:20px; }
.card { flex:0 0 300px; border:1px solid #ccc; padding:10px; border-radius:8px; text-align:center; }
.card img { max-width:100%; height:200px; object-fit:cover; border-radius:5px; }
button { padding:10px 15px; margin-top:10px; cursor:pointer; }

/* Modal */
#modalOverlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000; }
#modalContent { background:#fff; padding:20px; max-width:600px; width:90%; border-radius:8px; max-height:80%; overflow-y:auto; }
#modalContent table { width:100%; border-collapse:collapse; margin-top:10px; }
#modalContent th, #modalContent td { border:1px solid #ccc; padding:8px; text-align:left; }
#modalContent th { background:#f2f2f2; }
.closeBtn { background:red; color:#fff; border:none; padding:5px 10px; cursor:pointer; float:right; border-radius:3px; }
</style>
</head>
<body>

<!-- Menú -->
<header>
    <div class="logo"><h2>Expo Aprende Edayo</h2></div>
    <nav>
        <a href="#inicio">Inicio</a>
        <a href="#talleres">Talleres</a>
        <a href="#acerca">Acerca de</a>
        <a href="#contacto">Contáctanos</a>
        <a href="login.php">Iniciar sesión</a>
    </nav>
</header>

<!-- Sección Inicio -->
<section id="inicio">
    <h1>Bienvenido a Expo Aprende Edayo</h1>
    <p>Descubre nuestros talleres, aprende nuevas habilidades y participa en actividades educativas diseñadas para ti.</p>
</section>

<!-- Sección Talleres -->
<section id="talleres">
    <h2>Talleres Disponibles</h2>
    <div class="carousel">
        <?php foreach($talleres as $t): ?>
        <div class="card">
            <?php if($t['imagen']): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($t['imagen']) ?>" alt="<?= htmlspecialchars($t['nombre']) ?>">
            <?php else: ?>
                <img src="https://via.placeholder.com/300x200?text=Sin+Imagen" alt="Sin Imagen">
            <?php endif; ?>
            <h3><?= htmlspecialchars($t['nombre']) ?></h3>
            <p><?= htmlspecialchars($t['descripcion']) ?></p>
            <p><strong>Horario:</strong> <?= $t['hora_inicio'] ?> - <?= $t['hora_fin'] ?></p>
            <p><strong>Lugar:</strong> <?= htmlspecialchars($t['lugar']) ?></p>
            <button onclick="openModal(<?= $t['id'] ?>)">Más información</button>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Sección Acerca de -->
<section id="acerca">
    <h2>Acerca de</h2>
    <p>La Expo Aprende Edayo es un evento educativo diseñado para brindar a los participantes experiencias de aprendizaje únicas en diferentes áreas de conocimiento y talleres prácticos.</p>
</section>

<!-- Sección Contacto -->
<section id="contacto">
    <h2>Contáctanos</h2>
    <p>Para más información, escríbenos a <a href="mailto:info@edayo.com">info@edayo.com</a> o llama al 729-000-0000.</p>
</section>

<!-- Modal para mesas -->
<div id="modalOverlay">
    <div id="modalContent">
        <button class="closeBtn" onclick="closeModal()">Cerrar</button>
        <div id="modalBody"></div>
    </div>
</div>

<script>
// Función abrir modal con mesas
function openModal(tallerId){
    fetch(`modal_mesas.php?taller_id=${tallerId}`)
    .then(res => res.text())
    .then(html => {
        document.getElementById('modalBody').innerHTML = html;
        document.getElementById('modalOverlay').style.display = 'flex';
    });
}
function closeModal(){
    document.getElementById('modalOverlay').style.display = 'none';
    document.getElementById('modalBody').innerHTML = '';
}
</script>

</body>
</html>
