<?php
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

include(__DIR__ . "/../conexion.php");
pg_query($conexion, "SET search_path TO paw_rescue");

$cp = $_GET['cp'] ?? null;

if (!$cp || strlen($cp) !== 5) {
    echo json_encode([
        "municipio" => "",
        "asentamientos" => []
    ]);
    exit;
}

$sql = "
    SELECT 
        asentamiento_id,
        asentamiento,
        municipio
    FROM sepomex
    WHERE codigo_postal = $1
    ORDER BY asentamiento
    ";

$res = pg_query_params($conexion, $sql, [$cp]);

if (!$res) {
    echo json_encode([
        "municipio" => "",
        "asentamientos" => []
    ]);
    exit;
}

$asentamientos = [];
$municipio = "";

while ($row = pg_fetch_assoc($res)) {
    $municipio = $row['municipio'];
    $asentamientos[] = [
        "id" => $row['asentamiento_id'],
        "nombre" => $row['asentamiento']
    ];
}


echo json_encode([
    "municipio" => $municipio,
    "asentamientos" => $asentamientos
]);
