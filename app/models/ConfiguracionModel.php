<?php
require_once __DIR__ . '/../../config/database.php';

class ConfiguracionModel {
    public $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener datos del usuario por ID
    public function getUsuario($id) {
        $stmt = $this->conn->prepare("SELECT id, nombre, apellido_paterno, apellido_materno, email, tipo_usuario, foto_perfil FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener preferencias reales
    public function getPreferencias($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM preferencias WHERE usuario_id = :user_id LIMIT 1");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $prefs = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prefs) {
            // Si no existe, devolver valores por defecto
            return [
                'tema' => 'claro',
                'notificaciones' => 1,
                'formato_fecha' => 'd/m/Y',
                'idioma' => 'es'
            ];
        }
        return $prefs;
    }

    // Actualizar perfil
    public function actualizarPerfil($user_id, $nombre, $email) {
        try {
            $stmt = $this->conn->prepare("UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Cambiar contraseña
    public function cambiarPassword($user_id, $password) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $hash);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Guardar preferencias en BD
    public function guardarPreferencias($user_id, $tema, $notificaciones, $formato_fecha, $idioma) {
        try {
            // Verificar si ya existe
            $stmt = $this->conn->prepare("SELECT id FROM preferencias WHERE usuario_id = :user_id LIMIT 1");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $pref = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pref) {
                // Actualizar
                $stmt = $this->conn->prepare("UPDATE preferencias SET tema = :tema, notificaciones = :notificaciones, formato_fecha = :formato_fecha, idioma = :idioma WHERE usuario_id = :user_id");
            } else {
                // Insertar
                $stmt = $this->conn->prepare("INSERT INTO preferencias (usuario_id, tema, notificaciones, formato_fecha, idioma) VALUES (:user_id, :tema, :notificaciones, :formato_fecha, :idioma)");
            }

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':tema', $tema);
            $stmt->bindParam(':notificaciones', $notificaciones);
            $stmt->bindParam(':formato_fecha', $formato_fecha);
            $stmt->bindParam(':idioma', $idioma);
            $stmt->execute();

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
