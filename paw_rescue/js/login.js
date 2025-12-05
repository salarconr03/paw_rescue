const form = document.getElementById("loginForm");

    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const correo = document.getElementById("correo").value;
      const password = document.getElementById("password").value;

      //prueba login
      const usuarioDemo = "1@hotmail.com";
      const passDemo = "1";

      if (correo === usuarioDemo && password === passDemo) {
        // Guardamos usuario en localStorage
        localStorage.setItem("usuarioLogueado", "true");
        localStorage.setItem("correoUsuario", correo);

        alert("Sesión iniciada correctamente");
        window.location.href = "index.html"; // redirigir a inicio
      } else {
        alert("❌ Correo o contraseña incorrectos");
      }
    });