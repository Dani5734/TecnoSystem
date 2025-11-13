<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
include __DIR__ . '/../model/conexionBd.php';
$conexionClass = new ConexionBd();
$con = $conexionClass->conectarBd();

// Verificar si se recibió el token de Google
if (isset($_POST['credential'])) {
    $id_token = $_POST['credential'];

    // Decodificar token JWT (header.payload.signature)
    $token_parts = explode('.', $id_token);

    if (count($token_parts) === 3) {
        $payload = json_decode(base64_decode($token_parts[1]), true);

        $nombre = $payload['name'] ?? '';
        $email = $payload['email'] ?? '';
        $foto = $payload['picture'] ?? '';
        $google_id = $payload['sub'] ?? ''; // ID único del usuario de Google

        // Verificar si ya existe el usuario
        $query = $con->prepare("SELECT * FROM usuarios WHERE correoUsuario = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
           
            $userData = $result->fetch_assoc();

            
            $_SESSION['id'] = $userData['id'];
            $_SESSION['nombre'] = $userData['nombre'];
            $_SESSION['apellidos'] = $userData['apellidos'] ?? '';
            $_SESSION['telefono'] = $userData['telefono'] ?? '';
            $_SESSION['edad'] = $userData['edad'] ?? '';
            $_SESSION['genero'] = $userData['genero'] ?? '';
            $_SESSION['correousuario'] = $userData['correoUsuario'];
            $_SESSION['foto_perfil'] = $userData['foto_perfil'] ?? '';
            $_SESSION['tipo_registro'] = $userData['tipo_registro'] ?? 'google';

            // Redirigir al perfil del usuario
            header("Location: ../perfiluser.php");
            exit();

        } else {
          
            echo "<!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Completa tu registro</title>
                <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
            </head>
            <body class='bg-light'>
            <div class='container mt-5'>
                <div class='card shadow p-4'>
                    <h3 class='text-center mb-4'>Completa tu registro</h3>
                    <form action='usuario_google.php' method='post'>
                        <input type='hidden' name='nombre' value='" . htmlspecialchars($nombre) . "'>
                        <input type='hidden' name='correoUsuario' value='" . htmlspecialchars($email) . "'>
                        <input type='hidden' name='google_id' value='" . htmlspecialchars($google_id) . "'>
                        <input type='hidden' name='foto_perfil' value='" . htmlspecialchars($foto) . "'>
                        <input type='hidden' name='tipo_registro' value='google'>

                        <div class='form-group'>
                            <label>Apellidos:</label>
                            <input type='text' class='form-control' name='apellidos' required>
                        </div>
                        <div class='form-group'>
                            <label>Edad:</label>
                            <input type='number' class='form-control' name='edad' min='1' required>
                        </div>
                        <div class='form-group'>
                            <label>Género:</label>
                            <select class='form-control' name='genero' required>
                                <option value=''>Selecciona...</option>
                                <option value='Hombre'>Hombre</option>
                                <option value='Mujer'>Mujer</option>
                                <option value='Otro'>Otro</option>
                            </select>
                        </div>
                        <div class='form-group'>
                            <label>Teléfono:</label>
                            <input type='tel' class='form-control' name='telefono' required>
                        </div>
                        <button type='submit' class='btn btn-primary w-100'>Registrar</button>
                    </form>
                </div>
            </div>
            </body>
            </html>";
        }
    } else {
        echo "Token inválido";
    }
} else {
    echo "No se recibió el token de Google";
}
?>