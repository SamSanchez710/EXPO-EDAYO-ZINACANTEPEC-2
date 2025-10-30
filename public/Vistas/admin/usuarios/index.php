<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/UserController.php';

$controller = new UserController();

// Filtrado
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'all';

// Eliminar
if(isset($_GET['delete_id'])) {
    $controller->delete(intval($_GET['delete_id']));
    header("Location: index.php?tipo=$tipo");
    exit();
}

// Obtener lista de usuarios
$usuarios = $controller->list($tipo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Usuarios</title>
    <style>
        /* Estilos básicos para modal */
        #modalOverlay {
            display: none;
            position: fixed;
            top:0; left:0; width:100%; height:100%;
            background: rgba(0,0,0,0.5);
            justify-content: center; align-items: center;
        }
        #modalContent {
            background: #fff;
            padding: 20px;
            max-width: 600px;
            width: 90%;
        }
    </style>
</head>
<body>

<h1>Usuarios Registrados</h1>

<!-- Filtrado -->
<div>
    <a href="index.php?tipo=all">Todos</a> |
    <a href="index.php?tipo=admin">Administradores</a> |
    <a href="index.php?tipo=usuario">Usuarios</a>
</div>

<hr>

<!-- Botón Agregar Nuevo (solo admin) -->
<?php if($tipo == 'admin'): ?>
    <button onclick="openModal('form.php')">Agregar Nuevo Administrador</button>
<?php endif; ?>

<!-- Tabla -->
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Foto</th>
            <th>ID</th>
            <th>Nombre completo</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Activo</th>
            <?php if($tipo != 'all'): ?><th>Acciones</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach($usuarios as $user): ?>
        <tr>
            <td>
                <?php if($user['foto_perfil']): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($user['foto_perfil']) ?>" width="50" height="50" alt="Foto">
                <?php else: ?>
                    <span>No hay foto</span>
                <?php endif; ?>
            </td>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['nombre'].' '.$user['apellido_paterno'].' '.$user['apellido_materno']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['tipo_usuario'] ?></td>
            <td><?= $user['activo'] ? 'Sí' : 'No' ?></td>
            <?php if($tipo != 'all'): ?>
                <td>
                    <button onclick="openModal('view.php?id=<?= $user['id'] ?>&tipo=<?= $tipo ?>')">Ver</button>

                    <?php if($tipo == 'admin'): ?>
                        <button onclick="openModal('form.php?id=<?= $user['id'] ?>')">Editar</button>
                        <button onclick="if(confirm('Eliminar usuario?')) window.location.href='index.php?delete_id=<?= $user['id'] ?>&tipo=<?= $tipo ?>'">Eliminar</button>
                    <?php elseif($tipo == 'usuario'): ?>
                        <button onclick="if(confirm('Eliminar usuario?')) window.location.href='index.php?delete_id=<?= $user['id'] ?>&tipo=<?= $tipo ?>'">Eliminar</button>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<!-- Modal -->
<div id="modalOverlay">
    <div id="modalContent"></div>
</div>

<script>
function openModal(url){
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');

    // Cargar contenido con AJAX
    fetch(url)
    .then(response => response.text())
    .then(html => {
        content.innerHTML = html;
        overlay.style.display = 'flex';
    });
}

function closeModal(){
    const overlay = document.getElementById('modalOverlay');
    overlay.style.display = 'none';
    document.getElementById('modalContent').innerHTML = '';
}
</script>

</body>
</html>
