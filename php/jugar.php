<?php
// Leer el archivo JSON donde se almacenan las canciones
$jsonFile = '../json/canciones.json';
$canciones = json_decode(file_get_contents($jsonFile), true);

// Obtener la canción por el ID para jugar
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$cancion = null;

// Si el usuario no es null , recorremos el array y  buscamos la canción
if ($id !== null) {
    foreach ($canciones as $c) {
        if ($c['id'] == $id) {
            $cancion = $c;
            break;
        }
    }
}
// Mensaje en caso de no encontrar la canción
if ($cancion === null) {
    die("Canción no encontrada.");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stepmania</title>
    <link rel="stylesheet" href="../css/style.css" />
    <script src="../js/game.js"></script>
</head>

<body>
    <div class="main-container">
        <header class="header">
            <div class="logo">
                <a href="../index.php"><img src="../img/web-logo.webp" alt="web-logo" /></a>
            </div>
            <h1 class="header-title">STEP<span class="header-title2">MANIA</span></h1>
            <nav class="navbar">
                <ul>
                    <li><a href="listaCanciones.php">Lista Canciones</a></li>
                    <li><a href="../añadirCanciones.html">Añadir Canciones</a></li>
                    <li><a href="clasificacion.php">Clasificacion</a></li>
                </ul>
            </nav>
        </header>
        <!-- Seccion principal del juego -->
        <div class="main-section">
            <main class="main-area__game">
                <div class="div-area__game">
                    <div class="item"></div>
                    <div class="item"></div>
                    <div class="item"></div>
                    <div class="item"></div>
                </div>
            </main>
            <!-- Barra de progeso -->
            <div class="progress-container">
                <div class="score-container">
                    <h2>Puntuación: <span id="score">0</span></h2>
                </div>
                <div class="progress-bar">
                    <div class="progress"></div>
                </div>
                <!-- Nombre de la canción y artista -->
                <div class="song-info">
                    <h2><?= htmlspecialchars($cancion['titulo']) ?></h2>
                    <p>Artista: <?= htmlspecialchars($cancion['artista']) ?></p>
                </div>
            </div>
            <!-- Modal para pedir nombre de usuario -->
            <div id="usuarioModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="cerrarModal()">&times;</span>
                    <h2>Introduce tu nombre de usuario</h2>
                    <form id="formUsuario" method="POST">
                        <input type="hidden" name="id_cancion" id="id_cancion" value="<?= htmlspecialchars($cancion['id']) ?>" />
                        <div class="input-box">
                            <label for="usuario">Nombre de Usuario:</label>
                            <input type="text" id="usuario" name="usuario" required />
                        </div>
                        <div class="submit-btn__div">
                            <button type="button" onclick="guardarUsuario()">Iniciar Juego</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>

</html>