<?php
session_start();
include('../model/Experiencias.php');

$exp = new Experiencias();
$lista_experiencias = $exp->obtenerExperiencias();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestión de Experiencias - HealthBot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Estilos -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tooplate-gymso-style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .crud-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .crud-title {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }
        
        .table th {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            border: none;
            padding: 15px;
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .badge-excelente {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }
        
        .badge-buena {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .badge-regular {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }
        
        .badge-mala {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }
        
        .btn-presentar {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-presentar:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
            color: white;
        }
        
        .experiencia-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
        }
        
        .stat-card {
            border: none;
            border-radius: 10px;
            color: white;
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
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
                    <li class="nav-item">
                        <a href="gestion_usuarios.php" class="nav-link">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a href="gestion_experiencias.php" class="nav-link active">Experiencias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="crud-container" style="margin-top: 100px;">
        <h2 class="crud-title">Gestión de Experiencias</h2>
        
        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <h4><?= count($lista_experiencias) ?></h4>
                    <p class="text-dark">Total Experiencias</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <h4><?= count(array_filter($lista_experiencias, fn($exp) => $exp['tipo_experiencia'] === 'excelente')) ?></h4>
                    <p class="text-dark">Excelentes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <h4><?= count(array_filter($lista_experiencias, fn($exp) => $exp['tipo_experiencia'] === 'buena')) ?></h4>
                    <p class="text-dark">Buenas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <h4><?= count(array_filter($lista_experiencias, fn($exp) => $exp['tipo_experiencia'] === 'mala')) ?></h4>
                    <p class="text-dark">Malas</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_experiencias)) { ?>
                        <?php foreach ($lista_experiencias as $experiencia) { ?>
                            <tr>
                                <td><?= $experiencia['id'] ?></td>
                                <td><?= htmlspecialchars($experiencia['nombre']) ?></td>
                                <td><?= htmlspecialchars($experiencia['descripcion']) ?></td>
                                <td>
                                    <?php 
                                    $badge_class = '';
                                    $badge_icon = '';
                                    switch($experiencia['tipo_experiencia']) {
                                        case 'excelente':
                                            $badge_class = 'badge-excelente';
                                            $badge_icon = '⭐';
                                            break;
                                        case 'buena':
                                            $badge_class = 'badge-buena';
                                            $badge_icon = '👍';
                                            break;
                                        case 'regular':
                                            $badge_class = 'badge-regular';
                                            $badge_icon = '😐';
                                            break;
                                        case 'mala':
                                            $badge_class = 'badge-mala';
                                            $badge_icon = '👎';
                                            break;
                                        default:
                                            $badge_class = 'badge-secondary';
                                            $badge_icon = '💬';
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class ?> p-2">
                                        <?= $badge_icon ?> <?= ucfirst($experiencia['tipo_experiencia']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($experiencia['fecha'])) ?></td>
                                <td>
                                    <button class="btn btn-presentar btn-sm" 
                                            data-nombre="<?= htmlspecialchars($experiencia['nombre']) ?>"
                                            data-descripcion="<?= htmlspecialchars($experiencia['descripcion']) ?>"
                                            data-tipo="<?= $experiencia['tipo_experiencia'] ?>"
                                            data-fecha="<?= date('d/m/Y', strtotime($experiencia['fecha'])) ?>">
                                        <i class="fa fa-eye me-1"></i>Presentar
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-eliminar" 
                                            data-id="<?= $experiencia['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay experiencias registradas.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para presentar experiencia -->
    <div class="modal fade" id="modalPresentar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Experiencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="experiencia-card">
                        <h4 id="modalNombre" class="mb-3 text-primary"></h4>
                        <div class="mb-3">
                            <strong>Descripción:</strong>
                            <p id="modalDescripcion" class="mb-0 mt-2 p-3 bg-light rounded"></p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="modalTipo" class="badge p-2 fs-6"></span>
                            <small id="modalFecha" class="text-muted"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Eliminar experiencia
            document.querySelectorAll(".btn-eliminar").forEach(btn => {
                btn.addEventListener("click", () => {
                    const id = btn.getAttribute("data-id");
                    Swal.fire({
                        title: "¿Eliminar experiencia?",
                        text: "Esta acción no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    }).then(result => {
                        if (result.isConfirmed) {
                            // Enviar solicitud de eliminación
                            const formData = new FormData();
                            formData.append('opcion', 'eliminar');
                            formData.append('id', id);
                            
                            fetch("../controller/ctrlExperiencias.php", {
                                method: "POST",
                                body: formData
                            })
                            .then(res => res.text())
                            .then(data => {
                                if (data.includes("éxito") || data.includes("correctamente")) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Eliminada",
                                        text: "La experiencia ha sido eliminada correctamente.",
                                        confirmButtonText: "Aceptar"
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "No se pudo eliminar la experiencia: " + data
                                    });
                                }
                            })
                            .catch((error) => {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error del servidor",
                                    text: "No se pudo conectar con el servidor: " + error
                                });
                            });
                        }
                    });
                });
            });

            // Presentar experiencia
            document.querySelectorAll(".btn-presentar").forEach(btn => {
                btn.addEventListener("click", () => {
                    const nombre = btn.getAttribute("data-nombre");
                    const descripcion = btn.getAttribute("data-descripcion");
                    const tipo = btn.getAttribute("data-tipo");
                    const fecha = btn.getAttribute("data-fecha");
                    
                    // Configurar badge según tipo
                    let badgeClass = '';
                    let badgeText = '';
                    let badgeIcon = '';
                    
                    switch(tipo) {
                        case 'excelente':
                            badgeClass = 'badge-excelente';
                            badgeText = 'Excelente';
                            badgeIcon = '⭐';
                            break;
                        case 'buena':
                            badgeClass = 'badge-buena';
                            badgeText = 'Buena';
                            badgeIcon = '👍';
                            break;
                        case 'regular':
                            badgeClass = 'badge-regular';
                            badgeText = 'Regular';
                            badgeIcon = '😐';
                            break;
                        case 'mala':
                            badgeClass = 'badge-mala';
                            badgeText = 'Mala';
                            badgeIcon = '👎';
                            break;
                        default:
                            badgeClass = 'badge-secondary';
                            badgeText = tipo;
                            badgeIcon = '💬';
                    }
                    
                    // Llenar modal
                    document.getElementById('modalNombre').textContent = nombre;
                    document.getElementById('modalDescripcion').textContent = descripcion;
                    document.getElementById('modalTipo').textContent = badgeIcon + ' ' + badgeText;
                    document.getElementById('modalTipo').className = `badge ${badgeClass} p-2`;
                    document.getElementById('modalFecha').textContent = 'Fecha: ' + fecha;
                    
                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('modalPresentar'));
                    modal.show();
                });
            });
        });
    </script>
</body>

</html>