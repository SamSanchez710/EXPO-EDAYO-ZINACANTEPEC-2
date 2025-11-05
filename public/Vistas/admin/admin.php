<?php
require_once '../../../app/controllers/ConfiguracionController.php';

$controller = new ConfiguracionController();
$usuario = null;

if (isset($_SESSION['user_id'])) {
    $data = $controller->index();
    $usuario = $data['usuario'] ?? null;
}
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
    <img src="../../../app/controllers/ConfiguracionController.php?action=foto" 
         alt="Avatar Admin" class="avatar-animado" id="avatarImg">
    <button class="editar-avatar-btn" onclick="document.getElementById('avatar').click()">
      <i class="fas fa-pencil-alt"></i>
    </button>
    <input type="file" id="avatar" accept="image/*" style="display:none;" 
           onchange="subirAvatar(this)">
  </div>
  <h3 style="color: white; margin: 10px 0 5px 0;">
    <?php echo htmlspecialchars($usuario['nombre'] ?? 'Usuario'); ?>
</h3>
<p style="color: #b98f55; margin: 0; font-size: 0.9em;">
    <?php echo htmlspecialchars($usuario['email'] ?? 'correo@dominio.com'); ?>
</p>

</div>

    <ul>
      <li><a href="#" class="nav-link active" data-section="dashboard"> <i class="fas fa-chart-line"></i> Dashboard</a></li>
      <li><a href="#" class="nav-link" data-section="usuarios"> <i class="fas fa-user-cog"></i> Usuarios</a></li>
      <li><a href="#" class="nav-link" data-section="talleres"> <i class="fas fa-book"></i> Talleres</a></li>
      <li><a href="#" class="nav-link" data-section="inscripciones"> <i class="fas fa-users"></i> Inscripciones</a></li>
      <li><a href="#" class="nav-link" data-section="mesas"> <i class="fas fa-table"></i> Mesas</a></li>
      <li><a href="#" class="nav-link" data-section="reportes"> <i class="fas fa-chart-bar"></i> Reportes</a></li>
      <li><a href="#" class="nav-link" data-section="configuracion"> <i class="fas fa-chart-bar"></i> Configuracion</a></li>
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
  <!-- Puedes eliminar esta parte si lo cargas dinámicamente -->
  <!-- <section id="dashboard" class="active-section">
    <h1 class="titulo-admin">Dashboard de Administración</h1>
    <p>Bienvenido al panel de administración.</p>
  </section> -->

  <!-- Contenedor dinámico de CRUDs -->
  <section id="dynamicSection" style="display:block;"></section>
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
  // =============================
  // Sidebar móvil
  // =============================
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
  }

  // =============================
  // Variables globales
  // =============================
  const navLinks = document.querySelectorAll('.nav-link');
  const dynamicSection = document.getElementById('dynamicSection');
  const staticSections = document.querySelectorAll('main > section:not(#dynamicSection)');
  let currentSection = ''; // guarda la última vista cargada (para filtros o secciones)

  // =============================
  // Navegación de secciones
  // =============================
  navLinks.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const sectionId = link.getAttribute('data-section');

      // Activar el link seleccionado
      navLinks.forEach(l => l.classList.remove('active'));
      link.classList.add('active');

      // Ocultar secciones estáticas
      staticSections.forEach(s => s.style.display = 'none');

      // Cargar la sección correspondiente
      switch (sectionId) {
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
        case 'configuracion':
          loadCRUD('configuracion/index.php');
          break;
        default:
          dynamicSection.innerHTML = `<p>Bienvenido al Dashboard</p>`;
      }
    });
  });

  // =============================
  // ✅ Función para cargar secciones dinámicas (respetando filtros)
  // =============================
  function loadSection(path) {
    currentSection = path;
    const url = path + (path.includes('?') ? '&' : '?') + 't=' + Date.now();
    console.log('[loadSection] cargando:', url);

    dynamicSection.innerHTML = `<p style="text-align:center;margin:20px;">Cargando...</p>`;

    fetch(url, { cache: 'no-store' })
      .then(res => res.text())
      .then(html => {
        dynamicSection.style.display = 'block';
        dynamicSection.innerHTML = html;

        // Ejecutar los scripts embebidos dentro del HTML cargado
        const scripts = dynamicSection.querySelectorAll("script");
        scripts.forEach(oldScript => {
          const newScript = document.createElement("script");
          if (oldScript.src) newScript.src = oldScript.src;
          else newScript.textContent = oldScript.textContent;
          document.body.appendChild(newScript);
        });

        console.log('[loadSection] contenido inyectado correctamente.');
      })
      .catch(err => {
        console.error('[loadSection] error:', err);
        dynamicSection.innerHTML = `<p style="color:red;">Error cargando sección: ${err}</p>`;
      });
  }

  // =============================
  // ✅ Función general para CRUD dinámicos
  // =============================
  function loadCRUD(path) {
    const url = path + (path.includes('?') ? '&' : '?') + 't=' + Date.now();
    currentSection = path;
    console.log('[loadCRUD] cargando:', url);

    dynamicSection.innerHTML = `<p style="text-align:center;margin:20px;">Cargando...</p>`;

    fetch(url, { cache: 'no-store' })
      .then(res => res.text())
      .then(html => {
        dynamicSection.style.display = 'block';
        dynamicSection.innerHTML = html;

        // Ejecutar scripts embebidos (por ejemplo los que traen los formularios o tablas)
        const scripts = Array.from(dynamicSection.querySelectorAll("script"));
        scripts.forEach(oldScript => {
          const newScript = document.createElement("script");
          if (oldScript.src) newScript.src = oldScript.src;
          else newScript.textContent = oldScript.textContent;
          dynamicSection.appendChild(newScript);
        });

        console.log('[loadCRUD] contenido inyectado para:', path);
      })
      .catch(err => {
        console.error('[loadCRUD] error cargando', path, err);
        dynamicSection.innerHTML = `<p style="color:red;">Error cargando sección: ${err}</p>`;
      });
  }

  // =============================
  // ✅ Modal global
  // =============================
  function openModal(url) {
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');

    fetch(url)
      .then(res => res.text())
      .then(html => {
        content.innerHTML = html;
        overlay.style.display = 'flex';

        // Listener de preview (usuarios/talleres)
        const fotoInput = content.querySelector('#fotoInput');
        const previewFoto = content.querySelector('#previewFoto');
        const imagenInput = content.querySelector('#imagenInput');
        const previewImagen = content.querySelector('#previewImagen');

        if (fotoInput && previewFoto) {
          fotoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = e => {
                previewFoto.src = e.target.result;
                previewFoto.style.display = 'block';
              };
              reader.readAsDataURL(file);
            } else {
              previewFoto.src = '';
              previewFoto.style.display = 'none';
            }
          });
        }

        if (imagenInput && previewImagen) {
          imagenInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = e => {
                previewImagen.src = e.target.result;
                previewImagen.style.display = 'block';
              };
              reader.readAsDataURL(file);
            } else {
              previewImagen.src = '';
              previewImagen.style.display = 'none';
            }
          });
        }

        // Listener para submit AJAX del modal
        const form = content.querySelector('form');
        if (form) {
          console.log('[openModal] attach submit listener to form id=', form.id, 'for url=', url);
          form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(url, { method: 'POST', body: formData })
              .then(res => res.json())
              .then(data => {
                if (data.status === 'success') {
                  console.log('[openModal] success para form:', form.id);
                  alert('Guardado correctamente');
                  closeModal();

                  // Pequeño retardo para asegurar cierre de modal
                  setTimeout(() => {
                    if (form.id === 'userForm') {
                      loadCRUD('usuarios/index.php');
                    } else if (form.id === 'tallerForm') {
                      loadCRUD('talleres/index.php');
                    } else if (form.id === 'mesaForm') {
                      loadCRUD('mesas/mesas_index.php'); // ✅ recarga tabla mesas
                    } else if (currentSection) {
                      loadSection(currentSection); // fallback: recarga la sección actual
                    } else {
                      loadCRUD('dashboard/index.php');
                    }
                  }, 150);
                } else {
                  alert('Error al guardar');
                  console.warn('[openModal] respuesta con error:', data);
                }
              })
              .catch(err => alert('Error AJAX: ' + err));
          });
        }
      })
      .catch(err => {
        content.innerHTML = `<p style="color:red;">Error cargando modal: ${err}</p>`;
        overlay.style.display = 'flex';
      });
  }

  // =============================
  // ✅ Cerrar modal
  // =============================
  function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    overlay.style.display = 'none';
    document.getElementById('modalContent').innerHTML = '';
  }

  // =============================
  // ✅ Subir avatar (configuración)
  // =============================
  function subirAvatar(input) {
    const file = input.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('avatar', file);

    fetch('../../../app/controllers/ConfiguracionController.php?action=actualizarFotoPerfil', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(r => {
        if (r.success) {
          alert(r.message);
          document.getElementById('avatarImg').src =
            '../../../app/controllers/ConfiguracionController.php?action=foto&t=' + new Date().getTime();
        } else {
          alert('Error: ' + r.message);
        }
      })
      .catch(() => alert('Error al subir la foto'));
  }

// =============================
// ✅ Cargar Dashboard por defecto al iniciar
// =============================
document.addEventListener('DOMContentLoaded', function () {
  console.log('[init] Cargando Dashboard por defecto...');
  loadCRUD('dashboard/index.php');

  // Marcar el link de Dashboard como activo (opcional)
  const dashboardLink = document.querySelector('.nav-link[data-section="dashboard"]');
  if (dashboardLink) {
    dashboardLink.classList.add('active');
  }

  // Ocultar las demás secciones estáticas
  const staticSections = document.querySelectorAll('main > section:not(#dynamicSection)');
  staticSections.forEach(s => s.style.display = 'none');
});


</script>


</body>
</html>
