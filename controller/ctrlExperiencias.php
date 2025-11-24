<?php
session_start();
require_once("../model/Experiencias.php");
require_once("../model/ConexionBd.php"); // Asegúrate de incluir la conexión

if (!isset($_SESSION['nombre'])) {
    header("Location: ../index.html");
    exit();
}

if ($_POST) {
    $opcion = isset($_POST['opcion']) ? $_POST['opcion'] : '';
    
    switch ($opcion) {
        case '1': // Guardar experiencia
            guardarExperiencia();
            break;
        default:
            echo "error: Opción no válida";
            break;
    }
} else {
    echo "error: No se recibieron datos POST";
}

function guardarExperiencia() {
    // Verificar que todos los campos estén presentes
    if (!isset($_POST['nombre']) || !isset($_POST['tipo']) || !isset($_POST['mensaje'])) {
        echo "error: Faltan campos obligatorios";
        return;
    }
    
    $nombre = trim($_POST['nombre']);
    $tipo = trim($_POST['tipo']);
    $mensaje = trim($_POST['mensaje']);
    
    // Validaciones básicas
    if (empty($nombre) || empty($tipo) || empty($mensaje)) {
        echo "error: Todos los campos son obligatorios";
        return;
    }
    
    // Validar que el tipo sea válido
    $tiposPermitidos = ['excelente', 'buena', 'regular', 'mala'];
    if (!in_array($tipo, $tiposPermitidos)) {
        echo "error: Tipo de experiencia no válido";
        return;
    }
    
    try {
        $experiencia = new Experiencias();
        $resultado = $experiencia->guardarExperiencia($nombre, $tipo, $mensaje);
        
        if ($resultado) {
            echo "success: Experiencia guardada correctamente";
        } else {
            echo "error: No se pudo guardar la experiencia en la base de datos";
        }
    } catch (Exception $e) {
        echo "error: Excepción: " . $e->getMessage();
    }
}
?>