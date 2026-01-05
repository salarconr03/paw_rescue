<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!--acerca de -->
  <section class="titulo1">
        <h1>Acerca de nosotros </h1> 
  </section>

   <section class="descripcion">
    <div>
        <h4>
          <p>
            El albergue Paw Rescue nació como una pequeña iniciativa comunitaria en respuesta a la creciente problemática del abandono y maltrato animal en la ciudad. Todo comenzó en 2010, cuando un grupo de voluntarios, movidos por su amor a los animales, empezó a rescatar perros y gatos de la calle, improvisando refugios en patios y casas particulares.
            Con el tiempo, la necesidad se hizo mayor y ese sueño se transformó en un albergue formal. <br> <br>
            Gracias al apoyo de familias solidarias, donadores y médicos veterinarios comprometidos, Paw Rescue pasó de ser un proyecto improvisado a convertirse en una fundación organizada, con programas de rescate, rehabilitación y adopción. 
            Hoy contamos con instalaciones seguras, un equipo profesional y una red de voluntarios que trabajan día a día para devolverles la esperanza a cientos de animales.  <br> <br>
            Nuestra historia está marcada por la empatía y el compromiso: cada mascota rescatada representa una vida transformada y una nueva oportunidad de encontrar un hogar lleno de amor.
            Seguimos creciendo con un mismo propósito: que el abandono animal sea cosa del pasado y que cada rescate se convierta en una historia de unión entre mascotas y familias.
         </p>
         </h4> 
    </div>
  </section>

  <!-- pie pagina -->
  <footer>
    MURASAKI 2026. ©
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Script -->
  <script src="script.js"></script>
</body>
</html>
