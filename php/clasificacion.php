<?php
// Leer el archivo JSON de los usuarios
$jsonFile = '../json/usuarios.json'; // Ajusta la ruta de tu archivo JSON
$usuarios = json_decode(file_get_contents($jsonFile), true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clasificación - Stepmania</title>
  <link rel="stylesheet" href="../css/style.css" />
  <script src="../js/main.js"></script>
</head>
<body>
  <div class="main-container">
    <header class="header">
      <div class="logo">
        <a href="../index.php"><img src="../img/web-logo.webp" alt="web-logo" /></a>
      </div>
      <h1 class="header-title">
        STEP<span class="header-title2">MANIA</span>
      </h1>
      <nav class="navbar">
        <ul>
          <li><a href="listaCanciones.php">Lista Canciones</a></li>
          <li><a href="../añadirCanciones.html">Añadir Canciones</a></li>
          <li><a href="clasificacion.php">Clasificación</a></li>
        </ul>
      </nav>
    </header>
    <!-- Tabla clasificatoria -->
    <div class="main-section__ranking">
      <main class="main-area__ranking">
        <div class="area-div__ranking">
          <h2>Clasificación de Jugadores</h2>
          <div class="container">
            <table>
              <thead>
                <tr>
                  <th>Posición</th>
                  <th>Usuario</th>
                  <th>Puntuación</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($usuarios) > 0): ?>
                    <?php 
                    // Ordenar usuarios por puntuación en orden descendente
                    usort($usuarios, function($a, $b) {
                        return $b['puntuacion'] - $a['puntuacion'];
                    });
                    // Recorremos el array y mostramos el puesto, usuario y puntuación
                    foreach ($usuarios as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($user['usuario']); ?></td>
                            <td><?php echo htmlspecialchars($user['puntuacion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>

