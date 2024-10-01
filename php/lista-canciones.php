<?php
// Leer el archivo JSON donde se almacenan las canciones
$jsonFile = '../json/canciones.json'; // Ajusta la ruta de tu archivo JSON
$canciones = json_decode(file_get_contents($jsonFile), true);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stepmania</title>
  <link rel="stylesheet" href="../css/style.css" />
  <script src="../js/main.js"></script>
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
          <li><a href="#">Jugar</a></li>
          <li><a href="../añadir-canciones.html">Canciones</a></li> <!-- Cambiar a PHP si es necesario -->
          <li><a href="#">Clasificaciones</a></li>
        </ul>
      </nav>
    </header>

    <div class="main-section__list">
      <main class="main-area__list">
        <div class="area-div__list">
          <h2>PlayList</h2>
          <div class="list-songs">
            <?php if (!empty($canciones)): ?>
              <?php foreach ($canciones as $cancion): ?>
                <div class="list-song">
                  <div class="list-songs__right">
                    <div class="list-songs-right__img">
                      <img src="<?= htmlspecialchars($cancion['portada']) ?>"
                        alt="<?= htmlspecialchars($cancion['titulo']) ?>">
                    </div>
                    <div class="list-songs-right__info">
                      <p class="song-title"><?= htmlspecialchars($cancion['titulo']) ?></p>
                      <p class="song-artist"><?= htmlspecialchars($cancion['artista']) ?></p>
                    </div>
                    <div class="list-songs-right__play">
                      <button class="button">
                        <svg data-encore-id="icon" role="img" aria-hidden="true" viewBox="0 0 24 24" class="Svg-sc-ytk21e-0 bneLcE">
                          <path d="m7.05 3.606 13.49 7.788a.7.7 0 0 1 0 1.212L7.05 20.394A.7.7 0 0 1 6 19.788V4.212a.7.7 0 0 1 1.05-.606z">
                          </path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No hay canciones disponibles en este momento.</p>
            <?php endif; ?>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>