<?php
session_start();
include("../model/planes.php");

$usuario = $_SESSION['nombre'] ?? null; // o $_SESSION['id'], según lo que guardes

$planes = new Planes();
$planes->inicializar(null, $usuario, null, null, null, null, null);
$planesUsu = $planes->listarPlanes();
?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis planes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Bootstrap / AOS / Font Awesome -->

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/aos.css">
    <link rel="stylesheet" href="../css/tooplate-gymso-style.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <h2 class="text-center mb-4">Mis planes</h2>

        <?php if (count($planesUsu) > 0) { ?>
            <div class="row">
                <?php foreach ($planesUsu as $plan) { ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">Plan registrado</h5>
                                <p class="card-text"><?php echo htmlspecialchars($plan['contenido']); ?></p>
                                <ul class="list-unstyled mb-3">
                                    <li><strong>Peso:</strong> <?php echo htmlspecialchars($plan['peso']); ?> kg</li>
                                    <li><strong>Estatura:</strong> <?php echo htmlspecialchars($plan['estatura']); ?> m</li>
                                    <li><strong>IMC:</strong> <?php echo htmlspecialchars($plan['imc']); ?></li>
                                </ul>
                                <form id="formEliminar<?php echo $plan['id']; ?>" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $plan['id']; ?>">
                                    <button type="button" class="btn btn-danger"
                                        onclick="eliminarPlan(<?php echo $plan['id']; ?>)">Eliminar</button>
                                </form>
                                <small class="text-muted">Fecha: <?php echo htmlspecialchars($plan['fecha']); ?></small>
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

</body>

</html>