<?php
session_start();
include("../model/planes.php");

// Verificación de sesión
if (!isset($_SESSION['nombre'])) {
  header("Location: ../index.html");
  exit();
}

$usuario = $_SESSION['nombre'] ?? null;

$planes = new Planes();
$planes->inicializar(null, $usuario, null, null, null, null, null);
$planesUsu = $planes->listarPlanes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Planes | HealthBot</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Bootstrap / Font Awesome / AOS / SweetAlert -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/aos.css">
  <link rel="stylesheet" href="../css/tooplate-gymso-style.css">
  <link rel="stylesheet" href="../css/user.css">
  <link rel="stylesheet" href="../plugins/sweetalert2/sweetalert2.min.css">
</head>

<body class="bg-light" data-spy="scroll" data-target="#navbarNav" data-offset="50">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="../index.html">
        <img src="../images/logo4.png" alt="HealthBot" width="45" height="45" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-lg-auto">
          <li class="nav-item"><a href="../perfiluser.php" class="nav-link">Mi Perfil</a></li>
          <li class="nav-item"><a href="#planes" class="nav-link smoothScroll">Planes</a></li>
          <li class="nav-item"><a href="../controller/ctrlUsuario.php" class="nav-link">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- SECCIÓN PLANES -->
  <section class="section" id="planes">
    <div class="container py-5 mt-5">
      <h2 class="text-center mb-5">Mis planes</h2>

      <?php if (count($planesUsu) > 0) { ?>
        <div class="row">
          <?php foreach ($planesUsu as $plan) { ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card shadow-sm h-100" data-aos="fade-up">
                <div class="card-body">
                  <h5 class="card-title text-center">Plan registrado</h5>
                  <p class="card-text"><?php echo htmlspecialchars($plan['contenido']); ?></p>
                  <ul class="list-unstyled mb-3">
                    <li><strong>Peso:</strong> <?php echo htmlspecialchars($plan['peso']); ?> kg</li>
                    <li><strong>Estatura:</strong> <?php echo htmlspecialchars($plan['estatura']); ?> m</li>
                    <li><strong>IMC:</strong> <?php echo htmlspecialchars($plan['imc']); ?></li>
                  </ul>
                  <form id="formEliminar<?php echo $plan['id']; ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo $plan['id']; ?>">
                    <button type="button" class="btn btn-danger btn-sm w-100"
                      onclick="eliminarPlan(<?php echo $plan['id']; ?>)">
                      <i class="fa fa-trash"></i> Eliminar
                    </button>
                  </form>
                  <small class="text-muted d-block mt-3">Fecha: <?php echo htmlspecialchars($plan['fecha']); ?></small>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <div class="alert alert-info text-center">
          No tienes planes registrados aún.
        </div>
      <?php } ?>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="site-footer">
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
  <script src="../js/aos.js"></script>
  <script src="../js/smoothscroll.js"></script>
  <script src="../js/custom.js"></script>
  <script src="../plugins/sweetalert2/sweetalert2.min.js"></script>

  <script>
    AOS.init();

    function eliminarPlan(id) {
      Swal.fire({
        title: '¿Deseas eliminar este plan?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch('../controller/ctrlPlanes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'opcion=3&id=' + id
          })
          .then(response => response.text())
          .then(() => {
            Swal.fire('Eliminado', 'Tu plan ha sido eliminado exitosamente.', 'success')
              .then(() => location.reload());
          })
          .catch(() => {
            Swal.fire('Error', 'No se pudo eliminar el plan.', 'error');
          });
        }
      });
    }
  </script>
</body>
</html>
