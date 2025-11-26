<?php
class Contacto {
    private $id;
    private $nombre;
    private $email;
    private $mensaje;
    private $fecha;

    public function __construct() {
        // Constructor vacío
    }

    public function inicializar($id, $nombre, $email, $mensaje, $fecha) {
        $this->id = $this->validarEntero($id);
        $this->nombre = $this->validarTexto($nombre, 100);
        $this->email = $this->validarEmail($email);
        $this->mensaje = $this->validarTexto($mensaje, 1000);
        $this->fecha = $this->validarFecha($fecha);
    }

    /**
     * Valida y sanitiza texto
     */
    private function validarTexto($texto, $maxLength = 255) {
        if (empty($texto)) {
            throw new Exception("El texto no puede estar vacío");
        }

        // Limpiar y sanitizar
        $texto = trim($texto);
        $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
        
        // Validar longitud
        if (strlen($texto) > $maxLength) {
            throw new Exception("El texto excede la longitud máxima de {$maxLength} caracteres");
        }

        // Validar caracteres permitidos (letras, números, espacios y algunos símbolos básicos)
        if (!preg_match('/^[a-zA-Z0-9\sñáéíóúÑÁÉÍÓÚ.,!?¿¡()@:%_\-\n\r]*$/', $texto)) {
            throw new Exception("El texto contiene caracteres no permitidos");
        }

        return $texto;
    }

    /**
     * Valida y sanitiza email
     */
    private function validarEmail($email) {
        if (empty($email)) {
            throw new Exception("El email no puede estar vacío");
        }

        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del email no es válido");
        }

        // Validar longitud máxima
        if (strlen($email) > 100) {
            throw new Exception("El email excede la longitud máxima de 100 caracteres");
        }

        return $email;
    }

    /**
     * Valida números enteros
     */
    private function validarEntero($numero) {
        if (!is_numeric($numero) || $numero < 0) {
            throw new Exception("El ID debe ser un número entero positivo");
        }
        return intval($numero);
    }

    /**
     * Valida fecha
     */
    private function validarFecha($fecha) {
        if (empty($fecha)) {
            return date('Y-m-d H:i:s');
        }

        $fecha = trim($fecha);
        
        // Validar formato de fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/', $fecha)) {
            throw new Exception("El formato de fecha no es válido");
        }

        return $fecha;
    }
    /**
     * Guarda el mensaje de contacto en la base de datos
     */
     public function guardarMensaje($nombre, $email, $mensaje) {
        $conexion = new ConexionBd();
        $con = $conexion->conectarBd();
        
        if (!$con) {
            throw new Exception("Error de conexión a la base de datos");
        }

        try {
            // Validar datos antes de insertar
            $nombreValidado = $this->validarTexto($nombre, 100);
            $emailValidado = $this->validarEmail($email);
            $mensajeValidado = $this->validarTexto($mensaje, 1000);
            $sql = "INSERT INTO contacto (nombre, email, mensaje, fecha) 
                    VALUES (?, ?, ?, NOW())";
            
            $stmt = mysqli_prepare($con, $sql);
            
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . mysqli_error($con));
            }

            // Vincular parámetros
            mysqli_stmt_bind_param($stmt, "sss", $nombreValidado, $emailValidado, $mensajeValidado);
            
            // Ejecutar consulta
            $resultado = mysqli_stmt_execute($stmt);
            
            if (!$resultado) {
                throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
            }

            // Obtener el ID insertado
            $idInsertado = mysqli_insert_id($con);
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);

            return $idInsertado;

        } catch (Exception $e) {
            // Cerrar conexión en caso de error
            if (isset($stmt)) {
                mysqli_stmt_close($stmt);
            }
            mysqli_close($con);
            throw $e;
        }
    }

    public function obtenerMensajes() {
        $conexion = new ConexionBd();
        $con = $conexion->conectarBd();
        
        if (!$con) {
            throw new Exception("Error de conexión a la base de datos");
        }

        try {
            // CORREGIDO: Usar 'contacto' en lugar de 'contactos'
            $sql = "SELECT * FROM contacto ORDER BY fecha DESC";
            $resultado = mysqli_query($con, $sql);
            
            if (!$resultado) {
                throw new Exception("Error en la consulta: " . mysqli_error($con));
            }
            
            $mensajes = [];
            if (mysqli_num_rows($resultado) > 0) {
                while($fila = mysqli_fetch_assoc($resultado)) {
                    // Sanitizar datos antes de devolverlos
                    $fila['nombre'] = htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8');
                    $fila['email'] = htmlspecialchars($fila['email'], ENT_QUOTES, 'UTF-8');
                    $fila['mensaje'] = htmlspecialchars($fila['mensaje'], ENT_QUOTES, 'UTF-8');
                    $mensajes[] = $fila;
                }
            }
            
            mysqli_free_result($resultado);
            mysqli_close($con);
            
            return $mensajes;

        } catch (Exception $e) {
            mysqli_close($con);
            throw $e;
        }
    }

    /**
     * Obtiene un mensaje específico por ID
     */
    public function obtenerMensajePorId($id) {
        $conexion = new ConexionBd();
        $con = $conexion->conectarBd();
        
        if (!$con) {
            throw new Exception("Error de conexión a la base de datos");
        }

        try {
            $idValidado = $this->validarEntero($id);
            
            // CORREGIDO: Usar 'contacto' en lugar de 'contactos'
            $sql = "SELECT * FROM contacto WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . mysqli_error($con));
            }

            mysqli_stmt_bind_param($stmt, "i", $idValidado);
            mysqli_stmt_execute($stmt);
            
            $resultado = mysqli_stmt_get_result($stmt);
            
            if (!$resultado) {
                throw new Exception("Error al obtener resultado: " . mysqli_stmt_error($stmt));
            }
            
            $mensaje = null;
            if ($fila = mysqli_fetch_assoc($resultado)) {
                // Sanitizar datos
                $fila['nombre'] = htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8');
                $fila['email'] = htmlspecialchars($fila['email'], ENT_QUOTES, 'UTF-8');
                $fila['mensaje'] = htmlspecialchars($fila['mensaje'], ENT_QUOTES, 'UTF-8');
                $mensaje = $fila;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            
            return $mensaje;

        } catch (Exception $e) {
            if (isset($stmt)) {
                mysqli_stmt_close($stmt);
            }
            mysqli_close($con);
            throw $e;
        }
    }

    /**
     * Elimina un mensaje por ID
     */
    public function eliminarMensaje($id) {
        $conexion = new ConexionBd();
        $con = $conexion->conectarBd();
        
        if (!$con) {
            throw new Exception("Error de conexión a la base de datos");
        }

        try {
            $idValidado = $this->validarEntero($id);
            
            // CORREGIDO: Usar 'contacto' en lugar de 'contactos'
            $sql = "DELETE FROM contacto WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . mysqli_error($con));
            }

            mysqli_stmt_bind_param($stmt, "i", $idValidado);
            $resultado = mysqli_stmt_execute($stmt);
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            
            return $resultado;

        } catch (Exception $e) {
            if (isset($stmt)) {
                mysqli_stmt_close($stmt);
            }
            mysqli_close($con);
            throw $e;
        }
    }

    // ========== GETTERS Y SETTERS ==========

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMensaje() {
        return $this->mensaje;
    }

    public function getFecha() {
        return $this->fecha;
    }
}
?>