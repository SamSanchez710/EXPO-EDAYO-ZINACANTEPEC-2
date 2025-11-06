<?php
require_once __DIR__ . '/../../config/database.php';

class TalleresUsuarioModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los talleres activos
    public function getTalleres() {
        $stmt = $this->conn->prepare("SELECT * FROM talleres WHERE activo = 1 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener mesas de un taller
    public function getMesasByTaller($taller_id) {
        $stmt = $this->conn->prepare("SELECT * FROM mesas_trabajo WHERE taller_id=:taller_id");
        $stmt->bindParam(':taller_id', $taller_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
