<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

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
  <link rel="stylesheet" href="model/login.php">
  <!-- Fin Login -->

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
      <a class="navbar-brand" href="index.html">
        <img src="images/logo4.png" alt="HealthBot" width="45" height="45" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
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
            <a href="#contact" class="nav-link smoothScroll">Contact</a>
          </li>

          <li class="nav-item">
            <a href="controller/ctrlUsuario.php" class="nav-link" type="button">Cerrar Sesion</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- PERFIL DE USUARIO -->
  <section class="section" id="perfiluser">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-12" data-aos="fade-up" data-aos-delay="100">
          <div class="profile-card user-summary">
            <div class="profile-avatar-wrapper">
              <img src="images/testi2.jpg" alt="Avatar del Usuario" class="profile-avatar">
              <div class="edit-icon-overlay" title="Editar Perfil">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </div>
            </div>
            <h2 class="user-name"><?php echo $_SESSION['nombre'].' '. $_SESSION['apellidos']; ?></h2>
            <p class="user-email"><?php echo $_SESSION['correousuario']; ?></p>
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-lg-6 col-md-6 col-12 mb-4" data-aos="fade-right" data-aos-delay="200">
          <div class="info-card p-4">
            <h5 class="card-title text-center mb-4">Datos del usuario</h5>
            <div class="info-item mb-3">
              <div class="info-label">Edad:</div>
              <div class="info-value" id="userAge"><?php echo $_SESSION['edad']; ?></div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Estatura:</div>
              <div class="info-value" id="userHeight">1.75 m</div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Peso:</div>
              <div class="info-value" id="userWeight">70 kg</div>
            </div>
            <div class="info-item mb-3">
              <div class="info-label">Género:</div>
              <div class="info-value" id="userGender">Masculino</div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-4" data-aos="fade-left" data-aos-delay="200">
          <div class="info-card p-4">
            <h5 class="card-title text-center mb-4">Análisis</h5>
            <div class="info-item mb-3">
              <div class="info-label">IMC:</div>
              <div class="info-value" id="userIMC">22.86</div>
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
            <h3 class="card-title">Mi Plan de Salud</h3>
            <p class="mb-4">"Aquí puedes ver y gestionar tus planes de nutrición y rutinas de ejercicio."</p>
          <button class="btn btn-lg custom-btn" data-aos="fade-up">Ver Mi Plan</button>
          </div>
        </div>
      </div>

      <div class="row justify-content-center mt-4">
        <div class="col-lg-12 col-md-12 col-12">
          <div class="progress-card p-5 text-center" data-aos="zoom-in" data-aos-delay="400">
            <h3 class="card-title">Seguimiento de Progreso</h3>
            <p class="mb-4">"Registra tus comidas y ejercicios para mantener un control preciso."</p>
            <button class="btn btn-primary btn-lg custom-btn">Registrar Progreso</button>
          </div>
        </div>
      </div>

      <div class="row justify-content-center mt-4">
        <div class="col-lg-12 col-md-12 col-12">
          <div class="info-card p-4 text-center" data-aos="fade-up" data-aos-delay="500">
            <h3 class="card-title">Generar Nuevo Plan</h3>
            <p class="mb-4">"¿Estás listo para un nuevo desafío? Genera un plan personalizado."</p>
            <button class="btn btn-primary btn-lg custom-btn">Generar Nuevo Plan</button>
          </div>
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

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/smoothscroll.js"></script>
  <script src="js/custom.js"></script>
  <script src="js/chatbot.js"></script>

  <div id="chat-button" class="chat-button">
    <i class="fa fa-commenting" aria-hidden="true"></i>
  </div>

  <div id="chat-container" class="chat-container">
    <div class="chat-header">
      <span class="chat-logo">
        <img src="images/logo.png" alt="Logo del Chatbot">
      </span>
      <h3 class="chat-title">HealthBot</h3>
      <span id="close-chat" class="close-chat">&times;</span>
    </div>

    <div class="chat-body">
      <div class="welcome-message">
        <p>👋 ¡Hola! Soy Healthbot, tu asistente de bienestar.</p>
        <p>¿En qué puedo ayudarte hoy con tu nutrición o ejercicio?</p>
      </div>

      <div class="chat-options">
        <button class="chat-option-button">📝 Obten Plan nutricional</button>
        <button class="chat-option-button">🚀 Mejora tu salud</button>
        <button class="chat-option-button">📅 Genera una rutina</button>
        <button class="chat-option-button">💬 Salud</button>
      </div>

      <div id="messages-container" class="messages-container">
      </div>

    </div>

    <div class="chat-input-area">
      <input type="text" id="user-input" placeholder="Ask me anything...">
      <button id="send-button">
        <i class="fa fa-paper-plane" aria-hidden="true"></i>
      </button>
    </div>
  </div>
  </div>
</body>

</html>