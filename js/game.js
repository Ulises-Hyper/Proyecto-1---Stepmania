document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll(".item");
  const arrows = ["←", "↑", "↓", "→"];
  const scoreElement = document.getElementById("score");
  let score = 0;
  let misses = 0;
  const maxMisses = 3;
  const progressBar = document.querySelector(".progress");
  let audio;
  let gameInterval;
  const arrowIntervalTime = 2000; // Tiempo fijo entre cada aparición de flechas (2 segundos)
  const arrowLifetime = 3000; // Tiempo de vida de cada flecha (3 segundos)
  let currentUser; // Variable para almacenar el nombre de usuario

  // Función para obtener el ID de la canción desde la URL
  function getSongIdFromUrl() {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get('id');
  }

  // Función para cargar el JSON y obtener la ruta de la canción
  function loadSongPath() {
      const songId = getSongIdFromUrl();
      return fetch('../json/canciones.json')
          .then(response => response.json())
          .then(data => {
              const song = data.find(song => song.id === parseInt(songId));
              if (song) {
                  return song.cancion;
              } else {
                  throw new Error('Canción no encontrada');
              }
          });
  }

  // Función para cargar y empezar la canción
  function loadAndPlayAudio(songPath) {
      audio = new Audio(songPath);
      audio.load();
      audio.addEventListener('canplaythrough', function () {
          console.log("Audio loaded and can play");
          startGame();
      }, { once: true });
      audio.addEventListener('error', function (e) {
          console.error("Error loading audio:", e);
          alert("Error al cargar la canción. Por favor, inténtalo de nuevo.");
      });
  }

  // Empezar el juego cuando la canción esté lista
  function startGame() {
      console.log("Starting game...");
      audio.play().then(() => {
          console.log("Audio playing");
          gameInterval = setInterval(createArrow, arrowIntervalTime);
          updateProgressBar();
      }).catch(error => {
          console.error("Error playing audio:", error);
          alert("Error al reproducir la canción. Por favor, inténtalo de nuevo.");
      });
  }

  function createArrow() {
      const randomIndex = Math.floor(Math.random() * arrows.length);
      const item = items[randomIndex];
      const arrow = document.createElement("div");
      arrow.classList.add("arrow");
      arrow.innerText = arrows[randomIndex];
      arrow.dataset.direction = arrows[randomIndex];

      item.appendChild(arrow);
      arrow.style.position = "absolute";
      arrow.style.top = "0px";
      arrow.style.left = "50%";
      arrow.style.transform = "translateX(-50%)";

      const startTime = Date.now();

      function moveArrow() {
          const elapsedTime = Date.now() - startTime;
          const position = (elapsedTime / arrowLifetime) * item.clientHeight;
          arrow.style.top = `${position}px`;

          if (elapsedTime < arrowLifetime && !arrow.dataset.removed) {
              requestAnimationFrame(moveArrow);
          } else if (!arrow.dataset.removed) {
              arrow.remove();
              handleMiss();
          }
      }

      requestAnimationFrame(moveArrow);

      arrow.addEventListener("click", () => handleArrowClick(arrow));
  }

  function handleArrowClick(arrow) {
      handleCorrectArrow(arrow);
  }

  function handleMiss() {
      misses++;
      if (misses >= maxMisses) {
          endGame("Has perdido. Alcanzaste el máximo de fallos permitidos."); // Mensaje de pérdida
      }
  }

  // Manejo de las teclas de flechas
  document.addEventListener("keydown", (event) => {
      const arrows = document.querySelectorAll(".arrow");
      let matchingArrow = null;

      switch (event.key) {
          case "ArrowLeft":
              matchingArrow = Array.from(arrows).find(arrow => arrow.dataset.direction === "←" && !arrow.dataset.removed);
              break;
          case "ArrowUp":
              matchingArrow = Array.from(arrows).find(arrow => arrow.dataset.direction === "↑" && !arrow.dataset.removed);
              break;
          case "ArrowDown":
              matchingArrow = Array.from(arrows).find(arrow => arrow.dataset.direction === "↓" && !arrow.dataset.removed);
              break;
          case "ArrowRight":
              matchingArrow = Array.from(arrows).find(arrow => arrow.dataset.direction === "→" && !arrow.dataset.removed);
              break;
      }

      if (matchingArrow) {
          handleCorrectArrow(matchingArrow);
      }
  });

  function handleCorrectArrow(arrow) {
      if (arrow && !arrow.dataset.removed) {
          score += 100;
          scoreElement.innerText = score;
          arrow.dataset.removed = "true";
          arrow.remove();
      }
  }

  function endGame(message) {
      clearInterval(gameInterval);
      audio.pause();
      saveScore(); // Guardar la puntuación del usuario al finalizar el juego
      alert(message);
      // Redirigir según el mensaje
      if (message.includes("Has perdido")) {
          window.location.href = "listaCanciones.php"; // Redirigir a listaCanciones.php si se pierde
      } else {
          window.location.href = "clasificacion.php"; // Redirigir a clasificacion.php si se acaba la canción
      }
  }

  // Guarda la puntuación en el archivo JSON
  function saveScore() {
      if (score > 0) {
          fetch("guardarUsuario.php", {
              method: "POST",
              headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },
              body: `usuario=${currentUser}&id_cancion=${getSongIdFromUrl()}&puntuacion=${score}`, // Enviar la puntuación
          })
              .then(response => response.text())
              .then(data => {
                  console.log("Puntuación guardada:", data);
              })
              .catch(error => {
                  console.error("Error al guardar la puntuación:", error);
              });
      }
  }

  // Actualizar la barra de progreso
  function updateProgressBar() {
      const duration = audio.duration;
      const interval = setInterval(() => {
          const currentTime = audio.currentTime;
          const progressPercentage = (currentTime / duration) * 100;
          progressBar.style.width = `${progressPercentage}%`;

          if (currentTime >= duration) {
              clearInterval(interval);
              endGame("Fin de la canción. Puntuación final: " + score);
          }
      }, 100);
  }

  // Función para iniciar el juego después de cerrar el modal
  window.guardarUsuario = function () {
      const usuario = document.getElementById("usuario").value;
      const idCancion = document.getElementById("id_cancion").value;

      if (usuario) {
          currentUser = usuario; // Almacenar el nombre de usuario
          fetch("guardarUsuario.php", {
              method: "POST",
              headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },
              body: `usuario=${usuario}&id_cancion=${idCancion}&puntuacion=0`, // Inicializa la puntuación en 0
          })
              .then((response) => response.text())
              .then((data) => {
                  if (data === "success") {
                      cerrarModal();
                      loadSongPath().then(songPath => {
                          loadAndPlayAudio(songPath);
                      }); // Iniciar el juego
                  } else {
                      alert("Error al guardar el usuario.");
                  }
              });
      } else {
          alert("Por favor, ingrese un nombre de usuario.");
      }
  };

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
