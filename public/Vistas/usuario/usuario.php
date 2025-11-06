<?php
session_start();
require_once __DIR__ . '/../../../app/controllers/TalleresUsuarioController.php';

$controller = new TalleresUsuarioController();
$talleres = $controller->index();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXPO APRENDE EDAYO</title>
    <link rel="stylesheet" href="../../css/design-system.css">
    <link rel="stylesheet" href="../../css/components-base.css">
    <link rel="stylesheet" href="../../css/usuario.css">
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
                <img src="../../images/logo.png" alt="logo" style="height:50px;">
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
                        <a class="nav-link" href="#testimonios">
                            <i class="far fa-question-circle"></i>
                            Preguntas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">
                            <i class="fas fa-envelope"></i>
                            Contáctanos
                        </a>
             
                    <li class="user-icon">
                        <a href="../../../app/controllers/logout.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
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
            <button onclick="openModal(<?= $t['id'] ?>)">Inscribete</button>
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


<!-- Sección Testimonios -->
<section class="testimonios-section" id="testimonios">
    <h2>Lo que dicen nuestros estudiantes</h2>
    <div class="testimonios-grid">
        <div class="testimonio-card">
            <p>"Soy mamá soltera y con el taller de repostería pude empezar a vender postres desde mi casa. Ahora tengo ingresos extras sin descuidar a mis hijos"</p>
            <div class="testimonio-author">
                <strong>- Carolina Reyes</strong>
                <span>Repostería Básica</span>
            </div>
        </div>

        <div class="testimonio-card">
            <p>"A los 18 años no sabía qué estudiar, pero el curso de mecánica automotriz me apasionó tanto que ahora voy a seguir la carrera de ingeniería"</p>
            <div class="testimonio-author">
                <strong>- Diego Hernández</strong>
                <span>Mecánica Automotriz</span>
            </div>
        </div>
    </div>
</section>

<section class="faq-section">
    <h2>Preguntas Frecuentes</h2>
    <div class="faq-item">
        <button class="faq-question">
        ¿Puedo tomar más de una Mesa de Trabajo a la vez?
        <i class="fas fa-chevron-down"></i>
        </button>
        <div class="faq-answer">
        <p>Sí, siempre que los horarios no se empalmen. Puedes tomar varias incluso el mismo dia</p>
        </div>
    </div>

    <div class="faq-item">
            <button class="faq-question">
                ¿Necesito experiencia previa para inscribirme?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>¡Para nada! Por eso se llama Expo Aprende, brindaremos aprendizaje desde cero</p>
            </div>
    </div>

       <div class="faq-item">
            <button class="faq-question">
                ¿Los talleres tienen algún costo?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Son completamente gratuitos. El gobierno del estado subsidia todos los materiales y herramientas que uses durante el curso. Solo te pedimos compromiso y puntualidad.</p>
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

<!-- Modal dinámico -->
<div id="modalOverlay" class="modal-overlay" style="display:none;">
  <div class="modal-content" id="modalBody"></div>
</div>


<script src="../../JavaScript/carrusel_talleres.js"></script>
<script src="../../JavaScript/preguntas_usu.js"></script>

<script>
/**
 * reemplaza la función openModal anterior por esta
 * usage: openModal(tallerId)
 */
function openModal(tallerId) {
    fetch(`modal_inscripcion.php?taller_id=${tallerId}`)
    .then(res => {
        if (!res.ok) throw new Error('Error cargando modal: ' + res.status);
        return res.text();
    })
    .then(html => {
        // Si ya existe un overlay, elimínalo (evita duplicados)
        const existing = document.getElementById('modalOverlay');
        if (existing) existing.remove();

        // Crear overlay
        const overlay = document.createElement('div');
        overlay.id = 'modalOverlay';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0,0,0,0.6)';
        overlay.style.display = 'flex';
        overlay.style.justifyContent = 'center';
        overlay.style.alignItems = 'center';
        overlay.style.zIndex = '9999';

        // Inyectar HTML (modal body)
        overlay.innerHTML = html;
        document.body.appendChild(overlay);

        // Agregar listener para cerrar con clic fuera (opcional)
        overlay.addEventListener('click', function(e){
            if (e.target === overlay) closeModal();
        });

        // Buscar formulario y conectar submit por JS
        const form = overlay.querySelector('#formInscripcion');
        if (form) {
            form.addEventListener('submit', function(ev){
                ev.preventDefault();

                // Construir FormData desde el form (usa los name= para poblar POST)
                const formData = new FormData(form);

                // Llamada a tu controlador PHP (que actualmente espera $_POST)
                fetch('../../../app/controllers/InscripcionController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(resp => {
                    if (resp.status === 'success') {
                        // alerta en la página (puedes reemplazar por tu alert estilizado)
                        alert(resp.message || 'Inscripción guardada correctamente');
                        // cerrar modal
                        closeModal();
                        // opcional: recargar la página para que usuario vea cambios
                        // location.reload();
                    } else {
                        alert(resp.message || 'Error al guardar la inscripción');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al procesar la inscripción');
                });
            });
        } else {
            console.warn('formInscripcion no encontrado dentro del modal');
        }
    })
    .catch(err => {
        console.error(err);
        alert('No se pudo abrir el formulario. Revisa la consola.');
    });
}

/** cerrar modal */
function closeModal(){
    const overlay = document.getElementById('modalOverlay');
    if (overlay) overlay.remove();
}
</script>




</body>
</html>
