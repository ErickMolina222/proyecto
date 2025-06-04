<?php
session_start();
include '../Config/conexion.php';

// Filtrar por nick si se envía por GET
$filtro_nick = isset($_GET['buscar_nick']) ? $_GET['buscar_nick'] : '';

// Consulta SQL con filtro por nick si se proporciona
$sql = "SELECT id_b, nick, fecha, hora, accion, id_u FROM bitacora";
if ($filtro_nick != '') {
    $sql .= " WHERE nick LIKE ?";
}

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
if ($filtro_nick != '') {
    $like = '%' . $filtro_nick . '%';
    $stmt->bind_param("s", $like);
}
$stmt->execute();
$result = $stmt->get_result();

// Arreglo para almacenar los resultados
$bitacoras = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bitacoras[] = $row;
    }
}

// Registrar acción directamente (sin función)
if (isset($_SESSION['nick']) && isset($_SESSION['id_u'])) {
    $accion = 'CONSULTO BITACORA';
    $fecha = date('Y-m-d');
    $nick = $_SESSION['nick'];
    $id_u = $_SESSION['id_u'];

    $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, CURTIME(), ?, ?)");
    $stmt_log->bind_param("sssi", $nick, $fecha, $accion, $id_u);
    $stmt_log->execute();
    $stmt_log->close();
}

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($bitacoras);
?>
