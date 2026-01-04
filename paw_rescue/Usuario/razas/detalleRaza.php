<?php
session_start();
include(__DIR__ . "/../../conexion.php");
pg_query($conexion, "SET search_path TO paw_rescue");

/* ===============================
   PARÁMETROS BASE
================================ */
$id_raza = (int)($_GET['id_raza'] ?? 0);
$especie = pg_escape_string($conexion, $_GET['especie'] ?? '');

if ($id_raza === 0 || $especie === '') {
    die("Parámetros inválidos");
}

/* ===============================
   FILTROS
================================ */
$id_tam     = $_GET['tam'] ?? '';
$id_color   = $_GET['color'] ?? '';
$id_temp    = $_GET['temp'] ?? '';
$id_estatus = $_GET['estatus'] ?? '';

$condiciones = [];
$condiciones[] = "a.id_raza = $id_raza";
$condiciones[] = "e.nombre ILIKE '$especie'";

if ($id_tam !== '') {
    $condiciones[] = "a.id_tam = " . (int)$id_tam;
}
if ($id_color !== '') {
    $condiciones[] = "a.id_color = " . (int)$id_color;
}
if ($id_temp !== '') {
    $condiciones[] = "a.id_temp = " . (int)$id_temp;
}
if ($id_estatus !== '') {
    $condiciones[] = "a.id_estatus = " . (int)$id_estatus;
}

/* ===============================
   CONSULTA PRINCIPAL
================================ */
$sql = "
SELECT
    a.id_animal,
    a.nombre,
    a.edad_aprox,
    a.necesidades_especiales,
    r.nombre AS raza,
    t.nombre AS tamano,
    c.nombre AS color,
    temp.nombre AS temperamento,
    est.nombre AS estatus,
    a.foto
FROM animal a
JOIN raza r ON a.id_raza = r.id_raza
JOIN especie e ON a.id_esp = e.id_esp
LEFT JOIN tam t ON a.id_tam = t.id_tam
LEFT JOIN color c ON a.id_color = c.id_color
LEFT JOIN temperamento temp ON a.id_temp = temp.id_temp
LEFT JOIN estatus_adop est ON a.id_estatus = est.id_estatus
WHERE " . implode(" AND ", $condiciones) . "
ORDER BY a.nombre
";

$resultado = pg_query($conexion, $sql);
if (!$resultado) {
    die(pg_last_error($conexion));
}

/* ===============================
   SELECTS FILTROS
================================ */
$tamanios = pg_query($conexion, "SELECT id_tam, nombre FROM tam ORDER BY nombre");
$colores  = pg_query($conexion, "SELECT id_color, nombre FROM color ORDER BY nombre");
$temps    = pg_query($conexion, "SELECT id_temp, nombre FROM temperamento ORDER BY nombre");
$estatusQ = pg_query($conexion, "SELECT id_estatus, nombre FROM estatus_adop ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mascotas disponibles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- ===== NAVBAR (TU NAVBAR, SIN TOCAR) ===== -->
<?php
$nombreAdmin = $_SESSION['admin_nombre'] ?? '';
?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">
      <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" width="30" class="me-2">
      Paw Rescue
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="info.php">Peticiones</a></li>
        <li class="nav-item"><a class="nav-link" href="adoptar.php">Reportes</a></li>
        <li class="nav-item"><a class="nav-link" href="agregarMascota.php">Agregar mascotas</a></li>
        <li class="nav-item"><a class="nav-link" href="reporte.php">Reportar</a></li>
        <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
      </ul>

      <span class="me-3 fw-semibold">
        admin: <?= htmlspecialchars($nombreAdmin) ?>
      </span>

      <a href="logoutAdmin.php" class="btn btn-outline-danger">
        Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<!-- ===== FILTROS ===== -->
<div class="container my-4">
<form method="GET" class="row g-3">

<input type="hidden" name="id_raza" value="<?= $id_raza ?>">
<input type="hidden" name="especie" value="<?= htmlspecialchars($especie) ?>">

<div class="col-md-2">
<select name="tam" class="form-select">
<option value="">Tamaño</option>
<?php while ($t = pg_fetch_assoc($tamanios)): ?>
<option value="<?= $t['id_tam'] ?>" <?= ($id_tam == $t['id_tam']) ? 'selected' : '' ?>>
<?= $t['nombre'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-2">
<select name="color" class="form-select">
<option value="">Color</option>
<?php while ($c = pg_fetch_assoc($colores)): ?>
<option value="<?= $c['id_color'] ?>" <?= ($id_color == $c['id_color']) ? 'selected' : '' ?>>
<?= $c['nombre'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-2">
<select name="temp" class="form-select">
<option value="">Temperamento</option>
<?php while ($t = pg_fetch_assoc($temps)): ?>
<option value="<?= $t['id_temp'] ?>" <?= ($id_temp == $t['id_temp']) ? 'selected' : '' ?>>
<?= $t['nombre'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-2">
<select name="estatus" class="form-select">
<option value="">Estatus</option>
<?php while ($e = pg_fetch_assoc($estatusQ)): ?>
<option value="<?= $e['id_estatus'] ?>" <?= ($id_estatus == $e['id_estatus']) ? 'selected' : '' ?>>
<?= $e['nombre'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-2">
<button class="btn btn-dark w-100">Filtrar</button>
</div>

</form>
</div>

<!-- ===== RESULTADOS ===== -->
<div class="container">
<div class="row g-4">

<?php while ($a = pg_fetch_assoc($resultado)): ?>

<div class="col-md-3">
<div class="card h-100 shadow-sm">

<img src="<?= $a['foto'] ?: 'https://via.placeholder.com/300x200' ?>"
     class="card-img-top"
     style="height:200px; object-fit:cover;">

<div class="card-body">
<h5><?= htmlspecialchars($a['nombre']) ?></h5>
<p><?= $a['raza'] ?> · <?= $a['tamano'] ?></p>
<p><?= $a['color'] ?> · <?= $a['temperamento'] ?></p>
<span class="badge bg-secondary"><?= $a['estatus'] ?></span>
</div>

</div>
</div>

<?php endwhile; ?>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
