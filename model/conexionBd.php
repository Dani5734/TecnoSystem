<?php
class ConexionBd{
    public function conectarBd()
    {
        $con = mysqli_connect("localhost", "root", "", "healthbot");
        
        if (!$con) {
            error_log("Error de conexión a BD: " . mysqli_connect_error());
            return false;
        }
        
        mysqli_set_charset($con, "utf8mb4");
        return $con;
    }
}
?>