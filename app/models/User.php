<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $conn;
    private $table = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Buscar usuario por email
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND activo = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

// Registrar usuario tipo 'usuario'
    public function registerUser($nombre, $apellido_paterno, $apellido_materno, $email, $password) {
        $query = "INSERT INTO " . $this->table . " 
            (nombre, apellido_paterno, apellido_materno, email, password, tipo_usuario) 
            VALUES (:nombre, :apellido_paterno, :apellido_materno, :email, :password, 'usuario')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido_paterno', $apellido_paterno);
        $stmt->bindParam(':apellido_materno', $apellido_materno);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

}
?>
