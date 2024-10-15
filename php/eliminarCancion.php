<?php 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = isset($_POST['id_cancion']) ? intval($_POST['id_cancion']) : 0;

    $jsonFile = '../json/canciones.json';  // Archivo JSON de canciones

    if(file_exists($jsonFile)){
        $jsonData = json_decode(file_get_contents($jsonFile), true);

        // Si el fichero no está vacío recorremos el array para eliminar los archivos de la canción
        if(!empty($jsonData)) {
            foreach($jsonData as $index => $cancion){
                if($cancion['id'] == $id){
                    $archivoMP3 = '../uploads/songs/' . basename($cancion['cancion']);
                    $archivoImagen = '../uploads/img/' . basename($cancion['portada']);
                    $archivoTXT = '../uploads/txt/' . basename($cancion['txt']);

                    // Eliminar archivo MP3
                    if(file_exists($archivoMP3)){
                        unlink($archivoMP3);
                    }

                    // Eliminar imagen
                    if(file_exists($archivoImagen)){
                        unlink($archivoImagen);
                    }

                    // Eliminar archivo TXT
                    if(file_exists($archivoTXT)){
                        unlink($archivoTXT);
                    }

                    // Eliminar la canción del array
                    unset($jsonData[$index]);

                    // Reindexar el array y guardar cambios en el JSON
                    $jsonData = array_values($jsonData);
                    file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT));

                    // Mensaje de éxito
                    echo "Canción y archivos eliminados correctamente";
                    exit();
                }
            }
        }
    }
    echo "No se encontró la canción con ese ID.";
}
