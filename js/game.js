document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll(".item");
  const arrows = ["←", "↑", "↓", "→"]; // Definimos las flechas que vamos a utilizar
  const scoreElement = document.getElementById("score");
  let score = 0;
  let misses = 0;
  const maxMisses = 3; // Máximo de fallos permitidos
  const progressBar = document.querySelector(".progress");
  let audio;
  let gameData; // Datos del archivo de juego
  let gameStartTime; // Para sincronizar las flechas con el audio
  let currentUser;
  let gameStarted = false; // Estado del juego

  // Función para cargar el archivo del juego (archivo de texto) basado en la ruta del JSON
  function loadGameFile(filePath) {
    return fetch(filePath)
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "") {
          console.warn("El archivo está vacío, generando flechas mínimas.");
          return generateDefaultArrows();
        } else {
          return processGameFile(data);
        }
      })
      .catch((error) => {
        console.error("Error al cargar el archivo de juego:", error);
        return generateDefaultArrows();
      });
  }

  // Función para procesar el contenido del archivo de juego
  function processGameFile(data) {
    const lines = data.trim().split("\n");
    const numElements = parseInt(lines[0]);
    const gameElements = [];

    for (let i = 1; i <= numElements; i++) {
      const [arrowIndex, start, end] = lines[i]
        .split("#")
        .map((part) => part.trim());
      gameElements.push({
        key: arrows[parseInt(arrowIndex) - 2190], // Convertir índice a flecha
        startTime: parseFloat(start),
        endTime: parseFloat(end),
      });
    }

    return gameElements;
  }

  // Función para obtener el ID de la canción desde la URL
  function getSongIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get("id");
  }

  // Función para cargar el JSON y obtener la ruta de la canción y del archivo de texto
  function loadSongPath() {
    const songId = getSongIdFromUrl();
    return fetch("../json/canciones.json")
      .then((response) => response.json())
      .then((data) => {
        const song = data.find((song) => song.id === parseInt(songId));
        if (song) {
          return {
            songPath: song.cancion,
            gameFilePath: song.txt,
          };
        } else {
          throw new Error("Canción no encontrada");
        }
      });
  }

  // Función para cargar y empezar la canción
  function loadAndPlayAudio(songPath, gameFilePath) {
    audio = new Audio(songPath);
    audio.load();
    audio.addEventListener(
      "canplaythrough",
      function () {
        startGame(gameFilePath); // Pasar la ruta del archivo del juego
      },
      { once: true }
    );
    audio.addEventListener("error", function (e) {
      console.error("Error loading audio:", e);
      alert("Error al cargar la canción. Por favor, inténtalo de nuevo.");
    });
  }

  // Empezar el juego cuando la canción esté lista
  function startGame(gameFilePath) {
    loadGameFile(gameFilePath).then((gameDataFromFile) => {
      gameData = gameDataFromFile;
      audio
        .play()
        .then(() => {
          gameStartTime = Date.now();
          gameData.forEach((element) => {
            scheduleArrow(element);
          });
          updateProgressBar();
          gameStarted = true; // Cambiar el estado del juego a iniciado
        })
        .catch((error) => {
          console.error("Error playing audio:", error);
          alert(
            "Error al reproducir la canción. Por favor, inténtalo de nuevo."
          );
        });
    });
  }

  // Programar la aparición y desaparición de las flechas según los datos del archivo de juego
  function scheduleArrow(element) {
    const startDelay = element.startTime * 1000;
    const endDelay = element.endTime * 1000;

    setTimeout(() => {
      createArrow(element.key);
    }, startDelay);

    setTimeout(() => {
      removeArrow(element.key);
      handleMiss(); // Manejar la falta al desaparecer
    }, endDelay);
  }

  // Crear una flecha en la pantalla
  function createArrow(key) {
    const index = arrows.indexOf(key); // Obtener la posición correcta
    const item = items[index];

    if (!item) {
      console.error("No se encontró el elemento para la flecha:", key);
      return;
    }

    const arrow = document.createElement("div");
    arrow.classList.add("arrow");
    arrow.innerText = key;
    arrow.dataset.direction = key;

    item.appendChild(arrow);
    arrow.style.position = "absolute";
    arrow.style.top = "0px";
    arrow.style.left = "50%";
    arrow.style.transform = "translateX(-50%)";

    const startTime = Date.now();

    // Función para mover la flecha
    function moveArrow() {
      const elapsedTime = Date.now() - startTime;
      const position = (elapsedTime / 3000) * item.clientHeight; // Mover la flecha en 3 segundos
      arrow.style.top = `${position}px`;

      if (elapsedTime < 3000 && !arrow.dataset.removed) {
        requestAnimationFrame(moveArrow);
      } else if (!arrow.dataset.removed) {
        arrow.remove();
        handleMiss(); // Llama a handleMiss cuando la flecha se pierde
      }
    }

    requestAnimationFrame(moveArrow);

    arrow.addEventListener("click", () => handleArrowClick(arrow));
  }

  // Funcion para eliminar flecha
  function removeArrow(key) {
    const arrowsOnScreen = document.querySelectorAll(".arrow");
    arrowsOnScreen.forEach((arrow) => {
      if (arrow.innerText === key) {
        arrow.dataset.removed = true;
        arrow.remove();
      }
    });
  }

  // Manejar la pulsación de una flecha correcta
  function handleArrowClick(arrow) {
    console.log("Flecha correcta pulsada:", arrow);
    handleCorrectArrow(arrow);
  }

  // Contabilizar los fallos
  function handleMiss() {
    if (gameStarted) { // Solo restar puntos si el juego ya ha comenzado
      misses++;
      score -= 50; // Restar 50 puntos al fallar
      scoreElement.innerText = score; // Actualizar la puntuación en pantalla

      console.log("Fallo registrado, número de fallos:", misses);
      if (misses >= maxMisses) {
        endGame("Has perdido. Alcanzaste el máximo de fallos permitidos.");
      }
    }
  }

  // Manejo de las teclas de flechas del teclado
  document.addEventListener("keydown", (event) => {
    const arrowsOnScreen = document.querySelectorAll(".arrow");
    let matchingArrow = null;

    switch (event.key) {
      case "ArrowLeft":
        matchingArrow = Array.from(arrowsOnScreen).find(
          (arrow) => arrow.dataset.direction === "←" && !arrow.dataset.removed
        );
        break;
      case "ArrowUp":
        matchingArrow = Array.from(arrowsOnScreen).find(
          (arrow) => arrow.dataset.direction === "↑" && !arrow.dataset.removed
        );
        break;
      case "ArrowDown":
        matchingArrow = Array.from(arrowsOnScreen).find(
          (arrow) => arrow.dataset.direction === "↓" && !arrow.dataset.removed
        );
        break;
      case "ArrowRight":
        matchingArrow = Array.from(arrowsOnScreen).find(
          (arrow) => arrow.dataset.direction === "→" && !arrow.dataset.removed
        );
        break;
    }

    if (matchingArrow) {
      handleCorrectArrow(matchingArrow);
    } else {
      handleMiss(); // Si no hay flecha coincidente, contabilizar como fallo
    }
  });

  // Función para saber la flecha correcta
  function handleCorrectArrow(arrow) {
    if (arrow && !arrow.dataset.removed) {
      score += 100;
      scoreElement.innerText = score;
      arrow.dataset.removed = "true";
      arrow.remove();
    }
  }

  // Función para finalizar el juego
  function endGame(message) {
    audio.pause();
    // Guardar la puntuación antes de mostrar el mensaje
    saveScore().then(() => {
      alert(message);
      if (message.includes("Has perdido")) {
        window.location.href = "../php/listaCanciones.php";
      } else {
        window.location.href = "../php/clasificacion.php";
      }
    });
  }

  // Actualizar la barra de progreso
  function updateProgressBar() {
    const duration = audio.duration;
    const interval = setInterval(() => {
      const currentTime = audio.currentTime;
      const progressPercentage = (currentTime / duration) * 100;
      console.log(progressPercentage);
      progressBar.style.width = `${progressPercentage}%`;

      if (currentTime >= duration) {
        clearInterval(interval);
        endGame("Fin de la canción. Puntuación final: " + score);
      }
    }, 100);
  }

  // Funcion para guardar la puntuación
  function saveScore() {
    const songId = getSongIdFromUrl();
    const data = new FormData();
    data.append("usuario", currentUser);
    data.append("id_cancion", songId);
    data.append("puntuacion", score);

    return fetch("../php/guardarUsuario.php", {
      method: "POST",
      body: data,
    })
      .then((response) => response.text())
      .then((result) => {
        if (result === "success") {
          console.log("Puntuación guardada con éxito");
        } else {
          console.error("Error al guardar la puntuación:", result);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

  // Cargar y empezar la canción cuando el usuario introduce su nombre
  window.guardarUsuario = function () {
    const usuario = document.getElementById("usuario").value;
    const idCancion = getSongIdFromUrl();

    if (usuario) {
      currentUser = usuario;
      const data = new FormData();
      data.append("usuario", usuario);
      data.append("id_cancion", idCancion);
      data.append("puntuacion", 0); // Inicializamos con 0 puntos

      fetch("../php/guardarUsuario.php", {
        method: "POST",
        body: data,
      })
        .then((response) => response.text())
        .then((result) => {
          if (result === "success") {
            cerrarModal();
            loadSongPath().then(({ songPath, gameFilePath }) => {
              loadAndPlayAudio(songPath, gameFilePath);
            });
          } else {
            console.error("Error al guardar usuario:", result);
            alert("Error al guardar usuario. Por favor, inténtalo de nuevo.");
          }
        });
    } else {
      alert("Por favor, ingrese un nombre de usuario.");
    }
  };

  // Asegúrate de que esta función esté definida
  function getSongIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get("id");
  }

  // Cerrar el modal
  window.cerrarModal = function () {
    const modal = document.getElementById("usuarioModal");
    modal.style.display = "none";
  };

  // Mostrar el modal al cargar la página
  window.onload = function () {
    document.getElementById("usuarioModal").style.display = "block";
  };
});

