<?php
session_start();
include("../conexion.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ================= VALIDAR ADMIN ================= */
if (!isset($_SESSION['admin_id'])) {
    die("Acceso no autorizado");
}

/* ================= VALIDAR POST ================= */
if (
    !isset($_POST['id_solicitud']) ||
    !isset($_POST['resultado'])
) {
    die("Datos incompletos");
}

$idSolicitud   = (int)$_POST['id_solicitud'];
$resultado     = $_POST['resultado']; // apto / no_apto
$observaciones = $_POST['observaciones'] ?? null;

/*
ESTATUS:
5 = Periodo de prueba
6 = Firma de adopción
3 = No apto
*/

/* ================= DECISIÓN SIMPLE ================= */
if ($resultado === 'apto') {
    $nuevoEstatus = 6; // pasa a FIRMA
} else {
    $nuevoEstatus = 3; // rechazado
}

/* ================= ACTUALIZAR SOLICITUD ================= */
$sql = "
UPDATE paw_rescue.solicitud_adopcion
SET
    id_estatus = $1,
    observaciones = $2
WHERE id_solicitud = $3
";

$res = pg_query_params($conexion, $sql, [
    $nuevoEstatus,
    $observaciones,
    $idSolicitud
]);

if (!$res) {
    die("Error al actualizar periodo de prueba");
}

/* ================= REDIRECCIÓN ================= */
header("Location: verSolicitud.php?id=".$idSolicitud);
exit;
