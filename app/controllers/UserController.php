<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function list($tipo='all') {
        return $this->model->getUsers($tipo);
    }

    public function get($id) {
        return $this->model->getUserById($id);
    }

    public function create($data) {
        return $this->model->createUser($data);
    }

    public function update($id, $data) {
        return $this->model->updateUser($id, $data);
    }

    public function view($id) {
        return $this->model->getById($id);
    }

    public function delete($id) {
        return $this->model->deleteUser($id);
    }
}
?>
