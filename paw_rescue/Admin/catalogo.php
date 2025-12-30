<?php
include("../../paw_rescue/conexion.php");

/* ================= FILTROS ================= */
$where = " WHERE ea.nombre = 'Disponible' ";

if (!empty($_GET['especie'])) {
  $esp = pg_escape_string($conexion, $_GET['especie']);
  $where .= " AND e.nombre = '$esp'";
}

if (!empty($_GET['raza'])) {
  $raza = pg_escape_string($conexion, $_GET['raza']);
  $where .= " AND r.nombre ILIKE '%$raza%'";
}

if (!empty($_GET['tamanio'])) {
  $where .= " AND t.id_tam = " . (int)$_GET['tamanio'];
}

if (!empty($_GET['color'])) {
  $where .= " AND c.id_color = " . (int)$_GET['color'];
}

if (!empty($_GET['temperamento'])) {
  $where .= " AND a.id_temp = " . (int)$_GET['temperamento'];
}

/* ================= QUERY BASE ================= */
function obtenerMascotas($conexion, $extra = "") {
  $sql = "
  SELECT DISTINCT
    a.id_animal,
    a.nombre,
    a.edad_aprox,
    img.url AS imagen,
    e.nombre AS especie,
    r.nombre AS raza,
    t.nombre AS tamanio,
    c.nombre AS color,
    ea.nombre AS estado,
    CASE 
      WHEN EXISTS (
        SELECT 1 FROM paw_rescue.hist_vac hv 
        WHERE hv.id_animal = a.id_animal
      ) THEN 'Sí'
      ELSE 'No'
    END AS vacunado
  FROM paw_rescue.animal a
  JOIN paw_rescue.especie e ON a.id_esp = e.id_esp
  JOIN paw_rescue.raza r ON a.id_raza = r.id_raza
  JOIN paw_rescue.tam t ON a.id_tam = t.id_tam
  JOIN paw_rescue.color c ON a.id_color = c.id_color
  JOIN paw_rescue.estado_animal ea ON a.id_estado = ea.id_estado
  LEFT JOIN paw_rescue.img_animal_principal img 
       ON img.id_animal = a.id_animal
  $extra
  ORDER BY a.id_animal
  ";
  return pg_query($conexion, $sql);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="index.php">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="logo" width="30" class="me-2">
        Marca
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="info.php">Peticiones</a></li>
          <li class="nav-item"><a class="nav-link" href="adoptar.php">Reportes</a></li>
          <li class="nav-item"><a class="nav-link" href="agregar_mascota.php">Agregar mascotas</a></li>
          <li class="nav-item"><a class="nav-link" href="reporte.php">Reportar</a></li>
          <li class="nav-item"><a class="nav-link" href="catalogo.php">Catalogo</a></li>
        </ul>
        <a href="login.php" class="btn btn-outline-dark ms-3">Login</a>
      </div>
    </div>
  </nav>

<section class="aviso">
  <h2>Mascotas disponibles</h2>
</section>

<div class="container my-5">

<!-- ================= FILTROS ================= -->
<form method="GET" class="row g-3 mb-5">

  <div class="col-md-2">
    <select name="especie" class="form-select">
      <option value="">Especie</option>
      <option value="Perro">Perro</option>
      <option value="Gato">Gato</option>
    </select>
  </div>

  <div class="col-md-2">
    <input type="text" name="raza" class="form-control" placeholder="Raza">
  </div>

  <div class="col-md-2">
    <select name="tam" class="form-select">
      <option value="">Tamaño</option>
      <?php
      $t = pg_query($conexion, "SELECT id_tam, nombre FROM paw_rescue.tam");
      while ($row = pg_fetch_assoc($t))
        echo "<option value='{$row['idtamanio']}'>{$row['descripcion']}</option>";
      ?>
    </select>
  </div>

  <div class="col-md-2">
    <select name="color" class="form-select">
      <option value="">Color</option>
      <?php
      $c = pg_query($conexion, "SELECT id_color, nombre FROM paw_rescue.color");
      while ($row = pg_fetch_assoc($c))
        echo "<option value='{$row['idcolor']}'>{$row['nombre']}</option>";
      ?>
    </select>
  </div>

  <div class="col-md-2">
  <select name="temperamento" class="form-select">
    <option value="">Temperamento</option>
    <?php
   $temp = pg_query($conexion, "SELECT id_temp, nombre FROM paw_rescue.temperamento");
    while ($row = pg_fetch_assoc($temp)) {
      echo "<option value='{$row['idtemperamento']}'>{$row['descripcion']}</option>";
    }
    ?>
  </select>
</div>


  <div class="col-md-2">
    <button class="btn btn-dark w-100">Buscar</button>
  </div>

</form>

<!-- ================= RESULTADOS ================= -->
<div class="row g-4">

<?php
$result = obtenerMascotas($conexion, $where);
while ($m = pg_fetch_assoc($result)) {
?>

<div class="col-md-3">
  <div class="card h-100 shadow-sm">
    <img src="<?= $m['imagen'] ?>" class="card-img-top">

    <div class="card-body">
      <h5><?= $m['nombre'] ?></h5>
      <p class="mb-1"><b><?= $m['especie'] ?></b> · <?= $m['raza'] ?></p>
      <p class="mb-1">Edad: <?= $m['edad_aprox'] ?> años</p>
      <p class="mb-1">Tamaño: <?= $m['tam'] ?></p>
      <p class="mb-1">Color: <?= $m['color'] ?></p>
      <p class="mb-1">Vacunado: <?= $m['vacunado'] ?></p>
      <p class="mb-1">Atención: <?= $m['demanda'] ?></p>

      <span class="badge bg-success"><?= $m['estado'] ?></span>

      <a href="ficha_mascota.php?id=<?= $m['idanimal'] ?>"
         class="btn btn-dark w-100 mt-3">Ficha</a>
      <div class="d-flex gap-2 mt-2">
     <a href="editar_mascota.php?id=<?= $m['idanimal'] ?>"
     class="btn btn-warning w-50">Editar</a>

     <a href="eliminar_mascota.php?id=<?= $m['idanimal'] ?>"
     class="btn btn-danger w-50"
     onclick="return confirm('¿Seguro que deseas eliminar esta mascota?');">
     Eliminar
  </a>
</div>
    </div>
  </div>
</div>

<?php } ?>

</div>
</div>

<footer class="text-center py-3">
  Paw Rescue © 2026
</footer>

</body>
</html>
