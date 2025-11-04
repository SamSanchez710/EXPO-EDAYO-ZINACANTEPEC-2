<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel de Administración - EDAYO Zinacantepec</title>

  <link rel="stylesheet" href="../../css/admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <!-- Botón móvil -->
  <button class="mobile-menu-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <div class="perfil-avatar">
      <div class="avatar-container">
        <img src="../../images/avatar_default.png" alt="Avatar Admin" class="avatar-animado">
        <button class="editar-avatar-btn" onclick="document.getElementById('avatar').click()">
          <i class="fas fa-pencil-alt"></i>
        </button>
        <input type="file" id="avatar" accept="image/*" style="display:none;">
      </div>
      <h3 style="color: white; margin: 10px 0 5px 0;">Administrador</h3>
      <p style="color: #b98f55; margin: 0; font-size: 0.9em;">admin@edayo.edu.mx</p>
    </div>

    <ul>
      <li><a href="#" class="nav-link active" data-section="dashboard"> <i class="fas fa-chart-line"></i> Dashboard</a></li>
      <li><a href="#" class="nav-link" data-section="usuarios"> <i class="fas fa-user-cog"></i> Usuarios</a></li>
      <li><a href="#" class="nav-link" data-section="talleres"> <i class="fas fa-book"></i> Talleres</a></li>
      <li><a href="#" class="nav-link" data-section="inscripciones"> <i class="fas fa-users"></i> Inscripciones</a></li>
      <li><a href="#" class="nav-link" data-section="mesas"> <i class="fas fa-table"></i> Mesas</a></li>
      <li><a href="#" class="nav-link" data-section="reportes"> <i class="fas fa-chart-bar"></i> Reportes</a></li>
    </ul>
  </nav>

  <!-- Header -->
  <header class="navbar">
    <div class="logo">
      <img src="../../images/logo.png" alt="EDAYO Logo">
    </div>
    <div class="navbar-right">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Buscar...">
      </div>
      <div class="notifications">
        <i class="fas fa-bell" style="color: #882035; font-size: 1.2em; cursor: pointer;"></i>
        <span class="notification-badge">3</span>
      </div>
      <a href="../../../app/controllers/logout.php" class="login-btn">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
  </header>

  <!-- Contenido Principal -->
  <main class="main-content">
    <section id="dashboard" class="active-section">
      <h1 class="titulo-admin">Dashboard de Administración</h1>
      <p>Bienvenido al panel de administración.</p>
    </section>

    <!-- Contenedor dinámico de CRUDs -->
    <section id="dynamicSection" style="display:block;">
      <p>Selecciona una sección del menú para mostrar su contenido.</p>
    </section>
  </main>

  <!-- Modal global -->
  <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:9999;">
      <div id="modalContent" style="background:#fff; padding:20px; max-width:700px; width:90%;"></div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> EDAYO Zinacantepec. Todos los derechos reservados.
  </footer>

  <script src="../../JavaScript/admin.js"></script>
  <script>
    // Sidebar móvil
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
    }

    // Manejo de secciones
    const navLinks = document.querySelectorAll('.nav-link');
    const dynamicSection = document.getElementById('dynamicSection');
    const staticSections = document.querySelectorAll('main > section:not(#dynamicSection)');

    navLinks.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const sectionId = link.getAttribute('data-section');

        // Activar link
        navLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        // Ocultar secciones estáticas
        staticSections.forEach(s => s.style.display = 'none');

        // Cargar CRUD correspondiente
        switch(sectionId) {
          case 'usuarios':
            loadCRUD('usuarios/index.php');
            break;
          case 'talleres':
            loadCRUD('talleres/index.php');
            break;
          case 'inscripciones':
            loadCRUD('inscripciones/index.php');
            break;
          case 'mesas':
            loadCRUD('mesas/mesas_index.php');
            break;
          case 'reportes':
            loadCRUD('reportes/index.php');
            break;
          case 'dashboard':
            loadCRUD('dashboard/index.php');
            break;
          default:
            dynamicSection.innerHTML = `<p>Bienvenido al Dashboard</p>`;
        }
      });
    });

    // Función para cargar CRUD en sección dinámica
function loadCRUD(path) {
  fetch(path)
    .then(res => res.text())
    .then(html => {
      dynamicSection.style.display = 'block';
      dynamicSection.innerHTML = html;

      // 🔧 Ejecutar los scripts que vienen dentro del HTML cargado
      const scripts = dynamicSection.querySelectorAll("script");
      scripts.forEach(oldScript => {
        const newScript = document.createElement("script");
        if (oldScript.src) {
          // si el script tiene src (archivo externo)
          newScript.src = oldScript.src;
        } else {
          // si el script está embebido
          newScript.textContent = oldScript.textContent;
        }
        document.body.appendChild(newScript);
      });
    })
    .catch(err => {
      dynamicSection.innerHTML = `<p style="color:red;">Error cargando sección: ${err}</p>`;
    });
}


    // Modal global
    function openModal(url) {
      const overlay = document.getElementById('modalOverlay');
      const content = document.getElementById('modalContent');

      fetch(url)
        .then(res => res.text())
        .then(html => {
          content.innerHTML = html;
          overlay.style.display = 'flex';
        })
        .catch(err => {
          content.innerHTML = `<p style="color:red;">Error cargando modal: ${err}</p>`;
          overlay.style.display = 'flex';
        });
    }

    function closeModal() {
      const overlay = document.getElementById('modalOverlay');
      overlay.style.display = 'none';
      document.getElementById('modalContent').innerHTML = '';
    }

  
  </script>
</body>
</html>
