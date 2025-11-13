<?php
session_start();
include("../model/videos.php");

$video = new Videos();
$videos = $video->listarVideos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Videos | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Estilos -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/aos.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="../plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="../css/tooplate-gymso-style.css">
</head>

<body class="bg-light" data-spy="scroll" data-target="#navbarNav" data-offset="50">

  <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="perfiladmin.php">
                <img src="../images/logo4.png" alt="HealthBot" width="45" height="45">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-lg-auto">
                    <li class="nav-item">
                        <a href="perfiladmin.php" class="nav-link">Panel de Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


  <!-- CONTENIDO PRINCIPAL -->
  <section class="section" id="admin-videos">
    <div class="admin-profile-container mt-5">
      <!-- Panel principal -->
      <div class="profile-card admin-dashboard-summary">
        <h2 class="card-title text-center mb-4"><i class="fa fa-video-camera"></i> Gestión de Videos de Ejercicio</h2>

        <!-- Formulario para agregar video -->
        <div class="add-video-form mb-5">
          <h4 class="text-center mb-3">Agregar nuevo video</h4>
          <form id="formVideo" class="p-3 rounded shadow-sm bg-white">
            <div class="form-group mb-3">
              <label class="form-label">Nombre del ejercicio</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="form-group mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group mb-3">
              <label class="form-label">URL del video</label>
              <input type="text" name="url" class="form-control" placeholder="https://www.youtube.com/..." required>
            </div>

            <button type="submit" class="action-button w-100"><i class="fa fa-upload"></i> Guardar Video</button>
          </form>
        </div>

        <!-- Listado de videos -->
        <div class="video-gallery">
          <h4 class="text-center mb-4">Videos registrados</h4>

          <?php if (count($videos) > 0) { ?>
            <div class="row">
              <?php foreach ($videos as $v) { ?>
                <div class="col-md-4 mb-4">
                  <div class="card video-card shadow-sm">
                    <iframe src="<?= htmlspecialchars($v['video_url']); ?>" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($v['nombre_ejercicio']); ?></h5>
                      <p class="card-text"><?= htmlspecialchars($v['descripcion']); ?></p>
                      <button class="btn btn-danger btn-sm w-100" onclick="eliminarVideo(<?= $v['id_video']; ?>)">
                        <i class="fa fa-trash"></i> Eliminar
                      </button>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } else { ?>
            <div class="alert alert-info text-center">
              No hay videos agregados aún.
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="site-footer mt-5">
    <div class="container">
      <div class="row">
        <div class="ml-auto col-lg-4 col-md-5">
          <p class="copyright-text">Copyright &copy; 2025 TecnoSystem</p>
        </div>
        <div class="d-flex justify-content-center mx-auto col-lg-5 col-md-7 col-12">
          <ul class="social-icon ml-lg-3">
            <li><a href="#" class="fa fa-facebook"></a></li>
            <li><a href="#" class="fa fa-twitter"></a></li>
            <li><a href="#" class="fa fa-instagram"></a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- JS -->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="../js/aos.js"></script>
  <script src="../js/custom.js"></script>

  <script>
    AOS.init();

    // Insertar video
    $("#formVideo").on("submit", function(e) {
      e.preventDefault();
      $.post("../controller/ctrlVideos.php", $(this).serialize() + "&opcion=insertar", function(resp) {
        if (resp.trim() === "ok") {
          Swal.fire("Éxito", "Video agregado correctamente", "success").then(() => location.reload());
        }
      });
    });

    // Eliminar video
    function eliminarVideo(id) {
      Swal.fire({
        title: '¿Eliminar video?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post("../controller/ctrlVideos.php", { opcion: "eliminar", id }, function(resp) {
            if (resp.trim() === "ok") {
              Swal.fire("Eliminado", "El video fue eliminado", "success").then(() => location.reload());
            }
          });
        }
      });
    }
  </script>
</body>
</html>
