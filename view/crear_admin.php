<?php
// crear_admin.php - Script de instalación única
require_once "../model/Usuarios.php";
require_once "../model/config_seguridad.php";

// Verificar que estamos en localhost para seguridad
if ($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['SERVER_NAME'] != '127.0.0.1') {
    die("❌ Este script solo puede ejecutarse en localhost por seguridad.");
}

$usu = new Usuarios();
$conexion = $usu->conectarBd();

// Verificar que no existan admins ya
$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'administrador'");
$row = mysqli_fetch_array($result);

if ($row['total'] > 0) {
    echo "<h2>❌ Ya existen administradores en el sistema</h2>";
    echo "<p>No es necesario ejecutar este script.</p>";
    exit();
}

// Crear admin
$nombre = "Administrador Principal";
$apellidos = "HealthBot";
$correo = "admin@healthbot.com";
$contrasena = "Admin1234";
$contrasenaCifrada = openssl_encrypt($contrasena, 'AES-256-CBC', AES_KEY, 0, AES_IV);

$sql = "INSERT INTO usuarios (nombre, apellidos, telefono, edad, genero, correousuario, contrasena, rol) 
        VALUES ('$nombre', '$apellidos', '123456789', 30, 'Hombre', '$correo', '$contrasenaCifrada', 'administrador')";

if (mysqli_query($conexion, $sql)) {
    echo "<h2>✅ Administrador creado exitosamente</h2>";
    echo "<p><strong>Correo:</strong> $correo</p>";
    echo "<p><strong>Contraseña:</strong> $contrasena</p>";
    echo "<div style='background: #ffeb3b; padding: 15px; margin: 20px 0; border-left: 5px solid #ff9800;'>
            <strong>⚠️ IMPORTANTE:</strong> Elimina este archivo por seguridad:
            <code>view/crear_admin.php</code>
          </div>";
    
    // Enlace para ir al login
    echo "<a href='../index.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Ir al Login</a>";
    
} else {
    echo "<h2>❌ Error al crear administrador</h2>";
    echo "<p>" . mysqli_error($conexion) . "</p>";
}
?>