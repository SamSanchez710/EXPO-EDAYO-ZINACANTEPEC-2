<?php
require_once __DIR__ . '/../../config/database.php';

class InscripcionModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Guardar nueva inscripción
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

        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido_paterno', $data['apellido_paterno']);
        $stmt->bindParam(':apellido_materno', $data['apellido_materno']);
        $stmt->bindParam(':edad', $data['edad']);
        $stmt->bindParam(':municipio', $data['municipio']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':taller_seleccionado', $data['taller_seleccionado']);
        $stmt->bindParam(':mesa_trabajo', $data['mesa_trabajo']);
        $stmt->bindParam(':aviso_privacidad', $data['aviso_privacidad']);
        $stmt->bindParam(':confirmacion_asistencia', $data['confirmacion_asistencia']);
        $stmt->bindParam(':taller_id', $data['taller_id']);

        return $stmt->execute();
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
}
?>