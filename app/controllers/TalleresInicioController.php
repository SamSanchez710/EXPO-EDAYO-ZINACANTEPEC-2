<?php
require_once __DIR__ . '/../models/TalleresInicioModel.php';

class TalleresInicioController {
    private $model;

    public function __construct() {
        $this->model = new TalleresInicioModel();
    }

    public function index() {
        return $this->model->getTalleres();
    }

    // Método para obtener mesas por AJAX
    public function getMesas($taller_id) {
        return $this->model->getMesasByTaller($taller_id);
    }
}
?>
