<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli("localhost", "root", "", "paw_rescue", 3306);
    echo "CONECTADO CORRECTAMENTE";
} catch (mysqli_sql_exception $e) {
    echo "ERROR REAL: " . $e->getMessage();
}