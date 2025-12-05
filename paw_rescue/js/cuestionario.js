const usuarioLogueado = localStorage.getItem("usuarioLogueado") === "true";
const formCuestionario = document.getElementById("form-cuestionario");
const modalConfirmacion = new bootstrap.Modal(document.getElementById("modalConfirmacion"));
const modalLogin = new bootstrap.Modal(document.getElementById("modalLogin"));

formCuestionario.addEventListener("submit", (e) => {
  e.preventDefault();
  if (usuarioLogueado) {
    modalConfirmacion.show();
    formCuestionario.reset();
  } else {
    modalLogin.show();
  }
});