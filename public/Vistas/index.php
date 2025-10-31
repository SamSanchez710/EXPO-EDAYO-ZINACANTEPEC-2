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
    <title>EXPO APRENDE EDAYO</title>
    <link rel="stylesheet" href="../css/design-system.css">
    <link rel="stylesheet" href="../css/components-base.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/public/css/carrusel_talleres.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Menú -->
<header class="header" id="inicio">
        
<div class="navigation-wrap start-header start-style">
    <div class="container">
        <nav class="navbar">
            <a class="navbar-brand" href="#">
                <img src="../images/logo.png" alt="logo" style="height:50px;">
            </a>
            
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
            
            <div class="navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">
                            <i class="fas fa-home"></i>
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#talleres">
                            <i class="fas fa-laptop-code"></i>
                            Talleres
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#acerca">
                            <i class="fas fa-info-circle"></i>
                            Acerca De
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">
                            <i class="fas fa-envelope"></i>
                            Contáctanos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-login" href="#" id="openLogin">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

    <div class="header-content container">
        <div class="header-txt">
            <h1>Expo Aprende EDAYO Zinacantepec</h1>
            <p>
                Descubre tu potencial, aprende haciendo.
                En Expo Aprende EDAYO Zinacantepec, 
                te ofrecemos la oportunidad de explorar y
                dominar nuevas habilidades a través de una 
                variedad de mesas de trabajo en diferentes 
                talleres prácticos, diseñados para 
                impulsar tu creatividad y tu futuro.
            </p>
            <div class="butons">
                <a href="#" class="btn btn-primary btn-lg" id="btnInscribete">
                    <i class="fas fa-arrow-right"></i> Inscribete Ya!
                </a>
            </div>
        </div>
    </div>
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
