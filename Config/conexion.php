<?php
require_once('conf.inc');

$conn = new mysqli(SERVER, DB_USER, DB_PASS, DB);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

//echo "Conexión exitosa";
?>
