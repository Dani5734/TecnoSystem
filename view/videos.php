<?php
include("../model/conexionBd.php");
$conexion = new ConexionBd();
$con = $conexion->conectarBd();

// Insertar nuevo video
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_ejercicio"])) {
  $nombre = $_POST["nombre_ejercicio"];
  $descripcion = $_POST["descripcion"];
  $url = $_POST["video_url"];

  $stmt = $con->prepare("INSERT INTO videos_ejercicios (nombre_ejercicio, descripcion, video_url) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nombre, $descripcion, $url);
  $stmt->execute();
}

// Obtener lista
$result = $con->query("SELECT * FROM videos_ejercicios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Videos</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="container mt-5">

  <h2>🎥 Gestión de Videos de Ejercicios</h2>

  <form method="POST" class="mb-4">
    <input type="text" name="nombre_ejercicio" class="form-control mb-2" placeholder="Nombre del ejercicio" required>
    <textarea name="descripcion" class="form-control mb-2" placeholder="Descripción"></textarea>
    <input type="text" name="video_url" class="form-control mb-2" placeholder="URL del video (YouTube Embed)" required>
    <button type="submit" class="btn btn-primary">Agregar video</button>
  </form>

  <h4>Videos registrados</h4>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Ejercicio</th>
        <th>Descripción</th>
        <th>Video</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?= htmlspecialchars($row['nombre_ejercicio']) ?></td>
          <td><?= htmlspecialchars($row['descripcion']) ?></td>
          <td><iframe width="250" height="150" src="<?= htmlspecialchars($row['video_url']) ?>" frameborder="0" allowfullscreen></iframe></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

</body>
</html>
