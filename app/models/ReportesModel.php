<?php
require_once __DIR__ . '/../../config/database.php';

class ReportesModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); 
    }

    // Obtener inscripciones por fecha
    public function obtenerInscripcionesPorFecha($fecha = null) {
        $sql = "SELECT i.*, t.nombre AS nombre_taller
                FROM inscripciones i
                LEFT JOIN talleres t ON i.taller_id = t.id";
        if ($fecha) {
            $sql .= " WHERE DATE(i.fecha_registro) = :fecha";
        }
        $stmt = $this->conn->prepare($sql);
        if ($fecha) {
            $stmt->bindParam(':fecha', $fecha);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Estadísticas rápidas
    public function obtenerEstadisticasPorFecha($fecha) {
        $sql_total = "SELECT COUNT(*) AS total_inscritos FROM inscripciones WHERE DATE(fecha_registro) = :fecha";
        $sql_talleres = "SELECT COUNT(DISTINCT taller_id) AS total_talleres FROM inscripciones WHERE DATE(fecha_registro) = :fecha";
        $sql_mesas = "SELECT COUNT(DISTINCT mesa_trabajo) AS total_mesas FROM inscripciones WHERE DATE(fecha_registro) = :fecha";

        $stmt_total = $this->conn->prepare($sql_total);
        $stmt_total->bindParam(':fecha', $fecha);
        $stmt_total->execute();
        $total_inscritos = $stmt_total->fetch(PDO::FETCH_ASSOC)['total_inscritos'];

        $stmt_talleres = $this->conn->prepare($sql_talleres);
        $stmt_talleres->bindParam(':fecha', $fecha);
        $stmt_talleres->execute();
        $total_talleres = $stmt_talleres->fetch(PDO::FETCH_ASSOC)['total_talleres'];

        $stmt_mesas = $this->conn->prepare($sql_mesas);
        $stmt_mesas->bindParam(':fecha', $fecha);
        $stmt_mesas->execute();
        $total_mesas = $stmt_mesas->fetch(PDO::FETCH_ASSOC)['total_mesas'];

        return [
            'total_inscritos' => $total_inscritos,
            'total_talleres' => $total_talleres,
            'total_mesas' => $total_mesas
        ];
    }

    // Listar todas las fechas disponibles
    public function listarFechas() {
        $sql = "SELECT DISTINCT DATE(fecha_registro) AS fecha FROM inscripciones ORDER BY fecha DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
