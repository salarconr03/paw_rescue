<?php
include("../conexion.php");
header('Content-Type: application/json; charset=utf-8');

$cp = $_GET['cp'] ?? '';

if (!preg_match('/^\d{5}$/', $cp)) {
    echo json_encode(['municipio'=>'','asentamientos'=>[]]);
    exit;
}

$sql = "
  SELECT municipio, asentamiento_id, asentamiento
  FROM paw_rescue.sepomex
  WHERE codigo_postal = $1
";

$res = pg_query_params($conexion, $sql, [$cp]);

$municipio = '';
$asentamientos = [];

while ($row = pg_fetch_assoc($res)) {
    $municipio = $row['municipio'];
    $asentamientos[] = [
        'id' => $row['asentamiento_id'],
        'nombre' => $row['asentamiento']
    ];
}

echo json_encode([
    'municipio' => $municipio,
    'asentamientos' => $asentamientos
]);
