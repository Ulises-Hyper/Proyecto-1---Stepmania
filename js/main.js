function eliminarCancion(id_cancion) {
  if (confirm('¿Estás seguro de que quieres eliminar esta canción?')) {
      // Enviar la solicitud POST al servidor
      fetch('../php/eliminarCancion.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
              'id_cancion': id_cancion
          })
      })
      .then(response => response.text())
      .then(result => {
          console.log(result); // Mostrar el resultado (depuración)
          // Recargar la página después de eliminar la canción o mostrar mensaje
          window.location.reload();
      })
      .catch(error => console.error('Error al eliminar la canción:', error));
  }
}

function editarCancion(id) {
  window.location.href = "editarCancion.php?id=" + id;  // Redirige al formulario de edición
}