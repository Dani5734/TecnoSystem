<?php
// Para Xampp
$servidor='localhost';
$baseDeDatos='helbootdb';
$usuario='root';
$contrasena='';

try{
    $conexion= new PDO("mysql:host=$servidor; dbname=$baseDeDatos", $usuario, $contrasena);
}catch(Exception $ex){
    echo $ex -> getMessage();
}


?>
