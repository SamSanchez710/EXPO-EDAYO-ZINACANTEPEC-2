<?php
require_once __DIR__ . '/../../config/database.php';

class TallerModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los talleres
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM talleres ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un taller por ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM talleres WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $taller = $stmt->fetch(PDO::FETCH_ASSOC);

        if($taller){
            $stmt2 = $this->conn->prepare("SELECT * FROM mesas_trabajo WHERE taller_id=:id");
            $stmt2->bindParam(':id', $id);
            $stmt2->execute();
            $taller['mesas'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $taller['mesas'] = [];
        }

        return $taller;
    }

    // Crear taller
    public function create($data, $mesas) {
        $stmt = $this->conn->prepare("INSERT INTO talleres (nombre, descripcion, imagen, hora_inicio, hora_fin, lugar, activo) VALUES (:nombre,:descripcion,:imagen,:hora_inicio,:hora_fin,:lugar,:activo)");
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':imagen', $data['imagen'], PDO::PARAM_LOB);
        $stmt->bindParam(':hora_inicio', $data['hora_inicio']);
        $stmt->bindParam(':hora_fin', $data['hora_fin']);
        $stmt->bindParam(':lugar', $data['lugar']);
        $stmt->bindParam(':activo', $data['activo']);
        $stmt->execute();
        $taller_id = $this->conn->lastInsertId();

        foreach($mesas as $mesa){
            $stmt2 = $this->conn->prepare("INSERT INTO mesas_trabajo (taller_id, nombre_mesa, persona_cargo, hora_especifica, lugar_area) VALUES (:taller_id, :nombre_mesa, :persona_cargo, :hora_especifica, :lugar_area)");
            $stmt2->bindParam(':taller_id', $taller_id);
            $stmt2->bindParam(':nombre_mesa', $mesa['nombre_mesa']);
            $stmt2->bindParam(':persona_cargo', $mesa['persona_cargo']);
            $stmt2->bindParam(':hora_especifica', $mesa['hora_especifica']);
            $stmt2->bindParam(':lugar_area', $mesa['lugar_area']);
            $stmt2->execute();
        }

        return $taller_id;
    }

    // Actualizar taller
    public function update($id, $data, $mesas) {
        $stmt = $this->conn->prepare("UPDATE talleres SET nombre=:nombre, descripcion=:descripcion, imagen=:imagen, hora_inicio=:hora_inicio, hora_fin=:hora_fin, lugar=:lugar, activo=:activo WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':imagen', $data['imagen'], PDO::PARAM_LOB);
        $stmt->bindParam(':hora_inicio', $data['hora_inicio']);
        $stmt->bindParam(':hora_fin', $data['hora_fin']);
        $stmt->bindParam(':lugar', $data['lugar']);
        $stmt->bindParam(':activo', $data['activo']);
        $stmt->execute();

        // Eliminar mesas existentes y agregar nuevas
        $stmtDel = $this->conn->prepare("DELETE FROM mesas_trabajo WHERE taller_id=:id");
        $stmtDel->bindParam(':id', $id);
        $stmtDel->execute();

        foreach($mesas as $mesa){
            $stmt2 = $this->conn->prepare("INSERT INTO mesas_trabajo (taller_id, nombre_mesa, persona_cargo, hora_especifica, lugar_area) VALUES (:taller_id, :nombre_mesa, :persona_cargo, :hora_especifica, :lugar_area)");
            $stmt2->bindParam(':taller_id', $id);
            $stmt2->bindParam(':nombre_mesa', $mesa['nombre_mesa']);
            $stmt2->bindParam(':persona_cargo', $mesa['persona_cargo']);
            $stmt2->bindParam(':hora_especifica', $mesa['hora_especifica']);
            $stmt2->bindParam(':lugar_area', $mesa['lugar_area']);
            $stmt2->execute();
        }
    }

    // Eliminar taller
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM talleres WHERE id=:id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
