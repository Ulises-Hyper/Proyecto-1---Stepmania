document.addEventListener("DOMContentLoaded", function() {
  // Selecciona todos los elementos que contienen el input file y el botón
  const fileContainers = document.querySelectorAll(".custom-file");

  fileContainers.forEach(container => {
    // Selecciona los elementos dentro de cada contenedor
    const realFileBtn = container.querySelector(".real-file");
    const customBtn = container.querySelector(".custom-button");
    const customTxt = container.querySelector(".custom-text");

    // Al hacer clic en el botón personalizado, activa el input file oculto
    customBtn.addEventListener("click", function() {
      realFileBtn.click();  // Simula el clic en el input real
    });

    // Cuando se selecciona un archivo, actualiza el texto del archivo
    realFileBtn.addEventListener("change", function() {
      if (realFileBtn.files.length > 0) {
        customTxt.innerHTML = realFileBtn.files[0].name;
      } else {
        customTxt.innerHTML = "No se ha seleccionado archivo";
      }
    });
  });
});
