<?php
require_once __DIR__ . '/../models/MesaModel.php';

class MesaController {
    private $model;

    public function __construct() {
        $this->model = new MesaModel();
    }

    public function list() {
        return $this->model->getMesas();
    }

    public function get($id) {
        return $this->model->getMesaById($id);
    }

    public function create($data) {
        return $this->model->createMesa($data);
    }

    public function update($id, $data) {
        return $this->model->updateMesa($id, $data);
    }

    public function delete($id) {
        return $this->model->deleteMesa($id);
    }

    public function getTalleres() {
        return $this->model->getTalleres();
    }
}
?>
