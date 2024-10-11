<?php
// Ruta al archivo JSON donde guardaremos los usuarios
$jsonFile = '../json/usuarios.json';

// Leer los datos de la solicitud POST
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
$id_cancion = isset($_POST['id_cancion']) ? intval($_POST['id_cancion']) : null;
$puntuacion = isset($_POST['puntuacion']) ? intval($_POST['puntuacion']) : null; // Añadimos la puntuación

// Comprobar si se recibió el nombre del usuario y el ID de la canción
if ($usuario && $id_cancion) {
    // Generar una cookie única para el usuario
    $cookieValue = bin2hex(random_bytes(16)); // Genera un valor único para la cookie
    setcookie('usuario', $cookieValue, time() + (86400 * 30), "/"); // La cookie durará 30 días

    // Cargar el archivo JSON de usuarios
    if (file_exists($jsonFile)) {
        $usuarios = json_decode(file_get_contents($jsonFile), true);
    } else {
        $usuarios = [];
    }

    // Verificar si el usuario ya existe en el archivo JSON
    $usuarioExistente = false;
    foreach ($usuarios as &$user) { // Usamos referencia (&) para poder modificar el usuario existente
        if ($user['usuario'] === $usuario) {
            $usuarioExistente = true;
            // Actualizar los datos del usuario existente
            $user['cookie'] = $cookieValue;
            $user['ultima_entrada'] = date('Y-m-d H:i:s');
            if ($puntuacion !== null) {
                // Actualizar la puntuación
                $user['puntuacion'] += $puntuacion; // Sumar la puntuación en lugar de reemplazarla
            }
            break;
        }
    }

    // Si el usuario no existe, agregarlo
    if (!$usuarioExistente) {
        $nuevoUsuario = [
            'cookie' => $cookieValue,
            'usuario' => $usuario,
            'puntuacion' => $puntuacion ? $puntuacion : 0, // Inicializamos la puntuación en base a la solicitud
            'ultima_entrada' => date('Y-m-d H:i:s')
        ];
        $usuarios[] = $nuevoUsuario;
    }

    // Guardar los cambios en el archivo JSON
    file_put_contents($jsonFile, json_encode($usuarios, JSON_PRETTY_PRINT));

    // Devolver una respuesta de éxito
    echo 'success';
} else {
    // Si falta el usuario o el id de la canción
    echo 'error: faltan datos';
}
