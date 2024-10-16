<?php
// Ruta del archivo JSON de las canciones
$jsonFile = '../json/canciones.json';

// Comprovamos que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Comprovamos que el archivo JSON existe
    if (file_exists($jsonFile)) {
        // Leer el archivo JSON y decodificarlo para convertirlo en un array
        $canciones = json_decode(file_get_contents($jsonFile), true);
    } else {
        die("Error: No se encontró el archivo de la canción");
    }
    // Recorremos el array
    foreach ($canciones as $index => $cancion) {
        if ($cancion['id'] == $id) {

            // Actualizar los campos
            $canciones[$index]['titulo'] = $_POST['titulo'];
            $canciones[$index]['artista'] = $_POST['artista'];


            // Comprovar si se subió un nuevo archivo MP3
            if (!empty($_FILES['archivo-cancion']['name'])) {
                $mp3_dir = '../uploads/songs/' . basename($_FILES['archivo-cancion']['name']);
                if (move_uploaded_file($_FILES['archivo-cancion']['tmp_name'], $mp3_dir)) {
                    // Borrar el archivo MP3 antiguo
                    if (file_exists($cancion['cancion'])) {
                        unlink($cancion['cancion']);
                    }
                    // Actualizar ruta nuevo archivo MP3
                    $canciones[$index]['cancion'] = $mp3_dir;
                }
            }

            // Comprovar si se subió una nueva portada
            if (!empty($_FILES['archivo-portada']['name'])) {
                $portada_dir = '../uploads/img/' . basename($_FILES['archivo-portada']['name']);
                if (move_uploaded_file($_FILES['archivo-portada']['tmp_name'], $portada_dir)) {
                    // Borrar la portada antigua
                    if (file_exists($cancion['portada'])) {
                        unlink($cancion['portada']);
                    }
                    // Actualizar la ruta de la nueva portada
                    $canciones[$index]['portada'] = $portada_dir;
                }
            }

            // Comprovar si se subió el txt
            if (!empty($_FILES['archivo-txt']['name'])) {
                $txt_dir = '../uploads/txt/' . basename($_FILES['archivo-txt']['name']);
                if (move_uploaded_file($_FILES['archivo-txt']['tmp_name'], $txt_dir)) {
                    // Borrar el txt antiguo
                    if (file_exists($cancion['txt'])) {
                        unlink($cancion['txt']);
                    }
                    // Actualizar la ruta del nuevo txt
                    $canciones[$index]['txt'] = $txt_dir;
                }
            }

            // Guardar los cambios en el archivo JSON
            file_put_contents($jsonFile, json_encode($canciones, JSON_PRETTY_PRINT));

            // Redirigir con mensaje de éxito
            header('Location: listaCanciones.php?status=success&message=Canción actualizada correctamente');
            exit();
        }
    }
    die("Error: Canción no encontrada");
} else {
    die('Método no permitido');
}

