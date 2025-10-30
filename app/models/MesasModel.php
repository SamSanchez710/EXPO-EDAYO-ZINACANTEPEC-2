<?php
require_once __DIR__ . '/../../config/database.php';

class MesasModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todas las mesas
    public function getMesas() {
        $stmt = $this->conn->prepare("SELECT m.*, t.nombre AS nombre_taller FROM mesas_trabajo m LEFT JOIN talleres t ON m.taller_id=t.id ORDER BY m.id DESC");
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
        $stmt = $this->conn->prepare("INSERT INTO mesas_trabajo (taller_id, nombre_mesa, persona_cargo, hora_especifica, lugar_area) VALUES (:taller, :nombre, :persona, :hora, :lugar)");
        $stmt->bindParam(':taller', $data['taller_id']);
        $stmt->bindParam(':nombre', $data['nombre_mesa']);
        $stmt->bindParam(':persona', $data['persona_cargo']);
        $stmt->bindParam(':hora', $data['hora_especifica']);
        $stmt->bindParam(':lugar', $data['lugar_area']);
        return $stmt->execute();
    }

    // Actualizar mesa
    public function updateMesa($id, $data) {
        $stmt = $this->conn->prepare("UPDATE mesas_trabajo SET taller_id=:taller, nombre_mesa=:nombre, persona_cargo=:persona, hora_especifica=:hora, lugar_area=:lugar WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':taller', $data['taller_id']);
        $stmt->bindParam(':nombre', $data['nombre_mesa']);
        $stmt->bindParam(':persona', $data['persona_cargo']);
        $stmt->bindParam(':hora', $data['hora_especifica']);
        $stmt->bindParam(':lugar', $data['lugar_area']);
        return $stmt->execute();
    }

    // Eliminar mesa
    public function deleteMesa($id) {
        $stmt = $this->conn->prepare("DELETE FROM mesas_trabajo WHERE id=:id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
