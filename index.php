<?php
// Ruta al archivo JSON de usuarios
$jsonFile = 'json/usuarios.json';

// Leer el archivo JSON y decodificarlo para convertirlo en un array
$usuarios = json_decode(file_get_contents($jsonFile), true);

// Ordenar usuarios por puntuación de mayor a menor
usort($usuarios, function ($a, $b) {
  return $b['puntuacion'] - $a['puntuacion']; // Comparar puntuaciones
});

// Limitar a los primeros 5 jugadores para mostrar en la clasificación
$topUsuarios = array_slice($usuarios, 0, 5);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stepmania</title>
  <link rel="stylesheet" href="css/style.css" /> <!-- Vincular la hoja de estilos -->
</head>

<body>
  <div class="main-container">
    <header class="header">
      <div class="logo">
        <a href="index.php"><img src="img/web-logo.webp" alt="web-logo" /></a> <!-- Logo del juego -->
      </div>
      <h1 class="header-title">STEP<span class="header-title2">MANIA</span></h1> <!-- Título principal -->
      <nav class="navbar">
        <ul>
          <li><a href="php/listaCanciones.php">Lista Canciones</a></li> <!-- Enlace a la lista de canciones -->
          <li><a href="añadirCanciones.html">Añadir Canciones</a></li> <!-- Enlace para añadir canciones -->
          <li><a href="php/clasificacion.php">Clasificación</a></li> <!-- Enlace a la clasificación -->
        </ul>
      </nav>
    </header>

    <div class="main-section">
      <main class="main-area">
        <section class="section-area"> <!-- Sección explicativa sobre cómo jugar -->
          <div class="section-area__div">
            <h2>¿Cómo jugar?</h2>
            <div class="section-list">
              <ol>
                <li>Elige una canción</li>
                <li>Presiona las flechas al ritmo</li>
                <li>Acumula puntos y mejora tu precisión</li>
                <li>Compite entre otros jugadores</li>
                <li>¡Conviértete en el mejor jugador!</li>
              </ol>
            </div>
            <div class="section-btn">
              <button><a href="php/listaCanciones.php" class="neon-button">Jugar</a></button> <!-- Botón para jugar -->
            </div>
          </div>
        </section>
        <!-- Sección de los 5 mejores jugadores -->
        <aside class="aside-area">
          <div class="aside-area__div">
            <h2>Top 5 Jugadores</h2> <!-- Título de la sección de los mejores jugadores -->
            <div class="aside-list">
              <ol>
                <?php if (!empty($topUsuarios)): ?> <!-- Comprobar si hay usuarios en la lista -->
                  <?php foreach ($topUsuarios as $index => $usuario): ?> <!-- Recorrer el array -->
                    <li>
                      <?php if ($index === 0): ?> <!-- Medalla de oro para el primer lugar -->
                        🥇
                      <?php elseif ($index === 1): ?> <!-- Medalla de plata para el segundo lugar -->
                        🥈
                      <?php elseif ($index === 2): ?> <!-- Medalla de bronce para el tercer lugar -->
                        🥉
                      <?php else: ?> <!-- Estrella para el cuarto y quinto lugar -->
                        ⭐
                      <?php endif; ?>
                      <?php echo htmlspecialchars($usuario['usuario']) . ' - ' . htmlspecialchars($usuario['puntuacion']); ?> <!-- Mostrar usuario y puntuación -->
                    </li>
                  <?php endforeach; ?>
                <?php else: ?>
                  <li>No hay usuarios registrados.</li> <!-- Mensaje si no hay usuarios -->
                <?php endif; ?>
              </ol>
            </div>
          </div>
        </aside>
      </main>
    </div>
    <footer class="footer-area">
      <p>© 2024 STEPMANIA, ES. ALL RIGHTS RESERVED</p>
    </footer>
  </div>
</body>

</html>