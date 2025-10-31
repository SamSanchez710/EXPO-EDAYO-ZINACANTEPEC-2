<?php
require_once __DIR__ . '/Database.php';

class ReportesModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Obtener inscripciones filtradas por fecha
    public function obtenerInscripcionesPorFecha($fecha = null) {
        $sql = "SELECT i.*, t.nombre AS nombre_taller 
                FROM inscripciones i
                LEFT JOIN talleres t ON i.taller_id = t.id";
        $params = [];
        if ($fecha) {
            $sql .= " WHERE DATE(i.fecha_registro) = :fecha";
            $params[':fecha'] = $fecha;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener lista de fechas únicas de inscripciones
    public function listarFechas() {
        $sql = "SELECT DISTINCT DATE(fecha_registro) as fecha FROM inscripciones ORDER BY fecha DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Estadísticas rápidas
    public function obtenerEstadisticas($fecha = null) {
        $sqlTotal = "SELECT COUNT(*) as total FROM inscripciones";
        $sqlTalleres = "SELECT COUNT(DISTINCT taller_id) as total_talleres FROM inscripciones";
        $sqlMesas = "SELECT COUNT(DISTINCT mesa_trabajo) as total_mesas FROM inscripciones";
        $params = [];
        if ($fecha) {
            $sqlTotal .= " WHERE DATE(fecha_registro) = :fecha";
            $sqlTalleres .= " WHERE DATE(fecha_registro) = :fecha";
            $sqlMesas .= " WHERE DATE(fecha_registro) = :fecha";
            $params[':fecha'] = $fecha;
        }

        $stmt = $this->conn->prepare($sqlTotal);
        $stmt->execute($params);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $stmt = $this->conn->prepare($sqlTalleres);
        $stmt->execute($params);
        $totalTalleres = $stmt->fetch(PDO::FETCH_ASSOC)['total_talleres'];

        $stmt = $this->conn->prepare($sqlMesas);
        $stmt->execute($params);
        $totalMesas = $stmt->fetch(PDO::FETCH_ASSOC)['total_mesas'];

        return [
            'total' => $total,
            'total_talleres' => $totalTalleres,
            'total_mesas' => $totalMesas
        ];
    }
}
?>
