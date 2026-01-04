<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . "/../conexion.php");
pg_query($conexion, "SET search_path TO paw_rescue");

$id_esp = $_GET['id_esp'] ?? null;

if (!$id_esp) {
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT id_raza, nombre
    FROM paw_rescue.raza
    WHERE id_esp = $1
    ORDER BY nombre
";

$res = pg_query_params($conexion, $sql, [$id_esp]);

$razas = [];
while ($r = pg_fetch_assoc($res)) {
    $razas[] = $r;
}

header('Content-Type: application/json');
echo json_encode($razas);
