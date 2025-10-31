<?php
require_once __DIR__ . '/../models/InscripcionesModel.php';

class InscripcionesController {
    private $model;

    public function __construct() {
        $this->model = new InscripcionesModel();
    }

    public function index() {
        // Filtros desde GET
        $taller_id = isset($_GET['taller_id']) ? intval($_GET['taller_id']) : null;
        $mesa_trabajo = isset($_GET['mesa_trabajo']) ? $_GET['mesa_trabajo'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $inscripciones = $this->model->obtenerInscripciones($taller_id, $mesa_trabajo, $search);
        $talleres = $this->model->listarTalleres();
        $mesas = $this->model->listarMesas();

        return [
            'inscripciones' => $inscripciones,
            'talleres' => $talleres,
            'mesas' => $mesas,
            'filtros' => [
                'taller_id' => $taller_id,
                'mesa_trabajo' => $mesa_trabajo,
                'search' => $search
            ]
        ];
    }
}
?>
