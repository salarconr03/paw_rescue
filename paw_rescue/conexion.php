<?php
$host = "127.0.0.1";
$usuario = "root";
$password = "";
$bd = "paw_rescue";
$puerto = 3306

$conexion = new mysqli($host, $usuario, $password, $bd);

/* Validar conexión */
if ($conexion->connect_error) {
    // Mientras no exista la BD, no rompemos el sistema
    $conexion = null;
}
?>
