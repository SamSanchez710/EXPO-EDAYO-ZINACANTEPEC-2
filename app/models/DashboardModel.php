<?php
require_once __DIR__ . '/../../config/database.php';

class DashboardModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Total de personas registradas
    public function totalUsuarios() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM usuarios");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Talleres activos
    public function talleresActivos() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM talleres WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Inscripciones hoy
    public function inscripcionesHoy() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM inscripciones WHERE DATE(fecha_registro) = CURDATE()");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Tasa de completamiento (%)
    public function tasaCompletamiento() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total, SUM(confirmacion_asistencia) as completadas FROM inscripciones");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['total'] == 0) return 0;
        return round(($res['completadas'] / $res['total']) * 100, 2);
    }

    // Datos para gráfico: inscripciones por taller
    public function inscripcionesPorTaller() {
        $stmt = $this->conn->prepare("
            SELECT t.nombre as taller, COUNT(i.id) as total
            FROM talleres t
            LEFT JOIN inscripciones i ON t.id = i.taller_id
            GROUP BY t.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Datos para gráfico: actividad reciente (últimos 7 días)
    public function actividadReciente() {
        $stmt = $this->conn->prepare("
            SELECT DATE(fecha_registro) as fecha, COUNT(*) as total
            FROM inscripciones
            WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(fecha_registro)
            ORDER BY fecha ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Top 5 talleres con más inscripciones
public function topTalleres() {
    $stmt = $this->conn->prepare("
        SELECT t.nombre as taller, COUNT(i.id) as total
        FROM talleres t
        LEFT JOIN inscripciones i ON t.id = i.taller_id
        GROUP BY t.id
        ORDER BY total DESC
        LIMIT 5
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Mesas más populares
public function mesasPopulares() {
    $stmt = $this->conn->prepare("
        SELECT m.nombre_mesa as mesa, COUNT(i.id) as total
        FROM mesas_trabajo m
        LEFT JOIN inscripciones i ON m.nombre_mesa = i.mesa_trabajo
        GROUP BY m.id
        ORDER BY total DESC
        LIMIT 5
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Últimas 5 inscripciones
public function ultimasInscripciones() {
    $stmt = $this->conn->prepare("
        SELECT nombre, apellido_paterno, apellido_materno, taller_seleccionado, mesa_trabajo, fecha_registro
        FROM inscripciones
        ORDER BY fecha_registro DESC
        LIMIT 5
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
