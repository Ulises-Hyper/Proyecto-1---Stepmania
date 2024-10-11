<?php
// Leer el archivo JSON donde se almacenan las canciones
$jsonFile = '../json/canciones.json';
$canciones = json_decode(file_get_contents($jsonFile), true);

// Obtener la canción por el ID para editarla
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$cancion = null;

if ($id !== null) {
    foreach ($canciones as $index => $c) {
        if ($c['id'] == $id) {
            $cancion = $c;
            break;
        }
    }
}

if ($cancion === null) {
    die("Canción no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Canción</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
  <div class="main-container">
    <header class="header">
      <div class="logo">
        <a href="../index.html"><img src="../img/web-logo.webp" alt="web-logo" /></a>
      </div>
      <h1 class="header-title">
        STEP<span class="header-title2">MANIA</span>
      </h1>
      <nav class="navbar">
        <ul>
          <li><a href="listaCanciones.php">Lista Canciones</a></li>
          <li><a href="../añadirCanciones.html">Añadir Canciones</a></li>
          <li><a href="#">Clasificaciones</a></li>
        </ul>
      </nav>
    </header>
    <div class="main-section__form">
      <main class="main-area__form">
        <div class="area-div__form">
          <h2>Editar Canción: <?= htmlspecialchars($cancion['titulo']) ?></h2>
          <div class="div__form">
            <form action="actualizarCancion.php" method="POST" enctype="multipart/form-data" class="form">
              <input type="hidden" name="id" value="<?= htmlspecialchars($cancion['id']) ?>" />

              <div class="input-box">
                <label>Título de la canción:</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($cancion['titulo']) ?>" required />
              </div>

              <div class="input-box">
                <label>Artista:</label>
                <input type="text" name="artista" value="<?= htmlspecialchars($cancion['artista']) ?>" required />
              </div>

              <div class="custom-file">
                <label>Archivo MP3 (deja vacío para mantener el actual):</label>
                <input type="file" name="archivo-cancion" accept=".mp3" class="real-file" />
              </div>

              <div class="custom-file">
                <label>Portada (deja vacío para mantener la actual):</label>
                <input type="file" name="archivo-portada" accept=".jpg,.jpeg,.png" class="real-file" />
              </div>

              <div class="custom-file">
                <label>Archivo TXT (deja vacío para mantener el actual):</label>
                <input type="file" name="archivo-txt" accept=".txt" class="real-file" />
              </div>

              <div class="submit-btn__div">
                <button type="submit" class="submit-btn">Actualizar Canción</button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
