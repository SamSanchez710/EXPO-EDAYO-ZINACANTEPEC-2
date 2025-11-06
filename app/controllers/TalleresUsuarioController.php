<?php
require_once __DIR__ . '/../models/TalleresUsuarioModel.php';

class TalleresUsuarioController {
    private $model;

    public function __construct() {
        $this->model = new TalleresUsuarioModel();
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
