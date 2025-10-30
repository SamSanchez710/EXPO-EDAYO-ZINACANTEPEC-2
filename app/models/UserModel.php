<?php
require_once __DIR__ . '/../../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los usuarios o por tipo
    public function getUsers($tipo = 'all') {
        $sql = "SELECT * FROM usuarios";
        if($tipo == 'admin') {
            $sql .= " WHERE tipo_usuario='admin'";
        } elseif($tipo == 'usuario') {
            $sql .= " WHERE tipo_usuario='usuario'";
        }
        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener usuario por ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario
public function createUser($data) {
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, email, password, tipo_usuario, activo, foto_perfil) 
        VALUES (:nombre, :ap, :am, :email, :pass, :tipo, :activo, :foto)");
    $stmt->bindParam(':nombre', $data['nombre']);
    $stmt->bindParam(':ap', $data['apellido_paterno']);
    $stmt->bindParam(':am', $data['apellido_materno']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':pass', $password);
    $stmt->bindParam(':tipo', $data['tipo_usuario']);
    $stmt->bindParam(':activo', $data['activo']);
    $stmt->bindParam(':foto', $data['foto_perfil'], PDO::PARAM_LOB);
    return $stmt->execute();
}

// Editar usuario
public function updateUser($id, $data) {
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    if(isset($data['foto_perfil'])){
        $stmt = $this->conn->prepare("UPDATE usuarios SET nombre=:nombre, apellido_paterno=:ap, apellido_materno=:am, email=:email, password=:pass, tipo_usuario=:tipo, activo=:activo, foto_perfil=:foto WHERE id=:id");
        $stmt->bindParam(':foto', $data['foto_perfil'], PDO::PARAM_LOB);
    } else {
        $stmt = $this->conn->prepare("UPDATE usuarios SET nombre=:nombre, apellido_paterno=:ap, apellido_materno=:am, email=:email, password=:pass, tipo_usuario=:tipo, activo=:activo WHERE id=:id");
    }
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $data['nombre']);
    $stmt->bindParam(':ap', $data['apellido_paterno']);
    $stmt->bindParam(':am', $data['apellido_materno']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':pass', $password);
    $stmt->bindParam(':tipo', $data['tipo_usuario']);
    $stmt->bindParam(':activo', $data['activo']);
    return $stmt->execute();
}


    // Obtener un usuario por ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar usuario
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id=:id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
