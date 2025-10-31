<?php
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController {
    private $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    public function index() {
    return [
        'totalUsuarios' => $this->model->totalUsuarios(),
        'talleresActivos' => $this->model->talleresActivos(),
        'inscripcionesHoy' => $this->model->inscripcionesHoy(),
        'tasaCompletamiento' => $this->model->tasaCompletamiento(),
        'inscripcionesPorTaller' => $this->model->inscripcionesPorTaller(),
        'actividadReciente' => $this->model->actividadReciente(),
        'topTalleres' => $this->model->topTalleres(),
        'mesasPopulares' => $this->model->mesasPopulares(),
        'ultimasInscripciones' => $this->model->ultimasInscripciones()
    ];
}

}
