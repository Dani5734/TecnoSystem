<?php
session_start();
require_once("../model/Experiencias.php");
require_once("../model/ConexionBd.php");

if (!isset($_SESSION['nombre'])) {
    header("Location: ../index.php");
    exit();
}

if ($_POST) {
    $opcion = isset($_POST['opcion']) ? $_POST['opcion'] : '';
    
    switch ($opcion) {
        case '1': // Guardar experiencia
            guardarExperiencia();
            break;
        case 'eliminar': // Eliminar experiencia
            eliminarExperiencia();
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

function eliminarExperiencia() {
    // Verificar que el usuario sea administrador
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
        echo "error: No autorizado";
        exit();
    }
    
    // Verificar que el ID esté presente
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo "error: ID no válido";
        return;
    }
    
    $id = trim($_POST['id']);
    
    try {
        $conexion = new ConexionBd();
        $con = $conexion->conectarBd();
        
        if (!$con) {
            echo "error: Error de conexión a la base de datos";
            return;
        }
        
        // Escapar el ID para prevenir SQL injection
        $id = mysqli_real_escape_string($con, $id);
        
        $sql = "DELETE FROM experiencias WHERE id = '$id'";
        $resultado = mysqli_query($con, $sql);
        
        if ($resultado) {
            echo "success: Experiencia eliminada correctamente";
        } else {
            echo "error: No se pudo eliminar la experiencia: " . mysqli_error($con);
        }
        
        mysqli_close($con);
        
    } catch (Exception $e) {
        echo "error: Excepción: " . $e->getMessage();
    }
}
?>