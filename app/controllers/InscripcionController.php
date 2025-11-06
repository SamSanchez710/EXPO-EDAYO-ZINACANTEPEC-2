<?php
require_once __DIR__ . '/../models/InscripcionModel.php';

class InscripcionController {
    private $model;

    public function __construct() {
        $this->model = new InscripcionModel();
    }

    public function guardar() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'usuario_id' => $_POST['usuario_id'] ?? null,
                'nombre' => $_POST['nombre'] ?? '',
                'apellido_paterno' => $_POST['apellido_paterno'] ?? '',
                'apellido_materno' => $_POST['apellido_materno'] ?? '',
                'edad' => $_POST['edad'] ?? '',
                'municipio' => $_POST['municipio'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'taller_seleccionado' => $_POST['taller_seleccionado'] ?? '',
                'mesa_trabajo' => $_POST['mesa_trabajo'] ?? '',
                'aviso_privacidad' => isset($_POST['aviso_privacidad']) ? 1 : 0,
                'confirmacion_asistencia' => isset($_POST['confirmacion_asistencia']) ? 1 : 0,
                'taller_id' => $_POST['taller_id'] ?? null
            ];

            $ok = $this->model->guardarInscripcion($data);

            echo json_encode([
                'status' => $ok ? 'success' : 'error',
                'message' => $ok 
                    ? '¡Inscripción realizada correctamente!' 
                    : 'Error al registrar la inscripción.'
            ]);
            exit;
        }

        echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
    }
}

// ✅ Permitir ejecución directa
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $controller = new InscripcionController();
    $controller->guardar();
}
?>
