<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    public $conn; // público para usar en verificar.php
    private $table = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener usuario por email
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Registrar usuario con token
    public function registerUserWithToken($nombre, $apellido_paterno, $apellido_materno, $email, $password, $token) {
        $query = "INSERT INTO " . $this->table . " 
        (nombre, apellido_paterno, apellido_materno, email, password, tipo_usuario, verificado, token_verificacion) 
        VALUES (:nombre, :apellido_paterno, :apellido_materno, :email, :password, 'usuario', 0, :token)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido_paterno', $apellido_paterno);
        $stmt->bindParam(':apellido_materno', $apellido_materno);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':token', $token);

        if(!$stmt->execute()){
            // Muestra el error de SQL para debug
            error_log("Error al insertar usuario: " . implode(", ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    // Actualizar usuario como verificado
    public function verifyUserByToken($token) {
        $query = "SELECT * FROM " . $this->table . " WHERE token_verificacion = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            $update = "UPDATE " . $this->table . " SET verificado = 1, token_verificacion = NULL WHERE id = :id";
            $stmt2 = $this->conn->prepare($update);
            $stmt2->bindParam(':id', $user['id']);
            $stmt2->execute();
            return true;
        }
        return false;
    }
}
?>
