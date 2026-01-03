<?php
session_start();
include("../conexion.php");

/* Validar sesión */
if (!isset($_SESSION['id_usuario'])) {
    header("Location: reporte.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

/* Datos del formulario */
$nombre = $_POST['nombre'] ?? null;
$situacion = $_POST['situacion'] ?? null;
$herido = $_POST['herido'] ?? null;
$descripcion_heridas = $_POST['descripcion_heridas'] ?? null;
$descripcion = $_POST['descripcion'] ?? null;
$ubicacion = $_POST['ubicacion'] ?? null;

/* Manejo de imagen */
$foto = null;

if (!empty($_FILES['foto']['tmp_name'])) {

    // Ruta correcta dentro del proyecto
    $ruta = __DIR__ . "/../imgReportes/";

    if (!is_dir($ruta)) {
        mkdir($ruta, 0755, true);
    }

    // Obtener extensión segura
    $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = uniqid("reporte_") . "." . $extension;

    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta . $foto);
}

/* Insertar en la base de datos */
$sql = "
INSERT INTO paw_rescue.reporte_animal
(id_usuario, nombre, situacion, herido, descripcion_heridas, descripcion, ubicacion, foto)
VALUES ($1,$2,$3,$4,$5,$6,$7,$8)
";

$resultado = pg_query_params($conexion, $sql, [
    $id_usuario,
    $nombre,
    $situacion,
    $herido,
    $descripcion_heridas,
    $descripcion,
    $ubicacion,
    $foto
]);

if (!$resultado) {
    die("Error al guardar reporte: " . pg_last_error($conexion));
}

/* Redirigir */
header("Location: index.php");
exit;
