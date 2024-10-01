<?php
$cancion_dir = "../uploads/songs/";
$portada_dir = "../uploads/img/";
$txt_dir = "../uploads/txt/";

$max_file_size = 20000000;

$ext_validas_img = array("jpg", "jpeg", "png", "gif");
$ext_validas_cancion = array("mp3", "mp4");
$ext_validas_txt = array("txt");

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

    // Procesar archivo TXT
    $nombreTXT = $_FILES['archivo-txt']['name'];
    $filesizeTXT = $_FILES['archivo-txt']['size'];
    $directorioTXT = $_FILES['archivo-txt']['tmp_name'];
    $arrayTXT = pathinfo($nombreTXT);
    $extensionTXT = isset($arrayTXT['extension']) ? $arrayTXT['extension'] : '';

    // Validaciones de extensión
    if (!in_array($extensionPortada, $ext_validas_img)) {
        $errores[] = "La extensión del archivo de portada no es válida.";
    }

    if (!in_array($extensionCancion, $ext_validas_cancion)) {
        $errores[] = "La extensión del archivo de la canción no es válida.";
    }

    // Validaciones de tamaño
    if ($filesizeImg > $max_file_size) {
        $errores[] = "La imagen debe tener un tamaño inferior a 20MB.";
    }

    if ($filesizeCancion > $max_file_size) {
        $errores[] = "La canción debe tener un tamaño inferior a 20MB.";
    }

    // Verificar errores al subir archivos
    if ($_FILES['archivo-portada']['error'] !== UPLOAD_ERR_OK) {
        switch ($_FILES['archivo-portada']['error']) {
            case UPLOAD_ERR_PARTIAL:
                exit("La portada fue subida parcialmente");
            case UPLOAD_ERR_NO_FILE:
                exit("No se subió ninguna portada");
            case UPLOAD_ERR_EXTENSION:
                exit("La subida de la portada fue interrumpida debido a la extensión de PHP");
            case UPLOAD_ERR_FORM_SIZE:
                exit("La portada excede el tamaño de subida permitido");
            default:
                exit("Error desconocido al subir la portada");
        }
    }

    if ($_FILES['archivo-cancion']['error'] !== UPLOAD_ERR_OK) {
        switch ($_FILES['archivo-cancion']['error']) {
            case UPLOAD_ERR_PARTIAL:
                exit("La canción fue subida parcialmente");
            case UPLOAD_ERR_NO_FILE:
                exit("No se subió ninguna canción");
            case UPLOAD_ERR_EXTENSION:
                exit("La subida de la canción fue interrumpida debido a la extensión de PHP");
            case UPLOAD_ERR_FORM_SIZE:
                exit("La canción excede el tamaño de subida permitido");
            default:
                exit("Error desconocido al subir la canción");
        }
    }

    if ($_FILES['archivo-txt']['error'] !== UPLOAD_ERR_OK) {
        switch ($_FILES['archivo-txt']['error']) {
            case UPLOAD_ERR_PARTIAL:
                exit("El archivo TXT fue subido parcialmente");
            case UPLOAD_ERR_NO_FILE:
                exit("No se subió ningun archivo TXT");
            case UPLOAD_ERR_EXTENSION:
                exit("La subida del archivo TXT fue interrumpida debido a la extensión de PHP");
            case UPLOAD_ERR_FORM_SIZE:
                exit("El archivo TXT excede el tamaño de subida permitido");
            default:
                exit("Error desconocido al subir el archivo TXT");
        }
    }

    // Formatear nombres de archivos
    $nombrePortada = preg_replace("/[^A-Z0-9._-]/i", "_", $arrayPortada['filename']) . "_" . time();
    $nombreCancion = preg_replace("/[^A-Z0-9._-]/i", "_", $arrayCancion['filename']) . "_" . time();
    $nombreTXT = preg_replace("/[^A-Z0-9._-]/i", "_", $arrayTXT['filename']) . "_" . time();

    // Verificar y crear directorios si no existen
    if (!file_exists($portada_dir)) {
        mkdir($portada_dir, 0777, true);
    }

    if (!file_exists($cancion_dir)) {
        mkdir($cancion_dir, 0777, true);
    }

    if (!file_exists($txt_dir)) {
        mkdir($txt_dir, 0777, true);
    }

    if (empty($errores)) {
        $portada_file = $portada_dir . $nombrePortada . "." . $extensionPortada;
        $cancion_file = $cancion_dir . $nombreCancion . "." . $extensionCancion;
        $txt_file = $txt_dir . $nombreTXT . "." . $extensionTXT;

        // Mover archivos
        if (
            move_uploaded_file($directorioPortada, $portada_file) &&
            move_uploaded_file($directorioCancion, $cancion_file) &&
            move_uploaded_file($directorioTXT, $txt_file)
        ) {
            function generarNuevoId($jsonFile) {
                if (file_exists($jsonFile)) {
                    $jsonData = json_decode(file_get_contents($jsonFile), true);
            
                    // Si json_decode falla o el archivo está vacío, asegura que $jsonData sea un array vacío
                    if (!is_array($jsonData)) {
                        $jsonData = [];
                    }
            
                    // Obtener los IDs actuales
                    $ids = array_column($jsonData, 'id');
            
                    // Si el array de IDs no está vacío, obtener el máximo y sumar 1
                    if (!empty($ids)) {
                        $nuevoId = max($ids) + 1;
                    } else {
                        $nuevoId = 1;  // Si no hay canciones aún, el ID comienza en 1
                    }
                } else {
                    // Si el archivo no existe, el primer ID es 1
                    $nuevoId = 1;
                }
            
                return $nuevoId;
            }
            

            // Leer y actualizar JSON
            $jsonFile = '../json/canciones.json';
            $jsonData = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];

            $nuevoId = generarNuevoId($jsonFile);

            $nuevaCancion = array(
                'id' => $nuevoId,
                'titulo' => $nombre,
                'artista' => $artista,
                'cancion' => $cancion_file,
                'portada' => $portada_file,
                'txt' => $txt_file,
                'fecha_subida' => date("d-m-Y")
            );

            // Añadir la nueva canción al array
            $jsonData[] = $nuevaCancion;

            // Guardar el archivo JSON actualizado
            file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT));

            // Redirigir con éxito
            header("Location: ../php/lista-canciones.php?status=success&message=" . urlencode("Canción subida correctamente"));
            exit();
        } else {
            header("Location: ../añadir-canciones.html?status=error&message=" . urlencode("Error al subir los archivos"));
            exit();
        }
    } else {
        // Si hay errores
        $error_message = implode(", ", $errores);
        header("Location: ../añadir-canciones.html?status=error&message=" . urlencode($error_message));
        exit();
    }
}
