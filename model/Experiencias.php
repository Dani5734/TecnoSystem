<?php
class Experiencias{
    private $id;
    private $nombre;
    private $tipo_experiencia;
    private $descripcion;
    private $fecha;

    public function __construct() {
        
    }

    public function inicializar($id, $nombre, $tipo_experiencia, $descripcion){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo_experiencia = $tipo_experiencia;
        $this->descripcion = $descripcion;
    }

    // Método para guardar la experiencia en la base de datos
    public function guardarExperiencia($nombre, $tipo_experiencia, $descripcion) {
        $conexion = new ConexionBd();
        $con = $conexion->conectarBd();
        
        if (!$con) {
            return false;
        }

        // Escapar los datos para prevenir SQL injection
        $nombre = mysqli_real_escape_string($con, $nombre);
        $tipo_experiencia = mysqli_real_escape_string($con, $tipo_experiencia);
        $descripcion = mysqli_real_escape_string($con, $descripcion);
        
        $sql = "INSERT INTO experiencias (nombre, tipo_experiencia, descripcion, fecha) 
                VALUES ('$nombre', '$tipo_experiencia', '$descripcion', NOW())";
        
        $resultado = mysqli_query($con, $sql);
        
        mysqli_close($con);
        return $resultado;
    }

    // Método para obtener todas las experiencias
    public function obtenerExperiencias() {
        $conexion = new ConexionBd();
        $con = $conexion->conectarBd();
        
        if (!$con) {
            return false;
        }
        
        $sql = "SELECT * FROM experiencias ORDER BY fecha DESC";
        $resultado = mysqli_query($con, $sql);
        
        $experiencias = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while($fila = mysqli_fetch_assoc($resultado)) {
                $experiencias[] = $fila;
            }
        }
        
        mysqli_close($con);
        return $experiencias;
    }
}
?>