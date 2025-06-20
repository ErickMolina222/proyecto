<?php
session_start();

if (!isset($_SESSION['id_u'])) {
    http_response_code(401);
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

header("Content-Type: application/json");
require_once('../Config/conexion.php');

$id_usuario = $_SESSION['id_u'];

$sql = "SELECT id_pa, titulo, Estatus, fecha_inicio, fecha_termino, calificacion, urlConsulta FROM productoaca WHERE id_usuario = ? AND borrado = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($productos);
?>
