function eliminarCancion(id_cancion) {
  if (confirm("¿Estás seguro de que quieres eliminar esta canción?")) {
    // Enviar la solicitud POST al servidor
    fetch("../php/eliminarCancion.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        id_cancion: id_cancion,
      }),
    })
      .then((response) => response.text())
      .then((result) => {
        console.log(result);
        // Recargar la página después de eliminar la canción o mostrar mensaje
        window.location.reload();
      })
      .catch((error) => console.error("Error al eliminar la canción:", error));
  }
}

function editarCancion(id) {
  window.location.href = "editarCancion.php?id=" + id; // Redirige al formulario de edición
}

function redirigirAJugar(id) {
  // Redirigir a jugar.php con el ID de la canción
  window.location.href = '../php/jugar.php?id=' + id;
}

// Función para guardar el usuario y puntuación en el JSON
function guardarUsuario() {
  var usuario = document.getElementById('usuario').value;
  var idCancion = document.getElementById('id_cancion').value;

  if (usuario === '') {
    alert('Por favor, ingresa un nombre de usuario');
    return;
  }

  // Usar fetch para enviar datos a PHP
  fetch('../php/guardarUsuario.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'usuario=' + encodeURIComponent(usuario) + '&id_cancion=' + encodeURIComponent(idCancion),
  })
  .then(response => response.text())
  .then(data => {
    if (data.trim() === 'success') {
      // Si se guardó correctamente, cerrar el modal y cargar el juego
      cerrarModal();
      // Aquí puedes agregar lógica para iniciar el juego si es necesario
    } else {
      alert('Error al guardar los datos. Respuesta del servidor: ' + data);
    }
  })
  .catch(error => {
    console.error('Error en la solicitud fetch:', error);
    alert('Error en la solicitud. Verifica tu conexión.');
  });
}