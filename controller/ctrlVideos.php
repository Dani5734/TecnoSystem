<?php
include("../model/videos.php");

$opcion = $_POST['opcion'] ?? null;
$video = new VideosEjercicios();

switch ($opcion) {
    case "insertar":
        $nombre = $_POST['nombre'] ?? "";
        $descripcion = $_POST['descripcion'] ?? "";
        $url = $_POST['url'] ?? "";

        $video->inicializar(null, $nombre, $descripcion, $url);
        $video->insertarVideo();
        echo "ok";
        break;

    case "eliminar":
        $id = $_POST['id'] ?? 0;
        $video->eliminarVideo($id);
        echo "ok";
        break;

    case "listar":
        echo json_encode($video->listarVideos());
        break;
}
?>
