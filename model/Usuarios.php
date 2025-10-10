<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="../Css/style.css">

<?php
class Usuarios
{
    private $id;
    private $nombre;
    private $apellidos;
    private $telefono;
    private $edad;
    private $genero;
    private $correousuario;
    private $contrasena;

    public function conectarBd()
    {
        $con = mysqli_connect("localhost", "root", "", "healthbot") or die("Problemas con la conexion a la base de datos");
        return $con;
    }


    public function inicializar($id = null, $nombre = null, $apellidos = null, $telefono = null, $edad = null, $genero = null, $correousuario = null, $contrasena = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->edad = $edad;
        $this->genero = $genero;
        $this->correousuario = $correousuario;
        $this->contrasena = $contrasena;
    }
    private function encriptarAES($texto)
    {
        require_once("config_seguridad.php");
        return openssl_encrypt($texto, 'AES-256-CBC', AES_KEY, 0, AES_IV);
    }

    private function desencriptarAES($textoCifrado)
    {
        require_once("config_seguridad.php");
        return openssl_decrypt($textoCifrado, 'AES-256-CBC', AES_KEY, 0, AES_IV);
    }

    public function registrarUsuario()
    {
        $registrar = mysqli_query($this->conectarBd(), "SELECT * FROM usuarios WHERE correousuario = '$this->correousuario'") or die(mysqli_error($this->conectarBd()));
        if ($reg = mysqli_fetch_array($registrar)) {
            echo '<a href="../index.html"><i class="fa-solid fa-arrow-left-long"></i></a><br><br>';
            echo "Usuario registrado anteriormente";
        } else {

            $contrasenaCifrada = $this->encriptarAES($this->contrasena);

            $usuarios = mysqli_query($this->conectarBd(), "INSERT INTO usuarios(nombre, apellidos, telefono, edad, genero, correousuario, contrasena) 
            VALUES ('$this->nombre', '$this->apellidos', '$this->telefono', '$this->edad', '$this->genero', '$this->correousuario', '$contrasenaCifrada')")
                or die("Problemas al insertar" . mysqli_error($this->conectarBd()));
            echo '<script type="text/javascript">
        alert("Registro exitoso, bienvenido, Inicie sesión para continuar.");
        window.location.href="../index.html";
        </script>';
        }
    }

    public function iniciarSesion($correousuario, $contrasena)
    {
        require_once("config_seguridad.php");
        session_start();


        $consulta = mysqli_query($this->conectarBd(), "SELECT * FROM usuarios WHERE correousuario = '$correousuario'")
            or die(mysqli_error($this->conectarBd()));

        if ($reg = mysqli_fetch_array($consulta)) {

            $contrasenaCifrada = $reg['contrasena'];
            $contrasenaReal = openssl_decrypt($contrasenaCifrada, 'AES-256-CBC', AES_KEY, 0, AES_IV);


            if ($contrasenaReal === $contrasena) {
                $_SESSION['id'] = $reg['id'];
                $_SESSION['nombre'] = $reg['nombre'];
                $_SESSION['apellidos'] = $reg['apellidos'];
                $_SESSION['telefono'] = $reg['telefono'];
                $_SESSION['edad'] = $reg['edad'];
                $_SESSION['genero'] = $reg['genero'];
                $_SESSION['correousuario'] = $reg['correousuario'];
                $_SESSION['nomusuario'] = $reg['nombre'] . ' ' . $reg['apellidos'];

                echo '<script type="text/javascript">
                window.location.href="../perfiluser.php";
            </script>';
            } else {
                echo '<script type="text/javascript">
                alert("Contraseña incorrecta.");
                window.history.back();
            </script>';
            }
        } else {
            echo '<script type="text/javascript">
            alert("Correo no registrado.");
            window.history.back();
        </script>';
        }
    }

    public function cerrarSesion()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../index.html");
        exit();
    }


    public function consultarUsuarios($correousuario)
    {
        $consulta = mysqli_query($this->conectarBd(), "SELECT * FROM usuarios WHERE correousuario = '$correousuario'") or die(mysqli_error($this->conectarBd()));
        return mysqli_fetch_array($consulta);
    }
}

?>