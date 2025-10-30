<?php
session_start();
require_once __DIR__ . '/../../../../app/controllers/UserController.php';

$controller = new UserController();

// Verificar si se edita
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$usuario = $id ? $controller->get($id) : null;

// Si es petición AJAX para guardar
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == 1){
    $data = [
        'nombre' => $_POST['nombre'],
        'apellido_paterno' => $_POST['apellido_paterno'],
        'apellido_materno' => $_POST['apellido_materno'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'tipo_usuario' => $_POST['tipo_usuario'],
        'activo' => isset($_POST['activo']) ? 1 : 0
    ];

    if(!empty($_POST['id'])){
        $controller->update(intval($_POST['id']), $data);
    } else {
        $controller->create($data);
    }

    echo json_encode(['status'=>'success']);
    exit();
}
?>

<h2><?= $usuario ? 'Editar Usuario' : 'Agregar Usuario' ?></h2>

<form id="userForm" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $usuario['id'] ?? '' ?>">
    <input type="hidden" name="ajax" value="1">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= $usuario['nombre'] ?? '' ?>" required><br>

    <label>Apellido Paterno:</label><br>
    <input type="text" name="apellido_paterno" value="<?= $usuario['apellido_paterno'] ?? '' ?>" required><br>

    <label>Apellido Materno:</label><br>
    <input type="text" name="apellido_materno" value="<?= $usuario['apellido_materno'] ?? '' ?>" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= $usuario['email'] ?? '' ?>" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" value="<?= $usuario['password'] ?? '' ?>" required><br>

    <label>Tipo Usuario:</label><br>
    <select name="tipo_usuario">
        <option value="usuario" <?= ($usuario['tipo_usuario'] ?? '')=='usuario' ? 'selected' : '' ?>>Usuario</option>
        <option value="admin" <?= ($usuario['tipo_usuario'] ?? '')=='admin' ? 'selected' : '' ?>>Admin</option>
    </select><br>

    <label>Activo:</label>
    <input type="checkbox" name="activo" <?= isset($usuario['activo']) && $usuario['activo'] ? 'checked' : '' ?>><br>

    <label>Foto de Perfil:</label><br>
    <?php if($usuario && $usuario['foto_perfil']): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_perfil']) ?>" width="100" alt="Foto"><br>
    <?php endif; ?>
    <input type="file" name="foto_perfil" accept="image/*"><br><br>

    <button type="submit"><?= $usuario ? 'Actualizar' : 'Guardar' ?></button>
    <button type="button" onclick="closeModal()">Cancelar</button>
</form>

<script>
document.getElementById('userForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('form.php<?= $usuario ? "?id=".$usuario['id'] : "" ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            alert('Usuario guardado correctamente');
            closeModal();
            fetch('index.php?tipo=<?= $_GET['tipo'] ?? 'all' ?>')
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('table');
                    document.querySelector('table').replaceWith(newTable);
                });
        } else {
            alert('Error al guardar el usuario');
        }
    });
});
</script>

