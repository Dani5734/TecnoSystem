<?php
session_start();

if ($_POST) {

  include("./bd.php");

  $sentencia = $conexion->prepare("SELECT *, count(*) as n_usuarios FROM `usuarios`
    WHERE correousuario=:correousuario
    AND contrasena=:contrasena");

  $correousuario = $_POST["correousuario"];
  $contrasena = $_POST["contrasena"];
  $sentencia->bindParam(":correousuario", $correousuario);
  $sentencia->bindParam(":contrasena", $contrasena);

  $sentencia->execute();
  $registro = $sentencia->fetch(PDO::FETCH_LAZY);

  if ($registro["n_usuarios"] > 0) {
    $_SESSION['correousuario'] = $registro["correousuario"];
    $_SESSION['logueado'] = true;
    // Verificar el rol del usuario y redirigir según el rol
    if ($registro['rol'] == 'Administrador') {
      header("Location:index.php"); // Redirige a la gestión de Administradores
    } elseif ($registro['rol'] == 'Usuario') {
      header("Location:perfiluser.html"); // Redirige al perfil del usuario
    } else {
      // Puedes manejar otras opciones aquí
      echo '<script type="text/javascript"> alert ("Rol no reconocido"); </scrip>';
    }
  } else {
    echo '<script type="text/javascript"> alert("Usuario o contraseña incorrecto"); </script>';
  }
}
?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
  integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
  integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('contrasena');

    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);

      // Cambia el ícono del ojo abierto/cerrado
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');
    });
  });
</script>

</html>