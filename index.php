<!DOCTYPE html>
<html lang="es"> <!-- Cambié "en" por "es" -->

<head>
  <title>HealthBot</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Login -->
  <script src="https://kit.fontawesome.com/274421acc6.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
  <link rel="icon" type="image/x-icon" href="images/logo_sinfondo.png">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/aos.css">

  <!-- MAIN CSS -->
  <link rel="stylesheet" href="css/tooplate-gymso-style.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body data-spy="scroll" data-target="#navbarNav" data-offset="50">

  <!-- MENU BAR -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <!-- Logo con icono -->
      <a class="navbar-brand" href="index.php"> <!-- Cambiado a .php -->
        <img src="images/logo4.png" alt="HealthBot" width="45" height="45" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-lg-auto">
          <li class="nav-item">
            <a href="#home" class="nav-link smoothScroll">Home</a>
          </li>
          <li class="nav-item">
            <a href="#about" class="nav-link smoothScroll">Nosotros</a>
          </li>
          <li class="nav-item">
            <a href="#class" class="nav-link smoothScroll">Beneficios</a>
          </li>
          <li class="nav-item">
            <a href="#schedule" class="nav-link smoothScroll">Testimonios</a>
          </li>
          <li class="nav-item">
            <a href="#contact" class="nav-link smoothScroll">Contacto</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link smoothScroll" data-toggle="modal" data-target="#IniciarSesion">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero d-flex flex-column justify-content-center align-items-center" id="home">
    <div class="bg-overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto col-12">
          <div class="hero-text mt-5 text-center">
            <h1 class="text-white" data-aos="fade-up" data-aos-delay="500">Bienvenido a HealthBot</h1>
            <a href="#about" class="btn custom-btn bordered mt-3" data-aos="fade-up" data-aos-delay="700">Comenzar</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="feature py-5" id="feature" style="background:#fff;">
    <div class="container">
      <div class="row align-items-center">
        <!-- Texto -->
        <div class="col-lg-5 col-md-6 mb-4 mb-lg-0 text-center">
          <h2 class="mb-3 fw-bold" style="color:#111; font-size:2rem;" data-aos="fade-up">
            Tu camino hacia una vida más saludable
          </h2>
          <p class="lead" style="color:#444;" data-aos="fade-up" data-aos-delay="150">
            En HealthBot combinamos ciencia y tecnología para crear planes personalizados
            de nutrición y ejercicio. No importa tu punto de partida, te guiamos paso a paso
            hacia tus objetivos con recomendaciones seguras y efectivas.
          </p>
        </div>

        <!-- Línea divisoria -->
        <div class="col-lg-2 d-none d-lg-flex justify-content-center">
          <div style="width:5px; background:var(--primary-color); height:350px;"></div>
        </div>

        <img src="images/Imag1.jpg" alt="Trainer" style="width:300px; border:6px solid var(--primary-color); border-radius:15px;
            box-shadow:0 0 15px var(--primary-color),0 0 30px var(--primary-color),0 0 45px var(--primary-color);
            display:block; margin:0 auto;">

      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="about section" id="about"
    style="background-color: #000; color: #d8d8d8; text-align: center; padding: 50px 20px;">
    <div class="container">
      <div class="row">
        <div class="mt-lg-5 mb-lg-0 mb-4 col-lg-8 col-md-10 mx-auto col-12">
          <h2 class="mb-4" data-aos="fade-up" data-aos-delay="300">¡Hola! Soy HealthBot.</h2>
          <p data-aos="fade-up" data-aos-delay="400">
            Creado para ser tu acompañante virtual en salud y bienestar.
          </p>
          <p>
            Soy un chatbot con inteligencia artificial para apoyarte con rutinas de ejercicio personalizadas, planes de
            alimentación balanceados y consejos prácticos que te ayuden a mejorar tu estilo de vida. Siempre estoy
            disponible para escucharte y motivarte,
            convirtiéndome en ese compañero que te impulsa a mantenerte constante y alcanzar tus metas de forma segura y
            amigable.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Beneficios - Tarjetas -->
  <section class="class section" id="class">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-12 text-center mb-5">
          <h6 data-aos="fade-up">Te Ofrezco</h6>
          <h2 data-aos="fade-up" data-aos-delay="200">Beneficios</h2>
        </div>
      </div>

      <div class="row text-center">
        <!-- Tarjeta 1 -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
          <div class="card shadow h-100">
            <img src="images/class/consejos.jpg" class="card-img-top" alt="Consejos"
              style="width:100%; height:250px; object-fit:cover;">
            <div class="card-body">
              <h3 class="card-title">Tu compañero emocional de salud</h3>
              <p class="card-text">No estás solo en este camino. HealthBot comprende tus emociones, celebra tus logros y
                te apoya en los momentos difíciles. Recibe el apoyo que necesitas para seguir adelante.</p>
            </div>
          </div>
        </div>

        <!-- Tarjeta 2 -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
          <div class="card shadow h-100">
            <img src="images/class/comid.png" class="card-img-top" alt="Salud"
              style="width:100%; height:250px; object-fit:cover;">
            <div class="card-body">
              <h3 class="card-title">Planes nutricionales personalizados</h3>
              <p class="card-text">Logra tus objetivos con dietas diseñadas para ti. Pierde peso, gana masa muscular o
                mantén tu figura con alimentos que amas.</p>
            </div>
          </div>
        </div>

        <!-- Tarjeta 3 -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
          <div class="card shadow h-100">
            <img src="images/class/ejercicio.png" class="card-img-top" alt="Cardio"
              style="width:100%; height:250px; object-fit:cover;">
            <div class="card-body">
              <h3 class="card-title">Rutinas personalizadas</h3>
              <p class="card-text">Quema grasa, tonifica tu cuerpo y libera endorfinas. Rutinas basadas en ciencia para
                maximizar resultados adaptados a tus objetivos y nivel.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!--Sección de testimonios-->
  <section id="schedule" class="testimonios-section">
    <div class="container">
      <h2 class="text-center mb-5">Lo que opinan nuestros clientes</h2>

      <!-- Carrusel -->
      <div id="carruselTestimonios" class="carousel slide" data-ride="carousel" data-interval="4000">
        <div class="carousel-inner text-center">
          <?php
          require_once("model/Experiencias.php");
          require_once("model/ConexionBd.php");

          $experienciasModel = new Experiencias();
          $experiencias = $experienciasModel->obtenerExperiencias();

          if (!empty($experiencias)) {
            $active = 'active';
            foreach ($experiencias as $index => $exp) {
              // Asignar imagen rotativa
              $imagen = "images/testi" . (($index % 3) + 1) . ".jpg";

              // Icono según el tipo de experiencia
              $icono = '';
              switch ($exp['tipo_experiencia']) {
                case 'excelente':
                  $icono = '⭐';
                  break;
                case 'buena':
                  $icono = '👍';
                  break;
                case 'regular':
                  $icono = '😐';
                  break;
                case 'mala':
                  $icono = '👎';
                  break;
                default:
                  $icono = '💬';
              }
              ?>
              <!-- Testimonio dinámico -->
              <div class="carousel-item <?php echo $active; ?>">
                <img src="<?php echo $imagen; ?>" class="rounded-circle mx-auto d-block testimonio-img"
                  alt="Testimonio de <?php echo htmlspecialchars($exp['nombre']); ?>">
                <p class="testimonio-texto mt-4">
                  <?php echo $icono . ' ' . htmlspecialchars($exp['descripcion']); ?>
                </p>
                <h5 class="nombre_usuario mt-3"> <?php echo htmlspecialchars($exp['nombre']); ?></h5>
                <p class="cargo mt-3">Usuario de HealthBot</p>
                <small class="text-muted"><?php echo date('d/m/Y', strtotime($exp['fecha'])); ?></small>
              </div>
              <?php
              $active = '';
            }
          } else {
            // Mostrar testimonios por defecto si no hay experiencias
            ?>
            <!-- Testimonio 1 por defecto -->
            <div class="carousel-item active">
              <img src="images/testi2.jpg" class="rounded-circle mx-auto d-block testimonio-img" alt="Testimonio 1">
              <p class="testimonio-texto mt-4">
                Me encanta usar este chat bot, siempre tiene la respuesta exacta y me ahorra mucho tiempo.
              </p>
              <h5 class="mt-3 fw-bold">María</h5>
              <p class="cargo">Consultora en bienestar</p>
            </div>

            <!-- Testimonio 2 por defecto -->
            <div class="carousel-item">
              <img src="images/testi3.jpg" class="rounded-circle mx-auto d-block testimonio-img" alt="Testimonio 2">
              <p class="testimonio-texto mt-4">
                Es muy práctico, me ayuda a organizar mis tareas y siento que tengo un asistente personal.
              </p>
              <h5 class="mt-3 fw-bold">Ana</h5>
              <p class="cargo">Diseñadora UX/UI</p>
            </div>

            <!-- Testimonio 3 por defecto -->
            <div class="carousel-item">
              <img src="images/testi1.jpg" class="rounded-circle mx-auto d-block testimonio-img" alt="Testimonio 3">
              <p class="testimonio-texto mt-4">
                Lo recomiendo totalmente, es sencillo de usar y me ha dado muy buenos resultados.
              </p>
              <h5 class="mt-3 fw-bold">Carolina</h5>
              <p class="cargo">Project Manager</p>
            </div>
          <?php } ?>
        </div>

        <!-- Indicadores (los puntitos) -->
        <ol class="carousel-indicators">
          <?php
          if (!empty($experiencias)) {
            for ($i = 0; $i < count($experiencias); $i++) {
              echo '<li data-target="#carruselTestimonios" data-slide-to="' . $i . '" class="' . ($i === 0 ? 'active' : '') . '"></li>';
            }
          } else {
            echo '<li data-target="#carruselTestimonios" data-slide-to="0" class="active"></li>';
            echo '<li data-target="#carruselTestimonios" data-slide-to="1"></li>';
            echo '<li data-target="#carruselTestimonios" data-slide-to="2"></li>';
          }
          ?>
        </ol>
      </div>
    </div>
  </section>

 <!-- CONTACT -->
<section class="contact section py-5" id="contact">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 contact-info text-center">
        <!-- Información -->
        <h2 class="mb-3">Contáctanos</h2>
        <p class="mb-5">¿Tienes preguntas? Escríbenos y te responderemos pronto.</p>

        <div class="row align-items-stretch">
          <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="h-100 d-flex flex-column">
              <div class="card border-0 shadow-sm h-50 mt-5">
                <div class="card-body d-flex flex-column">
                  <div class="mb-3">
                    <i class="bi bi-whatsapp text-success fs-1"></i>
                  </div>
                  <h5 class="mb-2">WhatsApp</h5>
                  <p class="text-muted mb-3">Respuesta inmediata</p>
                  <div class="mt-auto">
                    <a href="https://wa.me/5512419236?text=Hola%20HealthBot,%20me%20interesa%20saber%20más%20sobre%20sus%20servicios" class="btn btn-success w-100" id="btn-sec1" target="_blank"><i class="bi bi-whatsapp me-2"></i>Escribir ahora</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <form action="controller/ctrlContacto.php" method="post" class="contact-form p-4 rounded shadow-lg h-100" id="formContacto">
              <h5 class="mb-4 text-center">Déjanos tu mensaje</h5>
              <input type="hidden" name="opcion" value="1">

              <!-- Nombre -->
              <div class="mb-3">
                <label for="nombre" class="form-label">
                  <i class="bi bi-person-fill me-2"></i> Nombre completo
                </label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre completo" required>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">
                  <i class="bi bi-envelope-fill me-2"></i> Correo electrónico
                </label>
                <input type="email" class="form-control" id="email" name="email" placeholder="tu.correo@ejemplo.com" required>
              </div>

              <!-- Mensaje -->
              <div class="mb-4">
                <label for="mensaje" class="form-label">
                  <i class="bi bi-pencil-fill me-2"></i> Mensaje
                </label>
                <textarea class="form-control" id="mensaje" name="mensaje" rows="4" 
                          placeholder="Describe tu consulta o mensaje aquí..." required></textarea>
              </div>

              <!-- Botón -->
              <div class="d-grid">
                <button type="submit" class="btn btn-dark btn-lg" id="btnEnviar">
                  <i class="bi bi-send-fill me-2"></i>Enviar mensaje
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>


.card {
  border-radius: 12px;
  transition: transform 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
}

.bi {
  color: var(--primary-color, #007bff);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('formContacto').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btnEnviar = document.getElementById('btnEnviar');
    const originalText = btnEnviar.innerHTML;
    
    // Mostrar estado de envío
    btnEnviar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Enviando...';
    btnEnviar.disabled = true;
    
    // Crear FormData con los datos del formulario
    const formData = new FormData(this);
    
    // Enviar datos REALES al controlador
    fetch('controller/ctrlContacto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.estado === 'success') {
            // Éxito - mensaje guardado en la base de datos
            btnEnviar.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Mensaje enviado';
            btnEnviar.classList.remove('btn-dark');
            btnEnviar.classList.add('btn-success');
            
            // Resetear formulario
            this.reset();
            
            // Mostrar SweetAlert de éxito
            Swal.fire({
                icon: 'success',
                title: '¡Mensaje Enviado!',
                text: 'Tu mensaje ha sido enviado correctamente. Será revisado por nuestro equipo pronto. Te contactaremos por tu correo electronico',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#28a745',
                timer: 5000,
                timerProgressBar: true
            });
            
        } else {
            // Error del servidor
            throw new Error(data.mensaje);
        }
    })
    .catch(error => {
        // Mostrar SweetAlert de error
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '❌ ' + error.message,
            confirmButtonText: 'Intentar de nuevo',
            confirmButtonColor: '#dc3545'
        });
        
        // Restaurar botón
        btnEnviar.innerHTML = originalText;
        btnEnviar.disabled = false;
    })
    .finally(() => {
        // Restaurar botón después de 5 segundos si fue exitoso
        if (btnEnviar.classList.contains('btn-success')) {
            setTimeout(() => {
                btnEnviar.innerHTML = originalText;
                btnEnviar.disabled = false;
                btnEnviar.classList.remove('btn-success');
                btnEnviar.classList.add('btn-dark');
            }, 5000);
        }
    });
});
</script>
  <!-- Modal Inicio de sesión-->
  <div class="modal" id="IniciarSesion" tabindex="-1" role="dialog" aria-labelledby="IniciarSesionLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="IniciarSesionLabel">Iniciar Sesión</h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form action="controller/ctrlUsuario.php" method="post">
            <div class="mb-3">
              <label for="correousuario" class="form-label">Correo</label>
              <input type="email" class="form-control" name="correousuario" id="correousuario"
                placeholder="Correo Electrónico" required>
            </div>

            <div class="mb-3">
              <label for="login-contrasena" class="form-label">Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control" id="login-contrasena" name="contrasena"
                  placeholder="Contraseña" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{8,}$"
                  title="Debe tener al menos una letra minúscula, una mayúscula, un número, mínimo 8 caracteres y sin espacios"
                  required>
                <div class="input-group-append">
                  <span class="input-group-text">
                    <i id="toggleLoginPassword" class="fa fa-eye" style="cursor:pointer;"></i>
                  </span>
                </div>
              </div>
            </div>

            <!-- Enlace para recuperar contraseña -->
            <p class="text-center mt-2">
              <a href="#" data-toggle="modal" data-target="#forgotPasswordModal" data-dismiss="modal">
                ¿Olvidaste tu contraseña?
              </a>
            </p>

            <input type="hidden" name="opcion" value="7" />
            <div class="mb-3">
              <button type="submit" class="custom-btn bg-color mt-3 w-100 rounded-pill" name="submit">
                Iniciar Sesión
              </button>
            </div>

            <!-- Botones de inicio con APIs -->
            <div class="text-center mt-3">
              <p>O inicia sesión con:</p>
              <div id="g_id_onload"
                data-client_id="163821559797-7rjdchh3lfasip35j7j46lfjnstne84s.apps.googleusercontent.com"
                data-login_uri="http://localhost/TecnoSystem/view/registro_google.php" data-auto_prompt="false">
              </div>
              <div class="g_id_signin"></div>
            </div>

            <p class="text-center mt-3">
              ¿No tienes cuenta?
              <a href="#" data-toggle="modal" data-target="#registerModal" data-dismiss="modal">
                Regístrate
              </a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Recuperar contraseña -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog"
    aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperar contraseña</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form action="controller/ctrlUsuario.php" method="POST">
            <div class="mb-3">
              <label for="emailRecuperacion" class="form-label">Ingresa tu correo electrónico</label>
              <input type="email" class="form-control" id="emailRecuperacion" name="correo_recuperacion"
                placeholder="ejemplo@correo.com" required>
            </div>

            <input type="hidden" name="opcion" value="9">
            <button type="submit" class="custom-btn bg-color w-100 mt-3 rounded-pill">
              Enviar código de recuperación
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Fin Modal y Formulario -->


  <!-- Modal de registro -->
  <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Formulario de Registro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="controller/ctrlUsuario.php" method="post" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="reg-nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" required>
              </div>
              <div class="form-group col-md-6">
                <label for="reg-apellidos">Apellidos</label>
                <input type="text" class="form-control" name="apellidos" id="apellidos" aria-describedby="helpId"
                  required>
              </div>
              <div class="form-group col-md-6">
                <label for="reg-apellidos">Teléfono</label>
                <input type="tel" class="form-control" name="telefono" id="telefono" aria-describedby="helpId" required>
              </div>
              <div class="form-group col-md-6">
                <label for="reg-apellidos">Edad</label>
                <input type="text" class="form-control" name="edad" id="edad" aria-describedby="helpId" required>
              </div>

              <div class="form-group col-md-6">
                <label for="reg-genero">Género</label>
                <select class="form-control" name="genero" id="genero" style="width: 466px;" required>
                  <option value="" disabled selected>Selecciona tu género</option>
                  <option value="Hombre">Hombre</option>
                  <option value="Mujer">Mujer</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="reg-correo">Correo Electrónico</label>
              <input type="email" class="form-control" name="correousuario" id="correousuario" aria-describedby="helpId"
                required>
            </div>

            <!-- Campo contraseña -->
            <div class="mb-3">
              <label for="reg-contrasena" class="form-label">Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control" id="reg-contrasena" placeholder="Contraseña"
                  name="contrasena" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{8,}$"
                  title="Debe tener al menos una letra minúscula, una mayúscula, un número, mínimo 8 caracteres y sin espacios"
                  required>
                <span class="input-group-text">
                  <i id="toggleRegisterPassword" class="fa-solid fa-eye" style="cursor:pointer;"></i>
                </span>
              </div>

              <!-- Reglas de validación -->
              <div id="passwordTooltip" style="display:none; font-size:14px; margin-top:5px;">
                <strong>La contraseña debe cumplir:</strong>
                <ul style="margin:5px 0 0 15px; padding:0;">
                  <li id="lower">Al menos una letra minúscula</li>
                  <li id="upper">Al menos una letra mayúscula</li>
                  <li id="number">Al menos un número</li>
                  <li id="length">Mínimo 8 caracteres</li>
                  <li id="space">Sin espacios</li>
                </ul>
              </div>
            </div>

            <!-- Botones de registro con APIs -->
            <div class="text-center mt-3" style="justify-content: center;">
              <p>O regístrate con:</p>
              <div id="g_id_onload"
                data-client_id="163821559797-7rjdchh3lfasip35j7j46lfjnstne84s.apps.googleusercontent.com"
                data-login_uri="http://localhost/TecnoSystem/view/registro_google.php" data-auto_prompt="false">
              </div>
              <div class="g_id_signin"></div>
            </div>

            <input type="hidden" name="opcion" value="1" />

            <div class="mb-3">
              <button type="submit" class="custom-btn bg-color mt-3 w-100 rounded-pill" name="submit">
                Registrarse
              </button>
            </div>


          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
<footer class="site-footer">
    <div class="container">
        <div class="row align-items-center">
            <!-- Copyright a la izquierda -->
            <div class="col-lg-4 col-md-4 col-12">
                <p class="copyright-text">Copyright &copy; 2025 TecnoSystem</p>
            </div>

            <!-- Enlaces legales en el centro -->
            <div class="col-lg-4 col-md-4 col-12">
                <div class="legal-links text-center">
                    <a href="docs/Aviso de Privacidad y Confidencialidad.pdf" target="_blank" class="legal-link">Política de Privacidad</a>
                </div>
            </div>

            <!-- Redes sociales a la derecha -->
            <div class="col-lg-4 col-md-4 col-12">
                <ul class="social-icon d-flex justify-content-end">
                    <li><a href="#" class="fa fa-facebook"></a></li>
                    <li><a href="#" class="fa fa-twitter"></a></li>
                    <li><a href="#" class="fa fa-instagram"></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

  <!-- SCRIPTS -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/smoothscroll.js"></script>
  <script src="js/custom.js"></script>
  <script src="js/chatbot.js"></script>

  <!-- Chatbot -->
<div id="chat-button" class="chat-button">
    <i class="fa fa-commenting" aria-hidden="true"></i>
    <span class="chat-notification">!</span>
    <div class="chat-pulse"></div>
</div>

<div id="chat-container" class="chat-container">
    <!-- El resto del código del chatbot permanece igual -->
    <div class="chat-header">
        <span class="chat-logo">
            <img src="images/logo.png" alt="Logo del Chatbot">
        </span>
        <h3 class="chat-title">HealthBot</h3>
        <div class="chat-controls">
            <span id="expand-chat" class="chat-control-btn">
                <i class="fa fa-expand" aria-hidden="true"></i>
            </span>
            <span id="close-chat" class="chat-control-btn">&times;</span>
        </div>
    </div>

    <div class="chat-body">
        <div class="welcome-message">
            <p>👋 ¡Hola! Soy Healthbot, tu asistente de bienestar.</p>
            <p>¿En qué puedo ayudarte hoy con tu nutrición o ejercicio?</p>
        </div>

        <div class="chat-options">
            <button class="chat-option-button">📝 Beneficios</button>
            <button class="chat-option-button">🚀 Mejora tu salud</button>
            <button class="chat-option-button">💬 Consejos</button>
        </div>

        <div id="messages-container" class="messages-container">
        </div>
    </div>

    <div class="chat-input-area">
        <input type="text" id="user-input" placeholder="Preguntame...">
        <button id="send-button">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
        </button>
    </div>
</div>


  <!--Script de Api Google-->
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>

  <script>
    const params = new URLSearchParams(window.location.search);
    if (params.get('registro') === 'success') {
      Swal.fire({
        icon: 'success',
        title: '¡Registro exitoso!',
        text: 'Tu cuenta ha sido creada correctamente.',
        confirmButtonText: 'Aceptar'
      }).then(() => {
        // Recargar sin parámetros
        window.location.href = "index.php";
      });
    }
  </script>

  <!-- Scripts de funcionalidad -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Toggle password visibility para login
      const toggleLogin = document.getElementById('toggleLoginPassword');
      const loginPass = document.getElementById('login-contrasena');

      if (toggleLogin && loginPass) {
        toggleLogin.addEventListener('click', function () {
          const type = loginPass.getAttribute('type') === 'password' ? 'text' : 'password';
          loginPass.setAttribute('type', type);
          toggleLogin.classList.toggle('fa-eye');
          toggleLogin.classList.toggle('fa-eye-slash');
        });
      }

      // Toggle password visibility para registro
      const toggleRegister = document.getElementById('toggleRegisterPassword');
      const registerPass = document.getElementById('reg-contrasena');

      if (toggleRegister && registerPass) {
        toggleRegister.addEventListener('click', function () {
          const type = registerPass.getAttribute('type') === 'password' ? 'text' : 'password';
          registerPass.setAttribute('type', type);
          toggleRegister.classList.toggle('fa-eye');
          toggleRegister.classList.toggle('fa-eye-slash');
        });
      }

      // Validación de contraseña en tiempo real
      const passwordInput = document.getElementById('reg-contrasena');
      const tooltip = document.getElementById('passwordTooltip');

      if (passwordInput && tooltip) {
        passwordInput.addEventListener('focus', () => {
          tooltip.style.display = 'block';
        });

        passwordInput.addEventListener('blur', () => {
          tooltip.style.display = 'none';
        });

        passwordInput.addEventListener('input', () => {
          const val = passwordInput.value;
          if (document.getElementById('lower')) document.getElementById('lower').style.color = /[a-z]/.test(val) ? 'green' : 'red';
          if (document.getElementById('upper')) document.getElementById('upper').style.color = /[A-Z]/.test(val) ? 'green' : 'red';
          if (document.getElementById('number')) document.getElementById('number').style.color = /\d/.test(val) ? 'green' : 'red';
          if (document.getElementById('length')) document.getElementById('length').style.color = val.length >= 8 ? 'green' : 'red';
          if (document.getElementById('space')) document.getElementById('space').style.color = /\s/.test(val) ? 'red' : 'green';
        });
      }
    });
  </script>
  

</body>

</html>