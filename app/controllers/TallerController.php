<?php
require_once __DIR__ . '/../models/TallerModel.php';

class TallerController {
    private $model;

    public function __construct() {
        $this->model = new TallerModel();
    }

    public function list() {
        return $this->model->getAll();
    }

    public function get($id) {
        return $this->model->getById($id);
    }

    public function create($data, $mesas) {
        return $this->model->create($data, $mesas);
    }

    public function update($id, $data, $mesas) {
        return $this->model->update($id, $data, $mesas);
    }

    public function delete($id) {
        return $this->model->delete($id);
    }
}
