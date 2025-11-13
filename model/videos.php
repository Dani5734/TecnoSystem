<?php
class Videos{
    private $id_video;
    private $nombre_ejercicio;
    private $descripcion;
    private $video_url;

    public function inicializar($id_video, $nombre_ejercicio, $descripcion, $video_url) {
        $this->id_video = $id_video;
        $this->nombre_ejercicio = $nombre_ejercicio;
        $this->descripcion = $descripcion;
        $this->video_url = $video_url;
    }

    public function conectarBd() {
        $con = mysqli_connect("localhost", "root", "", "healthbot") 
            or die("Error de conexión a la base de datos");
        return $con;
    }

    public function listarVideos() {
        $con = $this->conectarBd();
        $consulta = mysqli_query($con, "SELECT * FROM videos_ejercicios ORDER BY id_video DESC");
        $videos = [];
        while ($fila = mysqli_fetch_assoc($consulta)) {
            $videos[] = $fila;
        }
        mysqli_close($con);
        return $videos;
    }

    public function insertarVideo() {

    $con = $this->conectarBd();

    if (strpos($this->video_url, "watch?v=") !== false) {
        $this->video_url = str_replace("watch?v=", "embed/", $this->video_url);
    } elseif (strpos($this->video_url, "youtu.be/") !== false) {
        $this->video_url = str_replace("youtu.be/", "www.youtube.com/embed/", $this->video_url);
    }
    $stmt = $con->prepare("INSERT INTO videos_ejercicios (nombre_ejercicio, descripcion, video_url) VALUES (?, ?, ?)");

    $stmt->bind_param("sss", $this->nombre_ejercicio, $this->descripcion, $this->video_url);


    $stmt->execute();

    $stmt->close();
    mysqli_close($con);
}


    public function eliminarVideo($id_video) {
        $con = $this->conectarBd();
        $stmt = $con->prepare("DELETE FROM videos_ejercicios WHERE id_video = ?");
        $stmt->bind_param("i", $id_video);
        $stmt->execute();
        $stmt->close();
        mysqli_close($con);
    }
}
?>
