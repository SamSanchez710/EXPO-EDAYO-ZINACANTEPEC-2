<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/UserController.php';

$controller = new UserController();

// Filtrado por tipo
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'all';

// Eliminar usuario
if(isset($_GET['delete_id'])) {
    $controller->delete(intval($_GET['delete_id']));
    header("Location: index.php?tipo=$tipo");
    exit();
}

// Obtener lista de usuarios
$usuarios = $controller->list($tipo);
?>

<h2>Usuarios Registrados</h2>

<!-- Filtrado -->
<div style="margin-bottom:10px;">
    <a href="#" onclick="loadSection('usuarios/index.php?tipo=all'); return false;">Todos</a> |
    <a href="#" onclick="loadSection('usuarios/index.php?tipo=admin'); return false;">Administradores</a> |
    <a href="#" onclick="loadSection('usuarios/index.php?tipo=usuario'); return false;">Usuarios</a>
</div>

<!-- Botón Agregar (solo admin) -->
<?php if($tipo == 'admin'): ?>
    <button onclick="openModal('usuarios/form.php')">Agregar Nuevo Administrador</button>
<?php endif; ?>

<!-- Tabla de usuarios -->
<table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%;">
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
                    <button onclick="openModal('usuarios/view.php?id=<?= $user['id'] ?>&tipo=<?= $tipo ?>')">Ver</button>
                    <?php if($tipo == 'admin'): ?>
                        <button onclick="openModal('usuarios/form.php?id=<?= $user['id'] ?>')">Editar</button>
                        <button onclick="if(confirm('Eliminar usuario?')) loadSection('usuarios/index.php?delete_id=<?= $user['id'] ?>&tipo=<?= $tipo ?>')">Eliminar</button>
                    <?php elseif($tipo == 'usuario'): ?>
                        <button onclick="if(confirm('Eliminar usuario?')) loadSection('usuarios/index.php?delete_id=<?= $user['id'] ?>&tipo=<?= $tipo ?>')">Eliminar</button>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


