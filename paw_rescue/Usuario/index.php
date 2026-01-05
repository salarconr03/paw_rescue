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

  <!-- Hero Section -->
  <section class="hero">
    <div>
      <h1>ADOPTA <br> A TU <br> NUEVO MEJOR <br> AMIGO</h1>
      <a href="adoptar.php" class="btn btn-adoptar btn-lg">ADOPTA AQUÍ</a>
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
