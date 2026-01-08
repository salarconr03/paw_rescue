<?php
include("../conexion.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ================= VALIDAR ID ================= */
if (!isset($_GET['id'])) {
    die("Solicitud no válida");
}
$idSolicitud = (int)$_GET['id'];

/* ================= GUARDAR ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $inicio_fecha = $_POST['inicio_fecha'];
    $inicio_hora  = $_POST['inicio_hora'];

    /* === CALCULAR FIN (+15 DIAS) === */
    $inicio = new DateTime($inicio_fecha . ' ' . $inicio_hora);
    $fin = clone $inicio;
    $fin->modify('+15 days');

    $fin_fecha = $fin->format('Y-m-d');
    $fin_hora  = $fin->format('H:i');

    /* === INICIO PERIODO === */
    $sqlInicio = "
    INSERT INTO paw_rescue.cita_adopcion
    (id_solicitud, id_tipo, fecha, hora, id_estatus)
    VALUES ($1, 3, $2, $3, 1)
    ";
    pg_query_params($conexion, $sqlInicio, [
        $idSolicitud,
        $inicio_fecha,
        $inicio_hora
    ]);

    /* === FIN PERIODO === */
    $sqlFin = "
    INSERT INTO paw_rescue.cita_adopcion
    (id_solicitud, id_tipo, fecha, hora, id_estatus)
    VALUES ($1, 4, $2, $3, 1)
    ";
    pg_query_params($conexion, $sqlFin, [
        $idSolicitud,
        $fin_fecha,
        $fin_hora
    ]);
    /* === VISITA DE SEGUIMIENTO (+7 DIAS) === */
        $visita = clone $inicio;
        $visita->modify('+7 days');

        $visita_fecha = $visita->format('Y-m-d');
        $visita_hora  = $inicio->format('H:i');

        /* === VISITA DE SEGUIMIENTO === */
    $sqlVisita = "
    INSERT INTO paw_rescue.cita_adopcion
    (id_solicitud, id_tipo, fecha, hora, id_estatus)
    VALUES ($1, 5, $2, $3, 1)
    ";
    pg_query_params($conexion, $sqlVisita, [
        $idSolicitud,
        $visita_fecha,
        $visita_hora
    ]);



    

    header("Location: verSolicitud.php?id=$idSolicitud");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Programar periodo de prueba</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include("navbar.php"); ?>

<div class="container mt-4">

<h3>Programar periodo de prueba (15 días)</h3>

<form method="POST">

<div class="row">
<div class="col-md-6">
<label class="form-label">Fecha inicio</label>
<input type="date" name="inicio_fecha" class="form-control" required>
</div>

<div class="col-md-6">
<label class="form-label">Hora inicio</label>
<input type="time" name="inicio_hora" class="form-control" required>
</div>
</div>

<div class="alert alert-info mt-3">
📅 El periodo de prueba finalizará automáticamente <b>15 días después</b>.
</div>

<button class="btn btn-success mt-3">
Guardar periodo de prueba
</button>

<a href="verSolicitud.php?id=<?= $idSolicitud ?>" class="btn btn-secondary mt-3">
Cancelar
</a>

</form>

</div>
</body>
</html>
