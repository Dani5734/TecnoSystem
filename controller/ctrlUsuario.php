<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CtrlUsuarios</title>
</head>

<body>
    <?php
    
    include('../model/Usuarios.php');
    $usu = new Usuarios();
    $usu->conectarBd();
    switch ($_REQUEST["opcion"]) {
        case '1':
            $usu->inicializar(
                null,
                $_REQUEST['nombre'],
                $_REQUEST['apellidos'],
                $_REQUEST['telefono'],
                $_REQUEST['edad'],
                $_REQUEST['correousuario'],
                $_REQUEST['contrasena']
            );
            $usu->registrarUsuario();
            break;

        case '2':
            $usu->consultarUsuarios($_REQUEST['correousuario']);
            break;

        case '3':
            $usu->listarUsuarios();
            break;

        case '4':
            $usu->eliminarUsuario($_REQUEST['id']);
            break;

        case '5':
            $usu->modificarUsuario($_REQUEST['correo_e']);
            break;

        case '6':
            $usu->actualizarAlumno(
                $_REQUEST['id'],
                $_REQUEST['nombrenuevo'],
                $_REQUEST['apellidosnuevos'],
                $_REQUEST['numeronuevo'],
                $_REQUEST['numeroviejo'],
                $_REQUEST['correo_enuevo']
            );
            break;

        case '7':
            $correousuario = $_REQUEST['correousuario'];
            $contrasena = $_REQUEST['contrasena'];
            $usu->iniciarSesion($correousuario, $contrasena);
            break;

        default:
            session_start();
            session_destroy();
            header("Location:../index.php");
            break;   
    }
    ?>
</body>

</html>
