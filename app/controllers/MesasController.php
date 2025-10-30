<?php
require_once __DIR__ . '/../models/MesasModel.php';
require_once __DIR__ . '/../models/TalleresModel.php';

class MesasController {
    private $model;
    private $talleresModel;

    public function __construct() {
        $this->model = new MesasModel();
        $this->talleresModel = new TalleresModel();
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
        return $this->talleresModel->getTalleres();
    }
}
?>
