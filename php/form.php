<?php
$cancion_dir = "../uploads/songs/";
$portada_dir = "../uploads/img/"; 
$max_file_size = 20000000; 
$ext_validas_img = array("jpg", "jpeg", "png", "gif");
$ext_validas_cancion = array("mp3", "mp4");

print_r($_POST);
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errores = array();
    $nombre = filter_input(INPUT_POST, 'cancion', FILTER_SANITIZE_SPECIAL_CHARS);
    $artista = filter_input(INPUT_POST, 'artista', FILTER_SANITIZE_SPECIAL_CHARS);

    // Procesar portada
    $nombrePortada = $_FILES['archivo-portada']['name'];
    $filesizeImg = $_FILES['archivo-portada']['size'];
    $directorioPortada = $_FILES['archivo-portada']['tmp_name'];
    $arrayPortada = pathinfo($nombrePortada);
    $extensionPortada = isset($arrayPortada['extension']) ? $arrayPortada['extension'] : '';

    // Procesar canción
    $nombreCancion = $_FILES['archivo-cancion']['name'];
    $filesizeCancion = $_FILES['archivo-cancion']['size'];
    $directorioCancion = $_FILES['archivo-cancion']['tmp_name'];
    $arrayCancion = pathinfo($nombreCancion);
    $extensionCancion = isset($arrayCancion['extension']) ? $arrayCancion['extension'] : '';

    // Validaciones de extensión
    if (!in_array($extensionPortada, $ext_validas_img)) {
        $errores[] = "La extensión del archivo de portada no es válida.";
    }

    if (!in_array($extensionCancion, $ext_validas_cancion)) {
        $errores[] = "La extensión del archivo de la canción no es válida.";
    }

    // Validaciones de tamaño
    if ($filesizeImg > $max_file_size) {
        $errores[] = "La imagen debe tener un tamaño inferior a 10MB.";
    }

    if ($filesizeCancion > $max_file_size) {
        $errores[] = "La canción debe tener un tamaño inferior a 10MB.";
    }

    // Formatear nombres de archivos
    $nombrePortada = preg_replace("/[^A-Z0-9._-]/i", "_", $arrayPortada['filename']);
    $nombreCancion = preg_replace("/[^A-Z0-9._-]/i", "_", $arrayCancion['filename']);

    if (empty($errores)) {
        $portada_file = $portada_dir . $nombrePortada . "." . $extensionPortada;
        $cancion_file = $cancion_dir . $nombreCancion . "." . $extensionCancion;

        // Mover archivos
        if (move_uploaded_file($directorioPortada, $portada_file) && move_uploaded_file($directorioCancion, $cancion_file) ) {
            header("Location: ../canciones.html?status=success&message=Canción subida correctamente");
            exit();
        } else {
            header("Location: ../canciones.html?status=success&message=Error al subir los archivos");
            exit();
        }
        
    } else {
        $error_message = implode(", ", $errores);
        header("Location: ../canciones.html?status=error&message=" . urlencode($error_message));
    }
}