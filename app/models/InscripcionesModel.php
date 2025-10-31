<?php
require_once __DIR__ . '/../../config/database.php';

class InscripcionesModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todas las inscripciones con filtros opcionales
    public function obtenerInscripciones($taller_id = null, $mesa_trabajo = null, $search = null) {
        $sql = "SELECT i.*, t.nombre AS nombre_taller 
                FROM inscripciones i
                LEFT JOIN talleres t ON i.taller_id = t.id
                WHERE 1=1";

        $params = [];

        if($taller_id) {
            $sql .= " AND i.taller_id = :taller_id";
            $params[':taller_id'] = $taller_id;
        }

        if($mesa_trabajo) {
            $sql .= " AND i.mesa_trabajo = :mesa_trabajo";
            $params[':mesa_trabajo'] = $mesa_trabajo;
        }

        if($search) {
            $sql .= " AND (i.nombre LIKE :search OR i.apellido_paterno LIKE :search OR i.apellido_materno LIKE :search OR i.email LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener lista de talleres
    public function listarTalleres() {
        $stmt = $this->conn->prepare("SELECT * FROM talleres WHERE activo = 1 ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener lista de mesas de trabajo
    public function listarMesas() {
        $stmt = $this->conn->prepare("SELECT * FROM mesas_trabajo ORDER BY nombre_mesa");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllInscripciones($filtros = []) {
    $sql = "SELECT i.*, t.nombre AS taller_nombre 
            FROM inscripciones i
            LEFT JOIN talleres t ON i.taller_id = t.id 
            WHERE 1";

    $params = [];

    // Filtro por taller
    if(!empty($filtros['taller'])){
        $sql .= " AND t.nombre = :taller";
        $params[':taller'] = $filtros['taller'];
    }

    // Filtro por mesa de trabajo
    if(!empty($filtros['mesa'])){
        $sql .= " AND i.mesa_trabajo = :mesa";
        $params[':mesa'] = $filtros['mesa'];
    }

    // Filtro por búsqueda (nombre, apellido, email)
    if(!empty($filtros['busqueda'])){
        $sql .= " AND (i.nombre LIKE :busq OR i.apellido_paterno LIKE :busq OR i.apellido_materno LIKE :busq OR i.email LIKE :busq)";
        $params[':busq'] = "%".$filtros['busqueda']."%";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
?>
