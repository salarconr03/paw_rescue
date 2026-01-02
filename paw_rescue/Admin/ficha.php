<?php
include("../../paw_rescue/conexion.php");
pg_query($conexion, "SET search_path TO paw_rescue");

/* ========= VALIDAR ID ========= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido");
}
$id = (int)$_GET['id'];

/* ========= CONSULTA PRINCIPAL ========= */
$sql = "
SELECT
    a.id_animal,
    a.nombre,
    a.edad_aprox,
    a.tuvo_duenos_anteriores,

    e.nombre AS especie,
    r.nombre AS raza,
    t.nombre AS tam,
    c.nombre AS color,
    ea.nombre AS estado,

    sa.enfermo,
    sa.diagnostico,
    sa.obs AS obs_salud,

    ia.tiene_id,
    ia.codigo,

    re.lugar,
    re.condiciones,

    ea2.nombre AS estatus_adopcion

FROM animal a
LEFT JOIN especie e ON a.id_esp = e.id_esp
LEFT JOIN raza r ON a.id_raza = r.id_raza
LEFT JOIN tam t ON a.id_tam = t.id_tam
LEFT JOIN color c ON a.id_color = c.id_color
LEFT JOIN estado_animal ea ON a.id_estado = ea.id_estado
LEFT JOIN salud_actual sa ON sa.id_animal = a.id_animal
LEFT JOIN ident_animal ia ON ia.id_animal = a.id_animal
LEFT JOIN rescate re ON re.id_animal = a.id_animal
LEFT JOIN estatus_adop ea2 ON a.id_estatus = ea2.id_estatus
WHERE a.id_animal = $id
LIMIT 1
";

$res = pg_query($conexion, $sql);
if (!$res || pg_num_rows($res) === 0) {
    die("Mascota no encontrada");
}
$m = pg_fetch_assoc($res);

/* ========= NORMALIZAR ========= */
$enfermo = ($m['enfermo'] === 't');
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Expediente - <?= htmlspecialchars($m['nombre']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
  <!-- Navbar -->
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


<body class="bg-light">

<div class="container my-5">

<h2 class="mb-4">📋 Expediente de Mascota Rescatada</h2>

<!-- ========= DATOS PERSONALES ========= -->
<h4>Datos personales</h4>
<ul>
  <li><b>Nombre:</b> <?= $m['nombre'] ?></li>
  <li><b>Especie:</b> <?= $m['especie'] ?></li>
  <li><b>Raza:</b> <?= $m['raza'] ?: 'No especificada' ?></li>
  <li><b>Tamaño:</b> <?= $m['tam'] ?></li>
  <li><b>Color:</b> <?= $m['color'] ?></li>
  <li><b>Edad aproximada:</b> <?= $m['edad_aprox'] ?> años</li>
  <li><b>Estado actual:</b> <?= $m['estado'] ?></li>
</ul>

<hr>

<!-- ========= DUEÑOS ANTERIORES ========= -->
<h4>Dueños anteriores</h4>
<p><?= $m['tuvo_duenos_anteriores'] ?: 'No registrado' ?></p>

<hr>

<!-- ========= RESCATE ========= -->
<h4>Situación del rescate</h4>
<ul>
  <li><b>Lugar:</b> <?= $m['lugar'] ?: 'No registrado' ?></li>
  <li><b>Condiciones:</b> <?= $m['condiciones'] ?: 'Sin información' ?></li>
</ul>

<hr>

<!-- ========= ADOPCIÓN ========= -->
<h4>Adopción</h4>
<p><b>Estatus:</b> <?= $m['estatus_adopcion'] ?: 'No adoptado' ?></p>

<hr>

<!-- ========= SALUD ========= -->
<h4>Salud</h4>
<ul>
  <li><b>Estado:</b> <?= is_null($m['enfermo']) ? 'No registrado' : ($enfermo ? 'Enfermo' : 'Sano') ?></li>
  <li><b>Diagnóstico:</b> <?= $m['diagnostico'] ?: 'No aplica' ?></li>
  <li><b>Observaciones:</b> <?= $m['obs_salud'] ?: 'Sin observaciones' ?></li>
</ul>

<hr>

<!-- ========= ENFERMEDADES ========= -->
<h4>Enfermedades</h4>
<ul>
<?php
$enf = pg_query($conexion, "
SELECT e.nombre, ea.fecha
FROM enf_animal ea
JOIN enfermedad e ON ea.id_enf = e.id_enf
WHERE ea.id_animal = $id
");

if (pg_num_rows($enf) === 0) {
    echo "<li>No registra enfermedades</li>";
}
while ($row = pg_fetch_assoc($enf)) {
    echo "<li>{$row['nombre']} ({$row['fecha']})</li>";
}
?>
</ul>

<hr>

<!-- ========= DESPARASITACIÓN / VACUNAS ========= -->
<h4>Vacunas y desparasitación</h4>
<ul>
<?php
$vac = pg_query($conexion, "
SELECT v.nombre, hv.fecha_ap
FROM hist_vac hv
JOIN vacuna v ON hv.id_vac = v.id_vac
WHERE hv.id_animal = $id
");

if (pg_num_rows($vac) === 0) {
    echo "<li>No hay registros</li>";
}
while ($v = pg_fetch_assoc($vac)) {
    echo "<li>{$v['nombre']} ({$v['fecha_ap']})</li>";
}
?>
</ul>

<hr>

<!-- ========= CURACIONES ========= -->
<h4>Curaciones y tratamientos</h4>
<ul>
<?php
$trat = pg_query($conexion, "
SELECT t.medicamento, t.dosis, t.frecuencia, t.duracion
FROM tratamiento t
JOIN consulta c ON t.id_cons = c.id_cons
WHERE c.id_animal = $id
");

if (pg_num_rows($trat) === 0) {
    echo "<li>No hay tratamientos registrados</li>";
}
while ($t = pg_fetch_assoc($trat)) {
    echo "<li>{$t['medicamento']} - Dosis: {$t['dosis']}</li>";
}
?>
</ul>

<hr>

<!-- ========= CUIDADOS EN ALBERGUE ========= -->
<h4>Cuidados en el albergue</h4>
<ul>
<?php
$cui = pg_query($conexion, "
SELECT tipo_cuidado, frecuencia, observaciones
FROM cuidado_albergue
WHERE id_animal = $id
");

if (pg_num_rows($cui) === 0) {
    echo "<li>No hay cuidados registrados</li>";
}
while ($c = pg_fetch_assoc($cui)) {
    echo "<li>{$c['tipo_cuidado']} ({$c['frecuencia']}) - {$c['observaciones']}</li>";
}
?>
</ul>

<hr>

<!-- ========= IDENTIFICACIÓN ========= -->
<h4>Identificación</h4>
<ul>
  <li><b>Chip:</b> <?= is_null($m['tiene_id']) ? 'No registrado' : ($m['tiene_id'] ? 'Sí' : 'No') ?></li>
  <?php if ($m['tiene_id']) { ?>
    <li><b>Código:</b> <?= htmlspecialchars($m['codigo']) ?></li>
  <?php } ?>
</ul>

</div>

<footer class="text-center py-3 bg-white shadow-sm">
  Paw Rescue © 2026
</footer>

</body>
</html>
