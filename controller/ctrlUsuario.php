<?php
// Manejo de logout - AGREGAR ESTO AL INICIO
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
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
            $_REQUEST['genero'],
            $_REQUEST['correousuario'],
            $_REQUEST['contrasena']
        );
        $usu->registrarUsuario();
        header("Location: ../index.php?registro=success");
        exit();
        break;

    case '2':
        $usu->consultarUsuarios($_REQUEST['correousuario']);
        break;

    case '3':
        $usu->listaUsuarios();
        break;

    case '4':
        $usu->eliminarUsuario($_REQUEST['id']);
        break;

    case '5':
        $usu->inicializar(
            null,
            $_REQUEST['nombre'],
            $_REQUEST['apellidos'],
            $_REQUEST['telefono'],
            $_REQUEST['edad'],
            $_REQUEST['genero'],
            $_REQUEST['correousuario'],
            $_REQUEST['contrasena']
        );
        $usu->modificarUsuario($_REQUEST['correo_actual']);
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
    case '8': // Nuevo caso para actualizar imagen
        if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
            $idUsuario = $_POST['id_usuario'];
            $imagen = $_FILES['imagen_perfil'];
            
            // Validar tipo de archivo
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($imagen['type'], $tiposPermitidos)) {
                echo "Error: Tipo de archivo no permitido";
                exit;
            }
            
            // Validar tamaño (máximo 2MB)
            if ($imagen['size'] > 2 * 1024 * 1024) {
                echo "Error: La imagen es demasiado grande (máximo 2MB)";
                exit;
            }
            
            // Crear directorio si no existe
            $directorio = '../images/usuarios/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            // Generar nombre único para la imagen
            $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
            $nombreImagen = 'usuario_' . $idUsuario . '_' . time() . '.' . $extension;
            $rutaCompleta = $directorio . $nombreImagen;
            
            // Mover archivo
            if (move_uploaded_file($imagen['tmp_name'], $rutaCompleta)) {
                // Actualizar en base de datos
                if ($usu->actualizarImagenPerfil($idUsuario, 'images/usuarios/' . $nombreImagen)) {
                    echo "success:Imagen actualizada correctamente";
                } else {
                    echo "error:Error al actualizar en la base de datos";
                }
            } else {
                echo "error:Error al subir la imagen";
            }
        } else {
            echo "error:No se seleccionó ninguna imagen o hubo un error en la subida";
        }
        break;

    default:
        session_start();
        session_destroy();
        header("Location:../index.php");
        break;
}
?>