<?php
require_once __DIR__ . '/../models/TallerModel.php';

class TallerController {
    private $model;

    public function __construct() {
        $this->model = new TallerModel();
    }

    public function list() {
        return $this->model->getTalleres();
    }

    public function get($id) {
        return $this->model->getTallerById($id);
    }

    public function create($data) {
        return $this->model->createTaller($data);
    }

    public function update($id, $data) {
        return $this->model->updateTaller($id, $data);
    }

    public function delete($id) {
        return $this->model->deleteTaller($id);
    }
}
?>
