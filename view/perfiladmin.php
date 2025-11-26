<?php
session_start();
if (!isset($_SESSION['nombre']) || $_SESSION['rol'] != 'administrador') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Admin - HealthBot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/274421acc6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    
    <style>
        :root {
            --primary-color: #007bff; /* Azul principal del sitio */
            --secondary-color: #6c757d; /* Gris secundario */
            --accent-color: #28a745; /* Verde de éxito */
            --dark-color: #343a40; /* Color oscuro */
            --light-color: #f8f9fa; /* Color claro */
            --text-dark: #212529; /* Texto oscuro */
            --text-light: #6c757d; /* Texto claro */
        }
        
        body {
            background-color: var(--light-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        #sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--dark-color), #495057);
            color: white;
            position: fixed;
            width: 250px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        #sidebar .sidebar-header img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            border-radius: 8px;
        }
        
        #sidebar ul.components {
            padding: 20px 0;
        }
        
        #sidebar ul li a {
            padding: 15px 20px;
            color: #b8c7ce;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        #sidebar ul li a:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left: 3px solid var(--primary-color);
        }
        
        #sidebar ul li.active > a {
            color: white;
            background: rgba(var(--primary-color), 0.2);
            border-left: 3px solid var(--primary-color);
        }
        
        #content {
            margin-left: 250px;
            padding: 20px;
            background-color: var(--light-color);
        }
        
        .dashboard-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
        }
        
        .top-navbar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: none;
        }
        
        .metric-icon {
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        
        .dashboard-card:hover .metric-icon {
            opacity: 1;
        }
        
        .quick-action-btn {
            border: 2px solid;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 20px 10px;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
                transition: all 0.3s;
            }
            
            #content {
                margin-left: 0;
            }
            
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header text-center">
            <img src="../images/logo4.png" alt="HealthBot" width="50" height="50">
            <h5 class="mt-2" style="color: white;">HealthBot Admin</h5>
        </div>
        
        <ul class="list-unstyled components">
            <li class="active">
                <a href="perfiladmin.php">
                    <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="usuarios.php">
                    <i class="fa fa-users me-2"></i>Gestión de Usuarios
                </a>
            </li>
            <li>
                <a href="estadisticas.php">
                    <i class="fa fa-chart-bar me-2"></i>Estadísticas
                </a>
            </li>
            <li>
                <a href="admin_videos.php">
                    <i class="fa fa-video me-2"></i>Gestión de Videos
                </a>
            </li>
            <li>
                <a href="gestion_experiencias.php">
                    <i class="fa fa-comments me-2"></i>Experiencias
                </a>
            </li>
            <li>
                <a href="configuracion_admin.php">
                    <i class="fa fa-cogs me-2"></i>Configuración
                </a>
            </li>
            <li>
                <a href="../controller/ctrlUsuario.php?logout=true" style="color: #ff6b6b;">
                    <i class="fa fa-sign-out me-2"></i>Cerrar Sesión
                </a>
            </li>
        </ul>
    </nav>

    <!-- Content -->
    <div id="content">
        <!-- Top Bar -->
        <nav class="navbar navbar-light top-navbar mb-4">
            <div class="container-fluid">
                <span class="navbar-text fw-bold">
                    <i class="fa fa-user me-2 text-primary"></i>Bienvenido, <?php echo $_SESSION['nombre']; ?>
                </span>
                <div>
                    <a href="../index.php" class="btn btn-outline-danger btn-sm me-2">
                        <i class="fa fa-external-link-alt me-1"></i>Ir al Sitio
                    </a>
                    <button class="btn btn-outline-secondary btn-sm d-md-none" id="sidebarToggle">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, var(--primary-color), #0056b3);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">150</h3>
                                <p class="mb-0 opacity-75">Usuarios Totales</p>
                            </div>
                            <i class="fa fa-users fa-2x metric-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, var(--accent-color), #1e7e34);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">25</h3>
                                <p class="mb-0 opacity-75">Nuevos Hoy</p>
                            </div>
                            <i class="fa fa-user-plus fa-2x metric-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">48</h3>
                                <p class="mb-0 opacity-75">Consultas</p>
                            </div>
                            <i class="fa fa-comments fa-2x metric-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">95%</h3>
                                <p class="mb-0 opacity-75">Actividad</p>
                            </div>
                            <i class="fa fa-chart-line fa-2x metric-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fa fa-bolt me-2 text-warning"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="usuarios.php" class="btn quick-action-btn btn-outline-primary w-100 py-3">
                                    <i class="fa fa-users me-2"></i>Gestión de Usuarios
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="gestion_experiencias.php" class="btn quick-action-btn btn-outline-secondary w-100 py-3">
                                    <i class="fa fa-comments me-2"></i>Experiencias
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="registro_admin.php" class="btn quick-action-btn btn-outline-dark w-100 py-3">
                                    <i class="fa fa-user-plus me-2"></i>Nuevo Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <script>
        // Toggle sidebar en móviles
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        // Cerrar sidebar al hacer clic fuera en móviles
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                sidebar.classList.contains('active') && 
                !sidebar.contains(event.target) && 
                event.target !== sidebarToggle && 
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>