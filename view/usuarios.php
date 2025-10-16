<?php
session_start();
include('../model/Usuarios.php');

$usu = new Usuarios();
$lista_usuarios = $usu->listaUsuarios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestión de Usuarios - HealthBot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Estilos -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tooplate-gymso-style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
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

    <div class="crud-container" style="margin-top: 100px;">
        <h2 class="crud-title">Gestión de Usuarios</h2>
        <a href="crear-usuario.php" class="btn rojo action-button mb-3">Crear Nuevo Usuario</a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>telefono</th>
                        <th>Edad</th>
                        <th>Peso</th>
                        <th>Altura</th>
                        <th>Género</th>
                        <th>Contraseña</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_usuarios)) { ?>
                        <?php foreach ($lista_usuarios as $registro) { ?>
                            <tr>
                                <td><?= $registro['id'] ?></td>
                                <td><?= $registro['nombre'] . ' ' . $registro['apellidos'] ?></td>
                                <td><?= $registro['correousuario'] ?></td>
                                <td><?= $registro['telefono'] ?></td>
                                <td><?= $registro['edad'] ?></td>
                                <td>50 KG</td>
                                <td>1.70 cm</td>
                                <td><?= $registro['genero'] ?></td>
                                <td><?= $registro['contrasena'] ?></td>
                                <td>
                                    <a href="editar-usuario.php?id=<?= $registro['id'] ?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm btn-eliminar" data-id="<?= $registro['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JS -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".btn-eliminar").forEach(btn => {
                btn.addEventListener("click", () => {
                    const id = btn.getAttribute("data-id");
                    Swal.fire({
                        title: "¿Eliminar usuario?",
                        text: "Esta acción no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch("../controller/ctrlUsuario.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: "opcion=4&id=" + id
                            })
                                .then(res => res.text())
                                .then(data => {
                                    if (data.includes("Usuario eliminado correctamente")) {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Eliminado",
                                            text: "El usuario ha sido eliminado.",
                                            confirmButtonText: "Aceptar"
                                        }).then(() => location.reload());
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: "No se pudo eliminar el usuario."
                                        });
                                    }
                                })
                                .catch(() => {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error del servidor",
                                        text: "No se pudo conectar con el servidor."
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>