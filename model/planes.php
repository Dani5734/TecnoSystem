<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="../Css/style.css">
<?php
class Planes{
    private $id;
    private $usuario;
    private $contenido;
    private $peso;
    private $altura;
    private $imc;
    private $fecha;

    public function inicializar($id, $usuario, $contenido, $peso, $altura, $imc, $fecha){
        $this->id = $id;
        $this->usuario = $usuario;
        $this->contenido = $contenido;
        $this->peso = $peso;
        $this->altura = $altura;
        $this->imc = $imc;
        $this->fecha = $fecha;
    }

    public function conectarBd()
    {
        $con = mysqli_connect("localhost", "root", "", "healthbot") or die("Problemas con la conexion a la base de datos");
        return $con;
    }
    public function listarPlanes()
    {
        $consulta = mysqli_query($this->conectarBd(), "SELECT * FROM planes WHERE usuario = '$this->usuario'")
            or die(mysqli_error($this->conectarBd()));

        $planes = [];
        while ($reg = mysqli_fetch_array($consulta)) {
            $planes[] = $reg;
        }

        return $planes;

    }

    public function eliminarPlan($id){
        $consulta = mysqli_query($this->conectarBd(), "DELETE * FROM planes WHERE id = '$this->id'")
            or die(mysqli_error($this->conectarBd()));
    }


}

?>