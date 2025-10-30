<?php
require_once __DIR__ . '/../../config/database.php';

class MesaModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todas las mesas con info del taller
    public function getMesas() {
        $stmt = $this->conn->prepare("
            SELECT m.*, t.nombre AS taller_nombre
            FROM mesas_trabajo m
            JOIN talleres t ON m.taller_id = t.id
            ORDER BY m.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener mesa por ID
    public function getMesaById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM mesas_trabajo WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear mesa
    public function createMesa($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO mesas_trabajo (taller_id, nombre_mesa, persona_cargo, hora_especifica, lugar_area)
            VALUES (:taller_id, :nombre_mesa, :persona_cargo, :hora_especifica, :lugar_area)
        ");
        $stmt->bindParam(':taller_id', $data['taller_id']);
        $stmt->bindParam(':nombre_mesa', $data['nombre_mesa']);
        $stmt->bindParam(':persona_cargo', $data['persona_cargo']);
        $stmt->bindParam(':hora_especifica', $data['hora_especifica']);
        $stmt->bindParam(':lugar_area', $data['lugar_area']);
        return $stmt->execute();
    }

    // Actualizar mesa
    public function updateMesa($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE mesas_trabajo SET 
            taller_id=:taller_id,
            nombre_mesa=:nombre_mesa,
            persona_cargo=:persona_cargo,
            hora_especifica=:hora_especifica,
            lugar_area=:lugar_area
            WHERE id=:id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':taller_id', $data['taller_id']);
        $stmt->bindParam(':nombre_mesa', $data['nombre_mesa']);
        $stmt->bindParam(':persona_cargo', $data['persona_cargo']);
        $stmt->bindParam(':hora_especifica', $data['hora_especifica']);
        $stmt->bindParam(':lugar_area', $data['lugar_area']);
        return $stmt->execute();
    }

    // Eliminar mesa
    public function deleteMesa($id) {
        $stmt = $this->conn->prepare("DELETE FROM mesas_trabajo WHERE id=:id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Obtener todos los talleres para select
    public function getTalleres() {
        $stmt = $this->conn->prepare("SELECT id, nombre FROM talleres ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
