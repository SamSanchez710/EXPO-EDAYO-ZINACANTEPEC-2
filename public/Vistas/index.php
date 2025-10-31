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



<section id="talleres" class="talleres">
  <h2>Talleres Disponibles</h2>

  <div class="talleres-carousel">
    <?php foreach($talleres as $t): ?>
      <div class="taller-item">
        <div class="card">
          <img src="data:image/jpeg;base64,<?= base64_encode($t['imagen']) ?>" alt="<?= htmlspecialchars($t['nombre']) ?>">
          <div class="text-content">
            <h3><?= htmlspecialchars($t['nombre']) ?></h3>
            <p><?= htmlspecialchars($t['descripcion']) ?></p>
            <p><strong>Horario:</strong> <?= $t['hora_inicio'] ?> - <?= $t['hora_fin'] ?></p>
            <p><strong>Lugar:</strong> <?= htmlspecialchars($t['lugar']) ?></p>
            <button onclick="openModal(<?= $t['id'] ?>)">Más información</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- ✅ Controles fuera del carrusel -->
  <div class="carousel-controls">
    <button class="prev-btn">❮</button>
    <button class="next-btn">❯</button>
  </div>

  <!-- Dots opcionales -->
  <div class="carousel-dots">
    <?php foreach($talleres as $index => $t): ?>
      <div class="dot" data-index="<?= $index ?>"></div>
    <?php endforeach; ?>
  </div>
</section>


<!-- Sección Acerca de -->
<section id="acerca" class="acerca container_carru">
    <h2>Acerca De:</h2>
    <h3>EDAYO ZINACANTEPEC</h3>
    
    <div class="about-content">
        <p class="about-description">Ofrecemos Cursos de Capacitación de Lunes a Viernes, Sabatinos y Dominicales.
            Con diferentes Modalidades:
        </p>
        
        <div class="methodology-list">
            <div class="method-item">
                <i class="fas fa-hands-helping"></i>
                <div class="method-text">
                    <strong>CEA</strong>
                    <span>Capacitación Específica para el Auto-Empleo</span>
                    <span>27 horas, divididas en 9 sesiones (3 horas diarias).</span>
                </div>
            </div>
            <div class="method-item">
                <i class="fas fa-project-diagram"></i>
                <div class="method-text">
                    <strong>CEM</strong>
                    <span>Capacitación Emprendedora</span>
                    <span>40 horas / 2 meses de capacitación</span>
                </div>
            </div>
            <div class="method-item">
                <i class="fas fa-network-wired"></i>
                <div class="method-text">
                    <strong>CAE</strong>
                    <span>Capacitación Acelerada Específica</span>
                    <span>De 4 a 80 horas (dependiendo de la especialidad)</span>
                </div>
            </div>
            <div class="method-item">
                <i class="fas fa-rocket"></i>
                <div class="method-text">
                    <strong>Periodo Escolarizado</strong>
                    <span>Cursos en la modalidad escolarizada</span>
                    <span>Lunes a viernes</span>
                    <span>Tres horas diarias</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección Contacto -->
<footer class="modern-footer" id="contacto">
    <div class="footer-content">
        <div class="footer-main">
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="../images/logo.png" alt="EDAYO Zinacantepec" style="height: 60px;">
                    <h3>EDAYO Zinacantepec</h3>
                </div>
                <p class="footer-description">
                    Instituto de Capacitación y Adiestramiento para el Trabajo Industrial
                </p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/EDAYOZinacantepecEdoMex" class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/edayozinacantepec?igsh=MWUzaXg3OWdycDIwdw==" class="social-icon">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://x.com/EDAYO_ZINA?t=1-bWJagixVWDJ8Pp1TztxA&s=09" class="social-icon">
                        <i class="fas fa-xmark"></i>
                    </a>
                    <a href="https://youtube.com/@edayozinacantepecoficial?si=PZLdsQgWTDg1T24J" class="social-icon">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Cursos Populares</h4>
                <ul class="footer-links">
                    <li><a href=""><i class="fas fa-spa"></i>Masajes</a></li>
                    <li><a href=""><i class="fas fa-car"></i>Mecánica Automotriz</a></li>
                    <li><a href=""><i class="fas fa-utensils"></i>Gastronomía</a></li>
                    <li><a href=""><i class="fas fa-hammer"></i>Carpintería</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contacto</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>San Miguel Zinacantepec, Toluca de Lerdo, Estado de México</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+52 722 278 1207</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>edayo.zinacantepec@edomex.gob.mx</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Lun - Vie: 8:00 AM - 6:00 PM</span>
                        <span>Sab - Dom: 8:00 AM - 4:00 PM</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-newsletter">
            <div class="newsletter-content">
                <h4>¡Mantente Informado!</h4>
                <p>Suscríbete para recibir noticias sobre nuevos talleres y eventos.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Tu correo electrónico" required>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-content">
            <div class="copyright">
                <p>&copy; 2025 EDAYO Zinacantepec. Todos los derechos reservados.</p>
            </div>
            <div class="footer-legal">
                <a href="https://icati.edomex.gob.mx/aviso-de-privacidad" target="_blank">Política de Privacidad</a>
                <a href="https://icati.edomex.gob.mx/marco-juridico" target="_blank">Aviso Legal</a>
            </div>
        </div>
    </div>
</footer>

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

<script src="../JavaScript/carrusel_talleres.js"></script>

</body>
</html>
