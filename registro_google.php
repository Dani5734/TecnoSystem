<?php

if (isset($_POST['credential'])) {
    $id_token = $_POST['credential'];

    // Decodificar token JWT (payload)
    $token_parts = explode('.', $id_token);
    if (count($token_parts) === 3) {
        $payload = json_decode(base64_decode($token_parts[1]), true);

        $nombre = $payload['name'] ?? '';
        $email = $payload['email'] ?? '';
        $foto = $payload['picture'] ?? '';

        
        echo '<h2>Completa tus datos</h2>';
        echo '<form action="guardar_usuario.php" method="post">';
        echo '<input type="hidden" name="nombre" value="' . htmlspecialchars($nombre) . '">';
        echo '<input type="hidden" name="email" value="' . htmlspecialchars($email) . '">';
        echo '<input type="hidden" name="foto" value="' . htmlspecialchars($foto) . '">';

        echo '<label>Teléfono:</label><br>';
        echo '<input type="tel" name="telefono" required><br><br>';

        echo '<label>Edad:</label><br>';
        echo '<input type="number" name="edad" min="1" required><br><br>';

        echo '<button type="submit">Registrar</button>';
        echo '</form>';
    } else {
        echo 'Token inválido';
    }
} else {
    echo 'No se recibió el token de Google';
}

/* <?php

if(isset($_POST['credential'])){
    $id_token = $_POST['credential'];


    // Decodificar token (JWT)
    $token_parts = explode('.', $id_token);
    $payload = json_decode(base64_decode($token_parts[1]), true);
    
    $email = $payload['email'];
    $nombre = $payload['name'];
    $foto = $payload['picture'];

    echo "Usuario: $nombre ($email)";
}
?>
*/
?>
