<?php
require_once __DIR__ . '/../../config/database.php';

class DashboardModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function totalUsuarios() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function talleresActivos() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM talleres WHERE activo = 1");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function inscripcionesHoy() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM inscripciones WHERE DATE(fecha_registro) = CURDATE()");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function tasaCompletamiento() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total, COALESCE(SUM(confirmacion_asistencia),0) as completadas FROM inscripciones");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$res || (int)$res['total'] === 0) return 0;
        return round(((int)$res['completadas'] / (int)$res['total']) * 100, 2);
    }

    public function inscripcionesPorTaller() {
        $stmt = $this->conn->prepare("
            SELECT t.nombre AS taller, COUNT(i.id) AS total
            FROM talleres t
            LEFT JOIN inscripciones i ON t.id = i.taller_id
            GROUP BY t.id
            ORDER BY total DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actividadReciente($days = 7) {
        $stmt = $this->conn->prepare("
            SELECT DATE(fecha_registro) AS fecha, COUNT(*) AS total
            FROM inscripciones
            WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL :d DAY)
            GROUP BY DATE(fecha_registro)
            ORDER BY fecha ASC
        ");
        $stmt->bindValue(':d', (int)$days, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ensure all days present (fill zeros)
        $out = [];
        for ($i = $days-1; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-{$i} days"));
            $out[$day] = 0;
        }
        foreach($rows as $r) {
            $out[$r['fecha']] = (int)$r['total'];
        }
        $result = [];
        foreach($out as $date => $total) $result[] = ['fecha'=>$date, 'total'=>$total];
        return $result;
    }

    public function topTalleres($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT t.nombre AS taller, COUNT(i.id) AS total
            FROM talleres t
            LEFT JOIN inscripciones i ON t.id = i.taller_id
            GROUP BY t.id
            ORDER BY total DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mesasPopulares($limit = 5) {
        // Aquí la relación se basa en el campo mesa_trabajo en inscripciones (tal como tenías)
        $stmt = $this->conn->prepare("
            SELECT m.nombre_mesa AS mesa, COUNT(i.id) AS total
            FROM mesas_trabajo m
            LEFT JOIN inscripciones i ON m.nombre_mesa = i.mesa_trabajo
            GROUP BY m.id
            ORDER BY total DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ultimasInscripciones($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT nombre, apellido_paterno, apellido_materno, taller_seleccionado, mesa_trabajo, fecha_registro
            FROM inscripciones
            ORDER BY fecha_registro DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
