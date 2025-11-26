<?php
// controller/ctrlContacto.php

require_once '../model/Contacto.php';
require_once '../model/ConexionBd.php';

class CtrlContacto {
    private $contacto;

    public function __construct() {
        $this->contacto = new Contacto();
    }

    public function procesar() {
        // Verificar que se haya enviado la opción
        if (!isset($_POST['opcion'])) {
            $this->enviarRespuesta('error', 'Opción no especificada');
            return;
        }

        $opcion = intval($_POST['opcion']);

        try {
            switch ($opcion) {
                case 1:
                    $this->guardarMensaje();
                    break;

                case 2: 
                    $this->obtenerMensajes();
                    break;

                case 3: 
                    $this->obtenerMensajePorId();
                    break;

                case 4: 
                    $this->eliminarMensaje();
                    break;

                case 5:
                    $this->obtenerMensajesPaginados();
                    break;

                default:
                    $this->enviarRespuesta('error', 'Opción no válida');
                    break;
            }
        } catch (Exception $e) {
            $this->enviarRespuesta('error', $e->getMessage());
        }
    }

    // ========== MÉTODOS PARA CADA OPCIÓN ==========

    private function guardarMensaje() {
        // Validar datos requeridos
        if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['mensaje'])) {
            $this->enviarRespuesta('error', 'Todos los campos son obligatorios');
            return;
        }

        // Validar token CSRF si está implementado
        if (isset($_POST['csrf_token']) && !$this->validarCSRFToken($_POST['csrf_token'])) {
            $this->enviarRespuesta('error', 'Token de seguridad inválido');
            return;
        }

        // Validar límite de mensajes por IP (prevención de spam)
        if ($this->limiteMensajesExcedido()) {
            $this->enviarRespuesta('error', 'Has enviado demasiados mensajes recientemente. Intenta más tarde.');
            return;
        }

        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $mensaje = trim($_POST['mensaje']);

        // Guardar mensaje
        $id = $this->contacto->guardarMensaje($nombre, $email, $mensaje);

        if ($id) {
            // Opcional: Enviar email de notificación
            $this->enviarNotificacionEmail($nombre, $email, $mensaje);
            
            $this->enviarRespuesta('success', 'Mensaje enviado correctamente', ['id' => $id]);
        } else {
            $this->enviarRespuesta('error', 'Error al guardar el mensaje');
        }
    }

    private function obtenerMensajes() {
        // Verificar permisos (solo administradores)
        if (!$this->tienePermisosAdministrador()) {
            $this->enviarRespuesta('error', 'No tienes permisos para esta acción');
            return;
        }

        $mensajes = $this->contacto->obtenerMensajes();
        $this->enviarRespuesta('success', 'Mensajes obtenidos', $mensajes);
    }

    private function obtenerMensajePorId() {
        // Verificar permisos
        if (!$this->tienePermisosAdministrador()) {
            $this->enviarRespuesta('error', 'No tienes permisos para esta acción');
            return;
        }

        if (empty($_POST['id'])) {
            $this->enviarRespuesta('error', 'ID no especificado');
            return;
        }

        $id = intval($_POST['id']);
        $mensaje = $this->contacto->obtenerMensajePorId($id);

        if ($mensaje) {
            $this->enviarRespuesta('success', 'Mensaje obtenido', $mensaje);
        } else {
            $this->enviarRespuesta('error', 'Mensaje no encontrado');
        }
    }

    private function eliminarMensaje() {
        // Verificar permisos
        if (!$this->tienePermisosAdministrador()) {
            $this->enviarRespuesta('error', 'No tienes permisos para esta acción');
            return;
        }

        if (empty($_POST['id'])) {
            $this->enviarRespuesta('error', 'ID no especificado');
            return;
        }

        $id = intval($_POST['id']);
        $resultado = $this->contacto->eliminarMensaje($id);

        if ($resultado) {
            $this->enviarRespuesta('success', 'Mensaje eliminado correctamente');
        } else {
            $this->enviarRespuesta('error', 'Error al eliminar el mensaje');
        }
    }

    private function obtenerMensajesPaginados() {
        // Verificar permisos
        if (!$this->tienePermisosAdministrador()) {
            $this->enviarRespuesta('error', 'No tienes permisos para esta acción');
            return;
        }

        $pagina = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;
        $porPagina = isset($_POST['por_pagina']) ? intval($_POST['por_pagina']) : 10;

        // Lógica de paginación (debes implementar este método en la clase Contacto)
        $mensajes = $this->contacto->obtenerMensajesPaginados($pagina, $porPagina);
        $total = $this->contacto->contarMensajes();

        $this->enviarRespuesta('success', 'Mensajes paginados obtenidos', [
            'mensajes' => $mensajes,
            'pagina_actual' => $pagina,
            'total_paginas' => ceil($total / $porPagina),
            'total_mensajes' => $total
        ]);
    }

    // ========== MÉTODOS AUXILIARES DE SEGURIDAD ==========

    private function validarCSRFToken($token) {
        // Implementar validación de token CSRF
        // Ejemplo básico:
        session_start();
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }

    private function limiteMensajesExcedido() {
        // Prevenir spam: máximo 5 mensajes por IP en 1 hora
        $ip = $_SERVER['REMOTE_ADDR'];
        $limite = 5;
        $periodo = 3600; // 1 hora en segundos

        // Implementar lógica de verificación (necesitas una tabla en la BD para esto)
        // Por ahora retornamos false
        return false;
    }

    private function tienePermisosAdministrador() {
        // Verificar si el usuario tiene permisos de administrador
        // Implementar según tu sistema de autenticación
        session_start();
        return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin';
    }

    private function enviarNotificacionEmail($nombre, $email, $mensaje) {
        // Opcional: Enviar email de notificación al administrador
        $asunto = "Nuevo mensaje de contacto de $nombre";
        $cuerpo = "
        Nombre: $nombre
        Email: $email
        Mensaje: $mensaje
        Fecha: " . date('Y-m-d H:i:s') . "
        ";

        // mail('admin@healthbot.com', $asunto, $cuerpo);
    }

    private function enviarRespuesta($estado, $mensaje, $datos = null) {
        header('Content-Type: application/json');
        
        $respuesta = [
            'estado' => $estado,
            'mensaje' => $mensaje
        ];

        if ($datos !== null) {
            $respuesta['datos'] = $datos;
        }

        echo json_encode($respuesta);
        exit;
    }
}

// ========== EJECUCIÓN DEL CONTROLADOR ==========

// Verificar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new CtrlContacto();
    $controlador->procesar();
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'Método no permitido'
    ]);
}
?>