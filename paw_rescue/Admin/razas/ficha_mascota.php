<?php
// --------------------
// CONEXIÓN A BD
// --------------------
include("../config/conexion.php");

// Validar ID
if (!isset($_GET['id'])) {
  echo "Mascota no encontrada";
  exit;
}

$id = $_GET['id'];

// Consulta mascota
$sql = "SELECT * FROM mascotas WHERE id_mascota = $id";
$resultado = pg_query($conexion, $sql);
$mascota = pg_fetch_assoc($resultado);

if (!$mascota) {
  echo "Mascota no encontrada";
  exit;
}

// Consulta historial médico
$sql_medico = "SELECT * FROM historial_medico WHERE id_mascota = $id";
$medico = pg_query($conexion, $sql_medico);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ficha completa - <?php echo $mascota['nombre']; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../index.php">
      <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" width="30">
      Marca
    </a>
  </div>
</nav>

<!-- CONTENIDO -->
<section class="container my-5">

  <!-- TÍTULO -->
  <h2 class="fw-bold mb-4">
    Ficha completa: <?php echo $mascota['nombre']; ?>
  </h2>

  <div class="row">
    <!-- IMAGEN -->
    <div class="col-md-4">
      <img src="../../img/<?php echo $mascota['foto']; ?>" 
           class="img-fluid rounded shadow-sm">
    </div>

    <!-- DATOS GENERALES -->
    <div class="col-md-8">
      <h4>Datos generales</h4>
      <ul class="list-group mb-4">
        <li class="list-group-item"><b>Nombre:</b> <?php echo $mascota['nombre']; ?></li>
        <li class="list-group-item"><b>Edad:</b> <?php echo $mascota['edad']; ?> años</li>
        <li class="list-group-item"><b>Sexo:</b> <?php echo $mascota['sexo']; ?></li>
        <li class="list-group-item"><b>Talla:</b> <?php echo $mascota['talla']; ?></li>
        <li class="list-group-item"><b>Raza:</b> <?php echo $mascota['raza']; ?></li>
        <li class="list-group-item"><b>Especie:</b> <?php echo $mascota['especie']; ?></li>
      </ul>
    </div>
  </div>

  <!-- HISTORIAL MÉDICO -->
  <h4 class="mt-5">Historial médico</h4>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Descripción</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($fila = pg_fetch_assoc($medico)) { ?>
        <tr>
          <td><?php echo $fila['fecha']; ?></td>
          <td><?php echo $fila['tipo']; ?></td>
          <td><?php echo $fila['descripcion']; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- ESTADO MÉDICO -->
  <h4 class="mt-5">Estado médico</h4>
  <ul class="list-group mb-4">
    <li class="list-group-item">
      <b>Esterilizado:</b> <?php echo $mascota['esterilizado']; ?>
    </li>
    <li class="list-group-item">
      <b>Vacunado:</b> <?php echo $mascota['vacunado']; ?>
    </li>
    <li class="list-group-item">
      <b>Alergias:</b> <?php echo $mascota['alergias']; ?>
    </li>
    <li class="list-group-item">
      <b>Enfermedades:</b> <?php echo $mascota['enfermedades']; ?>
    </li>
  </ul>

  <!-- ESTADO DE ADOPCIÓN -->
  <h4>Estado de adopción</h4>
  <p class="alert alert-info">
    <?php echo $mascota['estado_adopcion']; ?>
  </p>

  <!-- BOTONES -->
  <div class="d-flex gap-3 mt-4">
    <a href="form_adopcion.php?id=<?php echo $mascota['id_mascota']; ?>" 
       class="btn btn-success">
       Solicitar adopción
    </a>

    <a href="catalogo.php" class="btn btn-secondary">
      Volver al catálogo
    </a>
  </div>

</section>

<!-- FOOTER -->
<footer class="text-center mt-5 p-3 bg-light">
  MURASAKI 2026 ©
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
