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

            $id = $this->model->guardarInscripcion($data);

            if ($id !== false) {
                // ----------------------------
                // Guardar folio en la BD
                // ----------------------------
                $folioExistente = $this->model->obtenerFolio($id);

                if (empty($folioExistente)) {
                    $folio = 'EDAYO-' . date('Y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
                    $this->model->guardarFolio($id, $folio);
                } else {
                    $folio = $folioExistente;
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Inscripción registrada correctamente.',
                    'id_inscripcion' => $id,
                    'folio' => $folio
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la inscripción.']);
            }
            exit;
        }

        echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
    }
}

// Ejecución directa segura
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $controller = new InscripcionController();
    $controller->guardar();
}
?>
