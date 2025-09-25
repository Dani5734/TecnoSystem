<?php

include("./bd.php");

if ($_POST) {

  // Recolectamos los datos del metodo POST
  $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
  $apellidos = (isset($_POST["apellidos"]) ? $_POST["apellidos"] : "");
//   $rol = (isset($_POST["rol"]) ? $_POST["rol"] : "");
  $correousuario = (isset($_POST["correousuario"]) ? $_POST["correousuario"] : "");
  $contrasena = (isset($_POST["contrasena"]) ? $_POST["contrasena"] : "");
  $edad = (isset($_POST["edad"]) ? $_POST["edad"] : "");
  $peso = (isset($_POST["peso"]) ? $_POST["peso"] : "");
  $estatura = (isset($_POST["estatura"]) ? $_POST["estatura"] : "");

  // Preparar la insercción de los datos
  $sentencia = $conexion->prepare("INSERT INTO usuarios(id, nombre, apellidos, rol, correousuario, contrasena, edad, peso, estatura) VALUES (NULL, :nombre, :apellidos, 'Usuario', :correousuario, :contrasena, :edad, :peso, :estatura)");

  // Asignando los valores del metodo POST (Los que vienen del formulario)
  $sentencia->bindParam(":nombre", $nombre);
  $sentencia->bindParam(":apellidos", $apellidos);
//   $sentencia->bindParam(":rol", $rol);
  $sentencia->bindParam(":correousuario", $correousuario);
  $sentencia->bindParam(":contrasena", $contrasena);
  $sentencia->bindParam(":edad", $edad);
  $sentencia->bindParam(":peso", $peso);
  $sentencia->bindParam(":estatura", $estatura);
  $sentencia->execute();
  $mensaje = "Registro completado";
  header("Location:index.html?mensaje=" . $mensaje);
}

?>