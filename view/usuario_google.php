<?php
include '../model/conexionBd.php';
session_start();

$obj = new ConexionBd();
$con = $obj->conectarBd();

$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellidos'] ?? '';
$edad = $_POST['edad'] ?? null;
$genero = $_POST['genero'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correoUsuario = $_POST['correoUsuario'] ?? '';
$google_id = $_POST['google_id'] ?? '';
$foto_perfil = $_POST['foto_perfil'] ?? '';
$tipo_registro = $_POST['tipo_registro'] ?? 'google';

// Validar conexión
if (!$con) {
    die("Error en la conexión a la base de datos");
}

// Inserción sin contraseña (porque es login con Google)
$stmt = $con->prepare("INSERT INTO usuarios 
    (nombre, apellidos, edad, genero, telefono, correousuario, google_id, foto_perfil, tipo_registro)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssissssss", $nombre, $apellido, $edad, $genero, $telefono, $correoUsuario, $google_id, $foto_perfil, $tipo_registro);

if ($stmt->execute()) {
    $_SESSION['usuario'] = $correoUsuario;
    header("Location: ../perfiluser.php");
    exit;
} else {
    echo "Error al registrar usuario: " . $stmt->error;
}

$stmt->close();
$con->close();
?>