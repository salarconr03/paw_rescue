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
    a.necesidades_especiales,

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
    re.fecha AS fecha_rescate,

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
WHERE a.id_animal = $1
LIMIT 1
";

$res = pg_query_params($conexion, $sql, [$id]);

if (!$res || pg_num_rows($res) === 0) {
    die("Mascota no encontrada");
}

$m = pg_fetch_assoc($res);

/* ========= NORMALIZAR BOOLEANOS ========= */
$enfermo = ($m['enfermo'] === 't');
$requiere_cuidados = ($m['necesidades_especiales'] === 't');
$tuvo_duenos = ($m['tuvo_duenos_anteriores'] === 't');
$tiene_id = ($m['tiene_id'] === 't');
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Expediente - <?= htmlspecialchars($m['nombre']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php include("navbar.php"); ?>

<div class="container my-4">
<div class="card shadow p-4">

<h3 class="mb-4">Expediente de <?= htmlspecialchars($m['nombre']) ?></h3>

<!-- ========= DATOS GENERALES ========= -->
<h5>Datos generales</h5>
<ul>
  <li><b>Especie:</b> <?= htmlspecialchars($m['especie']) ?></li>
  <li><b>Raza:</b> <?= $m['raza'] ? htmlspecialchars($m['raza']) : 'No especificada' ?></li>
  <li><b>Tamaño:</b> <?= htmlspecialchars($m['tam']) ?></li>
  <li><b>Color:</b> <?= htmlspecialchars($m['color']) ?></li>
  <li><b>Edad aproximada:</b> <?= $m['edad_aprox'] ?? 'No registrada' ?> años</li>
  <li><b>Estado actual:</b> <?= htmlspecialchars($m['estado']) ?></li>
</ul>

<hr>

<!-- ========= ORIGEN ========= -->
<h5>Origen</h5>
<p>
<?= $tuvo_duenos ? 'Retirada a una persona' : 'Rescate en la calle' ?>
</p>

<hr>

<!-- ========= RESCATE ========= -->
<h5>Rescate</h5>
<ul>
  <li><b>Día de rescate:</b> <?= $m['fecha_rescate'] ?: 'No registrado' ?></li>
  <li><b>Lugar:</b> <?= htmlspecialchars($m['lugar']) ?: 'No registrado' ?></li>
</ul>

<hr>

<!-- ========= CUIDADOS ESPECIALES ========= -->
<h5>Cuidados especiales</h5>
<ul>
<?php if (!$requiere_cuidados): ?>
    <li>No requiere cuidados especiales</li>
<?php else: ?>
<?php
$cuidados = pg_query_params($conexion, "
    SELECT tc.nombre, ace.observaciones
    FROM animal_cuidado_especial ace
    JOIN tipo_cuidado_especial tc ON ace.id_cuidado = tc.id_cuidado
    WHERE ace.id_animal = $1
", [$id]);

if (pg_num_rows($cuidados) === 0) {
    echo "<li>Requiere cuidados especiales (sin detalle)</li>";
}

while ($c = pg_fetch_assoc($cuidados)) {
    echo "<li><b>" . htmlspecialchars($c['nombre']) . ":</b> " .
         htmlspecialchars($c['observaciones']) . "</li>";
}
?>
<?php endif; ?>
</ul>

<hr>

<!-- ========= SALUD ========= -->
<h5>Salud</h5>
<ul>
  <li><b>Estado:</b>
    <?= is_null($m['enfermo']) ? 'No registrado' : ($enfermo ? 'Enfermo' : 'Sano') ?>
  </li>
  <li><b>Diagnóstico:</b> <?= htmlspecialchars($m['diagnostico']) ?: 'No aplica' ?></li>
  <li><b>Observaciones:</b> <?= htmlspecialchars($m['obs_salud']) ?: 'Sin observaciones' ?></li>
</ul>

<hr>

<!-- ========= ENFERMEDADES ========= -->
<h5>Enfermedades</h5>
<ul>
<?php
$enf = pg_query_params($conexion, "
    SELECT e.nombre, ea.fecha
    FROM enf_animal ea
    JOIN enfermedad e ON ea.id_enf = e.id_enf
    WHERE ea.id_animal = $1
", [$id]);

if (pg_num_rows($enf) === 0) {
    echo "<li>No registra enfermedades</li>";
}

while ($row = pg_fetch_assoc($enf)) {
    echo "<li>" . htmlspecialchars($row['nombre']) .
         " (" . $row['fecha'] . ")</li>";
}
?>
</ul>

<hr>

<!-- ========= VACUNAS ========= -->
<h5>Vacunas</h5>
<ul>
<?php
$vac = pg_query_params($conexion, "
    SELECT v.nombre, hv.fecha_ap
    FROM hist_vac hv
    JOIN vacuna v ON hv.id_vac = v.id_vac
    WHERE hv.id_animal = $1
", [$id]);

if (pg_num_rows($vac) === 0) {
    echo "<li>No hay registros</li>";
}

while ($v = pg_fetch_assoc($vac)) {
    echo "<li>" . htmlspecialchars($v['nombre']) .
         " (" . $v['fecha_ap'] . ")</li>";
}
?>
</ul>

<hr>

<!-- ========= IDENTIFICACIÓN ========= -->
<h5>Identificación</h5>
<ul>
  <li><b>Chip:</b> <?= is_null($m['tiene_id']) ? 'No registrado' : ($tiene_id ? 'Sí' : 'No') ?></li>
  <?php if ($tiene_id): ?>
    <li><b>Código:</b> <?= htmlspecialchars($m['codigo']) ?></li>
  <?php endif; ?>
</ul>

</div>
</div>

<footer class="text-center py-3 bg-white shadow-sm">
Paw Rescue © 2026
</footer>

</body>
</html>
