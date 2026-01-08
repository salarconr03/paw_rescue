<?php
include("../conexion.php");

$id = $_POST['id_solicitud'] ?? null;
if (!$id) die("Solicitud inválida");

$sql = "
UPDATE paw_rescue.solicitud_adopcion
SET id_estatus = 7
WHERE id_solicitud = $1
";
pg_query_params($conexion, $sql, [$id]);

header("Location: solicitudes.php");
exit;
