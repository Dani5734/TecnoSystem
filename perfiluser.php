<?php
require_once("model/Usuarios.php");
require_once("model/Experiencias.php"); // Añadir esta línea
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: index.php");
  exit();
}

$usuario = new Usuarios();
$datosSalud = $usuario->obtenerDatosSalud($_SESSION['nombre']);
?>

<!DOCTYPE html>
<html lang="es">

<head>

  <title>HealthBott</title>

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
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/user.css">

  <!-- MAIN CSS -->
  <link rel="stylesheet" href="css/tooplate-gymso-style.css">
</head>

<body data-spy="scroll" data-target="#navbarNav" data-offset="50">

  <!-- MENU BAR -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">

    <!-- Logo con icono -->
    <a class="navbar-brand" href="index.php">
      <img src="images/logo4.png" alt="HealthBot" width="45" height="45" class="d-inline-block align-text-top">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarUser">
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
        <li class="nav-item"><a class="nav-link" href="perfiluser.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="planes.php">Mis Planes</a></li>
        <li class="nav-item"><a class="nav-link" href="progreso.php">Progreso</a></li>
        
        <!-- Select de Configuración -->
        <li class="nav-item dropdown">
          <select class="form-select nav-config-select" id="configMenu" aria-label="Configuración">
            <option value="" selected disabled>⚙️ Configuración</option>
            <option value="editar">Editar Perfil</option>
            <option value="eliminar">Eliminar Cuenta</option>
            <option value="configuracion">Configuración Avanzada</option>
          </select>
        </li>
        
        <li class="nav-item"> 
          <a href="controller/ctrlUsuario.php" class="nav-link" type="button">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<style>
  .nav-config-select {
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
  border-radius: 20px;
  padding: 6px 12px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 180px;
  margin: 0 10px;
}

.nav-config-select:hover {
  border-color: rgba(255, 255, 255, 0.6);
  background: rgba(0, 0, 0, 0.3);
}

.nav-config-select:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  background: rgba(0, 0, 0, 0.4);
}

/* Estilos para las opciones del select */
.nav-config-select option {
  background: #000;
  color: white;
  padding: 8px;
}

.nav-config-select option:checked {
  background: #333;
}

/* Ajustes responsive */
@media (max-width: 768px) {
  .nav-config-select {
    min-width: 160px;
    font-size: 0.85rem;
    margin: 5px 0;
  }
}
</style>

<script>
// JavaScript para manejar las opciones del select de configuración
document.addEventListener('DOMContentLoaded', function() {
  const configMenu = document.getElementById('configMenu');
  
  if (configMenu) {
    configMenu.addEventListener('change', function() {
      const selectedValue = this.value;
      
      switch(selectedValue) {
        case 'editar':
          // Abrir modal de editar perfil
          const editModal = new bootstrap.Modal(document.getElementById('registerModal'));
          editModal.show();
          break;
          
        case 'eliminar':
          // Disparar el evento de eliminar cuenta
          document.getElementById('btnEliminarCuenta').click();
          break;
          
        case 'configuracion':
          // Redirigir a página de configuración avanzada
          window.location.href = 'configuracion.php';
          break;
      }
      
      // Resetear el select a la opción por defecto
      this.value = '';
    });
  }
});
</script>

  <!-- PERFIL DE USUARIO -->
  <section class="section" id="perfiluser">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-12" data-aos="fade-up" data-aos-delay="100">
          <div class="profile-card user-summary">
            <div class="profile-avatar-wrapper">
              <img src="images/Perfil.gif" alt="Avatar del Usuario" class="profile-avatar">
              <div class="edit-icon-overlay" title="Editar Perfil">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </div>
            </div>
            <h2 class="user-name"><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></h2>
            <p class="user-email"><?php echo $_SESSION['correousuario']; ?></p>
            <div id="camara-check" style="text-align:center; margin-top:20px;">

              <button id="btnCamara"
                style="padding:10px 20px; border:none; background:#4CAF50; color:white; border-radius:8px; cursor:pointer;">
                Activar cámara
              </button>

              <video id="video" autoplay playsinline width="320" height="240"
                style="border-radius:10px; display:none; margin-top:15px;"></video>

              <p id="estado-centro"
                style="font-weight:bold; padding:10px; border-radius:10px; display:none; margin-top:10px;">
                Verificando posición...
              </p>
            </div>
            
            <!-- <div class="password-wrapper">
              <input type="password" id="userPassword" value="<?php echo $_SESSION['contrasena']; ?>" readonly>
              <button type="button" onclick="togglePassword()">👁</button>
            </div> -->

            <script>
              function togglePassword() {
                let passField = document.getElementById("userPassword");
                if (passField.type === "password") {
                  passField.type = "text";
                } else {
                  passField.type = "password";
                }
              }
            </script>

          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-lg-6 col-md-6 col-12 mb-4" data-aos="fade-right" data-aos-delay="200">
          <div class="info-card p-4">
            <h5 class="card-title text-center mb-4">Tus datos</h5>
            <div class="info-item mb-3">
              <div class="info-label">Edad:</div>
              <div class="info-value" id="userAge"><?php echo $_SESSION['edad']; ?></div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Estatura:</div>
              <div class="info-value" id="userHeight">
                <?= isset($datosSalud['estatura']) ? $datosSalud['estatura'] . ' m' : 'No registrada' ?>
              </div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Peso:</div>
              <div class="info-value" id="userWeight">
                <?= isset($datosSalud['peso']) ? $datosSalud['peso'] . 'Kg' : 'No registrada' ?>
              </div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Género:</div>
              <div class="info-value" id="userGender"><?php echo $_SESSION['genero']; ?></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-4" data-aos="fade-left" data-aos-delay="200">
          <div class="info-card p-4">
            <h5 class="card-title text-center mb-4">Análisis</h5>
            <div class="info-item mb-3">
              <div class="info-label">IMC:</div>
              <div class="info-value" id="userIMC">
                <?= isset($datosSalud['imc']) ? $datosSalud['imc'] : 'No registrada' ?>
              </div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">TBM:</div>
              <div class="info-value" id="userTBM">1700 kcal</div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Progreso:</div>
              <div class="info-value" id="userProgress">En seguimiento</div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Resumen:</div>
              <div class="info-value" id="userSummary">Peso saludable</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center mt-4">
        <div class="col-lg-12 col-md-12 col-12">
          <div class="plan-card p-5 text-center" data-aos="zoom-in" data-aos-delay="300">
            <h3 class="card-title">Mis planes</h3>
            <p class="mb-4">"Aquí puedes ver tus planes generados por HealthBot"</p>
            <a href="view/planesUsuario.php"><button class="btn bg-danger border-danger text-white btn-lg">Ver Mi
                Plan</button></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Editar Usuarios -->
  <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Editar Datos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="controller/ctrlUsuario.php" method="post" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="reg-nombre">Nombre</label>
                <input value="<?php echo $_SESSION['nombre']; ?>" type="text" class="form-control" name="nombre"
                  id="nombre" aria-describedby="helpId" required>
              </div>
              <div class="form-group col-md-6">
                <label for="reg-apellidos">Apellidos</label>
                <input value="<?php echo $_SESSION['apellidos']; ?>" type="text" class="form-control" name="apellidos"
                  id="apellidos" aria-describedby="helpId" required>
              </div>
              <div class="form-group col-md-6">
                <label for="reg-apellidos">Teléfono</label>
                <input value="<?php echo $_SESSION['telefono']; ?>" type="tel" class="form-control" name="telefono"
                  id="telefono" aria-describedby="helpId" required>
              </div>
              <div class="form-group col-md-6">
                <label for="reg-apellidos">Edad</label>
                <input value="<?php echo $_SESSION['edad']; ?>" type="tetx" class="form-control" name="edad" id="edad"
                  aria-describedby="helpId" required>
              </div>

              <div class="form-group col-md-6">
                <label for="reg-genero">Género</label>
                <select class="form-control" name="genero" id="genero" style="width: 466px;" required>
                  <option value="<?php echo $_SESSION['genero']; ?>" disabled selected>
                    <?php echo $_SESSION['genero']; ?>
                  </option>
                  <option value="Hombre">Hombre</option>
                  <option value="Mujer">Mujer</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="reg-correo">Correo Electrónico</label>
              <input type="hidden" name="correo_actual" value="<?php echo $_SESSION['correousuario']; ?>">
              <input value="<?php echo $_SESSION['correousuario']; ?>" type="email" class="form-control"
                name="correousuario" id="correousuario" aria-describedby="helpId" required>
            </div>

            <!-- Campo contraseña -->
            <div class="mb-3">
              <label for="reg-contrasena" class="form-label">Contraseña</label>
              <div class="input-group">
                <input value="<?php echo $_SESSION['contrasena']; ?>" type="password" class="form-control"
                  id="reg-contrasena" placeholder="Contraseña" name="contrasena"
                  pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{8,}$"
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

            <input type="hidden" name="opcion" value="5" />
            <div class="mb-3">
              <button type="submit" class="custom-btn bg-color mt-3 w-100 rounded-pill" name="submit">
                Guardar Cambios
              </button>
              <a href="perfiluser.php" type="button" class="custom-btn bg-secondary mt-3 w-100 rounded-pill"
                data-bs-dismiss="modal" aria-label="Close">
                Cancelar
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Sección: Compartir experiencia -->
  <section class="experience section py-5" id="experience">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 contact-info text-center">

          <!-- Encabezado -->
          <h2 class="mb-4 fw-semibold">Comparte tu experiencia</h2>
          <p class="mb-4 fs-5 text-secondary">
            Cuéntanos cómo ha sido tu experiencia usando nuestro chatbot. Tus comentarios nos ayudan a mejorar y aparecerán en nuestra página principal.
          </p>

          <!-- Formulario -->
          <form id="experienceForm" action="controller/ctrlExperiencias.php" method="POST" class="contact-form p-4 rounded shadow-lg text-start bg-white">
            <h5 class="mb-4 text-center fw-semibold">Tu opinión cuenta</h5>

            <div class="mb-3">
              <label for="nombre" class="form-label">
                <i class="fa fa-user me-2"></i> Nombre
              </label>
              <input type="text" class="form-control" id="nombre" name="nombre" 
                     value="<?php echo $_SESSION['nombre']; ?>" 
                     placeholder="Tu nombre" required readonly>
            </div>

            <!-- Tipo de experiencia -->
            <div class="mb-3">
              <label for="tipo" class="form-label">
                <i class="fa fa-smile me-2"></i> Tipo de experiencia
              </label>
              <select class="form-select" id="tipo" name="tipo" required>
                <option value="" selected disabled>Selecciona una opción</option>
                <option value="excelente">⭐ Excelente</option>
                <option value="buena">👍 Buena</option>
                <option value="regular">😐 Regular</option>
                <option value="mala">👎 Mala</option>
              </select>
            </div>

            <!-- Mensaje -->
            <div class="mb-3">
              <label for="mensaje" class="form-label">
                <i class="fa fa-pencil me-2"></i> Cuéntanos más
              </label>
              <textarea class="form-control" id="mensaje" name="mensaje" rows="5"
                        placeholder="Describe brevemente cómo fue tu experiencia con HealthBot..." required></textarea>
            </div>

            <!-- Campo oculto para la opción -->
            <input type="hidden" name="opcion" value="1">

            <!-- Botón -->
            <div class="d-grid">
              <button type="submit" class="btn btn-dark btn-lg">Enviar experiencia</button>
            </div>

            <!-- Mensaje de confirmación -->
            <div id="confirmacion" class="alert alert-success mt-4 d-none text-center" role="alert">
              ¡Gracias por compartir tu experiencia! Aparecerá pronto en nuestra página principal.
            </div>
          </form>

        </div>
      </div>
    </div>
  </section>

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

  <!-- VENTANA CHATBOT -->
  <div id="chat-button" class="chat-button">
    <i class="fa fa-commenting" aria-hidden="true"></i>
  </div>
  <div id="chat-container" class="chat-container">
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
        <p>👋 ¡Hola! <?php echo $_SESSION['nombre'] ?> </p>
        <p>¡Bienvenido de nuevo! ¿Listo para empezar una nueva rutina?</p>
      </div>

      <div class="chat-options">
        <button class="chat-option-button">📝 Obten Plan nutricional</button>
        <button class="chat-option-button">🚀 Ejercicios</button>
        <button class="chat-option-button">📅 Genera una rutina</button>
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
<button id="btnEliminarCuenta" style="display: none;"></button>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/smoothscroll.js"></script>
  <script src="js/custom.js"></script>
  <script src="js/chatbot.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleLogin = document.getElementById('toggleLoginPassword');
      const loginPass = document.getElementById('login-contrasena');

      toggleLogin.addEventListener('click', function () {
        const type = loginPass.getAttribute('type') === 'password' ? 'text' : 'password';
        loginPass.setAttribute('type', type);
        toggleLogin.classList.toggle('fa-eye');
        toggleLogin.classList.toggle('fa-eye-slash');
      });
    });

    //Script de registro 
    document.addEventListener('DOMContentLoaded', function () {
      const toggleRegister = document.getElementById('toggleRegisterPassword');
      const registerPass = document.getElementById('reg-contrasena');

      toggleRegister.addEventListener('click', function () {
        const type = registerPass.getAttribute('type') === 'password' ? 'text' : 'password';
        registerPass.setAttribute('type', type);
        toggleRegister.classList.toggle('fa-eye');
        toggleRegister.classList.toggle('fa-eye-slash');
      });
    });

    const passwordInput = document.getElementById('reg-contrasena');
    const tooltip = document.getElementById('passwordTooltip');

    // Mostrar reglas al enfocar
    passwordInput.addEventListener('focus', () => {
      tooltip.style.display = 'block';
    });

    // Ocultar reglas al perder foco
    passwordInput.addEventListener('blur', () => {
      tooltip.style.display = 'none';
    });

    // Validación en tiempo real
    passwordInput.addEventListener('input', () => {
      const val = passwordInput.value;
      document.getElementById('lower').style.color = /[a-z]/.test(val) ? 'green' : 'red';
      document.getElementById('upper').style.color = /[A-Z]/.test(val) ? 'green' : 'red';
      document.getElementById('number').style.color = /\d/.test(val) ? 'green' : 'red';
      document.getElementById('length').style.color = val.length >= 8 ? 'green' : 'red';
      document.getElementById('space').style.color = /\s/.test(val) ? 'red' : 'green';
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const btnEliminar = document.getElementById("btnEliminarCuenta");

      btnEliminar.addEventListener("click", () => {
        Swal.fire({
          title: "¿Estás seguro?",
          text: "Tu cuenta será eliminada permanentemente.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sí, eliminar",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
            // Enviar petición AJAX
            fetch("controller/ctrlUsuario.php", {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: "opcion=4&id=<?php echo $_SESSION['id']; ?>"
            })
              .then(response => response.text())
              .then(data => {
                // Mostrar mensaje de éxito
                Swal.fire({
                  icon: "success",
                  title: "Cuenta eliminada",
                  text: "Tu cuenta ha sido eliminada exitosamente.",
                  confirmButtonText: "Aceptar"
                }).then(() => {
                  window.location.href = "index.php"; // Redirigir al inicio
                });
              })
              .catch(error => {
                // Mostrar mensaje de error
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Hubo un problema al eliminar tu cuenta. Inténtalo de nuevo.",
                  confirmButtonText: "Aceptar"
                });
              });
          }
        });
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/face_detection.js"></script>

  <script>
  document.addEventListener("DOMContentLoaded", function () {

    const btnCamara = document.getElementById("btnCamara");
    const video = document.getElementById("video");
    const estado = document.getElementById("estado-centro");

    let stream = null;
    let camaraActiva = false;
    let intervaloDeteccion = null;

    btnCamara.addEventListener("click", async function () {
      if (!camaraActiva) {
        // ---- ACTIVAR CÁMARA ----
        try {
          stream = await navigator.mediaDevices.getUserMedia({ video: true });
          video.srcObject = stream;

          video.style.display = "block";
          estado.style.display = "block";
          estado.innerHTML = "Cámara activada. Colócate frente a la cámara.";

          camaraActiva = true;
          btnCamara.innerText = "Desactivar cámara";
          btnCamara.style.background = "#d9534f";

          iniciarDeteccion();

        } catch (err) {
          console.error("Error al acceder a la cámara:", err);
          alert("No se pudo acceder a la cámara.");
        }

      } else {
        // ---- DESACTIVAR CÁMARA ----
        if (stream) {
          stream.getTracks().forEach(track => track.stop());
        }

        video.style.display = "none";
        estado.style.display = "none";
        btnCamara.innerText = "Activar cámara";
        btnCamara.style.background = "#4CAF50";

        camaraActiva = false;

        // Detener detección
        if (intervaloDeteccion) clearInterval(intervaloDeteccion);
      }
    });

    function iniciarDeteccion() {
      const canvas = document.createElement("canvas");
      const ctx = canvas.getContext("2d");

      intervaloDeteccion = setInterval(() => {
        if (!camaraActiva) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Zonas divididas para detectar movimiento
        const zonaCentro = obtenerLuminosidad(canvas, ctx, 0.33, 0.33, 0.33, 0.33);
        const zonaIzquierda = obtenerLuminosidad(canvas, ctx, 0.10, 0.33, 0.20, 0.33);
        const zonaDerecha = obtenerLuminosidad(canvas, ctx, 0.70, 0.33, 0.20, 0.33);

        const umbral = 80; // Ajuste sensible para medir presencia

        // ---- DECISIONES ----
        if (zonaIzquierda > umbral && zonaDerecha < umbral) {
          estado.style.color = "orange";
          estado.innerHTML = "⬅ Te moviste a la izquierda";
        } else if (zonaDerecha > umbral && zonaIzquierda < umbral) {
          estado.style.color = "orange";
          estado.innerHTML = "➡ Te moviste a la derecha";
        } else if (zonaCentro < umbral) {
          estado.style.color = "red";
          estado.innerHTML = "❌ No estás centrado — Reajusta tu posición";
        } else {
          estado.style.color = "green";
          estado.innerHTML = "✔ Perfecto, estás centrado";
        }

      }, 500);
    }

    function obtenerLuminosidad(canvas, ctx, x, y, w, h) {
      const zonaX = canvas.width * x;
      const zonaY = canvas.height * y;
      const zonaW = canvas.width * w;
      const zonaH = canvas.height * h;

      const data = ctx.getImageData(zonaX, zonaY, zonaW, zonaH).data;

      let total = 0;
      for (let i = 0; i < data.length; i += 4) {
        total += (data[i] + data[i + 1] + data[i + 2]) / 3;
      }

      return total / (data.length / 4);
    }

  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Mostrar aviso informativo sin activar nada
    Swal.fire({
      title: 'Funcionalidad de postura',
      html: `
        Para ayudarte a mejorar la postura frente a tu dispositivo,
        HealthBot puede usar la cámara para verificar tu posición y ayudarte a que tengas una mejor experiencia.
        <br><br>
        ✓ La cámara solo se activa cuando tú lo decidas.<br>
        ✓ No se guarda ninguna imagen ni video.<br>
        ✓ Todo se procesa únicamente en tu dispositivo.
      `,
      icon: 'info',
      confirmButtonText: 'Entendido',
      allowOutsideClick: false
    });
  });
</script>

<!-- Script para el formulario de experiencias -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const experienceForm = document.getElementById("experienceForm");
    const confirmacion = document.getElementById("confirmacion");
    
    if (experienceForm) {
        experienceForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Mostrar mensaje de carga
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';
            submitBtn.disabled = true;
            
            // Enviar formulario via AJAX
            const formData = new FormData(this);
            
            fetch('controller/ctrlExperiencias.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                
                // Separar tipo y mensaje de la respuesta
                const [tipo, mensaje] = data.split(': ');
                
                if (tipo === 'success') {
                    // Mostrar mensaje de confirmación
                    confirmacion.textContent = '¡Gracias por compartir tu experiencia! Aparecerá pronto en nuestra página principal.';
                    confirmacion.className = 'alert alert-success mt-4 text-center';
                    confirmacion.classList.remove("d-none");
                    
                    // Reiniciar formulario pero mantener el nombre
                    const nombreValue = document.getElementById('nombre').value;
                    experienceForm.reset();
                    document.getElementById('nombre').value = nombreValue;
                    
                    // Ocultar mensaje después de unos segundos
                    setTimeout(() => {
                        confirmacion.classList.add("d-none");
                    }, 5000);
                } else {
                    // Mostrar error
                    alert('Error: ' + mensaje);
                }
                
                // Restaurar botón
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión. Inténtalo de nuevo.');
                
                // Restaurar botón
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>

</body>
</html>