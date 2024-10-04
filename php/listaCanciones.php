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
          <li><a href="listaCanciones.php">Lista Canciones</a></li>
          <li><a href="../añadirCanciones.html">Añadir Canciones</a></li>
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
                      <button class="play-button">
                        <svg data-encore-id="icon" role="img" aria-hidden="true" viewBox="0 0 24 24" class="Svg-sc-ytk21e-0 bneLcE">
                          <path d="m7.05 3.606 13.49 7.788a.7.7 0 0 1 0 1.212L7.05 20.394A.7.7 0 0 1 6 19.788V4.212a.7.7 0 0 1 1.05-.606z">
                          </path>
                        </svg>
                      </button>
                      <button class="edit-button" onclick="editarCancion(<?= $cancion['id'] ?>)">
                        <a href=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z" />
                          </svg></a>
                      </button>
                      <button class="delete-button" onclick="eliminarCancion(<?= $cancion['id'] ?>)">
                        <a href=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                          </svg></a>
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
