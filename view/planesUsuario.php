<?php
session_start();
include("../model/planes.php");
include("../model/videos.php");

// Verificación de sesión
if (!isset($_SESSION['nombre'])) {
  header("Location: ../index.php");
  exit();
}

$usuario = $_SESSION['nombre'] ?? null;

$planes = new Planes();
$videosModel = new Videos();
$planes->inicializar(null, $usuario, null, null, null, null, null);
$planesUsu = $planes->listarPlanes();
$videos = $videosModel->listarVideos();

// Separar planes nutricionales y de ejercicio
$planesNutricionales = [];
$planesEjercicio = [];

foreach ($planesUsu as $plan) {
    if ($plan['tipo'] === 'nutricional') {
        $planesNutricionales[] = $plan;
    } else if ($plan['tipo'] === 'ejercicio') {
        $planesEjercicio[] = $plan;
    }
}

// Calcular estadísticas
$totalPlanes = count($planesUsu);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Planes | HealthBot</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Bootstrap 4 / Font Awesome / AOS / SweetAlert -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/aos.css">
  <link rel="stylesheet" href="../css/tooplate-gymso-style.css">
  <link rel="stylesheet" href="../css/user.css">
  <link rel="stylesheet" href="../plugins/sweetalert2/sweetalert2.min.css">
  <link rel="icon" type="image/x-icon" href="../images/logo_sinfondo.png">
  
  <!-- Biblioteca para generar PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  
  <style>
    /* Tus estilos existentes... */
    .plan-card {
      border-left: 4px solid #007bff;
      transition: transform 0.3s ease;
    }
    
    .plan-card:hover {
      transform: translateY(-5px);
    }
    
    .nutricion-card {
      border-left-color: #28a745;
    }
    
    .ejercicio-card {
      border-left-color: #dc3545;
    }
    
    .plan-content {
      max-height: 300px;
      overflow-y: auto;
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      font-family: 'Courier New', monospace;
      font-size: 0.9em;
      white-space: pre-wrap;
    }
    
    .nav-tabs .nav-link {
      color: #495057;
      font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
      font-weight: 600;
      background-color: #fff;
      border-bottom-color: #fff;
    }
    
    .tab-pane {
      padding-top: 20px;
    }
    
    .stats-card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }
    
    .stats-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .tip-card {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }
    
    .action-buttons {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }
    
    .nutrition-facts {
      background: white;
      border-radius: 8px;
      padding: 15px;
      margin-top: 15px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .fact-item {
      display: flex;
      justify-content: space-between;
      padding: 5px 0;
      border-bottom: 1px solid #f1f1f1;
    }
    
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #6c757d;
    }
    
    .empty-state i {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.5;
    }
  </style>
</head>

<body class="bg-light" data-spy="scroll" data-target="#navbarNav" data-offset="50">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="../index.php">
        <img src="../images/logo4.png" alt="HealthBot" width="45" height="45" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
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
      <h2 class="text-center mb-5">Mis Planes Personalizados</h2>
      
      <!-- Estadísticas y Resumen -->
      <div class="row mb-5">
        <div class="col-md-4 mb-4">
          <div class="card stats-card text-center bg-primary text-white">
            <div class="card-body">
              <h3 class="card-title"><?php echo $totalPlanes; ?></h3>
              <p class="card-text">Total de Planes</p>
              <i class="fa fa-clipboard-list fa-2x"></i>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card stats-card text-center bg-success text-white">
            <div class="card-body">
              <h3 class="card-title"><?php echo count($planesNutricionales); ?></h3>
              <p class="card-text">Planes Nutricionales</p>
              <i class="fa fa-apple fa-2x"></i>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card stats-card text-center bg-danger text-white">
            <div class="card-body">
              <h3 class="card-title"><?php echo count($planesEjercicio); ?></h3>
              <p class="card-text">Rutinas de Ejercicio</p>
              <i class="fa fa-heartbeat fa-2x"></i>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Consejo del día -->
      <div class="tip-card" data-aos="fade-up">
        <h4><i class="fa fa-lightbulb-o"></i> Consejo de HealthBot</h4>
        <p id="daily-tip"><?php 
          $tips = [
            "Recuerda mantener una hidratación adecuada durante el día",
            "Combina ejercicios cardiovasculares con entrenamiento de fuerza",
            "Incluye proteínas en cada comida para mantener la masa muscular",
            "El descanso es fundamental para la recuperación muscular",
            "Planifica tus comidas con anticipación para mantener una alimentación saludable"
          ];
          echo $tips[array_rand($tips)];
        ?></p>
      </div>

      <!-- Pestañas Bootstrap 4 -->
      <ul class="nav nav-tabs mb-4" id="planTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="nutricion-tab" data-toggle="tab" href="#nutricion" role="tab" aria-controls="nutricion" aria-selected="true">
            <i class="fa fa-apple"></i> Planes Nutricionales
            <span class="badge badge-success ml-1"><?php echo count($planesNutricionales); ?></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="ejercicio-tab" data-toggle="tab" href="#ejercicio" role="tab" aria-controls="ejercicio" aria-selected="false">
            <i class="fa fa-heartbeat"></i> Rutinas de Ejercicio
            <span class="badge badge-danger ml-1"><?php echo count($planesEjercicio); ?></span>
          </a>
        </li>
      </ul>

      <!-- Contenido de pestañas -->
      <div class="tab-content" id="planTabsContent">
        
        <!-- Pestaña Nutrición -->
        <div class="tab-pane fade show active" id="nutricion" role="tabpanel" aria-labelledby="nutricion-tab">
          <?php if (count($planesNutricionales) > 0) { ?>
            <div class="row">
              <?php foreach ($planesNutricionales as $plan) { 
                // Extraer información nutricional si está disponible
                $calorias = preg_match('/calor[íi]as?.*?(\d+)/i', $plan['contenido'], $matches) ? $matches[1] : 'No especificado';
                $proteinas = preg_match('/prote[íi]nas?.*?(\d+)/i', $plan['contenido'], $matches) ? $matches[1] : 'No especificado';
                $carbohidratos = preg_match('/carbohidratos?.*?(\d+)/i', $plan['contenido'], $matches) ? $matches[1] : 'No especificado';
              ?>
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card shadow-sm h-100 plan-card nutricion-card" data-aos="fade-up">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title">
                          <i class="fa fa-apple text-success"></i> Plan Nutricional
                        </h5>
                        <span class="badge badge-success">Nutrición</span>
                      </div>
                      
                      <div class="plan-content mb-3">
                        <?php echo htmlspecialchars($plan['contenido']); ?>
                      </div>
                      
                      <!-- Información nutricional resumida -->
                      <div class="nutrition-facts">
                        <h6 class="text-center">Resumen Nutricional</h6>
                        <div class="fact-item">
                          <span>Calorías:</span>
                          <strong><?php echo $calorias; ?></strong>
                        </div>
                        <div class="fact-item">
                          <span>Proteínas (g):</span>
                          <strong><?php echo $proteinas; ?></strong>
                        </div>
                        <div class="fact-item">
                          <span>Carbohidratos (g):</span>
                          <strong><?php echo $carbohidratos; ?></strong>
                        </div>
                      </div>
                      
                      <div class="plan-meta">
                        <?php if ($plan['peso'] && $plan['estatura']) { ?>
                          <div class="row small text-muted mb-2">
                            <div class="col-6">
                              <strong>Peso:</strong><br>
                              <?php echo htmlspecialchars($plan['peso']); ?> kg
                            </div>
                            <div class="col-6">
                              <strong>Estatura:</strong><br>
                              <?php echo htmlspecialchars($plan['estatura']); ?> m
                            </div>
                          </div>
                          <?php if ($plan['imc']) { ?>
                            <div class="mb-2">
                              <strong>IMC:</strong> 
                              <?php echo htmlspecialchars(number_format($plan['imc'], 1)); ?>
                              <?php
                                $imc = $plan['imc'];
                                if ($imc < 18.5) {
                                    echo '<span class="badge badge-info ml-1">Bajo peso</span>';
                                } elseif ($imc < 25) {
                                    echo '<span class="badge badge-success ml-1">Normal</span>';
                                } elseif ($imc < 30) {
                                    echo '<span class="badge badge-warning ml-1">Sobrepeso</span>';
                                } else {
                                    echo '<span class="badge badge-danger ml-1">Obesidad</span>';
                                }
                              ?>
                            </div>
                          <?php } ?>
                        <?php } ?>
                        
                        <small class="text-muted">
                          <i class="fa fa-calendar"></i> 
                          <?php echo date('d/m/Y H:i', strtotime($plan['fecha'])); ?>
                        </small>
                      </div>
                      
                      <div class="action-buttons">
                        <button type="button" class="btn btn-outline-primary btn-sm flex-fill export-pdf-btn"
                          data-plan-id="<?php echo $plan['id']; ?>"
                          data-tipo="nutricional"
                          data-contenido="<?php echo htmlspecialchars($plan['contenido']); ?>"
                          data-peso="<?php echo $plan['peso']; ?>"
                          data-estatura="<?php echo $plan['estatura']; ?>"
                          data-imc="<?php echo $plan['imc']; ?>">
                          <i class="fa fa-file-pdf-o"></i> Exportar PDF
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm flex-fill"
                          onclick="eliminarPlan(<?php echo $plan['id']; ?>)">
                          <i class="fa fa-trash"></i> Eliminar
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } else { ?>
            <div class="empty-state">
              <i class="fa fa-apple"></i>
              <h4>No tienes planes nutricionales</h4>
              <p>Puedes generar uno en el chat con HealthBot</p>
              <a href="../chat.php" class="btn btn-success mt-3">
                <i class="fa fa-comments"></i> Ir al Chat
              </a>
            </div>
          <?php } ?>
        </div>

        <!-- Pestaña Ejercicio -->
        <div class="tab-pane fade" id="ejercicio" role="tabpanel" aria-labelledby="ejercicio-tab">
          <?php if (count($planesEjercicio) > 0) { ?>
            <div class="row">
              <?php foreach ($planesEjercicio as $plan) { 
                // Detectar nivel de dificultad
                $dificultad = 'Intermedio';
                if (stripos($plan['contenido'], 'principiante') !== false) {
                    $dificultad = 'Principiante';
                } elseif (stripos($plan['contenido'], 'avanzado') !== false || 
                         stripos($plan['contenido'], 'experto') !== false) {
                    $dificultad = 'Avanzado';
                }
                
                // Detectar días de entrenamiento
                $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                $trainingDays = [];
                foreach ($days as $day) {
                    if (stripos($plan['contenido'], $day) !== false) {
                        $trainingDays[] = $day;
                    }
                }
                $trainingDaysCount = count($trainingDays);
              ?>
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card shadow-sm h-100 plan-card ejercicio-card" data-aos="fade-up">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title">
                          <i class="fa fa-heartbeat text-danger"></i> Rutina de Ejercicio
                        </h5>
                        <span class="badge badge-danger">Ejercicio</span>
                      </div>
                      
                      <!-- Información rápida de la rutina -->
                      <div class="d-flex justify-content-between small text-muted mb-3">
                        <span><i class="fa fa-signal"></i> <?php echo $dificultad; ?></span>
                        <span><i class="fa fa-calendar"></i> <?php echo $trainingDaysCount; ?> días/semana</span>
                      </div>
                      
                      <div class="plan-content mb-3">
                        <?php echo htmlspecialchars($plan['contenido']); ?>
                      </div>
                      
                      <div class="plan-meta">
                        <small class="text-muted">
                          <i class="fa fa-calendar"></i> 
                          <?php echo date('d/m/Y H:i', strtotime($plan['fecha'])); ?>
                        </small>
                      </div>
                      
                      <div class="action-buttons">
                        <button type="button" class="btn btn-outline-primary btn-sm flex-fill export-pdf-btn"
                          data-plan-id="<?php echo $plan['id']; ?>"
                          data-tipo="ejercicio"
                          data-contenido="<?php echo htmlspecialchars($plan['contenido']); ?>"
                          data-dificultad="<?php echo $dificultad; ?>"
                          data-dias-entrenamiento="<?php echo $trainingDaysCount; ?>">
                          <i class="fa fa-file-pdf-o"></i> Exportar PDF
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm flex-fill"
                          onclick="eliminarPlan(<?php echo $plan['id']; ?>)">
                          <i class="fa fa-trash"></i> Eliminar
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } else { ?>
            <div class="empty-state">
              <i class="fa fa-heartbeat"></i>
              <h4>No tienes rutinas de ejercicio</h4>
              <p>Puedes generar una en el chat con HealthBot</p>
              <a href="../chat.php" class="btn btn-danger mt-3">
                <i class="fa fa-comments"></i> Ir al Chat
              </a>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN VIDEOS -->
  <section class="section bg-white" id="videos">
    <div class="container py-5">
      <h2 class="text-center mb-5">Videos recomendados por HealthBot</h2>

      <?php if (count($videos) > 0) { ?>
        <div class="row">
          <?php foreach ($videos as $video) { ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card shadow-sm h-100" data-aos="fade-up">
                <div class="card-body">
                  <h5 class="card-title text-center text-primary">
                    <i class="fa fa-play-circle"></i>
                    <?php echo htmlspecialchars($video['nombre_ejercicio']); ?>
                  </h5>
                  <p class="card-text text-muted">
                    <?php echo htmlspecialchars($video['descripcion']); ?>
                  </p>

                  <div class="embed-responsive embed-responsive-16by9 mb-3">
                    <iframe 
                      class="embed-responsive-item"
                      src="<?php echo htmlspecialchars($video['video_url']); ?>" 
                      title="Video de ejercicio"
                      frameborder="0"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                      allowfullscreen>
                    </iframe>
                  </div>
                  
                  <small class="text-muted">
                    <i class="fa fa-clock-o"></i> 
                    <?php echo htmlspecialchars($video['duracion'] ?? 'Duración no especificada'); ?>
                  </small>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <div class="alert alert-info text-center">
          <i class="fa fa-info-circle"></i> Aún no hay videos cargados por el administrador.
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
  <script src="../js/bootstrap.min.js"></script> <!-- Bootstrap 4 -->
  <script src="../js/aos.js"></script>
  <script src="../js/smoothscroll.js"></script>
  <script src="../js/custom.js"></script>
  <script src="../plugins/sweetalert2/sweetalert2.min.js"></script>

  <script>
    AOS.init();

    // Event listener para los botones de exportar PDF
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.export-pdf-btn').forEach(button => {
        button.addEventListener('click', function() {
          const planId = this.getAttribute('data-plan-id');
          const tipo = this.getAttribute('data-tipo');
          const contenido = this.getAttribute('data-contenido');
          const peso = this.getAttribute('data-peso');
          const estatura = this.getAttribute('data-estatura');
          const imc = this.getAttribute('data-imc');
          const dificultad = this.getAttribute('data-dificultad');
          const diasEntrenamiento = this.getAttribute('data-dias-entrenamiento');
          
          exportarPDF(planId, tipo, contenido, peso, estatura, imc, dificultad, diasEntrenamiento);
        });
      });
    });

    function eliminarPlan(id) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        backdrop: true
      }).then((result) => {
        if (result.isConfirmed) {
          fetch('../controller/ctrlPlanes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'opcion=3&id=' + id
          })
          .then(response => response.text())
          .then(() => {
            Swal.fire({
              title: '¡Eliminado!',
              text: 'Tu plan ha sido eliminado exitosamente.',
              icon: 'success',
              confirmButtonColor: '#007bff'
            }).then(() => location.reload());
          })
          .catch(() => {
            Swal.fire({
              title: 'Error',
              text: 'No se pudo eliminar el plan.',
              icon: 'error',
              confirmButtonColor: '#007bff'
            });
          });
        }
      });
    }

    function exportarPDF(planId, tipo, contenido, peso, estatura, imc, dificultad, diasEntrenamiento) {
      console.log('Generando PDF para plan:', planId);
      
      // Mostrar loading
      Swal.fire({
        title: 'Generando PDF...',
        text: 'Por favor espera un momento',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // Crear contenido HTML para el PDF
      const fechaActual = new Date().toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });

      // Limpiar y preparar el contenido
      const contenidoLimpio = contenido
        .replace(/&quot;/g, '"')
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&#039;/g, "'");

      let pdfContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>HealthBot - Plan ${tipo === 'nutricional' ? 'Nutricional' : 'de Ejercicio'}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 20px; 
                    color: #333;
                    line-height: 1.4;
                }
                .header { 
                    text-align: center; 
                    border-bottom: 2px solid #007bff; 
                    padding-bottom: 15px; 
                    margin-bottom: 20px; 
                }
                .section { 
                    margin-bottom: 20px; 
                }
                .section-title { 
                    background: #f8f9fa; 
                    padding: 8px 15px; 
                    border-left: 4px solid #007bff; 
                    margin-bottom: 10px; 
                    font-size: 16px; 
                    font-weight: bold; 
                }
                .content { 
                    padding: 10px; 
                    font-family: 'Courier New', monospace; 
                    white-space: pre-wrap; 
                    font-size: 12px; 
                    background: #f9f9f9;
                    border-radius: 5px;
                    line-height: 1.6;
                }
                .user-info { 
                    margin-top: 20px; 
                    padding: 15px; 
                    background: #f0f8ff; 
                    border-radius: 5px; 
                }
                .footer { 
                    margin-top: 30px; 
                    padding-top: 15px; 
                    border-top: 1px solid #ddd; 
                    text-align: center; 
                    font-size: 12px; 
                    color: #666; 
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin: 10px 0; 
                }
                table, th, td { 
                    border: 1px solid #ddd; 
                }
                th, td { 
                    padding: 8px; 
                    text-align: left; 
                }
                th { 
                    background-color: #f2f2f2; 
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>HealthBot - Plan ${tipo === 'nutricional' ? 'Nutricional' : 'de Ejercicio'} Personalizado</h2>
                <p><strong>Generado el:</strong> ${fechaActual}</p>
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></p>
            </div>
            
            <div class="section">
                <div class="section-title">Plan ${tipo === 'nutricional' ? 'Nutricional' : 'de Ejercicio'}</div>
                <div class="content">${contenidoLimpio}</div>
            </div>`;

      // Agregar información específica según el tipo de plan
      if (tipo === 'nutricional') {
        const calorias = contenidoLimpio.match(/calor[íi]as?.*?(\d+)/i) ? contenidoLimpio.match(/calor[íi]as?.*?(\d+)/i)[1] : 'No especificado';
        const proteinas = contenidoLimpio.match(/prote[íi]nas?.*?(\d+)/i) ? contenidoLimpio.match(/prote[íi]nas?.*?(\d+)/i)[1] : 'No especificado';
        const carbohidratos = contenidoLimpio.match(/carbohidratos?.*?(\d+)/i) ? contenidoLimpio.match(/carbohidratos?.*?(\d+)/i)[1] : 'No especificado';
        
        pdfContent += `
            <div class="section">
                <div class="section-title">Información Nutricional</div>
                <table>
                    <tr>
                        <th>Calorías</th>
                        <th>Proteínas (g)</th>
                        <th>Carbohidratos (g)</th>
                    </tr>
                    <tr>
                        <td>${calorias}</td>
                        <td>${proteinas}</td>
                        <td>${carbohidratos}</td>
                    </tr>
                </table>
            </div>`;
      } else {
        pdfContent += `
            <div class="section">
                <div class="section-title">Información del Ejercicio</div>
                <p><strong>Nivel de dificultad:</strong> ${dificultad || 'Intermedio'}</p>
                <p><strong>Días de entrenamiento por semana:</strong> ${diasEntrenamiento || 'No especificado'}</p>
            </div>`;
      }

      // Agregar datos del usuario si están disponibles
      if (peso && estatura && peso !== 'null' && estatura !== 'null') {
        let estadoIMC = '';
        if (imc && imc !== 'null') {
          const imcNum = parseFloat(imc);
          if (imcNum < 18.5) estadoIMC = 'Bajo peso';
          else if (imcNum < 25) estadoIMC = 'Normal';
          else if (imcNum < 30) estadoIMC = 'Sobrepeso';
          else estadoIMC = 'Obesidad';
        }

        pdfContent += `
            <div class="user-info">
                <div class="section-title">Datos del Usuario</div>
                <p><strong>Peso:</strong> ${peso} kg</p>
                <p><strong>Estatura:</strong> ${estatura} m</p>
                ${imc && imc !== 'null' ? `<p><strong>IMC:</strong> ${parseFloat(imc).toFixed(1)} (${estadoIMC})</p>` : ''}
            </div>`;
      }

      pdfContent += `
            <div class="footer">
                <p>© 2025 HealthBot - TecnoSystem</p>
                <p>Este plan fue generado automáticamente por HealthBot</p>
            </div>
        </body>
        </html>`;

      // Crear elemento temporal para el PDF
      const element = document.createElement('div');
      element.innerHTML = pdfContent;

      // Configuración para el PDF
      const opt = {
        margin: [10, 10, 10, 10],
        filename: `healthbot-plan-${tipo}-${planId}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { 
          scale: 2,
          useCORS: true,
          logging: true
        },
        jsPDF: { 
          unit: 'mm', 
          format: 'a4', 
          orientation: 'portrait' 
        }
      };

      // Generar PDF
      setTimeout(() => {
        html2pdf()
          .set(opt)
          .from(element)
          .save()
          .then(() => {
            Swal.close();
            Swal.fire({
              title: '¡PDF Generado!',
              text: 'Tu plan ha sido exportado exitosamente.',
              icon: 'success',
              confirmButtonColor: '#007bff',
              timer: 2000
            });
          })
          .catch(error => {
            console.error('Error al generar PDF:', error);
            Swal.fire({
              title: 'Error',
              text: 'No se pudo generar el PDF. Inténtalo de nuevo.',
              icon: 'error',
              confirmButtonColor: '#007bff'
            });
          });
      }, 1000);
    }

    // Script para inicializar pestañas
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Página cargada - pestañas Bootstrap 4 inicializadas');
      
      $('a[data-toggle="tab"]').on('click', function() {
        console.log('Pestaña clickeada:', this.id);
      });
    });
  </script>
</body>
</html>