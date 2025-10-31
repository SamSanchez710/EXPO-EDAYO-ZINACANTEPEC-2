<?php
require_once __DIR__ . '/../models/ReportesModel.php';

class ReportesController {
    private $model;

    public function __construct() {
        $this->model = new ReportesModel();
    }

    public function index() {
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;
        $inscripciones = $this->model->obtenerInscripcionesPorFecha($fecha);
        $estadisticas = $fecha ? $this->model->obtenerEstadisticasPorFecha($fecha) : null;
        $fechas = $this->model->listarFechas();

        return [
            'inscripciones' => $inscripciones,
            'estadisticas' => $estadisticas,
            'fechas' => $fechas,
            'filtro_fecha' => $fecha
        ];
    }
}
?>
