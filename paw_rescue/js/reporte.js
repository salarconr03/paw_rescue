const usuarioLogueado = true; 
    const btnReporte = document.getElementById("btn-reporte");

    const modalReporte = new bootstrap.Modal(document.getElementById("modalReporte"));
    const modalLogin = new bootstrap.Modal(document.getElementById("modalLogin"));

    btnReporte.addEventListener("click", () => {
      if (usuarioLogueado) {
        modalReporte.show();
      } else {
        modalLogin.show();
      }
    });