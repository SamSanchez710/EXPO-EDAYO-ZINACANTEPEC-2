<?php
require_once __DIR__ . '/../../config/database.php';

class InscripcionModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Guardar nueva inscripción: devuelve id insertado o false
    public function guardarInscripcion($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO inscripciones (
                usuario_id, nombre, apellido_paterno, apellido_materno,
                edad, municipio, email, telefono, taller_seleccionado,
                mesa_trabajo, aviso_privacidad, confirmacion_asistencia,
                taller_id
            )
            VALUES (
                :usuario_id, :nombre, :apellido_paterno, :apellido_materno,
                :edad, :municipio, :email, :telefono, :taller_seleccionado,
                :mesa_trabajo, :aviso_privacidad, :confirmacion_asistencia,
                :taller_id
            )
        ");

        $stmt->bindValue(':usuario_id', $data['usuario_id'] !== '' ? $data['usuario_id'] : null, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $data['nombre']);
        $stmt->bindValue(':apellido_paterno', $data['apellido_paterno']);
        $stmt->bindValue(':apellido_materno', $data['apellido_materno']);
        $stmt->bindValue(':edad', $data['edad'] !== '' ? $data['edad'] : null, PDO::PARAM_INT);
        $stmt->bindValue(':municipio', $data['municipio']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':telefono', $data['telefono']);
        $stmt->bindValue(':taller_seleccionado', $data['taller_seleccionado']);
        $stmt->bindValue(':mesa_trabajo', $data['mesa_trabajo']);
        $stmt->bindValue(':aviso_privacidad', $data['aviso_privacidad'], PDO::PARAM_INT);
        $stmt->bindValue(':confirmacion_asistencia', $data['confirmacion_asistencia'], PDO::PARAM_INT);
        $stmt->bindValue(':taller_id', $data['taller_id'] !== '' ? $data['taller_id'] : null, PDO::PARAM_INT);

        $ok = $stmt->execute();
        if ($ok) {
            return (int)$this->conn->lastInsertId();
        }
        return false;
    }

    // Obtener mesas disponibles de un taller
    public function getMesasDisponibles($taller_id) {
        $stmt = $this->conn->prepare("
            SELECT id, nombre_mesa 
            FROM mesas_trabajo 
            WHERE taller_id = :taller_id
        ");
        $stmt->bindParam(':taller_id', $taller_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ----------------------------
    // NUEVO: Métodos para folio
    // ----------------------------
    public function obtenerFolio($id) {
        $stmt = $this->conn->prepare("SELECT folio FROM inscripciones WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['folio'] ?? null;
    }

    public function guardarFolio($id, $folio) {
        $stmt = $this->conn->prepare("UPDATE inscripciones SET folio = :folio WHERE id = :id");
        $stmt->bindParam(':folio', $folio, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
